<?php

/**
 * This is the model class for table "platform_shop_category".
 *
 * The followings are the available columns in table 'platform_shop_category':
 * @property integer $id
 * @property integer $type
 * @property string $title
 * @property string $picture
 * @property string $description
 * @property integer $seq
 * @property integer $isdelete
 */
class PlatformShopCategory extends CActiveRecord
{
	
	public $modelName = '平台商家分类';
	public $pictureFile;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'platform_shop_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('type, title, no', 'required', 'on'=>'create,update'),
			array('type, seq, isdelete', 'numerical', 'integerOnly'=>true),
			array('title, picture', 'length', 'max'=>200),
            array('no', 'unique'),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, no,title,content,seq, isdelete', 'safe', 'on'=>'search'),
			array('type, title, no,content,picture,pictureFile, description, seq, isdelete', 'safe', 'on'=>'create,update'),
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
			'type' => '类型',  //0广告1列表
            'no' => '唯一标示码',
			'title' => '标题',
            'content' => '简介',
			'picture' => '图片',
			'pictureFile' => '图片文件',
			'description' => '描述',
			'seq' => '排序号',
			'isdelete' => '删除',
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
        $criteria->compare('no',$this->no);
		$criteria->compare('type',$this->type);
		$criteria->compare('title',$this->title,true);
        $criteria->compare('content',$this->title,true);
		//$criteria->compare('picture',$this->picture,true);
		//$criteria->compare('description',$this->description,true);
		$criteria->compare('seq',$this->seq);
		//$criteria->compare('isdelete',$this->isdelete);
		$criteria->compare('isdelete',0);
		
		$criteria->order="seq ASC";

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PlatformShopCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
	
	protected function beforeValidate()
	{
		if (empty($this->picture) && !empty($this->pictureFile)){
			$this->picture = '';
		}
		return parent::beforeValidate();
	}
	
	/**
	 * 处理图片
	 * @return bool
	 */
	protected function beforeSave()
	{
		if (!empty($this->pictureFile)) {
			$this->picture = Yii::app()->ajaxUploadImage->moveSave($this->pictureFile, $this->picture);
		}
		return parent::beforeSave();
	}
	
	public function getPictureUrl()
	{
		return Yii::app()->ajaxUploadImage->getUrl($this->picture, '/common/images/nopic-map.jpg');
	}
	
	public function delete(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->isdelete=1;
		$model->save();
	}
}
