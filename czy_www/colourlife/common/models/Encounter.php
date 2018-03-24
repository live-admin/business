<?php
/*
 * author liangjianfeng
 * 配置世界杯对阵表
 */
class Encounter extends CActiveRecord{
    
    public $modelName = "世界杯对阵信息";
        
    const OUTCOME_HOME = 1;      //主队胜利
    const OUTCOME_GUEST = 2;     //客队胜利
    const OUTCOME_TIE = 3;       //平局
    
    const DEAL_NO = 0;           //未处理
    const DEAL_YES = 1;          //已处理
    public function tableName() {
        return 'encounter';
    }
    
    public static function model($className = __CLASS__) {
       return parent::model($className);
    }
    
    public function rules() {
        return array(
            array('team_one_id,team_two_id,start_time,game_number,start_quiz,end_quiz','required','on' => 'create,update'),
            array('outcome','safe','on' => 'update'),
            array('game_number','safe','on' => 'search'),
            array('deal','required','on' => 'deal'),
        );
    }
    
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('game_number', $this->game_number, true);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'team_one_id' => '主队',
            'team_two_id' => '客队',
            'start_time' => '比赛开始时间',
            'start_time_ymd' => '比赛开始时间年月日',
            'start_time_his' => '比赛开始时间时分秒',
            'outcome' => '比赛结果',
            'game_number' => '场次',
            'start_quiz' => '竞猜开始时间',
            'start_quiz_ymd' => '竞猜开始时间年月日',
            'start_quiz_his' => '竞猜开始时间时分秒',            
            'end_quiz' => '竞猜结束时间',
            'end_quiz_ymd' => '竞猜结束时间年月日',
            'end_quiz_his' => '竞猜结束时间时分秒',
            'HomeTeam' => '主队',
            'GuestTeam' => '客队',
            'HomeTeamLogo' => '主队国旗',
            'GuestTeamLogo' => '客队国旗',
            'deal' => '是否处理',
        );
    }
    
    public function getStart_quiz_ymd(){
        if($this->start_quiz){
            return date("Y-m-d",strtotime($this->start_quiz));
        }else{
            return date("Y-m-d",time());
        }
    }
    public function getStart_quiz_his(){
        return date("h:i A",strtotime($this->start_quiz));
    }
    
    public function getEnd_quiz_ymd(){
        if($this->end_quiz){
            return date("Y-m-d",strtotime($this->end_quiz));
        }else{
            return date("Y-m-d",time());
        }
    }
    public function getEnd_quiz_his(){
        return date("h:i A",strtotime($this->end_quiz));
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
    
    public function getOutComeNames(){
        return array(
            self::OUTCOME_HOME => '主队胜利',
            self::OUTCOME_GUEST => '客队胜利',
            self::OUTCOME_TIE => '平局',
        );
    }
    
    public function getOutComeName(){
        switch ($this->outcome){
            case self::OUTCOME_HOME:
                return "主队胜利";
                break;
            case self::OUTCOME_GUEST:
                return "客队胜利";
                break;
            case self::OUTCOME_TIE:
                return "平局";
                break;
            default :
                return "N/A";
                break;
        }
    }
    
    public function getDealName(){
        switch ($this->deal){
            case self::DEAL_NO:
                return "未处理";
                break;
            case self::DEAL_YES:
                return "已处理";
                break;
            default :
                return "未知";
        }
    }
    
    public function getOutComeNameInCss(){
        switch ($this->outcome){
            case self::OUTCOME_HOME:
                return "<span class='label label-success'>主队胜利</span>";
                break;
            case self::OUTCOME_GUEST:
                return '<span class="label label-success">客队胜利</span>';
                break;
            case self::OUTCOME_TIE:
                return '<span class="label label-success">平局</span>';
                break;
            default :
                return "<span class='label label-success'>N/A</span>";
                break;
        }
    }
    
    //获取主队名称
    public function getHomeTeam(){
        if ($this->team_code === null) {
            return '-';
        }
        return $this->team_code->team;
    }
    
    //获取客队名称
    public function getGuestTeam(){
        if ($this->team_code2 === null) {
            return '-';
        }
        return $this->team_code2->team;
    }
    
    public function getTeamList(){
        $teamList = array();
        $teamList[0] = "-";
        $teamsModel = TeamCode::model()->findAll(array('select' => array('team')));
        foreach($teamsModel as $_v){
            $teamList[] = $_v->team;
        }
        return $teamList;
    }
    
   
    
    public function relations()
    {
        return array(
            'team_code' => array(self::BELONGS_TO, 'TeamCode', 'team_one_id'),
            'team_code2' => array(self::BELONGS_TO, 'TeamCode', 'team_two_id'),
        );
    }
    
    public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
        );
    }
    
    //获取主队国旗
    public function getHomeTeamLogo(){
        if ($this->team_code === null) {
            return '';
        }
        return Yii::app()->ajaxUploadImage->getUrl($this->team_code->logo);
    }
    
    //获取客队国旗
    public function getGuestTeamLogo(){
        if ($this->team_code2 === null) {
            return '';
        }
        return Yii::app()->ajaxUploadImage->getUrl($this->team_code2->logo);
    }
    
    /**
     * 获取当前时间能竞猜的赛事
     */
    public function getAllEncounterAtNow(){
        $now_time = date("Y-m-d H:i:s");
        $criteria=new CDbCriteria;  
        $criteria->condition='start_quiz<=:start_quiz and end_quiz>=:end_quiz';  
        $criteria->params=array(':start_quiz'=>$now_time,':end_quiz'=>$now_time);  
        $criteria->order='start_time ASC';  
        return self::model()->findAll($criteria); 
    }
}
