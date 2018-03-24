<?php

/**
 * This is the model class for table "singlesday_lottery_member".
 *
 * The followings are the available columns in table 'singlesday_lottery_member':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $prize_id
 * @property string $prize_name
 * @property string $sign_name
 * @property integer $create_time
 */
class SinglesdayLotteryMember extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'singlesday_lottery_member';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, prize_id, changed_id, is_use, create_time', 'numerical', 'integerOnly'=>true),
			array('prize_name', 'length', 'max'=>100),
			array('sign_name', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, prize_id, prize_name, sign_name, changed_id, is_use, create_time', 'safe', 'on'=>'search'),
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
			'prize_id' => '奖品ID',
			'prize_name' => '奖品名称',
			'sign_name' => '签文',
			'changed_id' => '被换签的ID',
			'is_use' => '是否有效（0有效，1无效）',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('prize_name',$this->prize_name,true);
		$criteria->compare('sign_name',$this->sign_name,true);
		$criteria->compare('changed_id',$this->changed_id);
		$criteria->compare('is_use',$this->is_use);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SinglesdayLotteryMember the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
