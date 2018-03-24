<?php

class PayFactory
{
    private static $instance = array();

    //支付页面
    // public function get_code()
    //{}

    //支付返回页面
    //public function respond()
    // {}

    protected $_id;
    protected $_sn;
    protected $_return_url;

    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getSn()
    {
        return $this->_sn;
    }

    public function getReturnUrl()
    {
        return $this->_return_url;
    }

    public function setReturnUrl($returnUrl)
    {
        $this->_return_url = $returnUrl;
    }

    public function setKuaiqianReturnUrl($pay,$return_url)
    {
        $pay->return_url = $return_url;
        return $pay->update();
    }

    public function getKuaiqianReturnUrl($pay)
    {
        return $pay->return_url;
    }

    /**
     *取订单的结算金额
     * */
    protected function getAmount($pay)
    {
            return floatval($pay->amount) * 100;
    }

    /**
     *取订单的结算金额
     * */
    protected function getAmountNum($pay)
    {
            return floatval($pay->amount);
    }

    /**
     *取订单的商品描述
     * */
    function getGoodsDesc($pay_id)
    {
        $str = '';
        $order_sn = PayLib::getPayOrderSn($pay_id);
        foreach($order_sn as $sn){
            $model = PayLib::get_model_by_sn($sn);
            $order = PayLib::get_modelObject_by_sn($sn);
            switch ($model)
            {
                case 'OthersPropertyFees':
                    $str .= $order->getCustomerName().'业主为'.$order->getCommunityName().'小区缴物业费';
                    break;
                case 'OthersAdvanceFees':
                    $str .= $order->getCustomerName().'业主为'.$order->getCommunityName().'小区预缴物业费';
                    break;
                case 'OthersParkingFees':
                    $str .= $order->getCustomerName().'业主为'.$order->getCommunityName().'小区，牌照为：'.$order->getCarNumber().'的私家车缴纳停车费';
                    break;
                case 'OthersVirtualRecharge':
                    $str .= $order->getCustomerName().'业主手机充值';
                    break;
                case 'SellerOrder':
                    $goodsListName = $order->getGoodNameDescByOrder();
                    $str .= $goodsListName;
                    break;
                case 'CustomerOrder':
                    $goodsListName = $order->getGoodNameDescByOrder();
                    $str .= $goodsListName;
                    break;
                default:
                    $goodsListName = $order->getGoodNameDescByOrder();
                    $str .= $goodsListName;
                    break;
            }
        }
        return  F::sub($str,16);
    }

    /**
     * 生成 pay 单例
     * @param string $payType :自定义支付类型,为空表示采用系统默认;
     * @return pay object
     * @update 20150425 add alipay
     */
    static function getInstance($payType = '')
    {
        if (empty(self::$instance[$payType])) {
            // ucfirst:将字符串第一个字符改大写
            //$PType = ucfirst($payType ? $payType : 'tenpay');
            $PType = $payType ? $payType : 'tenpay';
            $payObjClass = trim($PType);
            //加载对应的支付组件
            $payFile = dirname(__FILE__) . "/payment/" . $PType . '.php';
            if (file_exists($payFile)) {
                Yii::import("common.components.payment.*");
            }
            if (!class_exists($payObjClass)) {
                //写日志
                throw new CHttpException(404, '请求的页面不存在.');
            }

            self::$instance[$payType] = new $payObjClass();
            return self::$instance[$payType];
        } else {
            return self::$instance[$payType];
        }
    }

}