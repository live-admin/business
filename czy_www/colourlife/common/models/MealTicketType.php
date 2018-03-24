<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/1/18
 * Time: 16:59
 */
class MealTicketType extends CActiveRecord
{
    public $modelName = '饭票券类型';

    public $typeStatus = array(
        0 => '关闭',
        1 => '开启',
    );

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ticket_type';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('category_id, status, value, create_time', 'required'),
            array('category_id, status, create_time, update_time', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('category_id, status', 'safe', 'on'=>'search'),
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
            'MealTicketCategory' => array(self::BELONGS_TO, 'MealTicketCategory', 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => '类别ID',
            'category_id' => '饭票券类型',
            'status' => '状态',
            'value' => '金额',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->compare('category_id',$this->category_id, true);
        $criteria->compare('status',$this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    // 状态值
    public function getStatusName()
    {
        return isset($this->typeStatus[$this->status]) ? $this->typeStatus[$this->status] : '';
    }
}