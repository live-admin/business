<?php

/**
 * This is the model class for table "order_send_present".
 *
 * The followings are the available columns in table 'order_send_present':
 * @property integer $id
 * @property integer $order_id
 * @property integer $type
 * @property integer $num
 */
class OrderSendPresent extends CActiveRecord
{

    /**
     * @var string 模型名
     */
    public $modelName = 'OrderSendPresent';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return OrderSendPresent the static model class
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
        return 'order_send_present';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('order_id, type, num', 'numerical', 'integerOnly' => true),
            array('type, num', 'length', 'max' => 10),

            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, order_id, num, type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'order_id' => '订单号',
            'type' => '赠送类型',
            'num' => '赠送数量',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('order_id', $this->order_id);
        $criteria->compare('type', $this->type);
        $criteria->compare('num', $this->num);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array();
    }

}
