<?php

/**
 * This is the model class for table "statement".
 *
 * The followings are the available columns in table 'statement':
 * @property integer $id
 * @property string $model
 * @property integer $object_id
 * @property string $amount
 * @property integer $status
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $create_time
 * @property integer $create_employee_id
 */
class Statement extends CActiveRecord
{
    public $modelName = '结算报表';
    public $startTime;
    public $endTime;
    public $_shopName;

    public $_branchName;
    public $shop;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'statement';
    }

    public function rules()
    {
        return array(
            array('_branchName,_shopName,object_id, status, start_time, end_time, create_time, create_employee_id', 'numerical', 'integerOnly' => true),
            array('model', 'length', 'max' => 50),
            array('amount', 'length', 'max' => 10),
            //验证推荐的一些规则
            array('startTime,endTime', 'date', 'format' => 'yyyy-MM-dd'),
            array('_branchName,shop,startTime,endTime', 'checkPayDate', 'on' => 'create'), // 增加，检查pay表里的结算数据

            array('_branchName,_shopName,id, model, object_id, amount, status, start_time, end_time, create_time, create_employee_id', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array(
            'shopModel' => array(self::BELONGS_TO, 'Shop', 'object_id'),
            'branchModel' => array(self::BELONGS_TO, 'Branch', 'object_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'model' => 'Model',
            'object_id' => 'Object',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'create_time' => 'Create Time',
            'create_employee_id' => 'Create Employee',
            'endTime' => '结束时间',
            'startTime' => '开始时间',
            'shop' => '商家',
            'status' => '状态',
            'amount ' => '金额',
            '_branchName' => '部门',
            '_shopName' => '商家名称',
        );
    }

    public function beforeFind()
    {
        $this->startTime = $this->start_time == 0 ? "" : date('Y-m-d', $this->start_time);
        $this->endTime = $this->end_time == 0 ? "" : date('Y-m-d', $this->end_time);
        return parent::beforeFind();
    }

    protected function beforeValidate()
    {
        if (empty($this->startTime)) $this->startTime = date('Y-m-d', time());
        if (empty($this->endTime)) $this->endTime = date('Y-m-d', time());
        $this->start_time = @strtotime($this->startTime);
        $this->end_time = @strtotime($this->endTime);

        return parent::beforeValidate();
    }

    public function getShopName()
    {
        if ($this->model == 'shop') {
            return empty($this->shopModel) ? '' : $this->shopModel->name;
        }
    }

    public function getShopTypeName()
    {
        if ($this->model == 'shop') {
            return empty($this->shopModel) ? '' : $this->shopModel->getTypeName($this->shopModel->type);
        }
    }

    public function getBranchName()
    {
        if ($this->model == 'branch') {
            return empty($this->branchModel) ? '' : $this->branchModel->name;
        }
    }

    public function getParkingFees()
    {
        if ($this->model == 'branch') {
            return empty($this->branchModel) ? '' : $this->branchModel->name;
        }
    }

    private function _startTime()
    {
        if (empty($this->startTime)) $this->startTime = date('Y-m-d', time());
        return strtotime($this->startTime);
    }

    private function _endTime()
    {
        if (empty($this->endTime)) $this->endTime = date('Y-m-d', time());
        return strtotime($this->endTime);
    }

    public function checkPayDate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $criteria = new CDbCriteria;
            $criteria->addCondition("model='" . $this->model . "'");
            $criteria->addCondition('object_id=' . $this->object_id);
            //$criteria->addCondition('status=' . Item::PAY_STATEMENT );
            $criteria->addCondition('statement_id=0');
            $criteria->addCondition('create_time<=' . $this->_endTime());
            $criteria->addCondition('create_time>=' . $this->_startTime());
            $result_count = StatementQueue::model()->count($criteria);
            if (!$result_count) {
                $this->addError($attribute, $this->modelName . '没有数据可以结算，请确认');
            }
        }
    }

    //结算
    public function balancing()
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition("model='" . $this->model . "'");
        $criteria->addCondition('object_id=' . $this->object_id);
        //$criteria->addCondition('status=' . Item::PAY_STATEMENT);
        $criteria->addCondition('statement_id=0');
        $criteria->addCondition('create_time<=' . $this->_endTime());
        $criteria->addCondition('create_time>=' . $this->_startTime());

        $amount = StatementQueue::model()->find($criteria, array('select' => 'SUM(`amount`) as amount'));
        $amount = empty($amount) ? '0' : $amount->amount;
        $data = StatementQueue::model()->findAll($criteria);

        //添加事务
        $model = new self;
        $transaction = Yii::app()->db->beginTransaction();
        try {
            $this->id = $this->maxId;
            $this->amount = $amount;

            if (count($data) > 0) {
                foreach ($data as $_statementQueue) {
                    $this->changeStatus($_statementQueue->id, Item::PAY_STATEMENT_OK);
                    StatementQueue::addStatementId($_statementQueue->id, $this->id);
                }
            }

            $this->save();
            $transaction->commit();

            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
        }

        return false;
    }

    private function changeStatus($Statement_id, $status)
    {
        return $this->updateByPk($Statement_id, array('status' => $status));
    }

    public function getMaxId()
    {
        return $this->find(array('select' => 'MAX(`id`) as id'))->id + 1;
    }

    public function search()
    {

        $criteria = new CDbCriteria;

        if ($this->_shopName != '') {
            $criteria->with[] = 'shopModel';
            $criteria->compare('shopModel.name', $this->_shopName, true);
        }
        if ($this->_branchName != '') {
            $criteria->with[] = 'branchModel';
            $criteria->compare('branchModel.name', $this->_branchName, true);
        }

        $criteria->compare('`t`.model', $this->model);
        $criteria->compare('`t`.object_id', $this->object_id);
        $criteria->compare('`t`.start_time', $this->start_time);
        $criteria->compare('`t`.end_time', $this->end_time);
        $criteria->compare('`t`.amount', $this->amount, true);
        $criteria->compare('`t`.status', $this->status);
        $criteria->compare('`t`.create_time', $this->create_time);
        $criteria->compare('`t`.create_employee_id', $this->create_employee_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => '`t`.create_time DESC',
            )
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function beforeSave()
    {
        if ($this->isNewRecord)
            $this->create_employee_id = Yii::app()->user->id;
        //else
        // $this->create_employee_id = Yii::app()->user->id;

        return parent::beforeSave();
    }

}
