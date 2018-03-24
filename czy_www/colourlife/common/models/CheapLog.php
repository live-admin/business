<?php

/**
 * This is the model class for table "cheap_log".
 *
 * The followings are the available columns in table 'cheap_log':
 * @property integer $id
 * @property integer $goods_id
 * @property string $price
 * @property integer $begin_time
 * @property integer $end_time
 * @property integer $display_order
 * @property integer $status
 * @property integer $is_deleted
 * @property string $big_pic
 */
class CheapLog extends CActiveRecord
{
	public $_goodName;
	public $region;
	public $community;
	public $startTime;
	public $endTime;

	public $communityIds; //小区
	public $community_id;

	public $province_id;
	public $city_id;
	public $district_id;

	/**
	 * @var string 模型名
	 */
	public $modelName = '天天特价历史记录';

	const CHEAP_ING = 0;
	const CHEAP_OLD = 1;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CheapLog the static model class
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
		return 'cheap_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_id, begin_time, end_time, display_order, status,sales, is_deleted', 'numerical', 'integerOnly' => true),
			array('price', 'length', 'max' => 10),
			array('end_time', 'checkEndTime', 'on' => 'cheap'),
			array('big_pic', 'safe', 'on' => 'create'),
			array('end_time', 'checkCheapTime', 'on' => 'cheap'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, goods_id,_goodName, price, begin_time, end_time, display_order, status,sales, is_deleted,region,community,startTime,endTime', 'safe', 'on' => 'search'),
			//			ICE 搜索数据
			array('communityIds,province_id,city_id,district_id', 'safe'),
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
			'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'goods_id' => '特价商品',
			'_goodName' => '特价商品',
			'price' => '优惠价格',
			'begin_time' => '开始时间',
			'end_time' => '结束时间',
			'display_order' => '优先级',
			'status' => '状态',
			'is_deleted' => '是否删除',
			'sales' => '销售量',
			'region' => '地区',
			'community' => '小区',
			'startTime' => '开始时间',
			'endTime' => '结束时间',
			'communityIds' => '小区',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('t.id', $this->id);
		if ($this->_goodName != '') {
			$criteria->with[] = 'goods';
			$criteria->compare('goods.name', $this->_goodName, true);
		}
		$criteria->compare('t.price', $this->price, true);

		$criteria->compare('t.display_order', $this->display_order);
		$criteria->compare('t.status', $this->status);

		if ($this->startTime != '') {
			$criteria->compare("t.begin_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("t.end_time", "< " . strtotime($this->endTime));
		}

//		if (!empty($this->community)) {
//			$criteria->distinct = true;
//			$criteria->join = 'inner join shop_community_goods_ownership s on s.goods_id=t.goods_id';
//			$criteria->addInCondition('s.community_id', $this->community);
//		} else if ($this->region != '') {
//			$criteria->distinct = true;
//			$criteria->join = 'inner join shop_community_goods_ownership s on s.goods_id=t.goods_id';
//			$criteria->addInCondition('s.community_id', Region::model()->getRegionCommunity($this->region, 'id'));
//		}

		//选择的组织架构ID
		if (!empty($this->communityIds)) {
			//如果有小区
			$criteria->distinct = true;
			$criteria->join = 'inner join shop_community_goods_ownership s on s.goods_id=t.goods_id';
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
			$criteria->join = 'inner join shop_community_goods_ownership s on s.goods_id=t.goods_id';
			$criteria->addInCondition('s.community_id', $community_ids = ICERegion::model()->getRegionCommunity(
				$regionId,
				'id'
			));
		}

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'id desc',
			)
		));

	}

	public function getUnit()
	{
		return empty($this->goods) ? '' : $this->goods->unit;
	}

	public function behaviors()
	{
		return array(

			'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
		);
	}

	//结束时间不能小于当前时间
	public function checkEndTime($attribute, $params)
	{
		if (!$this->hasErrors() && $this->end_time <= time()) {
			$this->addError('endTime', '天天特价结束时间不能小于当前时间！');
		}
	}

	//检查天天特价时间段。开始时间不能大于结束时间
	public function checkCheapTime($attribute, $params)
	{
		if (!$this->hasErrors() && $this->end_time <= $this->begin_time) {
			$this->addError('endTime', '天天特价结束时间不能早于天天特价开始时间！');
		}
	}

	public function getName()
	{
		return empty($this->goods) ? '' : $this->goods->name;
	}

	public function getGoodsName()
	{
		return empty($this->goods) ? '' : $this->goods->name;
	}

	public function getGoodsBrief()
	{
		return empty($this->goods) ? '' : $this->goods->brief;
	}

	public function getShopName()
	{
		return empty($this->goods) ? '' : $this->goods->shopName;
	}

	public function getShopUrl($path = '')
	{
		return empty($this->goods) ? '' : $this->goods->getShopUrl($path);
	}

	public function getGoodsScore()
	{
		return empty($this->goods) ? '' : $this->goods->score;
	}

	public function getGoodImgUrl()
	{
		return empty($this->goods) ? '' : $this->goods->goodImgUrl;
	}

	public function getCheapLogName()
	{
		if ($this->status == self::CHEAP_OLD) {
			$return = '<span class="label label-error">历史优惠</span>';
		} else {
			$return = '<span class="label label-success">正在优惠中</span>';
		}
		return $return;
	}

	public function getLogStatus($select = false)
	{
		$return = array();
		if ($select) {
			$return[''] = '全部';
		}
		$return[0] = '正在优惠中';
		$return[1] = '历史优惠';
		return $return;
	}

	//商品原价
	public function getCustomer_price()
	{
		return empty($this->goods) ? '' : $this->goods->customer_price;
	}

	public function getStart_cheap_time()
	{
		return $this->begin_time;
	}

	public function getEnd_cheap_time()
	{
		return $this->end_time;
	}

	public function getCommunityLastCheapGoods($community_id)
	{
		$criteria = new CDbCriteria;
		$criteria->join = ' inner join goods c on t.goods_id=c.id
        inner join shop_community_goods_sell b on t.goods_id=b.goods_id and c.id=b.goods_id
        and  b.community_id=' . $community_id . ' and b.is_on_sale=' . Goods::SALE_NO;
		$criteria->addCondition('status=' . CheapLog::CHEAP_ING); //正在优惠的商品

		$criteria->order = 'display_order DESC';
		return $this->find($criteria);
	}

	/**
	 * 根据小区ID得到天天物价商品
	 * @param $community_id 为小区ID
	 * $param $goods_id 如果不为空，表示不包含此ID的商品
	 */

	public function getCommunityCheapGoods($community_id, $num = '', $goods_id = '')
	{
		$criteria = new CDbCriteria;
		$criteria->join = ' inner join goods c on t.goods_id=c.id
        inner join shop_community_goods_sell b on t.goods_id=b.goods_id and c.id=b.goods_id
        and  b.community_id=' . $community_id . ' and b.is_on_sale=' . Goods::SALE_NO;
		$criteria->addCondition('status=' . CheapLog::CHEAP_ING); //正在优惠的商品
		if ($goods_id != '') {
			$criteria->addCondition('b.goods_id!=' . $goods_id); //正在优惠的商品
		}
		$criteria->order = 'display_order DESC';
		if ($num != '') {
			$criteria->limit = intval($num);
		}
		return $this->findAll($criteria);
	}

	//获取原价
	public function getOldPrice()
	{
		return empty($this->goods) ? 0 : $this->goods->customer_price;
	}

	public function getCheapPic()
	{
		if (!empty($this->cheap_pic))
			return Yii::app()->ajaxUploadImage->getUrl($this->cheap_pic);
		else
			return $this->goodImgUrl;
	}

	// 增加天天特价历史销量
	static public function addSales($goods_id, $num)
	{
		error_reporting(E_ALL ^ E_NOTICE);
		$sales = $num + self::model()->find('goods_id=:goods_id and status=:status',
				array(':goods_id' => $goods_id, ':status' => CheapLog::CHEAP_ING))->sales;
		return self::model()->updateAll(array('sales' => $sales), 'goods_id=:goods_id and status=:status',
			array(':goods_id' => $goods_id, ':status' => CheapLog::CHEAP_ING));
	}

	public function getMarketPrice()
	{
		return empty($this->goods) ? 0 : $this->goods->market_price;
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
