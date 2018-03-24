<?php

/**
 * This is the model class for table "personal_repairs_handling".
 *
 * The followings are the available columns in table 'personal_repairs_handling':
 * @property integer $id
 * @property integer $personal_repairs_id
 * @property string $handling_object
 * @property integer $employee_id
 */
class PersonalRepairsHandling extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '个人报修处理人列表';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PersonalRepairsHandling the static model class
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
        return 'personal_repairs_handling';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('personal_repairs_id, employee_id', 'numerical', 'integerOnly' => true),
            array('handling_object', 'length', 'max' => 255),
            array('id, personal_repairs_id, handling_object, employee_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'personalRepairs' => array(self::BELONGS_TO, 'PersonalRepairsInfo', 'personal_repairs_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'personal_repairs_id' => 'Personal Repairs',
            'handling_object' => 'Handling Object',
            'employee_id' => 'Employee',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('personal_repairs_id', $this->personal_repairs_id);
        $criteria->compare('handling_object', $this->handling_object, true);
        $criteria->compare('employee_id', $this->employee_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array();
    }

    /**
     * 创建报修处理人
     * @param $personal_repairs_id
     * @param $model
     * @param $employee_id
     * @return bool
     */
    static public function createHandling($personal_repairs_id, $model, $employee_id)
    {
        $log = new self();
        $log->personal_repairs_id = $personal_repairs_id;
        $log->handling_object = $model;
        $log->employee_id = $employee_id;
        if ($log->save())
            return true;
        else
            return false;
    }


    /**
     * 创建监督的人
     * @param $personal_repairs_id
     * @param $employee_id
     * @return bool
     */
    static public function createSupervision($personal_repairs_id, $employee_id = null)
    {
        $type = Item::COMPLAIN_REPAIRS_HANDLING_SUPERVISOR;
        $employee_id = empty($employee_id) ? Yii::app()->user->id : $employee_id;
        return self::createlog($personal_repairs_id, $employee_id, $type);
    }


    /**
     * 创建监督过了的人
     * @param $personal_repairs_id
     * @param $employee_id
     * @return bool
     */
    static public function createSupervisionOver($personal_repairs_id, $employee_id = null)
    {
        $type = Item::COMPLAIN_REPAIRS_HANDLING_SUPERVISIONOVER;
        $employee_id = empty($employee_id) ? Yii::app()->user->id : $employee_id;
        return self::createlog($personal_repairs_id, $employee_id, $type);
    }


    /**
     * 创建执行过了的人
     * @param $personal_repairs_id
     * @param $employee_id
     * @return bool
     */
    static public function createExecuted($personal_repairs_id, $employee_id = null)
    {
        $type = Item::COMPLAIN_REPAIRS_HANDLING_EXECUTE;
        $employee_id = empty($employee_id) ? Yii::app()->user->id : $employee_id;
        if (self::createlog($personal_repairs_id, $employee_id, $type)) {
            //添加一个监督人
            return self::createlog($personal_repairs_id, $employee_id, Item::COMPLAIN_REPAIRS_HANDLING_SUPERVISOR);
        }

        return false;
    }

    /**
     * @param $personal_repairs_id
     * @param $employee_id
     * @return bool
     * 写日志
     * */
    static private function createlog($personal_repairs_id, $employee_id, $type)
    {
        $personal_repairs_id = intval($personal_repairs_id);
        $employee_id = intval($employee_id);
        $type = intval($type);

        $model = new self();
        $data = $model->find('personal_repairs_id=:id and employee_id=:employee_id and type=:type', array(
            ':id' => $personal_repairs_id,
            ':employee_id' => $employee_id,
            ':type' => $type,
        ));
        //如果已存在则直接返回成功
        if (!empty($data))
            return true;

        $model->personal_repairs_id = $personal_repairs_id;
        $model->employee_id = $employee_id;
        $model->type = $type; //类型
        return $model->save();
    }
}