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
class ParkingFees extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ParkingFees the static model class
     */
    public $car_number;
    public $parking_id;
    public $type_id;
    public $cummunity_id;
    public $period;
    public $room;
    public $build_id;
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'parking_fees';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('community_id, type_id, build_id', 'numerical', 'integerOnly' => true),
            array('build_id, community_id', 'required'),
            array('build_id', "checkBuild"),
            array('community_id, build', 'compare', 'compareValue' => '0', 'operator' => '>', 'message' => '你输入的数据不正确'),
            array('car_number', 'length', 'max' => 20),
            array('room', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('period', 'safe'),
            array('id, car_number, room, community_id, type_id,build_id', 'safe', 'on' => 'search'),
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
            'type' => array(self::BELONGS_TO, 'ParkingFeesType', 'type_id'),
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
            'car_number' => 'Car Number',
            'parking_card_number' => 'Parking Card Number',
            'community_id' => '小区',
            'build_id' => '楼栋',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('car_number', $this->car_number, true);
        $criteria->compare('parking_card_number', $this->parking_card_number, true);
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('type_id', $this->type_id);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getTypeName()
    {
        return !empty($this->type) ? $this->type->name : '';
    }

    //检查Build 不能为0
    public function checkBuild($attribute, $params)
    {
        if ($this->build_id <= 0) {
            $this->addError($attribute, '楼栋不能为0!');
        }
    }

}
