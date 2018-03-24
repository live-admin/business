<?php

/**
 * 3.0标签 //TODO:kakatool
 */
class HomeConfigTag extends CActiveRecord
{


	public $modelName = '标签';

	public $imgFile;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_tag';
	}

	public function rules()
	{
		return array(
			array('name, state, sort', 'required', 'on' => 'update,create'),
			array('name, imgFile, state, sort', 'safe', 'on' => 'update,create'),
			array('id, name, img, state, sort', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
			'img' => '图片',
			'imgFile' => '图片',
			'state' => '状态',
			'sort' => '显示排序',
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
			$this->creationtime = time();
		}
		$this->updatetime = time();
		return parent::beforeSave();
	}
}