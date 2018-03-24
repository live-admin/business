<?php

/**
 * This is the model class for table "womensday_prize_list".
 *
 * The followings are the available columns in table 'womensday_prize_list':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $wc_id
 * @property integer $prize_id
 * @property string $prize_name
 * @property string $is_change
 * @property string $is_notify
 * @property integer $create_time
 * @property integer $update_time
 */
class WomensdayPrizeList extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'womensday_prize_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, wc_id, prize_id, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('prize_name', 'length', 'max'=>25),
			array('is_change, is_notify', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, wc_id, prize_id, prize_name, is_change, is_notify, create_time, update_time', 'safe', 'on'=>'search'),
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
			'customer_id' => '用户ID',
			'wc_id' => '抽奖机会表ID',
			'prize_id' => '奖品ID',
			'prize_name' => '奖品名称',
			'is_change' => '是否已兑换',
			'is_notify' => '是否已通知销券（1是已通知）',
			'create_time' => '添加时间',
			'update_time' => '更新时间',
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
		$criteria->compare('wc_id',$this->wc_id);
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('prize_name',$this->prize_name,true);
		$criteria->compare('is_change',$this->is_change,true);
		$criteria->compare('is_notify',$this->is_notify,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WomensdayPrizeList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
