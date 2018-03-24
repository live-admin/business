<?php

/**
 * This is the model class for table "sf_express_son".
 *
 * The followings are the available columns in table 'sf_express_son':
 * @property integer $id
 * @property string $expressSn
 * @property string $subExpressSn
 * @property string $subOrderStatus
 * @property string $subOrderAmount
 * @property string $subProductName
 * @property string $subProductNum
 * @property string $subProductPrice
 * @property string $subRefundAmount
 * @property string $subRefundId
 * @property integer $createTime
 * @property integer $updateTime
 */
class SfExpressSon extends CActiveRecord
{
	public $modelName = '顺丰优选订单';
	public $sn;
	public $status;
	public $orderStatus;
	public $customer_id;
	public $thirdFess_id;
	public $mobile;
	public $Orderstate_arr = array(
		"0" => "初始状态",
		"2" => "待客服审核",
		"3" => "待付款",
		"5" => "待备货",
		"6" => "待发货",
		"7" => " 待确认收货",
		"9" => "已完成",
		"10" => "退货订单",
		"13" => "已取消订单",
		"14" => "无效订单",
		"16" =>"拒收订单",
		"20" => "退款状态",
		"21" => "已通知退款",
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

	static $sf_order_status_arr = array(
		"0" => "初始状态",
		"2" => "待客服审核",
		"3" => "待付款",
		"5" => "待备货",
		"6" => "待发货",
		"7" => " 待确认收货",
		"9" => "已完成",
		"10" => "退货订单",
		"13" => "已取消订单",
		"14" => "无效订单",
		"20" => "退款状态",
		"21" => "已通知退款",
		"16" =>"拒收订单",
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sf_express_son';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('expressSn, subExpressSn, subOrderStatus, subOrderAmount, subProductName, subProductNum, subProductPrice, createTime, updateTime', 'required'),
			array('createTime, updateTime', 'numerical', 'integerOnly'=>true),
			array('expressSn, subExpressSn, subOrderAmount, subProductName, subProductNum, subProductPrice, subRefundAmount, subRefundId', 'length', 'max'=>250),
			array('subOrderStatus', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, expressSn, subExpressSn, subOrderStatus, subOrderAmount, subProductName, subProductNum, subProductPrice, subRefundAmount, subRefundId, createTime, updateTime, sn, status, mobile', 'safe', 'on'=>'search'),
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
			'thirdFees' => array(self::HAS_ONE, 'ThirdFees', array('cSn'=>'expressSn')),
			'sfExpress' => array(self::HAS_ONE, 'SfExpress', array('expressSn'=>'expressSn')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'expressSn' => '顺丰优选主订单号',
			'subExpressSn' => '顺丰优选子订单号',
			'subOrderStatus' => '子订单状态',
			'subOrderAmount' => '子订单金额',
			'subProductName' => '子订单商品名称',
			'subProductNum' => '子订单商品数量',
			'subProductPrice' => '子订单商品单价',
			'subRefundAmount' => '子订单退款金额',
			'subRefundId' => '子订单退款ID',
			'createTime' => '创建时间',
			'updateTime' => '更新时间',
			'sn' => '彩之云订单号',
			'mobile' => '手机号码',
			'status' => '彩之云订单状态'
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
			$criteria->with[]='thirdFees';
			$criteria->compare('thirdFees.cSn', $this->cSn, true);
		}
		if(isset($this->sn)){
			$criteria->with[]='thirdFees';
			$criteria->compare('thirdFees.sn', $this->sn, true);
		}
		if(isset($this->status)){
			$criteria->with[]='thirdFees';
			$criteria->compare('thirdFees.status', $this->status);
		}

		if(isset($this->orderStatus)){
			$criteria->with[]='sfExpress';
			$criteria->compare('sfExpress.orderStatus', $this->orderStatus, true);
		}

		if(isset($this->mobile)){
			$criteria->with[]='sfExpress';
			$criteria->compare('sfExpress.receivedMobile', $this->mobile, true);
		}

		$criteria->compare('t.id',$this->id);
		$criteria->compare('t.expressSn',$this->expressSn,true);
		$criteria->compare('t.subExpressSn',$this->subExpressSn,true);
		$criteria->compare('t.subOrderStatus',$this->subOrderStatus);
		$criteria->compare('t.subOrderAmount',$this->subOrderAmount,true);
		$criteria->compare('t.subProductName',$this->subProductName,true);
		$criteria->compare('t.subProductNum',$this->subProductNum,true);
		$criteria->compare('t.subProductPrice',$this->subProductPrice,true);
		$criteria->compare('t.subRefundAmount',$this->subRefundAmount,true);
		$criteria->compare('t.subRefundId',$this->subRefundId,true);
		$criteria->compare('t.createTime',$this->createTime);
		$criteria->compare('t.updateTime',$this->updateTime);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>10,
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SfExpressSon the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
	/*
     * @version 获取彩之云的支付状态
     */
	public function getStatus(){
		if(!empty($this->thirdFees)){
			return self::$third_status_arr[$this->thirdFees->status];
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
     * @version 获取彩之云的总金额
     */
	public function getAmount(){
		if(!empty($this->thirdFees)){
			return $this->thirdFees->amount;
		}
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
     * @version 获取顺丰优选主订单状态
     */
	public function getOrderStatus(){
		if(!empty($this->sfExpress)){
			return self::$sf_order_status_arr[$this->sfExpress->orderStatus];
		}
	}

	/*
     * @version 获取顺丰优选主订单手机号码
     */
	public function getMobile(){
		if(!empty($this->sfExpress)){
			return $this->sfExpress->receivedMobile;
		}
	}

	/*
     * @version 获取用户ID
     */
	public function getCustomerId(){
		if(!empty($this->thirdFees)){
			return $this->thirdFees->customer_id;
		}
	}

	/*
     * @version 获取ThirdFessId
     */
	public function getThirdFessId(){
		if(!empty($this->thirdFees)){
			return $this->thirdFees->id;
		}
	}

	/*
	 * 获取子订单状态*/
	public function getSubOrderState(){
		if(!empty($this->subOrderStatus)){
			return $this->Orderstate_arr[$this->subOrderStatus];
		}
	}
}
