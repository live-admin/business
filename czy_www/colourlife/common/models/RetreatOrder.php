<?php

/**
 * This is the model class for table "retreat_order".
 *
 * The followings are the available columns in table 'retreat_order':
 * @property integer $id
 * @property integer $type
 * @property string $sn
 * @property string $order_sn
 * @property integer $seller_id
 * @property integer $buyer_id
 * @property string $amount
 * @property string $bank_pay
 * @property string $red_packet_pay
 * @property integer $status
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $return_express_name
 * @property string $return_express_sn
 * @property string $note
 */
class RetreatOrder extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '退货订单';
    public $buyer_name;
    public $start_time;
    public $end_time;
    public $disposal_desc;
    public $buyer_tel;
    public $buyer_address;
    public $community_id;
    public $sellerName;
    public $buyer_model;
    public $payment_id;
    public $customerBuyerInfo;
    public $goods_id;
    public $count;
    public $region;
    public $_supplierName;
    public $goodsName;
    public $categoryByOrder;
    public $buyer_mobile;
    public $income_pay_time;
    public $customer_buyer_name;
    public $remark;
    public $seller_name;
    protected static $oldStatus;
    const RETREAT_ORDER_REFUND = 0; //退款
    const RETREAT_ORDER_GOODS_RETURN = 1; //退货


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return RetreatOrder the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'retreat_order';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('note', 'required'),
            array('type, seller_id, buyer_id,community_id, status, create_time, update_time', 'numerical', 'integerOnly' => true),
            array('sn, order_sn', 'length', 'max' => 32),
            array('amount, bank_pay, red_packet_pay', 'length', 'max' => 10),
            array('create_ip', 'length', 'max' => 50),
            array('return_express_name, return_express_sn', 'length', 'max' => 64),
            array('buyer_name,seller_name,start_time,end_time', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, type, sn, order_sn, seller_id, buyer_id,community_id, amount, bank_pay, red_packet_pay, status, create_time, create_ip, update_time, return_express_name, return_express_sn, note', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            //通过卖家ID关联商家表获得卖家记录
            "sellerInfo" => array(self::BELONGS_TO, 'Shop', 'seller_id'),
            //退货表里的buyer_id只能是业主
            "buyerInfo" => array(self::BELONGS_TO, 'Customer', 'buyer_id'),
            'good_list' => array(self::HAS_MANY, 'RetreatOrderGoodsRelation', 'retreat_id'),
            'logs' => array(self::HAS_MANY, 'RetreatOrderLog', 'retreat_id'),
            'seller' => array(self::BELONGS_TO, 'Shop', 'seller_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'type' => '类型',
            'sn' => '退货单号',
            'order_sn' => '原订单号',
            'seller_id' => '卖家',
            'buyer_id' => '买家',
            'amount' => '总金额',
            'bank_pay' => '实付金额',
            'red_packet_pay' => '红包抵扣金额',
            'status' => '状态',
            'create_time' => '创建时间',
            'create_ip' => '创建IP',
            'update_time' => '最后更新时间',
            'return_express_name' => '退货快递公司',
            'return_express_sn' => '退货快递单号',
            'note' => '退货原因',
            'remark' => '备注',
            'buyer_name' => "买家",
            'seller_name' => '卖家',
            'start_time' => "开始时间",
            'end_time' => "结束时间",
            'disposal_desc' => '备注/说明',
            'region' => '地区',
            'community' => '小区',
            'branch_id' => '管辖部门',
            'integral' => '积分抵扣'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('type', $this->type);
        $criteria->compare('sn', $this->sn, true);
        $criteria->compare('order_sn', $this->order_sn, true);
        $criteria->compare('seller_id', $this->seller_id);
        $criteria->compare('buyer_id', $this->buyer_id);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('bank_pay', $this->bank_pay, true);
        $criteria->compare('red_packet_pay', $this->red_packet_pay, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('create_ip', $this->create_ip, true);
        $criteria->compare('update_time', $this->update_time);
        $criteria->compare('return_express_name', $this->return_express_name, true);
        $criteria->compare('return_express_sn', $this->return_express_sn, true);
        $criteria->compare('note', $this->note, true);

        return new CActiveDataProvider($this, array(
            'sort' => array(
                'defaultOrder' => '`t`.update_time DESC', //设置默认排序是create_time倒序
            ),
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function getOrderInfo()
    {
        $order = Order::model()->findByAttributes(array('sn' => $this->order_sn));
        return $order;
    }


    //物业后台业主订单点击“已退款”时创建退款订单

    static public function createCwyRefundOrder($order_id, $note, $user_model = 'employee')
    {
        $order = Order::model()->findByPk($order_id);
        if (empty($order)) throw new CHttpException(400, "未知的订单");
        if (OrderStatus::getNextStatus($order->status, "cwy")) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $updateLock = Order::model()->updateByPk($order_id, array("is_lock" => Item::CUSTOMER_ORDER_LOCK));
                if ($updateLock) {
                    $model = new self();
                    $model->attributes = $order->getAttributes();
                    $model->sn = SN::initByCancelOrder()->getSN();
                    $model->order_sn = $order->sn;
                    $model->status = Item::RETREAT_ORDER_AWAITING_GOODS;
                    $model->note = $note;
                    if ($model->save()) {
                        foreach ($order->good_list as $goods) {
                            $relation = new RetreatOrderGoodsRelation();
                            $relation->attributes = $goods->getAttributes();
                            $relation->retreat_id = $model->id;
                            $relation->save();
                        }
                        RetreatOrderLog::createOrderLog($model->id, $user_model, $model->status, $note);
                        OrderLog::createOrderLog($model->id, $user_model, $model->status, $note);
                        $transaction->commit();
                        return true;
                    } else {
                        $transaction->rollback();
                        return false;
                    }

                } else {
                    $transaction->rollback();
                    return false;
                }
            } catch (Exception $e) {
                $transaction->rollback();
                return false;
            }
        } else {
            throw new CHttpException(400, "创建退款单失败");
        }

    }


    //业主后台创建退款单
    static public function createRefundOrder($order_id, $note = '', $user_model = 'customer')
    {
        $order = Order::model()->findByPk($order_id);
        if (empty($order)) throw new CHttpException(400, "未知的订单");
        if (($order->status == Item::ORDER_AWAITING_GOODS || $order->status == Item::ORDER_AWAITING_CONFIRM) && $order->is_lock == Item::CUSTOMER_ORDER_UNLOCK) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $updateLock = Order::model()->updateByPk($order_id, array("is_lock" => Item::CUSTOMER_ORDER_LOCK));
                if ($updateLock) {
                    $model = new self();
                    $model->attributes = $order->getAttributes();
                    $model->sn = SN::initByCancelOrder()->getSN();
                    $model->order_sn = $order->sn;
                    $model->status = Item::RETREAT_ORDER_BUYER_APPLY;
                    $model->note = $note;
                    if ($model->save()) {
                        foreach ($order->good_list as $goods) {
                            $relation = new RetreatOrderGoodsRelation();
                            $relation->attributes = $goods->getAttributes();
                            $relation->retreat_id = $model->id;
                            $relation->save();
                        }
                        RetreatOrderLog::createOrderLog($model->id, $user_model, $model->status, $note);
                        $transaction->commit();
                        return true;
                    } else {
                        $transaction->rollback();
                        return false;
                    }
                } else {
                    $transaction->rollback();
                    return false;
                }
            } catch (Exception $e) {
                $transaction->rollback();
                return false;
            }
        } else {
            throw new CHttpException(400, "创建退款单失败");
        }

    }

    //获取退款状态名
    public function  getRefundStatusName()
    {
        return RetreatOrderStatus::getRefundStatusName($this->status);
    }

    //下拉单时获取退款状态名
    public static function getRefundStatusNames($select = false)
    {
        return CMap::mergeArray(array('' => '全部'), RetreatOrderStatus::getRefundStatusNames());
    }

    public function getNameHtml()
    {
        return @CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '电话:' . $this->seller->tel . '手机:' . $this->seller->mobile . '地址:' . $this->seller->address), $this->seller->name);
    }

    //获取退款状态名
    public function  getReturnStatusName()
    {
        return RetreatOrderStatus::getReturnStatusName($this->status);
    }

    //下拉单时获取退款状态名
    public static function getReturnStatusNames($select = false)
    {
        return CMap::mergeArray(array('' => '全部'), RetreatOrderStatus::getReturnStatusNames());
    }

    public function getBuyerHtml()
    {
        return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '电话:' . $this->buyerInfo->mobile . '地址:' . $this->buyer_address), $this->buyerInfo->name);
    }

    //后台业主订单买家帐号
    public function getCustomerBuyerAccount()
    {
        if (!empty($this->buyerInfo))
            return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '姓名:' . $this->buyerInfo->name . '电话:' . $this->buyerInfo->mobile .
                '小区:' . $this->buyerInfo->communityRegionName . '地址:' . $this->buyer_address), $this->buyerInfo->username);
        else
            return '';
    }

    public function getBuyerName($buyerModel = null, $buyer_id = '')
    {
        $buyerModel = ucfirst(trim($buyerModel));
        if ($buyerModel == null || $buyer_id == '') {
            return "";
        } else {
            // $model = CActiveRecord::model($buyerModel)->findByPk($buyer_id);
            $model = $buyerModel::model()->findByPk($buyer_id);
            return empty($model) ? '' : (empty($model->name) ? (empty($model->username) ? '' : $model->username) : $model->name);
        }
    }

    public function getCustomerName()
    {
        $span = ' ';
        if (!empty($this->buyerInfo)) {
            $span = CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '姓名:' . $this->buyerInfo->name . '电话:' . $this->buyerInfo->mobile .
                '小区:' . $this->buyerInfo->communityRegionName . '地址:' . $this->buyer_address), $this->buyerInfo->name);
        }
        return CHtml::link($span, '/customer/' . $this->buyer_id, array('target' => '_blank'));
    }

    public function getSellerName()
    {
        return empty($this->sellerInfo) ? '' : $this->sellerInfo->name;
    }

    //根据订单得到付款方式名字
    public function getPaymentNameByOrder($order_id = '')
    {
        if ($order_id == '') {
            return "";
        } else {
            $order = Payment::model()->findByPk($order_id);
            $paymentNames = "";
            $criteria = new CDbCriteria;
            $criteria->select = array('name');
            $criteria->compare('id', $order_id);
            $order = Payment::model()->findAll($criteria);

            foreach ($order as $pay) {
                $paymentNames .= $pay->name . "  ";
            }
            return $paymentNames;
        }
    }

    //创建退货订单
    static public function createReturnOrder($order_id, $goodIds, $note, $user_model = "shop")
    {
        $order = Order::model()->findByPk($order_id);
        if (empty($order)) throw new CHttpException(400, "未知的订单");
        if (!$order->checkGoodsCanReturn($goodIds)) throw new CHttpException(400, "退货商品存在已退货/正在退货的商品.退货失败");
        $goodIds = is_array($goodIds) ? $goodIds : array($goodIds);
        //如果订单状态是已收货且订单未锁定。那么可以创建退货订单
        if ($order->status == Item::ORDER_TRANSACTION_SUCCESS && $order->is_lock == Item::CUSTOMER_ORDER_UNLOCK) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                //锁定商品
                $criteria = new CDbCriteria();
                $criteria->compare('order_id', $order_id);
                $criteria->addInCondition('goods_id', $goodIds);
                $count = OrderGoodsRelation::model()->updateAll(array('is_lock' => Item::GOODS_LOCK), $criteria);
                if ($count) {
                    //如果该订单下的商品不存在正常未锁定的商品。那么同时锁定该订单
                    if (count($order->getNormalGoods()) <= 0) {
                        Order::model()->updateByPk($order_id, array("is_lock" => Item::CUSTOMER_ORDER_LOCK));
                    }
                    $model = new self();
                    $model->attributes = $order->getAttributes();
                    $model->type = 1; //退货订单
                    $model->status = Item::RETREAT_ORDER_BUYER_APPLY; //退货审核
                    $model->sn = SN::initByReturnOrder()->getSN();
                    $model->order_sn = $order->sn;
                    $model->note = $note;
                    //先获得原订单的所有红包总额
                    $red_packet = $order->red_packet_pay;
                    $number = 0; //获得订单下的商品总数
                    foreach ($order->good_list as $goods) {
                        $number += $goods->count;
                    }
                    //以下算法唐静确认的。红包总数/商品总数后保留两位小数。舍去两位以后的小数
                    $packet = $number == 0 ? 0 : floor($red_packet / $number * 100); //舍去法取整
                    $packet = $packet / 100; //获得平均每个商品的红包额度，保留两位小数
                    //获得要退货的商品总数
                    $goodsNum = 0;
                    $amount = 0;
                    foreach ($goodIds as $goods_id) {
                        $ogRelation = OrderGoodsRelation::model()->findByAttributes(
                            array('goods_id' => $goods_id, 'order_id' => $order->id)
                        );
                        //获得商品数目
                        $goodsNum += $ogRelation->count;
                        //保存选中商品的价格
                        $amount += $ogRelation->bank_pay;
                    }
                    //退货订单的红包总额=红包额度*商品数目
                    $model->red_packet_pay = $packet * $goodsNum;
                    $model->amount = $amount;
                    $model->bank_pay = $amount - ($model->red_packet_pay);
                    if ($model->save()) {
                        foreach ($goodIds as $id) {
                            $ogRelation = OrderGoodsRelation::model()->findByAttributes(
                                array('goods_id' => $id, 'order_id' => $order->id)
                            );
                            $relation = new RetreatOrderGoodsRelation();
                            $relation->attributes = $ogRelation->getAttributes();
                            $relation->retreat_id = $model->id;
                            $relation->id = '';
                            $relation->save();
                        }
                        RetreatOrderLog::createOrderLog($model->id, $user_model, $model->status, $note);
                    } else {
                        $transaction->rollback();
                        return false;
                    }
                } else {
                    return false;
                }
                $transaction->commit();
                return true;
            } catch (Exception $e) {
                $transaction->rollback();
                return true;
            }
        } else {
            return false;
        }
    }


    /**
     * @param $note
     * @return bool
     * 退款操作
     */
    public function reimburse($note)
    {
        $orderModel = Order::model()->findByAttributes(array('sn' => $this->order_sn));
        $count = 0; //已进行退货数量
        $realtionCount = (int)RetreatOrderGoodsRelation::model()->count('retreat_id = ' . $this->id); //当前退货单 有多少个商品进行退货
        if ($retreat = self::model()->findAllByAttributes(array('order_sn' => $this->order_sn, 'status' => Item::RETREAT_ORDER_REFUND_SUCCESS))) { //旧的成功退货单
            foreach ($retreat as $val) {
                $count += (int)RetreatOrderGoodsRelation::model()->count('retreat_id = ' . $val->id);
            }
        }
        $orderGoodsCount = (int)OrderGoodsRelation::model()->count('order_id = ' . $orderModel->id);
        $this->status = Item::RETREAT_ORDER_REFUND_SUCCESS;
        if ($orderGoodsCount == ($realtionCount + $count)) { //如果已经退款就把单锁住
            $orderModel->is_lock = Item::CUSTOMER_ORDER_LOCK;
            $orderModel->status = Item::ORDER_TRANSACTION_SUCCESS_CLOSE;
        } else {
            $orderModel->is_lock = Item::CUSTOMER_ORDER_UNLOCK;
            $orderModel->status = Item::ORDER_TRANSACTION_SUCCESS;
        }
        //$orderModel->is_lock=Item::CUSTOMER_ORDER_LOCK;
        $return = true;
        $transaction = Yii::app()->db->beginTransaction();
        try {
            if ($this->red_packet_pay > 0) { //退货/退款订单里使用了红包金额。那么需要退红包
                $redPacket = new RedPacket();
                $items = array(
                    'customer_id' => $this->buyer_id, //业主的ID
                    'sum' => $this->red_packet_pay, //红包金额,
                    'sn' => $this->sn, //订单号(from_type=1or2or3的情况下)
                    'from_type' => Item::RED_PACKET_FROM_TYPE_GOODS, //商品退红包
                );
                $return = $redPacket->addRedPacker($items);
            }
            if (!$return) return false;
            if ($this->save() && $orderModel->save()) {
                RetreatOrderLog::createOrderLog($this->id, "employee", $this->status, $note);
                OrderLog::createOrderLog($orderModel->id, "employee", $orderModel->status, $note);
                $transaction->commit();
                return true;
            } else {
                $transaction->rollback();
                return false;
            }
        } catch (Exception $e) {
            $transaction->rollback();
        }
    }

    protected function beforeSave()
    {
        if ('update' == $this->getScenario()) {
            /**
             * @var RetreatOrder $retreatOrder
             */
            $retreatOrder = self::model()->findByPk($this->id);
            self::$oldStatus = $retreatOrder->status;
        }
        return parent::beforeSave();
    }

    protected function afterSave()
    {
        if ('update' == $this->getScenario()) {
            //订单类型:0=>取消的订单，1=>退货的订单
            $model = PayLib::get_model_by_sn($this->sn); //CustomerRefundOrder
            switch (self::$oldStatus) {
                case Item::RETREAT_ORDER_BUYER_APPLY: //(买家申请退款/退货)[商家]退款/退货审核(通过类型判断是退款还是退货)
                    if (self::RETREAT_ORDER_GOODS_RETURN == $this->type and Item::RETREAT_ORDER_AWAITING_BACK_GOODS == $this->status) { //退货单 商家同意退货
                        $type = 'agreedToReturn';
                        $this->sendSms($type);
                    } elseif (self::RETREAT_ORDER_GOODS_RETURN == $this->type and Item::RETREAT_ORDER_REFUND_REJECT == $this->status) { //退货单 商家拒绝退货
                        $this->sendSms('refusedToReturn');
                    }
                    break;
                case Item::RETREAT_ORDER_AWAITING_GOODS: //(商家/仲裁同意)[平台]待退款
                    break;
                case Item::RETREAT_ORDER_APPLY_ARBITRATE: //退货仲裁(同意=>待发货,不同意=>退货失败,仲裁都是针对是否同意买家)
                    break;
                case Item::RETREAT_ORDER_AWAITING_BACK_GOODS: //(商家同意退货)[买家]待发货
                    break;
                case Item::RETREAT_ORDER_AWAITING_BACK_CONFIRM: //(买家已发货)[商家]待收货
                    if (self::RETREAT_ORDER_GOODS_RETURN == $this->type and Item::RETREAT_ORDER_AWAITING_GOODS) { //退货  商家退货收货
                        $this->sendSms('returnReceipt');
                    }
                    break;
                case Item::RETREAT_ORDER_REFUND_REJECT: //(买家申请退款/退货)[商家]拒绝退款/退货
                    break;
                case Item::RETREAT_ORDER_AWAITING_ARBITRATE: //退款仲裁(同意=>待退款,不同意=>退货/退款失败,仲裁都是针对是否同意买家)
                    break;
                case Item::RETREAT_ORDER_REFUND_FAILED: //退款/退货失败(通过类型判断是退款还是退货)
                    break;
                case Item::RETREAT_ORDER_REFUND_SUCCESS: //(平台结算)退款成功
                    break;
            }
        }
        parent::afterSave();
    }

    protected function sendSms($type, $title = "商品订单")
    {
        /**
         * @var SmsComponent $sms
         * @var Customer $customer
         */
        $sms = Yii::app()->sms;
        $sms->orderModel = get_class($this);
        if ($customer = Customer::model()->findByPk($this->buyer_id)) {
            $sms->mobile = $customer->mobile; //业主手机号码
        }
        $sms->sendCustomerOrderReturnMessage($type, $this->id, $title);
    }

    //查询订单商品的积分抵扣价格
    public function getOrderAllIntegralPrice()
    {
        $discount = 0;
        $price = 0;
        if (!$this->good_list)
            return $discount;

        foreach ($this->good_list as $goods) {
            $discount += $goods->integral;
            $price += $goods->integral_price;
        }
        if ($price == 0) {
            return 0;
        } else {
            return $discount . "分抵扣" . $price . "元";
        }
    }
}