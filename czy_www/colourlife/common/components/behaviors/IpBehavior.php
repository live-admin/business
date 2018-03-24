<?php

class IpBehavior extends CActiveRecordBehavior
{
    public $createAttribute = 'create_ip';
    public $updateAttribute = 'update_ip';
    public $setUpdateOnCreate = false;

    public $ipExpression;

    public function beforeSave($event)
    {
        if ($this->getOwner()->getIsNewRecord() && ($this->createAttribute !== null)) {
            $this->getOwner()->{$this->createAttribute} = $this->getIpByAttribute($this->createAttribute);
        }
        if ((!$this->getOwner()->getIsNewRecord() || $this->setUpdateOnCreate) && ($this->updateAttribute !== null)) {
            $this->getOwner()->{$this->updateAttribute} = $this->getIpByAttribute($this->updateAttribute);
        }
    }

    protected function getIpByAttribute($attribute)
    {
        if ($this->ipExpression !== null)
            return @eval('return ' . $this->ipExpression . ';');
        return Yii::app()->getRequest()->getUserHostAddress();
    }

}
