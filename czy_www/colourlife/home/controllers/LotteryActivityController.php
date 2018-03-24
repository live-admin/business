<?php
/**
 * 中秋晚会活动
 * @author gongzhiling
 *
 */
class LotteryActivityController extends CController{
	
	public $secret = '^&lot`t*ery*act^*ivi^ty%';
	public $layout = false;

	public function init(){
		$this->checkLogin();
	}
	
	/**
	 * 验证登录
	 * @return boolean
	 */
	private function checkLogin(){
		$isLogin=false;
		if (!isset(Yii::app()->session['lottery_user'])||empty(Yii::app()->session['lottery_user'])){
			$isLogin=true;
		}
		if ( (isset($_GET['user_id']) && isset(Yii::app()->session['lottery_user']['userinfo']['id']) && $_GET['user_id']!=Yii::app()->session['lottery_user']['userinfo']['id'] )){
			$isLogin=true;
		}
		if ( (isset($_GET['aid']) && isset(Yii::app()->session['lottery_user']['aid']) && $_GET['aid']!=Yii::app()->session['lottery_user']['aid'] )){
			$isLogin=true;
		}
		if ($isLogin) {
			$param = $_GET;
			if (empty($param) || !isset($param['sign']) || !isset($param['request_time']))
				$this->redirect('http://mapp.colourlife.com/m.html');
			$sign = new Sign($this->secret);
		
			if (false === $sign->checkSign($param))
				$this->redirect('http://mapp.colourlife.com/m.html');
			if (!isset($_GET['user_id']))
				$this->redirect('http://mapp.colourlife.com/m.html');
			
			$userId=($_GET['user_id'] - 1778) / 778;
			if (ceil($userId)!=$userId)
				$this->redirect('http://mapp.colourlife.com/m.html');
			
			$customer = Customer::model()->find("id=:id and state = 0", array('id' => intval($userId)));
			if (empty($customer))
				$this->redirect('http://mapp.colourlife.com/m.html');
			Yii::app ()->session ['lottery_user'] = array (
					'aid' => $param ['aid'],
					'userinfo' => $customer 
			);
		}
	}
	/**
	 * 首页
	 */
	public function actionIndex(){
		$aid=$this->getAId();
		if (empty($aid)||ceil($aid)!=$aid){
			exit('参数错误！');
		}
		$activity=LotteryActivity::model()->findByPk($aid);
		if (empty($activity)||$activity->state==1){
			exit('活动不存在或已关闭！');
		}
		$pid=0;
		//活动日期
		if (time()<strtotime($activity->start_time)||time()>strtotime($activity->end_time)){
			$status= 0;
			$msg= '活动未开始或已结束！';
		}else {
			//判断抽奖是否已开始
			$prize=LotteryActivityPrize::model()->find("lottery_activity_id=:lottery_activity_id and state=1",array(':lottery_activity_id'=>$aid));
			if (empty($prize)){
				$status= 0;
				$msg= '抽奖未开始！';
			}else {
				$pid=$prize->id;
				$userID=$this->getUserId ();
				//判断是否还有抽奖机会
				$isWinning = LotteryActivityWinningMember::model ()->find ( "lottery_activity_prize_id=:lottery_activity_prize_id and customer_id=:customer_id", array (
						':lottery_activity_prize_id' => $prize->id,
						':customer_id' => $userID
				) );
				if (!empty($isWinning)){
					$status= 0;
					$msg= '您的抽奖机会已用完！';
				}else {
					$status= 1;
					$msg= $prize->prize_name.'抽奖已开始！';
				}
			}
		}
		//中奖名单
		$winningList = LotteryActivityWinningMember::model ()->getWinningList($aid);
		$data = array (
				'status' => $status,
				'msg' => $msg,
				'winningList' => $winningList,
				'pid' => $pid*177+178
		);
		$this->render('/v2016/lotteryActivity/index',$data);
	}
	
	/**
	 * 规则
	 */
	public function actionRule(){
		$this->render('/v2016/lotteryActivity/rule');
	}
	
	/**
	 * 我的中奖名单
	 */
	public function actionWinningList(){
		$aid=$this->getAId();
		$userID=$this->getUserId();
		$list = LotteryActivityWinningMember::model ()->getCustomerWinningList($aid, $userID);
		$this->render('/v2016/lotteryActivity/winningList',array('list'=>json_encode($list)));
	}
	
	/**
	 * 分享功能
	 */
	public function actionShareWeb(){
		$aid=intval(Yii::app()->session['lottery_user']['aid']);
		if (empty($aid)){
			echo json_encode ( array (
					'status' => 0,
					'msg' => '参数错误！' 
			) );
			exit();
		}
		$url=F::getHomeUrl('/lotteryActivity/QRCode/'.$aid);
		echo json_encode ( array (
				'status' => 1,
				'msg' => $url
		) );
	}
	
	/**
	 * 抽奖过程
	 */
	public function actionDraw(){
		$aid=$this->getAId();
		$activity=LotteryActivity::model()->findByPk($aid);
		if (empty($activity)||$activity->state==1){
			echo json_encode ( array (
					'status' => 0,
					'msg' => '抽奖活动已关闭！'
			) );
			exit();
		}

		if (time()<strtotime($activity->start_time)){
			echo json_encode ( array (
					'status' => 0,
					'msg' => '抽奖活动未开始！'
			) );
			exit();
		}
		if (time()>strtotime($activity->end_time)){
			echo json_encode ( array (
					'status' => 0,
					'msg' => '抽奖活动已结束！'
			) );
			exit();
		}
		$prize = LotteryActivityPrize::model ()->find ( "state=1 and type=0 and lottery_activity_id=:lottery_activity_id", array (
				':lottery_activity_id' => $aid 
		) );
		if (empty($prize)||$prize->state==0){
			echo json_encode ( array (
					'status' => 0,
					'msg' => '抽奖未开始！' 
			) );
			exit();
		}
		if ($aid!=$prize->lottery_activity_id){
			echo json_encode ( array (
					'status' => 0,
					'msg' => '抽奖活动出错了！'
			) );
			exit();
		}
		$customer=Yii::app()->session['lottery_user']['userinfo'];
		
		//判断是否已抽过奖
		$isWinning = LotteryActivityWinningMember::model ()->find ( "customer_id=:customer_id and lottery_activity_prize_id=:lottery_activity_prize_id", array (
				':customer_id' => $customer->id,
				':lottery_activity_prize_id' => $prize->id 
		) );
		if (!empty($isWinning)){
			echo json_encode ( array (
					'status' => -1,
					'msg' => '您已抽过奖！'
			) );
			exit();
		}
		
		$result=LotteryActivity::model()->draw($prize, $customer);
		echo json_encode($result);
	}
	
	/**
	 * 生成二维码
	 * @param unknown $id
	 */
	public function actionQRCode($id)
	{
		//引入核心库文件 $id=$id*177+1778; //加密
		include "../../backend/extensions/phpqrcode/phpqrcode.php";
		$url=F::getHomeUrl('/LotteryActivity?aid=').$id;
		$errorLevel = "L";
		//定义生成图片宽度和高度;默认为3
		$size = "8";
		//定义生成内容
		//生成网址类型
		dump(QRcode::png($url, false, $errorLevel, $size));
	}
	
	/**
	 * 解析uid
	 * @param $userId
	 * @return float
	 */
	private function getUserId()
	{
		$customer=Yii::app()->session['lottery_user']['userinfo'];
		return $customer->id;
	}
	/**
	 * 解析aid
	 * @param $aid
	 * @return float
	 */
	private function getAId($aid='')
	{
		if (empty($aid)){
			if (!isset(Yii::app()->session['lottery_user']['aid'])){
				return 0;
			}
			$aid = Yii::app()->session['lottery_user']['aid'];
			
		}
		return (intval($aid) - 1778) / 177;
	}
	
	/**
	 * 判断刷新页面
	 */
	public function actionIsRefresh(){
		if (!isset($_POST['pid'])){
			echo json_encode(array('status'=>0));
			exit();
		}
		$aid=$this->getAId();
		if (empty($aid)){
			echo json_encode(array('status'=>0));
			exit();
		}
		$activity=LotteryActivity::model()->findByPk($aid);
		if (empty($activity)||$activity->state==1){
			echo json_encode(array('status'=>0));
			exit();
		}
		//活动日期
		if (time()<strtotime($activity->start_time)||time()>strtotime($activity->end_time)){
			echo json_encode(array('status'=>0));
			exit();
		}
		//判断抽奖是否已开始
		$prize = LotteryActivityPrize::model ()->find ( "lottery_activity_id=:lottery_activity_id and state=1", array (
				':lottery_activity_id' => $aid 
		) );
		if (empty($_POST['pid'])){
			if (!empty($prize)){
				echo json_encode(array('status'=>1));
				exit();
			}
		}else {
			if (!empty($prize)){
				$pid=intval($_POST['pid']);
				$pid=($pid-178)/177;
				if ($pid!=$prize->id){
					echo json_encode(array('status'=>1));
					exit();
				}else {
					echo json_encode(array('status'=>0));
					exit();
				}
			}else {
				echo json_encode(array('status'=>1));
				exit();
			}
		}
	}
}