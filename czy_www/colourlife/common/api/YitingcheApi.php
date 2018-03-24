<?php
/**
 * 易停车缴费接口服务类
 * User: gongzhiling
 * Date: 2016/12/03
 * Time: 9:00
 */
Yii::import('common.api.IceApi');
class YitingcheApi
{
    //彩之云在易停车里的厂商名
    private $vendor = 'BdAxoy9C';

    private $baseUrl = '/v1/eparking';
    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }
    
   /**
     * 获取易停车的月卡缴费类型
     * @param unknown $uuid
     * @param unknown $carnum
     * @return boolean|multitype:NULL
     */
    public function getMonthDetails($uuid,$carnum,$bid){
    	if (empty($uuid) || empty($carnum) || empty($bid)){
    		return false;
    	}
    	/* $token = MicroAuthService::getInstance()->getAccessToken();
    	if(!$token){
    		throw new CHttpException(400, '获取月卡缴费类型,token为空');
    	} */
    	
    	$interface = $this->baseUrl.'/member/search';
    	$postParam = array(
    		'plate' => $carnum,
    		'station' => $uuid,
    		'bid' => $bid,
    		'vendor' => $this->vendor,
    		'access_token'=> 0
    	);
    	Yii::log("参数：".json_encode($postParam), CLogger::LEVEL_INFO, 'colourlife.api.YitingcheApi.getMonthDetails');
    	$result = IceApi::getInstance()->getRemoteData($interface,$postParam,'POST');
    	Yii::log("返回结果：".json_encode($result), CLogger::LEVEL_INFO, 'colourlife.api.YitingcheApi.getMonthDetails');
    	if (!isset($result['code']) ||  $result['code'] !== 0 || !isset($result['content']) || empty($result['content'])) {
    		return false;
    	}
    	return $result['content'];
    }

    /**
     * 查询用户是否存在新收费系统
     * @param $mobile
     */
    public function getParkingUserExisted($mobile)
    {
    	if (empty($mobile)){
    		return false;
    	}
    	$token = MicroAuthService::getInstance()->getAccessToken();
    	if(!$token){
    		throw new CHttpException(400, '查询易停车用户是否存在,token为空');
    	}
    	//$mobile = '13810501126';
    	$interface = $this->baseUrl.'/user/existed';
    	$getParam = array(
    			'mobile' => $mobile,
    			'source' => $this->vendor,
    			'access_token'=>$token
    	);
    	
    	return IceApi::getInstance()->getRemoteData($interface,$getParam,'GET');
    }
    
    /**
     *根据uuid查询是否属于易停小区
     * @param string
     * @return string
     */
    public function getStation($uuid){
    	if (empty($uuid)){
    		return false;
    	}
    	/* $token = MicroAuthService::getInstance()->getAccessToken();
    	if(!$token){
    		throw new CHttpException(400, '判断是否属于易停车小区,token为空');
    	} */
    	$getParam = array(
    			'station' => $uuid,
    			'vendor' => $this->vendor,
    			'access_token'=> 0
    	);
    	$interface = $this->baseUrl.'/common/station';
    	Yii::log("参数：".json_encode($getParam), CLogger::LEVEL_INFO, 'colourlife.api.YitingcheApi.getStation');
    	$result = IceApi::getInstance()->getRemoteData($interface,$getParam,'GET');
    	Yii::log("返回结果：".json_encode($result), CLogger::LEVEL_INFO, 'colourlife.api.YitingcheApi.getStation');
    	if (!isset($result['code']) ||  $result['code'] !== 0 || !isset($result['content']) || empty($result['content'])) {
    		return false;
    	}
    	//判断是否属于易停车逻辑
    	if (!isset($result['content']['isExisted']) || $result['content']['isExisted'] != true){
    		return false;
    	}
    	return $result['content']['stations'];
    }
    
    /**
     * 第三方下单
     * @param unknown $orderID 订单号
     * @param unknown $amount 金额
     * @param unknown $uuid ice的小区ID
     * @param unknown $carnum 车牌号
     * @param unknown $startTime 有效开始时间
     * @param unknown $endTime 有效的结束时间
     * @param unknown $mobile 手机号
     * @return boolean|mixed
     */
    public function createEparkingOrder($orderID,$amount,$uuid,$carnum,$total,$mobile,$bid){
    	Yii::log("调用易停车创单接口开始", CLogger::LEVEL_INFO, 'colourlife.api.YitingcheApi.createEparkingOrder');
    	if (empty($orderID) || empty($uuid) || empty($carnum) || empty($total) || empty($mobile) || empty($bid)){
    		Yii::log("参数：".$orderID.'_'.$uuid.'_'.$carnum.'_'.$total.'_'.$mobile.'_'.$bid, CLogger::LEVEL_INFO, 'colourlife.api.YitingcheApi.createEparkingOrder');
    		return false;
    	}
    	/* $token = MicroAuthService::getInstance()->getAccessToken();
    	if(!$token){
    		throw new CHttpException(400, '易停车下单,token为空');
    	}
    	
    	$getParam = array('access_token'=>$token); */
    	$interface = $this->baseUrl.'/order/create';
    	$postParam = array(
    			'sn' => $orderID,
    			'amount' => $amount,
    			'station' => $uuid,
    			'plate' => $carnum,
    			'total' => $total,
    			'mobile' => $mobile,
    			'bid' => $bid,
    			'vendor' => $this->vendor,
    			'access_token' => 0
    	);
    	Yii::log("参数：".json_encode($postParam), CLogger::LEVEL_INFO, 'colourlife.api.YitingcheApi.createEparkingOrder');
    	$result = IceApi::getInstance()->getRemoteData($interface,$postParam,'POST');
    	Yii::log("返回结果：".json_encode($result), CLogger::LEVEL_INFO, 'colourlife.api.YitingcheApi.createEparkingOrder');
    	if (!isset($result['code']) ||  $result['code'] !== 0 || !isset($result['content']) || empty($result['content'])) {
    		return false;
    	}
    	return $result['content'];
    }
    
    /**
     * 支付成功后回调
     * @param unknown $id 调用device/order下单时返回的id
     * @param unknown $orderID 订单ID
     * @param unknown $amount 金额
     * @param unknown $uuid ice的小区ID
     * @param unknown $carnum 车牌号
     * @param unknown $mobile 手机号
     * @return boolean|mixed
     */
    public function notify($tnum,$sn){
    	if (empty($tnum) || empty($sn)){
    		return false;
    	}
    	/* $token = MicroAuthService::getInstance()->getAccessToken();
    	if(!$token){
    		throw new CHttpException(400, '支付成功回调,token为空');
    	} */
    	 
    	//$getParam = array('access_token'=>$token);
    	$interface = $this->baseUrl.'/order/status';
    	$getParam = array(
    			'tnum' => $tnum,
    			'sn' => $sn,
    			'status' => 'paid',
    			'vendor' => $this->vendor,
    			'access_token' => 0,
    	);
    	Yii::log("参数：".json_encode($getParam), CLogger::LEVEL_INFO, 'colourlife.api.YitingcheApi.notify');
    	$result = IceApi::getInstance()->getRemoteData($interface,$getParam,'POST');
    	Yii::log("返回结果：".json_encode($result), CLogger::LEVEL_INFO, 'colourlife.api.YitingcheApi.notify');
    	if (!isset($result['code']) ||  $result['code'] !== 0 || !isset($result['message']) || $result['message'] != 'success') {
    		return false;
    	}
    	return true;
    }
    
}