<?php

/**
 * 红包充值支付单回调
 * Created by PhpStorm.
 * User: wenda
 * Update: 2015-06-03
 * Time: 下午3:49
 * @property RedpacketFees $order
 */
class RedpacketFeesOrder extends OrderFactory
{
	/**
	 * 初始化方法对象
	 * @param $order_id
	 * @param int $payment_id
	 * @param string $note
	 */
	public function init($order_id, $payment_id = 0, $note = '')
	{
		$this->payment_id = $payment_id;
		$this->note = $note;
		// $this->switch = @Yii::app()->config->SwitchThirdRedPacket; //商品开关
		$this->order = RedpacketFees::model()->findByPk($order_id);
		$this->order_id = $order_id;
		$this->order_sn = $this->order->sn;
		$this->model = SN::findModelBySN($this->order_sn);
		//$this->balance = empty($this->order->customer) ? 0 : $this->order->customer->getBalance();
		$this->balance = $this->order->customer->getBalance();
		$this->setProfits();
	}

	/**
	 * 修改付款状态
	 * @return bool
	 */
	private function updatePayStatus()
	{
		$this->order = RedpacketFees::model()->findByPk($this->order_id);
		$this->status = Item::ORDER_AWAITING_GOODS;
		$this->order->status = $this->status;
		$this->order->pay_time = time();
		$this->order->payment_id = $this->payment_id;
		// $this->note = empty($this->note) ? '状态:已付款。操作:在线支付。' : $this->note;
		if (empty($this->note)) {
			if ($this->payment_id == Item::POS_PAYMENT_STATUS) {
				$note = '状态:已付款。操作:pos支付。';
			} else {
				$note = '状态:已付款。操作:在线支付。';
			}
		} else {
			$note = $this->note;
		}
		$this->order->note .= ',' . $note;
		if ($this->order->update()) {
			//成功记录日志
			RedpacketFeesLog::createOtherFeesLog($this->order_id, $this->order->model, $this->status, $this->note, $this->order->customer_id);
			Yii::log("记录红包充值订单付款流水号'{$this->order_sn}' 状态:已付款。操作:在线支付。。", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');

			$this->sendSms();//发短信已异常处理
			//增加红包
			$this->optBalance();
			return true;
		}
		return false;
	}

	/*
	 * 增加红包
	 */
	private function OldoptBalance()
	{
		$items = array(
			'customer_id' => $this->order->customer_id,//业主的ID
			'sum' => $this->order->amount,//红包金额
			'sn' => 'redpacketFees_pay',
			'from_type' => Item::RED_PACKET_FROM_TYPE_REDPACKETFEES,
		);
		$redPacked = new RedPacket();
		if (!$redPacked->addRedPacker($items)) {
			Yii::log("红包充值增加红包失败'{$this->order->sn}'", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');
		}
	}


	/*
	 * 增加红包
	 */
	private function optBalance()
	{
		/*$items = array(
			'customer_id' => $this->order->customer_id,//业主的ID
			'sum' => $this->order->amount,//红包金额
			'sn' => 'redpacketFees_pay',
			'from_type' => Item::RED_PACKET_FROM_TYPE_REDPACKETFEES,
			'remark' => $this->order->sn,
		);*/

		$items = array(
			'customer_id' => $this->order->customer_id,//业主的ID
			'sum' => $this->order->amount,//红包金额
			'remark' => 'redpacketFees_pay',
			'from_type' => Item::RED_PACKET_FROM_TYPE_REDPACKETFEES,
			'sn' => $this->order->sn,
		);
		$redPacked = new RedPacket();
		if (!$redPacked->addRedPacker($items)) {
			Yii::log("红包充值增加红包失败'{$this->order->sn}'", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');
		} else {
			// 关闭红包充值送2元活动 2015-10-28
			if (0) { //($this->order->amount >= 100){
				// 体验小区不参与充值送
				$customer = Yii::app()->db->createCommand()
					->from('customer')
					->join('community', 'community.id=customer.community_id')
					->where('customer.id=:id AND community.region_id <> 3294', array(':id' => $this->order->customer_id))
					->queryRow();
				if ($customer) {
					$re = RedPacket::model()->find('sn=:sn and customer_id=:uid and remark=:remark', array(':sn' => 'redPacket_fees_activity_', ':uid' => $this->order->customer_id, ':remark' => $this->order->sn));
					if (!$re) {
						$amount = floor($this->order->amount / 50);
						//感恩回馈充饭票满100送2元
						$items_other = array(
							'customer_id' => $this->order->customer_id,//业主的ID
							'sum' => $amount,//饭票金额
							'sn' => 'redPacket_fees_activity_',
							'from_type' => Item::RED_PACKET_FROM_TYPE_REDPACKET_FEES_ACTIVITY,
							'remark' => $this->order->sn,
						);
						if ($redPacked->addRedPacker($items_other)) {
							Yii::log("感恩回馈，充饭票满100送2元，订单{$this->order->sn}赠送红包{$amount}元 活动成功,用户ID:{$this->order->customer_id}，送活动额度：{$amount}", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');
						} else {
							Yii::log("感恩回馈，充饭票满100送2元，订单{$this->order->sn}赠送红包{$amount}元 活动失败,用户ID:{$this->order->customer_id}，送活动额度：{$amount}", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');
						}

					}
				}
			}
		}
	}

	/**
	 * 修改红包余额不足状态
	 * @return bool
	 */
	private function balanceFailed()
	{
		$this->status = Item::ORDER_BALANCE_DEDUCT_FAILED;
		$this->order->status = $this->status;
		/*插入订单付款记录*/
		if ($this->order->update() && RedpacketFeesLog::createOrderLog($this->order_id, $this->model, $this->status, '银行支付成功，红包扣款失败')) {
			Yii::log("记录红包充值订单付款 订单流水号'{$this->order_sn}' 银行支付成功，红包扣款失败。", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');
			return true;
		}
		return false;
	}

	//红包充值  发送短信
	private function sendSms()
	{
		try {
			Yii::app()->sms->sendRedpacketFeesPaymentMessage('successfulPayment', $this->order_sn);
		} catch (Exception $e) {
			Yii::log("记录红包充值订单付款流水号'{$this->order_sn}' 状态:已付款。发短信异常。", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');
		}
	}

	//订单处理
	public function orderProcessing()
	{
		if ($this->order->red_packet_pay > 0 || (isset($this->order->pay_info) && !empty($this->order->pay_info))) { //使用红包抵扣
//            if (!$this->switch) { //开关关掉直接返回失败
//                $this->balanceFailed();
//                return null;
//            }
			if ($this->order->red_packet_pay <= $this->balance) { //余额可以支付
				$redPacketModel = new RedPacket();
				$attr = array();
				$attr['customer_id'] = $this->order->customer_id;
				$attr['to_type'] = 17;
				$attr['sum'] = $this->order->red_packet_pay;
				$attr['sn'] = $this->order->sn;
				//2017-04-14为支持地方饭票加入start
				if(isset($this->order->pay_info) && !empty($this->order->pay_info)){
					Yii::log("地方饭票支付。sn:".$this->order->sn, CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder.LocalRedPacketPay');
					$attr['pay_info'] = $this->order->pay_info;
				}
				//2017-04-14为支持地方饭票加入end
				if ($redPacketModel->consumeRedPacker($attr))
					return $this->updatePayStatus();
				else{
                    $this->balanceFailed();
                    return false;
                }
			} else{
                $this->balanceFailed();
                return false;
            }

		} else //正常更新流程
			return $this->updatePayStatus();
	}

	protected function setProfits()
	{
		/**
		 * @var OrderGoodsRelation $relation
		 * @var Payment $payment
		 */
		if ($this->payment_id > 0) {
			$payment = Payment::model()->findByPk($this->payment_id);
			if ($payment) {
				$this->order->pay_rate = $payment->rate;
			}
		}

	}
}