<?php

/**
 * This is the model class for table "power_address".
 *
 * The followings are the available columns in table 'power_address':
 * @property integer $id
 * @property string $meter
 * @property string $meter_address
 * @property integer $community_id
 * @property integer $customer_id
 * @property string $customer_name
 */
class PowerAddress extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    // public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = 'PowerAddress';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PowerAddress the static model class
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
        return 'power_address';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('meter, meter_address,customer_name,community_id, customer_id', 'required', 'on' => 'apiCreate,create,update'),
            array('id, community_id, customer_id,is_deleted', 'numerical', 'integerOnly' => true),
            array('meter, customer_name', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, meter, meter_address,customer_name, community_id, customer_id,is_deleted,last_time', 'safe', 'on' => 'search'),
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
            'meter' => '电表号',
            'community_id' => '小区id',
            'customer_id' => '业主id',
            'meter_address' => '电表地址',
            'customer_name' => '电表用户名',
            'last_time' => '最后更新时间',
            'is_deleted' => '是否删除',
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
        $criteria->compare('meter', $this->meter);
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('meter_address', $this->meter_address,true);
        $criteria->compare('customer_name', $this->customer_name);

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
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }


//  ICE接入
    public function getCommunityName()
    {
        // return empty($this->community) ? '' : $this->community->name;

//  ICE接入
        if(!empty($this->community_id)){
            $community = ICECommunity::model()->findByPk($this->community_id);
            if(!empty($community)){
               return  $community['name'];
            }
        }

        return '';
    }


    //查询省市区
    public function getRegions()
    {
        if (!empty($this->community)) {
            $region = Region::model()->enabled()->findByPk($this->community->region_id);
            if (!empty($region)) {
                $regions = $region->getParents();
                $regions[] = $region;
            }
        }

        return $regions;
    }
	

}
