<?php

class SellerOrder extends BaseOrder
{

    public $modelName = '加盟订单';
    public $goods_id;
    public $count;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

}
