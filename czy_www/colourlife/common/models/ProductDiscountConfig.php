<?php

/**
 * This is the model class for table "product_discount_config".
 *
 * The followings are the available columns in table 'product_discount_config':
 * @property integer $id
 * @property integer $product_id
 * @property integer $begin_num
 * @property integer $end_num
 * @property string $discount
 * @property string $price
 * @property integer $cheng_tuan_num
 * @property integer $create_time
 * @property integer $update_time
 */
class ProductDiscountConfig extends CActiveRecord
{
    public $modelName = '商品折扣';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product_discount_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, begin_num, end_num, cheng_tuan_num, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('discount, price', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, product_id, begin_num, end_num, discount, price, cheng_tuan_num, create_time, update_time', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键id',
			'product_id' => '产品id',
			'begin_num' => '起始人数',
			'end_num' => '结束人数',
			'discount' => '折扣',
			'price' => '价格',
			'cheng_tuan_num' => '最低组团人数',
			'create_time' => '创建时间',
			'update_time' => '修改时间',
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
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('begin_num',$this->begin_num);
		$criteria->compare('end_num',$this->end_num);
		$criteria->compare('discount',$this->discount,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('cheng_tuan_num',$this->cheng_tuan_num);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductDiscountConfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
