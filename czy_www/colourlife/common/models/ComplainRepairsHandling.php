<?php

/**
 * This is the model class for table "complain_repairs_handling".
 *
 * The followings are the available columns in table 'complain_repairs_handling':
 * @property integer $id
 * @property integer $complain_repairs_id
 * @property integer $employee_id
 * @property integer $type
 */
class ComplainRepairsHandling extends CActiveRecord
{
    //
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    // public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = '投诉报修执行/监督人列表';


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ComplainRepairsHandling the static model class
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
        return 'complain_repairs_handling';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('complain_repairs_id, employee_id, type', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, complain_repairs_id, employee_id, type', 'safe', 'on' => 'search'),
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
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
            'complain_repairs' => array(self::BELONGS_TO, 'ComplainRepairs', 'complain_repairs_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'complain_repairs_id' => 'Complain Repairs',
            'employee_id' => 'Employee',
            'type' => 'Type',
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
        $criteria->compare('complain_repairs_id', $this->complain_repairs_id);
        $criteria->compare('employee_id', $this->employee_id);
        $criteria->compare('type', $this->type);
        //$criteria->compare('designate',$this->designate);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    public function getEmployee()
    {
        return (empty($this->employee)) ? '' : $this->employee->name;
    }

    public function behaviors()
    {
        return array();
    }

    /**
     * 创建监督过了的人
     * @param $complain_repairs_id
     * @param $employee_id
     * @return bool
     */
    static public function createSupervisionOver($complain_repairs_id, $employee_id = null)
    {
        $type = Item::COMPLAIN_REPAIRS_HANDLING_SUPERVISIONOVER;
        $employee_id = empty($employee_id) ? Yii::app()->user->id : $employee_id;
        return self::createlog($complain_repairs_id, $employee_id, $type);
    }

    /**
     * 创建执行过了的人
     * @param $complain_repairs_id
     * @param $employee_id
     * @return bool
     */
    static public function createExecuted($complain_repairs_id, $employee_id = null)
    {
        $type = Item::COMPLAIN_REPAIRS_HANDLING_EXECUTE;
        $employee_id = empty($employee_id) ? Yii::app()->user->id : $employee_id;
        if (self::createlog($complain_repairs_id, $employee_id, $type)) {
            //添加一个监督人
            return self::createlog($complain_repairs_id, $employee_id, Item::COMPLAIN_REPAIRS_HANDLING_SUPERVISOR);
        }

        return false;
    }

    /**
     * @param $complain_repairs_id
     * @param $employee_id
     * @return bool
     * 写日志
     * */
    static private function createlog($complain_repairs_id, $employee_id, $type)
    {
        $complain_repairs_id = intval($complain_repairs_id);
        $employee_id = intval($employee_id);
        $type = intval($type);

        $model = new self();
        $data = $model->find('complain_repairs_id=:id and employee_id=:employee_id and type=:type', array(
            ':id' => $complain_repairs_id,
            ':employee_id' => $employee_id,
            ':type' => $type,
        ));
        //如果已存在则直接返回成功
        if (!empty($data))
            return true;

        $model->complain_repairs_id = $complain_repairs_id;
        $model->employee_id = $employee_id;
        $model->type = $type; //类型
        return $model->save();
    }

    //业主名称
    public function getCustomerName()
    {
        if (empty($this->complain_repairs)) {
            return "";
        } else {
            return $this->complain_repairs->customerName;
        }
    }

    //员工名称
    public function getEmployeeName()
    {
        // if (empty($this->employee)) {
        //     return "";
        // } else {
        //     return $this->employee->name;
        // }

        if(!empty($this->employee_id)){
            $employee = ICEEmployee::model()->findbypk($this->employee_id);
            if(!empty($employee['name'])){
                return $employee['name'];
            }

        }

        return '';
    }

    public function getComplainRepairsCreateTime()
    {
        if (empty($this->complain_repairs)) {
            return "";
        } else {
            return date('Y-m-d', $this->complain_repairs->create_time);
        }
    }

    public function getRepairCategoryName()
    {
        if (empty($this->complain_repairs)) {

        } else {
            return $this->complain_repairs->repairCategoryName;
        }
    }

    public function getStateNames()
    {
        return ComplainRepairs::model()->getStateNames();
    }


}