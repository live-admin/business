<?php
/**
 * 内部采购订单处理
 * @property PurchaseOrder $order
 * Created by PhpStorm.
 * User: sunny
 * Date: 14-3-24
 * Time: 下午3:49
 */
class PayPurchaseOrder extends OrderFactory
{
    public $integration;
    public $integral;
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
        $this->switch = @Yii::app()->config->integralSwitch['purchaseSwitch']; //开关
        $this->order = PurchaseOrder::model()->findByPk($order_id);
        $this->order_id = $order_id;
        $this->order_sn = $this->order->sn;
        $this->integration =$this->order->getOrderAllIntegral();//取得订单所有的积分
        $this->model = PayLib::get_model_by_sn($this->order_sn);
        $this->integral = empty($this->order->employee_buyer) ? 0 : $this->order->employee_buyer->integral;
        //计算支付费用
        $this->setProfits();
    }

    /**
     * 修改付款状态
     * @return bool
     */
    private function updatePayStatus()
    {
        $this->order = PurchaseOrder::model()->findByPk($this->order_id);
        $this->status = Item::ORDER_PURCHASE_GOODS;
        $this->order->status = $this->status;
        $this->order->pay_time = time();
        $this->order->payment_id = $this->payment_id;
        $this->note = empty($this->note) ? '采购订单'.$this->order_sn.'已付款，待发货' : $this->note;
        if ($this->order->update() && PurchaseOrderLog::createOrderLog($this->order_id,$this->status, $this->note,$this->order->employee_id)) {
            Yii::log("记录订单付款记录订单流水号'{$this->order_sn}' 备注已付款，待发货。", CLogger::LEVEL_INFO, 'colourlife.core.PurchaseOrder');
            $this->purchaseSms();
            return true;
        }
        return false;
    }

    //发送短信
    public function purchaseSms()
    {
        try {
            if(Item::ORDER_PURCHASE_GOODS == $this->order->status){//状态是已付款时才会发送短信
                //发送短信给商家
                $sms = Yii::app()->sms;
                // $sms->mobile = empty($this->order->seller) ? '' : $this->order->seller->mobile;
                $sms->sendPurchaseMessage('paid', $this->order_sn,$this->order->buyer_tel,$title="内部采购订单");
                $sms->sendPurchaseMessage('paymentSuccess', $this->order_sn,$this->order->seller_tel,$title="内部采购订单");
            }
        } catch (Exception $e) {
            Yii::log("记录内部采购订单付款流水号'{$this->order_sn}' 状态:已付款。发短信异常。", CLogger::LEVEL_INFO, 'colourlife.core.PurchaseOrder');
        }

    }
    /**
     * 修改红包余额不足状态
     * @return bool
     */
    private function integralFailed()
    {
        $this->status = Item::ORDER_PURCHASE_DEDUCT_FAILED;
        $this->order->status = $this->status;
        /*插入订单付款记录*/
        if ($this->order->update() && PurchaseOrderLog::createOrderLog($this->order_id,$this->model, $this->status, '银行支付成功，积分扣款失败',$this->order->employee_id)) {
            Yii::log("记录订单付款记录 订单流水号'{$this->order_sn}' 银行支付成功，积分扣款失败。", CLogger::LEVEL_INFO, 'colourlife.core.PurchaseOrder');
            return true;
        }
        return false;
    }

    //订单处理
    public function orderProcessing()
    {
        if ($this->integration > 0) { //使用积分抵扣
            if (!$this->switch) { //开关关掉直接返回失败
                $this->integralFailed();
                return false;
            }

            if ($this->integration <= $this->integral) { //余额可以支付
                $model= Employee::model()->findByPk($this->order->employee_id);
                $sn = $this->order->sn;
                if ($model->delIntegration($sn,$this->integration))
                    return $this->updatePayStatus();
                else{
                    $this->integralFailed();
                    return false;
                }

            } else{
                $this->integralFailed();
                return false;
            }

        } else //正常更新流程
            return $this->updatePayStatus();

    }

    /**
     * 内部采购订单计算支付比例
     */
    protected function setProfits()
    {
        /**
         * @var Payment  $payment
         */
        if($this->payment_id>0){
            $payment = Payment::model()->findByPk($this->payment_id);
            $this->order->pay_rate = $payment->rate;
        }

    }
}