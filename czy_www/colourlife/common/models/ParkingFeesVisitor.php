<?php
/**
 * 临时停车缴费信息
 * User: Joy
 * Date: 2015/8/30 14:13
 */
class ParkingFeesVisitor extends CActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ColorcloudCommunity the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'parking_fees_visitor';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id', 'numerical', 'integerOnly' => true),
            array('park_type, park_id, park_name, car_number, out_trade_no, third_park_id, enter_time, park_time, trade_status, out_result', 'required', 'on' => 'create, update'),
            array('park_type, park_id, park_name, car_number, out_trade_no, third_park_id, enter_time, park_time, trade_status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'park_type' => '停车场类型',
            'park_name' => '停车场名称',
            'car_number' => '车牌号',
            'out_trade_no' => '账单号',
            'enter_time' => '入场时间',
            'park_time' => '停车时长',
            'trade_status' => '交易状态',
            'notify_times' => '通知次数',
            'notify_date' => '通知时间'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('park_type', $this->park_type, true);
        $criteria->compare('car_number', $this->car_number, true);
        $criteria->compare('out_trade_no', $this->out_trade_no, true);
        $criteria->compare('trade_status', $this->trade_status, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}