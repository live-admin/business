<?php

/**
 * This is the model class for table "czz_certificate".
 *
 * The followings are the available columns in table 'czz_certificate':
 * @property integer $id
 * @property string $owner
 * @property string $id_number
 * @property string $mobile
 * @property string $address
 * @property string $huxing
 * @property string $house_pic_url
 * @property string $thumb_pic_url
 * @property string $total_area
 * @property string $use_area
 * @property string $total_price
 * @property string $useful
 * @property string $effective_age
 * @property string $total_tickets
 * @property string $month_tickets
 * @property string $ad_pic_url
 * @property string $ad_thumb_pic_url
 * @property string $account
 * @property string $is_buy
 * @property integer $addtime
 * @property integer $updatetime
 */
class CzzCertificate extends CActiveRecord
{
	public $temp_pic_url;
	public $temp_pic_path;
	public $ad_temp_pic_url;
	public $ad_temp_pic_path;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'czz_certificate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('owner, id_number, mobile', 'required'),
				array('id, effective_age, addtime, updatetime', 'numerical', 'integerOnly'=>true),
				array('owner', 'length', 'max'=>64),
				array('id_number, total_area, use_area, total_price, useful, total_tickets, month_tickets', 'length', 'max'=>100),
				array('mobile, huxing', 'length', 'max'=>50),
				array('address', 'length', 'max'=>512),
				array('house_pic_url, thumb_pic_url, ad_pic_url, ad_thumb_pic_url, account', 'length', 'max'=>500),
				array('is_buy', 'length', 'max'=>1),
				array('temp_pic_url, temp_pic_path,ad_temp_pic_url,ad_temp_pic_path','safe'),				
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('id, owner, id_number, mobile, address, huxing, house_pic_url, thumb_pic_url, total_area, use_area, total_price, useful, effective_age, total_tickets, month_tickets, ad_pic_url, ad_thumb_pic_url, account, is_buy, addtime, updatetime', 'safe', 'on'=>'search'),
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
			'owner' => '房主姓名',
			'id_number' => '身份证号',
			'mobile' => '手机号',
			'address' => '住房地址',
			'huxing' => '户型',
			'house_pic_url' => '住房图片',
			'thumb_pic_url' => '住房缩略图',
			'total_area' => '建筑面积',
			'use_area' => '使用面积',
			'total_price' => '总价',
			'useful' => '用途',
			'effective_age' => '产权年限',
			'total_tickets' => '总饭票',
			'month_tickets' => '月饭票',
			'ad_pic_url' => '广告图',
			'ad_thumb_pic_url' => '广告缩略图',
			'account' => '绑定彩之云账号',
			'is_buy' => '是否购买（Y为已购买，N为未购买）',
			'addtime' => '添加时间',
			'updatetime' => '更新时间',
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
		$criteria->compare('owner',$this->owner,true);
		$criteria->compare('id_number',$this->id_number,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('huxing',$this->huxing,true);
		$criteria->compare('house_pic_url',$this->house_pic_url,true);
		$criteria->compare('thumb_pic_url',$this->thumb_pic_url,true);
		$criteria->compare('total_area',$this->total_area);
		$criteria->compare('use_area',$this->use_area);
		$criteria->compare('total_price',$this->total_price);
		$criteria->compare('useful',$this->useful,true);
		$criteria->compare('effective_age',$this->effective_age);
		$criteria->compare('total_tickets',$this->total_tickets);
		$criteria->compare('month_tickets',$this->month_tickets);
		$criteria->compare('ad_pic_url',$this->ad_pic_url,true);
		$criteria->compare('ad_thumb_pic_url',$this->ad_thumb_pic_url,true);
		$criteria->compare('account',$this->account,true);
		$criteria->compare('is_buy',$this->is_buy,true);
		$criteria->compare('addtime',$this->addtime);
		$criteria->compare('updatetime',$this->updatetime);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CzzCertificate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getHousePicUrl()
	{
		return Yii::app()->imageFile->getUrl($this->house_pic_url, '/common/images/nopic-map.jpg');
	}
	public function getAdPicUrl()
	{
		return Yii::app()->imageFile->getUrl($this->ad_pic_url, '/common/images/nopic-map.jpg');
	}
	
	public function getThumbPicUrl()
	{
		return Yii::app()->imageFile->getUrl($this->thumb_pic_url, '/common/images/nopic-map.jpg');
	}
	public function getAdThumbPicUrl()
	{
		return Yii::app()->imageFile->getUrl($this->ad_thumb_pic_url, '/common/images/nopic-map.jpg');
	}
	
	/**
	 * @return bool
	 */
	protected function beforeSave()
	{
		if (!empty($this->temp_pic_path)) {
			$this->house_pic_url = Yii::app()->ajaxUploadImage->moveSave($this->temp_pic_path, $this->house_pic_url);
			$picPath = Yii::app()->imageFile->getFilename($this->house_pic_url);
			$thumbPicUrl = Yii::app()->imageFile->getNewName($this->house_pic_url);
			$thumbPicPath = Yii::app()->imageFile->getFilename($thumbPicUrl);
	
			$this->thumb_pic_url = $thumbPicUrl;
/* 	
			//剪切到缩略图
			$conversion = new ImageConversion($picPath);
			$conversion->conversion($thumbPicPath, array(
					'w' => 0,   // 结果图的宽
					'h' => 0,   // 结果图的高
					't' => 'resize ,clip', // 转换类型
			));
	
			//剪切大图
			$conversion = new ImageConversion($picPath);
			$conversion->conversion($picPath, array(
					'w' => 0,   // 结果图的宽
					'h' => 0,   // 结果图的高
					't' => 'resize ,clip', // 转换类型
			)); */
		}
		if (!empty($this->ad_temp_pic_path)) {
			$this->ad_pic_url = Yii::app()->ajaxUploadImage->moveSave($this->ad_temp_pic_path, $this->ad_pic_url);
			$ad_picPath = Yii::app()->imageFile->getFilename($this->ad_pic_url);
			$ad_thumbPicUrl = Yii::app()->imageFile->getNewName($this->ad_pic_url);
			$ad_thumbPicPath = Yii::app()->imageFile->getFilename($ad_thumbPicUrl);
		
			$this->ad_thumb_pic_url = $ad_thumbPicUrl;
/* 		
			//剪切到缩略图
			$conversion = new ImageConversion($ad_picPath);
			$conversion->conversion($ad_thumbPicPath, array(
					'w' => 0,   // 结果图的宽
					'h' => 0,   // 结果图的高
					't' => 'resize ,clip', // 转换类型
			));
		
			//剪切大图
			$conversion = new ImageConversion($ad_picPath);
			$conversion->conversion($ad_picPath, array(
					'w' => 0,   // 结果图的宽
					'h' => 0,   // 结果图的高
					't' => 'resize ,clip', // 转换类型
			)); */
		}
	
		return parent::beforeSave();
	}
	
}
