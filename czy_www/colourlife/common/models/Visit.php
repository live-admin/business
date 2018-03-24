<?php

class Visit extends CActiveRecord{
    
    public $modelName = "拜访";
    
    public $customer_name;
    public $customer_mobile;
    public $start_time_klint;
    public $end_time_klint;
    public $code;
    public $evaluation_startTime;
    public $evaluation_endTime;
    
    const STATUS_UNTREATED = 0;   //未处理
    const STATUS_AGREE = 1;      //同意拜访
    const STATUS_REFUSE = 2;     //拒绝拜访
    const STATUS_NOCOMMENTS =3 ;  //未评价 
    const STATUS_EVALUATION = 4;  //已评价

    const EVALUATE_NO = 0;                  //未评价
    const EVALUATE_GREATE_SATISFACTION = 1; //非常满意
    const EVALUATE_STATISFACTION = 2;       //满意
    const EVALUATE_ORDINARY = 3;            //一般
    const EVALUATE_YAWP = 4;                //不满意
    
    const COMPLAIN_NO = 0;      //否(不投诉)
    const COMPLAIN_YES = 1;     //是(投诉)

    public function tableName() {
        return "visit";
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function rules() {
         return array(
             array('customer_name,customer_mobile,status,evaluation_id,evaluation,code,evaluation_startTime,evaluation_endTime','safe','on'=>'search'),
         );
     }
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'customer_id' => '业主Id',
            'employee_id' => '员工Id',
            'status' => '状态',
            'invite_visit_time' => '预约拜访时间',
            'invite_visit_hour' => '预约上门时间',
            'create_time' => '创建时间',
            'reply_time' => '回复时间',
            'visit_time' => '拜访时间',
            'evaluation_time' => '评价时间',
            'refuse' => '拒绝内容',
            'content' => '拜访记录',
            'visit_content' => '拜访内容',
            'evaluation_id' => '评价',
            'evaluation' => '评价内容',
            'complain' => '投诉内容',
            'is_complain' => '是否投诉',
            'CustomerName' => '业主姓名',
            'CustomerMobile' => '业主手机',
            'EmployeeName' => '员工姓名',
            'EmployeeMobile' => '员工手机',
            'customer_name' => '业主姓名',
            'customer_mobile' => '业主手机',
            'VisitDateTime' => '预约拜访时间',
            'start_time_klint' => '开始时间',
            'end_time_klint' => '截止时间',
			'code' => '验证码',
            'evaluation_startTime' => '评价开始时间',
            'evaluation_endTime' => '评价结束时间',
        );
    }
    
    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('evaluation', $this->evaluation);
        $criteria->compare('status', $this->status);
        if ($this->evaluation_startTime != '') {
            $criteria->compare("`t`.evaluation_time", ">= " . strtotime($this->evaluation_startTime ." 00:00:00"));
        }
        if ($this->evaluation_endTime != '') {
            $criteria->compare("`t`.evaluation_time", "<= " . strtotime($this->evaluation_endTime." 23:59:59"));
        }
        if($this->customer_name || $this->customer_mobile){
            $criteria->with=array(  
                'customer',  
            );
            $criteria->compare('customer.name', $this->customer_name, true);
            $criteria->compare('customer.mobile', $this->customer_mobile, true);
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    public function getStatusNames(){
        return array(
            self::STATUS_UNTREATED => '未处理',
            self::STATUS_AGREE => '同意',
            self::STATUS_REFUSE => '拒绝',
            self::STATUS_NOCOMMENTS => '未评价',
            self::STATUS_EVALUATION => '已评价',
        );
    }
    
    public function getStatusName($html = false){
        $res = '';
        $res .= ($html) ? '<span class="label label-success">' : '';
        $res .= $this->StatusNames[$this->status];
        $res .= ($html) ? '</span>' : '';
        return $res;        
    }
    
    public function getEvaluationNames(){
        return array(
            self::EVALUATE_NO => '未评价',
            self::EVALUATE_GREATE_SATISFACTION => '非常满意',
            self::EVALUATE_STATISFACTION => '比较满意',
            self::EVALUATE_ORDINARY => '一般',
            self::EVALUATE_YAWP => '不满意',
        );
    }
    
    public function getEvaluationName($html = false){
        $res = '';
        $res .= ($html) ? '<span class="label label-success">' : '';
        $res .= $this->EvaluationNames[$this->evaluation_id];
        $res .= ($html) ? '</span>' : '';
        return $res;        
    }
    
    public function getIsComplain(){
        switch ($this->is_complain){
            case self::COMPLAIN_NO:
                return "否";
                break;
            case self::COMPLAIN_YES:
                return "是";
                break;
            default :
                return "未知";
        }
    }
    
    public function getVisitDateTime(){
        return $this->invite_visit_time."　".$this->invite_visit_hour;
    }
    
    public function getCustomerName(){
        return $this->customer?$this->customer->name:"";
    }
    
    public function getCustomerMobile(){
        return $this->customer?$this->customer->mobile:"";
    }

    public function getEmployeeName(){
        return $this->employee?$this->employee->name:"";
    }
    
    public function getEmployeeMobile(){
        return $this->employee?$this->employee->mobile:"";
    }
    
    public function relations()
    {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
        );
    }
    
    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }
    
    //获取客户经理所属的小区
    public function  getCommunityData($employee_id){
        $relation = EmployeeBranchRelation::model()->findAll('employee_id=:employee_id',array(':employee_id' => $employee_id));
        if(!$relation){
            return false;
        }
        $communityData = array();
        foreach($relation as $_k => $_v){
            $communitys = Community::model()->findAll('branch_id=:branch_id',array(':branch_id'=>$_v->branch_id));            
            
            foreach($communitys as $community){                
                if($community && $_v->branch_id != 599){
                    $communityData[$community->domain]['id'] = $community->id;
                    $communityData[$community->domain]['name'] = $community->name;                
                }
            }
        }
        return $communityData;        
    }
    
    public function visitSearch($employee_id,$type=0){
        $resultArr = array();
        if($type == 0){ //本月
            $start_date = date("Y-m")."-01";
            $end_date = date("Y-m")."-31";            
        }else{          //上月
            // $m = date("m")-1;
            // $start_date = date("Y")."-".$m."-01";
            // $end_date = date("Y")."-".$m."-31"; 
            //echo date("Y-m",strtotime("-1 month"));die;
            $start_date = date("Y-m",strtotime("-1 month"))."-01";
            //echo $end_date = date("Y-m",strtotime("-1 month"))."-31"; die;
            //echo date("Y-m",strtotime("-1 month"));die;
            //echo date("Y-m",strtotime("-1 month"))."-01";die;
            $end_date = date('Y-m-d',strtotime($start_date."+1 month"."-1 day"));
        }
        $resultArr['all'] = Visit::model()->count("employee_id=".$employee_id." and invite_visit_time>='".$start_date."' and invite_visit_time<='".$end_date."'");
        $resultArr['visited'] = Visit::model()->count("status > 2 and employee_id=".$employee_id." and invite_visit_time>='".$start_date."' and invite_visit_time<='".$end_date."'");
        $resultArr['reject'] = Visit::model()->count("status = 2 and employee_id=".$employee_id." and invite_visit_time>='".$start_date."' and invite_visit_time<='".$end_date."'");
        $resultArr['veryGood'] = Visit::model()->count("evaluation_id = 1 and employee_id=".$employee_id." and invite_visit_time>='".$start_date."' and invite_visit_time<='".$end_date."'");
        $resultArr['good'] = Visit::model()->count("evaluation_id = 2 and employee_id=".$employee_id." and invite_visit_time>='".$start_date."' and invite_visit_time<='".$end_date."'");
        $resultArr['common'] = Visit::model()->count("evaluation_id = 3 and employee_id=".$employee_id." and invite_visit_time>='".$start_date."' and invite_visit_time<='".$end_date."'");
        $resultArr['bad'] = Visit::model()->count("evaluation_id = 4 and employee_id=".$employee_id." and invite_visit_time>='".$start_date."' and invite_visit_time<='".$end_date."'");
        $resultArr['no'] = Visit::model()->count("evaluation_id = 0 and employee_id=".$employee_id." and invite_visit_time>='".$start_date."' and invite_visit_time<='".$end_date."'");
        return $resultArr;
    }
    
    public function getVisitReportCount($employee_id){
        $resultArr = array();
        $resultArr['agree'] = Visit::model()->count("status = 1 and employee_id=".$employee_id);
        $resultArr['untreated'] = Visit::model()->count("status = 0 and employee_id=".$employee_id);
        $resultArr['reject'] = Visit::model()->count("status = 2 and employee_id=".$employee_id);
        $resultArr['noEvaluation'] = Visit::model()->count("status = 3 and employee_id=".$employee_id);
        $resultArr['evaluation'] = Visit::model()->count("status = 4 and employee_id=".$employee_id);
        return $resultArr;
    }
    
    
}
