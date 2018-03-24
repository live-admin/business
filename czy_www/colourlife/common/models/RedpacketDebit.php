<?php

/**
 * This is the model class for table "redpacket_debit".
 *
 * The followings are the available columns in table 'redpacket_debit':
 * @property integer $id
 * @property string $mobile
 * @property string $amount
 * @property string $amount_left
 * @property string $note
 * @property integer $status
 * @property integer $account_type
 * @property string $debit_type
 * @property integer $create_time
 * @property integer $update_time
 */
class RedpacketDebit extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'redpacket_debit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('note', 'required'),
			array('status, account_type, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('mobile', 'length', 'max'=>15),
			array('amount, amount_left', 'length', 'max'=>10),
			array('debit_type', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mobile, amount, amount_left, note, status, account_type, debit_type, create_time, update_time', 'safe', 'on'=>'search'),
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
			'id' => '表ID',
			'mobile' => '手机号',
			'amount' => '金额',
			'amount_left' => '剩余扣款金额',
			'note' => '备注',
			'status' => '状态（1已扣完，0未扣，2扣款失败，3用户余额小于等于0，4未扣完）',
			'account_type' => '账号类型（0是未知，1是员工，2是业主）',
			'debit_type' => '扣款活动',
			'create_time' => '添加时间',
			'update_time' => '更新时间',
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
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('amount_left',$this->amount_left,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('account_type',$this->account_type);
		$criteria->compare('debit_type',$this->debit_type,true);
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
	 * @return RedpacketDebit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
