<?php
include_once("Utils.php");
Yii::import('common.components.models.UnionPayment');
//include dirname(__FILE__) . '/../../../vendor/phpseclib/phpseclib/Crypt/RSA.php';
//set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) . '/../../../vendor/phpseclib/phpseclib');
//include('Crypt/RSA.php');
//Yii::setPathOfAlias('phpseclib',dirname(__FILE__) . '/../../../vendor/phpseclib/phpseclib');
//yii::import('phpseclib.*');


/*set_include_path(implode(PATH_SEPARATOR, array(
    dirname(__FILE__) . '/../phpseclib/',
    dirname(__FILE__) . '/',
    get_include_path(),
)));*/


/*function phpseclib_autoload($class)
{
    $file = str_replace('_', '/', $class) . '.php';

    if (phpseclib_resolve_include_path($file)) {
        require $file;
    }
}

spl_autoload_register('phpseclib_autoload');*/

//Yii::import('comext.phpseclib.Crypt.RSA');
include dirname(__FILE__) . '/../../../common/extensions/phpseclib/Crypt/Hash.php';
include dirname(__FILE__) . '/../../../common/extensions/phpseclib/Math/BigInteger.php';
include dirname(__FILE__) . '/../../../common/extensions/phpseclib/Crypt/RSA.php';
class Unionpay
{
    static private $instance;
    //protected $submit_url = "http://mobilepay.unionpaysecure.com/qzjy/MerOrderAction/deal.action";
    protected $submit_url = "http://211.154.166.249/merchat/RequestServlet";

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $payment_id 支付方式ID
     * @param $startTime 开始时间 格式为：时间戳
     * @param $endTime 结束时间 同开始时间
     * @param string $requestPage 页码
     * @return array
     * 按时间段查询交易成功的订单
     */
    public function getAccountForIncomeByTime($payment_id,$startTime,$endTime=0,$requestPage="1"){
        $data = array();
        $payment = UnionPayment::model()->findByPk($payment_id);
        $merchantPublicCert = $payment->selfPublicKey;//
        $merchantId = $payment->account;
        $transType="01";
        $send_data = $merchantId.'|'.'20140721';
        $data['signature'] = $payment->selfSign($send_data);
        $public_key = $payment->selfPKey;
        $rsa = new Crypt_RSA();
        $rsa->loadKey($public_key);
        $modulus = base64_encode($rsa->modulus->toBytes(true));
        $publicExponent = base64_encode($rsa->publicExponent->toBytes(true));
        $data['pbk'] = $modulus.'|'.$publicExponent;
        $test = Yii::app()->curl->post($this->submit_url,$data);
        var_dump('发送的url:'.$this->submit_url);
        var_dump('发送的数据:'.$send_data);
        var_dump('发送的密钥信息：'.var_export($data,true));
        var_dump('返回的数据：'.$test);exit;
        $merchantOrderTime=date("Ymd",$startTime);

        $strForSign = "transType=" . $transType .
            "&merchantId=" . $merchantId .
           // '&merchantOrderId=' . $merchantOrderId .
            '&merchantOrderTime=' . $merchantOrderTime;
        $sign = $payment->selfSign($strForSign);

        $attrArr = array("application" => "QueryOrder.Req", "version" => "1.0.0");
        $nodeArr = array("transType" => $transType,
            "merchantId" => $merchantId,
            //"merchantOrderId" => $merchantOrderId,
            "merchantOrderTime" => $merchantOrderTime,
            "sign" => $sign,
            "merchantPublicCert" => $merchantPublicCert,
        );
        $xml = XmlUtils::writeXml($attrArr, $nodeArr);

        $postDeal = new PostUtils();
        $recv = $postDeal->submitByPost($this->submit_url, $xml);
        $xmlDeal = new XmlUtils();
        $parse= $xmlDeal->readXml($recv);

        if ($parse) {
            $nodeArray = $xmlDeal->getNodeArray();
             print_r($nodeArray);exit;
            //验签
            $checkIdentifier = "transType=" . $nodeArray['transType'] .
                "&merchantId=" . $nodeArray['merchantId'] .
                //"&merchantOrderId=" . $nodeArray['merchantOrderId'] .
                "&merchantOrderTime=" . $nodeArray['merchantOrderTime'];// .
                /*"&queryResult=" . $nodeArray['queryResult'] .
                "&settleDate=" . $nodeArray['settleDate'] .
                "&setlAmt=" . $nodeArray['setlAmt'] .
                "&setlCurrency=" . $nodeArray['setlCurrency'] .
                "&converRate=" . $nodeArray['converRate'] .
                "&cupsQid=" . $nodeArray['cupsQid'] .
                "&cupsTraceNum=" . $nodeArray['cupsTraceNum'] .
                "&cupsTraceTime=" . $nodeArray['cupsTraceTime'] .
                "&cupsRespCode=" . $nodeArray['cupsRespCode'] .
                "&cupsRespDesc=" . $nodeArray['cupsRespDesc'] .
                "&respCode=" . $nodeArray['respCode'];*/
            $respCode = $payment->checkUnionSign($checkIdentifier,$nodeArray['sign']);

            if ($respCode == '0000') {
                //验签通过
                echo "验签通过,respCode=".$nodeArray['respCode'].";queryResult=" . $nodeArray['queryResult'];
            } else {
                if ($respCode == '0001') {
                    //验签失败
                    echo "验签失败";
                }
                if ($respCode == '9999') {
                    //验签过程发生异常
                }
            }
        } else {
            echo "失败,接收返回：".$recv;
        }
    }
    public function getAccountForIncomeByTime_bak($payment_id,$startTime,$endTime=0,$requestPage="1"){
        $payment = UnionPayment::model()->findByPk($payment_id);
        $merchantPublicCert = $payment->selfPublicKey;//
        $merchantId = $payment->account;
        $transType="01";
        //$merchantOrderId="2030697131130111611001";
        $merchantOrderTime=date("YmdHis",$startTime);

        $strForSign = "transType=" . $transType .
            "&merchantId=" . $merchantId .
           // '&merchantOrderId=' . $merchantOrderId .
            '&merchantOrderTime=' . $merchantOrderTime;
        $sign = $payment->selfSign($strForSign);

        $attrArr = array("application" => "QueryOrder.Req", "version" => "1.0.0");
        $nodeArr = array("transType" => $transType,
            "merchantId" => $merchantId,
            //"merchantOrderId" => $merchantOrderId,
            "merchantOrderTime" => $merchantOrderTime,
            "sign" => $sign,
            "merchantPublicCert" => $merchantPublicCert,
        );
        $xml = XmlUtils::writeXml($attrArr, $nodeArr);

        $postDeal = new PostUtils();
        $recv = $postDeal->submitByPost($this->submit_url, $xml);
        $xmlDeal = new XmlUtils();
        $parse= $xmlDeal->readXml($recv);

        if ($parse) {
            $nodeArray = $xmlDeal->getNodeArray();
             print_r($nodeArray);exit;
            //验签
            $checkIdentifier = "transType=" . $nodeArray['transType'] .
                "&merchantId=" . $nodeArray['merchantId'] .
                //"&merchantOrderId=" . $nodeArray['merchantOrderId'] .
                "&merchantOrderTime=" . $nodeArray['merchantOrderTime'];// .
                /*"&queryResult=" . $nodeArray['queryResult'] .
                "&settleDate=" . $nodeArray['settleDate'] .
                "&setlAmt=" . $nodeArray['setlAmt'] .
                "&setlCurrency=" . $nodeArray['setlCurrency'] .
                "&converRate=" . $nodeArray['converRate'] .
                "&cupsQid=" . $nodeArray['cupsQid'] .
                "&cupsTraceNum=" . $nodeArray['cupsTraceNum'] .
                "&cupsTraceTime=" . $nodeArray['cupsTraceTime'] .
                "&cupsRespCode=" . $nodeArray['cupsRespCode'] .
                "&cupsRespDesc=" . $nodeArray['cupsRespDesc'] .
                "&respCode=" . $nodeArray['respCode'];*/
            $respCode = $payment->checkUnionSign($checkIdentifier,$nodeArray['sign']);

            if ($respCode == '0000') {
                //验签通过
                echo "验签通过,respCode=".$nodeArray['respCode'].";queryResult=" . $nodeArray['queryResult'];
            } else {
                if ($respCode == '0001') {
                    //验签失败
                    echo "验签失败";
                }
                if ($respCode == '9999') {
                    //验签过程发生异常
                }
            }
        } else {
            echo "失败,接收返回：".$recv;
        }
    }

    /**
     * @param $payment_id 支付方式ID
     * @param $orderId 订单号
     * @param string $requestPage
     * @return array
     * 根据订单号查询入账对账订单
     */
    public function getAccountForIncomeByOrderId($payment_id,$orderId,$requestPage="1"){

    }

    /**
     * @param $payment_id 支付方式
     * @param $startDate 退款最后查询开始时间，格式时间戳
     * @param $endDate    退款最后查询结束时间
     * @param int $requestPage 页码
     * @return array
     * 按时间查询退款对账单
     */
    public function getAccountForRefundByTime($payment_id,$startDate,$endDate,$requestPage=1){

    }

    /**
     * @param $payment_id   支付方式
     * @param $rOrderId      订单号
     * @param $startDate  退款最后查询开始时间
     * @param $endDate    退款最后查询结束时间
     * @param int $requestPage  页码
     * @return array
     * 按订单查询退款订单详情
     */
    public function getAccountForRefundByOrderId($payment_id,$rOrderId,$startDate,$endDate,$requestPage=1){

    }

    static function getXml($transType, $merchantOrderId, $merchantOrderTime)
    {
        $merchantPublicCert = SecretUtils::getPublicKeyBase64(MY_public_key);
        // echo  $merchantPublicCert;
        $merchantId = MY_id;
        $strForSign = "transType=" . $transType .
            "&merchantId=" . $merchantId .
            "&merchantOrderId=" . $merchantOrderId .
            "&merchantOrderTime=" . $merchantOrderTime;
        //echo $strForSign;
        $sign = SecretUtils::sign($strForSign, MY_private_key, MY_prikey_password);

        $attrArray = array("application" => "QueryOrder.Req", "version" => "1.0.0");
        $nodeArray = array("transType" => $transType,
            "merchantId" => $merchantId,
            "merchantOrderId" => $merchantOrderId,
            "merchantOrderTime" => $merchantOrderTime,
            "sign" => $sign,
            "merchantPublicCert" => $merchantPublicCert);
        $result = XmlUtils::writeXml($attrArray, $nodeArray);
        return $result;
    }
}

?>