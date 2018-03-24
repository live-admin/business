<?php

Yii::import('common.components.models.Category');

/**
 * This is the model class for table "inspect_category".
 *
 * The followings are the available columns in table 'inspect_category':
 * @property integer $id
 * @property string $name
 * @property integer $state
 * @property integer $is_deleted
 */
class InspectCategory extends Category
{
    public function getModelName()
    {
        return '巡检分类';
    }

    public function getItemModelName()
    {
        return 'Inspect';
    }

    public function getItemRelationName($enabled)
    {
        return 'inspects';
    }

    public $relationKeyName = 'category_id';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'inspect_category';
    }

}
