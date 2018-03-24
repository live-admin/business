<?php

/**
 * Created by PhpStorm.
 * User: austin
 * Date: 1/14/16
 * Time: 6:07 下午
 */
class HomeConfigMainFuncMoreRelation extends CActiveRecord
{


	public $modelName = '主功能';
	public $name;
	public $community_name;
	public $img;
	public $act;
	public $native;
	public $sort;
	public $category_id;
	public $resource_id;
	public $tag_id;
	public $creationtime;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_mainfunc_more_relation';
	}

	public function rules()
	{
		return array(
			array('more_function_id, community_id', 'required', 'on' => 'create'),
			array('more_function_id, community_id', 'required', 'on' => 'update'),
			array('more_function_id, community_id, creationtime', 'safe', 'on' => 'update,create'),
			array('id, more_function_id, community_id, name, category_id, tag_id', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
			'more_function_id' => '更多功能来源',
			'community_id' => '小区',
			'community_name' => '小区',
			'creationtime' => '创建时间',
			'resource_id' => '来源资源',
			'category_id' => '分组',
			'img' => '图片',
			'act' => '应用url/原型proto',
			'native' => '协议',
			'state' => '状态',
			'sort' => '显示排序',
			'is_new' => '新应用',
			'label_icon' => '角标',
			'labelIconFile' => '角标',
			'tag_id' => '标签',
		);
	}

	public function behaviors()
	{
		return array(
			//'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
			//'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
			//'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 'm.*, f.name, f.img, f.sort, f.act, f.native, f.category_id, f.resource_id, t.tag_id, c.name AS community_name';
		$criteria->alias = 'm';
		$criteria->join = 'LEFT JOIN home_config_morefunc AS f ON f.id = m.more_function_id
			LEFT JOIN home_config_mainfunc_tag_relation AS t ON t.more_function_id = m.more_function_id
			LEFT JOIN community AS c ON c.id = m.community_id';

        if (is_numeric($this->community_id)) {
            $criteria->compare('t.community_id', $this->community_id);
        } else {
            if ($this->community_id == '全部小区') {
                $this->community_id = '';
            }
            $criteria->compare('c.`name`', $this->community_id, true);
        }
		$criteria->compare('f.name', $this->name, true);
		$criteria->compare('f.category_id', $this->category_id);
		$criteria->compare('t.tag_id', $this->tag_id);

		$criteria->order = 'm.community_id, f.sort';
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

	public function getCommunityName()
	{
		return $this->community_name ? $this->community_name : '全部小区';
	}

	/**
	 * 处理图片
	 * @return bool
	 */
	protected function beforeSave()
	{
        // 检查是否小区id
        if ($this->community_id == 'r0') {
            $this->community_id = 0;
        } else {
            if (Branch::model()->count('parent_id = :community_id', array( ':community_id' => $this->community_id))) {
                return $this->addError('community_id', '请选择正确的小区');
            }
        }

		$relation = self::model()->find(
			'more_function_id = :more_function_id AND community_id = :community_id',
			array(
				':more_function_id' => $this->more_function_id,
				':community_id' => $this->community_id,
			)
		);

		if ($relation) {
			return false;
		}

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
