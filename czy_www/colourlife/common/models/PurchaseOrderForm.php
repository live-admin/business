<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class PurchaseOrderForm extends CFormModel
{
    public $buyer_address;
    public $buyer_tel;
    public $buyer_name;
    public $buyer_postcode;
    public $comment;
    public $params = array();
    public $type;
    public $address_id;
    public $receipt_type;
    public $invoice_title;
    public $fapiao;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return array(
            array('type, fapiao', 'required'),
            array('type', 'boolean'),
            array('address_id, receipt_type', 'numerical', 'integerOnly' => true),
            array('address_id, receipt_type', 'checkAddress'),
            array('params', 'checkOrderParams', 'on' => 'createOrder'),
            array('buyer_postcode, invoice_title, buyer_address', 'safe'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'buyer_name' => '收货人',
            'buyer_address' => '收货地址',
            'count' => '数量',
            'buyer_tel' => '收货电话',
            'amount' => ' 总金额',
            'buyer_postcode' => '收货邮编',
           'comment' => '留言',
            'type' => '采购方式不能为空',
            'address_id' => '收货信息',
            'invoice_title' => '发票抬头',
            'receipt_type' => '发票类型'
        );
    }

    public function checkAddress()
    {
        if(!$this->hasErrors()){
            if(!$model = DeliveryAddress::model()->findByPk($this->address_id)){
                $this->addError('address_id', '收货人信息不存在！');
            }
            if($this->fapiao){
                switch($this->receipt_type)
                {
                    case 1:
                    case 2:
                        if(empty($this->invoice_title)){
                            $this->addError('invoice_title', '发票抬头不能为空');
                        }
                        break;
                    default:
                        break;
                }
            }
            else{
                $this->receipt_type = 0;
            }
        }
    }

    protected  function afterValidate()
    {
        if(!$this->hasErrors()){
            /**
             * @var DeliveryAddress $model
             */
            $model = DeliveryAddress::model()->findByPk($this->address_id);
            $this->buyer_name = $model->name;
            $this->buyer_address = $model->getDetailedAddress();
            $this->buyer_postcode = $model->postal_code;
            $this->buyer_tel = $model->mobile;
        }
        parent::afterValidate();
    }
    /**
     * @return array
     * 创建业主订单，返回sn集合
     */
    public function createOrder(){
        $snList = array();
        $transaction = Yii::app()->db->beginTransaction(); //创建事务
        $is_commit = true;
        foreach($this->params as $orderParams){
            $order = new PurchaseOrder();
            //从表单获得一部分订单内容
            $order->attributes = $this->attributes;
            //从购物车获得一部分订单数据
            $order->attributes = $orderParams['param'];
            $order->employee_id = Yii::app()->user->id;
            $order->sn = SN::initByPurchaseOrder()->getSN();
            /*$order->validate();
            dump($order->getErrors());exit;*/
            if($order->save()){
                foreach($orderParams['relation'] as $goodInfo){
                    $ogRelation = new PurchaseOrderGoodsRelation();
                    $ogRelation->attributes = $goodInfo;
                    $ogRelation->order_id = $order->id;
                    //添加订单商品的关联
                    if($ogRelation->save()){
                        //添加周销量，抑制错误，添加销量失败不回滚事务
                        @PurchaseGoods::addWeekSales($goodInfo['goods_id'], $goodInfo['count']);
                        //添加总销量
                        @PurchaseGoods::addSales($goodInfo['goods_id'], $goodInfo['count']);
                    }else{
                        $is_commit = false;
                        Yii::log("保存订单-商品关联失败，创建业主订单失败,Error:".json_encode($ogRelation->getErrors()),
                            CLogger::LEVEL_ERROR,'colourlife.core.order.CreatePurchaseOrder');
                    }
                    //写订单日志
                    $note = $order->buyer_name . '向' . $order->seller_contact . " 下单" . ' date:' . date('Y-m-d H:i:s', time());
                    if (!PurchaseOrderLog::createOrderLog($order->id, Item::ORDER_AWAITING_PAYMENT, $note)) {
                        $is_commit = false;
                        Yii::log("写订单日志失败，创建业主订单失败！",CLogger::LEVEL_ERROR,'colourlife.core.order.CreatePurchaseOrder');
                    }
                    //出现了错误，结束循环
                    if(!$is_commit)break;
                }
            }else{
                Yii::log("创建业主订单失败！Error:".json_encode($order->getErrors()),
                    CLogger::LEVEL_ERROR,'colourlife.core.order.CreatePurchaseOrder');
            }
            //出现了错误，结束循环
            if(!$is_commit)break;
            //没有问题则保存sn
            $snList[] = $order->sn;
        }

        if($is_commit){
            $transaction->commit();
        }else{
            $transaction->rollback();
        }
        return $snList;
    }

    /**
     * 在创建业主订单前验证相关信息
     * @return bool
     */
    public function checkOrderParams(){
        if (empty($this->params)) {
            $this->addError('error',"订单商品相关信息不能为空！");
            return false;
        }
        foreach($this->params as $paramList){
            //验证订单的信息和关联商品的信息是否存在
            if(!isset($paramList['relation']) or !isset($paramList['param']))continue;
            if(empty($paramList['relation']) or empty($paramList['param']))continue;
            //循环每个订单的关联商品
            foreach($paramList['relation'] as $goods){
                $goodInfo = PurchaseGoods::model()->findByPk($goods['goods_id']);
                if($goodInfo->is_on_sale!=PurchaseGoods::SALE_YES){
                    $this->addError('error',"订单内存在已下架商品，创建订单失败！");
                    return false;
                }
            }
        }

        return true;
    }

}