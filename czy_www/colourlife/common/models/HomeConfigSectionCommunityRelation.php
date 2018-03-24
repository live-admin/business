<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 3/9/16
 * Time: 3:53 下午
 */

class HomeConfigSectionCommunityRelation extends CActiveRecord{

	public $modelName = '板块功能小区关联';

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_section_community_relation';
	}

	public function rules()
	{
		return array(
				array('section_id, community_id', 'required'),
				array('section_id, community_id, update_time', 'numerical', 'integerOnly' => true),
				array('section_id, community_id', 'safe', 'on' => 'search'),
		);
	}
}