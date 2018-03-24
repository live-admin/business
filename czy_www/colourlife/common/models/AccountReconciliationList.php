<?php

/**
 * This is the model class for table "account_reconciliation_list".
 *
 * The followings are the available columns in table 'account_reconciliation_list':
 * @property integer $id
 * @property integer $task_id
 * @property integer $payment_id
 * @property integer $type
 * @property string $sn
 * @property integer $employee_id
 * @property integer $state
 * @property integer $in_bill_state
 * @property integer $out_bill_state
 * @property integer $create_time
 */
class AccountReconciliationList extends CActiveRecord
{
    public $modelName = '对帐明细管理';
    public $start_time;
    public $end_time;
    public $run_start_time;
    public $run_end_time;
    public $order_sn;
    public $pay_amount;
    public $tradeDate; //对账日期
    public $payment_ids;
    public $types;

    private static $_state = array(
        Item::TASK_DETAILED_STATUS_NOT_START => '未开始',
        Item::TASK_DETAILED_SUCCESS => '成功',
        Item::TASK_DETAILED_FAIL => '失败，异常',
    );
    private static $_manualChecking = array(
        0 => '否',
        1 => '是',
    );

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'account_reconciliation_list';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('task_id, payment_id, sn, state, create_time', 'required'),
            array('task_id, payment_id, type, employee_id, state, in_bill_state, out_bill_state, create_time', 'numerical', 'integerOnly' => true),
            array('sn', 'length', 'max' => 45),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, task_id, payment_id, type, sn, employee_id, state, in_bill_state, out_bill_state, create_time, start_time, end_time,run_start_time, run_end_time,third_party_order,order_sn,manual_checking', 'safe', 'on' => 'search'),
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
            'task' => array(self::BELONGS_TO, 'AccountReconciliationTask', 'task_id'),
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
            'payment' => array(self::BELONGS_TO, 'Payment', 'payment_id'),
            'logs' => array(self::HAS_MANY, 'AccountReconciliationListLog', 'list_id'),
            'log' => array(self::HAS_ONE, 'AccountReconciliationListLog', 'list_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'task_id' => '对帐任务单号',
            'payment_id' => '支付方式',
            'type' => '对帐类型', // 1为入帐 0为出帐
            'sn' => '平台支付号',
            'employee_id' => '操作人', // 0为系统操作
            'state' => '状态',
            'in_bill_state' => '入帐是否成功',
            'out_bill_state' => '出帐是否成功',
            'create_time' => '对账时间',
            'start_time' => '对账的交易日期开始时间',
            'end_time' => '对账的交易日期结束时间',
            'run_start_time' => '对账开始时间',
            'run_end_time' => '对账结束时间',
            'third_party_order' => '第三方订单',
            'order_sn' => '平台订单号',
            'manual_checking' => '人工核对',
            'amount' => '对账金额',
            'pay_amount' => '平台支付单金额',
            'tradeDate' => '交易日期',
            'payment_ids' => '支付方式',
            'types' => '对帐类型',
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

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.task_id', $this->task_id);
        if (!empty($this->payment_ids)) {
            $criteria->addInCondition('t.payment_id', $this->payment_ids);
        }
        if (!empty($this->types)) {
            $criteria->addInCondition('t.type', $this->types);
        }

        $criteria->compare('t.payment_id', $this->payment_id);
        $criteria->compare('t.type', $this->type);
        $criteria->compare('t.sn', $this->sn, true);
        $criteria->compare('t.employee_id', $this->employee_id);
        $criteria->compare('t.third_party_order', $this->third_party_order);
        $criteria->compare('t.state', $this->state);
        $criteria->compare('t.in_bill_state', $this->in_bill_state);
        $criteria->compare('t.out_bill_state', $this->out_bill_state);
        $criteria->compare('t.manual_checking', $this->manual_checking);
        $criteria->compare('t.manual_checking_time', $this->manual_checking_time);
        if (!empty($this->run_start_time)) {
            $criteria->addCondition('t.create_time >= ' . strtotime($this->run_start_time));
        }
        if (!empty($this->run_end_time)) {
            $criteria->addCondition('t.create_time < ' . strtotime($this->run_end_time));
        }
        $criteria->with[] = 'task';
        if (!empty($this->start_time)) {
            $criteria->addCondition('task.reconciliation_time >= ' . strtotime($this->start_time));
        }
        if (!empty($this->end_time)) {
            $criteria->addCondition('task.reconciliation_time < ' . strtotime($this->end_time));
        }
        if (!empty($this->order_sn)) {
            $pay_sn = $this->orderSNGetPaySn();
            if ($pay_sn){
                 $criteria->compare('t.sn', $pay_sn, true, "OR");
            }

        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /*
     * 根据订单SN得到支付SN
     */
    protected function orderSNGetPaySn()
    {
        $order = SN::findContentBySN($this->order_sn);
        if (!empty($order)) {
            return Pay::getPaySn($order->pay_id);
        }
        return false;
    }

    public static function getAccountReconciliationState($state = null)
    {
        if (null === $state) {
            return self::$_state;
        } else {
            if (isset(self::$_state[$state])) {
                return self::$_state[$state];
            }
            return '状态未定义';
        }
    }

    public static function getAccountReconciliationManualChecking($type = null)
    {
        if (null === $type) {
            return self::$_manualChecking;
        } else {
            if (isset(self::$_manualChecking[$type])) {
                return self::$_manualChecking[$type];
            }
            return '类型未定义';
        }
    }

    /**
     * 根据订单号生成相应的订单号View层链接
     * @param $sn
     * @return string
     */
    public static function getFindOrderLink($sn)
    {
        $model = SN::findModelBySN($sn);
        $link = '';
        switch ($model) {
            case 'OthersPropertyFees':
                $link = '/propertyFees';
                break;
            case 'OthersParkingFees':
                $link = '/parkingFees';
                break;
            case 'CustomerOrder':
                $link = '/customerOrder';
                break;
            case 'SellerOrder':
                $link = '/sellerOrder';
                break;
            case 'OthersVirtualRecharge':
                $link = '/virtualRecharge';
                break;
            case 'OthersAdvanceFees':
                $link = '/advanceFee';
                break;
        }
        $model = CActiveRecord::model($model)->findByAttributes(array('sn' => $sn));
        if (empty($model) || empty($link)) {
            return $sn;
        }
        return CHtml::link($sn, $link . '/' . $model->id, array('target' => '_blank'));
    }

    /**
     * 获取订单号模型与订单相应日志表模型
     * @param   string $sn
     * @param $log
     * @return array|CActiveRecord|mixed|null
     */
    public static function getOrderObject($sn, &$log)
    {
        if ($order = Order::model()->findByAttributes(array('sn' => $sn))) {
            $log = array('OrderLog', 'createOrderLog');
            return $order;
        } elseif ($order = OthersFees::model()->findByAttributes(array('sn' => $sn))) {
            $log = array('OthersFeesLog', 'createOtherFeesLog');
            return $order;
        }
        return null;
    }


    public static function getFullString($str, $len)
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' => $str), F::sub($str, $len, $show = true, $f = '……'));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AccountReconciliationList the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    /**
     * @param $list_id
     * @param $order
     * 对入帐
     */
    static function checkInPay($task_id, $list_id, $order, $payment_id)
    {
        $sn = $order['sn'];
        $model = Pay::getModel($sn);
        if (empty($model)) {
            $note = "对账失败！未知的对账类型！";
            self::updateListStatus($task_id, $list_id, $sn, Item::TASK_DETAILED_FAIL, $note);
            return false;
        }
        $logNote = "系统自动对账完成，";
        if ($model->status == Item::PAY_STATUS_NO) {
            if (Pay::updatePayBySn($sn, $payment_id) && Pay::updateInBillStateBySn($sn)) { //修改状态为已支付和修改支付状态为已出账
                $note = "对账成功！系统自动入账对账修改订单状态,状态为已支付:1";
                PayLib::updatePayOrder($sn, $payment_id, $note);
                $state = Item::TASK_DETAILED_SUCCESS;
                $logNote .= "对账成功！更新订单({$sn})状态成功";
            } else {
                $logNote .= "对账失败！更新订单({$sn})状态失败";
                $state = Item::TASK_DETAILED_FAIL;
            }
        } else {
            $state = Item::TASK_DETAILED_SUCCESS;
            $logNote .= "对账成功！";
        }
        if (self::updateListStatus($task_id, $list_id, $sn, $state, $logNote, 1))
            return true;
        else
            return false;
    }

    /**
     * @param $list_id
     * @param $order
     * 对出帐，出帐自动对账直接返回异常
     */
    static function checkOutPay($task_id = 0, $list_id = 0, $order = array(), $payment_id = 0)
    {
        $sn = $order['sn'];
        $logNote = "对账失败！更新订单({$sn})状态失败";
        $state = Item::TASK_DETAILED_FAIL;
        self::updateListStatus($task_id, $list_id, $sn, $state, $logNote); //更改状态

        return false;
    }

    /**
     * @param $list_id
     * @param $task_id
     * @param $sn
     *
     */
    static function updateListStatus($task_id, $list_id, $sn, $state, $note = '', $in_bill_state = 0, $out_bill_state = 0)
    {
        $in_bill_time = $out_bill_time = 0;
        if ($in_bill_state)
            $in_bill_time = time();
        if ($out_bill_state)
            $out_bill_time = time();

        //修改对账明细状态
        $return = Yii::app()->db->createCommand()->update('account_reconciliation_list', array(
            'state' => $state, //对账状态
            'in_bill_state' => $in_bill_state, //对账状态
            'in_bill_time' => $in_bill_time, //对账状态
            'out_bill_state' => $out_bill_state, //对账状态
            'out_bill_time' => $out_bill_time, //对账状态
        ), 'id=:id', array(':id' => $list_id));

        Yii::app()->db->createCommand()->insert('account_reconciliation_list_log', array(
            'task_id' => $task_id, //任务ID
            'list_id' => $list_id, //明细ID
            'third_party_order' => "", //第三订单号
            'third_party_amount' => 0, //第三方金额
            'sn' => $sn,
            'amount' => 0, //平台金额
            'account_reconciliation_before' => null, //平台对账前状态
            'account_reconciliation_after' => null, //平台对账后状态
            'state' => 0, //状态
            'employee_id' => 0, //操作人，0=》系统
            'note' => $note, //备注
            'create_time' => time(),
        ));
        return ($return) ? true : false;
    }

    /**
     * 修改入账状态
     * @param $sn 支付流水号
     * @param $note 备注
     * @param $manual_checking 人工核对
     * @return bool
     */
    static public function updateInBillState($list_id, $note = '', $manual_checking = 0)
    {
        $transaction = Yii::app()->db->beginTransaction(); //创建事务
        try {
            $list_id = intval($list_id);
            $model = self::model()->findbypk($list_id);
            if (!empty($model)) {
                $model->in_bill_state = 1; //对账成功
                $model->in_bill_time = time(); //对账时间
                $model->state = Item::TASK_DETAILED_SUCCESS; //对账状态
                if ($manual_checking != 0) {
                    $model->manual_checking_time = time();
                    $model->manual_checking = $manual_checking;
                }
                $note = ($note != '') ? $note : '出账对账成功！';
                //更新状态写日志
                if ($model->update() && AccountReconciliationListLog::createLog($model->task_id, $model->id, $model->sn, $note)) {
                    if (Pay::getInBillState($model->sn) && AccountReconciliationTask::updateStateSuccess($model->task_id)) { //如果支付单号的已出账
                        $transaction->commit(); //提交事务
                        return true;
                    } else {
                        if (Pay::updateInBillStateBySn($model->sn) && AccountReconciliationTask::updateStateSuccess($model->task_id)) { //更新支付出账状态
                            $transaction->commit(); //提交事务
                            return true;
                        }
                    }
                }
                $transaction->rollback(); //提交事务
                return false;

            }
        } catch (Exception $e) {
            $transaction->rollback(); //回滚事务
        }
        return false;
    }

    /**
     * 修改出账状态
     * @param $sn 支付流水号
     * @param $note 备注
     * @param $manual_checking 人工核对
     * @return bool
     */
    static public function updateOutBillState($list_id, $note = '', $manual_checking = 0)
    {
        $transaction = Yii::app()->db->beginTransaction(); //创建事务
        try {
            $list_id = intval($list_id);
            $model = self::model()->findbypk($list_id);
            if (!empty($model)) {
                $model->out_bill_state = 1; //对账成功
                $model->out_bill_time = time(); //对账时间
                $model->state = Item::TASK_DETAILED_SUCCESS; //对账状态
                if ($manual_checking != 0) {
                    $model->manual_checking_time = time();
                    $model->manual_checking = $manual_checking;
                }
                $note = ($note != '') ? $note : '出账对账成功！';
                //更新状态写日志
                if ($model->update() && AccountReconciliationListLog::createLog($model->task_id, $model->id, $model->sn, $note)) {
                    if (Pay::getInBillState($model->sn) && AccountReconciliationTask::updateStateSuccess($model->task_id)) { //如果支付单号的已出账
                        $transaction->commit(); //提交事务
                        return true;
                    } else {
                        if (Pay::updateOutBillStateBySn($model->sn) && AccountReconciliationTask::updateStateSuccess($model->task_id)) { //更新支付出账状态
                            $transaction->commit(); //提交事务
                            return true;
                        }
                    }
                }
                $transaction->rollback(); //提交事务
                return false;

            }
        } catch (Exception $e) {
            $transaction->rollback(); //回滚事务
        }
        return false;
    }

    public static function getStateStyle($state)
    {
        $html = '<span class="label label-%s">%s</span>';
        $style = '';
        switch ($state) {
            case '0':
                $style = 'warning';
                break;
            case 1:
                $style = 'success';
                break;
            case 2:
                $style = 'error';
                break;
            default:
                $style = 'warning';
                break;
        }
        return sprintf($html, $style, self::$_state[$state]);
    }

    public function afterFind()
    {
        $pay = Pay::getModel($this->sn);
        $this->pay_amount = !empty($pay) ? $pay->amount : 0;
        $this->tradeDate = !empty($this->task) ? $this->task->reconciliation_time : 0;
        parent::afterfind();
    }
}
