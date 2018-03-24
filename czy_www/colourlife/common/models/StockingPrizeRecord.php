<?php

/**
 * This is the model class for table "stocking_prize_record".
 *
 * The followings are the available columns in table 'stocking_prize_record':
 * @property string $id
 * @property integer $customer_id
 * @property integer $time
 * @property integer $prize_id
 * @property integer $prize_name
 * @property string $mobile
 */
class StockingPrizeRecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $mobile;
	public $modelName = '中奖纪录管理';

	public function tableName()
	{
		return 'stocking_prize_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, time, prize_id,prize_name', 'required'),
			array('customer_id, time, prize_id', 'numerical', 'integerOnly'=>true),
			array('prize_name', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, time, prize_id,prize_name,mobile', 'safe', 'on'=>'search'),
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
			'Customer' => array(self::HAS_ONE, 'Customer', array('id'=>'customer_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => '用户',
			'time' => '中奖时间',
			'prize_id' => 'Prize',
			'prize_name' => '奖品名称',
			'mobile'=>'用户手机号'
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
		if(isset($this->mobile)){
			$criteria->with[]='Customer';
			$criteria->compare('Customer.mobile', $this->mobile, true);
		}
		$criteria->compare('t.id',$this->id,true);
		$criteria->compare('t.customer_id',$this->customer_id);
		$criteria->compare('t.time',$this->time);
		$criteria->compare('t.prize_id',$this->prize_id);
		$criteria->compare('t.prize_name',$this->prize_name);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return StockingPrizeRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 获取用户手机号
	 * @param string $state
	 * @return string
	 */
	public function getUserName($customer_id)
	{
		if(empty($customer_id)){
			return false;
		}
		$customerArr=Customer::model()->findByPk($customer_id);
		return $customerArr['mobile'];
	}
	/**
	 * 将中奖时间格式化
	 * @param string $state
	 * @return string
	 */
	public function getTime($time)
	{
		if(empty($time)){
			return false;
		}
		$date=date('Y-m-d H:i:s',$time);
		return $date;
	}

}
