<?php

/**
 * This is the model class for table "warm_purse".
 *
 * The followings are the available columns in table 'warm_purse':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $task
 * @property integer $type
 * @property string $tickets
 * @property integer $day
 * @property integer $create_time
 */
class WarmPurse extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'warm_purse';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('task, type', 'required'),
			array('customer_id, task, type, day, create_time', 'numerical', 'integerOnly'=>true),
			array('tickets', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, task, type, tickets, day, create_time', 'safe', 'on'=>'search'),
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
			'customer_id' => '用户ID',
			'task' => '任务',
			'type' => '类型（1为财富人生，2为E装修，3为E租房，4为E维修，5为花样保险）',
			'tickets' => '饭票',
			'day' => '年月日',
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
		$criteria->compare('task',$this->task);
		$criteria->compare('type',$this->type);
		$criteria->compare('tickets',$this->tickets,true);
		$criteria->compare('day',$this->day);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WarmPurse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
