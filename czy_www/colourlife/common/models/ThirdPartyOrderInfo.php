<?php

/**
 * This is the model class for table "third_party_order_info".
 *
 * The followings are the available columns in table 'third_party_order_info':
 * @property integer $id
 * @property string $orderSn
 * @property string $model
 * @property integer $customer_id
 * @property string $amount
 * @property string $bank_pay
 * @property string $red_packet_pay
 * @property string $create_ip
 * @property integer $create_time
 * @property integer $status
 * @property string $note
 */
class ThirdPartyOrderInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'third_party_order_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, create_time, status', 'numerical', 'integerOnly'=>true),
			array('orderSn', 'length', 'max'=>32),
			array('model, create_ip', 'length', 'max'=>20),
			array('amount, bank_pay, red_packet_pay', 'length', 'max'=>10),
			array('note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, orderSn, model, customer_id, amount, bank_pay, red_packet_pay, create_ip, create_time, status, note', 'safe', 'on'=>'search'),
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
			'id' => 'id',
			'orderSn' => 'sn',
			'model' => '来源',
			'customer_id' => '业主ID',
			'amount' => '金额',
			'bank_pay' => '银行支付金额',
			'red_packet_pay' => '红包支付金额',
			'create_ip' => '创建IP',
			'create_time' => '时间',
			'status' => '状态',
			'note' => '备注',
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
		$criteria->compare('orderSn',$this->orderSn,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('bank_pay',$this->bank_pay,true);
		$criteria->compare('red_packet_pay',$this->red_packet_pay,true);
		$criteria->compare('create_ip',$this->create_ip,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ThirdPartyOrderInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
