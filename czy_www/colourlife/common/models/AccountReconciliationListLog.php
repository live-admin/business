<?php

/**
 * This is the model class for table "account_reconciliation_list_log".
 *
 * The followings are the available columns in table 'account_reconciliation_list_log':
 * @property integer $id
 * @property integer $task_id
 * @property integer $list_id
 * @property string $third_party_order
 * @property string $sn
 * @property string $third_party_amount
 * @property string $amount
 * @property integer $account_reconciliation_before
 * @property integer $account_reconciliation_after
 * @property integer $state
 * @property integer $employee_id
 * @property string $note
 * @property integer $create_time
 */
class AccountReconciliationListLog extends CActiveRecord
{
    public $modelName = '对帐明细日志';
    public $start_time;
    public $end_time;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'account_reconciliation_list_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('task_id, third_party_order, sn, third_party_amount, amount, list_id', 'required'),
			array('task_id, list_id, account_reconciliation_before, account_reconciliation_after, state, employee_id, create_time', 'numerical', 'integerOnly'=>true),
			array('third_party_order, sn', 'length', 'max'=>45),
			array('third_party_amount, amount', 'length', 'max'=>10),
			array('note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, task_id, list_id, third_party_order, sn, third_party_amount, amount, account_reconciliation_before, account_reconciliation_after, state, employee_id, note, create_time, start_time, end_time', 'safe', 'on'=>'search'),
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
            'task' => array(self::BELONGS_TO,'AccountReconciliationTask','task_id'),
            'employee' => array(self::BELONGS_TO,'Employee','employee_id'),
            'payment' => array(self::BELONGS_TO,'Payment','payment_id'),
            'list' => array(self::BELONGS_TO,'AccountReconciliationList','list_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'task_id' => '对帐任务ID',
            'list_id' => '对帐明细ID',
			'third_party_order' => '第三方订单号',
			'sn' => '平台订单号',
			'third_party_amount' => '第三方交易金额',
			'amount' => '平台交易金额',
			'account_reconciliation_before' => '对帐前状态',
			'account_reconciliation_after' => '对帐后状态',
			'state' => '对帐状态',
			'employee_id' => '操作人',// 0为系统操作
			'note' => '备注信息',
			'create_time' => '创建时间',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('task_id',$this->task_id);
        $criteria->compare('list_id',$this->list_id);
		$criteria->compare('third_party_order',$this->third_party_order,true);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('third_party_amount',$this->third_party_amount,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('account_reconciliation_before',$this->account_reconciliation_before);
		$criteria->compare('account_reconciliation_after',$this->account_reconciliation_after);
		$criteria->compare('state',$this->state);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('note',$this->note,true);
        if(!empty($this->start_time)){
            $criteria->addCondition('create_time >=' . strtotime($this->start_time));
        }
        if(!empty($this->end_time)){
            $criteria->addCondition('create_time < ' . strtotime($this->end_time . ' 23:59:59'));
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public static function createLog($task_id,$list_id,$sn, $note='',$amount=0,$third_party_order=0,$third_party_amount=0,$before=0,$after=0,$state=2)
    {
        $model = new self();
        $model->task_id = $task_id;
        $model->list_id = $list_id;
        $model->sn = $sn;
        $model->amount = $amount;
        $model->third_party_amount = $third_party_amount;
        $model->third_party_order = $third_party_order;
        $model->note = Yii::app()->user->username.' '.$note;
        $model->state = $state;
        $model->account_reconciliation_before = $before;
        $model->account_reconciliation_after = $after;
        $model->create_time = time();
        $model->employee_id = !empty(Yii::app()->user->id) ? Yii::app()->user->id : 0;
        return $model->save();
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AccountReconciliationListLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
