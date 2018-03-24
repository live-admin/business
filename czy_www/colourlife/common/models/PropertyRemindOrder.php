<?php

/**
 * This is the model class for table "property_remind_order".
 *
 * The followings are the available columns in table 'property_remind_order':
 * @property integer $id
 * @property string $sn
 * @property integer $customer_id
 * @property string $model
 * @property integer $recover_time
 * @property integer $create_time
 */
class PropertyRemindOrder extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'property_remind_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, inviter, recover_time, create_time', 'numerical', 'integerOnly'=>true),
			array('sn', 'length', 'max'=>32),
			array('model', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sn, customer_id, model, inviter, recover_time, create_time', 'safe', 'on'=>'search'),
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
			'sn' => '订单号',
			'customer_id' => '用户ID',
			'model' => '类型',
			'inviter' => '推荐人ID',
			'recover_time' => '提现时间',
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
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('inviter',$this->inviter);
		$criteria->compare('recover_time',$this->recover_time);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PropertyRemindOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
