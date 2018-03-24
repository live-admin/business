<?php

/**
 * This is the model class for table "Employee_middle".
 *
 * The followings are the available columns in table 'Employee_middle':
 * @property integer $id
 * @property integer $uid
 * @property string $username
 * @property string $realname
 * @property string $password
 * @property integer $job_id
 * @property string $job_name
 * @property integer $familyid
 * @property string $familyname
 * @property string $mobile
 * @property string $email
 * @property integer $disable
 * @property datetime $createtime
 * @property datetime $uptime
 * @property integer $storage
 * @property integer $colourlife_id
 * @property text $corjob
 */
class EmployeeMiddle extends CActiveRecord
{

	/**
	 * @var string 模型名
	 */
	public $modelName = '员工中间转存';

	public static $employeeByOaCount = 0;
	public static $employeeByIceCount = 0;

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
		return 'employee_middle';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('uid,username,realname,password', 'required', 'on' => 'create,update'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => '员工ID',
			'username' => '账号',
			'realname' => '真实姓名',
			'password' => '密码',
			'job_id' => '位职编号',
			'job_name' => '位职名称',
			'familyid' => '架构编号',
			'familyname' => '架构名称',
			'mobile' => '手机号码',
			'email' => 'Email',
			'disable' => '0正常,1禁止,2锁定',
			'createtime' => '注册时间',
			'uptime' => '新更时间',
			'storage' => '录入',
			'colourlife_id' => '对应员工id',
			'corjob' => 'json字符串'
		);
	}

	public static function checkIsExist($uid)
	{
		return EmployeeMiddle::model()->find('uid=:uid', array(':uid' => $uid));
	}

	/**
	 * 获取OA员工表的全部数据批量插入中间表
	 */
	public function recInsert($pagesize, $pageindex)
	{
		Yii::import('common.api.ColorCloudApi');
		$colure = ColorCloudApi::getInstance();
		$res = $colure->callGetUserList("", $pagesize, $pageindex);
		if (!$res || (!empty($res['error']))) {
			$arrlog = array();
			$arrlog['level'] = "error";
			$arrlog['category'] = "get.user.list";
			$arrlog['logtime'] = time();
			$arrlog['message'] = "OaApi(get.user.list)访问异常pagesize=$pagesize,pageindex=$pageindex";
			$log = new MiddleLog("create");
			$log->attributes = $arrlog;
			$log->save();
			echo iconv("utf-8", "gbk", "OaApi(get.user.list)访问异常pagesize=$pagesize,pageindex=$pageindex");
			sleep(5);
			self::$employeeByOaCount++;
			if (self::$employeeByOaCount > 5) {
				die(iconv("utf-8", "gbk", "OaApi(get.user.list)访问异常pagesize=$pagesize,pageindex=$pageindex"));
			}
			$this->recInsert($pagesize, $pageindex);
		}
		// if(empty($res['data'])){
		//     //die(iconv("utf-8","gbk","插入完毕!"));
		//     die(iconv("utf-8","gbk","insert finished!"));
		// }
		if ($res['data']) {
			$this->insertEmployee($res);
		}
	}

	public function insertEmployee($res, $instore = 0)
	{
		foreach ($res['data'] as $v) {
			$model = EmployeeMiddle::checkIsExist($v['uid']);
			if (!$model) {
				$model = new EmployeeMiddle();
			}
			$model['uid'] = $v['uid'];
			$model['username'] = $v['username'];
			if (!empty($v['realname'])) {
				$model['realname'] = $v['realname'];
			}
			$model['password'] = $v['password'];
			if (!empty($v['job_id'])) {
				$model['job_id'] = $v['job_id'];
			}
			if (!empty($v['job_name'])) {
				$model['job_name'] = $v['job_name'];
			}
			if (!empty($v['familyid'])) {
				$model['familyid'] = $v['familyid'];
			}
			if (!empty($v['familyname'])) {
				$model['familyname'] = $v['familyname'];
			}
			if (!empty($v['mobile'])) {
				$model['mobile'] = $v['mobile'];
			}
			if (!empty($v['email'])) {
				$model['email'] = $v['email'];
			}
			$model['disable'] = $v['disable'] ? $v['disable'] : 0;
			$model['createtime'] = $v['createtime'] ? $v['createtime'] : date("Y-m-d");
			$model['uptime'] = $v['uptime'] ? $v['uptime'] : date("Y-m-d");
			$model['storage'] = 0;
			$model['colourlife_id'] = 0;
			$model['corjob'] = $v['corjob'];
			if (!$model->save()) {
				$arrlog = array();
				$arrlog['level'] = "error";
				$arrlog['category'] = "get.user.list";
				$arrlog['logtime'] = time();
				$arrlog['message'] = "uid为" . $v['uid'] . "账号为:" . $v['username'] . "的记录添加失败";
				$log = new MiddleLog("create");
				$log->attributes = $arrlog;
				$log->save();
			} else {
				if ($instore == 1) {
					$arrlog = array();
					$arrlog['level'] = "info";
					$arrlog['category'] = "get.user.list";
					$arrlog['logtime'] = time();
					$arrlog['message'] = "uid为" . $v['uid'] . "账号为:" . $v['username'] . "的记录添加成功";
					$log = new MiddleLog("create");
					$log->attributes = $arrlog;
					$log->save();
					// echo iconv("utf-8","gbk",$model->id." employee middle finished!"."\r\n");
				} else {
					$str = "uid为：" . $v['uid'] . "姓名为:" . $v['realname'] . "的记录添加成功\r\n";
					echo iconv("utf-8", "gbk", $str);
				}
			}
		}
	}

	/**
	 * 获取OA员工表每天更新的数据批量插入中间表和正式表(停用)
	 */
	public function recInsertByUpdate($uptime, $pagesize, $pageindex, $username)
	{
		Yii::import('common.api.ColorCloudApi');
		$colure = ColorCloudApi::getInstance();
		$res = $colure->callGetUserUpList($uptime, $pagesize, $pageindex, $username);

		if (!$res || (!empty($res['error']))) {
			$arrlog = array();
			$arrlog['level'] = "error";
			$arrlog['category'] = "get.user.uplist";
			$arrlog['logtime'] = time();
			$arrlog['message'] = "OaApi(get.user.uplist)访问异常pagesize=$pagesize,pageindex=$pageindex,uptime=$uptime";
			$log = new MiddleLog("create");
			$log->attributes = $arrlog;
			$log->save();
			echo iconv("utf-8", "gbk", "OaApi(get.user.uplist)访问异常pagesize=$pagesize,pageindex=$pageindex,uptime=$uptime");
			sleep(5);
			self::$employeeByOaCount++;
			if (self::$employeeByOaCount > 5) {
				die(iconv("utf-8", "gbk", "OaApi(get.user.uplist)访问异常pagesize=$pagesize,pageindex=$pageindex,uptime=$uptime"));
			}
			$this->recInsertByUpdate($uptime, $pagesize, $pageindex, $username);
		}
		if (empty($res['data'])) {
			//die(iconv("utf-8","gbk","插入完毕!"));
			die(iconv("utf-8", "gbk", "employee syn finished!"));
		} else {
			$this->insertEmployee($res, 1);  //往中间表插
			$this->createEmployee($res, 2);  //往正式员工表插
		}
	}

	// 登录时候使用（停用）
	/**
	 * 获取OA员工表每天更新的数据批量插入中间表和正式表
	 */
	public function recInsertByUpdateLogin($uptime, $pagesize, $pageindex, $username)
	{
		Yii::import('common.api.ColorCloudApi');
		$colure = ColorCloudApi::getInstance();
		$res = $colure->callGetUserUpList($uptime, $pagesize, $pageindex, $username);
		// var_dump($res);die;
		if (!$res || (!empty($res['error']))) {
			$arrlog = array();
			$arrlog['level'] = "error";
			$arrlog['category'] = "get.user.uplist";
			$arrlog['logtime'] = time();
			$arrlog['message'] = "OaApi(get.user.uplist)访问异常pagesize=$pagesize,pageindex=$pageindex,uptime=$uptime";
			$log = new MiddleLog("create");
			$log->attributes = $arrlog;
			$log->save();
			echo iconv("utf-8", "gbk", "OaApi(get.user.uplist)访问异常pagesize=$pagesize,pageindex=$pageindex,uptime=$uptime");
			sleep(5);
			self::$employeeByOaCount++;
			if (self::$employeeByOaCount > 5) {
				die(iconv("utf-8", "gbk", "OaApi(get.user.uplist)访问异常pagesize=$pagesize,pageindex=$pageindex,uptime=$uptime"));
			}
			$this->recInsertByUpdate($uptime, $pagesize, $pageindex, $username);
		}
		if (empty($res['content'])) {
			return true;
		} else {
			$this->insertEmployee($res, 1);  //往中间表插
			$this->createEmployee($res, 2);  //往正式员工表插
		}
	}

	/**
	 * 每月获取需同步的红包发放列表
	 */
	public function redPacketInsertByUpdate($year, $month, $pagesize, $pageindex)
	{
		Yii::import('common.api.ColorCloudApi');
		$colure = ColorCloudApi::getInstance();
		$res = $colure->callGetHongBaoUpList($year, $month, $pagesize, $pageindex);
		if (!$res || (!empty($res['error']))) {
			$arrlog = array();
			$arrlog['level'] = "error";
			$arrlog['category'] = "get.hongbao.uplist";
			$arrlog['logtime'] = time();
			$arrlog['message'] = "OaApi(get.hongbao.uplist)访问异常pagesize=$pagesize,pageindex=$pageindex,year=$year,month=$month";
			$log = new MiddleLog("create");
			$log->attributes = $arrlog;
			$log->save();
			echo iconv("utf-8", "gbk", "OaApi(get.hongbao.uplist)访问异常pagesize=$pagesize,pageindex=$pageindex,year=$year,month=$month");
			sleep(5);
			self::$employeeByOaCount++;
			if (self::$employeeByOaCount > 5) {
				die(iconv("utf-8", "gbk", "OaApi(get.hongbao.uplist)访问异常pagesize=$pagesize,pageindex=$pageindex,year=$year,month=$month"));
			}
			$this->redPacketInsertByUpdate($year, $month, $pagesize, $pageindex);
		}
		if (empty($res['data'])) {
			die(iconv("utf-8", "gbk", "hongbao uplist syn finished!"));
		} else {
			$this->createData($res, 2);  //往数据库表插入数据
		}


	}

	/**
	 * 获取每月获取需同步的红包发放列表批量插入数据库表
	 **/
	public function createData($res, $isprint = 1)
	{
		foreach ($res['data'] as $v) {
			$this->CreateOneData($v, $isprint);
		}
	}

	//往正式员工表更新一条记录
	public function CreateOneData($v, $isprint)
	{
		$model = HongBaoUplist::checkIsExist($v['oid']);
		if (!$model) {
			$model = new HongBaoUplist();
		} else {
			$model->setScenario("update");
		}
		$model['oid'] = $v['oid'];
		$model['oauser'] = $v['oauser'];
		$employee = Employee::model()->find('username=:username', array(':username' => $v['oauser']));
		if ($employee) {
			$model['employee_id'] = $employee->id;
			$model['year'] = $v['year'];
			$model['month'] = $v['month'];
			$model['hbfee'] = $v['hbfee'];
			if (!empty($v['realname'])) {
				$model['realname'] = $v['realname'];
			}
			if (!$model->save()) {
				$arrlog = array();
				$arrlog['level'] = "error";
				$arrlog['category'] = "OA红包同步";
				$arrlog['logtime'] = time();
				$arrlog['message'] = "oid为" . $v['oid'] . "账号为:" . $v['oauser'] . "的红包同步失败";
				$log = new MiddleLog("create");
				$log->attributes = $arrlog;
				$log->save();
			} else {
				if ($isprint == 1) {
					echo iconv("utf-8", "gbk", $model->id . "hongbao uplist finished!\r\n");
				} else if ($isprint == 2) {
					$arrlog = array();
					$arrlog['level'] = "info";
					$arrlog['category'] = "OA红包同步";
					$arrlog['logtime'] = time();
					$arrlog['message'] = "oid为" . $v['oid'] . "账号为:" . $v['oauser'] . "的红包同步成功";
					$log = new MiddleLog("create");
					$log->attributes = $arrlog;
					$log->save();
				}
			}
		}

	}

	public function searchByPage($pageSzie, $pageIndex)
	{
		//$count = EmployeeMiddle::model()->count();
		//$pageMax = ceil($count/$pageSzie);
		/*if($pageIndex > $pageMax){
			die(iconv("utf-8","gbk","更新完毕!"));
		}*/
		$index = $pageSzie * ($pageIndex - 1);
		$sql = "select * from employee_middle limit $index,$pageSzie";
		$result = Yii::app()->db->createcommand($sql);
		return $result->queryAll();
	}

	/**
	 * 员工中间表的数据往正式员工表插
	 */
	public function recCreate($res, $isprint = 1)
	{
		if ($res) {
			foreach ($res as $v) {
				$this->CreateOne($v, $isprint);
			}
		}
	}

	/**
	 * 获取OA员工表每天更新的数据批量插入正式员工表
	 **/
	public function createEmployee($res, $isprint = 1)
	{
		foreach ($res['data'] as $v) {
			$this->CreateOne($v, $isprint);
		}
	}

	//往正式员工表更新一条记录
	public function CreateOne($v, $isprint)
	{
		$branchMiddle = BranchMiddle::checkIsExist($v['familyid']);
		if ($branchMiddle && $branchMiddle['relation_id'] > 0) {
			$model = EmployeeOa::checkIsExist($v['username']);
			// var_dump($model);die;
			if (!$model) {
				$model = new EmployeeOa();
				// echo 123;
			} else {
				$model->setScenario("update");
				// echo 222;
			}
			$model['username'] = $v['username'];
			$model['oa_username'] = $v['username'];
			if (!$model) {
				$salt = F::random(8);
				/*如果salt为空重新生成salt*/
				if (empty($salt)) {
					$salt = F::getRandChar(8);
				}
			} else {
				$salt = $model['salt'];
				//$salt = F::random(8);
			}

			// var_dump($salt);die;
			$password = $v['password'];
			$model['salt'] = $salt;
			$model['password'] = md5($password . $salt);
			if (!empty($v['mobile'])) {
				$model['mobile'] = $v['mobile'];
			}
			if (!empty($v['realname'])) {
				$model['name'] = $v['realname'];
			}
			if (!empty($v['email'])) {
				$model['email'] = $v['email'];
			}
			if (!empty($v['job_name'])) {
				$model['job_name'] = $v['job_name'];
			}
			$model['state'] = $v['disable'] ? 1 : 0;
			$model['create_time'] = $model['create_time'] ? $model['create_time'] : time();
			$model['last_time'] = $model['last_time'] ? $model['last_time'] : time();
			if (!$model->save()) {
				$arrlog = array();
				$arrlog['level'] = "error";
				$arrlog['category'] = "OA账号同步";
				$arrlog['logtime'] = time();
				$arrlog['message'] = "uid为" . $v['uid'] . "账号为:" . $v['username'] . "的同步失败";
				$log = new MiddleLog("create");
				$log->attributes = $arrlog;
				$log->save();
			} else {
				$item = EmployeeMiddle::checkIsExist($v['uid']);
				if ($item) {
					$item['colourlife_id'] = $model['id'];
					$item['storage'] = 1;
					$item->save();

					//组织架构可能1对多
					$str_json = "[" . $v['corjob'] . "]";
					$relation_arr = json_decode($str_json, true);
					// EmployeeBranchRelation::model()->deleteAllByEmployeeId($model['id']);
					for ($i = 0; $i < count($relation_arr); $i++) {
						$branchMiddle = BranchMiddle::checkIsExist($relation_arr[$i]['cid']);
						if ($branchMiddle) {
							$employeeBranchRelation = new EmployeeBranchRelation("create");
							$employeeBranchRelation['employee_id'] = $model['id'];
							$employeeBranchRelation['branch_id'] = $branchMiddle['relation_id'];
							$employeeBranchRelation->save();
						}
					}
					$relationInfo = EmployeeBranchRelation::model()->getAllByEmployeeId($model['id']);
					if (!$relationInfo) {
						$employeeBranchRelation = new EmployeeBranchRelation("create");
						$employeeBranchRelation['employee_id'] = $model['id'];
						$employeeBranchRelation['branch_id'] = 599;    //599体验区
						$employeeBranchRelation->save();
					}
					if ($isprint == 1) {
						//$str = "uid为：".$v['uid']."姓名为:".$v['realname']."的记录同步成功\r\n";
						//echo iconv("utf-8","gbk",$str);
						echo iconv("utf-8", "gbk", $model->id . "employee finished!\r\n");
					} else if ($isprint == 2) {
						$arrlog = array();
						$arrlog['level'] = "info";
						$arrlog['category'] = "OA账号同步";
						$arrlog['logtime'] = time();
						$arrlog['message'] = "uid为" . $v['uid'] . "账号为:" . $v['username'] . "的同步成功";
						$log = new MiddleLog("create");
						$log->attributes = $arrlog;
						$log->save();
						// echo iconv("utf-8","gbk",$model->id."employee into Employee_oa finished!\r\n");
					}
				}
			}
		}
	}

	//根据组织架构Id,返回该组织架构下所有的员工（员工中间表）
	public function getAllByBranchidBranchname($familyid, $familyname)
	{
		return EmployeeMiddle::model()->findAll('familyid=:familyid and familyname=:familyname', array(':familyid' => $familyid, ':familyname' => $familyname));
	}

	public function changeEmployeeBranchRelation($branchId)
	{
		$res = EmployeeBranchRelation::getAllByBranchId($branchId);
		foreach ($res as $v) {
			$employeeBranchRelation = EmployeeBranchRelation::checkIsExist($v['employee_id'], $v['branch_id']);
			if ($employeeBranchRelation) {
				$employeeBranchRelation->setScenario("update");
				$employeeBranchRelation['branch_id'] = 599;            //599体验区
				$employeeBranchRelation->save();
			}
		}
	}

	/************************************2016-8-26从ICE平台同步OA账号信息******************************************/

	/**
	 * 获取ICE员工表每天更新的数据批量插入中间表和正式表2016-08-26
	 */
	public function InsertByICE($page, $size, $uptime, $startDate, $endDate)
	{
		Yii::import('common.api.IceApi');
		$ice = IceApi::getInstance();
		$res = $ice->getOAList($page, $size, $uptime, $startDate, $endDate);
		if (!$res) {
			$arr_log = array();
			$arr_log['level'] = "error";
			$arr_log['category'] = "/v1/account/page";
			$arr_log['logtime'] = time();
			$arr_log['message'] = "ICEApi(/v1/account/page)访问异常pagesize=$size,pageindex=$page,uptime=$uptime";
			$log = new MiddleLog("create");
			$log->attributes = $arr_log;
			$log->save();
			echo iconv("utf-8", "gbk", "ICEApi(/v1/account/page)访问异常pagesize=$size,pageindex=$page,uptime=$uptime");
			sleep(5);
			self::$employeeByIceCount++;
			if (self::$employeeByIceCount > 5) {
				die(iconv("utf-8", "gbk", "ICEApi(/v1/account/page)访问异常pagesize=$size,pageindex=$page,uptime=$uptime"));
			}
			$this->InsertByICE($page, $size, $uptime, $startDate, $endDate);
		}
		if (empty($res['list'])) {
			die(iconv("utf-8", "gbk", "employee syn finished!"));
		} else {
			$this->insertEmployeeMiddle($res['list'], 1);  //往中间表插
			$this->insertEmployeeByIce($res['list'], 2);  //往正式员工表插
		}
	}

	/**
	 * 往中间表插入OA账号数据2016-08-26
	 */
	public function insertEmployeeMiddle($res, $instore = 0)
	{
		foreach ($res as $v) {
			$model = $this->checkIsExistByOa($v['employeeAccount']);
			if (!$model) {
				$model = new EmployeeMiddle();
			}
			$model['uid'] = empty($v['oaId']) ? 0 : $v['oaId'];
			$model['username'] = $v['employeeAccount'];
			$model['realname'] = $v['realname'];
			$model['password'] = empty($v['userPassword']) ? 0 : $v['userPassword'];
			$model['job_id'] = $v['jobId'];
			$model['job_name'] = $v['jobName'];
			$model['familyid'] = $v['familyId'];
			$model['familyname'] = $v['familyName'];
			$model['mobile'] = $v['mobile'];
			$model['email'] = $v['mail'];
			$model['disable'] = $v['disable'];
			$model['createtime'] = $v['createtime'] ? $v['createtime'] : date("Y-m-d");
			$model['uptime'] = $v['uptime'] ? $v['uptime'] : date("Y-m-d");
			$model['storage'] = 0;
			$model['colourlife_id'] = 0;
			//dump($model['_scenario']);
			if (!$model->save()) {
				$arrlog = array();
				$arrlog['level'] = "error";
				$arrlog['category'] = "/v1/account/page";
				$arrlog['logtime'] = time();
				$arrlog['message'] = "uid为" . $v['oaId'] . "账号为:" . $v['employeeAccount'] . "的记录添加失败";
				$log = new MiddleLog("create");
				$log->attributes = $arrlog;
				$log->save();
			} else {
				if ($instore == 1) {
					$arrlog = array();
					$arrlog['level'] = "info";
					$arrlog['category'] = "/v1/account/page";
					$arrlog['logtime'] = time();
					$arrlog['message'] = "uid为" . $v['oaId'] . "账号为:" . $v['employeeAccount'] . "的记录添加成功1";
					$log = new MiddleLog("create");
					$log->attributes = $arrlog;
					$log->save();
				} else {
					$str = "uid为" . $v['oaId'] . "账号为:" . $v['employeeAccount'] . "的记录添加成功\r\n";
					echo iconv("utf-8", "gbk", $str);
				}
			}
		}
	}

	/**
	 * 往正式表插入OA账号数据2016-08-26
	 */
	public function insertEmployeeByIce($res, $isprint = 1)
	{
		foreach ($res as $v) {
			$model = EmployeeOa::checkIsExist($v['employeeAccount']);
			$czyId = isset($v['czyId']) && $v['czyId'] ? $v['czyId'] : 0;
			/*if ($czyId) {
				$model['id'] = $czyId;
			}*/
			if (!$model) {
				$model = new EmployeeOa();
				$salt = F::random(8);
				if (empty($salt)) {
					$salt = F::getRandChar(8);
				}
				$model['salt'] = $salt;
				$model['password'] = $v['userPassword'];
				$model['username'] = $v['employeeAccount'];
				$model['oa_username'] = $v['employeeAccount'];
				$model['password'] = md5($v['userPassword'] . $salt);
			} else {
				$model->setScenario("update");
			}
			$model['mobile'] = $v['mobile'];
			$model['name'] = $v['realname'];
			$model['email'] = $v['mail'];
			$model['job_name'] = $v['jobName'];
			$model['state'] = $v['disable'];
			$model['create_time'] = time();
			$model['last_time'] = strtotime($v['uptime']);
			if (!$model->save()) {
				$arrlog = array();
				$arrlog['level'] = "error";
				$arrlog['category'] = "OA账号同步";
				$arrlog['logtime'] = time();
				$arrlog['message'] = "uid为 " . $v['oaId'] . " 账号为: " . $v['employeeAccount'] . " 的同步失败" . json_encode($model->getErrors());
				$log = new MiddleLog("create");
				$log->attributes = $arrlog;
				$log->save();
			} else {
				if ($model['id'] != $czyId){
					$model->updateCzyId();
				}


				$item = EmployeeMiddle::checkIsExistByOa($v['employeeAccount']);
				if ($item) {
					$item['colourlife_id'] = $model['id'];
					$item['storage'] = 1;
					$item->save();

					if ($isprint == 1) {
						echo iconv("utf-8", "gbk", $model->id . "employee finished!\r\n");
					} else if ($isprint == 2) {
						$arrlog = array();
						$arrlog['level'] = "info";
						$arrlog['category'] = "OA账号同步";
						$arrlog['logtime'] = time();
						$arrlog['message'] = "uid为" . $v['oaId'] . "账号为:" . $v['employeeAccount'] . "的同步成功";
						$log = new MiddleLog("create");
						$log->attributes = $arrlog;
						$log->save();
					}
				}
			}


		}
	}

	/**
	 * 登录时根据oa账号从ice获取账号信息插入中间表和正式表2016-08-26
	 */
	public function InsertOAByICE($oa_username)
	{
		try {
			$employee = ICEEmployee::model()->ICEGetAccount(array('username' => $oa_username));
			$this->insertEmployeeMiddle(array($employee), 1);  //往中间表插
			$this->insertEmployeeByIce(array($employee), 2);  //往正式员工表插
		} catch (Exception $e) {
			$arr_log = array();
			$arr_log['level'] = 'error';
			$arr_log['category'] = '/v1/account/search';
			$arr_log['logtime'] = time();
			$arr_log['message'] = sprintf(
				'ICEEmployee->ICEGetAccount访问异常 oa_username=%s, Exeception: %s[%s]',
				$oa_username, $e->getMessage(), $e->getCode()
			);
			$log = new MiddleLog('create');
			$log->attributes = $arr_log;
			$log->save();
		}

		return true;

		Yii::import('common.api.IceApi');
		$ice = IceApi::getInstance();
		$res = $ice->getOA($oa_username);;
		if (!$res) {
			$arr_log = array();
			$arr_log['level'] = "error";
			$arr_log['category'] = "/v1/account/search";
			$arr_log['logtime'] = time();
			$arr_log['message'] = "ICEApi(/v1/account/search)访问异常oa_username=$oa_username";
			$log = new MiddleLog("create");
			$log->attributes = $arr_log;
			$log->save();
			echo iconv("utf-8", "gbk", "ICEApi(/v1/account/search)访问异常oa_username=$oa_username");
			sleep(5);
			self::$employeeByIceCount++;
			if (self::$employeeByIceCount > 5) {
				die(iconv("utf-8", "gbk", "ICEApi(/v1/account/search)访问异常oa_username=$oa_username"));
			}
			$this->InsertOAByICE($oa_username);
		}

		if (empty($res)) {
			return true;
		} else {
			$this->insertEmployeeMiddle(array($res), 1);  //往中间表插
			$this->insertEmployeeByIce(array($res), 2);  //往正式员工表插
		}
	}

	public function checkIsExistByOa($username)
	{
		return EmployeeMiddle::model()->find('username=:username', array(':username' => $username));
	}


}
        