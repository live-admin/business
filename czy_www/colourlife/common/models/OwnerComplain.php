<?php

class OwnerComplain extends ComplainRepairs
{
	public $modelName = '业主投诉';
	public $execName; //执行人名称
	public $superName; //执行人名称
	public $confirmName; //执行人名称
	public $execAttr = array(); //执行人列表
	public $superAttr = array(); //监督人列表
	public $images = array(); //图片路径列表
	public $confirm; //确认人
	public $region;
	public $branch_id;
	public $search_community;
	public $staffId = array();
	private $_old_execute;
	private $_old_confirm;

	public $province_id;
	public $city_id;
	public $district_id;

	public $communityIds;

	public function rules()
	{
		$array = array(
			array('execute,execName,confirmName,superAttr,confirm,images,accept_content,region,branch_id,search_community,low,work_no', 'safe'),
			array('accept_content,suggestion,agree', 'required', 'on' => 'dispose,supervision,handle'),
			array('suggestion,agree', 'required', 'on' => 'quality_dispose'),
			array('category_id,customer_name,customer_tel, community_id,content', 'required', 'on' => '400create'),
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
			'execAttr' => '执行人',
			'superAttr' => '监督人',
			'execName' => '执行人',
			'superName' => '监督人',
			'confirmName' => '确认人',
			'images' => '图片',
			'confirm' => '确认人',
			'id' => '投诉工单',
			'type' => '类型',
			'category_id' => '类别',
			'user_id' => '投诉人ID',
			'customer_name' => '业主姓名',
			'customer_tel' => '业主电话',
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
			'region' => '地域',
			'branch_id' => '部门',
			'search_community' => '小区', //主要用于搜索功能
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
	 * 业主新建业主投诉
	 */
	public function createComplainByOwner()
	{
		if (empty($this) || empty($this->community_id)) { //如果未传参数，或小区为空，直接报错
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
		$this->type = 0; //投诉类型为业主投诉
		$this->model = 0; //所处模块为业主
		$this->state = 0; //初始状态
		//业主自己创单，最后处理信息应该为空
		$this->accept_employee_id = 0;
		$this->accept_time = 0;
		$this->accept_content = "";
		//调用公共方法
		if ($this->createComplain()) {


			//获得业主投诉的小区的小区主任集合
			$employeeList = PublicRepairs::model()->getEmployeeByCommunity($this->community_id, Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER);
			//如果只有一个小区主任
			if (count($employeeList) == 1) {
				//那么设置该小区主任为确认人
				$handModel = new ComplainRepairsHandling();
				$handModel->complain_repairs_id = $this->id;
				//获取集合第一个元素，使用函数避免下标非0导致得不到数据
				$handModel->employee_id = current($employeeList);
				$handModel->type = 2; //确认人
				$handModel->designate = 0; //指派人为系统
				$handModel->state = 0; //非当前执行人
				if ($handModel->save()) {
					return true;
				} else {
					return false;
				}
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param $complainAttr array 至少包括以下内容：category_id、community_id、content
	 * @return boolean
	 * 400新建业主投诉
	 */
	public function createComplainByService()
	{
		if (empty($this) || empty($this->community_id)) { //如果未传参数，或小区为空，直接报错
			throw new CHttpException(400, "400创建业主投诉失败!");
		}

		//如果查到手机号码一样的就插入用户ID
		$user = Customer::model()->find('mobile=:mobile', array(':mobile' => $this->customer_tel));
		$this->user_id = !empty($user) ? $user->id : 0; //系统创建

		$this->type = Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER; //投诉类型为业主投诉
		$this->model = 1; //所处模块为400
		$this->state = 0; //初始状态
		//400创单，最后处理信息应该为400的信息
		$this->accept_employee_id = Yii::app()->user->id;
		$this->accept_time = time();
		$this->accept_content = "400替业主创单";
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
			$logs = ComplainRepairsCustomerLog::createCustomerLog($this->id, "【 系统接受到业主" . $this->customer_name . "[{$this->customer_tel}]投诉 】",
				0, Item::COMPLAIN_REPAIRS_LOG_AWAITING_HANDL, $this->type);
			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 0, 0, "【 系统接受到业主" . $this->customer_name . "[{$this->customer_tel}]投诉 】 ");
			Yii::app()->sms->sendOwnerComplaintsMessage('complaintsSuccess', $this->id);//发送业主投诉 投诉成功短信模板通知业主
			if (!$return) {
				Yii::log("投诉{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.create');
			}
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
			if (Item::COMPLAIN_REPAIRS_CONFIRM_END == $this->state && Item::COMPLAIN_REPAIRS_CONFIRM_END != $model->state) {//确认完成 上一个状态不等于处理完成状态
				Yii::app()->sms->sendOwnerComplaintsMessage('processingIsComplete', $this->id);//业主投诉处理完成
			}

			return true;
		} else {
			return false;
		}
	}


	/**
	 * @return bool
	 * 更新投诉流程状态
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
				Yii::log("投诉{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.ownerComplain.close');
			}
			if (Item::COMPLAIN_REPAIRS_CONFIRM_END == $this->state && Item::COMPLAIN_REPAIRS_CONFIRM_END != $model->state) {//确认完成 上一个状态不等于处理完成状态
				Yii::app()->sms->sendOwnerComplaintsMessage('processingIsComplete', $this->id);//业主投诉处理完成
			}

			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return bool
	 * 更新监督流程
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
			$return = ComplainRepairsLog::createLog($this->id, $this->model, $this->state, 2, Yii::app()->user->id, "【 {$note} 】 " . $this->accept_content);
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
		if (!empty($this->execute))
			$this->is_back = 1;//拒绝重新指派

		if (!empty($this->execute) && ($this->_old_execute != $this->execute)) { //如果执行人不为空
			$this->is_back = 1;
			$this->staffId[] = $this->execute;
			if (!$this->updateByPk($this->id, array('execute' => $this->execute)))//更新执行人,如果非400操作则添加执行日志
				return false;
		}
		if (!empty($this->confirm) && ($this->_old_confirm != $this->confirm)) { //如果确认人不为空
			$this->staffId[] = $this->confirm;
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

			//400指派
			if (!$type) {
				$cdb = new CDbCriteria();
				array_push($this->staffId, $this->confirm);//确认人 与新增的执行人，监督人
				$this->staffId = array_unique($this->staffId);
				$cdb->addInCondition('id', $this->staffId);
				$employee = Employee::model()->findAll($cdb);
				$mobile = array_map(function (Employee $record) {
					return $record->mobile;
				}, $employee);//var_dump($this->staffId);

				if (!empty($mobile)) {
					Yii::app()->sms->sendOwnerComplaintsMessage('designate', $this->id, $mobile);
					//  $template = SmsTemplate::model()->findByAttributes(
					//     array('category' => 'individualRepair', 'code' => 'designate'));
					//       // var_dump($template);exit;
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
	public function exchange($type = null)
	{
		if ($this->state == Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE)
			throw new CHttpException(400, "此单已结束，不能够流转，请联系管理员");

		if (!empty($this->execute) && ($this->_old_execute != $this->execute)) { //如果执行人为空
			$this->staffId[] = $this->execute;
			if (!$this->updateByPk($this->id, array('execute' => $this->execute)) ||
				!ComplainRepairsHandling::createExecuted($this->id)
			)//更新执行人,如果非400操作则添加执行日志
				return false;

			$this->state = Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING;
			//调用更新状态的方法
			if (!$type) {
				$note = Employee::getEmployeeNames(Yii::app()->user->id) . '流转任务给' . Employee::getEmployeeNames($this->execute);
			} else {
				$note = Employee::getEmployeeNames(Yii::app()->user->id) . '拒绝确认任务，任务打回重新处理';
			}
			if ($this->updateComplainState(0, $note)) { //修改状态为处理中
				if (!$type) {//是执行人流转时才发短信
					Yii::app()->sms->sendOwnerComplaintsMessage('circulation', $this->id);
//                    $cdb = new CDbCriteria();
//                    array_push($this->staffId,$this->confirm);
//                    $cdb->addInCondition('id',$this->staffId);
//                    $employee = Employee::model()->findAll($cdb);
//                    $mobile = array_map(function(Employee $record){
//                        return $record->mobile;
//                    },$employee);
//                    if(!empty($mobile)){
//                        Yii::app()->sms->sendOwnerComplaintsMessage('circulation',$this->id,$mobile);
//                    }
				}
				return true;
			}

		}

		return false;
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
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER);

		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭
		$criteria->compare('low', $this->low);//是否低分投诉
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
//        if ($this->branch_id != '')
//            $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
//        else if (!empty($this->communityIds)) //如果有小区
//            $community_ids = $this->communityIds;
//        else if ($this->region != '') //如果有地区
//            $community_ids = Region::model()->getRegionCommunity($this->region, 'id');

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

	/*
	 * 报表搜索方法
	 * */
	public function report_search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
		if (isset($_GET['OwnerComplain']) && !empty($_GET['OwnerComplain'])) {
			$_SESSION['OwnerComplain'] = array();
			$_SESSION['OwnerComplain'] = $_GET['OwnerComplain'];
		}
		if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
			if (isset($_SESSION['OwnerComplain']) && !empty($_SESSION['OwnerComplain'])) {
				foreach ($_SESSION['OwnerComplain'] as $key => $val) {
					if ($val != "") {
						$this->$key = $val;
					}
				}
			}
		}
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER);

		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭
		$criteria->compare('low', $this->low);//是否低分投诉
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
		//$criteria->limit=30;
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}


	public function searchByQuality()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER);
		//$criteria->compare('state', Item::COMPLAIN_REPAIRS_APPLY_COLOSE); //质检中心只查看申请关闭的投诉
		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->addCondition("t.state <>" . Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);
		$criteria->addCondition("t.state <>" . Item::COMPLAIN_REPAIRS_ABNORMAL_COLOSE);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭
		$criteria->compare('low', $this->low);//是否低分投诉
		$criteria->compare('work_no', $this->work_no);
		if ($this->username != '') {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.username', $this->username, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "< " . strtotime($this->endTime) . " 23:59:59");
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

	public function report_searchByQuality()
	{
		$criteria = new CDbCriteria;
		if (isset($_GET['OwnerComplain']) && !empty($_GET['OwnerComplain'])) {
			$_SESSION['OwnerComplain'] = array();
			$_SESSION['OwnerComplain'] = $_GET['OwnerComplain'];
		}
		if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
			if (isset($_SESSION['OwnerComplain']) && !empty($_SESSION['OwnerComplain'])) {
				foreach ($_SESSION['OwnerComplain'] as $key => $val) {
					if ($val != "") {
						$this->$key = $val;
					}
				}
			}
		}
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER);
		//$criteria->compare('state', Item::COMPLAIN_REPAIRS_APPLY_COLOSE); //质检中心只查看申请关闭的投诉
		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->addCondition("t.state <>" . Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE);
		$criteria->addCondition("t.state <>" . Item::COMPLAIN_REPAIRS_ABNORMAL_COLOSE);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭
		$criteria->compare('low', $this->low);//是否低分投诉
		$criteria->compare('work_no', $this->work_no);

		if ($this->username != '') {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.username', $this->username, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "< " . strtotime($this->endTime) . " 23:59:59");
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

	//我待处理的事项
	public function executeSearch()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER);

		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('apply_close', $this->apply_close);//是否申请关闭
		$criteria->compare('suggest_colse', $this->suggest_colse);//是否建议关闭

		$criteria->addCondition("((`t`.state = " . Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING .
			" and `t`.execute =" . Yii::app()->user->id . ")
         or ( `t`.state = " . Item::COMPLAIN_REPAIRS_HANDLE_END . " and `t`.confirm = " . Yii::app()->user->id . "))");

		$criteria->addCondition('`t`.state <> ' . Item::COMPLAIN_REPAIRS_CONFIRM_END . ' and `t`.state <> ' . Item:: COMPLAIN_REPAIRS_SUCCESS_COLOSE);
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

	public function mySearch($type = 0)
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
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER);

		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare("`t`.`execute`", $this->execute);
		$criteria->compare('`t`.`confirm`', $this->confirm);
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

	//当公业主投诉有多位监督人，将写入complain_repairs_handling记录，只指定监督人
	public function updateMoreSuperviseHandling($ids = array())
	{
		if (empty($this->id)) {
			throw new CHttpException(400, "指定失败,未知的业主投诉信息!");
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
			$note = '业主投诉获得多个第一次自动监督职位的员工' . $this->getEmployees($ids);
			ComplainRepairsLog::createLog($this->id, '', 0, 1, 0, "【 {$note} 】");

			return true;
		}
	}

	public function afterFind()
	{
		$this->_old_execute = $this->execute;
		$this->_old_confirm = $this->confirm;
		//$this->execName = $this->getExecName();
		//$this->confirmName = $this->getConfirmName();
		return parent::afterFind();
	}

	public function getApplyClose()
	{
		return empty($this->apply_close) ? "未申请关闭" : "已申请关闭";
	}

	public function ICEGetLinkageRegionDefaultValue()
	{
		$updateDefaults = $this->ICEGetLinkageRegionDefaultValueForUpdate();
		return $updateDefaults
			? $updateDefaults
			: $this->ICEGetLinkageRegionDefaultValueForSearch();
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

}
