<?php

/**
 * This is the model class for table "face_swiping_photo".
 *
 * The followings are the available columns in table 'face_swiping_photo':
 * @property integer $id
 * @property integer $customer_id
 * @property string $img
 * @property integer $create_time
 * @property integer $update_time
 */
class FaceSwipingPhoto extends CActiveRecord
{
	public $file;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'face_swiping_photo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('nickname', 'length', 'max'=>100),
			array('img', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, img, score, is_default, create_time, update_time', 'safe', 'on'=>'search'),
			array('file', 'file', 'types' => 'jpg, gif, png', 'safe' => true, 'on' => 'UploadPic'), // 增加图片文件
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
			'customer_id' => '用户ID',
			'nickname' => '用户昵称',
			'img' => '图片url',
			'score' => '总分数',
			'is_default' => '是否为默认头像（1是默认，0不是默认）',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('nickname',$this->nickname,true);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('score',$this->score);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('is_default',$this->is_default);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FaceSwipingPhoto the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getImgUrl()
	{
		if ($this->is_default == 1){
			return Yii::app()->imageFile->getUrl($this->img);
		}else{
			return Yii::app()->imageFile->getUrl('faceswiping/'.$this->img);
		}
	}
}
