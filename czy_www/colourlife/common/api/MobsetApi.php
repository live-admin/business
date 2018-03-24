<?php

class MobsetApi
{
    const SMS_BASE_URL = 'http://web.mobset.com/SDK/';
    const SMS_MOBILE_COUNT = 50;
    static protected $instance;
    private $corpId, $loginName, $passwd;

    static public function getInstanceWithConfig($config)
    {
        if (!isset(self::$instance))
            self::$instance = new self($config);
        return self::$instance;
    }

    public function __construct($config)
    {
        $this->corpId = @$config['corpId'];
        $this->loginName = @$config['loginName'];
        $this->passwd = @$config['passwd'];
    }

    /**
     * >0 发送成功，返回发送的数量，并且返回SmsID,如果有多条短信，按顺序返回。可以通过Sms_Status根据SmsID取短信状态。由于网络原因，返回的时间可能会较长，在调用时，建议控制超时值应大于30秒。
     * -1 输入参数不完整
     * -2 非法来源IP地址或帐号密码有误
     * -3 目标号码有误
     * -4 企业帐号余额不足，后跟已发送的数量及SmsID
     * -5 用户帐号余额不足，后跟已发送的数量及SmsID
     * -6 输入参数不完整
     * -7 连接数据库失败
     * -8 企业帐号已被禁用
     * -9 短信内容含有过滤关键字
     */
    public function send($mobile, $message,$isWait=true)
    {
        if (is_array($mobile)) {
            if (count($mobile) > self::SMS_MOBILE_COUNT)
                return -10;
            $mobile = implode(';', $mobile);
        }
        $params = array(
            'CorpId' => $this->corpId,
            'LoginName' => $this->loginName,
            'send_no' => $mobile,
            'msg' => @iconv('UTF-8', 'GB2312', $message),
        );
        if (!empty($this->passwd))
            $params['Passwd'] = $this->passwd;
        $return = Yii::app()->curl->get(self::SMS_BASE_URL . 'Sms_Send.asp', $params,$isWait);
        return @iconv('GB2312', 'UTF-8', $return);
    }

}
