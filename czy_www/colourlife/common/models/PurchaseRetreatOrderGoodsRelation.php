<?php

/**
 * This is the model class for table "purchase_retreat_order_goods_relation".
 *
 * The followings are the available columns in table 'purchase_retreat_order_goods_relation':
 * @property integer $id
 * @property integer $retreat_id
 * @property integer $goods_id
 * @property string $name
 * @property string $price
 * @property integer $count
 * @property string $amount
 * @property string $integral_price
 * @property integer $integral
 * @property integer $state
 * @property string $bank_pay
 */
class PurchaseRetreatOrderGoodsRelation extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PurchaseRetreatOrderGoodsRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase_retreat_order_goods_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('retreat_id, goods_id, count, integral, state', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('price, amount, integral_price, bank_pay', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, retreat_id, goods_id, name, price, count, amount, integral_price, integral, state, bank_pay', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'good'=>array(self::BELONGS_TO,'PurchaseGoods','goods_id'),
            'order' => array(self::BELONGS_TO, 'PurchaseRetreatOrder', 'retreat_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'retreat_id' => 'Retreat',
			'goods_id' => 'Goods',
			'name' => 'Name',
			'price' => 'Price',
			'count' => 'Count',
			'amount' => 'Amount',
			'integral_price' => 'Integral Price',
			'integral' => 'Integral',
			'state' => 'State',
			'bank_pay' => 'Bank Pay',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('retreat_id',$this->retreat_id);
		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('count',$this->count);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('integral_price',$this->integral_price,true);
		$criteria->compare('integral',$this->integral);
		$criteria->compare('state',$this->state);
		$criteria->compare('bank_pay',$this->bank_pay,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function behaviors()
    {
        return array(
																														            
			'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),            			            
        );
    }
    
}