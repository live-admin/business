<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/1/18
 * Time: 16:59
 */
class MealTicket extends CActiveRecord
{
    public $modelName = '饭票券';

    const MEAL_TICKET_INIT = 0;
    const MEAL_TICKET_SEND = 1;
    const MEAL_TICKET_RECEIVE = 2;
    const MEAL_TICKET_BACK = 3;

    public $ticketStatus = array(
        self::MEAL_TICKET_INIT => '<p>已购买</p>',
        self::MEAL_TICKET_SEND => '<p class="p_color1a">已赠送</p>',
        self::MEAL_TICKET_RECEIVE => '<p class="p_color2a">已认领</p>',
        self::MEAL_TICKET_BACK => '<p class="p_color3a">已退单</p>',
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
        return 'ticket';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('type_id, status, owner_id, create_time', 'required'),
            array('type_id, status, owner_id, receive_id, create_time, send_time, receive_time', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('type_id, status, owner_id, receive_id, receive_mobile', 'safe', 'on'=>'search'),
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
            'customer' => array(self::BELONGS_TO, 'Customer', 'owner_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => '饭票券ID',
            'type_id' => '饭票券类别',
            'status' => '状态',
            'owner_id' => '赠送人',
            'receive_id' => '接受人',
            'receive_mobile' => '接收人电话',
            'create_time' => '创建时间',
            'send_time' => '赠送时间',
            'receive_time' => '认领时间',
        );
    }

    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->compare('type_id',$this->type_id);
        $criteria->compare('status',$this->status);
        $criteria->compare('owner_id',$this->owner_id);
        $criteria->compare('receive_id',$this->receive_id);
        $criteria->compare('receive_mobile', $this->receive_mobile);
        $criteria->order = 'id asc';

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    // 状态值
    public function getStatusName()
    {
        return isset($this->ticketStatus[$this->status]) ? $this->ticketStatus[$this->status] : '';
    }

    public function getImage()
    {
        if (in_array($this->status, array(self::MEAL_TICKET_INIT, self::MEAL_TICKET_SEND)))
            return $this->MealTicketCategory->imageOnUrl;

        return $this->MealTicketCategory->imageOffUrl;
    }
}