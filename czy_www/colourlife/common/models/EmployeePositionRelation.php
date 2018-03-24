<?php

/**
 * This is the model class for table "employee_position_relation".
 *
 * The followings are the available columns in table 'employee_position_relation':
 * @property integer $id
 * @property integer $position_id
 * @property integer $employee_id
 * @property integer $update_time
 */
class EmployeePositionRelation extends CActiveRecord
{
    public $searchPositionName;
    public $searchEmployeeName;

    public function tableName()
    {
        return 'employee_position_relation';
    }

    public function rules()
    {
        return array(
            array('position_id, employee_id, update_time', 'numerical', 'integerOnly' => true),
            array('position_id,employee_id', 'required'),
            array('position_id, employee_id,searchPositionName,searchEmployeeName', 'safe', 'on' => 'search'),
            //array('', 'safe', 'on' => 'create'),
        );
    }

    public function relations()
    {
        return array(
            'position' => array(self::BELONGS_TO, 'Position', 'position_id'),
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => '编号',
            'position_id' => '职位',
            'employee_id' => '员工',
            'update_time' => '更新时间',
            'searchPositionName' => '职位名称',
            'searchEmployeeName' => '员工名称',
        );
    }

    public function getPositionName()
    {
        return empty($this->position)?'':$this->position->name;
    }
    public function getEmployeeName()
    {
        return empty($this->employee)?'':$this->employee->name;
    }
    
    public function getEmployeeMobile(){
    	return empty($this->employee)?'':$this->employee->mobile;
    	
    }
    
    public function getEmployeeJob(){
    	return empty($this->employee)?'':$this->employee->job_name;
    }
    
    public function getEmployeeBranch(){
    	$return = '';
    	if(!empty($this->employee)){
    		 $return = $this->employee->branchFull;
    		 $return = implode('-',$return);
    	}
    	
    	return $return;
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        if ($this->searchPositionName != '') {
            $criteria->with[] = 'position';
            $criteria->compare('position.name', $this->searchPositionName);
        }
        $criteria->compare('position_id', $this->position_id);

        if ($this->searchEmployeeName != '') {
            $criteria->with[] = 'employee';
            $criteria->compare('employee.name', $this->searchEmployeeName);
        }

        $criteria->compare('employee_id', $this->employee_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id desc,employee_id desc,position_id desc',
            )
        ));
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    
   
}
