<?php

/**
 * This is the model class for table "ef_fund_summary".
 *
 * The followings are the available columns in table 'ef_fund_summary':
 * @property string $name
 * @property string $value
 * @property string $description
 */
class FundSummary extends CActiveRecord
{
	
	/**
	 * 获取总的捐款金额
	 */
	public static function getTotalAmount()
	{
	    $r = self::getValue("total_amount");
		return $r=="" ? "0" : $r;
	}
	
	/**
	 * 获取总的捐款人次
	 */
	public static function getTotalRaiseCount()
	{
	    $r = self::getValue("total_raise_count");
	    return $r=="" ? "0" : $r;
	}

	/**
	 * 获取已向外捐款总金额
	 * @return string
	 */
	public static function getTotalUseAmount()
	{
	    $r = self::getValue("total_use_amount");
	    return $r=="" ? "0" : $r;
	}

    /**
     * 获取资金池总金额
     * @return string
     */
	public static function getFundPoolTotalAmount()
	{
	    $r = self::getValue("fund_pool_total_amount");
	    return $r=="" ? "0" : $r;
	}

	
	/**
	 * 增加总捐款金额
	 * 
	 * @param number $amount
	 * @return string 执行成功，返回增加后的捐款金额，否则返回空字符串
	 */
	public static function addAmount($amount)
	{
	    $inTheTrans = false;
	    $currentTran = Yii::app()->db->getCurrentTransaction();
		if($currentTran != null)
		{
			$inTheTrans = true;
		}
		
		if(!$inTheTrans)
		{
		    $trans = Yii::app()->db->beginTransaction();
		}
		
		//增加总收入金额
		$totalAmount = self::getTotalAmount();
		$totalAmount = bcadd($totalAmount, $amount, 2);
		self::setValue("total_amount", $totalAmount);
		
		//增加资金池金额
		$poolTotalAmount = self::getFundPoolTotalAmount();
		$poolTotalAmount = bcadd($poolTotalAmount, $amount, 2);
		self::setValue("fund_pool_total_amount", $poolTotalAmount);
		
		if(!$inTheTrans)
		{
			$trans->commit();
		}
	}
	
	/**
	 * 减少基金池资金.
	 * 基金会将资金往外捐助时
	 * @param number $amount
	 */
	public static function subAmount($amount)
	{
	    $inTheTrans = false;
	    $currentTran = Yii::app()->db->getCurrentTransaction();
	    if($currentTran != null)
	    {
	        $inTheTrans = true;
	    }
	    
	    if(!$inTheTrans)
	    {
	        $trans = Yii::app()->db->beginTransaction();
	    }
	    
	    //减少资金池金额
	    $poolTotalAmount = self::getFundPoolTotalAmount();
		$poolTotalAmount = bcsub($poolTotalAmount, $amount, 2);
		self::setValue("fund_pool_total_amount", $poolTotalAmount);
	    
	    //增加已捐金额
	    $totalUseAmount = self::getTotalUseAmount();
	    $totalUseAmount = bcadd($totalUseAmount, $amount, 2);
	    self::setValue("total_use_amount", $totalUseAmount);
	    
	    if(!$inTheTrans)
	    {
	        $trans->commit();
	    }
	}

	
	/**
	 * 增加一次捐款人次.
	 * @return string 增加后的人数
	 */
	public static function increaseRaiseCount()
	{
		$inTheTrans = false;
	    $currentTran = Yii::app()->db->getCurrentTransaction();
		if($currentTran != null)
		{
			$inTheTrans = true;
		}
		
		if(!$inTheTrans)
		{
		    $trans = Yii::app()->db->beginTransaction();
		}
		
		$raiseCount = self::getTotalRaiseCount();
		$raiseCount = bcadd($raiseCount, "1");		
		self::setValue("total_raise_count", $raiseCount);
        if(!$inTheTrans)
		{
			$trans->commit();
		}
		return $raiseCount;
	}
	
	
	public static function getValue($name)
	{
		$sumary = FundSummary::model()->findByPk($name);
		return $sumary === null ? "" : $sumary->value; 
	}
	
	public static function setValue($name, $value)
	{
		$model = FundSummary::model();
		$summary = $model->findByPk($name);		
		if($summary === null)
		{
			$summary = new FundSummary();
			$summary->name = $name;
		}
		$summary->value = $value;
		$summary->save();
	}

	
	
	
	
	
	
	
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ef_fund_summary';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>128),
			array('value, description', 'length', 'max'=>256),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('name, value, description', 'safe', 'on'=>'search'),
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
			'name' => '汇总名称',
			'value' => '汇总值',
			'description' => '汇总值的描述说明',
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

		$criteria->compare('name',$this->name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FundSummary the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
