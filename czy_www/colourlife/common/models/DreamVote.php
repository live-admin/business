<?php

class DreamVote extends CActiveRecord
{
    
    public $modelName = "投票";

    public $employee_name;
    public $sex;
    public $activity_id;
    public $address;
    public $imageURL;
    public $file_image;
    public $flag;


    public function tableName() {
        return "dream_vote";
    }
    
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function rules() {
         return array(
             array('employee_id,activity_id,display,address','required','on'=>'create,update'),
             // array('employee_id','unique','on'=>'create,update'),
             array('dream', 'length', 'encoding' => 'UTF-8', 'max' => 255, 'on' => 'create,update'),
             array('employee_id,employee_name,dream,age,special,votes,sex,imageURL,address,activity_id','safe','on'=>'search'),
             array('file_image,sex', 'safe', 'on' => 'create,update'),
             // array('employee_id,activity_id,', 'checkRepeat', 'on' => 'create,update'),
         );
     }
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'employee_id' => '员工Id',
            'employee_name' => '员工姓名',
            'dream' => '生日愿望',
            'age' => '年龄',
            'special' => '特长',
            'display' => '心愿故事',
            'votes' => '投票数',
            'EmployeeName' => '员工姓名',
            'EmployeeBranch' => '员工部门',
            'EmployeeJob' => '员工职位',
            'activity_id' => '活动主题',
            'address' => '故乡',
            'ActivityName' => '活动主题',
            'sex' => '性别',
            'SexName' => '性别',
            'Email' => '电子邮箱',
            'imageURL' => '个人照片',
            'file_image' => '个人照片',
        );
    }
    
    static $sex_list = array( "保密","男","女" );

    public function checkRepeat($attribute,$params)
    {
        $dream = DreamVote::model()->find(" employee_id=:employee_id and activity_id=:activity_id", array(':employee_id'=>$this->employee_id, ':activity_id'=>$this->activity_id));
        if(!$this->hasErrors()&&$dream)
            $this->addError($attribute,"不能重复添加相同的员工！");

    }


    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('employee_id', $this->employee_id);
        $criteria->compare('dream', $this->dream,true);
        $criteria->compare('age', $this->age,true);
        $criteria->compare('special', $this->special,true);
        if($this->employee_name!= ''){
            $criteria->with[] = 'employee';
            $criteria->compare('employee.name', $this->employee_name, true);
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    
    public function relations()
    {
        return array(
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
            'dream_activity' => array(self::BELONGS_TO, 'DreamActivity', 'activity_id'),
        );
    }
    
    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    public function getEmployeeName(){
        return $this->employee?$this->employee->name:"";
    }

    public function getActivityName(){
        return $this->dream_activity?$this->dream_activity->name:"";
    }

    public function getSimpleBranch(){
        $data = EmployeeBranchRelation::model()->find("employee_id=" . $this->employee_id);
        if (!empty($data)) {
            $model = Branch::model()->findByPk($data->branch_id);
            $branch_name = "";
            if($model){
                $branch_name = "彩生活服务集团-".$model->name;
            }else{
                $branch_name = "彩生活服务集团";
            }
            return $branch_name;
        } else if (empty($data)) {
            return "-";
        }    
        
    }


    

    public function getEmployeeId()
    {
        $employee = Employee::model()->findByPk($this->employee_id);
        if(empty($employee))
            return null;

        $arr[0]['id'] = $this->employee_id;
        $arr[0]['name'] = empty($employee)?'':$employee->name;
        return $arr;
    }

    public function getEmployeeJob(){
        return $this->employee?$this->employee->job_name:"";
    }

    public function getEmployeeBranch(){
        $data = EmployeeBranchRelation::model()->findAll("employee_id=" . $this->employee_id);
        if (!empty($data) && is_array($data)) {
            $branch_name = "";
            foreach ($data as $val) {
                $branch_name .= Branch::getMyParentBranchName($val['branch_id'], true) . " ";
            }
            return $branch_name;
        } else if (empty($data)) {
            return "-";
        }
    }



    public function getActivitySelection()
    {
        $dream = DreamActivity::model()->enabled()->findAll();
        $list = array();
        if ($dream) $list = CHtml::listData($dream, 'id', 'name');
        return $list;
    }
    

    public function getSexName($html = false)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$sex_list[$this->sex];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }

    public function getEmail(){
        return $this->employee?$this->employee->email:"";
    }
    

    public function getEmployeeImgUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->imageURL, '/common/images/nopic.png');
    }



    protected function beforeValidate()
    {
        if (empty($this->imageURL) && !empty($this->file_image))
            $this->imageURL = '';
        return parent::beforeValidate();
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->file_image)) {
            $this->imageURL = Yii::app()->ajaxUploadImage->moveSave($this->file_image, $this->imageURL);
        }
        return parent::beforeSave();
    }



}
