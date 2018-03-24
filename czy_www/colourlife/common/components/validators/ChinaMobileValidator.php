<?php

class ChinaMobileValidator extends CValidator
{
    public $allowEmpty = true;
    public $pattern = '/^1[3|4|5|7|8][0-9]\d{8}$/';
    public $message = '您输入的手机号码有误，请重新输入';

    protected function validateAttribute($object, $attribute)
    {
        $value = $object->$attribute;
        if ($this->allowEmpty && $this->isEmpty($value))
            return;
        if (!preg_match($this->pattern, $value)) {
            $this->addError($object, $attribute, $this->message);
        }
    }

}
