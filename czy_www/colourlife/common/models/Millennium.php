<?php

/**
 * This is the model class for table "millennium".
 *
 * The followings are the available columns in table 'millennium':
 * @property integer $id
 * @property integer $Uid
 * @property string $colourSn
 * @property string $orderId
 * @property string $receiver
 * @property string $address
 * @property string $contact
 * @property string $addTime
 * @property string $totalFee
 * @property string $voucherPrice
 * @property string $voucherCode
 * @property string $statusTxt
 * @property string $title
 * @property string $price
 * @property string $payPrice
 * @property string $totalPrice
 * @property string $num
 * @property string $createTime
 * @property string $updateTime
 */
class Millennium extends CActiveRecord
{
	public $modelName = '千禧订单';
	public $sn;
	public $status;

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
		return 'millennium';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Uid, colourSn, orderId, receiver, address, contact, addTime, totalFee, voucherPrice, voucherCode, statusTxt, title, price, payPrice, totalPrice, num', 'required'),
			array('Uid', 'numerical', 'integerOnly'=>true),
			array('colourSn, orderId, receiver, address, contact, addTime, totalFee, voucherPrice, voucherCode, statusTxt, title, price, payPrice, totalPrice, num, createTime, updateTime', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, Uid, colourSn, orderId, receiver, address, contact, addTime, totalFee, voucherPrice, voucherCode, statusTxt, title, price, payPrice, totalPrice, num, createTime, updateTime,cSn,sn,status', 'safe', 'on'=>'search'),
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
			'Uid' => '彩生活用户ID',
			'colourSn' => '彩生活订单号',
			'orderId' => '千禧之星订单号',
			'receiver' => '接收人',
			'address' => '收货地址',
			'contact' => '联系电话',
			'addTime' => '生成订单时间',
			'totalFee' => '商品总金额',
			'voucherPrice' => '千禧之星优惠券价格',
			'voucherCode' => '千禧之星优惠券编号',
			'statusTxt' => '千禧之星订单状态',
			'title' => '商品名称',
			'price' => '商品价格',
			'payPrice' => '单位要付款',
			'totalPrice' => '单位总价',
			'num' => '单位数量',
			'createTime' => 'Create Time',
			'updateTime' => 'Update Time',
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
			$criteria->with[]='thirdFees';
			$criteria->compare('thirdFees.cSn', $this->cSn, true);
		}
		if(isset($this->sn)){
			$criteria->with[]='thirdFees';
			$criteria->compare('thirdFees.sn', $this->sn, true);
		}
		if(isset($this->status)){
			$criteria->with[]='thirdFees';
			$criteria->compare('thirdFees.status', $this->status);
		}

		$criteria->compare('id',$this->id);
		$criteria->compare('Uid',$this->Uid);
		$criteria->compare('colourSn',$this->colourSn,true);
		$criteria->compare('orderId',$this->orderId,true);
		$criteria->compare('receiver',$this->receiver,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('contact',$this->contact,true);
		$criteria->compare('addTime',$this->addTime,true);
		$criteria->compare('totalFee',$this->totalFee,true);
		$criteria->compare('voucherPrice',$this->voucherPrice,true);
		$criteria->compare('voucherCode',$this->voucherCode,true);
		$criteria->compare('statusTxt',$this->statusTxt,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('payPrice',$this->payPrice,true);
		$criteria->compare('totalPrice',$this->totalPrice,true);
		$criteria->compare('num',$this->num,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('updateTime',$this->updateTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Millennium the static model class
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
     * @version 获取彩之云的总金额
     */
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
	public function getStatus(){
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
