<?php

/**
 * OAuth 网页授权获取用户信息
 */

require 'AlipaySign.php';
require 'HttpRequst.php';

class Auth
{
    const API_AUTH_URL     = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm';
    const API_URL      = 'https://openapi.alipay.com/gateway.do';


    /**
     * 应用ID
     *
     * @var string
     */
    protected $appId = '2015123001053074';

    /**
     * 获取上一次的授权信息
     *
     * @var array
     */
    protected $lastPermission;

    /**
     * 已授权用户
     *
     * @var \Overtrue\Wechat\Utils\Bag
     */
    protected $authorizedUser;

    /**
     * 生成outh URL
     *
     * @param string $to
     * @param string $scope
     *
     * @return string
     */
    public function url($to = null, $scope = 'auth_base')
    {
        $to !== null || $to = $this->current();

        $params = array(
            'app_id'        => $this->appId,
            'redirect_uri'  => $to,
            'scope'         => $scope,
        );

        return self::API_AUTH_URL.'?'.http_build_query($params);
    }

    /**
     * 直接跳转
     *
     * @param string $to
     * @param string $scope
     */
    public function redirect($to = null, $scope = 'auth_base')
    {
        header('Location:'.$this->url($to, $scope));

        exit;
    }

    /**
     * 获取已授权用户
     */
    public function user()
    {
        if ($this->authorizedUser
            || (!$code = $_GET['auth_code']) ) {
            return $this->authorizedUser;
        }

        $permission = $this->getAccessPermission($code);

        if ( isset($permission['alipay_system_oauth_token_response'])) {
            $user = $this->getUser($permission['alipay_system_oauth_token_response']['access_token']);
        } else {
            throw new \Exception('获取access_token失败', 400);
        }

        return $this->authorizedUser = $user;
    }

    /**
     * 通过授权获取用户
     *
     * @param string $to
     * @param string $scope
     *
     * @return Bag | null
     */
    public function authorize($to = null, $scope = 'auth_base')
    {
        if (!$_GET['auth_code']) {

            $this->redirect($to, $scope);
        }

        return $this->user();
    }

    /**
     * 获取access token
     *
     * @param string $code
     *
     * @return string
     */
    public function getAccessPermission($code)
    {
        $params = array(
            'app_id'      => $this->appId,
            'method'     => 'alipay.system.oauth.token',
            'charset'    => 'GBK',
            'sign_type'  => 'RSA',
            'timestamp'  => date('Y-m-d H:i:s'),
            'version'    => '1.0',
            'grant_type' => 'authorization_code',
            'code'       => $code,
        );

        $alipaySign = new AlipaySign();
        $sign = $alipaySign->sign_request($params);

        $params['sign'] = $sign;

        $result = HttpRequest::sendPostRequst(self::API_URL, $params);
        $result = iconv ( "GBK", "UTF-8", $result );

        return json_decode($result, true);
    }

    /**
     * 获取用户信息
     *
     * @param string $accessToken
     *
     * @return array
     */
    public function getUser($accessToken)
    {
        $params = array(
            'method'        => 'alipay.user.userinfo.share',
            'auth_token'    => $accessToken,
            'app_id'        => $this->appId,
            'charset'       => 'GBK',
            'sign_type'     => 'RSA',
            'timestamp'     => date('Y-m-d H:i:s'),
            'version'       => '1.0',
        );

        $alipaySign = new AlipaySign();
        $sign = $alipaySign->sign_request($params);

        $params['sign'] = $sign;

        //$url = self::API_URL.'?'.http_build_query($params);

        $result = HttpRequest::sendPostRequst(self::API_URL, $params);
        $result = iconv ( "GBK", "UTF-8", $result );

        return json_decode($result, true);
    }


    /**
     * 魔术访问
     *
     * @param string $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (isset($this->lastPermission[$property])) {
            return $this->lastPermission[$property];
        }
    }

    /**
     * 获取当前URL
     *
     * @return string
     */
    public function current()
    {
        $protocol = (!empty($_SERVER['HTTPS'])
            && $_SERVER['HTTPS'] !== 'off'
            || $_SERVER['SERVER_PORT'] === 443) ? 'https://' : 'http://';

        if(isset($_SERVER['HTTP_X_FORWARDED_HOST'])){
            $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }else{
            $host = $_SERVER['HTTP_HOST'];
        }
        return $protocol.$host.$_SERVER['REQUEST_URI'];
    }
}