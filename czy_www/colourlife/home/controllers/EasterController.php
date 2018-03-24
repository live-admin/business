<?php

/**
 * 复活节活动
 * @author gongzhiling
 * @copyright 2016-03-11 15:44
 */
class EasterController extends CController{
	private $_startDay='2016-03-23 00:00:00';
	private $_endDay='2016-04-01 23:59:59';
	private $_userId=0;
	private $_mobile=0;
	public function init(){
		//活动日期
		if (time()<strtotime($this->_startDay)||time()>strtotime($this->_endDay)){
			exit('<h1>活动还没开始或已结束</h1>');
		}
		$share=Yii::app()->request->getParam('s');
		//判断是否为分享页，分享页不需要登录
		if (empty($share)){
			$this->checkLogin();
		}
		
	}
	/**
	 * 首页
	 */
	public function actionIndex(){
		//是否显示弹窗
		$is_show=Easter::model()->isShowTips($this->_userId);
		Easter::model()->addLog($this->_userId,1);  //记录打开次数
		Easter::model()->addChallengeChance($this->_userId,1,5);  //系统每天赠送5次机会
		//获取总的挑战机会
		$c_chance=Easter::model()->getChances($this->_userId,1);
		//获取砸蛋机会
		$s_chance=Easter::model()->getChances($this->_userId,2);
		$validate=md5('colourlife'.date("Ymd"));
		$uid=$this->_userId+1778;
		$this->renderPartial('index',array('c_chance'=>$c_chance,'s_chance'=>$s_chance,'is_show'=>$is_show,'validate'=>$validate,'uid'=>$uid));
	}
	
	/**
	 * 砸蛋机会入库
	 */
	public function actionChallenge(){
		if (!isset($_POST['time'])){
			echo json_encode(array('status'=>0,'msg'=>'参数错误!'));
			exit();
		}
		//获取总的挑战机会
		$c_chance=Easter::model()->getChances($this->_userId,1);
		if ($c_chance<=0){
			echo json_encode(array('status'=>0,'msg'=>'挑战机会已用完!'));
			exit();
		}
		//过滤参数
		$time=FormatParam::formatGetParams($_POST['time']);
		$result=Easter::model()->addSmashedEgg($this->_userId,$time);
		if ($result){
			//获取砸蛋机会
			$s_chance=Easter::model()->getChances($this->_userId,2);
			echo json_encode(array('status'=>1,'Cchance'=>$c_chance-1,'Schance'=>$s_chance));
		}else {
			echo json_encode(array('status'=>0,'msg'=>'操作失败!'));
		}
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
			$this->_mobile = $customer->mobile;
		}
	}
	
	/**
	 * 活动规则
	 */
	public function actionRule(){
		$this->renderPartial('rule');
	}
	
	/**
	 * 砸蛋抽奖操作
	 */
	public function actionDraw(){
		if (!isset($_POST['param'])||$_POST['param']!=md5('colourlife'.date("Ymd"))){
			echo json_encode(array('status'=>0,'msg'=>'非法操作！'));
			exit();
		}
		$s_chance=Easter::model()->getChances($this->_userId,2);
		if ($s_chance<=0){
			echo json_encode(array('status'=>0,'msg'=>'今天机会已用完！'));
			exit();
		}
		$prize=Easter::model()->draw($this->_userId,$this->_mobile);
		if (!empty($prize)){
			echo json_encode(array('status'=>1,'prizeID'=>$prize-1,'s_chance'=>$s_chance-1));
		}else {
			echo json_encode(array('status'=>0,'msg'=>'操作失败'));
		}
	}
	
	/**
	 * 分享页
	 */
	public function actionShare(){
		Easter::model()->addLog(0,4);  //记录打开分享页次数
		$validate=md5('colourlife_share'.date("Ymd"));
		$uid=FormatParam::formatGetParams($_GET['uid']);
		$this->renderPartial('share',array('validate'=>$validate,'uid'=>$uid));
	}
	
	/**
	 * 分享页抽奖，返回奖品ID
	 */
	public function actionShareDraw(){
		if (!isset($_POST['validate'])||$_POST['validate']!=md5('colourlife_share'.date("Ymd"))){
			echo json_encode(array('status'=>0,''=>'非法操作！'));
			exit();
		}
		$rid=Easter::model()->shareLottery();
		echo json_encode(array('status'=>1,'rid'=>$rid));
	}
	
	/**
	 * 输入手机号领取奖品
	 */
	public function actionShareReceive(){
		//过滤参数
		$mobile=FormatParam::formatGetParams($_POST['mobile']);
		$rid=FormatParam::formatGetParams($_POST['rid']);
		$uid=FormatParam::formatGetParams($_POST['uid']);
		if (empty($mobile)||empty($rid)||empty($uid)){
			echo json_encode(array('msg'=>'参数错误！'));
			exit();
		}
		if ($rid>=3){
			$user_id=$uid-1778;
			$customer = Customer::model()->find("id=:id and state = 0", array('id' => $user_id));
			if (!empty($customer)&&$customer->mobile==$mobile){
				echo json_encode(array('msg'=>'不能领取自己分享的游戏奖励！'));
				exit();
			}
			$p_result=Easter::model()->shareReceive($mobile,$rid);
			echo json_encode($p_result);
		}
	}
	
	/**
	 * 记录统计
	 */
	public function actionLog(){
		if (!empty($_POST['type'])){
			$type=intval($_POST['type']);
			Easter::model()->addLog($this->_userId,$type);  //记录点击次数
			if ($type==2){
				$result=Easter::model()->addChallengeChance($this->_userId,4,3);  //分享获得3次挑战机会
				if (!empty($result)){
					//获取总的挑战机会
					$c_chance=Easter::model()->getChances($this->_userId,1);
					echo json_encode(array('status'=>1,'chance'=>$c_chance));
				}
			}
		}
	}
}
