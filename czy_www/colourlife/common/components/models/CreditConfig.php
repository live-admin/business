<?php

class CreditConfig extends Config
{
    public $valueAttributes = array('register', 'profile', 'invite', 'reserve', 'orderType', 'order', 'orderRate');
    public $register;
    public $profile;
    public $invite;
    public $reserve;
    public $orderType;
    public $order;
    public $orderRate;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('register, profile, invite, reserve, order', 'numerical', 'integerOnly' => true, 'on' => 'update'),
            array('orderType', 'boolean', 'on' => 'update'),
            array('orderRate', 'numerical', 'on' => 'update'),
            array('register, profile, invite, orderType, reserve, order, orderRate', 'required', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'register' => '注册完成',
            'profile' => '完善资料',
            'invite' => '邀请注册',
            'reserve' => '预订服务',
            'order' => '消费固定赠送',
            'orderRate' => '消费比例赠送',
            'orderType' => '消费积分赠送方式',
        ));
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            'register',
            'profile',
            'invite',
            'reserve',
            array('name' => 'orderType', 'value' => $this->orderType == 0 ? '固定赠送' : '比例赠送', 'type' => 'raw'),
            'order',
            'orderRate',
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
