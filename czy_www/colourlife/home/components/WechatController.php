<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/9/13
 * Time: 10:35
 */
require dirname(__DIR__).'/../common/components/wechat/autoload.php';

use Overtrue\Wechat\Auth;

abstract class WeChatController extends CController
{
    public $appId;
    public $secret;

    public $loginKey;

    public function filterWeChatAuth($filterChain)
    {
        $auth = new Auth($this->appId, $this->secret);

//        $_SESSION['wechat_user'] = array(
//            'openid' => 'opE6suNFTIDFymcJC9rp5r2E79ts',
//            'nickname' => 'Joy',
//            'sex' => 1,
//            'language' => 'zh_CN',
//            'city' => '深圳',
//            'province' => '广东',
//            'country' => '中国',
//            'headimgurl' => '',
//            'privilege' => array()
//        );
        if (empty($_SESSION['wechat_user'])) {
            $user = $auth->authorize(); // 返回用户 Bag
            $_SESSION['wechat_user'] = $user->all();
            // 跳转到其它授权才能访问的页面
        } else {
            $user = $_SESSION['wechat_user'];
        }

        $filterChain->run();
    }

    public function filterIsLogin($filterChain)
    {
        if (empty(Yii::app()->session['logged_user'])) {
            if ( !isset($_GET['customer_id'])
                || !isset($_GET['sign'])
                || !isset($_GET['ts'])
            )
                $this->output('', 0, '非法请求');

            $para = $_GET;

            $sign = new Sign($this->loginKey);
            if (false == $sign->checkSign($para))
                $this->output('', 0, '签名验证失败');

            $customer = Customer::model()->findByPk($para['customer_id']);

            Yii::app()->session['logged_user'] = $customer;
        }

        $filterChain->run();
    }

    public function output($data, $code=1, $msg='请求成功')
    {
        $result = array(
            'retCode' => $code,
            'retMsg' => $msg,
            'data' => $data
        );

        echo CJSON::encode($result); exit;
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


}