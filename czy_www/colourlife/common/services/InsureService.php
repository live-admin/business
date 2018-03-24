<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/6/7
 * Time: 10:55
 */
Yii::import('common.services.BaseService');
class InsureService extends BaseService {

    // 保险公司标示
    const INSURE_CODE_TAIKANG = 100; //泰康

    // 逻辑状态码
    const RECODE_TRUE = 1;
    const RECODE_FALSE = 0;

    // 订单状态
    const ORDER_STATUS_INIT = 0; // 订单初始状态
    const ORDER_STATUS_PAYED = 1; // 已支付
    const ORDER_STATUS_ = 2;  // 交易成功，已出保单

    /**
     * 保险类别
     * @return array
     */
    public function getCategoryList()
    {
        $sql = 'SELECT * FROM `insure_category` WHERE `status`='.self::RECODE_TRUE.' ORDER BY `order` ASC';

        $list = Yii::app()->db->createCommand($sql)->queryAll();

        $result = array();
        if ( ! $list)
            return $result;

        foreach ($list as $row) {
            $result[] = array(
                'categoryId' => $row['id'],
                'name' => $row['name'],
                'month' => $row['insure_month'],
                'fee' => $row['fee'],
                'vipFee' => $row['vip_fee'],
                'sum' => $row['insure_sum'],
                'desc' => $row['desc']
            );
        }

        return $result;
    }

    /**
     * 保险详情
     * @param $categoryId
     * @return array
     */
    public function getCategoryInfo($categoryId)
    {
        $model = InsureCategory::model()->findByPk($categoryId);
        if ( !$model)
            return $this->sendErrorMessage(false, 0, '保险类型不存在', '保险类型不存在');

        $result = array(
            'categoryId' => $model->id,
            'insureCode' => $model->insure_code,
            'name' => $model->name,
            'month' => $model->insure_month,
            'fee' => $model->fee,
            'vipFee' => $model->vip_fee,
            'sum' => $model->insure_sum,
            'desc' => $model->desc
        );

        return $result;
    }

    /**
     * 获取用户下单价格
     * @param $customerId
     * @return int  0 原价 1 vip价  2 免单
     */
    public function getUserInsurePrice($customerId)
    {
        $customerId = intval($customerId);

        $ret = 0;

        // 1.是否有免单 //
        $sql = 'SELECT `id` FROM `insure_free` WHERE `status`='.self::RECODE_FALSE.' AND `customer_id`='.$customerId;
        $result = Yii::app()->db->createCommand($sql)->queryRow();

        if ($result)
            return $ret = 2;

        // 2.是否是彩富用户 //

        // 彩富
        $inStatus = array(
            Item::PROFIT_ORDER_SUCCESS,
            Item::PROFIT_ORDER_CONTINUOUS,
            Item::PROFIT_ORDER_EXTRACT_ING,
            Item::PROFIT_ORDER_EXTRACT_SUCCESS,
            Item::PROFIT_ORDER_EXTRACT_FAIL
        );

        $criteria = new CDbCriteria;
        $criteria->addCondition('customer_id='.$customerId);
        $criteria->addInCondition('status', $inStatus);

        $model = PropertyActivity::model()->find($criteria);

        if ($model)
            return $ret = 1;

        // 增值计划
        $model = AppreciationPlan::model()->find($criteria);

        if ($model)
            return $ret = 1;

        return $ret;
    }

    /**
     * 获取地址
     * @param int $insureCode
     * @param $addressType
     * @param int $parentAddressID
     * @return array
     */
    public function getAddress($insureCode=self::INSURE_CODE_TAIKANG, $addressType, $parentAddressID=0)
    {
        if (!in_array($addressType, array('province', 'city', 'area')))
            return $this->sendErrorMessage(false, 0, '地址类型参数错误', '地址类型参数错误');

        $addressList = array();
        if (self::INSURE_CODE_TAIKANG == $insureCode) {
            $addressList = $this->getTaikangAddressList();
            if (false === $addressList)
                return $addressList;
        }

        $result = array();
        if ('province' == $addressType) {
            $result['province'] = $addressList['province'];

            if (0 == $parentAddressID)
                $parentAddressID = $addressList['province'][0]['key'];
            $result['city'] = $addressList['city'][$parentAddressID];

            $defaultParentCityCode = $result['city'][0]['key'];
            $result['area'] = $addressList['area'][$defaultParentCityCode];
        }
        else if ('city' == $addressType) {
            $result['area'] = $addressList['area'][$parentAddressID];
        }

        return $result;
    }

    /**
     * 获取泰康地址列表
     * @return array
     * @throws CException
     */
    protected function getTaikangAddressList()
    {
        $cacheKey = 'TaikangAddress';
        $address = Yii::app()->cache->get($cacheKey);
        if ( $address )
            return $address;

        Yii::import('common.api.TaikangApi');
        $list = TaikangApi::getInstance()->address();

        if ( !$list)
            return $this->sendErrorMessage(false, 0, '获取泰康保险地址失败', '获取泰康保险地址失败');

        $address = array(
            'province' => array(),
            'city' => array(),
            'area' => array()
        );

        foreach ($list as $row) {
            if ('01' == $row['addressType'])
                $address['province'][] = array('key' => $row['addressCode'], 'value' => $row['addressName']);

            if ('02' == $row['addressType'])
                $address['city'][$row['parentAddressID']][] = array('key' => $row['addressCode'], 'value' => $row['addressName']);

            if ('03' == $row['addressType'])
                $address['area'][$row['parentAddressID']][] = array('key' => $row['addressCode'], 'value' => $row['addressName']);
        }

        Yii::app()->cache->set($cacheKey, $address, 86400);

        return $address;
    }

    public function createOrder( $customerId,
                                 $categoryId,
                                 array $applicantInfo,
                                 array $insuredInfo,
                                 array $insureSubjectInfo,
                                 $insuredAddress,
                                 $detailedAddress,
                                 array $itemPropertyInfo)
    {
        // 保险类型详情
        $insureInfo = $this->getCategoryInfo($categoryId);
        if (false == $insureInfo)
            return false;

        // 保险下单
        $sn = SN::initByInsure()->sn;

        Yii::import('common.api.TaikangApi');
        $result = TaikangApi::getInstance()->confirmOrder($applicantInfo, $insuredInfo, $insureSubjectInfo, $insuredAddress, $detailedAddress, $itemPropertyInfo, $sn);
        if (false === $result)
            return $this->sendErrorMessage(false, 0, '泰康确认保单失败', '泰康确认保单失败');

        $insurePriceRet = $this->getUserInsurePrice($customerId);

        $amount = $insureInfo['fee'];
        if (2 == $insurePriceRet)
            $amount = 0;
        if (1 == $insurePriceRet)
            $amount = $insureInfo['vipFee'];

        $orderFees = new InsureOrder();
        $orderFees->sn = $sn;
        $orderFees->customer_id = $customerId;
        $orderFees->amount = $amount;
        $orderFees->category_id = $categoryId;
        $orderFees->insure_code = $insureInfo['insureCode'];
        $orderFees->create_time = time();
        $orderFees->create_ip = $_SERVER['REMOTE_ADDR'];

        if (0 == $amount)
            $orderFees->status = Item::INSURE_ORDER_PAYED;

        $insuredObj = new InsureInfo();
        $insuredObj->trade_id = $result['tradeId'];
        $insuredObj->proposal_no = $result['proposalNo'];
        $insuredObj->identity_no = $applicantInfo['identifyNumber'];
        $insuredObj->insure_sum = $insureInfo['sum'];
        $insuredObj->address = $insuredAddress;
        $insuredObj->detail_address = $detailedAddress;
        $insuredObj->applicant_info = json_encode($applicantInfo);
        $insuredObj->insure_info = json_encode($insuredInfo);
        $insuredObj->insure_subject_info = json_encode($insureSubjectInfo);
        $insuredObj->insure_subject_info = json_encode($insureSubjectInfo);
        $insuredObj->property_info = json_encode($itemPropertyInfo);
        $insuredObj->receive_confirm = json_encode($result);
        $insuredObj->create_time = time();

        if ($insuredObj->save()) {
            $orderFees->object_id = $insuredObj->id;
            if ($orderFees->save()) {

                // 如果已付款直接出保单
                if (Item::INSURE_ORDER_PAYED == $orderFees->status)
                    $this->updateOrderStatusSuccess($orderFees->sn);

                return $orderFees;
            } else {
                $oldRe = InsureInfo::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $insuredObj->id)));
                $oldRe->delete();
                return $this->sendErrorMessage(false, 0, '下单失败', $orderFees->getErrors());
            }
        }
        else {
            return $this->sendErrorMessage(false, 0, '下单失败', $insuredObj->getErrors());
        }
    }

    /**
     * 获取证件类别
     * @param int $insureCode
     * @param string $identifyCode
     * @return array|string
     */
    public function getIdentifyType($insureCode=self::INSURE_CODE_TAIKANG, $identifyCode='')
    {
        $_identifyTypeTk = array(
            '01' => '居民身份证',
            '02' => '护照',
            '03' => '军官证',
            '05' => '港澳同胞证'
        );

        if (self::INSURE_CODE_TAIKANG == $insureCode) {
            if ('' == $identifyCode)
                return $_identifyTypeTk;

            return isset($_identifyTypeTk[$identifyCode]) ? $_identifyTypeTk[$identifyCode] : '';
        }


        return $this->sendErrorMessage(false, 0, '保险商家CODE错误', '保险商家CODE错误');
    }

    /**
     * 获取保险人关系
     * @param int $insureCode
     * @param string $personCode
     * @return array|string
     */
    public function getRelatedPerson($insureCode=self::INSURE_CODE_TAIKANG, $personCode='')
    {
        $_relatedPersonTk = array(
            '0'  => '本人',
            '1'  => '配偶',
            '2'  => '子女',
            '13' => '父母'
        );

        if (self::INSURE_CODE_TAIKANG == $insureCode) {
            if ('' === $personCode)
                return $_relatedPersonTk;

            return isset($_relatedPersonTk[$personCode]) ? $_relatedPersonTk[$personCode] : '';
        }


        return $this->sendErrorMessage(false, 0, '保险人关系CODE错误', '保险人关系CODE错误');
    }

    /**
     * 获取订单状态
     * @param string $statusCode
     * @return array|string
     */
    public function getOrderStatus($statusCode='')
    {
        $_orderStatus = array(
            Item::INSURE_ORDER_INIT => '待付款',
            Item::INSURE_ORDER_PAYED => '已付款',
            Item::INSURE_ORDER_SUCCESS => '交易成功',
        );

        if ('' === $statusCode)
            return $_orderStatus;

        return isset($_orderStatus[$statusCode]) ? $_orderStatus[$statusCode] : '';
    }

    /**
     * 获取订单详情
     * @param $sn
     * @return array
     */
    public function getOrder($sn)
    {
        $model = InsureOrder::model()->find('sn=:sn', array(':sn' => $sn));
        if ( ! $model)
            return $this->sendErrorMessage(false, 0, '订单不存在', '['.$sn.']订单不存在');

        $applicantInfo = json_decode($model->insureInfo->applicant_info, true);
        $applicantInfo['identifyTypeName'] = $this->getIdentifyType($model->insure_code, $applicantInfo['identifyType']);

        $insureInfo = json_decode($model->insureInfo->insure_info, true);
        $insureInfo['identifyTypeName'] = $this->getIdentifyType($model->insure_code, $insureInfo['identifyType']);
        $insureInfo['relatedPersonName'] = $this->getRelatedPerson($model->insure_code, $insureInfo['relatedperson']);

        $result = array(
            'sn' => $model->sn,
            'categoryId' => $model->insureCategory->id,
            'categoryName' => $model->insureCategory->name,
            'startDate' => date("Y-m-d 00:00:00",strtotime("+1 day")),
            'endDate' => date("Y-m-d 23:59:59",strtotime("+1 year")),
            'amount' => $model->amount,
            'applicantInfo' => $applicantInfo,
            'insureInfo' => $insureInfo,
            'address' => $model->insureInfo->address,
            'detailAddress' => $model->insureInfo->detail_address,
            'statusStr' => $this->getOrderStatus($model->status),
            'status' => $model->status,
        );

        return $result;
    }

    public function getOrderList($customerId, $status=Item::INSURE_ORDER_SUCCESS)
    {
        $list = InsureOrder::model()->findAll('customer_id=:customer_id AND status=:status', array(':customer_id' => $customerId, ':status'=>Item::INSURE_ORDER_SUCCESS));
        $result = array();
        foreach ($list as $model) {

            $applicantInfo = json_decode($model->insureInfo->applicant_info, true);
            $insureInfo = json_decode($model->insureInfo->insure_info, true);
            $result[] = array(
                'categoryId' => $model->insureCategory->id,
                'categoryName' =>  $model->insureCategory->name,
                'startDate' => $model->insureInfo->start_time ? date("Y-m-d H:i:s", $model->insureInfo->start_time) : date("Y-m-d 00:00:00",strtotime("+1 day")),
                'endDate' => $model->insureInfo->end_time ? date("Y-m-d H:i:s", $model->insureInfo->end_time) : date("Y-m-d 23:59:59",strtotime("+1 year")),
                'applicantInfo' => $applicantInfo,
                'insureInfo' => $insureInfo,
                'status' => $this->getOrderStatus($model->status),
                'policyNo' => $model->status == Item::INSURE_ORDER_SUCCESS ? $model->insureInfo->policy_no : '--',
                'address' => $model->insureInfo->address.$model->insureInfo->detail_address
            );
        }

        return $result;
    }

    /**
     * 保险出单
     * @param $sn
     * @param $role
     * @return bool
     * @throws CException
     */
    public function updateOrderStatusSuccess($sn, $role='')
    {
        $model = InsureOrder::model()->find('sn=:sn', array(':sn' => $sn));
        if ( ! $model)
            return $this->sendErrorMessage(false, 0, '订单不存在', '['.$sn.']订单不存在');

        if ('admin' != $role && Item::INSURE_ORDER_PAYED != $model->status)
            return $this->sendErrorMessage(false, 0, '订单未付款', '订单未付款');

        $identityNo = $model->insureInfo->identity_no;
        $proposalNo = $model->insureInfo->proposal_no;
        $tradeId = $model->insureInfo->trade_id;
        $outTradeId = $model->sn;

        // 更新Free表
        if (0 === intval($model->amount)) {
            $freeModel =InsureFree::model()->find('customer_id=:customer_id AND status=:status', array(':customer_id'=>$model->customer_id, ':status'=>self::RECODE_FALSE));
            if ($freeModel) {
                $freeModel->relation_id = $model->sn;
                $freeModel->status = self::RECODE_TRUE;
                $freeModel->save();
            }
        }

        Yii::import('common.api.TaikangApi');
        $result = TaikangApi::getInstance()->makeOrder($identityNo, $proposalNo, $tradeId, $outTradeId, $model->sn);
        if (false === $result)
            return $this->sendErrorMessage(false, 0, '泰康请求出单失败', '泰康请求出单失败');

        $model->insureInfo->policy_no = $result['policyNo'];
        $model->insureInfo->start_time = intval($result['startDate'] / 1000);
        $model->insureInfo->end_time = intval($result['endDate'] / 1000);

        if (!$model->insureInfo->update())
            return $this->sendErrorMessage(false, 0, '数据库异常', $model->insureInfo->getErrors());

        $model->status = Item::INSURE_ORDER_SUCCESS;
        if ('admin' == $role)
            $model->remark = '管理员修改状态[交易成功]';

        if (!$model->save())
            return $this->sendErrorMessage(false, 0, '数据库异常', $model->getErrors());

        return true;
    }

    /**
     * 规则 1 每天前x名且金额满y元
     * 规则 2 订单满x元
     * @param $customerId
     * @param $sn
     * @param $price
     * @param $payTime
     * @return mixed
     */
    public function profitActivity($customerId, $sn, $price, $payTime)
    {
        $startTime = strtotime('2016-07-20 00:00:00');
        $endTime = strtotime('2016-08-19 23:00:00');

        if ($payTime < $startTime || $payTime > $endTime)
            return $this->sendErrorMessage(false, 0, '不在活动时间范围类', '不在活动时间范围类');

        $minPrice = 10000;  // 最低赠送价格
        if ($price < $minPrice)
            return $this->sendErrorMessage(false, 0, '未达到活动价格', '未达到活动价格');

        $result = InsureFree::model()->find('source_id=:sn', array(':sn'=>$sn));
        if ($result)
            return $this->sendErrorMessage(false, 0, '重复数据', '重复数据');

        $freePrice = 100000; // 免单价格
        if ($price > $freePrice)
            return $this->createFree($customerId, $sn, 1);

        // update 2016-7-20
        //$maxNum = 5; // 每天前多少名
        //$date = date('Y-m-d', $payTime);
        //$todayNum = InsureFree::model()->count('type=:type AND date=:date', array(':type' => 0, ':date'=>$date));
        //if ($todayNum >= $maxNum)
        //    return $this->sendErrorMessage(false, 0, '当天人数已上限', '当天人数已上限');

        //return $this->createFree($customerId, $sn, 0, $date);
    }

    /**
     * 添加免单记录
     * @param $customerId
     * @param $sn
     * @param $type
     * @param string $date
     * @return bool
     */
    protected function createFree($customerId, $sn, $type, $date='')
    {
        $model = new InsureFree();
        $model->customer_id = $customerId;
        $model->type = $type;
        $model->date = $date;
        $model->source_id = $sn;
        $model->create_time = time();

        if ( !$model->save())
            return $this->sendErrorMessage(false, 0, '数据库异常', $model->getErrors());

        return true;
    }

}
