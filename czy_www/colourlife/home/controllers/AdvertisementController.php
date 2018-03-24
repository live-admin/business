<?php


class AdvertisementController extends Controller{

    private $_userId=0;



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
//			$this->_username = $customer->username;
//			//点赞才使用
//			if (empty($this->_isAnnounce)){
//				$result=ThanksGivingResult::model()->find("customer_id=:cust_id and day =:day", array(':cust_id' => $custId,':day'=>date("Ymd")));
//				if ($result) {
//					$this->_luckyTodayCan = 1;
//				}
//			}
        }
    }


    private function checkLogin2()
    {
        if ((empty($_REQUEST['userid']) && empty($_SESSION['userid'])) && (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id']))) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        } else {
            $custId = 0;

            if (isset($_REQUEST['userid'])) {  //优先有参数的
                $custId = intval($_REQUEST['userid']);
                $_SESSION['userid'] = $custId;
            } else if (isset($_SESSION['userid'])) {  //没有参数，从session中判断
                $custId = $_SESSION['userid'];
            }else if (isset($_REQUEST['cust_id'])) {  //优先有参数的
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
//			$this->_username = $customer->username;
//			//点赞才使用
//			if (empty($this->_isAnnounce)){
//				$result=ThanksGivingResult::model()->find("customer_id=:cust_id and day =:day", array(':cust_id' => $custId,':day'=>date("Ymd")));
//				if ($result) {
//					$this->_luckyTodayCan = 1;
//				}
//			}
        }
    }


    /*
     * E缴费
     */
    public function actionEasyPay(){
        $this->renderPartial('easyPay');
    }

    /*
     * 彩富人生
     */
    public function actionWealthLife(){
        $this->renderPartial('wealthLife');
    }

    /*
     * E投诉
     */
    public function actionEasyComplain(){
        $this->checkLogin();
        $model = SetableSmallLoans::model();
        $url = $model->searchByIdAndType('tousubaoxiu', '', $this->_userId,false);
        $this->renderPartial('easyComplain',array('url'=>$url->completeURL));
    }

    /*
     * E停车
     */
    public function actionEasyParking(){
        $this->renderPartial('easyParking');
    }

    /*
     * 金猴送礼
     */
    public function actionMonkey(){
        $this->renderPartial('monkey');
    }

    /*
     * 门禁说明*/
    public  function actionMenjin(){
        $this->renderPartial('door');
    }

    public function actionLinLi(){
        $this->renderPartial('linLi');
    }

    public function actionSiQing(){
        $this->checkLogin2();
        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId('siqingchou',1,$this->_userId);
        //dump($rent);
        $url = $rent->completeURL;
        $this->redirect($url);
    }

    public function actionColourEat(){
        $this->checkLogin2();
        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId('daytuan',1,$this->_userId);
        $url =$rent->completeURL;
        $cheap = CheapLog::model();
        $url_goods = $cheap->findByAttributes(array('goods_id'=>31968 , 'status'=>0 , 'is_deleted'=>0));
        $pid1 = $url_goods->id;
        $url_goods = $cheap->findByAttributes(array('goods_id'=>31852 , 'status'=>0 , 'is_deleted'=>0));
        $pid2 = $url_goods->id;
        $url_goods = $cheap->findByAttributes(array('goods_id'=>31855 , 'status'=>0 , 'is_deleted'=>0));
        $pid3 = $url_goods->id;
        $url_goods = $cheap->findByAttributes(array('goods_id'=>31854 , 'status'=>0 , 'is_deleted'=>0));
        $pid4 = $url_goods->id;
        $url_goods = $cheap->findByAttributes(array('goods_id'=>30085 , 'status'=>0 , 'is_deleted'=>0));
        $pid5 = $url_goods->id;
        $urlInfo = array(
            'xiaqiu' => $url.'&pid='.$pid1,
            'lvixa' => $url.'&pid='.$pid2,
            'xiangla' => $url.'&pid='.$pid3,
            'egg' => $url.'&pid='.$pid4,
            'lvdou' => $url.'&pid='.$pid5,
        );
        $this->renderPartial('colourEat',array('urlInfo'=>$urlInfo));
    }

    public function actionPlant(){
        $this->renderPartial('plant');
    }

    public function actionTravel(){
           $this->checkLogin2();
            $SetableSmallLoansModel = new SetableSmallLoans();
            $cailvyou = $SetableSmallLoansModel->searchByIdAndType('cailvyou', '', $this->_userId,false);
            $url =$cailvyou->completeURL.'&vid=176&id=721';
            $url = str_replace("http://cailvyou.colourlife.com","http://wap.cailvyou.com/travel/detail",$url);
            $this->redirect($url);
    }

    public function actionGarden(){
        $this->renderPartial('garden');
    }

    public function actionQiXi(){
        $this->renderPartial('qixi');
    }

    public function actionLin(){
        $this->renderPartial('lin');
    }

    public function actionSummer(){
        $this->renderPartial('summer');
    }

    public function actionTeacher(){
        $this->renderPartial('teacher');
    }

    public function actionFood(){
        $this->checkLogin2();
        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId('food',1,$this->_userId);
        $url =$rent->completeURL;
        $url = str_replace("http://www.okliang.com/caizhiyun/index.php?","http://www.okliang.com/caizhiyun/flow.php?act=goods_detail&goods_id=995&",$url);
        $this->redirect($url);
    }


    //e旅游宣传
    public function actionColourTravel(){
        $this->checkLogin2();
        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId('cailvyou',1,$this->_userId);
        $url =$rent->completeURL;
        $urlArr = array(
            'hubei'=>$url.'&reurl=/travel/detail?id=1078',
            'zhuhai'=>$url.'&reurl=/travel/detail?id=1041',
            'nanxiong'=>$url.'&reurl=/travel/detail?id=1024',
            'huizhou'=>$url.'&reurl=/travel/detail?id=1055',
        );
        $this->renderPartial('travel', array('urlArr'=>$urlArr));
    }
    
    /**
     * 猜灯谜
     */
    public function actionCaiDengMi(){
    	$this->renderPartial('caidengmi');
    }
    public function actionNotice(){
        $this->renderPartial('/v2016/notice/index');
    }

    public function actionLinli2(){
        $this->renderPartial('linli2');
    }

    public function actionLinli3(){
        $this->renderPartial('linli3');
    }

    public function actionActivity(){
        $this->renderPartial('activity');
    }
    public function actionEvent(){
        $this->renderPartial('event');
    }

    //预缴费活动线上链接
    public function actionFee(){
        $this->checkLogin2();
        $customer_id = $this->_userId;
        $model = new SeptemberLog();
        $save = 0;
        if($model->addShareLog($customer_id,11) && $customer_id>0)
            $save = 1;
        $this->renderPartial('fee',array('confirm'=>$save));
    }

    public function actionEmployeeNotify(){
    	$userID = Yii::app()->request->getParam('cust_id');
    	if (empty($userID)){
    		throw new CHttpException(400,'请先登录！');
    	}
    	$userID = intval(($userID-1778)/777);
    	$homeConfig=new HomeConfigResource();
    	$href3=$homeConfig->getResourceByKeyOrId(815,1,$userID); //妇女节
    	$url = !empty($href3) ? $href3->completeURL : '';
    	$data['spare'][]= array('url' => $url);
    	$data['current']= array();
        //echo '该功能正在开发中！';
    	$this->renderPartial('employee',$data);
    }

    public function actionEvent1(){
        $this->renderPartial('event1');
    }
    public function actionEvent2(){
        $this->renderPartial('event2');
    }

    public function actionEvent3(){
        $this->renderPartial('event3');
    }
    public function actionEvent4(){
        $this->renderPartial('event4');
    }

    //春节旅游
    public function actionSpringTravel(){
        $this->checkLogin2();
        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId('cailvyou',1,$this->_userId);
        $url =$rent->completeURL;
        $urlArr = array(
            array(
                'xiamen'=>$url.'&reurl=/travel/detail?id=1297',
                'xijiang'=>$url.'&reurl=/travel/detail?id=893',
            ),
            array(
                'zhuhai'=>$url.'&reurl=/travel/detail?id=1041',
                'yunnan'=>$url.'&reurl=/travel/detail?id=2428',
                'tianyahaijiao'=>$url.'&reurl=/travel/detail?id=2426',
                'guizhou'=>$url.'&reurl=/travel/detail?id=897',
            ),
            array(
                'xinma'=>$url.'&reurl=/travel/detail?id=1636',
                'xuewu'=>$url.'&reurl=/travel/detail?id=1930',
                'puji'=>$url.'&reurl=/travel/detail?id=730',
                'taishifengqing'=>$url.'&reurl=/travel/detail?id=673',
                'shouer'=>$url.'&reurl=/travel/detail?id=2412',
                'ouzhou'=>$url.'&reurl=/travel/detail?id=1755',
                'malaixiya'=>$url.'&reurl=/travel/detail?id=1830',
                'riben'=>$url.'&reurl=/travel/detail?id=2369',
            ),
        );

        $this->renderPartial('springTravel', array('urlArr'=>$urlArr));

    }

    public function actionOrange1(){
        $this->checkLogin2();
        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId('daytuan',1,$this->_userId);
        $url =$rent->completeURL;
        $cheap = CheapLog::model();
        $url_goods = $cheap->findByAttributes(array('goods_id'=>41095 , 'status'=>0 , 'is_deleted'=>0));
        $pid1 = $url_goods->id;
        $url = $url.'&pid='.$pid1;
        $this->redirect($url);
    }

    public function actionOrange2(){
        $this->checkLogin2();
        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId('daytuan',1,$this->_userId);
        $url =$rent->completeURL;
        $cheap = CheapLog::model();
        $url_goods = $cheap->findByAttributes(array('goods_id'=>41096 , 'status'=>0 , 'is_deleted'=>0));
        $pid1 = $url_goods->id;
        $url = $url.'&pid='.$pid1;
        $this->redirect($url);
    }
}