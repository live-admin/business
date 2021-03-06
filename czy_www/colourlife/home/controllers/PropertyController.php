<?php
/**
 * Created by PhpStorm.
 * User: taodanfeng
 * Date: 2016/10/20
 * Time: 9:41
 * 预缴费活动
 */
class PropertyController extends ActivityController
{
    public $beginTime = '2016-12-31 23:59:59';//活动开始时间
    //public $beginTime='2016-11-01 09:00:00';//活动开始时间
    public $endTime = '2017-01-13 23:59:59';//活动结束时间
    public $secret = 'pr*op%er^ty';
    public $layout = false;
    private $cmobile = '20000000008';
    //这里是测试环境的appkey和appsecrets
    //private $Appkey='bh5Ua25gZlzCQMFrRoSc';
    //private $AppSecret='aR55DmCErU';
    //private $url = 'http://test.eshifu.cn/open/sendCoupon';//测试;正式环境m.eshifu.cn
    private $Appkey='LXqeFJBgguB8tUSmL2jT';
    private $AppSecret='H5z5VKfg9S';
    private $url = 'http://m.eshifu.cn/open/sendCoupon';
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb,Share,InviteRegister,SendCode,Register',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
                'users' => array('@'),
            ),
        );
    }
    //app首页弹窗
    public function actionTip(){
        $customer_id = $this->getUserId();
        $modelChance = PropertyPrizeChance::model()->find('customer_id=:customer_id and status=:status', array(':customer_id'=>$customer_id ,':status'=>0));
        $chance_number = 0;
        if(!empty($modelChance))
            $chance_number = $modelChance['number'];

        $this->render('/v2016/property/pop',array(
            'chance_number' => $chance_number,
        ));
    }

    //抽奖首页
    public function actionIndex(){
        //echo '活动将于1月1日后再次开放抽奖！';exit();
        $userID = $this->getUserId();
        $chance_number=Property::model()->getChanceNumber($userID);
        if($chance_number<0){$chance_number=0;}
        //朋友圈
        $time=time();
        $customer_id = $userID * 778 + 1778;
        $sign=md5('sd_id='.$customer_id.'&ts='.$time);
        $urlShare=F::getHomeUrl('/Property/ShareWeb').'?sd_id='.$customer_id.'&ts='.$time.'&sign='.$sign;
        $this->render('/v2016/property/index',array(
           'chance_number' => $chance_number,
            'surl'=>base64_encode($urlShare)
       ));
    }
    //抽奖首页中的活动规则
    public function actionRules(){
        $this->render('/v2016/property/rules');
    }
    //首页中奖纪录
    public function actionPrizeRecord(){
         $customer_id =  $this->getUserId();
        if (empty($customer_id)){
          exit("参数错误！");
       }
        $recordList=Property::model()->getPrizeRecord($customer_id);
        $this->render('/v2016/property/prizerecord',array('recordList'=>$recordList));
    }
    //点击抽奖
    public function actionAjaxProperty()
    {

        // 判断用户是否有抽奖的次数
        $customer_id =  $this->getUserId();
//        if($customer_id != 1288746){
//            echo '系统异常，抽奖稍后继续开启！';
//            exit();
//        }
        $sql = 'SELECT number FROM property_prize_chance WHERE `customer_id`=:customer_id and status=0';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':customer_id', $customer_id, PDO::PARAM_STR);
        $resultChance = $command->queryRow();
        if ($resultChance) {
            if($resultChance['number']<=0){
                $this->output('', 0, '无抽奖次数');//无抽奖次数
            }
        }else{
           $this->output('', 0, '此用户无抽奖权限');//无抽奖次数
        }
        /*
         * 奖项数组
         * 是一个二维数组，记录了所有本次抽奖的奖项信息，
         * 其中id表示中奖等级，prize表示奖品，v表示中奖概率。
         * 注意其中的v必须为整数，你可以将对应的 奖项的v设置成0，即意味着该奖项抽中的几率是0，
         * 数组中v的总和（基数），基数越大越能体现概率的准确性。
         * 本例中v的总和为100，那么平板电脑对应的 中奖概率就是1%，
         * 如果v的总和是10000，那中奖概率就是万分之一了。
         */
        $prize_arr = array(
//            '1' => array('id'=>1,  'v'=>1, 'maxNumber'=>10, 'award'=>'一等奖'),
//            '2' => array('id'=>2,  'v'=>5, 'maxNumber'=>50, 'award'=>'二等奖'),
//            '3' => array('id'=>3, 'v'=>300, 'maxNumber'=>3000, 'award'=>'三等奖'),
//            '4' => array('id'=>4, 'v'=>2000, 'maxNumber'=>20000, 'award'=>'四等奖'),
//            '5' => array('id'=>5,  'v'=>4500, 'maxNumber'=>45000, 'award'=>'五等奖'),
//            '6' => array('id'=>6, 'v'=>13194, 'maxNumber'=>131940, 'award'=>'六等奖'),
            '1' => array('id'=>1,  'v'=>0, 'maxNumber'=>10, 'award'=>'一等奖'),
            '2' => array('id'=>2,  'v'=>0, 'maxNumber'=>50, 'award'=>'二等奖'),
            '3' => array('id'=>3, 'v'=>0, 'maxNumber'=>3000, 'award'=>'三等奖'),
            '4' => array('id'=>4, 'v'=>0, 'maxNumber'=>20000, 'award'=>'四等奖'),
            '5' => array('id'=>5,  'v'=>0, 'maxNumber'=>45000, 'award'=>'五等奖'),
            '6' => array('id'=>6, 'v'=>13194, 'maxNumber'=>131940, 'award'=>'六等奖'),
        );
        $sql = 'SELECT `level_id`, count(`id`) AS `total` FROM `property_prize_record` GROUP BY `level_id`';
        $result = Yii::app()->db->createCommand($sql)->queryAll();
        if ($result) {
            foreach ($result as $row) {
                if ($row['total'] >= $prize_arr[$row['level_id']]['maxNumber'])
                    //将其概率设为0永远抽不到
                    $prize_arr[$row['level_id']]['v']=0;

            }
        }
        $tem = true;
        foreach($prize_arr as $vr){
            if($vr['v'] != 0){
                $tem = false;
            }
        }
        if($tem){
            $this->output('', 0, '奖品已抢完，请联系客服补充奖品！');//奖品已抢完
        }


        $awards = $this->zhongjiang($prize_arr);//得到中奖的数组信息


        $data = array(
            'customer_id'       => $customer_id,
            'time'   => time(),
            'prize_id'=>null,
            'status' => 0,
            'level_id' => $awards['id']
        );

        $res = Yii::app()->db->createCommand()->insert('property_prize_record', $data);
        //获取中奖纪录新增id
        $record_id=Yii::app()->db->getLastInsertID();
        if (!$res) {
            $this->output('', 0, '中奖纪录插入失败');//中奖纪录插入失败
        }else{
            //更新中奖次数
            $number=$resultChance['number']-1;
            $updateData=array("number"=>$number);
            $res1 = Yii::app()->db->createCommand()->update('property_prize_chance', $updateData, "customer_id=:customer_id and status=:status", array(':customer_id'=>$customer_id,':status'=>0));
            if(!$res1){
                $this->output('', 0, '更新中奖机会失败');//更新中奖机会失败
            }
        }

        $outData = array(
            'level_id'=> $awards['id'],
            'chanceNumber'=>$number,
            'time'=>$data['time'],
            'record_id'=>$record_id
        );
        $this->output($outData);
    }

    //中奖之后选择奖品的列表
    public function actionChoosePrize(){
        //获取中奖等级（在跳转链接上传递中奖等级和时间）
        $level_id = Yii::app()->request->getParam('level_id');
        $record_id = Yii::app()->request->getParam('record_id');
        $customer_id =  $this->getUserId();
        //中奖成功后插入到中奖纪录表还未领取的time
        $time= Yii::app()->request->getParam('time');
       $mobile=Property::model()->getMobile($customer_id);
        if($mobile==0){
            $this->output('', 0, '用户手机号获取错误');
        }
        //判断中奖等级
        $prizeList=Property::model()->getPrizeList($level_id);
        if($prizeList==0){
            $this->output('', 0, '此等奖奖品已抽完');
        }
        //判断每个奖品的剩余件数，如有剩余则显示，没有则不显示，通过status字段标识
        $param=Property::model()->getPrizeListParam($prizeList,$customer_id,$mobile,$record_id);
        $this->render('/v2016/property/chooseprize',array('prizeList'=>$param,'time'=>$time,'level_id'=>$level_id));
    }

    //中奖之后领取奖品
    public function actionGetPrize(){
        //通过actionChoosePrize方法传递获得的参数
        $id =Yii::app()->request->getParam('id');
        $category_id =  Yii::app()->request->getParam('category_id');
        $customer_id =  $this->getUserId();
        $level_id =  Yii::app()->request->getParam('level_id');
        $mobile =  Yii::app()->request->getParam('mobile');
        $record_id =  Yii::app()->request->getParam('record_id');
        //中奖成功后插入到中奖纪录表还未领取的time
        $time =  Yii::app()->request->getParam('time');
        if($category_id==1||$category_id==3||$category_id==5){
            //跳转到地址页面
            $res1=Property::model()->getRecordRow($time);
            if($res1) {
                $param = array('customer_id' => $customer_id, 'prize_id' => $id, 'level_id' => $level_id, 'time' => $time, 'category_id' => $category_id, 'record_id' => $record_id);
                //$this->render('/v2016/property/address', array('param' => $param));
                $this->output($param,1);
            }else{
                $this->output('',0,'您已经领取了该奖品');
            }
        }else if($category_id==2){
            //直接调用接口发送中奖饭票
            //判断是否已经领奖回跳后再领一次
            $res1=Property::model()->getRecordRow($time);
            if($res1){
                $this->putTiket($customer_id,$level_id,$id,$time);
            }else{
                $this->output('',0,'您已经领取了该奖品');
            }

        }else if($category_id==4){
            //判断是否已经领奖回跳后再领一次
            $res1=Property::model()->getRecordRow($time);
            if($res1) {
                //直接调用发送券的接口,选择优惠券
                $transaction = Yii::app()->db->beginTransaction();
                try{
                    //获取$openCouponId
                    $list=Property::model()->getOpenCouponId($id);
                    $res=$this->sendCoupon($mobile, $list['goods_id']);
                    if($res){
                        //券发放成功
                        $now = time();
                        //更新中奖纪录表
                        $updateSql = "update property_prize_record set time ='" . $now . "',prize_id=" . $id . ",status=1 where customer_id=" . $customer_id . " and level_id=" . $level_id . " and status=0 and time=" . $time;
                        Yii::app()->db->createCommand($updateSql)->execute();
                        //更新奖品数量
                        Property::model()->updatePrizeNumber($id, $level_id);
                        $HomeConfig = new HomeConfigResource();
                        $rent = $HomeConfig->getResourceByKeyOrId('EWEIXIU', 1, $customer_id);
                        $url = $rent->completeURL;
                    }else{
                        $transaction->rollback();
                        Yii::log("券发送失败-1",CLogger::LEVEL_INFO,'colourlife.core.GetPrize');
                        $this->output('',0,'券发送失败');
                    }
                }catch (Exception $e){
                    $transaction->rollback();
                    Yii::log("券发送失败-2",CLogger::LEVEL_INFO,'colourlife.core.GetPrize');
                    $this->output('',0,'券发送失败-2');
                }
                $transaction->commit();
                Yii::log("券发送成功",CLogger::LEVEL_INFO,'colourlife.core.GetPrize');
                $this->output(array('url'=>$url,'msg'=>'券发送成功'));
            }else{
                $this->output('',0,'您已经领取了该奖品');
            }
         
        }else if($category_id==6){
            //彩食惠优惠券
            //判断是第几等奖
            if($level_id==5){
                //判断是否已经领奖回跳后再领一次
                $res2=Property::model()->getRecordRow($time);
                if($res2) {
                    $transaction = Yii::app()->db->beginTransaction();
                    try{
                           //券发放成功
                            $now = time();
                            //更新中奖纪录表
                            $updateSql = "update property_prize_record set time ='" . $now . "',prize_id=" . $id . ",status=1 where customer_id=" . $customer_id . " and level_id=" . $level_id . " and status=0 and time=" . $time;
                           Yii::app()->db->createCommand($updateSql)->execute();
                           //更新奖品数量
                            Property::model()->updatePrizeNumber($id, $level_id);
                            $HomeConfig = new HomeConfigResource();
                            $rent = $HomeConfig->getResourceByKeyOrId('caishihui', 1, $customer_id);
                            $url = $rent->completeURL;
                        $url = str_replace("http://www.colourlife.com/advertisement/colourEat","https://csh520.cn/mobile/colorlife/colorliferoulette_five.php",$url);
                    }catch (Exception $e){
                        $transaction->rollback();
                        $this->output('',0,'券发送失败-2');
                    }
                    $transaction->commit();
                    $this->output(array('url'=>$url,'msg'=>'券发送成功'));
                }else{
                    $this->output('',0,'您已经领取了该奖品');
                }
            }else if($level_id==6){
                //判断是否已经领奖回跳后再领一次
                $res2=Property::model()->getRecordRow($time);
                if($res2) {
                    $transaction = Yii::app()->db->beginTransaction();
                    try{
                        //券发放成功
                        $now = time();
                        //更新中奖纪录表
                        $updateSql = "update property_prize_record set time ='" . $now . "',prize_id=" . $id . ",status=1 where customer_id=" . $customer_id . " and level_id=" . $level_id . " and status=0 and time=" . $time;
                        Yii::app()->db->createCommand($updateSql)->execute();
                        //更新奖品数量
                        Property::model()->updatePrizeNumber($id, $level_id);
                        $HomeConfig = new HomeConfigResource();
                        $rent = $HomeConfig->getResourceByKeyOrId('caishihui', 1, $customer_id);
                        $url = $rent->completeURL;
                        $url = str_replace("http://www.colourlife.com/advertisement/colourEat","https://csh520.cn/mobile/colorlife/colorliferoulette.php",$url);
                    }catch (Exception $e){
                        $transaction->rollback();
                        $this->output('',0,'券发送失败-2');
                    }
                    $transaction->commit();
                    $this->output(array('url'=>$url,'msg'=>'券发送成功'));
                }else{
                    $this->output('',0,'您已经领取了该奖品');
                }
            }
        }
    }
    public function actionGetAddress(){
        $id =Yii::app()->request->getParam('id');
        $category_id =  Yii::app()->request->getParam('category_id');
        $customer_id =  $this->getUserId();
        $level_id =  Yii::app()->request->getParam('level_id');
        $record_id =  Yii::app()->request->getParam('record_id');
        //中奖成功后插入到中奖纪录表还未领取的time
        $time =  Yii::app()->request->getParam('time');
        $param = array('customer_id' => $customer_id, 'prize_id' => $id, 'level_id' => $level_id, 'time' => $time, 'category_id' => $category_id, 'record_id' => $record_id);
        $this->render('/v2016/property/address', array('param' => $param));
    }

    //京东或者实物地址提交后
    public function actionSaveAddress(){
        //将接收到的参数插入到数据库中
        //判断是否
        $oldtime = Yii::app()->request->getParam('time');
        $res1=Property::model()->getRecordRow($oldtime);
        if($res1){
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $prize_id = Yii::app()->request->getParam('prize_id');
                $customer_id = $this->getUserId();
                $category_id = Yii::app()->request->getParam('category_id');
                //$oldtime = Yii::app()->request->getParam('time');
                $record_id = Yii::app()->request->getParam('record_id');
                $level_id = Yii::app()->request->getParam('level_id');

                $provinceName = Yii::app()->request->getParam('provinceName');
                $cityName = Yii::app()->request->getParam('cityName');
                $countyName = Yii::app()->request->getParam('countyName');
                $townName = Yii::app()->request->getParam('townName');

                $provinceId = Yii::app()->request->getParam('provinceId');
                $cityId = Yii::app()->request->getParam('cityId');
                $countyId = Yii::app()->request->getParam('countyId');
                $townid = Yii::app()->request->getParam('townid');

                $detailaddress = Yii::app()->request->getParam('address');
                $tel = Yii::app()->request->getParam('buyer_tel');
                $username = Yii::app()->request->getParam('buyer_name');
                $zip = Yii::app()->request->getParam('zip');
                $name = Property::model()->getOpenCouponId($prize_id);
                $prizename = $name['name'];
                //地址拼接
                $address = $provinceName . ' ' . $cityName . ' ' . $countyName . ' ' . $townName . ' ' . $detailaddress;
                $address_id=$provinceId . ' ' . $cityId . ' ' . $countyId . ' ' . $townid ;
                $time = time();
                $sn = SN::initByOnlineSale($prize_id)->getSN();
                $data = array(
                    'customer_id' => $customer_id,
                    'prize_id' => $prize_id,
                    'record_id'=>$record_id,
                    'name' => $prizename,
                    'address' => $address,
                    'mobile' => $tel,
                    'username' => $username,
                    'category_id' => $category_id,
                    'status' => 0,
                    'zip' => $zip,
                    'time' => $time,
                    'sn' => $sn,
                    'address_id'=>$address_id
                );
                $execute=Yii::app()->db->createCommand()->insert('property_prize_address', $data);
                //更新中奖纪录表
                $updateSql = "update property_prize_record set time ='" . $time . "',prize_id=" . $prize_id . ",status=1 where customer_id=" . $customer_id . " and level_id=" . $level_id . " and status=0 and time=" . $oldtime;
                $res=Yii::app()->db->createCommand($updateSql)->execute();
                if($execute&&$res){
                    //更新奖品数量
                    Property::model()->updatePrizeNumber($prize_id, $level_id);
                }else{
                    $transaction->rollback();
                   Yii::log("商品领取失败-1",CLogger::LEVEL_INFO,'colourlife.core.SaveAddress');
                    $this->output('', 0, '商品领取失败-5');
                }

//                if ($execute) {
//                    //更新中奖纪录表
//                    $updateSql = "update property_prize_record set time ='" . $time . "',prize_id=" . $prize_id . ",status=1 where customer_id=" . $customer_id . " and level_id=" . $level_id . " and status=0 and time=" . $oldtime;
//                    Yii::app()->db->createCommand($updateSql)->execute();
//                    //更新奖品数量
//                    Property::model()->updatePrizeNumber($prize_id, $level_id);
//                }else{
//                    $transaction->rollback();
//                    Yii::log("商品领取失败-1",CLogger::LEVEL_INFO,'colourlife.core.SaveAddress');
//                    $this->output('', 0, '商品领取失败-1');
//                }

            }catch (Exception $e){
                $transaction->rollback();
                Yii::log("商品领取失败-2",CLogger::LEVEL_INFO,'colourlife.core.SaveAddress');
                $this->output('', 0, '商品领取失败-2');
            }
            $transaction->commit();
            $data1=array('prizename'=>$prizename);
            Yii::log("商品领取成功",CLogger::LEVEL_INFO,'colourlife.core.SaveAddress');
            $this->output($data1,1,'商品领取成功，请耐心等待发货');
        }else{
            $this->output('',0,'您已经领取了该奖品');
        }


    }

    /*
    * @versino 获取所有省
    * @coptyright(c) 2015.04.30 josen
    * @return json
    */
    public function actionGetProvince(){
        $array=PinTuan::model()->getProvince();
        echo urldecode(json_encode($array));
    }
    /*
     * @versino 获取市
     * @coptyright(c) 2015.04.30 josen
     * @return json
     */
    public function actionGetCity(){
        $id = intval(Yii::app()->request->getParam('provice_id'));
        $array=PinTuan::model()->getCity($id);
        echo urldecode(json_encode($array));
    }
    /*
     * @versino 获取县/区
     * @coptyright(c) 2015.04.30 josen
     * @return json
     */
    public function actionGetCounty(){
        $id = intval(Yii::app()->request->getParam('city_id'));
        $array=PinTuan::model()->getCounty($id);
        echo urldecode(json_encode($array));
    }
    /*
     * @versino 获取镇
     * @coptyright(c) 2015.04.30 josen
     * @return json
     */
    public function actionGetTown(){
        $id = intval(Yii::app()->request->getParam('county_id'));
        $array=PinTuan::model()->GetTown($id);
        echo urldecode(json_encode($array));
    }



    //发送饭票
    private function putTiket($userid,$level_id,$id,$time){
       // $transaction = Yii::app()->db->beginTransaction();
        try {
            //正式环境
            $cmobile_id = 2507585;
            $cmobile = $this->cmobile;
            $customer_id = $userid;
            //测试
            //$cmobile_id = '170';
            //$cmobile = '13612878902';
           //$customer_id='171';

            //获取要发送的饭票金额
            $price = Property::model()->getTiketMoney($level_id);
            if(empty($price)){
                $this->output('', 0, '无饭票！');
            }else{
                $amount=$price['price'];
            }
            //判断饭票余额是否存在
            $res1=Property::model()->getMoney($cmobile_id);
            if(!empty($res1)){
                if($res1['balance']<$amount){
                    $this->output('', 0, '此账户余额不足！');
                }
            }else{
                $this->output('', 0, '此账户不存在！');
            }
            $note = '预缴费活动大转盘';
            $rebateResult = RedPacketCarry::model()->customerTransferAccounts($cmobile_id, $customer_id, $amount, 1, $cmobile, $note);
            if (1 ==$rebateResult['status']) {
                $now=time();
                //更新中奖纪录表
                $updateSql = "update property_prize_record set time ='".$now."',prize_id=".$id.",status=1 where customer_id=".$customer_id." and level_id=".$level_id." and status=0 and time=".$time;
                Yii::app()->db->createCommand($updateSql)->execute();
                //更新奖品数量
                Property::model()->updatePrizeNumber($id,$level_id);
            }
            else {
               // $transaction->rollback();
                Yii::log("饭票领取失败-1",CLogger::LEVEL_INFO,'colourlife.core.putTiket');
                $this->output('', 0, '饭票领取失败-1');
            }
        }
        catch (Exception $e) {
           //$transaction->rollback();
            Yii::log("饭票领取失败-2",CLogger::LEVEL_INFO,'colourlife.core.putTiket');
            $this->output('', 0, '饭票券领取失败-2');
        }

      //  $transaction->commit();
        Yii::log("领取成功",CLogger::LEVEL_INFO,'colourlife.core.putTiket');
        $this->output(array('price'=>$amount,'msg'=>'领取成功'));
    }


    private function zhongjiang($prize_arr)
    {
        /*
         * 每次前端页面的请求，PHP循环奖项设置数组
         * 通过概率计算函数get_rand获取抽中的奖项id。
         * 将中奖奖品保存在数组$res['yes']中
         * 而剩下的未中奖的信息保存在$res['no']中
         * 最后输出json个数数据给前端页面。
         */
        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->getRand($arr); //根据概率获取奖项id

        return $prize_arr[$rid];
    }

    private function getRand($proArr)
    {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }

    /**
     * 发放E维修优惠券
     * @param mobile,openCouponId
     * @return bool
     */
    private function sendCoupon($mobile, $openCouponId)
    {
        $timeStamp = time() * 1000;
        $sign = StrtoUpper(md5($this->AppSecret . 'appKey' . $this->Appkey . 'mobile' . $mobile . 'openCouponId' . $openCouponId . 'timestamp' . $timeStamp . $timeStamp));
        $param = array(
            'mobile' => $mobile,
            'appKey' => $this->Appkey,
            'couponId' => $openCouponId,
            'timestamp' => $timeStamp,
            'sign' => $sign,
        );
        $requestUrl = Yii::app()->curl->buildUrl($this->url, $param);
        $result = json_decode(Yii::app()->curl->post($requestUrl, $param), true);
        if (!empty($result) && $result['code'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    //分享页
    public function actionShareWeb(){
        $sd_id=  intval(Yii::app()->request->getParam('sd_id'));//用户id
        $time=Yii::app()->request->getParam('ts');
        $sign=Yii::app()->request->getParam('sign');
        $checkSign=md5('sd_id='.$sd_id.'&ts='.$time);
        if ($sign!=$checkSign){
            throw new CHttpException(400, "验证失败！");
        }
        $this->render('/v2016/property/share_wechat');

    }
    
}
