<?php
/*
 * @version 愚人节活动model
*/
class AprilFool extends CActiveRecord{

	//奖项配置
	private $prize_arr=array(
			'0' => array('id'=>1,'prize_name'=>'愚人节快乐','code'=>array(4,7),'v'=>88),
			'1' => array('id'=>2,'prize_name'=>'彩之云纪念8g U盘','code'=>array(3,8),'v'=>4),
			'2' => array('id'=>3,'prize_name'=>'百草味 肉类零食 五香牛肉粒100g/袋','code'=>array(2,6),'v'=>4),
			'3' => array('id'=>4,'prize_name'=>'贝瑟斯 创意陶瓷马克杯 云朵牛奶水杯','code'=>array(1,5),'v'=>4),
	);
	//第一天数量
	private $num1 = array (
			'2' => 6,
			'3' => 8,
			'4' => 8 
	);
	//第二天数量
	private $num2 = array (
			'2' => 4,
			'3' => 7,
			'4' => 7
	);
	//京东邮费
	private $code=100000079;

	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	/**
	 * 获取最新五条留言
	 */
	public function newestLeaves($cust_id){
		$data=array();
		if (empty($cust_id)){
			return $data;
		}
		$is_self=false;
		// 判断是否有自己的留言，没有则出最新5条留言，有则出最新4条
		$user_leave = AprilFoolLeave::model ()->find ( array (
					'condition' => 'customer_id ='.$cust_id,
					'order' => 'create_time desc',
			) );
		if (empty($user_leave)){  
			$data = AprilFoolLeave::model ()->findAll ( array (
					'condition' => 'customer_id !=' . $cust_id,
					'order' => 'create_time desc',
					'limit' => 5
			) );
			
		}else {
			$data = AprilFoolLeave::model ()->findAll ( array (
					'condition' => 'customer_id !=' . $cust_id,
					'order' => 'create_time desc',
					'limit' => 4
			) );
			$is_self=true;
			array_unshift($data, $user_leave);
		}
		return array('is_self'=>$is_self,'data'=>$data);
	}
	/**
	 * 通过用户ID获取用户信息
	 * @param unknown $cust_id
	 * @return multitype:string
	 */
	public function getUserInfo($cust_id){
		$portrait='';
		$nickname='';
		if (!empty($cust_id)){
			$customer = Customer::model()->find("id=:id and state = 0", array('id' => $cust_id));
			if (!empty($customer)){
				$portrait=$customer->portrait;
				if (!empty($customer->nickname)){
					$nickname=$customer->nickname;
				}else{
					$nickname=$tmp ['name'] = substr_replace($customer->mobile,"****",3,4);;
				}
			}
		}
		return array('portrait'=>$portrait,'nickname'=>$nickname);
	}
	
	/**
	 * 抽奖逻辑
	 * @param unknown $customer_id
	 * @param unknown $mobile 中奖填写的手机号
	 * @param $umobile 当前用户手机号
	 * @return boolean|Ambigous <string, unknown>
	 */
	public function lottery($customer_id,$umobile,$mobile=0){
		if (empty($customer_id)){
			return false;
		}
		$rid=$this->checkLottery($customer_id);
		//抽奖机会减1
		$chance=new AprilFoolChance();
		$chance->customer_id=$customer_id;
		$chance->times=1;
		$chance->type=2;
		$chance->way=0;
		$chance->create_time=time();
		$chance_result=$chance->save();
		if (!$chance_result){
			return false;
		}
		$prize_arr=$this->prize_arr[$rid-1];
		$key=array_rand($prize_arr['code']);
		$resultID=$prize_arr['code'][$key];
		if ($rid==1){  //祝福语直接返回
			return array('rid'=>$rid,'resultID'=>$resultID);
		}
		
		$transaction = Yii::app()->db->beginTransaction();
		//添加中奖记录
		$prize=new AprilFoolPrize();
		$prize->customer_id=$customer_id;
		$prize->umobile=$umobile;
		$prize->mobile=$mobile;
		$prize->prize_id=$rid;
		$prize->prize_name=$prize_arr['prize_name'];
		$prize->create_time=time();
		$prize_result=$prize->save();
		//零食或杯子 发一元购码
		if ($rid==3||$rid==4){
			if ($rid==3){
				$goods_id='22794';
				
			}else{
				$goods_id='25858';
			}
			$onecode = OneYuanBuy::sendCode($customer_id, $goods_id, 0, $type = 'aprilFool');
			if ($prize_result&&$onecode){
				$transaction->commit();
				//一元购商品链接
				$SetableSmallLoansModel = new SetableSmallLoans();
				$oneYuan = $SetableSmallLoansModel->searchByIdAndType('oneyuan', '', $customer_id,false);
				$url=$oneYuan->completeURL.'&pid='.$goods_id;
				return array('rid'=>$rid,'url'=>$url,'resultID'=>$resultID);
			}else {
				$transaction->rollback();
				return false;
			}
		}else{
			if ($prize_result){
				$transaction->commit();
				$id=$prize->attributes['id'];
				return array('rid'=>$rid,'param'=>$id,'resultID'=>$resultID);
			}else {
				$transaction->rollback();
				return false;
			}
		}
	}
	
	/**
	 * 抽奖过程中每个用户限一种实物，不能重复（U盘，零食，马克杯）
	 */
	public function checkLottery($customer_id){
		if (date("d")==31){
			$limitNum=$this->num1;
		}else{
			$limitNum=$this->num2;
		}
		$start_time=mktime(0,0,0);
		$end_time=time();
		//U盘
		$UpanNum = AprilFoolPrize::model ()->count ( "prize_id=2 and create_time>=:start_time and create_time<=:end_time", array (
				':start_time' => $start_time,
				':end_time' => $end_time 
		) );
		if ($UpanNum>=$limitNum[2]){
			unset($this->prize_arr[1]);
		}else {
			$Upan = AprilFoolPrize::model ()->find ( "customer_id=:customer_id and prize_id=2", array (
					':customer_id' => $customer_id
			) );
			if (!empty($Upan)){
				unset($this->prize_arr[1]);
			}
		}
		//零食
		$snacksNum = AprilFoolPrize::model ()->count ( "create_time>=:start_time and create_time<=:end_time and prize_id=3", array (
				':start_time' => $start_time,
				':end_time' => $end_time
		) );
		if ($snacksNum>=$limitNum[3]){
			unset($this->prize_arr[2]);
		}else {
			$snacks = AprilFoolPrize::model ()->find ( "customer_id=:customer_id and prize_id=3", array (
					':customer_id' => $customer_id
			) );
			if (!empty($snacks)){
				unset($this->prize_arr[2]);
					
			}
		}
		//杯子
		$mugCupNum = AprilFoolPrize::model ()->count ( "create_time>=:start_time and create_time<=:end_time and prize_id=4", array (
				':start_time' => $start_time,
				':end_time' => $end_time
		) );
		if ($mugCupNum>=$limitNum[4]){
			unset($this->prize_arr[3]);
		}else {
			$mugCup = AprilFoolPrize::model ()->find ( "customer_id=:customer_id and prize_id=4", array (
					':customer_id' => $customer_id
			) );
			if (!empty($mugCup)){
				unset($this->prize_arr[3]);
			}
		}
		foreach ($this->prize_arr as $key => $val) {
			$arr[$val['id']] = $val['v'];
		}
		$rid=$this->get_rand($arr);
		return $rid;
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
	 * 添加抽奖机会
	 * @param unknown $customer_id
	 * @param unknown $times
	 * @param unknown $way
	 * @return boolean
	 */
	public function addChance($customer_id,$times,$way){
		if (empty($customer_id)||empty($times)||empty($way)){
			return false;
		}
		
		//每天分享最多得两次抽奖机会
		if ($way==1){
			$chance=$this->shareChance($customer_id);
			if ($chance) {
				return false;
			}
		}
		$chance=new AprilFoolChance();
		$chance->customer_id=$customer_id;
		$chance->mobile=0;
		$chance->times=$times;
		$chance->type=1;
		$chance->way=$way;
		$chance->create_time=time();
		if ($chance->save()){
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 获取抽奖机会
	 * @param unknown $customer_id
	 * @return boolean|number
	 */
	public function getChances($customer_id,$umobile){
		if(empty($customer_id)){
			return false;
		}
		$total=0;
		$use_num=0;
		$all_num=0;
		$chance = AprilFoolChance::model()->findAll ( "customer_id=:customer_id", array (
				':customer_id' => $customer_id
		) );
		$new_chance=AprilFoolChance::model()->count("mobile=:mobile and way=3",array(':mobile'=>$umobile));
		if (!empty($chance)){
			foreach ($chance as $val){
				if ($val->type==1){  //获得的
					$total+=$val->times;
				}elseif ($val->type==2){  //使用的
					$use_num+=$val->times;
				}
			}
			$all_num=$total+$new_chance-$use_num;
		}
		return $all_num;
	}
	
    /*
     * @version 记录
    * @param int $customer_id
    * @param int $type
    * return boolean
    */
    public function addLog($customer_id,$type)
    {
    	$log =new AprilFoolLog();
    	$log->customer_id=$customer_id;
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
     * 更新中奖记录的手机号
     * @param unknown $mobile
     */
    public function updatePrize($id,$mobile){
    	if (empty($id)||empty($mobile)){
    		return false;
    	}
    	$result=AprilFoolPrize::model()->updateByPk($id, array('mobile'=>$mobile));
    	if ($result){
    		return true;
    	}else {
    		return false;
    	}
    }
    
    /**
     * 获取分享次数
     * @param unknown $customer_id
     * @return boolean
     */
    public function shareChance($customer_id){
    	if (empty($customer_id)){
    		return false;
    	}
    	$start_time=mktime(0,0,0);
    	$end_time=time();
    	$chance = AprilFoolChance::model()->findAll ( "type=1 and way=1 and customer_id=:customer_id and create_time>=:start_time and create_time<=:end_time", array (
    			':customer_id' => $customer_id,
    			':start_time' => $start_time,
    			':end_time' => $end_time
    	) );
    	if (! empty ( $chance ) && count ( $chance ) >= 2) {
    		return true;
    	}else {
    		return false;
    	}
    }
    
    /**
     * 获取中奖列表
     * @param unknown $customer_id
     * @return multitype:|multitype:multitype:NULL
     */
    public function getPrizeList($customer_id){
    	if (empty($customer_id)){
    		return array();
    	}
    	$data=array();
    	$prize=AprilFoolPrize::model()->findAll(array('order'=>'create_time desc'));
    	if (!empty($prize)){
    		foreach ($prize as $val){
    			$tmp=array();
    			$tmp['winnerName']=substr_replace($val->umobile,"****",3,4);
    			$tmp['prize']=$val->prize_name;
    			$data[]=$tmp;
    		}
    	}
    	return $data;
    }
	
	/**
	 * 获取分页数据
	 */
	public function getPageList($currentPage) {
		if (empty ( $currentPage )) {
			return array ();
		}
		$pageSize = 8;
		$nextPage = $currentPage + 1; // 下一页
		$offset = $currentPage * $pageSize;
		$criteria = new CDbCriteria ();
		$criteria->limit = $pageSize;
		$criteria->offset = $offset;
		$criteria->order = 'create_time desc'; // 排序条件
		$list = AprilFoolLeave::model ()->findAll ( $criteria );
		if (count ( $list ) <8) {
			$nextPage = 0;
		}
		$data = array ();
		if (! empty ( $list )) {
			foreach ( $list as $val ) {
				$tmp = array ();
				$tmp ['iconUrl'] = F::getStaticsUrl ( '/common/images/nopic.png' );
				if (! empty ( $val->customer_id )) {
					$customer = Customer::model ()->find ( "id=:id and state = 0", array (
							'id' => $val->customer_id 
					) );
					if (! empty ( $customer->nickname )) {
						$tmp ['name'] = $customer->nickname;
					} else {
						$tmp ['name'] = substr_replace($customer->mobile,"****",3,4);
					}
					if (! empty ( $customer->portrait )) {
						$tmp ['iconUrl'] = F::getUploadsUrl ( "/images/" . $customer->portrait );
					}
				} else {
					$tmp ['name'] = substr_replace ( $val->mobile, "****", 3, 4 );
				}
				$tmp ['time'] = date ( "Y.m.d H:i", $val->create_time );
				$tmp ['content'] = $val->content;
				$data [] = $tmp;
			}
		}
		return array (
				'data' => $data,
				'nextPage' => $nextPage 
		);
	}
}

