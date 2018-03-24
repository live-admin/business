<?php
class LuckyCircleWebController extends CController {
	//copy from luckyController 
	
	private $_luckyCustCan = 0;
	private $_luckyTodayCan=0;
	private $_luckyActId = 0;
	private $_username = "";
	private $_userId = 0;
	
	/**
	 * index页面，显示抽奖奖项
	 */
	public function actionIndex() {
		//$this->checkLogin();
		//获得奖项布局
		$layoutsList=array();
		//$data=LuckyLayout::model()->findAll("lucky_act_id=".$this->_luckyActId);
		$data=LuckyLayout::model()->findAll("lucky_act_id=".Item::LUCKY_ACT_ID);
		foreach ($data as $value){
			$layoutsList[$value['layout_index']]=$value;
		}
        //$allJoin=LuckyCustomerGet::model()->count("lucky_act_id=".$this->_luckyActId);
        $allJoin=LuckyCustomerGet::model()->count("lucky_act_id=".Item::LUCKY_ACT_ID);
        $isGuest=Yii::app ()->user->isGuest;
        if(! $isGuest){
        	$this->_luckyActId = isset ( $_REQUEST ['actid'] ) ? (intval($_REQUEST ['actid'])) : (3);
        	$this->_userId = Yii::app ()->user->id;
        	$this->_username = Yii::app ()->user->name;
        	$luckyNum = 0;
        	$luckyCan = new LuckyCustCan ();
        	$result = $luckyCan->getCustCan ( $this->_username, $this->_userId, $this->_luckyActId );
        	if ($result) {
        		$this->_luckyCustCan = $result ['cust_can'];
        	}
        	 
        }
		$this->renderPartial( "index",
					 array ( "luckyCustCan" => $this->_luckyCustCan,
					 		"allJoin"=>($allJoin+100),
					 		"layoutList"=>$layoutsList,
					 		"custId"=>$this->_userId,
					 		"isGuest"=>$isGuest,
					 		));
}
	
	/**
	 * 抽奖
	 */
	public function actionDoLucky() {
	    $this->checkLogin();
		//$result = $this->luckyOperation ();
		$luckyOper=new LuckyOperation();
		$besideids=array(9,10);
		$result=$luckyOper->doLucky($this->_luckyActId, $this->_luckyCustCan,$this->_luckyTodayCan, $this->_username,$this->_userId,true,$besideids);
		if ($result && $result ['success'] == 1) {
			//更改角度值，只取中间值
			$min=intval($result ['data'] ['result']['angle']['min']);
			$max=intval($result ['data'] ['result']['angle']['max']);
			$result ['data'] ['result']['angle']=intval(($min+$max)/2);
		}
		
		echo CJSON::encode ( $result );
	}
	
	public function actionLotteryrule(){
		$this->renderPartial("lotteryrule");
	}
	
	public function actionMylottery(){
		$this->checkLogin();
		
		$list=LuckyCustResult::model()->findAll("cust_id=".$this->_userId." and lucky_act_id=".$this->_luckyActId." order by id desc");
		$this->renderPartial("mylottery",
				array("list"=>$list)
		);
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
		$lucky=new LuckyOperation();
		$cityId=$lucky->getUserCity($userId);
		//$cityId=$cityId['parent_id'];
		var_dump($cityId);
	}

	/**
	 * 获取最新中奖用户
	 */
	public function actionGetUserNewListJson(){
		//$this->checkLogin();
		$result=array("success"=>1,'data'=>array('msg'=>'系统错误'));
		//倒叙查询最近N条记录
		$conditon="lucky_act_id=$this->_luckyActId order by id desc limit 15";
		$data =  LuckyCustResult::model()->findAll($conditon);
		$list=array();
		$listJia=array(
				array('prize_level_name'=>'三等奖','name'=>'136****6213',),
				array('prize_level_name'=>'二等奖','name'=>'186****1112',),
				array('prize_level_name'=>'三等奖','name'=>'188****9069',),
				array('prize_level_name'=>'三等奖','name'=>'183****0818',),
				array('prize_level_name'=>'二等奖','name'=>'137****9319',),
				array('prize_level_name'=>'三等奖','name'=>'188****0258',),
				array('prize_level_name'=>'二等奖','name'=>'183****7799',),
				array('prize_level_name'=>'三等奖','name'=>'186****3888',),
				array('prize_level_name'=>'二等奖','name'=>'155****0960',),
				array('prize_level_name'=>'三等奖','name'=>'130****5137',),
		);
		foreach ($data as $value){
			$list[]=array(
					'prize_level_name'=>empty($value['public_info'])?("*****"):($value['public_info']),
					//'name'=>empty($value['cust_name'])?("***"):($value['cust_name']),
					'name'=>substr($value['moblie'],0,3)."****".substr($value['moblie'],7),
			);
		}
		if(count($list)>0){
			if(count($list)<10){  //少于10条，拼加假数据
				$list=array_merge($list,$listJia);
			}
			$result=array("success"=>1,'data'=>array('list'=>$list));
		}else{
			$result=array("success"=>1,'data'=>array('list'=>$listJia));
		}
		echo CJSON::encode($result);
	}
    
	public function actionGetAgain(){
		$this->checkLogin();
		$luckyOper=new LuckyOperation();
		$ret=$luckyOper->getAgainChance($this->_userId, $this->_username);
		echo json_encode($ret);
	}
	
    private function checkLogin(){
//         if (Yii::app ()->user->isGuest) {
// 			$this->redirect ( Yii::app ()->user->loginUrl );
// 		}else {
// 			$this->_luckyActId = isset ( $_REQUEST ['actid'] ) ? ($_REQUEST ['actid']) : (2);
//             $this->_userId = Yii::app ()->user->id;
//             $this->_username = Yii::app ()->user->name;
//             $luckyNum = 0;
//             $luckyCan = new LuckyCustCan ();
//             $result = $luckyCan->getCustCan ( $this->_username, $this->_userId, $this->_luckyActId );
//             if ($result) {
//                 $this->_luckyCustCan = $result ['cust_can'];
//             }
// 		}

    	//未登录也可以进入，点击抽奖时才判断登录
    	//同12月幸福中国行活动  /frontend/controllers/LuckyController

    	if (Yii::app ()->user->isGuest) {
    		//$this->redirect ( Yii::app ()->user->loginUrl );
    		$result = array ("success" => 0,"data" => array ("location"=>1,"href"=>Yii::app ()->user->loginUrl ) );
    		echo CJSON::encode($result);
    		exit();
    	}else {
    		$this->_luckyActId = isset ( $_REQUEST ['actid'] ) ? (intval($_REQUEST ['actid'])) : (3);
    		$this->_userId = Yii::app ()->user->id;
    		$this->_username = Yii::app ()->user->name;
    		$luckyNum = 0;
    		$luckyCan = new LuckyCustCan ();
    		$result = $luckyCan->getCustCan ( $this->_username, $this->_userId, $this->_luckyActId );
    		if ($result) {
    			$this->_luckyCustCan = $result ['cust_can'];
    			$this->_luckyTodayCan=$result['cust_today_can'];
    		}
    	}
    	
    }
}