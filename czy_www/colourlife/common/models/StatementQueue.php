<?php
/**
 * This is the model class for table "statement_queue".
 *
 * The followings are the available columns in table 'statement_queue':
 * @property integer $id
 * @property string $model
 * @property integer $object_id
 * @property string $order_model
 * @property integer $order_id
 * @property integer $statement_id
 * @property string $amount
 * @property integer $create_time
 */

class StatementQueue extends CActiveRecord
{
    public $modelName = '结算报表详情';

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'statement_queue';
    }

    public function rules()
    {
        return array(
            array('model,order_model,object_id, order_id', 'required'),
            array('object_id, order_id, statement_id, create_time', 'numerical', 'integerOnly' => true),
            array('order_model', 'length', 'max' => 255),
            array('amount', 'length', 'max' => 10),
            array('id, model, object_id, order_model, order_id, statement_id, amount, create_time', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'orderModel' => array(self::BELONGS_TO, 'Order', 'order_id'), //业主加盟订单
            'othersFeesModel' => array(self::BELONGS_TO, 'OthersFees', 'order_id'), //物业停车费用
            'purchaseReturnModel' => array(self::BELONGS_TO, 'PurchaseReturn', 'order_id'), //加盟退货信息
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'model' => 'Model',
            'object_id' => 'Object',
            'order_model' => 'Order Model',
            'order_id' => 'Order',
            'statement_id' => 'Statement',
            'amount' => 'Amount',
            'create_time' => 'Create Time',
        );
    }

    public function getOrderSn()
    {
        return empty($this->orderModel) ? '' : $this->orderModel->sn;
    }

    public function getOrderSeller()
    {
        return empty($this->orderModel) ? '' : $this->orderModel->seller->name;
    }

    public function getOrderSupplier()
    {
        return empty($this->orderModel) ? '' : $this->orderModel->supplier->name;
    }

    public function getOrderAmount()
    {
        return empty($this->orderModel) ? '' : $this->orderModel->amount;
    }

    public function getOrderBuyer()
    {
        return empty($this->orderModel) ? '' : $this->orderModel->seller_buyer->name;
    }

    public function getFeesAmount()
    {
        return empty($this->othersFeesModel) ? '' : $this->othersFeesModel->amount;
    }

    public function getFeesCustomer()
    {
        return empty($this->othersFeesModel->customer) ? '' : $this->othersFeesModel->customer->name;
    }

    public function getFeesSn()
    {
        return empty($this->othersFeesModel) ? '' : $this->othersFeesModel->sn;
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('model', $this->model, true);
        $criteria->compare('object_id', $this->object_id);
        $criteria->compare('order_model', $this->order_model, true);
        $criteria->compare('order_id', $this->order_id);
        $criteria->compare('statement_id', $this->statement_id);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('create_time', $this->create_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    private function createStatementQueue($modelName, $object_id, $order_model, $order_id, $amount)
    {
        $model = new self;
        $model->model = $modelName;
        $model->order_id = intval($order_id);
        $model->order_model = $order_model;
        $model->object_id = intval($object_id);
        $model->amount = $amount;

        return $model->save();
    }

    /*
     * @操作商家订单支入支出
     * $object_id为商家id
     * $order_id为订单id
     * $amount 为金额，正数为支入，负数为支出
     */
    private function createShopOrderStatementQueue($object_id, $order_id, $amount)
    {
        return $this->createStatementQueue('shop', $object_id, 'order', $order_id, $amount);
    }

    /*
     * @操作商家退货单支入支出
     * $object_id为商家id
     * $order_id为订单id
     * $amount 为金额，正数为支入，负数为支出
     */
    private function createShopPurchaseReturnStatementQueue($object_id, $order_id, $amount)
    {
        return $this->createStatementQueue('shop', $object_id, 'order', $order_id, $amount);
    }

    /*
     * @操作部门停车费支入支出
     * $object_id为商家id
     * $order_id为订单id
     * $amount 为金额，正数为支入，负数为支出
     */
    private function createBranchParkingStatementQueue($object_id, $order_id, $amount)
    {
        return $this->createStatementQueue('branch', $object_id, 'othersParkingFees', $order_id, $amount);
    }

    /*
     * @操作部门物业费支入支出
     * $object_id为商家id
     * $order_id为订单id
     * $amount 为金额，正数为支入，负数为支出
     */
    private function createBranchPropertyStatementQueue($object_id, $order_id, $amount)
    {
        return $this->createStatementQueue('branch', $object_id, 'othersPropertyFees', $order_id, $amount);
    }

    //插入支入支出记录
    static public function createStatementRecord($type = 'shopOrder', $object_id, $order_id, $amount)
    {
        $object_id = intval($object_id);
        $order_id = intval($order_id);
        $model = new self;
        switch ($type) {
            case 'shopOrder':
                return $model->createShopOrderStatementQueue($object_id, $order_id, $amount);
                break;
            case 'shopPurchaseReturn':
                return $model->createShopPurchaseReturnStatementQueue($object_id, $order_id, $amount);
                break;
            case 'branchProperty':
                return $model->createBranchPropertyStatementQueue($object_id, $order_id, $amount);
                break;
            case 'branchParking':
                return $model->createBranchParkingStatementQueue($object_id, $order_id, $amount);
                break;
            default:
                return $model->createShopOrderStatementQueue($object_id, $order_id, $amount);
                break;
        }
        return false;
    }

    //修改结算报表ID
    static public function addStatementId($_statementQueue_id, $statement_id)
    {
        $model = new self;
        return $model->updateByPk($_statementQueue_id, array(
            'statement_id' => $statement_id
        ));
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

}
