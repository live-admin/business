<?php

/**
 * Created by PhpStorm.
 * User: austin
 * Date: 1/11/16
 * Time: 10:03 下午
 */
class HomeConfigMoreFunc extends CActiveRecord
{
	const IS_NEW_YES = 1;
	const IS_NEW_NO = 1;

	public $modelName = '主功能栏更多功能';
	public $imgFile;
	public $labelIconFile;
	public $tag_id;
	public $community_ids;

	public $is_default;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_morefunc';
	}

	public function rules()
	{
		return array(
			array('name, resource_id, category_id, act, sort, is_new, is_default', 'required', 'on' => 'create'),
			array('name, resource_id, category_id, act, native, state, sort, is_new, is_default', 'required', 'on' => 'update'),
			array('name, resource_id, category_id, img, imgFile, labelIconFile, act, native, state, sort, is_new, tag_id,is_default', 'safe', 'on' => 'update,create'),
			array('id, name, resource_id, category_id, img, act, native, state, sort, is_new, tag_id', 'safe', 'on' => 'search'),
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
			'is_new' => '新应用',
			'label_icon' => '角标',
			'labelIconFile' => '角标',
			'tag_id' => '标签',
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
		$criteria->select = 'm.*, r.tag_id';
		$criteria->alias = 'm';
		$criteria->join = 'LEFT JOIN home_config_mainfunc_tag_relation AS r ON r.more_function_id = m.id';
		$criteria->compare('m.name', $this->name, true);
		$criteria->compare('m.act', $this->act, true);
		$criteria->compare('m.category_id', $this->category_id, true);
		$criteria->compare('r.tag_id', $this->tag_id, true);
		$criteria->compare('m.is_new', $this->is_new);
		$criteria->compare('m.state', $this->state);
		$criteria->order = 'm.category_id, m.sort, m.id';
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
			$category = HomeConfigMoreCategory::model()->findByPk($this->category_id);
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

		$findCategories = HomeConfigMoreCategory::model()->findAll();

		foreach ($findCategories as $category) {
			$categories[$category->id] = $category->name;
		}

		return $categories;
	}

	public function getTagDropdown($unshiftBlank = false)
	{
		$tags = array();
		if ($unshiftBlank === true) {
			$tags[''] = '全部';
		}

		$findTags = HomeConfigTag::model()->findAll();

		foreach ($findTags as $tag) {
			$tags[$tag->id] = $tag->name;
		}

		return $tags;
	}

	public function getTagName()
	{
		if ($this->tag_id) {
			$tag = HomeConfigTag::model()->findByPk($this->tag_id);
			if ($tag && $tag->name) {
				return trim($tag->name);
			} else {
				return '--';
			}
		} else {
			return '--';
		}
	}

	public function getIsNewLabel()
	{
		return $this->is_new == self::IS_NEW_YES ? '是' : '否';
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
		if (!empty($this->labelIconFile)) {
			$this->label_icon = Yii::app()->ajaxUploadImage->moveSave($this->labelIconFile, $this->label_icon);
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
        //$this->is_default = $this->is_default == '1' ? '1' : '0';
		return parent::beforeSave();
	}

	public function getCommunityTreeData()
	{
		//默认去全国所有小区，如需要做现在则改变branch_id的值可改变范围
		$branch_id = 1;
		$branch = Branch::model()->findByPk($branch_id);
		return $branch->getRegionCommunityRelation($this->id, 'HomeConfigMoreFunc', false);
	}
} 
