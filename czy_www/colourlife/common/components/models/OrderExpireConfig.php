<?php

class OrderExpireConfig extends Config
{
    public $valueAttributes = array('customerOrderReceive', 'customerOrderClose', 'customerOrderCancel', 'shopOrderCancel', 'propertyOrderCancel', 'parkOrderCancel', 'sellerOrderReceive', 'rechargeOrderCancel');
    public $customerOrderReceive;
    public $customerOrderClose;
    public $customerOrderCancel;
    public $shopOrderCancel;
    public $propertyOrderCancel;
    public $parkOrderCancel;
    public $sellerOrderReceive;
    public $rechargeOrderCancel;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('customerOrderReceive, customerOrderClose,parkOrderCancel,propertyOrderCancel,shopOrderCancel, customerOrderCancel, sellerOrderReceive,rechargeOrderCancel', 'numerical', 'integerOnly' => true, 'on' => 'update'),
            array('customerOrderReceive, customerOrderClose,parkOrderCancel,propertyOrderCancel,shopOrderCancel, customerOrderCancel, sellerOrderReceive,rechargeOrderCancel', 'required', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'customerOrderReceive' => '业主订单自动确认收货',
            'customerOrderClose' => '业主订单自动交易成功',
            'customerOrderCancel' => '业主订单未付款自动取消',
            'shopOrderCancel' => '加盟订单未付款自动取消',
            'propertyOrderCancel' => '物业费未付款自动取消',
            'parkOrderCancel' => '停车费未付款自动取消',
            'sellerOrderReceive' => '加盟订单自动确认收货',
            'rechargeOrderCancel' => '充值订单未付款自动取消',
        ));
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            array(
                'name' => 'customerOrderReceive',
                'type' => 'timeValue',
            ),
            array(
                'name' => 'customerOrderClose',
                'type' => 'timeValue',
            ),
            array(
                'name' => 'customerOrderCancel',
                'type' => 'timeValue',
            ),
            array(
                'name' => 'shopOrderCancel',
                'type' => 'timeValue',
            ),
            array(
                'name' => 'propertyOrderCancel',
                'type' => 'timeValue',
            ),
            array(
                'name' => 'parkOrderCancel',
                'type' => 'timeValue',
            ),
            array(
                'name' => 'sellerOrderReceive',
                'type' => 'timeValue',
            ),
            array(
                'name' => 'rechargeOrderCancel',
                'type' => 'timeValue',
            ),
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
