<?php
Yii::import('common.components.models.Category');
/**
 * This is the model class for table "service_manage".
 *
 * The followings are the available columns in table 'service_manage':
 * @property integer $id
 * @property string $name
 * @property integer $state
 * @property integer $is_deleted
 */
class ServiceCategory extends Category
{
    public function getModelName()
    {
        return '服务分类';
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ServiceCategory the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getItemModelName()
    {
        return 'Service';
    }

    public function getItemRelationName($enabled)
    {
        return 'Services';
    }

    public $relationKeyName = 'category_id';
    public $itemHasState = true;
    public $hasLogoAndDesc = false;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'service_category';
    }

}
