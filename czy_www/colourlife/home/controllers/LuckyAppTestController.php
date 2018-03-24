<?php
class LuckyAppTestController extends CController {
    //copy from luckyController 

    private $_luckyCustCan = 0;
    private $_luckyTodayCan=0;
    private $_luckyActId = Item::LUCKY_ACT_ID;
    private $_username = "";
    private $_userId = 0;
    private $_cust_model = "";  
    public  $entityList = array(89,90,91,92,93,94,95,96,97,98,106,107,108,109,110,111,112,113,114,115,125,126,127,128,129,130,131,132,133,136);
    private $_dreamActId = Item::DREAM_ACT_ID;
    private $_userIP = "";
    
    //是否结束
    private function  isover(){
        $luckyAct=LuckyActivity::model()->findByPk($this->_luckyActId);
        if($luckyAct && ($luckyAct->end_date." 23:59:59" < date("Y-m-d H:i:s")) ){
            return true;//已结束
        }
        return false;//未结束
    }


    //是否开始
    private function  isstart(){
        $luckyAct=LuckyActivity::model()->findByPk($this->_luckyActId);
        if($luckyAct && ($luckyAct->start_date." 00:00:00" <= date("Y-m-d H:i:s")) ){
            return true;//已开始
        }
        return false;//未开始
    }



    public function actionIndex() {
        $this->checkLogin();
        $allJoin=LuckyCustomerOut::model()->count('lucky_act_id<>12');
        if(date('Y-m-d H:i:s')>='2015-03-30 23:59:59'){
            $this->renderPartial( "paintPuzzle",array ( 
                "luckyCustCan" => $this->_luckyCustCan,
                "luckyTodayCan" => $this->_luckyTodayCan,
                "custId"=>$this->_userId,
                "allJoin"=>$allJoin+123,
            ));
        }else{
            $this->renderPartial( "yugao6");
        }
    }

    public function actionPaintPuzzleRule(){
        $this->checkLogin();
        $this->renderPartial("paintPuzzle_rule");
    }


    public function actionPaintPuzzleResult(){
        $this->checkLogin();
        $listResutlist = $this->getListData();  
        $list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id in (".Item::LUCKY_ACT_ID."-2,".Item::LUCKY_ACT_ID."-1,".Item::LUCKY_ACT_ID.") and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");
        $this->renderPartial("paintPuzzle_result", array (
            "listResutl"=>$listResutlist,
            "list"=>$list,
        ));
    }






    public function actionMainIndex(){
        $this->checkLoginCar();
        $this->renderPartial("lucky_car_main");
    }


    public function actionLuckyApp(){
        $this->checkLoginCar();
        $customer = Customer::model()->findByPk($this->_userId);
        $_mModel = new SetableSmallLoans();
        $re = $_mModel->searchByIdAndType('oneyuan', 1, $this->_userId, false);
        if ($re) {$url = $re->completeURL;} else $url = 'error';
        $this->renderPartial("lucky_car",array('balance'=>$customer->balance,'url'=>$url,));
    }

    public function actionLuckyCarResult() {
        $this->checkLoginCar();
        $list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id in (".Item::LUCKY_ACT_ID_CAR.") and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");
        $this->renderPartial("lotteryDetails", array ("list"=>$list));
    }




    public function actionCarTopic(){
        $this->checkLoginCar();
        $_mModel = new SetableSmallLoans();
        $ree = $_mModel->searchByIdAndType('LICAIYI' , 1, $this->_userId, false);
        if ($ree) {$urle = $ree->completeURL;} else $urle = 'error';
        $list = LuckyMayCarOutcome::model()->findAll(" state=0 and customer_id=".$this->_userId);
        $count = LuckyMayCarOutcome::model()->count(" state=0 and customer_id=".$this->_userId);
        $this->renderPartial("lotterySubweb", array('urle'=>$urle,'list'=>$list,'count'=>$count));
    }


    public function actionCarMyCode(){
        $this->checkLoginCar();
        $list = LuckyMayCarOutcome::model()->findAll(" state=0 and customer_id=".$this->_userId);
        $this->renderPartial("lotteryMycode", array('list'=>$list));
    }    


    public function actionLuckyCarRule(){
        $this->checkLoginCar();
        $_mModel = new SetableSmallLoans();
        $ree = $_mModel->searchByIdAndType('LICAIYI' , 1, $this->_userId, false);
        if ($ree) {$urle = $ree->completeURL;} else $urle = 'error';
        $this->renderPartial("lotteryCarRule",array('urle'=>$urle));
    }



    /**
     * 抽奖
     */
    public function actionDoPaintPuzzle() {
        $this->checkLogin();
        if($this->isover()||!$this->isstart()){
            exit("activity error!");
        }
        $luckyOper = new LuckyOperation();
        $besideids = array(Item::LUCKY_THANKS_ID);
        if(isset($_POST["flag"])&&$_POST["flag"]=='colourlife'){
            $result = $luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan,$this->_luckyTodayCan, $this->_username,$this->_userId,true,$besideids,$flag=true);
        }else{
            $result = $luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan,$this->_luckyTodayCan, $this->_username,$this->_userId,true,$besideids,$flag=false);
        }        
        echo CJSON::encode ( $result );
    }


    /**
     * 汽车抽奖
     */
    public function actionDoLucky() {
        $this->checkLoginCar();
        if($this->isover()||!$this->isstart()){
            exit('活动异常');
        }
        $luckyOper = new LuckyOperationCar();
        $besideids = array(Item::LUCKY_THANKS_ID_CAR);
        // var_dump($this->_userId);die;
        $result = $luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan,$this->_luckyTodayCan, $this->_username,$this->_userId,true,$besideids);
        if ($result && $result ['success'] == 1) {
            //更改角度值，只取中间值
            $min=$result ['data'] ['result']['angle']['min'];
            $max=$result ['data'] ['result']['angle']['max'];
            $minArr=explode(",", $min);
            $maxArr=explode(",", $max);
            $index=0;
            $index=rand(0, count($min)-1);
            $minEnd=intval($minArr[$index]);
            $maxEnd=intval($maxArr[$index]);
            $result ['data'] ['result']['angle']=intval(($minEnd+$maxEnd)/2);
        }
        echo CJSON::encode ( $result );
    }





    public function actionXingrenyouli(){
        $this->checkLogin();
        $branchName=$this->_cust_model->community->branch->parent->parent;
        if(!empty($branchName)&&trim($branchName->name)=='深圳事业部'){
            $this->renderPartial("xingrenyouli_szVersions",array(
                'cust_id'=>$this->_userId,
            ));
        }else{
            $this->renderPartial("xingrenyouli_index",array(
                'cust_id'=>$this->_userId,
            ));
        }
        
    }




    //新注册送5员红包接口
    public function actionDoSendRedPacket()
    {   
        $this->checkLogin();
        if(date('Y-m')>='2015-04'&&date('Y-m')<='2015-05'){
            $model = Customer::model()->enabled()->findByPk($this->_userId);
            if (!isset($model)) {
                // throw new CHttpException(400, '用户不存在或被禁用!');
                echo CJSON::encode ( 0 );
            }else{
                if($model->community_id==585){
                    // throw new CHttpException(400, '活动用户不含体验区!');
                    echo CJSON::encode ( 1 );
                }else if(!in_array(date('m',$model->create_time),array('04','05'))){
                    // throw new CHttpException(400, '用户不是在活动时间内注册!');
                    // echo 1122;die;
                    echo CJSON::encode ( 2 );
                }else{
                    $result = RedPacket::model()->find('customer_id=:cust_id and sn=:sn and type=1',array(':cust_id'=>$this->_userId,':sn'=>'act_register'));
                    if($result){
                        echo CJSON::encode ( 6 );//红包已经领取过，不能重复领取
                    }else{
                        $items = array(
                            'customer_id' => $this->_userId,//业主的ID
                            'sum' => 5,//红包金额
                            'sn' => 'act_register',
                            'from_type' => Item::RED_PACKET_FROM_TYPE_NEW_CUSTOMER_REGISTER,
                        );
                        $redPacked = new RedPacket();
                        if(!$redPacked->addRedPacker($items)){
                            // throw new CHttpException(400, '红包领取失败!');
                            echo CJSON::encode ( 3 );
                        }
                        Yii::log("活动期间新注册的用户获得红包5元，用户ID[{$this->_userId}]", CLogger::LEVEL_INFO, 'colourlife.home.luckyApp.doSendRedPacket');
                        echo CJSON::encode ( 4 );
                    }
                    
                }                
            }                
        }else{
            // throw new CHttpException(400, '活动失效');
            echo CJSON::encode ( 5 );
        }
    }




    //E维修代金劵
    public function actionDoWeiXiuJuan()
    {   
        $this->checkLogin();
        if(date('Y-m')=='2015-05'){
            $model = Customer::model()->enabled()->findByPk($this->_userId);
            if (!isset($model)) {
                // throw new CHttpException(400, '用户不存在或被禁用!');
                echo CJSON::encode ( 0 );
            }else{
                if($model->community_id==585){
                    // throw new CHttpException(400, '活动用户不含体验区!');
                    echo CJSON::encode ( 1 );
                }else if(!in_array(date('m',$model->create_time),array('04','05'))){
                    // throw new CHttpException(400, '用户不是在活动时间内注册!');
                    echo CJSON::encode ( 2 );
                }else{
                    if($model->is_lingqu_weixiu==1){
                        echo CJSON::encode ( 6 );//代金劵已经领取,不能重复领取
                    }else{
                        $url="http://m.eshifu.cn/business/sendcoupons?mobile=".$model->mobile;
                        $return = Yii::app()->curl->get($url);
                        $result = json_decode($return,true);
                        if($result["code"]==0&&empty($result["message"])){
                            Yii::log("活动期间新注册的用户获得20元E维修代金劵，用户ID[{$this->_userId}]", CLogger::LEVEL_INFO, 'colourlife.home.luckyApp.doWeiXiuJuan');
                            if (!Customer::model()->updateByPk($model->id, array('is_lingqu_weixiu'=>1))) {
                                echo CJSON::encode ( 9 );//领取失败
                            }
                            echo CJSON::encode ( 4 );//领取成功
                        }else if($result["code"]==-1&&$result["message"]=='无效的用户手机号码'){
                            echo CJSON::encode ( 3 );//无效的用户手机号码
                        }else if($result["code"]==-1&&$result["message"]=='数据操作异常'){
                            echo CJSON::encode ( 7 );//数据操作异常
                        }else if($result["code"]==-1&&$result["message"]=='代金券发放时间已过期'){
                            echo CJSON::encode ( 8 );//代金券发放时间已过期
                        }else{
                            echo CJSON::encode ( 9 );//领取失败
                        }
                    }
                    
                }                
            }                
        }else{
            // throw new CHttpException(400, '活动失效');
            echo CJSON::encode ( 5 );
        }
    }



	
    public function getListData(){

        $listData=array();        
        $listData[0]='恭喜棕榈堡业主王**获得了8.8元红包';
        $listData[1]='恭喜鸿运嘉园业主吴**获得了88元红包';
        $listData[2]='恭喜左邻右舍业主詹**获得了18元红包';
        $listData[3]='恭喜百合花园业主刘**获得了8.8元红包';
        $listData[4]='恭喜晶地顺苑业主李**获得了0.18元红包';
        $listData[5]='恭喜锦绣华庭业主张**获得了18元红包';
        $listData[6]='恭喜联谊广场业主董**获得了18元红包';
        $listData[7]='恭喜望海新都业主梁**获得了8.8元红包';
        $listData[8]='恭喜山水田园业主蔡**获得了1.8元红包';
        $listData[9]='恭喜正喆花园业主高**获得了8.8元红包';
        $listData[10]='恭喜正喆花园业主高**获得了8.8元红包';
        $listData[11]='恭喜未来城业主唐**获得了1.8元红包';
        $listData[12]='恭喜香树丽舍业主喻**获得了18元红包';
        $listData[13]='恭喜聚缘北庭业主黄**获得了88元红包';
        $listData[14]='恭喜九州假日业主谢**获得了1.8元红包';
        $listData[15]='恭喜山景天下业主周**获得了8.8元红包';
        $listData[16]='恭喜恒达花园业主杨**获得了1.8元红包';
        $listData[17]='恭喜七星花园业主龙**获得了8.8元红包';
        $listData[18]='恭喜逸仙名居业主宁**获得了0.8元红包';
        $listData[19]='恭喜金色比华利业主贺**获得了8.8元红包';
        $conditon="lucky_act_id=".$this->_luckyActId." order by id desc limit 20";
        $dataResult =  LuckyCustResult::model()->findAll($conditon);
        $list = array();
        if(count($dataResult) < 8){
            $list[] = array("msg" => $listData[0]);
            $list[] = array("msg" => $listData[7]);
            $list[] = array("msg" => $listData[3]);
            $list[] = array("msg" => $listData[8]);
        }
        for($i=0;$i<count($dataResult);$i++){
            $name=empty($dataResult[$i]['receive_name'])?(""):(F::msubstr($dataResult[$i]['receive_name'],0,1)."**");

            if($dataResult[$i]['isred']){
                 $list[]=array(
                'msg'=>"恭喜".$dataResult[$i]['public_info']."业主".$name."获得了".$dataResult[$i]['rednum']."元红包",
                );
            }else{
                $list[]=array(
                'msg'=>"恭喜".$dataResult[$i]['public_info']."业主".$name."获得了".$dataResult[$i]->prize->prize_name,
                );
            }
           
            if($i%3==0){
                $list[] = array("msg" => $listData[rand(0,19)]);
            }
        }
        return $list;
    }


    
	




    



    public function actionDoShakeLucky() {
        $this->checkLogin();
        if($this->isover()){
            exit();
        }        
        $luckyOper = new LuckyOperation();
        $besideids = array(Item::LUCKY_THANKS_ID);
        $result=$luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan,$this->_luckyTodayCan, $this->_username,$this->_userId,true,$besideids);
        echo CJSON::encode ( $result );
    }



    public function actionDoShakeLuckyNew() {
        $this->checkLogin();
        if($this->isover()){
            exit();
        }

        $luckyOper = new LuckyOperation();
        $besideids = array(Item::LUCKY_THANKS_ID);
        $result=$luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan,$this->_luckyTodayCan, $this->_username,$this->_userId,true,$besideids);
        echo CJSON::encode ( $result );
    }



	
	
	public function actionMylottery(){
		$this->checkLogin();		
		$list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id in (".Item::LUCKY_ACT_ID."-2,".Item::LUCKY_ACT_ID."-1,".Item::LUCKY_ACT_ID.") and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");
		$this->renderPartial("lotterylist",
				array("list"=>$list)
		);
	}


    public function actionLotteryrule(){
        $this->checkLogin();
        $this->renderPartial("throughRule");
    }

    public function actionShakeRule(){
        $this->checkLogin();
        $this->renderPartial("shake_rule");
    }


    public function actionGuaguaRule(){
        $this->checkLogin();
        $this->renderPartial("guagua_rule");
    }


    public function actionHeimei_shuoming(){
        $this->checkLogin();
        $this->renderPartial("heimei_shuoming");
    }



    public function actionShakeRule5000(){
        $this->checkLogin();
        $this->renderPartial("shake_rule5000");
    }

    public function actionTaikanglingqu(){
        $this->checkLogin();
        if(isset($_GET['id'])){
    		$this->renderPartial("taikang_lingqu",array('lucky_result_id' =>$_GET['id']));
    	}else{
    		$this->renderPartial("taikang_lingqu");
    	}       
    	
    }




    public function actionChristmasResult(){
        $this->checkLogin();
        $listResutlist = $this->getListData();  
        $list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id in (".Item::LUCKY_ACT_ID."-2,".Item::LUCKY_ACT_ID."-1,".Item::LUCKY_ACT_ID.") and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");
        $this->renderPartial("christmas_result", array ("listResutlist"=>$listResutlist,"list"=>$list));
    }



    public function actionHappinessResult(){
        $this->checkLogin();
        $allJoin=LuckyCustomerOut::model()->count();
        $listResutlist = $this->getListData();  
        $list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id in (".Item::LUCKY_ACT_ID."-2,".Item::LUCKY_ACT_ID."-1,".Item::LUCKY_ACT_ID.") and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");
        $this->renderPartial("happiness_result", array (
            "listResutlist"=>$listResutlist,
            "list"=>$list,
            "allJoin"=>$allJoin+123,
        ));
    }



    public function actionHappinessSnh(){
        $this->checkLogin();
        $this->renderPartial("happiness_snh");
    }
	
	/*
	 * 专题 by 20150306
	 */
    public function actionSpecialTopic(){
	    $this->checkLogin();
	    $act = (int)Yii::app()->request->getParam('act');
		if ($act){
		   if ($act == 1) $act =1; else $act = 2;
		   $this->renderPartial("special_topic_{$act}");
		}else {
		    $customer = Customer::model()->findByPk($this->_userId);
		    if ($customer){
			   $community_id = $customer->community_id;
			} else $community_id = '';
			$community_array = array(31,81,13,1,2,6,72,75);
			$community_array = array_flip($community_array);
		    if (!array_key_exists($community_id, $community_array)) {echo '此区还没有开通'; exit; }
			$_mModel = new SetableSmallLoans();//echo $this->_userId;exit;
			$re = $_mModel->searchByIdAndType('market' , 1, $this->_userId, false);//var_dump($re);
			if ($re) {$url = $re->completeURL;} else $url = 'error';// echo $url;
			$this->renderPartial("special_topic", array('url'=>$url,'userid'=>$this->_userId,'community_id'=>$customer->community_id));
		}
    }
	



    public function actionHappinessRule(){
        $this->checkLogin();
        $this->renderPartial("happiness_rule");
    }


    public function actionChristmasRule(){
        $this->checkLogin();
        $this->renderPartial("christmas_rule");
    }



    public function actionChristmasShuoMingHuameida(){
        $this->checkLogin();
        $this->renderPartial("christmas_huameida");
    }


    public function actionChristmasShuoMingFengchidao(){
        $this->checkLogin();
        $this->renderPartial("christmas_fengchidao");
    }



    public function actionChristmasShuoMingHaoyahotel(){
        $this->checkLogin();
        $this->renderPartial("christmas_hao_ya");
    }



    public function actionChristmasShuoMingLizigongguan(){
        $this->checkLogin();
        $this->renderPartial("christmas_lizhi");
    }


    public function actionChristmasShuoMingFlyvilla(){
        $this->checkLogin();
        $this->renderPartial("christmas_luofushang");
    }


    public function actionChristmasShuoMingWonderland(){
        $this->checkLogin();
        $this->renderPartial("christmas_qing_feng");
    }


    public function actionChristmasShuoMingQuyuan(){
        $this->checkLogin();
        $this->renderPartial("christmas_quyuan");
    }


    public function actionChristmasShuoMingTaihutiancheng(){
        $this->checkLogin();
        $this->renderPartial("christmas_tai_hu");
    }


    public function actionChristmasShuoMingHailingdao(){
        $this->checkLogin();
        $this->renderPartial("christmas_yi_jing");
    }

    public function actionChristmasShuoMingSanjiaozhou(){
        $this->checkLogin();
        $this->renderPartial("christmas_shanjiaozhou");
    }




    public function actionChristmasRuleHuameida(){
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_huameida");
    }


    public function actionChristmasRuleFengchidao(){
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_fengchidao");
    }



    public function actionChristmasRuleHaoyahotel(){
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_haoya");
    }



    public function actionChristmasRuleLizigongguan(){
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_lizhi");
    }


    public function actionChristmasRuleFlyvilla(){
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_luofushang");
    }


    public function actionChristmasRuleWonderland(){
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_qingfeng");
    }


    public function actionChristmasRuleQuyuan(){
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_quyuan");
    }


    public function actionChristmasRuleTaihutiancheng(){
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_taihu");
    }

    public function actionChristmasRuleHailingdao(){
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_yijing");
    }

    public function actionChristmasRuleSanjiaozhou(){
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_xunliaowan");
    }



    public function actionChristmasRuleHeiMeiJiu(){
        $this->checkLogin();
        $this->renderPartial("happiness/christmas_shuoming_wine");
    }


    public function actionChristmasRule5000(){
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


    public function actionGuaguaResult(){

        $this->checkLogin();
        $listResutl = $this->getListData();  
        $list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id in (".Item::LUCKY_ACT_ID."-4,".Item::LUCKY_ACT_ID."-3,".Item::LUCKY_ACT_ID."-2,".Item::LUCKY_ACT_ID."-1,".Item::LUCKY_ACT_ID.") and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");
        // var_dump($list);die;
        $this->renderPartial("guagua_result", array ("listResutl"=>$listResutl,"list"=>$list));
    }



    public function actionShakeResult(){
        $this->checkLogin();
        $listResutl = $this->getListData();    
        $list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id in (".Item::LUCKY_ACT_ID."-3,".Item::LUCKY_ACT_ID."-2,".Item::LUCKY_ACT_ID."-1,".Item::LUCKY_ACT_ID.") and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");
        $this->renderPartial("shake_result", array ("listResutl"=>$listResutl,"list"=>$list));
    }


   
    public function actionDajiangshuoming(){
        $this->checkLogin();
        $this->renderPartial("dajiangshuoming");
    }

    public function actionShuoming(){
        $this->checkLogin();
        $this->renderPartial("shuoming");
    }

	public function actionHowgethb(){
                $this->checkLogin();
		$this->renderPartial("howgethb");
	}
	
	public function actionHowgetit(){
                $this->checkLogin();
		$this->renderPartial("howgetit");
	}
	public function actionHowuse(){
                $this->checkLogin();
		$this->renderPartial("howuse");
	}
	
	public function actionBieyangcheng(){
                $this->checkLogin();
		$this->renderPartial("bieyangcheng");
	}

	//水果特供
	public function actionFruitGet(){
        $this->checkLogin();
		$this->renderPartial("fruit");
	}


	
	/**
	 * 抽奖操作
	 */
	private function luckyOperation() {}
	
	/**
	 * 测试 产生抽奖机会
	 */
	public function actionTest() {
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
    public function actionGetCustomerInfo(){
        $this->checkLogin();
        $res = array();
        $customerModel = Customer::model()->findByPk($this->_userId);
        if($customerModel){
            $res['tel'] = $customerModel->mobile;
            $res['name'] = $customerModel->name;
            $res['email'] = $customerModel->email;
            $res['address'] = $customerModel->community->name.$customerModel->build->name.$customerModel->room;
        }
        echo CJSON::encode($res);
    }



    //确定收月饼地址
    public function actionFillReceiving(){
        $this->checkLogin();
        $id = $_POST['id'];
        $linkman = $_POST['linkman'];
        $tel = $_POST['tel'];
        if($this->validatephone($tel)){
            $criteria =new CDbCriteria;
            $criteria->addCondition("id=".$id);
            $criteria->addCondition("customer_id=".$this->_userId);
            $model = MoonCakesResult::model()->find($criteria);
            $model->linkman = $linkman;
            $model->tel = $tel;
            $model->address = $model->CustomerAddress;
            $model->save();
            echo CJSON::encode(1);
        }else{
            echo CJSON::encode(0);
        }
    }



    //确定收月饼地址
    public function actionFruit_FillReceiving(){
        $this->checkLogin();
        $id = $_POST['id'];
        $linkman = $_POST['linkman'];
        $tel = $_POST['tel'];
        if($this->validatephone($tel)){
            $criteria =new CDbCriteria;
            $criteria->addCondition("id=".$id);
            $criteria->addCondition("cust_id=".$this->_userId);
            $model = LuckyCustResult::model()->find($criteria);
            $model->receive_name = $linkman;
            $model->moblie = $tel;
            $model->address = $model->CustomerAddress;
            $model->save();
            echo CJSON::encode(1);
        }else{
            echo CJSON::encode(0);
        }
    }


	/**
	 * 获取最新中奖用户
	 */
	public function actionGetUserNewListJson(){
		$this->checkLogin();
		$result=array("success"=>1,'data'=>array('msg'=>'系统错误'));
		//倒叙查询最近N条记录
		$conditon="lucky_act_id=".$this->_luckyActId." AND prize_id!=".Item::LUCKY_THANKS_ID." AND (isred=0 OR (isred=1 AND rednum>10 AND rednum<500)) order by id desc limit 7";
		$data =  LuckyCustResult::model()->findAll($conditon);
		$list=array();
		$listJia=array(
				array('msg'=>'恭喜碧水龙庭业主谢**获得了5.18元红包'),
				array('msg'=>'恭喜南国丽园业主王**获得了8.88元红包'),
				array('msg'=>'恭喜金桔苑业主卢**获得了0.18元红包'),
				array('msg'=>'恭喜景尚雅苑业主雷**获得了1.68元红包'),
		);
		foreach ($data as $value){
			$name=empty($value['receive_name'])?(""):(F::msubstr($value['receive_name'],0,1)."**");
			if($value['isred']==1){
				$list[]=array(
						'msg'=>"恭喜".$value['public_info']."业主".$name."获得了".$value['rednum']."元红包",
				);
			}else{
				$list[]=array(
						'msg'=>"恭喜".$value['public_info']."业主".$name."获得了".$value->prize->prize_name,
				);
			}
		}
		if(count($list)>0){
			if(count($list)<6){  //少于6条，拼加假数据
				$list=array_merge($list,$listJia);
			}
			$result=array("success"=>1,'data'=>array('list'=>$list));
		}else{
			$result=array("success"=>1,'data'=>array('list'=>$listJia));
		}
		echo CJSON::encode($result);
	}
    
	/**
	 * 获得再来一次
	 */
	public function actionGetAgain(){
		$this->checkLogin();
		$luckyOper=new LuckyOperation();
		$ret=$luckyOper->getAgainChance($this->_userId, $this->_username);
		echo json_encode($ret);
	}


	
    private function checkLogin(){
        if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])){
        	exit('<h1>用户信息错误，请重新登录</h1>');
		}else {
			$custId=0;
			if(isset($_REQUEST['cust_id'])){  //优先有参数的
				$custId=intval($_REQUEST['cust_id']);
				$_SESSION['cust_id']=$custId;
			}else if(isset($_SESSION['cust_id'])){  //没有参数，从session中判断
				$custId=$_SESSION['cust_id'];
			}
			$customer=Customer::model()->find("id=:id and state = 0", array('id' => $custId));
			if(empty($custId) || empty($customer)){
				exit('<h1>用户信息错误，请重新登录</h1>');
			}
    		$this->_luckyActId = isset ( $_REQUEST ['actid'] ) ? ($_REQUEST ['actid']) : Item::LUCKY_ACT_ID;
    		$this->_userId = $custId;
    		$this->_username =$customer->username;
            $this->_cust_model = $customer;
    		$luckyNum = 0;
    		$luckyCan = new LuckyCustCan ();
    		$result = $luckyCan->getCustCan ( $this->_username, $this->_userId, $this->_luckyActId );
    		if ($result) {
    			$this->_luckyCustCan = $result ['cust_can']<0?0:$result ['cust_can'];
    			$this->_luckyTodayCan= $result['cust_today_can']<0?0:$result['cust_today_can'];
    		}
		}
    }



    private function checkLoginCar(){
        if (empty($_REQUEST['cust_id']) && empty($_SESSION['cust_id'])){
            exit('<h1>用户信息错误，请重新登录</h1>');
        }else {
            $custId=0;
            if(isset($_REQUEST['cust_id'])){  //优先有参数的
                $custId=intval($_REQUEST['cust_id']);
                $_SESSION['cust_id']=$custId;
            }else if(isset($_SESSION['cust_id'])){  //没有参数，从session中判断
                $custId=$_SESSION['cust_id'];
            }
            $customer=Customer::model()->find("id=:id and state = 0", array('id' => $custId));
            if(empty($custId) || empty($customer)){
                exit('<h1>用户信息错误，请重新登录</h1>');
            }
            $this->_luckyActId = isset ( $_REQUEST ['actid'] ) ? ($_REQUEST ['actid']) : Item::LUCKY_ACT_ID_CAR;
            $this->_userId = $custId;
            $this->_username =$customer->username;
            $this->_cust_model = $customer;
        }
    }


    
    public function actionColourRule(){
        $this->checkLogin();
        $this->renderPartial("colourRule");
    }
    
    //记录中了电信充值卡
    public function actionTelecom(){
        $this->checkLogin();
        $mobile = $_POST['mobile'];
        if(Telecom::model()->checkMobile($mobile)){
            $criteria =new CDbCriteria;
            $criteria->addCondition("customer_id=".$this->_userId); 
            $criteria->order = "id desc";
            $telecom = Telecom::model()->find($criteria);
            $telecom->mobile = $mobile;
            if($telecom->save()){
                echo CJSON::encode(1);    //成功
            }else{
                echo CJSON::encode(0);    //失败
            }
        }else{
            echo CJSON::encode(0);    //失败
        }        
    }
    
    //更改充值卡号码
    public function actionUpdateTelecom(){
        $lucky_cust_result_id = $_POST['lucky_cust_result_id'];
        $mobile = $_POST['mobile'];
        if(Telecom::model()->checkMobile($mobile)){
            $telecom = Telecom::model()->find('lucky_cust_result_id='.$lucky_cust_result_id);
            $telecom->mobile = $mobile;
            if($telecom->save()){
                echo CJSON::encode(1);    //成功
            }else{
                echo CJSON::encode(0);    //失败
            }
        }else{
            echo CJSON::encode(0);    //失败
        }        
    }

    //验证身份证号码
    public function actionCheckIdentity(){
        $this->checkLogin();
    	$code = $_POST['identity'];
    	$result = CheckIdentity::checkCode($code);
        if($result){
            $model = TaikangLife::model()->find("identity='".$code."'");
            if($model){
                //数据库有重复
                echo CJSON::encode(array('pass'=>1));
            }else{
                echo CJSON::encode(array('pass'=>2));
            }
        }else{
            //身份证格式不正确
            echo CJSON::encode(array('pass'=>0));
        }
    	
    }

    

    //验证手机号码
    public function actionCheckExistMobile(){
        $this->checkLogin();
    	$mobilephone = $_POST['mobile'];
        if(strlen(trim($mobilephone)) != 11){
            echo CJSON::encode(array('pass'=>0));    //失败
        }
        if(preg_match("/^1(3|4|5|7|8){1}\d{9}$/",$mobilephone)){
        //验证通过   
            $model = TaikangLife::model()->find("mobile='".$mobilephone."'");
            if($model){
                //数据库有重复
                echo CJSON::encode(array('pass'=>1));
            }else{
                echo CJSON::encode(array('pass'=>2));
            }

        }else{   
        //手机号码格式不对   
            echo CJSON::encode(array('pass'=>0));
        }
    }   



    public function actionDoTaiKang_Life(){
        $this->checkLogin();
        
        $type = $_POST['type'];
        $name = $_POST['name'];
        $identity = $_POST['identity'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
       	


        $criteria =new CDbCriteria;
        if(isset($_POST['lucky_result_id'])){
        	$criteria->addCondition(" lucky_result_id=".$_POST['lucky_result_id']." and customer_id=".$this->_userId);
        }else{
        	$criteria->addCondition("customer_id=".$this->_userId);
        	$criteria->order = "id desc";
        }
        
        $model = TaikangLife::model()->find($criteria);
        $model->name = $name;
        $model->identity = $identity;
        $model->mobile = $mobile;
        $model->email = $email;
        $model->type = $type;
        $model->save();
        echo CJSON::encode(array('pass'=>1));
        

    }




    //验证手机号码
    private function validatephone($mobilephone){
        if(strlen(trim($mobilephone)) != 11){
            return false;
        }
        if(preg_match("/^13[0-9]{1}\d{8}|15[0-9]{1}\d{8}|18[0-9]{1}\d{8}$/",$mobilephone)){   //1(3|4|5|7|8){1}\d{9}
        //验证通过   
            return true; 
        }else{   
        //手机号码格式不对   
            return false; 
        }
    }    

    
    //世界杯首页
    public function actionWorldCupIndex(){
        $this->renderPartial('worldCupIndex');
    }
    
    //世界杯猜胜负
    public function actionGuessOutcome(){
        $this->checkLogin();
        $encounters = Encounter::model()->getAllEncounterAtNow();
        $arr_outcome = array();
        //获取用户已经选择的记录
        foreach($encounters as $key=>$encounter){
            $model = CustomerOutcome::model()->find('encounter_id=:encounter_id and customer_id=:customer_id',
                    array(':encounter_id'=>$encounter->id,':customer_id'=>$this->_userId));
            if($model){
                $arr_outcome[$key]['encounter_id'] = $encounter->id;
                $arr_outcome[$key]['myoutcome'] = $model->myoutcome;
            }else{
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
    public function actionUpdateOutcome(){
        $this->checkLogin();
        $encounters = Encounter::model()->getAllEncounterAtNow();
        $customerStatistics = CustomerOutcome::model()->getCustomerStatistics($this->_userId);
        $arr_outcome = array();
        //获取用户已经选择的记录
        foreach($encounters as $key=>$encounter){
            $model = CustomerOutcome::model()->find('encounter_id=:encounter_id and customer_id=:customer_id',
                    array(':encounter_id'=>$encounter->id,':customer_id'=>$this->_userId));
            if($model){
                $arr_outcome[$key]['encounter_id'] = $encounter->id;
                $arr_outcome[$key]['myoutcome'] = $model->myoutcome;
            }else{
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
    



    private function checkLoginEx(){
        if(empty($_REQUEST['employee_id']) && empty(Yii::app()->session['employee_id'])) {
            exit('<h1>员工信息错误，请重新登录</h1>');
        }else {
            $employeeId=0;
            ini_set('session.gc_maxlifetime', 3600*12); //设置时间
            if(isset($_REQUEST['employee_id'])){  //优先有参数的
                $employeeId=intval($_REQUEST['employee_id']); 
                Yii::app()->session['employee_id']=$employeeId;
            }else if(isset(Yii::app()->session['employee_id'])){  //没有参数，从session中判断
                $employeeId=Yii::app()->session['employee_id'];
            }
            $employee=Employee::model()->findByPk($employeeId);
            if(empty($employeeId) || empty($employee) || $employee->state==1 || $employee->is_deleted==1){
                exit('<h1>员工信息错误，请重新登录</h1>');
            }
            
            $this->_dreamActId = isset ( $_REQUEST ['actid'] ) ? ($_REQUEST ['actid']) : Item::DREAM_ACT_ID;
            if($this->_dreamActId!=Item::DREAM_ACT_ID){
                exit('<h1>投票程序出错，请联系管理员</h1>');
            }
            $activity = DreamActivity::model()->findByPk($this->_dreamActId);
            if(empty($activity) || ($activity&&$activity->isdelete==1)){
                exit('<h4>活动异常</h4>');
            }
            $this->_userId = $employeeId;
            $this->_userIP = $employee->last_ip;
        }
    }


    public function actionMyoutcome(){
        $this->checkLogin();
        $encounter_game = $_POST['encounter_game'];
        $my_outcome = $_POST['my_outcome'];
        //查看活动是否存在
        $encounter = Encounter::model()->findByPk($encounter_game);
        if(!$encounter){
            echo CJSON::encode(array('code'=>2));    //该场比赛不存在
            exit();
        }
        if($encounter->end_quiz < date("Y-m-d H:i:s")){
            echo CJSON::encode(array('code'=>3));   //已经过了竞猜时间
            exit();
        }
        $model = CustomerOutcome::model()->find('encounter_id=:encounter_id and customer_id=:customer_id',
                array(':encounter_id'=>$encounter_game,':customer_id'=>$this->_userId));
        if(!$model){
            $model = new CustomerOutcome("create");
            $model->encounter_id = $encounter_game;
        }else{
            $model->setScenario("update");
        }
        $model->create_time = time();
        $model->customer_id = $this->_userId;
        $model->myoutcome = $my_outcome;
        $model->customer_ip = Yii::app()->getRequest()->getUserHostAddress();
        if($model->save()){
            echo CJSON::encode(array('code'=>1));    //成功
        }else{
            echo CJSON::encode(array('code'=>0));    //失败
        }        
    }
    
  
    //查看业主猜胜负结果
    public function actionLookResult(){
        $this->checkLogin();
        $customerStatistics = CustomerOutcome::model()->getCustomerStatistics($this->_userId);   //业主猜中的次数
        $customerTotal = CustomerOutcome::model()->getCustomerTotal($this->_userId);             //业主竞猜总数
        $customerTotalRecord = CustomerOutcome::model()->getCustomerTotalRecord($this->_userId);  //业主竞猜所有的记录
        //计算用户用户能中什么多少元红包
        if($customerStatistics>=0 && $customerStatistics<5){
            $redPacket = 2;
            $lack = 5 - $customerStatistics;
        }else if($customerStatistics>=5 && $customerStatistics<10){
            $redPacket = 5;
            $lack = 10 - $customerStatistics;
        }else if($customerStatistics>=10 && $customerStatistics<20){
            $redPacket = 58;
            $lack = 20 - $customerStatistics;
        }else if($customerStatistics>=20 && $customerStatistics<32){
            $redPacket = 288;
            $lack = 32 - $customerStatistics;
        }else if($customerStatistics>=32 && $customerStatistics<=64){
            $redPacket = 588;
            $lack = 64 - $customerStatistics;
        }
        $this->renderPartial("lookResult",array(
            'customerStatistics' => $customerStatistics,
            'customerTotal' => $customerTotal,
            'customerTotalRecord' => $customerTotalRecord,
            'redPacket' => $redPacket,
            'lack' => $lack,
        ));
    }
    
    public function actionWorldCupRule(){
//        $goucai = SmallLoans::model()->searchByIdAndType("GOUCAI",1);
//        var_dump($res->completeURL);exit();
        $this->renderPartial("worldCupRule");
    }
    
    
    //介绍荔枝
    public function actionLizhi(){
        $this->renderPartial("lizhi");
    }
    
    //猜胜负竞猜规则
    public function actionWorldRuleOne(){
        $this->renderPartial("worldRuleOne");
    }
    
    //查看世界杯结果
    public function actionLookAllResult(){
        $this->checkLogin();
        $customerStatistics = CustomerOutcome::model()->getCustomerStatistics($this->_userId);   //业主猜中的次数
        $customerTotal = CustomerOutcome::model()->getCustomerTotal($this->_userId);             //业主竞猜总数
        if(empty($_GET)){
            $all = "all";
            $customerTotalRecord = CustomerOutcome::model()->getCustomerRecodeByPage($this->_userId);  //业主竞猜所有的记录
        }else{
            $all = "";
            $customerTotalRecord = CustomerOutcome::model()->getCustomerTotalRecord($this->_userId);  //业主竞猜所有的记录
        }        
        //计算用户用户能中什么多少元红包
        if($customerStatistics>=0 && $customerStatistics<5){
            $redPacket = 2;
            $lack = 5 - $customerStatistics;
        }else if($customerStatistics>=5 && $customerStatistics<10){
            $redPacket = 5;
            $lack = 10 - $customerStatistics;
        }else if($customerStatistics>=10 && $customerStatistics<20){
            $redPacket = 58;
            $lack = 20 - $customerStatistics;
        }else if($customerStatistics>=20 && $customerStatistics<32){
            $redPacket = 288;
            $lack = 32 - $customerStatistics;
        }else if($customerStatistics>=32 && $customerStatistics<=64){
            $redPacket = 588;
            $lack = 64 - $customerStatistics;
        }
        //晋级
        $promotionList=array();
        $params=array(
            'condition'=>'customer_id=:id AND teams_promotion_id<5',
            'params'=>array(':id'=>$this->_userId),
            'order'=>'teams_promotion_id desc',
        );
        $findAll=CustomerPromotion::model()->findAll($params);
        if($findAll){
            foreach ($findAll as $value){
                $promotionList[$value['teams_promotion_id']]=$value;
            }
        }
        //王者
        $winnerList =CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>$this->_userId,':pid'=>5));
        if(!$winnerList)
            $winnerList=array();

        $this->renderPartial("lookAllResult",
                array('customerStatistics'=>$customerStatistics,
                    'customerTotal'=>$customerTotal,
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
    public function actionUpdateBranch(){
        $pageIndex = 1;
        $pageSize = 50;
        $uptime = $_GET["date"];
        while (true){                                     
            BranchMiddle::model()->insertByUpdate($uptime,$pageSize,$pageIndex);
            ++$pageIndex;
        }
    }


    /**
     * 获取Oa组织架构删除的记录更新中间表
     */
    public function actionDeleteBranch(){
        $pageIndex = 1;
        $pageSize = 50;
        $uptime = $_GET["date"];
        while (true){                                     
            BranchMiddle::model()->deleteByUpdate($uptime,$pageSize,$pageIndex);
            ++$pageIndex;
        }
    }

    public function actionInserEmployeeByUpdate(){
        $pageIndex = 1;
        $pageSize = 50;
        $uptime = "";
        $username=$_GET["name"];
        while (true){                                   
            EmployeeMiddle::model()->recInsertByUpdate($uptime,$pageSize,$pageIndex,$username);
            ++$pageIndex;
        }
    }



        public function actionTestKlintL()
    {
        // $currentTimes = strtotime(date('Y-m-d',time()));
        // Yii::import('application.extensions.phpmailer.JPhpMailer');
        // $mail = new JPhpMailer;
        // $mail->IsSMTP();
        // $mail->Host = 'smtp.163.com';
        // $mail->SMTPAuth = true;
        // $mail->Username = 'klintlili@163.com';
        // $mail->Password = 'z3811365';
        // $mail->SetFrom('klintlili@163.com', '熊见');
        // $mail->Subject = 'PHPMailer Test Subject via smtp, basic with authentication';
        // $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
        // $mail->MsgHTML('<h1>JUST A TEST!'.$currentTimes.'</h1>');
        // $mail->AddAddress('834105056@qq.com', 'John Doe');
        // $mail->Send();


        // $appKey=3;
        // // $appSecret = '%21%40%23JSD';test
        // $appSecret = 'PropertyActivity';
        // $queryStr = '18392066627';
        // $beginDate = '2014-03-16';
        // $endDate = '2015-04-19';
        // $queryType = 1;
        // $token = md5($appSecret.$beginDate.$endDate.$queryStr.$queryType);
        // $url = "http://www.hehenian.com/efinancial/getInvestData?appKey=3&token=".$token."&queryType=".$queryType."&queryStr=".$queryStr."&beginDate=".$beginDate."&endDate=".$endDate;
        // var_dump($url);die;
        // $return = Yii::app()->curl->get($url);
        // $result = json_decode($return,true);
        // var_dump($result["data"]);die;
        // // foreach ($result->data as $k => $v) {
        // // }
        // $rtDate = CJSON::decode($return);

        // $sql = " SELECT LC.date_day, D3.name, count( DISTINCT LC.cust_id) AS CC 
        //     FROM lucky_customer_out LC 
        //     left outer join customer C1 on LC.cust_id=C1.id 
        //     left outer join community C on C1.community_id = C.id and C.state=0 and C.is_deleted=0 
        //     left outer join branch D1 on C.branch_id=D1.id 
        //     left outer join branch D2 on D1.parent_id =D2.id 
        //     left outer join branch D3 on D2.parent_id =D3.id 
        //     group by LC.date_day desc, D3.name desc ";
        //     $rawData = Yii::app()->db->createCommand($sql)->queryAll();
        //     var_dump($rawData);die;

        //     $dataProvider = new CArrayDataProvider($rawData,array(  
        //         'keyField' => 'date_day',
        //         'pagination' => array('pageSize' => $pageCounts),
        //     )); 

        // $mdstr=md5('DJKC#$%CD%des$bnobsecretuserid113153usernameuser_15527730750mobile15527730750password6de9da5b1b19eb9ee9aefa540dd51672cid1tjridbranchName碧水龙庭cname碧水龙庭caddress广东省-深圳市-龙华新区-碧水龙庭DJKC#$%CD%des$');
        // var_dump($mdstr);


        // $url="http://m.eshifu.cn/business/sendcoupons?mobile=1552773075ddd0";
        // $return = Yii::app()->curl->get($url);
        // $result = json_decode($return,true);
        // var_dump($result["code"]);
        // $va = '2015-04-01';
        // echo time();echo '<br/>';
        // var_dump(strtotime($va."+1 month"));//echo '<br/>';
        // var_dump(strtotime("-10 month"));
        //$timeList = Yii::app()->config->orderExpire;
        // $autoTime = time() - intval($timeList['customerOrderCancel']);
        // var_dump($autoTime);
        // $pageindex = 1;
        // $pagesize = 10;
        // Yii::import('common.api.ColorCloudApi');
        // $colure = ColorCloudApi::getInstance();
        // $year = intval(date('Y'));
        // $month = intval(date('m'));
        // $res = $colure->callGetHongBaoUpList($year,$month,$pagesize,$pageindex);
        // var_dump($res);die;
        // if(!empty($res['data'])){
        //     $place = $res['data'][0]['familyname'];
        // }else{
            
        // }
        // var_dump($place);
        // var_dump(HongBaoUplist::model()->findAll()[0]['hbfee']);

        // $useroa = 'suyuan2';
        // // if(empty($useroa)){
        // //     throw new CHttpException(400, '用户错误');
        // // }
        // $page = 1;
        // $pagesize = 10;
        // $keyword = "本体金";
        // $data = ExamineMy::model()->getExamineMy($useroa, $keyword, $pagesize, $page);
        // $str = 'klintlili';
        // var_dump(md5(md5('302722').'n7q08NlJ'));die;
        // echo md5('123');die;



        /*
        *测试sign例子 
        *规则：MD5（加密私钥bno参数值bsecret参数值userid参数值username参数值mobile参数
        *值password参数值cid参数值cname参数值caddress参数值加密私钥）
        *@return sign
        *@ by wenda 2015.1.31
        */
        // $bno = 'test';
        // $bsecret = 'abcd';
        // $userid = '20351';
        // $username = 'front';
        // $mobile = '15382618692';
        // $password = '2933a6fd15db74cf581037ad33cb05e1';
        // $cid = '2';
        // $cname = '彩科彩悦大厦';
        // $caddress = '广东省-深圳市-龙华新区-彩科彩悦大厦';
        // //密钥
        // $sec = 'DJKC#$%CD%des$';
        // echo $md5 = 
        // $sec.'bno'.$bno.'bsecret'.$bsecret.'userid'.$userid.'username'.$username.'mobile'.$mobile.'password'.$password.'cid'.$cid.'cname'.$cname.'caddress'.$caddress.$sec;echo '<br>';
        // $md5 = md5($md5);
        // echo '<br>', '我的sign:', strtoupper($md5);
        // echo '<br>', '例子', 'sign:C9531043EFEFE69B58AA4E36656B90F5';

        // echo strtoupper(md5(urlencode("DJKC#$%CD%des$bnotestbsecretabcduserid20351usernamefrontmobile15382618692password2933a6fd15db74cf581037ad33cb05e1cid2cname彩科彩悦大厦caddress广东省-深圳市-龙华新区-彩科彩悦大厦DJKC#$%CD%des$")));
        
        // var_dump(md5(md5('123456').'Qze0QQIp'));


        // function is_json($string) {
        //     json_decode($string);
        //     return (json_last_error() == JSON_ERROR_NONE);
        // }

        //{"list":[{"oauser":"xiongjian","year":2015,"month":3,"fee":700,"hbfee":368.9,"hbdata":[{"title":"\u793e\u533a\u5e73\u53f0\u4e2d\u5fc3\u96c6\u56e22.3\u6574\u4f53\u901a\u8fc7\u7387","pinfen":73,"money":153.3},{"title":"\u793e\u533a\u5e73\u53f0\u4e2d\u5fc3\u96c6\u56e2\u7ecf\u8425\u7ee9\u6548\u5b8c\u6210\u7387","pinfen":77,"money":215.6},{"title":"\u793e\u533a\u5e73\u53f0\u4e2d\u5fc3\u603b\u88c1\u5bf9\u6240\u5c5e\u90e8\u95e8\u8bc4\u4ef7","pinfen":0,"money":0}],"kkdata":[],"totaljjbbase":90,"jjbbase":2,"agvpingfen":52.7,"state":1},{"oauser":"xiongjian","year":2015,"month":4,"fee":0,"hbfee":0,"hbdata":[],"kkdata":[],"totaljjbbase":0,"jjbbase":0,"agvpingfen":0,"state":0},{"oauser":"xiongjian","year":2015,"month":5,"fee":0,"hbfee":0,"hbdata":[],"kkdata":[],"totaljjbbase":0,"jjbbase":0,"agvpingfen":0,"state":0}]}
        // $string='{"list":[{"oauser":"xiongjian","year":2015,"month":3,"fee":700,"hbfee":368.9,"hbdata":[{"title":"\u793e\u533a\u5e73\u53f0\u4e2d\u5fc3\u96c6\u56e22.3\u6574\u4f53\u901a\u8fc7\u7387","pinfen":73,"money":153.3},{"title":"\u793e\u533a\u5e73\u53f0\u4e2d\u5fc3\u96c6\u56e2\u7ecf\u8425\u7ee9\u6548\u5b8c\u6210\u7387","pinfen":77,"money":215.6},{"title":"\u793e\u533a\u5e73\u53f0\u4e2d\u5fc3\u603b\u88c1\u5bf9\u6240\u5c5e\u90e8\u95e8\u8bc4\u4ef7","pinfen":0,"money":0}],"kkdata":[],"totaljjbbase":90,"jjbbase":2,"agvpingfen":52.7,"state":1},{"oauser":"xiongjian","year":2015,"month":4,"fee":0,"hbfee":0,"hbdata":[],"kkdata":[],"totaljjbbase":0,"jjbbase":0,"agvpingfen":0,"state":0},{"oauser":"xiongjian","year":2015,"month":5,"fee":0,"hbfee":0,"hbdata":[],"kkdata":[],"totaljjbbase":0,"jjbbase":0,"agvpingfen":0,"state":0}]}';
        // //json_decode($string);
        // //return (json_last_error() == JSON_ERROR_NONE);
        // //var_dump(json_last_error() == JSON_ERROR_NONE);die;
        // // $employee_id=53344;
        // // echo $employee_id;die;
        // $json=$this->is_json($string);
        // if($json){
        //     var_dump(json_decode($string,true));        //json_decode($string,true)
        // }else{
        //     die('no json');
        // }
        // die;
        // $model=Employee::model()->findByPk($employee_id);
        // if(empty($model)||empty($model->username)||$model->state==1||$model->is_deleted==1){
        //     //throw new CHttpException(400,"用户无效");
        //     die('no user');
        // }
        // $oauser = $model->username;
        // //$oauser = 'kangtb';
        // // var_dump($model);die;
        // // $oauser = Yii::app()->request->getPost('oauser');
        // $year = '';
        // $month = '';
        // if(empty($oauser)) throw new CHttpException(400, '缺少对应OA帐号');
        // // if(empty($year)) throw new CHttpException(400, '缺少年');
        // // if(empty($month)) throw new CHttpException(400, '缺少月');
        // //引入彩之云的接口
        // Yii::import('common.api.ColorCloudApi');
        // $coloure = ColorCloudApi::getInstance();
        // $result = $coloure->callGetHongBaoDetails($oauser, $year='', $month='');
        // if(empty($year)&&empty($month)){
        //     $data = array();
        //     foreach ($result['data'] as $k => $v) {
        //         if(in_array(intval($v['month']), array(1,2))){
        //             continue;
        //         }else{
        //             $data[] = $v;
        //         }
        //     }
        //     echo CJSON::encode(array('list'=>$data));
        // }else{
        //     $data = $arr = array();
        //     foreach ($result['data'][0]['kkdata'] as $k => $v) {
        //         $data[$k] = $v;
        //     }
        //     foreach ($result['data'][0]['hbdata'] as $key => $val) {
        //         $arr[$key] = $val;
        //     }
        //     if(empty($data)){
        //         $flag=0;$msg='暂无数据';$list=$data;
        //     }else{
        //         $flag=1;$msg='';$list=$data;
        //     }

        //     if(empty($arr)){
        //         $state=0;$que='暂无数据';$jxdf=$arr;
        //     }else{
        //         $state=1;$que='';$jxdf=$arr;
        //     }
        //     echo CJSON::encode(array('flag'=>$flag,'msg'=>$msg,'list'=>$list,'state'=>$state,'que'=>$que,'jxdf'=>$arr));
        // }

        
        // $criteria = new CDbCriteria;
        // $criteria->compare("`t`.create_time", ">= " . strtotime("2014-12-01 00:00:00"));
        // $criteria->compare("`t`.create_time", "<= " . strtotime("2014-12-31 23:59:59"));
        // $criteria->compare("`t`.status","99");
        // $criteria->compare("`t`.ticheng_send_status","0");
        // $criteria->compare("`t`.inviter","<> 0");
        // $cfr = CaifuAuto::model()->findAll($criteria);
        // foreach($cfr as $r){
        //     if(!empty($r->inviterRe)){
        //         $items = array(
        //             'employee_id' => $r->inviterRe->id,//员工的ID
        //             'sum' => $r->amount*Yii::app()->config->caifuTichengRate,//红包金额
        //             'sn' => $r->sn,
        //             'from_type' => Item::CAI_RED_PACKET_FROM_TYPE_CAIFU_TICHEN_JIANGLI,
        //             'remark' => '彩富提成奖励自动发放',
        //         );
        //     }else{
        //         $r->ticheng_send_status=2;
        //         $r->update_username='system';
        //         $r->update_date=date("Y-m-d H:i:s");
        //         $r->save();
        //         continue;
        //     }

        //     $transaction = Yii::app ()->db->beginTransaction ();
        //     $redPacked = new CaiRedPacket();
        //     try{
        //         if($redPacked->addRedPacker($items)){
        //             $r->ticheng_send_status=1;
        //             $r->update_username='system';
        //             $r->update_date=date("Y-m-d H:i:s");
        //             $r->save();
        //         }                
        //         $transaction->commit();
        //     }catch(Exception $e) {
        //         Yii::log("彩富人生提成自动发放异常：".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.OrderSendRedPcketCommand');
        //         $transaction->rollBack();   // 在异常处理中回滚
        //     }
            
        // }



        // $sqlcf = "select * from `caifu_auto` where status=99 and ticheng_send_status=0 and FROM_UNIXTIME(`create_time`,'%Y%m')='201505' and inviter<>0";
        // $resultcf = Yii::app()->db->createCommand($sqlcf)->queryAll();
        // foreach($resultcf as $_v){
        //     $items = array(
        //         'employee_id' => $model->inviterRe->id,//员工的ID
        //         'sum' => 100,//红包金额
        //         'sn' => $model->sn,
        //         'from_type' => Item::CAI_RED_PACKET_FROM_TYPE_CAIFU_TICHEN_JIANGLI,
        //         'remark' => '彩富提成奖励自动发放',
        //     );
        //     $redPacked = new CaiRedPacket();
        //     if($redPacked->addRedPacker($items)){
        //         $username=Yii::app()->user->name;
        //         $userid=Yii::app()->user->id;
        //         $update_username=$username;
        //         $update_userid=$userid;
        //         $update_date=date("Y-m-d H:i:s");
        //     }
            
        // }

        // $criteria = new CDbCriteria;
        // $criteria->compare("`t`.pay_time", ">= " . strtotime("2015-05-01 00:00:00"));
        // $criteria->compare("`t`.pay_time", "<= " . strtotime("2015-05-31 23:59:59"));
        // $criteria->compare("`t`.status","99");
        // $criteria->compare("`t`.ticheng_send_status","0");
        // $criteria->compare("`t`.inviter_id","<> 0");
        // // $criteria->compare("`t`.customer_id","<> 0");
        // $criteria->compare("`t`.is_receive","1");
        // $criteria->compare("`t`.amount",">0"); 
        // $cfr = ElicaiRedpacketTicheng::model()->findAll($criteria);
        // foreach($cfr as $r){
        //     // var_dump($r->inviterRe->state);die;
        //     if(!empty($r->inviterRe)&&$r->inviterRe->state==0){
        //         echo 111;die;
        //     }else{
        //         echo 222;die;
        //     }
        // }    

    }   



    private function is_json($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }


    /**
     * 获取Oa组织架构所有记录插入中间表
     */
    public function actionInsertBranch(){
        $pageIndex = 1;
        $pageSize = 80;
        while (true){                          
            BranchMiddle::model()->recInsert($pageSize,$pageIndex);
            ++$pageIndex;
        }   
    }


    /**
     * 获取Oa组织架构删除的记录更新中间表
     */
    public function actionAllDeleteBranch(){
        $pageIndex = 1;
        $pageSize = 50;
        $uptime = "";
        while (true){                                     
            BranchMiddle::model()->deleteByUpdate($uptime,$pageSize,$pageIndex);
            ++$pageIndex;
        }
    }



    public function actionInsertEmployee($pageIndex = 1){
        $pageSize = 80;
        while (true){                                     
            EmployeeMiddle::model()->recInsert($pageSize,$pageIndex);
            ++$pageIndex;
        }
    }



    // public function actionTestLaoLu()
    // {
    //     $this->renderPartial("test");
    // }


    // public function actionUpNew()
    // {
    //     // //$sql = " DROP TABLE IF EXISTS `invitation_code`;
    //     //       CREATE TABLE IF NOT EXISTS `invitation_code` (
    //  //                 `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
    //  //                 `code` char(5) NOT NULL COMMENT '邀请码',
    //  //                 PRIMARY KEY (`id`),
    //  //                 UNIQUE KEY `code` (`code`)
    //     //       ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    //     //$this->execute($sql);
    //     $varss = $_GET["code"];
    //     $id = $_GET["id"];
    //     $sid = $_GET["sid"];
    //     $i=1;
    //     $code = '';
    //     while ($i<=3200) {          
    //         $code = F::random(7,1);
    //         // $code = strtoupper($code);
    //         $count = LuckyEntity::model()->find("code='".$varss.$code."'");
    //         if($count){
    //             continue;               
    //         }
    //         // $sql2 = " INSERT INTO `invitation_code` (`id`, `code`) VALUES (NULL, '".$code."');";
    //         // echo $i."\r\n";
    //         // $this->execute($sql2);  

    //         $invitationcode = new LuckyEntity(); 
    //         $invitationcode->prize_id = $id;
    //         $invitationcode->code = $varss.$code;
    //         $invitationcode->shop_id = $sid;
    //         $invitationcode->save();
    //         $i++;
    //     }
    // }




    /**
     * 抽奖机会增加操作。外部调用，都调用这个方法。在此方法中，进行活动更改。
     * @param 相关参数 $param
     */
    public function execute($paramin){
        $result=array();
        //
        //幸福中国行活动，luckyActid=1  ,不在这里做了。该活动已经上线了。该活动等过期后，直接在原来处删除代码。也省去迁移到这里后的测试等
        //
        
        //
        //彩之云app抽奖
        //
        $type=$paramin['type'];
        $param=$paramin['param'];
        $r=$this->luckyAdd($type, $param);
        $result=array_merge($result,$r);
        return $result; 
    }
    

    /**
     * 
     */
    /**
     * 用户进行抽奖，返回抽奖结果 
     * @param 活动id $luckyActId
     * @param 可抽奖次数 $luckyCustCan
     * @param 用户名 $userName
     * @param 用户id $userId
     * @param string $besideGennerZero
     * @param number $besideid
     */
    public function doLucky($luckyActId,$luckyCustCan,$luckyTodayCan,$userName,$userId,$besideGennerZero = true, $besideid = array(0)){
        $result = array ("success" => 0,"data" => array ("msg" => "处理异常，请稍后再试" ) );
        // 检查活动是否有效
        $act = LuckyActivity::model ()->findByPk ( $luckyActId);
        
        if (empty ( $act ) || $act ['disable'] == 1 || $act ['isdelete'] == 1 || date("Y-m-d")<$act->start_date || date("Y-m-d")>$act->end_date) {
            $result = array ("success" => 0,"data" => array ("msg" => "活动不存在或不在有效期内" ) );
            return $result;
        }
        // 是否有抽奖机会 或者 是否今日抽了指定次数 
        
        if ($luckyCustCan <= 0) {
            $result = array ("success" => 0,"data" => array ("msg" => "没有抽奖机会" ) );
            return $result;
        }
        if($luckyTodayCan <=0){
            $result = array ("success" => 0,"data" => array ("msg" => "今日".Item::LUCKY_DAY_MAX."次抽奖机会已用完,明天再来吧" ) );
            return $result;
        }
        
        //
        // 进行抽奖
        //
        //指定组织架构(如:体验小区)，返回谢谢参与
        $customer=Customer::model()->findByPk($userId);
        $branchId=$customer->community->branch->id;
        if(in_array($branchId,Item::$lucky_tiyan_branch_ids)){
            $retObj = array (
                    'id' => intval(Item::LUCKY_THANKS_ID),
                    'prize_name' =>"",
                    'all' => 0,
                    //'prize_picture' => 0,
                    'prize_level_name' => 0,
                    'cust_result_id' => 0,
                    //'angle'=>array("min"=>0,"max"=>0),
                    //'angle'=>Item::$lucky_thanks_angle,
            );
            $change = $this->custDoOperation ( $userName, $userId,$luckyActId, $retObj ['id'],false,$besideGennerZero,$besideid );
            $result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
            return $result;
        }
        

        //新用户必中奖
        $newInfo = $this->newCustomerDo($luckyActId, $userName, $userId, $besideGennerZero, $besideid);
        if($newInfo != false){
            return $newInfo;
        }


        
        // 查的当前活动下的奖项列表
        $luckyPrize = new LuckyPrize ();
        $prizeList = $luckyPrize->getPrizeList ( $luckyActId);
        //没有奖项了，都给予【谢谢参与】奖
        $prize_ids = array (); // 奖项id
        $prize_id_object = array (); // 奖项id=>对象
        foreach ( $prizeList as $value ) {
            $prize_ids [] = $value ['id'];
            $prize_id_object [$value ['id']] = $value;
        }

        // 查询这些奖项id的产生记录
        $prizeBorn = new LuckyPrizeBorn ();
        $bornList = $prizeBorn->getLastBorn ( $prize_ids );
        
        // 如果上次产生的时间，低于设定时间，此奖项本次不产生
        foreach ( $bornList as $value ) {
            if (isset ( $prize_id_object [$value ['prize_id']] )) { // 当前奖项有产生过
                // Yii::log("奖项已经产生过",CLogger::LEVEL_ERROR);
                if ($prize_id_object [$value ['prize_id']] ['prize_count_now'] <= 0) { // 被抽完了
                    unset ( $prize_id_object [$value ['prize_id']] );
                    continue;
                }
                $last = strtotime ( $value ['last_date'] );
                $set = intval ( $prize_id_object [$value ['prize_id']] ['prize_genner_time'] );
                $now = strtotime ( "now" );
                // Yii::log("奖品:".$prize_id_object[$value['prize_id']]['prize_level_name']."当前时间:".date("Y-m-d H:i:s")."上次产生时间".$value['last_date'],CLogger::LEVEL_ERROR);
                if (($now - $last) < $set) { // 当前时间距离上一次产生的时间，小于设定时间
                    unset ( $prize_id_object [$value ['prize_id']] );
                }
            }
        }
        
        //如果没有中奖筛选奖项，直接给予【谢谢参与】
        if(empty($prize_id_object) || count($prize_id_object)<=0){
            $retObj = array (
                    'id' => 0,
                    'prize_name' =>"",
                    'all' => 0,
                    //'prize_picture' => 0,
                    'prize_level_name' => 0,
                    'cust_result_id' => 0,
                    //'angle'=>array("min"=>0,"max"=>0),
                    //'angle'=>Item::$lucky_thanks_angle,
            );
            $result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
            return $result;
        }
        
        // 填充中奖池
        //$allCount=count($prize_id_object);
        $allCount=0;
        foreach ( $prize_id_object as $value ) {
            $allCount += $value ['prize_count_now'];
        }
        //$getRand = rand ( 0, ($allCount - 1) );
        $getRand = mt_rand( 0, ($allCount - 1) );
        $getPrizeId=0;
        $start_index = 0;
        foreach ( $prize_id_object as $value ) {
            //$_temp = array_fill ( $start_index, $value ['prize_count_now'], $value ['id'] );
            if($getRand>=$start_index && $getRand < $start_index+$value ['prize_count_now']){
                //如：该随机数>=0 并且 <剩余数量，即中此奖
                $getPrizeId=$value ['id'];
                break;
            }
            $start_index += $value ['prize_count_now']; // 下一个数组的开始点
        }
        
        $getObj = $prize_id_object [$getPrizeId]; // 该下标对应的值，即为prize的id
        //
        //start 插入判断，如果是红包类型，判断有没有钱可以给
        //
        $redInfo=array('num'=>0,"isgivered"=>0);
        $redPackage=new LuckyRedEnvelope();
        if($getObj['type']==1){
          //中红包奖项           
          //产生红包，并同时更新红包奖项信息
          //$getRed=$redPackage->gennerRedPackage2($userId, $getObj['relation_red_envelope_id']);
            $getRed=$redPackage->gennerRedPackage3($userId, $getObj['id']);
            $getRed=floatval($getRed);
            //TODO 更改红包,大奖发不发-中奖结果记录的记录
            if($getRed==0){ //产生红包失败，转给予谢谢参与
                $retObj = array (
                    'id' => 0,
                    'prize_name' =>"",
                    'all' => 0,
                    //'prize_picture' => 0,
                    'prize_level_name' => 0,
                    'cust_result_id' => 0,
                    //'angle'=>array("min"=>0,"max"=>0),
                    //'angle'=>Item::$lucky_thanks_angle,
                            );
                $result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
                return $result;
            }else{
                //如果是马上给的，给
                if(intval($getObj['delay'])==0){
                    //给予红包
                    //Yii::log("产生红包==UID:".$userId."==num:".$getRed,CLogger::LEVEL_ERROR);
                    $items = array(
                            'customer_id' =>$userId,//登录用户的ID
                            'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,//红包获取方式，抽奖=3;物业欠费=2，预缴费=1
                            'sum' =>$getRed,//红包金额,
                            'sn' => Item::LUCKY_ACT_ID,//订单号(from_type=1or2的情况下)或活动ID(from_type=3的情况下)
                    );
                    $redPacked = new RedPacket();
                    $ret=$redPacked->addRedPacker($items,$redInfo);
                    if($ret){
                        $redInfo['num']=$getRed;
                        $redInfo['isgivered']=1;
                    }else{
                        $redInfo['num']=$getRed;
                        $redInfo['isgivered']=0;
                        Yii::log("抽奖中得红包,送红包失败".json_encode($items),CLogger::LEVEL_ERROR,'colourlife.core.lucky');
                    }
                    
                }else{
                    $redInfo=array('num'=>$getRed,"isgivered"=>0); //记录没给
                }
                
            }
            
            
                                                
        }else{
            //中实物奖项         
            //产生奖，并同时更新奖项信息
            $getRed=$redPackage->gennerEntity($userId, $getObj['id']);
            $getRed=floatval($getRed);
            if($getRed==0){ //产生奖失败，转给予谢谢参与
                $retObj = array (
                    'id' => 0,
                    'prize_name' =>"",
                    'all' => 0,
                    //'prize_picture' => 0,
                    'prize_level_name' => 0,
                    'cust_result_id' => 0,
                    //'angle'=>array("min"=>0,"max"=>0),
                    //'angle'=>Item::$lucky_thanks_angle,
                            );
                $result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
                return $result;
            }
        }
        
        //
        //end 
        //
            
         
        // 更改相关记录
        //$luckyOper = new LuckyOperation ();
        $change = $this->custDoOperation ( $userName, $userId,$luckyActId, $getObj ['id'],$redInfo,$besideGennerZero,$besideid );
        //Yii::log(CJSON::encode($change),CLogger::LEVEL_ERROR);
        if (! empty ( $change )) {
            $getObj->refresh ();
            $retObj = array (
                    'id' => intval($getObj ['id']),
                    'prize_name' => $getObj ['prize_name'],
                    // 'prize_des'=>$getObj['prize_des'],
                    'all' => $getObj ['prize_count_all'],
                    // /'now'=>$getObj['prize_count_now'],
                    // 'prize_picture'=>$getObj['prize_picture'],
                    //'prize_picture' => $getObj->prizePictureUrl,
                    'prize_level_name' => $getObj ['prize_level_name'],
                    //'cust_result_id' => $change ['luckyCustResult'] ['id'],
                    //'cust_result_name' => $change ['luckyCustResult'] ['name'],
                    //'cust_result_address' => $change ['luckyCustResult'] ['address'],
                    //'cust_result_phone' => $change ['luckyCustResult'] ['phone'],
                    //'angle'=>array("min"=>$getObj['angle_start'],"max"=>$getObj['angle_end']),
                    'rednum'=>empty($getRed)?0:$getRed,
            );
            if(in_array($getObj ['id'], $besideid)){  //【谢谢参与】等非实物  也算进去，但是不递减数量
                $result = array ("success" => 1,"data" => array ("bingo" => 0,"msg" => "","result" => $retObj ) );
            }else{
                $result = array ("success" => 1,"data" => array ("bingo" => 1,"msg" => "","result" => $retObj ) );
            }
                
        }else if(in_array($getObj ['id'], $besideid)){
            $retObj = array (
                    'id' => intval($getObj ['id']),
                    'prize_name' => $getObj ['prize_name'],
                    // 'prize_des'=>$getObj['prize_des'],
                    'all' => $getObj ['prize_count_all'],
                    // 'now'=>$getObj['prize_count_now'],
                    // 'prize_picture'=>$getObj['prize_picture'],
                    // 'prize_picture' => $getObj->prizePictureUrl,
                    'prize_level_name' => $getObj ['prize_level_name'],
                    // 'angle'=>array("min"=>$getObj['angle_start'],"max"=>$getObj['angle_end']),
                    'rednum'=>empty($getRed)?0:$getRed,
            );
            if($getObj ['id']==Item::LUCKY_AGAIN_ID){
                $result = array ("success" => 1,  "data" => array ("bingo" => 1, "msg" => "", "result" => $retObj) );
            }else{
                $result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
            }
            
            return $result;
        }else {
            //$result = array ("success" => 0,"data" => array ("msg" => "抽奖失败，本次抽奖不消耗抽奖机会，请重试" ,));
            $retObj = array (
                    'id' => 0,
                    'prize_name' =>"",
                    'all' => 0,
                    //'prize_picture' => 0,
                    'prize_level_name' => 0,
                    'cust_result_id' => 0,
                    //'angle'=>array("min"=>0,"max"=>0),
                    'rednum'=>0,
            );
            $result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
            return $result;
        
        }
        
        return $result;
        
    }

        public function actionCreatecustomer(){
            if (isset($_POST['mobile'])) {
                $customer = Customer::model()->find('mobile=:mobile', array(':mobile' => $_POST['mobile']));
                if($customer){
                    echo CJSON::encode(array('code' => '用户已经注册,不能邀请'));
                }else{

                    $ip_arr = array('1.179.134.69','1.180.120.80','1.180.187.22','1.182.134.83','1.183.130.219','1.183.49.7','1.188.249.223','1.188.69.207','1.189.100.21','1.189.138.183','1.189.149.180','1.189.163.31','1.189.169.231','1.189.209.162','1.189.209.68','1.189.215.130','1.189.50.239','1.189.58.71','1.189.60.37','1.190.249.33','1.191.127.185','1.191.140.102','1.191.154.154','1.191.161.239','1.192.100.174','1.192.107.56','1.192.112.93','1.192.113.40','1.192.119.111','1.192.121.169','1.192.123.118','1.192.128.46','1.192.142.255','1.192.144.87','1.192.145.167','1.192.146.19','1.192.146.44','1.192.147.122','1.192.147.145','1.192.147.99','1.192.158.119','1.192.158.123','1.192.164.35','1.192.167.115','1.192.168.107','1.192.168.120','1.192.171.209','1.192.172.52','1.192.175.143','1.192.175.220','1.192.180.132','1.192.181.43','1.192.182.145','1.192.190.128','1.192.190.149','1.192.190.249','1.192.190.9','1.192.195.168','1.192.195.254','1.192.196.7','1.192.197.51','1.192.198.155','1.192.198.253','1.192.198.54','1.192.199.191','1.192.199.26','1.192.201.136','1.192.204.39','1.192.205.206','1.192.205.234','1.192.205.42','1.192.207.247','1.192.207.99','1.192.219.115','1.192.224.249','1.192.228.131','1.192.246.161','1.192.250.223','1.192.252.22','1.192.28.254','1.192.29.249','1.192.31.213','1.192.36.156','1.192.36.99','1.192.40.102','1.192.40.104','1.192.40.108','1.192.40.11','1.192.40.110','1.192.40.112','1.192.40.113','1.192.40.115','1.192.40.118','1.192.40.121','1.192.40.122','1.192.40.125','1.192.40.126','1.192.40.144','1.192.40.146','1.192.40.152');

                    $community_arrr=array(1,2,3,5,6,7,8,9,10,11,12,13,14,15,16,18,19,20,22,23,24,25,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,52,53,54,55,56,57,58,59,60,61,62,63,64,65,67,68,69,70,71,72,73,74,75,77,78,79,80,81,82,83,84,85,86,87,88,89,90,91,92,93,94,95,96,97,98,99,100,101,102,104,105,106,107,108,109,110,112,114,115);
                    $username = 'user_'.$_POST['mobile'];
                    $salt = F::random(8);            
                    $password = md5(md5('123456').$salt);
                    $name = $_POST['name'];
                    $mobile = $_POST['mobile'];
                    $community_id = $community_arrr[F::random(2,1)];
                    $build = Build::model()->find('community_id=:community_id', array(':community_id' => $community_id));
                    if($build){
                        $build_id = $build->id;
                        $room = '302';
                    }else{
                        $community_id = 29;
                        $build_id = 135;
                        $room = '701';
                    }                    
                    $create_time = strtotime("-10 month");
                    $last_time = time()-30*24*60*45;
                    $last_ip = $ip_arr[intval(F::random(2,1))];
                    $reg_identity = F::random(6,1);
                    $customer_code = strtoupper(F::random(5));
                    $type = $_POST["type"];
                    if($type==1){$Userbalance=100;}
                    if($type==2){$Userbalance=100;}
                    if($type==3){$Userbalance=100;}
                    $sql = "INSERT INTO `customer` (`id`, `username`, `password`, `salt`, `name`, `nickname`, `mobile`, `email`, `community_id`, `build_id`, `room`, `is_show_in_neighbor`, `create_time`, `last_time`, `last_ip`, `state`, `is_deleted`, `audit`, `credit`, `portrait`, `old_mobile`, `status`, `reg_type`, `reg_identity`, `balance`, `is_complete`, `first_do_lucky`, `customer_code`, `invite_code`, `channel`) VALUES
        (null, '".$username."', '".$password."', '".$salt."', '".$name."', '', '".$mobile."', '', ".$community_id.", ".$build_id.", '".$room."', 1, ".$create_time.", ".$last_time.", '".$last_ip."', 0, 0, 0, 10, '', '', 0, 1, '".$reg_identity."', '".$Userbalance."', 1, 1, '".$customer_code."', '', 'Colourlife');";
                    Yii::app()->db->createCommand($sql)->execute();
                    $insertID = Yii::app()->db->getLastInsertID();
                    $cusModel = Customer::model()->findByPk($insertID);
                    $sqlsc = "select * from customer where invite_code='' and FROM_UNIXTIME(create_time,'%Y%m%d')>='20140715' and FROM_UNIXTIME(create_time,'%Y%m%d')<='20150228' limit 21";
                    $resultsc = Yii::app()->db->createCommand($sqlsc)->queryAll();
                    foreach ($resultsc as $k => $v) {
                        $diff = intval(substr($v["mobile"],6));
                        $newcre = $create_time+$diff+4*24*60*32;
                        $updateSql = "update `customer` set `create_time`='".$newcre."',last_time='".(time()-24*24*60*35-$diff*5)."',invite_code='".$cusModel->customer_code."' where `id`=".$v["id"];
                        Yii::app()->db->createCommand($updateSql)->execute();
                        $sql23 = " INSERT INTO `invite` (`id`, `customer_id`, `mobile`, `model`, `create_time`, `valid_time`, `status`, `is_send`, `effective`, `state`) VALUES (null, ".$insertID.", '".$v["mobile"]."', 'customer ', '".($newcre-2*24*60*14)."', '".($newcre-2*24*60*14+7*24*60*60)."', 1, 1, 1, 1);";
                        Yii::app()->db->createCommand($sql23)->execute();
                    }
                    $sqlrc = "INSERT INTO `red_packet` (`id`, `sn`, `type`, `customer_id`, `from_type`, `to_type`, `sum`, `create_time`, `remark`, `note`, `lukcy_result_id`) VALUES (null, 'invite', 2, ".$insertID.", 10, 0, 50.00, '".($newcre-2*24*60*14+7*24*60*60+10*24*24*18)."', NULL, '通过【邀请好友注册】获取红包【50】元', 0);";
                    Yii::app()->db->createCommand($sqlrc)->execute();
                    $sqlsc2 = "select id from red_packet order by id desc limit 1";
                    $resultsc2 = Yii::app()->db->createCommand($sqlsc2)->queryAll();
                    $insid = intval($resultsc2[0]["id"])+12;
                    $sqlrc2 = "INSERT INTO `red_packet` (`id`, `sn`, `type`, `customer_id`, `from_type`, `to_type`, `sum`, `create_time`, `remark`, `note`, `lukcy_result_id`) VALUES (".$insid.", 'invite', 2, ".$insertID.", 10, 0, 50.00, '".($newcre-2*24*60*14+7*24*60*60+21*24*24*45)."', NULL, '通过【邀请好友注册】获取红包【50】元', 0);";
                    Yii::app()->db->createCommand($sqlrc2)->execute();
                    echo CJSON::encode(array('code' => 'success'));
                }                
                
            }  
        }
        /*
         * 绿色通道  新用户必中奖
         */
        public function newCustomerDo($luckyActId,$userName,$userId,$besideGennerZero = true, $besideid = array(0)){
            $customerModel = Customer::model()->findByPk($userId);            
            if($customerModel->first_do_lucky == 0){    //新用户第一次抽奖
                //获得必中的奖项
                $luckyPrizeModel =  LuckyPrize::model()->findByPk(Item::LUCKY_SMALL_PRIZE);
                if($luckyPrizeModel->prize_count_now == 0){
                    $retObj = array (
                                'id' => 0,
                                'prize_name' =>"",
                                'all' => 0,
                                'prize_level_name' => 0,
                                'cust_result_id' => 0,
                                //'angle'=>Item::$lucky_thanks_angle,
                    );
                    $result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
                    return $result;
                }
                $retObj = array (
                                'id' => intval(Item::LUCKY_SMALL_PRIZE),
                                'prize_name' => $luckyPrizeModel->prize_name,
                                'all' => $luckyPrizeModel->prize_count_all,
                                'prize_level_name' => $luckyPrizeModel->prize_level_name,
                                //'angle'=>array("min"=>$luckyPrizeModel->angle_start,"max"=>$luckyPrizeModel->angle_end),
                                'rednum'=>0.8,
                );
                $redInfo=array('num'=>0,"isgivered"=>0);
                $redPackage=new LuckyRedEnvelope();
                $getRed=$redPackage->gennerRedPackage3($userId, Item::LUCKY_SMALL_PRIZE);
                $getRed=floatval($getRed);
                //TODO 更改红包,大奖发不发-中奖结果记录的记录
                if($getRed==0){ //产生红包失败，转给予谢谢参与
                    $retObj = array (
                                'id' => 0,
                                'prize_name' =>"",
                                'all' => 0,
                                //'prize_picture' => 0,
                                'prize_level_name' => 0,
                                'cust_result_id' => 0,
                                //'angle'=>array("min"=>0,"max"=>0),
                               // 'angle'=>Item::$lucky_thanks_angle,
                    );
                    $result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
                    return $result;
                }else{
                    $items = array(
                            'customer_id' =>$userId,//登录用户的ID
                            'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,//红包获取方式，抽奖=3;物业欠费=2，预缴费=1
                            'sum' =>$getRed,//红包金额,
                            'sn' => Item::LUCKY_ACT_ID,//订单号(from_type=1or2的情况下)或活动ID(from_type=3的情况下)
                    );
                    $redPacked = new RedPacket();
                    $ret=$redPacked->addRedPacker($items,$redInfo);
                    if($ret){
                            $redInfo['num']=$getRed;
                            $redInfo['isgivered']=1;
                    }else{
                            $redInfo['num']=$getRed;
                            $redInfo['isgivered']=0;
                            Yii::log("抽奖中得红包,送红包失败".json_encode($items),CLogger::LEVEL_ERROR,'colourlife.core.lucky');
                    }
                    $customerModel->balance += 0.8;
                    $customerModel->first_do_lucky = 1;
                    $customerModel->save();
                    $this->custDoOperation ( $userName, $userId,$luckyActId, Item::LUCKY_SMALL_PRIZE,$redInfo,$besideGennerZero,$besideid );
                    $result = array ("success" => 1,  "data" => array ("bingo" => 1, "msg" => "", "result" => $retObj) );
                    return $result;
                }                
            }else{
                return false;
            }
        }

    /**
     * 获取用户所在地区的城市对应到region中的id
     * @param 用户id $userId
     */
    public function getUserCity($userId){
//      $conn=Yii::app()->db;
//      $sql="SELECT c.`parent_id` FROM `customer` a
//              RIGHT JOIN  `community` b ON (a.`community_id`=b.`id`)
//              RIGHT JOIN `region` c ON (b.`region_id`=c.`id`)
//              WHERE a.`id`=".$userId;
//      $comm=$conn->createCommand($sql);
//      //$row=$comm->queryRow();
//      //$row=$comm->queryColumn();
//      $row=$comm->queryScalar();
//      return intval($row);
    }


    /**
     * 根据key获取config中的值
     * @param $key
     */
    public static function getConfigValue($key){
//      $loginNum=1;    //登录送抽奖次数
//      $orderNum=1;    //购买支付送抽奖次数
//      $complainNum=1; //投诉送次数
//      $personalRepainNum=1;   //报修送次数
//      $publicRepainNum=1;     //公共报修送
//      $intviteNum=1;      //邀请送
//      return intval(Config::model()->findByKey($key)->getVal());
    }
    public function actionViewcustomer(){ 
            // $community_id=55;
            // $build = Build::model()->find('community_id=:community_id', array(':community_id' => $community_id));
            // if($build){
            //     $build_id = $build->id;
            // }
            $this->renderPartial('export');
    }


    private function _confirmAction($id, $func, $msg, $returnUrl)
    {
        $action = $this->action->id;
        if (count($id) > 1 && Yii::app()->request->isPostRequest) {
            $this->checkBatchAccessWithCount(count($id));
            $can = true;
            foreach ($this->loadModels($id) as $model) {
                $model->setScenario($action);
                if (!$model->validate()) {
                    // 只要有一个项目验证未通过
                    $can = false;
                    $error = $this->errorSummary($model);
                    break;
                }
            }
            if ($can) {
                foreach ($this->loadModels($id) as $model) {
                    $func($model);
                }
            } else {
                throw new CHttpException(400, $error);
            }
        } else {
            $id = @$id[0]; // 强制转换
            $model = $this->loadModel($id);
            if (Yii::app()->request->isPostRequest) {
                $model->setScenario($action);
                if ($model->validate()) {
                    $func($model);
                    if (!isset($_GET['ajax'])) {
                        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $returnUrl);
                    } else {
                        return;
                    }
                } else {
                    if (isset($_GET['ajax'])) {
                        throw new CHttpException(400, $this->errorSummary($model));
                    }
                }
            }
            $this->_model = $model;
            $this->render('/shop/confirm', array(
                'model' => $model,
                'msg' => $msg,
            ));
        }
    }

    /**
     * 启用
     * @param $id
     * @throws CHttpException
     */
    public function actionEnable(array $id)
    {
        if (!$this->checkIsMyBelong($id))
            throw new CHttpException(403, "你没有权限执行该操作!");
        $this->_confirmAction($id, create_function('$a', '$a->enable();$a->save(false);'), array(
            'label' => '启用',
            'icon' => 'ok-sign',
        ), array('disable', 'id' => @$id[0]));
    }

    /**
     * 禁用
     * @param $id
     * @throws CHttpException
     */
    public function actionDisable(array $id)
    {
        if (!$this->checkIsMyBelong($id))
            throw new CHttpException(403, "你没有权限执行该操作!");
        $this->_confirmAction($id, create_function('$a', '$a->disable();$a->save(false);'), array(
            'label' => '禁用',
            'icon' => 'remove-sign',
        ), array('enable', 'id' => @$id[0]));
    }

    /**
     * 删除
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete(array $id)
    {
        if (!$this->checkIsMyBelong($id))
            throw new CHttpException(403, "你没有权限执行该操作!");
        $this->_confirmAction($id, create_function('$a', '$a->delete();'), array(
            'label' => '删除',
            'icon' => 'trash',
        ), array('index'));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $modelName = '')
    {
        $modelName = empty($modelName) ? $this->modelName : $modelName;
        $model = CActiveRecord::model($modelName)->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, '请求的内容不存在。');
        return $model;
    }

    /**
     * 取出多个模型对象用于批量操作
     * @param $array
     * @return array|CActiveRecord|mixed|null
     * @throws CHttpException
     */
    public function loadModels($array)
    {
        $criteria = new CDbCriteria();
        $criteria->addInCondition("id", $array);
        $models = CActiveRecord::model($this->modelName)->findAll($criteria);
        if (empty($models)) {
            throw new CHttpException(404, '请求的内容不存在。');
        }
        return $models;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $model->tableName() . '-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function checkParentExist($id)
    {
        if (!empty($id) && CActiveRecord::model($this->modelName)->findByPk($id) === null) {
            throw new CHttpException(404, '请求的内容不存在。');
        }
    }

    public function actionSpecial($id)
    {
        if (!$this->checkIsMyBelong($id))
            throw new CHttpException(403, "你没有权限执行该操作!");
        $model = $this->loadmodel($id);
        $this->_model = $model;
        $this->render('/speical/_set', array(
            'model' => $this->_model,
        ));
    }

    //添加周边特惠商家
    public function actionAddLifeBusiness($id)
    {
        if (!$this->checkIsMyBelong($id))
            throw new CHttpException(403, "你没有权限执行该操作!");
        $model = $this->loadModel($id);
        $this->_model = $model;

        $cateModel = LifeCategory::model()->enabled()->findAll();
        if (isset($_POST[$this->modelName])) {
            $model->attributes = $_POST[$this->modelName];
            $model->life_display_order = empty($_POST[$this->modelName]['life_display_order']) ? 0 : $_POST[$this->modelName]['life_display_order'];
            $model->setScenario('update');
            if ($model->save())
                $this->redirect(array('/life'));
        }

        $this->render('/life/create', array(
            'model' => $this->_model,
            'cateModel' => $cateModel,
        ));
    }

    public function actionBenefit($id)
    {
        if (!$this->checkIsMyBelong($id))
            throw new CHttpException(403, "你没有权限执行该操作!");
        $this->_model = $this->loadModel($id);
        $model = $this->loadModel($id, 'BenefitShop');
        //$model = SpBenefitShop::model()->findByPk($id);
        if (isset($_POST['BenefitShop'])) {
            $model->attributes = $_POST['BenefitShop'];
            $model->is_benefit = $model->is_benefit == 0 ? 1 : 0;
            $model->setScenario('update');
            if ($model->save())
                $this->redirect(array('special', 'id' => $id));
        }
        $this->render('/speical/_set_benefit', array(
            'model' => $model,
        ));
    }

    public function actionHouse($id)
    {
        if (!$this->checkIsMyBelong($id))
            throw new CHttpException(403, "你没有权限执行该操作!");
        $this->_model = $this->loadModel($id);
        $model = $this->loadModel($id, 'HouseShop');
        if (isset($_POST['HouseShop'])) {
            $model->attributes = $_POST['HouseShop'];
            $model->is_house = $model->is_house == 0 ? 1 : 0;
            $model->setScenario('update');
            if ($model->save())
                $this->redirect(array('special', 'id' => $id));
        }

        $this->render('/speical/_set_house', array(
            'model' => $model,
        ));
    }

    public function actionEducate($id)
    {
        if (!$this->checkIsMyBelong($id))
            throw new CHttpException(403, "你没有权限执行该操作!");
        $this->_model = $this->loadModel($id);
        $model = $this->loadModel($id, 'EducateShop');
        if (isset($_POST['EducateShop'])) {
            $model->attributes = $_POST['EducateShop'];
            $model->is_educate = $model->is_educate == 0 ? 1 : 0;
            $model->setScenario('update');
            if ($model->save())
                $this->redirect(array('special', 'id' => $id));
        }

        $this->render('/speical/_set_educate', array(
            'model' => $model,
        ));
    }

    public function actionRabbit($id)
    {
        if (!$this->checkIsMyBelong($id))
            throw new CHttpException(403, "你没有权限执行该操作!");
        $this->_model = $this->loadModel($id);
        $model = $this->loadModel($id, 'RabbitShop');
        if (isset($_POST['RabbitShop'])) {
            $model->attributes = $_POST['RabbitShop'];
            $model->is_rabbit = $model->is_rabbit == 0 ? 1 : 0;
            $model->setScenario('update');
            if ($model->save())
                $this->redirect(array('special', 'id' => $id));
        }

        $this->render('/speical/_set_rabbit', array(
            'model' => $model,
        ));
    }

    public function actionBreakfast($id)
    {
        if (!$this->checkIsMyBelong($id))
            throw new CHttpException(403, "你没有权限执行该操作!");
        $this->_model = $this->loadModel($id);
        $model = $this->loadModel($id, 'BreakfastShop');
        if (isset($_POST['BreakfastShop'])) {
            $model->attributes = $_POST['BreakfastShop'];
            $model->is_breakfast = $model->is_breakfast == 0 ? 1 : 0;
            $model->setScenario('update');
            if ($model->save())
                $this->redirect(array('special', 'id' => $id));
        }

        $this->render('/speical/_set_breakfast', array(
            'model' => $model,
        ));
    }

    public function actionSurrounding($id)
    {
        $model = $this->loadModel($id);
        $tab_id = Yii::app()->request->getParam('tab_id');
        if(BaseShop::SHOP_SURROUNDING != $model->surrounding_benefit){
            throw new CHttpException(404);
        }
        if(!empty($tab_id)){
            $model->surrounding_tab_id = $tab_id;
            if($content = SurroundingContent::model()->findByAttributes(array('shop_id' => $model->id, 'tab_id' => $model->surrounding_tab_id))){
                $model->app_content = $content->app_content;
                $model->content = $content->content;
            }
            else{
                $model->app_content = '';
                $model->content = '';
            }
        }
        if(isset($_POST[$this->modelName])){
            $model->setScenario('update');
            $model->attributes = $_POST[$this->modelName];
            if($model->saveSurroundingContent()){
                $this->redirect(array('view', 'id' => $model->id));
            }
        }
        $tab = SurroundingTab::model()->findAllByAttributes(array('surrounding_id' => $model->surrounding_cate_id));
        $this->_model = $model;
        $this->render('surrounding', array(
            'model' => $model,
            'tab' => $tab,
        ));
    }
    /*****************增加报修关联分类*************************/
    public function actionShopCateRelation($id){
        $shop = $this->loadModel($id);
        if(empty($shop)||!$shop->is_service_repair){
            throw new CHttpException(403,"你没有权限，无法继续访问！");
        }else{
            $this->_model = $shop;
            $model = new PersonalRepairsCategory('search');
            $this->render('/shop/local_category_relation',array(
                    'model'=>$model,
                    'shop'=>$shop,
                ));
        }
    }

    public function actionCreateRelation($id,array $categoryId)
    {
        $this->doActionConfirm2($id, $categoryId, create_function('$a', '$a->save();'), array(
                'label' => '增加',
                'icon' => 'check',
            ), array('shopCateRelation', 'id' => $id));
    }

    public function actionDeleteRelation($id, array $categoryId)
    {
        $this->doActionConfirm2($id, $categoryId, create_function('$a', '$a->cancel();'), array(
                'label' => '取消关联',
                'icon' => 'trash',
            ), array('shopCateRelation', 'id' => $id));
    }

    protected function loadModel2($shop_id,$id)
    {
        if($this->action->id == 'createRelation')
        {
            $model = new PersonalRepairsCateShopRelation();
            $model->repairs_cate_id = intval($id);
            $model->shop_id = intval($shop_id);
            return $model;
        }
        $criteria = new CDbCriteria();
        $criteria->addCondition('shop_id='. $shop_id);
        $criteria->addCondition('repairs_cate_id='. $id);
        $model = CActiveRecord::model('PersonalRepairsCateShopRelation')->find($criteria);
        $this->checkExists($model);
        return $model;
    }

    /**
     * 取出多个模型对象用于批量操作
     * @param $array
     * @return array|CActiveRecord|mixed|null
     * @throws CHttpException
     */
    protected function loadModels2($shop_id,$array)
    {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('repairs_cate_id', $array);
        $criteria->addCondition('shop_id='. $shop_id);
        $models = CActiveRecord::model('PersonalRepairsCateShopRelation')->findAll($criteria);
        foreach ($models as $model) {
            $this->checkExists($model);
        }
        return $models;
    }

    protected function doActionConfirm2($shop_id, $ids, $func, $msg, $returnUrl, $preScenario = '', $view = 'confirm')
    {
        $action = $this->action->id;
        $count = count($ids);
        if ($count > 1 && Yii::app()->request->isPostRequest) {
            $this->checkBatchAccessWithCount($count,'op_backend_shop_update');
            $can = true;
            $models = $this->loadModels2($shop_id, $ids);
            foreach ($models as $model) {
                $model->setScenario($preScenario . $action);
                if (!$model->validate()) {
                    // 只要有一个项目验证未通过
                    $can = false;
                    $error = $this->errorSummary($model);
                    break;
                }
            }
            if ($can) {
                foreach ($models as $model) {
                    $func($model);
                }
            } else {
                throw new CHttpException(400, $error);
            }
        } else {
            $id = @$ids[0]; // 强制转换
            $model = $this->loadModel2($shop_id, $id);
            if (Yii::app()->request->isPostRequest) {
                $model->setScenario($preScenario . $action);
                if ($model->validate()) {
                    $func($model);
                    if (isset($_GET['ajax'])) {
                        return;
                    }
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $returnUrl);
                } else {
                    if (isset($_GET['ajax'])) {
                        throw new CHttpException(400, $this->errorSummary($model));
                    }
                }
            }
            $this->model = $model;
            $this->render($view, array(
                    'model' => $model,
                    'msg' => $msg,
                ));
        }
    }
    /*****************增加报修关联分类结束*************************/

    public function actionAjaxSurroundingBenefit()
    {
        $category_id = Yii::app()->request->getParam('category_id');
        $shop_id = Yii::app()->request->getParam('id',0);
        $surrounding_benefit = Yii::app()->request->getParam('surrounding_benefit',0);
        if(BaseShop::SHOP_SURROUNDING == $surrounding_benefit){
            $cdb = new CDbCriteria();
            $cdb->compare('state',0)->compare('surrounding_id', $this->surrounding_id)->addCondition('shop_id = 0 OR shop_id = '.$this->id);
            $data = SurroundingTab::model()->findAll($cdb);
        }
        else{
            $data = Shop::model()->findAllByAttributes(array('is_deleted' => 0, 'state' => 0, 'life_cate_id' => $category_id));
        }

        echo CJSON::encode(array('data' => $data, 'selected' => $shop_id));
    }



}   