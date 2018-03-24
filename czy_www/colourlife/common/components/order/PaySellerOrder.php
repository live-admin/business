<?php
/**
 * 加盟订单处理
 * Created by PhpStorm.
 * User: sunny
 * Date: 14-3-24
 * Time: 下午3:49
 * @property Order  $order
 */
class PaySellerOrder extends OrderFactory
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
        //$this->switch = @Yii::app()->config->SwitchGoodsRedPacket; //商品开关
        $this->order = Order::model()->findByPk($order_id);
        $this->order_id = $order_id;
        $this->order_sn = $this->order->sn;
        $this->model = SN::findModelBySN($this->order_sn);
        //$this->balance = empty($this->order->customer_buyer) ? 0 : $this->order->customer_buyer->balance;
        //计算分成
        $this->setProfits();
    }

    /**
     * 修改付款状态
     * @return bool
     */
    private function updatePayStatus()
    {
        $this->status = Item::ORDER_AWAITING_GOODS;
        $this->order->status = $this->status;
        $this->order->income_pay_time = time();
        $this->order->payment_id = $this->payment_id;
        $this->note = empty($this->note) ? '已付款，待发货' : $this->note;
        if ($this->order->update() && OrderLog::createOrderLog($this->order_id, $this->model, $this->status, $this->note,$this->order->seller_id)) {
            Yii::log("记录订单付款记录订单流水号'{$this->order_sn}' 备注已付款，待发货。", CLogger::LEVEL_INFO, 'colourlife.core.SellerOrder');
            $this->customerSms();//发送短信
            return true;
        }
        return false;
    }
    //发送短信
    public function customerSms()
    {
        try {
            if(Item::ORDER_AWAITING_GOODS == $this->order->status){//状态是已付款时才会发送短信
                //发送短信给商家
                $sms = Yii::app()->sms;
                $sms->mobile = empty($this->order->seller) ? '' : $this->order->seller->mobile;
                $sms->sendGoodsOrdersMessage('paid', $this->order_sn);
                $sms->sendGoodsOrdersMessage('paymentSuccess', $this->order_sn);
            }
        } catch (Exception $e) {
            Yii::log("记录订单付款流水号'{$this->order_sn}' 状态:已付款。发短信异常。", CLogger::LEVEL_INFO, 'colourlife.core.SellerOrder');
        }

    }
    //订单处理
    public function orderProcessing()
    {
            return $this->updatePayStatus();
    }

    /**
     * 业主订单计算分成
     */
    protected function setProfits()
    {
        /**
         *  支付分成：（商品单价*商品数量）*支付分成比例
         */
        /**
         * @var OrderGoodsRelation  $relation
         * @var Payment  $payment
         */
        if($this->payment_id>0){
            $payment_profit = 0;
            $payment = Payment::model()->findByPk($this->payment_id);
            if($payment){
                if(!empty($this->order->good_list)){
                    foreach($this->order->good_list as $relation)
                    {
                        $amount = $relation->count * $relation->price;//商品单价*商品数量
                        $payment_profit += round($amount * $payment->rate / 100, 2);//分成
                    }
                }
                $this->order->pay_rate = $payment->rate;
            }

            $this->order->payment_profit = $payment_profit;
        }

    }
}