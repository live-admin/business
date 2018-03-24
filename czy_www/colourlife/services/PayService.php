<?php
/**
 * 支付服务
 */
Yii::import('common.services.BaseService');
class PayService extends BaseService{

	private $pay_list_url = 'http://caizhiyun.kakatool.cn:8081/';    //支付方式列表url 测试
	//private $pay_list_url = 'http://colour.kakatool.cn';    //支付方式列表url 正式
	private $wsq_callback_url = 'http://test.kakatool.cn:8082/'; //微商圈交易回调url 测试
	//private $wsq_callback_url = 'http://api.kakatool.com'; //微商圈交易回调url 正式
	private $url = '';

	private $pay_secret = 'X1Vz1BQxqlijaxAmAD4PPQ7cmM9OOlceeHglph5JjOU';


	public function __construct()
	{
		if (defined('YII_DEBUG') && YII_DEBUG == true) {
			$this->pay_list_url = 'http://caizhiyun.kakatool.cn:8081/';//测试
			$this->wsq_callback_url = 'http://test.kakatool.cn:8082/';//测试
		} else {
			$this->pay_list_url = 'http://colour.kakatool.cn';//测试
			$this->wsq_callback_url = 'http://api.kakatool.com';//测试
		}
	}
	

	/**
	 * 通过商户号获取合并后的支付方式
	 * @param unknown $businessId  商户ID
	 * @param unknown $customerId  用户ID
	 * @return boolean|Ambigous <boolean, unknown, unknown>
	 */
	public function getPayListByBusinessId($businessId,$customerId,$isOnlyInfo = false){
		if (empty($customerId)){
			Yii::log("customerId为空",CLogger::LEVEL_ERROR,'colourlife.core.PayService.getPayListByBusinessId');
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '查询支付方式,用户ID为空', '查询支付方式,用户ID为空');
		}
		//先从缓存里获取
		/* $redisKey = md5('business_payment_list:'.$businessId.$customerId);
		$data = Yii::app()->rediscache->get($redisKey);
		if (!empty($data['info']) && !empty($data['list'])){
			if ($isOnlyInfo){
				return $data['info'];
			}
			return $data;
		} */
		Yii::log("调用金融平台的支付列表开始",CLogger::LEVEL_ERROR,'colourlife.core.PayService.getPayListByBusinessId');
		$bidPayList = $this->getBusinessPayList($businessId, $customerId);
		
		if (empty($bidPayList) || empty($bidPayList['list'])){
			Yii::log("bidPayList或bidPayList->list为空",CLogger::LEVEL_ERROR,'colourlife.core.PayService.getPayListByBusinessId');
			return $this->sendErrorMessage(false, ErrorCode::LOGIC_ERROR, '查询支付方式,商户的支付方式为空', '查询支付方式,商户的支付方式为空');
		}
		//只要商户信息
		if ($isOnlyInfo){
			return array(
						'id' => $bidPayList['bizinfo']['bid'],
						'name' => $bidPayList['bizinfo']['name']
					);
		}
		//获取用户的支付方式
		$payList = $this->mergePayList($bidPayList['list'],$customerId);
		$data = array(
				'info' =>array(
					'id' => $bidPayList['bizinfo']['bid'],
					'name' => $bidPayList['bizinfo']['name']
				) ,
				'list' => $payList
		);
		//Yii::app()->rediscache->set($redisKey, $data, 180); //缓存3分钟
		return $data;
	}
	
	/**
	 * 合并支付方式
	 * @param unknown $financeCustomer
	 * @param unknown $bidPayList
	 * @return boolean|unknown
	 */
	private function mergePayList($payList,$customerId){
		Yii::log('合并支付方式开始,参数为：'.json_encode($payList).',用户ID为：'.$customerId,CLogger::LEVEL_ERROR, 'colourlife.core.PayService.mergePayList');
		$data = array();
		foreach ($payList as $key => $val){
			$payType = FinancePayType::model()->find("pano = :pano",array(
				':pano' => $val['pano']
			));
			//支付类型不存在，直接过滤掉
			if (empty($payType)){
				Yii::log('pano'.$val['pano'].'支付方式不存在',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.mergePayList');
				continue;
			}
			$val['atid'] = $payType->atid;
			if ($payType->typeid == 6 || $payType->typeid == 7){ //6全国，7地方
				$financeCustomer = FinanceCustomerRelateModel::model()->getPayListByPanoAndCustomerID($val['pano'],$customerId);
				if (empty($financeCustomer)){
					Yii::log('pano'.$val['pano'].'饭票关联账号不存在',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.mergePayList');
					continue;
				}
				if ($payType->typeid == 6){
					$val['is_quanguo'] = 1;
				}else {
					$val['is_quanguo'] = 0;
				}
				$customerMoney = $this -> getCustomerBalanceInfo($financeCustomer['pano'], $financeCustomer['cano']);
				$val['balance'] = $customerMoney;
				$val['cano'] = $financeCustomer['cano'];
				$val['payment_type'] = 'fanpiao';
			}else {
				$val['payment_type'] = 'third';
			}
			$data[$val['pano']] = $val;
		}
		Yii::log('返回支付列表：'.json_encode($data),CLogger::LEVEL_ERROR, 'colourlife.core.PayService.mergePayList');
		return $data;
	}
	

	/**
	 * 获取商户的支付方式
	 * @param unknown $businessId
	 * @param unknown $customerId
	 * @return boolean|unknown
	 */
	public function getBusinessPayList($businessId,$customerId){
		if (empty($businessId)){
			Yii::log("businessId为空",CLogger::LEVEL_ERROR,'colourlife.core.PayService.getBusinessPayList');
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '查询支付方式,商户的支付方式为空', '查询支付方式,商户的支付方式为空');
		}
		Yii::log('调用微商圈接口开始：',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getBusinessPayList');
		$param = array(
				'bid' => $businessId
		);
		$preFun = 'tpluspay/check';
		$re = new ConnectWetown();
		$result = $re->getRemoteData($preFun, $param, $this->pay_list_url,false, $customerId);
		Yii::log('调用微商圈接口返回结果：'.$result,CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getBusinessPayList');
		if (!empty($result)){
			$result = json_decode($result, true);
			if ($result['result'] == 0){
				Yii::log('调用微商圈接口返回结果result=0：'.json_encode($result),CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getBusinessPayList');
				return $result;
			}else {
				Yii::log('调用微商圈接口返回结果result!=0：'.$result['reason'],CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getBusinessPayList');
				return $this->sendErrorMessage(false, ErrorCode::API_RESPONSE_FAIL, $result['reason'], $result['reason']);
			}
		}else {
			Yii::log('调用接口异常返回的错误信息：'.$result,CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getBusinessPayList');
			return $this->sendErrorMessage(false, ErrorCode::API_REQUEST_FAIL, '调用远程请求失败', '调用远程请求失败');
		}
	}
	
	/**
	 * 查询用户余额信息
	 */
	public function getCustomerBalanceInfo($pano,$cano){
		if (empty($pano)){
			return 0.00;
		}
		if (empty($cano)){
			return 0.00;
		}
		Yii::log('调用调用金融平台接口开始',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getCustomerBalanceInfo');
		$financeService = new FinanceMicroService();
		$info = $financeService->queryClient($pano, $cano);
		Yii::log('调用调用金融平台接口结束：'.json_encode($info),CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getCustomerBalanceInfo');
		if (isset($info['account']['money'])){
			return $info['account']['money'];
		}else {
			return 0.00;
		}
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
	public function fasttransaction($money,$content = '',$orgtype = 0,$orgaccountno,$desttype = 0,$destaccountno,$detail = '',$starttime = 0,$stoptime = 0, $callback = '',$orderno = '', $fixedorgmoney = 0){
		if(!$money||empty($money)){
			Yii::log('参数为空',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.fasttransaction');
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '发起交易,金额为空', '发起交易,金额为空');
		}	
		try {
			Yii::log('调用接口开始',CLogger::LEVEL_INFO, 'colourlife.core.PayService.fasttransaction');
			$result = FinanceMicroService::getInstance()->fastTransaction(
					$money,           //交易金额
					$content,                     //备注
					intval($orgtype),    //支付类型
					$orgaccountno,     //支付账号
					intval($desttype),   //收款类型
					$destaccountno,    //收款账号
					$detail,                   //备注
					$callback,                     //回调方法，快速交易不需要设置
					$orderno,             //本地交易编号
					$fixedorgmoney,  //固定交易 ：1有兑换比率，0普通交易
					$starttime,  //开始时间
					$stoptime  //结束时间
			);
			Yii::log('调用接口结束',CLogger::LEVEL_INFO, 'colourlife.core.PayService.fasttransaction');
			//$result = ICEService::getInstance()->dispatch($this->fast_transaction_url,$getParam,$postParam,'POST');
			if (isset($result['payinfo'])){
				return $result;
			}else {
				Yii::log('调用接口失败',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.fasttransaction');
				return $this->sendErrorMessage(false, ErrorCode::API_RESPONSE_FAIL, '发起交易,payinfo不存在', '发起交易,payinfo不存在');
			}
		}catch (Exception $e){
			$message = $e->getMessage();
			$code = $e->getCode();
			//记录异常
			Yii::log('调用接口异常：code='.$code.',返回错误信息：'.$message,CLogger::LEVEL_ERROR, 'colourlife.core.PayService.fasttransaction');
			return $this->sendErrorMessage(false, ErrorCode::API_REQUEST_FAIL, $code.':'.$message, $code.':'.$message);
		}
	}
	
	/**
	 * 生成订单号
	 * @return string
	 */
	public function generateOrderNo(){
	
		date_default_timezone_set('PRC');
		$now= date("YmdHis");
	
		return $now.time();
	
	}
	
	/**
	 * 微商圈交易回调
	 * @param unknown $customerId 用户ID
	 * @param unknown $bid 商家编号
	 * @param unknown $tno 交易编号
	 * @param unknown $mobile 支付用户手机号
	 * @param unknown $amount 交易金额
	 * @param unknown $payamount 实际交易金额
	 * @param unknown $discount 折扣
	 * @param unknown $payid 支付方式
	 * @param unknown $creationtime 交易时间
	 * @param unknown $content 交易详情
	 * @param unknown $memo 交易备注
	 * @return mixed|boolean
	 */
	public function wsqCallBack($customerId,$bid,$tno,$mobile,$amount,$payamount,$discount,$payid,$creationtime,$content = '',$memo = ''){
		if (empty($customerId)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '交易回调,用户ID为空', '交易回调,用户ID为空');
		}
		if (empty($bid)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '交易回调,商家编号为空', '交易回调,商家编号为空');
		}
		if (empty($tno)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '交易回调,交易编号为空', '交易回调,交易编号为空');
		}
		if (empty($amount)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '交易回调,交易金额为空', '交易回调,交易金额为空');
		}
		if (empty($payamount)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '交易回调,实际交易金额为空', '交易回调,实际交易金额为空');
		}
		if ($amount < 0 || $payamount < 0){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '交易回调,金额错误', '交易回调,金额错误');
		}
		if (empty($discount)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '交易回调,折扣为空', '交易回调,折扣为空');
		}
		if (empty($payid)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '交易回调,支付方式为空', '交易回调,支付方式为空');
		}
		if (empty($creationtime)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '交易回调,交易时间为空', '交易回调,交易时间为空');
		}
		/* if (empty($content)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '交易回调,交易详情为空', '交易回调,交易详情为空');
		} */
		$param = array(
				'bid' => $bid,
				'tno' => $tno,
				'mobile' => $mobile,
				'amount' => $amount,
				'payamount' => $payamount,
				'discount' => $discount,
				'payid' => $payid,
				'creationtime' => $creationtime,
				'content' => $content,
				'memo' => $memo
		);
		Yii::log('回调微商圈参数：'.json_encode($param),CLogger::LEVEL_ERROR, 'colourlife.core.PayService.wsqCallBack');
		$preFun = 'tpluspay/callback';
		//$re = new ConnectWetown();
		$result = $this->getRemoteData($preFun, $param, $this->wsq_callback_url,$customerId);
		if (!empty($result)){
			Yii::log('调用接口返回信息：'.$result,CLogger::LEVEL_ERROR, 'colourlife.core.PayService.wsqCallBack');
			$result = json_decode($result, true);
			if ($result['result'] == 0){
				Yii::log('调用接口返回信息成功：'.json_encode($param),CLogger::LEVEL_ERROR, 'colourlife.core.PayService.wsqCallBack');
				return $result['tnum']; //返回交易号
			}else {
				Yii::log('调用接口返回信息失败：'.$result['reason'],CLogger::LEVEL_ERROR, 'colourlife.core.PayService.wsqCallBack');
				return $this->sendErrorMessage(false, ErrorCode::API_RESPONSE_FAIL, $result['reason'], $result['reason']);
			}
		}else {
			Yii::log('调用接口异常返回的错误信息：'.$result,CLogger::LEVEL_ERROR, 'colourlife.core.PayService.wsqCallBack');
			return $this->sendErrorMessage(false, ErrorCode::API_REQUEST_FAIL, '调用远程请求失败', '调用远程请求失败');
		}
	}
	
	/**
	 * 调用远程请求
	 * @param string $preFun
	 * @param string $param
	 * @param string $resetUrl
	 * @param string $color
	 * @return string|Ambigous <string, mixed>
	 */
	public function getRemoteData($preFun = null, $param = null, $resetUrl = null, $color = null){
		Yii::log('payservice远程请求：'.json_encode($param),CLogger::LEVEL_INFO, 'colourlife.core.PayService.getRemoteData');
		//时间
		$ts = time();
		//colorid id号
		if (!empty($color)){
			$colorid = $color;
		}else {
			$colorid = Yii::app()->user->id;
		}
		if (empty($colorid)) return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '请登录后再使用', '请登录后再使用');
		Yii::log('payservice远程请求：colorid:'.$colorid,CLogger::LEVEL_INFO, 'colourlife.core.PayService.getRemoteData');
		//key
		$key = 'test';
		//密钥
		$secret = 'test4wetown';
		//版本号
		$ve = '1.0';
		//前缀
		$preStr = '/v1/';
		//地址
		$url = empty($resetUrl) ? $this->url : $resetUrl;
		//取得参数
		$paramArr = $param;
		//固定参数
		$signArr = array('colorid'=>$colorid, 'key'=>$key, 'ts'=>$ts, 've'=>$ve);
	
		$signMergeArr = array_merge($signArr, $paramArr);
		ksort($signMergeArr);
		$signMergeArr = array_merge($signMergeArr, array('secret'=>$secret));
		$re = new PublicFunV23();
		//转换为url字符
		$signArrToString = $re->arrayToString($signMergeArr);
		//$signArrToString = http_build_query($signMergeArr, '', '&');
		//签名字符串
		$md5Url = $preStr . $preFun . "?{$signArrToString}";
		$sign=md5($md5Url);
		//传递参数
		unset($signMergeArr['secret']);
		$post = array('sign'=>$sign);
		$post = array_merge($post, $signMergeArr);
		$server_url = $url . $preStr . $preFun;
// 		dump($post);
		//远程curl连接
		return $re->contentMethod($server_url, $post);
	}
	
	/**
	 * 通过订单号获取合并后的支付方式
	 * @param unknown $sn 饭票商城订单号
	 * @param unknown $customerId 用户ID
	 * @return boolean|Ambigous <boolean, unknown, unknown>
	 */
	public function getPayListBySn($sn,$customerId,$isOnlyInfo = false){
		if (empty($customerId)){
			Yii::log("customerId为空",CLogger::LEVEL_ERROR,'colourlife.core.PayService.getPayListBySn');
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '查询支付方式,用户ID为空', '查询支付方式,用户ID为空');
		}
		/* //先从缓存里获取
		$redisKey = md5('sn_payment_list:'.$sn.$customerId);
		$data = Yii::app()->rediscache->get($redisKey);;
		if (!empty($data['info']) && !empty($data['list'])){
			if ($isOnlyInfo){
				return $data['info'];
			}
			return $data;
		} */
		Yii::log("调用金融平台的支付列表开始",CLogger::LEVEL_ERROR,'colourlife.core.PayService.getPayListBySn');
		$mallPayList = $this->getMallPayList($sn, $customerId);
		if (empty($mallPayList) || empty($mallPayList['list'])){
			Yii::log("mallPayList为空",CLogger::LEVEL_ERROR,'colourlife.core.PayService.getPayListBySn');
			return $this->sendErrorMessage(false, ErrorCode::LOGIC_ERROR, '查询支付方式,饭票商城的支付方式为空', '查询支付方式,饭票商城的支付方式为空');
		}
		//只要商户信息
		if ($isOnlyInfo){
			return array(
						'id' => $mallPayList['shopinfo']['shopid'],
						'name' => $mallPayList['shopinfo']['name']
					);
		}
		//获取用户的支付方式
		$payList = $this->mergePayList($mallPayList['list'],$customerId);
		$data = array(
				'info' =>array(
					'id' => $mallPayList['shopinfo']['shopid'],
					'name' => $mallPayList['shopinfo']['name']
				) ,
				'list' => $payList
		);
		//Yii::app()->rediscache->set($redisKey, $data, 180); //缓存3分钟
		
		return $data;
	}
	
	/**
	 * 获取饭票商城的支付方式
	 * @param unknown $sn 饭票商城订单号
	 * @param unknown $customerId 用户ID
	 * @return boolean|unknown
	 */
	public function getMallPayList($sn,$customerId){
		if (empty($sn)){
			Yii::log('订单号参数为空',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getMallPayList');
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '饭票商城的订单号不能为空', '饭票商城的订单号不能为空');
		}
		Yii::log('调用微商圈接口开始',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getMallPayList');
		$param = array(
				'csn' => $sn
		);
		$preFun = 'shop/check';
		$re = new ConnectWetown();
		$result = $re->getRemoteData($preFun, $param, $this->pay_list_url,false, $customerId);
		Yii::log('调用微商圈接口返回结果：'.$result,CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getMallPayList');
		if (!empty($result)){
			$result = json_decode($result, true);
			if ($result['result'] == 0){
				Yii::log('调用微商圈接口返回结果result=0：'.json_encode($result),CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getMallPayList');
				return $result;
			}else {
				Yii::log('调用微商圈接口返回结果result!=0：'.$result['reason'],CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getMallPayList');
				return $this->sendErrorMessage(false, ErrorCode::API_RESPONSE_FAIL, $result['reason'], $result['reason']);
			}
		}else {
			Yii::log('调用接口异常返回的错误信息：'.$result,CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getMallPayList');
			return $this->sendErrorMessage(false, ErrorCode::API_REQUEST_FAIL, '调用远程请求失败', '调用远程请求失败');
		}
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
		Yii::log('调用预支付接口',CLogger::LEVEL_INFO, 'colourlife.core.PayService.prepay');
		if(!$money||empty($money)){
			Yii::log('参数为空',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.prepay');
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '预支付,金额为空', '预支付,金额为空');
		}
		try {
			Yii::log('调用金融平台消费接口开始：',CLogger::LEVEL_INFO, 'colourlife.core.PayService.prepay');
			$result = FinanceMicroService::getInstance()->prepay(
					$money,  //交易金额
					$content,  //备注
					intval($orgtype), //支付类型
					$orgaccountno, //支付账号
					intval($desttype), //收款类型
					$destaccountno, //收款账号
					$detail,  //备注
					$starttime, //开始时间
					$stoptime, //结束时间
					$callback, //回调地址
					$orderno, //交易号
					$remoteip //ip地址
			);
			Yii::log('调用金融平台消费接口结束：',CLogger::LEVEL_INFO, 'colourlife.core.PayService.prepay');
			//$result = ICEService::getInstance()->dispatch($this->prepay_url,$getParam,$postParam,'POST');
			if (isset($result['payinfo'])){
				return $result;
			}else {
				Yii::log('调用金融平台消费接口失败',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.prepay');
				return $this->sendErrorMessage(false, ErrorCode::API_RESPONSE_FAIL, '预支付,payinfo不存在', '预支付,payinfo不存在');
			}
		}catch (Exception $e){
			$message = $e->getMessage();
			$code = $e->getCode();
			//记录异常
			Yii::log('调用接口异常：code='.$code.',返回错误信息：'.$message,CLogger::LEVEL_ERROR, 'colourlife.core.PayService.prepay');
			return $this->sendErrorMessage(false, ErrorCode::API_REQUEST_FAIL, $message, $message);
		}
	}
	
	/**
	 * 获取单个支付方式信息
	 * @param unknown $businessId
	 * @param unknown $customerId
	 * @param unknown $pano
	 */
	public function getPaymentInfo($businessId = 0,$customerId,$pano,$sn = ''){
		if (empty($customerId)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '获取单个支付方式信息,用户ID不能为空', '获取单个支付方式信息,用户ID不能为空');
		}
		if (empty($pano)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '获取单个支付方式信息,支付主账号编号不能为空', '获取单个支付方式信息,支付主账号编号不能为空');
		}
		if (empty($businessId) && empty($sn)){
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '获取单个支付方式信息,商户ID和第三方订单号不能同时为空', '获取单个支付方式信息,商户ID和第三方订单号不能同时为空');
		}
		if (!empty($businessId)){
			$payList = $this->getPayListByBusinessId($businessId,$customerId);
		}else {
			$payList = $this->getPayListBySn($sn, $customerId);
		}
		if (!isset($payList['list'][$pano])){
			return $this->sendErrorMessage(false, ErrorCode::LOGIC_ERROR, '获取单个支付方式信息,支付方式信息不存在', '获取单个支付方式信息,支付方式信息不存在');
		}
		return $payList['list'][$pano];
	}

	/**
	 * 支付回调地址
	 * @param $param
	 * @return string
	 */
	public function makeCallbackUrl($param)
	{
		$sign = new Sign($this->pay_secret);

		$param['sign'] = $sign->makeSign($param);

		return F::getOrderUrl('/api/payNotify/kkpay').'?'.$sign->createLinkString($param);
	}

	/**
	 * 生成饭票支付流水记录
	 * @param $customerId
	 * @param $sn
	 * @param $amount
	 * @return bool
	 */
	public function makeRedPacket($customerId, $sn, $amount, $isLocalFanPiao = false  , $atid='' , $note='')
	{
		if ($isLocalFanPiao){
			$table = "local_red_packet";
		}else {
			$table = "red_packet";
		}
		$connection = Yii::app()->db;
		$redPacketSql = 'SELECT * FROM `'.$table.'` WHERE `customer_id`=:customer_id AND `sn`=:sn';
		$command = $connection->createCommand($redPacketSql);
		$command->bindParam(':customer_id', $customerId, PDO::PARAM_INT);
		$command->bindParam(':sn', $sn, PDO::PARAM_STR);
		$orderRedPacket = $command->queryRow();

		if ($orderRedPacket)
			return true;

		if($isLocalFanPiao){
			$newData = array(
				'sn' => $sn,
				'type' => Item::RED_PACKET_TYPE_CONSUME,
				'customer_id' => $customerId,
				'to_type' => Item::RED_PACKET_TO_TYPE_ORDER_PAY,
				'sum' => $amount,
				'create_time' => time(),
				'note' => empty($note)?"订单【{$sn}】全国饭票汇兑消费饭票【{$amount}】元":$note,
				'state' => 0,
				'atid' => $atid
			);
		}else{
			$newData = array(
				'sn' => $sn,
				'type' => Item::RED_PACKET_TYPE_CONSUME,
				'customer_id' => $customerId,
				'to_type' => Item::RED_PACKET_TO_TYPE_ORDER_PAY,
				'sum' => $amount,
				'create_time' => time(),
				'note' =>  "订单【{$sn}】消费饭票【{$amount}】元",
				'state' => 0,
			);
		}


		Yii::log('地方饭票写入流水开始：结果：'.json_encode($newData),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.LocalPacket');
		$command = $connection->createCommand();
		try {
			$res = $command->insert($table, $newData);
			Yii::log('地方饭票写入流水开始结束：结果：'.json_encode($res),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.LocalPacket');
			return true;
		}
		catch (Exception $e) {
			Yii::log('地方饭票写入流水失败结束：结果：'.json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.redpacket.LocalPacket.default');
			return $this->sendErrorMessage(false, ErrorCode::DB_INSERT_FAIL, '写入流水失败', $e->getMessage());
		}
	}

	/**
	 * 返回饭票支付列表
	 * @param $customer_id
	 * @param int $is_common
	 * @return array|null
	 * @throws CException
	 * @throws CHttpException
	 */
	public function getMealType($customer_id , $is_common=0, $sn){
		if(!$customer_id){ return null ;}

		if($is_common){
			$sql = "SELECT a.pano , a.cano , a.cno , b.`name` , a.atid FROM finance_customer_relation AS a LEFT OUTER JOIN finance_pay_type AS b ON a.atid=b.atid WHERE customer_id={$customer_id} AND b.`status`=1  AND a.atid=1 GROUP BY b.atid;";
		}else{
			$sql = "SELECT a.pano , a.cano , a.cno , b.`name` , a.atid FROM finance_customer_relation AS a LEFT OUTER JOIN finance_pay_type AS b ON a.atid=b.atid WHERE customer_id={$customer_id} AND b.`status`=1 AND a.atid!=1 GROUP BY b.atid;";
		}
		$query = Yii::app()->db->createCommand($sql);
		$result = $query->queryAll();
		$list = [];
		if (!empty($result)) {
			foreach($result as $key=>$value){
				if($is_common) {
					$rate = 100;
				} else{
					Yii::import('common.services.LocalRedPacketService');
					$localRedPacketService = new LocalRedPacketService();
					$rate = $localRedPacketService->getLocalRate($value['pano']);
				}
				$list[] = [
					'payment_id' => $value['atid'],
					'logo' => $is_common ? F::getStaticsUrl('/common/v30/ptfp@2x.png'):F::getStaticsUrl('/common/v30/zxfp@2x.png'),
					'name' => $value['name'],
					'discount' => $rate,
					'user_amount' => $this->getCustomerBalanceInfo($value['pano'],$value['cano']),
					'is_valid' => $this->isValid($sn , $value['pano']),
					'valid_time' => ''
				];
			}
		}else{
			return null;
		}
		//dump($list);
		return $list;
	}

	/**
	 * 批量兑换接口
	 * @param $orderno
	 * @param $orgaccounts
	 * @param $desttype
	 * @param $destaccountno
	 * @param string $content
	 * @param string $detail
	 * @param int $starttime
	 * @param int $stoptime
	 * @param string $callback
	 * @return mixed
	 */
	public function batchExchange($orderno  , $orgaccounts, $desttype , $destaccountno ,$content='地方饭票转全国饭票' , $detail = '' , $starttime = 0 , $stoptime = 0 , $callback = ''){
		if(!$orderno || $orgaccounts || $desttype || $destaccountno){
			Yii::log('参数为空',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.batchExchange');
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '发起交易,金额为空', '发起交易,金额为空');
		}

		try {
			Yii::log('调用接口开始',CLogger::LEVEL_INFO, 'colourlife.core.PayService.batchExchange');
			$result = FinanceMicroService::getInstance()->batchExchange(
				$orderno,           				//业务系统交易编号
				$orgaccounts,                     //json数组[{"atid":1,"ano":"xxxxx","money":1.00}]
				$desttype,    					//收款账号类型，atid
				$destaccountno,     			//收款帐号编号
				$content,   					//交易说明（显示给用户的内容）
				$detail,    				//交易明细
				$starttime,                   //交易生效时间
				$stoptime,                     //交易失效时间
				$callback             		//业务系统回调地址
			);
			Yii::log('调用接口结束',CLogger::LEVEL_INFO, 'colourlife.core.PayService.batchExchange');
			if (isset($result['payinfo'])){
				return $result;
			}else {
				Yii::log('调用接口失败',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.batchExchange');
				return $this->sendErrorMessage(false, ErrorCode::API_RESPONSE_FAIL, '发起交易,payinfo不存在', '发起交易,payinfo不存在');
			}
		}catch (Exception $e){
			$message = $e->getMessage();
			$code = $e->getCode();
			//记录异常
			Yii::log('调用接口异常：code='.$code.',返回错误信息：'.$message,CLogger::LEVEL_ERROR, 'colourlife.core.PayService.batchExchange');
			return $this->sendErrorMessage(false, ErrorCode::API_REQUEST_FAIL, $code.':'.$message, $code.':'.$message);
		}
	}

	/*
	 *聚合支付接口
	 * $orderno  	业务系统交易编号
	 * $content  	交易说明（显示给用户的内容）
	 * $orgaccounts  	支付帐号，json数组，如[{"atid":1,"ano":"xxxxx","money":1.00}]
	 * $desttype 	收款账号类型，atid
	 * $atid		账号类型	可选
	 * $ano			账号编号	可选
	 * $starttime	查询起始时间，时间戳	可选
	 * $stoptime	查询结束时间，时间戳	可选
	 *$transtype	交易类型	可选
	 * $ispay		是否只匹配支付参数，0全部，1支付，2收款	可选
	 * $skip		忽略记录数	默认0
	 * $limit		最大记录数	默认20
	 */
	public function aggregatePay($orderno , $content='' , $orgaccounts , $desttype ,$atid='' ,$ano='' ,$starttime='' , $stoptime='' , $transtype='', $ispay='' , $skip=0 ,$limit=20)
	{
		if(!$orderno || $orgaccounts || $desttype){
			Yii::log('聚合支付参数为空',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.agreeGatePay');
			return $this->sendErrorMessage(false, ErrorCode::PARAM_VALUE_EMPTY, '发起交易,支付帐号为空', '发起交易,支付帐号为空');
		}

		try {
			Yii::log('调用聚合支付接口开始',CLogger::LEVEL_INFO, 'colourlife.core.PayService.agreeGatePay');
			$result = FinanceMicroService::getInstance()->aggregatePay(
				$orderno,           				//业务系统交易编号
				$orgaccounts,                     //json数组[{"atid":1,"ano":"xxxxx","money":1.00}]
				$desttype,    					//收款账号类型，atid
				$atid,     					//收款帐号编号
				$content,   					//交易说明（显示给用户的内容）
				$ano,    				//交易明细
				$starttime,                   //交易生效时间
				$stoptime,                     //交易失效时间
				$transtype,         		//业务系统回调地址
				$ispay,
				$skip,
				$limit
			);
			Yii::log('调用聚合支付接口结束',CLogger::LEVEL_INFO, 'colourlife.core.PayService.agreeGatePay');
			if (isset($result['list'])){
				return $result;
			}else {
				Yii::log('调用聚合支付接口失败',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.agreeGatePay');
				return $this->sendErrorMessage(false, ErrorCode::API_RESPONSE_FAIL, '发起交易,payinfo不存在', '发起交易,payinfo不存在');
			}
		}catch (Exception $e){
			$message = $e->getMessage();
			$code = $e->getCode();
			//记录异常
			Yii::log('调用聚合支付接口异常：code='.$code.',返回错误信息：'.$message,CLogger::LEVEL_ERROR, 'colourlife.core.PayService.agreeGatePay');
			return $this->sendErrorMessage(false, ErrorCode::API_REQUEST_FAIL, $code.':'.$message, $code.':'.$message);
		}
	}

		/**
	 * 根据订单号判断是否支持该地方饭票支付
	 * @param $sn
	 * @return int
	 */
	public function isValid($sn ,$pano){
		$model = SN::findContentBySN($sn);
		if (empty($model)){
			return 0;
		}
		$sn_code = substr($sn , 0 ,4);
		$sn_code_3 = substr($sn, 0, 3);
		$in_red_pay = array('9013' , '9035');   //第三方e费通订单,e停车订单
		$not_red_pay = array('105' , '801');   //红包充值、一元购

		if(in_array($sn_code_3 ,$not_red_pay)   ||  (substr($sn, 0, 1) == 9 && empty($model->isUseRed)&& !in_array($sn_code , $in_red_pay))){
			return 0;
		}

		//自动售货机不支持地方饭票
		if($sn_code == 9126){
			return 0;
		}
		if (in_array($sn_code , $in_red_pay)) {
			$local_red = json_decode($model->local_pay);
			if(!empty($local_red)){
				if(in_array($pano , $local_red)){
					//return 0;
					return 1;  //临时屏蔽
				}else{
					return 0;	}
			}else{
				return 0;
			}
		}
		//return 0;
		return 1;  //临时屏蔽
	}
}