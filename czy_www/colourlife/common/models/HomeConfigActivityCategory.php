<?php
/**
 * Created by PhpStorm.
 * User: austin
 * Date: 1/11/16
 * Time: 9:53 下午
 */

class HomeConfigActivityCategory extends CActiveRecord{

	public $modelName = '模板';
	public $is_default;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_activity_category';
	}

	public function rules()
	{
		return array(
			array('name, style, state', 'required', 'on' => 'create'),
			array('name, style, state', 'required', 'on' => 'update'),
			array('name, style, state, start_time, stop_time,is_default', 'safe', 'on' => 'update,create'),
			array('id, name, style, state, start_time, stop_time', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
			'style' => '样式',
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
		$criteria->compare('name', $this->name, true);
		$criteria->compare('state', $this->state);
		$criteria->order = 'id';
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
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
		return $branch->getRegionCommunityRelation($this->id, 'HomeConfigActivityCategory', false);
	}
} 