<?php
/* *
 * 类名：ChuanglanSmsApi
 * 功能：创蓝接口请求类
 * 详细：构造创蓝短信接口请求，获取远程HTTP数据
 */
class ChuanglanSmsApi {
    const SMS_BASE_URL = 'http://222.73.117.156/msg/HttpBatchSendSM';
    const API_BALANCE_QUERY_URL = 'http://222.73.117.156/msg/HttpBatchSendSM';
    const SMS_MOBILE_COUNT = 50000;
    static protected $instance;
    private $eprId, $userId, $passwd;
    private $padding = '【彩生活】';
    private $yuyinUserID='cshyuyin';  //语音账号
    private $yuyinPasswd='Tch778899';  //语音密码


    static public function getInstanceWithConfig($config)
    {
        if (!isset(self::$instance))
            self::$instance = new self($config);
        return self::$instance;
    }

    public function __construct($config)
    {
        $this->eprId = @$config['eprId'];
        $this->userId = @$config['userId'];
        $this->passwd = @$config['passwd'];
    }




    /**
     * 发送短信
     * @param string $mobile 手机号码
     * @param string $msg 短信内容
     * @param string $needstatus 是否需要状态报告
     * @param string $product 产品id，可选
     * @param string $extno   扩展码，可选
     */
    public function send( $mobile, $message, $needstatus = 'false', $extno = '') {
        $postArr = array (
            'account' => $this->userId,
            'pswd' => $this->passwd,
            'msg' =>  $message,
            'mobile' => $mobile,
            'needstatus' => $needstatus,
            'extno' => $extno
        );

        $return = Yii::app()->curl->post(self::SMS_BASE_URL , $postArr);
        //$return = $this->curlPost( self::SMS_BASE_URL , $postArr);
        if(substr($return, 0,3) == pack("CCC",0xef,0xbb,0xbf)) $return = substr($return, 3); //去掉BOM
        $return = explode(',',$return);
        if($return[1]==0){
            return $return;
        }else{
            return 1;
        }
    }
    
    /**
     * 发送语音
     * @param string $mobile 手机号码
     * @param string $msg 短信内容
     * @param string $needstatus 是否需要状态报告
     * @param string $product 产品id，可选
     * @param string $extno   扩展码，可选
     */
    public function sendYuYin( $mobile, $message, $needstatus = 'false', $extno = '') {
    	$postArr = array (
    			'account' => $this->yuyinUserID,
    			'pswd' => $this->yuyinPasswd,
    			'msg' =>  $message,
    			'mobile' => $mobile,
    			'needstatus' => $needstatus,
    			'extno' => $extno
    	);
    
    	$return = Yii::app()->curl->post(self::SMS_BASE_URL , $postArr);
    	//$return = $this->curlPost( self::SMS_BASE_URL , $postArr);
    	if(substr($return, 0,3) == pack("CCC",0xef,0xbb,0xbf)) $return = substr($return, 3); //去掉BOM
    	$return = explode(',',$return);
    	if($return[1]==0){
    		return $return;
    	}else{
    		return 1;
    	}
    }

    /**
     * 查询额度
     *
     *  查询地址
     */
    public function queryBalance() {
        global $chuanglan_config;
        //查询参数
        $postArr = array (
            'account' => $this->userId,
            'pswd' => $this->passwd,
        );
        $result = $this->curlPost(self::API_BALANCE_QUERY_URL, $postArr);
        return $result;
    }

    /**
     * 处理返回值
     *
     */
    public function execResult($result){
        $result=preg_split("/[,\r\n]/",$result);
        return $result;
    }

    /**
     * 通过CURL发送HTTP请求
     * @param string $url  //请求URL
     * @param array $postFields //请求参数
     * @return mixed
     */
    private function curlPost($url,$postFields){
        $postFields = http_build_query($postFields);
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }

    //魔术获取
    public function __get($name){
        return $this->$name;
    }

    //魔术设置
    public function __set($name,$value){
        $this->$name=$value;
    }
}
?>
