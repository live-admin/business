<?php

class UPOPPayment extends Payment
{
    public $configAttributes = array('selfName', 'account', 'key');
    public $selfName;
    public $account;
    public $key;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('selfName, account, key', 'safe', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'selfName' => '商户名',
            'account' => '商户账号',
            'key' => '商户密钥',
        ));
    }

}
