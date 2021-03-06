<?php

/**
 * Class StarApi
 *
 * Yii::import('common.api.StarApi');
 * $cloud = StarApi::getInstance();
 * 电表新接口
 */
class StarApiNew
{
    static protected $instance;

    //protected $baseUrl = 'http://183.238.159.19:8099/WebService_ACH/?WSDL';
    protected $baseUrl = 'http://114.119.9.154/StarWebService_AMR/?WSDL';
    private $userid = 'STAR', $userpws = 'star';


    static public function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }

    /**
     * 测试与Web Service服务器的连接
     *
     *  返回一个Hello World的字符串
     *
     * @return string 'Hello World'
     */
    public function callTest()
    {
        $client = $this->getClient();
        $test = $client->test();
        return $test->TestResult;
    }

    /**
     * 检查电表编号的合法性，即在思达系统中是否存在
     *
     * 接口声明：int CheckMeterNo(string OperatorCode, string Password, string
     *           ​MeterNo, out string CustomerName, out string CustomerAddress)
     * 参数说明：
     * ​OperatorCode​- 输入参数，调用接口的操作员编号。
     * Password    ​- 输入参数，调用接口的操作员密码。
     * MeterNo​- 输入参数，需要验证合法性的电表编号，特别说明，代码式电表的表号都是11位的数字。
     * CustomerName – 输出参数，电表用户的名称，如果函数调用失败或者电
     *                 表还没有安装给具体的用户，返回值都为空字符串。
     * CustomerAddress-输出参数，电表用户的地址，如果函数调用失败或者电
     *                  表还没有安装给具体的用户，返回值都为空字符串。
     * ​CheckMeterNoResult- 返回值说明：类型是整数，取值：-1 – 调用异常；0 – 表号合法；1 – 表号非法；
     *                  ​2 – 操作员编号检查错误；3 – 操作员密码错误
     *
     *
     */
    public function callCheckMeterNo($meter)
    {
        $client = $this->getClient();
        $params = array(
            'OperatorCode' => $this->userid,
            'Password' => $this->userpws,
            'MeterNo' => $meter
        );
        $check = $client->CheckMeterNo($params);

        return $check;
    }







    /**
     * 购电接口
     * 接口声明：int PurchasePower(string OperatorCode, string Password, string MeterNo,     string Amount, string OrderNo, out string Token, out string STOrderNo)
     * ​接口说明：购电
     * ​参数说明：
     *   ​OperatorCode​- 输入参数，调用接口的操作员编号。
     *   Password    ​- 输入参数，调用接口的操作员密码。
     *   MeterNo​- 输入参数，需要验证合法性的电表编号，特别说明，代码
     *   ​  式电表的表号都是11位的数字。
     *   Amount​- 输入参数，以元为单位的购电金额。
     *   OrderNo​- 输入参数，订单编号。
     *   Token​- 输出参数，给电表充值的Token码，是一个20位的数字
     *   ​  字符串。该参数的长度为20表示调用成功，调用失败时
     *   ​  长度为零。
     *   STOrderNo - 输出参数，思达系统记录的APP软件的订单编号，应
     *   ​该与输入参数OrderNo一样。
     * PurchasePowerResult- ​返回值说明：类型是整数，取值：-1 – 调用异常；0 – 调用成功1 – 调用失败；
     *       ​2 – 操作员编号检查错误；3 – 操作员密码错误；4 – 表号不存在；
     *       ​5 – 指定的表号没有用户在用；6 – 用户已经被列入黑名单；7 – 购
     *       ​电金额不足；8 – 系统日期错误
     *
     *
     */
    //生成电表充值码
    public function callPurchasePower($meter, $amount, $order_no)
    {
        $client = $this->getClient();
        $params = array(
            'OperatorCode' => $this->userid,
            'Password' => $this->userpws,
            'MeterNo' => $meter,
            'Amount' => $amount,
            'OrderNo' => $order_no
        );
        $power = $client->PurchasePower($params);

        return $power;
    }

    /*
     函数名	GetMeterBalance
     声明	int GetMeterBalance(string OperatorCode, string Password, string MeterNo,  out string MeterBalance)
     说明	查询电表的剩余金额
     参数说明	OperatorCode	输入参数，调用接口的操作员编号
     Password    	输入参数，调用接口的操作员密码
     MeterNo	输入参数，需要验证合法性的电表编号，特别说明，代码式电表的表号都是11位的数字
     MeterBalance	输出参数，电表的剩余金额
     返回值		-1	调用异常
		0	调用成功
		1	调用失败
		2	操作员编号检查错误
		3	操作员密码错误
		4	表号不存在

     */
    //查询电表的剩余金额
    public function  callGetMeterBalance($meter)
    {

        $client = $this->getClient();

        $params = array(
            'OperatorCode' => $this->userid,
            'Password' => $this->userpws,
            'MeterNo' => $meter,
        );

        $power = $client->GetMeterBalance($params);


        return $power;


    }


    /*函数名	GetMeterInsufficient
    声明	int GetMeterBalance(string OperatorCode, string Password, string MeterNo,  out string Insufficient)
    说明	查询电表的剩余金额是否不足
    参数说明	OperatorCode	输入参数，调用接口的操作员编号
    Password    	输入参数，调用接口的操作员密码
    MeterNo	输入参数，需要验证合法性的电表编号，特别说明，代码式电表的表号都是11位的数字
    Insufficient	输出参数，取值：1-余额不足；0-余额足够
    返回值	-1      调用异常
    0	调用成功
    1	调用失败
    2	操作员编号检查错误
    3	操作员密码错误
    4	表号不存在
    */
    //查询电表的剩余金额是否不足
    public function  callGetMeterInsufficient($meter)
    {
        $client = $this->getClient();
        $params = array(
            'OperatorCode' => $this->userid,
            'Password' => $this->userpws,
            'MeterNo' => $meter,
        );
        $power = $client->GetMeterInsufficient($params);

        return $power;


    }


    protected function getClient()
    {
        $client = new SoapClient($this->baseUrl);

        // $client = new SoapClient($this->baseUrl);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';

        Yii::log(sprintf("调用链接：\"%s\"", $this->baseUrl), CLogger::LEVEL_INFO, 'colourlife.core.api.StarApi_new');

        return $client;
    }

}