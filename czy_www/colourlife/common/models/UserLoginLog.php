<?php

/**
 * This is the model class for table "user_login_log".
 *
 * The followings are the available columns in table 'user_login_log':
 * @property integer $id
 * @property string $mobile
 * @property string $version
 * @property string $user_agent
 * @property integer $create_time
 */
class UserLoginLog extends CActiveRecord
{
	
	public $startTime;
	public $endTime;
	
	public $modelList = array('web'=>'网站','android'=>'android','ios'=>'ios');
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_login_log';
	}
	
	public function getModelName(){
		return "业主登录记录";
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_time,mobile', 'required'),
			array('create_time', 'numerical', 'integerOnly'=>true),
			array('mobile,address', 'length', 'max'=>20),
			array('version, model,user_agent', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mobile, address,model,version, user_agent, create_time', 'safe', 'on'=>'search'),
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
			'mobile' => '手机号',
			'version' => '版本',
			'user_agent' => '用户UA',
			'create_time' => 'Create Time',
			'model' => '登录端',
			'modelNames'=> '登录端',
			'createTime' => '登录时间',
			'startTime' =>'开始时间',
			'endTime' =>'结束时间',
			'address' =>'登录IP',
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
		$criteria->compare('version',$this->version,true);
		$criteria->compare('user_agent',$this->user_agent,true);
		$criteria->compare('address', $this->address,true);
		$criteria->compare('model', $this->model,true);
		
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
	 * @return UserLoginLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getCreateTime(){
		return date("Y-m-d H:i:s",$this->create_time);
	}
	
	public function getModelNames(){
		$modelName =  empty($this->modelList[$this->model])?"":$this->modelList[$this->model];
		return $modelName;
	}
	
	/**
	 * 创建登录记录
	 * @param String $mobile 手机号码
	 * @param string $m 业主终端（web ,android,ios）
	 * @param String $version 使用的版本
	 * @return boolean
	 */
	public function createLoginLog($mobile,$m="web",$version = 0){
		$model  = new self;
		$model->mobile = $mobile;
		$model->user_agent = Yii::app()->request->userAgent;
		$model->address = Yii::app()->request->userHostAddress;
		$model->version = $version;
		$model->model = $m;
		$model->create_time = time();
		$model->validate();
		return ($model->save());
	}
}
