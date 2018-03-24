<?php

/* 
 * @version 获取应用验证码（accessToken），具体参考彩生活鉴权系统
 */
class GetTokenService{
	private $privilegeServer = '';
	private $appKey = '';
	private $appSecret = '';

	public function __construct()
	{
		if (defined('YII_DEBUG') && YII_DEBUG == true) {
			$this->appKey = '88a2b3c4d5e6f7a8b9c2';
			$this->appSecret = '882b3c4d5e6f7a8b9c0d1a2b3c4d5e6f';
			$this->privilegeServer = 'http://neotest.kakatool.cn:8098/';

		}else {
			$this->appKey = '88a2b3c4d5e6f7a8b9c2';
			$this->appSecret = '882b3c4d5e6f7a8b9c0d1a2b3c4d5e6f';
			$this->privilegeServer = 'http://rules.ice.colourlife.com/';
		}
	}
    /*
     * @version 获取应用验证码（accessToken），具体参考彩生活鉴权系统
     */
    public function getAccessTokenFromPrivilegeMicroService()
	{
		$response = json_decode(
			HttpClient::getHttpResponsePOST(
				$this->getPrivelegeUrl('app/auth'),
				array()
			),
			true
		);

		if (!$response || !isset($response['code'])) {
			throw new CHttpException(501, '获取权限失败');
		}

		if ($response['code'] != 0) {
			throw new CHttpException(
				501,
				sprintf(
					'获取权限失败: %s[%s]',
					$response['message'],
					$response['code']
				)
			);
		}

		if (!isset($response['content']['access_token'])
			|| !isset($response['content']['expire'])
		) {
			throw new CHttpException(502, '获取权限失败');
		}
		$cacheKey = 'cmobile:cache:code:finance:getAccessToken';
		Yii::app()->rediscache->set($cacheKey, $response['content']['access_token'], 60);
		return $response['content']['access_token'];
	}
    /**
	 * 获取权限微服务 url
	 * @param string $interface
	 * @return string
	 */
	protected function getPrivelegeUrl($interface = '')
	{
		$ts = time();
		return sprintf(
			'%s/%s?%s',
			trim($this->privilegeServer, ' /'),
			trim($interface, ' /'),
			http_build_query(array(
				'appkey' => $this->appKey,
				'signature' => md5($this->appKey . $ts . $this->appSecret),
				'timestamp' => $ts,
			))
		);
	}
}
