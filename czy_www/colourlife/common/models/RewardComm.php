<?php

/**
 * This is the model class for table "reward_comm_val".
 *
 * The followings are the available columns in table 'region':
 * @property string $name
 * @property integer $parent_id
 * @property integer $state
 * @property integer $is_deleted
 */
class RewardComm extends CActiveRecord
{

	private static $mainFlds = array(
        'customer_id' => 'fld_50',
        'name' => 'fld_49',
    	'mobile' => 'fld_47',
		'amount' => 'fld_46',
		'pay_time' => 'fld_45',
		'inviter_mobile' => 'fld_44',
    );
    
    /**
     * @var string 模型名
     */
    public $modelName = '通用订单数据';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Region the static model class
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
        return 'reward_comm_val';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
			array('rela_sn', 'required'),
			
			array('create_time', 'numerical', 'integerOnly'=>true),
			
			array('sn, rela_sn', 'length', 'max'=>32),
			
			array('type, fld_1,fld_2,fld_3,fld_4,fld_5,fld_6,fld_7,fld_8,fld_9,fld_10,fld_11,fld_12,fld_13,fld_14 ,fld_15 ,fld_16 ,fld_17 ,fld_18 ,fld_19 ,fld_20 ,fld_21 ,fld_22 ,fld_23 ,fld_24 ,fld_25 ,fld_26 ,fld_27 ,fld_28 ,fld_29 ,fld_30 ,fld_31 ,fld_32 ,fld_33 ,fld_34 ,fld_35 ,fld_36 ,fld_37 ,fld_38 ,fld_39 ,fld_40 ,fld_41 ,fld_42,fld_43 ,fld_44 ,fld_45 ,fld_46 ,fld_47 ,fld_48 ,fld_49 ,fld_50', 'length', 'max'=>100),

			array('sn', 'unique', 'on' => 'newCreate'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sn, rela_sn, create_time, startTime, endTime, type, fld_1,fld_2,fld_3,fld_4,fld_5,fld_6,fld_7,fld_8,fld_9,fld_10,fld_11,fld_12,fld_13,fld_14 ,fld_15 ,fld_16 ,fld_17 ,fld_18 ,fld_19 ,fld_20 ,fld_21 ,fld_22 ,fld_23 ,fld_24 ,fld_25 ,fld_26 ,fld_27 ,fld_28 ,fld_29 ,fld_30 ,fld_31 ,fld_32 ,fld_33 ,fld_34 ,fld_35 ,fld_36 ,fld_37 ,fld_38 ,fld_39 ,fld_40 ,fld_41 ,fld_42,fld_43 ,fld_44 ,fld_45 ,fld_46 ,fld_47 ,fld_48 ,fld_49 ,fld_50', 'safe', 'on'=>'search'),
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
			'rela_sn' => '对方订单号',
			'type' => '产品种类',
			
			'create_time' => '记录创建时间',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
        );
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id',$this->id);
		$criteria->compare('sn',$this->sn,true);
		$criteria->compare('rela_sn',$this->rela_sn,true);	
		$criteria->compare('type',$this->type,true);

		Yii::import('common.components.ActiveDataProvider');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
    }
    
    public function getOrder($rela_sn = null, $sn = null)
    {
    	$orderInfo = array();
    	
    	if(empty($rela_sn) && empty($sn)) {
    		return $orderInfo;
    	}
    	
    	if(!empty($rela_sn) && !empty($sn)) {
    		$orderInfo = RewardComm::model()->find('rela_sn=:rela_sn and sn=:sn',array(':rela_sn'=>$rela_sn,':sn'=>$sn));
    		return $orderInfo;
    	}
    		
    	if(!empty($rela_sn)) {
    		$orderInfo = RewardComm::model()->find('rela_sn=:rela_sn',array(':rela_sn'=>$rela_sn));
    		return $orderInfo;
    	}
    	
    	if(!empty($sn)) {
    		$orderInfo=RewardComm::model()->find('sn=:sn',array(':sn'=>$sn));
    		return $orderInfo;
    	}
    	
    	return $orderInfo;
    }
    
	private function beCanNew($type, $rela_sn)
	{
    	$rtn = false;
    	
    	if (empty($type) || empty($rela_sn))
    	{
    		return false;
    	}
    	
    	try 
        {
//	        $connection = Yii::app()->db;
//	        $sql = "select type, rela_sn from reward_comm_val where type= '".$type."' and rela_sn= '".$rela_sn."'";
// 
//	        $command = $connection->createCommand($sql);
//	        $result = $command->queryAll();
//	        
//	        if (count($result) > 0) {
//	        	$rtn = false;
//	        }else{
//	        	$rtn = true;
//	        }
        	$rs = RewardComm::model()->find('type=:type and rela_sn=:rela_sn', array(':type'=>$type,':rela_sn'=>$rela_sn));
    		if ($rs){
    			$rtn = false;
    		}else{
	        	$rtn = true;
	        }
	       
        } catch (Exception $e) {
        	
        }    
    	return $rtn;
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
    		$rs = RewardComm::model()->find('type=:type and rela_sn=:rela_sn and sn<>:sn', array(':type'=>$type,':rela_sn'=>$rela_sn,':sn'=>$sn));
    		if ($rs){
    			return true;
    		}
	       
        } catch (Exception $e) {
        	return false;
        }    
        
        return false;
    }  
    
	public static function createOrder($mAttr)
    {
        //判断参数
        if (empty($mAttr)) {
            RewardComm::model()->addError('id', "接收订单数据失败！");
            return false;
        }
        
        Yii::import('common.components.MultiTblComm');
		$tb_info = MultiTblComm::getInstance()->getRelaTblDes($mAttr['type']); //获得域奖励相关的表结构信息
		if (empty($tb_info)){
			RewardComm::model()->addError('id', "找不到相关表结构描述！");
            return false;
		}
		$inum = intval($tb_info['fld_num']);
		$mOrder  = $mAttr;
		
		
		for($i = 1; $i <= $inum; $i++){
			$fld_name = 'fld_key'.$i;	
			$fld_v = 'fld_'.$i;
			if (empty($mAttr[$tb_info[$fld_name]])){
				$mOrder[$fld_v] =  '';
			}else {
				$mOrder[$fld_v] =  $mAttr[$tb_info[$fld_name]];
			}
		}
        
        //创建我们的订单记录及记录
        $other = new RewardComm();
        $other->setScenario('newCreate');
        $other->attributes = $mOrder;
        
        if (!$other->beCanNew($mAttr['type'], $mAttr['rela_sn'])){
        	
        	return false;
        }

        if (!$other->save()) {
			///Util::debugLog($other->getErrors());
            RewardComm::model()->addError('id', "接收订单数据失败！");
            return false;
        }
        return true;
    }
    
	public static function backUpOrder($mAttr)
    {
        //判断参数
        if (empty($mAttr)) {
            return false;
        }
        
        Yii::import('common.components.MultiTblComm');
		$tb_info = MultiTblComm::getInstance()->getRelaTblDes($mAttr['type']); //获得域奖励相关的表结构信息
		if (empty($tb_info)){
			RewardComm::model()->addError('id', "找不到相关表结构描述！");
            return false;
		}
		$inum = intval($tb_info['fld_num']);
		$mOrder  = $mAttr;
		
		for($i = 1; $i <= $inum; $i++){
			$fld_name = 'fld_key'.$i;	
			$fld_v = 'fld_'.$i;
			if (empty($mAttr[$tb_info[$fld_name]])){
				$mOrder[$fld_v] =  '';
			}else {
				$mOrder[$fld_v] =  $mAttr[$tb_info[$fld_name]];
			}
		}
		
    	foreach (self::$mainFlds as $k1 => $v1) {

    		if (isset($mAttr[$k1])){
	    		if (empty($mAttr[$k1])){
					$mOrder[$v1] =  '';
				}else {
					$mOrder[$v1] =  $mAttr[$k1];
				}
    		}else{
    			$mOrder[$v1] =  '';
    		}
		}
        
        //创建我们的订单记录及记录
        $other = new RewardComm();
        $other->setScenario('newCreate');
        $other->attributes = $mOrder;
        
        if ($other->isExistSame($mAttr['sn'], $mAttr['type'], $mAttr['rela_sn'])){
        	return false;
        }
        if (!$other->save()) {

            RewardComm::model()->addError('id', "接收订单数据失败！");
            return false;
        }
        return true;
    }
}   