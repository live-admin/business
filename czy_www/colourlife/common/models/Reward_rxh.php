<?php

/**
 * This is the model class for table "Reward_rxh".
 *
 * The followings are the available columns in table 'region':
 * @property string $name
 * @property integer $parent_id
 * @property integer $state
 * @property integer $is_deleted
 */
class Reward_rxh extends CActiveRecord
{

    /**
     * @var string 模型名
     */
    public $modelName = '荣信汇订单';

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
        return 'reward_rxh';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('rxh_sn', 'required'),
			array('licai_time_month, licai_time_day, create_time', 'numerical', 'integerOnly'=>true),
			array('income_rate', 'numerical', 'integerOnly'=>false),
			array('sn, rxh_sn', 'length', 'max'=>32),
			array('repayment_date', 'safe'),
		
			array('rxh_sn', 'unique', 'on' => 'newCreate'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sn, rxh_sn, licai_time_month, licai_time_day, create_time, repayment_date, startTime, endTime', 'safe', 'on'=>'search'),
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
			'rxh_sn' => '荣信汇订单号',
			'income_rate' => '收益率',
			'licai_time_month' => '理财时长月',
			'licai_time_day' => '理财时长天',
			'create_time' => '记录创建时间',
			'repayment_date' => '预计还款日',
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
		$criteria->compare('rxh_sn',$this->rxh_sn,true);
		
		$criteria->compare('income_rate',$this->income_rate,true);
		$criteria->compare('licai_time_month',$this->licai_time_month);
		$criteria->compare('licai_time_day',$this->licai_time_day);
		$criteria->compare('repayment_date',$this->repayment_date,true);
		
		Yii::import('common.components.ActiveDataProvider');
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
    }
    
    public function getOrder($rxh_sn = null, $sn = null)
    {
    	$orderInfo = array();
    	
    	if(empty($rxh_sn) && empty($sn)) {
    		return $orderInfo;
    	}
    	
    	if(!empty($rxh_sn) && !empty($sn)) {
    		$orderInfo = Reward_rxh::model()->find('rxh_sn=:rxh_sn and sn=:sn',array(':rxh_sn'=>$rxh_sn,':sn'=>$sn));
    		return $orderInfo;
    	}
    		
    	if(!empty($rxh_sn)) {
    		$orderInfo = Reward_rxh::model()->find('rxh_sn=:rxh_sn',array(':rxh_sn'=>$rxh_sn));
    		return $orderInfo;
    	}
    	
    	if(!empty($sn)) {
    		$orderInfo=Reward_rxh::model()->find('sn=:sn',array(':sn'=>$sn));
    		return $orderInfo;
    	}
    	
    	return $orderInfo;
    }
    
	private function beCanNew($rela_sn)
	{
    	$rtn = false;
    	
    	if (empty($rela_sn))
    	{
    		return false;
    	}
    	
    	try 
        {
        	$rs = Reward_rxh::model()->find('rxh_sn=:rxh_sn', array(':rxh_sn'=>$rela_sn));
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
        if (empty($rela_sn)){
        	return false;
        }
         
        try 
        {
    		$rs = Reward_rxh::model()->find('rxh_sn=:rxh_sn and sn<>:sn', array(':rxh_sn'=>$rela_sn,':sn'=>$sn));
    		if ($rs){
    			return true;
    		}
	       
        } catch (Exception $e) {
        	return false;
        }    
        
        return false;
    }  
    
	public static function createOrder($feeAttr)
    {
        //判断参数
        if (empty($feeAttr)) {
            Reward_rxh::model()->addError('id', "接收荣信汇订单数据失败！");
            return false;
        }
    	if(empty($feeAttr["rxh_sn"])){
    		if(isset($feeAttr["rela_sn"])){
				$feeAttr["rxh_sn"] = $feeAttr["rela_sn"];
    		}
		}
        
        //创建我们的订单记录及记录
        $other = new Reward_rxh();
        $other->setScenario('newCreate');
        $other->attributes = $feeAttr;
        
    	if (!$other->beCanNew($feeAttr["rxh_sn"])){
        	return false;
        }
        
        if (!$other->save()) {
            Reward_rxh::model()->addError('id', "接收荣信汇订单数据失败！");
            return false;
        }
        return true;
    }
}   