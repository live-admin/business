<?php
/**
 * 妇女节活动
 * @author gongzhiling
 *
 */
class WomensdayController extends ActivityController{
	public $beginTime='2017-03-06 00:00:00';//活动开始时间
	public $endTime='2017-03-08 23:59:59';//活动结束时间
	public $secret = 'W~^om$e%ns^da*y';
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
	 * 抽奖首页
	 */
	public function actionIndex(){
		$customer = $this->getUserInfo();
		if (empty($customer)){
			throw new CHttpException(400, "请先登录！");
		}
		if (isset($_GET['ftype']) && $_GET['ftype'] == 'wsq'){
			$type = 1;
		}else {
			$type = 2;
		}
		WomensdayChance::model()->addLog($this->getUserId(),$type);
		//获取抽奖机会
		$data['chance'] = WomensdayChance::model()->getChance($customer->id);
		$this->render('/v2017/womensday/index',$data);
	}
	
	/**
	 * 规则页
	 */
	public function actionRule(){
		WomensdayChance::model()->addLog($this->getUserId(),4);
		$this->render('/v2017/womensday/rule');
	}
	
	/**
	 * 中奖记录
	 */
	public function actionPrizeList(){
		WomensdayChance::model()->addLog($this->getUserId(),3);
		$prize = WomensdayChance::model()->getPrizeList($this->getUserId());
		$this->render('/v2017/womensday/prize_list',array('prize' => $prize));
	}
	
	/**
	 * 抽奖功能
	 */
	public function actionLottery(){
//		echo json_encode(array(
//				'status' => 0,
//				'msg' => '系统正在开小差，请稍后再试！'
//			));
//			exit();
		$this->isLimitRequest();
		$customer = $this->getUserInfo();
		if (empty($customer)){
			echo json_encode(array(
				'status' => 0,
				'msg' => '用户不存在！'
			));
			exit();
		}
		WomensdayChance::model()->addLog($this->getUserId(),6);
		$result = WomensdayChance::model()->addDraw($customer);
		echo json_encode($result);
		exit();
	}
	
	/**
	 * 兑换
	 */
	public function actionChange(){
		$this->isLimitRequest();
		$pid = isset($_POST['pid']) ? intval($_POST['pid']) : '';
		$uname = isset($_POST['uname']) ? FormatParam::formatGetParams($_POST['uname']) : '';
		$mobile = isset($_POST['mobile']) ? FormatParam::formatGetParams($_POST['mobile']) : '';
		if (empty($pid) || empty($uname) || empty($mobile)){
			echo json_encode(array(
					'status' => 0,
					'msg' => '参数不能为空！'
			));
			exit();
		}
		WomensdayChance::model()->addLog($this->getUserId(),5,$pid);
		$department = isset($_POST['department']) ? FormatParam::formatGetParams($_POST['department']) : '';
		$address = isset($_POST['address']) ? FormatParam::formatGetParams($_POST['address']) : '';
		
		$result = WomensdayChance::model()->changePrize($this->getUserId(), $pid, $uname, $mobile,$department,$address);
		echo json_encode($result);
		exit;
	}
	
	/**
	 * 商品列表
	 */
	public function actionGoodsList(){
		$goodsList = WomensdayChance::model()->getGoodsList();
		$homeConfig=new HomeConfigResource();
		$href3=$homeConfig->getResourceByKeyOrId(39,1,$this->getUserId()); //京东67,彩特供39
		if (!empty($href3)){
			$url = $href3->completeURL;
		}else {
			$url = '';
		}
		$this->render('/v2017/womensday/list',array('goodsList' => $goodsList,'url' => $url));
	}
	
	public function actionNotifyAgain(){
		$userID = $this->getUserId();
		if ($userID != 1880959){
			exit("非法操作！");
		}
		WomensdayChance::model()->nofityAgianWsq();
	}
}