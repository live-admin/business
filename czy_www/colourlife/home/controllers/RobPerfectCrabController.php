<?php
/* class RobPerfectCrabController extends CController {
    private $_userId = 0;

    public function actionIndex(){

        $status = $this->new_act_status();
        //var_dump($status);
        $this->checkLogin();
        $timeRemainingNext = $this->getNextTime();   //下一场完美蟹逅活动   格式"2014-09-24 05:01:13" 
        //var_dump($timeRemainingNext);
        if($status == 3){
            $timeRemaining = $this->getTimeRemaining();          //本场剩余时间
            $m = intval (date("m",strtotime($timeRemaining)));   //月
            $d = intval (date("d",strtotime($timeRemaining)));   //日
            $h = intval (date("H",strtotime($timeRemaining)));   //时
            $i = intval (date("i",strtotime($timeRemaining)));   //分
            $s = intval (date("s",strtotime($timeRemaining)));   //秒
        }

        if($status == 1 || $status == 2){           
            $m = intval (date("m",strtotime($timeRemainingNext)));   //月
            $d = intval (date("d",strtotime($timeRemainingNext)));   //日
            $h = intval (date("H",strtotime($timeRemainingNext)));   //时
            $i = intval (date("i",strtotime($timeRemainingNext)));   //分
            $s = intval (date("s",strtotime($timeRemainingNext)));   //秒 

        }
        $allJoin=PerfectCrabOutcome::model()->count();
        if(date('Y-m-d H:i:s',time())>="2014-10-09 23:59:59"){ //1411747199
             $this->renderPartial("crab3",
                  array('m' => $m,
                      'd' => $d,
                      'h' => $h,
                      'i' => $i,
                      's' => $s,
                      'timeRemainingNext' => $timeRemainingNext,    //下场完美蟹逅活动（时间）
                      'status' => $status,
                      'allJoin' => $allJoin+1123,
                      ));
        }else{
             $this->renderPartial("crab2",
                  array('m' => $m,
                      'd' => $d,
                      'h' => $h,
                      'i' => $i,
                      's' => $s,
                      'timeRemainingNext' => $timeRemainingNext,    //下场完美蟹逅活动（时间）
                      'status' => $status,
                      'allJoin' => $allJoin+1123,
                      ));

        }    
    }


    public function actionRule(){
        $this->checkLogin();
        if(date('Y-m-d H:i:s',time())>="2014-10-09 23:59:59"){
            $this->renderPartial("crab_rule4");
        }else{
            $this->renderPartial("crab_rule3");
        }
        
    }
    
    
    
    public function actionRob(){
        if (!Yii::app()->getRequest()->getIsPostRequest())
            throw new CHttpException(405, 'Method Not Allowed');
        $this->checkLogin();
        $this_act = SetPerfectCrab::model()->getThisActivity();
        if($this_act){
            $this_act->clicks+=1;
            $this_act->save();
            $customerModel=Customer::model()->findByPk($this->_userId);
            if($customerModel->balance<0.1){
               echo CJSON::encode(array('ok' =>3));//红包余额不足   
            }else{
                $res = PerfectCrabOutcome::model()->rob($this_act->id,$this->_userId);
                echo CJSON::encode($res);     //0没抢到(没扣红包)；1抢到了；2抢光了(号码不够)；3红包金额不足 5没抢到(扣了红包)
            }    
        }else
            echo CJSON::encode(array('ok' =>4));//4活动暂未开始
    }        
        
    
    //获取本场剩余时间
    public function getTimeRemaining(){
        $model = SetPerfectCrab::model()->getThisActivity();
        return $model?$model->end_time:date("Y-m-d H:i:s");
    }
    
    //获取距离下场开始时间
    public function getNextTime(){
        $model = SetPerfectCrab::model()->getNextActivity();
        //var_dump($model);
        //return $model?$model->start_time:date("Y-m-d H:i:s");
        return $model?$model->start_time:$model;
    }
    
    private function checkLogin(){
        if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])) {
        	exit('<h1>用户信息错误，请重新登录</h1>');
		}else {
			$custId=0;
			if(isset($_REQUEST['cust_id'])){  //优先有参数的
				$custId=intval($_REQUEST['cust_id']);
				$_SESSION['cust_id']=$custId;
			}else if(isset($_SESSION['cust_id'])){  //没有参数，从session中判断
				$custId=$_SESSION['cust_id'];
			}
			$custId=intval($custId);
			$customer=Customer::model()->findByPk($custId);
			if(empty($custId) || empty($customer)){
				exit('<h1>用户信息错误，请重新登录</h1>');
			}
            $this->_userId = $custId; 
		}
    }



   

     public function actionCrabResult(){
        $this->checkLogin();

        //中奖用户
        $listResut = $this->getListData(); 


        $criteria = new CDbCriteria;
        //$criteria->addCondition("customer_id=".$this->_userId); 
        $criteria->compare('customer_id', $this->_userId);
        $criteria->order = "id desc";
        $data=PerfectCrabOutcome::model()->findAll($criteria);
        $list=array();
        foreach ($data as $key=>$value){
            $list[$key]['id'] = $value->id;
            $list[$key]['mycode'] = perfectCrabOutcome::model()->getFullDrawCode($value->mycode);
            $list[$key]['is_right'] = $value->is_right;
            $list[$key]['lucky_date'] = date('Y-m-d H:i:s',$value->create_time);
            $list[$key]['right_result'] = perfectCrabOutcome::getFullDrawCode($value->set_perfect_crab->right_result);
        }

        $this->renderPartial("crab_result",
                array("list"=>$list,'listResut'=>$listResut)
        );
    }


    public function getListData(){
        $criteria = new CDbCriteria;
        $criteria->addCondition("is_right=1"); 
        $criteria->order ="id desc"; 
        $criteria->limit = 20; 
        $dataResult = PerfectCrabOutcome::model()->findAll($criteria);
        $list = array();   
        for($i=0;$i<count($dataResult);$i++){
            $name=empty($dataResult[$i]->customer->name)?(""):(F::msubstr($dataResult[$i]->customer->name,0,1)."**"); 
            $mobile = F::msubstr($dataResult[$i]->customer->mobile,0,3)."****".F::msubstr($dataResult[$i]->customer->mobile,7,4);        
            $list[]=array(
                'msg'=>"恭喜手机号".$mobile."的业主".$name."获得了大闸蟹一份",
            );
    
        }
        return $list;
    }







    public function actionFlushByAjax(){
        $over = $this->isOver();
        $timeRemainingNext = $this->getNextTime();   //下一场完美蟹逅活动 
        $timeRemaining = $this->getTimeRemaining();        //本场剩余时间
        $m = intval (date("m",strtotime($timeRemaining)));   //月
        $d = intval (date("d",strtotime($timeRemaining)));   //日
        $h = intval (date("H",strtotime($timeRemaining)));   //时
        $i = intval (date("i",strtotime($timeRemaining)));   //分
        $s = intval (date("s",strtotime($timeRemaining)));   //秒
        $res = array();
        $res['success'] = "ok";
        $res['timeRemainingNext'] = $timeRemainingNext?date("d日H",strtotime($timeRemainingNext)):date("d日H");   //下场完美蟹逅活动
        $res['m'] = $m;
        $res['d'] = $d;
        $res['h'] = $h;
        $res['i'] = $i;
        $res['s'] = $s;
        $res['over'] = $over;
        echo CJSON::encode($res);
    }
    
    //判断活动是不是已经结束 //万一禁用，需改善
    public function isOver(){
        $lastModel = SetPerfectCrab::model()->find(array('order'=>'id desc'));
        if(date("Y-m-d H:i:s") >= $lastModel->end_time){
            return true;
        }else{
            return false;
        }
    }


    //活动进行中  1、未开始  2、已结束  3、进行中
    public function act_status(){
        $startModel = SetPerfectCrab::model()->find(array('condition'=>'state=0 and is_deleted=0','order'=>'id asc'));
        $lastModel = SetPerfectCrab::model()->find(array('condition'=>'state=0 and is_deleted=0', 'order'=>'id desc'));
        if(date("Y-m-d H:i:s") < $startModel->start_time){
            return 1;
        }else if(date("Y-m-d H:i:s") > $lastModel->end_time){
            return 2;
        }else{
            return 3;
        }
    }


       //活动进行中  1、未开始  2、已结束  3、进行中
    public function new_act_status(){
        $startModel = SetPerfectCrab::model()->find(array('condition'=>'id>=14 and state=0 and is_deleted=0','order'=>'id asc'));
        $lastModel = SetPerfectCrab::model()->find(array('condition'=>'state=0 and is_deleted=0', 'order'=>'id desc'));
        //var_dump($lastModel);
        if(date("Y-m-d H:i:s") < $startModel->start_time){
            return 1;
        }else if(date("Y-m-d H:i:s") > $lastModel->end_time){
            return 2;
        }else{
            return 3;
        }
    }    
    
} */