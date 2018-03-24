<?php

class TimeConfig extends Config
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('val', 'numerical', 'integerOnly' => true, 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'val' => '时间',
        ));
    }


    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            array(
                'name' => 'val',
                'type' => 'timeValue',
            ),
        ));
    }
}