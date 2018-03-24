<?php
/**
 * ALIPAY API: alipay.lifeassistant.prod.bill.get request
 *
 * @author auto create
 * @since 1.0, 2014-09-04 21:27:22
 */
class AlipayLifeassistantProdBillGetRequest
{
	/** 
	 * 业务类型
1-手机充值
2-公共事业缴费
3-信用卡还款
	 **/
	private $bizType;
	
	/** 
	 * 商户类型
10001——新浪
	 **/
	private $mType;
	
	/** 
	 * 支付宝订单号
	 **/
	private $orderId;
	
	/** 
	 * 付款方外部用户ID
	 **/
	private $uid;

	private $apiParas = array();
	private $terminalType;
	private $terminalInfo;
	private $prodCode;
	private $apiVersion="1.0";
	
	public function setBizType($bizType)
	{
		$this->bizType = $bizType;
		$this->apiParas["biz_type"] = $bizType;
	}

	public function getBizType()
	{
		return $this->bizType;
	}

	public function setmType($mType)
	{
		$this->mType = $mType;
		$this->apiParas["m_type"] = $mType;
	}

	public function getmType()
	{
		return $this->mType;
	}

	public function setOrderId($orderId)
	{
		$this->orderId = $orderId;
		$this->apiParas["order_id"] = $orderId;
	}

	public function getOrderId()
	{
		return $this->orderId;
	}

	public function setUid($uid)
	{
		$this->uid = $uid;
		$this->apiParas["uid"] = $uid;
	}

	public function getUid()
	{
		return $this->uid;
	}

	public function getApiMethodName()
	{
		return "alipay.lifeassistant.prod.bill.get";
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
