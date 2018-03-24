<?php

/**
 * This is the model class for table "community_employee_relation".
 *
 * The followings are the available columns in table 'community_employee_relation':
 * @property integer $id
 * @property integer $employee_id
 * @property integer $community_id
 * @property integer $create_time
 * @property integer $create_employee_id
 */
class CommunityEmployeeRelation extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Community_Employee_Relation the static model class
     */
    /**
     * @var string 模型名
     */
    public $modelName = '物管';
    public $username;
    public $name;

	public $employee;
	public $community;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'community_employee_relation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('employee_id', 'required', 'on' => 'create'),
            array('employee_id', 'userExist', 'on' => 'create'),
            array('employee_id', 'communityUserIsExist', 'on' => 'create'),
            array('username, name', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            //'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id', 'condition' => '`employee`.`state`=0 AND `employee`.`is_deleted`=0'),
            //'community' => array(self::BELONGS_TO, 'Community', 'community_id', 'condition' => '`community`.`state`=0 AND `community`.`is_deleted`=0'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'employee_id' => '物管帐号',
            'username' => '物管帐号',
            'name' => '物管姓名',
            'branch_id' => '部门',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('community_id', $this->community_id);
        $criteria->with[] = 'employee';
        $criteria->compare('`employee`.username', $this->username, true);
        $criteria->compare('`employee`.name', $this->name, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    //用户名是否存在
    public function userExist($attribute, $params)
    {
        $user = Employee::model()->enabled()->findByPk($this->employee_id);
        if (empty($user)) {
            $this->addError($attribute, '系统没有此用户或被禁用，请确认后重新输入！');
            return false;
        }
        $this->employee_id = $user->id;
        return true;
    }

    //判断用户在此小区下是否已经存在
    public function communityUserIsExist($attribute, $params)
    {
        $user = CommunityEmployeeRelation::model()->find('employee_id=:employee_id and community_id=:community_id', array(':employee_id' => $this->employee_id, ':community_id' => $this->community_id));
        if (!empty($user)) {
            $this->addError($attribute, '此小区已存在该物管用户！');
            return false;
        }
        return true;
    }

    /**
     * 当前小区所属部门未指派的员工列表
     * @return array
     */
    public function getEmployeeDataList()
    {
        if (!empty($this->community)) {
            $criteria = new CDbCriteria;
            $ids = array();
            foreach ($this->findAllByAttributes(array('community_id' => $this->community_id)) as $model)
                $ids[] = $model->employee_id;
            $criteria->addNotInCondition('id', $ids);
            $criteria->compare('branch_id', $this->community->branch_id);
            $employees = Employee::model()->enabled()->findAll($criteria);
            return CHtml::listData($employees, 'id', 'name');
        }
        return array();
    }

    /**
     * 获取用户名
     * @param bool $username
     * @return string
     */
    public function getEmployee_Names($username = false)
    {
	    $this->employee = Employee::model()->enabled()->findByPk($this->employee_id);

        return isset($this->employee) ? ($username == false ? $this->employee->name : $this->employee->username) : '';
    }

}
