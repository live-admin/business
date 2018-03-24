<?php
/**
 * 格美特停车缴费接口服务类
 * User: Joy
 * Date: 2015/7/4
 * Time: 10:37
 */
class GemeiteApi
{

    private $serverUrl = 'http://hub.gateares.com:18090';
    //private $serverUrl = 'http://szgemeite.vicp.cc:9080'; // test
    //private $serverUrl = 'http://gmt2015.oicp.net:9090'; // test

    private $partner = "1200000102";

    private $partnerKey = "7*XShYttwJhaMpoaC8YwQsPS5SbYLHquDlyn8lojpZPH0r*#ZjY_63VK_umF#iM3";

    const SUCCESS_CODE = 0;
    const REVOKED_CODE = 2;
    const CONFLICT_CODE = 3;
    const FAIL_CODE = 4;


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
     * 小区查询接口
     */
    public function commQuery()
    {
        $this->queryUrl = $this->makeServerUrl('commquery');
        $this->queryData = array(
            'sign' => $this->makeSign(),
            'input_charset' => 'UTF-8'
        );

        $communityList = array();

        $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result =  $this->resolveResult($result);
        if ($result) {
            $list = explode(',', $result['data']['list']);
            if (is_array($list)) {
                foreach ($list as $row) {
                    $temp = explode('/', $row);
                    if (isset($temp[0]) && isset($temp[1]))
                        $communityList[$row] = 'G - '.$temp[1];
                }
            }
        }

        //dump($communityList);
        return $communityList;
    }

    /**
     * 续费查询接口
     * @param $palteNo 车牌号
     * @param $commId  小区唯一编号
     * @param $attach  附加数据，原样返回
     * @return array
     */
    public function feeQuery($palteNo, $commId, $attach=null)
    {
        $params = array(
            'plate_no' => $palteNo,
            'comm_id' => $commId,
            'attach' => $attach
        );

        $this->queryUrl = $this->makeServerUrl('feequery');
        $this->queryData = array(
            'Params' => $this->json_encode_ex($params),
            'sign' => $this->makeSign($params),
            'input_charset' => 'UTF-8'
        );

        $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result =  $this->resolveResult($result);

        $parkingFees = array();
        if ($result) {
            $parkingFees['plate_no'] = $result['data']['plate_no'];
            $parkingFees['owner_name'] = $result['data']['owner_name'];
            $parkingFees['time_expired'] = $result['data']['time_expired'];
            $parkingFees['time_start'] = $result['data']['time_start'];
            $parkingFees['attach'] = $result['data']['attach'];
            $parkingFees['comm_id'] = $commId;

            $parkingFees['fee_unit_list'] = array();

            if (is_array($result['data']['fee_unitlist'])) {
                foreach ($result['data']['fee_unitlist'] as $row) {
                    $temp = explode('/', $row);
                    if (in_array($temp[2], array('月', '年'))) {
                        $feeUnit = array(
                            'id' => $temp[0],
                            'fees' => sprintf('%.2f', $temp[1] / 100),
                            'name' => $temp[2],
                            'unit_name' => '个'.$temp[2],
                            'unit' => array(1,2,3,4,6,12)
                        );

                        if ($temp[2] == '年') {
                            $feeUnit['unit_name'] = '年';
                            $feeUnit['unit'] = array(1,2,3);
                        }
                        $parkingFees['fee_unit_list'][] = $feeUnit;
                        unset($feeUnit);
                    }
                }
            }
        }

        return $parkingFees;
    }

    /**
     * 续费订单查询接口
     * @param $transactionId 订单流水号
     * @param $commId 小区唯一编号
     * @param $plateNo 车牌号
     * @param $feeUnitId 用户选择的缴费标准编号
     * @param $feeNumber 缴费数量
     * @param $timeStart 下一个支付开始时间
     * @param null $attach 附加数据，原样返回
     * @return mixed
     */
    public function orderQuery($transactionId, $commId, $plateNo, $feeUnitId, $feeNumber, $timeStart, $attach=null)
    {
        $params = array(
            'transaction_id' => $transactionId,
            'comm_id' => $commId,
            'plate_no' => $plateNo,
            'fee_unitid' => intval($feeUnitId),
            'fee_number' => intval($feeNumber),
            'time_start' => $timeStart,
            'attach' => $attach
        );

        $this->queryUrl = $this->makeServerUrl('orderquery');
        $this->queryData = array(
            'Params' => $this->json_encode_ex($params),
            'sign' => $this->makeSign($params),
            'input_charset' => 'UTF-8'
        );

        $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 支付系统完成支付通知
     * @param $transactionId
     * @param $commId
     * @param $plateNo
     * @param $feeUnitId
     * @param $feeNumber
     * @param $timeStart
     * @param $outTradeNo
     * @param $payTime
     * @return mixed
     */
    public function notify($transactionId, $commId, $plateNo, $feeUnitId, $feeNumber, $timeStart, $outTradeNo, $payTime)
    {
        $params = array(
            'trade_state' => 0,
            'transaction_id' => $transactionId,
            'out_trade_no' => $outTradeNo,
            'comm_id' => $commId,
            'plate_no' => $plateNo,
            'fee_unitid' => intval($feeUnitId),
            'fee_number' => intval($feeNumber),
            'time_start' => $timeStart,
            'time_end' => date('YmdHis',$payTime)
        );

        $this->queryUrl = $this->makeServerUrl('notify');
        $this->queryData = array(
            'Params' => $this->json_encode_ex($params),
            'sign' => $this->makeSign($params),
            'input_charset' => 'UTF-8'
        );

        $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 月卡
     * @param $method
     * @return string
     */
    private function makeServerUrl($method)
    {
        return $this->serverUrl.'/continuationpay/'.$this->partner.'/'.$method;
    }

    /**
     * 临停
     * @param $method
     * @return string
     */
    private function makeVisitorServerUrl($method)
    {
        return $this->serverUrl.'/common/parkingaccess/'.$this->partner.'/'.$method;
    }

    private function makeSign($params=null)
    {
        $signStr = '';
        if ($params) {
            $signStr = 'Params='.$this->json_encode_ex($params).'&';
        }
        $signStr .= 'input_charset=UTF-8&key='.$this->partnerKey;

        //dump($signStr);
        return strtoupper(md5($signStr));
    }

    private function resolveResult($resultStr)
    {
        if (is_string($resultStr)) {
            $data = json_decode(mb_substr($resultStr, 0, -39, 'utf-8'), true);
            $sign = mb_substr($resultStr, -33, -1); //json_decode(mb_substr($resultStr, -39), true);

            //dump($data);
            if ($data && isset($data['retcode'])) {
                if ( self::SUCCESS_CODE === intval($data['retcode']) ) {
                    $result['data'] = $data;
                    $result['sign'] = $sign;
                    return $result;
                }

                $errMsg = isset($data['retmsg']) ? $data['retmsg'] : '';
                Yii::log('【格美特】调用接口：'.$this->queryUrl.' 参数：'.json_encode($this->queryData).' 错误信息：'.$errMsg, CLogger::LEVEL_INFO, 'colourlife.core.api.GemeiteApi');

                return false;
            }

            $errMsg = '返回结果格式错误';
            Yii::log('【格美特】调用接口：'.$this->queryUrl.' 参数：'.json_encode($this->queryData).' 错误信息：'.$errMsg, CLogger::LEVEL_INFO, 'colourlife.core.api.GemeiteApi');

            return false;
        }
        Yii::log('【格美特】调用接口：'.$this->queryUrl.' 参数：'.json_encode($this->queryData).' 返回数据：'.json_encode($resultStr), CLogger::LEVEL_INFO, 'colourlife.core.api.GemeiteApi');
        return false;
    }

    private function json_encode_ex( $value)
    {
        if ( version_compare( PHP_VERSION,'5.4.0','<'))
        {
            $str = json_encode($value);
            $str =  preg_replace_callback(
                "#\\\u([0-9a-f]{4})#i",
                function( $matchs)
                {
                    return  iconv('UCS-2BE', 'UTF-8',  pack('H4',  $matchs[1]));
                },
                $str
            );
            return  $str;
        }
        else
        {
            return json_encode( $value, JSON_UNESCAPED_UNICODE);
        }
    }


    /***********临时停车**************/

    /**
     * 订单查询接口
     * @param $palteNo
     * @return bool
     */
    public function visitorOrderQuery($palteNo)
    {
        $params = array(
            'partner' => $this->partner,
            'plateNo' => $palteNo,
            'mer_gid' => ''
        );

        $this->queryUrl = $this->makeVisitorServerUrl('visitor_order_query');
        $this->queryData = array(
            'Params' => $this->json_encode_ex($params),
            'sign' => $this->makeSign($params),
            'input_charset' => 'UTF-8'
        );

        $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result =  $this->resolveResult($result);

        return $result;
    }

    /**
     * 临时停车支付订单通知
     * @param $palteNo
     * @param $totalFee
     * @return bool
     */
    public function visitorOrderNotify($palteNo, $totalFee, $transactionId, $outTradeNo, $timeEnd)
    {
        $params = array(
            'trade_mode' => '1',
            'trade_state' => '0',
            'pay_info' => '',
            'partner' => $this->partner,
            'plateNo' => $palteNo,
            'mer_gid' => '',
            'total_fee' => $totalFee,
            'fee_type' => '1',
            'transaction_id' => $transactionId,
            'out_trade_no' => $outTradeNo,
            'time_end' => $timeEnd
        );

        $this->queryUrl = $this->makeVisitorServerUrl('visitor_order_notify');
        $this->queryData = array(
            'Params' => $this->json_encode_ex($params),
            'sign' => $this->makeSign($params),
            'input_charset' => 'UTF-8'
        );
        //echo json_encode($queryData);exit;

        $result =  Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result =  $this->resolveResult($result);

        return $result;

    }

}