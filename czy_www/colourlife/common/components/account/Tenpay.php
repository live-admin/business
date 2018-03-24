<?php
$lib_path = dirname(__FILE__);
// 包含库接口文件
include_once($lib_path . "/classes/CheckRequestHandler.class.php");
include_once($lib_path . "/classes/client/TenpayHttpClient.class.php");
include_once($lib_path . "/classes/client/ClientResponseHandler.class.php");
class Tenpay
{
    static private $instance;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $payment_id  支付方式
     * @param $startDate    开始时间。时间戳
     * @param $endDate      结束时间。可以空。传入无效
     * @param int $requestPage  页码
     * @return array()
     * 按时间段查 询交易成功的订单
     */
    public function getAccountForIncomeByTime($payment_id,$startDate,$endDate=0,$requestPage=1){
        $payment = Payment::model()->findByPk($payment_id);
        if(empty($payment)){
            Yii::log('财付通入账对账失败,获取订单支付方式失败！',CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
            return array();
        }

        //将传入的时间戳转换为财付通需要的时间字符串
        //格式YYYYMMDD  2014-01-08
        $trans_time = date('Y-m-d',$startDate);

        $config = unserialize($payment->config);
        $spid = @$config['account'];
        $key = @$config['key'];

        /* 创建支付请求对象 */
        $reqHandler = new CheckRequestHandler();
        //通信对象
        $httpClient = new TenpayHttpClient();

        //设置请求参数
        $reqHandler->init();
        $reqHandler->setKey($key);
        $reqHandler->setParameter("spid", $spid);
        $reqHandler->setParameter("trans_time", $trans_time);
        $reqHandler->setParameter("stamp", time());
        $reqHandler->setParameter("cft_signtype", "0");
        $reqHandler->setParameter("mchtype", "1");//只请求成功的订单
        //设置请求内容
        $httpClient->setReqContent($reqHandler->getRequestURL());
        //调用
        try{
            if($httpClient->call()) {
                $resContent = trim($httpClient->getResContent());
                return $this->str2arrbyincome($resContent);
            } else {
                //后台调用通信失败
                Yii::log('财付通入账对账失败,Error:'.$httpClient->getErrInfo(),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
                return array();
            }
        }catch (Exception $e){
            //后台调用通信失败
            Yii::log('财付通入账对账失败,Exception:'.var_export($e),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
            return array();
        }
    }

    /**
     * @param $payment_id 支付方式
     * @param $orderId  我们的订单号
     * @param string $requestPage   页码
     * @return array
     * 根据订单号查询入账对账订单
     */
    public function getAccountForIncomeByOrderId($payment_id,$orderId,$requestPage="1"){
        $payment = Payment::model()->findByPk($payment_id);
        if(empty($payment)){
            Yii::log('财付通入账对账失败,获取订单支付方式失败！',CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
            return array();
        }

        $config = unserialize($payment->config);
        $partner = @$config['account'];
        $key = @$config['key'];
        /* 创建支付请求对象 */
        $reqHandler = new RequestHandler();
        //通信对象
        $httpClient = new TenpayHttpClient();

        //设置请求参数
        $reqHandler->init();
        $reqHandler->setKey($key);

        $reqHandler->setGateUrl("https://gw.tenpay.com/gateway/normalorderquery.xml");
        $reqHandler->setParameter('input_charset','utf-8');
        $reqHandler->setParameter("partner", $partner);
        //out_trade_no和transaction_id至少一个必填，同时存在时transaction_id优先
        //我们的订单号
        $reqHandler->setParameter("out_trade_no", $orderId);
        //财付通订单号
        //$reqHandler->setParameter("transaction_id", "2000000501201004300000000442");

        //设置请求内容
        $httpClient->setReqContent($reqHandler->getRequestURL());

        try{
            if($httpClient->call()) {
                $resContent = trim($httpClient->getResContent());
                $res = @simplexml_load_string($resContent,NULL,LIBXML_NOCDATA);
                $res = json_decode(json_encode($res),true);
                if($res['retcode']==0){//返回状态码，0表示成功，其他未定义
                    $returnArr = array(
                        "currentPage"	=>	"1",//当前页
                        "errCode"		=>	"",//错误代码
                        "pageCount"		=>	"",//总页数
                        "pageSize"		=>	"1000",//页面最大条数
                        "recordCount"	=>	"1",//总条数
                        "orders"        =>  array(
                            'sn' => $res['out_trade_no'],//我们的订单号
                            'dealId' => $res['transaction_id'],//财付通订单号
                            'amount' => $res['total_fee']/100,//支付金额，单位:元
                            'status' => 1,//因为只返回成功。不会存在失败的
                            'note' => serialize($res),
                        ),
                    );
                    return $returnArr;
                }else{
                    //后台调用通信失败
                    Yii::log('财付通按订单号入账对账失败,ErrorCode:'.var_export($res['retmsg']),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
                    return array();
                }
            } else {
                //后台调用通信失败
                Yii::log('财付通入账对账失败,ErrorCode:'.$httpClient->getResponseCode().",ErrorMsg:".$httpClient->getErrInfo(),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
                return array();
            }
        }catch (Exception $e){
            Yii::log('财付通入账对账失败,Exception:'.var_export($e),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
            return array();
        }

    }

    /**
     * 按时间查询退款对账单
     */
    public function getAccountForRefundByTime($payment_id,$startDate,$endDate=0,$requestPage=1){
        $payment = Payment::model()->findByPk($payment_id);
        if(empty($payment)){
            Yii::log('财付通入账对账失败,获取订单支付方式失败！',CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
            return array();
        }

        //将传入的时间戳转换为财付通需要的时间字符串
        //格式YYYYMMDD  2014-01-08
        $trans_time = date('Y-m-d',$startDate);

        $config = unserialize($payment->config);
        $spid = @$config['account'];
        $key = @$config['key'];

        /* 创建支付请求对象 */
        $reqHandler = new CheckRequestHandler();
        //通信对象
        $httpClient = new TenpayHttpClient();

        //设置请求参数
        $reqHandler->init();
        $reqHandler->setKey($key);
        $reqHandler->setParameter('input_charset','utf-8');
        $reqHandler->setParameter("spid", $spid);
        $reqHandler->setParameter("trans_time", $trans_time);
        $reqHandler->setParameter("stamp", time());
        $reqHandler->setParameter("cft_signtype", "0");
        $reqHandler->setParameter("mchtype", "2");//只请求退款的订单
        //设置请求内容
        $httpClient->setReqContent($reqHandler->getRequestURL());
        //调用
        try{
            if($httpClient->call()) {
                $resContent = trim($httpClient->getResContent());
                return $this->str2arrbyrefund($resContent);
            } else {
                //后台调用通信失败
                Yii::log('财付通入账对账失败,Error:'.$httpClient->getErrInfo(),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
                return array();
            }
        }catch (Exception $e){
            //后台调用通信失败
            Yii::log('财付通入账对账失败,Exception:'.var_export($e),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
            return array();
        }
    }

    /**
     * @param $payment_id  支付方式
     * @param $rOrderId 订单号
     * @param int $startDate 可为空
     * @param int $endDate 可为空
     * @param int $requestPage
     * @return array
     * 按订单查询退款订单详情
     */
    public function getAccountForRefundByOrderId($payment_id,$rOrderId,$startDate=0,$endDate=0,$requestPage=1){
        $payment = Payment::model()->findByPk($payment_id);
        if(empty($payment)){
            Yii::log('财付通入账对账失败,获取订单支付方式失败！',CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
            return array();
        }
        $config = unserialize($payment->config);
        $partner = @$config['account'];
        $key = @$config['key'];

        /* 创建支付请求对象 */
        $reqHandler = new RequestHandler();
        //通信对象
        $httpClient = new TenpayHttpClient();
        //应答对象
        $resHandler = new ClientResponseHandler();

        //设置请求参数
        $reqHandler->init();
        $reqHandler->setKey($key);
        $reqHandler->setGateUrl("https://gw.tenpay.com/gateway/normalrefundquery.xml");
        $reqHandler->setParameter('input_charset','utf-8');
        $reqHandler->setParameter("partner", $partner);
        //out_trade_no和transaction_id、out_refund_no、refund_id至少一个必填，
        //同时存在时以优先级高为准，优先级为：refund_id>out_refund_no>transaction_id>out_trade_no
        $reqHandler->setParameter("out_trade_no", $rOrderId);
        //$reqHandler->setParameter("transaction_id", "1900000109201101120023707085");

        //设置请求内容
        $httpClient->setReqContent($reqHandler->getRequestURL());

        try{
            if($httpClient->call()) {
                $resContent = trim($httpClient->getResContent());
                $res = @simplexml_load_string($resContent,NULL,LIBXML_NOCDATA);
                $res = json_decode(json_encode($res),true);
                if($res['retcode']==0){//返回状态码，0表示成功，其他未定义
                    $returnArr = array(
                        "currentPage"	=>	"1",//当前页
                        "errCode"		=>	"",//错误代码
                        "pageCount"		=>	"",//总页数
                        "pageSize"		=>	"1000",//页面最大条数
                        "recordCount"	=>	"1",//总条数
                        "results"        =>  array(
                            'sn' => $res['out_trade_no'],//我们的订单号
                            'dealId' => $res['transaction_id'],//财付通订单号
                            'amount' => $res['refund_fee_0']/100,//支付金额，单位:元
                            'status' => in_array($res['refund_state_0'],array(4,10))?1:0,
                            'note' => serialize($res),
                        ),
                    );
                    return $returnArr;
                }else{
                    //后台调用通信失败
                    Yii::log('财付通按订单号入账对账失败,Error:'.$this->getErrorMsg($res['retcode']),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
                    return array();
                }
            } else {
                Yii::log('财付通入账对账失败,ErrorCode:'.$httpClient->getResponseCode().",ErrorMsg:".$httpClient->getErrInfo(),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
                return array();
            }
        }catch (Exception $e){
            Yii::log('财付通入账对账失败,Exception:'.var_export($e),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
            return array();
        }
    }

    //将入账对账单的字符串转为数组
    function str2arrbyincome($str){
        $str = trim(strip_tags($str));
        //将腾讯返回的字符串转为数组
        $list = @explode("\n",iconv("gb2312","utf-8",$str));
        $lastKey = count($list)-1;//获得数组最后一列
        $lastStr = @$list[$lastKey];//获得最后一列的结果,订单总数和总金额
        $lastArr = explode(",",trim($lastStr,","));
        $recordCount = isset($lastArr[0])?$lastArr[0]:0;
        if(count($list)<=1){
            Yii::log(("财付通入账对账失败:".var_export($list)),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
            //返回空数组
            return array();
        }else{
            $returnArr = array(
                "currentPage"	=>	"1",//当前页
                "errCode"		=>	"",//错误代码
                "pageCount"		=>	"1",//总页数
                "pageSize"		=>	"10000",//页面最大条数
                "recordCount"	=>	$recordCount,//总条数
                "orders"        =>  array(),
            );
            for($i=1;$i<=$recordCount;$i++){
                $contentStr = $list[$i];
                $contentArr = explode(",",trim($contentStr,","));
                $arr['sn'] = trim(trim($contentArr[2]),"`");//我们的订单号
                $arr['dealId'] = trim(trim($contentArr[1]),"`");//财付通订单号
                $arr["amount"] = trim(trim($contentArr[5]),"`");//支付金额，单位:元
                $arr['status'] = 1;//因为只返回成功。不会存在失败的
                $arr['note'] = "<br>{$list[0]}<br>$contentStr";
                $returnArr['orders'][] = $arr;
            }
            return $returnArr;
        }
    }

    function str2arrbyrefund($str){
        $str = trim(strip_tags($str));
        //将腾讯返回的字符串转为数组
        $list = explode("\n",iconv("gb2312","utf-8",$str));
        $lastKey = count($list)-1;//获得数组最后一列
        $lastStr = @$list[$lastKey];//获得最后一列的结果,订单总数和总金额
        $lastArr = explode(",",trim($lastStr,","));
        $recordCount = isset($lastArr[0])?$lastArr[0]:0;
        if(count($list)<=1){
            Yii::log(("财付通出账对账失败:".var_export($list)),CLogger::LEVEL_INFO,'colourlife.core.account.Tenpay');
            //返回空数组
            return array();
        }else{
            $returnArr = array(
                "currentPage"	=>	"1",//当前页
                "errCode"		=>	"",//错误代码
                "pageCount"		=>	"1",//总页数
                "pageSize"		=>	"10000",//页面最大条数
                "recordCount"	=>	$recordCount,//总条数
                "results"        =>  array(),
            );
            for($i=1;$i<=$recordCount;$i++){
                $contentStr = $list[$i];
                $contentArr = explode(",",trim($contentStr,","));
                $arr['sn'] = trim(trim($contentArr[3]),"`");//我们的订单号
                $arr['dealId'] = trim(trim($contentArr[7]),"`");//退款单号
                $arr["amount"] = trim(trim($contentArr[10]),"`");//退款金额，单位:元
                $arr['status'] = 1;//因为只返回成功。不会存在失败的
                $arr['note'] = "<br>{$list[0]}<br>$contentStr";
                $returnArr['results'][] = $arr;
            }
            return $returnArr;
        }
    }

    function getErrorMsg($errCode){
        $errorList = array(
            '88219998' => '系统错误',
            '88229999' => '其他业务错误',
            '88222001' => '参数错误',
            '88222002' => '签名错误',
            '88222003' => '订单不属于该商户',
            '88221010' => '没有客户端证书',
            '88221012' => '当前证书与商户未绑定',
            '88221013' => '当前证书已经失效',
            '88221009' => '订单不存在',
            '88222010' => '退款单号相同，但退款信息不一致',
            '88222011' => '订单信息不匹配',
            '88222012' => '订单未支付',
            '88222013' => '退款总金额已超过订单金额',
            '88222014' => '该笔订单未退款',
            '88229101' => '没有此商户号',
            '88229102' => '可用余额不足',
            '88229103' => '转账退款金额超出限制',
            '88229104' => '密码错误',
            '88229105' => '用户不存在',
            '88229106' => '查找不到对应的信息 用户不存在',
            '88229107' => '收款方姓名不一致',
            '88229108' => '商户无此权限',
            '88229109' => '此用户被冻结',
            '88229111' => '退款ip未授权',
            '88229112' => '数据错误，请联系财付通',
            '88229113' => '退款错误，商户退款单号对应多条财付通退款单号',
            '88229114' => '余额不足，可做现金帐号退款',
            '88229115' => '已经退款成功',
            '88229116' => '不能确定退款类型',
            '88229117' => '需要走退款审核',
            '88229118' => '不允许退款',
            '88229119' => '风控校验失败'
        );
        if(!isset($errCode)){
            return "未知的错误,错误代码未知！";
        }
        return isset($errorList[$errCode])?$errorList[$errCode]:"未知错误";
    }

}
?>