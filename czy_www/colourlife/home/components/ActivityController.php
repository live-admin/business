<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/4/11
 * Time: 16:33
 */
abstract class ActivityController extends CController
{
    public $secret;
    public $beginTime;
    public $endTime;

    public function filterSignAuth($filterChain)
    {
        if (empty(Yii::app()->session['sign_user'])||(isset($_GET['user_id'])&&isset(Yii::app()->session['sign_user']['user_id'])&&$_GET['user_id']!=Yii::app()->session['sign_user']['user_id'])) {
            $param = $_GET;
//            dump($param);
            if ( 'POST' == $_SERVER['REQUEST_METHOD'] )
                $param = $_POST;

            if (isset($param['ftype']) && $param['ftype'] == 'wsq'){
            	if (strpos($param['sign'],"?sn=") !== false){
            		$signStr = explode("?sn=",$param['sign']);
            		$param['sign'] = $signStr[0];
            	}
            }
            if (empty($param) || !isset($param['sign']) || !isset($param['request_time']))
                $this->redirect('http://mapp.colourlife.com/m.html');
            $sign = new Sign($this->secret);

            if (false === $sign->checkSign($param))
                $this->redirect('http://mapp.colourlife.com/m.html');

            Yii::app()->session['sign_user'] = $param;
        }

        $filterChain->run();
    }

    public function filterValidity($filterChain)
    {
        $nowTime = time();
        if ( $nowTime < strtotime($this->beginTime))
            exit('活动未开始，敬请期待！');
        if ( $nowTime > strtotime($this->endTime))
            exit('活动已结束！');

        $filterChain->run();
    }

    /**
     * 解析uid
     * @param $userId
     * @return float
     */
    public function getUserId($userId='')
    {
        if (empty($userId))
            $userId = Yii::app()->session['sign_user']['user_id'];

        return ($userId - 1778) / 778;
    }

    /**
     * 获取用户信息
     * @param $userId=2556
     * @return float
     */
    public function getUserInfo($userId='')
    {
    	if (empty($userId)){
    		$userId = Yii::app()->session['sign_user']['user_id'];
    	}
    	$userId=($userId - 1778) / 778;
    	if (ceil($userId)!=$userId){
    		return false;
    	}
    	$customer = Customer::model()->find("id=:id and state = 0", array('id' => intval($userId)));
    	if (empty($customer)){
    		return false;
    	}else {
    		return $customer;
    	}
    }
    
    public function output($data, $code=1, $msg='请求成功')
    {
        header('Content-type: application/json');
        $result = array(
            'retCode' => $code,
            'retMsg' => $msg,
            'data' => $data
        );

        echo CJSON::encode($result); exit;
    }
    
    /**
     * 分享页微信登录
     * @param $returnUrl 回调url
     */
    private function shareConfirm($returnUrl){
    	$share_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    	//有‘isWx’参数就只在微信里打开
    	if (isset($_GET['isWx'])){
    		$url=F::getMUrl('/ConfirmLogin/Index').'?url='.base64_encode($share_url).'&reUrl='.base64_encode($returnUrl);
            $url = 'http://m.aparcar.cn/service/wxauth/authorize?scope=snsapi_userinfo&backurl='.urlencode($url);
    	}else {
    		//判断是否在微信里打开
    		if(isset($_SERVER['HTTP_USER_AGENT'])&&strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger')==true){
    			$url=F::getMUrl('/ConfirmLogin/Index').'?url='.base64_encode($share_url).'&reUrl='.base64_encode($returnUrl);
                $url = 'http://m.aparcar.cn/service/wxauth/authorize?scope=snsapi_userinfo&backurl='.urlencode($url);
    		}else {
    			//跳到分享页
    			$url=$returnUrl;
    		}
    	}
    	$this->redirect($url);
    }
    /**
     * 分享
     */
    public function actionShare(){
    	$reUrl=base64_decode(Yii::app()->request->getParam('reUrl'));
    	//微信用户登录
    	if (!isset($_GET['openid'])){
    		if (isset($_SESSION['wx_user'])){
    			unset($_SESSION['wx_user']);
    		}
    		$this->shareConfirm($reUrl);
    	}else {
    		$wx_info=array();
    		if (isset($_GET['openid'])&&empty($_SESSION['wx_user'])){ //保存微信信息
    			$wx_info['openid']=$_GET['openid'];
    			if (isset($_GET['nickname'])){
    				$wx_info['nickname']=$_GET['nickname'];
    			}
    		
    			if (isset($_GET['headimgurl'])){
    				$wx_info['headimgurl']=$_GET['headimgurl'];
    			}
    			$_SESSION['wx_user']=$wx_info;
    		}
    	}
    	$this->redirect($reUrl);
    }

    /**
     * AJax Error Message
     * @param $model
     * @param bool $firstError
     * @return string
     */
    public function errorSummary( $model, $firstError = false ) {
        $content = '';
        if (!is_array($model)) {
            $model = array($model);
        }
        foreach ($model as $m) {
            foreach ($m->getErrors() as $errors) {
                foreach ($errors as $error) {
                    if ($error != '') {
                        $content .= "$error\n";
                    }
                    if ($firstError) {
                        break;
                    }
                }
            }
        }
        return $content;
    }

    //发送验证码短信
    public function actionSendCode()
    {
        $model = new SmsForm();
        $model->setScenario('register');
        $mobile = Yii::app()->request->getParam('mobile');
        $model->mobile = $mobile;

        //$model->attributes = Yii::app()->request->restParams;

        if (!$model->validate())
            $this->output('', 0, $this->errorSummary($model));

        //检查次数
        $num = Item::SMS_LIMIT_VALIDATE;
        $count = $model->GetBlackValidateNum();
        if ($count >= $num)
            $this->output('', 0, '您的手机号因验证次数过多已被禁用，如果不是您本人操作请联系客服');

        if ( !$model->send()){
            $this->output('', 0, $this->errorSummary($model));
        }

        $this->output(array('msg'=>'发送成功'));
    }

    protected function checkCode($mobile, $code)
    {
        $model = new SmsForm;
        $model->setScenario('check');
        //$model->attributes = Yii::app()->request->restParams;
        $model->mobile = $mobile;
        $model->code = $code;

        $num = Item::SMS_LIMIT_VALIDATE;
        //检查次数
        $count = $model->GetBlackValidateNum();
        if ($count >= $num)
            $this->output('', 0, '您的手机号因验证次数过多已被禁用，如果不是您本人操作请联系客服');

        if (!$model->validate())
            return $this->errorSummary($model);

        $model->useCode();

        return true;
    }
    
    /**
     * 限制重复提交
     * @param unknown $sn
     * @throws CHttpException
     * @return boolean
     */
    public function isLimitRequest(){
    	$postData = isset($_POST) ? $_POST : '';
    	$str = $_SERVER['SERVER_NAME'].http_build_query($postData);
    	$requestData = Yii::app()->rediscache->get($str);
    	if (!empty($requestData)){
    		throw new CHttpException(400, "请不要频繁提交！");
    	}
    	$currentTime = time();
    	Yii::app()->rediscache->set($str, $currentTime, 3);
    	return true;
    }

}