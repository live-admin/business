<?php

/**
 * This is the model class for table "sf_express".
 *
 * The followings are the available columns in table 'sf_express':
 * @property integer $id
 * @property string $expressSn
 * @property string $orderAmount
 * @property string $orderTime
 * @property string $orderStatus
 * @property string $payTime
 * @property string $payAmount
 * @property string $paySn
 * @property string $receivedName
 * @property string $receivedMobile
 * @property string $receivedAddress
 * @property string $productName
 * @property string $unitPrice
 * @property string $buyNumber
 * @property string $productAmount
 * @property string $refundMoney
 * @property integer $createTime
 * @property integer $updateTime
 */
class SfExpress extends CActiveRecord
{

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sf_express';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('expressSn, orderTime, orderStatus, payTime, payAmount, paySn, receivedName, receivedMobile, receivedAddress, productName, buyNumber', 'required'),
			array('createTime, updateTime', 'numerical', 'integerOnly'=>true),
			array('expressSn, orderTime, payTime, paySn, receivedName', 'length', 'max'=>50),
			array('orderAmount, payAmount', 'length', 'max'=>10),
			array('orderStatus', 'length', 'max'=>20),
			array('receivedMobile', 'length', 'max'=>15),
			array('receivedAddress, unitPrice, buyNumber, productAmount, refundMoney', 'length', 'max'=>250),
			array('productName', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, expressSn, orderAmount, orderTime, orderStatus, payTime, payAmount, paySn, receivedName, receivedMobile, receivedAddress, productName, unitPrice, buyNumber, productAmount, refundMoney, createTime, updateTime', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'expressSn' => '顺丰优选订单号',
			'orderAmount' => '订单总金额',
			'orderTime' => '下单时间',
			'orderStatus' => '主订单状态',
			'payTime' => '支付时间',
			'payAmount' => '支付金额',
			'paySn' => '支付流水号',
			'receivedName' => '收货人姓名',
			'receivedMobile' => '收货人手机号码',
			'receivedAddress' => '收货人地址',
			'productName' => '商品名称',
			'unitPrice' => '购买单价',
			'buyNumber' => '购买数量',
			'productAmount' => '商品金额',
			'refundMoney' => '退款金额',
			'createTime' => '创建时间',
			'updateTime' => '更新时间',
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
		$criteria->compare('expressSn',$this->expressSn,true);
		$criteria->compare('orderAmount',$this->orderAmount,true);
		$criteria->compare('orderTime',$this->orderTime,true);
		$criteria->compare('orderStatus',$this->orderStatus,true);
		$criteria->compare('payTime',$this->payTime,true);
		$criteria->compare('payAmount',$this->payAmount,true);
		$criteria->compare('paySn',$this->paySn,true);
		$criteria->compare('receivedName',$this->receivedName,true);
		$criteria->compare('receivedMobile',$this->receivedMobile,true);
		$criteria->compare('receivedAddress',$this->receivedAddress,true);
		$criteria->compare('productName',$this->productName,true);
		$criteria->compare('unitPrice',$this->unitPrice,true);
		$criteria->compare('buyNumber',$this->buyNumber,true);
		$criteria->compare('productAmount',$this->productAmount,true);
		$criteria->compare('refundMoney',$this->refundMoney,true);
		$criteria->compare('createTime',$this->createTime);
		$criteria->compare('updateTime',$this->updateTime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SfExpress the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
