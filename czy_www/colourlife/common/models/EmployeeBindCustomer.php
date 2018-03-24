<?php

/**
 * 菜管家用户绑定彩之云用户
 * @author dw
 * @property int $id
 * @property int $employee_id
 * @property int $customer_id
 * @property int $bind_time
 * @property int $unbind_time
 * @property int $state
 * @property Customer customer
 * @property Employee employee
 */
class EmployeeBindCustomer extends CActiveRecord
{
	public function tableName()
	{
		return 'jf_employee_bind_customer';
	}

	public function rules()
	{
		return array(
			array('employee_id, customer_id, bind_time', 'required'),
			array('employee_id, customer_id, bind_time, unbind_time, state', 'length', 'max'=>10),
			array('id, employee_id, customer_id, bind_time, unbind_time, state', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
				'employee' => array(self::BELONGS_TO, 'Employee', "employee_id"),
				'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id')
		);
	}


	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'employee_id' => '财管家用户ID',
			'customer_id' => '彩之云用户ID',
			'bind_time' => '绑定时间',
			'unbind_time' => '解绑时间',
			'state' => '绑定状态', //0--未绑定, 1--已绑定, 2--已解绑
		);
	}


	public function search()
	{

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('employee_id',$this->employee_id,true);
		$criteria->compare('customer_id',$this->customer_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
