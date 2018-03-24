<?php
Yii::import('common.components.models.Routine');
/**
 * This is the model class for table "complain".
 *
 * The followings are the available columns in table 'complain':
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
class Complain extends Routine
{
    public $modelName = '投诉';
    public $categorys = 'complains';
    public $categorysModel = 'ComplainCategory';

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'complain';
    }

}
