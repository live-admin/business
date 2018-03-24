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
 * @property integer $in_bill_state
 * @property integer $in_bill_time
 * @property integer $out_bill_state
 * @property integer $out_bill_time
 * @property string $return_url
 */
class Pay extends CActiveRecord
{
    public $sn;
    public $note;
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'pay';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('payment_id, status, create_time, pay_time', 'numerical', 'integerOnly' => true),
            array('pay_sn', 'safe'),
            array('amount', 'length', 'max' => 10),
            array('id, pay_sn, payment_id, status, amount, create_time, pay_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'orderPay' => array(self::BELONGS_TO, 'order', 'pay_id'),
            'feesPay' => array(self::BELONGS_TO, 'others_fees', 'pay_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'pay_sn' => 'Pay Sn',
            'payment_id' => 'Payment',
            'status' => 'Status',
            'amount' => 'Amount',
            'create_time' => 'Create Time',
            'pay_time' => 'Pay Time',
        );
    }

    public function search()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('pay_sn', $this->pay_sn);
        $criteria->compare('payment_id', $this->payment_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('pay_time', $this->pay_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 创建支付号
     */
    static private function createPaySn($order_sn)
    {
        /* 选择一个随机的方案 */
      /*  mt_srand((double)microtime() * 1000000);
        return date('Ymd') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);*/
        $order_sn = $order_sn[0];
        $num = substr($order_sn,0,7);
        return SN::write_create_sn($num);
    }

    /**
     * 修改支付表的状态
     * @param $pay_id 支付ID
     * @param int $payment_id 支付方式 ID
     * @return bool
     */
    static public function updatePay($pay_id, $payment_id = 0)
    {
        $pay_id = intval($pay_id);
        $payment_id = intval($payment_id);
        $pay = Pay::model()->findByPk($pay_id);
        if (Pay::model()->updateByPk($pay_id, array(
            'status' => Item::PAY_STATUS_OK,
            'pay_time' => time(),
            'payment' => $payment_id,
        ))
        ) { //修改状态成功，添加支付日志
            return PayLog::createPayLog($pay->pay_sn, $pay->amount, $payment_id);
        }
        return false;
    }

    /**
     * 修改支付表的状态
     * @param $sn 支付流水号
     * @param int $payment_id 支付方式 ID
     * @return bool
     */
    static public function updatePayBySn($sn, $payment_id = 0)
    {
        $payment_id = intval($payment_id);
        $pay = self::getPayModel($sn);
        if (!empty($pay)) {
            $pay->status = Item::PAY_STATUS_OK;
            $pay->pay_time = time();
            $pay->payment_id = $payment_id;
            if ($pay->update())
                return PayLog::createPayLog($sn, $pay->amount, $payment_id);
        }
        return false;
    }

    /**
     * 修改入账状态
     * @param $sn 支付流水号
     * @param $note 备注
     * @return bool
     */
    static public function updateInBillStateBySn($sn, $note = '')
    {
        $pay = self::getPayModel($sn);
        if (!empty($pay)) {
            $pay->in_bill_state = 1; //对账成功
            $pay->in_bill_time = time(); //对账时间
            $note = ($note != '') ? $note : '入账对账成功！';
            if ($pay->update())
                return PayLog::createPayLog($sn, $pay->amount, $pay->payment_id, $note);
        }
        return false;
    }

    /**
     * 修改出账状态
     * @param $sn 支付流水号
     * @param $note 备注
     * @return bool
     */
    static public function updateOutBillStateBySn($sn, $note = '')
    {
        $pay = self::getPayModel($sn);
        if (!empty($pay)) {
            $pay->out_bill_state = 1; //对账成功
            $pay->out_bill_time = time(); //对账时间
            $note = ($note != '') ? $note : '出账对账成功！';
            if ($pay->update())
                return PayLog::createPayLog($sn, $pay->amount, $pay->payment_id, $note);
        }
        return false;
    }

    /**
     * 查询入账状态
     * @param $sn 支付流水号
     * @param $note 备注
     * @return bool
     */
    static public function getInBillState($sn)
    {
        $pay = self::getPayModel($sn);
        if (!empty($pay)) {
            return $pay->in_bill_state;
        }
        return false;
    }

    /**
     * 修改出账状态
     * @param $sn 支付流水号
     * @param $note 备注
     * @return bool
     */
    static public function getOutBillState($sn)
    {
        $pay = self::getPayModel($sn);
        if (!empty($pay)) {
            return $pay->out_bill_state;
        }
        return false;
    }

    /**
     * 创建支付单号
     */
    static public function createPay($amount,$order_sn)
    {
        $model = new self;
        $model->pay_sn = self::createPaySn($order_sn);
        $model->amount = $amount;
        if ($model->save())
            return $model->id;
        else
            return false;
    }

    /**
     * 创建支付单号
     */
    static public function oldCreatePaySn($amount, $pay_sn)
    {
        $model = new self;
        $model->amount = $amount;
        $payModel = $model->getModel($pay_sn);
        if (!empty($payModel)) {
            $model->create_time = time();
            if ($payModel->update())
                return $payModel->id;
        } else {
            $model->pay_sn = $pay_sn;
            if ($model->save())
                return $model->id;
        }

        return false;
    }

    /**
     * 老订单创建支付单号
     */
    static public function oldCreatePay($sn, $amount, $status, $pay_time)
    {
        $model = new self;
        $model->pay_sn = $sn;
        $model->status = $status;

        $model->amount = $amount;
        $model->pay_time = $pay_time;
        if ($model->save())
            return $model->id;
        else
            return false;

    }


    /**
     *  取得流水号
     * @param $pay_id
     * @return array|mixed|null
     */
    static public function getPaySn($pay_id)
    {
        $model = self::model()->findByPk($pay_id);
        return $model->pay_sn;
    }

    /**
     *  取得流水号
     * @param $pay_id
     * @return array|mixed|null
     */
    static public function getPayStatus($pay_sn)
    {
        $model = self::getPayModel($pay_sn);
        return $model->status;
    }

    /**
     *  根据SN取得支付对象
     * @param $pay_sn
     * @return Pay
     */
    static public function getPayModel($pay_sn)
    {
        $pay = self::model()->find('pay_sn=:sn', array(':sn' => $pay_sn));
        if (empty($pay))
            throw new CHttpException(403, "支付单号不存在，请联系管理员");
        if (empty($pay))
            return '';

        return $pay;
    }


    static public function getPayModelNew($id)
    {
        $pay = self::model()->findByPk($id);
        if (empty($pay))
            throw new CHttpException(403, "支付单号不存在，请联系管理员");
        
        return $pay;
    }



    /**
     *  根据SN取得支付对象
     * @param $pay_sn
     * @return Pay
     */
    static public function getModel($pay_sn)
    {
        $pay = self::model()->find('pay_sn=:sn', array(':sn' => $pay_sn));
        if (empty($pay))
            return false;

        return $pay;
    }

    static public function getOrder($pay_sn)
    {
        $payModel = self::getModel($pay_sn);
        if (!empty($payModel))
            $payId = $payModel->id;
        else
            return false;

        //转到不同的订单
        $order = Order::model()->findAll('pay_id=:pay_id', array(':pay_id' => $payId));
        $fees = OthersFees::model()->findAll('pay_id=:pay_id', array(':pay_id' => $payId));
        $purchaseOrder = PurchaseOrder::model()->findAll('pay_id=:pay_id', array(':pay_id' => $payId));
        $thirdOrder = ThirdFees::model()->findAll('pay_id=:pay_id', array(':pay_id' => $payId));
        $redpacketFeesOrder = RedpacketFees::model()->findAll('pay_id=:pay_id', array(':pay_id' => $payId));
        $insureOrder = InsureOrder::model()->findAll('pay_id=:pay_id', array(':pay_id' => $payId));
        return Cmap::mergeArray($order, $fees, $purchaseOrder, $thirdOrder, $redpacketFeesOrder, $insureOrder);
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    //业主是否全额支付
    public function isFullPay()
    {
        if ($this->amount == 0)
            return true;
        else
            return false;
    }
}
