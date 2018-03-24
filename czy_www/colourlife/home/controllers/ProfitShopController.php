<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/8/26
 * Time: 14:28
 */
class ProfitShopController extends ActivityController
{
    public $beginTime       = '2016-09-14 00:00:00';//活动开始时间
    public $endTime         = '2016-10-31 23:59:59';//活动结束时间
    public $secret          = 'pr^of#it&sh@op';
    public $activityName    = 'profitShop';

    protected $couponCode   = 100000104; // 至尊券ID

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            //'Validity',
            'signAuth - Share, ShareWeb, Introduce, PaySuccess, ReceiveTicket',
        );
    }

    public function accessRules()
    {
        return array(
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'   => array(),
                'users'     => array('@'),
            ),
        );
    }

    /**
     * 商城首页
     * @throws CException
     */
    public function actionIndex()
    {
        $fromType = Yii::app()->request->getParam('from_type');
        if ('colourlife' == $fromType)
            $this->redirect('/ProfitShop/ShareWeb', array('from_type' => 'colourlife'));


        $goods = ActivityGoods::model()->getProducts($this->activityName, false);
        //京东
        //$jdUrl = ActivityGoods::model()->getShopUrl($this->getUserId(),1);
        //彩特供
        //$ctgUrl = ActivityGoods::model()->getShopUrl($this->getUserId());

        // 商品信息
        $sql = 'SELECT COUNT(`id`) FROM `profit_shop_coupon` WHERE `status` = 0 AND `customer_id`=:customer_id';
        $command = Yii::app()->db->createCommand($sql);
        $customerId = $this->getUserId();
        $command->bindParam(':customer_id', $customerId, PDO::PARAM_INT);

        // 是否有未领取的至尊券
        $couponNum = $command->queryScalar();

        // 用户信息
        $customer = $this->getUserInfo();
        $mobile = $customer->mobile;

        // 是否是彩富用户
        Yii::import('common.services.ProfitService');
        $profitService = new ProfitService();
        $level = $profitService->getUserLevel($customerId);
        $isProfitUser = 0;
        if ($level > 0) {
            $isProfitUser = 1;
            $this->sendTicket($customer->mobile, 1);
        }

        if ( 0 === $isProfitUser) {
            $sql = 'SELECT `id` FROM `profit_shop_ticket` WHERE `mobile`=:mobile AND `status`=0';
            $command = Yii::app()->db->createCommand($sql);
            $command->bindParam(':mobile', $mobile, PDO::PARAM_STR);
            $result = $command->queryRow();
            if ($result)
                $isProfitUser = 1;
        }

        $outData  = array(
            'goods'         => $goods,
            'couponNum'     => $couponNum,
            'beginDate'     => date('Y.m.d', strtotime($this->beginTime)),
            'endDate'       => date('Y.m.d', strtotime($this->endTime)),
            'isProfitUser'  => $isProfitUser
        );

        //dump($outData);

        $this->renderPartial('/v2016/profitShop/index', $outData);
    }

    /**
     * 介绍页
     * @throws CException
     */
    public function actionIntroduce(){

        $this->renderPartial('/v2016/profitShop/rule');
    }

    /**
     *彩富国庆巨献
     */
    public function actionNational()
    {
        // 用户信息
        $userId = $this->getUserId();

        $shareUrl = '';
        // 是否是彩富用户
        Yii::import('common.services.ProfitService');
        $profitService = new ProfitService();
        $level = $profitService->getUserLevel($userId);

        $sign = new Sign($this->secret);
        $para = array(
            'user_id' => $userId * 778 + 1778,
            'request_time' => time()
        );
        $para['sign'] = $sign->makeSign($para);

        if ($level > 0) {
            $shareUrl = F::getHomeUrl('/ProfitShop/ShareWeb') . '?' . $sign->createLinkString($para);
        }

        $HomeConfigResource = new HomeConfigResource();

        //彩富商城
        //$profitShopUrl = F::getHomeUrl('/ProfitShop/Index') . '?' . $sign->createLinkString($para);
        //国庆7天乐
        $resource = $HomeConfigResource->getResourceByKeyOrId(690, 1, $userId);
        $nationalDayUrl = $resource->completeURL;
        unset($resource);
        //太平洋车险
        $resource = $HomeConfigResource->getResourceByKeyOrId(523, 1, $userId);
        $carInsureUrl = $resource->completeURL;
        unset($resource);
        //彩富大闸蟹
        $resource = $HomeConfigResource->getResourceByKeyOrId(677, 1, $userId);
        $daZhaiXieUrl = $resource->completeURL;
        unset($resource);
        //E旅游商城首页
        $resource = $HomeConfigResource->getResourceByKeyOrId(530, 1, $userId);
        $eTravelUrl = $resource->completeURL;
        unset($resource);
        //E住房活动
        $resource = $HomeConfigResource->getResourceByKeyOrId(20, 1, $userId);
        $eZuFangUrl = $resource->completeURL;
        unset($resource);
        //E维修活动
        $resource = $HomeConfigResource->getResourceByKeyOrId(19, 1, $userId);
        $eWeiXiuUrl = $resource->completeURL;
        unset($resource);
        //国庆拼低价
        $resource = $HomeConfigResource->getResourceByKeyOrId(555, 1, $userId);
        $pingTuanUrl = $resource->completeURL;
        unset($resource);

        $outData = array(
            'share_url'         => base64_encode($shareUrl),
            //'profit_shop_url' => $profitShopUrl,
            'national_day_url'  => $nationalDayUrl,
            'car_insure_url'    => $carInsureUrl,
            'daZhaiXie_url'     => $daZhaiXieUrl,
            'eTravel_url'       => $eTravelUrl,
            'eZuFang_url'       => $eZuFangUrl,
            'eWeiXiu_url'       => $eWeiXiuUrl,
            'pingTuan_url'      => $pingTuanUrl,
        );

        $this->renderPartial('/v2016/profitShop/national', $outData);
    }

    /**
     * 领取优惠券
     */
    public function actionReceive()
    {
        $updateData = array(
            'status'        => 1,
            'receive_time'  => time()
        );

        $customer = $this->getUserInfo();
        $customerId = $customer->id;
        $couponNum = 0;

        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();
        $command = $connection->createCommand();

        try {
            // 生成优惠券
            $couponNum = $command->update('profit_shop_coupon', $updateData, "customer_id=:customer_id AND status=0", array(':customer_id'=>$customerId));

            $columArray = array(
                'mobile' => $customer->mobile,
                'you_hui_quan_id' => $this->couponCode,
                'is_use' => 0,
                'num' => 0,
                'create_time' => time()
            );

            if ($couponNum > 0) {
                for ($i=0; $i<$couponNum; $i++) {
                    $command->reset();
                    $command->insert('user_coupons', $columArray);
                }
            }

            $transaction->commit();

        }
        catch (Exception $e) {
            $transaction->rollback();
            //return $this->sendErrorMessage(false, 0, '写入数据失败', $e->getMessage());
        }

        $result = array(
            'couponNum' => $couponNum,
            'beginDate' =>  substr($this->beginTime, 0, 10),
            'endDate'   =>  substr($this->endTime, 0, 10)
        );

        $this->output($result);
    }

    /**
     * 获取用户购买资格
     */
    public function actionCountTicket()
    {
        $customer = $this->getUserInfo();
        $mobile = $customer->mobile;
        $customerId = $customer->id;
        $outData = array(
            'shareNum' => 0,
            'ownNum' => 0,
            'overNum' => 0
        );

        $connection = Yii::app()->db;
        $userCouponSql = 'SELECT `status`, COUNT(`id`) AS `number` FROM `profit_shop_ticket` WHERE `mobile`=:mobile GROUP BY `status`';
        $command = $connection->createCommand($userCouponSql);
        $command->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $couponArr = $command->queryAll();

        if ($couponArr) {
            foreach ($couponArr as $row) {
                if (0 == $row['status'])
                    $outData['overNum'] = $row['number'];
                $outData['ownNum'] += $row['number'];
            }
        }

        $userCouponSql = 'SELECT COUNT(`id`) FROM `profit_shop_ticket` WHERE `share_user_id`=:share_user_id';
        $command = $connection->createCommand($userCouponSql);
        $command->bindParam(':share_user_id', $customerId, PDO::PARAM_INT);
        $outData['shareNum'] = $command->queryScalar();

        $this->output($outData);
    }

    /**
     * 判断用户是否有购买资格
     */
    public function actionCanBuy()
    {
        $customer = $this->getUserInfo();
        $mobile = $customer->mobile;
        $customerId = $customer->id;

        //彩特供
        $ctgUrl = ActivityGoods::model()->getShopUrl($this->getUserId());


        // 是否是彩富用户
        Yii::import('common.services.ProfitService');
        $profitService = new ProfitService();
        $level = $profitService->getUserLevel($customerId);

        $result = array(
            'canBuy' => 3,
            'url' => ''
        );

        $connection = Yii::app()->db;
        $userCouponSql = 'SELECT `status`, COUNT(`id`) AS `number` FROM `profit_shop_ticket` WHERE `mobile`=:mobile GROUP BY `status`';
        $command = $connection->createCommand($userCouponSql);
        $command->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $ticketList = $command->queryColumn();
        if ($ticketList) {
            if ($level > 0)
                $result['canBuy'] = 2;

            if (in_array(0, $ticketList)) {
                $result['canBuy'] = 1;
                $result['url'] = $ctgUrl;

                $this->output($result);
            }
        }

        $userCouponSql = 'SELECT `is_use`, COUNT(`id`) FROM `user_coupons` WHERE `mobile`=:mobile AND `you_hui_quan_id`=:you_hui_quan_id GROUP BY `is_use`';
        $command = $connection->createCommand($userCouponSql);
        $command->bindParam(':mobile', $mobile, PDO::PARAM_STR);
        $command->bindParam(':you_hui_quan_id', $this->couponCode, PDO::PARAM_INT);
        $couponList = $command->queryColumn();

        if ($couponList) {
            if ($level > 0)
                $result['canBuy'] = 2;
            if (in_array(0, $couponList)) {
                $result['canBuy'] = 1;
                $result['url'] = $ctgUrl;

                $this->output($result);
            }
        }

        $this->output($result);
    }

    /**
     * 彩富下单成功回调展示页
     */
    public function actionPaySuccess()
    {
        $sn = Yii::app()->request->getParam('sn');
        $orderType = Yii::app()->request->getParam('order_type');
        if (empty($sn) || !in_array($orderType, array('profit', 'appreciationPlan')))
            exit('参数错误');

        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);

        if ('profit' == $orderType)
            $orderModel = PropertyActivity::model()->find($criteria);
        else
            $orderModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel )
            exit('订单不存在');

        if (Item::PROFIT_ORDER_SUCCESS != $orderModel->status)
            exit('订单未支付');

//        if ($orderType == 'profit') {
//            $month = $orderModel->PropertyActivityRate->month;
//        }
//        else {
//            Yii::import('common.services.AppreciationPlanService');
//            $service = new AppreciationPlanService();
//            $month = $service->getAppreciationPlanMonth($orderModel->rate_id);
//        }

        //
        //$customer = Customer::model()->findByPk($orderModel->customer_id);
        //$this->sendTicket($customer->mobile, 4, null, $sn);

        // 改为异步送
        //$this->sendCoupon($sn, $orderModel->customer_id, $orderModel->amount, $orderType, $month);

        $userId = $orderModel->customer_id;
        $para = array(
            'user_id' => $userId * 778 + 1778,
            'request_time' => time()
        );
        $sign = new Sign($this->secret);
        $para['sign'] = $sign->makeSign($para);
        $url = F::getHomeUrl('/ProfitShop/ShareWeb') . '?' . $sign->createLinkString($para);
        $goods = ActivityGoods::model()->getProducts($this->activityName, false, true);

        $outData = array(
            'url' => base64_encode($url),
            'goods' => $goods,
            'shop_url' => F::getHomeUrl('/ProfitShop/Index') . '?' . $sign->createLinkString($para)
        );

        //dump($outData);

        $this->renderPartial('/v2016/profitShop/paySuccess', $outData);
    }

    /**
     * 分享页面
     * @throws CException
     */
    public function actionShareWeb()
    {
        $param = $_GET;
        $fromType = isset($param['from_type']) ? $param['from_type'] : '';
        if (isset($param['from_type']))
            unset($param['from_type']);

        if (empty($param) || !isset($param['sign']) || !isset($param['request_time']))
            $this->redirect('http://mapp.colourlife.com/m.html');

        $sign = new Sign($this->secret);
        if (false === $sign->checkSign($param))
            $this->redirect('http://mapp.colourlife.com/m.html');

        $customer = $this->getUserInfo($param['user_id']);
        $username = $customer->nickname ? $customer->nickname : $customer->name;

        $goods = ActivityGoods::model()->getProducts($this->activityName, false, true);

        $outData = array(
            'username'  => $username,
            'user_id'   => $customer->id,
            'goods'     => $goods,
            'from_type' => $fromType
        );

        $this->renderPartial('/v2016/profitShop/share', $outData);
    }

    /**
     * 分享页面领券
     */
    public function actionReceiveTicket()
    {
        $userId = (int)Yii::app()->request->getParam('user_id');
        $mobile = Yii::app()->request->getParam('mobile');

        if ( !preg_match('/^1\d{10}$/', $mobile))
            $this->output('', 0, '手机号码格式错误');

        $customer = Customer::model()->find("id=:id and state = 0", array('id' => intval($userId)));
        if ( !$customer)
            $this->output('', 0, '分享用户不存在');

        if ( false == $this->sendTicket($mobile, 2, $userId))
            $this->output('', 0, '你已经领取过，赶紧去抢购最新的优惠商品吧！！');

        $this->sendTicket($customer->mobile, 3);

        $this->output(array('ret' => 1));
    }

    /**
     * 至尊券
     * @param $sn
     * @param $customerId
     * @param $amount
     * @param $type
     * @param int $month
     * @return bool|void
     */
    private function sendCoupon($sn, $customerId, $amount, $type, $month=0)
    {
        if (strtotime($this->beginTime) > time() || strtotime($this->endTime) < time())
            return false;

        if ( $month < 12)
            return false;

        if ('appreciationPlan' == $type && $amount < 50000)
            return false;

        $sql = 'SELECT `id` FROM `profit_shop_coupon` WHERE `relation_id`=:relation_id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':relation_id', $sn, PDO::PARAM_STR);

        $result = $command->queryRow();

        if ($result)
            return false;

        $data = array(
            'customer_id' => $customerId,
            'relation_id' => $sn,
            'status'      => 0,
            'create_time' => time()
        );

        try {
            Yii::app()->db->createCommand()->insert('profit_shop_coupon', $data);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }

    /**
     * 彩富价购买资格
     * @param $mobile
     * @param $source
     * @param $userId
     * @param $sn
     * @return bool
     */
    private function sendTicket($mobile, $source, $userId=null, $sn=null)
    {
        //$customer = $this->getUserInfo();

        $data = array(
            'mobile' => $mobile,
            'source' => $source,
            'status' => 0,
            'create_time' => time()
        );

        $ret = true;

        switch ($source) {
            case 1: // 彩富用户默认每周有一次彩富的彩富价购买机会
                $beginTime = strtotime('next monday') - 7*86400;
                $sql = 'SELECT `id` FROM `profit_shop_ticket` WHERE `mobile`=:mobile AND `create_time` > :beginTime AND `source`=1';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(':mobile', $mobile, PDO::PARAM_STR);
                $command->bindParam(':beginTime', $beginTime, PDO::PARAM_INT);
                $result = $command->queryRow();
                if ($result)
                    $ret = false;
                break;
            case 2: // 通过分享领取 只能领取一次
                $sql = 'SELECT `id` FROM `profit_shop_ticket` WHERE `mobile`=:mobile AND `source`=2';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(':mobile', $mobile, PDO::PARAM_STR);
                $result = $command->queryRow();
                if ($result)
                    $ret = false;

                $data['share_user_id'] = $userId;

                break;
            case 3: // 分享者最高每天可获得5次
                $beginTime = strtotime(date('Y-m-d'));
                $sql = 'SELECT COUNT(`id`) FROM `profit_shop_ticket` WHERE `mobile`=:mobile AND `create_time` > :beginTime AND `source`=3';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(':mobile', $mobile, PDO::PARAM_STR);
                $command->bindParam(':beginTime', $beginTime, PDO::PARAM_INT);
                $result = $command->queryScalar();
                if ($result >= 5)
                    $ret = false;
                break;
            case 4: // 购买彩富送一次购买机会
                $sql = 'SELECT `id` FROM `profit_shop_ticket` WHERE `sn`=:sn';
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(':sn', $sn, PDO::PARAM_STR);
                $result = $command->queryRow();
                if ($result)
                    $ret = false;
                break;
            default :
                $ret = false;
        }

        if (true === $ret) {
            try {
                Yii::app()->db->createCommand()->insert('profit_shop_ticket', $data);
                return true;
            }
            catch (Exception $e) {
                return false;
            }
        }

        return false;
    }
}