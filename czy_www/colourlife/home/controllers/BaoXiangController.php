<?php
class BaoXiangController extends CController{
	//开始和结束时间
	private $_startDay='2016-02-27';
    private $_endDay='2016-03-18';
	private $mobile;
	private $userId;
	public $layout = false;
	
	public function init(){
		$this->checkLogin();
	}
	/**
	 * @versino 宝箱首页
	 */
	public function actionIndex(){
			//banner图进首页统计次数
			if (!isset($_SERVER['HTTP_REFERER'])&&isset($_REQUEST['cust_id'])) { 
				$this->addLog($this->userId,0);
			}
            //获取宝箱
            $result=BaoXiang::model()->getBaoXiang($this->mobile);
            if(!$result){
                exit('获取宝箱失败');
            }
            //星辰答题的领取机会
            $result3=BaoXiang::model()->xingChen($this->mobile);
            if(!$result3){
                exit('星辰答卷获取领取机会失败');
            }
            //总的领取次数
            $lingCount=  BaoXiang::model()->getCount($this->mobile);
            //获取红宝石数量
            $redCount=BaoUserYaoshi::model()->count('mobile=:mobile and yaoshi_id=:yaoshi_id', array(':mobile'=>$this->mobile,':yaoshi_id'=>1));
            //获取蓝宝石数量
            $blueCount=BaoUserYaoshi::model()->count('mobile=:mobile and yaoshi_id=:yaoshi_id', array(':mobile'=>$this->mobile,':yaoshi_id'=>2));
            //获取绿宝石数量
            $greenCount=BaoUserYaoshi::model()->count('mobile=:mobile and yaoshi_id=:yaoshi_id', array(':mobile'=>$this->mobile,':yaoshi_id'=>3));
            //获取黄宝石数量
            $yellowCount=BaoUserYaoshi::model()->count('mobile=:mobile and yaoshi_id=:yaoshi_id', array(':mobile'=>$this->mobile,':yaoshi_id'=>4));
            //获取紫宝石数量
            $popupCount=BaoUserYaoshi::model()->count('mobile=:mobile and yaoshi_id=:yaoshi_id', array(':mobile'=>$this->mobile,':yaoshi_id'=>5));
            //是否能开启宝箱
            $isCan=BaoXiang::model()->isCanOpen($this->mobile);
            //是否开启过宝箱
            $isOpen=BaoXiang::model()->isOpen($this->mobile);
            //是否能点击摇一摇
            $isYao=BaoXiang::model()->canYao();
            $this->render('index', array(
                'mobile' => $this->mobile,
                'lingCount'=>$lingCount,
                'redCount'=>$redCount,
                'blueCount'=>$blueCount,
                'greenCount'=>$greenCount,
                'yellowCount'=>$yellowCount,
                'popupCount'=>$popupCount,
                'isCan'=>$isCan,
                'isOpen'=>$isOpen,
                'isYao'=>$isYao,
                'cust_id'=>$this->userId,
            ));
	}
    /*
     * @version app 弹框
     */
    public function actionTip(){
        $cust_id =  Yii::app()->request->getParam('cust_id');
        $cusArr=  Customer::model()->findByPk($cust_id);
        $mobile=$cusArr['mobile'];
        $this->render('tip', array(
            'cust_id'=>$cust_id,
            'mobile'=>$mobile,
            
        ));
    }
    /*
     * @version 登录页面马上领取宝箱
     */
    public function actionGetBaoXiang(){
        $mobile =  Yii::app()->request->getParam('mobile');
        $baoxiangModel=BaoUserBaoxiang::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
        if(empty($baoxiangModel)){
            $baoXiang_model=new BaoUserBaoxiang();
            $baoXiang_model->mobile=$mobile;
            $baoXiang_model->is_up=0;
            $baoXiang_model->is_open=0;
            $baoXiang_model->code='';
            $baoXiang_model->prize_name='';
            $baoXiang_model->create_time=time();
            $baoXiang_model->update_time=0;
            $bao=$baoXiang_model->save();
            if(!empty($bao)){
                echo json_encode(array('status'=>1));
            }else{
                echo json_encode(array('status'=>0,'msg'=>'领取失败'));
            }
        }else{
            echo json_encode(array('status'=>0,'msg'=>'您已经领取宝箱了'));
        }
    }
    /*
     * @version 领取宝石
     */
    public function actionLingQu(){
        $mobile =  Yii::app()->request->getParam('mobile');
        $result=BaoXiang::model()->lingQuLater($mobile);
        if(!empty($result)){
            echo json_encode(array('status'=>1,'lei'=>$result['lei'],'type'=>$result['id'],'name'=>$result['prize_name']));
        }else{
            echo json_encode(array('status'=>0));
        } 
    }
    /*
     * @version 开启宝箱
     */
    public function actionOpenBox(){
        $mobile =  Yii::app()->request->getParam('mobile');
        $result=BaoXiang::model()->openBoxLater($mobile);
        if(!empty($result)){
            echo json_encode(array('status'=>1,'type'=>$result['id'],'name'=>$result['prize_name']));
        }else{
            echo json_encode(array('status'=>0));
        }     
    }
    /*
     * @version 摇一摇
     */
    public function actionYao(){
    	//统计次数
    	$this->addLog($this->userId,1);
    	$yao_result=BaoXiang::model()->canYao();
    	if (empty($yao_result)){
    		echo json_encode(array('status'=>2));
    		exit();
    	}
        $mobile =  Yii::app()->request->getParam('mobile');
        $result=BaoXiang::model()->yaoLater($mobile);
        if(!empty($result)){
            echo json_encode(array('status'=>1,'type'=>$result['id'],'name'=>$result['prize_name']));
        }else{
            echo json_encode(array('status'=>0));
        } 
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
	
	/**
	 * 摇一摇页面
	 */
	public function actionYaoYiYao(){
		$this->render('yaoyiyao',array('mobile'=>$this->mobile));
	}
	
	/**
	 * 查询
	 */
	
	public function actionQueryTreasure(){
		// 其他券
		$other_prize_result = BaoOtherPrize::model ()->findAll ( array (
				'order' => 'create_time DESC',
				'condition' => 'mobile=:mobile',
				'params' => array (
						':mobile' => $this->mobile 
				) 
		) );
		// 优惠券
		$user_coupons_result = UserCoupons::model ()->findAll ( array (
				'order' => 'create_time DESC',
				'condition' => 'mobile=:mobile and create_time>=:start and create_time<=:end',
				'params' => array (
						':mobile' => $this->mobile,
						':start' =>strtotime($this->_startDay),
						':end'=>strtotime($this->_endDay),
				) 
		) );
		if (empty($other_prize_result)&&empty($user_coupons_result)){
			$this->render('query_the_treasure_chest_null');
		}else {
			$this->render('query_the_treasure_chest',array('other_prize_result'=>$other_prize_result,'user_coupons_result'=>$user_coupons_result));
		}
	}
    /*
     * @version 规则
     */
    public function actionRule(){
        //一元购url
        $SetableSmallLoansModel = new SetableSmallLoans();
        $href = $SetableSmallLoansModel->searchByIdAndType(57, '', $this->userId);
        if ($href) {
            $hqHref = $href->completeURL;
        }
        else {
            $hqHref = '';
        }
        $this->render('rule',array('hqHref'=>$hqHref));
    }
    
    /**
     * 操作统计
     * @param unknown $cust_id
     * @param unknown $type
     * @return boolean
     */
    private function addLog($cust_id,$type=0){
    	$baoTongJi=new BaoTongjiLog();
    	$baoTongJi->cust_id=$cust_id;
    	$baoTongJi->type=$type;
    	$baoTongJi->create_time=time();
    	if ($baoTongJi->save()){
    		return true;
    	}else {
    		return false;
    	}
    }
}