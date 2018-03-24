<?php

/**
 * This is the model class for table "conduct_data".
 *
 * The followings are the available columns in table 'conduct_data':
 * @property integer $id
 * @property string $sn
 * @property string $thirdSn
 * @property string $type
 * @property integer $employee_id
 * @property string $amount
 * @property integer $create_time
 * @property integer $status
 * @property string $note
 */
class ConductData extends CActiveRecord
{	

	public $modelName = '投资E理财红包订单';
	public $startTime;
    public $endTime;
    public $employeeName;
    public $OAName;
    public $employeeMobile;


	static $fees_status = array(//待审核0 //提现成功99 //提现失败98
        //Item::FEES_AWAITING_PAYMENT => "待审核",
        Item::FEES_TRANSACTION_SUCCESS => "红包扣除成功",
        Item::FEES_CANCEL => "红包扣除失败",
    );


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'conduct_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, create_time, status', 'numerical', 'integerOnly'=>true),
			array('sn, thirdSn', 'length', 'max'=>32),
			array('type', 'length', 'max'=>20),
			array('amount', 'length', 'max'=>10),
			array('note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sn, thirdSn, type, employee_id, amount, create_time, status, note, startTime, endTime, employeeName, OAName, employeeMobile', 'safe', 'on'=>'search'),
			array('id, sn, thirdSn, type, employee_id, amount, create_time, status, note, startTime, endTime, employeeName, OAName, employeeMobile', 'safe', 'on'=>'report_search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
        );
	}



	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => 'sn',
			'thirdSn' => '第三方SN',
			'type' => '投资类型',
			'employee_id' => '员工ID',
			'amount' => '红包消费金额',
			'create_time' => '创建时间',
			'status' => '状态',
			'note' => '订单备注',
			'employeeName' => '员工姓名',
            'OAName' => 'OA账号',
            'employeeMobile' => '员工手机',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
		);
	}

	public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
        );
    }


    public function getEmployeeName()
    {
        // return empty($this->employee)?'':$this->employee->name;

        if(!empty($this->employee_id)){
            $employee = ICEEmployee::model()->findbypk($this->employee_id);
            if(!empty($employee['name'])){
                return $employee['name'];
            }

        }

        return '';
    }



    public function getOAUserName()
    {
        // return empty($this->employee)?'':$this->employee->username;       

        if(!empty($this->employee_id)){
            $employee = ICEEmployee::model()->findbypk($this->employee_id);
            if(!empty($employee['username'])){
                return $employee['username'];
            }

        }

        return '';
    }


    public function getEmployeeMobile()
    {
        // return empty($this->employee)?'':$this->employee->mobile;
  
//      ICE接入
        if(!empty($this->employee_id)){
            $employee = ICEEmployee::model()->findbypk($this->employee_id);
            if(!empty($employee->mobile)){
                return $employee->mobile;
            }

        }

        return '';
    }



    public function getCreateTime()
    {
        return date("Y-m-d H:i:s", $this->create_time);
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
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('thirdSn',$this->thirdSn,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('amount',$this->amount,true);
		// $criteria->compare('create_time',$this->create_time);
		if ($this->startTime!= '') {
            $criteria->compare("create_time", ">= " . strtotime($this->startTime." 00:00:00"));
        }
        if ($this->endTime!= '') {
            $criteria->compare("create_time", "<= " . strtotime($this->endTime." 23:59:59"));
        }
//        if ($this->employeeName!= '') {
//            $criteria->with[] = 'employee';
//            $criteria->compare("employee.name", $this->employeeName);
//        }
//
//        if ($this->employeeMobile!= '') {
//            $criteria->with[] = 'employee';
//            $criteria->compare("employee.mobile", $this->employeeMobile);
//        }
//
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



		$criteria->compare('status',$this->status);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' =>array('defaultOrder' => '`t`.create_time desc'),
		));
	}



	public function report_search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria=new CDbCriteria;
        if (isset($_GET['ConductData']) && !empty($_GET['ConductData'])) {
            $_SESSION['ConductData'] = array();
            $_SESSION['ConductData'] = $_GET['ConductData'];
        }
        if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
            if (isset($_SESSION['ConductData']) && !empty($_SESSION['ConductData'])) {
                foreach ($_SESSION['ConductData'] as $key => $val) {
                    if ($val != "") {
                        $this->$key = $val;
                    }
                }
            }
        }

        $criteria->compare('id',$this->id);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('thirdSn',$this->thirdSn,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('amount',$this->amount,true);
        if ($this->startTime!= '') {
            $criteria->compare("create_time", ">= " . strtotime($this->startTime." 00:00:00"));
        }
        if ($this->endTime!= '') {
            $criteria->compare("create_time", "<= " . strtotime($this->endTime." 23:59:59"));
        }

        $criteria->with[] = 'employee';
        if ($this->employeeName!= '') {            
            $criteria->compare("employee.name", $this->employeeName);
        }

        if ($this->employeeMobile!= '') {
            $criteria->compare("employee.mobile", $this->employeeMobile);
        }
        
        if ($this->OAName!= '') {
            $criteria->compare("employee.username", $this->OAName);
        }
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }



	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ConductData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function createRedPacketOrder($feeAttr)
    {
        //判断参数
        if (empty($feeAttr)) {
            ConductData::model()->addError('id', "投资E理财消费红包失败！");
            return false;
        }

        //创建我们的订单记录及记录
        $other = new ConductData();
        $other->attributes = $feeAttr;
        // var_dump($other);die;
        if (!$other->save()) {
            ConductData::model()->addError('id', "投资E理财消费红包失败！");
            return false;
        } else {
            //写订单成功日志
            $items = array(
                'employee_id' => $other->employee_id,//员工ID
                'sum' => $other->amount,//红包金额,
                'sn' => $other->sn,
                'to_type' => Item::CAI_RED_PACKET_TO_TYPE_ELICAI_TOUZI,
            );
            $redPacked = new CaiRedPacket();
            if(!$redPacked->consumeRedPacker($items)){
                @$other->delete();
                return false;
            }
        	Yii::log("投资E理财消费彩管家红包,订单信息保存成功：订单:{$other->sn},消费金额:{$other->amount},用户：{$other->employee->name}({$other->employee_id})", CLogger::LEVEL_INFO, 'colourlife.core.ConductData.createRedPacketOrder');
        }
        return true;

    }



    public function getStatusName($html = false)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$fees_status[$this->status];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }

    

    public static function getStatusNames()
    {
        return CMap::mergeArray(array('' => '全部'), self::$fees_status);
    }
	


}
