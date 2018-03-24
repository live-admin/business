<?php

/**
 * This is the model class for table "account_reconciliation_task".
 *
 * The followings are the available columns in table 'account_reconciliation_task':
 * @property integer $id
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $payment_id
 * @property integer $type
 * @property integer $number
 * @property integer $success_count
 * @property integer $state
 * @property integer $employee_id
 * @property integer $create_time
 * @property Payment $payment
 * @property Employee   $employee
 */
class AccountReconciliationTask extends CActiveRecord
{
    public $modelName = '对帐任务管理';
    public $start_time;
    public $end_time;
    public $payment_ids;
    public $types;
    private static $_type = array(
        '0' => '核对退款交易',
        '1' => '核对成功交易',
    );
    private static $_state = array(
        Item::TASK_STATUS_NOT_START => '未开始',
        Item::TASK_STATUS_FAILED_DOWNLOAD => '下载失败',
        Item::TASK_STATUS_RECONCILIATION => '对账中',
        Item::TASK_STATUS_RECONCILIATION_PARTIAL_SUCCESS => '对账部分成功',
        Item::TASK_STATUS_RECONCILIATION_ALL_SUCCESS => '对账全部成功',
    );
    private static $_bill_state = array(
        '0' => '否',
        '1' => '是',
    );

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'account_reconciliation_task';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
           // array('reconciliation_time, payment_id, number', 'required'),
            array('payment_id, type, number, success_count, state', 'numerical', 'integerOnly' => true),
            array(
                'id, payment_id, type, number, success_count, state, employee_id, start_time, end_time,third_party_order,reconciliation_time',
                'safe',
                'on' => 'create'
            ),
            array(
                'payment_ids, types, start_time, end_time',
                'required',
                'on' => 'web_create'
            ),
           array(
                'start_time, end_time',
                'checkTime',
                'on' => 'web_create'
            ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array(
                'id, payment_ids,types,start_time, end_time, payment_id, type, number, success_count, state, employee_id, create_time,third_party_order',
                'safe',
                'on' => 'search'
            ),
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
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
            'payment' => array(self::BELONGS_TO, 'Payment', 'payment_id'),
            'logs' => array(self::HAS_MANY, 'AccountReconciliationTaskLog', 'task_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => '任务单号',
            'start_time' => '对账开始时间',
            'end_time' => '完成时间',
            'run_start_time' => '对账开始时间',
            'run_end_time' => '完成时间',
            'payment_id' => '支付方式',
            'type' => '对帐类型', // 入(1)或出(0)
            'number' => '记录数量',
            'success_count' => '对账成功数',
            'state' => '状态',
            'employee_id' => '操作人', //ID 默认0为系统创建
            'create_time' => '创建时间',
            'third_party_order' => '第三方订单号',
            'reconciliation_time' => '对账的交易日期',
            'payment_ids' => '支付方式',
            'types' => '对帐类型',
        );
    }

    public function checkTime($attribute, $params)
    {
        if (!$this->hasErrors() && strtotime($this->start_time)>strtotime($this->end_time)) {
            $this->addError($attribute, $this->modelName . '开始时间不能比结束时间大。');
        }
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
        $criteria->compare('reconciliation_time', $this->reconciliation_time);
        $criteria->compare('payment_id', $this->payment_id);
        $criteria->compare('type', $this->type);
        if(!empty($this->payment_ids))
        {
           $criteria->addInCondition('payment_id', $this->payment_ids);
        }
        if(!empty($this->types))
        {
           $criteria->addInCondition('type', $this->types);
        }
        $criteria->compare('number', $this->number);
        $criteria->compare('success_count', $this->success_count);
        $criteria->compare('state', $this->state);
        $criteria->compare('employee_id', $this->employee_id);
        if (!empty($this->start_time)) {
            $criteria->addCondition('reconciliation_time >= ' . strtotime($this->start_time));
        }
        if (!empty($this->end_time)) {
            $criteria->addCondition('reconciliation_time < ' . strtotime($this->end_time));
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id DESC'
            ),
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AccountReconciliationTask the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function getAccountReconciliationType($type = null)
    {
        if (null === $type) {
            return self::$_type;
        } else {
            if (isset(self::$_type[$type])) {
                return self::$_type[$type];
            }
            return '类型未定义';
        }
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

    public static function getBillState($state)
    {
        return sprintf(
            '<span class="label label-%s">' . (isset(self::$_bill_state[$state]) ? self::$_bill_state[$state] : '') . '</span>',
            $state == 1 ? 'success' : 'error'
        );
    }

    public static function getTaskName($id)
    {
        $model = self::model()->findByPk($id);
        $payment = !empty($model->payment) ? $model->payment->name : '';
        return $payment . ' ' . date('Y-m-d H:i:s', $model->create_time);
    }

    public function addTasks()
    {
        $employee_id = Yii::app()->user->id;
        $create_employee_id = !empty($employee_id) ? $employee_id : 0;
        $this->start_time = strtotime($this->start_time);
        if (!empty($this->end_time)) {
            $this->end_time = strtotime($this->end_time);
        } else {
            $this->end_time = strtotime(time());
        }
        foreach($this->payment_ids as $payment)
        {
            foreach($this->types as $type)
            {
                //获得开始时间和结束时间的天数差
                $day = floor(($this->end_time - $this->start_time) / 86400) + 1;
                for ($i = 0; $i < $day; $i++) {
                    //循环天数每次查询一天,照顾所有接口
                    $reconciliation_time = strtotime(date('Y-m-d 00:00:00', strtotime("+{$i} day", $this->start_time)));
                    $model = new self();
                    $model->state = Item::TASK_STATUS_NOT_START;
                    $model->reconciliation_time = $reconciliation_time;
                    $model->employee_id = $create_employee_id;
                    $model->type = $type;
                    $model->payment_id = $payment;
                    $model->number = $this->number;
                    if (!$model->save() || !AccountReconciliationTaskLog::createLog($model->id, $model->state))
                        return false;
                }
            }
        }
        return true;
    }

    static public function updateStateSuccess($task_id, $note = '')
    {
        $model = self::model()->findByPk($task_id);
        if (!empty($model)) {
            $model->success_count = $model->success_count + 1;
            if ($model->success_count == $model->number)
                $model->state = Item::TASK_STATUS_RECONCILIATION_ALL_SUCCESS; //对账成功
            else
                $model->state = Item::TASK_STATUS_RECONCILIATION_PARTIAL_SUCCESS;//对账部分成功

            $note = ($note != '') ? $note : '任务：'.$task_id.'有对账成功！';
            if ($model->update())
                return AccountReconciliationTaskLog::createLog($task_id, $model->state, $note);
        }
        return false;
    }
}
