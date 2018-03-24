<?php

/**
 * This is the model class for table "discount_picture".
 *
 * The followings are the available columns in table 'discount_picture':
 * @property integer $id
 * @property integer $discount_id
 * @property string $title
 * @property string $desc
 * @property string $url
 */
class DiscountPicture extends CActiveRecord
{
    public $modelName = "图片";
    public $hasLogoAndDesc = false;
    public $logofile;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return DiscountPicture the static model class
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
        return 'discount_picture';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('desc', 'required'),
            array('discount_id', 'numerical', 'integerOnly' => true),
            array('title, url', 'length', 'max' => 255),
            array('logofile', 'safe', 'on' => 'create, update'), // 增加和编辑图片文件
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, discount_id, title, desc, url', 'safe', 'on' => 'search'),
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
            'discount' => array(self::BELONGS_TO, 'Discount', 'discount_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'discount_id' => 'Discount',
            'title' => '标题',
            'desc' => '备注',
            'url' => '路径',
            'pic' => '图片',
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
        $criteria->compare('discount_id', $this->discount_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('url', $this->url, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    protected function beforeValidate()
    {
        if (empty($this->url) && !empty($this->logofile))
            $this->url = '';

        return parent::beforeValidate();
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->logofile)) {
            $this->url = Yii::app()->ajaxUploadImage->moveSave($this->logofile, $this->url);
        }
        return parent::beforeSave();
    }

    public function getPic()
    {
        //echo Yii::app()->imageFile->getUrl($this->url);exit;
        return Yii::app()->ajaxUploadImage->getUrl($this->url);

        return '';
    }

    public function behaviors()
    {
        return array();
    }

    public function getFull_url()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->url);
    }

}
