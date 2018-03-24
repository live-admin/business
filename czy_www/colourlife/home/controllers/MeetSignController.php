<?php
/**
 * Created by PhpStorm.
 * User: chenql
 * Date: 2016/5/16
 * Time: 10:51
 */
class MeetSignController extends CController
{
    private $_userId=0;

    public function  actionIndex($id){
        $from_type = Yii::app()->request->getParam('from_type');          //扫描途径
        $from_account = Yii::app()->request->getParam('from_account');    //来源ID
        //$customerId =intval(Yii::app()->request->getParam('cust_id'));
        $meet_id = floatval($id);
        $model = Meeting::model()->findByPk($meet_id);
        $meet_time = $model->meet_time;
        $time_arr = explode(' / ',$meet_time);
        $this->renderPartial('index', array(
            'model' => $model ,
            'time' => $time_arr ,
            //'cust_id' => $customerId,
            'from_type' => $from_type,
            'from_account' => $from_account,));
    }

    /*是否在规定时间内*/
    protected function timeCheck($meet_time){
        $time = time();
        //$str="2016-05-16 09:00 / 2016-05-16 09:30 / 2016-06-01 09:00 / 2016-06-01 19:00";
        $time_arr = explode(' / ',$meet_time);    //strtotime
        //dump(strtotime($time_arr[3]));
        $tem = false;
        for($i=0; $i<sizeof($time_arr); $i=$i+2){
            if(  strtotime($time_arr[0+$i])<$time && $time<strtotime($time_arr[$i+1] )){
                $tem = true;
                break;
            }else{
                //return false;
            }
        }
        return $tem;
    }


    /*
     * 判断是否在规定时间内签过到
     * */
    protected function isSign($OA , $meet_time , $meet_id){
        $time = time();
        $meet_time="2016-06-30 09:00 / 2016-06-30 19:30 / 2016-06-01 09:00 / 2016-06-01 19:00";
        $time_arr = explode(' / ',$meet_time);    //strtotime
        //dump(strtotime($time_arr[3]));
        $tem = false;
        for($i=0; $i<sizeof($time_arr); $i=$i+2){
            $isSign = MeetingSign::model()->find('oa_username=:oa_username and  meet_id=:meet_id and sign_time>=:start_time and sign_time<=:end_time',
                array(':oa_username'=>$OA , ':meet_id'=>$meet_id , ':start_time'=>strtotime($time_arr[0+$i]) , ':end_time'=>$time<strtotime($time_arr[$i+1])));
            if($isSign){
                $tem = true;
                break;
            }else{
                return false;
            }
        }
        return $tem;
    }



    public function actionSign(){
        $id = intval(Yii::app()->request->getParam('id'));                  //会议ID
        $mobile = Yii::app()->request->getParam('mobile');                 //手机号码
        $sign_location = Yii::app()->request->getParam('sign_location');   //签到地点
        $OA = Yii::app()->request->getParam('OA');                        //OA账号
        $from_type = Yii::app()->request->getParam('from_type');          //扫描途径
        $from_account = Yii::app()->request->getParam('from_account');    //来源ID
        $model = Meeting::model()->findByPk($id);
        $meet_time = $model->meet_time;
        $signTime = time();
        if($this->isSign($OA, $meet_time , $id)){
            echo json_encode(array('status'=>0,'param'=>'您已经在该时间内签过到了！'));
            return 0;
        }//签到时间
        if($this->timeCheck($meet_time)){     	
            $meetSign = new MeetingSign();
            $meetSign->from_type = $from_type;
            $meetSign->from_account = $from_account;
            $meetSign->oa_username = $OA;
            $meetSign->user_mobile= $mobile;
            $meetSign->meeting_id =$id;
            $meetSign->sign_time = $signTime;
            $meetSign->sign_location = $sign_location;
            if($meetSign->save()){
                echo json_encode(array('status'=>1,'param'=>'签到成功'));
                return 0;
            }else{
                echo json_encode(array('status'=>0,'param'=>'签到失败'));
                return 0;
            }
        }else{
            echo json_encode(array('status'=>0,'param'=>'请在规定时间内签到'));
            return 0;
        }
    }


    /*
    * 彩住宅发饭票*/
    public function actionFan(){
        //根据当前SESSION生成随机数
        $code = mt_rand(0,1000000);
        $_SESSION['code'] = $code;
        $customerId =intval(Yii::app()->request->getParam('cust_id'));
        $customerId = $customerId * 778 + 1778;
        $this->renderPartial('ticket' , array('cust_id' => $customerId, 'code'=>$code));
    }


    /*
     * 彩住宅发饭票*/
    public function actionMeal(){
        if(isset($_POST['code'])){
            if($_POST['code'] != $_SESSION['code']){
                echo json_encode(array('status'=>2,'param'=>'请不要重复提交！'));
            }
        }
        $startTime = strtotime("2016-06-28 12:00:00");       //活动开始时间
        $endTime = strtotime("2016-10-30 23:59:59");         //活动结束时间
        $customerId =intval(Yii::app()->request->getParam('cust_id'));
        $customerId = ($customerId - 1778) / 778;
        $customer = Customer::model()->findByPk($customerId);
        if(empty($customer)){
            echo json_encode(array('status'=>2,'param'=>'请使用彩之云扫描领取饭票哦！'));
            return 0;
        }
        if($customer['community_id'] != 2274){//2274
            echo json_encode(array('status'=>2,'param'=>'您不在指定小区不能领取哦！'));
            return 0;
        }
        $mobile = $customer['mobile'];
        $note = '黄梅县彩住宅饭票发放二次开放';
        $is_send1 = HourseMobile::model()->find('mobile=:mobile', array(':mobile'=>$mobile));
        $is_send2 = RedPacketCarry::model()->find('receiver_id=:receiver_id and note=:note',array(':receiver_id'=>$customerId, ':note'=>$note));
        if( $is_send2){//限定号码
            echo json_encode(array('status'=>2,'param'=>'您已经领取过饭票，不能再领取饭票了哦！'));
            return 0;
        }
        $time = time();
        if($time <= $startTime){
            echo json_encode(array('status'=>0,'param'=>'还未到领取时间哦，稍等一会就好！'));
            return 0;
        }
        if($time >= $endTime){
            echo json_encode(array('status'=>0,'param'=>'饭票领取活动已结束了，欢迎下次参与哦！'));
            return 0;
        }
        //扣款账户
        $cmobile=20000000006;
        $cmobile_id = 2252538;
        $sendModel = Customer::model()->findByPk($cmobile_id);
        $balance = $sendModel['balance'];
        if($balance<50){
            echo json_encode(array('status'=>0,'param'=>'饭票领取活动已结束，欢迎下次参与哦！'));//钱完了
            return 0;
        }
        $amount = 50;
        $rebateResult=RedPacketCarry::model()->customerTransferAccounts($cmobile_id,$customerId,$amount,1,$cmobile,$note);
        if ($rebateResult['status']==1){
            Yii::log('黄梅县彩住宅发布会发放体验饭票成功： customer_id：'.$customerId.' 时间：'.time() , CLogger::LEVEL_INFO, 'colourlife.core.CAI_HOUSE.Meal');
            echo json_encode(array('status'=>1,'param'=>'饭票领取成功，快去彩之云体验饭票吧！'));
            return 0;
        }else {
            Yii::log('黄梅县彩住宅发布会发放体验饭票失败： customer_id：'.$customerId.' 时间：'.time() , CLogger::LEVEL_INFO, 'colourlife.core.CAI_HOUSE.Meal');
            echo json_encode(array('status'=>0,'param'=>'亲，网络不给力哦！'));
            return 0;
        }
    }

    /*
   * 彩住宅发饭票*/
    public function actionFan1(){
        //根据当前SESSION生成随机数
        $code = mt_rand(0,1000000);
        $_SESSION['code'] = $code;
        $customerId =intval(Yii::app()->request->getParam('cust_id'));
        $customerId = $customerId * 778 + 1778;
        $this->renderPartial('ticket1' , array('cust_id' => $customerId, 'code'=>$code));
    }

    /*
    * 彩住宅发饭票*/
    public function actionMeal1(){
        if(isset($_POST['code'])){
            if($_POST['code'] != $_SESSION['code']){
                echo json_encode(array('status'=>2,'param'=>'请不要重复提交！'));
            }
        }
        $startTime = strtotime("2016-06-28 12:00:00");       //活动开始时间
        $endTime = strtotime("2016-10-30 23:59:59");         //活动结束时间
        $customerId =intval(Yii::app()->request->getParam('cust_id'));
        $customerId = ($customerId - 1778) / 778;
        $customer = Customer::model()->findByPk($customerId);
        if(empty($customer)){
            echo json_encode(array('status'=>2,'param'=>'请使用彩之云扫描领取饭票哦！'));
            return 0;
        }
        if($customer['community_id'] != 2274){//2274
            echo json_encode(array('status'=>2,'param'=>'您不在指定小区不能领取哦！'));
            return 0;
        }
        $mobile = $customer['mobile'];
        $note = '黄梅县彩住宅饭票发放30二次开发';
        //$is_send1 = HourseMobile::model()->find('mobile=:mobile', array(':mobile'=>$mobile));
        $is_send2 = RedPacketCarry::model()->find('receiver_id=:receiver_id and note=:note',array(':receiver_id'=>$customerId, ':note'=>$note));
        if($is_send2){//限定号码
            echo json_encode(array('status'=>2,'param'=>'您已经领取过饭票，不能再领取饭票了哦！'));
            return 0;
        }
        $time = time();
        if($time <= $startTime){
            echo json_encode(array('status'=>0,'param'=>'还未到领取时间哦，稍等一会就好！'));
            return 0;
        }
        if($time >= $endTime){
            echo json_encode(array('status'=>0,'param'=>'饭票领取活动已结束了，欢迎下次参与哦！'));
            return 0;
        }
        //扣款账户
        $cmobile=20000000006;
        $cmobile_id = 2252538;
        $sendModel = Customer::model()->findByPk($cmobile_id);
        $balance = $sendModel['balance'];
        if($balance<50){
            echo json_encode(array('status'=>0,'param'=>'饭票领取活动已结束，欢迎下次参与哦！'));//钱完了
            return 0;
        }
        $amount = 30;
        $rebateResult=RedPacketCarry::model()->customerTransferAccounts($cmobile_id,$customerId,$amount,1,$cmobile,$note);
        if ($rebateResult['status']==1){
            Yii::log('黄梅县彩住宅发布会发放体验饭票成功： customer_id：'.$customerId.' 时间：'.time() , CLogger::LEVEL_INFO, 'colourlife.core.CAI_HOUSE.Meal');
            echo json_encode(array('status'=>1,'param'=>'饭票领取成功，快去彩之云体验饭票吧！'));
            return 0;
        }else {
            Yii::log('黄梅县彩住宅发布会发放体验饭票失败： customer_id：'.$customerId.' 时间：'.time() , CLogger::LEVEL_INFO, 'colourlife.core.CAI_HOUSE.Meal');
            echo json_encode(array('status'=>0,'param'=>'亲，网络不给力哦！'));
            return 0;
        }
    }


    //发饭票4天2元
    public function actionAd(){
        $this->renderPartial('ad');
    }

    //发饭票1天10元
    public function actionAn(){
        $this->renderPartial('an');
    }

    //发送短信
    public function actionSendCode(){
        $mobile =Yii::app()->request->getParam('mobile');
        $type = Yii::app()->request->getParam('type');

        $startTime = strtotime("2016-11-28 12:00:00");       //活动开始时间
        $endTime = strtotime("2016-11-20 23:59:59");         //活动结束时间

        $balance = Customer::model()->findByPk(2118886);
        $balance = $balance['balance'];

        if($type == 0){
            $startTime = strtotime("2016-11-29 00:00:00");       //活动开始时间
            $endTime = strtotime("2016-11-30 23:59:59");         //活动结束时间
            $note = '2016-11-25活动二维码饭票发放4元';
            $start_ime = strtotime(date('Y-m-d',time()));
            $count = RedPacketCarry::model()->count('note=:note and create_time>=:start_ime', array(':note'=>$note, ':start_ime'=>$start_ime));
            if($count >= 250 || $balance<4){
                echo json_encode(array('status'=>3,'param'=>'饭票礼包已领完！'));
                return 0;
            }
        }

        if($type == 1){
            $startTime = strtotime("2016-11-30 00:00:00");       //活动开始时间
            $endTime = strtotime("2016-11-30 23:59:59");         //活动结束时间
            $note = '2016-11-25活动二维码饭票发放10元';
            $count = RedPacketCarry::model()->countByAttributes(array('note'=>$note));
            if($count >= 160 || $balance<10){
                echo json_encode(array('status'=>3,'param'=>'饭票礼包已领完！'));
                return 0;
            }
        }

        $time = time();
        if($time <= $startTime){
            echo json_encode(array('status'=>0,'param'=>'活动还未开始！'));
            return 0;
        }
        if($time >= $endTime){
            echo json_encode(array('status'=>0,'param'=>'活动已经结束！'));
            return 0;
        }

        $is_register = Customer::model()->findByAttributes(array('mobile'=>$mobile ,'state'=>0 ,'is_deleted'=>0));
        $customer_id = 0;
        if($is_register){$customer_id = $is_register['id'];}

        $is_receive = RedPacketCarry::model()->findByAttributes(array('note'=>$note, 'receiver_id'=>$customer_id));
        if($is_receive){
            echo json_encode(array('status'=>4,'param'=>'一个手机号码只能领一次！'));
            return 0;
        }

        $code = rand(1000,9999);
        $content = '【彩生活】您的验证码为'.$code.',验证码2分钟内有效！';

        $model = new XxxSendCode();
        $model->mobile = $mobile;
        $model->type = $type;
        $model->code = $code;
        $model->create_time = time();
        if($model->save()){
            Yii::import('common.api.IceApi');
            $result = IceApi::getInstance()->sendSms($mobile, $content);
            if($result){
                echo json_encode(array('status'=>1,'param'=>'短信验证码发送成功！'));
                return 0;
            }else{
                echo json_encode(array('status'=>0,'param'=>'短信验证码发送失败，请重试'));
                return 0;
            }
        }else{
            echo json_encode(array('status'=>0,'param'=>'短信验证码发送失败，请重试'));
            return 0;
        }

    }

    //发饭票
    public function actionSendMoney(){

        $startTime = strtotime("2016-11-29 00:00:00");       //活动开始时间
        $endTime = strtotime("2016-11-30 23:59:59");         //活动结束时间


        $mobile = Yii::app()->request->getParam('mobile');
        $code = Yii::app()->request->getParam('code');
        $type = Yii::app()->request->getParam('type');
        //先验证短信验证码，再判断是否注册发放

        $sql = "SELECT `code`,`create_time`,`id` FROM xxx_send_code WHERE mobile='".$mobile."' AND type='".$type."' AND is_use=0 ORDER BY create_time DESC LIMIT 1";
        $command = Yii::app()->db->createCommand($sql)->queryAll();

        if(!$command){
            echo json_encode(array('status'=>0,'param'=>'验证码不存在或已过期！'));
            return 0;
        }

        $code_surface = $command[0]['code'];
        $create_time = $command[0]['create_time'];
        if($code == $code_surface && $create_time+120>time()){

            $balance = Customer::model()->findByPk(2118886);
            $balance = $balance['balance'];

            $amount = 0;
            if($type == 0){
                $startTime = strtotime("2016-11-29 00:00:00");       //活动开始时间
                $endTime = strtotime("2016-11-30 23:59:59");         //活动结束时间
                $amount = 4;
                $note = '2016-11-25活动二维码饭票发放4元';
                $start_ime = strtotime(date('Y-m-d',time()));
                $end_ime = $start_ime+36000;
                $count = RedPacketCarry::model()->count('note=:note and create_time>=:start_ime and create_time<:end_time', array(':note'=>$note, ':start_ime'=>$start_ime, ':end_time'=>$end_ime));
                if($count >= 250 || $balance<4){
                    echo json_encode(array('status'=>3,'param'=>'饭票礼包已领完！'));
                    return 0;
                }
            }

            if($type == 1){
                $startTime = strtotime("2016-11-30 00:00:00");       //活动开始时间
                $endTime = strtotime("2016-11-30 23:59:59");         //活动结束时间
                $amount = 50;
                $note = '2016-11-25活动二维码饭票发放10元';
                $count = RedPacketCarry::model()->countByAttributes(array('note'=>$note));
                if($count >= 160 || $balance<50){
                    echo json_encode(array('status'=>3,'param'=>'饭票礼包已领完！'));
                    return 0;
                }
            }

            $time = time();
            if($time <= $startTime){
                echo json_encode(array('status'=>0,'param'=>'活动还未开始！'));
                return 0;
            }
            if($time >= $endTime){
                echo json_encode(array('status'=>0,'param'=>'活动已经结束！'));
                return 0;
            }

            $cmobile = 10000000019;
            $cmobile_id = 2118886;

            //是否注册
            $is_register = Customer::model()->findByAttributes(array('mobile'=>$mobile ,'state'=>0 ,'is_deleted'=>0));

            if($is_register){
                //发饭票
                $customer_id = $is_register['id'];

                $is_receive = RedPacketCarry::model()->findByAttributes(array('note'=>$note, 'receiver_id'=>$customer_id));
                if($is_receive){
                    echo json_encode(array('status'=>0,'param'=>'一个手机号码只能领一次！'));
                    return 0;
                }

                $rebateResult=RedPacketCarry::model()->customerTransferAccounts($cmobile_id,$customer_id,$amount,1,$cmobile,$note);
                if(1 == $rebateResult['status']){

                    $xxxModel = XxxSendCode::model()->findByPk($command[0]['id']);
                    $xxxModel->is_use = 1;
                    $xxxModel->update();

                    echo json_encode(array('status'=>1,'param'=>'恭喜您获取'.$amount.'饭票'));
                    return 0;
                }else{
                    echo json_encode(array('status'=>0,'param'=>'网络繁忙请重试1！'));
                    return 0;
                }
            }else{
                //注册发饭票
                Yii::import('common.api.IceApi');
                $result = IceApi::getInstance()->autoRegister($mobile);
                if($result){
                    //注册成功发钱
                    $customer_id = $result['id'];

                    $is_receive = RedPacketCarry::model()->findByAttributes(array('note'=>$note, 'receiver_id'=>$customer_id));
                    if($is_receive){
                        echo json_encode(array('status'=>0,'param'=>'一个手机号码只能领一次！'));
                        return 0;
                    }


                    $rebateResult=RedPacketCarry::model()->customerTransferAccounts($cmobile_id,$customer_id,$amount,1,$cmobile,$note);
                    if(1 == $rebateResult['status']){

                        $xxxModel = XxxSendCode::model()->findByPk($command[0]['id']);
                        $xxxModel->is_use = 1;
                        $xxxModel->update();

                        echo json_encode(array('status'=>2,'param'=>'恭喜您获取'.$amount.'饭票'));
                        return 0;
                    }else{
                        echo json_encode(array('status'=>0,'param'=>'网络繁忙请重试2！'));
                        return 0;
                    }
                }else{
                    echo json_encode(array('status'=>0,'param'=>'网络繁忙请重试3！'));
                    return 0;
                }
            }
        }else{
            echo json_encode(array('status'=>0,'param'=>'验证过期或错误！'));
            return 0;
        }
    }


}
