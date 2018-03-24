<?php
/**
 * ALIPAY API: alipay.gotone.message.mail.send request
 *
 * @author auto create
 * @since 1.0, 2014-06-12 17:16:37
 */
class AlipayGotoneMessageMailSendRequest
{
	/** 
	 * 模板参数
	 **/
	private $arguments;
	
	/** 
	 * 收件人邮箱地址
	 **/
	private $receiver;
	
	/** 
	 * 邮件模板对应的serviceCode
	 **/
	private $serviceCode;
	
	/** 
	 * 邮件标题
	 **/
	private $subject;
	
	/** 
	 * 用户的支付宝ID
	 **/
	private $userId;

	private $apiParas = array();
	private $terminalType;
	private $terminalInfo;
	private $prodCode;
	private $apiVersion="1.0";
	
	public function setArguments($arguments)
	{
		$this->arguments = $arguments;
		$this->apiParas["arguments"] = $arguments;
	}

	public function getArguments()
	{
		return $this->arguments;
	}

	public function setReceiver($receiver)
	{
		$this->receiver = $receiver;
		$this->apiParas["receiver"] = $receiver;
	}

	public function getReceiver()
	{
		return $this->receiver;
	}

	public function setServiceCode($serviceCode)
	{
		$this->serviceCode = $serviceCode;
		$this->apiParas["service_code"] = $serviceCode;
	}

	public function getServiceCode()
	{
		return $this->serviceCode;
	}

	public function setSubject($subject)
	{
		$this->subject = $subject;
		$this->apiParas["subject"] = $subject;
	}

	public function getSubject()
	{
		return $this->subject;
	}

	public function setUserId($userId)
	{
		$this->userId = $userId;
		$this->apiParas["user_id"] = $userId;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function getApiMethodName()
	{
		return "alipay.gotone.message.mail.send";
	}

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function getTerminalType()
	{
		return $this->terminalType;
	}

	public function setTerminalType($terminalType)
	{
		$this->terminalType = $terminalType;
	}

	public function getTerminalInfo()
	{
		return $this->terminalInfo;
	}

	public function setTerminalInfo($terminalInfo)
	{
		$this->terminalInfo = $terminalInfo;
	}

	public function getProdCode()
	{
		return $this->prodCode;
	}

	public function setProdCode($prodCode)
	{
		$this->prodCode = $prodCode;
	}

	public function setApiVersion($apiVersion)
	{
		$this->apiVersion=$apiVersion;
	}

	public function getApiVersion()
	{
		return $this->apiVersion;
	}

}
