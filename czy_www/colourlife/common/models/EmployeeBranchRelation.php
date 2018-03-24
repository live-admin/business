<?php

/**
 * This is the model class for table "employee_branch_relation".
 *
 * The followings are the available columns in table 'employee_branch_relation':
 * @property integer $id
 * @property integer $employee_id
 * @property integer $branch_id
 */
class EmployeeBranchRelation extends CActiveRecord
{
    public $name;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'employee_branch_relation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('employee_id, branch_id', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, employee_id, branch_id,name', 'safe', 'on' => 'search'),
            array('branch_id', 'checkBranchIdExist', 'on' => 'create'),
            array('branch_id','required','on' => 'update'),
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
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'employee_id' => '员工',
            'branch_id' => '部门',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('employee_id', $this->employee_id);
        if ($this->name != "") {
            $criteria->with[] = "branch";
            $criteria->compare("branch.name", $this->name, true);
        }

        $criteria->compare('branch_id', $this->branch_id);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function saveAll($employee_id, $branchIds = array())
    {
        if (empty($branchIds)) //如果传入为空
            return false;
        foreach ($branchIds as $key => $val) {
            //保存关连关系
            $model = new self;
            $model->branch_id = intval($val);
            $model->employee_id = intval($employee_id);
            // 已经存在直接跳过
            if (!$this->getCanCreate())
                continue;

            if (!$model->save())
                return false;
        }
        return true;
    }


    public function  updateEmployeeBranchRelation($employee_id, $branchList)
    {
        //删除所有的相关记录
        $this->deleteAllByAttributes(array('employee_id' => $employee_id));
        return $this->saveAll($employee_id, $branchList);

    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return EmployeeBranchRelation the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function checkBranchIdExist($attribute, $params)
    {

        if (!$this->hasErrors() && !empty($this->employee_id) && !empty($this->branch_id) && !$this->getCanCreate()) {
            $this->addError($attribute, "不可重复部门");
        }
    }

    private function getCanCreate()
    {
        $criteria = new CDbCriteria;
        $criteria->compare("employee_id", $this->employee_id);
        $criteria->compare("branch_id", $this->branch_id);
        $branch = EmployeeBranchRelation::model()->find($criteria);
        if ($branch) {
            return false;
        } else {
            return true;
        }
    }
    
    public static function checkIsExist($employee_id,$branch_id){
        return EmployeeBranchRelation::model()->find('employee_id=:employee_id and branch_id=:branch_id',array(':employee_id'=>$employee_id,':branch_id'=>$branch_id));
    }
    
    public static function getAllByBranchId($branch_id){
        return EmployeeBranchRelation::model()->findAll('branch_id=:branch_id',array(':branch_id'=>$branch_id));
    }

    public function deleteAllByEmployeeId($employee_id){
        EmployeeBranchRelation::model()->deleteAll('employee_id=:employee_id',array(':employee_id'=>$employee_id));
    }
    
    public function getAllByEmployeeId($employee_id){
        return EmployeeBranchRelation::model()->findAll('employee_id=:employee_id',array(':employee_id'=>$employee_id));
    }
    
}
