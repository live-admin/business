<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/4/20
 * Time: 18:21
 */
class ProfitController extends ActivityController
{

    public $secret = 'sidDIX382';
    public $beginTime = '2016-07-07';
    public $endTime = '2016-07-17 23:59:59';

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            //'Validity',
            'signAuth',
        );
    }

    /**
     * @throws CException
     * 活动规则：
        1、仅限活动期间内新用户可获得赠送饭票。
        2、活动结束后在7个工作日内饭票发放到新用户彩之云账户。
        3、本活动与其他活动不能同时参与。
        4、如提前赎回需要从客户本金中按奖励金额扣除。
        5、数量有限，送完即止。
     */
    public function actionJuly()
    {
        $this->renderPartial('/v2016/profit/July');
    }

    public function  actionAjaxJuly()
    {
        $customerID = $this->getUserId();
        // 判断是否是新用户
//        $customer = Customer::model()->findByPk($customerID);
//        if ($customer->create_time < strtotime($this->beginTime) || $customer->create_time > strtotime($this->endTime))
//            $this->output('', 0, '活动期间注册用户并购买彩富人生才能打开!');

//        // 判断是否购买过彩富
//        $criteria = new CDbCriteria;
//        $criteria->addCondition('customer_id='.$customerID);
//        $criteria->addCondition('status='.Item::PROFIT_ORDER_SUCCESS);
//
//        $orderModel = PropertyActivity::model()->find($criteria);

        $result = ProfitActivity::model()->find('customer_id='.$customerID);
        if ($result)
            $this->output(array('value' => intval($result->value)));

        $this->output('', 0, '活动期间注册用户并购买彩富人生才能打开!');
    }
}