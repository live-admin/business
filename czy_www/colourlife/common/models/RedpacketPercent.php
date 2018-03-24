<?php
/**
 * @红包充值
 * This is the model class for table "others_fees".
 * @date 2015-06-02
 * @by wenda
 */
class RedpacketPercent extends CActiveRecord {

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
        return 'redpacket_fees_percent';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('value, price, time', 'numerical', 'integerOnly' => true),
            array('value, price', 'length', 'max' => 8),
            array('percent', 'length', 'max' => 5),
            array('value, price', 'safe'),
            array('id,value, price, time, status, percent', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'value' => '面值',
            'price' => '实际充值金额',
            'percent' => '充值折扣',
            'status' => '状态',
            'time' => '结束时间'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;
        $criteria->compare('t.payment_id', $this->payment_id);
        $criteria->with[] = 'ThirdFeesAddr';
        if ($this->cId != '') {
            $criteria->compare('ThirdFeesAddr.cId', $this->room, true);
        }

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
        // $criteria->addInCondition('ThirdFeesAddr.community_id', $community_ids);
        $criteria->with[] = 'pay';
        $criteria->compare('`t`.id', $this->id);
        $criteria->compare('`t`.sn', $this->sn, true);
        $criteria->compare('pay.pay_sn', $this->pay_sn, true);
        $criteria->compare('`t`.customer_id', $this->customer_id);
        $criteria->compare('`t`.object_id', $this->object_id);
        //  $criteria->compare('`t`.model','PropertyActivity');
        $criteria->compare('`t`.amount', $this->amount, true);
        $criteria->compare('`t`.note', $this->note, true);
        $criteria->compare('`t`.cSn', $this->cSn);
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

    /**
     * 返回 调用返回url 回调已去掉 20150413
     * */
    public function callRemoteServerBack($log = true)
    {   
        //状态成功时调用远程数据
        if ($this->return == false && $this->callbackUrl && $this->pay_time && ($this->status == Item::FEES_TRANSACTION_ERROR || $this->status == Item::FEES_TRANSACTION_SUCCESS)){
            $re = new PublicFunV23();
            //验证网址
            if (!$re->validateURL($this->callbackUrl)) return false;
            $re->typeConnect = 'curl';
            $server_url = $this->callbackUrl;
            //商户号
            $cid = $this->ThirdFeesAddr->cId;
            $time = date('Y-m-d H:i:s');
            $sn = $this->sn;
            //商户sn
            $cSn = $this->cSn;
            //规则：MD5（加密私钥bno参数值bsecret参数值userid参数值username参数值mobile
            //商户secret
            $seller = ThirdFeesSeller::model()->find('cId=:cid', array(':cid'=>$cid));
            if (empty($seller)) return false;
            if (empty($seller->secret)) return false;
            $secret = $seller->secret;
            $md5 = 'secret' . $secret . 'cid' . $cid . 'sn' . $sn . 'cSn' . $cSn . 'secret' . $secret;
            $sign = md5($md5);
            if ($this->status == 1 || $this->status == 99) $status = 1; else $status = 0;
            $post = array(
                'cid' => $cid,
                'sn' => $sn,
                'cSn' => $cSn,
                'amount' => $this->amount,
                'bankPay' => $this->bank_pay,
                'redPacketPay' => $this->red_packet_pay,
                'createTime' => $this->create_time,
                'status' => $status,
                'payTime' => $this->pay_time,
                'updateTime' => $this->update_time,
                'time' => $time,
                'isUseRed' => $this->isUseRed,
                'sign' => $sign
            );
            if ($this->returnNums >=8){
                $log == true && Yii::log("第三方更新单据回调超过". Item::THIRD_REMOTE_SERVER_CALLBACK_NUM . "次不再通知，sn:{$this->sn}，cSn：{$this->cSn}，商户id：{$cid}，彩之云id：" . Yii::app()->user->id,
                          CLogger::LEVEL_INFO, 'colourlife.core.ThirdFees.callRemoteServerBack');
                return false;
            }
            $json = $re->contentMethod($server_url, $post);
            $l_url = $server_url . '，第三方返回值:' . $json;
            $json = json_decode($json);
            $re = self::model()->findByPk($this->id);
            $re->returnNums = $re->returnNums + 1;
            if (!empty($json->success) && $re->returnMsg && $json->success == true){
                $re->returnMsg = '0';
                $re->note = $re->note . '，第三方成功回调时间：' . $time;
                if ($re->update()) {
                    $log == true && Yii::log("第三方更新单据回调成功，sn:{$this->sn}，cSn：{$this->cSn}，商户id：{$cid}，彩之云id：" . Yii::app()->user->id . "，地址：" . $l_url,
                          CLogger::LEVEL_INFO, 'colourlife.core.ThirdFees.callRemoteServerBack');
                    return true;
                } else return false;
            } else {
                $re->update();
                $log == true && Yii::log("第三方更新单据回调失败，sn:{$this->sn}，cSn：{$this->cSn}，商户id：{$cid}，彩之云id：" . Yii::app()->user->id . "，地址：" . $l_url, 
                       CLogger::LEVEL_INFO, 'colourlife.core.ThirdFees.callRemoteServerBack');
                return false;
            }
        }
    }
}
