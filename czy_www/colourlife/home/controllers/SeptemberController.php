<?php
/**
 * Created by PhpStorm.
 * User: chenql
 * Date: 2016/8/29
 * Time: 11:33
 */

class SeptemberController extends ActivityController{
    public $beginTime='2016-09-01';//活动开始时间
    public $endTime='2016-09-10 23:59:59';//活动结束时间
    public $secret = '@&September*^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb,Share,InviteRegister,SendCode,Register',
        );
    }
    public function accessRules(){
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
                'users' => array('@'),
            ),
        );
    }
    /*
     * 活动首页
     * */
    public function actionIndex(){
        $customer_id = $this->getUserId();
        SeptemberLog::model()->addShareLog($customer_id,1);
        $sumSql = "SELECT SUM(amount) FROM september_luck WHERE customer_id =".$customer_id;
        $numSql = "SELECT COUNT(1) FROM september_luck WHERE type IN ('1','2') AND customer_id=".$customer_id;
        $sum =Yii::app()->db->createCommand($sumSql)->queryAll();
        $num =Yii::app()->db->createCommand($numSql)->queryAll();
        //$para = array('cust_id' => $customer_id,'way' => 1);
        //$url = F::getHomeUrl().'September/tip';
        //$url = Yii::app()->curl->buildUrl($url, $para);
        $time=time();
        $customer_id = $this->getUserId() * 778 + 1778;
        $sign=md5('sd_id='.$customer_id.'&ts='.$time);
        $urlShare=F::getHomeUrl('/September/ShareWeb').'?sd_id='.$customer_id.'&ts='.$time.'&sign='.$sign;
        $this->renderPartial('/v2016/september/index',array(
            'sum'=>empty($sum[0]['SUM(amount)'])?0:$sum[0]['SUM(amount)'],
            'num'=>$num[0]['COUNT(1)'],
            //'url'=>$url,
            'urlShare'=>base64_encode($urlShare),
        ));
    }

    //我的奖励
    public function actionMyReward(){
        $customer_id = $this->getUserId();
        $sumSql = "SELECT SUM(amount) AS sum FROM september_luck WHERE customer_id =".$customer_id;
        $sum =Yii::app()->db->createCommand($sumSql)->queryAll();
        $rewardSql1 = "SELECT invite_mobile AS mobile, FROM_UNIXTIME(create_time,'%Y-%m-%d') AS time, amount AS reward,'成功注册' AS type  FROM september_luck WHERE type IN ('1', '2') AND customer_id=".$customer_id;
        $reward1 =Yii::app()->db->createCommand($rewardSql1)->queryAll();
        $rewardSql2 = "SELECT customer.mobile AS mobile,FROM_UNIXTIME(september_luck.create_time,'%Y-%m-%d') AS time, september_luck.amount AS reward,'新人注册' AS type FROM september_luck LEFT OUTER JOIN customer ON september_luck.customer_id=customer.id WHERE september_luck.type=0 AND september_luck.customer_id=".$customer_id;
        $reward2 =Yii::app()->db->createCommand($rewardSql2)->queryAll();
        $reward = array_merge($reward1,$reward2);

        foreach($reward as $key=>$val){
            $reward[$key]['mobile'] = substr_replace($reward[$key]['mobile'],"****",3,4);;
        }
        //dump($reward);
        $this->renderPartial('/v2016/september/myReward',array('reward'=>$reward, 'sum'=>empty($sum[0]['sum'])?0:$sum[0]['sum']));

    }

    //抽奖页面
    public function actionTip(){
        $customer_id = $this->getUserId();
        $way = Yii::app()->request->getParam('way');
        $way = empty($way)?0:1;
        $chance = 0;
        //$customer_id=1288746;
        $chanceSql1 = "SELECT * FROM customer WHERE create_time>=1472659200 AND id=".$customer_id;
        $chanceSql2 = "SELECT * FROM september_luck WHERE type=0 AND customer_id=".$customer_id;
        $chance1 = Yii::app()->db->createCommand($chanceSql1)->queryAll();
        $chance2 = Yii::app()->db->createCommand($chanceSql2)->queryAll();
        if($chance1 && !$chance2)
            $chance = 1;
        $detailSql = "SELECT customer.mobile AS mobile, september_luck.amount as amount FROM september_luck LEFT OUTER JOIN customer ON september_luck.customer_id=customer.id WHERE september_luck.type=0 AND september_luck.amount>=0.8";
        $detail = Yii::app()->db->createCommand($detailSql)->queryAll();
        foreach($detail as $key=>$val){
            $detail[$key]['mobile'] = substr_replace($detail[$key]['mobile'],"****",3,4);;
        }
        $this->renderPartial('/v2016/september/luck',array('chance'=>$chance,'detail'=>$detail, 'way'=>$way));
    }

    //抽奖提交
    public function actionLuckCommit(){
        $way = Yii::app()->request->getParam('way');
        //$cust_id = Yii::app()->request->getParam('cust_id');
        $customer_id = $this->getUserId();
        //$customer_id=1288746;
        $customerModel = Customer::model()->find('id=:customer_id and create_time>=:create_time',array(':customer_id'=>$customer_id, ':create_time'=>1472659200));
        $model = SeptemberLuck::model()->find('customer_id=:customer_id and type=:type', array(':customer_id'=>$customer_id ,':type'=>0));
        $balance = Customer::model()->findByPk(2224375);

        if(!$customerModel){
            echo json_encode(array('status'=>0,'msg'=>'抱歉，您没有抽奖机会'));
            exit();
        }
        if($model){
            echo json_encode(array('status'=>0,'msg'=>'抽奖机会已用完'));
            exit();
        }
        $result = SeptemberLuck::model()->getLuck($customer_id, $way);

        if (!empty($result) && $result['amount'] == 0){
            echo json_encode(array('status'=>0,'param'=>'谢谢参与！'));
        }else if(!empty($result))
        {
            echo json_encode(array('status'=>1,'param'=>$result));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'网络出错！'));
        }
    }


    //分享页
    public function actionShareWeb(){
        $openId='';
        if(!empty(Yii::app()->session['wx_user']['openid'])){
            $openId= Yii::app()->session['wx_user']['openid'];
        }
        $sd_id=  intval(Yii::app()->request->getParam('sd_id'));//用户id
        $time=Yii::app()->request->getParam('ts');
        $sign=Yii::app()->request->getParam('sign');
        $checkSign=md5('sd_id='.$sd_id.'&ts='.$time);
        if ($sign!=$checkSign){
            exit ('验证失败！');
        }
        $customer_id=intval(($sd_id-1778)/778);
        //$customer_id=1288746;
        $model = Customer::model()->findByPk($customer_id);
        $mobile = $model['mobile'];
        $mobile=substr_replace($mobile,"****",3,4);

        $this->renderPartial('/v2016/september/share', array('sd_id'=>$sd_id,'mobile'=>$mobile));
    }


    public function actionInviteRegister1()
    {

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $ip=$_SERVER['REMOTE_ADDR'];//获取当前访问者的ip
            $refer=$_SERVER['HTTP_REFERER'];

            $code = Yii::app()->request->getParam('code');
            $mobile = Yii::app()->request->getParam('mobile');
            $key=md5($ip);
            if(!isset($_SESSION[$key])){
                $_SESSION[$key]=time();
            }elseif((time()-$_SESSION[$key])<=5){
                $this->output('', 0, '不允许频繁操作');
            }
            if ( !preg_match('/^1[3|4|5|7|8][0-9]\d{8}$/', $mobile))
                $this->output('', 0, '手机号码错误');

            if (Customer::model()->find("mobile=:mobile and state=0 and is_deleted=0",array(':mobile'=>$mobile)))
                $this->output('', 0, '手机号码已注册');

            $model = new SmsForm;
            $model->setScenario('check');
            //$model->attributes = Yii::app()->request->restParams;
            $model->mobile = $mobile;
            $model->code = $code;

            $num = Item::SMS_LIMIT_VALIDATE;
            //检查次数
            $count = $model->GetBlackValidateNum();
            if ($count >= $num)
                $this->output('', 0, '您的手机号因验证次数过多已被禁用，如果不是您本人操作请联系客服');

            if (!$model->validate())
                $this->output('', 0, $this->errorSummary($model));

            $model->useCode();
        }
        $_token = md5($code.'@&September*^%');

        $cacheKey = md5('register_key_'.$mobile);

        Yii::app()->cache->set($cacheKey, $_token, 400);
        $result = array(
            '_token' => $_token,
        );
        $this->output($result);
        //echo json_encode(array('status'=>1,'sd_id'=>$sd_id,'inviteMobile'=>$inviteMobile, 'registerMobile'=>$mobile));
        //$this->render('/v2016/september/share', array('sd_id'=>$sd_id,'inviteMobile'=>$inviteMobile, 'registerMobile'=>$mobile));
    }

    public function actionRegister(){
//        $cacheKey = md5('register_key_'.$mobile);
//        $_token = md5($code.'@&September*^%');
//        $_v_token = Yii::app()->cache->get($cacheKey);
//        if(empty($_v_token))
//            $this->output('', 0, '非法操作！');
//
//        if ($_token !== $_v_token) {
//            $this->output('', 0, '表单只能提交一次，不能重复提交！');
//        }

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if(!isset($_SERVER['HTTP_REFER'])){
                $this->output('', 0, '非法操作！');
            }
            $ip=$_SERVER['REMOTE_ADDR'];//获取当前访问者的ip
            $refer=$_SERVER['HTTP_REFERER'];


            $key=md5($refer.$ip);

            $sd_id=  intval(Yii::app()->request->getParam('sd_id'));//用户id
            $customer_id=intval(($sd_id-1778)/778);
            $code = Yii::app()->request->getParam('code');
            $mobile = Yii::app()->request->getParam('mobile');
            $password = Yii::app()->request->getParam('password');
            if (!preg_match('/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])[a-zA-Z0-9]{8,16}/', $password))
                $this->output('', 0, '请输入大小写字母＋数字最少8位的密码');
            if ( !preg_match('/^1[3|4|5|7|8][0-9]\d{8}$/', $mobile))
                $this->output('', 0, '手机号码错误');

            if (Customer::model()->find("mobile=:mobile and state=0 and is_deleted=0",array(':mobile'=>$mobile)))
                $this->output('', 0, '手机号码已注册');

            $model = new SmsForm;
            $model->setScenario('check');
            //$model->attributes = Yii::app()->request->restParams;
            $model->mobile = $mobile;
            $model->code = $code;

            $num = Item::SMS_LIMIT_VALIDATE;
            //检查次数
            $count = $model->GetBlackValidateNum();
            if ($count >= $num)
                $this->output('', 0, '您的手机号因验证次数过多已被禁用，如果不是您本人操作请联系客服');

            if (!$model->validate())
                $this->output('', 0, $this->errorSummary($model));

            $model->useCode();

            if(!isset($_SESSION[$key])){
                $pass=true;
                $_SESSION[$key]=time();
            }elseif((time()-$_SESSION[$key])>180){
                $pass=true;
                $_SESSION[$key]=time();
            }else{
                $pass=false;
            }
            if(!$pass){
                $this->output('', 0, '不允许频繁操作');
            }

            $customerModel = new Customer();
            $customerModel->username = Item::User_Prefix.$mobile;
            $customerModel->password = $password;
            $customerModel->salt = F::random(8);
            $customerModel->name = '访客';
            $customerModel->mobile = $mobile;
            $customerModel->community_id = 585;
            $customerModel->build_id = 10421;
            $customerModel->room = 1;
            $customerModel->create_time = time();
            $customerModel->reg_type = 0;
            if ($customerModel->save()) {
                $result = SeptemberLuck::model()->inviteLuck($customer_id, $mobile);
                if(!$result){
                    Yii::log('2016年9月新人礼活动(邀请)'.date('Y-m-d',time()).'用户id：'.$customer_id.'发票发放失败！错误信息为：',CLogger::LEVEL_ERROR,'colourlife.core.2016SeptemberLuck');
                }
                $result = array(
                    'msg' => '注册成功',
                );

                $this->output($result);
            }


            $this->output('', 0, '系统异常，请稍后再试');
        }
        //echo json_encode(array('status'=>1,'inviteMobile'=>$inviteMobile));
    }


    //发送验证码短信
    public function actionSendCode()
    {
        //Yii::import('m.models.SmsForm');
        $model = new SmsForm();
        $model->setScenario('register');
        $mobile = Yii::app()->request->getParam('mobile');
        $model->mobile = $mobile;

        //$model->attributes = Yii::app()->request->restParams;

        if (!$model->validate())
            $this->output('', 0, $this->errorSummary($model));

        //检查次数
        $num = Item::SMS_LIMIT_VALIDATE;
        $count = $model->GetBlackValidateNum();
        if ($count >= $num)
            $this->output('', 0, '您的手机号因验证次数过多已被禁用，如果不是您本人操作请联系客服');

        if ( !$model->send()){
            $this->output('', 0, $this->errorSummary($model));
        }

        $this->output(array('msg'=>'发送成功'));

    }


}