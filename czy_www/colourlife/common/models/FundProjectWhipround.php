<?php

/**
 * This is the model class for table "ef_fund_project_whipround".
 *
 * The followings are the available columns in table 'ef_fund_project_whipround':
 * @property string $id
 * @property string $fund_project_id
 * @property string $raiser_id
 * @property string $raiser_mobile
 * @property string $raiser_name
 * @property string $raiser_address
 * @property string $raise_amount
 * @property string $create_time
 * @property string $pay_success_time
 * @property string $inner_order_id
 * @property string $outer_order_id
 * @property integer $state
 */
class FundProjectWhipround extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'ef_fund_project_whipround';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('fund_project_id, raiser_id, raiser_mobile, create_time', 'required'),
            array('state', 'numerical', 'integerOnly'=>true),
            array('fund_project_id, raiser_id, create_time, pay_success_time', 'length', 'max'=>10),
            array('raiser_mobile, raiser_name', 'length', 'max'=>32),
            array('raiser_address', 'length', 'max'=>1000),
            array('raise_amount', 'length', 'max'=>14),
            array('inner_order_id, outer_order_id', 'length', 'max'=>128),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, fund_project_id, raiser_id, raiser_mobile, raiser_name, raiser_address, raise_amount, create_time, pay_success_time, inner_order_id, outer_order_id, state', 'safe', 'on'=>'search'),
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
            'fund_project_id' => '项目ID',
            'raiser_id' => '捐款人ID，customer.id',
            'raiser_mobile' => '捐款人手机号',
            'raiser_name' => '捐款人姓名',
            'raiser_address' => '捐款人地址',
            'raise_amount' => '捐款金额',
            'create_time' => '捐款日期',
            'pay_success_time' => '付款成功日期',
            'inner_order_id' => '内部订单号',
            'outer_order_id' => '外部订单号',
            'state' => '捐款状态。0-未付款, 1-已付款, 2-作废',
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
        $criteria->compare('fund_project_id',$this->fund_project_id,true);
        $criteria->compare('raiser_id',$this->raiser_id,true);
        $criteria->compare('raiser_mobile',$this->raiser_mobile,true);
        $criteria->compare('raiser_name',$this->raiser_name,true);
        $criteria->compare('raiser_address',$this->raiser_address,true);
        $criteria->compare('raise_amount',$this->raise_amount,true);
        $criteria->compare('create_time',$this->create_time,true);
        $criteria->compare('pay_success_time',$this->pay_success_time,true);
        $criteria->compare('inner_order_id',$this->inner_order_id,true);
        $criteria->compare('outer_order_id',$this->outer_order_id,true);
        $criteria->compare('state',$this->state);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return FundProjectWhipround the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}