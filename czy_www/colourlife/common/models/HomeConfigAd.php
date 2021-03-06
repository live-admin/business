<?php

/**
 * Created by PhpStorm.
 * User: austin
 * Date: 1/11/16
 * Time: 9:58 下午
 */
class HomeConfigAd extends CActiveRecord
{

	public $modelName = '首页广告';

	public $imgFile;

	public $community_ids;

	public $is_default;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_ad';
	}

	public function rules()
	{
		return array(
			array('title, resource_id, img, imgFile, act, sort', 'required', 'on' => 'create'),
			array('title, resource_id, img, act, native, state, sort', 'required', 'on' => 'update'),
			array('title, resource_id, img, imgFile, act, native, state, sort, start_time, stop_time,is_default', 'safe', 'on' => 'update,create'),
			array('id, title, resource_id, img, act, native, state, sort, start_time, stop_time', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'resource_id' => '来源资源',
			'title' => '名称',
			'img' => '图片',
			'imgFile' => '图片',
			'act' => '应用url/原型proto',
			'native' => '协议',
			'state' => '状态',
			'sort' => '显示排序',
			'start_time' => '开始时间',
			'stop_time' => '结束时间',
			'community_ids' => '服务范围',
			'is_default' => '全局默认',
		);
	}

	public function behaviors()
	{
		return array(
			//'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
			'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
			//'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('title', $this->title, true);
		$criteria->compare('act', $this->act, true);
		$criteria->compare('state', $this->state);
		$criteria->order = 'sort, id';
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * 获取资源图片
	 * @return string
	 */
	public function getImgUrl()
	{
		if (strstr($this->img, 'v30')) {
			$res = new PublicFunV23();
			return $res->setAbleUploadImg($this->img);
		} else {
			return Yii::app()->ajaxUploadImage->getUrl($this->img);
		}
	}

	public function isPorto()
	{
		if ($this->act == 'url')
			return '应用URL';
		else
			return '手机原型';
	}

	public function getResourceName()
	{
		if ($this->resource_id) {
			$resource = HomeConfigResource::model()->findByPk($this->resource_id);
			if ($resource && $resource->name) {
				return trim($resource->name);
			} else {
				return '';
			}
		} else {
			return '';
		}
	}

	public function getStartTime()
	{
		if ($this->start_time && is_numeric($this->start_time)) {
			return date('Y-m-d', $this->start_time);
		} else {
			return '';
		}
	}

	public function getStopTime()
	{
		if ($this->stop_time && is_numeric($this->stop_time)) {
			return date('Y-m-d', $this->stop_time);
		} else {
			return '';
		}
	}


	public function getIsDefault()
	{
		return $this->is_default == 1 ? '是' : '否';
	}

	/**
	 * 处理图片
	 * @return bool
	 */
	protected function beforeSave()
	{
		if (!empty($this->imgFile)) {
			$this->img = Yii::app()->ajaxUploadImage->moveSave($this->imgFile, $this->img);
		}

		if ($this->isNewRecord) {
			$this->state = '1';

			if ($this->resource_id) {
				$resource = HomeConfigResource::model()->findByPk($this->resource_id);
				if (!$resource || !$resource->name) {
					$this->resource_id = 0;
				} else {
					if ($this->act == 'proto'
						&& !$this->native && $resource->native
					) {
						$this->native = $resource->native;
					}
				}
			} else {
				$this->resource_id = 0;
			}
		}

		if ($this->start_time) {
			$startTime = strtotime($this->start_time . ' 00:00:00');
			$this->start_time = $startTime > 0 ? $startTime : 0;
		} else {
			$this->start_time = 0;
		}
		if ($this->stop_time) {
			$stopTime = strtotime($this->stop_time . ' 23:59:59');
			$this->stop_time = $stopTime > 0 ? $stopTime : 0;
		} else {
			$this->stop_time = 0;
		}
		if ($this->start_time && $this->stop_time && $this->start_time > $this->stop_time) {
			return $this->addError('start_time', '开始时间不能大于结束时间。');
		}
		return parent::beforeSave();
	}

	public function getCommunityTreeData()
	{
		//默认去全国所有小区，如需要做现在则改变branch_id的值可改变范围
		$branch_id = 1;
		$branch = Branch::model()->findByPk($branch_id);
		return $branch->getRegionCommunityRelation($this->id, 'HomeConfigAd', false);
	}
} 
