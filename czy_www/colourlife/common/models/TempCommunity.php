<?php

/**
 * This is the model class for table "temp_community".
 *
 * The followings are the available columns in table 'temp_community':
 * @property integer $id
 * @property string $name
 * @property string $uuid
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $level_one
 * @property string $level_two
 * @property string $level_three
 * @property integer $create_time
 * @property integer $update
 */
class TempCommunity extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'temp_community';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, uuid, create_time', 'required'),
			array('create_time, update', 'numerical', 'integerOnly'=>true),
			array('name, uuid, province, city, area', 'length', 'max'=>60),
			array('level_one, level_two, level_three', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, uuid, province, city, area, level_one, level_two, level_three, create_time, update', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'uuid' => 'Uuid',
			'province' => 'Province',
			'city' => 'City',
			'area' => 'Area',
			'level_one' => 'Level One',
			'level_two' => 'Level Two',
			'level_three' => 'Level Three',
			'create_time' => 'Create Time',
			'update' => 'Update',

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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('uuid',$this->uuid,true);
		$criteria->compare('province',$this->province,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('area',$this->area,true);
		$criteria->compare('level_one',$this->level_one,true);
		$criteria->compare('level_two',$this->level_two,true);
		$criteria->compare('level_three',$this->level_three,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update',$this->update);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TempCommunity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
