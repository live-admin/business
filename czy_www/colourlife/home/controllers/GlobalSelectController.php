<?php

/**
 * Class GlobalSelectController
 * 商品二维码
 */

class GlobalSelectController extends Controller{

    private $_userId=0;

    public function init(){
        $this->checkLogin2();
    }

    private function checkLogin2()
    {
        if ((empty($_REQUEST['userid']) && empty($_SESSION['userid'])) && (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id']))) {
            $this->redirect('http://mapp.colourlife.com/m.html');
            exit();
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
                $this->redirect('http://mapp.colourlife.com/m.html');
                exit();
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

    /**
     * 验证登录
     */
    private function checkLogin()
    {
        if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])) {
            $this->redirect('http://mapp.colourlife.com/m.html');
            exit();
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
                $this->redirect('http://mapp.colourlife.com/m.html');
                exit();
            }
            $this->_userId = $custId;
        }
    }



    public function actionIndex1820(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('daytuan', '', $this->_userId,false);
        $url =$huanqiu->completeURL.'&pid=1820';
        $this->redirect($url);
    }



    public function actionGoodsOne(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $jingdong = $SetableSmallLoansModel->searchByIdAndType('jd', '', $this->_userId,false);
        $url =$jingdong->completeURL.'&pid=25794';
        $this->redirect($url);
    }

    public function actionGoodsTwo(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $jingdong = $SetableSmallLoansModel->searchByIdAndType('jd', '', $this->_userId,false);
        $url =$jingdong->completeURL.'&pid=25854';
        $this->redirect($url);
    }

    /*手机充值*/
    public function actionMobileRecharge(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $MobileRecharge = $SetableSmallLoansModel->searchByIdAndType('newMobileRecharge', '', $this->_userId,false);
        $url =$MobileRecharge->completeURL;
        $this->redirect($url);
    }

    public function actionGoodsJd(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $jingdong = $SetableSmallLoansModel->searchByIdAndType('jd', '', $this->_userId,false);
        $url =$jingdong->completeURL.'&pid=24762';
        $this->redirect($url);
    }

    /*东星旅行*/
    public function actionTravel(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $travel = $SetableSmallLoansModel->searchByIdAndType('dongxing', '', $this->_userId,false);
        $url =$travel->completeURL.'&id=3';
        $url = str_replace("http://www.es789.com:802/caishenghuo.html","http://www.es789.com:8022/caishenghuo/TripProductDetail.html",$url);
        $this->redirect($url);
    }

    /*彩旅游*/
    public function actionColourTravel342(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $cailvyou = $SetableSmallLoansModel->searchByIdAndType('cailvyou', '', $this->_userId,false);
        $url =$cailvyou->completeURL.'&vid=33&id=342';
        $url = str_replace("http://cailvyou.colourlife.com","http://wap.cailvyou.com/travel/detail",$url);
        $this->redirect($url);
    }

    public function actionColourTravel336(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $cailvyou = $SetableSmallLoansModel->searchByIdAndType('cailvyou', '', $this->_userId,false);
        $url =$cailvyou->completeURL.'vid=37&id=336';
        $url = str_replace("http://cailvyou.colourlife.com","http://wap.cailvyou.com/travel/detail",$url);
        $this->redirect($url);
    }

    public function actionColourTrave425(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $cailvyou = $SetableSmallLoansModel->searchByIdAndType('cailvyou', '', $this->_userId,false);
        $url =$cailvyou->completeURL.'&id=425';
        $url = str_replace("http://cailvyou.colourlife.com","http://wap.cailvyou.com/travel/detail",$url);
        $this->redirect($url);
    }

    /*E师傅*/
    public function actionEshifu($id){
        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId('EWEIXIU',1,$this->_userId);
        $url = $rent->completeURL;
        $url_1 = 'http://m.eshifu.cn/family/qr?sid='.$id.'&';
        $url = str_replace("http://tsbxsso.colourlife.net/bxindex.aspx?",$url_1,$url);
        $this->redirect($url);
    }

    public function actionPropertyActivity(){
        $this->renderPartial('propertyActivity');
    }

    /*彩生活特供*/
    public function actionColourlifeSell(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('daytuan', '', $this->_userId,false);
        $url =$huanqiu->completeURL;
        $this->redirect($url);
    }


    /*星晨旅游*/
    public function actionMoringtravel(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('xingchenjq', '', $this->_userId,false);
        $url =$huanqiu->completeURL;
        $this->redirect($url);
    }

    public function actionMoringtravel48(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('xingchenjq', '', $this->_userId,false);
        $url =$huanqiu->completeURL;
        $url = str_replace("http://ec2-52-192-112-202.ap-northeast-1.compute.amazonaws.com/mst-colorlife-api/view/product/index","http://ec2-52-192-112-202.ap-northeast-1.compute.amazonaws.com/mst-colorlife-api/view/product/productDetail/48",$url);
        $this->redirect($url);
    }

    public function actionXcD($id){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('xingchenjq', '', $this->_userId,false);
        $url =$huanqiu->completeURL;
        $url = str_replace("http://ec2-52-192-112-202.ap-northeast-1.compute.amazonaws.com/mst-colorlife-api/view/product/index","http://ec2-52-192-112-202.ap-northeast-1.compute.amazonaws.com/mst-colorlife-api/view/product/productDetail/".$id,$url);
        $this->redirect($url);
    }

    public function actionRedWine(){
        $url = 'http://www.colourlife.com/MotherDay';
        $this->redirect($url);
    }

    /*E家政*/
    public function actionEjaizheng(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('ebaojie', '', $this->_userId,false);
        $url =$huanqiu->completeURL;
        $this->redirect($url);
    }



    public function actionDetail($id){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('daytuan', '', $this->_userId,false);
        $url =$huanqiu->completeURL.'&pid='.$id;
        $this->redirect($url);
    }

    public function actionColourTravel113(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $cailvyou = $SetableSmallLoansModel->searchByIdAndType('cailvyou', '', $this->_userId,false);
        $url =$cailvyou->completeURL.'&vid=113&id=568';
        $url = str_replace("http://cailvyou.colourlife.com","http://wap.cailvyou.com/travel/detail",$url);
        $this->redirect($url);
    }

    /*环球*/
    public function actionHuangQiu(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('huanqiujingxuanH5', '', $this->_userId,false);
        $url =$huanqiu->completeURL;
        $this->redirect($url);
    }

    /*千禧*/
    public function actionQianXi(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('qianxizhixing', '', $this->_userId,false);
        $url =$huanqiu->completeURL;
        $this->redirect($url);
    }

    /*良食*/
    public function actionLiangShi(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('food', '', $this->_userId,false);
        $url =$huanqiu->completeURL;
        $this->redirect($url);
    }



    public function actionLiZhi(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('activity', '', $this->_userId,false);
        $url =$huanqiu->completeURL;
        $this->redirect($url);
    }

    public function actionIndex31659(){
        $sql = "SELECT id FROM cheap_log WHERE goods_id=31659 AND is_deleted=0 AND `status`=0";
        $code = Yii::app()->db->createCommand ($sql)->queryAll();
        $pid=$code[0]['id'];
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('daytuan', '', $this->_userId,false);
        $url =$huanqiu->completeURL.'&pid='.$pid;
        $this->redirect($url);
    }

    public function actionYiDai(){

        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId('hehuayidai',1,$this->_userId);
        $url = $rent->completeURL;
//        $SetableSmallLoansModel = new SetableSmallLoans();
//        $cailvyou = $SetableSmallLoansModel->searchByIdAndType('hehuayidai', '', $this->_userId,false);
//        $url =$cailvyou->completeURL;
        $url = str_replace("http://test.hehuayidai.com:3150/user/cfbLogin.do","http://user.hehuayidai.com/user/cfbLogin.do",$url);
        $this->redirect($url);
    }

    /*
     * E缴费*/
    public function actionEasyPay(){
        $this->renderPartial('easyPay');
    }

    /*
     * E停车*/
    public function actionEasyPark(){
        $this->renderPartial('easyPark');
    }

    /*
     * 微商圈*/
    public function actionMicro(){
        $this->renderPartial('micro');
    }

    /*
     * 我的饭票*/
    public function actionMeal(){
        $this->renderPartial('meal');
    }


    /*
     * 京东*/
    public function actionJingDong(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $jingdong = $SetableSmallLoansModel->searchByIdAndType('jd', '', $this->_userId,false);
        $url =$jingdong->completeURL;
        $this->redirect($url);
    }

    /*
     * 彩旅游*/
    public function actionColourTravel(){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $cailvyou = $SetableSmallLoansModel->searchByIdAndType('cailvyou', '', $this->_userId,false);
        $url =$cailvyou->completeURL;
        $this->redirect($url);
    }

    /*
     * 通用*/
    public function actionMore(){
        $key =Yii::app()->request->getParam('key');
        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId($key,1,$this->_userId);
        $url = $rent->completeURL;
        //dump($url);
        $this->redirect($url);
    }

    /*
     * E住房*/
    public function actionRent(){
        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId('MIANYONGZUFANG',1,$this->_userId);
        $url = $rent->completeURL;
        $this->redirect($url);
    }

    public function actionJd(){
        $id =Yii::app()->request->getParam('id');
        $SetableSmallLoansModel = new SetableSmallLoans();
        $jingdong = $SetableSmallLoansModel->searchByIdAndType('jd', '', $this->_userId,false);
        $url =$jingdong->completeURL.'&pid='.$id;
        $this->redirect($url);
    }


    public function actionDet($id){
        $cheap = CheapLog::model();
        $url_goods = $cheap->findByAttributes(array('goods_id'=>$id , 'status'=>0 , 'is_deleted'=>0));
        $pid = $url_goods->id;
        $SetableSmallLoansModel = new SetableSmallLoans();
        $huanqiu = $SetableSmallLoansModel->searchByIdAndType('daytuan', '', $this->_userId,false);
        $url =$huanqiu->completeURL.'&pid='.$pid;
        $this->redirect($url);
    }

    public function actionHq($id){
        $arr_id = array(
            '1'=>'9e7bb888-edcc-476f-a774-feb990d121be',
            '2'=>'c3ba4f42-b0dc-44f6-b04c-66a5d94aed8c',
            '3'=>'bcafa2df-bde9-4d08-8a75-a4484e948d61',
            '4'=>'dde92895-39ba-4025-9b8a-b8562a586432',
            '5'=>'64fa1e76-6784-4417-a726-dc1af2b09417',
            '6'=>'423973ae-3668-45ae-9ef1-fa05ba610b87',
            '7'=>'514cc6d0-472b-4b30-839a-5baacb88a220',
            '8'=>'f9a6c0ca-4ab9-41bc-b565-cf6c9f0e924f',
            '9'=>'16091b1b-12b8-4079-b86f-9398e222e0b0',
            '10'=>'1c70e757-ce5c-4bbd-b166-b416e94a7914',
            '11'=>'8f4a23ca-98b2-416c-a4d4-6aeb62f372d9',
            '12'=>'5945eb29-f014-48d1-a10b-a2939b0c7d05',
            '13'=>'f22b0815-bf90-4140-8f0b-8ddcb87f25bc',
            '14'=>'d5402ff5-2bd3-48f7-9049-050f46b4cffe',
            '15'=>'65877a61-5a1f-4c55-a0ae-dfc7b8a0d7ae',
            '16'=>'92c49f43-041c-4f50-90eb-4fa67808f2b2',
            '17'=>'d342429d-0ed1-4e19-a812-95b6bf42837c',
            '18'=>'c3c4ce0c-db52-4000-a471-a91e278cb81a',
        );

        $HomeConfig = new HomeConfigResource();
        $rent = $HomeConfig->getResourceByKeyOrId('huanqiujingxuanH5',1,$this->_userId);
        $url = $rent->completeURL;
        $url = str_replace("http://czy.globalselect.net.au/account/colourlifelogin","http://czy.globalselect.net.au/850f37c5-3853-48a8-a405-1214e4c802cc/Product/Index/$arr_id[$id]",$url);
        $this->redirect($url);
    }


    public function actionCsh($id){
        $cheap = CheapLog::model();
        //$url_goods = $cheap->findByAttributes(array('goods_id'=>$id , 'status'=>0 , 'is_deleted'=>0));
        $pid = $id;
        $HomeConfig = new HomeConfigResource();
        $huanqiu = $HomeConfig->getResourceByKeyOrId('caishihuisc',1,$this->_userId);
        $url =$huanqiu->completeURL.'&pid='.$pid;
        $this->redirect($url);
    }

    //彩旅游
    public function actionCtr($id){
        $SetableSmallLoansModel = new SetableSmallLoans();
        $cailvyou = $SetableSmallLoansModel->searchByIdAndType('cailvyou', '', $this->_userId,false);
        $url =$cailvyou->completeURL.'&id='.$id;
        $url = str_replace("http://cailvyou.colourlife.com","http://wap.cailvyou.com/travel/detail",$url);
        $this->redirect($url);
    }


}