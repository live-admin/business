<?php

/**
 * This is the model class for table "purchase_order".
 *
 * The followings are the available columns in table 'purchase_order':
 * @property integer $id
 * @property string $sn
 * @property integer $type
 * @property integer $shop_id
 * @property integer $employee_id
 * @property string $buyer_name
 * @property string $buyer_address
 * @property string $buyer_tel
 * @property string $buyer_postcode
 * @property string $comment
 * @property string $seller_contact
 * @property string $seller_tel
 * @property string $note
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $pay_time
 * @property string $amount
 * @property string $bank_pay
 * @property integer $pay_id
 * @property integer $status
 * @property integer $update_time
 * @property integer $community_id
 * @property integer $payment_id
 * @property integer $colorcloud_approval_id
 * @property integer $receipt_type
 * @property string $invoice_title
 * @property float  $pay_rate
 */
class PurchaseOrder extends CActiveRecord
{
    public $modelName = '采购订单';

    const PERSONAL = 0;//个人类型
    const COMPANY  = 1; //公司类型

    public $employee_name;
    public $shop_name;
    public $payment_name;
    public $remark;
    public $pay_sn;
    public $end_time;
    public $start_time;
    public $integral;
    public $category_id;
    public $category;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, shop_id, employee_id, create_time, pay_time, pay_id, status, update_time, community_id, payment_id, colorcloud_approval_id, receipt_type', 'numerical', 'integerOnly'=>true),
			array('sn', 'length', 'max'=>32),
			array('buyer_name, seller_contact', 'length', 'max'=>255),
			array('buyer_tel, buyer_postcode, seller_tel', 'length', 'max'=>100),
			array('create_ip, invoice_title', 'length', 'max'=>50),
			array('amount, bank_pay', 'length', 'max'=>10),
            array('buyer_address', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sn, type, category_id,shop_name,integral,employee_name,shop_id, employee_id, buyer_name, buyer_address, buyer_tel, buyer_postcode, comment, seller_contact, seller_tel, note, create_time, create_ip, pay_time, amount, bank_pay, pay_id, status, update_time, community_id, payment_id, colorcloud_approval_id, receipt_type, invoice_title, pay_rate,pay_sn,start_time,end_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'good_list' => array(self::HAS_MANY, 'PurchaseOrderGoodsRelation', 'order_id'),
            'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
            'logs' => array(self::HAS_MANY, 'PurchaseOrderLog', 'order_id'),
            'payment' => array(self::BELONGS_TO, 'Payment', 'payment_id'),
            'pay' => array(self::BELONGS_TO, 'Pay', 'pay_id'),
            'employee_buyer'=> array(self::BELONGS_TO, 'Employee', 'employee_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => 'Sn',
			'type' => 'Type',
			'shop_id' => '商家',
			'employee_id' => '员工',
			'buyer_name' => '买家姓名',
			'buyer_address' => '买家地址',
			'buyer_tel' => '买家电话',
			'buyer_postcode' => '买家邮编',
			'comment' => '评论',
			'seller_contact' => '卖家留言',
			'seller_tel' => '卖家电话',
			'note' => '买家备注',
			'create_time' => '创建时间',
			'create_ip' => '创建IP',
			'pay_time' => '支付时间',
			'amount' => '总金额',
			'bank_pay' => '实际支付',
			'pay_id' => '付款id',
			'status' => '状态',
			'update_time' => '修改时间',
			'community_id' => '小区',
			'payment_id' => '支付方式',
			'shop_name' => '商家',
			'employee_name' => '员工',
			'payment_name' => '支付方式',
            'colorcloud_approval_id' => '彩之云审批单号',
            'receipt_type' => '发票类型',
            'invoice_title' => '发票抬头',
            'remark'=>'备注',
            'delivery_express_name' => '快递公司',
            'delivery_express_sn' => '快递单号',
            'pay_rate' => '支付费率',
            'pay_sn'=>'支付单号',
            'start_time'=>'开始时间',
            'end_time'=>'结束时间',
            'type'=>'类型',
            'integral'=>'是否使用积分',
            'category_id'=>'商品分类'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('employee_id',$this->employee_id);
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
		$criteria->compare('pay_time',$this->pay_time);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('bank_pay',$this->bank_pay,true);
		$criteria->compare('pay_id',$this->pay_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('payment_id',$this->payment_id);
        $criteria->compare('colorcloud_approval_id',$this->colorcloud_approval_id);
        $criteria->compare('receipt_type',$this->receipt_type);
        $criteria->compare('invoice_title',$this->invoice_title);

		return new CActiveDataProvider($this, array(
            'sort' => array(
                'defaultOrder' => 't.create_time DESC', //设置默认排序是create_time倒序
            ),
			'criteria'=>$criteria,
		));
	}

	public function search_backend_order()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('buyer_name',$this->buyer_name,true);
        $criteria->compare("buyer_tel",$this->buyer_tel,true);
        $criteria->compare("type",$this->type);
        $criteria->compare("status",$this->status);
        if($this->integral!=""){
            if($this->integral==0){
                $criteria->addCondition("amount=bank_pay");
            }
            if($this->integral==1){
                $criteria->addCondition("amount>bank_pay");
            }
        }
        if (!empty($this->category_id)) {
            $goodList = PurchaseOrderGoodsRelation::model()->findAll();
            $goodsIds = array();
            foreach ($goodList as $gModel) {
                if ($gModel->good) {
                    $categoryIds = PurchaseGoodsCategory::model()->findByPk($this->category_id)->getChildrenIdsAndSelf();
                    if (in_array($gModel->good->category_id, $categoryIds)) {
                        $goodsIds[] = $gModel->good->id;
                    }
                }
            }
            $criteria->addInCondition('t.id', $goodsIds);
        }
        if ($this->start_time != "") {
            $criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }
        if($this->pay_sn!=""){
            $pay=Pay::model()->getModel($this->pay_sn);
            if(!empty($pay)){
                $pay_id=$pay->id;
                $criteria->compare("`pay_id`",$pay_id);
            }else{
                $criteria->compare("`pay_id`","-1");
            }

        }
        return new CActiveDataProvider($this, array(
            'sort' => array(
                'defaultOrder' => 't.create_time DESC', //设置默认排序是create_time倒序
            ),
            'criteria'=>$criteria,
        ));
	}
	public function search_my_order()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('sn',$this->sn,true);
        $criteria->compare('buyer_name',$this->buyer_name,true);
        $criteria->compare("buyer_tel",$this->buyer_tel,true);
        $criteria->compare("type",$this->type);
        $criteria->compare("status",$this->status);
        if($this->integral!=""){
            if($this->integral==0){
                $criteria->addCondition("amount=bank_pay");
            }
            if($this->integral==1){
                $criteria->addCondition("amount>bank_pay");
            }
        }
        if (!empty($this->category_id)) {
            $goodList = PurchaseOrderGoodsRelation::model()->findAll();
            $goodsIds = array();
            foreach ($goodList as $gModel) {
                if ($gModel->good) {
                    $categoryIds = PurchaseGoodsCategory::model()->findByPk($this->category_id)->getChildrenIdsAndSelf();
                    if (in_array($gModel->good->category_id, $categoryIds)) {
                        $goodsIds[] = $gModel->good->id;
                    }
                }
            }
            $criteria->addInCondition('t.id', $goodsIds);
        }

        if ($this->start_time != "") {
            $criteria->addCondition('`t`.create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('`t`.create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }
        if($this->pay_sn!=""){
            $pay=Pay::model()->getModel($this->pay_sn);
            if(!empty($pay)){
                $pay_id=$pay->id;
                $criteria->compare("`pay_id`",$pay_id);
            }else{
                $criteria->compare("`pay_id`","-1");
            }

        }
		$criteria->compare('employee_id',Yii::app()->user->id);
        return new CActiveDataProvider($this, array(
            'sort' => array(
                'defaultOrder' => 't.create_time DESC', //设置默认排序是create_time倒序
            ),
            'criteria'=>$criteria,
        ));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PurchaseOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    //根据订单得到付款方式名字
    public function getPaymentName()
    {
  /*      if((empty($this->payment) && $this->status==Item::ORDER_PURCHASE_GOODS) || $this->type==1){
            return "无";
        }*/
        if($this->status==Item::ORDER_PURCHASE_PAYMENT){
            return '未支付';
        }
        if(($this->bank_pay==0) || $this->type==1){
            return "无";
        }

        return empty($this->payment)?'未支付':$this->payment->name;
    }

    
    public function getShopName()
    {
        return empty($this->shop)?'':$this->shop->name;
    }

    //根据订单得到订单下所有商品
    public function getGoodsByOrder($order_id = '')
    {
        if ($order_id == '') {
            return null;
        } else {
            $order = purchaseOrder::model()->findByPk($order_id);
            return $order->good_list; //Goods::model()->findAllByPk($order->good_list);
        }
    }
    //根据订单得到订单下所有商品
    public function getGoodsByOrderSn($order_sn = '')
    {
        if ($order_sn == '') {
            return null;
        } else {
            $order = purchaseOrder::model()->find('sn=:sn',array(':sn'=>$order_sn));
            return $order->good_list; //Goods::model()->findAllByPk($order->good_list);
        }
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => false,
            ),
        );
    }

    //修改为已提交审批
    public function updateStatusApprovalStart()
    {
        $this->status = Item::ORDER_PURCHASE_APPROVAL_START;
        return $this->updateStatus($this->status);
    }

    //修改为已提交审批
    public function updateStatusApprovalSuccess()
    {
        $this->status = Item::ORDER_PURCHASE_APPROVAL_SUCCESS;
        return $this->updateStatus($this->status);
    }

    //修改订单状态
    private  function updateStatus($status)
    {
        $status = intval($status);
        $this->status = $status;
        $note = '内部采购订单'.$this->sn.'修改状态为'.$status;

        if($this->update() && PurchaseOrderLog::createOrderLog($this->id,$status,$note))
            return true;

        return false;
    }

    //修改订单状态
    public function updateApprovalID($id)
    {
        $id = intval($id);    //修改订单状态
        $this->colorcloud_approval_id = $id;
        return $this->update();
    }

    //根据审批单号查询得到订单模型
    public function getApprovalModel($approval_id)
    {
        return $this->findByAttributes(array('colorcloud_approval_id' => $approval_id));
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

    //获得订单下未退货的未被锁定的商品
    public function getNormalGoods(){
        $criteria = new CDbCriteria();
        $criteria->compare('order_id',$this->id);
        $criteria->compare('is_lock',Item::GOODS_UNLOCK);//未锁定的
        $criteria->compare('state',0);//必须是正常的。1=》已退货的
        return PurchaseOrderGoodsRelation::model()->findAll($criteria);
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
            $ogRelation = PurchaseOrderGoodsRelation::model()->findByAttributes(
                array('goods_id'=>$id,'order_id'=>$this->id)
            );
            if($ogRelation->is_lock == Item::GOODS_LOCK or $ogRelation->state==1){
                return false;
            }
        }
        return true;
    }

    public function getPay_rateName()
    {
        return $this->pay_rate.'%';
    }

    public function getStateName(){
        $arr=PurchaseOrderStatus::getStatusNames();
        if(isset($arr[$this->status])){
            return $arr[$this->status];
        }else{
            return "";
        }
    }


    public function getAllStateName(){
        $arr=PurchaseOrderStatus::getStatusNames();
        return $arr;
    }

    //获取商品图片
    public function getGoodsPic(){
        $arr=array();
        $sql="SELECT * FROM purchase_order `t` LEFT JOIN purchase_order_goods_relation po ON `t`.id=`po`.order_id WHERE `t`.sn='{$this->sn}';";
        $model=PurchaseOrderGoodsRelation::model()->findAllBySql($sql);
        if(!empty($model)){
            foreach($model as $val){
                $data=PurchaseGoods::model()->findByPk($val['goods_id']);
                $arr[]= Yii::app()->ajaxUploadImage->getUrl($data->good_image);
            }
        }
        return $arr;
    }

    //获取订单的商品数量
    public function getOrderGoodsNum(){
        $num=0;
        if(isset($this->good_list)){
            foreach($this->good_list as $val){
                $num+=$val['count'];
            }
        }
        return $num;
    }

    //根据订单得到发票类型
    public function getReceiptType()
    {
        if($this->receipt_type==1) {
            return '个人发票';
        }
        return '公司发票';
    }

  //根据订单得到发票信息
    public function getInvoiceInfo()
    {
        if(empty($this->receipt_type)){
            return "无";
        }

        return '发票类型:'.$this->getReceiptType().'  /   发票抬头:'.$this->invoice_title;
    }
}
