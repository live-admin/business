<?php

Yii::import('common.components.models.Category');

/**
 * This is the model class for table "repair_category".
 *
 * The followings are the available columns in table 'repair_category':
 * @property integer $id
 * @property string $name
 * @property integer $state
 * @property integer $is_deleted
 */
class RepairCategory extends Category
{
    public function getModelName()
    {
        return '报修分类';
    }


    public function getItemModelName()
    {
        return 'Repair';
    }

    public function getItemRelationName($enabled)
    {
        return 'repairs';
    }

    public $relationKeyName = 'category_id';

    //临时方案不检查分类关联
    public function relations()
    {
        return array();
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'repair_category';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
