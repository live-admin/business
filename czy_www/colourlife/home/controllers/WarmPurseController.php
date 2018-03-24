<?php
/**
 * 暖荷包控制器
 * @author gongzhiling
 * @date 2015-12-8
 * @dept  array('1'=>'cfrs','2'=>'E装修','3'=>'E租房','4'=>'E维修','5'=>'花样保险')
 */
class WarmPurseController extends CController{
	private $_userId = 0;
	private $_username;
	private $startTime=9;
	private $endTime=21;
	private $day;
	private $customer_code;
	//private $taskArr=array('1'=>'cfrs','2'=>'E装修','3'=>'E租房','4'=>'E维修','5'=>'花样保险','6'=>'终极奖');
	
	/**
	 * 初始化
	 * @see CController::init()
	 */
	public function init(){
		$this->checkLogin();
		$this->day=date("Ymd");
		date_default_timezone_set('Asia/Shanghai');
		//活动日期
		if (time()<strtotime(Item::INVITE_REGISTER_START_TIME)||time()>strtotime(Item::INVITE_REGISTER_END_TIME)){
			exit('<h1>活动还没开始或已结束</h1>');
		}
		//活动时间
		if(intval(date('H'))<$this->startTime||intval(date('H'))>$this->endTime){
			exit('<h1>还没到活动时间</h1>');
		}
	}
	
	/**
	 * 暖荷包首页
	 */
	public function actionIndex(){
		$received1=false;
		$received2=false;
		$able_rec=false;
		$warmPurse1=false;
		$warmPurse2=false;
		$warmPurse3=false;
		$warmPurse4=false;
		$warmPurse5=false;
		$rec_invite=false;
		//取每天总数
		$count=PraiseCount::model()->find("type=1 and day=:day",array(':day'=>$this->day));
		if (!empty($count)&&$count['total']>0){
			$total=$count['total'];
		}else {
			$total=0;
		}
		//判断是否已领过
		$criteria = new CDbCriteria;
        $criteria->compare('day', $this->day);//时间
        $criteria->compare('customer_id',$this->_userId);//用户ID
        $rec_ticket=ReceiveTicket::model()->findAll($criteria);
		if (!empty($rec_ticket)){
			foreach ($rec_ticket as $v){
				switch ($v->type){
					case 1:
						$received1=true;
						break;
					case 2:
						$received2=true;
						break;
				}
			}
		}
		//判断是否完成了任务
		$criteria1 = new CDbCriteria;
		$criteria1->compare('day', $this->day);//时间
		$criteria1->compare('customer_id',$this->_userId);//用户ID
		$warmPurse=WarmPurse::model()->findAll($criteria1);
		if (!empty($warmPurse)){
			foreach ($warmPurse as $v){
				switch ($v->type){
					case 1:
						$warmPurse1=true;
						break;
					case 2:
						$warmPurse2=true;
						break;
					case 3:
						$warmPurse3=true;
						break;
					case 4:
						$warmPurse4=true;
						break;
					case 5:
						$warmPurse5=true;
						break;						
				}
			}
			if ($warmPurse1&&$warmPurse2&&$warmPurse3&&$warmPurse4&&$warmPurse5){
				$able_rec=true;
			}
		}
		//还没领过的可以领取
		if (!$received2){
			//判断是否邀请够三人以上
			$invite = Invite::model()->findAll("customer_id=:customer_id and status=1 and effective=1 and model='customer' and FROM_UNIXTIME(create_time,'%Y%m%d')=:create_day", array(':customer_id'=>$this->_userId,':create_day' => $this->day));
			if (count($invite)>=3){
				$rec_invite=true;
			}
		}
		$this->renderPartial('index',
				array(
						'total' => $total,
						'code'=>$this->customer_code,
						'warmPurse1'=>$warmPurse1,
						'warmPurse2'=>$warmPurse2,
						'warmPurse3'=>$warmPurse3,
						'warmPurse4'=>$warmPurse4,
						'warmPurse5'=>$warmPurse5,
						'uid'=>md5($this->_userId),
						'received1'=>$received1,
						'received2'=>$received2,
						'able_rec'=>$able_rec,
						'rec_invite'=>$rec_invite
				));
	}
	
	public function actionRule(){
		$this->renderPartial('rule');
	}
	
	/**
	 * 财富人生入口
	 */
	public function actionCFRS(){
		if (!isset($_POST['id'])||$_POST['id']!=md5($this->_userId)){
			echo 2;
			exit();
		}
		$cfrs=WarmPurse::model()->find("type=1 and day=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (empty($cfrs)){
			$add_cfrs=$this->add_warm_purse(1, 0.5, 1);
		}
		echo 1;
	}
	
	/**
	 * E装修
	 */
	public function actionEDecorate(){
		$cfrs=WarmPurse::model()->find("type=1 and day=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (empty($cfrs)){
			$this->redirect(array("index"));
		}
		$EDecorate=WarmPurse::model()->find("type=2 and day=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (empty($EDecorate)){
			$add_EDecorate=$this->add_warm_purse(2, 1, 2);
		}
		$url=$this->get_url('EZHUANGXIU');
		$this->redirect($url);
	}
	
	/**
	 * E维修
	 */
	public function actionERepair(){
		if (!isset($_POST['id'])||$_POST['id']!=md5($this->_userId)){
			echo 2;
			exit();
		}
		$EDecorate=WarmPurse::model()->find("type=2 and day=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (empty($EDecorate)){
			echo 3;
			exit();
		}
		$ERepair=WarmPurse::model()->find("type=3 and day=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (empty($ERepair)){
			$add_ERepair=$this->add_warm_purse(2, 1, 3);
		}
		echo 1;
	}
	
	/**
	 * E租房
	 */
	public function actionERent(){
		$ERepair=WarmPurse::model()->find("type=3 and day=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (empty($ERepair)){
			$this->redirect(array("index"));
		}
		$ERent=WarmPurse::model()->find("type=4 and day=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (empty($ERent)){
			$add_ERent=$this->add_warm_purse(3, 1, 4);
		}
		$url=$this->get_url('MIANYONGZUFANG');
		$this->redirect($url);
	}
	
	/**
	 * 花样保险
	 */
	public function actionHYBX(){
		$ERent=WarmPurse::model()->find("type=4 and day=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (empty($ERent)){
			$this->redirect(array("index"));
		}
		$HYBX=WarmPurse::model()->find("type=5 and day=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (empty($HYBX)){
			$add_HYBX=$this->add_warm_purse(4, 0.5, 5);
		}
		$url=$this->get_url('hybx');
		$this->redirect($url);
	}
	
	
	/**
	 * 添加数据
	 * @param unknown $task
	 * @param unknown $tickets
	 * @param unknown $type
	 * @return boolean
	 */
	private function add_warm_purse($task,$tickets,$type){
		if (empty($task)||empty($tickets)||empty($type)){
			return false;
		}
		$warmPurse=new WarmPurse();
		$warmPurse->customer_id=$this->_userId;
		$warmPurse->task=$task;
		$warmPurse->type=$type;
		$warmPurse->tickets=$tickets;
		$warmPurse->day=$this->day;
		$warmPurse->create_time=time();
		if ($warmPurse->save()){
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取链接
	 * @param unknown $type
	 * @return string
	 */
	private function get_url($type){
		if (empty($type)){
			return array('index');
		}
		$mModel=new SetableSmallLoans();
		$info=$mModel->searchByIdAndType($type,1,$this->_userId,false);
		if ($info && $info->completeURL){
			return $info->completeURL;
		}else {
			return array('index');
		}
	}
	
	/**
	 * 领取奖励
	 */
	public function actionReward(){
		if (!isset($_POST['id'])||$_POST['id']!=md5($this->_userId)){
			echo json_encode(array('status'=>0,'msg'=>'非法操作'));
			exit();
		}
		//取每天总数
		$count=PraiseCount::model()->find("type=1 and day=:day",array(':day'=>$this->day));
		if (!empty($count)&&$count['total']>=2000){
			echo json_encode(array('status'=>0,'msg'=>'今日已领完'));
			exit();
		}
		//判断是否已领过
		$rec_ticket=ReceiveTicket::model()->find("day=:day and customer_id=:customer_id and type=1",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (!empty($rec_ticket)){
			echo json_encode(array('status'=>0,'msg'=>'今日已领过，明天再来！'));
			exit();
		}
		$red_packet=RedPacket::model()->find("sn='invite_code1' and type=1 and from_type=10 and FROM_UNIXTIME(create_time,'%Y%m%d')=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (!empty($red_packet)){
			echo json_encode(array('status'=>0,'msg'=>'今日已领过，明天再来！'));
			exit();
		}
		//判断是否完成了任务
		$warmPurse=WarmPurse::model()->findAll("day=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (count($warmPurse)==5&&$warmPurse[0]->type==1&&$warmPurse[1]->type==2&&$warmPurse[2]->type==3&&$warmPurse[3]->type==4&&$warmPurse[4]->type==5){
			$sendMoney = 3;
			$items = array(
					'customer_id' => $this->_userId,//用户的ID
					'from_type' => Item::RED_PACKET_FROM_TYPE_WARM_PURSE,
					'sum' =>$sendMoney,//红包金额,
					'sn' => 'invite_code1',
			);
			$transaction = Yii::app()->db->beginTransaction();
			$redPacked = new RedPacket();
			//把对应的邀请记录设置成已发送红包
			try{
				$ret=$redPacked->addRedPacker($items);
				$praise_result=false;
				$rec_result=false;
				
				if ($ret){
					//领取奖励记录
					$recTicket=new ReceiveTicket();
					$recTicket->customer_id=$this->_userId;
					$recTicket->sum=$sendMoney;
					$recTicket->type=1;
					$recTicket->day=$this->day;
					$recTicket->create_time=time();
					$rec_result=$recTicket->save();
					if (empty($rec_result)){
						$transaction->rollback();
					}
				}
				if ($rec_result){
					//统计总数
					if (empty($count)){
						$praise=new PraiseCount();
						$praise->total=1;
						$praise->type=1;
						$praise->day=$this->day;
						$praise->addtime=time();
						$praise->updatetime=time();
						$praise_result=$praise->save();
					}else {
						//取每天总数
						$ptotal=PraiseCount::model()->find("type=1 and day=:day",array(':day'=>$this->day));
						if (!empty($ptotal)&&$ptotal['total']>=2000){
							$transaction->rollback();
						}
						$praise_result=PraiseCount::model ()->updateAll ( array (
								"total" => new CDbExpression ( "total+1" )
						), "id=$count->id");
					}
					if (empty($praise_result)){
						$transaction->rollback();
					}
				}
				if($praise_result){
					Yii::log("冬日饭票,送红包成功".json_encode($items),CLogger::LEVEL_INFO,'colourlife.core.WarmPurse');
				}else{
					Yii::log("冬日饭票,送红包失败".json_encode($items),CLogger::LEVEL_ERROR,'colourlife.core.WarmPurse');
				}
				$transaction->commit();
				echo json_encode(array('status'=>1,'msg'=>'领取成功！'));
			}catch(Exception $e) {
				Yii::log("try异常：".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.WarmPurse');
				$transaction->rollBack();   // 在异常处理中回滚
				echo json_encode(array('status'=>0,'msg'=>'领取失败！'));
			}
		}else {
			echo json_encode(array('status'=>0,'msg'=>'先完成任务才可以领取哦！'));
		}
	}
	
	/**
	 * 邀请送饭票
	 */
	public function actionInviteReward(){
		if (!isset($_POST['id'])||$_POST['id']!=md5($this->_userId)){
			echo json_encode(array('status'=>0,'msg'=>'非法操作'));
			exit();
		}
		//取每天总数
		$count=PraiseCount::model()->find("type=2 and day=:day",array(':day'=>$this->day));
		if (!empty($count)&&$count['total']>=2000){
			echo json_encode(array('status'=>0,'msg'=>'今日已领完'));
			exit();
		}
		//判断是否已领过
		$rec_ticket=ReceiveTicket::model()->find("day=:day and customer_id=:customer_id and type=2",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (!empty($rec_ticket)){
			echo json_encode(array('status'=>0,'msg'=>'今日已领过，明天再来！'));
			exit();
		}
		$red_packet=RedPacket::model()->find("sn='invite_code2' and type=1 and from_type=10 and FROM_UNIXTIME(create_time,'%Y%m%d')=:day and customer_id=:customer_id",array(':day'=>$this->day,':customer_id'=>$this->_userId));
		if (!empty($red_packet)){
			echo json_encode(array('status'=>0,'msg'=>'今日已领过，明天再来！'));
			exit();
		}
	//判断是否邀请够三人以上
		$invite = Invite::model()->findAll("status=1 and effective=1 and is_send=0 and customer_id=:customer_id and model='customer' and FROM_UNIXTIME(create_time,'%Y%m%d')=:create_day", array(':customer_id'=>$this->_userId,':create_day' => $this->day));
		if (count($invite)>=3){
			$sendMoney = 2;
			$items = array(
					'customer_id' => $this->_userId,//用户的ID
					'from_type' => Item::RED_PACKET_FROM_TYPE_INVITE,
					'sum' =>$sendMoney,//红包金额,
					'sn' => 'invite_code2',
			);
			$transaction = Yii::app()->db->beginTransaction();
			$redPacked = new RedPacket();
			//把对应的邀请记录设置成已发送红包
			try{
				$ret=$redPacked->addRedPacker($items);
				$praise_result=false;
				$rec_result=false;
				if ($ret){
					//领取奖励记录
					$recTicket=new ReceiveTicket();
					$recTicket->customer_id=$this->_userId;
					$recTicket->sum=$sendMoney;
					$recTicket->type=2;
					$recTicket->day=$this->day;
					$recTicket->create_time=time();
					$rec_result=$recTicket->save();
					if (empty($rec_result)){
						$transaction->rollback();
					}
				}
				if ($rec_result){
					//统计总数
					if (empty($count)){
						$praise=new PraiseCount();
						$praise->total=1;
						$praise->type=2;
						$praise->day=$this->day;
						$praise->addtime=time();
						$praise->updatetime=time();
						$praise_result=$praise->save();
					}else {
						//取每天总数
						$ptotal=PraiseCount::model()->find("type=1 and day=:day",array(':day'=>$this->day));
						if (!empty($ptotal)&&$ptotal['total']>=2000){
							$transaction->rollback();
						}
						$praise_result=PraiseCount::model ()->updateAll ( array (
								"total" => new CDbExpression ( "total+1" )
						), "id=$count->id");
					}
					if (empty($praise_result)){
						$transaction->rollback();
					}
				}
				if($praise_result){
					foreach ($invite as $v){
						Invite::model()->updateByPk($v->id,array('is_send'=>1));
					}
					Yii::log("邀请好友注册,送红包成功".json_encode($items),CLogger::LEVEL_INFO,'colourlife.core.InviteWarmPurse');
				}else{
					Yii::log("邀请好友注册,送红包失败".json_encode($items),CLogger::LEVEL_ERROR,'colourlife.core.InviteWarmPurse');
				}
				$transaction->commit();
				echo json_encode(array('status'=>1,'msg'=>'领取成功！'));
			}catch(Exception $e) {
				Yii::log("try异常：".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.InviteWarmPurse');
				$transaction->rollBack();   // 在异常处理中回滚
				echo json_encode(array('status'=>0,'msg'=>'领取失败！'));
			}
		}else {
			echo json_encode(array('status'=>0,'msg'=>'要邀请三人或以上且当天注册成功才可以领取哦！'));
		}
	}
	
	/**
	 * 邀请好友注册
	 */
	public function actionInviteFriend(){
		date_default_timezone_set('Asia/Shanghai');
		/* if(intval(date('H'))<9||intval(date('H'))>21){
			echo json_encode(array('status'=>0,'msg'=>'还没到活动时间！'));
			exit();
		} */
		if (empty($_POST['tels'])){
			echo json_encode(array('status'=>0,'msg'=>'参数为空！'));
			exit();
		}
		$mobile=$_POST['tels'];
		$tmp='';
		if (strpos($mobile, ",")!==false){
			$mobile=explode(",", $mobile);
			foreach ($mobile as $val){
				if ( ! preg_match('/^(1[3456789])[0123456789]{9}$/', $val)) {
					$tmp.=$val.'格式错误===';
					continue;
				}
				$result=$this->invite_register($val);
				$tmp.=$val.$result.'===';
			}
			
		}else {
			$tmp=$this->invite_register($mobile);
		}
		echo json_encode(array('msg'=>$tmp));
	}
	/**
	 * 邀请好友注册
	 * 当天的活动时间：9:00-21:00
	 */
	private function invite_register($mobile){
		if (empty($mobile)){
			return 'msg1:参数为空';  //参数为空
		}
		$customer = Customer::model()->find('mobile=:mobile', array(':mobile' => $mobile));
			if (!isset($customer)) {
				$invite = Invite::model()->find("mobile=:mobile and model='customer' and FROM_UNIXTIME(create_time,'%Y%m%d')=:create_day and customer_id=:customer_id", array(':mobile' => $mobile,':create_day' => $this->day,':customer_id' => $this->_userId));
	
				if (isset($invite)&&$invite->customer_id!=$this->_userId) {
					return 'msg2:邀请的好友已经被其他人邀请了，暂时不能邀请';  //邀请的好友已经被其他人邀请了，暂时不能邀请
				} else {
					if(!isset($invite)){
						$sms = Yii::app()->sms;
						$sms->mobile = $mobile;
						$sms->userMobile = $this->_username;
						$sms->inviteCode=$this->customer_code;
						$sms->setType('inviteCode', array('mobile' => $mobile));
						$sms->sendUserMessage('inviteCodeTemplate');
						
						$model = Invite::model()->find('mobile=:mobile and model = "customer" and customer_id=:customer_id',
								array(':mobile' => $mobile, ':customer_id' => $this->_userId));
						if(!$model){
							$model = new Invite();
						}
						$model->customer_id = $this->_userId;
						$model->mobile = $mobile;
						$model->create_time = time();
						$model->valid_time = time()+intval(Yii::app()->config->invite['validTime']);
						if(!$model->save()){
							return 'msg3:邀请没有正常发出'; //邀请没有正常发出
						}else{
							return 'msg4:邀请成功';  //邀请成功
						}
					}else{
						if($invite->create_time <= (time() - 120)){
							$invite->create_time = time();
							$invite->save();
							$sms = Yii::app()->sms;
							$sms->mobile = $mobile;
							$sms->userMobile = $this->_username;
							$sms->inviteCode=$this->customer_code;
							$sms->setType('inviteCode', array('mobile' => $mobile));
							$sms->sendUserMessage('inviteCodeTemplate');
							return 'msg4:邀请成功'; //邀请成功
						}else{
							return 'msg5:已经邀请过好友了，如果好友未收到短信，请2分钟后重试或邀请其他好友注册拿红包'; //已经邀请过好友了，如果好友未收到短信，请2分钟后重试或邀请其他好友注册拿红包。
						}
					}
				}
			} else{
				return 'msg6:好友已注册'; //好友已注册
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
			$this->_username = $customer->mobile;
			$this->customer_code=$customer->customer_code;
		}
	}
}