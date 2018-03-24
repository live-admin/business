<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/18
 * Time: 14:38
 */

class lhqpay extends PayFactory
{

    //APP_ID，由邻花钱提供
   // private $app_id="314830809130369056";   //测试环境
    private $app_id="327494513335603200";   //正式环境正式账号
    //private $app_id = "323521861252157440";   //正式环境测试账号

    //APP_KEY，由邻花钱提供
   // private $app_key="9C95CEEFA9BF4FE2A34CF15540F5DE5E";    //测试环境
    private $app_key="4EF288139EC84E5499AF8AD41629ACB5";    //正式环境正式账号
    //private $app_key = "9691A3618A704253B6EC57DBD3E29558";    //正式环境测试账号

    public function get_code($pay, $channel, $userId , $sellerId = '9959f117-df60-4d1b-a354-776c20ffb8c7')
    {

        //加载rsa加密文件
        require_once(dirname(__FILE__) . '/lhqpay/lhqpay_rsa.class.php');
        require_once(dirname(__FILE__) . '/lhqpay/AopEncrypt.php');

        /*取得返回回调信息地址 接收通知的URL，需绝对路径*/
        $notify_url = PayLib::notify_url('lhqpay');

        //订单号
        $out_trade_no = $pay->pay_sn;

        //查找订单相对应的model
        $model = SN::findContentBySN($out_trade_no);
        if (empty($model))
            throw new CHttpException(400, "订单不存在");

        $subject = $model->modelName;
        $remark = '';
        //找到对应的订单类型；
        $orderType = SN::orderTypeBySN($out_trade_no,'');
        //商品名
        if (!empty($model->shop_name)) $subject .= ',' . $model->shop_name;
        if (!empty($model->goods_brief)) $remark = $model->goods_brief;
        if (!$remark) $remark = $subject;

        if(!empty($model->community_uuid) && !empty($model->model) && $model->model == 'thirdFrees13')
        {
            $result = array();
            $communityArr = array(
                // uuid => seller_id
                'c0400d56-f925-4f51-a333-58338f345cc2' => 'c0400d36-f625-4f51-a333-69449f340cc2', // 彩网测试小区
                '82550031-75e7-4a8e-ab8f-074b285ab0a8' => '82550031-75e7-4a8e-ab8f-074b285ab0a8', // 彩科大厦
                '04f2977e-c5ac-4239-a782-37ed4ec5b6ab' => '04f2977e-c5ac-4239-a782-37ed4ec5b6ab', // 红树别院
                'fd23c2c5-cfed-4e65-b745-b618acbeb1d5' => 'fd23c2c5-cfed-4e65-b745-b618acbeb1d5', // 阳光四季
                '028a9c13-cc4b-427d-b550-fa24fba26f7c' => '028a9c13-cc4b-427d-b550-fa24fba26f7c', // 成都喜年广场
                '3dca77f7-7a1d-4ec9-b7d0-4de3e63535b1' => '3dca77f7-7a1d-4ec9-b7d0-4de3e63535b1', // 楼村花园
                '621544b6-fafc-4ecf-a76a-0d517f4e1565' => '621544b6-fafc-4ecf-a76a-0d517f4e1565', // 湖彬苑项目
                'aa3de793-ef23-4947-8ad7-7e7e28d2c4a6' => 'aa3de793-ef23-4947-8ad7-7e7e28d2c4a6', // 中通雅苑
                'c447f332-c611-45e2-a8a3-7aed7d49da0d' => 'c447f332-c611-45e2-a8a3-7aed7d49da0d', // 雍景豪庭
                //'2726bde5-194f-461e-8f1f-ce1cc73943c9' => '2726bde5-194f-461e-8f1f-ce1cc73943c9', // 龙泉壹中心停车（龙泉驿区）
                //'682d7de6-b521-4812-af50-ebc7b82f6c32' => '682d7de6-b521-4812-af50-ebc7b82f6c32', // 龙泉壹中心（锦江区）
                //'ac603997-ecc4-4e23-a924-5e1f0b2cc093' => 'ac603997-ecc4-4e23-a924-5e1f0b2cc093', // 龙泉壹中心商业（龙泉驿区）
            );
            if(isset($communityArr[$model->community_uuid]))
            {
                try {
                    $result = ICEService::getInstance()->dispatch(
                        'community/parent',
                        array(
                            'id' => $model->community_uuid,
                            'type' => 1
                        ),
                        array(),
                        'get'
                    );
                } catch (Exception $e) {
                    Yii::log(
                        sprintf(
                            '获取小区父级节点失败',
                            $model->community_uuid,
                            $e->getMessage(), $e->getCode()
                        ),
                        CLogger::LEVEL_ERROR,
                        'colourlife.logFile.local_type'
                    );
                }
                $remark = json_encode($result);
                $sellerId = $communityArr[$model->community_uuid];
            }
            Yii::log("订单号{$out_trade_no}当前商户id:{$sellerId}", CLogger::LEVEL_INFO, 'colourlife.core.sellerid');


//            if($model->community_uuid == 'c0400d56-f925-4f51-a333-58338f345cc2') //彩网测试小区
//            {
//                $remark = json_encode($result);
//                $sellerId = 'c0400d36-f625-4f51-a333-69449f340cc2';
//            }
//            if($model->community_uuid == '82550031-75e7-4a8e-ab8f-074b285ab0a8') //彩科大厦
//            {
//                $remark = json_encode($result);
//                $sellerId = '82550031-75e7-4a8e-ab8f-074b285ab0a8';
//            }
        }
        /* 总金额 */
        $total_fee = $this->getAmountNum($pay);

        //业务参数
        $bizContentArray = array(
            "notifyUrl" => $notify_url,
            "outTradeNo" => $out_trade_no,
            "remark" => $remark,
            "orderType" => $orderType,
            "subject" => $subject,
            "totalAmount" => $total_fee,
            "sellerId" => $sellerId,    //'9959f117-df60-4d1b-a354-776c20ffb8c7',
        );

        $bizContent = CJSON::encode($bizContentArray);

//        Yii::log('双乾支付业务参数：' . $bizContent, CLogger::LEVEL_INFO, 'colourlife.core.lhqpay.bizcontent');

        $rsaUtil = new RsaUtil();

        $rsaUtil->rsaPrivateKeyFilePath = dirname(__FILE__) . '/lhqpay/key/rsa_private_key.pem';
        $rsaPublicKeyPem = dirname(__FILE__) . '/lhqpay/key/rsa_public_key.pem';

        // 对业务参数执行公钥加密
        $cryptContent = '';
        $enCryptContent = $rsaUtil->rsaEncrypt($bizContent, $rsaPublicKeyPem, 'utf-8');
        foreach ($enCryptContent as $row){
            $cryptContent .= $row;
        }
        $cryptContent = base64_encode($cryptContent);


        $paramsArr = array(
            "appId" => $this->app_id,
            "timestamp" => time()*1000,
            "version" => "1.0",
            "bizContent" => $cryptContent,
            "type" => 1,
            "channel" => $channel,
            "outUserId" => $userId,
        );

        //生成签名参数
        $paramsStr = $rsaUtil->getSignContent($paramsArr);
        //签名
        $sign = $rsaUtil->sign($this->app_key.$paramsStr.$this->app_key);
        //$sign = urlencode($sign);
        $paramsArr['sign'] = $sign;
        //$paramsArr['bizContent'] = urlencode($cryptContent);

        return CJSON::encode($paramsArr);

    }


    /**
     * 支付回调
     * @param string $type
     * @return bool
     */
    public function respond($type = 'return')
    {

        $params = $_POST;

        //加载rsa加密文件
        require_once(dirname(__FILE__) . '/lhqpay/lhqpay_rsa.class.php');
        $rsaUtil = new RsaUtil();
        $rsaUtil->alipayPublicKey = dirname(__FILE__) . '/lhqpay/key/rsa_public_key.pem';
        $rsaPublicKeyPem = dirname(__FILE__) . '/lhqpay/key/rsa_public_key.pem';
        $sign = $params['sign'];
        $params['sign'] = null;
        $verify_result = $rsaUtil->verify($this->app_key . $rsaUtil->getSignContent($params) . $this->app_key, $sign, $rsaPublicKeyPem);

        //判断验证
        if ($verify_result) {
            $data = $_POST;

            //返回值
            $channel = $data['channel'];
            if ($channel == 'ALIPAY') {
                $payment_id = 11;
            } elseif ($channel == 'WECHAT') {
                $payment_id = 10;
            } else {
                $payment_id = 15;
            }

            $seller_id = $data['sellerId'];

            //订单号
            $out_trade_no = $data['outTradeNo'];
            $this->_sn = $out_trade_no;
            //总交易额
            $total_amount = $data['totalAmount'];
            $amount = $total_amount;
            //邻花钱流水号
            $trade_no = $data['tradeNo'];
            //交易状态
            $trade_status = $data['tradeStatus'];
            //1、开通了普通即时到账，买家付款成功后。
            if ($trade_status == 'SUCCESS') {
                /* 检查支付的金额是否相符 */
                if (!PayLib::check_money($this->_sn, $total_amount)) {
                    Yii::log('邻花钱流水号：' . $trade_no . '，彩之云订单号：' . $this->_sn . '检查支付的金额不相符', CLogger::LEVEL_INFO, 'colourlife.core.payment.lhqpay');
                    return false;
                }

                if ($type == 'notify' && Pay::getPayStatus($this->_sn) == 0) { //状态为0才能去修改状态
                    //添加支付日志
                    if (!PayLog::createPayLog($this->_sn, $amount, $payment_id))
                        Yii::log('邻花钱添加支付记录失败邻花钱流水号：' . $trade_no . '，彩之云订单号：' . $this->_sn . ' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.lhqpay');
                    else {
                        /* 改变订单状态 */
                        PayLib::order_paid($this->_sn, $payment_id);
                        Yii::log('邻花钱支付成功邻花钱流水号：' . $trade_no . '，彩之云订单号：' . $this->_sn . ' 支付金额：' . $amount, CLogger::LEVEL_INFO, 'colourlife.core.payment.lhqpay');
                    }
                }
            } else {
                Yii::log('邻花钱支付失败邻花钱流水号：' . $trade_no . '，彩之云订单号：' . $this->_sn, CLogger::LEVEL_INFO, 'colourlife.core.payment.lhqpay');
                return false;
            }
            return true;
        } else {
            //回调签名错误
            Yii::log('邻花钱回调签名错误', CLogger::LEVEL_INFO, 'colourlife.core.payment.lhqpay');
            return false;
        }
    }

    public function OrderStatus($sn){
            $bigContent = [
                'outTradeNo'=>$sn,
               'tradeNo'=>''
            ];
            $queryUrl = 'https://wallet.hynpay.com/pay-api/openapi/v1/queryOrderState';

            require_once(dirname(__FILE__) . '/lhqpay/lhqpay_rsa.class.php');
            $bizContent = json_encode($bigContent);

            $rsaUtil = new RsaUtil();
            $rsaUtil->rsaPrivateKeyFilePath = dirname(__FILE__) . '/lhqpay/key/rsa_private_key.pem';
            $rsaPublicKeyPem = dirname(__FILE__) . '/lhqpay/key/rsa_public_key.pem';

            // 对业务参数执行公钥加密
            $cryptContent = '';
            $enCryptContent = $rsaUtil->rsaEncrypt($bizContent, $rsaPublicKeyPem, 'utf-8');
            foreach ($enCryptContent as $row){
                $cryptContent .= $row;
            }
            $cryptContent = base64_encode($cryptContent);
            $paramsArr = array(
                'appId' => $this->app_id,
                'bizContent' =>$cryptContent,
                'channel' => 'LHQ',
                'timestamp' =>time()*1000,
                'type'=>1,
                'version' => '1.0',
            );
            //获取签名请求双乾
            $paramStr = $rsaUtil->getSignContent($paramsArr);
            $sign = $rsaUtil->sign($this->app_key.$paramStr.$this->app_key);
            $paramsArr['sign'] = $sign;

            $return = $this->postCurl($queryUrl,$paramsArr,array(),'POST');
            $return = json_decode($return);

            if(empty($return)){
                app('log')->info('双乾请求地址:' . $queryUrl . json_encode($return));
                throw new \Exception('请求双乾失败',205);
            }
            return $return;
    }

    function postCurl($url, $body = array(), $header = array(), $method = 'POST')
    {
        array_push($header, 'Accept: application/json');
        array_push($header, 'Content-Length: ' . strlen(http_build_query($body)));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        switch ($method) {
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  //原先是FALSE，可改为2

        if ($body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($body));
        }
        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $ret = curl_exec($ch);
        $err = curl_error($ch);
        $errno = curl_errno($ch);

        curl_close($ch);

        if ($errno) {
            return $errno;
        }
        if($err){
            return $err;
        }
        return $ret;
    }





}