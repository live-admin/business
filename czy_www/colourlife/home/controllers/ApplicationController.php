<?php
class ApplicationController extends CController {
    public function actionIndex(){
        $key = isset($_GET['key'])?$_GET['key']:"";
        $customer_id = isset($_GET['customer_id'])?$_GET['customer_id']:"";
        if(empty($key)){
            throw new CHttpException(400, 'key不存在！');
            Yii::log("key参数丢失", CLogger::LEVEL_ERROR, 'colourlife.home.application');
        }
        if(empty($customer_id)){
            throw new CHttpException(400, '用户信息参数丢失，请重新登录');
            Yii::log("customer_id参数丢失", CLogger::LEVEL_ERROR, 'colourlife.home.application');
        }
//        $info = SmallLoans::model()->searchByIdAndType($key , SmallLoans::TYPE_MOBILE , $customer_id);
//        if($info)
//            header("Location: $info->completeURL");
//        else
            $this->renderPartial("index");     
    }
}
