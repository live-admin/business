<?php

class PayCompleteAction extends CAction
{
    public $code = '';
    public $name = '';

    public function run(){
        $input = var_export($_GET, true);
        Yii::log($this->name.'Return_url返回值：' . $input, CLogger::LEVEL_INFO, 'colourlife.core.payReturnUrl');

        $pay = PayFactory::getInstance($this->code);
        if ($pay->respond()) {
            $sn = $pay->getSn();
            $model = Pay::getPayModel($sn);
            $powerFees = OthersPowerFees::model()->find('pay_id=:pay_id', array(':pay_id' => $model->id));
            $recharge_code = "";          
            if($powerFees){
                $result = PowerFees::model()->findByPK($powerFees->object_id);
                if($result&&!empty($result->interface_order)&&!empty($result->recharge_code)){
                    $recharge_code = $result->recharge_code;
                }
            }        
            $this->controller->render('success',array('model'=>$model,'recharge_code'=>$recharge_code)); 
        }
    }
}