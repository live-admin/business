<?php

/**
 * This is the model class for table "parking_fees_type".
 *
 * The followings are the available columns in table 'parking_fees_type':
 * @property integer $id
 * @property string $name
 * @property string $fees
 * @property integer $community_id
 */
class ParkingFeesType extends CActiveRecord
{
    public $modelName = '停车费类型';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ParkingFeesType the static model class
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
        return 'parking_fees_type';
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
            array('fees', 'required', 'on' => 'create, update'),
            array('state', 'required', 'on' => 'create'),
            array('state', 'checkEnable', 'on' => 'enable'),
            array('state', 'checkDisable', 'on' => 'disable'),
            array('is_deleted', 'checkDelete', 'on' => 'delete'),
            array('community_id, state, is_deleted,display', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 50, 'on' => 'create, update'),
            array('id, name, fees,display', 'safe', 'on' => 'search'),
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
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
            'feesCount' => array(self::STAT, 'ParkingFees', 'type_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '名称',
            'fees' => '费用',
            'community_id' => '小区',
            'state' => '状态',
        	'display' => '前台是否显示',
            'displayName' =>'前台是否显示',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('fees', $this->fees, true);
        $criteria->compare('community_id', $this->community_id);

        return new CActiveDataProvider($this, array(
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
//		用ice小区数据判断
	    $ICECommunity = ICECommunity::model()->ICEfindbypk($this->community_id);
	    if (!$ICECommunity || $ICECommunity['islock'] !== 'N') {
		    $this->addError($attribute, '指定的小区不存在或被禁用，无法创建、修改或启用' . $this->modelName);
	    }

//        if (!$this->hasErrors() && (empty($this->community) || $this->community->isDisabled)) {
//            $this->addError($attribute, '指定的小区不存在或被禁用，无法创建、修改或启用' . $this->modelName);
//        }
    }

    public function checkDisable($attribute, $params)
    {
        if (!$this->hasErrors() && (!empty($this->feesCount))) {
            $this->addError($attribute, '该' . $this->modelName . '存在停车缴费单，不能被禁用。');
        }
    }

    public function checkDelete($attribute, $params)
    {
        if (!$this->hasErrors() && (!empty($this->feesCount))) {
            $this->addError($attribute, '该' . $this->modelName . '存在停车缴费单，不能被删除。');
        }
    }
    public function getIsDisplay(){
    	if($this->display){
    		return false;
    	}else{
    		return true;
    	}
    }
    
    public function getDisplayName(){
    	if($this->display){
    		return "不显示";
    	}else{
    		return "显示";
    	}
    	
    }

    public function show(){
        $this->display = 0 ;

        if($this->save()){
            return true;
        }else{
            return false;
        }
    }

    public function hide(){
        $this->display = 1 ;
        if($this->save()){
            return true;
        }else{
            return false;
        }
    }

}
