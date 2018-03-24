<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/7/12
 * Time: 10:16
 */
class HhNianApi
{
    //private $serverUrl = 'http://test.hhnian.com:6050/';//测试
    private $serverUrl = 'http://www.hehenian.com/';//正式

    private $partnerKey = 'DJKC#$%CD%des$';

    // 请求的参数
    public $queryData;
    // 请求的地址
    public $queryUrl;

    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }

    /**
     * 彩富订单投资交易协议接口
     * @param $sn
     * @param $customerId
     * @return bool|mixed
     */
    public function serviceAgreement($sn)
    {
        $para = array(
            'orderSN' => $sn,
        );

        $this->queryUrl = $this->serverUrl.'activity/colorlife/serviceAgreement';
        $this->queryData = $this->makeQueryData($para);

        $result =  Yii::app()->curl->get( $this->queryUrl, $this->queryData);
        return $this->resolveResult($result);
    }

    /**
     * 彩富订单投资交易协议下载地址
     * @param $sn
     * @param $customerId
     * @return bool|mixed
     */
    public function investAgreement($sn)
    {
        $para = array(
            'orderSN' => $sn,
            //'reqTime' => time(),
        );

        $this->queryUrl = $this->serverUrl.'activity/colorlife/investAgreement';
        $this->queryData = $this->makeQueryData($para);

        $result =  Yii::app()->curl->buildUrl( $this->queryUrl, $this->queryData);
        $this->logResult($result);
        return $result;
    }

    /**
     * 彩富订单提前退单状态
     * @param $sn
     * @param $customerId
     * @return bool|mixed
     *
     * 返回值
     *   statusCode  000
     *   statusDesc  可以提前退单！
     *
     *   statusCode  001
     *   statusDesc  在锁定期内，不允许提前退单！
     *
     *   statusCode  002
     *   statusDesc 无效参数或验证签名失败
     *
     */
    public function orderRedeemStatus($sn, $customerId)
    {
        $para = array(
            'orderSN' => $sn,
            'userId' => $customerId,
            'reqTime' => time(),
        );

        $this->queryUrl = $this->serverUrl.'activity/colorlife/ordRedeemStatus';
        $this->queryData = $this->makeQueryData($para);

        $result =  Yii::app()->curl->get( $this->queryUrl, $this->queryData);

        return $this->resolveResult($result);
    }

    /**
     * 彩富订单提前退单
     * @param $sn
     * @return bool|mixed
     */
    public function orderCancel($sn)
    {
        $para = array(
            'orderSN' => $sn,
            //'reqTime' => time(),
        );

        $this->queryUrl = $this->serverUrl.'activity/colorlife/orderCancel';
        $this->queryData = $this->makeQueryData($para);

        $result =  Yii::app()->curl->buildUrl( $this->queryUrl, $this->queryData);

        return $result;
    }

    /**
     * 处理请求结果
     * @param $result
     * @return bool|mixed
     */
    private function resolveResult($result)
    {
        // TODO 请求日志记录
        $this->logResult($result);

        if ( ! $result)
            return false;

        $result = json_decode($result, true);

        return $result;
    }

    /************** 签名方法 ***************/

    /**
     * 生成请求参数
     * @param $para
     * @return mixed
     */
    private function makeQueryData($para)
    {
        $para['sign'] = $this->makeSign($para);

        return $para;
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

        $signStr = $this->createLinkString($para).'&key='.$this->partnerKey;

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
            if($key == "sign" || $key == "sign_type" || $val === "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    /**
     * 日志记录
     * @param $word
     * @param string $word
     */
    private function logResult($word='') {

        $url = Yii::app()->curl->buildUrl($this->queryUrl, $this->queryData);

        $word = F::json_encode_ex($word);

        $logFile = dirname(__DIR__).'/../../log/czylog/colourlife/hhnRequest/'.date('Y-m-d').'.txt';

        $logDir = dirname(__DIR__).'/../../log/czylog/colourlife/hhnRequest';
        if ( ! is_dir($logDir))
            mkdir($logDir, 0777, true);

        $fp = fopen($logFile, "a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y-%m-%d %H:%M:%S",time())."\n 请求地址：".$url."\n 请求返回：".$word."\n\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

}
