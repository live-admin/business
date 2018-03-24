<?php

/**
 * This is the model class for table "conduct_redeem".
 *
 * The followings are the available columns in table 'conduct_redeem':
 * @property integer $id
 * @property string $sn
 * @property string $thirdSn
 * @property string $type
 * @property integer $employee_id
 * @property string $amount
 * @property integer $create_time
 * @property integer $redeem_status
 * @property integer $redeem_time
 * @property integer $expiration_time
 * @property string $income_rate
 * @property string $sum
 * @property string $relation_sn
 * @property integer $month
 * @property string $note
 */
class ConductRedeem extends CActiveRecord
{	
	public $modelName = '投资E理财赎回订单';
	public $startTime;
    public $endTime;
    public $employeeName;
    public $OAName;
    public $employeeMobile;
	static $redeem_status = array(//未赎回0 //赎回成功88
        88 => "赎回成功",
        0 => "未赎回",
    );


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'conduct_redeem';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, create_time, redeem_status, redeem_time, expiration_time, month', 'numerical', 'integerOnly'=>true),
			array('sn, thirdSn, relation_sn', 'length', 'max'=>32),
			array('type', 'length', 'max'=>20),
			array('amount, income_rate, sum', 'length', 'max'=>10),
			array('note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sn, thirdSn, type, employee_id, amount, create_time, redeem_status, redeem_time, expiration_time, income_rate, sum, relation_sn, month, note, startTime, endTime, employeeName, OAName, employeeMobile', 'safe', 'on'=>'search'),
			array('id, sn, thirdSn, type, employee_id, amount, create_time, redeem_status, redeem_time, expiration_time, income_rate, sum, relation_sn, month, note, startTime, endTime, employeeName, OAName, employeeMobile', 'safe', 'on'=>'report_search'),
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
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'id',
			'sn' => 'sn',
			'thirdSn' => '第三方SN',
			'type' => '投资类型',
			'employee_id' => '员工ID',
			'amount' => '红包消费金额',
			'create_time' => '创建时间',
			'redeem_status' => '赎回状态',
			'redeem_time' => '赎回时间',
			'expiration_time' => '到期时间',
			'income_rate' => '收益率',
			'sum' => '到期返回金额',
			'relation_sn' => '投资sn',
			'month' => '购买时限',
			'note' => '订单备注',
			'employeeName' => '员工姓名',
            'OAName' => 'OA账号',
            'employeeMobile' => '员工手机',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
		);
	}


	public function getEmployeeName()
    {
        return empty($this->employee)?'':$this->employee->name;
    }



    public function getOAUserName()
    {
        return empty($this->employee)?'':$this->employee->username;
    }


    public function getEmployeeMobile()
    {
        return empty($this->employee)?'':$this->employee->mobile;
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
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('redeem_status',$this->redeem_status);
		$criteria->compare('redeem_time',$this->redeem_time);
		$criteria->compare('expiration_time',$this->expiration_time);
		$criteria->compare('income_rate',$this->income_rate,true);
		$criteria->compare('sum',$this->sum,true);
		$criteria->compare('relation_sn',$this->relation_sn,true);
		$criteria->compare('month',$this->month);
		$criteria->compare('note',$this->note,true);
		if ($this->startTime!= '') {
            $criteria->compare("create_time", ">= " . strtotime($this->startTime." 00:00:00"));
        }
        if ($this->endTime!= '') {
            $criteria->compare("create_time", "<= " . strtotime($this->endTime." 23:59:59"));
        }
        if ($this->employeeName!= '') {
            $criteria->with[] = 'employee';
            $criteria->compare("employee.name", $this->employeeName);
        }

        if ($this->employeeMobile!= '') {
            $criteria->with[] = 'employee';
            $criteria->compare("employee.mobile", $this->employeeMobile);
        }

        if ($this->OAName!= '') {
            $criteria->with[] = 'employee';
            $criteria->compare("employee.username", $this->OAName);
        }
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function report_search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        $criteria=new CDbCriteria;
        if (isset($_GET['ConductRedeem']) && !empty($_GET['ConductRedeem'])) {
            $_SESSION['ConductRedeem'] = array();
            $_SESSION['ConductRedeem'] = $_GET['ConductRedeem'];
        }
        if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
            if (isset($_SESSION['ConductRedeem']) && !empty($_SESSION['ConductRedeem'])) {
                foreach ($_SESSION['ConductRedeem'] as $key => $val) {
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
	 * @return ConductRedeem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function createRedPacketOrder($feeAttr,$relation_id)
    {
        //判断参数
        if (empty($feeAttr)) {
            ConductRedeem::model()->addError('id', "接受彩管家E理财赎回订单失败！");
            return false;
        }

        //创建我们的订单记录及记录
        $other = new ConductRedeem();
        $other->attributes = $feeAttr;
        if (!$other->save()) {
            ConductRedeem::model()->addError('id', "接受彩管家E理财赎回订单失败！");
            return false;
        } else {
			if (!ConductData::model()->updateByPk($relation_id, array('redeem_status'=>88,'redeem_time'=>time()))) {
				@$other->delete();
                return false;
			}
        	Yii::log("接受彩管家E理财赎回订单,订单信息保存成功：订单:{$other->sn},理财订单号:{$other->relation_sn},理财金额:{$other->amount},到期返还金额:{$other->sum},用户：{$other->employee->name}({$other->employee_id})", CLogger::LEVEL_INFO, 'colourlife.core.ConductRedeem.createRedPacketOrder');
        }
        return true;
    }



    public function getRedeemStatusName($html = false)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$redeem_status[$this->redeem_status];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }
    


     public static function getRedeemStatusNames()
    {
        return CMap::mergeArray(array('' => '全部'), self::$redeem_status);
    }

}
