<?php
/**
 * 愚人节活动
 * @author gongzhiling
 * @date 2016-03-24 14:48
 */
class AprilFoolController extends CController {
	private $_startDay='2016-03-31 10:00:00';
	private $_endDay='2016-04-06 23:59:59';
	private $_userId;
	private $_mobile;
	private $nickname;
	
	public function init(){
		//活动日期
		if (time()<strtotime($this->_startDay)||time()>strtotime($this->_endDay)){
			exit('<h1>活动还没开始或已结束</h1>');
		}
		$share=Yii::app()->request->getParam('s');
		if (empty($share)||$share!=1){
			$this->checkLogin();
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
			if (isset($_REQUEST['cust_id'])){
				$sign=FormatParam::formatGetParams($_REQUEST['sign']);
				$customer_id = intval($_REQUEST['cust_id']);
				$custId=($customer_id-1778)/778;
				$_SESSION['cust_id'] = $customer_id.'_'.$sign;
				
			} else if (isset($_SESSION['cust_id'])&&!empty($_SESSION['cust_id'])) {  //没有参数，从session中判断
				$customer_id = $_SESSION['cust_id'];
				$param=explode("_", $customer_id);
				$custId=($param[0]-1778)/778;
				$sign=$param[1];
			}
			if (empty($custId)||empty($sign)){
				exit('<h1>用户信息错误，请重新登录</h1>');
			}
			$customer = Customer::model()->find("id=:id and state = 0", array('id' => $custId));
			if (empty($customer)) {
				exit('<h1>用户信息错误，请重新登录</h1>');
			}
			$check_sign = md5('customer_id='.$custId.'||mobile='.$customer->mobile.'||time='.$customer->create_time);
			if ($check_sign!=$sign){
				exit('<h1>用户信息错误，请重新登录</h1>');
			}
			$this->_userId = $custId;
			$this->_mobile = $customer->mobile;
			$this->nickname=$customer->nickname;
		}
	}
	/**
	 * 首页
	 */
	public function actionIndex(){
		AprilFool::model()->addLog($this->_userId,1);
		$leaves=$this->getLeaves();
		if (!empty($leaves)&&!empty($leaves['is_self'])){
			$this->redirect(array('success'));
		}else{
			$this->renderPartial('index',array('leaves'=>$leaves['data']));
		}
	}
	
	/**
	 * 留言
	 */
	public function actionLeave(){
		$leaves=$this->getLeaves();
		$this->renderPartial('leave',array('leaves'=>$leaves['data']));
	}
	
	/**
	 * 添加留言
	 */
	public function actionAddLeave(){
		if (!isset($_POST['content'])||empty($_POST['content'])){
			echo json_encode(array('status'=>0,'msg'=>'请输入留言！'));
			exit();
		}
		if (isset($_SESSION['form_token'])&&(time()-$_SESSION['form_token']<=180)){
			echo json_encode(array('status'=>2,'msg'=>'留言过于频繁，请稍后再来！'));
			exit();
		}

		$content=FormatParam::formatGetParams($_POST['content']);
		if (empty($content)||$content == ''){
			echo json_encode(array('status'=>0,'msg'=>'请输入留言！'));
			exit();
		}
		$leave=new AprilFoolLeave();
		$leave->customer_id=$this->_userId;
		$leave->mobile=0;
		$leave->content=$content;
		$leave->create_time=time();
		if ($leave->save()){
			echo json_encode(array('status'=>1,'msg'=>'留言成功！'));
		}else {
			echo json_encode(array('status'=>0,'msg'=>'留言失败！'));
		}
		$_SESSION['form_token']=time();
	}
	
	/**
	 * 留言成功页
	 */
	public function actionSuccess(){
		$leaves=$this->getLeaves();
		$num=AprilFool::model()->getChances($this->_userId,$this->_mobile);
		$idstr='';
		if (!empty($leaves['data'])){
			if (!empty($leaves['data'][0])){
				$ids[]=$leaves['data'][0]->id;
			}
			if (!empty($leaves['data'][1])){
				$ids[]=$leaves['data'][1]->id;
			}
			if (!empty($leaves['data'][2])){
				$ids[]=$leaves['data'][2]->id;
			}
			if (!empty($leaves['data'][3])){
				$ids[]=$leaves['data'][3]->id;
			}
			if (!empty($leaves['data'][4])){
				$ids[]=$leaves['data'][4]->id;
			}
			$idstr=implode("_", $ids);
			$idstr=urlencode($idstr);
		}
		$this->renderPartial('success',array('leaves'=>$leaves['data'],'num'=>$num,'ids'=>$idstr,'nickname'=>$this->nickname));
	}
	
	/**
	 * 分享页--首页
	 */
	public function actionShareIndex(){
		if (!isset($_GET['s'])||empty($_GET['p'])){
			exit("访问出错！");
		}
		AprilFool::model()->addLog(0,3);
		$param=trim($_GET['p'],'_');
		$ids_arr=explode("_",$param);
		//通过id获取留言数据
		foreach ($ids_arr as $id){
			if (empty($id)){
				continue;
			}
			$leaves[]=AprilFoolLeave::model()->findByPk(intval($id));
		}
		$this->renderPartial('shareIndex',array('leaves'=>$leaves));
	}
	
	/**
	 * 分享页--留言成功后
	 */
	public function actionShare(){
		if (isset($_SERVER['HTTP_REFERER'])&&!empty($_SERVER['HTTP_REFERER'])){
			if (strpos($_SERVER['HTTP_REFERER'], "ShareIndex")===false){
				$_GET['n']=0;
			}
		}
		$data = AprilFoolLeave::model ()->findAll ( array (
				'order' => 'create_time desc',
				'limit' => 5
		) );
		$this->renderPartial('share',array('leaves'=>$data));
	}
	
	/**
	 * 分享留言
	 */
	public function actionShareLeave(){
		if (!isset($_POST['mobile'])||empty($_POST['mobile'])||!isset($_POST['content'])||empty($_POST['content'])){
			echo json_encode(array('status'=>0,'msg'=>'请输入手机号码！'));
			exit();
		}
		$num=0;
		$is_new=0;
		$content=FormatParam::formatGetParams($_POST['content']);
		if (empty($content)||$content == ''){
			echo json_encode(array('status'=>0,'msg'=>'请输入留言！'));
			exit();
		}
		$mobile=FormatParam::formatGetParams($_POST['mobile']);
		$chance=AprilFoolChance::model()->find("mobile=:mobile and type=1",array(':mobile'=>$mobile));
		if (empty($chance)){
			//判断是否为新用户
			$customer=Customer::model()->find("mobile=:mobile and state = 0",array(':mobile'=>$mobile));
			if (empty($customer)){
				$is_new=1;
			}
		}
		if (isset($_SESSION['form_token'])&&(time()-$_SESSION['form_token']<=180)){
			echo json_encode(array('status'=>2,'msg'=>'留言过于频繁，请稍后再来！'));
			exit();
		}
		$leave=new AprilFoolLeave();
		$leave->customer_id=0;
		$leave->mobile=$mobile;
		$leave->content=$content;
		$leave->is_new=$is_new;
		$leave->create_time=time();
		if ($leave->save()){
			if ($is_new>0){
				$leave=new AprilFoolChance();
				$leave->customer_id=0;
				$leave->mobile=$mobile;
				$leave->times=1;
				$leave->type=1;
				$leave->way=3;
				$leave->create_time=time();
				if ($leave->save()){
					$num=1;
				}
			}
			echo json_encode(array('status'=>1,'msg'=>'留言成功！','num'=>$num));
		}else {
			echo json_encode(array('status'=>0,'msg'=>'留言失败！'));
		}

		$_SESSION['form_token']=time();
	}
	
	/**
	 * 分享获得抽奖机会
	 */
	public function actionLog(){
		if (!isset($_POST['type'])||empty($_POST['type'])){
			echo json_encode(array('status'=>0,'msg'=>'非法操作！'));
			exit();
		}
		$type=intval($_POST['type']);
		AprilFool::model()->addLog($this->_userId,$type);
		if ($type==2||$type==5){
			$chance=AprilFool::model()->addChance($this->_userId,1,1);
			if ($chance){
				$num=AprilFool::model()->getChances($this->_userId,$this->_mobile);
				echo json_encode(array('status'=>1,'num'=>$num));
			}
		}
	}
	/**
	 * 活动规则
	 */
	public function actionRule(){
		$this->renderPartial('rule');
	}
	
	/**
	 * 留言列表--每页8条数据
	 */
	public function actionList(){
		$list=AprilFoolLeave::model()->findAll(array('order' => 'create_time desc','limit'=>8));
		$this->renderPartial('list',array('list'=>$list,'page'=>1));
	}
	
	/**
	 * 更多留言分页
	 */
	public function actionPageList(){
		if (!isset($_POST['page'])||empty($_POST['page'])){
			echo json_encode(array('status'=>0,'msg'=>'请求出错！'));
			exit();
		}
		$currentPage=intval($_POST['page']);//当前页
		$data=AprilFool::model()->getPageList($currentPage);
		echo json_encode(array('status'=>1,'list'=>$data['data'],'page'=>$data['nextPage']));
	}
	/**
	 * 获取最新的五条留言
	 * @return unknown
	 */
	private function getLeaves(){
		$leaves=AprilFool::model()->newestLeaves($this->_userId);
		return $leaves;
	}
	
	/**
	 * 抽奖页
	 */
	public function actionLottery(){
		$num=AprilFool::model()->getChances($this->_userId,$this->_mobile);
		//$shareChance=AprilFool::model()->shareChance($this->_userId);
		$prize=AprilFool::model()->getPrizeList($this->_userId);
		$this->renderPartial('lottery',array('num'=>$num,'prize'=>json_encode($prize)));
	}
	
	/**
	 * 抽奖
	 */
	public function actionDraw(){
		if (!isset($_POST['mobile'])){
			echo json_encode(array('status'=>0,'msg'=>'非法操作！'));
			exit();
		}
		$num=AprilFool::model()->getChances($this->_userId,$this->_mobile);
		if ($num<=0){
			echo json_encode(array('status'=>0,'msg'=>'没有抽奖机会！'));
			exit();
		}
		$result=AprilFool::model()->lottery($this->_userId,$this->_mobile);
		if (!empty($result)){
			echo json_encode(array('status'=>1,'param'=>$result,'num'=>$num-1));
		}else {
			echo json_encode(array('status'=>0,'msg'=>'网络错误！'));
		}
	}
	
	/**
	 * 填写手机号后的操作
	 */
	public function actionComplete(){
		if (!isset($_POST['mobile'])||empty($_POST['mobile'])||!isset($_POST['param'])||empty($_POST['param'])){
			echo json_encode(array('status'=>0,'msg'=>'非法操作！'));
			exit();
		}
		$mobile=FormatParam::formatGetParams($_POST['mobile']);
		$id=intval($_POST['param']);
		$result=AprilFool::model()->updatePrize($id,$mobile);
		if ($result){
			echo json_encode(array('status'=>1,'msg'=>'领取成功！'));
		}else {
			echo json_encode(array('status'=>0,'msg'=>'网络错误！'));
		}
	}
	
	/**
	 * 继续留言
	 */
	public function actionNextLeave(){
		$leaves=$this->getLeaves();
		$num=AprilFool::model()->getChances($this->_userId,$this->_mobile);
		$this->renderPartial('next_leave',array('leaves'=>$leaves['data'],'num'=>$num));
	}
}
