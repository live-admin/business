<?php

class RechargeServiceConfig extends Config
{
    public $valueAttributes = array('userid', 'userpws', 'KeyStr');
    public $userid;
    public $userpws;
    public $KeyStr;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('userid, userpws, KeyStr', 'required', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'userid' => 'SP编码(以A开头的编号)',
            'userpws' => 'SP接入密码(登陆密码)',
            'KeyStr' => 'MD5 签名密钥',
        ));
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            'userid',
            'userpws',
            'KeyStr',
        ));
    }

    protected function afterFind()
    {
        if (!is_array($this->val)) {
            $this->val = array();
        }
        foreach ($this->val as $name => $value) {
            if (!empty($name) && in_array($name, $this->valueAttributes)) {
                $this->$name = $value;
            }
        }
        return parent::afterFind();
    }

    protected function beforeSave()
    {
        $this->val = $this->getAttributes($this->valueAttributes);
        return parent::beforeSave();
    }

}
