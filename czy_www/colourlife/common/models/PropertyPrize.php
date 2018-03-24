<?php

/**
 * This is the model class for table "property_prize".
 *
 * The followings are the available columns in table 'property_prize':
 * @property string $id
 * @property string $name
 * @property integer $level_id
 * @property integer $category_id
 * @property integer $status
 * @property integer $number
 * @property integer $goods_id
 * @property string $img_url
 */
class PropertyPrize extends CActiveRecord
{
	public $logoFile;

	public $status_arr = array(
		"3" => "正常",
		"1" => "超出数量",
		"2" => "禁用",
	);
	public $level_arr = array(
		"1" => "一等奖",
		"2" => "二等奖",
		"3" => "三等奖",
		"4" => "四等奖",
		"5" => "五等奖",
		"6" => "六等奖",
	);

	public  $category_arr = array(
		'1'=>'京东商品',
		'2'=>'饭票',
		'3'=>'彩食惠商品',
		'4'=>'E维修优惠券',
		'5'=>'彩特供商品',
		'6'=>'彩食惠优惠券',
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'property_prize';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, level_id, category_id', 'required'),
			array('level_id, category_id, status, number, goods_id', 'numerical', 'integerOnly'=>true),
			array('name, img_url', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, level_id, category_id, status, number, goods_id, img_url, logoFile', 'safe', 'on'=>'search'),
			array('id, name, level_id, category_id, status, number, goods_id, img_url, logoFile', 'safe', 'on'=>'create,update'),
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
			'name' => '奖品名称',
			'level_id' => '奖品等级',
			'category_id' => '奖品类别',
			'status' => '状态',
			'number' => '商品领取数量',
			'goods_id' => '商品（券）编号',
			'img_url' => '图片',
			'logoFile' => '图片'
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('level_id',$this->level_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('number',$this->number);
		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('img_url',$this->img_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PropertyPrize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//获取状态名称
	public function getStatus(){
		if(!empty($this->status)){
			return $this->status_arr[$this->status];
		}
	}

	//获取奖品等级名称
	public function getLevel(){
		if(!empty($this->level_id)){
			return $this->level_arr[$this->level_id];
		}
	}

	//获取类别
	public function getCategory(){
		if(!empty($this->category_id)){
			return $this->category_arr[$this->category_id];
		}
	}

	/**
	 * 处理图片
	 * @return bool
	 */
	protected function beforeSave()
	{
		if (!empty($this->logoFile)) {
			$this->img_url = Yii::app()->ajaxUploadImage->moveSave($this->logoFile, $this->img_url);
		}
		return parent::beforeSave();
	}

	/**
	 * 获取资源图片
	 * @return string
	 */
	public function getImgUrl()
	{
		if (strstr($this->img_url, 'v30')) {
			$res = new PublicFunV23();
			return $res->setAbleUploadImg($this->img_url);
		} else {
			//return Yii::app()->ajaxUploadImage->getUrl($this->img_url);
			return F::getUploadsUrl('/images/' . $this->img_url);
		}
	}


}
