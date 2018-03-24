<?php
/*
 * @version 表白活动
 */
class BiaoBaiController extends ActivityController{
    public $beginTime='2017-05-16 00:00:00';//活动开始时间
	public $endTime='2017-05-26 23:59:59';//活动结束时间
    public $secret = '@&Biao*Bai^%';
    public $layout = false;
    
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb,Share',
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
        BiaoBai::model()->addShareLog($this->getUserId(),1);
        $huaUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),3);
        $qianUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),4);
        $jdUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
        $this->render('/v2017/biaoBai/index', array(
            'huaUrl'=>$huaUrl,
            'qianUrl'=>$qianUrl,
            'jdUrl'=>$jdUrl,
            
        ));
    }
    /*
     * @version boy页面
     */
    public function actionBoy(){
        //BiaoBai::model()->addShareLog($this->getUserId(),2);
        $type=1;
        $goods = BiaoBai::model()->getGoodsList($type);
		$jdUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
        $this->render('/v2017/biaoBai/goods_nav_other',array(
			'goods' => $goods,
			'jdUrl' => $jdUrl,
		));
    }
    /*
     * @version girl页面
     */
    public function actionGirl(){
        //BiaoBai::model()->addShareLog($this->getUserId(),3);
        $type=2;
        $goods = BiaoBai::model()->getGoodsList($type);
		$jdUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
        $this->render('/v2017/biaoBai/goods_nav_another',array(
			'goods' => $goods,
			'jdUrl' => $jdUrl,
		));
    }
    /*
     * @version 点击商品跳转
     */
    public function actionDian(){
        $tid = intval(Yii::app()->request->getParam('tid'));
        BiaoBai::model()->addShareLog($this->getUserId(),$tid);
        echo json_encode(array('status'=>1));
    }
}
