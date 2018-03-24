<?php
/*
 * @version 五月特惠活动
 */
class MayPreferential extends CActiveRecord{
    private $begin_time_other=32400;
    private $end_time_other=53999;
    //正式环境
    private $a=array(28455,28458,28461,28464,28467,28490,28487,28484,28481,28478,28475,28472);
    private $b=array(28456,28459,28462,28465,28468,28489,28486,28483,28480,28477,28474,28471);
    private $c=array(28457,28460,28463,28466,28469,28488,28485,28482,28479,28476,28473,28470);
    private $d=array(28437,28438,28439,28440,28436,28441,28711,28712,28713,28714,28715,28716);
//    private $a=array(24370);
//    private $b=array(24371);
//    private $c=array(24372);
//    private $d=array(24373);
    
    //测试环境
//    private $a=array(26990,26994,26998);
//    private $b=array(26991,26995,26999);
//    private $c=array(26992,26996,26988);
//    private $d=array(26993,26997,26989);
    private $jdProduct=array(1327330,302006,923559,1067048,1124783,2385655);//京东产品sku
    private $xfzgx=2607;
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 返回当前对应的四款产品
     * return array
     */
    public function getDayProduct(){
        $goodsArr=$this->getCheapLog();
        if(!empty($goodsArr)){
            $productList=array();
            foreach ($goodsArr as $goods){
                $cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0',array(':goods_id'=>$goods['goods_id']));
                if(in_array($goods['goods_id'], $this->a)){
                    $productList['a']['id']= $cheapArr->id;
                    $productArr=$this->getProductDetail($goods['goods_id']);
                    $productList['a']['name']=$productArr['name'];
                    if($productArr['ku_cun']<0){
                        $productArr['ku_cun']=0;
                    }
                    $productList['a']['ku_cun']=$productArr['ku_cun'];
                    $productList['a']['img_name']=date('Ymd',$goods['begin_time']).'a';
                }elseif(in_array($goods['goods_id'], $this->b)){
                    $productList['b']['id']= $cheapArr->id;
                    $productArr=$this->getProductDetail($goods['goods_id']);
                    $productList['b']['name']=$productArr['name'];
                    if($productArr['ku_cun']<0){
                        $productArr['ku_cun']=0;
                    }
                    $productList['b']['ku_cun']=$productArr['ku_cun'];
                    $productList['b']['img_name']=date('Ymd',$goods['begin_time']).'b';
                }elseif(in_array($goods['goods_id'], $this->c)){
                    $productList['c']['id']= $cheapArr->id;
                    $productArr=$this->getProductDetail($goods['goods_id']);
                    $productList['c']['name']=$productArr['name'];
                    if($productArr['ku_cun']<0){
                        $productArr['ku_cun']=0;
                    }
                    $productList['c']['ku_cun']=$productArr['ku_cun'];
                    $productList['c']['img_name']=date('Ymd',$goods['begin_time']).'c';
                }elseif(in_array($goods['goods_id'], $this->d)){
                    $productList['d']['id']= $cheapArr->id;
                    $productArr=$this->getProductDetail($goods['goods_id']);
                    $productList['d']['name']=$productArr['name'];
                    if($productArr['ku_cun']<0){
                        $productArr['ku_cun']=0;
                    }
                    $productList['d']['ku_cun']=$productArr['ku_cun'];
                    $productList['d']['img_name']=date('Ymd',$goods['begin_time']).'d';
                }
            }
            return $productList;
        }else{
            return false;
        }
    }
    /*
     * @version 通过用户id获取邀请注册的人数
     * @param $userId
     * return int
     */
    public function getRegisterNum($userId){
        if(empty($userId)){
            return false;
        }
        $goodsArr=$this->getCheapLog();
        if(!empty($goodsArr)){
           $beginTime=$goodsArr[0]['begin_time']+3600*9;
           $endTime=$beginTime+24*3600;
           $n=Invite::model()->count('create_time>=:begin_time and create_time<:end_time and customer_id=:customer_id and status=1 and effective=1',array(':begin_time'=>$beginTime,':end_time'=>$endTime,':customer_id'=>$userId));
           return $n;
        }else{
           return false;
        }
    }
    /*
     * @version 彩富人生用户
     * @param $userId
     * return int
     */
    public function getCaiFu($userId){
        if(empty($userId)){
            return false;
        }
        $propertyArr=PropertyActivity::model()->find('customer_id=:customer_id and (status=96 or status=99)',array(':customer_id'=>$userId));
        $appreciationArr=AppreciationPlan::model()->find('customer_id=:customer_id and (status=96 or status=99)',array(':customer_id'=>$userId));
        if(!empty($propertyArr) || !empty($appreciationArr)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 返回url
     * @param $userId
     * return array
     */
    public function getAllUrl($userId){
        if(empty($userId)){
            return false;
        }
        $SetableSmallLoansModel = new SetableSmallLoans();
        //京东url
        $href = $SetableSmallLoansModel->searchByIdAndType(67, '', $userId);
        if ($href) {
            $jdHref = $href->completeURL;
        }
        else {
            $jdHref = '';
        }
        //环球精选url
        $href2 = $SetableSmallLoansModel->searchByIdAndType(38, '', $userId);
        if ($href2) {
            $hqHref = $href2->completeURL;
        }
        else {
            $hqHref = '';
        }
        //彩生活特供
        $href3 = $SetableSmallLoansModel->searchByIdAndType(39, '', $userId);
        if ($href3) {
            $tgHref = $href3->completeURL;
        }
        else {
            $tgHref = '';
        }
        return array(
            'jdHref'=>$jdHref,
            'hqHref'=>$hqHref,
            'tgHref'=>$tgHref,
        );
    }
    /*
     * @version 获取京东的最新价格
     * return array
     */
    public function getJdPrice(){
        if(!empty($this->jdProduct)){
            $price=array();
            foreach ($this->jdProduct as $product){
                $productPriceJson=JdApi::model()->getProductXyPrice($product);
                $productPriceArr=json_decode($productPriceJson,true);
                $price[$product]=$productPriceArr['result'][0]['price'];
            }
            return $price;
        }else{
            return false;
        }
    }
    /*
     * @version 获取产品id和开始时间
     * return array
     */
    public function getCheapLog(){
        $beginTime = mktime(0,0,0);
        $endTime = time()-3600*9;
        if($beginTime>$endTime){
            $beginTime= mktime(0,0,0)-3600*24;
            $endTime=time()-3600*9;
        }
//        $beginTime=1461772800;
//        $endTime=1461805500;
        $sql="select * from cheap_log where begin_time>=".$beginTime." and begin_time<=".$endTime." and is_deleted=0 and status=0";
        $cheapArr=Yii::app()->db->createCommand($sql)->queryAll();
//        $cheapArr=CheapLog::model()->findAll('begin_time>=:beginTime and begin_time<:endTime and is_deleted=0 and status=0',array(':beginTime'=>$beginTime,':endTime'>=$endTime));
        return $cheapArr;
    }
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
/***************************************************彩生活特供********************************************************/
    /*
     * @version 通过产品id和用户id来判断是否可以购买和提交订单
     * @param int $goods_id
     * @param int $userId
     * return boolean
     */
    public function getYaoQingNum($goods_id,$userId){
        if(empty($goods_id) || empty($userId)){
            return false;
        }
        $all=  array_merge($this->a,$this->b,$this->c,$this->d);
        if(in_array($goods_id, $all)){
            $now=time();
            $cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0 and status=0',array(':goods_id'=>$goods_id));
            $beginTime=$cheapArr->begin_time+$this->begin_time_other;
            $endTime=$cheapArr->end_time-$this->end_time_other;
            if($now<$beginTime || $now>$endTime){
                return false;
            }elseif(in_array($goods_id, $this->b)){
                $n=Invite::model()->count('create_time>=:begin_time and create_time<:end_time and customer_id=:customer_id and status=1 and effective=1',array(':begin_time'=>$beginTime,':end_time'=>$endTime,':customer_id'=>$userId));
                if($n<3){
                    return false;
                }
            }elseif(in_array($goods_id, $this->c)){
                $n=Invite::model()->count('create_time>=:begin_time and create_time<:end_time and customer_id=:customer_id and status=1 and effective=1',array(':begin_time'=>$beginTime,':end_time'=>$endTime,':customer_id'=>$userId));
                if($n<6){
                    return false;
                }
            }elseif(in_array($goods_id, $this->d)){
                $n=$this->getCaiFu($userId);
                if(!$n){
                    return false;
                }
            }
            return true;
        }else{
            return true;
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
            $sql="select * from `order` o left join order_goods_relation ogr on o.id=ogr.order_id where o.seller_id=".$this->xfzgx." and o.`status` in(0,1,3,4,99) and o.buyer_id=".$userId." and ogr.goods_id=".$goods_id;
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
     * @version 限定的产品只能购买一个,数量限制一个
     * @param int $goods_id
     * return boolean
     */
    public function getOne($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $all=  array_merge($this->a,$this->b,$this->c,$this->d);
         if(in_array($goods_id, $all)){
             return true;
         }else{
             return false;
         }
    }
    /*
     * @version 时时获取库存信息
     * @param int $goods_id
     */
    public function getKuCun($goods_id){
        if(empty($goods_id)){
            return false;
        }
//        $goodsArr=Goods::model()->findByPk($goods_id);
        $sqlSelect="select ku_cun from goods where id=".$goods_id." for update"; 
        $query = Yii::app()->db->createCommand($sqlSelect);
        $result = $query->queryAll();
        return $result[0]['ku_cun'];
    }
    /*
     * @version 记录分享
     * @param int $customer_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$type)
    {
        $shareLog =new WuIndexLog();
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
