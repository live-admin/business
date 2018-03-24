<?php
/**
 * Created by PhpStorm.
 * User: Larry
 * Date: 13-12-11
 * Time: 下午5:09
 */

class SmsTemplateCategory extends CFormModel
{
    public $id, $name;

    static private $data = array(
        'goodsOrders' => '商品订单相关模版',//ownerOrder->goodsOrders
        'parkingFees' => '停车费相关模版',
        'prePayment' => '物业预缴费相关模版',
        'propertyPayment' => '物业缴费相关模版',
        'user' => '用户操作相关模版',
        'recharge' => '充值相关模板',
        'individualRepair' => '个人报修相关模板',
        'publicRepair' => '公共报修相关模板',
        'ownerComplaints' => '业主投诉相关模板',
        'staffComplaints' => '员工投诉相关模板',
        'thirdFees' => '第三方支付模板',
		'redpacketFees' => '红包支付模板',
    );

    private static $_model;
    static public function model()
    {
        if (empty(self::$_model))
            self::$_model = new self();
        return self::$_model;
    }

    public function attributeLabels()
    {
        return array(
            'name' => '模版分类名',
        );
    }

    public function search()
    {
        return new ArrayDataProvider($this->findAll(), array(
            'sort' => array(
                'attributes' => array(
                    'id', 'name',
                ),
            ),
        ));
    }

    public function findAll()
    {
        $models = array();
        foreach (self::$data as $k => $v)
            $models[] = $this->findByPk($k);
        return $models;
    }

    public function findByPk($id)
    {
        if (array_key_exists($id, self::$data)) {
            $model = new self;
            $model->id = $id;
            $model->name = self::$data[$id];
            return $model;
        }
        return null;
    }

}