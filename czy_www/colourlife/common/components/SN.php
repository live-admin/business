<?php
/* 9XXX 9开头为第三方下单 xxx为第三方客户号
 * @info 增加105红包充值 redpacket_fees
 * @update 2015-06-03
 * @by wenda
 */
class SN {

    private $community_id = 0;
    //类型
    private $type = 'online_sale';
    private $sn;

    private $types = array(
        array(//物业费
            'pre' => '101',
            'type' => 'property_fee',
            'model' => 'OthersPropertyFees',
        ),
        array(//停车费
            'pre' => '102',
            'type' => 'parking_fee',
            'model' => 'OthersParkingFees',
        ),
        array(//商铺买电
            'pre' => '103',
            'type' => 'power_fees',
            'model' => 'OthersPowerFees',
        ),
        array(//投资E理财减免物业费
            'pre' => '104',
            'type' => 'property_activity',
            'model' => 'PropertyActivity',
        ),
        array(//红包充值
            'pre' => '105',
            'type' => 'redpacket_fees',
            'model' => 'RedpacketFees',
        ),
        array(//彩管家红包提现订单
            'pre' => '106',
            'type' => 'cai_redpacket_tixian',
            'model' => 'CaiRedpacketTixian',
        ),        
        array(//彩管家OA转账订单
            'pre' => '107',
            'type' => 'cai_redpacket_carry',
            'model' => 'CaiRedpacketCarry',
        ),
        array(//投资E理财消费红包订单
            'pre' => '108',
            'type' => 'cai_redpacket_elicai',
            'model' => 'ConductData',
        ),
        array(//接收E理财定期投资提成奖励订单
            'pre' => '109',
            'type' => 'elicai_order_ticheng',
            'model' => 'ElicaiRedpacketTicheng',
        ),
        array(//彩之云饭票转账
            'pre' => '110',
            'type' => 'redpacket_carry',
            'model' => 'RedPacketCarry',
        ),
        array(//接收荣信汇订单
            'pre' => '111',
            'type' => 'rxh_order_info',
            'model' => 'RxhOrder',
        ),
        array(//彩管家投资E理财消费红包订单
            'pre' => '112',
            'type' => 'cai_redpacket_elicai_redeem',
            'model' => 'ConductRedeem',
        ),
        array(//饭票发放后台交易
            'pre' => '113',
            'type' => 'redpacket_internal_transaction',
            'model' => 'RedpacketInternalTransaction',
        ),
        array(//团购
            'pre' => '201',
            'type' => 'tuan',
            'model' => 'Order',
        ),
        array(//抢购
            'pre' => '202',
            'type' => 'qiang_gou',
            'model' => 'Order',
        ),
        array(//在线销售
            'pre' => '203',
            'type' => 'online_sale',
            'model' => 'CustomerOrder',
        ),
        array(//饭票券
            'pre' => '204',
            'type' => 'meal_ticket',
            'model' => 'OthersFees',
        ),
        array(//保险
            'pre' => '205',
            'type' => 'insure',
            'model' => 'InsureOrder',
        ),
        array(//复合公司
            'pre' => '206',
            'type' => 'specialty',
            'model' => 'SpecialtyOrder',
        ),
        array(//加盟商销售
            'pre' => '400',
            'type' => 'seller',
            'model' => 'SellerOrder',
        ),
        array(//电话费
            'pre' => '300',
            'type' => 'virtual_recharge',
            'model' => 'OthersVirtualRecharge',
        ),
        array(//qq充值
            'pre' => '301',
            'type' => 'virtual_recharge',
            'model' => 'OthersVirtualRecharge',
        ),
        array(//游戏充值
            'pre' => '302',
            'type' => 'virtual_recharge',
            'model' => 'OthersVirtualRecharge',
        ),
        array(//预付费
            'pre' => '303',
            'type' => 'advance_fees',
            'model' => 'OthersAdvanceFees',
        ),
        array(//取消的退货单
            'pre' => '501',
            'type' => 'cancel_order',
            'model' => 'RetreatOrder',
        ),
        array(//退货的退货单
            'pre' => '502',
            'type' => 'return_order',
            'model' => 'RetreatOrder',
        ),
        array(//内部采购
            'pre' => '601',
            'type' => 'purchase_order',
            'model' => 'PurchaseOrder',
        ),
        array(//1元购
            'pre' => '801',
            'type' => 'oneYuan',
            'model' => 'CustomerOrder',
        ),
        array(//第三方统一sn号
            'pre' => '9000',
            'type' => 'thirdFees',
            'model' => 'ThirdFees',
        ),
        array(//订单奖励
            'pre' => '701',
            'type' => 'rewards',
            'model' => 'Rewards',
        ),

    );

    //防止用户克隆
    public function __clone() {
        throw new Exception('Cannot clone the SN object.');
    }

    public function __set($property, $value) {
        $this->$property = $value;
    }

    public function __get($property) {
        return $this->$property;
    }

    private function getTypeSnPre() {
        foreach ($this->types as $k => $v) {
            if ($this->type == $v['type'])
                return $this->types[$k]['pre'];
        }
        return '000';
    }

    /*
     * @info 第三方下单
     * 根据订单号查找对应的model层
     * @update 20150409
     */
    private function getTypeSnModel($sn, $column = false) {
        //9开头的单号为第三方商铺，四位开头
        if ($column != false || substr($sn, 0, 1) == 9) return 'ThirdFees';
        $type_id = substr($sn, 0, 3);
        foreach ($this->types as $k => $v) {
            if ($type_id == $v['pre'])
                return $this->types[$k]['model'];
        }
        return 'Order';
    }

    /*
     * 生成一个16位SN
     * @return 头三/四位(新增四位的)+小区（4位不足补零)+年月日时分秒+限时三位数
     */
    public function create_sn() {
        $this->sn = str_pad($this->getTypeSnPre(), 3, '0', STR_PAD_LEFT) .
                str_pad($this->community_id, 4, '0', STR_PAD_LEFT) .
                date('ymd') .
                date('Him') .
                str_pad(F::random(3, 1), 3, '0', STR_PAD_LEFT);

        if (!self::findContentBySN($this->sn))
            return $this->sn;
        else
            return $this->create_sn();
    }

    //生成一个SN $num为前面的几位为7位
    static public function write_create_sn($num) {
        $sn = str_pad($num, 7, '0', STR_PAD_LEFT) .
                date('ymd') .
                date('Him') .
                str_pad(F::random(3, 1), 3, '0', STR_PAD_LEFT);


        return $sn;
    }

    public function getSN() {
        return $this->sn;
    }

    static public function findModelBySN($sn) {
        $model = new self();
        return $model->getTypeSnModel($sn);
    }

    //$column 查找表的字段对像
    static public function findContentBySN($sn, $column = '') {
        $model = new self();
        $model_string = $model->getTypeSnModel($sn, $column);
        if (!$column) $column = 'sn';
        if ($model_string != '') {
            $model = new $model_string();
            //sn号截取处理（用于一张单生成多个支付号：单号+a+N）只要有a字符就进行a截取 20150331
            if (strstr($sn, 'a')) {
                $sn = substr($sn, 0, strpos($sn, 'a'));
            }
            return $model::model()->find($column . '=:sn', array(':sn' => $sn));
        }
        return NULL;
    }

    /*
     * 根据订单号，更新对应的paysn
     */
    static public function updatePaySN($sn,$column='',$pay_sn){
        $model = new self();
        //根据订单号返回所属订单类型
        $model_string = $model->getTypeSnModel($sn,$column);
        if (!$column) $column = 'sn';
        //实例化
        $model = new $model_string();
        //sn号截取处理（用于一张单生成多个支付号：单号+a+N）只要有a字符就进行a截取 20150331
        if (strstr($sn, 'a')) {
            $sn = substr($sn, 0, strpos($sn, 'a'));
        }
        //根据订单号查找表数据
        $orderModel = $model::model()->find($column . '=:sn', array(':sn' => $sn));
        //更新数据
        $orderModel->pay_sn = $pay_sn;
        $count = $orderModel->update(array('pay_sn'));
        return $count;
    }

    /*
     * 根据订单号返回对应的，订单类型；
     */
    static public function orderTypeBySN($sn,$column=''){
        $model = new self();
        $model_string = $model->getTypeSnModel($sn, $column);
        if($model_string == 'ThirdFees'){
            if (!$column) $column = 'sn';
            $model = new $model_string();
            //sn号截取处理（用于一张单生成多个支付号：单号+a+N）只要有a字符就进行a截取 20150331
            if (strstr($sn, 'a')) {
                $sn = substr($sn, 0, strpos($sn, 'a'));
            }
            $orderModel = $model::model()->find($column . '=:sn', array(':sn' => $sn));
            if($orderModel->model == 'thirdFrees13'){
                return 3;
            }elseif ($orderModel->model =='thirdFrees35'){
                return 1;
            }else{
                return 5;
            }
        }
        switch ($model_string){
            case 'OthersParkingFees';
                return 3;
                break;
            case 'OthersPropertyFees';
                return 1;
                break;
            case 'OthersPowerFees';
                return 5;
                break;
            case 'Order';
                return 2;
                break;
            case 'RedpacketFees';
                return 6;
                break;
            default;
                return 5;
        }

        return NULL;
    }

    static public function initBySeller() {
        $model = new self();
        $model->type = 'seller';
        $model->create_sn();
        return $model;
    }

    static public function initByParkingFees() {
        $model = new self();
        $model->type = 'parking_fee';
        $model->create_sn();
        return $model;
    }

    //预付费
    static public function initByAdvanceFees() {
        $model = new self();
        $model->type = 'advance_fees';
        $model->create_sn();
        return $model;
    }

    static public function initByVirtualRecharge() {
        $model = new self();
        $model->type = 'virtual_recharge';
        $model->create_sn();
        return $model;
    }
    // 内部交易
    static public function initByInternalTransaction() {
        $model = new self();
        $model->type = 'redpacket_internal_transaction';
        $model->create_sn();
        return $model;
    }

    static public function initByPropertyFees() {
        $model = new self();
        $model->type = 'property_fee';
        $model->create_sn();
        return $model;
    }

    static public function initByPowerFees() {
        $model = new self();
        $model->type = 'power_fees';
        $model->create_sn();
        return $model;
    }

    static public function initByPropertyActivity() {
        $model = new self();
        $model->type = 'property_activity';
        $model->create_sn();
        return $model;
    }

    static public function initByCaiRedPacketOrder() {
        $model = new self();
        $model->type = 'cai_redpacket_tixian';
        $model->create_sn();
        return $model;
    }

    //彩管家投资E理财
    static public function initByCaiRedPacketForELiCai() {
        $model = new self();
        $model->type = 'cai_redpacket_elicai';
        $model->create_sn();
        return $model;
    }


    //彩管家投资E理财消费红包订单
    static public function initByCaiRedPacketForELiCaiRedeem() {
        $model = new self();
        $model->type = 'cai_redpacket_elicai_redeem';
        $model->create_sn();
        return $model;
    }
    

    //接收E理财定期投资提成奖励订单
    static public function initByEliCaiRedPacketOrderTC() {
        $model = new self();
        $model->type = 'elicai_order_ticheng';
        $model->create_sn();
        return $model;
    }

    //接收荣信汇订单数据信息
    static public function initByRXHOrderInfo() {
        $model = new self();
        $model->type = 'rxh_order_info';
        $model->create_sn();
        return $model;
    }

    static public function initByCaiRedPacketByOAOrder() {
        $model = new self();
        $model->type = 'cai_redpacket_carry';
        $model->create_sn();
        return $model;
    }


    static public function initByRedPacketCarry() {
        $model = new self();
        $model->type = 'redpacket_carry';
        $model->create_sn();
        return $model;
    }

    static public function initByOnlineSale($community_id) {
        $model = new self();
        $model->type = 'online_sale';
        $model->community_id = $community_id;
        $model->create_sn();
        return $model;
    }

    static public function initByCancelOrder() {
        $model = new self();
        $model->type = 'cancel_order';
        $model->create_sn();
        return $model;
    }

    static public function initByOneYuanOrder() {
        $model = new self();
        $model->type = 'oneYuan';
        $model->create_sn();
        return $model;
    }

    static public function initByReturnOrder() {
        $model = new self();
        $model->type = 'return_order';
        $model->create_sn();
        return $model;
    }

    static public function initByPurchaseOrder() {
        $model = new self();
        $model->type = 'purchase_order';
        $model->create_sn();
        return $model;
    }

    static public function initByMealTicket() {
        $model = new self();
        $model->type = 'meal_ticket';
        $model->create_sn();
        return $model;
    }

    static public function initByInsure() {
        $model = new self();
        $model->type = 'insure';
        $model->create_sn();
        return $model;
    }

    static public function initBySpecialty() {
        $model = new self();
        $model->type = 'specialty';
        $model->create_sn();
        return $model;
    }

    /*
     * 第三方支付生成17位sn 
     * @param $cid商户编号
     * @param $cSn商户订单号
     * @return 返回订单号
     */
    static public function initByThirdPaymentOrder($cid, $cSn , $customer_id) {
        if (empty($cid) || strlen($cid) > 3 || empty($cSn))
            throw new CHttpException(404, '获取sn失败');
        $self = new self();
        $obj = new SN();
        $obj->type = 'thirdFees';
        //取得前缀
        $pre = $obj->getTypeSnPre();
        //组装商户号
        $cSn = str_pad($cid, 3, '0', STR_PAD_LEFT);
        $pre = substr($pre, 0, 1) . $cSn;
        $obj->sn = str_pad($pre, 4, '0', STR_PAD_LEFT) .
                 str_pad(F::random(5, 1), 5, '0', STR_PAD_LEFT) .
                date('ymd') .
                date('Him') .
                $customer_id;
        $re = $obj->findContentBySN($obj->sn);
        if ($re)
            return self::initByThirdPaymentOrder($cid, $cSn , $customer_id);
        else
            return $obj;
    }

    static public function initByScanPayOrder()
    {
        $model = new self();
        $model->type = 'thirdFees';
        $model->create_sn();
        return $model;
    }
    
    /*
     * 红包充值sn 
     * @param $cSn商户订单号
     * @return 返回订单号 model
     */
    static public function initByRedpacketFeesOrder() {
        $model = new self();
        $model->type = 'redpacket_fees';
        $model->create_sn();
        return $model;
    }
        //订单奖励数据信息
    static public function initByRewards() {
        $model = new self();
        $model->type = 'rewards';
        $model->create_sn();
        return $model;
    }

}