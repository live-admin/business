<?php
/**
 * 彩之云第三方支付订单生成接口调用
 * 使用方法： <br>
 * 1.在 api_api_auth 表中创建用户验证的 key(id) 和 secret(secret).  <br>
 * 2.在 third_fees_seller 创建收款的商家,id(cid)
 * 
 * @author dw
 *
 */

class ColourLifeThirdFeeProxy
{
    private $api_url = "";
    
    private $api_version ;
    private $api_time_diff;
    
    private $key;
    private $secret;
    
    public function __construct($key, $secret)
    {
        $this->api_url = "http://capi" . BASE_DOMAIN . "/1.0";
        
        $this->key = $key;
        $this->secret = $secret;
        
        $this->getApiVersion();
        $this->getApiTimeDiff();
    }
    
    /**
     * 验证授权是否成功。
     * 
     * @throws CHttpException
     */
    public function getAuth()
    {
        $url = $this->api_url."/auth";
        $query = array();
        $signUrl = $this->buildSignUrl($url, $query);
        
        $return = HttpClient::getHttpResponseGET($signUrl);
        $return = json_decode($return, true);
        if(isset($return['ok']) && $return['ok'] == "1")
        {
            //成功，暂时不需要处理。
        }
        else
        { 
            throw new CHttpException(403, "API授权失败");
        }
    }
    

    /**
     * 创建第三方订单
     * @param array $orderData( <br>
     *      'mobile' => '', //支付者手机号 <br>
     *      'cSn' => '订单号',  //内部订单号(商家单号)。整个业务系统唯一。 <br>
     *      'cid' => '商家cid',  //商家的id号 <br>
     *      'amount' => '10' , //支付金额 <br>
     *      'isRed' => '1', //是否使用红包. 1-使用，0-不使用 <br>
     *      'callbackUrl' => 'http://xxx.xxx.xxx/xx/xx', //回调地址 <br>
     * )
     * @return string 彩之云生成的订单号
     */
    public function createThridOrder($orderData)
    {
        $url = $this->api_url . "/thirdFees/getOrder";
        $query = array(
            'mobile' => $orderData['mobile'],
            'cSn' => $orderData['cSn'],
            'cid' => $orderData['cid'],
            'amount' => $orderData['amount'],
            'isRed' => $orderData['isRed'],
            'callbackUrl' => $orderData['callbackUrl'],
        );
        
        $signUrl = $this->buildSignUrl($url, $query);
        $return_json = HttpClient::getHttpResponseGET($signUrl);
        $return = json_decode($return_json, true);
        if(isset($return['sn']) && isset($return['status']) && $return['status'] == "ok")
        {
            return $return['sn'];
        }
        else
        {
            throw new CHttpException(400, "创建订单错误：" . $return_json);
        }
    }
    
    private function getApiTimeDiff()
    {
        $url = $this->api_url."/ts?ts=".time();
        $return = HttpClient::getHttpResponseGET($url);
        $return = json_decode($return, true);
        if(isset($return['ok']) && $return['ok'] == "1" && isset($return['diff']))
        {
            $this->api_time_diff = $return['diff'];
        }
        else
        { 
            throw new CHttpException(400, "访问API失败");
        }
    }
    
    private function getApiVersion()
    {
        $return = HttpClient::getHttpResponseGET($this->api_url);
        $return = json_decode($return, true);
        if(isset($return['ok']) && $return['ok'] == "1" && isset($return['version']))
        {
            $this->api_version = $return['version'];
        }
        else 
        {
            throw new CHttpException(400, "访问API失败");
        }
    }
    
    /**
     * 构造签名的URL地址
     * @param string $url 访问的绝对URL地址，不带get参数
     * @param array $query 查询的参数数组形式。 不用传入secret参数 
     * @return string 返回经过签名的URL地址
     */
    private function buildSignUrl($url, $query)
    {
        
        
        unset($query['secret']);
    
        $query = array_merge($query, array(
            'key' =>$this->key,
            'ts' => $this->getTimeStamp(),
            've' => $this->api_version . ".0",
        ));
        $p_url = parse_url($url);
        $urlForSign = $p_url['path'] . "?" . http_build_query(array_merge($query,array('secret' => $this->secret)));
        $sign = md5($urlForSign);
        $signUrl = $url . "?" . http_build_query(array_merge($query,array('sign' => $sign)));
        return $signUrl;
    }
    
    private function getTimeStamp()
    {
        return time() + $this->api_time_diff;
    }
    
    private function getVersion()
    {
        return $this->api_version . ".0";
    }
}