<?php

/**
 * Created by PhpStorm.
 * User: austin
 * Date: 6/22/16
 * Time: 11:38 AM
 */
class MicroAuthService
{

	protected static $instance;

	private $privilegeServer = '';
	private $appKey = '88a2b3c4d5e6f7a8b9c2'; //彩之云key
	private $appSecret = '882b3c4d5e6f7a8b9c0d1a2b3c4d5e6f'; //彩之云secret

	private $AUTH_REDIS_KEY="micro_auth_token:colourlife";

	public function __construct()
	{
		if (defined('YII_DEBUG') && YII_DEBUG == true) {
            $this->privilegeServer = 'http://neotest.kakatool.cn:8098/';
		} else {
            $this->privilegeServer = 'http://rules.ice.colourlife.com/';
			//$this->privilegeServer = 'http://114.119.7.99:8098/';
		}
	}

	public static function getInstance()
	{
		if (!isset(self::$instance))
			self::$instance = new self;
		return self::$instance;
	}


	protected function getAccessTokenFromPrivilegeMicroService()
	{
		//echo $this->getPrivelegeUrl('app/auth');exit;
        Yii::log("验证信息:".$this->getPrivelegeUrl('app/auth'));
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

		return array(
			$response['content']['access_token'],
			$response['content']['expire']
		);
	}

	/**
	 * 从数据库读取 access_token
	 * @return bool
	 */
	protected function getAccessTokenFromRedis()
	{
		$data = Yii::app()->rediscache->executeCommand('GET',array($this->AUTH_REDIS_KEY));
		if ($data) {
			$data = json_decode($data,TRUE);
			if ($data
				&& isset($data['access_token'])
				&& isset($data['expires_in'])
				&& $data['expires_in'] > time()
			) {
				return $data['access_token'];
			}
		}

		return FALSE;
	}

	/**
	 * 保存 access_token 至数据库
	 * @param string $accessToken
	 * @param int $expireIn
	 * @return bool
	 */
	protected function saveAccessTokenToRedis($accessToken = '', $expireIn = 0)
	{

		if (!$accessToken || !$expireIn) {
			return false;
		}

		$data = array(
			'access_token' => $accessToken,
			'expires_in' => $expireIn
		);

		//保存到redis
		Yii::app()->rediscache->executeCommand('SET',array($this->AUTH_REDIS_KEY,json_encode($data)));

		return TRUE;
	}

	/**
	 * 从数据库读取 access_token
	 * @return bool
	 */
	protected function clearAccessTokenFromRedis()
	{
		Yii::app()->rediscache->executeCommand('DEL',array($this->AUTH_REDIS_KEY));

		return TRUE;
	}

	/**
	 * 获取 access_token
	 * @return bool|string
	 * @throws CHttpException
	 */
	public function getAccessToken()
	{
		$token = $this->getAccessTokenFromRedis();

		if (!$token) {
			list($token, $expireIn) = $this->getAccessTokenFromPrivilegeMicroService();

			$this->saveAccessTokenToRedis($token, $expireIn);
		}

		return $token;

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
