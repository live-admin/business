<?php

Yii::import('common.components.models.Category');

/**
 * This is the model class for table "app_category".
 *
 * The followings are the available columns in table 'app_category':
 * @property integer $id
 * @property string $name
 * @property integer $state
 * @property integer $is_deleted
 */
class AppCategory extends Category
{
    public function getModelName()
    {
        return '精品推荐分类';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getItemModelName()
    {
        return 'App';
    }

    public function getItemRelationName($enabled)
    {
        return 'apps';
    }

    public $relationKeyName = 'category_id';
    public $itemHasState = false;
    public $hasLogoAndDesc = false;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'app_category';
    }

}