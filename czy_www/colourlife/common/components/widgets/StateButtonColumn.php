<?php

Yii::import('bootstrap.widgets.TbDataColumn');

class StateButtonColumn extends TbDataColumn
{
    public $htmlOptions = array('class' => 'op-column');
    public $name = 'state';
    public $type = 'raw';

    //public $value = '$data->getStateButton()';

    public $columType = true;
    public function init()
    {
        parent::init();
        if ($this->columType){
            $this->value='$data->getStateButton()';
        }else{
            $this->value='$data->getStateName()';
        }
    }
}
