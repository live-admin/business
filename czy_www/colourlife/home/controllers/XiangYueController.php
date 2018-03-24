<?php
/*
 * @version 五月特惠活动
 */
class XiangYueController extends CController{
    private $_startDay='2016-04-28 09:00:00';//活动开始时间
    private $_endDay='2017-05-10 09:00:00';//活动结束时间
    private $_userId;
    public $layout = false;
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
        $shangChengUrl=XiangYue::model()->getAllUrl($this->_userId);
        //京东的最新价格
        $jdPrice=XiangYue::model()->getJdPrice();
        $this->render('/v2016/travelSeason/index', array(
            'shangChengUrl'=>$shangChengUrl,
            'jdPrice'=>$jdPrice,
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
			$this->_userId = $custId;
		}
	}
}
