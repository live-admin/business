<?php

/**
 * This is the model class for table "power_fees".
 *
 * The followings are the available columns in table 'power_fees':
 * @property integer $id
 * @property integer $community_id
 * @property string $build
 * @property string $room
 * @property string $customer_name
 */
class PowerFees extends CActiveRecord
{
    public $modelName = '商铺买电';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PropertyFees the static model class
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
        return 'power_fees';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('community_id', 'numerical', 'integerOnly' => true),
            array('customer_name', 'length', 'max' => 32),
            array('customer_name', 'filter', 'filter' => 'trim'),
            array('community_id,meter,meter_address,customer_name', 'required','on' => 'create'),
            //array('community_id', 'compare', 'compareValue' => '0', 'operator' => '>', 'message' => '你输入的数据不正确'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, community_id,interface_order,recharge_code, meter,meter_address,customer_name', 'safe', 'on' => 'search'),
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
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'community_id' => '小区',
            'build' => '楼栋',
            'room' => '房间号',
            'meter' => '电表号',
            'meter_address' => '电表地址',
            'customer_name' => '电表签约姓名',
            'interface_order' => '接口订单号',
            'recharge_code' => '购电码',
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
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('build', $this->build, true);
        $criteria->compare('room', $this->room, true);
        $criteria->compare('meter', $this->meter);
        $criteria->compare('interface_order', $this->interface_order);
        $criteria->compare('recharge_code', $this->recharge_code);
        $criteria->compare('customer_name', $this->customer_name, true);



        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

   


    static public function updateStarResult($id,$token,$star_order_no)
    {
        if(empty($id) || empty($token) || empty($star_order_no))
            return false;

        $power = self::model()->findByPk($id);
        $power->recharge_code = $token;
        $power->interface_order = $star_order_no;
        if ($power->save()) {
            Yii::log('商铺买电更新购电码和订单记录日志：' . '购电码:' . $token . '购电Star订单:' . $star_order_no, CLogger::LEVEL_INFO, 'colourlife.core.PowerFees.updateStarResult');
            return true;
        } else
            return false;
    }




}
