<?php
class DreamVoteController extends CController {
    private $_userId = 0;
    private $_usermobile = 0;
    private $_dreamActId = Item::DREAM_ACT_ID;

    public function actionIndex(){
        $this->checkLogin();
        // $activityName = '';
        $count = 0;
        $activity = DreamActivity::model()->findByPk(Item::DREAM_ACT_ID);
        // $activityName = $activity->name;
        $activityPeople = DreamVote::model()->findAll("activity_id=".$this->_dreamActId." and is_deleted=0");
        $count = DreamVote::model()->count("activity_id=".$this->_dreamActId." and is_deleted=0");
        if($this->underway()){
            $status = 1;
        }else{
            $status = $this->isover()?2:0;
        }
        foreach($activityPeople as $k => $v){            
            $dream = DreamVoteLog::model()->find(" employee_id=:employee_id and dream_vote_id=:dream_vote_id and from_unixtime(vote_time,'%Y%m%d')='".date('Ymd')."' ", array(':employee_id'=>$this->_userId, ':dream_vote_id'=>$v->id));
            $isJoin = !empty($dream)?1:0;
            $v->flag = $isJoin;
            $d[] = $v;
        }
        $k = floor((strtotime($activity->end_date." 23:59:59")-time())/(60*60*24));
        $a = $k==0?1:$k;
        $this->renderPartial("dreamlook",array(
                'list' => $d,
                'count' => $count,
                // 'activityName' => $activityName,
                'status' => $status,
                'day' => $a,
            ));
    }


    public function actionView($id){
        $this->checkLogin();
        //$activityName = '';
        $activity = DreamActivity::model()->findByPk(Item::DREAM_ACT_ID);
        // $activityName = $activity->name;
        $dream = DreamVote::model()->findByPk($id);
        $model = DreamVoteLog::model()->find(" employee_id=:employee_id and dream_vote_id=:dream_vote_id and from_unixtime(vote_time,'%Y%m%d')='".date('Ymd')."' ", array(':employee_id'=>$this->_userId,':dream_vote_id'=>$id));
        $flag = !empty($model)?1:0;
        $k = floor((strtotime($activity->end_date." 23:59:59")-time())/(60*60*24));
        $a = $k==0?1:$k;
        if($this->underway()){
            $status = 1;
        }else{
            $status = $this->isover()?2:0;
        }           
        $this->renderPartial("dream_details",array(
                'model' => $dream,
                'flag' => $flag,
                'day' => $a,
                'status' => $status,
                // 'activityName' => $activityName,
            ));
    }


    public function actionRule(){
        $this->checkLogin();        
        $this->renderPartial("dream_rule");
    }


    private function isover(){ //TRUE已经结束
        $voteAct = DreamActivity::model()->findByPk($this->_dreamActId);
        if($voteAct && ($voteAct->end_date." 23:59:59" < date("Y-m-d H:i:s"))){
            return true;
        }
        return false;
    }

    private function nostart(){
        $voteAct = DreamActivity::model()->findByPk($this->_dreamActId);
        if($voteAct && ($voteAct->start_date." 00:00:00" > date("Y-m-d H:i:s"))){
            return true;
        }
        return false;
    }


    private function underway(){
        $voteAct = DreamActivity::model()->findByPk($this->_dreamActId);
        if($voteAct && ($voteAct->start_date." 00:00:00" <= date("Y-m-d H:i:s")) && ($voteAct->end_date." 23:59:59" >= date("Y-m-d H:i:s"))){
            return true;
        }
        return false;
    }




    public function actionDoVote() {
        $this->checkLogin();
        if(!$this->underway()||!isset($_POST["id"])){
            exit();
        }
        if(empty($_POST["id"])||!is_numeric($_POST["id"])){
            exit();
        }
        $_id = $_POST["id"];
        $model = DreamVote::model()->findByPk($_id);
        if(!$model){
            exit();
        }
        $dream = DreamVoteLog::model()->find(" employee_id=:employee_id and from_unixtime(vote_time,'%Y%m%d')='".date('Ymd')."' ", array(':employee_id'=>$this->_userId));

        $dream2 = DreamVoteLog::model()->count(" vote_ip=:ip and dream_vote_id=:dream_vote_id and from_unixtime(vote_time,'%Y%m%d')='".date('Ymd')."' ", array(':ip'=>Yii::app()->getRequest()->getUserHostAddress(),':dream_vote_id'=>$_id));

        if($dream){
           $result = 0;           
        }else{
            if ($dream2>=3) {
                $result = 2;
            }else{
                $vote = new DreamVoteLog;
                $vote->dream_vote_id = $_id;
                $vote->employee_id = $this->_userId;
                $vote->vote_time = time();
                $vote->vote_ip = Yii::app()->getRequest()->getUserHostAddress();
                if (isset($_POST["id"])) {
                    //$vote->setScenario($this->action->id);
                    if ($vote->save()){
                        $votes = $model->votes+1;
                        DreamVote::model()->updateByPk($_id,array('votes'=>$votes));
                        $result = 1;
                    }
                       
                }
            }
            
        }
        echo CJSON::encode ( array('data'=>$result) );
    }


    public function actionActivitySwitch() {
        if($_GET["type"]==0 || $_GET["type"]==1 || $_GET["type"]==2){
            $code=$_GET["type"];
            Command::model()->updateByPk(3, array('IsExce'=>$code));
            echo CJSON::encode(array('status'=>1));
            // }else{
            //     echo CJSON::encode(array('status'=>1));
            // }
        }else{
            throw new CHttpException(400,"参数不正确");
        }

    }    


    private function checkLogin(){
        if(empty($_REQUEST['employee_id']) && empty(Yii::app()->session['employee_id'])) {
            exit('<h1>员工信息错误，请重新登录</h1>');
        }else {
            $employeeId=0;
            ini_set('session.gc_maxlifetime', 3600*12); //设置时间
            if(isset($_REQUEST['employee_id'])){  //优先有参数的
                $employeeId=intval($_REQUEST['employee_id']); 
                Yii::app()->session['employee_id']=$employeeId;
            }else if(isset(Yii::app()->session['employee_id'])){  //没有参数，从session中判断
                $employeeId=Yii::app()->session['employee_id'];
            }
            $employeeId=intval($employeeId);
            $employee=Employee::model()->findByPk($employeeId);
            if(empty($employeeId) || empty($employee)){
                exit('<h1>员工信息错误，请重新登录</h1>');
            }
            
            //$this->_dreamActId = isset ( $_REQUEST ['actid'] ) ? ($_REQUEST ['actid']) : Item::DREAM_ACT_ID;
            $this->_dreamActId = isset ( $_REQUEST ['actid'] ) ? (intval($_REQUEST ['actid'])) : Item::DREAM_ACT_ID;
            if($this->_dreamActId!=Item::DREAM_ACT_ID){
                exit('<h1>投票程序出错，请联系管理员</h1>');
            }
            $activity = DreamActivity::model()->findByPk($this->_dreamActId);
            if(empty($activity) || ($activity&&$activity->isdelete==1)){
                exit('<h4>活动异常</h4>');
            }
            $this->_userId = $employeeId;
        }
    }


}