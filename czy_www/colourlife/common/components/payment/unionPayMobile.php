<?php

/**
 * 银联手机插件
 */
class unionPayMobile extends PayFactory
{
    public function __construct()
    {
    }

    /**
     * 生成支付代码
     * @param   array $order 订单信息
     * @param   array $payment 支付方式信息
     */
    public function get_code($order, $payment)
    {
    }

    /**
     * 响应操作
     */
    public function respond()
    {
        Yii::import('common.api.UnionPayMobileApi');
        $union = UnionPayMobileApi::getInstance();
        $input = file_get_contents('php://input');
        $return = $union->getNotifyInfo($input); // 自动回应
        Yii::log('银联支付返回值：' . var_export($return, true), CLogger::LEVEL_INFO, 'colourlife.core.pay.notify.UnionPay');
        if (isset($return)) {
            Yii::log('respCode：' . $return['respCode'], CLogger::LEVEL_INFO, 'colourlife.core.pay.notify.UnionPay');
            if ($return['respCode'] == '0000') {
                $sn = $return['merchantOrderId']; //订单号
                $amount = $return['merchantOrderAmt'] / 100; //订单金额

                $this->_sn = $sn;
                $this->_id = Pay::getPaySn($this->_sn);
                $payment_id = intval($return['transType']);

                Yii::log('银联支付手机返回订单号：' . $sn . '订单金额' . ($amount) . '支付类型' . $return['transType'], CLogger::LEVEL_INFO, 'colourlife.core.pay.notify.UnionPay');

                if (Pay::getPayStatus($this->_sn) == 0) { //状态为0才能去修改状态
                    //添加支付日志
                    if (!PayLog::createPayLog($this->_sn, $amount, $payment_id))
                        Yii::log('银联支付手机添加支付记录失败流水号为：' . $this->_sn . ' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.UnionPay');
                    else {
                        /* 改变订单状态 */
                        PayLib::order_paid($this->_sn, $payment_id);
                        Yii::log('银联支付手机成功支付流水号为：' . $this->_sn . ' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.UnionPay');
                    }
                    return true;
                } else {
                    Yii::log('银联支付手机支付失败流水号为：' . $this->_sn, CLogger::LEVEL_INFO, 'colourlife.core.payment.UnionPay');
                    return false;
                }

            } else {
                Yii::log('银联支付手机出错：' . $return['respCode'], CLogger::LEVEL_INFO, 'colourlife.core.pay.fail');
                return false;
            }
        }
    }

}

