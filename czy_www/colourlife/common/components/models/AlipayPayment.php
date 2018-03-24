<?php
/*
 * 支付宝支付
 * @update 20150424 增加alipay支付宝
 */
class AlipayPayment extends Payment
{
    public $configAttributes = array('account', 'key', 'seller_id', 'key_path');
    public $account;
    public $key;
    public $seller_id;
    public $key_path;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('account, seller_id, key, key_path', 'safe', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'account' => '商户账号',
            'key' => '商户密钥',
            'seller_id'=>'卖家支付宝帐号',
            'key_path'=>'密钥路径',
        ));
    }

}
