<?php
/**
 * Created by PhpStorm.
 * User: 开门有礼
 * Date: 2016/10/9
 * Time: 16:48
 */

class OpenDoorController extends ActivityController{
    public $beginTime='2016-10-14 00:00:00';//活动开始时间
    public $endTime='2016-11-08 23:59:59';//活动结束时间
    public $secret = '@&OpenDoor*^%';
    public $layout = false;
    public function filters(){
        return array(
            'accessControl', // perform access control for CRUD operations
            'Validity',
            'signAuth - ShareWeb',
        );
    }
    public function accessRules(){
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array(),
                'users' => array('@'),
            ),
        );
    }


    /*
     *活动首页
     */
    public function actionIndex(){
        $data = $this->openLog();
        $this->renderPartial('/v2016/openDoor/index',array('data'=>$data));
    }


    /*
     *获取开门记录
     */
    private function openLog()
    {
        $color = $this->getUserId();
        $customer = Customer::model()->findByAttributes(array('id'=>$color, 'is_deleted'=>0, 'state'=>0));
        if (empty($customer)){
        	throw new CHttpException(400, "用户不存在");
        }
        $mobile = $customer['mobile'];

        //$color = '1511998';
        $param = array(
            array('v' => 'start', 'must' => true),
            array('v' => 'end', 'must' => true)
        );
        $_GET['start'] = '2016-10-14 00:00:00';
        $_GET['end'] = '2016-11-08 23:59:59';
        $preFun = 'openlog/continuousDays';
        $resetUrl = 'http://colour.kakatool.cn/color/v1/openlog/continuousDays';
        $result = json_decode($this->getRemoteData($preFun, $param, $resetUrl = null, $color), true);
        if (empty($result)){
        	throw new CHttpException(400, "亲，网络不给力~~");
        }
        //dump($result);
        if($result['days']<5){
            $result['trigger'] = 0;
        }else if($result['days']>4 && $result['days']<10){
            $code = 100000109;
            $is_send = UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile ', array(':you_hui_quan_id'=>$code, ':mobile'=>$mobile));
            if($is_send)
                $result['trigger'] = 0;
        }else if($result['days']>9 && $result['days']<15){
            $is_send = RedPacketCarry::model()->find('note=:note and receiver_id=:receiver_id', array(':note'=>'2016年开门有礼活动1元', ':receiver_id'=>$color));
            if($is_send)
                $result['trigger'] = 0;
        }else if($result['days']>14 && $result['days']<20){
            $code = 100000110;
            $is_send = UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile ', array(':you_hui_quan_id'=>$code, ':mobile'=>$mobile));
            if($is_send)
                $result['trigger'] = 0;
        }else if($result['days']>19){
            $is_send = RedPacketCarry::model()->find('note=:note and receiver_id=:receiver_id', array(':note'=>'2016年开门有礼活动2元', ':receiver_id'=>$color));
            if($is_send)
                $result['trigger'] = 0;
        }else{
            $result['trigger'] = 1;
        }
        return $result;
    }

    private function getRemoteData($preFun = null, $param = null, $resetUrl = null, $color= null ){
        $re = new ConnectWetown();
        return $re->getRemoteData($preFun, $param, $resetUrl,$isGetParamValue = true, $color);
    }

    /*
     * 开门有礼活动奖品
    */
    public function actionOpenDoor(){
        $colorId = $this->getUserId();
        if (empty($colorId))
            throw new CHttpException(400, '彩之云用户id不能为空');
        $customer = Customer::model()->findByAttributes(array('id'=>$colorId, 'is_deleted'=>0, 'state'=>0));
        if(!$customer)
            throw new CHttpException(400, '彩之云用户不存在');

        $type = Yii::app()->request->getParam('type');
        if (empty($type)) throw new CHttpException(400, '类型不能为空');

        $mobile = $customer['mobile'];

        if($type == 'caitegong5yuan'){
            $code = 100000109;
            $is_send = UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile ', array(':you_hui_quan_id'=>$code, ':mobile'=>$mobile));
            if(!$is_send){
                $model = new UserCoupons();
                $model->mobile = $mobile;
                $model->you_hui_quan_id = $code;
                $model->is_use = 0;
                $model->create_time = time();
                $model->num = 0;
                $result = $model->save();
                if($result){
                    $array = array('status'=>1,'message'=>'优惠券发放成功！');
                    echo json_encode($array);
                    exit;
                }else{
                    Yii::log('2016年开门有礼活动优惠券发放失败'.date('Y-m-d',time()).'用户id：'.$mobile.'发放失败！错误信息为：'.$model->getErrors(),CLogger::LEVEL_ERROR,'colourlife.core.2016DoorOpen');
                    $array = array('status'=>0,'message'=>'优惠券发放失败！');
                    echo json_encode($array);
                    exit;
                }
            }else{
                $array = array('status'=>0,'message'=>'该用户已发过该优惠券！');
                echo json_encode($array);
                exit;
            }
        }else if($type == '1yuanfanpiao'){
            $is_send = RedPacketCarry::model()->find('note=:note and receiver_id=:receiver_id', array(':note'=>'2016年开门有礼活动1元', ':receiver_id'=>$colorId));
            if($is_send){
                $array = array('status'=>0,'message'=>'该用户已发过该奖品！');
                echo json_encode($array);
                exit;
            }
            $amount = 1;
            $cmobile=20000000005;
            $cmobile_id = 2224375;
            $rebateResult=RedPacketCarry::model()->customerTransferAccounts($cmobile_id,$colorId,$amount,1,$cmobile,$note='2016年开门有礼活动1元');
            if ($rebateResult['status']==1){
                $array = array('status'=>1,'message'=>'奖品1发放成功！');
                echo json_encode($array);
                exit;
            }else {
                Yii::log('2016年开门有礼活动'.date('Y-m-d',time()).'用户id：'.$colorId.'发放失败！错误信息为：'.$rebateResult['msg'],CLogger::LEVEL_ERROR,'colourlife.core.2016DoorOpen');
                $array = array('status'=>0,'message'=>'奖品1发放失败！');
                echo json_encode($array);
                exit;
            }
        }else if($type == 'caitegong5yuan20'){
            $code = 100000110;
            $is_send = UserCoupons::model()->find('you_hui_quan_id=:you_hui_quan_id and mobile=:mobile ', array(':you_hui_quan_id'=>$code, ':mobile'=>$mobile));
            if(!$is_send){
                $model = new UserCoupons();
                $model->mobile = $mobile;
                $model->you_hui_quan_id = $code;
                $model->is_use = 0;
                $model->create_time = time();
                $model->num = 0;
                $result = $model->save();
                if($result){
                    $array = array('status'=>1,'message'=>'优惠券发放成功！');
                    echo json_encode($array);
                    exit;
                }else{
                    Yii::log('2016年开门有礼活动优惠券发放失败'.date('Y-m-d',time()).'用户id：'.$mobile.'发放失败！错误信息为：'.$model->getErrors(),CLogger::LEVEL_ERROR,'colourlife.core.2016DoorOpen');
                    $array = array('status'=>0,'message'=>'优惠券发放失败！');
                    echo json_encode($array);
                    exit;
                }
            }else{
                $array = array('status'=>0,'message'=>'该用户已发过该优惠券！');
                echo json_encode($array);
                exit;
            }
        }else if($type == '2yuanfanpiao'){
            $is_send = RedPacketCarry::model()->find('note=:note and receiver_id=:receiver_id', array(':note'=>'2016年开门有礼活动2元', ':receiver_id'=>$colorId));
            if($is_send){
                $array = array('status'=>0,'message'=>'该用户已发过该奖品！');
                echo json_encode($array);
                exit;
            }
            $amount = 2;
            $cmobile=20000000005;
            $cmobile_id = 2224375;
            $rebateResult=RedPacketCarry::model()->customerTransferAccounts($cmobile_id,$colorId,$amount,1,$cmobile,$note='2016年开门有礼活动2元');
            if ($rebateResult['status']==1){
                $array = array('status'=>1,'message'=>'奖品2发放成功！');
                echo json_encode($array);
                exit;
            }else {
                Yii::log('2016年开门有礼活动'.date('Y-m-d',time()).'用户id：'.$colorId.'发放失败！错误信息为：'.$rebateResult['msg'],CLogger::LEVEL_ERROR,'colourlife.core.2016DoorOpen');
                $array = array('status'=>0,'message'=>'奖品2发放失败！');
                echo json_encode($array);
                exit;
            }
        }else{
            $array = array('status'=>4,'message'=>'输入有误！');
            echo json_encode($array);
            exit;
        }

    }

}