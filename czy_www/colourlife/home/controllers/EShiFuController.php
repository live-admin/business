<?php
/*
 * @version E师傅专题活动
 */
class EShiFuController extends ActivityController{
    public $beginTime='2017-05-09 00:00:00';//活动开始时间
	public $endTime='2017-06-11 23:59:59';//活动结束时间
    public $secret = '@&EShi*Fu^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareKongXi,Share,ShareZhang',
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
        EntranceCountLog::model()->writeOperateLog($this->getUserId() , '' , $operation_time=time(), 31,'');
        EShiFu::model()->addShareLog($this->getUserId(),1);
        $this->render('/v2017/eShiFu/index', array());
    }
    /*
     * @version 空调清洗
     */
    public function actionKongXi(){
        EShiFu::model()->addShareLog($this->getUserId(),2);
        $urlShare=F::getHomeUrl('/EShiFu/ShareKongXi');
        $this->render('/v2017/eShiFu/kongxi', array(
            'surl'=>base64_encode($urlShare),
        ));
    }
    /*
     * @version 除蟑螂妙方
     */
    public function actionZhang(){
        EShiFu::model()->addShareLog($this->getUserId(),3);
        $urlShare=F::getHomeUrl('/EShiFu/ShareZhang');
        $this->render('/v2017/eShiFu/zhang', array(
            'surl'=>base64_encode($urlShare),
        ));
    }
    /*
     * @version 下单区
     */
//    public function actionXiaDan(){
//        EShiFu::model()->addShareLog($this->getUserId(),4);
//        $this->render('/v2017/eShiFu/xiadan');
//    }
    /*
     * @version 空调清洗分享页面
     */
    public function actionShareKongXi(){
        EShiFu::model()->addShareLog($this->getUserId(),5);
        $this->render('/v2017/eShiFu/sharekongxi');
    }
    /*
     * @version 除蟑螂妙方分享页面
     */
    public function actionShareZhang(){
        EShiFu::model()->addShareLog($this->getUserId(),6);
        $this->render('/v2017/eShiFu/sharezhang');
    }
    /*
     * @version 点击ajax
     */
    public function actionDian(){
        $tid = intval(Yii::app()->request->getParam('tid'));
        EShiFu::model()->addShareLog($this->getUserId(),$tid);
        echo json_encode(array('status'=>1));
    }
}
