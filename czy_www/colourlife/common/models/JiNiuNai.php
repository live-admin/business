<?php
/*
 * @version 挤牛奶model
 */
class JiNiuNai extends CActiveRecord{
    private $_startDay='2016-04-13';//活动开始时间
    private $_endDay='2016-04-27 23:59:59';//活动结束时间
    private $growArr=array(300,900,1600);//牛奶值临界点
    private $prizeArr=array(
        '1'=>array('prize'=>1,'code'=>100000084,'prize_name'=>'环球精选优惠券:满100减10券'),
        '2'=>array('prize'=>2,'code'=>100000085,'prize_name'=>'环球精选优惠券:满200减15券'),
        '3'=>array('prize'=>3,'code'=>100000086,'prize_name'=>'环球精选优惠券:满300减25券'),
        '4'=>array('prize'=>4,'code'=>100000087,'prize_name'=>'环球精选优惠券:满500减45券'),
    );
    //自己挤牛奶值配置
    private $jiArr=array(
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
        '12'=>120,
        '13'=>130,
        '14'=>140,
        '15'=>150,
        '16'=>160,
    );
    //奖项id的配置
    public $jiangXiang=array(
        '1'=>'第1-5名',
        '2'=>'第6-10名',
        '3'=>'第11-25名',
        '4'=>'第26-45名',
        '5'=>'第46-70名',
        '6'=>'第71-100名',
        '7'=>'挤奶量达到300ml',
        '8'=>'挤奶量达到900ml',
        '9'=>'挤奶量达到1600ml',
    );
    //奖品名称配置
    private $jiangPin=array(
        '1'=>'“环球精选”内任选奶粉2罐',
        '2'=>'伊利安慕希牛奶或伊利金典纯牛奶1箱',
        '3'=>'1份Sunrise日升芦荟绵羊油',
        '4'=>'贝瑟斯创意陶瓷马克杯1个',
        '5'=>'价值20元无门槛优惠券',
        '6'=>'价值10元无门槛优惠券',
    );
    //无门栏优惠券
    private $allYou=array(
        '1'=>100000081,
        '2'=>100000082,
        '3'=>100000083,
    );

    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 检查是否已经参加活动(有用户数据)
     * @param string $mobile
     * return boolean
     */
    public function isCanJia($mobile){
        if(empty($mobile)){
            return false;
        }
        $tongArr=NiuUserTong::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
        if(empty($tongArr)){
            return false;
        }else{
            return true;
        }
    }
    /*
     * @version 参加活动，保存数据
     * @param string $mobile
     * return boolean 
     */
    public function getTong($mobile){
        if(empty($mobile)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $TongModel=new NiuUserTong();
        $TongModel->mobile=$mobile;
        $TongModel->times=1;
        $TongModel->create_time=time();
        $result=$TongModel->save();
        $NiuModel=new NiuUserGrow();
        $NiuModel->mobile=$mobile;
        $NiuModel->value=0;
        $NiuModel->type=7;
        $NiuModel->create_time=time();
        $result2=$NiuModel->save();
        if($result && $result2){
            $transaction->commit();
            return true;
        }else{
            $transaction->rollback();
            return false;
        }
    }
    /*
     * @version 判断是否是彩富人生用户以及是否已经领取过牛奶值了，来决定是否弹框
     * @param string $mobile
     * return boolean
     */
    public function isCaiFuUser($mobile){
        if(empty($mobile)){
            return false;
        }
        $cust_id=$this->getCustIdByMobile($mobile);
        $isLingCaiArr=  NiuUserGrow::model()->find('mobile=:mobile and type=:type',array(':mobile'=>$mobile,':type'=>1));
        $propertyArr=PropertyActivity::model()->find('customer_id=:customer_id and (status=96 or status=99)',array(':customer_id'=>$cust_id));
        $appreciationArr=AppreciationPlan::model()->find('customer_id=:customer_id and (status=96 or status=99)',array(':customer_id'=>$cust_id));
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
        $isTongArr=  NiuUserTong::model()->find('mobile=:mobile and times=1',array(':mobile'=>$mobile));
        if(!empty($isTongArr)){
            return true;
        }else{
            return false;
        }
    }
    /*1
     * @verson 彩富人生用户自动获取100ml牛奶,每次用户进入页面都会进行验证
     * @param string $mobile
     * @return boolean
     */
    public function getValueByCaiFu($mobile){
        if(empty($mobile)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $result=$this->getAllGrowValue($mobile, 100, 1);
        $result2=NiuUserTong::model()->updateAll(array('times' =>new CDbExpression('times+1')),"mobile=".$mobile);
        if($result && $result2){
            $transaction->commit();
            return true;
        }else{
            $transaction->rollback();
            return false;
        }
    }
    /*2
     * @version 自己挤奶获取牛奶值
     * @param string $mobile
     * return array
     */
    public function getGrowValueByMyJi($mobile){
        if(empty($mobile)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr= NiuUserGrow::model()->findAll('mobile=:mobile and type=:type and create_time>=:beginTime and create_time<= :endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,'endTime'=>$endTime,':type'=>2));
        $num=count($zhiArr);
        if($num<1){
            $sql="select create_time from niu_user_grow where type=2 and mobile='".$mobile."' order by  create_time desc";
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
            $isJi=$this->getAllGrowValue($mobile,$this->jiArr[$dayNum],2);
            if($isJi){
                return $jiArr=array('value'=>$this->jiArr[$dayNum+1]);//挤牛奶后返回的数组
            }else{
                return false;
            }
        }
    }
    /*2-1
     * @version 获取明天挤牛奶的值
     * @param string $mobile
     * return array
     */
    public function getGrowValueTomorrow($mobile){
        if(empty($mobile)){
            return false;
        }
        $sql="select create_time from niu_user_grow where type=2 and mobile='".$mobile."' order by  create_time desc";
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
        return $jiArr=array('value'=>$this->jiArr[$dayNum+1]);//挤牛奶后返回的数组
    }
    /*3
     * @version 分享至朋友圈获得20ml牛奶值（每日任务-仅限一次）
     * @param string $mobile
     * return boolean
     */
    public function shareToFriend($mobile){
        if(empty($mobile)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr=  NiuUserGrow::model()->find('mobile=:mobile and type=:type and create_time>=:beginTime and create_time<= :endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,'endTime'=>$endTime,':type'=>3));
        if(empty($zhiArr)){
            $NiuModel=new NiuUserGrow();
            $NiuModel->mobile=$mobile;
            $NiuModel->value=20;
            $NiuModel->type=3;
            $NiuModel->create_time=time();
            $result=$NiuModel->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }
    /*4
     * @version 好友挤牛奶
     * @param string $mobile
     * retun boolean
     */
    public function getValueByOtherJi($mobile){
        if(empty($mobile)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr=NiuUserGrow::model()->findAll('mobile=:mobile and type=:type and create_time>=:beginTime and create_time<= :endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,'endTime'=>$endTime,':type'=>4));
        $num=count($zhiArr);
        if($num<15){
            $NiuModel=new NiuUserGrow();
            $NiuModel->mobile=$mobile;
            $NiuModel->value=1;
            $NiuModel->type=4;
            $NiuModel->create_time=time();
            $result=$NiuModel->save();
            if($result){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
    /*5 自动运行脚本里面*/
    /*6
     * @version 环球精选下单获取牛奶值
     * @param string $mobile
     * return array
     */
    public function getValueByOrder($mobile){
        if(empty($mobile)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $buyer_id=$this->getCustIdByMobile($mobile);
        $orderArr=$this->getOrderTan($mobile);
        if(!empty($orderArr)){
            $updateArr=array();
            foreach ($orderArr as $order){
                $updateArr[]=$order->id;
            }
            $updateStr=implode(",", $updateArr);
            $updateSql="update `order` set is_send=1 where seller_id=4995 and buyer_id=".$buyer_id." and id in (".$updateStr.")";
            $res = Yii::app()->db->createCommand($updateSql)->execute();
            $value=$res*50;
            $res2=$this->getAllGrowValue($mobile,$value,6);
            if($res && $res2){
                $transaction->commit();
                return $res;
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 判断是否有环球精选的订单
     * @param string $mobile  用户手机号
     * return boolean
     */
    public function getOrderTan($mobile){
        if(empty($mobile)){
            return false;
        }
        $buyer_id=$this->getCustIdByMobile($mobile);
        $beginTime=strtotime($this->_startDay);
        $endTime=strtotime($this->_endDay);
        $orderArr=Order::model()->findAll('seller_id=:seller_id and buyer_id=:buyer_id and (status=1 or status=99) and create_time>=:beginTime and create_time<= :endTime and is_send=0 and update_time>=:beginTime and update_time<= :endTime', array(':seller_id'=>Item::HUANQIU_JINGXUAN,':buyer_id'=>$buyer_id,':beginTime'=>$beginTime,'endTime'=>$endTime));
        if(!empty($orderArr)){
            return $orderArr;
        }else{
            return false;
        }
    }
    /**
     * 获取挤牛奶次数
     * @param string $mobile  用户手机号
     * @param int $type  类型
     * @return boolean
     */
    public function getWaterNum($mobile,$type){
    	$num=0;
    	if(empty($mobile)){
    		return $num;
    	}
    	$beginTime = mktime(0,0,0);
    	$endTime = time();
    	$zhiArr=  NiuUserGrow::model()->findAll('mobile=:mobile and type=:type and create_time>=:beginTime and create_time<= :endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,'endTime'=>$endTime,':type'=>$type));
    	if (!empty($zhiArr)){
    		$num=count($zhiArr);
    	}
    	return $num;
    }
    /*
     * @version 判断今天是否已经自己挤牛奶过
     * @param string $mobile
     * return boolean
     */
    public function isJiNiuNai($mobile){
        if(empty($mobile)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr= NiuUserGrow::model()->findAll('mobile=:mobile and type=:type and create_time>=:beginTime and create_time<= :endTime',array(':mobile'=>$mobile,':beginTime'=>$beginTime,'endTime'=>$endTime,':type'=>2));
        $num=count($zhiArr);
        if($num>=1){
            return false;
        }else{
            return true;
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
     * @version 插入牛奶值
     * @param string $mobile
     * @param int $value
     * @param int $type
     * return array/boolean
     */
    private function getAllGrowValue($mobile,$value,$type){
        if(empty($mobile) || empty($value) || empty($type)){
            return false;
        }
        $NiuGrowModel=new NiuUserGrow();
        $NiuGrowModel->mobile=$mobile;
        $NiuGrowModel->value=$value;
        $NiuGrowModel->type=$type;
        $NiuGrowModel->create_time= time();
        $isInsert=$NiuGrowModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 获取牛奶值
     * @param string $mobile
     * return int 总的牛奶值
     */
    public function getValueByMobile($mobile){
        if(empty($mobile)){
            return false;
        }
        $sql="select sum(value) as summary from niu_user_grow where mobile='".$mobile."'";
        $growArr =Yii::app()->db->createCommand($sql)->queryAll();
        $valueTotal=$growArr[0]['summary'];
        if(empty($valueTotal)){
            return 0;
        }else{
            return $valueTotal;
        }
    }
    
    /*
     * @version 时时判断牛奶值
     * @param string $mobile
     * @param string $returnType
     * return array
     */
    public function getPrizeByGrowValue($mobile){
        if(empty($mobile)){
            return false;
        }
        $sql="select sum(value) as summary from niu_user_grow where mobile='".$mobile."'";
        $growArr =Yii::app()->db->createCommand($sql)->queryAll();
        $valueTotal=$growArr[0]['summary'];
        
        if($valueTotal>=$this->growArr[0] && $valueTotal<$this->growArr[1]){
//            $isExist=ZhiUserZhongzi::model()->find('mobile=:mobile and status=:status',array(':mobile'=>$mobile,':status'=>2));
            $isExist=NiuUserPrize::model()->find('mobile=:mobile and prize_id=:prize_id',array(':mobile'=>$mobile,':prize_id'=>7));
            if(!empty($isExist)){
                return false;
            }else{
                $transaction = Yii::app()->db->beginTransaction();
                $rand=array_rand($this->prizeArr);
                $niuPrizeModel=new NiuUserPrize();
                $niuPrizeModel->mobile=$mobile;
                $niuPrizeModel->prize_id=7;
                $niuPrizeModel->prize_name=$this->prizeArr[$rand]['prize_name'];
                $niuPrizeModel->create_time=time();
                $insertResult=$niuPrizeModel->save();
                $insertResult2=$this->getYouHuiQuan($mobile,$this->prizeArr[$rand]['code']);
                if($insertResult && $insertResult2){
                    $transaction->commit();
                    return $jiangArr=array('prize_name'=>$this->prizeArr[$rand]['prize_name']);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }
        }elseif($valueTotal>=$this->growArr[1] && $valueTotal<$this->growArr[2]){
            $isExist=NiuUserPrize::model()->find('mobile=:mobile and prize_id=:prize_id',array(':mobile'=>$mobile,':prize_id'=>8));
            if(!empty($isExist)){
                return false;
            }else{
                $transaction = Yii::app()->db->beginTransaction();
                $prizeArr=$this->checkYouHuiQuan($mobile);
                $rand=array_rand($prizeArr);
                $niuPrizeModel=new NiuUserPrize();
                $niuPrizeModel->mobile=$mobile;
                $niuPrizeModel->prize_id=8;
                $niuPrizeModel->prize_name=$this->prizeArr[$rand]['prize_name'];
                $niuPrizeModel->create_time=time();
                $insertResult=$niuPrizeModel->save();
                $insertResult2=$this->getYouHuiQuan($mobile,$this->prizeArr[$rand]['code']);
                if($insertResult && $insertResult2){
                    $transaction->commit();                  
                    return $jiangArr=array('prize_name'=>$this->prizeArr[$rand]['prize_name']);//返回获奖数组
                }else{
                    $transaction->rollback();
                    return false;
                }
            }
        }elseif($valueTotal>=$this->growArr[2]){
            $isExist=NiuUserPrize::model()->find('mobile=:mobile and prize_id=:prize_id',array(':mobile'=>$mobile,':prize_id'=>9));
            if(!empty($isExist)){
                return false;
            }else{
                $transaction = Yii::app()->db->beginTransaction();
                $prizeArr=$this->checkYouHuiQuan($mobile);
                $rand=array_rand($prizeArr);
                $niuPrizeModel=new NiuUserPrize();
                $niuPrizeModel->mobile=$mobile;
                $niuPrizeModel->prize_id=9;
                $niuPrizeModel->prize_name=$this->prizeArr[$rand]['prize_name'];
                $niuPrizeModel->create_time=time();
                $insertResult=$niuPrizeModel->save();
                $insertResult2=$this->getYouHuiQuan($mobile,$this->prizeArr[$rand]['code']);
                if($insertResult && $insertResult2){
                    $transaction->commit();
                    return $jiangArr=array('prize_name'=>$this->prizeArr[$rand]['prize_name']);//返回获奖数组
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
     * @version 记录分享
     * @param int $customer_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$type)
    {
        $shareLog =new NiuShareLog();
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
     * @version 获取排名 
     * @param string $mobile
     * return array
     */
    public function getMingByMobile($mobile){
        if(empty($mobile)){
            return false;
        }
//        $isExist= NiuUserGrow::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
//        if(empty($isExist)){
//            return '';
//        }
        $list=array();
        $sql="select mobile,sum(value) as summary,max(create_time) as maxtime from niu_user_grow GROUP BY mobile ORDER BY summary desc,maxtime";
        $growArr =Yii::app()->db->createCommand($sql)->queryAll();
        
        if(!empty($growArr)){
            foreach ($growArr as $key=>$grow){
                if($grow['mobile']==$mobile){
                     $list['paiming']=$key+1;
                     $list['summary']=$grow['summary'];
                     $list['mobile']=$mobile;
                }
            }
        }
        $list['grow']= $growArr;
        return $list;
    }
    /*
     * @version 获取用户奖项
     * @param string $mobile
     * return array
     */
    public function getJiangByMobile($mobile){
        if(empty($mobile)){
            return false;
        }
        $prizeArr = NiuUserPrize::model()->findAll(array(
            'select'=>array('prize_id','prize_name','create_time'),
            'order' => 'id DESC',
            'condition' => 'mobile=:mobile',
            'params' => array(':mobile'=>$mobile),
        ));
        return $prizeArr;
    }
    /*
     * @version 时间结束后首页弹框提示
     * return boolean
     */
    public function isTan(){
        if (time()>strtotime($this->_endDay)){
			return true;
		}else{
            return false;
        }
    }
    /*
     * @version 根据排名获取奖励并弹框
     * @param string $mobile
     * @param int $paiming
     * return array
     */
    public function getPrizeByPaiMing($mobile,$paiming){
        if(empty($mobile) || empty($paiming)){
            return false;
        }
        $jp=array();
        $customer_id=$this->getCustIdByMobile($mobile);
        if($paiming>=1 && $paiming<=5){
            $isExist=NiuUserPrize::model()->find('mobile=:mobile and prize_id=:prize_id',array(':mobile'=>$mobile,':prize_id'=>1));
            if(!empty($isExist)){
                return false;
            }
            $result=$this->insertPrize($mobile,1);
            if($result){
                return $jp=array('dengji'=>1);
            }else{
                return false;
            }
        }elseif($paiming>=6 && $paiming<=10){
            $isExist=NiuUserPrize::model()->find('mobile=:mobile and prize_id=:prize_id',array(':mobile'=>$mobile,':prize_id'=>2));
            if(!empty($isExist)){
                return false;
            }
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($mobile,2);
            $goods_id = '13087,14556,2260';
            $onecode = OneYuanBuy::sendCode($customer_id, $goods_id, 0, $type = 'JiNiuNai');
            if($result && $onecode){
                $transaction->commit();
                return $jp=array('dengji'=>2);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($paiming>=11 && $paiming<=25){
            $isExist=NiuUserPrize::model()->find('mobile=:mobile and prize_id=:prize_id',array(':mobile'=>$mobile,':prize_id'=>3));
            if(!empty($isExist)){
                return false;
            }
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($mobile,3);
            $res=$this->getYouHuiQuan($mobile, $this->allYou[1]);
            if($result && $res){
                $transaction->commit();
                return $jp=array('dengji'=>3);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($paiming>=26 && $paiming<=45){
            $isExist=NiuUserPrize::model()->find('mobile=:mobile and prize_id=:prize_id',array(':mobile'=>$mobile,':prize_id'=>4));
            if(!empty($isExist)){
                return false;
            }
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($mobile,4);
            $goods_id='25790,25858,25859';
            $onecode = OneYuanBuy::sendCode($customer_id, $goods_id, 0, $type = 'JiNiuNai');
            if($result && $onecode){
                $transaction->commit();
                return $jp=array('dengji'=>4);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($paiming>=46 && $paiming<=70){
            $isExist=NiuUserPrize::model()->find('mobile=:mobile and prize_id=:prize_id',array(':mobile'=>$mobile,':prize_id'=>5));
            if(!empty($isExist)){
                return false;
            }
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($mobile,5);
            $res=$this->getYouHuiQuan($mobile, $this->allYou[2]);
            if($result && $res){
                $transaction->commit();
                return $jp=array('dengji'=>5);
            }else{
                $transaction->rollback();
                return false;
            }
        }elseif($paiming>=71 && $paiming<=100){
            $isExist=NiuUserPrize::model()->find('mobile=:mobile and prize_id=:prize_id',array(':mobile'=>$mobile,':prize_id'=>6));
            if(!empty($isExist)){
                return false;
            }
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertPrize($mobile,6);
            $res=$this->getYouHuiQuan($mobile, $this->allYou[3]);
            if($result && $res){
                $transaction->commit();
                return $jp=array('dengji'=>6);
            }else{
                $transaction->rollback();
                return false;
            }
        }
    }
    /*
     * @version 通过排名获取奖品
     * @param string $mobile
     * $param int $prize_id
     * return boolean
     */
    public function insertPrize($mobile,$prize_id){
        $niuPrizeModel=new NiuUserPrize();
        $niuPrizeModel->mobile=$mobile;
        $niuPrizeModel->prize_id=$prize_id;
        $niuPrizeModel->prize_name=$this->jiangPin[$prize_id];
        $niuPrizeModel->create_time=time();
        $result=$niuPrizeModel->save();
        if($result){
            return true;
        }else{
            return false;
        }
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
     * @version 通过手机号码判断用户已经拥有优惠券，下次不再中同一个
     * @param string $mobile
     * @param int $you_hui_quan_id
     * return array
     */
    public function checkYouHuiQuan($mobile){
        if(empty($mobile)){
            return false;
        }
        $youArr=UserCoupons::model()->findAll('mobile=:mobile',array(':mobile'=>$mobile));
        if(!empty($youArr)){
            foreach ($youArr as $you){
                if($you->you_hui_quan_id==100000084){
                    unset($this->prizeArr[1]);
                }
                if($you->you_hui_quan_id==100000085){
                    unset($this->prizeArr[2]);
                }
                if($you->you_hui_quan_id==100000086){
                    unset($this->prizeArr[3]);
                }
                if($you->you_hui_quan_id==100000087){
                    unset($this->prizeArr[4]);
                }
            }
        }
        return $this->prizeArr;
        
        
    }
    
    
}

