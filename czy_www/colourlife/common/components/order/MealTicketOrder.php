<?php

/**
 * 预缴费订单处理
 * @property OthersFees $order
 * Created by PhpStorm.
 * User: sunny
 * Date: 14-3-24
 * Time: 下午3:49
 */
class MealTicketOrder extends OrderFactory
{
    public $order;

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
        $this->switch = @Yii::app()->config->SwitchPropertyRedPacket; //红包开关
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
        $this->note = empty($this->note) ? '正在已付款充值中' : $this->note;
        if ($this->order->update() && OthersFeesLog::createOtherFeesLog($this->order_id, $this->model, $this->status, $this->note,$this->order->customer_id)) {


            try {
                $this->makeTicket();//生成票号
            } catch (Exception $e) {
                Yii::log("记录饭票券订单付款流水号'{$this->order_sn}' 状态:已付款，生成饭票券失败", CLogger::LEVEL_INFO, 'colourlife.core.MealTicketOrder');
            }
            try {
                IntegralEvent::fees($this->order);//付款成功积分
            } catch (Exception $e) {
                Yii::log("记录饭票券订单付款流水号'{$this->order_sn}' 状态:已付款。送积分异常。", CLogger::LEVEL_INFO, 'colourlife.core.MealTicketOrder');
            }
            $this->active();//活动，已做异常处理
            //$this->sendSms();//以送短信，已做异常处理
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
        if ($this->order->update() && OthersFeesLog::createOtherFeesLog($this->order_id, $this->model, $this->status, '红包余额不足，交易失败',$this->order->customer_id)) {
            Yii::log("记录预缴费订单付款流水号'{$this->order_sn}' 红包余额不足，交易失败。", CLogger::LEVEL_INFO, 'colourlife.core.AdvanceOrder');
            return true;
        }
        return false;
    }

    //物业缴费  发送短信
    private function sendSms()
    {
        try {
            Yii::app()->sms->sendPropertyPaymentMessage( 'paymentSuccess', $this->order_sn, 'prePayment' );
        } catch (Exception $e) {
            Yii::log("记录饭票券订单付款流水号'{$this->order_sn}' 状态:已付款。发短信异常。", CLogger::LEVEL_INFO, 'colourlife.core.MealTicketOrder');
        }

    }

    //回调彩之云的去修改订单
    private function makeTicket()
    {
        $db = Yii::app()->db;
        $isTransaction = $db->getCurrentTransaction();//判断是否有用过事务
        $transaction =  (!$isTransaction)? $db->beginTransaction() : '';

        $command = $db->createCommand();

        try {
            $newRow = array(
                'category_id' => $this->order->MealTicketFees->category_id,
                'type_id' => $this->order->MealTicketFees->type_id,
                'value' => $this->order->MealTicketFees->value,
                'owner_id' => $this->order->customer_id,
                'create_time' => time(),
                'sn' => $this->order->sn
            );

            $number = $this->order->MealTicketFees->number;
            // 生成饭票券
            for ($i=0; $i<$number; $i++) {
                $command->reset();
                $command->insert(MealTicket::model()->tableName(),$newRow);
            }

            // 更新订单状态
            OthersFees::model()->updateByPk($this->order_id, array('status' => Item::FEES_TRANSACTION_SUCCESS));
            (!$isTransaction)?$transaction->commit():'';

            return true;
        }
        catch(Exception $e) // 如果有一条查询失败，则会抛出异常
        {
            (!$isTransaction)?$transaction->rollback():'';
            Yii::log("生成饭票券失败{$e->getMessage()}", CLogger::LEVEL_INFO, 'colourlife.core.MealTicketOrder');
            return false;
        }

    }

    //订单处理
    public function orderProcessing()
    {
        if ($this->order->red_packet_pay > 0  || (isset($this->order->pay_info) && !empty($this->order->pay_info))) { //使用红包抵扣
            if (!$this->switch) { //开关关掉直接返回失败
                $this->balanceFailed();
                return false;
            }

            if ($this->order->red_packet_pay <= $this->balance) { //余额可以支付
                $redPacketModel=new RedPacket();
                $attr=array();
                $attr['customer_id']=$this->order->customer_id;
                $attr['to_type'] = Item::RED_PACKET_TO_TYPE_MEAL_TICKET;
                $attr['sum']= $this->order->red_packet_pay;
                $attr['sn']=$this->order->sn;
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
            } else {
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