<?php

/**
 * This is the model class for table "credit_log".
 *
 * The followings are the available columns in table 'credit_log':
 * @property integer $id
 * @property integer $customer_id
 * @property string $type
 * @property integer $credit
 * @property string $note
 * @property integer $create_time
 * @property string $desc
 */
class CreditLog extends CActiveRecord
{
    public $modelName = '积分记录';
    public $start_time;
    public $end_time;
    public $region;//地域
    public $community_id;//小区ID
    public $state;
    public $branch_id;
    const STATE_EXPEND = 0;//支出
    const STATE_OBTAIN = 1;//获得

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CreditLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'credit_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('note, credit', 'required'),
            array('credit, create_time', 'numerical', 'integerOnly'=>true),
            array('type', 'length', 'max'=>255),
            array('desc', 'rollback', 'on' => 'rollback'),
            array('customer_id, community_id', 'checkPlusMinus', 'on' => 'plus, minus'),//赠送或扣除
            array('customer_id', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, customer_id, type, credit, note, create_time, state, desc, region,branch_id, community_id, start_time, end_time', 'safe', 'on'=>'search'),
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
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'customer_id' => '业主',
            'type' => '积分操作类型',
            'credit' => '积分值',
            'note' => '备注',
            'create_time' => '操作时间',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'state' => '类型',
            'desc' => '回滚原因',
            'region' => '地域',
            'community_id' => '小区',
            'branch_id'=>'组织架构'

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
        $criteria->join = 'LEFT JOIN `customer` as c ON t.customer_id = c.id';
        $criteria->compare('id',$this->id);
        $criteria->compare('c.username',$this->customer_id);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('credit',$this->credit);
        $criteria->compare('note',$this->note,true);
        $criteria->compare('desc',$this->desc,true);
        if($this->community_id!=''){
            $criteria->compare('c.community_id', $this->community_id);//搜索小区下的用户积分
        }elseif($this->branch_id!=''){
            $community_ids = Branch::model()->findByPk($this->branch_id)->getBranchAllIds('Community');
            $criteria->addInCondition('c.community_id', $community_ids);//搜索小区下的用户积分
        }
        if('' != $this->state){
            if(self::STATE_OBTAIN == $this->state){
                $criteria->addCondition('t.credit > 0');
            }
            elseif(self::STATE_EXPEND == $this->state){
                $criteria->addCondition('t.credit < 0');
            }
        }
        if ($this->start_time != "") {
            $criteria->addCondition('t.create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('t.create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 't.`create_time` DESC'
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
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function getIntrgral($start_time = 0, $end_time = 0)
    {
        $criteria = new CDbCriteria;
        $criteria->select = " SUM(credit) as credit ";
        $criteria->addCondition("customer_id =" . Yii::app()->user->id);

        if (!empty($start_time)) {
            $criteria->addCondition("create_time >=" . $start_time);
        }
        if (!empty($end_time)) {
            $criteria->addCondition("create_time <=" . $end_time);
        }

        $intrgarl = $this->find($criteria);
        //var_dump($intrgarl->credit);
        return empty($intrgarl->credit) ? 0 : $intrgarl->credit;

    }

    /**
     * 创建积分操作日志
     * @param integer   $customer_id    业主ID
     * @param string    $type   记录类型
     * @param integer   $credit 积分数量
     * @param null|string $note 备注
     * @param integer   $algorithm  支出|获得
     * @return bool
     */
    public static function createLog($customer_id, $type, $credit, $note = null, $algorithm = 1)
    {
        $model = new self;
        $model->customer_id = $customer_id;
        $model->type = $type;
        $model->credit = $algorithm == 1 ? $credit : -$credit;
        $model->note = $note;
        return $model->save();
    }

    /**
     * 回滚操作
     * @param $attribute
     * @param null $params
     *
     */
    public function rollback($attribute, $params = null)
    {
        if(!$this->hasErrors()){
            if(empty($this->desc)){
                $this->addError($attribute, '回滚原因必需填写！');
            }
        }
    }

    /**
     * 获取业主名称
     * @param null $customer_id 业主ID
     * @param bool $link    是否需要生成链接
     * @return string       用户名|用户名称|用户名称链接
     */
    public function getCustomerName($customer_id=null, $link = true)
    {
        $customer = $name = '';
        if(!empty($customer_id)){
            $customer = Customer::model()->findByPk($customer_id);
        }
        elseif(!empty($this->customer_id) && !empty($this->customer)){
            $customer_id = $this->customer_id;
            $customer = $this->customer;
        }
        if($customer instanceof Customer){
            if(!empty($customer->name)){
                $name = $customer->name;
            }
            elseif(!empty($customer->username)){
                $name = $customer->username;
            }
        }
        if($link){
            return CHtml::link($name,'/customer/'.$customer_id, array('target'=>'_blank'));
        }
        else{
            return $name;
        }
    }

    public function afterSave()
    {
        if('rollback' == $this->getScenario()){//回滚操作时
            /**
             * @var Customer    $customer
             */
            if($customer = Customer::model()->findByPk($this->customer_id)){
                if($this->credit > 0){//原先为加的则回滚需要减
                    $customer->credit -= $this->credit;
                }
                else{
                    $customer->credit += $this->credit;
                }
                try{
                    $customer->save();
                }
                catch(Exception $e){
                    Yii::log('回滚积分失败！ID：【'.$this->id.'】');
                }
            }
        }
    }

    public function checkPlusMinus($attribute, $params = null)
    {
        if(!$this->hasErrors()){
            if(empty($this->customer_id)){
                $this->addError('customer_id', '业主不能为空！');
            }
        }
    }

    /**
     * 赠送|扣除保存操作
     * @return bool|int
     */
    public function savePlusMinus()
    {
        if(!empty($this->customer_id)){
            $this->customer_id = is_array($this->customer_id) ? $this->customer_id : array($this->customer_id);
            foreach($this->customer_id as $customer_id )
            {
                /**
                 * @var Customer    $customer
                 */
                if($customer = Customer::model()->findByPk($customer_id)){
                    $flag = 'plus' == $this->getScenario();
                    $customer->changeCredit($flag ? 'increase' : 'decrease', array('credit' => $this->credit, 'note' => $flag ? '物业后台赠送积分' : '物业后台扣除扣分'));
                }
                else{
                    return false;
                }
            }
            return true;
        }
        return false;
    }
}
