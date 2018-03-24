<?php

/**
 * This is the model class for table "house_url".
 *
 * The followings are the available columns in table 'house_url':
 * @property integer $id
 * @property string $web_url
 * @property string $app_url
 * @property integer $order_seq
 * @property integer $top
 * @property integer $isdelete
 */
class HouseUrl extends CActiveRecord
{
	public $modelName = '房屋链接';
	public $pictureFile;
	public $app_pictureFile;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'house_url';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title,web_url, app_url', 'required'),
			array('order_seq, top, isdelete', 'numerical', 'integerOnly'=>true),
			array('web_url, app_url', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, web_url, app_url, order_seq, top, isdelete', 'safe', 'on'=>'search'),
			array('pictureFile,picture,app_picture,app_pictureFile,description,pub_date', 'safe', 'on' => 'create, update'),
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
			'id' => 'ID',
			'title'=>'标题',
			'description'=>'描述',
			'picture'=>'web图片',
			'app_picture'=>'app图片',
			'pictureFile'=>'web图片文件',
			'app_pictureFile'=>'app图片文件',
			'pub_date'=>'发布时间',
			'web_url' => '网站链接',
			'app_url' => 'app链接',
			'order_seq' => '排序号',
			'top' => '是否置顶',
			'isdelete' => '是否删除',
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
		$criteria->compare('web_url',$this->web_url,true);
		$criteria->compare('app_url',$this->app_url,true);
		$criteria->compare('order_seq',$this->order_seq);
		$criteria->compare('top',$this->top);
		$criteria->compare('isdelete',0);
		$criteria->order="top DESC,order_seq ASC,pub_date DESC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HouseUrl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	protected function beforeValidate()
	{
		if (empty($this->picture) && !empty($this->pictureFile)){
			$this->picture = '';
		}
		if (empty($this->app_picture) && !empty($this->app_pictureFile)){
			$this->app_picture = '';
		}
		return parent::beforeValidate();
	}
	
	/**
	 * 处理图片
	 * @return bool
	 */
	protected function beforeSave()
	{
		// 		var_dump($this->prize_pictureFile);
		// 		exit();
		if (!empty($this->pictureFile)) {
			$this->picture = Yii::app()->ajaxUploadImage->moveSave($this->pictureFile, $this->picture);
		}
		if (!empty($this->app_pictureFile)) {
			$this->app_picture = Yii::app()->ajaxUploadImage->moveSave($this->app_pictureFile, $this->app_picture);
		}
		return parent::beforeSave();
	}
	
	public function getPictureUrl()
	{
		return Yii::app()->ajaxUploadImage->getUrl($this->picture, '/common/images/nopic-map.jpg');
	}
	public function getAppPictureUrl()
	{
		return Yii::app()->ajaxUploadImage->getUrl($this->app_picture, '/common/images/nopic-map.jpg');
	}
	
	/**
	 * 截取字符并hover显示全部字符串
	 * $str string 截取的字符串
	 * $len int 截取的长度
	 */
	public function getFullStringLink($str, $len)
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => $str,'target'=>'_blank', 'data-original-title' => $str), F::sub($str, $len, $show = true, $f = '……'));
	}
	
	/**
	 * 截取字符并hover显示全部字符串
	 * $str string 截取的字符串
	 * $len int 截取的长度
	 */
	public function getFullString($str, $len)
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' => $str), F::sub($str, $len, $show = true, $f = '……'));
	}
	
	public function delete(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->isdelete=1;
		$model->save();
	}
	
	public function top(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->top=1;
		$model->save();
	}
	
	public function canCleTop(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->top=0;
		$model->save();
	}
	
	
}
