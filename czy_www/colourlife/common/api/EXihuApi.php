<?php
/**
 * E洗护接口
 * User: gongzhiling
 * Date: 2017/3/6
 * Time: 11:37
 */
class EXihuApi
{
    private $serverUrl;
    private $app_id = "56DAC3EA75A6DA58C0EE2024FA23133D";
    private $fr = 'caizhiyun';
    private $zh = 'czy';

    private $queryUrl;
    protected static $instance;
    
    public function __construct()
    {
    	if (defined('YII_DEBUG') && YII_DEBUG == true) {
    		$this->serverUrl = 'http://59.41.220.171:802/';
    	} else {
    		$this->serverUrl = 'http://59.41.220.171:802/';
    	}
    }
    public static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }


    /**
     * 通知e洗护抽到优惠券
     * @param unknown $couponID
     * @param unknown $userID
     * @param unknown $mobile
     * @return Ambigous <boolean, mixed>
     */
    public function notifyEXihu($couponID,$userID,$mobile)
    {
    	Yii::log('调用e洗护接口:'.$couponID.'_'.$userID.'_'.$mobile,CLogger::LEVEL_ERROR, 'colourlife.core.EXihuApi.notifyEXihu');
    	$method = 'newdaxila/Open/CouponsMessage/couponApi.do';
        $para = array(
        	'user_Mobile' => $mobile,
        	'coupons_Id' => $couponID,
        	'user_Id' => $userID
        );
        //dump($para);

        $this->queryData = $this->makeQueryData($para);
        $this->queryUrl = $this->makeServerUrl($method);

        Yii::log('调用e洗护接口,url:'.$this->queryUrl.',参数：'.json_encode($this->queryData),CLogger::LEVEL_ERROR, 'colourlife.core.EXihuApi.notifyEXihu');
        $result =  Yii::app()->curl->post($this->queryUrl, $this->queryData);
        Yii::log('调用e洗护接口返回结果:'.$result,CLogger::LEVEL_ERROR, 'colourlife.core.EXihuApi.notifyEXihu');
        if ($result == 'success'){
        	return true;
        }else {
        	return false;
        }
    }

    /**
     * 生成请求的url
     * @param $method
     * @return string
     */
    private function makeServerUrl($method)
    {
        return $this->serverUrl.$method;
    }


    private function makeQueryData($para=null)
    {
        $paraComm = array(
            'app_id' => $this->app_id,
        	'zh' => $this->zh,
            'fr' => $this->fr,
        	'timestamp' => time(),
        	'method' => 'couponsInform'
        );
        if ($para && is_array($para)) {
            // 合并参数  php ver < 4  不能用array_merge
            foreach ($paraComm as $key => $val) {
                $para[$key] = $val;
            }
        }
        else {
            $para = $paraComm;
        }

        //$para['sign'] = $this->makeSign($para);
        $para['sign'] = md5($paraComm['app_id'].$paraComm['zh'].$paraComm['fr'].$paraComm['timestamp']);
        //dump($para);
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

        $signStr = $this->createLinkstring($para).'&key='.$this->partnerKey;

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
    public function createLinkstring($para) {
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
            if($key == "sign" || $key == "sign_type" || $val == "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
}