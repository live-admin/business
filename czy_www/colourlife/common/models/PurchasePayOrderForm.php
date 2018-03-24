<?php

class PurchasePayOrderForm extends CFormModel
{
    public $order_sn = array();
    public $amount = 0;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('order_sn', 'required'),
            // array('amount', 'numerical', 'integerOnly' => true),
            array('order_sn,amount', 'safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array();
    }

    /**创建支付记录
     * @return array|bool|mixed|null
     */
    public function createPay()
    {
        $user_id = Yii::app()->user->id;

        if (is_array($this->order_sn) && count($this->order_sn) > 0) {
            foreach ($this->order_sn as $sn) {
                $order = SN::findContentBySN($sn);

                if($order)
                {
                    //判断订单是否有效订单
                    if ($order->employee_id != $user_id || $order->status != Item::ORDER_AWAITING_PAYMENT)
                        return false;

                    $this->amount += $order->bank_pay;
                }

            }
            if ($this->amount >= 0) {
                //支付号
                $pay_id = Pay::createPay($this->amount,$this->order_sn);
                //更新订单pay_id
                if ($this->update_order_payId($pay_id))
                    return Pay::getPaySn($pay_id);
            }
        }

        return false;
    }

    private function update_order_payId($pay_id)
    {
        $falg = true;
        if (is_array($this->order_sn) && count($this->order_sn) > 0){
            foreach ($this->order_sn as $sn) {
                $order = SN::findContentBySN($sn);
                $order->pay_id = intval($pay_id);
                if(empty($order) || !$order->update())
                    $falg = false;
            }
            return $falg;
        }

        return false;
    }


}
