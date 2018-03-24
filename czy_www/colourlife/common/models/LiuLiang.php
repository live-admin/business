<?php

/*
 * @version 司庆抽奖model
 */
class LiuLiang extends CActiveRecord{
    public $beginTime='2016-08-11';//活动开始时间
    public $endTime='2016-12-30 23:59:59';//活动结束时间
    //流量包配置
    private $package_arr=array(
        '00010' => array('id'=>'00010','prize_id'=>1,'prize_name'=>'10M流量包','v'=>980),
        '00020' => array('id'=>'00020','prize_id'=>2,'prize_name'=>'20M流量包','v'=>15),
        '00030' => array('id'=>'00030','prize_id'=>3,'prize_name'=>'30M流量包','num'=>10,'v'=>2),
        '00050' => array('id'=>'00050','prize_id'=>4,'prize_name'=>'50M流量包','num'=>10,'v'=>2),
        '00100' => array('id'=>'00100','prize_id'=>5,'prize_name'=>'100M流量包','num'=>5,'v'=>1),
        '00500' => array('id'=>'00500','prize_id'=>6,'prize_name'=>'500M流量包'),//统一发500M流量
    );
    private $liuLiangBao=array(
        '1'=>'00010',
        '2'=>'00020',
        '3'=>'00030',
        '4'=>'00050',
        '5'=>'00100',
        '6'=>'00500',
    );
    //统一发500M流量
//    private $package_arr_other=array(
//        '00500' => array('id'=>'00500','prize_id'=>6,'prize_name'=>'500M流量包'),
//    );
    
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 通过手机号码领取流量
     * @param string $mobile 充值的号码
     * @param string $openId
     * return array
     */
    public function getLingQu($mobile,$openId){
        
        $nowTime = time();
        if ( $nowTime < strtotime($this->beginTime)){
            exit('活动未开始，敬请期待！');
        }
        if ( $nowTime > strtotime($this->endTime)){
            exit('活动已结束！');
        }
        if(empty($mobile) || empty($openId)){
            return false;
        }
        $openIdCheck= LiuliangPrize::model()->find('open_id=:open_id',array(':open_id'=>$openId));
        if(!empty($openIdCheck)){
           return $list=array('success'=>0,'msg'=>'每个微信ID限领一次');
        }
        $mobileCheck= LiuliangPrize::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
        if(!empty($mobileCheck)){
            return $list=array('success'=>0,'msg'=>'该手机号码已经领取过了！');
        }
//        $checkYan=$this->checkYanZhengMa($verifyCode);
//        if(!$checkYan){
//            return $list=array('success'=>0,'msg'=>'验证码错误！');
//        }
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
        $prize_name=$this->package_arr[$package]['prize_name'];
        $prize_id=$this->package_arr[$package]['prize_id'];
        //分开
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
            $res=$this->insertLiuPrize($openId, $mobile, $prize_id, $prize_name);
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
     * @version 点击获取验证码
     * @param string $mobile 充值的号码
     * @param string $openId
     * return array
     */
    public function getYanZhengMa($mobile,$openId){
        $nowTime = time();
        if ( $nowTime < strtotime($this->beginTime)){
            exit('活动未开始，敬请期待！');
        }
        if ( $nowTime > strtotime($this->endTime)){
            exit('活动已结束！');
        }
        if(empty($mobile) || empty($openId)){
            return false;
        }
        $openIdCheck= LiuliangPrize::model()->find('open_id=:open_id',array(':open_id'=>$openId));
		
        if(!empty($openIdCheck)){
           return $list=array('success'=>0,'msg'=>'每个微信ID限领一次');
        }
        $mobileCheck= LiuliangPrize::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
        if(!empty($mobileCheck)){
            return $list=array('success'=>0,'msg'=>'该手机号码已经领取过了！');
        }
        //发送验证码
        $model = new SmsForm();
		
        $model->setScenario('verifyMobile');
		
//        $model->attributes = Yii::app()->request->restParams;
        $model->mobile = $mobile;
        if (!$model->validate()){
//            $this->output('', 0, $this->errorSummary($model));
            return $list=array('success'=>0,'msg'=>$this->errorSummary($model));
        }
		
        //检查次数
        $num = Item::SMS_LIMIT_VALIDATE;
        $count = $model->GetBlackValidateNum();
        if ($count >= $num){
            return $list=array('success'=>0,'msg'=>'您的手机号因验证次数过多已被禁用，如果不是您本人操作请联系客服');
        }
        if ( !$model->send()){
            return $list=array('success'=>0,'msg'=>$this->errorSummary($model));
        }
        return $list=array('success'=>1,'msg'=>'发送成功');
    }
    /*
     * @version 点击立即领取
     * @param string $mobile 充值的号码
     * @param string $openId
     * return array
     */
    public function getRightNow($mobile,$openId){
        $nowTime = time();
        if ( $nowTime < strtotime($this->beginTime)){
            exit('活动未开始，敬请期待！');
        }
        if ( $nowTime > strtotime($this->endTime)){
            exit('活动已结束！');
        }
        if(empty($mobile) || empty($openId)){
            return false;
        }
        $openIdCheck= LiuliangPrize::model()->find('open_id=:open_id',array(':open_id'=>$openId));
        if(!empty($openIdCheck)){
           return $list=array('success'=>0,'msg'=>'每个微信ID限领一次');
        }
        $mobileCheck= LiuliangPrize::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
        if(!empty($mobileCheck)){
            return $list=array('success'=>0,'msg'=>'该手机号码已经领取过了！');
        }
        //通过手机号码获取相应的流量包
        $package='00500';
//        $package=$this->checkPackage($mobile);
//        if($package=='low'){
//            return $list=array('success'=>0,'msg'=>'流量包已经领取完了，正在加紧补充！');
//        }
//        if($package=='yidongout' || $package=='liantongout' || $package=='dianxinout'){
//            return $list=array('success'=>0,'msg'=>'手机号对应的运营商流量包已经领取完了，请更换其他运营商号码');
//        }
        $prize_name=$this->package_arr[$package]['prize_name'];
        $prize_id=$this->package_arr[$package]['prize_id'];
        $res=$this->insertLiuPrize($openId, $mobile, $prize_id, $prize_name);
        if($res){
            return $list=array('success'=>1);
        }else{
            return $list=array('success'=>0,'msg'=>'领取失败，请重新试一下');
        }
    }
    /*
     * @version app领取
     * @param int $id 记录id
     */
    public function getLiuLiangByApp($id){
        if(empty($id)){
            return false;
        }
        $prizeRecord=LiuliangPrize::model()->findByPk($id);
        $package=$this->liuLiangBao[$prizeRecord['prize_id']];
        $mobile=$prizeRecord['mobile'];
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
            $res=$this->changeGetStatus($id);
            $res2=$this->insertLiuOrder($ext_id,$mobile,$package,$result['code']);
            if($res && $res2){
                $transaction->commit();
                return $list=array('success'=>1);
            }else{
                $transaction->rollback();
                return $list=array('success'=>0,'msg'=>'获取产品失败！');
            }
        }else{
            return $list=array('success'=>0,'msg'=>$result['desc']);
        }
    }
    /*
     * @version 更改记录状态
     * @param int $id 记录id
     * return boolean
     */
    public function changeGetStatus($id){
        if(empty($id)){
            return false;
        }
        $sqlUpdate='update liuliang_prize set get_status=0 where id='.$id;
        $result=Yii::app()->db->createCommand($sqlUpdate)->execute();
        if($result){
            return true;
        }else{
            return false;
        }
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
        $m3=LiuliangPrize::model()->count('prize_id=:prize_id',array(':prize_id'=>3));
        $m4=LiuliangPrize::model()->count('prize_id=:prize_id',array(':prize_id'=>4));
        $m5=LiuliangPrize::model()->count('prize_id=:prize_id',array(':prize_id'=>5));
        
        if($m3>=$this->package_arr['00030']['num']){
            unset($this->package_arr['00030']);
        }
        if($m4>=$this->package_arr['00050']['num']){
            unset($this->package_arr['00050']);
        }
        if($m5>=$this->package_arr['00100']['num']){
            unset($this->package_arr['00100']);
        }
        if(!empty($this->package_arr)){
            $sms=$this->getMobileArea($mobile);
            if($sms['supplier']=='中国移动'){
                if(isset($this->package_arr['00010']) || isset($this->package_arr['00030'])){
                    unset($this->package_arr['00020']);
                    unset($this->package_arr['00050']);
                    unset($this->package_arr['00100']);
                }else{
                    return 'yidongout';
                }
            }elseif($sms['supplier']=='中国联通'){
                if(isset($this->package_arr['00020']) || isset($this->package_arr['00050']) || isset($this->package_arr['00100'])){
                    unset($this->package_arr['00010']);
                    unset($this->package_arr['00030']);
                }else{
                    return 'liantongout';
                }
            }elseif($sms['supplier']=='中国电信'){
                if(isset($this->package_arr['00010']) || isset($this->package_arr['00030']) || isset($this->package_arr['00100'])){
                    unset($this->package_arr['00020']);
                    unset($this->package_arr['00050']);
                }else{
                    return 'dianxinout';
                }
            }else{
                unset($this->package_arr['00020']);
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
     * @version 通过手机号码判断运营商
     * @param string $mobile
     * return string
     */
    public function getMobileArea($mobile){
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
        $recordModel=new LiuliangRecord();
        $recordModel->ext_id=$ext_id;
        $recordModel->mobile=$mobile;
        $recordModel->package=$package;
        $recordModel->status=$status;
        $recordModel->create_time= time();
        $isInsert=$recordModel->save();
        if($isInsert){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 插入流量奖品信息
     * @param string $open_id
     * @param string $mobile
     * @param int $prize_id
     * @param string $prize_name
     */
    public function insertLiuPrize($open_id,$mobile,$prize_id,$prize_name){
        if(empty($open_id) || empty($mobile) || empty($prize_id) || empty($prize_name)){
            return false;
        }
        $prizeModel=new LiuliangPrize();
        $prizeModel->open_id=$open_id;
        $prizeModel->mobile=$mobile;
        $prizeModel->prize_id=$prize_id;
        $prizeModel->prize_name=$prize_name;
        $prizeModel->get_status=1;
        $prizeModel->create_time= time();
        $isInsert=$prizeModel->save();
        if($isInsert){
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
    /*
     * @version 数据统计
     * @param string $open_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($open_id,$type){
        $liuLog =new LiuliangLog();
        $liuLog->open_id=$open_id;
        $liuLog->type=$type;
        $liuLog->create_time=time();
        $result = $liuLog->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    public function errorSummary($model, $firstError = false)
    {
        $content = '';
        if (!is_array($model)) {
            $model = array($model);
        }
        foreach ($model as $m) {
            foreach ($m->getErrors() as $errors) {
                foreach ($errors as $error) {
                    if ($error != '') {
                        $content .= "$error\n";
                    }
                    if ($firstError) {
                        break;
                    }
                }
            }
        }
        return $content;
    }
}

