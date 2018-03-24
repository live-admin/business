<?php

/**
 * This is the model class for table "pintuan_shouhuo_address".
 *
 * The followings are the available columns in table 'pintuan_shouhuo_address':
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property string $mobile
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $county_id
 * @property integer $town_id
 * @property string $address
 * @property integer $zip
 * @property integer $create_time
 * @property integer $update_time
 */
class PintuanShouhuoAddress extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pintuan_shouhuo_address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, province_id, city_id, county_id, town_id, zip, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('name, mobile, address', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, name, mobile, province_id, city_id, county_id, town_id, address, zip, create_time, update_time', 'safe', 'on'=>'search'),
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
			'id' => '主键ID',
			'uid' => '用户id',
			'name' => '收获人名称',
			'mobile' => '收货人手机号码',
			'province_id' => '收货人省份ID',
			'city_id' => '收货人城市ID',
			'county_id' => '收货人区/县ID',
			'town_id' => '收货人镇ID',
			'address' => '收货人详细地址',
			'zip' => '收货人邮编',
			'create_time' => '创建时间',
			'update_time' => '修改时间',
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
		$criteria->compare('uid',$this->uid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('province_id',$this->province_id);
		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('county_id',$this->county_id);
		$criteria->compare('town_id',$this->town_id);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('zip',$this->zip);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PintuanShouhuoAddress the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
