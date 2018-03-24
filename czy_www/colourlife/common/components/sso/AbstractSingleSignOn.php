<?php

/**
 * 单点登录代理
 * @author dw
 *
 */
abstract class AbstractSingleSignOn
{
	/**
	 * 响应是密文还是明文。目前只支持明文
	 * 密文为true， 明文为false。
	 * @var bool
	 */
	protected $isEncryption = false;
	
	protected $openId;
	protected $accessToken;
	protected $appId;
	protected $token;

	public function __construct($openId, $accessToken, $appId, $token)
	{
		$this->openId = $openId;
		$this->accessToken = $accessToken;
		$this->appId = $appId;
		$this->token = $token;
	}
	
	/**
	 * 
	 * @return array <br> 
	 * <pre>验证成功返回 
	 * array(
	 *  'result' => 'success',  //验证是否成功
	 *  'message' => 'success', //消息
	 *  'data' =>  array (   // OA 用户信息
	 *  	'username' => 'test',   //OA用户名，全局唯一
	 *  	'realname' => '', 
	 *  	'jobId' => '1' ,
	 *  	'jobName' => '测试' ,
	 *  	'username' => 'test' ,
	 *  	'familyId' => '1' ,
	 *  	'familyName' => '测试' ,
	 *  	'mobile' => '18900000000' ,
	 *  	'email' => '' ,
	 *  	'disable' => '1' ,
	 *  	'createtime' => '' ,
	 *  	)
	 *  )
	 *  </pre>
	 *  <pre>
	 *  验证失败返回
	 *  array(
	 *  	'result' => "fail",
	 *  	'message' => "access_token错误"
	 *  )
	 *  </pre>
	 */
	public function RequestAuthenticate()
	{
		$timestamp = time();
		$sign = $this->sign($timestamp);
		
		
		$responeContent = $this->RequestAuthenticateImp(
				$this->openId, 
				$this->accessToken,
				$this->appId,
				$timestamp, 
				$sign);
		
		$result = json_decode($responeContent, true);
		
		return $result;
	}
	
	/**
	 * 用于请求授权服务器。子类实现该方法。
	 * @param string $openId  是根据用户账号和第三方的app_id进行加密的密串，第三方无需关心加解密细节，open_id不能作为用户标识来使用，切记。
	 * @param string $accessToken 是一次性通讯凭据，在60秒内有效，且只能使用一次
	 * @param string $appId 由单点登录平台提供的应用ID
	 * @param string $timestamp 时间戳
	 * @param string $sign 签名
	 * @return string JSON 格式则字符串
	 */
	protected abstract function RequestAuthenticateImp($openId, $accessToken, $appId, $timestamp, $sign);
	
	private function sign($timestamp)
	{
		$signStr = $this->appId . $timestamp . $this->token;
		$signStr .= $this->isEncryption ? "true" : "false" ; 
		return md5($signStr);
	}
}