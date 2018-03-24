<?php

/**
 * Class ColorCloudApi
 *
 * Yii::import('common.api.ColorCloudApi');
 * $cloud = ColorCloudApi::getInstance();
 * $return = $cloud->callGetPayCommunity(....); // 带返回值和错误参数
 * $return = $cloud->getPayCommunity(); // 只有返回值，内部处理错误
 */

class ColorCloudApi
{
    const ColorCloudCacheId = 'ColorCloud';
    static protected $instance;
    protected $appSecret = 'SDFL#)@F';
    //protected $baseUrl = 'http://mapi2.colourlife.com/?';
    protected $baseUrl = 'http://mapi.colourlife.com/?';
    static public function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }

    protected function getCacheKey($key)
    {
        return self::ColorCloudCacheId . $key;
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
    public function call($method, $params)
    {
        $param = $this->json_encode($params);
        $params = $this->encrypt($param);
        $sign = strtoupper(md5($this->appSecret . 'Method' . $method . 'Params' . $params . $this->appSecret));
        try {
            $url = $this->baseUrl . 'Method=' . $method . '&Params=' . $params . '&Sign=' . $sign;
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"", $url, $method, $param), CLogger::LEVEL_INFO, 'colourlife.core.api.ColorCloudApi');
            $return = Yii::app()->curl->get($url);
            // $return = $this->getData($url);
            //var_dump($return);die;
            if ($return !== false)
                $return = $this->decrypt($return);
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"\n返回值：\"%s\"", $url, $method, $param, $return), CLogger::LEVEL_INFO, 'colourlife.core.api.ColorCloudApi');
            return CJSON::decode($return);
        } catch (CException $e) {
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"\n出错信息：\"%s\"", $url, $method, $param, $e->getMessage()), CLogger::LEVEL_ERROR, 'colourlife.core.api.ColorCloudApi');
            return false;
        }
    }

    public function callTest($method, $params)
    {
        $param = $this->json_encode($params);
        $params = $this->encrypt($param);
        $sign = strtoupper(md5($this->appSecret . 'Method' . $method . 'Params' . $params . $this->appSecret));
        try {
            $url = $this->baseUrl . 'Method=' . $method . '&Params=' . $params . '&Sign=' . $sign;
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"", $url, $method, $param), CLogger::LEVEL_INFO, 'colourlife.core.api.ColorCloudApi');
            //$return = Yii::app()->curl->get($url);
            $return = $this->getData($url);
            // var_dump($return);die;
            if ($return !== false)
                $return = $this->decrypt($return);
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"\n返回值：\"%s\"", $url, $method, $param, $return), CLogger::LEVEL_INFO, 'colourlife.core.api.ColorCloudApi');
            return CJSON::decode($return);
        } catch (CException $e) {
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"\n出错信息：\"%s\"", $url, $method, $param, $e->getMessage()), CLogger::LEVEL_ERROR, 'colourlife.core.api.ColorCloudApi');
            return false;
        }
    }


    //没有参数的调用
    public function emptycall($method)
    {
        $sign = strtoupper(md5($this->appSecret . 'Method' . $method. $this->appSecret));
        try {
            $url = $this->baseUrl . 'Method=' . $method . '&Sign=' . $sign;
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n", $url, $method), CLogger::LEVEL_INFO, 'colourlife.core.api.ColorCloudApi');
            $return = Yii::app()->curl->get($url);
            if ($return !== false)
                $return = $this->decrypt($return);
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n返回值：\"%s\"", $url, $method, $return), CLogger::LEVEL_INFO, 'colourlife.core.api.ColorCloudApi');
            return CJSON::decode($return);
        } catch (CException $e) {
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n出错信息：\"%s\"", $url, $method, $e->getMessage()), CLogger::LEVEL_ERROR, 'colourlife.core.api.ColorCloudApi');
            return false;
        }
    }

    public function postCall($method, $params,$otherParams)
    {
        $param = $this->json_encode($params);
        $params = $this->encrypt($param);
        $sign = strtoupper(md5($this->appSecret . 'Method' . $method . 'Params' . $params . $this->appSecret));
        try {
            $url = $this->baseUrl . 'Method=' . $method . '&Params=' . $params . '&Sign=' . $sign;
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"\npost参数：\"%s\"", $url, $method, $param, var_export($otherParams,true)), CLogger::LEVEL_INFO, 'colourlife.core.api.ColorCloudApi');
            $return = Yii::app()->curl->post($url,$otherParams);
            if ($return !== false)
                $return = $this->decrypt($return);
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"\npost参数：\"%s\"\n返回值：\"%s\"", $url, $method, $param,
                var_export($otherParams,true),$return), CLogger::LEVEL_INFO, 'colourlife.core.api.ColorCloudApi');
            return CJSON::decode($return);
        } catch (CException $e) {
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"\npost参数：\"%s\"\n出错信息：\"%s\"", $url, $method, $param,
                var_export($otherParams,true),$e->getMessage()), CLogger::LEVEL_ERROR, 'colourlife.core.api.ColorCloudApi');
            return false;
        }
    }

    /**
     * 物业公司列表
     * 接口名称：get.pay.community
     * 参数列表：
     * 参数名称    必填参数    参数描述                                                示例
     * parentid                父级城市编号暂无填0预留（为0时取得所有物业公司信息）    0
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * id          物业编号
     * name        物业公司名称
     *
     * 小区列表
     * 接口名称：get.pay.community
     * 参数列表：
     * 参数名称    必填参数    参数描述                                示例
     * parentid                父级物业编号（为0时取得所有小区信息）    0
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * id          小区编号
     * name        小区名称
     */
    public function callGetPayCommunity($parentid = '0')
    {
        return $this->call('get.pay.community', array(
            'parentid' => $parentid,
        ));
    }

    /**
     * 返回 ColorCloud 小区 id => name 键值对列表（也会返回部门）
     * @return array
     */
    public function getCommunity()
    {
        $data = $this->getCache('');
        if ($data === false) {
            $data = array();
            $return = $this->callGetPayCommunity();
            if ($return !== false && is_array($return['data'])) {
                foreach ($return['data'] as $item)
                    $data[$item['id']] = $item['name'];
                $this->setCache('', $data);
            }
        }
        return $data;
    }

    /**
     * 楼宇列表
     * 接口名称：get.pay.buildings
     * 参数列表：
     * 参数名称    必填参数    参数描述    示例
     * parentid    是            父级小区编号
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * id          楼宇编号
     * name        楼宇名称
     */
    public function callGetPayBuildings($parentid)
    {
        return $this->call('get.pay.buildings', array(
            'parentid' => $parentid,
        ));
    }

    /**
     * 返回指定 ColorCloud 小区的 ColorCloud 楼栋 id => name 键值对列表
     * @param $id ColorCloud 小区 ID
     * @return array
     */
    public function getBuildingsWithCommunity($id)
    {
        $data = array();
        $return = $this->callGetPayBuildings($id);
        if ($return !== false && is_array($return['data'])) {
            $data = $return['data'];
        }
        return $data;
    }

    /**
     * 单元列表
     * 接口名称：get.pay.units
     * 参数列表：
     * 参数名称    必填参数     参数描述    示例
     * parentid    是           父级楼宇编号
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * id          单元编号
     * name        单元名称
     */
    public function callGetPayUnits($parentid)
    {
        return $this->call('get.pay.units', array(
            'parentid' => $parentid,
        ));
    }




    /**
     * OA账号是否是主任
     * 接口名称：get.user.checkzr
     * 参数列表：
     * 参数名称    必填参数     参数描述    示例
     * username    是           登录名
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     */
    public function callGetUserCheckzr($username)
    {
        return $this->call('get.user.checkzr', array(
            'username' => $username,
        ));
    }




    /**
     * 返回指定 ColorCloud 楼栋的 ColorCloud 单位 id => name 键值对列表
     * @param $id ColorCloud 楼栋 ID
     * @return array
     */
    public function getUnitsWithBuilding($id)
    {
        $data = $this->getCache($id);
        if ($data === false) {
            $len = strlen($id);
            $data = array();
            $return = $this->callGetPayUnits($id);
            if ($return !== false && is_array($return['data'])) {
                $data = $return['data'];
                foreach ($data as $k => $v) {
                    $name = $v['name'];
                    if (strncmp($name, $id, $len) == 0)
                        $name = ltrim(substr($name, $len), '-');
                    $data[$k]['name'] = $name;
                }
            }
            $this->setCache($id, $data);
        }
        return $data;
    }


    /**
     * 校验单元信息
     *      参数名称    必填参数    参数描述                    示例
     * @param $unitid     Y         单元编号
     * @param $idcard     Y         业主身份证后4位 输入项
     */
    public function callCheckOwner($unitid,$idcard){
        return $this->call('get.pay.checkowner', array(
            'unitid' => $unitid,
            'idcard' => $idcard,
        ));
    }




    /**
     * 取得业主所在单元列表
     * 接口名称：get.pay.ownertollunitid
     * 参数列表：
     * 参数名称    必填参数    参数描述    示例
     * regionid    是          小区编号
     * bid                     楼宇编号
     * owner                   业主姓名 输入项
     * idcard                  业主身份证后6位 输入项
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明        示例
     * id          楼宇编号
     * name        楼宇名称
     */
    public function callGetPayOwnerTollUnitId($community_id, $building_id, $owner, $idcard)
    {
        return $this->call('get.pay.ownertollunitid', array(
            'regionid' => $community_id,
            'bid' => $building_id,
            'owner' => $owner,
            'idcard' => $idcard,
        ));
    }

    /**
     * 取得业主欠费清单记录列表
     * 接口名称：get.pay.ownertoll
     * 参数列表：
     * 参数名称    必填参数    参数描述    示例
     * unitid      是          单元编号
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * billid      收费项编号
     * yearmonth   收费年月
     * itemname    收费项名称
     * state       欠费状态
     * fee         正常费用
     * normalfee   欠交费用
     * latefeerate 滞纳金率
     * latefee     滞纳金
     * actualfee   总欠费
     * lastpaydate 交费期限
     * receivedfee 已收费用
     * category    交费种类
     *             （注意当种类=4时允许进行支付折扣，即该项费用-（该项费用*支付优惠率/100）=实际支付费用）
     *             支付优惠率从支付方式中获取
     */
    public function callGetPayOwnerToll($unit_id)
    {
        return $this->call('get.pay.ownertoll', array(
            'unitid' => $unit_id,
        ));
    }

    /**
     * 取得业主欠费清单记录列表2
     * 接口名称：get.pay.ownertoll2
     * 参数列表：
     * 参数名称    必填参数    参数描述    示例
     * unitid      是          单元编号
     * owner       是          业主姓名
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * billid      收费项编号
     * yearmonth   收费年月
     * itemname    收费项名称
     * state       欠费状态
     * fee         正常费用
     * normalfee   欠交费用
     * latefeerate 滞纳金率
     * latefee     滞纳金
     * actualfee   总欠费
     * lastpaydate 交费期限
     * receivedfee 已收费用
     * category    交费种类
     *             （注意当种类=4时允许进行支付折扣，即该项费用-（该项费用*支付优惠率/100）=实际支付费用）
     *             支付优惠率从支付方式中获取
     */
    public function callGetPayOwnerToll2($unitid, $owner)
    {
        /**
         * empty($return['data']) 有数据表示有欠费账单，无数据表示无欠费
         * empty($returm['error']) 不为空表示 单元号和业主姓名不匹配
         */
        return $this->call('get.pay.ownertoll2', array(
            'unitid' => $unitid,
            'owner' => $owner,
        ));
    }

    /**
     * 创建订单
     * 接口名称：get.order.create
     * 参数列表：
     * 参数名称    必填参数    参数描述    示例
     * regionid    是          小区编号
     * bid                     楼宇编号
     * unitid                  收费单元
     * billids                 收费项ID 多个用,号分隔
     * paycode                 支付方式编码
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * orderid     订单号
     * orderamount 订单金额
     * state       状态0失败1成功
     * msg         提示信息
     */
    public function callGetOrderCreate($regionid, $bid, $unitid, $billids, $paycode)
    {
        return $this->call('get.order.create', array(
            'regionid' => $regionid,
            'bid' => $bid,
            'unitid' => $unitid,
            'billids' => $billids,
            'paycode' => $paycode,
        ));
    }

    /**
     * 订单支付成功状态更新
     * 接口名称：get.pay.order
     * 参数列表：
     * 参数名称    必填参数    参数描述    示例
     * orderid 是  订单ID
     * total_fee   订单费用
     * note        支付说明如：支付宝支付，交易号：xxxxx
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称    说明    示例
     * status
     * mag
     */
    public function callGetPayOrder($orderid, $total_fee, $note, $payname, $payfee, $payfpfee)
    {
        // $note = "彩生活交易号：{$sn}";
        return $this->call('get.pay.order', array(
            'orderid' => $orderid,
            'total_fee' => $total_fee,
            'note' => $note,
            'payname' => $payname,
            'payfee' => $payfee,
            'payfpfee' => $payfpfee
        ));
    }

    /**
     * 订单数据修复
     * @param $orderId
     * @param $totalFee
     * @param $statusId
     * @param $payName
     * @param $payFee
     * @param $payFpFee
     * @return bool|mixed
     */
    public function callGetPayModifyOrder($orderId, $totalFee, $statusId, $payName, $payFee, $payFpFee)
    {
        return $this->call('get.pay.modfiyorder', array(
            'orderid' => $orderId,
            'total_fee' => $totalFee,
            'statusid' => $statusId,
            'payname' => $payName,
            'payfee' => $payFee,
            'payfpfee' => $payFpFee
        ));
    }

    /**
     * 四舍六入五成双算法
     * @see http://blog.sina.com.cn/s/blog_47542995010144ro.html
     * @see http://kinglyhum.iteye.com/blog/608976
     */
    protected function round2($num, $precision)
    {
        $pow = pow(10, $precision);
        if ((floor($num * $pow * 10) % 5 == 0) &&
            (floor($num * $pow * 10) == $num * $pow * 10) &&
            (floor($num * $pow) % 2 == 0)
        ) {
            //舍去位为5 && 舍去位后无数字 && 舍去位前一位是偶数 =》 不进一
            return floor($num * $pow) / $pow;
        } else {
            //四舍五入
            return round($num, $precision);
        }
    }

    /**
     * 计算打折后的价格
     */
    public function calculate($fee, $discount)
    {
        return $this->round2($fee * $discount, 2);
    }

    /**
     * 小区单元预收费项目列表
     * 接口名称：get.advance.item
     * 参数列表：
     * 参数名称     是否必填参数      参数描述        示例
     * @unitid      是               收费单元ID
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称         说明              示例
     * check            是否选中(0=No,1=Yes)
     * Category            统计类别
     * CategoryName        统计类别名称
     * ID                数据编号
     * LateFeeRate        滞纳金率
     * Memo                备注
     * Name                收费项名称
     * Price            单价
     * RegionID            小区编号
     * TollType            收费种类0固定类，1抄表类，2面积类，3动态类
     */
    public function callGetAdvanceItem($unitid)
    {
        return $this->call('get.advance.item', array(
            'unitid' => $unitid
        ));
    }

    /**
     * 小区单元预存金额
     * 接口名称：get.advance.fee
     * 参数列表：
     * 参数名称     是否必填参数      参数描述        示例
     * @unitid      是               收费单元ID
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称         说明              示例
     * state            状态1正常0未找到
     * Tenement            业主姓名
     * Balance            预存金额 （单位：元）
     */
    public function callGetAdvanceFee($unitid)
    {
        return $this->call('get.advance.fee', array(
            'unitid' => $unitid
        ));
    }

    /**
     * 计算小区单元预存费金额
     * 接口名称：get.advance.payfee
     * 参数列表：
     * 参数名称     是否必填参数      参数描述        示例
     * @unitid      是               收费单元ID
     * @month       是               月份(int),1年=12
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称         说明              示例
     * state            状态1正常0未找到
     * fee                预存金额 （单位：元）
     */
    public function callGetAdvancePayfee($unitid, $month)
    {
        return $this->call('get.advance.payfee', array(
            'unitid' => $unitid,
            'month' => $month
        ));
    }




     public function callGetAdvancePayfeeTest($unitid, $month)
    {
        return $this->callTest('get.advance.payfee', array(
            'unitid' => $unitid,
            'month' => $month
        ));
    }

    /**
     * 存储小区单元预存费金额
     * 接口名称：set.advance.savefee
     * 参数列表：
     * 参数名称     是否必填参数      参数描述        示例
     * @unitid       是               收费单元ID
     * @payfee       是               金额
     * @Toller       是               收费员            default:我们的预缴费订单SN
     * @Accountant  是                会计             default:系统
     * @Cashier     是                出纳             default:系统
     * @bewrite     是                备注
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称         说明              示例
     * state            状态1正常0失败
     */
    public function callSetAdvanceSavefee($unitid, $payfee, $toller, $accountant, $cashier, $bewrite, $payname, $contextnumber, $payfpfee)
    {
        return $this->call('set.advance.savefee', array(
            'unitid' => $unitid,
            'payfee' => $payfee,
            'Toller' => $toller,
            'Accountant' => $accountant,
            'Cashier' => $cashier,
            'bewrite' => $bewrite,
            'payname' => $payname,
            'contextnumber' => $contextnumber,
            'payfpfee' => $payfpfee
        ));
    }

    /**
     * 同步历史预缴数据使用
     * @param $payFee
     * @param $payName
     * @param $contextNumber
     * @param $payFpFee
     * @return bool|mixed
     */
    public function callSetAdvanceUpSaveFee($payFee, $payName, $contextNumber, $payFpFee)
    {
        return $this->call('set.advance.upsavefee', array(
            'payfee' => $payFee,
            'payname' => $payName,
            'contextnumber' => $contextNumber,
            'payfpfee' => $payFpFee
        ));
    }

     /**
     * 存储小区单冲抵订单
     * 接口名称：set.advance.saveappfee
     * 参数列表：
     * 参数名称         是否必填参数              参数描述                   示例
     * @unitid              是                 收费单元ID
     * @receiptnumber       是                 缴费单号                   default:我们的预缴费订单SN
	 * @flag		                           缴费类型                   默认0，预缴往年，1缴历史欠费
     * @payfee              是                 预缴金额            
     * @actfee              是                 实际支付金额                default:系统
     * @year                是                 可用于扣款起始年             default:系统
     * @month               是                 起始月
     * @discount                               折扣
     * @bewrite                                备注
     *
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称             说明              示例
     * state            状态1正常0失败
     */
    public function callSetAdvanceSaveAppFee($unitid, $receiptnumber, $flag=0, $payfee, $actfee, $actmoney, $year, $month, $discount,$bewrite)
    {
        return $this->call('set.advance.saveappfee', array(
            'unitid' => $unitid,
            'receiptnumber' => $receiptnumber,
			'flag' => $flag,
            'payfee' => $payfee,
            'actfee' => $actfee,
            'actmoney' => $actmoney,
            'year' => $year,
            'month' => $month,
            'discount' => $discount,
            'bewrite' => $bewrite,
        ));
    }
	
	
	
	/**
     * 收费记录查询
     * 接口名称：get.advance.appfeelog
     * 参数列表：
     * 参数名称     是否必填参数     	  参数描述        示例
     * @unitid       是               收费单元ID
     * @pagesize     是               每页显示数量
     * @pageindex    是               页码
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 记录总数（total）
     * 返回字段（Ret类型：Json）
     * 字段名称                      说明
     * @receiptnumber              缴费单号
     * @flag                       0预缴，1历史缴费              
	 * @normalfee		           剩余可用于扣款金额           
     * @payfee                     预缴金额            
     * @actfee                     实际支付金额    
	 * @actmoney                   实际到帐       
     * @year                       可用于扣款起始年 
     * @month                      起始月
     * @discount                   折扣
     * @chargedate                 缴费日期
     */
    public function callGetAdvanceAppFeeLog($unitid, $pagesize, $pageindex)
    {
        return $this->call('get.advance.appfeelog', array(
            'unitid' => $unitid,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex,
        ));
    }
	
	
	
	/**
     * 获取我的审批列表
     * 接口名称：get.examine.my
     * 参数列表：
     * 参数名称     是否必填参数        参数描述        示例
     * @username       是               OA登陆用户名
     * @keyword        是               搜索关键字
     * @pagesize       是               每页显示数量
     * @pageindex      是               页码
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 记录总数（total）
     * 返回字段（Ret类型：Json）
     * 字段名称                       说明
     * @id                            审批编号
     * @creater                       发起人              
     * @cid                           分类编号           
     * @title                         标题            
     * @state                         状态 0新建，1处理中，2完成，3拒绝
     * @statename                     
     * @msg                           审批内容简要 
     * @createtime                    创建时间
     */
    public function callGetExamineMy($username, $keyword, $pagesize, $pageindex)
    {
        return $this->call('get.examine.my', array(
            'username' => $username,
            'keyword' => $keyword,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex,
        ));
    }




	
	/**
     * 小区APP专项预存金额实际到帐接口
     * 接口名称：set.advance.saveappmoney
     * 参数列表：
     * 参数名称                是否必填参数           参数描述/示例
     * @unitid                   是               收费单元ID
     * @receiptnumber            是               缴费单号对应set.advance.saveappfee接口中传递的单号
     * @actmoney                                  实际到帐金额
	 * @datetime                 是               到款时间完整的时间2014-14-5 15:11:30，用于做重复校验
     * @bewrite                                   备注
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 记录总数（total）
     * 返回字段（Ret类型：Json）
     * 字段名称                说明
     * @state              状态1正常0失败
     */
    public function callGetAdvanceSaveMoney($unitid, $receiptnumber, $actmoney, $datetime, $bewrite)
    {
        return $this->call('set.advance.saveappmoney', array(
            'unitid' => $unitid,
            'receiptnumber' => $receiptnumber,
            'actmoney' => $actmoney,
			'datetime' => $datetime,
			'bewrite' => $bewrite,
        ));
    }
	
	



    /**
     * 小区APP专项预缴扣费情况
     * 接口名称：set.pay.applog
     * 参数列表：
     * 参数名称                是否必填参数           参数描述/示例
     * @unitid                   是                   收费单元ID
     * @year                                          查询缴费年，默认为所有(2014)
     * @month                                         查询缴费月，默认为所有(12)
     * @pagesize                                      每页显示数量
     * @pageindex                                     分页数，默认1
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 记录总数（total）
     * 返回字段（Ret类型：Json）
     * 字段名称                说明
     * @billid  收费编号
     * @tollitemname    收费项目名称
     * @receivedfee 收费费用
     * @billyear    收费项产生年
     * @billmonth   收费项产生月
     *
     */
    public function callGetPayAppLog($unitid, $year, $month, $pagesize, $pageindex)
    {
        return $this->call('get.pay.applog', array(
            'unitid' => $unitid,
            'year' => $year,
            'month' => $month,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex,
        ));
    }

	


    /**
     * 收费记录详情2通过收费编号查询
     * 接口名称：set.pay.billslog
     * 参数列表：
     * 参数名称                是否必填参数           参数描述/示例
     * @billids                   是                   收费编号IDs,多个编号用，号分隔。半角的
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 记录总数（total）
     * 返回字段（Ret类型：Json）
     * 字段名称                说明
     * @billid                 收费项编号
     * @yearmonth              收费年月
     * @itemname               收费项目名称
     * @state                  欠费状态
     * @fee                    正常费用
     * @normalfee              欠交费用
     * @lastfeerate            滞纳金率
     * @lastfee                滞纳金
     * @actualfee              总欠费
     * @receiveddate           交费日期
     * @receivedfee            已收费用
     *
     */
    public function callGetPayBillsLog($billids)
    {
        return $this->call('get.pay.billslog', array(
            'billids' => $billids,
        ));
    }
	

    /**
     * 收费记录查询
     * 接口名称：get.pay.log
     * 参数列表：
     * 参数名称     是否必填参数      参数描述        示例
     * @unitid       是               收费单元ID
     * @pagesize    是               每页显示数量
     * @pageindex   是               页码
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 记录总数（total）
     * 返回字段（Ret类型：Json）
     * 字段名称         说明              示例
     * tollid            收据编号
     * chargetype       收费方式
     * chargefee        费用
     * contextnumber    支票号码
     * chargedate       收费日期
     */
    public function callGetPayLog($unitid, $pagesize, $pageindex)
    {
        return $this->call('get.pay.log', array(
            'unitid' => $unitid,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex,
        ));
    }

    


    /**
     * 获取需同步的红包发放列表
     * 接口名称：get.hongbao.uplist
     * 参数列表：
     * 参数名称      是否必填参数      参数描述        示例
     * @year         是                年,从2015年开始
     * @month        是                月
     * @pagesize     否                每页显示数量
     * @pageindex    否                页码
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 记录总数（total）
     * 返回字段（Ret类型：Json）
     * 字段名称          说明              示例
     * oid               当前批次唯一编号
     * oauser            对应OA帐号
     * realname          真实姓名
     * year              年
     * month             月
     * hbfee             红包额，为-1时未计算个人红包
     */
    public function callGetHongBaoUpList($year, $month, $pagesize, $pageindex)
    {
        return $this->call('get.hongbao.uplist', array(
            'year' => $year,
            'month' => $month,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex,
        ));
    }






    /**
     * 根据用户获取该用户的红包发放列表情况
     * 接口名称：get.hongbao.userlist
     * 参数列表：
     * 参数名称      是否必填参数      参数描述        示例
     * @oauser       是                对应OA帐号
     * @year         否                年,从2015年开始
     * @month        否               月
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 记录总数（total）
     * 返回字段（Ret类型：Json）
     * 字段名称          说明              示例
     * oauser            对应OA帐号
     * year              年
     * month             月
     * hbfee             红包
     * jthbfee           集体红包总额
     * state             -1未计算,0正在计算,1计算完未审批,2正在审,3审批完成,4已同步到彩管家
     */
    public function callGetHongBaoUserList($oauser, $year, $month)
    {
        return $this->call('get.hongbao.userlist', array(
            'oauser' => $oauser,
            'year' => $year,
            'month' => $month,
        ));
    }





     /**
     * 获取用户的个人红包发放情况详情
     * 接口名称：get.hongbao.details
     * 参数列表：
     * 参数名称     是否必填参数      参数描述        示例
     * @oauser      是                对应OA帐号
     * @year        否                年,从2015年开始
     * @month       否                月
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 记录总数（total）
     * 返回字段（Ret类型：Json）
     * 字段名称          说明              示例
     * oauser            对应OA帐号
     * year              年
     * month             月
     * hbfee             红包额
     * hbdata            {"title":"集团2.2整体通过率","pinfen":90,"money":502.88}
     * kkdata            奖罚列表[{"title":个人扣款",”flag”:”0扣款1奖励,"money":100.00,"time":"2015-02-25"}]
     * totaljjbbase      集体系数之和
     * jjbbase           个人系数
     * agvpingfen        本月绩效得分      
     * state             -1未计算,0正在计算,1计算完未审批,2正在审,3审批完成,4已同步到彩管家
     */
    public function callGetHongBaoDetails($oauser, $year, $month)
    {
        return $this->call('get.hongbao.details', array(
            'oauser' => $oauser,
            'year' => $year,
            'month' => $month,
        ));
    }




     /**
     * 获取用户的集体红包情况详情
     * 接口名称：get.hongbao.jtdetails
     * 参数列表：
     * 参数名称     是否必填参数      参数描述        示例
     * @oauser      是                对应OA帐号
     * @year        否                年，从2015年开始
     * @month       否                月
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 记录总数（total）
     * 返回字段（Ret类型：Json）
     * 字段名称         说明              示例
     * oauser           对应OA帐号
     * year             年
     * month            月
     * jthbfee          集体红包额
     * jtkk             集体扣款情况
     * jthbactfee       实际用于发放额
     * jtkkdata         奖罚列表[{"title":”集体扣款",”flag”:”0扣款1奖励”,"money":100.00,"time":"2015-02-25"}]
     * state            -1未计算,0正在计算,1计算完未审批,2正在审,3审批完成,4已同步到彩管家
     */
    public function callGetHongBaoJtDetails($oauser, $year, $month)
    {
        return $this->call('get.hongbao.jtdetails', array(
            'oauser' => $oauser,
            'year' => $year,
            'month' => $month,
        ));
    }




    /**
     * 收费记录查询
     * 接口名称：get.pay.logdetial
     * 参数列表：
     * 参数名称     是否必填参数      参数描述        示例
     * @tollid       是               收据编号ID
     * 返回结果：
     * 调用状态（Status）：1-成功 0-失败
     * 返回字段（Ret类型：Json）
     * 字段名称         说明              示例
     * billid            收费项编号
     * yearmonth       收费年月
     * itemname        收费项名称
     * state            欠费状态
     * fee              正常费用
     * normalfee        欠交费用
     * latefeerate      滞纳金率
     * latefee          滞纳金
     * actualfee        总欠费
     * receiveddate     交费日期
     * receivedfee      已收费用
     */
    public function callGetPayLogdetial($tollid)
    {
        return $this->call('get.pay.logdetial', array(
            'tollid' => $tollid
        ));
    }
    
    /**
     *接口名称：get.company.group
     *获取组织架构列表
     *$parentid  父Id
     *$pagesize  一页显示多少条记录
     *$pageindex 页码
     *return array;   
     */
    public function callGetCompanyGroup($parentid,$pagesize,$pageindex){
        return $this->call('get.company.group',array(
           'parentid' => $parentid,
           'pagesize' => $pagesize,
           'pageindex' => $pageindex
        ));
    }

    /**
     *接口名称：get.user.list
     * 获取员工列表
     *$parentid  父Id
     *$pagesize  一页显示多少条记录
     *$pageindex 页码
     *return array;   
     */
    public function callGetUserList($parentid,$pagesize,$pageindex){
        return $this->call('get.user.list',array(
            'parentid' => $parentid,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex
        ));
    }
    
        
    /**
     *接口名称：get.company.upgroup
     *更新组织架构列表
     *$uptime 更新时间
     *$pagesize 一页显示多少条记录
     *$pageindex 页码
     * return array;   
     */
    public function callGetCompanyUpGroup($uptime,$pagesize,$pageindex){
        return $this->call('get.company.upgroup',array(
            'uptime' => $uptime,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex
        ));
    }
    
    /**
     *接口名称：get.company.delgroup
     *更新删除组织架构列表
     *$uptime 更新时间
     *$pagesize 一页显示多少条记录
     *$pageindex 页码
     * return array;   
     */
    public function callGetCompanyDelGroup($uptime,$pagesize,$pageindex){
        return $this->call('get.company.delgroup',array(
            'uptime' => $uptime,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex
        ));
    }
    
    
    /**
     *接口名称：get.user.uplist
     *获取更新员工列表
     *$uptime 更新时间
     *$pagesize 一页显示多少条记录
     *$pageindex 页码
     * return array; 
     */
    public function callGetUserUpList($uptime,$pagesize,$pageindex,$username){
        return $this->call('get.user.uplist',array(
            'uptime' => $uptime,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex,
            'username' => $username
        ));
    }
    
    /**
     * 接口名称：get.user.check
     * OA帐户有效性验证
     * @param $username
     * @param $pwd
     * return 1 or 0
     */
    public function callGetUserCheck($username,$pwd){
        return $this->call('get.user.check',array(
            'username' => $username,
            'pwd' => $pwd
        ));
    }
    /**
     * 接口名称：set.user.uppwd
     *OA帐户有效性验证
     * @param $username
     * @param $pwd
     * return 1 or 0
     */
    public function callSetUserUpPwd($username,$pwd){
        return $this->call('set.user.uppwd',array(
            'username' => $username,
            'pwd' => $pwd
        ));
    }

    //**************************审批的部分***********************//
    //工作审批取得分类列表
    public function callGetExamineClass()
    {
        return $this->emptycall('get.examine.class');
    }
    /**
     * 选择流程、获取审批人流程信息
     * @param $classid
     * @return bool|mixed
     */
    public function callGetExamineProcess($classid)
    {
        return $this->call('get.examine.process',array(
            'classid'=>$classid,
        ));
    }

    /**
     * 创建审批
     * @param $cid
     * @param $processid
     * @param $examuser
     * @param $body
     * @param $sourceid
     * @param $createuser
     * @return bool|mixed
     */
    public function callGetExamineCreate($cid,$processid,$title,$body,$sourceid,$createuser)
    {
        return $this->postCall('set.examine.my',array(
            'flag'=>1,
            'title'=>$title,
            'CID'=>$cid,
            'processid'=>$processid,
            'sourceid'=>$sourceid,
            'createuser'=>$createuser,
        ),array('body'=>$body));
    }

    public function callGetExamineStatus($examineid)
    {
        return $this->call('get.examine.show',array(
            'examineid'=>$examineid,
        ));
    }

    //E清洁上传图片接口
    public function callSetPictureAdd($img,$name)
    {
        return $this->postCall('set.picture.add', array('name' => $name),array('img'=>$img));
    }

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
    public function callGetEmployee($oa_username)
    {
        return $this->call('get.user.employee', array(
            'oa_username' => $oa_username,
        ));
    }

    /**
     * 返回员工详细信息
     * @param oa_username  员工的OA用户ID
     * @return array
     */
    public function getEmployee($oa_username = '')
    {
        $data = array();
        if (empty($oa_username)){
        	return $data;
        }
        
        $return = $this->callGetEmployee($oa_username);
        if ($return !== false && is_array($return['data'])) {
            $data = $return['data'];
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
    public function callGetBmManager($community_id)
    {
        return $this->call('get.user.bm_manager', array(
            '$community_id' => $community_id,
        ));
    }

    /**
     * 返回事业部客户部经理信息
     * @param community_id   小区ID
     * @return array
     */
    public function getBmManager($community_id)
    {
        $data = array();
    	if (empty($community_id)){
        	return $data;
        }
        
        $return = $this->callGetBmManager($community_id);
        if ($return !== false && is_array($return['data'])) {
            $data = $return['data'];
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
    public function callGetUser4Job($oa_username, $job_name, $community_id)
    {
        return $this->call('get.user.users4job', array(
            'oa_username' => $oa_username,'job_name' => $job_name,'community_id' => $community_id,
        ));
    }

    /**
     * 返回与此小区相关职位人信息
     * @param oa_username    客户经理OA ID
     * @param job_name 职位
     * @param $community_id  小区ID
     * @return array
     */
    public function getUser4Job($oa_username = '', $job_name = '', $community_id = '')
    {
        $data = array();
    	if ((empty($oa_username)) || (empty($job_name))){
        	return $data;
        }
        
        $return = $this->callGetUser4Job($oa_username, $job_name, $community_id);
        if ($return !== false && is_array($return['data'])) {
            $data = $return['data'];
        }
        return $data;
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
    public function callGetJobs()
    {
        return $this->call('get.user.jobs', array(
        ));
    }

    /**
     * 返回公司员工职位列表
     * @return array
     */
    public function getOAJobs()
    {
        $data = array();
        $return = $this->callGetJobs();
        if ($return !== false && is_array($return['data'])) {
            $data = $return['data'];
        }
        return $data;
    }

    public function getCompanyGroup($code)
    {
        $this->baseUrl = 'http://mapi2.colourlife.com/?';
        $method = 'get.company.group';
        $params = array(
            'code' => $code,
            'params' => 'mapi2testSDFL#)@F'
        );
        $param = $this->json_encode($params);
        $params = $this->encrypt($param);
        $sign = strtoupper(md5($this->appSecret . 'Method' . $method . 'Params' . $params . $this->appSecret));
        try {
            $url = $this->baseUrl . 'Method=' . $method . '&Params=' . $params . '&Sign=' . $sign;
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"", $url, $method, $param), CLogger::LEVEL_INFO, 'colourlife.core.api.ColorCloudApi');
            $return = Yii::app()->curl->get($url);
            // $return = $this->getData($url);
            //var_dump($return);die;
            if ($return !== false)
                $return = $this->decrypt($return);
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"\n返回值：\"%s\"", $url, $method, $param, $return), CLogger::LEVEL_INFO, 'colourlife.core.api.ColorCloudApi');
            return CJSON::decode($return);
        } catch (CException $e) {
            Yii::log(sprintf("调用链接：\"%s\"\n接口：\"%s\"\n参数：\"%s\"\n出错信息：\"%s\"", $url, $method, $param, $e->getMessage()), CLogger::LEVEL_ERROR, 'colourlife.core.api.ColorCloudApi');
            return false;
        }
    }
}
