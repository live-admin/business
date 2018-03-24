<?php

/**
 * This is the model class for table "order_goods_relation".
 *
 * The followings are the available columns in table 'order_goods_relation':
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property string $name
 * @property string $price
 * @property integer $count
 * @property string $amount
 * @property PurchaseGoodsCategory $goodsCategory
 * @property PurchaseGoods $good
 */
class PurchaseOrderGoodsRelation extends CActiveRecord
{

    /**
     * @var string 模型名
     */
    public $modelName = 'PurchaseOrderGoodsRelation';

    public $description;
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return OrderGoodsRelation the static model class
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
        return 'purchase_order_goods_relation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('order_id, goods_id, count', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('price, amount', 'length', 'max' => 10),
            array('integral,integral_price,bank_pay','safe'),
            //array('count', 'checkCount', 'on' => 'create,update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, order_id, goods_id, name, price, count, amount', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
            'good' => array(self::BELONGS_TO, 'PurchaseGoods', 'goods_id'),
            'goodsCategory' => array(self::BELONGS_TO, 'PurchaseGoodsCategory', 'goods_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'order_id' => '订单号',
            'goods_id' => '商品ID',
            'name' => '商品名',
            'price' => '单价',
            'count' => '数量',
            'amount' => '总价',
            'integral'=>'积分',
            'bank_pay' => '支付金额',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('order_id', $this->order_id);
        $criteria->compare('goods_id', $this->goods_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('count', $this->count);
        $criteria->compare('amount', $this->amount, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function goods_search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('order_id', $this->order_id);
        $criteria->compare('goods_id', $this->goods_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('count', $this->count);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('is_lock', Item::CUSTOMER_ORDER_UNLOCK);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getAllCategoryName()
    {
        $name = '-';
        if(!empty($this->good)){
            $name = $this->good->getGoodsAllCategoryName();
        }
        return $name;
    }

    public function behaviors()
    {
        return array();
    }

    public function getGoodsPic(){
        if(isset($this->good)){
            return F::getUploadsUrl("/images/" . $this->good->good_image);
        }else{
            return "";
        }
    }
}
