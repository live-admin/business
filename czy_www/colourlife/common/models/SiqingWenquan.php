<?php

/**
 * This is the model class for table "siqing_wenquan".
 *
 * The followings are the available columns in table 'siqing_wenquan':
 * @property integer $id
 * @property integer $customer_id
 * @property string $answer_1
 * @property string $answer_2
 * @property string $answer_3
 * @property string $answer_4
 * @property string $answer_5
 * @property string $answer_6
 * @property string $answer_7
 * @property string $answer_8
 * @property string $answer_9
 * @property string $answer_10
 * @property integer $create_time
 */
class SiqingWenquan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'siqing_wenquan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, create_time', 'numerical', 'integerOnly'=>true),
			array('answer_1, answer_2, answer_3, answer_4, answer_5, answer_6, answer_7, answer_8, answer_9, answer_10', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, answer_1, answer_2, answer_3, answer_4, answer_5, answer_6, answer_7, answer_8, answer_9, answer_10, create_time', 'safe', 'on'=>'search'),
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
			'customer_id' => '用户id',
			'answer_1' => '第1个问题答案',
			'answer_2' => '第2个问题答案',
			'answer_3' => '第3个问题答案',
			'answer_4' => '第4个问题答案',
			'answer_5' => '第5个问题答案',
			'answer_6' => '第6个问题答案',
			'answer_7' => '第7个问题答案',
			'answer_8' => '第8个问题答案',
			'answer_9' => '第9个问题答案',
			'answer_10' => '第10个问题答案',
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
		$criteria->compare('answer_1',$this->answer_1,true);
		$criteria->compare('answer_2',$this->answer_2,true);
		$criteria->compare('answer_3',$this->answer_3,true);
		$criteria->compare('answer_4',$this->answer_4,true);
		$criteria->compare('answer_5',$this->answer_5,true);
		$criteria->compare('answer_6',$this->answer_6,true);
		$criteria->compare('answer_7',$this->answer_7,true);
		$criteria->compare('answer_8',$this->answer_8,true);
		$criteria->compare('answer_9',$this->answer_9,true);
		$criteria->compare('answer_10',$this->answer_10,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SiqingWenquan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
