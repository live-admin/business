<?php
/**
 * 艾科停车缴费接口服务类
 * User: Joy
 * Date: 2015/8/27
 * Time: 14:57
 */
class AikeApi
{
    //private $serverUrl = 'http://58.252.73.14:5310/ThirdConnecter/alien/'; //test
    //private $partner = "6edc80a2-ea61-40df-9f8d-b4929d609229"; //test
    private $serverUrl = 'http://58.252.73.14:14900/ThirdConnecter/alien/';
    private $partner = "3c5d89f7-e5f9-4646-a178-99711a911f2a";
    private $partnerKey = 'LEukDaL12dauHhtp';

    private $version = 'v1.0.0';


    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }

    public $queryData;
    public $queryUrl;

    /**
     * 获取所有停车场信息
     * @return bool|mixed
     */
    public function getAllParkingInfo()
    {
        $this->queryData = $this->makeQueryData();
        $this->queryUrl = $this->makeServerUrl('getAllParkingInfo.do');

        $result =  Yii::app()->curl->post($this->queryUrl, $this->queryData);
        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 临停 查询计费接口
     * @param $parkCode
     * @param $carCode
     * @param string $cardCode
     * @return bool|mixed
     */
    public function getPayBill($parkCode, $carCode, $cardCode='')
    {
        $para = array(
            'parkCode' => $parkCode,
            'carCode' => $carCode,
            'cardCode' => $cardCode
        );

        $this->queryData = $this->makeQueryData($para);
        $this->queryUrl = $this->makeServerUrl('getPayBill.do');

        $result =  Yii::app()->curl->post($this->queryUrl, $this->queryData);
        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 支付账单同步接口
     * @param $parkCode
     * @param $parkingBillId
     * @param $billCode
     * @param $payDate
     * @param $actPayCharge
     * @param $reliefCharge
     * @param $payWay
     * @param string $desc
     * @return bool|mixed
     */
    public function synPayBill($parkCode, $parkingBillId, $billCode, $payDate, $actPayCharge, $reliefCharge, $payWay, $desc='')
    {
        $para = array(
            'parkCode' => $parkCode,
            'parkingBillId' => $parkingBillId,
            'billCode' => $billCode,
            'payDate' => $payDate,
            'actPayCharge' => $actPayCharge,
            'reliefCharge' => $reliefCharge,
            'payWay' => $payWay,
            'desc' => $desc,
        );

        $this->queryData = $this->makeQueryData($para);
        $this->queryUrl = $this->makeServerUrl('synPayBill.do');

        $result =  Yii::app()->curl->post($this->queryUrl, $this->queryData);
        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 月卡 信息查询
     * @param $parkCode 停车场编号
     * @param $carCode  车牌号
     * @param string $cusCode 车主名
     * @return bool|mixed
     */
    public function getMonthCardInfo($carCode, $parkCode, $cusCode='')
    {
        $para = array(
            'parkCode' => $parkCode,
            'carNO' => $carCode,
            'cusNO' => $cusCode
        );

        $this->queryData = $this->makeQueryData($para);
        $this->queryUrl = $this->makeServerUrl('getMonthCardInfo.do');

        $result =  Yii::app()->curl->post($this->queryUrl, $this->queryData);
        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 月卡 续费月卡
     * @param $parkCode 停车场编号
     * @param $carCode  车牌号
     * @param $payDate  续费日期
     * @param $payMonths 续费月数
     * @param $paySumMoney 续费金额
     * @param $payStandardCode 收费标准编号
     * @param $payExpiryDate 缴费截止日期
     * @return bool|mixed
     */
    public function renewMonthCard($parkCode, $carCode, $payDate, $payMonths, $paySumMoney, $payStandardCode, $payExpiryDate)
    {
        $para = array(
            'parkCode' => $parkCode,
            'carNO' => $carCode,
            'payDate' => $payDate,
            'payMonths' => $payMonths,
            'paySumMoney' => $paySumMoney,
            'payStandardCode' => 1,//$payStandardCode,
            'payExpiryDate' => $payExpiryDate
        );
        //dump($para);

        $this->queryData = $this->makeQueryData($para);
        $this->queryUrl = $this->makeServerUrl('renewMonthCard.do');

        $result =  Yii::app()->curl->post($this->queryUrl, $this->queryData);
        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 处理请求结果
     * @param $result
     * @param string $queryUrl
     * @return bool|mixed
     */
    private function resolveResult($result)
    {
        //echo urldecode($result);exit;
        $result = json_decode(urldecode($result), true);

        if (isset($result['status']) && 1 === intval($result['status'])) {
            $resultData = json_decode($result['data'], true);
            return $resultData ? $resultData : true;
        }
        else {
            Yii::log('【艾科停车】调用接口：'.$this->queryUrl.' 参数：'.json_encode($this->queryData).' 错误信息：['.$result['resultCode'].']'.$result['message'], CLogger::LEVEL_INFO, 'colourlife.core.api.AikeApi');
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
            'reqDate' => date('Y-m-d H:i:s'),
            'ver' => $this->version,
            'carrierId' => $this->partner,
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

        $para['sign'] = $this->makeSign($para);

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