<?php
class NianHuoController extends CController{
	
	//设置中奖概率
	private $_rate = array(2.3, 2.5, 88,7.2);
	//优惠券领取的开始和结束时间
	private $_startDay='2016-01-22';
	private $_endDay='2016-01-31';
	private $prize_arr=array(1=>'亨特庄园梅洛红葡萄酒一瓶',2=>'施洛维赤霞珠一瓶',4=>'谢谢参与');
	private $prize_name = array (
			'3_45' => 1,
			'3_32' => 2,
			'3_15' => 3,
			'3_10' => 4,
			'5_200' => 5,
			'5_100' => 6,
			'5_80' => 7,
			'5_50' => 8,
			'5_20'=>9,
			'1'=>10,
			'2'=>11,
			'4'=>12
	); 
	private $_mobile;
	private $_userId;
	private $_day;
	
	public function init(){
		if (time()<strtotime($this->_startDay)||strtotime($this->_endDay)<time()){
			exit("活动还没开始或已结束！");
		}
		$this->_day=date("Ymd");  //获取每天时间
		$this->checkLogin();
	}
	/**
	 * 年货首页
	 */
	public function actionIndex() {
		$click_total=0;
		$chance=1;
		$userType=0;
		//判断是否为财富人生用户
		$caifurensheng=PropertyActivity::model()->find("customer_id=:customer_id and (status=1 or status=99)",array(':customer_id'=>$this->_userId));
		if (!empty($caifurensheng)){
			$userType=1;
			$chance=2;
		}else{
			$caifurensheng=AppreciationPlan::model()->find("customer_id=:customer_id and (status=1 or status=99)",array(':customer_id'=>$this->_userId));
			if (!empty($caifurensheng)){
				$userType=1;
				$chance=2;
			}
		}
		$share=NianhuoShare::model()->find("mobile=:mobile and day=:day",array(':mobile'=>$this->_mobile,':day'=>$this->_day));
		if (!empty($share)&&$share->is_used==0){ //分享获得一次机会
			$chance+=1;
		}
		$nh_member=NianhuoLotteryMember::model()->findAll("type=1 and day=:day and mobile=:mobile",array(':day'=>$this->_day,':mobile'=>$this->_mobile));
		if (empty($nh_member)){
			$click_total=$chance;
		}else {
			$click_total=$chance-count($nh_member);
		}
		$you_hui_quan='http://'.HOME_DOMAIN.$this->createUrl('youHuiQuan/index',array('userid'=>$this->_userId));
		//中奖名单
		$lotteryMem = NianhuoLotteryMember::model()->findAll(array(
		  'order' => 'id DESC',
		  'condition' => 'prize_id !=4',  //剔除‘谢谢参与’
		));
		$this->renderPartial ( "index", array (
				'daytuan_url' => $this->get_url ( 'daytuan' ),
				'jd_url' => $this->get_url ( 'jd' ),
				'huanqiu_url' => $this->get_url ( 'anshi' ),
				'c_total' => $click_total, 
				'userType'=>$userType,
				'validate'=>md5($this->_userId.$this->_day),
				'lottery_mem'=>$lotteryMem,
				'you_hui_quan'=>$you_hui_quan
		) );
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
	 * 抽奖流程
	 */
	public function actionLottery() {
		if (!isset($_POST['u_type'])){
			echo json_encode(array('status'=>0,'msg'=>'非法操作！'));
			exit();
		}
		$userType=intval($_POST['u_type']);  //用户类型
		//$userType=intval($_GET['u_type']);
		$flag=false;
		$is_share=false;
		$is_yhq=false;
		$nh_member=NianhuoLotteryMember::model()->findAll("type=1 and day=:day and mobile=:mobile",array(':day'=>$this->_day,':mobile'=>$this->_mobile));
		if ($userType==1){
			$total=2;		
		}else {
			$total=1;
		}
		$share=NianhuoShare::model()->find("mobile=:mobile and day=:day",array(':mobile'=>$this->_mobile,':day'=>$this->_day));
		if (!empty($share)&&$share->is_used==0){
			$total+=1;
			$is_share=true;
		}
		
		if (empty($nh_member)||count($nh_member)<$total){ 
			$flag=true;
		}
		if (!$flag){ //抽奖
			echo json_encode(array('status'=>0,'msg'=>'不要太贪心哦，明天再来！'));
			exit();
		}else {
			$seed = mt_rand ( 0, 10000 );
			$id = $this->judge ( $seed ); // 获取中奖项
			if ($id == 3) { // 如果是优惠券，从所有优惠券里随机取一张
				$youhuiquan = YouHuiQuan::model ()->findAll ("get_way=2 and get_start_time=:startDay and get_end_time=:endDay and (shop_id=3 or shop_id=5)",array(':startDay'=>$this->_startDay,':endDay'=>$this->_endDay));
				if (! empty ( $youhuiquan )) {
					$yhq_key = array_rand ( $youhuiquan );
					$yhq_lottery=$youhuiquan[$yhq_key];
					$id='3_'.$yhq_lottery->id;
					$yhq_mem=NianhuoLotteryMember::model()->findAll("type=1 and prize_id=:pid",array(':pid'=>$id));
					if (!empty($yhq_mem)&&count($yhq_mem)>=$yhq_lottery->total){  //判断是否超过优惠券总数
						$id=4;
						$prize_name=$this->prize_arr[$id];
						$r_id=$this->prize_name[$id];
					}else{
						$prize_name=$yhq_lottery->name;
						$is_yhq=true;
						$r_id=$this->prize_name[$yhq_lottery->shop_id.'_'.round($yhq_lottery->amout)];
					}
				}else {
					$id=4;
					$prize_name=$this->prize_arr[$id];
					$r_id=$this->prize_name[$id];
				}
			}else{
				//判断一二等奖是否已达到限制数
				if ($id==1||$id==2){
					$lotter_mem=NianhuoLotteryMember::model()->findAll("type=1 and prize_id=:pid",array(':pid'=>$id));
					if (!empty($lotter_mem)){  
						if (count($lotter_mem)>=12){  //判断是否达到12瓶
							$id=4;
						}else {
							foreach ($lotter_mem as $val){
								if ($val->mobile==$this->_mobile||$val->day==date("Ymd")){  //判断是否已中过一二等奖
									$id=4;
									break;
								}
							}
						}
					}

				}
				$prize_name=$this->prize_arr[$id];
				$r_id=$this->prize_name[$id];
			}
			if (!empty($id)){
				$lm_r=false;
				$user_r=false;
				$share_r=false;
				$transaction = Yii::app()->db->beginTransaction();
				$lm_model=new NianhuoLotteryMember();
				try {
					$lm_model->mobile=$this->_mobile;
					$lm_model->prize_id=$id;
					$lm_model->prize_name=$prize_name;
					$lm_model->type=1;
					$lm_model->day=date("Ymd");
					$lm_model->create_time=time();
					$lm_result=$lm_model->save();
					if (empty($lm_result)){
						$lm_r=true;
					}
					if ($is_yhq){
						//用户优惠券记录表
						$uc_model=new UserCoupons();
						$uc_model->mobile=$this->_mobile;
						$uc_model->you_hui_quan_id=$yhq_lottery->id;
						$uc_model->create_time=time();
						$user_reseult=$uc_model->save();
						if (empty($user_reseult)){
							$user_r=true;
						}
					}
					if ($is_share){ //更改分享状态
						$share_result=NianhuoShare::model()->updateByPk($share->id,array('is_used'=>1,'update_time'=>time()));
						if (empty($share_result)){
							$share_r=true;
						}
					}
					if (!empty($lm_r)||!empty($user_r)||!empty($share_r)){
						$transaction->rollback();
						echo json_encode(array('status'=>0,'msg'=>'操作失败！'));
					}else {
						$transaction->commit();
						echo json_encode(array('status'=>1,'r_id'=>$r_id,'dt'=>date("Y.m.d"),'m'=>$this->_mobile,'pn'=>"{$prize_name}"));
					}
				}catch (Exception $e){
					$transaction->rollBack();
					echo json_encode(array('status'=>0,'msg'=>'操作失败！'));
				}
			}
		}
	}
	
	/**
	 * 分享记录
	 */
	public function actionShare() {
		if (!empty($_POST['validate'])&&$_POST['validate']==md5($this->_userId.$this->_day)){
			$share=NianhuoShare::model()->find("mobile=:mobile and day=:day",array(':mobile'=>$this->_mobile,':day'=>$this->_day));
			if (!empty($share)){
				echo json_encode(array('status'=>0,'msg'=>'已获得一次机会!'));
				exit();
			}
			$share_model=new NianhuoShare();
			$share_model->mobile=$this->_mobile;
			$share_model->is_used=0;
			$share_model->day=date("Ymd");
			$share_model->create_time=time();
			$share_model->update_time=time();
			if ($share_model->save()){
				echo json_encode(array('status'=>1,'msg'=>'写入成功！'));
			}else {
				echo json_encode(array('status'=>0,'msg'=>'操作失败'));
			}
		}
	}
	
	/**
	 * 按所设置的概率，判断一个传入的随机值是否中奖
	 * @param int,$seed 10000以内的随机数
	 * @return int,$i 按传入的概率排序，返回中奖的项数
	 */
	private function judge($seed) {
		foreach ($this->_rate as $key => $value) {
			$tmpArr[$key + 1] = $value * 100;
		}
		$tmpArr[0] = 0;
		foreach ($tmpArr as $key => $value) {
			if ($key > 0) {
				$tmpArr[$key] += $tmpArr[$key - 1];
			}
		}
		for ($i = 1; $i < count($tmpArr); $i++) {
			if ($tmpArr[$i - 1] < $seed && $seed <= $tmpArr[$i]) {
				return $i; //返回中奖的项数（按概率的设置顺序）
			}
		}
	}
	
	/**
	 * 获取链接
	 * @param unknown $type
	 * @return string
	 */
	private function get_url($type){
		if (empty($type)){
			return '';
		}
		$mModel=new SetableSmallLoans();
		$info=$mModel->searchByIdAndType($type,1,$this->_userId,false);
		if ($info && $info->completeURL){
			return $info->completeURL;
		}else {
			return $this->createUrl('index');
		}
	}
}