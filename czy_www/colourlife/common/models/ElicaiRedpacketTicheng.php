<?php

/**
 * This is the model class for table "elicai_redpacket_ticheng".
 *
 * The followings are the available columns in table 'elicai_redpacket_ticheng':
 * @property integer $id
 * @property string $sn
 * @property string $e_sn
 * @property integer $customer_id
 * @property string $name
 * @property string $mobile
 * @property string $type
 * @property string $amount
 * @property integer $create_time
 * @property integer $status
 * @property integer $sendCan
 * @property string $remark
 * @property integer $pay_time
 * @property integer $is_receive
 * @property integer $inviter_id
 * @property string $inviter_name
 * @property string $inviter_mobile
 * @property string $note
 */
class ElicaiRedpacketTicheng extends CActiveRecord
{	
	public $modelName = 'E理财投资提成奖励订单';
    public $startPayTime;
    public $endPayTime;



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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'elicai_redpacket_ticheng';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('customer_id, month, create_time, status, send_type, sendCan, pay_time, is_receive, inviter_id, sendCan_userid, ticheng_send_status, update_userid', 'numerical', 'integerOnly'=>true),
            array('sn, e_sn', 'length', 'max'=>32),
            array('name, inviter_name', 'length', 'max'=>255),
            array('mobile, inviter_mobile, bind_mobile', 'length', 'max'=>15),
            array('type', 'length', 'max'=>20),
            array('amount,ticheng_amount', 'length', 'max'=>10),
            array('sendCan_username, update_username', 'length', 'max'=>100),
            array('remark, note', 'safe'),
            array('revise_mobile', 'common.components.validators.ChinaMobileValidator', 'on' => 'inviterUpdate'),
            // array('revise_mobile', 'employee_exists', 'on' => 'inviterUpdate'),
            array('revise_type,revise_mobile', 'required', 'on' => 'inviterUpdate'),
            array('revise_type', 'in', 'range'=>array(0,1)),
            array('sendCan', 'in', 'range'=>array(2,3), 'on' => 'examine'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sn, e_sn, customer_id, name, mobile, type, month, amount, create_time, status, sendCan, remark, pay_time, is_receive, inviter_id, inviter_name, inviter_mobile, note, sendCan_username, sendCan_userid, sendCan_date, ticheng_send_status, update_username, update_userid, update_date, bind_mobile', 'safe', 'on'=>'search'),

            array('id, sn, e_sn, customer_id, name, mobile, type, month, amount, create_time, status, sendCan, remark, pay_time, is_receive, inviter_id, inviter_name, inviter_mobile, note, sendCan_username, sendCan_userid, sendCan_date, ticheng_send_status, update_username, update_userid, update_date, startPayTime, endPayTime', 'safe', 'on'=>'deductList_search'),
        );
	}


	// public function employee_exists($attribute,$params){
 //        if(empty($this->revise_mobile)){
 //                $this->addError($attribute,"修改的推荐人手机号码不能为空");
 //        }else{
 //            if($this->revise_type==0){
 //                $model = Employee::model()->find("mobile='".$this->revise_mobile."' and state=0 and is_deleted=0");
 //                if(!$model){
 //                    $this->addError($attribute,"彩之云手机用户不存在或者异常");
 //                }
 //            }else{
 //                $model = Customer::model()->find("mobile='".$this->revise_mobile."' and state=0 and is_deleted=0");
 //                if(!$model){
 //                    $this->addError($attribute,"彩管家手机用户不存在或者异常"); 
 //                }
 //            }
 //        }
 //    }

	

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





	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => '彩之云订单号',
			'e_sn' => '合和年订单号',
			'customer_id' => '用户ID',
			'name' => '姓名',
			'mobile' => '业主手机号',
			'type' => '理财产品类型',
			'month' => '投资周期',
			'amount' => '理财金额',
			'create_time' => '传递时间',
			'status' => '订单状态',
			'sendCan' => '审核状态',
			'remark' => '审核备注',
			'pay_time' => '订单支付时间',
			'is_receive' => '是否成功接收数据',
			'inviter_id' => '推荐人ID',
			'inviter_name' => '推荐人姓名',
			'inviter_mobile' => '推荐人手机号码',
			'note' => '备注',
			'sendCan_username' => '审核人OA',
            'sendCan_userid' => '审核人ID',
            'sendCan_date' => '审核时间',
            'ticheng_send_status' => '提成发放状态',
            'update_username' => '发放提成人OA',
            'update_userid' => '发放人id',
            'update_date' => '发放时间',
            'startPayTime' => '投资开始时间',
            'endPayTime' => '投资结束时间',
            'custFlag' => '是否存在彩之云用户',
            'inviterFlag' => '是否存在推荐人彩管家账号',
            'revise_type' => '手机号码类型',
            'revise_mobile' => '修正推荐人手机号码',
            'bind_mobile' => '绑定OA手机号码',
            'send_type' => '发放类型',
            'ticheng_amount' => '提成金额',
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
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('e_sn',$this->e_sn,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('month',$this->month);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('sendCan',$this->sendCan);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('pay_time',$this->pay_time);
		$criteria->compare('is_receive',$this->is_receive);
		$criteria->compare('inviter_id',$this->inviter_id);
		$criteria->compare('inviter_name',$this->inviter_name,true);
		$criteria->compare('inviter_mobile',$this->inviter_mobile,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('sendCan_username',$this->sendCan_username,true);
        $criteria->compare('sendCan_userid',$this->sendCan_userid);
        $criteria->compare('sendCan_date',$this->sendCan_date,true);
        $criteria->compare('ticheng_send_status',$this->ticheng_send_status);
        $criteria->compare('update_username',$this->update_username,true);
        $criteria->compare('update_userid',$this->update_userid);
        $criteria->compare('update_date',$this->update_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
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




    //提成奖励搜索    
    public function deductList_search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('e_sn',$this->e_sn,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('month',$this->month);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('sendCan',$this->sendCan);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('pay_time',$this->pay_time);
		$criteria->compare('is_receive',$this->is_receive);
		$criteria->compare('inviter_id',$this->inviter_id);
		$criteria->compare('inviter_name',$this->inviter_name,true);
		$criteria->compare('inviter_mobile',$this->inviter_mobile,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('sendCan_username',$this->sendCan_username,true);
        $criteria->compare('sendCan_userid',$this->sendCan_userid);
        $criteria->compare('sendCan_date',$this->sendCan_date,true);
        $criteria->compare('ticheng_send_status',$this->ticheng_send_status);
        $criteria->compare('update_username',$this->update_username,true);
        $criteria->compare('update_userid',$this->update_userid);
        $criteria->compare('update_date',$this->update_date,true);
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




    public static function createOrder($feeAttr)
    {
        //判断参数
        if (empty($feeAttr)) {
            ElicaiRedpacketTicheng::model()->addError('id', "接收E理财推荐奖励数据失败！");
            return false;
        }

        //创建我们的订单记录及记录
        $other = new ElicaiRedpacketTicheng();
        $other->attributes = $feeAttr;
        // var_dump($other);die;
        if (!$other->save()) {
            ElicaiRedpacketTicheng::model()->addError('id', "接收E理财推荐奖励数据失败！");
            return false;
        }    
        // } else {
        //     //写订单成功日志
        //     $items = array(
        //         'employee_id' => $other->employee_id,//员工ID
        //         'sum' => $other->amount,//红包金额,
        //         'sn' => $other->sn,
        //         'to_type' => Item::CAI_RED_PACKET_TO_TYPE_ELICAI_TOUZI,
        //     );
        //     $redPacked = new CaiRedPacket();
        //     if(!$redPacked->consumeRedPacker($items)){
        //         @$other->delete();
        //         return false;
        //     }
        // 	// Yii::log("接收E理财推荐奖励数据成功,订单:{$other->sn},消费金额:{$other->amount},用户：{$other->employee->name}({$other->employee_id})", CLogger::LEVEL_INFO, 'colourlife.core.ConductData.createRedPacketOrder');
        // }
        return true;

    }


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ElicaiRedpacketTicheng the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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


}
