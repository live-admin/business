<?php

/**
 * This is the model class for table "niu_user_grow".
 *
 * The followings are the available columns in table 'niu_user_grow':
 * @property integer $id
 * @property string $mobile
 * @property integer $value
 * @property integer $type
 * @property integer $create_time
 */
class NiuUserGrow extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'niu_user_grow';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('value, type, create_time', 'numerical', 'integerOnly'=>true),
			array('mobile', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mobile, value, type, create_time', 'safe', 'on'=>'search'),
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
			'id' => '主键id',
			'mobile' => '用户手机号码',
			'value' => '获取成长值',
			'type' => '来源类型(1:彩富人生用户;2:自己挤牛奶;3:分享;4:好友挤牛奶;5:邀请注册;6:环球精选下单)',
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
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('value',$this->value);
		$criteria->compare('type',$this->type);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NiuUserGrow the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
