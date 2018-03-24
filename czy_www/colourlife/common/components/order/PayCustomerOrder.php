<?php
/**
 * 业主订单处理
 * @property BaseOrder $order
 * Created by PhpStorm.
 * User: sunny
 * Date: 14-3-24
 * Time: 下午3:49
 */
class PayCustomerOrder extends OrderFactory
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
        $this->switch = @Yii::app()->config->SwitchGoodsRedPacket; //商品开关
        $this->order = Order::model()->findByPk($order_id);
        $this->order_id = $order_id;
        $this->order_sn = $this->order->sn;

        $this->model = PayLib::get_model_by_sn($this->order_sn);
        //$this->balance = empty($this->order->customer_buyer) ? 0 : $this->order->customer_buyer->getBalance();
        $this->balance = $this->order->customer_buyer->getBalance();

        //计算分成
        $this->setProfits();
    }

    /**
     * 修改付款状态
     * @return bool
     */
    private function updatePayStatus()
    {
        $this->order = Order::model()->findByPk($this->order_id);
        $this->status = Item::ORDER_AWAITING_GOODS;
        $couponNo = '';
        //检查优惠码
        if (!empty($this->order->good_list)){
            foreach($this->order->good_list as $v_1) { 
                $data=Goods::model()->findByPk($v_1->goods_id);
                if ($data->category_id == 186) {
                    $couponNoArr = GoodsCouponNo::model()->find('goods_id=:goods_id AND is_use=:is_use', array(':goods_id' => $v_1->goods_id, ':is_use'=>0));
                    if ($couponNoArr){
                        $couponNo = '，优惠码：' . $couponNoArr->code;
                        break;
                    }
                }
            }
        }
        $this->order->status = $this->status;
        $this->order->income_pay_time = time();
        $this->order->payment_id = $this->payment_id;
        $this->note = empty($this->note) ? '已付款，待发货' : $this->note;
        //var_dump($this->order->good_list);die;
        //扣除用户积分
       /*  if(!Customer::consumeIntegral($this->order_id)){
            //扣除失败
            $this->status = Item::ORDER_BALANCE_DEDUCT_FAILED;
            $this->order->status = $this->status;
            $this->note = "订单支付成功。但用户积分扣除失败，交易失败";
        } */
        if ($this->order->update() && OrderLog::createOrderLog($this->order_id, $this->model, $this->status, $this->note,$this->order->buyer_id)) {
            Yii::log("记录订单付款记录订单流水号'{$this->order_sn}' 备注:".$this->note . $couponNo, CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
            /* try {
                IntegralEvent::customerOrder($this->order);;//付款成功积分
            } catch (Exception $e) {
                Yii::log("记录订单付款流水号'{$this->order_sn}' 状态:已付款。送积分异常。" . $couponNo, CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
            } */
            $this->active();//活动，已做异常处理
            $this->customerSms($couponNo);//发送短信
            if ($couponNo){
                //优惠码再更新一次状态 变为收货
                $order = Order::model()->findByPk($this->order_id);
                $order->status = Item::ORDER_TRANSACTION_SUCCESS;
                $order->update();
            }
            //优惠券更新使用次数
            if(!empty($this->order->you_hui_quan_id)){
                $yes=YouHuiQuanWeb::model()->kouCoupons($this->order_id);
                if($yes){
                    Yii::log("使用优惠券的订单'{$this->order_id}'支付成功", CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
                }else{
                    Yii::log("使用优惠券的订单'{$this->order_id}'支付失败", CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
                }
            }
            //大闸蟹
//            if($this->order->order_type==2 && $this->order->seller_id==Item::DA_ZHA_XIE){
//                $yes=  DaZhaXie::model()->createTiHuoQuan($this->order_id);
//                if($yes){
//                    Yii::log("订单'{$this->order_id}'生成提货券成功", CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
//                }else{
//                    Yii::log("订单'{$this->order_id}'生成提货券失败", CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
//                }
//            }
            

            //京东下单
            if($this->order->seller_id==Item::SELLER_JD){
                $order = Order::model()->findByPk($this->order_id);
                $orderJson=JdApi::model()->jdPriceSubmit($order['sn']);
                $orderArr=json_decode($orderJson,true);
                if($orderArr['success']){
                    $sql="update `order` set jd_order_id='".$orderArr['result']['jdOrderId']."' where id=".$this->order_id;
                    Yii::app()->db->createCommand($sql)->execute();
                    Yii::log("京东下单成功".$orderArr['result']['jdOrderId'],CLogger::LEVEL_INFO,'colourlife.core.JdAutoCommand');
                }else{
                    Yii::log("京东下单失败:".$orderJson, CLogger::LEVEL_INFO,'colourlife.core.JdAutoCommand');
                }
            }

            //扣除库存
//            if($this->order->seller_id!=Item::JD_SELL_ID || $this->order->seller_id!=Item::DA_ZHA_XIE){
//                $res=Goods::model()->goodsKuCun($this->order_id);
//                if($res){
//                    Yii::log("订单'{$this->order_id}'扣除库存成功", CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
//                }else{
//                    Yii::log("订单'{$this->order_id}'扣除库存失败", CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
//                }
//            }

            //司庆活动
            if($this->order->seller_id==Item::SIQING){
                $order = Order::model()->findByPk($this->order_id);
                $orderRelationArr=OrderGoodsRelation::model()->find('order_id=:order_id',array(':order_id'=>$this->order_id));
                if(!empty($orderRelationArr)){
                    for($i=1;$i<=$orderRelationArr['count'];$i++){
                        SiQing::model()->getSiQingCode($orderRelationArr['goods_id'],$order['buyer_id'],4,$this->order_id);
                    }
                }
            }

            // 彩富商城 更新彩富价购买资格
            $orderGoods = OrderGoodsRelation::model()->find('order_id=:order_id', array(':order_id' => $this->order->id));
            $sql = 'SELECT `goods_id` FROM `activity_goods` WHERE `activity_name`=\'profitShop\'';
            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $profitShopGoodsIds = $command->queryColumn();
            //$profitShopGoodsIds = array(29742,29745,29746,29747);
            if ( !empty($orderGoods) && !empty($profitShopGoodsIds) && in_array($orderGoods->goods_id, $profitShopGoodsIds) && empty($this->order->you_hui_quan_id)) {
                $updateSql = 'UPDATE `profit_shop_ticket` SET `status`=1 WHERE `mobile`=:mobile AND `status`=0 ORDER BY `source` ASC  LIMIT 1';
                $mobile = $this->order->customer_buyer->mobile;
                Yii::app()->db->createCommand($updateSql)
                    ->bindValues(array(':mobile' => $mobile))
                    ->execute();
            }

            return true;
        }
        return false;
    }


    private function sendRedPacket($uid,$num,$count)
    {
        $items = array(
            'customer_id' => $uid,//用户的ID
            'from_type' => Item::RED_PACKET_FROM_TYPE_OCT_MILK,
            'sum' =>$num*$count,//红包金额,
            'sn' => 'Milk',
        );
        $redPacked = new RedPacket();
        $ret=$redPacked->addRedPacker($items);
        return $ret;

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
        if ($this->order->update() && OrderLog::createOrderLog($this->order_id, $this->model, $this->status, '银行支付成功，红包扣款失败',$this->order->buyer_id)) {
            Yii::log("记录订单付款记录 订单流水号'{$this->order_sn}' 银行支付成功，红包扣款失败。", CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
            return true;
        }
        return false;
    }

    /**
     * 扣除用户积分
     * @return bool
     */
    private function consumeIntegral()
    {
    	if ($this->updatePayStatus()){
    		return true;
    	}else {
    		$this->balanceFailed();
    		return false;
    	}
        //if(Customer::consumeIntegral($this->order_id)){
           //$this->updatePayStatus();
//         }else{
//             $this->IntegralFailed();
//         }
    }


    /**
     * 扣除用户积分失败
     * @return bool
     */
    private function IntegralFailed()
    {
        //交易失败
        $this->status = Item::ORDER_BALANCE_DEDUCT_FAILED;
        $this->order->status = $this->status;
        /*插入订单付款记录*/
        if ($this->order->update() && OrderLog::createOrderLog($this->order_id, $this->model, $this->status, '银行支付成功，扣除用户积分余额失败')) {
            Yii::log("记录订单付款记录 订单流水号'{$this->order_sn}' 银行支付成功，扣除用户积分余额失败。", CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
            return true;
        }
        return false;
    }

    //业主订单发送短信
    public function customerSms($couponNo = '')
    {
        try {
            if(Item::ORDER_AWAITING_GOODS == $this->order->status){//状态是已付款时才会发送短信
                //发送短信给商家
                $sms = Yii::app()->sms;
                //$sms->mobile = empty($this->order->seller) ? '' : $this->order->seller->mobile;
                $sms->sendGoodsOrdersMessage('paid', $this->order_sn,$this->order->buyer_tel,$title="商品订单", $couponNo);
                $sms->sendGoodsOrdersMessage('paymentSuccess', $this->order_sn,$this->order->seller_tel,$title="商品订单", $couponNo);
            }
        } catch (Exception $e) {
            Yii::log("记录订单付款流水号'{$this->order_sn}' 状态:已付款。发短信异常。", CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
        }

    }

    //订单处理
    public function orderProcessing()
    {
        if ($this->order->red_packet_pay > 0 || (isset($this->order->pay_info) && !empty($this->order->pay_info))) { //使用红包抵扣
            if (!$this->switch) { //开关关掉直接返回失败
            	Yii::log("开关关掉直接返回失败。sn:".$this->order->sn, CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder.orderProcessing');
                $this->balanceFailed();
                return false;
            }

            if ($this->order->red_packet_pay <= $this->balance) { //余额可以支付
            	Yii::log("余额可以支付。sn:".$this->order->sn, CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder.orderProcessing');
                $redPacketModel = new RedPacket();
                $attr = array();
                $attr['customer_id'] = $this->order->buyer_id;
                $attr['to_type'] = 4;
                $attr['sum'] = $this->order->red_packet_pay;
                $attr['sn'] = $this->order->sn;
                //2017-04-14为支持地方饭票加入start
                if(isset($this->order->pay_info) && !empty($this->order->pay_info)){
                    Yii::log("地方饭票支付。sn:".$this->order->sn, CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder.LocalRedPacketPay');
                    $attr['pay_info'] = $this->order->pay_info;
                    $pay_info_arr = json_decode($this->order->pay_info , true);
                    $attr['sum'] = $attr['sum'] + $pay_info_arr[0]['money'];
                }
                //2017-04-14为支持地方饭票加入end
                if ($redPacketModel->consumeRedPacker($attr)){
                	Yii::log("红包支付成功。sn:".$this->order->sn, CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder.orderProcessing');
                	return $this->consumeIntegral();//扣积分
                }else {
                	Yii::log("红包支付失败。sn:".$this->order->sn, CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder.orderProcessing');
                	$this->balanceFailed();
                	return false;
                }
            } else{
            	$this->balanceFailed();
            	return false;
            }
        } else {//正常更新流程
        	Yii::log("正常更新流程。sn:".$this->order->sn, CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder.orderProcessing');
            return $this->consumeIntegral();
        }

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