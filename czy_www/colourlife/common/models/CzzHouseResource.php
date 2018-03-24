<?php

/**
 * This is the model class for table "czz_house_resource".
 *
 * The followings are the available columns in table 'czz_house_resource':
 * @property string $id
 * @property string $building_id
 * @property string $room_layout_id
 * @property string $room_number
 * @property string $average_price
 * @property string $total_amount
 * @property string $deposit_amount
 * @property integer $state
 * @property integer $unlock_time
 */
class CzzHouseResource extends CActiveRecord
{
    /**
     * 未订
     */
    const STATE_UNBOOKED = 0;
    /**
     * 已订
     */
    const STATE_BOOKED = 1;
    /**
     * 作废
     */
    const STATE_INVALID = 3;
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'czz_house_resource';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('building_id, room_layout_id, room_number, average_price, total_amount, deposit_amount, state, unlock_time', 'required'),
			array('state, unlock_time', 'numerical', 'integerOnly'=>true),		    
			array('building_id, room_layout_id', 'length', 'max'=>10),
			array('room_number', 'length', 'max'=>32),
			array('average_price, total_amount, deposit_amount', 'length', 'max'=>14),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, building_id, room_layout_id, room_number, average_price, total_amount, deposit_amount, state, unlock_time', 'safe', 'on'=>'search'),
		    
		    array('deposit_amount, average_price, total_amount', 'numerical'),
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
		    'roomlayout' => array(self::BELONGS_TO, "CzzRoomLayout", "room_layout_id"),
		    'building' => array(self::BELONGS_TO, "CzzBuilding", "building_id"),
		    'paymenttypes' => array(self::HAS_MANY, "CzzPaymentType", "house_resource_id"),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'building_id' => '楼盘ID',
			'room_layout_id' => '户型', //户型id
			'room_number' => '楼栋号',
			'average_price' => '均价',
			'total_amount' => '总价',
			'deposit_amount' => '需要支付的订金',
			'state' => '房源状态', //0-未订, 1-已订, 3-作废'
			'unlock_time' => '解锁时间', // 默认为创建时间，如果当前时间小于该时间，表示该房源正在被锁定',
			
			'stateText' => '状态',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('building_id',$this->building_id,true);
		$criteria->compare('room_layout_id',$this->room_layout_id,true);
		$criteria->compare('room_number',$this->room_number,true);
		$criteria->compare('average_price',$this->average_price,true);
		$criteria->compare('total_amount',$this->total_amount,true);
		$criteria->compare('deposit_amount',$this->deposit_amount,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('unlock_time',$this->unlock_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getStateText()
	{
	    $stateTab = array(
	        0 => '未订',
	        1 => '已订',
	        3 => '作废',
	    );
	    return isset($stateTab[$this->state]) ? $stateTab[$this->state] : "未知状态";
	}
	
	public function isLocked()
	{
	    return $this->unlock_time > time();
	}
	
	/**
	 * 保存支付类型
	 * @param array $paymentTypeArr ( array( <br>
	 *     'id' => ,//id，新增的为0 <br>
	 *     'description' => ,//付款类型说明, <br>
	 *     'type' => , //付款类型,1-折扣, 2-返款 <br>
	 *     'discount' => ,//折扣 <br>
	 *     'back_amount' => , //返款金额 <br>
	 * ))
	 */
	public function savePaymentTypes($paymentTypeArr)
	{
	    if(!is_array($paymentTypeArr) || count($paymentTypeArr) == 0)
	    {
	        return;
	    }

	    CzzPaymentType::model()->deleteAll("house_resource_id=:house_resource_id", array('house_resource_id' => $this->id));
	    
	    foreach ($paymentTypeArr as $payType)
	    {
	        $ptModel = new CzzPaymentType();
	        $ptModel->attributes = $payType;
	        $ptModel->house_resource_id = $this->id;
	        $ptModel->save();
	    }
	}
	
	/**
	 * 获取支付类型的数组形式
	 * @return array (array( <br>
	 * 	        'id' => $p->id, <br>
	 *          'description' => $p->description, <br>
	 *          'type' => $p->type, <br>
	 *          'discount' => $p->discount, <br>
	 *          'back_amount' => $p->back_amount, <br>
	 *          'house_resource_id' => $p->house_resource_id, <br>
	 * ))
	 */
	public function getPaymentTypes()
	{
	    $result = array();
	    foreach($this->paymenttypes as $p)
	    {
	        $result[] = array(
	            'id' => $p->id,
	            'description' => $p->description,
	            'type' => $p->type,
	            'discount' => $p->discount,
	            'back_amount' => $p->back_amount,
	            'house_resource_id' => $p->house_resource_id,
	        );
	    }
	    return $result;
	}

	public function delete()
	{
	    CzzPaymentType::model()->deleteAll("house_resource_id=:house_resource_id", array(':house_resource_id' => $this->id));
	    return parent::delete();
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CzzHouseResource the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
