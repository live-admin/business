<?php
class DoubleCodeController extends CController
{

    public function actionScanCode()
    {
        $code = intval(Yii::app()->request->getParam('code'));
        $sign = Yii::app()->request->getParam('sign');
        if (empty($code) || empty($sign)){
            exit('参数错误');
        }
        $customerId=($code - 1778) / 778;
        $cusArr=Customer::model()->findByPk($customerId);
        $params['code'] = $code;
        $params['sign'] = $sign;
        $secret='dou*ble^co%de';
        $signObject = new Sign($secret);
		$result = $signObject->checkSign($params);
        if($result){
            $mobile=$cusArr['mobile'];
            $this->renderPartial('/doubleCode/scanCode', array('mobile'=>$mobile));
        }else{
            exit('签名错误');
        }
        
        
        
//        $cusArr=Customer::model()->findByPk($customerId);
//        $sql='select * from customer_code where customer_id='.$customerId;
//        $codeArr =Yii::app()->db->createCommand($sql)->queryAll();
//        if(empty($codeArr)){
//            exit('参数错误');
//        }
//        $codeUrl=$codeArr[0]['code_url'];
//        $parse = parse_url($codeUrl);
//        $paramsOther = empty($parse['query']) ? array() : $this->convertUrlQuery($parse['query']);
//        if($codeArr[0]['customer_id']==$customerId &&  $paramsOther['target']==$target){
//            $mobile=$cusArr['mobile'];
//            $this->renderPartial('/doubleCode/scanCode', array('mobile'=>$mobile));
//        }else{
//            exit('参数错误');
//        }
    }
    public function actionIndex(){
        $code = Yii::app()->request->getParam('code');
        $customer_id = ($code-1778)/778;
        $url = F::getMUrl('/home/register?customer_id=').$customer_id;
        //$url='http://mapp.colourlife.com/m.html';
        $this->redirect($url);
    }
    /**
	 * 将URL中的参数取出来放到数组里
	 * @param $query
	 * @return array
	 */
	private function convertUrlQuery($query)
	{
		$queryParts = explode('&', $query);
		$params = array();

		foreach ($queryParts as $param) {
			$item = explode('=', $param);
			$params[$item[0]] = $item[1];
		}

		return $params;
	}
    

}