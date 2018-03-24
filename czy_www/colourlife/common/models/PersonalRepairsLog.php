<?php

/**
 * This is the model class for table "personal_repairs_log".
 *
 * The followings are the available columns in table 'personal_repairs_log':
 * @property integer $id
 * @property integer $personal_repairs_id
 * @property string $handling_object
 * @property integer $employee_id
 * @property integer $employee_time
 * @property string $note
 * @property integer $state
 * @property integer $shop_state
 */
class PersonalRepairsLog extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = 'PersonalRepairsLog';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PersonalRepairsLog the static model class
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
        return 'personal_repairs_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('note', 'required'),
            array('personal_repairs_id, employee_id, employee_time, state', 'numerical', 'integerOnly' => true),
            array('handling_object', 'length', 'max' => 255),
            array('id, personal_repairs_id, handling_object, employee_id, employee_time, note, state', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'personalRepairs' => array(self::BELONGS_TO, 'PersonalRepairsInfo', 'personal_repairs_id'),
            'employee'=> array(self::BELONGS_TO, 'Employee', 'employee_id'),
            'shop'=> array(self::BELONGS_TO, 'Shop', 'employee_id'),
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
            'employee_time' => 'Employee Time',
            'note' => 'Note',
            'state' => 'State',
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
        $criteria->compare('employee_time', $this->employee_time);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('state', $this->state);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => NULL,
                'updateAttribute' => 'employee_time',
                'setUpdateOnCreate' => true,
            ),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
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
    static public function createLog($personal_repairs_id, $model, $state, $employee_id, $note,$shop_state=0)
    {
        $log = new self();
        $log->personal_repairs_id = $personal_repairs_id;
        $log->handling_object = $model;
        $log->state = $state;
        $log->shop_state = $shop_state;
        $log->employee_id = $employee_id;
        $log->note = $note;
        if ($log->save())
            return true;
        else
            return false;
    }

    public function getEmployeeName()
    {
        if($this->handling_object!='shop' && $this->handling_object!='customer')
            return empty($this->employee) ? '' : $this->employee->name;
        else
            return empty($this->shop) ? '' : $this->shop->name;
    }


    public function getStatusName($html=true, $type = null)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        if(!$type){
            $return .= empty(PersonalRepairsInfo::$_state_list[$this->state])?"":PersonalRepairsInfo::$_state_list[$this->state];
        }
        else{
            $return .= empty(PersonalRepairsInfo::$_state_list[$this->shop_state])?"":PersonalRepairsInfo::$_state_list[$this->shop_state];
        }
        $return .= ($html) ? '</span>' : '';
        return $return;
    }

    /**
     * 商家后台日志状态显示
     * @param bool $html
     * @return string
     */
    public function getShopStatusName($html=true)
    {
        return $this->getStatusName($html,true);
    }

    public function getFrontStart(){
    	if($this->state == Item::PERSONAL_REPAIRS_AWAITING_HANDLE){
    		return Item::COMPLAIN_REPARS_START;//
    	}else if($this->state == Item::PERSONAL_REPAIRS_HANDLE_END){
    		return Item::COMPLAIN_REPARS_EVALUATION;
    	}else if($this->state == Item::PERSONAL_REPAIRS_SUCCESS_COLOSE ){
    		return Item::CONPLAIN_REPARS_COMPLETE;
    	}else if($this->state == Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE){
            return Item::COMPLAIN_REPAIRS_REFUSE;
        }else{
    		return Item::COMPLAIN_REPARS_ING;
    	}
    }
    
    //前台状态
    public function getUserState(){
    	if($this->state == Item::PERSONAL_REPAIRS_AWAITING_HANDLE && $this->shop_state == Item::PERSONAL_REPAIRS_AWAITING_HANDLE){
    		return  Item::COMPLAIN_REPAIRS_LOG_AWAITING_HANDL;//0
    	}else if($this->state == Item::PERSONAL_REPAIRS_SUCCESS_COLOSE || $this->shop_state == Item::PERSONAL_REPAIRS_ABNORMAL_COLOSE){
    		return Item::COMPLAIN_REPAIRS_LOG_COLOSE;//5
    	}else if($this->state == Item::PERSONAL_REPAIRS_CONFIRM_END || $this->shop_state == Item::PERSONAL_REPAIRS_SHOP_HAS_SERVED){
    		return Item::COMPLAIN_REPAIRS_LOG_CONFIRM_END;//3
    	}/* else if(($this->state == Item::PERSONAL_REPAIRS_RECEIVE_HANDLING && $this->oldState == Item::PERSONAL_REPAIRS_CONFIRM_END)||
    			($this->shop_state == Item::PERSONAL_REPAIRS_SHOP_ACCEPTED && $this->oldShopState == Item::PERSONAL_REPAIRS_SHOP_HAS_SERVED)){
    		return Item::COMPLAIN_REPAIRS_LOG_BAD_HANDLING;//2 
    	}*/else{
    		return Item::COMPLAIN_REPAIRS_LOG_HANDLING;//1
    	}
    }
}