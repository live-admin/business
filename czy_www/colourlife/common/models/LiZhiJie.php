<?php
/*
 * @version 荔枝节活动model
 */
class LiZhiJie extends CActiveRecord{
    private $beginTime='2017-06-08 00:00:00';//活动开始时间
	private $endTime='2017-06-25 23:59:59';//活动结束时间
    private $activityName='lizhijie';
    private $chaceDay=1;//每天抽奖次数
//    private $lifeArr=array(
//        0=>array(38240),//首页产品
//        1=>array(38241),//荔枝的前世今生
//        2=>array(44378,44379,44380),//荔枝才是夏日的老大
//    );
    //正式
    private $lifeArr=array(
        0=>array(44897,44729,44726,44898,44730,44731,44833,44831,44896,44892,44830,44829,44891,44890,44889,44888,44887,44886),//首页产品
        1=>array(44126,44730,44831),//荔枝的前世今生
        2=>array(44450,44453,44454,44455,44452),//荔枝才是夏日的老大
    );
    //秒杀商品数组
//    private $oneSha=array(38092,38237,38238,38239);
    //正式
    private $oneSha=array(44880,44877);
    //首页商品
//    private $shouProduct=array(38236,38237,38238);
    //彩富优惠券
    private $youHuiQuan=array(100000678,100000668);
    //抽奖奖品配置
    private $prizeArr=array(
        '0' => array('id'=>1,'prize_name'=>'零度果品荔枝3元代金券','num'=>3000,'v'=>60),
        '1' => array('id'=>2,'prize_name'=>'零度果品荔枝10元代金券','num'=>600,'v'=>15),
        '2' => array('id'=>3,'prize_name'=>'零度果品荔枝20元代金券','num'=>200,'v'=>5),
        '3' => array('id'=>4,'prize_name'=>'零度果品荔枝50元代金券','num'=>100,'v'=>1),
        '4' => array('id'=>5,'prize_name'=>'未中奖','v'=>19),
    );
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*********************************************************首页***************************************************************************/
    /**
	 * 获取9.9限时秒杀
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
    /**
	 * @version 获取荔枝商品页面所有商品
     * @param int $type
	 * @return array
	 */
	public function getGoodsList($type){
		$data = $this->getProductDetail($this->lifeArr[$type]);
		return $data;
	}
    /*
     * @version 获取商品详情信息
     * @param array 商品ID数组
     * return array
     */
    public function getProductDetail($lifeArr){
        if(empty($lifeArr)){
            return false;
        }
        $tmp=array();
        foreach ($lifeArr as $key=>$val){
            $productArr=  Goods::model()->findByPk($val);
            //$cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0 and status = 0',array(':goods_id'=>$val));
            if (empty($productArr)){
                continue;
            }
            $image_arr=explode(':', $productArr['good_image']);
            if(count($image_arr)>1){
                $tmp[$key]['imgName'] = $productArr['good_image'];
            }else{
                $tmp[$key]['imgName'] = F::getUploadsUrl("/images/" . $productArr['good_image']);
            }
            $tmp[$key]['pid']= $productArr->id;
            $tmp[$key]['price']=$productArr->customer_price;
            $tmp[$key]['name']=$productArr->name;
            $tmp[$key]['market_price']=$productArr->market_price;
            $tmp[$key]['brief']=$productArr->brief;
        }
        return $tmp;
    }
    /*
     * @version 首页中奖信息，获取最新的5条
     * @param int $customer_id
     * return 
     */
    public function getTip($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $sql = 'select mobile,prize_name from lizhijie_prize where prize_id!=4 order by id desc limit 5';
		$tip = Yii::app()->db->createCommand($sql)->queryAll();
		if(empty($tip)){
            $tip=false;
        }
        return $tip;
    }
    /*
     * @version 获取总的抽奖机会
     * @param int $customer_id
     * return int 总的抽奖机会
     */
    public function getChanceValue($customer_id){
        $customerPrizeNum = Yii::app()->db->createCommand("select prize_num from customer_prize_num WHERE customer_id={$customer_id}")->queryRow();
        if($customerPrizeNum){
            $this->chaceDay = $customerPrizeNum['prize_num'];
        }

        if(empty($customer_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
    	$endTime = mktime(23, 59, 59);
        $mobile=$this->getMobileByCustomerId($customer_id);
        $userChance= LizhijiePrize::model()->count('mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
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
        if($rid==1){//零度果品荔枝3元代金券
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
        }elseif($rid==2){//零度果品荔枝10元代金券
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->getYouHuiQuan($customer_id,10);
            $result2=$this->insertPrize($customer_id, 6, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==3){//零度果品荔枝20元代金券
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->getYouHuiQuan($customer_id,20);
            $result2=$this->insertPrize($customer_id, 7, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid);
            }else{
                $transaction->rollback();
                return false;
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->getYouHuiQuan($customer_id,50);
            }
        }elseif($rid==4){//零度果品荔枝50元代金券
            $result2=$this->insertPrize($customer_id, 8, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==5){//谢谢惠顾
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
        $begin=  strtotime($this->beginTime);
        $end=  strtotime($this->endTime);
        $mobile=$this->getMobileByCustomerId($customer_id);
        $n5=LizhijiePrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>5,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $n6=LizhijiePrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>6,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $n7=LizhijiePrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>7,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $n8=LizhijiePrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>8,':beginTime'=>$beginTime,':endTime'=>$endTime));
        //优惠券判断
        $check5=LizhijiePrize::model()->find('prize_id=5 and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':mobile'=>$mobile,':beginTime'=>$begin,':endTime'=>$end));
        $check6=LizhijiePrize::model()->find('prize_id=6 and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':mobile'=>$mobile,':beginTime'=>$begin,':endTime'=>$end));
        $check7=LizhijiePrize::model()->find('prize_id=7 and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':mobile'=>$mobile,':beginTime'=>$begin,':endTime'=>$end));
        $check8=LizhijiePrize::model()->find('prize_id=8 and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':mobile'=>$mobile,':beginTime'=>$begin,':endTime'=>$end));
        
        if($n5>=$this->prizeArr[0]['num'] || !empty($check5)){
             unset($this->prizeArr[0]);
        }
        if($n6>=$this->prizeArr[1]['num'] || !empty($check6)){
             unset($this->prizeArr[1]);
        }
        if($n7>=$this->prizeArr[2]['num'] || !empty($check7)){
             unset($this->prizeArr[2]);
        }
        if($n8>=$this->prizeArr[3]['num'] || !empty($check8)){
             unset($this->prizeArr[3]);
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
        $liZhiPriceModel=new LizhijiePrize();
        $liZhiPriceModel->mobile=$mobile;
        $liZhiPriceModel->prize_id=$prize_id;
        $liZhiPriceModel->prize_name=$prize_name;
        $liZhiPriceModel->create_time= time();
        $isInsert=$liZhiPriceModel->save();
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
        $CouponsArr=YouHuiQuan::model()->find('amout=:amout and man_jian=:man_jian and use_start_time=:use_start_time and get_start_time=:get_start_time',array(':amout'=>$amount,':man_jian'=>$amount,':use_start_time'=>date('Y-m-d',$beginTime),':get_start_time'=>date('Y-m-d',$beginTime)));
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
     * @version 获奖明细
     * @param int $customer_id
     * return array 
     */
    public function getPrizeDetail($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $mobile=$this->getMobileByCustomerId($customer_id);
        $prizeMobileArr = LizhijiePrize::model()->findAll(array(
            'select'=>array('prize_name','create_time'),
            'order' => 'id DESC',
            'condition' => 'mobile=:mobile and prize_id!=4',
            'params' => array(':mobile'=>$mobile),
        ));
		if(!empty($prizeMobileArr)){
            $prizeDetailArr=array();
            foreach ($prizeMobileArr as $k=>$v){
                $prizeDetailArr[$k]['id']=$v->id;
                $prizeDetailArr[$k]['create_time']=date("Y-m-d H:i:s",$v->create_time);
                $prizeDetailArr[$k]['prize_name']=$v->prize_name;
            }
        }else{
            return false;
        }
        return $prizeDetailArr;
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
                    $statusName='已获得饭票';
                }elseif($v->status==1 && $v->effective==1 && $v->is_send==0){
					$statusName='已注册';
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
			return 1;
		}else{
			return 0;
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
			$sql="select * from `order` o left join order_goods_relation ogr on o.id=ogr.order_id where  o.`status` in(0,1,3,4,99) and o.buyer_id=".$userId." and ogr.goods_id=".$goods_id;
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
        $shareLog =new LizhijieLog();
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
    /*
     * @version 根据增值宝订单获取领取优惠券的权限
     * @param int $customer_id
     * @param int $buType
     * return int (1,2,3)
     */
    public function getCaiQuan($customer_id,$buType){
        if(empty($customer_id) || empty($buType)){
            return false;
        }
        $beginStr='2017-06-18 00:00:00';//活动开始时间
        $endStr='2017-06-25 23:59:59';//活动结束时间
        $mobile=$this->getMobileByCustomerId($customer_id);
        $begin=  strtotime($beginStr);
        $end=  strtotime($endStr);
        $sqlCheck='select * from user_coupons where mobile="'.$mobile.'" and you_hui_quan_id='.$this->youHuiQuan[0];
        $youArr =Yii::app()->db->createCommand($sqlCheck)->queryAll();
        
        $sqlCheck2='select * from user_coupons where mobile="'.$mobile.'" and you_hui_quan_id='.$this->youHuiQuan[1];
        $youArr2 =Yii::app()->db->createCommand($sqlCheck2)->queryAll();
        if($buType==1){
            if(!empty($youArr)){
                return 4;
            }
            if(!empty($youArr2)){
                return 5;
            }
            $appreciationOne=AppreciationPlan::model()->find('customer_id=:customer_id and status in(96,99) and rate_id=1 and amount>=150000 and pay_time>=:begin_time and pay_time<=:end_time',array(':customer_id'=>$customer_id,':begin_time'=>$begin,':end_time'=>$end));
            if(!empty($appreciationOne)){
                $resultOne=$this->getCoupon($customer_id,$this->youHuiQuan[0]);
                if($resultOne){
                    return 1;
                }
            }else{
                return 3;
            }
        }
        if($buType==2){
            if(!empty($youArr)){
                return 5;
            }
            if(!empty($youArr2)){
                return 4;
            }
            $appreciationTwo=AppreciationPlan::model()->find('customer_id=:customer_id and status in(96,99) and rate_id=1 and amount>=50000 and pay_time>=:begin_time and pay_time<=:end_time',array(':customer_id'=>$customer_id,':begin_time'=>$begin,':end_time'=>$end));
            if(!empty($appreciationTwo)){
                $resultTwo=$this->getCoupon($customer_id,$this->youHuiQuan[1]);
                if($resultTwo){
                    return 2;
                }
            }else{
                return 3;
            }
        }
    }
    /*
     * @version 彩富获取优惠券
     * @param int $customer_id
     * return int (1,2,3,4)
     */
    public function getCoupon($customerId,$youHuiQuanId){
        if(empty($customerId) || empty($youHuiQuanId)){
            return false;
        }
        $mobile=$this->getMobileByCustomerId($customerId);
        $checkCoupons=UserCoupons::model()->find('mobile=:mobile and you_hui_quan_id=:you_hui_quan_id',array('mobile'=>$mobile,'you_hui_quan_id'=>$youHuiQuanId));
        if(empty($checkCoupons)){
            $model = new UserCoupons();
			$model->mobile = $mobile;
			$model->you_hui_quan_id = $youHuiQuanId;
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
}