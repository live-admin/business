<?php

/**
 * This is the model class for table "hua_order".
 *
 * The followings are the available columns in table 'hua_order':
 * @property integer $id
 * @property string $uid
 * @property string $Csn
 * @property string $orderid
 * @property string $receiver
 * @property string $ProvinceCity
 * @property string $addressDetail
 * @property string $contact
 * @property string $sendTime
 * @property string $sendPeriod
 * @property string $productDetails
 * @property string $totalFee
 * @property string $orderStatus
 */
class HuaOrder extends CActiveRecord
{
	public $modelName = '花礼网订单';
	public $sn;
	public $status;
	public $orderStatus_arr = array(
		"待付款" => "待付款",
		"已付款" => "已付款",
		"处理中" => "处理中",
		"送货完毕" => "送货完毕",
		"取消订单" => "取消订单",
		"申请退款" => "申请退款",
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
		return 'hua_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sendTime', 'required'),
			array('uid, Csn, orderid, receiver, ProvinceCity, contact, orderStatus', 'length', 'max'=>100),
			array('addressDetail', 'length', 'max'=>1000),
			array('sendPeriod', 'length', 'max'=>20),
			array('productDetails', 'length', 'max'=>500),
			array('totalFee', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, Csn, orderid, receiver, ProvinceCity, addressDetail, contact, sendTime, sendPeriod, productDetails, totalFee, orderStatus,sn,status', 'safe', 'on'=>'search'),
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
			'thirdFees' => array(self::HAS_ONE, 'ThirdFees', array('cSn'=>'orderid')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => '彩生活用户ID',
			'Csn' => '彩生活订单号',
			'orderid' => '花礼网订单号',
			'receiver' => '接收人',
			'ProvinceCity' => '省市',
			'addressDetail' => '详细地址',
			'contact' => '联系方式',
			'sendTime' => '配送时间',
			'sendPeriod' => '配送时段',
			'productDetails' => '商品详情',
			'totalFee' => '总价格',
			'orderStatus' => '订单状态',
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
		$criteria->compare('id',$this->id);
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('t.Csn',$this->Csn,true);
		$criteria->compare('orderid',$this->orderid,true);
		$criteria->compare('receiver',$this->receiver,true);
		$criteria->compare('ProvinceCity',$this->ProvinceCity,true);
		$criteria->compare('addressDetail',$this->addressDetail,true);
		$criteria->compare('contact',$this->contact,true);
		$criteria->compare('sendTime',$this->sendTime,true);
		$criteria->compare('sendPeriod',$this->sendPeriod,true);
		$criteria->compare('productDetails',$this->productDetails,true);
		$criteria->compare('totalFee',$this->totalFee,true);
		$criteria->compare('orderStatus',$this->orderStatus,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HuaOrder the static model class
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

	public function getOrderState(){
		return $this->orderStatus_arr[$this->orderStatus];
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
