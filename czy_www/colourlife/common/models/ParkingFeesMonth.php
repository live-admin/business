<?php

/**
 * This is the model class for table "parking_fees".
 *
 * The followings are the available columns in table 'parking_fees':
 * @property integer $id
 * @property string $car_number
 * @property string $room
 * @property integer $community_id
 * @property integer $type_id
 * @property integer $period
 * @property integer $build_id
 */
class ParkingFeesMonth extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ParkingFees the static model class
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
        return 'parking_fees_month';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('community_id, build_id, fee_number, fee_unit_id', 'numerical', 'integerOnly' => true),
            array('build_id, community_id, fee_unit_id, fee_unit, fee_number, car_number, third_park_id', 'required'),
            array('build_id', "checkBuild"),
            array('community_id, build, fee_number', 'compare', 'compareValue' => '0', 'operator' => '>', 'message' => '你输入的数据不正确'),
            array('room', 'length', 'max' => 16),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
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
            'type' => array(self::BELONGS_TO, 'ParkingFeesType', 'fee_unit'),
            'build' => array(self::BELONGS_TO, 'Build', 'build_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'community_id' => '缴费小区',
            'build_id' => '楼栋',
            'room' => '房间号',
            'third_park_id' => '停车场ID',
            'car_number' => '车牌号',
            'fee_unit' => '缴费标准类型',
            'fee_number' => '缴费数量',
            'third_order_id' => '缴费订单号',
            'third_park_type' => '缴费商家'
        );
    }

    public function getTypeName()
    {
        //return !empty($this->type) ? $this->type->name : '';
    }

    //检查Build 不能为0
    public function checkBuild($attribute, $params)
    {
        if ($this->build_id <= 0) {
            $this->addError($attribute, '楼栋不能为0!');
        }
    }

}
