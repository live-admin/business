<?php
/*
 * @version 元宵节model
 */
class YuanXiao extends CActiveRecord{
    private $beginTime='2017-02-09 00:00:00';//活动开始时间
	private $endTime='2017-02-28 23:59:59';//活动结束时间
    private $chance=3;//每天的抽奖机会
    private $you_hui_quan_id=100000353;//元宵节 满99减50券
    private $activityName='yuanxiao';
    private $chaceDay=3;//每天抽奖次数
    //抽奖奖品配置
    private $prizeArr=array(
        '0' => array('id'=>1,'prize_name'=>'5元抵用券','num'=>50,'v'=>5),
        '1' => array('id'=>2,'prize_name'=>'3元抵用券','num'=>500,'v'=>20),
        '2' => array('id'=>3,'prize_name'=>'1元抵用券','num'=>1000,'v'=>50),
        '3' => array('id'=>4,'prize_name'=>'谢谢惠顾','v'=>25),
    );
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*********************************************************首页***************************************************************************/
    /*
     * @version 进入活动首页获取优惠券
     * @param int $customerId 用户ID
     * return boolean
     */
    public function getCoupon($customerId){
        if(empty($customerId)){
            return false;
        }
        $mobile=$this->getMobileByCustomerId($customerId);
        $checkCoupons=UserCoupons::model()->find('mobile=:mobile and you_hui_quan_id=:you_hui_quan_id',array('mobile'=>$mobile,'you_hui_quan_id'=>$this->you_hui_quan_id));
        if(empty($checkCoupons)){
            $model = new UserCoupons();
			$model->mobile = $mobile;
			$model->you_hui_quan_id = $this->you_hui_quan_id;
			$model->is_use = 0;
			$model->create_time = time();
			$model->num = 0;
            if($model->save()){
				return true;
			}else{
                return false;
            }
        }else{
            return false;
        }
    }
    /**
	 * @version 获取所有商品
	 * @return array
	 */
	public function getGoodsList(){
		//先从缓存里获取
		$redisKey = 'CZY_'.$this->activityName.'_goods_list';
		$data = Yii::app()->rediscache->get($redisKey);
		if (empty($data)){
			$data = ActivityGoods::model()->getProducts($this->activityName,true,false);
			Yii::app()->rediscache->set($redisKey, $data, 86400); //缓存1天
		}
		return $data;
	}
    /**
	 * 获取今日疯抢商品
	 * @return array
	 */
	public function getSekillGoodsList(){
		$data = StockingSeckillGoods::model()->getProducts($this->activityName,true,false);
		if(!empty($data)){
            return $data;
        }else{
            return false;
        }
	}
    /*
     * @verson 抽奖
     * @param int $customer_id
     * @return boolean
     */
    public function getPrizeByChouJiang($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $yan=$this->yanZhengChance($customer_id);
        if(!$yan){
            return false;
        }
        $prizeArr=$this->checkAll($customer_id);
        foreach ($prizeArr as $key => $val) {
            $arr[$val['id']] = $val['v']; 
        }
        $rid = $this->get_rand($arr); //根据概率获取奖项id
        $list=array();
        if($rid==1){//5元抵用券
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->getYouHuiQuan($customer_id,5);
            $result2=$this->insertPrize($customer_id, 1, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==2){//3元抵用券
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->getYouHuiQuan($customer_id,3);
            $result2=$this->insertPrize($customer_id, 2, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==3){//1元抵用券
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->getYouHuiQuan($customer_id,1);
            $result2=$this->insertPrize($customer_id, 3, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==4){//谢谢惠顾
            $result=$this->insertPrize($customer_id, 4, $this->prizeArr[$rid-1]['prize_name']);
            if($result){
                return $list=array('rid'=>$rid);
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    private function checkAll($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
    	$endTime = mktime(23, 59, 59);
        $mobile=$this->getMobileByCustomerId($customer_id);
        $n1=YuanxiaoPrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>1,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $n2=YuanxiaoPrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>2,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $n3=YuanxiaoPrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>3,':beginTime'=>$beginTime,':endTime'=>$endTime));
        //优惠券判断
        $check1=YuanxiaoPrize::model()->find('prize_id=:prize_id and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>1,':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $check2=YuanxiaoPrize::model()->find('prize_id=:prize_id and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>2,':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $check3=YuanxiaoPrize::model()->find('prize_id=:prize_id and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>3,':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
        
        if($n1>=$this->prizeArr[0]['num'] || !empty($check1)){
             unset($this->prizeArr[0]);
        }
        if($n2>=$this->prizeArr[1]['num'] || !empty($check2)){
             unset($this->prizeArr[1]);
        }
        if($n3>=$this->prizeArr[2]['num'] || !empty($check3)){
             unset($this->prizeArr[2]);
        }
        return $this->prizeArr;
    }
    /*
     * @version 验证机会次数
     * @param int $customer_id
     * return boolean
     */
    public function yanZhengChance($customer_id){
        if(empty($customer_id)){
    		return false;
    	}
        $leftChance=$this->getChanceValue($customer_id);
        if($leftChance>0){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 获取总的抽奖机会
     * @param int $customer_id
     * return int 总的抽奖机会
     */
    public function getChanceValue($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
    	$endTime = mktime(23, 59, 59);
        $mobile=$this->getMobileByCustomerId($customer_id);
        $userChance=YuanxiaoPrize::model()->count('mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
        if(empty($userChance)){
            return $this->chaceDay;
        }else{
            $leftChance=intval($this->chaceDay-$userChance);
            return $leftChance;
        }
    }
    /**
	 * @version 抽奖获取优惠券
     * @param int $customer_id
     * @param int $amount 优惠券金额
     * return boolean
	 */
	public function getYouHuiQuan($customer_id,$amount){
		if (empty($customer_id) || empty($amount)){
			return false;
		}
        $beginTime = mktime(0,0,0);
    	$endTime = mktime(23, 59, 59);
        $CouponsArr=YouHuiQuan::model()->find('man_jian=:man_jian and amout=:amout and use_start_time=:use_start_time and get_start_time=:get_start_time',array(':man_jian'=>1,':amout'=>$amount,':use_start_time'=>date('Y-m-d',$beginTime),':get_start_time'=>date('Y-m-d',$beginTime)));
        if(empty($CouponsArr)){
            return false;
        }
        $todayCoupons=$CouponsArr['id'];
        $mobile=$this->getMobileByCustomerId($customer_id);
		//添加优惠券
		$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(":you_hui_quan_id" => $todayCoupons,":mobile" => $mobile));
		if(empty($userCouponsArr)){
			$uc_model=new UserCoupons();
			$uc_model->mobile=$mobile;
			$uc_model->you_hui_quan_id=$todayCoupons;
            $uc_model->is_use=0;
            $uc_model->num=0;
			$uc_model->create_time=time();
			$result=$uc_model->save();
			if ($result){
				return true;
			}else {
				return false;
			}
		}else {
			return true;
		}
	}
    /*
     * @version 插入奖品
     * @param int $customer_id
     * @param int $prize_id
     * @param string $prize_name
     * return array/boolean
     */
    private function insertPrize($customer_id,$prize_id,$prize_name){
        if(empty($customer_id) || empty($prize_id) || empty($prize_name)){
            return false;
        }
        $mobile=$this->getMobileByCustomerId($customer_id);
        $YuanPriceModel=new YuanxiaoPrize();
        $YuanPriceModel->mobile=$mobile;
        $YuanPriceModel->prize_id=$prize_id;
        $YuanPriceModel->prize_name=$prize_name;
        $YuanPriceModel->create_time= time();
        $isInsert=$YuanPriceModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 获奖明细
     * @param int $customer_id
     * return array 
     */
    public function getPrizeDetail($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $mobile=$this->getMobileByCustomerId($customer_id);
        $prizeMobileArr = YuanxiaoPrize::model()->findAll(array(
            'select'=>array('prize_name','create_time'),
            'order' => 'id DESC',
            'condition' => 'mobile=:mobile',
            'params' => array(':mobile'=>$mobile),
        ));
        return $prizeMobileArr;
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
     * @version 通过用户ID获取手机号码
     * @param int $customerId 用户ID
     * return string 
     */
    public function getMobileByCustomerId($customerId){
        if(empty($customerId)){
            return false;
        }
        $cusArr=Customer::model()->findByPk($customerId);
        return $cusArr['mobile'];
    }
    /*
     * @version 记录分享
     * @param int $customer_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$type)
    {
        $shareLog =new YuanxiaoLog();
        $shareLog->customer_id=$customer_id;
        $shareLog->type=$type;
        $shareLog->create_time=time();
        $result = $shareLog->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
}