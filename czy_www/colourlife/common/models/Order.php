<?php

/**
 * @property integer $id
 * @property string $sn
 * @property float $cwy_profit
 * @property float $shop_profit
 * @property float $am_profit
 * @property float $pay_rate
 * @property integer $payment_id
 * @property integer $status
 * @property Shop $seller
 * @property float  $payment_profit
 * Class Order
 */
class Order extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '订单';
    public $goods_id;
    public $count;
    public $disposal_desc;
    public $region;
    public $start_time;
    public $end_time;
    public $_supplierName;
    public $goodsName;
    public $categoryByOrder;
    public $buyer_address;
    public $buyer_mobile;
    public $income_pay_time;
    public $customer_buyer_name;
    public $remark;
    public $state;
    private static $oldStatus;
    public $pay_sn;
    public $one_yuan_code;
    public $you_hui_quan_id;


    public static function getStatusNames($select = false)
    {
        return CMap::mergeArray(array('' => '全部'), OrderStatus::getStatusNames());
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Order the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'order';
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'seller' => array(self::BELONGS_TO, 'Shop', 'seller_id'),
            'supplier' => array(self::BELONGS_TO, 'Shop', 'supplier_id'),
            'seller_buyer' => array(self::BELONGS_TO, 'Shop', 'buyer_id'),
            'customer_buyer' => array(self::BELONGS_TO, 'Customer', 'buyer_id'),
            'good_list' => array(self::HAS_MANY, 'OrderGoodsRelation', 'order_id'),
            'normal_good_list' => array(self::HAS_MANY, 'OrderGoodsRelation', 'order_id','condition'=>'state=0'),
            'logs' => array(self::HAS_MANY, 'OrderLog', 'order_id'),
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
            'build' => array(self::BELONGS_TO, 'Build', 'build_id'),
            'payment' => array(self::BELONGS_TO, 'Payment', 'payment_id'),
            'pay' => array(self::BELONGS_TO, 'pay', 'pay_id'),
            'you_hui_quan' => array(self::BELONGS_TO, 'YouHuiQuan', 'you_hui_quan_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'seller_id' => '卖家',
            'supplier_id' => '供应商家',
            'buyer_model' => '买家模型',
            'buyer_id' => '买家',
            'payment_id' => '支付方式',
            // 'expense_pay_id' => '退货支付',
            'buyer_name' => '收货人',
            'buyer_address' => '收货地址',
            'buyer_tel' => '收货电话',
            'buyer_postcode' => '收货邮编',
            'comment' => '买家留言',
            'seller_contact' => '卖家联系人',
            'seller_tel' => '卖家联系电话',
            'note' => '卖家备注',
            'create_time' => '下单时间',
            'create_ip' => '买家IP',
            'income_pay_time' => '付款时间',
            'amount' => '订单金额',
            'status' => '订单状态',
            'sn' => '订单号',
            'delivery_express_name' => '快递公司',
            'delivery_express_sn' => '快递单号',
            'disposal_desc' => '备注/说明',
            'sellerName' => '加盟商',
            'region' => '地区',
            'community' => '小区',
            // 'customer_buyer_name'=>'卖家'
            'payment_name' => '支付方式',
            'c_name' => '小区名称',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            '_supplierName' => '供应商',
            'goodsName' => '商品名称',
            'branch_id' => '管辖部门',
            'categoryByOrder' => '商品分类',
            'price' => '单价',
            'count' => '数量',
            'customer_mobile' => '买家联系方式',
            'supplier_tel' => '供应商联系方式',
            'buyer_mobile' => '买家电话',
            'customer_buyer_name' => '买家',
            'red_packet_pay' => '红包支付金额',
            'user_red_packet' => '是否使用红包',
            'remark'=>'备注',
            'pay_rate' => '费率',
            'credit' => '积分',
            'cwy_profit' => '平台提成',
            'am_profit' => '客户经理提成',
            'payment_profit' => '支付手续费',
            'integral'=>"积分抵扣",
            'pay_sn'=>'支付单号',
            'one_yuan_code'=>'使用优惠码',
            'you_hui_quan_id'=>'优惠券编码',
            'freight'=>'运费',
        );
    }


    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
            'IpBehavior' => array(
                'class' => 'common.components.behaviors.IpBehavior',
                'createAttribute' => 'create_ip',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    //根据订单得到订单里的所有商品名
    public function getGoodNameByOrder($order_id = '')
    {
        if ($order_id == '') {
            return "";
        } else {
            $order = Order::model()->findByPk($order_id);
            $goodNames = "";
            foreach ($order->good_list as $good) {
                $goodNames .= $good->name . "  ";
            }
            return $goodNames;
        }
    }

    //根据订单得到订单里的所有商品名和描述
    public function getGoodNameDescByOrder()
    {
        $goodNames = "";
        $goodDesc = "";
        foreach ($this->good_list as $good) {
            $goodNames .= $good->name . "  ";
            $goodDesc .= $good->description . "  ";
        }
        return $goodNames.htmlspecialchars($goodDesc);
    }

    //根据订单得到付款方式名字
    public function getPaymentNameByOrder($order_id = '')
    {
        if ($order_id == '') {
            return "";
        } else {
            $order = Payment::model()->findByPk($order_id);
            $paymentNames = "";
            $criteria = new CDbCriteria;
            $criteria->select = array('name');
            $criteria->compare('id', $order_id);
            $order = Payment::model()->findAll($criteria);

            foreach ($order as $pay) {
                $paymentNames .= $pay->name . "  ";
            }
            return $paymentNames;
        }
    }

    //ICE 根据订单得到小区名称
    public function getCommunityNameByOrder($order_id = '')
    {
        if($order_id == ''){
            return "";
        }else{
            $community = Community::model()->findByPk($order_id);
//            $communityNames = '';
//            $criteria = new CDbCriteria;
//            $criteria->select = array('name');
//            $criteria->compare('id',$order_id);
//            $community = Community::model()->findAll($criteria);
//            foreach ($community as $com) {
//                $communityNames .= $com->name . " ";
//            }
//            return $communityNames;
            if(!empty($community)){
                return $community['name'];
            }else{
                return '';
            }
        }
    }

    //根据订单得到订单下所有商品
    public function getGoodsByOrder($order_id = '')
    {
        if ($order_id == '') {
            return null;
        } else {
            $order = Order::model()->findByPk($order_id);
            return $order->good_list; //Goods::model()->findAllByPk($order->good_list);
        }
    }

    public function getBuyerName($buyerModel = null, $buyer_id = '')
    {
        $buyerModel = ucfirst(trim($buyerModel));
        if ($buyerModel == null || $buyer_id == '') {
            return "";
        } else {
            // $model = CActiveRecord::model($buyerModel)->findByPk($buyer_id);
            $model = $buyerModel::model()->findByPk($buyer_id);
            return empty($model) ? '' : (empty($model->name) ? (empty($model->username) ? '' : $model->username) : $model->name);
        }
    }

    /**商品别名
     * @return string
     */
    public function getGoods_brief()
    {
        return isset($this->good_list) ? (isset($this->good_list[0]->good) ? $this->good_list[0]->good->brief : '') : '';
    }

    /**商家名称
     * @return string
     */
    public function getShop_name()
    {
        return isset($this->seller) ? $this->seller->name : '';
    }

    /**商品价格
     * @return string
     */
    public function getGoods_price()
    {
        return empty($this->good_list) ? 0 : $this->good_list[0]->price;
    }

    /**商家电话
     * @return string
     */
    public function getShop_Tel()
    {
        return isset($this->seller) ? $this->seller->tel : '';
    }

    /*
     * 获取供应商电话
     * */
    public function getSupplierTel()
    {
        return isset($this->seller->tel) ? $this->seller->tel : "";
    }

    /*获取买家电话*/
    public function  getCustomerMobile($buyerModel = null, $buyer_id = '')
    {
        $buyerModel = ucfirst(trim($buyerModel));
        if ($buyerModel == null || $buyer_id == '') {
            return "";
        } else {

            $customer = $buyerModel::model()->findByPk($buyer_id);

            return isset($customer->mobile) ? $customer->mobile : "";
        }
    }

    public function getOrderName()
    {
        return '订单号:' . $this->sn;
    }

    /*获取商品数量*/
    public function getOrderCount()
    {
        return empty($this->good_list) ? 0 : $this->good_list[0]->count;
    }

    public function getSellerName()
    {
        return empty($this->seller) ? '' : $this->seller->name;
    }

    public function getSupplierName()
    {
        return empty($this->seller) ? '' : $this->seller->name;
    }

    public function getPaymentNames()
    {
        if($this->payment_id=="0"){
            return "无";
        }else{
            if(empty($this->payment)){
                return "无";
            }else{
                if(!empty($this->payment->name)){
                    return $this->payment->name;
                }else{
                    return "无";
                }
            }
        }
    }
    public function getNameHtml()
    {
        return @CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '电话:' . $this->seller->tel . '手机:' . $this->seller->mobile . '地址:' . $this->seller->address), $this->seller->name);
    }

    public function getBuyerNameHtml()
    {
        return @CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '电话:' .
            $this->seller_buyer->tel . '手机:' . $this->seller_buyer->mobile . '地址:' .
            $this->seller_buyer->address), $this->seller_buyer->name);
    }

    public function getSupplierHtml()
    {
        return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '电话:' . $this->seller->tel . '手机:' . $this->seller->mobile . '地址:' . $this->seller->address), $this->seller->name);
    }

    public function getBuyerHtml()
    {
        return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '电话:' . $this->buyer_tel . '地址:' . $this->buyer_address), $this->buyer_name);
    }

    /*
     * Hover显示订单记录
     * */
    public function getOrderLog($order_id)
    {
        return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '备注:' . $this->getAllOrderLog($order_id)), $this->statusName);
    }

    /*
     * 获取所有订单记录
     * */
    public function getAllOrderLog($order_id = "")
    {
        if ($order_id == "") {
            return "";
        } else {
            $criteria = new CDbCriteria;
            $criteria->select = "note";
            $criteria->compare("order_id", $order_id);
            $model = OrderLog::model()->findAll($criteria);
            if (isset($model)) {
                $notes = "";
                foreach ($model as $orderLogs) {
                    $notes .= $orderLogs->note;
                }
                return $notes;
            } else {
                return "";
            }
        }
    }


    //后台业主订单买家帐号
//  ICE 在customer修改完了
    public function getCustomerBuyerAccount()
    {
        if (!empty($this->customer_buyer))
            return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '姓名:' . $this->customer_buyer->name . '电话:' . $this->buyer_tel .
                '小区:' . $this->customer_buyer->communityRegionName . '地址:' . $this->buyer_address), $this->customer_buyer->username);
        else
            return '';
    }

    //后台业主订单买家信息
//  ICE 在customer修改完了
    public function getCustomerBuyerInfo()
    {
        if (!empty($this->customer_buyer))
            return '姓名:' . $this->customer_buyer->name . ' 电话:' . $this->buyer_tel .
            ' 小区:' . $this->customer_buyer->communityRegionName . ' 地址:' . $this->buyer_address;
        else
            return '';
    }

		//后台业主订单详细地址信息
//      ICE接入 小区地区的信息完成
		public function getCustomerBuyerAddress()
    {
				$customer = Customer::model()->findByPk($this->buyer_id);
				if (empty($customer)) {
						return "获取业主信息失败！";
				}
				$community_id = $this->community_id;
				$community = Community::model()->findByPk($community_id);
				if (empty($community_id) || empty($community)) {
						return "获取业主所在小区信息失败！";
				}
//              ICE icecommunity 修改完成
				$addressList = $community->getMyParentRegionNames();
				$address = implode("-", $addressList) . "-" . $community->name;
				$address .= "-" . (empty($customer->build) ? "" : $customer->build->name);
				$address .= "-" . $customer->room;
				return $address;
		}

    //后台加盟订单买家信息
    public function getSellerBuyerInfo()
    {
        if (!empty($this->seller_buyer))
            return '姓名:' . $this->seller_buyer->name . ' 电话:' . $this->buyer_tel .
            ' 管辖部门:' . $this->seller_buyer->shopAllBranch . ' 地址:' . $this->buyer_address;
        else
            return '';
    }


    public function  getStatusName()
    {
        return OrderStatus::getStatusName($this->status);
    }

    //获取商品图片
    public function getGoodsPic(){
        $arr=array();
        $sql="SELECT * FROM `order` `t` LEFT JOIN order_goods_relation po ON `t`.id=`po`.order_id WHERE `t`.sn='{$this->sn}';";
        $model=OrderGoodsRelation::model()->findAllBySql($sql);
        if(!empty($model)){
            foreach($model as $val){
                $data=Goods::model()->findByPk($val['goods_id']);
                $arr[]= Yii::app()->ajaxUploadImage->getUrl($data->good_image);
            }
        }
        return $arr;
    }
    /*
     * 获取支付方式列表
     * */
    public function getPaymentList()
    {
        $model = Payment::model()->online()->findAll();
        if (isset($model)) {
            $payment_list = array();
            foreach ($model as $list) {
                $payment_list[''] = "全部";
                $payment_list[$list->id] = $list->name;
            }
            return $payment_list;
        } else {
            return "";
        }
    }

    /*获取订单商品分类全路径*/
    public function getGoodsAllCategoryName($order_id = '')
    {
        if ($order_id == '') {
            return "";
        } else {
            $order = Order::model()->findByPk($order_id);
            // $goodNames = "";
            foreach ($order->good_list as $good) {
                $goods = Goods::model()->findByPk($good->goods_id);
                if (isset($goods)) {
                    foreach ($goods as $allGoods) {
                        $category_id = $goods->category_id;
                        return GoodsCategory::getMyParentCategoryName($category_id, true);
                    }
                } else {
                    return "";
                }
            }
        }
    }
    
    /*获取订单详情*/
    public function getOrderInfo($sn){
        return Order::model()->find('sn=:sn',array(':sn'=>$sn));
    }

    //获得订单下未退货的未被锁定的商品
    public function getNormalGoods(){
        $criteria = new CDbCriteria();
        $criteria->compare('order_id',$this->id);
        $criteria->compare('is_lock',Item::GOODS_UNLOCK);//未锁定的
        $criteria->compare('state',0);//必须是正常的。1=》已退货的
        return OrderGoodsRelation::model()->findAll($criteria);
    }

    /**
     * @param $goodIds 商品ID可传数组
     * @return bool
     */
    public function checkGoodsCanReturn($goodIds){
        if(!is_array($goodIds)){
            $goodIds = array($goodIds);
        }
        foreach($goodIds as $id){
            $ogRelation = OrderGoodsRelation::model()->findByAttributes(
                array('goods_id'=>$id,'order_id'=>$this->id)
            );
            if($ogRelation->is_lock == Item::GOODS_LOCK or $ogRelation->state==1){
                return false;
            }
        }
        return true;
    }

    //通过商品ID获得对应商品的总价
    public function getGoodsAmount($goodIds){
        $goodIds = is_array($goodIds)?$goodIds:array($goodIds);
        $amount = 0;
        foreach($goodIds as $goods_id){
            $ogRelation = OrderGoodsRelation::model()->findByAttributes(
                array('goods_id'=>$goods_id,'order_id'=>$this->id)
            );
            //保存选中商品的价格
            $amount += $ogRelation->bank_pay;
        }
        return $amount;
    }

    //获取商品
    public function getOrderGoods(){
        if(!empty($this->goods_list)){
            return "aaaaaaaaaa";
        }
    }

    public function getPay_rateName()
    {
        return $this->pay_rate.'%';
    }

    protected function beforeSave()
    {
        if('update' == $this->getScenario()){
            /**
             * @var Order   $customerOrder
             * @var integer $status
             */
            $customerOrder = self::model()->findByPk($this->id);
            self::$oldStatus = $customerOrder->status;//记录旧状态
        }
        return parent::beforeSave();
    }

    protected function afterSave()
    {
        if('update' == $this->getScenario()){
            $model = PayLib::get_model_by_sn($this->sn);
            switch(self::$oldStatus)
            {
                case Item::ORDER_AWAITING_PAYMENT://已下单，未付款
                    if('CustomerOrder' == $model and Item::ORDER_AWAITING_GOODS == $this->status){//未付款->已付款
                       // $this->sendSms('paid');//发送业主已付款短信
                        /**
                         * @var SmsComponent    $sms
                         */
                        //$sms = Yii::app()->sms;
                        //$sms->mobile = empty($this->seller) ? '' : $this->seller->mobile;
                        //$this->sendSms('paymentSuccess');//商家付款成功短信
                    }
                    break;
                case Item::ORDER_AWAITING_GOODS://已付款，待发货
                    if('CustomerOrder' == $model and Item::ORDER_AWAITING_CONFIRM == $this->status){
                       // $this->sendSms('ship');//通知业主商家发货短信
                    }
                    break;
                case Item::ORDER_AWAITING_CONFIRM://已发货，待收货
                    break;
                case Item::ORDER_TRANSACTION_SUCCESS://买家已收货
                    break;
                case Item::ORDER_TRANSACTION_SUCCESS_CLOSE://待结算
                    break;
                case Item::ORDER_BALANCE_DEDUCT_FAILED://交易失败
                    break;
                case Item::ORDER_CANCEL_CLOSE://取消订单
                    break;
            }
        }
        parent::afterSave();
    }

    public function sendSms($type)
    {
        /**
         * @var SmsComponent    $sms
         */
        $sms = Yii::app()->sms;
        $sms->sendGoodsOrdersMessage($type, $this->sn);
    }

        //查询订单商品的积分总数
    public function getOrderAllIntegral()
    {
        $discount = 0;
        if(!$this->good_list)
            return $discount;

        foreach($this->good_list as $goods)
        {
            $discount += $goods->integral;
        }
        return intval($discount);
    }

    //查询订单商品的积分抵扣价格
    public function getOrderAllIntegralPrice()
    {
        $discount = 0;
        $price=0;
        if(!$this->good_list)
            return $discount;

        foreach($this->good_list as $goods)
        {
            $discount += $goods->integral;
            $price += $goods->integral_price;
        }
       if($price==0){
            return 0;
       }else{
            return $discount . "分抵扣" . $price . "元";
       }
    }
    public function listAll($id)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('seller_id',$this->seller_id);
		$criteria->compare('supplier_id',$this->supplier_id);
		$criteria->compare('buyer_model',$this->buyer_model,true);
		$criteria->compare('buyer_id',$this->buyer_id);
		$criteria->compare('buyer_name',$this->buyer_name,true);
		$criteria->compare('buyer_address',$this->buyer_address,true);
		$criteria->compare('buyer_tel',$this->buyer_tel,true);
		$criteria->compare('buyer_postcode',$this->buyer_postcode,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('seller_contact',$this->seller_contact,true);
		$criteria->compare('seller_tel',$this->seller_tel,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('create_ip',$this->create_ip,true);
		$criteria->compare('income_pay_time',$this->income_pay_time);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('bank_pay',$this->bank_pay,true);
		$criteria->compare('pay_id',$this->pay_id);
		$criteria->compare('red_packet_pay',$this->red_packet_pay,true);
		$criteria->compare('user_red_packet',$this->user_red_packet);
		$criteria->compare('status',$this->status);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('delivery_express_name',$this->delivery_express_name,true);
		$criteria->compare('delivery_express_sn',$this->delivery_express_sn,true);
		$criteria->compare('return_express_name',$this->return_express_name,true);
		$criteria->compare('return_express_sn',$this->return_express_sn,true);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('build_id',$this->build_id);
		$criteria->compare('payment_id',$this->payment_id);
		$criteria->compare('is_lock',$this->is_lock);
		$criteria->compare('cwy_profit',$this->cwy_profit,true);
		$criteria->compare('am_profit',$this->am_profit,true);
		$criteria->compare('pay_rate',$this->pay_rate,true);
		$criteria->compare('payment_profit',$this->payment_profit,true);
		$criteria->compare('is_send',$this->is_send);
		$criteria->compare('one_yuan_code',$this->one_yuan_code,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('province',$this->province);
		$criteria->compare('city',$this->city);
		$criteria->compare('county',$this->county);
		$criteria->compare('town',$this->town);
		$criteria->compare('jd_order_id',$this->jd_order_id,true);
		$criteria->compare('you_hui_quan_id',$this->you_hui_quan_id);
        if(isset($id)){
            $criteria->with=array(  
                'you_hui_quan',  
            );
            $criteria->compare('you_hui_quan.id', $id, true);
        }
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 获取饭票现金价
	 * @return Ambigous <multitype:string , mixed>
	 */
	public function getTradePrice(){
        if($this->pingtai_id == 1000)
        {
            if (!empty($this->trade_price)){
                $tradePice = json_decode($this->trade_price,true);
            }else {
                $tradePice = array(
                    'customer_amount' => '0.00',
                    'market_amount' => '0.00'
                );
            }
        }else{
            $tradePice = array(
                'customer_amount' => $this->amount,
                'market_amount' => $this->amount
            );
        }
		return $tradePice;
	}
}
