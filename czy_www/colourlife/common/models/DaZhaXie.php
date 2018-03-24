<?php
/*
 * @version 大闸蟹接口
 * @copyright(c) 2015-09-09 josen
 */
class DaZhaXie extends CActiveRecord{
    
    //客服定义的时间日期
    public $endDate='2019-01-01 23:59:59';
    //提货券使用时间
    public $thqDate='2015-09-25 00:00:00';
    public $status=array('1'=>'待发货','2'=>'已发货','3'=>'已收货');
    
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    /*
     * @version
     * @param int $uid
     */
    public function getMobile($uid){
        if(empty($uid)){
            return false;
        }
        $resultArr=Customer::model()->findByPk($uid);
        return $resultArr['mobile'];
    }
    /*
     * @version 随机产生一个提货券编码
     */
    public function randomThq($length, $chars = '0123456789') {
        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }
    /*
     * @version 根据产品获取商品属性
     * @param int $goods_id
     * @param int $type 1:数值;2:字符串
     * @return string/int
     */
    public function getGoodsType($goods_id,$type){
        if(empty($goods_id) || empty($type)){
            return false;
        }
        $goodsArr=Goods::model()->findByPk($goods_id);
        if($goodsArr['goods_type']==1){
            if($type==1){
                return $goodsArr['goods_type'];
            }else{
                return "产品";
            }
        }else{
            if($type==1){
                return $goodsArr['goods_type'];
            }else{
                return "提货券";
            }
        }
    }
    /*
     * @version 产生提货券，执行插入操作
     * @param int $order_id
     * @return boolean
     */
    public function createTiHuoQuan($order_id){
        if(empty($order_id)){
            return false;
        }
        $orderArr=Order::model()->findByPk($order_id);
        $orderGoodsArr=OrderGoodsRelation::model()->find('order_id=:order_id',array(':order_id'=>$order_id));
        $mobile=$this->getMobile($orderArr['buyer_id']);
        $is_use=1;
        $num=1;
        $create_time=time();
        $str = '';
        for($i=0;$i<$orderGoodsArr['count'];$i++){
            $thq_code="THQ".$this->randomThq(10);
            $str .= "(1,'" . $thq_code."','".$mobile."',".$is_use.",".$num.",".$order_id.",".$create_time."),";
        }
        if (!empty($str)){
            $str = trim($str, ',');
            $sql = "insert into user_ti_huo_quan(type,thq_code,mobile,is_use,num,order_id,create_time) values {$str}";
            $res = Yii::app()->db->createCommand($sql)->execute();
            if($res){
                return true;
            }else{
                return false;
            }
        }
    }
    /*
     * @version 根据状态和返回的时间获取提货券状态
     * @param int $is_use
     * @return string
     */
    public function getThqStatus($is_use){
        if(empty($is_use)){
            return false;
        }
        $endDataInt=strtotime($this->endDate);
        $now=time();
        if($now>$endDataInt){
            return '已过期';
        }elseif ($is_use==1) {
            return '未使用';
        }elseif($is_use==2){
            return '已使用';
        }elseif($is_use==3){
            return '已赠送';
        }
    }
    /*
     * @version 根据订单id获取产品信息
     * @param int $order_id
     */
    public function getOrderGoods($order_id){
        if(empty($order_id)){
            return false;
        }
        $orderGoodsArr=  OrderGoodsRelation::model()->find('order_id=:order_id',array(':order_id'=>$order_id));
        return $orderGoodsArr;
    }
    /*
     * @version 根据订单id获取产品信息
     * @param int $goods_id
     * @return array
     */
    public function getGoodsImage($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $goodsArr=Goods::model()->findByPk($goods_id);
        return F::getUploadsUrl("/images/" . $goodsArr['good_image']);
    }
    
    /*
     * @version 根据订单id获取提货券信息
     * @param int $order_id
     * @param int $mobile
     * @return array
     */
    public function getThqDetail($order_id,$mobile){
        if(empty($order_id) || empty($mobile)){
            return false;
        }
        $sql="select * from user_ti_huo_quan where order_id=".$order_id." and mobile=".$mobile;
        $resultArr=Yii::app()->db->createCommand($sql)->queryAll();
        return $resultArr;
    }
    /*
     * @version 通过兑换的提货券算总金额
     * @param string $id
     * @return decimal 总金额
     */
    public function getAmount($id){
        if(empty($id)){
            return false;
        }
        $amount=0;
        $sql="select * from user_ti_huo_quan uthq LEFT JOIN order_goods_relation ogr on uthq.order_id= ogr.order_id where uthq.id in(".$id.")";
        $resultArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($resultArr)){
            foreach ($resultArr as $result){
                $amount+=$result['num']*$result['price'];
            }
            return $amount;
        }else{
            return 0;
        }
    }
    /*
     * @version 通过提货券订单id获取订单详情
     * @param int $order_id
     * @return array
     */
    public function getThqOrderGoodsDetail($order_id){
        if(empty($order_id)){
            return false;
        }
        $thqOrderGoodsArr=ThqOrderGoods::model()->findAll('thq_order_id=:thq_order_id',array(':thq_order_id'=>$order_id));
        return $thqOrderGoodsArr;
    }
    /*
     * @version 根据提货券订单id获取订单的状态
     * @param int $order_id 订单id
     * @param int $uid
     * @return string
     */
    public function getOrderThqStatus($order_id,$uid){
        if(empty($order_id) || empty($uid)){
            return false;
        }
        $mobile=$this->getMobile($uid);
        $endDate=strtotime($this->endDate);
        $now=time();
        $sql="select count(*) from user_ti_huo_quan where order_id=".$order_id." and mobile=".$mobile." and is_use=1";
        $count = Yii::app()->db->createCommand($sql)->queryScalar();
        if($count>=1){
            return '未使用';
        }elseif($endDate<$now){
            return '已过期';
        }  elseif ($count==0) {
            return '已使用';
        }
    }
    /*
     * @version 统计大闸蟹提货券的数量
     * @param string $mobile
     */
    public function getThqNum($mobile){
        if(empty($mobile)){
            return false;
        }
        $sql="select count(*) from user_ti_huo_quan uthq LEFT JOIN `order` o on uthq.order_id=o.id where uthq.mobile='".$mobile."' and o.`status` in (1,3,4,99) and o.order_type=2";
        $count = Yii::app()->db->createCommand($sql)->queryScalar();
        return $count;
    }
    /*
     * @version 根据产品的id获取产品对应的详情图片
     * @param int $product_id
     */
    public function getProductImage($product_id){
        if(empty($product_id)){
            return false;
        }
        if($product_id==20758 || $product_id==20759){
            return F::getStaticsUrl('/dazhaxie/images/shop_02.png');
        }
        if($product_id==20760 || $product_id==20762){
            return F::getStaticsUrl('/dazhaxie/images/shop_03.png');
        }
        if($product_id==20761 || $product_id==20763){
            return F::getStaticsUrl('/dazhaxie/images/shop_01.png');
        }
    }
    
}

