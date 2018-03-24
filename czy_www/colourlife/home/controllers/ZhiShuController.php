<?php
/*
 * @version 植树节活动
 */
class ZhiShuController extends CController{
    private $_startDay='2016-03-10';//活动开始时间
    private $_endDay='2016-03-20';//活动结束时间
    private $mobile;
    private $userId;
    public $layout = false;
    public function init(){
        if (time()<strtotime($this->_startDay)||time()>strtotime($this->_endDay)){
			exit('<h1>活动还没开始或已结束</h1>');
		}
        $this->checkLogin();
    }
    /*
     * @version 领取种子
     */
    public function actionLing(){
        ZhiShuJie::model()->addShareLog($this->userId,3);  //记录分享点击时间
        $result=  ZhiShuJie::model()->isZhongZi($this->mobile);
        if($result){
            $this->render('ling', array(
                'mobile'=>$this->mobile,
            ));
        }else{
            $this->redirect('/ZhiShu/index');
        }
    }
    /*
     * @version 开始种植动作ajax
     */
    public function actionZhongZhi(){
        $mobile =  Yii::app()->request->getParam('mobile');
        $result=  ZhiShuJie::model()->getZhongZi($mobile);
        if(!empty($result)){
            echo json_encode(array('status'=>1));//种植成功
        }else{
            echo json_encode(array('status'=>0,'msg'=>'获取种子失败，请重新获取'));//种植失败
        }
    }
    /*
     * @version 首页
     */
    public function actionIndex(){
        $result=  ZhiShuJie::model()->isZhongZi($this->mobile);
        if($result){
            $this->redirect('/ZhiShu/Ling?cust_id='.$this->userId);
        }
        
        $isCaiFuUser=ZhiShuJie::model()->isCaiFuUser($this->mobile);//是否是彩富人生用户(是否弹框增长值)
        $isNoCaiFuUser=ZhiShuJie::model()->isNoCaiFuUser($this->mobile);//是否是彩富人生用户(是否弹框增长值)
        //成长值
        $chengZhangValue=ZhiShuJie::model()->getValueByMobile($this->mobile);
        //返回的奖品弹框
        $prizeArr=ZhiShuJie::model()->getPrizeByGrowValue($this->mobile);
        //今天是否自己浇水过
        $isJiaoShui=ZhiShuJie::model()->isJiaoShui($this->mobile);
        //明天的成长值
        $chengZhangTomorrow=ZhiShuJie::model()->getGrowValueTomorrow($this->mobile);
        $this->render('index', array(
            'isCaiFuUser'=>$isCaiFuUser,
            'isNoCaiFuUser'=>$isNoCaiFuUser,
            'mobile'=>$this->mobile,
            'cust_id'=>$this->userId,
            'chengZhangValue'=>$chengZhangValue,
            'prizeArr'=>$prizeArr,
            'isJiaoShui'=>$isJiaoShui,
            'chengZhangTomorrow'=>$chengZhangTomorrow['value'],
        ));
    }
    /*
     * @version 领取彩富人生成长值ajax
     */
    public function actionGetCai(){
        $mobile =  Yii::app()->request->getParam('mobile');
        $result=ZhiShuJie::model()->getValueByCaiFu($mobile);
        if($result){
            echo json_encode(array('status'=>1));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'领取失败!'));
        }
    }
    /*
     * @version 非彩富人生用户ajax
     */
    public function actionGetNoCai(){
        $mobile =  Yii::app()->request->getParam('mobile');
        $result=ZhiUserZhongzi::model()->updateAll(array('times' =>new CDbExpression('times+1')),"mobile=".$mobile);
        if($result){
            echo json_encode(array('status'=>1));
        }else{
            echo json_encode(array('status'=>0));
        }
    }

    /*
     * @verson 自己浇水动作ajax
     */
    public function actionMyJiao(){
        $mobile =  Yii::app()->request->getParam('mobile');
        $result=ZhiShuJie::model()->getGrowValueByMyJiao($mobile);
        
        if(!empty($result)){
            echo json_encode(array('status'=>1,'value'=>$result['value']));//返回浇水后的数组
        }else{
            echo json_encode(array('status'=>0));
        }
    }
    /*
     * @version 点击分享后的ajax
     */
    public function actionFenXiang(){
    	ZhiShuJie::model()->addShareLog($this->userId,1);  //记录分享点击时间
        $mobile =  Yii::app()->request->getParam('mobile');
        $result=ZhiShuJie::model()->shareToFriend($mobile);
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
        //$mobile=intval(Yii::app()->request->getParam('mobile'));
        ZhiShuJie::model()->addShareLog(0,2);  //记录分享页
        //成长值
        $chengZhangValue=ZhiShuJie::model()->getValueByMobile($this->mobile);
        $num=ZhiShuJie::model()->getWaterNum($this->mobile,4);
        $validate=date("Ymd").'colourlife';
        $this->render('share', array(
            'mobile'=>$this->mobile,
        	'num'=>$num,
        	'validate'=>md5($validate),
            'chengZhangValue'=>$chengZhangValue,
        ));
    }
    /*
     * @version 朋友帮忙浇水ajax
     */
    public function actionOtherJiaoShui(){
    	if (!isset($_POST['val'])||$_POST['val']!=md5(date("Ymd").'colourlife')){
    		echo json_encode(array('status'=>0,'msg'=>'非法操作！'));
    		exit();
    	}
        $result=ZhiShuJie::model()->getValueByOtherJiao($this->mobile);
        if($result){
        	//成长值
        	$chengZhangValue=ZhiShuJie::model()->getValueByMobile($this->mobile);
        	$num=ZhiShuJie::model()->getWaterNum($this->mobile,4);
            echo json_encode(array('status'=>1,'msg'=>'帮好友浇水成功!','czvalue'=>$chengZhangValue,'num'=>$num));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'今天已经浇够啦!明天再来吧'));
        }
    }
    /*
     * @version 活动规则
     */
    public function actionRule(){
        $this->render('rule');
    }

    /**
	 * 验证登录
	 */
	private function checkLogin(){
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
			$this->mobile = $customer->mobile;
		}
	}
    
    
    
}
