<?php
/**
 * class LakalaPosApi
 * 拉卡拉支付有限公司
 * pos 支付
 */
class LakalaPosApi{
/**
 * 订单查询
 * @param $txncod
 * @param $requestId
 * @param $mercId
 * @param $refNumber
 * @param $orderId
 * @param $transTime
 * @param $extData
 * @param $md5
 */

public $txncod;             //交易码
public $requestId;          //请求方
public $mercId;             //商户号
public $termId;             //终端号
public $refNumber;          //系统参考号
public $orderId;            //订单号
public $transTime;          //交易传输时间
public $extData;            //扩展信息
public $md5;                //MD5校验值

public function __construct($txncod, $requestId, $mercId, $termId=null, $refNumber, $orderId, $transTime, $extData=null, $md5) {
    $this->txncod = $txncod;
    $this->requestId = $requestId;
    $this->mercId = $mercId;
    $this->termId = $termId;
    $this->refNumber = $refNumber;
    $this->orderId = $orderId;
    $this->transTime = $transTime;
    $this->extData = $extData;
    $this->md5 = $md5;
}
        
    
public function getOrderInfo(){
    $resultArr = array();
    if($this->checkMd5Value()){
        $resultArr = $this->findOrderBySn();
        $result = $this->arrtoxml($resultArr);
        $resultArr['MD5'] = strtoupper(md5($result));         
    }else{
        //$resultArr['Txncod'] = $this->txncod;
        //$resultArr['RequestId'] = $this->requestId;
        $resultArr['MercId'] = $this->mercId;
        $resultArr['TermId'] = $this->termId;
        $resultArr['RefNumber'] = $this->refNumber;
        $resultArr['Result'] = "01";
        $resultArr['RspMsg'] = "md5检验出错";
        $resultArr['OrdAmt'] = 0;
        $resultArr['OrderSta'] = 99;
        $result = $this->arrtoxml($resultArr);
        $resultArr['MD5'] = strtoupper(md5($result));       
    }
    //记录入数据库
    $lakalaModel = new LakalaOrder();
    $lakalaModel->orderNo = $this->orderId;
    $lakalaModel->txncod = $this->txncod;
    $lakalaModel->requestId = $this->requestId;
    $lakalaModel->mercId = $this->mercId;
    $lakalaModel->termId = $this->termId;
    $lakalaModel->refNumber = $this->refNumber;
    $lakalaModel->transTime = $this->transTime;
    $lakalaModel->extData = $this->extData;
    $lakalaModel->lakala_md5 = $this->md5;
    $lakalaModel->rspMsg = $resultArr['RspMsg'];
    $lakalaModel->result = $resultArr['Result'];
    $lakalaModel->ordAmt = $resultArr['OrdAmt'];
    $lakalaModel->orderSta = $resultArr['OrderSta'];
    $lakalaModel->colourlife_md5 = $resultArr['MD5'];
    $lakalaModel->save();
    $resultXml = $this->arrtoxml($resultArr);    
    return $resultXml;
}    


public function checkMd5Value(){
    if(empty($this->extData)){
        $newMd5 = strtoupper(md5($this->mercId.$this->termId.$this->refNumber.$this->orderId.$this->transTime));        
    }else{
        $newMd5 = strtoupper(md5($this->mercId.$this->termId.$this->refNumber.$this->orderId.$this->transTime.$this->extData));    
    }
    if(trim($this->md5) == trim($newMd5)){
        Yii::log('成功调用订单查询接口：' . 'MD5的值='.$newMd5, CLogger::LEVEL_INFO, 'colourlife.backendapi.lakala');
        return true;
    }else{
        Yii::log('失败调用订单查询接口：' . '对方的MD5的值='.$this->md5."我方的md5值=".$newMd5, CLogger::LEVEL_INFO, 'colourlife.backendapi.lakala');
        return false;
    }
}

/*
 * 数组转XML
 */
public function arrtoxml($arr,$dom=0,$item=0){
    if (!$dom){
        $dom = new DOMDocument("1.0","gbk");
    }
    if(!$item){
        $item = $dom->createElement("ROOT"); 
        $dom->appendChild($item);
    }
    foreach ($arr as $key=>$val){
        $itemx = $dom->createElement(is_string($key)?$key:"item");
        $item->appendChild($itemx);
        if (!is_array($val)){
            $text = $dom->createTextNode($val);
            $itemx->appendChild($text);
            
        }else {
            arrtoxml($val,$dom,$itemx);
        }
    }
    return $dom->saveXML();
}


//根据订单号查询订单信息
public function findOrderBySn(){
    $info = array();
    //$info['Txncod'] = $this->txncod;           //交易码
    //$info['RequestId'] = $this->requestId;     //请求方
    $info['MercId'] = $this->mercId;           //商户号
    $info['TermId'] = $this->termId;           //终端号
    $info['RefNumber'] = $this->refNumber;     //系统参考号    
    $typeNo = substr($this->orderId,0,3);
    switch($typeNo){
        case "101":                  
            $dataTable = "others_fees";     //物业费
            break;
        case "102":
            $dataTable = "others_fees";     //停车费
            break;
        case "201":
            $dataTable = "order";           //团购
            break;
        case "202":
            $dataTable = "order";           //抢购
            break;
        case "203":
            $dataTable = "order";           //在线出售
            break;
        case "300":
            $dataTable = "others_fees";     //电话费
            break;
        case "301":
            $dataTable = "others_fees";     //QQ充值
            break;
        case "302":
            $dataTable = "others_fees";     //游戏充值
            break;
        case "303":
            $dataTable = "others_fees";     //预付费
            break;
        case "400":
            $dataTable = "others_fees";     //加盟商销售
            break; 
        default :
            $dataTable = "";
    }
    if($dataTable == "others_fees"){
        $orderInfo = OthersFees::model()->find('sn=:sn',array(':sn'=>$this->orderId));
        if($orderInfo){            
            $info['Result'] = "00";                         //返回码:成功            
            $customer = Customer::model()->findByPk($orderInfo->customer_id);
            $info['RspMsg'] = $customer?$customer->name:"";              //应答码描述(业主或收货人姓名)
            $info['OrdAmt'] = $orderInfo->amount*100;                //金额
            if($orderInfo->status == Item::FEES_AWAITING_PAYMENT){
                $info['OrderSta'] = "00";                     //POS机可以支付(未支付)
            }else if($orderInfo->status == Item::FEES_CANCEL){
                $info['OrderSta'] = "03";                     //POS机不能支付（订单已过期） 
            }else{
                $info['OrderSta'] = "02";                      //POS机不能支付(已支付)
            }            
            //重复物业费订单                
            if($orderInfo->model == "PropertyFees"){
               $flag = false; 
               $colorcloud_bills = PropertyFees::model()->findByPk($orderInfo->object_id)->colorcloud_bills;
               $models = PropertyFees::model()->findAll('colorcloud_bills=:colorcloud_bills',array(':colorcloud_bills'=>$colorcloud_bills));
               for($i=0;$i<count($models);$i++){
                   $repeat = OthersFees::model()->find('object_id=:object_id',array(':object_id'=>$models[$i]->id));
                   if($repeat->status!=Item::FEES_AWAITING_PAYMENT && $repeat->status!=Item::FEES_CANCEL){
                       $flag = true;
                       $info['OrderSta'] = "04";                      //POS机不能支付(订单重复)
                   }
               }
            }
        }else{
            $info['Result'] = "01";                    //返回码:失败
            $info['RspMsg'] = "彩之云无此订单号";  //应答描述 
            $info['OrderSta'] = "01";               //无此订单号
            $info['OrdAmt'] = 0;                //金额
        }            
    }else if($dataTable == "order"){
        $orderInfo = Order::model()->getOrderInfo($this->orderId);
        if($orderInfo){            
            $info['Result'] = "00";                    //返回码:成功
            $info['RspMsg'] = $orderInfo->buyer_name;   //应答码描述(业主或收货人姓名)
            $info['OrdAmt'] = $orderInfo->amount*100;
            if($orderInfo->status == Item::ORDER_AWAITING_PAYMENT){
                $info['OrderSta'] = "00";                     //POS机可以支付(未支付)
            }else if($orderInfo->status == Item::ORDER_BUYER_APPLY_CANCEL || $orderInfo->status == Item::ORDER_CANCEL_CLOSE){
                $info['OrderSta'] = "03";                    //POS机不能支付（订单已过期）
            }else{
                $info['OrderSta'] = "02";                      //POS机不能支付(已支付)
            }
        }else{
            $info['Result'] = "01";                    //返回码:失败
            $info['RspMsg'] = "彩之云无此订单号";  //应答描述
            $info['OrderSta'] = "01";               //无此订单号
            $info['OrdAmt'] = 0;                //金额
        }
    }else{
        $info['Result'] = "01";                    //返回码:失败
        $info['RspMsg'] = "彩之云无此订单号";  //应答描述
        $info['OrderSta'] = "01";               //无此订单号
        $info['OrdAmt'] = 0;                //金额
    }
    return $info;
}




}