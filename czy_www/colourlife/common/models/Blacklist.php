<?php

/**
 * This is the model class for table "blacklist".
 *
 * The followings are the available columns in table 'blacklist':
 * @property integer $id
 * @property integer $mobile
 * @property string $address
 * @property string $code
 * @property string $user_agent
 * @property integer $is_delete
 * @property integer $create_time
 */
class Blacklist extends CActiveRecord
{
	public $modelName ="黑名单";
	public $startTime ;
	public $endTime;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'blacklist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('mobile, address, code_num, user_agent, create_time', 'required'),
				array('is_deleted, code_num ,create_time', 'numerical', 'integerOnly'=>true),
				array('address, code_num', 'length', 'max'=>20),
				array('user_agent', 'length', 'max'=>200),
				array('mobile', 'common.components.validators.ChinaMobileValidator'),
				array('address','checkIp'),
				array('mobile','checkUniqueMobile'),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('id, mobile, address, code_num, user_agent, is_deleted, create_time', 'safe', 'on'=>'search'),
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
				'mobile' => '手机号码',
				'address' => 'IP地址',
				'code_num' => '验证码次数',
				'user_agent' => '设备号',
				'is_deleted' => 'Is Delete',
				'create_time' => '创建时间',
				'startTime' =>"开始时间",
				'endTime' =>"结束时间",
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
		$criteria->compare('address',$this->address,true);
		$criteria->compare('code_num',$this->code_num,true);
		$criteria->compare('user_agent',$this->user_agent,true);
		$criteria->compare('is_deleted',$this->is_deleted);
	    if ($this->startTime != '') {
            $criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
        }

        if ($this->endTime != '') {
            $criteria->compare("`t`.create_time", "< " . strtotime($this->endTime . " 23:59:59"));
        }
		

		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Blacklist the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function behaviors()
	{
		return array(
				'CTimestampBehavior' => array(
						'class' => 'zii.behaviors.CTimestampBehavior',
						'createAttribute' => 'create_time',
						'updateAttribute' => null,
						'setUpdateOnCreate' => true,
				),
				'IsDeletedBehavior' => array(
						'class' => 'common.components.behaviors.IsDeletedBehavior',
				),
		);
	}
	
	public function checkIp($attribute, $params){
		if(!filter_var($this->address,FILTER_VALIDATE_IP)){
			$this->addError($attribute, "IP地址不正确");
		}
	}
	
	public function checkUniqueMobile($attribute, $params){
		$data = self::model()->find("mobile=:mobile",array(":mobile" => $this->mobile));
		if(!empty($data)){
			$this->addError($attribute, "手机号码".$this->mobile."已使用");
		}
	}
	
	
}
