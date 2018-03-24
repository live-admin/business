<?php
/*
 * @version 爱就要勾搭
*/
class LoveHookController extends ActivityController{
	public $beginTime='2016-05-20';//活动开始时间
	public $endTime='2016-05-29 23:59:59';//活动结束时间
	public $secret = '^&Hook*Love^%';
	
	public $_mobile;
	public $_userID;
	public $layout = false;
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'Validity-RankList,Rule',
				'signAuth-ShareWeb,Rule,Share,TryPlay,TryPlayEnd',
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
	public function actionIndex() {
		$this->checkUser();
		// 记录日志
		LoveHook::model ()->addLog ( $this->_userID, 1, 0 );
		// 每天赠送3次
		LoveHook::model ()->addChanceByDay ( $this->_userID, 1 );
		// 彩富用户送1次
		$isCaiFu = LoveHook::model ()->isCaiFu ( $this->_userID );
		if ($isCaiFu) {
			LoveHook::model ()->addChanceByDay ( $this->_userID, 2 );
		}
		// 总次数
		$chance = LoveHook::model ()->getAllChance ( $this->getUserId () );
		$time = time ();
		$sign = md5 ( 'cf=colourlife' . '&ts=' . $time );
		// 分享url
		$url = F::getHomeUrl ( '/LoveHook/ShareWeb' ) . '?ts=' . $time . '&sign=' . $sign;
		
		$this->renderPartial ( '/v2016/loveisUp/index', array (
				'url' => base64_encode ( $url ),
				'chance'=>$chance 
		) );
	}
	
	/**
	 * 三名排名
	 */
	public function actionIsRank() {
		// 排名
		$rank = LoveHook::model ()->thirdRanking ( $this->getUserId () );
		echo json_encode ( array (
				'currentUserID' => $this->getUserId (),
				'rank' => $rank 
		) );
	}
	
	/**
	 * 点击开始玩游戏
	 */
	public function actionIsJump() {
	// 记录日志
		LoveHook::model ()->addLog ( $this->getUserId (), 5, 0 );
		// 总次数
		$chance = LoveHook::model ()->getAllChance ( $this->getUserId () );
		if ($chance > 0) {
			echo json_encode ( array (
					'status' => 1 
			) );
		} else {
			$tips = LoveHook::model ()->getTips ( $this->getUserId () ); // 获取提示语
			$data ['status'] = 0;
			$data = array_merge ( $data, $tips );
			echo json_encode ( $data );
		}
	}
	
	/**
	 * 点击分享按钮
	 */
	public function actionFenXiang() {
		// 记录日志
		LoveHook::model ()->addLog ( $this->getUserId (), 2, 0 );
		$num = LoveHook::model ()->shareNum ( $this->getUserId () );
		// 分享添加次数
		if ($num>=0 && $num < 3) {
			LoveHook::model ()->addChance ( $this->getUserId (), 4, 1 );
		}
	}
	/**
	 * 规则
	 */
	public function actionRule(){
		$this->renderPartial('/v2016/loveisUp/rule');
	}
	
	/**
	 * 开始玩游戏
	 */
	public function actionPlay(){
		//总次数
		$chance=LoveHook::model()->getAllChance($this->getUserId());
		if ($chance <= 0) {
			exit ( "次数不足！" );
		}
		//记录日志
		LoveHook::model()->addLog($this->getUserId(),6,0);
		$this->renderPartial('/v2016/loveisUp/play',array('chance'=>$chance-1));
	}
	
	/**
	 * 次数减1
	 */
	public function actionReduce(){
		//次数减1
		$chanceResult=LoveHook::model()->addChance($this->getUserId(),7,'-1');
		if (!$chanceResult){
			exit ( "网络出错！" );
		}
	}
	
	/**
	 * 游戏结束
	 */
	public function actionPlayEnd(){
		$Integ=0;
		if (isset($_GET['p'])&&intval($_GET['p'])>0){
			$Integ=intval($_GET['p']);
		}
		// 总次数
		$chance = LoveHook::model ()->getAllChance ( $this->getUserId () );
		$this->renderPartial('/v2016/loveisUp/gameover',array('integ'=>$Integ,'chance'=>$chance));
	}
	
	/**
	 * 勾到的礼品
	 */
	public function actionHookGift(){
		if (!isset($_POST['points'])&&!isset($_POST['tiems'])){
			echo json_encode(array('status'=>0,'msg'=>'参数错误'));
			exit();
		}
		$this->checkUser();
		$gchance=0;
		$Integ=0;
		//积分
		if (isset($_POST['points'])&&intval($_POST['points'])>0){
			$Integ=intval($_POST['points']);
			$IntegResult=LoveHook::model()->addIntegration($this->_userID,$this->_mobile,$Integ);
		}
		//获得的游戏次数
		if (isset($_POST['tiems'])&&intval($_POST['tiems'])>0){
			$gchance=  intval($_POST['tiems']);
		}
		//添加次数
		if ($gchance>0){
			$chanceResult=LoveHook::model()->addChance($this->_userID,6,$gchance);
		}
		//添加积分
		if ($Integ>0){
			echo json_encode(array('url'=>$this->createUrl('PlayEnd',array('p'=>$Integ))));
		}else {
			echo json_encode(array('url'=>$this->createUrl('PlayEnd')));
		}
		
	}
	/**
	 * 排名列表
	 */
	public function actionRankList(){
		$rankList=LoveHook::model()->ranking();
		$this->renderPartial('/v2016/loveisUp/ranking',array('rankList'=>$rankList,'currentUserID'=>$this->getUserId()));
	}
	
	/**
	 * 分享页
	 */
	public function actionShareWeb(){
		$time=Yii::app()->request->getParam('ts');
		$sign=Yii::app()->request->getParam('sign');
		$checkSign=md5('cf=colourlife'.'&ts='.$time);
		if ($sign!=$checkSign){
			exit ('验证失败！');
		}
		$openID=0;
		if(!empty(Yii::app()->session['wx_user']['openid'])){
			$openID= Yii::app()->session['wx_user']['openid'];
		}
		//记录日志
		LoveHook::model()->addLog(0,3,$openID);
		$this->renderPartial('/v2016/loveisUp/share');
	}
	
	/**
	 * 开始试玩
	 */
	public function actionTryPlay(){
		$openID=0;
		if(!empty(Yii::app()->session['wx_user']['openid'])){
			$openID= Yii::app()->session['wx_user']['openid'];
		}
		//记录日志
		LoveHook::model()->addLog(0,6,$openID);
		$this->renderPartial('/v2016/loveisUp/share_play');
	}
	
	/**
	 * 试玩结束
	 */
	public function actionTryPlayEnd(){
		$Integ=0;
		if (isset($_GET['p'])&&intval($_GET['p'])>0){
			$Integ=intval($_GET['p']);
		}
		$this->renderPartial('/v2016/loveisUp/share_over',array('integ'=>$Integ));
	}
	
	/**
	 * 检验用户信息是否存在
	 */
	public function checkUser(){
		$customer=$this->getUserInfo();
		if (empty($customer)){
			exit("用户信息错误！");
		}
		$this->_userID=$customer->id;
		$this->_mobile=$customer->mobile;
	}
}