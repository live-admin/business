<?php
/**
 * 苹果支付插件
 * @author gongzhiling
 * @date 2016-9-8 14:29
 */
class applePay extends PayFactory {
	
	private $password = 'c9d9b568eba54dfbbed4019755a0b0fe'; //app store秘钥
	//protected  $receiptURL = 'https://sandbox.itunes.apple.com/verifyReceipt'; //测试
	protected  $receiptURL = 'https://buy.itunes.apple.com/verifyReceipt'; //正式
	
	/**
	 * 验证支付
	 * @param unknown $pay
	 * @param unknown $payment
	 */
	public function get_code($pay,$appleKey){
		if (empty($pay) || empty($appleKey)){
			return false;
		}
		//订单号
		$sn = $pay->pay_sn;
		//查找订单相对应的model
		$model = SN::findContentBySN($sn);
		if (empty($model))
			throw new CHttpException(400, "订单不存在");
		//请求参数
		$param = array(
			"receipt-data" => $appleKey
		);
		$result = Yii::app()->curl->postJson($this->receiptURL,json_encode($param));
		Yii::log('苹果支付请求验证返回结果：' . $result, CLogger::LEVEL_INFO, 'colourlife.core.payment.get_code.applePay');
		if (!empty($result)){
			$status = 0;
			$result=json_decode($result,true);
			//成功，记录返回结果日志
			if ($result['status']==0){
				$log=new ThirdResultLog();
				$log->sn = $sn;
				$log->result = json_encode($result['receipt']);
				$log->type = 'applePay';
				$log->create_time = time();
				$mresult=$log->save();
				$status=$this->response($pay);
			}
			return $status;
		}else {
			throw new CHttpException(400, "验证失败！");
		}
	}
	
	/**
	 * 支付成功改状态
	 * @return boolean
	 */
	private function response($pay){
		if (empty($pay)){
			return false;
		}
		$this->_sn=$pay->pay_sn;
		$amount = $this->getAmountNum($pay);
		$payment = Payment::model()->find("code='applePay'");
		if (empty($payment)){
			Yii::log('苹果支付方式不存在或已禁用：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.applePay');
			return false;
		}
		$orderStatus = Pay::getPayStatus($this->_sn);
		if (1 == $orderStatus){
			Yii::log('苹果支付成功支付流水号为：' . $this->_sn .'的状态已为'.$orderStatus, CLogger::LEVEL_INFO, 'colourlife.core.payment.applePay');
			return true;
		}
		if (0 == $orderStatus) { //状态为0才能去修改状态
			//添加支付日志
			if(!PayLog::createPayLog($this->_sn, $amount, $payment->id)){
				Yii::log('苹果支付添加支付记录失败流水号为：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.applePay');
				return false;
			}else
			{
				/* 改变订单状态 */
				PayLib::order_paid($this->_sn, $payment->id);
				Yii::log('苹果支付成功支付流水号为：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.applePay');
				//再读一次，判断状态是否修改成功
				$orderStatus = Pay::getPayStatus($this->_sn);
				if (1 == $orderStatus){
					return true;
				}
			}
		}
		return false;
	}
}