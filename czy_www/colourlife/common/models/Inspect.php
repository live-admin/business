<?php
Yii::import('common.components.models.Routine');
/**
 * This is the model class for table "inspect".
 *
 * The followings are the available columns in table 'inspect':
 * @property integer $id
 * @property integer $branch_id
 * @property integer $report_employee_id
 * @property integer $accept_employee_id
 * @property integer $complete_employee_id
 * @property string $content
 * @property integer $create_time
 * @property integer $accept_time
 * @property integer $complete_time
 * @property integer $is_deleted
 * @property string $accept_content
 * @property string $complete_content
 * @property integer $category_id
 */
class Inspect extends Routine
{
    public $modelName = '巡检';
    public $belongKey = 'branch';
    public $userKey = 'employee';
    public $belongModel = 'Branch';
    public $userModel = 'Employee';
    public $belongRelationKey = 'branch_id';
    public $userRelationKey = 'report_employee_id';
    public $belongLabel = '部门';
    public $userLabel = '上报员工';

    public $categorys = 'inspects';
    public $categorysModel = 'InspectCategory';

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'inspect';
    }

//  ICE 覆盖了父类方法，新添加方法 获取职员名去ice获取
//  return string
    public function getUserName(){
//      ICE 新添加
        $UserName = ICEEmployee::model()->findByPk($this->report_employee_id);
        if(!empty($UserName)){
            return $UserName->name;
        }else{
            return '';
        }

    }
//  ICE 覆盖了父类方法，新添加方法 获取报告职员电话去ice获取
//  return string
    public function getUserMobile(){
//      ICE 新添加
        $userMobile = ICEEmployee::model()->findByPk($this->report_employee_id);
        if(!empty($userMobile)){
            return $userMobile->mobile;
        }else{
            return '';
        }

    }    


//  ICE 覆盖了父类方法，新添加方法 获取处理人名字去ice获取
//  return string
    public function getAcceptName()
    {
        $acceptName = ICEEmployee::model()->findByPk($this->accept_employee_id);
        if(!empty($acceptName)){
            return $acceptName->name;
        }else{
            return '';
        }
    }

//  ICE 覆盖了父类方法，新添加方法 获取完成人名字去ice获取
//  return string
    public function getCompleteName(){
        $completeName = ICEEmployee::model()->findByPk($this->complete_employee_id);
        if(!empty($completeName)){
            return $completeName->name;
        }else{
            return '';
        }
    }
}
