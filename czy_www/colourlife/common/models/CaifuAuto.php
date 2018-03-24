<?php

/**
 * This is the model class for table "caifu_auto".
 *
 * The followings are the available columns in table 'caifu_auto':
 * @property integer $id
 * @property string $sn
 * @property string $pay_sn
 * @property integer $customer_id
 * @property string $model
 * @property integer $object_id
 * @property integer $rate_id
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
 * @property integer $is_receive
 * @property string $erp_reduction
 * @property integer $inviter
 * @property integer $sendCan
 * @property string $remark
 * @property string $sendCan_username
 * @property integer $sendCan_userid
 * @property string $sendCan_date
 * @property integer $ticheng_send_status
 * @property string $update_username
 * @property integer $update_userid
 * @property string $update_date
 */
class CaifuAuto extends CActiveRecord
{

	public $modelName = '冲抵物业费订单';
	public $modelNameArrear = '欠费冲抵订单';
	public $modelNameParking = '停车费冲抵订单';
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
	// public $deductMonth;

	static $fees_status = array(
		Item::FEES_AWAITING_PAYMENT => "待付款",
		Item::FEES_TRANSACTION_SUCCESS => "交易成功",
		// Item::FEES_TRANSACTION_ERROR => '已付款',
		Item::FEES_TRANSACTION_ERROR => '已授权',
		Item::FEES_CANCEL => "订单已取消",
		Item::FEES_TRANSACTION_REFUND => '已退款',//90
	);


	// static $sendCan_status = array(
	//     Item::FEES_AWAITING_PAYMENT => "待审核", //0
	//     Item::FEES_TRANSACTION_ERROR => '审核通过',//1
	//     Item::FEES_CANCEL => "审核失败",//98
	// );


	static $fees_status_num = array(
		Item::FEES_AWAITING_PAYMENT => 0,
		Item::FEES_TRANSACTION_SUCCESS => 2,
		Item::FEES_CANCEL => 1,
		// Item::FEES_TRANSACTION_ERROR => 3,
		Item::FEES_TRANSACTION_ERROR => 0,
		Item::FEES_TRANSACTION_REFUND => 4,
	);


	static $send_status = array(
		0 => "未发放",
		1 => "提成红包发放成功",
		2 => "提成发放失败",
	);

	public static function getSendStatusNameList()
	{
		return CMap::mergeArray(array('' => '全部'), self::$send_status);
	}


	static $sendCan_status = array(
		0 => "未审核",
		1 => "审核中",
		2 => "审核成功",
		3 => "审核失败",
	);

	public static function getSendCanStatusNameList()
	{
		return CMap::mergeArray(array('' => '全部'), self::$sendCan_status);
	}


	static $revise_type = array(
		0 => "发放到彩管家",
		1 => "发放到彩之云",
	);


	//提成发放状态
	public function getSendStatusName($html = false)
	{
		if ($this->ticheng_send_status == 1) {
			return ($html) ? self::$send_status[$this->ticheng_send_status] : "<span class='label label-success'>" . self::$send_status[$this->ticheng_send_status] . "</span>";
		} else {
			return ($html) ? self::$send_status[$this->ticheng_send_status] : "<span class='label label-important'>" . self::$send_status[$this->ticheng_send_status] . "</span>";
		}
	}

	//审核状态
	public function getSendCanStatusName($html = false)
	{
		if ($this->sendCan == 2) {
			return ($html) ? self::$sendCan_status[$this->sendCan] : "<span class='label label-success'>" . self::$sendCan_status[$this->sendCan] . "</span>";
		} else {
			return ($html) ? self::$sendCan_status[$this->sendCan] : "<span class='label label-important'>" . self::$sendCan_status[$this->sendCan] . "</span>";
		}
	}


	public function customer_exists($html = false)
	{
		if ($this->customer_id == 0) {
			return ($html) ? "不存在" : "<span class='label label-important'>不存在</span>";
		} else {
			return ($html) ? "存在" : "<span class='label label-success'>存在</span>";
		}
	}


	public function inviter_exists($html = false)
	{
		if ($this->inviter == 0) {
			return ($html) ? "不存在" : "<span class='label label-important'>不存在</span>";
		} else {
			return ($html) ? "存在" : "<span class='label label-success'>存在</span>";
		}
	}


	//号码类型
	public function getReviseTypeName($html = false)
	{
		return ($html) ? self::$revise_type[$this->revise_type] : "<span class='label label-success'>" . self::$revise_type[$this->revise_type] . "</span>";
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'caifu_auto';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, rate_id, model, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime', 'required'),
			array('customer_id, object_id, rate_id, mitigate_starttime, mitigate_endtime, create_time, status, pay_time, update_time, is_receive, inviter, sendCan, sendCan_userid, ticheng_send_status, update_userid', 'numerical', 'integerOnly' => true),
			array('sn, pay_sn', 'length', 'max' => 32),
			array('model, create_ip', 'length', 'max' => 20),
			array('amount, reduction, earnings, community_rate, erp_reduction', 'length', 'max' => 10),
			array('sendCan_username, update_username', 'length', 'max' => 100),
			array('model', 'in', 'range' => array('PropertyActivity', 'PropertyFees', 'ParkingFees')),
			array('note, remark', 'safe'),
			array('rate_id', 'checkRateID'),
			array('revise_mobile', 'common.components.validators.ChinaMobileValidator', 'on' => 'inviterUpdate'),
			// array('revise_mobile', 'employee_exists', 'on' => 'inviterUpdate'),
			array('revise_type,revise_mobile', 'required', 'on' => 'inviterUpdate'),
			array('revise_type', 'in', 'range' => array(0, 1)),
			array('sendCan', 'in', 'range' => array(2, 3), 'on' => 'examine'),
			array('bind_mobile', 'length', 'max' => 15),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sn, pay_sn, customer_id, model, object_id, rate_id, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime, note, create_ip, create_time, status, pay_time, update_time, is_receive, erp_reduction, inviter, sendCan, remark, sendCan_username, sendCan_userid, sendCan_date, ticheng_send_status, update_username, update_userid, update_date, startTime, endTime, room, build, branch_id, communityIds, region, customerName, customer_name, customer_mobile', 'safe', 'on' => 'search'),
			array('id, sn, pay_sn, customer_id, model, object_id, rate_id, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime, note, create_ip, create_time, status, pay_time, update_time, is_receive, erp_reduction, inviter, sendCan, remark, sendCan_username, sendCan_userid, sendCan_date, ticheng_send_status, update_username, update_userid, update_date, startTime, endTime, room, build, branch_id, communityIds, region, customerName, customer_name, customer_mobile', 'safe', 'on' => 'arrear_search'),
			array('id, sn, pay_sn, customer_id, model, object_id, rate_id, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime, note, create_ip, create_time, status, pay_time, update_time, is_receive, erp_reduction, inviter, sendCan, remark, sendCan_username, sendCan_userid, sendCan_date, ticheng_send_status, update_username, update_userid, update_date, startTime, endTime, room, build, branch_id, communityIds, region, customerName, customer_name, customer_mobile, car_number', 'safe', 'on' => 'parkingFees_search'),
			array('id, sn, pay_sn, customer_id, model, object_id, rate_id, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime, note, create_ip, create_time, status, pay_time, update_time, is_receive, erp_reduction, inviter, sendCan, remark, sendCan_username, sendCan_userid, sendCan_date, ticheng_send_status, update_username, update_userid, update_date, startTime, endTime, room, build, branch_id, communityIds, region, customerName, customer_name, customer_mobile, car_number, car_number, deductMonth, remark', 'safe', 'on' => 'parkingFees_search'),
			array('id, sn, pay_sn, customer_id, model, object_id, rate_id, amount, reduction, earnings, community_rate, mitigate_starttime, mitigate_endtime, note, create_ip, create_time, status, pay_time, update_time, is_receive, erp_reduction, inviter, sendCan, remark, sendCan_username, sendCan_userid, sendCan_date, ticheng_send_status, update_username, update_userid, update_date, startTime, endTime, room, build, branch_id, communityIds, region, customerName, customer_name, customer_mobile, car_number, car_number, deductMonth, remark', 'safe', 'on' => 'deductList_search'),
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
			'model' => '订单类型',
			'object_id' => '预缴ID',
			'rate_id' => '彩富人生ID',
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
			'is_receive' => '收费系统是否收到订单',
			'erp_reduction' => 'ERP欠交金额',
			'inviter' => '推荐人ID',
			'sendCan' => '审核状态',
			'remark' => '审核备注',
			'sendCan_username' => '审核人OA',
			'sendCan_userid' => '审核人ID',
			'sendCan_date' => '审核时间',
			'ticheng_send_status' => '提成发放状态',
			'update_username' => '发放提成人OA',
			'update_userid' => '发放人id',
			'update_date' => '发放时间',
			'car_number' => '车牌号',
			// 'deductMonth'=>'提成月份',
			'custFlag' => '是否存在彩之云用户',
			'inviterFlag' => '是否存在推荐人彩管家账号',
			'revise_type' => '手机号码类型',
			'revise_mobile' => '修正推荐人手机号码',
			'bind_mobile' => '绑定OA手机号码',
			'send_type' => '发放类型',
			'inviter_name' => '推荐人姓名',
			'inviter_mobile' => '推荐人手机号码',
			'CustomerName' => '业主',
			'CustomerMobile' => '业主手机',
			'DeductMonth' => '投资月长',
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
			'ParkingFees' => array(self::BELONGS_TO, 'ParkingFees', 'object_id'),
			'PropertyActivityRate' => array(self::BELONGS_TO, 'PropertyActivityRate', 'rate_id'),
			'FeeDeductionProperty' => array(self::HAS_MANY, 'FeeDeductionProperty', 'orderID'),
			'inviterRe' => array(self::BELONGS_TO, 'Employee', 'inviter'),
			'inviterReCus' => array(self::BELONGS_TO, 'Customer', 'inviter'),
		);
	}


	public function checkRateID($attribute, $params)
	{
		if (!$this->hasErrors() && !empty($this->rate_id)) {
			$model = PropertyActivityRate::model()->findByPk($this->rate_id);
			if (!$this->hasErrors() && empty($model)) {
				$this->addError($attribute, "彩富人生ID错误！");
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

		$criteria->compare('id', $this->id);
		$criteria->compare('sn', $this->sn, true);
		$criteria->compare('pay_sn', $this->pay_sn, true);
		$criteria->compare('customer_id', $this->customer_id);
		$criteria->compare('model', $this->model, true);
		$criteria->compare('object_id', $this->object_id);
		$criteria->compare('rate_id', $this->rate_id);
		$criteria->compare('amount', $this->amount, true);
		$criteria->compare('reduction', $this->reduction, true);
		$criteria->compare('earnings', $this->earnings, true);
		$criteria->compare('community_rate', $this->community_rate, true);
		$criteria->compare('mitigate_starttime', $this->mitigate_starttime);
		$criteria->compare('mitigate_endtime', $this->mitigate_endtime);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('create_ip', $this->create_ip, true);
		$criteria->compare('create_time', $this->create_time);
		$criteria->compare('status', $this->status);
		$criteria->compare('pay_time', $this->pay_time);
		$criteria->compare('update_time', $this->update_time);
		$criteria->compare('is_receive', $this->is_receive);
		$criteria->compare('erp_reduction', $this->erp_reduction, true);
		$criteria->compare('inviter', $this->inviter);
		$criteria->compare('sendCan', $this->sendCan);
		$criteria->compare('remark', $this->remark, true);
		$criteria->compare('sendCan_username', $this->sendCan_username, true);
		$criteria->compare('sendCan_userid', $this->sendCan_userid);
		$criteria->compare('sendCan_date', $this->sendCan_date, true);
		$criteria->compare('ticheng_send_status', $this->ticheng_send_status);
		$criteria->compare('update_username', $this->update_username, true);
		$criteria->compare('update_userid', $this->update_userid);
		$criteria->compare('update_date', $this->update_date, true);
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
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.sn', $this->sn, true);
		$criteria->compare('`t`.pay_sn', $this->pay_sn, true);
		$criteria->compare('`t`.customer_id', $this->customer_id);
		$criteria->compare('`t`.object_id', $this->object_id);
		// $criteria->compare('`t`.model','PropertyActivity');
		$criteria->compare('`t`.model', $this->model, true);
		$criteria->compare('`t`.amount', $this->amount, true);
		$criteria->compare('`t`.reduction', $this->reduction, true);
		$criteria->compare('`t`.earnings', $this->earnings, true);
		$criteria->compare('`t`.community_rate', $this->community_rate, true);
		$criteria->compare('`t`.mitigate_starttime', $this->mitigate_starttime);
		$criteria->compare('`t`.mitigate_endtime', $this->mitigate_endtime);
		$criteria->compare('`t`.note', $this->note, true);
		$criteria->compare('`t`.create_ip', $this->create_ip, true);
		// $criteria->compare('`t`.create_time',$this->create_time);
		$criteria->compare('`t`.status', Item::FEES_TRANSACTION_SUCCESS);
		$criteria->compare('`t`.sendCan', $this->sendCan);
		$criteria->compare('`t`.pay_time', $this->pay_time);
		$criteria->compare('`t`.update_time', $this->update_time);
		$criteria->compare('`t`.sendCan', $this->sendCan);
		$criteria->compare('`t`.remark', $this->remark, true);
		$criteria->compare('`t`.pay_time', $this->pay_time);
		$criteria->compare('`t`.is_receive', $this->is_receive);
		$criteria->compare('`t`.inviter', $this->inviter);
		$criteria->compare('`t`.note', $this->note, true);
		$criteria->compare('`t`.sendCan_username', $this->sendCan_username, true);
		$criteria->compare('`t`.sendCan_userid', $this->sendCan_userid);
		$criteria->compare('`t`.sendCan_date', $this->sendCan_date, true);
		$criteria->compare('`t`.ticheng_send_status', $this->ticheng_send_status);
		$criteria->compare('`t`.update_username', $this->update_username, true);
		$criteria->compare('`t`.update_userid', $this->update_userid);
		$criteria->compare('`t`.update_date', $this->update_date, true);
		// if ($this->inviter_name != '' || $this->inviter_mobile != '') {
		//     $criteria->with[] = 'employee';
		//     $criteria->compare('employee.name', $this->inviter_name, true);
		//     $criteria->compare('employee.mobile', $this->inviter_mobile, true);
		// }
		if ($this->customer_name || $this->customer_mobile) {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.name', $this->customer_name, true);
			$criteria->compare('customer.mobile', $this->customer_mobile, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">= " . strtotime($this->startTime . " 00:00:00"));
		}

		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "<= " . strtotime($this->endTime . " 23:59:59"));
		}

		return new ActiveDataProvider($this,
			array(
				'criteria' => $criteria,
				'sort' => array('defaultOrder' => '`t`.create_time desc',)
			)
		);
	}


	//提成奖励搜索
	public function olddeductList_search()
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
		$criteria->addInCondition('PropertyFees.community_id', $community_ids);
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
		$criteria->addInCondition('ParkingFees.community_id', $community_ids);
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
		$comm = Community::model()->findByPk(intval($feeAttr['community_id']));
		$uuid = empty($comm) ? null : empty($comm->colorcloudCommunity) ? null : $comm->colorcloudCommunity[0]->color_community_id;
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
		$other->attributes = $feeAttr;

		Yii::log("使用ERP返回金额替换我们订单的金额（欠缴冲抵实际没有替换，这里只是记录）。欠费冲抵订单:{$other->sn},彩之云订单:{$result['content']['orderid']},我们的订单金额 :{$oldAmount},替换后的金额:{$feeAttr['erp_reduction']}", CLogger::LEVEL_INFO, 'colourlife.core.propertyfees.create');

		$propertyAttr['colorcloud_order'] = $result['content']['orderid'];
		$model = new PropertyFees();
		$model->attributes = $propertyAttr;

		if ($model->save()) { //先创建物业费记录，得到记录ID再创建订单记录
			$other->object_id = $model->id;
			if (!$other->save()) {
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

		//修改我们的订单失败
		if (!PropertyActivity::model()->updateByPk($order_id, array('status' => $state, 'pay_time' => $pay_time, 'update_time' => time(), 'pay_sn' => $pay_orderSn))) {
			Yii::log('失败：付款成功回调修改彩之云订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态失败！', CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
			PropertyActivity::model()->addError('id', "付款成功后更新彩之云订单失败！");
			return false;
		} else {
			Yii::log('成功：付款成功回调修改彩之云订单【id：' . $order_id . ',sn：' . $advanceFee->sn . '】状态成功！', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
		}

		$this->active($advanceFee);//活动，已做异常处理

		OthersFeesLog::createOtherFeesLog($order_id, 'PropertyActivity', $state, $note);

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

		//判断彩之云需要的必填参数
		if (empty($unitid) || empty($receiptnumber) || empty($payfee) || empty($actfee) || empty($discount) || empty($year) || empty($month)) {
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
		Yii::log('创建彩之ice预缴费订单:参数  ,unitid: ' . $unitid . ', receiptnumber: ' . $receiptnumber . ', flag: ' . $flag . ', payfee: ' . $payfee . ', actfee: ' . $actfee . ', actmoney: ' . $actmoney . ', year: ' . $year . ', month: ' . $month . ', discount: ' . $discount . ', note: ' . $note . ', uuid: ' . $uuid,
			CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.createAdvanceFeeOrder');

		//使用彩之云的接口创建彩之云的缴费订单
		$result = $coloure->callSetAdvanceSaveAppFee($unitid, $receiptnumber, $flag, $payfee, $actfee, $actmoney, $year, $month, $discount, $note, $uuid);

		//系统日志记录彩之云订单创建情况
		Yii::log('创建ice冲抵物业费订单返回值：' . var_export($result, true) . '======注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
			'colourlife.core.PropertyActivity.createAdvanceFeeOrder');

		//处理彩之云订单返回结果
		if (!isset($result) || $result['code'] != 0 || $result['message'] != '缴费成功') {
			Yii::log('失败：ice订单创建失败！返回信息：' . $result['message'] . '=====注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
			PropertyActivity::model()->addError('id', $result['message']);
			return false;
		} else {
			Yii::log('成功：ice系统成功接收到冲抵物业费数据。注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
				'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
			//调用ERP成功写入房间号
			PropertyFeeLog::createFeeLog($advanceFee->customer_id, $unitid);
		}


		if (!PropertyActivity::model()->updateByPk($order_id, array('is_receive' => 1))) {
			Yii::log('失败：更改订单字段is_receive为1失败！', CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
			PropertyActivity::model()->addError('id', "ERP冲抵物业费订单创建成功，更新彩之云订单is_receive失败！");
			return false;
		} else {
			Yii::log('成功：更改订单字段is_receive为1成功！', CLogger::LEVEL_INFO, 'colourlife.core.PropertyActivity.createAdvanceFeeOrder');
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
	public function NewSetAdvanceSavefeeLingshi($order_id, $note, $orderModel)
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


		//退款为负数
		// if($orderModel=='PropertyActivity'){
		//     $payfee = 0-($advanceFee->reduction*$activityRate->month);
		// }
		// if($orderModel=='PropertyFees'){
		//     $payfee = 0-$advanceFee->reduction;
		// }

		//临时
		if ($orderModel == 'PropertyActivity') {
			$payfee = $advanceFee->reduction * $activityRate->month;
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
			Yii::log('成功：ice系统成功接收到冲抵物业费数据。注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
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
			Yii::log('ERP订单创建失败！返回信息：' . $result['message'] . '====注：彩之云订单信息【id：' . $order_id . ',sn：' . $advanceFee->sn . '】', CLogger::LEVEL_INFO,
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


	// OLD
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


	public function getCustomerMobile()
	{
		return !empty($this->customer) ? $this->customer->mobile : "";
	}


	public function getDeductMonth()
	{
		return !empty($this->PropertyActivityRate) ? $this->PropertyActivityRate->month : 0;
	}

	public function getStatusName($html = false)
	{
		$return = '';
		$return .= ($html) ? '<span class="label label-success">' : '';
		$return .= self::$fees_status[$this->status];
		$return .= ($html) ? '</span>' : '';
		return $return;
	}



	// public function getSendCanStatusName($html = false)
	// {
	//     $return = '';
	//     $return .= ($html) ? '<span class="label label-success">' : '';
	//     $return .= self::$sendCan_status[$this->sendCan];
	//     $return .= ($html) ? '</span>' : '';
	//     return $return;
	// }


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
			$model = Community::model()->enabled()->findByPk($this->AdvanceFees->community_id);
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
			$model = Community::model()->enabled()->findByPk($this->PropertyFees->community_id);
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
			$model = Community::model()->enabled()->findByPk($this->ParkingFees->community_id);
			if (!empty($model)) {
				$regions = $model->getCommunityAddress();
				$regions .= $this->ParkingFees->build->name . $this->ParkingFees->room;
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
		$community = empty($this->AdvanceFees) ? null : $this->AdvanceFees->community;
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


	public function getParentBranchName()
	{
		$community = empty($this->AdvanceFees) ? null : $this->AdvanceFees->community;
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
		$community = empty($this->AdvanceFees) ? null : $this->AdvanceFees->community;
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
		$community = empty($this->PropertyFees) ? null : $this->PropertyFees->community;
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
		$community = empty($this->PropertyFees) ? null : $this->PropertyFees->community;
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
		$community = empty($this->PropertyFees) ? null : $this->PropertyFees->community;
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
		$community = empty($this->ParkingFees) ? null : $this->ParkingFees->community;
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

	public function getParkingRoomTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
			'data-original-title' => '彩之云门牌号:' . (empty($this->ParkingFees) ? "" : $this->ParkingFees->room)),
			empty($this->ParkingFees) ? "" : $this->ParkingFees->room);
	}


	public function getParkingParentBranchName()
	{
		$community = empty($this->ParkingFees) ? null : $this->ParkingFees->community;
		if (!empty($community)) {
			//$_barchName = Branch::getMyParentBranchName($community->branch_id, true);
			$_barchName = $community->ICEGetCommunityBranchesNames();
		} else {
			$_barchName = "-";
		}
		return $_barchName;
	}

	public function getParkingParentCommunityName()
	{
		$community = empty($this->ParkingFees) ? null : $this->ParkingFees->community;
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


}
