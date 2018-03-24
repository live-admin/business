<?php

/**
 * This is the model class for table "region".
 *
 * The followings are the available columns in table 'region':
 * @property string $name
 * @property integer $parent_id
 * @property integer $state
 * @property integer $is_deleted
 */
class Region extends CActiveRecord
{
	/**
	 * @var 标记是否搜索全部数据
	 */
	public $search_all;
	/**
	 * @var string 模型名
	 */
	public $modelName = '地区';

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Region the static model class
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
		return 'region';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name,initial', 'required', 'on' => 'create, update'), // 增加和修改时名称必填
			array('state', 'required', 'on' => 'create'), // 增加时启禁用必填
			array('parentName', 'checkEnable', 'on' => 'create'), // 增加时上级地区要存在并启用，或者为0
			array('parentName', 'checkParentNotSelf', 'on' => 'move'), // 转移时上级地区不能为自己
			array('parent_id', 'checkEnable', 'on' => 'move'), // 转移时上级地区要存在并启用，或者为0
			array('state', 'checkEnable', 'on' => 'enable'), // 启用时上级地区要存在并启用，或者为0
			array('state', 'checkDisable', 'on' => 'disable'), // 禁用时下级地区和关联数据要禁用
			array('is_deleted', 'checkDelete', 'on' => 'delete'), // 删除时下级地区和关联数据要删除
			array('parent_id, state, is_deleted', 'numerical', 'integerOnly' => true),
			array('name', 'length', 'max' => 50),
			// 可搜索字段
			array('id, name, parent_id, state, search_all,initial', 'safe', 'on' => 'search'),
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
			'parent' => array(self::BELONGS_TO, 'Region', 'parent_id'),
			'children' => array(self::HAS_MANY, 'Region', 'parent_id'),
			'communities' => array(self::HAS_MANY, 'Community', 'region_id'),
			'communitiesCount' => array(self::STAT, 'Community', 'region_id', 'condition' => 't.is_deleted=0'),
			'enabledCommunitiesCount' => array(self::STAT, 'Community', 'region_id', 'condition' => 't.is_deleted=0 AND t.state=0'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '地区名称',
			'parent_id' => '上级地区',
			'parentName' => '上级地区',
			'initial' => "首字母",
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
		$criteria->compare('state', $this->state);
		$criteria->compare('initial', $this->initial, true);

		// 只搜索指定的 id 下的地区
		if (empty($this->search_all)) {
			$criteria->compare('parent_id', $this->parent_id);
		}

		Yii::import('common.components.ActiveDataProvider');
		return new ActiveDataProvider($this->orderByGBK(), array(
			'criteria' => $criteria,
		));
	}

	/**
	 * 按地区名 GBK（拼音）排序
	 * @return $this
	 */
	public function orderByGBK()
	{
		$criteria = $this->getDbCriteria();
		$criteria->order = 'convert(`name` using gbk) asc';
		return $this;
	}

	/**
	 * 启用、增加、移动时检查上级是否被禁用
	 * @param $attribute
	 * @param $params
	 */
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

	/**
	 * 禁用时检查是否有启用的下级和小区
	 * @param $attribute
	 * @param $params
	 */
	public function checkDisable($attribute, $params)
	{
		if (!$this->hasErrors() && !$this->getCanDisable()) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在下级' . $this->modelName . '，无法禁用。');
		}
		if (!$this->hasErrors() && !empty($this->enabledCommunitiesCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在小区，无法禁用。');
		}
	}

	/**
	 * 删除时检查是否有下级和小区
	 * @param $attribute
	 * @param $params
	 */
	public function checkDelete($attribute, $params)
	{
		if (!$this->hasErrors() && !$this->getCanDelete()) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在下级' . $this->modelName . '，无法删除。');
		}
		if (!$this->hasErrors() && !empty($this->communitiesCount)) {
			$this->addError($attribute, '因为该' . $this->modelName . '下还存在小区，无法删除。');
		}
	}

	/**
	 * 移动时检查是否是自己和自己的下级
	 * @param $attribute
	 * @param $params
	 */
	public function checkParentNotSelf($attribute, $params)
	{
		if (!$this->hasErrors() && !$this->getCanMoveToParentId($this->parent_id)) {
			$this->addError($attribute, '不能将该' . $this->modelName . '的上级' . $this->modelName . '设置为自己或自己的下级，无法转移。');
		}
	}

	/**
	 * 移动时检查上级是否存在
	 * @param $attribute
	 * @param $params
	 */
	public function checkParentExist($attribute, $params)
	{
		if (!$this->hasErrors() && !empty($this->parent_id) && $this->parent === null) {
			$this->addError($attribute, '指定的上级' . $this->modelName . '不存在。');
		}
	}

	public function getParentName()
	{
		if ($this->parent === null) {
			return '-';
		}
		return $this->parent->name;
	}

	/**
	 * 得到上级部门(所有上级部门的名字的一个字符串)
	 * 参数：@myRegionId:部门id @isOneSelf:是否包括自身,defautl false;
	 * return String
	 * */
	public static function getMyParentRegionNames($myRegionId = 0, $isOneSelf = false)
	{
		if (empty($myRegionId)) {
			return "-";
		} else {
			$region = Region::model()->Enabled()->findByPk($myRegionId);
			//如果部门不存在或上级部门不存在
			if (empty($region)) {
				return "-";
			} else {
				$regionList = $region->getParents();
				$regionNameStr = "";
				foreach ($regionList as $pRegion) {
					$regionNameStr .= (empty($pRegion) ? "" : $pRegion->name) . " - ";
				}
				if ($isOneSelf) {
					$regionNameStr .= $region->name;
				}
			}
			return trim(trim($regionNameStr), '-');
		}
	}

	/*
	 * 得到所有的地区信息
	 */
	static public function getRegionList($doCheck = true)
	{
		$regionList = Region::model()->enabled()->orderByGBK()->findAll();
		$list = array();
		array_push($list, array('id' => "r_0", 'pId' => "-1", 'name' => "[地区]全国", 'open' => true, 'chkDisabled' => $doCheck));
		if (!empty($regionList)) {
			foreach ($regionList as $region) {
				array_push($list, array('id' => "r_{$region->id}", 'pId' => "r_{$region->parent_id}", 'name' => "[地区]" . $region->name, 'chkDisabled' => $doCheck));
			}
		}
		return $list;
	}

	//获得用户的部门及下级部门的id
	public function getShopBranchIds()
	{
		if (empty($this->branch)) {
			throw new CHttpException('400', "用户未关联组织机构,请联系管理员！");
		}

		$branch = Branch::model()->Enabled()->findByPk($branch_id);
		if (empty($branch)) {
			return array();
		} else {
			return $branch->getBranchIds();
		}
	}

	/**
	 * 根据地区得到自己的小区
	 * @param int $region_id 地区的ID
	 * @param string $type type不为id则返回值里带小区名
	 * @return array
	 */
	public function getRegionCommunity($region_id = 0, $type = 'name')
	{
//		return ICERegion::model()->getRegionCommunity($region_id, $type);
//		ICE 查询自己的小区，根据region_id和自己的组织架构查小区
		$employee = ICEEmployee::model()->findByPk(Yii::app()->user->id);
		$communities = $employee->ICEGetOrgCommunity(false,$region_id);

		$list = array();

		if ($type == 'name') {
			foreach ($communities as $community) {
				if (!isset($community['czy_id']) || !$community['czy_id']) {
					continue;
				}

				$list[$community['czy_id']] = $community['name'];
			}
		} else {
			foreach ($communities as $community) {
				if (!isset($community['czy_id']) || !$community['czy_id']) {
					continue;
				}

				$list[] = $community['czy_id'];
			}
		}

		return $list;

		$criteria = new CDbCriteria;
		$branchList = $regionIds = array();

		$employee = Employee::model()->enabled()->findByPk(Yii::app()->user->id);
		$branchList = $employee->getBranchIds();

		if ($region_id > 0) {
			//根据地区得到所有的下级地区集合
			$regionIds = Region::model()->enabled()->orderByGBK()->findByPk($region_id)->getChildrenIdsAndSelf();
			$criteria->addInCondition('region_id', $regionIds);
		}

		$criteria->addInCondition('branch_id', $branchList);
		$communityLists = Community::model()->enabled()->findAll($criteria);
		$list = array();
		if (count($communityLists) > 0) {
			if ($type == 'name') {
				foreach ($communityLists as $community) {
					$list[$community->id] = $community->name;
				}
			} else {
				foreach ($communityLists as $community) {
					$list[] = $community->id;
				}
			}
		}
		return $list;
	}

	/**
	 * 根据地区得到所有的小区
	 * @param int $region_id 地区的ID
	 * @param string $type type不为id则返回值里带小区名
	 * @return array
	 */
	public function getAllRegionCommunity($region_id = 0, $type = 'name')
	{
//		ICE 不考虑组织架构，直接查询小区。
		return ICERegion::model()->getRegionCommunity($region_id, $type);
		$criteria = new CDbCriteria;
		$regionIds = array();

		if ($region_id > 0) {
			//根据地区得到所有的下级地区集合
			$regionIds = Region::model()->enabled()->orderByGBK()->findByPk($region_id)->getChildrenIdsAndSelf();
			$criteria->addInCondition('region_id', $regionIds);
		}

		// var_dump($regionIds);
		$criteria_1 = new CDbCriteria;
		$criteria_1->addInCondition('id', $regionIds);
		$ffff = Region::model()->enabled()->findAll($criteria_1);
		$listRegion = array();
		foreach ($ffff as $k => $val) {
			$listRegion[] = $val["id"];
			$listRegion[] = $val["name"];
		}
		// var_dump($listRegion);die;
		$criteria->addCondition('type=' . Community::COMMUNITY_TYPE);
		$communityLists = Community::model()->enabled()->findAll($criteria);
		$list = array();
		if (count($communityLists) > 0) {
			if ($type == 'id-name') {
				foreach ($communityLists as $key => $community) {
					$list[$key]['Cid'] = $community->id;
					$list[$key]['Cname'] = $community->name;
					$list[$key]['Rid3'] = $listRegion[0];
					$list[$key]['Rname3'] = $listRegion[1];
					$list[$key]['Rid2'] = $listRegion[2];
					$list[$key]['Rname2'] = $listRegion[3];
					$list[$key]['Rid1'] = $listRegion[4];
					$list[$key]['Rname1'] = $listRegion[5];
				}
			} else if ($type == 'name') {
				foreach ($communityLists as $community) {
					$list[$community->id] = $community->name;
				}
			} else {
				foreach ($communityLists as $community) {
					$list[] = $community->id;
				}
			}
		}
		return $list;
	}

	/**
	 * 根据地区得到所有的小区
	 * @param int $region_id 地区的ID
	 * @param string $type type不为id则返回值里带小区名
	 * @return array
	 */
	public function getAllRegionCompanyCommunity($region_id = 0, $type = 'name')
	{
		return ICERegion::model()->getAllRegionCompanyCommunity($region_id, $type);
		$criteria = new CDbCriteria;
		$regionIds = array();

		if ($region_id > 0) {
			//根据地区得到所有的下级地区集合
			$regionIds = Region::model()->enabled()->orderByGBK()->findByPk($region_id)->getChildrenIdsAndSelf();
			$criteria->addInCondition('region_id', $regionIds);
		}

		// var_dump($regionIds);
		$criteria_1 = new CDbCriteria;
		$criteria_1->addInCondition('id', $regionIds);
		$ffff = Region::model()->enabled()->findAll($criteria_1);
		$listRegion = array();
		foreach ($ffff as $k => $val) {
			$listRegion[] = $val["id"];
			$listRegion[] = $val["name"];
		}
		// var_dump($listRegion);die;

		$criteria->addCondition('type=' . Community::COMPANY_TYPE);
		$communityLists = Community::model()->enabled()->findAll($criteria);
		$list = array();
		if (count($communityLists) > 0) {
			if ($type == 'id-name') {
				foreach ($communityLists as $key => $community) {
					$list[$key]['Cid'] = $community->id;
					$list[$key]['Cname'] = $community->name;
					$list[$key]['Rid3'] = $listRegion[0];
					$list[$key]['Rname3'] = $listRegion[1];
					$list[$key]['Rid2'] = $listRegion[2];
					$list[$key]['Rname2'] = $listRegion[3];
					$list[$key]['Rid1'] = $listRegion[4];
					$list[$key]['Rname1'] = $listRegion[5];
				}
			} else if ($type == 'name') {
				foreach ($communityLists as $community) {
					$list[$community->id] = $community->name;
				}
			} else {
				foreach ($communityLists as $community) {
					$list[] = $community->id;
				}
			}
		}
		return $list;
	}

	/**
	 * 根据商家地区得到自己的小区
	 * @param int $region_id 地区的ID
	 * @param string $type type不为id则返回值里带小区名
	 * @return array
	 */
	public function getShopRegionCommunity($region_id = 0, $type = 'name')
	{
		$criteria = new CDbCriteria;
		$branchList = $regionIds = array();

		$shop = Shop::model()->enabled()->findByPk(Yii::app()->user->id);
		$branchList = empty($shop->branch) ? array() : $shop->branch->getBranchIds();

		if ($region_id > 0) {
			//根据地区得到所有的下级地区集合
			$regionIds = Region::model()->enabled()->orderByGBK()->findByPk($region_id)->getChildrenIdsAndSelf();
			$criteria->addInCondition('region_id', $regionIds);
		}

		$criteria->addinCondition('branch_id', $branchList);
		$communityLists = Community::model()->enabled()->findAll($criteria);
		$list = array();
		if (count($communityLists) > 0) {
			if ($type == 'name') {
				foreach ($communityLists as $community) {
					$list[$community->id] = $community->name;
				}
			} else {
				foreach ($communityLists as $community) {
					$list[] = $community->id;
				}
			}
		}
		return $list;
	}

	public static function getMyRegion($region_id = null)
	{
		$data = array();
		if (!empty($region_id)) {
			$region = Region::model()->findByPk($region_id);
			// var_dump($region->name);exit;
			if (!empty($region)) {
				$data[] = empty($region) ? '' : trim($region->name);
				if ($region->parent_id > 0) {
					$data = array_merge(self::getMyRegion(($region->parent_id)), $data);
				}
			}
		}
		return $data;
	}

	//获取城市的id集合线
	public function getCityIds()
	{
		$ids = array();
		$divisionIds = $this->getParentIds();

		if (!empty($divisionIds))
			$ids = array_splice($divisionIds, -2);

		return $ids;
	}


	/**
	 * 获取所有的省份，城市，区
	 * @param int $parent_id 父类id
	 * @return array
	 *  TODO:kakatool
	 */
	public static function getRegionByParent($parent_id = 0, $level = 1)
	{

		$criteria = new CDbCriteria;
		$criteria->select = 'id,name,parent_id';
		$criteria->compare('parent_id', $parent_id);
		$criteria->compare('state', 0);
		$criteria->compare('is_deleted', 0);
		$criteria->order = 'convert(`name` using gbk) asc';

		$model = Region::model()->findAll($criteria);

		if ($model) {

			$items = array();
			foreach ($model as $m) {

				if ($level == 1) {
					$items[] = array('id' => $m->id, 'name' => $m->name, 'parent_id' => $m->parent_id, 'citys' => Region::getRegionByParent($m->id, 2));
				} else if ($level == 2) {

					$items[] = array('id' => $m->id, 'name' => $m->name, 'parent_id' => $m->parent_id, 'districts' => Region::getRegionByParent($m->id, 3));

				} else {
					$items[] = array('id' => $m->id, 'name' => $m->name, 'parent_id' => $m->parent_id);
				}

			}

			return $items;
		}

		return array();

//		return CJSON::decode(CJSON::encode($model));


	}


	/**
	 * 获取所有的城市
	 * @param int $parent_id
	 * @return array
	 * TODO:kakatool
	 */
	public static function getAllCitys()
	{

		$criteria = new CDbCriteria;
		$criteria->select = 'id,name,parent_id';
		$criteria->compare('parent_id', 0);
		$criteria->compare('state', 0);
		$criteria->compare('is_deleted', 0);
		$criteria->order = 'convert(`name` using gbk) asc';

		$model = Region::model()->orderByGBK()->findAll($criteria);

		if ($model) {
			$items = array();
			foreach ($model as $m) {
				$citys = $m->children;
				if ($citys && is_array($citys)) {
					foreach ($citys as $city) {
						$key = CUtf8_PY::encode($city->name, 'head');
						if ($key && strlen($key) > 0) {
							$key = substr($key, 0, 1);
						}
						$items[] = array('key' => $key, 'id' => $city->id, 'name' => $city->name, 'parent_id' => $city->parent_id,);
					}
				}
			}
			usort($items, function ($a, $b) {
				$key1 = $a['key'];
				$key2 = $b['key'];
				if ($key1 == $key2) {
					return 0;
				}
				return ($key1 > $key2) ? 1 : -1;

			});

			return $items;
		}
		return array();
	}


	/**
	 * 根据region_id获取旗下小区
	 * @param int $region_id
	 *  TODO:kakatool
	 */
	public static function getCommunityByRegion($region_id = 0)
	{

		$criteria = new CDbCriteria();
		$criteria->select = 'id,region_id,name,alpha,lat,lng';
		$criteria->compare('region_id', $region_id);
		$criteria->compare('state', 0);
		$criteria->compare('is_deleted', 0);
		$criteria->compare('type', 1); //1普通小区，2园区公司
		$criteria->order = ' alpha  asc';

		$model = Community::model()->enabled()->findAll($criteria);

		if (count($model)) {

			$community = array();
			foreach ($model as $m) {
				$community[] = array('id' => $m->id, 'region_id' => $m->region_id, 'name' => $m->name, 'alpha' => $m->alpha, 'lat' => $m->lat, 'lng' => $m->lng);

			}

			return $community;

		}

		return array();

	}

}   