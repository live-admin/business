<?php
/**
 * 中秋月饼活动
 * @author gongzhiling
 *
 */
class SinglesdayController extends ActivityController{
	public $beginTime='2016-11-1 00:00:00';//活动开始时间
	//public $beginTime='2016-10-31 00:00:00';//活动开始时间
	public $endTime='2016-11-15 23:59:59';//活动结束时间
	public $secret = 'si^ng%le*sd!ay';
	public $activityName='singlesDay';
	public $whiteMember = array(1240946,123658,2142258,1742724,2191401,2112219,1880959,2002207,1745829);
	public $layout = false;
	//黑名单
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
	 * 首页  1号到3号展示抽奖页，4号到15号展示商品页
	 */
	public function actionIndex(){
		$customerID = $this->getUserId();
		ActivityGoods::model()->addShareLog($customerID, '', 1);
		$day = date("j");
		if (in_array($customerID, $this->whiteMember)){
			$day = 4;
		}
		if ($day >= 1 && $day <4){ //抽奖首页
			$this->redirect(array('/Singlesday/LotteryIndex'));
		}else { //商品首页
			$this->redirect(array('/Singlesday/GoodsIndex'));
		}
	}
	
	/**
	 * 抽奖首页
	 */
	public function actionLotteryIndex(){
		$day = date("j");
		$isShow = 0;
		$chance = 0;
		if ($day >= 1 && $day <4){
			$isShow = 1;
		}
		//获取每天的信息
		$data = SinglesdayLotteryChance::model()->getPerDayInfo($this->getUserId());
		if (empty($data)){
			throw new CHttpException(400, "用户不存在！");
		}
		$this->render('/v2016/singlesday/index',array(
			'data' => json_encode($data),
			'isShow' => $isShow
		));
	}
	
	/**
	 * 商品首页
	 * $type 根据type读取不同的商品 （7粮油副食，8特惠饮品，9休闲零食，10新鲜水果）
	 */
	public function actionGoodsIndex(){
		$day = date("j");
		$customerID = $this->getUserId();
		if (in_array($customerID, $this->whiteMember)){
			$day = 4;
		}
		if ($day > 16 || $day <4){
			throw new CHttpException(400, "活动时间还没开始或已结束！");
		}
		$data = $this->getGoodsList();
		if (isset($data[7])){
			$goods[7] = $data[7];
		}else {
			$goods[7] = array();
		}
		if (isset($data[8])){
			$goods[8] = $data[8];
		}else {
			$goods[8] = array();
		}
		if (isset($data[9])){
			$goods[9] = $data[9];
		}else {
			$goods[9] = array();
		}
		if (isset($data[10])){
			$goods[10] = $data[10];
		}else {
			$goods[10] = array();
		}
		$url['jdUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
		$url['oneYuanUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),2);
		$url['tuanUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId());
		$this->render('/v2016/singlesday/goods_index',array(
				'goods' => json_encode($goods),
				'url' => json_encode($url)
		));
	}
	
	/**
	 * 签到
	 */
	public function actionSign(){
		$curTime = time();
		if (!isset($_SESSION['sign_token']) || empty($_SESSION['sign_token'])){
			$_SESSION['sign_token'] = $curTime;
		}else {
			$t = $curTime - $_SESSION['sign_token'];
			if ($t <= 2){  //5秒内不能重复提交
				echo json_encode(array(
						'status' => -1,
						'msg' => '亲，签到太频繁，请稍后再来！'
				));
				exit();
			}else {
				$_SESSION['sign_token'] = $curTime;
			}
		}
		$data = SinglesdayLotteryChance::model()->addSign($this->getUserId());
		echo json_encode($data);
		exit();
	}
	
	/**
	 * 抽签
	 */
	public function actionDraw(){
		$hours = date("G");
		if ($hours >= 0 && $hours <= 8){
			echo json_encode(array(
						'status' => -6,
						'msg' => '活动暂未开始，请于9点后再来！'
				));
				exit();
		}
		$curTime = time();
		if (!isset($_SESSION['draw_token']) || empty($_SESSION['draw_token'])){
			$_SESSION['draw_token'] = $curTime;
		}else {
			$t = $curTime - $_SESSION['draw_token'];
			if ($t <= 2){  //5秒内不能重复提交
				echo json_encode(array(
						'status' => -1,
						'msg' => '亲，抽签太频繁，请稍后再来！'
				));
				exit();
			}else {
				$_SESSION['draw_token'] = $curTime;
			}
		}
		$data = SinglesdayLotteryChance::model()->addDraw($this->getUserInfo());
		echo json_encode($data);
		exit();
	}
	
	/**
	 * 换签
	 */
	public function actionChangeSign(){
		$hours = date("G");
		if ($hours >= 0 && $hours <= 8){
			echo json_encode(array(
					'status' => -5,
					'msg' => '活动暂未开始，请于9点后再来！'
			));
			exit();
		}
		if (!isset($_POST['cid']) || !isset($_POST['prizeID']) || empty($_POST['cid']) || empty($_POST['prizeID'])){
			echo json_encode(array(
					'status' => -1,
					'msg' => '参数错误！'
			));
			exit();
		}
		$curTime = time();
		if (!isset($_SESSION['changesign_token']) || empty($_SESSION['changesign_token'])){
			$_SESSION['changesign_token'] = $curTime;
		}else {
			$t = $curTime - $_SESSION['changesign_token'];
			if ($t <= 2){  //5秒内不能重复提交
				echo json_encode(array(
						'status' => -1,
						'msg' => '亲，换签太频繁，请稍后再来！'
				));
				exit();
			}else {
				$_SESSION['changesign_token'] = $curTime;
			}
		}
		
		$cid = Yii::app()->request->getParam('cid');
		$prizeID = Yii::app()->request->getParam('prizeID');
		$changeID = $cid-$prizeID;
		if ($changeID <= 0){
			echo json_encode(array(
					'status' => -1,
					'msg' => '参数错误！'
			));
			exit();
		}
		$data = SinglesdayLotteryChance::model()->addChangeSign($this->getUserInfo(),$prizeID,$changeID);
		echo json_encode($data);
		exit();
	}
	
	/**
	 * 我的奖励
	 */
	public function actionReward(){
		$data = SinglesdayLotteryChance::model()->getMyReward($this->getUserId());
		$this->render('/v2016/singlesday/prize',array(
			'data' => json_encode($data)		
		));
	}
	
	/**
	 * 活动规则
	 */
	public function actionRule(){
		$this->render('/v2016/singlesday/rule');
	}
	
	/**
	 * 商品
	 * $type 根据type读取不同的商品 （4数码家电，5一元换购，6洗护清洁）
	 */
	public function actionGoods(){
		$day = date("j");
		$customerID = $this->getUserId();
		if (in_array($customerID, $this->whiteMember)){
			$day = 4;
		}
		if ($day >= 15 || $day <4){
			throw new CHttpException(400, "活动时间还没开始或已结束！");
		}
		$type = Yii::app()->request->getParam('type');
		if (empty($type) || !in_array($type, array(4,5,6))){
			throw new CHttpException(400, "参数错误！");
		}
		$data = $this->getGoodsList();
		if (isset($data[$type])){
			$goods[$type] = $data[$type];
		}else {
			$goods[$type] = array();
		}
		$url['jdUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
		$url['oneYuanUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),2);
		$url['tuanUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId());
		$this->render('/v2016/singlesday/goods_nav',array(
				'goods' => json_encode($goods),
				'url' => json_encode($url)
		));
	}
	
	/**
	 * 获取所有商品
	 * @return array
	 */
	private function getGoodsList(){
		//先从缓存里获取
		$redisKey = md5($this->activityName.'_goods_list:');
		$data = Yii::app()->rediscache->get($redisKey);
		if (empty($data) || isset($_GET['pageCache']) && $_GET['pageCache'] == 'false'){
			$data = ActivityGoods::model()->getProducts($this->activityName,true);
			Yii::app()->rediscache->set($redisKey, $data, 86400); //缓存1天
		}
		return $data;
	}
}