<?php

/**
 * 微服务接入服务
 */
class MicroServiceDispatchService
{

	protected static $instance;

	protected $baseUrl = '';
	protected $queryData;
	protected $queryUrl;

	protected $runOnConsole = false;

	protected $curlTimeOut = 16;
	protected $curlConnectTimeOut = 2;


	public function __construct()
	{
		$this->runOnConsole = php_sapi_name() == 'cli';
		if (defined('YII_DEBUG') && YII_DEBUG == true) {
			$this->baseUrl = 'http://neotest.kakatool.cn:8097/';
		} else {
			$this->baseUrl = 'https://finance.colourlife.com:3681/';
		}
	}

	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function setBaseUrl($url = '')
	{
		$this->baseUrl = trim($url, ' /');
	}

	/**
	 * 服务 url
	 * @param string $interface
	 * @param array $queryData
	 * @return string
	 */
	protected function getQueryUrl($interface = '', $queryData = array())
	{
		$url = sprintf(
			$queryData ? '%s/%s?%s' : '%s/%s',
			trim($this->baseUrl, ' /'),
			trim($interface, ' /'),
			$queryData ? http_build_query($queryData) : ''
		);

		if ($this->runOnConsole) {
			echo '[' . date("Y-m-d H:i:s") . ']:' . '微服务请求地址:' . $url . PHP_EOL;
		}

		return $url;
	}


	/**
	 * 处理传递参数
	 * @param array $data
	 * @return array
	 * @throws CHttpException
	 */
	protected function getQueryData($data = array())
	{
		if (!$data || !isset($data['access_token'])) {
			$token = MicroAuthService::getInstance()->getAccessToken();
			if (!$token) {
				throw new CHttpException(1001, '无法请求微服务：无法获取token');
			}

			$data['access_token'] = $token;
		}

		return $data;
	}

	protected function getPostData($data = array())
	{
		$parsedData = array();

		if ($data && is_array($data)) {
			foreach ($data as $key => $item) {
				// @ 开头的字段会被认为是文件上传
				// 处理方式，@开头的，先添加空格，然后服务器端去空格
				$pos = strpos($item, '@');
				if ($pos !== false && $pos == 0) {
					$item = ' ' . $item;
				}

				$parsedData[$key] = $item;
			}
		}

		if ($this->runOnConsole) {
			echo '[' . date("Y-m-d H:i:s") . ']:' . '微服务请求参数:' . json_encode($parsedData) . PHP_EOL;
		}

		return $parsedData;
	}

	/**
	 * 解析请求返回数据
	 * @param string $response
	 * @return mixed|string
	 */
	protected function parseQueryResponse($response = '')
	{
		if ($this->runOnConsole) {
			echo '[' . date("Y-m-d H:i:s") . ']:' . '微服务结果:' . $response . PHP_EOL;
		}

		Yii::log(
			sprintf(
				'调用微服务接口: %s, 参数: %s, 返回信息: %s.',
				$this->queryUrl,
				json_encode($this->queryData),
				substr($response, 0, 55000)
			),
			CLogger::LEVEL_ERROR,
			'colourlife.core.api.MicroServiceDispatchService.parseQueryResponse'
		);

		return @json_decode($response, true);
	}

	protected function requestCurlPut($queryUrl = '', $queryData = array(), $header = array())
	{
		array_push($header, 'Content-Type: application/x-www-form-urlencoded');
		array_push($header, 'Accept:application/json');

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $queryUrl);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->curlTimeOut);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->curlConnectTimeOut);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($queryData));

		$errno = curl_errno($curl);
		if ($errno) {
			$error = curl_error($curl);
			throw new Exception($error ? $error : '', $errno ? $errno : 5001);
		}

		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

	protected function requestCurlPost($queryUrl = '', $queryData = array(), $header = array())
	{
		array_push($header, 'Content-Type: application/x-www-form-urlencoded');
		array_push($header, 'Accept:application/json');

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $queryUrl);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->curlTimeOut);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->curlConnectTimeOut);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($queryData));

		$errno = curl_errno($curl);
		if ($errno) {
			$error = curl_error($curl);
			throw new Exception($error ? $error : '', $errno ? $errno : 5001);
		}

		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

	protected function requestCurlGet($queryUrl = '', $header = array())
	{
		//array_push($header, 'Content-Type: application/x-www-form-urlencoded');
		array_push($header, 'Accept:application/json');

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $queryUrl);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, $this->curlTimeOut);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->curlConnectTimeOut);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

		$errno = curl_errno($curl);
		if ($errno) {
			$error = curl_error($curl);
			throw new Exception($error ? $error : '', $errno ? $errno : 5001);
		}

		$response = curl_exec($curl);
		curl_close($curl);

		return $response;
	}

	/**
	 * 请求微服务接口
	 * @param string $queryUrl
	 * @param array $queryData
	 * @param string $method
	 * @return mixed|string
	 */
	protected function request($queryUrl = '', $queryData = array(), $method = 'GET')
	{
		$this->queryUrl = $queryUrl;
		$this->queryData = $queryData;
		/*echo $this->queryUrl, PHP_EOL;
		foreach ($queryData as $key => $value){
			echo $key, ': ', $value, PHP_EOL;
		}
		exit;*/
		switch (strtoupper($method)) {
			default:
			case 'GET':
				$response = $this->requestCurlGet($queryUrl, $queryData);
				break;

			case 'POST':
				$response = $this->requestCurlPost($queryUrl, $queryData);
				break;

			case 'PUT':
				$response = $this->requestCurlPut($queryUrl, $queryData);
				break;
		}

		return $this->parseQueryResponse($response);
	}

	/**
	 * 接口转发
	 * @param string $interface
	 * @param array $getParam
	 * @param array $postParam
	 * @param string $method
	 * @return mixed
	 * @throws CHttpException
	 */
	public function dispatch($interface = '', $getParam = array(), $postParam = array(), $method = 'GET')
	{
		try {
			$response = $this->request(
			// 拼接请求url，支持post既有get参数又有post参数
				$this->getQueryUrl(
					$interface,
					// 处理 queryString 参数
					$this->getQueryData($getParam)
				),
				// 处理 post field 参数
				$this->getPostData($postParam),
				$method
			);
		} catch (Exception $e) {
			$message = $e->getMessage();
			$code = $e->getCode();
			throw new CHttpException(
				501,
				sprintf(
					'微服务请求失败：%s[%s]。请重试!',
					$message ? $message : '连接出错',
					$code ? $code : '-1'
				)
			);
		}

		if (!$response || !isset($response['code'])) {
			throw new CHttpException(500, '微服务请求失败，无结果返回: ' . json_decode($response));
		}

		if ($response['code'] != 0) {
			if ($this->runOnConsole) {
				echo sprintf(
					'[%s] 微服务请求: %s queryData: %s response %s[%s]',
					date('Y-m-d H:i:s'),
					$this->queryUrl,
					json_encode($this->queryData),
					$response['message'],
					$response['code']
				), PHP_EOL;
			}

			throw new CHttpException(
				500,
				sprintf('微服务请求失败：%s[%s]。请重试。', $response['message'], $response['code'])
			);
		}

		return $response['content'];
	}

	/**
	 * 计算缓存失效时间
	 * @param int $time
	 * @param int $max
	 * @param int $base
	 * @return mixed
	 */
	public static function GetCacheExpire($time = 1800, $max = 0, $base = 0)
	{
		$now = time();
		if (is_string($time)) {
			$expireAt = max(strtotime($time), $now);
			$base = (int)$max;
			if (!$base) {
				$base = $now;
			}

			$expire = $expireAt - $base;
		} else {
			$min = (int)$time;
			if (!$min) {
				$min = 1800;
			}
			$max = (int)$max;
			if (!$max) {
				$max = 3600;
			}
			$max = max($min, $max);
			$expire = mt_rand($min, $max);

			$base = (int)$base;
			if (!$base) {
				$base = $now;
			}

			$expire = $expire + $base - $now;
		}

		return max(0, $expire);
	}
}