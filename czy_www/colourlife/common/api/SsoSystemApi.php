<?php

/**
 * Class SsoSystemApi
 *
 * Yii::import('common.api.SsoSystemApi');
 * $cloud = ColorCloudApi::getInstance();
 * $return = $cloud->callGetPayCommunity(....); // 带返回值和错误参数
 * $return = $cloud->getPayCommunity(); // 只有返回值，内部处理错误
 */

class SsoSystemApi
{
    const SsoCacheId = 'SsoSys';
    static protected $instance;
    protected $appSecret = 'SDFL#)@F';
    //protected $baseUrl = 'http://54.223.196.153:8081/'; //测试服务器地址
    protected $baseUrl = 'http://iceapi.colourlife.com:8081/';
    private $clientCode = 'czy';
    private $app_id = 'ICECZY00-F26F-42B8-988C-27F4AEE3292A';
    private $token = 'r9A0ZSn5b4jOSJEnGc3y';
    
    static public function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }

    protected function getCacheKey($key)
    {
        return self::SsoCacheId . $key;
    }

    protected function getCache($key)
    {
        $key = $this->getCacheKey($key);
        return Yii::app()->cache->get($key);
    }

    protected function setCache($key, $data)
    {
        $key = $this->getCacheKey($key);
        return Yii::app()->cache->set($key, $data, 3600);
    }

    public function json_encode($var)
    {
        switch (gettype($var)) {
            case 'boolean':
                return $var ? 'true' : 'false';

            case 'NULL':
                return 'null';

            case 'integer':
                return (int)$var;

            case 'double':
            case 'float':
                return str_replace(',', '.', (float)$var); // locale-independent representation

            case 'string':
                if (($enc = strtoupper(Yii::app()->charset)) !== 'UTF-8')
                    $var = iconv($enc, 'UTF-8', $var);
                // STRINGS ARE EXPECTED TO BE IN ASCII OR UTF-8 FORMAT
                $ascii = '';
                $strlen_var = strlen($var);
                /*
                 * Iterate over every character in the string,
                 * escaping with a slash or encoding to UTF-8 where necessary
                 */
                for ($c = 0; $c < $strlen_var; ++$c) {

                    $ord_var_c = ord($var{$c});

                    switch (true) {
                        case $ord_var_c == 0x08:
                            $ascii .= '\b';
                            break;
                        case $ord_var_c == 0x09:
                            $ascii .= '\t';
                            break;
                        case $ord_var_c == 0x0A:
                            $ascii .= '\n';
                            break;
                        case $ord_var_c == 0x0C:
                            $ascii .= '\f';
                            break;
                        case $ord_var_c == 0x0D:
                            $ascii .= '\r';
                            break;

                        case $ord_var_c == 0x22:
                        case $ord_var_c == 0x2F:
                        case $ord_var_c == 0x5C:
                            // double quote, slash, slosh
                            $ascii .= '\\' . $var{$c};
                            break;

                        default:
                            // 彩之云 API JSON DECODE 不支持 \u Unicode 字符串
                            $ascii .= $var{$c};
                            break;
                    }
                }

                return '"' . $ascii . '"';

            case 'array':
                // treat as a JSON object
                if (is_array($var) && count($var) && (array_keys($var) !== range(0, sizeof($var) - 1))) {
                    return '{' .
                    join(',', array_map(array($this, 'json_nameValue'),
                        array_keys($var),
                        array_values($var)))
                    . '}';
                }
                // treat it like a regular array
                return '[' . join(',', array_map(array($this, 'json_encode'), $var)) . ']';

            case 'object':
                if ($var instanceof Traversable) {
                    $vars = array();
                    foreach ($var as $k => $v)
                        $vars[$k] = $v;
                } else
                    $vars = get_object_vars($var);
                return '{' .
                join(',', array_map(array($this, 'json_nameValue'),
                    array_keys($vars),
                    array_values($vars)))
                . '}';

            default:
                return '';
        }
    }

    protected function json_nameValue($name, $value)
    {
        return $this->json_encode(strval($name)) . ':' . $this->json_encode($value);
    }

    //加密
    protected function encrypt($data)
    {
        $str = urlencode($data);
        $size = mcrypt_get_block_size(MCRYPT_DES, MCRYPT_MODE_CBC);
        $pad = $size - (strlen($str) % $size);
        $str .= str_repeat(chr($pad), $pad);

        $cipher = mcrypt_module_open(MCRYPT_DES, '', 'cbc', '');
        mcrypt_generic_init($cipher, $this->appSecret, $this->appSecret);
        $data = mcrypt_generic($cipher, $str); //$data = mcrypt_cbc(MCRYPT_DES, $this->appSecret, $str, MCRYPT_ENCRYPT, $this->appSecret);
        mcrypt_generic_deinit($cipher);
        return strtoupper(bin2hex($data));
    }

    // 5.4 以下版本没有 hex2bin
    protected function hex2bin($data)
    {
        $len = strlen($data);
        return pack('H' . $len, $data);
    }


    protected  function getData($url){
        $ch = curl_init($url ) ;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        $output = curl_exec($ch) ;
        return $output;
    }


    //解密
    protected function decrypt($str)
    {
        $str = $this->hex2bin($str);
        $cipher = mcrypt_module_open(MCRYPT_DES, '', 'cbc', '');
        mcrypt_generic_init($cipher, $this->appSecret, $this->appSecret);
        $str = mdecrypt_generic($cipher, $str); //$str = mcrypt_cbc(MCRYPT_DES, $this->appSecret, $str, MCRYPT_DECRYPT, $this->appSecret);
        mcrypt_generic_deinit($cipher);
        $pad = ord($str{strlen($str) - 1});
        if ($pad > strlen($str))
            return false;
        if (strspn($str, chr($pad), strlen($str) - $pad) != $pad)
            return false;
        return urldecode(substr($str, 0, -1 * $pad));
    }
    
    public function firstContact()
    {
    	$username = Yii::app()->user->id;
    	if (empty($username)){
    		$username = 'admin';
    	}
    	
    	$param = '';
        $timestamp = time();
        
        $sign = strtolower(md5($this->app_id.$timestamp.$this->token.'false'));

        $param ='appID='.$this->app_id.'&sign='.$sign.'&ts='.$timestamp;
        
        $sUrl = $this->baseUrl.'?username='.$username.'&clientCode='.$this->clientCode.'&'.$param;
        var_dump($sUrl); 
        
        $content = HttpClient::getHttpResponsePOST($sUrl, array());
       
		//返回的不是JSON格式的数据
		if(json_decode($content) === null)
		{
			return json_encode(array(				
					'code' => "9999",
					'message' => "访问授权服务器错误",
			));
		}
		$rtn = json_decode($content);
    }
    
    
	/**
     * （1）    后台测试管理地址http://mobile.colourlife.com/ 用户名admin密码123456
     * （2）    Http接口调用路径：http://mapi.colourlife.com
     * （3）    appSecret：接口加密私钥字符串 SDFL#)@F
     * （4）    sign：用appSecret与所有参数进行签名的结果
     *          参考淘宝的签名方式：http://open.taobao.com/doc/detail.htm?id=111
     * （5）    编码方式：utf-8。
     * （6）    接口调用参数格式
     *          数据Request格式为：
     *          http://jsonapi.xxx.com?Method=&Params=参数Json经Des加密后的字符串&Sign=签名字符串
     *          注：如果参数为空，则Params参数可以不传。
     *          接口调用Params参数格式（Json）：
     *          视实际情况而定，如获取地区登录接口:
     *          {"parentid":"0"}
     * （7）    返回结果说明
     *          返回结果统一为：
     *          {"verification":true|false,"total":总计数量,"data":[],error:错误信息}
     *          参数内容经3Des加密
     *          参数说明：
     *          Verification：等于true时验证通过，false时验证失败。
     *          total：返回数据总条目数
     *          data: 数据列表
     *          error:仅当Verification=true时error有效，会显示出错提示内容。否则该字段为null
     */
    public function ssoCall($aUrl, $params)
    {
        try {
        	usleep(50000);
        	//$params = $this->json_encode($params);
        	$bUrl = '';
        	if (!empty($params)){
        		foreach ($params as $k1 => $v1) {
        			$bUrl=$bUrl.'&'.$k1.'='.urlencode($v1);
        		}
        	}

        	$param = '';
        	$timestamp = time();
        
        	$sign = strtolower(md5($this->app_id.$timestamp.$this->token.'false'));

        	$param ='appID='.$this->app_id.'&sign='.$sign.'&ts='.$timestamp;
        	
        	///$sUrl = $this->baseUrl.'/v1/job/page?page='.$page.'&size='.$size.'&clientCode='.$this->clientCode.'&'.$param;
        	$sUrl = $this->baseUrl.$aUrl.'?'.$param;
        	
        	if (!empty($bUrl)){
        		$sUrl = $sUrl.$bUrl;
        	}
        	
        	$return = Yii::app()->curl->get($sUrl);
        	$rtn = CJSON::decode($return);
        	
        	if ((!empty($rtn)) && isset($rtn['code'])) {
	            $codei = $rtn['code'];
	            if (intval($codei) === 0){
	            	
	            	return $rtn;
	            }
	        }
            usleep(100000);
            
            $return = Yii::app()->curl->get($sUrl);
            $rtn = CJSON::decode($return);
            if ((!empty($rtn)) && isset($rtn['code'])) {
	            $codei = $rtn['code'];
	            if (intval($codei) === 0){
	            	
	            	return $rtn;
	            }
	        }
	        
    		usleep(200000);
    		$return = Yii::app()->curl->get($sUrl);
            $rtn = CJSON::decode($return);
            if ((!empty($rtn)) && isset($rtn['code'])) {
	            $codei = $rtn['code'];
	            if (intval($codei) === 0){
	            	
	            	return $rtn;
	            }
	        }
	        
	        //return false;
	        throw new CException('第三方平台有异常，'.$aUrl.'?'.$bUrl);

        } catch (CException $e) {
            //Yii::log(sprintf("调用链接：\"%s\"\n参数：\"%s\"\n出错信息：\"%s\"", $sUrl,  $params, $e->getMessage()), CLogger::LEVEL_ERROR, 'colourlife.core.api.SsoSystemApi');
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'colourlife.core.api.SsoSystemApi');
            return false;
        }
    }
    
	public function testCall($sUrl)
    {
        try {
        	if (empty($sUrl)){
        		return false;
        	}
        	
        	$return = Yii::app()->curl->get($sUrl);
        	$rtn = CJSON::decode($return);
        	
        	if ((!empty($rtn)) && isset($rtn['code'])) {
	            $codei = $rtn['code'];
	            if (intval($codei) === 0){
		            if (is_array($rtn['content'])) {
	         	 		return	$rtn['content'];
		            }
	            }
	        }
	        return false;
        } catch (CException $e) {
            var_dump($e->getMessage());
            return false;
        }
    }
    
	//没有参数的调用
    public function emptycall($aUrl)
    {
        try {
        	$param = '';
        	$timestamp = time();
        
        	$sign = strtolower(md5($this->app_id.$timestamp.$this->token.'false'));

        	$param ='appID='.$this->app_id.'&sign='.$sign.'&ts='.$timestamp;

        	$sUrl = $this->baseUrl.$aUrl.'?'.$param;
        	
        	$return = Yii::app()->curl->get($sUrl);
			return CJSON::decode($return);

        } catch (CException $e) {
            Yii::log(sprintf("调用链接：\"%s\"\n出错信息：\"%s\"", $sUrl,  $e->getMessage()), CLogger::LEVEL_ERROR, 'colourlife.core.api.SsoSystemApi');
            return false;
        }
    }
    
    /**
     * 公司员工职位列表
     * 接口名称：get.user.jobs
     * 参数列表：
     * 参数名称    必填参数    参数描述       示例
     * 无
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * job_name        职位名称
     *
     */
	public function getOAJobs()
    {
        $sUrl = 'v1/advanced/job';
        
        ///$return = $this->emptycall($sUrl);
        
		///$params = array('page' => 1,'size' => 100,);  
		$params = array();  
		    
        $return = $this->ssoCall($sUrl, $params);
        
        $data = array();
        if ($return !== false && is_array($return['content'])) {
            $data = $return['content'];
        }
        return $data;
    }

    
    /**
     * 获得事业部客户部经理信息
     * 接口名称：get.user.bm_manager
     * 参数列表：
     * 参数名称    必填参数    参数描述    示例
     * community_id    是       小区ID
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * userid          用户ID
     * username        用户姓名
     * moblie          手机
     * status          状态             
     * branch_id       事业部ID
     * branchName	        事业部	
     * community_id    小区ID
     * community	       小区
     * regionid        地区ID
     * region		        地区
     * job_name        职位
     */
	public function getBmManager($community_id)
    {
    	$sUrl = 'v1/advanced/manager';
        
    	$code = $this->getOACommunityCode($community_id);
    	
        if (empty($code)){
        	$params = array('community_id' => $community_id,);  
        }else{
        	$params = array('community_id' => $code,);  
        }
		    
        $return = $this->ssoCall($sUrl, $params);
        
		$data = array();
        if ($return !== false && is_array($return['content'])) {
            $data = $return['content'];
        }
        return $data;
    }

    protected function getOACommunityCode($community_id)
    {
        $code = "";

        if (empty($community_id)){
            return $code;
        }

        $connection = Yii::app()->db;

        $sql = "select * FROM colorcloud_community where color_community_id is not null and color_community_id <> '' and community_id='".$community_id."' limit 1";

        $result = $connection->createCommand($sql)->queryAll();

        if (count($result) > 0) {
            //$code = $result[0]['colorcloud_name'];
            $code = $result[0]['color_community_id'];
        }else{
            $sql = "select * FROM colorcloud_community where colorcloud_name is not null and colorcloud_name <> '' and community_id='".$community_id."' limit 1";
            $result = $connection->createCommand($sql)->queryAll();

            if (count($result) > 0) {
                $code = $result[0]['colorcloud_name'];
            }
        }

        return $code;
    }


//    protected function getOACommunityCode($community_id)
//    {
//    	$code = "";
//
//        if (empty($community_id)){
//        	return $code;
//        }
//
//        $connection = Yii::app()->db;
//        $sql = "select * FROM colorcloud_community where community_id='".$community_id."' ORDER BY color_community_id DESC limit 1";
//        $command = $connection->createCommand($sql);
//        $result = $command->queryAll();
//
//        if (count($result) > 0) {
//           //$code = $result[0]['colorcloud_name'];
//           $code = $result[0]['color_community_id'];
//        }
//        //$code='635bec60-03f0-455f-ba95-d11b09373421'; //community_id=1367
//        return $code;
//    }

    /**
     * 获得员工详细信息
     * 接口名称：get.user.employee
     * 参数列表：
     * 参数名称    必填参数    参数描述    示例
     * oa_username    是          员工的OA用户ID
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * userid          员工ID
     * username        客户经理姓名
     * moblie          手机
     * status          状态
     * branch_id       事业部ID
     * branchName	        事业部	
     * community_id    小区ID
     * community	       小区
     * regionid        地区ID
     * region		        地区
     * job_name        职位
     */
	public function getEmployee($oa_username)
    {
    	$sUrl = 'v1/advanced/employee';

		$params = array('username' => $oa_username,);  
		 
        $return = $this->ssoCall($sUrl, $params);
        
		$data = array();
        if ($return !== false && is_array($return['content'])) {
            $data = $return['content'];

            if(isSet($data[0]['community_id']) && isSet($data[0]['region_id']) && isSet($data[0]['branch_id'])){
	            if(empty($data[0]['community_id']) && empty($data[0]['region_id']) && empty($data[0]['branch_id'])){
	            	if (isSet($data[0]['familyid'])){
	            		$data[0]['branch_id'] = $data[0]['familyid'];
	            	}
	            	if (isSet($data[0]['familyname'])){
	            		$data[0]['branch'] = $data[0]['familyname'];
	            	}
	            }
            }else if(!isSet($data[0]['community_id']) && !isSet($data[0]['region_id']) && !isSet($data[0]['branch_id'])){
            	if (isSet($data[0]['familyid'])){
	            	$data[0]['branch_id'] = $data[0]['familyid'];
	            }
	            if (isSet($data[0]['familyname'])){
	            	$data[0]['branch'] = $data[0]['familyname'];
	            }
            }
        }
        return $data;
    }
    
    /**
     * 获得与此小区相关职位人信息
     * 接口名称：get.user.users4job
     * 参数列表：
     * 参数名称    必填参数    参数描述    示例
     * oa_username    	是           客户经理OA_ID
     * job_name		是          职位
     * community_id  否          小区ID
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * userid          用户ID
     * username        用户姓名
     * moblie          手机
     * status          状态             
     * branch_id       事业部ID
     * branchName	        事业部	
     * community_id    小区ID
     * community	       小区
     * regionid        地区ID
     * region		        地区
     * job_name        职位
     */
	public function getUser4Job($oa_username = '', $job_name = '', $community_id = '')
    {
    	$data = array();
    	if ((empty($oa_username)) || (empty($job_name))){
        	return $data;
        }
        
        $sUrl = 'v1/advanced/employees';

		$params = array('username' => $oa_username,'job_name' => $job_name,);  
		
    	if (!empty($community_id)){
    		
	    	$code = $this->getOACommunityCode($community_id);
	    	
	        if (empty($code)){
	        	$params['community_id'] = $community_id;  
	        }else{
	        	$params['community_id'] = $code;
	        }
        }
		    
        $return = $this->ssoCall($sUrl, $params);
        
		$data = array();
        if ($return !== false && is_array($return['content'])) {
            $data = $return['content'];
        }
        return $data;
    }

}
