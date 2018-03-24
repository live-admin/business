<?php

/**
 * This is the model class for table "fuwu_address".
 *
 * The followings are the available columns in table 'fuwu_address':
 * @property integer $id
 * @property integer $customer_id
 * @property string $address
 * @property string $mobile
 * @property string $username
 * @property integer $prize_id
 * @property integer $create_time
 */
class FuwuAddress extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fuwu_address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, prize_id, create_time', 'numerical', 'integerOnly'=>true),
			array('address, mobile', 'length', 'max'=>255),
			array('username', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, address, mobile, username, prize_id, create_time', 'safe', 'on'=>'search'),
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
			'customer_id' => '用户id',
			'address' => '收货人地址',
			'mobile' => '收货人电话',
			'username' => '收货人姓名',
			'prize_id' => '奖品表的主键ID',
			'create_time' => '领取时间',
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
		$criteria->compare('address',$this->address,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FuwuAddress the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
