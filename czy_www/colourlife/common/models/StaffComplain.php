<?php

class StaffComplain extends ComplainRepairs
{
	public $modelName = '员工投诉';
	public $execName; //执行人名称
	public $superName; //执行人名称
	public $confirmName; //执行人名称
	public $execAttr = array(); //执行人列表
	public $superAttr = array(); //监督人列表
	public $images = array(); //图片路径列表
	public $confirm; //确认人
	public $staffId = array();
	public $complainRepairsType = Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE;

	private $_old_execute;
	private $_old_confirm;

	public $communityIds;
	public $region;
	public $community_id;

	public $province_id;
	public $city_id;
	public $district_id;


	static $_state_list = array(
		Item::COMPLAIN_REPAIRS_AWAITING_HANDLE => "待处理",
		Item::COMPLAIN_REPAIRS_AWAITING_RECEIVE => "待接收",
		Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING => "处理中",
		Item::COMPLAIN_REPAIRS_HANDLE_END => "已处理",
		Item::COMPLAIN_REPAIRS_CONFIRM_END => "已确认",
		Item::COMPLAIN_REPAIRS_POOR_VISIT => "回访",
		Item::COMPLAIN_REPAIRS_APPLY_COLOSE => "申请关闭",
		Item::COMPLAIN_REPAIRS_ABNORMAL_COLOSE => "400申请关闭结束",
		Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE => "投诉单自动关闭",
		Item::COMPLAIN_REPAIRS_PUBLIC_NONE => "指定小区主任",
	);

	public function rules()
	{
		$array = array(
			array('execute,execName,superAttr,confirm,images,accept_content', 'safe'),
			array('accept_content,suggestion,agree', 'required', 'on' => 'dispose,supervision,handle'),
			array('category_id,customer_name,customer_tel, branch_id,content', 'required', 'on' => 'staffCreate'),
			array('execAttr', 'checkExec', 'on' => '400update'),
			array('confirm', 'checkConfirm', 'on' => '400update'),
			array('execAttr,accept_content', 'safe', 'on' => 'redo,refuse'),
			array('work_no', 'required', 'on' => '400update'),
			//			ICE 搜索数据
			array('province_id,city_id,district_id', 'safe'),
		);
		return CMap::mergeArray(parent::rules(), $array);
	}

	public function relations()
	{
		$array = array(
			'employee' => array(self::BELONGS_TO, 'Employee', 'user_id'),
		);
		return CMap::mergeArray(parent::relations(), $array);
	}

	public function getStatusName($html = true)
	{
		$return = '';
		$return .= ($html) ? '<span class="label label-success">' : '';
		$return .= empty(self::$_state_list[$this->state]) ? "" : self::$_state_list[$this->state];
		$return .= ($html) ? '</span>' : '';
		return $return;
	}

	public function getStatus()
	{
		$return = '';
		$return .= empty(self::$_state_list[$this->state]) ? "" : self::$_state_list[$this->state];
		return $return;
	}

	public function getCreateTime()
	{
		return date("Y-m-d H:i", $this->create_time);
	}

	public function attributeLabels()
	{
		$array = array(
			'execAttr' => '执行人',
			'superAttr' => '监督人',
			'images' => '图片',
			'confirm' => '确认人',
			'execName' => '执行人',
			'superName' => '监督人',
			'confirmName' => '确认人',
			'id' => '投诉工单',
			'type' => '类型',
			'category_id' => '类别',
			'user_id' => '投诉人ID',
			'customer_name' => '员工姓名',
			'customer_tel' => '员工电话',
			'community_id' => '小区',
			'content' => '内容',
			'create_time' => '创建时间',
			'accept_employee_id' => '最后处理人',
			'accept_time' => '最后处理时间',
			'accept_content' => '最后处理内容',
			'final_evaluate_state' => '用户最终评价',
			'final_evaluate_note' => '用户最终评价内容',
			'final_evaluate_time' => '用户最终评价时间',
			'state' => '状态',
			'model' => '模型',
			'user_is_visible' => '用户是否可见',
			'is_deleted' => '是否删除',
			'source' => '评分',
			'suggestion' => '处理意见',
			'agree' => '处理结果',
			'is_connect' => '是否接通',
			'visit_time' => '回访时间',
			'connect_time' => '接通时间',
			'score' => '评分',
			'note' => '备注',
			'customerName' => '报修人',
			'customerTel' => '联系电话',
			'create_time' => '报修时间',
			'communityInBranch' => '报修小区所属部门',
			'communityDetail' => '业主报修小区',
		);
		return CMap::mergeArray(parent::attributeLabels(), $array);
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	//get姓名
	public function getCustomerName()
	{
		if ($this->customer_name != '') {
			$username = empty($this->employee) ? '' : '(' . $this->employee->name . ')';
			return $this->customer_name . '(' . $username . ')';
		} else {
			return '';
		}

	}

	/**
	 * @param $complainAttr array 至少包括以下内容：category_id、user_id、community_id、content
	 * @return boolean
	 * 业主新建业主投诉
	 */
	public function createComplainByStaff()
	{
		if (empty($this) || empty($this->commuExecutionStaffComplainnity_id)) { //如果未传参数，或小区为空，直接报错
			throw new CHttpException(400, "创建业主投诉失败!");
		}
		if (empty($this->user_id)) { //如果投诉人ID为空或为0.
			throw new CHttpException(400, "创建业主投诉失败!投诉人不能为空！");
		}
		$customer = Customer::model()->findByPk($this->user_id);
		if (empty($customer)) {
			throw new CHttpException(400, "创建业主投诉失败!投诉人不能为空！");
		}
		$this->customer_name = empty($customer->name) ? $customer->username : $customer->name;
		$this->customer_tel = $customer->mobile;
		$this->type = Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE;; //投诉类型为员工投诉
		$this->model = 0; //所处模块为员工
		$this->state = 0; //初始状态
		//业主自己创单，最后处理信息应该为空
		$this->accept_employee_id = 0;
		$this->accept_time = 0;
		$this->accept_content = "";
		//调用公共方法
		return $this->createComplain();
	}

	/**
	 * @param $complainAttr array 至少包括以下内容：category_id、community_id、content
	 * @return boolean
	 * 400新建业主投诉
	 */
	public function createComplainByService()
	{
		if (empty($this) || empty($this->branch_id)) { //如果未传参数，或小区为空，直接报错
			throw new CHttpException(400, "创建员工投诉失败!");
		}

		$this->user_id = Yii::app()->user->id; //
		$this->type = Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE; //投诉类型为员工投诉
		$this->model = 1; //所处模块为400
		$this->state = 0; //初始状态
		//400创单，最后处理信息应该为400的信息
		$this->accept_employee_id = Yii::app()->user->id;
		$this->accept_time = time();
		$this->accept_content = "员工投诉创建";
		//调用公共方法
		return $this->createComplain();
	}

	private function createComplain()
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
					Yii::log("投诉{$this->id}更新图片失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.create');
				}
			}
			$note = Branch::getMyParentBranchName($this->branch_id, true);
			$note = '系统接收到员工投诉，投诉' . $note;
			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state,
				0, Yii::app()->user->id, "【 {$note} 】");
			if (!$return) {
				Yii::log("投诉{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.create');
			}


			//前台日志（）
			$logs = ComplainRepairsCustomerLog::createCustomerLog($this->id, $note,
				0, Item::COMPLAIN_REPAIRS_LOG_AWAITING_HANDL, $this->type
			);

			####### sms ######
			Yii::app()->sms->sendStaffComplaintsMessage('complaintsSuccess', $this->id, Yii::app()->user->mobile);//发送员工投诉短信
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return bool
	 * 更新投诉流程状态
	 */
	public function updateComplainState($is_back = 0, $content = null)
	{
		if (empty($this->id)) {
			throw new CHttpException(400, "更新状态失败,未知的投诉信息!");
		}
		$this->is_back = $is_back;

		$this->accept_employee_id = Yii::app()->user->id;
		$this->accept_time = time();
		$model = self::model()->findByPk($this->id);
		if ($this->save()) {

			//前台日志
			if ($this->oldState != $this->state) {
				ComplainRepairsCustomerLog::createEmployeeLog($this->id, $content,
					$this->getUserState(), $this->type);
			}

			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 1, Yii::app()->user->id, (!empty($content) ? '【 ' . $content . ' 】 ' : '') . $this->accept_content);
			if (!$return) {
				Yii::log("投诉{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.create');
			}
			if (Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE == $this->state && Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE != $model->state) {//确认人确认  上一个状态不等于处理完成状态
				Yii::app()->sms->sendStaffComplaintsMessage('processingIsComplete', $this->id);//员工投诉 发送处理完成短信
			}
			return true;
		} else {
			return false;
		}
	}


	/**
	 * @return bool
	 * 更新投诉流程申请关闭状态
	 */
	public function updateComplainCloseState($is_back = 0, $content = null)
	{
		if (empty($this->id)) {
			throw new CHttpException(400, "更新状态失败,未知的投诉信息!");
		}
		$this->is_back = $is_back;

		$this->accept_employee_id = Yii::app()->user->id;
		$this->accept_time = time();
		$model = self::model()->findByPk($this->id);
		if ($this->save()) {
			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 1, Yii::app()->user->id, (!empty($content) ? '【 ' . $content . ' 】 ' : '') . $this->apply_content);
			if (!$return) {
				Yii::log("投诉{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.create');
			}
			if (Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE == $this->state && Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE != $model->state) {//确认人确认  上一个状态不等于处理完成状态
				Yii::app()->sms->sendStaffComplaintsMessage('processingIsComplete', $this->id);//员工投诉 发送处理完成短信
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
			throw new CHttpException(400, "更新状态失败,未知的投诉信息!");
		}
		$this->accept_employee_id = Yii::app()->user->id;
		$this->accept_time = time();
		if ($this->save()) {
			$note = Employee::getEmployeeNames(Yii::app()->user->id) . '进行了评论';
			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 2, Yii::app()->user->id, "【 {$note} 】" . $this->accept_content);
			if (!$return) {
				Yii::log("投诉{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.create');
			}
			return true;
		} else {
			return false;
		}
	}


	private function deleteErrorHandling()
	{
		$idList = array_merge($this->execAttr, $this->superAttr);
		array_push($idList, $this->confirm);
		$idList = array_unique($idList);
		$criteria = new CDbCriteria;
		$criteria->addCondition('complain_repairs_id=' . $this->id);
		$criteria->addInCondition('employee_id', $idList);
		ComplainRepairsHandling::model()->deleteAll($criteria);
	}


	public function getCategoryName()
	{
		$cate = StaffComplainCategory::model()->findByPk($this->category_id);
		return $cate['name'];
	}

	public function getBranchName()
	{
//		这个架构用原来的就可以吧
		$data = Branch::getMyParentBranchName($this->branch_id, true);
		return $data;
	}


	//只获取员工投诉的架构小区
	public function getCommunityNameOnly()
	{
//		这个架构用原来的就可以吧
		$data = Branch::model()->findByPk($this->branch_id);
		if ($data) {
			return $data->name;
		} else {
			return "";
		}

	}

	/**
	 * 员工报修没有小区只有部门
	 * @return string|void
	 */
	public function getCommunityName()
	{
		$this->getBranchName();
	}

	/**
	 * @return bool
	 * 员工接收投诉流程
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
				if ($this->updateComplainState()) {
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
			if (!$this->updateComplainState($this->is_back, $note))
				return false;

			if (!$type) {
				########### 400 指派员工投诉指派 ##############
				$cdb = new CDbCriteria();
				array_push($this->staffId, $this->confirm);//确认人 与新增的执行人，监督人
				$cdb->addInCondition('id', $this->staffId);
				$employee = Employee::model()->findAll($cdb);
				$mobile = array_map(function (Employee $record) {
					return $record->mobile;
				}, $employee);
				if (!empty($mobile)) {
					Yii::app()->sms->sendStaffComplaintsMessage('designate', $this->id, $mobile);//发送员工投诉 400指派短信
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

		if (!empty($this->execute) && ($this->_old_execute != $this->execute)) { //如果执行人不为空
			if (!$this->updateByPk($this->id, array('execute' => $this->execute)) ||
				!ComplainRepairsHandling::createExecuted($this->id)
			)//更新执行人,如果非400操作则添加执行日志
				return false;

			$this->state = Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING;
			//调用更新状态的方法
			$note = Employee::getEmployeeNames(Yii::app()->user->id) . '流转任务给' . Employee::getEmployeeNames($this->execute);
			if ($this->updateComplainState(0, $note)) {
				Yii::app()->sms->sendStaffComplaintsMessage('circulation', $this->id);//员工投诉 发送处理完成短信
				return true;
			} //修改状态为处理中
		}

		return false;
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
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE);
		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->addCondition('`t`.branch_id <> 0');
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭

		if ($this->username != '') {
			$criteria->with[] = 'employee';
			$criteria->compare('employee.username', $this->username, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "< " . strtotime($this->endTime . " 23:59:59"));
		}

		/* //选择的组织架构ID
		 if ($this->branch_id != '')
			 $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
		 else if (!empty($this->communityIds)) //如果有小区
			 $community_ids = $this->communityIds;
		 else if ($this->region != '') //如果有地区
			 $community_ids = Region::model()->getRegionCommunity($this->region, 'id');

		 if(!empty($community_ids))
			 $criteria->addInCondition('`t`.community_id', $community_ids);*/

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
	public function check_search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		$criteria = new CDbCriteria;
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE);
		$criteria->compare('`t`.user_id', Yii::app()->user->id);
		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->addCondition('`t`.branch_id <> 0');
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);

//		if ($this->username != '') {
//			$criteria->with[] = 'employee';
//			$criteria->compare('employee.username', $this->username, true);
//		}

//      ICE 接入员工姓名搜索 上面的逻辑是链表查询通过员工username模糊搜索（搜出来的东西没有用），下面同样逻辑。
		if ($this->username != '') {
			$employee = ICEEmployee::model()->ICEGetAccountSearch(array('keyword'=>$this->username));
			$employee_id = array();
			if(!empty($employee)){
				$employee_id = array();
				foreach($employee as $id){
					if(empty($id['czyId'])){
						continue;
					}
					$employee_id[] = $id['czyId'];
				}
			}
			$criteria->addInCondition('`t`.user_id', $employee_id);
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

	public function searchByQuality()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE);
		//$criteria->compare('`t`.state', Item::COMPLAIN_REPAIRS_APPLY_COLOSE); //质检中心只查看申请关闭的投诉
		$criteria->compare('`t`.category_id', $this->category_id);
		//$criteria->compare("apply_close", '1');//加了这个  会使400申请关闭  质检中心不同意之后会看不到数据了
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->addCondition('`t`.branch_id <> 0');
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->addCondition('`t`.state <> ' . Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);
		$criteria->addCondition('`t`.state <> ' . Item::COMPLAIN_REPAIRS_ABNORMAL_COLOSE);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭

		if ($this->username != '') {
			$criteria->with[] = 'employee';
			$criteria->compare('employee.username', $this->username, true);
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

	public function mySearch()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE);
		$criteria->addCondition('`t`.branch_id <> 0');
		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->addCondition("(`t`.state = " . Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING . " and `t`.execute =" . Yii::app()->user->id . ")
         or ( `t`.state = " . Item::COMPLAIN_REPAIRS_HANDLE_END . " and `t`.confirm = " . Yii::app()->user->id . ")");
		// $criteria->compare('`t`.state',Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);
		$criteria->addCondition('`t`.state <>' . Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);
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
		/* //选择的组织架构ID
		 if ($this->branch_id != '')
			 $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
		 else if (!empty($this->communityIds)) //如果有小区
			 $community_ids = $this->communityIds;
		 else if ($this->region != '') //如果有地区
			 $community_ids = Region::model()->getRegionCommunity($this->region, 'id');

		 if(!empty($community_ids))
			 $criteria->addInCondition('`t`.community_id', $community_ids);*/

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id desc',
			)
		));
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
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE);

		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare("`t`.`execute`", $this->execute);
		$criteria->compare('`t`.`confirm`', $this->confirm);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭
		// $criteria->compare('`t`.state',Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);
		// $criteria->compare('`t`.state ',Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);
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

	/*
 * 报表搜索方法
 * */
	public function report_search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
		if (isset($_GET['StaffComplain']) && !empty($_GET['StaffComplain'])) {
			$_SESSION['StaffComplain'] = array();
			$_SESSION['StaffComplain'] = $_GET['StaffComplain'];
		}
		if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
			if (isset($_SESSION['StaffComplain']) && !empty($_SESSION['StaffComplain'])) {
				foreach ($_SESSION['StaffComplain'] as $key => $val) {
					if ($val != "") {
						$this->$key = $val;
					}
				}
			}
		}
		//
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE);
		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->addCondition('`t`.branch_id <> 0');
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭

		if ($this->username != '') {
			$criteria->with[] = 'employee';
			$criteria->compare('employee.username', $this->username, true);
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

	/*
 * 报表搜索方法
 * */
	public function quality_report_search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
		if (isset($_GET['ResourceCenter']) && !empty($_GET['ResourceCenter'])) {
			$_SESSION['ResourceCenter'] = array();
			$_SESSION['ResourceCenter'] = $_GET['ResourceCenter'];
		}
		if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
			if (isset($_SESSION['ResourceCenter']) && !empty($_SESSION['ResourceCenter'])) {
				foreach ($_SESSION['ResourceCenter'] as $key => $val) {
					if ($val != "") {
						$this->$key = $val;
					}
				}
			}
		}
		//
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_EMPLOYEE);
		//$criteria->compare('`t`.state', Item::COMPLAIN_REPAIRS_APPLY_COLOSE); //质检中心只查看申请关闭的投诉
		$criteria->compare('`t`.category_id', $this->category_id);
		//$criteria->compare("apply_close", '1');//加了这个  会使400申请关闭  质检中心不同意之后会看不到数据了
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->addCondition('`t`.branch_id <> 0');
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->addCondition('`t`.state <> ' . Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);
		$criteria->addCondition('`t`.state <> ' . Item::COMPLAIN_REPAIRS_ABNORMAL_COLOSE);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭

		if ($this->username != '') {
			$criteria->with[] = 'employee';
			$criteria->compare('employee.username', $this->username, true);
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


	public function afterFind()
	{
		$this->_old_execute = $this->execute;
		$this->_old_confirm = $this->confirm;
		//$this->execName = $this->getExecName();
		//$this->confirmName = $this->getConfirmName();
		return parent::afterFind();
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
