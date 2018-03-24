<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/6/7
 * Time: 11:15
 */
class InsureInfo extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'insure_info';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('start_time, end_time, create_time', 'numerical', 'integerOnly' => true),
            array('trade_id, proposal_no, policy_no, identity_no, start_time, end_time, insure_sum, create_time', 'safe', 'on' => 'search')
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
    			'trade_id' => '支付订单号（保险公司）',
    			'proposal_no' => '投保单号',
    			'policy_no' => '保单号',
    			'identity_no' => '身份号码',
    			'start_time' => '起保时间',
    			'end_time' => '终保时间',
    			'insure_sum' => '保额',
    			'address' => '地址（省、市、区）',
    			'detail_address' => '详细地址',
    			'applicant_info' => '投保人信息',
    			'insure_info' => '被保人信息',
    			'insure_subject_info' => '投保人和被保人关系',
    			'property_info' => '地址标码',
    			'receive_confirm' => '确认订单返回信息',
    			'receive_make' => '出单返回信息',
    			'create_time' => '创建时间',
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
    	$criteria->compare('trade_id',$this->trade_id,true);
    	$criteria->compare('proposal_no',$this->proposal_no,true);
    	$criteria->compare('policy_no',$this->policy_no,true);
    	$criteria->compare('identity_no',$this->identity_no,true);
    	$criteria->compare('start_time',$this->start_time);
    	$criteria->compare('end_time',$this->end_time);
    	$criteria->compare('insure_sum',$this->insure_sum,true);
    	$criteria->compare('address',$this->address,true);
    	$criteria->compare('detail_address',$this->detail_address,true);
    	$criteria->compare('applicant_info',$this->applicant_info,true);
    	$criteria->compare('insure_info',$this->insure_info,true);
    	$criteria->compare('insure_subject_info',$this->insure_subject_info,true);
    	$criteria->compare('property_info',$this->property_info,true);
    	$criteria->compare('receive_confirm',$this->receive_confirm,true);
    	$criteria->compare('receive_make',$this->receive_make,true);
    	$criteria->compare('create_time',$this->create_time);
    
    	return new CActiveDataProvider($this, array(
    			'criteria'=>$criteria,
    	));
    }
    
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return InsureInfo the static model class
     */
    public static function model($className=__CLASS__)
    {
    	return parent::model($className);
    }
}