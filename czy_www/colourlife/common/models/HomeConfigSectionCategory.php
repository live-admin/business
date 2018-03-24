<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 3/9/16
 * Time: 3:53 下午
 */

class HomeConfigSectionCategory extends CActiveRecord{

	public $modelName = '板块内容分类目录';

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_section_category';
	}

	public static function GetCategoryName($categoryID = 0)
	{
		$category = self::model()->findByPk($categoryID);
		if ($category && $category->name) {
			return $category->name;
		} else {
			return '未分组';
		}
	}

	public function rules()
	{
		return array(
				array('name, section_code', 'required', 'on' => 'create'),
				array('name', 'required', 'on' => 'update'),
				array('name, section_code', 'safe', 'on' => 'update,create'),
				array('id, name, section_code', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
				'id' => 'ID',
				'name' => '名称',
				'section_code' => '板块标示',
				'remark' => '备注',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('name', $this->name, true);
		$criteria->compare('section_code', $this->section_code);
		$criteria->order = 'id';
		return new ActiveDataProvider($this, array(
				'criteria' => $criteria,
		));
	}
} 