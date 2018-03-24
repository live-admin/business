<?php

class FriendForm extends CFormModel
{
    public $mobile;
    public $msg;
    public $note;


    public function rules()
    {
        return array(
            array('mobile', 'required'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'mobile' => '手机号码',
            'msg' => '信息',
            'note' => '备注',
        );
    }


    /**
     * 增加积分
     **/
    private function PlusCredit($mobile, $customer)
    {
        $customer->changeCredit('customer_register'); //注册加积分

        $invite = Invite::model()->find('mobile=:mobile and  create_time<=:create_time  and valid_time>=:valid_time', array(':mobile' => $mobile,
            ':create_time' => time(), ':valid_time' => time()));

        if (isset($invite)) {
            $customer = Customer::model()->findByPk($invite->customer_id);
            $customer->changeCredit('invite'); //邀请人加积分

        }
    }

    /**
     * 邀请好友
     * @param string $mobile 手机号码
     */
    public function inviteFriend()
    {
        $customer = Customer::model()->find('mobile=:mobile', array(':mobile' => $this->mobile));

        if (!isset($customer)) {
            $invite = Invite::model()->find('mobile=:mobile  and create_time<=:create_time  and valid_time>=:valid_time', array(':mobile' => $this->mobile,
                ':create_time' => time(), ':valid_time' => time()));
            if (isset($invite) && $invite->customer_id!=Yii::app()->user->id) {                
                $this->addError("mobile", '您邀请的好友已经被其他人邀请了，暂时不能邀请。');
                return false;
            } else {
                $custModel = Customer::model()->findByPk(Yii::app()->user->id);                
                if(!isset($invite)){
                    $sms = Yii::app()->sms;                    
                    $sms->name = $custModel->name?$custModel->name:$custModel->mobile;
                    if (empty($this->msg)) {
//                        $sms->setType('invite', array('mobile' => $this->mobile));
//                        $sms->sendUserMessage('inviteTemplate');
                        $this->sendSms($this->mobile , '');
                    } else {
//                        $sms->mobile = $this->mobile;
//                        $sms->sendMsg($this->msg);
                        $this->sendSms($this->mobile , '');
                    }
                    
                    $model = Invite::model()->find('mobile=:mobile and model = "customer" and customer_id=:customer_id',
                            array(':mobile' => $this->mobile, ':customer_id' => Yii::app()->user->id));
                    if(!$model){
                        $model = new Invite();
                    }
                    $model->customer_id = Yii::app()->user->id;
                    $model->mobile = $this->mobile;
                    $model->create_time = time();
                    $model->valid_time = time() + intval(Yii::app()->config->invite['validTime']);
                    if (!$model->save()) {
                        $this->addError("mobile", $this->errorSummary($model));
                        return false;
                    }                    
                }else{
                    if($invite->create_time <= (time() - 120)){
                        $invite->create_time = time();
                        $invite->save();
                        $sms = Yii::app()->sms;
                        $sms->name = $custModel->name?$custModel->name:$custModel->mobile;
                        if (empty($this->msg)) {
//                            $sms->setType('invite', array('mobile' => $this->mobile));
//                            $sms->sendUserMessage('inviteTemplate');
                            $this->sendSms($this->mobile , '');
                        } else {
//                            $sms->mobile = $this->mobile;
//                            $sms->sendMsg($this->msg);
                            $this->sendSms($this->mobile , '');
                        }
                        return true;
                    }else{
                        $this->addError("mobile", '您已经邀请过好友了，如果您的好友未收到短信，请2分钟后重试或邀请其他好友注册拿红包。');
                        return false;
                    }
               }
               return true;
            }
        } else {
            $this->addError("mobile", '很抱歉，您的好友已经注册了，您可以邀请其他好友注册拿红包。');
            return false;
        }
    }

    /**
     * 添加好友
     * @param string $mobile
     * @param string $msg
     * @return
     */
    public function addFriend()
    {
        if (empty($this->mobile)) {
            $this->addError("mobile", '手机号码不能为空');
            return false;
        }

        $customer = Customer::model()->enabled()->find('mobile=:mobile', array(':mobile' => $this->mobile));

        //var_dump($this->mobile);exit;
        if ($customer->id == Yii::app()->user->id) {
            $this->addError("mobile", '不能加自己为好友');
            return false;
        }
        if (!empty($customer)) {
            $f = Friend::model()->find('friend_id=:friend_id and customer_id=:customer_id'
                , array(':friend_id' => $customer->id, ':customer_id' => Yii::app()->user->id));
            if (!isset($f)) {
                $model = new Friend();
                $model->friend_id = $customer->id;
                $model->note = $this->note;
                $model->customer_id = Yii::app()->user->id;
                $model->status = 0;

                $friend = new Friend();
                $friend->friend_id = Yii::app()->user->id;
                $friend->note = '';
                $friend->customer_id = $customer->id;
                $friend->status = 0;

                if (!$model->save() || !$friend->save()) {
                    $this->addError("mobile", '添加好友出错');
                    return false;
                }

            } else {
                $this->addError("mobile", '好友已存在');
                return false;
            }
        } else {
            if (empty($msg)) {
                $this->addError("mobile", '短信内容不能为空');
                return false;
            }
            $this->inviteFriend();
        }
        return true;
    }

    /***
     * 物管 邀请 注册
     *
     */
    public function employeeInvite()
    {
        $customer = Customer::model()->find('mobile=:mobile', array(':mobile' => $this->mobile));

        if (empty($customer)) {
            $invite = Invite::model()->find('mobile=:mobile  and  create_time<=:create_time  and valid_time>=:valid_time',
                array(':mobile' => $this->mobile, ':create_time' => time(), ':valid_time' => time()));
            if (empty($invite)) {
                $sms = Yii::app()->sms;
                $employee = Employee::model()->findByPk(Yii::app()->user->id);

                if (empty($this->msg)) {

                    $sms->setType('employee', array('mobile' => $this->mobile, 'token' => $employee->name));
                    //$sms->sendMsgs($mobile,'employee');
                    $sms->send();
                }
                $model = new Invite();
                $model->customer_id = Yii::app()->user->id;
                $model->mobile = $this->mobile;
                $model->model = "employee";
                $model->valid_time = time() + intval(Yii::app()->config->invite['validEmployeeTime']);

                return true;
            } else {
                $this->addError("mobile", "已邀请，不能重复邀请");
                return false;
            }
        } else {
            $this->addError("mobile", "已被注册，不能再被邀请");
            return false;
        }
    }

    /***
     *营销短信
     */
    public function sendSms($mobile ,$message , $title = '彩之云')
    {
        if(empty($message)){}
        {
            $message = '您的好友'.$mobile.'邀请您注册彩之云，投资彩之云E理财，坐享年化10%收益。下载http://dwz.cn/8YPIv';
        }
        try {
            // 部分微服务没有版本（如 v1)
            Yii::import('common.components.GetTokenService');
            $service = new GetTokenService();
            $token = $service->getAccessTokenFromPrivilegeMicroService();
            ICEService::getInstance()->dispatch(
                'ztyy/voice/sendSMS',
                array(),
                array(
                    'access_token' => $token,
                    'to' => $mobile,
                    'content' => stripos($message, $title) !== false
                        ? $message
                        : ($title . $message),
                    'channelID' => '8'
                ),
                'POST'
            );
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
        }
    }

}
