<?php

/**
 * This is the model class for table "pos_machine_paysyntony".
 *
 * The followings are the available columns in table 'pos_machine_paysyntony':
 * @property integer $id
 * @property integer $orderID
 * @property string $orderSN
 * @property string $model
 * @property string $amount
 * @property integer $createTime
 * @property integer $status
 * @property string $remark
 */
class PosMachinePaysyntony extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pos_machine_paysyntony';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('orderID, createTime, status', 'numerical', 'integerOnly'=>true),
			array('orderSN', 'length', 'max'=>35),
			array('model', 'length', 'max'=>20),
			array('amount', 'length', 'max'=>10),
			array('remark', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, orderID, orderSN, model, amount, createTime, status, remark', 'safe', 'on'=>'search'),
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
			'orderID' => '订单ID',
			'orderSN' => '订单号SN',
			'model' => '订单类型',
			'amount' => '交易金额',
			'createTime' => '创建时间',
			'status' => '状态值：0失败/1成功',
			'remark' => '说明',
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
		$criteria->compare('orderID',$this->orderID);
		$criteria->compare('orderSN',$this->orderSN,true);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('createTime',$this->createTime);
		$criteria->compare('status',$this->status);
		$criteria->compare('remark',$this->remark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PosMachinePaysyntony the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
