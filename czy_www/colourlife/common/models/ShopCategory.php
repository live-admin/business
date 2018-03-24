<?php

Yii::import('common.components.models.Category');

/**
 * This is the model class for table "shop_category".
 *
 * The followings are the available columns in table 'shop_category':
 * @property integer $id
 * @property string $name
 * @property integer $state
 * @property integer $is_deleted
 */
class ShopCategory extends Category
{
    public function getModelName()
    {
        return '行业';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getItemModelName()
    {
        return 'Shop';
    }

    public function getItemRelationName($enabled)
    {
        return 'shops';
    }

    public $relationKeyName = 'category_id';
    public $itemHasState = false;
    public $hasLogoAndDesc = false;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop_category';
    }

}
