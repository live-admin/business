<?php

/**
 * This is the model class for table "push_information".
 *
 * The followings are the available columns in table 'push_information':
 * @property integer $id
 * @property integer $type
 * @property string $content
 * @property string $title
 * @property integer $create_time
 */
class PushInformation extends CActiveRecord
{

	public $modelName = "推送消息";

	const IS_TYPE_SHOP = 2; //商家
	const IS_TYPE_CUSTOMER = 0; // 业主
	const IS_TYPE_EMPLOYEE = 1; // 物业
	const IS_TYPE_UNDEFIND = -1;//未知
	const IS_TYPE_NIAN = 3;//年年卡

	public $pushType = array(
		PushInformation::IS_TYPE_CUSTOMER => '业主',
		PushInformation::IS_TYPE_EMPLOYEE => '物业',
		PushInformation::IS_TYPE_SHOP => '商家',
		PushInformation::IS_TYPE_UNDEFIND => '未知',
	);
	public $community_ids;
	public $branch;
	public $branch_id;
	public $branch_ids;
	public $startTime;
	public $endTime;
	public $send_id;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'push_information';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, create_time', 'numerical', 'integerOnly' => true),
			array('community_ids', 'required', 'on' => 'customerCreate'),
			array('branch_ids', 'required', 'on' => 'employeeCreate'),
			array('send_id', 'required', 'on' => 'send'),
			array('title, content', 'required', 'on' => 'all'),//推送到全部小区或部门
			array('title', 'length', 'max' => 200),
			array('soucre', 'length', 'max' => 50),
			array('content, send_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,groups,community_ids,branch_ids,branch_id, type, content, title, create_time, startTime, endTime', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => '类型',
			'content' => '内容',
			'title' => '标题',
			'community_ids' => '推送小区',
			'branch_id' => '管辖地区',
			'branch_ids' => '部门',
			'startTime' => '开始时间',
			'endTime' => '结束时间',
			'groups' => 'groups',
			'create_time' => '推送时间',
			'send_id' => '推送的用户',
			'shopCategory' => '商家分类',
			'groups' => '区域',
			'soucre' => '推送标识',
		);
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

		$criteria->compare('id', $this->id);
		$criteria->compare('type', $this->type);
		$criteria->compare('content', $this->content, true);
		$criteria->compare('title', $this->title, true);
		if ($this->startTime != "") {
			$criteria->addCondition('create_time>=' . strtotime($this->startTime));
		}
		if ($this->endTime != "") {
			$criteria->addCondition('create_time<=' . strtotime($this->endTime . " 23:59:59"));
		}
		$criteria->compare('create_time', $this->create_time);
		$criteria->order = 'create_time desc';

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PushInformation the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => null,
				'setUpdateOnCreate' => true,
			),
		);
	}

	public static function getListClients($c_id, $id)
	{
		$model = self::model()->findByPk($id);
		$clients = PushClient::model()->findAll('push_information_id=' . $model->id . '
         and type =' . $model->type . '');
		$dataClients = array();
		if ($model->type == 0) {
			$dataClients = CHtml::listData($clients, 'community_id', 'community_id');
		} else if ($model->type == 1) {
			$dataClients = CHtml::listData($clients, 'branch_id', 'branch_id');
		} else if ($model->type == 2) {
			$dataClients = CHtml::listData($clients, 'object_id', 'object_id');
		}
		return (!in_array($c_id, $dataClients));
	}

	/**
	 * @param string $title 标题
	 * @param string $note 内容
	 * @param array $mobiles 手机号码（数组）
	 * @param integer $type
	 * @return boolean
	 */
	public static function createSNSInformation($title = '', $note = "", $mobiles = array(), $type = -1)
	{
		$model = new self;
		$model->type = $type;
		$model->title = empty($title) ? "投诉报修" : $title;
		$model->content = $note;
		$mobiles = is_array($mobiles) ? $mobiles : array($mobiles);
		if ($model->save()) {
			if (is_array($mobiles)) {
				foreach ($mobiles as $mobile) {
					$pushClient = new PushClient();
					$pushClient->object_id = Yii::app()->user->id;
					$pushClient->push_information_id = $model->id;
					$pushClient->type = $type;
					$pushClient->mobile = $mobile;
					$pushClient->save();
				}
				Yii::import('common.components.JgPush');
				$tag_name = $mobiles;
				if ($title == "投诉报修") {
					JgPush::getInstance()->pushMessageByAlias($tag_name, $model->title, $model->content);
					//JgPush::getInstance(1)->pushMessageByAlias($tag_name,$model->title,$model->content);
				} else {
					JgPush::getInstance()->pushMessageByAlias($tag_name, $model->title, $model->content);
				}
			}
		}
		return true;
	}

	/**
	 * @param string $title 标题
	 * @param string $note 内容
	 * @param array $mobiles 手机号码（数组）
	 * @param integer $type
	 * @return boolean
	 */
	public static function createSNSInformations($title = '', $note = "", $mobiles = array(), $object_id, $type = -1)
	{
		$model = new self;
		$model->type = $type;
		$model->title = empty($title) ? "投诉报修" : $title;
		$model->content = $note;
		$mobiles = is_array($mobiles) ? $mobiles : array($mobiles);
		if ($model->save()) {
			if (is_array($mobiles)) {
				foreach ($mobiles as $mobile) {
					$pushClient = new PushClient();
					$pushClient->object_id = $object_id;
					$pushClient->push_information_id = $model->id;
					$pushClient->type = $type;
					$pushClient->mobile = $mobile;
					$pushClient->save();
				}
				Yii::import('common.components.JgPush');
				$tag_name = $mobiles;
				if ($type == PushInformation::IS_TYPE_CUSTOMER) {
					JgPush::getInstance()->pushMessageByAlias($tag_name, $model->title, $model->content);
				} else if ($type == PushInformation::IS_TYPE_EMPLOYEE) {
					//JgPush::getInstance(1)->pushMessageByAlias($tag_name,$model->content,$model->content);
					return true;
				}
			}
		}
		return true;
	}

	/**
	 * @version 以手机号码推送给用户
	 * @copyright (c) 2015-04-17, Josen
	 * @return bool
	 */
	public function createSendPush()
	{
		if (empty($this->send_id)) {
			return false;
		}
		if (!is_array($this->send_id)) {
			$userArr[] = $this->send_id;
		} else {
			$userArr = $this->send_id;
		}
		foreach ($userArr as $key => $v) {
			if (PushInformation::IS_TYPE_CUSTOMER == $this->type) {
				$model = Customer::model()->findByPk($v);
			} elseif (PushInformation::IS_TYPE_EMPLOYEE == $this->type) {
				$model = Employee::model()->findByPk($v);
			}
			$mobile[] = $model->mobile;
		}
		$this->groups = implode(',', $mobile);

		if ($this->save()) {
			Yii::import('common.components.JgPush');
			$cdb = new CDbCriteria();
			$cdb->compare('state', Item::STATE_ON)->compare('is_deleted', Item::DELETE_ON)->compare('id', $this->send_id);
			if (PushInformation::IS_TYPE_EMPLOYEE == $this->type) {//物业内容
				$models = Employee::model()->findAll($cdb);
			} else {//业主内容
				$models = Customer::model()->findAll($cdb);
			}
			if (!empty($models)) {
				/**
				 * @var Employee|Customer $model
				 */
				foreach ($models as $model) {
					$pushClient = new PushClient();
					$pushClient->object_id = $model->id;
					//$pushClient->note = $model->note;
					$pushClient->push_information_id = $this->id;
					$pushClient->type = $this->type;
					$pushClient->mobile = $model->mobile;
					if ($pushClient->save()) {
						$tag_name[] = $model->mobile;
					} else {
						return false;
					}
				}
			}
			if (PushInformation::IS_TYPE_CUSTOMER == $this->type) {
				$jgpush = JgPush::getInstance();
				$result = $jgpush->pushMessageByAlias($tag_name, $this->title, $this->content);
				return $result;
			} else {
				return true;
			}

		}
		return false;
	}

	/**
	 * @version 推送给全部小区
	 * @return bool
	 */
	public function createAll()
	{
		$data = array();
		if (PushInformation::IS_TYPE_CUSTOMER == $this->type) {//业主
//            $community = Community::model()->enabled()->findAll();
//            $data = array_map(function(Community $model){
//                    return $model->id;
//                }, $community);

//            ICE 原逻辑获取所有的小区id,线接入ice所有小区id
			$community = ICECommunity::model()->ICECommunitySearchAllData();
			$data = array_map(function ($community) {
				return $community['czy_id'];
			}, $community);
		} elseif (PushInformation::IS_TYPE_EMPLOYEE == $this->type) {//物业
			$branch = Branch::model()->enabled()->findAll();
			$data = array_map(function (Branch $model) {
				return $model->id;
			}, $branch);
		}
		$this->groups = implode(',', $data);
		if ($this->save()) {
			$result = false;
			if (PushInformation::IS_TYPE_CUSTOMER == $this->type) {//业主
				$sixmonth = strtotime("-3 month");
				$sql = 'INSERT INTO push_client (`push_information_id`, `object_id`, `type`, `mobile`, `create_time`) SELECT ' . $this->id . ', `id`, ' . $this->type . ', `mobile`, ' . time() . ' FROM `customer` WHERE state = ' . Item::STATE_ON . ' AND is_deleted = ' . Item::DELETE_ON . ' AND last_time >' . $sixmonth . ' AND last_time-create_time>86400';
			} elseif (PushInformation::IS_TYPE_EMPLOYEE == $this->type) {//物业
				$sql = 'INSERT INTO push_client (`push_information_id`, `object_id`, `type`, `mobile`, `create_time`) SELECT ' . $this->id . ', t.`employee_id`, ' . $this->type . ', e.`mobile`, ' . time() . ' FROM `employee_branch_relation` t LEFT JOIN `employee` e ON e.id = t.employee_id WHERE e.state = ' . Item::STATE_ON . ' AND e.is_deleted = ' . Item::DELETE_ON . ' AND  t.branch_id IN (' . $this->groups . ')';
			}
			if (isset($sql)) {
				$result = true;
				Yii::app()->db->createCommand($sql)->execute();
			}
			if ($result) {
				Yii::import('common.components.JgPush');
				if (PushInformation::IS_TYPE_EMPLOYEE == $this->type) {
//                    $jgpush=  JgPush::getInstance(1);
					return true;
				}
				if (PushInformation::IS_TYPE_CUSTOMER == $this->type) {
					$jgpush = JgPush::getInstance();
				}
				if (isset($jgpush)) {
					$result = $jgpush->pushMessageByAll($this->title, $this->content);
					return $result;

				}
			}
		} else {
			return false;
		}

	}

	//业主
	public function createPush()
	{
		// $return = false
		$this->groups = implode(',', $this->community_ids);
		if ($this->save()) {
			Yii::import('common.components.JgPush');
			if ($this->type == PushInformation::IS_TYPE_CUSTOMER) {
				$sql = 'INSERT INTO push_client (`push_information_id`, `object_id`, `type`, `mobile`, `create_time`) SELECT ' . $this->id . ', `id`, ' . $this->type . ', `mobile`, ' . time() . ' FROM `customer` WHERE state = ' . Item::STATE_ON . ' AND is_deleted = ' . Item::DELETE_ON . ' AND community_id IN (' . $this->groups . ')';
				Yii::app()->db->createCommand($sql)->execute();
				$tag_name = $this->community_ids;
				$jgpush = JgPush::getInstance();
				$result = $jgpush->pushMessageByTag($tag_name, $this->title, $this->content);
				return $result;
			}
		} else {
			return false;
		}

	}

	//物业
	public function createEmployeeInformation()
	{
		$this->groups = implode(',', $this->branch_ids);
		if ($this->save()) {
			Yii::import('common.components.JgPush');
			if ($this->type == PushInformation::IS_TYPE_EMPLOYEE) {
				$sql = 'INSERT INTO push_client (`push_information_id`, `object_id`, `type`, `mobile`, `create_time`) SELECT ' . $this->id . ', t.`employee_id`, ' . $this->type . ', e.`mobile`, ' . time() . ' FROM `employee_branch_relation` t LEFT JOIN `employee` e ON e.id = t.employee_id WHERE e.state = ' . Item::STATE_ON . ' AND e.is_deleted = ' . Item::DELETE_ON . ' AND  t.branch_id IN (' . $this->groups . ')';
				Yii::app()->db->createCommand($sql)->execute();
//                $tag_name=$this->branch_ids;
//                $jgpush=  JgPush::getInstance(1);
//                $result=$jgpush->pushMessageByTag($tag_name,$this->title,$this->content);
//                return $result;
				return true;
			}
		} else {
			return false;
		}
	}

	//商家不需要推送
	public function createShopInformation()
	{
		// $return = false
		if ($this->save()) {
			//Yii::import('common.components.BaiduPush.BaiduPush');
			if ($this->type == PushInformation::IS_TYPE_SHOP) {
				$sql = 'INSERT INTO push_client (`push_information_id`, `object_id`, `type`, `mobile`, `create_time`) SELECT ' . $this->id . ', `id`, ' . $this->type . ', `mobile`, ' . time() . ' FROM `shop` WHERE id IN (' . $this->groups . ')';
				Yii::app()->db->createCommand($sql)->execute();
			}
		}
		return true;
	}

	public function getBranchTreeData()
	{
		$list = $data = $branch_Ids = array();
		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		$data = $employee->getAllBranchIds();

		foreach ($data as $treeData) {
			$branch = Branch::model()->findByPk($treeData);
			$data = array('id' => "$branch->id", 'pId' => "$branch->parent_id", 'name' => "$branch->name", 'open' => true);
			if (in_array($branch->id, $branch_Ids))
				$data['checked'] = 'true';

			$list[] = $data;
		}

		return $list;
	}

	public function getCommunityTreeData()
	{
		$this->branch = Branch::model()->findByPk(1);
		if (empty($this->branch)) {
			return array(array('id' => "r_0", 'pId' => "-1", 'name' => "[地区]全国", 'open' => true));
		}
		// var_dump($this->branch->getRegionCommunityRelation(0, 'Shop'));exit;
		return $this->branch->getRegionCommunityRelation($this->branch->id);
	}

	public function getTypeName()
	{
		return $this->pushType[$this->type];
	}

	public function getGroupsName()
	{
		error_reporting(E_ALL & ~E_NOTICE);
		$return = '';
		$ids = explode(',', $this->groups);
		if (empty($this->groups))
			return "";

		if ($this->type == PushInformation::IS_TYPE_CUSTOMER) {


			foreach ($ids as $key => $communityId) {
				$return .= Community::model()->findByPk($communityId)->name;
				$return .= ',';
			}
		} else if ($this->type == PushInformation::IS_TYPE_EMPLOYEE) {
			foreach ($ids as $key => $branchId) {
				$return .= Branch::model()->findByPk($branchId)->name;
				$return .= ',';
			}
		}

		return $return;
	}

	//获取商家行业分类
	public function getShopCategory()
	{
		$model = ShopCategory::model()->findAll("state=:state AND is_deleted=:is_deleted", array(":state" => Item::STATE_ON, ":is_deleted" => Item::DELETE_ON));
		$shopCategory = array();
		if (!empty($model)) {
			foreach ($model as $val) {
				$id = $val->id;
				$shopCategory[$id] = $val->name;
			}
		}
		return $shopCategory;
	}

	//根据商家分类获取商家ID
	public function getShopIds(array $categoryIds)
	{
		$criteria = new CDbCriteria();
		$criteria->addInCondition("category_id", $categoryIds);
		$criteria->compare("is_deleted", Item::DELETE_ON);
		$criteria->compare("state", Item::STATE_ON);
		$model = Shop::model()->findAll($criteria);
		$ids = array();
		if (!empty($model)) {
			foreach ($model as $val) {
				$ids[] = $val->id;
			}
		}
		return $ids;
	}

	//根据小区ID获取商家ID
	/**
	 * @param array $communityIds
	 * @return array
	 */
	public function getShopIdsByCommunity(array $communityIds)
	{
		$criteria = new CDbCriteria();
		$criteria->select = "`t`.id";
		$criteria->join = "LEFT JOIN shop_community_relation s ON `t`.id=`s`.shop_id";
		$criteria->compare("`t`.is_deleted", Item::DELETE_ON);
		$criteria->compare("`t`.state", Item::STATE_ON);
		$criteria->addInCondition("`s`.community_id", $communityIds);
		$criteria->distinct = true;
		$model = Shop::model()->findAll($criteria);
		$ids = array();
		if (!empty($model)) {
			foreach ($model as $val) {
				$ids[] = $val->id;
			}
		}
		return $ids;
	}

	/*
	 * @version 年年卡推送消息
	 *
	 */
	public function createNian($mobile, $title, $content)
	{
		$model2 = new PushInformation();
		$model2->type = 0;
		$model2->content = $content;
		$model2->title = $title;
		$model2->create_time = time();
		$model2->groups = $mobile;
		$model2->soucre = '来自第三方接口消息';
//        $sql="insert into push_information(type,content,title,create_time,groups,soucre) values(".$this->type.",'".$this->content."','".$this->title."',".$this->create_time.",'".$this->groups."','".$this->soucre."')";
//        $execute=Yii::app()->db->createCommand($sql)->execute();
		if ($model2->save()) {
			Yii::import('common.components.JgPush');
			$cdb = new CDbCriteria();
			$cdb->compare('state', Item::STATE_ON)->compare('is_deleted', Item::DELETE_ON)->compare('mobile', $mobile);
			$models = Customer::model()->findAll($cdb);
			if (!empty($models)) {
				foreach ($models as $model) {
					$pushClient = new PushClient();
					$pushClient->object_id = $model->id;
					$pushClient->push_information_id = $model2->id;
					$pushClient->type = $model2->type;
					$pushClient->mobile = $model->mobile;
					if ($pushClient->save()) {
						$tag_name[] = $model->mobile;
					}
				}
			}
			$jgpush = JgPush::getInstance();
			$jgpush->pushMessageByAlias($tag_name, $model2->title, $model2->content);

		}
	}

	/*
	 * @version 京东特供已发货推送消息
	 */
	public function jdSendProduct($mobile, $title, $content)
	{
		$model2 = new PushInformation();
		$model2->type = 0;
		$model2->content = $content;
		$model2->title = $title;
		$model2->create_time = time();
		$model2->groups = $mobile;
		$model2->soucre = '来自第三方接口消息';
		$execute = $model2->save();
		Yii::import('common.components.JgPush');
		$cdb = new CDbCriteria();
		$cdb->compare('state', Item::STATE_ON)->compare('is_deleted', Item::DELETE_ON)->compare('mobile', $mobile);
		$model = Customer::model()->find($cdb);
		if (!empty($model)) {
			$pushClient = new PushClient();
			$pushClient->object_id = $model->id;
			$pushClient->push_information_id = $model2->id;
			$pushClient->type = $model2->type;
			$pushClient->mobile = $model->mobile;
			$execute2 = $pushClient->save();
			if ($execute && $execute2) {
				$tag_name[] = $model->mobile;
				$jgpush = JgPush::getInstance();
				$jgpush->pushMessageByAlias2($tag_name, $model2->title, $model2->content);
			}
		}
	}

	/*
	 * @version 前一天有签到第二天九点前没有签到的发送推送消息
	 * @param string $mobile
	 * @param string $title
	 * @param string $content
	 */
	public function renQiTuiSong($mobile, $title, $content)
	{
		$model2 = new PushInformation();
		$model2->type = 0;
		$model2->content = $content;
		$model2->title = $title;
		$model2->create_time = time();
		$model2->groups = $mobile;
		$model2->soucre = '来自第三方接口消息';
		$execute = $model2->save();
		Yii::import('common.components.JgPush');
		$cdb = new CDbCriteria();
		$cdb->compare('state', Item::STATE_ON)->compare('is_deleted', Item::DELETE_ON)->compare('mobile', $mobile);
		$model = Customer::model()->find($cdb);
		if (!empty($model)) {
			$pushClient = new PushClient();
			$pushClient->object_id = $model->id;
			$pushClient->push_information_id = $model2->id;
			$pushClient->type = $model2->type;
			$pushClient->mobile = $model->mobile;
			$execute2 = $pushClient->save();
			if ($execute && $execute2) {
				$tag_name[] = $model->mobile;
				$jgpush = JgPush::getInstance();
				$res = $jgpush->pushMessageByAlias($tag_name, $model2->title, $model2->content);
				return $res;
			}
		}
	}


}
