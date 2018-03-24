<?php

class KltxApi
{
    const SMS_BASE_URL = 'http://c.kf10000.com/sdk/SMS';
    const SMS_MOBILE_COUNT = 50;
    static protected $instance;
    private $corpId, $loginName, $passwd;
    private $padding = '【彩生活】';

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
     * >100   成功
     *  101   失败
     *  102   验证失败(密码不对)
     *  103   号码有错(接收号码格式错误)
     *  104   内容有错(敏感内容)
     *  105   操作频率过快(每秒十次)
     *  106   限制发送(无条数)
     *  107   参数不全(请查看提交的参数)
     */
    public function send($mobile, $message,$isWait=true)
    {
        if (is_array($mobile)) {
            if (count($mobile) > self::SMS_MOBILE_COUNT)
                return 106;
            $mobile = implode(',', $mobile);
        }
        $params = array(
            'cmd' => 'send',
            'uid' => $this->corpId,
            'psw' => md5($this->passwd),
            'mobiles' => $mobile,
            'msgid' => '',
            'msg' => @iconv('UTF-8', 'GB2312', $message.$this->padding),
        );
        $return = Yii::app()->curl->get(self::SMS_BASE_URL, $params, $isWait);
        return @iconv('GB2312', 'UTF-8', $return);
    }

}
