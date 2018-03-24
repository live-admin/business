<?php

/**
 * This is the model class for table "customer_expire_remind".
 *
 * The followings are the available columns in table 'customer_expire_remind':
 * @property integer $id
 * @property integer $customer_id
 * @property string $pro_sn
 * @property string $app_sn
 * @property integer $recover_time
 * @property integer $is_send
 * @property integer $create_time
 * @property integer $update_time
 */
class CustomerExpireRemind extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customer_expire_remind';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, recover_time, is_send, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>100),
			array('pro_sn, app_sn', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, pro_sn, app_sn, recover_time, is_send, type, create_time, update_time', 'safe', 'on'=>'search'),
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
			'customer_id' => '业主用户ID',
			'pro_sn' => '彩富订单（多个订单使用逗号隔开）',
			'app_sn' => '增值计划订单（多个使用逗号隔开）',
			'recover_time' => '过期时间',
			'is_send' => '是否已发送（0未发送，1已发送）',
			'type' => '类型（property物业宝，parking停车宝，app增值宝）',
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
		$criteria->compare('pro_sn',$this->pro_sn,true);
		$criteria->compare('app_sn',$this->app_sn,true);
		$criteria->compare('recover_time',$this->recover_time);
		$criteria->compare('is_send',$this->is_send);
		$criteria->compare('type',$this->type,true);
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
	 * @return CustomerExpireRemind the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
