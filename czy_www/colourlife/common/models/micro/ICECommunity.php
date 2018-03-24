<?php

/**
 * This is the model class for table "community".
 *
 * The followings are the available columns in table 'community':
 * @property integer $id
 * @property integer $region_id
 * @property integer $branch_id
 * @property string $name
 * @property string $domain
 * @property integer $state
 * @property integer $is_deleted
 * @property string $alpha
 * @property string $lat
 * @property string $lng
 */
class ICECommunity extends CActiveRecord
{
	/**
	 * @var string 模型名
	 */
	public $modelName = '小区';
	private $_branch_id;
	private $_branch_state;
	public $regions;
	const COMMUNITY_TYPE = 1;
	const COMPANY_TYPE = 2;


	public $czy_id;
	public $uuid;
	public $ice_branch_id;
	public $province_id;
	public $city_id;
	public $district_id;
	//public $regionString;
	//public $branchString;

	public $parentId;
	public $province;
	public $provincecode;
	public $city;
	public $citycode;
	public $region;
	public $regioncode;


	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Community the static model class
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
		return 'community';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		return array(
			array('region_id, branch_id, name,type, domain, alpha', 'required', 'on' => 'create, update'),
			array('domain', 'unique', 'caseSensitive' => false, 'criteria' => array('condition' => 'is_deleted=0'), 'on' => 'create, update'),
			array('state', 'required', 'on' => 'create'),
			array('region_id', 'checkRegion', 'on' => 'create, update, enable'),
			array('branch_id', 'checkBranch', 'on' => 'create, update, enable'),
			array('state', 'checkDisable', 'on' => 'disable'),
			array('is_deleted', 'checkDelete', 'on' => 'delete'),
			array('name, domain', 'length', 'encoding' => 'UTF-8', 'max' => 200, 'on' => 'create, update'),
			array('alpha', 'length', 'encoding' => 'UTF-8', 'max' => 1, 'on' => 'create, update'),
			array('domain, alpha', 'filter', 'filter' => 'strtoupper', 'on' => 'create, update'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('region_id, branch_id, name, domain, type,state', 'safe', 'on' => 'search'),
			array('lng,lat', 'safe', 'on' => 'update,create'),
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
			//'iCEBranch' => array(self::BELONGS_TO, 'ICEBranch', 'branch_id'),
			//'iCERegion' => array(self::BELONGS_TO, 'ICERegion', 'region_id'),
			'buildsCount' => array(self::STAT, 'Build', 'community_id', 'condition' => 't.is_deleted=0'),
			'enabledBuildsCount' => array(self::STAT, 'Build', 'community_id', 'condition' => 't.is_deleted=0 AND t.state=0'),
			'employeesCount' => array(self::STAT, 'CommunityEmployeeRelation', 'community_id'),
			'customers' => array(self::HAS_MANY, 'Customer', 'community_id'),
			'complains' => array(self::HAS_MANY, 'Complain', 'community_id'),
			'repairs' => array(self::HAS_MANY, 'Repair', 'community_id'),
			'colorcloudCommunity' => array(self::HAS_MANY, 'ColorcloudCommunity', 'community_id'),
			//'shopgoodscommunityrelation' => array(self::HAS_MANY, 'ShopGoodsCommunityRelation', 'community_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'region_id' => '地区',
			'region' => '地区',
			'branch_id' => '管辖部门',
			'ice_branch_id' => '管辖部门',
			'branch' => '管辖部门',
			'name' => '小区名称',
			'domain' => '域名',
			'state' => '状态',
			'alpha' => '字母索引',
			'lat' => '纬度',
			'lng' => '经度',
			'type' => '类型',
			'czy_id' => '小区ID',
			'regionString' => '地区',
			'branchString' => '管辖部门',
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

		return $this->ICESearch();

		$criteria = new CDbCriteria;

		if ($this->region !== null)
			$criteria->addInCondition('region_id', $this->region->childrenIdsAndSelf);
		$employee = Employee::model()->findByPk(Yii::app()->user->id);
		//选择的组织架构ID
		if (!empty($this->branch))
			$criteria->addInCondition('branch_id', $this->branch->getChildrenIdsAndSelf());
		else //自己的组织架构的ID
			$criteria->addInCondition('branch_id', $employee->getBranchIds());
		$criteria->compare('name', $this->name, true);
		$criteria->compare('domain', $this->domain, true);
		$criteria->compare('state', $this->state);
		$criteria->compare('alpha', $this->alpha, true);
		$criteria->compare('type', $this->type);
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
			'IsDeletedBehavior' => array(
				'class' => 'common.components.behaviors.IsDeletedBehavior',
			),
			'StateBehavior' => array(
				'class' => 'common.components.behaviors.StateBehavior',
			),
		);
	}

	public function checkRegion($attribute, $params)
	{
		if (!$this->hasErrors() && (empty($this->region) || $this->region->isDisabled)) {
			$this->addError($attribute, '指定的地区不存在或被禁用，无法创建、修改或启用' . $this->modelName);
		}
	}

	public function checkBranch($attribute, $params)
	{
		if (!$this->hasErrors() && (empty($this->branch) || $this->branch->isDisabled)) {
			$this->addError($attribute, '指定的管辖部门不存在或被禁用，无法创建、修改或启用' . $this->modelName);
		}
	}

	/**
	 * 禁用时检查楼栋
	 * @param $attribute
	 * @param $params
	 */
	public function checkDisable($attribute, $params)
	{
		if (!$this->hasErrors() && !empty($this->enabledBuildsCount)) {
			$this->addError($attribute, '该' . $this->modelName . '下存在楼栋，无法被禁用。');
		}
	}

	/**
	 * 删除时检查楼栋和物管
	 * @param $attribute
	 * @param $params
	 */
	public function checkDelete($attribute, $params)
	{
		if (!$this->hasErrors() && !empty($this->buildsCount)) {
			$this->addError($attribute, '该' . $this->modelName . '下存在楼栋，无法被删除。');
		}
		if (!$this->hasErrors() && !empty($this->employeesCount)) {
			$this->addError($attribute, '该' . $this->modelName . '下存在物管，无法被删除。');
		}
	}

	public function afterFind()
	{
		$this->_branch_id = $this->branch_id;
		$this->_branch_state = $this->state;
		return parent::afterFind();
	}

	public function beforeDelete()
	{
		$this->_clearCustomerCommunity(); // 清除业主的小区的信息
		ShopCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
		ShopRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
		//商家-小区-商品的归属表记录
		ShopCommunityGoodsOwnership::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
		//商家-小区-商品销售表记录
		ShopCommunityGoodsSell::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));


		//电子优惠卷的关系
		DiscountCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
		//黄页关系
		FacilityCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
		//精品推荐关系
		AppCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));

		//专题活动关系
		EventCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));

		//通知关系
		NotifyCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));

		//个人报修分类关系
		PersonalRepairsCateCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));

		return parent::beforeDelete();
	}

	public function afterSave()
	{
		//禁用时删除小区关系
		if ($this->state == Item::COMMUNITY_STATE_YES && $this->_branch_state != $this->state) {
			//商家与小区的关系
			ShopCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
			//删除商家和商家的关系
			ShopRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
			//商家-小区-商品的归属表记录
			ShopCommunityGoodsOwnership::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
			//商家-小区-商品销售表记录
			ShopCommunityGoodsSell::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
			//电子优惠卷的关系
			DiscountCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));

			//黄页关系
			FacilityCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
			//精品推荐关系
			AppCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
			//专题活动关系
			EventCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
			//通知关系
			NotifyCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
			//个人报修分类关系
			PersonalRepairsCateCommunityRelation::model()->deleteAll('community_id=:community_id', array(':community_id' => $this->id));
		}
		return parent::afterSave();
	}


	private function _clearCustomerCommunity()
	{
		Customer::model()->updateAll(array(
			'community_id' => 0,
			'build_id' => 0,
			'room' => '',
		), 'community_id=:community_id', array(
			':community_id' => $this->id,
		));
	}

	public function getRegionName()
	{
		return (empty($this->region)) ? '' : $this->region->name;
	}

	public function getBranchName()
	{
		return (empty($this->branch)) ? '' : $this->branch->name;
	}

	/**
	 * 返回可以管辖当前小区的部门 ID 数组
	 * @return array
	 */
	public function getBranchIds()
	{
		$ids = array($this->branch_id);
		if (!empty($this->branch))
			$ids = array_merge($this->branch->parentIds, $ids);
		return $ids;
	}

	public function getBranchIdsByPk($id)
	{
		$model = $this->enabled()->findByPk($id);
		if (empty($model))
			return false;
		return $model->getBranchIds();
	}

	public function getIdListByBranchId($branch_id)
	{

	}

	/*public function getColorCloudList()
	{
		Yii::import('common.api.ColorCloudApi');
		return ColorCloudApi::getInstance()->getCommunity();
	}*/

	public function getColorCloudBuildings()
	{
		Yii::import('common.api.IceApi');
		$arr = array();

		foreach ($this->colorcloudCommunity as $colorcloud) {
			$result = IceApi::getInstance()->getBuildingsWithCommunity($colorcloud->colorcloud_name, $colorcloud->color_community_id);

			$data = isset($result['data']) ? $result['data'] : $result;

			if ($data) {
				foreach ($data as $k => $val) {
					$data[$k]['id'] = urlencode($data[$k]['id']);
					$data[$k]["colorcloud"] = isset($val['h_uuid']) ? $val['h_uuid'] : $colorcloud->color_community_id;
				}
				$arr = array_merge($arr, $data);
			}
		}
		return $arr;
	}

	public function getUrl($path = '')
	{
		return 'http://' . strtolower($this->domain) . COMMUNITY_DOMAIN . $path;
	}

	public function getRegionTag()
	{
		return CHtml::tag(
			'a',
			array(
				'rel' => 'tooltip',
				'href' => 'javascript:void();',
				'data-original-title' => '所在地区:' .
					//Region::getMyParentRegionNames($this->region_id)
					$this->ICEGetCommunityRegionsNames()
			),
			$this->region
		);
	}

	public function getBranchTag()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' => '所属部门:' .
			Branch::getMyParentBranchName($this->branch_id)), $this->branchName);
	}

	public function getRegionString($sep = ' - ')
	{
		return implode(
			$sep,
			array(
				$this->province,
				$this->city,
				$this->region,
			)
		);
	}

	//获取小区所在地区
	/**
	 * @param int $region_id
	 * @return array
	 */
//	ICE 返回数组 省份 城市 地区
	public function getMyParentRegionNames($region_id = null)
	{

//		$region_id = empty($region_id) ? $this->region_id : $region_id;
//		$data = Region::getMyRegion($region_id);
//		//  krsort($data); //
//		return $data;

		$regions = $this->ICEGetCommunityRegions();
		$names = array();

		foreach ($regions as $region) {
			if (!isset($region) || !$region['name']) {
				continue;
			}

			$names[] = $region['name'];
		}
		return $names;
	}

	//ICE接入 获取小区所在部门
	public function getCommunityBelongBranch($community_id = null)
	{
		if (empty($community_id) || $community_id == 0) {
			return "";
		} else {
//			$model = Community::model()->findByPk($community_id);
//			$branch_id = $model->branch_id;
//			$branchModel = Branch::model()->findByPk($branch_id);
//			$parent_id = $branchModel->parent_id;
//			$branchName = "/" . $branchModel->name;
//			if ($parent_id != 0) {
//				$parentModel = Branch::model()->findByPk($parent_id);
//				if (!empty($parentModel)) {
//					$parentName = $parentModel->name;
//					$parent_id2 = $parentModel->parent_id;
//					$branchName = "/" . $parentName . $branchName;
//					if ($parent_id2 != 0) {
//						$parentModel2 = Branch::model()->findByPk($parent_id2);
//						$parentName2 = $parentModel2->name;
//						$branchName = "/" . $parentName2 . $branchName;
//					}
//				}
//			}

//			ICE 把小区的组织架构替换成ICE的
			$model = ICECommunity::model()->findByPk($community_id);
			$branchName = str_replace(' - ', '/', $model->getBranchString());
			return $branchName;
		}
	}

	//获取小区地址
	public function getCommunityAddress($isShort = false)
	{
		return $this->ICEGetCommunityAddress();
		$address = "";

		$info = Region::getMyRegion($this->region_id);
		foreach ($info as $_v) {
			$address .= $_v;
		}

		if (false === $isShort)
			$address .= $this->name;

		return $address;
	}


	//获取小区地址
	public function getIceInfo($isShort = false)
	{
		$address = "";
		$info = ColorcloudCommunity::model()->findByPk($this->id);
		// foreach($info as $_v){
		//     $address .= $_v;
		// }

		if (false === $isShort)
			$address .= $this->name;

		return $address;
	}


	//批量设置经纬度
	public function setLatAndLng()
	{
		$count = self::model()->count();
		$pageMax = ceil($count / 10);          //每页10条
		for ($i = 1; $i <= $pageMax; $i++) {
			$index = 10 * ($i - 1);
			$l = $index . ",10";
			$totalModel = self::model()->findAll(array('limit' => $l));
			foreach ($totalModel as $_model) {
				$url = "http://api.map.baidu.com/geocoder/v2/?address=" . urlencode($_model->CommunityAddress) . "&ak=zZtp9zi2isS4PRb43rOTaG8f&output=json";
				$res = file_get_contents($url);
				$data = json_decode($res);
				if ($data && $data->status == 0) {
					$_model['lat'] = $data->result->location->lat;
					$_model['lng'] = $data->result->location->lng;
					if ($_model->save()) {
						echo iconv("utf-8", "gbk", $_model->CommunityAddress . ":设置经纬度成功！lat=" . $_model['lat'] . ";lng=" . $_model['lng'] . "\r\n");
					}
				}
			}
			sleep(2000);
		}
		die("获取经纬度完毕");
	}

	public static function getCommunityRegion($community_id)
	{
		$data = array();
		$getRegion = function ($region_id) use (&$getRegion) {
			$data = array();
			if ($region = Region::model()->findByPk($region_id)) {
				$data[] = $region->id;
				if (0 < $region->parent_id) {
					$data = array_merge($getRegion($region->parent_id), $data);
				}
			}
			return $data;
		};
		if ($community = static::model()->findByPk($community_id)) {
			$data = $getRegion($community->region_id);
		}
		return $data;
	}

	//获取小区类型
	public function getCommunityType()
	{
		if ($this->type == 1) {
			return "小区";
		} else if ($this->type == 2) {
			return "园区";
		}
	}

	//经纬度距离算法
	static public function getAround($lat, $lon, $raidus)
	{
		$PI = 3.14159265;

		$latitude = $lat;
		$longitude = $lon;

		$degree = (24901 * 1609) / 360.0;
		$raidusMile = $raidus;

		$dpmLat = 1 / $degree;
		$radiusLat = $dpmLat * $raidusMile;
		$minLat = $latitude - $radiusLat;
		$maxLat = $latitude + $radiusLat;

		$mpdLng = $degree * cos($latitude * ($PI / 180));
		$dpmLng = 1 / $mpdLng;
		$radiusLng = $dpmLng * $raidusMile;
		$minLng = $longitude - $radiusLng;
		$maxLng = $longitude + $radiusLng;
		return array($minLat, $maxLat, $minLng, $maxLng);
	}

	//创建园区时，自动默认创建公司
	//$community_id=园区ID
	public static function addCompany($community_id)
	{
		if (empty($community_id)) {
			return false;
		}
		$data = Company::model()->find("community_id=:community_id AND type=:type", array(":community_id" => $community_id, ":type" => 0));
		if (!empty($data)) {
			return true;
		}
		$build = new Build();
		$build->community_id = $community_id;
		$build->name = "A栋";
		if ($build->save()) {
			$companyRoom = new CompanyRoom();
			$companyRoom->build_id = $build->id;
			$companyRoom->room = "0000";
			if ($companyRoom->save()) {
				$company = new Company();
				$company->name = "体验企业";
				$company->name = "体验企业";
				$company->username = "TestComany" . time() . rand(0, 10000);
				$company->password = "test123456";
				$company->community_id = $community_id;
				$company->last_time = time();
				$company->last_ip = "0.0.0.0";
				$company->build_id = $build->id;
				$company->room_id = $companyRoom->id;
				$company->type = 0;
				if ($company->save()) {
					return true;
				}
			}
		}

		return false;
	}


	/**
	 * 根据小区id获取旗下所有的楼栋
	 * @param int $community_id
	 * TODO:kakatool
	 */
	public static function getBuildByCommunity($community_id = 0)
	{

		$criteria = new CDbCriteria();
		$criteria->select = 'id,community_id,name';
		$criteria->compare('community_id', $community_id);
		$criteria->compare('state', 0);
		$criteria->compare('is_deleted', 0);
		$criteria->order = ' display_order  asc';

		$model = Build::model()->enabled()->findAll($criteria);

		if (count($model)) {

			$bulid = array();
			foreach ($model as $m) {
				$bulid[] = array('id' => $m->id, 'name' => $m->name, 'community_id' => $m->community_id);
			}

			return $bulid;

		}
		return array();


	}


	/**
	 * 重载 __get 方法，注入不同的 branch 类型
	 * @param string $name
	 * @return CActiveRecord
	 */
	public function __get($name)
	{
		switch ($name) {
			case 'iCEBranch':
				return ICEBranch::model()->findByPk($this->branch_id);
				break;
			default:
				return parent::__get($name);
				break;
		}
	}

	/*public function __set($name, $value)
	{
		return parent::__set($name, $value);
		if (parent::setAttribute($name, $value) === false) {

		}

		return true;
	}*/

	public function ICESearch()
	{
		$search = isset($_GET['ICECommunity']) ? $_GET['ICECommunity'] : array();
		$state = isset($search['state']) ? $search['state'] : '';
		switch ($state) {
			default:
			case '':
				$state = '';
				break;
			case '0':
				$state = 'N';
				break;
			case '1':
				$state = 'Y';
				break;
		}

		$searchRegion = $this->ICEGetSearchRegionData($search);

		return $this->ICESearchDataProvider($this->ICEGetCommunityPage(
			array(
				'page' => isset($_GET['page']) ? $_GET['page'] : 1,
				'size' => 10,
				'keyword' => isset($search['name']) ? $search['name'] : '',
				/*'pid' => isset($search['ice_branch_id']) && $search['ice_branch_id']
					? $search['ice_branch_id']
					: '9959f117-df60-4d1b-a354-776c20ffb8c7',*/
				'parentid' => isset($search['ice_branch_id']) && $search['ice_branch_id']
					? $search['ice_branch_id']
					: '',
				'code' => isset($search['alpha']) ? $search['alpha'] : '',
				'sort' => '',
				'isClose' => $state,
				'provincecode' => $searchRegion['province_id'],
				'citycode' => $searchRegion['city_id'],
				'regioncode' => $searchRegion['district_id'],
			),
			true,
			ICEService::GetCacheExpire()
		));
	}

	public function ICEGetCommunitySearch($param = array(), $cache = true)
	{
		$param = $param && is_array($param) ? $param : array();
		if (!$param) {
			return array();
		}

		if (!isset($param['pid']) || !$param['pid']) {
			//$param['pid'] = ICEBranch::SUPPER_UUID;
			$param['pid'] = '';
		}

		if (!isset($param['size']) || !$param['size']) {
			$param['size'] = 10;
		}

		if (!isset($param['page']) || !$param['page']) {
			$param['page'] = 1;
		}

		$cacheKey = 'cache:cwy:ice:community:search:' . http_build_query($param);
		$communities = array();

		if ($cache == true) {
			$communities = Yii::app()->rediscache->get($cacheKey);
		} else {
			Yii::app()->rediscache->delete($cacheKey);
		}

		if (!$communities) {
			$communities = ICEService::getInstance()->dispatch(
				'community/search',
				$param,
				array(),
				'get'
			);

			if ($communities) {
				Yii::app()->rediscache->set(
					$cacheKey,
					$communities,
					ICEService::GetCacheExpire()
				);
			}
		}

		return $communities;
	}

	protected function ICEGetCommunityPage($param = array(), $cache = true)
	{
		$param = $param && is_array($param) ? $param : array();
		if (!$param) {
			return array();
		}

		$cacheKey = 'cache:cwy:ice:community:page:' . http_build_query($param);

		$communities = array();
		if ($cache == true) {
			$communities = Yii::app()->rediscache->get($cacheKey);
		} else {
			Yii::app()->rediscache->delete($cacheKey);
		}

		if (!$communities) {
			$communities = ICEService::getInstance()->dispatch(
				'community/page',
				$param,
				array(),
				'get'
			);

			if ($communities) {
				Yii::app()->rediscache->set(
					$cacheKey,
					$communities,
					ICEService::GetCacheExpire()
				);
			}
		}

		return $communities;
	}

	protected function ICESearchDataProvider($communities = array())
	{
		$dataProvider = new CActiveDataProvider(
			$this,
			array(
				'id' => 'uuid',
				//'keyField' => 'uuid',
				'keyAttribute' => 'uuid',
				'keys' => array(
					'uuid'
				),
				'sort' => array(
					'attributes' => array('uuid')
				)
			)
		);

		$dataProvider->setData(
			$this->ICESearchDataProviderData(
				isset($communities['list']) ? $communities['list'] : array()
			)
		);

		$itemCount = isset($communities['totalNum'])
			? $communities['totalNum']
			: 0;
		$dataProvider->setTotalItemCount($itemCount);

		$pagination = new CPagination($itemCount);
		$pagination->params = array(
			'ICECommunity' => isset($_GET['ICECommunity']) ? $_GET['ICECommunity'] : array()
		);
		$pagination->pageVar = 'page';
		$pagination->setPageSize(10);
		$pagination->setItemCount($itemCount);

		$dataProvider->setPagination($pagination);

		return $dataProvider;
	}

	protected function ICESearchDataProviderData($communities = array())
	{
		if (!is_array($communities) || !$communities) {
			return array();
		}

		$data = array();

		foreach ($communities as $item) {
			$item = $this->ICECommunityToColourlife($item);

			$community = new self();
			foreach ($item as $key => $value) {
				$community->setAttribute($key, $value);
			}

			$data[] = $community;
		}

		return $data;
	}

	/**
	 * 获取ICE小区所属事业部和大区直接父节点
	 * @param string $uuid
	 * @param bool $flushCache
	 * @return string
	 */
	protected function ICEGetCommunityLastOrgTag($uuid = '', $flushCache = false)
	{
		$orgs = $this->ICEGetCommunityParent($uuid, $flushCache);
		return isset($orgs['region']) && $orgs['region'] ? $orgs['region'] : '';
	}

	/**
	 * 获取ICE小区所属事业部和大区
	 * @param $uuid
	 * @param bool $flushCache
	 * @return mixed
	 * @throws CHttpException
	 */
	protected function ICEGetCommunityParent($uuid, $flushCache = false)
	{
		$cacheKey = 'cache:cwy:ice:community:parent:' . $uuid;

		if ($flushCache == true) {
			Yii::app()->rediscache->delete($cacheKey);
		}

		$orgs = Yii::app()->rediscache->get($cacheKey);

		if (!$orgs) {
			try {
				$orgs = ICEService::getInstance()->dispatch(
					'community/parent',
					array(
						'id' => $uuid
					),
					array(),
					'get'
				);
			} catch (Exception $e) {
			}

			if ($orgs) {
				Yii::app()->rediscache->set(
					$cacheKey,
					$orgs,
					rand(3600, 86400)
				);
			}
		}

		return $orgs;
	}

	/**
	 * 获取ICE小区所属事业部和大区路径
	 * @param string $uuid
	 * @param string $sep
	 * @param bool $flushCache
	 * @return string
	 */
	protected function ICEGetCommunityFullOrgTag($uuid = '', $sep = '-', $flushCache = false)
	{
		$orgTags = array();
		$orgs = $this->ICEGetCommunityParent($uuid, $flushCache);

		if (isset($orgs['group']) && $orgs['group']) {
			$orgTags[] = $orgs['group'];
		}
		if (isset($orgs['branch']) && $orgs['branch']) {
			$orgTags[] = $orgs['branch'];
		}
		if (isset($orgs['region']) && $orgs['region']) {
			$orgTags[] = $orgs['region'];
		}

		return implode($sep, $orgTags);
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

	public function ICECommunityParent($uuid = '', $cache = true, $expire = 1800)
	{
		if (!$uuid) {
			return null;
		}

		$cacheKey = 'cache:cwy:ice:coumunity:parent:' . $uuid;

		if ($cache == true) {
			$result = Yii::app()->rediscache->get($cacheKey);
			if ($result) {
				return $result;
			}
		}

		$result = ICEService::getInstance()->dispatch(
			'community/parent',
			array(
				'id' => (string)$uuid
			),
			array(),
			'get'
		);

		if ($result) {
			Yii::app()->rediscache->set(
				$cacheKey,
				$result,
				rand(600, $expire)
			);
		}

		return $result;
	}

	public function ICEFindByPk($uuid = '', $cache = true)
	{
		if (!$uuid) {
			return null;
		}

		$cacheKey = 'cache:cwy:ice:coummunity:content:' . $uuid;

		if ($cache == true) {
			$result = Yii::app()->rediscache->get($cacheKey);
			if ($result) {
				return $result;
			}
		}

		$result = array();
		try {
			$result = ICEService::getInstance()->dispatch(
				'community',
				is_numeric($uuid)
					? array(
					'czy_id' => (int)$uuid
				)
					: array(
					'id' => (string)$uuid
				),
				array(),
				'get'
			);
		} catch (Exception $e) {
			Yii::log(
				sprintf('获取ICE小区失败。uuid(czy_id): %s', $uuid),
				CLogger::LEVEL_ERROR, 'colourlife.core.ICECommunity.ICEFindByPk'
			);
		}

		if ($result) {
			Yii::app()->rediscache->set(
				$cacheKey,
				$result,
				ICEService::GetCacheExpire()
			);
		}

		return $result;
	}

	public function getBranchString()
	{
		return ICEBranch::ICEGetMyParentBranchName($this->parentId);
	}

	public function findByPk($pk = '', $condition = '', $params = array())
	{
		$community = $this->ICEFindByPk($pk);
//		返回数据和原来查表一致，为0时返回$this。
		if (empty($community)) {
			return $this;
		}
		if ($community) {
			$community = $this->ICECommunityToColourlife($community);
		}

		foreach ($community as $key => $value) {
			$this->setAttribute($key, $value);
			//$this->{$key} = $value;
		}

		return $this;
	}

	protected function ICECommunityToColourlife($comunity = array())
	{
		return array(
			'id' => isset($comunity['czy_id']) && $comunity['czy_id']
				? $comunity['czy_id'] : '',
			'domain' => isset($comunity['site_prefix']) && $comunity['site_prefix']
				? $comunity['site_prefix'] : '',
			'state' => isset($comunity['islock']) && $comunity['islock'] == 'N'
				? '0' : '1',
			'is_deleted' => isset($comunity['islock']) && $comunity['islock'] == 'N'
				? '0' : '1',
			'lng' => $comunity['longitude'],
			'lat' => $comunity['latitude'],
			'ice_branch_id' => $comunity['parentId'],
			'region_id' => $comunity['regioncode'],
			'branch_id' => $comunity['regioncode'],
		) + $comunity;
	}

	public function ICEGetCommunityAddress($isShort = false)
	{
		return $isShort == true
			? str_replace(' - ', '', $this->regionString)
			: (str_replace(' - ', '', $this->regionString) . $this->name);
	}

	public function ICECheckIsMyBelong($cache = true, $expire = 1800)
	{
		$employee = Employee::model()->findByPk(Yii::app()->user->id, '', array(), true);
		if ($employee && $employee->id == 1) {
			return true;
		}

		$cacheKey = sprintf(
			'cache:cwy:ice:community:belong:%s:%s',
			$employee->id,
			$this->id
		);

		$belong = -1;
		if ($cache == true) {
			$belong = Yii::app()->rediscache->get($cacheKey);
			if ($belong) {
				return $belong == '1';
			}
		}

		// smallareacode
		$result = $this->ICECommunitySearchAllData(array(
			'pid' => $employee->orgId,
			'keyword' => $this->name,
			'code' => !empty($this->smallareacode) ? $this->smallareacode : '',
		));

		if ($result) {
			$belong = '-1';
			foreach ($result as $community) {
				if (isset($community['uuid'])
					&& $community['uuid']
					&& $community['uuid'] == $this->uuid
				) {
					$belong = '1';
					break;
				}
			}

			if ($belong == '1') {
				Yii::app()->rediscache->set(
					$cacheKey,
					$belong,
					rand(600, $expire)
				);
			}
		}

		return $belong == '1';
	}

	protected function ICECommunityDoSearchAllData($search = array(),
	                                               $isAll = true,
	                                               $retrieveAll = true,
	                                               $flush = false)
	{
		//echo 'fetch: ', json_encode($search, JSON_UNESCAPED_UNICODE), PHP_EOL;
		$page = max(1, isset($search['page']) ? $search['page'] : '1');
		$pageSize = max(50, min(isset($search['size']) ? $search['size'] : '200', 200));
		//$pid = isset($search['pid']) ? $search['pid'] : '9959f117-df60-4d1b-a354-776c20ffb8c7';
		$pid = isset($search['pid']) ? $search['pid'] : '';
		$provincecode = isset($search['provincecode']) && $search['provincecode']
			? $search['provincecode'] : '';
		$citycode = isset($search['citycode']) && $search['citycode']
			? $search['citycode'] : '';
		$regioncode = isset($search['regioncode']) && $search['regioncode']
			? $search['regioncode'] : '';
		$keyword = isset($search['keyword']) ? $search['keyword'] : '';
		$code = isset($search['code']) ? $search['code'] : '';

		$communities = array();
		$response = array();

		try {
			if ($isAll == true) {
				$response = $this->ICEGetCommunitySearch(
					array(
						'pid' => $pid,
						'isClose' => 'N',
						'size' => $pageSize,
						'page' => $page,
						'provincecode' => $provincecode,
						'citycode' => $citycode,
						'regioncode' => $regioncode,
						'keyword' => $keyword,
						'code' => $code
					),
					$flush == true ? false : true,
					ICEService::GetCacheExpire()
				);
			} else {
				$response = $this->ICEGetCommunityPage(
					array(
						'parentid' => $pid,
						'isClose' => 'N',
						'size' => $pageSize,
						'page' => $page,
						'provincecode' => $provincecode,
						'citycode' => $citycode,
						'regioncode' => $regioncode,
						'keyword' => $keyword,
						'code' => $code
					),
					$flush == true ? false : true,
					ICEService::GetCacheExpire()
				);
			}

			$communities = isset($response['list']) ? $response['list'] : array();
		} catch (Exception $e) {
		}


		if ($retrieveAll) {
			$totalPage = max(1, isset($response['totalPage']) ? $response['totalPage'] : 0);
			if ($totalPage > $page) {
				sleep(0.1);
				$search['page'] = $page + 1;
				$nextPages = $this->ICECommunityDoSearchAllData(
					$search,
					$isAll,
					$retrieveAll,
					$flush
				);

				if ($nextPages) {
					$communities = array_merge(
						$communities,
						$nextPages
					);
				}
			}
		}

		return $communities;
	}

	/**
	 * 搜索所有小区，逐页请求ICE接口，返回所有小区列表
	 * @param array $search
	 * @param bool $isAll
	 * @param bool $retrieveAll
	 * @param bool $flush
	 * @return array|mixed
	 */
	public function ICECommunitySearchAllData($search = array(),
	                                          $isAll = true,
	                                          $retrieveAll = true,
	                                          $flush = false)
	{
		$cacheKey = ($retrieveAll
				? 'cache:cwy:ice:coummunity:search:all:data:retrieve:all:'
				: 'cache:cwy:ice:coummunity:search:all:data:retrieve:limit:')
			. http_build_query($search);
		$result = array();

		if ($flush == true) {
			Yii::app()->rediscache->delete($cacheKey);
		} else {
			$result = Yii::app()->rediscache->get($cacheKey);
		}

		if (!$result) {
			$result = $this->ICECommunityDoSearchAllData($search, $isAll, $retrieveAll, $flush);

			if ($result) {
				Yii::app()->rediscache->set(
					$cacheKey,
					$result,
					ICEService::GetCacheExpire()
				);
			}
		}

		return $result;
	}

	public function ICEAllCommunityToCache($flush = false)
	{
		$communities = $this->ICECommunitySearchAllData(array(), false, true, $flush);
		// 缓存到当天结束
		$expire = ICEService::GetCacheExpire('tomorrow');
		foreach ($communities as $community) {
			if (!isset($community['czy_id']) || !$community['czy_id']) {
				Yii::app()->rediscache->set(
					sprintf('cache:cwy:ice:coummunity:content:%s', $community['uuid']),
					$community,
					$expire
				);
			}

			Yii::app()->rediscache->set(
				sprintf('cache:cwy:ice:coummunity:content:%s', $community['czy_id']),
				$community,
				$expire
			);
		}

		return $communities;
	}

	protected function ICECommunityToColourlifeRegionZTreeBuilder($communitys = array(), $disabled = false)
	{
		$communitys = !$communitys || !is_array($communitys) ? array() : $communitys;
		$flattened = array();

		foreach ($communitys as $community) {
			if (!isset($community['czy_id']) || !$community['czy_id']) {
				continue;
			}

			if (isset($community['regioncode']) && $community['regioncode']) {
				$pid = $community['regioncode'];
			} else if (isset($community['citycode']) && $community['citycode']) {
				$pid = $community['citycode'];
			} else if (isset($community['provincecode']) && $community['provincecode']) {
				$pid = $community['provincecode'];
			} else {
				$pid = ICERegion::DEFAULT_REGION_ID;
			}

			$flattened[] = array(
				'id' => $community['czy_id'],
				'pId' => 'r' . $pid,
				'name' => '[小区]' . $community['name'],
				'chkDisabled' => $disabled ? true : false
			);

		}

		return $flattened;
	}

	public function ICECommunityToColourlifeRegionZTree($search = array())
	{
		return $this->ICECommunityToColourlifeRegionZTreeBuilder(
			$this->ICECommunitySearchAllData($search, false)
		);
	}

	public function ICEGetCommunityRegions()
	{
		if ($this->regioncode) {
			$regions = ICERegion::model()->ICEGetColourlifeRegions($this->regioncode);
		} else if ($this->citycode) {
			$regions = ICERegion::model()->ICEGetColourlifeRegions($this->citycode);
		} else if ($this->provincecode) {
			$regions = ICERegion::model()->ICEGetColourlifeRegions($this->provincecode);
		} else {
			$regions = array();
		}

		return $regions;
	}

	public function ICEGetCommunityRegionsNames($sep = ' - ')
	{
		$regions = $this->ICEGetCommunityRegions();
		$names = array();

		foreach ($regions as $region) {
			if (!isset($region) || !$region['name']) {
				continue;
			}

			$names[] = $region['name'];
		}

		return implode($sep, $names);
	}

	public function ICEGetCommunityBranches()
	{
		$parents = $this->ICEGetCommunityParent($this->uuid);
		$parent = ICEBranch::model()->findByPk($this->parentId);

		return array(
			array(
				'id' => $parents['group_id'],
				'name' => $parents['group'],
				'parent_id' => ''
			),
			array(
				'id' => $parents['region_id'],
				'name' => $parents['region'],
				'parent_id' => $parents['group_id']
			),
			array(
				'id' => $parents['branch_id'],
				'name' => $parents['branch'],
				'parent_id' => $parents['region_id']
			),
			array(
				'id' => $parent->id,
				'name' => $parent->name,
				'parent_id' => $parents['branch_id']
			)
		);
	}

	public function ICEGetCommunityBranchesNames($sep = ' - ')
	{
		$branches = $this->ICEGetCommunityBranches();
		$names = array();

		foreach ($branches as $branch) {
			if (!isset($branch) || !$branch['name']) {
				continue;
			}

			$names[] = $branch['name'];
		}

		return implode($sep, $names);
	}
}
