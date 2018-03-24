<?php

/**
 * This is the model class for table "personal_repairs_info".
 *
 * The followings are the available columns in table 'personal_repairs_info':
 * @property integer $id
 * @property integer $category_id
 * @property string $category_name
 * @property integer $customer_id
 * @property string $customer_name
 * @property string $customer_tel
 * @property integer $community_id
 * @property integer $shop_id
 * @property string $content
 * @property integer $create_time
 * @property string $accept_object
 * @property integer $accept_employee_id
 * @property integer $accept_time
 * @property string $accept_content
 * @property integer $sorce
 * @property string $sorce_note
 * @property integer $sorce_time
 * @property integer $state
 * @property integer $is_deleted
 * @property integer $shop_state
 */
class PersonalRepairsInfo extends CActiveRecord
{
	/**
	 * @var string 模型名
	 */
	public $modelName = '个人报修';
	public $images = array(); //图片路径列表

	public $username;
	public $startTime;
	public $endTime;
	//以下字段仅供搜索用
	public $communityIds = array(); //小区
	public $branch_id;
	public $region;
	public $execName; //执行人名称
	public $superName; //执行人名称
	public $confirmName; //执行人名称
	public $execAttr = array(); //执行人列表
	public $superAttr = array(); //监督人列表
	public $handingModel = 'shop';
	public $staffId = array();

	private $_old_execute;
	private $_old_confirm;
	public $oldState;
	public $oldShopState;

	public $community_id;

	public $province_id;
	public $city_id;
	public $district_id;

	static $_state_list = array(
		Item::PERSONAL_REPAIRS_AWAITING_HANDLE => "待处理", //个人报修业主下单后的状态
		Item::PERSONAL_REPAIRS_RECEIVE_HANDLING => "已接收", //(商家)已接收
		Item::PERSONAL_REPAIRS_HANDLE_END => "处理完成", //（商家）处理完成
		Item:: PERSONAL_REPAIRS_ASSIST_HANDLE => "协助处理", //（商家4小时未处理）,小区主任/400协助处理
		Item::PERSONAL_REPAIRS_CONFIRM_END => '已确认',
		Item:: PERSONAL_REPAIRS_SUCCESS_COLOSE => "(业主评分后),已结束", //(业主评分后),结束
		Item:: PERSONAL_REPAIRS_ABNORMAL_COLOSE => "(拒绝),已结束", //(商家/小区主任/400)拒绝,结束
		Item::PERSONAL_REPAIRS_SHOP_ACCEPTED => '(商家)已接收',
		Item::PERSONAL_REPAIRS_SHOP_HAS_SERVED => '(商家)已服务',
		Item::PERSONAL_REPAIRS_SHOP_REFUSAL => '(商家)已拒绝'
	);

	static $_state_staff_list = array(
		Item::PERSONAL_REPAIRS_AWAITING_HANDLE => "待处理", //个人报修业主下单后的状态
		Item::PERSONAL_REPAIRS_RECEIVE_HANDLING => "处理中", //(商家)已接收
		Item::PERSONAL_REPAIRS_HANDLE_END => "处理完成",
		Item::PERSONAL_REPAIRS_CONFIRM_END => '已确认',
		Item:: PERSONAL_REPAIRS_ABNORMAL_COLOSE => "已结束", //(商家/小区主任/400)拒绝,结束
	);

	static $_shop_state_list = array(
		Item::PERSONAL_REPAIRS_SHOP_ACCEPTED => '(商家)已接收',
		Item::PERSONAL_REPAIRS_SHOP_HAS_SERVED => '(商家）已服务',
		Item::PERSONAL_REPAIRS_SHOP_REFUSAL => '(商家)已拒绝',
		Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE => '(商家)结束',
	);

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PersonalRepairsInfo the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function afterSave()
	{
		if ($this->getIsNewRecord()) {//新建投诉报修时赠送积分
			$key = 'personal_repairs';
			Customer::model()->changeCredit($key);
		}
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'personal_repairs_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('execute,execName,confirmName,superAttr,confirm,images,accept_content,accept_employee_id', 'safe'),
			array('category_id,customer_name,customer_id,customer_tel,category_name, community_id,shop_id,content', 'required', 'on' => 'create'),
			//array('accept_employee_id, accept_time,state,accept_content', 'required', 'on' => 'update'),
			array('execute', 'checkExec', 'on' => '400update'),
			array('confirm', 'checkConfirm', 'on' => '400update'),
			array('execute,accept_content', 'safe', 'on' => 'redo,refuse'),

			array('category_id, customer_id, community_id, shop_id, create_time, accept_employee_id, accept_time, sorce, sorce_time, state, is_deleted', 'numerical', 'integerOnly' => true),
			array('category_name, customer_name, customer_tel, accept_object', 'length', 'max' => 255),
			array('communityIds,shop_state,region,branch_id,username,id, category_id, startTime,endTime,category_name, customer_id, customer_name, customer_tel, community_id, shop_id, content, create_time, accept_object, accept_employee_id, accept_time, accept_content, sorce, sorce_note, sorce_time, state, is_deleted', 'safe', 'on' => 'search'),
			//			ICE 搜索数据
			array('province_id,city_id,district_id', 'safe'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
			'localShop' => array(self::BELONGS_TO, 'Shop', 'shop_id', 'condition' => 'localShop.type=' . Shop::TYPE_LOCAL),
			'cate' => array(self::BELONGS_TO, 'PersonalRepairsCategory', 'category_id'),
			'accept_employee' => array(self::BELONGS_TO, 'Employee', 'accept_employee_id'),
			'execute_employee' => array(self::BELONGS_TO, 'Employee', 'execute'),
			'accept_customer' => array(self::BELONGS_TO, 'Customer', 'accept_employee_id'),
			'accept_shop' => array(self::BELONGS_TO, 'Shop', 'accept_employee_id'),
			'picture' => array(self::HAS_MANY, 'Picture', 'object_id', 'condition' => "picture.model ='PersonalRepairsInfo'"),
			'logs' => array(self::HAS_MANY, 'PersonalRepairsLog', 'personal_repairs_id'),
			'superAttred' => array(self::HAS_MANY, 'PersonalRepairsHandling', 'personal_repairs_id', 'condition' => "superAttred.type ='1'"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '报修工单',
			'execAttr' => '执行人',
			'superAttr' => '监督人',
			'execName' => '执行人',
			'superName' => '监督人',
			'confirmName' => '确认人',
			'category_id' => '个人报修分类',
			'category_name' => '个人报修分类名称',
			'customer_id' => '报修业主',
			'customer_name' => '业主姓名',
			'customer_tel' => '业主电话',
			'community_id' => '报修小区',
			'shop_id' => '商家',
			'content' => '报修内容',
			'create_time' => '报修时间',
			'accept_object' => 'Accept Object',
			'accept_employee_id' => '最终处理人',
			'accept_time' => '最终处理时间',
			'accept_content' => '最终处理方案',
			'sorce' => '评分',
			'sorce_note' => '评价内容',
			'sorce_time' => '评价时间',
			'state' => '状态',
			'is_deleted' => 'Is Deleted',
			'username' => '用户名',
			'startTime' => '开始时间',
			'endTime' => '结束时间',
			'region' => '地区',
			'branch_id' => '部门',
			'communityIds' => '小区',
			'shopName' => '商家',
			'shopStateName' => '商家状态',
			'shop_state' => '商家状态',
			'apply_close' => '申请闭关',
		);
	}

	/**
	 * 判断是否可以回访
	 */
	public function getOpenStatus()
	{
		if ($this->state != Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE && $this->state != Item::PERSONAL_REPAIRS_SUCCESS_COLOSE) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 判断是否结束
	 */
	public function getCloseState()
	{
		if ($this->state == Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE || $this->state == Item::PERSONAL_REPAIRS_SUCCESS_COLOSE) {
			return true;
		} else {
			return false;
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


	/**
	 * @param $execAttr : array 执行人
	 * @param $superAttr  array 监督人
	 * @param $confirm  int 确认人ID
	 * @return bool
	 * 指派
	 */
	public function appoint($type = null)
	{
		if ($this->state == Item::PERSONAL_REPAIRS_SUCCESS_COLOSE)
			throw new CHttpException(400, "此单已结束，不能够指派，请联系管理员");

		$user_id = Yii::app()->user->id;
		$connection = Yii::app()->db;
		$sql = "INSERT INTO personal_repairs_handling(personal_repairs_id,employee_id,type) value ";
		$strSql = "";
		$existExec = array();
		if (!empty($this->execute) && ($this->_old_execute != $this->execute)) { //如果执行人为空
			$this->is_back = 1;
			$this->staffId[] = $this->execute;
			if (!$this->updateByPk($this->id, array('execute' => $this->execute)))//更新执行人,如果非400操作则添加执行日志
				return false;
		}
		if (!empty($this->confirm) && ($this->_old_confirm != $this->confirm)) { //如果确认人为空
			if (!$this->updateByPk($this->id, array('confirm' => $this->confirm)))//更新确认人,如果非400操作则添加执行日志
				return false;
		}
		//获得已有监督人ID
		$eSuper = PersonalRepairsHandling::model()->findAllByAttributes(array('personal_repairs_id' => $this->id, 'type' => 1));
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
			$this->state = Item::PERSONAL_REPAIRS_RECEIVE_HANDLING;
			//调用更新状态的方法
			$execute = Employee::getEmployeeNames(empty($this->execute) ? $this->_old_execute : $this->execute);//执行者 有且只有一位
			$supper = Employee::getEmployeeNames(array_merge($this->superAttr, $existSuper));//监督者
			$supper = !empty($supper) ? implode('，', $supper) : '';

			$confirm = Employee::getEmployeeNames(empty($this->confirm) ? $this->_old_confirm : $this->confirm);//确认者
			if (!$type) {
				//400指派任务
				$note = sprintf('信息中心%s指派任务给%s，指定监督人为%s，指定最后确认人为%s', Employee::getEmployeeNames($user_id), $execute, is_array($supper) ? implode('，', $supper) : $supper, $confirm);
			} else {
				//确认者拒绝
				$note = Employee::getEmployeeNames(Yii::app()->user->id) . '拒绝确认任务，任务打回重新处理';
			}
			if (!$this->updateAdminPersonalRepairs($this->is_back, $note))
				return false;

			if (!$type) {
				########400指派时分送的短信########
				$cdb = new CDbCriteria();
				array_push($this->staffId, $this->confirm);
				$this->staffId = array_unique($this->staffId);
				$cdb->addInCondition('id', $this->staffId);
				$employee = Employee::model()->findAll($cdb);
				$mobile = array_map(function (Employee $record) {
					return $record->mobile;
				}, $employee);
				if (!empty($mobile)) {
					Yii::app()->sms->sendIndividualRepairMessage('designate', $this->id, $mobile);
					//添加推送
					//PushInformation::createSNSInformation('title','note',  PushInformation::IS_TYPE_SHOP,array());
				}
			}
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
			if ($this->updateAdminPersonalRepairs(0, $note)) { //修改状态为处理中
				if (!$type) {//为执行人时发送短信  true为确认人拒绝
					Yii::app()->sms->sendOwnerComplaintsMessage('circulation', $this->id);
//                    $cdb = new CDbCriteria();
//                    array_push($this->staffId,$this->confirm);
//                    $cdb->addInCondition('id',$this->staffId);
//                    $employee = Employee::model()->findAll($cdb);
//                    $mobile = array_map(function(Employee $record){
//                        return $record->mobile;
//                    },$employee);
//                    if(!empty($mobile)){
//                        Yii::app()->sms->sendOwnerComplaintsMessage('circulation',$this->id,$mobile,$title="个人报修");
//                    }
				}
				return true;
			}
		}

		return false;
	}

	/**
	 * 400查询
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.shop_id', $this->shop_id);

		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.category_name', $this->category_name, true);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.sorce', $this->sorce, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('`t`.shop_state', $this->shop_state);

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

	/**
	 * 我需要执行的
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function searchByExec()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.shop_id', $this->shop_id);

		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.category_name', $this->category_name, true);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.sorce', $this->sorce, true);
		$criteria->compare('`t`.state', $this->state);

		$criteria->addCondition("(`t`.state = " . Item::PERSONAL_REPAIRS_RECEIVE_HANDLING . " and `t`.execute =" . Yii::app()->user->id . ")
         or ( `t`.state = " . Item::PERSONAL_REPAIRS_HANDLE_END .
			" and `t`.confirm = " . Yii::app()->user->id . ")");

		$criteria->addCondition(" ( `t`.state <> " . Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE . " or `t`.state <> " . Item::PERSONAL_REPAIRS_SUCCESS_COLOSE . ")");

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

	public function searchByShop($shop_id)
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('category_id', $this->category_id);
		$criteria->compare('category_name', $this->category_name, true);
		$criteria->compare('customer_id', $this->customer_id);
		$criteria->compare('customer_name', $this->customer_name, true);
		$criteria->compare('customer_tel', $this->customer_tel, true);
		$criteria->compare('community_id', $this->community_id);
		$criteria->compare('shop_id', $shop_id);
		$criteria->compare('content', $this->content, true);
		$criteria->compare('create_time', $this->create_time);
		$criteria->compare('accept_object', $this->accept_object, true);
		$criteria->compare('accept_employee_id', $this->accept_employee_id);
		$criteria->compare('accept_time', $this->accept_time);
		$criteria->compare('accept_content', $this->accept_content, true);
		$criteria->compare('sorce', $this->sorce);
		$criteria->compare('sorce_note', $this->sorce_note, true);
		$criteria->compare('sorce_time', $this->sorce_time)->compare('shop_state', $this->shop_state);
		$criteria->compare('state', $this->state);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id desc',
			)
		));
	}

	/**
	 * 监督过的，监督的，执行过的
	 * @param number $type
	 * @return CActiveDataProvider
	 */
	public function searchExec($type = 0)
	{
		$criteria = new CDbCriteria;
		//按类型得到处理人列表
		$handling = PersonalRepairsHandling::model()->findAllByAttributes(array('type' => $type, 'employee_id' => Yii::app()->user->id));
		$idList = array();
		foreach ($handling as $hand) {
			//获得该用户的所有投诉ID
			array_push($idList, $hand->personal_repairs_id);
		}
		$criteria->addInCondition('`t`.id', $idList);
		$criteria->compare('`t`.id', $this->id);

		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		//$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare("`t`.`execute`", $this->execute);
		$criteria->compare('`t`.`confirm`', $this->confirm);
		if ($type == Item::COMPLAIN_REPAIRS_HANDLING_SUPERVISOR) {
			$criteria->addCondition("`t`.state <> " . Item::PERSONAL_REPAIRS_SUCCESS_COLOSE . " and `t`.state <> " . Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE);
		}
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

	public function behaviors()
	{
		return array(

			'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => null,
				'setUpdateOnCreate' => true,
			),
			'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
			'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
		);
	}

	//get姓名
	public function getCustomerName()
	{
		if ($this->customer_name != '') {
			$username = empty($this->customer) ? '' : '(' . $this->customer->username . ')';
			return $this->customer_name . $username;
		} else {
			return '';
		}

	}

	public function getStateNames()
	{
		return CMap::mergeArray(array('' => '全部'), self::$_state_staff_list);
	}

	public function getShopStateNames()
	{
		return CMap::mergeArray(array('' => '全部'), self::$_shop_state_list);
	}

	public function getStateList()
	{
		return CMap::mergeArray(array('' => '全部'), self::$_state_list);
	}

	public function getCpStateList()
	{
		$state = array(
			Item::PERSONAL_REPAIRS_AWAITING_HANDLE => "待处理", //个人报修业主下单后的状态
			Item:: PERSONAL_REPAIRS_ABNORMAL_COLOSE => "(拒绝),已结束", //(商家/小区主任/400)拒绝,结束
			Item::PERSONAL_REPAIRS_SHOP_ACCEPTED => '(商家)已接收',
			Item::PERSONAL_REPAIRS_SHOP_HAS_SERVED => '(商家)已服务',
			Item::PERSONAL_REPAIRS_SHOP_REFUSAL => '(商家)已拒绝'
		);
		return CMap::mergeArray(array('' => '全部'), $state);
	}

	public function getStatusName($html = true)
	{
		$return = '';
		$return .= ($html) ? '<span class="label label-success">' : '';
		$return .= empty(self::$_state_list[$this->state]) ? "" : self::$_state_list[$this->state];
		$return .= ($html) ? '</span>' : '';
		return $return;
	}

	public function getCategoryName()
	{
		return empty($this->category_name) ? (empty($this->cate) ? '' : $this->cate->name) : $this->category_name;
	}
//      ICE接入
	public function getCommunityName()
	{
//		return empty($this->community) ? '' : $this->community->name;
//      ICE接入
		if(!empty($this->community_id)){
			$community = ICECommunity::model()->findByPk($this->community_id);
			if(!empty($community)){
				return  $community['name'];
			}
		}

		return '';
	}

	public function getSource()
	{
		return empty($this->source) ? 0 : $this->source;
	}

	public function getAcceptEmployeeName()
	{
		if ($this->accept_object != 'shop' && $this->accept_object != 'customer')
			return empty($this->accept_employee) ? '' : $this->accept_employee->name;
		else if ($this->accept_object == 'customer')
			return empty($this->accept_customer) ? '' : $this->accept_customer->name;
		else
			return empty($this->accept_shop) ? '' : $this->accept_shop->name;
	}

	/**
	 * 业主创建报修
	 * @return bool
	 * @throws CHttpException
	 */
	public function createPersonalRepairs()
	{
		if (empty($this) || empty($this->community_id) || empty($this->shop_id) || empty($this->category_id)) { //如果未传参数，或小区为空，直接报错
			throw new CHttpException(400, "创建个人报修失败!");
		}
		if (empty($this->customer_id)) { //如果投诉人ID为空或为0.
			throw new CHttpException(400, "创建个人报修失败!报修人不能为空！");
		}
		$customer = Customer::model()->findByPk($this->customer_id);
		if (empty($customer)) {
			throw new CHttpException(400, "创建个人报修失败!报修人不能为空！");
		}
		$this->category_name = !empty($this->category_name) ? $this->category_name : '';
		$this->customer_name = empty($customer->name) ? $customer->username : $customer->name;
		$this->customer_tel = empty($customer->customer_tel) ? $customer->mobile : $customer->customer_tel;
		$this->state = Item::PERSONAL_REPAIRS_AWAITING_HANDLE; //初始状态
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
					Yii::log("个人报修{$this->id}更新图片失败！", CLogger::LEVEL_INFO, 'colourlife.backend.PersonalRepairsInfo.create');
				}
			}

			//前台日志（）
			$logs = ComplainRepairsCustomerLog::createCustomerLog($this->id, '【 系统接受到业主报修 】 ',
				0, Item::COMPLAIN_REPAIRS_LOG_HANDLING, Item::COMPLAIN_REPAIRS_TYPE_PERSON
			);

			//插入报修人
			$insertHandling = PersonalRepairsHandling::createHandling($this->id, 'shop', $this->shop_id);
			//写日志,如果失败写写系统日志\
			//$insertLog = PersonalRepairsLog::createLog($this->id, 'customer', $this->state, $this->customer_id, '业主发起个人报修');
			//$insertLog = PersonalRepairsLog::createLog($this->id, 'shop', $this->state, $this->customer_id, '业主发起个人报修');//商家后台的个人报修日志 字段handling_object使用shop区别
			$insertLog = PersonalRepairsLog::createLog($this->id, 'Customer', $this->state, 0, '【 系统接受到业主报修 】 ', $this->shop_state);//商家后台的个人报修日志 字段handling_object使用shop区别

			Yii::app()->sms->sendIndividualRepairMessage('submitSuccessCustomer', $this->id);//个人报修 报修成功通知业主
			if ($shopMobile = Shop::model()->findByPk($this->shop_id)) {
				Yii::app()->sms->sendIndividualRepairMessage('submitSuccessSeller', $this->id, $shopMobile->mobile);//个人报修 报修成功通知商家
			}

			if ($insertHandling && $insertLog)
				return true;
			else {
				Yii::log("个人报修{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.PersonalRepairs.create');
				return false;
			}

		}
	}

	/**
	 * 商家修改报修
	 * @return bool
	 * @throws CHttpException
	 */
	public function updateShopPersonalRepairs($type = null)
	{
		$this->accept_object = 'shop';
		$this->accept_employee_id = empty($this->accept_employee_id) ? 0 : $this->accept_employee_id;
		if (empty($this) || empty($this->shop_state)) { //如果未传参数，或小区为空，直接报错
			throw new CHttpException(400, "更新业主报修失败!");
		}

		if ($this->save()) {
			//写日志,如果失败写写系统日志
			switch ($type) {
				case -1:
					$note = '投诉超时未处理，自动添加监督人如下：' . Employee::getEmployeeNames($this->accept_employee_id);
					break;
				case Item::PERSONAL_REPAIRS_SHOP_HAS_SERVED:
					Yii::app()->sms->sendIndividualRepairMessage('processingFinishCustomer', $this->id);//个人报修 发送商家处理完成模板短信
					$note = sprintf('商家%s已经处理完成了任务', $this->getShopName());
					break;
				case Item::PERSONAL_REPAIRS_SHOP_REFUSAL:
					Yii::app()->sms->sendIndividualRepairMessage('sellerRefusalCustomer', $this->id);//个人报修 发送商家拒绝模板短信
					$note = sprintf('商家%s拒绝接受任务', $this->getShopName());
					break;
				default://商家接收
					Yii::app()->sms->sendIndividualRepairMessage('sellerAcceptCustomer', $this->id);//个人报修 发送商家接收模板短信
					$note = '商家操作';
					break;
			}

			//前台日志
			if (($this->oldState != $this->state) || ($this->shop_state != $this->oldShopState)) {
				ComplainRepairsCustomerLog::createShopLog($this->id, $note,
					$this->getUserState(), Item::COMPLAIN_REPAIRS_TYPE_PERSON);
			}

			if (PersonalRepairsLog::createLog($this->id, $this->accept_object, 0, $this->accept_employee_id, "【 {$note} 】", $this->shop_state))
				return true;
			else {
				Yii::log("个人报修{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.PersonalRepairs.update');
				return false;
			}
		}

	}

	/**
	 * 员工修改报修
	 * @return bool
	 * @throws CHttpException
	 */
	public function updateEmployeePersonalRepairs($is_back = 0, $content = null)
	{
		$this->accept_object = 'Employee';
		$this->accept_employee_id = Yii::app()->user->id;
		$this->is_back = $is_back;
		$this->accept_content = (!empty($content) ? '【 ' . $content . ' 】 ' : '') . $this->accept_content;
		if (empty($this) || empty($this->state) || empty($this->accept_content)) { //如果未传参数，或小区为空，直接报错
			throw new CHttpException(400, "更新业主报修失败!");
		}
		$model = self::model()->findByPk($this->id);//保存前记录状态
		if ($this->save()) {

			//前台日志
			if (($this->oldState != $this->state) || ($this->shop_state != $this->oldShopState)) {
				ComplainRepairsCustomerLog::createEmployeeLog($this->id, $content,
					$this->getUserState(), Item::COMPLAIN_REPAIRS_TYPE_PERSON);
			}

			//写日志,如果失败写写系统日志
			if (PersonalRepairsLog::createLog($this->id, $this->accept_object, $this->state, $this->accept_employee_id, $this->accept_content, $this->shop_state)) {
				if (Item::PERSONAL_REPAIRS_CONFIRM_END == $this->state && Item::PERSONAL_REPAIRS_CONFIRM_END != $model->state) {//确认人确认时  上一个状态不等于处理完成状态
					Yii::app()->sms->sendStaffComplaintsMessage('processingIsComplete', $this->id);//员工投诉 发送处理完成短信
				}
				return true;
			} else {
				Yii::log("个人报修{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.PersonalRepairs.update');
				return false;
			}
		}

	}

	/**
	 * 400修改报修
	 * @return bool
	 * @throws CHttpException
	 */
	public function updateAdminPersonalRepairs($is_back = 0, $content = null)
	{
		$this->accept_object = '400';
		$this->accept_employee_id = Yii::app()->user->id;
		$this->is_back = $is_back;
		$this->accept_content = (!empty($content) ? '【 ' . $content . ' 】' : '') . $this->accept_content;
		if (empty($this) || empty($this->state) || empty($this->accept_content)) { //如果未传参数，或小区为空，直接报错
			throw new CHttpException(400, "更新业主报修失败!");
		}

		if ($this->save()) {

			//前台日志
			if (($this->oldState != $this->state) || ($this->shop_state != $this->oldShopState)) {
				ComplainRepairsCustomerLog::createEmployeeLog($this->id, $content,
					$this->getUserState(), Item::COMPLAIN_REPAIRS_TYPE_PERSON);
			}

			//写日志,如果失败写写系统日志
			if (PersonalRepairsLog::createLog($this->id, $this->accept_object, $this->state, $this->accept_employee_id, $this->accept_content, $this->shop_state))
				return true;
			else {
				Yii::log("个人报修{$this->id}写日志失败！", CLogger::LEVEL_INFO, 'colourlife.backend.PersonalRepairs.update');
				return false;
			}
		}

	}

	//获取查看页面的片，返回数组
	public function getPic()
	{
		$pics = array();
		if (!empty($this->picture))
			$pics = array_map(function ($pic) {
				return $pic->portraitUrl;
			}, $this->picture);
		return $pics;
	}

	//获得投诉人所在的地址，如果是员工投诉则获得员工所属部门
	public function getReportUserAddress()
	{
		$user_id = $this->customer_id;
		if (empty($user_id)) {
			return "获取用户信息失败！400创单？";
		}
		$customer = Customer::model()->findByPk($user_id);
		if (empty($customer)) {
			return "获取业主信息失败！";
		}
		$community_id = $customer->community_id;
		$community = Community::model()->findByPk($community_id);
		if (empty($community_id) || empty($community)) {
			return "获取业主所在小区信息失败！";
		}
		$addressList = $community->getMyParentRegionNames();
		$address = implode("-", $addressList) . "-" . $community->name;
		$address .= "-" . (empty($customer->build) ? "" : $customer->build->name);
		$address .= "-" . $customer->room;
		return $address;
	}

	//获得投诉小区所属的部门
	public function getCommunityInBranch()
	{
		$community_id = $this->community_id;
		if (empty($community_id)) {
			return "获取小区信息失败！";
		}
		$community = Community::model()->findByPk($community_id);
		if (empty($community)) {
			return "获取小区所属部门失败！";
		}
		return Branch::getMyParentBranchName($community->branch_id);
	}

	//将个人报修的状态重定义为1，2，3，4，5
	public function getFrontStart()
	{
		if ($this->state == Item::PERSONAL_REPAIRS_AWAITING_HANDLE) {
			return Item::COMPLAIN_REPARS_START;//
		} else if ($this->state == Item::PERSONAL_REPAIRS_HANDLE_END) {
			return Item::COMPLAIN_REPARS_EVALUATION;
		} else if ($this->state == Item::PERSONAL_REPAIRS_SUCCESS_COLOSE) {
			return Item::CONPLAIN_REPARS_COMPLETE;
		} else if ($this->state == Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE) {
			return Item::COMPLAIN_REPAIRS_REFUSE;
		} else {
			return Item::COMPLAIN_REPARS_ING;
		}
	}

	public function getSorce()
	{
		return array(
			'0' => '不满意',
			'1' => '满意',

		);
	}

	public function getShopName()
	{
		$shop = Shop::model()->findByPk($this->shop_id);
		return (empty($shop) ? "" : $shop->name);
	}

	public function getShopModel()
	{
		$shop = Shop::model()->findByPk($this->shop_id);
		return (empty($shop) ? "" : $shop->mobile);
	}

	public function getShopState()
	{
		$shop = Shop::model()->findByPk($this->shop_id);
		return (!empty($shop->state) && $shop->state == 1) ? "禁用" : "启用";
	}

	//获取执行人
	public function getExecName()
	{
		$employee = Employee::model()->findByPk($this->execute);
		return empty($employee) ? '' : $employee->name;
	}

	//获取确认人
	public function getConfirmName()
	{
		$employee = Employee::model()->findByPk($this->confirm);
		return empty($employee) ? '' : $employee->name;
	}

	public function getSorceName($html = false)
	{
		// $temp = $this->sorce == 1?'满意':'不满意';
		$temp = ($this->sorce == 1) ? '满意' : (($this->state == Item::PERSONAL_REPAIRS_AWAITING_HANDLE || $this->state == Item::PERSONAL_REPAIRS_RECEIVE_HANDLING) ? '未评论' : '不满意');
		if ($html) {
			return sprintf('<span class="label label-success">%s</span>', $temp);
		}
		return $temp;
	}

	public function getNotSuperAttrEmployee()
	{
		$criteria = new CDbCriteria;
		if (!empty($this->superAttred))
			$criteria->addNotInCondition('id', array_map(function ($hand) {
				return $hand->employee_id;
			}, $this->superAttred));

		return CHtml::listData(Employee::model()->findAll($criteria), 'id', 'name');
	}


	public function getExecAttrEmployee()
	{
		$str = '';
		$criteria = new CDbCriteria;
		if (!empty($this->execAttred)) {
			$criteria->addInCondition('id', array_map(function ($hand) {
				return $hand->employee_id;
			}, $this->execAttred));

			$data = Employee::model()->findAll($criteria);
			if (count($data) > 0) {
				foreach ($data as $employee) {
					$str .= $employee->name . ' , ';
				}
			}
		}

		return $str;
	}

	public function getSuperAttrEmployee()
	{
		$str = '';
		$criteria = new CDbCriteria;
		if (!empty($this->superAttred)) {
			$criteria->addInCondition('id', array_map(function ($hand) {
				return $hand->employee_id;
			}, $this->superAttred));

			$data = Employee::model()->findAll($criteria);
			if (count($data) > 0) {
				foreach ($data as $employee) {
					$str .= $employee->name . ' , ';
				}
			}
		}

		return $str;
	}

	//检测我是否是确认人
	public function checkIsConfirm()
	{
		if ($this->confirm == Yii::app()->user->id) {
			return true;
		} else {
			return false;
		}
	}

	//检测我是否能处理
	public function checkICanHandle()
	{
		if ($this->checkIsReceive(true)) {
			if ($this->state == Item::PERSONAL_REPAIRS_RECEIVE_HANDLING) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	/**
	 * @param $isNow :是否是当前执行人,default 0
	 * @return bool
	 * @throws CHttpException
	 * 检测当前用户是否是执行人,如果isNow=true,判断用户是否是当前执行人
	 */
	public function checkIsReceive($isNow = false)
	{
		if ($this->execute == Yii::app()->user->id)
			return true;

		return false;
	}

	//检测我是否能处理
	public function checkICanRedo()
	{
		//是执行人。且无当前执行人或当前执行人等于自己
		if ($this->checkIsReceive() && !$this->getCloseState()) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @return int/null
	 * 或得投诉的当前执行人。没有则返回null
	 */
	public function getNowExecutorsID()
	{
		if (empty($this->execAttred)) {
			return null;
		} else {
			$employee_id = null;
			foreach ($this->execAttred as $handing) {
				if ($handing->type == 0) {
					$employee_id = $handing->employee_id;
					break;
				}
			}
			return $employee_id;
		}
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


	/**
	 * @return bool
	 * @throws CHttpException
	 * 检测当前用户是否是监督人
	 */
	public function checkIsSuper()
	{
		$attr = array(
			'personal_repairs_id' => $this->id,
			'type' => 1, //监督人
		);
		//查询该投诉的所有监督人
		$execList = PersonalRepairsHandling::model()->findAllByAttributes($attr);
		if (empty($execList)) {
			//throw new CHttpException(403, '没有权限！');
			return false;
		}
		$idList = array();
		$user_id = Yii::app()->user->id;
		foreach ($execList as $exec) {
			array_push($idList, $exec->employee_id);
		}
		if (in_array($user_id, $idList)) {
			return true;
		} else {
			return false;
		}
	}

	public function getShopStatusName($html = true)
	{
		$return = '';
		$return .= ($html) ? '<span class="label label-success">' : '';
		$return .= self::$_state_list[$this->shop_state];
		$return .= ($html) ? '</span>' : '';
		return $return;
	}

	//获取片区总监
	public function getAreaDirector($community_id)
	{
		//获得片区总监职位ID
		$config = Yii::app()->config->personalRepairsConfig;
		$automaticSupervisoryPositions = @$config['automaticSupervisoryPositions'];

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

		$eprList = EmployeePositionRelation::model()->findAllByAttributes(array('position_id' => $automaticSupervisoryPositions));
		//获得职位下的所有员工
		$eprEmployeeList = array_map(function ($ebr) {
			return $ebr->employee_id;
		}, $eprList);
		//返回两个员工数组的交集
		return array_unique(array_intersect($ebrEmployeeList, $eprEmployeeList));
	}

	//添加成为监督人 $type为false为商家拒绝 true为自动处理
	public function addSuperintendent($type = false)
	{
		$emplayees = array();
		//获取小区主任
		$emplayees = $this->getAreaDirector($this->community_id);
		if (!empty($emplayees))//如果不为空，直接监督人
		{
			foreach ($emplayees as $e) {
				if (PersonalRepairsHandling::createSupervision($this->id, $e)) //添加监督人
				{
					$this->staffId[] = $e;
					/*$note = '个人报修找到片区经理，添加'.$e->id.'为监督人。';
					if(!PersonalRepairsLog::createLog($this->id, $this->accept_object, $this->state, 0, $note,$this->shop_state))
						return false;*/
				}
			}
			$super = Employee::getEmployeeNames($emplayees);
			$super = !empty($super) ? implode('，', $super) : '';
			$note = sprintf('指定监督人为%s', $super);
			PersonalRepairsLog::createLog($this->id, $this->accept_object, $this->state, 0, "【 {$note} 】", $this->shop_state);

			$employees = PublicRepairs::model()->getEmployeeByCommunity($this->community_id, Item::COMPLAIN_REPAIRS_TYPE_PERSON);
			$cdb = new CDbCriteria();
			$cdb->addInCondition('id', $employees);
			$employees = Employee::model()->findAll($cdb);
			$mobiles = array_map(function (Employee $record) {
				return $record->mobile;
			}, $employees);
			if (isset(Yii::app()->sms) && !empty($mobiles)) {
				if (!$type) {
					Yii::app()->sms->sendIndividualRepairMessage('merchantRefused', $this->id, $mobiles);//个人报修 商家拒绝短信
				} else {
					Yii::app()->sms->sendIndividualRepairMessage('automaticallyAssigned', $this->id, $mobiles);//个人报修 超时自动处理
				}
			} else {
				Yii::log('不能发送短信，请联系管理员!', CLogger::LEVEL_INFO, 'colourlife.core.PersonalRepairsInfo');
			}

			return true;
		}

		return true;
	}

	//获取小区主任并添加为执行人/确认人 $type为false为商家拒绝 true为自动处理
	public function addCommunityAdministrator($type = false)
	{
		$emplayees = array();
		//获取小区主任
		$emplayees = PublicRepairs::model()->getEmployeeByCommunity($this->community_id, Item::COMPLAIN_REPAIRS_TYPE_PERSON);
		if (!empty($emplayees))//如果不为空，直接取第一个来做为执行人和确认人
		{
			$id = current($emplayees);
			$this->is_back = 1; //可以回踢
			$this->accept_time = time();
			$this->state = Item::PERSONAL_REPAIRS_RECEIVE_HANDLING;
			$this->execute = $id;
			$this->confirm = $id;
			//$note = '个人报修找到小区主任，添加'.$id.'为执行人和确认人。';
			$execute = Employee::getEmployeeNames($id);
			$note = sprintf('指派任务给%s，指定最后确认人为%s', $execute, $execute);
			if ($this->update()) {
				if (!PersonalRepairsLog::createLog($this->id, $this->accept_object, $this->state, 0, "【 {$note} 】", $this->shop_state))
					return false;
			}
			$employees = PublicRepairs::model()->getEmployeeByCommunity($this->community_id, Item::COMPLAIN_REPAIRS_TYPE_PERSON);
			$cdb = new CDbCriteria();
			$cdb->addInCondition('id', $employees);
			$employees = Employee::model()->findAll($cdb);
			$mobiles = array_map(function (Employee $record) {
				return $record->mobile;
			}, $employees);

			$emplayeeIds = array_map(function (Employee $record) {
				return $record->id;
			}, $employees);

			//商家拒绝 通知小区主任发送短信
			if (isset(Yii::app()->sms) && !empty($mobiles)) {
				if (!$type) {
					Yii::app()->sms->sendIndividualRepairMessage('merchantRefused', $this->id, $mobiles);//个人报修 商家拒绝短信
					$template = SmsTemplate::model()->findByAttributes(
						array('category' => 'individualRepair', 'code' => 'merchantRefused'));

				} else {
					Yii::app()->sms->sendIndividualRepairMessage('automaticallyAssigned', $this->id, $mobiles);//个人报修 超时自动处理

					$template = SmsTemplate::model()->findByAttributes(
						array('category' => 'individualRepair', 'code' => 'automaticallyAssigned'));
				}

				PushInformation::createSNSInformation("投诉报修", $template, PushClient::IS_TYPE_EMPLOYEE, $emplayeeIds);
			} else
				Yii::log('不能发送短信，请联系管理员!', CLogger::LEVEL_INFO, 'colourlife.core.PersonalRepairsInfo');

			return true;
		}
		return true;
	}

	public function afterFind()
	{
		$this->_old_execute = $this->execute;
		$this->_old_confirm = $this->confirm;
		//$this->execName = $this->getExecName();
		//$this->confirmName = $this->getConfirmName();
		$this->oldState = $this->state;
		$this->oldShopState = $this->shop_state;
		return parent::afterFind();
	}

	public function getShopStateName($html = true)
	{

		$return = '';
		$return .= ($html) ? '<span class="label label-success">' : '';
		$return .= empty(self::$_shop_state_list[$this->shop_state]) ? '' : self::$_shop_state_list[$this->shop_state];
		$return .= ($html) ? '</span>' : '';
		return $return;
	}

	//获得投诉小区所在的地区
	public function getCommunityDetail()
	{
		$community_id = $this->community_id;
		if (empty($community_id)) {
			return "获取小区信息失败！";
		}
		$community = Community::model()->findByPk($community_id);
		if (empty($community)) {
			return "获取小区所属部门失败！";
		}
		$addressList = $community->getMyParentRegionNames();
		$address = implode("-", $addressList) . "-" . $community->name;
		return $address;
	}


	public function getResponsibleByStatus()
	{
		$returnArr = array();
		//商家没的拒绝或接收
		if ($this->state == Item::PERSONAL_REPAIRS_AWAITING_HANDLE || $this->shop_state == Item::PERSONAL_REPAIRS_SHOP_ACCEPTED) {
			$returnArr['id'] = $this->shop_id;
			$returnArr['state'] = Item::PERSONAL_REPAIRS_SHOP_STATE;
		} else if (($this->state == Item::PERSONAL_REPAIRS_HANDLE_END ||
				$this->state == Item::PERSONAL_REPAIRS_RECEIVE_HANDLING)
			&& ($this->shop_state == Item::PERSONAL_REPAIRS_SHOP_REFUSAL && $this->execute != 0)
		) {
			$returnArr['id'] = $this->execute;
			$returnArr['state'] = Item::PERSONAL_REPAIRS_EXRCUTE_STATE;
		} else if ($this->state == Item::PERSONAL_REPAIRS_CONFIRM_END || $this->shop_state == Item::PERSONAL_REPAIRS_SHOP_HAS_SERVED) {
			$returnArr['id'] = $this->customer_id;
			$returnArr['state'] = Item::COMPLAIN_REPAIRS_DETAIL_CUSTOMER;//业主评论
		} else if ($this->state == Item::PERSONAL_REPAIRS_RECEIVE_HANDLING && $this->execute == 0 && $this->shop_state == Item::PERSONAL_REPAIRS_SHOP_REFUSAL) {
			$returnArr['id'] = 0;
			$returnArr['state'] = Item::COMPLAIN_REPAIRS_DETAIL_SERVICE;//400
		} else {
			$returnArr['id'] = 0;
			$returnArr['state'] = Item::COMPLAIN_REPAIRS_DETAIL_UNKNOWN;//未知

		}

		return $returnArr;
	}


	public function getLogConfig($string)
	{
		$configList = Yii::app()->config->$string;
		if (empty($configList)) {
			return array("create" => "", "execution" => "", "two_execution" => "", "finish" => "", "comment_end" => "", "stoped" => "");
		} else {
			return $configList;
		}
	}

	//获取投诉报修详情当前执行人的名字跟电话
	public function getNameMobile($employee_id)
	{
		$model = Employee::model()->findByPk($employee_id);
		$info = array();
		if (!empty($model)) {
			$info['name'] = $model->name;
			$mobile = $model->mobile;
			$info['mobile'] = empty($mobile) ? "" : $mobile;

		}
		return $info;
	}

	//根据状态计算个人报修的条数，只包括执行过，监督，监督过
	public function getPersonalRepairsNum($type = NULL, $state = array())
	{
		$criteria = new CDbCriteria();
		$criteria->join = "LEFT JOIN personal_repairs_handling prh ON `t`.`id` = prh.`personal_repairs_id`";
		$criteria->compare("`prh`.type", $type);
		$criteria->compare("`prh`.employee_id", Yii::app()->user->id);
		$criteria->addInCondition("`t`.state", $state);
		$criteria->group = "`t`.id";
		$model = PersonalRepairsInfo::model()->findAll($criteria);
		$count = count($model);
		return $count;
	}

	//根据状态计算投诉报修除个人报修我执行的条数
	/*
	 * $state 状态
	 * $type 类型，0=业主投诉，1=员工投诉，3=公共报修
	 * $handlingType
	 * */
	public function getPersonalRepairsNumber($type = NULL, $state = array())
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition(" IF(`t`.state=2 ,`t`.`execute` =" . Yii::app()->user->id . ",1=1) AND  IF(`t`.state=3,`t`.`confirm` =" . Yii::app()->user->id . ",1=1)");
		$criteria->addInCondition("`t`.state", $state);
		$criteria->group = "`t`.id";
		$model = PersonalRepairsInfo::model()->findAll($criteria);
		$count = count($model);
		return $count;
	}

	public function getApplyClose()
	{
		return empty($this->apply_close) ? "未申请关闭" : "已申请闭关";
	}

	//根据状态计算个人报修的列表，只包括执行过，监督，监督过
	public function getPersonalRepairsList($handlingType = NULL, $state = array(), $page = 1, $pagesize = 10)
	{
		$criteria = new CDbCriteria();
		$criteria->join = "LEFT JOIN personal_repairs_handling prh ON `t`.`id` = prh.`personal_repairs_id`";
		$criteria->compare("`prh`.type", $handlingType);
		$criteria->compare("`prh`.employee_id", Yii::app()->user->id);
		$criteria->addInCondition("`t`.state", $state);
		$criteria->group = "`t`.id";
		$page_count = (intval($page) - 1) * $pagesize;
		$criteria->limit = $pagesize;
		$criteria->offset = $page_count;
		$model = PersonalRepairsInfo::model()->findAll($criteria);
		return $model;
	}

	//前台状态
	public function getUserState()
	{
		if ($this->state == Item::PERSONAL_REPAIRS_AWAITING_HANDLE && $this->shop_state == Item::PERSONAL_REPAIRS_AWAITING_HANDLE) {
			return Item::COMPLAIN_REPAIRS_LOG_AWAITING_HANDL;//0
		} else if ($this->state == Item::PERSONAL_REPAIRS_SUCCESS_COLOSE || $this->shop_state == Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE) {
			return Item::COMPLAIN_REPAIRS_LOG_COLOSE;//5
		} else if ($this->state == Item::PERSONAL_REPAIRS_CONFIRM_END || $this->shop_state == Item::PERSONAL_REPAIRS_SHOP_HAS_SERVED) {
			return Item::COMPLAIN_REPAIRS_LOG_CONFIRM_END;//3
		} else if (($this->state == Item::PERSONAL_REPAIRS_RECEIVE_HANDLING && $this->oldState == Item::PERSONAL_REPAIRS_CONFIRM_END) ||
			($this->shop_state == Item::PERSONAL_REPAIRS_SHOP_ACCEPTED && $this->oldShopState == Item::PERSONAL_REPAIRS_SHOP_HAS_SERVED)
		) {
			return Item::COMPLAIN_REPAIRS_LOG_BAD_HANDLING;//2
		} else {
			return Item::COMPLAIN_REPAIRS_LOG_HANDLING;//1
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