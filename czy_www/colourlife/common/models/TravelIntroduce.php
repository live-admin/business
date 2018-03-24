<?php

/**
 * This is the model class for table "travel_introduce".
 *
 * The followings are the available columns in table 'travel_introduce':
 * @property integer $id
 * @property string $travel_title
 * @property string $travel_intro
 * @property string $travel_img
 * @property string $travel_introduce
 * @property integer $travel_like
 * @property integer $create_time
 * @property integer $update_time
 */
class TravelIntroduce extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'travel_introduce';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('travel_img, travel_introduce', 'required'),
			array('travel_like, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('travel_title, travel_intro', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, travel_title, travel_intro, travel_img, travel_introduce, travel_like, create_time, update_time', 'safe', 'on'=>'search'),
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
			'id' => '主键ID',
			'travel_title' => '旅游攻略标题',
			'travel_intro' => '简介',
			'travel_img' => '图片',
			'travel_introduce' => '详细介绍',
			'travel_like' => '点赞总数',
			'create_time' => '创建时间',
			'update_time' => '更新点赞时间',
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
		$criteria->compare('travel_title',$this->travel_title,true);
		$criteria->compare('travel_intro',$this->travel_intro,true);
		$criteria->compare('travel_img',$this->travel_img,true);
		$criteria->compare('travel_introduce',$this->travel_introduce,true);
		$criteria->compare('travel_like',$this->travel_like);
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
	 * @return TravelIntroduce the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
