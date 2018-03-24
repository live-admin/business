<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class CustomerForm extends CustomerParentForm
{
    public $username;
    public $password;
    public $name;
    public $token;
    public $mobile;
    public $build_id;
    public $room;
    public $community_id;
    public $nickname;
    public $email;
    public $code;
    public $repeatPwd;
    public $reg_type;
    public $reg_identity;


    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            // username and password are required
            array('username', 'checkActivate', 'on' => 'create'),
            array("username","checkUserName",'on'=>'create'),
            array('username', 'length', 'min'=>3),
            array('password', 'length', 'min'=>6),
            array('username,password,room,code,mobile,repeatPwd,name', 'required','on'=>'create'),
            array('mobile', 'common.components.validators.ChinaMobileValidator','on'=>'create,reset'),
            array('repeatPwd', 'compare', 'compareAttribute' => 'password', 'operator' => '=', 'message' => '两次密码不一样','on'=>'create,update,edit'),
            array('build_id,room,community_id,nickname,email,token,repeatPwd,name,mobile', 'safe'),
            array('mobile','required','on'=>'reset'),
            array('password,code,repeatPwd', 'required','on'=>'update,edit'),
        	array('name','checkName','on'=>'create,edit'),
            array('name','required', 'on' => 'edit'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'community_id' => '所属小区',
            'build_id' => '所属楼栋',
            'room' => '门牌号',
            'username' => '账号',
            'password' => '密码',
            'name' => '姓名',
            'mobile' => '手机号码',
            'repeatPwd'=>'重复密码',
            'code'=>'验证码',
        );
    }

    public function saveData()
    {
        $model = Customer::model()->find('username=:username and is_deleted=:ise_deleted', array(':username' => $this->username, ':ise_deleted' => Item::DELETE_ON));
        if (isset($model)) {
            return $this->username . '已被使用。';
        }
        //check token
        $selfCode=$this->getTokenByself();
        if (!empty($selfCode)) {
            if ($selfCode['ok'] == 0) {
                return $selfCode['result'];
            }
            $sms = Yii::app()->sms;
            $sms->setType('verifyToken', array('mobile' => $this->mobile, 'token' => $selfCode['result']));
            if (!$sms->getTokenIsCorrect())
                return $sms->error;
            $sms->useToken();
        } else {
            return '验证码有误';
        }

        $model = new Customer();
        
        $model->attributes=$this->attributes;
        
        $model->password=$this->password;
        
        $result = $model->save();
        
        if ($result) {
            //注册送抽奖次数
            LuckyDoAdd::finishInfo($model->id, $model->username);
            $this->PlusCredit($this->mobile, $model);
            $identity = new UserIdentity($this->username, $this->password);
            $identity->model = 'Customer';
            $identity->akaMobile = true;
            $identity->login();
            Yii::app()->user->login($identity, 0);
        } else {
            $result = '内部错误';
        }
        return $result;
    }

    public function updateData()
    {
        $model = Customer::model()->findByPk(Yii::app()->user->getId());

        if (!empty($this->username)) {
            $model->username = $this->username;
        }
        if (!empty($this->name)) {
            $model->name = $this->name;
        }
        $model->nickname = $this->nickname;
        $model->email = $this->email;
        if ($this->checkExists()) {
            throw new CHttpException(400, '用户名已存在!');
        } else {
            if (!$model->save()) {
                throw new CHttpException(400, '数据错误');
            }
        }
    }

    /**
     *检查修改的username是否存在
     */
    public function  checkExists()
    {
        $model = Customer::model()->find('username=:username and id!=:id', array(':username' => $this->username, ':id' => Yii::app()->user->getId()));
        if (isset($model)) {
            return true;
        } else
            return false;
    }

    /**
     * 重置密码
     */
    public function resetPassword()
    {
        $model = Customer::model()->enabled()->findByPK(Yii::app()->user->id);//
        if (empty($model)) {
            return '用户不存在或者被禁用';
        }
        $selfCode = $this->getTokenByself();
        if (!empty($selfCode)) {
            if ($selfCode['ok'] == 0) {
                return $selfCode['result'];
            }
            $sms = Yii::app()->sms;
            $sms->setType('verifyToken', array('mobile' => $model->mobile, 'token' => $selfCode['result']));
            if (!$sms->getTokenIsCorrect())
                return $sms->error;
            $sms->useToken();
            $model->password = $this->password;
            return $model->save();
        } else {
            return '验证码有误';
        }
    }



    private function  getTokenByself()
    {
        $model = new SmsForm;
        $model->setScenario('check');
        $model->mobile = $this->mobile;
        $model->code = $this->code;

        if ($model->validate()) {
            $model->useCode();
            Yii::log("验证手机号码 '{$model->mobile}' 的验证码 '{$model->code}' 成功。", CLogger::LEVEL_INFO, 'colourlife.api.sms.PUT');
            return array('ok'=>1,
                'result'=>$model->token);
        } else {
            return array('ok'=>0,
                'result'=>$model->getError('code'));
        }

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

    //验证用户名
    public function checkUserName($attribute,$params){
        if(preg_match("/[\W]/i",$this->username)){
            $this->addError($attribute,"用户名只能字母、数字、下划线组成");
        }
    }
    
    public function checkName($attribute, $params){
    	if(!preg_match("/^[0-9a-zA-Z_\x{4e00}-\x{9fa5}]+$/u",$this->name)){
    		$this->addError($attribute,"真实姓名只能中文、字母、数字、下划线组成");
    	}
    }

}
