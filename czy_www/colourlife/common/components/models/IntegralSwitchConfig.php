<?php

class IntegralSwitchConfig extends Config
{
    public $valueAttributes = array(
        'switch',
        'propertyFeesSwitch',
        'advanceFeesSwitch',
        'parkingFeesSwitch',
        'goodsSwitch',
        'exchangeRate',
        'purchaseSwitch',
        'purchaseRate',
    );
    public $switch;//积分开关
    public $propertyFeesSwitch;//物业费缴费积分开关
    public $advanceFeesSwitch;//物业费预缴费积分开关
    public $parkingFeesSwitch;//停车费积分开关
    public $goodsSwitch;//商品积分支付
    public $exchangeRate;//现金/积分兑换比例
    public $purchaseSwitch;//内部采购积分开关
    public $purchaseRate;//内部采购现金/积分兑换比例

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('switch, propertyFeesSwitch, advanceFeesSwitch, parkingFeesSwitch, goodsSwitch, purchaseSwitch', 'boolean', 'on' => 'update'),
            array('exchangeRate, purchaseRate', 'numerical', 'integerOnly' => true, 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'switch' => '积分开关',
            'propertyFeesSwitch' => '物业费缴费积分开关',
            'exchangeRate' => '彩之云积分汇率',
            'advanceFeesSwitch' => '预缴费积分开关',
            'parkingFeesSwitch' => '停车费积分开关',
            'goodsSwitch' => '商品积分支付',
            'purchaseSwitch' => '内部采购积分开关',
            'purchaseRate' => '内部采购积分兑换比例'
        ));
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            array(
                'name' => 'switch',
                'type' => 'boolean',
            ),
            array(
                'name' => 'exchangeRate',
                'type' => 'number'
            ),
            /*array(
                'name' => 'propertyFeesSwitch',
                'type' => 'boolean',
            ),
            array(
                'name' => 'advanceFeesSwitch',
                'type' => 'boolean',
            ),
            array(
                'name' => 'parkingFeesSwitch',
                'type' => 'boolean',
            ),*/
            array(
                'name' => 'goodsSwitch',
                'type' => 'boolean',
            ),
            array(
                'name' => 'purchaseSwitch',
                'type' => 'boolean',
            ),
            array(
                'name' => 'purchaseRate',
                'type' => 'number'
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
