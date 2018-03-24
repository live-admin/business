<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CustomerLoginForm extends CFormModel
{
    public $username;
    public $password;
    public $verifyCode;
    public $rememberMe;

    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('username', 'checkActivate', 'on' => 'login'),
            // username and password are required
            array('username, password', 'required'),
            // password needs to be authenticated
            array('password', 'authenticate'),

            array('rememberMe', 'boolean'),
            // verifyCode needs to be entered correctly
            array('verifyCode', 'captcha', 'on' => 'login', 'allowEmpty' => !CCaptcha::checkRequirements()),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'username' => '用户名',
            'password' => '密码',
            'verifyCode' => '验证码',
            'rememberMe'=>' 记住密码',
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_identity = $this->_getIdentity();
            if (!$this->_identity->authenticate()) {
                $this->addError('password', '手机号码、账号或密码错误');
                return false;
            }
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if ($this->_identity === null) {
            $this->_identity = $this->_getIdentity();
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            UserLoginLog::model()->createLoginLog(Yii::app()->user->mobile);
            return true;
        } else {
            return false;
        }
    }

    private function _getIdentity()
    {
        $identity = new UserIdentity($this->username, $this->password);
        $identity->model = 'Customer';
        $identity->akaMobile = true;
        return $identity;
    }
    public function checkActivate($attribute, $params)
    {
//        $data = Customer::model()->enabled()->find('LOWER(username)=:username', array(':username' => strtolower($this->username)));
		$data = Customer::model()->enabled()->find('username=:username', array(':username' => $this->username));
        if (!$this->hasErrors() && !empty($data) && ($data->status==Item::USER_WAIT_ACTIVATE)) {
            //记录要激活的session id
            Yii::app()->session['activate_userId'] = $data->id;
            $this->addError($attribute, '平台升级，老用户请先<a href="'.F::getPassportUrl("/site/activate").'">激活</a>');
            return false;
        }
    }
}
