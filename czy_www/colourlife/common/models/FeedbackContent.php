<?php

/**
 * This is the model class for table "feedback_content".
 *
 * The followings are the available columns in table 'feedback_content':
 * @property integer $id
 * @property integer $feedback_type_id
 * @property integer $customer_id
 * @property string $content
 * @property string $image
 * @property integer $create_time
 */
class FeedbackContent extends CActiveRecord
{
	public $modelName = '用户反馈内容';
	public $from_type_arr=array(0=>'其他',1=>'ios',2=>'andriod');//来源(0为其他，1为ios，2为andriod)
	public $reply_type_arr=array(0=>'未回复',1=>'已回复');//回复状态
	public $start_time;
	public $end_time;
	public $userName;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'feedback_content';
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
			array('feedback_type_id, customer_id, from_type, is_reply, create_time', 'numerical', 'integerOnly'=>true),
			array('image', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, feedback_type_id, customer_id, content, image, from_type, is_reply, create_time,start_time,end_time,userName', 'safe', 'on'=>'search'),
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
			'feedback_type_id' => '反馈类型',
			'customer_id' => '用户ID',
			'userName'=>'用户名',
			'content' => '反馈内容',
			'image' => '反馈图片',
			'from_type' => '客户端',
			'is_reply' => '回复状态',
			'create_time' => '添加时间',
			'start_time'=>'',
			'end_time'=>'',
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
		$criteria->compare('feedback_type_id',$this->feedback_type_id);
		if (!empty($this->userName)) {
			$customer=Customer::model()->find("mobile=:mobile",array(':mobile'=>$this->userName));
			if (!empty($customer)){
				$criteria->compare('customer_id',$customer->id);
			}
		}
		$criteria->compare('content',$this->content,true);
		$criteria->compare('image',$this->image,true);
		$criteria->compare('from_type',$this->from_type);
		$criteria->compare('is_reply',$this->is_reply);
		if (!empty($this->start_time)) {
			$criteria->addCondition('create_time>=' . strtotime($this->start_time." 00:00:00"));
		}
		if (!empty($this->end_time)) {
			$criteria->addCondition('create_time<=' . strtotime($this->end_time." 23:59:59"));
		}
		//$criteria->compare('create_time',$this->create_time);

		$criteria->order = 'create_time DESC' ;//时间倒序
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FeedbackContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取用户的反馈
	 * @param unknown $cust_id
	 * @return multitype:
	 */
	public function getListByCustID($cust_id,$page=1,$pagesize=10){
		$data=array();
		if (empty($cust_id)){
			return $data;
		}
		$criteria = new CDbCriteria;
		$criteria->addCondition("customer_id = :cust_id");
		$criteria->params[':cust_id']=$cust_id;
		$criteria->order = 'create_time desc';
		$page = intval($page) - 1;
		if ($page < 0) {
			$page = 0;
		}
		//分页
		//Yii::import('common.components.ActiveDataProvider');
		$dp = new ActiveDataProvider($this, array(
				'criteria' => $criteria,
				'pagination' => array(
						'currentPage' => $page,
						'pageSize' => $pagesize,
						'validateCurrentPage' => false,
				),
		));
		$list=$dp->getData ();
		if (!empty($list)){
			foreach ($list as $val){
				$tmp=array();
				$name='';
				$typeModel=FeedbackType::model()->findByPk($val->feedback_type_id);
				if (!empty($typeModel)){
					$name=$typeModel->name;
				}
				$tmp['id']=$val->id;
				$tmp['typeName']=$name;
				$tmp['content']=$val->content;
				$reply=FeedbackReply::model()->isRead($val->id);
				$tmp['is_read']=$reply;
				$tmp['create_time']=date("Y-m-d H:i",$val->create_time);
				$data[]=$tmp;
			}
		}
		return array (
				'list' => $data,
				'total' => $dp->totalItemCount 
		);
	}
	
	/**
	 * 获取反馈详情
	 * @param unknown $feedbackID
	 * @return array
	 */
	public function getDetail($feedbackID){
		if (empty($feedbackID)){
			return array();
		}
		//更改已读状态
		FeedbackReply::model()->updateReadStatus($feedbackID);
		$feedbackList=array();
		$replyList=array();
		$content=$this->model()->findByPk($feedbackID);
		if (!empty($content)){
			$name='';
			$feedbackList['content']=$content->content;
			$typeModel=FeedbackType::model()->findByPk($content->feedback_type_id);
			if (!empty($typeModel)){
				$name=$typeModel->name;
			}
			$feedbackList['typeName']=$name;
			$feedbackList['create_time']=date("Y-m-d H:i",$content->create_time);
			//获取图片
			$feedbackList['imageArr']=$this->getImages($content->image);
			$replyModel=FeedbackReply::model()->findAll("feedback_content_id=:fcid",array(':fcid'=>$feedbackID));
			//回复内容
			if (!empty($replyModel)){
				foreach ($replyModel as $val){
					$tmp=array();
					$userName='';
					$tmp['content']=$val->content;
					$tmp['create_time']=date("Y-m-d H:i",$val->create_time);
					if (strpos($val->customer_id, 'e_')!==false){
						$custArr=explode("_", $val->customer_id);
						$custID=$custArr[1];
						$employee=Employee::model()->findByPk($custID);
						if (!empty($employee)){
							$userName=$employee->name;
						}
					}else {
						$customer=Customer::model()->findByPk($val->customer_id);
						if (!empty($customer)){
							$userName=$customer->name;
						}
					}
					$tmp['userName']=$userName;
					$replyList[]=$tmp;
				}
			}
		}
		return array (
				'feedbackList' => $feedbackList,
				'replyList' => $replyList 
		);
	}
	
	/**
	 * 获取反馈类型
	 */
	public function getTypeName($type=0)
	{
		if (empty($type)){
			return '';
		}
		$type=FeedbackType::model()->findByPk($type);
		if (!empty($type)){
			return $type->name;
		}
		return '';
	}
	
	/**
	 * 获取客户端类型
	 */
	public function getFromTypeName($type=0)
	{
		return $this->from_type_arr[$type];
	}
	
	/**
	 * 获取用户名称
	 * @param unknown $customer_id
	 */
	public function getCustomerName($customer_id,$isCommunity=false)
	{
		if (empty($customer_id)){
			return '';
		}
		$userName='';
		$communityName='';
		$customer=Customer::model()->findByPk($customer_id);
		if (!empty($customer)){
			$userName=$customer->mobile;
		}
		if ($isCommunity){
			$community=Community::model()->findByPk($customer->community_id);
			if (!empty($community)){
				$communityName=$community->name;
			}
			return $communityName;
		}else {
			return $userName;
		}
	}
	
	/**
	 * 获取图片ID
	 * 
	 * @param unknown $imageID        	
	 */
	public function getImages($imageID) {
		$imageArr=array();
		if (! empty ( $imageID )) {
			if (strpos ( $imageID, "|" ) !== false) {
				$imgArr = explode ( "|", $imageID );
				array_filter ( $imgArr );
			} else {
				$imgArr [] = $imageID;
			}
			foreach ( $imgArr as $img ) {
				$tmp = array ();
				$images = Picture::model ()->findByPk ( $img );
				if (empty ( $images )) {
					continue;
				}
				$tmp ['url'] = Yii::app ()->imageFile->getUrl ( $images->url );
				$imageArr[] = $tmp;
			}
		}
		return $imageArr;
	}
	
	/**
	 * 判断是否有回复
	 * @param unknown $feedbackContentID
	 * @return boolean
	 */
	public function isReply($type=0){
		/* if (empty($feedbackContentID)){
			return false;
		}
		$reply=FeedbackReply::model()->findAll("feedback_content_id=:fc_id",array(':fc_id'=>$feedbackContentID));
		if (empty($reply)){
			return true;
		}
		return false; */
		if ($type == 1){
			return false;
		}else {
			return true;
		}
	}
	
	/**
	 * 判断是否有回复
	 * @param unknown $feedbackContentID
	 * @return boolean
	 */
	public function getReplyName($type = 0){
		/* if (empty($feedbackContentID)){
			return '未知错误';
		}
		$reply=FeedbackReply::model()->findAll("feedback_content_id=:fc_id",array(':fc_id'=>$feedbackContentID));
		if (!empty($reply)){
			return '已回复';
		}
		return '未回复'; */
		return $this->reply_type_arr[$type];
	}
	
	/**
	 * 获取全部反馈类型
	 * @return multitype:string NULL
	 */
	public function getFeedbackType(){
		$type=array(''=>'全部');
		$typeModel=FeedbackType::model()->findAll("state=0");
		if (!empty($typeModel)){
			foreach ($typeModel as $val){
				$type[$val->id]=$val->name;
			}
		}
		return $type;
	}
}
