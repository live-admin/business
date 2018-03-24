<?php

Yii::import('common.components.models.Category');

/**
 * This is the model class for table "complain_category".
 *
 * The followings are the available columns in table 'complain_category':
 * @property integer $id
 * @property string $name
 * @property integer $state
 * @property integer $is_deleted
 */
class StaffComplainCategory extends Category
{
    public function getModelName()
    {
        return '员工投诉分类';
    }

    public function getItemModelName()
    {
        return 'Complain';
    }

    public function getItemRelationName($enabled)
    {
        return 'complains';
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
        return 'staff_complain_category';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
