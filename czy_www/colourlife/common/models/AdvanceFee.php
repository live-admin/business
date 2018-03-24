<?php

/**
 * This is the model class for table "advance_fee".
 *
 * The followings are the available columns in table 'advance_fee':
 * @property integer $id
 * @property integer $community_id
 * @property string $build
 * @property string $room
 * @property string $customer_name
 * @property string $colorcloud_building
 * @property string $colorcloud_unit
 * @property string $colorcloud_order
 *  * @property string $new_colorcloud_unit
 */
class AdvanceFee extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '预缴费订单';
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
     * @return AdvanceFee the static model class
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
        return 'advance_fee';
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
            array(' customer_name', 'length', 'max' => 64),
            array('build, colorcloud_building, colorcloud_order, colorcloud_unit, new_colorcloud_unit', 'length', 'max' => 255),
            array(' room', 'length', 'max' => 1000),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, community_id, build, room, customer_name, colorcloud_building, colorcloud_unit, colorcloud_order ,new_colorcloud_unit', 'safe', 'on' => 'search'),
        );
    }
    
    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'community' => array(self::BELONGS_TO, 'ICECommunity', 'community_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'community_id' => '所属小区',
            'build' => '楼栋',
            'room' => '房间号',
            'customer_name' => '业主姓名',
            'colorcloud_building' => '彩之云楼栋',
            'colorcloud_unit' => '彩之云门牌号',
            'colorcloud_order' => '彩之云订单号',
            'new_colorcloud_unit' => '新彩之云门牌号',

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
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('build', $this->build, true);
        $criteria->compare('room', $this->room, true);
        $criteria->compare('customer_name', $this->customer_name, true);
        $criteria->compare('colorcloud_building', $this->colorcloud_building, true);
        $criteria->compare('colorcloud_unit', $this->colorcloud_unit, true);
        $criteria->compare('colorcloud_order', $this->colorcloud_order, true);
        $criteria->compare('new_colorcloud_unit', $this->new_colorcloud_unit, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array();
    }

    public function getMyBalance()
    {

        $data = OthersAdvanceFees::model()->getAdvanceFeeByUnit($this->colorcloud_unit);
        return empty($data[0]["Balance"]) ? 0 : $data[0]["Balance"];
    }

    public function getColorUnit($build)
    {
        Yii::import('common.api.IceApi');
        $result = IceApi::getInstance()->getUnitsWithBuilding($build);
        $arr = array();
        if(!empty($result['data']))
        {
            foreach($result['data'] as $key => $value)
            {
                $name = $value['name'];
                $name = (isset($value['floor']) && $value['floor']) ? $value['floor'].'层'.$name : $name;
                $name = isset($value['unitname']) ? $value['unitname'].$name : $name;
                $arr[$name.'<=====>'.$value['id']] = $name.'<=====>'.$value['id'];
            }
        }
        return $arr;
    }
}
