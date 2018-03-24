<?php

class InviteConfig extends Config
{
    public $valueAttributes = array('validTime', 'validEmployeeTime');
    public $validTime;
    public $validEmployeeTime;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('validTime,validEmployeeTime', 'required', 'on' => 'update'),
            array('validTime,validEmployeeTime', 'numerical', 'integerOnly' => true),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'validTime' => '有效时间',
            'validEmployeeTime' => '物管邀请有效时间',
        ));
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            array(
                'name' => 'validTime',
                'type' => 'timeValue',
            ),
            array(
                'name' => 'validEmployeeTime',
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
