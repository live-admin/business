<?php

/**
 * This is the model class for table "red_packet".
 *
 * The followings are the available columns in table 'red_packet':
 * @property string $id
 * @property integer $type
 * @property integer $employee_id
 * @property integer $from_type
 * @property integer $to_type
 * @property string $sum
 * @property string $create_time
 * @property string $note
 */
class CaiRedPacket extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public $modelName = '彩管家饭票';
	public $username;
	public $name;
	public $mobile;
	public $branch_id;


	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'cai_red_packet';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('employee_id, note', 'required'),
			array('type, employee_id, from_type, create_time, to_type', 'numerical', 'integerOnly' => true),
			array('sum', 'length', 'max' => 10),
			array('note,remark', 'length', 'max' => 100),
			array('sn,lukcy_result_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('mobile,username,name, branch_id, type, employee_id, from_type, to_type, sum, create_time, note, remark', 'safe', 'on' => 'search'),
			array('mobile,username,name, branch_id, type, employee_id, from_type, to_type, sum, create_time, note, remark', 'safe', 'on' => 'list_search'),
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
			'id' => 'ID',
			'type' => '类型',
			'employee_id' => 'Customer',
			'from_type' => 'From Type',
			'to_type' => 'To Type',
			'sum' => '金额',
			'create_time' => '时间',
			'note' => '备注',
			'username' => 'OA',
			'name' => '员工',
			'mobile' => '员工手机',
			'branch_id' => "部门",
			'lukcy_result_id' => "中奖记录ID",
			'remark' => '捎一句话',
		);
	}

	public function getUsername()
	{
//        return empty($this->employee)?"":$this->employee->username;
//      ICE接入
		if (!empty($this->employee_id)) {
			$employee = ICEEmployee::model()->findbypk($this->employee_id);
			if (!empty($employee['username'])) {
				return $employee['username'];
			}

		}

		return '';
	}

	public function getName()
	{
//        return empty($this->employee)?"":$this->employee->name;
//      ICE接入
		if (!empty($this->employee_id)) {
			$employee = ICEEmployee::model()->findbypk($this->employee_id);
			if (!empty($employee['name'])) {
				return $employee['name'];
			}

		}

		return '';
	}

	public function getMobile()
	{
//        return empty($this->employee)?"":$this->employee->mobile;
//      ICE接入
		if (!empty($this->employee_id)) {
			$employee = ICEEmployee::model()->findbypk($this->employee_id);
			if (!empty($employee->mobile)) {
				return $employee->mobile;
			}

		}

		return '';
	}

	public function getType()
	{
		if ($this->type == Item::RED_PACKET_TYPE_CONSUME) {
			return "消费";
		} else if ($this->type == Item::RED_PACKET_TYPE_ACQUIRE) {
			return "获取";
		}
	}

	public function getRedPacketType()
	{
		return array(
			Item::RED_PACKET_TYPE_CONSUME => '消费',
			Item::RED_PACKET_TYPE_ACQUIRE => '获取'
		);
	}

	/**
	 * @param array $attr 数组元素包括employee_id(用户ID)、from_type(获取方式1or2or3or4)、sum(饭票金额)、
	 * sn(抽奖活动ID：from_type=3,或订单sn:from_type=1or2or4)
	 * @return bool
	 * 添加饭票,
	 */
	public function addRedPacker($attr = array(), &$redInfo = false)
	{
		Yii::log(
			sprintf(
				'addRedPacker 调用: %s %s',
				json_encode($attr),
				json_encode($redInfo)
			),
			CLogger::LEVEL_ERROR,
			'colourlife.core.common.models.CaiRedPacket.addRedPacker'
		);
		//关键参数不能缺失
		if (!isset($attr['employee_id']) or !isset($attr['from_type']) or !isset($attr['sum']) or !isset($attr['sn'])) {
			Yii::log(
			sprintf(
			'addRedPacker 调用，参数不存在：%s',
			json_encode($attr)
			),
			CLogger::LEVEL_ERROR,
			'colourlife.core.common.models.CaiRedPacket.addRedPacker'
					);
			return false;
		}
		if (empty($attr['employee_id']) or empty($attr['from_type']) or empty($attr['sum']) or empty($attr['sn'])) {
			Yii::log(
			sprintf(
			'addRedPacker 调用，参数为空：%s',
			json_encode($attr)
			),
			CLogger::LEVEL_ERROR,
			'colourlife.core.common.models.CaiRedPacket.addRedPacker'
					);
			return false;
		}
		//金额必须为数字
		if (!is_numeric($attr['sum'])) {
			Yii::log(
			sprintf(
			'addRedPacker 调用，金额不是数字：%s',
			$attr['sum']
			),
			CLogger::LEVEL_ERROR,
			'colourlife.core.common.models.CaiRedPacket.addRedPacker'
					);
			return false;
		}

		switch ($attr['from_type']) {
			case Item::CAI_RED_PACKET_FROM_TYPE_GOODJIXAO_AWARD://绩效奖励获得彩管家饭票
				$hasObj = true;
				$note = "通过【绩效奖励获得彩管家饭票】获取饭票【{$attr['sum']}】元";
				$remark = "";
				break;
			case Item::CAI_RED_PACKET_FROM_TYPE_TIXIAN_FAIL_REFUND://彩管家饭票提现失败退还饭票
				$model = SN::findContentBySN($attr['sn']);
				$hasObj = empty($model) ? false : true;
				$note = "订单【{$attr['sn']}】(提现失败)返还饭票【{$attr['sum']}】元";
				$remark = empty($model) ? "" : $model->note;
				break;
			case Item::CAI_RED_PACKET_FROM_TYPE_LOOK_EMONEY_AWARD://找E理财BUG奖励饭票
				$hasObj = true;
				$note = "找E理财BUG奖励饭票【{$attr['sum']}】元";
				$remark = "";
				break;
			case Item::CAI_RED_PACKET_FROM_TYPE_CARRY_EMPLOYEE://同事转账获得饭票
				$model = SN::findContentBySN($attr['sn']);
				$hasObj = empty($model) ? false : true;
				$note = "订单【{$attr['sn']}】(获得同事饭票)获得饭票【{$attr['sum']}】元";
				$remark = empty($model) ? "" : $model->note;
				break;
			case Item::CAI_RED_PACKET_FROM_TYPE_MANAGER_AWARD://获得总经理奖励饭票
				$hasObj = true;
				$note = "通过【总经理奖励】获取饭票【{$attr['sum']}】元";
				$remark = "";
				break;
			case Item::CAI_RED_PACKET_FROM_TYPE_PROPERTY_ACTIVITY_AWARD://保本保收益冲抵物业费奖励饭票
				$hasObj = true;
				$note = "通过【冲抵物业费奖励】获取饭票【{$attr['sum']}】元";
				$remark = "";
				break;
			case Item::CAI_RED_PACKET_FROM_TYPE_CAIFU_TICHEN_JIANGLI://彩富人生推荐提成奖励
				$model = SN::findContentBySN($attr['sn']);
				$hasObj = empty($model) ? false : true;
				$note = "订单【{$attr['sn']}】(彩富人生推荐提成自动发放奖励)获得饭票【{$attr['sum']}】元";
				$remark = $attr['remark'];
				break;
			case Item::CAI_RED_PACKET_FROM_TYPE_ELICAI_DINGQI_TICHEN_JIANGLI://E理财定期提成奖励
				$model = SN::findContentBySN($attr['sn']);
				$hasObj = empty($model) ? false : true;
				$note = "订单【{$attr['sn']}】(E理财定期提成奖励自动发放)获得饭票【{$attr['sum']}】元";
				$remark = $attr['remark'];
				break;
			case Item::CAI_RED_PACKET_FROM_TYPE_RXH_LICAI_TICHEN_JIANGLI://荣信汇理财奖励饭票
				$model = SN::findContentBySN($attr['sn']);
				$hasObj = empty($model) ? false : true;
				$note = "订单【{$attr['sn']}】(荣信汇理财提成奖励自动发放)获得饭票【{$attr['sum']}】元";
				$remark = $attr['remark'];
				break;
			case Item::CAI_RED_PACKET_FROM_TYPE_REWARDS_JIANGLI://订单推荐提成奖励
				$hasObj = true;
				$note = "订单【{$attr['rela_sn']}】(提成系统发放奖励)获得红包【{$attr['sum']}】元";
				$remark = $attr['remark'];
				break;
			case Item::CAI_RED_PACKET_FROM_TYPE_ARREARS_RETURN://大额欠费回收奖励彩管家
				$hasObj = true;
				$note = "订单【{$attr['sn']}】(大额欠费回收奖励彩管家)获得红包【{$attr['sum']}】元";
				$remark = $attr['remark'];
				break;

            default;
                $hasObj = false;
                //$note = "订单【{$attr['sn']}】获得饭票【{$attr['sum']}】元";;;
                $note = isset($attr['note']) ? $attr['note'] : "订单【{$attr['sn']}】获得饭票【{$attr['sum']}】元";
                $remark = isset($attr['remark']) ? $attr['remark'] : '';
        }
        
        // 内部充值处理
        if(strpos($attr['sn'], '113') === 0){
        	Yii::log(
        	sprintf(
        	'addRedPacker 调用，判断是否为113开头的订单：%s',
			json_encode($attr)
        	//strpos($attr['sn'], '113')
        	),
        	CLogger::LEVEL_ERROR,
        	'colourlife.core.common.models.CaiRedPacket.addRedPacker'
        			);
            $model = SN::findContentBySN($attr['sn']);
            $hasObj = empty($model)?false:true;
            $remark = isset($attr['remark']) ? $attr['remark'] : '';
        }
        Yii::log(
        sprintf(
        'addRedPacker 调用，参数已整理完：%s',
        json_encode($attr)
        ),
        CLogger::LEVEL_ERROR,
        'colourlife.core.common.models.CaiRedPacket.addRedPacker'
        		);
        //如果传入的sn找不到对象
        if(!$hasObj){
        	Yii::log(
        	sprintf(
        	'addRedPacker 调用，订单找不到：%s',
        	$attr['sn']
        	),
        	CLogger::LEVEL_ERROR,
        	'colourlife.core.common.models.CaiRedPacket.addRedPacker'
        			);
        	return false;
        }

        $attr['type'] = Item::RED_PACKET_TYPE_ACQUIRE;//设置属性为获取
        $attr['note'] = $note;//备注
        $attr['remark'] = $remark;//捎一句话

		Yii::log(
			sprintf(
				'addRedPacker 参数转换: %s',
				json_encode($attr)
			),
			CLogger::LEVEL_ERROR,
			'colourlife.core.common.models.CaiRedPacket.addRedPacker'
		);

        $redPacket = new self();
        $employee = Employee::model()->findByPk($attr['employee_id']);
        if(!$employee){
        	Yii::log(
        	sprintf(
        	'addRedPacker 报%s员工不存在错误',
        	$attr['employee_id']
        	),
        	CLogger::LEVEL_ERROR,
        	'colourlife.core.common.models.CaiRedPacket.addRedPacker'
        			);
        	throw new Exception('员工不存在！');
        }
        $isTransaction = Yii::app()->db->getCurrentTransaction();//判断是否有用过事务
        $transaction = (!$isTransaction)?Yii::app()->db->beginTransaction():'';
        try {
            $balance = ($employee->getBalance()+$attr['sum']);
            $redPacket->setAttributes($attr);
            $redPacket->to_type = 0;
            /*
            if(!$redPacket->save() or !Employee::model()->updateByPk($employee->id,array('balance'=>$balance))){
            	$errors1=$redPacket->getErrors();
            	$errors2=$employee->getErrors();
            	Yii::log("添加彩管家饭票失败".json_encode($errors1)."==".json_encode($errors2),CLogger::LEVEL_ERROR,CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket.add');
                //save方法返回bool值，所以需要手动抛出异常
                throw new Exception( '添加彩管家饭票失败！' );
            }
            $redInfo['modle']=$redPacket;
            */


            if (!$redPacket->save() or !Employee::model()->updateByPk($employee->id,array('balance'=>$balance,'last_time' => time()))) {
		            	Yii::log(
		            	sprintf(
		            	'addRedPacker 明细或更新饭票失败: %s',
		            	$employee->id.'_'.$balance
		            	),
		            	CLogger::LEVEL_ERROR,
		            	'colourlife.core.common.models.CaiRedPacket.addRedPacker'
		            			);
		            	(!$isTransaction)?$transaction->rollback():'';
		            	return false;
                        //save方法返回bool值，所以需要手动抛出异常
                       // throw new Exception('消费饭票失败！');
            }
            // 转账交易不需要请求金融平台，只产生本地交易记录
            if($attr['from_type']!=Item::CAI_RED_PACKET_FROM_TYPE_CARRY_EMPLOYEE) {
                // 普通交易，请求金融平台
				if($attr['from_type'] == Item::CAI_RED_PACKET_FROM_TYPE_TIXIAN_FAIL_REFUND && $attr['to_type'] == Item::CAI_RED_PACKET_TO_TYPE_TIXIAN){
					$attr['sn'] = $attr['sn'].'-';
				}
                $ftransaction = $this->finance_transaction($attr);
            } else {
                // 转账交易，模拟transaction结果
                $ftransaction['payinfo'] = array('tno'=>'simulate');
            }
            if(!$ftransaction || !isset($ftransaction['payinfo']) || !isset($ftransaction['payinfo']['tno'])){
            	Yii::log(
            	sprintf(
            	'addRedPacker 消费饭票失败: %s',
            	json_encode($ftransaction)
            	),
            	CLogger::LEVEL_ERROR,
            	'colourlife.core.common.models.CaiRedPacket.addRedPacker'
            			);
            	(!$isTransaction)?$transaction->rollback():'';
            	return false;
                //throw new Exception('消费饭票失败！');
            }
             (!$isTransaction)?$transaction->commit():'';
            /*
            //TODO:kakatool 插入金融平台交易同步方法
            //只要不是转账,都认为是充值
            if($attr['from_type']!=Item::CAI_RED_PACKET_FROM_TYPE_CARRY_EMPLOYEE){
                FinanceSyncService::getInstance()->employeeRecharge($employee['oa_username'],$attr['sum'],$note);
            }
            */

	        Yii::log(
		        sprintf(
			        'addRedPacker finance transaction response: %s',
			        json_encode($ftransaction)
		        ),
		        CLogger::LEVEL_ERROR,
		        'colourlife.core.common.models.CaiRedPacket.addRedPacker'
	        );
            return true;
        } catch ( Exception $e ) {
        	Yii::log("try异常：".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket.add');
            (!$isTransaction)?$transaction->rollback():'';
        }
        return false;
    }

    /**
     * @param array $attr 数组元素包括employee_id(用户ID)、to_type(消费方式1or2)、sum(饭票金额)、
     * sn(订单sn:to_type=1or2)
     * @return bool
     * 消费饭票,
     */
    public function consumeRedPacker($attr=array()){
    	
    	Yii::log("consumeRedPacker彩管家饭票消费开始，".json_encode($attr),CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket');
        //关键参数不能缺失
        if(!isset($attr['employee_id']) or !isset($attr['to_type']) or !isset($attr['sum']) or !isset($attr['sn'])){
        	Yii::log("consumeRedPacker彩管家饭票有参数不存在，".json_encode($attr),CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket');
            return false;
        }
        if(empty($attr['employee_id']) or empty($attr['to_type']) or empty($attr['sum']) or empty($attr['sn'])){
        	Yii::log("consumeRedPacker彩管家饭票有参数为空，".json_encode($attr),CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket');
            return false;
        }

        // 忽略，交由金融平台判断
        /* //金额必须为数字
        if(!$this->checkMoney($attr['employee_id'],$attr['sum'])){
        	Yii::log("金额不是数字:".$attr['sum'],CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket');
            return false;
        } */

        switch($attr['to_type']){
            case Item::CAI_RED_PACKET_TO_TYPE_TIXIAN://彩管家饭票提现消费饭票
                $model = SN::findContentBySN($attr['sn']);
                $hasObj = empty($model)?false:true;
                $note = "订单【{$attr['sn']}】(彩管家饭票提现)消费饭票【{$attr['sum']}】元";
                $remark = empty($model)?"":$model->note;
                break;
            case Item::CAI_RED_PACKET_TO_TYPE_CARRY://给同事发饭票
                $model = SN::findContentBySN($attr['sn']);
                $hasObj = empty($model)?false:true;
                $note = "订单【{$attr['sn']}】(给同事发饭票)消费饭票【{$attr['sum']}】元";
                $remark = empty($model)?"":$model->note;
                break;
            case Item::CAI_RED_PACKET_TO_TYPE_COLOURLIFE://OA转账消费饭票(转彩之云)
                $model = SN::findContentBySN($attr['sn']);
                $hasObj = empty($model)?false:true;
                $note = "订单【{$attr['sn']}】(OA转账消费饭票)消费饭票【{$attr['sum']}】元";
                $remark = empty($model)?"":$model->note;
                break;
            case Item::CAI_RED_PACKET_TO_TYPE_ELICAI_TOUZI://投资E理财消费饭票
                $model = SN::findContentBySN($attr['sn']);
                $hasObj = empty($model)?false:true;
                $note = "订单【{$attr['sn']}】(投资E理财)消费饭票【{$attr['sum']}】元";
                $remark = "";
                break;
            case Item::CAI_RED_PACKET_TO_TYPE_SYSTEM_DEDUCT://扣回饭票
            	if (isset($attr['sn']) && $attr['sn'] == 'manager'){ //管理员
            		$hasObj = true;
            	}else {
            		$hasObj = false;
            	}
                $note = isset($attr['note']) ? $attr['note'] : "扣回饭票";
                $remark = isset($attr['remark']) ? $attr['remark'] : "";
                break;
            default;
                $note = "订单【{$attr['sn']}】消费饭票【{$attr['sum']}】元";;
                $remark = "";
                $hasObj = null;
        }
        // 内部充值处理
        if(strpos($attr['sn'], '113') === 0){
            $model = SN::findContentBySN($attr['sn']);
            $hasObj = empty($model)?false:true;
            $remark = isset($attr['remark']) ? $attr['remark'] : '';
        }
        //如果传入的sn找不到对象
        if(!$hasObj)return false;

        // $model->user_red_packet=Item::RED_PACKET_USED;//已使用饭票
        $attr['type'] = Item::RED_PACKET_TYPE_CONSUME;//设置属性为消费
        $attr['note'] = $note;//备注
        $attr['remark'] = $remark;//捎一句话
        $redPacket = new self();
        $employee = Employee::model()->findByPk($attr['employee_id']);
        if(!$employee){
        	Yii::log("consumeRedPacker报员工不存在错误，员工号为：".$attr['employee_id'],CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket');
        	throw new Exception('员工不存在！');
        }
        $isTransaction = Yii::app()->db->getCurrentTransaction();//判断是否有用过事务
        $transaction = (!$isTransaction)?Yii::app()->db->beginTransaction():'';
        try {
        	Yii::log("consumeRedPacker进入事务",CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket');
            $balance = ($employee->getBalance()-$attr['sum']);
            Yii::log("consumeRedPacker进入事务，员工余额为：".$balance,CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket');
            $redPacket->setAttributes($attr);
            $redPacket->from_type = 0;
            $redPacket->validate();
            if(!$redPacket->validate()){
                Yii::log("验证信息:".json_encode($redPacket->getErrors()).">>>".json_encode($redPacket->getErrors()),CLogger::LEVEL_INFO,'colourlife.core.cairedpacket');
            }
            $redPacket->validate();
            /*
            if(Employee::model()->updateByPk($employee->id,array('balance'=>$balance))){
                if(!$redPacket->save()  or !$model->save()){
                    //save方法返回bool值，所以需要手动抛出异常
                    throw new Exception('消费彩管家饭票失败！');
                }
            }else{
                throw new Exception('消费彩管家饭票失败！');return false;
            }
            */



            if (!$redPacket->save()) {
            	Yii::log("caiRedPacket保存明细失败:".$employee->id.'参数为:'.json_encode($attr),CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket');
                //throw new Exception('消费饭票失败！');
            	(!$isTransaction)?$transaction->rollback():'';
            	return false;
            } else if(!Employee::model()->updateByPk($employee->id, array('balance'=>$balance,'last_time' => time()))){
            	Yii::log("更新员工饭票失败:".$employee->id.'，金额为：'.$balance,CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket');
                //throw new Exception('消费饭票失败！');
            	(!$isTransaction)?$transaction->rollback():'';
            	return false;
            }
            // 转账交易不需要请求金融平台，只产生本地交易记录
            if($attr['to_type']!=Item::CAI_RED_PACKET_TO_TYPE_CARRY
                && $attr['to_type']!=Item::CAI_RED_PACKET_TO_TYPE_COLOURLIFE) {
                // 普通交易，请求金融平台
                $ftransaction = $this->finance_transaction($attr);
            } else {
                // 转账交易，模拟transaction结果
                $ftransaction['payinfo'] = array('tno'=>'simulate');
            }
            if(!$ftransaction || !isset($ftransaction['payinfo']) || !isset($ftransaction['payinfo']['tno'])){
            	Yii::log("转账交易失败:".json_encode($ftransaction),CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket');
                //throw new Exception('消费饭票失败！');
            	(!$isTransaction)?$transaction->rollback():'';
            	return false;
            }

            (!$isTransaction)?$transaction->commit():'';
            /*
            //TODO:kakatool 插入金融平台交易同步方法
            //只要不是提现,员工内转账,员工转账到用户,都认为是消费
            if($attr['to_type']!=Item::CAI_RED_PACKET_TO_TYPE_TIXIAN
                &&$attr['to_type']!=Item::CAI_RED_PACKET_TO_TYPE_CARRY
                &&$attr['to_type']!=Item::CAI_RED_PACKET_TO_TYPE_COLOURLIFE){
                //消费
                FinanceSyncService::getInstance()->employeeConsume($employee['oa_username'],$attr['sum'],$note);
            }
            else{
                //提现
                if($attr['to_type']==Item::CAI_RED_PACKET_TO_TYPE_TIXIAN){
                    FinanceSyncService::getInstance()->employeeCash($employee['oa_username'],$attr['sum'],$note);
                }

            }
            */
            return true;
        }catch(Exception $e) {
        	Yii::log("consumeRedPacker的try异常:".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.cairedpacket');
            (!$isTransaction)?$transaction->rollback():'';
        }
        return false;
    }

    /**
     * 请求金融平台交易接口
     * @param array $attr
     * @return  array
     */
    private function finance_transaction($attr=array()){
	    Yii::log(
		    sprintf(
			    'addRedPacker finance transaction: %s',
			    json_encode($attr)
		    ),
		    CLogger::LEVEL_ERROR,
		    'colourlife.core.common.models.CaiRedPacket.addRedPacker'
	    );
        $orgAccount = null;
        $destAccount = null;
        if(isset($attr['ftype']) && isset($attr['ftype']) != 6){
            // 彩之云地方饭票
            // TODO
        } else {
            // 彩管家全国饭票
            $r = FinanceMicroService::getInstance()->getEmployeePano();
            Yii::log("员工内部消费    " . json_encode($r));
            $pano = $r['pano'];
            $atid = $r['atid'];

            $employee = Employee::model()->findByPk($attr['employee_id']);
            if($attr['type']!=Item::RED_PACKET_TYPE_ACQUIRE){
                // 如果不是充值交易，获取支付用户金融平台账号
                $orgAccount = FinanceEmployeeRelateModel::model()->find('oa_username=:oa_username',array(':oa_username'=>$employee['oa_username']));
                $orgcano = $orgAccount['cano'];
                $orgatid = $atid;
                $destcano = $pano;
                $destatid = $atid;
            } else if($attr['type']!=Item::RED_PACKET_TYPE_CONSUME){
                // 如果不是充值交易，获取收款用户金融平台账号
                $destAccount = FinanceEmployeeRelateModel::model()->find('oa_username=:oa_username',array(':oa_username'=>$employee['oa_username']));
                $orgcano = $pano;
                $orgatid = $atid;
                $destcano = $destAccount['cano'];
                $destatid = $atid;
            }
        }

	    Yii::log(
		    sprintf(
			    'addRedPacker finance transaction dispatch: %s',
			    json_encode(array(
				    $attr['sum'],           //交易金额
				    $attr['note'],          //备注
				    $orgatid,                  //支付类型
				    $orgcano,               //支付账号
				    $destatid,                  //收款类型
				    $destcano,              //收款账号
				    $attr['note'],          //备注
				    '',                     //回调方法，快速交易不需要设置
				    $attr['sn']             //本地交易编号
			    ))
		    ),
		    CLogger::LEVEL_ERROR,
		    'colourlife.core.common.models.CaiRedPacket.addRedPacker'
	    );
	    if (isset($attr['remark']) && !empty($attr['remark'])){
	    	$remark = $attr['remark'];
	    }else {
	    	$remark = $attr['note'];
	    }
        //执行交易操作
        return FinanceMicroService::getInstance()->fastTransaction(
            $attr['sum'],           //交易金额
            $remark,          //备注
            $orgatid,                  //支付类型
            $orgcano,               //支付账号
            $destatid,                  //收款类型
            $destcano,              //收款账号
            $attr['note'],          //备注
            '',                     //回调方法，快速交易不需要设置
            $attr['sn']             //本地交易编号
        );

    }
    /**
     * @param $employee_id 用户ID
     * @param $amount   需要支出的金额
     * @return bool
     * 判断需要支出的金额是否超出用户余额
     */
    public function checkMoney($employee_id,$amount){
        if(empty($employee_id) or empty($amount)){
            return false;
        }
        if(!is_numeric($amount)){
            return false;
        }
        $employee = Employee::model()->findByPk($employee_id);

		if (empty($employee)) {
			return false;
		}
		if ($amount <= $employee->getBalance()) {
			return true;
		}
		return false;
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;
		$criteria->with[] = "employee";
		$criteria->compare("employee.username", $this->username, true);
		$criteria->compare("employee.name", $this->name, true);
		$criteria->compare("employee.mobile", $this->mobile, true);
		$criteria->compare('`t`.type', $this->type);
		$criteria->compare('`t`.employee_id', $this->employee_id);
		$criteria->compare('`t`.from_type', $this->from_type);
		$criteria->compare('`t`.to_type', $this->to_type);
		$criteria->compare('`t`.sum', $this->sum);
		$criteria->compare('`t`.create_time', $this->create_time);
		$criteria->compare('`t`.note', $this->note, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => '`t`.create_time DESC'
			),
		));
	}


	public function list_search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria = new CDbCriteria;
		//选择的组织架构ID
//        if (!empty($this->branch_id)) {
//            $criteria->distinct = true;
//            $criteria->join = ' LEFT OUTER JOIN employee `employee` on `t`.employee_id=`employee`.id LEFT OUTER JOIN employee_branch_relation ebr on ebr.employee_id=`employee`.id';
//            $branch = Branch::model()->findByPk($this->branch_id);
//            if (!empty($branch)) {
//                $criteria->addInCondition('ebr.branch_id', $branch->getChildrenIdsAndSelf());
//            }
//            if (!empty($this->username) || !empty($this->name) || !empty($this->mobile)) {
//                $criteria->compare("`employee`.username",$this->username,true);
//                $criteria->compare("`employee`.name",$this->name,true);
//                $criteria->compare("`employee`.mobile",$this->mobile,true);
//            }
//        }else{
//            if (!empty($this->username) || !empty($this->name) || !empty($this->mobile)) {
//                $criteria->with[]="employee";
//                $criteria->compare("`employee`.username",$this->username,true);
//                $criteria->compare("`employee`.name",$this->name,true);
//                $criteria->compare("`employee`.mobile",$this->mobile,true);
//            }
//        }

//      ICE 没了employee表所以接入ice  上面的大if思路 1选了组织架构，先按照组织机构搜索$employee_ids，如果选择 名字，电话，usename 就把employee_ids清空，按照那三个搜索。2没选，就直接搜索了。
		//选择的组织架构ID
		if (!empty($this->branch_id)) {
			$branchEmployee_ids = array();
			$branch = Branch::model()->findByPk($this->branch_id);
			$branch_ids = $branch->getChildrenIdsAndSelf();
			$ebrcriteria = new CDbCriteria;
			$ebrcriteria->select = 'employee_id';
			$ebrcriteria->compare('branch_id', $branch_ids);
			$ebrs = EmployeeBranchRelation::model()->findAll($ebrcriteria);
			foreach ($ebrs as $ebr) {
				$branchEmployee_ids[] = $ebr->employee_id;
			}

	//      ICE 这个搜索就是为了让查询更准确，而不是用表里面的什么，所以按照以下接入ice
			$employee_ids = array();

			if ($this->name != '') {
				$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->name));
				if (!empty($employees)) {
					foreach ($employees as $employee) {
						$employee_ids[] = $employee['czyId'];
					}
				}
			}
			if ($this->username != '') {
				$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->username));
				if (!empty($employees)) {
					foreach ($employees as $employee) {
						$employee_ids[] = $employee['czyId'];
					}
				}
			}
			if ($this->mobile != '') {
				$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->mobile));
				if (!empty($employees)) {
					foreach ($employees as $employee) {
						$employee_ids[] = $employee['czyId'];
					}
				}
			}

			if (!empty($employee_ids)) {
//				选了 名字，电话，username 取交集
				$employee_ids = array_intersect($employee_ids, $branchEmployee_ids);
				$criteria->addInCondition('t.employee_id', array_unique($employee_ids));
			}else{
				$criteria->addInCondition('t.employee_id', $branchEmployee_ids);
			}

		} else {
//          没选组织架构
			$employee_ids = array();

			if ($this->name != '') {
				$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->name));
				if (!empty($employees)) {
					foreach ($employees as $employee) {
						$employee_ids[] = $employee['czyId'];
					}
				}
			}
			if ($this->username != '') {
				$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->username));
				if (!empty($employees)) {
					foreach ($employees as $employee) {
						$employee_ids[] = $employee['czyId'];
					}
				}
			}
			if ($this->mobile != '') {
				$employees = ICEEmployee::model()->ICEGetAccountSearch(array('keyword' => $this->mobile));
				if (!empty($employees)) {
					foreach ($employees as $employee) {
						$employee_ids[] = $employee['czyId'];
					}
				}
			}

			if (!empty($employee_ids)) {
				$criteria->addInCondition('t.employee_id', array_unique($employee_ids));
			}

		}


		$criteria->compare('`t`.type', $this->type);
		$criteria->compare('`t`.employee_id', $this->employee_id);
		$criteria->compare('`t`.from_type', $this->from_type);
		$criteria->compare('`t`.to_type', $this->to_type);
		$criteria->compare('`t`.sum', $this->sum);
		$criteria->compare('`t`.create_time', $this->create_time);
		$criteria->compare('`t`.note', $this->note, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => '`t`.create_time DESC'
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

	public function getTypeName()
	{
		if ($this->type == Item::RED_PACKET_TYPE_CONSUME) {//消费饭票
			if ($this->to_type == Item::CAI_RED_PACKET_TO_TYPE_TIXIAN) {
				return "提现支出饭票";
			} else if ($this->to_type == Item::CAI_RED_PACKET_TO_TYPE_CARRY) {
				return "给同事发饭票";
			} else if ($this->to_type == Item::CAI_RED_PACKET_TO_TYPE_COLOURLIFE) {
				return "OA转账支出饭票";
			} else if ($this->to_type == Item::CAI_RED_PACKET_TO_TYPE_ELICAI_TOUZI) {
				return "投资E理财消费饭票";
			} else if ($this->to_type == Item::CAI_RED_PACKET_TO_TYPE_SYSTEM_DEDUCT) {
				return "系统误发回收饭票";
			}
		} else if ($this->type == Item::RED_PACKET_TYPE_ACQUIRE) {//获得饭票
			if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_GOODJIXAO_AWARD) {//绩效奖励获得彩管家饭票
				return "绩效奖励收入饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_LOOK_EMONEY_AWARD) {//找E理财BUG奖励饭票
				return "找E理财BUG收入饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_TIXIAN_FAIL_REFUND) {//提现失败退还饭票
				return "提现失败退还饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_CARRY_EMPLOYEE) {//OA转账获得饭票
				return "获得同事饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_MANAGER_AWARD) {//获得总经理奖励饭票
				return "总经理奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_PROPERTY_ACTIVITY_AWARD) {//冲抵活动奖励饭票
				return "彩富人生奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_SPRING_AWARD) {//加班奖励饭票
				return "加班奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_CAI_ZHI_JIA_AWARD) {//彩之家年终奖励饭票
				return "年终奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_38_AWARD) {//3.8节日饭票
				return "3.8节日饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_FEBRUARY_FUWU_AWARD) {//服务之星饭票
				return "二月服务之星饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_DEC_XINFU_AWARD) {//2014年12月份幸福中国行游园奖励
				return "幸福中国行游园饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_NIAN_ZHONG_AWARD) {//年终奖励饭票
				return "年终奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_E_LICAI_TUIJIAN_AWARD) {//E理财推荐奖励饭票
				return "E理财推荐奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_JITI_JIANGJIN_AWARD) {//E理财推荐奖励饭票
				return "集体奖金";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_CAIFU_TICHEN_JIANGLI) {//彩富人生推荐提成奖励
				return "彩富人生推荐提成奖励";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_ELICAI_DINGQI_TICHEN_JIANGLI) {//E理财定期提成奖励饭票
				return "E理财定期提成奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_RXH_LICAI_TICHEN_JIANGLI) {//荣信汇理财提成奖励饭票
				return "荣信汇理财提成奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_MOST_BEAUTIFUL_SMILE) {//最美微笑奖励饭票
				return "最美微笑奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_TOUZI_DAOQI_AWARD) {//彩管家饭票理财到期返还饭票
				return "饭票理财到期返还饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_HUAMEI_CAIGUO_JIEKUANG) {//华美达酒店采购借款奖励饭票
				return "华美达酒店采购借款奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_ACCOUNT_MANAGER_REWARD) {//客户经理试点方案奖励饭票
				return "客户经理试点方案奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_CYBGZ_ACTIVITY) {//“我的空间我做主”创意办公桌奖
				return "“创意办公桌奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_EXPERT_IN_CIVIL) {//高手在民间，干货征集令
				return "“干货征集令奖励饭票";
			} else if ($this->from_type == Item::CAI_RED_PACKET_FROM_TYPE_REWARDS_JIANGLI) {//提成发放系统奖励红包
				return "提成发放系统奖励饭票";
			}
		} else {
			return "";
		}
	}

}
