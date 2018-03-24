<?php

/**
 * This is the model class for table "build".
 *
 * The followings are the available columns in table 'build':
 * @property integer $id
 * @property string $name
 * @property integer $community_id
 * @property integer $state
 * @property integer $is_deleted
 */
class Build extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '楼栋';

    public $director;

	public $community;
	public $uuid;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Build the static model class
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
        return 'build';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('community_id', 'checkEnable', 'on' => 'create'),
            array('name', 'required', 'on' => 'create, update'),
            array('state', 'required', 'on' => 'create'),
            array('state', 'checkEnable', 'on' => 'enable'),
            array('state', 'checkDisable', 'on' => 'disable'),
            array('is_deleted', 'checkDelete', 'on' => 'delete'),
            array('community_id, state, is_deleted,employee', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 50, 'on' => 'create, update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('name, state,employee', 'safe', 'on' => 'search'),
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
            //'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
            'customersCount' => array(self::STAT, 'Customer', 'build_id', 'condition' => 't.is_deleted=0'),
            'enabledCustomersCount' => array(self::STAT, 'Customer', 'build_id', 'condition' => 't.is_deleted=0 AND t.state=0'),
            'employees' => array(self::BELONGS_TO,'Employee','employee'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '楼栋',
            'community_id' => '小区',
            'employee' =>'楼栋经理',
            'employeeName' =>'楼栋经理',
            'director'  =>'楼栋经理',
            'state' => '状态',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('name', $this->name, true);
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('state', $this->state);
        $criteria->compare('employee',$this->employee);
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }

    public function checkEnable($attribute, $params)
    {
        if (!$this->hasErrors() && (empty($this->community) || $this->community->isDisabled)) {
            //$this->addError($attribute, '指定的小区不存在或被禁用，无法创建、修改或启用' . $this->modelName);
        }
    }

    public function checkDisable($attribute, $params)
    {
        if (!$this->hasErrors() && (!empty($this->enabledCustomersCount))) {
            $this->addError($attribute, '该' . $this->modelName . '存在业主用户，不能被禁用。');
        }
    }

    public function checkDelete($attribute, $params)
    {
        if (!$this->hasErrors() && (!empty($this->customersCount))) {
            $this->addError($attribute, '该' . $this->modelName . '存在业主用户，不能被删除。');
        }
    }

    public function getBuildName($build_id=""){
        if($build_id==""){
            return "";
        }else{
            $model=Build::model()->findByPk($build_id);
            if(!empty($model)){
                return $model->name;
            }else{
                return "";
            }
        }
    }

    public function getEmployeeName(){
        return (empty($this->employees))?"":$this->employees->name;
    }

    /**
     * 根据小区uuid获取楼栋列表（RMS）
     * @param $uuid
     * @return array
     */
    public function getBuildByUuid($uuid){
        $build_arr = array();
        try {
            $build_arr = ICEService::getInstance()->dispatch(
                'building/list',
                array(
                    'smallarea_uuid' => $uuid,
                ),
                array(),
                'get'
            );
        } catch (Exception $e) {
            Yii::log(
                sprintf(
                    '获取 uuid %s[%s] 小区楼栋信息失败',
                    $uuid,
                    $e->getMessage(), $e->getCode()
                ),
                CLogger::LEVEL_ERROR,
                'colourlife.logFile.get_build'
            );
        }
        $return_arr = [];
        if(!empty($build_arr) && isset($build_arr['housetype'])){
            foreach($build_arr['housetype'] as $key=>$value){
                $return_arr[] = [
                    'build_uuid' => $key,
                    'build_name' => $value,
                ];
            }
        }
        return $return_arr;
    }

    /**
     * 根据楼栋uuid获取单元列表（RMS）
     * @param $uuid
     * @return array
     */
    public function getUnitByUuid($uuid){
        $unit_arr = array();
        try {
            $unit_arr = ICEService::getInstance()->dispatch(
                'building/units',
                array(
                    'housetype_uuid' => $uuid,
                    'pageSize' => 1000
                ),
                array(),
                'get'
            );
        } catch (Exception $e) {
            Yii::log(
                sprintf(
                    '获取 uuid %s[%s] 楼栋单元信息失败',
                    $uuid,
                    $e->getMessage(), $e->getCode()
                ),
                CLogger::LEVEL_ERROR,
                'colourlife.logFile.get_build'
            );
        }
        $return_arr = [];
        if(!empty($unit_arr) && isset($unit_arr['unit'])){
            foreach($unit_arr['unit'] as $key=>$value){
                $return_arr[] = [
                    'unit_name' => $value['unit_name'],
                    'unit_uuid' => $value['unit_uuid'],
                ];
            }
        }
        return $return_arr;
    }

    /**
     * 根据楼栋uuid获取房间列表（RMS）
     * @param $uuid
     * @return array
     */
    public function getRoomByUuid($uuid){
        $room_arr = array();
        try {
            $room_arr = ICEService::getInstance()->dispatch(
                'owner/house/search',
                array(
                    'unit_uuid' => $uuid,
                    'pageIndex' => 1,
                    'pageSize' => 1000
                ),
                array(),
                'get'
            );
        } catch (Exception $e) {
            Yii::log(
                sprintf(
                    '获取 uuid %s[%s] 单元房间信息失败',
                    $uuid,
                    $e->getMessage(), $e->getCode()
                ),
                CLogger::LEVEL_ERROR,
                'colourlife.logFile.get_build'
            );
        }
        $return_arr = [];
        if(!empty($room_arr) && isset($room_arr['list'])){
            foreach($room_arr['list'] as $key=>$value){
                $return_arr[] = [
                    'room_name' =>$value['floor'].'层'.$value['roomno'],
                    'room_uuid' => $value['house_uuid'],
                ];
            }
        }
        return $return_arr;
    }

}
