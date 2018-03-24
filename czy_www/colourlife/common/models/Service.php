<?php

/**
 * This is the model class for table "service".
 *
 * The followings are the available columns in table 'service':
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $contact
 * @property integer $shop_id
 * @property integer $state
 * @property integer $is_deleted
 */
class Service extends CActiveRecord
{
    public $modelName = '服务';
    public $categoryName;
    public $shopName;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Service the static model class
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
        return 'service';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title,category_id,contact', 'required', 'on' => 'create'),
            array('category_id, shop_id, is_deleted', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('categoryName,shopName,title,state', 'safe', 'on' => 'search'),
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
            'serviceCategory' => array(self::BELONGS_TO, 'ServiceCategory', 'category_id'),
            'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'category_id' => '服务分类',
            'title' => '服务标题',
            'contact' => '服务内容',
            'shop_id' => '商家名',
            'state' => '状态',
            'shopName' => '商家名',
            'categoryName' => '服务分类',
            'is_deleted' => 'Is Deleted',
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
        $criteria->join .= " left join `service_category` on `t`.`category_id`=`service_category`.`id`";
        $criteria->join .= " left join `shop` on `t`.`shop_id`=`shop`.`id`";

        $criteria->compare('`t`.id', $this->id);
        $criteria->compare('`t`.category_id', $this->category_id);
        $criteria->compare('`t`.title', $this->title, true);
        $criteria->compare('`t`.contact', $this->contact, true);
        $criteria->compare('`t`.shop_id', $this->shop_id);
        $criteria->compare('`t`.state', $this->state);
        $criteria->compare("`service_category`.`name`", $this->categoryName, true);
        $criteria->compare("`shop`.`name`", $this->shopName, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function shopsearch()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;
        $criteria->join .= " left join `service_category` on `t`.`category_id`=`service_category`.`id`";
        $criteria->join .= " left join `shop` on `t`.`shop_id`=`shop`.`id`";

        $criteria->compare('`t`.id', $this->id);
        $criteria->compare('`t`.category_id', $this->category_id);
        $criteria->compare('`t`.title', $this->title, true);
        $criteria->compare('`t`.contact', $this->contact, true);
        $criteria->compare('`t`.shop_id', Yii::app()->user->id);
        $criteria->compare('`t`.state', $this->state);
        $criteria->compare("`service_category`.`name`", $this->categoryName, true);
        $criteria->compare("`shop`.`name`", $this->shopName, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
            'OnSaleBehavior' => array(
                'class' => 'common.components.behaviors.OnSaleBehavior',
            ),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    public function getNameHtml()
    {
        return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '电话:' . $this->shop->tel . '手机:' . $this->shop->mobile . '地址:' . $this->shop->address), $this->shop->name);
    }

}
