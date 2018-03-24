<?php
set_time_limit(0);
class CloudControlElectricMeterApi
{
    static protected $instance;
    protected $baseUrl = 'http://a.80ct.com:8091/api/';
    protected $uuid='002ab3a0393946e28f3013edc783a6f9';
//    protected  $baseUrl = 'http://intple.gicp.net:8092/api/';
//    protected $uuid = '37b3bd458252452f9bf76f0a928abcf1';
    public $queryData;
    public $queryUrl;

    static public function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }



    /**
     * 请求参数
     * @return array
     */
    private function makeQueryData($para=null)
    {
        // $ts = time();
        // $d_time = date('Y');
        $paraComm =  array(
            // 'sign' => $this->makeSign($ts),
            // 'ts' => $ts,
            // 'appID' => $this->appID,
            // // 'd_ad' => $this->d_ad,
            // // 'd_time' => $d_time,
        );

        if ($para && is_array($para)) {
            $para = array_merge($paraComm, $para);
        }
        else {
            $para = $paraComm;
        }

        return $para;
    }




    /**
     * 生成请求地址
     * @param $method
     * @return string
     */
    private function makeServerUrl($method)
    {
        return $this->baseUrl.$method;
    }
    

    
    /**
     * 处理请求结果(all)
     * @param $result
     * @return bool|mixed     * 
     */
    //返回code说明
    //42440：正常返回 
    //42441：参数验证错误 
    //42442：接口执行错误 
    //42443：UUID 签名不正确或不存在 
    //42444：UUID 已过期 
    //42445：输入参数错误或为空
    private function resolveResultAll($result)
    {   
        $result = json_decode($result, true);
        //Yii::log('调用云控电表平台API接口：'.$this->queryUrl.' 参数：'.json_encode($this->queryData).' 返回信息：'.var_export($result, true), CLogger::LEVEL_INFO, 'colourlife.core.api.CloudControlElectricMeterApi.resolveResultAll');
        return $result;
    }





    /**
     * 获取设备列表
     * uuid  用户标识码
     * wipm_sn  设备号
     * wipm_state  设备状态 0:已断电 1:已通电 2:设备离线
     * current_electricity  当前电能(kWh)
     * current_power  当前功率(W)
     */
    public function callFacilityWipmList()
    {   

        $params = array(
            'uuid' => $this->uuid,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('facility/wipm_list.html');
        $result = Yii::app()->curl->get($this->queryUrl,$this->queryData);
        $result =  $this->resolveResultAll($result);
        return $result;
    }

    /**
     *单台设备信息获取
     *uuid  用户标识码
     *wipm_sn  设备号
     * wipm_state  设备状态 0:已断电 1:已通电 2:设备离线
     * current_electricity  当前电能(kWh)
     * current_power  当前功率(W)
     * nicename  设备备注
     * wipm_author  设备人
     */
    public function callWipmDetails($wipm_sn , $pay_unit_price=''){

        $params = array(
            'uuid' => $this->uuid,
            'wipm_sn' => $wipm_sn,
            'pay_unit_price' => $pay_unit_price,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('facility/wipm_details.html');
        $result = Yii::app()->curl->get($this->queryUrl,$this->queryData);
        $result = $this->resolveResultAll($result);
        return $result;
    }


    /**
     * 设备预存款充值
     * @param $uuid
     * @param $wipm_sn
     * @param $pay_price
     * @param $pay_unit_price
     * @return bool|mixed
     * uuid  用户标识码
     * stored_price  预存款余额
     * stored_number  预存电能(kWh)
     */
    public function callPayWipmStored($wipm_sn,$pay_price,$pay_order_number)
    {   

        $params = array(
            'uuid' => $this->uuid,
            'wipm_sn' => $wipm_sn,
            'pay_price' => $pay_price,
            'pay_unit_price' => 1,
            //'pay_order_number' => $pay_order_number,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('pay/wipm_stored.html');
        $result = Yii::app()->curl->post($this->queryUrl,$this->queryData);
        $result =  $this->resolveResultAll($result);
        return $result;
    }

    public function callPurchasePower($meter,$amount,$order_no){
        return $this->callPayWipmStored($meter,$amount,$order_no);
    }


    /**
     * 设备开关
     * @param $uuid
     * @param $wipm_sn
     * @param $operate
     * @return bool|mixed
     * uuid  用户标识码
     * wipm_state  设备状态 0:已断电 1:已通电 2:设备离线
     */
    public function callFacilityWipmSwitches($wipm_sn,$operate)
    {   

        $params = array(
            'uuid' => $this->uuid,
            'wipm_sn' => $wipm_sn,
            'operate' => $operate
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('facility/wipm_switches.html');
        $result = Yii::app()->curl->post($this->queryUrl,$this->queryData);
        $result =  $this->resolveResultAll($result);
        return $result;
    }

    /**
     * 设备信息编辑
     * @param $uuid
     * @param $wipm_sn
     * @param $wipm_author
     * @return bool|mixed
     * uuid  用户标识码
     */
    public function callWipmInfoedit( $wipm_sn, $wipm_author, $nick_name){
        $params = array(
            'uuid' => $this->uuid,
            'wipm_sn' => $wipm_sn,
            'wipm_author' => $wipm_author,
            'nick_name' => $nick_name,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('facility/wipm_infoedit.html');
        $result = Yii::app()->curl->post($this->queryUrl,$this->queryData);
        $result = $this->resolveResultAll($result);
        return $result;
    }

    
}