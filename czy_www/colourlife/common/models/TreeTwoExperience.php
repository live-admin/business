<?php

/**
 * This is the model class for table "tree_two_experience".
 *
 * The followings are the available columns in table 'tree_two_experience':
 * @property integer $id
 * @property integer $seed_id
 * @property integer $customer_id
 * @property integer $value
 * @property integer $way
 * @property integer $create_time
 */
class TreeTwoExperience extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tree_two_experience';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('seed_id, customer_id, value, way, create_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, seed_id, customer_id, value, way, create_time', 'safe', 'on'=>'search'),
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
			'seed_id' => '种子ID',
			'customer_id' => '用户ID',
			'value' => '经验值',
			'way' => '来源途径(1每天登录，2摘果实,3抽奖,4解锁新的土地)',
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
		$criteria->compare('seed_id',$this->seed_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('value',$this->value);
		$criteria->compare('way',$this->way);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TreeTwoExperience the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
