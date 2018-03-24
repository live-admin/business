<?php

/**
 * This is the model class for table "lucky_customer_out".
 *
 * The followings are the available columns in table 'lucky_customer_out':
 * @property integer $id
 * @property string $cust_name
 * @property integer $cust_id
 * @property string $date_day
 * @property string $date
 * @property integer $number
 */
class LuckyCustomerOut extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_customer_out';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cust_id, date_day', 'required'),
			array('cust_id, number', 'numerical', 'integerOnly'=>true),
			array('cust_name', 'length', 'max'=>100),
			array('date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cust_name, cust_id, date_day, date, number', 'safe', 'on'=>'search'),
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
			'cust_name' => 'Cust Name',
			'cust_id' => 'Cust',
			'date_day' => '使用时间(天)',
			'date' => '使用时间',
			'number' => '使用数量',
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
		$criteria->compare('cust_name',$this->cust_name,true);
		$criteria->compare('cust_id',$this->cust_id);
		$criteria->compare('date_day',$this->date_day,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('number',$this->number);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyCustomerOut the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
