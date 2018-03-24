<?php
/*
 * @version 宝箱活动model
 */
class Box extends CActiveRecord{
    private $startDay='2016-08-05 10:00:00';
    private $endDay='2016-08-16 23:59:59';
    
    //领取的时候获取奖项配置
    private $prizeArr=array(
        '0' => array('id'=>1,'prize_name'=>'彩之云定制充电宝（卡片）','num'=>30,'v'=>10),
        '1' => array('id'=>2,'prize_name'=>'彩之云定制充电宝（5000毫安）','num'=>20,'v'=>10),
        '2' => array('id'=>3,'prize_name'=>'彩之云定制充电宝（10000毫安）','num'=>10,'v'=>10),
        '3' => array('id'=>4,'prize_name'=>'彩之云定制U盘','num'=>30,'v'=>10),
        '4' => array('id'=>5,'prize_name'=>'彩之云抱枕','num'=>20,'v'=>10),
        '5' => array('id'=>6,'prize_name'=>'京东特供满400减200券','code'=>100000097,'num'=>5,'v'=>10),
        '6' => array('id'=>7,'prize_name'=>'京东特供满200减100券','code'=>100000098,'num'=>7,'v'=>10),
        '7' => array('id'=>8,'prize_name'=>'京东特供满100减50券','code'=>100000099,'num'=>10,'v'=>10),
        '8' => array('id'=>9,'prize_name'=>'彩生活特供满400减200券','code'=>100000100,'num'=>5,'v'=>10),
        '9' => array('id'=>10,'prize_name'=>'彩生活特供满200减100券','code'=>100000101,'num'=>8,'v'=>10),
        '10' => array('id'=>11,'prize_name'=>'彩生活特供满100减50券','code'=>100000102,'num'=>10,'v'=>10),
        '11' => array('id'=>12,'prize_name'=>'小熊(Bear) ZDQ-206 煮蛋器 双层蒸蛋器 自动断电','goods_id'=>13060,'num'=>10,'v'=>10),
        '12' => array('id'=>13,'prize_name'=>'维达 卫生纸 蓝色经典140g卷纸*10卷','goods_id'=>23298,'num'=>10,'v'=>10),
    );
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 获取宝石数量
     * @param int $customer_id
     * return array()
     */
    public function getBaoShiArray($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $list=array();
        $num1=$this->getNumById($customer_id, 1);
        $num2=$this->getNumById($customer_id, 2);
        $num3=$this->getNumById($customer_id, 3);
        $num4=$this->getNumById($customer_id, 4);
        $num5=$this->getNumById($customer_id, 5);
        $num6=$this->getNumById($customer_id, 6);
        $num7=$this->getNumById($customer_id, 7);
        return $list=array(
            1=>array('baoshi_id'=>1,'num'=>$num1),
            2=>array('baoshi_id'=>2,'num'=>$num2),
            3=>array('baoshi_id'=>3,'num'=>$num3),
            4=>array('baoshi_id'=>4,'num'=>$num4),
            5=>array('baoshi_id'=>5,'num'=>$num5),
            6=>array('baoshi_id'=>6,'num'=>$num6),
            7=>array('baoshi_id'=>7,'num'=>$num7),
        );
    }
    /*
     * @version 通过不同的宝石id获取不同宝石的数量
     * @param int $customer_id
     * @param int $baoshi_id
     * return $int
     */
    public function getNumById($customer_id,$baoshi_id){
        if(empty($customer_id) || empty($baoshi_id)){
            return false;
        }
        $num=BoxBaoshi::model()->count('customer_id=:customer_id and baoshi_id=:baoshi_id and is_use=0',array(':customer_id'=>$customer_id,':baoshi_id'=>$baoshi_id));
        return $num;
    }
    /*
     * @version 通过不同的宝石id获取不同宝石表id（一条数据）
     * @param int $customer_id
     * @param int $baoshi_id
     * return $int
     */
    public function getBaoShiBiaoId($customer_id,$baoshi_id){
        if(empty($customer_id) || empty($baoshi_id)){
            return false;
        }
        $baoShiArr=BoxBaoshi::model()->find('customer_id=:customer_id and baoshi_id=:baoshi_id and is_use=0',array(':customer_id'=>$customer_id,':baoshi_id'=>$baoshi_id));
        return $baoShiArr;
    }
    /*
     * @version 宝箱是否可以开启
     * @param int $customer_id
     * return boolean
     */
    public function isOpen($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $list=$this->getBaoShiArray($customer_id);
        if(empty($list)){
            return false;
        }
        if($list[1]['num']==0 || $list[2]['num']==0 || $list[3]['num']==0 || $list[4]['num']==0 || $list[5]['num']==0 || $list[6]['num']==0 || $list[7]['num']==0){
            return false;
        }else{
            return true;
        }
    }
    /*
     * @version 开启宝箱分配奖品
     * @param int $customer_id
     * return array
     */
    public function openBox($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $yan=$this->isOpen($customer_id);
        if(!$yan){
            return false;
        }
        $prizeArr=$this->checkAll($customer_id);
        foreach ($prizeArr as $key => $val) {
            $arr[$val['id']] = $val['v']; 
        }
        $rid = $this->get_rand($arr); //根据概率获取奖项id
        $list=array();
        if($rid==1){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 1, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==2){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 2, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==3){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 3, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==4){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 4, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==5){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 5, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            if($result && $result2){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==6){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 6, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            $cusArr=Customer::model()->findByPk($customer_id);
            $result3=$this->getYouHuiQuan($cusArr['mobile'],$this->prizeArr[$rid-1]['code']);
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==7){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 7, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            $cusArr=Customer::model()->findByPk($customer_id);
            $result3=$this->getYouHuiQuan($cusArr['mobile'],$this->prizeArr[$rid-1]['code']);
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==8){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 8, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            $cusArr=Customer::model()->findByPk($customer_id);
            $result3=$this->getYouHuiQuan($cusArr['mobile'],$this->prizeArr[$rid-1]['code']);
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==9){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 9, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            $cusArr=Customer::model()->findByPk($customer_id);
            $result3=$this->getYouHuiQuan($cusArr['mobile'],$this->prizeArr[$rid-1]['code']);
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==10){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 10, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            $cusArr=Customer::model()->findByPk($customer_id);
            $result3=$this->getYouHuiQuan($cusArr['mobile'],$this->prizeArr[$rid-1]['code']);
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==11){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 11, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            $cusArr=Customer::model()->findByPk($customer_id);
            $result3=$this->getYouHuiQuan($cusArr['mobile'],$this->prizeArr[$rid-1]['code']);
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==12){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 12, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$rid-1]['goods_id'], 0, $type = 'Box');
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($rid==13){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($customer_id, 13, $this->prizeArr[$rid-1]['prize_name']);
            $result2=$this->useBaoShi($customer_id);
            $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$rid-1]['goods_id'], 0, $type = 'Box');
            if($result && $result2 && $result3){
                $transaction->commit();
                return $list=array('rid'=>$rid,'prize_name'=>$this->prizeArr[$rid-1]['prize_name']);
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 判断是否今天已经放送一把或者超过了总数量
     * @param int $num
     * @param int $type 获取类型
     * return booleean
     */
    private function checkAll($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $cusArr=Customer::model()->findByPk($customer_id);
        
        $userCoupons5=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$this->prizeArr[5]['code'],':mobile'=>$cusArr['mobile']));
        $userCoupons6=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$this->prizeArr[6]['code'],':mobile'=>$cusArr['mobile']));
        $userCoupons7=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$this->prizeArr[7]['code'],':mobile'=>$cusArr['mobile']));
        $userCoupons8=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$this->prizeArr[8]['code'],':mobile'=>$cusArr['mobile']));
        $userCoupons9=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$this->prizeArr[9]['code'],':mobile'=>$cusArr['mobile']));
        $userCoupons10=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$this->prizeArr[10]['code'],':mobile'=>$cusArr['mobile']));
        $n1=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>1,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n2=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>2,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n3=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>3,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n4=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>4,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n5=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>5,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n6=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>6,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n7=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>7,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n8=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>8,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n9=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>9,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n10=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>10,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n11=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>11,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n12=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>12,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        $n13=BoxPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>13,':startDay'=>strtotime($this->startDay),':endDay'=>strtotime($this->endDay)));
        
        if($n1>=$this->prizeArr[0]['num']){
             unset($this->prizeArr[0]);
        }
        if($n2>=$this->prizeArr[1]['num']){
             unset($this->prizeArr[1]);
        }
        if($n3>=$this->prizeArr[2]['num']){
             unset($this->prizeArr[2]);
        }
        if($n4>=$this->prizeArr[3]['num']){
             unset($this->prizeArr[3]);
        }
        if($n5>=$this->prizeArr[4]['num']){
             unset($this->prizeArr[4]);
        }
        if($n6>=$this->prizeArr[5]['num'] || !empty($userCoupons5)){
            unset($this->prizeArr[5]);

        }
        if($n7>=$this->prizeArr[6]['num'] || !empty($userCoupons6)){
            if(isset($this->prizeArr[6])){
                unset($this->prizeArr[6]);
            }
        }
        if($n8>=$this->prizeArr[7]['num'] ||  !empty($userCoupons7)){
            unset($this->prizeArr[7]);
             
        }
        if($n9>=$this->prizeArr[8]['num'] || !empty($userCoupons8)){
            unset($this->prizeArr[8]);
        }
        if($n10>=$this->prizeArr[9]['num']  || !empty($userCoupons9)){
            unset($this->prizeArr[9]);   
        }
        if($n11>=$this->prizeArr[10]['num']  || !empty($userCoupons10)){
            unset($this->prizeArr[10]); 
        }
        if($n12>=$this->prizeArr[11]['num']){
             unset($this->prizeArr[11]);
        }
        if($n13>=$this->prizeArr[12]['num']){
             unset($this->prizeArr[12]);
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
    /*
     * @version 插入奖品
     * @param int $customer_id
     * @param int $prize_id(1-10)
     * @param string $prize_name
     * return array/boolean
     */
    private function insertPrize($customer_id,$prize_id,$prize_name){
        if(empty($customer_id) || empty($prize_id) || empty($prize_name)){
            return false;
        }
        $cusArr=Customer::model()->findByPk($customer_id);
        $BoxPriceModel=new BoxPrize();
        $BoxPriceModel->customer_id=$customer_id;
        $BoxPriceModel->mobile=$cusArr->mobile;
        $BoxPriceModel->prize_id=$prize_id;
        $BoxPriceModel->prize_name=$prize_name;
        $BoxPriceModel->create_time= time();
        $isInsert=$BoxPriceModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 消耗掉宝石
     * @param int $customer_id
     * return boolean
     */
    public function useBaoShi($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $baoShiArr1=$this->getBaoShiBiaoId($customer_id,1);
        $baoShiArr2=$this->getBaoShiBiaoId($customer_id,2);
        $baoShiArr3=$this->getBaoShiBiaoId($customer_id,3);
        $baoShiArr4=$this->getBaoShiBiaoId($customer_id,4);
        $baoShiArr5=$this->getBaoShiBiaoId($customer_id,5);
        $baoShiArr6=$this->getBaoShiBiaoId($customer_id,6);
        $baoShiArr7=$this->getBaoShiBiaoId($customer_id,7);
        if(empty($baoShiArr1) || empty($baoShiArr2) || empty($baoShiArr3) || empty($baoShiArr4) || empty($baoShiArr5) || empty($baoShiArr6) || empty($baoShiArr7)){
            return false;
        }else{
            $list=array();
            $list[]=$baoShiArr1['id'];
            $list[]=$baoShiArr2['id'];
            $list[]=$baoShiArr3['id'];
            $list[]=$baoShiArr4['id'];
            $list[]=$baoShiArr5['id'];
            $list[]=$baoShiArr6['id'];
            $list[]=$baoShiArr7['id'];
            $updateStr=implode(",", $list);
            $updateTime=time();
            $updateSql="update box_baoshi set is_use=1,update_time=".$updateTime." where customer_id=".$customer_id." and id in (".$updateStr.")";
            $res = Yii::app()->db->createCommand($updateSql)->execute();
            if($res){
                return true;
            }else{
                return false;
            }
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
    /*
     * @version 获奖明细
     * @param int $customer_id
     * return array 
     */
    public function getPrizeDetail($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $prizeMobileArr = BoxPrize::model()->findAll(array(
            'select'=>array('prize_name','create_time'),
            'order' => 'id DESC',
            'condition' => 'customer_id=:customer_id',
            'params' => array(':customer_id'=>$customer_id),
        ));
        return $prizeMobileArr;
    }
    /*
     * @version 分享获得宝石
     * @param int $customer_id
     * return boolean
     */
    public function getBaoShiByShare($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $sql="select * from box_baoshi where customer_id=".$customer_id." and baoshi_id=2 and create_time>=".$beginTime." and create_time<=".$endTime;
        $baoArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(empty($baoArr)){
            $res=$this->insertBaoShi($customer_id, 2);
            return $res;
        }else{
            return false;
        }
    }
    /*
     * @version 好友点亮获得宝石
     * @param int $customer_id
     * @param int $openId
     * return boolean
     */
    public function getBaoShiByDianLiang($customer_id,$open_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        //判断是否微信用户点亮
    	if (!empty($open_id)){
    		$sql="select * from box_baoshi where open_id='".$open_id."' and baoshi_id in(4,5) and create_time>=".$beginTime." and create_time<=".$endTime;
            $model=Yii::app()->db->createCommand($sql)->queryAll();
    		if (!empty($model)){
    			return -2;
    		}
    	}
        $sql4="select * from box_baoshi where customer_id=".$customer_id." and baoshi_id=4 and create_time>=".$beginTime." and create_time<=".$endTime;
        $baoArr4=Yii::app()->db->createCommand($sql4)->queryAll();
        $sql5="select * from box_baoshi where customer_id=".$customer_id." and baoshi_id=5 and create_time>=".$beginTime." and create_time<=".$endTime;
        $baoArr5=Yii::app()->db->createCommand($sql5)->queryAll();
        if(!empty($baoArr4) && !empty($baoArr5)){
            return -3;
        }
        if(!empty($baoArr4) && empty($baoArr5)){
           $baoshi_id=5; 
        }
        if(empty($baoArr4) && !empty($baoArr5)){
           $baoshi_id=4; 
        }
        if(empty($baoArr4) && empty($baoArr5)){
            $list=array(4,5);
            $key=array_rand($list);
            $baoshi_id=$list[$key]; 
        }
        $res=$this->insertBaoShi($customer_id, $baoshi_id,$open_id);
        if($res){
            return $baoshi_id;
        }else{
            false;
        }
    }
    /*
     * @version 插入宝石
     * @param int $customer_id
     * @param int $baoshi_id
     * return boolean
     */
    public function insertBaoShi($customer_id,$baoshi_id,$open_id=''){
        if(empty($customer_id) || empty($baoshi_id)){
            return false;
        }
        $BaoShiModel=new BoxBaoshi();
        $BaoShiModel->customer_id=$customer_id;
        $BaoShiModel->open_id=$open_id;
        $BaoShiModel->baoshi_id=$baoshi_id;
        $BaoShiModel->is_use=0;
        $BaoShiModel->source=$baoshi_id;
        $BaoShiModel->create_time= time();
        $isInsert=$BaoShiModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 统计数据
     * @param int $customer_id
     * @param string $open_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$open_id,$type)
    {
        $shareLog =new BoxLog();
        $shareLog->customer_id=$customer_id;
        $shareLog->open_id=$open_id;
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

