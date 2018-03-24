<?php

/**
 * This is the model class for table "property_activity".
 *
 * The followings are the available columns in table 'property_activity':
 * @property integer $id
 * @property string $sn
 * @property integer $customer_id
 * @property integer $object_id
 * @property string $amount
 * @property string $reduction
 * @property string $earnings
 * @property string $community_rate
 * @property integer $mitigate_starttime
 * @property integer $mitigate_endtime
 * @property string $note
 * @property string $create_ip
 * @property integer $create_time
 * @property integer $status
 * @property integer $pay_time
 * @property integer $update_time
 */
class PropertyActivity extends CActiveRecord
{

	public $modelName = '冲抵物业费订单';
	public $modelNameArrear = '欠费冲抵订单';
	public $modelNameParking = '停车费冲抵订单';
	public $modelNameParkingMonth = '月卡停车费冲抵订单';
	public $modelNameDeductList = '彩富人生红包提成奖励订单';
	public $customer_name;
	public $customer_mobile;
	public $startTime;
	public $endTime;
	public $room;
	public $build;
	public $customerName;
	public $region;
	public $communityIds;
	public $branch_id;
	public $car_number;
	public $deductMonth;
	public $objectModel = 'ParkingFeesMonth';
	public $parkingFeesMonth;
	public $colorcloud_unit;
	public $new_colorcloud_unit;

	static $fees_status = array(
		Item::PROFIT_ORDER_INIT => "待付款",//0
		Item::PROFIT_ORDER_SUCCESS => "交易成功",//99
		Item::PROFIT_ORDER_AUTHORIZE => '已授权',//1
		Item::PROFIT_ORDER_CANCEL => "订单已取消",//98
		Item::PROFIT_ORDER_REFUND => '已退款',//90
		Item::PROFIT_ORDER_EXTRACT_FAIL => '提现失败',//97
		Item::PROFIT_ORDER_EXTRACT_ING => '提现中',
		Item::PROFIT_ORDER_EXTRACT_SUCCESS => '提现成功',
		Item::PROFIT_ORDER_CONTINUOUS => '已续投',
		Item::PROFIT_ORDER_REDEEM_ING => '赎回中',
		Item::PROFIT_ORDER_REDEEM_SUCCESS => '已赎回，待消单',
		Item::PROFIT_ORDER_REDEEM_FAIL => '赎回失败',
		Item::PROFIT_ORDER_REDEEM_DONE => '赎回成功'
	);


	static $sendCan_status = array(
		Item::FEES_AWAITING_PAYMENT => "待审核", //0
		Item::FEES_TRANSACTION_ERROR => '审核通过',//1
		Item::FEES_CANCEL => "审核失败",//2
	);


	static $fees_status_num = array(
		Item::FEES_AWAITING_PAYMENT => 0,
		Item::FEES_TRANSACTION_SUCCESS => 2,
		Item::FEES_CANCEL => 1,
		// Item::FEES_TRANSACTION_ERROR => 3,
		Item::FEES_TRANSACTION_ERROR => 0,
		Item::FEES_TRANSACTION_REFUND => 4,
	);


	public $province_id;
	public $city_id;
	public $district_id;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'property_activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, rate_id, model, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime', 'required', 'on' => 'create'),
			array('customer_id, object_id, rate_id, mitigate_starttime, mitigate_endtime, create_time, status, pay_time, update_time,is_receive,inviter,sendCan,cash_create_time,cash_result_time', 'numerical', 'integerOnly' => true),
			array('sn', 'length', 'max' => 32),
			array('amount, reduction, earnings, community_rate, ticheng_amount', 'length', 'max' => 10),
			array('create_ip', 'length', 'max' => 20),
			array('model', 'in', 'range' => array('PropertyActivity', 'PropertyFees', 'ParkingFees', 'ParkingFeesMonth')),
			array('rate_id', 'checkRateID'),
			array('note,remark', 'safe'),
			array('inviter_mobile', 'length', 'max' => 15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('startTime, endTime, id, sn, pay_sn, room, build, branch_id, communityIds, region, customerName, customer_id, object_id, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime, note, create_ip, create_time, status, pay_time, update_time,customer_name,customer_mobile,model,rate_id,inviter', 'safe', 'on' => 'search'),
			array('startTime, endTime, id, sn, pay_sn, room, build, branch_id, communityIds, region, customerName, customer_id, object_id, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime, note, create_ip, create_time, status, pay_time, update_time,customer_name,customer_mobile,model,rate_id,inviter', 'safe', 'on' => 'arrear_search'),
			array('startTime, endTime, id, sn, pay_sn, room, build, branch_id, communityIds, region, customerName, customer_id, object_id, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime, note, create_ip, create_time, status, pay_time, update_time,customer_name,customer_mobile,model,rate_id,inviter,car_number', 'safe', 'on' => 'parkingFees_search'),
			array('startTime, endTime, id, sn, pay_sn, room, build, branch_id, communityIds, region, customerName, customer_id, object_id, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime, note, create_ip, create_time, status, pay_time, update_time,customer_name,customer_mobile,model,rate_id,inviter,car_number', 'safe', 'on' => 'parkingFeesMonthSearch'),
			array('startTime, endTime, id, sn, pay_sn, room, build, branch_id, communityIds, region, customerName, customer_id, object_id, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime, note, create_ip, create_time, status, sendCan, pay_time, update_time,customer_name,customer_mobile,model,rate_id,inviter,car_number,deductMonth,remark', 'safe', 'on' => 'deductList_search'),
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
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'logs' => array(self::HAS_MANY, 'OthersFeesLog', 'others_fees_id'),
			'AdvanceFees' => array(self::BELONGS_TO, 'AdvanceFee', 'object_id'),
			'PropertyFees' => array(self::BELONGS_TO, 'PropertyFees', 'object_id'),
			'ParkingFeesMonth' => array(self::BELONGS_TO, 'ParkingFeesMonth', 'object_id'),
			'ParkingFees' => array(self::BELONGS_TO, 'ParkingFees', 'object_id'),
			//'ParkingFeesMonth' => array(self::BELONGS_TO, 'ParkingFeesMonth', 'object_id'),
			'PropertyActivityRate' => array(self::BELONGS_TO, 'PropertyActivityRate', 'rate_id'),
			'FeeDeductionProperty' => array(self::HAS_MANY, 'FeeDeductionProperty', 'orderID'),
			'inviterRe' => array(self::BELONGS_TO, 'Employee', array('inviter_mobile'=>'mobile')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => '订单号SN',
			'pay_sn' => '支付单号',
			'customer_id' => '用户ID',
			'object_id' => '预缴ID',
			'amount' => '投资金额',
			'reduction' => '每月减免费用',
			'earnings' => '预期收益',
			'community_rate' => '活动涨幅',
			'mitigate_starttime' => '减免开始时间（年月）',
			'mitigate_endtime' => '减免结束时间（年月）',
			'note' => '备注',
			'create_ip' => '创建IP',
			'create_time' => '下单时间',
			'status' => '状态',
			'pay_time' => '付款时间',
			'update_time' => '更新时间',
			'customer_name' => '业主姓名',
			'customer_mobile' => '业主手机',
			'startTime' => '开始时间',
			'endTime' => '结束时间',
			'room' => '房间号',
			'build' => '楼栋',
			'customerName' => '业主姓名',
			'region' => '地区',
			'communityIds' => '小区',
			'branch_id' => '管辖部门',
			'community_id' => '小区',
			'rate_id' => '彩富人生ID',
			'model' => '订单类型',
			'is_receive' => '收费系统是否收到订单',
			'car_number' => '车牌号',
			'deductMonth' => '提成月份',
			'sendCan' => '审核状态',
			'remark' => '审核备注',
			'inviter_mobile' => '推荐手机号码',
			'ticheng_amount' => '提成金额',
			'cash_create_time' => '提现时间',
			'cash_result_time' => '提现处理时间',
			'colorcloud_unit' => '彩之云门牌号',
			'new_colorcloud_unit' => '新彩之云门牌号'
		);
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => 'update_time',
				'setUpdateOnCreate' => false,
			),
			'IpBehavior' => array(
				'class' => 'common.components.behaviors.IpBehavior',
				'createAttribute' => 'create_ip',
				'updateAttribute' => null,
				'setUpdateOnCreate' => true,
			),
		);
	}

	// public function setConfigValue(array $config)
	// {
	//     $this->config = serialize($config);
	// }


	// public function getConfigValue()
	// {
	//     $config = @unserialize($this->config);
	//     if (!is_array($config))
	//         $config = array();
	//     return $config;
	// }


	// protected function afterFind()
	// {
	//     foreach ($this->getConfigValue() as $name => $value) {
	//         if (!empty($name) && in_array($name, $this->configAttributes)) {
	//             $this->$name = $value;
	//         }
	//     }
	//     return parent::afterFind();
	// }

	// protected function beforeSave()
	// {
	//     // if ($this->isNewRecord) {
	//     //     $this->getSavedData();
	//     // }else{
	//         //$this->setConfigValue($this->getAttributes($this->configAttributes));
	//         // $this->getSavedDataN();
	//     // }
	//     $this->setConfigValue($this->getAttributes($this->configAttributes));
	//     return parent::beforeSave();
	// }

	// protected function getSavedData()
	// {
	//     return $this->config = serialize(array(
	//         'January'=>$this->January=0,
	//         'February'=>$this->February=0,
	//         'March'=>$this->March=0,
	//         'April'=>$this->April=0,
	//         'May'=>$this->May=0,
	//         'June'=>$this->June=0,
	//         'July'=>$this->July=0,
	//         'August'=>$this->August=0,
	//         'September'=>$this->September=0,
	//         'October'=>$this->October=0,
	//         'November'=>$this->November=0,
	//         'December'=>$this->December=0,
	//     ));
	// }


	// protected function getSavedDataN()
	// {
	//     return $this->config = serialize(array(
	//         'January'=>$this->January,
	//         'February'=>$this->February,
	//         'March'=>$this->March,
	//         'April'=>$this->April,
	//         'May'=>$this->May,
	//         'June'=>$this->June,
	//         'July'=>$this->July,
	//         'August'=>$this->August,
	//         'September'=>$this->September,
	//         'October'=>$this->October,
	//         'November'=>$this->November,
	//         'December'=>$this->December,
	//     ));
	// }


	public function checkRateID($attribute, $params)
	{
		if (!$this->hasErrors() && !empty($this->rate_id)) {
			$model = PropertyActivityRate::model()->findByPk($this->rate_id);
			if (!$this->hasErrors() && empty($model)) {
				$this->addError($attribute, "彩富人生ID错误！");
				// throw new CHttpException(400, '彩富人生ID错误！');
			}
		}
	}


	public static function checkIsExist($mobile)
	{
		return Employee::model()->find('mobile=:mobile', array(':mobile' => $mobile));
	}


	public static function checkIsExistByName($username)
	{
		return Employee::model()->find('username=:username', array(':username' => $username));
	}


	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;
		$criteria->with[] = 'AdvanceFees';
		if ($this->room != '') {
			$criteria->compare('AdvanceFees.room', $this->room, true);
		}
		if ($this->colorcloud_unit != '') {
			$criteria->compare('AdvanceFees.colorcloud_unit', $this->colorcloud_unit, true);
		}
		if ($this->new_colorcloud_unit != '') {
			$criteria->compare('AdvanceFees.new_colorcloud_unit', $this->new_colorcloud_unit, true);
		}
		if ($this->build != '') {
			$criteria->compare('AdvanceFees.build', $this->build, true);
		}
		if ($this->customerName != '') {
			$criteria->compare('AdvanceFees.customer_name', $this->customerName, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">= " . strtotime($this->startTime . " 00:00:00"));
		}
		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "<= " . strtotime($this->endTime . " 23:59:59"));
		}

//		$employee = Employee::model()->findByPk(Yii::app()->user->id);
//		$branchIds = $employee->mergeBranch;
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


		if (Yii::app()->user->getId() != 1) {
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
				// TODO
				/*$employee = Employee::model()->findByPk(Yii::app()->user->id);
				$branchIds = $employee->mergeBranch;
				$community_ids = array();
				foreach ($branchIds as $branchId) {
					$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
					$community_ids = array_unique(array_merge($community_ids, $data));
				}*/
//			$community_ids = array();
				$employee = ICEEmployee::model()->findByPk(Yii::app()->user->id);
				$community_ids = $employee->ICEGetOrgCommunity();
			}
			$criteria->addInCondition('AdvanceFees.community_id', $community_ids);
		}

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.sn', $this->sn, true);
		$criteria->compare('`t`.pay_sn', $this->pay_sn, true);
		$criteria->compare('`t`.customer_id', $this->customer_id);
		$criteria->compare('`t`.object_id', $this->object_id);
		$criteria->compare('`t`.model', 'PropertyActivity');
		$criteria->compare('`t`.amount', $this->amount, true);
		$criteria->compare('`t`.reduction', $this->reduction, true);
		$criteria->compare('`t`.earnings', $this->earnings, true);
		$criteria->compare('`t`.community_rate', $this->community_rate, true);
		$criteria->compare('`t`.mitigate_starttime', $this->mitigate_starttime);
		$criteria->compare('`t`.mitigate_endtime', $this->mitigate_endtime);
		$criteria->compare('`t`.note', $this->note, true);
		$criteria->compare('`t`.create_ip', $this->create_ip, true);
		$criteria->compare('`t`.create_time', $this->create_time);
		$criteria->compare('`t`.status', $this->status);
		$criteria->compare('`t`.pay_time', $this->pay_time);
		$criteria->compare('`t`.update_time', $this->update_time);
		if ($this->customer_name || $this->customer_mobile) {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.name', $this->customer_name, true);
			$criteria->compare('customer.mobile', $this->customer_mobile, true);
		}
		//$criteria->order ='`t`.create_time desc';
		return new ActiveDataProvider($this, array('criteria' => $criteria, 'sort' =>
			array('defaultOrder' => '`t`.create_time desc',)));

	}


	//提成奖励搜索

	public function deductList_search()
	{
		$criteria = new CDbCriteria;
		$criteria->with[] = 'AdvanceFees';
		if ($this->room != '') {
			$criteria->compare('AdvanceFees.room', $this->room, true);
		}

		if ($this->build != '') {
			$criteria->compare('AdvanceFees.build', $this->build, true);
		}
		if ($this->customerName != '') {
			$criteria->compare('AdvanceFees.customer_name', $this->customerName, true);
		}

		if ($this->deductMonth != '') {
			$time = $this->deductMonth . '-01';
			$criteria->compare("`t`.mitigate_endtime", ">= " . strtotime($time . "+1 month"));
			$criteria->compare("`t`.mitigate_starttime", "<= " . strtotime($time . " 00:00:00"));
		}

		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		$branchIds = $employee->mergeBranch;
		//选择的组织架构ID
		if ($this->branch_id != '')
			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
		else if (!empty($this->communityIds)) //如果有小区
			$community_ids = $this->communityIds;
		else if ($this->region != '') //如果有地区
			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');
		else {
			$community_ids = array();
			foreach ($branchIds as $branchId) {
				$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
				$community_ids = array_unique(array_merge($community_ids, $data));
			}
		}
		$criteria->addInCondition('AdvanceFees.community_id', $community_ids);

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.sn', $this->sn, true);
		$criteria->compare('`t`.pay_sn', $this->pay_sn, true);
		$criteria->compare('`t`.customer_id', $this->customer_id);
		$criteria->compare('`t`.object_id', $this->object_id);
		$criteria->compare('`t`.model', 'PropertyActivity');
		$criteria->compare('`t`.amount', $this->amount, true);
		$criteria->compare('`t`.reduction', $this->reduction, true);
		$criteria->compare('`t`.earnings', $this->earnings, true);
		$criteria->compare('`t`.community_rate', $this->community_rate, true);
		$criteria->compare('`t`.mitigate_starttime', $this->mitigate_starttime);
		$criteria->compare('`t`.mitigate_endtime', $this->mitigate_endtime);
		$criteria->compare('`t`.note', $this->note, true);
		$criteria->compare('`t`.create_ip', $this->create_ip, true);
		$criteria->compare('`t`.create_time', $this->create_time);
		$criteria->compare('`t`.status', Item::FEES_TRANSACTION_SUCCESS);
		$criteria->compare('`t`.sendCan', $this->sendCan);
		$criteria->compare('`t`.pay_time', $this->pay_time);
		$criteria->compare('`t`.update_time', $this->update_time);
		$criteria->compare("`t`.inviter", "<> 0");
		$criteria->compare("`t`.remark", $this->note, true);
		if ($this->customer_name || $this->customer_mobile) {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.name', $this->customer_name, true);
			$criteria->compare('customer.mobile', $this->customer_mobile, true);
		}
		return new ActiveDataProvider($this,
			array(
				'criteria' => $criteria,
				'sort' => array(
					'defaultOrder' => '`t`.create_time desc',
				)
			)
		);
	}


	public function arrear_search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;
		$criteria->with[] = 'PropertyFees';
		if ($this->room != '') {
			$criteria->compare('PropertyFees.room', $this->room, true);
		}
		if ($this->build != '') {
			$criteria->compare('PropertyFees.build', $this->build, true);
		}
		if ($this->customerName != '') {
			$criteria->compare('PropertyFees.customer_name', $this->customerName, true);
		}
		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">= " . strtotime($this->startTime . " 00:00:00"));
		}
		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "<= " . strtotime($this->endTime . " 23:59:59"));
		}

//		$employee = Employee::model()->findByPk(Yii::app()->user->id);
//		$branchIds = $employee->mergeBranch;
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


		if (Yii::app()->user->getId() != 1) {
			//选择的组织架构ID
			if ($this->branch_id != '')
				//$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
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
				// TODO
				/*$employee = Employee::model()->findByPk(Yii::app()->user->id);
				$branchIds = $employee->mergeBranch;
				$community_ids = array();
				foreach ($branchIds as $branchId) {
					$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
					$community_ids = array_unique(array_merge($community_ids, $data));
				}*/
//			$community_ids = array();
				$employee = ICEEmployee::model()->findByPk(Yii::app()->user->id);
				$community_ids = $employee->ICEGetOrgCommunity();
			}

			// var_dump($community_ids);die;
			$criteria->addInCondition('PropertyFees.community_id', $community_ids);
		}

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.model', $this->id);
		$criteria->compare('`t`.sn', $this->sn, true);
		$criteria->compare('`t`.pay_sn', $this->pay_sn, true);
		$criteria->compare('`t`.customer_id', $this->customer_id);
		$criteria->compare('`t`.object_id', $this->object_id);
		$criteria->compare('`t`.model', 'PropertyFees');
		$criteria->compare('`t`.amount', $this->amount, true);
		$criteria->compare('`t`.reduction', $this->reduction, true);
		$criteria->compare('`t`.earnings', $this->earnings, true);
		$criteria->compare('`t`.community_rate', $this->community_rate, true);
		$criteria->compare('`t`.mitigate_starttime', $this->mitigate_starttime);
		$criteria->compare('`t`.mitigate_endtime', $this->mitigate_endtime);
		$criteria->compare('`t`.note', $this->note, true);
		$criteria->compare('`t`.create_ip', $this->create_ip, true);
		$criteria->compare('`t`.create_time', $this->create_time);
		$criteria->compare('`t`.status', $this->status);
		$criteria->compare('`t`.pay_time', $this->pay_time);
		$criteria->compare('`t`.update_time', $this->update_time);
		if ($this->customer_name || $this->customer_mobile) {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.name', $this->customer_name, true);
			$criteria->compare('customer.mobile', $this->customer_mobile, true);
		}
		//$criteria->order ='`t`.create_time desc';


		return new ActiveDataProvider($this, array('criteria' => $criteria, 'sort' =>
			array('defaultOrder' => '`t`.create_time desc',)));
	}

	public function parkingFeesMonthSearch()
	{

		$criteria = new CDbCriteria;
		$criteria->with[] = 'ParkingFeesMonth';
		if ($this->room != '') {
			$criteria->compare('ParkingFeesMonth.room', $this->room, true);
		}
		if ($this->build != '') {
			$criteria->compare('ParkingFeesMonth.build', $this->build, true);
		}
//        if($this->customerName != ''){
//            $criteria->compare('ParkingFeesMonth.customer_name',$this->customerName,true);
//        }
//        if($this->third_park_type != ''){
//            $criteria->compare('ParkingFeesMonth.third_park_type',$this->third_park_type,true);
//        }
		if ($this->car_number != '') {
			$criteria->compare('ParkingFeesMonth.car_number', $this->car_number, true);
		}
		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime . "00:00:00"));
		}
		if ($this->endTime) {
			$criteria->compare("`t`.create_time", "<=" . strtotime($this->endTime . "23:59:59"));
		}

//		$employee = Employee::model()->findByPk(Yii::app()->user->id);
//		$branchIds = $employee->mergeBranch;
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
		} else if (!empty($this->communityIds)) //如果有小区
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
			// TODO
			/*$employee = Employee::model()->findByPk(Yii::app()->user->id);
			$branchIds = $employee->mergeBranch;
			$community_ids = array();
			foreach ($branchIds as $branchId) {
				$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
				$community_ids = array_unique(array_merge($community_ids, $data));
			}*/
//			$community_ids = array();
			$employee = ICEEmployee::model()->findByPk(Yii::app()->user->id);
			$community_ids = $employee->ICEGetOrgCommunity();
		}

		$criteria->addInCondition('ParkingFeesMonth.community_id', $community_ids);
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.model', $this->id);
		$criteria->compare('`t`.sn', $this->sn, true);
		$criteria->compare('`t`.pay_sn', $this->pay_sn, true);
		$criteria->compare('`t`.customer_id', $this->customer_id);
		$criteria->compare('`t`.object_id', $this->object_id);
		$criteria->compare('`t`.model', 'ParkingFeesMonth');
		$criteria->compare('`t`.amount', $this->amount, true);
		$criteria->compare('`t`.reduction', $this->reduction, true);
		$criteria->compare('`t`.earnings', $this->earnings, true);
		$criteria->compare('`t`.community_rate', $this->community_rate, true);
		$criteria->compare('`t`.mitigate_starttime', $this->mitigate_starttime);
		$criteria->compare('`t`.mitigate_endtime', $this->mitigate_endtime);
		$criteria->compare('`t`.note', $this->note, true);
		$criteria->compare('`t`.create_ip', $this->create_ip, true);
		$criteria->compare('`t`.create_ip', $this->create_ip, true);
		$criteria->compare('`t`.status', $this->status);
		$criteria->compare('`t`.pay_time', $this->pay_time);
		$criteria->compare('`t`.update_time', $this->update_time);
		if ($this->customer_name || $this->customer_mobile) {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.name', $this->customer_name, true);
			$criteria->compare('customer.mobile', $this->customer_mobile, true);
		}
		//$criteria->order ='`t`.create_time desc';
		return new ActiveDataProvider($this, array('criteria' => $criteria, 'sort' =>
			array('defaultOrder' => '`t`.create_time desc',)));
	}

	public function parkingFees_search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;
		$criteria->with[] = 'ParkingFees';
		if ($this->room != '') {
			$criteria->compare('ParkingFees.room', $this->room, true);
		}
		// if ($this->build != '') {
		//     $criteria->compare('ParkingFees.build', $this->build, true);
		// }
		// if ($this->customerName != '') {
		//     $criteria->compare('ParkingFees.customer_name', $this->customerName, true);
		// }

		if ($this->car_number != '') {
			$criteria->compare('ParkingFees.car_number', $this->car_number, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">= " . strtotime($this->startTime . " 00:00:00"));
		}
		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "<= " . strtotime($this->endTime . " 23:59:59"));
		}

		//$employee = Employee::model()->findByPk(Yii::app()->user->id);
		//$branchIds = $employee->mergeBranch;
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

		if (Yii::app()->user->getId() != 1) {
			//选择的组织架构ID
			if ($this->branch_id != '')
				//$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
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
				// TODO
				/*$employee = Employee::model()->findByPk(Yii::app()->user->id);
				$branchIds = $employee->mergeBranch;
				$community_ids = array();
				foreach ($branchIds as $branchId) {
					$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
					$community_ids = array_unique(array_merge($community_ids, $data));
				}*/
				//$community_ids = array();
				$employee = ICEEmployee::model()->findByPk(Yii::app()->user->id);
				$community_ids = $employee->ICEGetOrgCommunity();
			}
			$criteria->addInCondition('ParkingFees.community_id', $community_ids);
		}

		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.model', $this->id);
		$criteria->compare('`t`.sn', $this->sn, true);
		$criteria->compare('`t`.pay_sn', $this->pay_sn, true);
		$criteria->compare('`t`.customer_id', $this->customer_id);
		$criteria->compare('`t`.object_id', $this->object_id);
		$criteria->compare('`t`.model', 'ParkingFees');
		$criteria->compare('`t`.amount', $this->amount, true);
		$criteria->compare('`t`.reduction', $this->reduction, true);
		$criteria->compare('`t`.earnings', $this->earnings, true);
		$criteria->compare('`t`.community_rate', $this->community_rate, true);
		$criteria->compare('`t`.mitigate_starttime', $this->mitigate_starttime);
		$criteria->compare('`t`.mitigate_endtime', $this->mitigate_endtime);
		$criteria->compare('`t`.note', $this->note, true);
		$criteria->compare('`t`.create_ip', $this->create_ip, true);
		$criteria->compare('`t`.create_ip', $this->create_ip, true);
		$criteria->compare('`t`.status', $this->status);
		$criteria->compare('`t`.pay_time', $this->pay_time);
		$criteria->compare('`t`.update_time', $this->update_time);
		if ($this->customer_name || $this->customer_mobile) {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.name', $this->customer_name, true);
			$criteria->compare('customer.mobile', $this->customer_mobile, true);
		}
		//$criteria->order ='`t`.create_time desc';


		return new ActiveDataProvider($this, array('criteria' => $criteria, 'sort' =>
			array('defaultOrder' => '`t`.create_time desc',)));
	}


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PropertyActivity the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * 创建投资理财减免物业费订单
	 * 参数说明：
	 * @feeAttr:PropertyActivity模型的属性集合
	 * @advanceAttr:AdvanceFees模型的属性集合
	 * 返回值 boolean, true:创建成功，false:创建失败
	 * */
	public static function createAdvanceFeeOrder($feeAttr, $advanceAttr)
	{
		//判断参数
		if (empty($feeAttr) || empty($advanceAttr)) {
			PropertyActivity::model()->addError('id', "创建冲抵物业费订单失败！");
			return false;
		}

		//创建我们的订单记录及预缴费记录
		$other = new PropertyActivity();
		$other->attributes = $feeAttr;

		$model = new AdvanceFee();
		$model->attributes = $advanceAttr;

		if ($model->save()) { //先创建预缴费详情记录，得到记录ID再创建订单记录
			$other->object_id = $model->id;
			if (!$other->save()) {
				PropertyActivity::model()->addError('id', "创建冲抵物业费订单失败！");
				//如果订单创建失败，删除预缴费费记录
				@$model->delete();
				return false;
			} else {
				$note = ($model->customer_name . '(' . $other->customer_id . ')冲抵物业费下单,金额:' . $other->amount);
				//写订单日志
				OthersFeesLog::createOtherFeesLog($other->id, 'PropertyActivity', Item::FEES_AWAITING_PAYMENT, $note);
			}
			Yii::log("冲抵物业费下单：{$other->sn},金额:{$other->amount},用户：{$model->customer_name}({$other->customer_id})",
				CLogger::LEVEL_INFO, 'colourlife.core.advancefee.create');
		} else {
			PropertyActivity::model()->addError('id', "创建冲抵物业费订单失败！");
			return false;
		}
		//写订单成功日志
		Yii::log("冲抵物业费下单：{$other->sn},金额:{$other->amount},用户：{$model->customer_name}({$other->customer_id})",
			CLogger::LEVEL_INFO, 'colourlife.core.advancefee.create');

		//返回结果
		return true;

	}


	/**
	 * 创建欠费冲抵订单
	 * 参数说明：
	 * @feeAttr:PropertyActivity模型的属性集合
	 * @advanceAttr:AdvanceFees模型的属性集合
	 * 返回值 boolean, true:创建成功，false:创建失败 20141210
	 * */
	public static function createPropertyFeeOrder($feeAttr, $propertyAttr)
	{
		//判断参数
		if (empty($feeAttr) || empty($propertyAttr)) {
			PropertyActivity::model()->addError('id', "创建冲抵物业费订单失败！");
			return false;
		}

		//创建我们的订单记录及预缴费记录
		$other = new PropertyActivity();
		$other->attributes = $feeAttr;

		$model = new PropertyFees();
		$model->attributes = $propertyAttr;

		if ($model->save()) { //先创建预缴费详情记录，得到记录ID再创建订单记录
			$other->object_id = $model->id;
			if (!$other->save()) {
				PropertyActivity::model()->addError('id', "创建冲抵物业费订单失败！");
				//如果订单创建失败，删除预缴费费记录
				@$model->delete();
				return false;
			} else {
				$note = ($model->customer_name . '(' . $other->customer_id . ')冲抵往期物业费下单,金额:' . $other->amount);
				//写订单日志
				PropertyFeeLog::createFeeLog($other->id, 'PropertyActivity', Item::FEES_AWAITING_PAYMENT, $note);
			}
			Yii::log("冲抵物业费下单：{$other->sn},金额:{$other->amount},用户：{$model->customer_name}({$other->customer_id})",
				CLogger::LEVEL_INFO, 'colourlife.core.propertyfee.create');
		} else {
			PropertyActivity::model()->addError('id', "创建冲抵物业费订单失败！");
			return false;
		}
		//写订单成功日志
		Yii::log("冲抵往期物业费下单：{$other->sn},金额:{$other->amount},用户：{$model->customer_name}({$other->customer_id})",
			CLogger::LEVEL_INFO, 'colourlife.core.propertyfee.create');

		//返回结果
		return true;

	}


	/**
	 * 创建欠费冲抵物业费订单
	 * 参数说明：1、feeAttr:OthersFees模型的属性集合，2、propertyAttr:PropertyFees模型的属性集合
	 * 返回值 boolean, true:创建成功，false:创建失败
	 * yichao by 2013年8月8日 10:37:50
	 * */
	public static function createPropertyOrder($feeAttr, $propertyAttr)
	{
		if (empty($feeAttr) || empty($propertyAttr)) {
			PropertyActivity::model()->addError('id', "创建欠费冲抵订单失败！");
			return false;
		}
		// var_dump($feeAttr);die;
		$comm = Community::model()->findByPk(intval($feeAttr['community_id']));
		// var_dump($comm);die;
		$uuid = empty($comm) ? null : empty($comm->colorcloudCommunity) ? null : $comm->colorcloudCommunity[0]->color_community_id;
		// $uuid = empty($advanceFee->PropertyFees) ? null : empty($advanceFee->PropertyFees->community->colorcloudCommunity)?null:$PropertyFees->PropertyFees->community->colorcloudCommunity[0]->color_community_id;
		//引入彩之云的接口
		//Yii::import('common.api.ColorCloudApi');
		Yii::import('common.api.IceApi');
		//实例化
		//$coloure = ColorCloudApi::getInstance();
		$coloure = IceApi::getInstance();

		//创建我们的物业费订单前，需要先创建彩之云的订单，
		//创建彩之云订单前写系统日志，记录参数
		Yii::log('彩之云下订单：传出参数  ,colorcloud_id ' . $propertyAttr['colorcloud_id'] .
			'  ,colorcloud_building ' . $propertyAttr['colorcloud_building'] .
			'  ,colorcloud_unit ' . $propertyAttr['colorcloud_unit'] .
			'  ,colorcloud_bills ' . $propertyAttr['colorcloud_bills'] .
			'  ,uuid ' . $uuid,
			CLogger::LEVEL_INFO,
			'colourlife.core.PropertyActivity.createPropertyOrder');

		//使用彩之云的接口创建彩之云的缴费订单
		$result = $coloure->callGetOrderCreate($propertyAttr['colorcloud_id'], $propertyAttr['colorcloud_building'],
			$propertyAttr['colorcloud_unit'], $propertyAttr['colorcloud_bills'], '', $uuid);
		//系统日志记录彩之云订单创建情况
		Yii::log('彩之云下订单返回值：' . var_export($result, true), CLogger::LEVEL_INFO,
			'colourlife.core.PropertyActivity.createPropertyOrder');

		//处理彩之云订单返回结果
		if (!isset($result) || $result['code'] != 0 || $result['content']['state'] != 1) {
			Yii::log('彩之云订单创建失败！返回信息：' . $result['content']['msg'], CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createPropertyOrder');
			PropertyActivity::model()->addError('id', $result['content']['msg']);
			return false;
			//throw new CHttpException(400, '彩之云订单创建失败！');
		}
		//如果收费系统返回的金额为0,那么不创建我们的订单。直接报错
		if ($result['content']['orderamount'] <= 0) {
			Yii::log('ERP订单号:' . $result['content']['orderid'] . '，但返回金额为' . $result['content']['orderamount'] . ',不能创建我们的订单！',
				CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.createPropertyOrder');
			PropertyActivity::model()->addError('id', "彩之云收费系统异常,创建订单失败！" . $result['content']['msg']);
			return false;
		}

		//创建我们的订单记录及物业费缴费记录
		$other = new PropertyActivity();
		//用彩之云返回的订单金额替换我们计算的金额
		$oldAmount = $feeAttr['reduction'];

		$feeAttr['erp_reduction'] = $result['content']['orderamount'];
		// var_dump($feeAttr);die;
		$other->attributes = $feeAttr;

		Yii::log("使用ERP返回金额替换我们订单的金额（欠缴冲抵实际没有替换，这里只是记录）。欠费冲抵订单:{$other->sn},彩之云订单:{$result['content']['orderid']},我们的订单金额 :{$oldAmount},替换后的金额:{$feeAttr['erp_reduction']}", CLogger::LEVEL_INFO, 'colourlife.core.propertyfees.create');

		$propertyAttr['colorcloud_order'] = $result['content']['orderid'];
		$model = new PropertyFees();
		$model->attributes = $propertyAttr;

		if ($model->save()) { //业费记录，得到记录ID再创建订单记录
			$other->object_id = $model->id;
			if (!$other->save()) {
				// var_dump($other->getErrors());die;
				PropertyActivity::model()->addError('id', "创建欠费冲抵订单失败！" . json_encode($other->getErrors()));
				//如果订单创建失败，删除物业费记录
				@$model->delete();
				return false;
			} else {
				//写订单日志
				OthersFeesLog::createOtherFeesLog($other->id, 'PropertyFees', Item::FEES_AWAITING_PAYMENT, ($other->customer_id . '欠费冲抵下单'));
			}
		} else {
			PropertyActivity::model()->addError('id', "创建欠费冲抵订单失败！" . json_encode($model->getErrors()));
			return false;
		}
		//写订单成功日志
		Yii::log("欠费冲抵下单：{$other->sn},金额:{$other->amount},用户：{$other->customer_id}",
			CLogger::LEVEL_INFO, 'colourlife.core.propertyfees.create');

		//返回结果
		return true;

	}


	/**
	 * 创建停车费订单
	 * 参数说明：1、activityAttr:PropertyActivity模型的属性集合，2、parkingAttr:ParkingFees模型的属性集合
	 * 返回值 boolean, true:创建成功，false:创建失败
	 * yichao by 2013年8月9日 14:40:04
	 * */
	public static function createParkingOrder($activityAttr, $parkingAttr)
	{
		if (empty($activityAttr) || empty($parkingAttr)) {
			PropertyActivity::model()->addError('id', "创建停车费订单失败！");
			return false;
		}

		//创建订单记录及物业费缴费记录
		$other = new PropertyActivity();
		$other->attributes = $activityAttr;

		$model = new ParkingFees();
		$model->attributes = $parkingAttr;

		$parking = new ParkingAddress('newApiCreate');
		$parking->attributes = $parkingAttr;
		if (!$parking->checkParkingAddress()) {
			$parking->customer_id = Yii::app()->user->id;
			$parking->is_activity = 1;
			$parking->save();
		}

		if ($model->save()) { //先创建物业费记录，得到记录ID再创建订单记录
			$other->object_id = $model->id;
			if (!$other->save()) {
				PropertyActivity::model()->addError('id', "创建冲抵停车费订单失败！");
				//如果订单创建失败，删除停车费记录
				@$model->delete();
				return false;
			}
		} else {
			PropertyActivity::model()->addError('id', "创建冲抵停车费订单失败！");
			return false;
		}
		//写订单成功日志
		Yii::log("冲抵停车费下单：{$other->sn},金额:{$other->amount},用户：{$other->customer_id}", CLogger::LEVEL_INFO, 'colourlife.core.parkingFees.create');

		//写订单日志
		OthersFeesLog::createOtherFeesLog($other->id, 'Customer', $other->customer_id . '冲抵停车费下单', Item::FEES_AWAITING_PAYMENT);

		//返回结果
		return true;
	}


	/**
	 * 提现状态保存
	 * @param $order_id
	 * @param $state 订单的状态
	 * @param $pay_time 提现处理时间
	 * @param $note 备注
	 * @return bool
	 */
	public function SaveCashOrderActivity($order_id, $state, $pay_time)
	{
		if (empty($order_id) || empty($state) || empty($pay_time)) {
			return false;
		}
		$advanceFee = PropertyActivity::model()->findByPk($order_id);
		if (empty($advanceFee)) {
			PropertyActivity::model()->addError('id', "未知的彩富人生订单！");
			return false;
		}

		//修改我们的订单失败
		if (!PropertyActivity::model()->updateByPk($order_id, array('status' => $state, 'cash_result_time' => $pay_time))) {
			Yii::log('失败：提现成功回调修改彩之云订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态失败！', CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.SaveCashOrderActivity');
			PropertyActivity::model()->addError('id', "提现成功后更新彩之云订单失败！");
			return false;
		} else {
			Yii::log('成功：提现成功回调修改彩之云订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态成功！', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.SaveCashOrderActivity');
		}

		return true;
	}


	/**
	 * 更改提现状态为已经提现
	 * @param $order_id
	 * @param $state 订单的状态
	 * @param $pay_time 提现处理时间
	 * @param $note 备注
	 * @return bool
	 */
	public function SaveCashOrderActivityAlready($order_id, $state, $pay_time)
	{
		if (empty($order_id) || empty($state) || empty($pay_time)) {
			return false;
		}
		$advanceFee = PropertyActivity::model()->findByPk($order_id);
		if (empty($advanceFee)) {
			PropertyActivity::model()->addError('id', "未知的彩富人生订单！");
			return false;
		}

		//修改我们的订单失败
		if (!PropertyActivity::model()->updateByPk($order_id, array('status' => $state, 'cash_create_time' => $pay_time))) {
			Yii::log('失败：更改提现状态为（已提现）失败，订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态失败！', CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.SaveCashOrderActivity');
			PropertyActivity::model()->addError('id', "提现成功后更新彩之云订单失败！");
			return false;
		} else {
			Yii::log('成功：更改提现状态为（已提现）成功，订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态成功！', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.SaveCashOrderActivity');
		}

		return true;
	}






	//OLD
	// /**
	//  * 零物业费订单支付成功回调，函数将调用彩之云接口创建ERP的预缴费订单
	//  * @param $order_id 我们的预缴费订单ID,propertyactivity的Id
	//  * @param $state 订单的状态
	//  * @param $pay_time 付款时间
	//  * @param $note 备注
	//  * @return bool
	//  */
	// public function NewSetAdvanceSavefee($order_id, $state, $pay_time, $note, $pay_orderSn, $orderModel)
	// {
	//     if (empty($order_id) || empty($state) || empty($pay_time) || empty($pay_orderSn) || empty($orderModel)) {
	//         return false;
	//     }
	//     $advanceFee = PropertyActivity::model()->findByPk($order_id);
	//     if (empty($advanceFee)) {
	//         PropertyActivity::model()->addError('id', "未知的预缴费订单！");
	//         return false;
	//     }

	//     $ticheng_amount = F::price_formatNew($advanceFee->amount*Yii::app()->config->caifuTichengRate/12*$advanceFee->PropertyActivityRate->month);

	//     //修改我们的订单失败
	//     if (!PropertyActivity::model()->updateByPk($order_id, array('status' => $state, 'pay_time' => $pay_time, 'update_time'=>time(), 'pay_sn'=>$pay_orderSn,'ticheng_amount'=>$ticheng_amount))) {
	//         Yii::log('失败：付款成功回调修改彩之云订单【id：'.$order_id.',sn：'.$advanceFee->sn.'】状态失败！', CLogger::LEVEL_INFO,'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
	//         PropertyActivity::model()->addError('id', "付款成功后更新彩之云订单失败！");
	//         return false;
	//     }else{
	//         Yii::log('成功：付款成功回调修改彩之云订单【id：'.$order_id.',sn：'.$advanceFee->sn.'】状态成功！', CLogger::LEVEL_INFO,
	//             'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
	//     }

	//     $this->active($advanceFee);//活动，已做异常处理

	//     OthersFeesLog::createOtherFeesLog($order_id, 'PropertyActivity', $state, $note);

	//     if($orderModel=='PropertyActivity'){
	//         $unitid = empty($advanceFee->AdvanceFees) ? null : $advanceFee->AdvanceFees->colorcloud_unit;
	//     }
	//     if($orderModel=='PropertyFees'){
	//        $unitid = empty($advanceFee->PropertyFees) ? null : $advanceFee->PropertyFees->colorcloud_unit;
	//     }
	//     $activityRate = PropertyActivityRate::model()->findByPk($advanceFee->rate_id);
	//     if(empty($activityRate)){
	//         return false;
	//     }
	//     if($orderModel=='PropertyActivity'){
	//         $payfee = ($advanceFee->reduction)*$activityRate->month;
	//     }
	//     if($orderModel=='PropertyFees'){
	//        $payfee = $advanceFee->reduction;
	//     }
	//     $actfee = $actmoney = $payfee;
	//     $discount = 1;
	//     $year = date('Y',$advanceFee->mitigate_starttime);
	//     $month = intval(date('m',$advanceFee->mitigate_starttime));
	//     $receiptnumber = $advanceFee->sn;
	//     $flag = $advanceFee->model=='PropertyActivity'?0:1;

	//     //判断彩之云需要的必填参数
	//     if (empty($unitid) || empty($receiptnumber) || empty($payfee) || empty($actfee) || empty($discount) || empty($year) || empty($month)) {
	//         PropertyActivity::model()->addError('id', "创建ERP预缴费订单失败！");
	//         return false;
	//     }
	//     //引入彩之云的接口
	//     Yii::import('common.api.ColorCloudApi');
	//     //实例化
	//     $coloure = ColorCloudApi::getInstance();

	//     //创建我们的物业费订单前，需要先创建彩之云的订单，
	//     //创建彩之云订单前写系统日志，记录参数
	//     Yii::log('创建彩之云ERP预缴费订单:参数  ,unitid: ' . $unitid . ', receiptnumber: ' . $receiptnumber . ', flag: ' . $flag . ', payfee: ' . $payfee . ', actfee: ' . $actfee . ', actmoney: ' . $actmoney . ', year: ' . $year . ', month: ' . $month . ', discount: ' . $discount . ', note: ' . $note,
	//         CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.createAdvanceFeeOrder');

	//     //使用彩之云的接口创建彩之云的缴费订单
	//     $result = $coloure->callSetAdvanceSaveAppFee($unitid, $receiptnumber, $flag, $payfee, $actfee, $actmoney, $year, $month, $discount,$note);

	//     //系统日志记录彩之云订单创建情况
	//     Yii::log('创建ERP冲抵物业费订单返回值：' . var_export($result, true).'======注：彩之云订单信息【id：'.$order_id.',sn：'.$advanceFee->sn.'】', CLogger::LEVEL_INFO,
	//         'colourlife.core.PropertyActivity.createAdvanceFeeOrder');

	//     //处理彩之云订单返回结果
	//     if (!isset($result) || $result['total'] <= 0) {
	//         Yii::log('失败：ERP订单创建失败！返回信息：' . $result['error'].'=====注：彩之云订单信息【id：'.$order_id.',sn：'.$advanceFee->sn.'】', CLogger::LEVEL_INFO,
	//             'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
	//         PropertyActivity::model()->addError('id', $result['error']);
	//         return false;
	//     }else{
	//         Yii::log('成功：收费系统成功接收到冲抵物业费数据。注：彩之云订单信息【id：'.$order_id.',sn：'.$advanceFee->sn.'】', CLogger::LEVEL_INFO,
	//             'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
	//         //调用ERP成功写入房间号
	//         PropertyFeeLog::createFeeLog($advanceFee->customer_id,$unitid);
	//     }


	//     if (!PropertyActivity::model()->updateByPk($order_id, array('is_receive'=>1))) {
	//         Yii::log('失败：更改订单字段is_receive为1失败！', CLogger::LEVEL_INFO,'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
	//         PropertyActivity::model()->addError('id', "ERP冲抵物业费订单创建成功，更新彩之云订单is_receive失败！");
	//         return false;
	//     }else{
	//         Yii::log('成功：更改订单字段is_receive为1成功！', CLogger::LEVEL_INFO,'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
	//     }


	//     return true;
	// }


	//ICE
	/**
	 * 零物业费订单支付成功回调，函数将调用彩之云接口创建ERP的预缴费订单
	 * @param $order_id 我们的预缴费订单ID,propertyactivity的Id
	 * @param $state 订单的状态
	 * @param $pay_time 付款时间
	 * @param $note 备注
	 * @return bool
	 */
	public function NewSetAdvanceSavefee($order_id, $state, $pay_time, $note, $pay_orderSn, $orderModel)
	{

		if (empty($order_id) || empty($state) || empty($pay_time) || empty($pay_orderSn) || empty($orderModel)) {
			return false;
		}
		$advanceFee = PropertyActivity::model()->findByPk($order_id);
		if (empty($advanceFee)) {
			PropertyActivity::model()->addError('id', "未知的预缴费订单！");
			return false;
		}

		$ticheng_amount = F::price_formatNew($advanceFee->amount * Yii::app()->config->caifuTichengRate / 12 * $advanceFee->PropertyActivityRate->month);

		//修改我们的订单失败
		if (!PropertyActivity::model()->updateByPk($order_id, array('status' => $state, 'pay_time' => $pay_time, 'update_time' => time(), 'pay_sn' => $pay_orderSn, 'ticheng_amount' => $ticheng_amount))) {
			Yii::log('失败：付款成功回调修改彩之云订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态失败！', CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.NewSetAdvanceSavefee');
			PropertyActivity::model()->addError('id', "付款成功后更新彩之云订单失败！");
			return false;
		} else {
			Yii::log('成功：付款成功回调修改彩之云订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态成功！', CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.NewSetAdvanceSavefee');
		}

		$this->active($advanceFee);//活动，已做异常处理
		OthersFeesLog::createOtherFeesLog($order_id, 'PropertyActivity', $state, $note);

		// $switch = 0;
		// if(isset(Yii::app()->config->IceSwitch)){
		//     $config = Yii::app()->config->IceSwitch;//1启用0禁用
		//     $switch = $config==1?1:0;
		// }

		$unitid = null;
		$uuid = '';
		if ($orderModel == 'PropertyActivity') {
			$unitid = empty($advanceFee->AdvanceFees) ? null : $advanceFee->AdvanceFees->colorcloud_unit;
			$uuid = empty($advanceFee->AdvanceFees) ? null : empty($advanceFee->AdvanceFees->community->colorcloudCommunity) ? null : $advanceFee->AdvanceFees->community->colorcloudCommunity[0]->color_community_id;
		}
		if ($orderModel == 'PropertyFees') {
			$unitid = empty($advanceFee->PropertyFees) ? null : $advanceFee->PropertyFees->colorcloud_unit;
			$uuid = empty($advanceFee->PropertyFees) ? null : empty($advanceFee->PropertyFees->community->colorcloudCommunity) ? null : $PropertyFees->PropertyFees->community->colorcloudCommunity[0]->color_community_id;
		}

		$activityRate = PropertyActivityRate::model()->findByPk($advanceFee->rate_id);
		if (empty($activityRate)) {
			return false;
		}

		if ($orderModel == 'PropertyActivity') {
			$payfee = ($advanceFee->reduction) * $activityRate->month;
		}
		if ($orderModel == 'PropertyFees') {
			$payfee = $advanceFee->reduction;
		}
		$actfee = $actmoney = $payfee;
		$discount = 1;
		$year = date('Y', $advanceFee->mitigate_starttime);
		$month = intval(date('m', $advanceFee->mitigate_starttime));
		$receiptnumber = $advanceFee->sn;
		$flag = $advanceFee->model == 'PropertyActivity' ? 0 : 1;

		if (empty($unitid) || empty($receiptnumber) || empty($payfee) || empty($actfee) || empty($discount) || empty($year) || empty($month)) {
			PropertyActivity::model()->addError('id', "创建ice预缴费订单失败！");
			return false;
		}
		//引入接口文件
		Yii::import('common.api.IceApi');
		$coloure = IceApi::getInstance();
		//记录参数
		Yii::log('创建ice冲抵物业费订单参数: uuid: ' . $uuid . ', unitid: ' . $unitid . ', receiptnumber: ' . $receiptnumber . ', flag: ' . $flag . ', payfee: ' . $payfee . ', actfee: ' . $actfee . ', actmoney: ' . $actmoney . ', year: ' . $year . ', month: ' . $month . ', discount: ' . $discount . ', note: ' . $note . ', uuid: ' . $uuid, CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.NewSetAdvanceSavefee');
		//执行接口
		$result = $coloure->callSetAdvanceSaveAppFee($unitid, $receiptnumber, $flag, $payfee, $actfee, $actmoney, $year, $month, $discount, $note, $uuid);

		//处理彩之云订单返回结果
		if (!isset($result) || $result['code'] != 0 || $result['message'] != '缴费成功') {
			Yii::log('失败：ice订单创建失败！返回信息：' . $result['message'] . '=====注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.NewSetAdvanceSavefee');
			PropertyActivity::model()->addError('id', $result['message']);
			return false;
		} else {
			Yii::log('成功：ice成功接收到冲抵物业费数据。注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.NewSetAdvanceSavefee');
			//调用ERP成功写入房间号
			PropertyFeeLog::createFeeLog($advanceFee->customer_id, $unitid);
		}

		if (!PropertyActivity::model()->updateByPk($order_id, array('is_receive' => 1))) {
			Yii::log('失败：更改订单字段is_receive为1失败！', CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.NewSetAdvanceSavefee');
			PropertyActivity::model()->addError('id', "ice冲抵物业费订单创建成功，更新彩之云订单is_receive失败！");
			return false;
		} else {
			Yii::log('成功：更改订单字段is_receive为1成功！', CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.NewSetAdvanceSavefee');
		}
		return true;
	}


	//临时传数据到收费系统或者退款
	/**
	 * 零物业费订单支付成功回调，函数将调用彩之云接口创建ERP的预缴费订单
	 * @param $order_id 我们的预缴费订单ID,propertyactivity的Id
	 * @param $state 订单的状态
	 * @param $pay_time 付款时间
	 * @param $note 备注
	 * @return bool
	 */
	public function NewSetAdvanceSavefeeLingshi($order_id, $note, $orderModel, $state = 0)
	{
		if (empty($order_id) || empty($orderModel)) {
			return false;
		}
		$advanceFee = PropertyActivity::model()->findByPk($order_id);
		if (empty($advanceFee)) {
			PropertyActivity::model()->addError('id', "未知的预缴费订单！");
			return false;
		}

		if ($orderModel == 'PropertyActivity') {
			$unitid = empty($advanceFee->AdvanceFees) ? null : $advanceFee->AdvanceFees->colorcloud_unit;
			$uuid = empty($advanceFee->AdvanceFees) ? null : empty($advanceFee->AdvanceFees->community->colorcloudCommunity) ? null : $advanceFee->AdvanceFees->community->colorcloudCommunity[0]->color_community_id;
		}
		if ($orderModel == 'PropertyFees') {
			$unitid = empty($advanceFee->PropertyFees) ? null : $advanceFee->PropertyFees->colorcloud_unit;
			$uuid = empty($advanceFee->PropertyFees) ? null : empty($advanceFee->PropertyFees->community->colorcloudCommunity) ? null : $advanceFee->PropertyFees->community->colorcloudCommunity[0]->color_community_id;
		}

		$activityRate = PropertyActivityRate::model()->findByPk($advanceFee->rate_id);
		if (empty($activityRate)) {
			return false;
		}


		if ($state == -1) {
			// 退款为负数
			if ($orderModel == 'PropertyActivity') {
				$payfee = 0 - ($advanceFee->reduction * $activityRate->month);
			}
			if ($orderModel == 'PropertyFees') {
				$payfee = 0 - $advanceFee->reduction;
			}
		}


		if ($state == 0) {
			//接收
			if ($orderModel == 'PropertyActivity') {
				$payfee = $advanceFee->reduction * $activityRate->month;
			}
			if ($orderModel == 'PropertyFees') {
				$payfee = $advanceFee->reduction;
			}
		}

		$actfee = $actmoney = $payfee;
		$discount = 1;
		$year = date('Y', $advanceFee->mitigate_starttime);
		$month = intval(date('m', $advanceFee->mitigate_starttime));
		$receiptnumber = $advanceFee->sn;
		$flag = $advanceFee->model == 'PropertyActivity' ? 0 : 1;

		//判断彩之云需要的必填参数
		if (empty($unitid) || empty($receiptnumber) || empty($payfee) || empty($actfee) || empty($actmoney) || empty($discount) || empty($year) || empty($month)) {
			PropertyActivity::model()->addError('id', "创建ice预缴费订单失败！");
			return false;
		}
		//调用收费系统接口
		Yii::import('common.api.IceApi');
		//实例化
		$coloure = IceApi::getInstance();

		Yii::log('创建ice冲抵物业费订单:参数  ,unitid: ' . $unitid . ', receiptnumber: ' . $receiptnumber . ', flag: ' . $flag .
			', payfee: ' . $payfee . ', actfee: ' . $actfee . ', actmoney: ' . $actmoney . ', year: ' . $year . ', month: ' . $month . ', discount: ' . $discount . ', note: ' . $note . ', uuid: ' . $uuid,
			CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
		// var_dump(23);die;
		//使用彩之云的接口创建彩之云的缴费订单
		$result = $coloure->callSetAdvanceSaveAppFee($unitid, $receiptnumber, $flag, $payfee, $actfee, $actmoney, $year, $month, $discount, $note, $uuid);

		//系统日志记录彩之云订单创建情况
		Yii::log('创建ice冲抵物业费订单返回值：' . var_export($result, true) . '======注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
			'colourlife.core.PropertyActivity.createAdvanceFeeOrder');

		//处理彩之云订单返回结果
		if (!isset($result) || $result['code'] != 0 || $result['message'] != '缴费成功') {
			Yii::log('ice订单创建失败！返回信息：' . $result['message'] . '====注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
			PropertyActivity::model()->addError('id', $result['message']);
			return false;
		} else {
			Yii::log('成功：ice收费系统成功接收到冲抵物业费数据。注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
		}


		if ($state == 0) {
			//修改我们的订单字段is_receive值为1
			if (!PropertyActivity::model()->updateByPk($order_id, array('is_receive' => 1))) {
				Yii::log('失败：ice冲抵物业费订单创建成功，回调修改彩之云订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态失败！', CLogger::LEVEL_INFO,
					'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
				PropertyActivity::model()->addError('id', "ERP冲抵物业费订单创建成功，更新彩之云订单失败！");
				return false;
			} else {
				Yii::log('成功：ice冲抵物业费订单创建成功,回调修改彩之云订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态成功！', CLogger::LEVEL_INFO,
					'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
			}
		} else {
			PropertyActivity::model()->updateByPk($order_id, array('status' => 87, 'note' => '订单已经退款'));
		}

		return true;
	}








	//临时传数据到收费系统
	/**
	 * 零物业费订单支付成功回调，函数将调用彩之云接口创建ERP的预缴费订单
	 * @param $order_id 我们的预缴费订单ID,propertyactivity的Id
	 * @param $state 订单的状态
	 * @param $pay_time 付款时间
	 * @param $note 备注
	 * @return bool
	 */
	public function NewSetAdvanceSavefeeLingshiTest($order_id, $note)
	{
		if (empty($order_id)) {
			return false;
		}
		$advanceFee = PropertyActivity::model()->findByPk($order_id);
		if (empty($advanceFee)) {
			PropertyActivity::model()->addError('id', "未知的预缴费订单！");
			return false;
		}

		$unitid = empty($advanceFee->AdvanceFees) ? null : $advanceFee->AdvanceFees->colorcloud_unit;
		$uuid = empty($advanceFee->AdvanceFees) ? null : empty($advanceFee->AdvanceFees->community->colorcloudCommunity) ? null : $advanceFee->AdvanceFees->community->colorcloudCommunity[0]->color_community_id;
		$payfee = ($advanceFee->reduction) * 12;
		$actfee = $actmoney = $payfee;
		$discount = 1;
		$year = date('Y', $advanceFee->mitigate_starttime);
		$month = intval(date('m', $advanceFee->mitigate_starttime));
		$receiptnumber = $advanceFee->sn;
		$flag = $advanceFee->model == 'PropertyActivity' ? 0 : 1;

		//判断彩之云需要的必填参数
		if (empty($unitid) || empty($receiptnumber) || empty($payfee) || empty($actfee) || empty($actmoney) || empty($discount) || empty($year) || empty($month)) {
			PropertyActivity::model()->addError('id', "创建ice预缴费订单失败！");
			return false;
		}
		//引入彩之云的接口
		//Yii::import('common.api.ColorCloudApi');
		Yii::import('common.api.IceApi');
		//实例化
		//$coloure = ColorCloudApi::getInstance();
		$coloure = IceApi::getInstance();

		//创建我们的物业费订单前，需要先创建彩之云的订单，
		//创建彩之云订单前写系统日志，记录参数
		Yii::log('创建ice冲抵物业费订单:参数  ,unitid: ' . $unitid . ', receiptnumber: ' . $receiptnumber . ', flag: ' . $flag .
			', payfee: ' . $payfee . ', actfee: ' . $actfee . ', actmoney: ' . $actmoney . ', year: ' . $year . ', month: ' . $month . ', discount: ' . $discount . ', note: ' . $note . ', uuid: ' . $uuid,
			CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.createAdvanceFeeOrder');

		//使用彩之云的接口创建彩之云的缴费订单
		$result = $coloure->callSetAdvanceSaveAppFee($unitid, $receiptnumber, $flag, $payfee, $actfee, $actmoney, $year, $month, $discount, $note, $uuid);

		//系统日志记录彩之云订单创建情况
		Yii::log('创建ice冲抵物业费订单返回值：' . var_export($result, true) . '======注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
			'colourlife.core.PropertyActivity.createAdvanceFeeOrder');

		//处理彩之云订单返回结果
		if (!isset($result) || $result['code'] != 0 || $result['message'] != '缴费成功') {
			Yii::log('ice订单创建失败！返回信息：' . $result['message'] . '====注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
			PropertyActivity::model()->addError('id', $result['message']);
			return false;
		} else {
			Yii::log('成功：ice收费系统成功接收到冲抵物业费数据。注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
		}

		//修改我们的订单字段is_receive值为1
		if (!PropertyActivity::model()->updateByPk($order_id, array('is_receive' => 1))) {
			Yii::log('失败：ice冲抵物业费订单创建成功，回调修改彩之云订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态失败！', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
			PropertyActivity::model()->addError('id', "ice冲抵物业费订单创建成功，更新彩之云订单失败！");
			return false;
		} else {
			Yii::log('成功：ice冲抵物业费订单创建成功,回调修改彩之云订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态成功！', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
		}
		return true;
	}














	//新版本(不需要传到收费系统)
	/**
	 * 零物业费订单支付成功回调，函数将调用彩之云接口创建ERP的预缴费订单
	 * @param $order_id 我们的预缴费订单ID,propertyactivity的Id
	 * @param $state 订单的状态
	 * @param $pay_time 付款时间
	 * @param $note 备注
	 * @return bool
	 */
	public function SetAdvanceSavefee($order_id, $state, $pay_time, $note, $pay_orderSn, $orderModel)
	{
		if (empty($order_id) || empty($state) || empty($pay_time) || empty($pay_orderSn) || empty($orderModel)) {
			return false;
		}
		$advanceFee = PropertyActivity::model()->findByPk($order_id);
		if (empty($advanceFee)) {
			PropertyActivity::model()->addError('id', "未知的预缴费订单！");
			return false;
		}
		if ($orderModel == 'PropertyActivity') {
			$unitid = empty($advanceFee->AdvanceFees) ? null : $advanceFee->AdvanceFees->colorcloud_unit;
		}

		if ($orderModel == 'PropertyFees') {
			$unitid = empty($advanceFee->PropertyFees) ? null : $advanceFee->PropertyFees->colorcloud_unit;
		}


		//判断彩之云需要的必填参数
		if (empty($unitid)) {
			PropertyActivity::model()->addError('id', "创建彩之云预缴费订单失败！");
			return false;
		}

		PropertyFeeLog::createFeeLog($advanceFee->customer_id, $unitid);

		//修改我们的订单
		if (!PropertyActivity::model()->updateByPk($order_id, array('status' => $state, 'pay_time' => $pay_time, 'update_time' => time(), 'pay_sn' => $pay_orderSn))) {
			Yii::log('失败：冲抵物业费订单回调,修改订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态失败！', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
			PropertyActivity::model()->addError('id', "冲抵物业费订单状态更新失败！");
			return false;
		} else {
			Yii::log('成功：冲抵物业费订单回调,修改订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态成功！', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
		}

		$this->active($advanceFee);//活动，已做异常处理

		OthersFeesLog::createOtherFeesLog($order_id, 'PropertyActivity', $state, $note);

		return true;
	}


	/**
	 * 冲抵物业费订单回调修改成已授权
	 * @param $order_id 我们的预缴费订单ID,propertyactivity的Id
	 * @param $state 订单的状态
	 * @param $pay_time 付款时间
	 * @param $note 备注
	 * @return bool
	 */
	public function UpdateAdvanceSavefee($order_id, $state, $pay_time, $note)
	{
		if (empty($order_id) || empty($state) || empty($pay_time) || empty($note)) {
			return false;
		}
		$advanceFee = PropertyActivity::model()->findByPk($order_id);
		if (empty($advanceFee)) {
			PropertyActivity::model()->addError('id', "未知的预缴费订单！");
			return false;
		}

		//修改我们的订单失败
		if (!PropertyActivity::model()->updateByPk($order_id, array('status' => $state))) {
			Yii::log('失败：回调修改订单为充值成功失败！', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.UpdateAdvanceSavefee');
			PropertyActivity::model()->addError('id', "彩之云更新充值成功订单状态失败！");
			return false;
		}

		Yii::log('成功：修改订单为充值成功！参数列表：充值时间【' . date('Y-m-d H:i:s', $pay_time) . '】', CLogger::LEVEL_INFO,
			'colourlife.core.PropertyActivity.UpdateAdvanceSavefee');

		OthersFeesLog::createOtherFeesLog($order_id, 'PropertyActivity', $state, $note);

		return true;
	}


	//活动
	public function active($advanceFee)
	{


		$order_sn = $advanceFee->sn;
		$model = PayLib::get_model_by_sn($order_sn);
		try {
			IntegralEvent::activityOrder($advanceFee);//付款成功积分
		} catch (Exception $e) {
			Yii::log("记录零物业订单付款流水号'{$this->order_sn}' 状态:已付款。送积分异常。", CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivityOrder');
		}

		try {
			//彩之云app活动
			LuckyDoAdd::propertyActivity($advanceFee->customer_id, $advanceFee->customerName, $advanceFee->id);
		} catch (Exception $e) {
			Yii::log("活动异常导致，订单SN为：'{$this->order_sn}' ", CLogger::LEVEL_INFO, 'colourlife.core.OrderFactory');
		}
	}


	//OLD
	// /**
	//  * 预缴冲抵明细查询
	//  * @param $unitid
	//  * @return isarray
	//  *
	//  */
	// public function getPayAppLogActivity($unitid,$year,$month,$pagesize=NULL,$pageindex=NULL){
	//     if(empty($unitid)){
	//         OthersAdvanceFees::model()->addError('id', "获取冲抵明细失败！");
	//     }else{
	//         //引入彩之云的接口
	//         Yii::import('common.api.ColorCloudApi');
	//         //实例化
	//         $coloure = ColorCloudApi::getInstance();
	//         $result = $coloure->callGetPayAppLog($unitid, $year, $month, $pagesize, $pageindex);
	//         if (!isset($result['data'])) {
	//             //如果未找到冲抵明细
	//             PropertyActivity::model()->addError('id', "获取用户冲抵明细记录失败！");
	//             $result['data'] = array();
	//         }
	//         $data["data"]= $result['data'];
	//         $data["total"]  = $result["total"];
	//         return $data;
	//     }
	// }

	//ICE
	/**
	 * 预缴冲抵明细查询
	 * @param $unitid
	 * @return isarray
	 *
	 */
	public function getPayAppLogActivity($unitid, $year, $month, $pagesize = NULL, $pageindex = NULL, $uuid)
	{
		if (empty($unitid)) {
			OthersAdvanceFees::model()->addError('id', "获取冲抵明细失败！");
		} else {
			//引入彩之云的接口
			Yii::import('common.api.IceApi');
			//实例化
			$coloure = IceApi::getInstance();
			$result = $coloure->callGetPayAppLog($unitid, $year, $month, $pagesize, $pageindex, $uuid);
			if ($result['code'] != 0 || !isset($result['data'])) {
				//如果未找到冲抵明细
				PropertyActivity::model()->addError('id', "获取用户冲抵明细记录失败！");
				$result['data'] = array();
			}
			$data['data'] = $result['data']['data'];
			$data['total'] = $result['data']["total"];
			return $data;
		}
	}


	public function getCustomerName()
	{
		return empty($this->customer) ? $this->customer_id : (empty($this->customer->name) ? $this->customer->username : $this->customer->name);
	}


	public function getStatusName($html = false)
	{
		$return = '';
		$return .= ($html) ? '<span class="label label-success">' : '';
		$return .= self::$fees_status[$this->status];
		$return .= ($html) ? '</span>' : '';
		return $return;
	}


	public function getSendCanStatusName($html = false)
	{
		$return = '';
		$return .= ($html) ? '<span class="label label-success">' : '';
		$return .= self::$sendCan_status[$this->sendCan];
		$return .= ($html) ? '</span>' : '';
		return $return;
	}


	public function getStatusNameNums($html = false)
	{
		$return = '';
		$return .= ($html) ? '<span class="label label-success">' : '';
		$return .= self::$fees_status_num[$this->status];
		$return .= ($html) ? '</span>' : '';
		return $return;
	}


	public function getActivityAddress()
	{
		$regions = "";
		if (!empty($this->AdvanceFees)) {
			$model = ICECommunity::model()->enabled()->findByPk($this->AdvanceFees->community_id);
			if (!empty($model)) {
				$regions = $model->getCommunityAddress();
				$regions .= $this->AdvanceFees->build . $this->AdvanceFees->room;
			}
		}

		return $regions;
	}


	// 得到欠缴地址
	public function getPropertyAddress()
	{
		$regions = "";
		if (!empty($this->PropertyFees)) {
			$model = ICECommunity::model()->enabled()->findByPk($this->PropertyFees->community_id);
			if (!empty($model)) {
				$regions = $model->getCommunityAddress();
				$regions .= $this->PropertyFees->build . $this->PropertyFees->room;
			}
		}

		return $regions;
	}


	// 得到停车费地址
	public function getParkingActivityAddress()
	{
		$regions = "";
		if (!empty($this->ParkingFees)) {
			$model = ICECommunity::model()->enabled()->findByPk($this->ParkingFees->community_id);
			if (!empty($model)) {
				$regions = $model->getCommunityAddress();
				$regions .= $this->ParkingFees->build->name . $this->ParkingFees->room;
			}
		}
		return $regions;
	}

	// 得到停车费地址
	public function getParkingMonthActivityAddress()
	{
		$regions = "";
		if (!empty($this->ParkingFeesMonth)) {
			$model = ICECommunity::model()->enabled()->findByPk($this->ParkingFeesMonth->community_id);
			if (!empty($model)) {
				$regions = $model->getCommunityAddress();
				$regions .= $this->ParkingFeesMonth->build->name . $this->ParkingFeesMonth->room;
			}
		}
		return $regions;
	}


	public function getMitigateStartTimeFormat()
	{
		if (!empty($this->mitigate_starttime)) {
			$time = date('Y-m', $this->mitigate_starttime);
		}

		return $time;
	}


	public function getMitigateEndTimeFormat()
	{
		if (!empty($this->mitigate_endtime)) {
			$time = date('Y-m', $this->mitigate_endtime);
		}

		return $time;
	}

	//获得彩富人生NAME
	public function getActivityType()
	{
		$type = "";
		if (!empty($this->PropertyActivityRate)) {
			$type = $this->PropertyActivityRate->name;
		}
		return $type;
	}


	//获得彩富人生ID
	public function getActivityTypeNum()
	{
		$typeID = "";
		if (!empty($this->PropertyActivityRate)) {
			$typeID = $this->PropertyActivityRate->id;
		}
		return $typeID;
	}


	//欠费冲抵单元号
	public function getPropertyUnitId()
	{
		$propertyUnitId = "";
		if (!empty($this->PropertyFees) && $this->status == Item::FEES_TRANSACTION_SUCCESS && $this->model == "PropertyFees") {
			$propertyUnitId = $this->PropertyFees->colorcloud_unit;
		}
		return $propertyUnitId;
	}


	//车牌号
	public function getPakingFeesCarNumber()
	{
		$car_number = "";
		if (!empty($this->ParkingFees) && $this->model == "ParkingFees") {
			$car_number = $this->ParkingFees->car_number;
		}
		return $car_number;
	}


	//停车费类型
	public function getPakingFeesType()
	{
		$parking_type = "";
		if (!empty($this->ParkingFees) && $this->model == "ParkingFees") {
			$parking_type = $this->ParkingFees->type->name;
		}
		return $parking_type;
	}


	//欠费冲抵单元号
	public function getColorcloudBills()
	{
		$bills = "";
		if (!empty($this->PropertyFees) && $this->status == Item::FEES_TRANSACTION_SUCCESS && $this->model == "PropertyFees") {
			$bills = $this->PropertyFees->colorcloud_bills;
		}
		return $bills;
	}


	public static function getStatusNames()
	{
		return CMap::mergeArray(array('' => '全部'), self::$fees_status);
	}


	public static function getSendCanStatusNameSel()
	{
		return CMap::mergeArray(array('' => '全部'), self::$sendCan_status);
	}


	public static function getStatusNamess()
	{
		return CMap::mergeArray(array('all' => '全部'), self::$fees_status);
	}


	public static function getValidSendMonth($order_id)
	{
		if (empty($order_id)) {
			throw new CHttpException('404', '无效的操作对象');
		} else {
			$order = PropertyActivity::model()->findByPk($order_id);
			if (empty($order)) {
				throw new CHttpException('404', '无效的操作对象');
			} else {
				if (empty($order->PropertyActivityRate)) {
					throw new CHttpException('404', '无效的操作对象');
				} else {
					$object = $order->PropertyActivityRate->month;
				}
				$arr = array();
				$va = date('Y-m', $order->mitigate_starttime);
				for ($i = 0; $i <= intval($object); $i++) {
					$str = date('Y-m', strtotime($va . " +" . $i . " month"));
					if ($str > date('Y-m')) {
						continue;
					}
					$arr[$str] = $str;
				}
				return $arr;
			}
		}
	}


	public static function getValidSendMonthBool($order_id)
	{
		if (empty($order_id)) {
			return false;
			// throw new CHttpException('404', '无效的操作对象');
		} else {
			$order = PropertyActivity::model()->findByPk($order_id);
			if (empty($order)) {
				return false;
				// throw new CHttpException('404', '无效的操作对象');
			} else {
				if (empty($order->PropertyActivityRate)) {
					// throw new CHttpException('404', '无效的操作对象');
					return false;
				} else {
					$object = $order->PropertyActivityRate->month;
				}
				$arr = array();
				$va = date('Y-m', $order->mitigate_starttime);
				for ($i = 0; $i <= intval($object); $i++) {
					$str = date('Y-m', strtotime($va . " +" . $i . " month"));
					if ($str > date('Y-m')) {
						continue;
					}
					$arr[$str] = $str;
				}
				return $arr;
			}
		}
	}


	static public function changeOrderStatus($order_id, $user_id, $user_model, $status, $note = '')
	{
		if (empty($order_id)) {
			throw new CHttpException('404', '无效的操作对象');
		} else {
			$order = PropertyActivity::model()->findByPk($order_id);
			if (empty($order)) {
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


	static public function ValidateStatus($order_id)
	{
		if (empty($order_id)) {
			return false;
		} else {
			$order = PropertyActivity::model()->findByPk($order_id);
			if (empty($order)) {
				return false;
			} else {
				if (!self::checkStatus($order->status)) {
					return false;
				} else {
					return true;
				}
			}
		}
	}


	static public function checkStatus($status)
	{


		$array = array(
			Item::FEES_AWAITING_PAYMENT,
			Item::FEES_TRANSACTION_SUCCESS,
			Item::FEES_CANCEL,
			Item::FEES_TRANSACTION_ERROR,
		);


		if (!in_array($status, $array) || $status != $array[0]) {
			return false;
		} else {
			return true;
		}
	}


	public function getCommunityTag()
	{
//		$community = empty($this->AdvanceFees) ? null : $this->AdvanceFees->community;

//		ICE bugfix 下面报错
		$community_id = empty($this->AdvanceFees) ? '' : $this->AdvanceFees->community_id;
		$community = ICECommunity::model()->findByPk($community_id);

		if (!empty($community)) {
			//$_barchName = Branch::getMyParentBranchName($community->branch_id, true);
			$_barchName = $community->ICEGetCommunityBranchesNames();
			//$_regionName = Region::getMyParentRegionNames($community->region_id, true);
			$_regionName = $community->ICEGetCommunityRegionsNames();
		} else {
			$_barchName = "未知的部门！";
			$_regionName = "未知的地区！";
		}
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '所属部门:' . $_barchName . '，所属地区:' . $_regionName),
			empty($community) ? "" : $community->name);
	}

	public function getMobileTag()
	{
		$customer = $this->customer;
		$mobile = $customer ? $customer->mobile : "";
		$username = $customer ? $customer->username : "";
		$customerName = empty($this->AdvanceFees) ? "" : $this->AdvanceFees->customer_name;
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '姓名:' . $customerName . '，帐号:' . $username),
			$mobile);
	}

	public function getBuildTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '彩之云楼栋:' . (empty($this->AdvanceFees) ? "" : $this->AdvanceFees->colorcloud_building)),
			empty($this->AdvanceFees) ? "" : $this->AdvanceFees->build);
	}

	public function getRoomTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '彩之云门牌号:' . (empty($this->AdvanceFees) ? "" : $this->AdvanceFees->colorcloud_unit)),
			empty($this->AdvanceFees) ? "" : $this->AdvanceFees->room);
	}

	public function getColour_unitTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '彩之云门牌号:' . (empty($this->AdvanceFees) ? "" : $this->AdvanceFees->colorcloud_unit)),
			empty($this->AdvanceFees) ? "" : $this->AdvanceFees->colorcloud_unit);
	}


	public function getParentBranchName()
	{
//		$community = empty($this->AdvanceFees) ? null : $this->AdvanceFees->community;

//		ICE bugfix 下面报错
		$community_id = empty($this->AdvanceFees) ? '' : $this->AdvanceFees->community_id;
		$community = ICECommunity::model()->findByPk($community_id);

		if (!empty($community)) {
			//$_barchName = Branch::getMyParentBranchName($community->branch_id, true);
			$_barchName = $community->ICEGetCommunityBranchesNames();
		} else {
			$_barchName = "-";
		}
		return $_barchName;
	}

	public function getParentCommunityName()
	{
//		$community = empty($this->AdvanceFees) ? null : $this->AdvanceFees->community;

//		ICE bugfix 下面报错
		$community_id = empty($this->AdvanceFees) ? '' : $this->AdvanceFees->community_id;
		$community = ICECommunity::model()->findByPk($community_id);

		if (!empty($community)) {
			//$_regionName = Region::getMyParentRegionNames($community->region_id, true);
			$_regionName = $community->ICEGetCommunityRegionsNames();
		} else {
			$_regionName = "-";
		}
		return $_regionName . '-' . (empty($community) ? "" : $community->name);
	}


	public function getArrearCommunityTag()
	{
//		$community = empty($this->PropertyFees) ? null : $this->PropertyFees->community;

//		ICE bugfix 下面报错
		$community_id = empty($this->PropertyFees) ? '' : $this->PropertyFees->community_id;
		$community = ICECommunity::model()->findByPk($community_id);

		if (!empty($community)) {
			//$_barchName = Branch::getMyParentBranchName($community->branch_id, true);
			$_barchName = $community->ICEGetCommunityBranchesNames();
			//$_regionName = Region::getMyParentRegionNames($community->region_id, true);
			$_regionName = $community->ICEGetCommunityRegionsNames();
		} else {
			$_barchName = "未知的部门！";
			$_regionName = "未知的地区！";
		}
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '所属部门:' . $_barchName . '，所属地区:' . $_regionName),
			empty($community) ? "" : $community->name);
	}

	public function getArrearMobileTag()
	{
		$customer = $this->customer;
		$mobile = $customer ? $customer->mobile : "";
		$username = $customer ? $customer->username : "";
		$customerName = empty($this->PropertyFees) ? "" : $this->PropertyFees->customer_name;
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '姓名:' . $customerName . '，帐号:' . $username),
			$mobile);
	}

	public function getArrearBuildTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '彩之云楼栋:' . (empty($this->PropertyFees) ? "" : $this->PropertyFees->colorcloud_building)),
			empty($this->PropertyFees) ? "" : $this->PropertyFees->build);
	}

	public function getArrearRoomTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '彩之云门牌号:' . (empty($this->PropertyFees) ? "" : $this->PropertyFees->colorcloud_unit)),
			empty($this->PropertyFees) ? "" : $this->PropertyFees->room);
	}


	public function getArrearParentBranchName()
	{
//		$community = empty($this->PropertyFees) ? null : $this->PropertyFees->community;

//		ICE bugfix 下面报错
		$community_id = empty($this->PropertyFees) ? '' : $this->PropertyFees->community_id;
		$community = ICECommunity::model()->findByPk($community_id);

		if (!empty($community)) {
			//$_barchName = Branch::getMyParentBranchName($community->branch_id, true);
			$_barchName = $community->ICEGetCommunityBranchesNames();
		} else {
			$_barchName = "-";
		}
		return $_barchName;
	}

	public function getArrearParentCommunityName()
	{
//		$community = empty($this->PropertyFees) ? null : $this->PropertyFees->community;

//		ICE bugfix 下面报错
		$community_id = empty($this->PropertyFees) ? '' : $this->PropertyFees->community_id;
		$community = ICECommunity::model()->findByPk($community_id);


		if (!empty($community)) {
			//$_regionName = Region::getMyParentRegionNames($community->region_id, true);
			$_regionName = $community->ICEGetCommunityRegionsNames();
		} else {
			$_regionName = "-";
		}
		return $_regionName . '-' . (empty($community) ? "" : $community->name);
	}


	public function getParkingCommunityTag()
	{
//		$community = empty($this->ParkingFees) ? null : $this->ParkingFees->community;

//		ICE bugfix 下面报错
		$community_id = empty($this->ParkingFees) ? '' : $this->ParkingFees->community_id;
		$community = ICECommunity::model()->findByPk($community_id);

		if (!empty($community)) {
			//$_barchName = Branch::getMyParentBranchName($community->branch_id, true);
			$_barchName = $community->ICEGetCommunityBranchesNames();
			//$_regionName = Region::getMyParentRegionNames($community->region_id, true);
			$_regionName = $community->ICEGetCommunityRegionsNames();
		} else {
			$_barchName = "未知的部门！";
			$_regionName = "未知的地区！";
		}
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '所属部门:' . $_barchName . '，所属地区:' . $_regionName),
			empty($community) ? "" : $community->name);
	}

	public function getParkingMonthCommunityTag()
	{
//		$community = empty($this->ParkingFeesMonth) ? null : $this->ParkingFeesMonth->community;

//		ICE bugfix 下面报错
		$community_id = empty($this->ParkingFeesMonth) ? '' : $this->ParkingFeesMonth->community_id;
		$community = ICECommunity::model()->findByPk($community_id);

		if (!empty($community)) {
			//$_barchName = Branch::getMyParentBranchName($community->branch_id, true);
			$_barchName = $community->ICEGetCommunityBranchesNames();
			//$_regionName = Region::getMyParentRegionNames($community->region_id, true);
			$_regionName = $community->ICEGetCommunityRegionsNames();
		} else {
			$_barchName = "未知的部门！";
			$_regionName = "未知的地区！";
		}
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '所属部门:' . $_barchName . '，所属地区:' . $_regionName),
			empty($community) ? "" : $community->name);
	}

	public function getParkingMobileTag()
	{
		$customer = $this->customer;
		$mobile = $customer ? $customer->mobile : "";
		$username = $customer ? $customer->username : "";
		$customerName = $customer ? $customer->name : "";
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '姓名:' . $customerName . '，帐号:' . $username),
			$mobile);
	}

	public function getParkingBuildTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '彩之云楼栋:' . (empty($this->ParkingFees) ? "" : $this->ParkingFees->build->name)),
			empty($this->ParkingFees) ? "" : $this->ParkingFees->build->name);
	}

	public function getParkingMonthBuildTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '彩之云楼栋:' . (empty($this->ParkingFeesMonth) ? "" : $this->ParkingFeesMonth->build->name)),
			empty($this->ParkingFeesMonth) ? "" : $this->ParkingFeesMonth->build->name);
	}

	public function getParkingRoomTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '彩之云门牌号:' . (empty($this->ParkingFees) ? "" : $this->ParkingFees->room)),
			empty($this->ParkingFees) ? "" : $this->ParkingFees->room);
	}

	public function getParkingMonthRoomTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '彩之云门牌号:' . (empty($this->ParkingFeesMonth) ? "" : $this->ParkingFeesMonth->room)),
			empty($this->ParkingFeesMonth) ? "" : $this->ParkingFeesMonth->room);
	}


	public function getParkingParentBranchName()
	{
		$community = empty($this->ParkingFees) ? null : $this->ParkingFees->community;
		if (!empty($community)) {
			$_barchName = Branch::getMyParentBranchName($community->branch_id, true);
		} else {
			$_barchName = "-";
		}
		return $_barchName;
	}

	public function getParkingMonthParentBranchName()
	{
		$community = empty($this->ParkingFeesMonth) ? null : $this->ParkingFeesMonth->community;
		if (!empty($community)) {
			$_barchName = Branch::getMyParentBranchName($community->branch_id, true);
		} else {
			$_barchName = "-";
		}
		return $_barchName;
	}

	public function getParkingParentCommunityName()
	{
//		$community = empty($this->ParkingFees) ? null : $this->ParkingFees->community;

//		ICE bugfix 下面报错
		$community_id = empty($this->ParkingFees) ? '' : $this->ParkingFees->community_id;
		$community = ICECommunity::model()->findByPk($community_id);

		if (!empty($community)) {
			//$_regionName = Region::getMyParentRegionNames($community->region_id, true);
			$_regionName = $community->ICEGetCommunityRegionsNames();
		} else {
			$_regionName = "-";
		}
		return $_regionName . '-' . (empty($community) ? "" : $community->name);
	}

	public function getParkingMonthParentCommunityName()
	{
//		$community = empty($this->ParkingFeesMonth) ? null : $this->ParkingFeesMonth->community;

//		ICE bugfix 下面报错
		$community_id = empty($this->ParkingFeesMonth) ? '' : $this->ParkingFeesMonth->community_id;
		$community = ICECommunity::model()->findByPk($community_id);

		if (!empty($community)) {
			//$_regionName = Region::getMyParentRegionNames($community->region_id, true);
			$_regionName = $community->ICEGetCommunityRegionsNames();
		} else {
			$_regionName = "-";
		}
		return $_regionName . '-' . (empty($community) ? "" : $community->name);
	}


	public function getAmountView()
	{
		$return = '<span>' . $this->amount . '</span>';
		$return .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="/advanceFee/history/' . $this->id . '">查看缴费历史</a>';
		return $return;
	}


	public function getStatusNameView()
	{
		$return = '<span class="label label-success">' . (self::$fees_status[$this->status]) . '</span>';
		if ($this->status == Item::FEES_AWAITING_PAYMENT || $this->status == Item::FEES_TRANSACTION_ERROR)
			$return .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="/advanceFee/update/' . $this->id . '">修改支付状态</a>';
		return $return;
	}


	/**
	 * API计算单元预缴费金额
	 * @param $unitid      收费单元ID
	 * @param $month        月数，1年=12
	 * @return int
	 */
	public function getApiSetPictureAdd($img, $name)
	{
		if (empty($name)) {
			PropertyActivity::model()->addError('id', "上传图片失败");
		} else {
			//引入彩之云的接口
			Yii::import('common.api.TestColorCloudApi');
			//实例化
			$coloure = TestColorCloudApi::getInstance();
			$result = $coloure->callSetPictureAdd($img, $name);

			if (empty($result['data'])) {
				PropertyActivity::model()->addError('id', "上传图片失败");
				$result['data'][0] = array();
			}

			return $result['data'][0];
		}
	}


	/**
	 * E清洁上传图片接口
	 * @param $unitid      收费单元ID
	 * @param $month        月数，1年=12
	 * @return int
	 */
	public function getApiSetPictureAddForEqj($img, $name, $type)
	{
		if (empty($name) || $type != 'oa') {
			PropertyActivity::model()->addError('id', "上传图片失败");
		} else {
			//引入彩之云的接口
			Yii::import('common.api.ColorCloudApi');
			//实例化
			$coloure = ColorCloudApi::getInstance();
			$result = $coloure->callSetPictureAdd($img, $name);
			// var_dump($result);die;
			$list = array();
			if (empty($result['total'])) {
				PropertyActivity::model()->addError('id', "上传图片失败");
				$list['state'] = 0;
				$list['msg'] = "";
			} else {
				$list['state'] = 1;
				$list['msg'] = $result['error'];
			}
			// var_dump($list);die;
			return $list;
		}
	}


	/**
	 * E入伙上传图片接口
	 * @param $unitid      收费单元ID
	 * @param $month        月数，1年=12
	 * @return int
	 */
	public function getApiSetPictureAddForERH($img, $name, $type)
	{
		if (empty($name) || $type != 'imglist') {
			PropertyActivity::model()->addError('id', "上传图片失败");
		} else {
			//引入彩之云的接口
			Yii::import('common.api.ColorCloudApi');
			//实例化
			$coloure = ColorCloudApi::getInstance();
			$result = $coloure->callSetPictureAdd($img, $name);
			// var_dump($result);die;
			$list = array();
			if (empty($result['total'])) {
				PropertyActivity::model()->addError('id', "上传图片失败");
				$list['state'] = 0;
				$list['msg'] = "";
			} else {
				$list['state'] = 1;
				$list['msg'] = $result['error'];
			}
			// var_dump($list);die;
			return $list;
		}
	}


	//OLD
	// /**
	//  *API 获得单元的收费记录
	//  * @param $unitid
	//  * @return mixed
	//  */
	// public function getArrearPayLog($billsid){
	//     if(empty($billsid)){
	//         PropertyActivity::model()->addError('id', "获取欠费冲抵记录失败！");
	//     }else{
	//         //引入彩之云的接口
	//         Yii::import('common.api.ColorCloudApi');
	//         //实例化
	//         $coloure = ColorCloudApi::getInstance();
	//         $result = $coloure->callGetPayBillsLog($billsid);
	//         if (!isset($result['data'])) {
	//             //如果未找到预缴费
	//             PropertyActivity::model()->addError('id', "获取收费记录失败！");
	//             $result['data'] = array();
	//         }
	//         $data["data"]= $result['data'];
	//         $data["total"]  = $result["total"];
	//         return  $data;
	//     }
	// }


	//ICE
	/**
	 *API 获得单元的收费记录
	 * @param $unitid
	 * @return mixed
	 */
	public function getArrearPayLog($billsid, $uuid)
	{
		if (empty($billsid)) {
			PropertyActivity::model()->addError('id', "获取欠费冲抵记录失败！");
		} else {
			//引入彩之云的接口
			Yii::import('common.api.IceApi');
			//实例化
			$coloure = IceApi::getInstance();
			$result = $coloure->callGetPayBillsLog($billsid, $uuid);
			if ($result['code'] != 0 || !isset($result['data'])) {
				//如果未找到预缴费
				PropertyActivity::model()->addError('id', "获取收费记录失败！");
				$result['data'] = array();
			}
			$data["data"] = $result['data']['data'];
			$data["total"] = $result['data']["total"];
			return $data;
		}
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

	//修改订单状态只能由指定的人操作
	public function getRole()
	{
		$username = Yii::app()->user->name;
		if (!empty($username)) {
			return $username;
		}

	}

}
