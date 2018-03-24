<?php

/**
 * This is the model class for table "thinks_giving".
 *
 * The followings are the available columns in table 'thinks_giving':
 * @property integer $id
 * @property integer $customer_id
 * @property string $sn
 * @property integer $prize_id
 * @property integer $type
 * @property integer $is_receive
 * @property integer $create_time
 * @property integer $update_time
 */
class ThinksGiving extends CActiveRecord
{
	//一元购商品
	private $oneyuanGoods = '367971,3358168';

	//奖品名称
	private $prizeName=array(
		'0' => array('id'=>1,'prize_name'=>'彩生活特供满100减10元优惠券','type'=>2),
		'1' => array('id'=>2,'prize_name'=>'1彩饭票','type'=>1),
		'2' => array('id'=>3,'prize_name'=>'2彩饭票','type'=>1),
		'3' => array('id'=>4,'prize_name'=>'彩之云定制抱枕','type'=>4),
		'4' => array('id'=>5,'prize_name'=>'乐扣乐扣不锈钢纤巧保温杯','type'=>3),
		'5' => array('id'=>6,'prize_name'=>'全程通W3智能心率手环','type'=>4),
		'6' => array('id'=>7,'prize_name'=>'Apple watch','type'=>4),
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'thinks_giving';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, sn, type', 'required'),
			array('customer_id, prize_id, type, is_receive, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('sn', 'length', 'max'=>32),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, sn, prize_id, type, is_receive, create_time, update_time', 'safe', 'on'=>'search'),
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
			'customer_id' => 'Customer',
			'sn' => 'Sn',
			'prize_id' => 'Prize',
			'type' => 'Type',
			'is_receive' => 'Is Receive',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('is_receive',$this->is_receive);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ThinksGiving the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
     * @version 是否完善资料
     * @param int $customer_id
     * return boolean
     */
	public function completeInfo($mobile){
		if(empty($mobile)){
			return false;
		}
		$Customer = Customer::model()->findByAttributes(array('mobile'=>$mobile,'state'=>0,'is_deleted'=>0));$Customer['gender']='1';
		if(!empty($Customer) && !empty($Customer['nickname'])&& !empty($Customer['name'])&& !empty($Customer->gender)&& !empty($Customer['community_id'])&& !empty($Customer['portrait'])) {
			return true;
		}else{
			return false;
		}
	}

	/*
     * @version 插入邀请注册用户
     * @param int $customer_id
	 * @param int $mobile
     * return boolean
     */
	public function insertInvite($customer_id,$mobile){
		if(empty($customer_id) || empty($mobile)){
			return false;
		}
		$inviteModel = Invite::model()->findByAttributes(array('customer_id'=>$customer_id, 'mobile'=>$mobile));
		if(empty($inviteModel)){
			$inviteModel = new Invite();
			$inviteModel->customer_id = $customer_id;
			$inviteModel->mobile = $mobile;
			$inviteModel->model = 'customertg';
		}
		$inviteModel->create_time = time();
		$inviteModel->valid_time = (time()+75200);
		$inviteModel->status = 1;
		$inviteModel->effective = 1;
		if($inviteModel->save()){
			return true;
		}else{
			return false;
		}
	}

/**
 * @version 打开福袋获取奖品
 * @param int $customer_id
 * @param int $type 来源类型1支付，2邀请，3抢福袋
 * return string
 */
	public function getPrize($type, $customer_id){
		if(empty($type) || empty($customer_id)){ return false; }
		$time =strtotime(date('Y-m-d',time())); //当天开始时间
		$endTime = $time+86400;    //当天结束时间

		if($type == 2){
			$sumSql = "SELECT COUNT(1) AS num FROM thinks_giving WHERE is_receive=1 AND type !=3 AND update_time<{$endTime} AND update_time>={$time}";
			$result = Yii::app()->db->createCommand($sumSql)->queryAll();
			$prize_sum = $result[0]['num'];
			if($prize_sum>=990){
				$arr = array(
					'state'=>2,
					'msg'=>'幸福安康！元气满满！～',
				);
				return $arr;
			}
			$type_limit = 7;
		}
		if($type == 1){
			$sumSql = "SELECT COUNT(1) AS num FROM thinks_giving WHERE is_receive=1 AND type !=3 AND update_time<{$endTime} AND update_time>={$time}";
			$result = Yii::app()->db->createCommand($sumSql)->queryAll();
			$prize_sum = $result[0]['num'];
			if($prize_sum>=990){
				$arr = array(
					'state'=>2,
					'msg'=>'幸福安康！元气满满！～',
				);
				return $arr;
			}
			$type_limit = 8;
		}
		if($type == 3){$type_limit = 5;}

		$randSql = "SELECT id ,number,last_number FROM thinks_giving_prize WHERE id <{$type_limit} AND last_number>0";
		$randArr = Yii::app()->db->createCommand($randSql)->queryAll();
		if(empty($randArr)){
			$model = ThinksGiving::model()->findByAttributes(array('customer_id'=>$customer_id, 'type'=>$type , 'is_receive'=>0));
			if($model){
				$model->is_receive = 1;
				$model->prize_id = 8;
				$model->save();
			}
			$arr = array(
				'state'=>2,
				'msg'=>'幸福安康！元气满满！～',
			);
			return $arr;
		}
		$rand = array();
		foreach($randArr as $key=>$value){
			$rand[$value['id']] = $value['number'];
		}
		$rid = $this->get_rand($rand); //根据概率获取奖项id
		$name = $this->prizeName[$rid-1]['prize_name'];
		$prize_type = $this->prizeName[$rid-1]['type'];
		$result = $this->sendPrize($type, $prize_type, $customer_id, $rid);
		if($result){
			$arr = array(
				'state'=>1,
				'msg'=>$name,
				'prize_id'=>$rid
			);
			return $arr;
		}
		else{
			$arr = array(
				'state'=>2,
				'msg'=>'网络繁忙，请重试！',
			);
			return $arr;
		}
	}

	//给用户发奖品（饭票，优惠券）来源类型，奖品类型，用户id，奖品id
	private function sendPrize($type,$prize_type, $customer_id, $rid){
		//饭票
		if($prize_type == 1){
			$cmobile=20000000005;
			$cmobile_id = 2224375;
			$amount = $rid-1;
			$note = '感恩节福袋活动';
			$balance = Customer::model()->findByPk(2224375);
			if($balance['balance']<10){}

			$transaction = Yii::app()->db->beginTransaction();
			try{

				//更新中奖记录表
				$thinksGiving = ThinksGiving::model()->findByAttributes(array('customer_id'=>$customer_id, 'is_receive'=>0,'type'=>$type));
				if(empty($thinksGiving)){//整点抢福袋
					$thinksGiving = new ThinksGiving();
					$thinksGiving->customer_id = $customer_id;
					$thinksGiving->type = $type;
					$thinksGiving->create_time = time();
					$thinksGiving->sn = time().'ThinksGivings';
				}
				$thinksGiving->is_receive = 1;
				$thinksGiving->prize_id = $rid;
				$thinksGiving->update_time = time();

				//更新奖品表
				$prize = ThinksGivingPrize::model()->findByPk($rid);
				$prize->last_number = $prize['last_number']-1;

				if($thinksGiving->save() && $prize->save()){
					$transaction->commit();
					//发饭票
					$rebateResult=RedPacketCarry::model()->customerTransferAccounts($cmobile_id,$customer_id,$amount,1,$cmobile,$note);
					if(1 ==$rebateResult['status']){
						return true;
					}else{
						Yii::log('2016年11月感恩节福袋活动'.date('Y-m-d',time()).'用户id：'.$customer_id.'发票发放失败！错误信息为：'.$rebateResult['msg'],CLogger::LEVEL_ERROR,'colourlife.core.ThinksGiving');
						return false;
					}
				}else{
					$transaction->rollback();
					return false;
				}
			}catch (Exception $e){
				$transaction->rollback();
				return false;
			}
		}
		//优惠券
		if($prize_type == 2){
			$code = 100000152;
			$mobile = Customer::model()->findByPk($customer_id);
			if(empty($mobile)){ return false; }
			$mobile = $mobile['mobile'];
			$transaction = Yii::app()->db->beginTransaction();
			try{
				//发优惠券
				$model = new UserCoupons();
				$model->mobile = $mobile;
				$model->you_hui_quan_id = $code;
				$model->is_use = 0;
				$model->create_time = time();
				$model->num = 0;
				//更新中奖记录表
				$thinksGiving = ThinksGiving::model()->findByAttributes(array('customer_id'=>$customer_id, 'is_receive'=>0,'type'=>$type));
				if(empty($thinksGiving)){//整点抢福袋
					$thinksGiving = new ThinksGiving();
					$thinksGiving->customer_id = $customer_id;
					$thinksGiving->type = $type;
					$thinksGiving->create_time = time();
					$thinksGiving->sn = time().'ThinksGiving';
				}
				$thinksGiving->is_receive = 1;
				$thinksGiving->prize_id = $rid;
				$thinksGiving->update_time = time();
				//更新奖品表数量
				$prize = ThinksGivingPrize::model()->findByPk($rid);
				$prize->last_number = $prize['last_number']-1;

				if($model->save() && $thinksGiving->save() && $prize->save()){
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
		//一元码
		if($prize_type == 3){
			$transaction = Yii::app()->db->beginTransaction();
			try{
				//发一元码
				$onecode = OneYuanBuy::sendCode($customer_id, $this->oneyuanGoods, 0,  'ThinksGiving');

				//更新中奖记录表
				$thinksGiving = ThinksGiving::model()->findByAttributes(array('customer_id'=>$customer_id, 'is_receive'=>0,'type'=>$type));
				if(empty($thinksGiving)){ return false; }
				$thinksGiving->is_receive = 1;
				$thinksGiving->prize_id = $rid;
				$thinksGiving->update_time = time();

				//更新奖品数量表
				$prize = ThinksGivingPrize::model()->findByPk($rid);
				$prize->last_number = $prize['last_number']-1;

				if($onecode && $thinksGiving->update() && $prize->update()){
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
		//实物
		if($prize_type == 4){
			$transaction = Yii::app()->db->beginTransaction();
			try{
				//更新中奖记录表
				$thinksGiving = ThinksGiving::model()->findByAttributes(array('customer_id'=>$customer_id, 'is_receive'=>0,'type'=>$type));
				if(empty($thinksGiving)){ return false; }
				$thinksGiving->is_receive = 1;
				$thinksGiving->prize_id = $rid;
				$thinksGiving->update_time = time();
				//更新奖品表数量
				$prize = ThinksGivingPrize::model()->findByPk($rid);
				$prize->last_number = $prize['last_number']-1;

				if($thinksGiving->save() && $prize->save()){
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
}
