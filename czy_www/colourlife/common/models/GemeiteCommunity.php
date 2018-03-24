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
class GemeiteCommunity extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '格美特小区';

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
        return 'gemeite_community';
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
            array('gemeite_community_id', 'length', 'encoding' => 'UTF-8', 'max' => 32, 'on' => 'create, update'),
            array('gemeite_community_id', 'checkExits', 'on' => 'create'),
            array('gemeite_community_name', 'length', 'encoding' => 'UTF-8', 'max' => 64, 'on' => 'create, update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, community_id, gemeite_community_id, gemeite_community_name', 'safe', 'on' => 'search'),
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
            'community_id' => '关联小区',
            'gemeite_community_id' => '格美特小区ID',
            'gemeite_community_name' => '格美特小区名称',
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
        $criteria->compare('gemeite_community_id', $this->gemeite_community_id, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function checkExits($attribute, $params)
    {
        if (GemeiteCommunity::model()->find('community_id=:id AND gemeite_community_id=:gemeite_community_id',
            array(':id' => $this->community_id, ':gemeite_community_id' => $this->gemeite_community_id))
        ) {
            $this->addError($attribute, '已存在的格美特小区ID。');
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


}
