<?php

/**
 * 3.0标签 //TODO:kakatool
 */
class HomeConfigMoreCategory extends CActiveRecord
{


	public $modelName = '主功能目录';

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_more_category';
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
			array('name', 'required', 'on' => 'create'),
			array('name', 'required', 'on' => 'update'),
			array('name', 'safe', 'on' => 'update,create'),
			array('id, name', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('name', $this->name, true);
		$criteria->order = 'id';
		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}