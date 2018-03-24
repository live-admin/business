<?php

class AppDownloadUrlConfig extends Config
{
    public $valueAttributes = array('url');
    public $url;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('url', 'url', 'message' => '{attribute} 必须是正确的URL', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'url' => 'URL地址',
        ));
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            'url',
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
