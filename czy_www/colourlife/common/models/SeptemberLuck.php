<?php

/**
 * This is the model class for table "september_luck".
 *
 * The followings are the available columns in table 'september_luck':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $type
 * @property string $invite_mobile
 * @property integer $coupon
 * @property string $amount
 * @property integer $create_time
 * @property integer $way
 */
class SeptemberLuck extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'september_luck';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, type, create_time', 'required'),
			array('customer_id, type, coupon, create_time, way', 'numerical', 'integerOnly'=>true),
			array('invite_mobile', 'length', 'max'=>15),
			array('amount', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, type, invite_mobile, coupon, amount, create_time, way', 'safe', 'on'=>'search'),
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
			'customer_id' => 'Customer',
			'type' => 'Type',
			'invite_mobile' => 'Invite Mobile',
			'coupon' => 'Coupon',
			'amount' => 'Amount',
			'create_time' => 'Create Time',
			'way' => 'Way',
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
		$criteria->compare('type',$this->type);
		$criteria->compare('invite_mobile',$this->invite_mobile,true);
		$criteria->compare('coupon',$this->coupon);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('way',$this->way);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SeptemberLuck the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/*
	 * 抽奖获得饭票
	 * */
	public function getLuck($customer_id, $way){
		if(empty($customer_id)){
			return false;
		}
		$note='2016年9月新人礼活动';
		$customerModel = Customer::model()->find('id=:customer_id and create_time>=:create_time',array(':customer_id'=>$customer_id, ':create_time'=>1472659200));
		$model = SeptemberLuck::model()->find('customer_id=:customer_id and type=:type', array(':customer_id'=>$customer_id ,':type'=>0));
		if(!$customerModel){return false;}
		if($model){return false;}
		$is_send = RedPacketCarry::model()->find('receiver_id=:receiver_id and note=:note', array(':receiver_id'=>$customer_id, ':note'=>$note));
		if($is_send){return false;}

			//扣款账户
		$cmobile=20000000005;
		$cmobile_id = 2224375;
		$amount = rand(8, 20)/10;
		$balance = Customer::model()->findByPk(2224375);

		if($balance['balance']<50){
			$amount = 0;
			$model = new SeptemberLuck();
			$model->customer_id = $customer_id;
			$model->type = 0;
			$model->create_time = time();
			$model->amount = $amount;
			$model->way = $way;
			$model->invite_mobile='';
			$model->save();
			return array('amount'=>$amount);
		}
			$rebateResult=RedPacketCarry::model()->customerTransferAccounts($cmobile_id,$customer_id,$amount,1,$cmobile,$note);
			if ($rebateResult['status']==1){
				$model = new SeptemberLuck();
				$model->customer_id = $customer_id;
				$model->type = 0;
				$model->create_time = time();
				$model->amount = $amount;
				$model->way = $way;
				$model->invite_mobile='';
				$model->save();
				return array('amount'=>$amount);
			}else {
				Yii::log('2016年9月新人礼活动'.date('Y-m-d',time()).'用户id：'.$customer_id.'发票发放失败！错误信息为：'.$rebateResult['msg'],CLogger::LEVEL_ERROR,'colourlife.core.2016SeptemberLuck');
				return false;
			}
	}

	/*
	 * 邀请获得一元饭票
	 * **/
	public function inviteLuck($customer_id, $invite_mobile, $type=1){
		if(empty($customer_id)){return false;}
		$is_send = SeptemberLuck::model()->find('customer_id=:customer_id and invite_mobile=:invite_mobile', array(':customer_id'=>$customer_id, ':invite_mobile'=>$invite_mobile));
		if($is_send){return false;}
		$amount = 1;
		$model = new SeptemberLuck();
		$model->customer_id = $customer_id;
		$model->invite_mobile = $invite_mobile;
		$model->type = $type;
		$model->create_time = time();
		$model->amount = $amount;
		if($model->save()){
			//扣款账户
			$cmobile=20000000005;
			$cmobile_id = 2224375;
			$rebateResult=RedPacketCarry::model()->customerTransferAccounts($cmobile_id,$customer_id,$amount,1,$cmobile,$note='2016年9月新人礼活动(邀请)');
			if ($rebateResult['status']==1){
				return array('amount'=>$amount);
			}else {
				Yii::log('2016年9月新人礼活动(邀请)'.date('Y-m-d',time()).'用户id：'.$customer_id.'发票发放失败！错误信息为：'.$rebateResult['msg'],CLogger::LEVEL_ERROR,'colourlife.core.2016SeptemberLuck');
				return false;
			}
		}else{
			Yii::log('2016年9月新人礼活动(邀请)'.date('Y-m-d',time()).'用户id：'.$customer_id.'返利失败！',CLogger::LEVEL_ERROR,'colourlife.core.2016SeptemberLuck');
			return false;
		}
	}
}
