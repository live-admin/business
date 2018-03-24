<?php
/*
 * @version 邀请注册送饭票
 * @copyright josen
 */
class InviteRegisterController extends CController {
    

    private $_userId = 0; 
    private $_username = "";
//    private $pageSize = 10;
    private $_cust_model = "";
    private $startTime=9;
    private $endTime=22;
    private $jianliArr=array(0=>200,1=>100,2=>50);
    /*
     * @version 我的成功邀请记录
     */
    public function actionSuccessList(){
        $this->checkLogin();
        $sql = "select i.mobile,i.customer_id,i.create_time,i.status from invite i LEFT JOIN customer c on i.mobile=c.mobile where i.status=1 and i.model='customer' and i.effective=1 and i.state=1 and FROM_UNIXTIME(i.create_time) >='".Item::INVITE_REGISTER_START_TIME."' and FROM_UNIXTIME(i.create_time) <='".Item::INVITE_REGISTER_END_TIME."' and TO_DAYS(FROM_UNIXTIME(c.create_time))=TO_DAYS(FROM_UNIXTIME(i.create_time)) and HOUR(FROM_UNIXTIME(i.create_time))>=".$this->startTime." and HOUR(FROM_UNIXTIME(i.create_time))<".$this->endTime." and i.customer_id=".$this->_userId;
        $result = Yii::app()->db->createCommand($sql);
        $records = $result->queryAll();
//        var_dump($records);
//        exit;
        $this->renderPartial('success_record',
            array(
                'records' => $records,
            ));
    }
    /*
     * @version 活动规则
     */
    public function actionRules(){
        $this->checkLogin();   
        $this->renderPartial('rule');
    }
    /*
     * @version 邀请首页
     */
    public function actionInvite(){
        $this->checkLogin();
        $this->renderPartial('yaoqing',array('username' => $this->_username));
    }
    /*
     * @version 前三甲中奖记录
     */
    public function actionQianSan(){
        date_default_timezone_set('Asia/Shanghai');
        $startdate=strtotime(Item::INVITE_REGISTER_START_TIME);
        $enddate=strtotime(date('Y-m-d'));
        $days=round(($enddate-$startdate)/3600/24);//统计前一天的
        $this->renderPartial('qiansan',array('days' => $days));
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
            $customer=Customer::model()->find("id=:id and state = 0", array('id' => $custId));
            if(empty($custId) || empty($customer)){
                exit('<h1>用户信息错误，请重新登录</h1>');
            }
            $this->_userId = $custId;
            $this->_username = $customer->name?$customer->name:$customer->mobile;
            $this->_cust_model = $customer;
        }
    }
    
    
    
    public function actionInviteFriendWarn(){
        $this->checkLogin();
        date_default_timezone_set('Asia/Shanghai');
        if(intval(date('H'))>=22||intval(date('H'))<9){
            echo CJSON::encode(1);
        }else{
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

    public function actionInviteFriend(){
        $this->checkLogin();
        $mobile = isset($_POST['mobile'])?$_POST['mobile']:"";

        if ( ! preg_match('/^(1[3456789])[0123456789]{9}$/', $mobile)) {
            echo CJSON::encode(array('code' => '您输入的手机号码有误。'));
        }
        else {
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
            }
            else {
                echo CJSON::encode(array('code' => '很抱歉，您的好友已经注册了，您可以邀请其他好友注册拿红包。'));
            }
        }
    }
    /*
     * 注册成功送2元饭票脚本
     */
//    public function actionSendRedPacketTwo(){
//        Yii::log("开始处理邀请好友送红包", CLogger::LEVEL_INFO, 'colourlife.core.InviteRegisterCommand');
//        date_default_timezone_set('Asia/Shanghai');
//        $now=strtotime(date('Y-m-d'));
//        $yesterday=strtotime(date('Y-m-d',strtotime('-1 day')));
//        $sql = "select i1.customer_id,i1.mobile from invite_1000 i1 LEFT JOIN invite i on i1.mobile=i.mobile where i1.create_time>=".$yesterday." and i1.create_time<".$now." and i.is_send=0 and i.status=1 and i.effective=1";
//        $result = Yii::app()->db->createCommand($sql);
//        $query = $result->queryAll();
//        foreach($query as $_v){
//            $items = array(
//                'customer_id' =>$_v['customer_id'],//用户的ID
//                'from_type' => Item::RED_PACKET_FROM_TYPE_INVITE,
//                'sum' =>2,//红包金额,
//                // 'sum' =>5*$_v['mycount'],//红包金额,
//                'sn' => 'inviteRegister',
//            );            
//            $transaction = Yii::app ()->db->beginTransaction ();
//            $redPacked = new RedPacket();
//            //把对应的邀请记录设置成已发送红包
//            try{
//                $ret=$redPacked->addRedPacker($items);
//                if($ret){
//                    $updateSql = "update invite set is_send = 1 where customer_id=".$_v['customer_id']." and mobile=".$_v['mobile']." and model='customer' and status = 1 and effective = 1 and is_send = 0 and state=1 and FROM_UNIXTIME(create_time) >= '".Item::INVITE_REGISTER_START_TIME."' and FROM_UNIXTIME(create_time) <= '".Item::INVITE_REGISTER_END_TIME."'";
//                    Yii::app()->db->createCommand($updateSql)->execute();
//                    Yii::log("邀请好友注册,送红包成功".json_encode($items),CLogger::LEVEL_INFO,'colourlife.core.InviteRegisterCommand');
//                }else{
//                    Yii::log("邀请好友注册,送红包失败".json_encode($items),CLogger::LEVEL_ERROR,'colourlife.core.InviteRegisterCommand');   
//                }
//                $transaction->commit();
//            }catch(Exception $e) {
//                Yii::log("try异常：".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.InviteRegisterCommand');
//                $transaction->rollBack();   // 在异常处理中回滚
//            }
//            
//        }
//        Yii::log("结束处理邀请好友送红包", CLogger::LEVEL_INFO, 'colourlife.core.InviteRegisterCommand');
//    }
    /*
     * @version 每天邀请好友数最多前三甲，分别额外获得200元、100元、50元的饭票奖励脚本
     */
    public function actionPaiMing(){
        Yii::log("开始处理邀请好友前三甲送红包", CLogger::LEVEL_INFO, 'colourlife.core.InviteRegisterCommand');
        date_default_timezone_set('Asia/Shanghai');
        $now=strtotime(date('Y-m-d',strtotime('-2 day')));
        $yesterday=strtotime(date('Y-m-d',strtotime('-3 day')));
//        $now=strtotime(date('Y-m-d',strtotime('-1 day')));
//        $yesterday=strtotime(date('Y-m-d',strtotime('-2 day')));
        $sql = "select i.customer_id,i.create_time,count(*) as mycount from invite i LEFT JOIN customer c on i.mobile=c.mobile where i.status=1 and i.model='customer' and i.effective=1 and i.state=1 and FROM_UNIXTIME(i.create_time) >='".Item::INVITE_REGISTER_START_TIME."' and FROM_UNIXTIME(i.create_time) <='".Item::INVITE_REGISTER_END_TIME."' and TO_DAYS(FROM_UNIXTIME(c.create_time))=TO_DAYS(FROM_UNIXTIME(i.create_time)) and i.create_time>=".$yesterday." and i.create_time<".$now." and HOUR(FROM_UNIXTIME(i.create_time))>=".$this->startTime." and HOUR(FROM_UNIXTIME(i.create_time))<".$this->endTime." GROUP BY i.customer_id HAVING mycount>20 ORDER BY mycount desc,i.create_time desc,i.id desc limit 3";
        $result = Yii::app()->db->createCommand($sql);
        $query = $result->queryAll();
        $i=0;
        foreach($query as $_v){
                $items = array(
                    'customer_id' =>$_v['customer_id'],//用户的ID
                    'from_type' => Item::RED_PACKET_FROM_TYPE_INVITE,
                    'sum' =>$this->jianliArr[$i],//红包金额,
                    // 'sum' =>5*$_v['mycount'],//红包金额,
                    'sn' => 'inviteRegister',
                );            
                $transaction = Yii::app ()->db->beginTransaction ();
                $redPacked = new RedPacket();
                //把对应的邀请记录设置成已发送红包
                try{
                    $ret=$redPacked->addRedPacker($items);
                    if($ret){
                        Yii::log("邀请好友注册,前三甲送红包成功".json_encode($items),CLogger::LEVEL_INFO,'colourlife.core.InviteRegisterCommand');
                    }else{
                        Yii::log("邀请好友注册,前三甲送红包失败".json_encode($items),CLogger::LEVEL_ERROR,'colourlife.core.InviteRegisterCommand');   
                    }
                    $transaction->commit();
                }catch(Exception $e) {
                    Yii::log("try异常：".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.InviteRegisterCommand');
                    $transaction->rollBack();   // 在异常处理中回滚
                }
                $i++;
        }
        Yii::log("结束处理邀请好友前三甲送红包", CLogger::LEVEL_INFO, 'colourlife.core.InviteRegisterCommand');
    }
} 