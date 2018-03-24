<?php
/*
 * @version 宝箱活动接口api
 */
class BaoXiang extends CActiveRecord{
    private $_startDay='2016-02-27';
    private $_endDay='2016-03-18';
    //定义一个每天能领取的紫宝石数量
    private $day_num=1;
    //定义一个每天摇一摇能获得的紫宝石数量
    private $yao_num=1;
    
    //领取的时候获取奖项配置
    private $prize_key_arr=array(
        '0' => array('id'=>1,'lei'=>0,'prize_name'=>'红宝石','v'=>15), 
        '1' => array('id'=>2,'lei'=>0,'prize_name'=>'蓝宝石','v'=>15), 
        '2' => array('id'=>3,'lei'=>0,'prize_name'=>'绿宝石','v'=>15), 
        '3' => array('id'=>4,'lei'=>0,'prize_name'=>'黄宝石','v'=>15), 
        '4' => array('id'=>5,'lei'=>0,'prize_name'=>'紫宝石','v'=>10),
        '5' => array('id'=>6,'lei'=>1,'v'=>30),//各种券
    );

    //券数组
    private $prize_quan_arr=array(
        '0' => array('id'=>11,'lei'=>1,'code'=>100000057,'prize_name'=>'彩生活特供满300减80券','v'=>0), 
        '1' => array('id'=>12,'lei'=>1,'code'=>100000058,'prize_name'=>'彩生活特供满500减100券','v'=>0),
        '2' => array('id'=>13,'lei'=>1,'code'=>100000059,'prize_name'=>'环球精选满100减10券','v'=>0),
        '3' => array('id'=>14,'lei'=>1,'code'=>100000060,'prize_name'=>'环球精选满200减20券','v'=>0),
        '4' => array('id'=>15,'lei'=>2,'code'=>'eweixiu01','prize_name'=>'“猴”彩包#马桶代金券30元#水管/水龙头代金券20元','v'=>10),
        '5' => array('id'=>16,'lei'=>2,'code'=>'eweixiu02','prize_name'=>'“赛”彩包#热水器代金券20元#燃气灶代金券30元','v'=>10),
        '6' => array('id'=>17,'lei'=>2,'code'=>'eweixiu03','prize_name'=>'“雷”彩包#灯具维代金20元#马桶代金券30元','v'=>10),
        '7' => array('id'=>18,'lei'=>1,'code'=>'ezufang01','prize_name'=>'租房优惠券100元','v'=>10),
        '8' => array('id'=>19,'lei'=>1,'code'=>'ezufang02','prize_name'=>'智能门锁200元抵用券','v'=>10),
        '9' => array('id'=>20,'lei'=>1,'code'=>'youlun','prize_name'=>'邮轮礼券200元（HKD）','v'=>50),
    );
    //提示数组
    private $prize_ti_arr=array(
       '哎呀，怎么什么都没有，换个姿势试一下',
       '什么都没摇到，换个姿势继续来~',
       '没摇到也不要灰心呀，邀请朋友一起来',
       '哟喂，摇不到宝石，不如去邻里发个帖吧！',
       '什么都没摇到，现在就去E缴费试试看',
       '摇不到没关系，星晨旅游问答，答对有奖'
    );
   
    //摇一摇得到奖项的配置和概率数组
    private $prize_yao_arr=array(
        '0' => array('id'=>1,'code'=>100000057,'prize_name'=>'彩生活特供满300减80券','v'=>10),
        '1' => array('id'=>2,'code'=>100000058,'prize_name'=>'彩生活特供满500减100券','v'=>10),
        '2' => array('id'=>3,'code'=>100000059,'prize_name'=>'环球精选满100减10券','v'=>10),
        '3' => array('id'=>4,'code'=>100000060,'prize_name'=>'环球精选满200减20券','v'=>10),
        '4' => array('id'=>5,'code'=>'cibaoshi','prize_name'=>'紫宝石','v'=>10),
        '5' => array('id'=>6,'code'=>'no','prize_name'=>'哎呀，怎么什么都没有，换个姿势试一下','v'=>50),
    );
    
    //开启宝箱的奖项的配置和概率数组
    private $prize_bao_arr=array(
        '0' => array('id'=>1,'code'=>'zuhe01','goods_id'=>23723,'prize_name'=>'澳门旅游酒店礼券#E维修终极组合大礼包','v'=>17), //一元购码
        '1' => array('id'=>2,'code'=>'zuhe02','prize_name'=>'彩之云平台礼券包#（环球精选、彩特供、E租房）# E维修终极组合大礼包','v'=>83),
    );
	
    
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 进入活动能够页面，如果用户没在首页弹框领取宝箱就自动获取一个宝箱
     * @param string $mobile
     * return boolean
     */
    public function getBaoXiang($mobile){
        if(empty($mobile)){
            return false;
        }
        $baoArr=BaoUserBaoxiang::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
        if(empty($baoArr)){
            $baoModel=new BaoUserBaoxiang();
            $baoModel->mobile=$mobile;
            $baoModel->is_up=0;
            $baoModel->is_open=0;
            $baoModel->code='';
            $baoModel->prize_name='';
            $baoModel->create_time=time();
            $result=$baoModel->save();
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
     * @version 邀请注册1
     * @param int $customer_id
     * @return boolean
     */
    public function getBaoShiNum($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $cusArr=Customer::model()->findByPk($customer_id);
        /*获取领取宝石的次数*/
        //1 邀请注册
        $invArr=Invite::model()->findAll('customer_id=:customer_id and status=1 and effective=1 and create_time>=unix_timestamp(curdate()) and create_time<unix_timestamp(date_sub(curdate(),interval -1 day)) and create_time>=:startDay and create_time<=:endDay',array(':customer_id'=>$customer_id,':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
        if(count($invArr)>=3){
            $lingArr=BaoUserLingqunum::model()->find('mobile=:mobile and type=1',array(':mobile'=>$cusArr->mobile));
            if(empty($lingArr)){
                $lingModel=new BaoUserLingqunum();
                $lingModel->mobile=$cusArr->mobile;
                $lingModel->type=1;
                $lingModel->state=0;
                $lingModel->create_time=time();
                $result=$lingModel->save();
                if(!empty($result)){
                    return true;
                }else{
                    return false;
                }
            }
        }
        return true;
    }
    /*
     * @version 星晨旅游问答2
     * @param string $mobile
     * @return boolean
     */
    public function xingChen($mobile){
        if(empty($mobile)){
            return false;
        }
        $result=XingQustion::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
        if(!empty($result)){
            $lingArr=BaoUserLingqunum::model()->find('mobile=:mobile and type=2',array(':mobile'=>$mobile));
            if(empty($lingArr)){
                $lingModel=new BaoUserLingqunum();
                $lingModel->mobile=$mobile;
                $lingModel->type=2;
                $lingModel->state=0;
                $lingModel->create_time=time();
                $result=$lingModel->save();
                if(!empty($result)){
                    return true;
                }else{
                    return false;
                }
            }
        }
        return true;
    }
    
    
    /*
     * @version 点击领取后的逻辑
     * @param string $mobile
     */
    public function lingQuLater($mobile){
        if(empty($mobile)){
            return false;
        }
        $lingArr=BaoUserLingqunum::model()->find('(type=1 or type=2) and state=0 and mobile=:mobile',array(':mobile'=>$mobile));
        if(!empty($lingArr)){
            $rid=mt_rand(1,4);
            $transaction = Yii::app()->db->beginTransaction();
            $yaoShiModel=new BaoUserYaoshi();
            $yaoShiModel->mobile=$mobile;
            $yaoShiModel->yaoshi_id=$rid;
            $yaoShiModel->is_use=0;
            $yaoShiModel->create_time=time();
            //保存数据
            $result=$yaoShiModel->save();
            //更改领取机会
            $result2=BaoUserLingqunum::model()->updateAll(array('state'=>1), 'mobile=:mobile and type=:type and create_time=:create_time', array(':mobile'=>$mobile,':type'=>$lingArr->type,':create_time'=>$lingArr->create_time));
            if($result && $result2){
                $transaction->commit();
                return $this->prize_key_arr[$rid-1];
            }else{
                $transaction->rollback();
                return false;
            }
        }
        $lingOtherArr=BaoUserLingqunum::model()->find('type in (3,4,5,6,7,8) and state=0 and mobile=:mobile',array(':mobile'=>$mobile));
        if(!empty($lingOtherArr)){
            $check=$this->checkDateLater($this->day_num,0);
            if(!$check){
                unset($this->prize_key_arr[4]);
            }
            foreach ($this->prize_key_arr as $key => $val) { 
                $arr[$val['id']] = $val['v']; 
            }
            
            $rid = $this->get_rand($arr); //根据概率获取奖项id
            if($rid<=5){
                $transaction = Yii::app()->db->beginTransaction();
                $yaoShiModel=new BaoUserYaoshi();
                $yaoShiModel->mobile=$mobile;
                $yaoShiModel->yaoshi_id=$rid;
                $yaoShiModel->is_use=0;
                $yaoShiModel->create_time=time();
                //保存数据
                $result=$yaoShiModel->save();
                //更改领取机会
                $result2=BaoUserLingqunum::model()->updateAll(array('state'=>1), 'mobile=:mobile and type=:type and create_time=:create_time', array(':mobile'=>$mobile,':type'=>$lingOtherArr->type,':create_time'=>$lingOtherArr->create_time));
                if($result && $result2){
                    $transaction->commit();
                    return $this->prize_key_arr[$rid-1];
                }else{
                    $transaction->rollback();
                    return false;
                }

            }else{
                //券
                $prize_quan_other_arr=$this->checkMoreNum();
                foreach ($prize_quan_other_arr as $key => $val) {
                    $arr2[$val['id']] = $val['v']; 
                }
                $rid = $this->get_rand($arr2); //根据概率获取奖项id
                //优惠券
                if($rid<=14){
                    $userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$this->prize_quan_arr[$rid-11]['code'],':mobile'=>$mobile));
                    if(!empty($userCouponsArr)){
                        $result=BaoUserLingqunum::model()->updateAll(array('state'=>1), 'mobile=:mobile and type=:type and create_time=:create_time', array(':mobile'=>$mobile,':type'=>$lingOtherArr->type,':create_time'=>$lingOtherArr->create_time));
                        if($result){
                            return $this->prize_quan_arr[$rid-11];
                        }else{
                            return false;
                        }
                    }else{
                        $transaction = Yii::app()->db->beginTransaction();
                        $uc_model=new UserCoupons();
                        $uc_model->mobile=$mobile;
                        $uc_model->you_hui_quan_id=$this->prize_quan_arr[$rid-11]['code'];
                        $uc_model->create_time=time();
                        $result=$uc_model->save();
                        $result2=BaoUserLingqunum::model()->updateAll(array('state'=>1), 'mobile=:mobile and type=:type and create_time=:create_time', array(':mobile'=>$mobile,':type'=>$lingOtherArr->type,':create_time'=>$lingOtherArr->create_time));
                        if($result && $result2){
                            $transaction->commit();
                            return $this->prize_quan_arr[$rid-11];
                        }else{
                            $transaction->rollback();
                            return false;
                        }
                    }
                }elseif($rid>14 && $rid<=17){
                    //E维修
                    $transaction = Yii::app()->db->beginTransaction();
                    $result=$this->insertPrize($mobile,$rid);
                    $result2=BaoUserLingqunum::model()->updateAll(array('state'=>1), 'mobile=:mobile and type=:type and create_time=:create_time', array(':mobile'=>$mobile,':type'=>$lingOtherArr->type,':create_time'=>$lingOtherArr->create_time));
                    if($result && $result2){
                        $transaction->commit();
                        return $this->prize_quan_arr[$rid-11];
                    }else{
                        $transaction->rollback();
                        return false;
                    }
                }elseif($rid>17 && $rid<=19){
                    //E租房
                    $transaction = Yii::app()->db->beginTransaction();
                    $result=$this->insertPrize($mobile,$rid);
                    $result2=BaoUserLingqunum::model()->updateAll(array('state'=>1), 'mobile=:mobile and type=:type and create_time=:create_time', array(':mobile'=>$mobile,':type'=>$lingOtherArr->type,':create_time'=>$lingOtherArr->create_time));
                    if($result && $result2){
                        $transaction->commit();
                        return $this->prize_quan_arr[$rid-11];
                    }else{
                        $transaction->rollback();
                        return false;
                    }
                }else{
                    //游轮
                    $transaction = Yii::app()->db->beginTransaction();
                    $result=$this->insertPrize($mobile,$rid);
                    $result2=BaoUserLingqunum::model()->updateAll(array('state'=>1), 'mobile=:mobile and type=:type and create_time=:create_time', array(':mobile'=>$mobile,':type'=>$lingOtherArr->type,':create_time'=>$lingOtherArr->create_time));
                    if($result && $result2){
                        $transaction->commit();
                        return $this->prize_quan_arr[$rid-11];
                    }else{
                        $transaction->rollback();
                        return false;
                    }
                }
                
                
            }
        }
    }
    /*
     * @version 判断是否可以开启宝箱
     * @param string $mobile
     * return boolean
     */
    public function isCanOpen($mobile){
        if(empty($mobile)){
            return false;
        }
        $sql="select DISTINCT yaoshi_id from bao_user_yaoshi where mobile='".$mobile."'";
        $totalArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(count($totalArr)==5){
            $isUpArr=BaoUserBaoxiang::model()->find('mobile=:mobile and is_up=:is_up and is_open=:is_open',array(':mobile'=>$mobile,':is_up'=>1,':is_open'=>0));
            if(empty($isUpArr)){
                $result=BaoUserBaoxiang::model()->updateAll(array('is_up'=>1), 'mobile=:mobile', array(':mobile'=>$mobile));
                if($result){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    /*
     * @version 判断宝箱是否已经开启
     * @param string $mobile
     * return boolean
     */
    public function isOpen($mobile){
        if(empty($mobile)){
            return false;
        }
        $baoArr=BaoUserBaoxiang::model()->find('mobile=:mobile and is_up=:is_up and is_open=:is_open',array(':mobile'=>$mobile,':is_up'=>1,':is_open'=>1));
        if(empty($baoArr)){
            return true;
        }else{
            return false;
        }
    }

    /*
     * @versino 开启宝箱的逻辑
     * @param string $mobile
     */
    public function openBoxLater($mobile){
        if(empty($mobile)){
            return false;
        }
        $cusArr=Customer::model()->find('mobile=:mobile and state=0',array(':mobile'=>$mobile));
        $transaction = Yii::app()->db->beginTransaction();
        $prize_bao_other_arr=$this->checkLiBao();
        foreach ($prize_bao_other_arr as $key => $val) { 
                $arr[$val['id']] = $val['v']; 
        }
        $rid = $this->get_rand($arr); //根据概率获取奖项id
        $code=$this->prize_bao_arr[$rid-1]['code'];
        $prize_name=$this->prize_bao_arr[$rid-1]['prize_name'];
        if($code=='zuhe01'){
            $goods_id=$this->prize_bao_arr[$rid-1]['goods_id'];
            $oneYuanArr=OneYuanBuy::model()->find('is_send=:is_send and is_use=:is_use and state=:state',array(':is_send'=>0,':is_use'=>0,':state'=>0));
            if(!empty($oneYuanArr)){
                $result=OneYuanBuy::model()->updateAll(array('goods_id'=>$goods_id,'is_send'=>1,'customer_id'=>$cusArr->id), 'id=:id', array(':id'=>$oneYuanArr->id));
            }
            $result2=$this->insertEWeiXiu($mobile,$code,$prize_name);
            $result3=BaoUserBaoxiang::model()->updateAll(array('code'=>$code,'is_open'=>1,'prize_name'=>$prize_name), 'mobile=:mobile and is_up=:is_up', array(':mobile'=>$mobile,':is_up'=>1));
            $result4=BaoUserYaoshi::model()->updateAll(array('is_use'=>1), 'mobile=:mobile', array(':mobile'=>$mobile));
            if($result && $result2 && $result3 && $result4){
                $transaction->commit();
                return $this->prize_bao_arr[$rid-1];
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            $result=$this->insertEZuFang($mobile);
            $result2=$this->insertYouHuiQuan($mobile);
            $result3=$this->insertEWeiXiu($mobile,$code,$prize_name);
            $result4=BaoUserBaoxiang::model()->updateAll(array('code'=>$code,'is_open'=>1,'prize_name'=>$prize_name,'update_time'=>time()), 'mobile=:mobile and is_up=:is_up', array(':mobile'=>$mobile,':is_up'=>1));
            $result5=BaoUserYaoshi::model()->updateAll(array('is_use'=>1), 'mobile=:mobile', array(':mobile'=>$mobile));
            if($result && $result2 && $result3 && $result4 && $result5){
                $transaction->commit();
                return $this->prize_bao_arr[$rid-1];
            }else{
                $transaction->rollback();
                return false;
            }
        }
    }
    /*
     * @version 摇一摇后的逻辑
     * @param string $mobile
     */
    public function yaoLater($mobile){
        if(empty($mobile)){
            return false;
        }
        $check=$this->checkDateLater($this->yao_num,1);
        if(!$check){
            unset($this->prize_yao_arr[4]);
        }
        //判断时间段
        foreach ($this->prize_yao_arr as $key => $val) { 
            $arr[$val['id']] = $val['v']; 
        }
        $rid = $this->get_rand($arr); //根据概率获取奖项id
        if($rid<=4){
            $userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$this->prize_yao_arr[$rid-1]['code'],':mobile'=>$mobile));
            if(!empty($userCouponsArr)){
                return $this->prize_yao_arr[$rid-1];
            }else{
                $uc_model=new UserCoupons();
                $uc_model->mobile=$mobile;
                $uc_model->you_hui_quan_id=$this->prize_yao_arr[$rid-1]['code'];
                $uc_model->create_time=time();
                $result=$uc_model->save();
                if($result){
                    return $this->prize_yao_arr[$rid-1];
                }else{
                    return false;
                }
            }
        }elseif($rid==5){
            $yaoShiModel=new BaoUserYaoshi();
            $yaoShiModel->type=1;
            $yaoShiModel->mobile=$mobile;
            $yaoShiModel->yaoshi_id=5;
            $yaoShiModel->is_use=0;
            $yaoShiModel->create_time=time();
            //保存数据
            $result=$yaoShiModel->save();
            if($result){
                return $this->prize_yao_arr[4];
            }else{
                return false;
            }
        }else{
            $key=array_rand($this->prize_ti_arr);
            return array('id'=>6,'prize_name'=>$this->prize_ti_arr[$key]);
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
    /*
     * @version 判断是否今天已经放送一把或者超过了总数量15把
     * @param int $num
     * @param int $type 获取类型
     * return booleean
     */
    private function checkDateLater($num,$type){
        if(empty($num)){
            return false;
        }
        $yaoshiArr=BaoUserYaoshi::model()->findAll('yaoshi_id=:yaoshi_id and type=:type and create_time>=unix_timestamp(curdate()) and create_time<unix_timestamp(date_sub(curdate(),interval -1 day))',array(':yaoshi_id'=>5,':type'=>$type));
        $totalArr=BaoUserYaoshi::model()->findAll('yaoshi_id=:yaoshi_id and create_time>=:startDay and create_time<=:endDay',array(':yaoshi_id'=>5,':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
        if(count($yaoshiArr)>=$num || count($totalArr)>=30){
            return false;
        }else{
            return true;
        }
    }
    /*
     * @version 判断E维修、E租房、游轮是否超过数量
     * return array
     */
    private function checkMoreNum(){
        $weixiuOneArr=  BaoOtherPrize::model()->findAll('code=:code and create_time>=:startDay and create_time<=:endDay',array(':code'=>'eweixiu01',':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
        $weixiuTwoArr=BaoOtherPrize::model()->findAll('code=:code and create_time>=:startDay and create_time<=:endDay',array(':code'=>'eweixiu02',':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
        $weixiuTThreeArr=BaoOtherPrize::model()->findAll('code=:code and create_time>=:startDay and create_time<=:endDay',array(':code'=>'eweixiu03',':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
        $youlunArr=BaoOtherPrize::model()->findAll('code=:code and create_time>=:startDay and create_time<=:endDay',array(':code'=>'youlun',':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
        if(count($weixiuOneArr)>500){
            unset($this->prize_quan_arr[4]);
        }
        if(count($weixiuTwoArr)>500){
            unset($this->prize_quan_arr[5]);
        }
        if(count($weixiuTThreeArr)>500){
            unset($this->prize_quan_arr[6]);
        }
        if(count($youlunArr)>2000){
            unset($this->prize_quan_arr[9]);
        }
//        shuffle($this->prize_quan_arr);
        return $this->prize_quan_arr;
    }
    /*
     * @version 插入其他奖品数据
     * @param int $rid 随机数 
     * @param string $mobile
     */
    private function insertPrize($mobile,$rid){
        if(empty($rid) || empty($mobile)){
            return false;
        }
        $baoOtherModel=new BaoOtherPrize();
        $baoOtherModel->mobile=$mobile;
        if($rid==15){
            $baoOtherModel->code='eweixiu01';
            $baoOtherModel->prize_name='“猴”彩包-马桶代金券30元+水管/水龙头代金券20元';
        }elseif($rid==16){
            $baoOtherModel->code='eweixiu02';
            $baoOtherModel->prize_name='“赛”彩包-热水器代金券20元+燃气灶代金券30元';
        }elseif($rid==17){
            $baoOtherModel->code='eweixiu03';
            $baoOtherModel->prize_name='“雷”彩包-灯具维代金20元+马桶代金券30元';
        }elseif($rid==18){
            $baoOtherModel->code='ezufang01';
            $baoOtherModel->prize_name='租房优惠券100元';
        }elseif($rid==19){
            $baoOtherModel->code='ezufang02';
            $baoOtherModel->prize_name='智能门锁200元抵用券';
        }elseif($rid==20){
            $baoOtherModel->code='youlun';
            $baoOtherModel->prize_name='邮轮礼券200元（HKD）';
        }
        $baoOtherModel->create_time=time();
        $result=$baoOtherModel->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 判断礼包的数量是否超过数量
     * @return array
     */
    public function checkLiBao(){
        $zuheOneArr=BaoUserBaoxiang::model()->findAll('code=:code and update_time>=:startDay and update_time<=:endDay',array(':code'=>'zuhe01',':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
        $zuheTwoArr=BaoUserBaoxiang::model()->findAll('code=:code and update_time>=:startDay and update_time<=:endDay',array(':code'=>'zuhe02',':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
        if(count($zuheOneArr)>2){
            unset($this->prize_bao_arr[0]);
        }
        if(count($zuheTwoArr)>20){
            unset($this->prize_bao_arr[1]);
        }
        return $this->prize_bao_arr;
    }
    /*
     * @version E维修全部插入
     * @param string $mobile
     * @param string $code
     * @param string $prize_name
     * return boolean
     */
    private function insertEWeiXiu($mobile,$code,$prize_name){
        if(empty($mobile)|| empty($code)){
            return false;
        }
        $create_time=time();
        $sql="insert into bao_other_prize(mobile,code,prize_name,create_time) values('".$mobile."','".$code."','".$prize_name."',".$create_time.")";
        $res = Yii::app()->db->createCommand($sql)->execute();
        if($res){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version E租房全部插入
     * @param string $mobile
     * return boolean
     */
    private function insertEZuFang($mobile){
        if(empty($mobile)){
            return false;
        }
        $create_time=time();
        $sql="insert into bao_other_prize(mobile,code,prize_name,create_time) values('".$mobile."','ezufang01','租房优惠券100元',".$create_time."),('".$mobile."','ezufang02','智能门锁200元抵用券',".$create_time.")";
        $res = Yii::app()->db->createCommand($sql)->execute();
        if($res){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 优惠券全部插入
     * @param string $mobile
     * return boolean
     */
    private function insertYouHuiQuan($mobile){
        if(empty($mobile)){
            return false;
        }
        $you_hui_quan_arr=array(
            '0' => array('id'=>1,'code'=>100000057), 
            '1' => array('id'=>2,'code'=>100000058),
            '2' => array('id'=>3,'code'=>100000059),
            '3' => array('id'=>4,'code'=>100000060),
            
        );
        $str='';
        $create_time=time();
        foreach ($you_hui_quan_arr as $v){
            $userCouponsArr=UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile',array(':you_hui_quan_id'=>$v['code'],':mobile'=>$mobile));
            if(!empty($userCouponsArr)){
                unset($you_hui_quan_arr[$v['id']-1]);
                $str .='';
            }else{
                $str .= "('" .$mobile."',".$v['code'].",0,0,".$create_time."),";
            }
            
            
        }
        if($str!=''){
            $str = trim($str, ',');
            $sql="insert into user_coupons(mobile,you_hui_quan_id,is_use,num,create_time) values {$str}";
            $res = Yii::app()->db->createCommand($sql)->execute();
            if($res){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
        
    }
    /*
     * @version 判断摇一摇是否可以点击
     * @return boolean
     */
    public function canYao(){
        $now=date('H');
        if(($now>=14 && $now<15) || ($now>=19 && $now<20)){
            return true;
        }else{
            return false;
        }
    }
    
    /**
     * 获取优惠券
     * @param unknown $you_hui_quan_id
     * @return array
     */
    public function getYouHuiQuan($you_hui_quan_id){
    	$youhuiquan=YouHuiQuan::model()->find("id=:id",array(':id'=>$you_hui_quan_id));
    	return $youhuiquan;
    }
    
    /**
     * 获取详细宝藏
     */
    public function getPrize($code){
    	$data=array();
    	$arr=array('zuhe01','zuhe02');
    	if (!in_array($code, $arr)){
    		return $data;
    	}
    	if ($code=='zuhe01'){
    		$data=array (
					'一元购码 ' => array (
							'澳门旅游酒店礼券'
					),
					'E维修' => array (
							'马桶代金券30元',
							'水管/水龙头代金券20元',
							'热水器代金券20元',
							'燃气灶代金券30元',
							'灯具代金券20元' 
					) 
			);
    	}elseif ($code=='zuhe02'){
    		$data=array (
					'优惠券' => array (
							'彩生活特供满300减80券',
							'彩生活特供满500减100券',
							'环球精选满100减10券',
							'环球精选满200减20券' 
					),
					'E租房' => array (
							'租房优惠券100元',
							'智能门锁200元抵用券' 
					),
					'E维修' => array (
							'马桶代金券30元',
							'水管/水龙头代金券20元',
							'热水器代金券20元',
							'燃气灶代金券30元',
							'灯具代金券20元'
					)
			);
    	}
    	return $data;
    }
    /*
     * @version 获取次数优化
     * @param String $mobile
     */
    public function getCount($mobile){
        if(empty($mobile)){
            return false;
        }
        $sql="select * from bao_user_lingqunum where mobile=".$mobile." and state=0 GROUP BY create_time";
        $resArr=Yii::app()->db->createCommand($sql)->queryAll();
        $count=count($resArr);
        return $count;
    }
}

