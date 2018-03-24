<?php

class DreamVoteLog extends CActiveRecord
{
    
    public $modelName = "投票记录";


    public function tableName() {
        return "dream_vote_log";
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function rules() {
         return array(
             array('dream_vote_id,employee_id,vote_time,vote_ip','required','on'=>'create'),
             array('dream_vote_id,employee_id,vote_time,vote_ip','safe','on'=>'search'),
             //array('employee_id,dream_vote_id,', 'checkRepeat', 'on' => 'create,update,doVote'),
         );
     }
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'dream_vote_id' => 'DreamVoteId',
            'employee_id' => '员工Id',
            'vote_time' => '投票时间',
            'vote_ip' => '投票IP',
            'EmployeeName' => '员工姓名',
            'EmployeeMobile' => '员工手机',
            'EmployeeBranch' => '员工部门',
            'EmployeeJob' => '员工职位',
        );
    }


    // public function checkRepeat($attribute,$params)
    // {
    //     $voteLog = DreamVoteLog::model()->find(" employee_id=:employee_id and from_unixtime(vote_time,'%Y%m%d')='".date('Ymd')."' ", array(':employee_id'=>$this->employee_id));
    //     if(!$this->hasErrors()&&$voteLog)
    //         $this->addError($attribute,"您今天已经投过一票，明天再来吧！");

    // }



    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('dream_vote_id', $this->dream_vote_id);
        $criteria->compare('employee_id', $this->employee_id);
        $criteria->compare('vote_time', $this->vote_time);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function log_search($id)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('dream_vote_id', $id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    public function getEmployeeName(){
        return $this->employee?$this->employee->name:"";
    }

    public function getEmployeeJob(){
        return $this->employee?$this->employee->job_name:"";
    }

    public function getVoteTime(){
        return date('Y-m-d H:i:s',$this->vote_time);
    }

    public function getEmployeeBranch(){
        $data = EmployeeBranchRelation::model()->findAll("employee_id=" . $this->employee_id);
        if (!empty($data) && is_array($data)) {
            $branch_name = "";
            foreach ($data as $val) {
                $branch_name .= Branch::getMyParentBranchName($val['branch_id'], true) . " ";
            }
            return $branch_name;
        } else if (empty($data)) {
            return "-";
        }
    }
    
    public function getEmployeeMobile(){
        return $this->employee?$this->employee->mobile:"";
    }
    
    public function relations()
    {
        return array(
            'dream_vote' => array(self::BELONGS_TO, 'DreamVote', 'dream_vote_id'),
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
        );
    }
    
    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    
    
}
