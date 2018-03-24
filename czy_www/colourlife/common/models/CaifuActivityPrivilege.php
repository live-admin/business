<?php

/**
 * This is the model class for table "caifu_activity_privilege".
 *
 * The followings are the available columns in table 'caifu_activity_privilege':
 * @property integer $id
 * @property integer $object_id
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $state
 * @property integer $create_time
 * @property integer $update_time
 */
class CaifuActivityPrivilege extends CActiveRecord
{
	public $modelName = '彩富活动特权';
	public $state_arr=array(0=>'已启用',1=>'已禁用');//是否禁用（0启用，1禁用）
	public $type_arr = array (   //类型
			0 => '特权介绍',
			1 => '权益详情',
	);
	public $imgFile;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'caifu_activity_privilege';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content', 'required'),
			array('object_id, type, state, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('title, img', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
				array('object_id, title, content, img, imgFile,type, state, create_time, update_time', 'safe', 'on' => 'update,create'),
			array('id, object_id, title, content, img, type, state, create_time, update_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '表ID',
			'object_id' => '首页配置的活动',
			'title' => '标题',
			'content' => '内容',
			'img' => '图片',
			'imgFile' => '图片',
			'type' => '类型',
			'state' => '是否禁用',
			'create_time' => '添加时间',
			'update_time' => '更新时间',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('state',$this->state);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CaifuActivityPrivilege the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
		return parent::beforeSave();
	}
	
	/**
	 * 获取启用名称
	 * @param string $state
	 * @return string
	 */
	public function getStateName($state = '')
	{
		$return = '';
		switch ($state) {
			case '':
				$return = "";
				break;
			case 0:
				$return = '<span class="label label-success">'.$this->state_arr[0].'</span>';
				break;
			case 1:
				$return = '<span class="label label-error">'.$this->state_arr[1].'</span>';
				break;
		}
		return $return;
	}
	
	/**
	 * 获取类型
	 */
	public function getType()
	{
		return $this->type_arr[$this->type];
	}
	
	/*
	 * @version 启用功能
	*/
	public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=0;
		$model->update_time=time();
		$model->save();
	}
	/*
	 * @version 禁用功能
	*/
	public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=1;
		$model->update_time=time();
		$model->save();
	}
	
	/**
	 * 获取在线的活动
	 * @return multitype:NULL
	 */
	public function getHomeConfigAds(){
		$data[]='----请选择----';
		$criteria=new CDbCriteria;
		$criteria->select='id,title';  // only select the 'title' column
		$criteria->condition='state=0';
		$criteria->order='id desc';
		$configAds=HomeConfigAd::model()->findAll($criteria);
		if (!empty($configAds)){
			foreach ($configAds as $val){
				$data[$val->id]=$val->title;
			}
		}
		return $data;
	}
	
	/**
	 * 获取资源图片
	 * @return string
	 */
	public function getImgUrl($img='')
	{
		if (empty($img)){
			$img=$this->img;
		}
		if (strstr($this->img, 'v30')) {
			$res = new PublicFunV23();
			return $res->setAbleUploadImg($img);
		} else {
			return Yii::app()->ajaxUploadImage->getUrl($img);
		}
	}
	
	/**
	 * 获取启用的所有活动
	 */
	public function getActivityAds($objectID,$custID){
		$data = array (
				'img' => '',
				'url' => '' 
		);
		if (empty($objectID)||empty($custID)){
			return $data;
		}
		$homeConfigAd=HomeConfigAd::model()->findByPK($objectID);
		if (empty($homeConfigAd)){
			return $data;
		}
		$resource=HomeConfigResource::model()->getResourceByKeyOrId($homeConfigAd->resource_id,1,$custID);
		$data['url']=$resource->completeURL;
		$data['img']=$homeConfigAd->img;
		return $data;
	}
	
	/**
	 * 获取所有数据
	 */
	public function getAllDatas($custID){
		$data=array();
		if (empty($custID)){
			return $data;
		}
		$activity=CaifuActivityPrivilege::model()->findAll('state=0');
		if (!empty($activity)){
			foreach ($activity as $val){
				$tmp=array();
				$tmp['title']=$val->title;
				$tmp['content']=$val->content;
				
				if ($val->type==1){
					$activityAds=$this->getActivityAds($val->object_id, $custID);
					$tmp['img']=$this->getImgUrl($activityAds['img']);
					$tmp['url']=$activityAds['url'];
				}else {
					$tmp['img']=Yii::app()->ajaxUploadImage->getUrl($val->img);
				}
				$data[$val->type][]=$tmp;
			}
		}
		return $data;
	}
}
