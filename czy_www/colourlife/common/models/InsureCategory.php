<?php
/**
 * Created by PhpStorm.
 * User: Joy
 * Date: 2016/6/7
 * Time: 11:15
 */
class InsureCategory extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'insure_category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('insure_code, name, desc', 'required'),
            array('insure_code, insure_month, create_time, status, order', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, insure_code, insure_month, create_time, status, insure_sum, fee, vip_fee, desc, order', 'safe', 'on'=>'search'),
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
    			'insure_code' => '保险公司标示',
    			'name' => '名称',
    			'insure_month' => '保险期限（/月）',
    			'insure_sum' => '保额',
    			'fee' => '购买价格',
    			'vip_fee' => '会员购买价格',
    			'desc' => '说明',
    			'status' => '状态 1-生效 2-失效',
    			'order' => '排序值',
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
    	$criteria->compare('insure_code',$this->insure_code);
    	$criteria->compare('name',$this->name,true);
    	$criteria->compare('insure_month',$this->insure_month);
    	$criteria->compare('insure_sum',$this->insure_sum,true);
    	$criteria->compare('fee',$this->fee,true);
    	$criteria->compare('vip_fee',$this->vip_fee,true);
    	$criteria->compare('desc',$this->desc,true);
    	$criteria->compare('status',$this->status);
    	$criteria->compare('order',$this->order);
    	$criteria->compare('create_time',$this->create_time);
    
    	return new CActiveDataProvider($this, array(
    			'criteria'=>$criteria,
    	));
    }
    
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return InsureCategory the static model class
     */
    public static function model($className=__CLASS__)
    {
    	return parent::model($className);
    }
}