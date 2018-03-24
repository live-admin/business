<?php
/**
 * 爱就要勾搭 model
 */
class LoveHook extends CActiveRecord{
	private $_startDay='2016-05-20';//活动开始时间
	private $_endDay='2016-05-29 23:59:59';//活动结束时间
	
	//提示语
	private $_tips= array (
			'陛下！您今天的游戏机会已用完！ 邀请邻国好友注册 可以获得一次游戏机会哦！',
			'陛下！您今天的游戏机会已用完！ 参加彩富人生每天奖励一次游戏机会哦！',
			'陛下！您今天的游戏机会已用完！去彩生活特供下单，每单奖励一次游戏机会哦！' ,
			'陛下！您今天的游戏机会已用完！去京东特供下单，每单奖励一次游戏机会哦！',
	);
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 添加游戏机会
	 */
	public function addChance($userID,$type,$times){
		if (empty($userID)||empty($type)||empty($times)){
			return false;
		}
		/* $sql = " INSERT INTO `love_hook_chance` (`id`, `customer_id`, `value`, `way`, `create_time`) VALUES (null, ".$userID.", ".$times.", ".$type.", ".time()."); ";
		$result=Yii::app()->db->createCommand($sql)->execute(); */
		$chanceModel=new LoveHookChance();
		$chanceModel->customer_id=$userID;
		$chanceModel->value=$times;
		$chanceModel->way=$type;
		$chanceModel->create_time=time();
		$result=$chanceModel->save();
		if ($result){
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 添加积分
	 * @param unknown $userID
	 * @param unknown $mobile
	 * @param unknown $times
	 * @return boolean
	 */
	public function addIntegration($userID,$mobile,$times){
		if (empty($userID)||empty($mobile)||empty($times)){
			return false;
		}
		$chanceModel=new LoveHookIntegration();
		$chanceModel->customer_id=$userID;
		$chanceModel->value=$times;
		$chanceModel->mobile=$mobile;
		$chanceModel->create_time=time();
		$result=$chanceModel->save();
		if ($result){
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 每天赠送游戏机会
	 * @param unknown $userID
	 * @param unknown $type
	 * @param unknown $times
	 * @return boolean
	 */
	public function addChanceByDay($userID,$type){
		if (empty($userID)||empty($type)){
			return false;
		}
		$start_time=mktime(0,0,0);
		$end_time=time();
		if ($type==1){
			$times=3;
		}else {
			$times=1;
		}
		$chance = LoveHookChance::model ()->find ( "customer_id=:customer_id and way=:type and create_time>=:start_time and create_time<=:end_time", array (
				':customer_id' => $userID,
				':start_time'=>$start_time,
				':end_time' =>$end_time,
				':type'=>$type
		) );
		if (empty($chance)){
			$this->addChance($userID, $type, $times);
		}
	}
	
	/**
	 * 判断是否彩富人生用户
	 * @param int $userID
	 * @return boolean
	 */
	public function isCaiFu($userID){
		if (empty($userID)){
			return false;
		}
		$propertyArr=PropertyActivity::model()->find('customer_id=:customer_id and (status=96 or status=99)',array(':customer_id'=>$userID));
		$appreciationArr=AppreciationPlan::model()->find('customer_id=:customer_id and status=99',array(':customer_id'=>$userID));
		if (!empty($propertyArr)||!empty($appreciationArr)){
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 分享的次数
	 * @param unknown $userID
	 * @return number|Ambigous <string, mixed, unknown>
	 */
	public function shareNum($userID){
		if (empty($userID)){
			return -1;
		}
		$start_time=mktime(0,0,0);
		$end_time=time();
		$chance = LoveHookChance::model ()->count ( "customer_id=:customer_id and way=:type and create_time>=:start_time and create_time<=:end_time", array (
				':customer_id' => $userID,
				':start_time'=>$start_time,
				':end_time' =>$end_time,
				':type'=>4
		) );
		return $chance;
	}
	
	/**
	 * 获取总次数
	 * @param unknown $userID
	 * @return boolean|number|unknown
	 */
	public function getAllChance($userID){
		if(empty($userID)){
			return false;
		}
		$sql="select sum(value) as summary from love_hook_chance where customer_id=".$userID;
		$growArr =Yii::app()->db->createCommand($sql)->queryAll();
		$valueTotal=$growArr[0]['summary'];
		if(empty($valueTotal)){
			return 0;
		}else{
			return $valueTotal;
		}
	}
	/**
	 * 全部排名
	 */
	public function ranking(){
		$sql="SELECT SUM(VALUE) AS total,customer_id,mobile,create_time FROM love_hook_integration GROUP BY customer_id ORDER BY total DESC,create_time ASC";
		$rank =Yii::app()->db->createCommand($sql)->queryAll();
		return $rank;
	}
	
	/**
	 * 当前用户排名、前一名、后一名
	 */
	public function thirdRanking($userID){
		$data=array();
		if (empty($userID)){
			return $data;
		}
		$isCurrent=false;
		$list=$this->ranking();
		if (!empty($list)){
			foreach ($list as $key=>&$val){  //查找当前用户的排名
				if ($val['customer_id']==$userID){
					$isCurrent=true;
					$rank=$key+1;
					break;
				}
			}
			//当前用户存在
			if ($isCurrent){
				if ($rank==1){ //第一名
					$data=array_slice($list, 0,2); //取前2个
				}elseif ($rank==count($list)) {  //最后一名
					$data=array_slice($list, -2);  //取后2个
				}else {
					$data=array_slice($list, $rank-2,3);
				}
			}
		}
		return $data;
	}
	
	/**
	 * 添加日志
	 * @param int $userID
	 * @param int $type
	 * @param string $open_id
	 */
	public function addLog($userID,$type,$open_id){
		$log =new LoveHookLog();
		$log->customer_id=$userID;
		$log->open_id=$open_id;
		$log->type=$type;
		$log->create_time=time();
		$result = $log->save();
		if($result){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 随机获取提示语
	 * @return string
	 */
	public function getTips($userID){
		$key=array_rand($this->_tips);
		$data['msg']=$this->_tips[$key];
		$data['name']='去看看';
		switch ($key){
			case 3: //京东
				$skey='jd';
				break;
			case 2: //彩生活特供
				$skey='daytuan';
				break;
			case 1: //彩富人生
				$skey='';
				$data['name']='参加彩富人生';
				$data['url']="javascript:mobileJump('EReduceList');";
				break;
			default:  //邀请注册
				$skey='';
				$data['name']='邀请注册';
				$data['url']="javascript:mobileJump('Invite');";
				break;
		}
		if (!empty($skey)){
			$SetableSmallLoansModel = new SetableSmallLoans();
			$href = $SetableSmallLoansModel->searchByIdAndType($skey, '', $userID,false);
			$data['url']=$href->completeURL;
		}
		return $data;
	}

}