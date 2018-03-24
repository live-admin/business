<?php

/**
 * This is the model class for table "profit_activity".
 *
 * The followings are the available columns in table 'profit_activity':
 * @property integer $id
 * @property integer $activity_code
 * @property integer $customer_id
 * @property string $relation_sn
 * @property string $model
 * @property string $value
 * @property integer $create_time
 */
class ProfitActivity extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profit_activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, relation_sn, value, create_time', 'required'),
			array('activity_code, customer_id, create_time', 'numerical', 'integerOnly'=>true),
			array('relation_sn', 'length', 'max'=>32),
			array('model', 'length', 'max'=>16),
			array('value', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, activity_code, customer_id, relation_sn, model, value, create_time', 'safe', 'on'=>'search'),
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
			'activity_code' => 'Activity Code',
			'customer_id' => 'Customer',
			'relation_sn' => 'Relation Sn',
			'model' => 'Model',
			'value' => 'Value',
			'create_time' => 'Create Time',
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
		$criteria->compare('activity_code',$this->activity_code);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('relation_sn',$this->relation_sn,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProfitActivity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
