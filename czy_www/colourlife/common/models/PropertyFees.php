<?php

/**
 * This is the model class for table "property_fees".
 *
 * The followings are the available columns in table 'property_fees':
 * @property integer $id
 * @property integer $community_id
 * @property string $build
 * @property string $room
 * @property string $customer_name
 * @property string $colorcloud_building
 * @property string $colorcloud_unit
 * @property string $colorcloud_bills
 * @property string $colorcloud_order
 */
class PropertyFees extends CActiveRecord
{
    public $modelName = '物业缴费';
    public $cummunity_id;
    public $customer_name;
    public $room;
    public $build;
    public $colorcloud_unit;
    public $colorcloud_building;
    public $colorcloud_order;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PropertyFees the static model class
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
        return 'property_fees';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('community_id', 'numerical', 'integerOnly' => true),
            array('colorcloud_building', "checkBuild"),
            array('build, room, customer_name', 'length', 'max' => 64),
            array('customer_name', 'filter', 'filter' => 'trim'),
            array('build,room,community_id', 'required'),
            array('colorcloud_bills,colorcloud_unit,colorcloud_building', 'required'),
            array('community_id', 'compare', 'compareValue' => '0', 'operator' => '>', 'message' => '你输入的数据不正确'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('colorcloud_building,colorcloud_unit,colorcloud_bills,colorcloud_order', 'safe'),
            array('id, community_id, build, room, customer_name', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'community_id' => '小区',
            'build' => '楼栋',
            'room' => '房间号',
            'customer_name' => 'Customer Name',
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
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('build', $this->build, true);
        $criteria->compare('room', $this->room, true);
        $criteria->compare('customer_name', $this->customer_name, true);



        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    //检查Build 不能为0
    public function checkBuild($attribute, $params)
    {
        if (empty($this->colorcloud_building)) {
            $this->addError($attribute, '楼栋不能为空');
        }
    }




}
