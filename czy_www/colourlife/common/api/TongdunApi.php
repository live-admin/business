<?php
/**
 * 同盾风险验证接口
 * User: Mandy
 * Date: 2015/10/31
 * Time: 11:55
 */
class TongdunApi{
	//private $api_url='https://apitest.fraudmetrix.cn/riskService';  //测试API
	private  $api_url = "https://api.fraudmetrix.cn/riskService";  //正式API
	private $partner_code='colourlife';
	protected static $instance;
	
	public static function getInstance()
	{
		if (!isset(self::$instance))
			self::$instance = new self;
		return self::$instance;
	}
	
	/**
	 * 风险验证接口
	 * @param unknown $username 用户名（手机号）
	 * @param unknown $device_info  设备信息或token_id
	 * @param unknown $reg_type 类型
	 * @param unknown $event_id  事件类型
	 * @return string|unknown 
	 */
	public function riskInfoRecord($username,$device_info,$reg_type,$event_id){
		if (empty($username)||empty($device_info)||empty($event_id)){
			return '';
		}
		//判断来自哪里，1为安卓，2为ios,其他为网页
		switch ($reg_type){
			case 2:
				//$secret_key='0f1a9f0ed9ed4affbbc1fb4576f1032d';  //测试key
				$secret_key='46554a5207cb4807b823a668e8bc1fbd';  //正式key
				$m_type=$event_id.'_ios';
				break;
			case 1:
				//$secret_key='b68cf94acdd94f94bab5267e96585fd8';  //测试key 
				$secret_key='382dfb65f0a8490cbb652e5e3e2b02ba';  //正式key
				$m_type=$event_id.'_and';
				break;
			default:
				//$secret_key='f2b1d37eff804ee693a03e297affb8ea';  //测试key
				$secret_key='f2b1d37eff804ee693a03e297affb8ea';  //正式key
				$m_type=$event_id.'_web';
				break;
		}
		//传递参数
		$param=array(
				"partner_code" => $this->partner_code,
				"secret_key" => $secret_key,
				"event_id" => $m_type,
				"account_login" => $username,
				"ip_address" => $_SERVER['REMOTE_ADDR']
		);
		if ($reg_type==1||$reg_type==2){
			$param['black_box']=$device_info;
		}else {
			$param['token_id']=$device_info;
		}
		if ($event_id=='register'){
			$param['account_mobile']=$username;
		}
		//$risk_result =  Yii::app()->curl->post($this->api_url, $param);
		$risk_result = $this->invoke_fraud_api($param);
		if ($risk_result['success']){
			return array(
				'username'=>$username,
				'device_info'=>$device_info,
				'event_id'=>$m_type,
				'risk_result'=>json_encode($risk_result)
			);
		}else {
			return array();
		}
		
	}
	
	private function invoke_fraud_api(array $params, $timeout = 10000, $connection_timeout = 10000) {
		$options = array(
				CURLOPT_POST => 1,            // 请求方式为POST
				CURLOPT_URL => $this->api_url,      // 请求URL
				CURLOPT_RETURNTRANSFER => 1,  // 获取请求结果
				// -----------请确保启用以下两行配置------------
				CURLOPT_SSL_VERIFYPEER => 1,  // 验证证书
				CURLOPT_SSL_VERIFYHOST => 2,  // 验证主机名
				// -----------否则会存在被窃听的风险------------
				CURLOPT_POSTFIELDS => http_build_query($params) // 注入接口参数
		);
		if (defined(CURLOPT_TIMEOUT_MS)) {
			$options[CURLOPT_NOSIGNAL] = 1;
			$options[CURLOPT_TIMEOUT_MS] = $timeout;
		} else {
			$options[CURLOPT_TIMEOUT] = ceil($timeout / 1000);
		}
		if (defined(CURLOPT_CONNECTTIMEOUT_MS)) {
			$options[CURLOPT_CONNECTTIMEOUT_MS] = $connection_timeout;
		} else {
			$options[CURLOPT_CONNECTTIMEOUT] = ceil($connection_timeout / 1000);
		}
		$ch = curl_init();
		curl_setopt_array($ch, $options);
		if(!($response = curl_exec($ch))) {
			// 错误处理，按照同盾接口格式fake调用结果
			return array(
					"success" => false,
					"reason_code" => "000:调用API时发生错误[".curl_error($ch)."]"
			);
		}
		curl_close($ch);
		return json_decode($response, true);
	}
	
}