<?php

class EnterRedController extends CController{
    
    public function actionIndex(){
        $this->checkLogin();
    }
    private function checkLogin(){
        $mobile = Yii::app()->request->getParam('mobile');
        $reurl = Yii::app()->request->getParam('reurl');
        $community_id = Yii::app()->request->getParam('community_id');
        $community_id=intval($community_id);
        $mobileArr=Customer::model()->find('is_deleted=0 and state=0 and status=0 and mobile=:mobile',array(':mobile'=>$mobile));
        if (empty($mobileArr) || empty($reurl) || empty($community_id)) {
            exit('<h1>用户信息错误，请重新登录</h1>');
        }
        $re=new SetableSmallLoans();
        $zufang = $re->searchByIdAndType('MIANYONGZUFANG',1,$mobileArr->id,false);
        $zhuangxiu = $re->searchByIdAndType('EZHUANGXIU',1,$mobileArr->id,false);
        $daikuan= $re->searchByIdAndType('EDAIKUAN',1,$mobileArr->id,false);
        $this->renderPartial("choujiang",array('mobile'=>$mobile,'reurl'=>$reurl,'community_id'=>$community_id,'zhuangxiu_url'=>$zhuangxiu->completeURL,'daikuan_url'=>$daikuan->completeURL,'zufang_url'=>$zufang->completeURL));
    }
    public function actionChange(){
        $mobile = Yii::app()->request->getParam('mobile');
        $mobileArr=Customer::model()->find('is_deleted=0 and state=0 and status=0 and mobile=:mobile',array(':mobile'=>$mobile));
        $oneyuan=new OneYuanBuy();
        $res=$oneyuan::getMyCode($mobileArr->id,2);
        $re=new SetableSmallLoans();
        $oneyuan_url = $re->searchByIdAndType('oneyuan',1,$mobileArr->id,false);
        $this->renderPartial("change",array('res'=>$res,'oneyuan_url'=>$oneyuan_url->completeURL));
    }
    public function actionCheng(){
        $mobile = Yii::app()->request->getParam('mobile');
        $mobileArr=Customer::model()->find('is_deleted=0 and state=0 and status=0 and mobile=:mobile',array(':mobile'=>$mobile));
        $re=new SetableSmallLoans();
        $zufang = $re->searchByIdAndType('MIANYONGZUFANG',1,$mobileArr->id,false);
        $zhuangxiu = $re->searchByIdAndType('EZHUANGXIU',1,$mobileArr->id,false);
        $daikuan= $re->searchByIdAndType('EDAIKUAN',1,$mobileArr->id,false);
        $this->renderPartial("chenggong",array('mobile'=>$mobile,'zhuangxiu_url'=>$zhuangxiu->completeURL,'daikuan_url'=>$daikuan->completeURL,'zufang_url'=>$zufang->completeURL));
    }
    public function actionCheckRedPack(){
        $ret=array("success"=>0,"msg"=>'饭票领取失败');
        $mobile=$_POST['mobile'];
        //$community_id=$_POST['community_id'];
        $community_id=intval($_POST['community_id']);
        if(!empty($mobile)){
            $mobileArr=Customer::model()->find('is_deleted=0 and status=0 and mobile=:mobile',array(':mobile'=>$mobile));
            $sql="select count(*) from red_packet where customer_id=".$mobileArr->id.' and from_type=36';
            $count = Yii::app()->db->createCommand($sql)->queryScalar();
            if($count==0){
                $q="select sum(num) as total  from red_package_config where community_id=".$community_id;
                $totalArr=Yii::app()->db->createCommand($q)->queryAll();
                if($totalArr[0]['total']!=0){
                    $create_time=time();
                    $sql2="select * from red_package_config where community_id=".$community_id;
                    $resArr=Yii::app()->db->createCommand($sql2)->queryAll();
                    if(is_array($resArr)){
                        foreach ($resArr as $key=>$v){
                            if($v['num']>0){
                                $keyArr[$key]=$v['id'];  
                            }
                        }
                        $rand=array_rand(array_flip($keyArr));
                        $id=$rand;
                        if(!empty($id)){
                            $transaction = Yii::app()->db->beginTransaction();
                            $sql3="select * from red_package_config where id=".$id;
                            $queryArr=Yii::app()->db->createCommand($sql3)->queryAll();
                            $sum=$queryArr[0]['amount'];
                            $sql4="update red_package_config set num=num-1 where id=".$id;
                            $execute4=Yii::app()->db->createCommand($sql4)->execute();
                            $items = array(
                                'customer_id' =>$mobileArr->id,
                                'from_type' => Item::RED_PACKET_FROM_TYPE_ERUHUO_LOTTERY,
                                'sum' => $sum,
                                'sn' => 'eandSource',
                                'remark'=>$community_id,
                            );
                            if($community_id==2008){
                                $cus_id=2214350;
                            }
                            if($community_id==2159){
                                $cus_id=2214974;
                            }
                            $cusArr=Customer::model()->findByPk($cus_id);
                            if($cusArr['balance']<$sum){
                                $ret=array("success"=>0,"msg"=>'账户余额不足');
                                echo json_encode($ret);
                                exit;
                            }
                            $sub_item = array(
                                'customer_id' => $cus_id,
                                'to_type' => Item::RED_PACKET_TO_TYPE_ERUHUO_LOTTERY,
                                'sum' =>$sum,                                              //红包金额,
                                'sn' => 'eandSource',
                                'remark'=>$community_id,
                            );
                            $redPacked = new RedPacket();
                            $execute5=$redPacked->addRedPacker($items);
                            $execute6 = $redPacked->consumeRedPacker($sub_item);  //扣饭票
                            if($execute4 && $execute5 && $execute6){
                                $transaction->commit();
                                $ret=array("success"=>1,"msg"=>'饭票领取成功',"amount"=>$sum);
                            }else{
                                $transaction->rollback();
                                $ret=array("success"=>0,"msg"=>'饭票领取失败');
                            }
                         }
                      }
                   }
                   else{
                       $ret=array("success"=>0,"msg"=>'没有饭票领取了');
                   }
            }else{
                $ret=array("success"=>0,"msg"=>'饭票已经领取');
            }
        }else{
            $ret=array("success"=>0,"msg"=>'饭票领取失败');
        }
        echo json_encode($ret);
    }

    public function actionSendCode(){
        $mobile=$_POST['mobile'];
        if(!empty($mobile)){
            $customer=Customer::model()->find('is_deleted=0 and state=0 and mobile=:mobile',array(':mobile'=>$mobile));
            if($customer){
                $oneyuan = OneYuanBuy::model()->find('is_send=:is_send and customer_id=:customer_id',array(':is_send'=>1,':customer_id'=>$customer->id));
                if(!$oneyuan){
                    $result=OneYuanBuy::sendCode($customer->id,1639,0);
                    if(!$result){
                        Yii::log('失败：用户id{$model->id}，注册到小区id{713}发放一元购码失败', CLogger::LEVEL_INFO,'colourlife.home.enterRed.sendCode');
                        echo json_encode(array('ok'=>0,'msg'=>'一元购码发放失败'));
                    }else{
                        Yii::log('成功：用户id{$model->id}，注册到小区id{713}发放一元购码成功', CLogger::LEVEL_INFO,'colourlife.home.enterRed.sendCode');
                        echo json_encode(array('ok'=>1));//成功发放
                    }
                }else{
                    echo json_encode(array('ok'=>0,'msg'=>'已经存在一元购买'));
                }
            }else{
                echo json_encode(array('ok'=>0,'msg'=>'用户信息错误'));
            }
        }else{
            echo json_encode(array('ok'=>0,'msg'=>'缺少手机号码'));
        }
    }


}

