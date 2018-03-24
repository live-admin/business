<?php

Yii::import('bootstrap.widgets.TbActiveForm');

class ActiveForm extends TbActiveForm
{
    const TYPE_ADVANCED = 'advanced';

    const INPUT_ADVANCED = 'common.components.widgets.input.InputAdvanced';

    public function init()
    {
        $type = $this->type;
        if ($this->type == self::TYPE_ADVANCED) {
            $this->type = self::TYPE_HORIZONTAL;
        }
        parent::init();
        $this->type = $type;
    }

    protected function getInputClassName()
    {
        if (isset($this->input))
            return $this->input;
        else {
            if ($this->type == self::TYPE_ADVANCED)
                return self::INPUT_ADVANCED;
            return parent::getInputClassName();
        }
    }
}