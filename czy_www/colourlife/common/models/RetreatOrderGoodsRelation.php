<?php

/**
 * This is the model class for table "retreat_order_goods_relation".
 *
 * The followings are the available columns in table 'retreat_order_goods_relation':
 * @property integer $id
 * @property integer $retreat_id
 * @property integer $goods_id
 * @property string $name
 * @property string $price
 * @property integer $count
 * @property string $amount
 */
class RetreatOrderGoodsRelation extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
   // public $search_all;
   
   /**
     * @var string 模型名
     */
    public $modelName = 'RetreatOrderGoodsRelation';
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return RetreatOrderGoodsRelation the static model class
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
		return 'retreat_order_goods_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('retreat_id, goods_id, count', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('price, amount', 'length', 'max'=>10),
            array('id, retreat_id, goods_id, name, price, count, amount,state,bank_pay,integral_price,integral,bank_pay','safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, retreat_id, goods_id, name, price, count, amount,state,bank_pay', 'safe', 'on'=>'search'),
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
            'good'=>array(self::BELONGS_TO,'Goods','goods_id'),
            'order' => array(self::BELONGS_TO, 'RetreatOrder', 'retreat_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'retreat_id' => '订单号',
			'goods_id' => '商品ID',
			'name' => '商品名',
			'price' => '单价',
			'count' => '数量',
			'amount' => '总价',
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
        $criteria->compare('state',$this->state);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function behaviors()
    {
        return array(
																					            
        );
    }
    
}