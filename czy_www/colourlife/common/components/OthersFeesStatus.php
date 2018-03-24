<?php

class OthersFeesStatus
{

    private $_status; /*现在的状态*/

    private static $_property_status = array(
        Item::FEES_AWAITING_PAYMENT => "待付款",
        Item::FEES_TRANSACTION_ERROR => '已付款',
        Item::FEES_TRANSACTION_SUCCESS => "交易成功",
        Item::FEES_TRANSACTION_FAIL => '交易失败',
        Item::FEES_TRANSACTION_REFUND => '已退款',
        Item::FEES_TRANSACTION_LACK =>'红包余额不足',
        Item::FEES_CANCEL => "订单已取消",
    );

    private static $_power_status = array(
        Item::FEES_AWAITING_PAYMENT => "待付款",
        Item::FEES_TRANSACTION_ERROR => '已付款',
        Item::FEES_TRANSACTION_SUCCESS => "交易成功",
        Item::FEES_TRANSACTION_FAIL => '交易失败',
        Item::FEES_TRANSACTION_REFUND => '已退款',
        Item::FEES_TRANSACTION_LACK =>'红包余额不足',
        Item::FEES_CANCEL => "订单已取消",
    );

    private static $_parking_status = array(
        Item::FEES_AWAITING_PAYMENT => "待付款",
        Item::FEES_RECHARGEING => "充值中",
        Item::FEES_TRANSACTION_ERROR => '已付款,未续卡',
        Item::FEES_TRANSACTION_REFUND => '已退款',
        Item::FEES_TRANSACTION_SUCCESS => "已续卡",
        Item::FEES_TRANSACTION_LACK =>'红包余额不足',
        Item::FEES_CANCEL => "订单已取消",
    );

    private static $_advance_status = array(
        Item::FEES_AWAITING_PAYMENT => "待付款",
        Item::FEES_TRANSACTION_SUCCESS => "交易成功",
        Item::FEES_TRANSACTION_ERROR => '已付款',
        Item::FEES_TRANSACTION_REFUND => '退款',
        Item::FEES_TRANSACTION_FAIL => '交易失败',
        Item::FEES_TRANSACTION_LACK =>'红包余额不足',
        Item::FEES_CANCEL => "订单已取消",
    );

    //得到状态集合
    static public function StatusList($type)
    {
        if ($type == 'property') {
            return CMap::mergeArray(array('' => '全部'), self::$_property_status);
        } elseif ($type == 'power') {
            return CMap::mergeArray(array('' => '全部'), self::$_power_status);
        } elseif ($type == 'parking') {
            return CMap::mergeArray(array('' => '全部'), self::$_parking_status);
        } elseif ($type == 'advance') {
            return CMap::mergeArray(array('' => '全部'), self::$_advance_status);
        } else {
            return CMap::mergeArray(array('' => '全部'), array());
        }
    }

    static public function getStatusName($type, $status)
    {
        if ($type == 'property') {
            return self::$_property_status[$status];
        } elseif ($type == 'power') {
            return self::$_power_status[$status];
        } elseif ($type == 'parking') {
            return self::$_parking_status[$status];
        } elseif ($type == 'advance') {
            return self::$_advance_status[$status];
        } else {
            throw new CHttpException(400, "未知的状态");
        }
    }

    //当前状态能有的操作
    private static $_others_fees_action = array(
        'property' => array(
            Item::FEES_AWAITING_PAYMENT => array('awaitingPayment','redAwaitingPayment',), //"待付款",
            Item::FEES_TRANSACTION_ERROR => array('paymentSuccess', 'paymentRefund',), //'已付款,',
            Item::FEES_TRANSACTION_REFUND => array('refundAwaiting'), //'已退款',
            Item::FEES_TRANSACTION_FAIL => array('failSuccess', 'failRefund'), //'交易失败',
            Item::FEES_TRANSACTION_LACK => array('redFailSuccess','redFailRefund'),//红包

            Item::FEES_TRANSACTION_SUCCESS => array(), //"交易成功",
            Item::FEES_CANCEL => array(),
        ),
        'parking' => array(
            Item::FEES_AWAITING_PAYMENT => array('awaitingPayment',), //"待付款",
            Item::FEES_TRANSACTION_ERROR => array('paymentSuccess', 'paymentRefund'), //'已付款,未续卡',
            Item::FEES_TRANSACTION_REFUND => array('refundAwaiting'), //'已退款',
            Item::FEES_TRANSACTION_FAIL => array('failSuccess', 'failRefund'), //'交易失败',
            Item::FEES_TRANSACTION_LACK => array('redFailSuccess','redFailRefund'),//红包
            Item::FEES_TRANSACTION_SUCCESS => array(), //"已续卡",
            Item::FEES_CANCEL => array(), //"订单已取消",
        ),
        'advance' => array(
            Item::FEES_AWAITING_PAYMENT => array('awaitingPayment','awaitingSuccess'),//待付款->已付款"待付款"->交易成功,
            Item::FEES_TRANSACTION_ERROR => array('paymentRefund','paymentSuccess'),//已付款->退款 '已付款'->交易成功,
            Item::FEES_TRANSACTION_REFUND => array('refundAwaiting'),//'退款'->待付款,
            Item::FEES_TRANSACTION_FAIL => array('failSuccess','failRefund'),//交易失败->已退款,
            Item::FEES_TRANSACTION_LACK => array('redFailSuccess','redFailRefund'),//红包
            Item::FEES_CANCEL => array('failRefund'),//"订单已取消",
            Item::FEES_TRANSACTION_SUCCESS => array(),//"交易成功",
        ),
        'power' => array(
            Item::FEES_AWAITING_PAYMENT => array('awaitingPayment','redAwaitingPayment',), //"待付款",
            Item::FEES_TRANSACTION_ERROR => array('paymentSuccess', 'paymentRefund',), //'已付款,',
            Item::FEES_TRANSACTION_REFUND => array('refundAwaiting'), //'已退款',
            Item::FEES_TRANSACTION_FAIL => array('failSuccess', 'failRefund'), //'交易失败',
            Item::FEES_TRANSACTION_LACK => array('redFailSuccess','redFailRefund'),//红包

            Item::FEES_TRANSACTION_SUCCESS => array(), //"交易成功",
            Item::FEES_CANCEL => array(),
        )
    );

    //进行操作后对应的状态
    private static $_action_by_status = array(
       /* 'payment'  => Item::FEES_TRANSACTION_ERROR,//"已付款",
        'awaiting' => Item::FEES_AWAITING_PAYMENT,//待付款'',
        'success'  => Item::FEES_TRANSACTION_SUCCESS,//"交易成功",
        'refund'   => Item::FEES_TRANSACTION_REFUND,//'已退款',*/
        'redFailSuccess' => Item::FEES_TRANSACTION_SUCCESS,//红包交易失败到交易成功
        'redFailRefund' => Item::FEES_TRANSACTION_REFUND,//红包交易失败到退款
        'awaitingPayment'  => Item::FEES_TRANSACTION_ERROR,//"待付款到已付款",
        'awaitingSuccess' => Item::FEES_TRANSACTION_SUCCESS,//待付款到交易成功
        'failSuccess' => Item::FEES_TRANSACTION_SUCCESS,//交易失败到交易成功
        'failRefund' => Item::FEES_TRANSACTION_REFUND,//交易失败到已退款
        'paymentSuccess' => Item::FEES_TRANSACTION_SUCCESS,//已付款到交易成功
        'paymentRefund' => Item::FEES_TRANSACTION_REFUND,//已付款到退款
        'refundAwaiting' => Item::FEES_AWAITING_PAYMENT,//退款到待付款
        'redAwaitingPayment'=> Item::FEES_TRANSACTION_ERROR,//红包待付款到已付款
    );

    static public function getNextAction($status, $_model)
    {
        return self::$_others_fees_action[$_model][$status];
    }

    static public function getStatusByAction($action)
    {
        return self::$_action_by_status[$action];
    }


    public static function getAdminButtonCompetence($status,$model)
    {
        $_model = str_replace(array('Fees','Fee'),'',$model);
        $competence = self::$_others_fees_action[$_model][$status];
        if(!empty($competence)){
            $flag = false;
            foreach($competence as $val){
                if(Yii::app()->user->checkAccess('op_backend_' .$model . '_'.$val)){
                    return $flag = true;
                }
            }
            return $flag;
        }
        return false;
    }
}
