<?php
/**
 * 顺风嘿客优惠卷
 * This is the model class for table "goods_coupon_no".
 * @update 2015-06-17
 * @by wenda
 */
class GoodsCouponNo extends CActiveRecord
{
    public $modelName = '顺风发码专用';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Friend the static model class
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
        return 'goods_coupon_no';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('code, shop_id ', 'required'),
            array('shop_id, order_id, is_use', 'numerical', 'integerOnly' => true),
            array('code', 'length', 'max' => 50),
            array('id,code,shop_id,order_id,is_use', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'code' => '优惠码',
            'shop_id' => '商家Id号',
            'order_id' => '订单编号',
            'is_use' => '是否已使用'
        );
    }

    public function behaviors()
    {
        return array();
    }

}
