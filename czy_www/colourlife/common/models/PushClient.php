<?php

/**
 * This is the model class for table "push_client".
 *
 * The followings are the available columns in table 'push_client':
 * @property integer $id
 * @property integer $push_information_id
 * @property integer $object_id
 * @property integer $type
 * @property integer $is_read
 * * @property integer $is_delete
 * @property integer $mobile
 * @property integer $android_request_id
 * @property integer $ios_request_id
 * @property integer $create_time
 */
class PushClient extends CActiveRecord
{

    public $modelName ="推送用户列表";

    const IS_TYPE_SHOP = 2; //商家
    const IS_TYPE_CUSTOMER = 0; // 业主
    const IS_TYPE_EMPLOYEE = 1; // 物业

    public $startTime;
    public $endTime;

    public $pushType = array(
        PushInformation::IS_TYPE_CUSTOMER=>'业主',
        PushInformation::IS_TYPE_EMPLOYEE=>'物业',
        PushInformation::IS_TYPE_SHOP=>'商家',
    );

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'push_client';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('push_information_id, object_id, type, is_read, is_delete, android_request_id, ios_request_id, create_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, startTime,endTime,push_information_id, object_id, type, is_read, is_delete, mobile, android_request_id, ios_request_id, create_time', 'safe', 'on'=>'search'),
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
             'pushInformation' => array(self::BELONGS_TO,"PushInformation","push_information_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'push_information_id' => 'Push Information',
			'object_id' => 'Object',
			'type' => '类型',
			'is_read' => '状态',
            'is_delete' => '是否删除',
			'mobile' => '电话',
            'title' => '标题',
            'content'=>'消息内容',
			'android_request_id' => 'Android Request',
			'ios_request_id' => 'Ios Request',
			'create_time' => '发送时间',
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
        $shop_id=Yii::app()->user->id;
		$criteria=new CDbCriteria;
		$criteria->compare('object_id',$shop_id);
		$criteria->compare('type',PushInformation::IS_TYPE_SHOP);
		$criteria->compare('is_read',$this->is_read);
        $criteria->compare('is_delete',$this->is_delete);
        $criteria->order = "is_read DESC , create_time DESC";
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getTitle(){
        return empty($this->pushInformation->title)?"":$this->pushInformation->title;
    }

    public function getContent(){
        return empty($this->pushInformation->content)?"":$this->pushInformation->content;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PushClient the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function behaviors()
    {
        return array(

            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function shopSearch(){
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->with[] = "pushInformation";
        $criteria->compare('push_information_id',$this->push_information_id);
        $criteria->compare("pushInformation.title",$this->title,true);
        $criteria->compare('object_id',Yii::app()->user->id);
        $criteria->addInCondition('t.type',array(PushClient::IS_TYPE_SHOP,-1));
        $criteria->compare('is_read',$this->is_read);
        $criteria->compare('is_delete',$this->is_delete);
        $criteria->compare('mobile',$this->mobile);
        $criteria->compare('android_request_id',$this->android_request_id);
        $criteria->compare('ios_request_id',$this->ios_request_id);
        $criteria->compare('t.create_time',$this->create_time);

        if ($this->startTime != "") {
            $criteria->addCondition('t.create_time>=' . strtotime($this->startTime));
        }
        if ($this->endTime != "") {
            $criteria->addCondition('t.create_time<=' . strtotime($this->endTime . " 23:59:59"));
        }

        $criteria->order = "is_read ASC , t.create_time DESC";
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));

    }

    public function employeeSearch(){
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->with[] = "pushInformation";
        $criteria->compare("pushInformation.title",$this->title,true);
        $criteria->compare('push_information_id',$this->push_information_id);
        $criteria->compare('object_id',Yii::app()->user->id);
        $criteria->addInCondition('t.type',array(PushClient::IS_TYPE_EMPLOYEE,-1));
        $criteria->compare('is_read',$this->is_read);
        $criteria->compare('is_delete',$this->is_delete);
        $criteria->compare('mobile',$this->mobile);
        $criteria->compare('android_request_id',$this->android_request_id);
        $criteria->compare('ios_request_id',$this->ios_request_id);
        $criteria->compare('t.create_time',$this->create_time);

        if ($this->startTime != "") {
            $criteria->addCondition('t.create_time>=' . strtotime($this->startTime));
        }
        if ($this->endTime != "") {
            $criteria->addCondition('t.create_time<=' . strtotime($this->endTime . " 23:59:59"));
        }

        $criteria->order = "is_read ASC , t.create_time DESC";
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function customerSearch(){
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->compare('push_information_id',$this->push_information_id);
        $criteria->compare('object_id',Yii::app()->user->id);
        $criteria->addInCondition('t.type',array(PushClient::IS_TYPE_CUSTOMER,-1));
        $criteria->compare('is_read',$this->is_read);
        $criteria->compare('is_delete',$this->is_delete);
        $criteria->compare('mobile',$this->mobile);
        $criteria->compare('android_request_id',$this->android_request_id);
        $criteria->compare('ios_request_id',$this->ios_request_id);
        $criteria->compare('t.create_time',$this->create_time);

        if ($this->startTime != "") {
            $criteria->addCondition('t.create_time>=' . strtotime($this->startTime));
        }
        if ($this->endTime != "") {
            $criteria->addCondition('t.create_time<=' . strtotime($this->endTime . " 23:59:59"));
        }

        $criteria->order = "is_read DESC , t.create_time DESC";
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }


    //获取信息状态
    public function getIsRead(){
        if($this->is_read=="0"){
            return "未读";
        }else if($this->is_read=="1"){
            return "已读";
        }
    }

}
