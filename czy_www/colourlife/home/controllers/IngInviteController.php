<?php
class IngInviteController extends CController {
    
    private $startTime = '2015-05-20 00:00:00';       //活动开始时间
    private $endTime = '2015-06-17 23:59:59';           //活动结束时间
    private $inviteCount = Invite::INVITECOUNT;   //累计邀请次数 
    private $_userId = 0; 
    private $_username = "";
    private $pageSize = 10;
    private $_cust_model = "";
	


	// 禁用r邀请访问
    // public function __construct(){
    //      $this->redirect(F::getHomeUrl() . 'pagePromptApp/NotCid'); 
    // }

    public function actionIndex(){
        $this->checkLogin();
        $mycount = Invite::model()->count("customer_id=:customer_id and status=1 and effective=1 and model='customer' and state=1 and    
             create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc",array(':customer_id'=>$this->_userId));   //已成功邀请注册数
        $sql = "select sum(`sum`) as mysum from red_packet where `sn`='invite' and create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' and customer_id=".$this->_userId;
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $mysum = $result[0]['mysum'];                 //已发送红包金额  
        $allsum = floor($mycount/10)*50;              //可获取红包金额
        $lack = 10 - abs($mycount%10);                //缺少注册数
        $diff = $allsum-$mysum;
        $this->renderPartial('index',array(
            'mycount' => $mycount,
            'mysum' => $mysum,
            'allsum' => $allsum,
            'lack' => $lack,
            'diff' => $diff,
        ));
    }

    
    public function actionMyInviteRecord(){
        $this->checkLogin();        
        $records = Invite::model()->findAll("customer_id=:customer_id and model='customer' and create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc limit ".$this->pageSize,
                                            array(':customer_id'=>$this->_userId));
        $mycount = Invite::model()->count("customer_id=:customer_id and model='customer' and create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc",array(':customer_id'=>$this->_userId));
        $this->renderPartial('record',array(
            'mycount' => $mycount,
            'records' => $records,
            'username' => $this->_username,
        ));
    }
    
    public function actionGetMyInviteRecordByAjax(){
        $this->checkLogin();   
        //$pageIndex = isset($_POST['pageIndex'])?$_POST['pageIndex']:1;
        $pageIndex = isset($_POST['pageIndex'])?intval($_POST['pageIndex']):1;
        $dataCount = ($pageIndex-1)*$this->pageSize;
        $records = Invite::model()->findAll("customer_id=:customer_id and model='customer' and create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc limit ".$dataCount.",".$this->pageSize,array(':customer_id'=>$this->_userId));
        $resultArr = array();
        foreach($records as $key => $_value){
            $resultArr[$key]['create_time'] = date("Y-m-d H:i:s",$_value->create_time);
            $resultArr[$key]['mobile'] = $_value->mobile;
            if($_value->status == 1 && $_value->effective == 1){
                $resultArr[$key]['status'] = '已注册';
            }else if($_value->status == 0 && (time() <= $_value->valid_time)){
                $resultArr[$key]['status'] = '注册中';
            }else if($_value->status == 0 && (time() > $_value->valid_time)){
                $resultArr[$key]['status'] = '已失效';
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
        $records = Invite::model()->findAll("customer_id=:customer_id and status=1 and effective=1 and model='customer' and state=1 and 
             create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc limit ".$this->pageSize,
             array(':customer_id'=>$this->_userId));
        
        $mycount = Invite::model()->count("customer_id=:customer_id and status=1 and effective=1 and model='customer' and state=1 and 
             create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' order by create_time desc",
             array(':customer_id'=>$this->_userId));   //已成功邀请注册数

        $sql = "select sum(`sum`) as mysum from red_packet where `sn`='invite' and create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."' and customer_id=".$this->_userId;
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        $mysum = $result[0]['mysum'];                 //已发送红包金额  
        $allsum = floor($mycount/10)*50;              //可获取红包金额
        //$allsum = $mycount*5;                       //可获取红包金额                             
        $lack = 10 - abs($mycount%10);                //缺少注册数
        $diff = $allsum-$mysum;
        $this->renderPartial('success_record',
                array('mycount' => $mycount,
                    'mysum' => $mysum,
                    'allsum' => $allsum,
                    'lack' => $lack,
                    'records' => $records,
                    'diff' => $diff));
    }
    


    public function actionGetSuccessListByAjax(){
        $this->checkLogin();   
       // $pageIndex = isset($_POST['pageIndex'])?$_POST['pageIndex']:1;
        $pageIndex = isset($_POST['pageIndex'])?intval($_POST['pageIndex']):1;
        $dataCount = ($pageIndex-1)*$this->pageSize;
        $records = Invite::model()->findAll("customer_id=:customer_id and status=1 and effective=1 and model='customer' and state=1 and 
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
            $this->_cust_model = $customer;
        }
    }
    

    //规则页面
    public function actionRules(){
        $this->checkLogin();   
        $this->renderPartial('rule');
    }
    

    //邀请页面
    public function actionInvite(){
        $this->checkLogin();
        $this->renderPartial('yaoqing',array('username' => $this->_username));
    }



    //注册者页面
    public function actionLinks(){
        $this->checkLogin();
        $branchName=$this->_cust_model->community->branch->parent->parent;
        if(!empty($branchName)&&trim($branchName->name)=='深圳事业部'){
            $this->renderPartial("link",array(
                'cust_id'=>$this->_userId,
            ));
        }else{
            $this->renderPartial("link_other",array(
                'cust_id'=>$this->_userId,
            ));
        }
    }



    //泰康判断
    public function actionTaiKangState(){
        $this->checkLogin();
        if($this->_cust_model->community_id==585){
            echo CJSON::encode(array('state' => 2));
            return;
        }

        if(date('Y-m-d',$this->_cust_model->create_time)<'2015-05-20'||date('Y-m-d',$this->_cust_model->create_time)>'2015-06-17'){
            echo CJSON::encode(array('state' => 3));
            return;
        }

        $result=TaikangLife::model()->find("customer_id=:customer_id and create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."'",
            array(':customer_id'=>$this->_userId));
        if($result){
            echo CJSON::encode(array('state' => 1));
        }else{
            echo CJSON::encode(array('state' => 0));
        }
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




    //发放红包
    public function actionSendRedPacket(){
        $this->checkLogin();
        // 当天已经发放过
        // echo CJSON::encode(array('state' => 100));
        // return;
        $sendSuccCount = RedPacket::model()->find("from_type=:from_type and customer_id=:customer_id and sn=:sn and FROM_UNIXTIME(create_time,'%Y%m%d')='".date('Ymd')."'",array(':from_type'=>Item::RED_PACKET_FROM_TYPE_INVITE,':customer_id'=>$this->_userId,':sn'=>'invite'));

        if($sendSuccCount){//一天只能领50
            echo CJSON::encode(array('state' => 'no'));
            return;
        }
        $successCount = Invite::model()->count("customer_id=:customer_id and status=1 and effective=1 and model='customer' and is_send =0 and state=1 and create_time >= '".strtotime($this->startTime)."' and create_time <= '".strtotime($this->endTime)."'",array(':customer_id'=>$this->_userId));
        $sendCount = floor($successCount/10);
        if($sendCount<=0){
            //没有审核通过的邀请数据
            echo CJSON::encode(array('state' => 102));
        }else{
            // $sendMoney = $sendCount*50;
            $sendMoney = 50;
            $items = array(
                'customer_id' => $this->_userId,//用户的ID
                'from_type' => Item::RED_PACKET_FROM_TYPE_INVITE,
                'sum' =>$sendMoney,//红包金额,
                'sn' => 'invite',
            );
            $transaction = Yii::app ()->db->beginTransaction ();
            $redPacked = new RedPacket();
            //把对应的邀请记录设置成已发送红包
            try{
                $ret=$redPacked->addRedPacker($items);
                if($ret){
                    // $updateSql = "update invite set is_send = 1 where customer_id=".$this->_userId." and model='customer' and status = 1 and effective = 1 and is_send = 0 and FROM_UNIXTIME(create_time) >= '".$this->startTime."' and FROM_UNIXTIME(create_time) <= '".$this->endTime."' limit ".$sendCount*10;
                    $updateSql = "update invite set is_send = 1 where customer_id=".$this->_userId." and model='customer' and status = 1 and effective = 1 and is_send = 0 and state=1 and FROM_UNIXTIME(create_time) >= '".$this->startTime."' and FROM_UNIXTIME(create_time) <= '".$this->endTime."' limit 10";
                    Yii::app()->db->createCommand($updateSql)->execute();
                    Yii::log("邀请好友注册,送红包成功".json_encode($items),CLogger::LEVEL_INFO,'colourlife.core.InviteCommand');   
                }else{
                    Yii::log("邀请好友注册,送红包失败".json_encode($items),CLogger::LEVEL_ERROR,'colourlife.core.InviteCommand');   
                }
                $transaction->commit();
                echo CJSON::encode(array('state' => 'ok'));
            }catch(Exception $e) {
                Yii::log("try异常：".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.InviteCommand');
                $transaction->rollBack();   // 在异常处理中回滚
                echo CJSON::encode(array('state' => 'no'));
            }
        }
    }



    //新注册送5元红包接口
    public function actionDoSendRedPacket()
    {   
        // sleep(1);
        $this->checkLogin();
        // echo CJSON::encode(100);
        // return;
        if(date('Y-m-d')>='2015-05-20'&&date('Y-m-d')<='2015-06-17'){
            $model = Customer::model()->enabled()->findByPk($this->_userId);
            $date = date('Y-m-d H:i:s');
            $end_date =  strtotime($date."-30 second");
            //小于30秒时提示
            if ($model->create_time > $end_date){
                echo CJSON::encode ( 21 );
                return;
            }

            if ($model->name == '访客'){
                echo CJSON::encode ( 22 );
                return;
            }

            if($model->reg_type==0){//网站注册不算
                echo CJSON::encode(100);
                return;
            }

            if (!isset($model)) {
                // throw new CHttpException(400, '用户不存在或被禁用!');
                echo CJSON::encode ( 0 );
            }else{
                if($model->community_id==585){
                    // throw new CHttpException(400, '活动用户不含体验区!');
                    echo CJSON::encode ( 1 );
                }else if(date('Y-m-d',$model->create_time)<'2015-05-20'||date('Y-m-d',$model->create_time)>'2015-06-17'){
                    // throw new CHttpException(400, '用户不是在活动时间内注册!');
                    echo CJSON::encode ( 2 );
                }else{
                    $result = RedPacket::model()->find('customer_id=:cust_id and sn=:sn and type=1',array(':cust_id'=>$this->_userId,':sn'=>'invite_register'));
                    if($result){
                        echo CJSON::encode ( 6 );//红包已经领取过，不能重复领取
                    }else{
                        $items = array(
                            'customer_id' => $this->_userId,//业主的ID
                            'sum' => 5,//红包金额
                            'sn' => 'invite_register',
                            'from_type' => Item::RED_PACKET_FROM_TYPE_NEW_CUSTOMER_REGISTER,
                        );
                        $redPacked = new RedPacket();
                        if(!$redPacked->addRedPacker($items)){
                            // throw new CHttpException(400, '红包领取失败!');
                            echo CJSON::encode ( 3 );
                        }
                        Yii::log("【新邀请注册】活动期间新注册的用户获得红包5元，用户ID[{$this->_userId}]", CLogger::LEVEL_INFO, 'colourlife.home.ingInvite.doSendRedPacket');
                        echo CJSON::encode ( 4 );
                    }
                    
                }                
            }                
        }else{
            // throw new CHttpException(400, '活动失效');
            echo CJSON::encode ( 5 );
        }
    }




    //E维修代金劵
    public function actionDoWeiXiuJuan()
    {   
        $this->checkLogin();
        if(date('Y-m-d')>='2015-05-20'&&date('Y-m-d')<='2015-06-17'){
            $model = Customer::model()->enabled()->findByPk($this->_userId);
            if (!isset($model)) {
                // throw new CHttpException(400, '用户不存在或被禁用!');
                echo CJSON::encode ( 0 );
            }else{
                if($model->community_id==585){
                    // throw new CHttpException(400, '活动用户不含体验区!');
                    echo CJSON::encode ( 1 );
                }else if(date('Y-m-d',$model->create_time)<'2015-05-20'||date('Y-m-d',$model->create_time)>'2015-06-17'){
                    // throw new CHttpException(400, '用户不是在活动时间内注册!');
                    echo CJSON::encode ( 2 );
                }else{
                    if($model->is_lingqu_weixiu==1){
                        echo CJSON::encode ( 6 );//代金劵已经领取,不能重复领取
                    }else{
                        $url="http://m.eshifu.cn/business/sendcoupons?mobile=".$model->mobile;
                        $return = Yii::app()->curl->get($url);
                        $result = json_decode($return,true);
                        if($result["code"]==0&&empty($result["message"])){
                            Yii::log("活动期间新注册的用户获得20元E维修代金劵，用户ID[{$this->_userId}]", CLogger::LEVEL_INFO, 'colourlife.home.luckyApp.doWeiXiuJuan');
                            if (!Customer::model()->updateByPk($model->id, array('is_lingqu_weixiu'=>1))) {
                                echo CJSON::encode ( 9 );//领取失败
                            }
                            echo CJSON::encode ( 4 );//领取成功
                        }else if($result["code"]==-1&&$result["message"]=='无效的用户手机号码'){
                            echo CJSON::encode ( 3 );//无效的用户手机号码
                        }else if($result["code"]==-1&&$result["message"]=='数据操作异常'){
                            echo CJSON::encode ( 7 );//数据操作异常
                        }else if($result["code"]==-1&&$result["message"]=='代金券发放时间已过期'){
                            echo CJSON::encode ( 8 );//代金券发放时间已过期
                        }else{
                            echo CJSON::encode ( 9 );//领取失败
                        }
                    }
                    
                }                
            }                
        }else{
            // throw new CHttpException(400, '活动失效');
            echo CJSON::encode ( 5 );
        }
    }








    
}