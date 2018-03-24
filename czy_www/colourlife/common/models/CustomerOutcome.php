<?php

/**
 * author liangjianfeng
 * 业主竞猜世界杯胜负表
 */
class CustomerOutcome extends CActiveRecord{
    public $modelName = "业主竞猜世界杯胜负";
    
    public $customer_name;
    public $customer_mobile;
    public $encounter_teamgame;
    
    const IS_SEND_NO = 0;   //未送抽奖机会
    const IS_SEND_YES = 1;  //已送抽奖机会
    
    const IS_RIGHT_UNKNOWN = 0;   //未知
    const IS_RIGHT_YES = 1;       //竞猜正确
    const IS_RIGHT_NO = 2;      //竞猜错误

    public function tableName(){
        return 'customer_outcome';
    }
    
     public static function model($className = __CLASS__) {
         return parent::model($className);
     }
     
     public function attributeLabels() {
         return array(
             'id' => 'ID',
             'encounter_id' => '对阵表id',
             'myoutcome' => '我的竞猜结果',
             'create_time' => '创建时间',
             'customer_id' => '业主Id',
             'customer_ip' => 'IP地址',
             'CustomerName' => '业主姓名',
             'CustomerMobile' => '业主手机',
             'customer_name' => '业主姓名',
             'customer_mobile' => '业主手机号',
             'EncounterGame' => '场次',
             'EncounterHomeTeam' => '主队',
             'EncounterGuestTeam' => '客队',
             'is_send' => '送抽奖次数',
             'is_right' => '比赛结果',
             'encounter_teamgame' => '场次',
             'Statistics' => '统计(猜对)',
             'Totality' => '统计(总数)',
         );
     }
     
     public function rules() {
         return array(
             array('customer_name,customer_mobile,customer_id,is_send,is_right,encounter_teamgame,myoutcome','safe','on'=>'search'),
         );
     }
     
     public function getMyOutcomeName(){
         switch ($this->myoutcome){
            case Encounter::OUTCOME_HOME:
                return "主队胜利";
                break;
            case Encounter::OUTCOME_GUEST:
                return "客队胜利";
                break;
            case Encounter::OUTCOME_TIE:
                return "平局";
                break;
            default :
                return "N/A";
                break;
        }
     }
     
     public function getMyOutcomeNameInCss(){
         switch ($this->myoutcome){
            case Encounter::OUTCOME_HOME:
                return "<span class='label label-success'>主队胜利</span>";
                break;
            case Encounter::OUTCOME_GUEST:
                return '<span class="label label-success">客队胜利</span>';
                break;
            case Encounter::OUTCOME_TIE:
                return '<span class="label label-success">平局</span>';
                break;
            default :
                return "<span class='label label-success'>N/A</span>";
                break;
        }
     }
     
     public function relations()
    {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'encounter' => array(self::BELONGS_TO, 'Encounter', 'encounter_id'),
        );
    }
     
     public function getCustomerName(){
        return $this->customer?$this->customer->name:"";
    }
    
    public function getCustomerMobile(){
        return $this->customer?$this->customer->mobile:"";
    }
    
    public function getEncounterGame(){
        return $this->encounter?$this->encounter->game_number:"";
    }
    
    public function getEncounterHomeTeam(){
        return $this->encounter?$this->encounter->HomeTeam:"";
    }
    
    public function getEncounterGuestTeam(){
        return $this->encounter?$this->encounter->GuestTeam:"";
    }
    
    public function getSendChance(){
        switch ($this->is_send){
            case self::IS_SEND_NO:
                return "未送";
                break;
            case self::IS_SEND_YES:
                return "已送";
                break;
            default :
                return "未知情况";
                break;
        }
    }
    
    public function getRightStatus(){
        switch($this->is_right){
            case self::IS_RIGHT_UNKNOWN:
                return "未知";
                break;
            case self::IS_RIGHT_YES:
                return "竞猜正确";
                break;
            case self::IS_RIGHT_NO:
                return "竞猜错误";
                break;
            default :
                return "-";
        }
    }
     
     public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('is_send', $this->is_send);
        $criteria->compare('is_right', $this->is_right);
        $criteria->compare('myoutcome', $this->myoutcome);        
        if($this->customer_name || $this->customer_mobile){
            $criteria->with=array(  
                'customer',  
            );
            $criteria->compare('customer.name', $this->customer_name, true);
            $criteria->compare('customer.mobile', $this->customer_mobile, true);
        }
        if($this->encounter_teamgame){
            $encounterModel = Encounter::model()->find('game_number=:game_number',array(':game_number'=>$this->encounter_teamgame));
            if($encounterModel){
                $criteria->compare('encounter_id', $encounterModel->id);
            }            
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function searchByStatistics(){
        $criteria = new CDbCriteria;
        $criteria->select = "*,count(customer_id) as customer_count";
        $criteria->compare('is_right', self::IS_RIGHT_YES);
        $criteria->group = 'customer_id';
        $criteria->order = "customer_count desc";
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function getStatistics(){
        return self::model()->count('customer_id=:customer_id and is_right=:is_right',
                                array(':customer_id'=>$this->customer_id,':is_right'=>self::IS_RIGHT_YES));
    }
    
    public function getTotality(){
        return self::model()->count('customer_id=:customer_id',
                                array(':customer_id'=>$this->customer_id));
    }
    
    public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
        );
    }
    
    public function searchByPage($pageSzie,$pageIndex,$encounter_id){
        $count = CustomerOutcome::model()->count('encounter_id=:encounter_id and is_right=:is_right',
                                                  array(':encounter_id' => $encounter_id,':is_right' => CustomerOutcome::IS_RIGHT_YES) );
        $pageMax = ceil($count/$pageSzie);
        if($pageIndex-1 > $pageMax){
            return false;
        }
        $index = $pageSzie*($pageIndex-1);
        $sql = "select * from customer_outcome where encounter_id = $encounter_id and is_right = ".CustomerOutcome::IS_RIGHT_YES." limit $index,$pageSzie";
        $result = Yii::app()->db->createcommand($sql);
        return $result->queryAll();        
    }
    
    public function searchCustomerByPage($pageSzie,$pageIndex){
        $count = Customer::model()->count('is_deleted = 0');
        $pageMax = ceil($count/$pageSzie);
        if($pageIndex-1 > $pageMax){
            return false;
        }
        $index = $pageSzie*($pageIndex-1);
        $sql = "select * from customer where is_deleted = 0 limit $index,$pageSzie";
        $result = Yii::app()->db->createcommand($sql);
        return $result->queryAll();
    }
    
    //获取用户目前猜中的数目
    public function getCustomerStatistics($customer_id){
        return self::model()->count('customer_id=:customer_id and is_right=:is_right',
                array(':customer_id'=>$customer_id,':is_right'=>self::IS_RIGHT_YES));
    }
    
    //获取用户目前一共猜了多少场
    public function getCustomerTotal($customer_id){
        return self::model()->count('customer_id=:customer_id',
                array(':customer_id'=>$customer_id));
    }
    
    //获取用户当前所有竞猜胜负的记录
    public function getCustomerTotalRecord($customer_id){
        $criteria=new CDbCriteria;
        $criteria->condition='customer_id=:customer_id';  
        $criteria->params=array(':customer_id'=>$customer_id);  
        $criteria->order='id DESC'; 
        return self::model()->findAll($criteria);
    }
    
    public function getCustomerRecodeByPage($customer_id){
        $criteria=new CDbCriteria;
        $criteria->condition='customer_id=:customer_id';  
        $criteria->params=array(':customer_id'=>$customer_id);  
        $criteria->order='id DESC'; 
        $criteria->limit = "3";
        return self::model()->findAll($criteria);
    }
    
}

