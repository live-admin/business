<?php
/*企业短信发送
 *@time2015-06-1
 *@by wenda
 */
class BusinessSmsApi
{
    const SMS_BASE_URL = 'http://client.sms10000.com/api/webservice';
    const SMS_MOBILE_COUNT = 50;
    static protected $instance;
    private $eprId, $userId, $passwd;
    private $padding = '';

    static public function getInstanceWithConfig($config)
    {
        if (!isset(self::$instance))
            self::$instance = new self($config);
        return self::$instance;
    }

    public function __construct($config)
    {
        $this->eprId = @$config['eprId'];
        $this->userId = @$config['userId'];
        $this->passwd = @$config['passwd'];
    }

    /**
        1	成功(只表示调接口成功，不代表发送成功)
        0	失败
        -1	缺少参数
        -2	请求已经过期
        -3	Key验证失败
        -4	Ip验证失败
        -5	eprId不存在
        -6	userId不存在
        -7	定时日期格式不对
        -8	签名不对
    */
    public function send($mobile, $message,$isWait=true)
    {
        if (is_array($mobile)) {
            if (count($mobile) > self::SMS_MOBILE_COUNT)
                return 106;
            $mobile = implode(',', $mobile);
        }
        $time = round(microtime(true) * 1000);
        //企业Id+用户Id+用户密码+时间戳
        $key = md5("{$this->eprId}{$this->userId}{$this->passwd}{$time}"); 
        $params = array(
            'cmd' => 'send',
            'eprId' => $this->eprId,
            'userId' => $this->userId,
            'key' => $key,
            'timestamp' => "$time",
            'format' => 'json',
            'mobile' => $mobile,
            'msgid' => 1,
            'content' => $message.$this->padding,
        );
        $return = Yii::app()->curl->get(self::SMS_BASE_URL, $params, $isWait);
        $utf = mb_detect_encoding($return);
        $utf == 'UTF-8' || $return = iconv('GBK', 'UTF-8', $return);
        $re = json_decode($return);
        if (isset($re->result)) return $re->result;
        else return 1;
    }
}
