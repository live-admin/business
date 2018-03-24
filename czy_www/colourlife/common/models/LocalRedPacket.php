<?php

/**
 * This is the model class for table "local_red_packet".
 *
 * The followings are the available columns in table 'local_red_packet':
 * @property string $id
 * @property string $sn
 * @property integer $type
 * @property integer $customer_id
 * @property integer $from_type
 * @property integer $to_type
 * @property string $sum
 * @property integer $create_time
 * @property string $remark
 * @property string $note
 * @property integer $lukcy_result_id
 */
class LocalRedPacket extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'local_red_packet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, note', 'required'),
			array('type, customer_id, from_type, to_type, create_time, lukcy_result_id', 'numerical', 'integerOnly'=>true),
			array('sn', 'length', 'max'=>32),
			array('sum, real_amount, rate', 'length', 'max'=>10),
			array('remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sn, type, customer_id, from_type, to_type, sum,  real_amount, rate, create_time, remark, note, lukcy_result_id', 'safe', 'on'=>'search'),
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
			'sn' => '订单号',
			'type' => '红包类型，0=>消费，1=>获取',
			'customer_id' => '业主ID',
			'from_type' => '获取红包的途径，1=>预缴费退款得到，2=>欠费退款得到，3=>抽奖得到',
			'to_type' => '消费红包的途径，1=>预缴费消费，2=>欠费消费',
			'sum' => '消费或者获取的红包总额',
			'real_amount' => '兑换后的金额',
			'rate' => '折扣率',
			'create_time' => '消费或者获取的时间',
			'remark' => '处理备注',
			'note' => '备注',
			'lukcy_result_id' => '中奖记录ID',
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
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('from_type',$this->from_type);
		$criteria->compare('to_type',$this->to_type);
		$criteria->compare('sum',$this->sum,true);
		$criteria->compare('real_amount',$this->real_amount,true);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('lukcy_result_id',$this->lukcy_result_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LocalRedPacket the static model class
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
