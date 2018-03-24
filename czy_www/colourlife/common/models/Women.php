<?php

/**
 * This is the model class for table "women".
 *
 * The followings are the available columns in table 'women':
 * @property integer $id
 * @property string $openid
 * @property integer $mobile
 * @property integer $customer_id
 * @property integer $is_send
 * @property integer $prize_id
 * @property integer $create_time
 * @property integer $send_time
 */
class Women extends CActiveRecord
{
	//奖品名称
	private $prizeName=array(
		'0' => array('id'=>1,'prize_name'=>'3.8元无门槛优惠券','number'=>5000),
		'1' => array('id'=>2,'prize_name'=>'舒肤佳洗手液','number'=>1000),
		'2' => array('id'=>3,'prize_name'=>'金龙鱼玉米油900ml','number'=>500),
		'3' => array('id'=>4,'prize_name'=>'格兰仕（Galanz）微波炉','number'=>0),
		'4' => array('id'=>5,'prize_name'=>'Iphone7（32GB）','number'=>0),
		'5' => array('id'=>6,'prize_name'=>'谢谢参与','number'=>3500),
	);


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'women';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('openid, create_time', 'required'),
			array('mobile, customer_id, is_send, prize_id, create_time, send_time', 'numerical', 'integerOnly'=>true),
			array('openid', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, openid, mobile, customer_id, is_send, prize_id, create_time, send_time', 'safe', 'on'=>'search'),
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
			'openid' => 'Openid',
			'mobile' => 'Mobile',
			'customer_id' => 'Customer',
			'is_send' => 'Is Send',
			'prize_id' => 'Prize',
			'create_time' => 'Create Time',
			'send_time' => 'Send Time',
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
		$criteria->compare('openid',$this->openid,true);
		$criteria->compare('mobile',$this->mobile);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('is_send',$this->is_send);
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('send_time',$this->send_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Women the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//获取抽奖几率
	function get_rand($proArr) {
		$result = '';
		//概率数组的总概率精度
		$proSum = array_sum($proArr);
		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset ($proArr);

		return $result;
	}

	//抽奖
	public function luckDraw($customer_id , $openid){
		$is_receiver = Women::model()->findByAttributes(array('openid'=>$openid , 'is_send'=>2));
		if($is_receiver){
			$result = array(
				'state'=>0,
				'msg'=>'谢谢参与',
				'prize_id'=>6
			);
			$this->setNoPrize($customer_id);
			return $result;
		}
		$rand = array();
		foreach($this->prizeName as $key=>$value){
			$rand[$value['id']] = $value['number'];
		}
		$rid = $this->get_rand($rand); //根据概率获取奖项id
		$prize_name = $this->prizeName[$rid-1]['prize_name'];//奖品名称
		$send_prize = $this->sendPrize($customer_id , $openid , $rid);
		if($send_prize){
			$result = array(
				'state'=>1,
				'msg'=>$prize_name,
				'prize_id'=>$rid
			);
		}else{
			$result = array(
				'state'=>0,
				'msg'=>'谢谢参与！',
				'prize_id'=>6
			);
			$this->setNoPrize($customer_id);
		}
		return $result;
	}

	//发奖
	private function sendPrize($customer_id , $openid , $prize_id){

		if(!($prize_id == 1 || $prize_id == 2 || $prize_id == 3 || $prize_id == 6)){ return false ;}

		//是否有机会
		$women_chance = Women::model()->findByAttributes(array('openid'=>$openid , 'is_send'=>1 ,'customer_id'=>$customer_id));
		if(empty($women_chance)){ return false ;}

		//3.8元无门槛优惠券
		if($prize_id == 1){
			$prize_count_one = Women::model()->countByAttributes(array('prize_id'=>1));
			//每天1000是否领完,累加机制
			$prize_count_limit = $this->getDay()*1000;//正式
			if($prize_count_one>=$prize_count_limit){
				$this->setNoPrize($customer_id);
				return false ;
			}
			//统计优惠券数量,如果超过5000则返回false
			if($prize_count_one >= 5000){
				$this->setNoPrize($customer_id);
				return false; }//正式是5000

			$code = 100000449;
			$mobile = Customer::model()->findByPk($customer_id);
			if(empty($mobile)){ return false; }
			$mobile = $mobile['mobile'];
			$transaction = Yii::app()->db->beginTransaction();
			try {
				//发优惠券
				$model = new UserCoupons();
				$model->mobile = $mobile;
				$model->you_hui_quan_id = $code;
				$model->is_use = 0;
				$model->create_time = time();
				$model->num = 0;
				//更新中奖记录表
				$women_chance->is_send = 2;
				$women_chance->send_time = time();
				$women_chance->prize_id = $prize_id;

				if($model->save() && $women_chance->update()){
					$transaction->commit();
					return true;
				}else{
					$transaction->rollback();
					return false;
				}

			}catch (Exception $e){
				$transaction->rollback();
				return false;
			}
		}
		//舒肤佳洗手液
		if($prize_id == 2){
			//统计一元购码发放数量,如果超过1000则返回false
			$prize_count_one = Women::model()->countByAttributes(array('prize_id'=>2));

			$prize_count_limit = $this->getDay()*200;//正式
			if($prize_count_one>=$prize_count_limit){
				$this->setNoPrize($customer_id);
				return false ;
			}

			if($prize_count_one >= 1000){
				$this->setNoPrize($customer_id);
				return false; }//正式是1000

			$one_yuan_code = '13438';
			$transaction = Yii::app()->db->beginTransaction();
			try{
				//发一元码
				$one_code = OneYuanBuy::sendCode($customer_id, $one_yuan_code, 0,  'women');

				//更新中奖记录表
				$women_chance->is_send = 2;
				$women_chance->send_time = time();
				$women_chance->prize_id = $prize_id;

				if($one_code && $women_chance->update()){
					$transaction->commit();
					return true;
				}else{
					$transaction->rollback();
					return false;
				}

			}catch(Exception $e){
				$transaction->rollback();
				return false;
			}
		}
		//金龙鱼玉米油900ml
		if($prize_id == 3){
			//统计一元购码发放数量,如果超过500则返回false
			$prize_count_one = Women::model()->countByAttributes(array('prize_id'=>3));

			$prize_count_limit = $this->getDay()*100;//正式
			if($prize_count_one>=$prize_count_limit){
				$this->setNoPrize($customer_id);
				return false ;
			}

			if($prize_count_one >= 500){
				$this->setNoPrize($customer_id);
				return false; }//正式是500

			$one_yuan_code = '14227';
			$transaction = Yii::app()->db->beginTransaction();
			try{
				//发一元码
				$one_code = OneYuanBuy::sendCode($customer_id, $one_yuan_code, 0,  'women');

				//更新中奖记录表
				$women_chance->is_send = 2;
				$women_chance->send_time = time();
				$women_chance->prize_id = $prize_id;

				if($one_code && $women_chance->update()){
					$transaction->commit();
					return true;
				}else{
					$transaction->rollback();
					return false;
				}

			}catch(Exception $e){
				$transaction->rollback();
				return false;
			}
		}
		//谢谢参与
		if($prize_id == 6){
			$women_chance->is_send = 2;
			$women_chance->send_time = time();
			$women_chance->prize_id = $prize_id;
			$women_chance->update();
			return true;
		}

	}

	//判断当前是第几天
	private function getDay(){
		$began_time = '1488816000';//正式活动开始时间7号1488816000
		$now_time = time();
		$diff = $now_time-$began_time;
		$day = ceil($diff/(24*60*60));
		return $day;
	}

	//获取中奖记录
	public function getRecord(){
		$sql = "SELECT prize_id,send_time FROM women WHERE is_send=2";
		$record = Yii::app()->db->createCommand($sql)->queryAll();
		foreach($record as $k=>&$v){
			$v['prize_name'] = $this->prizeName[$v['prize_id']-1]['prize_name'];;
		}
		return $record;
	}

	public function setNoPrize($customer_id){
		$women_chance = Women::model()->findByAttributes(array('is_send'=>1 ,'customer_id'=>$customer_id));
		if(!$women_chance){
			return false;
		}else{
			$women_chance->is_send = 2;
			$women_chance->send_time = time();
			$women_chance->prize_id = 6;
			$women_chance->update();
			return true;
		}

	}
}
