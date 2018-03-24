<?php

Yii::import('common.components.models.Category');

/**
 * This is the model class for table "notify_category".
 *
 * The followings are the available columns in table 'notify_category':
 * @property integer $id
 * @property string $name
 * @property integer $state
 * @property integer $is_deleted
 */
class NotifyCategory extends Category
{
    public function getModelName()
    {
        return '通知分类';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getItemModelName()
    {
        return 'Notify';
    }

    public function getItemRelationName($enabled)
    {
        return 'notifies';
    }

    public $relationKeyName = 'category_id';
    public $itemHasState = false;
    public $hasLogoAndDesc = false;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'notify_category';
    }

}
