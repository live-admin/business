<?php
/*
 * @version 植树节二期model
 */
class ZhiShuJieTwo extends CActiveRecord{
    private $_startDay='2016-05-10';//活动开始时间
    private $_endDay='2016-07-29 23:59:59';//活动结束时间
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
        '11'=>10,
        '12'=>10,
        '13'=>10,
        '14'=>10,
        '15'=>10,
        '16'=>10,
        '17'=>10,
        '18'=>10,
        '19'=>10,
        '20'=>10,
        '21'=>10,
        '22'=>10,
        '23'=>10,
        '24'=>10,
        '25'=>10,
        '26'=>10,
        '27'=>10,
        '28'=>10,
        '29'=>10,
        '30'=>10,
        '31'=>10,
        '32'=>10,
        '33'=>10,
        '34'=>10,
        '35'=>10,
        '36'=>10,
        '37'=>10,
        '38'=>10,
        '39'=>10,
        '40'=>10,
        '41'=>10,
        '42'=>10,
        '43'=>10,
        '44'=>10,
        '45'=>10,
        '46'=>10,
        '47'=>10,
        '48'=>10,
        '49'=>10,
        '50'=>10,
        '51'=>10,
        '52'=>10,
        '53'=>10,
        '54'=>10,
        '55'=>10,
        '56'=>10,
        '57'=>10,
        '58'=>10,
        '59'=>10,
        '60'=>10,
        '61'=>10,
        '62'=>10,
        '63'=>10,
        '64'=>10,
        '65'=>10,
        '66'=>10,
        '67'=>10,
        '68'=>10,
        '69'=>10,
        '70'=>10,
        '71'=>10,
        '72'=>10,
        '73'=>10,
        '74'=>10,
        '75'=>10,
        '76'=>10,
        '77'=>10,
        '78'=>10,
        '79'=>10,
    );
    //抽奖奖品配置
    private $prizeArr=array(
        //20积分奖品
        '1'=>array(
            '0' => array('id'=>1,'prize_name'=>'5点成长值','v'=>300),
            '1' => array('id'=>2,'prize_name'=>'5点经验值','v'=>300),
            '2' => array('id'=>3,'amount'=>1,'prize_name'=>'1饭票','v'=>100),
            '3' => array('id'=>4,'prize_name'=>'彩之云U盘','v'=>50),
            '4' => array('id'=>5,'goods_id'=>14241,'prize_name'=>'金龙鱼食用调和油','v'=>50),
            '5' => array('id'=>6,'prize_name'=>'谢谢参与','v'=>200),
        ),
        //50积分奖品
        '2'=>array(
            '0' => array('id'=>1,'prize_name'=>'10点成长值','v'=>375),
            '1' => array('id'=>2,'prize_name'=>'10点经验值','v'=>375),
            '2' => array('id'=>3,'amount'=>3,'prize_name'=>'3饭票','v'=>150),
            '3' => array('id'=>4,'prize_name'=>'彩之云充电宝','v'=>50),
            '4' => array('id'=>5,'goods_id'=>27599,'prize_name'=>'坚果礼盒','v'=>30),
            '5' => array('id'=>6,'goods_id'=>23896,'prize_name'=>'香山（CAMRY）圆形背光电子称体重秤','v'=>20),
        ),
        //100积分奖品
        '3'=>array(
            '0' => array('id'=>1,'prize_name'=>'50点成长值','v'=>375),
            '1' => array('id'=>2,'prize_name'=>'50点经验值','v'=>375),
            '2' => array('id'=>3,'amount'=>5,'prize_name'=>'5饭票','v'=>210),
            '3' => array('id'=>4,'goods_id'=>28973,'prize_name'=>'VR眼镜','v'=>20),
            '4' => array('id'=>5,'goods_id'=>8331,'prize_name'=>'美的系列电饭煲','v'=>15),
            '5' => array('id'=>6,'prize_name'=>'Ipad PRO','v'=>5),
        ),
    );
    
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    /*
     * @version 系统赠送一个种子
     * @param int $customer_id
     * return boolean 
     */
    public function getZhongZi($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $ZhongZiModel=new TreeTwoSeed();
        $ZhongZiModel->customer_id=$customer_id;
        $ZhongZiModel->times=1;
        $ZhongZiModel->way=1;
        $ZhongZiModel->create_time=time();
        $result=$ZhongZiModel->save();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 检查进入首页是否已经领取过种子
     * @param int $customer_id
     * return boolean
     */
    public function isGetZhongZi($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $zhongziArr=  TreeTwoSeed::model()->find('customer_id=:customer_id and way=:way',array(':customer_id'=>$customer_id,':way'=>1));
        if(empty($zhongziArr)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 判断是否是彩富人生用户以及是否已经领取过成长值了，来决定是否弹框
     * @param int $customer_id
     * return boolean
     */
    public function isCaiFuUser($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $isLingCaiArr=  TreeTwoGrow::model()->find('customer_id=:customer_id and way=:way',array(':customer_id'=>$customer_id,':way'=>6));
        $propertyArr=PropertyActivity::model()->find('customer_id=:customer_id and (status=96 or status=99)',array(':customer_id'=>$customer_id));
        $appreciationArr=AppreciationPlan::model()->find('customer_id=:customer_id and status=99',array(':customer_id'=>$customer_id));
        if((!empty($propertyArr) || !empty($appreciationArr)) && empty($isLingCaiArr)){
            return true;
        }else{
            return false;
        }
    }
    /*
     * @version 判断非彩富人生用户是否已经有弹框过
     * @param int $customer_id
     * @return boolean
     */
    public function isNoCaiFuUser($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $isZhongArr=  TreeTwoSeed::model()->find('customer_id=:customer_id and times=1',array(':customer_id'=>$customer_id));
        if(!empty($isZhongArr)){
            return true;
        }else{
            return false;
        }
    }
    /*1
     * @verson 自己浇水
     * @param int $customer_id
     * @param int $seed_id
     * @return boolean
     */
    public function getValueByJiaoShui($customer_id,$seed_id){
        if(empty($customer_id) || empty($seed_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr= TreeTwoGrow::model()->find('seed_id=:seed_id and customer_id=:customer_id and way=:way and create_time>=:beginTime and create_time<= :endTime',array(':seed_id'=>$seed_id,':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime,':way'=>1));
        if(empty($zhiArr)){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertGrowValue($customer_id, 1, 1);
            $result2=$this->insertIntegrationValue($customer_id, 1, 1);
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
    /*2
     * @verson 点击分享按钮
     * @param int $customer_id
     * @param int $seed_id
     * @return boolean
     */
    public function getValueByShare($customer_id,$seed_id){
        if(empty($customer_id) || empty($seed_id)){
            return false;
        }
        $beginTime = mktime(0,0,0);
        $endTime = time();
        $zhiArr= TreeTwoGrow::model()->find('seed_id=:seed_id and customer_id=:customer_id and way=:way and create_time>=:beginTime and create_time<= :endTime',array(':seed_id'=>$seed_id,':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime,':way'=>2));
        if(empty($zhiArr)){
            $transaction = Yii::app()->db->beginTransaction();
            $result=$this->insertGrowValue($customer_id, 5, 2);
            $result2=$this->insertIntegrationValue($customer_id, 5, 2);
            if($result && $result2){
                $transaction->commit();
                return true;
            }else{
                $transaction->rollback();
                return false;
            }
        }else{
            return true;
        }
    }
   /*3
    * @param int $customer_id
    * @param int $seed_id
    * @param string $openID
    * return boolean
    */
    public function getGrowValueByOtherJiao($seedID,$customer_id,$openID=0){
    	if(empty($seedID)){
    		return false;
    	}
    	//判断种子是否存在
    	$seed = TreeTwoSeed::model ()->find ( "id=:seed_id and customer_id=:customer_id", array (
				':seed_id' => $seedID,
				':customer_id' => $customer_id 
		) );
    	if (empty($seed)){
    		return -1;
    	}
    	$beginTime = mktime(0,0,0);
    	$endTime = time();
    	$zhiArr = TreeTwoGrow::model ()->findAll ( 'seed_id=:seed_id and way=:way and create_time>=:beginTime and create_time<= :endTime', array (
				':seed_id' => $seedID,
				':beginTime' => $beginTime,
				':endTime' => $endTime,
				':way' => 3 
		) );
    	//判断朋友浇水次数是否大于20次
    	if (!empty($zhiArr)&&count($zhiArr)>=20){
    		return -2;
    	}
    	
    	//判断是否微信用户浇水
    	if (!empty($openID)){
    		$model = TreeTwoGrow::model ()->find ( "seed_id=:seed_id and way=:way and open_id=:open_id and create_time>=:beginTime and create_time<= :endTime", array (
    				':seed_id' => $seedID,
    				':open_id' => $openID,
    				':beginTime' => $beginTime,
    				':endTime' => $endTime,
    				':way' => 3
    		) );
    		if (!empty($model)){
    			return -3;
    		}
    	}
    	$transaction = Yii::app()->db->beginTransaction();
    	//成长值
    	$growModel=new TreeTwoGrow();
    	$growModel->seed_id=$seedID;
    	$growModel->customer_id=$seed->customer_id;
    	$growModel->open_id=$openID;
    	$growModel->way=3;
    	$growModel->value=1;
    	$growModel->create_time=time();
    	$growResult=$growModel->save();
    	//积分
    	$integration=new TreeTwoIntegration();
    	$integration->seed_id=$seedID;
    	$integration->customer_id=$customer_id;
    	$integration->value=1;
    	$integration->way=3;
    	$integration->create_time=time();
    	$integrationResult=$integration->save();
    	if($growResult&&$integrationResult){
    		$transaction->commit();
    		return 1;
    	}else{
    		$transaction->rollback();
    		return false;
    	}
    }
    /*4
     * @verson 邀请注册
     * @param int $customer_id
     * @return boolean
     */
    public function getValueByYaoQing($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $beginTime = strtotime($this->_startDay);
        $endTime = time();
        $n=$this->getYaoQingNum($customer_id);
        if($n){
            $value=$n*1;
            $result=$this->insertGrowValue($customer_id, $value, 4);
            $result2=$this->insertIntegrationValue($customer_id, $value, 4);
            $sqlUpdate="update invite set is_send=1 where customer_id=".$customer_id." and create_time>=".$beginTime." and create_time<= " .$endTime. " and is_send=0 and `status`=1 and effective=1";
            $m=Yii::app()->db->createCommand($sqlUpdate)->execute();
            if($n==$m){
                $result3=true;
            }else{
                $result3=false;
            }
            if($result && $result2 && $m && $result3){
                $transaction->commit();
                return $n;
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
     * @return boolean
     */
    public function getValueByXingChenOrder($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $orderArr=$this->getXingChenOrderTan($customer_id);
        if(!empty($orderArr)){
            $updateArr=array();
            foreach ($orderArr as $order){
                $updateArr[]=$order->id;
            }
            $updateStr=implode(",", $updateArr);
            $updateSql="update third_fees set is_send=1 where model='thirdFrees111' and customer_id=".$customer_id." and id in (".$updateStr.")";
            $res = Yii::app()->db->createCommand($updateSql)->execute();
            $value=$res*2;
            $res2=$this->insertGrowValue($customer_id,$value,5);
            $res3=$this->insertIntegrationValue($customer_id,$value,5);
            if($res && $res2 && $res3){
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
    /*5-2
     * @verson 下单（太平洋保险）
     * @param int $customer_id
     * @return boolean
     */
    public function getValueByTaiPingYangOrder($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $orderArr=$this->getTaiPingYangOrderTan($customer_id);
        if(!empty($orderArr)){
            $updateArr=array();
            foreach ($orderArr as $order){
                $updateArr[]=$order->id;
            }
            $updateStr=implode(",", $updateArr);
            $updateSql="update third_fees set is_send=1 where model='thirdFrees119' and customer_id=".$customer_id." and id in (".$updateStr.")";
            $res = Yii::app()->db->createCommand($updateSql)->execute();
            $value=$res*2;
            $res2=$this->insertGrowValue($customer_id,$value,5);
            $res3=$this->insertIntegrationValue($customer_id,$value,5);
            if($res && $res2 && $res3){
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
    /*5-3
     * @verson 下单（E家政）
     * @param int $customer_id
     * @return boolean
     */
    public function getValueByEJiaZhengOrder($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $orderArr=$this->getEJiaZhengOrderTan($customer_id);
        if(!empty($orderArr)){
            $updateArr=array();
            foreach ($orderArr as $order){
                $updateArr[]=$order->id;
            }
            $updateStr=implode(",", $updateArr);
            $updateSql="update third_fees set is_send=1 where model='thirdFrees103' and customer_id=".$customer_id." and id in (".$updateStr.")";
            $res = Yii::app()->db->createCommand($updateSql)->execute();
            $value=$res*2;
            $res2=$this->insertGrowValue($customer_id,$value,5);
            $res3=$this->insertIntegrationValue($customer_id,$value,5);
            if($res && $res2 && $res3){
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
    /*6
     * @verson 彩富人生用户自动获取10成长值,每次用户进入页面都会进行验证
     * @param int $customer_id
     * @return boolean
     */
    public function getValueByCaiFu($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $result=$this->insertGrowValue($customer_id, 10, 6);
        $result2=$this->insertIntegrationValue($customer_id, 10, 6);
        $result3=  TreeTwoSeed::model()->updateAll(array('times' =>new CDbExpression('times+1')),"customer_id=".$customer_id);
        if($result && $result2 && $result3){
            $transaction->commit();
            return true;
        }else{
            $transaction->rollback();
            return false;
        }
    }
    /*7
     * @verson 抽奖
     * @param int $customer_id
     * @param int $type 1、20积分；2、50积分；3、100积分
     * @return boolean
     */
    public function getValueByChouJiang($customer_id,$type){
        if(empty($customer_id) || empty($type)){
            return false;
        }
        
        $prizeArr=$this->checkAll($type);
        foreach ($prizeArr[$type] as $key => $val) {
            $arr[$val['id']] = $val['v']; 
        }
        $rid = $this->get_rand($arr); //根据概率获取奖项id
        $list=array();
        if($type==1){
            if($rid==1){
                $transaction = Yii::app()->db->beginTransaction();
                $value=5;
                $result=$this->insertGrowValue($customer_id, $value, 7);
                $result2=$this->insertIntegrationValue($customer_id, $value, 7);
                $result3=$this->insertIntegrationValue($customer_id, -20, 8);//扣除积分
                $result4=$this->insertPrize($customer_id, 1, $this->prizeArr[$type][$rid-1]['prize_name'], 20);
                if($result && $result2 && $result3 && $result4){
                    $transaction->commit();
                    return $list=array('type'=>1,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==2){
                $transaction = Yii::app()->db->beginTransaction();
                $value=5;
                $result=$this->insertExperienceValue($customer_id, $value, 3);
                $result2=$this->insertIntegrationValue($customer_id, -20, 8);//扣除积分
                $result3=$this->insertPrize($customer_id, 2, $this->prizeArr[$type][$rid-1]['prize_name'], 20);
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>1,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==3){
                $transaction = Yii::app()->db->beginTransaction();
                $items = array(
    				'customer_id' => $customer_id,//用户的ID
    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
    				'sum' =>$this->prizeArr[$type][$rid-1]['amount'],//红包金额,
    				'sn' => 16,
                );
                $redPacked = new RedPacket();
                $result=$redPacked->addRedPacker($items);
                $result2=$this->insertIntegrationValue($customer_id, -20, 8);//扣除积分
                $result3=$this->insertPrize($customer_id, 3, $this->prizeArr[$type][$rid-1]['prize_name'], 20);
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>1,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==4){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -20, 8);//扣除积分
                $result2=$this->insertPrize($customer_id, 4, $this->prizeArr[$type][$rid-1]['prize_name'], 20);
                if($result && $result2){
                    $transaction->commit();
                    return $list=array('type'=>1,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==5){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -20, 8);//扣除积分
                $result2=$this->insertPrize($customer_id, 5, $this->prizeArr[$type][$rid-1]['prize_name'], 20);
                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieTwo');
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>1,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==6){
                $result=$this->insertIntegrationValue($customer_id, -20, 8);//扣除积分
                if($result){
                    return $list=array('type'=>1,'rid'=>$rid);
                }else{
                    return false;
                }
            }
        }elseif ($type==2) {
            if($rid==1){
                $transaction = Yii::app()->db->beginTransaction();
                $value=10;
                $result=$this->insertGrowValue($customer_id, $value, 7);
                $result2=$this->insertIntegrationValue($customer_id, $value, 7);
                $result3=$this->insertIntegrationValue($customer_id, -50, 8);//扣除积分
                $result4=$this->insertPrize($customer_id, 7, $this->prizeArr[$type][$rid-1]['prize_name'], 50);
                if($result && $result2 && $result3 && $result4){
                    $transaction->commit();
                    return $list=array('type'=>2,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==2){
                $transaction = Yii::app()->db->beginTransaction();
                $value=10;
                $result=$this->insertExperienceValue($customer_id, $value, 3);
                $result2=$this->insertIntegrationValue($customer_id, -50, 8);//扣除积分
                $result3=$this->insertPrize($customer_id, 8, $this->prizeArr[$type][$rid-1]['prize_name'], 50);
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>2,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==3){
                $transaction = Yii::app()->db->beginTransaction();
                $items = array(
    				'customer_id' => $customer_id,//用户的ID
    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
    				'sum' =>$this->prizeArr[$type][$rid-1]['amount'],//红包金额,
    				'sn' => 16,
                );
                $redPacked = new RedPacket();
                $result=$redPacked->addRedPacker($items);
                $result2=$this->insertIntegrationValue($customer_id, -50, 8);//扣除积分
                $result3=$this->insertPrize($customer_id, 9, $this->prizeArr[$type][$rid-1]['prize_name'], 50);
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>2,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==4){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -50, 8);//扣除积分
                $result2=$this->insertPrize($customer_id, 10, $this->prizeArr[$type][$rid-1]['prize_name'], 50);
                if($result && $result2){
                    $transaction->commit();
                    return $list=array('type'=>2,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==5){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -50, 8);//扣除积分
                $result2=$this->insertPrize($customer_id, 11, $this->prizeArr[$type][$rid-1]['prize_name'], 50);
                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieTwo');
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>2,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==6){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -50, 8);//扣除积分
                $result2=$this->insertPrize($customer_id, 12, $this->prizeArr[$type][$rid-1]['prize_name'], 50);
                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieTwo');
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>2,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }
            
        }elseif ($type==3) {
            if($rid==1){
                $transaction = Yii::app()->db->beginTransaction();
                $value=50;
                $result=$this->insertGrowValue($customer_id, $value, 7);
                $result2=$this->insertIntegrationValue($customer_id, $value, 7);
                $result3=$this->insertIntegrationValue($customer_id, -100, 8);//扣除积分
                $result4=$this->insertPrize($customer_id, 13, $this->prizeArr[$type][$rid-1]['prize_name'], 100);
                if($result && $result2 && $result3 && $result4){
                    $transaction->commit();
                    return $list=array('type'=>3,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==2){
                $transaction = Yii::app()->db->beginTransaction();
                $value=50;
                $result=$this->insertExperienceValue($customer_id, $value, 3);
                $result2=$this->insertIntegrationValue($customer_id, -100, 8);//扣除积分
                $result3=$this->insertPrize($customer_id, 14, $this->prizeArr[$type][$rid-1]['prize_name'], 100);
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>3,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==3){
                $transaction = Yii::app()->db->beginTransaction();
                $items = array(
    				'customer_id' => $customer_id,//用户的ID
    				'from_type' => Item::RED_PACKET_FROM_TYPE_LOTTERY,
    				'sum' =>$this->prizeArr[$type][$rid-1]['amount'],//红包金额,
    				'sn' => 16,
                );
                $redPacked = new RedPacket();
                $result=$redPacked->addRedPacker($items);
                $result2=$this->insertIntegrationValue($customer_id, -100, 8);//扣除积分
                $result3=$this->insertPrize($customer_id, 15, $this->prizeArr[$type][$rid-1]['prize_name'], 100);
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>3,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==4){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -100, 8);//扣除积分
                $result2=$this->insertPrize($customer_id, 16, $this->prizeArr[$type][$rid-1]['prize_name'], 100);
                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieTwo');
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>3,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==5){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -100, 8);//扣除积分
                $result2=$this->insertPrize($customer_id, 17, $this->prizeArr[$type][$rid-1]['prize_name'], 100);
                $result3 = OneYuanBuy::sendCode($customer_id, $this->prizeArr[$type][$rid-1]['goods_id'], 0, $type = 'ZhiShuJieTwo');
                if($result && $result2 && $result3){
                    $transaction->commit();
                    return $list=array('type'=>3,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }elseif($rid==6){
                $transaction = Yii::app()->db->beginTransaction();
                $result=$this->insertIntegrationValue($customer_id, -100, 8);//扣除积分
                $result2=$this->insertPrize($customer_id, 18, $this->prizeArr[$type][$rid-1]['prize_name'], 100);
                if($result && $result2){
                    $transaction->commit();
                    return $list=array('type'=>3,'rid'=>$rid);
                }else{
                    $transaction->rollback();
                    return false;
                }
            }
        }else{
            return false;
        }
    }
    /*
     * @version 判断是否今天已经放送一把或者超过了总数量15把
     * @param int $num
     * @param int $type 获取类型
     * return booleean
     */
    private function checkAll($type){
        if(empty($type)){
            return false;
        }
        if($type==1){
            $n3=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=unix_timestamp(curdate()) and create_time<unix_timestamp(date_sub(curdate(),interval -1 day))',array(':prize_id'=>3));
            $n4=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=unix_timestamp(curdate()) and create_time<unix_timestamp(date_sub(curdate(),interval -1 day))',array(':prize_id'=>4));
            $n5=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=unix_timestamp(curdate()) and create_time<unix_timestamp(date_sub(curdate(),interval -1 day))',array(':prize_id'=>5));
            if($n3>=30){
                 unset($this->prizeArr[$type][2]);
            }
            if($n4>=1){
                 unset($this->prizeArr[$type][3]);
            }
            if($n5>=1){
                 unset($this->prizeArr[$type][4]);
            }
            unset($this->prizeArr[$type][3]);
            
        }elseif($type==2){
            $n9=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=unix_timestamp(curdate()) and create_time<unix_timestamp(date_sub(curdate(),interval -1 day))',array(':prize_id'=>9));
            $n10=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=unix_timestamp(curdate()) and create_time<unix_timestamp(date_sub(curdate(),interval -1 day))',array(':prize_id'=>10));
            $n11=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>11,':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
            $n12=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>12,':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
            if($n9>=10){
                 unset($this->prizeArr[$type][2]);
            }
            if($n10>=1){
                 unset($this->prizeArr[$type][3]);
            }
            if($n11>=10){
                 unset($this->prizeArr[$type][4]);
            }
            if($n12>=10){
                 unset($this->prizeArr[$type][5]);
            }
            unset($this->prizeArr[$type][3]);
        }elseif($type==3){
            $n15=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=unix_timestamp(curdate()) and create_time<unix_timestamp(date_sub(curdate(),interval -1 day))',array(':prize_id'=>15));
            $n16=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>16,':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
            $n17=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>17,':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
            $n18=TreeTwoPrize::model()->count('prize_id=:prize_id and create_time>=:startDay and create_time<:endDay',array(':prize_id'=>18,':startDay'=>strtotime($this->_startDay),':endDay'=>strtotime($this->_endDay)));
            if($n15>=15){
                 unset($this->prizeArr[$type][2]);
            }
            if($n16>=5){
                 unset($this->prizeArr[$type][3]);
            }
            if($n17>=3){
                 unset($this->prizeArr[$type][4]);
            }
            if($n18>=1){
                 unset($this->prizeArr[$type][5]);
            }
            
        }
        return $this->prizeArr;
    }
    /*
     * @version 插入成长值
     * @param int $customer_id
     * @param int $value
     * @param int $way
     * return array/boolean
     */
    private function insertGrowValue($customer_id,$value,$way){
        if(empty($customer_id) || empty($value) || empty($way)){
            return false;
        }
        $seed_id=$this->getSeedId($customer_id, 1);
        $TreeGrowModel=new TreeTwoGrow();
        $TreeGrowModel->seed_id=$seed_id;
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
    /*
     * @version 插入积分
     * @param int $customer_id
     * @param int $value
     * @param int $way
     * return array/boolean
     */
    private function insertIntegrationValue($customer_id,$value,$way){
        if(empty($customer_id) || empty($value) || empty($way)){
            return false;
        }
        $seed_id=$this->getSeedId($customer_id, 1);
        $TreeIntegrationModel=new TreeTwoIntegration();
        $TreeIntegrationModel->seed_id=$seed_id;
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
     * @param int $prize_id(1~18)
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
     * @version 获取种子id
     * @param int $customer_id
     * @param int $way
     */
    public function getSeedId($customer_id,$way){
        if(empty($customer_id) || empty($way)){
            return false;
        }
        $treeArr=TreeTwoSeed::model()->find('customer_id=:customer_id and way=:way',array(':customer_id'=>$customer_id,':way'=>1));
        if(!empty($treeArr)){
            return $treeArr->id;
        }else{
            return false;
        }
    }
    /*
     * @version 获取总的成长值
     * @param int $customer_id
     * return int 总的成长值
     */
    public function getGrowValue($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $sql="select sum(value) as summary from tree_two_grow where customer_id=".$customer_id;
        $growArr =Yii::app()->db->createCommand($sql)->queryAll();
        $valueTotal=$growArr[0]['summary'];
        if(empty($valueTotal)){
            return 0;
        }else{
            return $valueTotal;
        }
    }
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
     * @version 获取邀请人数
     * @param int $customer_id
     * return int 人数
     */
    public function getYaoQingNum($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime = strtotime($this->_startDay);
        $endTime = time();
        $n=Invite::model()->count('customer_id=:customer_id and create_time>=:beginTime and create_time<= :endTime and is_send=0 and `status`=1 and effective=1',array(':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime));
        return $n;
    }
    /*
     * @version 判断是否有星辰旅游的订单
     * @param int $customer_id
     * return boolean/array
     */
    public function getXingChenOrderTan($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime=strtotime($this->_startDay);
        $endTime=strtotime($this->_endDay);
        $xingChenOrderArr=  ThirdFees::model()->findAll('model=:model and customer_id=:customer_id and (status=1 or status=99) and create_time>=:beginTime and create_time<= :endTime and is_send=0 and update_time>=:beginTime and update_time<= :endTime', array(':model'=>'thirdFrees111',':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime));
        if(!empty($xingChenOrderArr)){
            return $xingChenOrderArr;
        }else{
            return false;
        }
    }
    /*
     * @version 判断是否有太平洋保险的订单
     * @param int $customer_id
     * return boolean/array
     */
    public function getTaiPingYangOrderTan($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime=strtotime($this->_startDay);
        $endTime=strtotime($this->_endDay);
        $taiPingYangOrderArr=  ThirdFees::model()->findAll('model=:model and customer_id=:customer_id and (status=1 or status=99) and create_time>=:beginTime and create_time<= :endTime and is_send=0 and update_time>=:beginTime and update_time<= :endTime', array(':model'=>'thirdFrees119',':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime));
        if(!empty($taiPingYangOrderArr)){
            return $taiPingYangOrderArr;
        }else{
            return false;
        }
    }
    /*
     * @version 判断是否有E家政的订单
     * @param int $customer_id
     * return boolean/array
     */
    public function getEJiaZhengOrderTan($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $beginTime=strtotime($this->_startDay);
        $endTime=strtotime($this->_endDay);
        $eJiaZhengOrderArr=  ThirdFees::model()->findAll('model=:model and customer_id=:customer_id and (status=1 or status=99) and create_time>=:beginTime and create_time<= :endTime and is_send=0 and update_time>=:beginTime and update_time<= :endTime', array(':model'=>'thirdFrees103',':customer_id'=>$customer_id,':beginTime'=>$beginTime,':endTime'=>$endTime));
        if(!empty($eJiaZhengOrderArr)){
            return $eJiaZhengOrderArr;
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
        $cusArr=Customer::model()->findByPk($customer_id);
        $sql="select mobile,prize_name from tree_two_prize limit 10";
        $jiangArr=Yii::app()->db->createCommand($sql)->queryAll();
        if(!empty($jiangArr)){
            return $jiangArr;
        }else{
            return false;
        }
    }

    /***************************************************************经验值*****************************************************************/
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
     * @version 摘果实获得经验值
     * @param int $customer_id
     * @return boolean
     */
    public function getExperienceValueByZhai($customer_id){
        if(empty($customer_id)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $result=$this->insertGrowValue($customer_id,-100,8);
        $result2=$this->insertExperienceValue($customer_id,50,2);
        if($result && $result2){
            $transaction->commit();
            return true;
        }else{
            $transaction->rollback();
            return false;
        }
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
        $seed_id=$this->getSeedId($customer_id, 1);
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
    *@version 获取成长值
    * @param int $seed_id
    * return int 总的成长值
    */
    public function getGrowValueBySeedID($seed_id){
    	if(empty($seed_id)){
    		return false;
    	}
    	$sql="select sum(value) as summary from tree_two_grow where seed_id=".$seed_id;
    	$growArr =Yii::app()->db->createCommand($sql)->queryAll();
    	$valueTotal=$growArr[0]['summary'];
    	return $valueTotal;
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
    /*
     * @version 记录分享
     * @param int $customer_id
     * @param string $open_id
     * @param int $type
     * return boolean
     */
    public function addShareLog($customer_id,$open_id,$type)
    {
        $shareLog =new TreeTwoLog();
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
            if($total<20){
                return false;
            }
        }elseif($type==2){
            if($total<50){
                return false;
            }
        }else{
            if($total<100){
                return false;
            }
        }
        return true;
    }
}


