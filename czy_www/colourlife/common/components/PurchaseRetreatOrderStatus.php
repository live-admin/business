<?php
class PurchaseRetreatOrderStatus
{
    //退款状态
    private static $_refund_status = array(
        Item::RETREAT_ORDER_BUYER_APPLY => "退款审核",//买家申请退款
        Item::RETREAT_ORDER_AWAITING_GOODS => "待退款",//商家/仲裁同意[平台]
        Item::RETREAT_ORDER_AWAITING_ARBITRATE => "退款仲裁",//同意=>待退款,不同意=>退款失败,仲裁都是针对是否同意买家
        Item::RETREAT_ORDER_REFUND_FAILED => "退款失败",
        Item::RETREAT_ORDER_REFUND_SUCCESS => "退款成功",//平台结算
    );

    //退货状态
    private static $_return_status = array(
        Item::RETREAT_ORDER_BUYER_APPLY => "退货审核",//买家申请退货
        Item::RETREAT_ORDER_REFUND_REJECT => "拒绝退货",
        Item::RETREAT_ORDER_APPLY_ARBITRATE => "申请仲裁",//买家申请商家拒绝后的状态
        Item::RETREAT_ORDER_AWAITING_BACK_GOODS => "待发货",
        Item::RETREAT_ORDER_AWAITING_BACK_CONFIRM => "待收货",
        Item::RETREAT_ORDER_AWAITING_ARBITRATE => "退货仲裁",//商家拒绝收货后的状态
        Item::RETREAT_ORDER_REFUND_FAILED => "退货失败",
        Item::RETREAT_ORDER_AWAITING_GOODS => "待退款",
        Item::RETREAT_ORDER_REFUND_SUCCESS => "退款成功",
    );

    private static $_op_refund_status = array(
        'buyer' => array(
            Item::RETREAT_ORDER_BUYER_APPLY => array(),
            Item::RETREAT_ORDER_AWAITING_GOODS => array(),//业主申请退款
            Item::RETREAT_ORDER_AWAITING_ARBITRATE => array(),
            Item::RETREAT_ORDER_REFUND_FAILED => array(),
            Item::RETREAT_ORDER_REFUND_SUCCESS => array(),
        ),
        'shop' => array(
            Item::RETREAT_ORDER_BUYER_APPLY => array('shop_agree_refund','shop_refuse_refund'),//退款审核
            Item::RETREAT_ORDER_AWAITING_GOODS => array(),
            Item::RETREAT_ORDER_AWAITING_ARBITRATE => array(),
            Item::RETREAT_ORDER_REFUND_FAILED => array(),
            Item::RETREAT_ORDER_REFUND_SUCCESS => array(),
        ),
        'cwy' => array(
            Item::RETREAT_ORDER_BUYER_APPLY => array(),//物业后台退款
            Item::RETREAT_ORDER_AWAITING_GOODS => array("cwy_refund"),//已退款=》退款成功，改主状态。锁订单
            Item::RETREAT_ORDER_AWAITING_ARBITRATE => array('cwy_refuse_refund','cwy_agree_refund'),//不同意商家拒绝;仲裁同意
            Item::RETREAT_ORDER_REFUND_FAILED => array(),//解锁订单
            Item::RETREAT_ORDER_REFUND_SUCCESS => array(),
        ),
    );

    private static $_op_return_status = array(
        'buyer' => array(
            Item::RETREAT_ORDER_BUYER_APPLY => array(),//"退货审核"
            Item::RETREAT_ORDER_REFUND_REJECT => array("buyer_apply_arbitrate"),//"拒绝退货",
            Item::RETREAT_ORDER_APPLY_ARBITRATE => array(),//"申请仲裁",//买家申请商家拒绝后的状态
            Item::RETREAT_ORDER_AWAITING_BACK_GOODS => array("buyer_return_goods"),//"待发货",
            Item::RETREAT_ORDER_AWAITING_BACK_CONFIRM => array(),//"待收货",
            Item::RETREAT_ORDER_AWAITING_ARBITRATE => array(),//"退货仲裁",//商家拒绝收货后的状态
            Item::RETREAT_ORDER_REFUND_FAILED => array(),//"退货失败",
            Item::RETREAT_ORDER_AWAITING_GOODS => array(),//"待退款",
            Item::RETREAT_ORDER_REFUND_SUCCESS => array(),//"退款成功",
        ),
        'shop' => array(
            Item::RETREAT_ORDER_BUYER_APPLY => array('shop_assent_return','shop_refuse_return'),//"退货审核",//买家申请退货
            Item::RETREAT_ORDER_REFUND_REJECT => array(),//"拒绝退货",
            Item::RETREAT_ORDER_APPLY_ARBITRATE => array(),//"申请仲裁",//买家申请商家拒绝后的状态
            Item::RETREAT_ORDER_AWAITING_BACK_GOODS => array(),//"待发货",
            Item::RETREAT_ORDER_AWAITING_BACK_CONFIRM => array("shop_assent_receive","shop_refuse_receive"),//"待收货",
            Item::RETREAT_ORDER_AWAITING_ARBITRATE => array(),//"退货仲裁",//商家拒绝收货后的状态
            Item::RETREAT_ORDER_REFUND_FAILED => array(),//"退货失败",
            Item::RETREAT_ORDER_AWAITING_GOODS => array(),//"待退款",
            Item::RETREAT_ORDER_REFUND_SUCCESS => array(),//"退款成功",
        ),
        'cwy' => array(
            Item::RETREAT_ORDER_BUYER_APPLY => array(),//"退货审核",//买家申请退货
            Item::RETREAT_ORDER_REFUND_REJECT => array(),//"拒绝退货",
            Item::RETREAT_ORDER_APPLY_ARBITRATE => array("cwy_assent_return","cwy_refuse_return"),//"申请仲裁",//买家申请商家拒绝后的状态
            Item::RETREAT_ORDER_AWAITING_BACK_GOODS => array(),//"待发货",
            Item::RETREAT_ORDER_AWAITING_BACK_CONFIRM => array(),//"待收货",
            Item::RETREAT_ORDER_AWAITING_ARBITRATE => array("cwy_assent_back","cwy_refuse_back"),//"退货仲裁",//商家拒绝收货后的状态
            Item::RETREAT_ORDER_REFUND_FAILED => array(),//"退货失败",
            Item::RETREAT_ORDER_AWAITING_GOODS => array("cwy_reimburse"),//"待退款",
            Item::RETREAT_ORDER_REFUND_SUCCESS => array(),//"退款成功",
        ),
    );

    //获取退款状态名字
    static public function getRefundStatusName($status)
    {
        $statusList = self::$_refund_status;
        if (!isset($statusList[$status])) {
            return "未知状态";
        }
        return self::$_refund_status[$status];
    }

    static public function getReturnStatusName($status)
    {
        $statusList = self::$_return_status;
        if (!isset($statusList[$status])) {
            return "未知状态";
        }
        return $statusList[$status];
    }

    //返回退款状态名字
    static public function refundStatusName($status)
    {
        $retreatOrderStatus = new self();
        return $retreatOrderStatus->getRefundStatusName($status);
    }

    //返回退款状态数组
    static public function getRefundStatusNames()
    {
        return self::$_refund_status;
    }

    //返回退款状态数组
    static public function getReturnStatusNames()
    {
        return self::$_return_status;
    }

    static public function checkStatus($status)
    {
        $array = array(
            Item::RETREAT_ORDER_REFUND_FAILED,
            Item::RETREAT_ORDER_REFUND_SUCCESS
        );
        if (in_array($status, $array)) {
            return false;
        } else {
            return true;
        }
    }

    static public function getNextStatus($status, $_model)
    {
        $opList = self::$_op_refund_status;
        if(isset($opList[$_model])){
            return isset($opList[$_model][$status])?$opList[$_model][$status]:array();
        }else{
            return array();
        }
    }
    //验证退款步骤
    static public function ValidateStatus($order_id, $modelStr, $action)
    {
        if (empty($order_id)) {
            return false;
        } else {
            $order = PurchaseRetreatOrder::model()->findByPk($order_id);
            if (empty($order)) {
                return false;
            } else {
                if (!self::checkStatus($order->status)) {
                    return false;
                }
                $nextArr = self::getNextStatus($order->status, $modelStr);
                if (in_array($action, $nextArr)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    static public function getNextReturnStatus($status, $_model)
    {
        $opList = self::$_op_return_status;
        if(isset($opList[$_model])){
            return isset($opList[$_model][$status])?$opList[$_model][$status]:array();
        }else{
            return array();
        }
    }
    //验证退款步骤
    static public function ValidateReturnStatus($order_id, $modelStr, $action)
    {
        if (empty($order_id)) {
            return false;
        } else {
            $order = PurchaseRetreatOrder::model()->findByPk($order_id);
            if (empty($order)) {
                return false;
            } else {
                $nextArr = self::getNextReturnStatus($order->status, $modelStr);
                if (in_array($action, $nextArr)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * 参数说明：$Order_id=>订单ID,$user_id=>操作人ID,$user_model=>操作人的模型字符串,$status=>订单要修改为该状态
     * $note=>订单备注(体现在订单日志记录)
     *
     * */
    static public function changeOrderStatus($order_id, $user_id, $status, $note = '')
    {
        if (empty($order_id)) {
            throw new CHttpException('404', '无效的操作对象');
        } else {
            $order = PurchaseRetreatOrder::model()->findByPk($order_id);
            if (empty($order)) {
                throw new CHttpException('404', '无效的操作对象');
            } else {
                $oldStatus = $order->status;

                $order->status = $status;

                $log = new RetreatOrderLog();
                $log->retreat_id = $order_id;
                $log->user_id = $user_id;
                $log->status = $status;
                $statusName = $order->type==0?self::getRefundStatusName($status):self::getReturnStatusName($status);
                $oldStatusName = $order->type==0?self::getRefundStatusName($oldStatus):self::getReturnStatusName($oldStatus);
                $str = "员工 将订单状态从 " . $oldStatusName . " 修改为 " . $statusName;
                $log->note = $str."，备注:".$note;
                if ($log->save() && $order->save()) {
                    return true;
                } else {
                    dump($log->getErrors().$order->getErrors());
                    return false;
                }

            }
        }
    }

}

