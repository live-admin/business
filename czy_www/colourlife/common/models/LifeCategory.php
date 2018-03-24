<?php

Yii::import('common.components.models.Category');

/**
 * This is the model class for table "life_category".
 *
 * The followings are the available columns in table 'life_category':
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property string $desc
 * @property integer $state
 * @property integer $is_deleted
 */
class LifeCategory extends Category
{
    public $logoTips = '图片大小为100*100';
    public $logoDefault = 'nopic.png';

    public function getModelName()
    {
        return '周边优惠分类';
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
        if ($enabled)
            return 'enabledShops';
        return 'shops';
    }

    public $relationKeyName = 'life_cate_id';
    public $itemHasState = true;
    public $hasLogoAndDesc = true;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'life_category';
    }

}
