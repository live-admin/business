<?php

/**
 * Created by PhpStorm.
 * User: austin
 * Date: 6/22/16
 * Time: 11:38 AM
 */
class FinanceMicroService
{


	protected static $instance;

	private $add_client_url = "account/openClientAccount"; //添加用户
	private $fast_transaction_url = "transaction/fasttransaction";//快速交易
	private $query_client_url = "account/queryClientAccount"; //查询用户
	private $rate_transaction_url = 'account/getRate'; //获取地方饭票比率
	private $prepay_url = 'transaction/prepay'; //金融平台预支付url
	private $modify_client_url = "account/modifyClient"; //更新用户
	private $transaction_exchange = "transaction/batchexchange"; //批量汇兑交易
	private $aggregate_pay = "transaction/aggregatepay"; //批量汇兑交易
	private $prepay_for_color_url = 'transaction/prepayforcolor'; //金融平台预支付url(走钱生花支付)
	private $transaction_list = 'transaction/list'; //交易流水查询

	private $code = 'jrpt';
	/**
	 * 转发方式
	 * iCE 通过 iCE AG
	 * direct 直连
	 * @var string
	 */
	private $dispatcher = 'direct';
	private $destinationServer;
	private $interfaceQueryClientAccount = 'account/queryClientAccount';

	public function __construct()
	{
		if (defined('YII_DEBUG') && YII_DEBUG == true) {
			//$this->destinationServer = 'http://neotest.kakatool.cn:8097/';
			$this->destinationServer = 'http://finance.test.colourlife.com/';
		} else {
			$this->destinationServer = 'https://finance.colourlife.com:3681/';
		}
	}

	public static function getInstance()
	{
		if (!isset(self::$instance))
			self::$instance = new self;
		return self::$instance;
	}

	public function setDispatcter($dipatcher = 'iCE')
	{
		$this->dispatcher = $dipatcher;
	}


	protected function getInterface($interface = '')
	{
		return $this->dispatcher == 'iCE'
			? sprintf(
				'%s/%s',
				trim($this->code, ' /'),
				trim($interface, ' /')
			)
			: trim($interface, ' /');
	}

	protected function dispatch($interface = '',
	                            $queryData = array(), $postData = array(),
	                            $method = 'POST')
	{
		switch ($this->dispatcher) {
			case 'iCE':
				Yii::log(
				"fastTransaction调用iCE,interface：".$this->getInterface($interface),
				CLogger::LEVEL_ERROR,
				'colourlife.common.components.FinanceMicroService.fastTransaction'
						);
				return ICEService::getInstance()->dispatch(
					$this->getInterface($interface),
					$queryData,
					$postData,
					$method
				);
				break;
			case 'direct':
				MicroServiceDispatchService::getInstance()->setBaseUrl(
					$this->destinationServer
				);
				Yii::log(
				"fastTransaction调用direct,interface：".$this->getInterface($interface),
				CLogger::LEVEL_ERROR,
				'colourlife.common.components.FinanceMicroService.fastTransaction'
						);
				return MicroServiceDispatchService::getInstance()->dispatch(
					$this->getInterface($interface),
					$queryData,
					$postData,
					$method
				);
				break;
			default:
				throw new CHttpException(1001, '无法识别的微服务转发方式');
				break;
		}
	}


	/**
	 * 添加用户
	 * @param        $pano
	 * @param string $bano
	 * @param string $mobile
	 * @param int $gender
	 * @param string $birthday
	 * @param string $memo
	 * @param int $cannegative
	 * @return mixed
	 * @throws CHttpException
	 */
	public function addClientClient($pano, $bano = '', $name = '', $mobile = '', $gender = 1, $birthday = '', $memo = '', $cannegative = 0)
	{
		if (!$pano || empty($pano)) {
			throw new CHttpException(1001, '添加金融账号,平台账号为空');
		}

		$token = MicroAuthService::getInstance()->getAccessToken();
		if (!$token) {
			throw new CHttpException(1001, '添加金融账号,token为空');
		}

		$getParam = array('access_token' => $token);
		$postParam = array(
			'pano' => $pano,
			'bano' => $bano,
			'name' => $name,
			'mobile' => $mobile,
			'gender' => intval($gender),
			'birthday' => $birthday,
			'memo' => $memo,
			'cannegative' => intval($cannegative)

		);

		//return ICEService::getInstance()->dispatch($this->add_client_url, $getParam, $postParam, 'POST');
		return $this->dispatch(
				$this->add_client_url,
				$getParam,
				$postParam,
				'POST'
		);
	}

	public function queryClient($pano, $cano)
	{
		if (!$pano || empty($pano)) {
			throw new CHttpException(1001, '查询金融账号,平台账号为空');
		}

		$token = MicroAuthService::getInstance()->getAccessToken();
		if (!$token) {
			throw new CHttpException(1001, '查询金融账号,token为空');
		}

		$getParam = array('access_token' => $token);
		$postParam = array(
			'pano' => $pano,
			'cano' => $cano
		);

		//return ICEService::getInstance()->dispatch($this->query_client_url, $getParam, $postParam, 'POST');
		return $this->dispatch(
			$this->query_client_url,
			$getParam,
			$postParam,
			'POST'
		);
	}


	/**
	 * 快速交易
	 * @param        $money
	 * @param string $content
	 * @param int $orgtype
	 * @param        $orgaccountno
	 * @param int $desttype
	 * @param        $destaccountno
	 * @param string $detail
	 * @param string $callback
	 * @return mixed
	 * @throws CHttpException
	 */
	public function fastTransaction($money, $content = '', $orgtype = 0, $orgaccountno, $desttype = 0, $destaccountno, $detail = '', $callback = '', $orderno = '', $fixedorgmoney = 0 ,$starttime = 0, $stoptime = 0, $ver = 102)
	{

//		if(!$money||empty($money)){
//			throw new CHttpException(1001, '发起交易,金额为空');
//		}
		$money = round($money, 2);

		if (!$orgtype) {
			throw new CHttpException(1002, '发起交易,发起账号类型为空');
		}
		if (!$orgaccountno) {
			throw new CHttpException(1003, '发起交易,发起账号为空');
		}
		if (!$desttype) {
			throw new CHttpException(1004, '发起交易,接收账号类型为空');
		}
		if (!$destaccountno) {
			throw new CHttpException(1005, '发起交易,接收账号为空');
		}

		$token = MicroAuthService::getInstance()->getAccessToken();
		if (!$token) {
			throw new CHttpException(1006, '发起交易,token为空');
		}

		if (!$callback) {
			$callback = '';
		}
		if (!$orderno) {
			$orderno = $this->generateOrderNo();
		}

		$getParam = array('access_token' => $token);
		$postParam = array(
			'money' => $money,
			'orderno' => $orderno,
			'content' => $content,
			'orgtype' => intval($orgtype),
			'orgaccountno' => $orgaccountno,
			'desttype' => intval($desttype),
			'destaccountno' => $destaccountno,
			'detail' => $detail,
			'callback' => $callback,
			'fixedorgmoney' => $fixedorgmoney
		);

		//交易开始时间
		if (!empty($starttime) && $starttime != 0){
			$postParam['starttime'] = $starttime;
		}
		//交易结束时间
		if (!empty($stoptime) && $stoptime != 0){
			$postParam['stoptime'] = $stoptime;
		}

		if ($ver) {
            $postParam['ver'] = $ver;
        }

		Yii::log(
		"fastTransaction调用,参数：".json_encode($postParam),
		CLogger::LEVEL_ERROR,
		'colourlife.common.components.FinanceMicroService.fastTransaction'
				);
		//return ICEService::getInstance()->dispatch($this->fast_transaction_url, $getParam, $postParam, 'POST');
		return $this->dispatch(
				$this->fast_transaction_url,
				$getParam,
				$postParam,
				'POST'
		);

	}


    /**
     * 生成订单号
     * @param int $length
     * @return string
     */
	public function generateOrderNo($length = 10)
	{

        $candidate = range(0, 9);
        $rand = [];
        for ($i = 0; $i < $length; $i++) {
            $rand[] = array_rand($candidate);
        }
        return date('YmdHis') . implode('', $rand);

		date_default_timezone_set('PRC');
		$now = date("YmdHis");

		return $now . time();

	}

	/**
	 * 生成特俗前缀的手机号码
	 * @return string
	 */
	public function randMobile()
	{

		$length = 9;
		$pattern = '1234567890';    //字符池
		$key = '10';
		for ($i = 0; $i < $length; $i++) {
			$key .= $pattern{mt_rand(0, 9)};    //生成php随机数
		}
		return $key;

	}

	/**
	 * 检查手机号码
	 * @param $mobile
	 * @return bool
	 */
	function check_mobile($mobile)
	{

		if (!$mobile) {
			return false;
		}
		if (preg_match("/^1\d{10}$/", $mobile)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 获取当前执行环境下彩之云全国饭票的pano参数
	 * @return array
	 */
	public function getCustomerPano()
	{
		if (defined('YII_DEBUG') && YII_DEBUG == true) {
			$result = array(
				'pano' => Yii::app()->params['fanance']['debug_customer_pano'],
				'atid' => Yii::app()->params['fanance']['debug_customer_atid']
			);
		} else {
			$result = array(
				'pano' => Yii::app()->params['fanance']['production_customer_pano'],
				'atid' => Yii::app()->params['fanance']['production_customer_atid']
			);
		}
		return $result;
	}

	/**
	 * 获取当前执行环境下彩管家全国饭票的pano参数
	 * @return array
	 */
	public function getEmployeePano()
	{
		if (defined('YII_DEBUG') && YII_DEBUG == true) {
			$result = array(
				'pano' => Yii::app()->params['fanance']['debug_employee_pano'],
				'atid' => Yii::app()->params['fanance']['debug_employee_atid']
			);
		} else {
			$result = array(
				'pano' => Yii::app()->params['fanance']['production_employee_pano'],
				'atid' => Yii::app()->params['fanance']['production_employee_atid']
			);
		}
		return $result;

	}

	/**
	 * 获取地方饭票比率
	 * @param unknown $pano
	 * @param unknown $atid
	 * @throws CHttpException
	 * @return mixed
	 */
	public function getRate($pano, $atid)
	{
		if (!$pano || empty($pano)) {
			throw new CHttpException(1001, '查询地方饭票比率,平台账号为空');
		}

		if (!$atid) {
			throw new CHttpException(1002, '查询地方饭票比率,发起账号类型为空');
		}
		$token = MicroAuthService::getInstance()->getAccessToken();
		if (!$token) {
			throw new CHttpException(1001, '查询地方饭票比率,token为空');
		}

		$getParam = array('access_token' => $token);
		$postParam = array(
			'pano' => $pano,
			'atid' => $atid,
		);
		//return ICEService::getInstance()->dispatch($this->rate_transaction_url, $getParam, $postParam, 'POST');
		return $this->dispatch(
				$this->rate_transaction_url,
				$getParam,
				$postParam,
				'POST'
		);
	}

	/**
	 * 金融平台消费接口
	 * @param unknown $money 交易金额
	 * @param string $content 交易说明（显示给用户看的）
	 * @param number $orgtype 支付账号类型atid
	 * @param unknown $orgaccountno 支付账号，微信，支付宝等人民币交易填0 bano
	 * @param number $desttype 收款账号类型atid
	 * @param unknown $destaccountno 收款账号
	 * @param string $detail 交易明细
	 * @param number $starttime 交易生效时间, unix时间戳
	 * @param number $stoptime 交易失效时间，unix时间戳
	 * @param string $callback 回调地址,如http://abc.com/trasaction_handler
	 * @param string $orderno 接入方的内部交易号
	 * @throws CHttpException
	 */
	public function prepay($money,$content = '',$orgtype = 0,$orgaccountno,$desttype = 0,$destaccountno,$detail = '',$starttime = 0,$stoptime = 0, $callback = '',$orderno = '',$remoteip = ''){
		if(!$money||empty($money)){
			throw new CHttpException(1001, '预支付,金额为空');
		}
		if ($money < 0){
			throw new CHttpException(1002, '预支付,输入金额错误');
		}
		$money = round($money,2);

		/* if(!$orgtype){
		 throw new CHttpException(2002, '发起交易,发起账号类型为空');
		} */
		/* if(!$orgaccountno){
		 throw new CHttpException(2003, '发起交易,发起账号为空');
		} */
		if(!$desttype){
			throw new CHttpException(1003, '预支付,接收账号类型为空');
		}
		if(!$destaccountno){
			throw new CHttpException(1004, '预支付,接收账号为空');
		}

		$token = MicroAuthService::getInstance()->getAccessToken();
		if(!$token){
			throw new CHttpException(1005, '预支付,token为空');
		}

		if(!$callback){
			$callback='';
		}
		if(!$orderno){
			$orderno = $this->generateOrderNo();
		}

		$getParam = array(
				'access_token' => $token
		);
		$postParam = array(
				'money' => $money,
				'orderno' => $orderno,
				'content' => $content,
				'orgtype' => intval($orgtype),
				'orgaccountno' => $orgaccountno,
				'desttype' => intval($desttype),
				'destaccountno' => $destaccountno,
				'detail' => $detail,
				'starttime' => $starttime,
				'stoptime' => $stoptime,
				'callback' => $callback,
				'remoteip' => $remoteip
		);
		return $this->dispatch(
				$this->prepay_url,
				$getParam,
				$postParam,
				'POST'
		);
	}

	/**
	 * 更新用户信息
	 * @param unknown $pano
	 * @param string $cno
	 * @param string $name
	 * @param string $mobile
	 * @param number $gender
	 * @param string $birthday
	 * @param string $memo
	 * @throws CHttpException
	 */
	public function modifyClient($pano, $cno = '', $name = '', $mobile = '', $gender = 1, $birthday = '', $memo = '')
	{
		if (!$pano || empty($cno)) {
			throw new CHttpException(1001, '更新金融平台用户信息,平台账号为空');
		}

		$token = MicroAuthService::getInstance()->getAccessToken();
		if (!$token) {
			throw new CHttpException(1001, '更新金融平台用户信息,token为空');
		}

		$getParam = array('access_token' => $token);
		$postParam = array(
				'pano' => $pano,
				'cno' => $cno,
				'name' => $name,
				'mobile' => $mobile,
				'gender' => intval($gender),
				'birthday' => $birthday,
				'memo' => $memo,
		);

		//return ICEService::getInstance()->dispatch($this->add_client_url, $getParam, $postParam, 'POST');
		return $this->dispatch(
				$this->modify_client_url,
				$getParam,
				$postParam,
				'POST'
		);
	}

	/**
	 * 快速兑换地方饭票
	 * @param $orderno  业务系统交易编号
	 * @param $content  交易说明（显示给用户的内容）
	 * @param $orgaccounts  json数组[{"atid":1,"ano":"xxxxx","money":1.00}]
	 * @param $desttype  收款账号类型，atid
	 * @param $destaccountno  收款帐号编号
	 * @param string $detail  交易明细
	 * @param int $starttime  交易生效时间
	 * @param int $stoptime  交易失效时间
	 * @param string $callback  业务系统回调地址
	 */
	public function batchExchange($orderno  , $orgaccounts, $desttype , $destaccountno ,$content='地方饭票转全国饭票' , $detail = '' , $starttime = 0 , $stoptime = 0 , $callback = ''){
		if(!$orderno || $orgaccounts || $desttype || $destaccountno){
			throw new CHttpException(1001, '快速兑换地方饭票,平台账号为空');
		}

		$token = MicroAuthService::getInstance()->getAccessToken();
		if (!$token) {
			throw new CHttpException(1001, '快速兑换地方饭票,token为空');
		}
		$getParam = array('access_token' => $token);
		$postParam = array(
			'orderno' => $orderno,
			'content' => $content,
			'orgaccounts' => $orgaccounts,
			'desttype' => $desttype,
			'destaccountno' => $destaccountno,
			'detail' => $detail,
			'starttime' => $starttime,
			'stoptime' => $stoptime,
			'callback' => $callback,
		);
		;
		return $this->dispatch(
			$this->transaction_exchange,
			$getParam,
			$postParam,
			'POST'
		);
	}

	/*
	 *聚合支付接口
	 * $orderno  	业务系统交易编号
	 * $content  	交易说明（显示给用户的内容）
	 * $orgaccounts  	支付帐号，json数组，如[{"atid":1,"ano":"xxxxx","money":1.00}]
	 * $desttype 	收款账号类型，atid
	 * $destaccountno  收款帐号编号pano
	 * $detail			交易明细
	 * $starttime	查询起始时间，时间戳	可选
	 * $stoptime	查询结束时间，时间戳	可选
	 * $callback	业务系统回调地址
	 * $password		支付密码
	 * $passwordtime		支付密码时间
	 */
	public function aggregatePay($orderno , $content='' , $orgaccounts , $desttype ,$destaccountno ,$detail='' ,$starttime='' , $stoptime='' , $callback='', $password='' , $passwordtime=''){
		Yii::log('金融地方饭票支付开始：结果：'.json_encode($orderno),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.LocalPacket222');
		if(!$orderno || !$orgaccounts || !$desttype || !$destaccountno){
			throw new CHttpException(1001, '饭票聚合支付,关键参数为空');
		}

		$token = MicroAuthService::getInstance()->getAccessToken();
		if (!$token) {
			throw new CHttpException(1001, '饭票聚合支付,token为空');
		}
		Yii::log('金融地方饭票支付开始2：结果：'.json_encode($token),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.LocalPacket222');
		$getParam = array('access_token' => $token);
		$postParam = array(
			'orderno' => $orderno,
			'content' => $content,
			'orgaccounts' => $orgaccounts,
			'desttype' => $desttype,
			'destaccountno' => $destaccountno,
			'detail' => $detail,
			'starttime' => $starttime,
			'stoptime' => $stoptime,
			'callback' => $callback,
			'password' => $password,
			'passwordtime' => $passwordtime,
		);
		;
		Yii::log('聚合支付开始：结果：'.json_encode($postParam),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.LocalPacket222');
		return $this->dispatch(
			$this->aggregate_pay,
			$getParam,
			$postParam,
			'POST'
		);
	}

	/**
	 * 金融平台消费接口（走钱生花交易SDK）
	 * @param unknown $money 交易金额
	 * @param string $content 交易说明（显示给用户看的）
	 * @param number $orgtype 支付账号类型atid
	 * @param unknown $orgaccountno 支付账号，微信，支付宝等人民币交易填0 bano
	 * @param number $desttype 收款账号类型atid
	 * @param unknown $destaccountno 收款账号
	 * @param string $detail 交易明细
	 * @param number $starttime 交易生效时间, unix时间戳
	 * @param number $stoptime 交易失效时间，unix时间戳
	 * @param string $callback 回调地址,如http://abc.com/trasaction_handler
	 * @param string $orderno 接入方的内部交易号
	 * @throws CHttpException
	 */
	public function prepayForColor($money,$content = '',$orgtype = 0,$orgaccountno,$desttype = 0,$destaccountno,$detail = '',$starttime = 0,$stoptime = 0, $callback = '',$orderno = '',$remoteip = '' ,$customerId = ''){
		if(!$money||empty($money)){
			throw new CHttpException(1001, '预支付,金额为空');
		}
		if ($money < 0){
			throw new CHttpException(1002, '预支付,输入金额错误');
		}
		$money = round($money,2);

		/* if(!$orgtype){
		 throw new CHttpException(2002, '发起交易,发起账号类型为空');
		} */
		/* if(!$orgaccountno){
		 throw new CHttpException(2003, '发起交易,发起账号为空');
		} */
		if(!$desttype){
			throw new CHttpException(1003, '预支付,接收账号类型为空');
		}
		if(!$destaccountno){
			throw new CHttpException(1004, '预支付,接收账号为空');
		}

		$token = MicroAuthService::getInstance()->getAccessToken();
		if(!$token){
			throw new CHttpException(1005, '预支付,token为空');
		}

		if(!$callback){
			$callback='';
		}
		if(!$orderno){
			$orderno = $this->generateOrderNo();
		}

		$getParam = array(
			'access_token' => $token
		);
		$postParam = array(
			'money' => $money,
			'orderno' => $orderno,
			'content' => $content,
			'orgtype' => intval($orgtype),
			'orgaccountno' => $orgaccountno,
			'desttype' => intval($desttype),
			'destaccountno' => $destaccountno,
			'detail' => $detail,
			'starttime' => $starttime,
			'stoptime' => $stoptime,
			'callback' => $callback,
			'remoteip' => $remoteip,
			'customerId' => $customerId
		);
		return $this->dispatch(
			$this->prepay_for_color_url,
			$getParam,
			$postParam,
			'POST'
		);
	}

	/*
	 * 金融平台账户交易明细查询
	 * utype   类型，1：接入方应用；2：商家；3：用户
	 * uno	用户编号（用于创建金融平台账户编号，finance_customer_relation中的cno）
	 * atid	支付类型
	 * ano	用户金融账号（finance_customer_relation中的cano）
	 * pano	金融平台应用编号
	 * transtype	交易类型，0：全部；1：消费；2：充值；3：转账；4：提现
	 * ispay	是否区分付款/支付方，0：全部，1：支付，2：收款
	 * starttime	交易开始时间戳，unix时间戳
	 * stoptime	交易结束时间戳，unix时间戳
	 * skip	返回页数，默认0
	 * limit每页记录数，默认20
	 */
	public function transactionList($uno = '' , $atid = '' , $ano ='' , $pano = '' ,
									$transtype = '' , $ispay = '' ,$starttime = '' , $stoptime = '' ,
									$skip=0 , $limit = 10)
	{
		$token = MicroAuthService::getInstance()->getAccessToken();
		if(!$token){
			throw new CHttpException(1005, '交易流水,token为空');
		}
		$getParam = array(
			'access_token' => $token
		);
		$postParam = array(
			'utype' => 3,
			'uno' => $uno,
			'atid' => $atid,
			'ano' => $ano,
			'pano' => $pano,
			'transtype' => $transtype,
			'ispay' => $ispay,
			'starttime' => $starttime,
			'stoptime' => $stoptime,
			'skip' => $skip,
			'limit' => $limit
		);
		return $this->dispatch(
			$this->transaction_list,
			$getParam,
			$postParam,
			'POST'
		);
	}

}