<?php
/*
 * @version 国庆七天乐
 */
class TaskController extends ActivityController{
    public $beginTime='2016-10-01';//活动开始时间
    public $endTime='2016-10-07 23:59:59';//活动结束时间
    public $secret = '@&Task^%';
    public $layout = false;
    private $dateArr=array(
        1=>'2016-10-01',
        2=>'2016-10-02',
        3=>'2016-10-03',
        4=>'2016-10-04',
        5=>'2016-10-05',
        6=>'2016-10-06',
        7=>'2016-10-07',
        8=>'2016-10-08',
    );

    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb,Rule,Share,ZhuFu',
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
        TaskDate::model()->addShareLog($this->getUserId(),'',1);
        $isCai=TaskDate::model()->isCaiFu($this->getUserId());
        $checkCai=TaskComplete::model()->find('customer_id=:customer_id and task_id=7',array(':customer_id'=>$this->getUserId()));
        $now=time();
        if($isCai && empty($checkCai) && ($now>=strtotime($this->dateArr[6]) && $now<strtotime($this->dateArr[7]))){
            TaskDate::model()->getTask($this->getUserId(),'',7);
        }
        $resultData=TaskDate::model()->getIndexData($this->getUserId());
        $time=time();
        $customer_id = $this->getUserId() * 778 + 1778;
		$sign=md5('sd_id='.$customer_id.'&ts='.$time);
		$url=F::getHomeUrl('/Task/ShareWeb').'?sd_id='.$customer_id.'&ts='.$time.'&sign='.$sign;
//        dump($url);
        $this->render('/v2016/task/index', array(
            'resultData'=>json_encode($resultData),
            'url'=>base64_encode($url),
        ));
    }
    /*
     * @version 分享出去的页面
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
        TaskDate::model()->addShareLog($customer_id,$openId,2);
        $num=TaskDate::model()->getZhuFuNum($customer_id,2);
        $this->render('/v2016/task/share', array(
            'sd_id'=>$sd_id,//用户id
            'num'=>$num,
        ));
    }
    /*
     * @version 活动规则页面
     */
    public function actionRule(){
        $this->render('/v2016/task/rule');
    }
    /*
     * @version 任务明细
     */
    public function actionTaskDetail(){
        $historyData=TaskDate::model()->getHistory($this->getUserId());
    	$this->render('/v2016/task/history', array(
            'historyData'=>  json_encode($historyData),
        ));
    }
    /*
     * @version 好友祝福ajax
     */
    public function actionZhuFu(){
        $openId='';
        if(!empty(Yii::app()->session['wx_user']['openid'])){
           $openId= Yii::app()->session['wx_user']['openid'];
        }
        $sd_id=  intval(Yii::app()->request->getParam('sd_id'));
//        $sd_id=975789606;
        $customer_id=intval(($sd_id-1778)/778);
        $zhuLi=TaskDate::model()->getValueByZhuFu($customer_id,$openId);
        if($zhuLi>0){
            $num=TaskDate::model()->getZhuFuNum($customer_id,2);
            echo json_encode(array('status'=>1,'num'=>$num));
        }else{
            echo json_encode(array('status'=>0,'msg'=>$zhuLi));
        }
    }
}
