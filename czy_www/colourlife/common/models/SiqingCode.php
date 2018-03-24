<?php

/**
 * This is the model class for table "siqing_code".
 *
 * The followings are the available columns in table 'siqing_code':
 * @property integer $id
 * @property integer $goods_id
 * @property integer $customer_id
 * @property integer $code
 * @property integer $get_type
 * @property string $type_sign
 * @property integer $is_tan
 * @property integer $create_time
 */
class SiqingCode extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'siqing_code';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, customer_id, code, get_type, is_tan, create_time', 'numerical', 'integerOnly'=>true),
			array('type_sign', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, goods_id, customer_id, code, get_type, type_sign, is_tan, create_time', 'safe', 'on'=>'search'),
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
			'id' => '主键id',
			'goods_id' => '商品id',
			'customer_id' => '用户id',
			'code' => '夺宝码',
			'get_type' => '获得方式
1：参与问卷
2：邀请注册
3：彩富人生
4：购买下单',
			'type_sign' => '标记
（order订单的sn，彩富订单的sn，邀请的手机号码）',
			'is_tan' => '是否弹过框(0：否；1：是)',
			'create_time' => '创建时间',
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
		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('code',$this->code);
		$criteria->compare('get_type',$this->get_type);
		$criteria->compare('type_sign',$this->type_sign,true);
		$criteria->compare('is_tan',$this->is_tan);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SiqingCode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
