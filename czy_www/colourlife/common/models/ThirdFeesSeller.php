<?php
/**
 * This is the model class for table "others_fees_seller".
 *
 * The followings are the available columns in table 'others_fees':
 */
class ThirdFeesSeller extends CActiveRecord {

   public $modelName = '第三方下单-商家表';

    const AUDIT_NOT = 0;    //未审核
    const AUDIT_YES = 1;    //已审核
    const TYPE_WEB = 0;     //类型：网站
    const TYPE_MOBILE = 1;   //类型：手机
    const STATE_YES = 0;      //启用
    const STATE_NO = 1;       //禁用
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return OthersFees the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'third_fees_seller';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cId,state', 'numerical', 'integerOnly' => true),
            array('website, return_url', 'length', 'max' => 200),
            array('name', 'length', 'max' => 32),
            array('id,cId', 'length', 'max' => 3),
            array('secret', 'length', 'max' => 32),
            array('cId,name,website,secret,return_url', 'safe'),
            array('id,cId,name,website,secret,return_url,state', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'cId' => '商户号',
            'name' => '名称',
            'website' => '官网',
            'secret' => '密钥',
            'return_url' => '回调地址',
            'state' => '状态'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;
//        $criteria->compare('t.payment_id', $this->payment_id);
//        $criteria->with[] = 'ThirdFeesAddr';
//        if ($this->cId != '') {
//            $criteria->compare('ThirdFeesAddr.cId', $this->room, true);
//        }
//
//        if ($this->startTime != '') {
//            $criteria->compare("`t`.create_time", ">= " . strtotime($this->startTime . " 00:00:00"));
//        }
//        if ($this->endTime != '') {
//            $criteria->compare("`t`.create_time", "<= " . strtotime($this->endTime . " 23:59:59"));
//        }
//        $criteria->with[] = 'pay';
//        $criteria->compare('`t`.id', $this->id);
//        $criteria->compare('`t`.sn', $this->sn, true);
//        $criteria->compare('pay.pay_sn', $this->pay_sn, true);
//        $criteria->compare('`t`.customer_id', $this->customer_id);
//        $criteria->compare('`t`.object_id', $this->object_id);
//        //  $criteria->compare('`t`.model','PropertyActivity');
//        $criteria->compare('`t`.amount', $this->amount, true);
//        $criteria->compare('`t`.note', $this->note, true);
//        $criteria->compare('`t`.cSn', $this->cSn);
//        $criteria->compare('`t`.create_time', $this->create_time);
//        $criteria->compare('`t`.state', $this->state);
//        $criteria->compare('`t`.pay_time', $this->pay_time);
//        $criteria->compare('`t`.update_time', $this->update_time);
//        if ($this->customer_name || $this->mobile) {
//            $criteria->with[] = 'customer';
//            $criteria->compare('customer.name', $this->customer_name, true);
//            $criteria->compare('customer.mobile', $this->mobile, true);
//        }
        return new ActiveDataProvider($this, array(
           'criteria' => $criteria,
        ));
    }
    
    public function getStateName(){
        switch ($this->state){
            case self::STATE_YES:
                return "启用";
                break;
            case self::STATE_NO:
                return "禁用";
                break;
        }
    }
    
    public function getAuditName(){
        switch ($this->audit) {
            case self::AUDIT_NOT:
                return '未审核';
                break;
            case self::AUDIT_YES:
                return '已审核';
                break;
        }
    }
    
    public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
            //'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }
    
}
