<?php

class WeixinMobilePayment extends Payment
{
    public $configAttributes = array('account', 'key', 'app_id', 'app_secret', 'app_key');
    public $account;
    public $key;
    public $app_id; //appid
    public $app_secret; //appsecret
    public $app_key; //paysignkey(非appkey)

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('account, key,app_id,app_secret,app_key', 'safe', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'account' => '商户账号',
            'key' => '商户密钥',
            'app_id' => '商户appid',
            'app_secret' => '商户appsecret',
            'app_key' => '商户appkey',
        ));
    }

    protected function afterSave()
    {
        Yii::app()->cache->delete('weiXinToken');
        return parent::afterSave();
    }


}
