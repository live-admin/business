<?php
/*
 * @version 植树节model
 */
class ZhiShuJie extends CActiveRecord{
    private $_startDay='2016-03-10';//活动开始时间
    private $_endDay='2016-03-20';//活动结束时间
    private $growArr=array(300,500,800,1000);//种子成长值临界点
    private $prizeArr=array(
        '1'=>array('prize'=>1,'prize_name'=>'环球精选满100元减10元*2 '),
        '2'=>array('prize'=>2,'prize_name'=>'彩生活特供满100元减20元*2'),
        '3'=>array('prize'=>3,'prize_name'=>'环球精选满200元减20元*1,彩生活特供满200元减50元*2'),
        '4'=>array('1'=>array('id'=>1,'prize'=>4,'prize_name'=>'环球精选满300元减30元*5,彩生活特供满200元减50元*5','v'=>50,'price'=>0),'2'=>array('id'=>2,'prize'=>4,'prize_name'=>'10元饭票','v'=>30,'price'=>10),'3'=>array('id'=>3,'prize'=>4,'prize_name'=>'30元饭票','v'=>15,'price'=>30),'4'=>array('id'=>4,'prize'=>4,'prize_name'=>'50元饭票','v'=>5,'price'=>50)),
    );
    //优惠券
    private $youHuiQuanArr=array(
        100000070,
        100000071,
        100000072,
        100000073,
        100000074,
        100000075,
    );
    //饭票
    private $fanPiaoArr=array('10元饭票','30元饭票','50元饭票');
    //自己浇水成长值配置
    private $jiaoArr=array(
        '1'=>10,
        '2'=>20,
        '3'=>30,
        '4'=>40,
        '5'=>50,
        '6'=>60,
        '7'=>70,
        '8'=>80,
        '9'=>90,
        '10'=>100,
        '11'=>110,
    );
    //邀请注册每天限制50点成长值
    private $yaoQingValue=50;
    


    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 检查是否已经种植，领取种子了
     * @param string $mobile
     * return boolean
     */
    public function isZhongZi($mobile){
        if(empty($mobile)){
            return false;
        }
        $zhongziArr=ZhiUserZhongzi::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
        if(empty($zhongziArr)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 开始种植获取一颗种子
     * @param string $mobile
     * return boolean 
     */
    public function getZhongZi($mobile){
        if(empty($mobile)){
            return false;
        }
        $ZhongZiModel=new ZhiUserZhongzi();
        $ZhongZiModel->mobile=$mobile;
        $ZhongZiModel->status=1;
        $ZhongZiModel->times=1;
        $ZhongZiModel->create_time=time();
        $result=$ZhongZiModel->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 判断是否是彩富人生用户以及是否已经领取过成长值了，来决定是否弹框
     * @param string $mobile
     * return boolean
     */
    public function isCaiFuUser($mobile){
        if(empty($mobile)){
            return false;
        }
        $cust_id=$this->getCustIdByMobile($mobile);
        $isLingCaiArr=ZhiUserGrow::model()->find('mobile=:mobile and type=:type',array(':mobile'=>$mobile,':type'=>1));
        $propertyArr=PropertyActivity::model()->find('customer_id=:customer_id and (status=96 or status=99)',array(':customer_id'=>$cust_id));
        $appreciationArr=AppreciationPlan::model()->find('customer_id=:customer_id and status=99',array(':customer_id'=>$cust_id));
        if((!empty($propertyArr) || !empty($appreciationArr)) && empty($isLingCaiArr)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 判断非彩富人生用户是否已经有弹框过
     * @param string $mobile
     * @return boolean
     */
    public function isNoCaiFuUser($mobile){
        if(empty($mobile)){
            return false;
        }
        $isZhongArr=ZhiUserZhongzi::model()->find('mobile=:mobile and times=1',array(':mobile'=>$mobile));
        if(!empty($isZhongArr)){
            return true;
        }else{
            return false;
        }
    }
    /*1
     * @verson 彩富人生用户自动获取100成长值,每次用户进入页面都会进行验证
     * @param string $mobile
     * @return boolean
     */
    public function getValueByCaiFu($mobile){
        if(empty($mobile)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $result=$this->getAllGrowValue($mobile, 100, 1);
        $result2=ZhiUserZhongzi::model()->updateAll(array('times' =>new CDbExpression('times+1')),"mobile=".$mobile);
        if($result && $result2){
            $transaction->commit();
            return true;
        }else{
            $transaction->rollback();
            return false;
        }
    }
    /*2
     * @version 自己浇水获取成长值
     * @param string $mobile
     * return array
     */
    public function getGrowValueByMyJiao($mobile){
        if(empty($mobile)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr=ZhiUserGrow::model()->findAll('mobile=:mobile and type=:type and create_time>=:beginTime and create_time<= :endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,'endTime'=>$endTime,':type'=>2));
        $num=count($zhiArr);
        if($num<1){
            $sql="select create_time from zhi_user_grow where type=2 and mobile='".$mobile."' order by  create_time desc";
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
            $isJiao=$this->getAllGrowValue($mobile,$this->jiaoArr[$dayNum],2);
            if($isJiao){
                return $jiaoArr=array('value'=>$this->jiaoArr[$dayNum+1]);//浇水后返回的数组
            }else{
                return false;
            }
        }
    }
    /*2-1
     * @version 获取浇水明天的成长值
     * @param string $mobile
     * return array
     */
    public function getGrowValueTomorrow($mobile){
        if(empty($mobile)){
            return false;
        }
        $sql="select create_time from zhi_user_grow where type=2 and mobile='".$mobile."' order by  create_time desc";
        $createArr =Yii::app()->db->createCommand($sql)->queryAll();
        $day_list=array();
        if(!empty($createArr)){
            foreach ($createArr as $v){
                $day_list[]=date('Y-m-d',$v['create_time']);
            }
            $dayNum=$this->getNewDays($day_list);
        }else{
            $dayNum=1;
        }
        return $jiaoArr=array('value'=>$this->jiaoArr[$dayNum+1]);//浇水后返回的数组
    }
    /*3
     * @version 分享至朋友圈获得20点成长值（每日任务-仅限一次）
     * @param string $mobile
     * return boolean
     */
    public function shareToFriend($mobile){
        if(empty($mobile)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr=ZhiUserGrow::model()->find('mobile=:mobile and type=:type and create_time>=:beginTime and create_time<= :endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,'endTime'=>$endTime,':type'=>3));
        if(empty($zhiArr)){
            $ZhiModel=new ZhiUserGrow();
            $ZhiModel->mobile=$mobile;
            $ZhiModel->value=20;
            $ZhiModel->type=3;
            $ZhiModel->create_time=time();
            $result=$ZhiModel->save();
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
     * @version 好友浇水
     * @param string $mobile
     */
    public function getValueByOtherJiao($mobile){
        if(empty($mobile)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr=ZhiUserGrow::model()->findAll('mobile=:mobile and type=:type and create_time>=:beginTime and create_time<= :endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,'endTime'=>$endTime,':type'=>4));
        $num=count($zhiArr);
        if($num<15){
            $ZhiModel=new ZhiUserGrow();
            $ZhiModel->mobile=$mobile;
            $ZhiModel->value=1;
            $ZhiModel->type=4;
            $ZhiModel->create_time=time();
            $result=$ZhiModel->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 插入成长值并且更改种子表中的状态
     * @param string $mobile
     * @param int $value
     * @param int $type
     * return array/boolean
     */
    private function getAllGrowValue($mobile,$value,$type){
        if(empty($mobile) || empty($value) || empty($type)){
            return false;
        }
        $ZhiGrowModel=new ZhiUserGrow();
        $ZhiGrowModel->mobile=$mobile;
        $ZhiGrowModel->value=$value;
        $ZhiGrowModel->type=$type;
        $ZhiGrowModel->create_time= time();
        $isInsert=$ZhiGrowModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
        /*
         * @version 时时判断成长值
         * @param string $mobile
         * @param string $returnType
         * return array
         */
        public function getPrizeByGrowValue($mobile){
            if(empty($mobile)){
                return false;
            }
            $sql="select sum(value) as summary from zhi_user_grow where mobile='".$mobile."'";
            $growArr =Yii::app()->db->createCommand($sql)->queryAll();
            $valueTotal=$growArr[0]['summary'];
            if($valueTotal>=$this->growArr[0] && $valueTotal<$this->growArr[1]){
                $isExist=ZhiUserZhongzi::model()->find('mobile=:mobile and status=:status',array(':mobile'=>$mobile,':status'=>2));
                if(!empty($isExist)){
                    return false;
                }else{
                    $transaction = Yii::app()->db->beginTransaction();
                    $updateResult= ZhiUserZhongzi::model()->updateAll(array('status'=>2),'mobile=:mobile',array(':mobile'=>$mobile));
                    $zhiPrizeModel=new ZhiUserPrize();
                    $zhiPrizeModel->mobile=$mobile;
                    $zhiPrizeModel->prize_id=1;
                    $zhiPrizeModel->prize_name=$this->prizeArr[1]['prize_name'];
                    $zhiPrizeModel->create_time=time();
                    $insertResult=$zhiPrizeModel->save();
                    $insertResult2=$this->getYouHuiQuan($mobile,$this->youHuiQuanArr[0]);
                    if($updateResult && $insertResult && $insertResult2){
                        $transaction->commit();
                        return $jiangArr=array('prize_id'=>1,'prize_name'=>$this->prizeArr[1]['prize_name'],'zhang_name'=>'种子发芽了！继续好好养护它哟~快去“我的卡券”看看，有惊喜哦！');//返回获奖数组
                    }else{
                        $transaction->rollback();
                        return false;
                    }
                }
            }elseif($valueTotal>=$this->growArr[1] && $valueTotal<$this->growArr[2]){
                $isExist=ZhiUserZhongzi::model()->find('mobile=:mobile and status=:status',array(':mobile'=>$mobile,':status'=>3));
                if(!empty($isExist)){
                    return false;
                }else{
                    $transaction = Yii::app()->db->beginTransaction();
                    $updateResult= ZhiUserZhongzi::model()->updateAll(array('status'=>3),'mobile=:mobile',array(':mobile'=>$mobile));
                    $zhiPrizeModel=new ZhiUserPrize();
                    $zhiPrizeModel->mobile=$mobile;
                    $zhiPrizeModel->prize_id=2;
                    $zhiPrizeModel->prize_name=$this->prizeArr[2]['prize_name'];
                    $zhiPrizeModel->create_time=time();
                    $insertResult=$zhiPrizeModel->save();
                    $insertResult2=$this->getYouHuiQuan($mobile,$this->youHuiQuanArr[1]);
                    if($updateResult && $insertResult && $insertResult2){
                        $transaction->commit();
                        return $jiangArr=array('prize_id'=>1,'prize_name'=>$this->prizeArr[2]['prize_name'],'zhang_name'=>'哇~开花了呢！马上就要结果了！不知道这次会是什么礼包呢？');//返回获奖数组
                    }else{
                        $transaction->rollback();
                        return false;
                    }
                }
            }elseif($valueTotal>=$this->growArr[2] && $valueTotal<$this->growArr[3]){
                $isExist=ZhiUserZhongzi::model()->find('mobile=:mobile and status=:status',array(':mobile'=>$mobile,':status'=>4));
                if(!empty($isExist)){
                    return false;
                }else{
                    $transaction = Yii::app()->db->beginTransaction();
                    $updateResult= ZhiUserZhongzi::model()->updateAll(array('status'=>4),'mobile=:mobile',array(':mobile'=>$mobile));
                    $zhiPrizeModel=new ZhiUserPrize();
                    $zhiPrizeModel->mobile=$mobile;
                    $zhiPrizeModel->prize_id=3;
                    $zhiPrizeModel->prize_name=$this->prizeArr[3]['prize_name'];
                    $zhiPrizeModel->create_time=time();
                    $insertResult=$zhiPrizeModel->save();
                    $insertResult2=$this->getYouHuiQuan($mobile,$this->youHuiQuanArr[2]);
                    $insertResult3=$this->getYouHuiQuan($mobile,$this->youHuiQuanArr[3]);
                    if($updateResult && $insertResult && $insertResult2 && $insertResult3){
                        $transaction->commit();
                        return $jiangArr=array('prize_id'=>1,'prize_name'=>$this->prizeArr[3]['prize_name'],'zhang_name'=>'当当当~~果实很快就成熟了！哦？！又有礼包！！');//返回获奖数组
                        
                    }else{
                        $transaction->rollback();
                        return false;
                    }
                }
            }elseif($valueTotal>=$this->growArr[3]){
                $isExist=ZhiUserZhongzi::model()->find('mobile=:mobile and status=:status',array(':mobile'=>$mobile,':status'=>5));
                if(!empty($isExist)){
                    return false;
                }else{
                    $prizeResultArr=$this->checkNum();
                    foreach ($prizeResultArr as $key => $val) { 
                        $arr[$val['id']] = $val['v']; 
                    }
                    $rid =  ZhiShuJie::model()->get_rand($arr);
                    if($rid==1){
                        $insertResult2=$this->getYouHuiQuan($mobile,$this->youHuiQuanArr[4]);
                        $insertResult3=$this->getYouHuiQuan($mobile,$this->youHuiQuanArr[5]);
                    }
                    $transaction = Yii::app()->db->beginTransaction();
                    $updateResult= ZhiUserZhongzi::model()->updateAll(array('status'=>5),'mobile=:mobile',array(':mobile'=>$mobile));
                    $zhiPrizeModel=new ZhiUserPrize();
                    $zhiPrizeModel->mobile=$mobile;
                    $zhiPrizeModel->prize_id=4;
                    $zhiPrizeModel->prize_name=$this->prizeArr[4][$rid]['prize_name'];
                    $zhiPrizeModel->price=$this->prizeArr[4][$rid]['price'];
                    $zhiPrizeModel->create_time=time();
                    $insertResult=$zhiPrizeModel->save();
                    if($updateResult && $insertResult){
                        $transaction->commit();
                        return $jiangArr=array('prize_id'=>$rid,'prize_name'=>$this->prizeArr[4][$rid]['prize_name'],'zhang_name'=>'恭喜你获得商家礼券！ 快到我的卡券里看看都有什么吧！~','price'=>$this->prizeArr[4][$rid]['price']);//返回获奖数组
                    }else{
                        $transaction->rollback();
                        return false;
                    }
                }
            }else{
                return false;//没有任何效果的时候
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
     * @version 获取连续的天数
    * @param array $day_list
    * return int $continue_day 连续天数
    */
    private function getNewDays($day_list){
    	$continue_day = 1 ;//连续天数
    	if(count($day_list) >= 1){
    		for ($i=1; $i<count($day_list); $i++){
                if( ( abs(( strtotime($day_list[0]) - strtotime($day_list[$i]) ) / 86400)) == $i ){   
                   $continue_day = $i+1;  
                 }else{
                      break;       
                  }    
            }
    	}
    	return $continue_day;//输出连续几天
    }
    /*
     * @version 通过手机号码获取用户id
     * @param string $mobile
     * return int cust_id
     */
    private function getCustIdByMobile($mobile){
        if(empty($mobile)){
            return false;
        }
        $cusArr=Customer::model()->find('mobile=:mobile and state=0',array(':mobile'=>$mobile));
        return $cusArr->id;
    }
    /*
     * @version 记录分享
     * @param int $customer_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$type)
    {
        $shareLog =new ZhiShareLog();
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
     * @version 判断饭票的数量是否已经超过给定的数量
     * return array
     */
    public function checkNum(){
        $n=ZhiUserPrize::model()->count('prize_id=:prize_id and prize_name=:prize_name', array(':prize_id'=>4,':prize_name'=>$this->fanPiaoArr[0]));
        $n2=ZhiUserPrize::model()->count('prize_id=:prize_id and prize_name=:prize_name', array(':prize_id'=>4,':prize_name'=>$this->fanPiaoArr[1]));
        $n3=ZhiUserPrize::model()->count('prize_id=:prize_id and prize_name=:prize_name', array(':prize_id'=>4,':prize_name'=>$this->fanPiaoArr[1]));
        if($n>=300){
            unset($this->prizeArr[4][2]);
        }
        if($n2>=50){
            unset($this->prizeArr[4][3]);
        }
        if($n3>=30){
            unset($this->prizeArr[4][3]);
        }
        return $this->prizeArr[4];
    }
    /*
     * @version 获取成长值
     * @param string $mobile
     * return int 总的成长值
     */
    public function getValueByMobile($mobile){
        if(empty($mobile)){
            return false;
        }
        $sql="select sum(value) as summary from zhi_user_grow where mobile='".$mobile."'";
        $growArr =Yii::app()->db->createCommand($sql)->queryAll();
        $valueTotal=$growArr[0]['summary'];
        return $valueTotal;
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
     * @version 判断今天是否已经自己浇水过
     * @param string $mobile
     */
    public function isJiaoShui($mobile){
        if(empty($mobile)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr=ZhiUserGrow::model()->findAll('mobile=:mobile and type=:type and create_time>=:beginTime and create_time<= :endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,'endTime'=>$endTime,':type'=>2));
        $num=count($zhiArr);
        if($num>=1){
            return false;
        }else{
            return true;
        }
    }
    /**
     * 获取浇水次数
     * @param unknown $mobile  用户手机号
     * @param unknown $type  类型
     * @return boolean
     */
    public function getWaterNum($mobile,$type){
    	$num=0;
    	if(empty($mobile)){
    		return $num;
    	}
    	$beginTime = mktime(0,0,0);
    	$endTime = time();
    	$zhiArr=ZhiUserGrow::model()->findAll('mobile=:mobile and type=:type and create_time>=:beginTime and create_time<= :endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,'endTime'=>$endTime,':type'=>$type));
    	if (!empty($zhiArr)){
    		$num=count($zhiArr);
    	}
    	return $num;
    }
}

