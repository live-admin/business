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
 * @property float  $bank_pay
 * @property string $note
 * @property string $create_ip
 * @property integer $create_time
 * @property integer $pay_time
 * @property integer $status
 * @property float  $pay_rate
 * @property Customer $customer
 */
class ThirdFees extends CActiveRecord {

    public static $fees_status = array(
        Item::FEES_AWAITING_PAYMENT => "待付款",
        Item::FEES_RECHARGEING => "充值中",
        Item::FEES_TRANSACTION_ERROR => '已付款',
        Item::FEES_TRANSACTION_SUCCESS => "交易成功",
        Item::FEES_TRANSACTION_LACK => "交易失败,红包余额不足",
        Item::FEES_TRANSACTION_FAIL => '交易失败',
        Item::FEES_TRANSACTION_REFUND => '退款',
        Item::FEES_PART_REFUND => '部分退款',
        Item::FEES_CANCEL => "订单已取消",
    );

    /**
     * @var string 模型名
     */
    public $modelName = '第三方交费';
    public $objectLabel = '第三方交费';
    public $objectModel = 'Customer';
    //  public $objectName;
    //public $_customerName;
    public $branch_id;
    //public $car_number;
    //  public $parking_id;
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
    public $cId;

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
        return 'third_fees';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('object_id, customer_id, payment_id, create_time,pay_time, status', 'numerical', 'integerOnly' => true),
            array('sn , pay_sn', 'length', 'max' => 32),
            array('cSn', 'length', 'max' => 80),
            array('model, create_ip', 'length', 'max' => 20),
            array('amount', 'length', 'max' => 10),
            array('callbackUrl', 'length', 'max' => 250),
            array('bank_pay,red_packet_pay,note,user_red_packet,callbackUrl,pay_info', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,branch_id,cId,customer_name,user_red_packet,pay_sn,cSn,sn,mobile,startTime,endTime,communityIds, customer_id, payment_id, amount, note, create_time, status, business_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'payment' => array(self::BELONGS_TO, 'Payment', 'payment_id'),
            'logs' => array(self::HAS_MANY, 'ThirdFeesLog', 'others_fees_id'),
            $this->objectModel => array(self::BELONGS_TO, $this->objectModel, 'object_id'),
            'ThirdFeesAddr' => array(self::BELONGS_TO, 'ThirdFeesAddr', 'object_id'),
            'ThirdFeesSeller' => array(self::BELONGS_TO, 'ThirdFeesSeller', array('cId'=>'cId')),
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
            'cSn' => '第三方订单号',
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
            'cId' => '商户号',
            'mobile' => '手机号码',
            'branch_id' => '管辖部门',
            'red_packet_pay' => '红包抵扣',
            'bank_pay' => '实付',
            'user_red_packet' => '使用红包',
            'pay_rate' => '费率',
            'isUseRed' => '是否用红包',
            'callbackUrl' => '回调地址',
            'pay_sn' => '支付单号',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'communityIds' => '小区',
            'pay_info' => '饭票支付信息',
            'community_uuid'=>'小区'
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
        if ($this->cId){
            $criteria->with[] = 'ThirdFeesAddr';
            $criteria->compare('ThirdFeesAddr.cId',$this->cId);
        }
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

    /*
     * 得到商户名
     */
    public function getCname(){
       // if ($this->cId){
            $re = ThirdFeesSeller::model()->find('cId=:cid', array(':cid' => $this->ThirdFeesAddr->cId));
            if ($re){
                $str = $re->name;
            } else $str = '';
        return $str;
    }

    public function getCustomerName() {
        return empty($this->customer) ? $this->customer_id : (empty($this->customer->name) ? $this->customer->username : $this->customer->name);
    }

    public function getCustomerMobile() {
        return empty($this->customer) ? "" : $this->customer->mobile;
    }
// ICE 接入小区名字
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

            $payment = json_decode($this->payment_info, true);

            //支付方式
            $payStyle = array();

            if(!empty($this->pay_info)){
                $payInfo = json_decode($this->pay_info, true);
                $red_packet_pay = 0;
                foreach ($payInfo as $row){
                    $red_packet_pay += $row['money'];
                }
                $payStyle[] = array('name'=>'红包支付', 'money'=>$red_packet_pay);
            }elseif ($this->red_packet_pay>0){
                $payStyle[] = array('name'=>'红包支付', 'money'=>$this->red_packet_pay);
            }
            if($this->bank_pay>0 && $this->payment_id){
                $paymentModel = Payment::model()->findByPk($this->payment_id);
                if($paymentModel){
                    $payStyle[] = array('name'=>$paymentModel->name, 'money'=>$this->bank_pay);
                }
            }

            $post = array(
                'cid' => $cid,
                'sn' => $sn,
                'cSn' => $cSn,
                'amount' => $this->amount,
                'bankPay' => $this->bank_pay,
                'redPacketPay' => $this->red_packet_pay,
                'payamount' => bcadd ($this->bank_pay, $this->red_packet_pay, 2),
                'createTime' => $this->create_time,
                'status' => $status,
                'payTime' => $this->pay_time,
                'payid' => isset($payment['payid']) ? $payment['payid'] : '',
                'discount' => isset($payment['discount']) ? $payment['discount'] : '',
                'updateTime' => $this->update_time,
                'time' => $time,
                'isUseRed' => $this->isUseRed,
                'sign' => $sign,
                'payStyle' => $payStyle
            );
            if ($this->returnNums >=8){
                $log == true && Yii::log("第三方更新单据回调超过". Item::THIRD_REMOTE_SERVER_CALLBACK_NUM . "次不再通知，sn:{$this->sn}，cSn：{$this->cSn}，商户id：{$cid}，彩之云id：" . Yii::app()->user->id,
                          CLogger::LEVEL_INFO, 'colourlife.core.ThirdFees.callRemoteServerBack');
                return false;
            }
            $json = $re->contentMethod($server_url, $post);
            $l_url = $server_url . '?' .http_build_query($post).'，第三方返回值:' . $json;
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
                $log == true && Yii::log("第三方更新单据回调失败，sn:{$this->sn}，cSn：{$this->cSn}，status：{$status}，商户id：{$cid}，彩之云id：" . Yii::app()->user->id . "，地址：" . $l_url,
                       CLogger::LEVEL_INFO, 'colourlife.core.ThirdFees.callRemoteServerBack');
                return false;
            }
        }
    }

    //修改订单状态只能由指定的人操作
    public function getRole(){
        $username = Yii::app()->user->name;
        if(!empty($username)){
            return $username;
        }

    }

    /**
     * t+0回调
     * @return boolean
     */
    public function wsqRemoteBack(){
    	//状态成功时调用远程数据
    	if ($this->return == false && $this->returnMsg == 1 && ($this->status == Item::FEES_TRANSACTION_ERROR || $this->status == Item::FEES_TRANSACTION_SUCCESS)){
            if ($this->pay_time == 0) {
                $this->pay_time = $this->create_time;
                $this->save();
            }
            if ($this->returnNums >=8){
    			Yii::log("thirdFreesScan第三方更新单据回调超过". Item::THIRD_REMOTE_SERVER_CALLBACK_NUM . "次不再通知，sn:{$this->sn}",
    			CLogger::LEVEL_ERROR, 'colourlife.core.ThirdFees.wsqRemoteBack');
    			return false;
    		}
    		$customerModel = Customer::model()->findByPk($this->customer_id);
    		if (empty($customerModel)){
    			Yii::log("thirdFreesScan第三方更新单据回调，用户不存在。sn:{$this->sn}",CLogger::LEVEL_ERROR, 'colourlife.core.ThirdFees.wsqRemoteBack');
    			return false;
    		}
    		$payment = json_decode($this->payment_info, true);
    		//获取实际交易金额
    		$payamount = bcadd ($this->bank_pay, $this->red_packet_pay, 2);
    		// 扫码支付订单
    		Yii::import('common.services.PayService');
    		$payService = new PayService();
            #################区分新老微商圈---开始################
            if(empty($this->cSn))
            {
                $callBackResult = $payService->wsqCallBack(
                    $this->customer_id,
                    $this->business_id,
                    $this->pay_id,
                    $customerModel->mobile,
                    $this->amount,
                    $payamount,
                    $payment['discount'],
                    $payment['payid'],
                    $this->pay_time,
                    '',
                    ''
                );
            }else{
                $payment_information = json_decode($this->payment_info);
                $callBackResult = $payService->microBusinessCallBack(
                    $this->customer_id,
                    $this->business_id,
                    $this->cSn,
                    $this->sn,
                    $this->amount,
                    $payment_information['pano'],
                    $payment_information['payment_type'],
                    $this->pay_time
                );
            }
            #################区分新老微商圈---结束################

    		$thirdModel = self::model()->findByPk($this->id);
    		$thirdModel->returnNums = $thirdModel->returnNums + 1;
    		if (!empty($callBackResult)){ //回调成功改状态
    			Yii::log("thirdFreesScan回调第三方成功，订单号：".$this->sn.',结果：'.$callBackResult, CLogger::LEVEL_INFO, 'colourlife.core.wsqRemoteBack');
    			$thirdModel->returnMsg = 0;
    			$thirdModel->note = $thirdModel->note . '，第三方成功回调时间：' . time();
    			if ($thirdModel->update()){
    				Yii::log("thirdFreesScan回调第三方更新表成功：订单号：".$this->sn.',返回结果：'.$callBackResult, CLogger::LEVEL_INFO, 'colourlife.core.wsqRemoteBack');
    				return true;
    			}else {
    				Yii::log("thirdFreesScan回调第三方更新表失败：订单号：".$this->sn.',返回结果：'.$callBackResult, CLogger::LEVEL_ERROR, 'colourlife.core.wsqRemoteBack');
    				return false;
    			}
    		}else {
    			$thirdModel->update();
    			Yii::log("thirdFreesScan回调第三方失败：订单号：".$this->sn.'，返回结果：'.$callBackResult, CLogger::LEVEL_ERROR, 'colourlife.core.wsqRemoteBack');
    			return false;
    		}
    	}else {
    		Yii::log("thirdFreesScan回调第三方不满足条件：订单号：".$this->sn, CLogger::LEVEL_ERROR, 'colourlife.core.wsqRemoteBack');
    		return false;
    	}
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function wsqSearch() {
        $criteria = new CDbCriteria;
        $criteria->compare('t.payment_id', $this->payment_id);
        $criteria->with[] = 'ThirdFeesAddr';
        $this->cId = 77;
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
        $criteria->with[] = 'ThirdFeesAddr';
        $criteria->compare('ThirdFeesAddr.cId',$this->cId);

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
}
