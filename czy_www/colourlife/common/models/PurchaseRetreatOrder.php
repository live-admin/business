<?php

/**
 * This is the model class for table "purchase_retreat_order".
 *
 * The followings are the available columns in table 'purchase_retreat_order':
 * @property integer $id
 * @property integer $type
 * @property string $sn
 * @property string $order_sn
 * @property integer $seller_id
 * @property integer $buyer_id
 * @property string $amount
 * @property string $bank_pay
 * @property integer $status
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $return_express_name
 * @property string $return_express_sn
 * @property string $note
 * @property Employee   $buyerInfo
 * @property Shop   $sellerInfo
 * @property Shop   $seller
 * @property PurchaseOrder  $orderInfo
 */
class PurchaseRetreatOrder extends CActiveRecord
{
    public $modelName = '内部采购退货订单';
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

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PurchaseRetreatOrder the static model class
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
        return 'purchase_retreat_order';
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
            array('type, seller_id, buyer_id, status, create_time, update_time, community_id', 'numerical', 'integerOnly' => true),
            array('sn, order_sn', 'length', 'max' => 32),
            array('amount, bank_pay', 'length', 'max' => 10),
            array('create_ip', 'length', 'max' => 50),
            array('return_express_name, return_express_sn', 'length', 'max' => 64),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, type, sn, order_sn, seller_id, buyer_id, amount, bank_pay, status, create_time, create_ip, update_time, return_express_name, return_express_sn, note, community_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            //通过卖家ID关联商家表获得卖家记录
            "sellerInfo" => array(self::BELONGS_TO, 'Shop', 'seller_id'),
            //退货表里的buyer_id只能是员工
            "buyerInfo" => array(self::BELONGS_TO, 'Employee', 'buyer_id'),
            'good_list' => array(self::HAS_MANY, 'PurchaseRetreatOrderGoodsRelation', 'retreat_id'),
            'logs' => array(self::HAS_MANY, 'PurchaseRetreatOrderLog', 'retreat_id'),
            'seller' => array(self::BELONGS_TO, 'Shop', 'seller_id'),
            'order' => array(self::BELONGS_TO, 'PurchaseOrder', 'id'),
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
            'start_time' => "开始时间",
            'end_time' => "结束时间",
            'disposal_desc' => '备注/说明',
            'region' => '地区',
            'community' => '小区',
            'branch_id' => '管辖部门',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

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
                'defaultOrder' => '`t`.update_time DESC, t.create_time DESC', //设置默认排序是create_time倒序
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
        $order = PurchaseOrder::model()->findByAttributes(array('sn' => $this->order_sn));
        return $order;
    }

    //获得订单下未退货的未被锁定的商品
    public function getNormalGoods()
    {
        $criteria = new CDbCriteria();
        $criteria->compare('order_id', $this->id);
        $criteria->compare('is_lock', Item::GOODS_UNLOCK); //未锁定的
        $criteria->compare('state', 0); //必须是正常的。1=》已退货的
        return OrderGoodsRelation::model()->findAll($criteria);
    }

    /**
     * @param $goodIds 商品ID可传数组
     * @return bool
     */
    public function checkGoodsCanReturn($goodIds)
    {
        if (!is_array($goodIds)) {
            $goodIds = array($goodIds);
        }
        foreach ($goodIds as $id) {
            $ogRelation = OrderGoodsRelation::model()->findByAttributes(
                array('goods_id' => $id, 'order_id' => $this->id)
            );
            if ($ogRelation->is_lock == Item::GOODS_LOCK or $ogRelation->state == 1) {
                return false;
            }
        }
        return true;
    }

    //物业后台业主订单点击“已退款”时创建退款订单
    static public function createCwyRefundOrder($order_id, $note)
    {
        $order = PurchaseOrder::model()->findByPk($order_id);
        if (empty($order)) throw new CHttpException(400, "未知的订单");
        if (PurchaseOrderStatus::getNextStatus($order->status, "cwy")) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $updateLock = PurchaseOrder::model()->updateByPk($order_id, array("is_lock" => Item::CUSTOMER_ORDER_LOCK));
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
                        PurchaseRetreatOrderLog::createOrderLog($model->id, $model->status, $note);
                        PurchaseOrderLog::createOrderLog($model->id, $model->status, $note);
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
    static public function createRefundOrder($order_id, $note)
    {
        $order = PurchaseOrder::model()->findByPk($order_id);
        if (empty($order)) throw new CHttpException(400, "未知的订单");
        if (($order->status == Item::ORDER_PURCHASE_GOODS || $order->status == Item::ORDER_PURCHASE_CONFIRM) && $order->is_lock == Item::CUSTOMER_ORDER_UNLOCK) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $updateLock = PurchaseOrder::model()->updateByPk($order_id, array("is_lock" => Item::CUSTOMER_ORDER_LOCK));
                if ($updateLock) {
                    $model = new self();
                    $model->attributes = $order->getAttributes();
                    $model->sn = SN::initByCancelOrder()->getSN();
                    $model->order_sn = $order->sn;
                    $model->status = Item::RETREAT_ORDER_BUYER_APPLY;
                    $model->note = $note;
                    $model->seller_id = $order->shop_id;
                    $model->buyer_id = $order->employee_id;

                    if ($model->save()) {
                        foreach ($order->good_list as $goods) {
                            $relation = new PurchaseRetreatOrderGoodsRelation();
                            $relation->attributes = $goods->getAttributes();
                            $relation->retreat_id = $model->id;
                            $relation->save();
                        }
                        PurchaseRetreatOrderLog::createOrderLog($model->id, $model->status, $note);
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
    static public function createReturnOrder($order_id, $goodIds, $note)
    {
        $order = PurchaseOrder::model()->findByPk($order_id);
        if (empty($order)) throw new CHttpException(400, "未知的订单");
        if (!$order->checkGoodsCanReturn($goodIds)) throw new CHttpException(400, "退货商品存在已退货/正在退货的商品.退货失败");
        $goodIds = is_array($goodIds) ? $goodIds : array($goodIds);
        //如果订单状态是已收货且订单未锁定。那么可以创建退货订单
        if ($order->status == Item::ORDER_PURCHASE_TRANSACTION_SUCCESS && $order->is_lock == Item::CUSTOMER_ORDER_UNLOCK) {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                //锁定商品
                $criteria = new CDbCriteria();
                $criteria->compare('order_id', $order_id);
                $criteria->addInCondition('goods_id', $goodIds);
                $count = PurchaseOrderGoodsRelation::model()->updateAll(array('is_lock' => Item::GOODS_LOCK), $criteria);
                if ($count) {
                    //如果该订单下的商品不存在正常未锁定的商品。那么同时锁定该订单
                    if (count($order->getNormalGoods()) <= 0) {
                        PurchaseOrder::model()->updateByPk($order_id, array("is_lock" => Item::CUSTOMER_ORDER_LOCK));
                    }
                    $model = new self();
                    $model->attributes = $order->getAttributes();
                    $model->type = 1; //退货订单
                    $model->status = Item::RETREAT_ORDER_BUYER_APPLY; //退货审核
                    $model->sn = SN::initByReturnOrder()->getSN();
                    $model->order_sn = $order->sn;
                    $model->note = $note;
                    $model->seller_id = $order->shop_id;
                    $model->buyer_id = $order->employee_id;
                    $model->note = $note;
                    //获得要退货的商品总数
                    $goodsNum = 0;
                    $amount = 0;
                    foreach ($goodIds as $goods_id) {
                        $ogRelation = PurchaseOrderGoodsRelation::model()->findByAttributes(
                            array('goods_id' => $goods_id, 'order_id' => $order->id)
                        );
                        //获得商品数目
                        $goodsNum += $ogRelation->count;
                        //保存选中商品的价格
                        $amount += $ogRelation->bank_pay;
                    }
                    //退货订单的红包总额=红包额度*商品数目
                    $model->amount = $amount;
                    $model->bank_pay = $amount;
                    if ($model->save()) {
                        foreach ($goodIds as $id) {
                            $ogRelation = PurchaseOrderGoodsRelation::model()->findByAttributes(
                                array('goods_id' => $id, 'order_id' => $order->id)
                            );
                            $relation = new PurchaseRetreatOrderGoodsRelation();
                            $relation->attributes = $ogRelation->getAttributes();
                            $relation->retreat_id = $model->id;
                            $relation->save();
                        }
                        PurchaseRetreatOrderLog::createOrderLog($model->id, $model->status, $note);
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
        $orderModel = PurchaseOrder::model()->findByAttributes(array('sn' => $this->order_sn));
        $this->status = Item::RETREAT_ORDER_REFUND_SUCCESS;
        /**########start 判断是否已经把商品全部退货###########*/
        $count = 0; //已进行退货数量
        $realtionCount = (int)PurchaseRetreatOrderGoodsRelation::model()->count('retreat_id = ' . $this->id); //当前退货单 有多少个商品进行退货
        if ($retreat = self::model()->findAllByAttributes(array('order_sn' => $this->order_sn, 'status' => Item::RETREAT_ORDER_REFUND_SUCCESS))) { //旧的成功退货单
            foreach ($retreat as $val) {
                $count += (int)PurchaseRetreatOrderGoodsRelation::model()->count('retreat_id = ' . $val->id);
            }
        }
        $orderGoodsCount = (int)PurchaseOrderGoodsRelation::model()->count('order_id = ' . $orderModel->id);
        if ($orderGoodsCount == ($realtionCount + $count)) { //如果已经退款就把单锁住
            $orderModel->is_lock = Item::CUSTOMER_ORDER_LOCK;
            $orderModel->status = Item::ORDER_PURCHASE_SUCCESS_CLOSE;
        } else {
            $orderModel->is_lock = Item::CUSTOMER_ORDER_UNLOCK;
            $orderModel->status = Item::ORDER_PURCHASE_TRANSACTION_SUCCESS;
        }
        /**########end 判断是否已经把商品全部退货###########*/

        /*$orderModel->status=Item::ORDER_PURCHASE_SUCCESS_CLOSE;
        $orderModel->is_lock=Item::CUSTOMER_ORDER_LOCK;*/
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $integration = $this->getOrderAllIntegral(); //取得订单所有的积分
            if ($integration > 0) { //退货/退款订单里使用了红包金额。那么需要退红包
                $model = Employee::model()->findByPk($this->buyer_id, '', array(), false);
                $sn = $this->order_sn;
                $note = '内部采购订单【'.$sn.'】退款获得积分：'.$integration;
                if (!$model->addIntegration($sn, $integration,$note)) {
                    return false;
                }
            }
            if ($this->save() && $orderModel->save()) {
                PurchaseRetreatOrderLog::createOrderLog($this->id, $this->status, $note);
                PurchaseOrderLog::createOrderLog($orderModel->id, $orderModel->status, $note);
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


    //查询订单商品的积分总数
    public function getOrderAllIntegral()
    {
        $discount = 0;
        if (!$this->good_list)
            return $discount;

        foreach ($this->good_list as $goods) {
            $discount += $goods->integral;
        }
        return intval($discount);
    }

    public function getBuyerAccount()
    {
        return !empty($this->buyerInfo) ? $this->buyerInfo->username : '';
    }
}