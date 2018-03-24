<?php
/**
 * 中秋月饼活动
 * @author gongzhiling
 *
 */
class MidAutumnController extends ActivityController{
	//public $beginTime='2016-08-15 00:00:00';//活动开始时间
	public $beginTime='2016-08-15 00:00:00';//活动开始时间
	public $endTime='2016-09-15 23:59:59';//活动结束时间
	public $secret = 'mi^da*u^tu%mn';
	public $activityName='midautumn';
	public $layout = false;
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'Validity',
				'signAuth-Share,ShareWeb,Intro',
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
		$fromType=Yii::app()->request->getParam('from_type');
		if ($fromType=='colourlife'){
			ActivityGoods::model()->addShareLog($this->getUserId(),'',5); //首页
		}else {
			ActivityGoods::model()->addShareLog($this->getUserId(),'',1); 
		}
		$goods=ActivityGoods::model()->getProducts($this->activityName,true);
		//京东
		$jdUrl=ActivityGoods::model()->getShopUrl($this->getUserId(),1);
		//彩特供
		$ctgUrl=ActivityGoods::model()->getShopUrl($this->getUserId());
		$this->render('/v2016/midAutumn/index',array('goods'=>json_encode($goods),'jdUrl'=>$jdUrl,'ctgUrl'=>$ctgUrl));
	}
	
	/**
	 * 活动介绍
	 */
	public function actionIntro(){
		$time=time();
		$sign=md5('color'.$time.'MidAutumn');
		$url=F::getHomeUrl('/MidAutumn/ShareWeb').'?ts='.$time.'&sign='.$sign;
		$this->render('/v2016/midAutumn/intro',array('url'=>base64_encode($url)));
	}
	
	/**
	 * @version 分享出去的页面
	*/
	public function actionShareWeb(){
		$openId=0;
		if(!empty(Yii::app()->session['wx_user']['openid'])){
			$openId= Yii::app()->session['wx_user']['openid'];
		}
		ActivityGoods::model()->addShareLog(0,$openId,3);
		$time=Yii::app()->request->getParam('ts');
		$sign=Yii::app()->request->getParam('sign');
		$checkSign=md5('color'.$time.'MidAutumn');
		if ($sign!=$checkSign){
			exit ('验证失败！');
		}
		$this->render('/v2016/midAutumn/share');
	}
	
	/**
	 * 记录日志
	 */
	public function actionLog(){
		if (isset($_POST['type'])&&!empty($_POST['type'])){
			$goodID=0;
			if (isset($_POST['gid'])&&!empty($_POST['gid'])){
				$goodID=intval($_POST['gid']);
			}
			ActivityGoods::model()->addShareLog($this->getUserId(),'',intval($_POST['type']),$goodID);
		}
	}
}
