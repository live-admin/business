<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/6/7
 * Time: 11:15
 */
class InsureFree extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'insure_free';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id, type, source_id', 'required'),
            array('customer_id, type, create_time, status', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('customer_id, source_id, relation_id, type, date, create_time, status', 'safe', 'on'=>'search'),
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
    			'customer_id' => '用户ID',
    			'type' => '0-前X名 1-达到设定金额',
    			'date' => '前X名免单时间',
    			'relation_id' => '消费订单号',
    			'source_id' => '来源订单号',
    			'status' => '0-初始 1-已使用 2-已失效',
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
    	$criteria->compare('customer_id',$this->customer_id);
    	$criteria->compare('type',$this->type);
    	$criteria->compare('date',$this->date);
    	$criteria->compare('relation_id',$this->relation_id,true);
    	$criteria->compare('source_id',$this->source_id,true);
    	$criteria->compare('status',$this->status);
    	$criteria->compare('create_time',$this->create_time);
    
    	return new CActiveDataProvider($this, array(
    			'criteria'=>$criteria,
    	));
    }
    
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return InsureFree the static model class
     */
    public static function model($className=__CLASS__)
    {
    	return parent::model($className);
    }
}