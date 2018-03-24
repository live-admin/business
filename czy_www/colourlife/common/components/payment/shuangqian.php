<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/1/4
 * Time: 17:02
 */
/**
 * 支付宝支付插件
 */
class shuangqian extends PayFactory
{
    //protected $payURL = 'http://218.4.234.150:8999/creditsslpay/sslpayment'; // 测试
    protected $payURL = 'https://www.95epay.cn/sslpayment'; // 正式

    public function get_code($pay, $payment)
    {

        //dump($pay);
        $MerNo = $payment->account;
        $MD5key = $payment->key;

        /* 返回的路径 */
        $ReturnURL = F::getOrderUrl('/wap/ShuangQian');
        $NotifyURL = PayLib::notify_url('shuangqian');

        $Amount = $this->getAmountNum($pay);
        $BillNo = $pay->pay_sn;

        $signParam = array(
            'MerNo'       => $MerNo,
            'BillNo'      => $BillNo,
            'Amount'      => $Amount,
            'ReturnURL'   => $ReturnURL
        );

        $MD5info = $this->makeSign($signParam, $MD5key);

        $queryParam = array(
            'payURL'      => $this->payURL,
            'MerNo'       => $MerNo,
            'BillNo'      => $BillNo,
            'Amount'      => $Amount,
            'ReturnURL'   => $ReturnURL,
            'NotifyURL'   => $NotifyURL,
            'MD5info'     => $MD5info,
            'MerRemark'   => "付款订单号为：" . $BillNo,
            'products'    => "彩生活缴费",
        );

        $submitURL = F::getOrderUrl('/wap/ShuangQian/submit');

        return Yii::app()->curl->buildUrl($submitURL, $queryParam);
    }

    /**
     * 响应操作
     * @param string $type notify为回调时调用，return 为返回时调用
     * @return bool
     */
    public function respond($type = 'return')
    {
        if ( !isset($_POST["BillNo"]) ||
            !isset($_POST["Amount"]) ||
            !isset($_POST["Succeed"]) ||
            !isset($_POST["MD5info"]) ||
            !isset($_POST["Result"]) ||
            !isset($_POST["Orderno"])
        )
            return false;

        $payment = PayLib::get_payment('shuangqian');
        $MerNo = $payment->account;
        $MD5key = $payment->key;

        $BillNo          =     $_POST["BillNo"];
        $Amount          =     $_POST["Amount"];
        $Succeed         =     $_POST["Succeed"];
        $MD5info         =     $_POST["MD5info"];
        $Result          =     $_POST["Result"];
        //$MerNo           =     $_POST['MerNo'];
        $MerRemark       =  	 $_POST['MerRemark'];
        $Orderno       =  	 $_POST['Orderno'];

        $signParam = array(
            'MerNo'       => $MerNo,
            'BillNo'      => $BillNo,
            'Amount'      => $Amount,
            'Succeed'     => $Succeed
        );

        $this->_sn = $BillNo;

        $md5sign = $this->makeSign($signParam, $MD5key);

        if ($MD5info == $md5sign) {
            if ($Succeed == '88') {
                /* 检查支付的金额是否相符 */
                if (!PayLib::check_money($this->_sn, $Amount)) {
                    Yii::log('双乾流水号：' . $Orderno .'，彩之云订单号：' . $this->_sn .'检查支付的金额不相符', CLogger::LEVEL_INFO, 'colourlife.core.payment.shuangqian');
                    return false;
                }

                $orderStatus = Pay::getPayStatus($this->_sn);
                if (1 == $orderStatus)
                    return true;

                if (0 == $orderStatus) { //状态为0才能去修改状态
                    //添加支付日志
                    if(!PayLog::createPayLog($this->_sn, $Amount, $payment->id))
                        Yii::log('双乾添加支付记录失败 支付流水号：' . $Orderno .'，彩之云订单号：' . $this->_sn .' 支付金额：' . $Amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.shuangqian');
                    else
                    {
                        /* 改变订单状态 */
                        PayLib::order_paid($this->_sn, $payment->id);
                        Yii::log('双乾支付成功 支付流水号：' . $Orderno .'，彩之云订单号：' . $this->_sn .' 支付金额：' . $Amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.shuangqian');
                    }
                }

                return true;
            }
            else {
                Yii::log('双乾支付失败 支付流水号：' . $Orderno .'，彩之云订单号：' . $this->_sn, CLogger::LEVEL_INFO, 'colourlife.core.payment.shuangqian');
                return false;
            }
        }
        else {
            //回调签名错误
            Yii::log('双乾回调签名错误', CLogger::LEVEL_INFO, 'colourlife.core.payment.shuangqian');
            return false;
        }
    }

    public function makeSign($signParams, $MD5key)
    {
        $signStr = "";
        ksort($signParams);

        foreach ($signParams as $key => $val) {

            $signStr .= sprintf("%s=%s&", $key, $val);

        }

        return strtoupper(md5($signStr. strtoupper(md5($MD5key))));
    }

}