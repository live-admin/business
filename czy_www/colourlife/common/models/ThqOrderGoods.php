<?php

/**
 * This is the model class for table "thq_order_goods".
 *
 * The followings are the available columns in table 'thq_order_goods':
 * @property integer $id
 * @property integer $thq_order_id
 * @property integer $goods_id
 * @property string $name
 * @property string $price
 * @property integer $count
 */
class ThqOrderGoods extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'thq_order_goods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('thq_order_id, goods_id, count', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('price', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, thq_order_id, goods_id, name, price, count', 'safe', 'on'=>'search'),
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
            'thqorder' => array(self::BELONGS_TO, 'ThqOrder', 'order_id'),
            'good' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'thq_order_id' => 'Thq Order',
			'goods_id' => 'Goods',
			'name' => 'Name',
			'price' => 'Price',
			'count' => 'Count',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('thq_order_id',$this->thq_order_id);
		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('count',$this->count);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ThqOrderGoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
