<?php

/**
 * This is the model class for table "czz_payment_type".
 *
 * The followings are the available columns in table 'czz_payment_type':
 * @property string $id
 * @property string $description
 * @property integer $type
 * @property double $discount
 * @property string $back_amount
 * @property string $house_resource_id
 */
class CzzPaymentType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'czz_payment_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description, house_resource_id', 'required'),
			array('type', 'numerical', 'integerOnly'=>true),
			array('discount', 'numerical'),
			array('description', 'length', 'max'=>128),
			array('back_amount', 'length', 'max'=>14),
			array('house_resource_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, description, type, discount, back_amount, house_resource_id', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'description' => '返利说明',
			'type' => '付款类型,1-折扣, 2-返款',
			'discount' => '折扣',
			'back_amount' => '返款金额',
			'house_resource_id' => '房源ID',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('discount',$this->discount);
		$criteria->compare('back_amount',$this->back_amount,true);
		$criteria->compare('house_resource_id',$this->house_resource_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CzzPaymentType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
