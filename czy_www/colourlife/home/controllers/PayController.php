<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/6/29
 * Time: 16:46
 */
class PayController extends CController
{
    public function actionCallPay()
    {
        $sn = Yii::app()->request->getParam('sn');

        if (empty($sn))
            exit('参数错误');

        $order = ThirdFees::model()->find('sn=:sn', array(':sn' => $sn));
        if (!$order)
            exit('订单不存在');

        $this->renderPartial('/pay/callPay', array('sn' => $sn));
    }

    public function actionPayResult()
    {
        $sn = Yii::app()->request->getParam('sn');

        $order = ThirdFees::model()->find('sn=:sn', array(':sn' => $sn));
        if (!$order)
            exit('订单不存在');

        $result = array(
            'sn' => $order->sn,
            'amount' => $order->amount
        );

        $this->renderPartial('/pay/payResult', $result);
    }

    public function actionScanPay()
    {
        $businessId = Yii::app()->request->getParam('B');
//        if(strlen($businessId)<5)
//        {
//            $businessId = 'NEW'.$businessId;
//        }
        #TODO 获取商家信息

        $result = array(
            'businessId' => $businessId,
            'message' => ''
        );

        $this->renderPartial('/pay/scanPay', $result);
    }


    public function actionWeiPay(){
        $community_uuid = Yii::app()->request->getParam('uuid');
       // $community_uuid = "37919352-32b0-4636-8fd9-4bde386d8747";
        //获取小区信息
        $communityInfo = ICEService::getInstance()->dispatch(
            'community',
            array(
                'id' => $community_uuid,
            ),
            array(),
            'get'
        );
        $return['community_uuid'] = $community_uuid;
        $return['community_name'] = $communityInfo['name'];
        $return['community_address'] = $communityInfo['address'];
        $this->renderPartial('/pay/weiPay', $return);
    }
    
    /*
     * 微商圈临时扫码支付
     */
    public function actionCreateWeiPay(){
        header("Access-Control-Allow-Origin:*");
        $amount = Yii::app()->request->getParam('amount');
        $sn = $this->makeSn();
        $cid =77; //商户号是微商圈扫码
        $community_uuid = Yii::app()->request->getParam('community_uuid');
        $url = Yii::app()->request->hostInfo.CHtml::normalizeUrl(array('/pay/success'));
        $customer_id = Yii::app()->request->getParam('userId');
        
        $queryParam = [
            'mobile' => $customer_id,
        ];
        $customer_info = ICEService::getInstance()->dispatch(
            '/czyprovide/customer/getinfo',
            $queryParam,
            [],
            'GET'
        );
        $community = [
            'id'=> $community_uuid,
        ];
        $community_info = ICEService::getInstance()->dispatch(
            '/community',
            $community,
            [],
            'GET'
        );
        if($community_info){
            $community_name = $community_info['name'];
//            $vdef2 = $community_info['vdef2'];
//            $vdef3 = $community_info['vdef3'];
        }else{
            $community_name = '';
//            $vdef2 = '';
//            $vdef3 = '';
        }
        if($customer_info){
            $mobile = $customer_info['mobile'];
        }else{
            throw new Exception('账号不存在');
        }
        $data = [
            'mobile'=>$mobile,
            'amount'=>$amount,
            'cSn'=>$sn,
            'cid'=>$cid,
            'community_uuid'=>$community_uuid,
            'callbackUrl'=>$url,
         //   'pay_info'=>$vdef2.','.$vdef3.','.$community_name
            'pay_info'=>$community_name
        ];

        $result = ICEService::getInstance()->dispatch(
            'czyprovide/thirdFees/getOrder',
            $data,
            array(),
            'get'
        );
        if($result['status']=='ok'){
            $order_sn = $result['sn'];
        }else{
            return $result;
        }
        $this->renderPartial('/pay/payFromHtml',array('sn' => $order_sn,'url'=>$url));
    }

    public function makeSn(){
        $sn = md5("pay".time().rand(0,9999).rand(0,9999).rand(0,9999).rand(0,9999));
        return $sn;
    }

    //红包支付直接成功
    public function actionSuccess($sn)
    {
        $model = SN::findContentBySN($sn);
        //如果不为未支付直接跳转到首页
//        if($model->status!=Item::ORDER_AWAITING_PAYMENT)
//            $this->redirect('/goods');
        if($model->status==Item::ORDER_AWAITING_PAYMENT){
            $this->render('payFailed');
        }else{
            $this->render('paySuccess', array('model' => $model));
        }
//        $this->render('success', array('model' => $model));
    }

    public function actionView($id)
    {
        $id = intval($id);
        $this->pageTitle = "订单详情";
        if (empty($id)) {
            throw new CHttpException('404', '找不到请求的内容！');
        } else {
            $model = Order::model()->findByPk($id);
            if (empty($model) || $model->buyer_id != Yii::app()->user->id) {
                throw new CHttpException('404', '找不到请求的内容！');
            }
            $this->render('view', array(
                'model' => $model,
            ));
        }
    }
    
}