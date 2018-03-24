<?php

/**
 * @author dw
 *
 */
class BindCustomerController extends SsoController
{
	public $layout="//";
	
	protected $title = "绑定彩之云账户";
	
	private $titleDict = null;

	private $employeBindObj = null;
	
	private $check_code_expire_time = 600;   //验证码过期时间.10分钟
	
	protected function isCompatibleMode()
	{
		return true;
	}
	
	protected function getSsoConfig()
	{
		return array(
				'app_id' => 'ICEBDCZY-5EC3-4794-97EB-0AB1075E9C11', //由单点登录平台提供的应用ID
				'token' => 'etJcxySpfc2CxxqQLjfd', //由单点登录平台提供的签名秘钥
		);
	}
	
	public function init()
	{
		$this->checkLogin();
	}
	

	public function actionSendCheckcode()
	{
		if($this->currentEmployeeIsBindedCustomer())
		{
			$this->jsonReturn(array(
					'code' => 11,
					'msg' => "已经绑定，不能再绑定"
			));
		}
		
	    $mobile = Yii::app()->request->getQuery("mobile");
	    
	    $cust = Customer::model()->find("mobile=:mobile", array(":mobile" => $mobile));	    
	    
	    if($cust == null)
	    {
	        $this->jsonReturn(array(
	                'code' => 2,
	                'msg' => "不存在彩之云账户"
	        ));
	    }
	    
	    if($this->customerIsBinded($cust->id))
	    {
	        $this->jsonReturn(array(
	                'code' => 12,
	                'msg' => "彩之云账户已被绑定"
	        ));
	    }
	    
	    
	    if(isset(Yii::app()->session["BIND_CUSTOMER_SENDCHECKCODE_TIME"])
	            && Yii::app()->session["BIND_CUSTOMER_SENDCHECKCODE_TIME"] + 60 > time())
	    {
	        $this->jsonReturn(array(
	                'code' => 3,
	                'msg' => "您发送短信频率过高"
	        ));
	    }
	    
	    if(!$this->sendCheckCode($mobile))
	    {
	        $this->jsonReturn(array(
	                'code' => 1,
	                'msg' => "发送短信失败"
	        ));
	    }
	    
	    Yii::app()->session["BIND_CUSTOMER_SENDCHECKCODE_MOBILE"] = $mobile;
	    Yii::app()->session["BIND_CUSTOMER_SENDCHECKCODE_TIME"] = time();
	    
	    $customer = array(
	            'name' => $cust->name ,
	            'portrait' => $cust->getPortraitUrl()
	    );
	    
	    $this->jsonReturn(array(
	            'code' => 0,
	            'msg' => "成功发送",
	            'customer' => $customer
	    ));
	}
	
	private function customerIsBinded($custid)
	{
	    $customerBind = EmployeeBindCustomer::model()->find(
	            "customer_id=:customer_id and state=1", array(":customer_id" => $custid));
	    return $customerBind != null;
	}
	
	private function sendCheckCode($mobile)
	{
	    $sms = $this->getSms();
	    $sms->setType("bindDing", array('mobile' => $mobile));
	    if($sms->sendUserMessage("bindDingTemplate"))
	    {
	       return true;
	    }
	    return false;
	}
	
	/**
	 * @return SmsComponent 
	 */
	private function getSms()
	{
	    return Yii::app()->sms;
	}
	
	private function verifyCheckCode($mobile, $code)
	{
	   $smsModel = Sms::model()
	       ->find("mobile=:mobile order by id desc", 
	            array(':mobile' => $mobile));
	   
	   if($smsModel == null)
	   {
	       return false;
	   }
	   if($smsModel->status != SMS::STATUS_SEND_OK)
	   {
	       return false;    
	   }
	   if($smsModel->create_time + $this->check_code_expire_time < time())
	   {
	       $smsModel->status = SMS::STATUS_SEND_FAILED;
	       $smsModel->save();
	       return false;
	   }
	   else if($smsModel->code != $code) 
	   {
	       return false;
	   }
	   else 
	   {
	       $smsModel->status = SMS::STATUS_CODE_USED;
	       $smsModel->save();
	       return true;
	   }
	}
	



	public function actionIndex()
	{
		$data=array(
				'customer' => $this->getBindedCustomer()
		);
		$this->render("index", $data);
	}	


	public function actionDoBinding()
	{
	    $mobile = Yii::app()->session["BIND_CUSTOMER_SENDCHECKCODE_MOBILE"];
	    $time = Yii::app()->session["BIND_CUSTOMER_SENDCHECKCODE_TIME"];
	     

	    if(!isset($mobile))
	    {
	        $this->jsonReturn(array(
	                'code' => 11,
	                "msg"  => "未向手机发送验证码"
	        ));
	    }
	    
	    $checkcode = Yii::app()->request->getQuery("checkcode", "");
	    
	    if($checkcode == "")
	    {
	        $this->jsonReturn(array(
	                'code' => 12,
	                'msg'  => "验证码错误"
	        ));
	    }
	    
	    if(!$this->verifyCheckCode($mobile, $checkcode))
	    {
	        $this->jsonReturn(array(
	                'code' => 12,
	                'msg'  => "验证码错误"
	        ));
	    }	    
	    
		if($this->currentEmployeeIsBindedCustomer())
		{
			$this->jsonReturn(array(
					"code" => 3,
					"msg"  => "当前用户已经绑定，不能再绑定"
			));
		}
		
		$customer = $this->getCustomerByMobile($mobile);
		if( $customer == null )
		{
			$this->jsonReturn(array(
					"code" => 1,
					"msg"  => "不存在彩之云账户，不能绑定"
			));
		}
		
		if($this->customerIsBinded($customer->id))
		{
		    $this->jsonReturn(array(
		            "code" => 13,
		            "msg"  => "彩之云账号已经被绑定"
		    ));
		}
		EmployeeBindCustomer::model()->deleteAll("employee_id=:employee_id or customer_id=:customer_id", 
		        array(
		                ":employee_id" => $this->employee_id,
		                ':customer_id' => $customer->id
		        ));
		
		$bindObj = new EmployeeBindCustomer();
		$bindObj->attributes = array(
				'employee_id' => $this->employee_id,
				'customer_id' => $customer->id,
				'bind_time' => time(),
				'state' => 1
		);
		
		if($bindObj->save())
		{
		    unset(Yii::app()->session["BIND_CUSTOMER_SENDCHECKCODE_MOBILE"]);
		    unset(Yii::app()->session["BIND_CUSTOMER_SENDCHECKCODE_TIME"]);
		     
			$this->jsonReturn(array(
					'code' => 0,
					'msg' => "ok",
					'redirect' => $this->createUrl('index')
			));
		}
		else
		{
			$this->jsonReturn(array(
					'code' => 4,
					'msg' => "绑定失败"
			));
		}
	}
	

	/**
	 * @return EmployeeBindCustomer
	 */
	private function getEmployeeBindCustomerObj()
	{
		if($this->employeBindObj === null)
		{
			$this->employeBindObj = EmployeeBindCustomer::model()
				->with("customer", "employee")
				->find(
					"employee_id=:employeeid",
					array(":employeeid"=>$this->employee_id));
		}
		return $this->employeBindObj;
		
	}
	
	private function currentEmployeeIsBindedCustomer()
	{
		$bindObj = $this->getEmployeeBindCustomerObj();
		return $bindObj != null && $bindObj->customer != null && $bindObj->state == 1;
	}

	/**
	 * 
	 * @return Customer
	 */
	private function getCustomerByMobile($mobile)
	{
		$customer = Customer::model()->find("mobile=:mobile", array(":mobile" => $mobile));
		return $customer;
	}
	
	/**
	 * 获取绑定的彩之云账户
	 * @return Customer
	 */
	private function getBindedCustomer()
	{
		return $this->currentEmployeeIsBindedCustomer() ? $this->getEmployeeBindCustomerObj()->customer : null;
	}
	

	
	protected function jsonReturn($arr)
    {
    	exit(json_encode($arr));
    }
	
	private function isMobileNumber($mobile)
	{
		return preg_match("/^1[3-9]\d{9}$/", $mobile);
	}

	public function actionLockBind()
	{
		$employee_id = $this->employee_id;
		$customer_id = Yii::app()->request->getParam("customer_id");
		$bind = EmployeeBindCustomer::model()->findByAttributes(array('employee_id'=>$employee_id , 'customer_id'=>$customer_id , 'state'=>1));
		if($bind)
		{
			$bind->state = 2;
			if($bind->update())
			{
				$arr = [
					'state' => 1,
					'message' => '解绑成功！'
				];
			}else {
				$arr = [
					'state' => 2,
					'message' => '解绑失败请稍后重试！'
				];
			}
		}else{
			$arr = [
				'state' => 2,
				'message' => '没有绑定信息'
			];
		}
		echo json_encode($arr);
	}

}