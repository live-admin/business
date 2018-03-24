<?php

class HomeConfigTopAd extends CActiveRecord
{

	public $modelName = '首页顶部广告';
	public $imgFile;
	public $community_ids;
	public $is_default;

	public static function model($classNeme = __CLASS__)
	{
		return parent::model($classNeme);
	}

	public function tableName()
	{
		return 'home_config_top_ad';
	}

	public function rules()
	{

		return array(
//			array('top_ad_img ,imgFile', 'required'),
//			array('resource_id, state, last_time', 'numerical', 'integerOnly'=>true),
//			array('name', 'length', 'max'=>200),
//			array('top_ad_url, top_ad_img, top_ad_one, top_ad_two, top_ad_three', 'length', 'max'=>255),
//			// The following rule is used by search().
//			// @todo Please remove those attributes that should not be searched.
//			array('id, name, top_ad_url, top_ad_img, , imgFile ,resource_id, top_ad_one, top_ad_two, top_ad_three, state, last_time', 'safe', 'on'=>'search'),

			array('name, resource_id, imgFile', 'required', 'on' => 'create'),
			array('name, resource_id ', 'required', 'on' => 'update'),
			array('name, resource_id, imgFile , top_ad_url , top_ad_img , last_time', 'safe', 'on' => 'update,create'),
			array('id, name, top_ad_url, top_ad_img, , imgFile ,resource_id, top_ad_one, top_ad_two, top_ad_three, state, last_time', 'safe', 'on' => 'search'),

		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '名称',
			'top_ad_url' => '跳转地址',
			'top_ad_img' => '图片',
			'resource_id' => '来源资源',
			'top_ad_one' => '版块一',
			'top_ad_two' => '版块二',
			'top_ad_three' => '版块三',
			'state' => '状态',
			'last_time' => '最后改动时间',
			'imgFile' => '图片',
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
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('top_ad_url',$this->top_ad_url,true);
		$criteria->compare('top_ad_img',$this->top_ad_img,true);
		$criteria->compare('resource_id',$this->resource_id);
		$criteria->compare('top_ad_one',$this->top_ad_one,true);
		$criteria->compare('top_ad_two',$this->top_ad_two,true);
		$criteria->compare('top_ad_three',$this->top_ad_three,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('last_time',$this->last_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	/**
	 * 获取资源图片
	 * @return string
	 */
	public function getImgUrl()
	{
		if (strstr($this->top_ad_img, 'v30')) {
			$res = new PublicFunV23();
			return $res->setAbleUploadImg($this->top_ad_img);
		} else {
			return Yii::app()->ajaxUploadImage->getUrl($this->top_ad_img);
		}
	}

	public function getResourceName()
	{
		if ($this->resource_id) {
			$resource = HomeConfigResource::model()->findByPk($this->resource_id);
			if ($resource && $resource->name) {
				return trim($resource->name);
			} else {
				return '不存在';
			}
		} else {
			return '不存在';
		}
	}



	public function getImgOne($param , $type)
	{
		if($this->$param)
		{
			$data = json_decode($this->$param , true);
			if(!empty($data) && isset($data[$type]))
			{
				return $data[$type];
			}else{
				return '默认值';
			}
		}else{
			return '默认值';
		}
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
			$this->top_ad_img = Yii::app()->ajaxUploadImage->moveSave($this->imgFile, $this->top_ad_img);
		}

		if ($this->isNewRecord) {
			$this->state = '1';

			if ($this->resource_id) {
				$resource = HomeConfigResource::model()->findByPk($this->resource_id);
				if (!$resource || !$resource->name) {
					$this->resource_id = 0;
				}
			} else {
				$this->resource_id = 0;
			}
		}

		return parent::beforeSave();
	}

	public function getCommunityTreeData()
	{
		//默认去全国所有小区，如需要做现在则改变branch_id的值可改变范围
		$branch_id = 1;
		$branch = Branch::model()->findByPk($branch_id);
		return $branch->getRegionCommunityRelation($this->id, 'HomeConfigTopAd', false);
	}

}
