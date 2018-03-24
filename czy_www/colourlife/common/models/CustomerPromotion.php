<?php

/*
 * author liangjianfeng
 * 业主竞猜世界杯晋级表
 */
class CustomerPromotion extends CActiveRecord{

    public $modelName = "业主竞猜世界杯胜负";

    public $customer_name;
    public $customer_mobile;
    public $teams_promotion_type;

    const IS_SEND_NO = 0;   //未送奖品
    const IS_SEND_YES = 1;  //已送奖品

    const IS_RIGHT_UNKNOWN = 0;   //未知
    const IS_RIGHT_YES = 1;       //竞猜正确
    const IS_RIGHT_NO = 2;      //竞猜错误
    
    public function tableName() {
        return 'customer_promotion';
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'teams_promotion_id' => '阶级晋级表id',
            'my_quiz_teams' => '我的竞猜结果',
            'create_time' => '创建时间',
            'customer_id' => '业主Id',
            'customer_ip' => 'IP地址',
            'customer_name' => '业主姓名',
            'customer_mobile' => '业主手机号',
            'CustomerName' => '业主姓名',
            'CustomerMobile' => '业主手机号',
            'is_send' => '送奖品',
            'is_right' => '竞猜结果',
            'update_times' => '修改次数',
            'teams_promotion_type' => '阶级晋级类型',
            'TeamsPromotionType' => '阶级晋级类型',
        );
    }

    public function rules() {
        return array(
            array('teams_promotion_id,my_quiz_teams,create_time,customer_id,customer_ip', 'required', 'on' => 'create,update'),
            array('teams_promotion_id,customer_id,is_send,is_right,update_times, is_deleted', 'numerical', 'integerOnly' => true),
            array('teams_promotion_id,my_quiz_teams,customer_id,create_time,is_send,is_right,update_times,teams_promotion_type','safe','on'=>'search'),
        );
    }

    public function getRightStatus($html = false){
        switch ($this->is_right){
            case self::IS_RIGHT_UNKNOWN:
                return ($html) ? "N/A" : "<span class='label label-success'>N/A</span>";
                break;
            case self::IS_RIGHT_NO:
                return ($html) ? "竞猜错误" : "<span class='label label-important'>竞猜错误</span>";
                break;
            case self::IS_RIGHT_YES:
                return ($html) ? "竞猜正确" : "<span class='label label-success'>竞猜正确</span>";
                break;
            default :
                return ($html) ? "N/A" : "<span class='label label-success'>N/A</span>";
                break;
        }
    }

    public function relations()
    {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'teams_promotion' => array(self::BELONGS_TO, 'SetTeamsPromotion', 'teams_promotion_id'),
        );
    }

    public function getTeamsPromotionType(){
        return $this->teams_promotion?$this->teams_promotion->type:"";
    }

    public function getCustomerName(){
        return $this->customer?$this->customer->name:"";
    }

    public function getCustomerMobile(){
        return $this->customer?$this->customer->mobile:"";
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


    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('is_send', $this->is_send);
        $criteria->compare('is_right', $this->is_right);
        $criteria->compare('teams_promotion_id', $this->teams_promotion_id);
        if($this->customer_name || $this->customer_mobile){
            $criteria->with=array(
                'customer',
            );
            $criteria->compare('customer.name', $this->customer_name, true);
            $criteria->compare('customer.mobile', $this->customer_mobile, true);
        }
        if($this->teams_promotion_type){
            $promotionModel = SetTeamsPromotion::model()->find('type=:type',array(':type'=>$this->teams_promotion_type));
            if($promotionModel){
                $criteria->compare('teams_promotion_id', $promotionModel->id);
            }
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getPromotionList(){
        $promotionList = array();
        $teamsModel = SetTeamsPromotion::model()->findAll();
        foreach($teamsModel as $_v){
            $promotionList[$_v->id] = $_v->type;
        }
        return $promotionList;
    }

    public function getTeamsCode($id){
        $teamList = array();
        $teamsModel = SetTeamsPromotion::model()->findByPk($id);
        if(!$teamsModel)
            return false;
        $teamList=explode(",",$teamsModel->can_guess_teams);
        return $teamList;
    }

    public function getTeamModel($code){
        $teamModel=TeamCode::model()->find("code=:code",array(":code"=>$code));
        return $teamModel;
    }

    public function getRightTeams($id){
        $teamList = array();
        $teamsModel = SetTeamsPromotion::model()->findByPk($id);
        if(!$teamsModel)
            return false;
        $teamList=explode(",",$teamsModel->result_teams);
        return $teamList;
    }

    public function getCodeName($code){
        $teamModel=TeamCode::model()->find("code=:code",array(":code"=>$code));
        return $teamModel->team;
    }

    public function getGuessTime($id){
        $teamsModel = SetTeamsPromotion::model()->findByPk($id);
        if(!$teamsModel)
            return false;
        $time=date("m月d日",strtotime($teamsModel->start_time)).' — '.date("m月d日",strtotime($teamsModel->end_time));
        return $time;
    }

    public function getGroupTeam(){
        $group=array();
        $models=TeamCode::model()->findAll();
        foreach($models as $model){
            $group[$model->group][]=$model;
        }
        return $group;
    }

    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
        );
    }

}