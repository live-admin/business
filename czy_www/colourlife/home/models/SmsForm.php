<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class SmsForm extends CFormModel
{
    public $mobile;
    public $code;
    public $token;
    public $type;
    protected $sms;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('mobile', 'required'),
            array('mobile', 'common.components.validators.ChinaMobileValidator'),
            array('mobile', 'checkCanSend', 'on' => 'register, resetPassword, setPayPassword, verifyMobile'),
            array('mobile', 'common.components.validators.RegChangePassworSms','on' => 'register, resetPassword, setPayPassword, verifyMobile' ),
            array('code', 'checkCode', 'on' => 'check'),
            array('type', 'safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'mobile' => '手机号码',
        );
    }

    protected function beforeValidate()
    {
        $this->sms = Yii::app()->sms;
        switch ($this->scenario) {
            case 'check':
                $this->sms->setType('verifyCode', array('mobile' => $this->mobile, 'code' => $this->code));
                break;
            default:
                $this->sms->setType($this->scenario, array('mobile' => $this->mobile));
                break;
        }
        return parent::beforeValidate();
    }

    /**
     *判断手机号不存在，存在则不能注册
     */
    public function checkCanSend($attribute, $params)
    {
        if (!$this->sms->getCanSend()) {
            $this->addError($attribute, $this->sms->error);
        }
    }

    public function send()
    {
        if (!$this->sms->sendUserMessage($this->getSmsTemplate())) {
            $this->addError('mobile', $this->sms->error);
            return false;
        }
        $this->code = $this->sms->code;
        return true;
    }

    public function getSmsTemplate(){
        if($this->scenario=='resetPassword'){
            return 'resetPasswordTemplate';
        }elseif ($this->scenario=='register') {
            return 'registerTemplate';
        }elseif ($this->scenario=='setPayPassword') {
            return 'setPayPasswordTemplate';
        }elseif ($this->scenario=='resetPayPassword') {
            return 'resetPayPasswordTemplate';
        }elseif ($this->scenario=='verifyMobile') {
            return 'verifyMobile';
        }else {
            return 'registerTemplate';
        }
    }


    public function checkCode($attribute, $params)
    {
        if (!$this->sms->getCodeIsCorrect()) {
            $this->addError($attribute, $this->sms->error);
        }
    }

    public function useCode()
    {
        $this->sms->useCode();
        $this->token = $this->sms->token;
    }

    public function BlackValidate(){
        $num = Item::SMS_LIMIT_VALIDATE;
        $blacklist = new BlackValidate();
        $currentTime = time();
        //查询手机号
        $result = $blacklist->model()->findByAttributes(array("mobile"=>$this->mobile));
        //新增
        if (empty($result)) {
            $count = 1;
            $blacklist->mobile = $this->mobile;
            $blacklist->address = Yii::app()->request->userHostAddress;
            $blacklist->valinum = 1;
            $blacklist->user_agent = Yii::app()->request->userAgent;
            $blacklist->create_time = $currentTime;
            $blacklist->save();
        } else {
            $count = $result->valinum+1;
            //小于50次退出
            if ($count <= $num){
                $result->valinum = $count;
                $result->update();
            }
        }
        return $count;
    }

    //统计次数
    public function GetBlackValidateNum(){
        $blacklist = new BlackValidate();
        $result = $blacklist->model()->findByAttributes(array("mobile"=>$this->mobile));
        if ($result) {
            $count = $result->valinum;
        } else $count = 0;
        return $count;
    }

}
