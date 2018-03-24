<?php

/**
 * 3.15权益活动
 * @author gongzhiling
 *
 */
class RightsController extends CController{
	private $_userId=0;
	private $_startDay='2016-04-11 10:00:00';
	private $_endDay='2017-04-20 23:59:59';
	
	public function init(){
		//活动日期
		if (time()<strtotime($this->_startDay)||time()>strtotime($this->_endDay)){
			exit('<h1>活动还没开始或已结束</h1>');
		}
		$this->checkLogin();
	}

	/**
	 * 权益活动
	 */
	public function actionIndex(){
		exit('<h1>活动还没开始或已结束</h1>');
		$SetableSmallLoansModel = new SetableSmallLoans();
		$jingdong = $SetableSmallLoansModel->searchByIdAndType('jd', '', $this->_userId,false);
		$huanqiu = $SetableSmallLoansModel->searchByIdAndType('anshi', '', $this->_userId,false);
		//dump($jingdong);
		$this->renderPartial("index",array('jd_url'=>$jingdong->completeURL,'huanqiu_url'=>$huanqiu->completeURL));
	}

	/*
	 *京东运动
	  */
	public function actionSport()
	{
		$SetableSmallLoansModel = new SetableSmallLoans();
		$jingdong = $SetableSmallLoansModel->searchByIdAndType('jd', '', $this->_userId,false);
		$this->renderPartial("sport",array('jd_url'=>$jingdong->completeURL));
	}

	/**
	 * 验证登录
	 */
	private function checkLogin()
	{
		if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])) {
			exit('<h1>用户信息错误，请重新登录</h1>');
		} else {
			$custId = 0;
	
			if (isset($_REQUEST['cust_id'])) {  //优先有参数的
				$custId = intval($_REQUEST['cust_id']);
				$_SESSION['cust_id'] = $custId;
			} else if (isset($_SESSION['cust_id'])) {  //没有参数，从session中判断
				$custId = $_SESSION['cust_id'];
			}
			$custId=intval($custId);
			$customer = Customer::model()->find("id=:id and state = 0", array('id' => $custId));
			if (empty($custId) || empty($customer)) {
				exit('<h1>用户信息错误，请重新登录</h1>');
			}
			$this->_userId = $custId;
//			$this->_username = $customer->username;
//			//点赞才使用
//			if (empty($this->_isAnnounce)){
//				$result=ThanksGivingResult::model()->find("customer_id=:cust_id and day =:day", array(':cust_id' => $custId,':day'=>date("Ymd")));
//				if ($result) {
//					$this->_luckyTodayCan = 1;
//				}
//			}
		}
	}
}