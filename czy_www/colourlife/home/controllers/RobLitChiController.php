<?php
/* class RobLitChiController extends CController {
    private $_userId = 0;
    private $_usermobile = 0;

    // public function actionIndex(){
    //     $this_act = SetLitChi::model()->getThisActivity();
    //     $over = $this->isOver();
    //     if($this_act){
    //         $this_count = $this_act->remaining_number;
    //     }else{
    //         $this_count = 0;
    //     }
    //     $this->checkLogin();
    //     $able = 1;      //1代表活动正在进行/0代表休息
    //     $timeRemainingNext = $this->getNextTime();   //下一场抢粽子活动 
    //     if(date("H")==10 || date("H")==14 || date("H")==16 || date("H")==20){
    //         $timeRemaining = $this->getTimeRemaining();        //本场剩余时间
    //         $m = intval (date("m",strtotime($timeRemaining)));   //月
    //         $d = intval (date("d",strtotime($timeRemaining)));   //日
    //         $h = intval (date("H",strtotime($timeRemaining)));   //时
    //         $i = intval (date("i",strtotime($timeRemaining)));   //分
    //         $s = intval (date("s",strtotime($timeRemaining)));   //秒
    //     }else{
    //         $able = 0;
    //         $m = intval (date("m",strtotime($timeRemainingNext)));   //月
    //         $d = intval (date("d",strtotime($timeRemainingNext)));   //日
    //         $h = intval (date("H",strtotime($timeRemainingNext)));   //时
    //         $i = intval (date("i",strtotime($timeRemainingNext)));   //分
    //         $s = intval (date("s",strtotime($timeRemainingNext)));   //秒            
            
    //     }

    //     $this->renderPartial("mooncake",//index
    //       array('newInfo'=> RiceDumplingsResult::model()->getNewInfo(),  //最新中奖信息
    //           'remaining' => $this->getRemaining(),  //本场剩余个数
    //           'm' => $m,
    //           'd' => $d,
    //           'h' => $h,
    //           'i' => $i,
    //           's' => $s,
    //           'able' => $able,
    //           'this_count' => $this_count,
    //           'timeRemainingNext' => $timeRemainingNext,    //下场抢粽子活动
    //           'over' => $over,
    //           ));
    // }


    public function actionIndex(){
        $status = $this->act_status();
        if($status==1){
            exit('<center><h1>活动未开始</h1></center>');
        }
        if($status==2){
            exit('<center><h1>活动已经结束</h1></center>');
        }
        $this_act = SetLitChi::model()->getThisActivity();
        if($this_act){
            $flag=1;
            $this_count = $this_act->remaining_number;
        }else{
            $flag=0;
            $this_count = 0;
        }
        $this->checkLogin();
        $_mModel = new SetableSmallLoans();
        $re2 = $_mModel->searchByIdAndType('oneyuan', 1, $this->_userId, false);
        if ($re2) {$url2 = $re2->completeURL;} else $url2 = 'error';
        $this->renderPartial("rob",
          array('newInfo'=> LitChiResult::model()->getNewInfo(),  //最新中奖信息
              'remaining' => $this->getRemaining(),  //本场剩余个数
              'this_count' => $this_count,
              'url' => $url2,
              'flag' => $flag,
              ));

    }

    public function actionRule(){
        $this->checkLogin();
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('oneyuan', 1, $this->_userId, false);
        if ($re) {$url = $re->completeURL;} else $url = 'error';
        $this->renderPartial("hdgz",array(
            'url' => $url,
            ));
    }
    
    public function actionShare(){
        $this->checkLogin();
        $this->renderPartial("share");
    }


    public function actionNewTZS(){
        $this->checkLogin();
        $this->renderPartial("tzs");
    }



    public function actionNewPeople(){
        $this->checkLogin();
        $this->renderPartial("new_people");
    }

    public function actionLitChiInvite(){
        $this->checkLogin();
        $conn=Yii::app()->db;
        $sql="SELECT d.`parent_id` ss ,c.`parent_id` cc FROM `customer` a
                RIGHT JOIN  `community` b ON (a.`community_id`=b.`id`)
                RIGHT JOIN `region` c ON (b.`region_id`=c.`id`)
                RIGHT JOIN `region` d ON (c.`parent_id`=d.`id`)
                WHERE a.`id`=".$this->_userId;
        $rs=$conn->createCommand($sql)->queryAll();
        $proId=intval($rs[0]['ss']);
        $cityId=intval($rs[0]['cc']);
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('market', 1, $this->_userId, false);
        if ($re) {$url = $re->completeURL;} else $url = 'error';
        if($proId==19&&$cityId==233){
            $this->renderPartial("index_sz",array('url'=>$url));
        }else if($proId==19&&$cityId!=233){
            $this->renderPartial("index_sn",array('url'=>$url));
        }else{
            $this->renderPartial("index_sw",array('url'=>$url));
        }
        
    }


    
    public function actionRob(){
        if (!Yii::app()->getRequest()->getIsPostRequest())
            throw new CHttpException(405, 'Method Not Allowed');
        $this->checkLogin();
        if(date("H")==10 || date("H")==16){
            $this_act = SetLitChi::model()->getThisActivity();
            if($this_act){
                $this_act->clicks+=1;
                $this_act->save();
                $res = LitChiResult::model()->rob($this_act->id,$this->_userId);
                echo CJSON::encode($res);     //0抢光了;1抢到了;2今天已抢过不能再抢;4未开始 5/6/7没抢到
            }else{
                echo CJSON::encode(4);        //4活动暂未开始
            }
        }else{
            echo CJSON::encode(4);        //4活动暂未开始
        }    

    }        
    

    public function actionInviteFriend(){
        $this->checkLogin();
        $mobile = isset($_POST['mobile'])?$_POST['mobile']:"";         
        $customer = Customer::model()->find('mobile=:mobile', array(':mobile' => $mobile));
        $MilkInvite_model = LitChiInvite::model()->find('mobile=:mobile', array(':mobile' => $mobile));
        if($this->_usermobile==trim($mobile)){
            echo CJSON::encode(array('code' => '您不能自己邀请自己。'));
        }else{
            if(!$MilkInvite_model){
                $sms = Yii::app()->sms;
                $sms->name = $this->_usermobile;
                $sms->setType('milkInvite', array('mobile' => $mobile));
                $smsresult=$sms->sendUserMessage('milkInviteTemplate'); 
                if($smsresult=='name lookup timed out'){
                    $milk_invitecreate_Model = new LitChiInvite();
                    $milk_invitecreate_Model->customer_id = $this->_userId;
                    $milk_invitecreate_Model->mobile = $mobile;
                    $milk_invitecreate_Model->create_time = time();                    
                    if($customer){
                        $milk_invitecreate_Model->is_reg = 1;
                    }
                    $milk_invitecreate_Model->save();  
                }             
                echo CJSON::encode(array('code' => 'success'));
            }else{
                if($MilkInvite_model->is_buy==1){
                    echo CJSON::encode(array('code' => '您邀请的好友已经被其他人成功邀请了，您不能再邀请。'));
                }else{
                    if(time()-$MilkInvite_model->create_time>=24*60*60){
                        $sms = Yii::app()->sms;
                        $sms->name = $this->_usermobile;
                        $sms->setType('milkInvite', array('mobile' => $mobile));
                        $smsresult=$sms->sendUserMessage('milkInviteTemplate');
                        if($smsresult=='name lookup timed out'){
                            $milk_invitecreate_Model2 = new LitChiInvite();
                            $milk_invitecreate_Model2->customer_id = $this->_userId;
                            $milk_invitecreate_Model2->mobile = $mobile;
                            $milk_invitecreate_Model2->create_time = time();                    
                            if($customer){
                                $milk_invitecreate_Model2->is_reg = 1;
                            }
                            $milk_invitecreate_Model2->save();
                        }
                        echo CJSON::encode(array('code' => 'success'));
                    }else{
                        echo CJSON::encode(array('code' => '您邀请的好友已经被其他人邀请了，24小时内不能再接受邀请。'));
                    }
                }

            }
        }    

    }



    
    //获取本场剩余份数
    public function getRemaining(){
        $activity = SetLitChi::model()->getThisActivity();
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
        $model = SetLitChi::model()->getThisActivity();
        return $model?$model->end_time:date("Y-m-d H:i:s");
    }
    
    //获取距离下场开始时间
    public function getNextTime(){
        $model = SetLitChi::model()->getNextActivity();
        // return $model?$model->start_time:date("Y-m-d H:i:s");
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
            $this->_usermobile = $customer->mobile;
        }
    }


    
    //第一次获取用户的信息
    public function actionGetCustomerInfonew(){
        $this->checkLogin();
        $res = array();
        $customerModel = Customer::model()->findByPk($this->_userId);
        if($customerModel){
            $res['tel'] = $customerModel->mobile;
            $res['name'] = $customerModel->name;
            $res['address'] = $customerModel->CommunityAddress.$customerModel->build->name.$customerModel->room;
        }
        echo CJSON::encode($res);
    }


    //获取业主地址、手机号、名字
    public function actionGetCustomerInfo(){
        $this->checkLogin();
        $id= $_POST['id'];
        $res = array();    
        $id=intval($id);
        $customerinfo = MoonCakesResult::model()->findByPk($id);
        if($customerinfo){
            $res['tel'] = $customerinfo->tel;
            $res['name'] = $customerinfo->linkman;
            $res['address'] = $customerinfo->CustomerAddress;
        }
        echo CJSON::encode($res);
    }

    //第一次收月饼地址
    public function actionFillReceivingnew(){ 
        $this->checkLogin();        
        $linkman = $_POST['linkman'];
        $tel = $_POST['tel'];
        $criteria =new CDbCriteria;
        //$criteria->addCondition("customer_id=".$this->_userId); 
        $criteria->compare('customer_id', $this->_userId);
        $criteria->order = "id desc";
        $model = MoonCakesResult::model()->find($criteria);
        $model->linkman = $linkman;
        $model->tel = $tel;
        $model->address = $model->CustomerAddress;
        $model->save();
        echo CJSON::encode(1);
    }
    
    //修改收月饼地址
    public function actionFillReceiving(){ 
        $this->checkLogin();
        $id = $_POST['id'];
        $linkman = $_POST['linkman'];
        $tel = $_POST['tel'];
        $criteria =new CDbCriteria;
        $id=intval($id);
        //$criteria->addCondition("id=".$id." and customer_id=".$this->_userId); 
        $criteria->compare('id', $id);
        $criteria->compare('customer_id', $this->_userId);
        $criteria->order = "id desc";
        $model = MoonCakesResult::model()->find($criteria);
        $model->linkman = $linkman;
        $model->tel = $tel;
        $model->address = $model->CustomerAddress;
        $model->save();
        echo CJSON::encode(1);
    }
    
    public function actionMylottery(){ 
        $this->checkLogin();
        $criteria = new CDbCriteria;
        $criteria->addCondition("customer_id=".$this->_userId." and relation_id<>0 and model='rob_litchi'"); 
        $criteria->order = "id desc";
        $data=OneYuanBuy::model()->findAll($criteria);
        $list=array();
        foreach ($data as $key => $value){
            $list[$key]['mycode'] = $value->code;
            $list[$key]['isUse'] = $value->is_use;
            $list[$key]['lucky_date'] = date('Y.m.d H:i:s',$value->litchi->create_time);

        }
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('oneyuan', 1, $this->_userId, false);
        if ($re) {$url = $re->completeURL;} else $url = 'error';
        $this->renderPartial("hdzj",
            array("list"=>$list,'url'=>$url)
        );
    }


    public function actionOldMylottery(){ 
        $this->checkLogin();
        $criteria = new CDbCriteria;
        $criteria->addCondition("customer_id=".$this->_userId." and code_relation_id<>0"); 
        $criteria->order = "id desc";
        $data=LitChiResult::model()->findAll($criteria);
        $list=array();
        foreach ($data as $key => $value){
            $list[$key]['mycode'] = $value->oneCode->code;
            $list[$key]['isUse'] = $value->oneCode->is_use;
            $list[$key]['lucky_date'] = date('Y.m.d H:i:s',$value->create_time);
        }
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('oneyuan', 1, $this->_userId, false);
        if ($re) {$url = $re->completeURL;} else $url = 'error';
        $this->renderPartial("hdzj",
            array("list"=>$list,'url'=>$url)
        );
    }


    public function actionFlushByAjax(){
        $over = $this->isOver();
        $able = 1;      //1代表活动正在进行     0代表休息
        $timeRemainingNext = $this->getNextTime();   //下一场抢月饼活动 
        if(date("H")==10 || date("H")==14 || date("H")==16 || date("H")==20){
            $timeRemaining = $this->getTimeRemaining();        //本场剩余时间
            $m = intval (date("m",strtotime($timeRemaining)));   //月
            $d = intval (date("d",strtotime($timeRemaining)));   //日
            $h = intval (date("H",strtotime($timeRemaining)));   //时
            $i = intval (date("i",strtotime($timeRemaining)));   //分
            $s = intval (date("s",strtotime($timeRemaining)));   //秒
            if(date("i") > 50){                              //本场抢完   开始倒计时下一场
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
        $res['timeRemainingNext'] = $timeRemainingNext?date("d日H",strtotime($timeRemainingNext)):date("d日H");   //下场抢月饼活动
        $res['able'] = $able;
        $res['m'] = $m;
        $res['d'] = $d;
        $res['h'] = $h;
        $res['i'] = $i;
        $res['s'] = $s;
        $res['over'] = $over;
        echo CJSON::encode($res);
    }



    public function actionNewFlushByAjax(){
        $res = array();
        $status = $this->act_status();
        if($status==1||$status==2){
           $res['success'] = "no";
           echo CJSON::encode($res);
           return;
        }
        $res['success'] = "no";       
        if(date("H")==10 || date("H")==16){
            $this_act = SetLitChi::model()->getThisActivity();
            if($this_act){
                $num = intval($_POST['remaining']);
                if($num==50||$num==100){
                    if(($this_act->remaining_number*10)!=$num){
                        $res['success'] = "ok";
                        $res['remaining'] = $this->getRemaining();  //本场剩余个数
                    }
                }else{
                    if($num!=0){
                        $nums = floor(intval($_POST['remaining'])/10);                       
                        if(($nums+1)!=$this_act->remaining_number){
                            $res['success'] = "ok";
                            $res['remaining'] = $this->getRemaining();  //本场剩余个数
                        }
                    }                    
                }                
            }
        }        
        echo CJSON::encode($res);
    }
    

    
    //判断活动是不是已经结束
    public function isOver(){
        $lastModel = SetLitChi::model()->find(array('order'=>'id desc'));
        if(date("Y-m-d H:i:s") >= $lastModel->end_time){
            return true;
        }else{
            return false;
        }
    }


     //活动进行中  1、未开始  2、已结束  3、进行中
    public function act_status(){
        $startModel = SetLitChi::model()->find(array('order'=>'id asc'));
        $lastModel = SetLitChi::model()->find(array('order'=>'id desc'));
        if(date("Y-m-d H:i:s") < $startModel->start_time){
            return 1;
        }else if(date("Y-m-d H:i:s") > $lastModel->end_time){
            return 2;
        }else{
            return 3;
        }
    }
    
} */