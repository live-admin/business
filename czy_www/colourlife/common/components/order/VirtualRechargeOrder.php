<?php

/**
 * 充值订单处理
 * @property OthersFees $order
 * Created by PhpStorm.
 * User: sunny
 * Date: 14-3-24
 * Time: 下午3:49
 */
class VirtualRechargeOrder extends OrderFactory
{

    public function init($order_id, $payment_id = 0, $note = '')
    {
        $this->payment_id = $payment_id;
        $this->note = $note;
        $this->switch = @Yii::app()->config->SwitchTelRechargeRedPacket; //红包开关
        $this->order = OthersFees::model()->findByPk($order_id);
        $this->order_id = $order_id;
        $this->order_sn = $this->order->sn;
        $this->model = SN::findModelBySN($this->order_sn);
        //$this->balance = empty($this->order->customer) ? 0 : $this->order->customer->getBalance();
        $this->balance = $this->order->customer->getBalance();
        $this->setProfits();
    }

    //发送充值成功短信
    private function sendSms()
    {
        Yii::app()->sms->sendRechargeMessage('paymentSuccess', $this->order_sn);
    }

    /**
     * 修改付款状态
     * @return bool
     */
    private function updatePayStatus()
    {
        $this->order = OthersFees::model()->findByPk($this->order_id);
        $this->status = Item::FEES_RECHARGEING;
        $this->order->status = $this->status;
        $this->order->pay_time = time();
        $this->order->payment_id = $this->payment_id;
        $this->note = empty($this->note) ? '正在已付款充值中' : $this->note;
        if ($this->order->update() && OthersFeesLog::createOtherFeesLog($this->order_id, $this->model, $this->status, $this->note,$this->order->customer_id)) {
            $this->sendGoods();
            try {
                $this->sendSms();//以送短信
                //$this->active();//活动r);//付款成功积分
            } catch (Exception $e) {
                Yii::log("记录充值订单付款记录付款流水号'{$this->order_sn}'以送短信异常", CLogger::LEVEL_ERROR, 'colourlife.core.VirtualRechargeOrder');
            }
            try {//送活动
                $order = OthersFees::model()->findByPk($this->order_id);
                LuckyDoAdd::orderFrees($order->customer_id, $order->customerName, $this->order_id);
            } catch (Exception $e) {
                Yii::log("记录充值订单付款记录付款流水号'{$this->order_sn}'充值送抽奖机会异常", CLogger::LEVEL_ERROR, 'colourlife.core.lucky');
            }
            return true;
        }
        return false;
    }

    //发货虚拟商品
    private function sendGoods()
    {
        $order = OthersVirtualRecharge::model()->findByPk($this->order_id);
        OthersVirtualRecharge::onlineOrder($order->cardId, $order->cardNum, $order->sn,
            $order->GameUserId, $order->GameArea, $order->GameSrv, 'Customer');
    }

    //使用红包业务
    public function orderProcessing()
    {
        if ($this->order->red_packet_pay > 0 || (isset($this->order->pay_info) && !empty($this->order->pay_info))) {//使用红包抵扣
            if (!$this->switch) {//开关关掉直接返回失败
                $this->balanceFailed();
                return false;
            }

            if ($this->order->red_packet_pay <= $this->balance) {//余额可以支付
                $redPacketModel = new RedPacket();
                $attr = array();
                $attr['customer_id'] = $this->order->customer_id;
                $attr['to_type'] = Item::RED_PACKET_TO_TYPE_VIRTUALRECHARGE_PAYMENT;
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

        } else//正常更新流程
            return $this->updatePayStatus();
    }

    private function balanceFailed()
    {
        /* 修改订单状态为支付失败，红包余额不足，不修改红包是否使用的状态*/
        OthersFees::model()->updateByPk($this->order_id, array('status' => Item::ORDER_BALANCE_DEDUCT_FAILED));

        /*插入订单付款记录*/
        if (OthersFeesLog::createOtherFeesLog($this->order_id, $this->model, Item::ORDER_AWAITING_GOODS, '银行支付成功，红包扣款失败',$this->order->customer_id))
            Yii::log("记录充值订单付款记录付款流水号'{$this->order_sn}' 银行支付成功，红包扣款失败。", CLogger::LEVEL_INFO, 'colourlife.core.PayLib.VirtualRechargeOrder');

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