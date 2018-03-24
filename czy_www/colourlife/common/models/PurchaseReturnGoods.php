<?php

/**
 * This is the model class for table "purchase_return_goods".
 *
 * The followings are the available columns in table 'purchase_return_goods':
 * @property integer $id
 * @property string $goods_name
 * @property integer $goods_id
 * @property integer $goods_num
 * @property string $goods_price
 * @property integer $user_id
 * @property integer $return_id
 */
class PurchaseReturnGoods extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PurchaseReturnGoods the static model class
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
        return 'purchase_return_goods';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('goods_id, goods_num, user_id, return_id', 'numerical', 'integerOnly' => true),
            array('goods_name', 'length', 'max' => 120),
            array('goods_price', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, goods_name, goods_id, goods_num, goods_price, user_id, return_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'goods_name' => 'Goods Name',
            'goods_id' => 'Goods',
            'goods_num' => 'Goods Num',
            'goods_price' => 'Goods Price',
            'user_id' => 'User',
            'return_id' => 'Return',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('goods_name', $this->goods_name, true);
        $criteria->compare('goods_id', $this->goods_id);
        $criteria->compare('goods_num', $this->goods_num);
        $criteria->compare('goods_price', $this->goods_price, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('return_id', $this->return_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
