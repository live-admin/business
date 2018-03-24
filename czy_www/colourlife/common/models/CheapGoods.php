<?php
error_reporting(E_ALL ^ E_NOTICE);

class CheapGoods extends Goods
{

	/**
	 * @var string 模型名
	 */
	public $modelName = '商品优惠';
	public $_shopName;
	public $startTime;
	public $endTime;
	public $cheapImgFile; //天天特价图片
	public $region;
	public $community;

	public $communityIds; //小区
	public $community_id;

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
			array('audit_cheap', 'checkCheapIsWait', 'on' => 'auditcheap,noauditcheap'),
			//验证推荐的一些规则
			array('startTime,endTime', 'date', 'format' => 'yyyy-MM-dd'),
			array('cheap_category_id', 'checkCheapIsNext', 'on' => 'cheap,update'),
			array('cheap_price', 'checkCheapPrice', 'on' => 'cheap,update'),
			array('end_cheap_time', 'checkEndTime', 'on' => 'cheap,update'),
			array('end_cheap_time', 'checkAuditEndTime', 'on' => 'auditcheap,noauditcheap'),
			array('start_cheap_time,end_cheap_time', 'checkCheapTime', 'on' => 'cheap,update'),
			array('_shopName,startTime,endTime,region,community', 'safe', 'on' => 'search'),
			array('cheapImgFile', 'safe', 'on' => 'create, update'),
			//			ICE 搜索数据
			array('communityIds,province_id,city_id,district_id', 'safe'),
		);
		return CMap::mergeArray(parent::rules(), $array);
	}

	public function attributeLabels()
	{
		$array = array(
			'_shopName' => '所属商家',
			'endTime' => '结束时间',
			'startTime' => '开始时间',
			'cheapImgFile' => '天天特价图片',
			'region' => '地区',
			'community' => '小区',
			'communityIds' => '小区',
		);
		return CMap::mergeArray(parent::attributeLabels(), $array);
	}

	public function search($cheap_category_id = '')
	{

		$criteria = new CDbCriteria;

		if ($this->_shopName != '') {
			$criteria->with[] = 'shop';
			$criteria->compare('shop.name', $this->_shopName, true);
		}

		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.name', $this->name, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare('`t`.audit_cheap', $this->audit_cheap);
		$criteria->compare('`t`.cheap_price', $this->cheap_price);
		$criteria->compare('`t`.cheap_category_id', $cheap_category_id);
		$criteria->addCondition('`t`.cheap_category_id != 0');
		$criteria->addCondition('`t`.end_cheap_time >= ' . time());

		if ($this->startTime != '') {
			$criteria->compare("start_cheap_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("start_cheap_time", "< " . strtotime($this->endTime));
		}

//		if (!empty($this->community)) {
//			$criteria->distinct = true;
//			$criteria->join = 'inner join shop_community_goods_ownership s on s.goods_id=t.id';
//			$criteria->addInCondition('s.community_id', $this->community);
//		} else if ($this->region != '') {
//			$criteria->distinct = true;
//			$criteria->join = 'inner join shop_community_goods_ownership s on goods_id=t.id';
//			$criteria->addInCondition('s.community_id', Region::model()->getRegionCommunity($this->region, 'id'));
//		}

		//选择的组织架构ID
		if (!empty($this->communityIds)) {
			//如果有小区
			$criteria->distinct = true;
			$criteria->join = 'inner join shop_community_goods_ownership s on s.goods_id=t.id';
			$criteria->addInCondition('s.community_id', $this->communityIds);
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

			$criteria->distinct = true;
			$criteria->join = 'inner join shop_community_goods_ownership s on goods_id=t.id';
			$criteria->addInCondition('s.community_id', $community_ids = ICERegion::model()->getRegionCommunity(
				$regionId,
				'id'
			));
		}

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function afterFind()
	{
		$this->startTime = $this->start_cheap_time == 0 ? "" : date('Y-m-d', $this->start_cheap_time);
		$this->endTime = $this->end_cheap_time == 0 ? "" : date('Y-m-d', $this->end_cheap_time);
		return parent::afterFind();
	}

	protected function beforeValidate()
	{
		if (empty($this->startTime)) $this->startTime = date('Y-m-d', time());
		if (empty($this->endTime)) $this->endTime = date('Y-m-d', time());
		$this->start_cheap_time = @strtotime($this->startTime);
		$this->end_cheap_time = @strtotime($this->endTime) + 24 * 60 * 60 - 1;

		if (empty($this->cheap_pic) && !empty($this->cheapImgFile))
			$this->cheap_pic = '';

		return parent::beforeValidate();
	}

	protected function beforeSave()
	{
		if (!empty($this->cheapImgFile)) {
			$this->cheap_pic = Yii::app()->ajaxUploadImage->moveSave($this->cheapImgFile, $this->cheap_pic);
		}
		return parent::beforeSave();
	}

	public function checkCheapIsNext($attribute, $params)
	{
		if ($this->cheap_category_id == 0) {
			$this->addError($attribute, '商品优惠分类不能为空！');
		}
		if (!$this->hasErrors() && $this->state == self::STATE_DISABLE) {
			$this->addError($attribute, '因为该商品被禁用，无法继续进行该操作。');
		} elseif (!$this->hasErrors() && $this->audit != self::AUDIT_PASS) {
			$this->addError($attribute, '商品只有通过审核才能申请特价，无法继续进行该操作。');
		} elseif (!$this->hasErrors() && $this->is_on_sale != self::SALE_YES) {
			$this->addError($attribute, '商品尚未上架销售，无法继续申请特价');
		}
	}

	public function checkCheapIsWait($attribute, $params)
	{
		if (!$this->hasErrors() && $this->audit_cheap != self::AUDIT_CHEAP_WAIT) {
			$this->addError($attribute, '因为商品不能被重复优惠，无法继续进行该操作1。');
		}
	}

	//验证推荐价格,推荐价格不能低于业主价格
	public function checkCheapPrice($attribute, $params)
	{
		if (!$this->hasErrors() && $this->cheap_price > $this->customer_price && $this->cheap_category_id != 0) {
			$this->addError('cheap_price', '优惠价格不能大于业主价格！');
		}
	}

	//结束时间不能小于当前时间
	public function checkEndTime($attribute, $params)
	{
		if (!$this->hasErrors() && $this->cheap_category_id != 0 && $this->end_cheap_time <= time()) {
			$this->addError('endTime', '优惠结束时间不能小于当前时间！');
		}
	}

	//结束时间不能小于审核时间
	public function checkAuditEndTime($attribute, $params)
	{
		if (!$this->hasErrors() && $this->cheap_category_id != 0 && $this->end_cheap_time <= time()) {
			$this->addError('endTime', '优惠结束时间早于当前时间,无法继续进行操作！');
		}
	}

	//检查天天特价时间段。开始时间不能大于结束时间
	public function checkCheapTime($attribute, $params)
	{
		if (!$this->hasErrors() && $this->end_cheap_time <= $this->start_cheap_time && $this->cheap_category_id != 0) {
			$this->addError('endTime', '优惠结束时间不能小于优惠开始时间！');
		}
	}

	public function getIsCheap()
	{
		return $this->cheap_category_id != 0;
	}

	public function getIsSale()
	{
		return $this->cheap_category_id == 2;
	}

	public function getAuditCheap()
	{
		return $this->audit_cheap == self::AUDIT_CHEAP_WAIT;
	}

	public function getAuditCheapYes()
	{
		return $this->audit_cheap == self::AUDIT_CHEAP_YES;
	}

	public function cheap()
	{
		//return $this->updateByPk($this->id,array('is_cheap'=>self::IS_CHEAP_YES));
	}

	public function nocheap()
	{
		//return $this->updateByPk($this->id,array('is_cheap'=>self::IS_CHEAP_NO));
	}

	public function auditCheap()
	{
		if ($this->updateByPk($this->id, array('audit_cheap' => self::AUDIT_CHEAP_YES))) {
			if ($this->cheap_category_id == 2 || $this->cheap_category_id == 3) {
				return true;
			} //如果是促销。。 不添加日志
			$log = new CheapLog();
			$log->goods_id = $this->id;
			$log->price = $this->cheap_price;
			$log->begin_time = $this->start_cheap_time;
			$log->end_time = $this->end_cheap_time;
			$log->cheap_pic = $this->cheap_pic;
			$log->display_order = $this->display_order;
			$log->status = CheapLog::CHEAP_ING;
			$log->is_deleted = 0;
			if ($log->save()) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function noAuditCheap()
	{
		return $this->updateByPk($this->id, array('audit_cheap' => self::AUDIT_CHEAP_NO,
			'cheap_price' => 0, 'start_cheap_time' => 0, 'end_cheap_time' => 0));
	}

	public function getCheapPic()
	{
		return Yii::app()->ajaxUploadImage->getUrl($this->cheap_pic);
	}

	public function getNameHtml()
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' => '电话:' . $this->shop->tel . ', 手机:' . $this->shop->mobile . ', 地址:' .
			$this->shop->address . ', 所属部门:' . Branch::getMyParentBranchName($this->shop->branch_id, true)), $this->shop->name);
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
