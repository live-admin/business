<?php

Yii::import('common.components.models.ApiAuth');

/**
 * This is the model class for table "{{api_auth}}".
 *
 * The followings are the available columns in table '{{api_auth}}':
 * @property integer $id
 * @property string $secret
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $last_time
 * @property string $last_ip
 * @property integer $expire
 * @property string $data
 */
class EmployeeApiAuth extends ApiAuth
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ApiAuth the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'employee_api_auth';
    }

}
