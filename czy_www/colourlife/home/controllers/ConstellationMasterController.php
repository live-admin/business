<?php
/**
 * 星座达人购物趴
 * @author gongzhiling
 * @date 2016-07-26 19:29
 */
class ConstellationMasterController extends ActivityController{
	public $beginTime='2016-07-28 10:00:00';//活动开始时间
	public $endTime='2016-08-31 23:59:59';//活动结束时间
	public $secret = 'Con^st*ella_tion';
	public $layout = false;
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'Validity',
				'signAuth',
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
	
	/**
	 * 首页
	 */
	public function actionIndex(){
		ConstellationMasterCoupons::model()->addLog($this->getUserId(), 0);
		$this->render('/v2016/constellation/index');
	}
	
	/**
	 * 领券
	 */
	public function actionGetCoupons(){
		$youHuiQuan=ConstellationMasterCoupons::model()->getYouHuiQuan($this->getUserId());
		$this->render('/v2016/constellation/coupons',array('youHuiQuan'=>json_encode($youHuiQuan)));
	}
	
	/**
	 * 商品列表
	 */
	public function actionList(){
		$type=intval(Yii::app()->request->getParam('type'));
		if (empty($type)){
			exit('页面出错了！');
		}
		ConstellationMasterCoupons::model()->addLog($this->getUserId(), $type);
		$shangChengUrl=ConstellationMasterCoupons::model()->getUrl($this->getUserId(),$type);
		$goods=ConstellationMasterCoupons::model()->getProducts($type);
		$this->render('/v2016/constellation/list',array('shangChengUrl'=>$shangChengUrl['tgHref'],'goods'=>json_encode($goods)));
	}
	
	/**
	 * 优惠券入库
	 */
	public function actionAddCoupons(){
		if (!isset($_POST['money'])){
			echo json_encode(array('result'=>-1));
			exit();
		}
		$money=intval($_POST['money']);
		$result=ConstellationMasterCoupons::model()->addYouHuiQuan($this->getUserId(),$money);
		echo json_encode(array('result'=>$result));
	}
}