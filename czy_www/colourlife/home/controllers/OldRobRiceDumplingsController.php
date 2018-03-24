<?php
/* class RobRiceDumplingsController extends CController {
    private $_userId = 0;
    
    public function actionIndex(){
        $this_act = SetRiceDumplings::model()->getThisActivity();
        $over = $this->isOver();
        if($this_act){
            $this_count = $this_act->remaining_number;
        }else{
            $this_count = 0;
        }
        $this->checkLogin();
        $able = 1;      //1代表活动正在进行     0代表休息
        $timeRemainingNext = $this->getNextTime();   //下一场抢粽子活动 
        if(8<=date("H") && date("H")<=22 && date("i") < 50){
            $timeRemaining = $this->getTimeRemaining();        //本场剩余时间
            $m = intval (date("m",strtotime($timeRemaining)));   //月
            $d = intval (date("d",strtotime($timeRemaining)));   //日
            $h = intval (date("H",strtotime($timeRemaining)));   //时
            $i = intval (date("i",strtotime($timeRemaining)));   //分
            $s = intval (date("s",strtotime($timeRemaining)));   //秒
            if($this_count == 0){                              //本场抢完   开始倒计时下一场
                $able = 0;
                $m = intval (date("m",strtotime($timeRemainingNext)));       //月
                $d = intval (date("d",strtotime($timeRemainingNext)));       //日
                $h = intval (date("H",strtotime($timeRemainingNext)));   //时
                $i = intval (date("i",strtotime($timeRemainingNext)));   //分
                $s = intval (date("s",strtotime($timeRemainingNext)));   //秒                 
            }
        }else{
            $able = 0;
            $m = intval (date("m",strtotime($timeRemainingNext)));       //月
            $d = intval (date("d",strtotime($timeRemainingNext)));       //日
            $h = intval (date("H",strtotime($timeRemainingNext)));   //时
            $i = intval (date("i",strtotime($timeRemainingNext)));   //分
            $s = intval (date("s",strtotime($timeRemainingNext)));   //秒            
            
        }
        $this->renderPartial("index",
          array('newInfo'=> RiceDumplingsResult::model()->getNewInfo(),  //最新中奖信息
              'remaining' => $this->getRemaining(),  //本场剩余个数
              //'nextActivity' => SetRiceDumplings::model()->getNextActivity(),   //下一场抢粽子活动
              'm' => $m,
              'd' => $d,
              'h' => $h,
              'i' => $i,
              's' => $s,
              'able' => $able,
              'this_count' => $this_count,
              'timeRemainingNext' => $timeRemainingNext,    //下场抢粽子活动
              'over' => $over,
              ));
    }
    
    
    
    public function actionRob(){
        $this->checkLogin();
        $this_act = SetRiceDumplings::model()->getThisActivity();
        $res = RiceDumplingsResult::model()->rob($this_act->id,$this->_userId);
        echo CJSON::encode($res);     //0没抢到；1抢到了；2今天已抢过不能再抢
    }        
        
    
    //获取本场剩余份数
    public function getRemaining(){
        $activity = SetRiceDumplings::model()->getThisActivity();        
        if($activity){
            if($activity->remaining_number == 5 || $activity->remaining_number == 10){
                $remaining_number = $activity->remaining_number."0";
            }else{
                if($activity->remaining_number == 0){
                    $remaining_number = 0;
                }else{
                    $remaining_number = ($activity->remaining_number>1)?($activity->remaining_number-1).rand(0,9):rand(0,9);
                }
            }
        }else{
            $remaining_number = 0;
        }
        return $remaining_number;        
    }
    
    //获取本场剩余时间
    public function getTimeRemaining(){
        $model = SetRiceDumplings::model()->getThisActivity();
        return $model?$model->end_time:date("Y-m-d H:i:s");
    }
    
    //获取距离下场开始时间
    public function getNextTime(){
        $model = SetRiceDumplings::model()->getNextActivity();
        return $model?$model->start_time:date("Y-m-d H:i:s");
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
    
    
    //获取业主地址、手机号、名字
    public function actionGetCustomerInfo(){
        $this->checkLogin();
        $res = array();
        $customerModel = Customer::model()->findByPk($this->_userId);
        if($customerModel){
            $res['tel'] = $customerModel->mobile;
            $res['name'] = $customerModel->name;
            $res['address'] = $customerModel->community->name.$customerModel->build->name.$customerModel->room;
        }
        echo CJSON::encode($res);
    }
    
    //确定收粽子地址
    public function actionFillReceiving(){ 
        $this->checkLogin();
        $linkman = $_POST['linkman'];
        $tel = $_POST['tel'];
        $criteria =new CDbCriteria;
        //$criteria->addCondition("customer_id=".$this->_userId); 
        $criteria->compare('customer_id', $this->_userId);
        $criteria->order = "id desc";
        $model = RiceDumplingsResult::model()->find($criteria);
        $model->linkman = $linkman;
        $model->tel = $tel;
        $model->address = $model->CustomerAddress;
        $model->save();
        echo CJSON::encode(1);
    }
    
    
    public function actionFlushByAjax(){
        $this_act = SetRiceDumplings::model()->getThisActivity();
        $over = $this->isOver();
        if($this_act){
            $this_count = $this_act->remaining_number;
        }else{
            $this_count = 0;
        }
        $able = 1;      //1代表活动正在进行/0代表休息
        $timeRemainingNext = $this->getNextTime();   //下一场抢粽子活动 
        if(8<=date("H") && date("H")<=22 && date("i") < 50){
            $timeRemaining = $this->getTimeRemaining();        //本场剩余时间
            $m = intval (date("m",strtotime($timeRemaining)));   //月
            $d = intval (date("d",strtotime($timeRemaining)));   //日
            $h = intval (date("H",strtotime($timeRemaining)));   //时
            $i = intval (date("i",strtotime($timeRemaining)));   //分
            $s = intval (date("s",strtotime($timeRemaining)));   //秒
            if($this_count == 0){                              //本场抢完   开始倒计时下一场
                $able = 0;
                $m = intval (date("m",strtotime($timeRemainingNext)));       //月
                $d = intval (date("d",strtotime($timeRemainingNext)));       //日
                $h = intval (date("H",strtotime($timeRemainingNext)));   //时
                $i = intval (date("i",strtotime($timeRemainingNext)));   //分
                $s = intval (date("s",strtotime($timeRemainingNext)));   //秒                 
            }
        }else{
            $able = 0;
            $m = intval (date("m",strtotime($timeRemainingNext)));       //月
            $d = intval (date("d",strtotime($timeRemainingNext)));       //日
            $h = intval (date("H",strtotime($timeRemainingNext)));   //时
            $i = intval (date("i",strtotime($timeRemainingNext)));   //分
            $s = intval (date("s",strtotime($timeRemainingNext)));   //秒            
            
        }
        $res = array();
        $res['success'] = "ok";
        $res['remaining'] = $this->getRemaining();  //本场剩余个数 
        $res['timeRemainingNext'] = $timeRemainingNext?date("Y-m-d H",strtotime($timeRemainingNext)):date("Y-m-d H");   //下场抢粽子活动
        $res['able'] = $able;
        $res['m'] = $m;
        $res['d'] = $d;
        $res['h'] = $h;
        $res['i'] = $i;
        $res['s'] = $s;
        $res['over'] = $over;
        echo CJSON::encode($res);
    }
    
    //判断活动是不是已经结束
    public function isOver(){
        $lastModel = SetRiceDumplings::model()->find(array('order'=>'id desc'));
        if(date("Y-m-d H:i:s") >= $lastModel->start_time){
            return true;
        }else{
            return false;
        }
    }
    
} */