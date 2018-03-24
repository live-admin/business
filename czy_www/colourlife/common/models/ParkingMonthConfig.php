<?php

/**
 * This is the model class for table "gemeite_community".
 *
 * The followings are the available columns in table 'gemeite_community':
 * @property integer $id
 * @property integer $community_id
 * @property string $gemeite_community_id
 * @property string $gemeite_community_name
 */
class ParkingMonthConfig extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '停车月卡配置';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ColorcloudCommunity the static model class
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
        return 'parking_month_config';
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
            array('community_name, third_type, third_park_id, third_park_name', 'required', 'on' => 'create, update'),
            array('community_id', 'checkExits', 'on' => 'create'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, community_id, community_name, third_type', 'safe', 'on' => 'search'),
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
            'community_id' => '彩之云小区ID',
            'community_name' => '彩之云小区',
            'third_type' => '第三方停车类别',
            'third_park_id' => '第三方停车场',
            'third_park_name' => '第三方停车场名称',
            'create_time' => '添加时间',
            'update_time' => '更新时间',
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
        $criteria->compare('third_type', $this->third_type, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function checkExits($attribute)
    {
        if (ParkingMonthConfig::model()->find('community_id=:id', array(':id' => $this->community_id)))
        {
            $this->addError($attribute, '该小区已配置');
        }
    }

    /**
     * 获取格美特小区列表
     * @return array
     * @throws CException
     */
    public function getGemeiteCommunityList()
    {
        Yii::import('common.api.GemeiteApi');
        $communityList = GemeiteApi::getInstance()->commQuery();
        return $communityList;
    }

    /**
     * 第三方停车系统
     * @return array
     */
    public function getThirdTypeNames()
    {
        return array(
            Item::PARKING_TYPE_GEMEITE => '格美特',
            Item::PARKING_TYPE_AIKE => '艾科',
            Item::PARKING_TYPE_HANWANG => '汉王'
        );
    }

    /**
     * 获取第三方停车系统名称
     * @param $id
     * @return string
     */
    public function getThirdTypeName()
    {
        $names = $this->getThirdTypeNames();

        return isset($names[$this->third_type]) ? $names[$this->third_type] : '';
    }
}
