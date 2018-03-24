<?php

/**
 * This is the model class for table "dongxing_order".
 *
 * The followings are the available columns in table 'dongxing_order':
 * @property integer $id
 * @property string $OrderNo
 * @property string $ProductName
 * @property string $UnitPrice
 * @property string $TotalAmount
 * @property integer $Quantity
 * @property string $Contact
 * @property string $Mobile
 * @property integer $Status
 * @property string $Tourists
 */
class DongxingOrder extends CActiveRecord
{
	public $modelName = '东星旅行订单';
	public $sn;
	public $status;
	public $orderStatus_arr = array(
		"1" => "待支付",
		"2" => "已支付",
		"3" => "订单完成",
		"4" => "订单已确认",
		"5" => "订单已取消",
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
		return 'dongxing_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Tourists', 'required'),
			array('Quantity, Status', 'numerical', 'integerOnly'=>true),
			array('OrderNo, Contact, Mobile', 'length', 'max'=>50),
			array('ProductName', 'length', 'max'=>100),
			array('UnitPrice, TotalAmount', 'length', 'max'=>18),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, OrderNo, ProductName, UnitPrice, TotalAmount, Quantity, Contact, Mobile, Status, Tourists,cSn,sn,status', 'safe', 'on'=>'search'),
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
			'thirdFees' => array(self::HAS_ONE, 'ThirdFees', array('cSn'=>'OrderNo')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'OrderNo' => '第三方订单号',
			'ProductName' => '旅游产品名称',
			'UnitPrice' => '产品单价',
			'TotalAmount' => '订单总价',
			'Quantity' => '产品购买数量',
			'Contact' => '订单联系人',
			'Mobile' => '联系电话',
			'Status' => '订单状态',
			'Tourists' => '出行人',
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
		$criteria->compare('OrderNo',$this->OrderNo,true);
		$criteria->compare('ProductName',$this->ProductName,true);
		$criteria->compare('UnitPrice',$this->UnitPrice,true);
		$criteria->compare('TotalAmount',$this->TotalAmount,true);
		$criteria->compare('Quantity',$this->Quantity);
		$criteria->compare('Contact',$this->Contact,true);
		$criteria->compare('Mobile',$this->Mobile,true);
		$criteria->compare('t.Status',$this->Status,true);
		$criteria->compare('Tourists',$this->Tourists,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DongxingOrder the static model class
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
		return $this->orderStatus_arr[$this->Status];
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
