<?php

/**
 * This is the model class for table "employee".
 *
 * The followings are the available columns in table 'employee':
 * @property integer $id
 * @property integer $branch_id
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
 * @property integer $integral
 */
class Employee extends CActiveRecord
{
	/**
	 * @var string 模型名
	 */
	public $modelName = '员工';
	public $branch_id;
	public $branch_ids = array();
	public $employee_name;
	public $remark;

	public $keyword;

	public $gender;
	public $pay_password;
	private $finance_employee_sync_failed_queue = 'finance_employee_sync_failed'; //失败的用户信息
	public $ice;

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
			array('balance,integral, remark, ice', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('username, mobile, name, oa_username, email, tel, state, branch_id, balance, pay_password', 'safe', 'on' => 'search'),
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
			'third_party_account' => array(self::BELONGS_TO, 'ThirdPartyAccount', 'remark'),
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
			'integral' => '积分',
			'remark' => '第三方账号',
			'balance' => '红包余额',
			'pay_password' => '支付密码',
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
			'UserBehavior' => array(
				'class' => 'common.components.behaviors.UserBehavior',
				'isOaUpdatePassword' => true,
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

		//$employee = Employee::model()->findByPk(Yii::app()->user->id);

		$criteria->addCondition('t.id <>' . Yii::app()->user->id);

		//选择的组织架构ID
		if (!empty($this->branch_id)) {
			$criteria->distinct = true;
			$criteria->join = 'inner join employee_branch_relation ebr on ebr.employee_id=t.id';
			$branch = Branch::model()->findByPk($this->branch_id);
			if (!empty($branch)) {
				$criteria->addInCondition('ebr.branch_id', $branch->getChildrenIdsAndSelf());
			}
		}

		/*fix bug 1267 去掉部门权限控制
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
		}*/


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


	public function searchNew()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		//$employee = Employee::model()->findByPk(Yii::app()->user->id);

		$criteria->addCondition('t.id <>' . Yii::app()->user->id);
		$criteria->addCondition("t.remark!=0");

		//选择的组织架构ID
		if (!empty($this->branch_id)) {
			$criteria->distinct = true;
			$criteria->join = 'inner join employee_branch_relation ebr on ebr.employee_id=t.id';
			$branch = Branch::model()->findByPk($this->branch_id);
			if (!empty($branch)) {
				$criteria->addInCondition('ebr.branch_id', $branch->getChildrenIdsAndSelf());
			}
		}

		/*fix bug 1267 去掉部门权限控制
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
		}*/


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

	//部门或者小区公司
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

	public function getMyBranchName()
	{
		$branch_name = '';
		$branchid = $this->getMergeBranch();
		if (!empty($branchid) && is_array($branchid)) {
			$branchid = $branchid[0];
			$branch = Branch::model()->findBypk($branchid);
			$branch_name = !empty($branch) ? $branch->name : '';
		}

		return $branch_name;
	}

	public function getBranchFull()
	{
		$return = '';
		$temp = array();
		if (!empty($this->branch) && !empty($this->branch[0]->branch)) {
			$return = $this->branch[0]->branch->getAllBranch($this->branch[0]->branch->id);
			// $return = implode("/",$branchArr);
			$temp = array();
			for ($i = count($return); $i > 0; $i--) {
				$temp[] = $return[($i - 1)];
			}

		}

		return $temp;
	}


	public function getBranchBYONE()
	{
		$return = "";
		$temp = "";
		if (!empty($this->branch) && !empty($this->branch[0]->branch)) {
			$return = $this->branch[0]->branch->getAllBranch($this->branch[0]->branch->id);
			// $return = implode("/",$branchArr);
			// $temp = "";
			for ($i = count($return); $i > 0; $i--) {
				$temp .= "-" . $return[($i - 1)];
			}

		}
		return substr($temp, 1);
	}


	public function getSimpleBranch()
	{
		$data = EmployeeBranchRelation::model()->find("employee_id=" . $this->id);
		if (!empty($data)) {
			$model = Branch::model()->findByPk($data->branch_id);
			$branch_name = "";
			if ($model) {
				$branch_name = $model->name;
			} else {
				$branch_name = "彩生活服务集团";
			}
			return $branch_name;
		} else if (empty($data)) {
			return "-";
		}

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

	//获得用户的部门及下级部门的id ICE 获取的是表里面的
	static function ICEgetOldBranchIds()
	{
		$data = array();
		$branch = EmployeeBranchRelation::model()->findAllByAttributes(array('employee_id' => YII::app()->user->id));
		if (empty($branch)) {
			throw new CHttpException('400', "用户未关联组织机构,请联系管理员！");
		}

		//得到所有的组织管辖ID
		$branch_Ids = array_map(function ($record) {
			return $record->branch_id;
		}, $branch);

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
				$data = array_unique(array_merge($data, (empty($val->branch) ? array() : $val->branch->getBranchIds())));
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
		// var_dump($this->branch);die;
		$criteria = new CDbCriteria;
		$criteria->addInCondition('id', $data);
		// $criteria->compare('`t`.parent_id',1);
		$list = Branch::model()->findAll($criteria);

		// var_dump($list);die;
		if (!empty($list) && is_array($list)) {
			foreach ($list as $key => $val) {
				// var_dump($val->branch);
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

	//得到组织ID，合并组织架构ID
	static function ICEGetOldMergeBranch()
	{

		$branch = EmployeeBranchRelation::model()->findAllByAttributes(array('employee_id' => YII::app()->user->id));
//		if (empty($this->branch))
//			throw new CHttpException('400', "用户未关联组织架构,请联系管理员！");
		if (empty($branch))
			throw new CHttpException('400', "用户未关联组织架构,请联系管理员！");

		//得到所有的组织管辖ID
//		$branch_Ids = array_map(function ($record) {
//			return $record->branch_id;
//		}, $this->branch);

		//得到所有的组织管辖ID
		$branch_Ids = array_map(function ($record) {
			return $record->branch_id;
		}, $branch);

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
		//return ICEEmployee::model()->ICEGetBranchTreeData();
		$list = $data = $branch_Ids = array();
		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		//var_dump($employee);die;
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

	//得到所有的组织架构的树结构  同上，只是没有判断权限，供选择员工弹出层用
	public function getAllBranchTreeData()
	{
		//return ICEEmployee::model()->ICEGetAllBranchTreeData();
		$maxBranchId = 1; //现系统最大为彩生活组织架ID
		$list = $data = $branch_Ids = array();
		$employee = Employee::model()->findByPk(Yii::app()->user->id);

		if (!empty($employee->branch)) {
			$branch_Ids = array_map(function ($record) {
				return $record->branch_id;
			}, $employee->branch);
		}
		if (!empty($branch_Ids)) {
			$branch_id = max($branch_Ids); //取大的组织架构ID
			$divisionIds = Branch::model()->findbypk($branch_id)->getLevelBranchIds();
		}

		if (isset($divisionIds[0]))
			$maxBranchId = $divisionIds[0]; //取最大级的组织架构ID

		$data = Branch::model()->findByPk($maxBranchId)->getAllBranchIds();

		foreach ($data as $treeData) {
			$branch = Branch::model()->findByPk($treeData);
			$data = array('id' => "$branch->id", 'pId' => "$branch->parent_id", 'name' => "$branch->name", 'open' => false);
			if (in_array($branch->id, $branch_Ids))
				$data['checked'] = 'true';

			$list[] = $data;
		}

		return $list;
	}

	//判断是否有职位
	public function getIsPosition($position_id)
	{
		$data = EmployeePositionRelation::model()->find('employee_id=:employee_id and position_id=:position_id',
			array('position_id' => $position_id, 'employee_id' => $this->id));
		return empty($data) ? false : true;
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

	//根据组织架构ID获取员工详情
	public function getEmployeeDetails($id = "", $ids = array(), $page = 1, $pagesize = 10)
	{
		$data = array();
		if ($id != "") {
			$criteria = new CDbCriteria;
			// $criteria->with[] = "branch";
			$criteria->join = "LEFT JOIN employee_branch_relation branch ON branch.employee_id=`t`.id";
			$criteria->compare("branch.branch_id", $id);
			if (!empty($ids)) {
				$criteria->addNotInCondition("`t`.id", $ids);
			}
			$criteria->compare("state", "0");
			$criteria->group = "branch.employee_id";
			$page = ($page - 1);
			$criteria->limit = $pagesize;
			$criteria->offset = ($page * $pagesize);
			$data = Employee::model()->findAll($criteria);
		}
		return $data;
	}

	public function getEmployeeName($employee_id = "")
	{
		if ($employee_id == "") {
			return "";
		} else {
			$data = Employee::model()->findByPk($employee_id);
			if (empty($data)) {
				return "";
			} else {
				return $data->name;
			}
		}
	}

	//根据用户名判断用户是否存在
	public static function checkIsExist($username)
	{
		return Employee::model()->find('username=:username', array(':username' => $username));
	}


	//调用OA修改密码的API修改OA的密码
	//$pwd是明文
	public function updatePwdByOa($username, $pwd)
	{
		Yii::import('common.api.ColorCloudApi');
		$colure = ColorCloudApi::getInstance();
		$res = $colure->callGetUserCheck($username, "");
		if ($res['total'] == 1) {
			$result = $colure->callSetUserUpPwd($username, $pwd);
			return $result['total'];
		} else {
			return 0;
		}
	}


	public function getBranchNameForEDAI()
	{
		if ($this->id === null) {
			return "";
		} else if ($this->id) {
			$data = EmployeeBranchRelation::model()->findAll("employee_id=" . $this->id);
			if (!empty($data) && is_array($data)) {
				$branch_name = "";
				foreach ($data as $val) {
					$branch_name .= '^' . $val->branch->name;
				}
				return substr($branch_name, 1);
				// var_dump(substr($branch_name, 1));die;
			} else if (empty($data)) {
				return "";
			}
			var_dump($data);
			die;
		}
	}


	public function getBranchNames()
	{
		if ($this->id === null) {
			return "-";
		} else if ($this->id) {
			$data = EmployeeBranchRelation::model()->findAll("employee_id=" . $this->id);
			if (!empty($data) && is_array($data)) {
				$branch_name = "";
				foreach ($data as $val) {
					$branch_name .= Branch::getMyParentBranchName($val['branch_id'], true);
				}
				return $branch_name;
			} else if (empty($data)) {
				return "-";
			}
		}
	}


	public function loginByOaUrl($parameter)
	{
		$mykey = "abcd1234";
		$iv = "abcd1234";
		$des_model = new Des(base64_encode($mykey), base64_encode($iv));
		$result = $des_model->decrypt($parameter);
		$arr = json_decode($result, true);
		$model = new LoginForm;
		$model->username = $arr['username'];
		$model->password = $arr['pwd'];
		if ($model->validate() && $model->login()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 根据传入的员工ID获取员工名字
	 * @param array|string|array Employee $id
	 * @return array|mixed|string
	 */
	public static function getEmployeeNames($id)
	{
		//return ICEEmployee::getEmployeeNames($id);
		$flag = false;
		if (empty($id)) {
			return '';
		}
		if (!is_array($id)) {
			$flag = true;
			$id = array($id);
		}
		array_unique($id);
		if (current($id) instanceof Employee) {
			$model = $id;
		} else {
			$cdb = new CDbCriteria();
			$cdb->addInCondition('id', $id);
			$model = self::model()->findAll($cdb);
		}

		$names = array_map(function (Employee $model) {
			$mobile = empty($model->mobile) ? "()" : "(" . $model->mobile . ")";
			return empty($model->name) ? $model->username . $mobile : $model->name . $mobile;
		}, $model);

		if ($flag) {
			return current($names);
		}
		return $names;
	}

	/**
	 * 扣除积分
	 * @param $sn 订单sn
	 * @param $integration扣除的积分数
	 * @return bool
	 */
	public function delIntegration($sn, $integration)
	{
		$integration = intval($integration);//积分只能是整数
		try {
			//检测是否有积分
			if ($this->integral >= $integration) {
				//扣积分写入日志
				$this->integral = $this->integral - $integration;
				$type = EmployeeIntegralLog::TYPE_INTEGER_MINUS;
				$note = '【' . $this->name . '】下内部采购订单【' . $sn . '】消费积分' . $integration;
				if ($this->update() && EmployeeIntegralLog::createLog($this->id, $type, $integration, $note)) {
					return true;
				}
			} else {
				throw new Exception('消费红包失败！');
			}
		} catch (Exception $e) {
			throw new Exception('消费红包失败！');
		}
		return false;
	}

	/**
	 * 加退积分
	 * @param $sn 订单sn
	 * @param $integration扣除的积分数
	 * @return bool
	 */
	public function addIntegration($sn, $integration, $note = '')
	{
		$integration = intval($integration);//积分只能是整数
		try {
			//检测是否有积分
			if ($integration > 0) {
				//扣积分写入日志
				$this->integral = $this->integral + $integration;
				$type = EmployeeIntegralLog::TYPE_INTEGER_PLUS;
				$myNote = '【' . $this->name . '】' . (($note != '') ? $note : '操作订单【' . $sn . '】获得积分：' . $integration);
				if ($this->update() && EmployeeIntegralLog::createLog($this->id, $type, $integration, $myNote)) {
					return true;
				}
			} else {
				throw new Exception('获得红包失败！');
			}
		} catch (Exception $e) {
			throw new Exception('获得红包失败！');
		}
		return false;
	}

	public function getBranch_ids()
	{
		$data = EmployeeBranchRelation::model()->findAll('employee_id =:employee_id',
			array(':employee_id' => $this->id));
		$return = array();
		foreach ($data as $key => $value) {
			$return[] = $value->branch_id;
		}
		//return CHtml::listData($data, '', 'branch_id');
		return $return;
	}

	public function getBranch_id_one()
	{
		$data = EmployeeBranchRelation::model()->findAll('employee_id =:employee_id',
			array(':employee_id' => $this->id));
		$return = array();
		foreach ($data as $key => $value) {
			$return[] = $value->branch_id;
		}
		return $return[0];
	}


	public function getYearSimpleBranch()
	{
		$data = EmployeeBranchRelation::model()->find("employee_id=" . $this->id);
		if (!empty($data)) {
			$model = Branch::model()->findByPk($data->branch_id);
			$branch_name = "";
			if ($model) {
				$branch_name = "彩生活服务集团-" . $model->name;
			} else {
				$branch_name = "彩生活服务集团";
			}
			return $branch_name;
		} else if (empty($data)) {
			return "-";
		}

	}

	public function findByPk($pk = '', $condition = '', $params = array(), $ice = false)
	{
		if ($pk == 1) {
			return parent::findByPk($pk, $condition, $params);
		}

		return $ice == true
			? ICEEmployee::model()->findByPk($pk, $condition, $params)
			: parent::findByPk($pk, $condition, $params);
	}

	public function getBalance()
	{
		$employeeAccount = FinanceEmployeeRelateModel::model()->find(
			'employee_id = :employee_id',
			array(
				':employee_id' => $this->id
			)
		);
		if (!$employeeAccount) {
			Yii::log(
				sprintf(
					'获取账户余额失败，账号未同步至金融平台: %s.',
					$this->username
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.common.models.Employee.getBalance'
			);
			FinanceAccountRelationService::getInstance()->migrateEmployee($this->username, true);
			throw new CHttpException(400, '获取账户余额失败：系统正在同步，请稍后再试！');
		}

		$clientAccount = array();
		try {
			$clientAccount = FinanceMicroService::getInstance()->queryClient(
				$employeeAccount['pano'],
				$employeeAccount['cano']
			);
		} catch (Exception $e) {
			Yii::log(
				sprintf(
					'获取账户余额失败: %s[%s]， %s[%s]',
					$this->username,
					$this->id,
					$e->getMessage(),
					$e->getCode()
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.common.models.Employee.getBalance'
			);
		}

		if ($clientAccount
			&& isset($clientAccount['account'])
			&& isset($clientAccount['account']['money'])
		) {
			Yii::log(
				sprintf(
					'获取到的账户余额: %s[%s]， %s',
					$this->username,
					$this->id,
					$clientAccount['account']['money']
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.common.models.Employee.getBalance'
			);
			return $clientAccount['account']['money'];
		} else {
			Yii::log(
				sprintf(
					'获取账户余额失败，无法解析返回数据: %s[%s]， %s',
					$this->username,
					$this->id,
					json_encode($clientAccount)
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.common.models.Employee.getBalance'
			);
			throw new CHttpException(400, '无法获取账户余额');
		}
	}

	/**
	 * 增加彩之云全国饭票金融平台账号
	 */
	public function updateFinanceAccount()
	{
		$updateData = array();
		try {
			$result = FinanceMicroService::getInstance()->getEmployeePano();
			$pano = $result['pano'];

			$gender = $this->gender;
			$realname = $this->name;
			$mobile = $this->mobile;
			if (!$mobile) {
				$mobile = FinanceMicroService::getInstance()->randMobile();
			}
			$pay_password = $this->pay_password;
			$employee = FinanceMicroService::getInstance()->addClientClient(
				$pano,
				'',
				$realname,
				$mobile,
				$gender,
				'',
				'彩之云后台导入',
				0
			);
			//获取cano
			if ($employee && isset($employee['account'])) {
				$account = $employee['account'];
				if ($account && isset($account['cano'])) {
					$cano = $account['cano'];
				}
			}
			if ($employee && isset($employee['client'])) {
				$client = $employee['client'];
				if ($client && isset($client['cno'])) {
					$cno = $client['cno'];
				}
			}
			$updateData = array(
				'pano' => $pano,
				'cno' => $cno,
				'cano' => $cano,
				'employee_id' => intval($this->id),
				'mobile' => $mobile,
				'name' => $realname,
				'oa_username' => $this->username,
				'pay_password' => $pay_password
			);

			//更新本地数据
			FinanceEmployeeRelateModel::model()->addFinanceEmployeeRelation($updateData);
		} catch (Exception $e) {
			Yii::app()->rediscache->executeCommand(
				'RPUSH',
				array($this->finance_employee_sync_failed_queue, json_encode($updateData))
			);
			throw $e;
		}
	}
}

