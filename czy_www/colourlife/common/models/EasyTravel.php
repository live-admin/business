<?php

/**
 * This is the model class for table "easy_travel".
 *
 * The followings are the available columns in table 'easy_travel':
 * @property integer $id
 * @property string $uid
 * @property string $orderId
 * @property string $orderType
 * @property string $productDetails
 * @property string $productNumber
 * @property string $timeInfo
 * @property string $receiver
 * @property string $contact
 * @property string $totalFee
 * @property string $orderStatus
 * @property string $createTime
 * @property string $updateTime
 */
class EasyTravel extends CActiveRecord
{

	public $modelName = '彩旅游订单';
	public $sn;
	public $status;
	public $orderStatus_arr = array(
		"0" => "未付款",
		"1" => "已付款",
		"2" => "已发券",
		"3" => "已验证",
		"4" => "已完成",
		"-1" => "已取消",
		"-2" => "申请退款中",
		"-3" => "已退款",
	);
	static $third_status_arr=array(
		Item::FEES_AWAITING_PAYMENT => "待付款",
		Item::FEES_TRANSACTION_ERROR => "已付款",
		Item::FEES_TRANSACTION_SUCCESS => "交易成功",
		Item::FEES_TRANSACTION_REFUND => "退款",
		Item::FEES_TRANSACTION_LACK => "交易失败(饭票余额不足)",
		Item::FEES_TRANSACTION_FAIL => "交易失败",

	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'easy_travel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('uid, orderId, orderType, productDetails, productNumber, timeInfo, totalFee, orderStatus', 'required'),
			array('uid, orderId, productDetails, productNumber, timeInfo, receiver, contact, orderStatus, createTime, updateTime', 'length', 'max'=>100),
			array('orderType', 'length', 'max'=>20),
			array('totalFee', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, uid, orderId, orderType, productDetails, productNumber, timeInfo, receiver, contact, totalFee, orderStatus, createTime, updateTime,cSn,sn,status', 'safe', 'on'=>'search'),
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
			'thirdFees' => array(self::HAS_ONE, 'ThirdFees', array('cSn'=>'orderId')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'uid' => '彩生活用户编号',
			'orderId' => '彩旅游订单编号',
			'orderType' => '订单类型',
			'productDetails' => '商品名称',
			'productNumber' => '商品数量',
			'timeInfo' => '入住、离店日期或出游日期',
			'receiver' => '联系人',
			'contact' => '联系方式',
			'totalFee' => '订单总价',
			'orderStatus' => '订单状态',
			'createTime' => '创单时间',
			'updateTime' => '更新时间',
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
		$criteria->compare('uid',$this->uid,true);
		$criteria->compare('orderId',$this->orderId,true);
		$criteria->compare('orderType',$this->orderType,true);
		$criteria->compare('productDetails',$this->productDetails,true);
		$criteria->compare('productNumber',$this->productNumber,true);
		$criteria->compare('timeInfo',$this->timeInfo,true);
		$criteria->compare('receiver',$this->receiver,true);
		$criteria->compare('contact',$this->contact,true);
		$criteria->compare('totalFee',$this->totalFee,true);
		$criteria->compare('orderStatus',$this->orderStatus,true);
		$criteria->compare('createTime',$this->createTime,true);
		$criteria->compare('updateTime',$this->updateTime,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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
     * @version 获取订单的状态
     */
	public function getOrderState(){
		return $this->orderStatus_arr[$this->orderStatus];
	}

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

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EasyTravel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


}
