<?php

class PurchaseOrderStatus
{
    private static $_order_status = array(

        Item::ORDER_PURCHASE_PAYMENT => "未付款", //已下单，待付款
        Item::ORDER_PURCHASE_GOODS => "待发货", //买家已付款待发货

        Item::ORDER_PURCHASE_APPROVAL_START => "已提交审批", //已提交审批
        Item::ORDER_PURCHASE_APPROVAL_SUCCESS => "审批成功", //审批成功

        Item::ORDER_PURCHASE_CONFIRM => "待收货", //已发货，待收货
        Item::ORDER_PURCHASE_TRANSACTION_SUCCESS => "已收货", //买家已收货
        Item::ORDER_PURCHASE_DEDUCT_FAILED => "交易失败", //支付失败/抵扣红包或积分失败
        Item::ORDER_PURCHASE_SUCCESS_CLOSE => "待结算", //等待结算
        Item::ORDER_PURCHASE_CANCEL_CLOSE => "取消订单",
    );
    private static $_op_status = array(
        'buyer' => array(
            Item::ORDER_PURCHASE_PAYMENT => array('buyer_pay', 'buyer_order_cancel'), //1.【买家】在线支付  21.【买家】取消
            Item::ORDER_PURCHASE_APPROVAL_SUCCESS => array(),//审批成功
            Item::ORDER_PURCHASE_GOODS => array('buyer_unfilled_refund'), //【买家】申请退款
            Item::ORDER_PURCHASE_CONFIRM => array('buyer_receive', 'buyer_shipped_refund'), //5.【买家】确认收货  【买家】申请退款
            Item::ORDER_PURCHASE_TRANSACTION_SUCCESS => array('buyer_apply_return'), //7.【买家】申请退货
            Item::ORDER_PURCHASE_DEDUCT_FAILED => array(),
            Item::ORDER_PURCHASE_SUCCESS_CLOSE => array(),
            Item::ORDER_PURCHASE_CANCEL_CLOSE => array(),
        ),
        'shop' => array(
            Item::ORDER_PURCHASE_PAYMENT => array(),
            Item::ORDER_PURCHASE_APPROVAL_SUCCESS => array('shop_send'),//审批成功了,发货
            Item::ORDER_PURCHASE_GOODS => array('shop_send'), //发货
            Item::ORDER_PURCHASE_CONFIRM => array(),
            Item::ORDER_PURCHASE_TRANSACTION_SUCCESS => array(),
            Item::ORDER_PURCHASE_DEDUCT_FAILED => array(),
            Item::ORDER_PURCHASE_SUCCESS_CLOSE => array(),
            Item::ORDER_PURCHASE_CANCEL_CLOSE => array(),
        ),
        'cwy' => array(
            Item::ORDER_PURCHASE_PAYMENT => array('cwy_disposal'), //平台付款
            Item::ORDER_PURCHASE_APPROVAL_SUCCESS => array(),//审批成功
            Item::ORDER_PURCHASE_GOODS => array(),
            Item::ORDER_PURCHASE_CONFIRM => array(),
            Item::ORDER_PURCHASE_TRANSACTION_SUCCESS => array(),
            Item::ORDER_PURCHASE_DEDUCT_FAILED => array('cwy_disposal', 'cwy_reimburse'), //平台修改为已付款(再次扣红包，积分)、申请退款
            Item::ORDER_PURCHASE_SUCCESS_CLOSE => array(),
            Item::ORDER_PURCHASE_CANCEL_CLOSE => array(),
        ),
    );

    static public function getStatusName($status)
    {
        $statusList = self::$_order_status;
        if (!isset($statusList[$status])) {
            return "未知状态";
        }
        return self::$_order_status[$status];
    }

    static public function statusName($status)
    {
        $orderStatus = new self();
        return $orderStatus->getStatusName($status);
    }

    static public function getStatusNames()
    {
        return self::$_order_status;
    }

    static public function getNextStatus($status, $_model)
    {
        $opList = self::$_op_status;
        if (isset($opList[$_model])) {
            return isset($opList[$_model][$status]) ? $opList[$_model][$status] : array();
        } else {
            return array();
        }
    }

    /**
     * 参数说明：$Order_id=>订单ID,$user_id=>操作人ID,$status=>订单要修改为该状态
     * $note=>订单备注(体现在订单日志记录)
     *
     * */
    static public function changeOrderStatus($order_id, $employee_id, $status, $note = '')
    {
        if (empty($order_id)) {
            throw new CHttpException('404', '无效的操作对象');
        } else {
            $order = PurchaseOrder::model()->findByPk($order_id);
            if (empty($order)) {
                throw new CHttpException('404', '无效的操作对象');
            } else {
                $oldStatus = $order->status;

                $order->status = $status;

                $log = new PurchaseOrderLog();
                $log->order_id = $order_id;
                $log->employee_id = $employee_id;
                $log->status = $status;
                $str = "";
                $str .= "员工 将订单状态从 " . self::getStatusName($oldStatus) . " 修改为 " . self::getStatusName($status);
                $log->note = $str . "，备注:" . $note;
                if ($log->save() && $order->save()) {
                    return true;
                } else {
                    return false;
                }

            }
        }
    }

    static public function ValidateStatus($order_id, $modelStr, $action)
    {
        if (empty($order_id)) {
            return false;
        } else {
            $order = PurchaseOrder::model()->findByPk($order_id);
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

    static public function checkStatus($status)
    {
        $array = array(
            Item::ORDER_PURCHASE_CANCEL_CLOSE,
            //Item::ORDER_EXCEPTIONAL_CLOSE,
            Item::ORDER_PURCHASE_CANCEL_CLOSE,

        );
        if (in_array($status, $array)) {
            //throw new CHttpException('404', '已结束的订单，无法继续进行操作.');
            return false;
        } else {
            return true;
        }
    }

}

