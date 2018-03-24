<?php

class TestSenLocationController extends CController{
    
    private static $key = "2";
    private static $secret = "TestColourApi";
    private static $cid = "126";
//    public function actionIndex(){
//        $customer_id =  Yii::app()->request->getParam('customer_id');
//        $customer = Customer::model()->findByPk($customer_id);
//        $mobile=$customer->mobile;
//        $time=$customer->create_time;
//        $sign = md5('customer_id='.$customer_id.'||mobile='.$mobile.'||time='.$time);
//        $customer_id=$customer_id*778+1778;
//        echo $customer_id."<br>";
//        echo $sign."<br>";
//    }
    public function actionSanFang(){
        $thirdFee = new ColourlifeToThirdFee(self::$key, self::$secret);
//        $orderData = array(
//                'mobile' =>'15989573790', //手机号
//                'cSn' => 3333, //内部订单号(商家单号)。整个业务系统唯一。
//                'cid' => self::$cid, //商家的id号
//                'amount' => 15 , //支付金额
//                'isRed' => '1', //是否使用红包. 1-使用，0-不使用
//                'callbackUrl' => 'http://www.baidu.com', //回调地址
//        );
        $orderData = array(
            'csn' =>100400,
            'charge_status' =>2,
        );
        $result= $thirdFee->updateAutoSaleOrder($orderData);
        dump($result);
    }
    public function actionLiuLiang(){
        $thirdFee = new ChuangLan();
        $orderData = array(
            'mobile'=>'15013780576',
            'package'=>'00500',
            'ext_id'=>'8769098640',
        );
        $result= $thirdFee->getLiuLiangOther($orderData);
        dump($result);
    }
    public function actionSelectOrder(){
        $thirdFee = new ChuangLan();
        $orderData = array(
            'ext_id'=>'88888888',
        );
        $result= $thirdFee->selectOrderStatus($orderData);
        dump($result);
    }
    public function actionEJia(){
        $thirdFee = new ChuangLan();
        $orderData = array(
            'mobile'=>'13556801874',
            'couponTypeCode'=>'xx20160629180205',
        );
        $result= $thirdFee->getEJiaZheng($orderData);
        dump($result);
    }
    
    public function actionGetChouChance(){
        $startDay='2016-06-28';
		$endDay='2016-07-29 23:59:59';
		$start_time=mktime(0,0,0);
		$end_time=time();
	
		if ($end_time > strtotime($endDay) || $start_time < strtotime($startDay)) {
			exit;
		}
		$sqlSelect="select id,customer_id from invite where status=1 and effective=1 and is_send=0 and create_time between {$start_time} and {$end_time}";
		$query = Yii::app()->db->createCommand($sqlSelect);
		$result = $query->queryAll();
		if(!empty($result)){
			$str = '';
			foreach($result as $v){
				$cusArr=Customer::model()->findByPk($v['customer_id']);
				if (empty($cusArr)){
					continue;
				}
				$customer_id=$cusArr->id;
				$create_time=time();
				$str .= "(" .$customer_id.",'',2,0,".$create_time."),";
				$sqlUpdate="update invite set is_send=1 where id=".$v['id']." and is_send=0";
				Yii::app()->db->createCommand($sqlUpdate)->execute();
			}
			if(!empty($str)){
				$str = trim($str, ',');
				$sqlInsert = "insert into siqingchou_chance(customer_id,open_id,type,status,create_time) values {$str}";
				$res = Yii::app()->db->createCommand($sqlInsert)->execute();
			}else{
				Yii::log("司庆抽奖".date('Y-m-d',time())."str为空",CLogger::LEVEL_INFO,'colourlife.core.SiQingChouJiangYaoQing');
			}
			if($res){
				Yii::log("司庆抽奖".date('Y-m-d',time())."邀请注册获取抽奖机会成功",CLogger::LEVEL_INFO,'colourlife.core.SiQingChouJiangYaoQing');
			}else{
				Yii::log("司庆抽奖".date('Y-m-d',time())."邀请注册获取抽奖机会失败",CLogger::LEVEL_INFO,'colourlife.core.SiQingChouJiangYaoQing');
			}
		}
    }
    public function actionTestHanShu(){
        $str = 'apple';
        echo sha1($str)."<br/>";
        $mobile = "18202322752";  //要查询的电话号码
        $content = $this->get_mobile_area($mobile);
        dump($content);
    }
    function get_mobile_area($mobile){
        $sms = array('supplier'=>'');    //初始化变量
        //根据淘宝的数据库调用返回值
        $url = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=".$mobile."&t=".time();
        $content = file_get_contents($url);
        $content=iconv("gbk", "utf-8//IGNORE",$content);
        $sms['supplier'] = substr($content, "79", "12");
        return $sms;
    }
    public function actionProductToken(){
        Yii::import('common.api.IceApi');
		$coloure = IceApi::getInstance();
		$res= $coloure->appAuth('66a2b3c4d5e6f7a8b9c1','882b3c4d5e6f7a8b9c0d1a2b3c4d5e6f');
		dump($res);
    }
    
}