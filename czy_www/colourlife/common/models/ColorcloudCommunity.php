<?php

/**
 * This is the model class for table "colorcloud_community".
 *
 * The followings are the available columns in table 'colorcloud_community':
 * @property integer $id
 * @property integer $community_id
 * @property string $colorcloud_name
 */
class ColorcloudCommunity extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '彩之云小区';

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
        return 'colorcloud_community';
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
            array('colorcloud_name', 'length', 'encoding' => 'UTF-8', 'max' => 255, 'on' => 'create, update'),
            array('colorcloud_name', 'checkExits', 'on' => 'create'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, community_id, colorcloud_name', 'safe', 'on' => 'search'),
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
            'colorcloud_name' => '小区编号',
            'color_community_id' => '小区UUID',
            'color_community_name' => '小区名称',
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
        $criteria->compare('color_community_id', $this->color_community_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function checkExits($attribute, $params)
    {
        if (ColorcloudCommunity::model()->find('community_id=:id AND colorcloud_name=:name',
            array(':id' => $this->community_id, ':name' => $this->colorcloud_name))
        ) {
            $this->addError($attribute, '已存在的彩之云ID。');
        }
    }

    public function getColorCloudList()
    {
        Yii::import('common.api.ColorCloudApi');
        return ColorCloudApi::getInstance()->getCommunity();
    }

    public function getColorCloudName($colorcloud_name = '')
    {
        $list = $this->getColorCloudList();
        return empty($colorcloud_name) ? "" : $list[$colorcloud_name];
    }



    /*新增uid列表*/
    public function getColorCloudListu($keyWord='')
    {   
        Yii::import('common.api.IceApi');
        return IceApi::getInstance()->getCommunityUid($keyWord);
    }
}
