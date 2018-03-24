<?php
/*
 * @version 五月特惠活动
 */
class MayPreferentialController extends CController{
    private $_startDay='2016-04-28 09:00:00';//活动开始时间
    private $_endDay='2016-05-10 09:00:00';//活动结束时间
    private $mobile;
    private $userId;
    public $layout = false;
    private $sign;
    public function init(){
        if (time()<strtotime($this->_startDay)||time()>strtotime($this->_endDay)){
			exit('<h1>活动还没开始或已结束</h1>');
		}
        $this->checkLogin();
    }
    /*
     * @version 首页
     */
    public function actionIndex(){
        MayPreferential::model()->addShareLog($this->userId,1);  //记录进入首页次数
        //对应的四款产品
        $fourProductArr=MayPreferential::model()->getDayProduct();
        //获取邀请注册的人数    
        $registerNum=MayPreferential::model()->getRegisterNum($this->userId);
        //是否是彩富人生
        $isCaiFu=MayPreferential::model()->getCaiFu($this->userId);
        //返回商城的url
        $shangChengUrl=MayPreferential::model()->getAllUrl($this->userId);
        //京东的最新价格
        $jdPrice=MayPreferential::model()->getJdPrice();
        $this->render('/v2016/MayThePreferential/index', array(
            'fourProductArr'=>$fourProductArr,
            'registerNum'=>$registerNum,
            'isCaiFu'=>$isCaiFu,
            'shangChengUrl'=>$shangChengUrl,
            'jdPrice'=>$jdPrice,
        ));
    }
    /**
	 * 验证登录
	 */
	private function checkLogin(){
		if ((empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])) || (empty($_REQUEST['sign']) && empty($_SESSION['sign']))){
                exit('<h1>用户信息错误，请重新登录</h1>');
        }else{
            $custId = 0;
            if (isset($_REQUEST['cust_id']) && isset($_REQUEST['sign'])) {  //优先有参数的
                $sign=FormatParam::formatGetParams($_REQUEST['sign']);
                $customer_id = intval($_REQUEST['cust_id']);
                $custId=($customer_id-1778)/778;
                $_SESSION['cust_id'] = $customer_id;
                $_SESSION['sign'] = $sign;
            } else if (isset($_SESSION['cust_id']) && isset($_SESSION['sign'])) {  //没有参数，从session中判断
                $customer_id = $_SESSION['cust_id'];
                $custId=($customer_id-1778)/778;
                $sign=$_SESSION['sign'];
            }
            $custId=intval($custId);
            $customer = Customer::model()->find("id=:id and state = 0", array('id' => $custId));
            if (empty($custId) || empty($customer)) {
                    exit('<h1>用户信息错误，请重新登录</h1>');
            }
            $check_sign = md5('customer_id='.$custId.'||mobile='.$customer->mobile.'||time='.$customer->create_time);
			if ($check_sign!=$sign){
				exit('<h1>用户信息错误，请重新登录</h1>');
			}
            $this->userId = $custId;
            $this->mobile = $customer->mobile;
            $this->sign=$sign;
        }
	}
}
