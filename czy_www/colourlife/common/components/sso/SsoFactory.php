<?php

class SsoFactory
{
	/**
	 * 创建单点登录的实例对象
	 * @param string $openId   是根据用户账号和第三方的app_id进行加密的密串，第三方无需关心加解密细节，open_id不能作为用户标识来使用，切记
	 * @param string $accessToken 是一次性通讯凭据，在60秒内有效，且只能使用一次。
	 * @param string $appId  由单点登录平台提供的应用ID
	 * @param string $token  由单点登录平台提供的签名秘钥
	 * @return AbstractSingleSignOn 
	 */
	public static function createInstance($openId, $accessToken, $appId, $token)
	{
		$ssoClass = Yii::app()->params['SsoClass'];		
		return new $ssoClass($openId, $accessToken, $appId, $token);
	}
}