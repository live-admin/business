<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/4/27
 * Time: 17:40
 */

require 'AlipaySign.php';
require 'HttpRequst.php';

class Order
{
    protected $config;

    protected $sign;

    /**
     * HTTPS形式消息验证地址
     */
    protected $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    /**
     * HTTP形式消息验证地址
     */
    protected $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';

    /**
     *支付宝网关地址（新）
     */
    var $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';

    public function __construct() {
        $this->config = require(dirname ( __FILE__ ).'/payConfig.php');
        $this->sign = new AlipaySign();
    }

    public function buildRequestUrl($params)
    {
        //待请求参数数组
        $params = $this->buildRequestPara($params);

        $requestUrl = $this->alipay_gateway_new.$this->sign->createLinkstringUrlencode($params);//."&_input_charset=".trim(strtolower($this->config['input_charset']));

        return $requestUrl;
    }


    /**
     * 生成要请求给支付宝的参数数组
     * @param $params 请求前的参数数组
     * @return 要请求的参数数组
     */
    function buildRequestPara($params) {

        $params = array_merge($params, $this->getPayParams());

        $rsaPrivateKeyFilePath = $this->config['private_key_path'];
        //生成签名结果
        $signStr = $this->sign->sign_request($params, $rsaPrivateKeyFilePath);

        //签名结果与签名方式加入请求提交参数组中
        $params['sign'] = $signStr;
        $params['sign_type'] = strtoupper(trim($this->config['sign_type']));

        return $params;
    }

    public function getPayParams()
    {
        return $params = array(
            'service' => $this->config['service'],
            'partner' => $this->config['partner'],
            'seller_id' => $this->config['seller_id'],
            'payment_type' => $this->config['payment_type'],
            '_input_charset' => $this->config['_input_charset'],
        );
    }

    public function verifySign($params, $type)
    {
        $sign = $params['sign'];

        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->sign->paraFilter($params);

        //对待签名参数数组排序
        $para_sort = $this->sign->argSort($para_filter);

        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $para_str = $this->sign->createLinkstring($para_sort);

        $isSign =  $this->sign->rsa_verify($para_str, $sign, $this->config['alipay_public_key_path']);

        $responseTxt = 'false';
        if (! empty($params["notify_id"])) {
            $responseTxt = $this->getResponse($params["notify_id"]);
        }

        //写日志记录
        $isSignStr = 'false';
        if ($isSign)
            $isSignStr = 'true';
        $log_text = "responseTxt=".$responseTxt."\n ".$type." : isSign=".$isSignStr.",";
        $log_text = $log_text.$this->sign->createLinkString($params);
        $this->logResult($log_text);

        //验证
        //$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
        //isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
        if (preg_match("/true$/i",$responseTxt) && $isSign) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
    function getResponse($notify_id) {
        $transport = strtolower(trim($this->config['transport']));
        $partner = trim($this->config['partner']);
        $veryfy_url = '';
        if($transport == 'https') {
            $veryfy_url = $this->https_verify_url;
        }
        else {
            $veryfy_url = $this->http_verify_url;
        }
        $veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;

        $responseTxt = HttpRequest::getHttpResponseGET($veryfy_url, $this->config['cacert']);

        return $responseTxt;
    }

    /**
     * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
     * 注意：服务器需要开通fopen配置
     * @param $word 要写入日志里的文本内容 默认值：空值
     */
    public function logResult($word='') {
        $fp = fopen(Yii::app()->getRuntimePath()."/alipay/log.txt", "a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }
}