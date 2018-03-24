<?php

/**
 * 添加抽奖机会
 * @author xiaolei
 *
 */
class LuckyDoAdd {
	
	/**
	 * 登录送抽奖机会
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 */
	public static function login($custId,$custName){
		$luckyOper=new LuckyOperation();
		$paramin=array('type'=>'login',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$custId,
						'custName'=>$custName,
						'gennerCount'=>Item::LUCKY_LOGIN_NUM,
				),
		);
		$b=$luckyOper->execute($paramin);
		Yii::log("登录送抽奖机会".$custId."==".json_encode($b),CLogger::LEVEL_INFO,'colourlife.core.lucky');
	}
	
	/**
	 * 注册送抽奖机会
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 */
	public static function register($custId,$custName){
// 		$luckyOper=new LuckyOperation();
// 		$paramin=array('type'=>'register',
// 				'param'=>array(
// 						'luckyActId'=>3,
// 						'custId'=>$custId,
// 						'custName'=>$custName,
// 						'gennerCount'=>Item::LUCKY_REGIST_NUM,
// 				),
// 		);
// 		$luckyOper->execute($paramin);
		//注册不送，完善资料才送。
		//新用户的注册，必填完善资料，完善资料送，即是注册送
		//老用户完善资料也可以送
		return array("success"=>0,'data'=>array("msg"=>'方法已失效'));
	}
	
	/**
	 * 投诉送抽奖机会
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 */
	public static function complain($custId,$custName){
		$luckyOper=new LuckyOperation();
		$paramin=array('type'=>'ownerComplain',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$custId,
						'custName'=>$custName,
						'gennerCount'=>Item::LUCKY_COMPLAIN,
				),
		);
		$b=$luckyOper->execute($paramin);
		Yii::log("投诉送抽奖机会".$custId."==".json_encode($b),CLogger::LEVEL_INFO,'colourlife.core.lucky');
        return $b;
    }
	
	/**
	 * 个人报修送抽奖机会
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 */
	public static function personRepaire($custId,$custName){
		$luckyOper=new LuckyOperation();
		$paramin=array('type'=>'personalRepairs',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$custId,
						'custName'=>$custName,
						'gennerCount'=>Item::LUCKY_COMPLAIN,
				),
		);
		$b=$luckyOper->execute($paramin);
		Yii::log("个人报修送抽奖机会".$custId."==".json_encode($b),CLogger::LEVEL_INFO,'colourlife.core.lucky');
        return $b;
    }
	
	/**
	 * 公共报修送抽奖机会
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 */
	public static function publicRepaire($custId,$custName){
		$luckyOper=new LuckyOperation();
		$paramin=array('type'=>'publicRepairs',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$custId,
						'custName'=>$custName,
						'gennerCount'=>Item::LUCKY_COMPLAIN,
				),
		);
		$b=$luckyOper->execute($paramin);
		Yii::log("公共报修送抽奖机会".$custId."==".json_encode($b),CLogger::LEVEL_INFO,'colourlife.core.lucky');
        return $b;
    }
	
	/**
	 * 邀请注册送抽奖机会
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 */
	public static function invite($custId,$custName){
		$luckyOper=new LuckyOperation();
		$paramin=array('type'=>'invite',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$custId,
						'custName'=>$custName,
						'gennerCount'=>Item::LUCKY_INVITE_NUM,
				),
		);
		$b=$luckyOper->execute($paramin);
		Yii::log("邀请注册送抽奖机会".$custId."==".json_encode($b),CLogger::LEVEL_INFO,'colourlife.core.lucky');
		return $b;
	}
	
	/**
	 * 购买成功送抽奖机会
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 * @param 订单号 $orderId
	 */
	public static function order($custId,$custName,$orderId){
		$luckyOper=new LuckyOperation();
		$paramin=array('type'=>'orderAll',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$custId,
						'custName'=>$custName,
						'orderId'=>$orderId,
						'orderModel'=>'order',
						'gennerCount'=>Item::LUCKY_ORDER_NUM,
				),
		);
		$b=$luckyOper->execute($paramin);
		Yii::log("购买商品送抽奖机会".$custId."==".json_encode($b),CLogger::LEVEL_INFO,'colourlife.core.lucky');
	}
	
	/**
	 * 缴物业费等送抽奖机会
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 */
	public static function orderFrees($custId,$custName,$orderId){
		$luckyOper=new LuckyOperation();
		$paramin=array('type'=>'orderAll',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$custId,
						'custName'=>$custName,
						'orderId'=>$orderId,
						'orderModel'=>'otherFees',
						'gennerCount'=>Item::LUCKY_ORDER_NUM,
				),
		);
		$b=$luckyOper->execute($paramin);
		Yii::log("其他缴费送抽奖机会".$custId."==".json_encode($b),CLogger::LEVEL_INFO,'colourlife.core.lucky');
	}
	


	/**
	 * 零物业费订单送抽奖机会
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 */
	public static function propertyActivity($custId,$custName,$orderId){
		$luckyOper=new LuckyOperation();
		$paramin=array('type'=>'propertyActivity',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$custId,
						'custName'=>$custName,
						'orderId'=>$orderId,
						'gennerCount'=>Item::LUCKY_ORDER_NUM,
				),
		);
		$b=$luckyOper->execute($paramin);
		Yii::log("零物业费订单支付成功送抽奖机会,cust_id=".$custId."==参数".json_encode($paramin)."==返回".json_encode($b),CLogger::LEVEL_INFO,'colourlife.core.lucky');
	}


	/**
	 * 成功投资E理财送抽奖机会
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 */
	public static function investELICAI($custId,$custName,$orderId){
		$luckyOper=new LuckyOperation();
		$paramin=array('type'=>'ELICAI',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$custId,
						'custName'=>$custName,
						'orderId'=>$orderId,
						'gennerCount'=>Item::LUCKY_ELICAI_NUM,
				),
		);
		$b=$luckyOper->execute($paramin);
		Yii::log("成功投资E理财送抽奖机会,cust_id=".$custId."==参数".json_encode($paramin)."==返回".json_encode($b),CLogger::LEVEL_INFO,'colourlife.core.lucky');
		return $b;
	}


	/**
	 * 成功使用E维修送抽奖机会
	 * @param 用户id $custId
	 * @param 用户名 $custName
	 */
	public static function makeEWEIXIU($custId,$custName,$orderId){
		$luckyOper=new LuckyOperation();
		$paramin=array('type'=>'EWEIXIU',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$custId,
						'custName'=>$custName,
						'orderId'=>$orderId,
						'gennerCount'=>Item::LUCKY_EWEIXIU_NUM,
				),
		);
		$b=$luckyOper->execute($paramin);
		Yii::log("成功使用E维修送抽奖机会,cust_id=".$custId."==参数".json_encode($paramin)."==返回".json_encode($b),CLogger::LEVEL_INFO,'colourlife.core.lucky');
		return $b;
	}

	
	
	/**
	 * 完善资料送抽奖机会
	 * @param $custId
	 * @param $custName
	 * @return 成功 array ("success" => 1,"data" => array ("msg" => "" ) ); 
	 * <br/>失败 array ("success" => 0,"data" => array ("msg" => "已完善资料送过了"));
	 */
	public static function finishInfo($custId,$custName){
		$luckyOper=new LuckyOperation();
		$paramin=array('type'=>'finishInfo',
				'param'=>array(
						'luckyActId'=>Item::LUCKY_ACT_ID,
						'custId'=>$custId,
						'custName'=>$custName,
						'gennerCount'=>Item::LUCKY_FINISH_INFO,
				),
		);
		$b=$luckyOper->execute($paramin);
		Yii::log("=====custId=".$custId."更新资料成功(激活)".json_encode($b),CLogger::LEVEL_INFO,CLogger::LEVEL_INFO,'colourlife.core.lucky');
		return $b;
	}
}