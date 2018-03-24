<?php

/**
 * This is the model class for table "picture".
 *
 * The followings are the available columns in table 'picture':
 * @property integer $id
 * @property string $model
 * @property integer $object_id
 * @property string $url
 * @property integer $is_deleted
 */
class Picture extends CActiveRecord
{
    public $file;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Picture the static model class
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
        return 'picture';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('object_id, is_deleted', 'numerical', 'integerOnly' => true),
            array('model, url', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, model, object_id, url, is_deleted', 'safe', 'on' => 'search'),
            array('file', 'file', 'types' => 'jpg, gif, png', 'safe' => true, 'on' => 'create'), // 增加图片文件
        );

    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'model' => 'Model',
            'object_id' => 'Object',
            'url' => 'Url',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('model', $this->model, true);
        $criteria->compare('object_id', $this->object_id);
        $criteria->compare('url', $this->url, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    protected function beforeValidate()
    {
        $this->file = CUploadedFile::getInstanceByName('file');
        return parent::beforeValidate();
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        $this->url = Yii::app()->imageFile->saveToFile($this->file, $this->url);
        return parent::beforeSave();
    }

    public function getFullUrl()
    {
        return Yii::app()->imageFile->getUrl($this->url);
    }

    public function updateBelongTo($model, $id)
    {
        $this->model = $model;
        $this->object_id = $id;
        return $this->update();
    }

    public function getPortraitUrl()
    {
        return Yii::app()->imageFile->getUrl($this->url);
    }

}
