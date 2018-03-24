<?php

/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 3/9/16
 * Time: 3:53 下午
 */
class HomeConfigSectionFunc extends CActiveRecord
{
	public $modelName = '板块功能栏';
	public $imgFile;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_sectionfunc';
	}

	public function rules()
	{
		return array(
			array('name, resource_id, category_id, act, sort', 'required', 'on' => 'create'),
			array('name, resource_id, category_id, act, native, state, sort', 'required', 'on' => 'update'),
			array('name, resource_id, category_id, img, imgFile, labelIconFile, act, native, state, sort', 'safe', 'on' => 'update,create'),
			array('id, name, resource_id, category_id, img, act, native, state, sort', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'resource_id' => '来源资源',
			'category_id' => '分组',
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
		$criteria->compare('category_id', $this->category_id, true);
		$criteria->compare('state', $this->state);
		$criteria->order = 'category_id, sort, id';
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

	public function getLabelIconUrl()
	{
		if (strstr($this->label_icon, 'v30')) {
			$res = new PublicFunV23();
			return $res->setAbleUploadImg($this->label_icon);
		} else {
			return Yii::app()->ajaxUploadImage->getUrl($this->label_icon);
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

	public function getCategoryName()
	{
		if ($this->category_id) {
			$category = HomeConfigSectionCategory::model()->findByPk($this->category_id);
			if ($category && $category->name) {
				return trim($category->name);
			} else {
				return '';
			}
		} else {
			return '';
		}
	}

	public function getCategoryDropdown($unshiftBlank = false)
	{
		$categories = array();
		if ($unshiftBlank === true) {
			$categories[''] = '全部';
		}

		$findCategories = HomeConfigSectionCategory::model()->findAll();

		foreach ($findCategories as $category) {
			$categories[$category->id] = $category->name;
		}

		return $categories;
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
		if (!empty($this->labelIconFile)) {
			$this->label_icon = Yii::app()->ajaxUploadImage->moveSave($this->labelIconFile, $this->label_icon);
		}

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

		return parent::beforeSave();
	}


} 