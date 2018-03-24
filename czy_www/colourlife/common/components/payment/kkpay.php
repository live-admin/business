<?php
/**
 * Created by PhpStorm.
 * User: Roy
 * Date: 2016/10/25
 * Time: 14:19
 */

class kkpay extends PayFactory
{

    private $pay_secret = 'X1Vz1BQxqlijaxAmAD4PPQ7cmM9OOlceeHglph5JjOU';

    /**
     * 响应操作
     * @param string $type notify为回调时调用，return 为返回时调用
     * @return bool
     */
    public function respond($type = 'return')
    {
        if ( !isset($_POST['tno']) ||
            !isset($_POST['code']) ||
            !isset($_GET['sn']) ||
            !isset($_GET['request_time']) ||
            !isset($_GET['sign'])

        )
            return false;

        $this->_sn = $_GET['sn'];
        $requestTime = $_GET['request_time'];
        $signStr = $_GET['sign'];

        $tno = $_POST['tno'];
        $code = $_POST['code'];
        $reason = $_POST['reason'];

        $signParam = array(
            'sn' => $this->_sn,
            'request_time' => $requestTime,
            'sign' => $signStr
        );

        $sign = new Sign($this->pay_secret);

        if ($sign->checkSign($signParam)) {
            if (0 == $code) {

                $orderStatus = Pay::getPayStatus($this->_sn);
                if (1 == $orderStatus)
                    return true;

                if (0 == $orderStatus) { //状态为0才能去修改状态
                    /* 改变订单状态 */
                    PayLib::order_paid_kkpay($this->_sn);
                    Yii::log('T+0支付成功 支付流水号：' . $tno .'，彩之云订单号：' . $this->_sn, CLogger::LEVEL_INFO, 'colourlife.core.payment.kkpay');
                }

                return true;
            }
            else {
                Yii::log('T+0支付失败 支付流水号：' . $tno .'，彩之云订单号：' . $this->_sn, CLogger::LEVEL_INFO, 'colourlife.core.payment.kkpay');
                return false;
            }
        }
        else {
            //回调签名错误
            Yii::log('T+0支付回调签名错误', CLogger::LEVEL_INFO, 'colourlife.core.payment.kkpay');
            return false;
        }

    }

}