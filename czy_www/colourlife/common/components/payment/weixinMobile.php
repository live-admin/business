<?php

/**
 * 财付通插件
 */
define('DEBUG', false);

class weixinMobile extends PayFactory
{
    public $returnUrl;
    const EXPIRE = 7200; //超时时间

    public function __construct()
    {
        //默认返回地址
        $this->returnUrl = F::getOrderUrl('/wap/tenpay');
    }

    /*
     * 生成token
     */
    private function getToken($reqHandler, $payment)
    {
        if (empty($payment->app_id) || empty($payment->app_secret) || empty($payment->key) || empty($payment->app_key))
            throw new CHttpException(403, "微信支付方式资料没有完善，请联系管理员!");

        if (Yii::app()->cache->get('weiXinToken') === false) {
            $reqHandler->init($payment->app_id, $payment->app_secret, $payment->key, $payment->app_key);
            $access_token = $reqHandler->GetToken();
            Yii::app()->cache->set('weiXinToken', $access_token, self::EXPIRE);
            return $access_token;
        } else {
            return Yii::app()->cache->get('weiXinToken');
        }
    }

    /**
     * 生成支付代码
     * @param   Pay $pay 订单信息
     * @param   Paymnet $payment 支付方式信息
     */
    public function get_code($pay, $payment)
    {
        $lib_path = dirname(__FILE__) . '/weixin/';
        // 包含库接口文件
        require_once($lib_path . 'RequestHandler.class.php');
        require($lib_path . 'ResponseHandler.class.php');
        require($lib_path . 'client/TenpayHttpClient.class.php');

        /* 返回的路径 */
        $return_url = $this->returnUrl;
        $notify_url = PayLib::notify_url('weixinMobile');
        // 商户号
        $partner = $payment->account;

        /* 密钥 */
        $key = $payment->key;

        /* 总金额 //商品价格（包含运费），以分为单位*/
        $total_fee = $this->getAmount($pay);

        //兼容老版本。走双乾支付。
        $newlhqpay = PayFactory::getInstance('newlhqpay');
        $newlhqpayArr =$newlhqpay->get_code($pay, 'WECHAT', Yii::app()->user->id);

        //订单号
        //$out_trade_no = $pay->pay_sn;
        $out_trade_no = $newlhqpayArr['payNo'];
        $notify_url = $newlhqpayArr['channelUrl'];
        //获取提交的商品名称
        $product_name = "付款订单号为：" . $pay->pay_sn;

        $outparams = array();

        //获取token值
        $reqHandler = new RequestHandler();
        $token = $this->getToken($reqHandler, $payment);
        $note = '金额：' . $total_fee . '返回url：' . $return_url . 'token：' . $token . '订单号：' . $out_trade_no . '回调URL：' . $notify_url;
        Yii::log('微信支付取得的参数' . $note, CLogger::LEVEL_INFO, 'colourlife.core.payment.get_code.weixinMobile');
        if ($token != '') {
            $reqHandler->partner_key = $key;
            $reqHandler->Token = $token;
            //dump($reqHandler);
            //=========================
            //生成预支付单
            //=========================
            //设置packet支付参数
            $packageParams = array();

            $packageParams['bank_type'] = 'WX'; //支付类型
            $packageParams['body'] = $product_name; //商品描述
            $packageParams['fee_type'] = '1'; //银行币种
            $packageParams['input_charset'] = 'GBK'; //字符集
            $packageParams['notify_url'] = $notify_url; //通知地址
            $packageParams['out_trade_no'] = $out_trade_no; //商户订单号
            $packageParams['partner'] = $partner; //设置商户号
            $packageParams['total_fee'] = $total_fee; //商品总金额,以分为单位
            $packageParams['spbill_create_ip'] = $_SERVER['REMOTE_ADDR']; //支付机器IP

            //获取package包
            $package = $reqHandler->genPackage($packageParams);
            $time_stamp = time();
            $nonce_str = md5(rand());
            //设置支付参数
            $signParams = array();
            $signParams['appid'] = $payment->app_id;
            $signParams['appkey'] = $payment->app_key;
            $signParams['noncestr'] = $nonce_str;
            $signParams['package'] = $package;
            $signParams['timestamp'] = $time_stamp;
            $signParams['traceid'] = 'mytraceid_001';
            //生成支付签名

            $sign = $reqHandler->createSHA1Sign($signParams);
            //增加非参与签名的额外参数
            $signParams['sign_method'] = 'sha1';
            $signParams['app_signature'] = $sign;
            //剔除appkey
            unset($signParams['appkey']);
            //获取prepayid
            $prepayid = $reqHandler->sendPrepay($signParams);

            if ($prepayid != null) {
                $pack = 'Sign=WXPay';
                //输出参数列表
                $prePayParams = array();
                $prePayParams['appid'] = $payment->app_id;
                $prePayParams['appkey'] = $payment->app_key;
                $prePayParams['noncestr'] = $nonce_str;
                $prePayParams['package'] = $pack;
                $prePayParams['partnerid'] = $partner;
                $prePayParams['prepayid'] = $prepayid;
                $prePayParams['timestamp'] = $time_stamp;
                //生成签名
                $sign = $reqHandler->createSHA1Sign($prePayParams);

                $outparams['retcode'] = 0;
                $outparams['retmsg'] = 'ok';
                $outparams['appid'] = $payment->app_id;
                $outparams['noncestr'] = $nonce_str;
                $outparams['package'] = $pack;
                $outparams['prepayid'] = $prepayid;
                $outparams['timestamp'] = $time_stamp;
                $outparams['appkey'] = $prePayParams['appkey'];
                $outparams['partnerid'] = $prePayParams['partnerid'];
                $outparams['sign'] = $sign;

            } else {
                $outparams['retcode'] = -2;
                $outparams['retmsg'] = '错误：获取prepayId失败';
            }
        } else {
            $outparams['retcode'] = -1;
            $outparams['retmsg'] = '错误：获取不到Token';
        }

        /**
         * =========================
         * 输出参数列表
         * =========================
         */
        //Json 输出
        ob_clean();
        // echo json_encode($outparams);
        return $outparams;
        //debug信息,注意参数含有特殊字符，需要JsEncode
        if (DEBUG)
            echo PHP_EOL . '/*' . ($reqHandler->getDebugInfo()) . '*/';
    }

    public function respond($type = 'return')
    {
        $lib_path = dirname(__FILE__) . '/weixin/';
        // 包含库接口文件
        require($lib_path . "ResponseHandler.class.php");
        require($lib_path . "RequestHandler.class.php");
        require($lib_path . "client/ClientResponseHandler.class.php");
        require($lib_path . "client/TenpayHttpClient.class.php");
        $payment = PayLib::get_payment('weixinMobile');
        // 商户号
        $partner = $payment->account;
        /* 密钥 */
        $key = $payment->key;

        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey($key);

        //判断签名
        if ($resHandler->isTenpaySign() == true) {
            //商户在收到后台通知后根据通知ID向财付通发起验证确认，采用后台系统调用交互模式
            $notify_id = $resHandler->getParameter("notify_id"); //通知id

            //商户交易单号
            $out_trade_no = $resHandler->getParameter("out_trade_no");

            //财付通订单号
            $transaction_id = $resHandler->getParameter("transaction_id");

            //商品金额,以分为单位
            $total_fee = $resHandler->getParameter("total_fee");

            //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
            $discount = $resHandler->getParameter("discount");

            //支付结果
            $trade_state = $resHandler->getParameter("trade_state");
            //可获取的其他参数还有
            //bank_type			银行类型,默认：BL
            //fee_type			现金支付币种,目前只支持人民币,默认值是1-人民币
            //input_charset		字符编码,取值：GBK、UTF-8，默认：GBK。
            //partner			商户号,由财付通统一分配的10位正整数(120XXXXXXX)号
            //product_fee		物品费用，单位分。如果有值，必须保证transport_fee + product_fee=total_fee
            //sign_type			签名类型，取值：MD5、RSA，默认：MD5
            //time_end			支付完成时间
            //transport_fee		物流费用，单位分，默认0。如果有值，必须保证transport_fee +  product_fee = total_fee

            //判断签名及结果
            if ("0" == $trade_state) {
                /* 检查支付的金额是否相符 */
                if (!PayLib::check_money($out_trade_no, $total_fee / 100)) {
                    Yii::log('微信手机支付检查支付的金额不相符订单SN：' . $this->_sn, CLogger::LEVEL_INFO, 'colourlife.core.payment.weixinMobile');
                    return false;
                }

                //$this->_id = PayLib::get_id_by_sn($out_trade_no);
                $this->_sn = $out_trade_no;

                $amount = $total_fee / 100;
                if ($type == 'notify' && Pay::getPayStatus($this->_sn) == 0) { //状态为0才能去修改状态
                    //添加支付日志
                    if (!PayLog::createPayLog($this->_sn, $amount, $payment->id))
                        Yii::log('微信手机支付添加支付记录失败流水号为：' . $this->_sn . ' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.weixinMobile');
                    else {
                        /* 改变订单状态 */
                        PayLib::order_paid($this->_sn, $payment->id);
                        Yii::log('微信手机支付成功支付流水号为：' . $this->_sn . ' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.weixinMobile');
                    }
                    return true;
                }
            } else {
                Yii::log('微信手机支付支付失败订单SN：' . $this->_sn, CLogger::LEVEL_INFO, 'colourlife.core.payment.weixinMobile');
                return false;
            }
        } else {
            //回调签名错误
            Yii::log('微信手机支付失败流水号为：' . $this->_sn, CLogger::LEVEL_INFO, 'colourlife.core.payment.weixinMobile');
            return false;
        }
    }


}
