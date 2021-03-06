 <?php
/**
 * 收益服务类
 * User: PHP
 * Date: 2015/10/27
 * Time: 11:24
 */
Yii::import('common.services.BaseService');
class ProfitService extends BaseService {


    const ORDER_STATUS_INIT = 0; //订单初始状态
    const ORDER_STATUS_SUCCESS = 1; // 已收到钱，下单成功
    const ORDER_STATUS_EXTRACT_ING = 2; //已申请提现，未到账
    const ORDER_STATUS_AUTHORIZE = 3; // 已授权
    const ORDER_STATUS_EXTRACT_SUCCESS = 4; // 已提现到账


    //private $serverUrl = 'http://test.hhnian.com:9051/';//测试
    private $serverUrl = 'http://www.hehenian.com/';//正式

    private $partnerKey = 'DJKC#$%CD%des$';

    // 增值计划单份金额
    private $increaseStep = 5000.00;

    // 增值计划业主年化收益率
    private $increaseRates = array(
        6 => 0.080,
        12 => 0.100,
        24 => 0.120
    );

    // 银行定期存款收益率
    private $bankRates = array(
        6 => 0.013,
        12 => 0.015,
        24 => 0.021
    );

    public function __construct()
    {
    	if (defined('YII_DEBUG') && YII_DEBUG == true) {
    		$this->serverUrl = 'http://test.hhnian.com:9051/';//测试
    	} else {
    		$this->serverUrl = 'http://www.hehenian.com/';//正式
    	}
    }

    /**
     * 冲抵费缴费地址列表
     * @param $customerId
     * @param int $modelName
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getAddressList($customerId, $modelName, $page=0, $pageSize=10)
    {
        $modelNameList = array('PropertyActivity', 'PropertyFees', 'ParkingFees');
        if ( !in_array($modelName, $modelNameList))
            return $this->sendErrorMessage(false, 0, '冲抵类型参数错误', '冲抵类型参数错误');
        $result = array();
        if ( 'PropertyActivity' == $modelName || 'PropertyFees' == $modelName) {
            $criteria = new CDbCriteria;
            $criteria->addCondition("is_activity = 1");
            $criteria->addCondition("customer_id = {$customerId}");
            $criteria->order = " last_time DESC ";
            $dp = new CActiveDataProvider(PropertyAddress::model(), array(
                'criteria' => $criteria,
                'pagination' => array(
                    'currentPage' => $page,
                    'pageSize' => $pageSize,
                    'validateCurrentPage' => false,
                ),
            ));

            $list = $dp->getData();

            if ($list) {
                foreach ($list as $row) {
                    $result[]=array(
                        'id'=>$row->id,
                        'customerId'=>$row->customer_id,
                        'roomId'=>$row->room_id,
                        'buildId'=>$row->build_id,
                        'communityId'=>$row->community_id,
                        'colorcloudId'=>$row->colorcloud_id,
                        'room'=>$row->room,
                        'build'=>$row->build,
                        'customer'=>$row->customer,
                        'communityName'=>$row->communityName,
                        'regions'=>$row->regions,
                    );
                }
            }
        }
        elseif ( 'ParkingFees' == $modelName) {
            $criteria = new CDbCriteria;
            $criteria->addCondition("is_activity = 0");
            $criteria->addCondition("customer_id = {$customerId}");
            $criteria->order = " last_time DESC ";
            $page = intval($page) - 1;
            if ($page < 0) {
                $page = 0;
            }

            //分页
            $dp = new CActiveDataProvider(ParkingAddress::model(), array(
                'criteria' => $criteria,
                'pagination' => array(
                    'currentPage' => $page,
                    'pageSize' => $pageSize,
                    'validateCurrentPage' => false,
                ),
            ));

            $list = $dp->getData();

            if ($list) {
                foreach ($list as $row) {
                    $result[]=array(
                        'id'=>$row->id,
                        'customerId'=>$row->customer_id,
                        'carNumber'=>$row->car_number,
                        'buildId'=>$row->build_id,
                        'communityId'=>$row->community_id,
                        'room'=>$row->room,
                        'buildName'=>$row->buildName,
                        'communityName'=>$row->communityName,
                        'regions'=>$row->regions,
                        'isActivity'=>$row->is_activity
                    );
                }
            }

        }

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
        $orderModel = PropertyActivity::model()->find($criteria);
        if ( !$orderModel)
            return $this->sendErrorMessage(false, 0, '续投订单不存在', '续投订单不存在');

        $result = array();
        if ('PropertyActivity' == $orderModel->model) {
            //兼容银湾物业
            if($orderModel->AdvanceFees->community_id == 0 && !empty($orderModel->AdvanceFees->room)){
                $address_info = json_decode($orderModel->AdvanceFees->room , true);
                $result['provice_code'] = $address_info['provice_code'];
                $result['provice_name'] = $address_info['provice_name'];
                $result['city_code'] = $address_info['city_code'];
                $result['city_name'] =$address_info['city_name'];
                $result['area_code'] = $address_info['area_code'];
                $result['area_name'] = $address_info['area_name'];
                $result['communityId'] = $address_info['community_code'];
                $result['communityName'] = $address_info['community_name'];
                $result['buildId'] = $address_info['build_code'];
                $result['build'] = $address_info['build_name'];
                $result['roomId'] =$address_info['room_code'];
                $result['room'] = $address_info['room_name'];
                $result['address'] = $address_info['provice_name'].$address_info['city_name'].$address_info['area_name'].$address_info['community_name'];
            }else{
                $communityModel = Community::model()->enabled()->findByPk($orderModel->AdvanceFees->community_id);
                if ($communityModel) {
                    $result['communityId'] = $communityModel->id;
                    $result['communityName'] = $communityModel->name;
                    $result['buildId'] = $orderModel->AdvanceFees->colorcloud_building;
                    $result['build'] = $orderModel->AdvanceFees->build;
                    $result['roomId'] =empty($orderModel->AdvanceFees->new_colorcloud_unit)? $orderModel->AdvanceFees->colorcloud_unit:$orderModel->AdvanceFees->new_colorcloud_unit;
                    $result['room'] = $orderModel->AdvanceFees->room;
                    $result['address'] = $communityModel->getCommunityAddress();
                }
            }
        }
        elseif ('ParkingFees' == $orderModel->model) {
            $communityModel = Community::model()->enabled()->findByPk($orderModel->ParkingFees->community_id);
            if ($communityModel) {
                $result['communityId'] = $communityModel->id;
                $result['communityName'] = $communityModel->name;
                $result['buildId'] = $orderModel->ParkingFees->build_id;
                $result['room'] = $orderModel->ParkingFees->room;
                $result['carNumber'] = $orderModel->ParkingFees->car_number;
                $result['address'] = $communityModel->getCommunityAddress().$orderModel->ParkingFees->build->name.$orderModel->ParkingFees->room;;
            }
        }
        elseif ('ParkingFeesMonth' == $orderModel->model) {
            $communityModel = Community::model()->enabled()->findByPk($orderModel->ParkingFeesMonth->community_id);
            if ($communityModel) {
                $result['communityId'] = $communityModel->id;
                $result['communityName'] = $communityModel->name;
                $result['buildId'] = $orderModel->ParkingFeesMonth->build_id;
                $result['room'] = $orderModel->ParkingFeesMonth->room;
                $result['carNumber'] = $orderModel->ParkingFeesMonth->car_number;
                $result['address'] = $communityModel->getCommunityAddress().$orderModel->ParkingFeesMonth->build->name.$orderModel->ParkingFeesMonth->room;;
            }
        }
        elseif ('PropertyFees' == $orderModel->model) {
            $communityModel = Community::model()->enabled()->findByPk($orderModel->PropertyFees->community_id);
            if ($communityModel) {
                $result['communityId'] = $communityModel->id;
                $result['communityName'] = $communityModel->name;
                $result['buildId'] = $orderModel->PropertyFees->colorcloud_building;
                $result['build'] = $orderModel->PropertyFees->build;
                $result['roomId'] = $orderModel->PropertyFees->colorcloud_unit;
                $result['room'] = $orderModel->PropertyFees->room;
                $result['address'] = $communityModel->getCommunityAddress();

                $colorCloudBuilding = $communityModel->colorcloudCommunity;
                $result['colorCloudId'] = $colorCloudBuilding[0]->colorcloud_name;
            }
        }

        return $result;
    }

    /**
     * 根据用户ID查询订单
     * @param $customerId
     * @param $modelName
     * @return mixed
     */
    public function verifyUserType($customerId, $modelName)
    {
        $modelNameList = array('PropertyActivity', 'PropertyFees', 'ParkingFees');

        if ( !in_array($modelName, $modelNameList))
            return $this->sendErrorMessage(false, 0, '冲抵类型参数错误', '冲抵类型参数错误');

        $modelNames = array($modelName);
        if ('ParkingFees' == $modelName)
            $modelNames = array('ParkingFees', 'ParkingFeesMonth');

        $criteria = new CDbCriteria;
        $criteria->addCondition('customer_id='.$customerId);
        $criteria->addCondition('status='.Item::PROFIT_ORDER_AUTHORIZE);
        $criteria->addInCondition('model', $modelNames);
        $model = PropertyActivity::model()->find($criteria);
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
                Item::PROFIT_ORDER_REDEEM_FAIL,
                Item::PROFIT_ORDER_REDEEM_DONE
            );

            $criteria = new CDbCriteria;
            $criteria->addCondition('customer_id='.$customerId);
            $criteria->addInCondition('model', $modelNames);
            $criteria->addInCondition('status', $inStatus);

            $model = PropertyActivity::model()->find($criteria);

            if ($model) {
                $result['verify'] = 1;
            }
        }

        return $result;
    }

    /*
     * 计算用户等级
     */
    public function getUserLevel($customerId)
    {
        $levels = array(
            5000,
            20000,
            50000,
            150000,
            500000
        );

        $countStatus = array(
            Item::PROFIT_ORDER_SUCCESS,
            Item::PROFIT_ORDER_CONTINUOUS,
            Item::PROFIT_ORDER_EXTRACT_ING,
            Item::PROFIT_ORDER_EXTRACT_SUCCESS,
            Item::PROFIT_ORDER_EXTRACT_FAIL
        );

        // 彩富
        $profit = Yii::app()->db->createCommand()
                ->select(array('sum(amount) as total_amount'))
                ->from('property_activity')
                ->where(array('in', 'status', $countStatus))
                ->andWhere('customer_id=:customer_id', array(':customer_id'=>$customerId))
                ->queryRow();

        // 彩富
        $appreciation = Yii::app()->db->createCommand()
            ->select(array('sum(amount) as total_amount'))
            ->from('appreciation_plan')
            ->where(array('in', 'status', $countStatus))
            ->andWhere('customer_id=:customer_id', array(':customer_id'=>$customerId))
            ->queryRow();

        $total_amount = $profit['total_amount'] + $appreciation['total_amount'];

        $levels = array_merge($levels, array($total_amount));
        sort($levels);

        return array_search($total_amount, $levels);
    }

    /**
     * 校验地址是否可投注
     * @param $modelName
     * @param $communityId
     * @param $paramValue
     * @return array
     *
     */
    public function verifyAddressSuccess($modelName, $communityId, $paramValue)
    {
        $modelNameList = array('PropertyActivity', 'PropertyFees', 'ParkingFees');

        if ( !in_array($modelName, $modelNameList))
            return $this->sendErrorMessage(false, 0, '冲抵类型参数错误', '冲抵类型参数错误');

        if ( empty($communityId) && $communityId !=0)
            return $this->sendErrorMessage(false, 0, '小区参数为空', '小区参数为空');

        if ( empty($paramValue))
            return $this->sendErrorMessage(false, 0, '地址参数为空', '地址参数为空');

        $where = array('in', 'p.status', array(Item::PROFIT_ORDER_AUTHORIZE, Item::PROFIT_ORDER_SUCCESS, Item::PROFIT_ORDER_CONTINUOUS, Item::PROFIT_ORDER_EXTRACT_ING, Item::PROFIT_ORDER_EXTRACT_FAIL));

        $command = Yii::app()->db->createCommand();
        $command->from('property_activity p');


        $result = array('status' => 0);

        if ('ParkingFees' == $modelName) {
            $command->join('parking_fees pf', 'p.object_id = pf.id');
            $command->where('p.model=:model', array(':model'=>'ParkingFees'));
            $command->andWhere('pf.car_number=:car_number', array(':car_number'=>$paramValue));
            $command->andWhere('pf.community_id=:community_id', array(':community_id'=>$communityId));
            $command->andWhere($where);

            $data = $command->queryRow();
            if ($data)
                return $result;

            unset($command);

            $command = Yii::app()->db->createCommand();
            $command->from('property_activity p');
            $command->join('parking_fees_month pf', 'p.object_id = pf.id');
            $command->where('p.model=:model', array(':model'=>'ParkingFeesMonth'));
            $command->andWhere('pf.car_number=:car_number', array(':car_number'=>$paramValue));
            $command->andWhere('pf.community_id=:community_id', array(':community_id'=>$communityId));
            $command->andWhere($where);

            $data = $command->queryRow();
            if ($data)
                return $result;


            $result['status'] = 1;
        }
        else {
            if ('PropertyActivity' == $modelName) {
                $command->join('advance_fee af', 'p.object_id = af.id');
                $command->where('p.model=:model', array(':model'=>'PropertyActivity'));
                $command->andWhere('af.colorcloud_unit=:room_id', array(':room_id'=>$paramValue));
                $command->andWhere('af.community_id=:community_id', array(':community_id'=>$communityId));
            }
            elseif ('PropertyFees' == $modelName) {
                $command->join('property_fees pf', 'p.object_id = pf.id');
                $command->where('p.model=:model', array(':model'=>'PropertyFees'));
                $command->andWhere('pf.colorcloud_unit=:room_id', array(':room_id'=>$paramValue));
                $command->andWhere('pf.community_id=:community_id', array(':community_id'=>$communityId));
            }

            $command->andWhere($where);

            $data = $command->queryRow();
            if ($data)
                return $result;


            $result['status'] = 1;
        }

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
        $orderModel = PropertyActivity::model()->find($criteria);

        if ( !$orderModel)
            return $this->sendErrorMessage(false, 0, '订单号错误', '订单号错误');

        if ( !in_array($orderModel->status, array(Item::PROFIT_ORDER_INIT, Item::PROFIT_ORDER_AUTHORIZE)) )
            return $this->sendErrorMessage(false, 0, '订单状态不合法', '订单状态不合法');

        $carNumber = '';
        $modelName = $orderModel->model;
        $deductAmount = $orderModel->reduction * $orderModel->PropertyActivityRate->month;
        if ( 'PropertyActivity' == $orderModel->model) {

            $billingAddress = $orderModel->ActivityAddress;
            $communityId = $orderModel->AdvanceFees->community_id;

            //$cId = $orderModel->AdvanceFees->community->id;
            //$cName = $orderModel->AdvanceFees->community->name;

            $deductPerMonthText = '每月冲抵物业费';
            $billingDateText = '冲抵周期';
            $billingAddressText = '冲抵物业费地址：';

        }
        elseif ( 'PropertyFees' == $orderModel->model) {
            $communityId = $orderModel->PropertyFees->community_id;
            //$cId = $orderModel->PropertyFees->community->id;
            //$cName = $orderModel->PropertyFees->community->name;
            $billingAddress = $orderModel->PropertyAddress;

            $deductPerMonthText = '冲抵费用';
            $billingDateText = '冲抵时长';
            $billingAddressText = '冲抵地址：';

            $deductAmount = $orderModel->reduction;
        }
        elseif ( 'ParkingFees' == $orderModel->model) {
            $communityId = $orderModel->ParkingFees->community_id;
            //$cId = $orderModel->PropertyFees->community->id;
            //$cName = $orderModel->ParkingFees->community->name;
            $billingAddress = $orderModel->ParkingActivityAddress;

            $deductPerMonthText = '冲抵费用';
            $billingDateText = '冲抵时长';
            $billingAddressText = '冲抵地址：';

            $carNumber = $orderModel->ParkingFees->car_number;
        }
        elseif ( 'ParkingFeesMonth' == $orderModel->model ) {
            $modelName = 'ParkingFees';

            $communityId = $orderModel->ParkingFeesMonth->community_id;
            //$cId = $orderModel->ParkingFeesMonth->community->id;
            //$cName = $orderModel->ParkingFeesMonth->community->name;
            $billingAddress = $orderModel->ParkingMonthActivityAddress;

            $deductPerMonthText = '冲抵费用';
            $billingDateText = '冲抵时长';
            $billingAddressText = '冲抵地址：';

            $carNumber = $orderModel->ParkingFeesMonth->car_number;
        }
        else {
            return $this->sendErrorMessage(false, 0, '非法订单', '非法订单');
        }

        $cId = '';
        $cName = '';
        $communityModel = ICECommunity::model()->enabled()->findByPk($communityId);
        if (!empty($communityModel)) {
            $cId = $communityModel->id;
            $cName = $communityModel->name;
        }

        //兼容银湾物业
        if('PropertyActivity' == $orderModel->model && $communityId == 0 && !empty($orderModel->AdvanceFees->room)){
            $address_info = json_decode($orderModel->AdvanceFees->room , true);
            $cId = $address_info['community_code'];
            $cName = $address_info['community_name'];
            $billingAddress = $address_info['provice_name'].$address_info['city_name'].$address_info['area_name'].$address_info['community_name'].$address_info['build_name'].$address_info['room_name'];
        }

        $para = array(
            'username' => $orderModel->customer->name ? $orderModel->customer->name : $orderModel->customer->username,
            'userId' => $orderModel->customer_id,
            'mobile' => $orderModel->customer->mobile,
            'cId' => $cId,
            'cName' => $cName,
            'billingAddress' => $billingAddress,
            'carNumber' => $carNumber,
            'via' => 'colorlifewyf',
            'orderModel' => $modelName,
            'rateType' => $orderModel->rate_id,
            'ordNo' => $orderModel->sn,
            'beginDate' => date('Ymd', $orderModel->mitigate_starttime),
            'endDate' => date('Ymd', $orderModel->mitigate_endtime),
            'ordDate' => date('YmdHis', $orderModel->create_time),
            'investAmount' => $orderModel->amount,
            'deductAmount' => $deductAmount,
            'profitType' => $orderModel->profit_type,
            'deductPerMonth' => $orderModel->reduction,
            'profit' => $orderModel->earnings,
            'userRate' => $orderModel->user_rate,
            'colourlifeRate' => $orderModel->colourlife_rate,

            'orderType' => 0,
            'linkOrdNo' => '',
            'linkAmount' => 0,
            'linkOrdBalance' => 0,

            'increaseAmount' => $orderModel->increase_amount,
            'increaseRate' => $orderModel->increase_rate,
            'increaseProfit' => $orderModel->increase_profit,
            'deductPerMonthText' => $deductPerMonthText,
            'billingDateText' => $billingDateText,
            'billingAddressText' => $billingAddressText,
        	'accountType' => $accountType
        );

        //$para['payAmount'] = $orderModel->amount;

        if ($orderModel->last_sn) {
            $criteria = new CDbCriteria;
            $criteria->condition = "sn=:sn";
            $criteria->params = array(':sn'=>$orderModel->last_sn);
            $lastOrderModel = PropertyActivity::model()->find($criteria);

            $para['orderType'] = 1;
            $para['linkOrdNo'] = $orderModel->last_sn;
            $para['linkAmount'] = $lastOrderModel->amount + $lastOrderModel->earnings + $lastOrderModel->increase_amount + $lastOrderModel->increase_profit;
        }

        $payAmount = $para['investAmount'] + $para['increaseAmount'] - $para['linkAmount'];
        $para['linkOrdBalance'] = $payAmount > 0 ? 0 : abs($payAmount);
        $para['payAmount'] = $payAmount > 0 ? $payAmount : 0;

        $queryUrl = $this->serverUrl.'wyf/app-index.do';
        if ('pc' === $type)
            $queryUrl = $this->serverUrl.'wyf/web-index.do';

        $queryData = $this->makeQueryData($para);

        return array(
            'title' => '彩富人生',
            'sn' => $sn,
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
        $orderModel = PropertyActivity::model()->find($criteria);

        if ( !$orderModel)
            return $this->sendErrorMessage(false, 0, '订单号错误', '订单号错误');

        if ( !in_array($orderModel->status, array(Item::PROFIT_ORDER_SUCCESS, Item::PROFIT_ORDER_CONTINUOUS, Item::PROFIT_ORDER_REDEEM_DONE, Item::PROFIT_ORDER_EXTRACT_FAIL)) )
            return $this->sendErrorMessage(false, 0, '订单不可提现', '订单不可提现');

        // 判断是否到提现时间
        //$isCanWithdraw = $this->verifyWithdrawTime($orderModel->pay_time, $orderModel->mitigate_starttime, $orderModel->mitigate_endtime, $orderModel->last_sn);
        //if (false == $isCanWithdraw)
        //    return $this->sendErrorMessage(false, 0, '订单未到期，不可提现', '订单未到期，不可提现');

        $customerModel = Customer::model()->findByPk($orderModel->customer_id);
        if ( !$customerModel)
            return $this->sendErrorMessage(false, 0, '当单用户不存在', '当单用户不存在');

        // 请求是否可提现
        $result = $this->getOrderContinueState($sn, $orderModel->customer_id);
        if (false === $result)
            return false;

        //if ('001' == $result['statusCode'])
        //    return $this->sendErrorMessage(false, 0, $result['statusDesc'], $result['statusDesc']);

        if ('000' != $result['statusCode'] || '2' != $result['ordStatus'])
            return $this->sendErrorMessage(false, 0, '合和年返回订单不可提现', '合和年返回订单不可提现');

        $carNumber = '';
        $modelName = $orderModel->model;
        $deductAmount = $orderModel->reduction * $orderModel->PropertyActivityRate->month;
        if ( 'PropertyActivity' == $orderModel->model) {

            $billingAddress = $orderModel->ActivityAddress;
            $cId = $orderModel->AdvanceFees->community->id;
            $cName = $orderModel->AdvanceFees->community->name;

            $deductPerMonthText = '每月冲抵物业费';
            $billingDateText = '冲抵周期';
            $billingAddressText = '冲抵物业费地址：';

        }
        elseif ( 'PropertyFees' == $orderModel->model) {

            $cId = $orderModel->PropertyFees->community->id;
            $cName = $orderModel->PropertyFees->community->name;
            $billingAddress = $orderModel->PropertyAddress;

            $deductPerMonthText = '冲抵费用';
            $billingDateText = '冲抵时长';
            $billingAddressText = '冲抵地址：';

            $deductAmount = $orderModel->reduction;
        }
        elseif ( 'ParkingFees' == $orderModel->model) {

            $cId = $orderModel->ParkingFees->community->id;
            $cName = $orderModel->ParkingFees->community->name;
            $billingAddress = $orderModel->ParkingActivityAddress;

            $deductPerMonthText = '冲抵费用';
            $billingDateText = '冲抵时长';
            $billingAddressText = '冲抵地址：';

            $carNumber = $orderModel->ParkingFees->car_number;
        }
        elseif ( 'ParkingFeesMonth' == $orderModel->model ) {
            $modelName = 'ParkingFees';

            $cId = $orderModel->ParkingFeesMonth->community->id;
            $cName = $orderModel->ParkingFeesMonth->community->name;
            $billingAddress = $orderModel->ParkingMonthActivityAddress;

            $deductPerMonthText = '冲抵费用';
            $billingDateText = '冲抵时长';
            $billingAddressText = '冲抵地址：';

            $carNumber = $orderModel->ParkingFeesMonth->car_number;
        }
        else {
            return $this->sendErrorMessage(false, 0, '非法订单', '非法订单');
        }

        $para = array(
            'username' => $orderModel->customer->name ? $orderModel->customer->name : $orderModel->customer->username,
            'userId' => $orderModel->customer_id,
            'mobile' => $orderModel->customer->mobile,
            'cId' => $cId,
            'cName' => $cName,
            'billingAddress' => $billingAddress,
            'carNumber' => $carNumber,
            'via' => 'colorlifewyf',
            'orderModel' => $modelName,
            'rateType' => $orderModel->rate_id,
            'ordNo' => $orderModel->sn,
            'beginDate' => date('Ymd', $orderModel->mitigate_starttime),
            'endDate' => date('Ymd', $orderModel->mitigate_endtime),
            'ordDate' => date('YmdHis', $orderModel->create_time),
            'investAmount' => $orderModel->amount,
            'deductAmount' => $deductAmount,
            'profitType' => (int)$orderModel->profit_type,
            'deductPerMonth' => $orderModel->reduction,
            'profit' => $orderModel->earnings,
            'userRate' => $orderModel->user_rate,
            'colourlifeRate' => $orderModel->colourlife_rate,

//            'orderType' => 0,
//            'linkOrdNo' => '',
//            'linkAmount' => 0,
//            'linkOrdBalance' => 0,

            'increaseAmount' => $orderModel->increase_amount,
            'increaseRate' => $orderModel->increase_rate,
            'increaseProfit' => $orderModel->increase_profit,
            'deductPerMonthText' => $deductPerMonthText,
            'billingDateText' => $billingDateText,
            'billingAddressText' => $billingAddressText
        );

        $queryUrl = $this->serverUrl.'wyf/app-index.do';
        if ('pc' === $type)
            $queryUrl = $this->serverUrl.'wyf/web-index.do';

        $queryData = $this->makeQueryData($para);

        return array(
            'title' => '彩富人生',
            'sn' => $sn,
            'surplusMoney' => $result['amount'],//intval($orderModel->surplus_money) ? $orderModel->surplus_money : ($orderModel->amount + $orderModel->earnings + $orderModel->increase_amount + $orderModel->increase_profit),
            'url'=> Yii::app()->curl->buildUrl($queryUrl, $queryData)
        );
    }

    /**
     * 计算冲抵周期
     * @param $beginDate 冲抵开始时间
     * @param $months    冲抵周期
     * @return array
     */
    public function makeProfitDate($beginDate, $months)
    {
        $firstDay = date("Y-m-01", $beginDate);

        $lastDay = date("Y-m-d",strtotime("{$firstDay} +{$months} month -1 day"));

        return array($firstDay,$lastDay);
    }

    /**
     * 投资收益计算
     * @param $payFee  投资总金额
     * @param $communityRate  小区浮动率
     * @param $ratio   冲抵系数
     * @param $rate    业主收益系数
     * @param $months  投资周期
     * @return array
     */
    public function makeProfitData($payFee, $communityRate, $ratio, $rate, $months)
    {
        $levelNum = 5000; // 阶级系数
        $communityRate += 1;
        $investNum = ceil($payFee * $communityRate / $ratio / $levelNum);
        $investNum = $investNum > 2 ? $investNum : 2;

        // 投资金额
        $investMoney = $levelNum * $investNum;
        $investMoney = $investMoney > 20000 ? $investMoney+5000 : $investMoney+3500;

        // 收益金额
        $profitMoney = $investMoney * $rate / 12 * $months;
        // 到期返还金额
        $returnMoney = $investMoney + $profitMoney;

        return array(
            'investMoney' => F::price_discard($investMoney),
            'profitMoney' => F::price_discard($profitMoney),
            'returnMoney' => F::price_discard($returnMoney)
        );

    }

    /**
     * 获取小区浮动率
     * @param $communityId
     * @return int|mixed|null
     */
    private function getCommunityRate($communityId)
    {
        // 小区浮动率
        $criteria = new CDbCriteria;
        $criteria->condition = "community_id=:community_id";
        $criteria->params = array(':community_id'=>$communityId);
        $communityRateModel = ActivityIncreaseRate::model()->find($criteria);

        return $communityRateModel ? $communityRateModel->rate : 0;
    }

    /**
     * 校验续投订单是否能被续投
     * @param $sn
     * @param $modelName
     * @param $customerId
     * @return CActiveRecord
     */
    public function verifySn($sn, $modelName, $customerId)
    {
        // 2017-09-07
        return $this->sendErrorMessage(false, 0, '订单不可续投', '订单不可续投');

        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = PropertyActivity::model()->find($criteria);
        if ( !$orderModel)
            return $this->sendErrorMessage(false, 0, '续投订单不存在', '续投订单不存在');
        if ($orderModel->model != $modelName && ($orderModel->model != 'ParkingFeesMonth' && $modelName != 'ParkingFees'))
            return $this->sendErrorMessage(false, 0, '续投订单不合法', '续投订单不合法');
        if ( $customerId != $orderModel->customer_id)
            return $this->sendErrorMessage(false, 0, '只能续投自己订单', '只能续投自己订单');
        if (Item::PROFIT_ORDER_SUCCESS != $orderModel->status)
            return $this->sendErrorMessage(false, 0, '续投订单不是交易成功状态', '续投订单不是交易成功状态');
        if ($orderModel->mitigate_endtime > time() && !empty($orderModel->last_sn))
            return $this->sendErrorMessage(false, 0, '续投订单未到期', '续投订单未到期');
        //if ($orderModel->last_sn)
        //    return $this->sendErrorMessage(false, 0, '暂时不可连续续投', '暂时不可连续续投');

        unset($criteria);

        // 请求合和年是否可提现
        $result = $this->getOrderContinueState($sn, $customerId);
        if (false === $result)
            return false;

        if ('000' != $result['statusCode'])
            return $this->sendErrorMessage(false, 0, '订单不能续投', '订单不能续投');

        $criteria = new CDbCriteria;
        $criteria->condition = "last_sn=:sn AND status=:status";
        $criteria->params = array(':sn'=>$sn, ':status'=>Item::PROFIT_ORDER_AUTHORIZE);

        $nextOrderModel =  PropertyActivity::model()->find($criteria);
        if ($nextOrderModel)
            return $this->sendErrorMessage(false, 0, '订单号已存在续投订单', '订单号已存在续投订单');

        return $orderModel;
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
     * 订单提现时间验证
     * @param $payTime
     * @param $beginTime
     * @param $endTime
     * @param string $lastSn
     * @return bool
     */
    public function verifyWithdrawTime($payTime, $beginTime, $endTime, $lastSn='')
    {
        $nowTime = time();

        // 提现时间
        $WithDrawTime = $this->getWithdrawTime($payTime, $beginTime, $endTime, $lastSn);

        // 提现当日7点后才可提现
        if ($nowTime - $WithDrawTime > 25200)
            return true;

        return false;
    }

    /**
     * 计算订单提现时间
     * @param $payTime
     * @param $beginTime
     * @param $endTime
     * @param string $lastSn
     * @return bool
     */
    public function getWithdrawTime($payTime, $beginTime, $endTime, $lastSn='')
    {
        if ($lastSn) {
            $criteria = new CDbCriteria;
            $criteria->condition = "sn=:sn";
            $criteria->params = array(':sn'=>$lastSn);
            $orderModel = PropertyActivity::model()->find($criteria);
            // 续投订单 & 续投订单未到期
            if ( $orderModel && $orderModel->mitigate_endtime > $payTime )
                return $this->getWithdrawTime($orderModel->pay_time, $orderModel->mitigate_starttime, $endTime, $orderModel->last_sn);

        }

        // 计算提前时间差  支付时间第二天开始计算
        $diffTime = $beginTime - strtotime(date('Y-m-d 00:00:00', $payTime)." +1 day");

        // 提现时间
        $WithDrawTime = $endTime - $diffTime + 1;

        return $WithDrawTime;
    }

    /**
     * 冲抵物业费投资产品数据
     * @param $offsetMoney   每个月冲抵费用 （欠费为全部费用）
     * @param $communityId  冲抵地址小区ID
     * @param $modelName
     * @param $customerId
     * @param $sn
     * @return array
     */
    public function makeProfitList($offsetMoney, $communityId, $modelName, $customerId, $sn='')
    {
        $result = array();

        $list = PropertyActivityRate::rateList();
        if ($list) {
            // 冲抵周期开始时间
            $beginTime = strtotime('midnight first day of +1 month');
            if ($sn && 'propertyFees' != $modelName) {
                $orderModel = $this->verifySn($sn, $modelName, $customerId);
                if (false === $orderModel)
                    return false;

                if ($orderModel->mitigate_endtime > time())
                    $beginTime = $orderModel->mitigate_endtime + 86400;
            }

            // 小区浮动率
            $communityRate = $this->getCommunityRate($communityId);

            // 成功参加订单状态
            $countStatus = array(
                Item::PROFIT_ORDER_SUCCESS,
                Item::PROFIT_ORDER_CONTINUOUS,
                Item::PROFIT_ORDER_EXTRACT_ING,
                Item::PROFIT_ORDER_EXTRACT_SUCCESS,
                Item::PROFIT_ORDER_EXTRACT_FAIL
            );

            foreach ($list as $key => $row) {
                $countRate = Yii::app()->db->createCommand()
                                ->select(array('sum(amount) as sa', 'count(id) as ct'))
                                ->from('property_activity')
                                ->where(array('in', 'status', $countStatus))
                                ->andWhere('amount>:amount', array(':amount'=>1000))
                                ->andWhere('rate_id=:rate_id', array(':rate_id'=>$row->id))
                                ->queryRow();

                $countRateMoney = F::priceFormat(($countRate['sa'])/10000);
                $countRateNum = $countRate['ct'];

                $arr = array();
                $arr["countHouse"] = "<font color='#ff7e00'>".$countRateNum."</font>";
                $arr["countMoney"] = "<font color='#ff7e00'>".$countRateMoney."</font>";

                $resultRate = array();
                $resultRate['id'] = $row->id;
                $resultRate['month'] = $row->month;
                $resultRate['rate'] = $row->rate * 100;
                $resultRate['community_rate'] = $communityRate;
                $resultRate['name'] = $row->name;
                $resultRate['text'] = $row->replaceMessage($arr,$row->template);

                // 冲抵周期
                $investDate = $this->makeProfitDate($beginTime, $row->month);
                $resultRate['beginTime'] = date('Y-m', strtotime($investDate[0]));
                $resultRate['stopTime'] = date('Y-m', strtotime($investDate[1]));

                // 冲抵总费用
                $totalOffsetMoney = ($modelName == 'PropertyFees') ? $offsetMoney : $offsetMoney*$row->month;

                $profitData = $this->makeProfitData($totalOffsetMoney, $communityRate, $row->ratio, $row->rate, $row->month);

                $result[] = array_merge($resultRate, $profitData);
                unset($resultRate);

            }
        }

        return $result;
    }

    /**
     * 冲抵物业费投资产品详情
     * @param $profitProjectId
     * @param $offsetMoney
     * @param $communityId
     * @param $modelName
     * @param $customerId
     * @return array
     */
    public function makeProfitInfo($profitProjectId, $offsetMoney, $communityId, $modelName, $customerId, $sn='' , $addressJson='')
    {
        $projectModel = PropertyActivityRate::model()->findByPk($profitProjectId);
        if ( !$projectModel )
            return $this->sendErrorMessage(false, 0, '投资产品参数错误', '投资产品参数错误');

        $communityModel = Community::model()->enabled()->findByPk($communityId);
        if ( !$communityModel )
            return $this->sendErrorMessage(false, 0, '小区参数错误', '小区参数错误');

        // 小区浮动率
        $communityRate = $this->getCommunityRate($communityId);

        // 冲抵总费用
        $totalOffsetMoney = ($modelName == 'PropertyFees') ? $offsetMoney : $offsetMoney*$projectModel->month;

        // 计算投资收益
        $profitData = $this->makeProfitData($totalOffsetMoney, $communityRate, $projectModel->ratio, $projectModel->rate, $projectModel->month);

        //兼容银湾物业
        $address = '';
        if(empty($communityId) && !empty($addressJson)){
            $address_info = json_decode($addressJson , true);
            if(!empty($address_info)){
                $communityId = $address_info['community_code'];
                $address = $address_info['provice_name'].$address_info['city_name'].$address_info['area_name'].$address_info['community_name'];
            }
        }

        $tem = $communityModel->getCommunityAddress();
        $result = array();
        $result['projectId'] = $projectModel->id;
        $result['projectName'] = $projectModel->name;
        $result['projectMonth'] = $projectModel->month;
        $result['userRate'] = $projectModel->rate;
        $result['communityId'] = $communityId;
        $result['communityRate'] = $communityRate;
        $result['address'] = (empty($tem) && !empty($addressJson))? $address:$tem;
        $result['offsetMoney'] = F::price_discard($offsetMoney);
        $result['investMoney'] = $profitData['investMoney'];
        $result['profitMoney'] = $profitData['profitMoney'];
        $result['returnMoney'] = $profitData['returnMoney'];

        $result['payMoney'] = $result['investMoney'];
        $result['surplusMoney'] = 0;

        $result['lastOrder'] = '';

        // 冲抵周期开始时间
        $beginTime = strtotime('midnight first day of +1 month');
        if ($sn && 'propertyFees' != $modelName) {
            $orderModel = $this->verifySn($sn, $modelName, $customerId);
            if (false === $orderModel)
                return false;

            $lastOrder = array(
                'sn' => $orderModel->sn,
                'lastStopTime' => date('Y-m-d', $orderModel->mitigate_endtime),
                'lastReturnMoney' => F::price_discard($orderModel->amount + $orderModel->earnings + $orderModel->increase_amount + $orderModel->increase_profit),
            );

            $payMoney = $result['investMoney'] - $lastOrder['lastReturnMoney'];
            $surplusMoney = $lastOrder['lastReturnMoney'] - $result['investMoney'];

            $result['lastOrder'] = $lastOrder;
            $result['payMoney'] = F::price_discard($payMoney <= 0 ? 0 : $payMoney);
            $result['surplusMoney'] = strval($surplusMoney > 0 ? $surplusMoney : 0);

            if ($orderModel->mitigate_endtime > time())
                $beginTime = $orderModel->mitigate_endtime + 86400;
        }

        // 冲抵周期
        $investDate = $this->makeProfitDate($beginTime, $projectModel->month);
        $result['longBeginTime'] = $investDate[0];
        $result['beginTime'] = date('Y-m', strtotime($investDate[0]));
        $result['longStopTime'] = $investDate[1];
        $result['stopTime'] = date('Y-m', strtotime($investDate[1]));

        $result['increaseMessage'] = "享受年化收益：<font color='#ff7e00' size='14'>".($this->increaseRates[$result['projectMonth']] * 100)."%</font>";

        return $result;
    }

    /**
     * 获取预缴费月金额
     * @param $roomId
     * @param $uuid
     * @return bool
     */
    public function getPropertyFee($roomId, $uuid='' , $source = '')
    {
        $fees = OthersAdvanceFees::model()->getApiAdvancePayfee($roomId, 1, $uuid , $source);
        if($source == 'yinwan'){
            if ($fees && isset($fees["fee"]) && 0 != $fees["fee"] ) {
                return $fees["fee"];
            }
        }else{
            if ($fees && isset($fees[0]["fee"]) && 0 != $fees[0]["fee"] ) {
                return $fees[0]["fee"];
            }
        }

        return $this->sendErrorMessage(false, 0, '获取每月物业费失败', '获取每月物业费失败');
    }

    /**
     * 获取历史欠费数据
     * @param $roomId
     * @param $uuid
     * @return array
     */
    public function getHistoryFee($roomId, $uuid='')
    {
        Yii::import('common.api.IceApi');
        $fees = IceApi::getInstance()->callGetPayOwnerToll($roomId, $uuid);
        if (empty($fees['data']['data']))
            return $this->sendErrorMessage(false, 0, '获取历史欠费失败', $fees['code']===0 ? '获取历史欠费失败' : $fees['message']);

        $propertyFees = $fees['data']['data'];
        $totalMoney = 0;
        foreach ($propertyFees as $value){
            $totalMoney += $value["actualfee"];
        }

        return array('totalMoney'=>$totalMoney, 'propertyFees'=>$propertyFees);
    }

    /**
     * 获取订单列表
     * @param $customerId
     * @param $orderModelName
     * @param $orderStatusType
     * @param $page
     * @param $pageSize
     * @return array|CActiveRecord|mixed|null
     */
    public function getOrderList($customerId, $orderModelName, $orderStatusType, $page=1, $pageSize=10)
    {
        $modelNameList = array('PropertyActivity', 'PropertyFees', 'ParkingFees');
        $statusTypeList = array('all', 'success', 'connection');

        if ( !in_array($orderModelName, $modelNameList))
            return $this->sendErrorMessage(false, 0, '订单类型参数错误', '订单类型参数错误');
        if ( !in_array($orderStatusType, $statusTypeList))
            return $this->sendErrorMessage(false, 0, '订单状态类别参数错误', '订单状态类别参数错误');


        $modelNames = array($orderModelName);
        if ('ParkingFees' == $orderModelName)
            $modelNames = array('ParkingFees', 'ParkingFeesMonth');

        $criteria = new CDbCriteria;
        $criteria->addInCondition('model', $modelNames);
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
                    Item::PROFIT_ORDER_REDEEM_DONE,
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

        $criteria->order = 'create_time desc';

        // 总条数
        $total = PropertyActivity::model()->count($criteria);

        $criteria->offset = ( $page - 1 ) * $pageSize;
        $criteria->limit = $pageSize;


        $list = PropertyActivity::model()->findAll($criteria);

        return array(
            'total' => $total,
            'list'  => $list
        );
    }

    /**
     * 获取订单button [buttonStyle 1-可跳转 0-不可跳转] [buttonType 0-续投 1-提现]
     * @param $orderModel
     * @return array
     */
    public function getOrderButton($orderModel)
    {
        if (empty($orderModel))
            return array();

        $buttons = array();

        // 判断是否到提现时间
        $isCanWithdraw = $this->verifyWithdrawTime($orderModel->pay_time, $orderModel->mitigate_starttime, $orderModel->mitigate_endtime, $orderModel->last_sn);

        if (Item::PROFIT_ORDER_SUCCESS == $orderModel->status) {
            if (
                'PropertyActivity' == $orderModel->model
                || 'ParkingFees' == $orderModel->model
                || 'ParkingFeesMonth' == $orderModel->model
            ) {

                $buttons[] = array(
                    'buttonStyle' => 0,
                    'buttonType' => 0,
                    'buttonName' => '续投',
                    'buttonTip' => '提前续投，连续冲抵物业费，免去缴费烦扰'
                );

                if ($isCanWithdraw) {
                    $buttons[] = array(
                        'buttonStyle' => 1,
                        'buttonType' => 1,
                        'buttonName' => '提现',
                        'buttonTip' => ''
                    );
                }
            }
            elseif ('PropertyFees' == $orderModel->model) {

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
        elseif (
            Item::PROFIT_ORDER_EXTRACT_FAIL == $orderModel->status
            || Item::PROFIT_ORDER_REDEEM_DONE == $orderModel->status
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
     * 获取增值计划数据
     * @param $month
     * @return array
     */
    public function getIncreaseValue($month)
    {
        if ( !isset($this->increaseRates[$month]))
            return $this->sendErrorMessage(false, 0, '参数错误', '参数错误');
        if ( !isset($this->bankRates[$month]))
            return $this->sendErrorMessage(false, 0, '参数错误', '参数错误');

        $increaseRate = $this->increaseRates[$month];
        $bankRate = $this->bankRates[$month];

        $diff = sprintf('%.2f', ($increaseRate / $bankRate));

        return array(
            'rate' => sprintf('%.3f', $increaseRate),
            'diff' => $diff,
            'temp' => sprintf('%.2f', $this->increaseStep),
            'tip'  => "彩富人生全新升级，现在参加，可自由增加定投金额（限{$this->increaseStep}元整数倍），坐享更多收益回报。"
        );
    }

    /**
     * 获取每个类型投资总信息
     * @param $customerId
     * @param $modelName
     * @return mixed
     */
    public function getProfitTotal($customerId, $modelName)
    {
        $modelNameList = array('PropertyActivity', 'PropertyFees', 'ParkingFees');

        if ( !in_array($modelName, $modelNameList))
            return $this->sendErrorMessage(false, 0, '类型参数错误', '类型参数错误');

        $para = array(
            'userId' => $customerId,
            'reqTime' => time(),
            'orderModel' => $modelName
        );

        $queryUrl = $this->serverUrl.'activity/colorlife/userincome';
        $queryData = $this->makeQueryData($para);

        $result =  Yii::app()->curl->get($queryUrl, $queryData);
        if ( ! $result)
            return $this->sendErrorMessage(false, 0, '请求合和年数据异常', '请求合和年数据异常');

        $result = json_decode($result, true);

        return $result;
    }

    /**
     * 获取投资总信息
     * @param $customerId
     * @return mixed
     */
    public function getAssetsTotal($customerId)
    {
        $para = array(
            'userId' => $customerId,
            'reqTime' => time(),
        );

        $queryUrl = $this->serverUrl.'activity/colorlife/investAmount';
        $queryData = $this->makeQueryData($para);

        $result =  Yii::app()->curl->get($queryUrl, $queryData);
        if ( ! $result)
            return $this->sendErrorMessage(false, 0, '请求合和年数据异常', '请求合和年数据异常');

        $result = json_decode($result, true);

        return $result;
    }

    /**
     * 创建彩之云订单信息
     * @param $colorCloudId
     * @param $colorCloudBuilding
     * @param $colorCloudUnit
     * @param $colorCloudBills
     * @return mixed
     * @throws CException
     */
    public function getColorCloudOrder($colorCloudId, $colorCloudBuilding, $colorCloudUnit, $colorCloudBills, $uuid)
    {
        Yii::import('common.api.IceApi');
        $result = IceApi::getInstance()->callGetOrderCreate($colorCloudId, $colorCloudBuilding, $colorCloudUnit, $colorCloudBills, '', $uuid);

        if (!isset($result) || $result['code']!=0 || $result['content']['state'] != 1)
            return $this->sendErrorMessage(false, 0, '收费系统订单创建失败', $result['message']);

        //如果收费系统返回的金额为0,那么不创建我们的订单。直接报错
        if($result['content']['orderamount']<=0)
            return $this->sendErrorMessage(false, 0, '彩之云收费系统异常', '彩之云收费系统异常');

        return $result['content'];
    }

    /**
     * 创建订单
     * @param $saveData
     * @param $modelName
     * @return array
     */
     public function createOrder($saveData, $modelName)
     {
        $saveData['sn'] = SN::initByPropertyActivity()->sn;

        $model = new PropertyActivity();
        $model->sn = $saveData['sn'];
        $model->model = $modelName;
        $model->rate_id = $saveData['rate_id'];
        $model->customer_id = $saveData['customer_id'];
        $model->amount = $saveData['amount'];
        $model->user_rate = $saveData['user_rate'];
        $model->colourlife_rate = $saveData['colourlife_rate'];
        $model->reduction = $saveData['reduction'];
        $model->earnings = $saveData['earnings'];
        $model->community_rate = $saveData['community_rate'];
        $model->mitigate_starttime = $saveData['mitigate_starttime'];
        $model->mitigate_endtime = $saveData['mitigate_endtime'];
        $model->status = $saveData['status'];
        $model->last_sn = $saveData['last_sn'];
        $model->increase_amount = $saveData['increase_amount'];
        $model->increase_rate = $saveData['increase_rate'];
        $model->increase_profit = $saveData['increase_profit'];
        $model->surplus_money = $saveData['surplus_money'];
        $model->inviter_mobile = $saveData['inviter_mobile'];
        $model->note = $saveData['note'];
        $model->create_time = time();
        $model->profit_type = $saveData['profit_type'];

        //推荐人提成
        $model->ticheng_amount = F::price_discard(($saveData['amount'] + $saveData['increase_amount']) * Yii::app()->config->caifuTichengRate/12 * $saveData['months']);

        if ('PropertyActivity' == $modelName) {

            $feeModel = new AdvanceFee();

            if(isset($saveData['source']) && $saveData['source']=='yinwan'  && !empty($saveData['address_json'])){
                $feeModel->room = $saveData['address_json'];
                //$feeModel->colorcloud_unit = $saveData['address_json'];
            }else{
                $feeModel->community_id = $saveData['community_id'];
                $feeModel->build = $saveData['build'];
                $feeModel->room = $saveData['room'];
                $feeModel->colorcloud_building = $saveData['colorcloud_building'];
                $feeModel->colorcloud_unit = $saveData['colorcloud_unit'];
            }



            if ($feeModel->save()) {
                $model->object_id = $feeModel->id;
                if ($model->save()) {
                    //Yii::log("小区：{$ParkingFeesMonthModel->community_id}，停车费类型：{$ParkingFeesMonthModel->fee_unit}，停车费下单：{$OthersFeesModel->sn}，金额：{$OthersFeesModel->amount}，用户：{$OthersFeesModel->customer_id}", CLogger::LEVEL_INFO, 'colourlife.api.OrderFrees.ParkingFees');
                    //OthersFeesLog::createOtherFeesLog($OthersFeesModel->id, 'Customer', Item::FEES_AWAITING_PAYMENT, Yii::app()->user->id . '月停车费下单');
                    return array(
                        'sn' => $model->sn,
                    );
                }
                else {
                    $oldRe = $feeModel::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $feeModel->id)));
                    $oldRe->delete();
                    //OthersAdvanceFees::model()->addError('id', "创建停车费订单失败！");
                    //throw new CHttpException(400, $model->getErrors());
                    return $this->sendErrorMessage(false, 0, '创建订单失败', $model->getErrors());
                }
            }
            else {
                //throw new CHttpException(400, $feeModel->getErrors());
                return $this->sendErrorMessage(false, 0, '创建订单失败', $feeModel->getErrors());
            }
        }
        if ('PropertyFees' == $modelName) {

            $model->erp_reduction = $saveData['erp_reduction'];

            $feeModel = new PropertyFees();
            $feeModel->community_id = $saveData['community_id'];
            $feeModel->build = $saveData['build'];
            $feeModel->room = $saveData['room'];
            $feeModel->colorcloud_building = $saveData['colorcloud_building'];
            $feeModel->colorcloud_unit = $saveData['colorcloud_unit'];
            $feeModel->colorcloud_bills = $saveData['colorcloud_bills'];
            $feeModel->colorcloud_order = $saveData['colorcloud_order'];

            if ($feeModel->save()) {
                $model->object_id = $feeModel->id;
                if ($model->save()) {
                    //Yii::log("小区：{$ParkingFeesMonthModel->community_id}，停车费类型：{$ParkingFeesMonthModel->fee_unit}，停车费下单：{$OthersFeesModel->sn}，金额：{$OthersFeesModel->amount}，用户：{$OthersFeesModel->customer_id}", CLogger::LEVEL_INFO, 'colourlife.api.OrderFrees.ParkingFees');
                    //OthersFeesLog::createOtherFeesLog($OthersFeesModel->id, 'Customer', Item::FEES_AWAITING_PAYMENT, Yii::app()->user->id . '月停车费下单');
                    return array(
                        'sn' => $model->sn,
                    );
                }
                else {
                    $oldRe = $feeModel::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $feeModel->id)));
                    $oldRe->delete();
                    //OthersAdvanceFees::model()->addError('id', "创建停车费订单失败！");
                    //throw new CHttpException(400, $model->getErrors());
                    return $this->sendErrorMessage(false, 0, '创建订单失败', $model->getErrors());
                }
            }
            else {
                //throw new CHttpException(400, $feeModel->getErrors());
                return $this->sendErrorMessage(false, 0, '创建订单失败', $feeModel->getErrors());
            }
        }
        if ('ParkingFees' == $modelName) {
            Yii::import('common.services.ParkingService');
            $parkingService = new ParkingService();
            $parkingData = $parkingService->makeParkingData($saveData['car_number'], $saveData['third_park_type'], $saveData['fee_unit_id'], $saveData['months'], $saveData['third_fees'], $saveData['sn']);
            if (false === $parkingData)
                return $this->sendErrorMessage(false, 0, $parkingService->getErrorMsg(), $parkingService->getLogMsg());

            if (Item::PARKING_TYPE_COLOURLIFE == $parkingData['third_park_type']) {

                $model->model = 'ParkingFees';

                $feeModel = new ParkingFees();
                $feeModel->car_number = $parkingData['car_number'];
                $feeModel->type_id = $parkingData['fee_unit_id'];
                $feeModel->period = $saveData['months'];
                $feeModel->community_id = $saveData['community_id'];
                $feeModel->build_id = $saveData['build_id'];
                $feeModel->room = $saveData['room'];

                if ($feeModel->save()) {

                    $model->object_id = $feeModel->id;
                    if ($model->save()) {
                        //Yii::log("小区：{$ParkingFeesMonthModel->community_id}，停车费类型：{$ParkingFeesMonthModel->fee_unit}，停车费下单：{$OthersFeesModel->sn}，金额：{$OthersFeesModel->amount}，用户：{$OthersFeesModel->customer_id}", CLogger::LEVEL_INFO, 'colourlife.api.OrderFrees.ParkingFees');
                        //OthersFeesLog::createOtherFeesLog($OthersFeesModel->id, 'Customer', Item::FEES_AWAITING_PAYMENT, Yii::app()->user->id . '月停车费下单');
                        return array(
                            'sn' => $model->sn,
                        );
                    }
                    else {
                        $oldRe = $feeModel::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $feeModel->id)));
                        $oldRe->delete();
                        //OthersAdvanceFees::model()->addError('id', "创建停车费订单失败！");
                        //throw new CHttpException(400, $model->getErrors());
                        return $this->sendErrorMessage(false, 0, '创建订单失败', $model->getErrors());
                    }
                }
                else {
                    //throw new CHttpException(400, json_encode($feeModel->getErrors()));
                    return $this->sendErrorMessage(false, 0, '创建订单失败', $feeModel->getErrors());
                }
            }
            else {

                $model->model = 'ParkingFeesMonth';
                $feeModel = new ParkingFeesMonth();

                $feeModel->community_id = $saveData['community_id'];
                $feeModel->build_id = $saveData['build_id'];
                $feeModel->room = $saveData['room'];
                $feeModel->third_park_type = $parkingData['third_park_type'];
                $feeModel->third_park_id = $parkingData['third_park_id'];
                $feeModel->third_order_id = $parkingData['third_order_id'];
                $feeModel->car_number = $parkingData['car_number'];
                $feeModel->fee_unit_id = $parkingData['fee_unit_id'];
                $feeModel->fee_unit = $parkingData['fee_unit'];
                $feeModel->fee_number = $parkingData['fee_number'];
                $feeModel->out_result = $parkingData['out_result'];

                if ($feeModel->save()) {

                    $model->object_id = $feeModel->id;
                    if ($model->save()) {
                        //Yii::log("小区：{$ParkingFeesMonthModel->community_id}，停车费类型：{$ParkingFeesMonthModel->fee_unit}，停车费下单：{$OthersFeesModel->sn}，金额：{$OthersFeesModel->amount}，用户：{$OthersFeesModel->customer_id}", CLogger::LEVEL_INFO, 'colourlife.api.OrderFrees.ParkingFees');
                        //OthersFeesLog::createOtherFeesLog($OthersFeesModel->id, 'Customer', Item::FEES_AWAITING_PAYMENT, Yii::app()->user->id . '月停车费下单');
                        return array(
                            'sn' => $model->sn,
                        );
                    }
                    else {
                        $oldRe = $feeModel::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $feeModel->id)));
                        $oldRe->delete();
                        //OthersAdvanceFees::model()->addError('id', "创建停车费订单失败！");
                        return $this->sendErrorMessage(false, 0, '创建订单失败', $model->getErrors());
                    }
                }
                else {
                    return $this->sendErrorMessage(false, 0, '创建订单失败', $feeModel->getErrors());
                }
            }
        }

        return $this->sendErrorMessage(false, 0, '创建订单失败', '创建订单失败');
     }

    //ICE
    /**
     * 获取冲抵物业费明细
     * @param $sn
     * @param int $page
     * @param int $pageSize
     * @return mixed
     * @throws CException
     */
    public function getPropertyFeeCostDetail($sn, $page=1, $pageSize=10)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = PropertyActivity::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单号不存在', '订单号不存在');

        $statusArr = array(
            Item::PROFIT_ORDER_SUCCESS,
            Item::PROFIT_ORDER_CONTINUOUS,
            Item::PROFIT_ORDER_EXTRACT_ING,
            Item::PROFIT_ORDER_EXTRACT_SUCCESS,
            Item::PROFIT_ORDER_EXTRACT_FAIL,
        );

        if ( !in_array($orderModel->status, $statusArr))
            return $this->sendErrorMessage(false, 0, '订单未付款', '订单未付款');

        $year = date('Y', $orderModel->mitigate_starttime);
        $month = date('m', $orderModel->mitigate_starttime);

        if ('PropertyActivity' == $orderModel->model) {
            $unitid = $orderModel->AdvanceFees->colorcloud_unit;
            $uuid = empty($orderModel->AdvanceFees) ? null : empty($orderModel->AdvanceFees->community->colorcloudCommunity)?null:$orderModel->AdvanceFees->community->colorcloudCommunity[0]->color_community_id;
        }
        elseif ('PropertyFees' == $orderModel->model) {
            $unitid = $orderModel->PropertyFees->colorcloud_unit;
            $uuid = empty($orderModel->PropertyFees) ? null : empty($orderModel->PropertyFees->community->colorcloudCommunity)?null:$orderModel->PropertyFees->community->colorcloudCommunity[0]->color_community_id;
        }
        else {
            return $this->sendErrorMessage(false, 0, '不是物业费订单', '不是物业费订单');
        }

        //引入彩之云的接口
        Yii::import('common.api.IceApi');
        //实例化
        $colourCloudApi = IceApi::getInstance();
        $result = $colourCloudApi->callGetPayAppLog($unitid, $year, $month, $pageSize, $page, $uuid);
        if ($result['code']!=0 || !isset($result['data'])) {
            //如果未找到冲抵明细
            return $this->sendErrorMessage(false, 0, '获取用户冲抵明细记录失败', '获取用户冲抵明细记录失败');

            $data["total"] = $result["data"]["total"];
            $data['offsetMonty'] = $orderModel->reduction;
            $data['totalMoney'] = sprintf('%.2f', ($orderModel->amount + $orderModel->increase_amount));

            $data["list"] = array();
            if ($result['data']['data']) {
                foreach ($result['data']['data'] as $row) {
                    $data['list'][] = array(
                        'date' => $row["billyear"]."-".sprintf('%02d', $row["billmonth"]),
                        'discount' => F::price_discard($row["receivedfee"]),
                        'discountState' => '成功',
                        'billId' => $row["billid"],
                        'tollItemName' => $row["tollitemname"]
                    );
                }
            }

            return $data;
        }
    }

    /**
     * 订单通知 停车场续卡 & 物业ERP订单创建
     * @param $sn
     * @return bool
     * @throws CException
     */
    public function orderNotify($sn)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn' => $sn);
        $orderModel = PropertyActivity::model()->find($criteria);

        if (!$orderModel)
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        $activityRateModel = PropertyActivityRate::model()->findByPk($orderModel->rate_id);
        if (!$activityRateModel)
            return $this->sendErrorMessage(false, 0, '订单投资项目不存在', '订单投资项目不存在');


        if (in_array($orderModel->model, array('PropertyActivity', 'PropertyFees'))) {

            $year = date('Y', $orderModel->mitigate_starttime);
            $month = intval(date('m', $orderModel->mitigate_starttime));

            if ('PropertyActivity' == $orderModel->model) {
                $unitId = $orderModel->AdvanceFees->colorcloud_unit;
                $flag = 0;
                $offsetMoney = $orderModel->reduction * $activityRateModel->month;
                $note = '冲抵物业费订单交易成功';
                $communityId = $orderModel->AdvanceFees->community_id;
            } elseif ('PropertyFees' == $orderModel->model) {
                $unitId = $orderModel->PropertyFees->colorcloud_unit;
                $flag = 1;
                $offsetMoney = $orderModel->reduction;
                $note = '欠费冲抵订单交易成功';
                $communityId = $orderModel->PropertyFees->community_id;
            }

            $comm = ColorcloudCommunity::model()->find('community_id=:community_id', array(':community_id'=>$communityId));
            $uuid = empty($comm) ? null : $comm->color_community_id;

            //引入彩之云的接口
            //Yii::import('common.api.ColorCloudApi');
            Yii::import('common.api.IceApi');
            //实例化
            //$coloure = ColorCloudApi::getInstance();
            $coloure = IceApi::getInstance();

            //使用彩之云的接口创建彩之云的缴费订单
            $result = $coloure->callSetAdvanceSaveAppFee($unitId, $sn, $flag, $offsetMoney, $offsetMoney, $offsetMoney, $year, $month, 1, $note, $uuid);
            if (!isset($result) || $result['code'] != 0 || $result['message'] != '缴费成功') {
                return $this->sendErrorMessage(false, 0, '物业费系统订单创建请求异常', isset($result['message']) ? $result['message'] : '物业费系统订单创建请求异常');

                return true;
            }
        }
        else if ('ParkingFeesMonth' == $orderModel->model) {
            $parkingLotType = intval($orderModel->ParkingFeesMonth->third_park_type);
            if (Item::PARKING_TYPE_GEMEITE === $parkingLotType) {
                Yii::import('common.api.GemeiteApi');
                $out_result = json_decode($orderModel->ParkingFeesMonth->out_result, true);
                $queryResult = GemeiteApi::getInstance()->notify(
                    $orderModel->sn,
                    $orderModel->ParkingFeesMonth->third_park_id,
                    $orderModel->ParkingFeesMonth->car_number,
                    $orderModel->ParkingFeesMonth->fee_unit_id,
                    $orderModel->ParkingFeesMonth->fee_number,
                    $out_result['time_start'],
                    $orderModel->ParkingFeesMonth->third_order_id,
                    $orderModel->pay_time
                );

                if ($queryResult && 'success' === strtolower($queryResult['data']['retmsg']))
                    return true;

            }
            if (Item::PARKING_TYPE_AIKE === $parkingLotType) {
                Yii::import('common.api.AikeApi');
                $fee_unit_array = explode('/', $orderModel->ParkingFeesMonth->fee_unit);
                $result = AikeApi::getInstance()->renewMonthCard(
                    $orderModel->ParkingFeesMonth->third_park_id,
                    $orderModel->ParkingFeesMonth->car_number,
                    date('Y-m-d H:i:s', $orderModel->pay_time),
                    $orderModel->ParkingFeesMonth->fee_number,
                    $orderModel->reduction * $orderModel->ParkingFeesMonth->fee_number,
                    $fee_unit_array[0],
                    date('Y-m-d H:i:s')
                );

                if (false !== $result)
                    return true;
            }
            if (Item::PARKING_TYPE_HANWANG === $parkingLotType) {
                Yii::import('common.api.HanwangApi');
                $result = HanwangApi::getInstance()->carDelay(
                    $orderModel->ParkingFeesMonth->car_number,
                    $orderModel->ParkingFeesMonth->third_park_id,
                    $orderModel->ParkingFeesMonth->fee_number,
                    $orderModel->reduction * $orderModel->ParkingFeesMonth->fee_number,
                    $sn
                );

                if (false !== $result)
                    return true;
            }
            if (Item::PARKING_TYPE_YITINGCHE === $parkingLotType) {
            	Yii::import('common.api.YitingcheApi');
            	$result = YitingcheApi::getInstance()->notify($orderModel->ParkingFeesMonth->third_order_id, $sn);
            
            	if (false !== $result)
            		return true;
            }

            return $this->sendErrorMessage(false, 0, '第三方停车类型错误', '第三方停车类型错误');
        }
        else if ('ParkingFees' == $orderModel->model)
            return true;

        return $this->sendErrorMessage(false, 0, '订单Model类型错误', '订单Model类型错误');

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
        $orderModel = PropertyActivity::model()->find($criteria);

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
        $orderModel = PropertyActivity::model()->find($criteria);

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
     * 统计
     * @param $modelName
     * @return mixed
     */
    public function countOrder($modelName)
    {
        $modelNameList = array('PropertyActivity', 'PropertyFees', 'ParkingFees');

        if ( !in_array($modelName, $modelNameList))
            return $this->sendErrorMessage(false, 0, '类型参数错误', '类型参数错误');

        $modelNames = array($modelName);
        if ('ParkingFees' == $modelName)
            $modelNames = array('ParkingFees', 'ParkingFeesMonth');

        $statusArr = array(
            Item::PROFIT_ORDER_SUCCESS,
            Item::PROFIT_ORDER_CONTINUOUS,
            Item::PROFIT_ORDER_EXTRACT_ING,
            Item::PROFIT_ORDER_EXTRACT_SUCCESS,
            Item::PROFIT_ORDER_EXTRACT_FAIL,
        );

        $sql = "SELECT SUM(`amount`) AS `sum`, COUNT(`id`) AS `count` FROM `property_activity` WHERE `status` IN (" .implode(',', $statusArr). ") AND `model` IN ('" .implode('\',\'', $modelNames) . "')";

        $result = Yii::app()->db->createCommand($sql)->queryRow();

        return $result;
    }

    /**
     * 动态
     * @param $modelName
     * @return array|mixed|null
     */
    public function getMoving($modelName)
    {
        $modelNameList = array('PropertyActivity', 'PropertyFees', 'ParkingFees');

        if ( !in_array($modelName, $modelNameList))
            return $this->sendErrorMessage(false, 0, '类型参数错误', '类型参数错误');

        $modelNames = array($modelName);
        if ('ParkingFees' == $modelName)
            $modelNames = array('ParkingFees', 'ParkingFeesMonth');

        $criteria = new CDbCriteria;
        $criteria->select = 'customer_name, amount';
        $criteria->addInCondition('model', $modelNames);

        $statusArr = array(
            Item::PROFIT_ORDER_SUCCESS,
            Item::PROFIT_ORDER_CONTINUOUS,
            Item::PROFIT_ORDER_EXTRACT_ING,
            Item::PROFIT_ORDER_EXTRACT_SUCCESS,
            Item::PROFIT_ORDER_EXTRACT_FAIL,
        );

        $criteria->addInCondition('status', $statusArr);
        $criteria->limit = 5;
        $criteria->order = 'id DESC';

        $list = PropertyActivity::model()->findAll($criteria);

        return $list;
    }

     /**
      * 彩富订单投资交易协议接口
      * @param $sn
      * @return mixed
      * @throws CException
      */
    public function serviceAgreement($sn)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sn=:sn";
        $criteria->params = array(':sn'=>$sn);
        $orderModel = PropertyActivity::model()->find($criteria);


        $appreciationModel = AppreciationPlan::model()->find($criteria);

        if ( !$orderModel && !$appreciationModel )
            return $this->sendErrorMessage(false, 0, '订单号不存在', '订单号不存在');

        Yii::import('common.api.HhNianApi');
        $result = HhNianApi::getInstance()->serviceAgreement($sn);
        if (false === $result)
            return $this->sendErrorMessage(false, 0, '请求合和年订单投资交易协议接口异常', '请求合和年订单投资交易协议接口异常');

        if (isset($result['RESP_CODE']) && '000' === $result['RESP_CODE']) {
            $outData['content'] = json_decode($result['RESP_MSG'], true);
            $outData['downloadURL'] =  HhNianApi::getInstance()->investAgreement($sn);

            return $outData;
        }


        return $this->sendErrorMessage(false, 0, $result['RESP_MSG'], $result['RESP_MSG']);
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
        $orderModel = PropertyActivity::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单号不存在', '订单号不存在');

        if ( 'PropertyFees' == $orderModel->model)
            return $this->sendErrorMessage(false, 0, '冲抵欠费不可提前赎回', '冲抵欠费不可提前赎回');

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
        $orderModel = PropertyActivity::model()->find($criteria);

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

        if ($orderModel->mitigate_starttime > time())
            return $this->sendErrorMessage(false, 0, '订单未生效', '订单未生效');

        return true;
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
        $orderModel = PropertyActivity::model()->find($criteria);

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
        $orderModel = PropertyActivity::model()->find($criteria);

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
        $orderModel = PropertyActivity::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_SUCCESS == $orderModel->status)
            return true;

        //if (Item::PROFIT_ORDER_AUTHORIZE != $orderModel->status)
        if ( ! in_array($orderModel->status, array(Item::PROFIT_ORDER_AUTHORIZE)))
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[已支付]状态', '订单禁止修改为[已支付]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');


        $orderModel->status = Item::PROFIT_ORDER_SUCCESS;
        $orderModel->pay_sn = $orderPaySn;
        $orderModel->pay_time = $orderSuccessTime;
        $orderModel->payment_passage = $paymentPassage;
        $orderModel->update_time = time();

        $notifyResult = $this->orderNotify($sn);
        if (true === $notifyResult)
            $orderModel->is_receive = 1;

        // 开启事物
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        // 续投订单 赠送 50元饭票
        //$isSendRedPacket = false;

        if ($orderModel->last_sn) {
            $criteria = new CDbCriteria;
            $criteria->condition = "sn=:sn";
            $criteria->params = array(':sn'=>$orderModel->last_sn);
            $lastOrderModel = PropertyActivity::model()->find($criteria);

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
            $surplusMoney = $lastOrderModel->amount + $lastOrderModel->earnings + $lastOrderModel->increase_amount + $lastOrderModel->increase_profit;
            $payMoney = $orderModel->amount + $orderModel->increase_amount;
            $lastOrderModel->surplus_money = $surplusMoney > $payMoney ? $surplusMoney - $payMoney : 0;

            if ( !$lastOrderModel->save()) {
                $transaction->rollBack();
                return $this->sendErrorMessage(false, 0, '历史订单更新失败', $orderModel->$lastOrderModel());
            }

            //$isSendRedPacket = true;
        }


        if ( ! $orderModel->save() ) {
            $transaction->rollBack();
            return $this->sendErrorMessage(false, 0, '订单更新为[已支付]失败', $orderModel->getErrors());
        }

        $transaction->commit();

        //TODO 保险活动 活动到期去掉
        /**
        Yii::import('common.services.InsureService');
        $insureService = new InsureService();
        $price = $orderModel->amount + $orderModel->increase_amount;
        $insureService->profitActivity($customerId, $sn, $price, $orderSuccessTime);
        **/

        //TODO 彩富VIP尊享活动 到期删除
        if (strtotime('2017-06-30 23:59:59') > $orderSuccessTime && strtotime('2016-09-14 00:00:00') < $orderSuccessTime) {
            if ( $orderModel->PropertyActivityRate->month >= 12) {
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
        $orderModel = PropertyActivity::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_EXTRACT_ING == $orderModel->status)
            return true;

        if ( ! in_array($orderModel->status, array(Item::PROFIT_ORDER_SUCCESS, Item::PROFIT_ORDER_CONTINUOUS, Item::PROFIT_ORDER_EXTRACT_FAIL, Item::PROFIT_ORDER_REDEEM_DONE)))
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
        $orderModel = PropertyActivity::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_EXTRACT_FAIL == $orderModel->status)
            return true;

        if (Item::PROFIT_ORDER_EXTRACT_ING != $orderModel->status)
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[提现失败]状态', '订单禁止修改为[提现失败]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

        $orderModel->status = Item::PROFIT_ORDER_EXTRACT_FAIL;
        $orderModel->cash_result_time = $cashResultTime;
        $orderModel->update_time = time();

        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '订单更新为[提现失败]失败', $orderModel->getErrors());

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
        $orderModel = PropertyActivity::model()->find($criteria);

        if ( !$orderModel )
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::PROFIT_ORDER_EXTRACT_SUCCESS == $orderModel->status)
            return true;

        if (Item::PROFIT_ORDER_EXTRACT_ING != $orderModel->status)
            return $this->sendErrorMessage(false, 0, '订单禁止修改为[提现成功]状态', '订单禁止修改为[提现成功]状态');

        if ($orderModel->customer_id != $customerId)
            return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

        $orderModel->status = Item::PROFIT_ORDER_EXTRACT_SUCCESS;
        $orderModel->cash_result_time = $cashResultTime;
        $orderModel->update_time = time();

        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '订单更新为[提现成功]失败', $orderModel->getErrors());

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
        $orderModel = PropertyActivity::model()->find($criteria);

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
      * 更新订单为已赎回，待消单
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
         $orderModel = PropertyActivity::model()->find($criteria);

         if ( !$orderModel )
             return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

         if (Item::PROFIT_ORDER_REDEEM_SUCCESS == $orderModel->status)
             return true;

         if ( !in_array($orderModel->status, array(Item::PROFIT_ORDER_REDEEM_ING, Item::PROFIT_ORDER_SUCCESS)))
             return $this->sendErrorMessage(false, 0, '订单禁止修改为[已赎回，待消单]状态', '订单禁止修改为[已赎回，待消单]状态');

         if (Item::PROFIT_ORDER_SUCCESS == $orderModel->status && empty($orderModel->last_sn))
             return $this->sendErrorMessage(false, 0, '订单禁止修改为[已赎回，待消单]状态', '订单禁止修改为[已赎回，待消单]状态');

         if ($orderModel->customer_id != $customerId)
             return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

         $orderModel->status = Item::PROFIT_ORDER_REDEEM_SUCCESS;
         $orderModel->cash_result_time = $cashResultTime;
         $orderModel->update_time = time();

         // 有续投订单，提现金额恢复原来的金额
         if ($orderModel->next_sn) {
             $surplusMoney = $orderModel->amount + $orderModel->earnings + $orderModel->increase_amount + $orderModel->increase_profit;
             $orderModel->surplus_money = $surplusMoney;
         }

         // 续投后订单 可提现金额要减去历史可提现订单金额
         if ($orderModel->last_sn) {
             $criteria = new CDbCriteria;
             $criteria->condition = "sn=:sn";
             $criteria->params = array(':sn'=>$orderModel->last_sn);
             $lastOrderModel = PropertyActivity::model()->find($criteria);

             if ( !$lastOrderModel ) {
                 return $this->sendErrorMessage(false, 0, '历史订单不存在', '历史订单不存在');
             }

             $orderModel->surplus_money = 0;
             if ($lastOrderModel->surplus_money < ($orderModel->amount + $orderModel->increase_amount))
                 $orderModel->surplus_money = ($orderModel->amount + $orderModel->increase_amount) - $lastOrderModel->surplus_money;
         }

         if ( ! $orderModel->save() )
             return $this->sendErrorMessage(false, 0, '订单更新为[已赎回，待消单]失败', $orderModel->getErrors());

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
         $orderModel = PropertyActivity::model()->find($criteria);

         if ( !$orderModel )
             return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

         if (Item::PROFIT_ORDER_REDEEM_FAIL == $orderModel->status)
             return true;

         if (Item::PROFIT_ORDER_REDEEM_ING != $orderModel->status)
             return $this->sendErrorMessage(false, 0, '订单禁止修改为[赎回失败]状态', '订单禁止修改为[赎回失败]状态');

         if ($orderModel->customer_id != $customerId)
             return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

         $orderModel->status = Item::PROFIT_ORDER_REDEEM_FAIL;
         $orderModel->cash_result_time = $cashResultTime;
         $orderModel->update_time = time();

         if ( ! $orderModel->save() )
             return $this->sendErrorMessage(false, 0, '订单更新为[赎回失败]失败', $orderModel->getErrors());

         return true;
     }

     /**
      * 更新订单为已赎回，已消单
      * @param $sn
      * @param $customerId
      * @return bool
      */
     public function orderRedeemDone($sn, $customerId)
     {
         $criteria = new CDbCriteria;
         $criteria->condition = "sn=:sn";
         $criteria->params = array(':sn'=>$sn);
         $orderModel = PropertyActivity::model()->find($criteria);

         if ( !$orderModel )
             return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

         if (Item::PROFIT_ORDER_REDEEM_DONE == $orderModel->status)
             return true;

         if (Item::PROFIT_ORDER_REDEEM_SUCCESS != $orderModel->status)
             return $this->sendErrorMessage(false, 0, '订单禁止修改为[已赎回，已消单]状态', '订单禁止修改为[已赎回，已消单]状态');

         if ($orderModel->customer_id != $customerId)
             return $this->sendErrorMessage(false, 0, '订单所属者错误', '订单所属者错误');

         $orderModel->status = Item::PROFIT_ORDER_REDEEM_DONE;
         //$orderModel->cash_result_time = $cashResultTime;
         $orderModel->update_time = time();

         if ( ! $orderModel->save() )
             return $this->sendErrorMessage(false, 0, '订单更新为[已赎回，待消单]失败', $orderModel->getErrors());

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
    	$orderModel = PropertyActivity::model()->find($criteria);
    	 
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

    /**
     * 获取板块提示tips
     * @param $sectionCode
     * @param $customerId
     * @return array
     */
    public function getTips($sectionCode, $customerId)
    {
        $result = array(
            'image' => '',
            'url' => ''
        );

        // TODO 保险免单弹窗 活动到期删除
        /**
        if (101 == $sectionCode) {
            $insureFree = InsureFree::model()->find('customer_id=:customer_id AND status=0', array(':customer_id'=>$customerId));
            if ($insureFree) {
                $secret = 'SD&^)#@LDCsrS';
                $userID = $customerId * 778 + 1778;
                $para = array(
                    'user_id' => $userID,
                    'request_time' => time()
                );
                $sign = new Sign($secret);
                $para['sign'] = $sign->makeSign($para);
                $url = F::getMUrl('/Insure/Home') . '?' . $sign->createLinkString($para);


                $res = new PublicFunV23();
                return array(
                    'image' => $res->setAbleUploadImg('v30/tips/profit@insure.png'),
                    'url'   => $url
                );
            }
        }
         **/

        $tipModel = HomeConfigTips::model()->find('section_code=:section_code AND state=:state', array(':section_code'=>$sectionCode, ':state'=>1));
        if (!$tipModel)
            return $result;

        $count = HomeConfigTipsLog::model()->count('tip_id=:tip_id AND customer_id=:customer_id', array(':tip_id'=>$tipModel->id, ':customer_id'=>$customerId));
        if ($count >= $tipModel->times)
            return $result;

        $result['image'] = $tipModel->getImgUrl();

        $tipsLogModel = new HomeConfigTipsLog();
        $tipsLogModel->tip_id = $tipModel->id;
        $tipsLogModel->customer_id = $customerId;
        $tipsLogModel->create_time = time();
        $tipsLogModel->save();

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


    public function showData($communityIds)
    {
        $beginTime = mktime(0,0,0);
        $endTime = mktime(23,59,59);

        $connection = Yii::app()->db;
        // 停车
        $modelName = 'ParkingFeesMonth';

        $sql = 'SELECT `r`.`community_id`, COUNT(`m`.`id`) AS `number`, SUM(`m`.`amount`) AS `amount`, SUM(`m`.`increase_amount`) AS `increase_amount`
                  FROM `property_activity` AS `m`
                  INNER JOIN `parking_fees_month` AS `r` ON (`m`.`object_id`=`r`.`id`)
                  WHERE
			        `m`.`status`>1
			        AND `m`.`status`<>98
			        AND `model`=:modelName
			        AND `m`.`pay_time` BETWEEN :begintime AND :endtime
			        AND `r`.`community_id` IN ('.implode(',', $communityIds).')';


        $command = $connection->createCommand($sql);
        $command->bindParam(':begintime', $beginTime, PDO::PARAM_INT);
        $command->bindParam(':endtime', $endTime, PDO::PARAM_INT);
        $command->bindParam(':modelName', $modelName, PDO::PARAM_STR);

        $result = $command->queryRow();
        if ($result) {
            $data[] = array(
                'profit_type' => 101,
                'number' => $result['number'],
                'amount' => $result['amount'] + $result['increase_amount'],
            );
        }

        unset($modelName);
        unset($command);
        unset($result);

        // 冲抵欠费
        $modelName = 'PropertyFees';

        $sql = 'SELECT `r`.`community_id`, COUNT(`m`.`id`) AS `number`, SUM(`m`.`amount`) AS `amount`, SUM(`m`.`increase_amount`) AS `increase_amount`
                  FROM `property_activity` AS `m`
                  INNER JOIN `property_fees` AS `r` ON (`m`.`object_id`=`r`.`id`)
                  WHERE
			        `m`.`status`>1
			        AND `m`.`status`<>98
			        AND `model`=:modelName
			        AND `m`.`pay_time` BETWEEN :begintime AND :endtime
			        AND `r`.`community_id` IN ('.implode(',', $communityIds).')';

        $command = $connection->createCommand($sql);
        $command->bindParam(':begintime', $beginTime, PDO::PARAM_INT);
        $command->bindParam(':endtime', $endTime, PDO::PARAM_INT);
        $command->bindParam(':modelName', $modelName, PDO::PARAM_STR);

        $result = $command->queryRow();
        if ($result) {
            $data[] = array(
                'profit_type' => 102,
                'number' => $result['number'],
                'amount' => $result['amount'] + $result['increase_amount'],
            );
        }

        unset($modelName);
        unset($command);
        unset($result);

        // 冲抵预缴费
        $modelName = 'PropertyActivity';

        $sql = 'SELECT `r`.`community_id`, COUNT(`m`.`id`) AS `number`, SUM(`m`.`amount`) AS `amount`, SUM(`m`.`increase_amount`) AS `increase_amount`
                  FROM `property_activity` AS `m`
                  INNER JOIN `advance_fee` AS `r` ON (`m`.`object_id`=`r`.`id`)
                  WHERE
			        `m`.`status`>1
			        AND `m`.`status`<>98
			        AND `model`=:modelName
			        AND `m`.`pay_time` BETWEEN :begintime AND :endtime
			        AND `r`.`community_id` IN ('.implode(',', $communityIds).')';

        $command = $connection->createCommand($sql);
        $command->bindParam(':begintime', $beginTime, PDO::PARAM_INT);
        $command->bindParam(':endtime', $endTime, PDO::PARAM_INT);
        $command->bindParam(':modelName', $modelName, PDO::PARAM_STR);

        $result = $command->queryRow();
        if ($result) {
            $data[] = array(
                'profit_type' => 103,
                'number' => $result['number'],
                'amount' => $result['amount'] + $result['increase_amount'],
            );
        }

        unset($modelName);
        unset($command);
        unset($result);

        // 增值计划
        $sql = 'SELECT `r`.`community_id`, COUNT(`m`.`id`) AS `number`, SUM(`m`.`amount`) AS `amount`
                  FROM `appreciation_plan` AS `m`
                  INNER JOIN `advance_fee` AS `r` ON (`m`.`object_id`=`r`.`id`)
                  WHERE
			        `m`.`status`>1
			        AND `m`.`status`<>98
			        AND `m`.`pay_time` BETWEEN :begintime AND :endtime
			        AND `r`.`community_id` IN ('.implode(',', $communityIds).')';

        $command = $connection->createCommand($sql);
        $command->bindParam(':begintime', $beginTime, PDO::PARAM_INT);
        $command->bindParam(':endtime', $endTime, PDO::PARAM_INT);

        $result = $command->queryRow();
        if ($result) {
            $data[] = array(
                'profit_type' => 104,
                'number' => $result['number'],
                'amount' => $result['amount'] + 0,
            );
        }

        unset($command);
        unset($result);

        return $data;
    }
}
