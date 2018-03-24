<?php

/**
 * This is the model class for table "rxh_order".
 *
 * The followings are the available columns in table 'rxh_order':
 * @property integer $id
 * @property string $sn
 * @property string $rxh_sn
 * @property integer $customer_id
 * @property string $name
 * @property string $mobile
 * @property string $income_rate
 * @property string $type
 * @property string $amount
 * @property integer $licai_time_month
 * @property integer $licai_time_day
 * @property integer $pay_time
 * @property integer $create_time
 * @property string $inviter_mobile
 */
class RxhOrder extends CActiveRecord
{	
	public $modelName = '荣信汇平台交易订单';
	public $customer_name;
    public $customer_mobile;
    public $startTime;
    public $endTime;
    public $startPayTime;
    public $endPayTime;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rxh_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rxh_sn, name, mobile, type, amount, customer_id, pay_time', 'required'),
			// array(
   //              'domain',
   //              'unique',
   //              'caseSensitive' => false,
   //              'criteria' => array('condition' => 'is_deleted=0'),
   //              'on' => 'create, update'
   //          ),
			array('rxh_sn', 'unique', 'on' => 'newCreate'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.		
            array('customer_id, licai_time_month, licai_time_day, pay_time, create_time, inviter_id, sendCan, revise_type, sendCan_userid, ticheng_send_status, update_userid, send_type', 'numerical', 'integerOnly'=>true),
            array('sn, rxh_sn', 'length', 'max'=>32),
            array('name', 'length', 'max'=>255),
            array('mobile, inviter_mobile, revise_mobile, bind_mobile', 'length', 'max'=>15),
            array('income_rate, amount, ticheng_amount', 'length', 'max'=>10),
            array('type', 'length', 'max'=>20),
            array('sendCan_username, update_username', 'length', 'max'=>100),
            array('repayment_date, remark, note', 'safe'),
            array('revise_mobile', 'common.components.validators.ChinaMobileValidator', 'on' => 'inviterUpdate'),
            // array('revise_mobile', 'employee_exists', 'on' => 'inviterUpdate'),
            array('revise_type,revise_mobile', 'required', 'on' => 'inviterUpdate'),
            array('revise_type', 'in', 'range'=>array(0,1)),
            array('sendCan', 'in', 'range'=>array(2,3), 'on' => 'examine'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sn, rxh_sn, customer_id, name, mobile, inviter_id, inviter_mobile, income_rate, type, amount, licai_time_month, licai_time_day, pay_time, create_time, repayment_date, sendCan, remark, revise_type, revise_mobile, note, sendCan_username, sendCan_userid, sendCan_date, ticheng_send_status, update_username, update_userid, update_date, send_type, bind_mobile, ticheng_amount, customer_name, customer_mobile, startTime, endTime,', 'safe', 'on'=>'search'),

            array('id, sn, rxh_sn, customer_id, name, mobile, inviter_id, inviter_mobile, income_rate, type, amount, licai_time_month, licai_time_day, pay_time, create_time, repayment_date, sendCan, remark, revise_type, revise_mobile, note, sendCan_username, sendCan_userid, sendCan_date, ticheng_send_status, update_username, update_userid, update_date, send_type, bind_mobile, ticheng_amount, customer_name, customer_mobile, startTime, endTime, startPayTime, endPayTime', 'safe', 'on'=>'deductList_search'),
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
            'inviterRe' => array(self::BELONGS_TO, 'Employee', 'inviter_id'),
            'inviterReCus' => array(self::BELONGS_TO, 'Customer', 'inviter_id'),
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

    

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => '彩之云订单号',
			'rxh_sn' => '荣信汇订单号',
			'customer_id' => '彩之云用户ID',
			'name' => '投资人姓名',
			'mobile' => '投资人手机号',
			'income_rate' => '收益率',
			'type' => '理财产品类型',
			'amount' => '理财金额',
			'licai_time_month' => '理财时长月',
			'licai_time_day' => '理财时长天',
			'pay_time' => '荣信汇订单创建时间',
			'create_time' => '记录创建时间',
			'inviter_id' => '推荐人ID',
			'inviter_mobile' => '推荐人手机号码',
			'repayment_date' => '预计还款日',
			'customer_name' => '业主姓名',
            'customer_mobile' => '业主手机',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'sendCan' => '审核状态',
            'remark' => '审核备注',
            'revise_type' => '手机号码类型',
            'revise_mobile' => '修正推荐人手机号码',
            'note' => '备注',
            'sendCan_username' => '审核人OA',
            'sendCan_userid' => '审核人ID',
            'sendCan_date' => '审核时间',
            'ticheng_send_status' => '提成发放状态',
            'update_username' => '发放提成人OA',
            'update_userid' => '发放人id',
            'update_date' => '发放时间',
            'send_type' => '发放红包类型',
            'bind_mobile' => '绑定OA的手机号码',
            'ticheng_amount' => '提成金额',
            'startPayTime' => '投资开始时间',
            'endPayTime' => '投资结束时间',
            'custFlag' => '投资手机号码是否是彩之云用户',
            'inviterFlag' => '是否存在推荐人彩管家账号',
		);
	}


	public function customer_exists($html = false){
		if($this->customer_id==0){
			return ($html) ? "不存在" : "<span class='label label-important'>不存在</span>";
		}else{
			return ($html) ? "存在" : "<span class='label label-success'>存在</span>";
		}
	}



	public function inviter_exists($html = false){
		if($this->inviter_id==0){
			return ($html) ? "不存在" : "<span class='label label-important'>不存在</span>";
		}else{
			return ($html) ? "存在" : "<span class='label label-success'>存在</span>";
		}
	}

	static $send_status = array(
        0 => "未发放",
        1 => "提成红包发放成功",
        2 => "提成发放失败",
    );

    public static function getSendStatusNameList()
    {
        return CMap::mergeArray(array('' => '全部'), self::$send_status);
    }


    static $sendCan_status = array(
        0 => "未审核",
        1 => "审核中",
        2 => "审核成功",
        3 => "审核失败",
    );

    public static function getSendCanStatusNameList()
    {
        return CMap::mergeArray(array('' => '全部'), self::$sendCan_status);
    }


    static $revise_type = array(
        0 => "发放到彩管家",
        1 => "发放到彩之云",
    );



    //提成发放状态
    public function getSendStatusName($html = false)
    {
        if($this->ticheng_send_status==1){
        	return ($html) ? self::$send_status[$this->ticheng_send_status] : "<span class='label label-success'>".self::$send_status[$this->ticheng_send_status]."</span>";
        }else{
        	return ($html) ? self::$send_status[$this->ticheng_send_status] : "<span class='label label-important'>".self::$send_status[$this->ticheng_send_status]."</span>";
        }
    }

    //审核状态
    public function getSendCanStatusName($html = false)
    {
        if($this->sendCan==2){
            return ($html) ? self::$sendCan_status[$this->sendCan] : "<span class='label label-success'>".self::$sendCan_status[$this->sendCan]."</span>";
        }else{
            return ($html) ? self::$sendCan_status[$this->sendCan] : "<span class='label label-important'>".self::$sendCan_status[$this->sendCan]."</span>";
        }
    }


    //号码类型
    public function getReviseTypeName($html = false)
    {
        return ($html) ? self::$revise_type[$this->revise_type] : "<span class='label label-success'>".self::$revise_type[$this->revise_type]."</span>";
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
		$criteria->compare('rxh_sn',$this->rxh_sn,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('income_rate',$this->income_rate,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('licai_time_month',$this->licai_time_month);
		$criteria->compare('licai_time_day',$this->licai_time_day);
		$criteria->compare('pay_time',$this->pay_time);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('inviter_mobile',$this->inviter_mobile,true);
		$criteria->compare('repayment_date',$this->repayment_date,true);
		if($this->customer_name || $this->customer_mobile){
            $criteria->with[] = 'customer';
            $criteria->compare('customer.name', $this->customer_name, true);
            $criteria->compare('customer.mobile', $this->customer_mobile, true);
        }
        $criteria->compare('sendCan',$this->sendCan);
        $criteria->compare('remark',$this->remark,true);
        $criteria->compare('revise_type',$this->revise_type);
        $criteria->compare('revise_mobile',$this->revise_mobile,true);
        $criteria->compare('note',$this->note,true);
        $criteria->compare('sendCan_username',$this->sendCan_username,true);
        $criteria->compare('sendCan_userid',$this->sendCan_userid);
        $criteria->compare('sendCan_date',$this->sendCan_date,true);
        $criteria->compare('ticheng_send_status',$this->ticheng_send_status);
        $criteria->compare('update_username',$this->update_username,true);
        $criteria->compare('update_userid',$this->update_userid);
        $criteria->compare('update_date',$this->update_date,true);
        $criteria->compare('send_type',$this->send_type);
        $criteria->compare('bind_mobile',$this->bind_mobile,true);
        $criteria->compare('ticheng_amount',$this->ticheng_amount,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



	//提成奖励搜索    
    public function deductList_search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('rxh_sn',$this->rxh_sn,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('income_rate',$this->income_rate,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('licai_time_month',$this->licai_time_month);
		$criteria->compare('licai_time_day',$this->licai_time_day);
		$criteria->compare('pay_time',$this->pay_time);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('inviter_mobile',$this->inviter_mobile,true);
		$criteria->compare('repayment_date',$this->repayment_date,true);
		$criteria->compare('sendCan',$this->sendCan);
        $criteria->compare('remark',$this->remark,true);
        $criteria->compare('revise_type',$this->revise_type);
        $criteria->compare('revise_mobile',$this->revise_mobile,true);
        $criteria->compare('note',$this->note,true);
        $criteria->compare('sendCan_username',$this->sendCan_username,true);
        $criteria->compare('sendCan_userid',$this->sendCan_userid);
        $criteria->compare('sendCan_date',$this->sendCan_date,true);
        $criteria->compare('ticheng_send_status',$this->ticheng_send_status);
        $criteria->compare('update_username',$this->update_username,true);
        $criteria->compare('update_userid',$this->update_userid);
        $criteria->compare('update_date',$this->update_date,true);
        $criteria->compare('send_type',$this->send_type);
        $criteria->compare('bind_mobile',$this->bind_mobile,true);
        $criteria->compare('ticheng_amount',$this->ticheng_amount,true);
        if ($this->startPayTime != '') {
            $criteria->compare("`t`.pay_time", ">= " . strtotime($this->startPayTime." 00:00:00"));
        }
        if ($this->endPayTime != '') {
            $criteria->compare("`t`.pay_time", "<= " . strtotime($this->endPayTime." 23:59:59"));
        }
        return new ActiveDataProvider($this, 
            array(
                'criteria' => $criteria, 
                'sort' => array('defaultOrder' => '`t`.create_time desc',)
                )
            );        
    }


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RxhOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public static function createOrder($feeAttr)
    {
        //判断参数
        if (empty($feeAttr)) {
            RxhOrder::model()->addError('id', "接收荣信汇订单数据失败！");
            return false;
        }

        //创建我们的订单记录及记录
        $other = new RxhOrder();
        $other->setScenario('newCreate');
        $other->attributes = $feeAttr;
        if (!$other->save()) {
        	// var_dump($other->getErrors());die;
            RxhOrder::model()->addError('id', "接收荣信汇订单数据失败！");
            return false;
        }
        return true;
    }
}
