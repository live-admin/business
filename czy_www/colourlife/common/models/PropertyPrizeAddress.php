<?php

/**
 * This is the model class for table "property_prize_address".
 *
 * The followings are the available columns in table 'property_prize_address':
 * @property string $id
 * @property integer $customer_id
 * @property integer $prize_id
 * @property string $name
 * @property string $address
 * @property string $mobile
 * @property string $username
 * @property integer $category_id
 * @property integer $status
 * @property string $zip
 * @property integer $time
 * @property string $sn
 * @property integer $record_id
 * @property string $address_id
 */
class PropertyPrizeAddress extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'property_prize_address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, prize_id, name, address, mobile, username, time, address_id', 'required'),
			array('customer_id, prize_id, category_id, status, time, record_id', 'numerical', 'integerOnly'=>true),
			array('name, address, address_id', 'length', 'max'=>255),
			array('mobile', 'length', 'max'=>20),
			array('username, sn', 'length', 'max'=>32),
			array('zip', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, prize_id, name, address, mobile, username, category_id, status, zip, time, sn, record_id, address_id', 'safe', 'on'=>'search'),
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
			'customer_id' => 'Customer',
			'prize_id' => 'Prize',
			'name' => 'Name',
			'address' => 'Address',
			'mobile' => 'Mobile',
			'username' => 'Username',
			'category_id' => 'Category',
			'status' => 'Status',
			'zip' => 'Zip',
			'time' => 'Time',
			'sn' => 'Sn',
			'record_id' => 'Record',
			'address_id' => 'AddressId',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('time',$this->time);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('record_id',$this->record_id);
		$criteria->compare('address_id',$this->address_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PropertyPrizeAddress the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
