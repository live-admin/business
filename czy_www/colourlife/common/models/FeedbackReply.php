<?php

/**
 * This is the model class for table "feedback_reply".
 *
 * The followings are the available columns in table 'feedback_reply':
 * @property integer $id
 * @property integer $feedback_content_id
 * @property string $customer_id
 * @property string $content
 * @property integer $to_reply_id
 * @property string $to_user_id
 * @property integer $create_time
 * @property integer $update_time
 */
class FeedbackReply extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'feedback_reply';
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
			array('feedback_content_id, to_reply_id, is_read, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('customer_id, to_user_id', 'length', 'max'=>25),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, feedback_content_id, customer_id, content, to_reply_id, to_user_id, is_read, create_time, update_time', 'safe', 'on'=>'search'),
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
			'feedback_content_id' => '反馈内容ID',
			'customer_id' => '回复人ID',
			'content' => '回复内容',
			'to_reply_id' => '被回复的ID',
			'to_user_id' => '被回复人的ID',
			'is_read' => '是否已读（0未读，1已读）',
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
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('feedback_content_id',$this->feedback_content_id);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('to_reply_id',$this->to_reply_id);
		$criteria->compare('to_user_id',$this->to_user_id,true);
		$criteria->compare('is_read',$this->is_read);
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
	 * @return FeedbackReply the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 判断是否已读
	 * @param unknown $feedbackID
	 * @return string
	 */
	public function isRead($feedbackContentID){
		if (empty($feedbackContentID)){
			return 0;
		}
		$reply=$this->findAll("is_read=0 and feedback_content_id=:fc_id",array(':fc_id'=>$feedbackContentID));
		if (!empty($reply)){
			return 1;
		}
		return 0;
	}
	
	/**
	 * 更新读的状态
	 * @param unknown $feedbackContentID
	 * @return boolean
	 */
	public function updateReadStatus($feedbackContentID){
		if (empty($feedbackContentID)){
			return false;
		}
		$isRead=$this->isRead($feedbackContentID);
		if (empty($isRead)){
			return false;
		}
		$result=$this->updateAll(array('is_read'=>1),'feedback_content_id=:fc_id',array(':fc_id'=>$feedbackContentID));
		if ($result){
			return true;
		}
		return false;
	}
	
	/**
	 * 获取用户名
	 * @param unknown $customer_id
	 * @return string
	 */
	public function getCustomerName($customer_id){
		if (empty($customer_id)){
			return '';
		}
		$userName='';
		if (strpos ( $customer_id, 'e_' ) !== false) {
			$custArr = explode ( "_", $customer_id );
			$custID = $custArr [1];
			$employee = Employee::model ()->findByPk ( $custID );
			if (! empty ( $employee )) {
				$userName = $employee->name;
			}
		} else {
			$customer = Customer::model ()->findByPk ( $customer_id );
			if (! empty ( $customer )) {
				$userName = $customer->name;
			}
		}
		return $userName;
	}
}
