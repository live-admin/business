<?php

/**
 * This is the model class for table "property_address".
 *
 * The followings are the available columns in table 'property_address':
 * @property integer $id
 * @property integer $customer_id
 * @property string $room_id
 * @property string $build_id
 * @property integer $community_id
 * @property string $room
 * @property string $build
 * @property string $customer
 */
class OldPropertyAddress extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    // public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = 'OldPropertyAddress';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return OldPropertyAddress the static model class
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
        return 'old_property_address';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id, community_id', 'numerical', 'integerOnly' => true),
            array('room_id, room, build, customer, build_id', 'length', 'max' => 255),

            array('room_id, room, build,customer_id,community_id,build_id', 'required', 'on' => 'apiCreate,create,update'),
            array('room_id', 'checkRoom', 'on' => 'apiCreate,create,update'),

            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, customer_id, room_id, build_id, community_id, room, build, customer', 'safe', 'on' => 'search'),
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
            'customer_info' => array(self::HAS_MANY, 'Customer', 'customer_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'customer_id' => '业主id',
            'room_id' => 'ERP房间id',
            'build_id' => 'ERP楼栋id',
            'community_id' => '小区id',
            'room' => 'ERP房间名',
            'build' => 'ERP楼栋名',
            'customer' => '缴费人',
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
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('room_id', $this->room_id, true);
        $criteria->compare('build_id', $this->build_id);
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('room', $this->room, true);
        $criteria->compare('build', $this->build, true);
        $criteria->compare('customer', $this->customer, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array();
    }

    public function checkRoom($attribute, $params)
    {
        $result = $this->find('room_id=:room_id and customer_id=:customer_id', array(':room_id' => $this->room_id, ':customer_id' => Yii::app()->user->id));

        $is_self = true;
        if (!empty($result) && isset($this->id)) {
            if ($result->id == $this->id) {
                $is_self = false;
            }
        }

        if (!$this->hasErrors() && (!empty($result)) && $is_self) {
            $this->addError($attribute, '该地址已存在!');
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

    ///查询省市区
    public function getRegions()
    {
//        if (!empty($this->community)) {
//            $region = Region::model()->enabled()->findByPk($this->community->region_id);
//            if (!empty($region)) {
//                $regions = $region->getParents();
//                $regions[] = $region;
//            }
//        }
        $regions = array();
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

}
