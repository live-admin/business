<?php

class Telecom extends CActiveRecord{
    public $modelName = "电信卡充值";
    
    const STATUS_WAIT = 0;      //等待处理
    const STATUS_DEAL = 1;      //已处理
    const STATUS_REFUSED = 2;   //拒绝
    
    public static function model($className = __CLASS__){
        return parent::model($className);
    }
    
    public function tableName() {
        return "telecom";
    }
    
    public function rules()
    {
        return array(
            array('customer_id,create_time,status', 'required', 'on' => 'create'),
            array('mobile,lucky_cust_result_id','safe', 'on' => 'create'),
            array('mobile','safe', 'on' => 'update'),
            array('customer_id,create_time,status,mobile,lucky_cust_result_id','safe' , 'on' => 'search'),
        );
    }
    
    public function getStatusNames(){
        return array(self::STATUS_WAIT => '等待处理',
                    self::STATUS_DEAL => '已处理',
                    self::STATUS_REFUSED => '拒绝',
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
            'mobile' => '手机号码',
            'customer_id' => '业主Id',
            'create_time' => '创建时间',
            'status' => '状态',
        );
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
    
    //判断是否为电信的号码
    public function checkMobile($mobile){
        if(strlen(trim($mobile)) != 11){
            return false;
        }
        if(!is_numeric($mobile)){
            return false;
        }
        $str = substr($mobile,0,3);
        switch ($str){
            case "133":
                return true;
                break;
            case "153":
                return true;
                break;
            case "180":
                return true;
                break;
            case "181":
                return true;
                break;
            case "189":
                return true;
                break;
            default :
                return false;
        }
    }
    
    
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    
    
    public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
        );
    }
    
}