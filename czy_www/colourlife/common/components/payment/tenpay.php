<?php

/**
 * 财付通插件
 */
class tenpay extends PayFactory
{
    public function __construct()
    {
    }

    /**
     * 生成支付代码
     * @param   array $pay 支付信息
     * @param   array $payment 支付方式信息
     */
    public function get_code($pay, $payment, $returnUrl = '')
    {
        $lib_path = dirname(__FILE__) . '/tenpay/';
        // 包含库接口文件
        include_once($lib_path . 'RequestHandler.class.php');
        /* 返回的路径 */
        $return_url = empty($returnUrl) ? PayLib::return_url('tenpay') : $returnUrl;
        $notify_url = PayLib::notify_url('tenpay');

        // 商户号
        $partner = $payment->account;

        /* 密钥 */
        $key = $payment->key;

        /* 总金额 */
        $total_fee = $this->getAmount($pay);

        //订单号
        $out_trade_no = $pay->pay_sn;

        /* 创建支付请求对象 */
        $reqHandler = new RequestHandler();
        $reqHandler->init();
        $reqHandler->setKey($key);
        $reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

        //----------------------------------------
        //设置支付参数
        //----------------------------------------
        $reqHandler->setParameter("total_fee", $total_fee); //总金额
        //用户ip
        $reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']); //客户端IP
        $reqHandler->setParameter("return_url", $return_url); //支付成功后返回
        $reqHandler->setParameter("partner", $partner);
        $reqHandler->setParameter("out_trade_no", $out_trade_no);
        $reqHandler->setParameter("notify_url", $notify_url);
        $reqHandler->setParameter("body", "付款订单号为：" . $out_trade_no);
        $reqHandler->setParameter("bank_type", "DEFAULT"); //银行类型，默认为财付通
        $reqHandler->setParameter("fee_type", "1"); //币种
        //系统可选参数
        $reqHandler->setParameter("sign_type", "MD5"); //签名方式，默认为MD5，可选RSA
        $reqHandler->setParameter("service_version", "1.0"); //接口版本号
        $reqHandler->setParameter("input_charset", "UTF-8"); //字符集
        $reqHandler->setParameter("sign_key_index", "1"); //密钥序号

        //业务可选参数
        $reqHandler->setParameter("attach", ""); //附件数据，原样返回就可以了
        $reqHandler->setParameter("product_fee", ""); //商品费用
        $reqHandler->setParameter("transport_fee", ""); //物流费用
        $reqHandler->setParameter("time_start", date("YmdHis")); //订单生成时间
        $reqHandler->setParameter("time_expire", ""); //订单失效时间

        $reqHandler->setParameter("buyer_id", ""); //买方财付通帐号
        $reqHandler->setParameter("goods_tag", ""); //商品标记


        //请求的URL
        $reqUrl = $reqHandler->getRequestURL();

        //获取debug信息,建议把请求和debug信息写入日志，方便定位问题

        $button = '<form id="button_' . $payment->code . '" style="text-align:center;" action="'. $reqHandler->getGateUrl() .'" method="post"  target="_blank">';
        $params = $reqHandler->getAllParameters();
        foreach ($params as $k => $v) {
            $button .= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
        }
        //$button .= '<input type="submit" value="财付通支付">';
        $button .= '</form>';
        return $button;
    }

    /**
     * 响应操作
     * @param string $type notify为回调时调用，return 为返回时调用
     * @return bool
     */
    public function respond($type = 'return')
    {
        $lib_path = dirname(__FILE__) . '/tenpay/';
        // 包含库接口文件
        require($lib_path . 'ResponseHandler.class.php');
        require($lib_path . 'RequestHandler.class.php');
        require($lib_path . 'client/ClientResponseHandler.class.php');
        require($lib_path . 'client/TenpayHttpClient.class.php');

        $payment = PayLib::get_payment('tenpay');

        // 商户号
        $partner = $payment->account;

        /* 密钥 */
        $key = $payment->key;

        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey($key);

        //判断签名
        if ($resHandler->isTenpaySign()) {

            //通知id
            $notify_id = $resHandler->getParameter("notify_id");

            //通过通知ID查询，确保通知来至财付通
            //创建查询请求
            $queryReq = new RequestHandler();
            $queryReq->init();
            $queryReq->setKey($key);
            $queryReq->setGateUrl("https://gw.tenpay.com/gateway/verifynotifyid.xml");
            $queryReq->setParameter("partner", $partner);
            $queryReq->setParameter("notify_id", $notify_id);

            //通信对象
            $httpClient = new TenpayHttpClient();
            $httpClient->setTimeOut(5);
            //设置请求内容
            $httpClient->setReqContent($queryReq->getRequestURL());

            //后台调用
            if ($httpClient->call()) {
                //设置结果参数
                $queryRes = new ClientResponseHandler();
                $queryRes->setContent($httpClient->getResContent());
                $queryRes->setKey($key);

                //判断签名及结果
                //只有签名正确,retcode为0，trade_state为0才是支付成功
                if ($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0" && $queryRes->getParameter("trade_state") == "0" && $queryRes->getParameter("trade_mode") == "1" ) {
                    //取结果参数做业务处理
                    $out_trade_no = $queryRes->getParameter("out_trade_no");
                    //财付通订单号
                    $transaction_id = $queryRes->getParameter("transaction_id");
                    //金额,以分为单位
                    $total_fee = $queryRes->getParameter("total_fee");
                    //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
                    $discount = $queryRes->getParameter("discount");

                    $this->_sn = $out_trade_no;
                    $this->_id = Pay::getPaySn($this->_sn);
                    $amount = $total_fee / 100;

                    /* 检查支付的金额是否相符 */
                    if (!PayLib::check_money($this->_sn, $amount)) {
                        Yii::log('财付通流水号' . $this->_sn .'检查支付的金额不相符', CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpay');
                        return false;
                    }

                    if ($type == 'notify' && Pay::getPayStatus($this->_sn) == 0) { //状态为0才能去修改状态
                        //添加支付日志
                        if(!PayLog::createPayLog($this->_sn, $amount, $payment->id))
                            Yii::log('财付通添加支付记录失败流水号为：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpay');
                        else
                        {
                            /* 改变订单状态 */
                            PayLib::order_paid($this->_sn, $payment->id);
                            Yii::log('财付通成功支付流水号为：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpay');
                        }
                        return true;
                    } else {
                        //'支付结果失败';
                        Yii::log('财付通流水号' . $this->_sn .'检查支付结果失败', CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpay');
                        return false;
                    }
                } else {
                    Yii::log(
                        "财付通验证签名失败 或 业务错误信息:trade_state=" . $queryRes->getParameter(
                            "trade_state"
                        ) . ",retcode=" . $queryRes->getParameter("retcode") . ",retmsg=" . $queryRes->getParameter(
                            "retmsg"
                        ),
                        CLogger::LEVEL_INFO,
                        'colourlife.core.payment.tenpay'
                    );
                    return false;
                }
            } else {
                Yii::log(
                    '财付通订单通知查询失败:' . $httpClient->getResponseCode() . ',' . $httpClient->getErrInfo(),
                    CLogger::LEVEL_INFO,
                    'colourlife.core.payment.tenpay'
                );
                Yii::log('debug' . $resHandler->getDebugInfo(), CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpay');
                return false;
            }
        } else {
            Yii::log('财付通签名失败', CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpay');
            Yii::log('debug' . $resHandler->getDebugInfo(), CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpay');
            return false;
        }
    }

}
