<?php

/**
 * Curl wrapper for Yii
 * v - 1.2
 * @author hackerone
 */
class Curl extends CComponent {

    private $_ch;
    // config from config.php
    public $options;
    // default config
    private $_config = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_AUTOREFERER => true,
        CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:5.0) Gecko/20110619 Firefox/5.0'
    );

    private function _exec($url) {

        $this->setOption(CURLOPT_URL, $url);
        $c = curl_exec($this->_ch);
        if (!curl_errno($this->_ch))
            return $c;
        else
            throw new CException(curl_error($this->_ch));
    }

    public function get($url, $params = array(),$isWait=true, $header = array()) {
        //echo $this->buildUrl($url, $params); exit;
        $this->setOption(CURLOPT_HTTPGET, true);
        $this->setOption(CURLOPT_HTTPHEADER, $header); // 设置HTTP头

        if($isWait===false){
            $this->setOption(CURLOPT_NOSIGNAL,1);
            $connect_timeout = isset(Yii::app()->params['curl_connect_time'])?intval(Yii::app()->params['curl_connect_time']):100;
            $timeout = isset(Yii::app()->params['curl_time_out'])?intval(Yii::app()->params['curl_time_out']):100;
            $this->setOption(CURLOPT_CONNECTTIMEOUT_MS,$connect_timeout);
            $this->setOption(CURLOPT_TIMEOUT_MS,$timeout);
        }

        try{
            return $this->_exec($this->buildUrl($url, $params));
        }catch (Exception $e){
            if($isWait===false){
                return true;
            }else{
                throw new Exception($e->getMessage());
            }
        }
    }

    public function post($url, $data = array(), $isWait=true, $header = array()) {
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
        $this->setOption(CURLOPT_HTTPHEADER, $header); // 设置HTTP头

        if($isWait===false){
            $this->setOption(CURLOPT_NOSIGNAL,1);
            $connect_timeout = isset(Yii::app()->params['curl_connect_time'])?intval(Yii::app()->params['curl_connect_time']):100;
            $timeout = isset(Yii::app()->params['curl_time_out'])?intval(Yii::app()->params['curl_time_out']):100;
            $this->setOption(CURLOPT_CONNECTTIMEOUT_MS,$connect_timeout);
            $this->setOption(CURLOPT_TIMEOUT_MS,$timeout);
        }

        try{
            return $this->_exec($url);
        }catch (Exception $e){
            if($isWait===false){
                return true;
            }else{
                throw new Exception($e->getMessage());
            }
        }
    }

    public function postWithHttpCode($url, $data = array(), $isWait=true, $header = array()) {
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, http_build_query($data));
        $this->setOption(CURLOPT_HTTPHEADER, $header); // 设置HTTP头

        if($isWait===false){
            $this->setOption(CURLOPT_NOSIGNAL,1);
            $connect_timeout = isset(Yii::app()->params['curl_connect_time'])?intval(Yii::app()->params['curl_connect_time']):100;
            $timeout = isset(Yii::app()->params['curl_time_out'])?intval(Yii::app()->params['curl_time_out']):100;
            $this->setOption(CURLOPT_CONNECTTIMEOUT_MS,$connect_timeout);
            $this->setOption(CURLOPT_TIMEOUT_MS,$timeout);
        }

        try{
            $result = $this->_exec($url);
            $httpCode = curl_getinfo($this->_ch,CURLINFO_HTTP_CODE );

            return array('httpCode'=>$httpCode,'content'=>$result);
        }catch (Exception $e){
            if($isWait===false){
                return true;
            }else{
                throw new Exception($e->getMessage());
            }
        }
    }

    public function postFile($target_url, $fileOption = array() ,$data = array(), $isWait=true, $header = array()){
        if (function_exists('curl_file_create')) { // php 5.5+
            $fileInfo['file'] = curl_file_create($fileOption['path']);
        } else { //
            $fileInfo['file'] = '@' . $fileOption['path'];
        }

        //$fileInfo['file'] = new CurlFile($fileOption['path'], $fileOption['mime']);
        //$fileInfo['file'] = '@'.$fileOption['path'];
        $postData = array_merge($data, $fileInfo);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$target_url);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $result = curl_exec($ch);
        curl_close ($ch);
        return $result;
    }

    public function postJson($url, $data_string) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data_string))
        );
        if(0 === strpos(strtolower($url), 'https')) {
	        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();

        return $return_content;
        //$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //return array($return_code, $return_content);
    }

    public function put($url, $data, $params = array()) {

        // write to memory/temp
        $f = fopen('php://temp', 'rw+');
        fwrite($f, $data);
        rewind($f);

        $this->setOption(CURLOPT_PUT, true);
        $this->setOption(CURLOPT_INFILE, $f);
        $this->setOption(CURLOPT_INFILESIZE, strlen($data));
        return $this->_exec($this->buildUrl($url, $params));
    }


    /*
     * 新增put提交方法
     */
    public function putNew($url, $type = 'GET', $option = array(), $header = array())
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)'); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); // 设置HTTP头
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); // 设置HTTP头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);

        if($type != 'GET' && !empty($option))
        {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($option));
        }

        $result = curl_exec($curl); // 执行操作
        return $result;
        curl_close ($curl); // 关闭CURL会话
    }

    public function buildUrl($url, $data = array()) {
        $parsed = parse_url($url);
        isset($parsed['query']) ? parse_str($parsed['query'], $parsed['query']) : $parsed['query'] = array();
        $params = isset($parsed['query']) ? array_merge($parsed['query'], $data) : $data;
        $parsed['query'] = ($params) ? '?' . http_build_query($params) : '';
        if (!isset($parsed['path']))
            $parsed['path'] = '/';

        $port = '';
        if(isset($parsed['port'])){
            $port = ':' . $parsed['port'];
        }

        return $parsed['scheme'] . '://' . $parsed['host'] .$port. $parsed['path'] . $parsed['query'];
    }

    public function setOptions($options = array()) {
        curl_setopt_array($this->_ch, $options);
        return $this;
    }

    public function setOption($option, $value) {
        curl_setopt($this->_ch, $option, $value);
        return $this;
    }

    // initialize curl
    public function init() {
        try {
            $this->_ch = curl_init();
            $options = is_array($this->options) ? ($this->options + $this->_config) : $this->_config;
            $this->setOptions($options);

            $ch = $this->_ch;

            // close curl on exit
            Yii::app()->onEndRequest = function() use(&$ch) {
                curl_close($ch);
            };
        } catch (Exception $e) {
            throw new CException('Curl not installed');
        }
    }

}