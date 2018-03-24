<?php

/**
 * This is the model class for table "essay".
 *
 * The followings are the available columns in table 'essay':
 * @property integer $id
 * @property string $title
 * @property string $logo
 * @property integer $category_id
 * @property integer $type
 * @property string $url
 * @property string $keys
 * @property string $content
 * @property string $brief
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $is_deleted
 * @property integer $display_order
 */
class Essay extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    // public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = '文章';

    public $typesNames = array('1' => '内容', '2' => '外部链接');
    public $logoFile;


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Essay the static model class
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
        return 'essay';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title,category_id', 'required', 'on' => 'create,update'),
            array('category_id, type, create_time, update_time, is_deleted,display_order', 'numerical', 'integerOnly' => true),
            array('title, url, keys, brief', 'length', 'max' => 255),
            array('content,logoFile', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, title, logo, category_id, type, url, keys, content, brief, create_time, update_time, is_deleted', 'safe', 'on' => 'search'),
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
            'category' => array(self::BELONGS_TO, 'EssayCategory', 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '标题',
            'logo' => '图片',
            'category_id' => '分类',
            'type' => '类型',
            'url' => 'Url地址',
            'keys' => '关键字',
            'content' => '详细内容',
            'brief' => '简介',
            'create_time' => '创建时间',
            'update_time' => '最后更新时间',
            'is_deleted' => '是否删除',
            'display_order' => '权重',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('logo', $this->logo, true);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('type', $this->type);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('keys', $this->keys, true);
        $criteria->compare('content', $this->content, true);
        $criteria->compare('brief', $this->brief, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);
        $criteria->order = 'display_order desc,create_time desc';

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(

            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    public function getTypes()
    {
        return $this->typesNames;
    }

    public function getTypeName()
    {
        return $this->typesNames[$this->type];
    }

    public function getLogoUrl()
    {
        return Yii::app()->imageFile->getUrl($this->logo, '/common/images/nopic-map.jpg');
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->logoFile)) {
            $this->logo = Yii::app()->ajaxUploadImage->moveSave($this->logoFile, $this->logo);
        }
        return parent::beforeSave();
    }

}
