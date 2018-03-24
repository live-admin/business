<?php
/*
 * 支付宝支付
 * @update 20150424 增加alipay支付宝
 */
class ShuangQianPayment extends Payment
{
    public $configAttributes = array('account', 'key');
    public $account;
    public $key;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('account, key', 'safe', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'account' => '商户账号',
            'key' => '商户密钥',
        ));
    }

}
