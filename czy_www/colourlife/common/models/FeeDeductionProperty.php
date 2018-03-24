<?php

/**
 * This is the model class for table "fee_deduction_property".
 *
 * The followings are the available columns in table 'fee_deduction_property':
 * @property integer $userId
 * @property string $orderSN
 * @property string $feeAmount
 * @property integer $feeTime
 * @property integer $status
 * @property string $remark
 */
class FeeDeductionProperty extends CActiveRecord
{	
  

	public $modelName="返款管理";
	public $customer_name;
    public $customer_mobile;
    public $startTime;
    public $endTime;
    public $customerName;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'fee_deduction_property';
	}

	static $money_status = array( "返款失败", "返款成功" );


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('userId', 'required'),
			array('orderID, userId, feeTime, status', 'numerical', 'integerOnly'=>true),
			array('orderSN', 'length', 'max'=>35),
			array('feeAmount', 'length', 'max'=>10),
			array('remark', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('userId, orderID, orderSN, feeAmount, feeTime, status, remark, customer_name, customer_mobile, startTime, endTime', 'safe', 'on'=>'search'),
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
            'customer' => array(self::BELONGS_TO, 'Customer', 'userId'),
            'property_activity' => array(self::BELONGS_TO, 'PropertyActivity', 'orderID'),
            // 'property_activity' => array(self::BELONGS_TO, 'PropertyActivity', '', 'on' => 't.orderSN = property_activity.sn'),
        );
	}

	/**
	* @return array relational rules.
	*/
	// public function relations()
	// {
	// // NOTE: you may need to adjust the relation name and the related
	// // class name for the relations automatically generated below.
	// return array(
	// 	'member' => array(self::BELONGS_TO,'ShopMember','member_id','with'=>'extends','condition'=>"member.member_state = '1'"),
	// 	'shop' => array(self::BELONGS_TO , 'ShopInfo' , '','on'=>'t.member_id = shop.member_id' ),
	// 	);
	// }
	// 查找的时候必须使用'with'关键字，例如
	// Member::model()->with('shop')->findAll($criteria);

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'userId' => '用户ID',
			'orderID' => '冲抵物业费订单ID',
			'orderSN' => '冲抵物业费订单号',
			'feeAmount' => '返款金额',
			'feeTime' => '返款日期',
			'status' => '返款状态值',
			'remark' => '说明',
			'customer_name' => '业主姓名',
            'customer_mobile' => '业主手机',
            'startTime' => '返款开始时间',
            'endTime' => '返款结束时间',
            'customerName' => '业主姓名',
		);
	}


	public function getStatusName($html = false)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$money_status[$this->status];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }

    public function getCustomerName()
    {
        return empty($this->customer) ? $this->customer_id : (empty($this->customer->name) ? $this->customer->username : $this->customer->name);
    }

    public function getName()
    {
        return empty($this->customer) ? "" : $this->customer->name;
    }


    public function getMobile()
    {
        return empty($this->customer) ? "" : $this->customer->mobile;
    }


    public function getMobileTag()
    {
        $customer = $this->customer;
        $mobile = $customer?$customer->mobile:"";
        $username = $customer?$customer->username:"";
        $customerName = empty($this->AdvanceFees) ? "" : $this->AdvanceFees->customer_name;
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
                'data-original-title' => '姓名:' . $customerName . '，帐号:' . $username),
            $mobile);
    }


    //得到投资总金额
    public function getActMount()
    {
        return empty($this->property_activity) ? "" : $this->property_activity->amount;
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

		$criteria->compare('userId',$this->userId);
		$criteria->compare('orderSN',$this->orderSN,true);
		$criteria->compare('feeAmount',$this->feeAmount,true);
		$criteria->compare('feeTime',$this->feeTime);
		$criteria->compare('status',$this->status);
		$criteria->compare('remark',$this->remark,true);

		if ($this->startTime != '') {
            $criteria->compare("`t`.feeTime", ">=" . strtotime($this->startTime." 00:00:00"));
        }

        if ($this->endTime != '') {
            $criteria->compare("`t`.feeTime", "<=" . strtotime($this->endTime." 23:59:59"));
        }

		if($this->customer_name || $this->customer_mobile){
            $criteria->with[] = 'customer';
            $criteria->compare('customer.name', $this->customer_name, true);
            $criteria->compare('customer.mobile', $this->customer_mobile, true);
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FeeDeductionProperty the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
