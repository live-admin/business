<?php

/**
 * This is the model class for table "xing_qustion".
 *
 * The followings are the available columns in table 'xing_qustion':
 * @property integer $id
 * @property string $mobile
 * @property string $q1
 * @property string $q2
 * @property string $q3
 * @property string $q4
 * @property string $q5
 * @property string $q6
 * @property string $q7
 * @property string $q8
 * @property string $q9
 * @property string $q10
 */
class XingQustion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'xing_qustion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mobile, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mobile, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10', 'safe', 'on'=>'search'),
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
			'mobile' => '用户手机号码',
			'q1' => '问题1答案',
			'q2' => '问题2答案',
			'q3' => '问题3答案',
			'q4' => '问题4答案',
			'q5' => '问题5答案',
			'q6' => '问题6答案',
			'q7' => '问题7答案',
			'q8' => '问题8答案',
			'q9' => '问题9答案',
			'q10' => '问题10答案',
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
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('q1',$this->q1,true);
		$criteria->compare('q2',$this->q2,true);
		$criteria->compare('q3',$this->q3,true);
		$criteria->compare('q4',$this->q4,true);
		$criteria->compare('q5',$this->q5,true);
		$criteria->compare('q6',$this->q6,true);
		$criteria->compare('q7',$this->q7,true);
		$criteria->compare('q8',$this->q8,true);
		$criteria->compare('q9',$this->q9,true);
		$criteria->compare('q10',$this->q10,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return XingQustion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
