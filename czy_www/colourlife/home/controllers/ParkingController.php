<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/4/20
 * Time: 15:01
 */
class ParkingController extends ApiController
{
    public $secret = 'PKLT!#%$3542mfjx';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'signAuth',
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

    public function actionNotify()
    {
        $sn = Yii::app()->request->getParam('sn');

        $otherFeeModel = OthersFees::model()->find('sn=:sn', array(':sn'=>$sn));
        if (!$otherFeeModel)
            $this->output('', 0, '订单不存在');

        Yii::import('common.services.ParkingService');
        $parkingService = new ParkingService();

        if ('ParkingFeesMonth' == $otherFeeModel->model) {
            $result = $parkingService->monthTransactionSuccess($otherFeeModel);
            if (false === $result)
                $this->output('', 0, $parkingService->getErrorMsg());
        }
        elseif ('ParkingFeesVisitor' == $otherFeeModel->model) {
            $result = $parkingService->visitorTransactionSuccess($otherFeeModel);
            if (false === $result)
                $this->output('', 0, $parkingService->getErrorMsg());
        }
        else {
            $this->output('', 0, '订单类型错误');
        }

        $this->output('', 0, '通知停车厂商返回成功');
    }
}