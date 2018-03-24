<?php

/**
 * This is the model class for table "purchase_return".
 *
 * The followings are the available columns in table 'purchase_return':
 * @property integer $id
 * @property integer $seller_id
 * @property integer $buyer_id
 * @property string $reason
 * @property integer $order_id
 * @property integer $pay_id
 * @property integer $status
 * @property integer $create_time
 */
class PurchaseReturn extends CActiveRecord
{
    const STATUS_DISABLE = 2;
    const STATUS_ENABLE = 1;
    const STATUS_WAITING = 0;
    public $sellerName;
    public $buyerName;
    public $branch_id;
    public $disposal_desc;
    public $order_sn;

    static $return_status = array(
        self::STATUS_DISABLE => "未同意退货",
        self::STATUS_ENABLE => "同意退货",
        self::STATUS_WAITING => "等待处理",
    );

    public $order_goods = array();

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'purchase_return';
    }

    public function rules()
    {
        return array(
            array('order_goods, reason', 'required', 'on' => 'create'),
            array('order_goods', 'type', 'type' => 'array', 'on' => 'create'),
            array('seller_id, buyer_id, order_id, pay_id, status, create_time', 'numerical', 'integerOnly' => true),
            array('reason', 'length', 'max' => 500),
            array('order_goods', 'checkOrderGoods', 'on' => 'create'),
            array('order_goods', 'checkOrderGoodsNum', 'on' => 'create'),
            array('order_goods', 'checkGoods', 'on' => 'create'),
            array('id, order_sn, seller_id, buyer_id, reason, order_id, pay_id, status, branch_id,create_time,sellerName,buyerName', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'goods' => array(self::HAS_MANY, 'PurchaseReturnGoods', 'return_id'),
            // 'amount' => array(self::STAT, 'PurchaseReturnGoods', 'return_id', 'select' => 'sum(`goods_price`)'),
            'logs' => array(self::HAS_MANY, 'PurchaseReturnLog', 'return_id'),
            'seller' => array(self::BELONGS_TO, 'Shop', 'seller_id'),
            'buyer' => array(self::BELONGS_TO, 'Shop', 'buyer_id'),
            'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'seller_id' => 'Seller',
            'buyer_id' => 'Buyer',
            'reason' => '退货原因',
            'order_id' => '订单ID',
            'order_sn' => '订单号',
            'pay_id' => 'Pay',
            'status' => '状态',
            'create_time' => '创建时间',
            'amount' => '商品价格',
            'sellerName' => '供应商',
            'buyerName' => '加盟商',
            'goods_num' => '商品数量',
            'branch_id' => '管辖部门',
            'disposal_desc' => '备注/说明',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $employee = Employee::model()->findByPk(Yii::app()->user->id);

        //  $criteria->compare('id', $this->id);


        if (!empty($this->sellerName)) {
            $criteria->with[] = 'seller';
            $criteria->compare('seller.name', $this->sellerName, true);
        }
        if (!empty($this->buyerName)) {
            $criteria->with[] = 'buyer';
            $criteria->compare('buyer.name', $this->buyerName, true);
        }
        if (!empty($this->order_sn)) {
            $criteria->with[] = 'order';
            $criteria->compare('order.sn', $this->order_sn, true);
        }
        if (Yii::app()->user->checkAccess('is_goods_supplier')) {
            $criteria->compare('`t`.seller_id', Yii::app()->user->id);
            $criteria->compare('`t`.buyer_id', $this->buyer_id);
        } elseif (Yii::app()->user->checkAccess('is_goods_seller')) {
            $criteria->compare('`t`.seller_id', $this->seller_id);
            $criteria->compare('`t`.buyer_id', Yii::app()->user->id);
        } else {
            //选择的组织架构ID
            if (empty($this->sellerName))//重新加载seller
                $criteria->with[] = 'seller';

            if ($this->branch_id != '')
                $criteria->addInCondition('seller.branch_id', Branch::model()->findByPk($this->branch_id)->getChildrenIdsAndSelf());
            else //自己的组织架构的ID
                $criteria->addInCondition('seller.branch_id', $employee->getBranchIds());
            // $criteria->addInCondition('seller.branch_id', $employee->getBranchIds());

            $criteria->compare('`t`.seller_id', $this->seller_id);
            $criteria->compare('`t`.buyer_id', $this->buyer_id);
        }

        $criteria->compare('`t`.reason', $this->reason, true);
        $criteria->compare('`t`.order_id', $this->order_id);
        $criteria->compare('`t`.pay_id', $this->pay_id);
        $criteria->compare('`t`.status', $this->status);
        $criteria->compare('`t`.create_time', $this->create_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    //踢除为0的商品
    private function getOrderGoods()
    {
        if (count($this->order_goods) > 0) {
            foreach ($this->order_goods as $goods_id => $goods_num) {
                if ($goods_num == 0) {
                    unset($this->order_goods[$goods_id]);
                }
            }
        }
    }

    //得到订单退货商品的价格
    private function getOrderGoodsAmount()
    {
        return Order::model()->find('id=' . $this->order_id, array('select' => 'amount'))->amount;
    }

    //通过要退货的商品获得退货单的总价
    private function getPurchaseReturnGoodsAmount()
    {
        $amount = 0;
        if (count($this->order_goods) > 0) {
            foreach ($this->order_goods as $goods_id => $goods_num) {
                $price = OrderGoodsRelation::model()->find(' order_id= ' . $this->order_id . ' and goods_id= ' . $goods_id)->price;
                $price = empty($price) ? 0 : $price;
                $amount += $price * $goods_num;
            }
        }
        return $amount;
    }

    /*
     * 系统新建加盟订单不需要写入关联商品表
     */
    public function createSysPurchaseReturn($order_id)
    {
        //取订单信息
        $order_info = Order::model()->findByPk(intval($order_id));
        $this->seller_id = $order_info->seller_id;
        $this->buyer_id = $order_info->buyer_id;
        $this->order_id = $order_info->id;
        $this->status = Item::SELLER_ORDER_AWAITING_REFUND;
        $this->reason = '系统处理';
        $this->amount = $this->getOrderGoodsAmount();

        if (!$this->save())
            return false;

        if (!$this->addPurchaseReturnGoods())
            return false;

        if (!$this->addPurchaseReturnLog("系统为加盟商申请加盟退货"))
            return false;

        StatementQueue::createStatementRecord('shopPurchaseReturn', $this->buyer_id, $order_id, $this->amount);
        StatementQueue::createStatementRecord('shopPurchaseReturn', $this->seller_id, $order_id, (0 - ($this->amount)));

        return true;
    }

    //保存商品退货信息
    private function addPurchaseReturnGoods()
    {
        $this->getOrderGoods();
        if (count($this->order_goods) > 0) {
            foreach ($this->order_goods as $goods_id => $goods_num) {
                $order_goods = OrderGoodsRelation::model()->find("order_id = '$this->order_id' and goods_id='$goods_id'");

                $model = new PurchaseReturnGoods;
                $model->goods_name = $order_goods->name;
                $model->goods_id = $goods_id;
                $model->goods_num = $goods_num;
                $model->goods_price = $order_goods->price;
                $model->user_id = Yii::app()->user->id;
                $model->return_id = $this->id;

                if (!$model->save()) {
                    return false;
                }
            }
        }
        return true;
    }

    //写入退货日志
    public function addPurchaseReturnLog($note = '', $user_model = 'shop', $status = 0)
    {
        $model = new PurchaseReturnLog;
        $model->return_id = $this->id;
        $model->user_model = $user_model;
        $model->user_id = Yii::app()->user->id;
        $model->status = $status;
        $model->note = $note;
        if ($model->validate() && !$model->save()) {
            return false;
        }
        return true;
    }

    //保存信息
    public function saveAll()
    {
        if (!$this->validate())
            return false;

        //取订单信息
        $order_info = Order::model()->findByPk(intval($this->order_id));
        $this->seller_id = $order_info->seller_id;
        $this->buyer_id = $order_info->buyer_id;

        //算出退货的总价格
        $this->amount = $this->getPurchaseReturnGoodsAmount();

        if (!$this->save())
            return false;

        if (!$this->addPurchaseReturnGoods())
            return false;

        if (!$this->addPurchaseReturnLog("加盟商申请加盟退货"))
            return false;

        return true;
    }

    public function checkOrderGoodsNum($attribute, $params)
    {
        foreach ($this->order_goods as $val) {
            if (!preg_match('/^\s*\d+\s*$/', $val)) {
                $this->addError($attribute, '申请退货商品数量必须为整数');
                break;
            }
        }
    }

    //检查没有填写商品的情况
    public function checkGoods($attribute, $params)
    {
        $totalNum = 0;
        if (count($this->order_goods) > 0) {
            foreach ($this->order_goods as $goods_id => $goods_num) {
                $totalNum += $goods_num;
            }
        }
        if ($totalNum == 0) {
            $this->addError($attribute, '申请退货没有商品，无效申请');
        }
    }

    public function checkOrderGoods($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $order_goods = Order::model()->getGoodsByOrder($this->order_id);
            if (count($order_goods) > 0) {
                foreach ($order_goods as $goods) {
                    if ($goods->count < $this->order_goods[$goods->goods_id]) {
                        $this->addError($attribute, '申请退货商品' . $goods->name . '数量不能大于订单数量' . $goods->count . '件');
                        break;
                    }
                }
            }
        }
    }

    public function getPurchaseReturnAmount()
    {
        return empty($this->amount) ? 0 : $this->amount;
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function getStatusNames()
    {
        return CMap::mergeArray(array('' => '全部'), OrderStatus::getPurchaseStatusNames());
    }

    public function getStatusName($html = 'false')
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= OrderStatus::purchaseStatusName($this->status);
        $return .= ($html) ? '</span>' : '';
        return $return;
    }

    /*
     * 获取商家名字
     * $type 商家类型，加盟商家为seller_id,供应商家为supplier_id
     * */
    public function getSellerName($type)
    {
        return empty($this->$type) ? "" : CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' => '电话:' . $this->$type->tel . ', 手机:' . $this->$type->mobile . ', 所属部门:' . Branch::getMyParentBranchName($this->$type->branch_id, true)), $this->$type->name);
    }

    /* public function getSellerName($type)
     {
         $sellerId=isset($this->order)?$this->order->$type:"0";
         if($sellerId){
             $sellerName="";
             $model=Shop::model()->findByPk($sellerId);
             if($model){
                 $sellerName.=$model->name;
                 return CHtml::tag('a', array('rel' => 'tooltip','href'=>'javascript:void();', 'data-original-title' => '电话:' . $model->tel . ', 手机:' . $model->mobile .', 所属部门:'.Branch::getMyParentBranchName($model->branch_id,true)), $model->name);
             }else{
                 return "";
             }
         }else{
             return "";
         }
     }*/

    /*
     * 获取商品退货数量
     * */
    public function getReturnNum()
    {
        if (!empty($this->goods)) {
            $number = 0;
            foreach ($this->goods as $num) {
                $number += $num->goods_num;
            }
            return $number;
        } else {
            return "0";
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
                    $model->order_id = $order_id;
                    $model->seller_id = $order->seller_id;
                    $model->buyer_id = $order->buyer_id;
                    $model->pay_id = $order->pay_id;
                    $model->status = Item::SELLER_ORDER_APPLY_BACK;
                    $model->reason = $note;
                    $model->amount = $order->getGoodsAmount($goodIds);
                    if ($model->save()) {
                        foreach ($goodIds as $id) {
                            $ogRelation = OrderGoodsRelation::model()->findByAttributes(
                                array('goods_id' => $id, 'order_id' => $order->id)
                            );
                            $relation = new PurchaseReturnGoods();
                            $relation->attributes = $ogRelation->getAttributes();
                            $relation->return_id = $model->id;
                            $relation->goods_name = $ogRelation->name;
                            $relation->goods_num = $ogRelation->count;
                            $relation->goods_id = $ogRelation->goods_id;
                            $relation->goods_price = $ogRelation->price;
                            $relation->amount=$ogRelation->amount;
                            $relation->bank_pay=$ogRelation->bank_pay;
                            $relation->save();
                        }
                        if (!$model->addPurchaseReturnLog("加盟商申请加盟退货,备注：" . $note)) {
                            $transaction->rollback();
                            return false;
                        }
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
                var_dump($e);
                $transaction->rollback();
                var_dump($e);
                exit;
                return false;
            }
        } else {
            return false;
        }
    }

}
