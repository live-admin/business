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
class OthersParkingFeesGemeite extends OthersFees
{
	/**
	 * @var string 模型名
	 */
	public $modelName = '缴停车费';
	public $objectLabel = '停车费';
	public $objectModel = 'ParkingFeesGemeite';
	public $pay_sn;

	static $fees_status = array(
		Item::FEES_AWAITING_PAYMENT => "待付款",
		Item::FEES_RECHARGEING => "充值中",
		Item::FEES_TRANSACTION_ERROR => '已付款,未通知',
		Item::FEES_TRANSACTION_LACK => '红包余额不足',
		Item::FEES_TRANSACTION_REFUND => '退款',
		Item::FEES_TRANSACTION_SUCCESS => "已续卡",
		Item::FEES_CANCEL => "订单已取消",
	);

	public $plate_no;
	public $parking_card_number;
	public $community_id;
	public $username;
	public $mobile;
	public $build_id;
	public $room;
	public $community_name;
	public $build_name;
	public $startTime;
	public $endTime;
	public $buyer_id;

	//以下字段仅供搜索用
	public $communityIds = array(); //小区
	public $region; //地区

	public $province_id;
	public $city_id;
	public $district_id;

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
			'plate_no' => '车牌号',
			'parking_card_number' => '停车位号',
			'type_id' => '停车费类型',
			'period' => '缴费时间数',
			'community_id' => '小区',
			'community_name' => '小区',
			'build_name' => '楼栋',
			'mobile' => '电话号码',
			'build_id' => '楼栋',
			'room' => '房间号',
			'startTime' => '开始时间',
			'endTime' => '结束时间',
			'username' => '用户名',
			'communityIds' => '小区',
			'region' => '地区',
			'pay_sn' => '支付单号',
			'time_start' => '下一个支付开始时间',
			'time_expired' => '有效期时间',
			'out_trade_no' => '格美特订单号',
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
		return empty($this->$model->plate_no) ? '' : $this->$model->plate_no;
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

	/**
	 * 缴费类型
	 * @return string
	 */
	public function getParkingType()
	{
		$model = $this->objectModel;
		return empty($this->$model->fee_unit) ? '' : $this->$model->fee_unit;
	}

	/**
	 * 缴费数量
	 * @return string
	 */
	public function getParkingPeriod()
	{
		$model = $this->objectModel;
		return empty($this->$model->fee_number) ? '0' : $this->$model->fee_number;
	}

	/**
	 * 格美特订单
	 * @return string
	 */
	public function getOutTradeNo()
	{
		$model = $this->objectModel;
		return empty($this->$model->out_trade_no) ? '0' : $this->$model->out_trade_no;
	}

	/**
	 * 下一次续费开始时间
	 * @return string
	 */
	public function getTimeStart()
	{
		$model = $this->objectModel;
		return empty($this->$model->out_trade_no) ? '0' : $this->$model->out_trade_no;
	}

	/**
	 * 有效日期
	 * @return string
	 */
	public function getTimeExpired()
	{
		$model = $this->objectModel;
		return empty($this->$model->out_trade_no) ? '0' : $this->$model->out_trade_no;
	}

	/**
	 * 业主姓名
	 * @return string
	 */
	public function getOwnerName()
	{
		$model = $this->objectModel;
		return empty($this->$model->owner_name) ? '--' : $this->$model->owner_name;
	}

	/**
	 * 创建停车费订单
	 * 参数说明：1、feeAttr:OthersFees模型的属性集合，2、parkingAttr:ParkingFees模型的属性集合
	 * 返回值 boolean, true:创建成功，false:创建失败
	 * yichao by 2013年8月9日 14:40:04
	 * */
	public static function createParkingOrder($feeAttr, $parkingAttr)
	{
		if (empty($feeAttr) || empty($parkingAttr)) {
			OthersParkingFees::model()->addError('id', "创建停车费订单失败！");
			return false;
		}

		//创建订单记录及物业费缴费记录
		$other = new OthersFees();
		$other->attributes = $feeAttr;
		$other->bank_pay = $other->bank_pay < 0 ? 0 : $other->bank_pay;//银行支付金额不能为负数

		$model = new ParkingFees();
		$model->attributes = $parkingAttr;

		if ($model->save()) { //先创建物业费记录，得到记录ID再创建订单记录
			$other->object_id = $model->id;
			if (!$other->save()) {
				OthersParkingFees::model()->addError('id', "创建停车费订单失败！");
				//如果订单创建失败，删除物业费记录
				@$model->delete();
				return false;
			}
		} else {
			OthersParkingFees::model()->addError('id', "创建停车费订单失败！");
			return false;
		}
		//写订单成功日志
		Yii::log(
			"停车费下单：{$other->sn},金额:{$other->amount},用户：{$other->customer_id}",
			CLogger::LEVEL_INFO,
			'colourlife.core.OthersParkingFees.create'
		);

		//写订单日志
		OthersFeesLog::createOtherFeesLog(
			$other->id,
			'Customer',
			$other->customer_id . '停车费下单',
			Item::FEES_AWAITING_PAYMENT
		);

		//返回结果
		return true;
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

		$criteria->with[] = $this->objectModel;

//        $employee = Employee::model()->findByPk(Yii::app()->user->id);
//        $branchIds = $employee->mergeBranch;
//        //选择的组织架构ID
//        if ($this->branch_id != '') {
//            $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
//        } else {
//            if (!empty($this->communityIds)) //如果有小区
//            {
//                $community_ids = $this->communityIds;
//            } else {
//                if ($this->region != '') //如果有地区
//                {
//                    $community_ids = Region::model()->getRegionCommunity($this->region, 'id');
//                } else {
//                    $community_ids = array();
//                    foreach ($branchIds as $branchId) {
//                        $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
//                        $community_ids = array_unique(array_merge($community_ids, $data));
//                    }
//                }
//            }
//        }

		//选择的组织架构ID
		if ($this->branch_id != '')
			$community_ids = ICEBranch::model()->findByPk($this->branch_id)->ICEGetBranchAllCommunity();
		else if (!empty($this->communityIds)) //如果有小区
			$community_ids = $this->communityIds;
		else if ($this->province_id) {
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

//        if ($this->community_name != '') {
//            $community = Community::model()->findAll(
//                "name like :name",
//                array(':name' => "%" . $this->community_name . "%")
//            );
//            $communityArr = array();
//            foreach ($community as $key => $value) {
//                $communityArr[] = $value->id;
//            }
//            $criteria->addInCondition($this->objectModel . '.community_id', $communityArr);
//        }

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

		if ($this->build_name != '') {
			$buildList = Build::model()->findAll("name like :name", array(':name' => "%" . $this->build_name . "%"));
			$buildArr = array();
			foreach ($buildList as $key => $value) {
				$buildArr[] = $value->id;
			}

			$criteria->addInCondition($this->objectModel . '.build_id', $buildArr);
		}
		if ($this->room != '') {
			$criteria->compare($this->objectModel . '.room', $this->room, true);
		}

		if ($this->plate_no != '') {
			$criteria->compare($this->objectModel . '.plate_no', $this->plate_no, true);
		}
		if ($this->parking_card_number != '') {
			$criteria->compare($this->objectModel . '.parking_card_number', $this->parking_card_number, true);
		}

		if ($this->create_time != '') {
			$criteria->compare('create_time', $this->create_time);
		}

		if ($this->create_time != '') {
			$criteria->compare('create_time', $this->create_time);
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
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => '`t`.create_time desc',
			)
		));
	}

	public function getBranchName()
	{
//		if (isset($this->customer)) {
//			if (isset($this->customer->community)) {
//				if (isset($this->customer->community->branch)) {
//					//return $this->customer->community->branch->name;
//					return implode(' ', $this->getMyBranch($this->customer->community->branch->id));
//				}
//			}
//		}

//		ICE 接入
		if (isset($this->customer)) {
			if (isset($this->customer->community_id)) {
				//  ICE接入
				$community = ICECommunity::model()->findByPk($this->customer->community_id);
				if (!empty($community)) {
					return $community->branchstring;
				}
			}
		}
		return "";

	}

	public function getRegionName()
	{
//		if (isset($this->customer)) {
//			if (isset($this->customer->community)) {
//				if (isset($this->customer->community->region)) {
//					//return $this->customer->community->region->name;
//					return $this->myRegion($this->customer->community->region->id);
//				}
//			}
//		}
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
