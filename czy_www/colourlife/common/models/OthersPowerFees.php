<?php

/**
 * This is the model class for table "others_fees".
 *
 * The followings are the available columns in table 'others_fees':
 * @property integer $id
 * @property string $sn
 * @property string $model
 * @property integer $object_id
 * @property integer $customer_id
 * @property integer $payment_id
 * @property string $amount
 * @property string $note
 * @property string $create_ip
 * @property integer $create_time
 * @property integer $status
 * @property integer $meter
 */
class OthersPowerFees extends OthersFees
{
	/**
	 * @var string 模型名
	 */
	public $modelName = '商铺买电';
	public $objectLabel = '商铺买电';
	public $objectModel = 'PowerFees';

	static $fees_status = array(
		Item::FEES_AWAITING_PAYMENT => "待付款",
		// Item::FEES_RECHARGEING => "充值中",
		Item::FEES_TRANSACTION_ERROR => '已付款',
		Item::FEES_TRANSACTION_SUCCESS => "交易成功",
		Item::FEES_TRANSACTION_FAIL => '交易失败',
		// Item::FEES_TRANSACTION_REFUND => '退款',
		Item::FEES_TRANSACTION_LACK => '红包余额不足',
		Item::FEES_CANCEL => "订单已取消",
	);

	public $build;
	public $room;
	public $meter;
	public $meter_address;
	public $username;
	public $mobile;
	public $community_name;
	public $startTime;
	public $endTime;
	public $interface_order;
	public $recharge_code;
	//以下字段仅供搜索用
	public $communityIds = array(); //小区
	public $region; //地区
	public $community_id;

	public $province_id;
	public $city_id;
	public $district_id;

	public static function model($className = __class__)
	{
		return parent::model($className);
	}

	public function rules()
	{

		$array = array(
			//array('build_id,community_id,mobile,room', 'required'),
			array(
				'region,communityIds,community,region,username,interface_order,recharge_code,startTime,endTime,build, room,meter,meter_address,customer_id,customer_name,sn,status,mobile,community_name', 'safe', 'on' => 'search,index,report_search'),
			//			ICE 搜索数据
			array('province_id,city_id,district_id', 'safe'),
			);
		return CMap::mergeArray(parent::rules(), $array);
	}

	public function attributeLabels()
	{
		$array = array(
			'id' => 'ID',
			'sn' => '订单号',
			'build' => '楼栋号',
			'room' => '房间号',
			'meter' => '电表号',
			'meter_address' => '电表地址',
			'customer_id' => '用户姓名',
			'customer_name' => '电表签约姓名',
			'username' => '用户名',
			'community_name' => '小区',
			'interface_order' => '接口订单号',
			'recharge_code' => '充值码',
			'startTime' => '开始时间',
			'endTime' => '结束时间',
			'communityIds' => '小区',
			'region' => '地区',
		);
		return CMap::mergeArray(parent::attributeLabels(), $array);
	}

	static public function getStatusNames()
	{
		return CMap::mergeArray(array('' => '全部'), self::$fees_status);
	}

	static public function ValidateStatus($order_id, $order_status)
	{
		if (empty($order_id)) {
			return false;
		} else {
			if (!self::checkStatus($order_status)) {
				return false;
			} else {
				return true;
			}
		}
	}


	static public function checkStatus($status)
	{

		$status_arr = array(
			Item::FEES_AWAITING_PAYMENT,//"待付款",
			Item::FEES_TRANSACTION_ERROR,// '已付款',
			Item::FEES_TRANSACTION_SUCCESS, // "交易成功",
			Item::FEES_TRANSACTION_FAIL, // '交易失败',
			Item::FEES_TRANSACTION_REFUND, // '退款',
			Item::FEES_TRANSACTION_LACK, //=>'红包余额不足',
			Item::FEES_CANCEL, //=> "订单已取消",
		);

		if (!in_array($status, $status_arr) || $status != Item::FEES_AWAITING_PAYMENT) {
			return false;
		} else {
			return true;
		}
	}


	static public function changeOrderStatus($order_id, $user_id, $user_model, $status, $note = '')
	{
		if (empty($order_id)) {
			throw new CHttpException('404', '无效的操作对象');
		} else {
			$order = OthersPowerFees::model()->findByPk($order_id);
			if (!$order || Yii::app()->user->id != $order->customer_id || $order->model != 'PowerFees') {
				throw new CHttpException('404', '无效的操作对象');
			} else {
				$oldStatus = $order->status;

				$order->status = $status;

				$log = new OthersFeesLog();
				$log->others_fees_id = $order_id;
				$log->user_model = $user_model;
				$log->user_id = $user_id;
				$log->status = $status;
				$str = "";
				switch (strtolower($user_model)) {
					case "customer":
						$str .= "买家";
						break;
					case "shop":
						$str .= "商家";
						break;
					case "employee":
						$str .= "物业平台";
						break;
				}
				$str .= " 将订单状态从 【待付款】 修改为 【取消订单】";
				$log->note = $str . "，备注:" . $note;
				if ($log->save() && $order->save()) {
					return true;
				} else {
					return false;
				}

			}
		}
	}


	public function getPropertyStatus()
	{
		return CMap::mergeArray(array('' => '全部'), self::$fees_status);
	}

	public function getMobile()
	{
		return empty($this->customer) ? "" : $this->customer->mobile;
	}


	public function getPayment()
	{
		return empty($this->payment) ? "" : $this->payment->name;
	}


	public function getCreateTime()
	{
		return empty($this->create_time) ? "" : date("Y-m-d H:i:s", $this->create_time);
	}


	public function getRoomName()
	{
		$model = $this->objectModel;
		return empty($this->$model->room) ? '' : $this->$model->room;
	}

	public function getMeterName()
	{
		$model = $this->objectModel;
		return empty($this->$model->meter) ? '' : $this->$model->meter;
	}

	public function getMeterAddressName()
	{
		$model = $this->objectModel;
		return empty($this->$model->meter_address) ? '' : $this->$model->meter_address;
	}

	public function getCommunityId()
	{
		$model = $this->objectModel;
		return empty($this->$model->community_id) ? '' : $this->$model->community_id;
	}

	public function getBuildName()
	{
		$model = $this->objectModel;
		return empty($this->$model->build) ? '' : $this->$model->build;
	}

	public function getCustomer_name()
	{
		$model = $this->objectModel;
		return empty($this->$model->customer_name) ? '' : $this->$model->customer_name;
	}


	public function getInterfaceOrder()
	{
		$model = $this->objectModel;
		return empty($this->$model->interface_order) ? '' : $this->$model->interface_order;
	}

	public function getRechargeCode()
	{
		$model = $this->objectModel;
		return empty($this->$model->recharge_code) ? '' : $this->$model->recharge_code;
	}

	public function callStarWebOrder($order_id)
	{
		$powerFees = self::model()->findByPk($order_id);

		if (
			!empty($powerFees) &&
			!empty($powerFees->PowerFees) &&
			!empty($powerFees->PowerFees->meter)
		) {
			$meter = $powerFees->PowerFees->meter;
		}


		Yii::import('common.api.StarApi');
		$star = StarApi::getInstance();

		$send_params = '回调函数商铺买电订单sn:' . $powerFees->sn . ', OrderAmount:' . $powerFees->amount . ', MeterNo:' . $powerFees->meterName;

		//$testCommunityId = Yii::app()->config->testCommunityId; //得到测试小区ID

		//if (empty($testCommunityId) || $testCommunityId != $powerFees->communityId) {
		Yii::log('调用安彩华接口修改商铺买电订单' . $powerFees->sn . '状态，' . $send_params, CLogger::LEVEL_INFO, 'colourlife.core.colorcloud.OthersPowerFees');


		// var_dump($powerFees->RechargeCode);die;
		if ($powerFees->status == Item::FEES_TRANSACTION_SUCCESS && empty($powerFees->InterfaceOrder) && empty($powerFees->RechargeCode)) {

			//安彩华接口
			$star = $this->ConfirmInterfaceType($meter);
			$result = $star->callPurchasePower($meter, $powerFees->amount, $powerFees->sn);
			//$result = $star->callPurchasePower($powerFees->meterName, $powerFees->amount, $powerFees->sn);

			Yii::log('调用安彩华接口修改商铺买电订单sn:' . $powerFees->sn . '状态的返回值：' . var_export($result, true), CLogger::LEVEL_INFO,
				'colourlife.core.colorcloud.OthersPowerFees');
			//调用接口修改状态成功

			if (strlen($meter) == 9) {
				if (isset($result) && $result['code'] == '42440') { //云控电表调用接口成功
					$state = Item::FEES_TRANSACTION_SUCCESS;
					$note = '购电充值成功';
					OthersFeesLog::createOtherFeesLog($order_id, 'Customer', $state, $note);
					//更新购电码 和 STOrderNO
					PowerFees::updateStarResult($powerFees->object_id, $result->Token, $result->STOrderNo);
					return true;
				} else {
					$state = Item::FEES_TRANSACTION_FAIL;
					$note = '购电充值失败';
					OthersFeesLog::createOtherFeesLog($order_id, 'Customer', $state, $note);
					return false;
				}
			} else {
				if (!isset($result) || $result->PurchasePowerResult != 0) {
					$state = Item::FEES_TRANSACTION_FAIL;
					$note = '获取购电码失败';
					OthersFeesLog::createOtherFeesLog($order_id, 'Customer', $state, $note);
					return false;
				} else {
					$state = Item::FEES_TRANSACTION_SUCCESS;
					$note = '获取购电码成功';
					OthersFeesLog::createOtherFeesLog($order_id, 'Customer', $state, $note);
					//更新购电码 和 STOrderNO
					PowerFees::updateStarResult($powerFees->object_id, $result->Token, $result->STOrderNo);
					return true;
				}
			}


		}


		// } else {
		//     //测试小区不调用接口
		//     Yii::log("测试小区不调用接口，不修改商铺买电订单' . $powerFees->sn . '状态, 系统订单ID:" . $powerFees->id . "购电金额:" . $powerFees->amount . "MeterNo:" . $powerFees->meterName. " 测试小区ID" . $testCommunityId,
		//         CLogger::LEVEL_INFO, 'colourlife.core.colorcloud.PayOrder');
		//     return false;
		// }

	}


	//判断是使用的是新接口还是久接口 type 1: 久的电表接口 、2: 新的电表接口 3:云控电表
	public function ConfirmInterfaceType($meter)
	{
		if (!empty($meter)) {
			$Address = PowerAddress::model()->find("meter=:meter and is_deleted=:is_deleted", array(':meter' => $meter, ':is_deleted' => 0));
			if (!$Address)
				throw new CHttpException(400, "电表地址错误");

			$type = intval($Address->interface_type);

			if (1 === $type) {
				Yii::import('common.api.StarApi');
				return StarApi::getInstance();
			} elseif (2 === $type) {
				Yii::import('common.api.StarApiNew');
				return StarApiNew::getInstance();
			} elseif (3 === $type) {
				Yii::import('common.api.CloudControlElectricMeterApi');
				return CloudControlElectricMeterApi::getInstance();
			} else {
				throw new CHttpException(400, "类别错误");
			}
		}

	}


	public function callStarOrder($order_id, $state, $note)
	{
		$powerFees = self::model()->findByPk($order_id);

		if (
			!empty($powerFees) &&
			!empty($powerFees->PowerFees) &&
			!empty($powerFees->PowerFees->meter)
		) {

			$meter = $powerFees->PowerFees->meter;

			$send_params = '回调函数商铺买电订单sn:' . $powerFees->sn . ', OrderAmount:' . $powerFees->amount . ', MeterNo:' . $powerFees->meterName;

			//$testCommunityId = Yii::app()->config->testCommunityId; //得到测试小区ID

			//if (empty($testCommunityId) || $testCommunityId != $powerFees->communityId) {
			Yii::log('调用安彩华接口修改商铺买电订单' . $powerFees->sn . '状态，' . $send_params, CLogger::LEVEL_INFO, 'colourlife.core.colorcloud.OthersPowerFees');

			if ($powerFees->status == Item::FEES_TRANSACTION_SUCCESS) {
				Yii::log('支付再次回调商铺买电订单' . $powerFees->sn . '函数,当前状态:' . $powerFees->status, CLogger::LEVEL_INFO,
					'colourlife.core.colorcloud.OthersPowerFees');
				return false;
			}

			//安彩华接口

			$star = $this->ConfirmInterfaceType($meter);
			$result = $star->callPurchasePower($meter, $powerFees->amount, $powerFees->sn);

			Yii::log('调用安彩华/云控电表接口修改商铺买电订单sn:' . $powerFees->sn . '状态的返回值：' . var_export($result, true), CLogger::LEVEL_INFO,
				'colourlife.core.colorcloud.OthersPowerFees');

			if (strlen($meter) == 9) {
				if (isset($result) && $result['code'] == '42440') { //云控电表调用接口成功
					$state = Item::FEES_TRANSACTION_SUCCESS;
					//更新 STOrderNO
					//如果购电成功发送短信通知业主
					$sn = $powerFees->sn;
					$sms = new SmsComponent();
					$sms->init();
					$mobile = $sms->sendPowerFeesMessage('paymentSuccess', $sn);
					PowerFees::updateStarResult($powerFees->object_id, '云控电表无购电码', $sn);//云控电表无充值码，设置默认值
				} else {
					$state = Item::FEES_TRANSACTION_FAIL;
				}
			} else {
				if (isset($result) && $result->PurchasePowerResult == 0) { //安彩华调用接口修改状态成功
					$state = Item::FEES_TRANSACTION_SUCCESS;
					//更新购电码 和 STOrderNO
					//如果购电成功发送短信通知业主

					$sn = $powerFees->sn;
					$sms = new SmsComponent();
					$sms->init();
					$mobile = $sms->sendPowerFeesMessage('paymentSuccess', $sn);
					PowerFees::updateStarResult($powerFees->object_id, $result->Token, $result->STOrderNo);
				} else {
					$state = Item::FEES_TRANSACTION_FAIL;
				}
			}


			$electricityModel = OthersFees::model()->findByPk($order_id);
			if ($electricityModel->status == Item::FEES_TRANSACTION_SUCCESS) {
				Yii::log('支付再次回调商铺买电订单' . $powerFees->sn . '函数,当前状态:' . $powerFees->status . ",已成功，不修改为其他状态！", CLogger::LEVEL_INFO,
					'colourlife.core.colorcloud.OthersPowerFees');
				return false;
			}

			//修改我们的商铺买电订单状态
			OthersFees::model()->updateByPk($order_id, array('status' => $state));
			// } else {
			//     //测试小区不调用接口
			//     Yii::log("测试小区不调用接口，不修改商铺买电订单' . $powerFees->sn . '状态, 系统订单ID:" . $powerFees->id . "购电金额:" . $powerFees->amount . "MeterNo:" . $powerFees->meterName. " 测试小区ID" . $testCommunityId,
			//         CLogger::LEVEL_INFO, 'colourlife.core.colorcloud.PayOrder');
			// }
			//写我们的订单日志
			OthersFeesLog::createOtherFeesLog($order_id, 'Customer', $state, $note);
		} else {

			throw  new CHttpException(400, "数据为空");

		}

	}


	public function report_search()
	{
		$criteria = new CDbCriteria;
		if (isset($_GET['OthersPowerFees']) && !empty($_GET['OthersPowerFees'])) {
			$_SESSION['OthersPowerFees'] = array();
			$_SESSION['OthersPowerFees'] = $_GET['OthersPowerFees'];
		}
		if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
			if (isset($_SESSION['OthersPowerFees']) && !empty($_SESSION['OthersPowerFees'])) {
				foreach ($_SESSION['OthersPowerFees'] as $key => $val) {
					if ($val != "") {
						$this->$key = $val;
					}
				}
			}
		}
		$criteria->compare('model', $this->objectModel, true); //设置条件
		$criteria->join = "JOIN power_fees as se ON (se.id = t.object_id and  t.model='{$this->objectModel}' )";
		$criteria->compare('sn', $this->sn, true);
		$criteria->compare('t.status', $this->status);
		$criteria->compare('t.payment_id', $this->payment_id);
		$criteria->addInCondition('t.status', $this->getMyStatusList());
		if ($this->customer_id != '' || $this->mobile != '' || $this->username != '') {
			$criteria->with[] = 'customer';
			if ($this->mobile != '') {
				$criteria->compare('customer.mobile', $this->mobile);
			}
			if ($this->customer_id != '') {
				$criteria->compare('customer.name', $this->customer_id, true);
			}

			if ($this->username != '') {
				$criteria->compare('customer.username', $this->username, true);
			}

		}

		if ($this->create_time != '') {
			$criteria->compare('t.create_time', $this->create_time);
		}

		if ($this->startTime != '') {

			$criteria->compare("t.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {

			$criteria->compare("t.create_time", "< " . strtotime($this->endTime));

		}

		$criteria->with[] = $this->objectModel;


//		$employee = Employee::model()->findByPk(Yii::app()->user->id);
//		$branchIds = $employee->mergeBranch;
//		//选择的组织架构ID
//		if ($this->branch_id != '')
//			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
//		else if (!empty($this->communityIds)) //如果有小区
//			$community_ids = $this->communityIds;
//		else if ($this->region != '') //如果有地区
//			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');
//		else {
//			$community_ids = array();
//			foreach ($branchIds as $branchId) {
//				$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
//				$community_ids = array_unique(array_merge($community_ids, $data));
//			}
//		}

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
		} else {
			$employee = ICEEmployee::model()->findByPk(Yii::app()->user->id);
			$community_ids = $employee->ICEGetOrgCommunity();
		}

		$criteria->addInCondition($this->objectModel . '.community_id', $community_ids);

		if ($this->room != '') {
			$criteria->compare($this->objectModel . '.room', $this->room, true);
		}

		if ($this->build != '') {

			$criteria->compare($this->objectModel . '.build', $this->build, true);
		}
		if ($this->build != '') {

			$criteria->compare($this->objectModel . '.build', $this->build, true);
		}
		if ($this->customer_name != '') {
			$criteria->compare($this->objectModel . '.customer_name', $this->customer_name, true);
		}

//		if ($this->community_name != '') {
//			$community = Community::model()->findAll("name like :name", array(':name' => "%" . $this->community_name . "%"));
//			$communityArr = array();
//			foreach ($community as $key => $value) {
//				$communityArr[] = $value->id;
//			}
//			$criteria->addInCondition($this->objectModel . '.community_id', $communityArr);
//		}
//      ICE 接入按照小区名搜索返回小区id数组
		if ($this->community_name != '') {
			$response = ICECommunity::model()->ICEGetCommunitySearch(array(
				'keyword' => $this->community_name,
				'page' => '1',
				'size' => '500',
			));
			$communities = isset($response['list']) && is_array($response['list']) ? $response['list'] : array();
			$searchCommunityIds = array();
			foreach ($communities as $community) {
				if (!isset($community['czy_id']) || !$community['czy_id']) {
					continue;
				}
				$searchCommunityIds[] = $community['czy_id'];
			}
			$criteria->addInCondition($this->objectModel . '.community_id', $searchCommunityIds);
		}

		return new ActiveDataProvider($this, array('criteria' => $criteria, 'sort' =>
			array('defaultOrder' => '`t`.create_time desc',)));
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('model', $this->objectModel, true); //设置条件
		$criteria->join = "JOIN power_fees as se ON (se.id = t.object_id and  t.model='{$this->objectModel}' )";
		$criteria->compare('sn', $this->sn, true);
		$criteria->compare('se.meter', $this->meter, true);
		$criteria->compare('t.status', $this->status);
		$criteria->compare('t.payment_id', $this->payment_id);
		$criteria->addInCondition('t.status', $this->getMyStatusList());
		if ($this->customer_id != '' || $this->mobile != '' || $this->username != '') {
			$criteria->with[] = 'customer';
			if ($this->mobile != '') {
				$criteria->compare('customer.mobile', $this->mobile);
			}
			if ($this->customer_id != '') {
				$criteria->compare('customer.name', $this->customer_id, true);
			}

			if ($this->username != '') {
				$criteria->compare('customer.username', $this->username, true);
			}

		}

		if ($this->create_time != '') {
			$criteria->compare('t.create_time', $this->create_time);
		}

		if ($this->startTime != '') {

			$criteria->compare("t.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {

			$criteria->compare("t.create_time", "< " . strtotime($this->endTime));

		}

		$criteria->with[] = $this->objectModel;

//        $employee = Employee::model()->findByPk(Yii::app()->user->id);
//        $branchIds = $employee->mergeBranch;
		//选择的组织架构ID
		// if ($this->branch_id != '')
		//     $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
		// else if (!empty($this->communityIds)) //如果有小区
		//     $community_ids = $this->communityIds;
		// else if ($this->region != '') //如果有地区
		//     $community_ids = Region::model()->getRegionCommunity($this->region, 'id');
		// else {
		//     $community_ids = array();
		//     foreach ($branchIds as $branchId) {
		//         $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
		//         $community_ids = array_unique(array_merge($community_ids, $data));
		//     }
		// }
		
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
		} else {
			$employee = ICEEmployee::model()->findByPk(Yii::app()->user->id);
			$community_ids = $employee->ICEGetOrgCommunity();
		}

		$criteria->addInCondition($this->objectModel . '.community_id', $community_ids);

		if ($this->room != '') {
			$criteria->compare($this->objectModel . '.room', $this->room, true);
		}

		if ($this->build != '') {

			$criteria->compare($this->objectModel . '.build', $this->build, true);
		}
		if ($this->build != '') {

			$criteria->compare($this->objectModel . '.build', $this->build, true);
		}
		if ($this->customer_name != '') {
			$criteria->compare($this->objectModel . '.customer_name', $this->customer_name, true);
		}

//		if ($this->community_name != '') {
//			$community = Community::model()->findAll("name like :name", array(':name' => "%" . $this->community_name . "%"));
//			$communityArr = array();
//			foreach ($community as $key => $value) {
//				$communityArr[] = $value->id;
//			}
//			$criteria->addInCondition($this->objectModel . '.community_id', $communityArr);
//		}

//      ICE 接入按照小区名搜索返回小区id数组
		if ($this->community_name != '') {
			$response = ICECommunity::model()->ICEGetCommunitySearch(array(
				'keyword' => $this->community_name,
				'page' => '1',
				'size' => '500',
			));
			$communities = isset($response['list']) && is_array($response['list']) ? $response['list'] : array();
			$searchCommunityIds = array();
			foreach ($communities as $community) {
				if (!isset($community['czy_id']) || !$community['czy_id']) {
					continue;
				}
				$searchCommunityIds[] = $community['czy_id'];
			}
			$criteria->addInCondition($this->objectModel . '.community_id', $searchCommunityIds);
		}

		return new ActiveDataProvider($this, array('criteria' => $criteria, 'sort' =>
			array('defaultOrder' => '`t`.create_time desc',)));
	}

//
	public function getBranchName()
	{
//		if (isset($this->customer))
//			if (isset($this->customer->community))
//				if (isset($this->customer->community->branch)) {
//					//return $this->customer->community->branch->name;
//					return implode(' ', $this->getMyBranch($this->customer->community->branch->id));
//				}        if (isset($this->customer)) {
		if (isset($this->customer->community_id)) {
			//  ICE接入
			$community = ICECommunity::model()->findByPk($this->customer->community_id);
			if (!empty($community)) {
				return $community->branchstring;
			}
		}
	}


	public function getRegionName()
	{
//		if (isset($this->customer))
//			if (isset($this->customer->community))
//				if (isset($this->customer->community->region)) {
//					//return $this->customer->community->region->name;
//					return $this->myRegion($this->customer->community->region->id);
//				}
//		return "";
		if (isset($this->customer)) {
			if (isset($this->customer->community_id)) {
				//  ICE接入
				$community = ICECommunity::model()->findByPk($this->customer->community_id);
				if (!empty($community)) {
					return $community->ICEGetCommunityAddress(true);
				}
			}
		}
		return "";
	}

	public function myRegion($id)
	{
		return implode(' ', F::getRegion($id));
	}

	public function getMyBranch($id)
	{
		return Branch::model()->getAllBranch($id);
	}

	public function getCommunityHtml()
	{
		return CHtml::tag('a', array('href' => 'javascript:void();', 'rel' => 'tooltip',
			'data-original-title' => '地域: ' . $this->regionName . '  部门: ' . $this->branchName),
			$this->communityName);
	}

	public function getIdHtml()
	{
		return CHtml::tag('a', array('href' => 'javascript:void();', 'rel' => 'tooltip',
			'data-original-title' => '订单号: ' . $this->sn),
			$this->id);
	}

	public function getCustomerNameHtml()
	{
		return CHtml::tag('a', array('href' => 'javascript:void();', 'rel' => 'tooltip',
			'data-original-title' => '手机号码: ' . $this->getMobile()),
			$this->getCustomer_name());
	}

	public function getMeterHtml()
	{
		return CHtml::tag('a', array('href' => 'javascript:void();', 'rel' => 'tooltip',
			'data-original-title' => '电表地址: ' . $this->getMeterAddressName()),
			$this->getMeterName());
	}


	public function getMyStatusList()
	{
		$return = array();

		if (Yii::app()->user->checkAccess('op_backend_powerFees_awaiting')) {
			$return[] = Item::FEES_AWAITING_PAYMENT;
		}

		if (Yii::app()->user->checkAccess('op_backend_powerFees_success')) {
			$return[] = Item::FEES_TRANSACTION_SUCCESS;
		}

		if (Yii::app()->user->checkAccess('op_backend_powerFees_payment')) {
			$return[] = Item::FEES_TRANSACTION_ERROR;
		}

		if (Yii::app()->user->checkAccess('op_backend_powerFees_refund')) {
			$return[] = Item::FEES_TRANSACTION_REFUND;
		}

		if (Yii::app()->user->checkAccess('op_backend_powerFees_fail')) {
			$return[] = Item::FEES_TRANSACTION_FAIL;
		}

		if (Yii::app()->user->checkAccess('op_backend_powerFees_cancel')) {
			$return[] = Item::FEES_CANCEL;
		}

		if (Yii::app()->user->checkAccess('op_backend_powerFees_fail')) {
			$return[] = Item::FEES_TRANSACTION_LACK;
		}
		return $return;
	}

	public function getStatusList()
	{
		$return = array('' => '全部');
		$data = OthersFeesStatus::StatusList('power');

		foreach ($this->getMyStatusList() as $key => $value) {
			$return[$value] = $data[$value];
		}
		return $return;
	}


	public function ReturnStatus($status)
	{


		//返回状态类型
		$type = "";
		switch ($status) {
			case Item::FEES_AWAITING_PAYMENT:

				$type = "待付款";
				break;

			case Item::FEES_TRANSACTION_ERROR:

				$type = "已付款";
				break;
			case Item::FEES_TRANSACTION_SUCCESS:

				$type = "交易成功";
				break;
			case Item::FEES_TRANSACTION_FAIL:

				$type = "交易失败";
				break;
			case Item::FEES_TRANSACTION_REFUND:

				$type = "退款";
				break;
			case Item::FEES_TRANSACTION_LACK:

				$type = "红包余额不足";
				break;
			case Item::FEES_CANCEL:

				$type = "订单已取消";
				break;
			default :
				$type = "未知状态";

		}

		return $type;

	}

	public function getStatusName($html = false)
	{
		$return = '';
		$return .= ($html) ? '<span class="label label-success">' : '';
		// $return .= self::$fees_status[$this->status];
		$return .= $this->ReturnStatus($this->status);
		$return .= ($html) ? '</span>' : '';
		return $return;
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
