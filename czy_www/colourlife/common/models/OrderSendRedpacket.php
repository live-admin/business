<?php

/**
 * This is the model class for table "order_send_redpacket".
 *
 * The followings are the available columns in table 'order_send_redpacket':
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property integer $customer_id
 * @property integer $num
 * @property string $red_packet
 * @property string $red_sum
 */
class OrderSendRedpacket extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_send_redpacket';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, goods_id, customer_id, num', 'numerical', 'integerOnly'=>true),
			array('red_packet, red_sum', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, goods_id, customer_id, num, red_packet, red_sum', 'safe', 'on'=>'search'),
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
			'order_id' => '订单ID',
			'goods_id' => '商品ID',
			'customer_id' => '业主ID',
			'num' => '购买商品数量',
			'red_packet' => '单个商品的红包发放金额',
			'red_sum' => '红包总金额',
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
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('num',$this->num);
		$criteria->compare('red_packet',$this->red_packet,true);
		$criteria->compare('red_sum',$this->red_sum,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderSendRedpacket the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
