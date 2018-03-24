<?php

/**
 * This is the model class for table "cai_redpacket_bind_bank".
 *
 * The followings are the available columns in table 'cai_redpacket_bind_bank':
 * @property integer $id
 * @property integer $type_id
 * @property integer $customer_id
 * @property string $paperVal
 * @property integer $card_num
 * @property integer $create_time
 * @property integer $state
 * @property integer $is_deleted
 * @property string $note
 */
class RedPacketPayPwdVal extends CActiveRecord
{	
	public $modelName = '彩之云支付密码验证信息';
	public $startTime;
    public $endTime;
    public $paperName;
    public $customerName;
    public $customerUserName;
    public $customerMobile;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'red_packet_pay_pwd_val';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id,type_id,paperName,paperVal', 'required'),
            array('type_id, customer_id, create_time, state, is_deleted', 'numerical', 'integerOnly'=>true),
            array('paperName,paperVal', 'length', 'max'=>255),
            array('note', 'safe'),
            array('customer_id','checkRepeat', 'on'=>'bindBank'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, type_id, customer_id, paperName,paperVal, create_time, state, is_deleted, note, startTime, endTime, customerName, customerUserName, customerMobile, paperName', 'safe', 'on'=>'search'),
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
            'paper' => array(self::BELONGS_TO, 'RedPacketPwdType', 'type_id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type_id' => '证件类型ID',
			'customer_id' => '用户ID',
            'paperName' => '证件人',
			'paperVal' => '证件内容',
			'create_time' => '创建时间',
			'state' => '状态',
			'is_deleted' => '删除',
			'note' => '备注',
			'startTime' => '开始时间',
            'endTime' => '结束时间',
            'paperName' => '证件名称',
            'customerName' => '用户姓名',
            'customerUserName' => '彩之云账号',
            'customerMobile' => '用户手机',
		);
	}



	/**
     * @return array
     */
    public function behaviors()
    {
        return array(
        	'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
            'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }






    public function checkRepeat($attribute, $params)
    {
    	if(!$this->hasErrors()&&!empty($this->customer_id)){
            $model = self::model()->find('customer_id=:customer_id and state=:state and is_deleted=:is_deleted',array(':customer_id'=>$this->customer_id,':state'=>0,':is_deleted'=>0));
            if(!$this->hasErrors()&&$model)
                $this->addError($attribute,"已经存在证件信息,不能重复填写");

            // if(isset(Yii::app()->config->SwitchCaiRedPacketBindBank)){
            //     $config = Yii::app()->config->SwitchCaiRedPacketBindBank;
            //     if($config){
            //         if(!empty($this->employee)){
            //             if(!$this->hasErrors()&&$this->employee->name!=$this->paperVal){
            //                 $this->addError($attribute,"持卡人与OA姓名不匹配");
            //             }
            //         }
            //     }
            // }
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

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('type_id',$this->type_id);
        $criteria->compare('customer_id',$this->customer_id);
        $criteria->compare('paperName',$this->paperName,true);
        $criteria->compare('paperVal',$this->paperVal,true);
        $criteria->compare('create_time',$this->create_time);
        $criteria->compare('state',$this->state);
        $criteria->compare('is_deleted',$this->is_deleted);
        $criteria->compare('note',$this->note,true);
        if ($this->startTime!= '') {
            $criteria->compare("create_time", ">= " . strtotime($this->startTime." 00:00:00"));
        }
        if ($this->endTime!= '') {
            $criteria->compare("create_time", "<= " . strtotime($this->endTime." 23:59:59"));
        }
        if ($this->paperName!= '') {
            $criteria->with[] = 'paper';
            $criteria->compare("paper.name", $this->paperName);
        }
        if ($this->employeeName!= '') {
            $criteria->with[] = 'customer';
            $criteria->compare("customer.name", $this->customerName);
        }
        if ($this->customerMobile!= '') {
            $criteria->with[] = 'customer';
            $criteria->compare("customer.mobile", $this->customerMobile);
        }
        if ($this->customerUserName!= '') {
            $criteria->with[] = 'customer';
            $criteria->compare("customer.username", $this->customerUserName);
        }
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }


    public function getBankName()
    {
        return empty($this->paper)?'':$this->paper->name;
    }



    public function getCustomerName()
    {
        return empty($this->customer)?'':$this->customer->name;
    }



    public function getCustomerUserName()
    {
        return empty($this->customer)?'':$this->customer->username;
    }


    public function getCustomerMobile()
    {
        return empty($this->customer)?'':$this->customer->mobile;
    }


    public function getBankList($flag=false,$extra=false){
        $list = array();
        $criteria = new CDbCriteria;  
        $criteria->condition = 'state=:state and is_deleted=:is_deleted';  
        $criteria->params = array(':state'=>0, ':is_deleted'=>0);   
        $model = RedPacketPwdType::model()->findAll($criteria);
        foreach($model as $_v){
            if($flag){
                $list[$_v->name] = $_v->name;
            }else{
                $list[$_v->id] = $_v->name;
            }  
        }
        if(!$extra){
            return $list;
        }else{
            return array_merge(array(''=>'全部'), $list);
        }
        
    }



	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CaiRedpacketBindBank the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
