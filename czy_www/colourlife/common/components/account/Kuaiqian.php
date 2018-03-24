<?php
class Kuaiqian
{
    static private $instance;
    //快钱入账接口的请求url
    protected $request_url = "https://sandbox.99bill.com/apipay/services/gatewayOrderQuery?wsdl";
    //快钱入账对账的方法
    protected $request_action = "gatewayOrderQuery";
    //快钱出账接口的请求url
    protected $refund_url = "https://sandbox.99bill.com/gatewayapi/services/gatewayRefundQuery?wsdl";
    //快钱出账对账的方法
    protected $refund_action = "query";
    //字符集 1=>UTF-8
    protected $inputCharset = 1;
    //版本号
    protected $version = "v2.0";
    //签名类型 1=>MD5加密
    protected $signType = 1;

    static public function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $payment_id 支付方式ID
     * @param $startTime 开始时间 格式为：时间戳
     * @param $endTime 结束时间 同开始时间
     * @param string $requestPage 页码
     * @return array
     * 按时间段(Max30)查询交易成功的订单
     */
    public function getAccountForIncomeByTime($payment_id,$startTime,$endTime,$requestPage="1"){
        $clientObj = new SoapClient($this->request_url);
        $payment = Payment::model()->findByPk($payment_id);
        if(empty($payment)){
            Yii::log('快钱入账对账失败,获取订单支付方式失败！',CLogger::LEVEL_INFO,'colourlife.core.account.Kuaiqian');
            return array();
        }
        //将传入的时间戳转换为快钱需要的时间字符串
        //年[4位]月[2位]日[2位]时[2位]分[2位]秒[2位]，例如：20071117020101
        $startTime = date('YmdHis',$startTime);
        $endTime = date('YmdHis',$endTime);
        //获取支付方式的配置
        $config = unserialize($payment->config);
        $key = @$config['accountReconciliationCert'];
        $merchantAcctId = $config['account'].'01';

        $queryType="1";//查询方式 1=》按时间查询
        $queryMode="1";//查询模式 1=>简单查询
        $kq_all_para = "";
        //拼接字符串
        $kq_all_para=$this->appendParam($kq_all_para,'inputCharset',$this->inputCharset);//字符集
        $kq_all_para=$this->appendParam($kq_all_para,'version',$this->version);//版本
        $kq_all_para=$this->appendParam($kq_all_para,'signType',$this->signType);//签名类型
        $kq_all_para=$this->appendParam($kq_all_para,'merchantAcctId',$merchantAcctId);//商家号
        $kq_all_para=$this->appendParam($kq_all_para,'queryType',$queryType);//查询方式
        $kq_all_para=$this->appendParam($kq_all_para,'queryMode',$queryMode);//查询模式
        $kq_all_para=$this->appendParam($kq_all_para,'startTime',$startTime);//开始时间
        $kq_all_para=$this->appendParam($kq_all_para,'endTime',$endTime);//结束时间
        $kq_all_para=$this->appendParam($kq_all_para,'requestPage',$requestPage);//页码
        //生成签名字符串
        $signMsg=$kq_sign_msg=strtoupper(md5($kq_all_para."key=".$key));
        //准备发送参数
        $para['inputCharset']=$this->inputCharset;
        $para['version']=$this->version;
        $para['signType']=$this->signType;
        $para['merchantAcctId']=$merchantAcctId;
        $para['queryType']=$queryType;
        $para['queryMode']=$queryMode;
        $para['startTime']=$startTime;
        $para['endTime']=$endTime;
        $para['requestPage']=$requestPage;
        $para['signMsg']=$signMsg;

        try {
            //请求入账对帐单
            $result=$clientObj->__soapCall($this->request_action,array($para));
            //转为数组
            $re=($this->object_array($result));
            if($re['errCode']!=""&&$re['errCode']!='10012'){//如果对账报错
                //写日志
                $errorMeg = $this->getIncomeErrorMsg($re['errCode']);
                Yii::log(("快钱对账失败:".$errorMeg.",".var_export($re)),CLogger::LEVEL_INFO,'colourlife.core.account.Kuaiqian');
                //返回空数组
                return array();
            }else{
                //将快钱返回的结果统一按格式返回
                return $this->getReturnByIncome($re);
            }
        } catch (SOAPFault $e) {
            Yii::log('快钱按时间入账对账失败，Exception:'.var_export($e),CLogger::LEVEL_INFO,'colourlife.core.account.Kuaiqian');
            return array();
        }
    }

    /**
     * @param $payment_id 支付方式ID
     * @param $orderId 订单号
     * @param string $requestPage
     * @return array
     * 根据订单号查询入账对账订单
     */
    public function getAccountForIncomeByOrderId($payment_id,$orderId,$requestPage="1"){
        $clientObj = new SoapClient($this->request_url);
        $payment = Payment::model()->findByPk($payment_id);
        if(empty($payment)){
            Yii::log('快钱入账对账失败,获取订单支付方式失败！',CLogger::LEVEL_INFO,'colourlife.core.account.Kuaiqian');
            return array();
        }
        $config = unserialize($payment->config);
        $key = @$config['accountReconciliationCert'];//"5UHQX2G65W4ECF5G";
        $merchantAcctId = $config['account'];//"1001213884201";
        $kq_all_para = "";
        $queryType="0";//查询方式 1=》按订单查询
        $queryMode="1";//查询模式 1=>简单查询
        $kq_all_para=$this->appendParam($kq_all_para,'inputCharset',$this->inputCharset);
        $kq_all_para=$this->appendParam($kq_all_para,'version',$this->version);
        $kq_all_para=$this->appendParam($kq_all_para,'signType',$this->signType);
        $kq_all_para=$this->appendParam($kq_all_para,'merchantAcctId',$merchantAcctId);
        $kq_all_para=$this->appendParam($kq_all_para,'queryType',$queryType);
        $kq_all_para=$this->appendParam($kq_all_para,'queryMode',$queryMode);
        $kq_all_para=$this->appendParam($kq_all_para,'requestPage',$requestPage);
        $kq_all_para=$this->appendParam($kq_all_para,'orderId',$orderId);
        $signMsg=$kq_sign_msg=strtoupper(md5($kq_all_para."key=".$key));

        $para['inputCharset']=$this->inputCharset;
        $para['version']=$this->version;
        $para['signType']=$this->signType;
        $para['merchantAcctId']=$merchantAcctId;
        $para['queryType']=$queryType;
        $para['queryMode']=$queryMode;
        $para['requestPage']=$requestPage;
        $para['orderId']=$orderId;
        $para['signMsg']=$signMsg;

        try {
            $result=$clientObj->__soapCall($this->request_action,array($para));
            $re=($this->object_array($result));
            if($re['errCode']!=""&&$re['errCode']!='10012'){//如果对账报错
                //写日志
                $errorMeg = $this->getIncomeErrorMsg($re['errCode']);
                Yii::log(("快钱对账失败:".$errorMeg.",".var_export($re)),CLogger::LEVEL_INFO,'colourlife.core.account.Kuaiqian');
                //返回空数组
                return array();
            }else{
                //将快钱返回的结果统一按格式返回
                return $this->getReturnByIncome($re);
            }
        } catch (SOAPFault $e) {
            Yii::log("快钱按订单({$orderId})入账对账失败，Exception:".var_export($e),CLogger::LEVEL_INFO,'colourlife.core.api.billApi');
            return array();
        }
    }

    /**
     * @param $payment_id 支付方式
     * @param $startDate 退款最后查询开始时间，格式时间戳
     * @param $endDate    退款最后查询结束时间
     * @param int $requestPage 页码
     * @return array
     * 按时间查询退款对账单
     */
    public function getAccountForRefundByTime($payment_id,$startDate,$endDate,$requestPage=1){
        $clientObj = new SoapClient($this->refund_url);

        $payment = Payment::model()->findByPk($payment_id);
        if(empty($payment)){
            Yii::log('快钱出账对账失败,获取订单支付方式失败！',CLogger::LEVEL_INFO,'colourlife.core.account.Kuaiqian');
            return array();
        }

        //将传入的时间戳转换为快钱需要的时间字符串
        //格式YYYYMMDD  20140108
        $startDate = date('Ymd',$startDate);
        $endDate = date('Ymd',$endDate);

        $config = unserialize($payment->config);
        $key = @$config['accountReconciliationCert'];
        $merchantAcctId = @$config['account'];
        $kq_all_para = "";
        $kq_all_para=$this->appendParam($kq_all_para,'version',$this->version);
        $kq_all_para=$this->appendParam($kq_all_para,'signType',$this->signType);
        $kq_all_para=$this->appendParam($kq_all_para,'merchantAcctId',$merchantAcctId);
        $kq_all_para=$this->appendParam($kq_all_para,'startDate',$startDate);
        $kq_all_para=$this->appendParam($kq_all_para,'endDate',$endDate);
        $kq_all_para=$this->appendParam($kq_all_para,'requestPage',$requestPage);

        $signMsg=$kq_sign_msg=strtoupper(md5($kq_all_para."key=".$key));

        $para['version']=$this->version;
        $para['signType']=$this->signType;
        $para['merchantAcctId']=$merchantAcctId;
        $para['startDate']=$startDate;
        $para['endDate']=$endDate;
        $para['requestPage']=$requestPage;
        $para['signMsg']=$signMsg;

        try {
            $result=$clientObj->__soapCall($this->refund_action,array($para));
            $re=($this->object_array($result));
            if($re['errCode']!=""&&$re['errCode']!='10012'){//如果对账报错
                //写日志
                $errorMeg = $this->getRefundErrorMsg($re['errCode']);
                Yii::log(("快钱出账对账失败:".$errorMeg.",".var_export($re)),CLogger::LEVEL_INFO,'colourlife.core.account.Kuaiqian');
                //返回空数组
                return array();
            }else{
                //将快钱返回的结果统一按格式返回
                return $this->getReturnByRefund($re);
            }
        } catch (SOAPFault $e) {
            Yii::log("快钱按时间出账对账失败，Exception:".var_export($e),CLogger::LEVEL_INFO,'colourlife.core.account.Kuaiqian');
            return array();
        }
    }

    /**
     * @param $payment_id   支付方式
     * @param $rOrderId      订单号
     * @param $startDate  退款最后查询开始时间
     * @param $endDate    退款最后查询结束时间
     * @param int $requestPage  页码
     * @return array
     * 按订单查询退款订单详情
     */
    public function getAccountForRefundByOrderId($payment_id,$rOrderId,$startDate,$endDate,$requestPage=1){
        $clientObj = new SoapClient($this->refund_url);

        $payment = Payment::model()->findByPk($payment_id);
        if(empty($payment)){
            Yii::log('快钱出账对账失败,获取订单支付方式失败！',CLogger::LEVEL_INFO,'colourlife.core.account.Kuaiqian');
            return array();
        }

        //将传入的时间戳转换为快钱需要的时间字符串
        //格式YYYYMMDD  20140108
        $startDate = date('Ymd',$startDate);
        $endDate = date('Ymd',$endDate);

        $config = unserialize($payment->config);
        $key = @$config['accountReconciliationCert'];
        $merchantAcctId = @$config['account'];

        $kq_all_para = "";
        $kq_all_para=$this->appendParam($kq_all_para,'version',$this->version);
        $kq_all_para=$this->appendParam($kq_all_para,'signType',$this->signType);
        $kq_all_para=$this->appendParam($kq_all_para,'merchantAcctId',$merchantAcctId);
        $kq_all_para=$this->appendParam($kq_all_para,'startDate',$startDate);
        $kq_all_para=$this->appendParam($kq_all_para,'endDate',$endDate);
        $kq_all_para=$this->appendParam($kq_all_para,'requestPage',$requestPage);
        $kq_all_para=$this->appendParam($kq_all_para,'rOrderId',$rOrderId);

        $signMsg=$kq_sign_msg=strtoupper(md5($kq_all_para."key=".$key));

        $para['version']=$this->version;
        $para['signType']=$this->signType;
        $para['merchantAcctId']=$merchantAcctId;
        $para['startDate']=$startDate;
        $para['endDate']=$endDate;
        $para['requestPage']=$requestPage;
        $para['ROrderId']=$rOrderId;
        $para['signMsg']=$signMsg;

        try {
            $result=$clientObj->__soapCall($this->refund_action,array($para));
            $re=($this->object_array($result));
            if($re['errCode']!=""&&$re['errCode']!='10012'){//如果对账报错
                //写日志
                $errorMeg = $this->getRefundErrorMsg($re['errCode']);
                Yii::log(("快钱出账对账失败:".$errorMeg.",".var_export($re)),CLogger::LEVEL_INFO,'colourlife.core.account.Kuaiqian');
                //返回空数组
                return array();
            }else{
                //将快钱返回的结果统一按格式返回
                return $this->getReturnByRefund($re);
            }
        } catch (SOAPFault $e) {
            Yii::log("快钱按订单({$rOrderId})出账对账失败，Exception:".var_export($e),CLogger::LEVEL_INFO,'colourlife.core.account.Kuaiqian');
            return array();
        }
    }


    /**
     * 拼接字符串
     * @param $smval
     * @param $valname
     * @param $valvlue
     * @return string
     */
    function appendParam($smval,$valname,$valvlue){
        if($valvlue == ""){
            $smval .= "";
        }else{
            $smval.=($valname.'='.$valvlue.'&');
        }
        return $smval;
    }

    /**
     * @param $array
     * @return array
     * 解析快钱接口的返回值，转换为数组
     */
    function object_array($array)
    {
        if(is_object($array))
        {
            $array = (array)$array;
        }
        if(is_array($array))
        {
            foreach($array as $key=>$value)
            {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }

    //将入账对账单的字符串转为数组
    function getReturnByIncome($arr){
        $returnArr = array(
            "currentPage"	=>	$arr['currentPage'],//当前页
            "errCode"		=>	$arr['errCode'],//错误代码
            "pageCount"		=>	$arr['pageCount'],//总页数
            "pageSize"		=>	$arr['pageSize'],//页面最大条数
            "recordCount"	=>	$arr['recordCount'],//总条数
            "orders"        =>  array(),
        );
        foreach($arr['orders'] as $var){
            $orders['sn'] = $var['orderId'];//我们的订单号
            $orders['dealId'] = $var['dealId'];//快钱订单号
            $orders['amount'] = $var['orderAmount']/100;//支付金额，单位:元
            $orders['status'] = 1;////因为只返回成功。不会存在失败的
            $orders['note'] = serialize($var);
            $returnArr['orders'][] = $orders;
        }
        return $returnArr;
    }

    function getReturnByRefund($arr){
        $returnArr = array(
            "currentPage"	=>	$arr['currentPage'],//当前页
            "errCode"		=>	$arr['errCode'],//错误代码
            "pageCount"		=>	$arr['pageCount'],//总页数
            "pageSize"		=>	$arr['pageSize'],//页面最大条数
            "recordCount"	=>	$arr['recordCount'],//总条数
            "results"        =>  array(),
        );
        foreach($arr['results'] as $var){
            $orders['sn'] = $var['ROrderId'];//我们的订单号
            $orders['dealId'] = $var['orderId'];//快钱退款订单号
            $orders['amount'] = $var['orderAmout']/100;//退款金额，单位:元
            $orders['status'] = $var['status'];
            $orders['note'] = serialize($var);
            $returnArr['results'][] = $orders;
        }
        return $returnArr;
    }

    function getIncomeErrorMsg($errCode){
        $errorList = array(
            '00000' => "未知错误",
            '10001' => "网关版本号不正确或不存在",
            '10002' => "签名类型不正确或不存在",
            '10003' => "人民币帐号格式不正确",
            '10004' => "查询方式不正确或不存在",
            '10005' => "查询模式不正确或不存在",
            '10006' => "查询开始时间不正确",
            '10007' => "查询结束时间不正确",
            '10008' => "商户订单号格式不正确",
            '10010' => "字符集输入不正确",
            '11001' => "开始时间不能在结束时间之后",
            '11002' => "允许查询的时间段最长为30天",
            '11003' => "签名字符串不匹配",
            '11004' => "查询结束时间晚于当前时间",
            '20001' => "该帐号不存在或已注销",
            '20002' => "签名字符串不匹配,您无权查询",
            '30001' => "系统繁忙,请稍候再查询",
            '30002' => "查询过程异常,请稍后再试",
            '31001' => "本时间段内无交易记录",
            '31002' => "本时间段内无成功交易记录",
            '31003' => "商户订单号不存在",
            '31004' => "查询结果超出能允许的文件范围",
            '31005' => "订单号所对应的交易支付未成功",
            '31006' => "当前记录集页面不存在",
        );
        if(!isset($errCode)){
            return "未知的错误,错误代码未知！";
        }
        return isset($errorList[$errCode])?$errorList[$errCode]:"未知错误";
    }

    function getRefundErrorMsg($errCode){
        $errorList = array(
            '10000' => "未知错误",
            '10002' => "不支持的返回类型",
            '10003' => "不合法的页面返回地址",
            '10004' => "不合法的后台返回地址",
            '10005' => "不支持的网关接口版本",
            '10006' => "商家 merchantAcctId 非法",
            '10007' => "输入的查询时间段违法",
            '10008' => "不支持的签名类型",
            '10009' => "解密验签失败",
            '10010' => "版本号不能为空",
            '10011' => "不支持的日期类型",
            '10012' => "没有数据",
            '10013' => "查询出错",
            '10014' => "账户号为空",
            '10015' => "验签字段不能为空",
            '10016' => "签名类型不能为空",
            '10017' => "退款查询时间不能为空",
            '10018' => "额外输出参数不正确或不存在",
        );
        if(!isset($errCode)){
            return "未知的错误,错误代码未知！";
        }
        return isset($errorList[$errCode])?$errorList[$errCode]:"未知错误";
    }

}
?>