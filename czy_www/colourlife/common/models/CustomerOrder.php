<?php

class CustomerOrder extends BaseOrder
{
    public $modelName = '业主订单';
    public $goods_id;
    public $count;

    //业主下订单
    public function addOrder($goods)
    {
        $transaction = Yii::app()->db->beginTransaction(); //创建事务

        $this->buyer_id = Yii::app()->user->id;
        $this->buyer_model = 'customer';

        $customer = Customer::model()->findByPk(Yii::app()->user->id);
        $this->sn = SN::initByOnlineSale($customer->community_id)->getSN();

        if (empty($this->buyer_tel)) {
            $this->buyer_tel = $customer->mobile;
        }
        if (empty($this->buyer_name)) {
            $this->buyer_name = $customer->name;
        }
        if (empty($this->buyer_address)) {
            $this->buyer_address = isset($customer->community) ? $customer->community->name . (isset($customer->build) ? $customer->build->name : '') . (empty($customer->room) ? '' : $customer->room) : '';
        }
        //增加订单小区ID和楼栋ID
        if (empty($this->community_id)) {
            $this->community_id = $customer->community_id;
        }

        if (empty($this->build_id)) {
            $this->build_id = $customer->build_id;
        }

        $this->supplier_id = $goods->shop_id;
        //如果不存在小区ID就取用户自己的小区ID
        $community_id = empty($this->community_id) ? $customer->community_id : $this->community_id;
        $shopr = ShopCommunityGoodsSell::model()->find('goods_id=:goods_id and community_id=:community_id',
            array(':goods_id' => $goods->id, ':community_id' => $community_id));
        if (isset($shopr) && !empty($shopr)) {
            $this->seller_id = $shopr->shop_id; //加盟商商家ID
            if (isset($shopr->sellShop)) {
                $this->seller_contact = $shopr->sellShop->contact;
                $this->seller_tel = $shopr->sellShop->mobile;
            }
        } else {
            $this->seller_id = $goods->shop_id;
            $this->seller_contact = isset($goods->shop) ? $goods->shop->contact : '';
            $this->seller_tel = isset($goods->shop) ? $goods->shop->mobile : '';
        }
        //总价格
        $this->amount = $goods->goodsPrice * $this->count;
        $this->comment = $this->comment == null ? '' : $this->comment;
        $this->note = $this->note == null ? '' : $this->note;
        if ($this->save()) {
            if(@Yii::app()->config->SwitchGoodsRedPacket && $this->red_packet_pay>0 && $this->red_packet_pay==$this->amount){
                $pay = new PayOrderForm();
                $pay->order_sn =array($this->sn);
                $pay->pay_sn = $this->sn;
                $paySn = $pay->createPay();
                //$payInfo = Pay::getModel($paySn);
                PayLib::order_paid( $paySn,0,'商品全额红包支付');
                // PayLib::order_paid($this->sn,0,'商品全额红包支付');
            }else{
                $pay = new PayOrderForm();
                $pay->order_sn =array($this->sn);
                $pay->pay_sn = $this->sn;
                $paySn = $pay->createPay();
            }


            //添加销量
            Goods::addWeekSales($goods->id, $this->count);
            Goods::addSales($goods->id, $this->count);
            //如果是天天特价，加销量
            if ($goods->audit_cheap == Goods::AUDIT_CHEAP_YES) {
                CheapLog::addSales($goods->id, $this->count);
            }

            //订单关联表
            if (!$this->addOrderRelation($this->id, $goods, $this->count)) {
                $transaction->rollback(); //回滚事务
                return false;
            }
            //订单记录
            $note = Yii::app()->user->username . '向' . $goods->shop_id . " 下单" . ' date:' . date('Y-m-d H:i:s', time());
            if (!OrderLog::createOrderLog($this->id, 'customer', Item::ORDER_AWAITING_PAYMENT, $note)) {
                $transaction->rollback(); //回滚事务
                return false;
            }
        } else {
            $transaction->rollback(); //回滚事务
            return false;
        }

        $transaction->commit(); //提交事务
        return $this->id;

    }


    //添加订单关联表
    private function addOrderRelation($order_id, $goods, $count = 1)
    {
        $order_relation = new OrderGoodsRelation();
        $order_relation->goods_id = $goods->id;
        $order_relation->order_id = $order_id;
        $order_relation->name = $goods->name;
        $order_relation->count = $count;
        $order_relation->price = $goods->goodsPrice;
        $order_relation->amount = $count * $goods->goodsPrice;

        if ($order_relation->validate() && $order_relation->save())
            return true;
        else
            return false;
    }

    /**
     * @return array
     * 检测红包使用
     */
    private  function checkOrderBalance(){
        if(empty($this->customer_buyer)){
            return array('result'=>false,'error'=>"获取用户余额失败");
        }
        $balance = $this->customer_buyer->balance;//用户红包余额
        $amount = $this->amount;//订单总金额
        $redPackedPay = $this->red_packet_pay;//用户红包支付金额
        //如果红包支付金额大于余额或红包支付金额大于订单总额
        if($redPackedPay > $balance){
            return array('result'=>false,'error'=>"红包余额不足");
        }
        if($redPackedPay > $amount){
            return array('result'=>false,'error'=>"红包金额不能超过订单总额");
        }
        return array('result'=>true,'error'=>"");
    }

    /**
     * @param integer $community_id 小区ID
     * @param array $cartList 购物车数组
     * @param array $packetList 商家对应的红包
     * @return array 返回订单及商品的集合,及错误信息
     * 根据商家拆分购物车
     */
    public static function SplitCartByShop($community_id,$cartList,$packetList=array()){
        $return = array('result'=>array(),'error'=>"");
        if(empty($cartList) or !is_array($cartList)){
            $return['error'] = "购物车为空，无法结算";
            return $return;
        }
        $customer_id = Yii::app()->user->id;
        $customer = Customer::model()->findByPk($customer_id);
        if(empty($customer)){
            $return['error'] = "未知的用户！";
            return $return;
        }

        if($customer->credit<0){//只需要判断不为0就好，积分不足，抵扣是余额清零
            $return['error'] = "积分余额不足！无法结算";
            return $return;
        }

        //在使用红包前判断用户提交的红包总额是否超过余额
        $balance = 0;
        foreach($packetList as $packet_pay){
            $balance += $packet_pay;
        }
        if($balance>$customer->balance){
            $return['error'] = "用户红包余额不足，无法结算！";
            return $return;
        }
        foreach($cartList as $shop_id=>$cartArr){
            if(empty($shop_id)){
                $return['error'] = "购物车存在异常商家，无法结算！";
                return $return;
            };
            $scAttr = array('shop_id'=>$shop_id,'community_id'=>$community_id);
            //查询商家和小区的关联，看商家是否服务该小区
            $scRelation = ShopCommunityRelation::model()->findByAttributes($scAttr);
            if(empty($scRelation)){
                $return['error'] = "购物车存在异常商家(商家不服务小区)，无法结算！";
                return $return;
            };

            $shop_subtotal = 0;//订单实付金额
            $shop_amount = 0;//订单总金额
            $ogrArr = array();//用来保存订单-商品关联表的数据
            foreach($cartArr as $cartInfo){
                if(empty($cartInfo['good_id'])){
                    $return['error'] = "购物车存在异常商品，无法结算！";
                    return $return;
                };
                $attr = array('goods_id'=>$cartInfo['good_id'],'shop_id'=>$shop_id,'community_id'=>$community_id);
                //获得商家小区商品销售表记录
                $sell = ShopCommunityGoodsSell::model()->findByAttributes($attr);
                if(empty($sell) or $sell->is_on_sale!=Goods::SALE_YES){
                    $return['error'] = "购物车存在异常商品(商品在小区不销售)，无法结算！";
                    return $return;
                }

                $goodInfo = Goods::model()->findByPk($cartInfo['good_id']);
                if(empty($goodInfo)){
                    $return['error'] = "购物车存在异常商品(非法的商品)，无法结算！";
                    return $return;
                }

                //商品总金额=商品单价*商品数目
                $good_subtotal = $good_amount = $cartInfo['good_price'] * $cartInfo['number'];
                //如果商品使用了积分抵扣
                if($cartInfo['integral']>0){
                    $switchArr = Yii::app()->config->integralSwitch;
                    if(!$switchArr['switch']){//积分开关未开启
                        $return['error'] = "商品积分抵扣金额暂不支持，无法结算！";
                        return $return;
                    }
                    $good_subtotal = $good_amount-$cartInfo['integral_price'];
                }
                $shop_amount += $good_subtotal;
                $ogrArr[] = array(//构建订单商品关联表需要保存的数据
                    'goods_id'=>$cartInfo['good_id'],//商品ID
                    'name'=>$cartInfo['good_name'],//商品名称
                    'price'=>$cartInfo['good_price'],//商品单价
                    'count'=>$cartInfo['number'],//商品数目
                    'integral' => $cartInfo['integral'],//用来抵扣的积分
                    'integral_price' => $cartInfo['integral_price'],//积分抵扣金额
                    'bank_pay' => $good_subtotal,//实际应付金额
                    'amount'=>$good_amount,//总金额
                );
                $shop_subtotal += $good_subtotal;//商家(订单)总金额
            }

            $red_packet_pay = 0;
            if(isset($packetList[$shop_id]) && $packetList[$shop_id]>0){//商家有对应的红包
                if(!Yii::app()->config->SwitchgoodsRedPacket){
                    $return['error'] = "暂时不支持红包抵扣金额，无法结算！";
                    return $return;
                }
                //订单总金额减去红包抵扣金额
                $red_packet_pay = $packetList[$shop_id];
                $shop_subtotal -= $red_packet_pay;
            }
            $shopInfo = Shop::model()->findByPk($shop_id);
            //$profitList = self::getProfits($ogrArr);//通过订单下的商品集合获得分成信息
            //dump($ogrArr);
            $orderArr = array(
                'shop_id'=>$shop_id,
                'seller_id'=>$shop_id,//销售商ID=shop_id，刘总确认的，拆单按加盟商拆单
                'supplier_id'=>0,//商品的所有者(供应商).不展示
                'buyer_model'=>'customer',//买家模型
                'seller_contact'=> $shopInfo->contact,//卖家联系人
                'seller_tel'=> $shopInfo->mobile,//卖家电话
                'red_packet_pay'=>$red_packet_pay,//订单使用红包抵扣金额
                'amount'=>$shop_amount,//订单总金额(包括红包金额,但扣除了商品积分抵扣金额)
                'bank_pay'=>$shop_subtotal,//实际应该支付金额
            );

            $return['result'][] = array('param'=>$orderArr,'relation'=>$ogrArr);
        }
        return $return;
    }
    /**
     * @param integer $community_id 小区ID
     * @param array $cartList 购物车数组
     * @param array $packetList 商家对应的红包
     * @return array 返回订单及商品的集合,及错误信息
     * 根据商家拆分购物车
     */
    public static function SplitCartByShop2($community_id,$cartList,$packetList=array(),$you_hui_quan_id , $freight){

        $return = array('result'=>array(),'error'=>"");
        if(empty($cartList) or !is_array($cartList)){
            $return['error'] = "购物车为空，无法结算";
            return $return;
        }
        $customer_id = Yii::app()->user->id;
        $customer = Customer::model()->findByPk($customer_id);
        if(empty($customer)){
            $return['error'] = "未知的用户！";
            return $return;
        }

        if ($customer->state == 1 || $customer->is_deleted == 1){
            $return['error'] = "用户已被禁用或删除！";
            return $return;
        }
        if($customer->credit<0){//只需要判断不为0就好，积分不足，抵扣是余额清零
            $return['error'] = "积分余额不足！无法结算";
            return $return;
        }

        //在使用红包前判断用户提交的红包总额是否超过余额
        $balance = 0;
        foreach($packetList as $packet_pay){
            $balance += $packet_pay;
        }
        if($balance>$customer->balance){
            $return['error'] = "用户红包余额不足，无法结算！";
            return $return;
        }

        foreach($cartList as $shop_id=>$cartArr){
            if(empty($shop_id)){
                $return['error'] = "购物车存在异常商家，无法结算！";
                return $return;
            };
            $scAttr = array('shop_id'=>$shop_id,'community_id'=>$community_id);
            //查询商家和小区的关联，看商家是否服务该小区
            $scRelation = ShopCommunityRelation::model()->findByAttributes($scAttr);
            if(empty($scRelation)){
                $return['error'] = "购物车存在异常商家(商家不服务小区)，无法结算！";
                return $return;
            }

            $shop_subtotal = 0;//订单实付金额
            $shop_amount = 0;//订单总金额
            $ogrArr = array();//用来保存订单-商品关联表的数据
            $youAmountArr = array();// 使用的优惠券
            $isUseYouHuiQuan = false;// 是否使用了优惠券
            if(!empty($you_hui_quan_id) && $you_hui_quan_id != 0){


                $userCoupons = UserCoupons::model()->find("mobile=:mobile and you_hui_quan_id=:you_hui_quan_id and is_use=0",array(
                    ':mobile' => $customer->mobile,
                    ':you_hui_quan_id' => $you_hui_quan_id,
                ));
                if (empty($userCoupons)){
                    $return['error'] = "优惠券不存在！";
                    return $return;
//					exit("优惠券不存在！");
                }
                /* if ($userCoupons->is_use == 1){
                     $return['error'] = "优惠券已使用！";
                     return $return;
                } */

                $num=Order::model()->count('you_hui_quan_id=:you_hui_quan_id and buyer_id=:buyer_id and status=0',array(':you_hui_quan_id'=>$you_hui_quan_id,':buyer_id'=>$customer_id));
                $youAmountArr=YouHuiQuan::model()->findByPk($you_hui_quan_id);
                if($num>=$youAmountArr['limit_num']){
                    $sqlUpdate='UPDATE `order` SET `status`=97 WHERE `buyer_id`='.$customer_id.' AND `you_hui_quan_id`='.$you_hui_quan_id.' AND `status`=0';
                    $resultUpdate=Yii::app()->db->createCommand($sqlUpdate)->execute();
                    if(!$resultUpdate){
                        $return['error'] = "优惠券已使用,请去订单列表付款或联系客户进行取消订单！";
                        return $return;
                    }
                }
                $isUseYouHuiQuan = true;
            }
            foreach($cartArr as $cartInfo){
                if(empty($cartInfo['good_id'])){
                    $return['error'] = "购物车存在异常商品，无法结算！";
                    return $return;
                };
                $attr = array('goods_id'=>$cartInfo['good_id'],'shop_id'=>$shop_id,'community_id'=>$community_id);
                //获得商家小区商品销售表记录
                $sell = ShopCommunityGoodsSell::model()->findByAttributes($attr);
                if(empty($sell) or $sell->is_on_sale!=Goods::SALE_YES){
                    $return['error'] = "购物车存在异常商品(商品在小区不销售)，无法结算！";
                    return $return;
                }

                $goodInfo = Goods::model()->findByPk($cartInfo['good_id']);
                if(empty($goodInfo)){
                    $return['error'] = "购物车存在异常商品(非法的商品)，无法结算！";
                    return $return;
                }

                $cartInfo['number'] = $isUseYouHuiQuan ? 1 : $cartInfo['number']; // 如果使用了优惠券，则商品数量为1

                    //商品总金额=商品单价*商品数目
                $good_subtotal = $good_amount = $cartInfo['good_price'] * $cartInfo['number'];
                //如果商品使用了积分抵扣
                if($cartInfo['integral']>0){
                    $switchArr = Yii::app()->config->integralSwitch;
                    if(!$switchArr['switch']){//积分开关未开启
                        $return['error'] = "商品积分抵扣金额暂不支持，无法结算！";
                        return $return;
                    }
                    $good_subtotal = $good_amount-$cartInfo['integral_price'];
                }
                $shop_amount += $good_subtotal;
                $ogrArr[] = array(//构建订单商品关联表需要保存的数据
                    'goods_id'=>$cartInfo['good_id'],//商品ID
                    'name'=>$cartInfo['good_name'],//商品名称
                    'price'=>$cartInfo['good_price'],//商品单价
                    'count'=> $cartInfo['number'],//商品数目
                    'integral' => $cartInfo['integral'],//用来抵扣的积分
                    'integral_price' => $cartInfo['integral_price'],//积分抵扣金额
                    'bank_pay' => $good_subtotal,//实际应付金额
                    'amount'=>$good_amount,//总金额
                );
                $shop_subtotal += $good_subtotal;//商家(订单)总金额
            }
            // 如果使用了优惠券
            if ($isUseYouHuiQuan){
                $couponsAmount=$youAmountArr['amout'];
                if($youAmountArr['type']==2){
                    $goodNum = 0;
                    foreach ($ogrArr as $value){
                        $goodNum += $value['count'];
                        // 商品数量大于1时抛出异常
                        if ($goodNum > 1){
                            $return['error'] = "购物车存在异常商品，无法结算！";
                            return $return;
                        }
                    }
                    $all_amount=$couponsAmount;
                    $bank_pay=$couponsAmount;
                }else{
                    $all_amount=$shop_amount-$couponsAmount;
                    $bank_pay=$shop_subtotal-$couponsAmount;
                }
            }else{
                $all_amount=$shop_amount;
                $bank_pay=$shop_subtotal;
                $couponsAmount=0;
            }
            $red_packet_pay = 0;
            if(isset($packetList[$shop_id]) && $packetList[$shop_id]>0){//商家有对应的红包
                if(!Yii::app()->config->SwitchgoodsRedPacket){
                    $return['error'] = "暂时不支持红包抵扣金额，无法结算！";
                    return $return;
                }
                //订单总金额减去红包抵扣金额
                $red_packet_pay = $packetList[$shop_id];
                $shop_subtotal -= $red_packet_pay;
            }
            $shopInfo = Shop::model()->findByPk($shop_id);
            //$profitList = self::getProfits($ogrArr);//通过订单下的商品集合获得分成信息
            //dump($ogrArr);

            $orderArr = array(
                'shop_id'=>$shop_id,
                'seller_id'=>$shop_id,//销售商ID=shop_id，刘总确认的，拆单按加盟商拆单
                'supplier_id'=>0,//商品的所有者(供应商).不展示
                'buyer_model'=>'customer',//买家模型
                'seller_contact'=> $shopInfo->contact,//卖家联系人
                'seller_tel'=> $shopInfo->mobile,//卖家电话
                'red_packet_pay'=>$red_packet_pay,//订单使用红包抵扣金额
                'amount'=>$all_amount+$freight,//订单总金额(包括红包金额,但扣除了商品积分抵扣金额)
                'bank_pay'=>$bank_pay+$freight,//实际应该支付金额
            );

            $return['result'][] = array('param'=>$orderArr,'relation'=>$ogrArr);
        }
        return $return;
    }




    static public function getProfits($goodsList = array(), $shop_id){
        $profitsList = array(
            'cwy_profit' => 0,//平台分成
            'shop_profit' => 0,//商家分成
            'am_profit' => 0,//客户经理分成
        );
        if(empty($goodsList)){
            return $profitsList;
        }
        dump($goodsList);
        /**
         * 平台：（商品单价*商品数量-积分抵扣金额）*分成比例 四舍五入
         * 商家：（商品单价*商品数量-积分抵扣金额）- 平台分成
         * 客户经理：注册在客户经理绑定楼栋业主在平台上消费的不含积分、红包的订单金额*平台分成比例*客户经理分成比例；
         * ps:客户经理的分成因为用户可以更换楼栋。所以用户变更楼栋导致客户经理的分成给了新的客户经理。
         * 以上用户变更导致的问题不属于我们的程序bug，我们不管。这条由王昊确认的。
         */
        foreach($goodsList as $list)
        {
            /**
             * @var $goods  Goods
             */
            if($goods = Goods::model()->findByPk($list['goods_id'])){
                $community_id = 1;
                $category_id = $goods->category_id;
                /**
                 * @var $relation   GoodsCategoryCommunityRelation
                 */
                if($relation = GoodsCategoryCommunityRelation::model()->findByAttributes(array('category_id' => $category_id, 'community_id' => $community_id))){
                    $percentage = $relation->percentage;
                }
            }
        }
    }

    /**
     * @param integer $community_id 小区ID
     * @param array $cartList 购物车数组
     * @param array $packetList 商家对应的红包
     * @return array 返回订单及商品的集合,及错误信息
     * 根据商家拆分购物车
     */
    public static function SplitCartByTicketMall($community_id,$cartList,$packetList=array(),$you_hui_quan_id,$freight){
        $return = array('result'=>array(),'error'=>"");
        if(empty($cartList) or !is_array($cartList)){
            $return['error'] = "购物车为空，无法结算";
            return $return;
        }
        $customer_id = Yii::app()->user->id;
        $customer = Customer::model()->findByPk($customer_id);
        if(empty($customer)){
            $return['error'] = "未知的用户！";
            return $return;
        }

        if ($customer->state == 1 || $customer->is_deleted == 1){
            $return['error'] = "用户已被禁用或删除！";
            return $return;
        }
        foreach($cartList as $shop_id=>$cartArr){
            if(empty($shop_id)){
                $return['error'] = "购物车存在异常商家，无法结算！";
                return $return;
            }
//            $scAttr = array('shop_id'=>$shop_id,'community_id'=>$community_id);
            //查询商家和小区的关联，看商家是否服务该小区
//            $scRelation = ShopCommunityRelation::model()->findByAttributes($scAttr);
//            if(empty($scRelation)){
//                $return['error'] = "购物车存在异常商家(商家不服务小区)，无法结算！";
//                return $return;
//            }

            $customer_amount = 0; //饭票总额
            $market_amount = 0; //现金总额
            $ogrArr = array();//用来保存订单-商品关联表的数据
            foreach($cartArr as $cartInfo){
                if(empty($cartInfo['goods_id'])){
                    $return['error'] = "购物车存在异常商品，无法结算！";
                    return $return;
                }
//                $attr = array('goods_id'=>$cartInfo['goods_id'],'shop_id'=>$shop_id,'community_id'=>$community_id);
                //获得商家小区商品销售表记录
//                $sell = ShopCommunityGoodsSell::model()->findByAttributes($attr);

                if(false) {// ( empty($sell) || (intval($sell->is_on_sale) !== Goods::SALE_YES) ) {
                    $return['error'] = "购物车存在异常商品(商品在小区不销售)，无法结算！";
                    return $return;
                }
                $ogrArr[] = array(//构建订单商品关联表需要保存的数据
                    'goods_id'=>$cartInfo['goods_id'],//商品ID
                    'name'=>$cartInfo['good_name'],//商品名称
                    'price'=>0,//商品单价
                    'count'=>$cartInfo['number'],//商品数目
                    'integral' => $cartInfo['integral'],//用来抵扣的积分
                    'integral_price' => $cartInfo['integral_price'],//积分抵扣金额
                    'bank_pay' => 0,//实际应付金额
                    'amount'=>0,//总金额
                );
                $customer_amount = bcadd($customer_amount, $cartInfo['per_customer_amount'], 2);
                $market_amount = bcadd($market_amount, $cartInfo['per_market_amount'], 2);
            }
            if(!empty($you_hui_quan_id) && $you_hui_quan_id != 0){
                $userCoupons = UserCoupons::model()->find("mobile=:mobile and you_hui_quan_id=:you_hui_quan_id and is_use=0",array(
                    ':mobile' => $customer->mobile,
                    ':you_hui_quan_id' => $you_hui_quan_id,
                ));
                if (empty($userCoupons)){
                    $return['error'] = "优惠券不存在！";
                    return $return;
                }

                $num=Order::model()->count('you_hui_quan_id=:you_hui_quan_id and buyer_id=:buyer_id and status=0',array(':you_hui_quan_id'=>$you_hui_quan_id,':buyer_id'=>$customer_id));
                $youAmountArr=YouHuiQuan::model()->findByPk($you_hui_quan_id);
                if($num>=$youAmountArr['limit_num']){
                    $sqlUpdate='UPDATE `order` SET `status`=97 WHERE `buyer_id`='.$customer_id.' AND `you_hui_quan_id`='.$you_hui_quan_id.' AND `status`=0';
                    $resultUpdate=Yii::app()->db->createCommand($sqlUpdate)->execute();
                    if(!$resultUpdate){
                        $return['error'] = "优惠券已使用,请去订单列表付款或联系客户进行取消订单！";
                        return $return;
                    }
                }
                $couponsAmount=$youAmountArr['amout'];
            }else{
                $couponsAmount=0;
            }
            $customer_amount = bcadd($customer_amount, $freight, 2);
            $customer_amount = bcsub($customer_amount, $couponsAmount, 2);
            $market_amount = bcadd($market_amount, $freight, 2);
            $market_amount = bcsub($market_amount, $couponsAmount, 2);
            $trade_amount = array(
                'customer_amount' => $customer_amount,
                'market_amount' => $market_amount
            );//商家(订单)总金额

            $red_packet_pay = 0;
            $shopInfo = Shop::model()->findByPk($shop_id);
            $orderArr = array(
                'shop_id'=>$shop_id, //商户ID
                'seller_id'=>$shop_id,
                'supplier_id'=>0,//商品的所有者(供应商).不展示
                'buyer_model'=>'customer',//买家模型
                'seller_contact'=> $shopInfo->contact,//卖家联系人
                'seller_tel'=> $shopInfo->mobile,//卖家电话
                'red_packet_pay'=>$red_packet_pay,//订单使用红包抵扣金额
                'trade_price' => json_encode(array('customer_amount' => $customer_amount,'market_amount' => $market_amount))
            );

            $return['result'][] = array('param'=>$orderArr,'relation'=>$ogrArr);
        }
        return $return;
    }



}
