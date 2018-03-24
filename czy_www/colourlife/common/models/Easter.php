<?php
/*
 * @version 复活节model
 */
class Easter extends CActiveRecord{
    
    //奖项配置
    private $prize_arr=array(
        '0' => array('id'=>1,'prize_name'=>'无奖','v'=>20), 
        '1' => array('id'=>2,'prize_name'=>'游戏机会1次','v'=>27.5), 
        '2' => array('id'=>3,'code'=>100000078,'prize_name'=>'星晨旅游邮轮代金券200元','v'=>20), 
        '3' => array('id'=>4,'prize_name'=>'0.08饭票','money'=>0.08,'v'=>20), 
        '4' => array('id'=>5,'prize_name'=>'0.8饭票','money'=>0.8,'v'=>10),
    	'5' => array('id'=>6,'prize_name'=>'8饭票','money'=>8,'v'=>2),
    	'6' => array('id'=>7,'prize_name'=>'彩之云定制卡片U盘','v'=>0.3),
        '7' => array('id'=>8,'prize_name'=>'定制充电宝','v'=>0.2),
    );
	
    
    public static function model($className = __CLASS__){
        return parent::model($className);
    }

    /**
     * 获取总机会
     */
    public function getChances($customer_id,$type){
    	if(empty($customer_id)||empty($type)){
    		return false;
    	}
    	$total=0;
    	$use_num=0;
    	$all_num=0;
    	if ($type==1){ //挑战机会
    		$model=EasterChallenge::model();
    	}elseif ($type==2){  //砸蛋机会
    		$model=EasterSmashedEgg::model();
    	}
    	$chance = $model->findAll ( "customer_id=:customer_id", array (
				':customer_id' => $customer_id
		) );
    	if (!empty($chance)){
    		foreach ($chance as $val){
    			if ($val->type==1){  //获得的
    				$total+=$val->times;
    			}elseif ($val->type==2){  //使用的
    				$use_num+=$val->times;
    			}
    		}
    		$all_num=$total-$use_num;
    	}
    	return $all_num;
    }
    
    /**
     * 是否显示提示
     */
    public function isShowTips($customer_id){
    	if (empty($customer_id)){
    		return false;
    	}
    	$show=false;
    	$tips=TipsShowSetting::model()->find("customer_id=:customer_id",array(':customer_id'=>$customer_id));
    	if (empty($tips)){
    		$tips=new TipsShowSetting();
    		$tips->customer_id=$customer_id;
    		$tips->is_show=1;
    		$tips->type=1;
    		$tips->create_time=time();
    		$tips->save();
    	}else {
    		$show=true;
    	}
    	return $show;
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
    
    /*
     * @version 记录
    * @param int $customer_id
    * @param int $type
    * return boolean
    */
    public function addLog($customer_id,$type)
    {
    	$Elog =new EasterLog();
    	$Elog->customer_id=$customer_id;
    	$Elog->type=$type;
    	$Elog->create_time=time();
    	$result = $Elog->save();
    	if($result){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    /**
     * app里抽奖逻辑
     */
    public function draw($customer_id,$mobile){
    	if (empty($customer_id)||empty($mobile)){
    		return false;
    	}
    	//抽奖
    	$rid=$this->is_lottery();
    	//砸蛋机会减1
    	$smashedEgg=new EasterSmashedEgg();
    	$smashedEgg->customer_id=$customer_id;
    	$smashedEgg->times=1;
    	$smashedEgg->type=2;
    	$smashedEgg->create_time=time();
    	$se_result=$smashedEgg->save();
    	if (!$se_result){
    		return false;
    	}
    	if ($rid==1){  //没中奖直接返回
    		return $rid;
    	}
    	$prize_arr=$this->prize_arr[$rid-1];
    	$transaction = Yii::app()->db->beginTransaction();
    	//中奖记录
    	$userPrize=new EasterUserPrize();
    	$userPrize->customer_id=$customer_id;
    	$userPrize->mobile=0;
    	$userPrize->prize_id=$rid;
    	$userPrize->prize_name=$prize_arr['prize_name'];
    	$userPrize->is_new=0;
    	$userPrize->create_time=time();
    	$up_result=$userPrize->save();
    	if ($rid==2){  //获得游戏机会
    		$challenge=new EasterChallenge();
    		$challenge->customer_id=$customer_id;
    		$challenge->times=1;
    		$challenge->type=1;
    		$challenge->challenge_time=0;
    		$challenge->way=3;
    		$challenge->create_time=time();
    		$c_result=$challenge->save();
    		if ($up_result&&$c_result){
    			$transaction->commit();
    			return $rid;
    		}else {
    			$transaction->rollback();
    			return false;
    		}
    	}elseif ($rid==3){ //星辰旅游券
    		$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$prize_arr['code'],':mobile'=>$mobile));
    		if(empty($userCouponsArr)){
    			$uc_model=new UserCoupons();
    			$uc_model->mobile=$mobile;
    			$uc_model->you_hui_quan_id=$prize_arr['code'];
    			$uc_model->create_time=time();
    			$result=$uc_model->save();
    			if ($up_result&&$result){
    				$transaction->commit();
    				return $rid;
    			}else {
    				$transaction->rollback();
    				return false;
    			}
    		}else {
    			if ($up_result){
    				$transaction->commit();
    				return $rid;
    			}else {
    				$transaction->rollback();
    				return false;
    			}
    		}
    		
    	}elseif ($rid>=4&&$rid<=6){  //中了饭票，发饭票
    		$items = array(
    				'customer_id' => $customer_id,//用户的ID
    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
    				'sum' =>$prize_arr['money'],//红包金额,
    				'sn' => 15,
    		);
    		$redPacked = new RedPacket();
    		$ret=$redPacked->addRedPacker($items);
    		if ($up_result&&$ret){  //成功
    			$transaction->commit();
    			return $rid;
    		}else {
    			$transaction->rollback();
    			return false;
    		}
    		
    	}elseif ($rid==7||$rid==8){  //获取实物
    		if ($up_result){
    			$transaction->commit();
    			return $rid;
    		}else {
    			$transaction->rollback();
    			return false;
    		}    	
    	}
    }
    
    /**
     * 分享抽奖
     */
    public function shareLottery(){
    	//抽奖
    	$rid=$this->is_lottery();
    	return $rid;
    }
    /**
     * 分享外的领取奖品逻辑,只有实物和饭票才入库
     */
    public function shareReceive($mobile,$rid){
    	if (empty($mobile)||empty($rid)){
    		return array('msg'=>'参数错误！');
    	}
    	$start_time=mktime(0,0,0);
    	$end_time=time();
    	//判断当天是否已中过奖
    	$prize = EasterUserPrize::model ()->find ( "mobile=:mobile and create_time>=:start_time and create_time<=:end_time", array (
    			':mobile' => $mobile,
    			':start_time' => $start_time,
    			':end_time' => $end_time
    	) );
    	if (!empty($prize)){
    		return array('status'=>2,'pname'=>$prize->prize_name);
    	}
    	//抽奖
    	$is_check=$this->is_check($rid);    //检查是否达到要求
    	if ($is_check){
    		return array('msg'=>'抱歉，该奖品已经被领完！');
    	}
    	$prize_arr=$this->prize_arr[$rid-1];
    	$customer=Customer::model()->find("mobile=:mobile and state=0 and is_deleted=0",array(':mobile'=>$mobile));
    	//未注册用户自动注册，默认密码为‘Czy123456’
    	if (empty($customer)){ //自动注册
    		$new_customer=1;
    		$cust_id=0;
    	}else {
    		$cust_id=$customer->id;
    		$new_customer=0;
    	}
    	$transaction = Yii::app()->db->beginTransaction();
    	//中奖记录
    	$userPrize=new EasterUserPrize();
    	$userPrize->customer_id=0;
    	$userPrize->mobile=$mobile;
    	$userPrize->prize_id=$rid;
    	$userPrize->prize_name=$prize_arr['prize_name'];
    	$userPrize->is_new=$new_customer;
    	$userPrize->create_time=time();
    	$up_result=$userPrize->save();
    	//自动注册
    	if (!empty($new_customer)){
    		$ip=0;
    		if (isset($_SERVER['REMOTE_ADDR'])&&!empty($_SERVER['REMOTE_ADDR'])){
    			$ip=$_SERVER['REMOTE_ADDR'];
    		}
    		$salt = F::random(8);
    		$cust_model=new Customer();
    		$cust_model->username=Item::User_Prefix.$mobile;
    		$cust_model->password='Czy123456';
    		$cust_model->salt=$salt;
    		$cust_model->name='访客';
    		$cust_model->mobile=$mobile;
    		$cust_model->community_id=585;
    		$cust_model->build_id=10421;
    		$cust_model->room=1;
    		$cust_model->create_time=time();
    		$cust_model->last_time=time()+rand(100, 86400);
    		$cust_model->last_ip=$ip;
    		$cust_model->reg_type=rand(0, 2);
    		$cust_model->customer_code=$this->getInviteCode();
    		$cust_result=$cust_model->save();
    		if ($cust_result){
    			$cust_id=$cust_model->attributes['id'];
    			$msg='恭喜你获得'.$prize_arr['prize_name'].'，赶紧登陆彩之云去看看吧！账号：'.$mobile.' ，密码：Czy123456 下载链接：http://dwz.cn/2Xb78l';
    			//发短信通知
    			$sms = Yii::app()->sms;
    			$sms->setType('easter', array('mobile' => $mobile));
    	
    			if (false == $sms->sendMsg($msg)) {
    				Yii::log("短信发送失败[{$mobile}:{$sms->error}]",CLogger::LEVEL_ERROR,'colourlife.core.easter.register');
    			}else {
    				Yii::log("短信发送成功[{$mobile}]",CLogger::LEVEL_INFO,'colourlife.core.easter.register');
    			}
    		}
    	
    	}
    	if (!$up_result||empty($cust_id)){
    		$transaction->rollback();
    		return array('msg'=>'领取失败！');
    	}
    	//判断中什么奖
    	if ($rid==3){ //星辰旅游券
    		$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$prize_arr['code'],':mobile'=>$mobile));
    		if(empty($userCouponsArr)){
    			$uc_model=new UserCoupons();
    			$uc_model->mobile=$mobile;
    			$uc_model->you_hui_quan_id=$prize_arr['code'];
    			$uc_model->create_time=time();
    			$result=$uc_model->save();
    			if ($result){
    				$transaction->commit();
    				return array('status'=>1,'msg'=>'领取成功！');
    			}else {
    				$transaction->rollback();
    				return array('msg'=>'领取失败！');
    			}
    		}else {
    			$transaction->commit();
    			return array('status'=>1,'msg'=>'领取成功！');
    		}
    	
    	}elseif ($rid>=4&&$rid<=6){  //中了饭票，发饭票
    		$items = array(
    				'customer_id' => $cust_id,//用户的ID
    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
    				'sum' =>$prize_arr['money'],//红包金额,
    				'sn' => 15,
    		);
    		$redPacked = new RedPacket();
    		$ret=$redPacked->addRedPacker($items);
    		if ($ret){  //成功
    			$transaction->commit();
    			return array('status'=>1,'msg'=>'领取成功！');
    		}else {
    			$transaction->rollback();
    			return array('msg'=>'领取失败！');
    		}
    	
    	}elseif ($rid==7||$rid==8){  //获取实物
    		$transaction->commit();
    		return array('status'=>1,'msg'=>'领取成功！');
    	}	
    	
    }
   
    /**
     * 生成随机不重复邀请码
     * @return string
     */
    private function getInviteCode(){
    	$invitecode = '';
    	$flag = true;
    	$i=1;
    	while ($flag && $i<=100) {
    		$code = F::random(5);
    		$code = strtoupper($code);
    		$count = InvitationCode::model()->find('code=:code', array(':code' => $code));
    		if($count && $i!=100){
    			$i++;
    			continue;
    		}
    
    		if($count && $i==100){
    			$sms = Yii::app()->sms;
    			$sms->name = '亲爱的管理员';
    			$sms->setType('invitecodeNotice', array('mobile' => '13066839936')); //18998945813
    			$sms->sendUserMessage('invitecodeNoticeTemplate');
    			Yii::log("邀请码生成错误", CLogger::LEVEL_INFO, 'colourlife.api.customer.invitecode');
    			$invitecode='#####';
    			break;
    
    		}
    
    		$invitecode = $code;
    		$flag = false;
    
    	}
    	$invitationcode = new InvitationCode();
    	$invitationcode->code = $invitecode;
    	$invitationcode->save();
    	return $invitecode;
    }
    
    /*
     * @version 抽奖逻辑，判断每个奖品是否已达到给定量
     * (星辰:100/天，0.08饭票:1000/天，0.8饭票:100/天，8饭票:10/天，u盘:4/天，移动电源:2/天)
    * return array
    */
    public function is_lottery(){
    	$start_time=mktime(0,0,0);
    	$end_time=time();
    	$xingchen=EasterUserPrize::model()->count('prize_id=:prize_id and create_time>=:start_time and create_time<=:end_time', array(':prize_id'=>3,':start_time'=>$start_time,':end_time'=>$end_time));  //星辰旅游券
    	$tickect1=EasterUserPrize::model()->count('prize_id=:prize_id and create_time>=:start_time and create_time<=:end_time', array(':prize_id'=>4,':start_time'=>$start_time,':end_time'=>$end_time));  //0.08元饭票
    	$tickect2=EasterUserPrize::model()->count('prize_id=:prize_id and create_time>=:start_time and create_time<=:end_time', array(':prize_id'=>5,':start_time'=>$start_time,':end_time'=>$end_time));  //0.8元饭票
    	$tickect3=EasterUserPrize::model()->count('prize_id=:prize_id and create_time>=:start_time and create_time<=:end_time', array(':prize_id'=>6,':start_time'=>$start_time,':end_time'=>$end_time));  //8元饭票
    	$upan=EasterUserPrize::model()->count('prize_id=:prize_id and create_time>=:start_time and create_time<=:end_time', array(':prize_id'=>7,':start_time'=>$start_time,':end_time'=>$end_time));  //u盘
    	$portable_source=EasterUserPrize::model()->count('prize_id=:prize_id and create_time>=:start_time and create_time<=:end_time', array(':prize_id'=>8,':start_time'=>$start_time,':end_time'=>$end_time));  //移动电源
    	if($xingchen>=100){
    		unset($this->prize_arr[2]);
    	}
    	if($tickect1>=1000){
    		unset($this->prize_arr[3]);
    	}
    	if($tickect2>=100){
    		unset($this->prize_arr[4]);
    	}
    	if($tickect3>=10){
    		unset($this->prize_arr[5]);
    	}
    	if($upan>=4){
    		unset($this->prize_arr[6]);
    	}
    	if($portable_source>=2){
    		unset($this->prize_arr[7]);
    	}
    	foreach ($this->prize_arr as $key => $val) {
    		$arr[$val['id']] = $val['v'];
    	}
    	$rid=$this->get_rand($arr);
    	return $rid;
    }
    
    /**
     * 添加机会
     */
    public function addChallengeChance($customer_id,$way,$times){
    	if (empty($customer_id)||empty($way)||empty($times)){
    		return false;
    	}
    	$start_time=mktime(0,0,0);
    	$end_time=time();
    	if ($way==1){  //系统赠送挑战机会
    		$model=EasterChallenge::model();
    		$chance = $model->find ( "type=1 and way=1 and customer_id=:customer_id and create_time>=:start_time and create_time<=:end_time", array (
    				':customer_id' => $customer_id,
    				':start_time' => $start_time,
    				':end_time' => $end_time
    		) );
    		if (!empty($chance)){
    			return false;
    		}
    	}elseif ($way==4){  //分享送挑战机会
    		$model=EasterChallenge::model();
    		$chance = $model->findAll ( "type=1 and way=4 and customer_id=:customer_id and create_time>=:start_time and create_time<=:end_time", array (
    				':customer_id' => $customer_id,
    				':start_time' => $start_time,
    				':end_time' => $end_time
    		) );
    		if (!empty($chance)&&count($chance)>=3){ //每天最多分享3次得挑战机会
    			return false;
    		}
    	}
    	$challenge=new EasterChallenge();
    	$challenge->customer_id=$customer_id;
    	$challenge->times=$times;
    	$challenge->type=1;
    	$challenge->challenge_time=0;
    	$challenge->way=$way;
    	$challenge->create_time=time();
    	if ($challenge->save()>0){
    		return true;
    	}else {
    		return false;
    	}
    }
    
    /**
     * 添加砸蛋机会
     */
    public function addSmashedEgg($customer_id,$time){
    	if (empty($customer_id)){
    		return false;
    	}
    	$transaction = Yii::app()->db->beginTransaction();
    	//添加消耗挑战机会记录
    	$challenge=new EasterChallenge();
    	$challenge->customer_id=$customer_id;
    	$challenge->times=1;
    	$challenge->type=2;
    	$challenge->challenge_time=$time;
    	$challenge->way=0;
    	$challenge->create_time=time();
    	$c_result=$challenge->save();
    	//添加砸蛋机会
    	if (!empty($time)){
    		$smashedEgg=new EasterSmashedEgg();
    		$smashedEgg->customer_id=$customer_id;
    		$smashedEgg->times=1;
    		$smashedEgg->type=1;
    		$smashedEgg->create_time=time();
    		$s_result=$smashedEgg->save();
    		if ($s_result&&$c_result){
    			$transaction->commit();
    			return true;
    		}else {
    			$transaction->rollback();
    			return false;
    		}
    	}else {
    		if ($c_result){
    			$transaction->commit();
    			return true;
    		}else {
    			$transaction->rollback();
    			return false;
    		}
    	}
    }
    
    /*
     * @version 判断每个奖品是否已达到给定量
    * (星辰:100/天，0.08饭票:1000/天，0.8饭票:100/天，8饭票:10/天，u盘:4/天，移动电源:2/天)
    * return array
    */
    public function is_check($rid){
    	if (empty($rid)){
    		return false;
    	}
    	$start_time=mktime(0,0,0);
    	$end_time=time();
    	$is_check=false;
    	$total=EasterUserPrize::model()->count('prize_id=:prize_id and create_time>=:start_time and create_time<=:end_time', array(':prize_id'=>$rid,':start_time'=>$start_time,':end_time'=>$end_time));
    	switch ($rid){
    		case 3:
    			if($total>=100){
    				$is_check=true;
    			}
    			break;
    		case 4:
    			if($total>=1000){
    				$is_check=true;
    			}
    			break;
    		case 5:
    			if($total>=100){
    				$is_check=true;
    			}
    			break;
    		case 6:
    			if($total>=10){
    				$is_check=true;
    			}
    			break;
    		case 7:
    			if($total>=4){
    				$is_check=true;
    			}
    			break;
    		case 8:
    			if($total>=2){
    				$is_check=true;
    			}
    			break;
    	}
    	return $is_check;
    }

}

