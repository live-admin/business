<?php

/**
 * This is the model class for table "womensday_chance".
 *
 * The followings are the available columns in table 'womensday_chance':
 * @property integer $id
 * @property integer $customer_id
 * @property string $sn
 * @property integer $coupon_id
 * @property integer $chance
 * @property string $type
 * @property integer $create_time
 */
class WomensdayChance extends CActiveRecord
{
	private $prizeArr = array(
			'0' => array('id' => 1,'money' => 10,'prizeName' => '10彩饭票','total' => 0,'v' =>0),
			'1' => array('id' => 2,'prizeName' => '3000元体检券','total' => 1000,'v' =>10),
			'2' => array('id' => 3,'prizeName' => '20元洗护券','yhqid' => 30,'total' => 0,'v' => 0),
			'3' => array('id' => 4,'prizeName' => '龙蛙原生鲜米（半斤装）','total' => 5000,'v' => 60),
			'4' => array('id' => 5,'prizeName' => '100元洗护券','yhqid' => 31,'total' => 500,'v' => 0),
			'5' => array('id' => 6,'prizeName' => '综合意外险','total' => 0,'v' =>0),
			'6' => array('id' => 7,'prizeName' => '10元京东券','yhqid' => 100000382,'total' => 0,'v' => 20),
			'7' => array('id' => 8,'prizeName' => '5元京东券','yhqid' => 100000381,'total' => 0,'v' => 10)
	);
	//扣款账号
	private $cmobile=20000000005;
	//京东商品  正式
	private $good_ids = array(
			42361,
			42362,
			42363,
			42364,
			42365,
			42366,
			42367,
			42368,
			42369,
			42370,
			42371,
			42372,
			42373,
			42374,
			42375,
			42376,
			42377,
			42378,
			42379,
			42380,
			42381,
			42382,
			42383,
			42384,
			42385,
			42386,
			42387,
			42388,
			42389,
			42390,
			42391,
			42392,
			42393,
			42394,
			42395,
			42396,
			42397,
			42398,
			42399,
			42400
	);
	//测试
		/* private $good_ids = array (
				26974,
				26932,
				26931,
				26930,
				26929,
				26928,
				26927,
				26926,
				26925,
				26924,
				26923,
				26922,
				26921,
				26920,
				26919,
				20769,
				20768,
				20767,
				20766,
				20765,
				26918,
		); */
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'womensday_chance';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, coupon_id, chance, create_time', 'numerical', 'integerOnly'=>true),
			array('sn', 'length', 'max'=>32),
			array('type', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, sn, coupon_id, chance, type, create_time', 'safe', 'on'=>'search'),
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
			'sn' => '订单号',
			'coupon_id' => '微商圈券ID',
			'chance' => '抽奖机会',
			'type' => '机会类型（0已使用，1获得）',
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
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('coupon_id',$this->coupon_id);
		$criteria->compare('chance',$this->chance);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WomensdayChance the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取抽奖机会
	 * @param number $userID
	 * @return number
	 */
	public function getChance($userID = 0){
		if (empty($userID)){
			return array(
				'status' => 0,
				'chance' => 0
			);
		}
		//判断是否有抽奖机会
		$chance = self::model()->find("customer_id=:userID and type=1",array(':userID' => $userID));
		if (!empty($chance)){
			$prize = WomensdayPrizeList::model()->find("customer_id=:userID",array(':userID' => $userID));
			if (!empty($prize)){  //已抽奖
				return array(
						'status' => 2,
						'chance' => 0,
				);
			}
			return array(
				'status' => 1,
				'chance' => 1,
				'wc_id' => $chance->id,
				'coupon_id' => $chance->coupon_id
			);
		}
		//饭票商城的url
		$url = 'http://colour.kakatool.cn/bonus/bonus/couponList?userid='.$userID;
		return array(
				'status' => 3,  //没有抽奖机会
				'chance' => 0,
				'url' => $url
			);
	}
	
	/**
	 * 获取中奖记录
	 * @param number $userID
	 * @return multitype:|multitype:string NULL
	 */
	public function getPrizeList($userID = 0){
		if (empty($userID)){
			return array();
		}
		$data = array();
		$prize = WomensdayPrizeList::model()->findAll("customer_id=:userID",array(':userID' => $userID));
		if (!empty($prize)){
			foreach ($prize as $val){
				$tmp = array();
				$tmp['id'] = $val->id;
				$tmp['name'] = $val->prize_name;
				$tmp['day'] = date("Y-m-d",$val->create_time);
				$tmp['ctime'] = date("H:i:s",$val->create_time);
				$tmp['is_change'] = $val->is_change;
				$tmp['url'] = '';
				//奖品类型：'zhyw','fp','tj','dami','xihu','weizhi','jd'
				if (in_array($val->prize_id, array(3,5,7,8))){
					//$homeConfig=new HomeConfigResource();
					if ($val->prize_id == 7 || $val->prize_id == 8){
						$tmp['type'] = 'jd';
						//$href3=$homeConfig->getResourceByKeyOrId(67,1,$userID); //京东67,彩特供39
						//$tmp['url'] = $href3->completeURL;
					}elseif ($val->prize_id == 3 || $val->prize_id == 5){
						$tmp['type'] = 'exihu';
						//$href3=$homeConfig->getResourceByKeyOrId(803,1,$userID); //京东67,彩特供39
						//$tmp['url'] = '';
					}
				}elseif ($val->prize_id == 2){ //体检
					$tmp['type'] = 'tj';
				}elseif ($val->prize_id == 4){ //大米
					$tmp['type'] = 'dm';
				}elseif ($val->prize_id == 6){ //保险
					$tmp['type'] = 'bx';
				}else {
					$tmp['type'] = '';
				}
				$data[] = $tmp;
			}
		}
		return $data;
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
	 * 抽奖过程
	 */
	public function addDraw($customer){
		//判断机会是否已用完
		$result = $this->getChance($customer->id);
		if ($result['status'] !=1){
			return array(
				'status' => 0,
				'msg' => $result
			);
		}
		//判断数量是否已用完
		$prizeList = $this->isPrizeEnough();
		foreach ($prizeList as $key => $val) {
			$arr[$val['id']] = $val['v'];
		}
		$rid = $this->get_rand($arr); //根据概率获取奖项id
		$prize = $this->prizeArr[$rid-1];
		$transaction = Yii::app()->db->beginTransaction(); //启用事务
		
		$womensdayPrize = new WomensdayPrizeList();
		$womensdayPrize->customer_id = $customer->id;
		$womensdayPrize->wc_id = $result['wc_id'];
		$womensdayPrize->prize_id = $rid;
		$womensdayPrize->prize_name = $prize['prizeName'];
		$womensdayPrize->is_change = 'N';
		$womensdayPrize->create_time = time();
		if (!$womensdayPrize->save()){
			$transaction->rollback();
			return array(
					'status' => 0,
					'msg' => '哎呀，网络出错了，请稍后再试！'
			);
		}
		if (in_array($rid, array(7,8))){ //发优惠券
			$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$prize['yhqid'],':mobile'=>$customer->mobile));
			if (empty($userCouponsArr)){
				$uc_model=new UserCoupons();
				$uc_model->mobile=$customer->mobile;
				$uc_model->you_hui_quan_id=$prize['yhqid'];
				$uc_model->create_time=time();
				if (!$uc_model->save()){
					$transaction->rollback();
					return array(
							'status' => 0,
							'msg' => '哎呀，网络出错了，请稍后再试！',
					);
				}
			}
		}elseif ($rid == 1){ //发饭票
			$price = $prize['money'];
			//扣款账户
			$redPacketCustomer = Customer::model()->find("mobile=:mobile and state=0",array(':mobile'=>$this->cmobile));
			if (empty($redPacketCustomer) || $redPacketCustomer->getBalance() < $price || $redPacketCustomer->is_deleted != 0){
				$transaction->rollback();
				return array(
						'status' => 0,
						'msg' => '哎呀，网络出错了，请稍后再试！',
				);
			}
			$redPacketResult=RedPacketCarry::model()->customerTransferAccounts($redPacketCustomer->id,$customer->id,$price,1,$this->cmobile,'2017年3.8妇女节抽奖活动');
			if ($redPacketResult['status'] != 1){
				$transaction->rollback();
				return array(
						'status' => 0,
						'msg' => '哎呀，网络出错了，请稍后再试！',
				);
			}
			
		}
		$transaction->commit();
		//通知微商圈
		$r = $this->notifyWsq($result['coupon_id'],$customer->id);
		if (in_array($rid, array(3,5))){ //通知e洗护
			$exihuResult = $this->receiveExihu($prize['yhqid'],$customer->id,$customer->mobile);
			if ($exihuResult){
				$data['is_change'] = 'Y';
			}
		}
		if ($r){
			$data['is_notify'] = 1;
			WomensdayPrizeList::model()->updateByPk($womensdayPrize->attributes['id'], $data);
		}
		return array(
				'status' => 1,
				'msg' => '恭喜您中奖了',
				'name' => $prize['prizeName'],
				'pid' => $rid,
				'chance' => 0
		);
	}
	
	/**
	 * 过滤不符合要求的奖品
	 */
	private function isPrizeEnough(){
		foreach ($this->prizeArr as $key => $val){
			if ($val['v'] == 0){
				unset($this->prizeArr[$key]);
				continue;
			}
		}
		if (isset($this->prizeArr[0])){
			//扣款账户
			$redPacketCustomer = Customer::model()->find("mobile=:mobile and state=0",array(':mobile'=>$this->cmobile));
			if (empty($redPacketCustomer) || $redPacketCustomer->getBalance() < $this->prizeArr[0]['money'] || $redPacketCustomer->is_deleted != 0){
				unset($this->prizeArr[0]);
			}
		}
		if (isset($this->prizeArr[1])){
			$tiJianQuan = WomensdayPrizeList::model()->count("prize_id=2"); //体检券
			if ($tiJianQuan >= $this->prizeArr[1]['total']){
				unset($this->prizeArr[1]);
			}
		}
		if (isset($this->prizeArr[3])){
			$dami = WomensdayPrizeList::model()->count("prize_id=4"); //大米
			if ($dami >= $this->prizeArr[3]['total']){
				unset($this->prizeArr[3]);
			}
		}
		if (isset($this->prizeArr[4])){
			$xihuanQuan = WomensdayPrizeList::model()->count("prize_id=5"); //100元洗护券
			if ($xihuanQuan >= $this->prizeArr[4]['total']){
				unset($this->prizeArr[4]);
			}
		}
		return $this->prizeArr;
	}
	
	/**
	 * 通知微商圈销券
	 * @param number $couponID
	 * @return boolean
	 */
	private function notifyWsq($couponID = 0,$userID = 0){
		if (empty($couponID) || empty($userID)){
			return false;
		}
		//通知微商圈
		$param = array(
				'couponid' => $couponID //7176，7175，7174
		);
		$preFun = 'coupon/useCoupon';
		//$resetUrl = 'https://caizhiyun.kakatool.cn/';
		$resetUrl = 'https://colour.kakatool.cn/';
		try {
			$re = new ConnectWetown();
			$result = $re->getRemoteData($preFun, $param, $resetUrl,false, $userID);
			Yii::log('调用微商圈接口返回结果'.$result,CLogger::LEVEL_ERROR, 'colourlife.core.WomensdayChance.notifyWsq');
			if (!empty($result)){
				$result = json_decode($result, true);
				if ($result['result'] == 0){
					Yii::log('调用微商圈接口返回结果成功',CLogger::LEVEL_ERROR, 'colourlife.core.WomensdayChance.notifyWsq');
					return true;
				}else {
					Yii::log('调用微商圈接口返回结果result!=0：'.$result['reason'],CLogger::LEVEL_ERROR, 'colourlife.core.WomensdayChance.notifyWsq');
					return false;
				}
			}else {
				Yii::log('调用接口异常返回的错误信息：'.$result,CLogger::LEVEL_ERROR, 'colourlife.core.WomensdayChance.notifyWsq');
				return false;
			}
		}catch (Exception $e){
			Yii::log('调用接口异常返回的错误信息：'.json_encode($e->getMessage()),CLogger::LEVEL_ERROR, 'colourlife.core.WomensdayChance.notifyWsq');
			return false;
		}
	}
	
	/**
	 * 兑换
	 * @param unknown $userID
	 * @param unknown $prizeID
	 * @param unknown $uname
	 * @param unknown $mobile
	 * @param string $department
	 * @param string $address
	 * @return multitype:number string
	 */
	public function changePrize($userID,$prizeID,$uname,$mobile,$department = '',$address = ''){
		if (empty($prizeID) || empty($userID)){
			return array(
				'status' => 0,
				'msg' => '参数错误！'
			);
		}
		$prize = WomensdayPrizeList::model()->findByPk($prizeID);
		if (empty($prize)){
			return array(
					'status' => 0,
					'msg' => '奖项错误！'
			);
		}
		if ($prize->customer_id != $userID){
			return array(
					'status' => 0,
					'msg' => '不是自己的奖项！'
			);
		}
		if ($prize->is_change == 'Y'){
			return array(
					'status' => 0,
					'msg' => '已兑换！'
			);
		}
		if ($prize->prize_id == 4 && (empty($department) || empty($address))){
			return array(
					'status' => 0,
					'msg' => '事业部或地址不能为空！'
			);
		}
		$change = WomensdayChange::model()->find("wpl_id=:wplID",array(':wplID' => $prizeID));
		if (!empty($change)){
			return array(
					'status' => 0,
					'msg' => '已兑换！'
			);
		}
		//启动事务
		$transaction = Yii::app()->db->beginTransaction(); //启用事务
		$change = new WomensdayChange();
		$change->wpl_id = $prizeID;
		$change->user_name = $uname;
		$change->mobile = $mobile;
		$change->department = $department;
		$change->address = $address;
		$change->create_time = time();
		if (!$change->save()){
			$transaction->rollback();
			return array(
					'status' => 0,
					'msg' => '兑换失败！'
			);
		}
		$r = $prize->updateByPk($prizeID, array('is_change' => 'Y'));
		if (!$r){
			$transaction->rollback();
			return array(
					'status' => 0,
					'msg' => '兑换失败！'
			);
		}
		$transaction->commit();
		return array(
				'status' => 1,
				'msg' => '兑换成功！'
		);
	}
	/**
	 * 日志记录
	 * @param unknown $customer_id
	 * @param unknown $type
	 * @return boolean
	 */
	public function addLog($customer_id,$type,$prize_id = 0){
		$log = new WomensdayLog();
		$log->customer_id = $customer_id;
		$log->type = $type;
		$log->pid = $prize_id;
		$log->create_time = time();
		if ($log->save()){
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 商品列表
	 * @param number $customer_id
	 * @return multitype:|multitype:multitype:unknown string number Ambigous <CActiveRecord, unknown, Ambigous <CActiveRecord, NULL>, unknown, Ambigous <unknown, NULL>, unknown>
	 */
	public function getGoodsList(){
		$productList=array();
		$productList=Yii::app ()->cache->get ( 'womensday_jd_goods_list' );
		if (isset($_GET['pageCache'])&&$_GET['pageCache']=='false'){
			$productList=array();
		}
		//Yii::app ()->cache->delete ( $cacheKey );
		if (!empty($productList)){
			return $productList;
		}
		foreach ($this->good_ids as $val){
			$tmp=array();
			$productArr=Goods::model()->findByPk($val);
			if (empty($productArr)){
				continue;
			}
			$cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0 and status = 0',array(':goods_id'=>$val));
			if (empty($cheapArr)){
				continue;
			}
			$tmp['id']= $cheapArr->id;
			$tmp['name']=$productArr['name'];
			$image_arr=explode(':', $productArr['good_image']);
			if(count($image_arr)>1){
				$tmp['img_name'] = $productArr['good_image'];
			}else{
				$tmp['img_name'] = F::getUploadsUrl("/images/" . $productArr['good_image']);
			}
			$tmp['price']=$productArr['customer_price'];
			$productList[]=$tmp;
		}
		if (!empty($productList)){
			Yii::app ()->cache->set ( 'womensday_jd_goods_list', $productList, 86400 );
		}
		return $productList;
	}
	
	/**
	 * 领取e洗护的券
	 * @param number $couponID
	 * @return boolean
	 */
	private function receiveExihu($couponID = 0,$userID = 0,$mobile = 0){
		if (empty($couponID) || empty($userID) || empty($mobile)){
			Yii::log('调用e洗护接口返回结果'.$couponID.'_'.$userID.'_'.$mobile,CLogger::LEVEL_ERROR, 'colourlife.core.WomensdayChance.receiveExihu');
			return false;
		}
		Yii::import('common.api.EXihuApi');
		try {
			$result = EXihuApi::getInstance()->notifyEXihu($couponID, $userID, $mobile);
			Yii::log('调用e洗护接口返回结果'.$result,CLogger::LEVEL_ERROR, 'colourlife.core.WomensdayChance.receiveExihu');
			return $result;
		}catch (Exception $e){
			Yii::log('调用接口异常返回的错误信息：'.json_encode($e->getMessage()),CLogger::LEVEL_ERROR, 'colourlife.core.WomensdayChance.receiveExihu');
			return false;
		}
	}
	
	/**
	 * 再次通知微商圈
	 */
	public function nofityAgianWsq(){
		$prize = WomensdayPrizeList::model()->findAll("is_notify='0'");
		if (!empty($prize)){
			foreach ($prize as $val){
				$chance = WomensdayChance::model()->findByPk($val->wc_id);
				if (empty($chance)){
					echo "{$val->wc_id}抽奖机会为空！<br>\n";
					continue;
				} 
				//通知微商圈
				$r = $this->notifyWsq($chance->coupon_id,$chance->customer_id);
				if ($r){
					echo "{$val->wc_id}通知成功！<br>\n";
					$plResult = WomensdayPrizeList::model()->updateByPk($val->id, array('is_notify' => 1));
					echo "prizelist表更新返回值：{$plResult}<br>\n";
				}else {
					echo "{$val->wc_id}通知失败！<br>\n";
				}
			}
		}else {
			echo "数据为空！<br>\n";
		}
		return true;
	}
}
