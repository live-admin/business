<?php
class ChristmasAppWebController extends CController {
	//copy from luckyController 
	
	private $_luckyCustCan = 0;
    private $_luckyTodayCan=0;
    private $_luckyActId = Item::LUCKY_ACT_ID;
    private $_username = "";
    private $_userId = 0;
	

	private function  isover(){
            $luckyAct=LuckyActivity::model()->findByPk($this->_luckyActId);
            if($luckyAct && ($luckyAct->end_date."23:59:59" < date("Y-m-d H:i:s")) ){
                return true;
            }
            return false;
    }

    private function  isstart(){
        $luckyAct=LuckyActivity::model()->findByPk($this->_luckyActId);
        if($luckyAct && ($luckyAct->start_date."23:59:59" < date("Y-m-d H:i:s")) ){
            return true;
        }
        return false;
    }

	
	
	/**
	 * index页面，显示抽奖奖项
	 */
       public function actionIndex() { echo F::getFrontendUrl();;exit;
        // if(!$this->isstart()){
        if(date('Y-m-d H:i:s')<='2014-12-20 23:59:59'){
            $layoutsList=array();            
            $data=LuckyLayout::model()->findAll("lucky_act_id=".$this->_luckyActId);
            foreach ($data as $value){
                    $layoutsList[$value['layout_index']]=$value;
            }
            $listResult = $this->getListData();
            $allJoin=LuckyCustomerOut::model()->count();
            $isGuest=Yii::app ()->user->isGuest;        
            if(! $isGuest){
                    $this->_userId = Yii::app ()->user->id;
                    $this->_username = Yii::app ()->user->name;
                    $luckyNum = 0;
                    $luckyCan = new LuckyCustCan ();
                    $result = $luckyCan->getCustCan ( $this->_username, $this->_userId, $this->_luckyActId );
                    if ($result) {
                            $this->_luckyCustCan =$result ['cust_can']<0?0:$result ['cust_can'];
                            $this->_luckyTodayCan= $result['cust_today_can']<0?0:$result['cust_today_can'];
                    }

            }else{
                $this->redirect ( Yii::app ()->user->loginUrl );
            }


            $customer = Customer::model()->findByPk($this->_userId);
            $community_id = $customer->community_id;
            if($customer){
                $secret = "DJKC#$%CD%des$";
                $argument = "";
                $str = "";

                $argument .= "bno=&bsecret=&userid=".$customer->id."&username=".$customer->username."&mobile=".$customer->mobile."&password=".md5($customer->id)."&cid=".$customer->community_id."&cname=".urlencode($customer->CommunityName)."&caddress=".urlencode($customer->CommunityAddress)."&cprovince=".urlencode($customer->community->region->parent->parent->name)."&ccity=".urlencode($customer->community->region->parent->name)."&cdistrict=".urlencode($customer->community->region->name);

                $str .= "bnobsecretuserid".$customer->id."username".$customer->username."mobile".$customer->mobile."password".md5($customer->id)."cid".$customer->community_id."cname".$customer->CommunityName."caddress".$customer->CommunityAddress."cprovince".$customer->community->region->parent->parent->name."ccity".$customer->community->region->parent->name."cdistrict".$customer->community->region->name;
                //echo $argument;

                $sign = strtoupper(md5($secret.$str.$secret));

                $argument .= '&sign='.$sign;
                //echo $argument;
                $completeURL = $argument;           
            }
			
			
			
			$this->checkLogin();
			$listResutl = $this->getListData();    
			//$list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id in (".Item::LUCKY_ACT_ID."-4,".Item::LUCKY_ACT_ID."-3,".Item::LUCKY_ACT_ID."-2,".Item::LUCKY_ACT_ID."-1,".Item::LUCKY_ACT_ID.") and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");
			//$this->renderPartial("guagua_result", array ("listResutl"=>$listResutl,"list"=>$list));
			
			
			

                $this->renderPartial( "indexWeb",
                        array ( "luckyCustCan" => $this->_luckyCustCan,
                            "luckyTodayCan" => $this->_luckyTodayCan,
                            "allJoin"=>($allJoin+128),
                            "layoutList"=>$layoutsList,
                            "custId"=>$this->_userId,
                            "isGuest"=>$isGuest,
                            "href"=>Yii::app ()->user->loginUrl,
                            "isover"=>$this->isover(),
                            "newLuckyInfo"=>$this->getNewLuckyInfo(),
                            "listResult"=>$listResult,
                            'completeURL' =>$completeURL,
							"listResutl"=>$listResutl,
                 ));
            }else{
                 $this->renderPartial("indexWeb");
            }

    }

    //抢月饼－－－－－－－－－－－

    public function actionRob(){
        if (!Yii::app()->getRequest()->getIsPostRequest())
            throw new CHttpException(405, 'Method Not Allowed');
        $this->checkLogin();
        $this_act = SetMoonCakes::model()->getThisActivity();
        if($this_act){
            $this_act->clicks+=1;
            $this_act->save();
            $res = MoonCakesResult::model()->rob($this_act->id,$this->_userId);
            echo CJSON::encode($res);     //0没抢到；1抢到了；2今天已抢过不能再抢 3红包金额不足
        }else
            echo CJSON::encode(4);//4活动暂未开始
    }

    public function actionRobRule(){
        $this->checkLogin();
        $this->renderPartial("rule");
    }


    public function actionFruitGetWeb(){
        $this->checkLogin();
        $this->renderPartial("fruitWeb");
    }



    //获取本场剩余份数
    public function getRemaining(){
        $activity = SetMoonCakes::model()->getThisActivity();
        if($activity){
            if($activity->remaining_number == 5 || $activity->remaining_number == 10){
                $remaining_number = $activity->remaining_number."0";
            }else{
                if($activity->remaining_number == 0){
                    $remaining_number = 0;
                }else{
                    $remaining_number = ($activity->remaining_number>1)?($activity->remaining_number-1).rand(0,9):rand(0,9);
                }
            }
        }else{
            $remaining_number = 0;
        }
        return $remaining_number;
    }

    //获取本场剩余时间
    public function getTimeRemaining(){
        $model = SetMoonCakes::model()->getThisActivity();
        return $model?$model->end_time:date("Y-m-d H:i:s");
    }

    //获取距离下场开始时间
    public function getNextTime(){
        $model = SetMoonCakes::model()->getNextActivity();
        return $model?$model->start_time:date("Y-m-d H:i:s");
    }


    //确定收月饼地址
    public function actionFillReceiving(){
        $this->checkLogin();
        $linkman = $_POST['linkman'];
        $tel = $_POST['tel'];
        $criteria =new CDbCriteria;
        //$criteria->addCondition("customer_id=".$this->_userId);
        $criteria->compare('customer_id', $this->_userId);
        $criteria->order = "id desc";
        $model = MoonCakesResult::model()->find($criteria);
        $model->linkman = $linkman;
        $model->tel = $tel;
        $model->address = $model->CustomerAddress;
        $model->save();
        echo CJSON::encode(1);
    }


    //确定收品果精品地址
    public function actionFruit_FillReceiving(){
        $this->checkLogin();
        $id = $_POST['id'];
        $id=intval($id);
        $linkman = $_POST['linkman'];
        $tel = $_POST['tel'];
        if($this->validatephone($tel)){
            $criteria =new CDbCriteria;
            //$criteria->addCondition("id=".$id);
            $criteria->compare('id', $id);
            //$criteria->addCondition("cust_id=".$this->_userId);
            $criteria->compare('cust_id', $this->_userId);
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



    public function actionFlushByAjax(){
        $over = $this->isOver();
        $able = 1;      //1代表活动正在进行     0代表休息
        $timeRemainingNext = $this->getNextTime();   //下一场抢月饼活动
        if(10==date("H") && date("H")==16){
            $timeRemaining = $this->getTimeRemaining();        //本场剩余时间
            $m = intval (date("m",strtotime($timeRemaining)));   //月
            $d = intval (date("d",strtotime($timeRemaining)));   //日
            $h = intval (date("H",strtotime($timeRemaining)));   //时
            $i = intval (date("i",strtotime($timeRemaining)));   //分
            $s = intval (date("s",strtotime($timeRemaining)));   //秒
            if(date("i") > 50){                              //本场抢完   开始倒计时下一场
                $able = 0;
                $m = intval (date("m",strtotime($timeRemainingNext)));       //月
                $d = intval (date("d",strtotime($timeRemainingNext)));       //日
                $h = intval (date("H",strtotime($timeRemainingNext)));   //时
                $i = intval (date("i",strtotime($timeRemainingNext)));   //分
                $s = intval (date("s",strtotime($timeRemainingNext)));   //秒
            }
        }else{
            $able = 0;
            $m = intval (date("m",strtotime($timeRemainingNext)));       //月
            $d = intval (date("d",strtotime($timeRemainingNext)));       //日
            $h = intval (date("H",strtotime($timeRemainingNext)));   //时
            $i = intval (date("i",strtotime($timeRemainingNext)));   //分
            $s = intval (date("s",strtotime($timeRemainingNext)));   //秒

        }
        $res = array();
        $res['success'] = "ok";
        $res['remaining'] = $this->getRemaining();  //本场剩余个数
        $res['timeRemainingNext'] = $timeRemainingNext?date("d日H",strtotime($timeRemainingNext)):date("d日H");   //下场抢月饼活动
        $res['able'] = $able;
        $res['m'] = $m;
        $res['d'] = $d;
        $res['h'] = $h;
        $res['i'] = $i;
        $res['s'] = $s;
        $res['over'] = $over;
        echo CJSON::encode($res);
    }

    //判断活动是不是已经结束
    public function isRobOver(){
        $lastModel = SetMoonCakes::model()->find(array('order'=>'id desc'));
        if(date("Y-m-d H:i:s") >= $lastModel->end_time){
            return true;
        }else{
            return false;
        }
    }

    //抢月饼结束－－－－－－－－－－－－－－

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
	
	/**
	 * 抽奖
	 */
	public function actionDoLucky() {
	    $this->checkLogin();
	    if($this->isover()){
	    	exit();
	    }
            
		//$result = $this->luckyOperation ();
		$luckyOper=new LuckyOperation();
		$besideids=array(item::LUCKY_THANKS_ID);
		$result=$luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan,$this->_luckyTodayCan,$this->_username,$this->_userId,true,$besideids);
		// if ($result && $result ['success'] == 1) {
		// 	//更改角度值，只取中间值
		// 	$min=$result ['data'] ['result']['angle']['min'];
		// 	$max=$result ['data'] ['result']['angle']['max'];
		// 	$minArr=explode(",", $min);
		// 	$maxArr=explode(",", $max);
		// 	$index=0;
		// 	$index=rand(0, count($min)-1);
		// 	$minEnd=intval($minArr[$index]);
		// 	$maxEnd=intval($maxArr[$index]);
		// 	$result ['data'] ['result']['angle']=intval(($minEnd+$maxEnd)/2);
		// }
		
		echo CJSON::encode ( $result );
	}



    /**
     * 八月抽奖
     */
    public function actionDoMoonLucky() {
        $this->checkLogin();
        if($this->isover()){
            exit();
        }
            
        $luckyOper=new LuckyOperation();
        $besideids=array(item::LUCKY_THANKS_ID);
        $result=$luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan,$this->_luckyTodayCan,$this->_username,$this->_userId,true,$besideids);
        
        echo CJSON::encode ( $result );
    }


    public function actionDoShakeLucky() {
        $this->checkLogin();
        if($this->isover()){
            exit();
        }
        
        $luckyOper=new LuckyOperation();
        $besideids=array(Item::LUCKY_THANKS_ID);
        $result=$luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan,$this->_luckyTodayCan, $this->_username,$this->_userId,true,$besideids);
        //print_r($result);
		$result['data']['result']['prizeid'] = 76;
		$result['data']['result']['prize_level_name'] = '名字';
		$result['data']['result']['prize_name'] = '名字1';
		$result['data']['result']['id'] = 72;
		$result['data']['result']['cust_result_id'] = 1;
		$result['data']['result']['all'] = 2;
        echo CJSON::encode ( $result );
    }


    private function checkLogin(){
//         if (Yii::app ()->user->isGuest) {
//          $this->redirect ( Yii::app ()->user->loginUrl );
//      }else {
//          $this->_luckyActId = isset ( $_REQUEST ['actid'] ) ? ($_REQUEST ['actid']) : (2);
//             $this->_userId = Yii::app ()->user->id;
//             $this->_username = Yii::app ()->user->name;
//             $luckyNum = 0;
//             $luckyCan = new LuckyCustCan ();
//             $result = $luckyCan->getCustCan ( $this->_username, $this->_userId, $this->_luckyActId );
//             if ($result) {
//                 $this->_luckyCustCan = $result ['cust_can'];
//             }
//      }

        //未登录也可以进入，点击抽奖时才判断登录
        //同12月幸福中国行活动  /frontend/controllers/LuckyController

        if (Yii::app ()->user->isGuest) {
            $this->redirect ( Yii::app ()->user->loginUrl );
            // $result = array ("success" => 0,"data" => array ("location"=>1,"href"=>Yii::app ()->user->loginUrl ) );
            // echo CJSON::encode($result);
            // exit();
        }else {
            $this->_userId = Yii::app ()->user->id;
            $this->_username = Yii::app ()->user->name;
            $luckyNum = 0;
            $luckyCan = new LuckyCustCan ();
            $result = $luckyCan->getCustCan ( $this->_username, $this->_userId, $this->_luckyActId );
            if ($result) {
                $this->_luckyCustCan = $result ['cust_can']<0?0:$result ['cust_can'];
                $this->_luckyTodayCan= $result['cust_today_can']<0?0:$result['cust_today_can'];
            }
        }
        
    }


    public function actionlingqutaikang() {
        $this->checkLogin();
        if(isset($_GET['id'])){
            $this->renderPartial("taikang_web",array('lucky_result_id' =>$_GET['id']));
        }else{
            $this->renderPartial("taikang_web");
        }       
        
    }
    	

    public function getCrabRightData(){
        $criteria = new CDbCriteria;
        $criteria->addCondition("is_right=1"); 
        $criteria->order ="id desc"; 
        $criteria->limit = 20; 
        $dataResult = PerfectCrabOutcome::model()->findAll($criteria);
        $list = array();   
        for($i=0;$i<count($dataResult);$i++){
            $name=empty($dataResult[$i]->customer->name)?(""):(F::msubstr($dataResult[$i]->customer->name,0,1)."**"); 
            $mobile = F::msubstr($dataResult[$i]->customer->mobile,0,3)."****".F::msubstr($dataResult[$i]->customer->mobile,7,4);        
            $list[]=array(
                'msg'=>"恭喜手机号".$mobile."的业主".$name."获得了大闸蟹一份",
            );
    
        }
        return $list;
    }
    
    public function actionPerfectCrabRuleWeb() {
    	$this->checkLogin();
    	$this->renderPartial("crab_RuleWeb");
    }


    public function actionPerfectCrabResultWeb(){
        $this->checkLogin();
        $listResut = $this->getCrabRightData();
        $criteria = new CDbCriteria;
        //$criteria->addCondition("customer_id=".$this->_userId); 
        $criteria->compare('customer_id', $this->_userId);
        $criteria->order = "id desc";
        $data=PerfectCrabOutcome::model()->findAll($criteria);
        $list=array();
        foreach ($data as $key=>$value){
            $list[$key]['id'] = $value->id;
            $list[$key]['mycode'] = perfectCrabOutcome::model()->getFullDrawCode($value->mycode);
            $list[$key]['is_right'] = $value->is_right;
            $list[$key]['lucky_date'] = date('Y-m-d H:i:s',$value->create_time);
            $list[$key]['right_result'] = perfectCrabOutcome::getFullDrawCode($value->set_perfect_crab->right_result);
        }

        $this->renderPartial("crab_resultWeb",
                array("list"=>$list,'listResut'=>$listResut)
        );
    }



     public function actionRobPerfectCrab(){
        $status = $this->new_act_status();
        //var_dump($status);
        $this->checkLogin();
        $timeRemainingNext = $this->getCrabNextTime();   //下一场完美蟹逅活动   格式"2014-09-24 05:01:13" 

        if($status == 3){
            $timeRemaining = $this->getCrabTimeRemaining();          //本场剩余时间
            //var_dump($timeRemaining);
            $m = intval (date("m",strtotime($timeRemaining)));   //月
            $d = intval (date("d",strtotime($timeRemaining)));   //日
            $h = intval (date("H",strtotime($timeRemaining)));   //时
            $i = intval (date("i",strtotime($timeRemaining)));   //分
            $s = intval (date("s",strtotime($timeRemaining)));   //秒
        }

        if($status == 1 || $status == 2){           
            $m = intval (date("m",strtotime($timeRemainingNext)));   //月
            $d = intval (date("d",strtotime($timeRemainingNext)));   //日
            $h = intval (date("H",strtotime($timeRemainingNext)));   //时
            $i = intval (date("i",strtotime($timeRemainingNext)));   //分
            $s = intval (date("s",strtotime($timeRemainingNext)));   //秒 

        }
        $allJoin=PerfectCrabOutcome::model()->count();
        if(date('Y-m-d H:i:s',time())>="2014-10-09 23:59:59"){ //1411747199
            $this->renderPartial("crabWeb3",
              array('m' => $m,
                  'd' => $d,
                  'h' => $h,
                  'i' => $i,
                  's' => $s,
                  'timeRemainingNext' => $timeRemainingNext,    //下场完美蟹逅活动（时间）
                  'status' => $status,
                  'allJoin' => $allJoin+1123,
                  ));
        }else{
            $this->renderPartial("crabWeb2",
              array('m' => $m,
                  'd' => $d,
                  'h' => $h,
                  'i' => $i,
                  's' => $s,
                  'timeRemainingNext' => $timeRemainingNext,    //下场完美蟹逅活动（时间）
                  'status' => $status,
                  'allJoin' => $allJoin+1123,
                  ));
        }
        
    }



    public function actionRobCrab(){
        if (!Yii::app()->getRequest()->getIsPostRequest())
            throw new CHttpException(405, 'Method Not Allowed');
        $this->checkLogin();
        $this_act = SetPerfectCrab::model()->getThisActivity();
        if($this_act){
            $this_act->clicks+=1;
            $this_act->save();
            $customerModel=Customer::model()->findByPk($this->_userId);
            if($customerModel->getBalance()<0.1){
               echo CJSON::encode(array('ok' =>3));//红包余额不足   
            }else{
                $res = PerfectCrabOutcome::model()->rob($this_act->id,$this->_userId);
                echo CJSON::encode($res);     //0没抢到(没扣红包)；1抢到了；2抢光了(号码不够)；3红包金额不足 5没抢到(扣了红包)
            }    
        }else
            echo CJSON::encode(array('ok' =>4));//4活动暂未开始
    }



    //获取本场剩余时间
    public function getCrabTimeRemaining(){
        $model = SetPerfectCrab::model()->getThisActivity();
        return $model?$model->end_time:date("Y-m-d H:i:s");
    }
    
    //获取距离下场开始时间
    public function getCrabNextTime(){
        $model = SetPerfectCrab::model()->getNextActivity();
        return $model?$model->start_time:$model;
    }



    //活动进行中  1、未开始  2、已结束  3、进行中
    public function act_status(){
        $startModel = SetPerfectCrab::model()->find(array('condition'=>'state=0 and is_deleted=0','order'=>'id asc'));
        $lastModel = SetPerfectCrab::model()->find(array('condition'=>'state=0 and is_deleted=0', 'order'=>'id desc'));
        //var_dump($lastModel);
        if(date("Y-m-d H:i:s") < $startModel->start_time){
            return 1;
        }else if(date("Y-m-d H:i:s") > $lastModel->end_time){
            return 2;
        }else{
            return 3;
        }
    }    

    //活动进行中  1、未开始  2、已结束  3、进行中
    public function new_act_status(){
        $startModel = SetPerfectCrab::model()->find(array('condition'=>'id>=14 and state=0 and is_deleted=0','order'=>'id asc'));
        $lastModel = SetPerfectCrab::model()->find(array('condition'=>'state=0 and is_deleted=0', 'order'=>'id desc'));
        //var_dump($lastModel);
        if(date("Y-m-d H:i:s") < $startModel->start_time){
            return 1;
        }else if(date("Y-m-d H:i:s") > $lastModel->end_time){
            return 2;
        }else{
            return 3;
        }
    } 


    public function actionShakeRuleWeb(){
    	$this->checkLogin();
		$this->renderPartial("shake_RuleWeb");
	}


    public function actionGuaguaRuleWeb(){
        $this->checkLogin();
        $this->renderPartial("guagua_RuleWeb");
    }

    public function actionGuagua_heimei(){
        $this->checkLogin();
        $this->renderPartial("heimeijiu_shuoming");
    }

    public function actionGuaguas_5000(){
        $this->checkLogin();
        $this->renderPartial("guagua_rule5000");
    }

    public function actionShake_5000(){
        $this->checkLogin();
        $this->renderPartial("shake_rule5000");
    }


    public function actionGuaguaResultWeb(){
        $this->checkLogin();
        $listResutl = $this->getListData();    
        $list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id in (".Item::LUCKY_ACT_ID."-4,".Item::LUCKY_ACT_ID."-3,".Item::LUCKY_ACT_ID."-2,".Item::LUCKY_ACT_ID."-1,".Item::LUCKY_ACT_ID.") and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");
        $this->renderPartial("guagua_result", array ("listResutl"=>$listResutl,"list"=>$list));
    }




	public function actionShakeResultWeb(){
        $this->checkLogin();
        $listResutl = $this->getListData();    
        $list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id in (".Item::LUCKY_ACT_ID."-3,".Item::LUCKY_ACT_ID."-2,".Item::LUCKY_ACT_ID."-1,".Item::LUCKY_ACT_ID.") and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");
        $this->renderPartial("shake_ResultWeb", array ("listResutl"=>$listResutl,"list"=>$list));
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

        if (!Yii::app()->getRequest()->getIsPostRequest())
            throw new CHttpException(405, 'Method Not Allowed');
        $this->checkLogin();    

        $criteria = new CDbCriteria;
        if(isset($_POST['lucky_result_id']) && is_numeric($_POST['lucky_result_id'])){
            //$criteria->addCondition(" lucky_result_id=".$_POST['lucky_result_id']." and customer_id=".$this->_userId);
            $criteria->compare('lucky_result_id', intval($_POST['lucky_result_id']));
            $criteria->compare('customer_id', $this->_userId);
        } else if(!isset($_POST['lucky_result_id'])){
            //$criteria->addCondition("customer_id=".$this->_userId);
             $criteria->compare('customer_id', $this->_userId);
            $criteria->order = "id desc";
        } else{
            //var_dump('www');
            throw new CHttpException(405, 'Invalid Operation');
        }
        
        $model = TaikangLife::model()->find($criteria);
        if($model){
            $type = $_POST['type'];
            $name = $_POST['name'];
            $identity = $_POST['identity'];
            $mobile = $_POST['mobile'];
            $email = $_POST['email'];
            $model->name = $name;
            $model->identity = $identity;
            $model->mobile = $mobile;
            $model->email = $email;
            $model->type = $type;
            $model->save();
            echo CJSON::encode(array('pass'=>1));
        }else{
            echo CJSON::encode(array('pass'=>0));
        }    
        
        

    }

	
	public function actionLotteryrule(){
		$this->renderPartial("throughRuleWeb");
	}

    public function actionInvite(){
        $this->renderPartial("invite");
    }
	
	public function actionMylottery(){
        if (Yii::app ()->user->isGuest) {
            $this->redirect ( Yii::app ()->user->loginUrl );
        }
		$this->checkLogin();
	
		$list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");

		$this->renderPartial("lotterylistWeb",
				array("list"=>$list)
		);
	}



    public function actionMyMoonlottery(){ 
        $this->checkLogin();
        $data=MoonCakesResult::model()->findAll("customer_id=".$this->_userId);
        
        $listMoon=array();
        foreach ($data as $key=>$value){
            $listMoon[$key]['id'] = $value->id;
            $listMoon[$key]['status'] = $value->status;
            $listMoon[$key]['lucky_date'] = date('Y-m-d H:i:s',$value->create_time);
            $listMoon[$key]['prize_name'] = "彩生活月饼一盒";
        }

        $this->renderPartial("mooncakeResultWeb",
                array("listMoon"=>$listMoon)
        );
    }




    //查看我晋级竞猜
    public function actionMyPromotionGuess(){
        if (Yii::app ()->user->isGuest) {
            $this->redirect ( Yii::app ()->user->loginUrl );
        }
        $isGuest=Yii::app ()->user->isGuest;

        $guessList=array();

        $params=array(
            'condition'=>'customer_id=:id AND teams_promotion_id<5',
            'params'=>array(':id'=>Yii::app ()->user->id),
            'order'=>'teams_promotion_id desc',
        );
        $findAll=CustomerPromotion::model()->findAll($params);
        if(!$findAll)
            $this->redirect(array('index'));
        foreach ($findAll as $value){
            $guessList[$value['teams_promotion_id']]=$value;
        }

        $this->renderPartial( "myGuess",
            array (
                "custId"=>Yii::app ()->user->id,
                "isGuest"=>$isGuest,
                "href"=>Yii::app ()->user->loginUrl,
                "isover"=>$this->isover(),
                'guessList'=>$guessList,
            ));
    }

    //查看我王者竞猜
    public function actionMyWinnerGuess(){
        if (Yii::app ()->user->isGuest) {
            $this->redirect ( Yii::app ()->user->loginUrl );
        }
        $isGuest=Yii::app ()->user->isGuest;

        $model =CustomerPromotion::model()->find("customer_id=:id AND teams_promotion_id=:pid",array(':id'=>Yii::app ()->user->id,':pid'=>5));
        if(!$model)
            $this->redirect(array('index'));

        $this->renderPartial( "myWinnerGuess",
            array (
                "custId"=>Yii::app ()->user->id,
                "isGuest"=>$isGuest,
                "href"=>Yii::app ()->user->loginUrl,
                "isover"=>$this->isover(),
                'guess'=>$model,
            ));
    }

	
    public function actionShuomingw(){
        $this->renderPartial("shuomingWeb");
    }
    
    public function actionDajiangshuomingw(){
        $this->renderPartial("dajiangshuomingWeb");
    }

	public function actionHowgethb(){
		$this->renderPartial("howgethb");
	}
	
	public function actionHowgetit(){
		$this->renderPartial("howgetit");
	}
	public function actionHowuse(){
		$this->renderPartial("howuse");
	}
	
	public function actionBieyangcheng(){
		$this->renderPartial("bieyangcheng");
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
		$userId=Yii::app()->user->id;
// 		$lucky=new LuckyOperation();
// 		$cityId=$lucky->getUserCity($userId);
// 		//$cityId=$cityId['parent_id'];
// 		var_dump($cityId);
// 		$customer=Customer::model()->findByPk($userId);
// 		$branchId=$customer->community->branch->id;
// 		//var_dump($branchId);
// 		var_dump($branchId);
// 		$luckyOper=new LuckyOperation();
// 		$b=$luckyOper->doLucky(2, 10, 10, "lei_1430", 29729);
		
// 		Yii::log("======".json_encode($b),CLogger::LEVEL_ERROR);
		//PayLib::order_paid("3000000130720125107603");
	}
        
        
        /**
	 * 获取最新中奖用户
	 */
        public function getNewLuckyInfo(){
            $listJia=array(
                '恭喜金桔苑业主卢**获得了0.18元红包',
                '恭喜金桔苑业主雷**获得了68.00元红包',
                '恭喜碧水龙庭业主谢**获得了0.18元红包',
                '恭喜南国丽园业主王**获得了雅士利',
            );
            $conditon="lucky_act_id=".$this->_luckyActId." and prize_id!=".Item::LUCKY_THANKS_ID." AND(isred=0 OR (isred=1 AND rednum>10 AND rednum<500)) order by id desc limit 20";
            $data =  LuckyCustResult::model()->findAll($conditon);
            if(count($data) > 6){
                $listJia = array();            
            }
            foreach($data as $_v){
                if($_v->isred == 1){
                    $listJia[] = "恭喜".$_v->community->name."业主".F::msubstr($_v->receive_name,0,1)."**获得了".$_v->rednum."元红包";
                }else{
                    $listJia[] = "恭喜".$_v->community->name."业主".F::msubstr($_v->receive_name,0,1)."**获得了".$_v->prize->prize_name;
                }
            }
            return $listJia;
            
        }

	/**
	 * 获取最新中奖用户
	 */
	public function actionGetUserNewListJson(){
		//$this->checkLogin();
		$result=array("success"=>1,'data'=>array('msg'=>'系统错误'));
		//倒叙查询最近N条记录
		$conditon="lucky_act_id=".$this->_luckyActId." AND(isred=0 OR (isred=1 AND rednum>10 AND rednum<500)) order by id desc limit 7";
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
						'msg'=>"恭喜".$value['public_info']."业主".$name."获得了".$value->prize->prize_name."大礼包",
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
	
	public function actionWeixinShare(){
		$this->renderPartial("weixinShare");
	}
	



    
    /**
     * 获得资讯列表
     */
    private function getZixun(){
    	$imgList=array(
    			array("title"=>'',"src"=>F::getStaticsUrl('/common/images/luckyAppWeb/zixun_img1.jpg')),
    			array("title"=>'',"src"=>F::getStaticsUrl('/common/images/luckyAppWeb/zixun_img2.jpg')),
    			array("title"=>'',"src"=>F::getStaticsUrl('/common/images/luckyAppWeb/zixun_img3.jpg')),
    			array("title"=>'',"src"=>F::getStaticsUrl('/common/images/luckyAppWeb/zixun_img4.jpg')),
    			array("title"=>'',"src"=>F::getStaticsUrl('/common/images/luckyAppWeb/zixun_img5.jpg')),
    			array("title"=>'',"src"=>F::getStaticsUrl('/common/images/luckyAppWeb/zixun_img6.jpg')),
    			array("title"=>'',"src"=>F::getStaticsUrl('/common/images/luckyAppWeb/zixun_img7.jpg')),
    			array("title"=>'',"src"=>F::getStaticsUrl('/common/images/luckyAppWeb/zixun_img8.jpg')),
    	);
    	$infoList=array(
    		array("title"=>'彩生活升级物业服务体验 百万红包大礼一触即发',"src"=>'http://bslt.c.colourlife.com/notify/118'),
    		array("title"=>'彩生活APP百万红包抽奖在即 社区服务盛大回馈拭目以待',"src"=>'http://bslt.c.colourlife.com/notify/117'),
    		array("title"=>'传统行业创新成潮 彩生活搭上移动互联网快车',"src"=>'http://bslt.c.colourlife.com/notify/116'),
    		array("title"=>'指尖上畅想未来 彩生活APP乐彩世界 ',"src"=>'http://bslt.c.colourlife.com/notify/115'),
    		array("title"=>'彩生活APP精准服务引领潮流 移动互联网社区生活启幕',"src"=>'http://bslt.c.colourlife.com/notify/114'),
    		array("title"=>'服务到家 乐彩生活',"src"=>'http://bslt.c.colourlife.com/notify/113'),
    		array("title"=>'彩生活：社区服务的最后一公里',"src"=>'http://bslt.c.colourlife.com/notify/112'),
    		array("title"=>' 多重视角下的彩生活模式',"src"=>'http://bslt.c.colourlife.com/notify/111'),
    		array("title"=>'社区服务大有可为——由彩生活引发的思考',"src"=>'http://bslt.c.colourlife.com/notify/110'),
    		array("title"=>'我有一个梦想——彩生活总裁唐学斌的社区服务运营之路',"src"=>'http://bslt.c.colourlife.com/notify/109'),
    	);
    	$result=array("imgs"=>$imgList,"infos"=>$infoList);
    	return $result;
    }
    
    
    
    //记录中了电信充值卡
    public function actionTelecom(){
        $mobile = $_POST['mobile'];
        if(Telecom::model()->checkMobile($mobile)){
            $criteria =new CDbCriteria;
            //$criteria->addCondition("customer_id=".Yii::app ()->user->id); 
            $criteria->compare("customer_id",Yii::app ()->user->id);
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


        
    //获取业主地址、手机号、名字
    public function actionGetCustomerInfo(){
        $this->checkLogin();
        $res = array();
        $customerModel = Customer::model()->findByPk($this->_userId);
        if($customerModel){
            $res['tel'] = $customerModel->mobile;
            $res['name'] = $customerModel->name;
            $res['address'] = $customerModel->community->name.$customerModel->build->name.$customerModel->room;
        }
        echo CJSON::encode($res);
    }
    
    
    //5000元大奖说明
    public function actionIssuelotteryWeb(){
        $this->renderPartial("issuelotteryWeb");
    }
    
    public function actionWorldCupRule(){
        $this->renderPartial("worldCupRule");
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
    
    //查看世界杯结果
    public function actionLookAllResultWeb(){
        $this->checkLogin();
        $customerStatistics = CustomerOutcome::model()->getCustomerStatistics($this->_userId);   //业主猜中的次数
        $customerTotal = CustomerOutcome::model()->getCustomerTotal($this->_userId);             //业主竞猜总数
        if(empty($_GET)){
            $customerTotalRecord = CustomerOutcome::model()->getCustomerRecodeByPage($this->_userId);  //业主竞猜所有的记录
        }else{
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

        $this->renderPartial("lookAllResultWeb",
                array('customerStatistics'=>$customerStatistics,
                    'customerTotal'=>$customerTotal,
                    'redPacket' => $redPacket,
                    'lack' => $lack,
                    'customerTotalRecord' => $customerTotalRecord,
                    'promotionList' => $promotionList,
                    'winnerList' => $winnerList,
          ));
    }
	
	
	function actionHtml(){
	    $tpl = Yii::app()->request->getParam('tpl');
		$template = array('rule', 'shanjiaozhou', 'shuoming_5000', 'shuoming_fengchidao', 'shuoming_haoya', 'shuoming_huameida', 'shuoming_lizhi', 'shuoming_luofushang', 'shuoming_qingfeng', 'shuoming_quyuan', 'shuoming_taihu', 'shuoming_wine', 'shuoming_xunliaowan', 'shuoming_yijing', 'tai_hu', 'yi_jing', 'fengchidao', 'hao_ya', 'huameida', 'lizhi', 'luofushang', 'qing_feng', 'quyuan');
		if (!array_search($tpl, $template)) throw new CHttpException(405, 'Method Not Allowed');
		$array = array();
		$this->renderPartial($tpl, $array);
	    
	}
	
	public function actionChristmasResultWeb(){
        $this->checkLogin();
        $listResutl = $this->getListData();    
        $list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id in (".Item::LUCKY_ACT_ID."-4,".Item::LUCKY_ACT_ID."-3,".Item::LUCKY_ACT_ID."-2,".Item::LUCKY_ACT_ID."-1,".Item::LUCKY_ACT_ID.") and prize_id!=".Item::LUCKY_THANKS_ID." order by id desc");
        $this->renderPartial("Christmas_result", array ("listResutl"=>$listResutl,"list"=>$list));
    }
    
}