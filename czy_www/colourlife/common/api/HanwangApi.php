<?php
/**
 * 汉王停车缴费接口服务类
 * User: PHP
 * Date: 2015/9/22
 * Time: 9:55
 */
class HanwangApi
{
    //private $serverUrl = 'http://115.28.239.44:8083/Interface/';
    private $serverUrl = 'http://api.tongtongtingche.com.cn/Interface/';
    private $partner = '17';
    private $partnerKey = '2aab745552a5a1c1';

    protected static $instance;

    public static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }

    public $queryData;
    public $queryUrl;

    /**
     * 获取所有停车场信息
     * @return bool|mixed
     */
    public function getAllParkingInfo()
    {
        $this->queryData = $this->makeQueryData();
        $this->queryUrl = $this->makeServerUrl('getparks');

        $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
        //dump($result);
        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 月卡车查询
     * @param $car_number
     * @return bool|mixed
     */
    public function getCarByPlate($car_number, $parkCode='')
    {
        $params = array(
            'plate' => $car_number,
            'parkID' => $parkCode
        );

        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('getcarbyplate');

        $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
        //dump($result);
        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 月卡续费查询
     * @param $car_number
     * @param $parkCode
     * @param $unit
     * @return bool|mixed
     */
    public function calculateCarDelay($car_number, $parkCode, $unit)
    {
        $params = array(
            'plate' => $car_number,
            'parkID' => $parkCode,
            'unit' => $unit
        );

        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('calculatecardelay');

        $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
        //dump($result);

        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 续费通知（续卡）
     * @param $car_number
     * @param $parkCode
     * @param $unit 延期时长（月）
     * @param $paid 支付金额
     * @param $orderID 订单
     * @return bool|mixed
     */
    public function carDelay($car_number, $parkCode, $unit, $paid, $orderID)
    {
        $params = array(
            'plate' => $car_number,
            'parkID' => $parkCode,
            'unit' => $unit,
            'paid' => $paid,
            'orderID' => $orderID
        );

        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('cardelay');

        try {
            $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
            $result =  $this->resolveResult($result);
        }
        catch (Exception $e) {
            $result = false;
        }

        //dump($result);

        return $result;
    }


    /**********临时停车**********/

    /**
     * 临时停车 费用查询
     * @param $car_number
     * @return bool|mixed
     */
    public function calFee($car_number, $parkCode='')
    {
        $params = array(
            'plate' => $car_number,
            'parkID' => $parkCode
        );

        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('calfee');

        $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
        //dump($result);
        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 临时停车 用户缴费完成后，调用接口在停车场系统中添加支付账单
     * @param $car_number
     * @param $parkCode
     * @param $time
     * @param $paid
     * @param $billID
     * @param $orderID
     * @return bool|mixed
     */
    public function addBill($car_number, $parkCode, $time, $paid, $billID, $orderID)
    {
        $params = array(
            'plate' => $car_number,
            'parkID' => $parkCode,
            'time' => $time,
            'paid' => $paid,
            'billID' => $billID,
            'orderID' => $orderID
        );

        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('addbill');

        $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
        //dump($result);

        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 处理请求结果
     * @param $result
     * @return bool|mixed
     */
    private function resolveResult($result)
    {
        $result = json_decode($result, true);
        //dump($result);

        if (isset($result['success']) && true === $result['success']) {
            return (isset($result['data']) && ! empty($result['data']) ) ? $result['data'] : true;
        }
        else {
            Yii::log('【汉王停车】调用接口：'.$this->queryUrl.' 参数：'.json_encode($this->queryData).' 错误信息：'.$result['message'], CLogger::LEVEL_INFO, 'colourlife.core.api.HanwangApi');
            return false;
        }
    }


    /**
     * 生成请求地址
     * @param $method
     * @return string
     */
    private function makeServerUrl($method)
    {
        return $this->serverUrl.$method;
    }

    /**
     * 请求参数
     * @param null $para
     * @return array
     */
    private function makeQueryData($para=null)
    {
        $timestamp = date('Y-m-d H:i:s');
        $paraComm = array(
            'companyID' => $this->partner,
        );

        if ($para && is_array($para)) {
            // 合并参数  php version < 4  不能用array_merge
            foreach ($paraComm as $key => $val) {
                $para[$key] = $val;
            }
        }
        else {
            $para = $paraComm;
        }

        return array(
            'param' => json_encode($para),
            'timestamp' => $timestamp,
            'sign' => $this->makeSign($para, $timestamp)
        );
    }

    /**
     * 签名
     * @param $para
     * @param $timestamp
     * @return string
     */
    private function makeSign($para, $timestamp)
    {
        return md5('param='.json_encode($para).'&timestamp='.$timestamp.'&key='.$this->partnerKey);
    }

}