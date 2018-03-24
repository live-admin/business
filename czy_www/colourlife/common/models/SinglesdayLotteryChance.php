<?php

/**
 * This is the model class for table "singlesday_lottery_chance".
 *
 * The followings are the available columns in table 'singlesday_lottery_chance':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $chance
 * @property integer $way
 * @property integer $type
 * @property integer $create_time
 */
class SinglesdayLotteryChance extends CActiveRecord
{
	private $prizeArr = array(
		'0' => array('id' => 1,'money' => 3,'prizeName' => '3彩饭票','v' =>10,'signName' => '姻缘签','signID' => 1),
		'1' => array('id' => 2,'prizeName' => '1元换购码','code' => 0,'v' =>1,'signName' => '高升签','signID' => 2),
		'2' => array('id' => 3,'prizeName' => '满59减12元优惠券','yhqid' => 100000112,'v' => 967,'signName' => '财运签','signID' => 3),
		'3' => array('id' => 4,'prizeName' => '满99减20元优惠券','yhqid' => 100000113,'v' => 20,'signName' => '财运签','signID' => 3),
		'4' => array('id' => 5,'prizeName' => '满599减88元优惠券','yhqid' => 100000114,'v' => 2,'signName' => '财运签','signID' => 3)
	);
	private $totalArr = array(
			'1' => 0,
			'2' => 0,
			'3' => 1600,
			'4' => 400,
			'5' => 80
	);
	private $prizeIDArr = array(
		'4' => array(100000135,100000145,100000124),
		'5' => array(100000134,100000144,100000123),
		'6' => array(100000133,100000143,100000122),
		'7' => array(100000132,100000142,100000121),
		'8' => array(100000131,100000141,100000120),
		'9' => array(100000130,100000140,100000119),
		'10' => array(100000129,100000139,100000118),
		'11' => array(100000128,100000138,100000117),
		'12' => array(100000127,100000137,100000116),
		'13' => array(100000126,100000136,100000115),
		'14' => array(100000125,100000146,100000149),
		'15' => array(100000148,100000147,100000150)
	);
	//一元购商品
	private $oneyuanGoods = '37966,37978,37968,37969,31860,37970,37971,37967,37972,37973,14268,37974,37975,37976,37977';
	//扣款账号
	private $cmobile=20000000005;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'singlesday_lottery_chance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, chance, way, type, create_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, chance, way, type, create_time', 'safe', 'on'=>'search'),
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
			'id' => '表ID',
			'customer_id' => '用户ID',
			'chance' => '机会',
			'way' => '途径（1签到，2邀请注册，3换签）',
			'type' => '机会类型（1获得，2使用）',
			'create_time' => '添加时间',
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
		$criteria->compare('chance',$this->chance);
		$criteria->compare('way',$this->way);
		$criteria->compare('type',$this->type);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SinglesdayLotteryChance the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取每天的基本信息
	 * @param unknown $customerID
	 * @return multitype:|number
	 */
	public function getPerDayInfo($customerID){
		if (empty($customerID) || $customerID <= 0){
			return array();
		}
		$dateTime = $this->getPerTime();
		$startTime = $dateTime['startTime'];
		$endTime = $dateTime['endTime'];
		$data['day'] = date("j");
		$hours = date("G");
		$data['chance'] = 0;
		$data['isTimesEnough'] = 0;
		//判断是否已签到
		$data['isSign'] = $this->isSign($customerID, $startTime, $endTime);
		
		//判断是否有抽奖机会
		$data['chance'] = $this->getChance($customerID, $startTime, $endTime);
		
		//判断用户每天是否抽了5次
		$data['isTimesEnough'] = $this->isTimesEnough($customerID, $startTime, $endTime);
		//判断今日抽奖是否已结束，即奖品数量是否已达到每天的量 0点到8点不更新奖品
		if ($hours >=0 && $hours <=8){
			$data['isOver'] = 1;
		}else {
			$checkArr = $this->isPrizeEnough($startTime, $endTime);
			$data['isOver'] = $checkArr['isOver'];
		}
		return $data;
	}
	 
	/**
	 * 每天抽奖次数
	 * @param unknown $customerID
	 * @param unknown $startTime
	 * @param unknown $endTime
	 * @return unknown
	 */
	private function isTimesEnough($customerID,$startTime,$endTime){
		//判断用户每天是否抽了5次
		$drawTimes = SinglesdayLotteryChance::model()->count("customer_id=:customer_id and type = 2 and way!=3 and create_time >=:startTime and create_time <=:endTime",array(
				':customer_id' => $customerID,
				':startTime' => $startTime,
				':endTime' => $endTime
		));
		if ($drawTimes >= 5){
			return 1;
		}
		return 0;
	}
	
	/**
	 * 判断是否已签到
	 * @param unknown $customerID
	 * @param unknown $startTime
	 * @param unknown $endTime
	 * @return boolean
	 */
	private function isSign($customerID,$startTime,$endTime){
		$signModel = SinglesdayLotteryChance::model()->find("customer_id=:customer_id and way=1 and type = 1 and create_time >=:startTime and create_time <=:endTime",array(
				':customer_id' => $customerID,
				':startTime' => $startTime,
				':endTime' => $endTime
		));
		if (!empty($signModel)){
			return 1;
		}
		return 0;
	}
	
	/**
	 * 获取抽奖机会
	 * @param unknown $customerID
	 * @param unknown $startTime
	 * @param unknown $endTime
	 * @return number
	 */
	public function getChance($customerID,$startTime,$endTime,$isAdd = false){
		if ($isAdd){
			$type = 'and type=1';
		}else {
			$type = '';
		}
		//判断是否有抽奖机会
		$sql="select sum(chance) as total from singlesday_lottery_chance where customer_id={$customerID} and way!=3 {$type} and create_time>={$startTime} and create_time<={$endTime}";
		$chanceArr =Yii::app()->db->createCommand($sql)->queryAll();
		if (!empty($chanceArr)){
			return $chanceArr[0]['total'];
		}
		return 0;
	}
	
	/**
	 * 判断是否为彩富用户
	 * @param unknown $customerID
	 */
	private function isCaifu($customerID){
		$isCaiFu = 0;
		//判断是否为彩富用户
		$propertyArr = PropertyActivity::model()->find('customer_id=:customer_id and status not in(0,1,98,90)',array(
				':customer_id' => $customerID
		));
		if (!empty($propertyArr)){
			$isCaiFu = 1;
		}else {
			$appreciationArr = AppreciationPlan::model()->find('customer_id=:customer_id and status not in(0,1,98,90)',array(
					':customer_id' => $customerID
			));
			if (!empty($appreciationArr)){
				$isCaiFu = 1;
			}
		}
		return $isCaiFu;
	}
	
	/**
	 * 判断是否已换签
	 * @param unknown $customerID
	 */
	private function isChange($customerID){
		$changeSign = SinglesdayLotteryChance::model()->find("customer_id=:customer_id and way=3",array(
				':customer_id' => $customerID,
		));
		if (!empty($changeSign)){
			return 1;
		}
		return 0;
	}
	
	/**
	 * 判断奖品数量是否领完
	 * @param unknown $startTime
	 * @param unknown $endTime
	 * @return boolean
	 */
	private function isPrizeEnough($startTime,$endTime){
		$isDeleteFanPiao = false;
		$isOver = 0;
		$fpEnough = false;
		$oneYuanEnough = false;
		$yhq12Enough = false;
		$yhq20Enough = false;
		$yhq88Enough = false;
		$day = date("j");
		//没开奖直接返回空
		$hours = date("G");
		if ($hours >= 0 && $hours <= 8){
			return array(
				'isOver' => 1,
				'prizeArr' => array()
			);
		}
		//读取每天的优惠券
		$this->prizeArr[2]['yhqid'] = $this->prizeIDArr[$day][0];
		$this->prizeArr[3]['yhqid'] = $this->prizeIDArr[$day][1];
		$this->prizeArr[4]['yhqid'] = $this->prizeIDArr[$day][2];
		$prizeID = array();
		if ($this->totalArr[1] == 0){
			$prizeID[] = 1;
			unset($this->prizeArr[0]);
			$fpEnough = true;
		}
		if ($this->totalArr[2] == 0){
			$prizeID[] = 2;
			unset($this->prizeArr[1]);
			$oneYuanEnough = true;
		}
		if ($this->totalArr[3] == 0){
			$prizeID[] = 3;
			unset($this->prizeArr[2]);
			$yhq12Enough = true;
		}
		if ($this->totalArr[4] == 0){
			$prizeID[] = 4;
			unset($this->prizeArr[3]);
			$yhq20Enough = true;
		}
		if ($this->totalArr[5] == 0){
			$prizeID[] = 5;
			unset($this->prizeArr[4]);
			$yhq88Enough = true;
		}
		
		//判断扣款账户余额不足不发
		if (!$fpEnough){
			$redPacketCustomer=Customer::model()->find("mobile=:mobile and state=0 and is_deleted=0",array(':mobile'=>$this->cmobile));
			if (empty($redPacketCustomer) || $redPacketCustomer->getBalance()<$this->prizeArr[0]['money']){
				$prizeID[] = 1;
				unset($this->prizeArr[0]);
				$fpEnough = true;
			}
		}
		$strPrizeID = '';
		if (count($prizeID) > 0){
			$strPrizeID = ' and prize_id not in ('.implode(",", $prizeID).')';
		}
		$sql="select prize_id,count(*) as total from singlesday_lottery_member where is_use = 0 {$strPrizeID} and create_time>={$startTime} and create_time<={$endTime} group by prize_id";
		$arr =Yii::app()->db->createCommand($sql)->queryAll();
		if (!empty($arr)){
			foreach ($arr as $val){
				switch ($val['prize_id']){
					case 1: //彩饭票
						if (!$fpEnough && $val['total'] >= $this->totalArr[1]){
							unset($this->prizeArr[0]);
							$fpEnough = true;
						}
						break;
					case 2: //一元换购码
						if (!$oneYuanEnough && $val['total'] >= $this->totalArr[2]){
							unset($this->prizeArr[1]);
							$oneYuanEnough = true;
						}
						break;
					case 3:
						if (!$yhq12Enough && $val['total'] >= $this->totalArr[3]){
							unset($this->prizeArr[2]);
							$yhq12Enough = true;
						}
						break;
					case 4:
						if (!$yhq20Enough && $val['total'] >= $this->totalArr[4]){
							unset($this->prizeArr[3]);
							$yhq20Enough = true;
						}
						break;
					case 5:
						if (!$yhq88Enough && $val['total'] >= $this->totalArr[5]){
							unset($this->prizeArr[4]);
							$yhq88Enough = true;
						}
						break;
					default:
						break;
				}
			}
		}
		//全部满足则今天抽奖结束
		if ($fpEnough && $oneYuanEnough && $yhq12Enough && $yhq20Enough && $yhq88Enough){
			$isOver = 1;
		}
		return array(
				'isOver' => $isOver,
				'prizeArr' => $this->prizeArr
			);
	}
	
	/**
	 * 获取我的奖励
	 * @param unknown $customerID
	 * @return multitype:
	 */
	public function getMyReward($customerID){
		if (empty($customerID) || $customerID <= 0){
			return array();
		}
		$sql="select prize_name,sign_name,FROM_UNIXTIME(create_time,'%m月%d日') as add_time from singlesday_lottery_member where is_use = 0 and customer_id = {$customerID} order by create_time desc";
		$reward =Yii::app()->db->createCommand($sql)->queryAll();
		return $reward;
	}
	
	/**
	 * 签到
	 * @param unknown $customerID
	 * @return number
	 */
	public function addSign($customerID){
		if (empty($customerID) || $customerID <= 0){
			return array(
					'status' => -1,
					'msg' => '参数错误'
			);
		}
		$dateTime = $this->getPerTime();
		//判断是否已签到
		$isSign = $this->isSign($customerID, $dateTime['startTime'], $dateTime['endTime']);
		if ($isSign == 1){
			return array(
					'status' => -2,
					'msg' => '您已签到'
			);
		}
		$isEnough = $this->getChance($customerID, $dateTime['startTime'], $dateTime['endTime'],true);
		if ($isEnough >=5){
			return array(
					'status' => -3,
					'msg' => '每天最多可获得5次抽签机会'
			);
		}
		$chance = $this->getChance($customerID, $dateTime['startTime'], $dateTime['endTime']);
		//入库
		$singlesdayLotteryChance = new SinglesdayLotteryChance();
		$singlesdayLotteryChance->customer_id = $customerID;
		$singlesdayLotteryChance->chance = 1;
		$singlesdayLotteryChance->way = 1;
		$singlesdayLotteryChance->type = 1;
		$singlesdayLotteryChance->create_time = time();
		if ($singlesdayLotteryChance->save()){
			return array(
					'status' => 1,
					'msg' => '签到成功',
					'chance' => $chance+1
			);
		}else {
			return array(
					'status' => 0,
					'msg' => '签到失败'
			);
		}
	}
	
	/**
	 * 获取当天时间
	 * @return multitype:number
	 */
	private function getPerTime(){
		$startTime = strtotime(date("Y-m-d").'00:00:00');
		$endTime = strtotime(date("Y-m-d").' 23:59:59');
		return array(
			'startTime' => $startTime,
			'endTime' => $endTime
		);
	}
	
	/**
	 * 抽签
	 * @param unknown $customer
	 * @return multitype:number string
	 */
	public function addDraw($customer){
		if (empty($customer)){
			return array(
					'status' => -1,
					'msg' => '用户不存在'
			);
		}
		$dateTime = $this->getPerTime();
		$startTime = $dateTime['startTime'];
		$endTime = $dateTime['endTime'];
		$hours = date("G");
		//奖品还没开放
		if ($hours >= 0 && $hours <= 8){
			return array(
					'status' => -6,
					'msg' => '活动暂未开始，请于9点后再来'
			);
		}
		//判断用户每天是否抽了5次
		$isTimesEnough = $this->isTimesEnough($customer->id, $startTime, $endTime);
		if ($isTimesEnough == 1){
			return array(
					'status' => -2,
					'msg' => '抽奖机会已用完'
			);
		}
		//判断是否已签到
		$isSign = $this->isSign($customer->id, $startTime, $endTime);
		
 		//判断是否还有抽奖机会
		$chance = $this->getChance($customer->id, $startTime, $endTime);
		if ($chance <= 0){
			if ($isSign == 0){
				return array(
						'status' => -5,
						'msg' => '您还未签到，点击签到获得一次抽奖机会'
				);
			}else {
				return array(
						'status' => -3,
						'msg' => '抽奖机会已用完'
				);
			}
			
		}
		//判断数量是否已用完
		$checkArr = $this->isPrizeEnough($startTime, $endTime);
		if ($checkArr['isOver'] == 1 || empty($checkArr['prizeArr'])){
			return array(
					'status' => -4,
					'msg' => '今日上上签已抽完，请明日再来'
			);
		}
		foreach ($checkArr['prizeArr'] as $key => $val) {
			$arr[$val['id']] = $val['v'];
		}
		$rid = $this->get_rand($arr); //根据概率获取奖项id
		//抽奖扣除机会
		$chanceModel = new SinglesdayLotteryChance();
		$chanceModel->customer_id = $customer->id;
		$chanceModel->chance = -1;
		$chanceModel->way = 0;
		$chanceModel->type = 2;
		$chanceModel->create_time = time();
		if ($chanceModel->save()){
			//中奖纪录
			$singlePrize = new SinglesdayLotteryMember();
			$singlePrize->customer_id = $customer->id;
			$singlePrize->prize_id = $this->prizeArr[$rid-1]['id'];
			$singlePrize->prize_name = $this->prizeArr[$rid-1]['prizeName'];
			$singlePrize->sign_name = $this->prizeArr[$rid-1]['signName'];
			$singlePrize->changed_id = 0;
			$singlePrize->is_use = 0;
			$singlePrize->create_time = time();
			if ($singlePrize->save()){
				//抽奖
				if ($rid == 1){ //饭票
					$price = $this->prizeArr[0]['money'];
					//扣款账户
					$redPacketCustomer = Customer::model()->find("mobile=:mobile and state=0",array(':mobile'=>$this->cmobile));
					if (!empty($redPacketCustomer) && $redPacketCustomer->getBalance()>=$this->prizeArr[0]['money'] && $redPacketCustomer->is_deleted == 0){
						$redPacketResult=RedPacketCarry::model()->customerTransferAccounts($redPacketCustomer->id,$customer->id,$this->prizeArr[0]['money'],1,$this->cmobile,'2016年双十一电商专惠抽奖活动');
						if ($redPacketResult['status'] == 1){
							$result = array(
									'status' => 1,
									'signID' => $this->prizeArr[0]['signID'],
									'name' => $this->prizeArr[0]['prizeName'],
									'prizeID' => $this->prizeArr[$rid-1]['id']
							);
							Yii::log('2016年双十一电商专惠抽奖活动'.date('Y-m-d',time()).$this->cmobile.'账号转饭票给'.$customer->mobile.'成功！转账单号：'.$redPacketResult['msg'],CLogger::LEVEL_INFO,'colourlife.core.2016SinglesDay');
						}else {
							$result = array(
									'status' => 0,
									'msg' => '哎呀，今日运气不佳，换换运气再来',
							);
							Yii::log('2016年双十一电商专惠抽奖活动'.date('Y-m-d',time()).$this->cmobile.'账号转饭票给'.$customer->mobile.'失败！错误信息为：'.$redPacketResult['msg'],CLogger::LEVEL_ERROR,'colourlife.core.2016SinglesDay');
						}
					}else {
						$result = array(
								'status' => 0,
								'msg' => '哎呀，今日运气不佳，换换运气再来',
						);
					}
						
				}elseif ($rid == 2){ //一元购码
					$onecode = OneYuanBuy::sendCode($customer->id, $this->oneyuanGoods, 0, $type = 'SinglesDay');
					if ($onecode){
						$result = array(
								'status' => 1,
								'signID' => $this->prizeArr[1]['signID'],
								'name' => $this->prizeArr[1]['prizeName'],
								'prizeID' => $this->prizeArr[$rid-1]['id']
						);
					}else {
						$result = array(
								'status' => 0,
								'msg' => '哎呀，今日运气不佳，换换运气再来',
						);
					}
				}else { //优惠券
					$youhuiquanID = $this->prizeArr[$rid-1]['yhqid'];
					$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$youhuiquanID,':mobile'=>$customer->mobile));
					if (empty($userCouponsArr)){
						$uc_model=new UserCoupons();
						$uc_model->mobile=$customer->mobile;
						$uc_model->you_hui_quan_id=$youhuiquanID;
						$uc_model->create_time=time();
						$yhqResult=$uc_model->save();
						if ($yhqResult){
							$result = array(
									'status' => 1,
									'signID' => $this->prizeArr[$rid-1]['signID'],
									'name' => $this->prizeArr[$rid-1]['prizeName'],
									'prizeID' => $this->prizeArr[$rid-1]['id']
							);
						}else {
							$result = array(
									'status' => 0,
									'msg' => '哎呀，今日运气不佳，换换运气再来',
							);
						}
					}else {
						$result = array(
								'status' => 1,
								'signID' => $this->prizeArr[$rid-1]['signID'],
								'name' => $this->prizeArr[$rid-1]['prizeName'],
								'prizeID' => $this->prizeArr[$rid-1]['id']
						);
					}
				}
				//抽奖入库失败则更新奖品无效
				if ($result['status'] == 0){
					SinglesdayLotteryMember::model()->updateByPk($singlePrize->attributes['id'], array('is_use'=>1));
				}else {
					$result['cid'] = $singlePrize->attributes['id']+$this->prizeArr[$rid-1]['id'];
				}
			}else { //中奖纪录写入失败
				$result = array(
						'status' => 0,
						'msg' => '哎呀，今日运气不佳，换换运气再来',
				);
			}
			$result['chance'] = $chance-1;
		}else { //抽奖失败
			$result = array(
					'status' => 0,
					'msg' => '哎呀，今日运气不佳，换换运气再来',
					'chance' => $chance
			);
		}
		
		return $result;
	}
	
	/*
	 * @version 概率算法
	* @param array $proArr
	* return array
	*/
	private function get_rand($proArr){
		$result = '';
		//概率数组的总概率精度
		$proSum=0;
		foreach ($proArr as $v){
			$proSum+=$v;
		}
		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			}else {
				$proSum -= $proCur;
			}
		}
		unset ($proArr);
		return $result;
	}
	
	/**
	 * 换签
	 * @param unknown $customer
	 * @param unknown $prizeID
	 * @param unknown $changeID
	 * @return multitype:number string
	 */
	public function addChangeSign($customer,$prizeID,$changeID){
		if (empty($customer) || empty($prizeID) || empty($changeID)){
			return array(
					'status' => -1,
					'msg' => '参数错误'
			);
		}
		$hours = date("G");
		//判断数量是否已用完
		if ($hours >=0 && $hours <=8){
			return array(
					'status' => -5,
					'msg' => '活动暂未开始，请于9点后再来'
			);
		}
		$singlePrizeMember = SinglesdayLotteryMember::model()->findByPk($changeID);
		if (empty($singlePrizeMember) || $singlePrizeMember->customer_id != $customer->id || $singlePrizeMember->prize_id != $prizeID){
			return array(
					'status' => -1,
					'msg' => '参数错误'
			);
		}
		//判断是否彩富用户
		$isCaiFu = $this->isCaifu($customer->id);
		if ($isCaiFu == 0){
			return array(
					'status' => -2,
					'msg' => '很抱歉，只有彩富用户才有换签机会'
			);
		}
		//判断是否已换过签
		$isChange = $this->isChange($customer->id);
		if ($isChange == 1){
			return array(
					'status' => -3,
					'msg' => '很抱歉，您的换签机会已用完'
			);
		}
		$dateTime = $this->getPerTime();
		$startTime = $dateTime['startTime'];
		$endTime = $dateTime['endTime'];
		$checkArr = $this->isPrizeEnough($startTime, $endTime);
		if ($checkArr['isOver'] == 1){
			return array(
					'status' => -4,
					'msg' => '今日上上签已换完，请明日再来'
			);
		}
		//剔除抽中的那个奖品
		if (isset($checkArr['prizeArr'][$prizeID-1])){
			unset($checkArr['prizeArr'][$prizeID-1]);
		}
		if (empty($checkArr['prizeArr'])){
			return array(
					'status' => -4,
					'msg' => '今日上上签已换完，请明日再来'
			);
		}
		//换签
		foreach ($checkArr['prizeArr'] as $key => $val) {
			$arr[$val['id']] = $val['v'];
		}
		$rid = $this->get_rand($arr); //根据概率获取奖项id
		//抽奖扣除机会
		$chanceModel = new SinglesdayLotteryChance();
		$chanceModel->customer_id = $customer->id;
		$chanceModel->chance = -1;
		$chanceModel->way = 3;
		$chanceModel->type = 2;
		$chanceModel->create_time = time();
		if ($chanceModel->save()){
			//中奖纪录
			$singlePrize = new SinglesdayLotteryMember();
			$singlePrize->customer_id = $customer->id;
			$singlePrize->prize_id = $this->prizeArr[$rid-1]['id'];
			$singlePrize->prize_name = $this->prizeArr[$rid-1]['prizeName'];
			$singlePrize->sign_name = $this->prizeArr[$rid-1]['signName'];
			$singlePrize->changed_id = $changeID;
			$singlePrize->is_use = 0;
			$singlePrize->create_time = time();
			if ($singlePrize->save()){
				//抽奖
				if ($rid == 1){ //饭票
					//扣款账户
					$redPacketCustomer = Customer::model()->find("mobile=:mobile and state=0",array(':mobile'=>$this->cmobile));
					if (!empty($redPacketCustomer) && $redPacketCustomer->getBalance()>=$this->prizeArr[0]['money'] && $redPacketCustomer->is_deleted == 0){
						$redPacketResult=RedPacketCarry::model()->customerTransferAccounts($redPacketCustomer->id,$customer->id,$this->prizeArr[0]['money'],1,$this->cmobile,'2016年双十一电商专惠抽奖活动');
						if ($redPacketResult['status'] == 1){
							$result = array(
									'status' => 1,
									'signID' => $this->prizeArr[0]['signID'],
									'name' => $this->prizeArr[0]['prizeName'],
							);
							Yii::log('2016年双十一电商专惠抽奖活动'.date('Y-m-d',time()).$this->cmobile.'账号转饭票给'.$customer->mobile.'成功！转账单号：'.$redPacketResult['msg'],CLogger::LEVEL_INFO,'colourlife.core.2016SinglesDay');
						}else {
							$result = array(
									'status' => 0,
									'msg' => '哎呀，今日运气不佳，换换运气再来',
							);
							Yii::log('2016年双十一电商专惠抽奖活动'.date('Y-m-d',time()).$this->cmobile.'账号转饭票给'.$customer->mobile.'失败！错误信息为：'.$redPacketResult['msg'],CLogger::LEVEL_ERROR,'colourlife.core.2016SinglesDay');
						}
					}else {
						$result = array(
								'status' => 0,
								'msg' => '哎呀，今日运气不佳，换换运气再来',
						);
					}
		
				}elseif ($rid == 2){ //一元购码
					$onecode = OneYuanBuy::sendCode($customer->id, $this->oneyuanGoods, 0, $type = 'SinglesDay');
					if ($onecode){
						$result = array(
								'status' => 1,
								'signID' => $this->prizeArr[1]['signID'],
								'name' => $this->prizeArr[1]['prizeName'],
						);
					}else {
						$result = array(
								'status' => 0,
								'msg' => '哎呀，今日运气不佳，换换运气再来',
						);
					}
				}else { //优惠券
					$youhuiquanID = $this->prizeArr[$rid-1]['yhqid'];
					$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$youhuiquanID,':mobile'=>$customer->mobile));
					if (empty($userCouponsArr)){
						$uc_model=new UserCoupons();
						$uc_model->mobile=$customer->mobile;
						$uc_model->you_hui_quan_id=$youhuiquanID;
						$uc_model->create_time=time();
						$yhqResult=$uc_model->save();
						if ($yhqResult){
							$result = array(
									'status' => 1,
									'signID' => $this->prizeArr[$rid-1]['signID'],
									'name' => $this->prizeArr[$rid-1]['prizeName'],
							);
						}else {
							$result = array(
									'status' => 0,
									'msg' => '哎呀，今日运气不佳，换换运气再来',
							);
						}
					}else {
						$result = array(
								'status' => 1,
								'signID' => $this->prizeArr[$rid-1]['signID'],
								'name' => $this->prizeArr[$rid-1]['prizeName'],
						);
					}
				}
				//抽奖入库失败则更新奖品无效
				if ($result['status'] == 0){
					$sid = $singlePrize->attributes['id'];
				}else {
					$sid = $changeID;
				}
				SinglesdayLotteryMember::model()->updateByPk($sid, array('is_use'=>1));
			}else { //中奖纪录写入失败
				$result = array(
						'status' => 0,
						'msg' => '哎呀，今日运气不佳，换换运气再来',
				);
			}
		}else { //抽奖失败
			$result = array(
					'status' => 0,
					'msg' => '哎呀，今日运气不佳，换换运气再来',
			);
		}
		return $result;
	}
	
}
