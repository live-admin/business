<?php

class VirtualRechargeNian extends CActiveRecord
{
    // 如果你有上级
    public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = '年年卡充值订单';
    public $customer_name;
    public $red_packet_pay;
    
    static $charge_status = array(
        Item::NIAN_RECHARGEING => "充值中",
        Item::NIAN_SUCCESS => "充值成功",
        Item::NIAN_REFUND => "已退款",

    );

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return EssayCategory the static model class
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
        return 'nian_nian_ka';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id,sn,customer_id,customer_name,mobile,charge_mobile,charge_type,number,price,pay_method,amount,charge_status,create_time,red_packet_pay', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
//            'ThirdFees' => array(self::HAS_ONE, 'ThirdFees', '','on'=>"'t.sn'='ThirdFees.sn'"),
            'thirdFees'=>array(self::HAS_ONE,'ThirdFees','','on'=>'t.sn=thirdFees.sn'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'sn' => '订单号',
            'customer_id' => '用户ID',
            'mobile' => '手机号码',
            'charge_mobile' => '充值号码',
            'charge_type' => '充值类型',
            'number' => '充值数量',
            'price' => '价格',
            'pay_method'=>'支付方式',
            'amount'=>'实付金额',
            'charge_status'=>'充值状态',
            'create_time'=>'创建时间',
            'customer_name' => '业主名称',
            'red_packet_pay'=>'红包抵扣',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        $criteria = new CDbCriteria;
//        if ($this->customer_name != '') {
//            $criteria->with[] = 'customer';
//            $criteria->compare('customer.name', $this->customer_name, true);
//        }
        if($this->customer_name){
            $criteria->with=array(  
                'customer',  
            );
            $criteria->compare('customer.name', $this->customer_name, true);
        }
//        if($this->red_packet_pay){
//            $criteria->with=array(  
//                'thirdFees',  
//            );
//            $criteria->compare('thirdFees.red_packet_pay', $this->red_packet_pay, true);
//        }

        $criteria->compare('t.sn', $this->sn, true);
        $criteria->compare('t.mobile', $this->mobile, true);
        $criteria->compare('t.charge_mobile', $this->charge_mobile, true);
//        $criteria->compare('customer_id', $this->customer_id);
//        $criteria->compare('create_time', $this->create_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
//            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
//            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
//            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }
    public function getCustomerName(){
        return empty($this->customer) ? $this->customer_id : (empty($this->customer->name) ? $this->customer->username : $this->customer->name);
    }
    public function getRedPacketPay(){
        return $this->thirdFees->red_packet_pay;
    }
    public function getStatusName($html = false)
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$charge_status[$this->charge_status];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }
}
