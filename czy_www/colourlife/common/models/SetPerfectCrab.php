<?php
/**
 * This is the model class for table "set_moon_cakes".
 *
 * The followings are the available columns in table 'set_moon_cakes':
 * @property int $id
 * @property string $activity_name
 * @property int $start_time
 * @property int $end_time
 * @property int $clicks
 * @property int $is_deleted
 */
class SetPerfectCrab extends CActiveRecord{
    public $modelName = "完美蟹逅活动";
    
    public static $act_id;
    
    public static $act_time = 0;


    public function tableName() {
        return "set_perfect_crab";
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function rules() {
        return array(
            array('activity_name,start_time,end_time,create_time,state','required','on' => 'create'),
            array('activity_name,start_time,end_time','required','on' => 'update'),
            array('activity_name', 'safe', 'on' => 'search'),
            array('right_result','checkResult','on' => 'result'),
        );
    }
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'activity_name' => '活动名称',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'clicks' => '点击次数',
            'create_time' => '创建时间',
            'is_deleted' => '是否删除',
            'state' => '状态',
            'right_result' => '正确结果',
        );
    }
    
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        
        $criteria->compare('activity_name', $this->activity_name, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
        );
    }
    
    //获取本场抢号码活动
    public function getThisActivity(){
        return SetPerfectCrab::model()->find('state = 0 and start_time like "%'.date('Y-m-d',time()).'%"');
    }
    
    //获取下个小时抢号码活动
    public function getNextHour(){
        //获取最后一个号码活动
        $criteria =new CDbCriteria;
        $criteria->order = "id desc";
        $last = self::model()->find($criteria);
        if(time()+self::$act_time > strtotime($last->end_time)){
            return false;
        }        
        self::$act_time += 86400; //1*24*60*60
        $nextHour = SetPerfectCrab::model()->find('state = 0 and start_time like "%'.date('Y-m-d',time()+self::$act_time).'%"');
        if(!$nextHour){
            return $this->getNextHour();
        }else{
            return $nextHour;
        }
    }
    
    
    //获取下一场抢号码活动
    public function getNextActivity(){
        $thisAct = $this->getThisActivity(); 
        if(!$thisAct){
            return $this->getNextHour();
        }else{
            $nextId = $thisAct->id + 1;
            return $this->getAct($nextId);
        }        
    }
    
    public function getAct($activity_id){
        //获取最大Id
        $criteria =new CDbCriteria;
        $criteria->order = "id desc";
        $lastId = self::model()->find($criteria)->id;
        if($activity_id  <= $lastId){
            self::$act_id = $activity_id;
            $model = SetPerfectCrab::model()->findByPk(self::$act_id);
            if($model){
                if($model->state == 1){
                    self::$act_id++;
                    return $this->getAct(self::$act_id);
                }else{
                    return $model;
                }
            }else{
                self::$act_id++ ;
                return $this->getAct(self::$act_id);
            }
        }else{
            return false;
        }
    }
   

    public function getCrabList(){
        $CrabList = array();
        $CrabModel = SetPerfectCrab::model()->findAll();
        foreach($CrabModel as $_v){
            $CrabList[$_v->id] = $_v->activity_name;
        }
        return $CrabList;
    }



    public function checkResult($attribute,$params){
        if(empty($this->right_result)){
                $this->addError($attribute,"录入结果不能为空！");
        }
        // if (!empty($this->right_result) && (!is_numeric($this->right_result) || strlen($this->right_result)!=5)){
        //         $this->addError($attribute,"您录入的正确结果格式有误！");
        // }
    }
 

    
    
}