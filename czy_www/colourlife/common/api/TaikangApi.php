<?php
/**
 * 泰康保险
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/6/2
 * Time: 15:05
 */
class TaikangApi
{
    // 服务地址
    //private $serverUrl   = 'http://119.253.81.113/tk-link/rest'; // test
    private $serverUrl   = 'http://119.253.80.26/tk-link/rest';
    // 合作账号
    private $partner     = 'cai_sheng_huo';
    // 签名密钥
    //private $partnerKey  = '1234567890ABCDEF'; // test
    private $partnerKey  = '0Y32E8T3k56wc1izf08o34j5HM095O2544nD296k37Q60T387p8k756ucB629020Y01c4QpP5u27U72V14szM0061TSIn86bycJ7fhPmrn09C7Fq1hafdaNn15I1822J';

    // 请求的参数
    public $queryData;
    // 请求的地址
    public $queryUrl;

    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }

    protected function defaultParam()
    {
        return array(
            'coop_id'       => $this->partner,
            //'service_id'    => '',
            'sign_type'     => 'md5',
            //'sign'          => '',
            'format'        => 'json',
            'charset'       => 'utf-8',
            'version'       => '1.0',
            //'timestamp'     => microtime(),
            //'serial_no'     => '',
            'product_type'  => '1702L102',
            //'apply_content' => ''
        );
    }

    /**
     * 核保请求
     * @param $applicantInfo 投保人信息数组
     * @param $insuredInfo 被保人信息数组
     * @param $insureSubjectInfo 动态标的
     * @param $insuredAddress 投保地址
     * @param $detailedAddress 详细地址
     * @param $itemPropertyInfo 标的信息
     * @param $sn
     */
    public function confirmOrder(array $applicantInfo, array $insuredInfo, array $insureSubjectInfo, $insuredAddress, $detailedAddress, array $itemPropertyInfo, $sn)
    {
//        return array(
//            "createTime" => '1458630815821',
//		    "proposalNo" => "222222222222",
//		    "tradeId"    => "145863081582018499411"
//        );

        $applyContent = array(
            'applicantInfo'     => $applicantInfo,
            'insuredInfo'       => $insuredInfo,
            'issueDate'         => F::getMillisecond(),
            'insureSubjectInfo' => $insureSubjectInfo,
            'businessChannel'   => '05',
            'insuredAddress'    => $insuredAddress,
            'detailedAddress'   => $detailedAddress,
            'itemPropertyInfo'  => $itemPropertyInfo
        );

        $param = array(
            'service_id'    => '01',
            'timestamp'     => F::getMillisecond(),
            'serial_no'     => $sn,
            'apply_content' => $applyContent
        );

        $this->queryData = F::json_encode_ex($this->makeQueryData($param));
        $this->queryUrl = $this->makeServerUrl();

        $result =  Yii::app()->curl->postJson($this->queryUrl, $this->queryData);

        return $this->resolveResult($result);
    }

    /**
     * 出单
     * @param $customerId
     * @param $proposalNo
     * @param $tradeId
     * @param $outTradeId
     * @param $sn
     * @return bool|mixed
     */
    public function makeOrder($customerId, $proposalNo, $tradeId, $outTradeId, $sn)
    {
        $applyContent = array(
            'customerId'        => $customerId,
            'proposalNo'        => $proposalNo,
            'tradeId'           => $tradeId,
            'payAccount'        => '0000',
            'outTradeId'        => $outTradeId,
            'businessChannel'   => '05'
        );

        $param = array(
            'service_id'    => '02',
            'timestamp'     => F::getMillisecond(),
            'serial_no'     => $sn,
            'apply_content' => $applyContent
        );

        $this->queryData = F::json_encode_ex($this->makeQueryData($param));
        $this->queryUrl = $this->makeServerUrl();

        $result =  Yii::app()->curl->postJson($this->queryUrl, $this->queryData);

        return $this->resolveResult($result);
    }

    /**
     * 获取地址
     * @return mixed
     */
    public function address()
    {

        $this->queryUrl = $this->makeServerUrl('/address');
        $httpHeader = array(
            'content-type:application/json;charset=utf-8',
            'accept:application/json'
        );
        $result = Yii::app()->curl->get($this->queryUrl, array(), true, $httpHeader);
        $result = json_decode($result, true);

//        $result = array(
//            'addressList' => array(
//                array(
//                    'addressCode' => '110000',
//                    'addressName' => '北京市',
//                    'addressType' => '01',
//                    'parentAddressID' => ''
//                ),
//                array(
//                    'addressCode' => '110100',
//                    'addressName' => '北京市',
//                    'addressType' => '02',
//                    'parentAddressID' => '110000'
//                ),
//                array(
//                    'addressCode' => '110101',
//                    'addressName' => '东城区',
//                    'addressType' => '03',
//                    'parentAddressID' => '110100'
//                ),
//                array(
//                    'addressCode' => '110102',
//                    'addressName' => '西城区',
//                    'addressType' => '03',
//                    'parentAddressID' => '110100'
//                ),
//
//                array(
//                    'addressCode' => '210000',
//                    'addressName' => '广东省',
//                    'addressType' => '01',
//                    'parentAddressID' => ''
//                ),
//                array(
//                    'addressCode' => '210100',
//                    'addressName' => '深圳市',
//                    'addressType' => '02',
//                    'parentAddressID' => '210000'
//                ),
//                array(
//                    'addressCode' => '210101',
//                    'addressName' => '福田区',
//                    'addressType' => '03',
//                    'parentAddressID' => '210100'
//                ),
//                array(
//                    'addressCode' => '210102',
//                    'addressName' => '南山区',
//                    'addressType' => '03',
//                    'parentAddressID' => '210100'
//                ),
//            )
//        );

        return $result['addressList'];
    }

    /**
     * 生成请求的url
     * @param $method
     * @return string
     */
    private function makeServerUrl($method='')
    {
        return $this->serverUrl.$method;
    }

    /**
     * 生成请求的参数
     * @param null $param
     * @return array
     */
    private function makeQueryData($param=null)
    {
        $sign = $this->makeSign($param['apply_content']);

        $param['sign'] = $sign;

        return array_merge($param, $this->defaultParam());
    }

    /**
     * 处理请求结果
     * @param $result
     * @return bool|mixed
     */
    private function resolveResult($result)
    {
        $result = json_decode($result, true);

        if (isset($result['result_code']) && 0 === intval($result['result_code'])) {
            //$resultData = json_decode($result['data'], true);
            return $result['result_content'];
        }
        else {
            return false;
        }
    }

    /**
     * 生成签名
     * @param array $paras
     * @return string
     */
    private function makeSign(array $paras)
    {
        $signStr = $this->partnerKey.F::json_encode_ex($paras);

        return strtolower(md5($signStr));
    }

}
