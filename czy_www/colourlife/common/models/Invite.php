<?php

/**
 * This is the model class for table "invite".
 *
 * The followings are the available columns in table 'invite':
 * @property integer $id
 * @property integer $customer_id
 * @property string $mobile
 * @property integer $create_time
 * @property integer $valid_time
 * @property integer $status
 */
class Invite extends CActiveRecord
{
    public $modelName = "邀请好友注册";
    //const STARTTIME = "2014-07-09 00:00:00";   //活动开始时间
    //const ENDTIME = "2015-03-04 17:00:00";     //活动结束时间
    const STARTTIME = "2015-07-16 00:00:00";   //活动开始时间
    const ENDTIME = "2015-10-30 23:59:59";     //活动结束时间
    const INVITECOUNT  = 10;                   //累计邀请次数
    private $hour_start=9;
    private $hour_end=22;
    
    public $customer_name;
    public $customer_mobile;
    
    public $create_start_time;
    public $create_end_time;
    
    public $reg_start_time;
    public $reg_end_time;
    
    public $invitorState;

    public $startTime;
    public $endTime;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Invite the static model class
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
        return 'invite';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id, create_time, valid_time, status, effective, is_send', 'numerical', 'integerOnly' => true),
            array('mobile', 'length', 'max' => 15),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, customer_id, mobile, create_time, valid_time, status, customer_name,customer_mobile,create_start_time,create_end_time,reg_start_time,reg_end_time', 'safe', 'on' => 'search'),
            array('id, customer_id, mobile, create_time, valid_time, status, customer_name,customer_mobile,create_start_time,create_end_time,effective,invitorState', 'safe', 'on' => 'search_send'),
            array('id, sn, employee_id, mobile, create_time, valid_time, status, state, startTime, endTime, is_send, effective', 'safe', 'on'=>'report_search'),
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
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            //'employee' => array(self::BELONGS_TO, 'Employee', 'customer_id'),
        );
    }

    static $invite_status = array(
        0 => "未注册",
        1 => "注册成功",
    );

    public static function getInviteStatusNames()
    {
        return CMap::mergeArray(array('' => '全部'), self::$invite_status);
    }

    static $invite_state = array(
        0 => "未审核",
        1 => "审核成功",
        2 => "审核失败",
    );

    public static function getInviteStateNames()
    {
        return CMap::mergeArray(array('' => '全部'), self::$invite_state);
    }    


    static $invite_send = array(
        0 => "未发放",
        1 => "已经发放",
    );

    public static function getInviteSendNames()
    {
        return CMap::mergeArray(array('' => '全部'), self::$invite_send);
    }

    static $invite_effective = array(
        0 => "邀请无效",
        1 => "有效",
    );

    public static function getInviteEffectiveNames()
    {
        return CMap::mergeArray(array('' => '全部'), self::$invite_effective);
    }




    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'customer_id' => '业主Id',
            'mobile' => '邀请号码',
            'model' => '邀请模型',
            'create_time' => '邀请时间',
            'valid_time' => '过期时间',
            'status' => '注册状态',
            'is_send' => '是否已发',
            'effective' => '是否有效',
            'CustomerName' => '业主姓名',
            'CustomerMobile' => '业主手机',
            'customer_name' => '业主姓名',
            'customer_mobile' => '业主手机',
            'create_start_time' => '邀请开始时间',
            'create_end_time' => '邀请结束时间',
            'reg_start_time' => '注册时间开始',
            'reg_end_time' => '注册时间结束',
            'InviteSuccessCount' => '待发红包人次',
            'RegisterTime' => '注册时间',
            'InvitorStatus' => '是否禁用',
            'invitorState' => '是否禁用',
            'AllInviteCount' => '总邀请人数',
            'SendInviteCount' => '已发红包人数',
            'RegisterTimeNew' => '注册时间',
            'CreateDate' => '邀请时间',
            'state' => '审核状态',
            'startTime' => '邀请开始时间',
            'endTime' => '邀请结束时间',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('valid_time', $this->valid_time);
        $criteria->compare('status', $this->status);
        $criteria->compare('model', 'customer');
        if($this->customer_name || $this->customer_mobile){
            if($this->reg_start_time || $this->reg_end_time){
                $criteria->join = "left join customer on customer.mobile = t.mobile";
                if($this->reg_start_time != ""){
                    $criteria->addCondition('customer.create_time>=' . strtotime($this->reg_start_time));
                }
                if($this->reg_end_time != ""){
                    $criteria->addCondition('customer.create_time<=' . strtotime($this->reg_end_time));
                }
            }else{
                //$criteria->join = "left join customer on customer.mobile = t.mobile";
                $criteria->with = 'customer';
            }                
                $criteria->compare('customer.name', $this->customer_name, true);
                $criteria->compare('customer.mobile', $this->customer_mobile, true);
        }else{
            if($this->reg_start_time || $this->reg_end_time){
                $criteria->join = "left join customer on customer.mobile = t.mobile";
                if($this->reg_start_time != ""){
                    $criteria->addCondition('customer.create_time>=' . strtotime($this->reg_start_time));
                }
                if($this->reg_end_time != ""){
                    $criteria->addCondition('customer.create_time<=' . strtotime($this->reg_end_time));
                }
            }
        }
        if ($this->create_start_time != "") {
            $criteria->addCondition('t.create_time>=' . strtotime($this->create_start_time));
        }
        if ($this->create_end_time != "") {
            $criteria->addCondition('t.create_time<=' . strtotime($this->create_end_time . " 23:59:59"));
        }
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function search_send(){
        $criteria = new CDbCriteria;
        $criteria->select = '*,count(*) as mycount';
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.is_send', $this->is_send);
        $criteria->compare('t.effective', $this->effective);
        $criteria->addCondition('t.create_time>=' . strtotime($this->create_start_time));
        $criteria->addCondition('t.create_time<=' . strtotime($this->create_end_time));
        
        if(isset($this->invitorState)){
            if($this->invitorState != 2){
                $criteria->with = 'customer';
                $criteria->compare('customer.state', $this->invitorState);
                if($this->customer_name || $this->customer_mobile){
                    $criteria->compare('customer.name', $this->customer_name, true);
                    $criteria->compare('customer.mobile', $this->customer_mobile, true);
                }
            }else{
                if($this->customer_name || $this->customer_mobile){
                    $criteria->with = 'customer';
                    $criteria->compare('customer.name', $this->customer_name, true);
                    $criteria->compare('customer.mobile', $this->customer_mobile, true);
                }
                
            }
            
        }else{
            if($this->customer_name || $this->customer_mobile){
                $criteria->with = 'customer';
                $criteria->compare('customer.name', $this->customer_name, true);
                $criteria->compare('customer.mobile', $this->customer_mobile, true);
            }
        }
        $criteria->group = "customer_id";
        $criteria->having = "mycount >= 10";
        $criteria->order = " t.create_time desc, mycount desc";
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    


    public function report_search()
    {
        $criteria=new CDbCriteria;
        if (isset($_GET['Invite']) && !empty($_GET['Invite'])) {
            $_SESSION['Invite'] = array();
            $_SESSION['Invite'] = $_GET['Invite'];
        }
        if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
            if (isset($_SESSION['Invite']) && !empty($_SESSION['Invite'])) {
                foreach ($_SESSION['Invite'] as $key => $val) {
                    if ($val != "") {
                        $this->$key = $val;
                    }
                }
            }
        }
        $criteria->compare('id', $this->id);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('valid_time', $this->valid_time);
        $criteria->compare('status', $this->status);
        $criteria->compare('state', $this->state);
        $criteria->compare('effective', $this->effective);
        $criteria->compare('is_send', 0);
        $criteria->compare('model', 'customer');

        if ($this->startTime!= '') {
            $criteria->compare("create_time", ">= " . strtotime($this->startTime." 00:00:00"));
        }
        if ($this->endTime!= '') {
            $criteria->compare("create_time", "<= " . strtotime($this->endTime." 23:59:59"));
        }
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
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
    
    //获取被邀请者注册时间
    public function getRegisterTime(){
        $customer = Customer::model()->find('mobile=:mobile',array(':mobile'=>$this->mobile));
        return $customer?$customer->create_time:0;
    }
    
    public function getRegisterTimeNew(){
        $customer = Customer::model()->find('mobile=:mobile',array(':mobile'=>$this->mobile));
        return $customer?date("Y-m-d H:i:s",$customer->create_time):date("Y-m-d H:i:s",0);
    }
    
    public function getCustomerName(){
            return $this->customer?$this->customer->name:"";
    }
    
    public function getCustomerMobile(){
            return $this->customer?$this->customer->mobile:"";
    }
    
    
    public function getStatusName(){
        if($this->status == 1){
            return "<span class='label label-success'>已注册</span>";
        }else{
            return "<span class='label label-important'>未注册</span>";
        }
    }


    public function getInviteStatusName(){
        if($this->status == 1){
            return "已注册";
        }else{
            return "未注册";
        }
    }
    
    public function getSendName(){
        if($this->is_send == 1){
            return "已发";
        }else{
            return "未发";
        }
    }
    
    public function getEffectiveName(){
        if($this->effective == 1){
            return "有效";
        }else{
            return "无效";
        }
    }
    

    public function getInviteStateName(){
        if($this->state == 0){
            return "未审核";
        }else if($this->state == 1){
            return "审核成功";
        }else{
            return "审核失败";
        }
    }


    
    public function getInviteSuccessCount(){
        return Invite::model()->count("customer_id=".$this->customer_id." and status = 1 and is_send = 0 and effective = 1 and create_time>=".strtotime(Invite::STARTTIME)." and create_time<=".strtotime(Invite::ENDTIME));
    }
    
    public function getAllInviteCount(){
        return Invite::model()->count("customer_id=".$this->customer_id." and create_time>=".strtotime(Invite::STARTTIME)." and create_time<=".strtotime(Invite::ENDTIME));
    }
    
    public function getSendInviteCount(){
        return Invite::model()->count("customer_id=".$this->customer_id." and status = 1 and is_send = 1 and effective = 1 and create_time>=".strtotime(Invite::STARTTIME)." and create_time<=".strtotime(Invite::ENDTIME));
    }
    
    public function getCreateDate(){
        return date("Y-m-d H:i:s",$this->create_time);
    }
    
    public function getInvitorStatus(){
        $customerModel = Customer::model()->findByPk($this->customer_id);
        if($customerModel){
            if($customerModel->state == 1){
                return "<span class='label label-important'>已禁用</span>";
            }else if($customerModel->state == 0){
                return "<span class='label label-success'>启用</span>";
            }else{
                return "未知";
            }
        }else{
            return "没有该业主";
        }
    }


    public function getCreateTime()
    {
        return date("Y-m-d H:i:s", $this->create_time);
    }

    public function getValidTime()
    {
        return date("Y-m-d H:i:s", $this->valid_time);
    }
    
    /*
     * @version 根据日期统计前三名
     * @param int 时间戳 $daySeconds
     */
    public function getCountSan($daySeconds){
        if(empty($daySeconds)){
            return false;
        }
        $dayMoreSeconds=$daySeconds+24*60*60;
        $sql = "select i.customer_id,i.create_time,count(*) as mycount from invite i LEFT JOIN customer c on i.mobile=c.mobile where i.status=1 and i.model='customer' and i.effective=1 and i.state=1 and FROM_UNIXTIME(i.create_time) >='".Item::INVITE_REGISTER_START_TIME."' and FROM_UNIXTIME(i.create_time) <='".Item::INVITE_REGISTER_END_TIME."' and TO_DAYS(FROM_UNIXTIME(c.create_time))=TO_DAYS(FROM_UNIXTIME(i.create_time)) and HOUR(FROM_UNIXTIME(i.create_time))>=".$this->hour_start." and HOUR(FROM_UNIXTIME(i.create_time))<".$this->hour_end." and i.create_time>=".$daySeconds." and i.create_time<=".$dayMoreSeconds." GROUP BY i.customer_id HAVING mycount>20 ORDER BY mycount desc,i.create_time desc,i.id desc limit 3";
        $result = Yii::app()->db->createCommand($sql);
        $query = $result->queryAll();
        return $query;
    }
    

}
