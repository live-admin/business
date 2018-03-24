<?php

/**
 * 财付通插件
 */
class weixin extends PayFactory
{
    public function __construct()
    {
    }

    /**
     * 生成支付代码
     * @param   Pay $pay 支付订单信息
     * @param   Payment $payment 支付方式信息
     * @param   String $returnUrl 支付方式信息
     */
    public function get_code($pay, $payment, $returnUrl = '')
    {
        $lib_path = dirname(__FILE__) . '/tenpay/';
        // 包含库接口文件
        include_once($lib_path . 'RequestHandler.class.php');
        /* 返回的路径 */
        $return_url = empty($returnUrl) ? PayLib::return_url('weixin') : $returnUrl;
        $notify_url = PayLib::notify_url('weixin');

        // 商户号
        $partner = $payment->account;

        /* 密钥 */
        $key = $payment->key;

        /* 总金额 */
        $total_fee = $this->getAmount($pay);;

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
        $reqHandler->setParameter("partner",	$partner);
        $reqHandler->setParameter("out_trade_no",	$out_trade_no);
        $reqHandler->setParameter("total_fee",	$total_fee);  //总金额
        $reqHandler->setParameter("return_url", $return_url);
        $reqHandler->setParameter("notify_url", $notify_url);
        $reqHandler->setParameter("body",		"付款订单号为：" . $out_trade_no);
        $reqHandler->setParameter("bank_type", "WX");		//银行类型
        //用户ip
        $reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//客户端IP
        $reqHandler->setParameter("fee_type", "1");               //币种
        //系统可选参数
        $reqHandler->setParameter("sign_type", "MD5"); //签名方式，默认为MD5，可选RSA
        $reqHandler->setParameter("service_version", "1.0"); //接口版本号
        $reqHandler->setParameter("input_charset", "UTF-8"); //字符集
        $reqHandler->setParameter("sign_key_index", "1"); //密钥序号

        //业务可选参数
        $reqHandler->setParameter("attach",		"");				//附件数据，原样返回就可以了
        $reqHandler->setParameter("buyer_id",	"");                //买方财付通帐号
        $reqHandler->setParameter("time_start", date("YmdHis"));	//订单生成时间
        $reqHandler->setParameter("time_expire","");				//订单失效时间
        $reqHandler->setParameter("transport_fee", "0");			//物流费用
        $reqHandler->setParameter("product_fee", "");				//商品费用
        $reqHandler->setParameter("goods_tag",	"");				//商品标记

        //请求的URL
        $reqUrl = $reqHandler->getRequestURL();


        $button = '<form id="button_' . $payment->code . '" style="text-align:center;" action="'. $reqHandler->getGateUrl() .'" method="post"  target="_blank">';
        $params = $reqHandler->getAllParameters();
        foreach ($params as $k => $v) {
            $button .= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
        }
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

        $payment = PayLib::get_payment('weixin');

        // 商户号
        $partner = $payment->account;

        /* 密钥 */
        $key = $payment->key;

        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey($key);
        //Yii::log('判断签名' . $resHandler->isTenpaySign(), CLogger::LEVEL_INFO, 'colourlife.core.payment.weixin');
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
                if ($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0" && $queryRes->getParameter(
                        "trade_state"
                    ) == "0" && $queryRes->getParameter("trade_mode") == "1"
                ) {
                    //取结果参数做业务处理
                    $out_trade_no = $queryRes->getParameter("out_trade_no");
                    //财付通订单号
                    $transaction_id = $queryRes->getParameter("transaction_id");
                    //金额,以分为单位
                    $total_fee = $queryRes->getParameter("total_fee");
                    //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
                    $discount = $queryRes->getParameter("discount");

                    //------------------------------
                    //处理业务开始
                    //------------------------------
                    /* 检查支付的金额是否相符 */
                    if (!PayLib::check_money($out_trade_no, $total_fee / 100)) {
                        Yii::log('微信支付银行流水号' . $this->_sn .'检查支付的金额不相符', CLogger::LEVEL_INFO, 'colourlife.core.payment.weixin');
                        return false;
                    }
                    $this->_id = PayLib::get_id_by_sn($out_trade_no);
                    $this->_sn = $out_trade_no;
                    //------------------------------
                    //处理业务完毕
                    //------------------------------
                    Yii::log(
                        '微信支付得到数据订单ID：' . $this->_id . '订单SN：' . $this->_sn . '支付方式：' . $payment->id . '支付金额：' . $total_fee / 100,
                        CLogger::LEVEL_INFO,
                        'colourlife.core.payment.weixin'
                    );

                    if ($type == 'notify' && PayLib::get_status_by_sn($this->_sn) == 0) { //状态为0才能去修改状态
                        PayLog::createPayLog($this->_sn, $total_fee / 100, $payment->id);
                        Yii::log(
                            '微信支付订单SN：' . $this->_sn . '通过状态验证',
                            CLogger::LEVEL_INFO,
                            'colourlife.core.payment.weixin'
                        );
                        /* 改变订单状态 */
                        PayLib::order_paid($out_trade_no, $payment->id);
                    }
                    Yii::log('微信支付订单SN：' . $out_trade_no . '支付成功', CLogger::LEVEL_INFO, 'colourlife.core.payment.weixin');
                    return true;
                } else {
                    Yii::log(
                        "微信支付验证签名失败 或 业务错误信息:trade_state=" . $queryRes->getParameter(
                            "trade_state"
                        ) . ",retcode=" . $queryRes->getParameter("retcode") . ",retmsg=" . $queryRes->getParameter(
                            "retmsg"
                        ),
                        CLogger::LEVEL_INFO,
                        'colourlife.core.payment.weixin'
                    );
                    return false;
                }
            } else {
                Yii::log(
                    '微信支付订单通知查询失败:' . $httpClient->getResponseCode() . ',' . $httpClient->getErrInfo(),
                    CLogger::LEVEL_INFO,
                    'colourlife.core.payment.weixin'
                );
                Yii::log('debug' . $resHandler->getDebugInfo(), CLogger::LEVEL_INFO, 'colourlife.core.payment.weixin');
                return false;
            }
        } else {
            Yii::log('微信支付签名失败', CLogger::LEVEL_INFO, 'colourlife.core.payment.weixin');
            Yii::log('微信支付debug' . $resHandler->getDebugInfo(), CLogger::LEVEL_INFO, 'colourlife.core.payment.weixin');
            return false;
        }
    }

}
