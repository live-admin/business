<?php

/**
 * 处理过的投诉
 * Created by PhpStorm.
 * User: wede
 * Date: 14-1-17
 * Time: 下午2:19
 */
class ExecutionHandingComplain extends ComplainRepairs
{
	public $communityIds = array(); //小区
	public $branch_id;
	public $region;
	public $community_id;

	public $province_id;
	public $city_id;
	public $district_id;

	public $complainRepairsType = Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER;

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function attributeLabels()
	{
		$array = array(
			'complain_repairs_id' => '投诉工单',
			'complainRepairsCreateTime' => '投诉时间',
			'repairCategoryName' => '类别',
			'execAttr' => '执行人',
			'superAttr' => '监督人',
			'images' => '图片',
			'confirm' => '确认人',
			'execName' => '执行人',
			'superName' => '监督人',
			'confirmName' => '确认人',
			'content' => '报修内容',
			'customerName' => '投诉人',
			'customerTel' => '联系电话',
			'create_time' => '报修时间',
			'communityInBranch' => '报修小区所属部门',
			'communityDetail' => '业主报修小区',
		);
		return CMap::mergeArray(parent::attributeLabels(), $array);
	}


	public function executionSearch($type = 0)
	{
		$criteria = new CDbCriteria;

		//按类型得到**列表
		$handling = ComplainRepairsHandling::model()->findAllByAttributes(array('type' => $type, 'employee_id' => Yii::app()->user->id));
		$idList = array();
		foreach ($handling as $hand) {
			//获得该用户的所有投诉ID
			array_push($idList, $hand->complain_repairs_id);
		}
		$criteria->addInCondition('`t`.id', $idList);
		$criteria->compare('`t`.id', $this->id);
		$criteria->compare('`t`.type', Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER);

		$criteria->compare('`t`.category_id', $this->category_id);
		$criteria->compare('`t`.customer_name', $this->customer_name, true);
		$criteria->compare('`t`.customer_tel', $this->customer_tel, true);
		$criteria->compare('`t`.final_evaluate_state', $this->final_evaluate_state, true);
		$criteria->compare('`t`.state', $this->state);
		$criteria->compare("`t`.`execute`", $this->execute);
		$criteria->compare('`t`.`confirm`', $this->confirm);

		if ($this->username != '') {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.username', $this->username, true);
		}

		if ($this->startTime != '') {
			$criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
		}

		if ($this->endTime != '') {
			$criteria->compare("`t`.create_time", "< " . strtotime($this->endTime . " 23:59:59"));
		}

		//选择的组织架构ID
//        if ($this->branch_id != '')
//            $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
//        else if (!empty($this->communityIds)) //如果有小区
//            $community_ids = $this->communityIds;
//        else if ($this->region != '') //如果有地区
//            $community_ids = Region::model()->getRegionCommunity($this->region, 'id');


		//选择的组织架构ID
		if ($this->branch_id != '') {
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
		}

		if (!empty($community_ids))
			$criteria->addInCondition('`t`.community_id', $community_ids);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 't.id desc',
			)
		));
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