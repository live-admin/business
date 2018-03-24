<?php

/**
 * This is the model class for table "employee_integral_log".
 *
 * The followings are the available columns in table 'employee_integral_log':
 * @property integer $id
 * @property integer $employee_id
 * @property integer $type
 * @property integer $credit
 * @property string $note
 * @property integer $create_time
 */
class EmployeeIntegralLog extends CActiveRecord
{
	public $start_time;
	public $end_time;
	public $modelName = '员工积分日志';
	const TYPE_INTEGER_PLUS = 1;
	const TYPE_INTEGER_MINUS = 0;
	public static $_type = array(
		self::TYPE_INTEGER_MINUS => '支出',
		self::TYPE_INTEGER_PLUS => '获取',
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'employee_integral_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, credit', 'required'),
			array('note', 'safe', 'on' => 'plus, minus'),
			array('employee_id, credit, type', 'numerical', 'integerOnly' => true),
			array('type', 'length', 'max' => 4),
			array('note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, employee_id, type, credit, note, create_time, start_time, end_time', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'employee_id' => '员工',
			'type' => '积分操作类型',
			'credit' => '积分',
			'note' => '备注信息',
			'create_time' => '创建时间',
			'start_time' => '开始时间',
			'end_time' => '结束时间',
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

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
//        if(!empty($this->employee_id)){
//            $employee = Employee::model()->findByAttributes(array('username' => $this->employee_id));
//            if(!empty($employee)){
//                $criteria->compare('employee_id', $employee->id);
//            }
//        }
//		ICE接入搜索username名字
		if (!empty($this->employee_id)) {
//			这里的employee_id 是username。
			$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword'=>$this->employee_id));
			if (count($employees)>0) {
				$employee_id = array();
				foreach ($employees as $employee) {
					$employee_id[] = !empty($employee['czyId'])?$employee['czyId']:'';
				}
				$criteria->compare('employee_id', $employee_id);
			}
		}
		$criteria->compare('type', $this->type);
		$criteria->compare('credit', $this->credit);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('create_time', $this->create_time);

		if ($this->start_time != "") {
			$criteria->addCondition('create_time>=' . strtotime($this->start_time));
		}
		if ($this->end_time != "") {
			$criteria->addCondition('create_time<=' . strtotime($this->end_time . " 23:59:59"));
		}

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'create_time DESC'
			),
		));
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
	public function backend_search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('type', $this->type);
		$criteria->compare('credit', $this->credit);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('create_time', $this->create_time);
		$criteria->compare('t.employee_id', $this->employee_id);

		if ($this->start_time != "") {
			$criteria->addCondition('create_time>=' . strtotime($this->start_time));
		}
		if ($this->end_time != "") {
			$criteria->addCondition('create_time<=' . strtotime($this->end_time . " 23:59:59"));
		}

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'create_time DESC'
			),
		));
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => null,
				'setUpdateOnCreate' => true,
			),
		);
	}

	public function getEmployeeName()
	{
		$employee = Employee::model()->findByPk($this->employee_id);
		if (!empty($employee)) {
			return $employee->username;
		}
		return '';
	}

	protected function beforeSave()
	{
		if ('plus' == $this->getScenario() || 'minus' == $this->getScenario()) {
			$employee = Employee::model()->findByPk($this->employee_id);
			if ('plus' == $this->getScenario()) {
				$this->type = self::TYPE_INTEGER_PLUS;
				$employee->integral += $this->credit;
			} else {
				if ($employee->integral < $this->credit) {
					$this->addError('note', '员工积分不足');
					return false;
				}
				$this->type = self::TYPE_INTEGER_MINUS;
				$employee->integral -= $this->credit;
			}
			if (!$employee->save()) {
				$this->addError('note', '员工积分操作失败!');
				return false;
			}
			$this->employee_id = $employee->id;
			$this->note = '员工【' . $this->employee_id . '】' . ('plus' == $this->getScenario() ? '增加' : '减少') . '了【' . $this->credit . '】积分  ' . $this->note;
		}
		return parent::beforeSave();
	}

	public function getCreditName()
	{
		if (self::TYPE_INTEGER_PLUS == $this->type) {
			$credit = '+' . $this->credit;
		} else {
			$credit = '-' . $this->credit;
		}
		return $credit;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmployeeIntegralLog the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * 创建员工积分记录日志
	 * @param integer $employee_id 员工ID
	 * @param integer $type 操作类型
	 * @param integer $credit 积分值
	 * @param string|null $note 备注
	 * @return bool
	 */
	public static function createLog($employee_id, $type, $credit, $note = null)
	{
		$model = new self;
		$model->employee_id = $employee_id;
		$model->type = $type;
		$model->credit = $credit;
		$model->note = $note;
		return $model->save();
	}

	public function getEmployeeIntegral()
	{
		/**
		 * @var Employee $employee
		 */
		$integral = 0;
		if ($employee = Employee::model()->findByPk(Yii::app()->user->id)) {
			$integral = $employee->integral;
		}
		return $integral;
	}
}
