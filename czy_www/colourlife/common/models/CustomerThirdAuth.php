<?php


/**
 * This is the model class for table "{{customer_third_auth}}".
 *
 * The followings are the available columns in table '{{customer_third_auth}}':
 * @property integer $customer_id
 * @property string $source
 * @property integer $create_time
 * @property string $open_code
 */
class CustomerThirdAuth extends CActiveRecord
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
        return 'customer_third_auth';
    }

}
