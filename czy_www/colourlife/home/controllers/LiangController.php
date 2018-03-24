<?php
/*
 * 微信分享送流量
 */
class LiangController extends ActivityController{
    public $beginTime='2016-08-11';//活动开始时间
    public $endTime='2016-12-30 23:59:59';//活动结束时间      
    public $secret = '@&LiuLiang^%';
    public $layout = false;
    private $prize_arr=array(
        1=>'10M',
        2=>'20M',
        3=>'30M',
        4=>'50M',
        5=>'100M',
        6=>'500M',
    );
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth-ShareWeb,Share,SendCode,GetChance,Index',
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

    /**
     * Declares class-based actions.
     */
//    public function actions()
//    {
//        return array(
//            // captcha action renders the CAPTCHA image displayed on the contact page
//            'captcha' => array(
//                'class' => 'NewCaptchaAction',
//                'backColor' => 0xFFFFFF,
//                'maxLength' => '4', // 最多生成几个字符
//                'minLength' => '4', // 最少生成几个字符
//                'height' => '40',
//                'transparent' => true, //显示为透明，当关闭该选项，才显示背景颜色
//            ),
//        );
//    }

    public function actionIndex(){
        $time=time();
		$sign=md5('share=ShareWeb&ts='.$time);
		$url=F::getHomeUrl('/Liang/ShareWeb').'?ts='.$time.'&sign='.$sign;
//        dump($url);
//        dump(base64_encode($url));
        $this->render('/v2016/liuliang/index', array(
            'url'=>base64_encode($url),
        ));
    }
    /*
     * @version 分享页面
     */
    public function actionShareWeb(){
        $openId='';
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
        }else{
            $time=time();
            $sign=md5('share=ShareWeb&ts='.$time);
            $urlOther=F::getHomeUrl('/Liang/ShareWeb').'?ts='.$time.'&sign='.$sign;
            $reUrl=base64_encode($urlOther);
            $url=F::getHomeUrl().'Liang/Share?reUrl='.$reUrl.'&isWx=1';
//            dump($url);
            $this->redirect($url);
        }
        //测试使用
//        $openId='oEw2NuOCp_wMwcLmAiVQt1VOUCqc';
        $time=Yii::app()->request->getParam('ts');
    	$sign=Yii::app()->request->getParam('sign');
    	$checkSign=md5('share=ShareWeb&ts='.$time);
    	if ($sign!=$checkSign){
    		exit ('验证失败！');
    	}
        LiuLiang::model()->addShareLog($openId,1);
        $this->render('/v2016/liuliang/share');
    }
    /*
     * @version 输入手机号码点击领取的ajax
     */
//    public function actionLingQu(){
////        $openId='';
////        if(!empty(Yii::app()->session['wx_user']['openid'])){
////           $openId= Yii::app()->session['wx_user']['openid'];
////        }else{
////            //exit('请重新登录一下！');
////            echo json_encode(array('status'=>0,'msg'=>'请重新登录一下'));
////            exit;
////        }
//
//        $openId='oEw2NuOCp_wMwcLmAiVQt1VOUCqc';
//        LiuLiang::model()->addShareLog($openId,2);
//        $mobile = Yii::app()->request->getParam('mobile');
//        $verifyCode = strtolower(Yii::app()->request->getParam('verifyCode'));
//
//        //$sessionCode = $this->createAction('captcha')->getVerifyCode();
//        //dump($sessionCode);
//        
//        if( empty($verifyCode) || $verifyCode != $this->createAction('captcha')->getVerifyCode()) {
//            echo json_encode(array('status'=>0,'msg'=>'验证码错误'));
//            exit;
//        }
//
//        $openIdCheck= LiuliangPrize::model()->find('open_id=:open_id',array(':open_id'=>$openId));
//        if(!empty($openIdCheck)){
//            echo json_encode(array('status'=>0,'msg'=>'每个微信ID限领一次'));
//            exit;
//        }
//
//        $mobileCheck= LiuliangPrize::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
//        if(!empty($mobileCheck)){
//            echo json_encode(array('status'=>0,'msg'=>'该手机号码已经领取过了！'));
//            exit;
//        }
//        $list=  LiuLiang::model()->getLingQu($mobile,$openId);
//        if(!empty($list)){
//            if($list['success']==1){
//                echo json_encode(array('status'=>1,'msg'=>$list['msg']));
//            }else{
//                echo json_encode(array('status'=>0,'msg'=>$list['msg']));
//            }
//        }
//    }
    //点击发送短信验证码
    public function actionSendCode(){
//        $openId='oEw2NuOCp_wMwcLmAiVQt1VOUCqc';
        $openId='';
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
        }else{
            echo json_encode(array('status'=>0,'msg'=>'请重新登录一下'));
            exit;
        }
        $mobile = Yii::app()->request->getParam('mobile');
//        $mobile='15989573790';
        $openIdCheck= LiuliangPrize::model()->find('open_id=:open_id',array(':open_id'=>$openId));
        if(!empty($openIdCheck)){
            echo json_encode(array('status'=>0,'msg'=>'每个微信ID限领一次'));
            exit;
        }

        $mobileCheck= LiuliangPrize::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
        if(!empty($mobileCheck)){
            echo json_encode(array('status'=>0,'msg'=>'该手机号码已经领取过了！'));
            exit;
        }
		
        $list=  LiuLiang::model()->getYanZhengMa($mobile,$openId);
		
        if(!empty($list)){
            if($list['success']==1){
                echo json_encode(array('status'=>1,'msg'=>$list['msg']));
            }else{
                echo json_encode(array('status'=>0,'msg'=>$list['msg']));
            }
        }
    }
    //点击立即领取
    public function actionGetChance(){
//        $openId='oEw2NuOCp_wMwcLmAiVQt1VOUCqc';
        $openId='';
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
        }else{
            //exit('请重新登录一下！');
            echo json_encode(array('status'=>0,'msg'=>'请重新登录一下'));
            exit;
        }
        $mobile = Yii::app()->request->getParam('mobile');
        $code = Yii::app()->request->getParam('code');
//        $mobile='15989573790';
//        $code='6828';
        LiuLiang::model()->addShareLog($openId,2);
        $model = new SmsForm;
        $model->setScenario('check');
//        $model->attributes = Yii::app()->request->restParams;
        $model->mobile = $mobile;
        $model->code = $code;
        $num = Item::SMS_LIMIT_VALIDATE;
        //检查次数
        $count = $model->GetBlackValidateNum();
        if ($count >= $num){
            echo json_encode(array('status'=>0,'msg'=>'您的手机号因验证次数过多已被禁用，如果不是您本人操作请联系客服'));
            exit;
        }
        if (!$model->validate()){
            echo json_encode(array('status'=>0,'msg'=>$this->errorSummary($model)));
            exit;
        }
        $model->useCode();
        $list=  LiuLiang::model()->getRightNow($mobile,$openId);
        if(!empty($list)){
            if($list['success']==1){
                echo json_encode(array('status'=>1));
            }else{
                echo json_encode(array('status'=>0,'msg'=>$list['msg']));
            }
        }
    }
    /*
     * @version app 流量领取弹框
     */
    public function actionTip(){
        $customer_id = $this->getUserId();
        $cusArr=  Customer::model()->findByPk($customer_id);
        $mobile=$cusArr['mobile'];
        $mobileCheck= LiuliangPrize::model()->find('mobile=:mobile and get_status=1',array(':mobile'=>$mobile));
        if(empty($mobileCheck)){
            exit('请先领取机会！');
        }
        $this->render('/v2016/liuliang/tips', array(
            'prize_name'=>$this->prize_arr[$mobileCheck['prize_id']],
            'id'=>$mobileCheck['id'],
        ));
    }
    //app 点击领取
    public function actionGetByApp(){
        $id = Yii::app()->request->getParam('id');
//        $id=1;
        $customer_id = $this->getUserId();
        $cusArr=  Customer::model()->findByPk($customer_id);
        $mobile=$cusArr['mobile'];
        $prizeRecord=LiuliangPrize::model()->findByPk($id);
        if($prizeRecord['mobile']!=$mobile){
            echo json_encode(array('status'=>0,'msg'=>'不是自己的流量，不能领取！'));
            exit;
        }
        if($prizeRecord['get_status']==0){
            echo json_encode(array('status'=>0,'msg'=>'你已经领取了，不能再重新领取'));
            exit;
        }
        $list=  LiuLiang::model()->getLiuLiangByApp($id);
        if(!empty($list)){
            if($list['success']==1){
                echo json_encode(array('status'=>1));
            }else{
                echo json_encode(array('status'=>0,'msg'=>$list['msg']));
            }
        }
    }
    /*
     * @version app 流量领取弹框
     */
    public function actionBanner(){
        $customer_id = $this->getUserId();
        $cusArr=  Customer::model()->findByPk($customer_id);
        $mobile=$cusArr['mobile'];
        $chanceCheck= LiuliangPrize::model()->find('mobile=:mobile and get_status=1',array(':mobile'=>$mobile));
        if(empty($chanceCheck)){
            $chance=false;
            $id=0;
        }else{
            $chance=true;
            $id=$chanceCheck['id'];
        }
        $this->render('/v2016/liuliang/banner', array(
            'id'=>$id,
            'chance'=>$chance,
        ));
    }
    
}

