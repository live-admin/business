<?php

/**
 * This is the model class for table "whole_country_region".
 *
 * The followings are the available columns in table 'whole_country_region':
 * @property integer $id
 * @property integer $code
 * @property string $name
 * @property integer $parent_id
 * @property integer $type
 * @property integer $create_time
 */
class WholeCountryRegion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'whole_country_region';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('code, parent_id, type, create_time', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, code, name, parent_id, type, create_time', 'safe', 'on'=>'search'),
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
			'code' => '地区编码',
			'name' => '地区名',
			'parent_id' => '直属上级',
			'type' => '类型（1省，2市，3县区，4镇）',
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
		$criteria->compare('code',$this->code);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WholeCountryRegion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取省的code
	 * @param unknown $provinceName
	 * @return boolean
	 */
	public function getProvinceCode($provinceName){
		if (empty($provinceName)){
			return false;
		}
		$wholeRegoin=WholeCountryRegion::model()->find("name=:name and type=1",array(':name'=>F::msubstr($provinceName, 0, 2, "utf-8", true)));
		if (!empty($wholeRegoin)){
			return $wholeRegoin->code;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取城市code
	 * @param unknown $cityName
	 * @return boolean
	 */
	public function getCityCode($cityName){
		if (empty($cityName)){
			return false;
		}
		$wholeRegoin=WholeCountryRegion::model()->find("name=:name and type=1",array(':name'=>$cityName));
		if (!empty($wholeRegoin)){
			return $wholeRegoin->code;
		}else {
			return false;
		}
	}
}
