<?php

/**
 * This is the model class for table "cai_redpacket_carry".
 *
 * The followings are the available columns in table 'cai_redpacket_carry':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $receiver_id
 * @property integer $type
 * @property string $amount
 * @property integer $is_received
 * @property integer $state
 * @property integer $is_deleted
 * @property integer $create_time
 * @property string $note
 */
class RedPacketCarry extends CActiveRecord
{	
	public $modelName = '彩之云饭票转账';
    public $customerName;
    public $customerZH;
    public $customerMobile;
    public $startTime;
    public $endTime;
    public $receiverName;
    public $receiverUserName;
    public $receiverMobile;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'red_packet_carry';
	}

    static $carryType = array(//0=>OA转账 1=>转账彩之于云
        // 0=>"转账到OA",
        1=>"转账到彩之云",
    );

    static $carryReceiver = array(//0=>未收到 1=>已到账
        0=>"未收到",
        1=>"已到账",
    );

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sn, customer_id, receiver_id, amount', 'required'),
            array('customer_id, receiver_id, type, is_received, state, is_deleted, create_time', 'numerical', 'integerOnly'=>true),
            array('amount', 'length', 'max'=>10),
            array('note', 'safe'),
            array('sn', 'length', 'max'=>32),
            array('type', 'in', 'range'=>array(1)),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sn, customer_id, receiver_id, type, amount, is_received, state, is_deleted, create_time, note, customerName, customerZH, customerMobile, startTime, endTime, receiverName, receiverUserName, receiverMobile', 'safe', 'on'=>'search'),
            array('id, sn, customer_id, receiver_id, type, amount, is_received, state, is_deleted, create_time, note, customerName, customerZH, customerMobile, startTime, endTime, receiverName, receiverUserName, receiverMobile', 'safe', 'on'=>'report_search'),
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
            'sender' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            // 'receiver' => array(self::BELONGS_TO, 'Employee', 'receiver_id'),
            'receiver' => array(self::BELONGS_TO, 'Customer', 'receiver_id'),
            
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => 'SN号',
			'customer_id' => '转账人ID',
			'receiver_id' => '接受人ID',
			'type' => '类型',
			'amount' => '转账金额',
			'is_received' => '接收',
			'state' => '状态',
			'is_deleted' => '删除',
			'create_time' => '创建时间',
			'note' => '备注',
            'customerName' => '转账人',
            'customerZH' => '转账人账号',
            'customerMobile' => '转账人手机',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'receiverName'=>'接收人',
            'receiverUserName'=>'接收人账号',
            'receiverMobile'=>'接收人手机',
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

        $criteria->compare('`t`.id',$this->id);
        $criteria->compare('`t`.sn',$this->sn,true);
        $criteria->compare('`t`.customer_id',$this->customer_id);
        $criteria->compare('`t`.receiver_id',$this->receiver_id);
        $criteria->compare('`t`.type',$this->type);
        $criteria->compare('`t`.amount',$this->amount,true);
        $criteria->compare('`t`.is_received',$this->is_received);
        $criteria->compare('`t`.state',$this->state);
        $criteria->compare('`t`.is_deleted',$this->is_deleted);
        // $criteria->compare('`t`.create_time',$this->create_time);
        $criteria->compare('`t`.note',$this->note,true);
        if ($this->startTime!='') {
            $criteria->compare("`t`.create_time", ">= ".strtotime($this->startTime." 00:00:00"));
        }
        if ($this->endTime!='') {
            $criteria->compare("`t`.create_time", "<= ".strtotime($this->endTime." 23:59:59"));
        }
        $criteria->with[] = 'sender';
        if ($this->customerName!= '') {
            $criteria->compare("sender.name", $this->customerName);
        }

        if ($this->customerMobile!= '') {            
            $criteria->compare("sender.mobile", $this->customerMobile);
        }

        if ($this->customerZH!= '') {
            $criteria->compare("sender.username", $this->customerZH);
        }
        // $criteria->order = "`t`.create_time DESC ";
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }




    public function report_search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;
        if (isset($_GET['RedPacketCarry']) && !empty($_GET['RedPacketCarry'])) {
            $_SESSION['RedPacketCarry'] = array();
            $_SESSION['RedPacketCarry'] = $_GET['RedPacketCarry'];
        }
        if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
            if (isset($_SESSION['RedPacketCarry']) && !empty($_SESSION['RedPacketCarry'])) {
                foreach ($_SESSION['RedPacketCarry'] as $key => $val) {
                    if ($val != "") {
                        $this->$key = $val;
                    }
                }
            }
        }
        $criteria->compare('`t`.id',$this->id);
        $criteria->compare('`t`.sn',$this->sn,true);
        $criteria->compare('`t`.customer_id',$this->customer_id);
        $criteria->compare('`t`.receiver_id',$this->receiver_id);
        $criteria->compare('`t`.type',$this->type);
        $criteria->compare('`t`.amount',$this->amount,true);
        $criteria->compare('`t`.is_received',$this->is_received);
        $criteria->compare('`t`.state',$this->state);
        $criteria->compare('`t`.is_deleted',$this->is_deleted);
        $criteria->compare('`t`.create_time',$this->create_time);
        $criteria->compare('`t`.note',$this->note,true);
        if ($this->startTime!='') {
            $criteria->compare("`t`.create_time", ">= ".strtotime($this->startTime." 00:00:00"));
        }
        if ($this->endTime!='') {
            $criteria->compare("`t`.create_time", "<= ".strtotime($this->endTime." 23:59:59"));
        }
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }


     public function getCustomerName()
    {
        return empty($this->sender)?'':$this->sender->name;
    }


    public function getCustomerZH()
    {
        return empty($this->sender)?'':$this->sender->username;
    }


    public function getCustomerMobile()
    {
        return empty($this->sender)?'':$this->sender->mobile;
    }



    public function getReceiverName()
    {   
        return empty($this->receiver)?'':$this->receiver->name;
    }


    public function getReceiverUserName()
    {
        return empty($this->receiver)?'':$this->receiver->username;
    }


    public function getReceiverMobile()
    {
        return empty($this->receiver)?'':$this->receiver->mobile;
    }




    public function getTypeName($html = false)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$carryType[$this->type];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }

    public function getReceiverStatusName($html = false)
    {   
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$carryReceiver[$this->is_received];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }


    public function getCreateTime()
    {
        return date("Y-m-d H:i:s", $this->create_time);
    }


    public function getTypeList(){
        $list = array();
        $list = self::$carryType;
        return array_merge(array(''=>'全部'), $list);
        
    }


    public function getReceiverList(){
        $list = array();
        $list = self::$carryReceiver;
        return array_merge(array(''=>'全部'), $list);
        
    }


    /**
     * 创建转账订单
     * 参数说明：
     * @feeAttr:RedPacketCarry模型的属性集合
     * 返回值 boolean, true:创建成功，false:创建失败
     * */
    public static function createRedPacketCarryOrder($feeAttr)
    {
        //判断参数
        if (empty($feeAttr)) {
            RedPacketCarry::model()->addError('id', "饭票转账失败！");
            return false;
        }

        //创建我们的订单记录及记录
        $other = new RedPacketCarry();
        $other->attributes = $feeAttr;
        if($feeAttr["type"]==1){//转账到彩之云
            //消费饭票
            $items_to = array(
                'customer_id' => $feeAttr["customer_id"],//转账人
                'to_type' => Item::RED_PACKET_TO_TYPE_CARRY,//彩之云转账消费饭票
                'sum' => $feeAttr["amount"],//饭票金额,
                'sn' => $feeAttr["sn"],
            );

            //获得饭票
    		$items_from = array(
                'customer_id' => $feeAttr["receiver_id"],//用户的ID(customer)
                'from_type' => Item::RED_PACKET_FROM_TYPE_CARRY,//彩之云转账获得饭票
                'sum' => $feeAttr["amount"],//饭票金额,
                'sn' => $feeAttr["sn"],
    		);
    	}else{
    		return false;
    	}

        $isTransaction = Yii::app()->db->getCurrentTransaction();//判断是否有用过事务
        $transaction = (!$isTransaction) ? Yii::app()->db->beginTransaction() : '';

        $redPacked = new RedPacket();
        //把对应的邀请记录设置成已发送饭票
        try{
        	Yii::log("红包转账1".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
        	if(!$other->validate()||!$other->save()){
        		Yii::log("红包转账2验证失败或保存失败".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
	        	RedPacketCarry::model()->addError('id', "饭票转账失败！");
	        	return false;
        	}
            // 交易成功，保存本地交易记录
        	$ret_to=$redPacked->consumeRedPacker($items_to);
        	if(!$ret_to){
            	Yii::log("饭票转账失败,返回ret_to结果：".$ret_to.",参数列表：".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
            	//@$other->delete();
                (!$isTransaction) ? $transaction->rollback() : '';
            	return false;
            }
            $ret_from=$redPacked->addRedPacker($items_from);
        	if(!$ret_from){
            	Yii::log("饭票转账失败,返回ret_from结果：".$ret_from.",参数列表".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
            	//@$other->delete();
                (!$isTransaction) ? $transaction->rollback() : '';
            	return false;
        	}

    		$updateSql = "update red_packet_carry set is_received = 1 where id=".$other->id;
            $result = Yii::app()->db->createCommand($updateSql)->execute();
            if($result){
            	Yii::log("饭票转账更新red_packet_carry成功,结果：".$result.",记录ID".$other->id.",参数列表".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_INFO,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
            	// 发起交易
            	$ftransaction = self::transaction($items_to, $items_from, $feeAttr['note']);
            	if(!$ftransaction || !isset($ftransaction['payinfo']) || !isset($ftransaction['payinfo']['tno'])){
            		Yii::log("红包交易失败,ftransaction结果：".json_encode($ftransaction).'记录ID:'.$other->id,CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
            		//throw new Exception('饭票转账失败！');
                    (!$isTransaction) ? $transaction->rollback() : '';
            		return false;
            	}
                (!$isTransaction) ? $transaction->commit() : '';

                /*
				//TODO:kakatool 插入金融平台交易同步方法
				//转账,用户转账给用户
				if($feeAttr["type"]==1){
					FinanceSyncService::getInstance()->customerTransfer($items_to['customer_id'],$items_from['customer_id'],$feeAttr["amount"]);
				}
				*/
    			return true;
            }else{
            	Yii::log("饭票转账更新red_packet_carry失败,结果：".$result.",参数列表".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
            	//@$other->delete();
                (!$isTransaction) ? $transaction->rollback() : '';
            	return false;
            }
        }catch(Exception $e) {
            Yii::log("try异常：".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
           // @$other->delete();
            (!$isTransaction) ? $transaction->rollback() : '';   // 在异常处理中回滚
            return false;
        }

     }
     
     /**
      * 扣回饭票转账
      * 参数说明：
      * @feeAttr:RedPacketCarry模型的属性集合
      * 返回值 boolean, true:创建成功，false:创建失败
      * */
     public static function createRedPacketDebitOrder($feeAttr)
     {
     	//判断参数
     	if (empty($feeAttr)) {
     		RedPacketCarry::model()->addError('id', "追回饭票失败！");
     		return false;
     	}
     
     	//创建我们的订单记录及记录
     	$other = new RedPacketCarry();
     	$other->attributes = $feeAttr;
     	//消费饭票
     	$items_to = array(
     			'customer_id' => $feeAttr["customer_id"],//转账人
     			'to_type' => Item::RED_PACKET_TO_TYPE_RETURN_FP,//追回饭票
     			'sum' => $feeAttr["amount"],//饭票金额,
     			'sn' => $feeAttr["sn"],
     			'note' => $feeAttr["note"]
     	);
     	 
     	//获得饭票
     	$items_from = array(
     			'customer_id' => $feeAttr["receiver_id"],//用户的ID(customer)
     			'from_type' => Item::RED_PACKET_FROM_TYPE_REFUND_FP,//追回获得饭票
     			'sum' => $feeAttr["amount"],//饭票金额,
     			'sn' => $feeAttr["sn"],
     			'note' => $feeAttr["from_note"]
     	);
     
     	$isTransaction = Yii::app()->db->getCurrentTransaction();//判断是否有用过事务
     	$transaction = (!$isTransaction) ? Yii::app()->db->beginTransaction() : '';
     
     	$redPacked = new RedPacket();
     	//把对应的邀请记录设置成已发送饭票
     	try{
     		Yii::log("追回饭票1".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
     		if(!$other->validate()||!$other->save()){
     			Yii::log("追回饭票2验证失败或保存失败".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
     			RedPacketCarry::model()->addError('id', "追回饭票失败！");
     			return false;
     		}
     		// 交易成功，保存本地交易记录
     		$ret_to=$redPacked->consumeRedPacker($items_to);
     		if(!$ret_to){
     			Yii::log("追回饭票失败,返回ret_to结果：".$ret_to.",参数列表：".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
     			//@$other->delete();
     			(!$isTransaction) ? $transaction->rollback() : '';
     			return false;
     		}
     		$ret_from=$redPacked->addRedPacker($items_from);
     		if(!$ret_from){
     			Yii::log("追回饭票失败,返回ret_from结果：".$ret_from.",参数列表".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
     			//@$other->delete();
     			(!$isTransaction) ? $transaction->rollback() : '';
     			return false;
     		}
     
     		$updateSql = "update red_packet_carry set is_received = 1 where id=".$other->id;
     		$result = Yii::app()->db->createCommand($updateSql)->execute();
     		if($result){
     			Yii::log("追回饭票更新red_packet_carry成功,结果：".$result.",记录ID".$other->id.",参数列表".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_INFO,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
     			// 发起交易
     			$ftransaction = self::transaction($items_to, $items_from, $feeAttr['note']);
     			if(!$ftransaction || !isset($ftransaction['payinfo']) || !isset($ftransaction['payinfo']['tno'])){
     				Yii::log("追回饭票失败,ftransaction结果：".json_encode($ftransaction).'记录ID:'.$other->id,CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
     				//throw new Exception('饭票转账失败！');
     				(!$isTransaction) ? $transaction->rollback() : '';
     				return false;
     			}
     			(!$isTransaction) ? $transaction->commit() : '';
     
     			/*
     				//TODO:kakatool 插入金融平台交易同步方法
     			//转账,用户转账给用户
     			if($feeAttr["type"]==1){
     			FinanceSyncService::getInstance()->customerTransfer($items_to['customer_id'],$items_from['customer_id'],$feeAttr["amount"]);
     			}
     			*/
     			return true;
     		}else{
     			Yii::log("追回饭票更新red_packet_carry失败,结果：".$result.",参数列表".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
     			//@$other->delete();
     			(!$isTransaction) ? $transaction->rollback() : '';
     			return false;
     		}
     	}catch(Exception $e) {
     		Yii::log("try异常：".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.redPacketCarryOrder');
     		// @$other->delete();
     		(!$isTransaction) ? $transaction->rollback() : '';   // 在异常处理中回滚
     		return false;
     	}
     
     }

    /**
     * 请求金融平台交易接口
     * @param array $attr
     * @return  array
     */
    private static function transaction($items_to, $items_from, $note){
        $orgAccount = null;
        $destAccount = null;
        $fixedorgmoney = 0;
        if(isset($items_from['is_local']) && isset($items_to['is_local']) && isset($items_from['is_local']) == 1 && isset($items_to['is_local']) == 1){
            // 彩之云地方饭票
            // TODO
        	$orgAtid = $items_to['org_atid'];
        	$orgPano = $items_to['org_pano'];
        	$destAtid = isset($items_from['dest_atid']) ? $items_from['dest_atid']: FinanceMicroService::getInstance()->getCustomerPano()['atid'];
        	$destPano = isset($items_from['dest_pano']) ? $items_from['dest_pano']: FinanceMicroService::getInstance()->getCustomerPano()['pano'];
        	$fixedorgmoney = isset($items_to['fixedorgmoney']) ? $items_to['fixedorgmoney']: 0;
            Yii::log('地方饭票');
        } else {
        	// 彩之云全国饭票
        	$orgAtid = FinanceMicroService::getInstance()->getCustomerPano()['atid'];
        	$orgPano = FinanceMicroService::getInstance()->getCustomerPano()['pano'];
        	$destAtid = $orgAtid;
        	$destPano = $orgPano;
        }
        $orgAccount = FinanceCustomerRelateModel::model()->find('customer_id=:customer_id and pano=:pano and atid=:atid',array(':customer_id'=>$items_to['customer_id'],':pano'=>$orgPano,':atid'=>$orgAtid));
        $destAccount = FinanceCustomerRelateModel::model()->find('customer_id=:customer_id and pano=:pano and atid=:atid',array(':customer_id'=>$items_from['customer_id'],':pano'=>$destPano,':atid'=>$destAtid));
        if(!$orgAccount || !$destAccount){
        	return false;
        }
        //执行交易操作
        Yii::log("transaction 4\n" . json_encode(array(
                'amount' => $items_to['sum'],           //交易金额
                'note' => $note,                     //备注
                'orgatid' => $orgAtid,    //支付类型
                'orgaccountno' => $orgAccount['cano'],     //支付账号
                'destatid' => $destAtid,   //收款类型
                'destaccountno' => $destAccount['cano'],    //收款账号
                'detail' => $note,                   //备注
                'callback' => '',                     //回调方法，快速交易不需要设置
                'orderno' => $items_to['sn']
            )),CLogger::LEVEL_INFO,'colourlife.core.Transaction');
        return FinanceMicroService::getInstance()->fastTransaction(
            $items_to['sum'],           //交易金额
            $note,                     //备注
            $orgAtid,    //支付类型
            $orgAccount['cano'],     //支付账号
            $destAtid,   //收款类型
            $destAccount['cano'],    //收款账号
            $note,                   //备注
            '',                     //回调方法，快速交易不需要设置
            $items_to['sn'],             //本地交易编号
            $fixedorgmoney  //固定交易
        );

    }


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RedPacketCarry the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 转账操作
	 * @param unknown $customerID  转出人账号
	 * @param unknown $receiverID  接收人账号
	 * @param unknown $amount  转账金额
	 * @param unknown $type 类型(1转账到彩之云账号)
	 * @param string $note  备注
	 * @throws CHttpException
	 */
	public function customerTransferAccounts($customerID,$receiverID,$amount,$type,$cmobile,$note='',$isLimit=false){
		
		if (empty($customerID)||empty($cmobile)){
			return array('status'=>0,'msg'=>'用户ID或手机号不能为空！');
		}
		if (empty($receiverID)){
			return array('status'=>0,'msg'=>'接受人ID不能为空！');
		}
		if (empty($amount)){
			return array('status'=>0,'msg'=>'转账金额不能为空！');
		}
		if (empty($type)&&$type!==0) {
			return array('status'=>0,'msg'=>'类型不能为空！');
		}
		if ( $type == 1&& $receiverID==$customerID) {
			return array('status'=>0,'msg'=>'不能给自己转账！');
		}
		$model = new RedPacketCarry;
		$sn = SN::initByRedPacketCarry()->sn;
		//是否限制
		if ($isLimit){
			$checkResult=$this->checkSendAndGetPacket($customerID,$receiverID, $amount,$cmobile);
			if ($checkResult<=0){
				return array('status'=>0,'msg'=>'不满足发送或接收红包条件！');
			}
		}
		$orderData['receiver_id']=$receiverID;
		$orderData['type']=$type;
		$orderData["sn"] = $sn;
		$orderData["customer_id"] = $customerID;
		$orderData["note"] = !empty($note)?$note:"from_android";
		$orderData["amount"] = !empty($amount)?F::priceFormat($amount):0.00;
		if($orderData['amount']>0 && $model->createRedPacketCarryOrder($orderData)){
			$other = SN::findContentBySN($sn);
			return array('status'=>1,'msg'=>$sn);
		}else if($orderData['amount']<=0 || !$model->createRedPacketCarryOrder($orderData)){
			return array('status'=>0,'msg'=>'饭票转账失败！');
		}
	}
	/**
	 * 验证发送红包和接受红包
	 * @param $receiver_id
	 * @param $amount
	 * @return bool
	 * @throws CHttpException
	 */
	protected function checkSendAndGetPacket($customer_id,$receiver_id, $amount,$cmobile)
	{
		if (empty($customer_id)||empty($receiver_id)||empty($amount)||empty($cmobile)){
			return false;
		}
		$maxTimes = 5;
		$maxValue = 5000;
	
		$startTime = mktime(0,0,0);
		$endTime = mktime(23,59,59);
	
		// 白名单
		/**
		 *  add 2016-05-24
		 *  1、活动账户 20000000005
		*/
		$whiteList = array('20000000005');
		if (in_array($cmobile, $whiteList))
			return true;
	
		if ($amount > $maxValue){
			return -1;//已达到当天赠送金额上限
		} 
		$sendResult = Yii::app()->db->createCommand()
		->select('COUNT(id) AS num, SUM(sum) AS `value`')
		->from('red_packet')
		->where('customer_id=:customer_id AND to_type=:to_type AND create_time BETWEEN :startTime AND :endTime', array(':customer_id'=>$customer_id,':to_type'=>Item::RED_PACKET_TO_TYPE_CARRY, ':startTime'=>$startTime, ':endTime'=>$endTime))
		->queryRow();
	
		if ( $sendResult['num'] >= $maxTimes){
			return -2;//已达到当天赠送个数上限
		}
		if ( ($sendResult['value'] + $amount) > $maxValue ){
			return -3;//已达到当天赠送金额上限
		}
		$GetResult = Yii::app()->db->createCommand()
		->select('COUNT(id) AS num, SUM(sum) AS `value`')
		->from('red_packet')
		->where('customer_id=:customer_id AND from_type=:from_type AND create_time BETWEEN :startTime AND :endTime', array(':customer_id'=>$receiver_id,':from_type'=>Item::RED_PACKET_FROM_TYPE_CARRY, ':startTime'=>$startTime, ':endTime'=>$endTime))
		->queryRow();
	
		if ( $GetResult['num'] >= $maxTimes){
			return -4;//已达到接收人当天接受个数上限
		}
		if ( ($GetResult['value'] + $amount) > $maxValue ){
			return -5;//已达到接收人当天接受金额上限
		}
		return true;
	}
	
	/**
	 * 创建地方饭票转全国饭票的转账订单
	 * 参数说明：
	 * @feeAttr:RedPacketCarry模型的属性集合
	 * 返回值 boolean, true:创建成功，false:创建失败
	 * */
	public static function createLocalRedPacketCarryOrder($feeAttr)
	{
		//判断参数
		if (empty($feeAttr)) {
			RedPacketCarry::model()->addError('id', "饭票转账失败！");
			return false;
		}
	
		//创建我们的订单记录及记录
		$other = new RedPacketCarry();
		$other->attributes = $feeAttr;
		//消费饭票
		$items_to = array(
				'customer_id' => $feeAttr["customer_id"],//转账人
				'to_type' => Item::RED_PACKET_TO_TYPE_CARRY,//彩之云转账消费饭票
				'sum' => $feeAttr["amount"],//饭票金额,
				'sn' => $feeAttr["sn"],
				'org_pano' => $feeAttr['pano'],
				'org_atid' => $feeAttr['atid'],
				'rate' => $feeAttr['rate'],
				'real_amount' => $feeAttr['real_amount'],
				'is_local' => 1,
				'fixedorgmoney' => 1
		);

		//获得饭票
		$items_from = array(
				'customer_id' => $feeAttr["receiver_id"],//用户的ID(customer)
				'from_type' => Item::RED_PACKET_FROM_TYPE_CARRY,//彩之云转账获得饭票
				'sum' => $feeAttr["real_amount"],//饭票金额,
				'sn' => $feeAttr["sn"],
				'is_local' => 1,
		);
		$isTransaction = Yii::app()->db->getCurrentTransaction();//判断是否有用过事务
		$transaction = (!$isTransaction) ? Yii::app()->db->beginTransaction() : '';
	
		$redPacked = new RedPacket();
		//把对应的邀请记录设置成已发送饭票
		try{
			Yii::log("地方饭票转全国饭票1".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.createLocalRedPacketCarryOrder');
			if(!$other->validate()||!$other->save()){
				Yii::log("地方饭票转全国饭票2验证失败或保存失败".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.createLocalRedPacketCarryOrder');
				RedPacketCarry::model()->addError('id', "饭票转账失败！");
				return false;
			}
			// 交易成功，保存本地交易记录
			$ret_to=$redPacked->consumeRedPacker($items_to);
			if(!$ret_to){
				Yii::log("地方饭票转全国饭票失败,返回ret_to结果：".$ret_to.",参数列表：".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.createLocalRedPacketCarryOrder');
				//@$other->delete();
				(!$isTransaction) ? $transaction->rollback() : '';
				return false;
			}
			$ret_from=$redPacked->addRedPacker($items_from);
			if(!$ret_from){
				Yii::log("地方饭票转全国饭票,返回ret_from结果：".$ret_from.",参数列表".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.createLocalRedPacketCarryOrder');
				//@$other->delete();
				(!$isTransaction) ? $transaction->rollback() : '';
				return false;
			}
	
			$updateSql = "update red_packet_carry set is_received = 1 where id=".$other->id;
			$result = Yii::app()->db->createCommand($updateSql)->execute();
			if($result){
				Yii::log("地方饭票转全国饭票更新red_packet_carry成功,结果：".$result.",记录ID".$other->id.",参数列表".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_INFO,'colourlife.core.RedPacketCarry.createLocalRedPacketCarryOrder');
				// 发起交易
				$ftransaction = self::transaction($items_to, $items_from, $feeAttr['note']);
				if(!$ftransaction || !isset($ftransaction['payinfo']) || !isset($ftransaction['payinfo']['tno'])){
					Yii::log("地方饭票转全国饭票交易失败,ftransaction结果：".json_encode($ftransaction).'记录ID:'.$other->id,CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.createLocalRedPacketCarryOrder');
					//throw new Exception('饭票转账失败！');
					(!$isTransaction) ? $transaction->rollback() : '';
					return false;
				}
				(!$isTransaction) ? $transaction->commit() : '';
	
				/*
					//TODO:kakatool 插入金融平台交易同步方法
				//转账,用户转账给用户
				if($feeAttr["type"]==1){
				FinanceSyncService::getInstance()->customerTransfer($items_to['customer_id'],$items_from['customer_id'],$feeAttr["amount"]);
				}
				*/
				return true;
			}else{
				Yii::log("地方饭票转全国饭票更新red_packet_carry失败,结果：".$result.",参数列表".json_encode($items_to).">>>>".json_encode($items_from),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.createLocalRedPacketCarryOrder');
				//@$other->delete();
				(!$isTransaction) ? $transaction->rollback() : '';
				return false;
			}
		}catch(Exception $e) {
			Yii::log("try异常：".json_encode($e->getMessage()),CLogger::LEVEL_ERROR,'colourlife.core.RedPacketCarry.createLocalRedPacketCarryOrder');
			// @$other->delete();
			(!$isTransaction) ? $transaction->rollback() : '';   // 在异常处理中回滚
			return false;
		}
	
	}
}
