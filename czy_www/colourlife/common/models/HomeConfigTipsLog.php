<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 3/9/16
 * Time: 3:53 下午
 */

class HomeConfigTipsLog extends CActiveRecord{

	public $modelName = '图片提示日志';

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_tips_log';
	}

	public function rules()
	{
		return array(
				array('tip_id, customer_id, create_time', 'required', 'on' => 'create'),
				array('tip_id, customer_id, create_time', 'safe', 'on' => 'update,create'),
				array('tip_id, customer_id', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
				'tip_id' => '提示ID',
				'customer_id' => '用户ID',
				'create_time' => '创建时间',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('tip_id', $this->section_code);
		$criteria->order = 'id';
		return new ActiveDataProvider($this, array(
				'criteria' => $criteria,
		));
	}
} 