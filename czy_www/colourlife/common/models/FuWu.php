<?php
/*
 * @version 服务专题活动model
 */
class FuWu extends CActiveRecord{
    private $beginTime='2017-04-23 00:00:00';//活动开始时间
	private $endTime='2017-05-07 23:59:59';//活动结束时间
    //这里是测试环境的appkey和appsecrets
//    private $Appkey='bh5Ua25gZlzCQMFrRoSc';
//    private $AppSecret='aR55DmCErU';
//    private $url = 'http://test.eshifu.cn/open/sendCoupon';//测试;正式环境m.eshifu.cn
    private $Appkey='LXqeFJBgguB8tUSmL2jT';
    private $AppSecret='H5z5VKfg9S';
    private $url = 'http://m.eshifu.cn/open/sendCoupon';
    private $chaceDay=3;//每天抽奖次数
    //抽奖奖品配置
    private $prizeArr=array(
        '0' => array('id'=>1,'prize_name'=>'谢谢参与','v'=>50),
        '1' => array('id'=>2,'prize_name'=>'京东3元代金券','num'=>100,'v'=>30),
        '2' => array('id'=>3,'prize_name'=>'e绿化小盆栽','num'=>10,'v'=>5),
        '3' => array('id'=>4,'prize_name'=>'空调挂机清洗','num'=>5,'v'=>5),
        '4' => array('id'=>5,'prize_name'=>'空调清洗团购券包 ','num'=>100,'v'=>10),
    );
    //
//    private $lifeArr=array(
//        1=>array(38127,38128,38129),
//        2=>array(38127,38128,38129),
//    );
    //正式
    private $lifeArr=array(
        1=>array(2460,7489,44046),
        2=>array(23724),
    );
    private $activityName='fuwu';
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*********************************************************首页***************************************************************************/
    
    /**
	 * @version 获取绿植领养所有商品
     * @param int $type
	 * @return array
	 */
	public function getGoodsList($type){
        if(empty($type)){
            return false;
        }
		//先从缓存里获取
//		$redisKey = 'CZY_'.$this->activityName.'_goods_list';
//		$data = Yii::app()->rediscache->get($redisKey);
//		if (empty($data)){
			$data = $this->getProductDetail($this->lifeArr[$type]);
//			Yii::app()->rediscache->set($redisKey, $data, 86400); //缓存1天
//		}
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
        }
        return $tmp;
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
        if($rid==1){//谢谢参与
            $result=$this->insertPrize($customer_id, 1, $this->prizeArr[$rid-1]['prize_name']);
            if($result){
                return $list=array('rid'=>$rid);
            }else{
                return false;
            }
        }elseif($rid==2){//京东3元代金券
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
        }elseif($rid==3){//e绿化小盆栽
            $result=$this->insertPrize($customer_id, 3, $this->prizeArr[$rid-1]['prize_name']);
            if($result){
                return $list=array('rid'=>$rid,'id'=>$result);
            }else{
                return false;
            }
        }elseif($rid==4){//空调挂机清洗
            $transaction = Yii::app()->db->beginTransaction();
            $mobile=$this->getMobileByCustomerId($customer_id);
            $result=$this->sendCoupon($mobile,14);
//            $result=1;//调用e师傅的接口
            $result2=$this->insertPrize($customer_id, 4, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==5){//空调清洗团购券包
            $transaction = Yii::app()->db->beginTransaction();
            $mobile=$this->getMobileByCustomerId($customer_id);
            $result=$this->sendCoupon($mobile,15);
            $result2=$this->sendCoupon($mobile,16);
//            $result=1;//调用e师傅的接口
            $result3=$this->insertPrize($customer_id, 5, $this->prizeArr[$rid-1]['prize_name']);
            if($result && $result2 && $result3){
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
        $n2=FuwuPrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>2,':beginTime'=>$beginTime,':endTime'=>$endTime));
        $n3=FuwuPrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>3,':beginTime'=>strtotime($this->beginTime),':endTime'=>strtotime($this->endTime)));
        $n4=FuwuPrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>4,':beginTime'=>strtotime($this->beginTime),':endTime'=>strtotime($this->endTime)));
        $n5=FuwuPrize::model()->count('prize_id=:prize_id and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>5,':beginTime'=>strtotime($this->beginTime),':endTime'=>strtotime($this->endTime)));
        //优惠券判断
        $check2=FuwuPrize::model()->find('prize_id=:prize_id and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>2,':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
		$check4=FuwuPrize::model()->find('prize_id=:prize_id and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>4,':mobile'=>$mobile,':beginTime'=>strtotime($this->beginTime),':endTime'=>strtotime($this->endTime)));
		$check5=FuwuPrize::model()->find('prize_id=:prize_id and mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':prize_id'=>5,':mobile'=>$mobile,':beginTime'=>strtotime($this->beginTime),':endTime'=>strtotime($this->endTime)));
        
        if($n2>=$this->prizeArr[1]['num'] || !empty($check2)){
             unset($this->prizeArr[1]);
        }
        if($n3>=$this->prizeArr[2]['num']){
             unset($this->prizeArr[2]);
        }
        if($n4>=$this->prizeArr[3]['num'] || !empty($check4)){
             unset($this->prizeArr[3]);
        }
        if($n5>=$this->prizeArr[4]['num'] || !empty($check5)){
             unset($this->prizeArr[4]);
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
        $userChance=  FuwuPrize::model()->count('mobile=:mobile and create_time>=:beginTime and create_time<:endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,':endTime'=>$endTime));
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
        $CouponsArr=YouHuiQuan::model()->find('amout=:amout and use_start_time=:use_start_time and get_start_time=:get_start_time and shop_id=2',array(':amout'=>$amount,':use_start_time'=>date('Y-m-d',$beginTime),':get_start_time'=>date('Y-m-d',$beginTime)));
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
        $fuPriceModel=new FuwuPrize();
        $fuPriceModel->mobile=$mobile;
        $fuPriceModel->prize_id=$prize_id;
        $fuPriceModel->prize_name=$prize_name;
        $fuPriceModel->create_time= time();
        $isInsert=$fuPriceModel->save();
        if($isInsert){
            return $fuPriceModel->attributes['id'];
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
        $prizeMobileArr = FuwuPrize::model()->findAll(array(
            'select'=>array('id','prize_id','prize_name','create_time'),
            'order' => 'id DESC',
            'condition' => 'mobile=:mobile and prize_id!=1',
            'params' => array(':mobile'=>$mobile),
        ));
        if(!empty($prizeMobileArr)){
            $prizeDetailArr=array();
            foreach ($prizeMobileArr as $k=>$v){
                $prizeDetailArr[$k]['id']=$v->id;
                $prizeDetailArr[$k]['create_time']=date("Y-m-d H:i:s",$v->create_time);
                $prizeDetailArr[$k]['prize_name']=$v->prize_name;
                $check=$this->getPan($v->id);
                if(!$check && ($v->prize_id==3)){
                    $flag='未领取';
                }else{
                    $flag='已领取';
                }
                $prizeDetailArr[$k]['status']=$flag;
            }
        }else{
            return false;
        }
        return $prizeDetailArr;
    }
    /*
     * @version 判断小盆栽状态
     * @param int $id
     * return boolean
     */
    public function getPan($id){
        if(empty($id)){
            return false;
        }
        $check=FuwuAddress::model()->find('prize_id=:prize_id',array(':prize_id'=>$id));
        if(!empty($check)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 发放E维修优惠券
     * @param mobile,openCouponId
     * @return bool
     */
    public function sendCoupon($mobile, $openCouponId)
    {
        $timeStamp = time() * 1000;
        $sign = StrtoUpper(md5($this->AppSecret . 'appKey' . $this->Appkey . 'mobile' . $mobile . 'openCouponId' . $openCouponId . 'timestamp' . $timeStamp . $timeStamp));
        $param = array(
            'mobile' => $mobile,
            'appKey' => $this->Appkey,
            'couponId' => $openCouponId,
            'timestamp' => $timeStamp,
            'sign' => $sign,
        );
        $requestUrl = Yii::app()->curl->buildUrl($this->url, $param);
        $result = json_decode(Yii::app()->curl->post($requestUrl, $param), true);
        if (!empty($result) && $result['code'] == 0) {
            return true;
        } else {
            return false;
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
     * @param int $tid
     * return boolean
     */
    public function addShareLog($customer_id,$tid)
    {
        $shareLog =new FuwuLog();
        $shareLog->customer_id=$customer_id;
        $shareLog->type=$tid;
        $shareLog->create_time=time();
        $result = $shareLog->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
}