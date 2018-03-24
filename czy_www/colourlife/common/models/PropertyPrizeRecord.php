<?php

/**
 * This is the model class for table "property_prize_record".
 *
 * The followings are the available columns in table 'property_prize_record':
 * @property string $id
 * @property integer $customer_id
 * @property integer $time
 * @property integer $prize_id
 * @property integer $status
 * @property integer $level_id
 */
class PropertyPrizeRecord extends CActiveRecord
{
	public $category_id;
	public $status1;
	public $mobile;
	public $sn;
	public $level_arr = array(
		"1" => "一等奖",
		"2" => "二等奖",
		"3" => "三等奖",
		"4" => "四等奖",
		"5" => "五等奖",
		"6" => "六等奖",
	);

	public $status_arr = array(
		'0' => '未领取',
		'1' => '已领取',
		'2' => '已发货',
	);

	public  $category_arr = array(
		'1'=>'京东商品',
		'2'=>'饭票',
		'3'=>'彩食惠商品',
		'4'=>'E维修优惠券',
		'5'=>'彩特供商品',
		'6'=>'彩食惠优惠券',
	);

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'property_prize_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, time, level_id', 'required'),
			array('customer_id, time, prize_id, status, level_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, time, prize_id, status, level_id, category_id,status1,mobile,sn', 'safe', 'on'=>'search'),
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
			'propertyPrize' => array(self::HAS_ONE, 'PropertyPrize', array('id'=>'prize_id')),
			'PropertyPrizeAddress' => array(self::HAS_ONE, 'PropertyPrizeAddress', array('record_id'=>'id')),
			'Customer' => array(self::HAS_ONE, 'Customer', array('id'=>'customer_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => '用户',
			'time' => '获奖时间',
			'prize_id' => '奖品',
			'status' => '发货状态',
			'level_id' => '等级',
			'category_id' => '类别',
			'status1' => '发货状态',
			'mobile'=>'用户手机号',
			'sn'=>'彩之云订单号',
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


		if(isset($this->prize_id)){
			$criteria->with[]='propertyPrize';
			$criteria->compare('propertyPrize.id', $this->prize_id, true);
		}
		if(isset($this->category_id)){
			$criteria->with[]='propertyPrize';
			$criteria->compare('propertyPrize.category_id', $this->category_id, true);
		}
		if(isset($this->status1)){
			$criteria->with[]='PropertyPrizeAddress';
			$criteria->compare('PropertyPrizeAddress.status', $this->status1, true);
		}
		if(isset($this->mobile)){
			$criteria->with[]='Customer';
			$criteria->compare('Customer.mobile', $this->mobile, true);
		}
		if(isset($this->sn)){
			$criteria->with[]='PropertyPrizeAddress';
			$criteria->compare('PropertyPrizeAddress.sn', $this->sn, true);
		}
		$criteria->compare('t.id',$this->id,true);
		$criteria->compare('t.customer_id',$this->customer_id);
		$criteria->compare('t.time',$this->time);
		$criteria->compare('t.prize_id',$this->prize_id);
		$criteria->compare('t.status',$this->status);
		$criteria->compare('t.level_id',$this->level_id);
		$criteria->order = 't.id DESC' ;//排序条件

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PropertyPrizeRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//获取用户手机号码
	public function getMobile(){
		if(!empty($this->customer_id)){
			$customerModel = Customer::model()->findByPk($this->customer_id);
			return $customerModel['mobile'];
		}
	}

	//获取奖品等级名称
	public function getLevel(){
		if(!empty($this->level_id)){
			return $this->level_arr[$this->level_id];
		}
	}

	//获取状态名称
	public function getStatus(){
		if(!empty($this->status)){
			return $this->status_arr[$this->status];
		}
	}

	//获取奖品名称
	public function getPrizeName(){
		if(!empty($this->propertyPrize)){
			return $this->propertyPrize->name;
		}
	}

	//获取奖品类别
	public function getPrizeCategory(){
		if(!empty($this->propertyPrize)){
			return $this->category_arr[$this->propertyPrize->category_id];
		}
	}
	

	//获取订单状态
	public function getOrderStatus(){
		if(!empty($this->id)){
			$orderStatus = 0;
			$propertyPrizeAddress = PropertyPrizeAddress::model()->find('record_id=:record_id', array(':record_id'=>$this->id));
			if(!empty($propertyPrizeAddress))
				$orderStatus = $propertyPrizeAddress['status'];
			if($orderStatus == 1){
				return '已发货';
			}else{
				return '';
			}

		}
	}

	//获取彩之云订单号
	public function getSn(){
		if(!empty($this->id)){
			$sn = '';
			$propertyPrizeAddress = PropertyPrizeAddress::model()->find('record_id=:record_id', array(':record_id'=>$this->id));
			if(!empty($propertyPrizeAddress))
				$sn = $propertyPrizeAddress['sn'];
			return $sn;
		}
	}
	//获取京东订单号
	public function getJdSn(){
		if(!empty($this->id)){
			$jd_sn = '';
			$propertyPrizeAddress = PropertyPrizeAddress::model()->find('record_id=:record_id', array(':record_id'=>$this->id));
			if(!empty($propertyPrizeAddress))
				$jd_sn = $propertyPrizeAddress['jd_sn'];
			return $jd_sn;
		}
	}
	//获取收件人姓名
	public function getUsename(){
		if(!empty($this->id)){
			$username = '';
			$propertyPrizeAddress = PropertyPrizeAddress::model()->find('record_id=:record_id', array(':record_id'=>$this->id));
			if(!empty($propertyPrizeAddress))
				$username = $propertyPrizeAddress['username'];
			return $username;
		}
	}
	//获取收件人地址
	public function getAddress(){
		if(!empty($this->id)){
			$address = '';
			$propertyPrizeAddress = PropertyPrizeAddress::model()->find('record_id=:record_id', array(':record_id'=>$this->id));
			if(!empty($propertyPrizeAddress))
				$address = $propertyPrizeAddress['address'];
			return $address;
		}
	}
	//获取收件人电话
	public function getTel(){
		if(!empty($this->id)){
			$mobile = '';
			$propertyPrizeAddress = PropertyPrizeAddress::model()->find('record_id=:record_id', array(':record_id'=>$this->id));
			if(!empty($propertyPrizeAddress))
				$mobile = $propertyPrizeAddress['mobile'];
			return $mobile;
		}
	}


	//确认订单从运营账户扣钱
	public function confirm($sn){
		if(empty($sn))
			return false;
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$order = Order::model()->find('sn=:sn',array(':sn'=>$sn));
			if(empty($order))
				return false;

			$order->status = 1;
			$cmobile_id = 2507585;
			$redPacked = new RedPacket();
			$items = array(
				'customer_id' => $cmobile_id,                                  //用户的ID
				'to_type' => Item::RED_PACKET_TO_TYPE_GOODS_PAYMENT,
				'sum' =>$order['amount'],                                              //红包金额,
				'sn' => $sn,
			);
			if($redPacked->consumeRedPacker($items) && $order->save()){
				$transaction->commit();
				return true;
			}else{
				$transaction->rollback();
				return false;
			}

		} catch (Exception $e) {
			$transaction->rollback();
			return false;
		}

	}
}
