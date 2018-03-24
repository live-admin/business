<?php

class PublicRepairs extends ComplainRepairs
{
	public $modelName = '公共报修';
	public $execName; //执行人名称
	public $superName; //执行人名称
	public $confirmName; //执行人名称
	public $execAttr = array(); //执行人列表
	public $superAttr = array(); //监督人列表
	public $images = array(); //图片路径列表
	public $confirm; //确认人
	public $staffId = array();
	private $_old_execute;
	private $_old_confirm;
	public $complainRepairsType;

	public $communityIds;
	public $region;
	public $community_id;

	public $province_id;
	public $city_id;
	public $district_id;

	public function rules()
	{
		$array = array(
			array('execute,superAttr,confirm,images,accept_content,work_no', 'safe'),
			array('accept_content,suggestion,agree', 'required', 'on' => 'dispose,supervision,handle'),
			array('category_id,customer_name,customer_tel, community_id,content', 'required', 'on' => '400create'),
			array('community_id', 'checkCommunity', 'on' => '400create'),
			array('work_no', 'required', 'on' => '400update'),
			array('execute', 'checkExec', 'on' => '400update'),
			array('confirm', 'checkConfirm', 'on' => '400update'),
			array('execute,accept_content', 'safe', 'on' => 'redo,refuse'),
			//			ICE 搜索数据
			array('province_id,city_id,district_id', 'safe'),
		);
		return CMap::mergeArray(parent::rules(), $array);
	}

	public function attributeLabels()
	{
		$array = array(
			'id' => '报修工单',
			'execAttr' => '执行人',
			'superAttr' => '监督人',
			'images' => '图片',
			'confirm' => '确认人',
			'execName' => '执行人',
			'superName' => '监督人',
			'confirmName' => '确认人',
			'content' => '报修内容',
			'customerName' => '报修人',
			'customerTel' => '联系电话',
			'create_time' => '报修时间',
			'communityInBranch' => '报修小区所属部门',
			'communityDetail' => '业主报修小区',
			'apply_content' => '申请关闭原因',
		);
		return CMap::mergeArray(parent::attributeLabels(), $array);
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @param $complainAttr array 至少包括以下内容：category_id、user_id、community_id、content
	 * @return boolean
	 * 公共报修新建
	 */
	public function createPublicRepairsByCustomer()
	{
		if (empty($this) || empty($this->community_id)) { //如果未传参数，或小区为空，直接报错
			throw new CHttpException(400, "创建公共报修失败!");
		}
		if (empty($this->user_id)) { //业主ID
			throw new CHttpException(400, "创建公共报修失败!业主ID不能为空！");
		}
		$customer = Customer::model()->findByPk($this->user_id);
		if (empty($customer)) {
			throw new CHttpException(400, "创建公共报修失败!业主不能为空！");
		}
		$this->customer_name = empty($customer->name) ? $customer->username : $customer->name;
		$this->customer_tel = $customer->mobile;
		$this->type = Item::COMPLAIN_REPAIRS_TYPE_PUBLIC; //投诉类型为公共报修
		$this->model = 0; //所处模块为业主
		$this->state = Item::COMPLAIN_REPAIRS_PUBLIC_NONE; //初始状态

		$this->accept_employee_id = 0;
		$this->accept_time = 0;
		$this->accept_content = "";
		//调用公共方法
		//return $this->createPublicRepairs();
		if ($this->createPublicRepairs()) {

			return true;
		}
		return false;
	}


	/**
	 * @param $complainAttr array 至少包括以下内容：category_id、community_id、content
	 * @return boolean
	 * 400新建业主投诉
	 */
	public function createPublicRepairsByService()
	{
		if (empty($this) || empty($this->community_id) || empty($this->customer_tel)) { //如果未传参数，或小区为空，直接报错
			throw new CHttpException(400, "400创建公共报修失败!");
		}

		//如果查到手机号码一样的就插入用户ID
		$user = Customer::model()->find('mobile=:mobile', array(':mobile' => $this->customer_tel));
		$this->user_id = !empty($user) ? $user->id : 0; //系统创建

		$this->type = Item::COMPLAIN_REPAIRS_TYPE_PUBLIC; //投诉类型为业主投诉
		$this->model = 1; //所处模块为400
		$this->state = Item::COMPLAIN_REPAIRS_PUBLIC_NONE; //初始状态
		//400创单，最后处理信息应该为400的信息
		$this->accept_employee_id = Yii::app()->user->id;
		$this->accept_time = time();
		$this->accept_content = "400替业主创单";
		//调用公共方法
		if ($this->createPublicRepairs()) { //保存
			//分配小区主任
			$people = $this->getEmployeeByCommunity($this->community_id, Item::COMPLAIN_REPAIRS_TYPE_PUBLIC);
			if (empty($people)) {
				if (!$this->updateLogWithoutLeader())
					return false;
				//throw new CHttpException(400, "未获得小区主人，写日志失败！");
			} else if (!empty($people)) {
				$count = count($people);
				if ($count == "1") {
					$handling_id = current($people);
					if (!$this->updateOneHandling($handling_id))
						return false;
					// throw new CHttpException(400, "获得一个小区主任，设置执行人和确认人失败！");
				} else if ($count > 1) {
					if (!$this->updateMoreHandling($people))
						return false;
					// throw new CHttpException(400, "获得多个小区主任，设置执行人出错！");
				}
			}
			//添加第一次发送监督人
			$monitorPeople = PublicRepairs::model()->getMonitorEmployeeByCommunity($this->community_id, Item::COMPLAIN_REPAIRS_TYPE_PUBLIC);
			if (!empty($monitorPeople)) {
				if (!$this->updateMoreSuperviseHandling($monitorPeople)) {
					throw new CHttpException(400, "获得多个第一次自动监督职位的员工，设置监督人出错！");
				}
			}
			return true;
		} else {
			return false;
			// throw new CHttpException(400, "创建报修失败");
		}
	}

	private function createPublicRepairs()
	{
		if ($this->save()) {
			if (!empty($this->images)) {
				//保存上传的图片路径
				$crieria = new CDbCriteria();
				$crieria->addInCondition('id', $this->images);
				$attr = array(
					'model' => get_class($this),
					'object_id' => $this->id,
				);
				if (!Picture::model()->updateAll($attr, $crieria)) {
					Yii::log("公共报修{$this->id}更新图片失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.create');
				}
			}
			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 0, 0, "【 系统接受到业主" . $this->customer_name . "报修 】 ");

			//前台日志（）
			$logs = ComplainRepairsCustomerLog::createCustomerLog($this->id, "【 系统接受到业主" . $this->customer_name . "报修 】 ",
				0, Item::COMPLAIN_REPAIRS_LOG_AWAITING_HANDL, $this->type
			);


			if (!$return) {
				Yii::log("公共报修{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.create');
			}

			Yii::app()->sms->sendPublicRepairMessage('successfulRepair', $this->id);//发送公共报修 报修成功短信通知业主
			$employeeArr = PublicRepairs::model()->getEmployeeByCommunity($this->community_id, Item::COMPLAIN_REPAIRS_TYPE_PUBLIC);//得到员工ID数组
			if (!empty($employeeArr)) {//不为空时进行发送短信给对应的小区主任

				//前台日志（）
				$logs = ComplainRepairsCustomerLog::createCustomerLog($this->id, "系统自动指派",
					0, Item::COMPLAIN_REPAIRS_LOG_HANDLING, $this->type
				);

				###################能过小区主任ID查找小区主任号码###############
				$cdb = new CDbCriteria();
				$cdb->addInCondition('id', $employeeArr);
				$employee = Employee::model()->findAll($cdb);
				$mobile = array_map(function (Employee $record) {
					return $record->mobile;
				}, $employee);
				if (is_array($mobile)) {
					$mobile = array_unique($mobile);
				}
				Yii::app()->sms->sendPublicRepairMessage('automaticallyAssigned', $this->id, $mobile);//发送公共报修 系统自动指派短信通知小区主任
			}
			return true;
		} else {
			return false;
		}
	}


	/**
	 * @return bool
	 * 接收公共报修流程
	 */
	public function receiveComplain()
	{
		//判断用户是否合格
		if ($this->checkIsReceive()) {
			if ($this->getNowExecutorsID()) {
				throw new CHttpException(400, "投诉已存在当前执行人！");
			}
			//先修改执行人的状态为当前执行人
			$criteria = new CDbCriteria();
			$criteria->compare('complain_repairs_id', $this->id);
			$criteria->compare('employee_id', Yii::app()->user->id);
			$criteria->compare('type', 0);
			$receiveHandle = ComplainRepairsHandling::model()->find($criteria);
			if (ComplainRepairsHandling::model()->updateByPk($receiveHandle->id, array('state' => 1))) {
				//如果是公共报修，且无确认人,那么指定接收的执行人(主任)为确认人.唐静确认过的
				if ($this->type == Item::COMPLAIN_REPAIRS_TYPE_PUBLIC && !$this->confirmed) {
					$crHandle = new ComplainRepairsHandling();
					$crHandle->complain_repairs_id = $this->id;
					$crHandle->employee_id = Yii::app()->user->id;
					$crHandle->type = 2; //确认人
					$crHandle->designate = Yii::app()->user->id;
					$crHandle->state = 0;
					if (!$crHandle->save()) {
						//如果更新状态失败,把当前执行人状态改回来
						ComplainRepairsHandling::model()->updateAll(array('state' => 0), $criteria);
						throw new CHttpException(400, "流程需要一个确认人,且指定失败！");
						//return false;
					}
				}

				if ($this->updatePublicRepairsState()) {
					return true;
				} else {
					//如果更新状态失败,把当前执行人状态改回来
					ComplainRepairsHandling::model()->updateAll(array('state' => 0), $criteria);
					return false;
				}
			} else {
				return false;
			}
		}
		return false;
	}


	/**
	 * @return bool
	 * 更新公共报修流程状态
	 */
	public function updatePublicRepairsState($is_back = 0, $content = null)
	{
		if (empty($this->id)) {
			throw new CHttpException(400, "更新状态失败,未知的公共报修信息!");
		}

		$this->is_back = $is_back;
		$this->accept_employee_id = Yii::app()->user->id;
		$this->accept_time = time();
		$model = self::model()->findByPk($this->id);//保存前记录状态
		if ($this->save()) {

			//前台日志
			if ($this->oldState != $this->state) {
				ComplainRepairsCustomerLog::createEmployeeLog($this->id, $content,
					$this->getUserState(), $this->type);
			}

			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 1, Yii::app()->user->id, (!empty($content) ? '【 ' . $content . ' 】 ' : '') . $this->accept_content);
			if (!$return) {
				Yii::log("公共报修{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.create');
			}
			if (Item::PERSONAL_REPAIRS_CONFIRM_END == $this->state && Item::PERSONAL_REPAIRS_CONFIRM_END != $model->state) {//确认人处理完成
				Yii::app()->sms->sendPublicRepairMessage('processingIsComplete', $this->id);//公共报修处理完成
			}
			return true;
		} else {
			return false;
		}
	}


	/**
	 * @return bool
	 * 更新公共报修流程状态
	 */
	public function updatePublicRepairsStateByEshifu($is_back = 0, $content = null, $eid)
	{
		if (empty($this->id)) {
			throw new CHttpException(400, "更新状态失败,未知的公共报修信息!");
		}

		$this->is_back = $is_back;
		$this->accept_employee_id = $eid;
		$this->accept_time = time();
		$model = self::model()->findByPk($this->id);//保存前记录状态
		if ($this->save()) {

			//前台日志
			if ($this->oldState != $this->state) {
				ComplainRepairsCustomerLog::createEmployeeLog($this->id, $content,
					$this->getUserState(), $this->type);
			}

			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 1, $eid, (!empty($content) ? '【 ' . $content . ' 】 ' : '') . $this->accept_content);
			if (!$return) {
				Yii::log("公共报修{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.create');
			}
			if (Item::PERSONAL_REPAIRS_CONFIRM_END == $this->state && Item::PERSONAL_REPAIRS_CONFIRM_END != $model->state) {//确认人处理完成
				Yii::app()->sms->sendPublicRepairMessage('processingIsComplete', $this->id);//公共报修处理完成
			}
			return true;
		} else {
			return false;
		}
	}


	/**
	 * @return bool
	 * 更新公共报修申请关闭状态
	 */
	public function updatePublicRepairsCloseState($is_back = 0, $content = null)
	{
		if (empty($this->id)) {
			throw new CHttpException(400, "更新状态失败,未知的公共报修信息!");
		}

		$this->is_back = $is_back;
		$this->accept_employee_id = Yii::app()->user->id;
		$this->accept_time = time();
		$model = self::model()->findByPk($this->id);//保存前记录状态
		if ($this->save()) {
			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 1, Yii::app()->user->id, (!empty($content) ? '【 ' . $content . ' 】 ' : '') . $this->apply_content);
			if (!$return) {
				Yii::log("公共报修{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.publicRepairsService.close');
			}
			if (Item::PERSONAL_REPAIRS_CONFIRM_END == $this->state && Item::PERSONAL_REPAIRS_CONFIRM_END != $model->state) {//确认人处理完成
				Yii::app()->sms->sendPublicRepairMessage('processingIsComplete', $this->id);//公共报修处理完成
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return bool
	 * 更新监督流程状态
	 */
	public function updateDisposeState()
	{
		if (empty($this->id)) {
			throw new CHttpException(400, "更新状态失败,未知的公共报修信息!");
		}
		$this->accept_employee_id = Yii::app()->user->id;
		$this->accept_time = time();
		if ($this->save()) {
			$note = Employee::getEmployeeNames(Yii::app()->user->id) . '进行了评论';
			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 2, Yii::app()->user->id, "【 {$note} 】 " . $this->accept_content);
			if (!$return) {
				Yii::log("公共报修{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.create');
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $execAttr : array 执行人
	 * @param $superAttr  array 监督人
	 * @param $confirm  int 确认人ID
	 * @return bool
	 * 指派
	 */
	public function appoint($type = null)
	{
		if ($this->state == Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE)
			throw new CHttpException(400, "此单已结束，不能够指派，请联系管理员");

		$user_id = Yii::app()->user->id;
		$connection = Yii::app()->db;
		$sql = "INSERT INTO complain_repairs_handling(complain_repairs_id,employee_id,type) value ";
		$strSql = "";
		$existExec = array();
		if (!empty($this->execute) && ($this->_old_execute != $this->execute)) { //如果执行人为空
			$this->is_back = 1;
			if (!$this->updateByPk($this->id, array('execute' => $this->execute)))//更新执行人,如果非400操作则添加执行日志
				return false;
		}
		if (!empty($this->confirm) && ($this->_old_confirm != $this->confirm)) { //如果确认人为空
			if (!$this->updateByPk($this->id, array('confirm' => $this->confirm)))//更新确认人,如果非400操作则添加执行日志
				return false;
		}
		//获得已有监督人ID
		$eSuper = ComplainRepairsHandling::model()->findAllByAttributes(array('complain_repairs_id' => $this->id, 'type' => 1));
		$existSuper = array();
		if (!empty($eSuper)) {
			foreach ($eSuper as $esp) {
				array_push($existSuper, $esp->employee_id);
			}
		}
		//增加新的监督人
		foreach ($this->superAttr as $super) {
			if (!in_array($super, $existSuper)) {
				$this->staffId[] = $super;
				$strSql .= "(" . $this->id . "," . $super . ",1),";
			}
		}

		$strSql = trim($strSql, ',');
		if (empty($strSql)) { //没有数据插入到数据库
			$result = true;
		} else {
			$sql = $sql . $strSql;
			$command = $connection->createCommand($sql);
			$result = $command->query();
		}
		//判断是否增加成功
		if ($result) {
			$this->state = Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING;
			//调用更新状态的方法
			$execute = Employee::getEmployeeNames(empty($this->execute) ? $this->_old_execute : $this->execute);//扫行者 有且只有一位
			$supper = Employee::getEmployeeNames(array_merge($this->superAttr, $existSuper));//监督者
			$confirm = Employee::getEmployeeNames(empty($this->confirm) ? $this->_old_confirm : $this->confirm);//确认者
			if (!$type) {
				//400指派任务
				$note = sprintf('信息中心%s指派任务给%s，指定监督人为%s，指定最后确认人为%s', Employee::getEmployeeNames($user_id), $execute, is_array($supper) ? implode('，', $supper) : $supper, $confirm);
			} else {
				//确认者拒绝
				$note = Employee::getEmployeeNames(Yii::app()->user->id) . '拒绝确认任务，任务打回重新处理';
			}
			if (!$this->updatePublicRepairsState($this->is_back, $note))
				return false;

			if (!$type) {
				########指派时分送的短信########
				$cdb = new CDbCriteria();
				array_push($this->staffId, $this->confirm);
				$cdb->addInCondition('id', $this->staffId);
				$employee = Employee::model()->findAll($cdb);
				$mobile = array_map(function (Employee $record) {
					return $record->mobile;
				}, $employee);
				if (!empty($mobile)) {
					if (is_array($mobile)) {
						$mobile = array_unique($mobile);
					}
					Yii::app()->sms->sendPublicRepairMessage('designate', $this->id, $mobile);
				}
			}
		}

		//前台日志
		if ($this->oldState != $this->state) {
			ComplainRepairsCustomerLog::createEmployeeLog($this->id, $this->accept_content,
				$this->getUserState(), $this->type);
		}

		return true;
	}

	//执行人流转
	public function exchange()
	{
		if ($this->state == Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE)
			throw new CHttpException(400, "此单已结束，不能够流转，请联系管理员");

		if (!empty($this->execute) && ($this->_old_execute != $this->execute)) { //如果执行人为空
			if (!$this->updateByPk($this->id, array('execute' => $this->execute)) ||
				!ComplainRepairsHandling::createExecuted($this->id)
			)//更新执行人,如果非400操作则添加执行日志
				return false;

			$this->state = Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING;
			//调用更新状态的方法
			$note = Employee::getEmployeeNames(Yii::app()->user->id) . '流转任务给' . Employee::getEmployeeNames($this->execute);
			if ($this->updatePublicRepairsState(0, $note)) { //修改状态为处理中
				Yii::app()->sms->sendPublicRepairMessage('circulation', $this->id);
//                ########执行人流转时分送的短信########
//                $cdb = new CDbCriteria();
//                array_push($this->staffId,$this->confirm);
//                $cdb->addInCondition('id',$this->staffId);
//                $employee = Employee::model()->findAll($cdb);
//                $mobile = array_map(function(Employee $record){
//                    return $record->mobile;
//                },$employee);
//                if(!empty($mobile)){
//                    if(is_array($mobile)){
//                        $mobile = array_unique($mobile);
//                    }
//                    Yii::app()->sms->sendPublicRepairMessage('circulation',$this->id,$mobile);
//                }
				return true;
			}
		}

		return false;
	}

	/**
	 * @return bool
	 * 找不到公共报修办公主任时，写入记录表
	 */
	public function updateLogWithoutLeader()
	{
		if (empty($this->id)) {
			throw new CHttpException(400, "写入记录失败,未知的公共报修信息!");
		}
		$this->accept_employee_id = 0; //最终处理人0，系统
		$this->accept_time = time();
		$this->state = Item::COMPLAIN_REPAIRS_AWAITING_HANDLE;
		if ($this->update()) {
			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 1, 0, "【 找不到小区办公主任，公共报修转到400服务中心 】");
			if ($return) {
				return true;
			} else if (!$return) {
				return false;
			}
		}
	}

	//当公共报修时只有一位小区主任，指定执行人跟确认人是自己
	public function updateOneHandling($id = "")
	{
		if (empty($this->id)) {
			throw new CHttpException(400, "指定失败,未知的公共报修信息!");
		}
		if ($id == "") {
			return false;
		} else {
			$this->is_back = 1; //可以回踢
			$this->accept_time = time();
			$this->state = Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING;
			$this->execute = $id;
			$this->confirm = $id;
			if ($this->update()) {
				$temp = Employee::getEmployeeNames($id);
				$note = sprintf('系统自动指派任务给%s，指定最后确认人为%s', $temp, $temp);
				$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 1, 0, "【 {$note} 】");
				//$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 1, 0, "找不到小区办公主任，公共报修转到400服务中心");
				if ($return) {
					return true;
				} else {
					return false;
				}
			}
		}
	}

	//当公共报修有多位小区主任，取第一个指定执行人/确认人
	public function updateMoreHandling($ids = array())
	{
		if (empty($this->id)) {
			throw new CHttpException(400, "指定失败,未知的公共报修信息!");
		}
		if (empty($ids)) {
			return false;
		} else if (!empty($ids) && is_array($ids)) {
			$id = intval(current($ids));
			$this->is_back = 1; //可以回踢
			$this->accept_time = time();
			$this->state = Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING;
			$this->execute = $id;
			$this->confirm = $id;
			if ($this->update()) {
				$temp = Employee::getEmployeeNames($id);
				$note = sprintf('系统自动指派任务给%s，指定最后确认人为%s', $temp, $temp);
				if (!ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 1, 0, "【 {$note} 】"))
					return false;
			}
			return true;
		}
	}

	//当公共报修有多位监督人，将写入complain_repairs_handling记录，只指定监督人
	public function updateMoreSuperviseHandling($ids = array())
	{
		if (empty($this->id)) {
			throw new CHttpException(400, "指定失败,未知的公共报修信息!");
		}
		if (empty($ids)) {
			return false;
		} else if (!empty($ids)) {
			foreach ($ids as $id) {
				$model = new ComplainRepairsHandling();
				$model->complain_repairs_id = $this->id;
				$model->employee_id = $id;
				$model->type = 1;
				if (!$model->save()) {
					return false;
				}
			}
			//写日志
			$note = '系统自动指派任务指定监督人为' . implode('，', Employee::getEmployeeNames($ids));
			//$note = '公共报修获得多个第一次自动监督职位的员工'.$this->getEmployees($ids);
			ComplainRepairsLog::createLog($this->id, '', 0, 1, 0, "【 {$note} 】");

			return true;
		}
	}

	public function checkCommunity()
	{
		if (!$this->hasErrors() && $this->community_id <= 0 && empty($this->community_id)) {
			$this->addError('execName', '请填写小区。');
		}
	}

	public function checkExec()
	{
		if (!$this->hasErrors() && empty($this->execute))
			$this->addError('execName', '没有指定执行人,执行人不能为空。');
	}

	public function checkConfirm()
	{
		if (!$this->hasErrors() && empty($this->confirm)) {
			//if (empty($this->confirmed))
			$this->addError('confirmName', '没有指定确认人,确认人不能为空。');
		}
	}

	public function searchByQuality()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_PUBLIC);
		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭
		$criteria->compare('work_no', $this->work_no);
		//$criteria->compare('apply_close','1'); //{质检中心只查看申请关闭的投诉} 质检中心可以看到所有的单

		if ($this->username != '') {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.username', $this->username, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "< " . strtotime($this->endTime . " 23:59:59"));
		}

		//选择的组织架构ID
		if ($this->branch_id != '')
			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
		else if (!empty($this->communityIds)) //如果有小区
			$community_ids = $this->communityIds;
		else if ($this->region != '') //如果有地区
			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');

		if (!empty($community_ids))
			$criteria->addInCondition('`t`.community_id', $community_ids);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id desc',
			)
		));
	}

	public function report_searchByQuality()
	{
		$criteria = new CDbCriteria;
		if (isset($_GET['PublicRepairs']) && !empty($_GET['PublicRepairs'])) {
			$_SESSION['PublicRepairs'] = array();
			$_SESSION['PublicRepairs'] = $_GET['PublicRepairs'];
		}
		if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
			if (isset($_SESSION['PublicRepairs']) && !empty($_SESSION['PublicRepairs'])) {
				foreach ($_SESSION['PublicRepairs'] as $key => $val) {
					if ($val != "") {
						$this->$key = $val;
					}
				}
			}
		}
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_PUBLIC);
		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭
		$criteria->compare('work_no', $this->work_no);
		//$criteria->compare('apply_close','1'); //{质检中心只查看申请关闭的投诉} 质检中心可以看到所有的单

		if ($this->username != '') {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.username', $this->username, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "< " . strtotime($this->endTime . " 23:59:59"));
		}

		//选择的组织架构ID
		if ($this->branch_id != '')
			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
		else if (!empty($this->communityIds)) //如果有小区
			$community_ids = $this->communityIds;
		else if ($this->region != '') //如果有地区
			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');

		if (!empty($community_ids))
			$criteria->addInCondition('`t`.community_id', $community_ids);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function mySearch($type = '')
	{

		$criteria = new CDbCriteria;


		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_PUBLIC);
		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭


		$criteria->addCondition("((`t`.state = " . Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING . " and `t`.execute =" . Yii::app()->user->id . ")
         or ( `t`.state = " . Item::COMPLAIN_REPAIRS_HANDLE_END . " and `t`.confirm = " . Yii::app()->user->id . "))");
		$criteria->addCondition('`t`.state <>' . Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);

		if ($this->username != '') {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.username', $this->username, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "< " . strtotime($this->endTime . " 23:59:59"));
		}

		//选择的组织架构ID
//		if ($this->branch_id != '')
//			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
//		else if (!empty($this->communityIds)) //如果有小区
//			$community_ids = $this->communityIds;
//		else if ($this->region != '') //如果有地区
//			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');

		//选择的组织架构ID
		if ($this->branch_id != '') {
			$community_ids = ICEBranch::model()->findByPk($this->branch_id)->ICEGetBranchAllCommunity();
		} else if (!empty($this->communityIds)) {
			//如果有小区
			$community_ids = $this->communityIds;
		} else if ($this->province_id) {
			//如果有地区
			if ($this->district_id) {
				$regionId = $this->district_id;
			} else if ($this->city_id) {
				$regionId = $this->city_id;
			} else if ($this->province_id) {
				$regionId = $this->province_id;
			} else {
				$regionId = 0;
			}
			$community_ids = ICERegion::model()->getRegionCommunity(
				$regionId,
				'id'
			);
		}

		if (!empty($community_ids))
			$criteria->addInCondition('`t`.community_id', $community_ids);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id desc',
			)
		));
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_PUBLIC);
		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭
		$criteria->compare('work_no', $this->work_no);
		if ($this->username != '') {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.username', $this->username, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "< " . strtotime($this->endTime . " 23:59:59"));
		}
		//选择的组织架构ID
		if ($this->branch_id != '')
			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
		else if (!empty($this->communityIds)) //如果有小区
			$community_ids = $this->communityIds;
		else if ($this->region != '') //如果有地区
			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');

		if (!empty($community_ids))
			$criteria->addInCondition('`t`.community_id', $community_ids);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id desc',
			)
		));
	}

	public function report_search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
		if (isset($_GET['PublicRepairs']) && !empty($_GET['PublicRepairs'])) {
			$_SESSION['PublicRepairs'] = array();
			$_SESSION['PublicRepairs'] = $_GET['PublicRepairs'];
		}
		if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
			if (isset($_SESSION['PublicRepairs']) && !empty($_SESSION['PublicRepairs'])) {
				foreach ($_SESSION['PublicRepairs'] as $key => $val) {
					if ($val != "") {
						$this->$key = $val;
					}
				}
			}
		}
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_PUBLIC);
		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭
		$criteria->compare('work_no', $this->work_no);
		if ($this->username != '') {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.username', $this->username, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "< " . strtotime($this->endTime . " 23:59:59"));
		}
		//选择的组织架构ID
		if ($this->branch_id != '')
			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
		else if (!empty($this->communityIds)) //如果有小区
			$community_ids = $this->communityIds;
		else if ($this->region != '') //如果有地区
			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');

		if (!empty($community_ids))
			$criteria->addInCondition('`t`.community_id', $community_ids);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * @param $community_id
	 * $param $type:类型，传Item里的报修类型，int
	 * @return array|null
	 * 获得小区主任
	 */
	public function getEmployeeByCommunity($community_id, $type)
	{
		if (empty($community_id)) {
			return null;
		}
		$community = Community::model()->findByPk($community_id);
		if (empty($community)) {
			return null;
		}
		//获得小区对应的管辖部门ID
		$branch = $community->branch;
		if (empty($branch)) {
			return null;
		}
		//得到小区对应的部门及该部门下的职能部门ID
		$branchList = $branch->getFunctionBranchIds();
		if (empty($branchList)) {
			return null;
		}
		$criteria = new CDbCriteria();
		$criteria->addInCondition('branch_id', $branchList);
		$ebrList = EmployeeBranchRelation::model()->findAll($criteria);
		if (empty($ebrList)) {
			return null;
		}
		//获得部门下所有员工
		$ebrEmployeeList = array_map(function ($ebr) {
			return $ebr->employee_id;
		}, $ebrList);
		//获得小区主任职位ID
		if ($type == Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER) { //业主投诉
			$config = Yii::app()->config->customerComplainConfig;
		} elseif ($type == Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE) { //员工投诉
			$config = Yii::app()->config->staffComplainConfig;
		} elseif ($type == Item::COMPLAIN_REPAIRS_TYPE_PERSON) { //个人报修
			$config = Yii::app()->config->personalRepairsConfig;
		} elseif ($type == Item::COMPLAIN_REPAIRS_TYPE_PUBLIC) { //公共报修
			$config = Yii::app()->config->publicRepairsConfig;
		} else {
			throw new CHttpException('400', "投诉报修类型错误:未知的类型！");
		}

		$eprList = EmployeePositionRelation::model()->findAllByAttributes(array('position_id' => @$config['communityLeader']));
		//获得职位下的所有员工
		$eprEmployeeList = array_map(function ($ebr) {
			return $ebr->employee_id;
		}, $eprList);
		//返回两个员工数组的交集
		return array_unique(array_intersect($ebrEmployeeList, $eprEmployeeList));

	}

	/**
	 * @param $community_id
	 * $param $type:类型，传Item里的报修类型，int
	 * @return array|null
	 * 获得自动监督人
	 */
	public function getMonitorEmployeeByCommunity($community_id, $type)
	{
		if (empty($community_id)) {
			return null;
		}
		$community = Community::model()->findByPk($community_id);
		if (empty($community)) {
			return null;
		}
		//获得小区对应的管辖部门ID
		$branch = $community->branch;
		if (empty($branch)) {
			return null;
		}
		//得到小区对应的部门上面三级不包括本身的部门ID
		$branchList = $branch->getSuperviseBranchIds();
		if (empty($branchList)) {
			return null;
		}
		$criteria = new CDbCriteria();
		$criteria->addInCondition('branch_id', $branchList);
		$ebrList = EmployeeBranchRelation::model()->findAll($criteria);
		if (empty($ebrList)) {
			return null;
		}
		//获得部门下所有员工
		$ebrEmployeeList = array_map(function ($ebr) {
			return $ebr->employee_id;
		}, $ebrList);
		//获得小区主任职位ID
		if ($type == Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER) { //业主投诉
			$config = Yii::app()->config->customerComplainConfig;
		} elseif ($type == Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE) { //员工投诉
			$config = Yii::app()->config->staffComplainConfig;
		} elseif ($type == Item::COMPLAIN_REPAIRS_TYPE_PERSON) { //个人报修
			$config = Yii::app()->config->personalRepairsConfig;
		} elseif ($type == Item::COMPLAIN_REPAIRS_TYPE_PUBLIC) { //公共报修
			$config = Yii::app()->config->publicRepairsConfig;
		} else {
			throw new CHttpException('400', "投诉报修类型错误:未知的类型！");
		}

		$eprList = EmployeePositionRelation::model()->findAllByAttributes(array('position_id' => @$config['automaticSupervisoryPositions']));
		//获得职位下的所有员工
		$eprEmployeeList = array_map(function ($ebr) {
			return $ebr->employee_id;
		}, $eprList);
		//返回两个员工数组的交集
		return array_unique(array_intersect($ebrEmployeeList, $eprEmployeeList));
	}

	public function executionSearch($type = 0)
	{
		$criteria = new CDbCriteria;

		//按类型得到处理人列表
		$handling = ComplainRepairsHandling::model()->findAllByAttributes(array('type' => $type, 'employee_id' => Yii::app()->user->id));
		$idList = array();
		foreach ($handling as $hand) {
			//获得该用户的所有投诉ID
			array_push($idList, $hand->complain_repairs_id);
		}
		$criteria->addInCondition('`t`.id', $idList);
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', $this->complainRepairsType);

		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare("`t`.`execute`", $this->execute);
		$criteria->compare('`t`.`confirm`', $this->confirm);
		$criteria->addCondition('`t`.state <> ' . Item:: COMPLAIN_REPAIRS_SUCCESS_COLOSE);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭

		if ($this->username != '') {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.username', $this->username, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "< " . strtotime($this->endTime . " 23:59:59"));
		}

//		//选择的组织架构ID
//		if ($this->branch_id != '')
//			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
//		else if (!empty($this->communityIds)) //如果有小区
//			$community_ids = $this->communityIds;
//		else if ($this->region != '') //如果有地区
//			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');

		//选择的组织架构ID
		if ($this->branch_id != '') {
			$community_ids = ICEBranch::model()->findByPk($this->branch_id)->ICEGetBranchAllCommunity();
		} else if (!empty($this->communityIds)) {
			//如果有小区
			$community_ids = $this->communityIds;
		} else if ($this->province_id) {
			//如果有地区
			if ($this->district_id) {
				$regionId = $this->district_id;
			} else if ($this->city_id) {
				$regionId = $this->city_id;
			} else if ($this->province_id) {
				$regionId = $this->province_id;
			} else {
				$regionId = 0;
			}
			$community_ids = ICERegion::model()->getRegionCommunity(
				$regionId,
				'id'
			);
		}

		if (!empty($community_ids))
			$criteria->addInCondition('`t`.community_id', $community_ids);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id desc',
			)
		));
	}


	public function afterFind()
	{
		$this->_old_execute = $this->execute;
		$this->_old_confirm = $this->confirm;
		return parent::afterFind();
	}

	/**
	 * 商家是否拒绝
	 * @return boolean(拒绝=>true,接受=> false)
	 */
	public function getShopAgree()
	{
		if ($this->shop_state == Item::PERSONAL_REPAIRS_SHOP_REFUSAL) {
			return true;
		} else {
			return false;
		}
	}

	protected function ICEGetSearchRegionData($search = array())
	{
		return array(
			'province_id' => isset($search['province_id']) && $search['province_id']
				? $search['province_id'] : '',
			'city_id' => isset($search['city_id']) && $search['city_id']
				? $search['city_id'] : '',
			'district_id' => isset($search['district_id']) && $search['district_id']
				? $search['district_id'] : '',
		);
	}

	protected function ICEGetLinkageRegionDefaultValueForUpdate()
	{
		return array();
	}

	public function ICEGetLinkageRegionDefaultValueForSearch()
	{
		$searchRegion = $this->ICEGetSearchRegionData(isset($_GET[__CLASS__])
			? $_GET[__CLASS__] : array());

		$defaultValue = array();

		if ($searchRegion['province_id']) {
			$defaultValue[] = $searchRegion['province_id'];
		} else {
			return $defaultValue;
		}

		if ($searchRegion['city_id']) {
			$defaultValue[] = $searchRegion['city_id'];
		} else {
			return $defaultValue;
		}

		if ($searchRegion['district_id']) {
			$defaultValue[] = $searchRegion['district_id'];
		} else {
			return $defaultValue;
		}

		return $defaultValue;
	}

	public function ICEGetLinkageRegionDefaultValue()
	{
		$updateDefaults = $this->ICEGetLinkageRegionDefaultValueForUpdate();
		return $updateDefaults
			? $updateDefaults
			: $this->ICEGetLinkageRegionDefaultValueForSearch();
	}
}

?>
