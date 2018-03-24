<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/6/7
 * Time:
 */
Yii::import('common.services.BaseService');
class BusinessService extends BaseService
{

    protected $baseUrl = '';
    protected $queryData;
    protected $queryUrl;
    protected $curlTimeOut = 16;
    protected $curlConnectTimeOut = 2;
    protected $secret;
    protected $appID;

    //F::getStaticsUrl('/common/v30/zxfp@2x.png')
    //采集饭票
    protected $common_payment_arr =
        [
            '10409df9f330665b451e82c7f4a03e43' =>
                [
                    'payment_id' => 1,
                    'logo' =>  'https://cc.colourlife.com/common/v30/ptfp@2x.png',
                    'name' => '彩之云全国饭票',
                    'discount' => 1,
                    'user_amount' => 0,
                    'is_valid' => 1,
                    'valid_time' => ''
                ]
        ];
    //三方支付
    protected $third_payment_arr =
        [
            '1025f6c4eedfcd92462b8b7383257877' =>
                [
                    'payment_id'=> 10,
                    'logo' => 'http://cimg.colourlife.com/images/2014/08/22/14/wechat3x.png',
                    'name' => '微信手机支付',
                    'discount'=> 1,
                    'user_amount' => '',
                    'is_valid' => 1,
                    'valid_time' => ''
                ],
            '5221sd566sd1x6dfg1321fbf2g15h' =>
                [
                    'payment_id'=> 15,
                    'logo' => 'http://cimg.colourlife.com/images/2017/06/16/23/484810431.png',
                    'name' => '花样钱包（98折）',
                    'discount'=> 1,
                    'user_amount' => '',
                    'is_valid' => 1,
                    'valid_time' => ''
                ],
            '10409df9f330665b451e82c7f4a03e4' =>
                [
                    'payment_id'=> 11,
                    'logo' => 'http://cimg.colourlife.com/images/2014/08/22/14/alipay3x.png',
                    'name' => '支付宝手机支付',
                    'discount'=> 1,
                    'user_amount' => '',
                    'is_valid' => 1,
                    'valid_time' => ''
                ]
        ];

    //尊享饭票
    protected $special_payment_arr =
        [

        ];

    //默认支付方式
    protected $default_payment_arr =
        [
            [
                'payment_type' => 1,
                'payment_name' => '尊享饭票',
                'list' => []
            ],
            [
                'payment_type' => 2,
                'payment_name' => '第三方支付',
                'list' =>
                    [
                        [
                            'payment_id'=> 10,
                            'logo' => 'http://cimg.colourlife.com/images/2014/08/22/14/wechat3x.png',
                            'name' => '微信手机支付',
                            'discount'=> 1,
                            'user_amount' => '',
                            'is_valid' => 1,
                            'valid_time' => ''
                        ],
                        [
                            'payment_id'=> 11,
                            'logo' => 'http://cimg.colourlife.com/images/2014/08/22/14/alipay3x.png',
                            'name' => '支付宝手机支付',
                            'discount'=> 1,
                            'user_amount' => '',
                            'is_valid' => 1,
                            'valid_time' => ''
                        ],
                        [
                            'payment_id'=> 15,
                            'logo' => 'http://cimg.colourlife.com/images/2017/06/16/23/484810431.png',
                            'name' => '花样钱包（98折）',
                            'discount'=> 1,
                            'user_amount' => '',
                            'is_valid' => 1,
                            'valid_time' => ''
                        ],
                    ]
            ]
        ];

    public function __construct()
    {
        if (defined('YII_DEBUG') && YII_DEBUG == true) {
            $this->baseUrl = 'http://business-czytest.colourlife.com';
            $this->appID = 'ICECZY00-F26F-42B8-988C-27F4AEE3292A';
            $this->secret = 'LkbMwVxeDIsN3nIaExIq';
        } else {
            $this->baseUrl = 'http://business.colourlife.com';
            $this->appID = 'ICECZY00-F26F-42B8-988C-27F4AEE3292A';
            $this->secret = 'LkbMwVxeDIsN3nIaExIq';
        }
    }

    public function dispatch($interface = '', $getParam = array() ,$postParam = array() , $method = 'GET')
    {
        try {
            $response = $this->request(
                $this->getQueryUrl(
                    $interface,
                    $getParam
                ),
                // 处理 post field 参数
                $postParam,
                $method
            );
        } catch (Exception $e) {
            $message = $e->getMessage();
            $code = $e->getCode();
            throw new CHttpException(
                501,
                sprintf(
                    'ICE请求失败：%s[%s]。请重试!',
                    $message ? $message : '连接出错',
                    $code ? $code : '-1'
                )
            );
        }

        $response = json_decode($response , true);
        if(!$response || !isset($response['code']) || $response['code'] !=0)
        {
            throw new CHttpException(
                501,
               isset($response['message']) && !empty($response['message']) ? $response['message'] : '获取商户平台数据失败'
            );
        }
        return $response['content'];
    }

    protected function getQueryUrl($interface = '', $queryData = array())
    {
        $url = sprintf(
            $queryData ? '%s%s/%s?%s' : '%s%s/%s',
            trim($this->baseUrl, ' /'),
            '',
            trim($interface, ' /'),
            $queryData ? http_build_query($queryData) : ''
        );
        return $url;
    }

    protected function request($queryUrl = '', $queryData = array(), $method = 'GET')
    {
        $this->queryUrl = $queryUrl;
        $this->queryData = $queryData;

        switch (strtoupper($method)) {
            default:
            case 'GET':
                $response = $this->requestCurlGet($queryUrl, $queryData);
                break;

            case 'POST':
                $response = $this->requestCurlPost($queryUrl, $queryData);
                break;

            case 'PUT':
                $response = $this->requestCurlPut($queryUrl, $queryData);
                break;
        }

        return $response;
        //return $this->parseQueryResponse($response);
    }

    protected function requestCurlGet($queryUrl = '', $header = array())
    {
        //array_push($header, 'Content-Type: application/x-www-form-urlencoded');
        array_push($header, 'Accept:application/json');

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $queryUrl);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->curlTimeOut);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->curlConnectTimeOut);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        $errno = curl_errno($curl);
        if ($errno) {
            $error = curl_error($curl);
            throw new Exception($error ? $error : '', $errno ? $errno : 5001);
        }

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    protected function requestCurlPost($queryUrl = '', $queryData = array(), $header = array())
    {
        array_push($header, 'Content-Type: application/x-www-form-urlencoded');
        array_push($header, 'Accept:application/json');

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $queryUrl);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->curlTimeOut);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->curlConnectTimeOut);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($queryData));

        $errno = curl_errno($curl);
        if ($errno) {
            $error = curl_error($curl);
            throw new Exception($error ? $error : '', $errno ? $errno : 5001);
        }

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    protected function requestCurlPut($queryUrl = '', $queryData = array(), $header = array())
    {
        array_push($header, 'Content-Type: application/x-www-form-urlencoded');
        array_push($header, 'Accept:application/json');

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $queryUrl);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->curlTimeOut);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->curlConnectTimeOut);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($queryData));

        $errno = curl_errno($curl);
        if ($errno) {
            $error = curl_error($curl);
            throw new Exception($error ? $error : '', $errno ? $errno : 5001);
        }

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    public function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $key == "sign_type" || $key == "signature"
                || $key == "ts"|| $key == "access_token"|| $val == "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    public function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    public function createLinkStringUrlEncode($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".urlencode($val)."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);

        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

        return $arg;
    }

    public function createSign($data  ,$sign_type = 'MD5') {
        $filter_data = $this->paraFilter($data);
        $sort_data = $this->argSort($filter_data);
        $stringA = $this->createLinkstringUrlencode($sort_data);
        $stringSignTemp = $stringA.'&secret='.$this->secret;
        if($sign_type == 'MD5')
        {
            $sign = StrtoUpper(md5($stringSignTemp));
        }else if($sign_type == 'HMAC-SHA256')
        {
            $sign = StrtoUpper(hash_hmac('sha256' , $stringSignTemp , $this->secret));
        }else{
            $sign = '';
        }
        return $sign;

    }

    /*
     * 获取订单详情
     */
    public function getPayInfo($colour_sn , $customer_id)
    {
        $data =
            [
                'appID' => $this->appID,
                'business_uuid' => 'a83aba9e-f23e-4e5d-8694-5d7c10705fce',
                'colour_sn' => $colour_sn,
                'nonce_str' => rand(1,5555555),
            ];
        $data['signature'] = $this->createSign($data);
        $result = $this->dispatch('/pay/orderquery' ,$data , [] , 'GET');
        $order =
            [
                'sn' => $colour_sn,
                'amount' => bcdiv($result['total_fee'], 100, 2),
                'fpAmount' => bcdiv($result['meal_total_fee'], 100, 2),
                'mix_type' => 0,
                'status' => 0,
                'name' => 'attach'
            ];
        //默认支付方式，微信、支付宝、花样钱包
        if(!isset($result['limit_payment']) || !json_decode($result['limit_payment'] ,true))
        {
            $reArray = array(
                'retCode' => 1,
                'retMsg' => '请求成功',
                'data' =>array('order' => $order, 'payment' => $this->default_payment_arr)
            );
            return $reArray;
        }

        //可配置化支付方式
        $limit_payment = json_decode($result['limit_payment'] ,true);
        $third_payment = [];
        $common_payment = [];
        $special_payment = [];
        foreach($limit_payment as $key=>$value)
        {
            //三方支付
            if(isset($this->third_payment_arr[$value['payment_uuid']]))
            {
                $third_payment[] = $this->third_payment_arr[$value['payment_uuid']];
            }
            //普通饭票支付
            if(isset($this->common_payment_arr[$value['payment_uuid']]))
            {
                $common_payment[] = $this->common_payment_arr[$value['payment_uuid']];
            }
            //尊享饭票支付
            if(isset($value['payment_atid']))
            {
                $special = FinancePayType::model()->findByAttributes(array('typeid' => 7 , 'status'=>1 , 'atid'=>$value['payment_atid']));
                if($special)
                {
                    $relation = FinanceCustomerRelateModel::model()->findByAttributes(array('customer_id'=>$customer_id , 'atid'=>$value['payment_atid']));
                    if($relation)
                    {
                        Yii::import('common.services.LocalRedPacketService');
                        $localRedPacketService = new LocalRedPacketService();
                        $rate = $localRedPacketService->getLocalRate($special->pano);
                        $special_payment[] =
                            [
                                'payment_id'=> $value['payment_uuid'],
                                'logo' => 'https://cc.colourlife.com/common/v30/zxfp@2x.png',
                                'name' => $value['payment_name'],
                                'discount'=> $rate,
                                'is_valid'=> 1,
                                'valid_time'=> '',
                                'user_amount'=> $this->getCustomerBalanceInfo($relation->pano , $relation->cano)
                            ];
                    }

                }
            }
        }

        $special_mealTicket = array(
            'payment_type' => 1,
            'payment_name' => '尊享饭票',
            'list' => $special_payment
        );


        $third_list = array(
            'payment_type' => 2,
            'payment_name' => '第三方支付',
            'list' =>$third_payment
        );
        if(empty($common_payment))
        {
            $pay_list = array(
                $special_mealTicket,
                $third_list
            );
        }else{
            //获取全国饭票余额
            $panoResult = FinanceMicroService::getInstance()->getCustomerPano();
            $atid = isset($panoResult['atid']) && $panoResult['atid'] ? $panoResult['atid'] : '';
            $pano = isset($panoResult['pano']) && $panoResult['pano'] ? $panoResult['pano'] : '';
            if (!$pano || !$atid) {
                throw new CHttpException(400, '彩之云金融平台账号未配置');
            }
            $customerAccount = FinanceCustomerRelateModel::model()->find('customer_id=:customer_id and pano=:pano and atid=:atid', array(':customer_id' => Yii::app()->user->id,':pano'=>$pano,':atid'=>$atid));
            $clientAccount = FinanceMicroService::getInstance()->queryClient($customerAccount['pano'], $customerAccount['cano']);
            $balance = $clientAccount['account']['money'];
            $common_payment[0]['user_amount'] = $balance;
            $common_mealTicket = array(
                'payment_type' => 0,
                'payment_name' => '普通饭票',
                'list' => $common_payment
            );
            $pay_list = array(
                $common_mealTicket,
                $special_mealTicket,
                $third_list
            );
        }


        $reArray = array(
            'retCode' => 1,
            'retMsg' => '请求成功',
            'data' =>array('order' => $order, 'payment' => $pay_list)
        );
        return $reArray;
    }

    /*
     * 获取订单支付详情
     */
    public function getPay($colour_sn , $payment_uuid , $customer_id)
    {
        $data =
            [
                'appID' => $this->appID,
                'payment_uuid' => $payment_uuid,
                'colour_sn' => $colour_sn,
                'customer_id' => $customer_id,
                'nonce_str' => rand(1,5555555),
            ];
        $data['signature'] = $this->createSign($data);
        $result = $this->dispatch('/pay/orderinfo' ,$data , [] , 'GET');
        return $result;
    }

    /*
     * 同步地方饭票支付方式
     */
    public function SysLocal($atid , $pano , $name , $community_uuid)
    {
        $data =
            [
                'appID' => $this->appID,
                'sign_type'=> 'MD5',
                'atid' => $atid,
                'pano' => $pano,
                'name' => $name.'尊享饭票',
                'logo' => 'https://cc.colourlife.com/common/v30/zxfp@2x.png',
                'community_uuid' => $community_uuid,
                'nonce_str' => md5(rand(1,5555555)),
            ];
        $data['signature'] = $this->createSign($data);
        $result = $this->dispatch('/payment/create' , []  , $data , 'POST');
        return $result;
    }

    /**
     * 查询用户余额信息
     */
    public function getCustomerBalanceInfo($pano,$cano){
        if (empty($pano)){
            return 0.00;
        }
        if (empty($cano)){
            return 0.00;
        }
        Yii::log('调用调用金融平台接口开始',CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getCustomerBalanceInfo');
        $financeService = new FinanceMicroService();
        $info = $financeService->queryClient($pano, $cano);
        Yii::log('调用调用金融平台接口结束：'.json_encode($info),CLogger::LEVEL_ERROR, 'colourlife.core.PayService.getCustomerBalanceInfo');
        if (isset($info['account']['money'])){
            return $info['account']['money'];
        }else {
            return 0.00;
        }
    }
}