<?php
/**
 * This is the model class for table "RedpacketInternalTransaction".
 */
class RedpacketInternalTransaction extends CActiveRecord {
    /**
     * @var string 模型名
     */
    public $id;
    public $sn;
    public $sum;
    public $trans_type;
    public $customer_id;
    public $employee_id;
    public $note;
    public $remark;
    public $create_time;
    public $status;

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
        return 'redpacket_internal_transaction';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('trans_type,sum,sn', 'required'),
            array('customer_id, employee_id, create_time, trans_type', 'numerical', 'integerOnly'=>true),
            array('sn', 'length', 'max' => 32),
            array('sum', 'length', 'max' => 10)
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
        );

        return CMap::mergeArray(parent::relations(), $array);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'sn' => '彩之云订单号',
            'customer_name' => '用户名',
            'customer_id' => '用户ID',
            'employee_id' => '员工ID',
            'employee_name' => '员工姓名',
            'sum' => '总金额',
            'note' => '说明',
            'remark' => '备注',
            'create_time' => '创建时间',
            'status' => '状态',
            'trans_type' => '交易类型'
        );
    }
}
