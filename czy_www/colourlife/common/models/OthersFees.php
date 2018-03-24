<?php

/**
 * This is the model class for table "others_fees".
 *
 * The followings are the available columns in table 'others_fees':
 * @property integer $id
 * @property string $sn
 * @property string $model
 * @property integer $object_id
 * @property integer $customer_id
 * @property integer $payment_id
 * @property string $amount
 * @property float $bank_pay
 * @property string $note
 * @property string $create_ip
 * @property integer $create_time
 * @property integer $pay_time
 * @property integer $status
 * @property float $pay_rate
 * @property Customer $customer
 */
class OthersFees extends CActiveRecord
{
	static $fees_status = array(
		Item::FEES_AWAITING_PAYMENT => "待付款",
		Item::FEES_RECHARGEING => "充值中",
		Item::FEES_TRANSACTION_ERROR => '已付款',
		Item::FEES_TRANSACTION_SUCCESS => "交易成功",
		Item::FEES_TRANSACTION_LACK => "交易失败,红包余额不足",
		Item::FEES_TRANSACTION_FAIL => '交易失败',
		Item::FEES_TRANSACTION_REFUND => '退款',
		Item::FEES_CANCEL => "订单已取消",
	);

	/**
	 * @var string 模型名
	 */
	public $modelName = '缴物业费';
	public $objectLabel = '物业费';
	public $objectModel = 'PropertyFees';
	public $objectName;
	public $_customerName;
	public $branch_id;
	public $car_number;
	public $parking_id;
	public $type_id;
	public $community_id;
	public $period;
	public $room;
	public $build_id;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return OthersFees the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'others_fees';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('object_id, customer_id, payment_id, create_time,pay_time, status', 'numerical', 'integerOnly' => true),
			array('sn', 'length', 'max' => 32),
			array('model, create_ip', 'length', 'max' => 20),
			array('amount', 'length', 'max' => 10),
			array('bank_pay,red_packet_pay,note,user_red_packet', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id,branch_id,_customerName,user_red_packet, sn, customer_id, payment_id, amount, note, create_ip, create_time, status', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
			'payment' => array(self::BELONGS_TO, 'Payment', 'payment_id'),
			'logs' => array(self::HAS_MANY, 'OthersFeesLog', 'others_fees_id'),
			$this->objectModel => array(self::BELONGS_TO, $this->objectModel, 'object_id'),
			'ParkingFees' => array(self::BELONGS_TO, 'ParkingFees', 'object_id'),
			'PropertyFees' => array(self::BELONGS_TO, 'PropertyFees', 'object_id'),
			'AdvanceFees' => array(self::BELONGS_TO, 'AdvanceFee', 'object_id'),
			'PowerFees' => array(self::BELONGS_TO, 'PowerFees', 'object_id'),
			'ParkingFeesGemeite' => array(self::BELONGS_TO, 'ParkingFeesGemeite', 'object_id'),
			'ParkingFeesMonth' => array(self::BELONGS_TO, 'ParkingFeesMonth', 'object_id'),
			'ParkingFeesVisitor' => array(self::BELONGS_TO, 'ParkingFeesVisitor', 'object_id'),
			'MealTicketFees' => array(self::BELONGS_TO, 'MealTicketFees', 'object_id'),
			'pay' => array(self::BELONGS_TO, 'pay', 'pay_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => 'Sn号',
			'object_id' => $this->objectLabel,
			'customer_id' => '业主名',
			'payment_id' => '支付方式',
			'amount' => '总金额',
			'note' => '备注',
			'create_ip' => '创建IP',
			'create_time' => '创建时间',
			'pay_time' => '支付时间',
			'objectName' => $this->objectLabel,
			'status' => '状态',
			'community_id' => '小区',
			'_customerName' => '业主名称',
			'mobile' => '手机号码',
			'branch_id' => '管辖部门',
			'red_packet_pay' => '红包抵扣',
			'bank_pay' => '实付',
			'user_red_packet' => '使用红包',
			'pay_rate' => '费率',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('model', $this->objectModel, true); //设置条件

		if ($this->_customerName != '') {
			$criteria->with[] = 'customer';
			$criteria->compare('customer.name', $this->_customerName, true);
		}
		$criteria->compare('id', $this->id);
		$criteria->compare('sn', $this->sn, true);
		$criteria->compare('customer_id', $this->customer_id);
		$criteria->compare('payment_id', $this->payment_id);
		$criteria->compare('amount', $this->amount, true);
		$criteria->compare('note', $this->note, true);
		$criteria->compare('create_ip', $this->create_ip, true);
		$criteria->compare('create_time', $this->create_time);
		$criteria->compare('status', $this->status);

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	protected function beforeSave()
	{
		if ($this->isNewRecord) {
			/**
			 * @var Payment $payment
			 */
			if (!empty($this->payment_id) && $payment = Payment::model()->findByPk($this->payment_id)) {
				$this->pay_rate = $payment->rate;
			}
		}
		return parent::beforeSave();
	}

	public function getPaymentName()
	{
		return empty($this->payment) ? '' : $this->payment->name;
	}

	public function getPaymentNames()
	{
		if (empty($this->payment_id)) {
			return '红包全额支付';
		}
		if (empty($this->payment)) {
			return "无";
		} else {
			if (!empty($this->payment->name)) {
				return $this->payment->name;
			} else {
				return "无";
			}
		}
	}

	public function getModelNames()
	{
		$name = "";
		if ($this->model == "AdvanceFees") {
			$name = "预缴费";
		} else if ($this->model == "PowerFees") {
			$name = "商铺买电";
		} else if ($this->model == "PropertyFees") {
			$name = "物业费";
		} else if ($this->model == "ParkingFees") {
			$name = "停车费";
		} else if ($this->model == "ParkingFeesGemeite") {
			$name = "格美特停车费";
		} else if ($this->model == "VirtualRecharge") {
			$name = "充值";
		}
		return $name;
	}

	public function getCustomerName()
	{
		return empty($this->customer) ? $this->customer_id : (empty($this->customer->name) ? $this->customer->username : $this->customer->name);
	}

	public function getCustomerMobile()
	{
		return empty($this->customer) ? "" : $this->customer->mobile;
	}
//  ICE 接入小区名字
	public function getCommunityName()
	{
//        $model_string = $this->objectModel;
//        if (!empty($this->$model_string->community))
//            return $this->$model_string->community->name;
//        else
//            return '';

//		ICE 接入小区名字
		$model_string = $this->objectModel;
		if (!empty($this->$model_string->community_id)) {
			$communityName = ICECommunity::model()->findByPk($this->$model_string->community_id);
			if (!empty($communityName)) {
				return $communityName['name'];
			} else {
				return '';
			}
		} else {
			return '';
		}
	}

	public function getBranchId()
	{
		$model_string = $this->objectModel;
		return empty($this->$model_string->community) ? 0 : $this->$model_string->community->branch_id;

	}

	public function getCarNumber()
	{
		$modelStr = $this->model;
		$modelList = array(
			'ParkingFees',
			'ParkingFeesGemeite',
			'ParkingFeesMonth',
			'ParkingFeesVisitor'
		);

		if (!in_array($modelStr, $modelList))
			return '';

		$otherModel = $this->$modelStr;
		if (empty($otherModel))
			return '';

		return isset($otherModel->car_number) ? $otherModel->car_number : $otherModel->plate_no;
	}

	public function getMyMobile()
	{
		return empty($this->customer) ? '' : $this->customer->mobile;
	}


	public function getParkingCardNumber()
	{
		return empty($this->ParkingFees) ? '' : $this->ParkingFees->parking_card_number;
	}

	static public function getStatusNames()
	{
		return CMap::mergeArray(array('' => '全部'), self::$fees_status);
	}

	public function getStatusName($html = false)
	{
		$return = '';
		$return .= ($html) ? '<span class="label label-success">' : '';
		$return .= self::$fees_status[$this->status];
		$return .= ($html) ? '</span>' : '';
		return $return;
	}


	public function getParkStatus()
	{
		$parkStatus = array(
			Item::FEES_AWAITING_PAYMENT => "待付款",
			// Item::FEES_RECHARGEING => "充值中",
			Item::FEES_TRANSACTION_FAIL => '交易失败',
			Item::FEES_TRANSACTION_LACK => '交易失败,余额不足',
			Item::FEES_TRANSACTION_ERROR => '已付款,未续卡',
			Item::FEES_TRANSACTION_SUCCESS => "已续卡",
			Item::FEES_CANCEL => "订单已取消",
			Item::FEES_PART_REFUND => "部分退款（手动）",
		);

		return CMap::mergeArray(array('' => '全部'), $parkStatus);
	}


	public function getFeesSn()
	{
		return '单据号:' . $this->sn;
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => 'update_time',
				'setUpdateOnCreate' => true,
			),
			'IpBehavior' => array(
				'class' => 'common.components.behaviors.IpBehavior',
				'createAttribute' => 'create_ip',
				'updateAttribute' => null,
				'setUpdateOnCreate' => true,
			),
		);
	}

	public function getCustomerAdress()
	{
		return isset($this->customer) ? (isset($this->customer->community) ? $this->customer->community->name . (
			isset($this->customer->build) ? $this->customer->build->name : '') . $this->customer->room : '') : '';
	}

	//获得物业缴费单的缴费地址
	public function getOtherFeeAddress()
	{
		if (!empty($this->PropertyFees)) {
			$property = $this->PropertyFees;
			$communityName = empty($property->community) ? "" : $property->community->name;
			return $communityName . $property->build . $property->room;
		} else {
			return "";
		}
	}

	public function getParkingTypeName()
	{
		return empty($this->ParkingFees) ? '' : $this->ParkingFees->getTypeName();
	}

	///获得停车缴费单的缴费地址
	public function getParkingAddress()
	{
		if (!empty($this->ParkingFees)) {
			$parking = $this->ParkingFees;
			$communityName = empty($parking->community) ? "" : $parking->community->name;
			return $communityName . (empty($parking->build) ? '' : $parking->build->name) . $parking->room;
		} else {
			return "";
		}
	}

	public function getAdvanceAddress()
	{
		if (!empty($this->AdvanceFees)) {
			$advance = $this->AdvanceFees;
			$communityName = empty($advance->community) ? "" : $advance->community->name;

			return $communityName . (empty($advance->build) ? '' : $advance->build) . $advance->room;
		} else {
			return "";
		}
	}

	public function getAddress()
	{
		$modelStr = $this->model;
		$otherModel = $this->$modelStr;
		if (!empty($otherModel)) {


			$modelList = array(
				'AdvanceFees',
				'PropertyFees',
				'ParkingFees',
				'ParkingFeesGemeite',
				'ParkingFeesMonth',
			);

			if (in_array($modelStr, $modelList)) {
				$communityName = empty($otherModel->community) ? "" : $otherModel->community->name;
				return $communityName . (isset($otherModel->build) ? '' : $otherModel->build->name) . $otherModel->room;
			}

			if ('ParkingFeesVisitor' == $modelStr) {
				return $otherModel->park_name;
			}
		}
		return '';
	}

	public function getCustomerHtml()
	{
		if (empty($this->customer)) return "";
		return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '业主:' . $this->customer->name . ',联系电话:' . $this->customer->mobile), $this->customer->name);
	}

	/**
	 * @return array
	 * 检测红包使用
	 */
	public function checkOrderFees()
	{
		if (empty($this->customer)) {
			return array('result' => false, 'error' => "获取用户余额失败");
		}
		$balance = $this->customer->getBalance();//用户红包余额
		$amount = $this->amount;//订单总金额
		$redPackedPay = $this->red_packet_pay;//用户红包支付金额
		//如果红包支付金额大于余额或红包支付金额大于订单总额
		if ($redPackedPay > $balance) {
			return array('result' => false, 'error' => "红包余额不足");
		}
		if ($redPackedPay > $amount) {
			return array('result' => false, 'error' => "红包金额不能超过订单总额");
		}
		return array('result' => true, 'error' => "");
	}

	public function getPaymentList()
	{
		$model = Payment::model()->online()->findAll();
		if (isset($model)) {
			$payment_list = array();
			foreach ($model as $list) {
				$payment_list[''] = "全部";
				$payment_list[$list->id] = $list->name;
			}
			return $payment_list;
		} else {
			return "";
		}
	}

	/*public function getStatusList(){
		if($this->status==Item::FEES_AWAITING_PAYMENT){
			$arr = array(Item::FEES_TRANSACTION_ERROR => '已付款');
		}elseif($this->status==Item::FEES_TRANSACTION_ERROR){
			$arr = array(Item::FEES_TRANSACTION_REFUND => '退款');
		}elseif($this->status==Item::FEES_TRANSACTION_FAIL){
			$arr = array(Item::FEES_TRANSACTION_REFUND => '退款',Item::FEES_TRANSACTION_SUCCESS=>'交易成功');
		}elseif($this->status==Item::FEES_TRANSACTION_REFUND){
			$arr = array(Item::FEES_AWAITING_PAYMENT => '待付款');
		}else{
			$arr = array(''=>'');
		}
		return $arr;
	}*/

	//查询订单商品的积分总数,为了兼容内部采购订单和业主订单
	public function getOrderAllIntegral()
	{
		$discount = 0;
		return intval($discount);
	}
}
