<?php
/**
 * 地方饭票服务
 */
Yii::import('common.services.BaseService');
class LocalRedPacketService extends BaseService{
	
	/**
	 * 开通业主地方饭票账号
	 */
	public function openLocalAccount($param = array()){
		if (empty($param) || empty($param['pano']) || empty($param['customerID'])){
			Yii::log('开通地方饭票账号：参数不能为空，pano：'.$param['pano'].'，customerID:'.$param['customerID'],CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.openLocalAccount');
			throw new CHttpException(400, '账号或用户ID不能为空！');
		}
		$pano = $param['pano'];
		//判断是否存在支付类型
		$panoParam = $this->checkPano($pano);
		if (empty($panoParam)){
			Yii::log('开通地方饭票账号：该地方饭票支付类型不存在:'.$pano,CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.openLocalAccount');
			throw new CHttpException(400, '支付类型不存在！');
		}
		$customerID = $param['customerID'];
		$customer = Customer::model()->findByPk($customerID);
		if (empty($customer) || $customer->state == 1 || $customer->is_deleted == 1){
			Yii::log('开通地方饭票账号：用户不存在，customerID：'.$customerID,CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.openLocalAccount');
			throw new CHttpException(400, '账号不存在！');
		}
		//判断用户是否已经添加到金融平台
		$financeCustomerRelateModel = FinanceCustomerRelateModel::model()->getByCustomerid($customerID,$pano);
		if (!empty($financeCustomerRelateModel)){
			return array('cano' => $financeCustomerRelateModel->cano);
		}
		$name = $customer->name;
		if(trim($name)==''){
			$name = $customer->nickname;
		}
		if(trim($name)==''){
			$name=$customer->username;
		}
		$paramData = array(
				'customer_id'=>$customer->id,
				'name'=>$name,
				'mobile'=>$customer->mobile,
				'gender'=>intval($customer->gender)
		);
		$note = isset($param['note'])?$param['note']:'开通地方饭票账号';
		try {
			//开通地方饭票账号接口
			Yii::log('调用金融平台开通地方饭票账号开始,参数为:'.json_encode($paramData).'，时间:'.time(),CLogger::LEVEL_INFO, 'colourlife.core.LocalRedPacketService.openLocalAccount');
			$customerModel =  FinanceMicroService::getInstance()->addClientClient($pano,'',$paramData['name'],$paramData['mobile'],$paramData['gender'],'',$note,0);
			Yii::log('调用金融平台开通地方饭票账号结束，时间:'.time().',结果：'.json_encode($customerModel),CLogger::LEVEL_INFO, 'colourlife.core.LocalRedPacketService.openLocalAccount');
		}catch (Exception $e){
			throw new CHttpException(400, $e->getMessage());
		}
		$cano = '';
		$cno = '';
		//获取cano
		if($customerModel && isset($customerModel['account'])){
			$account = $customerModel['account'];
			if($account && isset($account['cano'])){
				$cano = $account['cano'];
			}
		}
		//获取cno
		if($customerModel && isset($customerModel['client'])){
			$client = $customerModel['client'];
			if($client && isset($client['cno'])){
				$cno = $client['cno'];
			}
		}
		if($cano && $cno){
			$updateData= array(
					'pano'=>$pano,
					'fanpiaoid'=>$panoParam['fanpiaoid'],
					'atid'=>$panoParam['atid'],
					'cno'=>$cno,
					'cano'=>$cano,
					'customer_id'=>intval($customer->id),
					'mobile'=>$customer->mobile,
					'name'=>$name,
					'pay_password'=>$customer->pay_password
			);
			Yii::log('更新本地数据开始，参数为：'.json_encode($updateData),CLogger::LEVEL_INFO, 'colourlife.core.LocalRedPacketService.openLocalAccount');
			//更新本地数据
			$result = FinanceCustomerRelateModel::model()->addFinanceCustomerRelation($updateData);
			Yii::log('更新本地数据结束，结果为：'.$result,CLogger::LEVEL_INFO, 'colourlife.core.LocalRedPacketService.openLocalAccount');
			return array('cano' => $cano);
		}else {
			throw new CHttpException(400, '开通地方饭票账号失败！');
		}
	}
	
	/**
	 * 判断支付类型是否存在
	 * @param unknown $pano
	 * @return multitype:unknown NULL |multitype:
	 */
	public function checkPano($pano){
		$panoModel = FinancePayType::model()->find("pano = :pano and typeid = 7",array(':pano' => $pano));
		if (!empty($panoModel)){
			return array(
					'fanpiaoid' => $panoModel->id,
					'pano' => $panoModel->pano,
					'atid' => $panoModel->atid
			);
		}else {
			return array();
		}
	}
	
	/**
	 * 查询地方饭票余额
	 * @param unknown $pano
	 * @param unknown $customerID
	 * @param string $note
	 * @throws CHttpException
	 * @return unknown
	 */
	public function queryBalance($param = array()){
		if (empty($param) || empty($param['pano']) || empty($param['customerID'])){
			Yii::log('查询用户地方饭票余额：参数不能为空，'.json_encode($param),CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.queryBalance');
			throw new CHttpException(400, '账号或用户ID不能为空！');
		}
		$pano = $param['pano'];
		//判断是否存在支付类型
		$panoParam = $this->checkPano($pano);
		if (empty($panoParam)){
			Yii::log('查询用户地方饭票余额：该地方饭票支付类型不存在:'.$pano,CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.queryBalance');
			throw new CHttpException(400, '支付类型不存在！');
		}
		$customerID = $param['customerID'];
		$customer = Customer::model()->findByPk($customerID);
		if (empty($customer) || $customer->state == 1 || $customer->is_deleted == 1){
			Yii::log('查询用户地方饭票余额：用户不存在，customerID：'.$customerID,CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.queryBalance');
			throw new CHttpException(400, '账号不存在！');
		}
		$note = isset($param['note'])?$param['note']:'查询用户地方饭票余额';
		//查询余额的逻辑
		$balance = $customer->getBalance(true,$panoParam['pano'],$panoParam['atid'],$note);
		return array('balance' => $balance,'customer' => $customer,'atid' => $panoParam['atid']);
	}
	
	/**
	 * 地方饭票充值
	 * @param unknown $param
	 * @throws CHttpException
	 */
	public function localTransaction($param = array()){
		if (empty($param) || empty($param['pano']) || empty($param['customerID']) || empty($param['orderNo'])){
			Yii::log('地方饭票充值：参数不能为空:'.json_encode($param),CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.localTransaction');
			throw new CHttpException(400, '参数不能为空！');
		}
		$pano = $param['pano'];
		//判断是否存在支付类型
		$panoParam = $this->checkPano($pano);
		if (empty($panoParam)){
			Yii::log('地方饭票充值：该地方饭票支付类型不存在:'.$pano,CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.localTransaction');
			throw new CHttpException(400, '支付类型不存在！');
		}
		$customerID = $param['customerID'];
		$customer = Customer::model()->findByPk($customerID);
		if (empty($customer) || $customer->state == 1 || $customer->is_deleted == 1){
			Yii::log('地方饭票充值：用户不存在，customerID：'.$customerID,CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.localTransaction');
			throw new CHttpException(400, '账号不存在！');
		}
		$amount = $param['amount'];
		$note = isset($param['note'])?$param['note']:'地方饭票充值';
		//获取金融平台账户
		$financeCustomerRelateModel = FinanceCustomerRelateModel::model()->getByCustomerid($customerID,$pano);
		if (empty($financeCustomerRelateModel)){
			Yii::log('地方饭票充值：用户的金融平台地方饭票账号未配置，customerID：'.$customerID,CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.localTransaction');
			throw new CHttpException(400, '用户的金融平台地方饭票账号还未配置，请联系管理人员！');
		}
		$isTransaction = Yii::app()->db->getCurrentTransaction();//判断是否有用过事务
		$transaction = (!$isTransaction)?Yii::app()->db->beginTransaction():'';
		//本地记录
		$localRedPacketRecharge = new LocalRedPacketRecharge();
		$attr = array(
			'customer_id' => $customerID,
			'pano' => $panoParam['pano'],
			'atid' => $panoParam['atid'],
			'cano' => $financeCustomerRelateModel->cano,
			'amount' => $amount,
			'note' => $note,
			'orderno' => $param['orderNo'],
			'state' => 0,
			'create_time' => time()
		);
		//充值
		try {
			Yii::log('地方饭票充值开始，参数为：'.json_encode($attr),CLogger::LEVEL_INFO, 'colourlife.core.LocalRedPacketService.localTransaction');
			$localRedPacketRecharge->setAttributes($attr);
			if (!$localRedPacketRecharge->save()){
				Yii::log('地方饭票充值记录入库失败，'.json_encode($transaction),CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.localTransaction');
				(!$isTransaction)?$transaction->rollback():'';
				return array('status'=>0,'msg'=>'充值入库失败');
			}
			
			$ftransaction = FinanceMicroService::getInstance()->fastTransaction($amount,$note,$panoParam['atid'],$panoParam['pano'],$panoParam['atid'],$financeCustomerRelateModel->cano,'','',$param['orderNo']);
			if(!$ftransaction || !isset($ftransaction['payinfo']) || !isset($ftransaction['payinfo']['tno'])){
                	Yii::log('调用金融平台的地方饭票充值失败，结果为：'.json_encode($ftransaction),CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.localTransaction');
                	(!$isTransaction)?$transaction->rollback():'';
                	return array('status'=>0,'msg'=>'调用金融平台的地方饭票充值接口失败');
                }
			(!$isTransaction)?$transaction->commit():'';
			Yii::log('地方饭票充值结束，结果为：'.json_encode($transaction),CLogger::LEVEL_INFO, 'colourlife.core.LocalRedPacketService.localTransaction');
			return array('status'=>1,'msg'=>$ftransaction['payinfo']['tno']);
		}catch (Exception $e){
			Yii::log('地方饭票充值异常：'.json_encode($e->getMessage()),CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.localTransaction');
			(!$isTransaction)?$transaction->rollback():'';
			return array('status'=>0,'msg'=>$e->getMessage());
		}
		return array('status'=>0,'msg'=>'地方饭票充值失败');
	}
	
	/**
	 * 获取地方饭票比率
	 * @param unknown $param
	 * @throws CHttpException
	 */
	public function getLocalRate($pano){
		if (!$pano || empty($pano)){
			Yii::log('查询地方饭票比率：pano参数不能为空',CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.getLocalRate');
			throw new CHttpException(400, '账号不能为空！');
		}
		//判断是否存在支付类型
		$panoParam = $this->checkPano($pano);
		if (empty($panoParam)){
			Yii::log('查询地方饭票比率：该地方饭票支付类型不存在:'.$pano,CLogger::LEVEL_ERROR, 'colourlife.core.LocalRedPacketService.getLocalRate');
			throw new CHttpException(400, '支付类型不存在！');
		}
		
		try {
			//查询地方饭票比率
			Yii::log('调用金融平台查询地方饭票比率开始,账号：'.$pano.'，时间:'.time(),CLogger::LEVEL_INFO, 'colourlife.core.LocalRedPacketService.getLocalRate');
			$rateModel =  FinanceMicroService::getInstance()->getRate($pano, $panoParam['atid']);
			Yii::log('调用金融平台查询地方饭票比率结束，时间:'.time().',结果：'.json_encode($rateModel),CLogger::LEVEL_INFO, 'colourlife.core.LocalRedPacketService.getLocalRate');
			if (empty($rateModel) || empty($rateModel['rate'])){
				Yii::log('调用金融平台查询地方饭票比率失败，时间:'.time().',账号：'.$pano,CLogger::LEVEL_INFO, 'colourlife.core.LocalRedPacketService.getLocalRate');
				throw new CHttpException(400, '无数据返回');
			}
			return $rateModel['rate'];
		}catch (Exception $e){
			Yii::log('调用金融平台查询地方饭票比率结束，时间:'.time().',错误码：'.$e->getCode().',错误信息：'.$e->getMessage(),CLogger::LEVEL_INFO, 'colourlife.core.LocalRedPacketService.getLocalRate');
			throw new CHttpException(400, $e->getMessage());
		}
	}

	/*
	 * 获取个人所有饭票类型名称及余额
	 */
	public function getMealList($customer_id){
		if(empty($customer_id)){
			return false;
		}

	}
	
}