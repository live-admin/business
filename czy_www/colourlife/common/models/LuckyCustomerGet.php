<?php

/**
 * This is the model class for table "lucky_customer_get".
 *
 * The followings are the available columns in table 'lucky_customer_get':
 * @property integer $id
 * @property string $cust_name
 * @property integer $cust_id
 * @property integer $order_id
 * @property integer $genner_count
 * @property string $create_date
 * @property integer $lucky_act_id
 * @property string $login_date
 */
class LuckyCustomerGet extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_customer_get';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cust_id, lucky_act_id', 'required'),
			array('cust_id, order_id, genner_count, lucky_act_id', 'numerical', 'integerOnly'=>true),
			array('cust_name', 'length', 'max'=>100),
			array('create_date, login_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cust_name, cust_id, order_id, genner_count, create_date, lucky_act_id, login_date', 'safe', 'on'=>'search'),
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
			'cust_name' => '用户名',
			'cust_id' => '用户id',
			'order_id' => '订单id',
			'genner_count' => '产生的抽奖次数',
			'create_date' => '产生时间',
			'lucky_act_id' => '活动id',
			'login_date' => '登录时间',
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
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('genner_count',$this->genner_count);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('lucky_act_id',$this->lucky_act_id);
		$criteria->compare('login_date',$this->login_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyCustomerGet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
