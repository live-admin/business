<?php

/**
 * This is the model class for table "tree_two_grow".
 *
 * The followings are the available columns in table 'tree_two_grow':
 * @property integer $id
 * @property integer $seed_id
 * @property integer $customer_id
 * @property string $open_id
 * @property integer $value
 * @property integer $way
 * @property integer $create_time
 */
class TreeTwoGrow extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tree_two_grow';
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
			array('open_id', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, seed_id, customer_id, open_id, value, way, create_time', 'safe', 'on'=>'search'),
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
			'open_id' => '微信openID',
			'value' => '成长值',
			'way' => '来源途径(1自己浇水，2分享，3朋友浇水，4邀请注册，5下单，6彩富人生，7抽奖，8使用(摘果实),9邀请彩富,10第三次摘果实(成长值为0),11分享到邻里，12分享到第三方)',
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
		$criteria->compare('open_id',$this->open_id,true);
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
	 * @return TreeTwoGrow the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
