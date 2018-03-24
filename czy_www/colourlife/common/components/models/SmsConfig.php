<?php

class SmsConfig extends Config
{
    public $valueAttributes = array('simulate', 'resendInterval', 'validTime', 'smsInfo');
    public $simulate;
    public $resendInterval;
    public $validTime;
    public $smsInfo;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('simulate', 'boolean', 'on' => 'update'),
            array('resendInterval', 'numerical', 'min' => 10, 'max' => 3600, 'on' => 'update'),
            array('validTime', 'numerical', 'min' => 60, 'max' => 3600 * 24, 'on' => 'update'),
            array('smsInfo', 'required', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'simulate' => '模拟',
            'smsInfo' => '选择接口',
            'resendInterval' => '注册验证码重发时间间隔',
            'validTime' => '注册验证码有效时间',
        ));
    }

    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            array(
                array(
                    'name' => 'simulate',
                    'type' => 'boolean',
                ),
                array(
                    'name' => 'resendInterval',
                    'type' => 'timeValue',
                ),
                array(
                    'name' => 'validTime',
                    'type' => 'timeValue',
                ),
                array(
                    'name' => 'smsInfo',
                    'value' => $this->getSmsInterfaceName($this->smsInfo),
                ),
            )
        );
    }

    public function checkSmsParam($attribute, $params)
    {
        if (!$this->hasErrors() && !$this->simulate && empty($this->$attribute)) {
            $this->addError($attribute, $this->getAttributeLabel($attribute) . '不能为空');
        }
    }

    public function checkSmsTemplate($attribute, $params)
    {
        if (!$this->hasErrors() && strpos($this->$attribute, '{code}') === false) {
            $this->addError($attribute, $this->getAttributeLabel($attribute) . '必须包含 <code>{code}</code> 代码');
        }
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

    public function getSmsInterfaceName($id = null)
    {
        if (empty($id)) {
            return '';
        }
        if (!$smsInterface = SmsInterface::model()->findByPk($id)) {
            return '';
        }
        return isset($smsInterface->name) ? $smsInterface->name : '';
    }

}
