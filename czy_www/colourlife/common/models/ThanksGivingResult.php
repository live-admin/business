<?php

/**
 * This is the model class for table "thanks_giving_result".
 *
 * The followings are the available columns in table 'thanks_giving_result':
 * @property integer $id
 * @property integer $customer_id
 * @property string $username
 * @property integer $prize_id
 * @property string $prize_name
 * @property integer $prize_act_id
 * @property integer $lucky_entity_id
 * @property string $code
 * @property integer $day
 * @property integer $addtime
 */
class ThanksGivingResult extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'thanks_giving_result';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, prize_id, prize_name, lucky_entity_id, code, day, addtime', 'required'),
			array('customer_id, prize_id, prize_act_id, lucky_entity_id, day, addtime', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>50),
			array('prize_name', 'length', 'max'=>100),
			array('code', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, username, prize_id, prize_name, prize_act_id, lucky_entity_id, code, day, addtime', 'safe', 'on'=>'search'),
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
			'customer_id' => '用户ID',
			'username' => '用户名',
			'prize_id' => '奖项ID',
			'prize_name' => '奖项名',
			'prize_act_id' => '活动ID',
			'lucky_entity_id' => '奖品ID',
			'code' => '优惠码',
			'day' => '中奖日',
			'addtime' => '中奖时间',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('prize_name',$this->prize_name,true);
		$criteria->compare('prize_act_id',$this->prize_act_id);
		$criteria->compare('lucky_entity_id',$this->lucky_entity_id);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('day',$this->day);
		$criteria->compare('addtime',$this->addtime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ThanksGivingResult the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
