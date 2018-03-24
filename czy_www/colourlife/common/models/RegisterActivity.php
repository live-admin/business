<?php

/**
 * This is the model class for table "invite".
 *
 * The followings are the available columns in table 'invite':
 * @property integer $id
 * @property integer $customer_id
 * @property string $mobile
 * @property integer $create_time
 * @property integer $valid_time
 * @property integer $status
 */
class RegisterActivity extends CActiveRecord
{
    public $modelName = "邀请好友注册";

    const STARTTIME = "2015-08-06 00:00:00";   //活动开始时间
    const ENDTIME = "2015-10-31 23:59:59";     //活动结束时间

    public $customer_id;
    public $activity_loans_id = 105;

    public $is_send = 0;
    public $create_time;


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Invite the static model class
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
        return 'register_activity';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id, create_time, activity_loans_id,is_send', 'numerical', 'integerOnly' => true),
        );
    }



}
