<?php
class InviteController extends CController {
    
    private $startTime = Invite::STARTTIME;       //活动开始时间
    private $endTime = Invite::ENDTIME;           //活动结束时间
    private $inviteCount = Invite::INVITECOUNT;   //累计邀请次数 
    private $_userId = 0; 
    private $_username = "";
    private $pageSize = 10;
	
	//禁用r邀请访问
	// public function __construct(){
	//      $this->redirect(F::getHomeUrl() . 'pagePromptApp/NotCid');	
	// }

    public function actionIndex(){
        $this->checkLogin();
        $this->renderPartial('index',array('username' => $this->_username));
    }
    
    public function actionMyInviteRecord(){
        $this->checkLogin();        
        $records = Invite::model()->findAll("customer_id=:customer_id and model='customer' and create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc limit ".$this->pageSize,
                                            array(':customer_id'=>$this->_userId));
        $mycount = Invite::model()->count("customer_id=:customer_id and model='customer' and create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc",
                                            array(':customer_id'=>$this->_userId));
        $this->renderPartial('myInviteRecord',
                array('mycount' => $mycount,
                    'records' => $records,
                    'username' => $this->_username));
    }
    
    public function actionGetMyInviteRecordByAjax(){
        $this->checkLogin();   
        //$pageIndex = isset($_POST['pageIndex'])?$_POST['pageIndex']:1;
        $pageIndex = isset($_POST['pageIndex'])?intval($_POST['pageIndex']):1;
        $dataCount = ($pageIndex-1)*$this->pageSize;
        $records = Invite::model()->findAll("customer_id=:customer_id and model='customer' and create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc limit ".$dataCount.",".$this->pageSize,
                                            array(':customer_id'=>$this->_userId));
        $resultArr = array();
        foreach($records as $key => $_value){
            $resultArr[$key]['create_time'] = date("Y-m-d H:i:s",$_value->create_time);
            $resultArr[$key]['mobile'] = $_value->mobile;
            if($_value->status == 1 && $_value->effective == 1){
                $resultArr[$key]['status'] = '已注册';
            }else if($_value->status == 0 && (time() <= $_value->valid_time)){
                $resultArr[$key]['status'] = '注册中<br/><a href="javascript:void();" class="invite_it">重新邀请</a>';
            }else if($_value->status == 0 && (time() > $_value->valid_time)){
                $resultArr[$key]['status'] = '已失效<br/><a href="javascript:void();" class="invite_it">重新邀请</a>';
            }else{
                $resultArr[$key]['status'] = '邀请无效';
            }
        }
        if(count($resultArr) > 0){
            echo CJSON::encode(array("records"=>$resultArr));
        }else{
            echo CJSON::encode(array("code" => "error"));
        }
    }
    
    public function actionSuccessList(){
        $this->checkLogin();
        $records = Invite::model()->findAll("customer_id=:customer_id and status=1 and effective=1 and model='customer' and  
             create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc limit ".$this->pageSize,
             array(':customer_id'=>$this->_userId));
        $mycount = Invite::model()->count("customer_id=:customer_id and status=1 and effective=1 and model='customer' and  
             create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc",
             array(':customer_id'=>$this->_userId));   //已成功邀请注册数
        $sql = "select sum(`sum`) as mysum from red_packet where `sn`='invite' and customer_id=".$this->_userId;
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $mysum = $result[0]['mysum'];                 //已发送红包金额  
        // $allsum = floor($mycount/10)*50;              //可获取红包金额  
        $allsum = $mycount*5;              //可获取红包金额                             
        // $lack = 10 - abs($mycount%10);                //缺少注册数
        $this->renderPartial('successList',
                array('mycount' => $mycount,
                    'mysum' => $mysum,
                    'allsum' => $allsum,
                    // 'lack' => $lack,
                    'records' => $records));
    }
    
    public function actionGetSuccessListByAjax(){
        $this->checkLogin();   
        //$pageIndex = isset($_POST['pageIndex'])?$_POST['pageIndex']:1;
        $pageIndex = isset($_POST['pageIndex'])?intval($_POST['pageIndex']):1;
        $dataCount = ($pageIndex-1)*$this->pageSize;
        $records = Invite::model()->findAll("customer_id=:customer_id and status=1 and effective=1 and model='customer' and  
             create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc limit ".$dataCount.",".$this->pageSize,
             array(':customer_id'=>$this->_userId));
        $resultArr = array();
        foreach($records as $key => $_value){
            $resultArr[$key]['create_time'] = date("Y-m-d H:i:s",$_value->create_time);
            $resultArr[$key]['mobile'] = $_value->mobile;
            $resultArr[$key]['registerTime'] = date("Y-m-d H:i:s",$_value->getRegisterTime());
        }
        if(count($resultArr) > 0){
            echo CJSON::encode(array("records"=>$resultArr));
        }else{
            echo CJSON::encode(array("code" => "error"));
        }
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
            $customer=Customer::model()->find("id=:id and state = 0", array('id' => $custId));
            if(empty($custId) || empty($customer)){
                    exit('<h1>用户信息错误，请重新登录</h1>');
            }
            $this->_userId = $custId;
            $this->_username = $customer->name?$customer->name:$customer->mobile;
        }
    }
    
    public function actionRules(){
        $this->checkLogin();   
        $this->renderPartial('rules');
    }
    
    public function actionInviteFriend(){
        $this->checkLogin();
        $mobile = isset($_POST['mobile'])?$_POST['mobile']:"";        
        $customer = Customer::model()->find('mobile=:mobile', array(':mobile' => $mobile));        
        if (!isset($customer)) {
            $invite = Invite::model()->find('mobile=:mobile and model="customer" and create_time<=:create_time  and valid_time>=:valid_time', array(':mobile' => $mobile,
                ':create_time' => time(), ':valid_time' => time()));

            if (isset($invite)&&$invite->customer_id!=$this->_userId) {
                echo CJSON::encode(array('code' => '您邀请的好友已经被其他人邀请了，暂时不能邀请。'));
            } else {
                if(!isset($invite)){
                    $sms = Yii::app()->sms;
                    $sms->name = $this->_username;
                    $sms->setType('invite', array('mobile' => $mobile));
                    $sms->sendUserMessage('inviteTemplate');
                    
                    $model = Invite::model()->find('mobile=:mobile and model = "customer" and customer_id=:customer_id',
                            array(':mobile' => $mobile, ':customer_id' => $this->_userId));
                    if(!$model){
                        $model = new Invite();
                    }                    
                    $model->customer_id = $this->_userId;
                    $model->mobile = $mobile;
                    $model->create_time = time();
                    $model->valid_time = time() + intval(Yii::app()->config->invite['validTime']);
                    if(!$model->save()){
                        echo CJSON::encode(array('code' => '您的邀请没有正常发出，请重试。'));
                    }else{
                        echo CJSON::encode(array(
                            'code' => 'success'
                        ));
                    }
                }else{
                    if($invite->create_time <= (time() - 120)){
                        $invite->create_time = time();
                        $invite->save();
                        $sms = Yii::app()->sms;
                        $sms->name = $this->_username;
                        $sms->setType('invite', array('mobile' => $mobile));
                        $sms->sendUserMessage('inviteTemplate');
                        echo CJSON::encode(array('code' => 'success'));
                    }else{
                        echo CJSON::encode(array('code' => '您已经邀请过好友了，如果您的好友未收到短信，请2分钟后重试或邀请其他好友注册拿红包。'));
                    }
                }
            }
        } else
            echo CJSON::encode(array('code' => '很抱歉，您的好友已经注册了，您可以邀请其他好友注册拿红包。'));
    }
    
    
}