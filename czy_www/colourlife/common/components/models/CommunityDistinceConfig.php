<?php

class CommunityDistinceConfig extends Config
{
    public $valueAttributes = array('company', 'community');
    public $company;
    public $community;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('company, community', 'numerical', 'integerOnly' => true, 'on' => 'update'),
            array('company, community', 'required', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'company' => '园区距离',
            'community' => '小区距离',
        ));
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            'company',
            'community'
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
