<?php

/**
 * This is the model class for table "popularity_get".
 *
 * The followings are the available columns in table 'popularity_get':
 * @property integer $id
 * @property integer $customer_id
 * @property string $open_id
 * @property integer $value
 * @property integer $way
 * @property integer $create_time
 */
class PopularityGet extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'popularity_get';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, value, way, create_time', 'numerical', 'integerOnly'=>true),
			array('open_id', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, open_id, value, way, create_time', 'safe', 'on'=>'search'),
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
			'id' => '主键id',
			'customer_id' => '用户ID',
			'open_id' => '微信openID',
			'value' => '人气值',
			'way' => '来源途径
1:签到;
2:完善资料;
3:邻里发帖;
4:邻里点赞;
5:邀请注册;
6:下单购买;

',
			'create_time' => '创建时间',
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
	 * @return PopularityGet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
