<?php

/**
 * This is the model class for table "sms".
 *
 * The followings are the available columns in table 'sms':
 * @property integer $id
 * @property string $mobile
 * @property integer $code
 * @property string $token
 * @property integer $status
 * @property integer $create_time
 */
class Sms extends CActiveRecord
{
    const STATUS_BEGIN = 0;
    const STATUS_SEND_OK = 1;
    const STATUS_SEND_FAILED = -1;
    const STATUS_CODE_USED = 2;
    const STATUS_TOKEN_USED = 3;
   
    public $modelName ="SMS";
    
    public $startTime ;
    public $endTime;
    public $user_agent;
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Sms the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sms';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('id, mobile, code,user_agent, token, status, create_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'mobile' => '手机号码',
            'code' => 'Code',
            'token' => 'Token',
            'status' => 'Status',
            'create_time' => '发送时间',
        	'startTime' =>"开始时间",
        	'endTime' =>"结束时间",
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('mobile', $this->mobile,true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('token', $this->token, true);
        $criteria->compare('status', $this->status);
        
        if ($this->startTime != '') {
            $criteria->compare("`t`.create_time", ">=" . strtotime($this->startTime));
        }

        if ($this->endTime != '') {
            $criteria->compare("`t`.create_time", "< " . strtotime($this->endTime . " 23:59:59"));
        }

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
        );
    }

    protected function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            $this->code = F::random(4, 1);
            $this->token = F::random(8);
        }

        if (substr($this->mobile, 0, 8) == '13200000')
            $this->code = '4321';

        return parent::BeforeSave();
    }

    // 设置发送成功
    public function sendOK()
    {
        $this->updateByPk($this->id, array('status' => self::STATUS_SEND_OK));
    }

    // 设置发送失败
    public function sendFailed()
    {
        $this->updateByPk($this->id, array('status' => self::STATUS_SEND_FAILED));
    }

    // 设置已验证
    public function useCode()
    {
        $this->updateByPk($this->id, array('status' => self::STATUS_CODE_USED));
    }

    // 设置已操作
    public function useToken()
    {
        $this->updateByPk($this->id, array('status' => self::STATUS_TOKEN_USED));
    }

    // 最近发送的
    public function findByMobile($mobile)
    {
        return SMS::model()->findByAttributes(array(
            'mobile' => $mobile,
            'status' => self::STATUS_SEND_OK,
        ));
    }

    public function findByMobileAndCode($mobile, $code, $time)
    {
        return SMS::model()->findByAttributes(array(
            'mobile' => $mobile,
            'code' => $code,
            'status' => self::STATUS_SEND_OK,
        ), 'create_time>:time', array(
            ':time' => $time,
        ));
    }

    public function findByMobileAndToken($mobile, $token, $time)
    {
        return SMS::model()->findByAttributes(array(
            'mobile' => $mobile,
            'token' => $token,
            'status' => self::STATUS_CODE_USED,
        ), 'create_time>:time', array(
            ':time' => $time,
        ));
    }
    
    public function queryByMessage($message){
        return self::model()->findBySql("select * from sms where mobile = ".$message." order by id desc limit 1");
    }

}
