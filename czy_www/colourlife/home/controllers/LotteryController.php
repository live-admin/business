<?php
/**
 * 2016年会抽奖控制器
 * @author gongzhiling
 * Date: 2015/12/17
 * Time:9:43
 */

class LotteryController extends CController{
	private $_userId=0;
	private $_account;
	private $start='2016-1-31 00:00:00';
	private $end='2016-1-31 23:59:59';
	public function init(){
		/* if (time()<$start||time()>$end){
			exit("活动还没开始或已结束！");
		} */ 
		$this->checkLogin();
	}
	
	/**
	 * 首页  已经参与抽奖的去到查看中奖结果，没有参与抽奖的去到名单录入页
	 */
	public function actionIndex(){
		//判断该账号是否已参与活动
		$lottery=LotteryMember::model()->find("customer_id=:customer_id",array(":customer_id"=>$this->_userId));
		if (!empty($lottery)){
			$this->redirect(array('result','name'=>urlencode($lottery->uname))); //去到结果页
		}else{
			$this->renderPartial("index"); //去到首页
		}
	}
	
	/**
	 * 中奖结果页
	 */
	public function actionResult(){
		$model=WinningLog::model()->findAll();
		if (empty($model)){
			$this->renderPartial("start");
		}else{
			$username=urldecode($_GET['name']);
			$prize=PrizeList::model()->findAll(array('order'=>'sort asc'));
			$this->renderPartial("result",array('prize'=>$prize,'username'=>$username));
		}
	}
	/**
	 * 验证登录
	 */
	private function checkLogin(){
		//csh判断是否来自彩之云客户端
		if (empty($_REQUEST['csh_lottery']) && empty($_SESSION['csh_lottery'])){
			$this->redirect('http://mapp.colourlife.com/m.html');
			exit();
		}
		if (isset($_REQUEST['csh_lottery'])) {  //优先有参数的
			$csh_lottery=$_REQUEST['csh_lottery'];
			$_SESSION['csh_lottery'] = $csh_lottery;
		} else if (isset($_SESSION['csh_lottery'])) {  //没有参数，从session中判断
			$csh_lottery=$_SESSION['csh_lottery'];
		}
		$custIDArr=explode("_",$csh_lottery);
		if ($custIDArr[1]!=md5(date("Ymd").$custIDArr[0])){
			$this->redirect('http://mapp.colourlife.com/m.html');
			exit();
		}
		$cust_id = ($custIDArr[0]+1778)/178;
		$cust_id=intval($cust_id);
		$customer = Customer::model()->find("id=:id and state = 0", array('id' => $cust_id));
		if (empty($customer)) {
			$this->redirect('http://mapp.colourlife.com/m.html');
			exit();
		}
		$this->_account=$customer->mobile;
		$this->_userId = $cust_id;
	}
	
	/**
	 * 录入抽奖名单
	 */
	public function actionEntry(){
		$data=array();
		if (empty($_POST['name'])||empty($_POST['type'])||empty($_POST['mobile'])){
			$data['status']=0;
			$data['msg']='信息不完整！';
			echo json_encode($data);
			exit();
		}
		$type=intval($_POST['type']);
		if (!in_array($type, array(1,2))){
			$data['status']=0;
			$data['msg']='身份错误！';
			echo json_encode($data);
			exit();
		}
		$uname=FormatParam::formatGetParams($_POST['name']);
		$mobile=FormatParam::formatGetParams($_POST['mobile']);
		if ($type==1){  //判断是否员工
			$employee=Employee::model()->find("username=:username",array(':username'=>$uname));
			if (empty($employee)){
				$data['status']=0;
				$data['msg']='oa账号不存在！';
				echo json_encode($data);
				exit();
			}
			$lottery_result=LotteryMember::model()->find("uname=:uname",array(":uname"=>$uname));
			if (!empty($lottery_result)){            
				$data['status']=0;
				$data['msg']='该账号已参与抽奖！';
				echo json_encode($data);
				exit();
			}
			$username=$employee->name;
			$flag=false;
			if (strpos($username, '（')!==false){
				$len=strpos($username, '（')/3;
				$flag=true;
			}elseif (strpos($username, '(')!==false){
				$len=strpos($username, '(')/3;
				$flag=true;
			}elseif (strpos($username, '-')!==false){
				$len=strpos($username, '-')/3;
				$flag=true;
			}
			if ($flag){
				$username=mb_substr($username,0,$len,'utf-8');
			}
		}elseif ($type==2){  //判断是否嘉宾
			$lottery_result=LotteryMember::model()->find("mobile=:mobile",array(":mobile"=>$mobile));
			if (!empty($lottery_result)){
				$data['status']=0;
				$data['msg']='该手机号已参与抽奖！';
				echo json_encode($data);
				exit();
			}
			$username='';
		}
		//判断该账号是否已参与活动
		$lottery=LotteryMember::model()->find("customer_id=:customer_id",array(":customer_id"=>$this->_userId));
		
		if (empty($lottery)){
			$model=new LotteryMember();
			$model->customer_id=$this->_userId;
			$model->uname=$uname;
			$model->mobile=$mobile;
			$model->username=$username;
			$model->type=$type;
			$model->lottery_type=0;
			$model->create_time=time();
			$model->update_time=time();
			if ($model->save()){
				$data['status']=1;
				$data['url']=$this->createUrl('Result',array('name'=>$uname));
				$data['msg']='提交成功！';
			}else {
				$data['status']=0;
				$data['msg']='提交失败！';
			}
		}else {
			$data['status']=0;
			$data['msg']='您已参与活动！';
		}
		echo json_encode($data);
	}
}