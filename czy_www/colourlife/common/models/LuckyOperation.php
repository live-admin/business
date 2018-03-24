<?php

/**
 * 抽奖相关操作
 * @author xiaolei
 *
 */
class LuckyOperation {
    public  $entityList = array(
        89,90,91,92,93,94,95,96,97,98,
        106,107,108,109,110,111,112,113,114,115,
        125,126,127,128,129,130,131,132,133,136,
        154,155,156,157,158,159,160,161
    );
	/**
	 * 用户抽奖完成后的更新操作
	 *
	 * @param 用户名 $custname        	
	 * @param 用户id $custid
	 *        	用户id
	 * @param 活动id $activityid
	 *        	活动id
	 * @param 奖项id $prizeid
	 *        	奖项id
	 * @param 不参与更改的奖项ids $besideid        	
	 * @param 产生时间为0的商品，不进行剩余数量递减 $besideGennerZero        	
	 * @return true 更改成功 false 更改失败
	 */
	public function custDoOperation($custname, $custid, $activityid, $prizeid,$redInfo=false, $besideGennerZero = true, $besideid = array(0)) {
		//Yii::log("prizeid : $prizeid",CLogger::LEVEL_ERROR);
		$result = array ();
		$transaction = Yii::app ()->db->beginTransaction ();
		// -------- CDbTransaction 状态为未启用, 无法进行 commit 或 roll back 动作.
		try {
			
			//记录抽奖机会的使用
			//if($prizeid!=Item::LUCKY_AGAIN_ID){   //再来一次，不消抽奖次数
			//所有抽奖操作，都记录抽奖机会的使用记录
				$luckycanout=new LuckyCustomerOut();
				$luckycanout->lucky_act_id=$activityid;
				$luckycanout->cust_name=$custname;
				$luckycanout->cust_id=$custid;
				$luckycanout->date_day=date("Y-m-d");
				$luckycanout->date=date("Y-m-d H:i:s");
				$luckycanout->number=1;
				$luckycanout->prize_id=$prizeid;
				$luckycanout->mark=empty(Yii::app()->user->id)?("app"):("web");
				if(! $luckycanout->save()){
					Yii::log("记录抽奖机会消耗错误",CLogger::LEVEL_ERROR);
					$transaction->rollback();return false;
				}
			//}
			
			// 1、奖项表，减去此奖项的数量
			// LuckyPrize::model()->updateAll(array("prize_count_now"=>new CDbExpression("prize_count_now-1")),"id=$prizeid");
			$besideidStr=implode(",", $besideid);

		    //更改为实物一定要减量
	        $exeRet = LuckyPrize::model ()->updateAll ( array (
	                 "prize_count_now" => new CDbExpression ( "prize_count_now-1" )
	            ), "id=$prizeid and id not in ($besideidStr) and prize_count_now>0" );
			if(empty($exeRet) && !in_array($prizeid, $besideid)){
    			 //如果该奖项是不参与递减的，不判断它的返回值，以免触发rooback
                 //不是不参与递减的，却没有递减，rollback  
			    Yii::log("更新奖项数量错误",CLogger::LEVEL_ERROR);
			    $transaction->rollback();
                return false;
			}
			
		    
			// 2、记录奖项的生产记录
			if (!in_array($prizeid, $besideid)) {
				$prizeBorn = LuckyPrizeBorn::model ()->find ( "prize_id=$prizeid" );
				if ($prizeBorn) { // 已经有了，更改，没有，添加
					$prizeBorn->last_born_date = date ( "Y-m-d H:i:s" );
					$exeRet = $prizeBorn->update ();
				} else {
					$prizeBorn = new LuckyPrizeBorn ();
					$prizeBorn->prize_id = $prizeid;
					$prizeBorn->last_born_date = date ( "Y-m-d H:i:s" );
					$exeRet = $prizeBorn->save ();
				}
				if (empty ( $exeRet )) {
					Yii::log("产生奖项生成记录错误 ",CLogger::LEVEL_ERROR);
					$transaction->rollback ();
					return false;
					// echo 'had rollbacked 2';return;
				}
			}
			
			// 3、减去用户的抽奖机会
			$exeRet = LuckyCustCan::model ()->updateAll ( array (
					"cust_can" => new CDbExpression ( "cust_can-1" ),"cust_today_can" => new CDbExpression ( "cust_today_can-1" )  
			), "cust_id=:custId and lucky_act_id=:luckyActId", array (
					":custId" => $custid,
					":luckyActId" => $activityid 
			) );
			if (empty ( $exeRet )) {
				Yii::log("减去用户抽奖机会错误",CLogger::LEVEL_ERROR);
				$transaction->rollback ();
				return false;
				// echo 'had rollbacked 3';return;
			}
			
			
			if( !in_array($prizeid, $besideid)){
    			// 4、增加中奖记录
    			$customer = Customer::model ()->findByPk ( $custid );
    			
    			$address1 = $customer->getCommunityAddress (); // 小区地址：广东省深圳市罗湖区碧清园
    			$address2 = $customer->getBuildName (); // 楼栋：1栋1单元
    			$address = $address1 . $address2 . $customer->room;
    			$public_adress=$customer->getCommunityName();
    			$luckyPrize=LuckyPrize::model()->findByPk($prizeid);
    			//$public_info=$luckyPrize->prize_level_name;
    			$public_info=$public_adress;
    			$custResult = new LuckyCustResult ();
    			$custResult->cust_name = $custname;
    			$custResult->receive_name = $customer->name;
    			$custResult->cust_id = $custid;
    			// $custResult->address=$customer->community_id."-".$customer->build_id."-".$customer->room; //地址，可以有communityid等关联用户注册时填写的。//此处暂时考虑以其他方式更新，以减少抽奖时的处理。
    			// $custResult->address="000";
    			$custResult->address = $address;
    			$custResult->community_id=$customer->community_id;
    			$custResult->lucky_act_id=$activityid;
    			$custResult->public_info=$public_info;
    			// $custResult->address="";
    			$custResult->moblie = $customer->mobile;
    			$custResult->prize_id = $prizeid;
    			$custResult->lucky_date = date ( "Y-m-d H:i:s" );
    			if($redInfo && $redInfo['num']){
    				//array('num'=>$getRed,"isgivered"=>1);
    				$custResult->isred=1;
    				$custResult->rednum=$redInfo['num'];
    				$custResult->isgivered=$redInfo['isgivered'];
    				if($redInfo['isgivered']==1){
    					$custResult->deal_state=2;
    					$custResult->deal_userid=0;
    					$custResult->deal_username='auto';
    				}
    			}
    			$exeRet = $custResult->save ();
    			if (empty ( $exeRet )) {
    				Yii::log("增加用户中奖记录错误",CLogger::LEVEL_ERROR);
    				$errors=$custResult->errors;
    				Yii::log("增加用户中奖记录错误详情".CJSON::encode($errors),CLogger::LEVEL_ERROR);
    				$transaction->rollback ();
    				return false;
    				// echo 'had rollbacked 4';return;
    			}
                        
                //判断是否是电信充值卡
                if($luckyPrize->prize_name == "中国电信话费"){
                    $telecom = new Telecom();
                    $telecom->customer_id = $custid;
                    $telecom->status = Telecom::STATUS_WAIT;
                    $telecom->create_time = time();
                    $telecom->lucky_cust_result_id = $custResult->id;
                    $telecom->save();
                }                        
                        

    			//判断是否是泰康人寿保险
                if($luckyPrize->prize_name == "泰康人寿"){
                    $telecom = new TaikangLife();
                    $telecom->customer_id = $custid;
                    $telecom->status = TaikangLife::STATUS_WAIT;
                    $telecom->create_time = time();
                    $telecom->lucky_result_id = $custResult->id;
                    $telecom->save();
                }


                //优惠券
                $result ['luckyCustResult'] ['code'] = "";
                if(in_array($prizeid, $this->entityList)){
                	$luckyEntity = LuckyEntity::model()->find('prize_id=:prize_id and is_use=:is_use',array(':prize_id'=>$prizeid,':is_use'=>0));
                	if(!$luckyEntity){
                		Yii::log("优惠券用完,增加用户中奖记录错误",CLogger::LEVEL_ERROR);
						$transaction->rollback();
						return false;
                	}

                	$luckyEntity->is_use = 1;
                	$luckyEntity->customer_id = $custid;
                	$luckyEntity->lucky_result_id = $custResult->id;

					if (! $luckyEntity->update ()) {
						Yii::log("更新优惠券出现错误",CLogger::LEVEL_ERROR);
						$transaction->rollback ();
						return false;
					}
					LuckyCustResult::model()->updateByPk($custResult->id, array('deal_state'=>2,'deal_userid'=>0,'deal_username'=>'auto','isgivered'=>1));
					$result ['luckyCustResult'] ['code'] = $luckyEntity->code;
                }


                //一元码
     //            $result ['luckyCustResult'] ['one_yuan_code'] = "";
     //            if($prizeid==137){
     //            	$onecode = OneYuanBuy::model()->find('is_send=:is_send',array(':is_send'=>0));
     //            	if(!$onecode){
     //            		Yii::log("一元购码使用完毕,需要增加数据库表中码的数量,增加用户中奖记录错误",CLogger::LEVEL_ERROR);
					// 	$transaction->rollback ();
					// 	return false;
     //            	}
     //            	$onecode->is_send = 1;
     //            	$onecode->customer_id = $custid;
     //            	$onecode->send_time = time();
     //            	$onecode->valid_time = strtotime("+1 month");
     //            	$onecode->lucky_result_id = $custResult->id;
					// if (! $onecode->update ()) {
					// 	Yii::log("更新一元购码出现错误",CLogger::LEVEL_ERROR);
					// 	$transaction->rollback ();
					// 	return false;
					// }
					// LuckyCustResult::model()->updateByPk($custResult->id, array('deal_state'=>2,'deal_userid'=>0,'deal_username'=>'auto','isgivered'=>1));
					// $result ['luckyCustResult'] ['one_yuan_code'] = $onecode->code;
     //            }

    			//返回中奖记录
    			$result ['luckyCustResult'] ['id'] = $custResult->id;
    			$result ['luckyCustResult'] ['name'] = $custResult->receive_name;
    			$result ['luckyCustResult'] ['address'] = $custResult->address;
    			$result ['luckyCustResult'] ['phone'] = $custResult->moblie;
    			//redPacaket添加中奖记录id
    			if(!empty($redInfo['modle'])){
    				$model=$redInfo['modle'];
    				$model->lukcy_result_id=$custResult->id;
    				$model->update();
    			}
			
			}
			
			$transaction->commit ();
			return $result;
		} catch ( Exception $e ) {
			Yii::log("其他异常错误",CLogger::LEVEL_ERROR);
			$transaction->rollback ();
			return false;
		}
	}
	
	/**
	 * 根据订单id或者登录，增加用户的抽奖机会
	 *
	 * @param 用户名 $custName        	
	 * @param 用户id $custId        	
	 * @param 订单id(没有订单id，则验证是否通过登录来产生) $orderId        	
	 * @param 活动id $luckyactid        	
	 * @param 给予的抽奖次数(默认1) $gennerCount        	
	 * @return array("success"=>1,"data"=>array("msg"=>"增加成功")); array("success"=>0,"data"=>array("msg"=>"增加失败"));
	 */
	public function custGetLuckyNum($custName, $custId, $orderId, $luckyactid, $gennerCount = 1) {
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
		
		$today = date ( "Y-m-d" );
		// 是否活动已经过期
		$luckyAct = LuckyActivity::model ()->findByPk ( $luckyactid );
		if ($luckyAct) {
			$startDate = $luckyAct->start_date;
			$endDate = $luckyAct->end_date;
			if ($today < $startDate) {
				$result = array ("success" => 0,"data" => array ("msg" => "活动尚未开始" ) );
				return $result;
			}
			if ($today > $endDate) {
				$result = array ("success" => 0,"data" => array ("msg" => "活动已经结束" ) );
				return $result;
			}
		} else {
			$result = array ("success" => 0,"data" => array ("msg" => "活动不存在" ) );
			return $result;
		}
		
		if ($orderId) { // 有订单，验证订单的抽奖机会产生
		                // 验证该订单是否已经产生过了抽奖机会 。
			$custGet = LuckyCustomerGet::model ()->find ( "lucky_act_id=:lucky_act_id and cust_id=:custId and order_id=:orderId", 
			         array (":lucky_act_id"=>$luckyactid,":custId" => $custId,":orderId" => $orderId ) );
			if ($custGet) {
				$result = array ("success" => 0,"data" => array ("msg" => "该订单已经产生过了，无法重复产生" ) );
				return $result;
			}
			
			// 验证订单是否存在
			$order = Order::model ()->findByPk ( $orderId );
			if (empty ( $order )) {
				$result = array (
						"success" => 0,
						"data" => array (
								"msg" => "该订单不存在" 
						) 
				);
				return $result;
			}
			if ($order ['status'] != 1) {  //1->已付款，待发货
				$result = array (
						"success" => 0,
						"data" => array (
								"msg" => "只有状态为“已付款，待发货”的才可以产生抽奖机会" 
						) 
				);
				return $result;
			}
			// 一个订单只有一个商品
			// 验证该商品，是否有抽奖机会
			$orderGoods = OrderGoodsRelation::model ()->find ( "order_id=" . $order ['id'] );
			if (empty ( $orderGoods )) {
				$result = array (
						"success" => 0,
						"data" => array (
								"msg" => "该订单没有商品" 
						) 
				);
				return $result;
			}
			$goodsId = $orderGoods ['goods_id'];
			$luckyGoods = LuckyGoods::model ()->find ( "goods_id=" . $goodsId . " and lucky_act_id=" . $luckyactid . " and disable=0 and isdelete=0" );
			if (empty ( $luckyGoods )) {
				$result = array (
						"success" => 0,
						"data" => array (
								"msg" => "该订单对应的商品没有参加到此次抽奖活动" 
						) 
				);
				return $result;
			}
		} else {
			// 验证 今日 是否已经产生过了抽奖机会 。
			$custGet = LuckyCustomerGet::model ()->find ( "lucky_act_id=:lucky_act_id and cust_id=:custId and login_date=:today", 
					array (
					":lucky_act_id"=>$luckyactid,
					":custId" => $custId,
					":today" => $today 
					) );
			if ($custGet) {
				$result = array (
						"success" => 0,
						"data" => array (
								"msg" => "今日已登录已送" 
						) 
				);
				return $result;
			}
		}
		
		// 添加记录
		$custGet = new LuckyCustomerGet ();
		$custGet->cust_name = $custName;
		$custGet->cust_id = $custId;
		if ($orderId) {
			$custGet->order_id = $order ['id'];
			$custGet->get_action='orderSome';
		} else {
			$custGet->login_date = $today;
			$custGet->get_action='login';
		}
		$custGet->genner_count = $gennerCount;
		$custGet->lucky_act_id = $luckyactid;
		$custGet->create_date = date ( "Y-m-d H:i:s" );
		$custGet->save ();
// 		$errors=$custGet->getErrors();
// 		////var_dump($errors);
// 		exit();
		
		// 添加用户抽奖机会或者更新抽奖次数
		$luckyCustCan = LuckyCustCan::model ()->find ( "lucky_act_id=:lucky_act_id and cust_id=:custId", array (
				":lucky_act_id"=>$luckyactid,":custId" => $custId 
		) );
		if ($luckyCustCan) {
			$exeRet = LuckyCustCan::model ()->updateAll ( array (
					"cust_can" => new CDbExpression ( "cust_can+" . $gennerCount ) 
			), "cust_id=:custId and lucky_act_id=:luckyActId", array (
					":custId" => $custId,
					":luckyActId" => $luckyactid 
			) );
		} else {
			$luckyCustCan = new LuckyCustCan ();
			$luckyCustCan->cust_name = $custName;
			$luckyCustCan->cust_id = $custId;
			$luckyCustCan->cust_can = $gennerCount;
			$luckyCustCan->lucky_act_id = $luckyactid;
			$exeRet = $luckyCustCan->save ();
		}
		if ($exeRet) {
			$result = array (
					"success" => 1,
					"data" => array (
							"msg" => "增加成功" 
					) 
			);
		}
		return $result;
	}

	
	/**
	 * 抽奖，增加可抽奖机会
	 * @param 类型  $type  登录login、购买指定商品orderSome 、购买所有商品以及所有支付成功(缴物业费、停车费、预缴费、充值)  orderAll  、
	 * <br/> 投诉报修 ownerComplain/personalRepairs/publicRepairs
	 * @param 参数  $param  所需的参数  luckyActId、custId、custName、gennerCount=1、orderId订单必选
	 */
	private function luckyAdd($type,$param){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );

		$luckyActId=isset($param['luckyActId'])?($param['luckyActId']):false;   //活动
		//$luckyActId=isset($param['luckyActId'])?($param['luckyActId']):3;   //活动
		$custId=isset($param['custId'])?($param['custId']):false;  				//用户
		$custName=isset($param['custName'])?($param['custName']):false;         //用户名
		$gennerCount=isset($param['gennerCount'])?($param['gennerCount']):1;    //产生的机会数量
		if(empty($luckyActId) || empty($custId)){
			$result = array ("success" => 0,"data" => array ("msg" => "参数错误" ) );
			return $result;
		}
		
		//
		// 是否活动已经过期
		$today = date ( "Y-m-d" );
		$luckyAct = LuckyActivity::model()->findByPk ( $luckyActId );
		if ($luckyAct) {
// 			$startDate = $luckyAct->start_date;
// 			$endDate = $luckyAct->end_date;
// 			if ($today < $startDate) {
// 				$result = array ("success" => 0,"data" => array ("msg" => "活动尚未开始" ) );
// 				return $result;
// 			}
// 			if ($today > $endDate) {
// 				$result = array ("success" => 0,"data" => array ("msg" => "活动已经结束" ) );
// 				return $result;
// 			}
//			//抽奖机会，不关联活动，只关联人，统一放到id为2的活动去。
		} else {
			$result = array ("success" => 0,"data" => array ("msg" => "活动不存在" ) );
			return $result;
		}
		
		$checkAdd=false;  //是否增加
		
		// 检查是否增加，如果增加，返回true，并在其中，添加增加记录  LuckyCustomerGet
		if($type=="login"){  //登录
			$result=$this->checkAddLogin($luckyActId, $custId, $custName, $today, $gennerCount);
			//$this->updateUserTodayCan($luckyActId, $custId);	//更新今日可抽奖次数为每天最大值
			$checkAdd=$result['success'];
		}else if($type=="orderSome"){   //订单指定商品
			$orderId=isset($param['orderId'])?($param['orderId']):false; 
			if(empty($orderId)){
				$result = array ("success" => 0,"data" => array ("msg" => "参数错误(orderId)" ) );
				return $result;
			}
			$result=$this->checkAddOrderSome($luckyActId, $custId, $custName, $orderId, $gennerCount);
			$checkAdd=$result['success'];
		}else if($type=="orderAll"){  //订单所有商品+缴费(物业费水电费)
			$orderId=isset($param['orderId'])?($param['orderId']):false;
			$oreerModel=isset($param['orderModel'])?($param['orderModel']):false;
			if(empty($orderId) || empty($oreerModel)){
				$result = array ("success" => 0,"data" => array ("msg" => "参数错误(orderId?orderModel)" ) );
				return $result;
			}
			$result=$this->checkAddOrderAll($luckyActId, $custId, $custName, $orderId, $gennerCount,$oreerModel);
			$checkAdd=$result['success'];
		}else if($type=="ownerComplain" || $type=="personalRepairs" || $type=="publicRepairs"){
			//个人投诉，个人保修，公共报修
			//$result=$this->checkAddComplain($luckyActId, $custId, $custName, $today, $gennerCount,$type);
			$result=$this->checkAddOnlyFirstAction($luckyActId, $custId, $custName, $today, $gennerCount, "complainRepairs");
			$checkAdd=$result['success'];
		}else if($type=="invite"){
			//邀请注册
			$result=$this->checkInvite($luckyActId, $custId, $custName, $today, $gennerCount,$type);
			$checkAdd=$result['success'];
		}else if($type=="finishInfo"){ //完善资料
			//$result=$this->checkAddOnlyFirstAction($luckyActId, $custId, $custName, $today, $gennerCount, $type);
			$result=$this->checkFinishInfo($luckyActId, $custId, $custName, $today, $gennerCount);
			$checkAdd=$result['success'];
		}else if($type=="propertyActivity"){
			$orderId=isset($param['orderId'])?($param['orderId']):false;
			$result=$this->checkAddPropertyActivity($luckyActId, $custId, $custName, $orderId, $gennerCount);
			$checkAdd=$result['success'];
		}else if($type=="ELICAI"){
			$orderId=isset($param['orderId'])?($param['orderId']):false;
			$result=$this->checkAddELICAI($luckyActId, $custId, $custName, $orderId, $gennerCount);
			$checkAdd=$result['success'];
		}else if($type=="EWEIXIU"){
			$orderId=isset($param['orderId'])?($param['orderId']):false;
			$result=$this->checkAddEWEIXIU($luckyActId, $custId, $custName, $orderId, $gennerCount);
			$checkAdd=$result['success'];
		}else if($type=="again"){
			$result=$this->checkAddAgain($luckyActId, $custId, $custName, $today, $gennerCount, $type);
			
			$this->updateForAgain($luckyActId, $custId);  //再来一次，更新今日可抽奖次数
			$checkAdd=$result['success'];
		}else{
			//其他，每次动作都送
			$result=$this->checkAddEveryAction($luckyActId, $custId, $custName, $today, $gennerCount, $type);
			$checkAdd=$result['success'];
		}
		
		
		if($checkAdd){
			// 添加用户抽奖机会或者更新抽奖次数
			$luckyCustCan = LuckyCustCan::model()->find ( "lucky_act_id=:lucky_act_id and cust_id=:custId", 
					array (":lucky_act_id"=>$luckyActId,":custId" => $custId) );
			if ($luckyCustCan) {
				$exeRet = LuckyCustCan::model()->updateAll ( array (
						"cust_can" => new CDbExpression ( "cust_can+" . $gennerCount ),
				), "cust_id=:custId and lucky_act_id=:luckyActId", array (
						":custId" => $custId,
						":luckyActId" => $luckyActId
				) );
			} else {
				$luckyCustCan = new LuckyCustCan ();
				$luckyCustCan->cust_name = $custName;
				$luckyCustCan->cust_id = $custId;
				$luckyCustCan->cust_can = $gennerCount;
				$luckyCustCan->lucky_act_id = $luckyActId;
				$exeRet = $luckyCustCan->save ();
			}
			if ($exeRet) {
				$result = array ("success" => 1,"data" => array ("msg" => "增加成功"));
			}
			if($type=="login"){  //登录
				$this->updateUserTodayCan($luckyActId, $custId);	//更新今日可抽奖次数为每天最大值
			}
		}
		return $result;
	}
	
	/**
	 * 登录增加 抽奖机会 
	 */
	private function checkAddLogin($luckyactid,$custId,$custName,$today,$gennerCount){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
		
		// 验证 今日 是否已经产生过了抽奖机会 。
		$custGet = LuckyCustomerGet::model ()->find ( "lucky_act_id=:lucky_act_id and cust_id=:custId and login_date=:today and get_action=:action", 
				array (":lucky_act_id"=>$luckyactid,":custId" => $custId,":today" => $today,":action"=>"login") );
		
		if ($custGet) {
			$result = array ("success" => 0,"data" => array ("msg" => "今日已登录已送"));
			return $result;
		}else{
			// 添加记录
			$custGet = new LuckyCustomerGet ();
			$custGet->cust_name = $custName;
			$custGet->cust_id = $custId;
			$custGet->login_date = $today;
			$custGet->get_action='login';
			$custGet->genner_count = $gennerCount;
			$custGet->lucky_act_id = $luckyactid;
			$custGet->create_date = date ( "Y-m-d H:i:s" );
			$custGet->save ();
			$result = array ("success" => 1,"data" => array ("msg" => "" ) );
		}
		return $result;
	}
	
	/**
	 * 邀请注册 抽奖机会
	 */
	private function checkInvite($luckyactid,$custId,$custName,$today,$gennerCount,$type){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
			// 添加记录
			$custGet = new LuckyCustomerGet ();
			$custGet->cust_name = $custName;
			$custGet->cust_id = $custId;
			$custGet->login_date = $today;
			$custGet->get_action=$type;
			$custGet->genner_count = $gennerCount;
			$custGet->lucky_act_id = $luckyactid;
			$custGet->create_date = date ( "Y-m-d H:i:s" );
			if($custGet->save ()){
				$result = array ("success" => 1,"data" => array ("msg" => "" ) );
			}
		return $result;
	}
	


	
	/**
	 *  零物业费订单 增加 抽奖机会
	 */
	private function checkAddPropertyActivity($luckyactid,$custId,$custName,$orderId,$gennerCount){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
		if ($orderId) { // 有订单，验证订单的抽奖机会产生
			// 验证该订单是否已经产生过了抽奖机会 。
			$custGet = LuckyCustomerGet::model ()->find ( "lucky_act_id=:lucky_act_id and cust_id=:custId and order_id=:orderId",
					array (":lucky_act_id"=>$luckyactid,":custId" => $custId,":orderId" => $orderId ) );
			
			if ($custGet) {
				$result = array ("success" => 0,"data" => array ("msg" => "该订单已经送过抽奖机会了" ) );
				return $result;
			}
				
			// 验证订单是否存在
			$order = propertyActivity::model()->findByPk ( $orderId );
			if (empty ( $order )) {
				$result = array ("success" => 0,"data" => array ("msg" => "该订单不存在"));
				return $result;
			}
			if ($order ['status'] != 99) {  //1->已付款，待发货
				$result = array ("success" => 0,"data" => array ("msg" => "只有状态为“已付款”的零物业费订单才可以产生抽奖机会"));
				return $result;
			}
			// 添加记录
			$custGet = new LuckyCustomerGet ();
			$custGet->cust_name = $custName;
			$custGet->cust_id = $custId;
			$custGet->order_id = $order ['id'];
			$custGet->get_action='propertyActivity';
			$custGet->genner_count = $gennerCount;
			$custGet->lucky_act_id = $luckyactid;
			$custGet->create_date = date ( "Y-m-d H:i:s" );
			$custGet->save ();			
			$result = array ("success" => 1,"data" => array () );
		}
		return $result;
	}




	/**
	 *  成功投资E理财 增加 抽奖机会
	 */
	private function checkAddELICAI($luckyactid,$custId,$custName,$orderId,$gennerCount){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
		if ($orderId) {
			
			// 验证该订单是否已经产生过了抽奖机会
			$custGet = LuckyCustomerGet::model ()->find ( "lucky_act_id=:lucky_act_id and cust_id=:custId and order_id=:orderId and get_action=:get_action",
					array (":lucky_act_id"=>$luckyactid,":custId" => $custId,":orderId" => $orderId,':get_action'=>'elicai' ) );
			if ($custGet) {
				$result = array ("success" => 0,"data" => array ("msg" => "订单已经送过抽奖机会了!") );
				return $result;
			}

			// 添加记录
			$custGet = new LuckyCustomerGet ();
			$custGet->cust_name = $custName;
			$custGet->cust_id = $custId;
			$custGet->order_id = $orderId;
			$custGet->get_action='elicai';
			$custGet->genner_count = $gennerCount;
			$custGet->lucky_act_id = $luckyactid;
			$custGet->create_date = date ( "Y-m-d H:i:s" );
			$custGet->save ();			
			$result = array ("success" => 1,"data" => array () );
		}
		return $result;
	}




	/**
	 *  成功投资E理财 增加 抽奖机会
	 */
	private function checkAddEWEIXIU($luckyactid,$custId,$custName,$orderId,$gennerCount){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
		if ($orderId) {
			// 验证该订单是否已经产生过了抽奖机会
			$custGet = LuckyCustomerGet::model ()->find ( "lucky_act_id=:lucky_act_id and cust_id=:custId and order_id=:orderId and get_action=:get_action",
					array (":lucky_act_id"=>$luckyactid,":custId" => $custId,":orderId" => $orderId,':get_action'=>'eweixiu' ) );
			
			if ($custGet) {
				$result = array ("success" => 0,"data" => array ("msg" => "订单已经送过抽奖机会了!") );
				return $result;
			}
			// 添加记录
			$custGet = new LuckyCustomerGet ();
			$custGet->cust_name = $custName;
			$custGet->cust_id = $custId;
			$custGet->order_id = $orderId;
			$custGet->get_action='eweixiu';
			$custGet->genner_count = $gennerCount;
			$custGet->lucky_act_id = $luckyactid;
			$custGet->create_date = date ( "Y-m-d H:i:s" );
			$custGet->save ();			
			$result = array ("success" => 1,"data" => array () );
		}
		return $result;
	}





	/**
	 *  购买指定商品 增加 抽奖机会
	 */
	private function checkAddOrderSome($luckyactid,$custId,$custName,$orderId,$gennerCount){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
		if ($orderId) { // 有订单，验证订单的抽奖机会产生
			// 验证该订单是否已经产生过了抽奖机会 。
			$custGet = LuckyCustomerGet::model ()->find ( "lucky_act_id=:lucky_act_id and cust_id=:custId and order_id=:orderId",
					array (":lucky_act_id"=>$luckyactid,":custId" => $custId,":orderId" => $orderId ) );
			if ($custGet) {
				$result = array ("success" => 0,"data" => array ("msg" => "该订单已经产生过了，无法重复产生" ) );
				return $result;
			}
				
			// 验证订单是否存在
			$order = Order::model ()->findByPk ( $orderId );
			if (empty ( $order )) {
				$result = array ("success" => 0,"data" => array ("msg" => "该订单不存在"));
				return $result;
			}
			if ($order ['status'] != 1) {  //1->已付款，待发货
				$result = array ("success" => 0,"data" => array ("msg" => "只有状态为“已付款，待发货”的才可以产生抽奖机会"));
				return $result;
			}
			// 一个订单只有一个商品
			// 验证该商品，是否有抽奖机会
			$orderGoods = OrderGoodsRelation::model ()->find ( "order_id=" . $order ['id'] );
			if (empty ( $orderGoods )) {
				$result = array ("success" => 0,"data" => array ("msg" => "该订单没有商品"));
				return $result;
			}
			$goodsId = $orderGoods ['goods_id'];
			$luckyGoods = LuckyGoods::model ()->find ( "goods_id=" . $goodsId . " and lucky_act_id=" . $luckyactid . " and disable=0 and isdelete=0" );
			if (empty ( $luckyGoods )) {
				$result = array ("success" => 0,"data" => array ("msg" => "该订单对应的商品没有参加到此次抽奖活动"));
				return $result;
			}
			
			// 添加记录
			$custGet = new LuckyCustomerGet ();
			$custGet->cust_name = $custName;
			$custGet->cust_id = $custId;
			$custGet->order_id = $order ['id'];
			$custGet->get_action='orderSome';
			$custGet->genner_count = $gennerCount;
			$custGet->lucky_act_id = $luckyactid;
			$custGet->create_date = date ( "Y-m-d H:i:s" );
			$custGet->save ();
			
			$result = array ("success" => 1,"data" => array () );
		}
		return $result;
	}
	
	/**
	 *  购买所有商品商品 增加 抽奖机会
	 */
	private function checkAddOrderAll($luckyactid,$custId,$custName,$orderId,$gennerCount,$orderModel){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
		if ($orderId) { // 有订单，验证订单的抽奖机会产生
			//商品购买、缴物业费停车费、虚拟充值……………………

			// 添加记录
			$custGet = new LuckyCustomerGet ();
			$custGet->cust_name =$custName;
			$custGet->cust_id = $custId;
			//$custGet->order_id = $order ['id'];
			$custGet->order_id = $orderId;
			$custGet->get_action=$orderModel;
			$custGet->genner_count = $gennerCount;
			$custGet->lucky_act_id = $luckyactid;
			$custGet->create_date = date ( "Y-m-d H:i:s" );
			if(! $custGet->save ()){
				$errors=$custGet->getErrors();
				$result = array ("success" => 0,"data" => array ("msg" => CJSON::encode($errors)));
				return $result;
			}
			$result = array ("success" => 1,"data" => array () );
		}
		return $result;
	}
	
	/**
	 * 投诉保修发起 送抽奖机会
	 */
	private function checkAddComplain($luckyactid,$custId,$custName,$today,$gennerCount,$type){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
			// 添加记录
			$custGet = new LuckyCustomerGet ();
			$custGet->cust_name = $custName;
			$custGet->cust_id = $custId;
			$custGet->login_date = $today;
			$custGet->get_action=$type;
			$custGet->genner_count = $gennerCount;
			$custGet->lucky_act_id = $luckyactid;
			$custGet->create_date = date ( "Y-m-d H:i:s" );
			if($custGet->save ()){
				$result = array ("success" => 1,"data" => array ("msg" => "" ) );
			}
		return $result;
	}
	
	
	/**
	 * 一天只送一次的操作动作
	 */
	private function checkAddOnlyFirstAction($luckyactid,$custId,$custName,$today,$gennerCount,$type){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
	
		// 验证 今日 是否已经产生过了抽奖机会 。
		$custGet = LuckyCustomerGet::model ()->find ( "lucky_act_id=:lucky_act_id and cust_id=:custId and login_date=:today and get_action=:action",
				array (":lucky_act_id"=>$luckyactid,":custId" => $custId,":today" => $today,":action"=>$type) );
	
		if ($custGet) {
			$result = array ("success" => 0,"data" => array ("msg" => "今日".$type."已送"));
			return $result;
		}else{
			// 添加记录
			$custGet = new LuckyCustomerGet ();
			$custGet->cust_name = $custName;
			$custGet->cust_id = $custId;
			$custGet->login_date = $today;
			$custGet->get_action=$type;
			$custGet->genner_count = $gennerCount;
			$custGet->lucky_act_id = $luckyactid;
			$custGet->create_date = date ( "Y-m-d H:i:s" );
			$custGet->save ();
			$result = array ("success" => 1,"data" => array ("msg" => "" ) );
		}
		return $result;
	}
	
	/**
	 * 每次都送的操作动作
	 */
	private function checkAddEveryAction($luckyactid,$custId,$custName,$today,$gennerCount,$type){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
		// 添加记录
		$custGet = new LuckyCustomerGet ();
		$custGet->cust_name = $custName;
		$custGet->cust_id = $custId;
		$custGet->login_date = $today;
		$custGet->get_action=$type;
		$custGet->genner_count = $gennerCount;
		$custGet->lucky_act_id = $luckyactid;
		$custGet->create_date = date ( "Y-m-d H:i:s" );
		if($custGet->save ()){
			$result = array ("success" => 1,"data" => array ("msg" => "" ) );
		}
		return $result;
	}
	
	/**
	 * 再来一次de结果，抽奖机会。
	 */
	private function checkAddAgain($luckyactid,$custId,$custName,$today,$gennerCount,$type){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
		// 添加记录
		$custGet = new LuckyCustomerGet ();
		$custGet->cust_name = $custName;
		$custGet->cust_id = $custId;
		$custGet->login_date = $today;
		$custGet->get_action=$type;
		$custGet->genner_count = $gennerCount;
		$custGet->lucky_act_id = $luckyactid;
		$custGet->create_date = date ( "Y-m-d H:i:s" );
		if($custGet->save ()){
			$result = array ("success" => 1,"data" => array ("msg" => "" ) );
		}
		return $result;
	}
	
	/**
	 * 完善资料送抽奖机会
	 * @param 活动id $luckyactid
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 * @param today $today
	 * @param 增加数量 $gennerCount
	 */
	private function checkFinishInfo($luckyactid,$custId,$custName,$today,$gennerCount){
		$result = array ("success" => 0,"data" => array ("msg" => "系统错误" ) );
		
		// 验证  是否已经送过。
		$custGet = LuckyCustomerGet::model ()->find ( "cust_id=:custId and get_action=:action", 
				array (":custId" => $custId,":action"=>"finishInfo") );
		
		if ($custGet) {
			$result = array ("success" => 0,"data" => array ("msg" => "已完善资料送过了"));
			return $result;
		}else{
			$customer=Customer::model()->findByPk($custId);
			if(empty($customer) || empty($customer->community_id) || empty($customer->build_id) || empty($customer->room) || empty($customer->name)){
				//必填字段：小区、楼栋、房间、姓名
				$result = array ("success" => 0,"data" => array ("msg" => "资料不完善"));
				return $result;
			}
			// 添加记录
			$custGet = new LuckyCustomerGet ();
			$custGet->cust_name = $custName;
			$custGet->cust_id = $custId;
			$custGet->login_date = $today;
			$custGet->get_action='finishInfo';
			$custGet->genner_count = $gennerCount;
			$custGet->lucky_act_id = $luckyactid;
			$custGet->create_date = date ( "Y-m-d H:i:s" );
			$custGet->save ();
			$result = array ("success" => 1,"data" => array ("msg" => "" ) );
		}
		return $result;
	}

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
	public function doLucky($luckyActId,$luckyCustCan,$luckyTodayCan,$userName,$userId,$besideGennerZero = true, $besideid = array(0),$flag = false, $prizeLevel=''){
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
		
		if($flag){
			$retObj = array (
				'id' => intval(Item::LUCKY_THANKS_ID),
				'prize_name' =>"flag",
				'all' => 0,
				'prize_level_name' => 0,
				'cust_result_id' => 0,
			);
			$this->custDoOperation ( $userName, $userId,$luckyActId, $retObj ['id'],false,$besideGennerZero,$besideid );
			$result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
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
				'prize_name' =>"1",
				'all' => 0,
				'prize_level_name' => 0,
				'cust_result_id' => 0,
			);
			$this->custDoOperation ( $userName, $userId,$luckyActId, $retObj ['id'],false,$besideGennerZero,$besideid );
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
		$prizeList = $luckyPrize->getPrizeList ( $luckyActId, false, $prizeLevel);
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
				'id' => intval(Item::LUCKY_THANKS_ID),
				'prize_name' =>"2-".$luckyActId,
				'all' => 0,
				'prize_level_name' => 0,
				'cust_result_id' => 0,
			);
			$this->custDoOperation ( $userName, $userId,$luckyActId, $retObj ['id'],false,$besideGennerZero,$besideid );
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
		//var_dump($getObj);die;

		//中过泰康人寿的不中，返回谢谢参与
		if($getObj['id']==161){
			$luckModel = LuckyCustResult::model()->find(' cust_id=:cust_id and prize_id in(70,77,86,116,134) ',array(':cust_id'=>$userId));
			if($luckModel){
				$retObj = array (
					'id' => intval(Item::LUCKY_THANKS_ID),
					'prize_name' =>"3",
					'all' => 0,
					'prize_level_name' => 0,
					'cust_result_id' => 0,
				);
				$this->custDoOperation ( $userName, $userId,$luckyActId, $retObj ['id'],false,$besideGennerZero,$besideid );
				$result = array ("success" => 1, "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
				return $result;
			}
		}




//		if($getObj['id']==135){//本期中过黑莓酒，不会再中
//			$luckModel = LuckyCustResult::model()->find(' cust_id=:cust_id and prize_id=:prize_id ',array(':cust_id'=>$userId, ':prize_id'=>$getObj['id']));
//			if($luckModel){
//				$retObj = array (
//					'id' => intval(Item::LUCKY_THANKS_ID),
//					'prize_name' =>"",
//					'all' => 0,
//					'prize_level_name' => 0,
//					'cust_result_id' => 0,
//				);
//				$this->custDoOperation ( $userName, $userId,$luckyActId, $retObj ['id'],false,$besideGennerZero,$besideid );
//				$result = array ("success" => 1, "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
//				return $result;
//			}
//		}


		if(in_array($getObj['id'], $this->entityList)){//优惠券
			$luckModel = LuckyCustResult::model()->find(' cust_id=:cust_id and prize_id=:prize_id ',array(':cust_id'=>$userId, ':prize_id'=>$getObj['id']));
			if($luckModel){
				$retObj = array (
					'id' => intval(Item::LUCKY_THANKS_ID),
					'prize_name' =>"4",
					'all' => 0,
					'prize_level_name' => 0,
					'cust_result_id' => 0,
				);
				$this->custDoOperation ( $userName, $userId,$luckyActId, $retObj ['id'],false,$besideGennerZero,$besideid );
				$result = array ("success" => 1, "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
				return $result;
			}
		}


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
			if ($getRed==0){ //产生红包失败，转给予谢谢参与
                $retObj = array (
					'id' => intval(Item::LUCKY_THANKS_ID),
					'prize_name' =>"5",
					'all' => 0,
					'prize_level_name' => 0,
					'cust_result_id' => 0,
                );
                $this->custDoOperation ( $userName, $userId,$luckyActId, $retObj ['id'],false,$besideGennerZero,$besideid );
                $result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
                return $result;
			}
            else {
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

				}
                else{
					$redInfo=array('num'=>$getRed,"isgivered"=>0); //记录没给
				}

			}
        }
        else {
            //中实物奖项
			//产生奖，并同时更新奖项信息
			$getRed=$redPackage->gennerEntity($userId, $getObj['id']);
			$getRed=floatval($getRed);
			if($getRed==0){ //产生奖失败，转给予谢谢参与
                $retObj = array (
					'id' => intval(Item::LUCKY_THANKS_ID),
					'prize_name' =>"6",
					'all' => 0,
					'prize_level_name' => 0,
					'cust_result_id' => 0,
                );
                $this->custDoOperation ( $userName, $userId,$luckyActId, $retObj ['id'],false,$besideGennerZero,$besideid );
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
		if ( $change != false ) {
			$getObj->refresh ();
			$retObj = array (
					'id' => intval($getObj ['id']),
					'prize_name' => $getObj ['prize_name'],
					// 'prize_des'=>$getObj['prize_des'],
					'all' => $getObj ['prize_count_all'],
					//'now'=>$getObj['prize_count_now'],
					//'prize_picture'=>$getObj['prize_picture'],
					//'prize_picture' => $getObj->prizePictureUrl,
					'prize_level_name' => $getObj ['prize_level_name'],
					//'cust_result_id' => $change ['luckyCustResult'] ['id'],
					//'cust_result_name' => $change ['luckyCustResult'] ['name'],
					//'cust_result_address' => $change ['luckyCustResult'] ['address'],
					//'cust_result_phone' => $change ['luckyCustResult'] ['phone'],
					'lucky_code' => $change ['luckyCustResult'] ['code'],
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
					'id' => intval(Item::LUCKY_THANKS_ID),
					'prize_name' =>"7",
					'all' => 0,
					//'prize_picture' => 0,
					'prize_level_name' => 0,
					'cust_result_id' => 0,
					//'angle'=>array("min"=>0,"max"=>0),
					'rednum'=>0,
			);			
			//var_dump($getObj ['id']);die;
			$result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
			return $result;
		
		}
		
		return $result;
		
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
                    'id' => intval(Item::LUCKY_THANKS_ID),
                    'prize_name' =>"",
                    'all' => 0,
                    'prize_level_name' => 0,
                    'cust_result_id' => 0,
                    //'angle'=>Item::$lucky_thanks_angle,
                );
                $this->custDoOperation ( $userName, $userId,$luckyActId, $retObj ['id'],false,$besideGennerZero,$besideid );
                $result = array ("success" => 1,  "data" => array ("bingo" => 0, "msg" => "", "result" => $retObj) );
                return $result;
            }
            $retObj = array (
                'id' => intval(Item::LUCKY_SMALL_PRIZE),
                'prize_name' => $luckyPrizeModel->prize_name,
                'all' => $luckyPrizeModel->prize_count_all,
                'prize_level_name' => $luckyPrizeModel->prize_level_name,
                //'angle'=>array("min"=>$luckyPrizeModel->angle_start,"max"=>$luckyPrizeModel->angle_end),
                'rednum'=>0.18,
            );
            $redInfo=array('num'=>0,"isgivered"=>0);
            $redPackage=new LuckyRedEnvelope();
            $getRed=$redPackage->gennerRedPackage3($userId, Item::LUCKY_SMALL_PRIZE);
            $getRed=floatval($getRed);
            //TODO 更改红包,大奖发不发-中奖结果记录的记录
            if($getRed==0){ //产生红包失败，转给予谢谢参与
                $retObj = array (
                    'id' => intval(Item::LUCKY_THANKS_ID),
                    'prize_name' =>"",
                    'all' => 0,
                    //'prize_picture' => 0,
                    'prize_level_name' => 0,
                    'cust_result_id' => 0,
                    //'angle'=>array("min"=>0,"max"=>0),
                    // 'angle'=>Item::$lucky_thanks_angle,
                );
                $this->custDoOperation ( $userName, $userId,$luckyActId, $retObj ['id'],false,$besideGennerZero,$besideid );
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
                // TODO 是否接入金融平台？
                $customerModel->balance += 0.18;
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
// 		$conn=Yii::app()->db;
// 		$sql="SELECT c.`parent_id` FROM `customer` a
// 				RIGHT JOIN  `community` b ON (a.`community_id`=b.`id`)
// 				RIGHT JOIN `region` c ON (b.`region_id`=c.`id`)
// 				WHERE a.`id`=".$userId;
// 		$comm=$conn->createCommand($sql);
// 		//$row=$comm->queryRow();
// 		//$row=$comm->queryColumn();
// 		$row=$comm->queryScalar();
// 		return intval($row);
	}


	/**
	 * 根据key获取config中的值
	 * @param $key
	 */
	public static function getConfigValue($key){
// 		$loginNum=1;	//登录送抽奖次数
// 		$orderNum=1;	//购买支付送抽奖次数
// 		$complainNum=1;	//投诉送次数
// 		$personalRepainNum=1;	//报修送次数
// 		$publicRepainNum=1;		//公共报修送
// 		$intviteNum=1;		//邀请送
// 		return intval(Config::model()->findByKey($key)->getVal());
	}

	
	/**
	 * 获得再来一次机会，添加抽奖次数，更改今日限制
	 * @param 用户id $userId
	 */
	public function getAgainChance($userId,$userName){
		$result=array("success"=>0,'data'=>'系统错误');
		//最近一次得到‘再来一次’的记录
		$sql1="SELECT `date` FROM `lucky_customer_out` WHERE `prize_id`=".Item::LUCKY_AGAIN_ID." ORDER BY `date` DESC LIMIT 1";
		$outInfo=LuckyCustomerOut::model()->findBySql($sql1);
		if(empty($outInfo)){
			$result=array("success"=>0,'data'=>'没有得到‘再来一次’');
			return $result;
		}
		//最近一次因为‘再来一次’获得抽奖机会的记录 
		$sql2="SELECT create_date FROM `lucky_customer_get` WHERE get_action='again' ORDER BY create_date DESC LIMIT 1";
		$getInfo=LuckyCustomerGet::model()->findBySql($sql2);
		if(empty($getInfo)){
			
		}else{
			$outTime=strtotime($outInfo['date']);
			$getTime=strtotime($getInfo['create_date']);
			if($getTime>$outTime){
				$result=array("success"=>0,'data'=>'本次‘再来一次’已发送');
				return $result;
			}
		}
		$paramin=array('type'=>'again',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$userId,
						'custName'=>$userName,
						'gennerCount'=>1,
				),
		);
		$b=$this->execute($paramin);
		return $b;
	}
	
	/**
	 * 更新用户今日可抽奖次数
	 * @param unknown $luckyactid
	 * @param unknown $custId
	 * @param unknown $today
	 */
	private function updateUserTodayCan($luckyActId,$custId){
		$luckyCustCan = LuckyCustCan::model ()->find ("lucky_act_id=:lucky_act_id and cust_id=:custId", 
					array (":lucky_act_id"=>$luckyActId,":custId" => $custId) );
		if($luckyCustCan){
			$luckyCustCan->cust_today_can=Item::LUCKY_DAY_MAX;
			$luckyCustCan->update();
		}
		
	}
	
	/**
	 * 再来一次，增加总抽奖次数外，更新今日可抽奖次数
	 * @param unknown $luckyActId
	 * @param unknown $custId
	 */
	private function updateForAgain($luckyActId,$custId){
		$exeRet = LuckyCustCan::model ()->updateAll ( array (
				"cust_today_can" => new CDbExpression ( "cust_today_can+1" )
		), "cust_id=:custId and lucky_act_id=:luckyActId", array (
				":custId" => $custId,
				":luckyActId" => $luckyActId
		) );
	}
	
	
}