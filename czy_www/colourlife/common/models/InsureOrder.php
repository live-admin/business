<?php

/**
 * This is the model class for table "insure_order".
 *
 * The followings are the available columns in table 'insure_order':
 * @property integer $id
 * @property string $sn
 * @property string $amount
 * @property string $bank_pay
 * @property string $red_packet_pay
 * @property integer $user_red_packet
 * @property integer $customer_id
 * @property integer $object_id
 * @property integer $category_id
 * @property integer $insure_code
 * @property integer $status
 * @property string $remark
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $payment_id
 * @property integer $pay_time
 */
class InsureOrder extends CActiveRecord
{
	public $modelName = '保险';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'insure_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('sn, amount, customer_id, object_id, category_id, insure_code, create_time, create_ip', 'required'),
				array('user_red_packet, customer_id, object_id, category_id, insure_code, status, create_time, payment_id, pay_time', 'numerical', 'integerOnly'=>true),
				array('sn, create_ip', 'length', 'max'=>32),
				array('amount, bank_pay, red_packet_pay', 'length', 'max'=>10),
				array('remark', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
				array('id, sn, amount, bank_pay, red_packet_pay, user_red_packet, customer_id, object_id, category_id, insure_code, status, remark, create_time, create_ip, payment_id, pay_time', 'safe', 'on'=>'search'),
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
				'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
				'payment' => array(self::BELONGS_TO, 'Payment', 'payment_id'),
				'insureInfo' => array(self::BELONGS_TO, 'InsureInfo', 'object_id'),
				'insureCategory' => array(self::BELONGS_TO, 'InsureCategory', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
				'id' => 'ID',
				'sn' => '订单号',
				'amount' => '订单金额',
				'bank_pay' => '实付',
				'red_packet_pay' => '红包抵扣',
				'user_red_packet' => '是否使用红包',
				'customer_id' => '用户id',
				'object_id' => '关联id',
				'category_id' => '类别id',
				'insure_code' => '保险公司标示',
				'status' => '订单状态值',
				'remark' => '备注',
				'create_time' => '创建时间',
				'create_ip' => '下单IP',
				'payment_id' => '支付方式',
				'pay_time' => '支付时间',
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
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('bank_pay',$this->bank_pay,true);
		$criteria->compare('red_packet_pay',$this->red_packet_pay,true);
		$criteria->compare('user_red_packet',$this->user_red_packet);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('insure_code',$this->insure_code);
		$criteria->compare('status',$this->status);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('create_ip',$this->create_ip,true);
		$criteria->compare('payment_id',$this->payment_id);
		$criteria->compare('pay_time',$this->pay_time);

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return InsureOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
