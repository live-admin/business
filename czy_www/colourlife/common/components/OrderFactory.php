<?php

class OrderFactory
{
    protected $order_id;//订单ID
    protected $order_sn;//订单SN
    protected $status;//订单状态
    protected $switch;//红包开关
    protected $note;//备注
    protected $payment_id;//支付方式
    protected $model;//订单模型
    protected $balance;//订单红包

    private static $instance = array();

    /**
     * 生成 Order 单例
     * @param string $OrderType :自定义支付类型,为空表示采用系统默认;
     * @return pay object
     */
    static function getInstance($OrderType = '')
    {
        if (empty(self::$instance[$OrderType])) {
            $OType = $OrderType ? $OrderType : 'CustomerOrder';
            $orderObjClass = trim($OType);
            //加载对应的支付组件
            $payFile = dirname(__FILE__) . "/order/" . $OType . '.php';
            //dump($payFile);
            if (file_exists($payFile)) {
                Yii::import("common.components.order.*");
            }
            if (!class_exists($orderObjClass)) {
                //写日志
                throw new CHttpException(404, '您请求的页面不存在！');
            }

            self::$instance[$OrderType] = new $orderObjClass();
            return self::$instance[$OrderType];
        } else {
            return self::$instance[$OrderType];
        }
    }

    //活动
    public function active()
    {
        $order_sn = $this->order_sn;
        $order_id = $this->order_id;
        $model = PayLib::get_model_by_sn($order_sn);
        try {
            //
            //其他活动start
            //
            //$luckyOper1=new LuckyOperation();
            $order = $model::model()->find('sn=:sn', array(':sn' => $order_sn));
            if (($model == 'SellerOrder' || $model == 'CustomerOrder') && $order->status == Item::ORDER_AWAITING_GOODS) //订单的状态为支付成功，及进行增加抽奖机会
            {
                //彩之云app活动
                //$b=$luckyOper1->execute($paramin);
                $customerName = $order->getBuyerName('Customer', $order->buyer_id);
                LuckyDoAdd::order($order->buyer_id, $customerName, $order_id);

            } else if ($model != 'PurchaseOrder' && ($order->status == Item::FEES_TRANSACTION_SUCCESS || $order->status == Item::FEES_TRANSACTION_ERROR)) //其他支付成功，及进行增加抽奖机会
            {
                //彩之云app活动
                //$b=$luckyOper1->execute($paramin);
                LuckyDoAdd::orderFrees($order->customer_id, $order->customerName, $order_id);
            }
            //
            //其他活动end
            //
        } catch (Exception $e) {
            Yii::log("活动异常导致，订单SN为：'{$this->order_sn}' ", CLogger::LEVEL_INFO, 'colourlife.core.OrderFactory');
        }
    }


}