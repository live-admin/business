<?php

class ActiveDataProvider extends CActiveDataProvider
{

    public function __construct($modelClass, $config = array())
    {
        if (!isset($config['pagination']['pageSize'])) {
            $config['pagination']['pageSize'] = Yii::app()->config->pageSize; // 设置默认每页数
        }
        return parent::__construct($modelClass, $config);
    }

    protected function calculateTotalItemCount()
    {
        $isDeleted = $this->model->asa('IsDeletedBehavior');
        if ($isDeleted !== null) {
            $isDeleted->filterDeleted(); // 过滤被删除的数据
        }
        return parent::calculateTotalItemCount();
    }

}
