<?php

/**
 * This is the model class for table "user_info".
 *
 * The followings are the available columns in table 'user_info':
 * @property integer $id
 * @property string $oAAccount
 * @property string $realName
 * @property string $dept
 * @property string $simple_build
 * @property string $mobile
 * @property integer $create_time
 * @property integer $state
 * @property integer $isPrize
 * @property integer $isPartake
 * @property string $lucky_configid
 * @property integer $currentIndex
 */
class UserInfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dept, simple_build', 'required'),
			array('create_time, state, isPrize, isPartake, currentIndex', 'numerical', 'integerOnly'=>true),
			array('oAAccount', 'length', 'max'=>50),
			array('realName', 'length', 'max'=>20),
			array('mobile', 'length', 'max'=>15),
			array('lucky_configid', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, oAAccount, realName, dept, simple_build, mobile, create_time, state, isPrize, isPartake, lucky_configid, currentIndex', 'safe', 'on'=>'search'),
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
			'id' => 'id',
			'oAAccount' => 'OA账号',
			'realName' => '真实名称',
			'dept' => '部门',
			'simple_build' => '部门简介',
			'mobile' => '手机号',
			'create_time' => '创建时间',
			'state' => '状态（默认0）',
			'isPrize' => '是否中奖',
			'isPartake' => '是否参与抽奖',
			'lucky_configid' => 'Lucky Configid',
			'currentIndex' => 'Current Index',
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
		$criteria->compare('oAAccount',$this->oAAccount,true);
		$criteria->compare('realName',$this->realName,true);
		$criteria->compare('dept',$this->dept,true);
		$criteria->compare('simple_build',$this->simple_build,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('state',$this->state);
		$criteria->compare('isPrize',$this->isPrize);
		$criteria->compare('isPartake',$this->isPartake);
		$criteria->compare('lucky_configid',$this->lucky_configid,true);
		$criteria->compare('currentIndex',$this->currentIndex);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
