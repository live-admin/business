<?php
/**
 * This is the model class for table "RedpacketFees".
 * The followings are the available columns in table 'others_fees':
 * @property integer $id
 * @property string $sn
 * @property string $model
 * @property integer $object_id
 * @property integer $customer_id
 * @property integer $payment_id
 * @property string $amount
 * @property float  $bank_pay
 * @property string $note
 * @property string $create_ip
 * @property integer $create_time
 * @property integer $pay_time
 * @property integer $status
 * @property float  $pay_rate
 * @property Customer $customer
 * @update 2015-06-03
 * @by wenda
 */
class RedpacketFees extends CActiveRecord {

    public static $fees_status = array(
        Item::FEES_AWAITING_PAYMENT => "待付款",
        Item::FEES_RECHARGEING => "充值中",
        Item::FEES_TRANSACTION_ERROR => '已付款',
        Item::FEES_TRANSACTION_SUCCESS => "交易成功",
        Item::FEES_TRANSACTION_LACK => "交易失败,红包余额不足",
        Item::FEES_TRANSACTION_FAIL => '交易失败',
        Item::FEES_TRANSACTION_REFUND => '退款',
        Item::FEES_CANCEL => "订单已取消",
    );

    /**
     * @var string 模型名
     */
    public $modelName = '红包充值';
    public $objectLabel = '红包充值';
    public $objectModel = 'Customer';
    public $branch_id;
    public $type_id;
    public $cummunity_id;
    public $period;
    public $room;
    public $build_id;
    public $build;
    public $customer_name;
    public $username;
    public $mobile;
    public $community_name;
    public $startTime;
    public $endTime;
    //回调
    private $return = false;
    //以下字段仅供搜索用
    public $communityIds = array(); //小区
    public $region; //地区
    public $community_id;
    public $pay_sn;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return OthersFees the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'redpacket_fees';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('object_id, customer_id, payment_id, create_time,pay_time, status', 'numerical', 'integerOnly' => true),
            array('sn', 'length', 'max' => 32),
            array('model, create_ip', 'length', 'max' => 20),
            array('amount', 'length', 'max' => 10),
            array('bank_pay,red_packet_pay,note,user_red_packet', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,branch_id,customer_name,user_red_packet,pay_sn,sn,mobile,startTime,endTime,communityIds, customer_id, payment_id, amount, note, create_time, status', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'payment' => array(self::BELONGS_TO, 'Payment', 'payment_id'),
            'logs' => array(self::HAS_MANY, 'RedpacketFeesLog', 'rfl_id'),
            $this->objectModel => array(self::BELONGS_TO, $this->objectModel, 'object_id'),
            'RedpacketFeesAddr' => array(self::BELONGS_TO, 'RedpacketFeesAddr', 'object_id'),
            'pay' => array(self::BELONGS_TO, 'Pay', 'pay_id'),
        );

        return CMap::mergeArray(parent::relations(), $array);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'sn' => '彩之云订单号',
            'object_id' => $this->objectLabel,
            'customer_name' => '用户名',
            'customer_id' => '用户名',
            'payment_id' => '支付方式',
            'amount' => '总金额',
            'note' => '备注',
            'create_ip' => '创建IP',
            'create_time' => '创建时间',
            'pay_time' => '支付时间',
            'objectName' => $this->objectLabel,
            'status' => '状态',
            'community_id' => '小区',
            'mobile' => '手机号码',
            'branch_id' => '管辖部门',
            'red_packet_pay' => '红包抵扣',
            'bank_pay' => '实付',
            'user_red_packet' => '使用红包',
            'pay_rate' => '费率',
            'pay_sn' => '支付单号',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'communityIds' => '小区'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('t.payment_id', $this->payment_id);
        $criteria->with[] = 'RedpacketFeesAddr';
//        if ($this->cId != '') {
//            $criteria->compare('RedpacketFeesAddr.cId', $this->room, true);
//        }

        if ($this->startTime != '') {
            $criteria->compare("`t`.create_time", ">= " . strtotime($this->startTime . " 00:00:00"));
        }
        if ($this->endTime != '') {
            $criteria->compare("`t`.create_time", "<= " . strtotime($this->endTime . " 23:59:59"));
        }

//        $employee = Employee::model()->findByPk(Yii::app()->user->id);
//        $branchIds = $employee->mergeBranch;
//        //选择的组织架构ID
//        if ($this->branch_id != '')
//            $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
//        else if (!empty($this->communityIds)) //如果有小区
//            $community_ids = $this->communityIds;
//        else if ($this->region != '') //如果有地区
//            $community_ids = Region::model()->getRegionCommunity($this->region, 'id');
//        else {
//            $community_ids = array();
//            foreach ($branchIds as $branchId) {
//                $data = Branch::model()->findByPk($branchId)->getBranchAllIds('Community');
//                $community_ids = array_unique(array_merge($community_ids, $data));
//            }
//        }
        // $criteria->addInCondition('RedpacketFeesAddr.community_id', $community_ids);
        $criteria->with[] = 'pay';
        $criteria->compare('`t`.id', $this->id);
        $criteria->compare('`t`.sn', $this->sn, true);
        $criteria->compare('pay.pay_sn', $this->pay_sn, true);
        $criteria->compare('`t`.customer_id', $this->customer_id);
        $criteria->compare('`t`.object_id', $this->object_id);
        //  $criteria->compare('`t`.model','PropertyActivity');
        $criteria->compare('`t`.amount', $this->amount, true);
        $criteria->compare('`t`.note', $this->note, true);
        $criteria->compare('`t`.create_time', $this->create_time);
        $criteria->compare('`t`.status', $this->status);
        $criteria->compare('`t`.pay_time', $this->pay_time);
        $criteria->compare('`t`.update_time', $this->update_time);
        if ($this->customer_name || $this->mobile) {
            $criteria->with[] = 'customer';
            $criteria->compare('customer.name', $this->customer_name, true);
            $criteria->compare('customer.mobile', $this->mobile, true);
        }
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    protected function beforeSave() {
        if ($this->isNewRecord) {
            /**
             * @var Payment $payment
             */
            if (!empty($this->payment_id) && $payment = Payment::model()->findByPk($this->payment_id)) {
                $this->pay_rate = $payment->rate;
            }
        }
        return parent::beforeSave();
    }

    public function getPaymentName() {
        return empty($this->payment) ? '' : $this->payment->name;
    }

    public function getPaymentNames() {
        if (empty($this->payment)) {
            return "无";
        } else {
            if (!empty($this->payment->name)) {
                return $this->payment->name;
            } else {
                return "无";
            }
        }
    }

    public function getCustomerName() {
        return empty($this->customer) ? $this->customer_id : (empty($this->customer->name) ? $this->customer->username : $this->customer->name);
    }

    public function getCustomerMobile() {
        return empty($this->customer) ? "" : $this->customer->mobile;
    }
//      ICE 接入小区名字
    public function getCommunityName() {
        // $model_string = $this->objectModel;
        // if (!empty($this->$model_string->community))
        //     return $this->$model_string->community->name;
        // else
        //     return '';

        //      ICE 接入小区名字
        $model_string = $this->objectModel;
        if (!empty($this->$model_string->community_id)) {
            $communityName = ICECommunity::model()->findByPk($this->$model_string->community_id);
            if (!empty($communityName)) {
                return $communityName['name'];
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function getBranchId() {
        $model_string = $this->objectModel;
        return empty($this->$model_string->community) ? 0 : $this->$model_string->community->branch_id;
    }

    public function getMyMobile() {
        return empty($this->customer) ? '' : $this->customer->mobile;
    }

    static public function getStatusNames() {
        return CMap::mergeArray(array('' => '全部'), self::$fees_status);
    }

    public function getStatusName($html = false) {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$fees_status[$this->status];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }

    public function getParkStatus() {
        $parkStatus = array(
            Item::FEES_AWAITING_PAYMENT => "待付款",
            // Item::FEES_RECHARGEING => "充值中",
            Item::FEES_TRANSACTION_FAIL => '交易失败',
            Item::FEES_TRANSACTION_LACK => '交易失败,余额不足',
            Item::FEES_TRANSACTION_ERROR => '已付款,未续卡',
            Item::FEES_TRANSACTION_SUCCESS => "已续卡",
            Item::FEES_CANCEL => "订单已取消",
        );

        return CMap::mergeArray(array('' => '全部'), $parkStatus);
    }

    public function getFeesSn() {
        return '单据号:' . $this->sn;
    }

    public function behaviors() {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
            'IpBehavior' => array(
                'class' => 'common.components.behaviors.IpBehavior',
                'createAttribute' => 'create_ip',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function getCustomerAdress() {
        return isset($this->customer) ? (isset($this->customer->community) ? $this->customer->community->name . (
                        isset($this->customer->build) ? $this->customer->build->name : '') . $this->customer->room : '') : '';
    }

    public function getCustomerHtml() {
        if (empty($this->customer))
            return "";
        return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => '业主:' . $this->customer->name . ',联系电话:' . $this->customer->mobile), $this->customer->name);
    }

    public function getPaymentList() {
        $model = Payment::model()->online()->findAll();
        if (isset($model)) {
            $payment_list = array();
            foreach ($model as $list) {
                $payment_list[''] = "全部";
                $payment_list[$list->id] = $list->name;
            }
            return $payment_list;
        } else {
            return "";
        }
    }

    public function getStatusList() {
        if ($this->status == Item::FEES_AWAITING_PAYMENT) {
            $arr = array(Item::FEES_TRANSACTION_ERROR => '已付款');
        } elseif ($this->status == Item::FEES_TRANSACTION_ERROR) {
            $arr = array(Item::FEES_TRANSACTION_REFUND => '退款');
        } elseif ($this->status == Item::FEES_TRANSACTION_FAIL) {
            $arr = array(Item::FEES_TRANSACTION_REFUND => '退款', Item::FEES_TRANSACTION_SUCCESS => '交易成功');
        } elseif ($this->status == Item::FEES_TRANSACTION_REFUND) {
            $arr = array(Item::FEES_AWAITING_PAYMENT => '待付款');
        } else {
            $arr = array('' => '');
        }
        return $arr;
    }

    //查询订单商品的积分总数,为了兼容内部采购订单和业主订单
    public function getOrderAllIntegral() {
        $discount = 0;
        return intval($discount);
    }

    public function save1($runValidation = true, $attributes = NULL, $ok = null) {
        if (!$this->validate())
            return false;
        $model = OthersFees::model()->findByPk($this->id);
        $logModel = new OthersFeesLog();
        $state = false;

        if ($stateModel = OthersFees::model()->findByPk($this->id)) {
            $state = ( $stateModel->status == 1 ? true : false );
        }

        $logModel->note = empty($this->note) ? "自动备注,状态" : $this->note;
        $logModel->user_model = "Employee";
        $logModel->user_id = Yii::app()->user->id;
        $logModel->others_fees_id = $this->id;

        $logModel->status = $this->status;

        $changeStatus = $model->updateByPk($this->id, array('status' => $this->status,)); //修改状态

        if ($changeStatus && $state) {
            if ($this->checkIsPropertyFees()) {
                //物业费订单
                Yii::app()->sms->sendPropertyPaymentMessage('paymentSuccess', $stateModel->sn, 'propertyPayment');
            }

            if ($this->checkIsParkingFees()) {
                //停车费订单
                Yii::app()->sms->sendParkingFeesMessage('continuedSuccessCards', $stateModel->sn);
            }
        }
        if ($changeStatus && $logModel->save())
            return true;
        else
            return false;
    }

    //记录日志不能修改为下单或无效状态
    public function checkStatus($attribute, $params) {
        //现在只判断是否为空，以后可能判断在某一阶段不能修改为某个状态。
        if ($this->status == '') {
            $this->addError($attribute, '状态错误');
        }
    }
}
