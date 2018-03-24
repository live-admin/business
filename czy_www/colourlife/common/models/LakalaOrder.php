<?php

/**
 * This is the model class for table "lakala_order".
 *                   
 * The followings are the available columns in table 'lucky_goods':
 * @property integer $id
 * @property string $orderNo
 * @property string $txncod
 * @property string $requestId
 * @property string $mercId
 * @property string $termId
 * @property string $refNumber
 * @property string $transTime
 * @property string $extData
 * @property string $lakala_md5
 * @property string $rspMsg
 * @property string $result
 * @property integer $ordAmt
 * @property integer $orderSta
 * @property string $colourlife_md5
 */
class LakalaOrder extends CActiveRecord
{
	public $modelName = '拉卡拉支付';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lakala_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('orderNo, txncod, requestId, mercId, termId, refNumber, transTime, extData, lakala_md5, rspMsg, result, ordAmt, orderSta, colourlife_md5', 'required', 'on' => 'create,update'),			
			array('id, orderNo, txncod, requestId, mercId, termId, refNumber, transTime', 'safe', 'on'=>'search'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'orderNo' => '订单号',
			'txncod' => '交易码',
			'requestId' => '请求方',
			'mercId' => '商户号',
			'termId' => '终端号',
			'refNumber' => '系统参考号',
			'transTime' => '交易传输时间',
			'extData' => '扩展信息',
			'lakala_md5' => '请求校验值',
			'rspMsg' => '应答码描述',
			'result' => '返回码',
			'ordAmt' => '订单金额',
			'orderSta' => '订单状态',
			'colourlife_md5' => '应答校验值',
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
		$criteria->compare('orderNo',$this->orderNo,true);
		$criteria->compare('txncod',$this->txncod,true);
		$criteria->compare('requestId',$this->requestId,true);
		$criteria->compare('mercId',$this->mercId,true);
		$criteria->compare('termId',$this->termId,true);
		$criteria->compare('refNumber',$this->refNumber,true);
		$criteria->compare('transTime',$this->transTime);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyGoods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	

}
