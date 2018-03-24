<?php

class SiteController extends Controller
{

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    //根据地区id获取小区
    public function  actionCommunities($region_id)
    {
        $data = AjaxDataHelp::Communities(intval($region_id));
        $data = $this->getCommunityUrl($data);
        echo CJSON::encode($data);
    }
    
    //根据省id获取小区
    public function  actionCommunitiesByProvince($region_id)
    {
        $data = AjaxDataHelp::CommunitiesByProvince(intval($region_id));
        $data = $this->getCommunityUrl($data);
        echo CJSON::encode($data);
    }

    ///根据小区id获取楼栋
    public function actionBuilds($community_id)
    {
        $data = AjaxDataHelp::Builds(intval($community_id));

        echo CJSON::encode($data);
    }

    ///获取地区
    public function actionRegions($parent_id = 0)
    {
        $data = AjaxDataHelp::Regions(intval($parent_id));

        echo CJSON::encode($data);
    }

    //设置每个小区url
    private function getCommunityUrl($data)
    {
        foreach ($data as $a) {
            $a->domain = F::getFrontendUrl(strtolower($a->domain));
        }

        return $data;
    }


    /*public function actionSendMsg($mobile, $type = 0)
    {
        $result = $this->sendMsgs($mobile, $type);
        if ($result === true) {
            echo CJSON::encode(array('ok' => 1));
        } else
            echo CJSON::encode(array('ok' => $result));
    }*/

    ///发送短信
    /*private function sendMsgs($mobile, $type = 0)
    {
        //先检验是否可以发短信
        if($this->checkSendMsgs($mobile)){
            $model = new SmsForm();
            $model->setScenario($type == 1 ? 'resetPassword' : 'register');               
            $model->mobile = $mobile;
            if ($model->validate() && $model->send()) {
                Yii::log("发送验证码 '{$model->code}' 到手机号码 '{$model->mobile}'。", CLogger::LEVEL_INFO, 'colourlife.api.sms.POST');
                return true;
            } else{
                return $model->getError('mobile');
            }
        }else{
            return "您请求发送验证码过于频繁！";
        }
    }*/
    
    public function actionCheckMobile($mobile){
        if(empty($mobile)){
            echo CJSON::encode(array('result' => 0));
        }else{
            if(Customer::model()->enabled()->findByAttributes(array('mobile'=>$mobile))){
                echo CJSON::encode(array('result' => 0));
            }else{
                echo CJSON::encode(array('result' => 1));
            }
        }
    }

    /*public function actionReg()
    {
        $model = new CustomerForm('create');
        if (isset($_POST)) {
            $model->attributes = $_POST;
            if ($model->validate()) {
                $model->reg_type = 0;//标识为网站注册用户
                $result = $model->saveData();
                if ($result == 1) {
                    //注册成功 记录Cookie
                    CustomerUserAgent::createUserAgent($model->username);
                    
                    
                    echo CJSON::encode(array('ok' => 1));
                } else {
                    echo CJSON::encode(array('ok' => $result));
                }
            } else {
                echo CJSON::encode(array('ok' => $model->getError('username') .  $model->getError('mobile') . $model->getError('password') . $model->getError('repeatPwd')
                . $model->getError('code') . $model->getError('room')));
            }
        }

    }*/
    
    
    //验证发短信(如果是恶意攻击则不发)
    /*public function checkSendMsgs($mobile){
        //第一步检验该用户是否黑名单
        $blackModel = Blacklist::model()->find('mobile=:mobile and is_deleted = 0',array(':mobile' => $mobile));
        if($blackModel){
            return false;
        }else{
            //第二步检验当天该用户发送是否达到10次。
            $sql = "select count(*) as count from sms where mobile = '".$mobile ."' and FROM_UNIXTIME(create_time) like '".date("Y-m-d")."%'";
            $result = Yii::app()->db->createcommand($sql);
            $resArr = $result->queryAll();       
            if($resArr[0]['count'] <= 5){
                return true;
            }else{
                return false;
            }
        }
    }*/
    
    
}




