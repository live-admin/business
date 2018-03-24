<?php

/**
 * This is the model class for table "pacific_insuranc".
 *
 * The followings are the available columns in table 'pacific_insuranc':
 * @property integer $id
 * @property string $BillId
 * @property string $InsuranceName
 * @property string $ColorLifeOrderId
 * @property string $UserId
 * @property string $Mobile
 * @property integer $CpicStatus
 * @property string $PolicyAmount
 * @property string $BusinessAmount
 * @property string $StrongAmount
 * @property string $CarValue
 * @property string $BuyTime
 * @property string $CarName
 * @property string $PayTime
 * @property string $CreateTime
 * @property string $UpdateTime
 */
class PacificInsuranc extends CActiveRecord
{
	public $modelName = '太平洋保险订单';
	public $sn;
	public $status;
	public $customer_id;
	public $orderStatus_arr = array(
		"1" => "待报价",
		"2" => "已报价",
		"3" => "已支付",
		"4" => "已扣款",
		"5" => "已配送",
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
		return 'pacific_insuranc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('BillId, InsuranceName, UserId, CpicStatus', 'required'),
			array('CpicStatus', 'numerical', 'integerOnly'=>true),
			array('BillId, InsuranceName, ColorLifeOrderId, UserId, Mobile, PayTime, CreateTime, UpdateTime', 'length', 'max'=>255),
			array('PolicyAmount, BusinessAmount, StrongAmount, CarValue', 'length', 'max'=>10),
			array('BuyTime', 'length', 'max'=>12),
			array('CarName', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, BillId, InsuranceName, ColorLifeOrderId, UserId, Mobile, CpicStatus, PolicyAmount, BusinessAmount, StrongAmount, CarValue, BuyTime, CarName, PayTime, CreateTime, UpdateTime,cSn,sn,status,customer_id', 'safe', 'on'=>'search'),
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
			'thirdFees' => array(self::HAS_ONE, 'ThirdFees', array('cSn'=>'BillId')),
			//'customer' => array(self::HAS_ONE, 'Customer', array('id'=>'UserId')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'BillId' => '太平洋保险订单号',
			'InsuranceName' => '接入名称',
			'ColorLifeOrderId' => '彩生活订单号',
			'UserId' => '用户id',
			'Mobile' => '联系方式',
			'CpicStatus' => '太保订单状态',
			'PolicyAmount' => '保费支付金额',
			'BusinessAmount' => '商业险金额',
			'StrongAmount' => '交强险金额',
			'CarValue' => '车型价值',
			'BuyTime' => '购车日期',
			'CarName' => '车型名称',
			'PayTime' => '支付时间',
			'CreateTime' => 'Create Time',
			'UpdateTime' => 'Update Time',
			'sn' => '订单号',
			'amount' => '总金额(彩之云)',
			'bank_pay' => '实付金额',
			'red_packet_pay' => '红包抵扣',
			'status' => '支付状态',
			'payment_id'=>'支付方式',
			'create_time'=>'下单时间',
			'customer_id'=>'用户账号'
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
//		if(isset($this->BillId)){
//			$criteria->with[]=array(
//				'customer',
//			);
//			$criteria->compare('customer.id', $this->status, true);
//		}

		$criteria->compare('id',$this->id);
		$criteria->compare('BillId',$this->BillId,true);
		$criteria->compare('InsuranceName',$this->InsuranceName,true);
		$criteria->compare('ColorLifeOrderId',$this->ColorLifeOrderId,true);
		$criteria->compare('UserId',$this->UserId,true);
		$criteria->compare('Mobile',$this->Mobile,true);
		$criteria->compare('CpicStatus',$this->CpicStatus);
		$criteria->compare('PolicyAmount',$this->PolicyAmount,true);
		$criteria->compare('BusinessAmount',$this->BusinessAmount,true);
		$criteria->compare('StrongAmount',$this->StrongAmount,true);
		$criteria->compare('CarValue',$this->CarValue,true);
		$criteria->compare('BuyTime',$this->BuyTime,true);
		$criteria->compare('CarName',$this->CarName,true);
		$criteria->compare('PayTime',$this->PayTime,true);
		$criteria->compare('CreateTime',$this->CreateTime,true);
		$criteria->compare('UpdateTime',$this->UpdateTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PacificInsuranc the static model class
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
		return $this->orderStatus_arr[$this->CpicStatus];
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
     * @version 获取彩之云用户Id
     */
	public function getCustomerId(){
		if(!empty($this->UserId)){
			$model = Customer::model()->findByPk($this->UserId);
			return $model['mobile'];
		}
	}
}
