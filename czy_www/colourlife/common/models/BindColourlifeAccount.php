<?php

/**
 * This is the model class for table "bind_colourlife_account".
 *
 * The followings are the available columns in table 'bind_colourlife_account':
 * @property integer $id
 * @property string $other_account
 * @property string $mobile
 * @property integer $type
 * @property integer $state
 * @property integer $create_time
 */
class BindColourlifeAccount extends CActiveRecord
{
	public $token;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'bind_colourlife_account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('other_account, mobile','required'),
			array('type, state, create_time', 'numerical', 'integerOnly'=>true),
			array('other_account', 'length', 'max'=>255),
			array('mobile,token', 'length', 'max'=>25),
			array('mobile', 'common.components.validators.ChinaMobileValidator'),
			array('token','required','on'=>'BindCustomer'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, other_account, mobile, type, state, create_time', 'safe', 'on'=>'search'),
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
			'other_account' => '要与彩之云绑定的账号',
			'mobile' => '彩之云账号',
			'type' => '绑定账号的类型（0未知，1微信）',
			'state' => '是否禁用（0启用，1禁用）',
			'create_time' => '添加时间',
			'token' => ''
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
		$criteria->compare('other_account',$this->other_account,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('state',$this->state);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return BindColourlifeAccount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//验证绑定账号
	public function checkOtherAccount(){
		$bindModel = self::find('other_account=:otherAccount and state=:state',array(':otherAccount' => $this->other_account, ':state' => 0));
		if (isset($bindModel)){
			return true;
		}
		return false;
	}
	//判断是否已注册
	public function checkIsRegister(){
		$model = Customer::model()->find('mobile=:cmobile and is_deleted=:ise_deleted', array(':cmobile' => $this->mobile, ':ise_deleted' => Item::DELETE_ON));
		if (isset($model)) {
			return true;
		}
		return false;
	}
	
	/**
	 * 是否已被绑定
	 * @return boolean
	 */
	public function checkIsBinded(){
		$bindModel = self::find('mobile=:cmobile and state=:state',array(':cmobile' => $this->mobile, ':state' => 0));
		if (isset($bindModel)){
			return true;
		}
		return false;
	}
	
	//新的保存用户信息
	public function saveData()
	{
	
		//check token
		$sms = Yii::app()->sms;
		$sms->setType('verifyToken', array('mobile' => $this->mobile, 'token' => $this->token));
		if (!$sms->getTokenIsCorrect())
			return array(
				'status' => -1,
				'msg' => $sms->error
			);
	
		$sms->useToken();
	
		$isOtherAccount = $this->checkOtherAccount();
		if ($isOtherAccount){
			return array(
					'status' => -2,
					'msg' => '不能重新绑定'
			);
		}
		$isRegister = $this->checkIsRegister();
		if (!$isRegister){
			return array(
					'status' => -2,
					'msg' => '请先注册彩之云账号'
			);
		}
		
		$isBind = $this->checkIsBinded();
		if ($isBind){
			return array(
					'status' => -3,
					'msg' => '该账号已被使用'
			);
		}
	
		$model = new self();
		$model->other_account = $this->other_account;
		$model->mobile = $this->mobile;
		$model->type = $this->type;
		$model->state = 0;
		$model->create_time = time();
		if ($model->save()){
			return array(
					'status' => 1,
					'msg' => '绑定成功'
			);
		}else {
			dump($model->getErrors());
			return array(
					'status' => 0,
					'msg' => '绑定失败'
			);
		}
	}
}
