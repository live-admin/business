<?php

/**
 * Class IceApi
 *
 * Yii::import('common.api.IceApi');
 * $ice = IceApi::getInstance();
 * sign=MD5($appID+$ts+$token+”false”)
 */

set_time_limit(0);
class IceApi
{
    protected $appID = 'ICECZY00-F26F-42B8-988C-27F4AEE3292A';                 //调用者app id
    protected $token = 'r9A0ZSn5b4jOSJEnGc3y';                                 //彩之云token
    protected $serviceUrl = 'http://iceapi.colourlife.com:8081';               //ICE正式接口地址接口
    
    protected $serviceTestUrl = 'http://icetest.colourlife.net:8081';           //ICE测试接口地址
    protected $d_ad  = '0,9959f117-df60-4d1b-a354-776c20ffb8c7,760d5ff3-136f-445f-b9df-f01d0943a9e0';
    protected static $instance;

    public $queryData;
    public $queryUrl;

    public static function getInstance()
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }

    /**
     * 更新Kpi信息
     * $d_ad       组织结构id，0集团，9959f117-df60-4d1b-a354-776c20ffb8c7集团总部，760d5ff3-136f-445f-b9df-f01d0943a9e0地区事业部
     * $d_time     时间，例如年2015，月201501, 日20150101
     * $appCount   app注册数
     * $communityCount   小区数(string)
     * $complainCount    投诉数(string)
     * $satisfaction     满意度(string)
     * $floorArea        上线面积(string)
     * $sign             签名(string)
     * $ts               时间戳(string),ts为10位忽略毫秒的时间戳如：1433428766
     * $appID            调用者app id(string)
     */
    public function updateKpi($appCount)
    {
        $params = array(
            'appCount' => $appCount,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/bigdata/kpi');
        $result =  Yii::app()->curl->putNew($this->queryUrl, 'put', $this->queryData);
        //$result = $this->curltest($this->queryUrl, 'put', $this->queryData);
        $result = $this->resolveResult($result);

        return $result;
    }

    public function getBmManager($community_id){
        $params = array(
            'community_id' => $community_id,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/advanced/manager');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResult($result);

        return $result;
    }

    /*小区详细数据*/
    public function getCommPage(){
        $params = array(
            // 'keyword' => $community_name,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/community/page');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResult($result);
        return $result;
    }

    /**
     * 返回 ColorCloud 小区 id => name 键值对列表（也会返回部门）
     * @return array
     */
    public function getCommunityUid($community_name)
    {
        $params = array(
            'keyword' => $community_name,
            'size' => 15,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/community/page');
        $data = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $data =  $this->resolveResultData($data);

        $result = array();
        if ($data !== false && is_array($data['list'])) {

            foreach ($data['list'] as $item)
                $result[$item['uuid'].'/'.$item['name']] = $item['name'];
        }

        return $result;
    }

    /**
     * 获取彩之云小区对应的收费系统小区详细信息
     * @param $czyID
     * @return bool|mixed
     */
    public function getCommunityInfo($czyID)
    {
        $params = array(
            'czy_id' => $czyID,
        );

        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/community');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result =  $this->resolveResultData($result);

        return $result;
    }

    /*AD登录验证*/
    public function login($username, $password)
    {
        $params = array(
            'username' => $username,
            'password' => md5($password),
	        'type' => 'czy'
        );
        $this->queryData = $this->makeQueryLogin($params);
        $this->queryUrl = $this->makeServerUrl('/v1/account/login');
        $result = Yii::app()->curl->post($this->queryUrl,$this->queryData);
        $result = $this->resolveResult($result);

        return $result;
    }

    /**
     * 通过id或code获取小区信息，两个参数都传优先通过id查询
     * @param $code
     * @param string $id
     * @return bool|mixed
     */
    public function getCommunity($code, $id='')
    {
        $params = array(
            'id' => $id,
            'code' => $code
        );

        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/community');

        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResult($result);

        return $result;
    }

    /**
     * AD登录请求参数
     * @return array
     */
    private function makeQueryLogin($para=null)
    {
        $ts = time();
        $paraComm =  array(
            'sign' => $this->makeSign($ts),
            'ts' => $ts,
            'appID' => $this->appID,
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
     * 存储小区单冲抵订单
     * 接口名称：set.advance.saveappfee
     * 参数列表：
     * 参数名称         是否必填参数            参数描述                   示例
     * @unitid              是                 收费单元ID
     * @receiptnumber       是                 缴费单号                   default:我们的预缴费订单SN
     * @flag                                   缴费类型                   默认0，预缴往年，1缴历史欠费
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
    public function callSetAdvanceSaveAppFee($unitid, $receiptnumber, $flag=0, $payfee, $actfee, $actmoney, $year, $month, $discount,$bewrite,$uuid='')
    {   
        $params = array(
            'uuid' => $uuid,
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
        );

        $this->queryData = $this->makeQueryData($params);
        //dump($this->queryData);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/saveappfee');

        $header = array(
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json'
        );

        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData, true, $header);

        $result =  $this->resolveResultAll($result);
        return $result;
    }

     /**取得小区预收费设置
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
    public function callGetAdvanceFee($unitid,$uuid='')
    {   

        $params = array(
            'uuid' => $uuid,
            'unitid' => $unitid,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/advance_fee');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResult($result);
        return $result;
    }

    /**小区管理费用情况
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
    public function callGetAdvancePayfee($unitid, $month, $uuid='')
    {   
        $params = array(
            'uuid' => $uuid,
            'unitid' => $unitid,
            'month' => $month
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/advance_payfee');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResult($result);
        return $result;
    }

    /**
     * 获取银湾物业费
     * @param $room_id
     * @return bool|mixed
     */
    public function getYinWanMonthFee($room_id){
        $params = array(
            'room_id' => $room_id
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/ywwy/yinwan/monthFee');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResult($result);
        return $result;
    }

    //test
    public function callGetAdvancePayfeeTest($unitid, $month, $uuid='')
    {   
        $params = array(
            'uuid' => $uuid,
            'unitid' => $unitid,
            'month' => $month
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/advance_payfee');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResult($result);
        return $result;
    }

    /**取得小区预收费设置
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
    public function callGetAdvanceItem($unitid,$uuid='')
    {   

        $params = array(
            'uuid' => $uuid,
            'unitid' => $unitid,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/advance_item');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResult($result);
        return $result;
    }

    /**已收费记录查询
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
    public function callGetPayLog($unitid, $pagesize, $pageindex,$uuid='')
    {   

        $params = array(
            'uuid' => $uuid,
            'unitid' => $unitid,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/paylog');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }

    /**已收费记录查询详情
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
    public function callGetPayLogdetial($tollid,$uuid='')
    {

        $params = array(
            'uuid' => $uuid,
            'tollid' => $tollid,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl("paylog/{$tollid}");
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResult($result);
        return $result;
    }

    /**
     * 存储小区预收费缴费费用
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
    public function callSetAdvanceSavefee($unitid, $payfee, $toller, $accountant, $cashier, $bewrite, $payname, $contextnumber, $payfpfee,$uuid='')
    {
        $params = array(
            'uuid' => $uuid,
            'unitid' => $unitid,
            'payfee' => $payfee,
            'Toller' => $toller,
            'Accountant' => $accountant,
            'Cashier' => $cashier,
            'bewrite' => $bewrite,
            'payname' => $payname,
            'contextnumber' => $contextnumber,
            'payfpfee' => $payfpfee
        );

        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/advance_savefee');
        $result = Yii::app()->curl->get($this->queryUrl,$this->queryData);
        //$result = $this->resolveResultMsg($result);
        //return $result;

        $result = json_decode($result, true);

        # TODO
        $this->logResult($result);

        return $result;
    }

    public function callXSetAdvanceSavefee($unitid, $payfee, $toller, $accountant, $cashier, $bewrite, $payname, $contextnumber, $payfpfee,$uuid='')
    {
        $params = array(
            'uuid' => $uuid,
            'unitid' => $unitid,
            'payfee' => $payfee,
            'Toller' => $toller,
            'Accountant' => $accountant,
            'Cashier' => $cashier,
            'bewrite' => $bewrite,
            'payname' => $payname,
            'contextnumber' => $contextnumber,
            'payfpfee' => $payfpfee
        );

        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/xsfxt/advance_savefee');
        $result = Yii::app()->curl->post($this->queryUrl,$this->queryData);
        //$result = $this->resolveResultMsg($result);
        //return $result;

        $result = json_decode($result, true);

        # TODO
        $this->logResult($result);

        return $result;
    }

    /**支付订单
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
    public function callGetPayOrder($orderid, $total_fee, $note, $payname, $payfee, $payfpfee,$uuid='')
    {
        $params = array(
            'uuid' => $uuid,
            'orderid' => $orderid,
            'total_fee' => $total_fee,
            'note' => $note,
            'payname' => $payname,
            'payfee' => $payfee,
            'payfpfee' => $payfpfee,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/order_pay');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResultAll($result);

        # TODO
        $this->logResult($result);

        return $result;
    }

    /**创建订单
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
    public function callGetOrderCreate($regionid, $bid, $unitid, $billids, $paycode,$uuid='')
    {
        $params = array(
            'uuid' => $uuid,
            'regionid' => $regionid,
            'bid' => $bid,
            'unitid' => urldecode($unitid),
            'billids' => $billids,
            'paycode' => $paycode,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/ordercreate');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResultAll($result);

        # TODO
        $this->logResult($result);

        return $result;
    }

    /**
     * 校验单元信息
     * 接口名称 get.pay.checkowner
     * 校验单元信息
     *      参数名称    必填参数    参数描述                    示例
     * @param $unitid     Y         单元编号
     * @param $idcard     Y         业主身份证后4位 输入项
     */
    public function callCheckOwner($unitid,$idcard,$uuid=''){
        $params = array(
            'uuid' => $uuid,
            'unitid' => $unitid,
            'idcard' => $idcard,
        );

        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/checkowner');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResultMsg($result);
        return $result;
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
    public function callGetPayOwnerToll($unitid,$uuid='')
    {
        $params = array(
            'uuid' => $uuid,
            'unitid' => urldecode($unitid),
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/xsfxt/ownertoll');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResult($result);
        return $result;
    }

    /**
     * 已收费记录查询
     * 小区APP专项预缴扣费情况
     * 接口名称：get.pay.applog
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
    public function callGetPayAppLog($unitid, $year, $month, $pagesize, $pageindex, $uuid='')
    {
        $params = array(
            'uuid' => $uuid,
            'unitid' => $unitid,
            'year' => $year,
            'month' => $month,
            'pagesize' => $pagesize,
            'pageindex' => $pageindex,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/applog');
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);
        $result = $this->resolveResult($result);
        return $result;
    }

    /**
     * 查询单元收费项的情况
     * 收费记录详情通过收费编号查询
     * 接口名称：get.pay.billslog
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
    public function callGetPayBillsLog($billids,$uuid='')
    {   

        $params = array(            
            'billids' => $billids,
            'uuid' => $uuid,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl("billlog/{$billids}");
        $result = Yii::app()->curl->get($this->queryUrl, $this->queryData);

        $result = $this->resolveResult($result);
        return $result;
    }

    /**
     * 根据OA账号获取员工信息
     */
    public function getAccount($username)
    {
        $params = array(
            'username' => $username,
            'type' => 'czy',
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/account');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }

    /**
     * 根据job_id账号获取职位架构信息
     */
    public function getJob($job_id)
    {
        $params = array(
            'jobId' => $job_id,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/job');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }

    /**
     * 根据org_id账号获取职位架构完整信息
     */
    public function getOrg($uuid)
    {
        $params = array(
            'uuid' => $uuid,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/org');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }

    /**
     * 根据ice获取OA账号信息同步使用
    */
    public function getOAList($page, $size, $uptime, $startDate, $endDate){
        $params = array(
            'page' => $page,
            'size' => $size,
            'uptime' => $uptime,
//            'startDate' => $startDate,
//            'endDate' => $endDate,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/account/page');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }


    /**
     * 根据OA账号从ice获取账号信息
    */
    public function getOA($oa_username){
        $params = array(
            'username' => $oa_username,
	        'type' => 'czy'
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/account');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }


    //发送短信
    public function sendSms($mobile, $content){
        Yii::import('common.components.GetTokenService');
        $service = new GetTokenService();
        $token = $service->getAccessTokenFromPrivilegeMicroService();
        $params = array(
            'access_token' => $token,
            'to' => $mobile,
            'content'=>strpos($content, '【彩之云】') ? $content : '【彩之云】'.$content,
           // 'key'=>'3UjhuuWh',
            'channelID'=>'6'
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/ztyy/voice/sendSMS');
        $result =  Yii::app()->curl->post($this->queryUrl , $this->queryData);
        $result = $this->resolveResultMsg($result);
        return $result;
    }

    //自动注册
    public function autoRegister($mobile , $name = '访客'){
        $params = array(
            'mobile' => $mobile,
            'name' => $name
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/newczy/customer/autoRegister');
        $result =  Yii::app()->curl->post($this->queryUrl , $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }

    /**
     * 返回指定 ColorCloud 小区的 ColorCloud 楼栋 id => name 键值对列表
     * @param $parentId
     * @param string $uuid
     * @return bool|mixed
     */
    public function getBuildingsWithCommunity($parentId, $uuid='')
    {
        $params = array(
            //'parentid' => $parentId,
            'uuid' => $uuid,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/paybuildings');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);

        //dump(json_decode($result, true));
        $result = $this->resolveResultData($result);
        return $result;
    }

    /**
     * 返回指定 ColorCloud 楼栋的 ColorCloud 单位 id => name 键值对列表
     * @param $parentId
     * @param string $uuid
     * @return array
     */
    public function getUnitsWithBuilding($parentId, $uuid='')
    {
        $params = array(
            'parentid' => $parentId,
            'uuid' => $uuid,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/sfxt/payunits');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);

        $result = $this->resolveResultData($result);
        return $result;
    }

    public function getCode($uuid=''){
        $params = array(
            'uuid' => $uuid,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/xsfxt/paycommunity');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);

        $result = $this->resolveResultData($result);
        return $result;
    }

    /**
     * 根据收费系统订单号获取订单详情
     * @param $parentId
     * @param string
     * @return array
     */
    public function getOrderDetail($order_id){
        $params = array(
            'ordersid' => $order_id,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/xsfxt/order_paydetails');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);

        $result = $this->resolveResultData($result);
        return $result;
    }


    /***********接入金融平台相关接口****************/

    //鉴权
    public function appAuth($appkey, $appSecret){
        $timestamp = time();
        $params = array(
            'appkey' => $appkey,
            'timestamp' => $timestamp,
            'signature' => md5($appkey.$timestamp.$appSecret),
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/jqfw/app/auth');
        $result =  Yii::app()->curl->post($this->queryUrl , $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }

    //开通用户金融账号
    public function openClientAccount($access_token, $pano, $bano='', $mobile, $name='', $gender=1, $birthday='', $memo='', $cannegative=1){
        $params = array(
            'access_token' => $access_token,      //身份认证参数access_token,access_token从权限微服务获取
            'pano' => $pano,                     //接入方金融账号
            'bano' => $bano,                    //商家金融账号（用户关联的商家，一般是会员卡体系使用,默认填空
            'mobile' => $mobile,               //11位有效手机号码
            'name' => $name,                  //真实姓名
            'gender' => $gender,             //性别，1为男，2为女
            'birthday' => $birthday,        //生日1990-01-01
            'memo' => $memo,               //备注信息
            'cannegative' => $cannegative,//账号余额是否能负，默认为0，0不能负值 1能负值
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/jrpt/account/openClientAccount');
        $result =  Yii::app()->curl->post($this->queryUrl , $this->queryData);

        $result = $this->resolveResultData($result);
        return $result;
    }

    //快速交易
    public function fastTransaction($access_token, $money, $orderno, $content='', $orgtype, $orgaccountno=0, $desttype, $destaccountno, $detail, $starttime, $stoptime, $callback){
        $params = array(
            'access_token' => $access_token,  //身份认证参数access_token,access_token从权限微服务获取
            'money' => $money,                //交易金额，支持小数点后两位,如89.00
            'orderno' => $orderno,            //接入方的内部交易号
            'content' => $content,            //交易说明（显示给用户看的）
            'orgtype' => $orgtype,            //支付账号类型atid
            'orgaccountno' => $orgaccountno,  //支付账号，微信，支付宝等人民币交易填0
            'desttype' => $desttype,          //收款账号类型atid
            'orgaccountno' => $orgaccountno,  //支付账号，微信，支付宝等人民币交易填0
            'desttype' => $desttype,          //收款账号类型atid
            'destaccountno' => $destaccountno,//收款账号
            'detail' => $detail,              //交易明细
            'starttime' => $starttime,        //交易生效时间, unix时间戳
            'stoptime' => $stoptime,          //交易失效时间，unix时间戳
            'callback' => $callback,          //回调地址,如http://abc.com/trasaction_handler

        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/jrpt/transaction/fasttransaction');
        $result =  Yii::app()->curl->post($this->queryUrl , $this->queryData);

        $result = $this->resolveResultData($result);
        return $result;
    }



    /********* 停车 相关接口 ************/
    /**
     * 查询用户是否存在新收费系统
     * @param $mobile
     */
    public function getParkingUserExisted($mobile)
    {
        //$mobile = '13810501126';
        $params = array(
            'mobile' => $mobile,
            'source' => 'BdAxoy9C'
        );

        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/eparking/user/existed');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);

        $result = $this->resolveResultData($result);

        return $result;
    }

    /**
     *根据uuid查询是否属于易停小区
     * @param string
     * @return string
     */
    public function getStation($uuid){
        $params = array(
            'station' => $uuid,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/eparking/station');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);
        $result = $this->resolveResultAll($result);
        return $result;
    }
    /*
     * @version 买电通知订单信息
     * @param string $event 事件
     * @param string $data 数据
     * @param string $source 事件提供方
     * return string
     */
    public function sendPowerOrder($event,$data,$source){
        $params = array(
            'event' => $event,
            'data' => $data,
            'source' => $source,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/triger/send');
        $result =  Yii::app()->curl->post($this->queryUrl , $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }
    /*
     * @version 推送用户（手机号码）
     * @param string $content 推送内容
     * @param string $platform 推送平台，默认为all
     * @param string $mobile 手机号码
     * @param string $url 推送链接地址
     * @param int $resource_id 对应商家资源id
     * @param string $access_token access_token
     * @param string $title 标题
     * @param string $img 
     * @param int $information_type 推送1/通知0 
     * return boolean
     */ 
    public function pushSingleUser($content,$platform,$mobile,$url,$resource_id,$access_token,$title,$img,$information_type){
        $params = array(
            'content' => $content,
            'platform' => $platform,
            'mobile' => $mobile,
            'url' => $url,
            'resource_id' => $resource_id,
            'access_token' => $access_token,
            'title' => $title,
            'img' => $img,
            'information_type' => $information_type,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/newczy/push/pushSingleUser');
        $result =  Yii::app()->curl->post($this->queryUrl , $this->queryData);
        $result = $this->resolveResultAll($result);
        return $result;
    }
    /*
     * 二维码申请
     * @param string $accessToken 应用验证码，具体参考彩生活鉴权系统
     * @param string $username 用户唯一标识
     * @param int $applyCount 申请数量，单次申请不要超过10万个
     * @param string $prefix URL前缀
     * return string
     */
    public function qrcodeApplyOne($accessToken,$username,$applyCount,$prefix){
        $params = array(
            'accessToken' => $accessToken,
            'username' => $username,
            'applyCount' => $applyCount,
            'prefix' => $prefix,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/zkyw_code/qrcode/apply');
        $result =  Yii::app()->curl->post($this->queryUrl , $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }
    /*
     * 根据申请ID查询申请的二维码内容
     * @param string $accessToken 应用验证码，具体参考彩生活鉴权系统
     * @param string $applyId 申请ID，请使用二维码申请接口返回的applyId
     * @param int $page 分页页码，从1开始，默认是1
     * @param int $pageSize 每页条目数量，默认是10
     * return string
     */
    public function qrcodeApplyTwo($accessToken,$applyId,$page,$pageSize){
        $params = array(
            'accessToken' => $accessToken,
            'applyId' => $applyId,
            'page' => $page,
            'pageSize' => $pageSize,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/zkyw_code/qrcode/apply');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }

    public function getOwnerOrg($pid){
        $params = array(
            'org_id' => $pid,
            'pageSize' => 1,
            'pageIndex' => 1,
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/owner/org');
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);
        $result = $this->resolveResultData($result);
        return $result;
    }

    /**
     * 调用ice方法
     * @param string $interface
     * @param unknown $params
     * @param string $method
     * @return unknown
     */
    public function getRemoteData($interface = '',$params = array(),$method = 'GET'){
    	$this->queryData = $this->makeQueryData($params);
    	$this->queryUrl = $this->makeServerUrl($interface);
    	if ($method == 'POST'){
    		$result =  Yii::app()->curl->post($this->queryUrl , $this->queryData);
    	}else {
    		$result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);
    	}
    	$result = $this->resolveResultAll($result);
    	return $result;
    }

    /********* 功能方法 **********/
    /**
     * 日志记录
     * @param $word
     * @param string $word
     */
    private function logResult($word='') {

        $url = Yii::app()->curl->buildUrl($this->queryUrl, $this->queryData);

        $word = F::json_encode_ex($word);

        $logFile = dirname(__DIR__).'/../../log/czylog/colourlife/iceRequest/'.date('Y-m-d').'.txt';

        $logDir = dirname(__DIR__).'/../../log/czylog/colourlife/iceRequest';
        if ( ! is_dir($logDir))
            mkdir($logDir, 0777, true);

        $fp = fopen($logFile, "a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y-%m-%d %H:%M:%S",time())."\n 请求地址：".$url."\n 请求返回：".$word."\n\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /**
     * 生成签名
     * @param $ts
     * sign=MD5($appID+$ts+$token+”false”)
     * @return string
     */
    private function makeSign($ts)
    {
        return md5($this->appID.$ts.$this->token.'false');
    }

    /**
     * 请求参数
     * @param $para
     * @return array
     */
    private function makeQueryData($para=null)
    {
        $ts = time();
        //$d_time = date('Y');
        $paraComm =  array(
            'sign' => $this->makeSign($ts),
            'ts' => $ts,
            'appID' => $this->appID,
            // 'd_ad' => $this->d_ad,
            // 'd_time' => $d_time,
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
     * 生成 物业费 请求地址
     * @param $method
     * @return string
     */
    private function makeServerUrl($method)
    {
        if (defined('YII_DEBUG') && YII_DEBUG == true) {
            return $this->serviceTestUrl.$method;
//            return $this->serviceUrl.$method;
        } else {
            return $this->serviceUrl.$method;
        }
    }



    /**OLD
     * 处理请求结果
     * @param $result
     * @return bool|mixed
     */
    private function resolveResults($result)
    {
        $result = json_decode($result, true);
        if (isset($result['code']) &&  $result['code'] === 0) {
            return (isset($result['content']) && ! empty($result['content']) ) ? $result['content'] : false;
        }
        else {
            Yii::log('调用ICE接口：'.$this->queryUrl.' 参数：'.json_encode($this->queryData).' 错误信息：'.$result['message'], CLogger::LEVEL_INFO, 'colourlife.core.api.caiguanjia');
            return false;
        }
    }

    /**
     * 处理请求结果(带data)
     * @param $result
     * @return bool|mixed
     */
    private function resolveResult($result)
    {
        $result = json_decode($result, true);
        //Yii::log('调用ICE接口：'.$this->queryUrl.' 参数：'.json_encode($this->queryData).' 返回信息：'.var_export($result, true), CLogger::LEVEL_INFO, 'colourlife.core.api.IceApi.resolveResult');

        // TODO 请求日志记录
        $this->logResult($result);

        if (isset($result['code']) &&  $result['code'] == 0) {
            $result['data'] = array();
            $aa = (isset($result['content']) && !empty($result['content'])) ? $result['content'] : false;
            if($aa!=false){
                $result['data'] = $aa;
                return $result;
            }
            return false;

        } else {

            return false;
        }
    }

    /**
     * 处理请求结果(content)
     * @param $result
     * @return bool|mixed
     */
    private function resolveResultData($result)
    {
        $result = json_decode($result, true);
        // TODO 请求日志记录
        $this->logResult($result);

        //Yii::log('调用ICE接口：'.$this->queryUrl.' 参数：'.json_encode($this->queryData).' 返回信息：'.var_export($result, true), CLogger::LEVEL_INFO, 'colourlife.core.api.IceApi.resolveResultData');
        if (isset($result['code']) &&  $result['code'] === 0) {
            return (isset($result['content']) && !empty($result['content']) ) ? $result['content'] : false;
        }
        else {

            return false;
        }
    }

    /**
     * 处理请求结果(message)
     * @param $result
     * @return bool|mixed
     */
    private function resolveResultMsg($result)
    {
        $result = json_decode($result, true);

        $this->logResult($result);
        //Yii::log('调用ICE接口：'.$this->queryUrl.' 参数：'.json_encode($this->queryData).' 返回信息：'.var_export($result, true), CLogger::LEVEL_INFO, 'colourlife.core.api.IceApi.resolveResultMsg');
        if (isset($result['code']) &&  $result['code'] === 0) {
            return (isset($result['message']) && !empty($result['message']) ) ? $result['message'] : false;
        }
        else {

            return false;
        }
    }

    /**
     * 处理请求结果(all)
     * @param $result
     * @return bool|mixed
     */
    private function resolveResultAll($result)
    {
        $result = json_decode($result, true);

        $this->logResult($result);
        // var_dump($result);die;
        //Yii::log('调用ICE接口：'.$this->queryUrl.' 参数：'.json_encode($this->queryData).' 返回信息：'.var_export($result, true), CLogger::LEVEL_INFO, 'colourlife.core.api.IceApi.resolveResultAll');
        return $result;
    }

    /**
     * 获取快递公司列表
     * @return bool|mixed
     */
    public function getExpress(){
        $params = array( 'access_token' => '123' );
        $this->queryData = $this->makeQueryData($params);
        if (defined('YII_DEBUG') && YII_DEBUG == true) {
            $this->queryUrl = $this->makeServerUrl('/v1/shoufashi/Expess/');
        } else {
            $this->queryUrl = $this->makeServerUrl('/v1/KDCX/Expess/');
        }
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);

        $result = $this->resolveResultAll($result);

        if (isset($result['Code']) &&  $result['Code'] == '1') {
            return (isset($result['Row']) && !empty($result['Row']) ) ? $result['Row'] : false;
        } else {
            return false;
        }
    }


    /**
     * 获取物流信息
     * @param $express_id int 快递公司id
     * @param $express_sn string 单号
     * @return bool
     */
    public function getExpressInfo($express_id, $express_sn)
    {
        $params = array(
            'access_token' => '123',
            'company' => ' 彩生活',
            'Supplier' => '彩生活',
            'ExpessID' => $express_id,
            'Exp_num' => $express_sn
        );
        $this->queryData = $this->makeQueryData($params);
        if (defined('YII_DEBUG') && YII_DEBUG == true) {
            $this->queryUrl = $this->makeServerUrl('/v1/shoufashi/QueryExpress/');
        } else {
            $this->queryUrl = $this->makeServerUrl('/v1/KDCX/QueryExpress/');
        }
        $result =  Yii::app()->curl->get($this->queryUrl , $this->queryData);

        $result = $this->resolveResultAll($result);

        if (isset($result['Code']) &&  $result['Code'] == '1') {
            return (isset($result['Row']) && !empty($result['Row']) ) ? $result['Row'] : false;
        } else {
            return false;
        }
    }

    /**
     * 获取金融平台订单交易详情
     * @param $access_token
     * @param $tno
     * @return bool|mixed
     */
    public function getTransaction($access_token, $tno)
    {
        $params = array(
            'access_token' => $access_token,
            'tno' => $tno
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/jrpt/transaction/get');

        $result =  Yii::app()->curl->post($this->queryUrl , $this->queryData);

        $result = $this->resolveResultData($result);

        return $result;
    }

    /**
     * 获取小区uuid
     * @param $community_id
     * @return mixed
     */
    public function getCommunityUuidByCache($community_id)
    {
        $cache_key = md5('community_uuid_' . $community_id);
        $community_uuid =  Yii::app()->rediscache->get($cache_key);
        if (!$community_uuid) {
            $community = $this->getCommunityInfo($community_id);
            if ($community) {
                $community_uuid = $community['uuid'];
                Yii::app()->rediscache->set(
                    $cache_key,
                    $community_uuid,
                    86400
                );
            }
        }
        return $community_uuid;
    }

    /**
     * 发送营销短信
     * @param $mobile
     * @param $content
     * @return bool|mixed
     */
    public function sendMarketingSms($mobile, $content){
        Yii::import('common.components.GetTokenService');
        $service = new GetTokenService();
        $token = $service->getAccessTokenFromPrivilegeMicroService();
        $params = array(
            'access_token' => $token,
            'to' => $mobile,
            'content'=> $content,
            // 'key'=>'3UjhuuWh',
            'channelID'=>'8'
        );
        $this->queryData = $this->makeQueryData($params);
        $this->queryUrl = $this->makeServerUrl('/v1/ztyy/voice/sendSMS');
        $result =  Yii::app()->curl->post($this->queryUrl , $this->queryData);
        $result = json_decode($result, true);
        if (isset($result['code']) && $result['code'] == 0) {
            return true;
        } else {
            return false;
        }
    }

}