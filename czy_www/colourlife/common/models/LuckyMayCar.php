<?php

/**
 * This is the model class for table "lucky_may_car".
 *
 * The followings are the available columns in table 'lucky_may_car':
 * @property integer $id
 * @property string $sn
 * @property integer $customer_id
 * @property string $model
 * @property string $amount
 * @property string $note
 * @property integer $create_time
 * @property integer $status
 * @property integer $pay_time
 * @property integer $is_send
 * @property string $update_username
 * @property integer $update_userid
 * @property string $update_date
 */
class LuckyMayCar extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_may_car';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_username, update_userid, update_date', 'required'),
			array('customer_id, create_time, status, pay_time, is_send, update_userid', 'numerical', 'integerOnly'=>true),
			array('sn', 'length', 'max'=>32),
			array('model', 'length', 'max'=>20),
			array('amount', 'length', 'max'=>10),
			array('update_username', 'length', 'max'=>100),
			array('note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sn, customer_id, model, amount, note, create_time, status, pay_time, is_send, update_username, update_userid, update_date', 'safe', 'on'=>'search'),
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
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => 'sn',
			'customer_id' => '用户ID',
			'model' => '订单类型',
			'amount' => '投资金额',
			'note' => '备注',
			'create_time' => '创建时间',
			'status' => '状态',
			'pay_time' => '付款时间',
			'is_send' => '发放标志',
			'update_username' => '发放提成人OA',
			'update_userid' => '发放人id',
			'update_date' => '发放时间',
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
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('model',$this->model,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('pay_time',$this->pay_time);
		$criteria->compare('is_send',$this->is_send);
		$criteria->compare('update_username',$this->update_username,true);
		$criteria->compare('update_userid',$this->update_userid);
		$criteria->compare('update_date',$this->update_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyMayCar the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
