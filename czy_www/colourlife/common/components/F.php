<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class F
{

    static public function hello()
    {
        echo 'hello';
    }

    static public function rand_color()
    {
        $d = '';
        for ($a = 0; $a < 6; $a++) { //采用#FFFFFF方法，
            $d .= dechex(rand(0, 15)); //累加随机的数据--dechex()将十进制改为十六进制
        }
        return '#' . $d;
    }

    /**
     * 语言翻译
     *
     * @param unknown_type $message
     * @param unknown_type $category
     * @param unknown_type $params
     * @param unknown_type $source
     * @param unknown_type $language
     * @return unknown
     */
    static public function t($message, $category = 'Default', $params = array(), $source = null, $language = null)
    {
        return Yii::t($category, $message, $params, $source, $language);
        //t('hello','xx');xx是语言包
    }

    
    /**
     * +----------------------------------------------------------
     * 字符串截取，支持中文和其他编码
     * +----------------------------------------------------------
     * @static
     * @access public
     * +----------------------------------------------------------
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     * +----------------------------------------------------------
     * @return string
    +----------------------------------------------------------
     */
    static public function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
    {
        if (function_exists("mb_substr"))
            return mb_substr($str, $start, $length, $charset);
        elseif (function_exists('iconv_substr')) {
            return iconv_substr($str, $start, $length, $charset);
        }
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
        if ($suffix)
            return $slice . "…";
        return $slice;
    }

    //按日期生成目录
    static public function do_mkdir($day_file, $path)
    {
        $dir_array = explode("-", $day_file);
        if (count($dir_array) == 3) {
            $dir_1 = $path . '/' . $dir_array[0];
            $dir_2 = $dir_1 . "/" . $dir_array[1];
            $dir_3 = $dir_2 . "/" . $dir_array[2];
            $dir_file_array = array($dir_1, $dir_2, $dir_3);
            $dir_file_array = array();
            $dir_str = $path . '/';
            for ($i = 0; $i < count($dir_array); $i++) {
                $dir_str .= $dir_array[$i] . "/";
                array_push($dir_file_array, $dir_str);
            }
            for ($i = 0; $i < count($dir_file_array); $i++) {
                $dir_file = $dir_file_array[$i];
                if (file_exists($dir_file)) {
                    continue;
                } else {
                    //                    echo $dir_file;
                    //                    exit;
                    mkdir($dir_file, 0777);
                    chmod($dir_file, 0777);
                }
            }
            return $dir_file_array[count($dir_file_array) - 1];
        } else {
            echo "error!!!!!!!!!";
            exit;
        }
    }

    /**
     * 得到新订单号
     * @return  string
     */
    static public function get_order_id()
    {
        /* 选择一个随机的方案 */
        mt_srand((double)microtime() * 1000000);
        return date('Ymd') . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    static public function script($file)
    {
        Yii::app()->getClientScript()->registerScriptFile($file);
    }

    /**
     * 直接写script
     *
     * @param unknown_type $file
     */
    static public function rgScript($id, $script)
    {
        Yii::app()->getClientScript()->registerScript($id, $script);
    }

    static public function check2Array($array)
    {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                return true;
            }
        }
        return false;
    }

    static public function isfile($url)
    {
        $isfilestr = '';
        $isfile = get_headers($url);
        foreach ($isfile as $str) {
            $isfilestr .= $str;
        }
        $pos = strpos($isfilestr, "Content-Type: image/");
        if ($pos > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    /* set a flash message to display after the request is done */

    static public function setFlash($message)
    {
        Yii::app()->user->setFlash('mysite', $message);
    }

    static public function hasFlash()
    {
        return Yii::app()->user->hasFlash('mysite');
    }

    /* retrieve the flash message again */

    static public function getFlash()
    {
        if (Yii::app()->user->hasFlash('mysite')) {
            return Yii::app()->user->getFlash('mysite');
        }
    }

    static public function renderFlash()
    {
        if (Yii::app()->user->hasFlash('mysite')) {
            echo '<div class="errorSummary">';
            echo F::getFlash();
            echo '</div>';
            Yii::app()->clientScript->registerScript('fade', "
setTimeout(function() { $('.errorSummary').fadeOut('slow'); }, 5000);
");
        }
    }

    /**
     * 无限级分类，显示
     * @$arr 数组
     * @$id id
     * @$show_curent 是否显示当然分类
     * @$pid 分类上级id
     * @$name 显示名
     */
    static public function toTree($arr, $selected = '', $id = 'id', $pid = 'pid', $name = 'title', $span = 1)
    {
        $str = $select = '';
        foreach ($arr as $rs) {
            if ($rs->$pid == 0) {
                if ($selected == $rs->$id)
                    $select = "selected='selected'";
                //echo $select;exit;
                $str .= "<option value='" . $rs->$id . "' $select >" . F::t($rs->$name) . "</option>";
                $str .= self::toTreeHelper($arr, $selected, $rs->$id, $pid, $id, $name, $span);
            }
        }
        return $str;
    }

    //辅助生成tree
    static public function toTreeHelper($arr, $selected, $value, $pid, $id, $name, $span)
    {
        $array = array();
        $string = '|';
        $str = $select = '';
        for ($i = 0; $i < $span; $i++) {
            $string .= '---';
        }
        foreach ($arr as $rs) {
            if ($value == $rs->$pid) {
                if ($selected == $rs->$id)
                    $select = "selected='selected'";
                $str .= "<option value='" . $rs->$id . "' $select>" . $string . F::t($rs->$name) . "</option>";
                if (self::toTreeHelper($arr, $selected, $rs->$id, $pid, $id, $name, $span))
                    $str .= self::toTreeHelper($arr, $selected, $rs->$id, $pid, $id, $name, $span + 1);
            }
        }
        return $str;
    }

    /**
     * 所多维数组 转成一维数组
     *
     * @param unknown_type $array
     * @return unknown
     */
    static public function array_values_one($array)
    {
        $arrayValues = array();
        $i = 0;
        foreach ($array as $key => $value) {
            if (is_scalar($value) or is_resource($value)) {
                $arrayValues[$key] = $value;
            } elseif (is_array($value)) {

                $arrayValues = array_merge($arrayValues, self::array_values_one($value));
            }
        }

        return $arrayValues;
    }

    /**
     * 函数名： deleteDir
     * 功 能： 递归地删除指定目录
     * 参 数： $dir 目录
     * 返回值： 无
     */
    static public function deleteDir($dir)
    {
        if ($items = glob($dir . "/*")) {
            foreach ($items as $obj) {
                is_dir($obj) ? deleteDir($obj) : unlink($obj);
            }
        }
        rmdir($dir);
    }

    static public function listFile($dir)
    {
        $fileArray = array();
        $cFileNameArray = array();
        if ($handle = opendir($dir)) {
            while (($file = readdir($handle)) !== false) {
                if ($file != "." && $file != "..") {
                    if (is_dir($dir . "\\" . $file)) {
                        $cFileNameArray = self::listFile($dir . "\\" . $file);
                        for ($i = 0; $i < count($cFileNameArray); $i++) {
                            $fileArray[] = $cFileNameArray[$i];
                        }
                    } else {
                        $fileArray[] = $file;
                    }
                }
            }

            return $fileArray;
        } else {
            echo "listFile出错了";
        }
    }

    //以下四个函数为必须函数。
    static public function sub($content, $maxlen = 300, $show = false, $f = '……')
    {
        //把字符按HTML标签变成数组。
        $content = preg_split("/(<[^>]+?>)/si", $content, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $wordrows = 0; //中英字数
        $outstr = ""; //生成的字串
        $wordend = false; //是否符合最大的长度
        $beginTags = 0; //除<img><br><hr>这些短标签外，其它计算开始标签，如<div*>
        $endTags = 0; //计算结尾标签，如</div>，如果$beginTags==$endTags表示标签数目相对称，可以退出循环。
        //print_r($content);
        foreach ($content as $value) {
            if (trim($value) == "")
                continue; //如果该值为空，则继续下一个值
            if (strpos(";$value", "<") > 0) {
                //如果与要载取的标签相同，则到处结束截取。
                if (trim($value) == $maxlen) {
                    $wordend = true;
                    continue;
                }
                if ($wordend == false) {
                    $outstr .= $value;
                    if (!preg_match("/<img([^>]+?)>/is", $value) && !preg_match("/<param([^>]+?)>/is", $value) && !preg_match("/<!([^>]+?)>/is", $value) && !preg_match("/<br([^>]+?)>/is", $value) && !preg_match("/<hr([^>]+?)>/is", $value)) {
                        $beginTags++; //除img,br,hr外的标签都加1
                    }
                } else if (preg_match("/<\/([^>]+?)>/is", $value, $matches)) {
                    $endTags++;
                    $outstr .= $value;
                    if ($beginTags == $endTags && $wordend == true)
                        break; //字已载完了，并且标签数相称，就可以退出循环。
                } else {
                    if (!preg_match("/<img([^>]+?)>/is", $value) && !preg_match("/<param([^>]+?)>/is", $value) && !preg_match("/<!([^>]+?)>/is", $value) && !preg_match("/<br([^>]+?)>/is", $value) && !preg_match("/<hr([^>]+?)>/is", $value)) {
                        $beginTags++; //除img,br,hr外的标签都加1
                        $outstr .= $value;
                    }
                }
            } else {
                if (is_numeric($maxlen)) { //截取字数
                    $curLength = self::getStringLength($value);
                    $maxLength = $curLength + $wordrows;
                    if ($wordend == false) {
                        if ($maxLength > $maxlen) { //总字数大于要截取的字数，要在该行要截取
                            $outstr .= self::subString($value, 0, $maxlen - $wordrows);
                            $wordend = true;
                        } else {
                            $wordrows = $maxLength;
                            $outstr .= $value;
                        }
                    }
                } else {
                    if ($wordend == false)
                        $outstr .= $value;
                }
            }
        }

        //循环替换掉多余的标签，如<p></p>这一类
        while (preg_match("/<([^\/][^>]*?)><\/([^>]+?)>/is", $outstr)) {
            $outstr = preg_replace_callback("/<([^\/][^>]*?)><\/([^>]+?)>/is", array('F', "strip_empty_html"), $outstr);
        }
        //把误换的标签换回来
        if (strpos(";" . $outstr, "[html_") > 0) {
            $outstr = str_replace("[html_&lt;]", "<", $outstr);
            $outstr = str_replace("[html_&gt;]", ">", $outstr);
        }
        //echo htmlspecialchars($outstr);
        if (true == $show && true == $wordend) {
            $outstr .= $f;
        }
        return $outstr;
    }

    //取得字符串的长度，包括中英文。
    static public function getStringLength($text)
    {
        if (function_exists('mb_substr')) {
            $length = mb_strlen($text, 'UTF-8');
        } elseif (function_exists('iconv_substr')) {
            $length = iconv_strlen($text, 'UTF-8');
        } else {
            preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);
            $length = count($ar[0]);
        }
        return $length;
    }

    /*     * *********按一定长度截取字符串（包括中文）******** */

    static public function subString($text, $start = 0, $limit = 12)
    {
        if (function_exists('mb_substr')) {
            $more = (mb_strlen($text, 'UTF-8') > $limit) ? TRUE : FALSE;
            $text = mb_substr($text, 0, $limit, 'UTF-8');
            return $text;
        } elseif (function_exists('iconv_substr')) {
            $more = (iconv_strlen($text, 'UTF-8') > $limit) ? TRUE : FALSE;
            $text = iconv_substr($text, 0, $limit, 'UTF-8');
            //return array($text, $more);
            return $text;
        } else {
            preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $text, $ar);
            if (func_num_args() >= 3) {
                if (count($ar[0]) > $limit) {
                    $more = TRUE;
                    $text = join("", array_slice($ar[0], 0, $limit));
                } else {
                    $more = FALSE;
                    $text = join("", array_slice($ar[0], 0, $limit));
                }
            } else {
                $more = FALSE;
                $text = join("", array_slice($ar[0], 0));
            }
            return $text;
        }
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    static public function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    //去掉多余的空标签
    static public function strip_empty_html($matches)
    {
        $arr_tags1 = explode(" ", $matches[1]);
        if ($arr_tags1[0] == $matches[2]) { //如果前后标签相同，则替换为空。
            return "";
        } else {
            $matches[0] = str_replace("<", "[html_&lt;]", $matches[0]);
            $matches[0] = str_replace(">", "[html_&gt;]", $matches[0]);
            return $matches[0];
        }
    }

    /**
     * 获得当前格林威治时间的时间戳
     *
     * @return  integer
     */
    static public function gmtime()
    {
        return (time() - date('Z'));
    }

    /**
     * 产生随机字符
     */
    public static function random($length, $numeric = 0)
    {
        mt_srand();
        $seed = base_convert(md5(print_r($_SERVER, 1) . microtime()), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed) . '012340567890') : ($seed . 'zZ' . strtoupper($seed));
        $hash = '';
        $max = strlen($seed) - 1;
        for ($i = 0; $i < $length; $i++)
            $hash .= $seed[mt_rand(0, $max)];
        return $hash;
    }

    /**
     * 产生随机字符
     */
    public static function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }

        return $str;
    }

    public static function getApiUrl($path = '/')
    {
        return 'http://' . API_DOMAIN . $path;
    }

    public static function getShopBackendUrl($path = '/')
    {
        return 'http://' . SHOPBACKEND_DOMAIN . $path;
    }

    public static function getStaticsUrl($path = '/')
    {
        return 'https://' . STATICS_DOMAIN . $path;
    }

    public static function getStaticsPath($path = '/')
    {
        return dirname(dirname(__FILE__) . '/../../statics') . '/statics' . $path;
    }

    public static function getUploadsUrl($path = '/')
    {
        return 'http://' . UPLOADS_DOMAIN . $path;
    }

    public static function getUploadsPath($path = '/')
    {
        return dirname(str_replace(dirname(__FILE__), '\/', '\\') . '/../../uploads') . '/uploads' . $path;
        //return str_replace('common/components/F.php', '', str_replace('\\', '/', __FILE__).'uploads'. $path);
    }

    public static function getShopFrontendUrl($name = '', $path = '/')
    {
        return 'http://' . $name . SHOP_DOMAIN . $path;
    }

    public static function getFrontendUrl($name = '', $path = '/')
    {
        if ($name != '')
            return 'http://' . $name . COMMUNITY_DOMAIN . $path;
        else
            return self::getHomeUrl();

    }

    //跳转到小区的首页，没有找到就直接跳转到总站首页
    public static function getCommunityUrl($path = '/')
    {
        $domain = self::getCommunityDomain();
        if (!empty($domain))
            return self::getFrontendUrl($domain, $path);
        else
            return self::getHomeUrl($path);
    }

    public static function getCommunityDomain()
    {
        $community_id = self::getCookie('community_id');
        $community = Community::model()->enabled()->findByPk(intval($community_id));
        if (!empty($community) && !empty($community->domain))
            return strtolower($community->domain);
        else
            return NULL;
    }

    public static function getPassportUrl($path = '/')
    {
        return 'http://' . PASSPORT_DOMAIN . $path;
    }

    public static function getCyzUrl($path = '/')
    {
        return 'http://' . CUSTOMERBACKEND_DOMAIN . $path;
    }

    public static function getOrderUrl($path = '/')
    {
        return 'http://' . ORDER_DOMAIN . $path;
    }

    public static function getHomeUrl($path = '/')
    {
        return 'http://' . HOME_DOMAIN . $path;
    }

    public static function getCwyUrl($path = '/')
    {
        return "http://" . BACKEND_DOMAIN . $path;
    }


    public static function getSsoUrl($path = '/')
    {
        return "http://" . SSO_DOMAIN . $path;
    }



    public static function getIOSPushDeployStatus()
    {
        return Yii::app()->params['ios_push_deploy_status'];
    }

    public static function getJmUrl($path = '/')
    {
        return 'http://' . LEAGUE_DOMAIN . $path;
    }

    public static function getBrackUrl($path = '/')
    {
        return 'http://' . BACKEND_DOMAIN . $path;
    }

    public static function getPurchaseUrl($path = '/')
    {
        return 'http://' . PURCHASE_DOMAIN . $path;
    }

    public static function getCPAUrl($path = '/')
    {
        return 'http://' . CPA_DOMAIN . $path;
    }

    public static function getMUrl($path = '/')
    {
        return 'http://' . M_DOMAIN . $path;
    }

    public static function getAdminUrl($path = '/')
    {
        return 'http://' . ADMIN_DOMAIN . $path;
    }

    /**
     * 读取COOKIE
     */
    public static function getCookie($name)
    {
        return empty(Yii::app()->request->cookies[$name]) ? NULL : Yii::app()->request->cookies[$name]->value;
    }

    /**
     * 设置COOKIE
     */
    public static function setCookie($name, $value, $expire = 0)
    {
        $cookie = new CHttpCookie($name, $value);
        $cookie->expire = null === $expire ? 0 : $expire; //浏览器生命周期
        $cookie->domain = (strrpos(BASE_DOMAIN, ':') > 0) ? substr(BASE_DOMAIN, 0, strrpos(BASE_DOMAIN, ':')) : BASE_DOMAIN; //去掉端口  //有限期30天
        $cookie->path = '/';
        Yii::app()->request->cookies[$name] = $cookie;
    }

    /**
     *销毁cookie
     */
    public static function removeCookie($name)
    {
        unset(Yii::app()->request->cookies[$name]);
    }

    /**
     * 最近时间
     */
    public static function getDatetimeSelectOptions()
    {
        $lastWeek = '>=' . strval((intval(time() / 86400) - 7) * 86400);
        $lastMonth = '>=' . strval((intval(time() / 86400) - 30) * 86400);
        $ret = array($lastWeek => '最近七天', $lastMonth => '最近一个月');
        return $ret;
    }

    /**
     * 把对象转换成数组
     * @param   object $object 要转换的对象
     * @return  void
     */
    public static function _objectToArray($object)
    {
        $result = array();
        $object = is_object($object) ? get_object_vars($object) : $object;
        foreach ($object as $key => $val) {
            $val = (is_object($val) || is_array($val)) ? self::_objectToArray($val) : $val;
            $result[$key] = $val;
        }
        return $result;
    }

    /**
     * 通过传过来的ID，求父类 直到最上面一层
     * @param int $area_id 地区ID
     * @return array
     */
    public static function getRegion($area_id, $region = null)
    {
        //$region =null;
        $connection = Yii::app()->db;
        if ($area_id > 0) {
            $sql = "SELECT * FROM `region` WHERE id=" . $area_id . " ORDER BY id DESC";
            $command = $connection->createCommand($sql);
            $result = $command->queryAll();
            // var_dump($result);
            if (!empty($result[0])) {
                /* @var $result type */
                $region[] = $result[0]["name"];
                //echo $result[0]["name"]."<br/>";
                $region = F::getRegion($result[0]["parent_id"], $region);
            }
        }
        if (is_array($region))
            krsort($region);
        return ($region);
    }

    /**
     * 这个月的第一天
     * @$timestamp ，某个月的某一个时间戳，默认为当前时间
     * @is_return_timestamp ,是否返回时间戳，否则返回时间格式
     */
    public static function month_firstday($timestamp = 0, $is_return_timestamp = true)
    {
        static $cache;
        $id = $timestamp . $is_return_timestamp;
        if (!isset($cache[$id])) {
            if (!$timestamp) $timestamp = time();
            $firstday = date('Y-m-d', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
            if ($is_return_timestamp) {
                $cache[$id] = strtotime($firstday);
            } else {
                $cache[$id] = $firstday;
            }
        }
        return $cache[$id];
    }

    /***
     * 这个月的最后一天
     *  @$timestamp ，某个月的某一个时间戳，默认为当前时间
     * @is_return_timestamp ,是否返回时间戳，否则返回时间格式
     */
    public static function month_lastday($timestamp = 0, $is_return_timestamp = true)
    {
        static $cache;
        $id = $timestamp . $is_return_timestamp;
        if (!isset($cache[$id])) {
            if (!$timestamp) $timestamp = time();
            $lastday = date('Y-m-d', mktime(0, 0, 0, date('m', $timestamp), date('t', $timestamp), date('Y', $timestamp)));
            if ($is_return_timestamp) {
                $cache[$id] = strtotime($lastday);
            } else {
                $cache[$id] = $lastday;
            }
        }
        return $cache[$id];
    }

    /**
     * 计算上一个月的今天，如果上个月没有今天，则返回上一个月的最后一天
     * @param init $time
     * @return type
     */
    public static function last_month_today($time)
    {
        $last_month_time = mktime(date("G", $time), date("i", $time),
            date("s", $time), date("n", $time), 0, date("Y", $time));
        $last_month_t = date("t", $last_month_time);
        if ($last_month_t < date("j", $time)) {
            return date("Y-m-t H:i:s", $last_month_time);
        }
        return date(date("Y-m", $last_month_time) . "-d", $time);
    }


    /**
     * 截取字符并hover显示全部字符串
     * $str string  截取的字符串
     * $len int 截取的长度
     */
    public static function getFullString($str, $len)
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' => $str), self::sub($str, $len, $show = true, $f = '……'));
    }

    /*
     * total_report_search汇总创造一个组织架构分类作为计算的排序
     * */
    public static function getBranchParentId()
    {
        $sql = "select community.id,branch.parent_id from community,branch where community.branch_id=branch.id";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        $caseThen = "CASE ";
        foreach ($data as $val) {
            $case = " WHEN `t`.community_id=" . $val['id'] . " THEN " . $val['parent_id'];
            $caseThen .= $case;
        }
        $caseThen .= " ELSE 1 END branch_parent_id";
        return $caseThen;
    }

    /*
     * total_report_search汇总创造一个地区分类作为计算的排序
     * */
    public static function getRegionId()
    {
        // $sql="select community.id,region.id region_id from community,region where community.region_id=region.id";
        $sql = "select id,region_id from community";
        $data = Yii::app()->db->createCommand($sql)->queryAll();
        $caseThen = "CASE ";
        foreach ($data as $val) {
            $case = " WHEN `t`.community_id=" . $val['id'] . " THEN " . $val['region_id'];
            $caseThen .= $case;
        }
        $caseThen .= " ELSE 1 END region_id";
        return $caseThen;
    }

    /*
     * 计算两个时间戳的差值，返回数组，小时，分钟，秒
     * 传参数$begin_time,$end_time，均是时间戳
     * 返回array  $res
     * */
    public static function getTimeDiff($begin_time, $end_time, $day = true)
    {
        if ($begin_time < $end_time) {
            $starttime = $begin_time;
            $endtime = $end_time;
        } else {
            $starttime = $end_time;
            $endtime = $begin_time;
        }
        $timediff = $endtime - $starttime;
        if ($day) {
            $days = intval($timediff / 86400);
            $remain = $timediff % 86400;
        } else {
            $remain = $timediff;
        }
        $hours = intval($remain / 3600);
        $remain = $remain % 3600;
        $mins = intval($remain / 60);
        $secs = $remain % 60;
        if ($day) {
            $res = array("day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs);
        } else {
            $res = array("hour" => $hours, "min" => $mins, "sec" => $secs);
        }

        return $res;
    }

    /**
     * 返回相关的物业后台基本配置
     * @param string $key
     * @param null|string $value
     * @return bool|string|integer
     */
    public static function getConfig($key, $value = null)
    {
        if (!isset(Yii::app()->config->$key)) {
            return false;
        }
        $config = Yii::app()->config->$key;
        if (!empty($value)) {
            $config = isset($config[$value]) ? $config[$value] : false;
        }
        return $config;
    }

    static function  priceFormat($price)
    {
        if (empty($price)) $price = '0.00';
        $price = number_format($price, 2, '.', '');

        return (float)$price;
    }

    static function  price_format($price, $price_format = NULL)
    {
        if (empty($price)) $price = '0.00';
        $price = number_format($price, 2);

        if ($price_format === NULL) {
            $price_format = '%.2f';
        }

        return sprintf($price_format, $price);
    }



    static function  price_formatNew($price, $price_format = NULL)
    {
        if (empty($price)) $price = '0.00';
        //$price = number_format($price, 2);

        if ($price_format === NULL) {
            $price_format = '%.2f';
        }

        return sprintf($price_format, $price);
    }

    static function price_discard($price)
    {
        return sprintf("%.2f", substr(sprintf("%.4f", $price), 0, -2));
    }

    /**
     * 获取毫秒
     * @return float
     */
    static function getMillisecond() {
        list($t1, $t2) = explode(' ', microtime());
        return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
    }

    /**
     * json 中文不转码
     * @param $value
     * @return mixed|string
     */
    static function json_encode_ex( $value)
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

    /**
     * 危险 HTML代码过滤器
     *
     * @param   string $html 需要过滤的html代码
     *
     * @return  string
     */
    static function html_filter($html)
    {
        $filter = array(
            "/\s/",
            "/<(\/?)(script|i?frame|style|html|body|title|link|\?|\%)([^>]*?)>/isU",//object|meta|
            "/(<[^>]*)on[a-zA-Z]\s*=([^>]*>)/isU",
        );

        $replace = array(
            " ",
            "&lt;\\1\\2\\3&gt;",
            "\\1\\2",
        );

        $str = preg_replace($filter, $replace, $html);
        return $str;
    }
    /**
     * 危险 HTML代码过滤器
     *
     * @param   string $html 需要过滤的param代码
     *
     * @return  string
     */
    public static function ParamFilter($param){
        if(!isset($param)){
            $param="";
        }

        return  $param;
    }

    /**********功能方法***********/
    /**
     * 对数组排序
     * @param $para 排序前的数组
     * @return 排序后的数组
     */
    public static function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * @return 拼接完成以后的字符串
     */
    public static function createLinkString($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);

        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

        return $arg;
    }

    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * @return 去掉空值与签名参数后的新签名参数组
     */
    public static function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $key == "sign_type" || $val === "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    public static function arrayToStr($array = null)
    {
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
}
