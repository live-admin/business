<?php

/**
 * This is the model class for table "bao_user_yaoshi".
 *
 * The followings are the available columns in table 'bao_user_yaoshi':
 * @property integer $id
 * @property integer $type
 * @property string $mobile
 * @property integer $yaoshi_id
 * @property integer $is_use
 * @property integer $create_time
 */
class BaoUserYaoshi extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bao_user_yaoshi';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, yaoshi_id, is_use, create_time', 'numerical', 'integerOnly'=>true),
			array('mobile', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, mobile, yaoshi_id, is_use, create_time', 'safe', 'on'=>'search'),
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
			'type' => '获取类型(0:抽取宝石;1:摇一摇)',
			'mobile' => '用户手机号码',
			'yaoshi_id' => '宝石id',
			'is_use' => '是否使用(0:未使用;1:已使用)',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('yaoshi_id',$this->yaoshi_id);
		$criteria->compare('is_use',$this->is_use);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BaoUserYaoshi the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
