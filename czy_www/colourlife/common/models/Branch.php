<?php

/**
 * This is the model class for table "branch".
 *
 * The followings are the available columns in table 'branch':
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property integer $parent_id
 * @property integer $branch_id
 * @property integer $state
 * @property integer $is_deleted
 */
class Branch extends CActiveRecord
{
	/**
	 * @var 标记是否搜索全部数据
	 */
	public $search_all;
	/**
	 * @var string 模型名
	 */
	public $modelName = '部门';

	CONST TYPE_RANGE = 0;
	CONST TYPE_FUNCTION = 1;
	static $TYPE_NAMES = array(
		self::TYPE_RANGE => '管辖部门',
		self::TYPE_FUNCTION => '职能部门',
	);

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Branch the static model class
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
		return 'branch';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required', 'on' => 'create, update'),
			array('name', 'length', 'max' => 200, 'encoding' => 'UTF-8', 'on' => 'create'),
			array('state, type', 'boolean', 'on' => 'create'),
			array('parentName', 'checkEnable', 'on' => 'create'),
			array('parentName', 'checkParentExist', 'on' => 'create, move'),
			array('parentName', 'checkParentNotSelf', 'on' => 'move'),
			array('type', 'checkType', 'on' => 'create'),
			array('parent_id', 'checkType', 'on' => 'move'),
			array('parent_id', 'checkEnable', 'on' => 'move'),
			array('state', 'checkEnable', 'on' => 'enable'),
			array('state', 'checkDisable', 'on' => 'disable'),
			array('is_deleted', 'checkDelete', 'on' => 'delete'),
			array('name, type, state, search_all,parent_id', 'safe', 'on' => 'search'),
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
			'employees' => array(self::HAS_MANY, 'EmployeeBranchRelation', 'branch_id'),
			'communities' => array(self::HAS_MANY, 'Community', 'branch_id'),
			'shops' => array(self::HAS_MANY, 'Shop', 'branch_id'),
			'employeesCount' => array(self::STAT, 'EmployeeBranchRelation', 'branch_id'),
			'communitiesCount' => array(self::STAT, 'Community', 'branch_id', 'condition' => 't.is_deleted=0'),
			'shopsCount' => array(self::STAT, 'Shop', 'branch_id', 'condition' => 't.is_deleted=0'),
			'enabledEmployeesCount' => array(self::STAT, 'EmployeeBranchRelation', 'branch_id'),
			'enabledCommunitiesCount' => array(self::STAT, 'Community', 'branch_id', 'condition' => 't.is_deleted=0 AND t.state=0'),
			'enabledShopsCount' => array(self::STAT, 'Shop', 'branch_id', 'condition' => 't.is_deleted=0 AND t.state=0'),
			'notifiesCount' => array(self::STAT, 'Notify', 'branch_id', 'condition' => 't.is_deleted=0'),
			'eventsCount' => array(self::STAT, 'Event', 'branch_id', 'condition' => 't.is_deleted=0'),
			'parent' => array(self::BELONGS_TO, 'Branch', 'parent_id'),
			'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
			'children' => array(self::HAS_MANY, 'Branch', 'parent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '部门名称',
			'parent_id' => '上级部门',
			'parentName' => '上级部门',
			'branch_id' => '管辖部门',
			'branchName' => '管辖部门',
			'type' => '部门类型',
			'state' => '状态',
		);
	}

	/**
	 * @return array
	 */
	public function behaviors()
	{
		return array(
			'IsDeletedBehavior' => array(
				'class' => 'common.components.behaviors.IsDeletedBehavior',
			),
			'StateBehavior' => array(
				'class' => 'common.components.behaviors.StateBehavior',
			),
			'ParentBehavior' => array(
				'class' => 'common.components.behaviors.ParentBehavior',
			),
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

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('type', $this->type);
		$criteria->compare('state', $this->state);

		//搜索指定的部门的下级部门

		/*if(!empty($this->parent_id)){
			$ids = $this->getChildrenIdsAndSelf();
			$criteria->addInCondition('parent_id', $ids);
		}
			$criteria->addInCondition('id',$ids);
		}*/

		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		//选择的组织架构ID
		if (!empty($this->parent_id)) {
			if (empty($this->search_all)) {
				$criteria->compare('parent_id', $this->parent_id);
			} else {

				$criteria->addInCondition('id', Branch::model()->findByPk($this->parent_id)->getChildrenIds());
			}
		} else { //自己的组织架构的ID
			$criteria->addInCondition('parent_id', $employee->getMyBranchId());
			//$criteria->addInCondition('id', Branch::model()->findByPk($employee->getMyBranchId())->getChildrenIds());
		}
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function getTypeName($html = false)
	{
		return @self::$TYPE_NAMES[$this->type];
	}

	public function getTypeNames($select = false)
	{
		if ($select) {
			return array_merge(array('' => '全部'), self::$TYPE_NAMES);
		}
		return self::$TYPE_NAMES;
	}

	public function checkType($attribute, $params)
	{
		if (!$this->hasErrors() && $this->parent !== null && $this->type == self::TYPE_RANGE && $this->parent->type == self::TYPE_FUNCTION) {
			$this->addError($attribute, "上级{$this->modelName} \"{$this->parentName}\" 不能是{$this->parent->typeName}。");
		}
	}

	public function checkEnable($attribute, $params)
	{
		if (!$this->hasErrors() && !$this->getCanEnable()) {
			if ($this->isNewRecord) {
				$this->addError($attribute, '上级' . $this->modelName . '被禁用，无法在其下增加新' . $this->modelName);
			} else if ($attribute == 'state') {
				$this->addError($attribute, '因为该' . $this->modelName . '的上级' . $this->modelName . '被禁用，无法启用。');
			} else {
				$this->addError($attribute, '当前' . $this->modelName . '的上级' . $this->modelName . '被禁用，无法移动。');
			}
		}
	}

	public function checkDisable($attribute, $params)
	{
		if (!$this->hasErrors() && !$this->getCanDisable()) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在下级' . $this->modelName . '，无法禁用。');
		}
		// 需要判断是否有物业用户
		if (!$this->hasErrors() && !empty($this->enabledCommunitiesCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在小区，无法禁用。');
		}
		if (!$this->hasErrors() && !empty($this->enabledEmployeesCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在物业用户，无法禁用。');
		}
		if (!$this->hasErrors() && !empty($this->enabledShopsCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在商家，无法禁用。');
		}
		if (!$this->hasErrors() && !empty($this->notifiesCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在通知，无法禁用。');
		}
		if (!$this->hasErrors() && !empty($this->eventsCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在活动，无法禁用。');
		}
	}

	public function checkDelete($attribute, $params)
	{
		if (!$this->hasErrors() && !$this->getCanDelete()) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在下级' . $this->modelName . '，无法删除。');
		}
		// 需要判断是否有物业用户
		if (!$this->hasErrors() && !empty($this->communitiesCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在小区，无法删除。');
		}
		if (!$this->hasErrors() && !empty($this->employeesCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在物业用户，无法删除。');
		}
		if (!$this->hasErrors() && !empty($this->shopsCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在商家，无法删除。');
		}
		if (!$this->hasErrors() && !empty($this->notifiesCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在通知，无法删除。');
		}
		if (!$this->hasErrors() && !empty($this->eventsCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在活动，无法删除。');
		}
	}

	public function checkParentExist($attribute, $params)
	{
		if (!$this->hasErrors() && !empty($this->parent_id) && $this->parent === null) {
			$this->addError($attribute, '指定的上级' . $this->modelName . '不存在。');
		}
	}

	public function checkParentNotSelf($attribute, $params)
	{
		if (!$this->hasErrors() && !$this->getCanMoveToParentId($this->parent_id)) {
			$this->addError($attribute, '不能将该' . $this->modelName . '的上级' . $this->modelName . '设置为自己或自己的下级，无法转移。');
		}
	}

	public function getParentName()
	{
		if ($this->parent === null) {
			return '-';
		}
		return $this->parent->name;
	}

	public function getBranchName()
	{

		if ($this->branch === null) {
			return '-';
		}
		return $this->branch->name;
	}

	public function getCanSetType()
	{
		if ($this->parent === null) {
			return true;
		}
		return $this->parent->type == self::TYPE_RANGE;
	}

	protected function initBranchFromParent()
	{
		if ($this->type == self::TYPE_RANGE)
			$this->branch_id = $this->parent_id;
		else {
			if ($this->parent !== null) {
				if ($this->parent->type == self::TYPE_FUNCTION)
					$this->branch_id = $this->parent->branch_id;
				else
					$this->branch_id = $this->parent_id;
			} else
				$this->branch_id = 0;
		}
	}

	protected function beforeSave()
	{
		if ($this->isNewRecord) {
			if (!isset($this->type))
				$this->type = self::TYPE_FUNCTION;
			$this->initBranchFromParent();
		} else if ($this->getIsParentChanged()) {
			$this->initBranchFromParent();
		}
		return parent::beforeSave();
	}

	protected function flushBranchIdsOfParentsCache()
	{
		foreach ($this->getParentIds() as $id) {
			$this->deleteParentCachedValue('BranchIds', $id);
			$this->deleteParentCachedValue('RegionCommunityBranch', $id);
		}
	}

	protected function flushBranchIdsOfOldParentsCache()
	{
		foreach ($this->getOldParentIds() as $id) {
			$this->deleteParentCachedValue('BranchIds', $id);
			$this->deleteParentCachedValue('RegionCommunityBranch', $id);
		}
	}

	protected function flushSelfBranchIdsCache()
	{
		$this->deleteParentCachedValue('BranchIds', $this->id);
		$this->deleteParentCachedValue('RegionCommunityBranch', $this->id);
	}

	/**
	 * 删除指定的缓存
	 */
	public function afterSave()
	{
		$owner = $this->owner;
		if ($owner->isNewRecord) {
			$this->flushBranchIdsOfParentsCache();
		} else if ($this->getIsParentChanged()) {
			$this->flushBranchIdsOfOldParentsCache();
			$this->flushSelfBranchIdsCache();
			$this->flushBranchIdsOfParentsCache();
		} else if ($this->getIsEnableChanged()) {
			$this->flushSelfBranchIdsCache();
			$this->flushBranchIdsOfParentsCache();
		}
		return parent::afterSave();
	}

	public function beforeDelete()
	{
		$this->flushSelfBranchIdsCache();
		$this->flushBranchIdsOfParentsCache();
		return parent::beforeDelete();
	}

	/**
	 * 获取管辖部门 ID 数组
	 */
	public function getBranchIds()
	{
		$ids = $this->getParentCachedValue('BranchIds', $this->id);
		// var_dump($ids);die;
		if ($ids === false) {
			$ids = array();
			if ($this->type == self::TYPE_FUNCTION) {
				if ($this->branch !== null)
					$ids = $this->branch->getBranchIds();
			} else {
				$ids[] = $this->id;
				foreach ($this->findFilterChildrenByPk($this->id, 0, array('type' => self::TYPE_RANGE)) as $model) {
					$ids = array_unique(array_merge($ids, $model->getBranchIds()));
				}
			}
			$this->setParentCachedValue('BranchIds', $this->id, $ids);
		}
		return $ids;
	}

	/**
	 * 获取职能部门 ID 数组
	 */
	public function getFunctionBranchIds()
	{
		$ids = array();
		$ids[] = $this->id;
		foreach ($this->findFilterChildrenByPk($this->id, 0) as $model) {
			if ($model->type == self::TYPE_FUNCTION) {
				$ids = array_unique(array_merge($ids, $model->getAllBranchIds()));
			}
		}

		return $ids;
	}

	/**
	 * 获取所有部门 ID 数组，包括职能部门，还有自己
	 */
	public function getAllBranchIds()
	{
		//$ids = $this->getParentCachedValue('AllBranchIds', $this->id);
		//if ($ids === false) {
		$ids = array();
		$ids[] = $this->id;
		foreach ($this->findFilterChildrenByPk($this->id, 0) as $model) {
			$ids = array_unique(array_merge($ids, $model->getAllBranchIds()));
		}
		// $this->setParentCachedValue('AllBranchIds', $this->id, $ids);
		// }
		return $ids;
	}

	/**
	 * 获取所有部门 ID 数组，包括职能部门，还有自己 显示层级关系
	 */
	public function getLevelBranchIds()
	{
		$ids = $this->getParentCachedValue('LevelBranchIds', $this->id);
		if ($ids === false) {
			$ids = array();
			$ids = $this->getLevelParentIds();
			$this->setParentCachedValue('LevelBranchIds', $this->id, $ids);
		}
		//sort($ids);
		$ids[] = $this->id;
		return $ids;
	}

	//自动处理时得事业部以下的职能部门
	public function getAutoFunctionBranchIds()
	{
		$ids = array();
		$divisionIds = $this->getLevelBranchIds();
		$divisionId = !empty($divisionIds[2]) ? $divisionIds[2] : 0; //事业部取第三级数据
		if ($divisionId != 0) {
			//去掉原有的逻辑，直接只取事业部
			/* foreach ($this->findFilterChildrenByPk($divisionId, 0) as $model) {
				 if ($model->type == self::TYPE_FUNCTION) {
					 $ids = array_unique(array_merge($ids, $model->getAllBranchIds()));
				 }
			 }*/
			$ids[] = $divisionId;
		}

		return $ids;
	}

	//自动处理时得事业部以下的监督的部门，小区对应的部门上面三级不包括本身的部门ID
	public function getSuperviseBranchIds()
	{
		$ids = array();
		$divisionIds = $this->getParentIds();

		if (!empty($divisionIds))
			$ids = array_splice($divisionIds, -3);

		return $ids;
	}


	/**
	 * 获取管辖部门 ID 数组
	 */
	public function getBranchParentIds()
	{
		$ids = $this->getParentCachedValue('ParentBranchIds', $this->id);
		if ($ids === false) {
			$ids = array();
			if ($this->type == self::TYPE_FUNCTION) {
				if ($this->branch !== null)
					$ids = $this->branch->getBranchParentIds();
			} else {
				$ids[] = $this->id;
				$ids = array_unique(array_merge($ids, $this->getParentIds()));
			}
			$this->setParentCachedValue('ParentBranchIds', $this->id, $ids);
		}
		return $ids;
	}

	/**
	 * 根据组织架构ID取得下面所有的信息
	 * $model为要返回模型数据
	 */
	public function getBranchAllData($model)
	{
		$criteria = new CDbCriteria;
		$criteria->addInCondition('branch_id', $this->getBranchIds());
		$data = $model::model()->findAll($criteria);
		return $data;
	}

	public function getBranchAllIds($model)
	{
		$cmd = $model::$db->createCommand();
		$cmd->from($model::model()->tableName())->select('id');
		$cmd->where(array('in', 'branch_id', $this->getBranchIds()));
		$ids = array();
		foreach ($cmd->queryAll() as $data) {
			$ids[] = $data['id'];
		}
		return $ids;
	}

	/**
	 * 根据组织架构ID取得上面所有的信息
	 * $model为要返回模型数据
	 */
	public function getBranchAllParentData($model)
	{
		$criteria = new CDbCriteria;
		$criteria->addInCondition('branch_id', $this->getBranchParentIds());
		$data = $model::model()->findAll($criteria);
		return $data;
	}

	//由下往上查
	public function getAllBranch($area_id, $branch = null)
	{
		$connection = Yii::app()->db;
		if ($area_id > 0) {
			$sql = "SELECT * FROM `branch` WHERE id=" . $area_id . " ORDER BY id DESC";
			$command = $connection->createCommand($sql);
			$result = $command->queryAll();
			// var_dump($result);
			if (!empty($result[0])) {
				/* @var $result type */
				$branch[] = $result[0]["name"];
				//echo $result[0]["name"]."<br/>";
				$branch = $this->getAllBranch($result[0]["parent_id"], $branch);
			}
		}
		if (is_array($branch))
			krsort($branch);
		return ($branch);
	}


	public function getRegionCommunityRelation($object_id = 0, $model = '', $regionIsDisabled = false, $is_ICE = true)
	{
		if ($is_ICE) {
			return ICEBranch::model()->ICEGetRegionCommunityZTree($object_id, $model, $regionIsDisabled);
		}
		$list = array(array('id' => 'r0', 'pId' => '-1', 'name' => '[地区]全国', 'open' => true, 'chkDisabled' => $regionIsDisabled));
		if (empty($this->id))
			return $list;
		$regionIds = $communities = array();
		// 取出所有可用的小区
		foreach ($this->getBranchAllData('Community') as $community) {
			$communities[] = array(
				'id' => $community->id,
				'pId' => $community->region_id,
				'name' => '[小区]' . $community->name,
			);
			$regionIds[] = $community->region_id;
		}
		$regionIds = array_unique($regionIds);
		$criteria = new CDbCriteria;
		$criteria->addInCondition('id', $regionIds);
		foreach (Region::model()->enabled()->findAll($criteria) as $region) {
			$regionIds = array_unique(array_merge($regionIds, $region->getParentIds()));
		}
		$criteria = new CDbCriteria;
		$criteria->addInCondition('id', $regionIds);
		$regionIds = array();
		foreach (Region::model()->enabled()->orderByGBK()->findAll($criteria) as $region) {
			$data = array('id' => 'r' . $region->id, 'pId' => 'r' . $region->parent_id, 'name' => '[地区]' . $region->name);
			if ($regionIsDisabled)
				$data['chkDisabled'] = $regionIsDisabled;
			$list[] = $data;
			$regionIds[] = $region->id; // 重新统计一遍可用的 ID
		}
		$checkIds = self::getRelationCommunityIds($model, $object_id);
		foreach ($communities as $community) {
			if (!in_array($community['pId'], $regionIds)) {
				continue;
			}
			$data = array('id' => $community['id'], 'pId' => 'r' . $community['pId'], 'name' => $community['name']);
			if (in_array($community['id'], $checkIds))
				$data['checked'] = 'true';
			$list[] = $data;
		}
		return $list;
	}

	/** 商家后台取小区方法
	 * @param int $object_id
	 * @param string $model
	 * @param bool $regionIsDisabled
	 * @return array
	 */
	public function getShopRegionCommunityRelation($object_id = 0, $model = '', $regionIsDisabled = false, $is_ICE = true)
	{
		error_reporting(E_ALL ^ E_NOTICE);
		if ($is_ICE) {
			return ICEBranch::model()->ICEGetShopRegionCommunityZTree($object_id, $model, $regionIsDisabled);
		}
		$list = array(array('id' => 'r0', 'pId' => '-1', 'name' => '[地区]全国', 'open' => true, 'chkDisabled' => $regionIsDisabled));
		if (empty($this->id))
			return $list;
		$regionIds = $communities = array();
		// 取出所有可用的小区
		$communityIds = ShopCommunityRelation::model()->findAll('shop_id=:shop_id', array(':shop_id' => Yii::app()->user->id));
		if (count($communityIds) > 0) {
			foreach ($communityIds as $community) {
				$community = $community->community;
				$communities[] = array(
					'id' => $community->id,
					'pId' => $community->region_id,
					'name' => '[小区]' . $community->name,
				);
				$regionIds[] = $community->region_id;
			}
		}
		$regionIds = array_unique($regionIds);
		$criteria = new CDbCriteria;
		$criteria->addInCondition('id', $regionIds);
		foreach (Region::model()->enabled()->findAll($criteria) as $region) {
			$regionIds = array_unique(array_merge($regionIds, $region->getParentIds()));
		}
		$criteria = new CDbCriteria;
		$criteria->addInCondition('id', $regionIds);
		$regionIds = array();
		foreach (Region::model()->enabled()->orderByGBK()->findAll($criteria) as $region) {
			$data = array('id' => 'r' . $region->id, 'pId' => 'r' . $region->parent_id, 'name' => '[地区]' . $region->name);
			if ($regionIsDisabled)
				$data['chkDisabled'] = $regionIsDisabled;
			$list[] = $data;
			$regionIds[] = $region->id; // 重新统计一遍可用的 ID
		}
		$checkIds = self::getRelationCommunityIds($model, $object_id);
		foreach ($communities as $community) {
			if (!in_array($community['pId'], $regionIds)) {
				continue;
			}
			$data = array('id' => $community['id'], 'pId' => 'r' . $community['pId'], 'name' => $community['name']);
			if (in_array($community['id'], $checkIds))
				$data['checked'] = 'true';
			$list[] = $data;
		}
		return $list;
	}

	/** 商家后台取地区方法
	 * @param int $object_id
	 * @param string $model
	 * @param bool $regionIsDisabled
	 * @return array
	 */
	public function getShopRegion($object_id = 0, $model = '', $regionIsDisabled = false)
	{
		$list = array(array('id' => 'r0', 'pId' => '-1', 'name' => '全国', 'open' => true, 'chkDisabled' => $regionIsDisabled));

		$regionIds = $communities = array();
		// 取出所有可用的小区
		$communityIds = ShopCommunityRelation::model()->findAll('shop_id=:shop_id', array(':shop_id' => Yii::app()->user->id));
		if (count($communityIds) > 0) {
			foreach ($communityIds as $community) {
				$community = $community->community;
				$region = Region::model()->enabled()->orderByGBK()->findByPk($community->region_id);
				$regions = $region->getCityIds();
				(!empty($regions[0])) ? $regionIds[] = $regions[0] : '';//只取省
				(!empty($regions[1])) ? $regionIds[] = $regions[1] : '';//只取城市
			}
		}
		$regionIds = array_unique($regionIds);
		//dump($regionIds);exit;
		$criteria = new CDbCriteria;
		$criteria->addInCondition('id', $regionIds);
		$regionIds = array();

		$checkIds = self::getRelationRegionIds($model, $object_id);
		foreach (Region::model()->enabled()->orderByGBK()->findAll($criteria) as $region) {
			if ($region->parent_id != 0) {
				Region::model()->enabled()->orderByGBK()->findByPk($region->id);
			}

			if ($region->parent_id == 0)//省
				$data = array('id' => 'r' . $region->id, 'pId' => 'r' . $region->parent_id, 'name' => $region->name);
			else {//市

				$data = array('id' => $region->id, 'pId' => 'r' . $region->parent_id, 'name' => $region->name);
				//}else{
				//continue;
			}

			if (in_array($region['id'], $checkIds))
				$data['checked'] = 'true';

			if ($regionIsDisabled)
				$data['chkDisabled'] = $regionIsDisabled;
			$list[] = $data;
			$regionIds[] = $region->id; // 重新统计一遍可用的 ID
		}
		return $list;
	}

	/*
   * 根据地区加ID得到是否被数据选中
   *  @return bool
   */
	static public function getRelationRegionIds($model, $object_id)
	{
		if (empty($model) || empty($object_id))
			return array();

		$criteria = new CDbCriteria;
		$criteria->distinct = true;
		$criteria->select = 'region_id';
		$criteria->compare('goods_id', $object_id);
		$result = array();
		foreach ($model::model()->findAll($criteria) as $relation) {
			$result[] = $relation->region_id;
		}
		return $result;
	}

	/*
	 * 根据小区加ID得到是否被数据选中
	 *  @return bool
	 */
	static public function getRelationCommunityIds($model, $object_id)
	{
		if (empty($model) || empty($object_id))
			return array();

		if ($model == 'ShopCommunityGoodsOwnership') {
			$field = 'goods_id';
		} else if ($model == 'PersonalRepairsCateCommunityRelation') {
			$field = 'repairs_cate_id';
		} else if ($model == 'SmallLoans') {
			$field = 'small_loans_id';
			$model = 'SmallLoansCommunityRelation';
		} else if ($model == 'SetableSmallLoans' || $model == 'SetableAd' || $model == 'SetableCls' || $model == 'SetableActivityPic') {
			$field = 'small_loans_id';
			$model = $model . 'CommunityRelation';
		} else if ($model == 'TopicGroup') {
			$field = 'group_id';
			$model = 'TopicGroupCommunityRelation';
		} else if ($model == 'HomeConfigAd') {
			$field = 'ad_id';
			$model = 'HomeConfigAdCommunityRelation';
		} else if ($model == 'HomeConfigActivityCategory') {
			$field = 'activity_category_id';
			$model = 'HomeConfigActivityCommunityRelation';
		} else if ($model == 'HomeConfigMoreFunc') {
			$field = 'function_id';
			$model = 'HomeConfigMoreFuncCommunityRelation';
		} else if ($model == 'FeedActivityType') {
			$field = 'activity_type_id';
			$model = 'FeedActivityTypeCommunityRelation';
		}else if ($model == 'HomeConfigTopAd') {
			$field = 'top_ad_id';
			$model = 'HomeConfigTopAdCommunityRelation';
		} else {
			$field = strtolower($model) . '_id';
			$model = ucfirst(strtolower($model)) . 'CommunityRelation';
		}
		$criteria = new CDbCriteria;
		$criteria->distinct = true;
		$criteria->select = 'community_id';
		$criteria->compare($field, $object_id);
		$result = array();
		foreach ($model::model()->findAll($criteria) as $relation) {
			$result[] = $relation->community_id;
		}
		return $result;
	}

	/**
	 * 得到上级部门(所有上级部门的名字的一个字符串)
	 * 参数：@myBranchId:部门id @isOneSelf:是否包括自身,defautl false;
	 * return String
	 * */
	public static function getMyParentBranchName($myBranchId = 0, $isOneSelf = false)
	{
		if (empty($myBranchId)) {
			return "-";
		} else {
			$branch = Branch::model()->Enabled()->findByPk($myBranchId);
			//如果部门不存在或上级部门不存在
			if (empty($branch)) {
				return "-";
			} else {
				$branchList = $branch->getParents();
				$branchNameStr = "";
				foreach ($branchList as $pBranch) {
					$branchNameStr .= (empty($pBranch) ? "" : $pBranch->name) . " - ";
				}
				if ($isOneSelf) {
					$branchNameStr .= $branch->name;
				}
			}
			return trim(trim($branchNameStr), '-');
		}
	}

	public function getRangeLinkageSelectData($startId, $hasSelf = true)
	{
		return $this->getLinkageSelectData($startId, $hasSelf, array('type' => self::TYPE_RANGE));
	}

	public function getRangeLinkageSelectData1($startId, $hasSelf)
	{
		return $this->getLinkageSelectData($startId, $hasSelf, array('type' => self::TYPE_FUNCTION));
	}

	public function getIsEmployee($employee_id)
	{
		return EmployeeBranchRelation::model()->find('employee_id=:employee_id and branch_id=:branch_id', array(':employee_id' => $employee_id, ':branch_id' => $this->id));
	}

}
