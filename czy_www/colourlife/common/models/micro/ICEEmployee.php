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
class ICEEmployee extends CActiveRecord
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

	const DISABLE_YES = 1;
	CONST DISABLE_YES_TEXT = '已禁用';
	CONST DISABLE_NO_TEXT = '已启用';

	public $uuid;
	public $orgId;
	public $jobId;
	public $jobName;
	public $landline;
	public $familyId;
	public $familyName;
	public $employeeAccount;
	public $disable;
	public $mail;
	public $mobile;
	public $realname;
	public $uptime;
	public $createtime;


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
			array('balance,integral, remark', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('username, mobile, name, oa_username, email, tel, state, branch_id, balance, pay_password, keyword', 'safe', 'on' => 'search'),
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
			'keyword' => '关键词',
			'disable' => '状态'
		);
	}

	/**
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			/*'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'updateAttribute' => null,
			),
			'IsDeletedBehavior' => array(
				'class' => 'common.components.behaviors.IsDeletedBehavior',
			),*/
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
		return $this->ICESearch();
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
		return $this->ICESearch();
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$employee = Employee::model()->findByPk(Yii::app()->user->id);

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

//　部门或者小区公司
//	ICE　返回表里面数据检查完毕不用修改
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

//	ICE返回表里的组织架构
	public function getBranchFull()
	{
//		$return = '';
//		$temp = array();
//		if (!empty($this->branch) && !empty($this->branch[0]->branch)) {
//			$return = $this->branch[0]->branch->getAllBranch($this->branch[0]->branch->id);
//			// $return = implode("/",$branchArr);
//			$temp = array();
//			for ($i = count($return); $i > 0; $i--) {
//				$temp[] = $return[($i - 1)];
//			}
//
//		}
//
//		return $temp;

//		ICE 返回表里的组织架构,接入之后 branch_id就是非数字id了,原来的失效 所以去EmployeeBranchRelation里面查在返回
		return explode(' - ', ICEBranch::model()->ICEGetMyParentBranchName($this->branch_id));
		$temp = array();
		$EmployeeBranchRelation = EmployeeBranchRelation::model()->findAllByAttributes(array('employee_id' => YII::app()->user->id));
		if (!empty($EmployeeBranchRelation)) {
			$branch = Branch::model()->findbypk($EmployeeBranchRelation[0]->branch_id);
			if (!empty($branch)) {
				$return = Branch::model()->getAllBranch($EmployeeBranchRelation[0]->branch_id);
				$temp = array();
				for ($i = count($return); $i > 0; $i--) {
					$temp[] = $return[($i - 1)];
				}
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
		return $this->ICEGetBranchIds();
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
	public function getMergeBranch($isGetIceBranchData = true)
	{
		if ($isGetIceBranchData) {
			return $this->ICEGetMergeBranch();
		}

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
			return ICEBranch::model()->ICEGetMyParentBranchName($this->branch_id);
			if ($branch && isset($branch['dn'])) {

			}

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

	protected function iceGetEmployeeName($id)
	{
		$employee = $this->findByPk($id);

		return (empty($employee->name)
				? $employee->name
				: $employee->username)
			. (empty($employee->mobile)
				? ''
				: $employee->mobile);
	}

	/**
	 * 根据传入的员工ID获取员工名字
	 * @param array|string|array Employee $id
	 * @return array|mixed|string
	 */
	public static function getEmployeeNames($id)
	{
		if (empty($id)) {
			return '';
		}
		if (!is_array($id)) {
			return self::model()->iceGetEmployeeName($id);
		} else {
			$names = array();
			foreach ($id as $item) {
				if ($item instanceof Employee) {
					$employeeId = $item->id;
				} else {
					$employeeId = $item;
				}

				$names[] = self::model()->iceGetEmployeeName($employeeId);
			}
			return $names;
		}
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
		$data = $this->ICEGetBranchIds();
		foreach ($data as $v) {
			$arr[] = $v;
		}
		return $arr;
		return $this->branch_id;
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


	protected function ICESearchPage($keyword = '', $page = 1, $pageSize = 10,
	                                 $cache = true)
	{
		if ($keyword) {
			$queryData = array(
				'type' => 'czy',
				'keyword' => $keyword,
				'page' => $page,
				'pageSize' => $pageSize
			);
		} else {
			$queryData = array(
				'type' => 'czy',
				'page' => $page,
				'size' => $pageSize
			);
		}

		$cacheKey = 'cache:cwy:ice:account:page:' . http_build_query($queryData);


		if ($cache == true) {
			$result = Yii::app()->rediscache->get($cacheKey);
			if ($result) {
				return $result;
			}
		}

		if ($keyword) {
			$result = ICEService::getInstance()->dispatch(
				'account/search',
				array(),
				$queryData,
				'post'
			);

			$list = isset($result['list']) ? $result['list'] : $result;
			$totalNum = isset($result['totalNum']) ? $result['totalNum'] : count($list);

			$result = array(
				'list' => $list,
				'totalNum' => $totalNum
			);
		} else {
			$result = ICEService::getInstance()->dispatch(
				'account/page',
				$queryData,
				array(),
				'get'
			);
		}

		if ($result) {
			Yii::app()->rediscache->set(
				$cacheKey,
				$result,
				ICEService::GetCacheExpire()
			);
		}

		return $result;
	}

	public function ICESearch()
	{
		$search = isset($_GET['ICEEmployee']) ? $_GET['ICEEmployee'] : array();
		$keyword = isset($search['keyword']) && trim($search['keyword'])
			? $search['keyword'] : '';
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		if ($keyword) {
			$result = $this->ICEPostAccountSearch(array(
				'keyword' => isset($search['keyword']) && trim($search['keyword'])
					? $search['keyword'] : '',
				'page' => $page,
				'pageSize' => 10
			));
		} else {
			$result = $this->ICEGetAccountPage(array(
				'page' => $page,
				'size' => 10
			));
		}


		$dataProvider = new CActiveDataProvider(
			$this,
			array(
				'id' => 'uuid',
				//'keyField' => 'uuid',
				'keyAttribute' => 'uuid',
				'keys' => array(
					'uuid'
				),
				'sort' => array(
					'attributes' => array('uuid')
				)
			)
		);

		//$dataProvider->setData(isset($result['list']) ? $result['list'] : array());
		$dataProvider->setData(
			$this->ICESearchDataProviderData(
				isset($result['list']) ? $result['list'] : array()
			)
		);

		$itemCount = isset($result['totalNum']) && $result['totalNum'] > 10
			? $result['totalNum']
			: 100;
		$itemCount = max($itemCount, $page * 10);
		$dataProvider->setTotalItemCount($itemCount);

		$pagination = new CPagination($itemCount);
		$pagination->params = array(
			'ICEEmployee' => $search
		);
		$pagination->pageVar = 'page';
		$pagination->setPageSize(10);
		$pagination->setItemCount($itemCount);

		$dataProvider->setPagination($pagination);

		return $dataProvider;
	}

	protected function ICESearchDataProviderData($employees = array())
	{
		if (!is_array($employees) || !$employees) {
			return array();
		}

		$data = array();

		foreach ($employees as $item) {
			$item = $this->ICEEmployeeToColourlife($item);

			$employee = new self();

			foreach ($item as $key => $value) {
				$employee->setAttribute($key, $value);
			}


			$data[] = $employee;
		}

		return $data;
	}

	protected function ICEEmployeeToColourlife($item = array())
	{
		return $item + array(
				'id' => isset($item['czyId']) && $item['czyId']
					? $item['czyId'] : '',
				'uuid' => $item['uuid'],
				'username' => $item['employeeAccount'],
				'name' => $item['realname'],
				'mobile' => $item['mobile'],
				'email' => $item['mail'],
				'state' => $item['disable'],
				'branch_id' => $item['orgId'],
				'is_deleted' => '0',
			);
	}

	public function ICEFindByPk($uuid = '', $cache = true, $expire = 1800)
	{
		if (!$uuid) {
			return null;
		}

		$cacheKey = 'cache:cwy:ice:account:detail:' . $uuid;

		if ($cache == true) {
			$result = Yii::app()->rediscache->get($cacheKey);
			if ($result) {
				return $result;
			}
		}

		$result = array();
		try {
			$result = ICEService::getInstance()->dispatch(
				'account',
				is_numeric($uuid) ? array(
//					'czhId' => (int)$uuid
					'czyId' => (int)$uuid,
					'type' => 'czy'
				) : array(
					'uid' => (string)$uuid,
					'type' => 'czy'
				),
				array(),
				'get'
			);
		} catch (Exception $e) {
			Yii::log(
				sprintf('获取ICE内部账号失败。uuid(czyId): %s', $uuid),
				CLogger::LEVEL_ERROR, 'colourlife.core.ICEEmployee.ICEFindByPk'
			);

			$result = array();
		}

		Yii::app()->rediscache->set(
			$cacheKey,
			$result,
			ICEService::GetCacheExpire()
		);

		return $result;
	}

	public function findByPk($pk = '', $condition = '', $params = array())
	{
		if ($pk == '1') {
			$employee = parent::findByPk($pk, $condition, $params);
			$handler = new self();

			if ($employee) {
				foreach ($employee->getAttributes() as $key => $value) {
					$handler->setAttribute($key, $value);
				}

				$handler->setAttribute('orgId', ICEBranch::SUPPER_UUID);
			}

			return $handler;
		} else {
			$employee = $this->ICEFindByPk($pk);
			$handler = new self();

			if ($employee) {
				$employee = $this->ICEEmployeeToColourlife($employee);

				foreach ($employee as $key => $value) {
					$handler->setAttribute($key, $value);
				}
			}

			return $handler;
		}
	}

	public function ICELogin($username = '', $password = '', $isTemp = false)
	{
		if (!$username || !$password) {
			return false;
		}
		if (!preg_match('/^[a-z0-9]{32}$/', $password)) {
			$password = md5($password);
		}
		//$password = md5($password);

		$cacheKey = sprintf('cache:cwy:ice:account:login:%s:%s', $username, md5($password));

		$user = Yii::app()->rediscache->get($cacheKey);
		if (!$user) {
			$user = ICEService::getInstance()->dispatch(
				'account/login',
				array(),
				array(
					'username' => $username,
					'password' => $password,
					'type' => 'czy'
				),
				'post'
			);

			if (!$user || !isset($user['uuid'])) {
				return null;
			}

			//Yii::app()->rediscache->set($cacheKey, $user, ICEService::GetCacheExpire());
			Yii::app()->rediscache->set($cacheKey, $user, 5);
		}

		$user['name'] = $user['username'] = isset($user['realname']) && $user['realname']
			? $user['realname'] : '';
		$user['id'] = isset($user['czyId']) && $user['czyId']
			? $user['czyId'] : '';

		foreach ($user as $key => $value) {
			if ($this->hasAttribute($key)) {
				$this->setAttribute($key, $value);
			}
		}

		return $this;
	}

	public function getByUsername($username, $password, $akaMobile = false)
	{
		//return $this->ICELogin($username, $password);
		//return $this->ICELogin($username, $password);
		if (strtolower($username) == 'cwy_admin') {
			if ($akaMobile) { // Fixme: 检测 $username 是否是手机号码
				$user = $this->find('mobile=:mobile', array(':mobile' => strtolower($username)));
				if ($user != null)
					return $user;
			}
			return $this->find('username=:username', array(':username' => $username));
		} else {
			return $this->ICELogin($username, $password);
		}
	}

	public function updateByPk($pk, $attributes, $condition = '', $params = array())
	{
		if ($pk == '1') {
			return parent::updateByPk($pk, $attributes, $condition, $params);
		} else {
			return true;
		}
	}


	public function ICEGetMergeBranch()
	{
		return $this->ICEGetBranchIds();
	}

	/**
	 * 获取 权限分配 分配到的组织结构
	 * @param bool $cache
	 * @param int $expire
	 * @return mixed
	 */
	public function ICEGetAccountJurisdiction($cache = true, $expire = 600)
	{
		$cacheKey = 'cache:cwy:ice:jurisdiction:account:' . $this->employeeAccount;

		$result = array();
		if ($cache == true) {
			$result = Yii::app()->rediscache->get($cacheKey);
		} else {
			Yii::app()->rediscache->delete($cacheKey);
		}

		if (!$result) {
			try {
				$result = ICEService::getInstance()->dispatch(
					'jurisdiction/account',
					array(
						'username' => $this->employeeAccount,
						'app_code' => 'czy'
					),
					array(),
					'get'
				);
			} catch (Exception $e) {

			}

			if ($result) {
				Yii::app()->rediscache->set(
					$cacheKey,
					$result,
					min(600, $expire)
				);
			}
		}

		return $result;
	}

	public function ICEGetBranchIds()
	{
		// TODO 后续需要注释掉
		//$this->orgId = '9959f117-df60-4d1b-a354-776c20ffb8c7';
		//$this->employeeAccount = 'mahongtao';

		if (empty($this->orgId)) {
			throw new CHttpException('400', "用户未关联组织机构,请联系管理员！");
		}


		// 内部账号所在组织结构所有子节点
		$data = ICEBranch::model()->findByPk($this->orgId)->ICEGetOrgSubs();

		// 合并 jurisdiction/account 权限分配的组织结构节点
		$jurOrgs = $this->ICEGetAccountJurisdiction();
		if ($jurOrgs && is_array($jurOrgs)) {
			foreach ($jurOrgs as $org) {
				$uuid = $org['org_id'];

				// 如果分配的权限已经包含在账号所属组织结构，则跳过
				if (in_array($uuid, $data)) {
					continue;
				}

				if ($org['is_all'] == 1) {
					$data = array_merge(
						$data,
						ICEBranch::model()->findByPk($uuid)->ICEGetOrgSubs()
					);
				} else {
					$org[] = $org['org_id'];
				}
			}
		}


		return array_unique($data);
	}

	public function ICEGetOrgCommunity($idsOnly = true, $regionId = 0)
	{
		// TODO 后续需要注释掉
		//$this->orgId = '9959f117-df60-4d1b-a354-776c20ffb8c7';
		//$this->employeeAccount = 'mahongtao';

		if (empty($this->orgId)) {
			throw new CHttpException('400', "用户未关联组织机构,请联系管理员！");
		}

		$regions = ICERegion::model()->ColourlifeRegionToICEIDs($regionId);
		$searchRegion = array(
			'provincecode' => $regions['provincecode'] ? $regions['provincecode'] : '',
			'citycode' => $regions['citycode'] ? $regions['citycode'] : '',
			'regioncode' => $regions['districtcode'] ? $regions['districtcode'] : ''
		);

		// 内部账号所在组织结构所有子节点
		$communities = ICECommunity::model()->ICECommunitySearchAllData(
			$searchRegion + array(
				'pid' => $this->orgId
			),
			true
		);


		// 内部账号所在组织结构所有子节点
		$orgs = ICEBranch::model()->findByPk($this->orgId)->ICEGetOrgSubs();

		// 合并 jurisdiction/account 权限分配的组织结构节点
		$jurOrgs = $this->ICEGetAccountJurisdiction();
		if ($jurOrgs && is_array($jurOrgs)) {
			foreach ($jurOrgs as $org) {
				$uuid = $org['org_id'];

				// 如果分配的权限已经包含在账号所属组织结构，则跳过
				if (in_array($uuid, $orgs)) {
					continue;
				}

				$communities = array_merge(
					$communities,
					ICECommunity::model()->ICECommunitySearchAllData(
						$searchRegion + array(
							'pid' => $uuid
						),
						$org['is_all'] == 1
					)
				);
			}
		}

		$communitys = array();
		foreach ($communities as $community) {
			if (!isset($community['czy_id']) || !$community['czy_id']) {
				continue;
			}

			/*if ($idsOnly) {
				$communityIDs[$community['czy_id']] = $community['czy_id'];
			} else {
				$communityIDs[$community['czy_id']] = $community;
			}*/

			$communitys[$community['czy_id']] = $community;
		}

		return $idsOnly ? array_keys($communitys) : $communitys;
	}

	/**
	 * 得到所有的组织架构的树结构
	 * @return array
	 */
	public function ICEGetBranchTreeData()
	{
		return $this->ICEGetAllBranchTreeData();
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
			$data = array(
				'id' => "$branch->id",
				'pId' => "$branch->parent_id",
				'name' => "$branch->name",
				'open' => true
			);
			if (in_array($branch->id, $branch_Ids))
				$data['checked'] = 'true';

			$list[] = $data;
		}

		return $list;
	}

	/**
	 * 得到所有的组织架构的树结构
	 * 同上，只是没有判断权限，供选择员工弹出层用
	 * @return array
	 */
	public function ICEGetAllBranchTreeData()
	{
		// TODO 后续需要注释掉
		//$this->orgId = ICEBranch::SUPPER_UUID;
		//$this->employeeAccount = 'mahongtao';

		$branchModel = ICEBranch::model()->findByPk($this->orgId);

		// 内部账号所在组织结构所有子节点
		$subs = ICEBranch::model()->findByPk($this->orgId)->ICEGetOrgSubs();
		$orgs = $branchModel->ICEGetAllOrgsTOCacheGetAndSet($this->orgId);

		// 合并 jurisdiction/account 权限分配的组织结构节点
		$jurOrgs = $this->ICEGetAccountJurisdiction();

		if ($jurOrgs && is_array($jurOrgs)) {
			foreach ($jurOrgs as $org) {
				$uuid = $org['org_id'];

				// 如果分配的权限已经包含在账号所属组织结构，则跳过
				if (in_array($uuid, $subs)) {
					continue;
				}

				if ($org['is_all'] == 1) {
					$orgs = array_merge(
						$orgs,
						$branchModel->ICEGetAllOrgsTOCacheGetAndSet($uuid)
					);
				} else {
					if (!isset($orgs[$org['org_id']])) {
						$orgs[$org['org_id']] = $branchModel->ICEGetOrg($uuid);
					}
				}
			}
		}


		$flattenOrgs = array();
		foreach ($orgs as $uuid => $org) {
			if (!$org || !isset($org['uuid'])) {
				continue;
			}

			$flattenOrgs[] = array(
				'id' => $org['uuid'],
				'uuid' => $org['uuid'],
				'pId' => $org['parentId'] ? $org['parentId'] : '-1',
				'name' => sprintf('[%s]%s', $org['orgType'] ? $org['orgType'] : '-', $org['name']),
				'open' => false,
			);
		}

		return $flattenOrgs;
	}

	public function ICEGetAllBranchData()
	{
		// TODO 后续需要注释掉
		//$this->orgId = ICEBranch::SUPPER_UUID;
		//$this->employeeAccount = 'mahongtao';

		$branchModel = ICEBranch::model()->findByPk($this->orgId);

		// 内部账号所在组织结构所有子节点
		$subs = ICEBranch::model()->findByPk($this->orgId)->ICEGetOrgSubs();
		$orgs = $branchModel->ICEGetAllOrgsTOCacheGetAndSet($this->orgId);

		// 合并 jurisdiction/account 权限分配的组织结构节点
		$jurOrgs = $this->ICEGetAccountJurisdiction();

		if ($jurOrgs && is_array($jurOrgs)) {
			foreach ($jurOrgs as $org) {
				$uuid = $org['org_id'];

				// 如果分配的权限已经包含在账号所属组织结构，则跳过
				if (in_array($uuid, $subs)) {
					continue;
				}

				if ($org['is_all'] == 1) {
					$orgs = array_merge(
						$orgs,
						$branchModel->ICEGetAllOrgsTOCacheGetAndSet($uuid)
					);
				} else {
					if (!isset($orgs[$org['org_id']])) {
						$orgs[$org['org_id']] = $branchModel->ICEGetOrg($uuid);
					}
				}
			}
		}


		$flattenOrgs = array();
		foreach ($orgs as $uuid => $org) {
			if (!$org || !isset($org['uuid'])) {
				continue;
			}

			$flattenOrgs[] = array(
				'id' => $org['uuid'],
				'uuid' => $org['uuid'],
				'pId' => $org['parentId'] ? $org['parentId'] : '-1',
				'name' => sprintf('[%s]%s', $org['orgType'] ? $org['orgType'] : '-', $org['name']),
				'open' => false,
			);
		}

		return $flattenOrgs;
	}


	public function ICEGetAccount($param = array(), $cache = true)
	{
		if (!$param) {
			return array();
		}

		$param['type'] = 'czy';

		$cacheKey = 'cache:cwy:ice:account:get:' . http_build_query($param);

		if ($cache == true) {
			$response = Yii::app()->rediscache->get($cacheKey);
		} else {
			Yii::app()->rediscache->delete($cacheKey);
		}

		if (!$response) {
			$response = ICEService::getInstance()->dispatch(
				'account',
				$param,
				array(),
				'get'
			);

			if ($response) {
				Yii::app()->rediscache->set(
					$cacheKey,
					$response,
					ICEService::GetCacheExpire()
				);
			}
		}

		return $response;
	}

	public function ICEGetAccountSearch($param = array(), $cache = true)
	{
		if (!$param) {
			return array();
		}

		$param['type'] = 'czy';

		$cacheKey = 'cache:cwy:ice:account:search:get:' . http_build_query($param);

		if ($cache == true) {
			$response = Yii::app()->rediscache->get($cacheKey);
		} else {
			Yii::app()->rediscache->delete($cacheKey);
		}

		if (!$response) {
			$response = ICEService::getInstance()->dispatch(
				'account/search',
				$param,
				array(),
				'get'
			);

			if ($response) {
				Yii::app()->rediscache->set(
					$cacheKey,
					$response,
					ICEService::GetCacheExpire()
				);
			}
		}

		return $response;
	}

	public function ICEPostAccountSearch($search = array(), $cache = true)
	{
		$result = array();
		$queryData = array(
			'type' => 'czy',
			'keyword' => isset($search['keyword']) && $search['keyword'] ? $search['keyword'] : '',
			'page' => isset($search['page']) && $search['page'] ? $search['page'] : '1',
			'pageSize' => isset($search['pageSize']) && $search['pageSize'] ? $search['pageSize'] : '10'
		);
		$cacheKey = 'cache:cwy:ice:account:search:post:' . http_build_query($queryData);

		if ($cache == true) {
			$result = Yii::app()->rediscache->get($cacheKey);
		} else {
			Yii::app()->rediscache->delete($cacheKey);
		}

		if (!$result) {
			$result = ICEService::getInstance()->dispatch(
				'account/search',
				array(),
				$queryData,
				'post'
			);

			$list = isset($result['list']) ? $result['list'] : $result;
			$totalNum = isset($result['totalNum']) ? $result['totalNum'] : count($list);

			$result = array(
				'list' => $list,
				'totalNum' => $totalNum
			);

			if ($result) {
				Yii::app()->rediscache->set(
					$cacheKey,
					$result,
					ICEService::GetCacheExpire()
				);
			}
		}

		return $result;
	}

	public function ICEGetAccountPage($search = array(), $cache = true)
	{
		$result = array();
		$queryData = array(
			//'uptime' => '',
			//'startDate' => '',
			//'endDate' => '',
			'type' => 'czy',
			'size' => isset($search['pageSize']) && $search['pageSize'] ? $search['pageSize'] : '10',
			'page' => isset($search['page']) && $search['page'] ? $search['page'] : '1',
		);
		$cacheKey = 'cache:cwy:ice:account:page:get:' . http_build_query($queryData);

		if ($cache == true) {
			$result = Yii::app()->rediscache->get($cacheKey);
		} else {
			Yii::app()->rediscache->delete($cacheKey);
		}

		if (!$result) {
			$result = ICEService::getInstance()->dispatch(
				'account/page',
				$queryData,
				array(),
				'get'
			);

			if ($result) {
				Yii::app()->rediscache->set(
					$cacheKey,
					$result,
					ICEService::GetCacheExpire()
				);
			}
		}

		return $result;
	}

	protected function ICEEmployeeFlattenToList($accounts = array(), $excludeEmployeeId = array())
	{
		if (!$accounts) {
			return array();
		}

		$employees = array();
		if ($excludeEmployeeId && !is_array($excludeEmployeeId)) {
			$excludeEmployeeId = array($excludeEmployeeId);
		}

		foreach ($accounts as $account) {
			if (!$account || !isset($account['czyId']) || !$account['czyId']) {
				continue;
			}
			$employeeId = $account['czyId'];
			if ($excludeEmployeeId && in_array($employeeId, $excludeEmployeeId)) {
				continue;
			}
			$employees[] = array(
				'id' => $employeeId,
				'name' => $account['realname']
					. (isset($account['jobName']) && $account['jobName']
						? '[' . $account['jobName'] . ']' : ''),
			);
		}

		return $employees;
	}

	public function ICEAjaxGetEmployeeName($branchID = '')
	{
		if (!$branchID) {
			return array();
		}

		$org = ICEBranch::model()->ICEGetOrg($branchID);
		if ($org) {
			return array();
		}


		return $this->ICEEmployeeFlattenToList(
			$this->ICEPostAccountSearch(array(
				'page' => 1,
				'pageSize' => 100,
				'keyword' => isset($org['name']) && $org['name'] ? $org['name'] : '',
			))
		);
	}

	public function ICEAjaxGetExecEmployeeName($branchID = '', $excludeEmployeeId = 0)
	{
		if (!$branchID) {
			return array();
		}

		$org = ICEBranch::model()->ICEGetOrg($branchID);
		if (!$org) {
			return array();
		}

		return $this->ICEEmployeeFlattenToList(
			$this->ICEPostAccountSearch(array(
				'page' => 1,
				'pageSize' => 100,
				'keyword' => isset($org['name']) && $org['name'] ? $org['name'] : '',
			)),
			$excludeEmployeeId
		);
	}

	public function ICEAjaxGetSuperEmployeeName($branchID = '', $excludeEmployeeId = array())
	{
		if (!$branchID) {
			return array();
		}

		$org = ICEBranch::model()->ICEGetOrg($branchID);
		if (!$org) {
			return array();
		}

		return $this->ICEEmployeeFlattenToList(
			$this->ICEPostAccountSearch(array(
				'page' => 1,
				'pageSize' => 100,
				'keyword' => isset($org['name']) && $org['name'] ? $org['name'] : '',
			)),
			$excludeEmployeeId
		);
	}

	public function ICEAjaxGetConfirmEmployeeName($branchID = '', $excludeEmployeeId = 0)
	{
		if (!$branchID) {
			return array();
		}

		$org = ICEBranch::model()->ICEGetOrg($branchID);
		if (!$org) {
			return array();
		}

		return $this->ICEEmployeeFlattenToList(
			$this->ICEPostAccountSearch(array(
				'page' => 1,
				'pageSize' => 100,
				'keyword' => isset($org['name']) && $org['name'] ? $org['name'] : '',
			)),
			$excludeEmployeeId
		);
	}

	public function ICEAjaxGetAllEmployeeName($keyword = '')
	{
		if (!$keyword) {
			return array();
		}

		return $this->ICEEmployeeFlattenToList(
			$this->ICEPostAccountSearch(array(
				'page' => 1,
				'pageSize' => 100,
				'keyword' => $keyword,
			))
		);
	}

	public function getDisableText()
	{
		return $this->disable == self::DISABLE_YES
			? self::DISABLE_YES_TEXT
			: self::DISABLE_NO_TEXT;;
	}

	public function ICEGetAccountSearchByMobile($mobile = '')
	{
		$employees = array();
		try {
			$employees = $this->ICEGetAccountSearch(array('keyword' => $mobile));
		} catch (Exception $e) {
		}
		if (!$employees || !isset($employees[0])) {
			return array();
		}

		return $this->ICEEmployeeToColourlife($employees[0]);
	}

	public function ICEFindByMobile($mobile = '')
	{
		$handler = null;
		$employee = $this->ICEGetAccountSearchByMobile($mobile);
		if ($employee) {
			$handler = new self();

			foreach ($employee as $key => $value) {
				$handler->setAttribute($key, $value);
			}

			return $handler;
		}

		return $handler;
	}

	public function ICEFindByUserName($username = '')
	{
		$handler = null;
		$employee = array();
		try {
			$employee = $this->ICEGetAccount(array('username' => $username));
		} catch (Exception $e) {

		}

		if ($employee) {
			$handler = new self();
			$employee = $this->ICEEmployeeToColourlife($employee);

			foreach ($employee as $key => $value) {
				$handler->setAttribute($key, $value);
			}

			return $handler;
		}

		return $handler;
	}

	public function getBalance()
	{
		return Employee::model()->findByPk($this->id)->getBalance();
	}

	public function ICEGetEmployeeAccountJurisdiction()
	{
		if (empty($this->orgId)) {
			throw new CHttpException('400', "用户未关联组织机构,请联系管理员！");
		}

		// 内部账号所在组织结构所有子节点
		$orgs = array();
		$pids = array($this->orgId);

		// 合并 jurisdiction/account 权限分配的组织结构节点
		$jurOrgs = $this->ICEGetAccountJurisdiction();
		if ($jurOrgs && is_array($jurOrgs)) {
			foreach ($jurOrgs as $org) {
				$uuid = $org['org_id'];

				// 如果分配的权限已经包含在账号所属组织结构，则跳过
				if (in_array($uuid, $orgs)
					|| in_array($uuid, $pids)
				) {
					continue;
				}

				if ($org['is_all'] == 1) {
					$pids[] = $org['org_id'];
				} else {
					$orgs[] = $org['org_id'];
				}
			}
		}

		if ($pids) {
			$subs = ICEBranch::model()->ICEGetOrgSubs(implode(',', $pids));

			if ($subs) {
				$orgs = array_merge($orgs, $subs);
			}
		}

		return $orgs;
	}
}