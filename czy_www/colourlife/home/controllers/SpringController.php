<?php
/**
 * 春节活动
 * @author taodanfeng
 *
 */
class SpringController extends ActivityController{
	public $beginTime='2017-02-17 00:00:00';//活动开始时间  正式改为2017-01-26 00:00:00
	public $endTime='2017-02-19 23:59:59';//活动结束时间  正式改为2017-02-25 23:59:59
	private $endTimeSpring='2017-02-10 23:59:59';//正式改为2017-02-10 23:59:59
	public $secret = 'sp^ri*n%g';
	public $layout = false;
	//黑名单
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
				'Validity',
			    'signAuth - ShareWeb,Share,AjaxPraise',
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
	 * $message   arr 用户的所有消息
	 * $customer_info arr  用户信息
	 * flag  0:用户没有选择生肖 1：用户已经选择了生肖
	 * type  '':进入消息页 1：进入首页
	 * hongdian 数组 1：无  0：有
	 * $lingqu_status 是否可以点击领取按钮 1：可以点击 0：无法点击
	 * $endtime_status 0:活动正常进行，可以点击 1：活动已结束，无法点击
	 * $result 打扫和放鞭炮页面的签到，和连续天数
	 */
//	public function actionIndex(){
//		$userID = $this->getUserId();
//		$content=array();
//		$type=Yii::app()->request->getParam('type','');
//		$lingqu_status=0;
//		$hongdian=1;
//		$endtime_status=0;
//		$customer_arr=Customer::model()->findByPk($userID);
//
//		if(empty($customer_arr)){
//			throw new CHttpException(400, '用户信息不能为空');
//		}
//
//		//实时修改连续的天数
//		$result=$this->getQiandao($userID);
//
//		$flag=0;//是否选过生肖
//
//		//朋友圈
//		$time=time();
//		$customer_id = $userID * 778 + 1778;
//		$sign=md5('sd_id='.$customer_id.'&ts='.$time);
//		$urlShare=F::getHomeUrl('/Spring/ShareWeb').'?sd_id='.$customer_id.'&ts='.$time.'&sign='.$sign;
//
//		$customer_info=array(
//			'mobile'=>$customer_arr['mobile'],
//			'avatar'=>$customer_arr->getPortraitUrl(),
//			'blessing'=>0,
//		);
//		$customer_user=SpringUserInfo::model()->find('customer_id=:customer_id',array(':customer_id'=>$userID));
//		if($customer_user){
//			//该用户已经选过生肖
//			$flag=1;
//			$customer_info['zodiac']=$customer_user['zodiac'];
//			$customer_info['blessing']=$customer_user['blessing'];
//
//			//判断首页用户福气值是否大于等于300，并且在活动结束之后以及用户没有提交过地址，如果这两个条件成立，则可以点击领取按钮填写地址
//			$customer_address=SpringAddress::model()->find('customer_id=:customer_id',array(':customer_id'=>$userID));
//			if(($customer_info['blessing']>=300) && (time()>strtotime($this->endTimeSpring)) && empty($customer_address)){
//				$lingqu_status=1;
//			}
//
//			//活动时间结束，用户无法点击按钮
//			if(time()>strtotime($this->endTimeSpring)){
//				$endtime_status=1;
//			}
//
//			if($type){
//				//跳转首页
//				//聚宝盆，打扫，放鞭炮显示小红点
//				$hongdian=$this->showRedDot($userID);
//
//				$this->render('/v2016/spring/index',array(
//					'customer_info'=>$customer_info,
//					'type'=>$type,
//					'flag'=>$flag,
//					'hongdian'=>$hongdian,
//					'lingqu_status'=>$lingqu_status,
//					'endtime_status'=>$endtime_status,
//					'result'=>$result,
//					'surl'=>base64_encode($urlShare)
//
//				));
//			}else{
//				//跳转消息页
//				SeptemberLog::model()->addShareLog($userID,88);//首页点击记录
//				$message = SpringUserMessage::model()->findAll(array(
//					'select'=>array('message','createtime'),
//					'order' => 'createtime DESC',
//					'limit'=>50,
//					'condition' => 'customer_id=:customer_id',
//					'params' => array(':customer_id'=>$userID),
//				));
//				if($message){
//					foreach($message as $key_message=>$message_value){
//						$content[$key_message]['message']=$message_value['message'];
//						$content[$key_message]['createtime']=$message_value['createtime'];
//					}
//				}
//				//print_r($message);exit;
//				$this->render('/v2016/spring/message',array(
//					'message' => $content,
//					'customer_info'=>$customer_info,
//					'type'=>$type
//				));
//			}
//		}else{
//			//跳转首页，弹出选择生肖弹框
//			SeptemberLog::model()->addShareLog($userID,88);//首页点击记录
//			$this->render('/v2016/spring/index',array(
//				'customer_info'=>$customer_info,
//				'type'=>$type,
//				'flag'=>$flag,
//				'hongdian'=>$hongdian,
//				'lingqu_status'=>$lingqu_status,
//				'endtime_status'=>$endtime_status,
//				'result'=>$result,
//				'surl'=>base64_encode($urlShare)
//			));
//		}
//	}

//活动结束后直接进入排行榜页面查看前50名的结果
	public function actionIndex(){
		$userID = $this->getUserId();
		$user_ranking=array();
		$my_user_ranking=array();

		$sql1 = "select customer_id,mobile,zodiac,blessing from spring_user_info order by blessing DESC,createtime ASC limit 59";
		$user_arr = Yii::app()->db->createCommand($sql1)->queryAll();

//		$sql = "select customer_id,mobile,zodiac,blessing from spring_user_info order by blessing DESC,createtime ASC ";
//		$user_arr_my = Yii::app()->db->createCommand($sql)->queryAll();

		if(empty($user_arr)){
			throw new CHttpException(400, '用户排名数组不能为空');
		}

		//用户排名
		foreach ($user_arr as $key=>$value){
			$user_ranking[$key+1]['mobile']=$value['mobile'];
			$user_ranking[$key+1]['zodiac']=$value['zodiac'];
			$user_ranking[$key+1]['blessing']=$value['blessing'];
		}

//		//自己的排名
//		foreach ($user_arr_my as $key=>$value){
//			if($value['customer_id']==$userID){
//				$my_user_ranking[$key+1]['mobile']=$value['mobile'];
//				$my_user_ranking[$key+1]['zodiac']=$value['zodiac'];
//				$my_user_ranking[$key+1]['blessing']=$value['blessing'];
//			}
//		}

		//生肖排名
		$zodiac_ranking=array();
//		$user_zodiac_ranking=array();
		$sql = "select zodiac,sum(blessing) as total from spring_user_info group by zodiac order by total desc";
		$zodiac_arr = Yii::app()->db->createCommand($sql)->queryAll();

//		$customer_user=SpringUserInfo::model()->find('customer_id=:customer_id',array(':customer_id'=>$userID));
		if(!empty($zodiac_arr)){
			foreach ($zodiac_arr as $zodiac_key=>$zodiac_value){
				$zodiac_ranking[$zodiac_key+1]['zodiac']=$zodiac_value['zodiac'];
				$zodiac_ranking[$zodiac_key+1]['total']=$zodiac_value['total'];
//				if($zodiac_value['zodiac']==$customer_user['zodiac']){
//					$user_zodiac_ranking[$zodiac_key+1]['zodiac']=$zodiac_value['zodiac'];
//					$user_zodiac_ranking[$zodiac_key+1]['total']=$zodiac_value['total'];
//
//				}
			}
		}else{
			throw new CHttpException(400, '您之前未参与活动，无法看到最终排名结果！');
		}

//		var_dump($my_user_ranking);
//		var_dump($user_zodiac_ranking);
//		exit;
		$customer_info=$this->getUserMessage();
		$this->render('/v2016/spring/ranking',array(
			'user_ranking'=>$user_ranking,
//			'my_user_ranking'=>$my_user_ranking,
			'zodiac_ranking'=>$zodiac_ranking,
			'customer_info'=>$customer_info,
//			'user_zodiac_ranking'=>$user_zodiac_ranking
		));
	}

	/**
	 * 选择生肖调用ajax
	 * @param $message   zodiac  生肖
	 */
	public function actionAjaxChooseZodiac(){
		$userID = $this->getUserId();
		$customer_user=SpringUserInfo::model()->find('customer_id=:customer_id',array(':customer_id'=>$userID));
		$zodiac=Yii::app()->request->getParam('zodiac','');
		$customer_arr=Customer::model()->findByPk($userID);

		if(empty($zodiac)||empty($customer_arr)){
			throw new CHttpException(400, '用户信息或者zodiac不能为空');
		}

		if($customer_user){
			$this->output('', 0, '您之前已经选择了生肖！');
		}else{
			$data = array(
				'customer_id'=> $userID,
				'mobile'   => $customer_arr['mobile'],
				'zodiac'=>$zodiac,
				'blessing'=>0,
				'firenum'=>3,
				'createtime'=>time()
			);

			$res = Yii::app()->db->createCommand()->insert('spring_user_info', $data);
			$customer_info=$this->getUserMessage();
			if($res && $customer_info){
				$customer_info['zodiac']=$zodiac;
				$this->output($customer_info, 1, '生肖选择成功');
			}
		}
	}

	/**
	 * 分享
	 */
	public function actionShareWeb(){
		$sd_id=  intval(Yii::app()->request->getParam('sd_id'));//用户id
		$time=Yii::app()->request->getParam('ts');
		$sign=Yii::app()->request->getParam('sign');
		$checkSign=md5('sd_id='.$sd_id.'&ts='.$time);
		if ($sign!=$checkSign){
			throw new CHttpException(400, "验证失败！");
		}
		$start_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$end_time=mktime(23, 59, 59, date('m'), date('d'), date('Y'));
		$customer_id=intval(($sd_id-1778)/778);
		$content=array();
		$customer_info=$this->getUserMessage($customer_id);
		$praise_message=SpringPraise::model()->findAll('from_customer_id=:from_customer_id and createtime between :start_time and :end_time',array(":from_customer_id" =>$customer_id,":start_time" => $start_time, ":end_time" => $end_time));
		if($praise_message){
			foreach($praise_message as $key_praise_message=>$praise_value){
				$customer_arr=Customer::model()->findByPk($praise_value['customer_id']);
				if($customer_arr){
					$content[$key_praise_message]['mobile']=$customer_arr['mobile'];
					$content[$key_praise_message]['createtime']=$praise_value['createtime'];
				}else{
					continue;
				}


			}
		}
		$this->render('/v2016/spring/share',array(
			'sd_id'=>$sd_id,
			'praise_message'=>$content,
			'customer_info'=>$customer_info
		));
	}


	/**
	 * 分享页点赞
	 * $from_customer_id 点赞人的用户id
	 * $customer_id 被点赞的用户id
	 */
	public function actionAjaxPraise(){
		$from_customer_id=intval(Yii::app()->request->getParam('from_id'));
		$sd_id=intval(Yii::app()->request->getParam('sd_id'));
		$customer_id=intval(($sd_id-1778)/778);

		$start_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$end_time=mktime(23, 59, 59, date('m'), date('d'), date('Y'));

		if(empty($from_customer_id)||empty($sd_id)){
			throw new CHttpException(400, '获取用户id异常');
		}

		//不能给自己点赞
		if($from_customer_id==$customer_id){
			$this->output('', 2, '自己不能给自己点赞');
		}

		$transaction = Yii::app()->db->beginTransaction();
		try {
			//该用户今天已经点过一次赞，不能再点赞了
			$flag = SpringPraise::model()->find("customer_id=:from_customer_id and from_customer_id=:customer_id and createtime between :start_time and :end_time", array(":from_customer_id" => $from_customer_id, ":customer_id" => $customer_id, ":start_time" => $start_time, ":end_time" => $end_time));
			if ($flag) {
				$this->output('', 3, '您今天已经给他点过赞了，请明天再来');
			}

			//每人每天最多只能获得5个福气值
			$time = time();
			$data = array(
				'customer_id' => $from_customer_id,
				'from_customer_id' => $customer_id,
				'createtime' => $time,
			);

			$sql = "select count(1) as number from spring_praise where from_customer_id=$customer_id and createtime BETWEEN $start_time and $end_time GROUP BY from_customer_id";
			$number = Yii::app()->db->createCommand($sql)->queryAll();

			if (!empty($number) && ($number[0]['number'] >= 5)) {
				$execute = Yii::app()->db->createCommand()->insert('spring_praise', $data);

				if($execute){
					$transaction->commit();
					$this->output('', 4, '点赞人数超过5个，只能点赞，不能获取福气');
				}else{
					$transaction->rollback();
					$this->output('', 0, '点赞失败');
				}

			}else{
				$execute = Yii::app()->db->createCommand()->insert('spring_praise', $data);

				$blessing = SpringUserInfo::model()->find("customer_id=:customer_id", array(":customer_id" => $customer_id));
				$new_blessing = $blessing['blessing'] + 1;
				$res2 = SpringUserInfo::model()->updateAll(array('blessing' => $new_blessing), 'customer_id=:customer_id', array(':customer_id' => $customer_id));

				$res3 = $this->addMessage($from_customer_id, '给你点了一个赞，获得1个福气值', $customer_id);
				if($execute && $res2>0 && $res3){
					$transaction->commit();
					$this->output('', 1, '你今天已点赞成功，福气值+1');
				}else{
					$transaction->rollback();
					$this->output('', 0, '点赞失败');
				}

			}

		}catch (Exception $e) {
			$transaction->rollback();
			$this->output('', 0, 'catch捕捉到错误');
		}

	}


    //领取地址界面
	public function actionAddress(){
		$this->render('/v2016/spring/address');
	}

	//填写地址后点击保存
	public function actionAjaxSaveAddress(){
		    $userID = $this->getUserId();
			try {
				$provinceName = Yii::app()->request->getParam('provinceName');
				$cityName = Yii::app()->request->getParam('cityName');
				$countyName = Yii::app()->request->getParam('countyName');
				$townName = Yii::app()->request->getParam('townName');

				$detailaddress = Yii::app()->request->getParam('address');
				$tel = Yii::app()->request->getParam('buyer_tel');
				$username = Yii::app()->request->getParam('buyer_name');

				//地址拼接
				$address = $provinceName . ' ' . $cityName . ' ' . $countyName . ' ' . $townName . ' ' . $detailaddress;
				$time = time();
				$data = array(
					'customer_id' => $userID,
					'address' => $address,
					'mobile' => $tel,
					'username' => $username,
					'createtime' => $time,
				);

				//判断首页用户福气值是否大于等于300，并且在活动结束之后以及用户没有提交过地址，如果这两个条件成立，则可以点击领取按钮填写地址
				$customer_address=SpringAddress::model()->find('customer_id=:customer_id',array(':customer_id'=>$userID));
				$customer_user=SpringUserInfo::model()->find('customer_id=:customer_id',array(':customer_id'=>$userID));
				if(($customer_user['blessing']>=300) && (time()>strtotime($this->endTimeSpring)) && empty($customer_address)){
					$execute=Yii::app()->db->createCommand()->insert('spring_address', $data);
					if($execute){
						$this->output('', 1, '地址保存成功，请耐心等待发货');
					}else{
						Yii::log("春节活动地址保存失败",CLogger::LEVEL_INFO,'colourlife.core.SpringAjaxSaveAddress');
						$this->output('', 0, '地址保存失败');
					}
				}else{
					$this->output('', 0, '无法保存地址');
				}

			}catch (Exception $e){
				Yii::log("春节活动地址保存失败",CLogger::LEVEL_INFO,'colourlife.core.SpringAjaxSaveAddress');
				$this->output('', 0, '错误');
			}
	}

	//活动规则页面
	public function actionRules(){
		$customer_info=$this->getUserMessage();
		$this->render('/v2016/spring/rules',array('customer_info'=>$customer_info));
	}

	//消息页面
	public function actionMessage(){
		$userID = $this->getUserId();
		$type=Yii::app()->request->getParam('type','');
		$customer_info=$this->getUserMessage();

		if(empty($type)||empty($customer_info)){
			throw new CHttpException(400, '用户栏信息或者type不能为空');
		}

		$content=array();
		$message = SpringUserMessage::model()->findAll(array(
			'select'=>array('message','createtime'),
			'order' => 'createtime DESC',
			'limit'=>50,
			'condition' => 'customer_id=:customer_id',
			'params' => array(':customer_id'=>$userID),
		));
		if($message){
			foreach($message as $key_message=>$message_value){
				$content[$key_message]['message']=$message_value['message'];
				$content[$key_message]['createtime']=$message_value['createtime'];
			}
		}
		$this->render('/v2016/spring/message',array(
			'message' =>$content ,
			'customer_info'=>$customer_info,
			'type'=>$type
		));
	}

	/**
	 * 排行榜
	 *$user_ranking 用户排名数组
	 * $zodiac_ranking 生肖排名数组
	 * my_user_ranking 本人排名数组
	 */
	public function actionRanking(){
		$userID = $this->getUserId();
		$user_ranking=array();
		$my_user_ranking=array();
		$start_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$end_time=mktime(23, 59, 59, date('m'), date('d'), date('Y'));

		$user_arr = SpringUserInfo::model()->findAll(array(
			'select'=>array('customer_id','mobile','zodiac','blessing'),
			'order' => 'blessing DESC',
		));

		if(empty($user_arr)){
			throw new CHttpException(400, '用户排名数组不能为空');
		}

		$sql = "select * from spring_grourd_record where customer_id=$userID and createtime BETWEEN $start_time and $end_time";
		$grourd = Yii::app()->db->createCommand($sql)->queryAll();
		//var_dump($grourd);exit;
		//用户排名
		foreach ($user_arr as $key=>$value){
			if($grourd){
				//吸福气的纪录
				foreach ($grourd as $vo){
					//葫芦为灰色无法点击
					if(($vo['from_customer_id']==$value['customer_id'])||($value['blessing']<=0)){
						$user_ranking[$key+1]['status']=0;//葫芦为灰色无法点击
					}
				}
			}else{
				if(($value['blessing']<=0)){
					$user_ranking[$key+1]['status']=0;//葫芦为灰色无法点击
				}else{
					$user_ranking[$key+1]['status']=1;//葫芦为亮色可以吸福气
				}
			}

			$user_ranking[$key+1]['mobile']=$value['mobile'];
			$user_ranking[$key+1]['zodiac']=$value['zodiac'];
			$user_ranking[$key+1]['blessing']=$value['blessing'];

			//自己的排名
			if($value['customer_id']==$userID){
				$my_user_ranking[$key+1]['mobile']=$value['mobile'];
				$my_user_ranking[$key+1]['zodiac']=$value['zodiac'];
				$my_user_ranking[$key+1]['blessing']=$value['blessing'];
				$my_user_ranking[$key+1]['status']=0;
				$user_ranking[$key+1]['status']=0;//葫芦为灰色无法点击
			}
		}

		//生肖排名
		$zodiac_ranking=array();
		$user_zodiac_ranking=array();
		$sql = "select zodiac,sum(blessing) as total from spring_user_info group by zodiac order by total desc";
		$zodiac_arr = Yii::app()->db->createCommand($sql)->queryAll();

		$customer_user=SpringUserInfo::model()->find('customer_id=:customer_id',array(':customer_id'=>$userID));
		if(!empty($zodiac_arr) && !empty($customer_user)){
			foreach ($zodiac_arr as $zodiac_key=>$zodiac_value){
				$zodiac_ranking[$zodiac_key+1]['zodiac']=$zodiac_value['zodiac'];
				$zodiac_ranking[$zodiac_key+1]['total']=$zodiac_value['total'];
				if($zodiac_value['zodiac']==$customer_user['zodiac']){
					$user_zodiac_ranking[$zodiac_key+1]['zodiac']=$zodiac_value['zodiac'];
					$user_zodiac_ranking[$zodiac_key+1]['total']=$zodiac_value['total'];

				}
			}
		}else{
			throw new CHttpException(400, '误操作导致排名获取失败');
		}

		var_dump($user_ranking);
//		var_dump($user_zodiac_ranking);
		exit;
		$customer_info=$this->getUserMessage();
		$this->render('/v2016/spring/ranking',array(
			'user_ranking'=>$user_ranking,
			'my_user_ranking'=>$my_user_ranking,
			'zodiac_ranking'=>$zodiac_ranking,
			'customer_info'=>$customer_info,
			'user_zodiac_ranking'=>$user_zodiac_ranking
		));
	}


	/**
	 * 葫芦吸福气页面
	 * @param $mobile 被吸取福气的用户号码
	 * $blessing 被吸福气用户福气值
	 * $from_customer 被吸福气的用户id
	 */

	public function actionGrourdGetBlessing(){
		$mobile=Yii::app()->request->getParam('mobile','');
		$customer_info=$this->getUserMessage();

		if(empty($mobile)||empty($customer_info)){
			throw new CHttpException(400, '用户手机号和用户栏数组不能为空');
		}

		$arr=SpringUserInfo::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
		$this->render('/v2016/spring/grourdgetblessing',array(
			'blessing'=>$arr['blessing'],
			'from_customer'=>$arr['customer_id'],
			'customer_info'=>$customer_info
		));
	}

	/**
	 * 葫芦吸福气的ajax,普通用户每天可以吸5次，彩富用户可以吸8次
	 * @param $from_customer 被吸取福气的用户id
	 * $blessing 被吸福气用户福气值
	 */
	public function actionAjaxGrourdGetBlessing(){
		$userID = $this->getUserId();
		$from_customer=Yii::app()->request->getParam('from_customer','');
		$start_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$end_time=mktime(23, 59, 59, date('m'), date('d'), date('Y'));

		if(empty($from_customer)){
			throw new CHttpException(400, 'from_customer不能为空');
		}

		//用户吸取过福气弹框关闭后再次吸取时给用户提示
		$flag=SpringGrourdRecord::model()->find("customer_id=:customer_id and from_customer_id=:from_customer_id and createtime between :start_time and :end_time",array(":customer_id"=>$userID,":from_customer_id"=>$from_customer,":start_time"=>$start_time,":end_time"=>$end_time));
		if($flag){
			$this->output('', 2, '您已经吸取过他的福气值，明天再来吧！');
		}

		$transaction = Yii::app()->db->beginTransaction();
		try{
			$isProfitUser=$this->judgeUser($userID);
			$sql = "select count(1) as number from spring_grourd_record where customer_id=$userID and createtime BETWEEN $start_time and $end_time GROUP BY customer_id";
			$number = Yii::app()->db->createCommand($sql)->queryAll();
			if(empty($number)){
				//此用户没有吸过福气
				$data = array(
					'customer_id'       => $userID,
					'from_customer_id'   => $from_customer,
					'createtime'=>time()
				);
				$res1 = Yii::app()->db->createCommand()->insert('spring_grourd_record', $data);

				$blessing=SpringUserInfo::model()->find("customer_id=:customer_id",array(":customer_id"=>$userID));
				$new_blessing=$blessing['blessing']+1;
				$res2 = SpringUserInfo::model()->updateAll(array('blessing'=>$new_blessing),'customer_id=:customer_id',array(':customer_id'=>$userID));

				$res3=$this->addMessage($userID,'吸走您1点福气值',$from_customer);
				$res33=$this->addMessage($from_customer,'被您吸走1点福气值',$userID);

				$blessing1=SpringUserInfo::model()->find("customer_id=:customer_id",array(":customer_id"=>$from_customer));
				$new_blessing1=$blessing1['blessing']-1;
				$res4 = SpringUserInfo::model()->updateAll(array('blessing'=>$new_blessing1),'customer_id=:from_customer',array(':from_customer'=>$from_customer));

				if($res1 && $res2>0 && $res3 && $res33 && $res4 && $blessing1['blessing']>0 && !$flag){
					$transaction->commit();
					$this->output($new_blessing1, 1, '恭喜你，已吸取到1点福气值');
				}else{
					$transaction->rollback();
					$this->output('', 0, '吸取福气失败1');
				}
			}else{
				if($isProfitUser==1){
					//彩富用户
					if($number[0]['number']>=8){
						//今天的吸福气次数已用完
						$this->output('', 3, '您今天吸取次数已用完，明天再来吧！');
					}else{
						$data = array(
							'customer_id'       => $userID,
							'from_customer_id'   => $from_customer,
							'createtime'=>time()
						);
						$res1 = Yii::app()->db->createCommand()->insert('spring_grourd_record', $data);

						$blessing=SpringUserInfo::model()->find("customer_id=:customer_id",array(":customer_id"=>$userID));
						$new_blessing=$blessing['blessing']+1;
						$res2 = SpringUserInfo::model()->updateAll(array('blessing'=>$new_blessing),'customer_id=:customer_id',array(':customer_id'=>$userID));

						$res3=$this->addMessage($userID,'吸走您1点福气值',$from_customer);
						$res33=$this->addMessage($from_customer,'被您吸走1点福气值',$userID);

						$blessing1=SpringUserInfo::model()->find("customer_id=:customer_id",array(":customer_id"=>$from_customer));
						$new_blessing1=$blessing1['blessing']-1;
						$res4 = SpringUserInfo::model()->updateAll(array('blessing'=>$new_blessing1),'customer_id=:from_customer',array(':from_customer'=>$from_customer));

						if($res1 && $res2>0 && $res3 && $res33 && $res4 && $blessing1['blessing']>0 && !$flag){
							$transaction->commit();
							$this->output($new_blessing1, 1, '恭喜你，已吸取到1点福气值');
						}else{
							$transaction->rollback();
							$this->output('', 0, '吸取福气失败2');
						}
					}
				}else{
					//普通用户
					if($number[0]['number']>=5){
						//今天的吸福气次数已用完
						$this->output('', 3, '您今天吸取次数已用完，明天再来吧！');
					}else{
						$data = array(
							'customer_id'       => $userID,
							'from_customer_id'   => $from_customer,
							'createtime'=>time()
						);
						$res1 = Yii::app()->db->createCommand()->insert('spring_grourd_record', $data);

						$blessing=SpringUserInfo::model()->find("customer_id=:customer_id",array(":customer_id"=>$userID));
						$new_blessing=$blessing['blessing']+1;
						$res2 = SpringUserInfo::model()->updateAll(array('blessing'=>$new_blessing),'customer_id=:customer_id',array(':customer_id'=>$userID));
						$res3=$this->addMessage($userID,'吸走您1点福气值',$from_customer);
						$res33=$this->addMessage($from_customer,'被您吸走1点福气值',$userID);

						$blessing1=SpringUserInfo::model()->find("customer_id=:customer_id",array(":customer_id"=>$from_customer));
						$new_blessing1=$blessing1['blessing']-1;
						$res4 = SpringUserInfo::model()->updateAll(array('blessing'=>$new_blessing1),'customer_id=:from_customer',array(':from_customer'=>$from_customer));

						if($res1 && $res2>0 && $res3 && $res33 && $res4 && $blessing1['blessing']>0 && !$flag){
							$transaction->commit();
							$this->output($new_blessing1, 1, '恭喜你，已吸取到1点福气值');
						}else{
							$transaction->rollback();
							$this->output('', 0, '吸取福气失败3');
						}
					}
				}
			}
		}catch (Exception $e){
			$transaction->rollback();
			$this->output('', 0, 'catch捕捉到错误');
		}
	}

	/**
	 * 聚宝盆页面
	 */

	public function actionCornucopia(){
		$customer_info=$this->getUserMessage();
		$this->render('/v2016/spring/cornucopia',array(
			'customer_info'=>$customer_info
		));
	}

	/**
	 * 聚宝盆收取福气的ajax,普通用户每天凌晨自动生成3点福气，彩富用户每天凌晨自动生成11点福气,当天福气当天领取
	 */
	public function actionAjaxCornucopiaGetBlessing(){
		$userID = $this->getUserId();
		$start_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$end_time=mktime(23, 59, 59, date('m'), date('d'), date('Y'));
		//用户收取过福气，弹框关闭后再次收取时给用户提示
		$flag=SpringOperationRecord::model()->find("customer_id=:customer_id and type=:type and createtime between :start_time and :end_time",array(":customer_id"=>$userID,":type"=>1,":start_time"=>$start_time,":end_time"=>$end_time));
		if($flag){
			$this->output('', 2, '您今天已经收取过福气了，明天再来吧！');
		}else{

			$transaction = Yii::app()->db->beginTransaction();
			try{
				$isProfitUser=$this->judgeUser($userID);
				if($isProfitUser==1){
					//彩富用户
						$data = array(
							'customer_id'       => $userID,
							'type'   =>1,
							'createtime'=>time()
						);
						$res1 = Yii::app()->db->createCommand()->insert('spring_operation_record', $data);

						$blessing=SpringUserInfo::model()->find("customer_id=:customer_id",array(":customer_id"=>$userID));
						$new_blessing=$blessing['blessing']+11;
						$res2 = SpringUserInfo::model()->updateAll(array('blessing'=>$new_blessing),'customer_id=:customer_id',array(':customer_id'=>$userID));

					    $res3=$this->addMessage($userID,'在聚宝盆页面成功收取11点福气值');

						if($res1 && $res2>0 && $res3 && !$flag){
							$transaction->commit();
							$this->output('', 3, '恭喜你，已收取到11点福气值');
						}else{
							$transaction->rollback();
							$this->output('', 0, '收取福气失败');
						}
				}else{
					//普通用户
					$data = array(
						'customer_id'       => $userID,
						'type'   =>1,
						'createtime'=>time()
					);
						$res1 = Yii::app()->db->createCommand()->insert('spring_operation_record', $data);

						$blessing=SpringUserInfo::model()->find("customer_id=:customer_id",array(":customer_id"=>$userID));
						$new_blessing=$blessing['blessing']+3;
						$res2 = SpringUserInfo::model()->updateAll(array('blessing'=>$new_blessing),'customer_id=:customer_id',array(':customer_id'=>$userID));

					   $res3=$this->addMessage($userID,'在聚宝盆页面成功收取3点福气值');

						if($res1 && $res2>0 && $res3 && !$flag){
							$transaction->commit();
							$this->output('', 4, '恭喜你，已收取到3点福气值');
						}else{
							$transaction->rollback();
							$this->output('', 0, '收取福气失败2');
						}
				}

			}catch (Exception $e){
				$transaction->rollback();
				$this->output('', 0, 'catch捕捉到错误');
			}
		}
	}

	/**
	 * 首页点击打扫按钮弹出打扫页面调用的ajax
	 * $dayNum  连续打扫天数
	 * $date_list 用户打扫过的日期
	 */
	public function actionAjaxSweep(){
		$userID = $this->getUserId();
		$status=0;
		$start_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$end_time=mktime(23, 59, 59, date('m'), date('d'), date('Y'));

		$sweep_data = SpringOperationRecord::model()->findAll(array(
			'select'=>array('createtime'),
			'order' => 'createtime DESC',
			'condition' => 'customer_id=:customer_id and type=:type',
			'params' => array(':customer_id'=>$userID,':type'=>2),
		));
//		var_dump(
//			((strtotime(date('Y-m-d',time()))-strtotime(date('Y-m-d',$sweep_data[0]['createtime'])))/86400)
//			);exit;

		if(!empty($sweep_data)){
			//是否两天连续未打扫
			if(count($sweep_data)>=1){
				if(((strtotime(date('Y-m-d',time()))-strtotime(date('Y-m-d',$sweep_data[0]['createtime'])))/86400)>=3){
					$status=1;
				}
			}
		}

		$res=SpringUserMessage::model()->find("customer_id=:customer_id and message=:message and createtime between :start_time and :end_time",array(":customer_id"=>$userID,":message"=>'您已经有两天没有打扫卫生了，已减去1点福气值，下次注意哦！',":start_time"=>$start_time,":end_time"=>$end_time));
		//var_dump($res);exit;

		if($status==1 && !$res){
			$transaction = Yii::app()->db->beginTransaction();
			try{
				$blessing=SpringUserInfo::model()->find("customer_id=:customer_id",array(":customer_id"=>$userID));
				if($blessing['blessing']>0){
					$new_blessing=$blessing['blessing']-1;
					$res2 = SpringUserInfo::model()->updateAll(array('blessing'=>$new_blessing),'customer_id=:customer_id',array(':customer_id'=>$userID));
				}else{
					$res2=1;
				}

				$res3=$this->addMessage($userID,'您已经有两天没有打扫卫生了，已减去1点福气值，下次注意哦！');

				if($res2>0 && $res3 && $blessing['blessing']>0){
					$transaction->commit();
					$this->output('', 1, '由于您两天没有打扫，福气值-1');
				}else{
					$transaction->rollback();
					$this->output('', 0, '扣除福气值失败');
				}

			}catch (Exception $e){
				$transaction->rollback();
				$this->output('', 0, 'catch捕捉到错误');
			}
		}

		$this->output('', 2, '不会扣除福气值');
	}

	/**
	 * 打扫页面中的打扫按钮调用的ajax
	 * $result 为空则表示用户没有打扫过
	 */
	public function actionAjaxSweepGetBlessing(){
		$userID = $this->getUserId();
		$start_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$end_time=mktime(23, 59, 59, date('m'), date('d'), date('Y'));

		//用户今天已打扫过卫生
		$flag=SpringOperationRecord::model()->find("customer_id=:customer_id and type=:type and createtime between :start_time and :end_time",array(":customer_id"=>$userID,":type"=>2,":start_time"=>$start_time,":end_time"=>$end_time));
		if($flag){
			$this->output('', 2, '您今天已经打扫过卫生了，明天再来吧！');
		}else{

			$transaction = Yii::app()->db->beginTransaction();
			try{
					$data = array(
						'customer_id'       => $userID,
						'type'   =>2,
						'createtime'=>time()
					);
					$res1 = Yii::app()->db->createCommand()->insert('spring_operation_record', $data);

					$blessing=SpringUserInfo::model()->find("customer_id=:customer_id",array(":customer_id"=>$userID));
					$new_blessing=$blessing['blessing']+1;
					$res2 = SpringUserInfo::model()->updateAll(array('blessing'=>$new_blessing),'customer_id=:customer_id',array(':customer_id'=>$userID));

					$res3=$this->addMessage($userID,'成功打扫卫生，已获得1点福气值');

					if($res1 && $res2>0 && $res3 && !$flag){
						$transaction->commit();
						//获取连续天数
						$result=$this->getQiandao($userID);
						$this->output($result, 1, '福气值+1”“请继续再接再厉哦～');
					}else{
						$transaction->rollback();
						$this->output('', 0, '成功打扫卫生失败');
					}

			}catch (Exception $e){
				$transaction->rollback();
				$this->output('', 0, 'catch捕捉到错误');
			}
		}
	}



	/*
    * @version 获取连续的天数
    * @param array $day_list
    * return int $continue_day 连续天数
    */
	private function getDays($day_list){
		$continue_day = 0 ;//连续天数
		if(count($day_list) >= 1){
			for ($i=1; $i<=count($day_list); $i++){
				if( ( abs(( strtotime(date('Y-m-d')) - strtotime($day_list[$i-1]) ) / 86400)) == $i ){
					$continue_day = $i;
				}else{
					break;
				}
			}
		}
		return $continue_day;//输出连续几天
	}

	/**
	 * 添加用户操作消息记录
	 *
	 */
	private function addMessage($userID,$message,$from_customer=''){
		if(!empty($from_customer)){
			//紫金葫芦
			$customer_arr=Customer::model()->findByPk($userID);
			$message=substr_replace($customer_arr['mobile'],'****',3,4).'用户'.$message;
			$data = array(
				'customer_id'       => $from_customer,
				'message'   => $message,
				'createtime'=>time()
			);
			$res1 = Yii::app()->db->createCommand()->insert('spring_user_message', $data);
			if($res1){
				return true;
			}else{
				return false;
			}
		}else{
			//其它
			$data = array(
				'customer_id'       => $userID,
				'message'   => $message,
				'createtime'=>time()
			);
			$res1 = Yii::app()->db->createCommand()->insert('spring_user_message', $data);
			if($res1){
				return true;
			}else{
				return false;
			}
		}
	}

	/**
	 * 登录用户是否为财富用户
	 * $isProfitUser 0：普通用户 1：为财富用户
	 */
	private function judgeUser($userID){
        $isProfitUser = 0;
		$countStatus = array(
			Item::PROFIT_ORDER_SUCCESS,
			Item::PROFIT_ORDER_CONTINUOUS,
			Item::PROFIT_ORDER_EXTRACT_ING,
			Item::PROFIT_ORDER_EXTRACT_SUCCESS,
			Item::PROFIT_ORDER_EXTRACT_FAIL
		);
		// 彩富
		$profit = Yii::app()->db->createCommand()
			->select(array('*'))
			->from('property_activity')
			->where(array('in', 'status', $countStatus))
			->andWhere('customer_id=:customer_id', array(':customer_id'=>$userID))
			->queryRow();

		// 彩富
		$appreciation = Yii::app()->db->createCommand()
			->select(array('*'))
			->from('appreciation_plan')
			->where(array('in', 'status', $countStatus))
			->andWhere('customer_id=:customer_id', array(':customer_id'=>$userID))
			->queryRow();

       if( !empty($profit) || !empty($appreciation)){
		   $isProfitUser = 1;
	   }
		return $isProfitUser;
	}


  //获取用户信息
	private function getUserMessage($customer_id=''){
		if(empty($customer_id)){
			$userID = $this->getUserId();
		}else{
			$userID=$customer_id;
		}

		$customer_arr=Customer::model()->findByPk($userID);
		$customer_user=SpringUserInfo::model()->find('customer_id=:customer_id',array(':customer_id'=>$userID));
		$customer_info=array(
			'mobile'=>$customer_arr['mobile'],
			'avatar'=>$customer_arr->getPortraitUrl(),
			'zodiac'=>$customer_user['zodiac']
		);
		return $customer_info;
	}

	/**
	 * 首页判断是否有小红点
	 * 1：无  0：有
	 */
	private function showRedDot($userID){
		$hongdian=array('cornucopia'=>0,'sweep'=>0,'fire'=>0);
		$start_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$end_time=mktime(23, 59, 59, date('m'), date('d'), date('Y'));
		$arr=SpringOperationRecord::model()->findAll("customer_id=:customer_id  and createtime between :start_time and :end_time",array(":customer_id"=>$userID,":start_time"=>$start_time,":end_time"=>$end_time));

		//打扫和聚宝盆
		if(empty($arr)){
			//有红点提示
			$hongdian['cornucopia']=0;
			$hongdian['sweep']=0;
		}else{
			foreach ($arr as $reddot_value){
				if($reddot_value['type']==1){
					//无红点提示
					$hongdian['cornucopia']=1;
				}else if($reddot_value['type']==2){
					$hongdian['sweep']=1;
				}
			}
		}
		//鞭炮，只要鞭炮数量不为0，则红点提示
		$customer_user=SpringUserInfo::model()->find('customer_id=:customer_id',array(':customer_id'=>$userID));
		if(!empty($customer_user)){
			if($customer_user['firenum']>0){
				//有鞭炮未燃放，红点提示
				$hongdian['fire']=0;
			}else{
				$hongdian['fire']=1;
			}
		}else{
			throw new CHttpException(400, '用户数组不能为空');
		}

		return $hongdian;
	}

	/**
	 * 获取打扫页连续天数和签到
	 * $result  数组
	 */
	private function getQiandao($userID){
        //实时修改连续的天数
		$day_list=array();
		$dayNum=0;
		$date_list=array();
		$result=array();
		//测试
		//$list=array('1.14'=>0,'1.15'=>0,'1.16'=>0,'1.17'=>0,'1.18'=>0,'1.19'=>0,'1.20'=>0,'1.21'=>0,'1.22'=>0,'1.23'=>0,'1.24'=>0,'1.25'=>0,'1.26'=>0,'1.27'=>0,'1.28'=>0,'1.29'=>0,'1.30'=>0,'1.31'=>0,'2.01'=>0,'2.02'=>0,'2.03'=>0,'2.04'=>0,'2.05'=>0,'2.06'=>0,'2.07'=>0,'2.08'=>0,'2.09'=>0,'2.10'=>0);
		//正式
		$list=array('1.26'=>0,'1.27'=>0,'1.28'=>0,'1.29'=>0,'1.30'=>0,'1.31'=>0,'2.01'=>0,'2.02'=>0,'2.03'=>0,'2.04'=>0,'2.05'=>0,'2.06'=>0,'2.07'=>0,'2.08'=>0,'2.09'=>0,'2.10'=>0);
		$sweep_data = SpringOperationRecord::model()->findAll(array(
			'select'=>array('createtime'),
			'order' => 'createtime DESC',
			'condition' => 'customer_id=:customer_id and type=:type',
			'params' => array(':customer_id'=>$userID,':type'=>2),
		));
		if(!empty($sweep_data)) {
			foreach ($sweep_data as $vo_sweep) {
				if (date('Y-m-d', $vo_sweep['createtime']) != date('Y-m-d', time())) {
					$day_list[] = date('Y-m-d', $vo_sweep['createtime']);
				} else {
					$dayNum = $dayNum + 1;
				}
				$date_list[] = date('n.d', $vo_sweep['createtime']);
			}
			$num = $this->getDays($day_list);
			$dayNum = $dayNum + $num;
		}

		if(!empty($date_list)){
			foreach ($date_list as $date_list_value){
				foreach ($list as $list_key=>$value){
					if($date_list_value==$list_key){
						$list[$list_key]=1;
					}
			    }
			}
		}
        //var_dump($date_list);exit;
		$result['dayNum']=$dayNum;
		$result['date_list']=$list;
		$result['time']=date('n.d',time());
		return $result;
	}

	/*
    * @versino 获取所有省
    * @coptyright(c) 2015.04.30 josen
    * @return json
    */
	public function actionGetProvince(){
		$array=PinTuan::model()->getProvince();
		echo urldecode(json_encode($array));
	}
	/*
     * @versino 获取市
     * @coptyright(c) 2015.04.30 josen
     * @return json
     */
	public function actionGetCity(){
		$id = intval(Yii::app()->request->getParam('provice_id'));
		$array=PinTuan::model()->getCity($id);
		echo urldecode(json_encode($array));
	}
	/*
     * @versino 获取县/区
     * @coptyright(c) 2015.04.30 josen
     * @return json
     */
	public function actionGetCounty(){
		$id = intval(Yii::app()->request->getParam('city_id'));
		$array=PinTuan::model()->getCounty($id);
		echo urldecode(json_encode($array));
	}
	/*
     * @versino 获取镇
     * @coptyright(c) 2015.04.30 josen
     * @return json
     */
	public function actionGetTown(){
		$id = intval(Yii::app()->request->getParam('county_id'));
		$array=PinTuan::model()->GetTown($id);
		echo urldecode(json_encode($array));
	}

	//返回结果
	public function output($data, $code=1, $msg='请求成功')
	{
		$result = array(
			'retCode' => $code,
			'retMsg' => $msg,
			'data' => $data
		);

		echo CJSON::encode($result); exit;
	}

	public function actionFirecrackerTip()
	{
		$userID = $this->getUserId();
		$times = $this->firecrackerTimes($userID);

		$sql = 'SELECT `firenum` FROM `spring_user_info` WHERE `customer_id`=:user_id';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':user_id', $userID, PDO::PARAM_INT);
		$number = $command->queryScalar();

		$result = array(
			'times' => $times,
			'number' => $number
		);

		$this->output($result);
	}

	/**
	 * 燃放鞭炮
	 */
	public function actionDoFirecracker()
	{
		$userID = $this->getUserId();

		$sql = 'select `firenum`, `blessing` from `spring_user_info` where `customer_id`=:user_id';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':user_id', $userID, PDO::PARAM_INT);
		$userMsg = $command->queryRow();
		$number = $userMsg['firenum'];
		if ($number < 1)
			$this->output('', 0, '没有鞭炮了');

		$times = $this->firecrackerTimes($userID);

		$updateData = array(
				'firenum' => new CDbExpression('firenum-1'),
				'blessing' =>  new CDbExpression('blessing+1'),
		);

		$saveData = array(
			'customer_id' => $userID,
			'message' => '',
			'createtime' => time()
		);

		$saveRecord= array(
			'customer_id' => $userID,
			'type' =>3,
			'createtime' => time()
		);

		$doFirecrackerSql = 'SELECT DISTINCT FROM_UNIXTIME(`createtime`, \'%Y%m%d\') AS `date`  FROM `spring_operation_record` WHERE `type`=3 AND `customer_id`=:user_id ORDER BY `id` DESC LIMIT 1';
		$command = Yii::app()->db->createCommand($doFirecrackerSql);
		$command->bindParam(':user_id', $userID, PDO::PARAM_INT);
		$firecrackerDate = $command->queryScalar();
		$todayIsFirecracker = date('Ymd') == $firecrackerDate ? 1 : 0;
		
		if ($times >= 2) {
			$saveData['message'] = '连续'.($todayIsFirecracker ? $times : $times + 1).'天燃放鞭炮造成大气污染，已扣除1点福气值';
			$updateData['blessing'] = (0 == $userMsg['blessing']) ? 0 : new CDbExpression('blessing-1');
		}
		else {
			$saveData['message'] = '成功燃放鞭炮，获得1点福气值';
		}
		

		$connection = Yii::app()->db;

		$transaction = $connection->beginTransaction();
		$command = $connection->createCommand();
		try {
			$user_info=$command->update('spring_user_info', $updateData, 'customer_id='.$userID);
			if (0 == $userMsg['blessing']) {
				$user_message = 1;
			}
			else {
				$user_message=$command->insert('spring_user_message', $saveData);
			}

			$operation_record=$command->insert('spring_operation_record', $saveRecord);
			
			if($user_info && $user_message && $operation_record){
				$transaction->commit();
				$result = array(
					'number' => $number - 1,
					'times'	=> $todayIsFirecracker ? $times : $times + 1
				);
				$this->output($result);
			}else{
				$transaction->rollback();
				$this->output('', 0, '燃放鞭炮失败');
			}

		}
		catch (Exception $e) {
			$transaction->rollback();
			$this->output('', 0, '燃放鞭炮失败');
		}
	}

	/**
	 * 连续放鞭炮天数
	 * @param $userID
	 * @return int
	 */
	public function firecrackerTimes($userID)
	{
		$times = 0;

		$sql = 'SELECT DISTINCT FROM_UNIXTIME(`createtime`, \'%Y%m%d\') AS `date`  FROM `spring_operation_record` WHERE `type`=3 AND `customer_id`=:user_id';

		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':user_id', $userID, PDO::PARAM_INT);
		$result = $command->queryColumn();

		if (empty($result))
			return $times;

		rsort($result);
		$date = date('Ymd');

		if ($date - $result[0] > 1)
			return $times;

		foreach ($result as $key => $val) {
			if ((isset($result[$key+1])) && $val - $result[$key+1] != 1) {
				$times += 1;
				break;
			}
			else {
				$times += 1;
			}
		}

		return $times;
	}
}