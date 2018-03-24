<?php

/**
 * This is the model class for table "chedada".
 *
 * The followings are the available columns in table 'chedada':
 * @property integer $id
 * @property string $orderId
 * @property string $mobile
 * @property string $orderTime
 * @property string $state
 * @property string $address
 * @property string $price
 */
class Chedada extends CActiveRecord
{
	public $modelName = '车大大订单';
	public $sn;
	public $status;
	public $orderStatus_arr = array(
		"D" => "待审核",
		"L" => "已审核",
		"C" => "已结束",
		"S" => "待退款",
		"Q" => "已通知退款",
	);
	static $third_status_arr=array(
		Item::FEES_AWAITING_PAYMENT => "待付款",
		Item::FEES_TRANSACTION_ERROR => "已付款",
		Item::FEES_TRANSACTION_SUCCESS => "交易成功",
		Item::FEES_TRANSACTION_REFUND => "退款",
		Item::FEES_TRANSACTION_LACK => "交易失败(饭票余额不足)",
		Item::FEES_TRANSACTION_FAIL => "交易失败",
		Item::FEES_PART_REFUND => "部分退款",

	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'chedada';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('orderId, mobile, orderTime, state, address, price', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, orderId, mobile, orderTime, state, address, price,cSn,sn,status, updateTime, refundMoney', 'safe', 'on'=>'search'),
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
			'thirdFees' => array(self::HAS_ONE, 'ThirdFees', array('cSn'=>'orderId')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'orderId' => '车大大订单编号',
			'mobile' => '车主手机号',
			'orderTime' => '预约日期',
			'state' => '订单状态',
			'address' => '地址',
			'price' => '金额',
			'sn' => '订单号',
			'amount' => '总金额(彩之云)',
			'bank_pay' => '实付金额',
			'red_packet_pay' => '红包抵扣',
			'status' => '支付状态',
			'payment_id'=>'支付方式',
			'create_time'=>'下单时间',
			'updateTime' => '更新时间',
			'refundMoney' => '退款金额',
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
		$criteria->compare('orderId',$this->orderId,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('orderTime',$this->orderTime,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('updateTime',$this->updateTime);
		$criteria->compare('refundMoney',$this->refundMoney,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Chedada the static model class
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
		return $this->orderStatus_arr[$this->state];
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

	/*
     * @version 获取用户ID
     */
	public function getCustomerId(){
		if(!empty($this->thirdFees)){
			return $this->thirdFees->customer_id;
		}
	}

	/*
     * @version 获取ThirdFessId
     */
	public function getThirdFessId(){
		if(!empty($this->thirdFees)){
			return $this->thirdFees->id;
		}
	}
}
