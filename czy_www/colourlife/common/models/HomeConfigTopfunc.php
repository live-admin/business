<?php

/**
 * 3.0标签 //TODO:kakatool
 */
class HomeConfigTopfunc extends CActiveRecord
{

	public $modelName = '顶部功能栏';

	public $imgFile;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_topfunc';
	}

	public function rules()
	{
		return array(
			array('name, resource_id, img, imgFile, act, sort', 'required', 'on' => 'create'),
			array('name, resource_id, img, act, native, state, sort', 'required', 'on' => 'update'),
			array('name, resource_id, img, imgFile, act, native, state, sort', 'safe', 'on' => 'update,create'),
			array('id, name, resource_id, img, act, native, state, sort', 'safe', 'on' => 'search'),
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
			'act' => '应用url/原型proto',
			'native' => '协议',
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
		$criteria->compare('name', $this->name, true);
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
		return parent::beforeSave();
	}
}
