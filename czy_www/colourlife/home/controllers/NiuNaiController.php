<?php
/*
 * @version 挤牛奶活动
 */
class NiuNaiController extends CController{
    private $_startDay='2016-04-13';//活动开始时间
    private $_endDay='2016-04-27 23:59:59';//活动结束时间
    private $mobile;
    private $userId;
    private $sign;
    public $layout = false;
    public function init(){
        /* if (time()<strtotime($this->_startDay)||time()>strtotime($this->_endDay)){
			exit('<h1>活动还没开始或已结束</h1>');
		} */
        $this->checkLogin();
    }
    /*
     * @version 首页
     */
    public function actionIndex(){
        $result= JiNiuNai::model()->isCanJia($this->mobile);
        if(!$result){
            $res=JiNiuNai::model()->getTong($this->mobile);
            if(!$res){
                exit('<h1>数据有错，请刷新一下页面</h1>');
            }
        }
        JiNiuNai::model()->addShareLog($this->userId,3);  //记录进入首页次数
        $isCaiFuUser=JiNiuNai::model()->isCaiFuUser($this->mobile);//是否是彩富人生用户(是否弹框牛奶值)
        $isNoCaiFuUser=JiNiuNai::model()->isNoCaiFuUser($this->mobile);//是否是彩富人生用户(是否弹框增长值)
        //牛奶值
        $niuNaiValue=JiNiuNai::model()->getValueByMobile($this->mobile);
        //排名
        $mingValue=JiNiuNai::model()->getMingByMobile($this->mobile);
        //返回的奖品弹框
        $prizeArr=JiNiuNai::model()->getPrizeByGrowValue($this->mobile);
        //今天是否自己挤牛奶过
        $isJiNiuNai=JiNiuNai::model()->isJiNiuNai($this->mobile);
        //明天的牛奶值
        $niuNaiTomorrow=JiNiuNai::model()->getGrowValueTomorrow($this->mobile);
        //是否下单
        $orderArr=JiNiuNai::model()->getOrderTan($this->mobile);
        if(!empty($orderArr)){
            $orderCount=count($orderArr);
        }else{
            $orderCount=0;
        }
        //活动结束后的弹框
        $isTan=JiNiuNai::model()->isTan();
        $cust_id=$this->userId*778+1778;
//        dump($isCaiFuUser);
        $this->render('index', array(
            'isCaiFuUser'=>$isCaiFuUser,
            'isNoCaiFuUser'=>$isNoCaiFuUser,
            'mobile'=>$this->mobile,
            'cust_id'=>$cust_id,
            'niuNaiValue'=>$niuNaiValue,
            'paiMing'=>$mingValue['paiming'],
            'prizeName'=>$prizeArr['prize_name'],
            'isJiNiuNai'=>$isJiNiuNai,
            'niuNaiTomorrow'=>$niuNaiTomorrow['value'],
            'orderCount'=>$orderCount,
            'isTan'=>$isTan,
            'result'=>$result,
            'sign'=>$this->sign,
        )); 
    }
    /*
     * @version 领取彩富人生成长值ajax
     */
    public function actionGetCai(){
        $mobile =  Yii::app()->request->getParam('mobile');
        $isCaiFuUser=JiNiuNai::model()->isCaiFuUser($this->mobile);
        if(!$isCaiFuUser){
            return false;
        }
        $result= JiNiuNai::model()->getValueByCaiFu($mobile);
        if($result){
            $niuNaiValue=JiNiuNai::model()->getValueByMobile($this->mobile);
            $mingValue=JiNiuNai::model()->getMingByMobile($this->mobile);
            //是否下单
            $orderArr=JiNiuNai::model()->getOrderTan($this->mobile);
            if(!empty($orderArr)){
                $orderCount=count($orderArr);
                $prizeArr=false;
            }else{
                $orderCount=0;
                $prizeArr=JiNiuNai::model()->getPrizeByGrowValue($this->mobile);
            }
            echo json_encode(array('status'=>1,'niuNaiValue'=>$niuNaiValue,'paiMing'=>$mingValue['paiming'],'orderCount'=>$orderCount,'prizeName'=>$prizeArr['prize_name']));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败!'));
        }
    }
    /*
     * @version 非彩富人生用户ajax
     */
    public function actionGetNoCai(){
        $mobile =  Yii::app()->request->getParam('mobile');
        $result=NiuUserTong::model()->updateAll(array('times' =>new CDbExpression('times+1')),"mobile=".$mobile);
        if($result){
            //是否下单
            $orderArr=JiNiuNai::model()->getOrderTan($this->mobile);
            if(!empty($orderArr)){
                $orderCount=count($orderArr);
                $prizeArr=false;
            }else{
                $orderCount=0;
                $prizeArr=JiNiuNai::model()->getPrizeByGrowValue($this->mobile);
            }
            echo json_encode(array('status'=>1,'orderCount'=>$orderCount,'prizeName'=>$prizeArr['prize_name']));
        }else{
            echo json_encode(array('status'=>0));
        }
    }
    /*
     * @version 环球精选下单ajax
     */
    public function actionGetHuanQiuOrder(){
        $mobile =  Yii::app()->request->getParam('mobile');
        $orderTan=JiNiuNai::model()->getOrderTan($mobile);
        if(empty($orderTan)){
            return false;
        }
        $result= JiNiuNai::model()->getValueByOrder($mobile);
        if($result){
            $niuNaiValue=JiNiuNai::model()->getValueByMobile($this->mobile);
            $mingValue=JiNiuNai::model()->getMingByMobile($this->mobile);
            $prizeArr=JiNiuNai::model()->getPrizeByGrowValue($this->mobile);
            echo json_encode(array('status'=>1,'niuNaiValue'=>$niuNaiValue,'paiMing'=>$mingValue['paiming'],'prizeName'=>$prizeArr['prize_name']));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败!'));
        }
    }
    /*
     * @verson 自己挤牛奶动作ajax
     */
    public function actionMyJi(){
        if(time()>strtotime($this->_endDay)){
            echo json_encode(array('status'=>0,'msg'=>'活动已经结束!'));
    		exit;
        }
        $mobile =  Yii::app()->request->getParam('mobile');
        $result= JiNiuNai::model()->getGrowValueByMyJi($mobile);
        if(!empty($result)){
            $niuNaiValue=JiNiuNai::model()->getValueByMobile($this->mobile);
            $mingValue=JiNiuNai::model()->getMingByMobile($this->mobile);
//            $prizeArr=JiNiuNai::model()->getPrizeByGrowValue($this->mobile);
            $niuNaiTomorrow=JiNiuNai::model()->getGrowValueTomorrow($this->mobile);
            $niuNaiToday=$niuNaiTomorrow['value']-10;
            echo json_encode(array('status'=>1,'value'=>$result['value'],'niuNaiValue'=>$niuNaiValue,'paiMing'=>$mingValue['paiming'],'tomorrowValue'=>$niuNaiTomorrow['value'],'todayValue'=>$niuNaiToday));//返回挤牛奶后的数组
        }else{
            echo json_encode(array('status'=>0));
        }
    }
    /*
     * @version 点击分享后的ajax
     */
    public function actionFenXiang(){
        if(time()>strtotime($this->_endDay)){
            echo json_encode(array('status'=>0,'msg'=>'活动已经结束!'));
    		exit;
        }
    	JiNiuNai::model()->addShareLog($this->userId,1);  //记录分享点击时间
        $mobile =  Yii::app()->request->getParam('mobile');
        $result=JiNiuNai::model()->shareToFriend($mobile);
        if(!empty($result)){
            echo json_encode(array('status'=>1));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'分享失败'));
        }
    }
    /*
     * @version 分享的页面
     */
    public function actionFenWeb(){
        //活动结束后的弹框
        $isTan=JiNiuNai::model()->isTan();
        JiNiuNai::model()->addShareLog(0,2);  //记录分享页
        //牛奶值
        $niuNaiValue=JiNiuNai::model()->getValueByMobile($this->mobile);
        $mingValue=JiNiuNai::model()->getMingByMobile($this->mobile);
        $num=JiNiuNai::model()->getWaterNum($this->mobile,4);
        $validate=date("Ymd").'colourlife';
        $this->render('share', array(
            'mobile'=>$this->mobile,
        	'num'=>$num,
        	'validate'=>md5($validate),
            'niuNaiValue'=>$niuNaiValue,
            'paiMing'=>$mingValue['paiming'],
            'isTan'=>$isTan,
        ));
    }
    /*
     * @version 朋友帮忙挤牛奶ajax
     */
    public function actionOtherJiNai(){
    	if (!isset($_POST['val'])||$_POST['val']!=md5(date("Ymd").'colourlife')){
    		echo json_encode(array('status'=>0,'msg'=>'非法操作！'));
    		exit();
    	}
        if(time()>strtotime($this->_endDay)){
            echo json_encode(array('status'=>0,'msg'=>'活动已经结束!'));
    		exit;
        }
        $result=JiNiuNai::model()->getValueByOtherJi($this->mobile);
        if($result){
        	//牛奶值
        	$niuNaiValue=JiNiuNai::model()->getValueByMobile($this->mobile);
            $mingValue=JiNiuNai::model()->getMingByMobile($this->mobile);
            $num=JiNiuNai::model()->getWaterNum($this->mobile,4);
            echo json_encode(array('status'=>1,'paiMing'=>$mingValue['paiming'],'num'=>$num,'niuNaiValue'=>$niuNaiValue,'msg'=>'您已成功帮好友挤到1ml牛奶!'));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'帮好友挤牛奶的次数已经用完了'));
        }
    }
    /*
     * @version 活动规则
     */
    public function actionRule(){
        $this->render('rule');
    }
    /*
     * @version 排名列表
     */
    public function actionPaiMing(){
        $type =  Yii::app()->request->getParam('type');
        if(empty($type)){
            $type=0;
        }
        $mingValue=JiNiuNai::model()->getMingByMobile($this->mobile);
        //活动结束后的弹框
        $isTan=JiNiuNai::model()->isTan();
        if($isTan){
            $jpArr=JiNiuNai::model()->getPrizeByPaiMing($this->mobile,$mingValue['paiming']);
        }else{
            $jpArr['dengji']='';
        }
        $this->render('paiming', array(
            'mingValue'=>$mingValue,
            'isTan'=>$isTan,
            'jp'=>$jpArr['dengji'],
            'type'=>$type,
        ));
    }
    /*
     * @version 奖项列表
     */
    public function actionJiangXiang(){
        $prizeArr=JiNiuNai::model()->getJiangByMobile($this->mobile);
        $this->render('jiangxiang', array(
            'prizeArr'=>$prizeArr,
        ));
    }

    /**
     * 验证登录
     */
//    private function checkLogin(){
//        if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])){
//                exit('<h1>用户信息错误，请重新登录</h1>');
//        }else{
//            $custId = 0;
//            if (isset($_REQUEST['cust_id'])) {  //优先有参数的
//                    $custId = intval($_REQUEST['cust_id']);
//                    $_SESSION['cust_id'] = $custId;
//            } else if (isset($_SESSION['cust_id'])) {  //没有参数，从session中判断
//                    $custId = $_SESSION['cust_id'];
//            }
//            $custId=intval($custId);
//            $customer = Customer::model()->find("id=:id and state = 0", array('id' => $custId));
//            if (empty($custId) || empty($customer)) {
//                    exit('<h1>用户信息错误，请重新登录</h1>');
//            }
//            $this->userId = $custId;
//            $this->mobile = $customer->mobile;
//        }
//    }
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
