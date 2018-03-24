<?php

/**
 * This is the model class for table "integral_event".
 *
 * The followings are the available columns in table 'integral_event':
 * @property integer $id
 * @property string $key
 * @property string $event
 * @property string $num
 * @property integer $state
 * @property string $desc
 * @property integer $create_time
 * @property integer $is_ratio
 * @property integer $type
 */
class IntegralEvent extends CActiveRecord
{
    public $modelName = '积分事件管理';
    const TYPE_FIXED = 0;
    const TYPE_RATIO = 1;
    public static $_type = array(
        self::TYPE_FIXED => '固定赠送',
        self::TYPE_RATIO => '比例赠送',
    );

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'integral_event';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('key, event', 'required'),
			array('state, create_time, type, is_ratio', 'numerical', 'integerOnly'=>true),
			array('key', 'length', 'max'=>45),
			array('event', 'length', 'max'=>200),
			array('num', 'length', 'max'=>10),
			array('desc', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, key, event, num, state, desc, create_time, type, is_ratio', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'key' => 'Key',
			'event' => '事件名',
			'num' => '赠送积分数量/比例',
			'state' => '状态',
			'desc' => '备注',
			'create_time' => '创建时间',
            'is_ratio' => '是否可使用比例赠送',
            'type' => '赠送类型',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('`key`',$this->key,true);
		$criteria->compare('event',$this->event,true);
		$criteria->compare('num',$this->num,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('create_time',$this->create_time);
        $criteria->compare('type', $this->type);
        $criteria->compare('is_ratio', $this->is_ratio);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return IntegralEvent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 物业费缴费|停车费|预缴费|充值 赠送积分
     * @param OthersFees $order
     * @return bool
     */
    public static function fees(OthersFees $order)
    {

        if(empty($order->customer_id) || empty($order->amount) || empty($order->model)){
            return false;
        }
        $key = '';
        $note = '';
        switch($order->model)
        {
            case 'ParkingFees'://停车费
                $note = '停车费缴费';
                $key = 'parking_fees';
                break;
            case 'PropertyFees'://物业缴费
                $key = 'property_fees';
                $note = '物业缴费';
                break;
            case 'AdvanceFees'://预缴费
            case 'AdvanceFee':
                $key = 'advance_fees';
                $note = '预缴费缴费';
                break;
            case 'VirtualRecharge'://充值
                $key = 'virtual_recharge';
                $note = '充值';
                break;
        }
        if(empty($key)){
            return false;
        }
        Customer::model()->changeCredit($key, array('amount' => $order->amount));
    }

    /**
     * 业主购买商品付款成功赠送积分
     * @param Order $order
     * @return bool
     */
    public static function customerOrder(Order $order)
    {
        $key = 'shopping';
        Customer::model()->changeCredit($key, array('amount' => $order->amount));
    }



    /**
     * 零物业费付款成功赠送积分
     * @param Order $order
     * @return bool
     */
    public static function activityOrder(PropertyActivity $order)
    {
        $key = 'property_activity';
        Customer::model()->changeCredit($key, array('amount' => $order->amount));
    }



    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }

    public function getTypeName()
    {
        return self::$_type[$this->type];
    }
}
