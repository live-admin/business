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
 */
class OthersParkingFeesMonth extends OthersFees
{
	/**
	 * @var string 模型名
	 */
	public $modelName = '缴月卡停车费';
	public $objectLabel = '月卡停车费';
	public $objectModel = 'ParkingFeesMonth';
	public $pay_sn;
	public $region;


	static $fees_status = array(
		Item::FEES_AWAITING_PAYMENT => "待付款",
		Item::FEES_RECHARGEING => "充值中",
		Item::FEES_TRANSACTION_ERROR => '已付款,未通知',
		Item::FEES_TRANSACTION_LACK => '红包余额不足',
		Item::FEES_TRANSACTION_REFUND => '退款',
		Item::FEES_TRANSACTION_SUCCESS => "已续卡",
		Item::FEES_CANCEL => "订单已取消",
	);

	public $mobile;
	public $username;
	public $community_id;
	public $startTime;
	public $endTime;
	public $third_order_id;
	public $third_park_type;
	public $third_park_id;
	public $fee_number;
	public $fee_unit;
	public $car_number;
	public $communityIds;

	public $province_id;
	public $city_id;
	public $district_id;

	public $branch_id;

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function rules()
	{
		$array = array(
			//  array('build_id,community_id,mobile,room', 'required'),
			array('car_number', 'numerical', 'integerOnly' => true),
			array(
				'region,communityIds,username,endTime,startTime,build_name,pay_sn,type_id,mobile,community_name,build_id,room,plate_no,community_id, parking_card_number,customer_id',
				'safe',
				'on' => 'search'
			),
			//          ICE 搜索数据
			array('province_id,city_id,district_id', 'safe'),
		);
		return CMap::mergeArray(parent::rules(), $array);
	}


	public function relations()
	{
		$array = array('community' => array(self::BELONGS_TO, 'Community', 'community_id'));

		return CMap::mergeArray(parent::relations(), $array);
	}

	public function attributeLabels()
	{
		$array = array(
			'id' => 'ID',
			'car_number' => '车牌号',
			'community_id' => '小区ID',
			'community_name' => '小区',
			'build_name' => '楼栋',
			'mobile' => '电话号码',
			'build_id' => '楼栋',
			'room' => '房间号',
			'startTime' => '开始时间',
			'endTime' => '结束时间',
			'username' => '用户名',
			'pay_sn' => '支付单号',
			'third_order_id' => '缴费订单号',
			'third_park_type' => '缴费商家',
			'third_park_id' => '停车场ID',
			'fee_unit' => '缴费标准类型',
			'fee_number' => '缴费数量',
			'communityIds' => '小区',
		);

		return CMap::mergeArray(parent::attributeLabels(), $array);
	}

	/**
	 * 车牌号
	 * @return string
	 */
	public function getCarNumber()
	{
		$model = $this->objectModel;
		return empty($this->$model->car_number) ? '' : $this->$model->car_number;
	}

	/**
	 * 楼栋
	 * @return string
	 */
	public function getBuildName()
	{
		$model = $this->objectModel;
		return empty($this->$model->build) ? "" : $this->$model->build->name;
	}

	/**
	 * 房间号
	 * @return string
	 */
	public function getMyRoom()
	{
		$model = $this->objectModel;
		return empty($this->$model) ? "" : $this->$model->room;
	}

	public function getThirdParkName()
	{
		$model = $this->objectModel;
		$parkType = array(
			Item::PARKING_TYPE_GEMEITE => '格美特',
			Item::PARKING_TYPE_AIKE => '艾科',
			Item::PARKING_TYPE_HANWANG => '汉王'
		);

		return empty($this->$model->third_park_type) ? '' : $parkType[$this->$model->third_park_type];
	}

	/**
	 * 缴费类型
	 * @return string
	 */
	public function getParkingFeeUnit()
	{
		$model = $this->objectModel;
		return empty($this->$model->fee_unit) ? '' : $this->$model->fee_unit;
	}

	/**
	 * 缴费数量
	 * @return string
	 */
	public function getParkingFeeNumber()
	{
		$model = $this->objectModel;
		return empty($this->$model->fee_number) ? '0' : $this->$model->fee_number;
	}

	/**
	 * 停车订单
	 * @return string
	 */
	public function getThirdOrderId()
	{
		$model = $this->objectModel;
		return empty($this->$model->third_order_id) ? '0' : $this->$model->third_order_id;
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('model', $this->objectModel, false); //设置条件

		$criteria->compare('sn', $this->sn, true);
		$criteria->compare('`t`.status', $this->status);
		$criteria->compare('payment_id', $this->payment_id);
		$criteria->addInCondition('`t`.status', $this->getMyStatusList());

		if ($this->customer_id != '' || $this->mobile != '' || $this->username != '') {
			$criteria->with[] = 'customer';
			if ($this->customer_id != '') {

				$criteria->compare('customer.name', $this->customer_id, true);
			}
			if ($this->mobile != '') {
				$criteria->compare('customer.mobile', $this->mobile, true);
			}

			if ($this->username != '') {
				$criteria->compare('customer.username', $this->username, true);
			}

		}

		if ($this->car_number != '') {
			$criteria->with[] = 'ParkingFeesMonth';
			$criteria->compare('ParkingFeesMonth.car_number', $this->car_number, true);
			//$criteria->compare($this->objectModel . '.plate_no', $this->plate_no, true);
		}


		if ($this->startTime != '') {
			$criteria->compare("create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("create_time", "< " . strtotime($this->endTime));
		}

		if ($this->pay_sn != "") {
			$pay = Pay::model()->getModel($this->pay_sn);
			if (!empty($pay)) {
				$pay_id = $pay->id;
				$criteria->compare("`pay_id`", $pay_id);
			} else {
				$criteria->compare("`pay_id`", "-1");
			}
		}


		$criteria->with[] = 'ParkingFeesMonth';
//        $employee = Employee::model()->findByPk(Yii::app()->user->id);
//        $branchIds = $employee->mergeBranch;
		//选择的组织架构ID
		// if($this->branch_id != '')
		//     $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
		// else if(!empty($this->communityIds))  //如果没有小区
		//     $community_ids = $this->communityIds;
		// else if($this->region != '')  //如果有地区
		//     $community_ids = Region::model()->getRegionCommunity($this->region,'id');
		// else {
		//     $community_ids = array();
		//     foreach($branchIds as $branchId){
		//         $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
		//         $community_ids = array_unique(array_merge($community_ids, $data));
		//     }
		// }

		//选择的组织架构ID
		if ($this->branch_id != '') {
			//$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
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

		$criteria->addInCondition('ParkingFeesMonth.community_id', $community_ids);


		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => '`t`.create_time desc',
			)
		));
	}

//  ICE接入
	public function getBranchName()
	{
//        if (isset($this->customer)) {
//            if (isset($this->customer->community)) {
//                if (isset($this->customer->community->branch)) {
//                    //return $this->customer->community->branch->name;
//                    return implode(' ', $this->getMyBranch($this->customer->community->branch->id));
//                }
//            }
//        }
		if (isset($this->customer)) {
			if (isset($this->customer->community_id)) {
				//  ICE接入
				$community = ICECommunity::model()->findByPk($this->customer->community_id);
				if (!empty($community)) {
					return $community->branchstring;
				}
			}
		}
	}

//  ICE接入
	public function getRegionName()
	{
//        if (isset($this->customer)) {
//            if (isset($this->customer->community)) {
//                if (isset($this->customer->community->region)) {
//                    //return $this->customer->community->region->name;
//                    return $this->myRegion($this->customer->community->region->id);
//                }
//            }
//        }
//        return "";

		if (isset($this->customer)) {
			if (isset($this->customer->community)) {
				if (isset($this->customer->community->region)) {
					//return $this->customer->community->region->name;
					return $this->myRegion($this->customer->community->region->id);
				}
			}
		}
		return "";
		$a = ICECommunity::model()->findByPk(2);
		var_dump($a->ICEGetCommunityAddress(true));
		die;

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
		return CHtml::tag(
			'span',
			array(
				'rel' => 'tooltip',
				'data-original-title' => '地域:' . $this->regionName . '  部门:' . $this->branchName
			),
			$this->communityName
		);
	}

	public function getParkStatusList()
	{
		if ($this->status == Item::FEES_AWAITING_PAYMENT) {
			$arr = array(Item::FEES_TRANSACTION_ERROR => '已付款');
		} elseif ($this->status == Item::FEES_TRANSACTION_ERROR) {
			$arr = array(Item::FEES_TRANSACTION_SUCCESS => '已续卡', Item::FEES_TRANSACTION_REFUND => '退款');
		} elseif ($this->status == Item::FEES_TRANSACTION_FAIL) {
			$arr = array(Item::FEES_TRANSACTION_REFUND => '退款');
		} elseif ($this->status == Item::FEES_TRANSACTION_REFUND) {
			$arr = array(Item::FEES_AWAITING_PAYMENT => '待付款');
		} else {
			$arr = array('' => '');
		}
		return $arr;
	}

	public function getStatusName($html = false)
	{
		$return = '';
		$return .= ($html) ? '<span class="label label-success">' : '';
		$return .= self::$fees_status[$this->status];
		$return .= ($html) ? '</span>' : '';
		return $return;
	}

	//给search用的状态
	public function getMyStatusList()
	{
		$return = array();
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Awaiting')) {
			$return[] = Item::FEES_AWAITING_PAYMENT;
		}
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Rechargeing')) {
			$return[] = Item::FEES_RECHARGEING;
		}
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Success')) {
			$return[] = Item::FEES_TRANSACTION_SUCCESS;
		}
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Payment')) {
			$return[] = Item::FEES_TRANSACTION_ERROR;
		}
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Refund')) {
			$return[] = Item::FEES_TRANSACTION_REFUND;
		}
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Cancel')) {
			$return[] = Item::FEES_CANCEL;
		}

		if (Yii::app()->user->checkAccess('op_backend_parkingFees_redFail')) {
			$return[] = Item::FEES_TRANSACTION_LACK;
		}
		return $return;
	}

	//取得搜索显示用的状态
	public function getMyStatusListName()
	{
		$return = array('' => '全部');
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Awaiting')) {
			$return[Item::FEES_AWAITING_PAYMENT] = self::$fees_status[Item::FEES_AWAITING_PAYMENT];
		}
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Rechargeing')) {
			$return[Item::FEES_RECHARGEING] = self::$fees_status[Item::FEES_RECHARGEING];
		}
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Success')) {
			$return[Item::FEES_TRANSACTION_SUCCESS] = self::$fees_status[Item::FEES_TRANSACTION_SUCCESS];
		}
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Payment')) {
			$return[Item::FEES_TRANSACTION_ERROR] = self::$fees_status[Item::FEES_TRANSACTION_ERROR];
		}
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Refund')) {
			$return[Item::FEES_TRANSACTION_REFUND] = self::$fees_status[Item::FEES_TRANSACTION_REFUND];
		}
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_redFail')) {
			$return[Item::FEES_TRANSACTION_LACK] = self::$fees_status[Item::FEES_TRANSACTION_LACK];
		}
		if (Yii::app()->user->checkAccess('op_backend_parkingFees_Cancel')) {
			$return[Item::FEES_CANCEL] = self::$fees_status[Item::FEES_CANCEL];
		}

		return $return;
	}

	// 得到停车费地址
	public function getParkingMonthActivityAddress()
	{
		$regions = "";
		if (!empty($this->ParkingFeesMonth)) {
			$model = Community::model()->enabled()->findByPk($this->ParkingFeesMonth->community_id);
			if (!empty($model)) {
				$regions = $model->getCommunityAddress();
				$regions .= $this->ParkingFeesMonth->build->name . $this->ParkingFeesMonth->room;
			}
		}
		return $regions;
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
