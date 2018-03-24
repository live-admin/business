<?php

/**
 * This is the model class for table "local_red_packet_recharge".
 *
 * The followings are the available columns in table 'local_red_packet_recharge':
 * @property integer $id
 * @property integer $customer_id
 * @property string $pano
 * @property integer $atid
 * @property string $cano
 * @property string $amount
 * @property string $note
 * @property integer $create_time
 */
class LocalRedPacketRecharge extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'local_red_packet_recharge';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, atid, state, create_time', 'numerical', 'integerOnly'=>true),
			array('pano, cano', 'length', 'max'=>32),
			array('amount', 'length', 'max'=>10),
			array('orderno', 'length', 'max'=>64),
			array('note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, pano, atid, cano, amount, note, orderno, state, create_time', 'safe', 'on'=>'search'),
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
			'customer_id' => '用户ID',
			'pano' => '平台饭票账号',
			'atid' => '平台饭票类型',
			'cano' => '用户饭票账号',
			'amount' => '充值金额',
			'note' => '备注',
			'orderno' => '流水号',
			'state' => '是否生效（0生效，1失效）',
			'create_time' => '添加时间',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('pano',$this->pano,true);
		$criteria->compare('atid',$this->atid);
		$criteria->compare('cano',$this->cano,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('orderno',$this->orderno,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LocalRedPacketRecharge the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
