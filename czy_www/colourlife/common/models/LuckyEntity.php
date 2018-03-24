<?php

/**
 * This is the model class for table "lucky_entity".
 *
 * The followings are the available columns in table 'lucky_entity':
 * @property integer $id
 * @property integer $prize_id
 * @property integer $shop_id
 * @property string $code
 * @property integer $is_use
 * @property integer $state
 * @property integer $customer_id
 * @property integer $update_time
 * @property integer $lucky_result_id
 */
class LuckyEntity extends CActiveRecord
{	


	public $modelName = "中奖名单管理";
	public $customer_name;
    public $customer_mobile;
    public $startTime;
    public $endTime;

	static $shop_list = array(
        52424 => "深圳豪派特华美达酒店",
        52425 => "趣园酒店公寓",
        52427 => '清风仙境',
        52428 => '海岛三角洲',
        52429 => '苏州太湖天成',
        52430 => '海陵岛',
        52431 => '惠州丽兹公馆',
        52432 => '皓雅养生度假酒店',
        52433 => '罗浮山公路度假山庄',
        52434 => '凤池岛',
    );



    static $avoid_status = array( "验证","已验证" );


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_entity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prize_id, shop_id, is_use, state, customer_id, update_time, lucky_result_id', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>25),
			array('note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, prize_id, shop_id, code, is_use, state, customer_id, update_time, lucky_result_id, note, customer_name, customer_mobile, startTime, endTime', 'safe', 'on'=>'search'),
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
			'prize' => array(self::BELONGS_TO, 'LuckyPrize', 'prize_id'),
			'luckyResult' => array(self::HAS_ONE, 'LuckyCustResult', 'lucky_result_id'),
			'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'prize_id' => '奖项ID',
			'shop_id' => '商家ID',
			'code' => '优惠码',
			'is_use' => '是否使用',
			'state' => '验证状态',
			'customer_id' => '用户ID',
			'update_time' => '验证时间',
			'note' => '备注',
			'lucky_result_id' => '中奖纪录ID',
			'CustomerName' => '用户姓名',
			'CustomerMobile' => '用户手机',
			'ShopName' => '优惠商家',
			'customer_name' => '业主姓名',
            'customer_mobile' => '业主手机',
            'startTime' => '验证开始时间',
            'endTime' => '验证结束时间',
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

		$criteria->compare('is_use',1);
		$criteria->compare('id',$this->id);
		$criteria->compare('prize_id',$this->prize_id);
		if(Yii::app()->user->id!=1){
			$criteria->compare('shop_id',Yii::app()->user->id);
		}
		$criteria->compare('code',$this->code,true);
		$criteria->compare('is_use',$this->is_use);
		$criteria->compare('state',$this->state);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('lucky_result_id',$this->lucky_result_id);
		$criteria->compare('note',$this->note,true);

		if ($this->startTime != '') {
            $criteria->compare("`t`.update_time", ">=" . strtotime($this->startTime." 00:00:00"));
        }

        if ($this->endTime != '') {
            $criteria->compare("`t`.update_time", "<=" . strtotime($this->endTime." 23:59:59"));
        }

		if($this->customer_name || $this->customer_mobile){
            $criteria->with[] = 'customer';
            $criteria->compare('customer.name', $this->customer_name, true);
            $criteria->compare('customer.mobile', $this->customer_mobile, true);
        }


		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyEntity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function getCustomerName(){
        return $this->customer?$this->customer->name:"";
    }


    public function getCustomerMobile(){
        return $this->customer?$this->customer->mobile:"";
    }


    public function getShopName()
    {
        $return = '';
        $return .= self::$shop_list[$this->shop_id];
        return $return;
    }


    public function getStatusName($html = false)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$avoid_status[$this->state];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }


    
}
