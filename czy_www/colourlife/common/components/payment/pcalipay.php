<?php
/**
 * Created by PhpStorm.
 * User: hzz
 * Date: 2017/9/1
 * Time: 10:45
 */
/**
 * 支付宝支付插件
 */
class pcalipay extends PayFactory
{
    public $returnUrl;

    public $sign_type = 'RSA';

    public $alipay_config;

    private $_lib_path;

    public $app_id = '2015123001053074';

    public $gatewayUrl = 'https://openapi.alipay.com/gateway.do';

    private $fileCharset = "UTF-8";

    //返回数据格式
    public $format = "json";

    // 表单提交字符集编码
    public $postCharset = "UTF-8";

    public function __construct()
    {
        //默认返回地址
        $this->returnUrl = F::getOrderUrl('/wap/alipay');
        //加载配置文件
        $this->_lib_path = dirname(__FILE__) . '/alipay/';
        require_once($this->_lib_path . 'alipay.config.php');
        $alipay_config['private_key_path'] = $this->_lib_path . $alipay_config['private_key_path'];
        $alipay_config['ali_public_key_path'] = $this->_lib_path . $alipay_config['ali_public_key_path'];
        $this->alipay_config = $alipay_config;
        $this->alipayPublicKey = $alipay_config['ali_public_key_path'];

    }

    /**
     * 生成支付代码
     * @param   array $pay 订单信息
     * @param   array $payment 支付方式信息
     */
    public function get_code($pay, $payment,$total_fee)
    {

        // 包含库接口文件
        // require_once($lib_path . 'alipay.config.php');
        //require_once($lib_path . 'alipay_notify.class.php');
        require_once($this->_lib_path . 'alipay_core.class.php');
        require_once($this->_lib_path . 'alipay_rsa.class.php');
        //订单号
        $out_trade_no = $pay->pay_sn;
        //查找订单相对应的model
        $model = SN::findContentBySN($out_trade_no);

        if (empty($model))
            throw new CHttpException(400, "订单不存在");
        $subject = $model->modelName;
        $body = '';
        //商品名
        if (!empty($model->shop_name)) $subject .= ',' . $model->shop_name;
        if (!empty($model->goods_brief)) $body = $model->goods_brief;
        if (!$body) $body = $subject;
        /* 交易完成后跳转的URL */
        $return_url = $this->returnUrl;
        /*取得返回回调信息地址 接收通知的URL，需绝对路径*/
        $notify_url = PayLib::notify_url('alipay');
        // 商户号
        $partner = $payment->account;

        /* 卖家支付宝帐号 */
        $seller_id = $payment->seller_id;

        /* 总金额 */
       // $total_fee = $this->getAmountNum($pay);

        $array = array(
            "WIDout_trade_no"=>$out_trade_no ,
            "WIDsubject"=>$subject,
            "WIDbody"=>$body,
            "WIDtotal_amount"=>"$total_fee",
        );
        return $array;
    }

    /**
     * 响应操作
     * @param string $type notify为回调时调用，return 为返回时调用
     * @return bool
     */
    public function respond($type = 'return')
    {
        $payment = PayLib::get_payment('alipay');
        // 商户号
        $partner = $payment->account;
        /* 卖家支付宝帐号 */
        $seller_id = $payment->seller_id;
        require_once($this->_lib_path . 'alipay_notify.class.php');
        require_once($this->_lib_path . 'alipay_core.class.php');
        require_once($this->_lib_path . 'alipay_rsa.class.php');

        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if (empty($_REQUEST['out_trade_no']) || empty($_REQUEST['trade_no']) || empty($_REQUEST['trade_status'])
            || empty($_REQUEST['total_amount']) || empty($_REQUEST['seller_id']) ) {
            Yii::log('支付宝返回值有误', CLogger::LEVEL_INFO, 'colourlife.core.payment.alipay');
            //throw new CHttpException(400, "支付宝返回值有误");
            return false;
        }
        $verify_result = 1;
        //判断验证
        if ($verify_result) {
            //支付宝返回值
            $alipay_total_fee = $_REQUEST['total_amount'];
            $amount = $alipay_total_fee;
            $alipay_seller_id = $_REQUEST['seller_id'];
          //  $alipay_buyer_email = $_POST['seller_email'];
            //订单号
            $out_trade_no = $_REQUEST['out_trade_no'];
            $this->_sn = $out_trade_no;
            //支付宝交易号
            $trade_no = $_REQUEST['trade_no'];
            //交易状态
            $trade_status = $_REQUEST['trade_status'];
            //1、开通了普通即时到账，买家付款成功后。
            if($trade_status == 'TRADE_SUCCESS') {
                /* 检查支付的金额是否相符 */
                if (!PayLib::check_money($this->_sn, $alipay_total_fee)) {
                    Yii::log('支付宝流水号：' . $trade_no .'，彩之云订单号：' . $this->_sn .'检查支付的金额不相符', CLogger::LEVEL_INFO, 'colourlife.core.payment.alipay');
                    return false;
                }

                if ($type == 'notify' && Pay::getPayStatus($this->_sn) == 0) { //状态为0才能去修改状态
                    //添加支付日志
                    if(!PayLog::createPayLog($this->_sn, $amount, $payment->id))
                        Yii::log('支付宝添加支付记录失败支付宝流水号：' . $trade_no .'，彩之云订单号：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.alipay');
                    else
                    {
                        $note = "支付宝回调修改商铺买电订单状态。";
                        /* 改变订单状态 */
                        PayLib::order_paid($this->_sn, $payment->id);
                        OthersPowerFees::callStarOrder($this->_sn,$trade_status,$note);
                        Yii::log('支付宝支付成功支付宝流水号：' . $trade_no .'，彩之云订单号：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.alipay');
                    }
                }
            } elseif($trade_status == 'WAIT_BUYER_PAY') {
                //家付款
                $buyer_email = $_POST['buyer_email'];
                Yii::log('支付宝支付等待买家付款支付宝流水号：' . $trade_no .'，彩之云订单号：' . $this->_sn.'，买家支付宝帐号：' . $buyer_email, CLogger::LEVEL_INFO, 'colourlife.core.payment.alipay');
            } else {
                Yii::log('支付宝支付失败支付宝流水号：' . $trade_no .'，彩之云订单号：' . $this->_sn, CLogger::LEVEL_INFO, 'colourlife.core.payment.alipay');
                return false;
            }
            return true;
        } else {
            //回调签名错误
            Yii::log('支付宝回调签名错误', CLogger::LEVEL_INFO, 'colourlife.core.payment.alipay');
            return false;
        }
    }

    public function refund($params){
        //商户订单号和支付宝交易号不能同时为空。 trade_no、  out_trade_no如果同时存在优先取trade_no
        //商户订单号，和支付宝交易号二选一
        //$out_trade_no = $params['pay_sn'];
        $out_trade_no = '201741792433358';
        //支付宝交易号，和商户订单号二选一
        $trade_no = $params['trade_no'];

        //退款金额，不能大于订单总金额
        $refund_amount = $params['refund_amount'];

        //退款的原因说明
        $refund_reason = $params['refund_reason'];

        //标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传。
        $out_request_no = $params['out_request_no'];

        $bizContentarr = array(
            'trade_no' => $trade_no,
            'out_trade_no' => $out_trade_no,
            'refund_amount' => $refund_amount,
            'refund_reason' => $refund_reason,
            'out_request_no' => $out_request_no
        );

        $bizContent = json_encode($bizContentarr,JSON_UNESCAPED_UNICODE);

        $sysParams['biz_content'] = $bizContent;

        $apiParams = array(
            "app_id"=>$this->app_id,
            "method"=>'alipay.trade.refund',
            "format"=>'json',
            "timestamp"=>date('Y-m-d H:i:s', time()),
            "version"=>'1.0',
            "sign_type"=>'RSA',
            "charset"=>'UTF-8',
            "auth_token"=>'',
            "alipay_sdk"=>'alipay-sdk-php-20161101',
            "terminal_type"=>'',
            "terminal_info"=>'',
            "prod_code"=>'',
            "notify_url"=>'',
            "app_auth_token"=>''
        );

        $signData = null;
        require_once(dirname(__FILE__) . '/../alipayService/aop/AopClient.php');
        require_once(dirname(__FILE__) . '/../alipayService/aop/request/AlipayTradeRefundRequest.php');

        $aop = new AopClient ();
        $aop->rsaPrivateKeyFilePath = dirname(__FILE__) . '/../alipayService/key/rsa_private_key.pem';
        $request = new AlipayTradeRefundRequest();
        $request->setBizContent ( $bizContent );

        //签名
        $apiParams["sign"] = $aop->generateSign(array_merge($apiParams, $sysParams), $this->sign_type);

        //系统参数放入GET请求串
        $requestUrl = $this->gatewayUrl . "?";
        foreach ($apiParams as $sysParamKey => $sysParamValue) {
            $requestUrl .= "$sysParamKey=" . urlencode($this->characet($sysParamValue, $this->postCharset)) . "&";
        }
        $requestUrl = substr($requestUrl, 0, -1);

        //发起HTTP请求
        try {
            $resp = $this->curl($requestUrl, $sysParams);
        } catch (Exception $e) {

            Yii::log('支付宝退款失败！彩之云订单号：' . $out_trade_no . "HTTP_ERROR_" . $e->getCode() . $e->getMessage(), CLogger::LEVEL_INFO, 'colourlife.alipay.trade.refund');
            return false;
        }

        //解析返回结果
        $respWellFormed = false;


        // 将返回结果转换本地文件编码
        $r = iconv($this->postCharset, $this->fileCharset . "//IGNORE", $resp);

        if ("json" == $this->format) {

            $respObject = json_decode($r);
            if (null !== $respObject) {
                $respWellFormed = true;
                $signData = $aop->parserJSONSignData($request, $resp, $respObject);
            }
        } else if ("xml" == $this->format) {

            $respObject = @ simplexml_load_string($resp);
            if (false !== $respObject) {
                $respWellFormed = true;

                $signData = $aop->parserXMLSignData($request, $resp);
            }
        }

        //返回的HTTP文本不是标准JSON或者XML，记下错误日志
        if (false === $respWellFormed) {
            //$aop->logCommunicationError($sysParams["method"], $requestUrl, "HTTP_RESPONSE_NOT_WELL_FORMED", $resp);
            return false;
        }

        // 验签
        $this->checkResponseSign($request, $signData, $resp, $respObject);


        // 解密
        if (method_exists($request,"getNeedEncrypt") &&$request->getNeedEncrypt()){

            if ("json" == $this->format) {


                $resp = $aop->encryptJSONSignSource($request, $resp);

                // 将返回结果转换本地文件编码
                $r = iconv($this->postCharset, $this->fileCharset . "//IGNORE", $resp);
                $respObject = json_decode($r);
            }else{

                $resp = $aop->encryptXMLSignSource($request, $resp);

                $r = iconv($this->postCharset, $this->fileCharset . "//IGNORE", $resp);
                $respObject = @ simplexml_load_string($r);

            }
        }

        return $respObject;

    }

    protected function curl($url, $postFields = null) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $postBodyString = "";
        $encodeArray = Array();
        $postMultipart = false;


        if (is_array($postFields) && 0 < count($postFields)) {

            foreach ($postFields as $k => $v) {
                if ("@" != substr($v, 0, 1)) //判断是不是文件上传
                {

                    $postBodyString .= "$k=" . urlencode($this->characet($v, $this->postCharset)) . "&";
                    $encodeArray[$k] = $this->characet($v, $this->postCharset);
                } else //文件上传用multipart/form-data，否则用www-form-urlencoded
                {
                    $postMultipart = true;
                    $encodeArray[$k] = new \CURLFile(substr($v, 1));
                }

            }
            unset ($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodeArray);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
            }
        }

        if ($postMultipart) {

            $headers = array('content-type: multipart/form-data;charset=' . $this->postCharset . ';boundary=' . $this->getMillisecond());
        } else {

            $headers = array('content-type: application/x-www-form-urlencoded;charset=' . $this->postCharset);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


        $reponse = curl_exec($ch);

        if (curl_errno($ch)) {

            throw new Exception(curl_error($ch), 0);
        } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }
        }

        curl_close($ch);
        return $reponse;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {

        if (!empty($data)) {
            $fileType = $this->fileCharset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //				$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }


        return $data;
    }


    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;

        return false;
    }

    /**
     * 验签
     * @param $request
     * @param $signData
     * @param $resp
     * @param $respObject
     * @throws Exception
     */
    public function checkResponseSign($request, $signData, $resp, $respObject) {

        require_once(dirname(__FILE__) . '/../alipayService/aop/AopClient.php');

        $aop = new AopClient ();
        if (!$this->checkEmpty($this->alipayPublicKey) || !$this->checkEmpty($this->alipayrsaPublicKey)) {


            if ($signData == null || $this->checkEmpty($signData->sign) || $this->checkEmpty($signData->signSourceData)) {

                throw new Exception(" check sign Fail! The reason : signData is Empty");
            }


            // 获取结果sub_code
            $responseSubCode = $aop->parserResponseSubCode($request, $resp, $respObject, $this->format);


            if (!$this->checkEmpty($responseSubCode) || ($this->checkEmpty($responseSubCode) && !$this->checkEmpty($signData->sign))) {

                $checkResult = $aop->verify($signData->signSourceData, $signData->sign, $this->alipayPublicKey, 'RSA');


                if (!$checkResult) {

                    if (strpos($signData->signSourceData, "\\/") > 0) {

                        $signData->signSourceData = str_replace("\\/", "/", $signData->signSourceData);

                        $checkResult = $aop->verify($signData->signSourceData, $signData->sign, $this->alipayPublicKey, 'RSA');

                        if (!$checkResult) {
                            throw new Exception("check sign Fail! [sign=" . $signData->sign . ", signSourceData=" . $signData->signSourceData . "]");
                        }

                    } else {

                        throw new Exception("check sign Fail! [sign=" . $signData->sign . ", signSourceData=" . $signData->signSourceData . "]");
                    }

                }
            }


        }
    }

}
