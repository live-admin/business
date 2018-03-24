<?php

/**
 * This is the model class for table "czz_book_order".
 *
 * The followings are the available columns in table 'czz_book_order':
 * @property string $id
 * @property string $customer_id
 * @property string $customer_name
 * @property string $customer_mobile
 * @property string $customer_identity_number
 * @property string $house_resource_id
 * @property string $building_name
 * @property string $room_layout
 * @property string $orientation
 * @property string $room_number
 * @property double $floor_area
 * @property string $total_amount
 * @property string $payment_type_id
 * @property integer $payment_type_type
 * @property double $payment_discount
 * @property string $payment_back_amount
 * @property string $payment_type_desc
 * @property integer $is_loan
 * @property integer $is_first_pay_loan
 * @property string $pay_amount
 * @property string $inner_order_id
 * @property string $outer_order_id
 * @property integer $create_time
 * @property integer $failure_time
 * @property integer $pay_success_time
 * @property integer $state
 */
class CzzBookOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'czz_book_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_name, customer_mobile, customer_identity_number, house_resource_id, payment_type_id, pay_amount, inner_order_id, create_time, failure_time', 'required'),
			array('payment_type_type, is_loan, is_first_pay_loan, create_time, failure_time, pay_success_time, state', 'numerical', 'integerOnly'=>true),
			array('floor_area, payment_discount', 'numerical'),
			array('customer_id, house_resource_id, payment_type_id', 'length', 'max'=>10),
			array('customer_name', 'length', 'max'=>64),
			array('customer_mobile, customer_identity_number, orientation, room_number, room_layout', 'length', 'max'=>32),
			array('building_name', 'length', 'max'=>256),
			array('total_amount, payment_back_amount, pay_amount', 'length', 'max'=>14),
			array('payment_type_desc, inner_order_id, outer_order_id', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, customer_name, customer_mobile, customer_identity_number, house_resource_id, building_name, room_layout, orientation, room_number, floor_area, total_amount, payment_type_id, payment_type_type, payment_discount, payment_back_amount, payment_type_desc, is_loan, is_first_pay_loan, pay_amount, inner_order_id, outer_order_id, create_time, failure_time, pay_success_time, state', 'safe', 'on'=>'search'),
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
			'customer_id' => '客户id',
			'customer_name' => '客户姓名',
			'customer_mobile' => '客户手机号',
			'customer_identity_number' => '客户身份证号',
			'house_resource_id' => '预定房源ID',
			'building_name' => '楼盘名称',
		    'room_layout' => '户型',
			'orientation' => '朝向',
			'room_number' => '楼栋号',
			'floor_area' => '面积',
			'total_amount' => '总价',
			'payment_type_id' => '房源付款类型ID',
			'payment_type_type' => '付款类型,1-折扣, 2-返款',
			'payment_discount' => '折扣',
			'payment_back_amount' => '返款金额',
			'payment_type_desc' => '返利说明',
			'is_loan' => '是否需要贷款',
			'is_first_pay_loan' => '首付是否需要贷款',
			'pay_amount' => '订金支付金额',
			'inner_order_id' => '内部订单号',
			'outer_order_id' => '外部订单号.第三方支付返回的订单号',
			'create_time' => '创建时间',
			'failure_time' => '未支付失效时间',
			'pay_success_time' => '支付成功时间',
			'state' => '支付状态。0-未付款, 1-已付款',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('customer_name',$this->customer_name,true);
		$criteria->compare('customer_mobile',$this->customer_mobile,true);
		$criteria->compare('customer_identity_number',$this->customer_identity_number,true);
		$criteria->compare('house_resource_id',$this->house_resource_id,true);
		$criteria->compare('building_name',$this->building_name,true);
		$criteria->compare('room_layout',$this->room_layout,true);		
		$criteria->compare('orientation',$this->orientation,true);
		$criteria->compare('room_number',$this->room_number,true);
		$criteria->compare('floor_area',$this->floor_area);
		$criteria->compare('total_amount',$this->total_amount,true);
		$criteria->compare('payment_type_id',$this->payment_type_id,true);
		$criteria->compare('payment_type_type',$this->payment_type_type);
		$criteria->compare('payment_discount',$this->payment_discount);
		$criteria->compare('payment_back_amount',$this->payment_back_amount,true);
		$criteria->compare('payment_type_desc',$this->payment_type_desc,true);
		$criteria->compare('is_loan',$this->is_loan);
		$criteria->compare('is_first_pay_loan',$this->is_first_pay_loan);
		$criteria->compare('pay_amount',$this->pay_amount,true);
		$criteria->compare('inner_order_id',$this->inner_order_id,true);
		$criteria->compare('outer_order_id',$this->outer_order_id,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('failure_time',$this->failure_time);
		$criteria->compare('pay_success_time',$this->pay_success_time);
		$criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CzzBookOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
