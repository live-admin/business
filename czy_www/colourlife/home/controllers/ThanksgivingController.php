<?php
/**
 * Created by PhpStorm.
 * User: 感恩送福袋活动
 * Date: 2016/11/1
 * Time: 14:20
 */
class ThanksGivingController extends ActivityController{
    public $beginTime = '2016-11-16 00:00:00';//活动开始时间
   // public $beginTime='2016-11-15 09:00:00';//活动开始时间
    public $endTime = '2016-11-26 23:59:59';//活动结束时间
    public $secret = 'th^an#ksgi*ving';
    public $layout = false;
    private $cmobile = '20000000005';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb,Share,SendCode,Register',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
                'users' => array('@'),
            ),
        );
    }


    //活动首页
    public function actionIndex(){
        $customer_id = $this->getUserId();
        SeptemberLog::model()->addShareLog($customer_id,5);//首页点击记录
        $time=  strtotime($this->beginTime);
        $inviteSql = "SELECT mobile FROM invite WHERE status=1 AND effective=1 AND  customer_id={$customer_id} AND create_time >= {$time} ";
        $inviteRecord = Yii::app()->db->createCommand($inviteSql)->queryAll();
        $hasBag = 0;//是否有福袋弹框
        $bagSql = "SELECT COUNT(1) FROM thinks_giving WHERE is_receive=0 AND customer_id={$customer_id}";
        $result = Yii::app()->db->createCommand($bagSql)->queryAll();
        if(!empty($result)){$hasBag = $result[0]['COUNT(1)'];}
        $count = 0;   //成功邀请人数
        foreach($inviteRecord as $v){
            $flag = ThinksGiving::model()->completeInfo($v['mobile']);
            if($flag){$count++;}
        }

        $todayTime= strtotime(date('Y-m-d',time()));
        $hasRain = 1;

        $is_receive = ThinksGiving::model()->find('type=:type and customer_id=:customer_id and create_time>=:create_time',
            array(':type'=>3, ':customer_id'=>$customer_id, ':create_time'=>$todayTime));

        $count1 = ThinksGiving::model()->count('type=:type and create_time>=:create_time', array(':type'=>3, ':create_time'=>$todayTime));
        if($count1>=10 || $is_receive){
            $hasRain = 0;
        }


        //分享链接
        $customer_id = $this->getUserId() * 778 + 1778;
        $sign=md5('sd_id='.$customer_id.'&ts='.$time);
        $urlShare=F::getHomeUrl('/Thanksgiving/ShareWeb').'?sd_id='.$customer_id.'&ts='.$time.'&sign='.$sign;
        $timeNow = date("Y/m/d H:i:s",time());//date("Y-m-d H:i:s")
        $timeEnd = date("Y/m/d H:i:s",1480039200);
        $this->renderPartial('/v2016/thinksGiving/index',
            array('number'=>$count,
                'hasBag'=>$hasBag,
                'timeNow'=>$timeNow,
                'timeEnd'=>$timeEnd,
                'hasRain'=>$hasRain,
                'surl'=>base64_encode($urlShare)));
    }

    //邀请记录
    public function actionInviteRecord(){
        $customer_id = $this->getUserId();
        $time=  strtotime($this->beginTime);
        $inviteSql = "SELECT mobile FROM invite WHERE status=1 AND effective=1 AND  customer_id={$customer_id} AND create_time >= {$time} ";
        $inviteRecord =Yii::app()->db->createCommand($inviteSql)->queryAll();
        $recordArray = array();
        foreach($inviteRecord as $v){
            $flag = ThinksGiving::model()->completeInfo($v['mobile']);
            if($flag){
                $recordArray[] =  substr_replace($v,'****',3,4);
            }
        }
        $this->renderPartial('/v2016/thinksGiving/inviteRecord', array('recordArray'=>$recordArray));
    }

    //中奖记录
    public function actionRewardRecord(){
        $customer_id = $this->getUserId();
        $rewardSql = "SELECT a.`update_time` AS time, b.`name` FROM thinks_giving a LEFT OUTER JOIN thinks_giving_prize b ON a.prize_id=b.id WHERE a.customer_id={$customer_id} AND a.prize_id IN(1,2,3,4,5,6,7) ORDER BY a.`update_time` DESC ";
        $rewardRecord =Yii::app()->db->createCommand($rewardSql)->queryAll();
        foreach($rewardRecord as $key =>  &$value){
            $value['time'] =  date('Y年m月d日', ($value['time']));
        }
        $this->renderPartial('/v2016/thinksGiving/myReward', array('rewardRecord'=>$rewardRecord));
    }

    //活动规则
    public function actionRules(){
        $this->renderPartial('/v2016/thinksGiving/rules');
    }

    //打开福袋
    public function actionOpen(){
        $type = intval(Yii::app()->request->getParam('type'));
        $customer_id = $this->getUserId();
        if($type == 3){
            $todayTime= strtotime(date('Y-m-d',time()));
            if(time()<$todayTime+36000){
                $arr = array(
                    'state'=>0,
                    'msg'=>'还没到领取时间哦！',
                );
                $this->output($arr);
                //$this->output('', 0, '还没到领取时间哦！');
            }

            //10个是否已抢完
            $count = ThinksGiving::model()->count('type=:type and create_time>=:create_time', array(':type'=>3, ':create_time'=>$todayTime));
            if($count>=10){
                $arr = array(
                    'state'=>0,
                    'msg'=>'幸福安康！元气满满！～',
                );
                $this->output($arr);
               // $this->output('', 0, '幸福安康！元气满满！～ ');
            }

            //奖品是否已抢完
            $randSql = "SELECT id ,number,last_number FROM thinks_giving_prize WHERE id <5 AND last_number>0";
            $randArr = Yii::app()->db->createCommand($randSql)->queryAll();
            if(empty($randArr)){
                $arr = array(
                    'state'=>0,
                    'msg'=>'幸福安康！元气满满！～',
                );
                $this->output($arr);
                //$this->output('', 0, '幸福安康！元气满满！～ ');
            }

            //今天是否已经抢过
            $is_receive = ThinksGiving::model()->find('type=:type and customer_id=:customer_id and create_time>=:create_time',
                array(':type'=>3, ':customer_id'=>$customer_id, ':create_time'=>$todayTime));
            if($is_receive){
                $arr = array(
                    'state'=>0,
                    'msg'=>'您今天已经抢过福袋了！',
                );
                $this->output($arr);
                $this->output('', 0, '您今天已经抢过福袋了！');
            }


            $result = ThinksGiving::model()->getPrize($type, $customer_id);
            $result['count'] = $count;
        }else{
            $sql = "SELECT type FROM thinks_giving WHERE customer_id={$customer_id} AND is_receive=0 LIMIT 1";
            $record =Yii::app()->db->createCommand($sql)->queryAll();
            if(empty($record)){
            	$result = array();
            }else{
            	$result = ThinksGiving::model()->getPrize($record[0]['type'], $customer_id);
            }
        }
        if(!empty($result)){
            $this->output($result);
        }else{
            $arr = array(
                'state'=>0,
                'msg'=>'网络繁忙！',
            );
            $this->output($arr);
            //$this->output('', 0, '网络繁忙！');
        }
    }

    //分享
    public function actionShareWeb(){
//        $openId='';
//        if(!empty(Yii::app()->session['wx_user']['openid'])){
//            $openId= Yii::app()->session['wx_user']['openid'];
//        }
        $sd_id=  intval(Yii::app()->request->getParam('sd_id'));//用户id
        $time=Yii::app()->request->getParam('ts');
        $sign=Yii::app()->request->getParam('sign');
        $checkSign=md5('sd_id='.$sd_id.'&ts='.$time);
        if ($sign!=$checkSign){
            exit ('验证失败！');
        }
        $customer_id=intval(($sd_id-1778)/778);
        SeptemberLog::model()->addShareLog($customer_id,6);//首页点击记录
        //$customer_id=1288746;
        $model = Customer::model()->findByPk($customer_id);
        $mobile = $model['mobile'];
        $mobile=substr_replace($mobile,"****",3,4);
        $this->renderPartial('/v2016/thinksGiving/share', array('sd_id'=>$sd_id,'mobile'=>$mobile));

    }

    //发送验证码短信
    public function actionSendCode()
    {
        //Yii::import('m.models.SmsForm');
        $model = new SmsForm();
        $model->setScenario('register');
        $mobile = Yii::app()->request->getParam('mobile');
        $model->mobile = $mobile;


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

    //注册
    public function actionRegister(){

        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if(!isset($_SERVER['HTTP_REFER'])){
                //$this->output('', 0, '非法操作！');
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

            $isRegister = Customer::model()->findByAttributes(array('mobile'=>$mobile,'is_deleted'=>0,'status'=>0));
            if($isRegister)
                $this->output('', 0, '该账号已注册彩之云！');

            if ($customerModel->save()) {
                $result = ThinksGiving::model()->insertInvite($customer_id, $mobile);
                if(!$result){
                    Yii::log('感恩节活动网页邀请插入失败'.date('Y-m-d',time()).'用户id：'.$customer_id.'发邀请插入失败！错误信息为：',CLogger::LEVEL_ERROR,'colourlife.core.ThinksGiving');
                }
                $result = array(
                    'msg' => '注册成功',
                );
                $this->output($result);
            }

            $this->output('', 0, '系统异常，请稍后再试');
        }

    }
}
