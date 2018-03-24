
<?php

/**
 * This is the model class for table "nian_nian_ka".
 *
 * The followings are the available columns in table 'nian_nian_ka':
 * @property integer $id
 * @property string $sn
 * @property integer $customer_id
 * @property string $mobile
 * @property string $charge_mobile
 * @property integer $charge_type
 * @property integer $number
 * @property string $price
 * @property string $pay_method
 * @property string $amount
 * @property integer $charge_status
 * @property integer $create_time
 */
class NianNianKa extends CActiveRecord
{
    public $modelName = '年年卡充值订单';
    public $customer_name;
    public $red_packet_pay;
    public $user_red_packet;
    public $cSn;
    public $status;
    static $charge_status = array(
        Item::NIAN_RECHARGEING => "充值中",
        Item::NIAN_SUCCESS => "充值成功",
        Item::NIAN_REFUND => "待退款",
        Item::NIAN_BACK=>"已退款",
    );
    static $charge_type = array(
        Item::NIAN_HUAFEI => "话费",
        Item::NIAN_LIULIANG => "流量",
    );
    static $third_status=array(
        Item::PAY_STATUS_NO => "支付失败",
        Item::PAY_STATUS_OK => "支付成功",
        
    );
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'nian_nian_ka';
    }
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id, charge_type, number, charge_status, create_time', 'numerical', 'integerOnly'=>true),
            array('sn, mobile, charge_mobile, pay_method', 'length', 'max'=>255),
            array('price, amount', 'length', 'max'=>10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sn, customer_id, red_packet_pay,mobile,user_red_packet,cSn,status,charge_mobile, charge_type, number, price, pay_method, amount, charge_status, create_time,operator', 'safe', 'on'=>'search'),
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
//            'thirdFees'=>array(self::HAS_ONE,'ThirdFees','','on'=>'t.sn=thirdFees.sn'),
            'thirdFees' => array(self::HAS_ONE, 'ThirdFees', array('sn'=>'sn')),
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
//            'pay_method' => '支付方式',
            'amount' => '总金额',
            'charge_status' => '充值状态',
            'create_time' => '创建时间',
            'customer_name' => '业主名称',
            'red_packet_pay'=>'红包抵扣',
            'bank_pay'=>'实付金额',
            'user_red_packet'=>'使用红包',
            'status'=>'支付状态',
            'payment_id'=>'支付方式',
            'operator'=>'运营商',
            'cSn'=>'第三方订单号',
        );
    }
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
        if(isset($this->user_red_packet)){
            $criteria->with=array(  
                'thirdFees',  
            );
            $criteria->compare('thirdFees.user_red_packet', $this->user_red_packet, true);
        }
        if(isset($this->status)){
            $criteria->with=array(  
                'thirdFees',  
            );
            $criteria->compare('thirdFees.status', $this->status, true);
        }
        if(isset($this->cSn)){
            $criteria->with=array(  
                'thirdFees',  
            );
            $criteria->compare('thirdFees.cSn', $this->cSn, true);
        }
        
        $criteria->compare('t.sn', $this->sn,true);
        $criteria->compare('t.mobile', $this->mobile, true);
        $criteria->compare('t.charge_mobile', $this->charge_mobile, true);
        $criteria->compare('t.charge_status', $this->charge_status, true);
//        $criteria->compare('customer_id', $this->customer_id);
//        $criteria->compare('create_time', $this->create_time);
        $criteria->order='t.create_time desc';
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    public function getChargeMobile(){
        
        return $this->charge_mobile;
    }
    public function getChargeStatus(){
        
        return $this->charge_status;
    }
//    public function getAmount(){
//        
//        return $this->amount;
//    }
    public function getCreateTime(){
        
        return $this->create_time;
    }
    /*
     * @version 获取充值状态名称
     */
    public function getChargeStatusName(){
        if($this->charge_status==Item::NIAN_RECHARGEING){
            return self::$charge_status[Item::NIAN_RECHARGEING];
        }
        if($this->charge_status==Item::NIAN_SUCCESS){
            return self::$charge_status[Item::NIAN_SUCCESS];
        }
        if($this->charge_status==Item::NIAN_REFUND){
            return self::$charge_status[Item::NIAN_REFUND];
        }
        if($this->charge_status==Item::NIAN_BACK){
            return self::$charge_status[Item::NIAN_BACK];
        }
    }
    /*
     * @version 获取支付方式名称
     */
    public function getPayMethodName(){
        if(!empty($this->thirdFees)){
            $resultArr=Payment::model()->findByPk($this->thirdFees->payment_id);
            return $resultArr['name'];
        }
    }
    
    
    /*
     * @version 获取充值类型名称
     */
    public function getChargeType(){
        
        if($this->charge_type==Item::NIAN_HUAFEI){
            return self::$charge_type[Item::NIAN_HUAFEI];
        }
        if($this->charge_type==Item::NIAN_LIULIANG){
            return self::$charge_type[Item::NIAN_LIULIANG];
        }
    }
    
    
    public function getCustomerName(){
        return empty($this->customer) ? $this->customer_id : (empty($this->customer->name) ? $this->customer->username : $this->customer->name);
    }
    public function getRedPacketPay(){
        if(!empty($this->thirdFees)){
            return $this->thirdFees->red_packet_pay;
        }
    }
    public function getBankPay(){
        if(!empty($this->thirdFees)){
            return $this->thirdFees->bank_pay;
        }
    }
    public function getCsn(){
        if(!empty($this->thirdFees)){
            return $this->thirdFees->cSn;
        }
    }
    public function getStatus(){
        if(!empty($this->thirdFees)){
            return $this->thirdFees->status;
        }
    }
    public function getPaymentId(){
        if(!empty($this->thirdFees)){
            return $this->thirdFees->payment_id;
        }
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
    public function getThirdStatusName($html = false)
    {
        error_reporting(E_ALL ^ E_NOTICE);
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$third_status[$this->thirdFees->status];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }
    public function back(){
		$model=$this->findByPk($this->getPrimaryKey());
        $feeArr=ThirdFees::model()->find('sn=:sn',array(':sn'=>$model->sn));
        $mobile=$model->mobile;
        $sn=$model->sn;
        $amount=$model->amount;
        $charge_mobile=$model->charge_mobile;
        if($model->charge_status== Item::NIAN_BACK){
            throw new CHttpException(400, '您的订单已经退款过了！');
        }
        $items = array(
            'customer_id' =>$model->customer_id,
            'from_type' => Item::RED_PACKET_FROM_TYPE_BACK,
            'sum' => $feeArr['red_packet_pay'],
            'sn' => $sn,
        );
        $redPacked = new RedPacket();
        $execute=$redPacked->addRedPacker($items);
        if($execute){
            $model->charge_status=  Item::NIAN_BACK;
            if($model->save()){
                $title="第三方缴费通知";
                $content="尊敬的用户，您的充值订单[".$sn."],总额[".$amount."],充值手机号[".$charge_mobile."],已退款";
                PushInformation::model()->createNian($mobile,$title,$content);
            }
            
        }
	}
}
    

