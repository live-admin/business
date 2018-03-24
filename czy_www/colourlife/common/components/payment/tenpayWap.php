<?php

/**
 * 财付通手机插件
 */
class tenpayWap extends PayFactory
{
    public $returnUrl;

    public function __construct()
    {
        //默认返回地址
        $this->returnUrl = F::getOrderUrl('/wap/tenpay');
    }

    /**
     * 生成支付代码
     * @param   array $pay 支付 信息
     * @param   array $payment 支付方式信息
     */
    public function get_code($pay, $payment)
    {
        $lib_path = dirname(__FILE__) . '/tenpay/';
        // 包含库接口文件
        require_once($lib_path . 'RequestHandler.class.php');
        require($lib_path . 'client/ClientResponseHandler.class.php');
        require($lib_path . 'client/TenpayHttpClient.class.php');
        /* 返回的路径 */
        $return_url = $this->returnUrl;
        $notify_url = PayLib::notify_url('tenpayWap');
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
        //设置初始化请求接口，以获得token_id
        //old
		//$reqHandler->setGateUrl("http://wap.tenpay.com/cgi-bin/wappayv2.0/wappay_init.cgi");
		//new
		$reqHandler->setGateUrl("https://www.tenpay.com/app/mpay/wappay_init.cgi");

        $httpClient = new TenpayHttpClient();
        //应答对象
        $resHandler = new ClientResponseHandler();
        //----------------------------------------
        //设置支付参数
        //----------------------------------------
        $reqHandler->setParameter("total_fee", $total_fee); //总金额
        //用户ip
        $reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']); //客户端IP
        $reqHandler->setParameter("ver", "2.0"); //版本类型
        $reqHandler->setParameter("bank_type", "0"); //银行类型，财付通填写0
        $reqHandler->setParameter("callback_url", $return_url); //交易完成后跳转的URL
        $reqHandler->setParameter("bargainor_id", $partner); //商户号
        $reqHandler->setParameter("sp_billno", $out_trade_no); //商户订单号
        $reqHandler->setParameter("notify_url", $notify_url); //接收财付通通知的URL，需绝对路径
        $reqHandler->setParameter("desc", "付款订单号为：" . $out_trade_no);
        $reqHandler->setParameter("attach", "");
        $httpClient->setReqContent($reqHandler->getRequestURL());

        $note = '金额：' . $total_fee . '返回url：' . $return_url . '支付流水号：' . $out_trade_no . '回调URL：' . $notify_url;
        Yii::log('财付通支付送出参数' . $note, CLogger::LEVEL_INFO, 'colourlife.core.payment.get_code.tenpayWap');

        //后台调用
        if ($httpClient->call()) {

            $resHandler->setContent($httpClient->getResContent());
            if ($resHandler->parameters['err_info'] != '') {
                return array('error' => $resHandler->parameters['err_info']);
            }
            //获得的token_id，用于支付请求
            $token_id = $resHandler->getParameter('token_id');
            $reqHandler->setParameter("token_id:", $token_id);
            return array('token_id' => $token_id, 'bargainor_id' => $partner);
        }
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
        require($lib_path . 'WapNotifyResponseHandler.class.php');

        $payment = PayLib::get_payment('tenpayWap');

        // 商户号
        $partner = $payment->account;

        /* 密钥 */
        $key = $payment->key;

        /* 创建支付应答对象 */
        $resHandler = new WapNotifyResponseHandler();
        $resHandler->setKey($key);

        //判断签名
        if ($resHandler->isTenpaySign()) {

            //商户订单号
            $bargainor_id = $resHandler->getParameter("bargainor_id");
            //支付订单号
            $sp_billno = $resHandler->getParameter("sp_billno");

            //财付通交易单号
            $transaction_id = $resHandler->getParameter("transaction_id");
            //金额,以分为单位
            $total_fee = $resHandler->getParameter("total_fee");

            //支付结果
            $pay_result = $resHandler->getParameter("pay_result");

            $this->_sn = $sp_billno;
            $this->_id = Pay::getPaySn($this->_sn);
            $amount = $total_fee / 100;

            if ("0" == $pay_result) {
                /* 检查支付的金额是否相符 */
                if (!PayLib::check_money($this->_sn, $amount)) {
                    Yii::log('财付通wap流水号' . $this->_sn .'检查支付的金额不相符', CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpayWap');
                    return false;
                }

                if ($type == 'notify' && Pay::getPayStatus($this->_sn) == 0) { //状态为0才能去修改状态
                    //添加支付日志
                    if(!PayLog::createPayLog($this->_sn, $amount, $payment->id))
                        Yii::log('财付通wap添加支付记录失败流水号为：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpayWap');
                    else
                    {
                        /* 改变订单状态 */
                        PayLib::order_paid($this->_sn, $payment->id);
                        Yii::log('财付通wap成功支付流水号为：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpayWap');
                    }
                    return true;
                }
            } else {
                Yii::log('财付通wap支付失败流水号为：' . $this->_sn, CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpayWap');
                return false;
            }

        } else {
            //回调签名错误
            Yii::log('财付通wap回调签名错误', CLogger::LEVEL_INFO, 'colourlife.core.payment.tenpayWap');
            return false;
        }
    }

}
