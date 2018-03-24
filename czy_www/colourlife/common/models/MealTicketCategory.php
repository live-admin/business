<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/1/18
 * Time: 16:59
 */
class MealTicketCategory extends CActiveRecord
{

    public $modelName = '饭票券分类';

    public $categoryStatus = array(
        0 => '关闭',
        1 => '开启',
    );

    public $imageOn; //图片

    public $imageOff; //图片

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ticket_category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, detail, status, create_time', 'required'),
            array('status, create_time, update_time', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>255),
            array('imageOn, imageOff', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('name, status', 'safe', 'on'=>'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => '类别ID',
            'name' => '名称',
            'detail' => '描述',
            'status' => '状态',
            'image_on' => '开启状态图片',
            'image_off' => '关闭状态图片',
            'imageOn' => '开启状态图片',
            'imageOff' => '关闭状态图片',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        );
    }

    //开启图片
    public function getImageOnUrl()
    {
        return Yii::app()->imageFile->getUrl($this->image_on, '/common/images/nopic-map.jpg');
    }

    //开启图片
    public function getImageOffUrl()
    {
        return Yii::app()->imageFile->getUrl($this->image_off, '/common/images/nopic-map.jpg');
    }

    // 状态值
    public function getStatusName()
    {
        return isset($this->categoryStatus[$this->status]) ? $this->categoryStatus[$this->status] : '';
    }
    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->imageOn)) {
            $this->image_on = Yii::app()->ajaxUploadImage->moveSave($this->imageOn, $this->image_on);
        }
        if (!empty($this->imageOff)) {
            $this->image_off = Yii::app()->ajaxUploadImage->moveSave($this->imageOff, $this->image_off);
        }
        return parent::beforeSave();
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name, true);
        $criteria->compare('status',$this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function getKeyValue()
    {
        $list = self::model()->findAll();
        $result = array();
        foreach ($list as $row) {
            $result[$row->id] = $row->name;
        }

        return $result;
    }
}