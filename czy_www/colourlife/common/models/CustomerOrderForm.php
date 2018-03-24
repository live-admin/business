<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CustomerOrderForm extends CFormModel
{
    public $community_id;
    public $buyer_address;
    public $buyer_tel;
    public $buyer_name;
    public $buyer_postcode;
    public $comment;
    public $params = array();
    //京东
    public $email;
    public $province;
    public $city;
    public $county;
    public $town;
    public $you_hui_quan_id;
    public $order_type;
    public $pingtai_id;
    public $trade_price;
    public $freight;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('buyer_name,community_id, buyer_address,buyer_tel', 'required'),
            array('buyer_name,community_id, buyer_address,buyer_tel', 'required', 'on' => 'createCustomerOrder'),
            array('params', 'checkOrderParams', 'on' => 'createCustomerOrder'),
            array('params', 'checkOrderParams', 'on' => 'createJmOrder'),
            array('buyer_postcode,email,province,city,county,town,you_hui_quan_id,order_type,pingtai_id,trade_price ,freight', 'safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'buyer_name' => '收货人',
            'buyer_address' => '收货地址',
            'count' => '数量',
            'buyer_tel' => '收货电话',
            'amount' => ' 总金额',
            'buyer_postcode' => '收货邮编',
            'comment' => '留言',
        );
    }

    public function createOrder()
    {
        //下订单
        $order = new CustomerOrder;
        $goods = Goods::model()->findByPk(intval($this->goods_id));

        if (!$this->hasErrors() && !isset($goods) || empty($goods)) {
            $this->addError('buyer_name', '请确认你下单个的商品是否存在！');
        }

        if(!$this->hasErrors() && ($this->red_packet_pay>0) && ($this->red_packet_pay+$this->bank_pay!=$this->amount)){//判断用户是否有使用红包抵扣
            throw new CHttpException(400, "创建订单失败，金额不匹配！");
        }

        $order->count = $this->count;
        $order->bank_pay = $this->bank_pay;
        $order->red_packet_pay = $this->red_packet_pay;
        $order->buyer_address = $this->buyer_address;
        $order->buyer_tel = $this->buyer_tel;
        $order->buyer_name = $this->buyer_name;
        $order->buyer_postcode = $this->buyer_postcode;
        $order->comment = $this->comment;
        $order->pingtai_id = $this->pingtai_id;
        //取得购买商品所在的小区的的小区
        $community_id = F::getCookie("community_id");
        $order->community_id = !empty($community_id) ? $community_id : 0;

        $order->build_id = 'null';

        $this->order_id = $order->addOrder($goods);
        if ($this->order_id)
            return true;
        else
            return false;
    }

    /**
     * @return array
     * 创建业主订单，返回sn集合
     */
    public function createCustomerOrder(){
        $snList = array();
        $transaction = Yii::app()->db->beginTransaction(); //创建事务
        $is_commit = true;
        foreach($this->params as $orderParams){
            $order = new CustomerOrder();
            //从表单获得一部分订单内容
            $attributes = array_merge($this->attributes, $orderParams['param']);
            $order->attributes = $this->attributes;
            
            //从购物车获得一部分订单数据

            $order->attributes = $orderParams['param'];
            
            
            //京东字段
            $order->email=$this->email;
            $order->province=$this->province;
            $order->city=$this->city;
            $order->county=$this->county;
            $order->town=$this->town;
            //优惠券
            $order->you_hui_quan_id=$this->you_hui_quan_id;
            //大闸蟹
            $order->order_type=$this->order_type;
            $order->freight=$this->freight;
            
            
            $order->buyer_id = Yii::app()->user->id;
            $order->sn = SN::initByOnlineSale($order->community_id)->getSN();
            if($order->save()){
                /**
                 * @var Shop    $shop
                 */
                $cwyProfit = 0;//平台提成
                $amProfit = 0;//客户经理提成
                $shop = Shop::model()->findByPk($order->seller_id);
                foreach($orderParams['relation'] as $goodInfo){
                    $ogRelation = new OrderGoodsRelation();
                    $ogRelation->attributes = $goodInfo;
                    $ogRelation->order_id = $order->id;
                    !isset($ogRelation->cwy_rate) ?:$ogRelation->cwy_rate = $shop->deduct;
                    /**#########START 客户经理分成###########*/
                    $goods = Goods::model()->findByPk($ogRelation->goods_id);
                    if(!empty($goods)){
                        $goodsCategory = GoodsCategory::model()->findByPk($goods->category_id);
                        $amRate = GoodsCategoryCommunityRelation::getAmRate($order->community_id, $goodsCategory->id);//客户经理分成比例
                        if(false !== $amRate){
                            !isset($ogRelation->am_rate) ?:$ogRelation->am_rate = $amRate;
                        }
                    }
                    /**#########END 客户经理分成###########*/
                    //添加订单商品的关联
                    if($ogRelation->save()){

                        /**###########start 分成###############*/
                        /**
                         *  平台：（商品单价*商品数量）*分成比例
                         * 商家：（商品单价*商品数量）*商家分成比例
                         * 客户经理：注册在客户经理绑定楼栋业主在平台上消费的不含积分的订单金额*客户经理分成比例，即（商品单价*商品数量-积分兑换金额）*分成比例；
                         * ps:客户经理的分成因为用户可以更换楼栋。所以用户变更楼栋导致客户经理的分成给了新的客户经理。
                         * 以上用户变更导致的问题不属于我们的程序bug，我们不管。这条由王昊确认的。
                         */
                        $amount = $ogRelation->count * $ogRelation->price;//商品单价*商品数量
                        if(!empty($shop)){
                            $cwyProfit += round($amount * ($shop->deduct / 100), 2);//平台分成
                        }
                        else{
                            Yii::log('商家不存在，无法计算平台的分成！订单ID:【'.$order->id.'】', CLogger::LEVEL_INFO, 'colourlife.core.CustomerOrder');
                        }

                        /**#########START 客户经理分成###########*/
                        if(isset($amRate) && false !== $amRate){
                            $integral = 0;//积分支付
                            if(0 < $ogRelation->use_integral){
                                $integral = $ogRelation->integral_price;//积分抵扣金额
                            }
                            $amProfit += round(($amount - $integral) * ($amRate / 100), 2);//客户经理分成
                        }
                        /**#########END 客户经理分成###########*/
                        $order->cwy_profit = $cwyProfit;
                        $order->am_profit = $amProfit;
                        $order->income_pay_time=0;//支付时间为空时报错
                        $order->setScenario('profit');
                        if(!$order->save()){
                            $is_commit = false;//保存提成失败
                        }
                        /**###########end 分成###############*/

                        //添加周销量，抑制错误，添加销量失败不回滚事务
                        @Goods::addWeekSales($goodInfo['goods_id'], $goodInfo['count']);
                        //添加总销量
                        @Goods::addSales($goodInfo['goods_id'], $goodInfo['count']);
                        //如果是天天特价，加销量
                        $goods = Goods::model()->findByPk($goodInfo['goods_id']);
                        if ($goods->audit_cheap == Goods::AUDIT_CHEAP_YES) {
                            @CheapLog::addSales($goodInfo['goods_id'], $goodInfo['count']);
                        }
                    }else{
                        $is_commit = false;
                        Yii::log("保存订单-商品关联失败，创建业主订单失败,Error:".json_encode($ogRelation->getErrors()),
                            CLogger::LEVEL_ERROR,'colourlife.core.order.CreateCustomerOrder');
                    }
                    //写订单日志
                    $goodsName = '';
                    if($goods = Goods::model()->findByPk($ogRelation->goods_id)){
                        $goodsName = $goods->name;
                    }
                    $note = $order->buyer_name . '向' . $order->seller_contact . " 下单,商品：【{$goodsName}】" . ' date:' . date('Y-m-d H:i:s', time());
                    if (!OrderLog::createOrderLog($order->id, 'customer', Item::ORDER_AWAITING_PAYMENT, $note)) {
                        $is_commit = false;
                        Yii::log("写订单日志失败，创建业主订单失败！",CLogger::LEVEL_ERROR,'colourlife.core.order.CreateCustomerOrder');
                    }
                    //出现了错误，结束循环
                    if(!$is_commit)break;
                }
            }else{
                Yii::log("创建业主订单失败！Error:".json_encode($order->getErrors()),
                    CLogger::LEVEL_ERROR,'colourlife.core.order.CreateCustomerOrder');
            }
            //出现了错误，结束循环
            if(!$is_commit)break;

            //没有问题则保存sn
            $snList[] = $order->sn;
        }

        if($is_commit){
            $transaction->commit();
        }else{
            $transaction->rollback();
        }

        return $snList;
    }

    /**
     * 在创建业主订单前验证相关信息
     * @return bool
     */
    public function checkOrderParams(){
        if (empty($this->params)) {
            $this->addError('error',"订单商品相关信息不能为空！");
            return false;
        }
        $packet_amount = 0;//所有订单使用的红包总额
        $integral_amount = $integral_price_amount = 0;//用户使用的积分和积分抵扣金额
        foreach($this->params as $paramList){
            //验证订单的信息和关联商品的信息是否存在
            if(!isset($paramList['relation']) or !isset($paramList['param']))continue;
            if(empty($paramList['relation']) or empty($paramList['param']))continue;
            $shop_id = $paramList['param']['shop_id'];
            $scRelation = ShopCommunityRelation::model()->findByAttributes(array('shop_id'=>$shop_id,
                'community_id'=>$this->community_id));
            if(empty($scRelation)){
                $this->addError('error',"订单内存在不服务小区的商品，创建订单失败！");
                return false;
            }
            $packet_amount += $paramList['param']['red_packet_pay'];//获取用户欲消费的红包总额
            //循环每个订单的关联商品
            foreach($paramList['relation'] as $goods){
                $goodInfo = Goods::model()->findByPk($goods['goods_id']);
                if($goodInfo->is_on_sale!=Goods::SALE_YES){
                    $this->addError('error',"订单内存在已下架商品，创建订单失败！");
                    return false;
                }
                $attr = array('goods_id'=>$goods['goods_id'],
                    'shop_id'=>$paramList['param']['shop_id'],
                    'community_id'=>$this->community_id,
                );
                //获得商家小区商品销售表记录
                $sell = ShopCommunityGoodsSell::model()->findByAttributes($attr);
                if(empty($sell) or $sell->is_on_sale!=Goods::SALE_YES){
                    $this->addError('error',"订单内存在(小区)已下架商品，创建订单失败！");
                    return false;
                }
                //保存用户使用的积分总额
                $integral_amount += $goods['integral'];
                $integral_price_amount += $goods['integral_price'];
            }
        }

        $customer = Customer::model()->findByPk(Yii::app()->user->id);
        if($packet_amount > $customer->getBalance()){
            $this->addError('error',"红包使用总额不能大于用户余额");
            return false;
        }

        //如果有商品使用了积分抵扣,那么使用积分总额》0
        if($integral_amount>0){
            $switchArr = Yii::app()->config->integralSwitch;
            if(!$switchArr['switch']){//积分开关未开启
                $this->addError('error',"商品积分抵扣金额暂不支持，创建订单失败！");
                return false;
            }
            //只有使用了积分才判断用户积分余额
            if($customer->credit<=0 || $customer->credit<=$integral_amount){
                $this->addError('error',"用户积分余额不足，创建订单失败！");
                return false;
            }
        }
        return true;
    }

}