<?php

/**
 * This is the model class for table "epower_finance_customer_relation".
 *
 * The followings are the available columns in table 'epower_finance_customer_relation':
 * @property string $id
 * @property string $pano
 * @property integer $atid
 * @property integer $fanpiaoid
 * @property string $cno
 * @property string $cano
 * @property integer $customer_id
 * @property string $mobile
 * @property string $name
 * @property string $pay_password
 */
class EpowerFinanceCustomerRelation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'epower_finance_customer_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('atid, fanpiaoid, customer_id', 'numerical', 'integerOnly'=>true),
			array('pano, cno, cano', 'length', 'max'=>32),
			array('mobile', 'length', 'max'=>11),
			array('name, pay_password', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pano, atid, fanpiaoid, cno, cano, customer_id, mobile, name, pay_password', 'safe', 'on'=>'search'),
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
			'pano' => 'Pano',
			'atid' => 'Atid',
			'fanpiaoid' => 'Fanpiaoid',
			'cno' => 'Cno',
			'cano' => 'Cano',
			'customer_id' => 'Customer',
			'mobile' => 'Mobile',
			'name' => 'Name',
			'pay_password' => 'Pay Password',
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
		$criteria->compare('pano',$this->pano,true);
		$criteria->compare('atid',$this->atid);
		$criteria->compare('fanpiaoid',$this->fanpiaoid);
		$criteria->compare('cno',$this->cno,true);
		$criteria->compare('cano',$this->cano,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('pay_password',$this->pay_password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EpowerFinanceCustomerRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
