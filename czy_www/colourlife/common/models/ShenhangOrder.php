<?php

/**
 * This is the model class for table "shenhang_order".
 *
 * The followings are the available columns in table 'shenhang_order':
 * @property integer $id
 * @property string $Ordercode
 * @property string $Orderstate
 * @property string $Paypric
 * @property string $Username
 * @property string $Ordertime
 * @property string $Refundprice
 */
class ShenhangOrder extends CActiveRecord
{
    public $modelName = '深航订单';
    public $sn;
    public $status;
    public $Orderstate_arr = array(
        "df001" => "未支付",
        "df003" => "已支付",
        "df004" => "出票中",
        "df005" => "已出票",
        "df006" => "已取消",
        "df008" => "处理中",
        "df009" => "已退票，未退款",
        "df010" =>"全部退款",
    );
    static $third_status_arr=array(
        Item::FEES_AWAITING_PAYMENT => "待付款",
        Item::FEES_TRANSACTION_ERROR => "已付款",
        Item::FEES_TRANSACTION_SUCCESS => "交易成功",
        Item::FEES_TRANSACTION_REFUND => "退款",
        Item::FEES_TRANSACTION_LACK => "交易失败(饭票余额不足)",
        Item::FEES_TRANSACTION_FAIL => "交易失败",
        Item::FEES_PART_REFUND => "部分退款",
        
    );
    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shenhang_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Ordercode, Orderstate, Paypric, Username, Ordertime, Refundprice', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, Ordercode, Orderstate, Paypric, Username, Ordertime, Refundprice,cSn,sn,status', 'safe', 'on'=>'search'),
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
            'thirdFees' => array(self::HAS_ONE, 'ThirdFees', array('cSn'=>'Ordercode')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'Ordercode' => '第三方订单号',
			'Orderstate' => '订单状态',
			'Paypric' => '支付金额(深航)',
			'Username' => '用户（手机号码）',
			'Ordertime' => '下订单时间',
			'Refundprice' => '退款金额',
            'sn' => '订单号',
            'amount' => '总金额(彩之云)',
            'bank_pay' => '实付金额',
            'red_packet_pay' => '红包抵扣',
            'status' => '支付状态',
            'payment_id'=>'支付方式',
            'create_time'=>'下单时间',
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
//        if(isset($this->sn)){
//            $criteria->with=array(  
//                'thirdFees',  
//            );
//            $criteria->compare('thirdFees.sn', $this->sn, true);
//        }
        if(isset($this->cSn)){
            $criteria->with=array(  
                'thirdFees',  
            );
            $criteria->compare('thirdFees.cSn', $this->cSn, true);
        }
        if(isset($this->sn)){
            $criteria->with=array(  
                'thirdFees',  
            );
            $criteria->compare('thirdFees.sn', $this->sn, true);
        }
        if(isset($this->status)){
            $criteria->with=array(  
                'thirdFees',  
            );
            $criteria->compare('thirdFees.status', $this->status, true);
        }
		$criteria->compare('t.Ordercode',$this->Ordercode,true);
		$criteria->compare('t.Orderstate',$this->Orderstate,true);
		$criteria->compare('t.Paypric',$this->Paypric,true);
		$criteria->compare('t.Username',$this->Username,true);
		$criteria->compare('t.Ordertime',$this->Ordertime,true);
		$criteria->compare('t.Refundprice',$this->Refundprice,true);
        $criteria->order='t.id desc';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ShenhangOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    /*
     * @version 获取订单号
     */
    public function getSn(){
        if(!empty($this->thirdFees)){
            return $this->thirdFees->sn;
        }
    }
    /*
     * @version 获取深航订单的状态
     */
    public function getOrderState(){
        return $this->Orderstate_arr[$this->Orderstate];
    }
    /*
     * @version 获取彩之云的总金额
     */
    public function getAmount(){
        if(!empty($this->thirdFees)){
            return $this->thirdFees->amount;
        }
    }
    /*
     * @version 获取彩之云的实际金额
     */
    public function getBankPay(){
        if(!empty($this->thirdFees)){
            return $this->thirdFees->bank_pay;
        }
    }
    /*
     *@version 获取彩之云的红包抵扣
     */
    public function getRedPacketPay(){
        if(!empty($this->thirdFees)){
            return $this->thirdFees->red_packet_pay;
        }
    }
    /*
     * @version 获取彩之云的支付状态
     */
    public function getStatus(){
        if(!empty($this->thirdFees)){
            return self::$third_status_arr[$this->thirdFees->status];
        }
    }
    /*
     * @version 获取支付方式名称
     */
    public function getPayMethodName(){
        if(!empty($this->thirdFees)){
            $resultArr=Payment::model()->findByPk($this->thirdFees->payment_id);
            return $resultArr['name'];
        }
    }
    /*
     * @version 获取彩之云的支付方式id
     */
    public function getPaymentId(){
        if(!empty($this->thirdFees)){
            return $this->thirdFees->payment_id;
        }
    }
    /*
     * @version 获取彩之云的下单时间
     */
    public function getCreateTime(){
        if(!empty($this->thirdFees)){
            return $this->thirdFees->create_time;
        }
    }
}
