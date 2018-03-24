<?php
/**
 * This is the model class for table "middle_log".
 *
 * The followings are the available columns in table 'branch_middle':
 * @property integer $id
 * @property string $level
 * @property string $category
 * @property integer $logtime
 * @property string $message
 */

class MiddleLog extends CActiveRecord
{
  
    /**
     * @var string 模型名
     */
    public $modelName = 'OA对接日志表';

   

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Branch the static model class
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
        return 'middle_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('level,category,logtime,message', 'required', 'on' => 'create'),
        );
    }
    

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'level' => '级别',
            'category' => '类别',
            'logtime' => '记录时间',
            'message' => '日志信息'           
        );
    }
    
 
  
}