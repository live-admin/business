<?php
/**
 * Created by PhpStorm.
 * User: chenql
 * Date: 2016/7/13
 * Time: 10:55
 */
class ServiceAgreementController extends  Controller{
    public function actionIndex(){
        $sn = Yii::app()->request->getParam('sn');
        //$sn='1040000160629113006732';
        Yii::import('common.services.ProfitService');
        $profit = new ProfitService();
        $info = $profit->serviceAgreement($sn);
        $this->renderPartial('index' , array('info'=>$info));
    }
}