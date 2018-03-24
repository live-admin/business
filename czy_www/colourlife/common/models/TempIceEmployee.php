<?php

/**
 * This is the model class for table "temp_ice_employee".
 *
 * The followings are the available columns in table 'temp_ice_employee':
 * @property integer $id
 * @property string $oa_username
 * @property string $job_id
 * @property string $org_id
 * @property string $uuid
 */
class TempIceEmployee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'temp_ice_employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('oa_username', 'required'),
			array('oa_username', 'length', 'max'=>250),
			array('job_id, org_id, uuid', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, oa_username, job_id, org_id, uuid', 'safe', 'on'=>'search'),
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
			'oa_username' => 'Oa Username',
			'job_id' => 'Job',
			'org_id' => 'Org',
			'uuid' => 'Uuid',
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
		$criteria->compare('oa_username',$this->oa_username,true);
		$criteria->compare('job_id',$this->job_id,true);
		$criteria->compare('org_id',$this->org_id,true);
		$criteria->compare('uuid',$this->uuid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TempIceEmployee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
