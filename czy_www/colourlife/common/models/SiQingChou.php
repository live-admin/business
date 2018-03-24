<?php

/*
 * @version 司庆抽奖model
 */
class SiQingChou extends CActiveRecord{
    public $beginTime='2016-06-30';//活动开始时间
    public $endTime='2016-07-02 23:59:59';//活动结束时间
    //所有奖品总配置
//    private $prize_arr=array(
//        '0' => array('id'=>1,'lei'=>0,'prize_name'=>'1元饭票','num'=>2000,'amount'=>1,'v'=>0),//2000
//        '1' => array('id'=>2,'lei'=>0,'prize_name'=>'0.1元饭票','num'=>50000,'amount'=>0.1,'v'=>0),//50000
//        '2' => array('id'=>3,'lei'=>0,'prize_name'=>'彩之云定制充电宝','num'=>10,'v'=>0),//10
//        '3' => array('id'=>4,'lei'=>0,'prize_name'=>'小米充电宝（5000ma）','num'=>5,'v'=>0),//5
//        '4' => array('id'=>5,'lei'=>0,'prize_name'=>'小米充电宝（10000ma）','num'=>3,'v'=>0),//3
//        '5' => array('id'=>6,'lei'=>0,'prize_name'=>'彩之云定制u盘','num'=>10,'v'=>0),//10
//        '6' => array('id'=>7,'lei'=>0,'prize_name'=>'三角洲门票','num'=>6,'v'=>0),//6
//        '7' => array('id'=>8,'lei'=>0,'prize_name'=>'彩别院标准双人房','num'=>2,'v'=>0),//2
//        '8' => array('id'=>9,'lei'=>1,'prize_name'=>'随机流量包','num'=>3310,'v'=>0),//流量包//3310
//        '9' => array('id'=>10,'lei'=>2,'prize_name'=>'E家政代金券礼包','num'=>500,'v'=>100),//E家政代金券//500
//        '10' => array('id'=>11,'lei'=>3,'prize_name'=>'E维修代金券礼包','num'=>500,'v'=>0),//E维修代金券//500
//    );
    private $prize_arr=array(
        '0' => array('id'=>1,'lei'=>0,'prize_name'=>'1元饭票','num'=>2000,'amount'=>1,'v'=>5),//2000
        '1' => array('id'=>2,'lei'=>0,'prize_name'=>'0.1元饭票','num'=>50000,'amount'=>0.1,'v'=>35),//50000
        '2' => array('id'=>3,'lei'=>0,'prize_name'=>'彩之云定制充电宝','num'=>10,'v'=>5),//10
        '3' => array('id'=>4,'lei'=>0,'prize_name'=>'小米充电宝（5000ma）','num'=>5,'v'=>5),//5
        '4' => array('id'=>5,'lei'=>0,'prize_name'=>'小米充电宝（10000ma）','num'=>3,'v'=>2),//3
        '5' => array('id'=>6,'lei'=>0,'prize_name'=>'彩之云定制u盘','num'=>10,'v'=>3),//10
        '6' => array('id'=>7,'lei'=>0,'prize_name'=>'三角洲门票','num'=>6,'v'=>3),//6
        '7' => array('id'=>8,'lei'=>0,'prize_name'=>'彩别院标准双人房','num'=>2,'v'=>2),//2
        '8' => array('id'=>9,'lei'=>1,'prize_name'=>'随机流量包','num'=>3310,'v'=>20),//流量包//3310
        '9' => array('id'=>10,'lei'=>2,'prize_name'=>'E家政代金券礼包','num'=>500,'v'=>10),//E家政代金券//500
        '10' => array('id'=>11,'lei'=>3,'prize_name'=>'E维修代金券礼包','num'=>500,'v'=>10),//E维修代金券//500
    );
    //流量包配置
    private $package_arr=array(
        '00010' => array('id'=>'00010','lei'=>1,'prize_name'=>'10m流量包','num'=>3000,'v'=>70), 
        '00030' => array('id'=>'00030','lei'=>1,'prize_name'=>'30m流量包','num'=>200,'v'=>20),
        '00050' => array('id'=>'00050','lei'=>1,'prize_name'=>'50m流量包','num'=>100,'v'=>8),
        '00100' => array('id'=>'00100','lei'=>1,'prize_name'=>'100m流量包','num'=>10,'v'=>2),
    );
    //E家政代金券配置
    private $e_jiazheng_arr=array(
        '0' => array('id'=>1,'lei'=>2,'prize_name'=>'500元月嫂代金券','num'=>500),
        '1' => array('id'=>2,'lei'=>2,'prize_name'=>'200元育婴师、老人护理、保姆代金券','num'=>500),
    );
    //E维修代金券配置
    private $e_weixiu_arr=array(
        '0' => array('id'=>1,'lei'=>3,'prize_name'=>'10元空调维修代金券','num'=>500),
        '1' => array('id'=>2,'lei'=>3,'prize_name'=>'10元抽烟机维修代金券','num'=>500),
    );
    //测试
//    private $couponTypeCode=array(
//      '0'=>'xx20160627120654',
//      '1'=>'xx20160627120806',
//    );
    //正式
    private $couponTypeCode=array(
      '0'=>'xx20160629180029',
      '1'=>'xx20160629180205',
    );
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 检查进入首页是否已经领取机会
     * @param int $customer_id
     * @param string $open_id
     * return boolean
     */
    public function isGetChance($customer_id,$open_id){
        if(empty($customer_id) && empty($open_id)){
            return false;
        }
        $chanceArr= SiqingchouChance::model()->find('customer_id=:customer_id and open_id=:open_id and type=:type',array(':customer_id'=>$customer_id,':type'=>1,':open_id'=>$open_id));
        if(empty($chanceArr)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 进入app首页获取三次抽奖机会
     * @param int $customer_id
     * return boolean 
     */
    public function getChanceByIndex($customer_id){
        if(empty($customer_id)){
            return false;
        }
        for($i=1;$i<=3;$i++){
            $this->insertChanceData($customer_id, '',1);
        }
    }
    /*
     * @version 进入分享获取一次抽奖机会
     * @param string $open_id
     * return boolean 
     */
    public function getChanceByShare($open_id){
        if(empty($open_id)){
            return false;
        }
        $isExist=SiqingchouChance::model()->find('open_id=:open_id',array(':open_id'=>$open_id));
        if(empty($isExist)){
            $this->insertChanceData(0,$open_id,1);
        }
        
    }
    /*
     * @version 插入机会表数据
     * @param int $customer_id/string $open_id,$type
     * return boolean
     */
    public function insertChanceData($customer_id,$open_id,$type){
        if(empty($customer_id) && empty($open_id)){
            return false;
        }
        $ChanceModel=new SiqingchouChance();
        $ChanceModel->customer_id=$customer_id;
        $ChanceModel->open_id=$open_id;
        $ChanceModel->type=$type;
        $ChanceModel->status=0;
        $ChanceModel->create_time=time();
        $ChanceModel->update_time=0;
        $result=$ChanceModel->save();
        if($result){
            $this->addChanceLog($customer_id,$open_id,$type,2);
        }else{
            $this->addChanceLog($customer_id,$open_id,$type,1);
        }
    }
    /*
     * @version 分享获取一次机会
     * @param int $customer_id/string $open_id,$type
     * return boolean
     */
    public function insertChanceDataByShare($customer_id,$open_id,$type){
        if(empty($customer_id) && empty($open_id)){
            return false;
        }
        $ChanceModel=new SiqingchouChance();
        $ChanceModel->customer_id=$customer_id;
        $ChanceModel->open_id=$open_id;
        $ChanceModel->type=$type;
        $ChanceModel->status=0;
        $ChanceModel->create_time=time();
        $ChanceModel->update_time=0;
        $result=$ChanceModel->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     *@version 添加获取机会log
     */
    public function addChanceLog($customer_id,$open_id,$type,$status){
        $chanceLog=new SiqingchouLog();
        $chanceLog->customer_id=$customer_id;
        $chanceLog->open_id=$open_id;
        $chanceLog->type=$type;
        $chanceLog->status=$status;
        $chanceLog->create_time=time();
        $chanceLog->save();
    }
    /*******************************抽奖****************************************/
    /*
     * @version 抽奖逻辑
     * @param $customer_id
     * @param $open_id
     * return array
     */
    public function chouJiang($customer_id,$open_id){
        if(empty($customer_id) && empty($open_id)){
            return false;
        }
        $prizeOtherArr=$this->checkAll();
        $prizeOtherArr2=$this->chouOnce($customer_id,$open_id,$prizeOtherArr);
        foreach ($prizeOtherArr2 as $key => $val){
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->get_rand($arr); //根据概率获取奖项id
        if(empty($customer_id) && !empty($open_id)){
            if(isset($arr[9]) && !empty($arr[9])){
                $rid=9;
            }else{
                return $list=array('rid'=>-2);
            }
        }
        $list=array();
        $chanceList=$this->getChanceArray($customer_id,$open_id);
//		$rid=9;
        if($rid==1){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id,$open_id,'',1,$this->prize_arr[$rid-1]['prize_name'], $chanceList[0]);
            $result2=$this->updatechanceStatus($chanceList[0]);
            $items = array(
    				'customer_id' => $customer_id,//用户的ID
    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
    				'sum' =>$this->prize_arr[$rid-1]['amount'],//红包金额,
    				'sn' => 18,
                );
            $redPacked = new RedPacket();
            $result3=$redPacked->addRedPacker($items);
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==2){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id,$open_id,'',2,$this->prize_arr[$rid-1]['prize_name'], $chanceList[0]);
            $result2=$this->updatechanceStatus($chanceList[0]);
            $items = array(
    				'customer_id' => $customer_id,//用户的ID
    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
    				'sum' =>$this->prize_arr[$rid-1]['amount'],//红包金额,
    				'sn' => 18,
                );
            $redPacked = new RedPacket();
            $result3=$redPacked->addRedPacker($items);
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==3){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id,$open_id,'',3,$this->prize_arr[$rid-1]['prize_name'], $chanceList[0]);
            $result2=$this->updatechanceStatus($chanceList[0]);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==4){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id,$open_id,'',4,$this->prize_arr[$rid-1]['prize_name'], $chanceList[0]);
            $result2=$this->updatechanceStatus($chanceList[0]);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==5){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id,$open_id,'',5,$this->prize_arr[$rid-1]['prize_name'], $chanceList[0]);
            $result2=$this->updatechanceStatus($chanceList[0]);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==6){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id,$open_id,'',6,$this->prize_arr[$rid-1]['prize_name'], $chanceList[0]);
            $result2=$this->updatechanceStatus($chanceList[0]);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==7){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id,$open_id,'',7,$this->prize_arr[$rid-1]['prize_name'], $chanceList[0]);
            $result2=$this->updatechanceStatus($chanceList[0]);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==8){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id,$open_id,'',8,$this->prize_arr[$rid-1]['prize_name'], $chanceList[0]);
            $result2=$this->updatechanceStatus($chanceList[0]);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==9){//流量包
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id,$open_id,'',9,$this->prize_arr[$rid-1]['prize_name'], $chanceList[0]);
            $result2=$this->updatechanceStatus($chanceList[0]);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid-1,'chance_id'=>$chanceList[0]);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==10){//E家政
            $transaction = Yii::app()->db->beginTransaction();
            $cusArr=Customer::model()->findByPk($customer_id);
            $result=$this->insertPrize($customer_id,$open_id,'',10,$this->prize_arr[$rid-1]['prize_name'], $chanceList[0]);
            $result2=$this->updatechanceStatus($chanceList[0]);
            $eObject = new ChuangLan();
            $eData = array(
                'mobile'=>$cusArr['mobile'],
                'couponTypeCode'=>$this->couponTypeCode[0],
            );
            $result3= $eObject->getEJiaZheng($eData);
            if($result3['code']!=200){
                return $list=array('rid'=>-1);
            }
            $eData2 = array(
                'mobile'=>$cusArr['mobile'],
                'couponTypeCode'=>$this->couponTypeCode[1],
            );
            $result4= $eObject->getEJiaZheng($eData2);
            if($result4['code']!=200){
                return $list=array('rid'=>-1);
            }
            if($result && $result2 && $result3['code']==200 && $result4['code']==200){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==11){//E维修
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id,$open_id,'',11,$this->prize_arr[$rid-1]['prize_name'], $chanceList[0]);
            $result2=$this->updatechanceStatus($chanceList[0]);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid-1);
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return $list=array('rid'=>-1);
        }
    }
    /*
     * @version 通过手机号码和chance_id领取流量
     * @param string $mobile 充值的号码
     * @param int $chance_id
     * return array
     */
    public function getLingQu($mobile,$chance_id){
        if(empty($mobile) || empty($chance_id)){
            return false;
        }
        $cusArr=Customer::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
        if(empty($cusArr)){
            return $list=array('success'=>0,'msg'=>'该手机号未注册彩之云不能领取');
        }
        /*
         * 通过手机号码获取相应的流量包
         */
        $package=$this->checkPackage($mobile);
        if($package=='low'){
            return $list=array('success'=>0,'msg'=>'流量包已经领取完了，正在加紧补充！');
        }
        if($package=='yidongout' || $package=='liantongout' || $package=='dianxinout'){
            return $list=array('success'=>0,'msg'=>'手机号对应的运营商流量包已经领取完了，请更换其他运营商号码');
        }
//        $package='00010';
        $prize_name=$this->package_arr[$package]['prize_name'];
        /*
         * 获取订单号
         */
        $ext_id=$this->randomExt(10, $chars = '0123456789');
        $liuObject = new ChuangLan();
        $orderData = array(
            'mobile'=>$mobile,
            'package'=>$package,
            'ext_id'=>$ext_id,
        );
        $result= $liuObject->getLiuLiang($orderData);
        $list=array();
        if($result['code']=='0'){
            $transaction = Yii::app()->db->beginTransaction();
            $sqlUpdate="update siqingchou_prize set mobile='".$mobile."',prize_name='".$prize_name."' where chance_id=".$chance_id;
            $res=Yii::app()->db->createCommand($sqlUpdate)->execute();
            $res2=$this->insertLiuOrder($ext_id,$mobile,$package,$result['code']);
            if($res && $res2){
                $transaction->commit();
                return $list=array('success'=>1,'msg'=>$prize_name);
            }else{
                $transaction->rollback();
                return $list=array('success'=>0,'msg'=>'获取产品失败！');
            }
        }else{
            return $list=array('success'=>0,'msg'=>$result['desc']);
        }
    }
    /*
     * @version 获取总的抽奖机会
     * @param int $customer_id
     * @param string $open_id
     * return int
     */
    public function getAllChance($customer_id,$open_id){
        if(empty($customer_id) && empty($open_id)){
            return false;
        }
        $chanceList=$this->getChanceArray($customer_id,$open_id);
        if(!empty($chanceList)){
            $count=count($chanceList);
        }else{
            $count=0;
        }
        $allChance=$count;
        return $allChance;
    }
    /*
     * @version 随机产生一个流量订单号
     */
    public function randomExt($length, $chars = '0123456789') {
        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }
    /*
     * @version 插入流量订单信息
     * @param string $ext_id
     * @param string $mobile
     * @param string $package
     * @param string $status
     */
    public function insertLiuOrder($ext_id,$mobile,$package,$status){
        if(empty($ext_id) || empty($mobile) || empty($package)){
            return false;
        }
        $ChouLiuModel=new SiqingchouLiu();
        $ChouLiuModel->sn=$ext_id;
        $ChouLiuModel->mobile=$mobile;
        $ChouLiuModel->package=$package;
        $ChouLiuModel->status=$status;
        $ChouLiuModel->create_time= time();
        $isInsert=$ChouLiuModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }

    /*
     * @version 通过id获取机会数据
     * @param int $customer_id
     * @param string $open_id
     * return array
     */
    public function getChanceArray($customer_id,$open_id){
        if(empty($customer_id) && empty($open_id)){
            return false;
        }
        $chanceArr = SiqingchouChance::model()->findAll(array(
            'select'=>array('id,open_id'),
            'condition' => 'customer_id=:customer_id and open_id=:open_id and status=0 and create_time>=:startDay and create_time<:endDay',
            'params' => array(':customer_id'=>$customer_id,':open_id'=>$open_id,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)),
        ));
        $chanceList=array();
        if(!empty($chanceArr)){
            foreach ($chanceArr as $key=>$val){
                $chanceList[]=$val['id'];
            }
            return $chanceList;
        }else{
            return false;
        }
    }
    /*
     * @version 检查总的数量
     * return array
     */
    public function checkAll(){
        $n1=SiqingchouPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>1,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n2=SiqingchouPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>2,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n3=SiqingchouPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>3,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n4=SiqingchouPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>4,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n5=SiqingchouPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>5,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n6=SiqingchouPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>6,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n7=SiqingchouPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>7,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n8=SiqingchouPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>8,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n9=SiqingchouPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>9,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n10=SiqingchouPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>10,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $n11=SiqingchouPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>11,':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        if($n1>=$this->prize_arr[0]['num']){
             unset($this->prize_arr[0]);
        }
        if($n2>=$this->prize_arr[1]['num']){
             unset($this->prize_arr[1]);
        }
        if($n3>=$this->prize_arr[2]['num']){
             unset($this->prize_arr[2]);
        }
        if($n4>=$this->prize_arr[3]['num']){
             unset($this->prize_arr[3]);
        }
        if($n5>=$this->prize_arr[4]['num']){
             unset($this->prize_arr[4]);
        }
        if($n6>=$this->prize_arr[5]['num']){
             unset($this->prize_arr[5]);
        }
        if($n7>=$this->prize_arr[6]['num']){
             unset($this->prize_arr[6]);
        }
        if($n8>=$this->prize_arr[7]['num']){
             unset($this->prize_arr[7]);
        }
        if($n9>=$this->prize_arr[8]['num']){
             unset($this->prize_arr[8]);
        }
        if($n10>=$this->prize_arr[9]['num']){
             unset($this->prize_arr[9]);
        }
        if($n11>=$this->prize_arr[10]['num']){
             unset($this->prize_arr[10]);
        }
        
        return $this->prize_arr;
    }
    /*
     * @version 每个用户只能抽取一次流量，一个E家政，一个E维修
     * @param int $customer_id
     * @param string $open_id
     * @param array $prizeOtherArr
     */
    public function chouOnce($customer_id,$open_id,$prizeOtherArr){
        if(empty($customer_id) && empty($open_id)){
            return false;
        }
        $nine=SiqingchouPrize::model()->find('customer_id=:customer_id and open_id=:open_id and prize_id=:prize_id and chance_id!=0',array(':customer_id'=>$customer_id,':open_id'=>$open_id,':prize_id'=>9));
        $ten=SiqingchouPrize::model()->find('customer_id=:customer_id and open_id=:open_id and prize_id=:prize_id and chance_id!=0',array(':customer_id'=>$customer_id,':open_id'=>$open_id,':prize_id'=>10));
        $elevent=SiqingchouPrize::model()->find('customer_id=:customer_id and open_id=:open_id and prize_id=:prize_id and chance_id!=0',array(':customer_id'=>$customer_id,':open_id'=>$open_id,':prize_id'=>11));
        if(!empty($nine)){
            unset($prizeOtherArr[8]);
        }
        if(!empty($ten)){
            unset($prizeOtherArr[9]);
        }
        if(!empty($elevent)){
            unset($prizeOtherArr[10]);
        }
        return $prizeOtherArr;
    }
    /*
     * @version 检查流量包的数量并返回对应的流量包
     * @param string $mobile
     * return array
     */
    public function checkPackage($mobile){
        if(empty($mobile)){
            return false;
        }
        $m1=SiqingchouPrize::model()->count('prize_id=:prize_id and prize_name=:prize_name and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>9,':prize_name'=>$this->package_arr['00010']['prize_name'],':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $m2=SiqingchouPrize::model()->count('prize_id=:prize_id and prize_name=:prize_name and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>9,':prize_name'=>$this->package_arr['00030']['prize_name'],':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $m3=SiqingchouPrize::model()->count('prize_id=:prize_id and prize_name=:prize_name and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>9,':prize_name'=>$this->package_arr['00050']['prize_name'],':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        $m4=SiqingchouPrize::model()->count('prize_id=:prize_id and prize_name=:prize_name and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>9,':prize_name'=>$this->package_arr['00100']['prize_name'],':startDay'=>strtotime($this->beginTime),':endDay'=>strtotime($this->endTime)));
        if($m1>=$this->package_arr['00010']['num']){
            unset($this->package_arr['00010']);
        }
        if($m2>=$this->package_arr['00030']['num']){
            unset($this->package_arr['00030']);
        }
        if($m3>=$this->package_arr['00050']['num']){
            unset($this->package_arr['00050']);
        }
        if($m4>=$this->package_arr['00100']['num']){
            unset($this->package_arr['00100']);
        }
        if(!empty($this->package_arr)){
            $sms=$this->getMobileArea($mobile);
            if($sms['supplier']=='中国移动'){
                if(isset($this->package_arr['00010']) || isset($this->package_arr['00030'])){
                    unset($this->package_arr['00050']);
                    unset($this->package_arr['00100']);
                }else{
                    return 'yidongout';
                }
            }elseif($sms['supplier']=='中国联通'){
                if(isset($this->package_arr['00050']) || isset($this->package_arr['00100'])){
                    unset($this->package_arr['00010']);
                    unset($this->package_arr['00030']);
                }else{
                    return 'liantongout';
                }
            }elseif($sms['supplier']=='中国电信'){
                if(isset($this->package_arr['00010']) || isset($this->package_arr['00030']) || isset($this->package_arr['00100'])){
                    unset($this->package_arr['00050']);
                }else{
                    return 'dianxinout';
                }
            }else{
                unset($this->package_arr['00030']);
                unset($this->package_arr['00050']);
                unset($this->package_arr['00100']);
            }
            foreach ($this->package_arr as $key => $val){
                $arr[$val['id']] = $val['v']; 
            }
            $rid = $this->get_rand($arr); //根据概率获取奖项id
            return $rid;
        }else{
            return 'low';
        }
            
    }
    /*
     * @version 
     */
    function getMobileArea($mobile){
        $sms = array('supplier'=>'');//初始化变量
        //根据淘宝的数据库调用返回值
        $url = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=".$mobile."&t=".time();
        $content = file_get_contents($url);
        $content=iconv("gbk", "utf-8//IGNORE",$content);
        $sms['supplier'] = substr($content, "79", "12");
        return $sms;
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
     * @version 插入奖品
     * @param int $customer_id
     * @param int $open_id
     * @param string $mobile
     * @param int $prize_id
     * @param string $prize_name
     * @param int $chance_id
     * return array/boolean
     */
    private function insertPrize($customer_id,$open_id,$mobile,$prize_id,$prize_name,$chance_id){
        if(empty($prize_id) || empty($prize_name) || empty($chance_id)){
            return false;
        }
        $ChouPriceModel=new SiqingchouPrize();
        $ChouPriceModel->customer_id=$customer_id;
        $ChouPriceModel->open_id=$open_id;
        $ChouPriceModel->mobile=$mobile;
        $ChouPriceModel->prize_id=$prize_id;
        $ChouPriceModel->prize_name=$prize_name;
        $ChouPriceModel->chance_id=$chance_id;
        $ChouPriceModel->create_time= time();
        $isInsert=$ChouPriceModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 通过机会id更改机会的status状态
     * @param int $chanceId
     * return boolean
     */
    public function updatechanceStatus($chanceId){
        if(empty($chanceId)){
            return false;
        }
        $chanceArr=SiqingchouChance::model()->findByPk($chanceId);
        if(empty($chanceArr['open_id'])){
            $sqlUpdate="update siqingchou_chance set status=1 where id=".$chanceId;
        }else{
            $sqlUpdate="update siqingchou_chance set status=1 where open_id='".$chanceArr['open_id']."'";
        }
        
        $result=Yii::app()->db->createCommand($sqlUpdate)->execute();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 检测用户的机会是否存在
     * @param int $customer_id
     * @param string $open_id
     * @param int $chance_id
     * return boolean
     */
    public function checkChance($customer_id,$open_id,$chance_id){
        if(empty($chance_id)){
            return false;
        }
        $checkArr=SiqingchouChance::model()->findByPk($chance_id,'customer_id=:customer_id and open_id=:open_id',array(':customer_id'=>$customer_id,':open_id'=>$open_id));
        if(!empty($checkArr)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 
     * @param string mobile
     */

    /*******************************奖品********************************************/
    /*
     * @version 获奖滚动十条
     * return array
     */
    public function getPrizeDetailTop(){
        $sql="select customer_id,mobile,prize_name from siqingchou_prize where chance_id!=0 order by create_time limit 10";
        $jiangArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($jiangArr)){
            $awarderList=array();
            foreach($jiangArr as $key=>$jiang){
                if(empty($jiang['mobile'])){
                    $cusArr=Customer::model()->findByPk($jiang['customer_id']);
                    $awarderList[$key]['phone']=substr_replace($cusArr['mobile'],'****',2,5);
                }else{
                    $awarderList[$key]['phone']=substr_replace($jiang['mobile'],'****',2,5);
                }
                $awarderList[$key]['awarderName']=$jiang['prize_name'];
            }
            $awarderList=json_encode($awarderList);
            return $awarderList;
        }else{
            return false;
        }
    }
    /*
     * @version 获奖明细
     * @param int $customer_id
     * @param string $open_id
     * return array
     */
    public function getPrizeDetail($customer_id,$open_id){
        if(empty($customer_id) && empty($open_id)){
            return false;
        }
        $prizeMobileArr = SiqingchouPrize::model()->findAll(array(
            'select'=>array('mobile','prize_name','create_time','chance_id'),
            'order' => 'id DESC',
            'condition' => 'customer_id=:customer_id and open_id=:open_id and chance_id!=:chance_id',
            'params' => array(':customer_id'=>$customer_id,':open_id'=>$open_id,':chance_id'=>0),
        ));
        return $prizeMobileArr;
    }
    /*
     * @verson 点击分享按钮
     * @param int $userId
     * @return boolean
     */
    public function getChanceByClickShare($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $chanceArr= SiqingchouChance::model()->find('customer_id=:customer_id and type=:type',array(':customer_id'=>$customer_id,':type'=>3));
        if(empty($chanceArr)){
            $result=$this->insertChanceDataByShare($customer_id,'',3);
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
    
    
    









    
    
    
    
    
    
    
    
    
    
}

