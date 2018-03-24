<?php

/**
 * This is the model class for table "expire_remind_log".
 *
 * The followings are the available columns in table 'expire_remind_log':
 * @property integer $id
 * @property integer $object_id
 * @property string $content
 * @property string $type
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 */
class ExpireRemindLog extends CActiveRecord
{
	public $modelName = '订单到期提醒记录';
	public $type_arr = array (   //c_1业主推送，c_2业主短信,m_1客户经理推送，m_2客户经理短信
			'c_1' => '业主推送',
			'c_2' => '业主短信',
			'm_1' => '客户经理推送',
			'm_2' => '客户经理短信',
	);
	public $status_arr=array(0=>'未知',1=>'成功',2=>'失败');//状态
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'expire_remind_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content', 'required'),
			array('object_id, customer_id, status, recover_time, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>64),
			array('type', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, object_id, customer_id, title, content, type, status, recover_time, create_time, update_time', 'safe', 'on'=>'search'),
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
				'object_id' => '提醒表ID',
				'customer_id' => '用户',
				'title' => '标题',
				'content' => '发送内容',
				'type' => '类型',
				'status' => '状态',
				'recover_time' => '过期时间',
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
		//dump($this->recover_time);
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('status',$this->status);
		if ($this->recover_time != "") {
			$criteria->addCondition('recover_time>=' . strtotime($this->recover_time." 00:00:00"));
			$criteria->addCondition('recover_time<=' . strtotime($this->recover_time." 23:59:59"));
		}
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
		$criteria->order='id DESC'; //设置排序

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>100,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ExpireRemindLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	/**
	 * 获取类型
	 */
	public function getType()
	{
		return $this->type_arr[$this->type];
	}
	/**
	 * 获取启用名称
	 * @param string $state
	 * @return string
	 */
	public function getStatusName($status = 0,$recover_time=0)
	{
		$return = '';
		//判断是否已过期;
		if (!empty($recover_time)&&time()>$recover_time){
			$return = '<span class="label label-error">已过期</span>';
		}else {
			switch ($status) {
				case 0:
					$return = '<span class="label label-error">'.$this->status_arr[0].'</span>';
					break;
				case 2:
					$return = '<span class="label label-error">'.$this->status_arr[2].'</span>';
					break;
				case 1:
					$return = '<span class="label label-success">'.$this->status_arr[1].'</span>';
					break;
			}
		}
		return $return;
	}
	/*
	 * @version 补发功能
	*/
	public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
		if (!empty($model)&&time()<$model->recover_time){
			//发给指定用户
			/* $cusArr = array (
					1880959,
					2222309,
					2224384,
					2222319
			); */
			//if (in_array($model->customer_id, $cusArr)){
				$result=false;
				if ($model->type=='c_1'||$model->type=='m_1'){ //推送
					$result=$this->sendPushMessage($model->customer_id, $model->title, $model->content);
				}elseif ($model->type=='c_2'||$model->type=='m_2'){  //短信
					$result=$this->sendSmsMessage($model->customer_id, $model->content);
				}
				if ($result){
					$this->updateByPk($model->id, array('status'=>1,'update_time'=>time()));
				}
			//}
		}
	}
	
	/**
	 * 发送推送消息
	 * @param unknown $customer_id
	 * @return boolean
	 */
	public function sendPushMessage($customer_id,$title,$message){
		if (empty($customer_id)||empty($title)||empty($message)){
			return false;
		}
		$customer=Customer::model()->findByPk($customer_id);
		if (empty($customer)||$customer->state!=0||$customer->is_deleted!=0){
			return false;
		}
		//推送消息
		$pushModel=PushInformation::createSNSInformations($title,$message,$customer->mobile,$customer_id, PushClient::IS_TYPE_CUSTOMER);
		if ($pushModel) {
			Yii::log("给号码'{$customer->mobile}' 客户端消息推送：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.ExpireOrderRemindTuiSong');
			return true;
		} else {
			Yii::log("给号码'{$customer->mobile}' 客户端消息推送：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.ExpireOrderRemindTuiSong');
			return false;
		}
	}
	
	/**
	 * 发送短信消息
	 * @param unknown $mobile
	 * @param unknown $msg
	 * @return boolean
	 */
	public function sendSmsMessage($customer_id,$msg){
		if (empty($customer_id)||empty($msg)){
			return false;
		}
		$customer=Customer::model()->findByPk($customer_id);
		if (empty($customer)||$customer->state!=0||$customer->is_deleted!=0){
			return false;
		}
		//发短信通知,调用ICE短息接口
		Yii::import('common.api.IceApi');
        $colour = IceApi::getInstance();
        $result=$colour->sendSms($customer->mobile,$msg);
		if (!empty($result)) {
			Yii::log ( "短信发送成功[{$customer->mobile}]", CLogger::LEVEL_INFO, 'colourlife.core.ExpireOrderRemindSms' );
			return true;
		} else {
			Yii::log ( "短信发送失败[{$customer->mobile}:{$sms->error}]", CLogger::LEVEL_ERROR, 'colourlife.core.ExpireOrderRemindSms' );
			return false;
		}
	}
	
	/**
	 * 获取用户名
	 * @param unknown $customer_id
	 * @return string
	 */
	public function getCustomerName($customer_id){
		$userName='访客';
		if (empty($customer_id)){
			return $userName;
		}
		$customer=Customer::model()->findByPk($customer_id);
		if (!empty($customer)){
			$userName=$customer->name;
		}
		return $userName;
	}
	
	/**
	 * 判断是否已过期
	 * @param number $recover_time
	 * @return boolean
	 */
	public function getIsExpired($recover_time=0){
		if (!empty($recover_time)&&time()>$recover_time){
			return false;
		}else{
			return true;
		}
	}
	
	/**
	 * 添加数据
	 */
	public function addLog($object_id,$customer_id,$type,$status,$recover_time,$title='',$content=''){
		if (empty($object_id)||empty($customer_id)||empty($type)||empty($recover_time)){
			return false;
		}
		$expireRemindLog=new ExpireRemindLog();
		$expireRemindLog->object_id=$object_id;
		$expireRemindLog->customer_id=$customer_id;
		if (!empty($title)){
			$expireRemindLog->title=$title;
		}
		$expireRemindLog->content=$content;
		$expireRemindLog->type=$type;
		$expireRemindLog->status=$status;
		$expireRemindLog->recover_time=$recover_time;
		$expireRemindLog->create_time=time();
		$result=$expireRemindLog->save();
		return $result;
	}
}
