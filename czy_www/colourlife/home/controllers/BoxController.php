<?php
/*
 * @version 宝箱
 */
class BoxController extends ActivityController{
    public $beginTime='2016-08-05 10:00:00';//活动开始时间
    public $endTime='2016-08-16 23:59:59';//活动结束时间
    public $secret = '@&Box^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb,Rule,SelectPrize,Share,DianLiang',
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
        Box::model()->addShareLog($this->getUserId(),'',1);    
        $list=Box::model()->getBaoShiArray($this->getUserId());
        $isOpen=Box::model()->isOpen($this->getUserId());
        $this->render('/v2016/box/index', array(
            'list'=>$list,
            'isOpen'=>$isOpen,
            
        ));
    }
    /*
     * @version 开启宝箱ajax
     */
    public function actionOpenBox(){
        $listPrize=Box::model()->openBox($this->getUserId());
        if(!empty($listPrize)){
            $list=Box::model()->getBaoShiArray($this->getUserId());
            $isOpen=Box::model()->isOpen($this->getUserId());
            echo json_encode(array('status'=>1,'listPrize'=>$listPrize,'list'=>$list,'isOpen'=>$isOpen));
        }else{
            echo json_encode(array('status'=>0));
        }
    }
    /*
     * @version 寻宝攻略
     */
    public function actionXunBaoWay(){
        Box::model()->addShareLog($this->getUserId(),'',2);
        $time=time();
        $customer_id = $this->getUserId() * 778 + 1778;
		$sign=md5('sd_id='.$customer_id.'&ts='.$time);
		$url=F::getHomeUrl('/Box/ShareWeb').'?sd_id='.$customer_id.'&ts='.$time.'&sign='.$sign;
        $this->render('/v2016/box/baoWay',array(
            'url'=>base64_encode($url),
        ));
    }
    /*
     * @version 查询宝藏
     */
    public function actionSelectPrize(){
       $prizeMobileArr=Box::model()->getPrizeDetail($this->getUserId());
       $this->render('/v2016/box/prize',array(
           'prizeMobileArr'=>$prizeMobileArr,
       ));
    }
    /*
     * @version 活动规则
     */
    public function actionRule(){
       $this->render('/v2016/box/rule');
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
        $customer_id=intval(($sd_id-1778)/778);
        Box::model()->addShareLog($customer_id,$openId,3);
        $list=Box::model()->getBaoShiArray($customer_id);
        $this->render('/v2016/box/share',array(
            'list'=>$list,
            'sd_id'=>$sd_id,
       ));
    }
    /*
     * @version 点亮宝石ajax
     */
    public function actionDianLiang(){
        $openId='';
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
        }
        
        $sd_id=  intval(Yii::app()->request->getParam('sd_id'));
//        $sd_id=975789606;
        $customer_id=intval(($sd_id-1778)/778);
        Box::model()->addShareLog($customer_id,$openId,10);
        $baoshi_id=Box::model()->getBaoShiByDianLiang($customer_id,$openId);
        if($baoshi_id>0){
            $list=Box::model()->getBaoShiArray($customer_id);
            echo json_encode(array('status'=>1,'baoshi_id'=>$baoshi_id,'list'=>$list));
        }else{
            if($baoshi_id==-2){
                $msg=-2;
            }elseif($baoshi_id==-3){
                $msg=-3;
            }else{
                $msg=-4;
            }
            echo json_encode(array('status'=>0,'msg'=>$msg));
        }
    }
    /*
     * @version 分享到邻里/朋友圈 ajax
     */
    public function actionFenXiang(){
        Box::model()->addShareLog($this->getUserId(),'',5);
        $result=Box::model()->getBaoShiByShare($this->getUserId());
        if(!empty($result)){
            echo json_encode(array('status'=>1));
        }else{
            echo json_encode(array('status'=>0));
        }
    }
    /*
     * @version 寻宝攻略中的点击事件
     */
    public function actionDianJi(){
        $type=  intval(Yii::app()->request->getParam('type'));
        Box::model()->addShareLog($this->getUserId(),'',$type);
        echo json_encode(array('status'=>1));
    }
}
