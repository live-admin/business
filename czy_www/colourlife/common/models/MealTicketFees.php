<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/1/18
 * Time: 16:59
 */
class MealTicketFees extends CActiveRecord
{
    public $modelName = '饭票券';
	public $community_id;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ticket_fees';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type_id, type_name, value, number', 'required', 'on' => 'create'),
            array('type_id, number', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('type_id', 'safe', 'on'=>'search'),
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
            'MealTicketType' => array(self::BELONGS_TO, 'MealTicketType', 'type_id'),
            'MealTicketCategory' => array(self::BELONGS_TO, 'MealTicketCategory', 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'type_id' => '饭票券类型',
            'type_name' => '饭票券类型',
            'value' => '面额',
            'number' => '数量',
        );
    }

}