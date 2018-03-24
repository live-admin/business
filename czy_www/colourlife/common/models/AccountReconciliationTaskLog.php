<?php

/**
 * This is the model class for table "account_reconciliation_task_log".
 *
 * The followings are the available columns in table 'account_reconciliation_task_log':
 * @property integer $id
 * @property integer $task_id
 * @property integer $state
 * @property integer $create_time
 * @property string  $note
 */
class AccountReconciliationTaskLog extends CActiveRecord
{
    public $modelName = '对帐任务日志';

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'account_reconciliation_task_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('task_id, create_time', 'required'),
            array('task_id, state, create_time', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, task_id, state, start_time, end_time', 'safe', 'on' => 'search'),
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
            'state' => '任务状态',
            'create_time' => '日志创建时间',
            'note' => '备注',
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
        $criteria->compare('task_id', $this->task_id);
        $criteria->compare('state', $this->state);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AccountReconciliationTaskLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function createLog($task_id, $state,$note='')
    {
        $model = new self;
        $model->task_id = $task_id;
        $model->state = $state;
        $model->create_time = time();
         $model->note = ($note=='')?Yii::app()->user->username . sprintf(' 增加了自动对帐任务%d，成功。 date:' . date('Y-m-d H:i:s',time()),
                $task_id):$note;
        return $model->save();
    }
}
