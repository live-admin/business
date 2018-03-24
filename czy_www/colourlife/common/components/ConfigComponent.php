<?php

class ConfigComponent extends CApplicationComponent
{
    protected $data;

    public function init()
    {
        $this->reload();
    }

    public function reload()
    {
        $data = array();
        foreach (Config::model()->findAll() as $model) {
            $data[$model->key] = $model->val;
        }
        $this->data = new CAttributeCollection($data, true);
    }

    public function __isset($name)
    {
        return $this->data->__isset($name);
    }

    public function __get($name)
    {
        return $this->data->__get($name);
    }

    public function __set($name, $value)
    {
        // nothing
    }

    public function __unset($name)
    {
        // nothing
    }
}