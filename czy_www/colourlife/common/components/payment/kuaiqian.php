<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 13-7-30
 * Time: 上午9:25
 * To change this template use File | Settings | File Templates.
 */

class kuaiqian extends PayFactory
{

    protected function getInfoActionUrl()
    {
        $url = Yii::app()->params['kuaiqianInfoActionUrl'];
        if (!empty($url))
            return $url;
        return 'https://sandbox.99bill.com/gateway/recvMerchantInfoAction.htm';
    }


    /**
     * 生成支付代码
     * @param   Pay $pay订单信息
     * @param   Payment $payment 支付方式信息
     * @param   string $bankId 银行直连id
     * @param   string $returnUrl 自定义返回页面
     */
    function get_code($pay, $payment, $returnUrl = '', $bankId = '')
    {
        $merchant_acctid = trim($payment->account . '01'); //人民币账号 不可空
        $input_charset = 1; //字符集 默认1=utf-8
        /* 返回的路径 */
        $page_url = empty($returnUrl) ? PayLib::return_url((basename(__FILE__, '.php'))) : $returnUrl;
        $this->setKuaiqianReturnUrl($pay,$page_url);//设置返回的URL
        $bg_url = PayLib::notify_url(basename(__FILE__, '.php'));
        $version = 'v2.0';
        $language = 1;
        $sign_type = 4; //签名类型,该值为4，代表PKI加密方式,该参数必填。
        $payer_name = '';
        $payer_contact_type = '';
        $payer_contact = '';
        $order_id = $pay->pay_sn; //商户订单号 不可空
        $order_amount = $this->getAmount($pay); //商户订单金额 不可空
        $order_time = date('YmdHis', $pay->create_time); //商户订单提交时间 不可空 14位
        $product_name = '';
        $product_num = '';
        $product_id = '';
        $product_desc = '';
        $ext1 = $pay->id;
        $ext2 = '';
        $pay_type = ($bankId == '') ? '00' : '10'; //支付方式 不可空
        $bank_id = ($bankId == '') ? '' : strtoupper($bankId); //如果存在就显示，否则变换大写
        $redo_flag = '0';
        $pid = '';

        /* 生成加密签名串 请务必按照如下顺序和规则组成加密串！*/
        $signmsgval = '';
        $signmsgval = $this->append_param($signmsgval, "inputCharset", $input_charset);
        $signmsgval = $this->append_param($signmsgval, "pageUrl", $page_url);
        $signmsgval = $this->append_param($signmsgval, "bgUrl", $bg_url);
        $signmsgval = $this->append_param($signmsgval, "version", $version);
        $signmsgval = $this->append_param($signmsgval, "language", $language);
        $signmsgval = $this->append_param($signmsgval, "signType", $sign_type);
        $signmsgval = $this->append_param($signmsgval, "merchantAcctId", $merchant_acctid);
        $signmsgval = $this->append_param($signmsgval, "payerName", $payer_name);
        $signmsgval = $this->append_param($signmsgval, "payerContactType", $payer_contact_type);
        $signmsgval = $this->append_param($signmsgval, "payerContact", $payer_contact);
        $signmsgval = $this->append_param($signmsgval, "orderId", $order_id);
        $signmsgval = $this->append_param($signmsgval, "orderAmount", $order_amount);
        $signmsgval = $this->append_param($signmsgval, "orderTime", $order_time);
        $signmsgval = $this->append_param($signmsgval, "productName", $product_name);
        $signmsgval = $this->append_param($signmsgval, "productNum", $product_num);
        $signmsgval = $this->append_param($signmsgval, "productId", $product_id);
        $signmsgval = $this->append_param($signmsgval, "productDesc", $product_desc);
        $signmsgval = $this->append_param($signmsgval, "ext1", $ext1);
        $signmsgval = $this->append_param($signmsgval, "ext2", $ext2);
        $signmsgval = $this->append_param($signmsgval, "payType", $pay_type);
        $signmsgval = $this->append_param($signmsgval, "bankId", $bank_id);
        $signmsgval = $this->append_param($signmsgval, "redoFlag", $redo_flag);
        $signmsgval = $this->append_param($signmsgval, "pid", $pid);
        $signmsg = $payment->send_sign($signmsgval); //签名字符串 不可空

        //button页面提交的id
        $js_id = ($pay_type == 00) ? '"button_' . $payment->code . '"' : '"button_' . strtolower($bank_id) . '"';
        $def_url = '<div style="text-align:center"><form name="kqPay" id=' . $js_id . ' style="text-align:center;" method="post" action="' . $this->getInfoActionUrl() . '"  target="_blank">';
        $def_url .= "<input type='hidden' name='inputCharset' value='" . $input_charset . "' />";
        $def_url .= "<input type='hidden' name='bgUrl' value='" . $bg_url . "' />";
        $def_url .= "<input type='hidden' name='pageUrl' value='" . $page_url . "' />";
        $def_url .= "<input type='hidden' name='version' value='" . $version . "' />";
        $def_url .= "<input type='hidden' name='language' value='" . $language . "' />";
        $def_url .= "<input type='hidden' name='signType' value='" . $sign_type . "' />";
        $def_url .= "<input type='hidden' name='signMsg' value='" . $signmsg . "' />";
        $def_url .= "<input type='hidden' name='merchantAcctId' value='" . $merchant_acctid . "' />";
        $def_url .= "<input type='hidden' name='payerName' value='" . $payer_name . "' />";
        $def_url .= "<input type='hidden' name='payerContactType' value='" . $payer_contact_type . "' />";
        $def_url .= "<input type='hidden' name='payerContact' value='" . $payer_contact . "' />";
        $def_url .= "<input type='hidden' name='orderId' value='" . $order_id . "' />";
        $def_url .= "<input type='hidden' name='orderAmount' value='" . $order_amount . "' />";
        $def_url .= "<input type='hidden' name='orderTime' value='" . $order_time . "' />";
        $def_url .= "<input type='hidden' name='productName' value='" . $product_name . "' />";
        $def_url .= "<input type='hidden' name='payType' value='" . $pay_type . "' />";
        $def_url .= "<input type='hidden' name='productNum' value='" . $product_num . "' />";
        $def_url .= "<input type='hidden' name='productId' value='" . $product_id . "' />";
        $def_url .= "<input type='hidden' name='productDesc' value='" . $product_desc . "' />";
        $def_url .= "<input type='hidden' name='ext1' value='" . $ext1 . "' />";
        $def_url .= "<input type='hidden' name='ext2' value='" . $ext2 . "' />";
        $def_url .= "<input type='hidden' name='bankId' value='" . $bank_id . "' />";
        $def_url .= "<input type='hidden' name='redoFlag' value='" . $redo_flag . "' />";
        $def_url .= "<input type='hidden' name='pid' value='" . $pid . "' />";
        //$def_url .= "<input type='submit' name='submit' value='快钱支付 ' />";
        $def_url .= "</form></div></br>";

        return $def_url;
    }

    /**
     * 响应操作
     * @param string $type notify为回调时调用，return 为返回时调用
     * @return bool
     */
    public function respond($type = 'return')
    {
        $payment = PayLib::get_payment(basename(__FILE__, '.php'));
        $merchant_acctid = $payment->account . '01'; //人民币账号 不可空
        $get_merchant_acctid = trim($_REQUEST['merchantAcctId']);
        $pay_result = trim($_REQUEST['payResult']);
        $version = trim($_REQUEST['version']);
        $language = trim($_REQUEST['language']);
        $sign_type = trim($_REQUEST['signType']);
        $pay_type = trim($_REQUEST['payType']);
        $bank_id = trim($_REQUEST['bankId']);
        $order_id = trim($_REQUEST['orderId']);
        $order_time = trim($_REQUEST['orderTime']);
        $order_amount = trim($_REQUEST['orderAmount']);
        $deal_id = trim($_REQUEST['dealId']);
        $bank_deal_id = trim($_REQUEST['bankDealId']);
        $deal_time = trim($_REQUEST['dealTime']);
        $pay_amount = trim($_REQUEST['payAmount']);
        $fee = trim($_REQUEST['fee']);
        $ext1 = trim($_REQUEST['ext1']);
        $ext2 = trim($_REQUEST['ext2']);
        $err_code = trim($_REQUEST['errCode']);
        $sign_msg = trim($_REQUEST['signMsg']);

        //生成加密串。必须保持如下顺序。
        $merchant_signmsgval = '';
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "merchantAcctId", $merchant_acctid);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "version", $version);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "language", $language);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "signType", $sign_type);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "payType", $pay_type);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "bankId", $bank_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "orderId", $order_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "orderTime", $order_time);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "orderAmount", $order_amount);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "dealId", $deal_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "bankDealId", $bank_deal_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "dealTime", $deal_time);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "payAmount", $pay_amount);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "fee", $fee);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "ext1", $ext1);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "ext2", $ext2);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "payResult", $pay_result);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "errCode", $err_code);

        $ok = $payment->recieve_sign($_REQUEST['signMsg'], $merchant_signmsgval);

        $this->_sn = $order_id;
        // $this->_id = Pay::getPaySn($order_id);
        $amount = $pay_amount / 100;

        //首先对获得的商户号进行比对
        if ($get_merchant_acctid != $merchant_acctid) {
            //商户号错误
            Yii::log('快钱流水号' . $this->_sn .'检查商户号错误', CLogger::LEVEL_INFO, 'colourlife.core.payment.kuaiqian');
            return false;
        }

        if ($ok == 1) {
            if ($pay_result == 10 || $pay_result == 00) {
                /* 检查支付的金额是否相符 */
                if (!PayLib::check_money($this->_sn, $amount)) {
                    Yii::log('快钱流水号' . $this->_sn .'检查支付的金额不相符 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.kuaiqian');
                    return false;
                }
                if ($type == 'notify' && Pay::getPayStatus($this->_sn) == 0) { //状态为0才能去修改状态
                    //添加支付日志
                    if(!PayLog::createPayLog($this->_sn, $amount, $payment->id))
                        Yii::log('快钱支付添加支付记录失败流水号为：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.kuaiqian');
                    else
                    {
                        /* 改变订单状态 */
                        PayLib::order_paid($this->_sn, $payment->id);
                        Yii::log('快钱支付成功支付流水号为：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.kuaiqian');
                    }
                }
                return true;
            } else {
                //'支付结果失败';
                Yii::log('快钱流水号' . $this->_sn .'检查支付结果失败', CLogger::LEVEL_INFO, 'colourlife.core.payment.kuaiqian');
                return false;
            }
        } else {
            //'密钥校对错误';
            Yii::log('快钱流水号' . $this->_sn .'检查密钥校对错误', CLogger::LEVEL_INFO, 'colourlife.core.payment.kuaiqian');
            return false;
        }
    }


    /**
     * 将变量值不为空的参数组成字符串
     * @param   string $strs 参数字符串
     * @param   string $key 参数键名
     * @param   string $val 参数键对应值
     */
    function append_param($strs, $key, $val)
    {
        if ($strs != "") {
            if ($key != '' && $val != '') {
                $strs .= '&' . $key . '=' . $val;
            }
        } else {
            if ($val != '') {
                $strs = $key . '=' . $val;
            }
        }
        return $strs;
    }

}
