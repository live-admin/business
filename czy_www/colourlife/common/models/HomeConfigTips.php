<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 3/9/16
 * Time: 3:53 下午
 */

class HomeConfigTips extends CActiveRecord{

	public $modelName = '图片提示';

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_tips';
	}

	public function rules()
	{
		return array(
				array('section_code, image, create_time', 'required', 'on' => 'create'),
				array('image', 'required', 'on' => 'update'),
				array('section_code, image', 'safe', 'on' => 'update,create'),
				array('section_code', 'safe', 'on' => 'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
				'id' => 'ID',
				'section_code' => '所属板块',
				'image' => '图片地址',
				'times' => '提示次数',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('section_code', $this->section_code);
		$criteria->order = 'id';
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
		if (strstr($this->image, 'v30')) {
			$res = new PublicFunV23();
			return $res->setAbleUploadImg($this->image);
		} else {
			return Yii::app()->ajaxUploadImage->getUrl($this->image);
		}
	}
} 