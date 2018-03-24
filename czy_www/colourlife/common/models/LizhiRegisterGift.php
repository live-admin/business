<?php

/**
 * This is the model class for table "lizhi_register_gift".
 *
 * The followings are the available columns in table 'lizhi_register_gift':
 * @property integer $customer_id
 * @property string $mobile
 * @property integer $chance
 * @property integer $is_use
 * @property string $prize_name
 * @property integer $create_time
 * @property integer $update_time
 */
class LizhiRegisterGift extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lizhi_register_gift';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id', 'required'),
			array('customer_id, chance, is_use, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('mobile', 'length', 'max'=>15),
			array('prize_name', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('customer_id, mobile, chance, is_use, prize_name, create_time, update_time', 'safe', 'on'=>'search'),
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
			'customer_id' => '用户ID',
			'mobile' => '手机号',
			'chance' => '抽奖机会',
			'is_use' => '是否已使用（0未使用，1已使用）',
			'prize_name' => '奖品名称',
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

		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('chance',$this->chance);
		$criteria->compare('is_use',$this->is_use);
		$criteria->compare('prize_name',$this->prize_name,true);
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
	 * @return LizhiRegisterGift the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
