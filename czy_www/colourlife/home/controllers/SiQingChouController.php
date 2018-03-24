<?php
/*
 * @version 司庆抽奖
 */
class SiQingChouController extends ActivityController{
    public $beginTime='2016-06-30';//活动开始时间
    public $endTime='2016-07-02 23:59:59';//活动结束时间
    public $secret = '@&Si*Qing^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth-ShareWeb,Share,JiangDetail,GetChouJiang,LingQu',
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
     * @version 首页页面
     */
    public function actionIndex(){
        $result=  SiQingChou::model()->isGetChance($this->getUserId(),'');
        if($result){
            $res=SiQingChou::model()->getChanceByIndex($this->getUserId());
        }
        $allChance=  SiQingChou::model()->getAllChance($this->getUserId(),'');
        $awarderList=SiQingChou::model()->getPrizeDetailTop();
        $prizeMobileArr=SiQingChou::model()->getPrizeDetail($this->getUserId(),'');
        $time=time();
		$sign=md5('share=ShareWeb&ts='.$time);
		$url=F::getHomeUrl('/SiQingChou/ShareWeb').'?ts='.$time.'&sign='.$sign;
        $this->render('/v2016/luckdraw/index', array(
            'allChance'=>$allChance,
            'awarderList'=>$awarderList,
            'prizeMobileArr'=>$prizeMobileArr,
            'userId'=>$this->getUserId(),
            'url'=>base64_encode($url),
        ));
    }
    /*
     * @version 历史记录
     */
    public function actionJiangDetail(){
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
           $customer_id=0;
        }else{
            $customer_id=$this->getUserId();
            $openId='';
        }
        $prizeMobileArr=  SiQingChou::model()->getPrizeDetail($customer_id,$openId);
        $this->render("/v2016/luckdraw/jiangDetail",array(
            'prizeMobileArr'=>$prizeMobileArr,
        ));
    }
    /*
     * @version 点击抽奖的ajax（app）
     */
    public function actionGetChouJiang(){
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
           $customer_id=0;
        }else{
            $customer_id=$this->getUserId();
            $openId='';
        }
        
        $allChance=SiQingChou::model()->getAllChance($customer_id,$openId);
        if($allChance){
            $list= SiQingChou::model()->chouJiang($customer_id,$openId);
        }else{
            $list=array();
        }
        if(!empty($list)){
            $allChance=SiQingChou::model()->getAllChance($customer_id,$openId);
            echo json_encode(array('status'=>1,'list'=>$list,'allChance'=>$allChance));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'抽奖失败，请重新尝试一下'));
        }
    }
    /*
     * @version 输入手机号码点击领取的ajax
     */
    public function actionLingQu(){
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
           $customer_id=0;
        }else{
            $customer_id=$this->getUserId();
            $openId='';
        }
        $chance_id=intval(Yii::app()->request->getParam('chance_id'));
        $mobile=Yii::app()->request->getParam('mobile');
//        测试使用
//        $chance_id=27;
//        $mobile ='15989573790';
        $nine=SiqingchouPrize::model()->find('mobile=:mobile and prize_id=:prize_id and chance_id!=0',array(':mobile'=>$mobile,':prize_id'=>9));
        if(!empty($nine)){
            echo json_encode(array('status'=>0,'msg'=>'该手机号码已经领取过了！'));
            exit;
        }
        $check=SiQingChou::model()->checkChance($customer_id,$openId,$chance_id);
        if(!$check){
            exit('非法操作！');
        }
        $list=SiQingChou::model()->getLingQu($mobile,$chance_id);
        if(!empty($list)){
            if($list['success']==1){
                echo json_encode(array('status'=>1,'msg'=>$list['msg']));
            }else{
                echo json_encode(array('status'=>0,'msg'=>$list['msg']));
            }
        }
    }
    /*
     * @version 分享页面
     */
    public function actionShareWeb(){
        $openId='';
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
        }
//        $openId="oEw2NuOCp_wMwcLmAiVQt1VOUCqc";
        $time=Yii::app()->request->getParam('ts');
    	$sign=Yii::app()->request->getParam('sign');
    	$checkSign=md5('share=ShareWeb&ts='.$time);
    	if ($sign!=$checkSign){
    		exit ('验证失败！');
    	}
        $result=  SiQingChou::model()->isGetChance(0,$openId);
        if($result){
            $res=SiQingChou::model()->getChanceByShare($openId);
        }
        $allChance=  SiQingChou::model()->getAllChance(0,$openId);
        if($allChance>1){
            $allChance=1;
        }
        $awarderList=SiQingChou::model()->getPrizeDetailTop();
        $prizeMobileArr=SiQingChou::model()->getPrizeDetail(0,$openId);
        $this->render('/v2016/luckdraw/share', array(
            'allChance'=>$allChance,
            'awarderList'=>$awarderList,
            'prizeMobileArr'=>$prizeMobileArr,
        ));
    }
    /*
     * @version 点击分享
     */
    public function actionFenXiang(){
        $result= SiQingChou::model()->getChanceByClickShare($this->getUserId());
        if(!empty($result)){
            $allChance=SiQingChou::model()->getAllChance($this->getUserId(),'');
            echo json_encode(array('status'=>1,'allChance'=>$allChance));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'分享失败'));
        }
    }
}
