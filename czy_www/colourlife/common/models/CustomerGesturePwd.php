<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/3/2
 * Time: 15:39
 */

class CustomerGesturePwd extends CActiveRecord
{

    public $isUpdateGestureCode = 0;

    /**
     * @var string 模型名
     */
    public $modelName = '手势密码';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Customer the static model class
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
        return 'customer_gesture_pwd';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array();
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(

        );
    }

    protected function beforeSave()
    {
        $this->update_time = time();
        if ($this->isUpdateGestureCode)
            $this->gesture_code = $this->encypt($this->gesture_code);
        return parent::beforeSave();
    }

    /**
     * 加密
     * @param $code
     * @return string
     */
    protected function encypt($code)
    {
        return md5($code);
    }

    public function verifyGesture($code)
    {
        return $this->encypt($code) == $this->gesture_code ? true : false;
    }
}