<?php

class ArrayDataProvider extends CArrayDataProvider
{

    public function __construct($modelClass, $config = array())
    {
        if (!isset($config['pagination']['pageSize'])) {
            $config['pagination']['pageSize'] = Yii::app()->config->pageSize; // 设置默认每页数
        }
        return parent::__construct($modelClass, $config);
    }

}