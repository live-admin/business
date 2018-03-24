<?php

/**
 * 第三方支付单回调
 * Created by PhpStorm.
 * User: wenda
 * Update: 2015-04-29
 * Time: 下午3:49
 * @property ThirdFees $order
 */
class ThirdOrder extends OrderFactory
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
        $this->order = ThirdFees::model()->findByPk($order_id);
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
    public function updatePayStatus()
    {
    	Yii::log("updatePayStatus操作开始", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');
        //$this->order = ThirdFees::model()->findByPk($this->order_id);
        $this->status = Item::ORDER_AWAITING_GOODS;
        $this->order->status = $this->status;
        $this->order->pay_time = time();
        // $this->order->payment_id = $this->payment_id;
        // $this->note = empty($this->note) ? '状态:已付款。操作:在线支付。' : $this->note;
        if(empty($this->note)){
            if($this->payment_id==Item::POS_PAYMENT_STATUS){
                $note = '状态:已付款。操作:pos支付。';
            }else{
                $note = '状态:已付款。操作:在线支付。';
            }
        }else{
            $note = $this->note;
        }
        $this->order->note .= ',' . $note;
        if ($this->order->update()) {
            $this->sendSms();//发短信已异常处理
            //成功记录日志
            ThirdFeesLog::createOtherFeesLog($this->order_id, $this->order->model, $this->status, $this->note,$this->order->customer_id);
            Yii::log("记录第三方订单付款流水号'{$this->order_sn}' 状态:已付款。操作:在线支付。。", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');

            $table = "red_packet";
            if (!empty($this->order->payment_info)){
            	$pinfo = json_decode($this->order->payment_info, true);
            	if (isset($pinfo['is_quanguo']) && $pinfo['is_quanguo'] == 0){
            		$table = "local_red_packet";
            	}
            }
            
            // 更新流水状态值
            Yii::app()->db->createCommand()->update($table, array('state'=>1), "customer_id=:customer_id AND sn=:sn", array(':customer_id'=>$this->order->customer_id, ':sn'=>$this->order->sn));


            if ('thirdFreesScan' == $this->order->model) {

            	Yii::log("thirdFreesScan调用微商圈回调'{$this->order_sn}'。", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');
                $customerModel = Customer::model()->findByPk($this->order->customer_id);

                $payment = json_decode($this->order->payment_info, true);

                //获取实际交易金额
                $payamount = bcadd ($this->order->bank_pay, $this->order->red_packet_pay, 2);

                // 扫码支付订单
                Yii::import('common.services.PayService');
                $payService = new PayService();

                Yii::log("thirdFreesScan调用微商圈回调开始'{$this->order->cSn}'。", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder222');
                #################区分新老微商圈---开始################
                if(empty($this->order->cSn)){
                    Yii::log("thirdFreesScan1调用微商圈回调开始'{$this->order->cSn}'。", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder222');

                    $callBackResult = $payService->wsqCallBack(
                        $this->order->customer_id,
                        $this->order->business_id,
                        $this->order->pay_id,
                        $customerModel->mobile,
                        $this->order->amount,
                        $payamount,
                        $payment['discount'],
                        $payment['payid'],
                        $this->order->pay_time,
                        '',
                        ''
                    );

                }else{
                    Yii::log("thirdFreesScan2调用微商圈回调开始'{$this->order->payment_info}'。", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder222');

                    $payment_information = json_decode($this->order->payment_info , true);
                    $callBackResult = $payService->microBusinessCallBack(
                        $this->order->customer_id,
                        $this->order->business_id,
                        $this->order->cSn,
                        $this->order->sn,
                        $this->order->amount,
                        $payment_information['pano'],
                        $payment_information['payment_type'],
                        $this->order->pay_time
                    );
                }
                #################区分新老微商圈---结束################

                if (!empty($callBackResult)){ //回调成功改状态
                	Yii::log("thirdFreesScan回调第三方成功：".$callBackResult, CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder222');
                	$this->order->returnMsg = '0';
                	$this->order->returnNums = $this->order->returnNums + 1;
                	$this->order->note = $this->order->note . '，第三方成功回调时间：' . time();
                	$this->order->update();  //更新表
                }else {
                	Yii::log("thirdFreesScan回调第三方失败：".$callBackResult, CLogger::LEVEL_ERROR, 'colourlife.core.ThirdOrder222');
                }

            }
            else {
            	Yii::log("thirdFreesScan回调第三方'{$this->order->callbackUrl}'。", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder222');
                // 回调对方
                $this->order->callRemoteServerBack();
            }


            return true;
        }
        return false;
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
        if ($this->order->update() && ThirdFeesLog::createOtherFeesLog($this->order_id, $this->order->model, $this->status, '银行支付成功，红包扣款失败',$this->order->customer_id)) {
            Yii::log("记录第三方订单付款 订单流水号'{$this->order_sn}' 银行支付成功，红包扣款失败。", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');
            return true;
        }
        return false;
    }

    //物业缴费  发送短信
    private function sendSms()
    {
        try {
            Yii::app()->sms->sendThirdPaymentMessage('successfulPayment', $this->order_sn);
        } catch (Exception $e) {
            Yii::log("记录第三方订单付款流水号'{$this->order_sn}' 状态:已付款。发短信异常。", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder');
        }
    }

    //订单处理
    public function orderProcessing()
    {
    	Yii::log("ThirdOrder第三方订单处理方法", CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder.OrderProcessing');
        if ($this->order->red_packet_pay > 0 || (isset($this->order->pay_info) && !empty($this->order->pay_info))) { //使用红包抵扣
//            if (!$this->switch) { //开关关掉直接返回失败
//                $this->balanceFailed();
//                return null;
//            }
            if($this->order->model == 'thirdFrees77'){
                return $this->updatePayStatus();
            }


        	Yii::log("ThirdOrder第三方订单处理方法红包支付,订单号：".$this->order->sn.'金额：'.$this->order->red_packet_pay, CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder.OrderProcessing');
            if ($this->order->red_packet_pay <= $this->balance) { //余额可以支付
            	Yii::log("ThirdOrder第三方订单处理方法红包支付,订单号：".$this->order->sn.'用户余额：'.$this->balance, CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder.OrderProcessing');
                $redPacketModel = new RedPacket();
                $attr = array();
                $attr['customer_id'] = $this->order->customer_id;
                $attr['to_type'] = 11;
                $attr['sum'] = $this->order->red_packet_pay;
                $attr['sn'] = $this->order->sn;
                //2017-04-14为支持地方饭票加入start
                if(isset($this->order->pay_info) && !empty($this->order->pay_info)){
                    Yii::log("地方饭票支付。sn:".$this->order->sn, CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder.LocalRedPacketPay');
                    $attr['pay_info'] = $this->order->pay_info;
                }
                //2017-04-14为支持地方饭票加入end

                $order = ThirdFees::model()->find('sn=:sn', array(':sn' => $attr['sn']));
                if($order->model == 'thirdFrees126'){
                    $add_items = array(
                        'customer_id' => 2222308,                                  //用户的ID
                        'from_type' => Item::RED_PACKET_FROM_TYPE_THIRD_PAYMENT,
                        'sum' =>$attr['sum'],                                              //红包金额,
                        'sn' => $attr['sn'],
                    );
                    Yii::log("ThirdOrder第三方thirdFrees126订单处理方法红包支付,".json_encode($attr), CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder.OrderProcessing');
                    if ($redPacketModel->consumeRedPacker($attr) && $redPacketModel->addRedPacker($add_items) ){
                    	Yii::log("ThirdOrder第三方thirdFrees126订单处理方法交易成功,".json_encode($attr), CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder.OrderProcessing');
                    	return $this->updatePayStatus();
                    }else{
                    	Yii::log("ThirdOrder第三方thirdFrees126订单处理方法交易失败,".json_encode($attr), CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder.OrderProcessing');
                    	$this->balanceFailed();
                    	return false;
                    }
                        
                }else{
                	Yii::log("ThirdOrder第三方".$order->model."订单扣红包开始,".json_encode($attr), CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder.OrderProcessing');
                    if ($redPacketModel->consumeRedPacker($attr))
                        return $this->updatePayStatus();
                    else{
                        $this->balanceFailed();
                        return false;
                    }
                }
                Yii::log("ThirdOrder第三方".$this->order->model."订单扣红包结束,".json_encode($attr), CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder.OrderProcessing');
            } else{
            	Yii::log("ThirdOrder第三方".$this->order->model."订单余额不可以支付,".json_encode($attr).'余额：'.$this->balance, CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder.OrderProcessing');
                $this->balanceFailed();
                return false;
            }

        } else {//正常更新流程
        	//Yii::log("ThirdOrder第三方支付流程,".json_encode($attr), CLogger::LEVEL_INFO, 'colourlife.core.ThirdOrder.OrderProcessing');
            return $this->updatePayStatus();
        }

        //return $this->updatePayStatus();
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