<?php

/**
 * This is the model class for table "pay".
 *
 * The followings are the available columns in table 'pay':
 * @property integer $id
 * @property integer $pay_sn
 * @property integer $payment_id
 * @property integer $status
 * @property string $amount
 * @property integer $create_time
 * @property integer $pay_time
 * @property string $return_url
 */
class PayOrderForm extends CFormModel
{
    public $order_sn = array();
    public $amount = 0;
    public $pay_sn ; //不为空时，pay_sn为指定的

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
            array('order_sn,amount,pay_sn', 'safe'),
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
    public function createPay($user_id='')
    {
        if(empty($user_id)){
            $user_id = Yii::app()->user->id;
        }

        if (is_array($this->order_sn) && count($this->order_sn) > 0) {
            foreach ($this->order_sn as $sn) {
                $order = SN::findContentBySN($sn);

                if ($order) {
                    $buyer_id=0;
                    if(isset($order->buyer_id)){
                        $buyer_id= $order->buyer_id;
                    }else if(isset($order->customer_id)){
                        $buyer_id=$order->customer_id;
                    }
                    //判断订单是否有效订单
                    if ( $buyer_id != $user_id || !($order->status == Item::ORDER_AWAITING_PAYMENT)) return false;
                    $this->amount += $order->bank_pay;
                }

            }
            if ($this->amount >= 0) {
                //支付号
                if (isset($this->pay_sn)){
					//查到有sn有则修改支付时间，无则b创建一条记录
                    $pay_id = Pay::oldCreatePaySn($this->amount, $this->pay_sn);
				}else{
					//新增一条记录到pay表
                    $pay_id = Pay::createPay($this->amount,$this->order_sn);
				}
                if ($this->update_order_payId($pay_id))
                    return Pay::getPaySn($pay_id);

            }
        }

        return false;
    }

    private function update_order_payId($pay_id)
    {
        $falg = true;
        if (is_array($this->order_sn) && count($this->order_sn) > 0) {
            foreach ($this->order_sn as $sn) {
                $order = SN::findContentBySN($sn);
                $order->pay_id = intval($pay_id);
                if (empty($order) || !$order->update())
                    $falg = false;
            }
            return $falg;
        }

        return false;
    }
}
