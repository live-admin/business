<?php

class SingleSignOn extends AbstractSingleSignOn
{
 	private static $ssoUrl = "http://iceapi.colourlife.com:8081/v1/auth?";  //正式地址
//    private static $ssoUrl = "http://54.223.171.230:8081/v1/auth?";   //测试地址
	public function RequestAuthenticateImp($openId, $accessToken, $appId, $timestamp, $sign)
	{
		$url = self::buildRqeustUrl($appId, $timestamp, $sign);
		$param = array(
		        'openID' => $openId,
		        'accessToken' => $accessToken,
		);
		$content = HttpClient::getHttpResponsePOST($url, $param);
		//返回的不是JSON格式的数据
		if(json_decode($content) === null)
		{
			return json_encode(array(				
					'result' => "9999",
					'message' => "访问授权服务器错误",
			));
		}
		return $content;
	}
	
	private static function buildRqeustUrl($app_id, $timestamp, $sign)
	{
		$url = self::$ssoUrl;
		$url .= "&appID=". $app_id;
		$url .= "&ts=". $timestamp;
		$url .= "&sign=". $sign;
		return $url;
	}
}