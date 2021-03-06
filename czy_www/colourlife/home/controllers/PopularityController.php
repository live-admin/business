<?php
/*
 * @version 人气征集
 */
class PopularityController extends ActivityController{
    public $beginTime='2016-10-17';//活动开始时间
    public $endTime='2016-11-17 23:59:59';//活动结束时间
    public $secret = '@&Popularity*^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity - Index,PaiMing',
            'signAuth - ShareWeb,Share,ZhuLi',
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
        $nowTime = time();
        if( $nowTime > strtotime($this->endTime)){
            $this->redirect('/Popularity/PaiMing');
            exit;
        }
        Popularity::model()->addShareLog($this->getUserId(),'',1);
        $listDay=Popularity::model()->getAllQianDaoByCustomer($this->getUserId());
//        dump($listDay);
        $info=Popularity::model()->completeInfo($this->getUserId());
        $linLiTie=Popularity::model()->isLinLiComplete($this->getUserId(),3);
        $linLiZan=Popularity::model()->isLinLiComplete($this->getUserId(),4);
        $ctgUrl=Popularity::model()->getShopUrl($this->getUserId(),0);
        $time=time();
        $customer_id = $this->getUserId() * 778 + 1778;
		$sign=md5('sd_id='.$customer_id.'&ts='.$time);
		$url=F::getHomeUrl('/Popularity/ShareWeb').'?sd_id='.$customer_id.'&ts='.$time.'&sign='.$sign;
        $renQiValue=Popularity::model()->getRenQiValueByCustomerId($this->getUserId());
        //投诉报修是否完成
        $touSuStatus=Popularity::model()->checkMonth($this->getUserId(),10);
        //建议反馈是否完成
        $pingJiaStatus=Popularity::model()->checkMonth($this->getUserId(),11);
        $pingJiaUrl=Popularity::model()->getShopUrl($this->getUserId(),3);
        $this->render('/v2016/popularity/index', array(
            'renQiValue'=>$renQiValue,
            'listDay'=>$listDay,
            'info'=>$info,
            'linLiTie'=>$linLiTie,
            'linLiZan'=>$linLiZan,
            'url'=>base64_encode($url),
            'ctgUrl'=>$ctgUrl,
            'userId'=>$this->getUserId(),
            'touSuStatus'=>$touSuStatus,
            'pingJiaStatus'=>$pingJiaStatus,
            'pingJiaUrl'=>$pingJiaUrl,
        ));
    }
    /*
     * @version 签到 ajax
     */
    public function actionQianDao(){
        $qianDao=Popularity::model()->getValueByQianDao($this->getUserId());
        if(!empty($qianDao)){
            $listDay=Popularity::model()->getAllQianDaoByCustomer($this->getUserId());
            $renQiValue=Popularity::model()->getRenQiValueByCustomerId($this->getUserId());
            echo json_encode(array(
                'status'=>1,
                'listDay'=>$listDay,
                'renQiValue'=>$renQiValue,
                )
            );
        }else{
            echo json_encode(array('status'=>0));
        }
    }
    /*
     * @version 我的人气值
     */
    public function actionMyRenQi(){
        $renDetail=Popularity::model()->renQiDetail($this->getUserId());
        $this->render('/v2016/popularity/myRenQi', array(
            'renDetail'=>$renDetail,
        ));
    }
    /*
     * @version 活动排名页面
     */
    public function actionPaiMing(){
//        $customer_id=$this->getUserId();
//        $zuoBi=false;
//        if(in_array($customer_id, $this->zuoBi)){
//            $zuoBi=true;
//        }
        $paiMing=Popularity::model()->getMingByCustomerId($this->getUserId());
        $this->render('/v2016/popularity/paiMing', array(
            'paiMing'=>$paiMing,
        	'userId'=>$this->getUserId(),
//            'zuoBi'=>$zuoBi,
        ));
    }
    /*
     * @version 活动规则
     */
    public function actionRule(){
        $this->render('/v2016/popularity/rule');
    }
    /*
     * @version 分享页面
     */
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
        Popularity::model()->addShareLog($customer_id,$openId,3);
        $num=Popularity::model()->getZhuLiNum($customer_id,7);
        $this->render('/v2016/popularity/share',array(
            'sd_id'=>$sd_id,
            'num'=>$num,
       ));
    }
    /*
     * @version 助力好友ajax
     */
    public function actionZhuLi(){
        $openId='';
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
        }
        
        $sd_id=  intval(Yii::app()->request->getParam('sd_id'));
//        $sd_id=975789606;
        $customer_id=intval(($sd_id-1778)/778);
        $returnNum=Popularity::model()->checkFiften($customer_id);
        if(!$returnNum){
            echo json_encode(array('status'=>0,'msg'=>-2));
            exit;
        }
        $zhuLi=Popularity::model()->getValueByZhuLi($customer_id,$openId);
        if($zhuLi>0){
            $num=Popularity::model()->getZhuLiNum($customer_id,7);
            echo json_encode(array('status'=>1,'num'=>$num));
        }else{
            echo json_encode(array('status'=>0,'msg'=>$zhuLi));
        }
    }
    /*
     * @version 活动页点击前往下单的次数、活动页点击邀请好友的次数 点击事件 增加分享
     */
    public function actionDianJi(){
        $type=  intval(Yii::app()->request->getParam('type'));
        Popularity::model()->addShareLog($this->getUserId(),'',$type);
        echo json_encode(array('status'=>1));
    }
    /*
     * @version 点击投诉报修 e投诉
     */
    public function actionTouSu(){
        $checkMon=Popularity::model()->checkMonth($this->getUserId(),10);
        $touUrl=Popularity::model()->getShopUrl($this->getUserId(),2);
        if($checkMon){
            echo json_encode(array('status'=>1,'touUrl'=>$touUrl));
        }else{
            $result=Popularity::model()->insertValue($this->getUserId(),5,10,'');
            if($result){
                echo json_encode(array('status'=>1,'touUrl'=>$touUrl));
            }else{
                echo json_encode(array('status'=>0,'msg'=>'数据有误请重新试一下！'));
            }
        }
    }
}
