<?php
/*
 * @version 荔枝文化节活动
*/
class LiZhiCultureController extends ActivityController{
	public $beginTime='2016-06-04 09:00:00';//活动开始时间
	//public $beginTime='2016-06-03 09:00:00';//活动开始时间
	public $endTime='2016-07-04 23:59:59';//活动结束时间
	public $secret = '^&Lizhi*cult^ure%';
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
	 * 首页--省内
	 */
	public function actionProvinceIndex(){
		//判断是否是新注册的
		$productsList=LiZhi::model()->getProvinceAllProducts();
		//商城url
		$shangChengUrl=LiZhi::model()->getAllUrl($this->getUserId (),1);
		$this->render('/v2016/liZhiCulture/index', array(
				'productsList'=>$productsList,
				'shangChengUrl'=>$shangChengUrl['tgHref'],
		));
	}
	
	/**
	 * 首页--省外
	 */
	public function actionOtherProvinceIndex(){
		$productsList=LiZhi::model()->getOtherProvinceAllProducts();
		//商城url
		$shangChengUrl=LiZhi::model()->getAllUrl($this->getUserId (),1);
		$this->render('/v2016/liZhiCulture/index', array(
				'productsList'=>$productsList,
				'shangChengUrl'=>$shangChengUrl['tgHref'],
		));
	}
	
	/**
	 * 9.9秒杀
	 */
	public function actionSeckill(){
		$SeckillProducts=LiZhi::model()->getSeckillProducts();
		$h=date("H");
		$shangChengUrl1='';
		$shangChengUrl2='';
		if ($h>=10&&$h<=23){  //10点-23点之间
			//商城url
			$shangChengUrl=LiZhi::model()->getAllUrl($this->getUserId (),1);
			$shangChengUrl1=$shangChengUrl['tgHref'];
			if ($h>=16){
				$shangChengUrl2=$shangChengUrl['tgHref'];
			}
		}
		$this->render('/v2016/liZhiCulture/seckill', array(
				'seckillProducts'=>$SeckillProducts,
				'shangChengUrl1'=>$shangChengUrl1,
				'shangChengUrl2'=>$shangChengUrl2,
		));
	}
	/**
	 * 新人有礼
	 */
	public function actionNewTalent(){
		$chance=0;
		//当前用户是否有抽奖机会
		$model=LizhiRegisterGift::model()->findByPk($this->getUserId ());
		if (!empty($model)&&$model->is_use==0){
			$chance=1;
		}
		//奖品列表
		$prizeList=LiZhi::model()->getPrizeList();
		$this->render('/v2016/liZhiCulture/newTalent', array(
				'chance'=>$chance,
				'prizeList'=>json_encode($prizeList)
		));
	}
	
	/**
	 * 抽奖
	 */
	public function actionLottery(){
		$model=LizhiRegisterGift::model()->findByPk($this->getUserId ());
		if (empty($model)){
			echo json_encode(array('status'=>0,'msg'=>'抱歉，您没有抽奖机会'));
			exit();
		}
		if ($model->is_use==1){
			echo json_encode(array('status'=>0,'msg'=>'抽奖机会已用完'));
			exit();
		}
		
		$result=LiZhi::model()->draw($this->getUserId ());
		//商城url
		if (!empty($result)){
			echo json_encode(array('status'=>1,'param'=>$result));
		}else {
			echo json_encode(array('status'=>0,'msg'=>'网络出错！'));
		}
	}
	
	/**
	 * 彩富人生
	 */
	public function actionCaiFuVip(){
		$url=F::getHomeUrl('/advertisement/wealthLife').'?cust_id='.$this->getUserId ();
		$this->render('/v2016/liZhiCulture/caiFuVip',array('caifu_url'=>$url));
	}
}