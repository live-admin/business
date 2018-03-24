<?php

class netpay extends PayFactory
{

    protected function getInfoActionUrl()
    {
        $url = Yii::app()->params['neypayInfoActionUrl'];
        if (!empty($url))
            return $url;
        return 'http://payment-test.ChinaPay.com/pay/TransGet';
        //return 'https://payment.ChinaPay.com/pay/TransGet';
    }

    /**
     * 生成支付代码
     * @param array $pay 支付信息
     * @param array $payment 支付方式信息
     */

    function get_code($pay, $payment, $returnUrl = '')
    {

        $MerId = trim($payment->account);
        $OrdId = sn2netpaysn($pay->pay_sn);
        $TransAmt = formatamount($this->getAmountNum($pay));
        $CuryId = '156';
        $TransDate = date('Ymd', $pay->create_time);
        $TransType = '0001';
        $Version = '20070129';
        $GateId = ''; //无卡为0001，b2C为空
        $data_vreturnurl = empty($returnUrl) ? PayLib::return_url('netpay') : $returnUrl;
        $bg_url = PayLib::notify_url(basename(__FILE__, '.php'));
        $Priv1 = "test priv1";

        //按次序组合订单信息为待签名串
        $plain = $MerId . $OrdId . $TransAmt . $CuryId . $TransDate . $TransType . $Priv1;
        $plain1 = $MerId . '----' . $OrdId . '----' . $TransAmt . '----' . $CuryId . '----' . $TransDate . '----' . $TransType . '----' . $Priv1;
        //生成签名值，必填

        $chkvalue = $payment->sign($plain);
        //暂停 10 秒
        //sleep(3);
        Yii::log('银联在线支付签名值chkvalue' . $chkvalue, CLogger::LEVEL_INFO, 'colourlife.core.payment.netpay');
        if (empty($chkvalue)) {
            Yii::log( $pay->pay_sn.'银联在线签名失败,签名值' . $plain1, CLogger::LEVEL_INFO, 'colourlife.core.payment.netpay');
            exit;
        }

        $def_url = "<br /><form id= 'button_" . $payment->code . "' style='text-align:center;' method=post action='" . $this->getInfoActionUrl() . "'  target='_blank'>";
        $def_url .= "<input type=HIDDEN name='MerId' value='" . $MerId . "'/>";
        $def_url .= "<input type=HIDDEN name='OrdId' value='" . $OrdId . "'>";
        $def_url .= "<input type=HIDDEN name='TransAmt' value='" . $TransAmt . "'>";
        $def_url .= "<input type=HIDDEN name='CuryId' value='" . $CuryId . "'>";
        $def_url .= "<input type=HIDDEN name='TransDate' value='" . $TransDate . "'>";
        $def_url .= "<input type=HIDDEN name='TransType' value='" . $TransType . "'>";
        $def_url .= "<input type=HIDDEN name='Version' value='" . $Version . "'>";
        $def_url .= "<input type=HIDDEN name='BgRetUrl' value='" . $bg_url . "'>";
        $def_url .= "<input type=HIDDEN name='PageRetUrl' value='" . $data_vreturnurl . "'>";
        $def_url .= "<input type=HIDDEN name='GateId' value='" . $GateId . "'>";
        $def_url .= "<input type=hidden name='Priv1' value='" . $Priv1 . "'>";
        $def_url .= "<input type=HIDDEN name='ChkValue' value='" . $chkvalue . "'>";
        $def_url .= "<input type=submit value='支付'>";

        $def_url .= "</form>";

        return $def_url;
    }


    /**
     * 响应操作

     */

    public function respond($type = 'return')
    {
        $payment = PayLib::get_payment(basename(__FILE__, '.php'));
        $merid = trim($_POST['merid']);
        $orderno = trim($_POST['orderno']);
        $transdate = trim($_POST['transdate']);
        $amount = trim($_POST['amount']);
        $currencycode = trim($_POST['currencycode']);
        $transtype = trim($_POST['transtype']);
        $status = trim($_POST['status']);
        $checkvalue = trim($_POST['checkvalue']);
        $v_gateid = trim($_POST['GateId']);
        $v_Priv1 = trim($_POST['Priv1']);
        $amount = intval($amount);

        $plain = $merid . $orderno . $amount . $currencycode . $transdate . $transtype . $status . $checkvalue;
        if ($payment->verify($plain, $checkvalue)) {
            Yii::log('订单流水号为' . $this->_sn . '验证签名失败', CLogger::LEVEL_INFO, 'colourlife.core.payment.netpay');
            exit;
        }

        /* 检查秘钥是否正确 */
        if ($status == '1001') {
            $order_sn = netpaysn2sn($orderno, $transdate);
            $this->_sn = $order_sn;
            $this->_id = Pay::getPaySn($order_sn);
            $amount = $amount / 100;
            /* 检查支付的金额是否相符 */
            if (!PayLib::check_money($this->_sn, $amount)) {
                Yii::log('银联在线流水号' . $this->_sn .'检查支付的金额不相符', CLogger::LEVEL_INFO, 'colourlife.core.payment.netpay');
                return false;
            }
            if ($type == 'notify' && Pay::getPayStatus($this->_sn) == 0) { //状态为0才能去修改状态
                //添加支付日志
                if(!PayLog::createPayLog($this->_sn, $amount, $payment->id))
                    Yii::log('银联在线添加支付记录失败流水号为：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.kuaiqian');
                else
                {
                    /* 改变订单状态 */
                    PayLib::order_paid($order_sn, $payment->id);
                    Yii::log('银联在线支付流水号为：' . $this->_sn .' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.kuaiqian');
                }
            }
            return true;
        } else {
            Yii::log('银联在线支付失败支付流水号为：' . $this->_sn , CLogger::LEVEL_INFO, 'colourlife.core.payment.netpay');
            return false;
        }
    }
}

/*
*本地订单号转为银联订单号
*/
function sn2netpaysn($orderSN)
{
    if ($orderSN)
        return substr($orderSN, 0, 7) . substr($orderSN, 13);
}

/*
*银联订单号转为本地订单号
*/
function netpaysn2sn($netsn, $transdate)
{
    if ($netsn) {
        return substr($netsn, 0, 7) . substr($transdate, 2) . substr($netsn, 7);
    }
}

/*
*格式化交易金额，以分位单位的12位数字。
*/
function formatamount($amount)
{
    if ($amount) {
        if (!strstr($amount, ".")) {
            $amount = $amount . ".00";
        }
        $amount = str_replace(".", "", $amount);
        $temp = $amount;
        for ($i = 0; $i < 12 - strlen($amount); $i++) {
            $temp = "0" . $temp;
        }
        return $temp;
    }
}
