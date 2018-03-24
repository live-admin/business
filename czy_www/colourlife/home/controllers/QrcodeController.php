<?php
/**
 * Created by PhpStorm.
 * User: hzz
 * Date: 2017/8/23
 * Time: 20:40
 */

class QrcodeController extends CController{

    public function actionIndex(){
        $data['code_id'] = Yii::app()->request->getQuery("code");
        $data['access_token'] = Yii::app()->request->getQuery("access_token");
//        print_r($data);exit;
//        $data = json_decode($code);
        //		$result = ICEService::getInstance()->dispatch(
//			'qrcode/bind/list',
//			array(),
//			array(
//				'token' => $data['token'],
//				'code'	=>$data['code']
//			),
//			'post'
//		);
        if (defined('YII_DEBUG') && YII_DEBUG == true) {
            $queryUrl = 'http://qrcode-czytest.colourlife.com/qrcode/bind/list';;
        } else {
            $queryUrl = 'http://qrcode.colourlife.com/qrcode/bind/list';;
        }
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $queryUrl);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, TRUE);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $return = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //Yii::log('记录日志'.$data,CLogger::LEVEL_INFO, 'colourlife.core.OrderFrees.updateFeeOrder.lhqpay');
        $url = CJSON::decode($return);
        $this->renderPartial('index',['url'=>$url['content']['bindList']]);
    }

    /**
     * 旧版开门
     */
    public function actionDoor()
    {
        $user_id = Yii::app()->request->getParam('user_id');
        $qrcode = Yii::app()->request->getParam('qrcode');
        if($this->isNew($qrcode))
        {
            $re = new ConnectDoor();
        }else{
            $re = new ConnectWetown();
        }

        $param =
            [
                [
                    'v' => 'qrcode',
                    'must' => true
                ]
            ];
        $re =  $re->getRemoteData('door/open', $param, null , true , $user_id);
        $result = json_decode($re , true);
        if($result && isset($result['result']) && $result['result'] == 0)
        {
            $redirect_uri = F::getHomeUrl('/opendoor/succeed.html');
            header("Location: $redirect_uri");
            exit();
        }else{
            $redirect_uri = F::getHomeUrl('/opendoor/fail.html');
            header("Location: $redirect_uri");
            exit();
        }
    }


    /*
     * 区分新老门禁
     */
    private function isNew($code = '')
    {
        return true;
//        if(!$code)
//        {
//            return false;
//        }
//        $is_new = DoorNew::model()->findByAttributes(array('code'=>$code));
//        if($is_new)
//        {
//            return true;
//        }else{
//            return false;
//        }
    }

    public function actionActive(){
        $data['code'] = Yii::app()->request->getQuery("code");
        $uuid = Yii::app()->request->getQuery("access_token");

        $service = new GetTokenService();
        $data['access_token'] = $service->getAccessTokenFromPrivilegeMicroService();

        if (defined('YII_DEBUG') && YII_DEBUG == true) {
            $queryUrl = 'http://qrcode-czytest.colourlife.com/qrcode/status';;
        } else {
            $queryUrl = 'http://qrcode.colourlife.com/qrcode/status';;
        }

        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $queryUrl);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, TRUE);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        $return = json_decode($res);
        if(empty($return)){
            $this->renderPartial('Fail');
        }
        $status = $return->content;
        if($status->status){
            $this->renderPartial('active');
        }else{
            $this->renderPartial('Success',['code'=>$data['code'],'uuid'=>$uuid]);
        }
    }


    public function actionRelevance(){
        $data['community_uuid'] = Yii::app()->request->getQuery("community_uuid");
        $data['community_name'] = Yii::app()->request->getQuery("community_name");
        $data['code'] = Yii::app()->request->getQuery("code");
        $data['uuid'] = Yii::app()->request->getQuery("access_token");
        $service = new GetTokenService();
        $data['access_token'] = $service->getAccessTokenFromPrivilegeMicroService();

        if (defined('YII_DEBUG') && YII_DEBUG == true) {
            //$queryUrl = 'http://qrcode-czytest.colourlife.com/qrcode/api/activity';;
            $queryUrl = 'http://czy.qrcode.pw/qrcode/api/activity';;
        } else {
            $queryUrl = 'http://qrcode.colourlife.com/qrcode/api/activity';;
        }

        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $queryUrl);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, TRUE);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $res = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        if(empty($res)){
            $this->renderPartial('active_fail');exit;
        }
        $res = json_decode($res);
       if($res->code == 0){
           $this->renderPartial('active_success');
       }else{
           $this->renderPartial('active_fail');exit;
       }
    }

    public function actionRegions(){
        $result = ICEService::getInstance()->dispatch(
            'resource/regions',
            array(
            ),
            array(),
            'get'
        );
        echo json_encode($result);
    }

    public function actionRegion(){
        $pid = Yii::app()->request->getQuery("pid");
        $result = ICEService::getInstance()->dispatch(
            'resource/region',
            array(
                'pid'=>$pid
            ),
            array(),
            'get'
        );
        echo json_encode($result);
    }

    public function actionCommunity(){
        $provincecode = Yii::app()->request->getQuery("provincecode");
        $citycode = Yii::app()->request->getQuery("citycode");
        $regioncode = Yii::app()->request->getQuery("regioncode");


        $result = ICEService::getInstance()->dispatch(
            'community/area',
            array(
                'provincecode' => $provincecode,
                'citycode' => $citycode,
                'regioncode' => $regioncode,
            ),
            array(),
            'get'
        );

        $data = [];
        if(empty($result)){
            echo false;
        }
        foreach ($result as $k=>$value) {
            $data[$k]['uuid'] = $value['uuid'];
            $data[$k]['name'] = $value['name'];
        }
        echo json_encode($data);
    }

}