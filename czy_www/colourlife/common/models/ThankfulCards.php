<?php
/**
 * This is the model class for table "thankful_cards".
 *
 * The followings are the available columns in table 'thankful_cards':
 * @property int $id
 * @property string $note
 * @property int $customer_id
 * @property string $linkman
 * @property string $tel
 * @property string $address
 * @property int $lucky_prize_id
 * @property int $create_time
 * @property int $type
 * @property int $status
 */
class ThankfulCards extends CActiveRecord{
    public $modelName = "感恩卡";
    
    public $customer_name;
    public $customer_mobile;
    
    const STATUS_WAIT = 0;      //等待发货
    const STATUS_SEND = 1;      //已发货
    const STATUS_RECEIVED = 2;   //已收货
    
    const TYPE_MEMO = 0;  //便签
    const TYPE_CARD = 1;  //感恩卡
    const TYPE_GIFT = 2;  //感恩礼包
    
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    
    public function tableName() {
        return "thankful_cards";
    }
    
    public function rules()
    {
        return array(
            array('note,customer_id,linkman,tel,address,lucky_prize_id,create_time,is_deleted,status,type', 'required', 'on' => 'create'),
            array('note,linkman,tel,address', 'required', 'on' => 'update'),
            array('status,express_company,tracking_number', 'safe', 'on' => 'update'),
            array('linkman,tel,customer_id,customer_name,customer_mobile,status,type','safe' , 'on' => 'search'),
        );
    }
    
    public function getStatusNames(){
        return array(self::STATUS_WAIT => '等待发货',
                    self::STATUS_SEND => '已发货',
                    self::STATUS_RECEIVED => '已收货',
                );
    }
    
    public function getStatusName($html = false){
        $res = '';
        $res .= ($html) ? '<span class="label label-success">' : '';
        $res .= $this->StatusNames[$this->status];
        $res .= ($html) ? '</span>' : '';
        return $res;        
    }
    
    public function getTypeNames(){
        return array(
            self::TYPE_MEMO => '便签',
            self::TYPE_CARD => '感恩卡',
            self::TYPE_GIFT => '感恩礼包',
        );
    }
    
    public function getTypeName(){
        return $this->TypeNames[$this->type];
    }
    
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'note' => '卡片内容',
            'customer_id' => '业主Id',
            'linkman' => '收件人',
            'tel' => '联系电话',
            'address' => '收货地址',
            'lucky_prize_id' => '奖项Id',
            'create_time' => '创建时间',
            'is_deleted' => '是否删除',
            'CustomerName' => '业主姓名',
            'CustomerMobile' => '业主手机号',
            'customer_name' => '业主姓名',
            'customer_mobile' => '业主手机号',
            'LuckyPrizeName' => '奖品名称',
            'status' => '状态',
            'StatusName' => '状态',
            'type' => '类型',
            'express_company' => '快递公司',
            'tracking_number' => '快递单号',
        );
    }
    
    public function getCustomerName(){
        return $this->customer?$this->customer->name:"";
    }
    
    public function getCustomerMobile(){
        return $this->customer?$this->customer->mobile:"";
    }
    
    public function getLuckyPrizeName(){
        return $this->lucky_prize?$this->lucky_prize->prize_name:"";
    }
    
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        
        $criteria->compare('linkman', $this->linkman, true);
        $criteria->compare('tel', $this->tel, true);
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.type', $this->type);
        $criteria->compare('customer_id', $this->customer_id, true);
        if($this->customer_name || $this->customer_mobile){
            $criteria->with=array(  
                'customer',  
            );
            $criteria->compare('customer.name', $this->customer_name, true);
            $criteria->compare('customer.mobile', $this->customer_mobile, true);
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
        /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'lucky_prize' => array(self::BELONGS_TO, 'LuckyPrize', 'lucky_prize_id'),
        );
    }
    
    public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
        );
    }
    
    
}