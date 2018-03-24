<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/4/28
 * Time: 15:40
 */
Yii::import('common.services.BaseService');
class PropertyService extends BaseService
{
    /**
     * 创单
     * @param $saveData
     * @return array
     */
    public function createOrder($saveData)
    {
        $sn = SN::initByPropertyFees()->sn;
        $orderFees = new OthersFees();
        $orderFees->customer_id = $saveData['customer_id'];
        $orderFees->sn = $sn;
        $orderFees->model = 'PropertyFees';
        $orderFees->payment_id = 0;
        $orderFees->amount = $saveData['amount'];
        $orderFees->bank_pay = $orderFees->amount;
        $orderFees->red_packet_pay = '0.00';
        $orderFees->note = $saveData['note'];
        $orderFees->open_id = $saveData['open_id'];
        $orderFees->source = $saveData['source'];

        $orderCheck = $orderFees->checkOrderFees();
        if ($orderCheck['result']) {
            $PropertyFees = new PropertyFees();
            $PropertyFees->community_id = $saveData['community_id'];
            $PropertyFees->build = $saveData['building'];
            $PropertyFees->colorcloud_building = $saveData['building_id'];
            $PropertyFees->room = $saveData['room'];
            $PropertyFees->colorcloud_unit = $saveData['room_id'];
            $PropertyFees->colorcloud_bills = $saveData['bills'];
            $PropertyFees->colorcloud_order = $saveData['color_cloud_order'];

            if ($PropertyFees->save()) {
                $orderFees->object_id = $PropertyFees->id;
                if ($orderFees->save()) {
                    return array(
                        'sn' => $orderFees->sn,
                    );
                } else {
                    $oldRe = PropertyFees::model()->find(array('condition' => 'id=:id', 'params' => array(':id' => $PropertyFees->id)));
                    $oldRe->delete();
                    return $this->sendErrorMessage(false, 0, '创建订单失败master', json_encode($orderFees->getErrors()));
                }
            } else {
                return $this->sendErrorMessage(false, 0, '创建订单失败origin', json_encode($PropertyFees->getErrors()));
            }
        }

        return $this->sendErrorMessage(false, 0, '创建订单失败', $orderCheck['error']);
    }

    /**
     * 获取物业费系统订单信息
     * @param $communityCode
     * @param $buildingId
     * @param $roomId
     * @param $billsStr
     * @return mixed
     * @throws CException
     */
    public function getPropertyOrder($communityCode, $buildingId, $roomId, $billsStr)
    {
        Yii::import('common.api.ColorCloudApi');
        $result = ColorCloudApi::getInstance()->callGetOrderCreate($communityCode, $buildingId, $roomId, $billsStr, '');
        if (!isset($result) || $result['data'][0]['state'] != 1)
            return $this->sendErrorMessage(false, 0, '创建物业系统订单失败', $result['data'][0]['msg']);
        if($result['data'][0]['orderamount'] <= 0)
            return $this->sendErrorMessage(false, 0, '创建物业系统订单失败', '彩之云收费系统异常');

        return $result['data'];
    }

    /**
     * 通知物业系统消单
     * @param $propertyOrder
     * @param $amount
     * @param $sn
     * @param $paymentNames
     * @param $bankPay
     * @param $redPacketPay
     * @return bool
     * @throws CException
     */
    public function notifyPropertySystemPaySuccess($propertyOrder, $amount, $sn, $paymentNames, $bankPay, $redPacketPay, $uuid='')
    {
        $switch = 0;
//        if(isset(Yii::app()->config->IceSwitch)){
//            $config = Yii::app()->config->IceSwitch;//1启用0禁用
//            $switch = $config==1 ? 1 : 0;
//        }

        if ($switch && $uuid) {
            Yii::import('common.api.IceApi');
            $result = IceApi::getInstance()->callGetPayOrder(
                $uuid,
                $propertyOrder,
                $amount,
                '彩生活交易号：' . $sn,
                $paymentNames,
                $bankPay,
                $redPacketPay
            );
        }
        else {
            Yii::import('common.api.ColorCloudApi');
            $result = ColorCloudApi::getInstance()->callGetPayOrder(
                $propertyOrder,
                $amount,
                '彩生活交易号：' . $sn,
                $paymentNames,
                $bankPay,
                $redPacketPay
            );
        }

        if (!isset($result) || $result['total'] <= 0) //调用接口修改状态成功
            return $this->sendErrorMessage(false, 0, '通知物业消单失败', '通知物业消单失败');

        return true;
    }

    /**
     * 更新订单状态为支付成功
     * @param $sn
     * @param $paymentId
     * @param $payTime
     * @return bool
     */
    public function updateOrderStatusPaySuccess($sn, $paymentId, $payTime)
    {
        $orderModel = OthersFees::model()->find('sn=:sn', array(':sn'=>$sn));
        if (!$orderModel)
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::ORDER_AWAITING_GOODS == $orderModel->status)
            return true;

        if (Item::ORDER_AWAITING_PAYMENT != $orderModel->status)
            return $this->sendErrorMessage(false, 0, '订单状态拒绝修改为[付款成功]', '订单状态拒绝修改为[付款成功]');

        $orderModel->status = Item::ORDER_AWAITING_GOODS;
        $orderModel->pay_time = $payTime;
        $orderModel->payment_id = $paymentId;

        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '更新订单状态[付款成功]失败', $orderModel->getErrors());

        return true;
    }

    /**
     * 更新订单状态为交易成功
     * @param $sn
     * @return bool
     */
    public function updateOrderStatusTransactionSuccess($sn)
    {
        $orderModel = OthersFees::model()->find('sn=:sn', array(':sn'=>$sn));
        if (!$orderModel)
            return $this->sendErrorMessage(false, 0, '订单不存在', '订单不存在');

        if (Item::FEES_TRANSACTION_SUCCESS == $orderModel->status)
            return true;

        if ( ! in_array($orderModel->status, array(Item::ORDER_AWAITING_GOODS, Item::FEES_TRANSACTION_FAIL)))
            return $this->sendErrorMessage(false, 0, '订单状态拒绝修改为[交易成功]', '订单状态拒绝修改为[交易成功]');

        $result = $this->notifyPropertySystemPaySuccess(
            $orderModel->PropertyFees->colorcloud_order,
            $orderModel->amount,
            $orderModel->sn,
            $orderModel->payment->name,
            $orderModel->bank_pay,
            $orderModel->red_packet_pay
        );

        if (false === $result)
            return false;

        $orderModel->status = Item::FEES_TRANSACTION_SUCCESS;
        if ( ! $orderModel->save() )
            return $this->sendErrorMessage(false, 0, '更新订单状态[交易成功]失败', $orderModel->getErrors());

        return true;
    }

    /**
     * 获取默认缴费地址
     * @param $communityId
     * @param $openId
     * @return array
     */
    public function getPropertyDefaultUnit($communityId, $openId)
    {

        $sql = 'SELECT colorcloud_name FROM colorcloud_community where community_id=:community_id';

        $result = array(
            'build' => '',
            'room' => '',
            'colorcloud_building' => '',
            'colorcloud_unit' => '',
        );

        $sql = 'SELECT p.build,p.room,p.colorcloud_building,p.colorcloud_unit FROM others_fees as t INNER JOIN property_fees as p ON t.object_id=p.id WHERE t.open_id=:open_id AND p.community_id=:community_id ORDER BY t.id DESC';

        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(':open_id', $openId, PDO::PARAM_STR);
        $command->bindParam(':community_id', $communityId, PDO::PARAM_STR);

        $data = $command->queryRow();
        if ($data)
            $result = $data;

        return $result;
    }

}