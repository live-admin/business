<?php

/**
 * This is the model class for table "others_fees".
 *
 * The followings are the available columns in table 'others_fees':
 * @property integer $id
 * @property string $sn
 * @property string $model
 * @property integer $object_id
 * @property integer $customer_id
 * @property integer $payment_id
 * @property string $amount
 * @property string $note
 * @property string $create_ip
 * @property integer $create_time
 * @property integer $status
 */
class OthersParkingFeesVisitor extends OthersFees
{
    /**
     * @var string 模型名
     */
    public $modelName = '缴临时停车费';
    public $objectLabel = '月卡停车费';
    public $objectModel = 'ParkingFeesVisitor';
    public $pay_sn;

    static $fees_status = array(
        Item::FEES_AWAITING_PAYMENT => "待付款",
        Item::FEES_RECHARGEING => "充值中",
        Item::FEES_TRANSACTION_ERROR => '已付款,未通知',
        Item::FEES_TRANSACTION_LACK =>'红包余额不足',
        Item::FEES_TRANSACTION_REFUND => '退款',
        Item::FEES_TRANSACTION_SUCCESS => "已通知",
        Item::FEES_CANCEL => "订单已取消",
    );

    public $mobile;
    public $username;
    public $community_id;
    public $startTime;
    public $endTime;
    public $out_trade_no;
    public $park_type;
    public $park_name;
    public $third_park_id;
    public $fee_number;
    public $fee_unit;
    public $car_number;


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        $array = array(
          //  array('build_id,community_id,mobile,room', 'required'),
            array('car_number', 'numerical', 'integerOnly' => true),
            array(
                'region,communityIds,username,endTime,startTime,build_name,pay_sn,type_id,mobile,community_name,build_id,room,plate_no,community_id, parking_card_number,customer_id',
                'safe',
                'on' => 'search'
            ),
        );
        return CMap::mergeArray(parent::rules(), $array);
    }


    public function relations()
    {
        $array = array(
            //'community' => array(self::BELONGS_TO, 'Community', 'community_id')
        );

        return CMap::mergeArray(parent::relations(), $array);
    }

    public function attributeLabels()
    {
        $array = array(
            'id' => 'ID',
            'mobile' => '电话号码',
            'username' => '用户名',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'pay_sn'=>'支付单号',
            'park_time' => '停车时长',
            'enter_time' => '入场时间',
            'car_number' => '车牌号',
            'out_trade_no' => '缴费订单号',
            'park_type' => '缴费商家',
            'park_id' => '停车场ID',
            'park_name' => '停车场',
            'third_park_id' => '第三方停车场ID',
            'fee_unit' => '缴费标准类型',
            'fee_number' => '缴费数量',
        );

        return CMap::mergeArray(parent::attributeLabels(), $array);
    }

    /**
     * 车牌号
     * @return string
     */
    public function getCarNumber()
    {
        $model = $this->objectModel;
        return empty($this->$model->car_number) ? '' : $this->$model->car_number;
    }

    public  function getThirdParkName()
    {
        $model = $this->objectModel;
        $parkType = array(
            Item::PARKING_TYPE_GEMEITE => '格美特',
            Item::PARKING_TYPE_AIKE => '艾科',
            Item::PARKING_TYPE_HANWANG => '汉王',
        );

        return empty($this->$model->park_type) ? '' : $parkType[$this->$model->park_type];
    }

    public function getParkTime()
    {
        $model = $this->objectModel;
        return ParkingLot::model()->secToTime($this->$model->park_time);
    }

    public function getEnterTime()
    {
        $model = $this->objectModel;
        return empty($this->$model->enter_time) ? '' : date('Y-m-d H:i:s', $this->$model->enter_time);
    }

    /**
     * 停车订单
     * @return string
     */
    public function getThirdOrderId()
    {
        $model = $this->objectModel;
        return empty($this->$model->out_trade_no) ? '0' : $this->$model->out_trade_no;
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('model', $this->objectModel, false); //设置条件

        $criteria->compare('sn', $this->sn, true);
        $criteria->compare('`t`.status', $this->status);
        $criteria->compare('payment_id', $this->payment_id);
        $criteria->addInCondition('`t`.status', $this->getMyStatusList());

        if ($this->customer_id != '' || $this->mobile != '' || $this->username != '') {
            $criteria->with[] = 'customer';
            if ($this->customer_id != '') {

                $criteria->compare('customer.name', $this->customer_id, true);
            }
            if ($this->mobile != '') {
                $criteria->compare('customer.mobile', $this->mobile, true);
            }

            if ($this->username != '') {
                $criteria->compare('customer.username', $this->username, true);
            }

        }

        if ($this->car_number != '') {
            $criteria->with[] = $this->objectModel;
            $criteria->compare($this->objectModel . '.car_number', $this->car_number, true);
        }

        if ($this->startTime != '') {
            $criteria->compare("create_time", ">=" . strtotime($this->startTime));
        }

        if ($this->endTime != '') {
            $criteria->compare("create_time", "< " . strtotime($this->endTime));
        }

        if ($this->pay_sn!="") {
            $pay=Pay::model()->getModel($this->pay_sn);
            if(!empty($pay)){
                $pay_id=$pay->id;
                $criteria->compare("`pay_id`",$pay_id);
            }else{
                $criteria->compare("`pay_id`","-1");
            }
        }
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => '`t`.create_time desc',
            )
        ));
    }

    public function getBranchName()
    {
        if (isset($this->customer)) {
            if (isset($this->customer->community)) {
                if (isset($this->customer->community->branch)) {
                    //return $this->customer->community->branch->name;
                    return implode(' ', $this->getMyBranch($this->customer->community->branch->id));
                }
            }
        }
    }

    public function getRegionName()
    {
        if (isset($this->customer)) {
            if (isset($this->customer->community)) {
                if (isset($this->customer->community->region)) {
                    //return $this->customer->community->region->name;
                    return $this->myRegion($this->customer->community->region->id);
                }
            }
        }
        return "";
    }

    /*
     * 获取小区名
     */
    public function getCommunityName()
    {
        if (isset($this->customer)) {
            $community_id =$this->customer->community_id;
            return Community::model()->findByPk($community_id)->name;
        }
        return "";
    }

    public function myRegion($id)
    {
        return implode(' ', F::getRegion($id));
    }

    public function getMyBranch($id)
    {
        return Branch::model()->getAllBranch($id);
    }

    public function getCommunityHtml()
    {
        return CHtml::tag(
            'span',
            array(
                'rel' => 'tooltip',
                'data-original-title' => '地域:' . $this->regionName . '  部门:' . $this->branchName
            ),
            $this->communityName
        );
    }

    public function getParkStatusList()
    {
        if ($this->status == Item::FEES_AWAITING_PAYMENT) {
            $arr = array(Item::FEES_TRANSACTION_ERROR => '已付款');
        } elseif ($this->status == Item::FEES_TRANSACTION_ERROR) {
            $arr = array(Item::FEES_TRANSACTION_SUCCESS => '已续卡', Item::FEES_TRANSACTION_REFUND => '退款');
        } elseif ($this->status == Item::FEES_TRANSACTION_FAIL) {
            $arr = array(Item::FEES_TRANSACTION_REFUND => '退款');
        } elseif ($this->status == Item::FEES_TRANSACTION_REFUND) {
            $arr = array(Item::FEES_AWAITING_PAYMENT => '待付款');
        } else {
            $arr = array('' => '');
        }
        return $arr;
    }

    public function getStatusName($html = false)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$fees_status[$this->status];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }

    //给search用的状态
    public function getMyStatusList()
    {
        $return = array();
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Awaiting')) {
            $return[] = Item::FEES_AWAITING_PAYMENT;
        }
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Rechargeing')) {
            $return[] = Item::FEES_RECHARGEING;
        }
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Success')) {
            $return[] = Item::FEES_TRANSACTION_SUCCESS;
        }
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Payment')) {
            $return[] = Item::FEES_TRANSACTION_ERROR;
        }
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Refund')) {
            $return[] = Item::FEES_TRANSACTION_REFUND;
        }
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Cancel')) {
            $return[] = Item::FEES_CANCEL;
        }

        if(Yii::app()->user->checkAccess('op_backend_parkingFees_redFail')){
            $return[] = Item::FEES_TRANSACTION_LACK;
        }
        return $return;
    }

    //取得搜索显示用的状态
    public function getMyStatusListName()
    {
        $return = array(''=>'全部');
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Awaiting')) {
            $return[Item::FEES_AWAITING_PAYMENT] = self::$fees_status[Item::FEES_AWAITING_PAYMENT];
        }
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Rechargeing')) {
            $return[Item::FEES_RECHARGEING] = self::$fees_status[Item::FEES_RECHARGEING];
        }
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Success')) {
            $return[Item::FEES_TRANSACTION_SUCCESS] = self::$fees_status[Item::FEES_TRANSACTION_SUCCESS];
        }
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Payment')) {
            $return[Item::FEES_TRANSACTION_ERROR] = self::$fees_status[Item::FEES_TRANSACTION_ERROR];
        }
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Refund')) {
            $return[Item::FEES_TRANSACTION_REFUND] = self::$fees_status[Item::FEES_TRANSACTION_REFUND];
        }
         if(Yii::app()->user->checkAccess('op_backend_parkingFees_redFail')){
            $return[Item::FEES_TRANSACTION_LACK] = self::$fees_status[Item::FEES_TRANSACTION_LACK];
        }
        if (Yii::app()->user->checkAccess('op_backend_parkingFees_Cancel')) {
            $return[Item::FEES_CANCEL] = self::$fees_status[Item::FEES_CANCEL];
        }

        return $return;
    }

}
