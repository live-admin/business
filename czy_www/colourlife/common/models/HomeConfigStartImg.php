<?php

/**
 * 3.0标签 //TODO:kakatool
 */
class HomeConfigStartImg extends CActiveRecord
{

	public $modelName = 'app启动页';

	public $imgFile;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_start_img';
	}

	public function rules()
	{
		return array(
			array('name, imgFile, load_time, start_time, end_time', 'required', 'on' => 'create'),
			array('name, resource_id, img, state, load_time, start_time, end_time', 'required', 'on' => 'update'),
			array('name, resource_id, img, imgFile, state, load_time, start_time, end_time', 'safe', 'on' => 'update,create'),
			array('id, name, resource_id, img, state', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'resource_id' => '来源资源',
			'name' => '名称',
			'img' => '图片',
			'imgFile' => '图片',
			'state' => '状态',
			'load_time' => '加载时间',
			'start_time' => '有效开始时间',
			'end_time' => '有效结束时间',
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

	/**
	 * 获取资源图片
	 * @return string
	 */
	public function getImgUrl()
	{
		return Yii::app()->ajaxUploadImage->getUrl($this->img);
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


		if ( !$this->resource_id)
			$this->resource_id = 0;


		return parent::beforeSave();
	}
}