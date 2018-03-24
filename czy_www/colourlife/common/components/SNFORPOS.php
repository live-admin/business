<?php

class SNFORPOS
{
    private $community_id = 0;
    //类型
    private $type = 'online_sale';

    private $sn;

    private $types = array(
        array( //物业费
            'pre' => '101',
            'type' => 'property_fee',
            'model' => 'OthersPropertyFees',
        ),
        array( //停车费
            'pre' => '102',
            'type' => 'parking_fee',
            'model' => 'OthersParkingFees',
        ),
        array( //商铺买电
            'pre' => '103',
            'type' => 'power_fees',
            'model' => 'OthersPowerFees',
        ),
        array( //投资E理财减免物业费
            'pre' => '104',
            'type' => 'property_activity',
            'model' => 'PropertyActivity',
        ),
        array( //团购
            'pre' => '201',
            'type' => 'tuan',
            'model' => 'Order',
        ),
        array( //抢购
            'pre' => '202',
            'type' => 'qiang_gou',
            'model' => 'Order',
        ),
        array( //在线销售
            'pre' => '203',
            'type' => 'online_sale',
            'model' => 'CustomerOrder',
        ),
        array( //加盟商销售
            'pre' => '400',
            'type' => 'seller',
            'model' => 'SellerOrder',
        ),
        array( //电话费
            'pre' => '300',
            'type' => 'virtual_recharge',
            'model' => 'OthersVirtualRecharge',
        ),
        array( //qq充值
            'pre' => '301',
            'type' => 'virtual_recharge',
            'model' => 'OthersVirtualRecharge',
        ),
        array( //游戏充值
            'pre' => '302',
            'type' => 'virtual_recharge',
            'model' => 'OthersVirtualRecharge',
        ),
		 array( //预付费
            'pre' => '303',
            'type' => 'advance_fees',
            'model' => 'OthersAdvanceFees',
        ),
        // array( //取消的退货单
        //     'pre' => '501',
        //     'type' => 'cancel_order',
        //     'model' => 'RetreatOrder',
        // ),
        // array( //退货的退货单
        //     'pre' => '502',
        //     'type' => 'return_order',
        //     'model' => 'RetreatOrder',
        // ),
        array( //内部采购
            'pre' => '601',
            'type' => 'purchase_order',
            'model' => 'PurchaseOrder',
        ),
    );

    //防止用户克隆
    public function __clone()
    {
        throw new Exception('Cannot clone the SN object.');
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function __get($property)
    {
        return $this->$property;
    }

    private function getTypeSnPre()
    {
        foreach ($this->types as $k => $v) {
            if ($this->type == $v['type'])
                return $this->types[$k]['pre'];
        }
        return '000';
    }

    private function getTypeSnModel($sn)
    {
        $type_id = substr($sn, 0, 3);
        foreach ($this->types as $k => $v) {
            if ($type_id == $v['pre'])
                return $this->types[$k]['model'];
        }
        return 'Order';
    }

    //生成一个SN
    public function create_sn()
    {
        $this->sn =
            str_pad($this->getTypeSnPre(), 3, '0', STR_PAD_LEFT) .
            str_pad($this->community_id, 4, '0', STR_PAD_LEFT) .
            date('ymd') .
            date('Him') .
            str_pad(F::random(3,1), 3, '0', STR_PAD_LEFT);

        if(!self::findContentBySN($this->sn))
            return $this->sn;
        else
            return $this->create_sn();
    }

     //生成一个SN $num为前面的几位为7位
    static public function write_create_sn($num)
    {
        $sn =
            str_pad($num, 7, '0', STR_PAD_LEFT) .
            date('ymd') .
            date('Him') .
            str_pad(F::random(3,1), 3, '0', STR_PAD_LEFT);

        return $sn;
    }

    public function getSN()
    {
        return $this->sn;
    }

    static public function findModelBySN($sn)
    {
        $model = new self();
        return $model->getTypeSnModel($sn);
    }

    static public function findContentBySN($sn)
    {
        $model = new self();
        $model_string = $model->getTypeSnModel($sn);
        if ($model_string != '') {
            $model = new $model_string();
            return $model::model()->find('sn=:sn', array(':sn' => $sn));
        }
        return NULL;
    }


    static public function initBySeller()
    {
        $model = new self();
        $model->type = 'seller';
        $model->create_sn();
        return $model;
    }

    static public function initByParkingFees()
    {
        $model = new self();
        $model->type = 'parking_fee';
        $model->create_sn();
        return $model;
    }

	//预付费
    static public function initByAdvanceFees(){
		$model = new self();
        $model->type = 'advance_fees';
        $model->create_sn();
        return $model;
	}

    static public function initByVirtualRecharge()
    {
        $model = new self();
        $model->type = 'virtual_recharge';
        $model->create_sn();
        return $model;
    }

    static public function initByPropertyFees()
    {
        $model = new self();
        $model->type = 'property_fee';
        $model->create_sn();
        return $model;
    }

    static public function initByPowerFees()
    {
        $model = new self();
        $model->type = 'power_fees';
        $model->create_sn();
        return $model;
    }

    static public function initByPropertyActivity()
    {
        $model = new self();
        $model->type = 'property_activity';
        $model->create_sn();
        return $model;
    }

    static public function initByOnlineSale($community_id)
    {
        $model = new self();
        $model->type = 'online_sale';
        $model->community_id = $community_id;
        $model->create_sn();
        return $model;
    }

    // static public function initByCancelOrder()
    // {
    //     $model = new self();
    //     $model->type = 'cancel_order';
    //     $model->create_sn();
    //     return $model;
    // }

    // static public function initByReturnOrder()
    // {
    //     $model = new self();
    //     $model->type = 'return_order';
    //     $model->create_sn();
    //     return $model;
    // }

    static public function initByPurchaseOrder()
    {
        $model = new self();
        $model->type = 'purchase_order';
        $model->create_sn();
        return $model;
    }


}
/**
 * $sn = SN::initByOrder($order);
 * $sn->getSN();
 *
 * $sn = SN::initBySN($str);
 * $type = $sn->getType();
 * switch ($type) {
 *   case 'order':
 *     $order = $sn->getModel();
 *     $order->pay1();
 *   case 'wuye':
 *      $wuye = $sn->getModel();
 *     $wuye->pay2();
 *
 * }
 */