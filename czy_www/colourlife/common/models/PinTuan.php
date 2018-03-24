<?php
/*
 * @version 拼团接口
 */
class PinTuan extends CActiveRecord{
    private $beginTime='2017-04-15 00:00:00';
    private $endTime='2017-04-28 23:59:59';
    //本地
//    private $good_Ids = array (
//        '1' => array (
//            38137,
//			38138,
//            
//        ),
//        '2' => array (
//            38149,
//            38150,
//            38151,
//            38152,
//        ),
//        '3' => array (
//        ),
//        '4' => array (
//        ),
//        '5' => array (
//        ),
//        '6' => array (
//        ),
//    );
//    private $goods_count=array(
//        38137=>3,
//		38138=>4,
//        38149=>5,
//        38150=>6,
//        38151=>7,
//        38152=>8,
//    );
    //正式
    private $goods_count=array(
        43838=>14,
        43904=>19,
        43905=>15,
        43921=>17,
        43834=>15,
        43910=>12,
        43920=>16,
        43915=>12,
        43872=>6,
        43873=>7,
        43922=>16,
        43923=>15,
        43882=>8,
        43883=>9,
        43788=>3,
        43790=>4,
        43784=>3,
        43787=>2,
        43786=>1,
        43778=>2,
        43913=>16,
        43914=>18,
        43908=>9,
        43917=>4,
        43916=>3,
        43869=>8,
        43867=>3,
        43868=>4,
        43866=>5,
        43808=>15,
        43807=>8,
        43802=>7,
        43801=>9,
        43800=>7,
        43799=>8,
        43806=>4,
        43804=>3,
        43803=>4,
        43805=>3,
        43935=>4,
        43972=>3,
        43973=>4,
        43974=>2,
        43975=>3,
    );
    //正式
    private $good_Ids = array (
        '1' => array (
            43869,
            43867,
            43868,
            43866,
        ),
        '2' => array (
            43935,
            43972,
            43973,
            43974,
            43975,
        ),
        '3' => array (
            43922,
            43923,
            43882,
            43883,
            43788,
            43790,
            43784,
            43787,
            43786,     
            43778,
        ),
        '4' => array (
            43838,
            43904,
            43905,
            43921,
            43834,
            43910,
            43920,
            43915,
            43872,
            43873,
        ),
        '5' => array (
            43913,
            43914,
            43908,
            43917,
            43916,
        ),
        '6' => array (
            43808,
            43807,
            43802,
            43801,
            43800,
            43799,
            43806,
            43804,
            43803,
            43805,
        ),
    );
    private $shopTuan=5033;
    private $tuanStatus=array(
        '0'=>'未成团',
        '1'=>'已成团',
    );
    private $statusArr=array(
        '0'=>'未付款',
        '1'=>'待发货',
        '3'=>'待收货',
        '4'=>'已收货',
        '99'=>'交易成功',
        '96'=>'交易失败',
        '97'=>'订单已取消',
        '102'=>'已退款',
        '103'=>'部分退款',
    );
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 返回当前对应的四款产品
     * @param int $category
     * return array
     */
    public function getWeekProduct($category){
//        $num=$this->getWeeks();
//        $num=0;
        if(empty($category)){
            return false;
        }
        $goodsArr=$this->good_Ids[$category];  //当前分类商品
        if(!empty($goodsArr)){
            $productList=array();
            $timeLeft=$this->getDaoJiShi();
            foreach ($goodsArr as $key=>$val){
                $cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0 and status=0',array(':goods_id'=>$val));
                if($key==0){
                    $productArr=$this->getProductDetail($val);
                    $resultArr=$this->getProductLowPrice($val);
                    $orderNum=$this->getOrderNum($val);
                    $productList['a']['name']=$productArr['name'];
                    $productList['a']['img_name']=$category.'a';
                    $productList['a']['price']=$resultArr['price'];
                    $productList['a']['tuanNum']=$resultArr['tuanNum'];
                    $productList['a']['orderNum']=$orderNum;
                    $productList['a']['timeLeft']=$timeLeft;
                    $productList['a']['url']='/goods/'.$val;
                }elseif($key==1){
                    $productArr=$this->getProductDetail($val);
                    $resultArr=$this->getProductLowPrice($val);
                    $orderNum=$this->getOrderNum($val);
                    $productList['b']['name']=$productArr['name'];
                    $productList['b']['img_name']=$category.'b';
                    $productList['b']['price']=$resultArr['price'];
                    $productList['b']['tuanNum']=$resultArr['tuanNum'];
                    $productList['b']['orderNum']=$orderNum;
                    $productList['b']['timeLeft']=$timeLeft;
                    $productList['b']['url']='/goods/'.$val;
                }elseif($key==2){
                    $productArr=$this->getProductDetail($val);
                    $resultArr=$this->getProductLowPrice($val);
                    $orderNum=$this->getOrderNum($val);
                    $productList['c']['name']=$productArr['name'];
                    $productList['c']['img_name']=$category.'c';
                    $productList['c']['price']=$resultArr['price'];
                    $productList['c']['tuanNum']=$resultArr['tuanNum'];
                    $productList['c']['orderNum']=$orderNum;
                    $productList['c']['timeLeft']=$timeLeft;
                    $productList['c']['url']='/goods/'.$val;
                }elseif($key==3){
                    $productArr=$this->getProductDetail($val);
                    $resultArr=$this->getProductLowPrice($val);
                    $orderNum=$this->getOrderNum($val);
                    $productList['d']['name']=$productArr['name'];
                    $productList['d']['img_name']=$category.'d';
                    $productList['d']['price']=$resultArr['price'];
                    $productList['d']['tuanNum']=$resultArr['tuanNum'];
                    $productList['d']['orderNum']=$orderNum;
                    $productList['d']['timeLeft']=$timeLeft;
                    $productList['d']['url']='/goods/'.$val;
                }elseif($key==4){
                    $productArr=$this->getProductDetail($val);
                    $resultArr=$this->getProductLowPrice($val);
                    $orderNum=$this->getOrderNum($val);
                    $productList['e']['name']=$productArr['name'];
                    $productList['e']['img_name']=$category.'e';
                    $productList['e']['price']=$resultArr['price'];
                    $productList['e']['tuanNum']=$resultArr['tuanNum'];
                    $productList['e']['orderNum']=$orderNum;
                    $productList['e']['timeLeft']=$timeLeft;
                    $productList['e']['url']='/goods/'.$val;
                }elseif($key==5){
                    $productArr=$this->getProductDetail($val);
                    $resultArr=$this->getProductLowPrice($val);
                    $orderNum=$this->getOrderNum($val);
                    $productList['f']['name']=$productArr['name'];
                    $productList['f']['img_name']=$category.'f';
                    $productList['f']['price']=$resultArr['price'];
                    $productList['f']['tuanNum']=$resultArr['tuanNum'];
                    $productList['f']['orderNum']=$orderNum;
                    $productList['f']['timeLeft']=$timeLeft;
                    $productList['f']['url']='/goods/'.$val;
                }elseif($key==6){
                    $productArr=$this->getProductDetail($val);
                    $resultArr=$this->getProductLowPrice($val);
                    $orderNum=$this->getOrderNum($val);
                    $productList['g']['name']=$productArr['name'];
                    $productList['g']['img_name']=$category.'g';
                    $productList['g']['price']=$resultArr['price'];
                    $productList['g']['tuanNum']=$resultArr['tuanNum'];
                    $productList['g']['orderNum']=$orderNum;
                    $productList['g']['timeLeft']=$timeLeft;
                    $productList['g']['url']='/goods/'.$val;
                }elseif($key==7){
                    $productArr=$this->getProductDetail($val);
                    $resultArr=$this->getProductLowPrice($val);
                    $orderNum=$this->getOrderNum($val);
                    $productList['h']['name']=$productArr['name'];
                    $productList['h']['img_name']=$category.'h';
                    $productList['h']['price']=$resultArr['price'];
                    $productList['h']['tuanNum']=$resultArr['tuanNum'];
                    $productList['h']['orderNum']=$orderNum;
                    $productList['h']['timeLeft']=$timeLeft;
                    $productList['h']['url']='/goods/'.$val;
                }elseif($key==8){
                    $productArr=$this->getProductDetail($val);
                    $resultArr=$this->getProductLowPrice($val);
                    $orderNum=$this->getOrderNum($val);
                    $productList['i']['name']=$productArr['name'];
                    $productList['i']['img_name']=$category.'i';
                    $productList['i']['price']=$resultArr['price'];
                    $productList['i']['tuanNum']=$resultArr['tuanNum'];
                    $productList['i']['orderNum']=$orderNum;
                    $productList['i']['timeLeft']=$timeLeft;
                    $productList['i']['url']='/goods/'.$val;
                }elseif($key==9){
                    $productArr=$this->getProductDetail($val);
                    $resultArr=$this->getProductLowPrice($val);
                    $orderNum=$this->getOrderNum($val);
                    $productList['j']['name']=$productArr['name'];
                    $productList['j']['img_name']=$category.'j';
                    $productList['j']['price']=$resultArr['price'];
                    $productList['j']['tuanNum']=$resultArr['tuanNum'];
                    $productList['j']['orderNum']=$orderNum;
                    $productList['j']['timeLeft']=$timeLeft;
                    $productList['j']['url']='/goods/'.$val;
                }
            }
            return $productList;
        }else{
            return false;
        }
    }
    
    /**
     * @version 获取周数
     * return int
     */
//    public function getWeeks(){
//    	$current_time=  time();
//    	$begin_time=strtotime($this->beginTime);
//    	$weeks=($current_time-$begin_time)/(7*86400);
//    	return floor($weeks);
//    }
    /*
     * @version 通过产品id获取产品信息
     * @param int goods_id
     * return array
     */
    public function getProductDetail($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $productArr=Goods::model()->findByPk($goods_id);
        return $productArr;
    }
    /*
     * @version 通过产品id获取最低价格以及最少成团人数
     * @param int goods_id
     * return array 价格、成团人数
     */
    public function getProductLowPrice($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $productConfigArr=ProductDiscountConfig::model()->findAll('product_id=:product_id',array(':product_id'=>$goods_id));
        if(!empty($productConfigArr)){
            $list=array();
            $resultArr=array();
            foreach ($productConfigArr as $productConfig){
                $list['price'][]=$productConfig['price'];
                $list['cheng_tuan_num'][]=$productConfig['cheng_tuan_num'];
            }
            $price=min($list['price']);
            $tuanNum=min($list['cheng_tuan_num']);
            $resultArr['price']=$price;
            $resultArr['tuanNum']=$tuanNum;
            return $resultArr;
        }else{
            return false;
        }
    }
    /*
     * @version 通过产品id获取组团的人数
     * @param int $goods_id
     * return int 组团人数
     */
    public function getOrderNum($goods_id){
        if(empty($goods_id)){
            return false;
        }
//        $beginTimeInt=  strtotime($this->beginTime);
//        $endTimeInt=  strtotime($this->endTime);
        $sql="select count(*) from `order` o left join order_goods_relation ogr on o.id=ogr.order_id  where o.seller_id=".$this->shopTuan."  and o.`status` in(1,3,4,99) and o.is_lock=0 and ogr.goods_id=".$goods_id;
        $count = Yii::app()->db->createCommand($sql)->queryScalar();
        $total=$count+$this->goods_count[$goods_id];
        return $total;
    }
    /*
     * @version 倒计时时间
     * return int 时间戳
     */
    public function getDaoJiShi(){
        $now=time();
        $next_time=strtotime($this->endTime);
        $daoJiShi=$next_time-$now;
        return $daoJiShi;
    }
    /*
     * @version 参与抽奖url
     * return string
     */
//    public function getChouUrl(){
//        return '/chouJiang/index';
//    }
    
    
    /**********************************************产品详情页************************************/
    /*
     * @version 通过产品id获取产品折扣
     * @param int $goods_id
     * return array
     */
    public function getProductDiscount($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $productDiscountArr = ProductDiscountConfig::model()->findAll(array(
            'select'=>array('begin_num','end_num','discount'),
            'order' => 'begin_num asc',
            'condition' => 'product_id=:product_id',
            'params' => array(':product_id'=>$goods_id),
        ));
        return $productDiscountArr;
    }
    /*
     * @version 获取当前对应的价格
     * @param int $goods_id
     * return array
     */
    public function getCurrentPrice($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $orderNum=$this->getOrderNum($goods_id);
        $num=$orderNum+1;
        $productDiscountArr = ProductDiscountConfig::model()->find(array(
            'select'=>array('price'),
            'condition' => 'product_id=:product_id and begin_num<=:num and end_num>=:num',
            'params' => array(':product_id'=>$goods_id,':num'=>$num),
        ));
        if(!empty($productDiscountArr)){
            return $productDiscountArr['price'];
        }else{
            $productArr=$this->getProductDetail($goods_id);
            return $productArr['customer_price'];
        }
    }
    /*
     * @version 限定的产品只能购买一个,数量限制一个
     * @param int $goods_id
     * @param int $userId
     * return boolean
     */
    public function getOrderBuy($goods_id,$userId){
        if(empty($goods_id) || empty($userId)){
            return false;
        }
        $sql="select * from `order` o left join order_goods_relation ogr on o.id=ogr.order_id where o.seller_id=".$this->shopTuan." and o.`status` in(1,3,4,99) and o.buyer_id=".$userId." and ogr.goods_id=".$goods_id;
        $orderArr =Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($orderArr)){
            return true;
        }else{
            return true;
        }
    }
    /*
     * @version 根据产品id获取产品的banner图片
     * @param int $goods_id
     * return array
     */
    public function getProductBanner($goods_id){
        if(empty($goods_id)){
           return false; 
        }
        $listBanner=array();
        if($this->banner_count[$goods_id]>0){
            for($i=0;$i<$this->banner_count[$goods_id];$i++){
                $listBanner[]=$goods_id."banner_".$i;
            }
        }
        return $listBanner;
    }
    /**************************************************抽奖页面******************************************************/
    /*
     * @version 通过用户id获取已付款的订单
     * @param int $userId
     * return array
     */
    public function getOrderArray($userId){
        if(empty($userId)){
            return false;
        }
        $orderArr = Order::model()->findAll(array(
            'select'=>array('id'),
            'condition' => 'seller_id=:seller_id and buyer_id=:buyer_id and status in(1,3,4,99) and is_send=0',
            'params' => array(':seller_id'=>$this->shopTuan,':buyer_id'=>$userId),
        ));
        //$orderCount=Order::model()->count('seller_id=:seller_id and status in(1,3,4,99) and create_time>=:startDay and create_time<:endDay',array(':seller_id'=>$this->shopTuan,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $orderList=array();
        if(!empty($orderArr)){
            foreach ($orderArr as $key=>$val){
                $orderList[]=$val['id'];
            }
            return $orderList;
        }else{
            return false;
        }
    }
    /*
     * @version 通过用户id获取已付款的订单
     * @param int $userId
     * return array
     */
    public function getOrderArray2($userId){
        if(empty($userId)){
            return false;
        }
        $orderArr = Order::model()->findAll(array(
            'select'=>array('id'),
            'condition' => 'seller_id=:seller_id and buyer_id=:buyer_id and status in(1,3,4,99) and create_time>=:startDay and create_time<:endDay',
            'params' => array(':seller_id'=>$this->shopTuan,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime),':buyer_id'=>$userId),
        ));
        //$orderCount=Order::model()->count('seller_id=:seller_id and status in(1,3,4,99) and create_time>=:startDay and create_time<:endDay',array(':seller_id'=>$this->shopTuan,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $orderList=array();
        if(!empty($orderArr)){
            foreach ($orderArr as $key=>$val){
                $orderList[]=$val['id'];
            }
            return $orderList;
        }else{
            return false;
        }
    }
    /*
     * @version 通过用户id获取分享记录
     * @param int $userId
     * return array
     */
    public function getPinShareChanceArray($userId){
        if(empty($userId)){
            return false;
        }
        $pinShareChanceArr = PinTuanShare::model()->findAll(array(
            'select'=>array('id'),
            'condition' => 'customer_id=:customer_id and is_send=0',
            'params' => array(':customer_id'=>$userId),
        ));
        //$orderCount=Order::model()->count('seller_id=:seller_id and status in(1,3,4,99) and create_time>=:startDay and create_time<:endDay',array(':seller_id'=>$this->shopTuan,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $shareChanceList=array();
        if(!empty($pinShareChanceArr)){
            foreach ($pinShareChanceArr as $key=>$val){
                $shareChanceList[]=$val['id'];
            }
            return $shareChanceList;
        }else{
            return false;
        }
    }
    /*
     * @version 通过用户id获取用户分享获得的抽奖次数
     * @param int $userId
     * return int 数量
     */
    public function getChanceByShare($userId){
        if(empty($userId)){
            return false;
        }
        $m=PinTuanShare::model()->count('customer_id=:customer_id and way=:way and is_send=0',array(':customer_id'=>$userId,':way'=>1));
        return $m;
    }
    /*
     * @version 获取总的抽奖机会
     */
    public function getAllChance($userId){
        if(empty($userId)){
            return false;
        }
        $orderList=$this->getOrderArray($userId);
        if(!empty($orderList)){
            $count=count($orderList);
        }else{
            $count=0;
        }
        $count2=$this->getChanceByShare($userId);
        $allChance=$count+$count2;
        return $allChance;
    }
    /*
     * @verson 抽奖
     * @param int $userId
     * @return boolean
     */
    public function chouJiang($userId){
        if(empty($userId)){
            return false;
        }
        $prizeOtherArr=$this->checkAll();
        foreach ($prizeOtherArr as $key => $val){
            $arr[$val['id']] = $val['v']; 
        }
        $rid = $this->get_rand($arr); //根据概率获取奖项id
        $list=array();
        $orderList=$this->getOrderArray($userId);
        $shareChanceList=$this->getPinShareChanceArray($userId);
        if($rid==1){
            $transaction = Yii::app()->db->beginTransaction();
            $items = array(
    				'customer_id' => $userId,//用户的ID
    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
    				'sum' =>$this->prizeArr[$rid-1]['amount'],//红包金额,
    				'sn' => 17,
                );
            $redPacked = new RedPacket();
            $result=$redPacked->addRedPacker($items);
            if(!empty($shareChanceList)){
                $result2=$this->insertPrize($userId, 1, $this->prizeArr[$rid-1]['prize_name'], $shareChanceList[0]);
            }else{
                $result2=$this->insertPrize($userId, 1, $this->prizeArr[$rid-1]['prize_name'], $orderList[0]);
            }
            if(!empty($shareChanceList)){
                $result3=$this->updateShare($shareChanceList[0]);
            }else{
                $result3=$this->updateOrder($orderList[0]);
            }
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==2){
            $transaction = Yii::app()->db->beginTransaction();
            $items = array(
    				'customer_id' => $userId,//用户的ID
    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
    				'sum' =>$this->prizeArr[$rid-1]['amount'],//红包金额,
    				'sn' => 17,
                );
            $redPacked = new RedPacket();
            $result=$redPacked->addRedPacker($items);
            if(!empty($shareChanceList)){
                $result2=$this->insertPrize($userId, 2, $this->prizeArr[$rid-1]['prize_name'], $shareChanceList[0]);
            }else{
                $result2=$this->insertPrize($userId, 2, $this->prizeArr[$rid-1]['prize_name'], $orderList[0]);
            }
            if(!empty($shareChanceList)){
                $result3=$this->updateShare($shareChanceList[0]);
            }else{
                $result3=$this->updateOrder($orderList[0]);
            }
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==3){
            $transaction = Yii::app()->db->beginTransaction();
            $items = array(
    				'customer_id' => $userId,//用户的ID
    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
    				'sum' =>$this->prizeArr[$rid-1]['amount'],//红包金额,
    				'sn' => 17,
                );
            $redPacked = new RedPacket();
            $result=$redPacked->addRedPacker($items);
            if(!empty($shareChanceList)){
                $result2=$this->insertPrize($userId, 3, $this->prizeArr[$rid-1]['prize_name'], $shareChanceList[0]);
            }else{
                $result2=$this->insertPrize($userId, 3, $this->prizeArr[$rid-1]['prize_name'], $orderList[0]);
            }
            if(!empty($shareChanceList)){
                $result3=$this->updateShare($shareChanceList[0]);
            }else{
                $result3=$this->updateOrder($orderList[0]);
            }
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==4){
            if(!empty($shareChanceList)){
                $result=$this->updateShare($shareChanceList[0]);
            }else{
                $result=$this->updateOrder($orderList[0]);
            }
            if($result){
                return $list=array('rid'=>$rid-1);
            }else{
                return false;
            }
        }else{
            return $list=array('rid'=>-1);
        }
    }
    /*
     * @version 获取优惠券
     * @param string $mobile
     * @param int $yhq_id
     * return boolean 
     */
    public function getYouHuiQuan($mobile,$yhq_id){
       if(empty($mobile) || empty($yhq_id)){
            return false;
        }
        $userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$yhq_id,':mobile'=>$mobile));
        if(!empty($userCouponsArr)){
            return true;
        }else{
            $uc_model=new UserCoupons();
            $uc_model->mobile=$mobile;
            $uc_model->you_hui_quan_id=$yhq_id;
            $uc_model->create_time=time();
            $result=$uc_model->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }
    }
    /*
     * @version 获奖明细
     * @param int $userId
     * return array
     */
    public function getPrizeDetail($userId){
        if(empty($userId)){
            return false;
        }
        $cusArr=Customer::model()->findByPk($userId);
        $prizeMobileArr = PinTuanPrize::model()->findAll(array(
            'select'=>array('mobile','prize_name','create_time'),
            'order' => 'id DESC',
            'condition' => 'mobile=:mobile',
            'params' => array(':mobile'=>$cusArr->mobile),
        ));
        return $prizeMobileArr;
    }
    /*
     * @version 获奖滚动十条
     * @param int $userId
     * return array
     */
    public function getPrizeDetailTop($userId){
        if(empty($userId)){
            return false;
        }
        $sql="select mobile,prize_name from pin_tuan_prize order by create_time limit 10 ";
        $jiangArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($jiangArr)){
            $awarderList=array();
            foreach($jiangArr as $key=>$jiang){
                $awarderList[$key]['phone']=substr_replace($jiang['mobile'],'****',2,5);
                $awarderList[$key]['awarderName']=$jiang['prize_name'];
            }
            
            $awarderList=json_encode($awarderList);
            return $awarderList;
        }else{
            return false;
        }
    }
    /*
     * @version 插入奖品
     * @param int $userId
     * @param int $prize_id
     * @param string $prize_name
     * @param int $order_id
     * return array/boolean
     */
    private function insertPrize($userId,$prize_id,$prize_name,$order_id){
        if(empty($userId) || empty($prize_id) || empty($prize_name) || empty($order_id)){
            return false;
        }
        $cusArr=Customer::model()->findByPk($userId);
        $TuanPriceModel=new PinTuanPrize();
        $TuanPriceModel->mobile=$cusArr->mobile;
        $TuanPriceModel->prize_id=$prize_id;
        $TuanPriceModel->prize_name=$prize_name;
        $TuanPriceModel->order_id=$order_id;
        $TuanPriceModel->create_time= time();
        $isInsert=$TuanPriceModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 通过订单id更改订单的is_send 状态
     * @param int $orderId
     * return boolean
     */
    public function updateOrder($orderId){
        if(empty($orderId)){
            return false;
        }
        $sqlUpdate="update `order` set is_send=1 where id=".$orderId." and is_send=0 and seller_id=".$this->shopTuan;
        $result=Yii::app()->db->createCommand($sqlUpdate)->execute();
        if($result){
            return true;
        }else{
            return false;
        }
        
    }
    /*
     * @version 通过分享的id更改分享记录表的is_send 状态
     * @param int $shareID
     * return boolean
     */
    public function updateShare($shareID){
        if(empty($shareID)){
            return false;
        }
        $sqlUpdate="update pin_tuan_share set is_send=1 where id=".$shareID." and is_send=0";
        $result=Yii::app()->db->createCommand($sqlUpdate)->execute();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 检查总的数量
     */
    public function checkAll(){
        $n1=PinTuanPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>1,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n2=PinTuanPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>2,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n3=PinTuanPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>3,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        if($n1>=1000){
             unset($this->prizeArr[0]);
        }
        if($n2>=200){
             unset($this->prizeArr[1]);
        }
        if($n3>=100){
             unset($this->prizeArr[2]);
        }
        return $this->prizeArr;
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
    /****************************首页返现提示框********************************/
    /*
     * @version 通过用户id获取拼团的返现情况
     * @param int $userId
     * return array/boolean
     */
//    public function getFanXian($userId){
//        if(empty($userId)){
//            return false;
//        }
//        $orderArr=$this->getOrderArray2($userId);
//        if(empty($orderArr)){
//            return false;
//        }
//        $num=$this->getWeeks();
//        $orderGoodsArr=$this->getProductIdByOrder($userId);//订单产品id数组
//        if(empty($orderGoodsArr)){
//            return false;
//        }
//        if($num==0){
//            return false;
//        }else{
//            $list=array();
//            for($i=1;$i<=$num;$i++){
//                foreach ($this->good_Ids[$i] as $key=>$val){
//                    $list[]=$val;
//                }
//            }
//        }
//        $goodsArr=$list;//前一个商品数组
//        if(!empty($goodsArr) && !empty($orderGoodsArr)){
//            $allGoodsArr=array();
//            $allGoodsArr=array_intersect($orderGoodsArr, $goodsArr);
//            if(!empty($allGoodsArr)){
//                $resultArr=array();
//                foreach ($allGoodsArr as $key=>$val){
//                    $minArr=$this->getProductLowPrice($val);//$resultArr['tuanNum']
//                    $n=$minArr['tuanNum'];//最少的组团人数
//                    $m=$this->getOrderNum($val);//组团人数
//                    $amountArr=$this->getPayAmount($val, $userId);//下单的金额$amountArr[0]['amount']
//                    $currentPrice=$this->getCurrentPrice($val);//当前价格
//                    if(($m>=$n) && ($currentPrice!=$amountArr[0]['amount'])){
//                        $type=1;
//                        $items = array(
//                            'customer_id' => $userId,//用户的ID
//                            'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
//                            'sum' =>abs($amountArr[0]['amount']-$currentPrice),//红包金额,
//                            'sn' => 17,
//                        );
//                        $redPacked = new RedPacket();
//                        $result=$redPacked->addRedPacker($items);
//                        $result2=$this->changeOrderType($amountArr[0]['order_id']);
//                        if(!$result || !$result2){
//                            continue;
//                        }else{
//                            $resultArr[$key]['type']=$type;
//                            $resultArr[$key]['num']=$m;
//                            $resultArr[$key]['amount']=abs($amountArr[0]['amount']-$currentPrice);
//                        }
//                    }elseif(($m<$n) && ($currentPrice==$amountArr[0]['amount'])){
//                        $type=0;
//                        
//                        $result=$this->changeOrderType($amountArr[0]['order_id']);
//                        if(!$result){
//                            continue;
//                        }else{
//                            $productArr=$this->getProductDetail($val);
//                            $resultArr[$key]['type']=$type;
//                            $resultArr[$key]['name']=$productArr['name'];
//                        }
//                    }else{
//                        continue;
//                    }
//                }
//                return $resultArr;
//
//            }else{
//                return false;
//            }
//        }else{
//            return false;
//        }
//    }
    /*
     * @version 更改订单的order_type
     * @param int $order_id
     */
    public function changeOrderType($order_id){
        if(empty($order_id)){
            return false;
        }
        $sqlUpdate="update `order` set order_type=3 where id=".$order_id." and seller_id=".$this->shopTuan;
        $result=Yii::app()->db->createCommand($sqlUpdate)->execute();
        if($result){
            return true;
        }else{
            return false;
        }
        
        
        
        
    }
    /*
     * @version 通过产品id和用户id获取客户付款的金额
     * @param int int $goods_id
     * @param int int $userId
     * return decial 付款金额
    */
   public function getPayAmount($goods_id,$userId){
       if(empty($goods_id) || empty($userId)){
           return false;
       }
       $sql="select o.amount,ogr.order_id from `order` o left join order_goods_relation ogr on o.id=ogr.order_id  where o.buyer_model='customer' and o.seller_id=".$this->shopTuan." and o.buyer_id=".$userId." and ogr.goods_id=".$goods_id;
       $orderAmountArr=Yii::app()->db->createCommand($sql)->queryAll();
       return $orderAmountArr;
   }
   /*
     * @version 通过用户id获取下单的产品id
     * @param int $userId
     * return array
     */
    public function getProductIdByOrder($userId){
        if(empty($userId)){
            return false;
        }
        $orderGoodsArr=$this->getPinOrderList2($userId);
        if(!empty($orderGoodsArr)){
            $list=array();
            foreach ($orderGoodsArr as $orderGoods){
                $list[]=$orderGoods['goods_id'];
            }
            return $list;
        }else{
            return false;
        }
    }


    /**********************************订单列表和订单详情****************************************/
    /*
     * @version 通过产品id来判断是否成团  未成团、已成团
     * @param int $goods_id
     * return string
     */
    public function getTuanStatus($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $m=$this->getOrderNum($goods_id);
        $resultArr=$this->getProductLowPrice($goods_id);
        $n=$resultArr['tuanNum'];
        if($m<$n){
            $status=0;
        }else{
            $status=1;
        }
        return $this->tuanStatus[$status];
    }
    /*
     * @version 通过产品id获取产品的图片路径
     * @param int $goods_id
     * return string
     */
    public function getProductImage($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $goodsArr=Goods::model()->findByPk($goods_id);
        return F::getUploadsUrl("/images/" . $goodsArr->good_image);
    }
    /*
     * @version 通过订单id获取订单详情
     * @param int $orderId
     * return array
     */
    public function getOrderDetail($orderId){
        if(empty($orderId)){
            return false;
        }
        $sql="select * from `order` o left join order_goods_relation ogr on o.id=ogr.order_id  where o.buyer_model='customer' and o.seller_id=".$this->shopTuan." and o.id=".$orderId;
        $orderDetailArr=Yii::app()->db->createCommand($sql)->queryAll();
//        dump($orderDetailArr);
        return $orderDetailArr[0];
    }
    /*
     * @version 通过订单状态返回订单状态名称
     * @param int $status
     * return string
     */
    public function getStatusName($status){
        $statusName=$this->statusArr[$status];
        if(empty($statusName)){
            return '未知状态';
        }else{
            return $statusName;
        }
    }
    /*
     * @version 通过用户id获取拼团的所有订单
     * @param int $userId
     * return array
     */
    public function getPinOrderList($userId){
        if(empty($userId)){
            return false;
        }
        $beginTimeInt=  strtotime($this->beginTime);
        $endTimeInt=  strtotime($this->endTime);
        $sql="select * from `order` o left join order_goods_relation ogr on o.id=ogr.order_id  where o.buyer_model='customer' and o.seller_id=".$this->shopTuan." and o.buyer_id=".$userId."  and o.is_lock=0 and o.create_time>".$beginTimeInt." and o.create_time<".$endTimeInt;
        $orderArr=Yii::app()->db->createCommand($sql)->queryAll();
        return $orderArr;
    }
    /*
     * @version 通过用户id获取拼团的所有订单
     * @param int $userId
     * return array
     */
    public function getPinOrderList2($userId){
        if(empty($userId)){
            return false;
        }
        $beginTimeInt=  strtotime($this->beginTime);
        $endTimeInt=  strtotime($this->endTime);
        $sql="select * from `order` o left join order_goods_relation ogr on o.id=ogr.order_id  where o.buyer_model='customer' and o.`status` in(1,3,4,99) and o.seller_id=".$this->shopTuan." and o.buyer_id=".$userId."  and o.is_lock=0 and o.order_type!=3 and o.create_time>".$beginTimeInt." and o.create_time<".$endTimeInt;
        $orderArr=Yii::app()->db->createCommand($sql)->queryAll();
        return $orderArr;
    }
    /*
     * @version 通过支付id获取支付名称
     * @param int $payment_id
     * return string
     */
    public function getPaymentName($payment_id){
        if($payment_id==0){
            return '饭票全额支付';
        }else{
            $paymentArr=Payment::model()->findByPk($payment_id);
            return $paymentArr['name'];
        }
    }
    /*************************************收货地址列表**********************************/
    /*
     * @version 通过用户id获取用户地址列表
     * param int $userId
     * 
     */
    public function getPinAddressList($userId){
        if(empty($userId)){
            return false;
        }
        $sql="select * from pintuan_shouhuo_address where uid=".$userId."  order by create_time desc";
        $pinAddressListArr=Yii::app()->db->createCommand($sql)->queryAll();
        return $pinAddressListArr;
    }
    /*
     * @version 获取全部地址
     * @param int $province_id 省份ID
     * @param int $city_id  城市ID
     * @param int $county_id 区/县ID
     * @param int $town_id 城镇ID
     * return string
     */
    public function getPinFourAddress($province_id,$city_id,$county_id,$town_id){
        if(empty($province_id) || empty($city_id) || empty($county_id)){
            return false;
        }
        $province_name=$this->getProvinceName($province_id);
        $city_name=$this->getCityName($city_id);
        $county_name=$this->getCountyName($county_id);
        if(!empty($town_id)){
            $town_name=$this->getTownName($town_id);
        }else{
            $town_name='';
        }
        return $province_name." ".$city_name." ".$county_name." ".$town_name;
    }
    /*
     * @version 通过地址id获取地址信息
     * @param int $addressId
     * return array
     */
    public function getAddressDetail($addressId){
        if(empty($addressId)){
            return false;
        }
        $addressDetailArr=PintuanShouhuoAddress::model()->findByPk($addressId);
        if(!empty($addressDetailArr)){
            return $addressDetailArr;
        }else{
            return false;
        }
    }
    /*
     * @version 删除收货地址
     * @param int $addressId
     * return boolean
     */
    public function deleteAddress($addressId){
        if(empty($addressId)){
            return false;
        }
        $sql="delete from pintuan_shouhuo_address where id=".$addressId;
        $result=Yii::app()->db->createCommand($sql)->execute();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 通过收货地址的id获取地址信息
     * @param int $addressId
     * return array
     */
    public function getAddressById($addressId){
        if(empty($addressId)){
            return false;
        }
        $list=array();
        $addressDetail=PintuanShouhuoAddress::model()->findByPk($addressId);
        if(!empty($addressDetail)){
            $name=$addressDetail['name'];
            $mobile=$addressDetail['mobile'];
            $province_id=$addressDetail['province_id'];
            $city_id=$addressDetail['city_id'];
            $county_id=$addressDetail['county_id'];
            $town_id=$addressDetail['town_id'];
            $address=$addressDetail['address'];
            $zip=$addressDetail['zip'];
            $fourAddress=$this->getPinFourAddress($province_id, $city_id, $county_id, $town_id);
            return $list=array(
                'name'=>$name,
                'mobile'=>$mobile,
                'fourAddress'=>$fourAddress,
                'address'=>$address,
                'province_id'=>$province_id,
                'city_id'=>$city_id,
                'county_id'=>$county_id,
                'town_id'=>$town_id,
                'zip'=>$zip,
                
           );
        }else{
            return false;
        }
    }
    /**********************************分享*******************************************/
    /*
     * @verson 点击分享按钮
     * @param int $userId
     * @return boolean
     */
    public function getValueByShare($userId){
        if(empty($userId)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $pinArr= PinTuanShare::model()->find('customer_id=:customer_id and way=:way and create_time>=:beginTime and create_time<= :endTime',array(':customer_id'=>$userId,':beginTime'=>$beginTime,':endTime'=>$endTime,':way'=>1));
        if(empty($pinArr)){
            $result=$this->insertPinShareValue($userId, 1, 1);
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
    /*
     * @version 插入次数
     * @param int $userId
     * @param int $value
     * @param int $way
     * return array/boolean
     */
    private function insertPinShareValue($userId,$value,$way){
        if(empty($userId) || empty($value) || empty($way)){
            return false;
        }
        $PinShareModel=new PinTuanShare();
        $PinShareModel->customer_id=$userId;
        $PinShareModel->value=$value;
        $PinShareModel->way=$way;
        $PinShareModel->is_send=0;
        $PinShareModel->create_time= time();
        $isInsert=$PinShareModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 记录分享
     * @param int $userId
     * @param int $type
     * return boolean
     */
    public function addShareLog($userId,$type){
        $shareLog =new PinTuanLog();
        $shareLog->customer_id=$userId;
        $shareLog->type=$type;
        $shareLog->create_time=time();
        $result = $shareLog->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /***************************************调用表的地址信息*************************************************/
    /*
     * @version 通过provinceId获取省份名称
     * @param int $provinceId 省份ID
     */
    public function getProvinceName($provinceId){
        if(empty($provinceId)){
            return false;
        }
        $provinceArr=WholeCountryRegion::model()->find("code=:code and type=1",array(':code'=>$provinceId));
        if(!empty($provinceArr)){
            return $provinceArr['name'];
        }else{
            return false;
        }
    }
    /*
     * @version 通过cityId获取城市名称
     * @param int $cityId 城市ID
     */
    public function getCityName($cityId){
        if(empty($cityId)){
            return false;
        }
        $cityArr=WholeCountryRegion::model()->find("code=:code and type=2",array(':code'=>$cityId));
        if(!empty($cityArr)){
            return $cityArr['name'];
        }else{
            return false;
        }
    }
    
    /*
     * @version 通过countyID获取县/区ID
     * @param int countyID 县/区ID
     */
    public function getCountyName($countyId){
        if(empty($countyId)){
            return false;
        }
        $countyArr=WholeCountryRegion::model()->find("code=:code and type=3",array(':code'=>$countyId));
        if(!empty($countyArr)){
            return $countyArr['name'];
        }else{
            return false;
        }
    }
    /*
     * @version 通过townID获取县/区名称
     * @param int $townId 镇ID
     */
    public function getTownName($townId){
        if(empty($townId)){
            return false;
        }
        $townyArr=WholeCountryRegion::model()->find("code=:code and type=4",array(':code'=>$townId));
        if(!empty($townyArr)){
            return $townyArr['name'];
        }else{
            return false;
        }
    }
    /*
     * @version 获取所有的省份
     */
    public function getProvince(){
        $privinceArr=WholeCountryRegion::model()->findAll('parent_id=:parent_id',array(':parent_id'=>0));
        $array = array();
        if(!empty($privinceArr)){
            foreach ($privinceArr as $key=>$v){
                $array[] = array('name'=>urlencode($v['name']), 'id'=>$v['code']);
            }
            return $array;
        }
    }
    /*
     * @version 通过provinceId获取所有的城市
     * @param int $pinvinceID
     */
    public function getCity($pinvinceID){
        if(empty($pinvinceID)){
            return false;
        }
        $cityArr=WholeCountryRegion::model()->findAll('parent_id=:parent_id',array(':parent_id'=>$pinvinceID));
        $array = array();
        if(!empty($cityArr)){
            foreach ($cityArr as $key=>$v){
                $array[] = array('name'=>urlencode($v['name']), 'id'=>$v['code']);
            }
            return $array;
        }
    }
    /*
     * @version 通过$cityID获取所有的县/区
     * @param int $cityID
     */
    public function getCounty($cityID){
        if(empty($cityID)){
            return false;
        }
        $countyArr=WholeCountryRegion::model()->findAll('parent_id=:parent_id',array(':parent_id'=>$cityID));
        $array = array();
        if(!empty($countyArr)){
            foreach ($countyArr as $key=>$v){
                $array[] = array('name'=>urlencode($v['name']), 'id'=>$v['code']);
            }
            return $array;
        }
    }
    /*
     * @version 通过countyId获取所有的镇
     * @param int $countyId
     */
    public function GetTown($countyId){
        if(empty($countyId)){
            return false;
        }
        $townArr=WholeCountryRegion::model()->findAll('parent_id=:parent_id',array(':parent_id'=>$countyId));
        $array = array();
        if(!empty($townArr)){
            foreach ($townArr as $key=>$v){
                $array[] = array('name'=>urlencode($v['name']), 'id'=>$v['code']);
            }
            return $array;
        }
    }
    /*
     * @version 通过用户id判断是否是彩富用户
     * @param int $customer_id
     * return boolean
     */
    public function isCaiFuUser($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $propertyArr=PropertyActivity::model()->find('customer_id=:customer_id and (status=96 or status=99)',array(':customer_id'=>$customer_id));
        $appreciationArr=AppreciationPlan::model()->find('customer_id=:customer_id and status=99',array(':customer_id'=>$customer_id));
        if((!empty($propertyArr) || !empty($appreciationArr))){
            return true;
        }else{
            return false;
        }
    }
    
}

