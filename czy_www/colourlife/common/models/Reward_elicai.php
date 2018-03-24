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
class Reward_elicai extends CActiveRecord
{	
	public $modelName = 'E理财奖励订单';
    public $startPayTime;
    public $endPayTime;


    static $revise_type = array(
        0 => "发放到彩管家",
        1 => "发放到彩之云",
    );

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
		return 'reward_elicai';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('month, create_time, is_receive, revise_type', 'numerical', 'integerOnly'=>true),
            array('sn, e_sn', 'length', 'max'=>32),
            array('bind_mobile', 'length', 'max'=>15),

            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, sn, e_sn, create_time, is_receive, revise_type, bind_mobile', 'safe', 'on'=>'search'),
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
			'e_sn' => '合和年订单号',
			'month' => '投资周期',
			'create_time' => '传递时间',
			'is_receive' => '是否成功接收数据',
            'revise_type' => '手机号码类型',
            'bind_mobile' => '绑定OA手机号码',
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
		$criteria->compare('month',$this->month);
		$criteria->compare('is_receive',$this->is_receive);
		
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
		$criteria->compare('e_sn',$this->e_sn,true);
		
		$criteria->compare('month',$this->month);
		$criteria->compare('is_receive',$this->is_receive);
		
        return new ActiveDataProvider($this, 
            array(
                'criteria' => $criteria, 
                'sort' => array('defaultOrder' => '`t`.create_time desc',)
                )
            );        
    }
    
	public function getOrder($e_sn = null, $sn = null)
    {
    	$orderInfo = array();
    	
    	if(empty($e_sn) && empty($sn)) {
    		return $orderInfo;
    	}
    	
    	if(!empty($e_sn) && !empty($sn)) {
    		$orderInfo = Reward_elicai::model()->find('e_sn=:e_sn and sn=:sn',array(':e_sn'=>$e_sn,':sn'=>$sn));
    		return $orderInfo;
    	}
    		
    	if(!empty($e_sn)) {
    		$orderInfo = Reward_elicai::model()->find('e_sn=:e_sn',array(':e_sn'=>$e_sn));
    		return $orderInfo;
    	}
    	
    	if(!empty($sn)) {
    		$orderInfo=Reward_elicai::model()->find('sn=:sn',array(':sn'=>$sn));
    		return $orderInfo;
    	}
    	
    	return $orderInfo;
    }

    public static function createOrder($feeAttr)
    {
        //判断参数
        if (empty($feeAttr)) {
            Reward_elicai::model()->addError('id', "接收E理财推荐奖励数据失败！");
            return false;
        }

        //创建我们的订单记录及记录
        $other = new Reward_elicai();
        $other->attributes = $feeAttr;
        // var_dump($other);die;
        if (!$other->save()) {
            Reward_elicai::model()->addError('id', "接收E理财推荐奖励数据失败！");
            return false;
        }    
        
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

}
