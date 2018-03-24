<?php

class NewThanksgivingController extends CController
{
	private $_luckyTodayCan = 0;  //0可点，1为不可点
	private $_username = "";
	private $_userId = 0;
	private $_thanksActId=14;
	private $_prize_name='';
	private $_isAnnounce=false;
	
	//是否结束
	private function  is_over()
	{
		$luckyAct = LuckyActivity::model()->findByPk($this->_thanksActId);
		if ($luckyAct && ($luckyAct->end_date . " 23:59:59" < date("Y-m-d H:i:s"))) {
			return true;//已结束
		}
		return false;//未结束
	}
	
	
	//是否开始
	private function  is_start()
	{
		$luckyAct = LuckyActivity::model()->findByPk($this->_thanksActId);
		if ($luckyAct && ($luckyAct->start_date . " 00:00:00" <= date("Y-m-d H:i:s"))) {
			return true;//已开始
		}
		return false;//未开始
	}
	
	/**
	 * 点赞首页
	 */
	public function actionIndex(){
		$this->checkLogin();
		//总赞数据
		$pdata=PraiseCount::model ()->findByPk(1);
		if (!empty($pdata)&&$pdata->total>0){
			$total=$pdata->total;
		}else{
			$total=0;
		}
		$this->renderPartial("praise", array(
					"luckyTodayCan" => $this->_luckyTodayCan,
				    "total"=>$total
			));
	}
	
	/**
	 * 读取点赞记录表
	 */
	public function actionResult(){
		$this->checkLogin();
		$thanks=ThanksGivingResult::model()->findAll("customer_id=" . $this->_userId);
		$this->renderPartial("result", array(
				"thanks" => $thanks
		));
	}
	
	/**
	 * 点赞操作路程
	 */
	public function actionPraiseCreate(){
		$this->checkLogin();
		$status=0;
		if ($this->is_over() || !$this->is_start()) {
			echo json_encode(array('status'=>$status,'data'=>'activity error!'));
			exit();
		}
		$act = LuckyActivity::model ()->findByPk ( $this->_thanksActId);
		if (empty($act)||$act ['disable'] == 1 || $act ['isdelete'] == 1 ){
			echo json_encode(array('status'=>$status,'data'=>'活动不存在!'));
			exit();
		}
		if (isset($_POST['actid'])&&is_numeric($_POST['actid'])){
			$actid=intval($_POST['actid']);
		}else {
			$actid=13;
		}
		if (isset($_POST['prize_level'])&&is_numeric($_POST['prize_level'])){
			$prize_level=intval($_POST['prize_level']);
		}else {
			$prize_level=2;
		}
		$add_result=$this->addInfo($actid, $prize_level);
		
		if ($add_result==1){
			$str='奖项已禁用或已删除';
		}elseif ($add_result==2){
			$str=$this->_prize_name.'奖券已领完';
		}elseif ($add_result==3){
			$str='今天已点赞';
		}else {
			if (!empty($add_result)){
				$status=1;
				$str=$add_result;
			}else {
				$str='网络出错';
			}
		}
		echo json_encode(array('status'=>$status,'data'=>$str));
	}
	
	
	/**
	 * 数据入库
	 * @param unknown $prize_id
	 * @param unknown $prize_entity_id
	 * @return boolean
	 */
	private function addInfo($actid=13,$prize_level=2){
		$result = array ();
		$transaction = Yii::app ()->db->beginTransaction ();
		try {
			$thanksgiving_data=ThanksGivingResult::model()->find("customer_id=:cust_id and day =:day", array(':cust_id' => $this->_userId,':day'=>date("Ymd")));
			if (!empty($thanksgiving_data)){
				return 3;  //今天已点赞
			}
			//获取所有符合要求的奖项
			$prizeList=LuckyPrize::model()->findAll("disable=0 and isdelete=0 and prize_count_now>0 and type=0 and lucky_act_id=:lucky_act_id and prize_level=:prize_level",array(':lucky_act_id'=>$actid,':prize_level'=>$prize_level));
			if (empty($prizeList)){
				return 1;  //奖项已禁用或已删除
			}
			$p_id=array_rand($prizeList,1);
			$prizeArr=$prizeList[$p_id];
			//$prizeArr->id=153;
			//获取所有符合要求的奖券
			$entityList=LuckyEntity::model()->find('prize_id=:prize_id and is_use=0',array(':prize_id'=>$prizeArr->id));
			if (empty($entityList)){
				$this->_prize_name=$prizeArr->prize_name;
				return 2; //奖券已领完
			}
			//入库
			$thanksgiving=new ThanksGivingResult;
			$thanksgiving->customer_id=$this->_userId;
			$thanksgiving->username=$this->_username;
			$thanksgiving->prize_id=$prizeArr->id;
			$thanksgiving->prize_name=$prizeArr->prize_name;
			$thanksgiving->lucky_entity_id=$entityList->id;
			$thanksgiving->code=$entityList->code;
			$thanksgiving->prize_act_id=14;
			$thanksgiving->day=date("Ymd");
			$thanksgiving->addtime=time();
			$exeRet=$thanksgiving->save();
			//添加中奖记录
			if (empty($exeRet)){
				$transaction->rollback ();
				return false;
			}
			
			//优惠券张数减一
			$exeRet=LuckyPrize::model ()->updateAll ( array (
					"prize_count_now" => new CDbExpression ( "prize_count_now-1" )
			), "id=$prizeArr->id" );
			if (empty($exeRet)){
				$transaction->rollback();
				return false;
			}
			//优惠码
			
			$entityList->is_use = 1;
			$entityList->customer_id = $this->_userId;
			$entityList->update_time=time();
			if (! $entityList->update ()) {
				$transaction->rollback ();
				return false;
			}
			
			//入总赞表
			$pdata=PraiseCount::model ()->findByPk(1);
			if (empty($pdata)){
				$praise=new PraiseCount();
				$praise->total=1;
				$praise->addtime=time();
				$praise->updatetime=time();
				$pexeRet=$praise->save();
				$total=1;
			}else {
				$pexeRet=PraiseCount::model ()->updateAll ( array (
						"total" => new CDbExpression ( "total+1" )
				), "id=1" );
				$total=$pdata->total+1;
			}
			if (empty($pexeRet)){
				$transaction->rollback();
				return false;
			}
			$result ['prize_name']= $prizeArr->prize_name;
			$result ['code']= $entityList->code;
			$result ['luckyTodayCan'] = 1;
			$result ['total'] = $total;
			$transaction->commit ();
			return $result;
			
		} catch ( Exception $e ) {
			$transaction->rollback ();
			return false;
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
			$this->_username = $customer->username;
			//点赞才使用
			if (empty($this->_isAnnounce)){
				$result=ThanksGivingResult::model()->find("customer_id=:cust_id and day =:day", array(':cust_id' => $custId,':day'=>date("Ymd")));
				if ($result) {
					$this->_luckyTodayCan = 1;
				}				
			}
		}
	}
	
	/**
	 * 感恩揭晓活动
	 */
	public function actionAnnounce(){
		$this->_isAnnounce=true;
		$this->checkLogin();
		/* $re=new SetableSmallLoans();
		$zhuangxiu = $re->searchByIdAndType('EZHUANGXIU',1,$this->_userId,false); */
		$this->renderPartial("announce/announce");
	}
	
	public function actionEnd(){
		$this->_isAnnounce=true;
		$this->checkLogin();
		$type='';
		//判断去到哪个页面
		if (isset($_GET['type'])&&!empty($_GET['type'])){
			$type=$_GET['type'];
			if ($type=='g_1'||$type=='g_2'){  //1为12号商品，2为19号商品
				$type_arr=explode("_", $type);
				$type=$type_arr[0];
				$date=$type_arr[1];
			}
		}
		switch ($type){
			case 'd': //选择日期
				$this->renderPartial("announce/date");
				break;
			case 'g': //商品页
				
				//商品
/* 				$goods=array(
					'1'=>array(21174,21175,21176),
					'2'=>array(21177,21178,21179)
				); */
				$goods=array(
						'1'=>array(1450,1449,1448),
						'2'=>array(1452,1451,1447)
				);
				$SetableSmallLoansModel = new SetableSmallLoans();
				$href = $SetableSmallLoansModel->searchByIdAndType('daytuan', '', $this->_userId,false);
				$this->renderPartial("announce/ganenGoods",array('ids'=>$goods[$date],'url'=>$href->completeURL));
				break;
			default:
				$this->renderPartial("announce/end");
				break;
		}
	}
}
