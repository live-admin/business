<?php

/**
 * This is the model class for table "april_fool_chance".
 *
 * The followings are the available columns in table 'april_fool_chance':
 * @property integer $id
 * @property integer $customer_id
 * @property string $mobile
 * @property integer $times
 * @property integer $type
 * @property integer $way
 * @property integer $create_time
 */
class AprilFoolChance extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'april_fool_chance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, times, type, way, create_time', 'numerical', 'integerOnly'=>true),
			array('mobile', 'length', 'max'=>15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, mobile, times, type, way, create_time', 'safe', 'on'=>'search'),
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
			'id' => '表ID',
			'customer_id' => '用户ID',
			'mobile' => '分享外手机号',
			'times' => '次数',
			'type' => '类型（1获得，2使用）',
			'way' => '途径（1分享，2邀请注册，3新用户，4分享页的分享）',
			'create_time' => '添加时间',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('times',$this->times);
		$criteria->compare('type',$this->type);
		$criteria->compare('way',$this->way);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AprilFoolChance the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
