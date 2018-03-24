<?php

/**
 * 3.0标签 //TODO:kakatool
 */
class HomeConfigCustomerTagRelation extends CActiveRecord
{


	public $modelName = '用户标签';

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_customer_tag_relation';
	}
}