<?php

/**
 * This is the model class for table "spring_address".
 *
 * The followings are the available columns in table 'spring_address':
 * @property string $id
 * @property integer $user_id
 * @property string $address
 * @property string $mobile
 * @property string $username
 * @property string $address_id
 * @property integer $createtime
 */
class SpringAddress extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'spring_address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, address, mobile, username, address_id, createtime', 'required'),
			array('user_id, createtime', 'numerical', 'integerOnly'=>true),
			array('address, address_id', 'length', 'max'=>255),
			array('mobile', 'length', 'max'=>20),
			array('username', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, address, mobile, username, address_id, createtime', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'address' => 'Address',
			'mobile' => 'Mobile',
			'username' => 'Username',
			'address_id' => 'Address',
			'createtime' => 'Createtime',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('address_id',$this->address_id,true);
		$criteria->compare('createtime',$this->createtime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SpringAddress the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
