<?php

/**
 * This is the model class for table "property_free_log".
 *
 * The followings are the available columns in table 'property_free_log':
 * @property integer $id
 * @property integer $customer_id
 * @property string $room_id
 * @property integer $create_time
 */
class PropertyFeeLog extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'property_fee_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id, room_id', 'required'),
            array('customer_id, create_time', 'numerical', 'integerOnly' => true),
            array('room_id', 'length', 'max' => 255),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, customer_id, room_id, create_time', 'safe', 'on' => 'search'),
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
            'id' => '主键',
            'customer_id' => '业主ID',
            'room_id' => '彩之云ERP房间ID',
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('customer_id', $this->customer_id);
        $criteria->compare('room_id', $this->room_id, true);
        $criteria->compare('create_time', $this->create_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PropertyFeeLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 获得单元的收费记录详情
     * @param $tollid //收据编号ID
     * @return mixed
     */
    public function getPayLogdetail($tollid)
    {
        if (empty($tollid)) {
            $this->addError('id', "获取收费记录详情失败！");
        } else {
            //引入彩之云的接口
            Yii::import('common.api.ColorCloudApi');
            //实例化
            $coloure = ColorCloudApi::getInstance();
            $result = $coloure->callGetPayLogdetial($tollid);
            if (!isset($result['data'])) {
                //如果未找到预缴费
                $this->addError('id', "获取收费记录详情失败！");
            } else {
                return $result['data'];
            }
        }
    }


    /**
     * 获得单元的收费记录
     * @param $unitid
     * @return mixed
     */
    public function getPayLog($unitid, $pageSize, $pageIndex)
    {
        if (empty($unitid)) {
            $this->addError('id', "获取收费记录失败！");
        } else {
            //引入彩之云的接口
            Yii::import('common.api.ColorCloudApi');
            //实例化
            $coloure = ColorCloudApi::getInstance();
            $result = $coloure->callGetPayLog($unitid, $pageSize, $pageIndex);

            return $result;
        }
    }

    //创建日志
    static public function createFeeLog($customer_id, $room_id)
    {
        if(empty($customer_id) || empty($room_id))
            return false;

        $feeLog = new self();
        $feeLog->customer_id = intval($customer_id);
        $feeLog->room_id = $room_id;
        if ($feeLog->save()) {
            Yii::log('物业费回调彩之云记录日志：' . '业主ID号:' . $customer_id . '房间号:' . $room_id, CLogger::LEVEL_INFO, 'colourlife.core.PropertyFeelog.createFeeLog');
            return true;
        } else
            return false;
    }

    public function behaviors()
    {
        return array(

            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

}
