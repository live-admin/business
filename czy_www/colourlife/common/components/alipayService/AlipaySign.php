<?php

class AlipaySign {

	protected $config;

	public function __construct() {
		$this->config = require(dirname ( __FILE__ ).'/config.php');
	}

	public function rsa_sign($data, $rsaPrivateKeyFilePath='') {
		$rsaPrivateKeyFilePath = $rsaPrivateKeyFilePath ? $rsaPrivateKeyFilePath : $this->config['merchant_private_key_file'];
		$priKey = file_get_contents ( $rsaPrivateKeyFilePath );
		$res = openssl_get_privatekey ( $priKey );
		openssl_sign ( $data, $sign, $res );
		openssl_free_key ( $res );
		$sign = base64_encode ( $sign );
		return $sign;
	}

	public function sign_request($params, $rsaPrivateKeyFilePath='') {
		$rsaPrivateKeyFilePath = $rsaPrivateKeyFilePath ? $rsaPrivateKeyFilePath : $this->config['merchant_private_key_file'];
		return $this->rsa_sign ( $this->getSignContent ( $params ), $rsaPrivateKeyFilePath );
	}

	public function sign_response($bizContent, $charset) {
		$sign = $this->rsa_sign ( $bizContent );
		$response = "<?xml version=\"1.0\" encoding=\"$charset\"?><alipay><response>$bizContent</response><sign>$sign</sign><sign_type>RSA</sign_type></alipay>";
		return $response;
	}

	public function rsa_verify($data, $sign, $aliPublicKeyFilePath='') {
		$aliPublicKeyFilePath = $aliPublicKeyFilePath ? $aliPublicKeyFilePath : $this->config['alipay_public_key_file'];
		// 读取公钥文件
		$pubKey = file_get_contents ( $aliPublicKeyFilePath );

		// 转换为openssl格式密钥
		$res = openssl_get_publickey ( $pubKey );

		// 调用openssl内置方法验签，返回bool值
		$result = ( bool ) openssl_verify ( $data, base64_decode ( $sign ), $res );

		// 释放资源
		openssl_free_key ( $res );

		return $result;
	}

	public function rsaCheckV2($params, $aliPublicKeyFilePath='') {
		$aliPublicKeyFilePath = $aliPublicKeyFilePath ? $aliPublicKeyFilePath : $this->config['alipay_public_key_file'];
		$sign = $params ['sign'];
		$params ['sign'] = null;

		return $this->rsa_verify ( $this->getSignContent ( $params ), $sign, $aliPublicKeyFilePath );
	}

	public function getSignContent($params) {
		ksort ( $params );
		
		$stringToBeSigned = "";
		$i = 0;
		foreach ( $params as $k => $v ) {
			if (false === $this->checkEmpty ( $v ) && "@" != substr ( $v, 0, 1 )) {
				if ($i == 0) {
					$stringToBeSigned .= "$k" . "=" . "$v";
				} else {
					$stringToBeSigned .= "&" . "$k" . "=" . "$v";
				}
				$i ++;
			}
		}
		unset ( $k, $v );
		return $stringToBeSigned;
	}
	
	/**
	 * 校验$value是否非空
	 * if not set ,return true;
	 * if is null , return true;
	 */
	protected function checkEmpty($value) {
		if (! isset ( $value ))
			return true;
		if ($value === null)
			return true;
		if (trim ( $value ) === "")
			return true;
		
		return false;
	}

	public function getPublicKeyStr($rsaPublicKeyFilePath='') {
		$rsaPublicKeyFilePath = $rsaPublicKeyFilePath ? $rsaPublicKeyFilePath : $this->config['merchant_public_key_file'];
		$content = file_get_contents ( $rsaPublicKeyFilePath );
		$content = str_replace ( "-----BEGIN PUBLIC KEY-----", "", $content );
		$content = str_replace ( "-----END PUBLIC KEY-----", "", $content );
		$content = str_replace ( "\r", "", $content );
		$content = str_replace ( "\n", "", $content );
		return $content;
	}



	/******************/
	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	public function createLinkstring($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);

		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

		return $arg;
	}

	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	public function createLinkstringUrlencode($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".urlencode($val)."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);

		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

		return $arg;
	}

	/**
	 * 除去数组中的空值和签名参数
	 * @param $para 签名参数组
	 * return 去掉空值与签名参数后的新签名参数组
	 */
	public function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == "sign" || $key == "sign_type" || $val == "")continue;
			else	$para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}

	/**
	 * 对数组排序
	 * @param $para 排序前的数组
	 * return 排序后的数组
	 */
	public function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}


}