<?php

/**
 * This is the model class for table "company_customer_register_relation".
 *
 * The followings are the available columns in table 'company_customer_register_relation':
 * @property string $id
 * @property integer $company_id
 * @property integer $customer_id
 * @property string $create_time
 */
class CompanyCustomerRegisterRelation extends CActiveRecord
{
	public $modelName = '企业业主';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'company_customer_register_relation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, customer_id', 'required'),
			array('company_id, customer_id', 'numerical', 'integerOnly'=>true),
			array('create_time', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, company_id, customer_id, create_time', 'safe', 'on'=>'search'),
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
			'company_id' => 'Company',
			'customer_id' => 'Customer',
			'create_time' => 'åˆ›å»ºæ—¶é—´',
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
		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CompanyCustomerRegisterRelation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function behaviors()
	{
		return array(
				'CTimestampBehavior' => array(
						'class' => 'zii.behaviors.CTimestampBehavior',
						'createAttribute' => 'create_time',
						'updateAttribute' => null,
						'setUpdateOnCreate' => true,
				),
		);
	}
}
