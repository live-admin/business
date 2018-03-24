<?php

/**
 * This is the model class for table "door_user_relation".
 *
 * The followings are the available columns in table 'door_user_relation':
 * @property integer $id
 * @property integer $user_id
 * @property string $mobile
 * @property string $qrcode
 * @property integer $door_id
 * @property integer $state
 * @property integer $create_time
 * @property integer $invalid_time
 */
class DoorUserRelation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'door_user_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, create_time', 'required'),
			array('user_id, door_id, state, create_time, invalid_time', 'numerical', 'integerOnly'=>true),
			array('mobile', 'length', 'max'=>15),
			array('qrcode', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, mobile, qrcode, door_id, state, create_time, invalid_time', 'safe', 'on'=>'search'),
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
			'mobile' => 'Mobile',
			'qrcode' => 'Qrcode',
			'door_id' => 'Door',
			'state' => 'State',
			'create_time' => 'Create Time',
			'invalid_time' => 'Invalid Time',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('qrcode',$this->qrcode,true);
		$criteria->compare('door_id',$this->door_id);
		$criteria->compare('state',$this->state);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('invalid_time',$this->invalid_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DoorUserRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
