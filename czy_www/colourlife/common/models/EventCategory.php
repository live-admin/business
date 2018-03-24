<?php

Yii::import('common.components.models.Category');

/**
 * This is the model class for table "event_category".
 *
 * The followings are the available columns in table 'event_category':
 * @property integer $id
 * @property string $name
 * @property string $logo
 * @property string $desc
 * @property integer $state
 * @property integer $is_deleted
 */
class EventCategory extends Category
{
    public $logoTips = '图片大小为460*140';
    public $logoDefault = 'event_category.jpg';

    public function getModelName()
    {
        return '专题活动分类';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getItemModelName()
    {
        return 'Event';
    }

    public function getItemRelationName($enabled)
    {
        return 'events';
    }

    public $relationKeyName = 'category_id';
    public $itemHasState = false;
    public $hasLogoAndDesc = true;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'event_category';
    }

}
