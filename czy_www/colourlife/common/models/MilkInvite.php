<?php

/**
 * This is the model class for table "milk_invite".
 *
 * The followings are the available columns in table 'milk_invite':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $mobile
 * @property integer $is_reg
 * @property integer $create_time
 * @property integer $is_send
 * @property integer $is_buy
 */
class MilkInvite extends CActiveRecord
{	

	public $modelName = "邀请好友注册";
    const STARTTIME = "2014-10-09 00:00:00";   //活动开始时间
    const ENDTIME = "2014-10-18 23:59:59";     //活动结束时间


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'milk_invite';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, mobile, is_reg, create_time, is_send, is_buy', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, mobile, is_reg, create_time, is_send, is_buy', 'safe', 'on'=>'search'),
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
			'customer_id' => '邀请人ID',
			'mobile' => '邀请手机号',
			'is_reg' => '是否注册',
			'create_time' => '邀请时间',
			'is_send' => '是否已发红包',
			'is_buy' => '是否购买',
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
		$criteria->compare('mobile',$this->mobile);
		$criteria->compare('is_reg',$this->is_reg);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('is_send',$this->is_send);
		$criteria->compare('is_buy',$this->is_buy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MilkInvite the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
