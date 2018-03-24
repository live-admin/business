<?php

/**
 * This is the model class for table "manager_expire_remind".
 *
 * The followings are the available columns in table 'manager_expire_remind':
 * @property integer $id
 * @property integer $manager_id
 * @property integer $customer_id
 * @property integer $recover_time
 * @property integer $is_send
 * @property integer $p_type
 * @property string $type
 * @property integer $create_time
 * @property integer $update_time
 */
class ManagerExpireRemind extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'manager_expire_remind';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('manager_id, customer_id, recover_time, is_send, p_type, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('type', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, manager_id, customer_id, recover_time, is_send, p_type, type, create_time, update_time', 'safe', 'on'=>'search'),
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
			'manager_id' => '客户经理ID',
			'customer_id' => '业主用户ID',
			'recover_time' => '过期时间',
			'is_send' => '是否已发送（0未发送，1已发送）',
			'p_type' => '类型（0 jf的客户经理，1推荐人）',
			'type' => '订单类型（property物业宝，parking停车宝，app增值宝）',
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
		$criteria->compare('manager_id',$this->manager_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('recover_time',$this->recover_time);
		$criteria->compare('is_send',$this->is_send);
		$criteria->compare('p_type',$this->p_type);
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
	 * @return ManagerExpireRemind the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
