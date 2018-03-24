<?php
/**
 * 购电订单处理
 * Created by PhpStorm.
 * User: sunny
 * Date: 14-3-24
 * Time: 下午3:49
 * @property OthersFees $order
 */
class PowerOrder extends OrderFactory
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
        $this->switch = @Yii::app()->config->SwitchPowerRedPacket; //商品开关
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
        $this->status = Item::ORDER_AWAITING_GOODS;
        $this->order->status = $this->status;
        $this->order->pay_time = time();
        $this->order->payment_id = $this->payment_id;
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
        $this->note = $note;
        $powerArr=PowerFees::model()->findByPk($this->order->object_id);
        $event='BILL_ACH_CHARGE';
        $source='eepho5Ei';
        if ($this->order->update() && OrderLog::createOrderLog($this->order_id, $this->model, $this->status, $this->note,$this->order->customer_id)) {
            //加入金融平台转账记录
            Yii::import('common.services.PowerFinanceService');
            $model = new PowerFinanceService();
            $transaction = $model->transaction($this->order->sn, $this->order->customer_id, $this->order->amount, $this->note, time(), 0);
            if(!$transaction){
                Yii::log('e能源金融平台转账记录失败，用户id'.$this->order->customer_id.' 订单号:'.$this->order->sn, CLogger::LEVEL_INFO, 'colourlife.core.PowerFinanceTransaction');
            }

            //加入ice触发器
            Yii::import('common.api.IceApi');
            $sendPower = IceApi::getInstance();
            $dataArr=array(
                'meterid'=>$powerArr['meter'],
                'ordernumber'=>$this->order->sn,
                'amount'=>$this->order->amount,
                'rdate'=>date('Y-m-d H:i:s',time()),
                'uuid'=>$powerArr['community_id'],
                'result'=>0,
            );
            $data=  json_encode($dataArr);
            $mg=$sendPower->sendPowerOrder($event,$data,$source);
            Yii::log('商铺买电订单付款流水号'.$this->order_sn.' 状态:已付款,返回值:'.var_export($mg, true), CLogger::LEVEL_INFO, 'colourlife.core.SendPowerOrder');
            //$this->sendSms();
            $this->callStarOrder();
            Yii::log("记录商铺买电订单付款流水号'{$this->order_sn}' 状态:已付款。操作:在线支付。。", CLogger::LEVEL_INFO, 'colourlife.core.PowerOrder');
            //IntegralEvent::fees($this->order);//付款成功积分
            //$this->active();//活动
            return true;
        }
        //加入ice触发器
        Yii::import('common.api.IceApi');
        $sendPower = IceApi::getInstance();
        $dataArr=array(
            'meterid'=>$powerArr['meter'],
            'ordernumber'=>$this->order->sn,
            'amount'=>$this->order->amount,
            'rdate'=>date('Y-m-d H:i:s',time()),
            'uuid'=>$powerArr['community_id'],
            'result'=>4,
        );
        $data=  json_encode($dataArr);
        $mg=$sendPower->sendPowerOrder($event,$data,$source);
        Yii::log('商铺买电订单付款流水号'.$this->order_sn.' 状态:付款失败,返回值:'.var_export($mg, true), CLogger::LEVEL_INFO, 'colourlife.core.SendPowerOrder');
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
        $powerArr=PowerFees::model()->findByPk($this->order->object_id);
        $event='BILL_ACH_CHARGE';
        $source='eepho5Ei';
        Yii::import('common.api.IceApi');
        $sendPower = IceApi::getInstance();
        $dataArr=array(
            'meterid'=>$powerArr['meter'],
            'ordernumber'=>$this->order->sn,
            'amount'=>$this->order->amount,
            'rdate'=>date('Y-m-d H:i:s',time()),
            'uuid'=>$powerArr['community_id'],
            'result'=>4,
        );
        $data=  json_encode($dataArr);
        $mg=$sendPower->sendPowerOrder($event,$data,$source);
        Yii::log('商铺买电订单付款流水号'.$this->order_sn.' 状态:交易失败,返回值:'.var_export($mg, true), CLogger::LEVEL_INFO, 'colourlife.core.SendPowerOrder');
        /*插入订单付款记录*/
        if ($this->order->update() && OrderLog::createOrderLog($this->order_id, $this->model, $this->status, '银行支付成功，红包扣款失败',$this->order->customer_id)) {
            Yii::log("记录商铺买电订单付款 订单流水号'{$this->order_sn}' 银行支付成功，红包扣款失败。", CLogger::LEVEL_INFO, 'colourlife.core.PowerOrder');
            return true;
        }
        return false;
    }
    //购电缴费  发送短信
    private function sendSms()
    {
        Yii::app()->sms->sendPowerPaymentMessage( 'paymentSuccess', $this->order_sn  );
    }

    //回调安彩华接口的去修改订单
    private function callStarOrder()
    {
        OthersPowerFees::model()->callStarOrder($this->order_id, Item::FEES_TRANSACTION_SUCCESS, '回调安彩华接口修改商铺买电状态');
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
                $attr['to_type'] = 6;
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