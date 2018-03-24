<?php
/**
 * This is the model class for table "send_love".
 *
 * The followings are the available columns in table 'send_love':
 * @property int $id
 * @property string $linkman
 * @property int $tel
 * @property string $address
 * @property string $describe
 * @property int $status
 * @property int $customer_id
 * @property int $create_time
 * @property int $is_deleted
 */
class SendLove extends CActiveRecord{
    public $modelName = "送爱心";
    
    const STATUS_WAIT = 0;      //爱心待接收
    const STATUS_RECEIVED = 1;   //爱心已接收
    
    public $customer_name;
    public $customer_mobile;
    
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    
    public function tableName() {
        return "send_love";
    }
    
    public function rules()
    {
        return array(
            array('linkman,tel,address,describe,status,customer_id,create_time,is_deleted', 'required', 'on' => 'create'),
            array('linkman,tel,address,describe,status','safe', 'on' => 'update'),
            array('linkman,tel,customer_name,customer_mobile,customer_id,status', 'safe', 'on' => 'search'),
        );
    }
    
    public function getStatusNames(){
        return array(self::STATUS_WAIT => '爱心待接收',
                    self::STATUS_RECEIVED => '爱心已接收',
                );
    }
    
    public function getStatusName($html = false){
        $res = '';
        $res .= ($html) ? '<span class="label label-success">' : '';
        $res .= $this->StatusNames[$this->status];
        $res .= ($html) ? '</span>' : '';
        return $res;        
    }
    
    
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'linkman' => '姓名',
            'tel' => '联系电话',
            'address' => '地址',
            'describe' => '物品描述',
            'status' => '状态',
            'customer_id' => '业主Id',
            'create_time' => '创建时间',
            'is_deleted' => '是否删除',
            'CustomerName' => '业主姓名',
            'CustomerMobile' => '业主手机号',
            'customer_name' => '业主姓名',
            'customer_mobile' => '业主手机号',
            'StatusName' => '状态',
        );
    }
    
    public function getCustomerName(){
        return $this->customer?$this->customer->name:"";
    }
    
    public function getCustomerMobile(){
        return $this->customer?$this->customer->mobile:"";
    }
    
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        
        $criteria->compare('linkman', $this->linkman, true);
        $criteria->compare('tel', $this->tel, true);
        $criteria->compare('t.status', $this->status);
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

