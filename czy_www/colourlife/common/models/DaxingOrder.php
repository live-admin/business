<?php

/**
 * This is the model class for table "daxing_order".
 *
 * The followings are the available columns in table 'daxing_order':
 * @property integer $oid
 * @property string $osn
 * @property integer $uid
 * @property integer $orderstate
 * @property string $productamount
 * @property string $orderamount
 * @property string $addtime
 * @property string $shipsn
 * @property string $shipsystemname
 * @property string $shiptime
 * @property string $paysn
 * @property string $paysystemname
 * @property integer $paymode
 * @property string $paytime
 * @property string $consignee
 * @property string $mobile
 * @property string $zipcode
 * @property string $address
 * @property string $shipfee
 * @property string $payfee
 * @property string $csn
 */
class DaxingOrder extends CActiveRecord
{
	public $modelName = '大兴汽车订单';
	public $sn;
	public $status;
	public $orderStatus_arr = array(
		"10" => "已提交",
		"30" => "等待付款",
		"50" => "确认中",
		"70" => "已确认",
		"90" => "备货中",
		"110" => "已发货",
		"140" => "已完成",
		"160" => "退货",
		"180" => "锁定",
		"200" => "取消",
	);
	static $third_status_arr=array(
		Item::FEES_AWAITING_PAYMENT => "待付款",
		Item::FEES_TRANSACTION_ERROR => "已付款",
		Item::FEES_TRANSACTION_SUCCESS => "交易成功",
		Item::FEES_TRANSACTION_REFUND => "退款",
		Item::FEES_TRANSACTION_LACK => "交易失败(饭票余额不足)",
		Item::FEES_TRANSACTION_FAIL => "交易失败",

	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'daxing_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('addtime, shiptime, paytime', 'required'),
			array('uid, orderstate, paymode', 'numerical', 'integerOnly'=>true),
			array('osn, shipsn, shipsystemname, paysn, paysystemname', 'length', 'max'=>30),
			array('productamount, orderamount, shipfee, payfee', 'length', 'max'=>10),
			array('consignee', 'length', 'max'=>20),
			array('mobile', 'length', 'max'=>15),
			array('zipcode', 'length', 'max'=>6),
			array('address', 'length', 'max'=>150),
			array('csn', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('oid, osn, uid, orderstate, productamount, orderamount, addtime, shipsn, shipsystemname, shiptime, paysn, paysystemname, paymode, paytime, consignee, mobile, zipcode, address, shipfee, payfee, csn,cSn,sn,status', 'safe', 'on'=>'search'),
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
			'thirdFees' => array(self::HAS_ONE, 'ThirdFees', array('sn'=>'csn')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'oid' => 'id',
			'osn' => '大兴订单编号',
			'uid' => '订单状态',
			'orderstate' => '订单状态',
			'productamount' => '商品合计',
			'orderamount' => '订单合计',
			'addtime' => '添加时间',
			'shipsn' => '配送单号',
			'shipsystemname' => '配送方式系统名',
			'shiptime' => '配送时间',
			'paysn' => '支付单号',
			'paysystemname' => '支付方式系统名',
			'paymode' => '支付方式',
			'paytime' => '支付时间',
			'consignee' => '收货人',
			'mobile' => '手机号',
			'zipcode' => '邮政编码',
			'address' => '详细地址',
			'shipfee' => '配送费用',
			'payfee' => '支付费用',
			'csn' => '彩生活订单ID',
			'sn' => '订单号',
			'amount' => '总金额(彩之云)',
			'bank_pay' => '实付金额',
			'red_packet_pay' => '红包抵扣',
			'status' => '支付状态',
			'payment_id'=>'支付方式',
			'create_time'=>'下单时间',
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
		if(isset($this->cSn)){
			$criteria->with=array(
				'thirdFees',
			);
			$criteria->compare('thirdFees.cSn', $this->cSn, true);
		}
		if(isset($this->sn)){
			$criteria->with=array(
				'thirdFees',
			);
			$criteria->compare('thirdFees.sn', $this->sn, true);
		}
		if(isset($this->status)){
			$criteria->with=array(
				'thirdFees',
			);
			$criteria->compare('thirdFees.status', $this->status, true);
		}

		$criteria->compare('oid',$this->oid);
		$criteria->compare('osn',$this->osn,true);
		$criteria->compare('uid',$this->uid);
		$criteria->compare('orderstate',$this->orderstate);
		$criteria->compare('productamount',$this->productamount,true);
		$criteria->compare('orderamount',$this->orderamount,true);
		$criteria->compare('addtime',$this->addtime,true);
		$criteria->compare('shipsn',$this->shipsn,true);
		$criteria->compare('shipsystemname',$this->shipsystemname,true);
		$criteria->compare('shiptime',$this->shiptime,true);
		$criteria->compare('paysn',$this->paysn,true);
		$criteria->compare('paysystemname',$this->paysystemname,true);
		$criteria->compare('paymode',$this->paymode);
		$criteria->compare('paytime',$this->paytime,true);
		$criteria->compare('consignee',$this->consignee,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('zipcode',$this->zipcode,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('shipfee',$this->shipfee,true);
		$criteria->compare('payfee',$this->payfee,true);
		$criteria->compare('csn',$this->csn,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DaxingOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
    * @version 获取订单号
    */
	public function getSn(){
		if(!empty($this->thirdFees)){
			return $this->thirdFees->sn;
		}
	}

	/*
     * @version 获取订单的状态
     */
	public function getOrderState(){
		return $this->orderStatus_arr[$this->orderstate];
	}

	public function getAmount(){
		if(!empty($this->thirdFees)){
			return $this->thirdFees->amount;
		}
	}
	/*
         * @version 获取彩之云的实际金额
         */
	public function getBankPay(){
		if(!empty($this->thirdFees)){
			return $this->thirdFees->bank_pay;
		}
	}

	/*
     *@version 获取彩之云的红包抵扣
     */
	public function getRedPacketPay(){
		if(!empty($this->thirdFees)){
			return $this->thirdFees->red_packet_pay;
		}
	}

	/*
     * @version 获取彩之云的支付状态
     */
	public function getPayStatus(){
		if(!empty($this->thirdFees)){
			return self::$third_status_arr[$this->thirdFees->status];
		}
	}

	/*
    * @version 获取支付方式名称
    */
	public function getPayMethodName(){
		if(!empty($this->thirdFees)){
			$resultArr=Payment::model()->findByPk($this->thirdFees->payment_id);
			return $resultArr['name'];
		}
	}
	/*
     * @version 获取彩之云的支付方式id
     */
	public function getPaymentId(){
		if(!empty($this->thirdFees)){
			return $this->thirdFees->payment_id;
		}
	}

	/*
     * @version 获取彩之云的下单时间
     */
	public function getCreateTime(){
		if(!empty($this->thirdFees)){
			return $this->thirdFees->create_time;
		}
	}
}
