<?php

class CustomerOrderPresent extends BaseOrder
{
    public $modelName = '业主订单礼品';
    public $goods_id;
    public $count;


    // 大闸蟹商品ID：1295，1291，1299，1296，1297，1293，1298，1300，1292，1301，1302，1294
    // 牛奶商品ID:1388,1389,1390,1391,1392,1393,1394,1395,1396,1397,1398,1399,1400,1401,1402
    static $LuckyGoodId = '1250,1233,1212,1213,1230,1231,1232,1234,1235,1236,1237,1238,1239,1240,1241,1242,1243,1248,1249,1244,1245,1246,1247,1295,1291,1299,1296,1297,1293,1298,1300,1292,1301,1302,1294,1388,1389,1390,1391,1392,1393,1394,1395,1396,1397,1398,1399,1400,1401,1402,1544,1545,1546,1547,1629,1630,1631,1632,1633,985,1645,1646,1647,1648,1650,1651,1652,1653';
    static $RedPacketGoodId = '1250,1232,1236,1242,1241';

    public function getSendLuckyType($id)
    {
        $model = OrderSendPresent::model()->find('type=2 AND order_id='.$id);
        if($model){
            return $model->num;
        }else{
            return '0';
        }
    }

    public function getSendRedPacketType($id)
    {
        $model = OrderSendPresent::model()->find('type=1 AND order_id='.$id);
        if($model){
            return $model->num;
        }else{
            return '0';
        }
    }

    public function search_backend_order_send_lucky_times()
    {
        $criteria = new CDbCriteria;

        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        $branchIds = $employee->mergeBranch;
        //选择的组织架构ID
        if ($this->branch_id != '')
            $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
        else if (!empty($this->communityIds)) //如果有小区
            $community_ids = $this->communityIds;
        else if ($this->region != '') //如果有地区
            $community_ids = Region::model()->getRegionCommunity($this->region, 'id');
        else {
            $community_ids = array();
            foreach ($branchIds as $branchId) {
                $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
                $community_ids = array_unique(array_merge($community_ids, $data));
            }
        }

        $criteria->addInCondition('`t`.community_id', $community_ids);

        $criteria->with[] = "customer_buyer";
        $criteria->compare("customer_buyer.name", $this->customer_buyer_name, true);
        $criteria->compare('user_red_packet',$this->user_red_packet);
        /*if(1 == $this->user_red_packet){
            $criteria->addCondition('red_packet_pay > 0');
        }*/
        // $criteria->with[] = 'seller';
        // $criteria->compare("seller.name", $this->sellerName, true);
        $criteria->with[] = "supplier";
        $criteria->compare("supplier.name", $this->seller_id, true);
        $criteria->compare('`t`.buyer_name', $this->buyer_name, true);
        $criteria->compare('`t`.status', $this->status);
        $criteria->compare('`t`.buyer_model', 'customer', true);
        $criteria->compare('`t`.sn', $this->sn, true);

        $criteria->join = "left join `order_goods_relation` ogr on ogr.order_id=`t`.id";
        $criteria->compare("ogr.name", $this->goodsName, true);
        if ($this->start_time != "") {
            $criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }

        $goodList = OrderGoodsRelation::model()->findAll('goods_id IN ('.self::$LuckyGoodId.')');
        $orderIds = array();
        foreach ($goodList as $gModel) {
            $orderIds[] = $gModel->order_id;
        }
        $criteria->addInCondition('t.id', $orderIds);
        $criteria->addInCondition('t.status', array(1,3,4,99));

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => '`t`.create_time desc',
            )
        ));
    }


    public function search_backend_order_send_red_packet()
    {
        $criteria = new CDbCriteria;

        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        $branchIds = $employee->mergeBranch;
        //选择的组织架构ID
        if ($this->branch_id != '')
            $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
        else if (!empty($this->communityIds)) //如果有小区
            $community_ids = $this->communityIds;
        else if ($this->region != '') //如果有地区
            $community_ids = Region::model()->getRegionCommunity($this->region, 'id');
        else {
            $community_ids = array();
            foreach ($branchIds as $branchId) {
                $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
                $community_ids = array_unique(array_merge($community_ids, $data));
            }
        }

        $criteria->addInCondition('`t`.community_id', $community_ids);

        $criteria->with[] = "customer_buyer";
        $criteria->compare("customer_buyer.name", $this->customer_buyer_name, true);
        $criteria->compare('user_red_packet',$this->user_red_packet);
        /*if(1 == $this->user_red_packet){
            $criteria->addCondition('red_packet_pay > 0');
        }*/
        // $criteria->with[] = 'seller';
        // $criteria->compare("seller.name", $this->sellerName, true);
        $criteria->with[] = "supplier";
        $criteria->compare("supplier.name", $this->seller_id, true);
        $criteria->compare('`t`.buyer_name', $this->buyer_name, true);
        $criteria->compare('`t`.status', $this->status);
        $criteria->compare('`t`.buyer_model', 'customer', true);
        $criteria->compare('`t`.sn', $this->sn, true);

        $criteria->join = "left join `order_goods_relation` ogr on ogr.order_id=`t`.id";
        $criteria->compare("ogr.name", $this->goodsName, true);
        if ($this->start_time != "") {
            $criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }

        $goodList = OrderGoodsRelation::model()->findAll('goods_id IN ('.self::$RedPacketGoodId.')');
        $orderIds = array();
        foreach ($goodList as $gModel) {
            $orderIds[] = $gModel->order_id;
        }
        $criteria->addInCondition('t.id', $orderIds);
        $criteria->addInCondition('t.status', array(99));
        $criteria->addCondition("t.amount>100");

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => '`t`.create_time desc',
            )
        ));
    }

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
            if(@Yii::app()->config->SwitchGoodsRedPacket && $this->red_packet_pay>0 && $this->red_packet_pay==$this->amount)//如果用户使用红包全额支付
                PayLib::order_paid($this->sn,0,'商品全额红包支付');

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
        $balance = $this->customer_buyer->getBalance();//用户红包余额
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

}
