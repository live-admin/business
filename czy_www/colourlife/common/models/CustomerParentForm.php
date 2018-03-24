<?php

class CustomerParentForm extends CFormModel
{
    private $startTime = Item::INVITE_REGISTER_START_TIME;       //活动开始时间
    private $endTime = Item::INVITE_REGISTER_END_TIME;          //活动结束时间
    /**
     * 增加积分
     * @param string $mobile
     * @param Object $customer
     **/
    public function PlusCredit($mobile, $customer)
    {
        $customer->changeCredit('customer_register'); //注册加积分

        $invite = Invite::model()->find('mobile=:mobile  and create_time<=:create_time   and valid_time>=:valid_time ',
            array(':mobile' => $mobile, ':create_time' => time(), ':valid_time' => time()));

        if (!empty($invite)) {
            $invite->status = 1;
            $invite->save();
            $initModel=$invite->model;		//该字段在保存时，多了空格，会造成下面的if进不去
            if (trim($initModel) == "customer") {
                $inviter_model = Customer::model()->findByPk($invite->customer_id);
                $inviter_model->changeCredit('invite'); //邀请人加积分
                $reger = Customer::model()->findByPk($customer->id);
                $reger->invite_code = $inviter_model->customer_code;
                $reger->save();
                //===start 邀请注册，送抽奖机会
                //彩之云app活动
                //$luckyOper->execute($paramin);
                LuckyDoAdd::invite($inviter_model->id, $inviter_model->username);
                //===end 邀请注册，送抽奖机会
                //获取同个设备ID注册的个数(小于等于1，是有效的)
                $custModel = Customer::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
                if($custModel){
                    $reg_identityCount = Customer::model()->count('reg_identity=:reg_identity and reg_type=:reg_type',array(':reg_identity'=>$custModel->reg_identity,':reg_type'=>intval($custModel->reg_type)));
                    if(($custModel->reg_identity == 0) || ($reg_identityCount <= 5)){
                        if($inviter_model->state == 0){
                            if(date('Y-m-d H:i:s',$invite->create_time)>$this->endTime||date('Y-m-d H:i:s',$invite->create_time)<$this->startTime||intval(date('H',$invite->create_time))>21||intval(date('H',$invite->create_time))<9||$custModel->reg_type==0){
                                $invite->effective = 0;
                            }else{
                                $invite->effective = 1;
                            }
                            $invite->save();
                        }
                    }
                }
            }
        }
    }



    public function PlusCreditNew($mobile,$customer,$inviter)
    {
        $customer->changeCredit('customer_register'); //注册加积分
        $invite = Invite::model()->find('customer_id=:customer_id and mobile=:mobile  and create_time<=:create_time  and valid_time>=:valid_time ',
            array(':customer_id' => $inviter, ':mobile' => $mobile, ':create_time' => time(), ':valid_time' => time()));

        if (!empty($invite)) {//手机邀请
            $invite->status = 1;
            $invite->save();
            $initModel=$invite->model;      //该字段在保存时，多了空格，会造成下面的if进不去
            if (trim($initModel) == "customer") {
                $customer = Customer::model()->findByPk($invite->customer_id);
                $customer->changeCredit('invite'); //邀请人加积分
                //===start 邀请注册，送抽奖机会
                //彩之云app活动
                //$luckyOper->execute($paramin);
                LuckyDoAdd::invite($customer->id, $customer->username);
                //===end 邀请注册，送抽奖机会
                //获取同个设备ID注册的个数(小于等于1，是有效的)
                $custModel = Customer::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
                if($custModel){
                    $reg_identityCount = Customer::model()->count('reg_identity=:reg_identity and reg_type=:reg_type',array(':reg_identity'=>$custModel->reg_identity,':reg_type'=>$custModel->reg_type));
                    if(($custModel->reg_identity == 0) || ($reg_identityCount <= 5)){
                        if($customer->state == 0){
                            if(date('Y-m-d H:i:s',$invite->create_time)>$this->endTime||date('Y-m-d H:i:s',$invite->create_time)<$this->startTime||intval(date('H',$invite->create_time))>21||intval(date('H',$invite->create_time))<9||$custModel->reg_type==0){
                                $invite->effective = 0;
                            }else{
                                $invite->effective = 1;
                            }
                            $invite->save();
                        }
                    }
                }
            }
        }else{//分享
            //不管之前是谁邀请的，都改为无效，以注册时填写的邀请码人为准
            $invite_model = Invite::model()->find('mobile=:mobile  and create_time<=:create_time  and valid_time>=:valid_time and status=:status',
            array(':mobile' => $mobile, ':create_time' => time(), ':valid_time' => time(), ':status'=>0));
            if(!empty($invite_model)){
                $invite_model->status = 1;
                $invite_model->effective = 0;
                $invite_model->save();
            }
            $customer = Customer::model()->findByPk($inviter);
            if($customer){
                $model = new Invite();                                       
                $model->customer_id = $inviter;
                $model->model = 'customer';
                $model->mobile = $mobile;
                $model->create_time = time();
                $model->valid_time = time() + intval(Yii::app()->config->invite['validTime']);
                $model->status = 1;
                $model->is_send = 0;
                $customer->changeCredit('invite'); //邀请人加积分
                LuckyDoAdd::invite($customer->id, $customer->username);//送抽奖机会
                $custModel = Customer::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
                if($custModel){
                    $reg_identityCount = Customer::model()->count('reg_identity=:reg_identity and reg_type=:reg_type',array(':reg_identity'=>$custModel->reg_identity,':reg_type'=>$custModel->reg_type));
                    if(($custModel->reg_identity == 0) || ($reg_identityCount <= 5)){ ///获取同个设备ID注册的个数(小于等于1，是有效的)
                        if($customer->state == 0){  //邀请人没有被禁用
                            if(date('Y-m-d H:i:s',$custModel->create_time)>$this->endTime||date('Y-m-d H:i:s',$custModel->create_time)<$this->startTime||intval(date('H',$custModel->create_time))>21||intval(date('H',$custModel->create_time))<9||$custModel->reg_type==0){
                                $model->effective = 0;
                            }else{
                                $model->effective = 1;
                            }
                        }else{
                            $model->effective = 0;
                        }
                    }else{
                        $model->effective = 0;
                    }
                }
                $model->save();                
            }        

        }



    }




}
