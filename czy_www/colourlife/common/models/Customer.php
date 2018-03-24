<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property integer $id
 * @property integer $community_id
 * @property integer $build_id
 * @property integer $room
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $name
 * @property string $mobile
 * @property string $email
 * @property integer $create_time
 * @property integer $last_time
 * @property string $last_ip
 * @property integer $state
 * @property integer $is_deleted
 * @property integer $is_show_in_neighbor
 * @property integer $nickname
 * @property integer $credit
 * @property integer $audit
 * @property string $portrait
 */
class Customer extends CActiveRecord
{
	/**
	 * @var string 模型名
	 */
	public $modelName = '业主';

	public $file;
	public $branch_id;
	public $region;
	public $create_start_time;
	public $create_end_time;
	public $last_start_time;
	public $last_end_time;
	public $count;
	public $reg_type;
	public $reg_identity;
	public $balance;
	public $start_time_klint;
	public $end_time_klint;
	public $channel;
	public $bangding;
	public $note;
	public $create_time;
	public $gender;//TODO:kakatool  增加性别参数

	private $parameters='start_date:date:true:开始日期，如2015-01-01,end_date:date:true:结束日期，如2015-12-31';
	//private $finance_customer_sync_queue = "finance_customer_sync";//用户金融账号同步队列
	private $finance_customer_sync_failed_queue = "finance_customer_sync_failed"; //失败的用户信息
	//private $finance_customer_sync_counter = 'finance_customer_sync_counter';

	public $availabilityCount = 0;//设置mobile账号数量
	public $invalidCount = 0;//未设置mobile账号数量

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Customer the static model class
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
		return 'customer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('community_id, build_id, room, name, nickname, state, is_show_in_neighbor', 'required'),
			array('name,community_id,build_id,room,username, password, mobile,email', 'required', 'on' => 'create'),
			array('username, mobile', 'unique', 'on' => 'create'),
			array('mobile', 'common.components.validators.ChinaMobileValidator', 'on' => 'create, update,edit'),
			array('mobile', 'exists', 'on' => 'create'),
			array('email', 'exists', 'on' => 'create'),
			array('reg_type,community_id,credit, build_id, create_time, last_time, state, is_deleted, is_show_in_neighbor,gender', 'numerical', 'integerOnly' => true),
			array('room', 'length', 'max' => 32),
			array('username,reg_identity, name, nickname', 'length', 'max' => 64),
			array('mobile', 'length', 'max' => 15),
			array('email', 'length', 'max' => 25),
			array("email", 'email'),
			array("balance, consume_balance, integral ,channel, username", "safe"),
			array("first_do_lucky", "safe"),
			array('name', 'checkName', 'on' => 'create, edit'),
			array('name', 'required', 'on' => 'edit'),
			array('community_id,build_id,room', 'required', 'on' => 'updatecomunity'),
			array('username', 'required', 'on' => 'update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id,branch_id,region,balance, community_id, build_id, room, username, nickname, password, salt, name, mobile, email, create_time, last_time, last_ip, state, is_deleted, is_show_in_neighbor,create_start_time,create_end_time,last_start_time,last_end_time,reg_type,reg_identity,pay_password,gender', 'safe', 'on' => 'search'),
			array('id,branch_id,region, balance,community_id, build_id, room, username, nickname, password, salt, name, mobile, email, create_time, last_time, last_ip, state, is_deleted, is_show_in_neighbor,create_start_time,create_end_time,last_start_time,last_end_time,pay_password,gender', 'safe', 'on' => 'report_search'),
			array('id,branch_id,region, balance,community_id, build_id, room, username, nickname, password, salt, name, mobile, email, create_time, last_time, last_ip, state, is_deleted, is_show_in_neighbor,create_start_time,create_end_time,last_start_time,last_end_time,pay_password,gender', 'safe', 'on' => 'total_report_search'),
			array('id,branch_id,region,balance, community_id, build_id, room, username, nickname, password, salt, name, mobile, email, create_time, last_time, last_ip, state, is_deleted, is_show_in_neighbor,create_start_time,create_end_time,last_start_time,last_end_time,reg_type,reg_identity,start_time_klint,end_time_klint,pay_password,gender', 'safe', 'on' => 'cpa_search'),
			array('file', 'file', 'types' => 'jpg, gif, png', 'safe' => true, 'on' => 'portrait'), // 增加图片文件
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
			'build' => array(self::BELONGS_TO, 'Build', 'build_id'),
			'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
			'friends' => array(self::HAS_MANY, 'Friend', 'friend_id'),
			'complains' => array(self::HAS_MANY, 'Complain', 'customer_id'),
			'repairs' => array(self::HAS_MANY, 'Repair', 'customer_id'),
			'reserve' => array(self::HAS_MANY, 'Reserve', 'customer_id'),
			'gesture' => array(self::HAS_ONE, 'CustomerGesturePwd', 'customer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'community_id' => '所属小区',
			'build_id' => '所属楼栋',
			'room' => '门牌号',
			'username' => '账号',
			'nickname' => '昵称',
			'password' => '密码',
			'email' => '邮件地址',
			'salt' => '加密码',
			'name' => '姓名',
			'mobile' => '手机号码',
			'email' => 'Email',
			'create_time' => '注册时间',
			'last_time' => '最后登录时间',
			'last_ip' => '最后登录IP',
			'state' => '状态',
			'is_show_in_neighbor' => '是否显示在邻里中心',
			'audit' => '审核状态',
			'credit' => '当前积分',
			'branch_id' => '所属部门',
			'region' => '地区',
			'create_start_time' => '注册时间开始',
			'create_end_time' => '注册时间结束',
			'last_start_time' => '最后登录时间开始',
			'last_end_time' => '最后登录时间结束',
			'reg_type' => '注册类型',
			'reg_identity' => '注册识别码',
			'balance' => '红包余额',
			'consume_balance' => '可消费余额',
			'first_do_lucky' => '是否第一次抽奖',
			'customer_code' => '用户邀请码',
			'invite_code' => '邀请人邀请码',
			'start_time_klint' => '开始时间',
			'end_time_klint' => '截止时间',
			'channel' => '下载渠道',
			'bangding' => '绑定渠道',
			'pay_password' => '支付密码',
			'note' => '修改手机号码原因',
			'gender' => '性别',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
		/*$community_ids = array();

		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		$branchIds = $employee->mergeBranch;
		//判断小区权限
		if (!empty($employee->branch)) {
			foreach ($branchIds as $branchId) {
				$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
				$community_ids = array_unique(array_merge($community_ids, $data));
			}
			$criteria->addInCondition('community_id', $community_ids);
		}

		if ($this->branch_id != '') {
			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');

			$criteria->addInCondition('community_id', $community_ids);
		} else if ($this->region != '') //如果有地区
		{
			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');
			$criteria->addInCondition('community_id', $community_ids);
		}*/

		// TODO community_id in $community_ids 的搜索，很早之前就去掉了
		//$criteria->addInCondition('community_id', $community_ids);

		$criteria->compare('id', $this->id);
		$criteria->compare('community_id', $this->community_id);

		$criteria->compare('build_id', $this->build_id);
		$criteria->compare('room', $this->room);
		$criteria->compare('username', $this->username, true);
		$criteria->compare('nickname', $this->nickname, true);
		$criteria->compare('password', $this->password, true);
		$criteria->compare('salt', $this->salt, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('mobile', $this->mobile, true);
		$criteria->compare('email', $this->email, true);
		$criteria->compare('create_time', $this->create_time);
		$criteria->compare('last_time', $this->last_time);
		$criteria->compare('last_ip', $this->last_ip, true);
		$criteria->compare('state', $this->state);
		$criteria->compare('is_show_in_neighbor', $this->is_show_in_neighbor);
		$criteria->compare('audit', $this->audit);
		$criteria->compare('reg_type', $this->reg_type);
		$criteria->compare('reg_identity', $this->reg_identity);
		$criteria->compare('gender', $this->gender);

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'create_time desc',
			)
		));
	}


	/*
	 * 用户报表搜索方法
	 * */
	public function report_search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;
		$community_ids = array();
		//  $_SESSION['Customer']="";
		if (isset($_GET['Customer']) && !empty($_GET['Customer'])) {
			$_SESSION['Customer'] = array();
			$_SESSION['Customer'] = $_GET['Customer'];
		}
		if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
			if (isset($_SESSION['Customer']) && !empty($_SESSION['Customer'])) {
				foreach ($_SESSION['Customer'] as $key => $val) {
					if ($val != "") {
						$this->$key = $val;
					}
				}
			}
		}
		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		$branchIds = $employee->mergeBranch;
		if (isset($_GET['state'])) {
			$this->state = $_GET['state'];
		} else if (isset($_GET['region'])) {
			$this->region = $_GET['region'];
		}
		//判断小区权限
		if (!empty($employee->branch)) {
			foreach ($branchIds as $branchId) {
				$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
				$community_ids = array_unique(array_merge($community_ids, $data));
			}
			// $community_ids = Branch::model()->findByPk($employee->branch_id)->getBranchAllIds('Community');
			$criteria->addInCondition('community_id', $community_ids);
		}

		if ($this->branch_id != '') {
			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
			$criteria->addInCondition('community_id', $community_ids);
		} else if ($this->region != '') //如果有地区
		{
			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');
			$criteria->addInCondition('community_id', $community_ids);
		}
		// $criteria->addInCondition('community_id', $community_ids);
		$criteria->compare('id', $this->id);
		$criteria->compare('community_id', $this->community_id);
		$criteria->compare('build_id', $this->build_id);
		$criteria->compare('room', $this->room);
		if ($this->create_start_time != "") {
			$criteria->addCondition('create_time>=' . strtotime($this->create_start_time));
		}
		if ($this->create_end_time != "") {
			$criteria->addCondition('create_time<=' . strtotime($this->create_end_time . " 23:59:59"));
		}
		if ($this->last_start_time != "") {
			$criteria->addCondition('last_time>=' . strtotime($this->last_start_time));
		}
		if ($this->create_end_time != "") {
			$criteria->addCondition('last_time<=' . strtotime($this->last_end_time . " 23:59:59"));
		}
		$criteria->compare('state', $this->state);
		$criteria->compare('gender', $this->gender);
		//$criteria->limit=30;
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/*
	 * 汇总用户报表搜索方法
	 * */
	public function total_report_search()
	{
		$criteria = new CDbCriteria;
		$community_ids = array();
		$branchParentId = F::getBranchParentId();
		$regionId = F::getRegionId();
		if (isset($_GET['Customer']) && !empty($_GET['Customer'])) {
			$_SESSION['Customer'] = array();
			$_SESSION['Customer'] = $_GET['Customer'];
		}
		if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportTotalReport') {
			if (isset($_SESSION['Customer']) && !empty($_SESSION['Customer'])) {
				foreach ($_SESSION['Customer'] as $key => $val) {
					if ($val != "") {
						$this->$key = $val;
					}
				}
			}
		}
		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		$branchIds = $employee->mergeBranch;
		if (isset($_GET['state'])) {
			$this->state = $_GET['state'];
		} else if (isset($_GET['region'])) {
			$this->region = $_GET['region'];
		}
		//判断小区权限
		if (!empty($employee->branch)) {
			foreach ($branchIds as $branchId) {
				$data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
				$community_ids = array_unique(array_merge($community_ids, $data));
			}
			$criteria->addInCondition('community_id', $community_ids);
			$select = "count(*) count," . $branchParentId . ",t.community_id";
			$criteria->select = array($select);
			$criteria->group = "branch_parent_id";
		}

		if ($this->branch_id != '') {
			$community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
			$criteria->addInCondition('community_id', $community_ids);
			if ($this->build_id != "") {
				$criteria->compare('build_id', $this->build_id);
				$select = "count(*) count," . $branchParentId . ",`t`.community_id,`t`.build_id";
				$criteria->select = array($select);
				$criteria->group = "`t`.build_id,branch_parent_id";
			} else {
				$select = "count(*) count," . $branchParentId . ",t.community_id";
				$criteria->select = array($select);
				$criteria->group = "branch_parent_id";
			}
		} else if ($this->region != '') //如果有地区
		{
			$community_ids = Region::model()->getRegionCommunity($this->region, 'id');
			$criteria->addInCondition('community_id', $community_ids);
			if ($this->build_id != "") {
				$criteria->compare('build_id', $this->build_id);
				$select = "count(*) count," . $regionId . ",`t`.community_id,`t`.build_id";
				$criteria->select = array($select);
				$criteria->group = "`t`.build_id,region_id";
			} else {
				$select = "count(*) count," . $regionId . ",`t`.community_id";
				$criteria->select = array($select);
				$criteria->group = "region_id";
			}
		}
		$criteria->addInCondition('community_id', $community_ids);
		$criteria->compare('id', $this->id);
		$criteria->compare('community_id', $this->community_id);
		$criteria->compare('build_id', $this->build_id);
		$criteria->compare('room', $this->room);
		$criteria->compare('channel', $this->channel);
		if ($this->create_start_time != "") {
			$criteria->addCondition('create_time>=' . strtotime($this->create_start_time));
		}
		if ($this->create_end_time != "") {
			$criteria->addCondition('create_time<=' . strtotime($this->create_end_time . " 23:59:59"));
		}
		if ($this->last_start_time != "") {
			$criteria->addCondition('last_time>=' . strtotime($this->last_start_time));
		}
		if ($this->create_end_time != "") {
			$criteria->addCondition('last_time<=' . strtotime($this->last_end_time . " 23:59:59"));
		}

		$criteria->compare('state', $this->state);
		$criteria->compare('gender', $this->gender);

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}


	public function cpa_search()
	{
		$criteria = new CDbCriteria;
		$criteria->select = "FROM_UNIXTIME(`t`.create_time,'%Y/%m/%d') as date_day, count(*) as cc,sum(is_success) as dd, sum(is_success_licai) as ee";
		$criteria->compare("`t`.create_time", ">=" . strtotime($this->start_time_klint));
		$criteria->compare("`t`.create_time", "<=" . strtotime($this->end_time_klint));
		$criteria->group = "date_day";
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}


	/**
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'IpBehavior' => array(
				'class' => 'common.components.behaviors.IpBehavior',
				'createAttribute' => 'last_ip',
				'updateAttribute' => 'last_ip'
			),
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => null,
				'setUpdateOnCreate' => true,
			),
			'IsDeletedBehavior' => array(
				'class' => 'common.components.behaviors.IsDeletedBehavior',
			),
			'AuditBehavior' => array(
				'class' => 'common.components.behaviors.AuditBehavior',
			),
			'StateBehavior' => array(
				'class' => 'common.components.behaviors.StateBehavior',
			),
			'UserBehavior' => array(
				'class' => 'common.components.behaviors.UserBehavior',
			),
		);
	}

	public function getCommunitySelection()
	{
		$community = Community::model()->enabled()->findAll();
		$list = array();
		if ($community) $list = CHtml::listData($community, 'id', 'name');
		return $list;
	}


	/*
	 * 获取注册时间格式"Y-m-d H:i :s"
	 * */
	public function getCreateTime()
	{
		return date("Y-m-d H:i:s", $this->create_time);
	}


	/*
	* 获取最后登录时间格式"Y-m-d H:i :s"
	* */
	public function getLastTime()
	{
		return date("Y-m-d H:i:s", $this->last_time);
	}

	public function getBuildSelection()
	{
		$build = Build::model()->enabled()->findAll();
		$list = array();
		if ($build) $list = CHtml::listData($build, 'id', 'name');
		return $list;
	}

	/**
	 * 获取小区
	 * @return string
	 */
//    ICE获取小区名
	public function getCommunityName()
	{
//        return ($this->community === null) ? '' : trim($this->community->name);
		$communityName = ICECommunity::model()->findByPk($this->community_id);
		if (!empty($communityName)) {
			return $communityName['name'];
		} else {
			return '';
		}
	}

	/**
	 * 获取楼栋
	 * @return string
	 */
	public function getBuildName()
	{
		return ($this->build === null) ? '' : $this->build->name;
	}

	protected function afterConstruct()
	{
		$this->is_show_in_neighbor = 1;
		return parent::afterConstruct();
	}

//    public function changeCredit($type, $data = array())
//    {
//        $config = Yii::app()->config->credit;
//        $log = new CreditLog;
//        $log->customer_id = $this->id;
//        $log->type = $type;
//        switch ($type) {
//            case 'increase':
//                $log->credit = $data['credit'];
//                $employeeName = Yii::app()->user->name;
//                $log->note = "员工账号 {$employeeName} 赠送了 {$log->credit} 个积分。\n{$data['note']}";
//                break;
//            case 'decrease':
//                $log->credit = -$data['credit'];
//                $employeeName = Yii::app()->user->name;
//                $log->note = "员工账号 {$employeeName} 扣除了 {$log->credit} 个积分。\n{$data['note']}";
//                break;
//            case 'order':
//                if ($config['orderType']) {
//                    $log->credit = $config['order'];
//                } else {
//                    $log->credit = intval($config['orderRate'] * $data['amount']);
//                }
//                $log->note = "{$type} 增加了 {$log->credit} 个积分。";
//                break;
//            default:
//                $log->credit = $config[$type];
//                $log->note = "{$type} 增加了 {$log->credit} 个积分。";
//                break;
//        }
//        $log->save();
//        $this->updateByPk($this->id, array(
//            'credit' => $this->credit + $log->credit,
//        ));
//    }

	public function changeCredit($type, $data = array())
	{
		if (F::getConfig('integralSwitch', 'switch')) {
			$model = IntegralEvent::model()->find("`key`=:key AND state=:state", array(":key" => $type, ":state" => Item::STATE_ON));
			$log = new CreditLog;
			$log->customer_id = !empty($this->id) ? $this->id : Yii::app()->user->id;//有可能是物业后台进行用户赠送扣分
			$log->type = $type;
			switch ($type) {
				case 'increase':
					$log->credit = $data['credit'];
					$employeeName = Yii::app()->user->name;
					$log->note = "员工账号 {$employeeName} 赠送了 {$log->credit} 个积分。\n{$data['note']}";
					break;
				case 'decrease':
					$log->credit = -$data['credit'];
					$employeeName = Yii::app()->user->name;
					$log->note = "员工账号 {$employeeName} 扣除了 {$log->credit} 个积分。\n{$data['note']}";
					break;
				case in_array($type, array(
					'property_fees',
					'advance_fees',
					'parking_fees',
					'shopping',
					'virtual_recharge',
					'property_activity',
				)):
					if ($model->type) {
						$log->credit = ($model->num) * $data['amount'];
					} else {
						$log->credit = $model->num;
					}
					$log->note = "{$type} 增加了 {$log->credit} 个积分。";
					break;
				default:
					$log->credit = empty($model->num) ? '' : $model->num;
					$log->note = "{$type} 增加了 {$log->credit} 个积分。";
					break;
			}
			$log->credit = (int)$log->credit;
			$log->save();
			$this->updateByPk($this->id, array(
				'credit' => $this->credit + $log->credit,
			));
		}
	}

	protected function beforeValidate()
	{
		$this->file = CUploadedFile::getInstanceByName('file');
		return parent::beforeValidate();
	}

	/**
	 * 处理图片
	 * @return bool
	 */
	protected function beforeSave()
	{
		if ($this->is_complete == 0 && (!empty($this->community_id)) && (!empty($this->build_id)) && (!empty($this->room))) {
			$this->is_complete = 1;

		}

		if (!empty($this->file)) {
			if (!empty($this->portrait))
				$df = $this->portrait;
			$this->portrait = Yii::app()->imageFile->saveToFile($this->file, $this->portrait);

			if (!empty($df)) {
				Yii::app()->imageFile->delete($df);
			}
		}
		return parent::beforeSave();
	}

	/*
	 * 增加wetown用户同步数据接口
	 */
	protected function afterSave()
	{
//		if ($this->getIsNewRecord()) {
//			//注册成功时赠送积分
//			$key = 'customer_register';
//			$this->changeCredit($key);
//		}
		//$this->updateWetown();
		$this->updateFinance();
		$this->setCustomerMobileToCache($this->mobile);
		return parent::afterSave();
	}

	/**
	 * 增加彩之云全国饭票金融平台账号
	 */
	public function updateFinance()
	{
		$updateData = array();
		try {
			//
			$customer = Customer::model()->findByPk($this->id);
			if (!$customer) {
				return;
			}

			$result = FinanceMicroService::getInstance()->getCustomerPano();
			Yii::log('result:' . json_encode($result));
			$pano = $result['pano'];
			$atid = $result['atid'];

			$financeType = FinancePayType::model()->find("pano=:pano and atid=:atid and status=1", array(':pano' => $pano, ':atid' => $atid));
			$fanpiaoId = 0;
			if (!empty($financeType)) {
				$fanpiaoId = $financeType->id;
			}
			//判断账号是否已存在
			$customerAccount = FinanceCustomerRelateModel::model()->find(
				'customer_id = :customer_id and pano = :pano and atid = :atid',
				array(
					':customer_id' => $this->id,
					':pano' => $pano,
					':atid' => $atid,
				)
			);

			$gender = $customer->gender;
			$realname = $customer->name;
			$mobile = $customer->mobile;
			$pay_password = $customer->pay_password;
			if (!empty($customerAccount)){ //存在的话同步账号信息
				$modifyResult = FinanceMicroService::getInstance()->modifyClient($pano, $customerAccount->cno, $realname, $mobile, $gender,'','用户信息更新');
				$arr = array();
				if ($customerAccount->mobile != $mobile){
					$arr['mobile'] = $mobile;
				}
				if ($customerAccount->name != $realname){
					$arr['name'] = $realname;
				}
				if ($customerAccount->pay_password != $pay_password){
					$arr['pay_password'] = $pay_password;
				}
				if (!empty($arr)){ //更新关联表
					$updateResult = FinanceCustomerRelateModel::model()->updateByPk($customerAccount->id,$arr);
				}
				return true;
			}
			$customer = FinanceMicroService::getInstance()->addClientClient($pano, '', $realname, $mobile, $gender, '', '彩之云后台导入', 0);
			//获取cano
			if ($customer && isset($customer['account'])) {
				$account = $customer['account'];
				if ($account && isset($account['cano'])) {
					$cano = $account['cano'];
				}
			}
			if ($customer && isset($customer['client'])) {
				$client = $customer['client'];
				if ($client && isset($client['cno'])) {
					$cno = $client['cno'];
				}
			}
			$updateData = array(
				'pano' => $pano,
				'cno' => $cno,
				'cano' => $cano,
				'customer_id' => intval($this->id),
				'mobile' => $mobile,
				'name' => $realname,
				'pay_password' => $pay_password,
				'atid' => $atid,
				'fanpiaoid' => $fanpiaoId
			);

			//更新本地数据
			FinanceCustomerRelateModel::model()->addFinanceCustomerRelation($updateData);
		} catch (Exception $e) {
			throw new CHttpException($e->getMessage());
			Yii::app()->rediscache->executeCommand('RPUSH', array($this->finance_customer_sync_failed_queue, json_encode($updateData)));
		}
	}

	/*
	 * 增加wetown用户同步数据接口
	 */
	protected function updateWetown()
	{
		//同步wetown
		$customer = Customer::model()->findByPk($this->id);
		$username = $customer->username;
		$realname = $customer->name;
		$password = $customer->password;
		$cid = $customer->community_id;
		/*if (!empty($customer->community_id)) $cname = $customer->community->name;
		else $cname = '';*/
		$cname = '';
		if (!empty($customer->community_id)) {
			$ICECommunity = ICECommunity::model()->FindByPk($customer->community_id);
			if (!empty($ICECommunity)) {
				$cname = $ICECommunity['name'];
			}
		}
		if ($cid) {
			$community = Community::model()->findByPk($cid);
			//$community = F::getRegion($community->region_id);
			$community = array(
				$community['province'],
				$community['city'],
				$community['region']
			);
			$caddress = is_array($community) ? implode('-', $community) . '-' . $cname . '-' . $customer->build->name . '-' . $customer->room : '';
		} else $caddress = '';

		$param = array(
			array('v' => 'ursername', 'must' => true),
			array('v' => 'realname', 'must' => true),
			array('v' => 'password', 'must' => true),
			array('v' => 'cid', 'must' => false),
			array('v' => 'cname', 'must' => false),
			array('v' => 'caddress', 'must' => false)
		);
		//重组post值
		$_POST = null;
		$_POST = array(
			'username' => $username,
			'realname' => $realname,
			'password' => $password,
			'cid' => $cid,
			'cname' => $cname,
			'caddress' => $caddress
		);
		$preFun = 'user/update';
		$re = new ConnectWetown();
		$re->getRemoteData($preFun, $param);
	}

	public function getCommunity()
	{
		if (!empty($this->community)) {
			return $this->community;
		} else {
			return new Community();
		}
	}

	public function getregioncommunityName()
	{

		$communityName = '';
		if (!empty($this->community)) {
			$communityName = $this->community->name;
			return $communityName;
		}
		return $communityName;

	}


	public function getThirdRegionName()
	{

		$regionName = '';
		if (!empty($this->community) && !empty($this->community->region)) {
			$regionName = $this->community->region->name;
			return $regionName;
		}
		return $regionName;

	}


	public function getThirdRegionId()
	{

		$regionId = '';
		if (!empty($this->community) && !empty($this->community->region)) {
			$regionId = $this->community->region->id;
			return $regionId;
		}
		return $regionId;

	}

	public function getSecondRegionName()
	{

		$regionName = '';
		if (!empty($this->community) && !empty($this->community->region)) {
			$regionName = $this->community->region->parent->name;
			return $regionName;
		}
		return $regionName;

	}


	public function getSecondRegionId()
	{

		$regionId = '';
		if (!empty($this->community) && !empty($this->community->region)) {
			$regionId = $this->community->region->parent->id;
			return $regionId;
		}
		return $regionId;

	}


	public function getFirstRegionName()
	{

		$regionName = '';
		if (!empty($this->community) && !empty($this->community->region)) {
			$regionName = $this->community->region->parent->parent->name;
			return $regionName;
		}
		return $regionName;

	}


	public function getFirstRegionId()
	{

		$regionId = '';
		if (!empty($this->community) && !empty($this->community->region)) {
			$regionId = $this->community->region->parent->parent->id;
			return $regionId;
		}
		return $regionId;

	}


	public function getCommunityHtml()
	{
		$branchName = '';
		if (!empty($this->community) && !empty($this->community->branch)) {
			$branchName = $this->community->branch->getMyParentBranchName($this->community->branch_id);
		}
		return CHtml::tag('span', array('rel' => 'tooltip',
			'data-original-title' => $branchName),
			$this->getCommunityName());
	}

	public function getBranchNames()
	{
		$branchName = '';
		if (!empty($this->community) && !empty($this->community->branch)) {
			$branchName = $this->community->branch->getMyParentBranchName($this->community->branch_id);
		}
		return $branchName;
	}

//	ICE 接入小区组织架构名
	public function getCommunityRegionName()
	{
//		$branchName = '';
//		if (!empty($this->community) && !empty($this->community->branch)) {
//			$branchName = $this->community->branch->getMyParentBranchName($this->community->branch_id);
//		}
//		return $branchName . $this->getCommunityName();
//	ICE 接入小区组织架构名
		$branchName = '';
		if (!empty($this->community_id)) {
			$community = ICECommunity::model()->FindByPk($this->community_id);
			if (!empty($community)) {
				$branchName = $community->branchString;
			}
		}
		return $branchName . $this->getCommunityName();
	}

	//获得小区所在的地区，isself=true，包括小区
	public function getCommunityBelongRegion($isself = false)
	{
		$community = Community::model()->findByPk($this->community_id);
		if (!empty($community)) {
			//$_regionName = Region::getMyParentRegionNames($community->region_id, true);
			$_regionName = $community->ICEGetCommunityRegionsNames();
		} else {
			$_regionName = "";
		}
		if ($isself) {
			$_regionName .= '-' . $community->name;
		}
		return $_regionName;
	}


	public function getregion_community($isself = true)
	{
		$community = Community::model()->findByPk($this->community_id);
		if (!empty($community)) {
			//$_regionName = Region::getMyParentRegionNames($community->region_id, true);
			$_regionName = $community->ICEGetCommunityRegionsNames();
		} else {
			$_regionName = "";
		}
		if ($isself) {
			$_regionName .= ' - ' . $community["name"];
		}
		return $_regionName;
	}


	public function getPortraitUrl()
	{
		if ('HTTP' == strtoupper(substr($this->portrait, 0, 4)))
			return $this->portrait;

		return Yii::app()->imageFile->getUrl($this->portrait);
	}

	///查询省市区
	public function getRegions()
	{
//		$regions = array();
//		if (!empty($this->community)) {
//			$region = Region::model()->enabled()->findByPk($this->community->region_id);
//			if (!empty($region)) {
//				$regions = $region->getParents();
//				$regions[] = $region;
//			}
//		}
//
//		return $regions;

//		ICE 接入ice数据
		$regions = array();
		$ICECommunity = ICECommunity::model()->ICEFindByPk($this->community_id);
		if (!empty($ICECommunity)) {
			$province = new stdClass();
			$province->id = $ICECommunity['provincecode'];
			$province->name = $ICECommunity['province'];
			$city = new stdClass();
			$city->id = $ICECommunity['citycode'];
			$city->name = $ICECommunity['city'];
			$district = new stdClass();
			$district->id = $ICECommunity['regioncode'];
			$district->name = $ICECommunity['region'];
			$regions[] = $province;
			$regions[] = $city;
			$regions[] = $district;
		}
		return $regions;
	}

	public function auditOK()
	{
		//  return $this->updateByPk($this->id, array('name' => 1));
		$model = Customer::model()->findByPk($this->id);
		$model->name = $this->name;
		$model->build_id = $this->build_id;
		$model->audit = 1;
		$model->room = $this->room;
		if ($model->update()) {
			Yii::log(Yii::app()->user->name . "审核业主{$model->id}，审核后业主名字为{$model->name},楼栋为{$model->getBuildName()},房间号为{$model->room},审核后状态为'审核通过'", CLogger::LEVEL_INFO, 'colourlife.backend.customer.Auditok');
			return true;
		}
	}

	public function auditNo()
	{
		if ($this->updateByPk($this->id, array('audit' => 0))) {
			Yii::log(Yii::app()->user->name . "审核业主{$this->id}，审核后业主名字为{$this->name},楼栋为{$this->getBuildName()},房间号为{$this->room},审核后状态为'待审核'", CLogger::LEVEL_INFO, 'colourlife.backend.customer.Auditok');
			return true;
		}
	}


	//获取小区地址
	public function getCommunityAddress()
	{
		$community = Community::model()->findByPk($this->community_id);
		if (!empty($community)) {
//            $_regionName = Region::model()->getMyRegion($community->region_id);
//            $_regionNames=implode("-",$_regionName);
//          ICE 接入ICE的地区数据
			$_regionNames = $userAddress = str_replace(' - ', '-', $community->regionString);;
			$_regionNames .= '-' . trim($community->name);
		} else {
			$_regionNames = "";
		}
		return $_regionNames;
	}

	/*
	 * @version 获取省市区地址
	 */
	public function getThreeAddress()
	{
		$community = Community::model()->findByPk($this->community_id);
		if (!empty($community)) {
			//$_regionName = Region::model()->getMyRegion($community->region_id);
			$_regionName = array(
				$community['province'],
				$community['city'],
				$community['region']
			);
		} else {
			$_regionName = array();
		}
		$_regionNames = implode("-", $_regionName);
//        $_regionNames .='-'.  $community->name;
		return $_regionNames;
	}


	public function getTypeName()
	{
		switch ($this->reg_type) {
			case 1:
				return 'android';
				break;
			case 2:
				return 'ios';
				break;
			default:
				return '网站';
				break;
		}
	}
	//获取格式化的注册时间
	public function getRegTime()
	{
		return date('Y-m-d H:i:s',$this->create_time);
	}

	/*汇总统计全国APP注册量 获取参数的数组形式
	 * @return array( array('name', 'type', 'require', 'desc')
     *         )
	 */
	public function getParameterArray()
	{
		$params = array();
		if (! empty($this->parameters))
		{
			$str_parameters = $this->parameters;
			$str_parameters = str_replace(" ", "", $str_parameters);
			$str_parameters = str_replace("\r", "", $str_parameters);
			$str_parameters = str_replace("\n", "", $str_parameters);
			foreach (explode(",", $str_parameters) as $p)
			{
				$param = explode(":", $p);
				if (count($param) != 4)
				{
					continue;
				}
				$params[] = $param;
			}
		}
		return $params;
	}



	public function getRegIdentity()
	{
		if (empty($this->reg_identity)) {
			return '';
		}
		switch ($this->reg_type) {
			case '0':
				if ($model = CustomerUserAgent::model()->findByPk($this->reg_identity)) {
					return $model->update_user_agent;
				} else {
					return '记录被删除';
				}
				break;
			case 1:
			case 2:
				if ($model = CustomerApiAuth::model()->findByPk($this->reg_identity)) {
					return $model->secret;
				} else {
					return '记录被删除';
				}
				break;
		}
	}

	public function checkName($attribute, $params)
	{
		if (!preg_match("/^[0-9a-zA-Z_\x{4e00}-\x{9fa5}]+$/u", $this->name)) {
			$this->addError($attribute, "真实姓名只能中文、字母、数字、下划线组成");
		}
	}

	/**
	 * @param $order_id
	 * @return bool
	 * 扣用户积分
	 */
	static public function consumeIntegral($order_id)
	{
		$switchArr = Yii::app()->config->integralSwitch;
		$order = Order::model()->findByPk($order_id);
		$integral = 0;
		foreach ($order->good_list as $goods) {
			if ($goods->use_integral != 1) {
				$integral += $goods->integral;
			}
		}
		if ($integral <= 0) {//如果用户积分余额不足。直接失败，更新关联商品的使用积分字段为已使用
			OrderGoodsRelation::model()->updateAll(array('use_integral' => 1), 'order_id=:order_id',
				array(':order_id' => $order_id));
			return true;
		} else {//用户有积分，
			if ($switchArr['switch']) {
				$buyer_id = $order->buyer_id;//业主
				$customer = Customer::model()->findByPk($buyer_id);
				$newIntegral = 0;//用户抵扣积分后的余额
				$minusIntegral = 0;//实际抵扣的积分
				//ps:这里唐静确认的。用户积分抵扣商品金额时。不管使用多少积分。如果能扣则全扣。否则扣为0也算成功。
				if ($customer->credit >= $integral) {
					$newIntegral = ($customer->credit - $integral);
					$minusIntegral = $integral;
				} else {
					$minusIntegral = $customer->credit;
					$newIntegral = 0;
				}
				$num1 = $customer->updateByPk($buyer_id, array('credit' => $newIntegral));
				//Customer::model()->updateByPk($buyer_id,array('credit'=>$newIntegral));
				$num2 = OrderGoodsRelation::model()->updateAll(array('use_integral' => 1), 'order_id=:order_id',
					array(':order_id' => $order_id));
				if ($num1 && $num2) {
					$note = "用户购买商品使用积分抵扣，应扣积分" . $integral . ",实际扣除积分" . $minusIntegral;
					//创建积分收支记录
					CreditLog::createLog($buyer_id, "decrease", $integral, $note);
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}
	}


	//通过小区id获取业主的ID
	public function getCustomerIdsByCommunity(array $community_ids)
	{
		$customer_ids = array();
		if (empty($community_ids)) {
			return $customer_ids;
		}
		$criteria = new CDbCriteria();
		$criteria->addInCondition("community_id", $community_ids);
		$criteria->compare("`state`", Item::STATE_ON);
		$criteria->compare("`is_deleted`", Item::DELETE_ON);
		$criteria->compare("`status`", 0);
		$criteria->compare("`audit`", 0);
		$criteria->distinct = true;
		$criteria->select = 'id';
		$model = Customer::model()->findAll($criteria);
		if (empty($model)) {
			return $customer_ids;
		}
		foreach ($model as $val) {
			$id = $val['id'];
			$customer_ids[] = $id;
		}
		return $customer_ids;
	}


	static $bang_ding = array(
		'All' => "全部",
		'is_success' => "E租房",
		'is_success_licai' => "E理财",
	);

//获取用户的地址的小区类型
	public function getCommunityType()
	{
		if (isset($this->community)) {
			return $this->community->type;
		}
	}

	//获取用户的地址的小区类型
	public function getType()
	{
		if (isset($this->community)) {
			return $this->community->type;
		}
	}


	//注册时如果有选择公司，把关联关系写进公司与注册人的关系表中
	public function saveRelation($build_id, $room)
	{
		if ($build_id != 0) {
			$room = CompanyRoom::model()->find("build_id=:build_id AND room=:room AND state=0", array(":build_id" => $build_id, ":room" => $room));
			if (!empty($room)) {
				$company = Company::model()->find("build_id=:build_id AND room_id=:room_id", array(":build_id" => $build_id, ":room_id" => $room->id));
				if (!empty($company)) {
					$companyCustomerRelation = CompanyCustomerRelation::model()->find("company_id=:company_id AND customer_id=:customer_id", array(":company_id" => $company->id, ":customer_id" => Yii::app()->user->id));
					if (empty($companyCustomerRelation)) {
						$companyCustomerRegisterRelation = new CompanyCustomerRegisterRelation();
						$companyCustomerRegisterRelation->customer_id = Yii::app()->user->id;
						$companyCustomerRegisterRelation->company_id = $company->id;
						$companyCustomerRegisterRelation->save();
					}
				}
			}
		}

	}

	/**
	 * 获取业主首页配置关联标签
	 * @return array
	 */
	public function getRelationTags()
	{
		$tags = array();

		$findTags = HomeConfigCustomerTagRelation::model()->findAll(
			'customer_id = :customer_id',
			array(
				'customer_id' => Yii::app()->user->id
			)
		);
		if ($findTags) {
			foreach ($findTags as $tag) {
				$tags[] = $tag->tagid;
			}
		}

		return $tags;
	}

	/**
	 * 手势密码
	 * @return int
	 */
	public function getGesture()
	{
		$result = 0;
		if (!$this->gesture)
			return $result;

		$result = 2;
		if ($this->gesture->state)
			$result = 1;

		return $result;
	}

	protected function ICEGetLinkageRegionDefaultValueForUpdate()
	{
		return array();
	}

	public function ICEGetLinkageRegionDefaultValueForSearch()
	{
		$searchRegion = $this->ICEGetSearchRegionData(isset($_GET['ICECommunity']) ? $_GET['ICECommunity'] : array());

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

	public function getBalance($isLocal = false, $pano = '', $atid = '', $note = '')
	{
		if ($isLocal) { //地方饭票
			$withBalance = false;
		} else {
			$panoArr = FinanceMicroService::getInstance()->getCustomerPano();
			$atid = isset($panoArr['atid']) && $panoArr['atid'] ? $panoArr['atid'] : '';
			$pano = isset($panoArr['pano']) && $panoArr['pano'] ? $panoArr['pano'] : '';
			$withBalance = true;
		}
		if (!$pano || !$atid) {
			throw new CHttpException(400, '彩之云金融平台账号未配置');
		}
		$customerAccount = FinanceCustomerRelateModel::model()->find(
			'customer_id = :customer_id and pano = :pano and atid = :atid',
			array(
				':customer_id' => $this->id,
				':pano' => $pano,
				':atid' => $atid
			)
		);

		if ($isLocal && !$customerAccount) {
			throw new CHttpException(400, '彩之云金融平台地方饭票账号未配置');
		}
		if (empty($customerAccount)) {
			Yii::log(
				sprintf(
					'获取账户余额失败，账号未同步至金融平台: %s.',
					$this->username
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.common.models.Customer.getBalance'
			);
			FinanceAccountRelationService::getInstance()->migrateCustomer($this->mobile, $withBalance, array('pano' => $pano, 'atid' => $atid, 'note' => $note));
			throw new CHttpException(400, '获取账户余额失败：系统正在同步，请稍后再试！');
		}

		$clientAccount = array();
		try {
			$clientAccount = FinanceMicroService::getInstance()->queryClient(
				$customerAccount['pano'],
				$customerAccount['cano']
			);
		} catch (Exception $e) {
			Yii::log(
				sprintf(
					'获取账户余额失败: %s， %s[%s]',
					$this->mobile,
					$e->getMessage(),
					$e->getCode()
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.common.models.Customer.getBalance'
			);
		}

		if ($clientAccount
			&& isset($clientAccount['account'])
			&& isset($clientAccount['account']['money'])
		) {
			Yii::log(
				sprintf(
					'获取到的账户余额: %s， %s',
					$this->mobile,
					$clientAccount['account']['money']
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.common.models.Customer.getBalance'
			);
			return $clientAccount['account']['money'];
		} else {
			Yii::log(
				sprintf(
					'获取账户余额失败，无法解析返回数据: %s， %s',
					$this->mobile,
					json_encode($clientAccount)
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.common.models.Customer.getBalance'
			);

			throw new CHttpException(400, '无法获取账户余额');
		}
	}

	public function queryBalance()
	{
		try {
			$balance = $this->getBalance();
		} catch (Exception $e) {
			$balance = 0.00;
		}

		return $balance;
	}

	/**
	 * 从数据获取用户账号（手机号）列表
	 * @param int $skip
	 * @param int $limit
	 * @return mixed
	 */
	public function getCustomerMobileList($skip = 0, $limit = 10000)
	{
		$sql = "select mobile from " . $this->tableName() . " where 1=1  limit $skip,$limit";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		echo sprintf(
			'sql语句为: %s 。%s',
			$sql,
			PHP_EOL
		);
		return $result;
	}

	/**
	 * 获取账号缓存内容
	 * @return array
	 */
	public function getCustomerMobileCacheContent($mobile)
	{
		//	    key设为1。
		$customer =  Customer::model()->find('mobile=:mobile', array(':mobile' => $mobile));
		return
			[
				'password' => $customer->password,
				'salt' => $customer->salt,
				'last_time' => $customer->last_time
			];
		//return ['status'=>1];
//		$nowTime = time();
//		return [
//			"create_time" => $nowTime,
//			"update_time" => $nowTime,
//		];
	}

	/**
	 * 获取用户账号缓存cache
	 * @param string $mobile
	 * @return string
	 */
	public function getCustomerMobileCacheKey($mobile = '')
	{
		return sprintf('cache:cwy:customer:reinforce:mobile:%s', $mobile);
	}

	/**
	 * 设置单个用户信息到 Bloom Filter
	 */
	public function setCustomerMobileToCache($mobile = '')
	{
		require_once 'lib/BloomFilter.php';
		$bf = new BloomFilter(108500000, 6);
		$bf->add($mobile);
//		return Yii::app()->rediscache->set(
//			$this->getCustomerMobileCacheKey($mobile),
//			$content ? $content : $this->getCustomerMobileCacheContent($mobile),
//			96400//这个是秒钟 确定 yii中 0是否是永久保存
//		);
	}

	/**
	 * 缓存所有账号信息到 Bloom Filter
	 */
	public function allCustomerMobileToCache($skip = 0, $limit = 10000)
	{
		$customerMobiles = $this->getCustomerMobileList($skip, $limit);
		if ($customerMobiles) {
			$i = 0;
			$time = date('Y-m-d H:i:s');
			$count = count($customerMobiles);
			echo sprintf(
				'%s 开始第 %s 次同步账号，从 %s 到 %s 。%s',
				$time, $skip / $limit + 1, $skip, $skip + $limit, PHP_EOL
			);
			foreach ($customerMobiles as $customerMobile) {
				if ($customerMobile && $customerMobile['mobile']) {
					$this->setCustomerMobileToCache($customerMobile['mobile']);
					$i++;
				}
			}
			$time = date('Y-m-d H:i:s');
//          记录设置mobile账号数量
			$this->availabilityCount += $i;
//          记录未设置mobile账号数量
			$this->invalidCount += $count - $i;

			echo sprintf(
				'%s 第 %s 次同步完成，共同步账号 %s 个,未同步 %s 个（因为账号未设置mobile），从customer表中第 %s 列到 %s 列。%s',
				$time, $skip / $limit + 1, $i, $count - $i, $limit, $skip, $limit, PHP_EOL
			);
			return $this->allCustomerMobileToCache($skip + $limit, $limit);
		}

		return ['availabilityCount' => $this->availabilityCount, 'invalidCount' => $this->invalidCount];

	}

	/**
	 * 缓存所有账号信息到 Bloom Filter
	 */
	public function allCustomerMobileToCacheNew($skip = 0, $limit = 10000)
	{
		$customerMobiles = $this->getCustomerMobileList($skip, $limit);

		if ($customerMobiles) {
			$i = 0;
			$count = count($customerMobiles);
			echo sprintf(
				'%s 查找数据库完成，共找到 %s 个，开始同步账号，从 %s 到 %s ,%s',
				date('Y-m-d H:i:s'), $count,$skip ,$limit, PHP_EOL
			);
			foreach ($customerMobiles as $customerMobile) {
				if ($customerMobile && $customerMobile['mobile']) {
					$this->setCustomerMobileToCache($customerMobile['mobile']);
					$i++;
				}
			}

//          记录设置mobile账号数量
			$this->availabilityCount += $i;
//          记录未设置mobile账号数量
			$this->invalidCount += $count - $i;

			echo sprintf(
				'%s redis存放完成 %s',
				date('Y-m-d H:i:s'), PHP_EOL
			);
//			return $this->allCustomerMobileToCache($skip + $limit, $limit);
		}

		return ['availabilityCount' => $this->availabilityCount, 'invalidCount' => $this->invalidCount];

	}



	/**
	 * 获取用户缓存信息
	 * @param string $mobile
	 * @return null
	 */
	public function getCustomerMobileFromCache($mobile = '')
	{
		if (!$mobile)
			return null;

		return Yii::app()->rediscache->get($this->getCustomerMobileCacheKey($mobile));
	}

	public function getCustomerList($skip = 0, $limit = 10000)
	{
		$sql = "select id , community_id , build_id , room from " . $this->tableName() . " where 1=1  limit $skip,$limit";
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		echo sprintf(
			'sql语句为: %s 。%s',
			$sql,
			PHP_EOL
		);
		return $result;
	}

	public function updateAddress($data)
	{
		//print_r($data);exit();
		$exit = CustomerAddress::model()->findByAttributes(array('customer_id'=>$data['id']));
		if($data)
		{
			$model = new CustomerAddress();
			$model->customer_id = $data['id'];
			$model->community_id = $data['community_id'];
			$model->build_id = $data['build_id'];
			$model->room = $data['room'];
			$model->status = empty($exit) ? 1: 0;
			$model->create_time = time();
			$model->community_uuid = $this->getCommunityMine( $data['community_id']);
			if($model->save())
			{
			}
		}
	}

	public function getCommunityMine($id)
	{
		Yii::import('common.api.IceApi');
		$result = IceApi::getInstance()->getCommunityInfo($id);
		return $result['uuid'];
	}
}
