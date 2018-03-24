<?php
/**
 * 年货庆典活动
 * @author taodanfeng
 *
 */
class StockingController extends ActivityController{
	public $beginTime='2016-12-15 00:00:00';//活动开始时间
	public $endTime='2017-01-10 23:59:59';//活动结束时间
	public $secret = 'st^oc*kin%g';
	public $activityName='stocking';
	public $layout = false;
	//黑名单
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'Validity',
				'signAuth',
		);
	}
	
	public function accessRules()
	{
		return array(
				array('allow', // allow admin user to perform 'admin' and 'delete' actions
						'actions' => array(),
						'users' => array('@'),
				),
		);
	}
	
	/**
	 * 首页
	 * $flag   0:弹出红包雨悬浮框 1：弹出红包悬浮按钮
	 * $tip   中奖纪录信息，最新5条
	 * $time   几点场
	 * $date   当前时间戳（用以得到动态倒计时）
	 */
	public function actionIndex(){
		$userID = $this->getUserId();
		$session = Yii::app()->session;
		$sessionKey=$userID.'stocking_flag';
		$flag=0;
		if(isset($session[$sessionKey])){
			//不弹红包雨悬浮框，通过首页“红包悬浮按钮”进入“红包雨活动”页面
			$flag=1;
		}
		Yii::app()->session->add($sessionKey,1);


		//首页中奖消息，获取最新的前5条数据
		$sql = 'select a.mobile,b.prize_name from stocking_prize_record b JOIN customer a on a.id=b.customer_id where b.prize_id!=6 order by b.id desc limit 5';
		$tip = Yii::app()->db->createCommand($sql)->queryAll();
		if(!$tip){
			$tip=0;
		}
		//var_dump($tip);
		//首页秒杀专区时间显示
		$tenTime=mktime(10, 0, 0, date('m'), date('d'), date('Y'));
		$twoTime=mktime(14, 0, 0, date('m'), date('d'), date('Y'));
		$fourTime=mktime(16, 0, 0, date('m'), date('d'), date('Y'));
		$eightTime=mktime(20, 0, 0, date('m'), date('d'), date('Y'));
		$date=time();
		if($date<$tenTime){
			$time=10;
		}else if($date<$twoTime && $date>=$tenTime){
			$time=14;
		}else if($date<$fourTime && $date>=$twoTime){
			$time=16;
		}else if($date<$eightTime && $date>=$fourTime){
			$time=20;
		}else{
			$time=10;
		}


		$this->render('/v2016/stocking/index',array(
			'flag' => $flag,
			'tip'=>$tip,
			'time'=>$time,
			'date'=>$date,
		));

	}
	//活动规则页面
	public function actionRules(){
		$this->render('/v2016/stocking/rules');
	}

	/**
	 * 专区列表页面,根据type值获取不同的商品列表
	 *$goods  某个专区的商品信息，类型为json数组
	 *$url    进入到不同类型商品的url
	 */
	public function actionArea(){
		$type=Yii::app()->request->getParam('type');
		if (empty($type)){
			$this->output('', 0, '参数错误！');
		}
		//获取所有商品
		$data = $this->getGoodsList();
		//var_dump($data);
		if (isset($data[$type])){
			$goods[$type] = $data[$type];
		}else {
			$goods[$type] = array();
		}
		//var_dump($goods);
		$url['jdUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
		$url['tuanUrl'] = ActivityGoods::model()->getShopUrl($this->getUserId());
		$this->render('/v2016/stocking/goods_nav',array(
			'goods' => json_encode($goods),
			'url' => json_encode($url)
		));
	}

	/**
	 * 专区列表页面,根据type值获取不同的商品列表
	 *$goods  秒杀专区的商品信息，类型为json数组
	 *$url    进入到不同类型商品的url
	 * $time  当前时间，来判断可以进行抢购的时间点
	 */
	public function actionAreasekill(){
		//获取所有时段秒杀商品
		$data = $this->getSekillGoodsList();
		//var_dump($data);
		$time=time();
		$url['jdUrl'] = StockingSeckillGoods::model()->getShopUrl($this->getUserId(),1);
		$url['tuanUrl'] = StockingSeckillGoods::model()->getShopUrl($this->getUserId());
		$this->render('/v2016/stocking/sekill_goods_nav',array(
			'goods' => json_encode($data),
			'url' => json_encode($url),
			'time'=>$time
		));
	}


	/**
	 * 获取所有商品
	 * @return array
	 */
	private function getGoodsList(){
		//先从缓存里获取
		$redisKey = md5($this->activityName.'_goods_list:');
		$data = Yii::app()->rediscache->get($redisKey);
		if (empty($data)){
			$data = ActivityGoods::model()->getProducts($this->activityName,true);
			Yii::app()->rediscache->set($redisKey, $data, 86400); //缓存1天
		}
		return $data;
	}


	/**
	 * 获取所有商品
	 * @return array
	 */
	private function getSekillGoodsList(){
		//先从缓存里获取
		$redisKey = md5($this->activityName.'_sekill_goods_list:');
		$data = Yii::app()->rediscache->get($redisKey);
		if (empty($data)){
		$data = StockingSeckillGoods::model()->getProducts($this->activityName,true);
			Yii::app()->rediscache->set($redisKey, $data, 86400); //缓存1天
		}
		return $data;
	}


	/**
	 * 红包雨页面
	 * $number   参与游戏的次数，每个用户1天3次机会
	 * $cookieKey  当天某用户cookie的键
	 */
	public function actionRedpacket(){
		$userID = $this->getUserId();
//		$cookieKey=md5(date('Ymd',time()).'-uid'.$userID);
//		$cookie = Yii::app()->request->getCookies();
//		if(isset($cookie[$cookieKey])){
//			//当天参与过游戏
//			$number=$cookie[$cookieKey]->value;
//		}else{
			//参与过游戏，但清除过缓存，重新获得游戏次数
			$start_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
			$end_time=mktime(23, 59, 59, date('m'), date('d'), date('Y'));
			$sql = "select `customer_id`, count(`id`) AS `total` from stocking_prize_record where customer_id=$userID and time BETWEEN $start_time and $end_time GROUP BY `customer_id`";
			$result = Yii::app()->db->createCommand($sql)->queryAll();
			//var_dump($result);exit;
			if($result){
				if($result[0]['total']>=3){
					//$this->output('', 1001, '您今天已无抽奖次数，请明天再来！');
					$number=0;
				}elseif($result[0]['total']>0 && $result[0]['total']<3){
					$number=3-$result[0]['total'];
//					$cookie = new CHttpCookie($cookieKey,$number);
//					$cookie->expire = time()+60*60*24; //有限期1天
//					Yii::app()->request->cookies[$cookieKey]=$cookie;
				}
			}else{
				$number=3;
//				$cookie = new CHttpCookie($cookieKey,$number);
//				$cookie->expire = time()+60*60*24; //有限期1天
//				Yii::app()->request->cookies[$cookieKey]=$cookie;
			}

//		}

		$this->render('/v2016/stocking/redpacket',array(
     		'number' => $number,
//			'cookieKey'=>$cookieKey
		));
	}

	/**
	 * 用户参加红包雨活动点击红包少于10个
	 */
	public function actionAjaxRedpacketMin(){
		//谢谢参与
		//将中奖信息插入到中奖记录表
		$customer_id = $this->getUserId();
		$prize=StockingPrize::model()->find("state=:state and id=:id",array(":state"=>0,":id"=>6));
		$data = array(
			'customer_id'       => $customer_id,
			'time'   => time(),
			'prize_id'=>$prize['id'],
			'prize_name'=>$prize['name']
		);
		$res = Yii::app()->db->createCommand()->insert('stocking_prize_record', $data);
		if($res){
			$this->output('', 1, '中奖信息插入成功！');
		}
	}

	/**
	 * 用户参加红包雨活动成功进行抽奖的ajax
	 */
	public function actionAjaxRedpacket(){
		$customer_id = $this->getUserId();
		$prize=StockingPrize::model()->findAll("state=:state",array(":state"=>0));

		//组装奖项数组
		$prize_arr=array();
		foreach($prize as $key=>$vo){
			$prize_arr[$vo['id']]=array('id'=>$vo['id'],'v'=>$vo['chance'],'amount'=>$vo['amount'],'name'=>$vo['name'],'desc'=>$vo['desc'],'type'=>$vo['type']);
		}
//		dump($prize_arr);exit;
		$sql = 'SELECT `prize_id`, count(`id`) AS `total` FROM `stocking_prize_record` GROUP BY `prize_id`';
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		if ($result) {
			foreach ($result as $row) {
				if ($row['total'] >= $prize_arr[$row['prize_id']]['amount'])
					//将其概率设为0永远抽不到
					$prize_arr[$row['prize_id']]['v']=0;

			}
		}

		$awards = $this->zhongjiang($prize_arr);//得到中奖的数组信息
		//dump($awards);exit;

		//同个用户每天领券不能相同
		//当天00:00:00开始，23:59：59结束
		$start_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$end_time=mktime(23, 59, 59, date('m'), date('d'), date('Y'));
		$record=StockingPrizeRecord::model()->findAllBySql("select prize_id from stocking_prize_record where time BETWEEN :start_time and :end_time and customer_id=:customer_id",array(':start_time'=>$start_time,':end_time'=>$end_time,'customer_id'=>$customer_id));
		$flag=false;
		if($record){
			//用户在当天有抽过奖
			foreach($record as $vo){
				if($awards['id']!=6){
					$prize_arr[$vo['prize_id']]['v']=0;
					if($vo['prize_id']==$awards['id']){
						$flag=true;
					}
				}
			}
		}

		if($flag==true){
			//此券已被抽到过，将抽奖概率改为0，重新进行抽奖
			$v=true;
			foreach($prize_arr as $vo1){
				if($vo1['v']!=0){
					$v=false;
				}
			}
			if($v){
				//所有奖品都有抽到过，概率都变成0了
				$this->output('', 0, '无中奖奖品！');
			}else{
				$awards = $this->zhongjiang($prize_arr);
			}

		}

		$data = array(
			'customer_id'       => $customer_id,
			'time'   => time(),
			'prize_id'=>$awards['id'],
			'prize_name'=>$awards['name']
		);


		switch ($awards['type']){
			case 0:
				//谢谢参与
				//将中奖信息插入到中奖记录表
				$res = Yii::app()->db->createCommand()->insert('stocking_prize_record', $data);
				if(!$res){
					$this->output('', 0, '中奖信息插入失败！');
				}
				break;
			case 1:
				//优惠券
				//$code = StockingPrize::model()->findByAttributes(array('id'=>$awards['id'],'state'=>0));
				$date=date('Y-m-d H:i:s',$start_time);
				//$date='2016-12-16 00:00:00';
				$sqlSelect="SELECT cc.code 
						from stocking_prize AS sp 
						LEFT JOIN conpons_change AS cc ON sp.id=cc.stockingprize_id 
						WHERE  sp.state=0 AND sp.id={$awards['id']} AND  cc.date='{$date}'";
				$query = Yii::app()->db->createCommand($sqlSelect);
				$arr = $query->queryAll();
				$res=$this->sendCupon($arr[0]['code'],$customer_id,$data);
				if(!$res){
					$this->output('', 0, '优惠券发送失败');
				}
				break;

		}
		$outData = array(
			'prize_name'=>$awards['name'],
			'desc'=>$awards['desc']
		);
		$this->output($outData);
	}


	/**
	 * 发送优惠券
	 * $cuponCode   优惠券码
	 * $customer_id  用户id
	 * $data     中奖信息数组
	 */
	private function sendCupon($cuponCode,$customer_id,$data){
		$mobile = Customer::model()->findByPk($customer_id);
		if(empty($mobile)){ return false; }
		$mobile = $mobile['mobile'];
		$transaction = Yii::app()->db->beginTransaction();
		try{
			//发优惠券
			$model = new UserCoupons();
			$model->mobile = $mobile;
			$model->you_hui_quan_id = $cuponCode;
			$model->is_use = 0;
			$model->create_time = time();
			$model->num = 0;
			//将中奖信息插入到中奖记录表
			$res = Yii::app()->db->createCommand()->insert('stocking_prize_record', $data);

			if($model->save() && $res){
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

	//获取中奖信息
	private function zhongjiang($prize_arr)
	{
		/*
         * 每次前端页面的请求，PHP循环奖项设置数组
         * 通过概率计算函数get_rand获取抽中的奖项id。
         * 将中奖奖品保存在数组$res['yes']中
         * 而剩下的未中奖的信息保存在$res['no']中
         * 最后输出json个数数据给前端页面。
         */
		foreach ($prize_arr as $key => $val) {
			$arr[$val['id']] = $val['v'];
		}
		$rid = $this->getRand($arr); //根据概率获取奖项id

		return $prize_arr[$rid];
	}

	private function getRand($proArr)
	{
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