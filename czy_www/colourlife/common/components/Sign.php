<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/4/11
 * Time: 16:47
 */
class Sign {

    private $secret;

    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * 生成签名
     * @param array $para
     * @return string
     */
    public function makeSign(array $para)
    {
        $para = $this->paraFilter($para);
        $para = $this->argSort($para);
        $signStr = $this->createLinkString($para);
        $sign = md5($this->secret.md5($signStr).$this->secret);

        return strtoupper($sign);
    }

    /**
     * 校验签名
     * @param $para
     * @param $sign
     * @return bool
     */
    public function checkSign($para)
    {
        $sign = $para['sign'];
        $result = (strcasecmp($this->makeSign($para), $sign) === 0) ? true : false;

        return $result;
    }

    /**
     * 对数组排序
     * @param $para 排序前的数组
     * @return 排序后的数组
     */
    public function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * @return 拼接完成以后的字符串
     */
    public function createLinkString($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);

        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

        return $arg;
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * @return 去掉空值与签名参数后的新签名参数组
     */
    public function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $key == "sign_type" || $val === "" || $key == "v" || $key=='from_type')continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
}