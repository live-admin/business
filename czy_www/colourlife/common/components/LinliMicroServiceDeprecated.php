<?php


/**
 * 邻里微服务
 * 移动到 common/components/micro/
 * @author    Ender
 */
class LinliMicroServiceDeprecated
{


	private $apiServer = '';
	private $privilegeServer = '';
	private $appKey = '88a2b3c4d5e6f7a8b9c2';
	private $appSecret = '882b3c4d5e6f7a8b9c0d1a2b3c4d5e6f';
	private $accessToken = '';

	public function __construct()
	{
		if (defined('YII_DEBUG') && YII_DEBUG == true) {
			$this->apiServer = 'http://114.119.6.163:8090/';
			$this->apiServer = 'http://linli.ms.colourlife.jiaxin.li/';
			$this->privilegeServer = 'http://neotest.kakatool.cn:8098/';
		} else {
			$this->apiServer = 'http://114.119.7.99:1790/';
			$this->privilegeServer = 'http://114.119.7.99:8098/';
		}
	}


	protected function getAccessTokenFromPrivilegeMicroService()
	{
		//echo $this->getPrivelegeUrl('app/auth');exit;
		$response = json_decode(
			HttpClient::getHttpResponsePOST(
				$this->getPrivelegeUrl('app/auth'),
				array()
			),
			true
		);

		if (!$response || !isset($response['code'])) {
			throw new CHttpException(501, '获取邻里权限失败');
		}

		if ($response['code'] != 0) {
			throw new CHttpException(
				501,
				sprintf(
					'获取邻里权限失败: %s[%s]',
					$response['message'],
					$response['code']
				)
			);
		}

		if (!isset($response['content']['access_token'])
			|| !isset($response['content']['expire'])
		) {
			throw new CHttpException(502, '获取邻里权限失败');
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
	protected function getAccessTokenFromDB()
	{
		$config = Config::model()->find('`key`=:key', array(':key' => 'LinliMicroService'));
		if ($config) {
			$data = $config->getVal();
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
	protected function saveAccessTokenToDB($accessToken = '', $expireIn = 0)
	{

		if (!$accessToken || !$expireIn) {
			return false;
		}

		$data = array(
			'access_token' => $accessToken,
			'expires_in' => $expireIn
		);

		$config = Config::model()->find('`key`=:key', array(':key' => 'LinliMicroService'));
		if ($config) {
			$config->update_time = time();
			$config->setVal($data);
		} else {
			$config = new Config();
			$config->key = 'LinliMicroService';
			$config->name = '邻里微服务 Token';
			$config->type = 'LinliMicroService';
			$config->update_time = time();
			$config->setVal($data);
		}

		return $config->save();
	}

	/**
	 * 从数据库读取 access_token
	 * @return bool
	 */
	protected function clearAccessTokenFromDB()
	{
		$config = Config::model()->find('`key`=:key', array(':key' => 'LinliMicroService'));
		if ($config) {
			return $config->delete();
		}

		return FALSE;
	}

	/**
	 * 获取 access_token
	 * @return bool|string
	 * @throws CHttpException
	 */
	protected function getAccessToken()
	{
		if (!$this->accessToken) {
			$token = $this->getAccessTokenFromDB();

			if (!$token) {
				list($token, $expireIn) = $this->getAccessTokenFromPrivilegeMicroService();

				$this->saveAccessTokenToDB($token, $expireIn);
			}

			return $this->accessToken = $token;
		}
		return $this->accessToken;
	}

	/**
	 * 获取邻里微服务 url
	 * @param string $interface
	 * @return string
	 */
	protected function getApiUrl($interface = '')
	{
		return sprintf(
			'%s/%s?access_token=%s',
			trim($this->apiServer, ' /'),
			trim($interface, ' /'),
			$this->getAccessToken()
		);
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

	protected function handleSpecialCharInPostField($postFields = array())
	{
		if (!$postFields) {
			return $postFields;
		}

		$parsedFields = array();
		foreach ($postFields as $key => $postField) {
			// @ 开头的字段会被认为是文件上传
			// 处理方式，@开头的，先添加空格，然后服务器端去空格
			$pos = strpos($postField, '@');
			if ($pos !== false && $pos == 0) {
				$postField = ' ' . $postField;
			}

			$parsedFields[$key] = $postField;
		}

		return $parsedFields;
	}

	/**
	 * 邻里接口转发
	 * @param string $interface
	 * @param array $postData
	 * @return mixed
	 * @throws CHttpException
	 */
	public function dispatchService($interface = '', $postData = array())
	{
		$response = json_decode(
			HttpClient::getHttpResponsePOST(
				$this->getApiUrl($interface),
				$this->handleSpecialCharInPostField($postData)
			),
			true
		);

		if (!$response || !isset($response['code'])) {
			throw new CHttpException(500, '邻里微服务请求失败');
		}

		if ($response['code'] > 0) {
			if ($response['code'] == 3003) {
				$this->clearAccessTokenFromDB();
			}

			throw new CHttpException(
				500,
				sprintf('%s[%s]。请重试。', $response['message'], $response['code'])
			);
		}

		return $response['content'];
	}
}