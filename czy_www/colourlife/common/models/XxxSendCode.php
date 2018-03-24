<?php

/**
 * This is the model class for table "xxx_send_code".
 *
 * The followings are the available columns in table 'xxx_send_code':
 * @property integer $id
 * @property string $mobile
 * @property integer $code
 * @property integer $type
 * @property integer $create_time
 * @property integer $is_use
 */
class XxxSendCode extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'xxx_send_code';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mobile, code, create_time', 'required'),
			array('code, type, create_time, is_use', 'numerical', 'integerOnly'=>true),
			array('mobile', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mobile, code, type, create_time, is_use', 'safe', 'on'=>'search'),
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
			'mobile' => 'Mobile',
			'code' => 'Code',
			'type' => 'Type',
			'create_time' => 'Create Time',
			'is_use' => 'Is Use',
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
		$criteria->compare('code',$this->code);
		$criteria->compare('type',$this->type);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('is_use',$this->is_use);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return XxxSendCode the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
