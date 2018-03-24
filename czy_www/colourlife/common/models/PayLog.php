<?php

/**
 * This is the model class for table "pay_log".
 *
 * The followings are the available columns in table 'pay_log':
 * @property integer $id
 * @property string $sn
 * @property string $amount
 * @property integer $create_time
 * @property integer $payment_id
 */
class PayLog extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'pay_log';
    }

    public function rules()
    {
        return array(
            array('create_time, payment_id', 'numerical', 'integerOnly' => true),
            array('sn', 'length', 'max' => 50),
            array('amount', 'length', 'max' => 10),
            array('id, sn, amount, create_time, payment_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'payment' => array(self::BELONGS_TO, 'Payment', 'payment_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'sn' => '流水号',
            'amount' => '支付金额',
            'create_time' => '创建时间',
            'payment_id' => '支付方式',
        );
    }

    public function search()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('sn', $this->sn, true);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('payment_id', $this->payment_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    static public function createPayLog($sn, $amount, $payment_id=0,$note='')
    {
        $model = new self;
        $model->sn = $sn;
        $model->amount = $amount;
        $model->note = $note;
        $model->payment_id = $payment_id;
        return $model->save();
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

}
