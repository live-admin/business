<?php

/**
 * This is the model class for table "cai_redpacket_bind_bank".
 *
 * The followings are the available columns in table 'cai_redpacket_bind_bank':
 * @property integer $id
 * @property integer $bank_id
 * @property integer $employee_id
 * @property string $card_holder
 * @property integer $card_num
 * @property integer $create_time
 * @property integer $state
 * @property integer $is_deleted
 * @property string $note
 *  * @property string $ide_num
 */
class CaiRedpacketBindBank extends CActiveRecord
{	
	public $modelName = '银行卡绑定';
	public $startTime;
    public $endTime;
    public $bankName;
    public $employeeName;
    public $OAName;
    public $employeeMobile;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'cai_redpacket_bind_bank';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id,bank_id,card_num,card_holder', 'required'),
            array('bank_id, employee_id, create_time, state, is_deleted', 'numerical', 'integerOnly'=>true),
            array('card_holder', 'length', 'max'=>255),
            array('card_num, ide_num', 'length', 'max'=>32),
            array('note', 'safe'),
            array('employee_id','checkRepeat', 'on'=>'bindBank'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, bank_id, employee_id, card_holder, card_num, create_time, state, is_deleted, note, startTime, endTime, employeeName, OAName, employeeMobile, bankName, ide_num', 'safe', 'on'=>'search'),
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
            'bank' => array(self::BELONGS_TO, 'CaiRedpacketBank', 'bank_id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'bank_id' => '银行ID',
			'employee_id' => '员工ID',
			'card_holder' => '持卡人',
			'card_num' => '卡号',
			'create_time' => '创建时间',
			'state' => '状态',
			'is_deleted' => '删除',
			'note' => '备注',
			'startTime' => '开始时间',
            'endTime' => '结束时间',
            'bankName' => '绑定银行',
            'employeeName' => '员工',
            'OAName' => 'OA账号',
            'employeeMobile' => '员工手机',
            'ide_num' => '证件号码',
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


    public function checkRepeatOld($attribute, $params)
    {
    	if(!$this->hasErrors()&&!empty($this->employee_id)&&!empty($this->bank_id)&&!empty($this->card_holder)&&!empty($this->card_num)){
            $model = self::model()->find('employee_id=:employee_id and bank_id=:bank_id and card_holder=:card_holder and card_num=:card_num and state=:state and is_deleted=:is_deleted',array(':employee_id'=>$this->employee_id,':bank_id'=>$this->bank_id,':card_holder'=>$this->card_holder,':card_num'=>$this->card_num,':state'=>0,':is_deleted'=>0));
            if(!$this->hasErrors()&&$model)
                $this->addError($attribute,"银行卡已经绑定,不能重复绑定");
        }
    }




    public function checkRepeat($attribute, $params)
    {
    	if(!$this->hasErrors()&&!empty($this->employee_id)&&!empty($this->card_num)){
            $model = self::model()->find('employee_id=:employee_id and card_num=:card_num and state=:state and is_deleted=:is_deleted',array(':employee_id'=>$this->employee_id,':card_num'=>$this->card_num,':state'=>0,':is_deleted'=>0));
            if(!$this->hasErrors()&&$model)
                $this->addError($attribute,"银行卡已经绑定,不能重复绑定");

            if(isset(Yii::app()->config->SwitchCaiRedPacketBindBank)){
                $config = Yii::app()->config->SwitchCaiRedPacketBindBank;
                if($config){
                    if(!empty($this->employee)){
                        if(!$this->hasErrors()&&$this->employee->name!=$this->card_holder){
                            $this->addError($attribute,"持卡人与OA姓名不匹配");
                        }
                    }
                }
            }
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
        $criteria->compare('bank_id',$this->bank_id);
        $criteria->compare('employee_id',$this->employee_id);
        $criteria->compare('card_holder',$this->card_holder,true);
        $criteria->compare('card_num',$this->card_num,true);
        $criteria->compare('create_time',$this->create_time);
        $criteria->compare('state',$this->state);
        $criteria->compare('is_deleted',$this->is_deleted);
        $criteria->compare('note',$this->note,true);
        $criteria->compare('ide_num',$this->ide_num);
        if ($this->startTime!= '') {
            $criteria->compare("create_time", ">= " . strtotime($this->startTime." 00:00:00"));
        }
        if ($this->endTime!= '') {
            $criteria->compare("create_time", "<= " . strtotime($this->endTime." 23:59:59"));
        }
        if ($this->bankName!= '') {
            $criteria->with[] = 'bank';
            $criteria->compare("bank.name", $this->bankName);
        }

//        if ($this->employeeName!= '') {
//            $criteria->with[] = 'employee';
//            $criteria->compare("employee.name", $this->employeeName);
//        }
//        if ($this->employeeMobile!= '') {
//            $criteria->with[] = 'employee';
//            $criteria->compare("employee.mobile", $this->employeeMobile);
//        }
//        if ($this->OAName!= '') {
//            $criteria->with[] = 'employee';
//            $criteria->compare("employee.username", $this->OAName);
//        }

//      ICE 上面三个搜索就是为了让查询更准确，而不是用表里面的什么，所以按照以下接入ice
		$employee_ids = array();
		if ($this->employeeName != '') {
			$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->employeeName));
			if (!empty($employees)) {
				foreach ($employees as $employee) {
					$employee_ids[] = $employee['czyId'];
				}
			}
		}
		if ($this->employeeMobile != '') {
			$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->employeeMobile));
			if (!empty($employees)) {
				foreach ($employees as $employee) {
					$employee_ids[] = $employee['czyId'];
				}
			}
		}
		if ($this->OAName != '') {
			$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->OAName));
			if (!empty($employees)) {
				foreach ($employees as $employee) {
					$employee_ids[] = $employee['czyId'];
				}
			}
		}
		if (!empty($employee_ids)) {
			$criteria->addInCondition('t.employee_id', array_unique($employee_ids));
		}


        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }


    public function getBankName()
    {
        return empty($this->bank)?'':$this->bank->name;
    }


//	    ICE接入
    public function getEmployeeName()
    {
//        return empty($this->employee)?'':$this->employee->name;
	    if(!empty($this->employee_id)){
		    $employee = ICEEmployee::model()->findbypk($this->employee_id);
		    if(!empty($employee['name'])){
			    return $employee['name'];
		    }

	    }

	    return '';
    }


//	    ICE接入
    public function getOAUserName()
    {
//        return empty($this->employee)?'':$this->employee->username;
	    if(!empty($this->employee_id)){
		    $employee = ICEEmployee::model()->findbypk($this->employee_id);
		    if(!empty($employee['username'])){
			    return $employee['username'];
		    }

	    }

	    return '';
    }

//	    ICE接入
    public function getEmployeeMobile()
    {
//        return empty($this->employee)?'':$this->employee->mobile;
//	    ICE接入
	    if(!empty($this->employee_id)){
		    $employee = ICEEmployee::model()->findbypk($this->employee_id);
		    if(!empty($employee->mobile)){
			    return $employee->mobile;
		    }

	    }

	    return '';
    }


    public function getBankList($flag=false,$extra=false){
        $list = array();
        $criteria = new CDbCriteria;  
        $criteria->condition = 'state=:state and is_deleted=:is_deleted';  
        $criteria->params = array(':state'=>0, ':is_deleted'=>0);   
        $model = CaiRedpacketBank::model()->findAll($criteria);
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
