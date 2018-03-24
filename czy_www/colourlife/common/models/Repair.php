<?php

Yii::import('common.components.models.Routine');

/**
 * This is the model class for table "repair".
 *
 * The followings are the available columns in table 'repair':
 * @property integer $id
 * @property integer $community_id
 * @property integer $customer_id
 * @property integer $accept_employee_id
 * @property integer $complete_employee_id
 * @property string $content
 * @property integer $create_time
 * @property integer $accept_time
 * @property integer $complete_time
 * @property integer $is_deleted
 * @property string $accept_content
 * @property string $complete_content
 * @property integer $category_id
 */
class Repair extends Routine
{
    public $modelName = '报修';

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'repair';
    }

    public $categorys = 'repairs';
    public $categorysModel = 'RepairCategory';

}
