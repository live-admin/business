<?php

/**
 * This is the model class for table "employee".
 *
 * The followings are the available columns in table 'employee':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $mobile
 * @property string $tel
 * @property string $name
 * @property string $oa_username
 * @property string $email
 * @property integer $create_time
 * @property integer $last_time
 * @property string $last_ip
 * @property integer $state
 * @property integer $is_deleted
 * @property string $job_name
 */
class EmployeeOa extends CActiveRecord
{
	/**
	 * @var string 模型名
	 */
	public $modelName = '员工oa';
	public $branch_id;
	public $branch_ids = array();

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Employee the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'employee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password', 'required', 'on' => 'create'),
			array('username', 'unique', 'on' => 'create'),
			array('name', 'required', 'on' => 'create, update'),
			array('password', 'required', 'on' => 'change_password,reset'),
			array('username', 'length', 'encoding' => 'UTF-8', 'max' => 255, 'on' => 'create'),
			array('name, oa_username', 'length', 'encoding' => 'UTF-8', 'max' => 255, 'on' => 'create, update, update_profile'),
			array('email', 'length', 'encoding' => 'UTF-8', 'max' => 50, 'on' => 'create, update, update_profile'),
			array('mobile, tel', 'length', 'encoding' => 'UTF-8', 'max' => 15, 'on' => 'create, update, update_profile'),
			array('email', 'email', 'on' => 'create, update, update_profile'),
			array('mobile', 'common.components.validators.ChinaMobileValidator', 'on' => 'create, update, update_profile'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('username, mobile, name, oa_username, email, tel, state,branch_id', 'safe', 'on' => 'search'),
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
			'branch' => array(self::HAS_MANY, 'EmployeeBranchRelation', 'employee_id'),
			//'accept_employee' => array(self::HAS_MANY, 'Routine', 'accept_employee_id'),
			//'complete_employee' => array(self::HAS_MANY, 'Routine', 'complete_employee_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'branch_id' => '部门',
			'branchName' => '部门',
			'username' => '帐号',
			'password' => '密码',
			'mobile' => '手机号',
			'tel' => '电话 ',
			'name' => '姓名',
			'oa_username' => 'OA帐号',
			'email' => 'Email',
			'create_time' => '创建时间',
			'last_time' => '最后登录时间',
			'last_ip' => '登录IP',
			'state' => '状态',
			'branch_ids' => '部门',
			'job_name' => '职位',
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
				'updateAttribute' => null,
			),
			'IsDeletedBehavior' => array(
				'class' => 'common.components.behaviors.IsDeletedBehavior',
			),
			'StateBehavior' => array(
				'class' => 'common.components.behaviors.StateBehavior',
			),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$employee = Employee::model()->findByPk(Yii::app()->user->id);

		$criteria->addCondition('t.id <>' . Yii::app()->user->id);
		//选择的组织架构ID
		if (!empty($this->branch_id)) {
			$criteria->distinct = true;
			$criteria->join = 'inner join employee_branch_relation ebr on ebr.employee_id=t.id';
			$branch = Branch::model()->findByPk($this->branch_id);
			if (!empty($branch)) {
				$criteria->addInCondition('ebr.branch_id', $branch->getChildrenIdsAndSelf());
			}
		} else { //自己的组织架构的ID
			//搜索组织架构显示的数据要在登陆帐号所管理的组织下
			$criteria->distinct = true;
			$criteria->join = 'inner join employee_branch_relation ebr on ebr.employee_id=t.id';
			$criteria->addInCondition('ebr.branch_id', $employee->getAllBranchIds());
		}


		$criteria->compare('username', $this->username, true);

		$criteria->compare('mobile', $this->mobile, true);
		$criteria->compare('tel', $this->tel, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('oa_username', $this->oa_username, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('state', $this->state);

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/*
	 * 记录最后访问
	 */
	public function updateLast()
	{
		$this->updateByPk($this->id, array(
			'last_time' => time(),
			'last_ip' => Yii::app()->getRequest()->getUserHostAddress(),
		));
	}

	public function getBranchName()
	{
		if ($this->id === null) {
			return "-";
		} else if ($this->id) {
			$data = EmployeeBranchRelation::model()->findAll("employee_id=" . $this->id);
			if (!empty($data) && is_array($data)) {
				$branch_name = "";
				foreach ($data as $val) {
					$branch_name .= Branch::getMyParentBranchName($val['branch_id'], true) . "<br/>";
				}
				return $branch_name;
			} else if (empty($data)) {
				return "-";
			}
		}
	}


	public function getBranchFull()
	{
		$return = '';
		if (!empty($this->branch)) {
			$return = $this->branch->getAllBranch($this->branch->id);
			// $return = implode("/",$branchArr);
			$temp = array();
			for ($i = count($return); $i > 0; $i--) {
				$temp[] = $return[($i - 1)];
			}

		}
		return $temp;
	}

	//得到用户所在的管辖部门ID
	public function getMyBranchId()
	{
		$data = array();
		if (empty($this->branch)) {
			throw new CHttpException('400', "用户未关联组织架构,请联系管理员！");
		}
		$branchIds[] = array_map(function ($record) {
			return $record->branch_id;
		}, $this->branch);
		foreach ($branchIds as $branchId) {
			$branch = Branch::model()->findByPk($branchId);
			if ($branch->type == Branch::TYPE_RANGE) {
				$tmp[] = $branch->id;
			} else { //取branch_id的数据
				$tmp[] = $branch->branch->id;
			}
			$data = array_unique(array_merge($data, $tmp));
		}
		return $data;
	}

	//获得用户的部门及下级部门的id
	public function getBranchIds()
	{
		$data = array();
		if (empty($this->branch)) {
			throw new CHttpException('400', "用户未关联组织机构,请联系管理员！");
		}

		//得到所有的组织管辖ID
		$branch_Ids = array_map(function ($record) {
			return $record->branch_id;
		}, $this->branch);

		//第一次遍历解决管辖部门和组织部门的问题
		foreach ($branch_Ids as $branchId) {
			$branch = Branch::model()->findByPk($branchId);
			if ($branch->type == Branch::TYPE_RANGE) {
				$tmp = $branch->getBranchIds();
			} else { //取branch_id的数据
				$tmp = $branch->branch->getBranchIds();
			}
			$data = array_unique(array_merge($data, $tmp));
		}

		return $data;
	}


	//取得所有的组织架构ID，包括职能部门
	public function getAllChildBranchIds()
	{
		$data = array();
		if (empty($this->branch))
			return $data;

		$data = array_map(function ($record) {
			return $record->branch_id;
		}, $this->branch);

		$criteria = new CDbCriteria;
		$criteria->addInCondition('id', $data);
		$list = Branch::model()->findAll($criteria);

		if (!empty($list) && is_array($list)) {
			foreach ($list as $key => $val) {
				$data = array_unique(array_merge($data, $val->branch->getBranchIds()));
			}
		}
		//返回去除重复的数据
		return $data;
	}


	public function getChildBranchIds()
	{
		$data = array();
		if (empty($this->branch))
			return $data;

		$data = array_map(function ($record) {
			return $record->branch_id;
		}, $this->branch);

		$criteria = new CDbCriteria;
		$criteria->addInCondition('id', $data);
		$list = Branch::model()->findAll($criteria);

		if (!empty($list) && is_array($list)) {
			foreach ($list as $key => $val) {
				$data = array_unique(array_merge($data, $val->getBranchIds()));
			}
		}
		//返回去除重复的数据
		return $data;
	}

//查詢所有部門ID，包括职能部門还有自己
	public function getAllBranchIds()
	{
		$data = array();
		if (empty($this->branch))
			return $data;

		$data = array_map(function ($record) {
			return $record->branch_id;
		}, $this->branch);

		$criteria = new CDbCriteria;
		$criteria->addInCondition('id', $data);
		$list = Branch::model()->findAll($criteria);

		if (!empty($list) && is_array($list)) {
			foreach ($list as $key => $val) {
				$data = array_unique(array_merge($data, $val->getAllBranchIds()));
			}
		}
		//返回去除重复的数据
		return $data;
	}

	public function getTagHtml($spanValue = "")
	{
		return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' =>
			'所属部门:' . $this->branchName .
			'    手机号:' . $this->mobile .
			'    办公电话:' . $this->tel .
			'    Email:' . $this->email
		), $spanValue);
	}

	//得到组织ID，合并组织架构ID
	public function getMergeBranch()
	{
		if (empty($this->branch))
			throw new CHttpException('400', "用户未关联组织架构,请联系管理员！");

		//得到所有的组织管辖ID
		$branch_Ids = array_map(function ($record) {
			return $record->branch_id;
		}, $this->branch);

		//第一次遍历解决管辖部门和组织部门的问题
		foreach ($branch_Ids as $branchId) {
			$branch = Branch::model()->findByPk($branchId);
			if ($branch->type == Branch::TYPE_RANGE) {
				$branchIds[] = $branch->id;
			} else {
				$branchIds[] = $branch->branch_id;
			}
		}

		$data = &$branchIds;
		foreach ($branchIds as $branchId) {
			//取得所有的子集
			$branch = Branch::model()->findByPk($branchId);
			//如果是父级直接返回
			if ($branch->parent_id == 0)
				return array($branch->id);
			else {
				$tmp = $branch->getBranchIds();
				foreach ($data as $key => $val) {
					if (($branchId != $val) && (in_array($val, $tmp))) {
						unset($data[$key]);
					}
				}
			}

		}
		return array_unique($data);
	}


	public function getBranchTreeData()
	{
		$list = $data = $branch_Ids = array();
		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		$data = $employee->getAllBranchIds();

		if (!empty($this->branch)) {
			$branch_Ids = array_map(function ($record) {
				return $record->branch_id;
			}, $this->branch);
		}
		foreach ($data as $treeData) {
			$branch = Branch::model()->findByPk($treeData);
			$data = array('id' => "$branch->id", 'pId' => "$branch->parent_id", 'name' => "$branch->name", 'open' => true);
			if (in_array($branch->id, $branch_Ids))
				$data['checked'] = 'true';

			$list[] = $data;
		}

		return $list;
	}

	//取得最大的组织架构ID
	public function getMaxBranchId()
	{
		$branch_Ids = array();

		if (!empty($this->branch)) {
			$branch_Ids = array_map(function ($record) {
				return $record->branch_id;
			}, $this->branch);
		}

		return max($branch_Ids);
	}

	//根据用户名判断用户是否存在
	public static function checkIsExist($username)
	{
		return EmployeeOa::model()->find('username=:username', array(':username' => $username));
	}

	public function updateCzyId()
	{
		Yii::log(
			sprintf(
				'更新 czyId %s[%s]',
				$this->username, $this->id
			),
			CLogger::LEVEL_ERROR,
			'colourlife.core.common.models.EmployeeOa'
		);
		try {
			ICEService::getInstance()->dispatch(
				'account',
				array(),
				array(
					'employeeAccount' => $this->username,
					'czyId' => $this->id
				),
				'put'
			);
		} catch (Exception $e) {
			Yii::log(
				sprintf(
					'更新 czyId %s[%s] 失败 %s[%s]',
					$this->username, $this->id,
					$e->getMessage(), $e->getCode()
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.common.models.EmployeeOa'
			);
		}
	}
}
