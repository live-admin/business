<?php

class AppVersionConfig extends Config
{
    public $valueAttributes = array('current', 'require');
    public $current;
    public $require;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('require, current', 'match', 'pattern' => '/^\d+(\.\d+)+$/', 'message' => '{attribute} 必须是 1.0 或者 1.0.0 格式，每个分节符的数字最多两位，最少一位', 'on' => 'update'),
            array('current', 'checkVersion', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'current' => '当前最新版本号',
            'require' => '支持的最低版本号',
        ));
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            'current',
            'require',
        ));
    }

    public function checkVersion($attribute, $params)
    {
        if (!$this->hasErrors() && version_compare($this->$attribute, $this->require) < 0) {
            $this->addError($attribute, $this->getAttributeLabel($attribute) . '必须大于等于最低版本');
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

}
