<?php

/**
 * This is the model class for table "reply".
 *
 * The followings are the available columns in table 'reply':
 * @property integer $id
 * @property integer $ping_lun_id
 * @property integer $user_id
 * @property integer $to_reply_id
 * @property integer $to_user_id
 * @property string $content
 * @property integer $create_time
 */
class Reply extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'reply';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('ping_lun_id, user_id, to_reply_id, to_user_id, create_time', 'numerical', 'integerOnly'=>true),
			array('content', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ping_lun_id, user_id, to_reply_id, to_user_id, content, create_time', 'safe', 'on'=>'search'),
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
			'id' => '主键',
			'ping_lun_id' => '评论id',
			'user_id' => '回复人ID',
			'to_reply_id' => '被回复的id',
			'to_user_id' => '被回复人的id',
			'content' => '回复内容',
			'create_time' => '创建时间',
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
		$criteria->compare('ping_lun_id',$this->ping_lun_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('to_reply_id',$this->to_reply_id);
		$criteria->compare('to_user_id',$this->to_user_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Reply the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
