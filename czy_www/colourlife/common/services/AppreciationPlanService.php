<?php
/**
 * 增值计划
 * Created by PhpStorm.
 * User: Joy
 * Date: 2015/12/22
 * Time: 14:32
 */
Yii::import('common.services.BaseService');
class AppreciationPlanService extends BaseService {

    //private $serverUrl = 'http://test.hhnian.com:9051/';//测试
    private $serverUrl = 'http://www.hehenian.com/';//正式


    public function __construct()
    {
        if (defined('YII_DEBUG') && YII_DEBUG == true) {
            $this->serverUrl = 'http://test.hhnian.com:9051/';//测试
        } else {
            $this->serverUrl = 'http://www.hehenian.com/';//正式
        }
//        $startTime = strtotime('2017-08-01 00:00:00');
//        $endTime = strtotime('2017-09-01 00:00:00');
//        $now = time();
//        if ($now > $startTime && $now < $endTime){
//            $this->appreciationPlanRates[3] = 0.0600;
//        }
    }



    private $partnerKey = 'DJKC#$%CD%des$';

    // 单份金额
    private $singleStep = 5000.00;

    const APPRECIATION_PLAN_FIRST  = 1; // 增值计划I
    const APPRECIATION_PLAN_SECOND = 2; // 增值计划II
    const APPRECIATION_PLAN_THIRD  = 3; // 增值计划III
    const APPRECIATION_PLAN_FOURTH = 4; // 增值计划0

    // 名称
    protected $appreciationPlanNames = array(
        self::APPRECIATION_PLAN_FIRST => '增值计划I号',
        self::APPRECIATION_PLAN_SECOND => '增值计划II号',
        self::APPRECIATION_PLAN_THIRD => '增值计划III号',
        self::APPRECIATION_PLAN_FOURTH => '增值计划0号',
    );

    // 合和年订单状态
    const ORDER_STATUS_INIT = 0; //订单初始状态
    const ORDER_STATUS_SUCCESS = 1; // 已收到钱，下单成功
    const ORDER_STATUS_EXTRACT_ING = 2; //已申请提现，未到账
    const ORDER_STATUS_AUTHORIZE = 3; // 已授权
    const ORDER_STATUS_EXTRACT_SUCCESS = 4; // 已提现到账

    public function getAppreciationPlanName($projectId)
    {
        return isset($this->appreciationPlanNames[$projectId]) ? $this->appreciationPlanNames[$projectId] : '';
    }

    // 投资期限
    protected $appreciationPlanMonths = array(
        self::APPRECIATION_PLAN_FIRST => 12,
        self::APPRECIATION_PLAN_SECOND => 6,
        self::APPRECIATION_PLAN_THIRD => 3,
        self::APPRECIATION_PLAN_FOURTH => 1
    );

    public function getAppreciationPlanMonth($projectId)
    {
        return isset($this->appreciationPlanMonths[$projectId]) ? $this->appreciationPlanMonths[$projectId] : '';
    }

    // 利率
    protected $appreciationPlanRates = array(
        12 => 0.0900,
        6 => 0.0700,
        3 => 0.0480,
        1 => 0.0450
    );

    public function getAppreciationPlanRate($month)
    {
        return isset($this->appreciationPlanRates[$month]) ? $this->appreciationPlanRates[$month] : '';
    }

    public function getMaxRate()
    {
        return max($this->appreciationPlanRates);
    }

    // 银行利率
    protected $bankRates = array(
        12 => 0.0150,
        6 => 0.0130,
        3 => 0.0110,
        1 => 0.0035
    );

    public function getBankRate($month)
    {
        return isset($this->bankRates[$month]) ? $this->bankRates[$month] : '';
    }

    protected $appreciationPlanText = array(
        self::APPRECIATION_PLAN_FIRST => '高于银行一年期定期存款利率，预期年化收益{rate}，定投周期{month}个月，已有{countHouse}户业主参加，定投金额{countMoney}万元',
        self::APPRECIATION_PLAN_SECOND => '增值计划II号',
        self::APPRECIATION_PLAN_THIRD => '增值计划III号',
        self::APPRECIATION_PLAN_FOURTH => '增值计划0号',
    );

    /**
     * 计算收益
     * @param $investMoney
     * @param $rate
     * @param $months
     * @return array
     *
     */
    public function makeProfitData($investMoney, $rate, $months)
    {
        // 收益金额
        $profitMoney = $investMoney * $rate / 12 * $months;
        // 到期返还金额
        $returnMoney = $investMoney + $profitMoney;

        return array(
            'invest_money' => F::price_formatNew($investMoney),
            'profit_money' => F::price_formatNew($profitMoney),
            'return_money' => F::price_formatNew($returnMoney)
        );
    }

    /**
     * 计算冲抵周期
     * @param $beginTime 开始时间
     * @param $months    周期
     * @return array
     */
    public function makeProfitDate($beginTime, $months)
    {
        $day = date("d", $beginTime);
        $monthFirstDate = date("Y-m-01", $beginTime);
        $month = $months + 1;
        $monthLastDay = date("d",strtotime("{$monthFirstDate} +{$month} month -1 day"));

        // 到期日
        $endDay =  $day > $monthLastDay ? $monthLastDay : $day;

        $firstDay = date("Y-m-d", $beginTime);
        $lastDay = date("Y-m",strtotime("{$monthFirstDate} +{$months} month")).'-'.$endDay;
        $recoverDay = $lastDay; //date("Y-m-d",strtotime("{$lastDay}  +1 day"));

        return array(
            'first_day' => $firstDay,
            'last_day' => $lastDay,
            'recover_day' => $recoverDay
        );
    }

    /**
     * 校验续投订单是否能被续投
     * @param $sn
     * @param $customerId
     * @return CActiveRecord
     */
    public function verifySn($sn, $customerId)
    {
        // 2017-09-07
        return $this->sendErrorMessage(false, 0, '订单不可续投', '订单不可续投');

        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);
        if ( !$orderModel)
            return $this->sendErrorMessage(false, 0, '续投订单不存在', '续投订单不存在');
        if ( $customerId != $orderModel->customer_id)
            return $this->sendErrorMessage(false, 0, '只能续投自己订单', '只能续投自己订单');
        if (Item::PROFIT_ORDER_SUCCESS != $orderModel->status)
            return $this->sendErrorMessage(false, 0, '续投订单不是交易成功状态', '续投订单不是交易成功状态');
        if ($orderModel->begin_time > time())
            return $this->sendErrorMessage(false, 0, '订单未生效，不能连续续投', '订单未生效，不能连续续投');

        unset($criteria);

        // 请求合和年是否可提现
        $result = $this->getOrderContinueState($sn, $customerId);
        if (false === $result)
            return false;

        if ( '000' != $result['statusCode'] )
            return $this->sendErrorMessage(false, 0, '订单不能续投', '订单不能续投');

        $criteria = new CDbCriteria;
        $criteria->condition = "last_sn=:sn AND status=:status";
        $criteria->params = array(':sn'=>$sn, ':status'=>Item::PROFIT_ORDER_AUTHORIZE);

        $nextOrderModel =  AppreciationPlan::model()->find($criteria);
        if ($nextOrderModel)
            return $this->sendErrorMessage(false, 0, '订单号已存在续投订单', '订单号已存在续投订单');

        return $orderModel;
    }

    /**
     * 根据用户ID查询订单
     * @param $customerId
     * @return mixed
     */
    public function verifyUserType($customerId)
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('customer_id='.$customerId);
        $criteria->addCondition('status='.Item::PROFIT_ORDER_AUTHORIZE);
        $model = AppreciationPlan::model()->find($criteria);
        unset($criteria);

        $result = array('verify'=>0);
        if ($model) {
            $result['verify'] = 2;
            $data = $this->makeOrderUrl($model->sn);
            if (false === $data)
                return $this->sendErrorMessage(false, 0, $this->getErrorMsg(), $this->getLogMsg());
            $result = array_merge($result, $data);
        }
        else {
            $inStatus = array(
                Item::PROFIT_ORDER_SUCCESS,
                Item::PROFIT_ORDER_CONTINUOUS,
                Item::PROFIT_ORDER_EXTRACT_ING,
                Item::PROFIT_ORDER_EXTRACT_SUCCESS,
                Item::PROFIT_ORDER_EXTRACT_FAIL,
                Item::PROFIT_ORDER_REDEEM_ING,
                Item::PROFIT_ORDER_REDEEM_SUCCESS,
                Item::PROFIT_ORDER_REDEEM_FAIL
            );

            $criteria = new CDbCriteria;
            $criteria->addCondition('customer_id='.$customerId);
            $criteria->addInCondition('status', $inStatus);

            $model = AppreciationPlan::model()->find($criteria);

            if ($model) {
                $result['verify'] = 1;
            }
        }

        return $result;
    }

    /**
     * 获取订单续投、提现状态
     * @param $sn
     * @param $customerId
     * @return mixed
     *
     * statusCode 000成功
     *  ordStatus订单状态
     *       1 正在投资中    可续投，不可提现
     *       2 已赎回待提现    可续投，可提现
     *
     * statusCode 001
     *  statusDesc  不能续投，提现
     *  statusDesc  不能续投，提现 或者md5验证失败
     */
    private function getOrderContinueState($sn, $customerId)
    {
        $para = array(
            'orderSN' => $sn,
            'userId' => $customerId,
            'reqTime' => time(),
        );

        $queryUrl = $this->serverUrl.'activity/colorlife/increaseStatus';
        $queryData = $this->makeQueryData($para);

        $result =  Yii::app()->curl->get($queryUrl, $queryData);
        if ( ! $result)
            return $this->sendErrorMessage(false, 0, '请求合和年数据异常', '请求合和年数据异常');

        $result = json_decode($result, true);
        if (!isset($result['statusCode']))
            return $this->sendErrorMessage(false, 0, '请求合和年数据返回异常', '请求合和年数据返回异常');

        return $result;
    }

    /**
     * 续投 根据订单号获取缴费地址详情
     * @param $sn
     * @return array
     */
    public function getAddressBySn($sn)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);
        if ( !$orderModel)
            return $this->sendErrorMessage(false, 0, '续投订单不存在', '续投订单不存在');

        $cId = '';
        $cName = '';
        $buildId = '';
        $build = '';
        $roomId = '';
        $room = '';
        $address ='';
        $result = array();

        if ( intval($orderModel->AdvanceFees->community_id) < 0) {
            $cId = $orderModel->AdvanceFees->community_id;

            $addressArr = json_decode($orderModel->AdvanceFees->build, true);
            $cName = isset($addressArr['community']) ? $addressArr['community'] : '';
            $build = isset($addressArr['build']) ? $addressArr['build'] : '';
            $room = isset($addressArr['room']) ? $addressArr['room'] : '';
            $result['buildId'] = $buildId; //$orderModel->AdvanceFees->colorcloud_building;
            $result['roomId'] = $roomId; //$orderModel->AdvanceFees->colorcloud_unit;
            $address = $addressArr['province'].$addressArr['city'].$addressArr['district'].$addressArr['community'].$addressArr['build'].$addressArr['room'];
        }
        //兼容银湾
        else if(intval($orderModel->AdvanceFees->community_id) == 0){
            $addressArr = json_decode($orderModel->AdvanceFees->room, true);
            $result['provice_code'] = $addressArr['provice_code'];
            $result['provice_name'] = $addressArr['provice_name'];
            $result['city_code'] = $addressArr['city_code'];
            $result['city_name'] = $addressArr['city_name'];
            $result['area_code'] = $addressArr['area_code'];
            $result['area_name'] = $addressArr['area_name'];
            $result['buildId'] = $addressArr['build_code'];
            $result['roomId'] = $addressArr['room_code'];



            $cId = $addressArr['community_code'];
            $cName =$addressArr['community_name'];
            $build = $addressArr['build_name'];
            $room = $addressArr['room_name'];
            $address = $addressArr['provice_name'].$addressArr['city_name'].$addressArr['area_name'].$addressArr['community_name'].$addressArr['build_name'].$addressArr['room_name'];
        }
        else {
            $communityModel = ICECommunity::model()->enabled()->findByPk($orderModel->AdvanceFees->community_id);
            if (!empty($communityModel)) {
                $cId = $communityModel->czy_id;
                $cName = $communityModel->name;
                $buildId = $orderModel->AdvanceFees->colorcloud_building;;
                $build = $orderModel->AdvanceFees->build;
                $roomId = $orderModel->AdvanceFees->colorcloud_unit;
                $room = $orderModel->AdvanceFees->room;
                $address = $communityModel->getCommunityAddress();
                $result['buildId'] = $buildId; //$orderModel->AdvanceFees->colorcloud_building;
                $result['roomId'] = $roomId; //$orderModel->AdvanceFees->colorcloud_unit;
            }

        }


        $communityModel = ICECommunity::model()->enabled()->findByPk($orderModel->AdvanceFees->community_id);
        if ($communityModel) {
            $result['communityId'] = $cId; //$communityModel->id;
            $result['communityName'] = $cName; //$communityModel->name;
            $result['build'] = $build; //$orderModel->AdvanceFees->build;
            $result['room'] = $room; //$orderModel->AdvanceFees->room;
            $result['address'] = $address;//$communityModel->getCommunityAddress();
        }

        return $result;
    }

    /**
     * 投资产品数据
     * @param $customerId
     * @param string $sn
     * @return array|bool
     */
    public function appreciationPlanList($customerId, $sn='')
    {
        $result = array();

        // 冲抵周期开始时间
        $beginTime = strtotime("+1 day");
        if ($sn) {
            $orderModel = $this->verifySn($sn, $customerId);
            if (false === $orderModel)
                return false;

            if ($orderModel->stop_time > time())
                $beginTime = $orderModel->stop_time + 86400;
        }

        foreach ($this->appreciationPlanNames as $key => $val) {
            $info['id'] = $key;
            $info['name'] = $val;

            $month = $this->appreciationPlanMonths[$key];
            $info['month'] = $month;

            $rate = $this->appreciationPlanRates[$month];
            $info['rate'] = $rate;

            $profitData = $this->makeProfitData($this->singleStep, $rate, $month);

            // 成功参加订单状态
            $countStatus = array(
                Item::PROFIT_ORDER_SUCCESS,
                Item::PROFIT_ORDER_CONTINUOUS,
                Item::PROFIT_ORDER_EXTRACT_ING,
                Item::PROFIT_ORDER_EXTRACT_SUCCESS,
                Item::PROFIT_ORDER_EXTRACT_FAIL
            );

            $countRate = Yii::app()->db->createCommand()
                ->select(array('sum(amount) as sa', 'count(id) as ct'))
                ->from('appreciation_plan')
                ->where(array('in', 'status', $countStatus))
                ->andWhere('rate_id=:rate_id', array(':rate_id'=>$key))
                ->queryRow();

            $countRateNum = "<font color='#ff7e00'>".$countRate['ct']."</font>";
            $countRateMoney = "<font color='#ff7e00'>".F::priceFormat(($countRate['sa'])/10000)."</font>";
            $rateText = $rate * 100;

            $info['text'] = "高于银行{$month}个月定期存款利率，预期年化收益{$rateText}%，定投周期{$month}个月，已有{$countRateNum}户业主参加，定投金额{$countRateMoney}万元";

            // 增值周期
            $investDate = $this->makeProfitDate($beginTime, $month);
            $info['begin_time'] = $investDate['first_day'];
            $info['stop_time'] = date('Y-m-d', (strtotime($investDate['last_day']) - 1));
            $info['recover_time'] = $investDate['recover_day'];

            $result[] = array_merge($info, $profitData);
        }

        return $result;
    }

    /**
     * 获取增值计划计算数据
     * @param $projectId
     * @return array
     */
    public function appreciationPlanParam($projectId)
    {
        if ( !in_array($projectId, array(
            self::APPRECIATION_PLAN_FIRST,
            self::APPRECIATION_PLAN_SECOND,
            self::APPRECIATION_PLAN_THIRD,
            self::APPRECIATION_PLAN_FOURTH)
        )) {
            return $this->sendErrorMessage(false, 0, '参数错误', '参数错误');
        }

        $month = $this->appreciationPlanMonths[$projectId];

        $rate = $this->appreciationPlanRates[$month];
        $bankRate = $this->bankRates[$month];

        $diff = sprintf('%.2f', ($rate / $bankRate));

        return array(
            'rate'  => sprintf('%.3f', $rate),
            'diff'  => $diff,
            'temp'  => sprintf('%.2f', $this->singleStep),
            'month' => $month,
            'tip'   => "全新升级，现在参加，可自由增加定投金额（限{$this->singleStep}元整数倍），坐享更多收益回报。",
            'fp_tip'=> '饭票是指可以抵扣彩之云平台专属代金券，下单成功后投资到期该收益发放至“彩之云-我的饭票”。'
        );
    }

    /**
     * 详情
     * @param $customerId
     * @param $projectId
     * @param $num
     * @param $communityId
     * @param $sn
     * @param $build
     * @return array|bool
     */
    public function appreciationPlanInfo($customerId, $projectId, $num, $communityId, $sn, $build='')
    {
        if ( !in_array($projectId, array(
                self::APPRECIATION_PLAN_FIRST,
                self::APPRECIATION_PLAN_SECOND,
                self::APPRECIATION_PLAN_THIRD,
                self::APPRECIATION_PLAN_FOURTH)
        )) {
            return $this->sendErrorMessage(false, 0, '增值计划项目参数错误', '增值计划项目参数错误');
        }

        // 第三方订单 -1 银湾;
        if (intval($communityId) < 0) {
            $address = implode('', array_values(json_decode($build, true)));
        }
        else {
            $communityModel = ICECommunity::model()->enabled()->findByPk($communityId);
            if ( !$communityModel )
                return $this->sendErrorMessage(false, 0, '小区参数错误', '小区参数错误');

            $address = $communityModel->getCommunityAddress();
        }


        $month = $this->appreciationPlanMonths[$projectId];
        $rate = $this->appreciationPlanRates[$month];

        $investMoney = $this->singleStep * $num;
        $profitData = $this->makeProfitData($investMoney, $rate, $month);

        $result = array();
        $result['project_id'] = $projectId;
        $result['project_name'] = $this->appreciationPlanNames[$projectId];
        $result['project_month'] = $month;
        $result['user_rate'] = $rate;
        $result['community_id'] = $communityId;
        $result['address'] = $address;

        $result['invest_money'] = $profitData['invest_money'];
        $result['profit_money'] = $profitData['profit_money'];
        $result['return_money'] = $profitData['return_money'];

        $result['pay_money'] = $result['invest_money'];

        $result['last_order'] = '';

        // 增值周期开始时间
        $beginTime = strtotime("+1 day");
        if ($sn) {
            $orderModel = $this->verifySn($sn, $customerId);
            if (false === $orderModel)
                return false;

            $lastOrder = array(
                'sn' => $orderModel->sn,
                'last_stop_time' => date('Y-m-d', $orderModel->stop_time),
                'last_return_money' => F::price_formatNew($orderModel->amount + $orderModel->profit),
            );

            $payMoney = $result['invest_money'] - $lastOrder['last_return_money'];

            $result['last_order'] = $lastOrder;
            $result['pay_money'] = F::price_formatNew($payMoney <= 0 ? 0 : $payMoney);

            if ($orderModel->stop_time > time())
                $beginTime = $orderModel->stop_time + 86400;
        }

        // 增值周期
        $investDate = $this->makeProfitDate($beginTime, $month);
        $result['begin_time'] = $investDate['first_day'];
        $result['stop_time'] = $investDate['last_day'];
        $result['recover_time'] = $investDate['recover_day'];

        return $result;
    }

    /**
     * 生成 提交到合和年 订单url
     * @param $sn
     * @param $type
     * @return array
     */
    public function makeOrderUrl($sn, $type='app',$accountType = 0)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel)
            return $this->sendErrorMessage(false, 0, '订单号错误', '订单号错误');

        if ( !in_array($orderModel->status, array(Item::PROFIT_ORDER_INIT, Item::PROFIT_ORDER_AUTHORIZE)) )
            return $this->sendErrorMessage(false, 0, '订单状态不合法', '订单状态不合法');

        $cId = '';
        $cName = '';

        if ( intval($orderModel->AdvanceFees->community_id) < 0) {
            $cId = $orderModel->AdvanceFees->community_id;
            $addressArr = json_decode($orderModel->AdvanceFees->build, true);
            $cName = isset($addressArr['community']) ? $addressArr['community'] : '';
            $address = $addressArr['province'].$addressArr['city'].$addressArr['district'].$addressArr['community'].$addressArr['build'].$addressArr['room'];
        }
        else if(intval($orderModel->AdvanceFees->community_id) == 0){
            $addressArr = json_decode($orderModel->AdvanceFees->room, true);
            $cId = $addressArr['community_code'];
            $cName = $addressArr['community_name'];
            $address = $addressArr['provice_name'].$addressArr['city_name'].$addressArr['area_name'].$addressArr['community_name'].$addressArr['build_name'].$addressArr['room_name'];
        }
        else{
            $communityModel = ICECommunity::model()->enabled()->findByPk($orderModel->AdvanceFees->community_id);
            if (!empty($communityModel)) {
                $cId = $communityModel->czy_id;
                $cName = $communityModel->name;
            }

            $address = $orderModel->getAddress();
        }


        $para = array(
            'username' => $orderModel->customer->name ? $orderModel->customer->name : $orderModel->customer->username,
            'userId' => $orderModel->customer_id,
            'mobile' => $orderModel->customer->mobile,
            'cId' => $cId,
            'cName' => $cName,
            //'cId' => $orderModel->AdvanceFees->community->id,
            //'cName' => $orderModel->AdvanceFees->community->name,
            'billingAddress' => $address, //$orderModel->getAddress(),
            'rateType' => $orderModel->rate_id,
            'ordNo' => $orderModel->sn,
            'beginDate' => date('Ymd', $orderModel->begin_time),
            'endDate' => date('Ymd', $orderModel->stop_time),
            'ordDate' => date('YmdHis', $orderModel->create_time),
            'amount' => $orderModel->amount,
            'profit' => $orderModel->profit,
            'profitType' => $orderModel->profit_type,
            'userRate' => $orderModel->user_rate,
            'orderType' => 0,
            'linkOrdNo' => '',
            'linkAmount' => 0,
            'linkOrdBalance' => 0,
        	'accountType' => $accountType
        );

        if ($orderModel->last_sn) {
            $criteria = new CDbCriteria;
            $criteria->condition = "sn=:sn";
            $criteria->params = array(':sn'=>$orderModel->last_sn);
            $lastOrderModel = AppreciationPlan::model()->find($criteria);

            $para['orderType'] = 1;
            $para['linkOrdNo'] = $orderModel->last_sn;
            $para['linkAmount'] = $lastOrderModel->surplus_money;
        }

        $payAmount = $para['amount'] - $para['linkAmount'];
        $para['linkOrdBalance'] = $payAmount > 0 ? 0 : abs($payAmount);
        $para['payAmount'] = $payAmount > 0 ? $payAmount : 0;

        $queryUrl = $this->serverUrl.'increment/app-increment.do';
        if ('pc' === $type)
            $queryUrl = $this->serverUrl.'increment/web-increment.do';

        $queryData = $this->makeQueryData($para);

        return array(
            'title' => '增值计划',
            'sn' => $sn,
            'url'=> Yii::app()->curl->buildUrl($queryUrl, $queryData)
        );
    }

    /**
     * 绑卡
     * @param $id
     * @param $username
     * @param $mobile
     * @param string $type
     * @return array
     */
    public function makeBindCardUrl($id, $username, $mobile, $type='app')
    {
        $para = array(
            'username' => $username,
            'userId' => $id,
            'mobile' => $mobile
        );

        $queryUrl = $this->serverUrl.'increment/app-increment-bankcard.do';
        if ('pc' === $type)
            $queryUrl = $this->serverUrl.'increment/web-increment-bankcard.do';

        $queryData = $this->makeQueryData($para);

        return array(
            'title' => '增值计划绑卡',
            'url'=> Yii::app()->curl->buildUrl($queryUrl, $queryData)
        );
    }

    /**
     * 生成 提交到合和年 提现url
     * @param $sn
     * @param string $type
     * @return array
     */
    public function makeWithdrawUrl($sn, $type='app')
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel)
            return $this->sendErrorMessage(false, 0, '订单号错误', '订单号错误');

        if ( !in_array($orderModel->status, array(Item::PROFIT_ORDER_SUCCESS, Item::PROFIT_ORDER_CONTINUOUS, Item::PROFIT_ORDER_REDEEM_SUCCESS, Item::PROFIT_ORDER_EXTRACT_FAIL)) )
            return $this->sendErrorMessage(false, 0, '订单状态不可提现', '订单状态不可提现');

        // 判断是否到提现时间
        //$isCanWithdraw = $orderModel->recover_time < time() ? true : false;
        //if (false == $isCanWithdraw)
        //    return $this->sendErrorMessage(false, 0, '订单未到期，不可提现', '订单未到期，不可提现');

        $customerModel = Customer::model()->findByPk($orderModel->customer_id);
        if ( !$customerModel)
            return $this->sendErrorMessage(false, 0, '订单用户不存在', '订单单用户不存在');

        $cId = '';
        $cName = '';

        if ( intval($orderModel->AdvanceFees->community_id) < 0) {
            $cId = $orderModel->AdvanceFees->community_id;
            $addressArr = json_decode($orderModel->AdvanceFees->build, true);
            $cName = isset($addressArr['community']) ? $addressArr['community'] : '';
            $address = $addressArr['province'].$addressArr['city'].$addressArr['district'].$addressArr['community'].$addressArr['build'].$addressArr['room'];
        }
        else {
            $communityModel = ICECommunity::model()->enabled()->findByPk($orderModel->AdvanceFees->community_id);
            if (!empty($communityModel)) {
                $cId = $communityModel->czy_id;
                $cName = $communityModel->name;
            }

            $address = $orderModel->getAddress();
        }

        // 请求是否可提现
        $result = $this->getOrderContinueState($sn, $orderModel->customer_id);
        if (false === $result)
            return false;

        //if ('001' == $result['statusCode'])
        //    return $this->sendErrorMessage(false, 0, $result['statusDesc'], $result['statusDesc']);

        if ('000' != $result['statusCode'] || '2' != $result['ordStatus'])
            return $this->sendErrorMessage(false, 0, '合和年返回订单不可提现', '合和年返回订单不可提现');

        $para = array(
            'username' => $orderModel->customer->name ? $orderModel->customer->name : $orderModel->customer->username,
            'userId' => $orderModel->customer_id,
            'mobile' => $orderModel->customer->mobile,
            'cId' => $cId,//$orderModel->AdvanceFees->community->id,
            'cName' => $cName,//$orderModel->AdvanceFees->community->name,
            'billingAddress' => $address,//$orderModel->getAddress(),
            'rateType' => $orderModel->rate_id,
            'ordNo' => $orderModel->sn,
            'beginDate' => date('Ymd', $orderModel->begin_time),
            'endDate' => date('Ymd', $orderModel->stop_time),
            'ordDate' => date('YmdHis', $orderModel->create_time),
            'amount' => $orderModel->amount,
            'profit' => $orderModel->profit,
            'profitType' => $orderModel->profit_type,
            'userRate' => $orderModel->user_rate,
            'orderType' => 0,
            'linkOrdNo' => '',
            'linkAmount' => 0,
            'linkOrdBalance' => 0,
        );

        $queryUrl = $this->serverUrl.'increment/app-increment.do';
        if ('pc' === $type)
            $queryUrl = $this->serverUrl.'increment/web-increment.do';

        $queryData = $this->makeQueryData($para);

        return array(
            'title' => '增值计划',
            'sn' => $sn,
            'surplusMoney' => $result['amount'],//F::price_formatNew($orderModel->surplus_money),
            'url'=> Yii::app()->curl->buildUrl($queryUrl, $queryData)
        );
    }

    /**
     * 提前赎回
     * @param $sn
     * @return bool|mixed
     * @throws CException
     */
    public function makeRedeemUrl($sn)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单号不存在', '订单号不存在');

        Yii::import('common.api.HhNianApi');
        $result = HhNianApi::getInstance()->orderRedeemStatus($sn, $orderModel->customer_id);
        if (false === $result)
            return $this->sendErrorMessage(false, 0, '请求合和年订单赎回状态接口异常', '请求合和年订单赎回状态接口异常');

        if ( '000' != $result['statusCode'])
            return $this->sendErrorMessage(false, 0, $result['statusDesc'], $result['statusDesc']);

        return HhNianApi::getInstance()->orderCancel($sn);
    }

    /**
     * 判断是否显示服务协议
     * @param $sn
     * @return bool
     */
    public function isShowPact($sn)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单号不存在', '订单号不存在');

        $orderStatus = array(
            Item::PROFIT_ORDER_SUCCESS,
            Item::PROFIT_ORDER_CONTINUOUS,
            Item::PROFIT_ORDER_EXTRACT_ING,
            Item::PROFIT_ORDER_EXTRACT_SUCCESS,
            Item::PROFIT_ORDER_EXTRACT_FAIL,
            Item::PROFIT_ORDER_REDEEM_ING,
            Item::PROFIT_ORDER_REDEEM_SUCCESS,
            Item::PROFIT_ORDER_REDEEM_FAIL,
            Item::PROFIT_ORDER_REDEEM_DONE
        );

        if ( !in_array($orderModel->status, $orderStatus))
            return $this->sendErrorMessage(false, 0, '订单未支付', '订单未支付');

        if ($orderModel->begin_time > time())
            return $this->sendErrorMessage(false, 0, '订单未生效', '订单未生效');

        return true;
    }

    /**
     * 创建订单
     * @param $saveData
     * @return array
     */
    public function createOrder($saveData)
    {
        $saveData['sn'] = SN::initByPropertyActivity()->sn;

        $model = new AppreciationPlan();
        $model->sn = $saveData['sn'];
        $model->rate_id = $saveData['rate_id'];
        $model->customer_id = $saveData['customer_id'];
        $model->amount = $saveData['amount'];
        $model->user_rate = $saveData['user_rate'];
        $model->profit = $saveData['profit'];
        $model->surplus_money = $saveData['surplus_money'];
        $model->begin_time = $saveData['begin_time'];
        $model->stop_time = $saveData['stop_time'] - 1; // 冲抵结束时间到23:59:59
        $model->recover_time = $saveData['recover_time'];
        $model->profit_type = $saveData['profit_type'];

        $model->last_sn = $saveData['last_sn'];
        $model->inviter_mobile = $saveData['inviter_mobile'];
        $model->source = $saveData['source'];
        $model->create_time = time();

        //推荐人提成
        $model->inviter_amount = F::price_formatNew($saveData['amount'] * Yii::app()->config->caifuTichengRate / 12 * $saveData['month']);

        //dump($model);
        $feeModel = new AdvanceFee();
        if(isset($saveData['yinwan']) && $saveData['yinwan'] == 'yinwan'){
            $feeModel->room = $saveData['room'];
        }else{
            $feeModel->community_id = $saveData['community_id'];
            $feeModel->build = $saveData['build'];
            $feeModel->room = $saveData['room'];
        }



        if ($feeModel->save()) {
            $model->object_id = $feeModel->id;
            if ($model->save()) {
                return array(
                    'sn' => $model->sn,
                );
            } else {
                $oldRe = $feeModel::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $feeModel->id)));
                $oldRe->delete();
                return $this->sendErrorMessage(false, 0, '创建订单失败', $model->getErrors());
            }
        } else {
            return $this->sendErrorMessage(false, 0, '创建订单失败', $feeModel->getErrors());
        }

    }

    /**
     * 获取订单列表
     * @param $customerId
     * @param $orderStatusType
     * @param $page
     * @param $pageSize
     * @return array|CActiveRecord|mixed|null
     */
    public function getOrderList($customerId, $orderStatusType, $page=1, $pageSize=10)
    {
        $statusTypeList = array('all', 'success', 'connection');

        if ( !in_array($orderStatusType, $statusTypeList))
            return $this->sendErrorMessage(false, 0, '订单状态类别参数错误', '订单状态类别参数错误');

        $criteria = new CDbCriteria;
        $criteria->addCondition('customer_id='.$customerId);

        $statusArr = array();
        switch ($orderStatusType) {
            case 'all' :
                $statusArr = array();
                break;
            case 'success' :
                $statusArr = array(
                    Item::PROFIT_ORDER_SUCCESS,
                    Item::PROFIT_ORDER_CONTINUOUS,
                    Item::PROFIT_ORDER_EXTRACT_ING,
                    Item::PROFIT_ORDER_EXTRACT_SUCCESS,
                    Item::PROFIT_ORDER_EXTRACT_FAIL,
                    Item::PROFIT_ORDER_REDEEM_ING,
                    Item::PROFIT_ORDER_REDEEM_SUCCESS,
                    Item::PROFIT_ORDER_REDEEM_FAIL,
                );
                break;
            case 'connection' :
                $statusArr = array(
                    Item::PROFIT_ORDER_SUCCESS,
                );
                //$criteria->addCondition('last_sn !=""');
                break;
        }

        if ($statusArr)
            $criteria->addInCondition('status', $statusArr);

        if ('connection' == $orderStatusType)
            $criteria->addCondition('begin_time<'.time());

        $criteria->order = 'create_time desc';

        // 总条数
        $total = AppreciationPlan::model()->count($criteria);

        $criteria->offset = ( $page - 1 ) * $pageSize;
        $criteria->limit = $pageSize;


        $list = AppreciationPlan::model()->findAll($criteria);

        return array(
            'total' => $total,
            'list'  => $list
        );
    }

    /**
     * 获取投资总信息
     * @param $customerId
     * @return mixed
     */
    public function getProfitTotal($customerId)
    {
//        // 成功参加订单状态
//        $countStatus = array(
//            Item::PROFIT_ORDER_SUCCESS,
//            Item::PROFIT_ORDER_CONTINUOUS,
//            Item::PROFIT_ORDER_EXTRACT_ING,
//            Item::PROFIT_ORDER_EXTRACT_SUCCESS,
//            Item::PROFIT_ORDER_EXTRACT_FAIL
//        );
//
//        try {
//            $result = Yii::app()->db->createCommand()
//                ->select(array('sum(amount) as total_amount', 'count(id) as total_num', 'sum(profit) as total_profit'))
//                ->from('appreciation_plan')
//                ->where(array('in', 'status', $countStatus))
//                ->andWhere('customer_id=:customer_id', array(':customer_id'=>$customerId))
//                ->queryRow();
//        } catch(Exception $e) {
//            return $this->sendErrorMessage(false, 0, '获取投注信息失败', $e->getMessage());
//        }
//
//        return $result;


        $para = array(
            'userId' => $customerId,
            'reqTime' => time(),
        );

        $queryUrl = $this->serverUrl.'activity/colorlife/appPlanUserincome';
        $queryData = $this->makeQueryData($para);

        $result =  Yii::app()->curl->get($queryUrl, $queryData);
        if ( ! $result)
            return $this->sendErrorMessage(false, 0, '请求合和年数据异常', '请求合和年数据异常');

        $result = json_decode($result, true);

        return $result;
    }

    /**
     * 获取订单button [buttonStyle 1-可跳转 0-不可跳转] [buttonType 0-续投 1-提现]
     * @param $orderModel
     * @param $version
     * @return array
     */
    public function getOrderButton($orderModel, $version='')
    {
        if (empty($orderModel))
            return array();

        $buttons = array();

        // 判断是否到提现时间 (提现当日7点后才可提现)
        $isCanWithdraw =  (time() - $orderModel->recover_time > 25200) ? true : false;

        if (Item::PROFIT_ORDER_SUCCESS == $orderModel->status) {

            $version = $version ? str_pad(str_replace('.', '', $version), 4, "0", STR_PAD_RIGHT) : $version;
            /**
             * 版本号为空 pc端
             * 客户端版本号3.0.1以下 不能续投
             */
            if ($version >= '3010') {
                if ($orderModel->begin_time > time()) {
                    $buttons[] = array(
                        'buttonStyle' => 0,
                        'buttonType' => 0,
                        'buttonName' => '续投',
                        'buttonTip' => ''
                    );
                }
                else {
                    $buttons[] = array(
                        'buttonStyle' => 0,
                        'buttonType' => 0,
                        'buttonName' => '续投',
                        'buttonTip' => ''
                    );
                }
            }

            if ($isCanWithdraw) {
                $buttons[] = array(
                    'buttonStyle' => 1,
                    'buttonType' => 1,
                    'buttonName' => '提现',
                    'buttonTip' => ''
                );
            }
            else {
                $buttons[] = array(
                    'buttonStyle' => 0,
                    'buttonType' => 0,
                    'buttonName' => '提现',
                    'buttonTip' => ''
                );
            }
        }
        elseif (Item::PROFIT_ORDER_CONTINUOUS == $orderModel->status) {
            if ($orderModel->surplus_money > 0 ) {
                if ($isCanWithdraw) {
                    $buttons[] = array(
                        'buttonStyle' => 1,
                        'buttonType' => 1,
                        'buttonName' => '提现',
                        'buttonTip' => ''
                    );
                }
                else {
                    $buttons[] = array(
                        'buttonStyle' => 0,
                        'buttonType' => 0,
                        'buttonName' => '提现',
                        'buttonTip' => ''
                    );
                }
            }
            else {
                $buttons[] = array(
                    'buttonStyle' => 0,
                    'buttonType' => 0,
                    'buttonName' => '已续投',
                    'buttonTip' => ''
                );
            }
        }
        elseif (Item::PROFIT_ORDER_EXTRACT_ING == $orderModel->status) {
            $buttons[] = array(
                'buttonStyle' => 0,
                'buttonType' => 0,
                'buttonName' => '提现中',
                'buttonTip' => ''
            );
        }
        elseif (Item::PROFIT_ORDER_EXTRACT_SUCCESS == $orderModel->status) {
            $buttons[] = array(
                'buttonStyle' => 0,
                'buttonType' => 0,
                'buttonName' => '提现成功',
                'buttonTip' => ''
            );
        }
        elseif (Item::PROFIT_ORDER_EXTRACT_FAIL == $orderModel->status
                || Item::PROFIT_ORDER_REDEEM_SUCCESS == $orderModel->status
        ) {
            $buttons[] = array(
                'buttonStyle' => 1,
                'buttonType' => 1,
                'buttonName' => '提现',
                'buttonTip' => ''
            );
        }
        else {
            $buttons[] = array(
                'buttonStyle' => 0,
                'buttonType' => 0,
                'buttonName' => '提现',
                'buttonTip' => ''
            );
        }

        return $buttons;
    }

    /**
     * 判断订单是否支付成功 1-成功 0-失败
     * @param $sn
     * @param $customerId
     * @return array
     */
    public function getOrderPayStatus($sn, $customerId)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单号不存在', '订单号不存在');

        $result = array('sn'=>$sn, 'status' => 1);

        if (Item::PROFIT_ORDER_SUCCESS == $orderModel->status)
            return $result;

        $orderStatusDetail = $this->queryOrderStatus($sn, $customerId);
        if (false === $orderStatusDetail)
            return $this->sendErrorMessage(false, 0, $this->getErrorMsg(), $this->getLogMsg());

        if ('004' != $orderStatusDetail['statusCode'])
            return $this->sendErrorMessage(false, 0, $orderStatusDetail['status'], $orderStatusDetail['status']);

        if ( self::ORDER_STATUS_SUCCESS !== $orderStatusDetail['order']['ordStatus'])
            $result['status'] = 0;

        return $result;
    }

    /**
     * 请求合和年订单状态
     * @param $sn
     * @param $customerId
     * @return mixed
     */
    public function queryOrderStatus($sn, $customerId)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单号不存在', '订单号不存在');

        $para = array(
            'userId' => $customerId,
            'reqTime' => time(),
            'orderSN' => $sn
        );

        $queryUrl = $this->serverUrl.'activity/colorlife/orderDetailStatus';
        $queryData = $this->makeQueryData($para);

        $result =  Yii::app()->curl->get($queryUrl, $queryData);
        if ( ! $result)
            return $this->sendErrorMessage(false, 0, '请求合和年订单状态异常', '请求合和年订单状态异常');

        $result = json_decode($result, true);

        return $result;
    }

    /**
     * 更新订单为已授权
     * @param $sn
     * @param $customerId
     * @param $realName
     * @param $cardID
     * @param $paymentPassage 支付通道 1 汇付 2 定期理财（里面有4个支付渠道的， 连连，快钱，双乾，通联）
     * @return bool
     */
    public function orderAuthorize($sn, $customerId, $realName, $cardID, $paymentPassage)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_AUTHORIZE == $orderModel->status)
            return true;

        if (Item::PROFIT_ORDER_INIT != $orderModel->status)
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[已授权]状态', '订单禁止修改为[已授权]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

        $orderModel->status = Item::PROFIT_ORDER_AUTHORIZE;
        $orderModel->customer_name = $realName;
        $orderModel->customer_card = $cardID;
        $orderModel->payment_passage = $paymentPassage;
        $orderModel->update_time = time();

        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '订单更新为[已授权]失败', $orderModel->getErrors());

        return true;
    }

    /**
     * 更新订单为已取消
     * @param $sn
     * @param $customerId
     * @return bool
     */
    public function orderCancel($sn, $customerId)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_CANCEL == $orderModel->status)
            return true;

        if ( !in_array($orderModel->status, array(Item::PROFIT_ORDER_INIT, Item::PROFIT_ORDER_AUTHORIZE)))
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[已取消]状态', '订单禁止修改为[已取消]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

        $orderModel->status = Item::PROFIT_ORDER_CANCEL;
        $orderModel->update_time = time();

        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '订单更新为[已取消]失败', $orderModel->getErrors());

        return true;
    }

    /**
     * 更新订单为交易成功
     * @param $sn
     * @param $customerId
     * @param $orderPaySn
     * @param $orderSuccessTime
     * @param $paymentPassage 支付通道 1 汇付 2 定期理财（里面有4个支付渠道的， 连连，快钱，双乾，通联）
     * @return bool
     */
    public function orderPaySuccess($sn, $customerId, $orderPaySn, $orderSuccessTime, $paymentPassage)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_SUCCESS == $orderModel->status)
            return true;

        //if (Item::PROFIT_ORDER_AUTHORIZE != $orderModel->status)
        if ( ! in_array($orderModel->status, array(Item::PROFIT_ORDER_AUTHORIZE)))
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[已支付]状态', '订单禁止修改为[已支付]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

        // 冲抵开始时间
        $beginTime = $orderSuccessTime+86400;

        $orderModel->status = Item::PROFIT_ORDER_SUCCESS;
        $orderModel->pay_sn = $orderPaySn;
        $orderModel->pay_time = $orderSuccessTime;
        $orderModel->payment_passage = $paymentPassage;
        $orderModel->update_time = time();

        // 开启事物
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        if ($orderModel->last_sn) {
            $criteria = new CDbCriteria;
            $criteria->condition = "sn=:sn";
            $criteria->params = array(':sn'=>$orderModel->last_sn);
            $lastOrderModel = AppreciationPlan::model()->find($criteria);

            if ( !$lastOrderModel ) {
                $transaction->rollBack();
                return $this->sendErrorMessage(false, 0, '历史订单不存在', '历史订单不存在');
            }

            if ( Item::PROFIT_ORDER_SUCCESS != $lastOrderModel->status) {
                $transaction->rollBack();
                return $this->sendErrorMessage(false, 0, '历史订单状态错误', '历史订单状态错误');
            }

            $lastOrderModel->status = Item::PROFIT_ORDER_CONTINUOUS;
            $lastOrderModel->next_sn = $orderModel->sn;
            $lastOrderModel->surplus_money = $lastOrderModel->surplus_money > $orderModel->amount ? $lastOrderModel->surplus_money - $orderModel->amount : 0;

            if ( !$lastOrderModel->save()) {
                $transaction->rollBack();
                return $this->sendErrorMessage(false, 0, '历史订单更新失败', $orderModel->$lastOrderModel());
            }

            // 续投订单冲抵时间
            if ($lastOrderModel->stop_time > time())
                $beginTime = $lastOrderModel->stop_time + 86400;
        }

        $month = $this->getAppreciationPlanMonth($orderModel->rate_id);
        $dates = $this->makeProfitDate($beginTime, $month);

        $orderModel->begin_time = strtotime($dates['first_day']);
        $orderModel->stop_time = strtotime($dates['last_day']) - 1; // 冲抵结束时间到23:59:59
        $orderModel->recover_time = strtotime($dates['recover_day']);

        if ( ! $orderModel->save() ) {
            $transaction->rollBack();
            return $this->sendErrorMessage(false, 0, '订单更新为[已支付]失败', $orderModel->getErrors());
        }

        $transaction->commit();

        //TODO 保险活动 活动到期去掉
        /**
        Yii::import('common.services.InsureService');
        $insureService = new InsureService();
        $insureService->profitActivity($customerId, $sn, $orderModel->amount, $orderSuccessTime);
        **/

        //TODO 彩富VIP尊享活动 到期删除
        if (strtotime('2017-06-30 23:59:59') > $orderSuccessTime && strtotime('2016-09-14 00:00:00') < $orderSuccessTime) {

            $month = $this->getAppreciationPlanMonth($orderModel->rate_id);
            $amount = $orderModel->amount;
            if ( $month >= 12 && $amount >= 100000) {
                $sql = 'SELECT `id` FROM `profit_shop_coupon` WHERE `relation_id`=:relation_id';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(':relation_id', $sn, PDO::PARAM_STR);

                $result = $command->queryRow();

                if ( !$result) {
                    $data = array(
                        'customer_id' => $customerId,
                        'relation_id' => $sn,
                        'status'      => 0,
                        'create_time' => time()
                    );

                    try {
                        Yii::app()->db->createCommand()->insert('profit_shop_coupon', $data);
                    }
                    catch (Exception $e) {
                    }
                }
            }
        }


        return true;
    }

    /**
     * 更新订单为提现中
     * @param $sn
     * @param $customerId
     * @param $cashCreateTime
     * @return bool
     */
    public function orderWithdrawIng($sn, $customerId, $cashCreateTime)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_EXTRACT_ING == $orderModel->status)
            return true;

        if ( ! in_array($orderModel->status, array(Item::PROFIT_ORDER_SUCCESS, Item::PROFIT_ORDER_CONTINUOUS, Item::PROFIT_ORDER_EXTRACT_FAIL, Item::PROFIT_ORDER_REDEEM_SUCCESS)))
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[提现中]状态', '订单禁止修改为[提现中]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

        $orderModel->status = Item::PROFIT_ORDER_EXTRACT_ING;
        $orderModel->cash_create_time = $cashCreateTime;
        $orderModel->update_time = time();

        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '订单更新为[提现中]失败', $orderModel->getErrors());

        return true;
    }

    /**
     * 更新订单为提现成功
     * @param $sn
     * @param $customerId
     * @param $cashResultTime
     * @return bool
     */
    public function orderWithdrawSuccess($sn, $customerId, $cashResultTime)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_EXTRACT_SUCCESS == $orderModel->status)
            return true;

        if (Item::PROFIT_ORDER_EXTRACT_ING != $orderModel->status)
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[提现成功]状态', '订单禁止修改为[提现成功]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

        $orderModel->status = Item::PROFIT_ORDER_EXTRACT_SUCCESS;
        $orderModel->cash_return_time = $cashResultTime;
        $orderModel->update_time = time();

        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '订单更新为[提现成功]失败', $orderModel->getErrors());

        return true;
    }

    /**
     * 更新订单为提现失败
     * @param $sn
     * @param $customerId
     * @param $cashResultTime
     * @return bool
     */
    public function orderWithdrawFail($sn, $customerId, $cashResultTime)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_EXTRACT_FAIL == $orderModel->status)
            return true;

        if (Item::PROFIT_ORDER_EXTRACT_ING != $orderModel->status)
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[提现失败]状态', '订单禁止修改为[提现失败]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

        $orderModel->status = Item::PROFIT_ORDER_EXTRACT_FAIL;
        $orderModel->cash_return_time = $cashResultTime;
        $orderModel->update_time = time();

        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '订单更新为[提现失败]失败', $orderModel->getErrors());

        return true;
    }

    /**
     * 更新订单为赎回中
     * @param $sn
     * @param $customerId
     * @param $cashCreateTime
     * @return bool
     */
    public function orderRedeemIng($sn, $customerId, $cashCreateTime)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_REDEEM_ING == $orderModel->status)
            return true;

        if ( !in_array($orderModel->status, array(Item::PROFIT_ORDER_SUCCESS, Item::PROFIT_ORDER_CONTINUOUS, Item::PROFIT_ORDER_REDEEM_FAIL)))
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[赎回中]状态', '订单禁止修改为[赎回中]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

        $orderModel->status = Item::PROFIT_ORDER_REDEEM_ING;
        $orderModel->cash_create_time = $cashCreateTime;
        $orderModel->update_time = time();

        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '订单更新为[赎回中]失败', $orderModel->getErrors());

        return true;
    }

    /**
     * 更新订单为赎回成功
     * @param $sn
     * @param $customerId
     * @param $cashResultTime
     * @return bool
     */
    public function orderRedeemSuccess($sn, $customerId, $cashResultTime)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_REDEEM_SUCCESS == $orderModel->status)
            return true;

        if ( !in_array($orderModel->status, array(Item::PROFIT_ORDER_REDEEM_ING, Item::PROFIT_ORDER_SUCCESS)))
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[赎回成功]状态', '订单禁止修改为[赎回成功]状态');

        if (Item::PROFIT_ORDER_SUCCESS == $orderModel->status && empty($orderModel->last_sn))
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[赎回成功]状态', '订单禁止修改为[赎回成功]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

        $orderModel->status = Item::PROFIT_ORDER_REDEEM_SUCCESS;
        $orderModel->cash_return_time = $cashResultTime;
        $orderModel->update_time = time();

        if ($orderModel->next_sn)
            $orderModel->surplus_money = $orderModel->amount + $orderModel->profit;

        if ($orderModel->last_sn) {
            $criteria = new CDbCriteria;
            $criteria->condition = "sn=:sn";
            $criteria->params = array(':sn'=>$orderModel->last_sn);
            $lastOrderModel = AppreciationPlan::model()->find($criteria);

            if ( !$lastOrderModel ) {
                return $this->sendErrorMessage(false, 0, '历史订单不存在', '历史订单不存在');
            }

            $orderModel->surplus_money = 0;
            if ($lastOrderModel->surplus_money < $orderModel->amount)
                $orderModel->surplus_money = $orderModel->amount - $lastOrderModel->surplus_money;
        }


        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '订单更新为[赎回成功]失败', $orderModel->getErrors());

        // 续投订单更新为已赎回待消单
        if ($orderModel->next_sn) {
            $this->orderRedeemSuccess($orderModel->next_sn, $customerId, $cashResultTime);
        }

        return true;
    }

    /**
     * 更新订单为赎回失败
     * @param $sn
     * @param $customerId
     * @param $cashResultTime
     * @return bool
     */
    public function orderRedeemFail($sn, $customerId, $cashResultTime)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_REDEEM_FAIL == $orderModel->status)
            return true;

        if (Item::PROFIT_ORDER_REDEEM_ING != $orderModel->status)
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[赎回失败]状态', '订单禁止修改为[赎回失败]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

        $orderModel->status = Item::PROFIT_ORDER_REDEEM_FAIL;
        $orderModel->cash_return_time = $cashResultTime;
        $orderModel->update_time = time();

        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '订单更新为[赎回失败]失败', $orderModel->getErrors());

        return true;
    }

    /**
     * 饭票收益结算
     * @param $sn
     * @param $source
     * @param $customerId
     * @param $profitAmount
     * @return bool
     */
    public function orderSettled($sn, $source='admin', $customerId='', $profitAmount='')
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        $profitSuccessStatus = 2;
        if ('admin' != $source) {
            $profitSuccessStatus = 1;
            if ($orderModel->customer_id != $customerId)
                return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

            if ( $orderModel->profit != $profitAmount)
                return $this->sendErrorMessage(false, 0, '订单收益金额与通知发放金额不匹配', '订单收益金额与通知发放金额不匹配');
        }

        if ( 1 !== (int)$orderModel->profit_type )
            return $this->sendErrorMessage(false, 0, '订单不是红包收益类型', '订单不是红包收益类型');

        if ( ! in_array($orderModel->status, array(Item::PROFIT_ORDER_SUCCESS, Item::PROFIT_ORDER_CONTINUOUS, Item::PROFIT_ORDER_EXTRACT_SUCCESS)))
            return $this->sendErrorMessage(false, 0, '订单禁止结算饭票', '订单禁止结算饭票');

        if ( ! in_array($orderModel->profit_status, array(0, 3)))
            return $this->sendErrorMessage(false, 0, '订单收益以成功结算', '订单收益以成功结算');



        //$transaction = Yii::app()->db->beginTransaction();
        try {
            #TODO 彩富提供账户
            $cmobile_id = 2118884;
            $cmobile = '10000000017';
            $customer_id = $orderModel->customer_id;
            $amount = $orderModel->profit;
            $note = '增值计划订单['.$orderModel->sn.']到期收益结算';

            $rebateResult = RedPacketCarry::model()->customerTransferAccounts($cmobile_id, $customer_id, $amount, 1, $cmobile, $note);

            if (1 ==$rebateResult['status']) {
                AppreciationPlan::model()->updateByPk($orderModel->id, array('profit_status'=>$profitSuccessStatus, 'update_time'=>time()));
            }
            else {
                //$transaction->rollback();
                $profitSuccessStatus = 3;
                AppreciationPlan::model()->updateByPk($orderModel->id, array('profit_status'=>$profitSuccessStatus, 'update_time'=>time()));
                return $this->sendErrorMessage(false, 0, '结算收益失败', '金融平台：'.$rebateResult['msg']);
            }
        }
        catch (Exception $e) {
            //$transaction->rollback();
            return $this->sendErrorMessage(false, 0, '结算收益失败', $e->getMessage());
        }

        return true;
    }
    
    /**
     * 获取合和年订单的资金流向数据
     * @param  $sn
     * @return mixed
     */
    public function getFundsDetail($sn){
    	$criteria = new CDbCriteria;
    	$criteria->condition = "sn=:sn";
    	$criteria->params = array(':sn'=>$sn);
    	$orderModel = AppreciationPlan::model()->find($criteria);
    	
    	if ( !$orderModel )
    		return $this->sendErrorMessage(false, 0, '订单号不存在', '订单号不存在');
    	//需要传的参数
    	$para = array(
    			'orderSN' => $sn
    	);
    	$queryUrl = $this->serverUrl.'activity/colorlife/loanAgreement';
    	$queryData = $this->makeQueryData($para);
    	$result =  Yii::app()->curl->get($queryUrl, $queryData);
    	if ( ! $result)
    		return $this->sendErrorMessage(false, 0, '请求合和年订单资金流向异常', '请求合和年订单资金流向异常');
    	
    	$result = json_decode($result, true);
    	
    	return $result;
    }

    /************************************
     * ** 签名方法 **
     ***********************************/

    /**
     * 生成请求参数
     * @param $para
     * @return mixed
     */
    private function makeQueryData($para)
    {
        $para['sign'] = $this->makeSign($para);

        return $para;
    }

    /**
     * 生成签名
     * @param array $para
     * @return string
     */
    private function makeSign(array $para)
    {
        $para = $this->paraFilter($para);
        $para = $this->argSort($para);

        $signStr = $this->createLinkString($para).'&key='.$this->partnerKey;

        return strtoupper(md5($signStr));
    }

    /**********功能方法***********/
    /**
     * 对数组排序
     * @param $para 排序前的数组
     * @return 排序后的数组
     */
    public function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param $para 需要拼接的数组
     * @return 拼接完成以后的字符串
     */
    public function createLinkString($para) {
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
    public function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign" || $key == "sign_type" || $val === "")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
}