<?php

/**
 * This is the model class for table "parking_address".
 *
 * The followings are the available columns in table 'parking_address':
 * @property integer $id
 * @property string $car_number
 * @property integer $community_id
 * @property integer $customer_id
 * @property integer $build_id
 * @property string $room
 */
class ParkingAddress extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    // public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = 'ParkingAddress';
    public $is_activity;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ParkingAddress the static model class
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
        return 'parking_address';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('car_number, community_id, customer_id,room,build_id', 'required', 'on' => 'apiCreate,create,update'),
            array('car_number, community_id, room, build_id', 'required', 'on' => 'newApiCreate'),
			array('build_id', "checkBuild"),
            array('id, community_id, customer_id, build_id', 'numerical', 'integerOnly' => true),
            array('car_number, room', 'length', 'max' => 255),
            array('car_number_repeat', 'checkCarNumber', 'on' => 'apiCreate,create,update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, car_number, community_id, customer_id, build_id, room, is_activity', 'safe', 'on' => 'search'),
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
            'build_list' => array(self::BELONGS_TO, 'Build', 'build_id'),
            'customer' => array(self::BELONGS_TO, "Customer", 'customer_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'car_number' => '车牌号',
            'community_id' => '小区id',
            'customer_id' => '业主id',
            'build_id' => '楼栋id',
            'room' => '房间号',
            'is_activity'=>'是否为活动',
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
        $criteria->compare('car_number', $this->car_number, true);
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('build_id', $this->build_id);
        $criteria->compare('room', $this->room, true);
        $criteria->compare('is_activity', $this->is_activity);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => null,
                'updateAttribute' => 'last_time',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function NewcheckCarNumber($attribute, $params)
    {   
        if(!empty($this->is_activity)){
            $result = $this->find('car_number=:car_number and customer_id=:customer_id and build_id=:build_id and room=:room and is_activity=:is_activity', array(':car_number' => $this->car_number, ':customer_id' => Yii::app()->user->id, ':build_id' => $this->build_id, ':room' => $this->room, ':is_activity' => 1));

            if (!$this->hasErrors() && (!empty($result))) {
                $this->addError($attribute, '该地址已存在!');
            }
        }else{
            $result = $this->find('car_number=:car_number and customer_id=:customer_id and build_id=:build_id and room=:room', array(':car_number' => $this->car_number, ':customer_id' => Yii::app()->user->id, ':build_id' => $this->build_id, ':room' => $this->room));

            $is_self = true;
            if (!empty($result) && isset($this->id)) {

                if ($result->id == $this->id) {

                    $is_self = false;
                }
            }

            if (!$this->hasErrors() && (!empty($result)) && $is_self) {
                $this->addError($attribute, '该地址已存在!' . $result->id);
            }
        }    
        
    }



    public function checkParkingAddress()
    {
        $result = $this->find('car_number=:car_number and community_id=:community_id and customer_id=:customer_id and build_id=:build_id and room=:room and is_activity=:is_activity',array(':car_number' => $this->car_number, ':community_id' =>$this->community_id, ':customer_id' => Yii::app()->user->id, ':build_id' => $this->build_id, ':room' => $this->room, ':is_activity' => 1));

        $is_self = false;//无地址
        if ($result) {
            $is_self = true;//有地址
        }
        return $is_self;
    }



    public function checkCarNumber($attribute, $params)
    {
        $result = $this->find('car_number=:car_number and customer_id=:customer_id and build_id=:build_id and room=:room',
            array(':car_number' => $this->car_number, ':customer_id' => Yii::app()->user->id, ':build_id' => $this->build_id, ':room' => $this->room));

        $is_self = true;
        if (!empty($result) && isset($this->id)) {

            if ($result->id == $this->id) {

                $is_self = false;
            }
        }

        if (!$this->hasErrors() && (!empty($result)) && $is_self) {
            $this->addError($attribute, '该地址已存在!' . $result->id);
        }
    }




    public function getCommunityName()
    {
//      ICE 接入小区名字
        $community = ICECommunity::model()->findbypk($this->community_id);
        if(!empty($community)){
            return $community['name'];
        }else{
            return '';
        }
//        return empty($this->community) ? '' : $this->community->name;
    }

    public function getBuildName()
    {
        return empty($this->build) ? '' : $this->build->name;
    }

    //查询省市区
    public function getRegions()
    {
//        if (!empty($this->community)) {
//            $region = Region::model()->enabled()->findByPk($this->community->region_id);
//            if (!empty($region)) {
//                $regions = $region->getParents();
//                $regions[] = $region;
//            }
//        }

//      查ice的省市区数据
        if (!empty($this->community_id)) {
            $community = ICECommunity::model()->findByPk($this->community_id);
            $Regiondata = $community->ICEGetCommunityRegions();
            if (!empty($community)) {
//              ICE bugfix 多了一层数组
                $regions = $Regiondata;
            }
        }
        return $regions;
    }
	
	//检查Build 不能为0
    public function checkBuild($attribute, $params)
    {
        if ($this->build_id <= 0) {
            $this->addError($attribute, '楼栋不能为0!');
        }
    }
}
