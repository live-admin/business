<?php

/**
 * This is the model class for table "vending_machine".
 *
 * The followings are the available columns in table 'vending_machine':
 * @property integer $id
 * @property integer $vendor
 * @property string $order_num
 * @property string $ext_num
 * @property string $detail
 * @property string $user_phone
 * @property string $order_amount
 * @property integer $order_status
 * @property string $refund_money
 * @property integer $create_time
 * @property string $update_time
 */
class VendingMachine extends CActiveRecord
{
	public $modelName = '自动售货机订单';
	public $sn;
	public $status;
	public $Orderstate_arr = array(
		"Y" => "已支付",
		"N" => "未支付",
		"C" => "取消",
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
		return 'vending_machine';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('vendor, order_num, ext_num, detail, user_phone, order_amount, order_status, create_time', 'required'),
			array('order_status, create_time', 'numerical', 'integerOnly'=>true),
			array('vendor, detail', 'length', 'max'=>50),
			array('order_num, ext_num', 'length', 'max'=>36),
			array('user_phone', 'length', 'max'=>12),
			array('order_amount, refund_money', 'length', 'max'=>10),
			array('update_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('sn ,status,id, vendor, order_num, ext_num, detail, user_phone, order_amount, order_status, refund_money, create_time, update_time', 'safe', 'on'=>'search'),
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
			'thirdFees' => array(self::HAS_ONE, 'ThirdFees', array('cSn'=>'order_num')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'vendor' => '供应商',
			'order_num' => '第三方订单号',
			'ext_num' => '供应商订单号',
			'detail' => '名称',
			'user_phone' => '手机号码',
			'order_amount' => '订单金额',
			'order_status' => '订单状态',
			'refund_money' => '退款金额',
			'create_time' => 'Create Time',
			'update_time' => 'Update Time',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('vendor',$this->vendor);
		$criteria->compare('order_num',$this->order_num,true);
		$criteria->compare('ext_num',$this->ext_num,true);
		$criteria->compare('detail',$this->detail,true);
		$criteria->compare('user_phone',$this->user_phone,true);
		$criteria->compare('order_amount',$this->order_amount,true);
		$criteria->compare('order_status',$this->order_status);
		$criteria->compare('refund_money',$this->refund_money,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VendingMachine the static model class
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
     * @version 获取订单的状态
     */
	public function getOrderState(){
		return $this->Orderstate_arr[$this->order_status];
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
