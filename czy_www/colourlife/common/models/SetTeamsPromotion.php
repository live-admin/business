<?php

/*
 * author liangjianfeng
 * 配置世界杯阶段晋级表
 */
class SetTeamsPromotion extends CActiveRecord{

    public $modelName = '世界杯阶段晋级信息';
    
    public function tableName(){
        return 'set_teams_promotion';
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'type' => '比赛类型',
            'can_guess_teams' => '可选球队',
            'result_teams' => '结果',
            'start_time' => '竞猜开始时间',
            'end_time' => '竞猜结束时间',
            'start_time_ymd' => '竞猜开始时间年月日',
            'start_time_his' => '竞猜开始时间时分秒',
            'end_time_ymd' => '竞猜结束时间年月日',
            'end_time_his' => '竞猜结束时间时分秒',
            'redpacket' => '猜中红包',
            'is_deleted' => '是否删除',
        );
    }


    public function rules() {
        return array(
            array('type,start_time,end_time,redpacket','required','on' => 'create,update'),
            array('can_guess_teams,result_teams','safe','on' => 'update'),
            array('result_teams','required','on' => 'result'),
            array('id,type,is_deleted','safe','on' => 'search'),
        );
    }

    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('type', $this->type, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    public function getStart_time_ymd(){
        if($this->start_time){
            return date("Y-m-d",  strtotime($this->start_time));
        }else{
            return date("Y-m-d",time());
        }
    }
    public function getStart_time_his(){
        return date("h:i A",  strtotime($this->start_time));
    }

    public function getEnd_time_ymd(){
        if($this->end_time){
            return date("Y-m-d",  strtotime($this->end_time));
        }else{
            return date("Y-m-d",time());
        }
    }
    public function getEnd_time_his(){
        return date("h:i A",  strtotime($this->end_time));
    }


    public function getPromotionList(){
        $promotionList = array();
        $teamsModel = SetTeamsPromotion::model()->findAll();
        foreach($teamsModel as $_v){
            $promotionList[$_v->id] = $_v->type;
        }
        return $promotionList;
    }



    public function relations()
    {
        return array(
        );
    }

    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
        );
    }




}