<?php
/**
 * This is the model class for table "others_fees_seller".
 *
 * The followings are the available columns in table 'others_fees':
 */
class ThirdSellerPrivilege extends CActiveRecord {

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
        return 'third_seller_privilege';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cid,state', 'numerical', 'integerOnly' => true),
            array('uri', 'length', 'max' => 200)
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
            'uri' => '请求地址',
            'state' => '状态'
        );
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

    public function behaviors()
    {
        return array(
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
            //'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }
    
}
