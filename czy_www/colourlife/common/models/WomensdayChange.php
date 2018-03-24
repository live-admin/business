<?php

/**
 * This is the model class for table "womensday_change".
 *
 * The followings are the available columns in table 'womensday_change':
 * @property integer $id
 * @property integer $wpl_id
 * @property string $user_name
 * @property string $mobile
 * @property string $department
 * @property string $address
 * @property integer $create_time
 */
class WomensdayChange extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'womensday_change';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('address', 'required'),
			array('wpl_id, create_time', 'numerical', 'integerOnly'=>true),
			array('user_name', 'length', 'max'=>64),
			array('mobile', 'length', 'max'=>11),
			array('department', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, wpl_id, user_name, mobile, department, address, create_time', 'safe', 'on'=>'search'),
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
			'wpl_id' => '中奖记录ID',
			'user_name' => '姓名',
			'mobile' => '手机号',
			'department' => '事业部',
			'address' => '详细地址',
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
		$criteria->compare('wpl_id',$this->wpl_id);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('department',$this->department,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WomensdayChange the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
