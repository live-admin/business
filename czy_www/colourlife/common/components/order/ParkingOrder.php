<?php

/**
 * 停车费订单处理
 * Created by PhpStorm.
 * User: sunny
 * Date: 14-3-24
 * Time: 下午3:49
 * @property OthersFees $order
 */
class ParkingOrder extends OrderFactory
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
        $this->switch = @Yii::app()->config->SwitchParkingRedPacket; //商品开关
        $this->order = OthersFees::model()->findByPk($order_id);
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
        $this->order = OthersFees::model()->findByPk($this->order_id);
        $this->status = Item::FEES_TRANSACTION_ERROR;
        $this->order->status = $this->status;
        $this->order->pay_time = time();
        $this->order->payment_id = $this->payment_id;
        $this->note = empty($this->note) ? '已付款，待发货' : $this->note;
        if ($this->order->update() && OrderLog::createOrderLog($this->order_id, $this->model, $this->status, $this->note,$this->order->customer_id)) {

            Yii::log("记录停车费订单付款记录订单流水号'{$this->order_sn}' 备注已付款，待发货。", CLogger::LEVEL_INFO, 'colourlife.core.ParkingOrder');

            /***************** 通知第三方 ***********************/
            Yii::import('common.services.ParkingService');
            $parkingService = new ParkingService();
            if ('ParkingFeesMonth' == $this->order->model) {
            	$result = $parkingService->monthTransactionSuccess($this->order);
            }
            elseif ('ParkingFeesVisitor' == $this->order->model) {
            	$result = $parkingService->visitorTransactionSuccess($this->order);
            }
            /***************** 通知第三方 ***********************/
            try {
                IntegralEvent::fees($this->order);//付款成功积分
            } catch (Exception $e) {
                Yii::log("记录停车费订单付款流水号'{$this->order_sn}' 状态:已付款。送积分异常。", CLogger::LEVEL_INFO, 'colourlife.core.ParkingOrder');
            }
            $this->active();//活动,已做异常处理
            $this->sendSms();//发送短信，已做异常处理
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
        $this->status = Item::FEES_TRANSACTION_LACK;
        $this->order->status = $this->status;
        /*插入订单付款记录*/
        if ($this->order->update() && OrderLog::createOrderLog($this->order_id, $this->model, $this->status, '银行支付成功，红包扣款失败',$this->order->customer_id)) {
            Yii::log("记录停车费订单付款订单流水号'{$this->order_sn}' 银行支付成功，红包扣款失败。", CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
            return true;
        }
        return false;
    }

    //停车费缴费成功   发送停车费支付成功短信
    private function sendSms()
    {
        try {
            Yii::app()->sms->sendParkingFeesMessage( 'successfulPayment', $this->order_sn);
        } catch (Exception $e) {
            Yii::log("记录预缴费订单付款流水号'{$this->order_sn}' 状态:已付款。发短信异常。", CLogger::LEVEL_INFO, 'colourlife.core.AdvanceOrder');
        }

    }

    //订单处理
    public function orderProcessing()
    {
        if ($this->order->red_packet_pay > 0 || (isset($this->order->pay_info) && !empty($this->order->pay_info))) { //使用红包抵扣
            if (!$this->switch) { //开关关掉直接返回失败
                $this->balanceFailed();
                return false;
            }

            if ($this->order->red_packet_pay <= $this->balance) { //余额可以支付
                $redPacketModel = new RedPacket();
                $attr = array();
                $attr['customer_id'] = $this->order->customer_id;
                $attr['to_type'] = 3;
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
         * @var OrderGoodsRelation  $relation
         * @var Payment  $payment
         */
        if($this->payment_id>0){
            $payment = Payment::model()->findByPk($this->payment_id);
            if($payment){
                $this->order->pay_rate = $payment->rate;
            }
        }

    }
}