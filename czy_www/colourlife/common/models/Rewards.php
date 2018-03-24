<?php

/**
 * This is the model class for table "rewards".
 *
 * The followings are the available columns in table 'caifu_auto':
 * @property integer $id
 * @property string $sn
 * @property string $rela_sn
 * @property integer $customer_id
 * @property string $model
 * @property string $type
 * @property string $amount
 * @property string $reduction
 * @property string $earnings
 * @property string $community_rate
 * @property integer $mitigate_starttime
 * @property integer $mitigate_endtime
 * @property string $note
 * @property string $create_ip
 * @property integer $create_time
 * @property integer $status
 * @property integer $pay_time
 * @property integer $update_time
 * @property integer $is_receive
 * @property string $erp_reduction
 * @property integer $inviter
 * @property integer $sendCan
 * @property string $remark
 * @property string $sendCan_username
 * @property integer $sendCan_userid
 * @property string $sendCan_date
 * @property integer $ticheng_send_status
 * @property string $update_username
 * @property integer $update_userid
 * @property string $update_date
 */
class Rewards extends CActiveRecord
{   

    public $modelName = '交易奖励';
    
    public $start_time_klint;
    public $end_time_klint;

    public $reward_send_status;

    //public $region;
    //public $community_id;
    //public $branch_id;
  
    public $startTime;
    public $endTime;
    public $oprId;

    static $fees_status = array(
        Item::FEES_AWAITING_PAYMENT => "待付款", //0
        Item::FEES_TRANSACTION_SUCCESS => "交易成功", //99
        // Item::FEES_TRANSACTION_ERROR => '已付款',
        Item::FEES_TRANSACTION_ERROR => '已授权',  //1
        Item::FEES_CANCEL => "订单已取消",		//98
        Item::FEES_TRANSACTION_REFUND => '已退款',//90
    );


    // static $sendCan_status = array(
    //     Item::FEES_AWAITING_PAYMENT => "待审核", //0
    //     Item::FEES_TRANSACTION_ERROR => '审核通过',//1
    //     Item::FEES_CANCEL => "审核失败",//98
    // );

    static $fees_status_num = array(
        Item::FEES_AWAITING_PAYMENT => 0,
        Item::FEES_TRANSACTION_SUCCESS => 2,
        Item::FEES_CANCEL => 1,
        // Item::FEES_TRANSACTION_ERROR => 3,
        Item::FEES_TRANSACTION_ERROR => 0,
        Item::FEES_TRANSACTION_REFUND => 4,
    );

    static $send_status = array(
        0 => "未发放",
        1 => "提成发放成功",
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

    public function customer_exists($html = false){
        if($this->customer_id==0){
            return ($html) ? "不存在" : "<span class='label label-important'>不存在</span>";
        }else{
            return ($html) ? "存在" : "<span class='label label-success'>存在</span>";
        }
    }

    public function rewarder_exists($html = false){
    	if(empty($this->reward_userid)){
            return ($html) ? "不存在" : "<span class='label label-important'>不存在</span>";
        }else{
            return ($html) ? "存在" : "<span class='label label-success'>存在</span>";
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
		return 'rewards';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sn, customer_id, amount', 'required'),
			///array('customer_id, reward_userid,  create_time, update_time, status, sendCan, sendCan_userid, ticheng_send_status, update_userid, community_id, branch_id, inviter, pay_time, is_send_code, send_type', 'numerical', 'integerOnly'=>true),
			array('customer_id, create_time, update_time, status, sendCan, ticheng_send_status, inviter, pay_time, is_send_code, send_type, revise_type, istate_all, rewardparam', 'numerical', 'integerOnly'=>true),
			array('amount, allot_all, reward', 'numerical', 'integerOnly'=>false),
			array('sn, rela_sn, fpiao_sn', 'length', 'max'=>32),
			array('community_id, branch_id, region_id', 'length', 'max'=>50),
			array('create_ip, model, user_id, reward_userid, jobkey', 'length', 'max'=>20),
			array('reward_userid', 'length', 'max'=>10),
			array('customer_mobile, reward_mobile, inviter_mobile, revise_mobile', 'length', 'max'=>15),
			array('customer_name, reward_username, reward_jobs, region, sendCan_username, inviter_name, type, inviter_oa, branch, community', 'length', 'max'=>100),
			array('note, remark, order_des', 'safe'),
            array('sendCan', 'in', 'range'=>array(2,3), 'on' => 'examine'),
            
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sn, customer_id, customer_name,user_id, customer_mobile, reward_userid, reward_username, reward_mobile, reward_jobs, amount, allot_all, reward, model, rela_sn, order_des, note, create_ip, create_time, status, update_time, sendCan, remark, sendCan_username, sendCan_userid, sendCan_date, ticheng_send_status, update_username, region, update_userid, update_date, community_id, branch_id, inviter, inviter_name, inviter_mobile, rewardparam, type, pay_time, is_send_code, send_type, istate_all, startTime, endTime, jobkey, fpiao_sn', 'safe', 'on'=>'search'),
			array('id, sn, customer_id, customer_name,user_id, customer_mobile, reward_userid, reward_username, reward_mobile, reward_jobs, amount, allot_all, reward, model, rela_sn, order_des, note, create_ip, create_time, status, update_time, sendCan, remark, sendCan_username, sendCan_userid, sendCan_date, ticheng_send_status, update_username, region, update_userid, update_date, community_id, branch_id, inviter, inviter_name, inviter_mobile, rewardparam, type, pay_time, is_send_code, send_type, istate_all, startTime, endTime, jobkey, fpiao_sn', 'safe', 'on'=>'rewards_search'),
		);
	}


	/**
	 * @return array customized attribute labels (name=>label)
	 * istate_all 说明：0x3为控制是否有修改，0x30控制是否红包发生，0xF00控制获奖人类型
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => '订单号SN',
			'customer_id' => '用户ID',
			'customer_name' => '用户姓名',
			'user_id' => '用户OA',
			'customer_mobile' => '用户手机',
			'reward_userid' => '获奖励人ID',
			'reward_username' => '获奖励人',
			'reward_mobile' => '获奖励人手机',
			'jobkey'  => '职位key',
			'istate_all' => '状态集',
			'reward_jobs' => '获奖励人职位',		
			'amount' => '成交金额',
			'allot_all' => '可提成总金额',
			'reward' => '奖励金额',
			'model' => '订单类型',

			'rela_sn' => '对应表单SN',
			'order_des' => '订单概叙',
			'note' => '备注',
			'create_ip' => '创建IP',
			'create_time' => '下单时间',
			'status' => '状态',
			'update_time' => '更新时间',
			'sendCan' => '审核状态',
			'remark' => '审核备注',
			'sendCan_username' => '审核人OA',
			'sendCan_userid' => '审核人ID',
			'sendCan_date' => '审核时间',
			'ticheng_send_status' => '提成发放状态',
			'fpiao_sn' => '饭票SN',
			'update_username' => '发放提成人OA',
			'update_userid' => '发放人id',
			'update_date' => '发放时间',
            'region' => '地区',
            'branch_id' => '管辖部门ID',
            'community_id' => '小区ID',
			'inviter' => '推荐人ID',
			'inviter_name' => '推荐人',
            'inviter_mobile' => '推荐人手机',
            'rewardparam' => '奖励参数',
			'type' => '理财产品类型',
			'pay_time' => '支付时间',
			'is_send_code' => '发放标记',
			'send_type' => '发放红包类型',
		
			'revise_type' => '手机号码类型',
            'revise_mobile' => '新获奖人手机',
			'inviter_oa' => '推荐人OA',
			'region_id' => '地区ID',
			'branch' => '管辖部门',
			'community' => '小区',
			'rewardFlag' => '获奖人彩管家账号',
		
			'startTime' => '开始时间',
            'endTime' =>  '结束时间',
			'start_time_klint' => '开始时间',
            'end_time_klint' => '截止时间',
			'reward_send_status' => '提成发放状态',
			'oprId' => '操作',
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
        	/*
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'logs' => array(self::HAS_MANY, 'OthersFeesLog', 'others_fees_id'),
            'AdvanceFees' => array(self::BELONGS_TO, 'AdvanceFee', 'object_id'),
            'PropertyFees' => array(self::BELONGS_TO, 'PropertyFees', 'object_id'),
            'ParkingFees' => array(self::BELONGS_TO, 'ParkingFees', 'object_id'),
            'PropertyActivityRate' => array(self::BELONGS_TO, 'PropertyActivityRate', 'rate_id'),
            'FeeDeductionProperty' => array(self::HAS_MANY, 'FeeDeductionProperty', 'orderID'),
            'inviterRe' => array(self::BELONGS_TO, 'Employee', 'inviter'),
            'inviterReCus' => array(self::BELONGS_TO, 'Customer', 'inviter'),
            */
        );
    }
    

    public static function checkIsExist($mobile){
        return Employee::model()->find('mobile=:mobile',array(':mobile'=>$mobile));
    }

    public static function checkIsExistByName($username){
        return Employee::model()->find('username=:username',array(':username'=>$username));
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
		$criteria->compare('customer_id',$this->customer_id,false);
		if(!empty($this->user_id)){
			$criteria->compare('user_id',$this->user_id,false);
		}
		$criteria->compare('model',$this->model,false);
		
		$criteria->compare('rela_sn',$this->rela_sn,false);
		$criteria->compare('status',$this->status,false);
		
		$criteria->compare('inviter',$this->inviter,true);
		$criteria->compare('sendCan',$this->sendCan,false);
		$criteria->compare('sendCan_userid',$this->sendCan_userid,false);
		
		$criteria->compare('inviter_name',$this->inviter_name,true);
		$criteria->compare('inviter_mobile',$this->inviter_mobile,false);
		
		$criteria->compare('ticheng_send_status',$this->ticheng_send_status,false);
		$criteria->compare('update_userid',$this->update_userid,false);

		$criteria->compare('reward_username',$this->reward_username,true);
		$criteria->compare('reward_mobile',$this->reward_mobile,true);
		$criteria->compare('fpiao_sn',$this->fpiao_sn, true);
		
    	if(!empty($this->type)){
			$criteria->compare('type',$this->type, false);
		}
    	if(!empty($this->is_send_code)){
			$criteria->compare('is_send_code',$this->is_send_code,false);
		}
    	if(!empty($this->send_type)){
			$criteria->compare('send_type',$this->send_type,false);
		}
        //$criteria->order ='`t`.create_time desc';
        return new ActiveDataProvider($this, array('criteria' => $criteria, ));
    }


    //提成奖励搜索    
    public function rewards_search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('`t`.id',$this->id);
        $criteria->compare('`t`.sn',$this->sn,true);
        $criteria->compare('`t`.rela_sn',$this->rela_sn,true);
        $criteria->compare('`t`.customer_id',$this->customer_id,true);
    	if(!empty($this->user_id)){
			$criteria->compare('`t`.user_id', $this->user_id,true);
		}
		$criteria->compare('`t`.customer_name',$this->customer_name,true);
		$criteria->compare('`t`.customer_mobile',$this->customer_mobile,true);
        $criteria->compare('`t`.model',$this->model,true);
        $criteria->compare('`t`.amount',$this->amount,true);
        $criteria->compare('`t`.status',$this->status,true);
        $criteria->compare('`t`.sendCan',$this->sendCan,true);
        $criteria->compare('`t`.update_time',$this->update_time,true);
        $criteria->compare('`t`.sendCan',$this->sendCan,true);
        $criteria->compare('`t`.inviter',$this->inviter,true);
        $criteria->compare('`t`.sendCan_username',$this->sendCan_username,true);
        $criteria->compare('`t`.sendCan_userid',$this->sendCan_userid,true);
        
        $criteria->compare('`t`.ticheng_send_status',$this->ticheng_send_status,true);
        $criteria->compare('`t`.update_username',$this->update_username,true);
        $criteria->compare('`t`.update_userid',$this->update_userid,true);
        
        $criteria->compare('`t`.reward_username',$this->reward_username,true);
        $criteria->compare('`t`.reward_mobile',$this->reward_mobile,true);
        
        $criteria->compare('`t`.inviter_name',$this->inviter_name,true);
        $criteria->compare('`t`.inviter_mobile',$this->inviter_mobile,true);
        $criteria->compare('`t`.fpiao_sn',$this->fpiao_sn, true);
        
    	if(!empty($this->type)){
			$criteria->compare('`t`.type', $this->type, false);
		}
		
    	if(!empty($this->reward_jobs)){
			$criteria->compare('`t`.reward_jobs', $this->reward_jobs,true);
		}
		
    	if(!empty($this->is_send_code)){
			$criteria->compare('`t`.is_send_code', $this->is_send_code,true);
		}
		
    	if(!empty($this->send_type)){
			$criteria->compare('`t`.send_type', $this->send_type,true);
		}

        if ($this->startTime != '') {
            $criteria->compare("`t`.pay_time", ">= " . strtotime($this->startTime." 00:00:00"));
        }

        if ($this->endTime != '') {
            $criteria->compare("`t`.pay_time", "<= " . strtotime($this->endTime." 23:59:59"));
        }

        return new ActiveDataProvider($this, 
            array(
                'criteria' => $criteria,
                'sort' => array('defaultOrder' => '`t`.create_time desc',)
                )
            );        
    }
    
	public function getSearchSql()
    {
        $sql = "select rela_sn, customer_name, customer_mobile, reward_username, reward_mobile, amount,  
          inviter_mobile,type, revise_type,FROM_UNIXTIME(pay_time),reward,ticheng_send_status,sendCan,region,branch,community from rewards ";
        
        $wSql = " where 1=1 ";
        if (!empty($this->id)){
        	$wSql = $wSql." and id=".$this->id;
        }
        
    	if (!empty($this->sn)){
        	$wSql = $wSql." and sn like %'".$this->sn."%'";
        }
    	if (!empty($this->rela_sn)){
        	$wSql = $wSql." and rela_sn like %'".$this->rela_sn."%'";
        }
    	if (!empty($this->customer_id)){
        	$wSql = $wSql." and customer_id like %'".$this->customer_id."%'";
        }
    	if(!empty($this->user_id)){
			$wSql = $wSql." and user_id like %'".$this->user_id."%'";
		}
    	if(!empty($this->customer_name)){
			$wSql = $wSql." and customer_name like %'".$this->customer_name."%'";
		}
    	if(!empty($this->customer_mobile)){
			$wSql = $wSql." and customer_mobile like %'".$this->customer_mobile."%'";
		}
    	if(!empty($this->model)){
			$wSql = $wSql." and model like %'".$this->model."%'";
		}
    	if(!empty($this->amount)){
			$wSql = $wSql." and amount like %'".$this->amount."%'";
		}
    	if(!empty($this->status)){
			$wSql = $wSql." and status like %'".$this->status."%'";
		}
    	if(!empty($this->sendCan)){
			$wSql = $wSql." and sendCan like %'".$this->sendCan."%'";
		}
    	if(!empty($this->update_time)){
			$wSql = $wSql." and update_time like %'".$this->update_time."%'";
		}
    	if(!empty($this->inviter)){
			$wSql = $wSql." and inviter like %'".$this->inviter."%'";
		}
		
    	if(!empty($this->inviter_name)){
			$wSql = $wSql." and inviter_name like %'".$this->inviter_name."%'";
		}
    	if(!empty($this->inviter_mobile)){
			$wSql = $wSql." and inviter_mobile like %'".$this->inviter_mobile."%'";
		}
		
    	if(!empty($this->sendCan_username)){
			$wSql = $wSql." and sendCan_username like %'".$this->sendCan_username."%'";
		}
    	if(!empty($this->sendCan_userid)){
			$wSql = $wSql." and sendCan_userid like %'".$this->sendCan_userid."%'";
		}
    	if(!empty($this->ticheng_send_status)){
			$wSql = $wSql." and ticheng_send_status like %'".$this->ticheng_send_status."%'";
		}
    	if(!empty($this->update_username)){
			$wSql = $wSql." and update_username like %'".$this->update_username."%'";
		}
    	if(!empty($this->update_userid)){
			$wSql = $wSql." and update_userid like %'".$this->update_userid."%'";
		}
    	if(!empty($this->reward_username)){
			$wSql = $wSql." and reward_username like %'".$this->reward_username."%'";
		}
    	if(!empty($this->reward_mobile)){
			$wSql = $wSql." and reward_mobile like %'".$this->reward_mobile."%'";
		}
    	if(!empty($this->type)){
			$wSql = $wSql." and type ='".$this->type."'";
		}
    	if(!empty($this->reward_jobs)){
    		$wSql = $wSql." and reward_jobs like %'".$this->reward_jobs."%'";
		}
    	if(!empty($this->is_send_code)){
    		$wSql = $wSql." and is_send_code like %'".$this->is_send_code."%'";
		}		
    	if(!empty($this->send_type)){
    		$wSql = $wSql." and send_type like %'".$this->send_type."%'";
		}

        if ($this->startTime != '') {
            $wSql = $wSql." and pay_time >='".strtotime($this->startTime." 00:00:00")."'";
        }

        if ($this->endTime != '') {
            $wSql = $wSql." and pay_time <='".strtotime($this->endTime." 23:59:59")."'";
        }
        
        $wSql = $wSql." order by create_time desc";

        $sql = $sql.$wSql;
        
        return $sql;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PropertyActivity the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

	public function getCustomerName()
    {
        return empty($this->customer) ? $this->customer_id : (empty($this->customer->name) ? $this->customer->username : $this->customer->name);
    }
	
    public function judgeRewardNumOut($sn, $rela_sn, $id, $reward, $oldReward)
    {
    	if (empty($sn) || empty($rela_sn) || empty($id)){
    		return false;
    	}
    	
    	$m_reward = isset($reward)?F::price_formatNew($reward):0.00;
    	$old_reward = isset($oldReward)?F::price_formatNew($oldReward):0.00;
    	if ($m_reward <= $old_reward){
    		return false;
    	}
    	
    	$connection = Yii::app()->db;
        $sql = "select max(allot_all) as allot_all, sum(reward) as all_reward FROM rewards where status not in ("
         	.Item::FEES_TRANSACTION_REFUND.",".Item::FEES_TRANSACTION_LACK.",".Item::FEES_TRANSACTION_FAIL.",".Item::FEES_CANCEL.")" 
         	." and sn= '".$sn."' and rela_sn = '".$rela_sn."'";

         	
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        $allot_all = 0.00;
        $all_reward = 0.00;

        if (count($result) > 0) {
        	foreach ($result as $k1 => $v1) {
        		
                    $allot_all = $v1['allot_all'];
                    $all_reward = $v1['all_reward'];
                }
        }
        
        $allot_1 = isset($allot_all)?F::price_formatNew($allot_all):0.00;
    	$reward_1 = isset($all_reward)?F::price_formatNew($all_reward):0.00;
    	if (($reward_1 -$old_reward + $m_reward) > ($allot_1)){

    		return true;
    	}
    	return false;
    }

    public function getCustomerMobile()
    {
        return !empty($this->customer) ? $this->customer->mobile : "";
    }

    public function getStatusName($html = false)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$fees_status[$this->status];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }

    public static function showStatusName($status)
    {
    	if (null === $status) {
            return '状态为空';
        } else {
            if (isset(self::$fees_status[$status])) {
                return self::$fees_status[$status];
            }
            return '状态未定义';
        }
    }
	
    //获得审核状态
	public static function showExamStatusName($status)
    {
    	if (null === $status) {
            return '状态为空';
        } else {
            if (isset(self::$sendCan_status[$status])) {
                return self::$sendCan_status[$status];
            }
            return '状态未定义';
        }
    }

    // public function getSendCanStatusName($html = false)
    // {
    //     $return = '';
    //     $return .= ($html) ? '<span class="label label-success">' : '';
    //     $return .= self::$sendCan_status[$this->sendCan];
    //     $return .= ($html) ? '</span>' : '';
    //     return $return;
    // }

	public function getStatusNameNums($html = false)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$fees_status_num[$this->status];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }

    
    public static function getStatusNames()
    {
        return CMap::mergeArray(array('' => '全部'), self::$fees_status);
    }



    public static function getSendCanStatusNameSel()
    {
        return CMap::mergeArray(array('' => '全部'), self::$sendCan_status);
    }
    

    public static function getStatusNamess()
    {
        return CMap::mergeArray(array('all' => '全部'), self::$fees_status);
    }


    static public function checkStatus($status)
    {   

        $array = array(
             Item::FEES_AWAITING_PAYMENT,
             Item::FEES_TRANSACTION_SUCCESS,      
             Item::FEES_CANCEL,
             Item::FEES_TRANSACTION_ERROR,
        );


        if (!in_array($status, $array)||$status!=$array[0]) {
            return false;
        } else {
            return true;
        }
    }

    public function getMobileTag()
    {
        $customer = $this->customer;
        $mobile = $customer?$customer->mobile:"";
        $username = $customer?$customer->username:"";
        $customerName = empty($this->AdvanceFees) ? "" : $this->AdvanceFees->customer_name;
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
                'data-original-title' => '姓名:' . $customerName . ', 帐号:' . $username),
            $mobile);
    }


    public function getAmountView()
    {
        $return = '<span>' . $this->amount . '</span>';
        $return .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="/advanceFee/history/' . $this->id . '">查看缴费历史</a>';
        return $return;
    }
    
	public function showProductName()
    {
    	if (empty($this->type)){
    		return '';
    	}
    	
    	Yii::import('common.components.MultiTblComm');
    	$eInfos = MultiTblComm::getInstance()->getEproductInfo($this->type);
    	if (!empty($eInfos)){
    		return $eInfos['product_name'];
    	}
    	return '';
    }
    
	public function createJobkey4Mdf()
    {
    	$imid = 0;
    	
    	$sql="select MIN(id) as id from rewards where ((istate_all & 0x100) = 0x100) and sn='".$this->sn."' and rela_sn='".$this->rela_sn."' and type='".$this->type."' ";

        $command = Yii::app()->db->createCommand($sql);
	    $result = $command->queryAll();
	    
    	if (count($result) > 0) {
	        $id2 = $result[0]['id'];
	        $imid = intval($this->id) - intval($id2);
	    }
        $v_jobkey = 'M'.$imid;

    	return $v_jobkey;
    }
    
    public function createJobkey4New()
    {
    	$sql="select count(*) from rewards where sn='".$this->sn."' and rela_sn='".$this->rela_sn."' and type='".$this->type."' ";
        $count = Yii::app()->db->createCommand($sql)->queryScalar();
        $count = $count + 1;
        $v_jobkey = 'N'.$count;

    	return $v_jobkey;
    }
    
	public function getUqKey4manual($jobkey)
    {
    	$v_userid = $this->reward_userid;
    	if (empty($v_userid)){
    		$v_userid = '0';
    	}
    	$v_jobkey = $jobkey;
    	if (empty($jobkey)){
    		$v_jobkey = $this->createJobkey4Mdf();
    	}
    	
    	$result = $v_userid.'-'.$v_jobkey;
    	$ilen = 31 - strlen($result);
    	if ($ilen > 0){
    		if ($ilen > 15){
    			$ilen = 15;
    		}
    		$v_sn = substr($this->sn, -1* $ilen, $ilen);
    		$result = $result.'-'.$v_sn;
    	}
    	return $result;
    }
    
    public function getJobKey4State()
    {
    	$v_jobkey = ($this->istate_all>>8)&0xfff;
    	return $v_jobkey;
    }
    
    public function getUniqueKey()
    {
    	$v_userid = $this->reward_userid;
    	if (empty($v_userid)){
    		$v_userid = '0';
    	}
    	$v_jobkey = $this->jobkey;
    	if (empty($v_jobkey)){
    		$v_jobkey = ($this->istate_all>>8)&0xfff;
    	}else{
	    	try{
	    		if (is_numeric($v_jobkey)){
		    		if (intval($v_jobkey) == 0){
		    			$v_jobkey = ($this->istate_all>>8)&0xfff;
		    		}
	    		}
	    	}catch(Exception $e){
	    		//jobkey 可能包含字母
	    	}
    	}
    	$result = $v_userid.'-'.$v_jobkey;
    	$ilen = 31 - strlen($result);
    	if ($ilen > 0){
    		if ($ilen > 15){
    			$ilen = 15;
    		}
    		$v_sn = substr($this->sn, -1* $ilen, $ilen);
    		$result = $result.'-'.$v_sn;
    	}
    	return $result;
    }
    
    public function getRemainder()
    {
    	if (empty($this->sn) || empty($this->allot_all)){
    		return 0.0;
    	}
    	
    	if((intval($this->rewardparam) & 0x80)== 0x80){
    		return 0.0;
    	}
    	
    	$connection = Yii::app()->db;
    	$sql = "select sum(reward) as reward_sum FROM rewards where ((rewardparam & 0x80)<> 0x80) and sn='".$this->sn."' ";
    	$command = $connection->createCommand($sql);
        
        $result = $command->queryAll();
        
    	if (count($result) > 0) {
    		$reward_sum = $result[0]['reward_sum']; 
		    
    		return $this-> allot_all - $reward_sum;
        }
    	return 0.0;
    }


    public function getStatusNameView()
    {
        $return = '<span class="label label-success">' . (self::$fees_status[$this->status]) . '</span>';
        if ($this->status == Item::FEES_AWAITING_PAYMENT || $this->status == Item::FEES_TRANSACTION_ERROR)
            $return .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="/advanceFee/update/' . $this->id . '">修改支付状态</a>';
        return $return;
    }
    
	public function backUpOrder($mAttr)
	{
		//判断参数
        if (empty($mAttr)) {
            Rewards::model()->addError('id', "接收订单奖励数据失败！");
            return false;
        }

        if (empty($mAttr["type"])){
            Rewards::model()->addError('id', "缺少理财产品类型数据！");
            return false;
        }
        
		$mAttr["create_ip"] =  Yii::app()->request->userHostAddress;
    
    	if (empty($mAttr["customer_name"])){
            $mAttr["customer_name"] = $mAttr["name"];
        }
    	if (empty($mAttr["customer_mobile"])){
            $mAttr["customer_mobile"] = $mAttr["mobile"];
        }	
        
        $bRtn = false;
        
		$pdt_info =  MultiTblComm::getInstance()->getEproductInfo($mAttr["type"]);
		if (!empty($pdt_info)){
			$product_type = $pdt_info['product_type'];
			$mAttr["order_des"] = "【".$mAttr["customer_name"]."】购买【".$mAttr["amount"].'】元'.$pdt_info['product_name'] ;
		}
		else{
			$product_type = MultiTblComm::getInstance()->getEproductIType($mAttr["type"]);
		}
		$mAttr["model"] = intval($product_type);
		if(!isset($mAttr["inviter_oa"])){
			$mAttr["inviter_oa"] = '';
		}
		if(!isset($mAttr["inviter_id"])){
			if(isset($mAttr["inviter"])) {
				$mAttr["inviter_id"] = $mAttr["inviter"];
			}else{
				$mAttr["inviter_id"] = 0;
			}
		}
		if (empty($mAttr["inviter_oa"])){
        	if (intval($mAttr["inviter_id"]) != 0){ 

				$mEp = Employee::model()->findByPk($mAttr["inviter_id"]);
				if($mEp){
					$mAttr["inviter_oa"]=$mEp->oa_username; 
					$mAttr["inviter_name"]=$mEp->name; 
					$mAttr["inviter_mobile"]=$mEp->mobile; 
				}
			}
        }
        $mAttr['reward'] =  $mAttr["allot_all"];
        $mAttr['rewardparam'] = 0x80; //不能够再操作
		
		$item = new Rewards;
        $item->setScenario('newCreate');
        $item->attributes = $mAttr;
        
		if ($item->isExistSame($mAttr['sn'], $mAttr['type'], $mAttr['rela_sn'])){
        	return false;
        }
		if($item->save()){
        	$bRtn = true;
        }
        
        return $bRtn;
	}

	public function createOrder($mAttr)
    {
        //判断参数
        if (empty($mAttr)) {
            Rewards::model()->addError('id', "接收订单奖励数据失败！");
            return false;
        }

        if (empty($mAttr["type"])){
            Rewards::model()->addError('id', "缺少理财产品类型数据！");
            return false;
        }
        
		$mAttr["create_ip"] =  Yii::app()->request->userHostAddress;
    
    	if (empty($mAttr["customer_name"])){
            $mAttr["customer_name"] = $mAttr["name"];
        }
    	if (empty($mAttr["customer_mobile"])){
            $mAttr["customer_mobile"] = $mAttr["mobile"];
        }
    
        $bRtn = false;
        Yii::import('common.api.SsoSystemApi');
		$inRecord = array();
        	
        ///$product_type = $this->getProductType($mAttr["type"]);
        Yii::import('common.components.MultiTblComm');

		$pdt_info =  MultiTblComm::getInstance()->getEproductInfo($mAttr["type"]);
		if (!empty($pdt_info)){
			$product_type = $pdt_info['product_type'];
			$mAttr["order_des"] = "【".$mAttr["customer_name"]."】购买【".$mAttr["amount"].'】元'.$pdt_info['product_name'] ;
		}
		else{
			$product_type = MultiTblComm::getInstance()->getEproductIType($mAttr["type"]);
		}
		
        $mAttr["model"] = intval($product_type);
        
        $rewardZbId = 'caifrsb';
        $rewardZbNme = '彩富人生部';
        try {
	        $zbSpitTm = $mAttr["pay_time"];
	        if (empty($zbSpitTm) || intval($zbSpitTm) < 1475251201) {
	        	$zbSpitTm = $mAttr["create_time"];
	        }
			if (intval($zbSpitTm) < 1488211201){
				$rewardZbId = 'caizhiyunkehu';
				$rewardZbNme = '总部客户部';
			}
        }catch(Exception $e1) {
        	$rewardZbId = 'caifrsb';
        	$rewardZbNme = '彩富人生部';
        }
        

		$isTransaction = Yii::app()->db->getCurrentTransaction();//判断是否有用过事务
        $transaction = (!$isTransaction)?Yii::app()->db->beginTransaction():'';
		try
		{
			if (intval($product_type)==1){ //金融类产品
				if (empty($mAttr["order_des"])){
					$mAttr["order_des"] = "【".$mAttr["customer_name"]."】购买【".$mAttr["amount"].'】元金融类产品';
				}
	
	       		///总部客户部///
			    $rJobs = RewardJobs::model()->getRewardJobs($mAttr["type"], '总部客户部');
	
			    $inRecord['reward_username'] = $rewardZbNme;
	            $inRecord['reward_jobs'] = '总部客户部';
	            $inRecord['region'] = $rewardZbNme;
	            
	            $inRecord['istate_all'] = 256; //表明是总部
	            $inRecord['jobkey'] = 1;
	            $inRecord['reward_userid'] = $this->getEmployeeByOa('oa_username', $rewardZbId, 'id');
	            $inRecord['reward_mobile'] = $this->getEmployeeByOa('oa_username', $rewardZbId, 'mobile');
	            $inRecord['user_id'] = $rewardZbId;


	            if (isset($mAttr['region_4bm'])){
	            	$inRecord['region'] = $mAttr['region_4bm'];
	            	$inRecord['region_id'] = $mAttr['region_id_4bm'];
	            	$inRecord['branch'] = $mAttr['branch_4bm'];
	            	$inRecord['branch_id'] = $mAttr['branch_id_4bm'];
	            }
	
			    if(!empty($rJobs)){
	
	               	$param = isset($rJobs[0]['reward_param'])?F::price_formatNew($rJobs[0]['reward_param']):0.00;
	            	$al_param = isset($rJobs[0]['allot_param'])?F::price_formatNew($rJobs[0]['allot_param']):0.00;
	
	            	$inRecord['reward'] =  $al_param / 100 * $mAttr["allot_all"];
			    }
			    if ($inRecord['reward'] <0.01){
			    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
			    	$inRecord['ticheng_send_status']=1;
			    }
			    if (($mAttr['rewardparam']&128)> 0){
			    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
			    	if (!empty($inRecord['reward_userid']) && intval($inRecord['reward_userid'])>0){
			    		$inRecord['ticheng_send_status']=1;
			    	}
			    }

	  
			    $inRecord = array_merge($inRecord, $mAttr);
			    
		        //创建我们的订单记录及记录
		        $item = new Rewards;
		        $item->setScenario('newCreate');
		        $item->attributes = $inRecord;
		        
		        if ($item->judgeDoubleRecord($inRecord)<= 1){
			        if($item->save()){
			        	$bRtn = true;
			        }else{
			        	throw new CException('信息保存失败:总部,通知事务回滚');
			        }
		        }else{
		        	throw new CException('信息保存失败:总部有重复,通知事务回滚');
		        }
		        
		        ///总部客户部  end///
		       
		        ///事业部客户部经理///
		        $bfind = false;
		        $inRecord = array(); 
		        
		        if (!empty($mAttr["community_id"])){
		        	$data = SsoSystemApi::getInstance()->getBmManager(trim($mAttr["community_id"])); //获得事业部客户部经理信息
		        	$iNum = count($data);
		        	$manNum = $iNum;
			        if(!empty($data)){
		        		$rJobs = RewardJobs::model()->getRewardJobs($mAttr["type"], '事业部客户部经理');

						
		        		for($i = 0; $i < $iNum; $i++)
		        		{
		        			$bfind = true;
		        			$inRecord = array(); 
		        			
		        			$inRecord['reward_username'] = $data[$i]['realname'];
				            $inRecord['reward_jobs'] = '事业部客户部经理';
				            $inRecord['reward_userid'] = $this->getEmployeeByOa('oa_username', $data[$i]['username'], 'id');
				            $inRecord['reward_mobile'] = $data[$i]['mobile'];
				            $inRecord['istate_all'] = 512; //表明是事业部客户部
				            $inRecord['jobkey'] = 2;
				            
				            $inRecord['user_id'] = $data[$i]['username'];
				            $inRecord['region'] = isset($data[$i]['region'])? $data[$i]['region']: '';
				            $inRecord['region_id'] = isset($data[$i]['region_id'])? $data[$i]['region_id']: '';
				            $inRecord['community'] = isset($data[$i]['community'])? $data[$i]['community']: '';
				            $inRecord['community_id'] = isset($data[$i]['community_id'])? $data[$i]['community_id']: '';
				            $inRecord['branch'] = isset($data[$i]['branch'])? $data[$i]['branch']: '';
				            $inRecord['branch_id'] = isset($data[$i]['branch_id'])? $data[$i]['branch_id']: '';

				            $al_param = 0.00;
				            
			        		if(!empty($rJobs)){
				
				               	$param = isset($rJobs[0]['reward_param'])?F::price_formatNew($rJobs[0]['reward_param']):0.00;
				            	$al_param = isset($rJobs[0]['allot_param'])?F::price_formatNew($rJobs[0]['allot_param']):0.00;
				
				            	$inRecord['reward'] = $al_param / 100 * $mAttr["allot_all"] / $manNum;
						    }
			        		if (($mAttr['rewardparam']&128)> 0){
						    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
						    	if (!empty($inRecord['reward_userid']) && intval($inRecord['reward_userid'])>0){
						    		$inRecord['ticheng_send_status']=1;
						    	}
						    }
		  
						    $inRecord = array_merge($inRecord, $mAttr);
						    
					        //创建我们的订单记录及记录
					        $item = new Rewards;
					        $item->setScenario('newCreate');
					        $item->attributes = $inRecord;
							
					        if ($item->judgeDoubleRecord($inRecord)<= 1){
						        if($item->save()){
						        	$bRtn = true;
								}else{
							         throw new CException('信息保存失败:事业部客户部经理,通知事务回滚');
						        }
					        }else{
								$manNum = $manNum - 1;
					        	$model_list = $this->gainDoubleRecord($inRecord);
								if ($model_list){
									
									foreach ($model_list as $model_db) {
										if ($manNum > 0)
					 					$model_db->reward = $al_param / 100 * $mAttr["allot_all"] / $manNum;
	
										if ($model_db->save()){
											;
										}
									}
								}
					        }
		        		}
		        	}
		        }
		        
		        if(!$bfind){  //没有找到事业部客户部经理的人员信息

		        	$inRecord['reward_username'] = '事业部客户部经理';
		            $inRecord['reward_jobs'] = '事业部客户部经理';
		            $inRecord['reward_userid'] = '0';
		            $inRecord['reward_mobile'] = '';
		            $inRecord['istate_all'] = 512; //表明是事业部客户部
		            $inRecord['jobkey'] = 2;

		    		$rJobs = RewardJobs::model()->getRewardJobs($mAttr["type"], '事业部客户部经理');
		            
				    if(!empty($rJobs)){
		
		               	$param = isset($rJobs[0]['reward_param'])?F::price_formatNew($rJobs[0]['reward_param']):0.00;
		            	$al_param = isset($rJobs[0]['allot_param'])?F::price_formatNew($rJobs[0]['allot_param']):0.00;
		
		            	$inRecord['reward'] =  $al_param * $mAttr["allot_all"] / 100 ;
				    }
	  
				    $inRecord = array_merge($inRecord, $mAttr);

			        //创建我们的订单记录及记录
			        $item = new Rewards;
			        $item->setScenario('newCreate');
			        $item->attributes = $inRecord;
			        if (($mAttr['rewardparam']&128)> 0){
				    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
				    }
			        
				    if ($item->judgeDoubleRecord($inRecord)<= 1){
					    if($item->save()){
				        	$bRtn = true;
						}else{
				        	throw new CException('信息保存失败:事业部客户部经理,通知事务回滚');
				        }
				    }else{
				    	throw new CException('信息保存失败:事业部客户部经理有重复,通知事务回滚');
				    }
		        }
		        ///事业部客户部经理  end///
				
		        $bfind = false;
		        $inRecord = array();

		        if(empty($mAttr["inviter_oa"])){ 
		        	if($bRtn){
	        			(!$isTransaction)?$transaction->commit():'';
	        		}else{
	        			(!$isTransaction)?$transaction->rollback():'';
	        		}
		        	return $bRtn;
		        }

		        $data = SsoSystemApi::getInstance()->getEmployee(trim($mAttr["inviter_oa"])); //获得推荐人信息
				$f_job= "";
			
	        	if(!empty($data)){
	        		$f_job = $data[0]['jobname'];
	        		$bfind = true;
	        	}
	        	
	        	if ((!empty($f_job)) && (stripos($f_job, '客户经理') !== false)){
	        		//应对是专属客户经理推荐

	        		$inRecord['istate_all'] = 1024; //表明是客户经理
	        		$inRecord['jobkey'] = 4;
	
	        		if((!empty($data)) && (!empty($data[0]['username']))){
	
		               	$inRecord['reward_username'] = $data[0]['realname'];
			            $inRecord['reward_jobs'] = $data[0]['jobname'];
			            $inRecord['reward_userid'] = $this->getEmployeeByOa('oa_username', $data[0]['username'], 'id');
			            $inRecord['reward_mobile'] = $data[0]['mobile'];
			            
			            $inRecord['user_id'] = $data[0]['username'];
			            $inRecord['region'] = isset($data[0]['region'])? $data[0]['region']: '';
			            $inRecord['region_id'] = isset($data[0]['region_id'])? $data[0]['region_id']: '';
			            $inRecord['community'] = isset($data[0]['community'])? $data[0]['community']: '';
			            $inRecord['community_id'] = isset($data[0]['community_id'])? $data[0]['community_id']: '';
			            $inRecord['branch'] = isset($data[0]['branch'])? $data[0]['branch']: '';
			            $inRecord['branch_id'] = isset($data[0]['branch_id'])? $data[0]['branch_id']: '';
		
		             }else {
		             	$inRecord['reward_jobs'] = '客户经理';
	
		             	$inRecord['reward_username'] = '客户经理';
			            $inRecord['reward_userid'] = '0';
			            $inRecord['reward_mobile'] = '';
		             }

		        	 $rJobs = RewardJobs::model()->getRewardJobs($mAttr["type"], '客户经理');
		             if(!empty($rJobs)){
		            	$param = isset($rJobs[0]['reward_param'])?F::price_formatNew($rJobs[0]['reward_param']):0.00;
		            	$al_param = isset($rJobs[0]['allot_param'])?F::price_formatNew($rJobs[0]['allot_param']):0.00;
		
		            	$inRecord['reward'] =  $al_param * $mAttr["allot_all"] / 100 ;
		             }
		        	if (($mAttr['rewardparam']&128)> 0){
				    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
				    	if (!empty($inRecord['reward_userid']) && intval($inRecord['reward_userid'])>0){
				    		$inRecord['ticheng_send_status']=1;
				    	}
				    }

		        	 $inRecord = array_merge($inRecord, $mAttr);
		        	 //创建我们的订单记录及记录
			         $item = new Rewards;
			         $item->setScenario('newCreate');
			         $item->attributes = $inRecord;
			         
			         if ($item->judgeDoubleRecord($inRecord)<= 1){
				         if($item->save()){
	
				         	$bRtn = true;
				         }else{
				        	throw new CException('信息保存失败:客户经理,通知事务回滚');
				         }
			         }
			         
			         $rJobs = RewardJobs::model()->getRewardJobs($mAttr["type"], ''); //其它所有相关的职位

		             if(!empty($rJobs) && !empty($mAttr["inviter_oa"])){
			             foreach ($rJobs as $k => $val) {
			             	
			             	$bfind = false; //准备其它相关职位
	
			                $jobName = $rJobs[$k]['job_name'];
			                
			                if(empty($jobName)){
			                	continue;
			                }
			                if ((strnatcmp(strtoupper($jobName),strtoupper('总部客户部'))==0) 
			                	||(strnatcmp(strtoupper($jobName),strtoupper('事业部客户部经理'))==0)
			                	||(strnatcmp(strtoupper($jobName),strtoupper('客户经理'))==0)
			                	||(strnatcmp(strtoupper($jobName),strtoupper('员工(非客户经理)'))==0)){
			                		continue;
			                }
			                 
							$param = isset($rJobs[$k]['reward_param'])?F::price_formatNew($rJobs[$k]['reward_param']):0.00;
		            		$al_param = isset($rJobs[$k]['allot_param'])?F::price_formatNew($rJobs[$k]['allot_param']):0.00;

			                 $oaJobs = RewardJobs::model()->getOAJobs($jobName);
			                 $iNum = count($oaJobs);

			                 if($iNum == 0){

			                 	$data4User = SsoSystemApi::getInstance()->getUser4Job(trim($mAttr["inviter_oa"]), trim($jobName), $mAttr["community_id"]); 
			                 	$iNum = count($data4User);
			                 	$manNum = $iNum;
			                 	
			                 	foreach ($data4User as $k1 => $val1) {
			                 		$inRecord = array();
			                 		
			                 		$inRecord['jobkey'] = $rJobs[$k]['jobkey'];
			                 		
			                 		$inRecord['reward_userid'] = $this->getEmployeeByOa('oa_username', $data4User[$k1]['username'], 'id');
					               	$inRecord['reward_username'] = $data4User[$k1]['realname'];
					               	$inRecord['reward_mobile'] = $data4User[$k1]['mobile'];
					               	$inRecord['reward_jobs'] = $data4User[$k1]['jobname'];
					               	
						            $inRecord['user_id'] = $data4User[$k1]['username'];
						            $inRecord['region'] = isset($data4User[$k1]['region'])? $data4User[$k1]['region']: '';
						            $inRecord['region_id'] = isset($data4User[$k1]['region_id'])? $data4User[$k1]['region_id']: '';
						            $inRecord['community'] =  isset($data4User[$k1]['community'])? $data4User[$k1]['community']: '';
						            $inRecord['community_id'] = isset($data4User[$k1]['community_id'])? $data4User[$k1]['community_id']: '';
						            $inRecord['branch'] = isset($data4User[$k1]['branch'])? $data4User[$k1]['branch']: '';
						            $inRecord['branch_id'] = isset($data4User[$k1]['branch_id'])? $data4User[$k1]['branch_id']: '';
					               	
		            				$inRecord['reward'] =  $al_param * $mAttr["allot_all"] /100 / $manNum;
				                 	if (($mAttr['rewardparam']&128)> 0){
								    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
								    	if (!empty($inRecord['reward_userid']) && intval($inRecord['reward_userid'])>0){
								    		$inRecord['ticheng_send_status']=1;
								    	}
								    }
		            				
		            				$inRecord = array_merge($inRecord, $mAttr);
		            				
			                 		//创建我们的订单记录及记录
							        $item = new Rewards;
							        $item->setScenario('newCreate');
							        $item->attributes = $inRecord;
							        
							        $bfind = true; //找到了此职位相关人
									
							        if ($item->judgeDoubleRecord($inRecord)<= 1){
								        if($item->save()){
								        	
								        	$bRtn = true;
								        }else{
								        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].',通知事务回滚');
								        }
							        }else{
									    $manNum = $manNum - 1;
							        	$model_list = $this->gainDoubleRecord($inRecord);
										if ($model_list){
											
											foreach ($model_list as $model_db) {
												if ($manNum > 0)
							 					$model_db->reward = $al_param / 100 * $mAttr["allot_all"] / $manNum;
			
												if ($model_db->save()){
													;
												}
											}
										}
							        }
			                 	}
			                 }else{ //有对应的OA Job
			                 	$iNum = 0;
			                 	$manNum = 0;
			                 	$inUsers = array();
								
			                 	$jobSql = "";
			                 	
			                 	foreach ($oaJobs as $k2 => $val2) {
	
				                 	$data4User = SsoSystemApi::getInstance()->getUser4Job(trim($mAttr["inviter_oa"]), trim($oaJobs[$k2]['oa_job']), $mAttr["community_id"]); 
				                 	$iNum = $iNum + count($data4User);
			                 		$manNum = $manNum + count($data4User);
			                 		
									$jobSql = $jobSql."'".trim($oaJobs[$k2]['oa_job'])."' ,";

				                 	foreach ($data4User as $k1 => $val1) {
				                 		$inRecord = array();
				                 		
				                 		$inRecord['jobkey'] = $rJobs[$k]['jobkey'];
				                 		
				                 		$inRecord['reward_userid'] = $this->getEmployeeByOa('oa_username', $data4User[$k1]['username'], 'id');
				                 		$inUsers[] =  $inRecord['reward_userid'];

						               	$inRecord['reward_username'] = $data4User[$k1]['realname'];
						               	$inRecord['reward_mobile'] = $data4User[$k1]['mobile'];
						               	//$inRecord['reward_jobs'] = $data4User[$k1]['jobname'];
						               	$inRecord['reward_jobs'] = trim($oaJobs[$k2]['oa_job']);

							            $inRecord['user_id'] = $data4User[$k1]['username'];
							            $inRecord['region'] = isset($data4User[$k1]['region'])? $data4User[$k1]['region']: '';
							            $inRecord['region_id'] = isset($data4User[$k1]['region_id'])? $data4User[$k1]['region_id']: '';
							            $inRecord['community'] = isset($data4User[$k1]['community'])? $data4User[$k1]['community']: '';
							            $inRecord['community_id'] = isset($data4User[$k1]['community_id'])? $data4User[$k1]['community_id']: '';
							            $inRecord['branch'] = isset($data4User[$k1]['branch'])? $data4User[$k1]['branch']: '';
							            $inRecord['branch_id'] =  isset($data4User[$k1]['branch_id'])? $data4User[$k1]['branch_id']: '';
						               	
			            				$inRecord['reward'] =  $al_param * $mAttr["allot_all"] / 100; ///应当/iNum

			            				$inRecord = array_merge($inRecord, $mAttr);
			            				
				                 		//创建我们的订单记录及记录
								        $item = new Rewards;
								        $item->setScenario('newCreate');
								        $item->attributes = $inRecord;
								        
								        $bfind = true; //找到了此OA职位相关人
										
								        if ($item->judgeDoubleRecord($inRecord)<= 1){
									        if($item->save()){
									        	$bRtn = true;
									        }else{
									        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].',通知事务回滚');
									        }
								        }else{
								        	$manNum = $manNum - 1;
								        }
								        
				                 	}
			                 	}
			                 	
			                 	if($iNum > 0 && count($inUsers) > 0){

									$sql="SELECT * FROM rewards WHERE sn ='".$mAttr['sn']."' and rela_sn ='".$mAttr['rela_sn']."' and reward_userid in (";
									$sSql = "";
									foreach ($inUsers as $u1) {
										$sSql = $sSql."'".$u1."' ,";
									}
									$sql = $sql.$sSql;
									$sql = substr($sql,0,strlen($sql)-1);
									$sql = $sql.")";
									
			                 		if (strlen($jobSql) > 1){
										$sql =$sql." and reward_jobs in (";
										$sql = $sql.$jobSql;
										$sql = substr($sql,0,strlen($sql)-1);
										$sql = $sql.")";
									}

									$mdfModels = Rewards::model()->findAllBySql($sql);
									if(!empty($mdfModels)){
										foreach($mdfModels as $mdfModel){
											if ($manNum > 0){
												$mdfModel->reward = $mdfModel->reward / $manNum;
												$mdfModel->save();
											}
										}
									}

//			                 		$connection = Yii::app()->db;
//							        $sql = "update rewards set reward=reward/".$iNum." where sn ='".$mAttr['sn']."' and rela_sn ='".$mAttr['rela_sn']."' and reward_userid in (";
//									$sSql = "";
//									foreach ($inUsers as $u1) {
//										$sSql = $sSql."'".$u1."' ,";
//									}
//									$sql = $sql.$sSql;
//									$sql = substr($sql,0,strlen($sql)-1);
//									$sql = $sql.")";
//
//							        $command = $connection->createCommand($sql);
//							        //$command->execute();
//
//							        if ($command->execute()<=0){
//							        	throw new CException('信息保存失败:更改相关奖励份,通知事务回滚');
//							         }

								}
							}

			             	if(!$bfind){  //没有找到此职位的人员信息
			             		$inRecord = array();
			             		
					        	$inRecord['reward_username'] = $jobName;
					            $inRecord['reward_jobs'] = $jobName;
					            $inRecord['reward_userid'] = '0';
					            $inRecord['reward_mobile'] = '';
					            
					            //$inRecord['istate_all'] = 0; //表明是是其他职位: 0x0XX
					            if (strnatcmp(strtoupper($jobName),strtoupper('小组团队长'))==0){
					            	$inRecord['istate_all'] = 4096;
					            }
					            $inRecord['jobkey'] = $rJobs[$k]['jobkey'];
					
				            	$inRecord['reward'] =  $al_param * $mAttr["allot_all"] / 100 ;

			             		if (isset($mAttr['region_4bm'])){
					            	$inRecord['region'] = $mAttr['region_4bm'];
					            	$inRecord['region_id'] = $mAttr['region_id_4bm'];
					            	$inRecord['branch'] = $mAttr['branch_4bm'];
					            	$inRecord['branch_id'] = $mAttr['branch_id_4bm'];
					            }
				             	if (($mAttr['rewardparam']&128)> 0){
							    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
							    }
	
							    $inRecord = array_merge($inRecord, $mAttr);
							    
						        //创建我们的订单记录及记录
						        $item = new Rewards;
						        $item->setScenario('newCreate');
						        $item->attributes = $inRecord;
								
						        if ($item->judgeDoubleRecord($inRecord)<= 1){
							        if($item->save()){
							        	$bRtn = true;
									}else{
							        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].',通知事务回滚2');
							        }
						        }
					        }
						}
					}
	
	        	}else {
	        		//员工推荐//
	        		$inRecord = array();
	        		$inRecord['istate_all'] = 2048; //表明是员工推荐
	        		$inRecord['jobkey'] = 8;
	        		
	        		if (empty($mAttr["inviter_oa"])){
		        		if (intval($mAttr["inviter_id"]) != 0){ 
		
							$mEp = Employee::model()->findByPk($mAttr["inviter_id"]);
							if($mEp){
								$mAttr["inviter_oa"]=$mEp->oa_username; 
								$mAttr["inviter_name"]=$mEp->name; 
								$mAttr["inviter_mobile"]=$mEp->mobile; 
							}
						}
	        		}
	        				
	        		if (!empty($mAttr["inviter_oa"])){

						$data = SsoSystemApi::getInstance()->getEmployee(trim($mAttr["inviter_oa"])); //获得员工信息
	
			        	if(!empty($data)){
			               	
			               	$inRecord['reward_username'] = $data[0]['realname'];
				            $inRecord['reward_jobs'] = '员工(非客户经理)';
				            $inRecord['reward_userid'] = $this->getEmployeeByOa('oa_username', $data[0]['username'], 'id');
				            $inRecord['reward_mobile'] = $data[0]['mobile'];
				            
				            $inRecord['user_id'] = $data[0]['username'];
				            $inRecord['region'] = isset($data[0]['region'])? $data[0]['region']: '';
				            $inRecord['region_id'] = isset($data[0]['region_id'])? $data[0]['region_id']: '';
				            $inRecord['community'] = isset($data[0]['community'])? $data[0]['community']: '';
				            $inRecord['community_id'] = isset($data[0]['community_id'])? $data[0]['community_id']: '';
				            $inRecord['branch'] = isset($data[0]['branch'])? $data[0]['branch']: '';
				            $inRecord['branch_id'] = isset($data[0]['branch_id'])? $data[0]['branch_id']: '';
	
			             }else {
			             	$inRecord['reward_jobs'] = '员工(非客户经理)';
			             	
			             	if (isset($mAttr['region_4bm'])){
				            	$inRecord['region'] = $mAttr['region_4bm'];
				            	$inRecord['region_id'] = $mAttr['region_id_4bm'];
				            	$inRecord['branch'] = $mAttr['branch_4bm'];
				            	$inRecord['branch_id'] = $mAttr['branch_id_4bm'];
				            }
						}

			        	$rJobs = RewardJobs::model()->getRewardJobs($mAttr["type"], '员工(非客户经理)');
			            if(!empty($rJobs)){
			            	$param = isset($rJobs[0]['reward_param'])?F::price_formatNew($rJobs[0]['reward_param']):0.00;
			            	$al_param = isset($rJobs[0]['allot_param'])?F::price_formatNew($rJobs[0]['allot_param']):0.00;
			
	
			            	$inRecord['reward'] = $al_param * $mAttr["allot_all"] / 100;
			            }
			            
						if (($mAttr['rewardparam']&128)> 0){
					    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
					    	if (!empty($inRecord['reward_userid']) && intval($inRecord['reward_userid'])>0){
					    		$inRecord['ticheng_send_status']=1;
					    	}
					    }
	            
			        	 $inRecord = array_merge($inRecord, $mAttr);
			        	 //创建我们的订单记录及记录
				         $item = new Rewards;
				         $item->setScenario('newCreate');
				         $item->attributes = $inRecord;
					     
				         if ($item->judgeDoubleRecord($inRecord)<= 1){
					         if($item->save()){
					         	$bRtn = true;
					         }else{
					        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].',通知事务回滚');
					         }
				         }
					}
	        	}
	
	        }else if (intval($product_type)==2){ //非金融类产品
	        	if (empty($mAttr["order_des"])){
					$mAttr["order_des"] = "【".$mAttr["customer_name"]."】购买【".$mAttr["amount"].'】元非金融类产品';
				}

			    ///总部客户部///
			    $rJobs = RewardJobs::model()->getRewardJobs($mAttr["type"], '总部客户部');
			    $inRecord['reward_username'] = rewardZbNme;
	            $inRecord['reward_jobs'] = '总部客户部';
	            $inRecord['region'] = rewardZbNme;
	            
	        	$inRecord['istate_all'] = 256; //表明是总部
	        	$inRecord['jobkey'] = 1;
	        	$inRecord['reward_userid'] = $this->getEmployeeByOa('oa_username', $rewardZbId, 'id');
	            $inRecord['reward_mobile'] = $this->getEmployeeByOa('oa_username', $rewardZbId, 'mobile');
	            $inRecord['user_id'] = $rewardZbId;
	            
	            if (isset($mAttr['region_4bm'])){
	            	$inRecord['region'] = $mAttr['region_4bm'];
	            	$inRecord['region_id'] = $mAttr['region_id_4bm'];
	            	$inRecord['branch'] = $mAttr['branch_4bm'];
	            	$inRecord['branch_id'] = $mAttr['branch_id_4bm'];
	            }

			    if(!empty($rJobs)){
	
	               	$param = isset($rJobs[0]['reward_param'])?F::price_formatNew($rJobs[0]['reward_param']):0.00;
	            	$al_param = isset($rJobs[0]['allot_param'])?F::price_formatNew($rJobs[0]['allot_param']):0.00;
	
	            	$inRecord['reward'] =$al_param  / 100 * $mAttr["allot_all"];
			    }
	        	if ($inRecord['reward'] <0.01){
			    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
			    	$inRecord['ticheng_send_status']=1;
			    }
	        	if (($mAttr['rewardparam']&128)> 0){
			    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
			    	if (!empty($inRecord['reward_userid']) && intval($inRecord['reward_userid'])>0){
			    		$inRecord['ticheng_send_status']=1;
			    	}
			    }
			    
			    $inRecord = array_merge($inRecord, $mAttr);
			    
		        //创建我们的订单记录及记录
		        $item = new Rewards;
		        $item->setScenario('newCreate');
		        $item->attributes = $inRecord;
				
		        if ($item->judgeDoubleRecord($inRecord)<= 1){
			        if($item->save()){
			        	$bRtn = true;
			        }else{
			        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].',通知事务回滚');
			        }
		        }else{
		        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].'有重复,通知事务回滚');
		        }
		        
		        ///总部客户部  end///
		        
		        ///事业部客户部经理///
		        $bfind = false;
		        $inRecord = array();

		        if (!empty($mAttr["community_id"])){
		        	$data = SsoSystemApi::getInstance()->getBmManager($mAttr["community_id"]); //获得事业部客户部经理信息
		        	$iNum = count($data);
					$manNum = $iNum;
					
			        if(!empty($data)){
			        	$rJobs = RewardJobs::model()->getRewardJobs($mAttr["type"], '事业部客户部经理');

			        	for($i = 0; $i < $iNum; $i++)
			        	{
			        		$bfind = true;
			        		$inRecord = array(); 
			        		
			        		$inRecord['reward_username'] = $data[$i]['realname'];
				            $inRecord['reward_jobs'] = '事业部客户部经理';
				            $inRecord['reward_userid'] = $this->getEmployeeByOa('oa_username', $data[$i]['username'], 'id');
				            $inRecord['reward_mobile'] = $data[$i]['mobile'];
				            $inRecord['istate_all'] = 512; //表明是事业部客户部
				            $inRecord['jobkey'] = 2;
				            
				            $inRecord['user_id'] = $data[$i]['username'];
				            $inRecord['region'] = isset($data[$i]['region'])? $data[$i]['region']: '';
				            $inRecord['region_id'] = isset($data[$i]['region_id'])? $data[$i]['region_id']: '';
				            $inRecord['community'] = isset($data[$i]['community'])? $data[$i]['community']: '';
				            $inRecord['community_id'] = isset($data[$i]['community_id'])? $data[$i]['community_id']: '';
				            $inRecord['branch'] = isset($data[$i]['branch'])? $data[$i]['branch']: '';
				            $inRecord['branch_id'] = isset($data[$i]['branch_id'])? $data[$i]['branch_id']: '';
				            
				            $al_param = 0.00;
				        	if(!empty($rJobs)){
				
				               	$param = isset($rJobs[0]['reward_param'])?F::price_formatNew($rJobs[0]['reward_param']):0.00;
				            	$al_param = isset($rJobs[0]['allot_param'])?F::price_formatNew($rJobs[0]['allot_param']):0.00;
				
				            	$inRecord['reward'] = $al_param * $mAttr["allot_all"]/ 100 / $manNum;
						    }
			        		if (($mAttr['rewardparam']&128)> 0){
						    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
						    	if (!empty($inRecord['reward_userid']) && intval($inRecord['reward_userid'])>0){
						    		$inRecord['ticheng_send_status']=1;
						    	}
						    }
						    $inRecord = array_merge($inRecord, $mAttr);
						    
					        //创建我们的订单记录及记录
					        $item = new Rewards;
					        $item->setScenario('newCreate');
					        $item->attributes = $inRecord;
							
					        if ($item->judgeDoubleRecord($inRecord)<= 1){
						        if($item->save()){
						        	$bRtn = true;
								}else{
						        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].',通知事务回滚');
						        }
					        }else{
					        	$manNum = $manNum - 1;
					        	$model_list = $this->gainDoubleRecord($inRecord);
								if ($model_list){
									
									foreach ($model_list as $model_db) {
										if ($manNum > 0)
					 					$model_db->reward = $al_param * $mAttr["allot_all"]/ 100 / $manNum;
	
										if ($model_db->save()){
											;
										}
									}
								}	
					        }
			        	}
		        	}
		        }
		        
		        if(!$bfind){  //没有找到事业部客户部经理的人员信息

		        	$inRecord['reward_username'] = '事业部客户部经理';
		            $inRecord['reward_jobs'] = '事业部客户部经理';
		            $inRecord['reward_userid'] = '0';
		            $inRecord['reward_mobile'] = '';
		            $inRecord['istate_all'] = 512; //表明是事业部客户部
		            $inRecord['jobkey'] = 2;
		
		    		$rJobs = RewardJobs::model()->getRewardJobs($mAttr["type"], '事业部客户部经理');
		            
				    if(!empty($rJobs)){
		
		               	$param = isset($rJobs[0]['reward_param'])?F::price_formatNew($rJobs[0]['reward_param']):0.00;
		            	$al_param = isset($rJobs[0]['allot_param'])?F::price_formatNew($rJobs[0]['allot_param']):0.00;
		
		            	$inRecord['reward'] =  $al_param / 100 * $mAttr["allot_all"];
				    }
			        if (($mAttr['rewardparam']&128)> 0){
				    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
				    	
				    }
	  
				    $inRecord = array_merge($inRecord, $mAttr);
				    
			        //创建我们的订单记录及记录
			        $item = new Rewards;
			        $item->setScenario('newCreate');
			        $item->attributes = $inRecord;
					
			        if ($item->judgeDoubleRecord($inRecord)<= 1){
				        if($item->save()){
				        	$bRtn = true;
						}else{
				        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].',通知事务回滚2');
				        }
			        }else{
			        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].'有重复,通知事务回滚2');
			        }
		        }
		        ///事业部客户部经理  end///
	
	            $bfind = false;
		        $inRecord = array(); 
		        
	        	if(empty($mAttr["inviter_oa"])){ 
	        		if($bRtn){
	        			(!$isTransaction)?$transaction->commit():'';
	        		}else{
	        			(!$isTransaction)?$transaction->rollback():'';
	        		}
		        	return $bRtn;
		        }

		        $data = SsoSystemApi::getInstance()->getEmployee(trim($mAttr["inviter_oa"])); //获得推荐人信息
				$f_job= "";
				
	        	if(!empty($data)){
	        		$f_job = $data[0]['jobname'];
	        		$bfind = true;
	        	}
	        	
	        	if ((!empty($f_job)) && (stripos($f_job, '客户经理') !== false)){
	        		//应对是专属客户经理推荐
					$inRecord['istate_all'] = 1024; //表明是客户经理
					$inRecord['jobkey'] = 4;
						
	        		if((!empty($data)) && (!empty($data[0]['username']))){
	
		               	$inRecord['reward_username'] = $data[0]['realname'];
			            $inRecord['reward_jobs'] = $data[0]['jobname'];
			            $inRecord['reward_userid'] = $this->getEmployeeByOa('oa_username', $data[0]['username'], 'id');
			            $inRecord['reward_mobile'] = $data[0]['mobile'];
			            
			            $inRecord['user_id'] = $data[0]['username'];
			            $inRecord['region'] = isset($data[0]['region'])? $data[0]['region']: '';
			            $inRecord['region_id'] = isset($data[0]['region_id'])? $data[0]['region_id']: '';
			            $inRecord['community'] = isset($data[0]['community'])? $data[0]['community']: '';
			            $inRecord['community_id'] = isset($data[0]['community_id'])? $data[0]['community_id']: '';
			            $inRecord['branch'] = isset($data[0]['branch'])? $data[0]['branch']: '';
			            $inRecord['branch_id'] = isset($data[0]['branch_id'])? $data[0]['branch_id']: '';
		
		             }else {
		             	$inRecord['reward_jobs'] = '客户经理';
	
		             	$inRecord['reward_username'] = '客户经理';
			            $inRecord['reward_userid'] = '0';
			            $inRecord['reward_mobile'] = '';
					}

		        	$rJobs = RewardJobs::model()->getRewardJobs($mAttr["type"], '客户经理');
		            if(!empty($rJobs)){
		            	$param = isset($rJobs[0]['reward_param'])?F::price_formatNew($rJobs[0]['reward_param']):0.00;
		            	$al_param = isset($rJobs[0]['allot_param'])?F::price_formatNew($rJobs[0]['allot_param']):0.00;
	
		            	$inRecord['reward'] = $al_param * $mAttr["allot_all"] / 100;
		            }
		        	if (($mAttr['rewardparam']&128)> 0){
				    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
				    	if (!empty($inRecord['reward_userid']) && intval($inRecord['reward_userid'])>0){
				    		$inRecord['ticheng_send_status']=1;
				    	}
				    }
			            
		        	 $inRecord = array_merge($inRecord, $mAttr);
		        	 //创建我们的订单记录及记录
			         $item = new Rewards;
			         $item->setScenario('newCreate');
			         $item->attributes = $inRecord;
					 
			         if ($item->judgeDoubleRecord($inRecord)<= 1){
				         if($item->save()){
				         	$bRtn = true;
				         }else{
				        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].',通知事务回滚');
				         }
			         }
			         
			         $rJobs = RewardJobs::model()->getRewardJobs($mAttr["type"], ''); //其它所有相关的职位

		             if(!empty($rJobs) && !empty($mAttr["inviter_oa"])){
			             foreach ($rJobs as $k => $val) {
			             	
			             	$bfind = false; //准备其它相关职位
			             	
			                $jobName = $rJobs[$k]['job_name'];
			                if(empty($jobName)){
			                	continue;
			                }
			                if ((strnatcmp(strtoupper($jobName),strtoupper('总部客户部'))==0) 
			                	||(strnatcmp(strtoupper($jobName),strtoupper('事业部客户部经理'))==0)
			                	||(strnatcmp(strtoupper($jobName),strtoupper('客户经理'))==0)
			                	||(strnatcmp(strtoupper($jobName),strtoupper('员工(非客户经理)'))==0)){
			                		continue;
			                 }

							$param = isset($rJobs[$k]['reward_param'])?F::price_formatNew($rJobs[$k]['reward_param']):0.00;
		            		$al_param = isset($rJobs[$k]['allot_param'])?F::price_formatNew($rJobs[$k]['allot_param']):0.00;
			                 
			                 $oaJobs = RewardJobs::model()->getOAJobs($jobName);
			                 $iNum = count($oaJobs);
			                 $manNum = $iNum;
			                 
			                 if($iNum == 0){
			                 	$data4User = SsoSystemApi::getInstance()->getUser4Job(trim($mAttr["inviter_oa"]), trim($jobName), $mAttr["community_id"]); 
			                 	$iNum = count($data4User);
			                 	$manNum = $iNum;

			                 	foreach ($data4User as $k1 => $val1) {
			                 		$inRecord = array();
			                 		
			                 		$inRecord['jobkey'] = $rJobs[$k]['jobkey'];
			                 		$inRecord['reward_userid'] = $this->getEmployeeByOa('oa_username', $data4User[$k1]['username'], 'id');
					               	$inRecord['reward_username'] = $data4User[$k1]['realname'];
					               	$inRecord['reward_mobile'] = $data4User[$k1]['mobile'];
					               	$inRecord['reward_jobs'] = $data4User[$k1]['jobname'];
					               	
						            $inRecord['user_id'] = $data4User[$k1]['username'];
						            $inRecord['region'] = isset($data4User[$k1]['region'])? $data4User[$k1]['region']: '';
						            $inRecord['region_id'] = isset($data4User[$k1]['region_id'])? $data4User[$k1]['region_id']: '';
						            $inRecord['community'] = isset($data4User[$k1]['community'])? $data4User[$k1]['community']: '';
						            $inRecord['community_id'] = isset($data4User[$k1]['community_id'])? $data4User[$k1]['community_id']: '';
						            $inRecord['branch'] = isset($data4User[$k1]['branch'])? $data4User[$k1]['branch']: '';
						            $inRecord['branch_id'] = isset($data4User[$k1]['branch_id'])? $data4User[$k1]['branch_id']: '';
					               	
		            				$inRecord['reward'] = $al_param * $mAttr["allot_all"] /100 / $manNum;
		            				
				                 	if (($mAttr['rewardparam']&128)> 0){
								    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
								    	if (!empty($inRecord['reward_userid']) && intval($inRecord['reward_userid'])>0){
								    		$inRecord['ticheng_send_status']=1;
								    	}
								    }
		            				
		            				$inRecord = array_merge($inRecord, $mAttr);
		            				
			                 		//创建我们的订单记录及记录
							        $item = new Rewards;
							        $item->setScenario('newCreate');
							        $item->attributes = $inRecord;
							        
							        $bfind = true; //找到了此职位相关人
									
							        if ($item->judgeDoubleRecord($inRecord)<= 1){
								        if($item->save()){
								        	$bRtn = true;
								        }else{
								        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].',通知事务回滚');
								        }
							        }else{
							        	$manNum = $manNum - 1;
							        	$model_list = $this->gainDoubleRecord($inRecord);
										if ($model_list){
											
											foreach ($model_list as $model_db) {
												if ($manNum > 0)
							 					$model_db->reward = $al_param * $mAttr["allot_all"] /100 / $manNum;
			
												if ($model_db->save()){
													;
												}
											}
										}
							        }
							        
			                 	}
			                 }else{ //有对应的OA Job
			                 	$iNum = 0;
			                 	$manNum = 0;
			                 	$inUsers = array();
			                 	
			                 	$jobSql = "";

			                 	foreach ($oaJobs as $k2 => $val2) {
									
				                 	$data4User = SsoSystemApi::getInstance()->getUser4Job(trim($mAttr["inviter_oa"]), trim($oaJobs[$k2]['oa_job']), $mAttr["community_id"]); 
				                 	$iNum = $iNum + count($data4User);
				                 	$manNum =  $manNum + count($data4User);
				                 	
				                 	$jobSql = $jobSql."'".trim($oaJobs[$k2]['oa_job'])."' ,";

				                 	foreach ($data4User as $k1 => $val1) {
				                 		$inRecord = array();
				                 		
				                 		$inRecord['jobkey'] = $rJobs[$k]['jobkey'];
	
				                 		$inRecord['reward_userid'] = $this->getEmployeeByOa('oa_username', $data4User[$k1]['username'], 'id');
				                 		$inUsers[] = $inRecord['reward_userid'];
				                 		
						               	$inRecord['reward_username'] = $data4User[$k1]['realname'];
						               	$inRecord['reward_mobile'] = $data4User[$k1]['mobile'];
						               	//$inRecord['reward_jobs'] = $data4User[$k1]['jobname'];
						               	$inRecord['reward_jobs'] = trim($oaJobs[$k2]['oa_job']);
						               
							            $inRecord['user_id'] = $data4User[$k1]['username'];
							            $inRecord['region'] = isset($data4User[$k1]['region'])? $data4User[$k1]['region']: '';
							            $inRecord['region_id'] = isset($data4User[$k1]['region_id'])? $data4User[$k1]['region_id']: '';
							            $inRecord['community'] = isset($data4User[$k1]['community'])? $data4User[$k1]['community']: '';
							            $inRecord['community_id'] = isset($data4User[$k1]['community_id'])? $data4User[$k1]['community_id']: '';
							            $inRecord['branch'] = isset($data4User[$k1]['branch'])? $data4User[$k1]['branch']: '';
							            $inRecord['branch_id'] = isset($data4User[$k1]['branch_id'])? $data4User[$k1]['branch_id']: '';
						               	
			            				$inRecord['reward'] = $al_param * $mAttr["allot_all"] / 100; ///应当/iNum
			            				
					                 	if (($mAttr['rewardparam']&128)> 0){
									    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
									    	if (!empty($inRecord['reward_userid']) && intval($inRecord['reward_userid'])>0){
									    		$inRecord['ticheng_send_status']=1;
									    	}
									    }
			            				
			            				$inRecord = array_merge($inRecord, $mAttr);
			            				
				                 		//创建我们的订单记录及记录
								        $item = new Rewards;
								        $item->setScenario('newCreate');
								        $item->attributes = $inRecord;
								        
								        $bfind = true; //找到了此OA职位相关人
								        
								        if ($item->judgeDoubleRecord($inRecord)<= 1){
									        if($item->save()){
									        	$bRtn = true;
									        }else{
									        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].',通知事务回滚');
									        }
								        }else{
								        	$manNum = $manNum - 1;
								        }
				                 	}
			                 	}
			                 	
			                 	if($iNum > 0 && count($inUsers) > 0){

									$sql="SELECT * FROM rewards WHERE sn ='".$mAttr['sn']."' and rela_sn ='".$mAttr['rela_sn']."' and reward_userid in (";
									$sSql = "";
									foreach ($inUsers as $u1) {
										$sSql = $sSql."'".$u1."' ,";
									}
									$sql = $sql.$sSql;
									$sql = substr($sql,0,strlen($sql)-1);
									$sql = $sql.")";
									
									if (strlen($jobSql) > 1){
										$sql =$sql." and reward_jobs in (";
										$sql = $sql.$jobSql;
										$sql = substr($sql,0,strlen($sql)-1);
										$sql = $sql.")";
									}

									$mdfModels = Rewards::model()->findAllBySql($sql);
									if(!empty($mdfModels)){
										foreach($mdfModels as $mdfModel){
											if ($manNum > 0){
												$mdfModel->reward = $mdfModel->reward / $manNum;
												$mdfModel->save();
											}
										}
									}

//			                 		$connection = Yii::app()->db;
//							        $sql = "update rewards set reward=reward/".$iNum." where sn ='".$mAttr['sn']."' and rela_sn ='".$mAttr['rela_sn']."' and reward_userid in (";
//							        $sSql = "";
//							        foreach ($inUsers as $u1) {
//							        	$sSql = $sSql."'".$u1."' ,";
//							        }
//							        $sql = $sql.$sSql;
//
//							        $sql = substr($sql,0,strlen($sql)-1);
//							        $sql = $sql.")";
//							        $command = $connection->createCommand($sql);
//							        ///$command->execute();
//			                 		if ($command->execute()<=0){
//							        	throw new CException('信息保存失败:更改相关奖励份,通知事务回滚');
//							        }
								}
							}
							
			             	if(!$bfind){  //没有找到此职位的人员信息
			             		$inRecord = array();
					        	$inRecord['reward_username'] = $jobName;
					            $inRecord['reward_jobs'] = $jobName;
					            $inRecord['reward_userid'] = '0';
					            $inRecord['reward_mobile'] = '';
					            
					            //$inRecord['istate_all'] = 0; //表明是是其他职位: 0x0XX
					            if (strnatcmp(strtoupper($jobName),strtoupper('小组团队长'))==0){
					            	$inRecord['istate_all'] = 4096;
					            } 
					            $inRecord['jobkey'] = $rJobs[$k]['jobkey'];
					
				            	$inRecord['reward'] =  $al_param * $mAttr["allot_all"] / 100 ;
				            	
			             		if (isset($mAttr['region_4bm'])){
					            	$inRecord['region'] = $mAttr['region_4bm'];
					            	$inRecord['region_id'] = $mAttr['region_id_4bm'];
					            	$inRecord['branch'] = $mAttr['branch_4bm'];
					            	$inRecord['branch_id'] = $mAttr['branch_id_4bm'];
					            }
				             	if (($mAttr['rewardparam']&128)> 0){
							    	$inRecord['istate_all'] = $inRecord['istate_all']|16;
							    }
	
							    $inRecord = array_merge($inRecord, $mAttr);
							    
						        //创建我们的订单记录及记录
						        $item = new Rewards;
						        $item->setScenario('newCreate');
						        $item->attributes = $inRecord;
								
						        if ($item->judgeDoubleRecord($inRecord)<= 1){
							        if($item->save()){
							        	$bRtn = true;
									}else{
							        	throw new CException('信息保存失败:'.$inRecord['reward_jobs'].',通知事务回滚');
							        }
						        }
								
					        }
						}
					}
	        	}else {
	        		//员工推荐// 暂时没有奖励
	        	}
				
	        }else{
	        	Rewards::model()->addError('id', "理财产品类型数据出现错误！");
	        	
	            $bRtn = false;
	        }
	        (!$isTransaction)?$transaction->commit():'';
		}
    	catch(Exception $e) {

    		$bRtn = false;
			(!$isTransaction)?$transaction->rollback():''; // 在异常处理中回滚
	    }
        return $bRtn;
    }
    
     /**
     * 根据Oa信息来获得彩生活的员工信息
     * @param string $skey  关键字段名
     * @param string $sval  字段值
     * @return array()
     */
    public function getEmployeeByOa($skey , $sval, $rtnKey ='')
    { 
        $rtn = array();
        
        if (empty($skey) || empty($sval)){
        	return $rtn;
        }
        
        try 
        {
	        $connection = Yii::app()->db;
	        $sql = "select * FROM employee where is_deleted =0 and ".$skey."= '".$sval."' limit 1";
	        
	        $command = $connection->createCommand($sql);
	        $result = $command->queryAll();
	        
	        if (count($result) > 0) {
	        	$rtn = $result[0];
	        }
	        if (!empty($rtnKey)){
	        	if(empty($rtn[$rtnKey])){
	        		$rtn = '';
	        	}else{
	        		$rtn = $rtn[$rtnKey];
	        	}
	        }
        } catch (Exception $e) {
        	if (!empty($rtnKey)){
	        	$rtn = '';
	        }
        }    
        
        return $rtn;
    }  
    
    public function  notDoubleSendRed($sRewardUserId = '', $sJobKey ='')
    { 
        $rtn = false;
        
        if (empty($sRewardUserId)){
        	$sRewardUserId = $this->reward_userid;
        }
    	if (empty($sJobKey)){
        	$sJobKey = $this->jobkey;
        }
        if (empty($sJobKey)){
        	$sJobKey = '0';
        }
        if (empty($sRewardUserId)){
        	return false;
        }
    	if(intval($sRewardUserId)==0){
        	return false;
        }
    	$validJobKey = true;
    	try{
    		if (is_numeric($sJobKey)){
	    		if (intval($sJobKey) == 0){
	    			$sJobKey = $this->getJobKey4State();
	    			$validJobKey = false;
	    		}
    		}
    	}catch(Exception $e){
    		$validJobKey = false; 
    	}
        
        try 
        {
	        $connection = Yii::app()->db;
	        $sql = "select id FROM rewards where ((istate_all & 0x10) <> 0) and sn <>'".$this->sn."' and rela_sn= '".$this->rela_sn."' and type ='".$this->type."' limit 1";
	        
	        $command = $connection->createCommand($sql);
	        $result = $command->queryAll();
	        
	        if (count($result) > 0) {
	        	return false;
	        }
	        if ($validJobKey){
	        	$sql = "select id FROM rewards where ((istate_all & 0x10) <> 0) and jobkey ='".$sJobKey."' and reward_userid ='".$sRewardUserId."' and rela_sn= '".$this->rela_sn."' and type ='".$this->type."' limit 1";
	        }else{
	        	$sql = "select id FROM rewards where ((istate_all & 0x10) <> 0) and ((istate_all>>8)&0xfff) ='".$sJobKey."' and reward_userid ='".$sRewardUserId."' and rela_sn= '".$this->rela_sn."' and type ='".$this->type."' limit 1";
	        }
	        
	        $command1 = $connection->createCommand($sql);
	        $result1 = $command1->queryAll();
	        
	        if (count($result1) > 0) {
	        	return false;
	        }
        } catch (Exception $e) {
        	return false;
        }    
        
        return true;
    }  
    
    public function  notDoubleSendRed4OverTm()
    { 
        $sRewardUserId = $this->reward_userid;
         
    	$sJobKey = $this->jobkey;
        
        if (empty($sJobKey)){
        	$sJobKey = '0';
        }
        $validJobKey = true;
    	try{
    		if (is_numeric($sJobKey)){
	    		if (intval($sJobKey) == 0){
	    			$validJobKey = false;
	    		}
    		}
    	}catch(Exception $e){
    		$validJobKey = false; 
    	}
        
        $existMan = true;
        
        if (empty($sRewardUserId) || (intval($sRewardUserId)==0)){
        	$existMan = false;
        }
    	 
        try 
        {
	        $connection = Yii::app()->db;
	        $sql = "select id FROM rewards where ((istate_all & 0x10) <> 0) and sn <>'".$this->sn."' and rela_sn= '".$this->rela_sn."' and type ='".$this->type."' limit 1";
	        
	        $command = $connection->createCommand($sql);
	        $result = $command->queryAll();
	        
	        if (count($result) > 0) {
	        	return false;
	        }
	        
	        if ($existMan){
	        	$sql = "select id FROM rewards where ((istate_all & 0x10) <> 0) and jobkey ='".$sJobKey."' and reward_userid ='".$sRewardUserId."' and rela_sn= '".$this->rela_sn."' and type ='".$this->type."' limit 1";
	        	
	        }else{
	        	if ($validJobKey){
	        		$sql = "select id FROM rewards where ((istate_all & 0x10) <> 0 or (rewardparam & 0x80) <> 0 or (reward_userid <>'' and reward_userid is not null 
	        		and reward_userid <> '0')) and sn ='".$this->sn."' and id <>".$this->id." and jobkey ='".$sJobKey."' and rela_sn= '".$this->rela_sn."' and type ='".$this->type."' limit 1";
	        	}else{
	        		$sql = "select id FROM rewards where ((istate_all & 0x10) <> 0 or (rewardparam & 0x80) <> 0 or (reward_userid <>'' and reward_userid is not null 
	        		and reward_userid <> '0')) and sn ='".$this->sn."' and id <>".$this->id." and reward_jobs ='".$this->reward_jobs."' and rela_sn= '".$this->rela_sn."' and type ='".$this->type."' limit 1";
	        	}
	        }

	        $command1 = $connection->createCommand($sql);
	        $result1 = $command1->queryAll();
	        
	        if (count($result1) > 0) {
	        	return false;
	        }
        } catch (Exception $e) {
        	return false;
        }    
        
        return true;
    }  
    
    public function beCanSendRed()
    {
    	$rtn = false;
    	
    	if (empty($this->type) || empty($this->rela_sn))
    	{
    		return false;
    	}
    	if ($this->allot_all < 0.01){
    		return false;
    	}
    	
    	try 
        {
	        $connection = Yii::app()->db;
	        $sql = "select type, sn FROM reward_import_cases where (kind&1) =1 and type= '".$this->type."' and sn= '".$this->rela_sn."' limit 1";
	        
	        $command = $connection->createCommand($sql);
	        $result = $command->queryAll();
	        
	        if (count($result) > 0) {
	        	$rtn = false;
	        }else{
	        	$rtn = true;
	        }
	       
        } catch (Exception $e) {
        	$rtn = false;
        }    
    	
    	return $rtn;
    }
    
	public function add4White($whiteMdl)
    {
    	if (empty($whiteMdl)){
    		return false;
    	}

		$fjr_types = array(
			'ParkingFees2' => 'ParkingFees',
			'PropertyFees2' => 'PropertyFees',
			'AdvanceFees' => 'AdvanceFees',
		);

    	$rela_sn = $whiteMdl->rela_sn;
    	$type = $whiteMdl->type;
    	$mobile = $whiteMdl->customer_mobile;
    	
    	
    	if (empty($rela_sn) || empty($type) || empty($mobile)){
    		return false;
    	}
    	
    	if (empty($whiteMdl->reward_rate) ||($whiteMdl->reward_rate < 0.01)){
    		return false;
    	}

       	if (strnatcmp(strtoupper($type),'RXH')==0)  //RXH产品类型
    	{
    		 $sql ="select id,sn, rxh_sn as rela_sn, type, customer_id, name as customer_name, mobile, amount, pay_time, repayment_date,
        			inviter_mobile, income_rate, licai_time_month, licai_time_day, create_time 
        			from rxh_order where type='".$type."' and rxh_sn='".$rela_sn."' 
        			and mobile='".$mobile."'";
    		 
    	}else if (strnatcmp(strtoupper($type),strtoupper('hhn_eload'))==0){
    		

    	}else if (strnatcmp(strtoupper($type),strtoupper('elicai'))==0){
    
    		 $sql = "select id, sn, e_sn  as rela_sn, type, customer_id, name as customer_name, mobile, month, amount,pay_time,
        			inviter_id, inviter_name, inviter_mobile, revise_type, create_time, ticheng_send_status  
        			from elicai_redpacket_ticheng where customer_id>0 and type='".$type."'  
        			and e_sn='".$rela_sn."' and mobile='".$mobile."'";

    	}else if ((strnatcmp(strtoupper($type),strtoupper('ParkingFees2'))==0)
    			||(strnatcmp(strtoupper($type),strtoupper('PropertyFees2'))==0)
    			||(strnatcmp(strtoupper($type),strtoupper('AdvanceFees'))==0)){

    		$sql = "select at.id, at.sn as rela_sn, at.model as type, at.customer_id, bt.name as customer_name, bt.mobile, at.amount, at.pay_time,
        		    at.object_id, at.payment_id, at.bank_pay,at.red_packet_pay,at.user_red_packet,
        			at.pay_id, at.pay_rate, at.create_time from others_fees at left join customer bt 
        			on at.customer_id=bt.id where model='".$fjr_types[$type]."' and at.STATUS=99 AND bt.mobile<>'' 
        			AND bt.is_deleted=0  AND bt.state=0 and at.sn='".$rela_sn."' and bt.mobile='".$mobile."'";

    	}else if ((strnatcmp(strtoupper($type),strtoupper('PropertyActivity'))==0)
    			||(strnatcmp(strtoupper($type),strtoupper('PropertyFees'))==0)
    			||(strnatcmp(strtoupper($type),strtoupper('ParkingFeesMonth'))==0)
    			||(strnatcmp(strtoupper($type),strtoupper('ParkingFees'))==0)){
				   				
    			$sql = "select at.id, at.sn as rela_sn, at.model as type, at.customer_id, at.customer_name, bt.name, bt.mobile, at.amount + at.increase_amount as amount, at.pay_time,
        			at.earnings, at.reduction, at.rate_id, at.object_id, at.community_rate, at.mitigate_starttime,
        			at.mitigate_endtime,at.inviter_mobile, at.create_time from property_activity at left join customer bt 
        			on at.customer_id=bt.id where (at.STATUS=99 or at.STATUS=96 or at.STATUS=88) AND bt.mobile<>'' 
        			AND bt.is_deleted=0  AND bt.state=0 and model='".$type."'  
        			and at.sn='".$rela_sn."' and bt.mobile='".$mobile."'";
    			
    	}else if (strnatcmp(strtoupper($type),strtoupper('zzhplan'))==0){
    		
    		$sql = "select at.id, at.sn as rela_sn, at.model as type, at.customer_id, at.customer_name, bt.name, bt.mobile, at.amount, at.pay_time, 
        			at.customer_card, at.user_rate, at.rate_id, at.object_id, at.profit, at.surplus_money, at.begin_time, at.stop_time,at.recover_time, 
        			at.cash_create_time,at.cash_return_time,at.note,at.source, at.pay_sn, at.payment_passage, at.inviter_mobile, 
        			at.create_time from appreciation_plan at left join customer bt on at.customer_id=bt.id where (model='Zengzhi')  
        			and (at.STATUS=99 or at.STATUS=96 or at.STATUS=88) AND bt.mobile<>'' AND bt.is_deleted=0  AND bt.state=0  
							and at.sn='".$rela_sn."' and bt.mobile='".$mobile."'";
    		
    	}

    	if (empty($sql)){
    		return false;
    	}
    	else{
    		$sql = $sql." limit 3";
    	}

		$connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        
        $result = $command->queryAll();
        
    	if (count($result) > 0) {
    		
    		if (count($result) > 1) {
				return false;
    		}else{			
    			$m_id = $result[0]['id']; 
		    	$m_rela_sn = $result[0]['rela_sn']; 
		    	$m_type = $result[0]['type'];

    			if (strnatcmp(strtoupper($type),'RXH')==0)  //RXH产品类型
		    	{
		    		 $items = array(
		                'customer_id' =>$result[0]['customer_id'],//用户的ID
		            	'name' =>$result[0]['customer_name'],//用户
		            	'mobile' =>$result[0]['mobile'],//用户的mobile
		                'type' => $result[0]['type'],
		                'rela_sn' =>$result[0]['rela_sn'],//sn,
		                'amount' => $result[0]['amount'],
			            'pay_time' => $result[0]['pay_time'],
			            'inviter_mobile' => $result[0]['inviter_mobile'],
			            'repayment_date' => $result[0]['repayment_date'],
			            'income_rate' => $result[0]['income_rate'],
			            'licai_time_month' => $result[0]['licai_time_month'],
		            	'licai_time_day' => $result[0]['licai_time_day'],
		            	'create_time' => $result[0]['create_time'],
		            );
		    	}else if (strnatcmp(strtoupper($type),strtoupper('hhn_eload'))==0){
		    		
		
		    	}else if (strnatcmp(strtoupper($type),strtoupper('elicai'))==0){
		    	 
		    		 $items = array(
		                'customer_id' =>$result[0]['customer_id'],//用户的ID
		            	'name' =>$result[0]['customer_name'],//用户
		            	'mobile' =>$result[0]['mobile'],//用户的mobile
		                'type' => $result[0]['type'],
		                'rela_sn' =>$result[0]['rela_sn'],//sn,
		                'amount' => $result[0]['amount'],
			            'pay_time' => $result[0]['pay_time'],
			            'inviter_mobile' => $result[0]['inviter_mobile'],
			            'inviter' => $result[0]['inviter_id'],
			            'inviter_name' => $result[0]['inviter_name'],
			            'month' => $result[0]['month'],
		            	'revise_type' => $result[0]['revise_type'],
		            	'create_time' => $result[0]['create_time'],
		            );
		
		    	}else if ((strnatcmp(strtoupper($type),strtoupper('ParkingFees2'))==0)
		    			||(strnatcmp(strtoupper($type),strtoupper('PropertyFees2'))==0)
		    			||(strnatcmp(strtoupper($type),strtoupper('AdvanceFees'))==0)){
		
		    		
		    		$items = array(
		                'customer_id' =>$result[0]['customer_id'],//用户的ID
		            	'name' =>$result[0]['customer_name'],//用户
		            	'mobile' =>$result[0]['mobile'],//用户的mobile
		                'type' => $type,
		                'rela_sn' =>$result[0]['rela_sn'],//sn,
		                'amount' => $result[0]['amount'],
			            'pay_time' => $result[0]['pay_time'],
			            'object_id' => $result[0]['object_id'],
			            'payment_id' => $result[0]['payment_id'],
		            	'bank_pay' => $result[0]['bank_pay'],
			            'red_packet_pay' => $result[0]['red_packet_pay'],
			            'user_red_packet' => $result[0]['user_red_packet'],
			            'pay_id' => $result[0]['pay_id'],
		            	'pay_rate' => $result[0]['pay_rate'],
		            	'create_time' => $result[0]['create_time'],
		            );
		
		    	}else if ((strnatcmp(strtoupper($type),strtoupper('PropertyActivity'))==0)
		    			||(strnatcmp(strtoupper($type),strtoupper('PropertyFees'))==0)
		    			||(strnatcmp(strtoupper($type),strtoupper('ParkingFeesMonth'))==0)
		    			||(strnatcmp(strtoupper($type),strtoupper('ParkingFees'))==0)){
		    			
		    			$items = array(
			                'customer_id' =>$result[0]['customer_id'],//用户的ID
			            	'name' =>$result[0]['customer_name'],//用户
			            	'mobile' =>$result[0]['mobile'],//用户的mobile
			                'type' => $result[0]['type'],
			                'rela_sn' =>$result[0]['rela_sn'],//sn,
			                'amount' => $result[0]['amount'],
				            'pay_time' => $result[0]['pay_time'],
				            'inviter_mobile' => $result[0]['inviter_mobile'],
				            'earnings' => $result[0]['earnings'],
				            'reduction' => $result[0]['reduction'],
				            'rate_id' => $result[0]['rate_id'],
			            	'object_id' => $result[0]['object_id'],
				            'community_rate' => $result[0]['community_rate'],
				            'mitigate_starttime' => $result[0]['mitigate_starttime'],
				            'mitigate_endtime' => $result[0]['mitigate_endtime'],
			            	'create_time' => $result[0]['create_time'],
			            );
	            }else if (strnatcmp(strtoupper($type),strtoupper('zzhplan'))==0){
	            	
	            		$items = array(
			                'customer_id' =>$result[0]['customer_id'],//用户的ID
			            	'name' =>$result[0]['customer_name'],//用户
			            	'mobile' =>$result[0]['mobile'],//用户的mobile
			                'type' => $type,
			                'rela_sn' =>$result[0]['rela_sn'],//sn,
			                'amount' => $result[0]['amount'],
				            'pay_time' => $result[0]['pay_time'],
				            'inviter_mobile' => $result[0]['inviter_mobile'],
				            'customer_card' => $result[0]['customer_card'],
				            'user_rate' => $result[0]['user_rate'],
				            'rate_id' => $result[0]['rate_id'],
			            	'object_id' => $result[0]['object_id'],
				            'profit' => $result[0]['profit'],
				            'surplus_money' => $result[0]['surplus_money'],
				            'begin_time' => $result[0]['begin_time'],
		            		'stop_time' => $result[0]['stop_time'],
		            		'recover_time' => $result[0]['recover_time'],
		            		'payment_passage' => $result[0]['payment_passage'],
		            		'source' => $result[0]['source'],
		            		'note' => $result[0]['note'],
		            		'pay_sn' => $result[0]['pay_sn'],
		            		'cash_create_time' => $result[0]['cash_create_time'],
		            		'cash_return_time' => $result[0]['cash_return_time'],
			            	'create_time' => $result[0]['create_time'],
			            );
			            
			            $m_type = 'zzhplan';
	            }
		    	
		    	$sSql ="select kind, sn from reward_import_cases where (kind&8)=8 and  
		    			type='".$m_type."' and relaId =".$m_id." and sn ='".$m_rela_sn."' limit 1";
		    	    	
	    		$rs_t1= $connection->createCommand($sSql)->queryAll();

		        if (count($rs_t1) > 0) {
		        	return false;
		        }else{
		        	
		        	$sSql ="select max(lastid) as maxid from reward_import_mark where type='".$m_type."' ";
		        	$m_lastid = 0;
		        	$rs_t2= $connection->createCommand($sSql)->queryAll();

		        	if (count($rs_t2) > 0) {
		        		$m_lastid = $rs_t2[0]['maxid']; 
		        	}
		        	if ($m_lastid <=$m_id){

		        		return false;
		        	}else{
		        		if ($this->addZbRecord($items, $m_id, $whiteMdl->reward_rate, $whiteMdl->allot_zb))
		        		{
		        			return $this->doOkMark($type, $m_id, $m_rela_sn,'白名单保存');
		        		}
		        	}
		        }
    		}
        }
		return false;
    }
    
	private function doOkMark($type, $id, $sn, $remark = '')
    {
    	if (empty($id) && empty($sn)){
    		return false;
    	}
    	
    	if (empty($remark)){
    		$remark = "订单数据已处理！";
    	}
    	$db = Yii::app()->db;
    	
    	$insert_sql = "insert into reward_import_cases (type, relaId, sn, remark, kind, create_time) values ('".
                        $type."',".$id.",'".$sn."','".$remark."', 8,'".time()."');";
                        
        $res2 = $db->createCommand($insert_sql)->execute();
        //kind 为 8时，表明是成功处理的数据
        return $res2;
	}
	
	public function addZbRecord($mAtt, $relaId, $reward_rate, $allot_zb){
		
		$rate1 = isset($reward_rate)?F::price_formatNew($reward_rate):0.00;
        if($rate1 < 0.01){
			//$arr[]=array( "ok" => 0,'Sn' => $orderData["rela_sn"],'Status' => 0, 'Message'=>'奖金参数为零,暂不处理.');
			return false;
		}
        if(!empty($mAtt)){
            ///if(empty($mAtt["rela_sn"])) {throw new CHttpException(400,"订单ID不能为空");}
            if(empty($mAtt["customer_id"])) {throw new CHttpException(400,"彩之云用户ID不能为空");}
            //if(empty($mAtt["name"])) {throw new CHttpException(400,"投资人姓名不能为空");}
            if(empty($mAtt["mobile"])) {throw new CHttpException(400,"投资人手机号不能为空");}
            if(empty($mAtt["type"])) {throw new CHttpException(400,"理财产品类型");}
            if(empty($mAtt["amount"])) {throw new CHttpException(400,"投资金额不能为空");}           
            
            if (empty($mAtt["name"])){
            	$mAtt["name"] ='访客';
            }
        	$sn = SN::initByRewards()->sn;

            $orderData = $mAtt;
            $orderData["sn"] = $sn;
            $orderData["rela_sn"] = $mAtt["rela_sn"];
            $orderData["amount"] = isset($mAtt['amount'])?F::priceFormat($mAtt['amount']):0.00;
          
            if (!isset($orderData["create_time"])){
            	$orderData["create_time"] = time();
            }

	        $rewardZbId = 'caifrsb';
	        $rewardZbNme = '彩富人生部';
	        try {
		        $zbSpitTm = $orderData["pay_time"];
		        if (empty($zbSpitTm) || intval($zbSpitTm) < 1475251201) {
		        	$zbSpitTm = $orderData["create_time"];
		        }
				if (intval($zbSpitTm) < 1488211201){
					$rewardZbId = 'caizhiyunkehu';
					$rewardZbNme = '总部客户部';
				}
	        }catch(Exception $e1) {
	        	$rewardZbId = 'caifrsb';
	        	$rewardZbNme = '彩富人生部';
	        }
            
            if(empty($orderData["rela_sn"])){
            	if(!empty($orderData["rxh_sn"])){
            		$orderData["rela_sn"] = $orderData["rxh_sn"];
            	}
            }
            if(empty($orderData["rela_sn"])){
            	throw new CHttpException(400,"订单ID不能为空");
            }

            if ($orderData['amount']<=0){
            	throw new CHttpException(400,"订单金额不能为空或订单金额格式不正确");
            }
            
            $cu = Customer::model()->findByPK($mAtt["customer_id"]);
            if(!$cu) {throw new CHttpException(400,"提供的彩之云用户ID无效");}

            $rs=Rewards::model()->find('rela_sn=:rela_sn and type=:type', array(':rela_sn'=>$orderData["rela_sn"],':type'=>$orderData["type"]));
            if($rs){
                //$arr[]=array("ok"=>0,'Sn' => $orderData["rela_sn"],'Status' => 0,'ColourSn'=> $rs->sn,'Message'=>'投标ID数据已经存在,不需重复添加');
                return false;
            }
			
            $orderData["community_id"] = $cu["community_id"];

			Yii::import('common.components.MultiTblComm');
			$orderData['allot_all'] = MultiTblComm::getInstance()->getAnnualizedAllot($rate1, $orderData);
        	if ($orderData['allot_all'] <0.01) //奖励项没钱
			{
				//$arr[]=array( "ok" => 1,'Sn' => $orderData["rela_sn"],'Status' => 0, 'Message'=>'奖金极低,暂不处理.');
				return false;
			}
            
        	if (!empty($orderData["community_id"])){
        		
        		if (strnatcmp(strtoupper($orderData['community_id']),'585')==0)  //体验小区
        		{
        			if(isset($orderData["object_id"])){
        				if(!empty($orderData["object_id"])){
        					$advFee = AdvanceFee::model()->findByPk($orderData["object_id"]);
        					if(!empty($advFee)){
        						$orderData["community_id"] = $advFee->community_id;
        					}
        				}
        			}
        		}
            	
            	Yii::import('common.api.SsoSystemApi');
            	$data_bm = SsoSystemApi::getInstance()->getBmManager(trim($orderData["community_id"]));
	        	if(!empty($data_bm)){
	        		$orderData['region'] = isset($data_bm[0]['region'])? $data_bm[0]['region']: '';
		            $orderData['region_id'] = isset($data_bm[0]['region_id'])? $data_bm[0]['region_id']: '';
		            $orderData['branch'] = isset($data_bm[0]['branch'])? $data_bm[0]['branch']: '';
		            $orderData['branch_id'] = isset($data_bm[0]['branch_id'])? $data_bm[0]['branch_id']: '';

	        	}
            }
        	if(empty($orderData['inviter_mobile'])){//如果业主没有填写推荐人手机号码，则通过该业主是否绑定了 专属客服经理（通过专属客服经理绑定表对照）
	            if($cu){
	            	$bind = CustomerBindManager::model()->find('proprietor_id=:uid and state=1',array(':uid'=>$cu->id));
		        	if($bind&&!empty($bind->manager)){
		            	$bind2 = EmployeeBindCustomer::model()->find('customer_id=:uid and state=1',array(':uid'=>$bind->manager_id));
		            	if($bind2&&!empty($bind2->employee)){
		                	$orderData["inviter_id"]=$bind2->employee_id;
		                    //$orderData["send_type"]=1;
		               	}else{
		                	$orderData["inviter_id"]=0;
		                }
		             }else{
	             		$orderData["inviter_id"]=0;
	             	 }
	            }else{
					$orderData["inviter_id"]=0;
	            }
			}else{//如果 业主填写了推荐人手机号码，首先优先判断该手机号码(认为填写的是彩之云手机号)是否存在对应的oa账户（通过彩之云绑定表对照）
                        
	        	$c1= Customer::model()->find('mobile=:mobile',array(':mobile'=>$orderData['inviter_mobile']));
	         	if($c1){
	            	$bind = EmployeeBindCustomer::model()->find('customer_id=:uid and state=1',array(':uid'=>$c1->id));
	            	if($bind&&!empty($bind->employee)){
	                	$orderData["inviter_id"]=$bind->employee_id;
	                }else{
	                	$orderData["inviter_id"]=0; 
	                }
	            }else{
	            	$orderData["inviter_id"]=0;
	            }
			}
			$orderData["inviter"]= $orderData["inviter_id"];
			
			if (intval($orderData["inviter_id"]) != 0){ 

				$mEp = Employee::model()->findByPk($orderData["inviter_id"]);
				if($mEp){
					$orderData["inviter_oa"]=$mEp->oa_username; 
					$orderData["inviter_name"]=$mEp->name; 
					$orderData["inviter_mobile"]=$mEp->mobile; 
				}
			}
			
        	if (strnatcmp(strtoupper($orderData['type']),'RXH')==0)  //RXH产品类型
    		{
    			$tb_model = new Reward_rxh;
    		}
    		else if (strnatcmp(strtoupper($orderData['type']),strtoupper('hhn_eload'))==0)
    		{
    			$tb_model = new Reward_loan;
	    		
    		}else{	
    			$tb_model = new RewardComm;
    		}
    		
    		$orderData["create_ip"] =  Yii::app()->request->userHostAddress;
    
	    	if (empty($orderData["customer_name"])){
	            $orderData["customer_name"] = $orderData["name"];
	        }
	    	if (empty($orderData["customer_mobile"])){
	            $orderData["customer_mobile"] = $orderData["mobile"];
	        }

			$pdt_info =  MultiTblComm::getInstance()->getEproductInfo($orderData["type"]);
			if (!empty($pdt_info)){
				$product_type = $pdt_info['product_type'];
				$orderData["order_des"] = "【".$orderData["customer_name"]."】购买【".$orderData["amount"].'】元'.$pdt_info['product_name'] ;
			}
			else{
				$product_type = MultiTblComm::getInstance()->getEproductIType($orderData["type"]);
			}
        	$orderData["model"] = intval($product_type);
        	
        	if (intval($product_type)==1){ //金融类产品
				if (empty($orderData["order_des"])){
					$orderData["order_des"] = "【".$orderData["customer_name"]."】购买【".$orderData["amount"].'】元金融类产品';
				}
        	}else{
				if (empty($orderData["order_des"])){
					$orderData["order_des"] = "【".$orderData["customer_name"]."】购买【".$orderData["amount"].'】元非金融类产品';
				}
			}
       		///总部客户部///
		    $orderData['reward_username'] = $rewardZbNme;
            $orderData['reward_jobs'] = '总部客户部';
            $orderData['region'] = $rewardZbNme;
            
            $orderData['istate_all'] = 256; //表明是总部
            $orderData['jobkey'] = 1; 
            
            $orderData['reward_userid'] = $this->getEmployeeByOa('oa_username', $rewardZbId, 'id');
            $orderData['reward_mobile'] = $this->getEmployeeByOa('oa_username', $rewardZbId, 'mobile');
            $orderData['user_id'] = $rewardZbId;

            $orderData['reward'] =  $allot_zb * $orderData["allot_all"]/100;
            $orderData['rewardparam'] = 0x2000; //白名单
            
        	if ($orderData['reward'] <0.01){
		    	$orderData['istate_all'] = $orderData['istate_all']|16;
		    	$orderData['ticheng_send_status']=1;
		    }

            $isTransaction = Yii::app()->db->getCurrentTransaction();//判断是否有用过事务
 			$transaction = (!$isTransaction)?Yii::app()->db->beginTransaction():'';
        	try 
        	{
	        	if(!$tb_model->createOrder($orderData)){
	        		throw new CException('信息保存失败:总部');
	            }
		        //创建我们的订单记录及记录
		        $item = new Rewards;
		        $item->setScenario('newCreate');
		        $item->attributes = $orderData;
				
		        if ($item->judgeDoubleRecord($orderData)<= 1){
			        if(!$item->save()){
			        	throw new CException('信息保存失败:总部');
			        }
		        }else{
		        	throw new CException('信息保存失败:已有重复数据');
		        }

				(!$isTransaction)?$transaction->commit():'';
	            return true;
	        }
	        catch(Exception $e)
	        {
	            (!$isTransaction)?$transaction->rollback():'';
	            return false;
	        }
	        ///总部客户部  end///
        }else{
            throw new CHttpException(400,"参数不正确");
        }
    }
    
	public function createSingle($mAttr)
	{
		//判断参数
        if (empty($mAttr)) {
            Rewards::model()->addError('id', "接收订单奖励数据失败！");
            return false;
        }

        if (empty($mAttr["type"])){
            Rewards::model()->addError('id', "缺少理财产品类型数据！");
            return false;
        }
        
		$mAttr["create_ip"] =  Yii::app()->request->userHostAddress;
    
    	if (empty($mAttr["customer_name"])){
            $mAttr["customer_name"] = $mAttr["name"];
        }
    	if (empty($mAttr["customer_mobile"])){
            $mAttr["customer_mobile"] = $mAttr["mobile"];
        }	
        
        $bRtn = false;
		
		$item = new Rewards;
        $item->setScenario('newCreate');
        $item->attributes = $mAttr;
        
        if ($item->judgeDoubleRecord($mAttr)<= 1){
	        if($item->save()){
	        	$bRtn = true;
	        }
        }

        return $bRtn;
	}
	
	public function judgeDoubleRecord($mAttr){
		if (empty($mAttr)) {
            return 2;
        }
		if (empty($mAttr["type"])){
            return 2;
        }
		if (empty($mAttr["rela_sn"])){
            return 2;
        }
		if (empty($mAttr["jobkey"])){
            return 2;
        }
        if (!isset($mAttr["reward_userid"])){
        	return 0;
        }
//		if (empty($mAttr["reward_userid"])){
//            return 0;
//        }
		$rs=Rewards::model()->find('rela_sn=:rela_sn and type=:type and jobkey=:jobkey and reward_userid=:reward_userid', array(':rela_sn'=>$mAttr["rela_sn"],':type'=>$mAttr["type"],':jobkey'=>$mAttr["jobkey"],':reward_userid'=>$mAttr["reward_userid"]));
        if($rs){
            //数据已经存在,不需重复添加
            return 3;
        }
        return 1;
	}
	
	public function gainDoubleRecord($mAttr){
		if (empty($mAttr)) {
            return null;
        }
		if (empty($mAttr["type"])){
            return null;
        }
		if (empty($mAttr["rela_sn"])){
            return null;
        }
		if (empty($mAttr["jobkey"])){
            return null;
        }
		if (empty($mAttr["sn"])){
            return null;
        }
 
		$rs=Rewards::model()->findAll('rela_sn=:rela_sn and type=:type and jobkey=:jobkey and sn=:sn', array(':rela_sn'=>$mAttr["rela_sn"],':type'=>$mAttr["type"],':jobkey'=>$mAttr["jobkey"],':sn'=>$mAttr["sn"]));
        if($rs){
            return $rs;
        }
        return null;
	}
    
	public function isExistSame($sn, $type, $rela_sn)
    { 
        if (empty($sn)){
        	return false;
        }
    	if (empty($type)){
        	return false;
        }
        if (empty($rela_sn)){
        	return false;
        }
         
        try 
        {
    		$rs = Rewards::model()->find('type=:type and rela_sn=:rela_sn and sn<>:sn', array(':type'=>$type,':rela_sn'=>$rela_sn,':sn'=>$sn));
    		if ($rs){
    			return true;
    		}
	       
        } catch (Exception $e) {
        	return false;
        }    
        
        return false;
    }  
}
