<?php
/*
 * @version 司庆接口
 */
class SiQing extends CActiveRecord{
    private $beginTime='2016-07-18 09:00:00';
    private $endTime='2016-08-30 23:59:59';

    //本地环境
//  private $good_Ids = array (
//      '0' =>24379,
//      '1' =>24380,
//      '2' =>24380,
//      '3' =>24380,
//      '4' =>24380,
//  );
    //测试环境
//	  private $good_Ids = array (
//        '0' =>29731,
//        '1' =>29732,
//        '2' =>29733,
//        '3' =>29734,
//        '4' =>29735,
//    );
    //正式环境
//    private $good_Ids = array (
//        '0' =>31831,
//        '1' =>31833,
//        '2' =>31851,
//        '3' =>31835,
//        '4' =>31849,
//    );
    //二期正式环境产品
//    private $good_Ids = array (
//        '0' =>31834,
//        '1' =>31960,
//        '2' =>31961,
//        '3' =>31962,
//        '4' =>31963,
//        '5' =>31964,
//        '6' =>31967,
//        '7' =>31966,
//        '8' =>31969,
//        '9' =>31970,
//        '10' =>31971,
//        '11' =>31972,
//    );
    //正式环境3产品
    private $good_Ids = array (
        '0' =>32727,
        '1' =>32728,
        '2' =>32729,
        '3' =>32730,
        '4' =>32731,
        '5' =>32732,
        '6' =>32733,
        '7' =>32734,
    );
    //本地  
//    private $banner_count=array(
//        '24379'=>0,
//        '24380'=>0,
//        '24379'=>0,
//        '24379'=>0,
//        '24379'=>0,
//    );
    //测试环境banner数量
//	private $banner_count = array (
//        29731=>3,
//        29732=>3,
//        29733=>3,
//        29734=>3,
//        29735=>3,
//    );
    //正式
//    private $banner_count = array (
//        31831=>3,
//        31833=>3,
//        31851=>3,
//        31835=>3,
//        31849=>3,
//    );
    //二期正式环境
//    private $banner_count = array (
//        31834=>3,
//        31960=>3,
//        31961=>3,
//        31962=>3,
//        31963=>3,
//        31964=>3,
//        31967=>3,
//        31966=>3,
//        31969=>3,
//        31970=>3,
//        31971=>3,
//        31972=>3,
//    );
    //正式环境3
    private $banner_count = array (
        32727=>3,
        32728=>3,
        32729=>3,
        32730=>3,
        32731=>3,
        32732=>3,
        32733=>3,
        32734=>3,
    );
    private $shopSiQing=5045;
    //订单状态
    private $statusArr=array(
        '0'=>'未付款',
        '1'=>'已支付',
        '3'=>'已支付',
        '4'=>'已支付',
        '99'=>'交易成功',
        '96'=>'交易失败',
        '97'=>'订单已取消',
        '102'=>'已退款',
    );
    //获得方式
    private $getTypeArr=array(
        '1'=>'参与问卷',
        '2'=>'邀请注册',
        '3'=>'彩富人生',
        '4'=>'购买下单',
    );
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /**********************************************首页**************************************************************/
    /*
     * @version 返回当前对应的全部产品
     * return array
     */
    public function getAllProduct(){
        $goodsArr=$this->good_Ids;
        if(!empty($goodsArr)){
            $productList=array();
            foreach ($goodsArr as $key=>$val){
                $productArr=$this->getProductDetail($val);
                $productList[$key]['name']=$productArr['name'];
                $productList[$key]['img_url']=$this->getProductImage($val);
                $productList[$key]['url']='/goods/'.$val;
                $siQingProductArr=$this->getSiqingProductConfig($val);
                $productList[$key]['total_price']=$siQingProductArr['total_price'];
                $productList[$key]['total_num']=$siQingProductArr['total_num'];
                $productList[$key]['price']=floatval($siQingProductArr['price']);
                $productList[$key]['num']=$this->getDuoBaoNum($val);
            }
            return $productList;
        }else{
            return false;
        }
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
    /*
     * @version 倒计时时间
     * return int 时间戳
     */
    public function getDaoJiShi(){
        $now=time();
        $cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0 and status=0',array(':goods_id'=>$this->good_Ids[0]));
        $endTime=$cheapArr['end_time'];
        $daoJiShi=$endTime-$now;
        return $daoJiShi;
    }
    /*
     * @version 倒计时时间
     * return int 时间戳
     */
    public function getKaiJiangJiShi(){
        $now=time();
        $cheapArr=CheapLog::model()->find('goods_id=:goods_id and is_deleted=0',array(':goods_id'=>$this->good_Ids[0]));
        $endTime=$cheapArr['end_time'];
        $kaiJiangJiShi=$endTime+(24*3600)-$now;
        return $kaiJiangJiShi;
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
     * @version 通过产品Id获取产品的夺宝配置信息
     * @param int $goods_id
     * return array
     */
    public function getSiqingProductConfig($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $siQingProductArr=SiqingProductConfig::model()->find('goods_id=:goods_id',array(':goods_id'=>$goods_id));
        return $siQingProductArr;
    }
    /*
     * @version 通过产品id获取夺宝的数量
     * @param int $goods_id
     * return int
     */
    public function getDuoBaoNum($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $m=SiqingCode::model()->count('goods_id=:goods_id and get_type!=0',array('goods_id'=>$goods_id));
        return $m;
    }
    /*
     * @version 通过用户id获取邀请弹框
     * @param int $customer_id
     */
    public function getRegisterTan($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $sql="select * from siqing_code where get_type=2 and is_tan=0 and customer_id=".$customer_id." order by goods_id";
        $registerArr=Yii::app()->db->createCommand($sql)->queryAll();
        return $registerArr;
    }
    /*
     * @version 通过用户id获取彩富下单弹框
     * @param int $customer_id
     */
    public function getCaiFuTan($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $sql="select * from siqing_code where get_type=3 and is_tan=0 and customer_id=".$customer_id." order by goods_id";
        $caiFuArr=Yii::app()->db->createCommand($sql)->queryAll();
        return $caiFuArr;
        
    }
    /**********************************************产品详情页************************************/
    /*
     * @version 通过用户id获取夺宝码详情
     * @param int $goods_id
     * @param int $customerId
     * return array
     */
    public function getCodeDetail($goods_id,$customerId){
        if(empty($goods_id) || empty($customerId)){
            return false;
        }
        $siQingCodeArr=SiqingCode::model()->findAll('goods_id=:goods_id and customer_id=:customer_id and get_type!=0',array(':goods_id'=>$goods_id,':customer_id'=>$customerId));
        return $siQingCodeArr;
    }
    /*
     * @version 通过订单状态返回订单状态名称
     * @param int $getType
     * return string
     */
    public function getTypeName($getType){
        $getTypeName=$this->getTypeArr[$getType];
        if(empty($getTypeName)){
            return '未知状态';
        }else{
            return $getTypeName;
        }
    }
    /**********************************订单列表和订单详情****************************************/
    /*
     * @version 通过用户id获取拼团的所有订单
     * @param int $userId
     * return array
     */
    public function getPinOrderList($userId){
        if(empty($userId)){
            return false;
        }
        $sql="select * from `order` o left join order_goods_relation ogr on o.id=ogr.order_id  where o.buyer_model='customer' and o.seller_id=".$this->shopSiQing." and o.buyer_id=".$userId."  and o.is_lock=0";
        $orderArr=Yii::app()->db->createCommand($sql)->queryAll();
        return $orderArr;
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
        $sql="select * from `order` o left join order_goods_relation ogr on o.id=ogr.order_id  where o.buyer_model='customer' and o.seller_id=".$this->shopSiQing." and o.id=".$orderId;
        $orderDetailArr=Yii::app()->db->createCommand($sql)->queryAll();
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
    /*************************************获取夺宝码************************************************/
    /*
     * @version 获取夺宝码
     * @param int $goods_id
     * @param int $customer_id
     * @param int $get_type
     * @param string $type_sign
     * @param int $num
     * return
     */
    public function getSiQingCode($goods_id,$customer_id,$get_type,$type_sign){
        if(empty($customer_id) || empty($get_type) || empty($type_sign)){
            return false;
        }
        $siQingCode=SiqingCode::model()->find('goods_id=:goods_id and customer_id=:customer_id and get_type=:get_type and type_sign=:type_sign',array(':goods_id'=>$goods_id,':customer_id'=>0,':get_type'=>0,':type_sign'=>''));
        if(empty($siQingCode)){
            $status=2;
            $this->addCodeLog($goods_id,$customer_id,$get_type,$type_sign,$status);
        }else{
            $siQingCode->goods_id = $goods_id;
            $siQingCode->customer_id = $customer_id;
            $siQingCode->get_type = $get_type;
            $siQingCode->type_sign = $type_sign;
            $siQingCode->create_time = time();
            if (!$siQingCode->update()) {
                $status=0;
            }else{
                $status=1;
            }
            $this->addCodeLog($goods_id,$customer_id,$get_type,$type_sign,$status);
        }
        
    }
    /*
     * @version 通过产品id和数量获取是否有夺宝码
     * @param int $goods_id
     * @param int $num
     * return boolean
     */
    public function getCodeStock($goods_id,$num){
        if(empty($goods_id) || empty($num)){
            return false;
        }
        $m=SiqingCode::model()->count('goods_id=:goods_id and get_type=:get_type and type_sign=:type_sign',array(':goods_id'=>$goods_id,':get_type'=>0,':type_sign'=>''));
        if($m>=$num){
            return true;
        }else{
            return false;
        }
    }
    /*
     *@version 添加发送夺宝码日志
     */
    public function addCodeLog($goods_id,$customer_id,$get_type,$type_sign,$status){
        $codeLog=new SiqingCodeLog();
        $codeLog->goods_id=$goods_id;
        $codeLog->customer_id=$customer_id;
        $codeLog->get_type=$get_type;
        $codeLog->type_sign=$type_sign;
        $codeLog->status=$status;
        $codeLog->create_time=time();
        $codeLog->save();
    }
    /*************************************问卷调查*********************************************************/
    /*
     * @version 通过用户id来判断是否已经答过题目
     * @param $customer_id
     * return boolean
     */
    public function checkAnswer($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $answerArr=SiqingCode::model()->find('customer_id=:customer_id and get_type=1',array(':customer_id'=>$customer_id));
        if(!empty($answerArr)){
            return false;
        }else{
            return true;
        }
    }
    /*
     * @version 随机获取产品数组，根据是否有夺宝码
     * return array
     */
    public function getGoodsArray(){
        $siQingCode0=SiqingCode::model()->find('goods_id=:goods_id and customer_id=:customer_id and get_type=:get_type and type_sign=:type_sign',array(':goods_id'=>$this->good_Ids[0],':customer_id'=>0,':get_type'=>0,':type_sign'=>''));
        $siQingCode1=SiqingCode::model()->find('goods_id=:goods_id and customer_id=:customer_id and get_type=:get_type and type_sign=:type_sign',array(':goods_id'=>$this->good_Ids[1],':customer_id'=>0,':get_type'=>0,':type_sign'=>''));
        $siQingCode3=SiqingCode::model()->find('goods_id=:goods_id and customer_id=:customer_id and get_type=:get_type and type_sign=:type_sign',array(':goods_id'=>$this->good_Ids[3],':customer_id'=>0,':get_type'=>0,':type_sign'=>''));
        if(empty($siQingCode0)){
            unset($this->good_Ids[0]);
        }
        if(empty($siQingCode1)){
            unset($this->good_Ids[1]);
        }
            
        if(empty($siQingCode3)){
            unset($this->good_Ids[3]);
        }
        unset($this->good_Ids[2]);
        unset($this->good_Ids[4]);
        unset($this->good_Ids[5]);
        unset($this->good_Ids[6]);
        unset($this->good_Ids[7]);
        return $this->good_Ids;
    }
    /*
     * @version 彩富人生获得一个固定的产品的夺宝码
     * return array
     */
    public function getCaiFuGoods(){
        $siQingCaiFuCode=SiqingCode::model()->find('goods_id=:goods_id and customer_id=:customer_id and get_type=:get_type and type_sign=:type_sign',array(':goods_id'=>$this->good_Ids[7],':customer_id'=>0,':get_type'=>0,':type_sign'=>''));
        return $siQingCaiFuCode;
    }
    
    /*
     * @version 用户通过答题(问卷调查)获取一个夺宝码
     * @param int $customer_id
     * return boolean
     */
    public function getCodeByAnswer($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $currentGoodsArr=$this->getCaiFuGoods();
        if(empty($currentGoodsArr)){
            return false;
        }
        $this->getSiQingCode($currentGoodsArr['goods_id'],$customer_id,1,'问卷调查');
        $res=SiqingCodeLog::model()->find('customer_id=:customer_id and get_type=1 and status=1',array(':customer_id'=>$customer_id));
        if(!empty($res)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 插入问卷的答案
     * @param int $customer_id
     * @param array $answerArr
     * return boolean
     */
    public function insertAnswer($customer_id,$answerArr){
        if(empty($customer_id) || empty($answerArr)){
            return false;
        }
        $str='('.$customer_id.',';
        foreach ($answerArr as $key=>$v){
            $str .='"'.$v['options'].'",';
        }
        $now=time();
        $str.=$now.')';
        $sql="insert into siqing_wenquan(customer_id,answer_1,answer_2,answer_3,answer_4,answer_5,answer_6,answer_7,answer_8,answer_9,answer_10,create_time) values {$str}";
        $res = Yii::app()->db->createCommand($sql)->execute();
        if($res){
            return true;
        }else{
            return false;
        }
    }
    /******************************订单夺宝码*************************************************/
    /*
     * @version 通过订单号获取夺宝码
     * @param int $order_id
     * return array
     */
    public function getOrderCode($order_id){
        if(empty($order_id)){
            return false;
        }
        $orderCodeArr=SiqingCode::model()->findAll('type_sign=:type_sign',array(':type_sign'=>$order_id));
        return $orderCodeArr;
    }
}

