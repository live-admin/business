<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	public $model;
	public $akaMobile = false;

	private $_id;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		if ($this->model == 'ICEEmployee') {
			$user = CActiveRecord::model($this->model)->getByUsername($this->username, $this->password, $this->akaMobile);
			if ($user === null)
				$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
			else
				$this->login();
		} else {
			$user = CActiveRecord::model($this->model)->getByUsername($this->username, $this->akaMobile);
			if ($user === null)
				$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
			else if (!$user->validatePassword($this->password, $this->username))
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			else
				$this->login();
		}

		return $this->errorCode == self::ERROR_NONE;
	}

	/**
	 * 第三方auth 验证登录
	 * @return bool
	 */
	public function authenticateThirdAuth()
	{
		$thirdAuth = CustomerThirdAuth::model()->find('open_code=:open_code', array(':open_code' => $this->username));
		if (!$thirdAuth)
			return $this->errorCode == self::ERROR_NONE;

		$user = Customer::model()->findByPk($thirdAuth->customer_id);
		if ($user === null)
			$this->errorCode = self::ERROR_UNKNOWN_IDENTITY;

		$this->username = $user->mobile;
		$this->akaMobile = true;

		$this->login();

		return $this->errorCode == self::ERROR_NONE;
	}


	/**
	 * @return bool
	 * 验证cpmobile登录
	 */
//    public function authenticateEmployee()
//    {
//        $user = CActiveRecord::model($this->model)->getByUsername($this->username, $this->akaMobile);
//        if ($user === null)
//            $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
//        else if (
//            (!$user->validatePasswordByOa($this->password, $this->username))
//            && (!$user->validatePasswordByAD($this->username,$this->password)))
//            $this->errorCode = self::ERROR_PASSWORD_INVALID;
//        else
//            $this->login();
//        return $this->errorCode == self::ERROR_NONE;
//    }

	public function authenticateNew()   //for CPA
	{
		$user = CActiveRecord::model($this->model)->getByUsername($this->username, $this->akaMobile);
		if ($user === null)
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else if ((!$user->validatePassword($this->password))
			&& (!$user->validatePasswordByOa($this->password, $this->username))
		)
			//&& (!$user->validatePasswordByAD($this->username,$this->password)))
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else if ($user->remark == 0)
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else
			$this->login();
		return $this->errorCode == self::ERROR_NONE;
	}

	public function authenticateEnd()   //for backend
	{
		if ($this->model == 'ICEEmployee') {
			$user = CActiveRecord::model($this->model)->getByUsername(
				$this->username,
				$this->password,
				$this->akaMobile
			);
		} else {
			$user = CActiveRecord::model($this->model)->getByUsername($this->username, $this->akaMobile);
			if ($user === null) {
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			} else if ((!$user->validatePassword($this->password, $this->username))
				&& (!$user->validatePasswordByOa($this->password, $this->username))
			) {
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			} else if ($user->remark != 0) {
				$this->errorCode = self::ERROR_PASSWORD_INVALID;
			}
		}

		$this->login();

		return $this->errorCode == self::ERROR_NONE;
	}


	public function login()
	{
		if ($this->model == 'ICEEmployee') {
			$user = CActiveRecord::model($this->model)->getByUsername(
				$this->username,
				$this->password,
				$this->akaMobile
			);
		} else {
			$user = CActiveRecord::model($this->model)->getByUsername($this->username, $this->akaMobile);
		}

		$this->_id = $user->id;

		CActiveRecord::model($this->model)->updateByPk(
			$this->_id,
			array(
				'last_time' => time(),
				'last_ip' => Yii::app()->getRequest()->getUserHostAddress(),
			)
		);

		$this->username = $user->username;
		$this->setState('model', strtolower($this->model));
		$this->setState('id', $user->id);
		$this->setState('username', $user->username);
		if ($this->akaMobile) {
			$this->setState('mobile', $user->mobile);
		}

		$this->errorCode = self::ERROR_NONE;


//		if ($this->model == 'Customer') {
			//
			//start===12月幸福中国行，抽奖活动，登录增加抽奖机会
			//活动结束后可注释可删除
			//$luckyOper = new LuckyOperation();
			//$luckyOper->custGetLuckyNum($user->username, $user->id, false, 1);

			// TODO 不造为嘛连续执行两次
			//TODO 2017-10-17没鸟用的活动留着干嘛
			//$luckyOper = new LuckyOperation();
			//$luckyOper->custGetLuckyNum($user->username, $user->id, false, 1);
			//
			//end ====12月幸福中国行，抽奖活动，登录增加抽奖机会


			//彩之云app活动
			//$luckyOper->execute($paramin);
			//LuckyDoAdd::login($user->id, $user->username);

			//业主登录赠送积分
			//$user->changeCredit('customer_login');

			//用户登录后将session里的购物车添加至数据库
//			try {
				//获得session的数据
//				$cart = new SessionCart();
//				$cartList = $cart->contentsAll();
//				//转存到数据库内
//				$dbCart = new DbCart();
//				$dbCart->insert($cartList, $this->_id);
//				$cart->destroy();
//			} catch (Exception $e) {
				//转存出错
				//Yii::log("登录将Session购物车转存到数据库失败！", CLogger::LEVEL_INFO, 'colourlife.core.components.UserIdentity');
//			}
//		}
	}

	/**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}
}
