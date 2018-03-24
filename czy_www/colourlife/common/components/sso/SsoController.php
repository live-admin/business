<?php

/**
 * 用于单点登录的控制器
 * @author dw
 *
 */
abstract class SsoController extends CController
{
	/**
	 * 职员ID（彩管家用户ID）
	 * @var int
	 */
	protected $employee_id;
	
	/**
	 * 获取当前控制授权是否启用兼容模式。
	 * @return true 执行兼容模式,自动识别SSO模式还是传统模式。 false 仅支持SSO模式
	 */
	protected abstract function isCompatibleMode();
	
	/**
	 * 获取Sso登录配置信息
	 * @return array( <br>
	 *  'app_id' => , //由单点登录平台提供的应用ID <br>
	 *  'token' => ,  //由单点登录平台提供的签名秘钥 <br>
	 * )
	 */
	protected abstract function getSsoConfig();
	
	/**
	 * 验证登录
	 */
	protected function checkLogin()
	{
		// 第二次及以后的访问处理 （也就是用户已经验证通过，以后的访问处理）
		if($this->isLoggedIn())
		{
			if($this->isTraditionalAccess())
			{
				$this->TraditionalLogin();
			}
			else 
			{
				$this->checkLoginForAfterFist();
			}
			return ;
		}
		
		
		// 第一次访问的授权
		if($this->isCompatibleMode())
		{
			if($this->isSsoAccess())
			{
				$this->SsoLogin();
			}
			else
			{
				$this->TraditionalLogin();
			}
		}
		else
		{
			$this->SsoLogin();
		}
		
	}
	
	/**
	 * 在第一次登陆之后的每次访问网站的验证登录
	 * @throws CHttpException
	 */
	private function checkLoginForAfterFist()
	{
		$employee_id = $this->getSessionEmployeeId();
		if(empty($employee_id))
		{
			throw new CHttpException(403, "未授权");
		}
		$emp = Employee::model()->findByPk($employee_id);
		if($emp == null)
		{
			throw new CHttpException(403, "未授权");
		}
		$this->setSessionEmployeeId($emp->id);
		$this->employee_id = $emp->id;
	}
	
	
	
	/**
	 * 判断是否已经登陆
	 */
	private function isLoggedIn()
	{
	    $sessionEmployeeId = $this->getSessionEmployeeId();
		return !empty($sessionEmployeeId);
	}

	private function setSessionEmployeeId($employee_id)
	{
		Yii::app()->session['COMMON_COMPONENTS_SSO_EMPLOYEE_ID'] = $employee_id;
	}
	
	private function getSessionEmployeeId()
	{
		return isset(Yii::app()->session['COMMON_COMPONENTS_SSO_EMPLOYEE_ID']) ? Yii::app()->session['COMMON_COMPONENTS_SSO_EMPLOYEE_ID'] : null;
	}

	/**
	 * 传统模式登录
	 */
	protected function TraditionalLogin()
	{
		if(empty($_REQUEST['employee_id']) && empty(Yii::app()->session['employee_id'])) {
			exit('<h1>员工信息错误，请重新登录</h1>');
		}else {
			$employeeId=0;
			ini_set('session.gc_maxlifetime', 3600*12); //设置时间
			if(isset($_REQUEST['employee_id'])){  //优先有参数的
				$employeeId=intval($_REQUEST['employee_id']);
				Yii::app()->session['employee_id']=$employeeId;
			}else if(isset(Yii::app()->session['employee_id'])){  //没有参数，从session中判断
				$employeeId=Yii::app()->session['employee_id'];
			}
			$employee=Employee::model()->findByPk($employeeId);
			if(empty($employeeId) || empty($employee)){
				exit('<h1>员工信息错误，请重新登录</h1>');
			}
			$this->employee_id = $employeeId;
			
			$this->setSessionEmployeeId( $employeeId );
		}
	}
	
	/**
	 * Sso模式登录
	 */
	protected function SsoLogin()
	{
		$this->checkSsoParm();
		
		$conf = $this->getSsoConfig();		
		if(!isset($conf['app_id']) || !isset($conf['token']))
		{
			throw new RuntimeException("SsoController的子类的getSsoConfig方法必须返回有效的数据。");
		}
		$openId = $_GET['openID'];
		$accessToken = $_GET['accessToken'];
		$sso = SsoFactory::createInstance($openId, $accessToken, $conf['app_id'], $conf['token']);
		
		$authReturn = $sso->RequestAuthenticate();
		
		if($authReturn['code'] != "0")
		{
			throw new CHttpException(403, "登录失败". $authReturn['message']);
		}
		
		$oa_username = $authReturn['content']['username'];
		
		$emp = Employee::model()->find("oa_username=:oa_username", array(":oa_username" => $oa_username));
		if($emp  === null)
		{
			throw new CHttpException(403, "不存在指定的用户");
		}
		
		$this->setSessionEmployeeId($emp->id);
		$this->employee_id = $emp->id;
	}
	
	private function checkSsoParm()
	{
		if(!isset($_GET['openID']))
		{
			throw new CHttpException("403", "未授权");
		}
		if(!isset($_GET['accessToken']))
		{
			throw new CHttpException("403", "未授权");
		}
	}
	
	/**
	 * 是否是SSO模式访问
	 * 仅仅根据传入的URL参数检查
	 * @return boolean
	 */
	private function isSsoAccess()
	{
		return isset($_GET['openID']) && isset($_GET['accessToken']);
	}
	
	/**
	 * 是否是传统模式访问。
	 * 仅仅根据传入的URL参数检查
	 * @return boolean
	 */
	private function isTraditionalAccess()
	{
		return isset($_GET['employee_id']);
	}
}












