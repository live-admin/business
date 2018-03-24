<?php

Yii::import('common.api.EntInfoApi');
Yii::import('common.api.MobsetApi');
Yii::import('common.api.KltxApi');
Yii::import('common.api.BusinessSmsApi');
Yii::import('common.api.ChuanglanSmsApi');  //创蓝

/**
 * 短信发送组件
 *
 * $sms = Yii::app()->sms;;
 * $sms->setType('register', array('mobile, '13012345678'));
 * if ($sms->send()) {
 *      // ok
 * } else {
 *      // failed
 * }
 *
 * 使用 Yii::app()->config->sms 配置业务
 * corpId, loginName, passwd 用于 MobsetApi 初始化
 * simulate 模拟运行，不调用 api
 * registerTemplate 注册短信模版，使用 {code} 替换随机字符串
 * resetPasswordTemplate 重置密码模版，使用 {code} 替换随机字符串
 *
 * Class SmsComponent
 */
class SmsComponent extends CApplicationComponent
{
	CONST TYPE_REGISTER = 1;
	CONST TYPE_RESET_PASSWORD = 2;
	protected $config, $api;
	protected $smsMobileCount;

	public $mobile, $type, $code, $name, $token, $errno = 0, $error = '', $object_id, $userMobile, $inviteCode;
	public $num;
	public $smsModel;
	/**
	 * @var Order|PurchaseReturn|RetreatOrder
	 */
	public $orderModel;

	public function init()
	{
		$this->config = Yii::app()->config->sms;


		##########  通过短信接口选择而选取接口信息  ############
		$key = array();
		if ($model = SmsInterface::model()->findByPk($this->config['smsInfo'])) {
			$key = @unserialize($model->config);
		}
		//unset( $this->config['smsInfo'] );
		$this->config = array_merge($this->config, $key);
		#############  END 选取接口结束  ##################

		switch ($this->config['smsInfo']) {
			case 1:
				$this->api = EntInfoApi::getInstanceWithConfig($this->config);
				$this->smsMobileCount = EntInfoApi::SMS_MOBILE_COUNT;
				break;
			case 2:
				$this->api = MobsetApi::getInstanceWithConfig($this->config);
				$this->smsMobileCount = MobsetApi::SMS_MOBILE_COUNT;
				break;
			case 3:
				$this->api = KltxApi::getInstanceWithConfig($this->config);
				$this->smsMobileCount = KltxApi::SMS_MOBILE_COUNT;
				break;
			case 4:
				$this->api = BusinessSmsApi::getInstanceWithConfig($this->config);
				$this->smsMobileCount = BusinessSmsApi::SMS_MOBILE_COUNT;
				break;
			case 5:
				$this->api = ChuanglanSmsApi::getInstanceWithConfig($this->config);
				$this->smsMobileCount = ChuanglanSmsApi::SMS_MOBILE_COUNT;
				break;
			default:
				throw new CException('内部错误：未配置短信接口，请联系网站管理员。');
		}

		$this->smsMobileCount = 70;

		//$this->api = EntInfoApi::getInstanceWithConfig ( $this->config );
		return parent::init();
	}

	public function setType($type, $data)
	{
		$this->type = $type;
		$this->mobile = $data['mobile'];
		//fb($type);
		switch ($type) {
			case 'verifyCode':
				$this->code = $data['code'];
				break;
			case 'verifyToken':
				$this->token = $data['token'];
				break;
			case 'employee' : //物管邀请
				$this->name = $data['token'];
				break;

		}
	}

	public function getCanSend()
	{
		// 只能初始状态下可以发送短信
		if (!empty($this->code) || !empty($this->token)) {
			$this->errno = 1;
			$this->error = '短信已发送';
			return false;
		}
		// 使用手机号码获取用户，包含被禁用的用户，但不包含被删除的
		$user = Customer::model()->findByAttributes(array('mobile' => $this->mobile));
		switch ($this->type) {
			case 'register':
				if (!empty($user)) {
					$this->errno = 2;
					$this->error = '该手机号码已被使用';
					return false;
				}
				break;
			case 'resetPassword':
				if (empty($user)) {
					$this->errno = 3;
					$this->error = '该手机号码未被使用';
					return false;
				}
				break;
			case 'bindDing':
				if (empty($user)) {
					$this->errno = 4;
					$this->error = '该手机号码未被使用';
					return false;
				}
				break;
		}
		return true;
	}

	/**
	 * 用户注册|重置密码|好友邀请|经理邀请
	 * @param string $type registerTemplate|resetPasswordTemplate|inviteTemplate|employeeTemplate
	 * @param int $way 0普通短信途径，1为语音途径
	 * @return bool
	 * @throws CHttpException
	 */
	public function sendUserMessage($type = 'registerTemplate', $way = 0)
	{
		if ($type != 'otherVerify' && !$model = SmsTemplate::model()->findByAttributes(array('category' => 'user', 'code' => $type))) {

			throw new CHttpException(404);
		}

		if ($model->state == 1) {
			return true; //根据字段判断是否需要发短信
		}

		$config = Yii::app()->config->sms;
		$time = time() - $config['validTime'];
		$this->smsModel = Sms::model()->find("mobile=:mobile AND create_time>:time AND status=:status", array(":mobile" => $this->mobile, ":time" => $time, ":status" => Sms::STATUS_SEND_OK));
		$num = count($this->smsModel);
		if ($num > 0) {
			$this->code = $this->smsModel->code;
		} else {
			$this->code = $this->setSmsMessage();
		}

		if ($way == 1) {  //发送语音验证码
			$message = '您的验证码是' . $this->code;
			$this->setSmsCount();

			return $this->sendYuyin($message, true);
		} else {

			if ($this->type == 'employee') { //判断是否为物管邀请
				$message = $this->render($model->template, array('{name}' => $this->name));
			} else if ($this->type == "invite") {
				$message = $this->render($model->template, array('{name}' => $this->name));

			} else if ($this->type == "invitecodeNotice") {
				$message = $this->render($model->template, array('{name}' => $this->name));
			} else if ($this->type == "milkInvite") {
				$message = $this->render($model->template, array('{name}' => $this->name));
			} else if ($this->type == "give") {

				$message = $this->render($model->template, array('{mobile}' => $this->userMobile, '{num}' => $this->num));

			} elseif ($this->type == "inviteCode") {
				$message = $this->render($model->template, array('{mobile}' => $this->userMobile, '{inviteCode}' => $this->inviteCode));
			} elseif ($this->type == "serviceMonitor") {
				$message = $this->render($model->template, array('{num}' => $this->num));
			} else {

				if ($this->type == "bindDing") {
					$this->setSmsCountNew();
				} else {
					$this->setSmsCount();
				}
				// $this->setSmsCount();
				$message = $this->render($model->template, array('{code}' => $this->code));
			}

			return $this->send($message, true);
		}
	}

	/**
	 * 用户重置密码
	 * @param string $type registerTemplate|resetPasswordTemplate|inviteTemplate|employeeTemplate
	 * @return bool
	 * @throws CHttpException
	 */
//    public function sendCustomerResetPasswordMessage($templateCode = 'resetPasswordTemplate',$customer_id,$title="重置密码通知")
//    {
//        if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'user', 'code' => $templateCode))) {
//            throw new CHttpException(404);
//        }
//        if ($model->state == 1) {
//            return true; //根据字段判断是否需要发短信
//        }
//        if (!$customer = Customer::model()->findByPk($customer_id)) {
//            $this->errno = 6;
//            $this->error = '用户不存在！';
//            return false;
//        }
//        $this->mobile=$customer->mobile;
//        $this->object_id=$customer_id;
//        $params = array();
//        $value = @explode(',', $model->value);
//        $value = @array_flip($value);
//        $config=Yii::app()->config->sms;
//        $time=time()-$config['validTime'];
//        $smsModel=Sms::model()->find("mobile=:mobile AND create_time>:time AND status=:status",array(":mobile"=>$this->mobile,":time"=>$time,":status"=>Sms::STATUS_SEND_OK));
//        $num=count($this->smsModel);
//        if (isset($value['{code}'])) {
//            if($num>0){
//                $params['{code}'] = $smsModel->code;
//            }else{
//                $params['{code}'] =$this->setSmsMessage();
//            }
//        }
//
//
//
//        $this->setSmsMessage();
//        $message = $this->render($model->template, $params);
//        if ($this->send($message)) {
//            PushInformation::createSNSInformations($title,$message,$this->mobile,$this->object_id,PushInformation::IS_TYPE_CUSTOMER);
//            Yii::log("给号码'{$this->mobile}' 发送重置密码短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
//        } else {
//            Yii::log("给号码'{$this->mobile}' 发送重置密码短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
//        }
//    }

	/**
	 * 业主订单短信发送             已付款|接受|发货|拒绝|退款成功|同意退货|退货收货|拒绝退货
	 * @param   string $type paid|accept|ship|deny||refundSuccessagreedToReturn|returnReceipt|refusedToReturn
	 * @param string $orderId 订单号
	 * @return bool                 返回短信发送状态
	 * 根据模版名称和订单号获得手机号码及发送内容。发送短信
	 */
	public function sendOwnerOrderMessage($templateCode, $orderId, $title = "商品订单")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'goodsOrders', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错';
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($model->state == 1) {
			return true;
		}
		if (!$order = Order::model()->findByAttributes(array('sn' => $orderId))) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}

		$this->mobile = empty($order->customer_buyer->mobile) ? $order->buyer_tel : $order->customer_buyer->mobile; //如没设置手机号则用订单里面的手机号
		$this->object_id = $order->buyer_id;
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->sn; //订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $order->amount; //订单金额
		}
		if (isset($value['{order_shipped}'])) {
			$params['{order_shipped}'] = $order->delivery_express_sn; //送货订单
		}
		if (isset($value['{delivery_express_name}'])) {
			$params['{delivery_express_name}'] = $order->delivery_express_name; //快递公司
		}

		$message = $this->render($model->template, $params);
		if ($this->send($message, false)) {
			PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, PushInformation::IS_TYPE_CUSTOMER);
			Yii::log("给号码'{$this->mobile}' 发送业主订单短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$this->mobile}' 发送业主订单短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}

	/*
	 * @version 提货券订单发送短信
	 */
	public function sendOwnerThqOrderMessage($templateCode, $orderId, $title = "商品订单")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'goodsOrders', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错';
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($model->state == 1) {
			return true;
		}
		if (!$order = ThqOrder::model()->findByPk($orderId)) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}

		$this->mobile = $order->tel;
		$this->object_id = $order->buyer_id;
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->sn; //订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $order->amount; //订单金额
		}
		if (isset($value['{order_shipped}'])) {
			$params['{order_shipped}'] = $order->delivery_express_sn; //送货订单
		}
		if (isset($value['{delivery_express_name}'])) {
			$params['{delivery_express_name}'] = $order->delivery_express_name; //快递公司
		}

		$message = $this->render($model->template, $params);
		if ($this->send($message, false)) {
			PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, PushInformation::IS_TYPE_CUSTOMER);
			Yii::log("给号码'{$this->mobile}' 发送业主订单短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$this->mobile}' 发送业主订单短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}

	/**
	 * 商品订单
	 * @param $templateCode
	 * @param $orderId
	 * @param null $mobile
	 * @return bool
	 */
	public function sendGoodsOrdersMessage($templateCode, $orderId, $mobile = null, $title = "商品订单", $couponNo = '')
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'goodsOrders', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错！';
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($model->state == 1) {
			return true;
		}
		if (!$order = Order::model()->findByAttributes(array('sn' => $orderId))) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		if ($templateCode == 'paymentSuccess') {
			$type = PushClient::IS_TYPE_SHOP;
		} else {
			$type = PushClient::IS_TYPE_CUSTOMER;
		}
		if ($type == PushClient::IS_TYPE_SHOP) {
			$this->mobile = empty($mobile) ? $order->seller_tel : $mobile;
			$this->object_id = $order->seller_id;
		} else if ($type == PushClient::IS_TYPE_CUSTOMER) {
			$this->mobile = empty($order->customer_buyer->mobile) ? $mobile : $order->customer_buyer->mobile;
			$this->object_id = $order->buyer_id;
		}
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$order_sn = $order->sn;
			if (!empty($couponNo)) {
				//发送优惠码
				$order_sn .= $couponNo;
			}
			$params['{order}'] = $order_sn; //订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $order->amount; //订单金额
		}
		if (isset($value['{order_shipped}'])) {
			$params['{order_shipped}'] = $order->delivery_express_sn; //送货订单
		}
		if (isset($value['{delivery_express_name}'])) {
			$params['{delivery_express_name}'] = $order->delivery_express_name; //快递公司
		}
		//$this->setSmsMessage();
		$message = $this->render($model->template, $params);//参数组装

		if ($this->send($message, false)) {
			PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, $type);
			Yii::log("给号码'{$this->mobile}' 发送商品订单短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$this->mobile}' 发送商品订单短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}

	}

	/**
	 * 内部采购商品订单
	 * @param $templateCode
	 * @param $orderId
	 * @param null $mobile
	 * @return bool
	 */
	public function sendPurchaseMessage($templateCode, $orderId, $mobile = null, $title = "内部采购订单")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'goodsOrders', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错！';
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($model->state == 1) {
			return true;
		}
		if (!$purchaseOrder = PurchaseOrder::model()->findByAttributes(array('sn' => $orderId))) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		$type = 'paymentSuccess' == $templateCode ? PushClient::IS_TYPE_SHOP : PushClient::IS_TYPE_EMPLOYEE;
		if ($type == PushClient::IS_TYPE_SHOP) {
			$this->mobile = empty($mobile) ? $purchaseOrder->seller_tel : $mobile;
			$this->object_id = $purchaseOrder->shop_id;
		} else if ($type == PushClient::IS_TYPE_EMPLOYEE) {
			$this->mobile = empty($purchaseOrder->employee_buyer->mobile) ? $mobile : $purchaseOrder->employee_buyer->mobile;
			$this->object_id = $purchaseOrder->employee_id;
		}
		//$this->mobile = empty($mobile) ? (empty($this->mobile) ? $purchaseOrder->buyer_tel : $this->mobile) : $mobile;//手机号码选取
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $purchaseOrder->sn; //订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $purchaseOrder->amount; //订单金额
		}
		if (isset($value['{order_shipped}'])) {
			$params['{order_shipped}'] = $purchaseOrder->delivery_express_sn; //送货订单
		}
		if (isset($value['{delivery_express_name}'])) {
			$params['{delivery_express_name}'] = $purchaseOrder->delivery_express_name; //快递公司
		}
		//$this->setSmsMessage();
		$message = $this->render($model->template, $params);//参数组装
		if ($this->send($message, false)) {
			Yii::log("给号码'{$this->mobile}' 发送内部采购商品订单短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$this->mobile}' 发送内部采购商品订单短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}

		PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, $type);
	}

	/**
	 * 充值发送短信
	 * @param $templateCode 模板名
	 * @param $orderId      订单号
	 * @param null $mobile 手机号
	 * @return bool
	 */
	public function sendRechargeMessage($templateCode, $orderId, $mobile = null, $title = "付款通知")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'recharge', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错！';
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($model->state == 1) {
			return true;
		}
		if (!$order = OthersFees::model()->findByAttributes(array('sn' => $orderId))) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		$this->object_id = $order->customer_id;
		$this->mobile = empty($mobile) ? (empty($this->mobile) ? $order->buyer_tel : $this->mobile) : $mobile;//手机号码选取
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->sn; //订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $order->amount; //订单金额
		}
		//$this->setSmsMessage();   //保存到Sms表
		$message = $this->render($model->template, $params);//参数组装
		if ($this->send($message, false)) {
			PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, PushClient::IS_TYPE_CUSTOMER);
			Yii::log("给号码'{$this->mobile}' 发送充值订单短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$this->mobile}' 发送充值订单短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}

	/**
	 * 个人报修短信模板
	 * @param $templateCode
	 * @param $orderId
	 * @param null $mobile
	 * @return bool
	 */
	public function sendIndividualRepairMessage($templateCode, $orderId, $mobiles = null, $title = "个人报修通知")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'individualRepair', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错！';
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($model->state == 1) {
			return true;
		}
		if (!$order = PersonalRepairsInfo::model()->findByPk($orderId)) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		$type = "";
		if ($templateCode == "processingFinishCustomer" || $templateCode == "sellerAcceptCustomer" || $templateCode == "sellerRefusalCustomer" || $templateCode == "submitSuccessCustomer" || $templateCode == "successfulRepair") {
			$type = PushClient::IS_TYPE_CUSTOMER;
			$this->mobile = empty($mobiles) ? $order->customer->mobile : $mobiles;
			$this->object_id = $order->customer_id;
		} else if ($templateCode == "submitSuccessSeller" || $templateCode == "successfulRepairToBusiness") {
			$type = PushClient::IS_TYPE_SHOP;
			$this->mobile = empty($mobiles) ? $order->localShop->mobile : $mobiles;
			$this->object_id = $order->shop_id;
		} else {
			$type = PushClient::IS_TYPE_EMPLOYEE;
			$this->mobile = empty($mobiles) ? $order->execute_employee->mobile : $mobiles;
			$this->object_id = $order->execute;
		}
		$customer = Customer::model()->findByPk($order->customer_id);
		$customerMobile = (empty($customer)) ? 0 : $customer->mobile;
		$this->mobile = empty($mobile) ? (empty($this->mobile) ? $customerMobile : $this->mobile) : $mobile;//手机号码选取
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->id; //单号
		}
		if (isset($value['{seller}'])) {
			$params['{seller}'] = $order->getShopName(); //商家名称
		}
		if (isset($value['{customer}'])) {
			$params['{customer}'] = $order->getCustomerName(); //业主名称
		}
		if (isset($value['{community}'])) {
			$params['{community}'] = $order->getCommunityName(); //小区名称
		}
		if (isset($value['{mobile}'])) {
			$params['{mobile}'] = empty($order->customer) ? '' : $order->customer->mobile; //小区名称
		}
		$message = $this->render($model->template, $params);//参数组装
		$tem = is_array($this->mobile) ? explode(",", $this->mobile) : $this->mobile;
		PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, $type);

		if ($this->send($message, false)) {
			Yii::log("给号码'{$tem}' 发送个人报修短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$tem}' 发送个人报修短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}

	/**
	 * 公共报修
	 * @param $templateCode
	 * @param $orderId
	 * @param null $mobile
	 * @return bool
	 */
	public function sendPublicRepairMessage($templateCode, $orderId, $mobiles = null, $title = "公共报修通知")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'publicRepair', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错！';
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($model->state == 1) {
			return true;
		}
		if (!$order = PublicRepairs::model()->findByPk($orderId)) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		if ($templateCode == "successfulRepair" || $templateCode == "processingIsComplete") {
			$type = PushClient::IS_TYPE_CUSTOMER;
		} else {
			$type = PushClient::IS_TYPE_EMPLOYEE;
		}
		if ($type == PushClient::IS_TYPE_CUSTOMER) {
			$this->object_id = $order->user_id;
			$this->mobile = empty($mobiles) ? $order->customer->mobile : $mobiles;
		} else if ($type == PushClient::IS_TYPE_EMPLOYEE) {
			$this->object_id = $order->execute;
			$this->mobile = empty($mobiles) ? $order->execute_employee->mobile : $mobiles;
		}

		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->id; //订单号
		}
		$message = $this->render($model->template, $params);//参数组装
		$temp = is_array($this->mobile) ? implode(',', $this->mobile) : $this->mobile;
		if ($this->send($message, false)) {
			PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, $type);
			Yii::log("给号码'{$temp}' 发送公共报修短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$temp}' 发送公共报修短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}

	/**
	 * 业主投诉
	 * @param $templateCode
	 * @param $orderId
	 * @param null $mobile
	 * @return bool
	 */
	public function sendOwnerComplaintsMessage($templateCode, $orderId, $mobile = null, $title = "业主投诉通知")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'ownerComplaints', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错！';
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($templateCode == "complaintsSuccess" || $templateCode == "processingIsComplete") {
			$type = PushClient::IS_TYPE_CUSTOMER;
		} else {
			$type = PushClient::IS_TYPE_EMPLOYEE;
		}
		if ($model->state == 1) {
			return true;
		}
		if (!$order = OwnerComplain::model()->findByPk($orderId)) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		if ($type == PushClient::IS_TYPE_CUSTOMER) {
			$this->object_id = $order->user_id;
			$this->mobile = empty($mobile) ? $order->customer->mobile : $mobile;
		} else if ($type == PushClient::IS_TYPE_EMPLOYEE) {
			$this->object_id = $order->execute;
			$this->mobile = empty($mobile) ? $order->execute_employee->mobile : $mobile;
		}
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->id; //投诉单号
		}
		$message = $this->render($model->template, $params);//参数组装
		$temp = is_array($this->mobile) ? implode(',', $this->mobile) : $this->mobile;
		if ($this->send($message, false)) {
			PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, $type);
			Yii::log("给号码'{$temp}' 发送业主投诉短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$temp}' 发送业主投诉短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}

	/**
	 * 员工投诉发送短信模板内容
	 * @param $templateCode
	 * @param $orderId
	 * @param null $mobile
	 * @return bool
	 */
	public function sendStaffComplaintsMessage($templateCode, $orderId, $mobile = null, $title = "员工投诉通知")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'staffComplaints', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错！';
			Yii::log("获取短信模板出错。\$templateCode:{$templateCode},文件：" . __FILE__ . ",Line:" . __LINE__, CLogger::LEVEL_ERROR, 'colourlife.core.Sms');
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($model->state == 1) {
			return true;
		}
		if (!$order = StaffComplain::model()->findByPk($orderId)) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			Yii::log("获取订单出错。\$orderId:{$orderId},文件：" . __FILE__ . ",Line:" . __LINE__, CLogger::LEVEL_ERROR, 'colourlife.core.Sms');
			return false;
		}
		if ($templateCode == "complaintsSuccess" || $templateCode == "processingIsComplete") {
			$this->object_id = $order->user_id;
		} else {
			$this->object_id = $order->execute;
		}
		$employee = Employee::model()->findByPk($order->user_id);
		$employeeMobile = (empty($employee)) ? 0 : $employee->mobile;
		$this->mobile = empty($mobile) ? (empty($this->mobile) ? $employeeMobile : $this->mobile) : $mobile;//手机号码选取
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->id; //单号
		}
		$message = $this->render($model->template, $params);//参数组装
		$tmp = is_array($this->mobile) ? implode(',', $this->mobile) : $this->mobile;
		if ($this->send($message, false)) {
			Yii::log("给号码'{$tmp}' 发送员工投诉短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
			PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, PushClient::IS_TYPE_EMPLOYEE);
		} else {
			Yii::log("给号码'{$tmp}' 发送员工投诉短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}

	/**
	 * 物业缴费成功
	 * @param $type                 短信模板类型      paymentSuccess
	 * @param $orderId              订单ID
	 * @param string $category 物业缴费|物业预缴费    propertyPayment|prePayment
	 * @return bool
	 */
	public function sendPropertyPaymentMessage($type, $orderId, $category = 'propertyPayment', $title = "物业费缴费通知")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => $category, 'code' => $type))) {
			$this->errno = 6;
			$this->error = '内部出错';
			return false;
		}
		//根据配置不需要发送短信  返回1
		if ($model->state == 1) {
			return true;
		}

		if (!$order = OthersFees::model()->findByAttributes(array('sn' => $orderId))) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		$this->object_id = $order->customer_id;
		if (empty($this->mobile)) {
			//如没设置手机号则业主信息里面的手机号
			if ($customer = Customer::model()->findByPk($order->customer_id)) {
				$this->mobile = $customer->mobile;
			}
		}
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->sn; //订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $order->amount; //订单金额
		}
		if (isset($value['{community}'])) {
			$params['{community}'] = $order->ParkingFees->community->name; //小区名称
		}
		if (isset($value['{car_number}'])) {
			$params['{car_number}'] = $order->ParkingFees->car_number; //车牌
		}

		$this->setSmsMessage();
		$message = $this->render($model->template, $params);
		try {
			if ($this->send($message, false)) {
				PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, PushClient::IS_TYPE_CUSTOMER);
				Yii::log("给号码'{$this->mobile}' 发送短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
			} else {
				Yii::log("给号码'{$this->mobile}' 发送短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
			}
		} catch (Exception $e) {
			Yii::log('物业停车费等短信发送失败:' . $e->getMessage(), CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}

	/**
	 * 停车费短信模板
	 * @param $type     continuedSuccessCards|successfulPayment     续卡成功|支付成功
	 * @param $orderId  订单号
	 * @return bool
	 */
	public function sendParkingFeesMessage($type, $orderId, $title = "停车费缴费通知")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'parkingFees', 'code' => $type))) {
			$this->errno = 6;
			$this->error = '内部出错';
			return false;
		}
		//根据配置不需要发送短信  返回1
		if ($model->state == 1) {
			return true;
		}

		if (!$order = OthersFees::model()->findByAttributes(array('sn' => $orderId))) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		//if (empty($this->mobile)) {
		//如没设置手机号则业主信息里面的手机号
		$customer = Customer::model()->findByPk($order->customer_id);
		$this->object_id = $order->customer_id;
		$this->mobile = $customer->mobile;
		//}
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->sn; //订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $order->amount; //订单金额
		}
		if (isset($value['{community}'])) {
			$params['{community}'] = $order->ParkingFees->community->name; //小区名称
		}
		if (isset($value['{car_number}'])) {
			$params['{car_number}'] = $order->ParkingFees->car_number; //车牌
		}

		$this->setSmsMessage();
		$message = $this->render($model->template, $params);
		if ($this->send($message)) {
			PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, PushClient::IS_TYPE_CUSTOMER);
			Yii::log("给号码'{$this->mobile}' 发送停车费短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$this->mobile}' 发送停车费短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}


	/**
	 * 商铺买电短信模板
	 * @param $type     paymentSuccess     支付成功
	 * @param $sn 订单号
	 * @return bool
	 */
	public function sendPowerFeesMessage($type, $sn, $title = "商铺买电缴费成功通知")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'powerFees', 'code' => $type))) {
			$this->errno = 6;
			$this->error = '内部出错';
			return false;
		}
		//根据配置不需要发送短信  返回1
		if ($model->state == 1) {
			return true;
		}

		if (!$order = OthersFees::model()->findByAttributes(array('sn' => $sn, 'model' => 'PowerFees'))) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		//if (empty($this->mobile)) {
		//如没设置手机号则业主信息里面的手机号
		$customer = Customer::model()->findByPk($order->customer_id);
		$this->object_id = $order->customer_id;
		$this->mobile = $customer->mobile;
		//}
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->sn; //订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $order->amount; //订单金额
		}


		$this->setSmsMessage();
		$message = $this->render($model->template, $params);
		if (PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, PushClient::IS_TYPE_CUSTOMER)) {

			return true;
			Yii::log("给号码'{$this->mobile}' 客户端消息推送：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.SmsTuiSong');
		} else {
			Yii::log("给号码'{$this->mobile}' 客户端消息推送：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.SmsTuiSong');
		}

	}

	/**
	 * 商铺买电短信模板
	 * @param $type     insufficient   余额不足提醒
	 * @param $orderId  订单号
	 * @param $amount  电表剩余金额
	 * @return bool
	 */
	public function sendPowerFeesInsufficientMessage($type, $meter, $mobile, $amount, $title = "商铺买电余额不足通知")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'powerFees', 'code' => $type))) {
			$this->errno = 6;
			$this->error = '内部出错';
			return false;
		}
		//根据配置不需要发送短信  返回1
		if ($model->state == 1) {
			return true;
		}

		$this->mobile = $mobile;
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{meter}'])) {

			$params['{meter}'] = $meter; //电表号
		}

		if (isset($value['{amount}'])) {

			$params['{amount}'] = $amount; //订单金额
		}


		$this->setSmsMessage();
		$message = $this->render($model->template, $params);

		if ($this->send($message)) {

			$SmsLog = new SmsLog();
			$SmsLog->mobile = $mobile;
			$SmsLog->model = "PowerFeesInsufficient";
			$SmsLog->message = $message;
			$SmsLog->sms_type = 4;//企业短信接口
			$SmsLog->create_time = time();
			if (!$SmsLog->save()) {

				throw  new CHttpException(400, '短信日志保存失败');
			}


			Yii::log("给号码'{$this->mobile}' 发送商铺买电余额不足短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$this->mobile}' 发送商铺买电余额不足短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
		return $this->mobile;
	}


	/**
	 * 保存发送短信信息
	 * @return bool|int
	 */
	private function setSmsMessage()
	{
		$this->smsModel = new Sms();
		$this->smsModel->mobile = $this->mobile;
		$this->smsModel->user_agent = Yii::app()->request->userAgent;
		if (!$this->smsModel->save()) {
			$this->errno = 5;
			$this->error = '内部保存错误';
			return false;
		}

		return $this->smsModel->code;
	}

	//保存短信验证码
	private function setSmsCount()
	{
		$smsModel = new SmsCount();
		$smsModel->setScenario("register");
		$smsModel->mobile = $this->mobile;
		$smsModel->code = $this->code;
		$smsModel->create_time = time();
		$smsModel->save();
	}


	//保存短信验证码
	private function setSmsCountNew()
	{
		$smsModel = new SmsCount();
		$smsModel->setScenario("bindDing");
		$smsModel->mobile = $this->mobile;
		$smsModel->code = $this->code;
		$smsModel->create_time = time();
		$smsModel->save();
	}
	/**
	 *
	 */


	/**
	 * 通过 iCE 短信微服务发送短信
	 * @param string $mobile
	 * @param string $message
	 * @param bool $isWait
	 * @return bool
	 */
	private function iCESend($mobile = '', $message = '', $isWait = false)
	{
		// 园丁 （后期去掉该逻辑）
		$source = isset($_POST['source']) ? $_POST['source'] : '';
		$title = strtolower($source) == 'yd' ? '【园丁】' : '【彩生活】';
		$message = strtolower($source) == 'yd' ? str_replace('彩生活', '园丁', $message) : $message;
		$result = true;
		try {
			// 部分微服务没有版本（如 v1)
			Yii::import('common.components.GetTokenService');
			$service = new GetTokenService();
			$token = $service->getAccessTokenFromPrivilegeMicroService();
			//ICEService::getInstance()->setVersion('');
			ICEService::getInstance()->dispatch(
				'ztyy/voice/sendSMS',
				array(),
				array(
					'access_token' => $token,
					//'key' => '3UjhuuWh',
					'to' => $mobile,
					'content' => stripos($message, $title) !== false
						? $message
						: ($title . $message),
					'channelID' => '4'
				),
				'POST'
			);
//			ICEService::getInstance()->dispatch(
//				'dxpt/sms/send',
//				array(),
//				array(
//					'key' => '3UjhuuWh',
//					'mobile' => $mobile,
//					'content' => stripos($message, $title) !== false
//						? $message
//						: ($title . $message),
//					'channel' => 'csh_cly_yzm'
//				),
//				'POST'
//			);
			// 重置版本前缀，避免对后续影响
			ICEService::getInstance()->resetVersion();
		} catch (Exception $e) {
			Yii::log(
				sprintf(
					'手机号：%s 信息：%s 发送失败：%s[%s]',
					$mobile,
					$message,
					$e->getMessage(),
					$e->getCode()
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.sms.iCESend'
			);

			$result = false;
		}

		return $result;
	}

	/**
	 * @param $message      发送短信内容
	 * @param bool $isWait 是否要求接口返回内容  默认为true需要返回内容
	 * @return bool     返回接口操作状态
	 */
	private function send($message, $isWait = true)
	{

		$temp = is_array($this->mobile) ? implode(',', $this->mobile) : $this->mobile;
		Yii::log("手机号：'{$temp}' 信息：'{$message}'", CLogger::LEVEL_INFO, 'colourlife.core.sms.Send');
		if ($this->config['simulate'])
			$return = 1;
		else {
			if (is_array($this->mobile) && count($this->mobile = array_unique($this->mobile)) > $this->smsMobileCount) {
				$step = array_chunk($this->mobile, $this->smsMobileCount);//分割数组
				foreach ($step as $mobile) {
					//$return = $this->api->send($mobile,$message,$isWait);
					$return = $this->iCESend($mobile, $message, $isWait);
				}
			} else {

				//$return = $this->api->send($this->mobile, $message,$isWait);
				$return = $this->iCESend($this->mobile, $message, $isWait);
			}
		}
		if (!$isWait) {
			return true;
		}
		if (intval($return) > 0) {

			if ($this->type != "invite") {

				if (!$this->smsModel) {
					$this->setSmsMessage();
				}
				$this->smsModel->sendOK();

				$this->code = $this->smsModel->code;
			}
			return true;
		}
		//要求有返回值的时候 在这里进行返回处理
		$len = strlen($return);
		Yii::log("手机号：'{$temp}' 信息：'{$message}' 返回值({$len})：'{$return}'", CLogger::LEVEL_ERROR, 'colourlife.core.sms.SendFailed');
		$this->smsModel->sendFailed();
		$this->errno = 6;
		$this->error = '发送短信失败，请稍后重试';
		return false;
	}

	public function getCodeIsCorrect()
	{
		if (empty($this->code)) {
			$this->errno = 7;
			$this->error = '校验码不能为空';
			return false;
		}
		$time = time() - $this->config['validTime'];
		$sms = Sms::model()->findByMobileAndCode($this->mobile, $this->code, $time);
		if (empty($sms)) {
			$this->errno = 8;
			$this->error = '验证码不正确，请重新输入';
			return false;
		}
		return true;
	}

	public function useCode()
	{
		$sms = Sms::model()->findByMobileAndCode($this->mobile, $this->code, 0);
		if ($sms !== false) {
			$sms->useCode();
			$this->token = $sms->token;
		}
	}

	public function getTokenIsCorrect()
	{
		if (empty($this->token)) {
			$this->errno = 9;
			$this->error = '授权码不能为空';
			return false;
		}
		$time = time() - $this->config['validTime'];
		$sms = Sms::model()->findByMobileAndToken($this->mobile, $this->token, $time);
		if (empty($sms)) {
			Yii::log("验证手机号码 '{$this->mobile}' 的token '{$this->token}' 为空。时间为" . $time, CLogger::LEVEL_ERROR, 'colourlife.common.components.SmsComponent.getTokenIsCorrect');
			$this->errno = 10;
			$this->error = '授权码验证失败或过期';
			return false;
		}
		return true;
	}

	public function useToken()
	{
		$model = Sms::model()->findByMobileAndToken($this->mobile, $this->token, 0);
		if ($model !== false) {
			$model->useToken();
		}
	}

	protected function render($template, $params)
	{

		$keys = array_keys($params);
		$values = array_values($params);

		return str_replace($keys, $values, $template);
	}

	//发送自定义短信
	public function sendMsg($msg)
	{
		Yii::log("手机号：'{$this->mobile}' 信息：'{$msg}'", CLogger::LEVEL_INFO, 'colourlife.core.sms.Send');
		if ($this->config['simulate'])
			$return = 1;
		else
			//$return = $this->api->send($this->mobile, $msg);
			$return = $this->iCESend($this->mobile, $msg);
		if (intval($return) > 0) {
			return true;
		}
		$len = strlen($return);
		Yii::log("手机号：'{$this->mobile}' 信息：'{$msg}' 返回值({$len})：'{$return}'", CLogger::LEVEL_ERROR, 'colourlife.core.sms.SendFailed');
		$this->errno = 6;
		$this->error = '发送短信失败，请稍后重试';
		return false;
	}

	/**
	 * $sms->orderModel = 'Order|RetreatOrder'配置需要从哪个Model取数据
	 * $sms->sendCustomerOrderReturnMessage($type, $id)
	 * @param string $templateCode 短信模板
	 * @param integer $orderId 业主退货、退款表的ID
	 * @return bool
	 */
	public function sendCustomerOrderReturnMessage($templateCode, $orderId, $title = "商品订单")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'goodsOrders', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错！';
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($model->state == 1) {
			return true;
		}
		if (!$order = RetreatOrder::model()->findByPk($orderId)) {
			$this->errno = 6;
			$this->error = '退货单号不存在！';
			return false;
		}
		$this->mobile = $order->buyerInfo->mobile;
		$this->object_id = $order->buyer_id;
		$params = array();
		$params['orderId'] = $orderId;
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->order_sn; //原订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $order->amount; //订单金额
		}
		if (isset($value['{returnorder}'])) {
			$params['{returnorder}'] = $order->sn; //退货单号
		}
		$message = $this->render($model->template, $params);//参数组装
		if ($this->send($message, false)) {
			PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, PushClient::IS_TYPE_CUSTOMER);
			Yii::log("给号码'{$this->mobile}' 发送商品订单短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$this->mobile}' 发送商品订单短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}

	/**
	 * $sms->orderModel = 'Order|RetreatOrder'配置需要从哪个Model取数据
	 * $sms->sendCustomerOrderReturnMessage($type, $id)
	 * @param string $templateCode 短信模板
	 * @param integer $orderId 业主退货、退款表的ID
	 * @return bool
	 */
	public function sendPurchaseOrderReturnMessage($templateCode, $orderId, $title = "商品订单")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'goodsOrders', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错！';
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($model->state == 1) {
			return true;
		}
		if (!$order = PurchaseRetreatOrder::model()->findByPk($orderId)) {
			$this->errno = 6;
			$this->error = '退货单号不存在！';
			return false;
		}
		$this->mobile = $order->buyerInfo->mobile;
		$this->object_id = $order->buyer_id;
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->order_sn; //原订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $order->amount; //订单金额
		}
		if (isset($value['{returnorder}'])) {
			$params['{returnorder}'] = $order->sn; //退货单号
		}
		$message = $this->render($model->template, $params);//参数组装

		if ($this->send($message, false)) {
			PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, PushClient::IS_TYPE_EMPLOYEE);
			Yii::log("给号码'{$this->mobile}' 发送商品订单短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$this->mobile}' 发送商品订单短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}


	/**
	 * 业主退款订单
	 * @param $templateCode
	 * @param $orderId
	 * @param null $mobile
	 * @return bool
	 */
	public function sendRetreatOrdersMessage($templateCode, $orderId, $mobile = null, $title = "商品订单")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'goodsOrders', 'code' => $templateCode))) {
			$this->errno = 6;
			$this->error = '内部出错！';
			return false;
		}
		//判断是否开启该模板，0为开启， 1为关闭 如在关闭状态则返回true
		if ($model->state == 1) {
			return true;
		}
		if (!$order = RetreatOrder::model()->findByAttributes(array('sn' => $orderId))) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		$type = 'paymentSuccess' == $templateCode ? PushClient::IS_TYPE_SHOP : PushClient::IS_TYPE_CUSTOMER;
		if ($type == PushClient::IS_TYPE_SHOP) {
			$this->mobile = $order->seller_tel;
			$this->object_id = $order->seller_id;
		} else if ($type == PushClient::IS_TYPE_CUSTOMER) {
			$this->mobile = $order->customer_buyer->mobile;
			$this->object_id = $order->buyer_id;
		}
		$this->mobile = empty($mobile) ? (empty($this->mobile) ? $order->buyer_tel : $this->mobile) : $mobile;//手机号码选取
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->sn; //订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $order->amount; //订单金额
		}
		if (isset($value['{order_shipped}'])) {
			$params['{order_shipped}'] = $order->delivery_express_sn; //送货订单
		}
		if (isset($value['{delivery_express_name}'])) {
			$params['{delivery_express_name}'] = $order->delivery_express_name; //快递公司
		}
		//$this->setSmsMessage();
		$message = $this->render($model->template, $params);//参数组装
		if ($this->send($message, false)) {
			Yii::log("给号码'{$this->mobile}' 发送商品订单短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		} else {
			Yii::log("给号码'{$this->mobile}' 发送商品订单短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
		PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, $type);
	}

	/**
	 * 第三方支付 发送模板
	 * @param $type                 短信模板类型      paymentSuccess
	 * @param $orderId              订单ID
	 * @param string $category 第三方交费    thirdPayment
	 * @return bool
	 * @by wenda
	 */
	public function sendThirdPaymentMessage($type, $orderId, $title = "第三方缴费通知")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'thirdFees', 'code' => $type))) {
			$this->errno = 6;
			$this->error = '内部出错';
			return false;
		}
		//根据配置不需要发送短信  返回1
		if ($model->state == 1) {
			return true;
		}

		if (!$order = ThirdFees::model()->findByAttributes(array('sn' => $orderId))) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		$this->object_id = $order->customer_id;
		if (empty($this->mobile)) {
			//如没设置手机号则业主信息里面的手机号
			if ($customer = Customer::model()->findByPk($order->customer_id)) {
				$this->mobile = $customer->mobile;
			}
		}
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->sn; //订单号
		}
		if (isset($value['{amount}'])) {
			//$params['{amount}'] = $order->bank_pay + $order->red_packet_pay; //订单金额
			$params['{amount}'] = $order->amount; //订单金额
		}
		if (isset($value['{cId}'])) {
			if (empty($order->ThirdFeesAddr)) {
				Yii::log('第三方支付短信发送失败商户号为空:' . $e->getMessage(), CLogger::LEVEL_INFO, 'colourlife.core.Sms');
				return false;
			}
			$params['{cId}'] = $order->ThirdFeesAddr->cId; //商户号
		}
		if (isset($value['{name}'])) {
			if (empty($order->customer)) {
				Yii::log('第三方支付短信发送失败彩之云用户名为空:' . $e->getMessage(), CLogger::LEVEL_INFO, 'colourlife.core.Sms');
				return false;
			}
			$params['{name}'] = $order->customer->name; //彩之云用户名
		}

		$this->setSmsMessage();
		$message = $this->render($model->template, $params);
		try {
			if ($this->send($message, false)) {
				PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, PushClient::IS_TYPE_CUSTOMER);
				Yii::log("给号码'{$this->mobile}' 发送短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
			} else {
				Yii::log("给号码'{$this->mobile}' 发送短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
			}
		} catch (Exception $e) {
			Yii::log('第三方支付短信发送失败:' . $e->getMessage(), CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}

	/**
	 * 红包支付发送模板
	 * @param $type                 短信模板类型      paymentSuccess
	 * @param $orderId              订单ID
	 * @param string $category 红包支付   redpacketPayment
	 * @return bool
	 * @update 2015-06-03
	 * @by wenda
	 */
	public function sendRedpacketFeesPaymentMessage($type, $orderId, $title = "红包支付款通知")
	{
		if (!$model = SmsTemplate::model()->findByAttributes(array('category' => 'redpacketFees', 'code' => $type))) {
			$this->errno = 6;
			$this->error = '内部出错';
			return false;
		}
		//根据配置不需要发送短信  返回1
		if ($model->state == 1) {
			return true;
		}

		if (!$order = RedpacketFees::model()->findByAttributes(array('sn' => $orderId))) {
			$this->errno = 6;
			$this->error = '订单号不存在！';
			return false;
		}
		$this->object_id = $order->customer_id;
		if (empty($this->mobile)) {
			//如没设置手机号则业主信息里面的手机号
			if ($customer = Customer::model()->findByPk($order->customer_id)) {
				$this->mobile = $customer->mobile;
			}
		}
		$params = array();
		$value = @explode(',', $model->value);
		$value = @array_flip($value);
		if (isset($value['{order}'])) {
			$params['{order}'] = $order->sn; //订单号
		}
		if (isset($value['{amount}'])) {
			$params['{amount}'] = $order->amount; //订单金额
		}

		if (isset($value['{name}'])) {
			if (empty($order->customer)) {
				Yii::log('红包充值短信发送失败彩之云用户名为空:' . $e->getMessage(), CLogger::LEVEL_INFO, 'colourlife.core.Sms');
				return false;
			}
			$params['{name}'] = $order->customer->name; //彩之云用户名
		}

		$this->setSmsMessage();
		$message = $this->render($model->template, $params);
		try {
			if ($this->send($message, false)) {
				PushInformation::createSNSInformations($title, $message, $this->mobile, $this->object_id, PushClient::IS_TYPE_CUSTOMER);
				Yii::log("给号码'{$this->mobile}' 发送短信：'{$message}'成功！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
			} else {
				Yii::log("给号码'{$this->mobile}' 发送短信：'{$message}'失败！", CLogger::LEVEL_INFO, 'colourlife.core.Sms');
			}
		} catch (Exception $e) {
			Yii::log('红包充值短信发送失败:' . $e->getMessage(), CLogger::LEVEL_INFO, 'colourlife.core.Sms');
		}
	}

	/**
	 * 通过 iCE 短信微服务发送语音短信
	 * @param string $mobile
	 * @param string $code
	 * @param int $playTimes
	 * @return bool
	 */
	private function iCESendYuYin($mobile = '', $code = '', $playTimes = 3)
	{
		$result = true;
		try {
			// 部分微服务没有版本（如 v1)
			//ICEService::getInstance()->setVersion('');
			ICEService::getInstance()->dispatch(
					'szzxkj/colorapi/verifycode',
					array(
							'to' => $mobile,
							'code' => $code,
							'playtimes' => $playTimes,
							'channel' =>1,
							'access_token' => 1
					),
				array(),
					'GET'
			);
			// 重置版本前缀，避免对后续影响
			//ICEService::getInstance()->resetVersion();
		} catch (Exception $e) {
			Yii::log(
					sprintf(
							'手机号：%s 信息：%s 发送失败：%s[%s]',
							$mobile,
							$code,
							$e->getMessage(),
							$e->getCode()
					),
					CLogger::LEVEL_ERROR,
					'colourlife.core.sms.iCESendYuYin'
			);

			$result = false;
		}

		return $result;
	}

	//发送语音验证码
	private function sendYuyin($message, $isWait = true)
	{
		$temp = is_array($this->mobile) ? implode(',', $this->mobile) : $this->mobile;
		Yii::log("手机号：'{$temp}' 信息：'{$message}'", CLogger::LEVEL_INFO, 'colourlife.core.sms.SendYuyin');
		if ($this->config['simulate'])
			$return = 1;
		else {
			if (is_array($this->mobile) && count($this->mobile = array_unique($this->mobile)) > $this->smsMobileCount) {
				$step = array_chunk($this->mobile, $this->smsMobileCount);//分割数组
				foreach ($step as $mobile) {
					//$return = $this->api->sendYuYin($mobile, $message, $isWait);
					//return $this->iCESendYuYin($mobile, $this->code);
					$return = $this->sendNewYuYin($mobile, $this->code);
				}
			} else {

				//$return = $this->api->sendYuYin($this->mobile, $message, $isWait);
				//return $this->iCESendYuYin($this->mobile, $this->code);
				$return = $this->sendNewYuYin($this->mobile, $this->code);
			}
		}
		if (!$isWait) {
			return true;
		}
		if (intval($return) > 0) {

			if ($this->type != "invite") {

				if (!$this->smsModel) {
					$this->setSmsMessage();
				}
				$this->smsModel->sendOK();

				$this->code = $this->smsModel->code;
			}
			return true;
		}
		//要求有返回值的时候 在这里进行返回处理
		$len = strlen($return);
		Yii::log("手机号：'{$temp}' 信息：'{$message}' 返回值({$len})：'{$return}'", CLogger::LEVEL_ERROR, 'colourlife.core.sms.SendYuyinFailed');
		$this->smsModel->sendFailed();
		$this->errno = 6;
		$this->error = '发送语音验证码失败，请稍后重试';
		return false;
	}
	
	/**
	 * 自定义模板
	 * @param string $type registerTemplate|resetPasswordTemplate|inviteTemplate|employeeTemplate
	 * @param int $way 0普通短信途径，1为语音途径
	 * @return bool
	 * @throws CHttpException
	 */
	public function sendCustomMessage($smsTemplate = '',$way = 0)
	{
		$config = Yii::app()->config->sms;
		$time = time() - $config['validTime'];
		$this->smsModel = Sms::model()->find("mobile=:mobile AND create_time>:time AND status=:status", array(":mobile" => $this->mobile, ":time" => $time, ":status" => Sms::STATUS_SEND_OK));
		$num = count($this->smsModel);
		if ($num > 0) {
			$this->code = $this->smsModel->code;
		} else {
			$this->code = $this->setSmsMessage();
		}
		if ($this->type == "bindDing") {
			$this->setSmsCountNew();
		} else {
			$this->setSmsCount();
		}
		$message = $this->render($smsTemplate, array('{code}' => $this->code));
		if ($way == 1) {  //发送语音验证码
			$message = '您的验证码是' . $this->code;
			return $this->sendYuyin($message, true);

		} else {
			return $this->send($message, true);
		}
	}

	/*
	 * 语音验证码
	 */
	private function sendNewYuYin($mobile = '', $code = '', $play_times = 3)
	{
		$result = true;
		$service = new GetTokenService();
		try {
			ICEService::getInstance()->dispatch(
				'ztyy/voice/sendVoiceVerifyCode',
				array(),
				array(
					'to' => $mobile,
					'code' => $code,
					'playTimes' => $play_times,
					'channelID' =>12,
					'access_token' => $service->getAccessTokenFromPrivilegeMicroService()
				),
				'POST'
			);
		} catch (Exception $e) {
			Yii::log(
				sprintf(
					'手机号：%s 信息：%s 发送失败：%s[%s]',
					$mobile,
					$code,
					$e->getMessage(),
					$e->getCode()
				),
				CLogger::LEVEL_ERROR,
				'colourlife.core.sms.iCESendYuYin'
			);

			$result = false;
		}

		return $result;
	}
}