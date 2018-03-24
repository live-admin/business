<?php

/**
 * This is the model class for table "others_fees".
 *
 * The followings are the available columns in table 'others_fees':
 * @property integer $id
 * @property string $cardid
 * @property integer $cardnum
 * @property string $game_userid
 * @property string $game_area
 * @property string $game_srv
 * @property float $income_price
 * @property float $expend_price
 * @property integer $type
 */
class OthersVirtualRecharge extends OthersFees
{
    /**
     * @var string 模型名
     */
    public $modelName = '虚拟充值';
    public $objectLabel = '虚拟充值';
    public $objectModel = 'VirtualRecharge';
    public $type;
    public $account;
    public $phone;
    public $customer_name;
    public $startTime;
    public $endTime;
    public $pay_sn;
    static $virtualType = array(
        Item::VIRTUAL_MOBILE_TYPE => "手机充值",
        Item::VIRTUAL_QQ_TYPE => "Q币",
        Item::VIRTUAL_GAME_TYPE => "游戏充值",
    );

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        $array = array(
            array('type', 'safe'),
            array('cardnum,', 'numerical', 'integerOnly' => true),
            array('cardid, game_userid, game_area, game_srv', 'length', 'max' => 255,'on'=>'create'),
            array('startTime,endTime,id, cardid, cardnum, type,game_userid, game_area,account, game_srv,income_price,expend_price,phone,customer_id,customer_name,pay_sn', 'safe', 'on' => 'search'),
        );
        return CMap::mergeArray(parent::rules(), $array);
    }

    public function attributeLabels()
    {
        $array = array(
            'id' => 'ID',
            'cardid' => '商品的编码',
            'cardnum' => '数量',
            'game_userid' => '充值号码',
            'game_area' => '区域',
            'game_srv' => '服务器组',
            'income_price' => '收入价格',
            'expend_price' => '支出价格',
            'payment_id' => '支付方式',
            'type' => '类型',
            'account' => '充值号码',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'phone' => '手机号码',
            'customer_name' => '业主姓名',
            'pay_sn'=>'支付单号'
        );
        return CMap::mergeArray(parent::attributeLabels(), $array);
    }

    public function getVirtualTypeNames()
    {
        return CMap::mergeArray(array('' => '全部'), self::$virtualType);
    }

    public function getVirtualType($html = false)
    {
        $model = $this->objectModel;
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$virtualType[empty($this->$model->type) ? '0' : $this->$model->type];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }


    public function getCardId()
    {
        $model = $this->objectModel;
        return empty($this->$model->cardid) ? '' : $this->$model->cardid;
    }

    public function getCardNum()
    {
        $model = $this->objectModel;
        return empty($this->$model->cardnum) ? '' : $this->$model->cardnum;
    }

    public function getGameUserId()
    {
        $model = $this->objectModel;
        return empty($this->$model->game_userid) ? '' : $this->$model->game_userid;
    }

    public function getGameArea()
    {
        $model = $this->objectModel;
        return empty($this->$model->game_area) ? '' : $this->$model->game_area;
    }

    public function getGameSrv()
    {
        $model = $this->objectModel;
        return empty($this->$model->game_srv) ? '' : $this->$model->game_srv;
    }

    public function getIncomePrice()
    {
        $model = $this->objectModel;
        return empty($this->$model->income_price) ? '' : $this->$model->income_price;
    }

    public function getExpendPrice()
    {
        $model = $this->objectModel;
        return empty($this->$model->expend_price) ? '' : $this->$model->expend_price;
    }

    public function getGameName()
    {
        $model = $this->objectModel;
        if (empty($this->$model->cardid)) {
            return '';
        } else {
            $recharge = Recharge::model()->find('cardid=:cardid', array(':cardid' => $this->$model->cardid));
            return empty($recharge) ? '' : $recharge->cardname;
        }
    }    

    /**
     * 下虚拟商品的订单
     * @param $sn
     * @param $cardId 商品号
     * @param $cardNum 商品数量
     * @param $gameUserId 帐号
     * @param $gameArea 地域
     * @param $gameSrv 服务器
     * @param $incomePrice 收入钱数
     * @param $expendPrice 支出钱数
     * @param $type 类型，手机、qq,game
     * @param int $payment_id 支付方式
     * @param string $note 备注
     * @return bool
     */
    static public function addVirtualRecharge($sn, $cardId, $cardNum, $gameUserId, $gameArea, $gameSrv, $incomePrice, $expendPrice, $bank_pay,$red_packet_pay=0, $payment_id = 0, $note = '', $type)
    {
        $transaction = Yii::app()->db->beginTransaction(); //开始事务
        try {
            $virtual = new VirtualRecharge;
            $virtual->cardid = $cardId;
            $virtual->cardnum = $cardNum;
            $virtual->game_userid = $gameUserId;
            $virtual->game_area = $gameArea;
            $virtual->game_srv = $gameSrv;
            $virtual->income_price = $incomePrice;
            $virtual->expend_price = $expendPrice;
            $virtual->type = $type;
            if ($virtual->save()) {
                $order = new OthersFees;
                $order->sn = $sn;
                $order->note = $note;
                $order->customer_id = Yii::app()->user->id;
                $order->payment_id = $payment_id;
                if ($type == Item::VIRTUAL_MOBILE_TYPE) {
                    $order->amount = $incomePrice;
                } else {
                    $order->amount = $cardNum * $incomePrice;
                }

                $order->bank_pay = $bank_pay;
                $order->red_packet_pay = $red_packet_pay;
                $order->model = 'VirtualRecharge';
                $order->object_id = $virtual->id;
                if($order->save()){
                    OthersFeesLog::createOtherFeesLog($order->id, 'Customer', Item::FEES_AWAITING_PAYMENT, '系统添加');//添加日志

                    $transaction->commit(); //提交事务

                    if(@Yii::app()->config->SwitchTelRechargeRedPacket && $red_packet_pay>0 && $red_packet_pay==$order->amount){//如果用户使用红包全额支付
                        $pay = new PayOrderForm();
                        $pay->order_sn =array($order->sn);
                        $pay->pay_sn = $order->sn;
                        $paySn = $pay->createPay();
                        //$payInfo = Pay::getModel($paySn);

                        $pay = new PayOrderForm();
                        $pay->order_sn =array($order->sn);
                        $pay->pay_sn = $order->sn;
                        $paySn = $pay->createPay();
                        //$payInfo = Pay::getModel($paySn);
                        PayLib::order_paid( $paySn,0,'全额红包支付');
                        return 'all_red_pay';
                    }

                    return true;
                }
                return false;
            }
        } catch (Exception $e) {
            //var_dump($e->getMessage());
            $transaction->rollback(); //数据回滚
            return false;
        }
        return false;
    }

    /* 欧飞的接口下订单
     * $cardid  商品Id号
     * $cardnum 商品数量
     * $sporder_id 系统订单号
     * $game_userid 系统帐号
     * $game_area 帐号区域
     * $game_srv  帐号服务器
     * $log_model 调用模型
     * return true;
     */
    static public function onlineOrder($cardid, $cardnum, $sporder_id, $game_userid, $game_area, $game_srv, $log_model = 'Employee')
    {
        Yii::import('common.api.OfCardApi');
        $of = OfCardApi::getInstanceWithConfig(Yii::app()->config->rechargeService);
        $userip = Yii::app()->request->userHostAddress;
        $ret_url = F::getOrderUrl('') . Yii::app()->createUrl('api/ofCardNotify/ofCode'); //欧飞自动跳转页面
        $sporder_time = date('YmdHis', time());

        $order_note = '商品的编码：' . $cardid . '商品的数量：' . $cardnum . '订单号：' . $sporder_id . '订单时间：' . $sporder_time . '账号：' .
            $game_userid . '区域：' . $game_area . '服务器组：' . $game_srv . '返回的URL地址：' . $ret_url . '买家IP：' . $userip;

        Yii::log('欧飞下订单数据：' . $order_note, CLogger::LEVEL_INFO, 'colourlife.core.OFCRAD.ofcrad');

        $data = $of->onlineOrder($cardid, $cardnum, $sporder_id, $sporder_time, $game_userid, $game_area, $game_srv, $ret_url, $userip);

        Yii::log('欧飞下订单返回结果：' . var_export($data, true), CLogger::LEVEL_INFO, 'colourlife.core.OFCRAD.ofcrad');

        if ($data['game_state'] == 1) //充值成功了
        {
            self::update_status($data['sporder_id']);
            self::addVirtualRechargeLog($data, $log_model);
        }

    }

    //根据订单SN修改订单状态及付款时间
    static public function update_status($order_sn)
    {
        //修改订单状态
        $order = OthersFees::model()->find('sn=:sn', array(':sn' => $order_sn));
        return OthersFees::model()->updateByPk($order->id, array(':sn' => $order_sn, 'pay_time' => time(),
            'status' => Item::FEES_TRANSACTION_SUCCESS));
    }

    //添加系统回调订单日志
    static public function addVirtualRechargeLog($data, $model = 'Employee')
    {
        $order = OthersFees::model()->find('sn=:sn', array(':sn' => $data['sporder_id']));
        $log_text = '欧飞的接口充值成功  订单号' . $data['orderid'] . ' 订单的钱' . $data['ordercash'] . ' 商品名称' .
            $data['cardname'] . ' 商品ID' . $data['cardid'] . ' 商品数量' . $data['cardnum'];
        return OthersFeesLog::createOtherFeesLog($order->id, 'Employee',
            Item::FEES_TRANSACTION_SUCCESS, $log_text);
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('model', $this->objectModel, true); //设置条件

        $criteria->compare('sn', $this->sn, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('user_red_packet', $this->user_red_packet,true);

        $criteria->with[] = $this->objectModel;
        if ($this->type != '') {
            $criteria->compare($this->objectModel . '.type', $this->type, true);
        }
        if ($this->startTime != '') {

            $criteria->compare("create_time", ">=" . strtotime($this->startTime));
        }

        if ($this->endTime != '') {

            $criteria->compare("create_time", "< " . strtotime($this->endTime));

        }
        if ($this->account != '') {
            $criteria->compare($this->objectModel . '.game_userid', $this->account, true);
        }
        if($this->phone != '') {
            $customer_one = Customer::model()->find('mobile=:mobile',array(":mobile"=>$this->phone));
            if($customer_one){
                $customer_id = $customer_one->id;
            }else{
                $customer_id = 0;
            }
            $criteria->compare('customer_id', $customer_id);
        }
        if($this->customer_name != '') {
            $customer_Model = Customer::model()->find('name=:name',array(":name"=>$this->customer_name));
            if(!$customer_Model){
                $customer_Model = Customer::model()->find('username=:username',array(":username"=>$this->customer_name));
            }
            $criteria->compare('customer_id', $customer_Model->id);
        }
        if($this->pay_sn!=""){
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
                'defaultOrder' => 'create_time desc',
            )
        ));
    }

}
