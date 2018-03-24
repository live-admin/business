<?php

/**
 * This is the model class for table "complain_repairs_log".
 *
 * The followings are the available columns in table 'complain_repairs_log':
 * @property integer $id
 * @property integer $complain_repairs_id
 * @property integer $model
 * @property integer $state
 * @property integer $type
 * @property integer $employee_id
 * @property integer $employee_time
 * @property string $note
 */
class ComplainRepairsLog extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    // public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = '投诉报修记录';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ComplainRepairsLog the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'complain_repairs_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('note', 'required'),
            array('complain_repairs_id, model, state, type, employee_id, employee_time', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, complain_repairs_id, model, state, type, employee_id, employee_time, note', 'safe', 'on'=>'search'),
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
            'employee'=> array(self::BELONGS_TO, 'Employee', 'employee_id'),
            'complainRepairs'=> array(self::BELONGS_TO, 'ComplainRepairs', 'complain_repairs_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'complain_repairs_id' => 'Common Repairs',
            'model' => 'Model',
            'state' => 'State',
            'type' => 'Type',
            'employee_id' => 'Employee',
            'employee_time' => 'Employee Time',
            'note' => 'Note',
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

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('complain_repairs_id',$this->complain_repairs_id);
        $criteria->compare('model',$this->model);
        $criteria->compare('state',$this->state);
        $criteria->compare('type',$this->type);
        $criteria->compare('employee_id',$this->employee_id);
        $criteria->compare('employee_time',$this->employee_time);
        $criteria->compare('note',$this->note,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'employee_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    /**
     * @param $complain_repairs_id
     * @param $model
     * @param $state
     * @param $type
     * @param $employee_id
     * @param $note
     * @return bool
     * 写日志
     * */
    static public function createLog($complain_repairs_id,$model,$state,$type,$employee_id,$note){
        $log = new self();
        $log->complain_repairs_id = $complain_repairs_id;
        $log->model = $model;
        $log->state = $state;
        $log->type = $type;//常规记录
        $log->employee_id = $employee_id;
        $log->note = $note;
        if($log->save()){
            return true;
        }else{
            return false;
        }
    }

    //创建后台的报修日志
    static public function createEmployeeLog($complain_repairs_id,$state,$employee_id,$note){
        $log = new self();

        $log->complain_repairs_id = $complain_repairs_id;
        $log->model = 0;
        $log->state = $state;
        $log->type = 1;//常规记录
        $log->employee_id = $employee_id;
        $log->note = $note;

        if($log->save()){
            return true;
        }else{
            return false;
        }
    }


    public function getEmployeeName()
    {
//        return empty($this->employee) ? '系统' : $this->employee->name;
        
//      ICE　员工名接入ice
        if(!empty($this->employee_id)){
            $employee = ICEEmployee::model()->findbypk($this->employee_id);
            if(!empty($employee['name'])){
                return $employee['name'];
            }
            return '系统';
        }else{
            return '系统';
        }
    }


    public function getStatusName($html=true)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= ComplainRepairs::$_state_list[$this->state];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }
	
    //前台状态
    public function getUserState(){
    	if($this->state == Item::COMPLAIN_REPAIRS_AWAITING_HANDLE){
    		return  Item::COMPLAIN_REPAIRS_LOG_AWAITING_HANDL;//0
    	}else if($this->state == Item::COMPLAIN_REPAIRS_SUCCESS_COLOSE){
    		return Item::COMPLAIN_REPAIRS_LOG_COLOSE;//5
    	}else if($this->state == Item::COMPLAIN_REPAIRS_CONFIRM_END){
    		return Item::COMPLAIN_REPAIRS_LOG_CONFIRM_END;//3
    	}/* else if($this->state == Item::COMPLAIN_REPAIRS_RECEIVE_HANDLING){
    		return Item::COMPLAIN_REPAIRS_LOG_BAD_HANDLING;//2
    	} */else{
    		return Item::COMPLAIN_REPAIRS_LOG_HANDLING;//1
    	}
    }

    
}