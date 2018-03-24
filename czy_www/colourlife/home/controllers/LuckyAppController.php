<?php

class LuckyAppController extends CController
{
    //copy from luckyController 

    private $_luckyCustCan = 0;
    private $_luckyTodayCan = 0;
    private $_luckyActId = Item::LUCKY_ACT_ID;
    private $_username = "";
    private $_userId = 0;
    private $_cust_model = "";
    public $entityList = array(89, 90, 91, 92, 93, 94, 95, 96, 97, 98,
        106, 107, 108, 109, 110, 111, 112, 113, 114, 115,
        125, 126, 127, 128, 129, 130, 131, 132, 133, 136,
        154, 155, 156, 157, 158, 159, 160, 161
    );
    private $_dreamActId = Item::DREAM_ACT_ID;
    private $_userIP = "";

    //是否结束
    private function  isover()
    {
        $luckyAct = LuckyActivity::model()->findByPk($this->_luckyActId);
        if ($luckyAct && ($luckyAct->end_date . " 23:59:59" < date("Y-m-d H:i:s"))) {
            return true;//已结束
        }
        return false;//未结束
    }


    //是否开始
    private function  isstart()
    {
        $luckyAct = LuckyActivity::model()->findByPk($this->_luckyActId);
        if ($luckyAct && ($luckyAct->start_date . " 00:00:00" <= date("Y-m-d H:i:s"))) {
            return true;//已开始
        }
        return false;//未开始
    }

    public function actionIndex()
    {
//        $this->checkLogin();
//        $allJoin=LuckyCustomerOut::model()->count('lucky_act_id<>12');
//        if(date('Y-m-d H:i:s')>='2015-03-30 23:59:59'){
//            $this->renderPartial( "paintPuzzle",array (
//                "luckyCustCan" => $this->_luckyCustCan,
//                "luckyTodayCan" => $this->_luckyTodayCan,
//                "custId"=>$this->_userId,
//                "allJoin"=>$allJoin+123,
//            ));
//        }else{
//            $this->renderPartial( "yugao6");
//        }
        $this->checkLogin();
        if (date('Y-m-d H:i:s') >= '2015-08-16 23:59:59') {
            $this->renderPartial("onesecond/index", array(
                "luckyCustCan" => $this->_luckyCustCan,
                "luckyTodayCan" => $this->_luckyTodayCan,
                "custId" => $this->_userId,
            ));
        } else {
            exit('<h1>活动未开始，敬请期待</h1>');
        }
    }

    public function actionDjhIndex()
    {

        $this->checkLogin();
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('sfheike', 1, $this->_userId, false);
        if ($re) {
            $url = $re->completeURL;
        } else $url = 'error';


        $connection = Yii::app()->db;
        $sql = "select cu.id,cu.username,cu.password,cu.mobile, cu.community_id,co.name,re3.name 省,re2.name 市,re.name 区,br_3.name 事业部 from  customer  cu
                LEFT JOIN community co on (cu.community_id=co.id)
                LEFT JOIN region as re on (co.region_id=re.id)
                LEFT JOIN region as re2 on (re.parent_id=re2.id)
                LEFT JOIN region as re3 on (re2.parent_id=re3.id)
                LEFT JOIN branch as br on(co.branch_id=br.id) 
                LEFT JOIN branch as br_2 on(br.parent_id=br_2.id )  
                LEFT JOIN branch as br_3 on(br_2.parent_id=br_3.id) 
                where cu.id=" . $this->_userId;
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        $userid = $this->_userId;
        $username = $result[0]['username'];
        $mobile = $result[0]['mobile'];
        $password = $result[0]['password'];
        $community_id = $result[0]['community_id'];
        $community_name = $result[0]['name'];
        $shiyebu = $result[0]['事业部'];
        $sheng = $result[0]['省'];
        $shi = $result[0]['市'];
        $qu = $result[0]['区'];
        $caddress = "$sheng-$shi-$qu-$community_name";
        $sign = "$userid$username$mobile$password$community_id$shiyebu$community_name$caddress";
        $sing = strtoupper(md5($sign));
        //和合年贷款url
        $URL2 = "http://m.hehenian.com/colourlife/index.do?bno=&bsecret=&userid=$userid&username=$username&mobile=$mobile&password=$password&cid=$community_id&tjrid=&branchName=$shiyebu&cname=$community_name&caddress=$caddress&sign=$sing";

        $userid = $this->_userId;
        $SetableSmallLoansModel = new SetableSmallLoans();
        $href = $SetableSmallLoansModel->searchByIdAndType(113, '', $userid);
        if ($href) {
            $dzxHref = $href->completeURL;
        } else {
            $dzxHref = '';
        }
        //大闸蟹url
        $DaZhaXieUrl = $href->completeURL;


        $this->renderPartial("djh_index", array(
            'cust_id' => $this->_userId,
            'url' => $url,
            'url2' => $URL2,
            'DaZhaXieUrl' => $DaZhaXieUrl,
        ));
    }

    public function actionInviteLucky()
    {
        $this->checkLogin();
        //LuckyDoAdd::finishInfo($this->_userId, $this->_username);
        $allJoin = LuckyCustomerOut::model()->count('lucky_act_id<>12');
        if (date('Y-m-d H:i:s') >= '2015-03-30 23:59:59') {
            $this->renderPartial("paintPuzzle", array(
                "luckyCustCan" => $this->_luckyCustCan,
                "luckyTodayCan" => $this->_luckyTodayCan,
                "custId" => $this->_userId,
                "allJoin" => $allJoin + 123,
            ));
        } else {
            $this->renderPartial("yugao6");
        }
    }


    public function actionPaintPuzzleRule()
    {
        $this->checkLogin();
        $this->renderPartial("onesecond/rule");
    }


    public function actionPaintPuzzleResult()
    {
        $this->checkLogin();
        $listResutlist = $this->getListData();
        $list = LuckyCustResult::model()->findAll("cust_id=" . $this->_userId . " and lucky_act_id in (" . Item::LUCKY_ACT_ID . "-2," . Item::LUCKY_ACT_ID . "-1," . Item::LUCKY_ACT_ID . ") and prize_id!=" . Item::LUCKY_THANKS_ID . " order by id desc");
        $this->renderPartial("onesecond/record", array(
            "listResutl" => $listResutlist,
            "list" => $list,
        ));
    }

    /*
     * @update 修改时间跳转页
     * @update 2015-06-17 19:09
     * @by wenda
     */
    public function actionMainIndex()
    {
        $this->checkLoginCar();
        if (date('Y-m-d H:i:s') >= '2015-06-17 23:59:59') {
            $this->renderPartial("car/main");
        } else {
            $this->renderPartial("lucky_car_main");
        }
    }


    public function actionLuckyApp()
    {
        $this->checkLoginCar();
        $customer = Customer::model()->findByPk($this->_userId);
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('oneyuan', 1, $this->_userId, false);
        if ($re) {
            $url = $re->completeURL;
        } else $url = 'error';
        $this->renderPartial("lucky_car", array('balance' => $customer->getBalance(), 'url' => $url,));
    }

    public function actionLuckyCarResult()
    {
        $this->checkLoginCar();
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('oneyuan', 1, $this->_userId, false);
        if ($re) {
            $url = $re->completeURL;
        } else $url = 'error';
        $list = LuckyCustResult::model()->findAll("cust_id=" . $this->_userId . " and lucky_act_id in (" . Item::LUCKY_ACT_ID_CAR . ") and prize_id!=" . Item::LUCKY_THANKS_ID . " order by id desc");
        $this->renderPartial("lotteryDetails", array("list" => $list, 'url' => $url));
    }


    /*
     * 汔车抽奖显示页
     * @add 2015-06-17
     * @by wenda
     */
    public function actionLuckyCarResultNew()
    {
        $this->renderPartial("car/code");
        // $this->renderPartial("car/result");
    }

    /*
     * 汔车抽奖列表页
     * @add 2015-06-17
     * @by wenda  19:12
     */
    public function actionLuckyCarResultList()
    {
        //$this->checkLoginCar();
        // $list = LuckyMayCarOutcome::model()->findAll(array('condition'=>" state=0", 'limit'=>'200'));
        //$this->renderPartial("lotteryMycode", array('list'=>$list));
        $sql = "SELECT t.*, c.mobile FROM lucky_may_car_outcome as t
                left join customer as c on (c.id=t.customer_id)
               LIMIT 200";
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        $obj = new LuckyMayCarOutcome();
        $this->renderPartial("car/list", array('list' => $list, 'obj' => $obj));
    }

    /*
     * @update 修改时间跳转页
     * @update 2015-06-17 19:09
     * @by wenda
     */
    public function actionCarTopic()
    {
        $this->checkLoginCar();
        $_mModel = new SetableSmallLoans();
        $ree = $_mModel->searchByIdAndType('LICAIYI', 1, $this->_userId, false);
        if ($ree) {
            $urle = $ree->completeURL;
        } else $urle = 'error';
        $list = LuckyMayCarOutcome::model()->findAll(" state=0 and customer_id=" . $this->_userId);
        $count = LuckyMayCarOutcome::model()->count(" state=0 and customer_id=" . $this->_userId);
        $this->renderPartial("lotterySubweb", array('urle' => $urle, 'list' => $list, 'count' => $count));
    }


    public function actionCarMyCode()
    {
        $this->checkLoginCar();
        $list = LuckyMayCarOutcome::model()->findAll(" state=0 and customer_id=" . $this->_userId);
        $this->renderPartial("lotteryMycode", array('list' => $list));
    }


    public function actionLuckyCarRule()
    {
        $this->checkLoginCar();
        $_mModel = new SetableSmallLoans();
        $ree = $_mModel->searchByIdAndType('LICAIYI', 1, $this->_userId, false);
        if ($ree) {
            $urle = $ree->completeURL;
        } else $urle = 'error';
        $this->renderPartial("lotteryCarRule", array('urle' => $urle));
    }


    public function actionNewPurchase()
    {
        $this->checkLogin();
        $_mModel = new SetableSmallLoans();
        $ree = $_mModel->searchByIdAndType('anshi', 1, $this->_userId, false);
        if ($ree) {
            $urle = $ree->completeURL;
        } else $urle = 'error';
        $this->renderPartial("new_purchase", array('urle' => $urle));
    }


    public function actionUploadFail()
    {
        $this->renderPartial("upload_fail");
    }


    public function actionLoad()
    {
        if (isset($_POST['submit'])) {
            $file = CUploadedFile::getInstanceByName('fileField');//获取上传的文件实例
            if ($file->getType() == 'application/vnd.ms-excel') {
                $arr = explode('.', $file->getName());
                $ext = $arr[count($arr) - 1];
                $excelFile = Yii::getPathOfAlias('excel_upload') . '/' . date("YmdHis") . mt_rand(1, 9999) . "." . $ext;
                $is = move_uploaded_file($file->getTempName(), $excelFile);
                if (!$is) exit('上传文件失败！');
                Yii::$enableIncludePath = false;
                Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
                $excelReader = PHPExcel_IOFactory::createReader('Excel5');
                $phpexcel = $excelReader->load($excelFile)->getActiveSheet();//载入文件并获取第一个sheet       
                $total_line = $phpexcel->getHighestRow();
                $total_column = $phpexcel->getHighestColumn();
                $data = array();
                for ($row = 2; $row <= $total_line; $row++) {
                    for ($column = 'A'; $column <= $total_column; $column++) {
                        $data[$row][] = trim($phpexcel->getCell($column . $row)->getValue());
                    }
                }
                $str = "";
                foreach ($data as $k => $v) {
                    $str .= "update customer set state=1,is_deleted=1,balance=0 where mobile='" . $v[1] . "';";
                    $str .= "<br/>";
                    $str .= "update invite set state=2 mobile='" . $v[1] . "';";
                    $str .= "<br/>";
                }
                echo $str;
                @unlink($excelFile);
            } else {
                exit('上传文件格式不支持！');
            }
        }
    }


    public function actionUploadSucc()
    {
        $this->renderPartial("upload_succ");
    }


    public function actionLoadSucc()
    {
        if (isset($_POST['submit'])) {
            $file = CUploadedFile::getInstanceByName('fileField');//获取上传的文件实例
            if ($file->getType() == 'application/vnd.ms-excel') {
                $arr = explode('.', $file->getName());
                $ext = $arr[count($arr) - 1];
                $excelFile = Yii::getPathOfAlias('excel_upload') . '/' . date("YmdHis") . mt_rand(1, 9999) . "." . $ext;
                $is = move_uploaded_file($file->getTempName(), $excelFile);
                if (!$is) exit('上传文件失败！');
                Yii::$enableIncludePath = false;
                Yii::import('application.extensions.PHPExcel.PHPExcel', 1);
                $excelReader = PHPExcel_IOFactory::createReader('Excel5');
                $phpexcel = $excelReader->load($excelFile)->getActiveSheet();//载入文件并获取第一个sheet       
                $total_line = $phpexcel->getHighestRow();
                $total_column = $phpexcel->getHighestColumn();
                $data = array();
                for ($row = 2; $row <= $total_line; $row++) {
                    for ($column = 'A'; $column <= $total_column; $column++) {
                        $data[$row][] = trim($phpexcel->getCell($column . $row)->getValue());
                    }
                }
                $str = "";
                foreach ($data as $k => $v) {
                    $str .= "update invite set state=1 mobile='" . $v[1] . "';";
                    $str .= "<br/>";
                }
                echo $str;
                @unlink($excelFile);
            } else {
                exit('上传文件格式不支持！');
            }
        }
    }


    /**
     * 抽奖
     */
    public function actionDoPaintPuzzle()
    {
        $this->checkLogin();
        if ($this->isover() || !$this->isstart()) {
            exit("activity error!");
        }
        $luckyOper = new LuckyOperation();
        $besideids = array(Item::LUCKY_THANKS_ID);
        if (isset($_POST["flag"]) && $_POST["flag"] == 'colourlife') {
            $result = $luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan, $this->_luckyTodayCan, $this->_username, $this->_userId, true, $besideids, $flag = true);
        } else {
            $result = $luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan, $this->_luckyTodayCan, $this->_username, $this->_userId, true, $besideids, $flag = false);
        }
        echo CJSON::encode($result);
    }


    /**
     * 汽车抽奖
     */
    public function actionDoLucky()
    {
        $this->checkLoginCar();
        if ($this->isover() || !$this->isstart()) {
            exit('活动异常');
        }
        $luckyOper = new LuckyOperationCar();
        $besideids = array(Item::LUCKY_THANKS_ID_CAR);
        // var_dump($this->_userId);die;
        $result = $luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan, $this->_luckyTodayCan, $this->_username, $this->_userId, true, $besideids);
        if ($result && $result ['success'] == 1) {
            //更改角度值，只取中间值
            $min = $result ['data'] ['result']['angle']['min'];
            $max = $result ['data'] ['result']['angle']['max'];
            $minArr = explode(",", $min);
            $maxArr = explode(",", $max);
            $index = 0;
            $index = rand(0, count($min) - 1);
            $minEnd = intval($minArr[$index]);
            $maxEnd = intval($maxArr[$index]);
            $result ['data'] ['result']['angle'] = intval(($minEnd + $maxEnd) / 2);
        }
        echo CJSON::encode($result);
    }

    /**
     * 新人有礼
     * @throws CException
     */
    public function actionXingrenyouli()
    {
        $this->checkLogin();
        $branchName = $this->_cust_model->community->branch->parent->parent;
        if (!empty($branchName) && trim($branchName->name) == '深圳事业部') {
            $this->renderPartial("xingrenyouli_szVersions", array(
                'cust_id' => $this->_userId,
            ));
        } else {
            $this->renderPartial("xingrenyouli_index", array(
                'cust_id' => $this->_userId,
            ));
        }

    }


    /*******************按一秒送红包活动***********************/

    /**
     * 活动首页
     * @throws CException
     */
    public function actionOneSecondIndex()
    {
        $this->checkLogin();
        if (date('Y-m-d H:i:s') >= '2015-08-16 23:59:59') {
            $this->renderPartial("onesecond/index", array(
                "luckyCustCan" => $this->_luckyCustCan,
                "luckyTodayCan" => $this->_luckyTodayCan,
                "custId" => $this->_userId,
            ));
        } else {
            exit('<h1>活动未开始，敬请期待</h1>');
        }
    }

    /**
     * 按一秒抽奖
     */
    public function actionOneSecondDo()
    {
        $this->checkLogin();
        if ($this->isover() || !$this->isstart()) {
            exit("activity error!");
        }

        $luckyOper = new LuckyOperation();
        $besideids = array(Item::LUCKY_ONE_SECOND_ID);
        $flag = (isset($_POST["flag"]) && $_POST["flag"] == 'colourlife') ? true : false;

        $prizeLevel = (isset($_POST["prize_level"]) && in_array($_POST["prize_level"], array('1', '2'))) ? $_POST["prize_level"] : '2';

        $result = $luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan, $this->_luckyTodayCan, $this->_username, $this->_userId, true, $besideids, $flag, $prizeLevel);

        echo CJSON::encode($result);
    }


    // //新注册送5元红包接口
    // public function actionDoSendRedPacket()
    // {   
    //     $this->checkLogin();
    //     if(date('Y-m')>='2015-04'&&date('Y-m')<='2015-05'){
    //         $model = Customer::model()->enabled()->findByPk($this->_userId);
    //         $date = date('Y-m-d H:i:s');
    //         $end_date =  strtotime($date."-30 second");
    //         //小于30秒时提示
    //         if ($model->create_time > $end_date){
    //             echo CJSON::encode ( 21 );
    //             return;
    //         }

    //         if ($model->name == '访客'){
    //             echo CJSON::encode ( 22 );
    //             return;
    //         }
    //         if (!isset($model)) {
    //             // throw new CHttpException(400, '用户不存在或被禁用!');
    //             echo CJSON::encode ( 0 );
    //         }else{
    //             if($model->community_id==585){
    //                 // throw new CHttpException(400, '活动用户不含体验区!');
    //                 echo CJSON::encode ( 1 );
    //             }else if(!in_array(date('m',$model->create_time),array('04','05'))){
    //                 // throw new CHttpException(400, '用户不是在活动时间内注册!');
    //                 // echo 1122;die;
    //                 echo CJSON::encode ( 2 );
    //             }else{

    //                 $result = RedPacket::model()->find('customer_id=:cust_id and sn=:sn and type=1',array(':cust_id'=>$this->_userId,':sn'=>'act_register'));
    //                 if($result){
    //                     echo CJSON::encode ( 6 );//红包已经领取过，不能重复领取
    //                 }else{
    //                     $items = array(
    //                         'customer_id' => $this->_userId,//业主的ID
    //                         'sum' => 5,//红包金额
    //                         'sn' => 'act_register',
    //                         'from_type' => Item::RED_PACKET_FROM_TYPE_NEW_CUSTOMER_REGISTER,
    //                     );
    //                     $redPacked = new RedPacket();
    //                     if(!$redPacked->addRedPacker($items)){
    //                         // throw new CHttpException(400, '红包领取失败!');
    //                         echo CJSON::encode ( 3 );
    //                     }
    //                     Yii::log("活动期间新注册的用户获得红包5元，用户ID[{$this->_userId}]", CLogger::LEVEL_INFO, 'colourlife.home.luckyApp.doSendRedPacket');
    //                     echo CJSON::encode ( 4 );
    //                 }

    //             }                
    //         }                
    //     }else{
    //         // throw new CHttpException(400, '活动失效');
    //         echo CJSON::encode ( 5 );
    //     }
    // }

    // //E维修代金劵
    // public function actionDoWeiXiuJuan()
    // {   
    //     $this->checkLogin();
    //     if(date('Y-m')=='2015-05'){
    //         $model = Customer::model()->enabled()->findByPk($this->_userId);
    //         if (!isset($model)) {
    //             // throw new CHttpException(400, '用户不存在或被禁用!');
    //             echo CJSON::encode ( 0 );
    //         }else{
    //             if($model->community_id==585){
    //                 // throw new CHttpException(400, '活动用户不含体验区!');
    //                 echo CJSON::encode ( 1 );
    //             }else if(!in_array(date('m',$model->create_time),array('04','05'))){
    //                 // throw new CHttpException(400, '用户不是在活动时间内注册!');
    //                 echo CJSON::encode ( 2 );
    //             }else{
    //                 if($model->is_lingqu_weixiu==1){
    //                     echo CJSON::encode ( 6 );//代金劵已经领取,不能重复领取
    //                 }else{
    //                     $url="http://m.eshifu.cn/business/sendcoupons?mobile=".$model->mobile;
    //                     $return = Yii::app()->curl->get($url);
    //                     $result = json_decode($return,true);
    //                     if($result["code"]==0&&empty($result["message"])){
    //                         Yii::log("活动期间新注册的用户获得20元E维修代金劵，用户ID[{$this->_userId}]", CLogger::LEVEL_INFO, 'colourlife.home.luckyApp.doWeiXiuJuan');
    //                         if (!Customer::model()->updateByPk($model->id, array('is_lingqu_weixiu'=>1))) {
    //                             echo CJSON::encode ( 9 );//领取失败
    //                         }
    //                         echo CJSON::encode ( 4 );//领取成功
    //                     }else if($result["code"]==-1&&$result["message"]=='无效的用户手机号码'){
    //                         echo CJSON::encode ( 3 );//无效的用户手机号码
    //                     }else if($result["code"]==-1&&$result["message"]=='数据操作异常'){
    //                         echo CJSON::encode ( 7 );//数据操作异常
    //                     }else if($result["code"]==-1&&$result["message"]=='代金券发放时间已过期'){
    //                         echo CJSON::encode ( 8 );//代金券发放时间已过期
    //                     }else{
    //                         echo CJSON::encode ( 9 );//领取失败
    //                     }
    //                 }

    //             }                
    //         }                
    //     }else{
    //         // throw new CHttpException(400, '活动失效');
    //         echo CJSON::encode ( 5 );
    //     }
    // }


    public function getListData()
    {

        $listData = array();
        $listData[0] = '恭喜棕榈堡业主王**获得了8.8元红包';
        $listData[1] = '恭喜鸿运嘉园业主吴**获得了88元红包';
        $listData[2] = '恭喜左邻右舍业主詹**获得了18元红包';
        $listData[3] = '恭喜百合花园业主刘**获得了8.8元红包';
        $listData[4] = '恭喜晶地顺苑业主李**获得了0.18元红包';
        $listData[5] = '恭喜锦绣华庭业主张**获得了18元红包';
        $listData[6] = '恭喜联谊广场业主董**获得了18元红包';
        $listData[7] = '恭喜望海新都业主梁**获得了8.8元红包';
        $listData[8] = '恭喜山水田园业主蔡**获得了1.8元红包';
        $listData[9] = '恭喜正喆花园业主高**获得了8.8元红包';
        $listData[10] = '恭喜正喆花园业主高**获得了8.8元红包';
        $listData[11] = '恭喜未来城业主唐**获得了1.8元红包';
        $listData[12] = '恭喜香树丽舍业主喻**获得了18元红包';
        $listData[13] = '恭喜聚缘北庭业主黄**获得了88元红包';
        $listData[14] = '恭喜九州假日业主谢**获得了1.8元红包';
        $listData[15] = '恭喜山景天下业主周**获得了8.8元红包';
        $listData[16] = '恭喜恒达花园业主杨**获得了1.8元红包';
        $listData[17] = '恭喜七星花园业主龙**获得了8.8元红包';
        $listData[18] = '恭喜逸仙名居业主宁**获得了0.8元红包';
        $listData[19] = '恭喜金色比华利业主贺**获得了8.8元红包';
        $conditon = "lucky_act_id=" . $this->_luckyActId . " order by id desc limit 20";
        $dataResult = LuckyCustResult::model()->findAll($conditon);
        $list = array();
        if (count($dataResult) < 8) {
            $list[] = array("msg" => $listData[0]);
            $list[] = array("msg" => $listData[7]);
            $list[] = array("msg" => $listData[3]);
            $list[] = array("msg" => $listData[8]);
        }
        for ($i = 0; $i < count($dataResult); $i++) {
            $name = empty($dataResult[$i]['receive_name']) ? ("") : (F::msubstr($dataResult[$i]['receive_name'], 0, 1) . "**");

            if ($dataResult[$i]['isred']) {
                $list[] = array(
                    'msg' => "恭喜" . $dataResult[$i]['public_info'] . "业主" . $name . "获得了" . $dataResult[$i]['rednum'] . "元红包",
                );
            } else {
                $list[] = array(
                    'msg' => "恭喜" . $dataResult[$i]['public_info'] . "业主" . $name . "获得了" . $dataResult[$i]->prize->prize_name,
                );
            }

            if ($i % 3 == 0) {
                $list[] = array("msg" => $listData[rand(0, 19)]);
            }
        }
        return $list;
    }


    public function actionDoShakeLucky()
    {
        $this->checkLogin();
        if ($this->isover()) {
            exit();
        }
        $luckyOper = new LuckyOperation();
        $besideids = array(Item::LUCKY_THANKS_ID);
        $result = $luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan, $this->_luckyTodayCan, $this->_username, $this->_userId, true, $besideids);
        echo CJSON::encode($result);
    }


    public function actionDoShakeLuckyNew()
    {
        $this->checkLogin();
        if ($this->isover()) {
            exit();
        }

        $luckyOper = new LuckyOperation();
        $besideids = array(Item::LUCKY_THANKS_ID);
        $result = $luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan, $this->_luckyTodayCan, $this->_username, $this->_userId, true, $besideids);
        echo CJSON::encode($result);
    }


    public function actionMylottery()
    {
        $this->checkLogin();
        $list = LuckyCustResult::model()->findAll("cust_id=" . $this->_userId . " and lucky_act_id in (" . Item::LUCKY_ACT_ID . "-2," . Item::LUCKY_ACT_ID . "-1," . Item::LUCKY_ACT_ID . ") and prize_id!=" . Item::LUCKY_THANKS_ID . " order by id desc");
        $this->renderPartial("lotterylist",
            array("list" => $list)
        );
    }


    public function actionLotteryrule()
    {
        $this->checkLogin();
        $this->renderPartial("throughRule");
    }

    public function actionShakeRule()
    {
        $this->checkLogin();
        $this->renderPartial("shake_rule");
    }


    public function actionGuaguaRule()
    {
        $this->checkLogin();
        $this->renderPartial("guagua_rule");
    }


    public function actionHeimei_shuoming()
    {
        $this->checkLogin();
        $this->renderPartial("heimei_shuoming");
    }


    public function actionShakeRule5000()
    {
        $this->checkLogin();
        $this->renderPartial("shake_rule5000");
    }

    public function actionTaikanglingqu()
    {
        $this->checkLogin();
        if (isset($_GET['id'])) {
            $this->renderPartial("taikang_lingqu", array('lucky_result_id' => $_GET['id']));
        } else {
            $this->renderPartial("taikang_lingqu");
        }

    }


    public function actionChristmasResult()
    {
        $this->checkLogin();
        $listResutlist = $this->getListData();
        $list = LuckyCustResult::model()->findAll("cust_id=" . $this->_userId . " and lucky_act_id in (" . Item::LUCKY_ACT_ID . "-2," . Item::LUCKY_ACT_ID . "-1," . Item::LUCKY_ACT_ID . ") and prize_id!=" . Item::LUCKY_THANKS_ID . " order by id desc");
        $this->renderPartial("christmas_result", array("listResutlist" => $listResutlist, "list" => $list));
    }


    public function actionHappinessResult()
    {
        $this->checkLogin();
        $allJoin = LuckyCustomerOut::model()->count();
        $listResutlist = $this->getListData();
        $list = LuckyCustResult::model()->findAll("cust_id=" . $this->_userId . " and lucky_act_id in (" . Item::LUCKY_ACT_ID . "-2," . Item::LUCKY_ACT_ID . "-1," . Item::LUCKY_ACT_ID . ") and prize_id!=" . Item::LUCKY_THANKS_ID . " order by id desc");
        $this->renderPartial("happiness_result", array(
            "listResutlist" => $listResutlist,
            "list" => $list,
            "allJoin" => $allJoin + 123,
        ));
    }


    public function actionHappinessSnh()
    {
        $this->checkLogin();
        $this->renderPartial("happiness_snh");
    }

    /*
     * 专题 by 20150306
     */
    public function actionSpecialTopic()
    {
        $this->checkLogin();
        $act = (int)Yii::app()->request->getParam('act');
        if ($act) {
            if ($act == 1) $act = 1; else $act = 2;
            $this->renderPartial("special_topic_{$act}");
        } else {
            $customer = Customer::model()->findByPk($this->_userId);
            if ($customer) {
                $community_id = $customer->community_id;
            } else $community_id = '';
            $community_array = array(31, 81, 13, 1, 2, 6, 72, 75);
            $community_array = array_flip($community_array);
            if (!array_key_exists($community_id, $community_array)) {
                echo '此区还没有开通';
                exit;
            }
            $_mModel = new SetableSmallLoans();//echo $this->_userId;exit;
            $re = $_mModel->searchByIdAndType('market', 1, $this->_userId, false);//var_dump($re);
            if ($re) {
                $url = $re->completeURL;
            } else $url = 'error';// echo $url;
            $this->renderPartial("special_topic", array('url' => $url, 'userid' => $this->_userId, 'community_id' => $customer->community_id));
        }
    }


    public function actionHappinessRule()
    {
        $this->checkLogin();
        $this->renderPartial("happiness_rule");
    }


    public function actionChristmasRule()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_rule");
    }


    public function actionChristmasShuoMingHuameida()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_huameida");
    }


    public function actionChristmasShuoMingFengchidao()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_fengchidao");
    }


    public function actionChristmasShuoMingHaoyahotel()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_hao_ya");
    }


    public function actionChristmasShuoMingLizigongguan()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_lizhi");
    }


    public function actionChristmasShuoMingFlyvilla()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_luofushang");
    }


    public function actionChristmasShuoMingWonderland()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_qing_feng");
    }


    public function actionChristmasShuoMingQuyuan()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_quyuan");
    }


    public function actionChristmasShuoMingTaihutiancheng()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_tai_hu");
    }


    public function actionChristmasShuoMingHailingdao()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_yi_jing");
    }

    public function actionChristmasShuoMingSanjiaozhou()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_shanjiaozhou");
    }


    public function actionChristmasRuleHuameida()
    {
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_huameida");
    }


    public function actionChristmasRuleFengchidao()
    {
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_fengchidao");
    }


    public function actionChristmasRuleHaoyahotel()
    {
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_haoya");
    }


    public function actionChristmasRuleLizigongguan()
    {
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_lizhi");
    }


    public function actionChristmasRuleFlyvilla()
    {
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_luofushang");
    }


    public function actionChristmasRuleWonderland()
    {
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_qingfeng");
    }


    public function actionChristmasRuleQuyuan()
    {
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_quyuan");
    }


    public function actionChristmasRuleTaihutiancheng()
    {
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_taihu");
    }

    public function actionChristmasRuleHailingdao()
    {
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_yijing");
    }

    public function actionChristmasRuleSanjiaozhou()
    {
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_xunliaowan");
    }


    public function actionChristmasRuleHeiMeiJiu()
    {
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_wine");
    }


    public function actionChristmasRule5000()
    {
        $this->checkLogin();
        $this->renderPartial("christmas_shuoming_5000");
    }


    public function actionIntroduce()
    {
        // $this->checkLogin();
        // if(time()>='1420041599'){
        //     $this->renderPartial("introduceNew");
        // }else{
        //     $this->renderPartial("introduce"); 
        // }
        $this->checkLogin();
        $this->renderPartial("happiness_introduce");
    }


    public function actionGuaguaResult()
    {

        $this->checkLogin();
        $listResutl = $this->getListData();
        $list = LuckyCustResult::model()->findAll("cust_id=" . $this->_userId . " and lucky_act_id in (" . Item::LUCKY_ACT_ID . "-4," . Item::LUCKY_ACT_ID . "-3," . Item::LUCKY_ACT_ID . "-2," . Item::LUCKY_ACT_ID . "-1," . Item::LUCKY_ACT_ID . ") and prize_id!=" . Item::LUCKY_THANKS_ID . " order by id desc");
        // var_dump($list);die;
        $this->renderPartial("guagua_result", array("listResutl" => $listResutl, "list" => $list));
    }


    public function actionShakeResult()
    {
        $this->checkLogin();
        $listResutl = $this->getListData();
        $list = LuckyCustResult::model()->findAll("cust_id=" . $this->_userId . " and lucky_act_id in (" . Item::LUCKY_ACT_ID . "-3," . Item::LUCKY_ACT_ID . "-2," . Item::LUCKY_ACT_ID . "-1," . Item::LUCKY_ACT_ID . ") and prize_id!=" . Item::LUCKY_THANKS_ID . " order by id desc");
        $this->renderPartial("shake_result", array("listResutl" => $listResutl, "list" => $list));
    }


    public function actionDajiangshuoming()
    {
        $this->checkLogin();
        $this->renderPartial("dajiangshuoming");
    }

    public function actionShuoming()
    {
        $this->checkLogin();
        $this->renderPartial("shuoming");
    }

    public function actionHowgethb()
    {
        $this->checkLogin();
        $this->renderPartial("howgethb");
    }

    public function actionHowgetit()
    {
        $this->checkLogin();
        $this->renderPartial("howgetit");
    }

    public function actionHowuse()
    {
        $this->checkLogin();
        $this->renderPartial("howuse");
    }

    public function actionBieyangcheng()
    {
        $this->checkLogin();
        $this->renderPartial("bieyangcheng");
    }

    //水果特供
    public function actionFruitGet()
    {
        $this->checkLogin();
        $this->renderPartial("fruit");
    }


    /**
     * 抽奖操作
     */
    private function luckyOperation()
    {
    }

    /**
     * 测试 产生抽奖机会
     */
    public function actionTest()
    {
// 		// 测试订单产生抽奖机会
// 		$luckyOper = new LuckyOperation ();
// 		$orderId = 365;
// 		$result = $luckyOper->custGetLuckyNum ( $this->_username, $this->_userId, false, $this->_luckyActId );

// 		var_dump ( $result );
// 		exit ();
        //$result=PayLib::order_paid('2030555130708195907788',1);
// 		$result=PayLib::order_paid('2030555130708210207920',1);  //此单号的商品，加入到“幸福中国行”活动商品
// 		$customer_id=Yii::app()->user->isGuest ?  0 : Yii::app()->user->id;
// 		var_dump($customer_id);
    }


    //获取业主地址、手机号、名字
    public function actionGetCustomerInfo()
    {
        $this->checkLogin();
        $res = array();
        $customerModel = Customer::model()->findByPk($this->_userId);
        if ($customerModel) {
            $res['tel'] = $customerModel->mobile;
            $res['name'] = $customerModel->name;
            $res['email'] = $customerModel->email;
            $res['address'] = $customerModel->community->name . $customerModel->build->name . $customerModel->room;
        }
        echo CJSON::encode($res);
    }


    //确定收月饼地址
    public function actionFillReceiving()
    {
        $this->checkLogin();
        //$id = $_POST['id'];
        $id = intval($_POST['id']);
        $linkman = $_POST['linkman'];
        $tel = $_POST['tel'];
        if ($this->validatephone($tel)) {
            $criteria = new CDbCriteria;
            //$criteria->addCondition("id=" . $id);
           // $criteria->addCondition("customer_id=" . $this->_userId);
           $criteria->compare('id', $id);
           $criteria->compare('customer_id', $this->_userId);
            $model = MoonCakesResult::model()->find($criteria);
            $model->linkman = $linkman;
            $model->tel = $tel;
            $model->address = $model->CustomerAddress;
            $model->save();
            echo CJSON::encode(1);
        } else {
            echo CJSON::encode(0);
        }
    }


    //确定收月饼地址
    public function actionFruit_FillReceiving()
    {
        $this->checkLogin();
        $id = $_POST['id'];
        $linkman = $_POST['linkman'];
        $tel = $_POST['tel'];
        if ($this->validatephone($tel)) {
            $criteria = new CDbCriteria;
            /* $criteria->addCondition("id=" . $id);
            $criteria->addCondition("cust_id=" . $this->_userId); */
            $criteria->compare('id', intval($id));
            $criteria->compare('cust_id', $this->_userId);
            $model = LuckyCustResult::model()->find($criteria);
            $model->receive_name = $linkman;
            $model->moblie = $tel;
            $model->address = $model->CustomerAddress;
            $model->save();
            echo CJSON::encode(1);
        } else {
            echo CJSON::encode(0);
        }
    }


    /**
     * 获取最新中奖用户
     */
    public function actionGetUserNewListJson()
    {
        $this->checkLogin();
        $result = array("success" => 1, 'data' => array('msg' => '系统错误'));
        //倒叙查询最近N条记录
        $conditon = "lucky_act_id=" . $this->_luckyActId . " AND prize_id!=" . Item::LUCKY_THANKS_ID . " AND (isred=0 OR (isred=1 AND rednum>10 AND rednum<500)) order by id desc limit 7";
        $data = LuckyCustResult::model()->findAll($conditon);
        $list = array();
        $listJia = array(
            array('msg' => '恭喜碧水龙庭业主谢**获得了5.18元红包'),
            array('msg' => '恭喜南国丽园业主王**获得了8.88元红包'),
            array('msg' => '恭喜金桔苑业主卢**获得了0.18元红包'),
            array('msg' => '恭喜景尚雅苑业主雷**获得了1.68元红包'),
        );
        foreach ($data as $value) {
            $name = empty($value['receive_name']) ? ("") : (F::msubstr($value['receive_name'], 0, 1) . "**");
            if ($value['isred'] == 1) {
                $list[] = array(
                    'msg' => "恭喜" . $value['public_info'] . "业主" . $name . "获得了" . $value['rednum'] . "元红包",
                );
            } else {
                $list[] = array(
                    'msg' => "恭喜" . $value['public_info'] . "业主" . $name . "获得了" . $value->prize->prize_name,
                );
            }
        }
        if (count($list) > 0) {
            if (count($list) < 6) {  //少于6条，拼加假数据
                $list = array_merge($list, $listJia);
            }
            $result = array("success" => 1, 'data' => array('list' => $list));
        } else {
            $result = array("success" => 1, 'data' => array('list' => $listJia));
        }
        echo CJSON::encode($result);
    }

    /**
     * 获得再来一次
     */
    public function actionGetAgain()
    {
        $this->checkLogin();
        $luckyOper = new LuckyOperation();
        $ret = $luckyOper->getAgainChance($this->_userId, $this->_username);
        echo json_encode($ret);
    }


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
            //$this->_luckyActId = isset ($_REQUEST ['actid']) ? ($_REQUEST ['actid']) : Item::LUCKY_ACT_ID;
            $this->_luckyActId = isset ($_REQUEST ['actid']) ? (intval($_REQUEST ['actid'])) : Item::LUCKY_ACT_ID;
            $this->_userId = $custId;
            $this->_username = $customer->username;
            $this->_cust_model = $customer;
            $luckyNum = 0;
            $luckyCan = new LuckyCustCan ();
            $result = $luckyCan->getCustCan($this->_username, $this->_userId, $this->_luckyActId);
            if ($result) {
                $this->_luckyCustCan = $result['cust_can'] < 0 ? 0 : $result ['cust_can'];
                $this->_luckyTodayCan = $result['cust_today_can'] < 0 ? 0 : $result['cust_today_can'];
            }
        }
    }


    private function checkLoginCar()
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
            //$this->_luckyActId = isset ($_REQUEST ['actid']) ? ($_REQUEST ['actid']) : Item::LUCKY_ACT_ID_CAR;
            $this->_luckyActId = isset ($_REQUEST ['actid']) ? (intval($_REQUEST ['actid'])) : Item::LUCKY_ACT_ID_CAR;
            $this->_userId = $custId;
            $this->_username = $customer->username;
            $this->_cust_model = $customer;
        }
    }


    public function actionColourRule()
    {
        $this->checkLogin();
        $this->renderPartial("colourRule");
    }

    //记录中了电信充值卡
    public function actionTelecom()
    {
        $this->checkLogin();
        $mobile = $_POST['mobile'];
        if (Telecom::model()->checkMobile($mobile)) {
            $criteria = new CDbCriteria;
            //$criteria->addCondition("customer_id=" . $this->_userId);
            $criteria->compare('customer_id', $this->_userId);
            $criteria->order = "id desc";
            $telecom = Telecom::model()->find($criteria);
            $telecom->mobile = $mobile;
            if ($telecom->save()) {
                echo CJSON::encode(1);    //成功
            } else {
                echo CJSON::encode(0);    //失败
            }
        } else {
            echo CJSON::encode(0);    //失败
        }
    }

    //更改充值卡号码
    public function actionUpdateTelecom()
    {
        $lucky_cust_result_id = $_POST['lucky_cust_result_id'];
        $mobile = $_POST['mobile'];
        if (Telecom::model()->checkMobile($mobile)) {
        	$lucky_cust_result_id=intval($lucky_cust_result_id);
            $telecom = Telecom::model()->find('lucky_cust_result_id=' . $lucky_cust_result_id);
            $telecom->mobile = $mobile;
            if ($telecom->save()) {
                echo CJSON::encode(1);    //成功
            } else {
                echo CJSON::encode(0);    //失败
            }
        } else {
            echo CJSON::encode(0);    //失败
        }
    }

    //验证身份证号码
    public function actionCheckIdentity()
    {
        $this->checkLogin();
        $code = $_POST['identity'];
        $result = CheckIdentity::checkCode($code);
        if ($result) {
            $model = TaikangLife::model()->find("identity='" . $code . "'");
            if ($model) {
                //数据库有重复
                echo CJSON::encode(array('pass' => 1));
            } else {
                echo CJSON::encode(array('pass' => 2));
            }
        } else {
            //身份证格式不正确
            echo CJSON::encode(array('pass' => 0));
        }

    }


    //验证手机号码
    public function actionCheckExistMobile()
    {
        $this->checkLogin();
        $mobilephone = $_POST['mobile'];
        if (strlen(trim($mobilephone)) != 11) {
            echo CJSON::encode(array('pass' => 0));    //失败
        }
        if (preg_match("/^1(3|4|5|7|8){1}\d{9}$/", $mobilephone)) {
            //验证通过
            $model = TaikangLife::model()->find("mobile='" . $mobilephone . "'");
            if ($model) {
                //数据库有重复
                echo CJSON::encode(array('pass' => 1));
            } else {
                echo CJSON::encode(array('pass' => 2));
            }

        } else {
            //手机号码格式不对
            echo CJSON::encode(array('pass' => 0));
        }
    }


    public function actionDoTaiKang_Life()
    {
        $this->checkLogin();
        $type = $_POST['type'];
        $name = $_POST['name'];
        $identity = $_POST['identity'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];

        $criteria = new CDbCriteria;
        if (isset($_POST['lucky_result_id'])) {
            //$criteria->addCondition(" lucky_result_id=" . $_POST['lucky_result_id'] . " and customer_id=" . $this->_userId);
        	$criteria->compare('lucky_result_id', intval($_POST['lucky_result_id']));
        	$criteria->compare('customer_id', $this->_userId);
        } else {
            //$criteria->addCondition("customer_id=" . $this->_userId);
            $criteria->compare('customer_id', $this->_userId);
            $criteria->order = "id desc";
        }

        $model = TaikangLife::model()->find($criteria);
        $model->name = $name;
        $model->identity = $identity;
        $model->mobile = $mobile;
        $model->email = $email;
        $model->type = $type;
        $model->save();
        echo CJSON::encode(array('pass' => 1));


    }


    //注册送保险
    public function actionDoTaiKang_LifeNew()
    {
        $this->checkLogin();
        $type = $_POST['type'];
        $name = $_POST['name'];
        $identity = $_POST['identity'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];

        $model = new TaikangLife;
        $model->customer_id = $this->_userId;
        $model->name = $name;
        $model->identity = $identity;
        $model->mobile = $mobile;
        $model->email = $email;
        $model->type = $type;
        $model->create_time = time();
        $model->save();
        echo CJSON::encode(array('pass' => 1));
    }


    //验证手机号码
    private function validatephone($mobilephone)
    {
        if (strlen(trim($mobilephone)) != 11) {
            return false;
        }
        if (preg_match("/^13[0-9]{1}\d{8}|15[0-9]{1}\d{8}|18[0-9]{1}\d{8}$/", $mobilephone)) {   //1(3|4|5|7|8){1}\d{9}
            //验证通过
            return true;
        } else {
            //手机号码格式不对
            return false;
        }
    }


    //世界杯首页
    public function actionWorldCupIndex()
    {
        $this->renderPartial('worldCupIndex');
    }

    //世界杯猜胜负
    public function actionGuessOutcome()
    {
        $this->checkLogin();
        $encounters = Encounter::model()->getAllEncounterAtNow();
        $arr_outcome = array();
        //获取用户已经选择的记录
        foreach ($encounters as $key => $encounter) {
            $model = CustomerOutcome::model()->find('encounter_id=:encounter_id and customer_id=:customer_id',
                array(':encounter_id' => $encounter->id, ':customer_id' => $this->_userId));
            if ($model) {
                $arr_outcome[$key]['encounter_id'] = $encounter->id;
                $arr_outcome[$key]['myoutcome'] = $model->myoutcome;
            } else {
                $arr_outcome[$key]['encounter_id'] = $encounter->id;
                $arr_outcome[$key]['myoutcome'] = "";
            }
        }
        //var_dump($arr_outcome);
        $customerStatistics = CustomerOutcome::model()->getCustomerStatistics($this->_userId);
        $this->renderPartial("guessOutcome",
            array(
                'encounters' => $encounters,
                'customerStatistics' => $customerStatistics,
                'arr_outcome' => $arr_outcome,
            ));
    }

    //业主修改胜负
    public function actionUpdateOutcome()
    {
        $this->checkLogin();
        $encounters = Encounter::model()->getAllEncounterAtNow();
        $customerStatistics = CustomerOutcome::model()->getCustomerStatistics($this->_userId);
        $arr_outcome = array();
        //获取用户已经选择的记录
        foreach ($encounters as $key => $encounter) {
            $model = CustomerOutcome::model()->find('encounter_id=:encounter_id and customer_id=:customer_id',
                array(':encounter_id' => $encounter->id, ':customer_id' => $this->_userId));
            if ($model) {
                $arr_outcome[$key]['encounter_id'] = $encounter->id;
                $arr_outcome[$key]['myoutcome'] = $model->myoutcome;
            } else {
                $arr_outcome[$key]['encounter_id'] = $encounter->id;
                $arr_outcome[$key]['myoutcome'] = "";
            }
        }
        $this->renderPartial("updateOutcome",
            array(
                'encounters' => $encounters,
                'customerStatistics' => $customerStatistics,
                'arr_outcome' => $arr_outcome,
            ));
    }


    private function checkLoginEx()
    {
        if (empty($_REQUEST['employee_id']) && empty(Yii::app()->session['employee_id'])) {
            exit('<h1>员工信息错误，请重新登录</h1>');
        } else {
            $employeeId = 0;
            ini_set('session.gc_maxlifetime', 3600 * 12); //设置时间
            if (isset($_REQUEST['employee_id'])) {  //优先有参数的
                $employeeId = intval($_REQUEST['employee_id']);
                Yii::app()->session['employee_id'] = $employeeId;
            } else if (isset(Yii::app()->session['employee_id'])) {  //没有参数，从session中判断
                $employeeId = Yii::app()->session['employee_id'];
            }
            $employee = Employee::model()->findByPk($employeeId);
            if (empty($employeeId) || empty($employee) || $employee->state == 1 || $employee->is_deleted == 1) {
                exit('<h1>员工信息错误，请重新登录</h1>');
            }

            $this->_dreamActId = isset ($_REQUEST ['actid']) ? ($_REQUEST ['actid']) : Item::DREAM_ACT_ID;
            if ($this->_dreamActId != Item::DREAM_ACT_ID) {
                exit('<h1>投票程序出错，请联系管理员</h1>');
            }
            $activity = DreamActivity::model()->findByPk($this->_dreamActId);
            if (empty($activity) || ($activity && $activity->isdelete == 1)) {
                exit('<h4>活动异常</h4>');
            }
            $this->_userId = $employeeId;
            $this->_userIP = $employee->last_ip;
        }
    }


    public function actionMyoutcome()
    {
        $this->checkLogin();
        $encounter_game = $_POST['encounter_game'];
        $my_outcome = $_POST['my_outcome'];
        //查看活动是否存在
        $encounter = Encounter::model()->findByPk($encounter_game);
        if (!$encounter) {
            echo CJSON::encode(array('code' => 2));    //该场比赛不存在
            exit();
        }
        if ($encounter->end_quiz < date("Y-m-d H:i:s")) {
            echo CJSON::encode(array('code' => 3));   //已经过了竞猜时间
            exit();
        }
        $model = CustomerOutcome::model()->find('encounter_id=:encounter_id and customer_id=:customer_id',
            array(':encounter_id' => $encounter_game, ':customer_id' => $this->_userId));
        if (!$model) {
            $model = new CustomerOutcome("create");
            $model->encounter_id = $encounter_game;
        } else {
            $model->setScenario("update");
        }
        $model->create_time = time();
        $model->customer_id = $this->_userId;
        $model->myoutcome = $my_outcome;
        $model->customer_ip = Yii::app()->getRequest()->getUserHostAddress();
        if ($model->save()) {
            echo CJSON::encode(array('code' => 1));    //成功
        } else {
            echo CJSON::encode(array('code' => 0));    //失败
        }
    }


    //查看业主猜胜负结果
    public function actionLookResult()
    {
        $this->checkLogin();
        $customerStatistics = CustomerOutcome::model()->getCustomerStatistics($this->_userId);   //业主猜中的次数
        $customerTotal = CustomerOutcome::model()->getCustomerTotal($this->_userId);             //业主竞猜总数
        $customerTotalRecord = CustomerOutcome::model()->getCustomerTotalRecord($this->_userId);  //业主竞猜所有的记录
        //计算用户用户能中什么多少元红包
        if ($customerStatistics >= 0 && $customerStatistics < 5) {
            $redPacket = 2;
            $lack = 5 - $customerStatistics;
        } else if ($customerStatistics >= 5 && $customerStatistics < 10) {
            $redPacket = 5;
            $lack = 10 - $customerStatistics;
        } else if ($customerStatistics >= 10 && $customerStatistics < 20) {
            $redPacket = 58;
            $lack = 20 - $customerStatistics;
        } else if ($customerStatistics >= 20 && $customerStatistics < 32) {
            $redPacket = 288;
            $lack = 32 - $customerStatistics;
        } else if ($customerStatistics >= 32 && $customerStatistics <= 64) {
            $redPacket = 588;
            $lack = 64 - $customerStatistics;
        }
        $this->renderPartial("lookResult", array(
            'customerStatistics' => $customerStatistics,
            'customerTotal' => $customerTotal,
            'customerTotalRecord' => $customerTotalRecord,
            'redPacket' => $redPacket,
            'lack' => $lack,
        ));
    }

    public function actionWorldCupRule()
    {
//        $goucai = SmallLoans::model()->searchByIdAndType("GOUCAI",1);
//        var_dump($res->completeURL);exit();
        $this->renderPartial("worldCupRule");
    }


    //介绍荔枝
    public function actionLizhi()
    {
        $this->renderPartial("lizhi");
    }

    //猜胜负竞猜规则
    public function actionWorldRuleOne()
    {
        $this->renderPartial("worldRuleOne");
    }

    //查看世界杯结果
    public function actionLookAllResult()
    {
        $this->checkLogin();
        $customerStatistics = CustomerOutcome::model()->getCustomerStatistics($this->_userId);   //业主猜中的次数
        $customerTotal = CustomerOutcome::model()->getCustomerTotal($this->_userId);             //业主竞猜总数
        if (empty($_GET)) {
            $all = "all";
            $customerTotalRecord = CustomerOutcome::model()->getCustomerRecodeByPage($this->_userId);  //业主竞猜所有的记录
        } else {
            $all = "";
            $customerTotalRecord = CustomerOutcome::model()->getCustomerTotalRecord($this->_userId);  //业主竞猜所有的记录
        }
        //计算用户用户能中什么多少元红包
        if ($customerStatistics >= 0 && $customerStatistics < 5) {
            $redPacket = 2;
            $lack = 5 - $customerStatistics;
        } else if ($customerStatistics >= 5 && $customerStatistics < 10) {
            $redPacket = 5;
            $lack = 10 - $customerStatistics;
        } else if ($customerStatistics >= 10 && $customerStatistics < 20) {
            $redPacket = 58;
            $lack = 20 - $customerStatistics;
        } else if ($customerStatistics >= 20 && $customerStatistics < 32) {
            $redPacket = 288;
            $lack = 32 - $customerStatistics;
        } else if ($customerStatistics >= 32 && $customerStatistics <= 64) {
            $redPacket = 588;
            $lack = 64 - $customerStatistics;
        }
        //晋级
        $promotionList = array();
        $params = array(
            'condition' => 'customer_id=:id AND teams_promotion_id<5',
            'params' => array(':id' => $this->_userId),
            'order' => 'teams_promotion_id desc',
        );
        $findAll = CustomerPromotion::model()->findAll($params);
        if ($findAll) {
            foreach ($findAll as $value) {
                $promotionList[$value['teams_promotion_id']] = $value;
            }
        }
        //王者
        $winnerList = CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid", array(':id' => $this->_userId, ':pid' => 5));
        if (!$winnerList)
            $winnerList = array();

        $this->renderPartial("lookAllResult",
            array('customerStatistics' => $customerStatistics,
                'customerTotal' => $customerTotal,
                'redPacket' => $redPacket,
                'lack' => $lack,
                'customerTotalRecord' => $customerTotalRecord,
                'promotionList' => $promotionList,
                'winnerList' => $winnerList,
                'all' => $all,
            ));
    }


    /**
     * 获取Oa组织架构更新的记录插入中间表
     */
    public function actionUpdateBranch()
    {
        $pageIndex = 1;
        $pageSize = 50;
        $uptime = $_GET["date"];
        while (true) {
            BranchMiddle::model()->insertByUpdate($uptime, $pageSize, $pageIndex);
            ++$pageIndex;
        }
    }


    /**
     * 获取Oa组织架构删除的记录更新中间表
     */
    public function actionDeleteBranch()
    {
        $pageIndex = 1;
        $pageSize = 50;
        $uptime = $_GET["date"];
        while (true) {
            BranchMiddle::model()->deleteByUpdate($uptime, $pageSize, $pageIndex);
            ++$pageIndex;
        }
    }

    public function actionInserEmployeeByUpdate()
    {
        $pageIndex = 1;
        $pageSize = 50;
        $uptime = "";
        $username = $_GET["name"];
        while (true) {
            EmployeeMiddle::model()->recInsertByUpdate($uptime, $pageSize, $pageIndex, $username);
            ++$pageIndex;
        }
    }


    //回调彩之云的去修改订单
    private function setAdvanceSavefee()
    {
        OthersAdvanceFees::model()->SetAdvanceSavefee($this->order_id, Item::FEES_TRANSACTION_SUCCESS, '预缴费支付成功');
    }


    private function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


    /**
     * 获取Oa组织架构所有记录插入中间表
     */
    public function actionInsertBranch()
    {
        $pageIndex = 1;
        $pageSize = 80;
        while (true) {
            BranchMiddle::model()->recInsert($pageSize, $pageIndex);
            ++$pageIndex;
        }
    }


    /**
     * 获取Oa组织架构删除的记录更新中间表
     */
    public function actionAllDeleteBranch()
    {
        $pageIndex = 1;
        $pageSize = 50;
        $uptime = "";
        while (true) {
            BranchMiddle::model()->deleteByUpdate($uptime, $pageSize, $pageIndex);
            ++$pageIndex;
        }
    }


    public function actionInsertEmployee($pageIndex = 1)
    {
        $pageSize = 80;
        while (true) {
            EmployeeMiddle::model()->recInsert($pageSize, $pageIndex);
            ++$pageIndex;
        }
    }


    public function actionClearPayPwd()
    {
        if (!isset($_GET) || !isset($_GET['oa'])) exit('参数错误');
        $e = Employee::model()->find("is_deleted=0 and state=0 and username='" . $_GET['oa'] . "'");
        if (!$e) exit('查询出错');
        if ($e->pay_password == '') exit('账号支付密码已经清空过了');
        if (!Employee::model()->updateByPk($e->id, array('pay_password' => ''))) {
            echo '清空oa账号为【' . $_GET['oa'] . '】的支付密码失败';
            die;
        } else {
            echo '成功清空oa账号为【' . $_GET['oa'] . '】的支付密码';
            die;
        }
    }


    public function actionTestLaoLu()
    {
        // $this->renderPartial("test");
        // echo date('Y-m-d',strtotime("+3 month"));
    }


    public function actionUpNew()
    {
        // $sql = " DROP TABLE IF EXISTS `invitation_code`;
        //       CREATE TABLE IF NOT EXISTS `invitation_code` (
        //                 `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
        //                 `code` char(5) NOT NULL COMMENT '邀请码',
        //                 PRIMARY KEY (`id`),
        //                 UNIQUE KEY `code` (`code`)
        //       ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        // $this->execute($sql);
        $varss = $_GET["code"];
        $id = $_GET["id"];
        $sid = $_GET["sid"];
        $i = 1;
        $code = '';
        while ($i <= 1100) {
            $code = F::random(7, 1);
            // $code = strtoupper($code);
            $count = LuckyEntity::model()->find("code='" . $varss . $code . "'");
            if ($count) {
                continue;
            }
            // $sql2 = " INSERT INTO `invitation_code` (`id`, `code`) VALUES (NULL, '".$code."');";
            // echo $i."\r\n";
            // $this->execute($sql2);

            $invitationcode = new LuckyEntity();
            $invitationcode->prize_id = $id;
            $invitationcode->code = $varss . $code;
            $invitationcode->shop_id = $sid;
            $invitationcode->save();
            $i++;
        }

        echo $i;
    }

    /*
     *
     * 双十一活动页面(入口页面)
     *活动时间：11月9号——11月16号
     */
    public function actionSSYView()
    {
      $cust_id=Yii::app()->request->getParam('cust_id');
        $this->renderPartial("index4",array('cust_id'=>$cust_id));
    }


    /*
     *
     * 双十一活动页面(商品页面)
     *活动时间：11月9号——11月16号
     */

    /**
     * @throws CException
     */
    public function actionSSYShop($cust_id)
    {
        $sql = "SELECT id, customer_price, market_price FROM  goods where id in(20992,20993,1942,2008,21053,21052,21045,21036,21024,21023,21000,21058) order by id";
        $ListGoods = Yii::app()->db->createCommand($sql)->queryAll();
        //dump($ListGoods);
        foreach ($ListGoods as $row) {
            $ResGoods[$row['id']] = array(
                '销售价' => $row['market_price'],
                '幸运价' => $row['customer_price'],
            );
        }



        $SetableSmallLoansModel = new SetableSmallLoans();
        $href = $SetableSmallLoansModel->searchByIdAndType('linli', '', $cust_id);
        if ($href) {
            $LinLiUrl = $href->completeURL;
        } else {
            $LinLiUrl = '';
        }

        $href2 = $SetableSmallLoansModel->searchByIdAndType('anshi', '', $cust_id);
        //邻里url
        if ($href2) {
            $AnShiUrl = $href2->completeURL;
        } else {
            $AnShiUrl = '';
        }

        $href3 = $SetableSmallLoansModel->searchByIdAndType('jd', '', $cust_id);
        //邻里url
        if ($href3) {
            $JDUrl = $href3->completeURL;
        } else {
            $JDUrl = '';
        }
        $JDUrl = $href3->completeURL;

        $this->renderPartial(
            "shop",
            array('ListGoods' => $ResGoods,
            'LinLiUrl'=>$LinLiUrl,
                'AnShiUrl'=>$AnShiUrl,
                'JDUrl'=>$JDUrl)
        );

    }


}   