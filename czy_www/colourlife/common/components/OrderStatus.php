<?php
class OrderStatus
{
    private static $_order_status = array(
        Item::ORDER_AWAITING_PAYMENT => "待付款",//已下单，待付款
        Item::ORDER_AWAITING_GOODS => "待发货",//买家已付款待发货
        Item::ORDER_AWAITING_CONFIRM => "待收货",//已发货，待收货
        Item::ORDER_TRANSACTION_SUCCESS => "已收货",//买家已收货
        Item::ORDER_BALANCE_DEDUCT_FAILED => "交易失败",//支付失败/抵扣红包或积分失败
        Item::ORDER_TRANSACTION_SUCCESS_CLOSE => "待结算",//等待结算
        Item::ORDER_CANCEL_CLOSE => "订单已取消",
        Item::ORDER_CANCEL_REFUND=>"已退款(手动)",
        Item::ORDER_CANCEL_REFUND_APART=>"部分退款(手动)",
    );
    private static $_op_status = array(
        'buyer' => array(
            Item::ORDER_AWAITING_PAYMENT => array('buyer_pay', 'buyer_order_cancel'), //1.【买家】在线支付  21.【买家】取消
            Item::ORDER_AWAITING_GOODS => array('buyer_unfilled_refund'), //【买家】申请退款
            Item::ORDER_AWAITING_CONFIRM => array('buyer_receive','buyer_shipped_refund'), //5.【买家】确认收货  【买家】申请退款
            Item::ORDER_TRANSACTION_SUCCESS => array('buyer_apply_return'), //7.【买家】申请退货
            Item::ORDER_BALANCE_DEDUCT_FAILED => array(),
            Item::ORDER_TRANSACTION_SUCCESS_CLOSE => array(),
            Item::ORDER_CANCEL_CLOSE => array(),
        ),
        'shop' => array(
            Item::ORDER_AWAITING_PAYMENT => array(),
            Item::ORDER_AWAITING_GOODS => array('shop_send'),//发货
            Item::ORDER_AWAITING_CONFIRM => array(),
            Item::ORDER_TRANSACTION_SUCCESS => array(),
            Item::ORDER_BALANCE_DEDUCT_FAILED => array(),
            Item::ORDER_TRANSACTION_SUCCESS_CLOSE => array(),
            Item::ORDER_CANCEL_CLOSE => array(),
        ),
        'cwy' => array(
            Item::ORDER_AWAITING_PAYMENT => array('cwy_disposal'),//平台付款
            Item::ORDER_AWAITING_GOODS => array(),
            Item::ORDER_AWAITING_CONFIRM => array(),
            Item::ORDER_TRANSACTION_SUCCESS => array(),
            Item::ORDER_BALANCE_DEDUCT_FAILED => array('cwy_disposal','cwy_reimburse'),//平台修改为已付款(再次扣红包，积分)、申请退款
            Item::ORDER_TRANSACTION_SUCCESS_CLOSE => array(),
            Item::ORDER_CANCEL_CLOSE => array(),
        ),
        'seller' => array(
            Item::ORDER_AWAITING_PAYMENT => array('seller_pay', 'seller_cancel'),
            Item::ORDER_AWAITING_GOODS => array(),
            Item::ORDER_AWAITING_CONFIRM => array('seller_receive'),
            Item::ORDER_TRANSACTION_SUCCESS => array('seller_return'),
            Item::ORDER_BALANCE_DEDUCT_FAILED => array(),
            Item::ORDER_CANCEL_CLOSE => array(),
        ),
        'supplier' => array(
            Item::ORDER_AWAITING_PAYMENT => array(),
            Item::ORDER_AWAITING_GOODS => array('supplier_send_good'),
            Item::ORDER_AWAITING_CONFIRM => array(),
            Item::ORDER_TRANSACTION_SUCCESS => array(),
            Item::ORDER_BALANCE_DEDUCT_FAILED => array(),
            Item::ORDER_CANCEL_CLOSE => array(),
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
        if(isset($opList[$_model])){
            return isset($opList[$_model][$status])?$opList[$_model][$status]:array();
        }else{
            return array();
        }
    }

    /**
     * 参数说明：$Order_id=>订单ID,$user_id=>操作人ID,$user_model=>操作人的模型字符串,$status=>订单要修改为该状态
     * $note=>订单备注(体现在订单日志记录)
     *
     * */
    static public function changeOrderStatus($order_id, $user_id, $user_model, $status, $note = '')
    {
        if (empty($order_id)) {
            throw new CHttpException('404', '无效的操作对象');
        } else {
            $order = Order::model()->findByPk($order_id);
            if (empty($order)) {
                throw new CHttpException('404', '无效的操作对象');
            } else {
                $oldStatus = $order->status;

                $order->status = $status;

                $log = new OrderLog();
                $log->order_id = $order_id;
                $log->user_model = $user_model;
                $log->user_id = $user_id;
                $log->status = $status;
                $str = "";
                switch (strtolower($user_model)) {
                    case "customer":
                        $str .= "买家";
                        break;
                    case "shop":
                        $str .= "商家";
                        break;
                    case "employee":
                        $str .= "物业平台";
                        break;
                }
                $str .= " 将订单状态从 " . self::getStatusName($oldStatus) . " 修改为 " . self::getStatusName($status);
                $log->note = $str."，备注:".$note;
                if ($log->save() && $order->save()) {
                    return true;
                } else {
                    return false;
                }

            }
        }
    }
    
    /*
     * @version 提货券订单状态的改变
     * @param int $order_id 提货券订单id
     * @param int $user_id
     * @param int $status
     */
    static public function changeThqOrderStatus($order_id, $user_id, $status)
    {
        if (empty($order_id)) {
            throw new CHttpException('404', '无效的操作对象');
        } else {
            $thqOrder = ThqOrder::model()->findByPk($order_id);
            if (empty($thqOrder)) {
                throw new CHttpException('404', '无效的操作对象');
            } else {
                $oldStatus = $thqOrder->status;

                $thqOrder->status = $status;

                $log = new OrderLog();
                $log->order_id = $order_id;
                $log->user_model = 'shop';
                $log->user_id = $user_id;
                $log->status = $status;
                $str = "";
                $str .= " 将订单状态从 " . ThqOrder::model()->getThqStatusName($oldStatus) . " 修改为 " . ThqOrder::model()->getThqStatusName($status);
                $log->note = $str;
                if ($log->save() && $thqOrder->save()) {
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
            $order = Order::model()->findByPk($order_id);
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
            Item::ORDER_TRANSACTION_SUCCESS_CLOSE,
            //Item::ORDER_EXCEPTIONAL_CLOSE,
            Item::ORDER_CANCEL_CLOSE,
            Item::SELLER_ORDER_ARBITRATE_SUCCESS_CLOSE,
            Item::SELLER_ORDER_REFUND_SUCCESS_CLOSE

        );
        if (in_array($status, $array)) {
            //throw new CHttpException('404', '已结束的订单，无法继续进行操作.');
            return false;
        } else {
            return true;
        }
    }


    private static $_purchase_order_status = array(
        Item::SELLER_ORDER_APPLY_BACK => "退货审核",
        Item::SELLER_ORDER_AWAITING_REFUND => "待退款",
        Item::SELLER_ORDER_AWAITING_GOODS => "待发货",
        Item::SELLER_ORDER_AWAITING_CONFIRM => "已发货",
        Item::SELLER_ORDER_APPLY_BACK_REJECT => "拒绝退货",
        Item::SELLER_ORDER_AWAITING_ARBITRATE => "退款仲裁",//，商家拒绝收货后的仲裁",同意=>退款
        Item::SELLER_ORDER_APPLY_ARBITRATE => "退货仲裁",// 买家申请退货，商家拒绝后的状态", 同意=》代发货
        Item::SELLER_ORDER_ARBITRATE_SUCCESS_CLOSE => "退货失败",
        Item::SELLER_ORDER_REFUND_SUCCESS_CLOSE => "退款成功",
    );

    private static $_op_purchase_status = array(
        'cwy' => array(
            Item::SELLER_ORDER_APPLY_BACK => array(),
            Item::SELLER_ORDER_AWAITING_REFUND => array('cwy_refund'),
            Item::SELLER_ORDER_AWAITING_GOODS => array(),
            Item::SELLER_ORDER_AWAITING_CONFIRM => array(),
            Item::SELLER_ORDER_APPLY_BACK_REJECT => array(),
            Item::SELLER_ORDER_AWAITING_ARBITRATE => array('cwy_assent_return', 'cwy_refuse_return'),//"同意退货、拒绝退货
            Item::SELLER_ORDER_APPLY_ARBITRATE => array('cwy_assent_apply', 'cwy_refuse_apply'),//"同意申请、拒绝申请
            Item::SELLER_ORDER_ARBITRATE_SUCCESS_CLOSE => array(),
            Item::SELLER_ORDER_REFUND_SUCCESS_CLOSE => array(),
        ),
        'seller' => array(
            Item::SELLER_ORDER_APPLY_BACK => array(),//"退货审核"
            Item::SELLER_ORDER_AWAITING_REFUND => array(),//"待退款"
            Item::SELLER_ORDER_AWAITING_GOODS => array('seller_send_good'),//【加盟商】退货发货
            Item::SELLER_ORDER_AWAITING_CONFIRM => array(),//"已发货"
            Item::SELLER_ORDER_APPLY_BACK_REJECT => array('seller_apply_arbitrate'),//"申请仲裁
            Item::SELLER_ORDER_AWAITING_ARBITRATE => array(),//"退货仲裁"
            Item::SELLER_ORDER_APPLY_ARBITRATE => array(),//"申请仲裁"
            Item::SELLER_ORDER_ARBITRATE_SUCCESS_CLOSE => array(),//"退款失败"
            Item::SELLER_ORDER_REFUND_SUCCESS_CLOSE => array(),//"退款成功"
        ),
        'supplier' => array(
            Item::SELLER_ORDER_APPLY_BACK => array('supplier_assent_return', 'supplier_refuse_return'),//【供应商】同意退货  【供应商】不同意
            Item::SELLER_ORDER_AWAITING_REFUND => array(),//"待退款"
            Item::SELLER_ORDER_AWAITING_GOODS => array(),//"待发货"
            Item::SELLER_ORDER_AWAITING_CONFIRM => array('supplier_assent_receive','supplier_refuse_receive'),//"已发货"
            Item::SELLER_ORDER_APPLY_BACK_REJECT => array(),//"拒绝退货"
            Item::SELLER_ORDER_AWAITING_ARBITRATE => array(),//"退货仲裁"
            Item::SELLER_ORDER_APPLY_ARBITRATE => array(),//"申请仲裁"
            Item::SELLER_ORDER_ARBITRATE_SUCCESS_CLOSE => array(),//"退款失败"
            Item::SELLER_ORDER_REFUND_SUCCESS_CLOSE => array(),//"退款成功"
        ),
    );

    static public function getPurchaseStatusName($status)
    {
        if (empty(self::$_purchase_order_status[$status])) {
            return "未知的状态";
        }
        return self::$_purchase_order_status[$status];
    }

    static public function purchaseStatusName($status)
    {
        $purchase = new self();
        return $purchase->getPurchaseStatusName($status);
    }

    static public function getPurchaseStatusNames()
    {
        return self::$_purchase_order_status;
    }

    static public function getNextPurchaseStatus($status, $_model)
    {
        $opList = self::$_op_purchase_status;
        if(isset($opList[$_model])){
            return isset($opList[$_model][$status])?$opList[$_model][$status]:array();
        }else{
            return array();
        }
    }

    /**
     * 参数说明：
     * $order_id:加盟退货订单ID
     * $status:加盟订单要修改为什么状态
     * $modelName:操作人的model，shop or cwy
     * */
    static public function changePurchaseStatus($order_id, $status, $modelName = '',$note="")
    {
        $purchase = PurchaseReturn::model()->findByPk($order_id);
        $oldStatus = $purchase->status;

        $purchase->status = $status;

        $desc = "";
        switch ($modelName) {
            case 'seller':
                $desc = "加盟商";
                $modelName = 'shop';
                break;
            case 'supplier':
                $desc = "供应商";
                $modelName = 'shop';
                break;
            case 'cwy':
                $desc = "物业平台";
                $modelName = 'employee';
                break;
        }

        $desc .= " 将加盟退货订单状态从 " . self::getPurchaseStatusName($oldStatus) . " 修改为 " . self::getPurchaseStatusName($status);
        $desc .= ",备注：".$note;

        if ($purchase->save() && $purchase->addPurchaseReturnLog($desc, $modelName, $status)) {
            return true;
        } else {
            return false;
        }
    }

    static public function ValidatePurchaseStatus($order_id, $modelStr, $action)
    {
        if (empty($order_id)) {
            return false;
        } else {
            $order = PurchaseReturn::model()->findByPk($order_id);
            if (empty($order)) {
                return false;
            } else {
                if (in_array($order->status, array(Item::SELLER_ORDER_ARBITRATE_SUCCESS_CLOSE, Item::SELLER_ORDER_REFUND_SUCCESS_CLOSE))) {
                    return false;
                }
                $nextArr = self::getNextPurchaseStatus($order->status, $modelStr);
                if (in_array($action, $nextArr)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

}

