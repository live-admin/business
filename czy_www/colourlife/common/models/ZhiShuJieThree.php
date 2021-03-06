<?php
/*
 * @version 植树节三期model
 */
class ZhiShuJieThree extends CActiveRecord{
    private $startDay='2016-07-24';//活动开始时间
    private $endDay='2017-06-30 23:59:59';//活动结束时间
    //四期奖品更换时间
    private $fourStartDay='2016-10-21';
    //每天登录获得经验值配置
    private $loginArr=array(
        '1'=>1,
        '2'=>2,
        '3'=>3,
        '4'=>4,
        '5'=>5,
        '6'=>6,
        '7'=>7,
        '8'=>8,
        '9'=>9,
        '10'=>10,
    );
    private $guoshi=400;//果实的成长值
    private $jifen=array(50,150,300);
    //
    private $cmobile=20000000005;
    
    
    //抽奖奖品配置
    private $prizeArr=array(
        //50积分奖品
        '1'=>array(
            '0' => array('id'=>1,'prize_name'=>'1元饭票','amount'=>1,'num'=>600,'v'=>20),
            '1' => array('id'=>2,'prize_name'=>'休闲零食蒜香豌豆','goods_id'=>36399,'num'=>200,'v'=>5),
            '2' => array('id'=>3,'prize_name'=>'零食蚕豆牛肉味','goods_id'=>34584,'num'=>100,'v'=>5),
//            '3' => array('id'=>4,'prize_name'=>'5点经验值','v'=>270),
            '3' => array('id'=>4,'prize_name'=>'彩特供满100元减5元','code'=>100000154,'v'=>270),
            '4' => array('id'=>5,'prize_name'=>'谢谢参与','v'=>700),
        ),
        //150积分奖品
        '2'=>array(
            '0' => array('id'=>1,'prize_name'=>'3元饭票','amount'=>3,'num'=>200,'v'=>15),
            '1' => array('id'=>2,'prize_name'=>'手机自拍杆','goods_id'=>22192,'num'=>50,'v'=>10),
            '2' => array('id'=>3,'prize_name'=>'玻璃把手茶水杯','goods_id'=>36380,'num'=>50,'v'=>5),
//            '3' => array('id'=>4,'prize_name'=>'彩之云定制抱枕','goods_id'=>36491,'num'=>10,'v'=>70),
            '3' => array('id'=>4,'prize_name'=>'谢谢参与','v'=>70),
//            '4' => array('id'=>5,'prize_name'=>'10点经验值','v'=>900),
            '4' => array('id'=>5,'prize_name'=>'彩生活特供满100减10元','code'=>100000153,'v'=>900),
        ),
        //300积分奖品
        '3'=>array(
            '0' => array('id'=>1,'prize_name'=>'5元饭票','amount'=>5,'num'=>200,'v'=>10),
//            '1' => array('id'=>2,'prize_name'=>'旅行暖瓶热水壶','goods_id'=>36379,'num'=>3,'v'=>70),
            '1' => array('id'=>2,'prize_name'=>'500m流量','num'=>100,'v'=>70),
            '2' => array('id'=>3,'prize_name'=>'清风卷纸原木纯品','goods_id'=>29168,'num'=>5,'v'=>10),
            '3' => array('id'=>4,'prize_name'=>'现实智能VR眼镜','goods_id'=>28973,'num'=>3,'v'=>10),
//            '4' => array('id'=>5,'prize_name'=>'15点经验值','v'=>900),
            '4' => array('id'=>5,'prize_name'=>'彩生活特供满100元减20元','code'=>100000151,'v'=>900),
        ),
    );
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*********************************************************首页***************************************************************************/
    /*
     * @version 检查进入首页是否已经领取过种子以及新老用户
     * @param int $customer_id
     * return boolean
     */
    public function isGetZhongZi($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $zhongziArr=  TreeTwoSeed::model()->find('customer_id=:customer_id and way=:way and is_new=:is_new',array(':customer_id'=>$customer_id,':way'=>1,':is_new'=>1));
        $zhongziTwoArr=  TreeTwoSeed::model()->find('customer_id=:customer_id and way=:way and is_new=:is_new',array(':customer_id'=>$customer_id,':way'=>1,':is_new'=>2));
        $zhongziThreeArr=  TreeTwoSeed::model()->find('customer_id=:customer_id and way=:way and is_new=:is_new',array(':customer_id'=>$customer_id,':way'=>2,':is_new'=>2));
        if(!empty($zhongziArr)){
            return 2;
        }elseif(!empty($zhongziTwoArr)){
            return 3;
        }elseif(!empty($zhongziThreeArr)){
            return 4;
        }else{
            return 1;
        }
    }
    /*
     * @version 系统赠送一个种子(插入种子数据)
     * @param int $customer_id
     * return boolean 
     */
//    public function insertZhongZi($customer_id){
//        if(empty($customer_id)){
//            return false;
//        }
//        $ZhongZiModel=new TreeTwoSeed();
//        $ZhongZiModel->customer_id=$customer_id;
//        $ZhongZiModel->times=1;
//        $ZhongZiModel->way=2;
//        $ZhongZiModel->is_new=1;
//        $ZhongZiModel->is_cai=1;
//        $ZhongZiModel->create_time=time();
//        $result=$ZhongZiModel->save();
//        if($result){
//            return true;
//        }else{
//            return false;
//        }
//    }
    /*
     * @version 新用户/老用户改变状态(is_new=2)
     * @param int $customer_id
     * @type int 1:新老用户，2：彩富用户
     * return boolean
     */
    public function changeIsNewOrCaiFu($customer_id,$type){
        if(empty($customer_id) || empty($type)){
            return false;
        }
        if($type==1){
            $sqlUpdate="update tree_two_seed set is_new=2 where customer_id=".$customer_id;
        }else{
            $sqlUpdate="update tree_two_seed set is_cai=2 where customer_id=".$customer_id;
        }
        $result=Yii::app()->db->createCommand($sqlUpdate)->execute();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @versopm 彩富温馨提示
     * @param int $customer_id
     * return boolean
     */
    public function isCaiTan($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $isCaiArr=  TreeTwoSeed::model()->find('customer_id=:customer_id and is_cai=:is_cai',array(':customer_id'=>$customer_id,'is_cai'=>2));
        $propertyArr=PropertyActivity::model()->find('customer_id=:customer_id and (status=96 or status=99)',array(':customer_id'=>$customer_id));
        $appreciationArr=AppreciationPlan::model()->find('customer_id=:customer_id and status=99',array(':customer_id'=>$customer_id));
        if((!empty($propertyArr) || !empty($appreciationArr)) && empty($isCaiArr)){
            return true;
        }else{
            return false;
        }
        
    }
    /*
     * @version 判断是否是彩富人生用户
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
    /*
     * @version 根据经验值判断用户所获得的土地
     * @param int $customer_id
     * return array
     */
    public function getJingYanAndTuDi($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $list=array();
        $sqlSum="select sum(value) as summary from tree_two_experience where customer_id=".$customer_id;
        $experienceArr =Yii::app()->db->createCommand($sqlSum)->queryAll();
        $experienceTotal=$experienceArr[0]['summary'];
        if($experienceTotal>=0 && $experienceTotal<=99){
            $num=1;$reach=100;
        }elseif($experienceTotal>=100 && $experienceTotal<=299){
            $num=2;$reach=300;
        }elseif($experienceTotal>=300 && $experienceTotal<=799){
            $num=3;$reach=800;
        }elseif($experienceTotal>=800 && $experienceTotal<=1599){
            $num=4;$reach=1600;
        }elseif($experienceTotal>=1600 && $experienceTotal<=2999){
            $num=5;$reach=3000;
        }elseif($experienceTotal>=3000 && $experienceTotal<=5999){
            $num=6;$reach=6000;
        }elseif($experienceTotal>=6000 && $experienceTotal<=11999){
            $num=7;$reach=12000;
        }elseif($experienceTotal>=12000){
            $num=8;$reach=12000;
        }
        return $list=array('value'=>$experienceTotal,'num'=>$num,'reach'=>$reach);
    }

    /*
     * @version 根据经验值获取对应的土地(插入土地数据)并且获取经验值(弹框土地升级)
     * @param int $customer_id
     * return array
     */
    public function insertSeed($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $list=$this->getJingYanAndTuDi($customer_id);
        $seedNum=$this->getSeedNum($customer_id);
        if($list['num']==$seedNum || $list['num']<$seedNum){
            return false;
        }
        $str = '';
        $str2 = '';
        $create_time=time();
//        $seedId=$this->getSeedId($customer_id,1);
//        if(empty($seedId)){
//            
//        }
        $shengSeedNum=$list['num']-$seedNum;
        $transaction = Yii::app()->db->beginTransaction();
        if(!empty($list)){
            for($i=0;$i<$shengSeedNum;$i++){
                $str .= "(" .$customer_id.",1,2,1,1,".$create_time."),";
            }
            $str = trim($str, ',');
            
            $sqlInsert = "insert into tree_two_seed(customer_id,times,way,is_new,is_cai,create_time) values {$str}";
            $result = Yii::app()->db->createCommand($sqlInsert)->execute();
            $seedId=$this->getSeedId($customer_id,1);
            for($i=0;$i<$shengSeedNum;$i++){
                $str2 .= "(".$seedId.",".$customer_id.",10,4,".$create_time."),";
            }
            $str2 = trim($str2, ',');
            $sqlInsert2 = "insert into tree_two_experience(seed_id,customer_id,value,way,create_time) values {$str2}";
            $result2 = Yii::app()->db->createCommand($sqlInsert2)->execute();
            if($result && $result2){
                $transaction->commit();
                return $list;
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 根据用户id获取用户已经获得的土地数量
     * @param int $customer_id
     */
    public function getSeedNum($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $seedNum=TreeTwoSeed::model()->count('customer_id=:customer_id and way=:way',array(':customer_id'=>$customer_id,':way'=>2));
        return $seedNum;
    }
        
    /*
     * @version 每天登录获取经验值
     * @param int $customer_id
     * @return boolean
     */
    public function getExperienceValueBylogin($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr= TreeTwoExperience::model()->findAll('customer_id=:customer_id and way=:way and create_time>=:beginTime and create_time<= :endTime',array(':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime,':way'=>1));
        $num=count($zhiArr);
        if($num<1){
            $sql="select create_time from tree_two_experience where way=1 and customer_id=".$customer_id." order by  create_time desc";
            $createArr =Yii::app()->db->createCommand($sql)->queryAll();
            $day_list=array();
            if(!empty($createArr)){
                foreach ($createArr as $v){
                    $day_list[]=date('Y-m-d',$v['create_time']);
                }
                $dayNum=$this->getDays($day_list);
            }else{
                $dayNum=1;
            }
            if($dayNum>=10){
                $dayNum=10;
            }
            $isLogin=$this->insertExperienceValue($customer_id,$this->loginArr[$dayNum],1);
            if($isLogin){
                return $loginArr=array('value'=>$this->loginArr[$dayNum]);//登录后返回的数组
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 获取总的经验值
     * @param int $customer_id
     * return int 总的经验值
     */
    public function getExperienceValue($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $sql="select sum(value) as summary from tree_two_experience where customer_id=".$customer_id;
        $growArr =Yii::app()->db->createCommand($sql)->queryAll();
        $valueTotal=$growArr[0]['summary'];
        if(empty($valueTotal)){
            return 0;
        }else{
            return $valueTotal;
        }
    }
    
    /*
     * @version 获取连续的天数
     * @param array $day_list
     * return int $continue_day 连续天数
     */
    private function getDays($day_list){
        $continue_day = 1 ;//连续天数
        if(count($day_list) >= 1){
            for ($i=1; $i<=count($day_list); $i++){
                if( ( abs(( strtotime(date('Y-m-d')) - strtotime($day_list[$i-1]) ) / 86400)) == $i ){   
                   $continue_day = $i+1;  
                 }else{
                      break;       
                  }    
            }
        }
        return $continue_day;//输出连续几天
    }
    /*
     * @version 插入经验值
     * @param int $customer_id
     * @param int $value
     * @param int $way
     * return array/boolean
     */
    private function insertExperienceValue($customer_id,$value,$way){
        if(empty($customer_id) || empty($value) || empty($way)){
            return false;
        }
        $seed_id=$this->getSeedId($customer_id, 2);
        $TreeExperienceModel=new TreeTwoExperience();
        $TreeExperienceModel->seed_id=$seed_id;
        $TreeExperienceModel->customer_id=$customer_id;
        $TreeExperienceModel->value=$value;
        $TreeExperienceModel->way=$way;
        $TreeExperienceModel->create_time= time();
        $isInsert=$TreeExperienceModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 摘果实获得经验值并且减少100成长值
     * @param int $customer_id
     * @param int $seedId,土地id
     * @return boolean
     */
    public function getExperienceValueByZhai($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $isZhai=$this->isCanZhai($customer_id, $seedId);
        if($isZhai){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertGrowValue($customer_id,-100,8,$seedId);
            $result2=$this->insertExperienceValue($customer_id,50,2);
            if($result && $result2){
                $transaction->commit();
                return true;
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
        
    }
    
    /*
     * @version 根据土地id获取是否可摘果实
     * @param int $customer_id
     * @param int $seedId
     * return boolean
     */
    public function isCanZhai($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $growVaule=$this->getGrowValue($customer_id,$seedId);
        if($growVaule>=$this->guoshi){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 获取果实摘的次数
     * @param int $customer_id
     * @param int $seedId
     * return int 摘果实的次数
     */
    public function getZhaiNum($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $zhaiCount=TreeTwoGrow::model()->count('seed_id=:seed_id and customer_id=:customer_id and way=:way',array(':seed_id'=>$seedId,':customer_id'=>$customer_id,'way'=>8));
        return $zhaiCount;
    }
    
    /*
     * @version 获取之前土地的id
     * @param int $customer_id
     * @param int $way
     */
    public function getSeedId($customer_id,$way){
        if(empty($customer_id) || empty($way)){
            return false;
        }
        $treeArr=TreeTwoSeed::model()->find('customer_id=:customer_id and way=:way',array(':customer_id'=>$customer_id,':way'=>$way));
        if(!empty($treeArr)){
            return $treeArr->id;
        }else{
            $treeOtherArr=TreeTwoSeed::model()->find('customer_id=:customer_id and way=:way',array(':customer_id'=>$customer_id,':way'=>2));
            if(!empty($treeOtherArr)){
                return $treeOtherArr->id;
            }else{
                return false;
            }
        }
    }
    /****************************************************积分*********************************************************/
    /*
     * @version 获取总的积分
     * @param int $customer_id
     * return int 总的积分
     */
    public function getIntegrationValue($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $sql="select sum(value) as summary from tree_two_integration where customer_id=".$customer_id;
        $growArr =Yii::app()->db->createCommand($sql)->queryAll();
        $valueTotal=$growArr[0]['summary'];
        if(empty($valueTotal)){
            return 0;
        }else{
            return $valueTotal;
        }
    }
    /*
     * @version 验证积分
     * @param int $customer_id
     * @param int $type
     * return boolean
     */
    public function yanZheng($customer_id,$type){
        if(empty($customer_id) || empty($type)){
    		return false;
    	}
        $total=$this->getIntegrationValue($customer_id);
        if($type==1){
            if($total<$this->jifen[0]){
                return false;
            }
        }elseif($type==2){
            if($total<$this->jifen[1]){
                return false;
            }
        }else{
            if($total<$this->jifen[2]){
                return false;
            }
        }
        return true;
    }
    /*
     * @verson 积分抽奖
     * @param int $customer_id
     * @param int $type 1、50积分；2、150积分；3、300积分
     * @return boolean
     */
    public function getValueByChouJiang($customer_id,$type){
        if(empty($customer_id) || empty($type)){
            return false;
        }
        $yan=$this->yanZheng($customer_id,$type);
        if(!$yan){
            return false;
        }
        $prizeArr=$this->checkAll($type);
        foreach ($prizeArr[$type] as $key => $val) {
            $arr[$val['id']] = $val['v']; 
        }
        $rid = $this->get_rand($arr); //根据概率获取奖项id
        $list=array();
        $seedId=$this->getSeedId($customer_id, 2);
        if($type==1){
            if($rid==1){
                //扣款账户
                $redPacketCustomer = Customer::model()->find("mobile=:mobile and state=0 and is_deleted=0",array(':mobile'=>$this->cmobile));
                if(!empty($redPacketCustomer) && $redPacketCustomer->getBalance()>=$this->prizeArr[$type][$rid-1]['amount']){
                    $redPacketResult=RedPacketCarry::model()->customerTransferAccounts($redPacketCustomer->id,$customer_id,$this->prizeArr[$type][$rid-1]['amount'],1,$this->cmobile,'植树节三期活动');
                    if ($redPacketResult['status'] == 1){
                        $result2=$this->insertIntegrationValue($customer_id, -$this->jifen[0], 8,$seedId);//扣除积分
                        $result3=$this->insertPrize($customer_id, 21, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[0]);
                        if($result2 && $result3){
                            Yii::log('植树节三期活动'.date('Y-m-d',time()).$this->cmobile.'账号转饭票给'.$customer_id.'成功！转账单号：'.$redPacketResult['msg'],CLogger::LEVEL_INFO,'colourlife.core.treeThree');
                            return $list=array('type'=>1,'rid'=>$rid);
                        }else{
                            Yii::log('植树节三期活动'.date('Y-m-d',time()).'扣积分以及奖品明细失败',CLogger::LEVEL_INFO,'colourlife.core.treeThree');
                            return false;
                        }
                    }else {
                        Yii::log('植树节三期活动'.date('Y-m-d',time()).$this->cmobile.'账号转饭票给'.$customer_id.'失败！错误信息为：'.$redPacketResult['msg'],CLogger::LEVEL_ERROR,'colourlife.core.treeThree');
                        return false;
                    }
                }else{
                    Yii::log('植树节三期活动'.date('Y-m-d',time()).'饭票余额不足',CLogger::LEVEL_INFO,'colourlife.core.treeThree');
                    return false;
                }
                
//                $transaction = Yii::app()->db->beginTransaction();
//                $items = array(
//    				'customer_id' => $customer_id,//用户的ID
//    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
//    				'sum' =>$this->prizeArr[$type][$rid-1]['amount'],//红包金额,
//    				'sn' => 19,
//                );
//                $redPacked = new RedPacket();
//                $result=$redPacked->addRedPacker($items);
//                $result2=$this->insertIntegrationValue($customer_id, -$this->jifen[0], 8,$seedId);//扣除积分
//                $result3=$this->insertPrize($customer_id, 21, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[0]);
//                if($result && $result2 && $result3){
//                    $transaction->commit();
//                    return $list=array('type'=>1,'rid'=>$rid);
//                }else{
//                    $transaction->rollback();
//                    return false;
//                }
            }elseif($rid==2){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -$this->jifen[0], 8,$seedId);//扣除积分
                $result2=$this->insertPrize($customer_id, 122, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[0]);
                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieThree');
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>1,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==3){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -$this->jifen[0], 8,$seedId);//扣除积分
                $result2=$this->insertPrize($customer_id, 123, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[0]);
                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieThree');
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>1,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==4){
                $transaction = Yii::app()->db->beginTransaction();
//                $value=5;
                $result=$this->getYouHuiQuan($customer_id,$this->prizeArr[$type][$rid-1]['code']);
                $result2=$this->insertIntegrationValue($customer_id, -$this->jifen[0], 8,$seedId);//扣除积分
                $result3=$this->insertPrize($customer_id, 124, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[0]);
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>1,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==5){
                $result=$this->insertIntegrationValue($customer_id, -$this->jifen[0], 8,$seedId);//扣除积分
                if($result){
                    return $list=array('type'=>1,'rid'=>$rid);
                }else{
                    return false;
                }
            }
        }elseif($type==2){
            if($rid==1){
                $redPacketCustomer = Customer::model()->find("mobile=:mobile and state=0 and is_deleted=0",array(':mobile'=>$this->cmobile));
                if (!empty($redPacketCustomer) && $redPacketCustomer->getBalance()>=$this->prizeArr[$type][$rid-1]['amount']){
                    $redPacketResult=RedPacketCarry::model()->customerTransferAccounts($redPacketCustomer->id,$customer_id,$this->prizeArr[$type][$rid-1]['amount'],1,$this->cmobile,'植树节三期活动');
                    if ($redPacketResult['status'] == 1){
                        $result2=$this->insertIntegrationValue($customer_id, -$this->jifen[1], 8,$seedId);//扣除积分
                        $result3=$this->insertPrize($customer_id, 26, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[1]);
                        if($result2 && $result3){
                            Yii::log('植树节三期活动'.date('Y-m-d',time()).$this->cmobile.'账号转饭票给'.$customer_id.'成功！转账单号：'.$redPacketResult['msg'],CLogger::LEVEL_INFO,'colourlife.core.treeThree');
                            return $list=array('type'=>2,'rid'=>$rid);
                        }else{
                            Yii::log('植树节三期活动'.date('Y-m-d',time()).'扣积分以及奖品明细失败',CLogger::LEVEL_INFO,'colourlife.core.treeThree');
                            return false;
                        }
                    }else {
                        Yii::log('植树节三期活动'.date('Y-m-d',time()).$this->cmobile.'账号转饭票给'.$customer_id.'失败！错误信息为：'.$redPacketResult['msg'],CLogger::LEVEL_ERROR,'colourlife.core.treeThree');
                        return false;
                    }
                }else{
                    Yii::log('植树节三期活动'.date('Y-m-d',time()).'饭票余额不足',CLogger::LEVEL_INFO,'colourlife.core.treeThree');
                    return false;
                }
//                $transaction = Yii::app()->db->beginTransaction();
//                $items = array(
//    				'customer_id' => $customer_id,//用户的ID
//    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
//    				'sum' =>$this->prizeArr[$type][$rid-1]['amount'],//红包金额,
//    				'sn' => 19,
//                );
//                $redPacked = new RedPacket();
//                $result=$redPacked->addRedPacker($items);
//                $result2=$this->insertIntegrationValue($customer_id, -$this->jifen[1], 8,$seedId);//扣除积分
//                $result3=$this->insertPrize($customer_id, 26, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[1]);
//                if($result && $result2 && $result3){
//                    $transaction->commit();
//                    return $list=array('type'=>2,'rid'=>$rid);
//                }else{
//                    $transaction->rollback();
//                    return false;
//                }
            }elseif($rid==2){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -$this->jifen[1], 8,$seedId);//扣除积分
                $result2=$this->insertPrize($customer_id, 127, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[1]);
                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieThree');
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>2,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==3){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -$this->jifen[1], 8,$seedId);//扣除积分
                $result2=$this->insertPrize($customer_id, 128, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[1]);
                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieThree');
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>2,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==4){
                $result=$this->insertIntegrationValue($customer_id, -$this->jifen[1], 8,$seedId);//扣除积分
                if($result){
                    return $list=array('type'=>2,'rid'=>$rid);
                }else{
                    return false;
                }
//                $transaction = Yii::app()->db->beginTransaction();
//                $result=$this->insertIntegrationValue($customer_id, -$this->jifen[1], 8,$seedId);//扣除积分
//                $result2=$this->insertPrize($customer_id, 129, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[1]);
//                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieThree');
//                if($result && $result2 && $result3){
//                    $transaction->commit();
//                    return $list=array('type'=>2,'rid'=>$rid);
//                }else{
//                    $transaction->rollback();
//                    return false;
//                }
            }elseif($rid==5){
                $transaction = Yii::app()->db->beginTransaction();
//                $value=10;
                $result=$this->getYouHuiQuan($customer_id,$this->prizeArr[$type][$rid-1]['code']);
                $result2=$this->insertIntegrationValue($customer_id, -$this->jifen[1], 8,$seedId);//扣除积分
                $result3=$this->insertPrize($customer_id, 130, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[1]);
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>2,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }
        }elseif($type==3){
            if($rid==1){
                $redPacketCustomer = Customer::model()->find("mobile=:mobile and state=0 and is_deleted=0",array(':mobile'=>$this->cmobile));
                if (!empty($redPacketCustomer) && $redPacketCustomer->getBalance()>=$this->prizeArr[$type][$rid-1]['amount']){
                    $redPacketResult=RedPacketCarry::model()->customerTransferAccounts($redPacketCustomer->id,$customer_id,$this->prizeArr[$type][$rid-1]['amount'],1,$this->cmobile,'植树节三期活动');
                    if ($redPacketResult['status'] == 1){
                        $result2=$this->insertIntegrationValue($customer_id, -$this->jifen[2], 8,$seedId);//扣除积分
                        $result3=$this->insertPrize($customer_id, 31, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[2]);
                        if($result2 && $result3){
                            Yii::log('植树节三期活动'.date('Y-m-d',time()).$this->cmobile.'账号转饭票给'.$customer_id.'成功！转账单号：'.$redPacketResult['msg'],CLogger::LEVEL_INFO,'colourlife.core.treeThree');
                            return $list=array('type'=>3,'rid'=>$rid);
                        }else{
                            Yii::log('植树节三期活动'.date('Y-m-d',time()).'扣积分以及奖品明细失败',CLogger::LEVEL_INFO,'colourlife.core.treeThree');
                            return false;
                        }
                    }else {
                        Yii::log('植树节三期活动'.date('Y-m-d',time()).$this->cmobile.'账号转饭票给'.$customer_id.'失败！错误信息为：'.$redPacketResult['msg'],CLogger::LEVEL_ERROR,'colourlife.core.treeThree');
                        return false;
                    }
                }else{
                    Yii::log('植树节三期活动'.date('Y-m-d',time()).'饭票余额不足',CLogger::LEVEL_INFO,'colourlife.core.treeThree');
                    return false;
                }
//                $transaction = Yii::app()->db->beginTransaction();
//                $items = array(
//    				'customer_id' => $customer_id,//用户的ID
//    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
//    				'sum' =>$this->prizeArr[$type][$rid-1]['amount'],//红包金额,
//    				'sn' => 19,
//                );
//                $redPacked = new RedPacket();
//                $result=$redPacked->addRedPacker($items);
//                $result2=$this->insertIntegrationValue($customer_id, -$this->jifen[2], 8,$seedId);//扣除积分
//                $result3=$this->insertPrize($customer_id, 31, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[2]);
//                if($result && $result2 && $result3){
//                    $transaction->commit();
//                    return $list=array('type'=>3,'rid'=>$rid);
//                }else{
//                    $transaction->rollback();
//                    return false;
//                }
            }elseif($rid==2){
                //流量
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -$this->jifen[2], 8,$seedId);//扣除积分
                $result2=$this->insertPrize($customer_id, 1132, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[2]);
                $result3=$this->getLiang($customer_id);
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>3,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==3){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -$this->jifen[2], 8,$seedId);//扣除积分
                $result2=$this->insertPrize($customer_id, 133, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[2]);
                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieThree');
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>3,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==4){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -$this->jifen[2], 8,$seedId);//扣除积分
                $result2=$this->insertPrize($customer_id, 134, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[2]);
                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieThree');
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>3,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==5){
                $transaction = Yii::app()->db->beginTransaction();
//                $value=10;
                $result=$this->getYouHuiQuan($customer_id,$this->prizeArr[$type][$rid-1]['code']);
                $result2=$this->insertIntegrationValue($customer_id, -$this->jifen[2], 8,$seedId);//扣除积分
                $result3=$this->insertPrize($customer_id, 35, $this->prizeArr[$type][$rid-1]['prize_name'], $this->jifen[2]);
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>3,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }
        }
        else{
            return false;
        }
    }
    /*
     * @version 判断是否今天已经放送一把或者超过了总数量
     * @param int $num
     * @param int $type 获取类型
     * return booleean
     */
    private function checkAll($type){
        if(empty($type)){
            return false;
        }
        if($type==1){
            $n21=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>21,':startDay'=>strtotime($this->fourStartDay),':endDay'=>strtotime($this->endDay)));
            $n22=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>122,':startDay'=>strtotime($this->fourStartDay),':endDay'=>strtotime($this->endDay)));
            $n23=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>123,':startDay'=>strtotime($this->fourStartDay),':endDay'=>strtotime($this->endDay)));
            if($n21>=$this->prizeArr[$type][0]['num']){
                 unset($this->prizeArr[$type][0]);
            }
            if($n22>=$this->prizeArr[$type][1]['num']){
                 unset($this->prizeArr[$type][1]);
            }
            if($n23>=$this->prizeArr[$type][2]['num']){
                 unset($this->prizeArr[$type][2]);
            }
        }elseif($type==2){
            $n26=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>26,':startDay'=>strtotime($this->fourStartDay),':endDay'=>strtotime($this->endDay)));
            $n27=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>127,':startDay'=>strtotime($this->fourStartDay),':endDay'=>strtotime($this->endDay)));
            $n28=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>128,':startDay'=>strtotime($this->fourStartDay),':endDay'=>strtotime($this->endDay)));
           // $n29=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>129,':startDay'=>strtotime($this->fourStartDay),':endDay'=>strtotime($this->endDay)));
            if($n26>=$this->prizeArr[$type][0]['num']){
                 unset($this->prizeArr[$type][0]);
            }
            if($n27>=$this->prizeArr[$type][1]['num']){
                 unset($this->prizeArr[$type][1]);
            }
            if($n28>=$this->prizeArr[$type][2]['num']){
                 unset($this->prizeArr[$type][2]);
            }
//            if($n29>=$this->prizeArr[$type][3]['num']){
//                 unset($this->prizeArr[$type][3]);
//            }
        }elseif($type==3){
            $n31=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>31,':startDay'=>strtotime($this->fourStartDay),':endDay'=>strtotime($this->endDay)));
            $n32=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>1132,':startDay'=>strtotime($this->fourStartDay),':endDay'=>strtotime($this->endDay)));
            $n33=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>133,':startDay'=>strtotime($this->fourStartDay),':endDay'=>strtotime($this->endDay)));
            $n34=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>134,':startDay'=>strtotime($this->fourStartDay),':endDay'=>strtotime($this->endDay)));
            if($n31>=$this->prizeArr[$type][0]['num']){
                 unset($this->prizeArr[$type][0]);
            }
            if($n32>=$this->prizeArr[$type][1]['num']){
                 unset($this->prizeArr[$type][1]);
            }
            if($n33>=$this->prizeArr[$type][2]['num']){
                 unset($this->prizeArr[$type][2]);
            }
            if($n34>=$this->prizeArr[$type][3]['num']){
                 unset($this->prizeArr[$type][3]);
            }
        }
        return $this->prizeArr;
    }
    /*
     * @version 插入积分
     * @param int $customer_id
     * @param int $value
     * @param int $way
     * @param int $seedId
     * return array/boolean
     */
    public function insertIntegrationValue($customer_id,$value,$way,$seedId){
        if(empty($customer_id) || empty($value) || empty($way) || empty($seedId)){
            return false;
        }
        $TreeIntegrationModel=new TreeTwoIntegration();
        $TreeIntegrationModel->seed_id=$seedId;
        $TreeIntegrationModel->customer_id=$customer_id;
        $TreeIntegrationModel->value=$value;
        $TreeIntegrationModel->way=$way;
        $TreeIntegrationModel->create_time= time();
        $isInsert=$TreeIntegrationModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 插入奖品
     * @param int $customer_id
     * @param int $prize_id(21-35)
     * @param string $prize_name
     * @param int $integration
     * return array/boolean
     */
    private function insertPrize($customer_id,$prize_id,$prize_name,$integration){
        if(empty($customer_id) || empty($prize_id) || empty($prize_name) || empty($integration)){
            return false;
        }
        $cusArr=Customer::model()->findByPk($customer_id);
        $TreePriceModel=new TreeTwoPrize();
        $TreePriceModel->mobile=$cusArr->mobile;
        $TreePriceModel->prize_id=$prize_id;
        $TreePriceModel->prize_name=$prize_name;
        $TreePriceModel->integration=$integration;
        $TreePriceModel->create_time= time();
        $isInsert=$TreePriceModel->save();
        if($isInsert){
            return true;
        }else{
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
    /**********************************************成长值*******************************************************************/
    /*
     * @version 获取每块土地总的成长值
     * @param int $customer_id
     * @param int $seedId
     * return int 总的成长值
     */
    public function getGrowValue($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $sql="select sum(value) as summary from tree_two_grow where customer_id=".$customer_id." and seed_id=".$seedId;
        $growArr =Yii::app()->db->createCommand($sql)->queryAll();
        $valueTotal=$growArr[0]['summary'];
        if(empty($valueTotal)){
            return 0;
        }else{
            return $valueTotal;
        }
    }
    /*
     * @version 插入成长值
     * @param int $customer_id
     * @param int $value
     * @param int $way
     * @param int $seedId
     * return array/boolean
     */
    public function insertGrowValue($customer_id,$value,$way,$seedId){
        if(empty($customer_id) || empty($value) || empty($way) || empty($seedId)){
            return false;
        }
        $TreeGrowModel=new TreeTwoGrow();
        $TreeGrowModel->seed_id=$seedId;
        $TreeGrowModel->customer_id=$customer_id;
        $TreeGrowModel->open_id='';
        $TreeGrowModel->value=$value;
        $TreeGrowModel->way=$way;
        $TreeGrowModel->create_time= time();
        $isInsert=$TreeGrowModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*1
     * @verson 自己浇水
     * @param int $customer_id
     * @param int $seedId
     * @return boolean
     */
    public function getGrowValueByJiaoShui($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $check=$this->checkJiaoShui($customer_id,$seedId);
        if(!$check){
            $list=$this->getJingYanAndTuDi($customer_id);
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertGrowValue($customer_id, $list['num'], 1,$seedId);
            $result2=$this->insertIntegrationValue($customer_id, $list['num'], 1,$seedId);
            if($result && $result2){
                $transaction->commit();
                return $list['num'];
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*1-1
     * @version 一键浇水
     * @param int $customer_id
     */
    public function getGrowValueByOneKeyJiaoShui($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $resultNum=$this->oneKeyJiaoShui($customer_id);
        return $resultNum;
    }
    /*3
    * @param int $customer_id
    * @param int $seed_id
    * @param string $openID
    * return boolean
    */
    public function getGrowValueByOtherJiao($seedId,$customer_id,$openId=''){
    	if(empty($seedId) || empty($customer_id)){
    		return false;
    	}
    	//判断种子是否存在
    	$seed = TreeTwoSeed::model ()->find ( "id=:seed_id and customer_id=:customer_id", array (
				':seed_id' => $seedId,
				':customer_id' => $customer_id 
		) );
    	if (empty($seed)){
    		return -1;
    	}
    	$beginTime = mktime(0,0,0);
    	$endTime = time();
    	$list_id=$this->getAllSeedId($customer_id);
        $sql="select * from tree_two_grow where seed_id=".$list_id[0]." and way=3 and create_time>=".$beginTime." and create_time<=".$endTime;
        $zhiArr=Yii::app()->db->createCommand($sql)->queryAll();
    	//判断朋友浇水次数是否大于1次
    	if (!empty($zhiArr)&&count($zhiArr)>=5){
    		return -2;
    	}
    	//判断是否微信用户浇水
    	if (!empty($openId)){
    		$sql="select * from tree_two_grow where open_id='".$openId."' and seed_id=".$list_id[0]." and way=3 and create_time>=".$beginTime." and create_time<=".$endTime;
            $model=Yii::app()->db->createCommand($sql)->queryAll();
    		if (!empty($model)){
    			return -3;
    		}
    	}
        $str='';
        $str2='';
        $list=$this->getJingYanAndTuDi($customer_id);
        $value=$list['num'];
        if(!empty($list_id)){
            $create_time=time();
            foreach ($list_id as $key=>$v){
                $str .= "(" .$v.",".$customer_id.",'".$openId."',".$value.",3,".$create_time."),";
                $str2 .= "(" .$v.",".$customer_id.",".$value.",3,".$create_time."),";
            }
            if(empty($str) || empty($str2)){
                return false;
            }
            $transaction = Yii::app()->db->beginTransaction();
            $str = trim($str, ',');
            $str2 = trim($str2, ',');
            $sqlInsert = "insert into tree_two_grow(seed_id,customer_id,open_id,value,way,create_time) values {$str}";
            $result = Yii::app()->db->createCommand($sqlInsert)->execute();
            $sqlInsert2 = "insert into tree_two_integration(seed_id,customer_id,value,way,create_time) values {$str2}";
            $result2 = Yii::app()->db->createCommand($sqlInsert2)->execute();
            if($result && $result2){
                $transaction->commit();
                return $result*$value;
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*4
     * @verson 邀请注册
     * @param int $customer_id
     * @param int $seedId
     * @return boolean
     */
    public function getGrowValueByYaoQing($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $beginTime = strtotime($this->startDay);
        $endTime = time();
        $n=$this->getYaoQingNum($customer_id);
        if($n){
            $value=$n*10;
            $result=$this->insertGrowValue($customer_id, $value, 4,$seedId);
            $result2=$this->insertIntegrationValue($customer_id, $value, 4,$seedId);
            $sqlUpdate="update invite set is_send=1 where customer_id=".$customer_id." and create_time>=".$beginTime." and create_time<= " .$endTime. " and is_send=0 and `status`=1 and effective=1";
            $m=Yii::app()->db->createCommand($sqlUpdate)->execute();
            if($m){
                $result3=true;
            }else{
                $result3=false;
            }
            if($result && $result2 && $result3){
                $transaction->commit();
                return $yaoQingRes=array('res'=>$n,'value'=>$value);
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*5-1
     * @verson 下单（星辰旅游）
     * @param int $customer_id
     * @param int $seedId
     * @return boolean
     */
    public function getGrowValueByXingChenOrder($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $orderArr=$this->getXingChenOrderTan($customer_id,1);
        if(!empty($orderArr)){
            $resArr=array();
            $updateArr=array();
            foreach ($orderArr as $order){
                $updateArr[]=$order->id;
            }
            $updateStr=implode(",", $updateArr);
            $updateSql="update third_fees set is_send=2 where model='thirdFrees111' and customer_id=".$customer_id." and id in (".$updateStr.")";
            $res = Yii::app()->db->createCommand($updateSql)->execute();
            $value=$res*5;
            $res2=$this->insertGrowValue($customer_id,$value,5,$seedId);
            $res3=$this->insertIntegrationValue($customer_id,$value,5,$seedId);
            if($res && $res2 && $res3){
                $transaction->commit();
                return $resArr=array('res'=>$res,'value'=>$value);
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*5-2
     * @verson 下单（京东）
     * @param int $customer_id
     * @param int $seedId
     * @return boolean
     */
    public function getGrowValueByJingDongOrder($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $orderArr=$this->getJingDongOrderTan($customer_id,1);
        if(!empty($orderArr)){
            $updateArr=array();
            foreach ($orderArr as $order){
                $updateArr[]=$order->id;
            }
            $updateStr=implode(",", $updateArr);
            $updateSql="update `order` set is_send=2 where seller_id=".Item::JD_SELL_ID." and buyer_id=".$customer_id." and id in (".$updateStr.")";
            $res = Yii::app()->db->createCommand($updateSql)->execute();
            $value=$res*5;
            $res2=$this->insertGrowValue($customer_id,$value,5,$seedId);
            $res3=$this->insertIntegrationValue($customer_id,$value,5,$seedId);
            if($res && $res2 && $res3){
                $transaction->commit();
                return $resArr=array('res'=>$res,'value'=>$value);
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*5-3
     * @verson 下单（环球精选）
     * @param int $customer_id
     * @param int $seedId
     * @return boolean
     */
    public function getGrowValueByHuanQiuOrder($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $orderArr=$this->getHuanQiuOrderTan($customer_id,1);
        if(!empty($orderArr)){
            $updateArr=array();
            foreach ($orderArr as $order){
                $updateArr[]=$order->id;
            }
            $updateStr=implode(",", $updateArr);
            $updateSql="update third_fees set is_send=2 where model='thirdFrees107' and customer_id=".$customer_id." and id in (".$updateStr.")";
            $res = Yii::app()->db->createCommand($updateSql)->execute();
            $value=$res*5;
            $res2=$this->insertGrowValue($customer_id,$value,5,$seedId);
            $res3=$this->insertIntegrationValue($customer_id,$value,5,$seedId);
            if($res && $res2 && $res3){
                $transaction->commit();
                return $resArr=array('res'=>$res,'value'=>$value);
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*5-4
     * @verson 下单（彩生活特供）
     * @param int $customer_id
     * @param int $seedId
     * @return boolean
     */
    public function getGrowValueByCaiTeGongOrder($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $orderArr=$this->getCaiTeGongOrderTan($customer_id,1);
        if(!empty($orderArr)){
            $updateArr=array();
            foreach ($orderArr as $order){
                $updateArr[]=$order->id;
            }
            $updateStr=implode(",", $updateArr);
            $updateSql="update `order` set is_send=2 where seller_id in (2607,5043,5018,5016,5019,5021,5022,5039,4999,5031,5030,5029,5036,5040,5044,5047,4990) and buyer_id=".$customer_id." and id in (".$updateStr.")";
            $res = Yii::app()->db->createCommand($updateSql)->execute();
            $value=$res*5;
            $res2=$this->insertGrowValue($customer_id,$value,5,$seedId);
            $res3=$this->insertIntegrationValue($customer_id,$value,5,$seedId);
            if($res && $res2 && $res3){
                $transaction->commit();
                return $resArr=array('res'=>$res,'value'=>$value);
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*9
     * @version 邀请彩富
     * @param int $customer_id
     * @param int $seedId
     * return boolean
     */
    public function getGrowValueByTuiJianRen($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $beginTime = strtotime($this->startDay);
        $endTime = time();
        $n=$this->getTuiJianNum($customer_id);
        $cusArr=Customer::model()->findByPk($customer_id);
        if($n){
            $value=$n*20;
            $result=$this->insertGrowValue($customer_id, $value, 9,$seedId);
            $result2=$this->insertIntegrationValue($customer_id, $value, 9,$seedId);
            $sqlUpdate1="update property_activity set activity_send=1 where inviter_mobile='".$cusArr['mobile']."' and create_time>=".$beginTime." and create_time<= " .$endTime. " and activity_send=0 and (status=96 or status=99)";
            $m1=Yii::app()->db->createCommand($sqlUpdate1)->execute();
            $sqlUpdate2="update appreciation_plan set activity_send=1 where inviter_mobile='".$cusArr['mobile']."' and create_time>=".$beginTime." and create_time<= " .$endTime. " and activity_send=0 and status=99";
            $m2=Yii::app()->db->createCommand($sqlUpdate2)->execute();
            $m=$m1+$m2;
            if($m){
                $result3=true;
            }else{
                $result3=false;
            }
            if($result && $result2 && $result3){
                $transaction->commit();
                return $resArr=array('res'=>$n,'value'=>$value);
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*11
     * @verson 点击分享按钮,分享到邻里
     * @param int $customer_id
     * @param int $seed_id
     * @return boolean
     */
    public function getGrowValueByShare($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $list_id=$this->getAllSeedId($customer_id);
        $listIdStr=implode(",", $list_id);
        $sql="select * from tree_two_grow where customer_id=".$customer_id." and seed_id in(".$listIdStr.") and way=11 and create_time>=".$beginTime." and create_time<=".$endTime;
        $zhiArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(empty($zhiArr)){
            $str='';
            $str2='';
            if(!empty($list_id)){
                $create_time=time();
                foreach ($list_id as $key=>$v){
                    $str .= "(" .$v.",".$customer_id.",'',10,11,".$create_time."),";
                    $str2 .= "(" .$v.",".$customer_id.",10,11,".$create_time."),";
                }
                if(empty($str) || empty($str2)){
                    return false;
                }
                $transaction = Yii::app()->db->beginTransaction();
                $str = trim($str, ',');
                $str2 = trim($str2, ',');
                $sqlInsert = "insert into tree_two_grow(seed_id,customer_id,open_id,value,way,create_time) values {$str}";
                $result = Yii::app()->db->createCommand($sqlInsert)->execute();
                $sqlInsert2 = "insert into tree_two_integration(seed_id,customer_id,value,way,create_time) values {$str2}";
                $result2 = Yii::app()->db->createCommand($sqlInsert2)->execute();
                if($result && $result2){
                    $transaction->commit();
                    return true;
                }else{
                    $transaction->rollback();
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * 获取浇水次数
     * @param int $seed_id  
     * @param int $type  类型
     * @return int 
     */
    public function getWaterNum($seed_id,$type){
    	$num=0;
    	if(empty($seed_id)){
    		return $num;
    	}
    	$beginTime = mktime(0,0,0);
    	$endTime = time();
    	$zhiArr = TreeTwoGrow::model ()->findAll ( 'seed_id=:seed_id and way=:way and create_time>=:beginTime and create_time<= :endTime', array (
				':seed_id' => $seed_id,
				':beginTime' => $beginTime,
				':endTime' => $endTime,
				':way' => $type 
		) );
    	if (!empty($zhiArr)){
    		$num=count($zhiArr);
    	}
    	return $num;
    }
    /*12
     * @verson 点击分享按钮,分享到第三方
     * @param int $customer_id
     * @param int $seed_id
     * @return boolean
     */
    public function getGrowValueByShareOther($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr= TreeTwoGrow::model()->find('seed_id=:seed_id and customer_id=:customer_id and way=:way and create_time>=:beginTime and create_time<= :endTime',array(':seed_id'=>$seedId,':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime,':way'=>12));
        if(empty($zhiArr)){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertGrowValue($customer_id, 10, 12,$seedId);
            $result2=$this->insertIntegrationValue($customer_id, 10, 12,$seedId);
            if($result && $result2){
                $transaction->commit();
                return true;
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 通过用户id获
     */
    /*
     * @version 判断是否有星辰旅游的订单
     * @param int $customer_id
     * @param int $type
     * return boolean/array
     */
    public function getXingChenOrderTan($customer_id,$type){
        if(empty($customer_id) || empty($type)){
            return false;
        }
        $beginTime=strtotime($this->startDay);
        $endTime=strtotime($this->endDay);
        $xingChenOrderArr=  ThirdFees::model()->findAll('model=:model and customer_id=:customer_id and (status=1 or status=99) and create_time>=:beginTime and create_time<= :endTime and is_send=1', array(':model'=>'thirdFrees111',':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime));
        if(!empty($xingChenOrderArr)){
            if($type==1){
                return $xingChenOrderArr;
            }else{
                $res=count($xingChenOrderArr);
                $value=$res*5;
                $xingChen=array('res'=>$res,'value'=>$value);
                return $xingChen;
            }
            
        }else{
            return false;
        }
    }
    /*
     * @version 判断是否有京东特供的订单
     * @param int $customer_id
     * @param int $type
     * return boolean/array
     */
    public function getJingDongOrderTan($customer_id,$type){
        if(empty($customer_id) || empty($type)){
            return false;
        }
        $beginTime=strtotime($this->startDay);
        $endTime=strtotime($this->endDay);
        $jingDongOrderArr=  Order::model()->findAll('seller_id=:seller_id and buyer_id=:buyer_id and status in (1,3,4,99) and create_time>=:beginTime and create_time<= :endTime and is_send=1', array(':seller_id'=>  Item::JD_SELL_ID,':buyer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime));
        if(!empty($jingDongOrderArr)){
            if($type==1){
                return $jingDongOrderArr;
            }else{
                $res=count($jingDongOrderArr);
                $value=$res*5;
                $jingDong=array('res'=>$res,'value'=>$value);
                return $jingDong;
            }
            
        }else{
            return false;
        }
    }
    /*
     * @version 判断是否有环球精选的订单
     * @param int $customer_id
     * @param int $type
     * return boolean/array
     */
    public function getHuanQiuOrderTan($customer_id,$type){
        if(empty($customer_id) || empty($type)){
            return false;
        }
        $beginTime=strtotime($this->startDay);
        $endTime=strtotime($this->endDay);
        $huanQiuOrderArr=  ThirdFees::model()->findAll('model=:model and customer_id=:customer_id and (status=1 or status=99) and create_time>=:beginTime and create_time<= :endTime and is_send=1', array(':model'=>'thirdFrees107',':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime));
        if(!empty($huanQiuOrderArr)){
            if($type==1){
                return $huanQiuOrderArr;
            }else{
                $res=count($huanQiuOrderArr);
                $value=$res*5;
                $huanQiu=array('res'=>$res,'value'=>$value);
                return $huanQiu;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 判断是否有彩生活特供的订单
     * @param int $customer_id
     * @param int $type
     * return boolean/array
     */
    public function getCaiTeGongOrderTan($customer_id,$type){
        if(empty($customer_id) || empty($type)){
            return false;
        }
        $beginTime=strtotime($this->startDay);
        $endTime=strtotime($this->endDay);
        $CaiTeGongOrderArr=  Order::model()->findAll('seller_id in (2607,5043,5018,5016,5019,5021,5022,5039,4999,5031,5030,5029,5036,5040,5044,5047,4990) and buyer_id=:buyer_id and status in (1,3,4,99) and create_time>=:beginTime and create_time<= :endTime and is_send=1', array(':buyer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime));
        if(!empty($CaiTeGongOrderArr)){
            if($type==1){
                return $CaiTeGongOrderArr;
            }else{
                $res=count($CaiTeGongOrderArr);
                $value=$res*5;
                $caiTeGong=array('res'=>$res,'value'=>$value);
                return $caiTeGong;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 获取邀请人数
     * @param int $customer_id
     * return int 人数
     */
    public function getYaoQingNum($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = strtotime($this->startDay);
        $endTime = time();
        $n=Invite::model()->count('customer_id=:customer_id and create_time>=:beginTime and create_time<= :endTime and is_send=0 and `status`=1 and effective=1',array(':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime));
        return $n;
    }
    /*
     * @version 获取是推荐人的人数
     * @param int $customer_id
     * return int 人数
     */
    public function getTuiJianNum($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $cusArr=Customer::model()->findByPk($customer_id);
        $beginTime = strtotime($this->startDay);
        $endTime = time();
        $n1=PropertyActivity::model()->count('inviter_mobile=:inviter_mobile and create_time>=:beginTime and create_time<= :endTime and activity_send=0 and (status=96 or status=99)',array(':inviter_mobile'=>$cusArr['mobile'],':beginTime'=>$beginTime,':endTime'=>$endTime));
        $n2=AppreciationPlan::model()->count('inviter_mobile=:inviter_mobile and create_time>=:beginTime and create_time<= :endTime and activity_send=0 and status=99',array(':inviter_mobile'=>$cusArr['mobile'],':beginTime'=>$beginTime,':endTime'=>$endTime));
        $n=$n1+$n2;
        return $n;
    }
    /*
     * @version 判断当天的土地是否已经浇水过
     * @param int $customer_id
     * @param int $seedId
     * return boolean
     */
    public function checkJiaoShui($customer_id,$seedId){
        if(empty($customer_id) || empty($seedId)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr= TreeTwoGrow::model()->find('seed_id=:seed_id and customer_id=:customer_id and way=:way and create_time>=:beginTime and create_time<= :endTime',array(':seed_id'=>$seedId,':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime,':way'=>1));
        if(!empty($zhiArr)){
            return true;
        }else{
            return false;
        }
        
    }
    /*
     * @version 通过用户id获取用户土地的所有id
     * @param int $customer_id
     * return array
     */
    public function getAllSeedId($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $seedArr=TreeTwoSeed::model()->findAll('customer_id=:customer_id and way=:way',array(':customer_id'=>$customer_id,':way'=>2));
        if(!empty($seedArr)){
            $list_id=array();
            foreach ($seedArr as $key=>$v){
                $list_id[]=$v['id'];
            }
            return $list_id;
        }else{
            return false;
        }
    }
    /*
     * @version 通过用户id获取用户土地的所有id和果实是否可摘
     * @param int $customer_id
     * return array
     */
    public function getAllSeedIdAndGuoShi($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $seedArr=TreeTwoSeed::model()->findAll('customer_id=:customer_id and way=:way',array(':customer_id'=>$customer_id,':way'=>2));
        if(!empty($seedArr)){
            $listIdZhai=array();
            foreach ($seedArr as $key=>$v){
                $isZhai=$this->isCanZhai($customer_id,$v['id']);
                if($isZhai){
                    $zhai=1;
                }else{
                    $zhai=0;
                }
                $listIdZhai[$key]['id']=$v['id'];
                $listIdZhai[$key]['zhai']=$zhai;
            }
            return $listIdZhai;
        }else{
            return false;
        }
    }
    /*
     * @version 通过用户id获取用户土地的所有id和new
     * @param int $customer_id
     * return array
     */
    public function getAllSeedIdAndNew($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $seedArr=TreeTwoSeed::model()->findAll('customer_id=:customer_id and way=:way',array(':customer_id'=>$customer_id,':way'=>2));
        if(!empty($seedArr)){
            $listIdNew=array();
            foreach ($seedArr as $key=>$v){
                $seedArr=TreeTwoSeed::model()->findByPk($v['id']);
                if($seedArr['is_click']==2){
                    $new=1;
                }else{
                    $new=0;
                }
                $listIdNew[$key]['id']=$v['id'];
                $listIdNew[$key]['new']=$new;
            }
            return $listIdNew;
        }else{
            return false;
        }
    }
    /*
     * @version 通过用户id获取用户土地的所有id和new
     * @param int $customer_id
     * return array
     */
    public function getAllSeedIdAndValue($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $seedArr=TreeTwoSeed::model()->findAll('customer_id=:customer_id and way=:way',array(':customer_id'=>$customer_id,':way'=>2));
        if(!empty($seedArr)){
            $listIdValue=array();
            foreach ($seedArr as $key=>$v){
                $value=ZhiShuJieThree::model()->getGrowValue($customer_id,$v['id']);
                $listIdValue[$key]['id']=$v['id'];
                $listIdValue[$key]['value']=$value;
            }
            return $listIdValue;
        }else{
            return false;
        }
    }
    /*
     * @version 一键浇水数据插入
     * @param int $customer_id
     * return boolean
     */
    public function oneKeyJiaoShui($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $isCai=$this->isCaiFuUser($customer_id);
        if($isCai){
            $list=$this->getJingYanAndTuDi($customer_id);
            $value=$list['num'];
            $list_id=$this->getAllSeedId($customer_id);
            $str='';
            $str2='';
            if(!empty($list_id)){
                $create_time=time();
                $i=1;
                foreach ($list_id as $key=>$v){
                    $check=$this->checkJiaoShui($customer_id, $v);
                    if($check){
                        continue;
                    }
                    $str .= "(" .$v.",".$customer_id.",'',".$value.",1,".$create_time."),";
                    $str2 .= "(" .$v.",".$customer_id.",".$value.",1,".$create_time."),";
                    $i++;
                    if($i>$list['num']){
                        break;
                    }
                }
                if(empty($str) || empty($str2)){
                    return false;
                }
                $transaction = Yii::app()->db->beginTransaction();
                $str = trim($str, ',');
                $str2 = trim($str2, ',');
                $sqlInsert = "insert into tree_two_grow(seed_id,customer_id,open_id,value,way,create_time) values {$str}";
                $result = Yii::app()->db->createCommand($sqlInsert)->execute();
                $sqlInsert2 = "insert into tree_two_integration(seed_id,customer_id,value,way,create_time) values {$str2}";
                $result2 = Yii::app()->db->createCommand($sqlInsert2)->execute();
                if($result && $result2){
                    $transaction->commit();
                    return $result*$value;
                }else{
                    $transaction->rollback();
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
        
    }
    /***************************************************奖品列表*************************************************************************/
    /*
     * @version 获奖明细
     * @param int $customer_id
     * return array 
     */
    public function getPrizeDetail($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $cusArr=Customer::model()->findByPk($customer_id);
        $prizeMobileArr = TreeTwoPrize::model()->findAll(array(
            'select'=>array('prize_name','integration','create_time'),
            'order' => 'id DESC',
            'condition' => 'mobile=:mobile',
            'params' => array(':mobile'=>$cusArr->mobile),
        ));
        return $prizeMobileArr;
    }
    /*
     * @version 获取最新的十条获奖信息
     * @param int $customer_id
     * return array 
     */
    public function getTopTenPrize($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $sql="select mobile,prize_name from tree_two_prize order by id desc limit 10";
        $jiangArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($jiangArr)){
            return $jiangArr;
        }else{
            return false;
        }
    }
    /*
     * @version 记录分享
     * @param int $customer_id
     * @param string $open_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$open_id,$type)
    {
        $shareLog =new TreeThreeLog();
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
    /*
     * @version 检测是否升级领取积分了
     * @param int $customer_id
     * @param int $way
     */
    public function getJiFenByShengJi($customer_id,$way){
        if(empty($customer_id) || empty($way)){
            return false;
        }
        $checkJiFen=TreeTwoIntegration::model()->find('customer_id=:customer_id and way=:way',array(':customer_id'=>$customer_id,':way'=>$way));
        if(!empty($checkJiFen)){
            return false;
        }else{
            return true;
        }
    }
    /**
	 * @version 领取优惠券
     * @param int $customer_id
     * @param int $youhuiquan
     * return boolean
	 */
	public function getYouHuiQuan($customer_id,$youhuiquan){
		if (empty($customer_id) || empty($youhuiquan)){
			return false;
		}
        $cusArr=Customer::model()->findByPk($customer_id);
        $mobile=$cusArr['mobile'];
		//添加优惠券
		$userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(":you_hui_quan_id" => $youhuiquan,":mobile" => $mobile));
		if(empty($userCouponsArr)){
			$uc_model=new UserCoupons();
			$uc_model->mobile=$mobile;
			$uc_model->you_hui_quan_id=$youhuiquan;
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
     * @version 获取流量
     * @param int $customer_id
     * return boolean
     */
    public function getLiang($customer_id){
        if (empty($customer_id)){
			return false;
		}
        $cusArr=Customer::model()->findByPk($customer_id);
        $mobile=$cusArr['mobile'];
        $ext_id=$this->randomExt(10, $chars = '0123456789');
        $liuObject = new ChuangLan();
        $orderData = array(
            'mobile'=>$mobile,
            'package'=>'00500',
            'ext_id'=>$ext_id,
        );
        $result= $liuObject->getLiuLiang($orderData);
        if($result['code']=='0'){
            return true;
        }else{
            return false;
        }
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
    
}