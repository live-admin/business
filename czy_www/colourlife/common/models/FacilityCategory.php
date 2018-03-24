<?php

Yii::import('common.components.models.Category');

/**
 * This is the model class for table "facility_category".
 *
 * The followings are the available columns in table 'facility_category':
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property string $desc
 * @property integer $state
 * @property integer $is_deleted
 */
class FacilityCategory extends Category
{
    public $logoTips = '图片大小为100*100';
    public $logoDefault = 'nopic.png';

    public function getModelName()
    {
        return '黄页信息分类';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getItemModelName()
    {
        return 'Facility';
    }

    public function getItemRelationName($enabled)
    {
        return 'facilities';
    }

    public $relationKeyName = 'category_id';
    public $itemHasState = false;
    public $hasLogoAndDesc = true;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'facility_category';
    }

}
