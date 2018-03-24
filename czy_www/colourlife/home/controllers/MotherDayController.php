<?php
/*
 * @version 五月特惠活动
 */
class MotherDayController extends CController{
    //private $_startDay='2016-05-06 09:00:00';//活动开始时间
    //private $_endDay='2017-12-31 09:00:00';//活动结束时间
    private $mobile;
    private $userId;
    public $layout = false;
    public $isDelay=false;
    public function init(){
        /* if (time()<strtotime($this->_startDay)||time()>strtotime($this->_endDay)){
			exit('<h1>活动还没开始或已结束</h1>');
		} */
        $this->checkLogin();
        $day = date("Ymd");
        $startDay = 20170106;
        if (isset($_GET['cust_id'])&&($_GET['cust_id']==1745829||$_GET['cust_id']==1734816||$_GET['cust_id']==1254226||$_GET['cust_id']==2208133)){
        	$startDay = 20170105;
        }
        if ($day >=$startDay && $day <20170120){
        	$this->isDelay=true;
        }
    }
    /*
     * @version 首页
     */
    public function actionIndex(){
    	//判断是否顺延
    	$isDelay=false;
    	//第几周
    	$num=MotherDay::model()->getWeeks();
    	//自动切换页面 ，奇数为tryst页面，偶数为index页面
    	if ($this->isDelay){
    		$page='spring';
    	}else {
    		if (($num+1) % 2 == 0){
    			//$isDelay=true;
    			$page='index';
    		}else {
    			$page='tryst';
    		}
    	}
    	
        MotherDay::model()->addShareLog($this->userId,1);  //记录进入首页次数
        //对应的四款产品
        $fourProductArr=MotherDay::model()->getDayProduct($isDelay);
        //返回商城的url
        $shangChengUrl= MotherDay::model()->getAllUrl($this->userId);
        //判断是否有邀请注册
        //$isRegister=MotherDay::model()->isRegister($this->userId);
        //去掉邀请注册的条件
        $isRegister=1;
        //京东的最新价格
        if ($this->isDelay){
        	$fourProductArr['currentProductList'] = json_encode($fourProductArr['currentProductList']);
        }
        $this->render('/v2016/motherDay/'.$page, array(
        		'currentProductArr'=>$fourProductArr['currentProductList'],
        		'nextProductArr' =>$fourProductArr['nextProductList'],
        		'shangChengUrl'=>$shangChengUrl,
        		'next_date'=>$fourProductArr['next_date'],
        		'registerNum'=>$isRegister
        ));
    }
    /*
     * @version 点击A产品
     */
    public function actionClickLog(){
    	if (isset($_POST['type'])&&!empty($_POST['type'])){
    		switch ($_POST['type']){
    			case 'a':
    				$type=2;
    				break;
    			case 'b':
    				$type=3;
    				break;
    			case 'c':
    				$type=4;
    				break;
    			case 'd':
    				$type=5;
    				break;
    		}
    		if (!empty($type)){
    			MotherDay::model()->addShareLog($this->userId,$type);  //记录进入首页次数
    		}
    	}
        echo json_encode(array('status'=>1));
    }
    /*
     * @version 点击B产品
     */
    public function actionClickB(){
        MotherDay::model()->addShareLog($this->userId,3);  //记录进入首页次数
         echo json_encode(array('status'=>1));
    }
    public function actionDuanWu(){
        MotherDay::model()->addShareLog($this->userId,55);//端午节首页
        $sixProductArr=MotherDay::model()->getSixProduct();
        $shangChengUrl= MotherDay::model()->getAllUrl($this->userId);
        $this->render('/v2016/motherDay/dragonboat', array(
        		'currentProductArr'=>$sixProductArr,
        		'shangChengUrl'=>$shangChengUrl,
        ));
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
			$this->userId = $custId;
		}
	}
}
