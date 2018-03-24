<?php
/*
 * @version 四月拼团model
 */
class SiYue extends CActiveRecord{
    private $beginTime='2017-04-15 00:00:00';//活动开始时间
	private $endTime='2017-04-28 23:59:59';//活动结束时间
    private $activityName='siyue';
    private $chaceDay=3;//每天抽奖次数
//    private $yaoArr=array(38151,38152);
    //正式
    private $yaoArr=array(43960,43961);
    //红包雨奖品配置
    private $prizeArr=array(
        '0' => array('id'=>1,'prize_name'=>'谢谢惠顾','v'=>40),
        '1' => array('id'=>2,'prize_name'=>'12元优惠券','num'=>50,'v'=>5),
        '2' => array('id'=>3,'prize_name'=>'8元优惠券','num'=>50,'v'=>5),
        '3' => array('id'=>4,'prize_name'=>'6元优惠券','num'=>50,'v'=>5),
        '4' => array('id'=>5,'prize_name'=>'3元优惠券','num'=>1150,'v'=>45),
        
    );
    //一元秒杀商品id
//    private $oneSha=array(38124,38123,38137,38138,38140,38141,38142,38143,38144);
    //正式
    private $oneSha=array(43971,43996,43997);
    private $siyueShop=5058;
    //
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*********************************************************首页***************************************************************************/
    /*
     * @version 首页中奖信息，获取最新的5条
     * @param int $customer_id
     * return 
     */
    public function getTip($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $sql = 'select mobile,prize_name from siyue_prize where prize_id!=1 order by id desc limit 5';
		$tip = Yii::app()->db->createCommand($sql)->queryAll();
		if(empty($tip)){
            $tip=false;
        }
        return $tip;
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
	 * 获取1元秒杀
	 * @return array
	 */
	public function getSekillGoodsList(){
		//$redisKey = 'CZY_'.$this->activityName.'_sekill_goods_list';
		//$data = Yii::app()->rediscache->get($redisKey);
		//if (empty($data)){
		$data = StockingSeckillGoods::model()->getProducts($this->activityName,true);
		//Yii::app()->rediscache->set($redisKey, $data, 86400); //缓存1天
		//}
		return $data;
	}
    /*
     * @version 根据商品ID获取商品库存
     * @param array $gid
     * return int
     */
    public function getStock(){
        if(empty($this->oneSha)){
            return false;
        }
        $kuCunArr=array();
        foreach ($this->oneSha as $k=>$v){
            $goodArr=Goods::model()->findByPk($v);
            $kuCunArr[$v]=$goodArr['ku_cun'];
        }
        return $kuCunArr;
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
        $userChance=  SiyuePrize::model()->count('mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
        if(empty($userChance)){
            return $this->chaceDay;
        }else{
            $leftChance=intval($this->chaceDay-$userChance);
            return $leftChance;
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
        if($rid==1){//谢谢惠顾
            $result=$this->insertPrize($customer_id, 1, $this->prizeArr[$rid-1]['prize_name']);
            if($result){
                return $list=array('rid'=>$rid);
            }else{
                return false;
            }
        }elseif($rid==2){//12元优惠券
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->getYouHuiQuan($customer_id,12);
            $result2=$this->insertPrize($customer_id, 2, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==3){//8元优惠券
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->getYouHuiQuan($customer_id,8);
            $result2=$this->insertPrize($customer_id, 3, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==4){//6元优惠券
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->getYouHuiQuan($customer_id,6);
            $result2=$this->insertPrize($customer_id, 4, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==5){//3元优惠券
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->getYouHuiQuan($customer_id,3);
            $result2=$this->insertPrize($customer_id, 5, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid);
            }else{
                $transaction->rollback();
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
        $n1=SiyuePrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>2,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $n2=SiyuePrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>3,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $n3=SiyuePrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>4,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $n4=SiyuePrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>5,':beginTime'=>$beginTime,':endTime'=>$endTime));
        //优惠券判断
        $check1=SiyuePrize::model()->find('prize_id=:prize_id and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>2,':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $check2=SiyuePrize::model()->find('prize_id=:prize_id and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>3,':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $check3=SiyuePrize::model()->find('prize_id=:prize_id and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>4,':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $check4=SiyuePrize::model()->find('prize_id=:prize_id and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>5,':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
        
        if($n1>=$this->prizeArr[1]['num'] || !empty($check1)){
             unset($this->prizeArr[1]);
        }
        if($n2>=$this->prizeArr[2]['num'] || !empty($check2)){
             unset($this->prizeArr[2]);
        }
        if($n3>=$this->prizeArr[3]['num'] || !empty($check3)){
             unset($this->prizeArr[3]);
        }
        if($n4>=$this->prizeArr[4]['num'] || !empty($check4)){
             unset($this->prizeArr[4]);
        }
        return $this->prizeArr;
    }
    /*
     * @version 插入奖品
     * @param int $customer_id
     * @param int $prize_id
     * @param string $prize_name
     * return array/boolean
     */
    public function insertPrize($customer_id,$prize_id,$prize_name){
        if(empty($customer_id) || empty($prize_id) || empty($prize_name)){
            return false;
        }
        $mobile=$this->getMobileByCustomerId($customer_id);
        $siYuePriceModel=new SiyuePrize();
        $siYuePriceModel->mobile=$mobile;
        $siYuePriceModel->prize_id=$prize_id;
        $siYuePriceModel->prize_name=$prize_name;
        $siYuePriceModel->create_time= time();
        $isInsert=$siYuePriceModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
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
        $CouponsArr=YouHuiQuan::model()->find('amout=:amout and use_start_time=:use_start_time and get_start_time=:get_start_time',array(':amout'=>$amount,':use_start_time'=>date('Y-m-d',$beginTime),':get_start_time'=>date('Y-m-d',$beginTime)));
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
     * @version 获取邀请好友页面的产品信息
     * return array
     */
    public function getProductInfo(){
        if(!empty($this->yaoArr)){
            $date=array();
            foreach ($this->yaoArr as $k=>$v){
                $goodArr=Goods::model()->findByPk($v);
                $cheapArr=CheapLog::model()->find('goods_id=:goods_id and status=0 and is_deleted=0',array(':goods_id'=>$v));
                $date[$cheapArr['id']]['img_name']=F::getUploadsUrl("/images/" . $goodArr['good_image']);
                $date[$cheapArr['id']]['price']=$goodArr['customer_price'];
                $date[$cheapArr['id']]['name']=$goodArr['name'];
            }
            return $date;
        }else{
            return false;
        }
    }
    /*
     * @version 获取邀请记录
     * @param int $customer_id
     * return array
     */
    public function getRecord($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $invArr=Invite::model()->findAll('customer_id=:customer_id and create_time>=:beginTime and create_time<=:endTime',array(':customer_id'=>$customer_id,':beginTime'=>strtotime($this->beginTime),':endTime'=>strtotime($this->endTime)));
        if(!empty($invArr)){
            $data=array();
            foreach ($invArr as $k=>$v){
                $data[$k]['mobile']=$v->mobile;
                if($v->status==1 && $v->effective==1 && $v->is_send==1 && $v->state==1){
                    $statusName='已获得优惠券';
                }elseif($v->status==1 && $v->effective==1 && $v->is_send==0){
					$dateStr = date('Y-m-d', $v->create_time);
					$timestamp= strtotime($dateStr);
					$timestampOther= $timestamp + 86400;
                    $check=Invite::model()->find('customer_id=:customer_id  and  create_time>=:beginTime and create_time<:endTime and state=:state',array(':customer_id'=>$customer_id,':beginTime'=>$timestamp,':endTime'=>$timestampOther,':state'=>1));
                    if(!empty($check)){
                        $statusName='当天已获得优惠券';
                    }else{
                        $statusName='已注册';
                    }
                }else{
                    $statusName='未注册';
                }
                $data[$k]['status']=$statusName;
            }
            return $data;
        }else{
            return false;
        }
    }
    /* @version 
     * @param int $customer_id
     * @param string $mobile
     */
    public function insertInvite($customer_id,$mobile){
        if(empty($customer_id) || empty($mobile)){
            return false;
        }
        $inviteModel=new Invite();
        $inviteModel->customer_id=$customer_id;
        $inviteModel->mobile=$mobile;
        $inviteModel->model='customer';
        $inviteModel->create_time= time();
        $inviteModel->valid_time= time()+7200;
        $inviteModel->status= 1;
        $inviteModel->is_send= 0;
        $inviteModel->effective= 1;
        $inviteModel->state= 0;
        $isInsert=$inviteModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
	 * @version 限定的产品只能购买一个,数量限制一个
	* @param int $goods_id
	* return boolean
	*/
	public function getOne($goods_id){
		if(empty($goods_id)){
			return false;
		}
		if(in_array($goods_id, $this->oneSha)){
			return true;
		}else{
			return false;
		}
	}
    /*
	 * @version 根据产品id和用户id来判断每个人只能购买一个产品,限制一人买一个
	* @param int $goods_id
	* @param int $userId
	* return boolean
	*/
	public function getOrderBuy($goods_id,$userId){
		if(empty($goods_id) || empty($userId)){
			return false;
		}
		$result=$this->getOne($goods_id);
		if($result){
			$sql="select * from `order` o left join order_goods_relation ogr on o.id=ogr.order_id where o.seller_id=".$this->siyueShop." and o.`status` in(0,1,3,4,99) and o.buyer_id=".$userId." and ogr.goods_id=".$goods_id;
			$orderArr =Yii::app()->db->createCommand($sql)->queryAll();
			if(!empty($orderArr)){
				return false;
			}else{
				return true;
			}
		}else{
			return true;
		}
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
     * @version 记录分享
     * @param int $customer_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$type)
    {
        $shareLog =new SiyueLog();
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