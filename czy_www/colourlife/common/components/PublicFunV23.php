<?php
/*
 * 后台图片连接
 * 判断 upload地址或statics中的v23地址
 * @update validateURL 验证url地址的合法性
 * 2015.3.10
 */
class PublicFunV23 {

    public $typeConnect = 'curl';
    //提交方式
    public $method = 'POST';
    //端口
    public $port = '';

    //private $url = 'http://colour.kakatool.cn';//http://caizhiyun.kakatool.com:8080

    /*
     * 可配置化 上传图片
     */
    public function setAbleUploadImg($url) {
        //$test = new AjaxUploadImage();
        $ch = curl_init();
        $timeout = 10;
        if (strstr($url, 'http://')) {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $contents = curl_exec($ch);
            if (preg_match("/404/", $contents)) {
                //echo '文件不存在';
                $url = F::getStaticsUrl('/common/images/nopic.png');
            }
            return $url;
        } elseif (strstr($url, 'v23')) {
            $url = F::getStaticsUrl('/common/' . $url);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $contents = curl_exec($ch);
            if (preg_match("/404 Not Found/", $contents)) {
                //echo '文件不存在';
                $url = F::getStaticsUrl('/common/images/nopic.png');
            }
        }
		elseif (strstr($url, 'v30')) {
			$url = F::getStaticsUrl('/common/' . $url);
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$contents = curl_exec($ch);
			if (preg_match("/404 Not Found/", $contents)) {
				//echo '文件不存在';
				$url = F::getStaticsUrl('/common/images/nopic.png');
			}
		}else
            $url = Yii::app()->ajaxUploadImage->getUrl($url);
        return $url;
    }
    
    /*
     * @param $url 等验证的字符串
     * @return bool
     */
    public function validateURL($URL) {
        $pa = '/^http[s]?:\/\/'.  
                '(([0-9]{1,3}\.){3}[0-9]{1,3}'. // IP形式的URL- 199.194.52.184  
                '|'. // 允许IP和DOMAIN（域名）  
                '([0-9a-z_!~*\'()-]+\.)*'. // 域名- www.  
                '([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.'. // 二级域名  
                '[a-z]{2,6})'.  // first level domain- .com or .museum  
                '(:[0-9]{1,4})?'.  // 端口- :80  
                '((\/\?)|'.  // a slash isn't required if there is no file name  
                '(\/[0-9a-zA-Z_!~\'
            \.;\?:@&=\+\$,%#-\/^\*\|]*)?)$/';
        if (preg_match($pa, $URL)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 数组转化为字符串
     */

    public function arrayToString($array = null) {
        $str = '';
        if ($array) {
            foreach ($array as $k => $v) {
                if (empty($v))
                    continue;
                $str .= "&{$k}={$v}";
            }
            $str = trim($str, '&');
        }
        return $str;
    }

    /*
     * 数组转化为字符串(微商圈)
     */
    public function arrayToString1($array = null) {
        $str = '';
        if ($array) {
            foreach ($array as $k => $v) {
//                if (empty($v) && $v !=='0' && $v == null)
//                    continue;
                $str .= "&{$k}={$v}";
            }
            $str = trim($str, '&');
        }
        return $str;
    }

    /*
     * 远程连接
     * @param:server_url 远程地址
     * @param:post 数组参数
     * @return 返回结果
     */
    public function contentMethod($server_url, $post = array()) {
        if (!$server_url || !is_array($post))
            return json_encode(array('result' => 004, 'reason' => '传参数有误'));
        $post = http_build_query($post);
        //远程curl连接
        if ($this->typeConnect == 'curl') {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $server_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
            //端口
            $this->port && curl_setopt($ch, CURLOPT_PORT, $this->port);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method); //设置请求方式
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-HTTP-Method-Override: $this->method")); //设置HTTP头信息
            if(0 === strpos(strtolower($server_url), 'https')) {
            	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            }
            $this->method == 'POST' && curl_setopt($ch, CURLOPT_POSTFIELDS, $post); //设置提交的字符串
            $file = curl_exec($ch); //执行预定义的CURL
            curl_close($ch);
        } else {
            //远程file_get_contents连接 暂没用
            //$post = http_build_query($post, '', '&');/
            $post = $this->arrayToString($post);
            //ini_set('allow_url_fopen', 'on');
            //ini_set('user_agent', 'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.0)');
            $opts = array(
                'http' => array(
                    'method' => $this->method,
                    'header' => "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)\r\nContent-type: application/x-www-form-urlencoded",
                    'timeout' => 120,
                    'content' => $post
                )
            );
            $context = stream_context_create($opts);
            // Open the file using the HTTP headers set above
            $file = file_get_contents($server_url, false, $context);
        }
        if ($file) {
            $utf = mb_detect_encoding($file);
            $utf == 'UTF-8' || $file = iconv('GBK', 'UTF-8', $file);
            if (strstr($file, 'Line Number:'))
                $file = json_encode(array('result' => 002, 'reason' => '远程服务服器错误'));
        } else{
            $file = json_encode(array('result' => 001, 'reason' => '远程接口读取失败'));
        }
        return $file;
    }

    
    /*
     * 判断浏览器的头（ios|android,wp,web)
     * @update 2005-06-04
     * @by 函数命名来自青林的灵感
     * @return $act
     */
    public function getRequestSource($check = FALSE){
        $userAgent = Yii::app()->request->userAgent;
        if (preg_match("/(iPod|iPad|iPhone)/", $userAgent))
        {
            $act = 'ios'; //IOS客户端
        }
        elseif (preg_match("/WP/", $userAgent))
        {
            $act = 'wp'; //WinPhone客户端
        }
        elseif (preg_match("/android/i", $userAgent)) {
            $act = 'android'; //android客户端
        } else {
            $act = 'web';
            if ($check === true){
                //不能从网页端下单
                throw new CHttpException(400, '不能从web端调用接口');
            }
        }
        return $act;
    }

}
