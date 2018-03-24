<?php

/**
 * 停车场信息MODEL
 * Class ParkingLot
 * @author Joy
 * @date 2015-08-28
 */
class ParkingLot extends CActiveRecord
{

    /**
     * @var string 模型名
     */
    public $modelName = '停车场';

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
        return 'parking_lot';
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
            array('name, address, city, lng, lat, third_type, third_park_id, state', 'required'),
            array('name', 'length', 'encoding' => 'UTF-8', 'max' => 32, 'on' => 'create, update'),
            array('city', 'length', 'encoding' => 'UTF-8', 'max' => 15, 'on' => 'create, update'),
            array('address', 'length', 'encoding' => 'UTF-8', 'max' => 64, 'on' => 'create, update'),
            array('id, name, address, lng, lat', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '停车场名称',
            'city' => '所在城市',
            'address' => '地址',
            'lng' => '经度',
            'lat' => '纬度',
            'third_type' => '停车类别',
            'third_park_id' => '停车关联ID',
            'state' => '状态',
            'create_time' => '创建时间',
            'update_time' => '更新时间'
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('third_type', $this->third_type, true);
        $criteria->compare('state', $this->state, true);
        $criteria->compare('create_time', $this->create_time, true);
        $criteria->compare('update_time', $this->update_time, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * 获取格美特停车场列表
     * @return array
     * @throws CException
     */
    public function getGemeiteParkingList()
    {
        Yii::import('common.api.GemeiteApi');
        $ParkingList = GemeiteApi::getInstance()->commQuery();
        return $ParkingList;
    }

    /**
     * 获取艾科停车场列表
     * @return array
     * @throws CException
     */
    public function getAikeParkingList()
    {
        Yii::import('common.api.AikeApi');
        $list = AikeApi::getInstance()->getAllParkingInfo();

        $ParkingList = array();
        if ($list) {
            foreach ($list['parkList'] as $row) {
                $ParkingList[$row['parkCode'].'/'.$row['parkName']] = 'I - '.$row['parkName'];
            }
        }

        return $ParkingList;
    }

    /**
     * 获取汉王停车场列表
     * @return array
     * @throws CException
     */
    public function getHanwangParkingList()
    {
        Yii::import('common.api.HanwangApi');
        $list = HanwangApi::getInstance()->getAllParkingInfo();

        $ParkingList = array();
        if ($list) {
            foreach ($list['list'] as $row) {
                $ParkingList[$row['parkID'].'/'.$row['parkName']] = 'H - '.$row['parkName'];
            }
        }

        return $ParkingList;
    }

    public function getStateNames()
    {
        return array(0=>'启用', 1=>'禁用');
    }

    /**
     * 第三方停车系统
     * @return array
     */
    public function getThirdTypeNames()
    {
        return array(
            Item::PARKING_TYPE_GEMEITE => '格美特',
            Item::PARKING_TYPE_AIKE => '艾科',
            Item::PARKING_TYPE_HANWANG => '汉王停车'
        );
    }

    /**
     * 获取第三方停车系统名称
     * @param $id
     * @return string
     */
    public function getThirdTypeName()
    {
        $names = $this->getThirdTypeNames();

        return isset($names[$this->third_type]) ? $names[$this->third_type] : '';
    }

    /**
     * 获取状态值
     * @param $id
     * @return string
     */
    public function getStateName()
    {
        $names = $this->getStateNames();

        return isset($names[$this->state]) ? $names[$this->state] : '';
    }

    /**
     * 根据坐标获取附近停车场
     * @param $lat
     * @param $lng
     * @return mixed
     */
    public function getParkingList($lat, $lng)
    {
        //$sql='SELECT * FROM `parking_lot` WHERE `state`=0 AND `lat` > '.$lat.'-1 AND `lat` < '.$lat.'+1 AND `lng` > '.$lng.'-1 AND `lng` < '.$lng.'+1 ORDER BY ACOS(SIN(('.$lat.' * 3.1415) / 180 ) *SIN((`lat` * 3.1415) / 180 ) +COS(('.$lat.' * 3.1415) / 180 ) * COS((`lat` * 3.1415) / 180 ) * COS(('.$lng.'* 3.1415) / 180 - (`lng` * 3.1415) / 180 ) ) * 6380 ASC LIMIT 10';
        $sql='SELECT * FROM `parking_lot` ORDER BY ACOS(SIN(('.$lat.' * 3.1415) / 180 ) *SIN((`lat` * 3.1415) / 180 ) +COS(('.$lat.' * 3.1415) / 180 ) * COS((`lat` * 3.1415) / 180 ) * COS(('.$lng.'* 3.1415) / 180 - (`lng` * 3.1415) / 180 ) ) * 6380 ASC LIMIT 10';

        return Yii::app()->db->createCommand($sql)->query();
    }

    /**
     * 将秒转换车时间格式
     * @param $sec
     * @return string
     */
    public function secToTime($sec)
    {
        $sec = round($sec/60);
        if ($sec >= 60){
            $hour = floor($sec/60);
            $min = $sec % 60;
            $res = $hour.' 小时 ';
            $min != 0  &&  $res .= $min.' 分';
        }
        else{
            $res = $sec.' 分钟';
        }
        return $res;
    }
}