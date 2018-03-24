<?php

class EntInfoApi
{
    const SMS_BASE_URL = 'http://sdk105.entinfo.cn:8060/webservice.asmx/';
    const SMS_MOBILE_COUNT = 5000;
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
     *  1    没有需要取得的数据    取用户回复就出现1的返回值,表示没有回复数据
     * -2    帐号/密码不正确    1.序列号未注册2.密码加密不正确3.密码已被修改
     * -4    余额不足    直接调用查询看是否余额为0或不足
     * -5    数据格式错误
     * -6    参数有误    看参数传的是否均正常,请调试程序查看各参数
     * -7    权限受限    该序列号是否已经开通了调用该方法的权限
     * -8    流量控制错误
     * -9    扩展码权限错误    该序列号是否已经开通了扩展子号的权限
     * -10    内容长度长    短信内容过长
     * -11    内部数据库错误
     * -12    序列号状态错误    序列号是否被禁用
     * -13    没有提交增值内容
     * -14    服务器写文件失败
     * -15    文件内容base64编码错误
     * -16    返回报告库参数错误
     * -17    没有权限    如发送彩信仅限于SDK3
     * -18    上次提交没有等待返回不能继续提交    默认不支持多线程
     * -19    禁止同时使用多个接口地址    每个序列号提交只能使用一个接口地址
     * -20    相同手机号，相同内容重复提交
     * -21    Ip鉴权失败    提交的IP不是所绑定的IP
     */
    public function send($mobile, $message,$isWait=true)
    {
        if (is_array($mobile)) {
            if (count($mobile) > self::SMS_MOBILE_COUNT)
                return -100;
            $mobile = implode(',', $mobile);
        }
        $params = array(
            'sn' => $this->corpId,
            'pwd' => strtoupper(md5($this->corpId . $this->passwd)),
            'mobile' => $mobile,
            'content' => @iconv('UTF-8', 'GB2312', $message . $this->padding),
            'ext' => '',
            'stime' => '',
            'rrid' => '',
        );
        $return = Yii::app()->curl->get(self::SMS_BASE_URL . 'mt', $params,$isWait);
        $data = is_bool($return)?$return:@simplexml_load_string($return);
        if ($data === false)
            throw new CException("解析三基时代发送短信返回出错。内容为：\"{$return}\"。");
        return (string)$data;
    }

}