<?php
/**
 * E租房接口类.
 * User: Joy
 * Date: 2016/2/25
 * Time: 9:28
 */
class ZoFoonApi {

    // 接口调用路径
    private $serverUrl = 'http://api.zofoon.com/';

    // 接口加密私钥字符串
    private $appSecret = 'SDFL#)@F';


    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }

    public function ExCouponAct($username, $code)
    {

        $method = 'Ex.coupon.act';

        $params = array(
            'username' => $username,
            'code' => $code
        );

        $queryData = $this->makeQueryData($method, $params);

        $result =  Yii::app()->curl->get($this->serverUrl, $queryData);

        return json_decode($this->decrypt($result), true);
    }

    /**
     * 请求参数
     * @param $method
     * @param null $params
     * @return array|null
     */
    private function makeQueryData($method, $params=null)
    {
        $queryData['Method'] = $method;

        if (!empty($params)) {
            $queryData['Params'] = $this->encrypt($params);
        }

        $queryData['Sign'] = $this->makeSign($queryData);

        return $queryData;
    }

    /**
     * 生成签名
     * @param array $para
     * @return string
     */
    private function makeSign(array $para)
    {
        $para = $this->paraFilter($para);
        $para = $this->argSort($para);

        $signStr = $this->appSecret.$this->createLinkstring($para).$this->appSecret;

        return strtoupper(md5($signStr));
    }

    /**********功能方法***********/
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
     * 把数组所有元素，按照“参数+参数值”的模式拼接成字符串
     * @param $para 需要拼接的数组
     * @return 拼接完成以后的字符串
     */
    public function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key.$val;
        }

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
            if($key == "sign" || $key == "sign_type" || $val == "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    //加密
    protected function encrypt($data)
    {
        $str = json_encode($data);
        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);

        $pad = $size - (strlen($str) % $size);
        $str .= str_repeat(chr($pad), $pad);

        $cipher = mcrypt_module_open(MCRYPT_DES, '', 'cbc', '');
        mcrypt_generic_init($cipher, $this->appSecret, $this->appSecret);
        $data = mcrypt_generic($cipher, $str); //$data = mcrypt_cbc(MCRYPT_DES, $this->appSecret, $str, MCRYPT_ENCRYPT, $this->appSecret);
        mcrypt_generic_deinit($cipher);
        return strtoupper(bin2hex($data));
    }

    // 5.4 以下版本没有 hex2bin
    protected function hex2bin($data)
    {
        $len = strlen($data);
        return pack('H' . $len, $data);
    }

    //解密
    protected function decrypt($str)
    {
        $str = $this->hex2bin($str);
        $cipher = mcrypt_module_open(MCRYPT_DES, '', 'cbc', '');
        mcrypt_generic_init($cipher, $this->appSecret, $this->appSecret);
        $str = mdecrypt_generic($cipher, $str); //$str = mcrypt_cbc(MCRYPT_DES, $this->appSecret, $str, MCRYPT_DECRYPT, $this->appSecret);
        mcrypt_generic_deinit($cipher);
        $pad = ord($str{strlen($str) - 1});
        if ($pad > strlen($str))
            return false;
        if (strspn($str, chr($pad), strlen($str) - $pad) != $pad)
            return false;
        return urldecode(substr($str, 0, -1 * $pad));
    }

}
