<?php
/*
 * @version 京东接口api
 * @copyright(c) 2015-05-20 josen
 */
class JdApi extends CActiveRecord{
    
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    /**
     * @varsion 证书验证
     * @param string $url 接收数据的api
     * @param string $data 提交的数据
     * @param int $second 要求程序必须在$second秒内完成,负责到$second秒后放到后台执行
     * @return json
     */
    public function curl_post_ssl($url, $data,$second=30){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_TIMEOUT, $second);
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
           echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        //echo json_encode($tmpInfo); // 返回数据
//        return json_decode($tmpInfo);
        return  $tmpInfo;
    }
    /*
     * @version 获取AccessToken
     */
    private function getAccessToken(){
        $grant_type='access_token';
        $client_id='colorlife';
        $client_secret='Nd56Qb0YQNzP0QibyoPT';
        $timestamp=date('Y-m-d H:i:s',time());
        $username='彩生活vop';
        $password=md5('123123');
        $scope='';
        $sign=$client_secret.$timestamp.$client_id.$username.$password.$grant_type.$scope.$client_secret;
        $sign = strtoupper(md5($sign));
        $data='grant_type='.$grant_type.'&client_id='.$client_id.'&scope=&username='.$username.'&password='.$password.'&timestamp='.$timestamp.'&sign='.$sign;
        $url='https://bizapi.jd.com/oauth2/access_token';
        $result = $this->curl_post_ssl($url,$data,$second=30);
        $resultArr=json_decode($result,true);
        $refresh_expires=(string)$resultArr['result']['refresh_token_expires'];
        $refresh_token_expires= (int)substr($refresh_expires, 0, 10);
        $refresh_time=(string)$resultArr['result']['time'];
        $time= (int)substr($refresh_time, 0, 10);
        if($resultArr['success']==true){
            $sql="insert into jd_access_refresh_token(uid,access_token,refresh_token,time,expires_in,refresh_token_expires) values('".$resultArr['result']['uid']."','".$resultArr['result']['access_token']."','".$resultArr['result']['refresh_token']."',".$time.",".$resultArr['result']['expires_in'].",".$refresh_token_expires.")";
            $execute=Yii::app()->db->createCommand($sql)->execute();
            if($execute){
                return $result;
            }
        }
    }
    /*
     * @version 使用Refresh Token刷新Access Token
     */
    public function refreshAccessToken() {
        $sql="select * from jd_access_refresh_token";
        $queryArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($queryArr)){
            $refresh_token=$queryArr[0]['refresh_token'];
        }else{
            $result=$this->getAccessToken();
            $resultArr=json_decode($result,true);
            $refresh_token= $resultArr['result']['refresh_token'];
        }
        $client_id='colorlife';
        $client_secret='Nd56Qb0YQNzP0QibyoPT';
        $url='https://bizapi.jd.com/oauth2/refresh_token';
        $data='refresh_token='.$refresh_token.'&client_id='.$client_id.'&client_secret='.$client_secret;
        return  $re = $this->curl_post_ssl($url,$data,$second=30);
    }
    /*
     * @version 获取商品池编号
     */
    public function getProductNum(){
        $token=$this->getAccessTokenByData();
        $url='http://bizapi.jd.com/api/product/getPageNum';
        $data='token='.$token;
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 获取池内商品编号
     */
    public function getProductSku(){
        $token=$this->getAccessTokenByData();
        $productNumJson=$this->getProductNum();
        $productNumArr=json_decode($productNumJson,true);
        
        if($productNumArr['success']==true){
            foreach ($productNumArr as $key=>$val){
                if($key=='result'){
                    foreach ($val as $k=>$v){
                        $data='token='.$token.'&pageNum='.$v['page_num'];
                        $url='http://bizapi.jd.com/api/product/getSku';
                        $result=$this->curl_post_ssl($url,$data,$second=30);
                        $resultArr[$v['page_num']]=json_decode($result,true);
                    }
                    return $resultArr;
                }
            }
        }else{
            return false;
        }
    }
    /*
     * @version 通过商品sku获取商品详细信息
     * @param int $productSku 商品sku
     */
    public function getProductDetail($productSku){
        if(empty($productSku)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&sku='.$productSku;
        $url='http://bizapi.jd.com/api/product/getDetail';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 通过单个商品sku获取商品价格
     * @param int $productSku 商品sku
     */
    public function getProductPrice($productSku){
        if(empty($productSku)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&sku='.$productSku;
        $url='http://bizapi.jd.com/api/price/getJdPrice';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 通过单个商品sku获取商品图片(主图)
     * @param int $productSku 商品sku
     */
    public function getProductImage($productSku){
        if(empty($productSku)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&sku='.$productSku;
        $url='http://bizapi.jd.com/api/product/skuImage';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 获取商品上下架状态接口(商品详情进行调用)
     * @param  $productSku 商品sku
     */
    public function getProductState($productSku){
        if(empty($productSku)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&sku='.$productSku;
        $url='http://bizapi.jd.com/api/product/skuState';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 获取一级地址
     */
    public function getProvince(){
        $token=$this->getAccessTokenByData();
        $data='token='.$token;
        $url='http://bizapi.jd.com/api/area/getProvince';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 获取二级地址
     * @param int $provinceId 一级地址ID
     */
    public function getCity($provinceId){
        if(empty($provinceId)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&id='.$provinceId;
        $url='http://bizapi.jd.com/api/area/getCity';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 获取三级地址
     * @param int $cityId 二级地址ID
     */
    public function getCounty($cityId){
        if(empty($cityId)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&id='.$cityId;
        $url='http://bizapi.jd.com/api/area/getCounty';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 获取四级地址
     * @param int $countyId 三级地址ID
     */
    public function getTown($countyId){
        if(empty($countyId)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&id='.$countyId;
        $url='http://bizapi.jd.com/api/area/getTown';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 商品区域购买限制查询(商品详情页)
     * @param int productSku 商品sku
     * @param int province 一级地址编号
     * @param int city 二级地址编号
     * @param int county 三级地址编号
     * 
     */
    public function checkAreaLimit($productSku,$province,$city,$county){
        if(empty($productSku) || empty($province)|| empty($city) || empty($county)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&skuIds='.$productSku.'&province='.$province.'&city='.$city.'&county='.$county;
        $url='http://bizapi.jd.com/api/product/checkAreaLimit';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 获取商品库存(商品详情页)
     * @param string skuNums 商品和数量(格式：[{skuId:1366087,num:1}])
     * @param string area 一二三级地址(格式：1_0_0)
     */
    public function getSkuStock($skuNums,$area){ //getSkuStock("[{skuId:1366087,num:1}]",'16_1303_3484');
        if(empty($skuNums) || empty($area)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&skuNums='.$skuNums.'&area='.$area;
        $url='http://bizapi.jd.com/api/stock/getNewStockById';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    
    /*
     * @version 预存款京东价下单
     * @param string thirdOrder 第三方的订单单号
     * @param string sku 商品和数量(格式：[{skuId:1366087,num:1}])
     * @param string name 收货人
     * @param int province 一级地址
     * @param int city 二级地址
     * @param int county 三级地址
     * @param int town 四级地址(如果该地区有四级地址，则必须传递四级地址)
     * @param string address 详细地址
     * @param string zip 邮编
     * @param string phone 座机号 (与mobile其中一个有值即可)
     * @param string mobile 手机号 (与phone其中一个有值即可)
     * @param string email 邮箱
     * @param string remark 备注（少于100字）非必须
     * @param int invoiceState 开票方式(1为随货开票，0为订单预借，2为集中开票 )--------1
     * @param int invoiceType 1普通发票2增值税发票---------1
     * @param int selectedInvoiceTitle 4个人，5单位-------------4
     * @param string companyName 发票抬头 (如果selectedInvoiceTitle=5则此字段必须)
     * @param int invoiceContent 1:明细，3：电脑配件，19:耗材，22：办公用品 备注:若增值发票则只能选1 明细-----------------1
     * @param int paymentType 4:在线支付 1：货到付款（如果选择货到付款，则不要传isUseBalance参数）---------------4
     * @param int isUseBalance 此值固定是1 使用余额----------1
     * @param int submitState 是否预占库存，0是预占库存（需要调用确认订单接口），1是不预占库存-------------0
     */
    public function jdPriceSubmit($thirdOrder){
        $queryArr=Order::model()->find('sn=:sn',array(':sn'=>$thirdOrder));
        $queryArr2=OrderGoodsRelation::model()->findAll('order_id=:order_id',array(':order_id'=>$queryArr['id']));
        $sku='';
        if(!empty($queryArr2)){
            $sku='[';
            foreach ($queryArr2 as $query2){
                $queryArr3=Goods::model()->findByPk($query2['goods_id']);
                $skuId=$queryArr3['sku'];
                $num=$query2['count'];
                $sku.='{"skuId":'.$skuId.',"num":'.$num.'},';
            }
            $sku=trim($sku,',');
            $sku.=']';
        }
        $name=$queryArr['buyer_name'];
        $province=$queryArr['province'];
        $city=$queryArr['city'];
        $county=$queryArr['county'];
        $town=$queryArr['town'];
        $addressString=$queryArr['buyer_address'];
        $addressDetail=$this->start($addressString,1);
        $zip=trim($queryArr['buyer_postcode']);
        $mobile=trim($queryArr['buyer_tel']);
        $email='caishenhuo@126.com';
        
        $invoiceState=2;
        if($queryArr['seller_id']==Item::GOUWUKA){
            $invoiceType=1;
        }else{
            $invoiceType=2;
        }
        
        $selectedInvoiceTitle=4;
        $invoiceContent=1;
        $paymentType=4;
        $isUseBalance=1;
        $submitState=1;
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&sku='.$sku.'&thirdOrder='.$thirdOrder.'&name='.$name.'&province='.$province.'&city='.$city.'&county='.$county.'&town='.$town.'&address='.$addressDetail.'&zip='.$zip.'&mobile='.$mobile.'&email='.$email.'&invoiceState='.$invoiceState.'&invoiceType='.$invoiceType.'&selectedInvoiceTitle='.$selectedInvoiceTitle.'&invoiceContent='.$invoiceContent.'&paymentType='.$paymentType.'&isUseBalance='.$isUseBalance.'&submitState='.$submitState;
        $url='http://bizapi.jd.com/api/order/submit';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }

    //预缴费活动自动发货
    public  function PropertySend($thirdOrder){
        $queryArr = PropertyPrizeAddress::model()->find('sn=:sn', array(':sn'=>$thirdOrder ));
        //获取商品表中的京东商品的sku
        if(!empty($queryArr)){
            $sku='[';
            $row=PropertyPrize::model()->findByPk($queryArr['prize_id']);
            $queryArr3=Goods::model()->findByPk($row['goods_id']);
            $skuId=$queryArr3['sku'];
            $num=1;
            $sku.='{"skuId":'.$skuId.',"num":'.$num.'},';
            $sku=trim($sku,',');
            $sku.=']';
        }
        //将地址拆开
        $arr_address=explode(' ',$queryArr['address_id']);
        $name=$queryArr['username'];

        $province=intval($arr_address[0]);
        $city=intval($arr_address[1]);
        $county=intval($arr_address[2]);
        $town=intval($arr_address[3]);

        $addressde=explode(' ',$queryArr['address']);
        $addressDetail= $addressde[4];

        $zip=$queryArr['zip'];
        $mobile=trim($queryArr['mobile']);
        $email='www.111@colourlife.com';
        $invoiceState=2;
        $invoiceType=2;
        $selectedInvoiceTitle=4;
        $invoiceContent=1;
        $paymentType=4;
        $isUseBalance=1;
        $submitState=1;
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&sku='.$sku.'&thirdOrder='.$thirdOrder.'&name='.$name.'&province='.$province.'&city='.$city.'&county='.$county.'&town='.$town.'&address='.$addressDetail.'&zip='.$zip.'&mobile='.$mobile.'&email='.$email.'&invoiceState='.$invoiceState.'&invoiceType='.$invoiceType.'&selectedInvoiceTitle='.$selectedInvoiceTitle.'&invoiceContent='.$invoiceContent.'&paymentType='.$paymentType.'&isUseBalance='.$isUseBalance.'&submitState='.$submitState;
        $url='http://bizapi.jd.com/api/order/submit';
        $result=$this->curl_post_ssl($url,$data,$second=30);
       //dump(json_decode($result));
        return $result;
    }
    
    /*
     * @version  取消未确定订单接口
     */
    public function orderCancel($jdOrderId){
        if(empty($jdOrderId)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&jdOrderId='.$jdOrderId;
        $url='http://bizapi.jd.com/api/order/cancel';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version  查询京东的配送信息
     */
    public function orderTrack($jdOrderId){
        if(empty($jdOrderId)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&jdOrderId='.$jdOrderId;
        $url='http://bizapi.jd.com/api/order/orderTrack';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 查询用户余额
     */
    public function selectBalance(){
        $token=$this->getAccessTokenByData();
        $data='token='.$token;
        $url='http://bizapi.jd.com/api/price/selectBalance';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 根据商品池编号获取商品编号
     * @param 商品池编号
     */
    public function getProductSkuByPageNum($page_num){
        if(empty($page_num)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&pageNum='.$page_num;
        $url='http://bizapi.jd.com/api/product/getSku';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
        
    }
    
    
    /*
     * @version 根据商品池编号获取商品编号
     * @param 商品池编号
     */
    public function selectJdOrderIdByThirdOrder($thirdOrder){
        if(empty($thirdOrder)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&thirdOrder='.$thirdOrder;
        $url='https://bizapi.jd.com/api/order/selectJdOrderIdByThirdOrder';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 从数据库获取access_token
     */
    public function getAccessTokenByData(){
        $sql="select access_token from jd_access_refresh_token";
        $queryArr=Yii::app()->db->createCommand($sql)->queryAll();
        $token=$queryArr[0]['access_token'];
        return $token;
    }
    /*
     * @version 更新产品价格
     * @param int $id 产品ID
     * @param decimal $jd_price 京东实时价格
     */
    public function updatePriceFromJd($id,$jd_price){
        if(empty($id) || empty($jd_price)){
            return false;
        }
        $sql="update goods set customer_price=".$jd_price." where id=".$id;
        $execute=Yii::app()->db->createCommand($sql)->execute();
        if($execute){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 因价格变动更改未付款订单的信息
     * @param int $order_id 订单ID
     * @param decimal $jd_price 京东实时价格
     * @param int $num 商品数目
     */
//    public function updateOrderInfo($order_id,$jd_price,$num){
//        
//        if(empty($order_id) || empty($jd_price) || empty($num)){
//            return false;
//        }
//        $db = Yii::app()->db;
//        $transaction = $db->beginTransaction();
//        $flag=0;
//        $flag2=0;
//        $total=$jd_price*$num;
//        $sql="update `order` set amount=".$total.",bank_pay=".$total." where id=".$order_id;
//        $execute=Yii::app()->db->createCommand($sql)->execute();
//        if($execute){
//            $flag=1;
//        }else{
//            $flag=0;
//        }
//        $sql2="update order_goods_relation set price=".$jd_price.",amount=".$total.",bank_pay=".$total." where order_id=".$order_id;
//        $execute2=Yii::app()->db->createCommand($sql2)->execute();
//        if($execute2){
//            $flag2=1;
//        }else{
//            $flag2=0;
//        }
//        if($flag && $flag2){
//            $transaction->commit();
//            return true;
//        }else{
//            $transaction->rollback();
//            return false;
//        }
//    }
    /*
     * @version 获取京东商家的账户余额
     */
    public function getCustomerTotal(){
        $result=Config::model()->findByKey('jdCustomerTotal');
        $result2=unserialize($result->value);
        return $result2;
    }
    
    /*
     * @version 通过用户id获取地址信息
     * @param int $uid 用户id
     */
    public function getAddressList($uid){
        
        $sql="select * from jd_shouhuo_address where uid=".$uid."  order by is_selected desc";
        $addressListArr=Yii::app()->db->createCommand($sql)->queryAll();

        foreach ($addressListArr as &$value) {
            $value['full_address'] = $this->getAllAddress($value['province_id'],$value['city_id'],$value['county_id'],$value['town_id'])." ".$value['address'];
        }
            
        return $addressListArr;
    }
    /*
     * @version 通过provinceId获取省份名称
     * @param int $provinceId 省份ID
     */
    public function getProvinceName($provinceId){
        if(empty($provinceId)){
            return false;
        }
        $provinceJson=JdApi::model()->getProvince();
        $provinceArr=json_decode($provinceJson,true);
        if(!empty($provinceArr)){
            foreach ($provinceArr['result'] as $key=>$val){
                if($val==$provinceId){
                    return $key;
                }
            }
        }else{
            return false;
        }
    }
    /*
     * @version 通过provinceName获取省份ID
     * @param string $provinceName 省份名称
     */
    public function getProvinceId($provinceName){
        if(empty($provinceName)){
            return false;
        }
        $provinceJson=JdApi::model()->getProvince();
        $provinceArr=json_decode($provinceJson,true);
        if(!empty($provinceArr)){
            foreach ($provinceArr['result'] as $key=>$val){
                if($key==$provinceName || $key==F::msubstr($provinceName, 0, 2, "utf-8", true)){
                    return $val;
                }
            }
        }else{
            return false;
        }
    }
    /*
     * @version 通过provinceId,cityId获取城市名称
     * @param int $provinceId 省份ID
     * @param int $cityId 城市ID
     */
    public function getCityName($provinceId,$cityId){
        if(empty($cityId) ||empty($provinceId)){
            return false;
        }
        $cityJson=JdApi::model()->getCity($provinceId);
        $cityArr=json_decode($cityJson,true);
        if(!empty($cityArr)){
            foreach ($cityArr['result'] as $key=>$val){
                if($val==$cityId){
                    return $key;
                }
            }
        }else{
            return false;
        }
    }
    /*
     * @version 通过provinceId,cityName获取城市ID
     * @param int $provinceId 省份ID
     * @param string $cityName 城市名称
     */
    public function getCityId($provinceId,$cityName){
        if(empty($cityName) ||empty($provinceId)){
            return false;
        }
        $cityJson=JdApi::model()->getCity($provinceId);
        $cityArr=json_decode($cityJson,true);
        if(!empty($cityArr)){
            foreach ($cityArr['result'] as $key=>$val){
                if($key==$cityName){
                    return $val;
                }
            }
        }else{
            return false;
        }
    }
    /*
     * @version 通过cityId,countyID获取县/区ID
     * @param int $cityId 城市ID
     * @param int $countyId 县/区ID
     */
    public function getCountyName($cityId,$countyId){
        if(empty($countyId) || empty($cityId)){
            return false;
        }
        $countyJson=JdApi::model()->getCounty($cityId);
        $countyArr=json_decode($countyJson,true);
        if(!empty($countyArr)){
            foreach ($countyArr['result'] as $key=>$val){
                if($val==$countyId){
                    return $key;
                }
            }
        }else{
            return false;
        }
    }
    /*
     * @version 通过cityId,countyName获取县/区ID
     * @param int $cityId 城市ID
     * @param string $countyName 县/区名称
     */
    public function getCountyId($cityId,$countyName){
        if(empty($countyName) || empty($cityId)){
            return false;
        }
        $countyJson=JdApi::model()->getCounty($cityId);
        $countyArr=json_decode($countyJson,true);
        if(!empty($countyArr)){
            foreach ($countyArr['result'] as $key=>$val){
                if($key==$countyName){
                    return $val;
                }
            }
        }else{
            return false;
        }
    }
    /*
     * @version 通过countyID,townID获取县/区名称
     * @param int $countyId 县/区ID
     * @param int $townId 镇ID
     */
    public function getTownName($countyId,$townId){
        if(empty($countyId) || empty($townId)){
            return false;
        }
        $townJson=JdApi::model()->getTown($countyId);
        $townArr=json_decode($townJson,true);
        if(!empty($townArr)){
            foreach ($townArr['result'] as $key=>$val){
                if($val==$townId){
                    return $key;
                }
            }
        }else{
            return false;
        }
    }
    /*
     * @version 通过countyID,townName获取县/区ID
     * @param int $countyId 县/区ID
     * @param string $townName 镇名称
     */
    public function getTownId($countyId,$townName){
        if(empty($countyId) || empty($townName)){
            return false;
        }
        $townJson=JdApi::model()->getTown($countyId);
        $townArr=json_decode($townJson,true);
        if(!empty($townArr)){
            foreach ($townArr['result'] as $key=>$val){
                if($key==$townName){
                    return $val;
                }
            }
        }else{
            return false;
        }
    }
    /*
     * @version 判断是否有默认地址
     * @param int uid 用户id
     */
    public function isSelected($uid){
        $sql="select * from jd_shouhuo_address where uid=".$uid;
        $addressListArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($addressListArr)){
            return 0;
        }else{
            return 1;
        } 
    }
    /*
     * @version 获取全部地址
     * @param int $province_id 省份ID
     * @param int $city_id  城市ID
     * @param int $county_id 区/县ID
     * @param int $town_id 城镇ID
     */
    public function getAllAddress($province_id,$city_id,$county_id,$town_id){
        if(empty($province_id) || empty($city_id) || empty($county_id)){
            return false;
        }
        $province_name=$this->getProvinceName($province_id);
        $city_name=$this->getCityName($province_id,$city_id);
        $county_name=$this->getCountyName($city_id,$county_id);
        if(!empty($town_id)){
            $town_name=$this->getTownName($county_id,$town_id);
        }else{
            $town_name='';
        }
        return $province_name.$city_name.$county_name.$town_name;
    }
    /*
     * @version 更改默认地址(json)
     * @param int $id 收货地址id
     */
    public function changeSelected($id,$uid){
        if(empty($id)){
           return false;
        }else{  
            $countSql="select count(*) from jd_shouhuo_address where uid=".$uid;
            $count = Yii::app()->db->createCommand($countSql)->queryScalar();
            if($count==1){
                $updateSql="update jd_shouhuo_address set is_selected=1 where id=".$id;
                Yii::app()->db->createCommand($updateSql)->execute();
                $ret=array("success"=>1);
                echo json_encode($ret);
            }else{
                $db = Yii::app()->db;
                $transaction = $db->beginTransaction();
                $updateSql="update jd_shouhuo_address set is_selected=0 where uid=".$uid;
                $execute=Yii::app()->db->createCommand($updateSql)->execute();
                $updateSql2="update jd_shouhuo_address set is_selected=1 where id=".$id;
                $execute2=Yii::app()->db->createCommand($updateSql2)->execute();
                if($execute && $execute2){
                    $transaction->commit();
                    $ret=array("success"=>1);
                    echo json_encode($ret);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }
        }
    }
    /*
     * @version 更改默认地址(boolean)
     * @param int $id 收货地址id
     */
    public function changeSelectedOther($id,$uid){
        if(empty($id)){
           return false;
        }else{
            $countSql="select count(*) from jd_shouhuo_address where uid=".$uid;
            $count = Yii::app()->db->createCommand($countSql)->queryScalar();
            if($count==1){
                $updateSql="update jd_shouhuo_address set is_selected=1 where id=".$id;
                Yii::app()->db->createCommand($updateSql)->execute();
                return true;
            }else{
                $db = Yii::app()->db;
                $transaction = $db->beginTransaction();
                $updateSql="update jd_shouhuo_address set is_selected=0 where uid=".$uid;
                $execute=Yii::app()->db->createCommand($updateSql)->execute();
                $updateSql2="update jd_shouhuo_address set is_selected=1 where id=".$id;
                $execute2=Yii::app()->db->createCommand($updateSql2)->execute();
                if($execute && $execute2){
                    $transaction->commit();
                    return true;
                }else{
                    $transaction->rollback();
                    return false;
                }
            }
        }
    }
    /*
     * @version 根据条件获取产品是否有货
     * @param int $uid 用户id
     * @param int $good_id 产品id
     * @param int $num 产品数量
     */
    public function getStockState($uid,$good_id,$num){
        if (!$uid) {
            return false;
        }
        $select_sql="select * from jd_shouhuo_address where uid='".$uid."' and is_selected=1";
        $selectArr=Yii::app()->db->createCommand($select_sql)->queryAll();
        if(!empty($selectArr)){
            $productArr=Goods::model()->findByPk($good_id);
            $stockjson=$this->getSkuStock("[{skuId:{$productArr['sku']},num:{$num}}]","{$selectArr[0]['province_id']}_{$selectArr[0]['city_id']}_{$selectArr[0]['county_id']}");
            $stockArr=json_decode($stockjson,true);
            $stockStateIdArr=  json_decode($stockArr['result'],true);
            $stockStateId=$stockStateIdArr[0]['stockStateId'];
            return $stockStateId;
        }else{
            return false;
        }
    }
    /*
     * @version 根据条件获取产品是否有区域限制
     * @param int $uid 用户id
     * @param int $good_id 产品id
     */
    public function getAreaLimit($uid,$good_id){
        if (!$uid) {
            return false;
        }
        $select_sql="select * from jd_shouhuo_address where uid='".$uid."' and is_selected=1";
        $selectArr=Yii::app()->db->createCommand($select_sql)->queryAll();
        if(!empty($selectArr)){
            $productArr=Goods::model()->findByPk($good_id);
            $resultjson=JdApi::model()->checkAreaLimit($productArr['sku'],$selectArr[0]['province_id'],$selectArr[0]['city_id'],$selectArr[0]['county_id']);
            $resultArr=json_decode($resultjson,true);
            $isAreaRestrictArr=  json_decode($resultArr['result'],true);
            $isAreaRestrict=$isAreaRestrictArr[0]['isAreaRestrict'];
            return $isAreaRestrict;
        }else{
            return false;
        }
    }
    /*
     * @version 根据条件来判断订单是否可以继续付款
     * @param int $order_id
     */
    public function getOrderPay($order_id){
        $orderGoodsArr=OrderGoodsRelation::model()->findAll('order_id=:order_id',array(':order_id'=>$order_id));
        $order=Order::model()->findByPk($order_id);
        if(!empty($orderGoodsArr) && !empty($order)){
            foreach ($orderGoodsArr as $orderGoods){
                $goods=Goods::model()->findByPk($orderGoods['goods_id']);
                $stockjson=JdApi::model()->getSkuStock("[{skuId:{$goods['sku']},num:{$orderGoods['count']}}]","{$order->province}_{$order->city}_{$order->county}");
                $stockArr=json_decode($stockjson,true);
                $stockStateIdArr=  json_decode($stockArr['result'],true);
                $stockStateId=$stockStateIdArr[0]['stockStateId'];
                $stockAllArr[]=$stockStateId;
            }
            $result=array_unique($stockAllArr);
            if(count($result)==1 && $result[0]==33){
                return 33;
            }else{
                return 34;
            }
        }
    }
    /*
     * @version 根据条件判断是否可以提交订单
     * @param string  $good_id_str
     */
    public function checkOrderPay($good_id_str,$province_id,$city_id,$county_id){
        if(empty($good_id_str)){
            return false;
        }
        $good_id_arr=explode('-', $good_id_str);
        foreach ($good_id_arr as $good_id){
            $goodCart=CustomerGoodsCart::model()->find('good_id=:good_id',array(':good_id'=>$good_id));
            $goods=Goods::model()->findByPk($good_id);
            $stockjson=JdApi::model()->getSkuStock("[{skuId:{$goods['sku']},num:{$goodCart['number']}}]","{$province_id}_{$city_id}_{$county_id}");
            $stockArr=json_decode($stockjson,true);
            $stockStateIdArr=  json_decode($stockArr['result'],true);
            $stockStateId=$stockStateIdArr[0]['stockStateId'];
            $stockAllArr[]=$stockStateId;
        }
        $result=array_unique($stockAllArr);
        if(count($result)==1 && $result[0]==33){
            return 33;
        }else{
            return 34;
        }
    }
    /*
     * @version 根据条件判断是否可以提交订单
     * @param string  $good_id_str
     */
    public function checkAreaOrderPay($good_id_str,$province_id,$city_id,$county_id){
        if(empty($good_id_str)){
            return false;
        }
        $good_id_arr=explode('-', $good_id_str);
        foreach ($good_id_arr as $good_id){
            $goodCart=CustomerGoodsCart::model()->find('good_id=:good_id',array(':good_id'=>$good_id));
            $goods=Goods::model()->findByPk($good_id);
            $resultjson=JdApi::model()->checkAreaLimit($goods['sku'],$province_id,$city_id,$county_id);
            $resultArr=json_decode($resultjson,true);
            $isAreaRestrictArr=  json_decode($resultArr['result'],true);
            $isAreaRestrict=$isAreaRestrictArr[0]['isAreaRestrict'];
            $isAreaRestrictAllArr[]=$isAreaRestrict;
        }

        $result=array_unique($isAreaRestrictAllArr);
        if(count($result)==1 && $result[0]==false){
            return 'y';
        }else{
            return 'n';
        }
    }
    /*
     * @version 通过单个商品sku获取商品协议
     * @param int $productSku 商品sku
     */
    public function getProductXyPrice($productSku){
        if(empty($productSku)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&sku='.$productSku;
        $url='http://bizapi.jd.com/api/price/getPrice';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 截取字符串，以第一个空格截取
     */
    function start($str, $n){
        $str_arr = explode(" ",$str);
        for($i=$n; $i<count($str_arr); $i++){
            $str_arr2[] = $str_arr[$i];
        }
         return implode(" ",$str_arr2);
    }
    /*
     * @version 根据条件购物车产品价格更新
     * @param int $good_id 产品id
     */
    public function updateCartProductPrice($good_id){
        if(empty($good_id)){
            return false;
        }
        $goodArr=Goods::model()->findByPk($good_id);
        $goodPriceJson=$this->getProductXyPrice($goodArr->sku);
        $goodPriceArr=json_decode($goodPriceJson,true);
        if(!empty($goodPriceArr['result'])){
            $good_price=$goodPriceArr['result'][0]['price'];
            $cartArr=CustomerGoodsCart::model()->find('good_id=:good_id',array(':good_id'=>$good_id));
            if($cartArr->good_price!=$good_price){
                $updateSql="update customer_goods_cart set good_price=".$good_price." where good_id=".$good_id;
                Yii::app()->db->createCommand($updateSql)->execute();
            }
        }else{
            $sqlDelete="delete from customer_goods_cart where good_id=".$good_id;
            Yii::app()->db->createCommand($sqlDelete)->execute();
        }
        return true; 
    }
    public function selectJdOrder($jdOrderId){
        if(empty($jdOrderId)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&jdOrderId='.$jdOrderId;
        $url='https://bizapi.jd.com/api/order/selectJdOrder';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*******************************************京东退换货接口**********************************************************/
    /*
     * @version 校验某订单中某商品是否可以提交售后服务
     * @param int $jdOrderId 京东订单号
     * @param int $skuId 京东商品编号 
     */
    public function getAvailableNumberComp($jdOrderId,$skuId){
        if(empty($jdOrderId) || empty($skuId)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&param={"jdOrderId":'.$jdOrderId.',"skuId":'.$skuId.'}';
        $url='http://bizapi.jd.com/api/afterSale/getAvailableNumberComp';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 根据订单号、商品编号查询支持的服务类型
     * @param int $jdOrderId 京东订单号
     * @param int $skuId 京东商品编号
     */
    public function getCustomerExpectComp($jdOrderId,$skuId){
        if(empty($jdOrderId) || empty($skuId)){
            return false;
        }
//        $token=$this->getAccessTokenByData();
        $data='jdOrderId='.$jdOrderId.'&skuId='.$skuId;
        $url='http://bizapi.jd.com/api/afterSale/getCustomerExpectComp';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /*
     * @version 根据订单号、商品编号查询支持的商品返回京东方式
     * @param int $jdOrderId 京东订单号
     * @param int $skuId 京东商品编号
     */
    public function getWareReturnJdComp($jdOrderId,$skuId){
        if(empty($jdOrderId) || empty($skuId)){
            return false;
        }
//        $token=$this->getAccessTokenByData();
        $data='jdOrderId='.$jdOrderId.'&skuId='.$skuId;
        $url='http://bizapi.jd.com/api/afterSale/getWareReturnJdComp';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }
    /**********************************京东运费调整*******************************************************/
    /*
     * @version 运费查询接口
     * @param string sku 商品和数量(格式：[{skuId:1366087,num:1}])
     * @param int province 一级地址
     * @param int city 二级地址
     * @param int county 三级地址
     * @param int town 四级地址(如果该地区有四级地址，则必须传递四级地址)
     * @param int paymentType 4:在线支付 1：货到付款（如果选择货到付款，则不要传isUseBalance参数）---------------4
     */
    public function getFreight($sku,$province,$city,$county,$town,$paymentType){
        if(empty($sku) || empty($province) || empty($city) || empty($county) || empty($paymentType)){
            return false;
        }
        $token=$this->getAccessTokenByData();
        $data='token='.$token.'&sku='.$sku.'&province='.$province.'&city='.$city.'&county='.$county.'&town='.$town.'&paymentType='.$paymentType;
        $url='http://bizapi.jd.com/api/order/getFreight';
        $result=$this->curl_post_ssl($url,$data,$second=30);
        return $result;
    }

}

