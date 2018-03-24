<?php

/**
 * This is the model class for table "cai_redpacket_bind_colourlife".
 *
 * The followings are the available columns in table 'cai_redpacket_bind_colourlife':
 * @property integer $id
 * @property integer $employee_id
 * @property integer $customer_id
 * @property string $mobile
 * @property integer $state
 * @property integer $is_deleted
 * @property integer $create_time
 * @property string $note
 */
class CaiRedpacketBindColourlife extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $modelName = '彩之云绑定';
	public $startTime;
	public $endTime;
	public $employeeName;
	public $OAName;
	public $employeeMobile;
	public $customerName;
	public $customerAcc;
	public $customerMobile;

	public function tableName()
	{
		return 'cai_redpacket_bind_colourlife';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, customer_id, mobile', 'required'),
			array('employee_id, customer_id, state, is_deleted, create_time', 'numerical', 'integerOnly' => true),
			array('mobile', 'length', 'max' => 15),
			array('note', 'safe'),
			array('employee_id', 'checkQuery', 'on' => 'bindColourLife'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, employee_id, customer_id, mobile, state, is_deleted, create_time, note, startTime, endTime, employeeName, OAName, employeeMobile, customerName, customerAcc, customerMobile', 'safe', 'on' => 'search'),
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
			'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'employee_id' => '员工ID',
			'customer_id' => '彩之云ID',
			'mobile' => '绑定号码',
			'state' => '状态',
			'is_deleted' => '删除',
			'create_time' => '绑定时间',
			'note' => '备注',
			'startTime' => '绑定开始时间',
			'endTime' => '绑定结束时间',
			'employeeName' => '员工',
			'OAName' => 'OA',
			'employeeMobile' => '员工手机',
			'customerName' => '姓名',
			'customerAcc' => '彩之云账号',
			'customerMobile' => '彩之云手机',
		);
	}


	/**
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => null,
				'setUpdateOnCreate' => false,
			),
			'IsDeletedBehavior' => array(
				'class' => 'common.components.behaviors.IsDeletedBehavior',
			),
			'StateBehavior' => array(
				'class' => 'common.components.behaviors.StateBehavior',
			),
		);
	}


	public function checkQuery($attribute, $params)
	{
		if (!$this->hasErrors() && !empty($this->employee_id) && !empty($this->customer_id)) {
			$model = self::model()->find('employee_id=:employee_id and customer_id=:customer_id and state=:state and is_deleted=:is_deleted', array(':employee_id' => $this->employee_id, ':customer_id' => $this->customer_id, ':state' => 0, ':is_deleted' => 0));
			if ($model) {
				$this->addError($attribute, '已经绑定过彩之云，不能再绑定');
			}
		}
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

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('employee_id', $this->employee_id);
		$criteria->compare('customer_id', $this->customer_id);
		$criteria->compare('mobile', $this->mobile, true);
		$criteria->compare('state', $this->state);
		$criteria->compare('is_deleted', $this->is_deleted);
		$criteria->compare('create_time', $this->create_time);
		$criteria->compare('note', $this->note, true);

		if ($this->startTime != '') {
			$criteria->compare("create_time", ">= " . strtotime($this->startTime . " 00:00:00"));
		}
		if ($this->endTime != '') {
			$criteria->compare("create_time", "<= " . strtotime($this->endTime . " 23:59:59"));
		}

//        if ($this->employeeName!= '') {
//            $criteria->with[] = 'employee';
//            $criteria->compare("employee.name", $this->employeeName);
//        }
//        if ($this->employeeMobile!= '') {
//            $criteria->with[] = 'employee';
//            $criteria->compare("employee.mobile", $this->employeeMobile);
//        }
//        if ($this->OAName!= '') {
//            $criteria->with[] = 'employee';
//            $criteria->compare("employee.username", $this->OAName);
//        }

//      ICE 上面三个搜索就是为了让查询更准确，而不是用表里面的什么，所以按照以下接入ice
		$employee_ids = array();
		if ($this->employeeName != '') {
			$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->employeeName));
			if (!empty($employees)) {
				foreach ($employees as $employee) {
					$employee_ids[] = $employee['czyId'];
				}
			}
		}
		if ($this->employeeMobile != '') {
			$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->employeeMobile));
			if (!empty($employees)) {
				foreach ($employees as $employee) {
					$employee_ids[] = $employee['czyId'];
				}
			}
		}
		if ($this->OAName != '') {
			$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->OAName));
			if (!empty($employees)) {
				foreach ($employees as $employee) {
					$employee_ids[] = $employee['czyId'];
				}
			}
		}
		if (!empty($employee_ids)) {
			$criteria->addInCondition('t.employee_id', array_unique($employee_ids));
		}


		if ($this->customerName != '') {
			$criteria->with[] = 'customer';
			$criteria->compare("customer.name", $this->customerName);
		}
		if ($this->customerMobile != '') {
			$criteria->with[] = 'customer';
			$criteria->compare("customer.mobile", $this->customerMobile);
		}
		if ($this->customerAcc != '') {
			$criteria->with[] = 'customer';
			$criteria->compare("customer.username", $this->customerAcc);
		}
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}


	public function getEmployeeName()
	{
//         return empty($this->employee)?'':$this->employee->name;
		if (!empty($this->employee_id)) {
			$employee = ICEEmployee::model()->findbypk($this->employee_id);
			if (!empty($employee['name'])) {
				return $employee['name'];
			}

		}

		return '';
	}


	public function getOAUserName()
	{
		// return empty($this->employee)?'':$this->employee->username;
		if (!empty($this->employee_id)) {
			$employee = Employee::model()->findbypk($this->employee_id);
			if (isset($employee['username']) && $employee['username'] != '') {
				return $employee['username'];
			} else {
				return '';
			}
		}

		return '';
	}


	public function getEmployeeMobile()
	{
		// return empty($this->employee)?'':$this->employee->mobile;
		//        return empty($this->employee)?'':$this->employee->mobile;
//      ICE接入
		if (!empty($this->employee_id)) {
			$employee = ICEEmployee::model()->findbypk($this->employee_id);
			if (!empty($employee->mobile)) {
				return $employee->mobile;
			}

		}

		return '';
	}


	public function getCustomerName()
	{
		return empty($this->customer) ? '' : $this->customer->name;
	}


	public function getCustomerAcc()
	{
		return empty($this->customer) ? '' : $this->customer->username;
	}


	public function getCustomerMobile()
	{
		return empty($this->customer) ? '' : $this->customer->mobile;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CaiRedpacketBindColourlife the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}
