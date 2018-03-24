<?php

/**
 * This is the model class for table "thq_order".
 *
 * The followings are the available columns in table 'thq_order':
 * @property integer $id
 * @property string $sn
 * @property integer $buyer_id
 * @property string $shou_huo_name
 * @property string $tel
 * @property string $zip
 * @property string $address
 * @property integer $province
 * @property integer $city
 * @property integer $county
 * @property integer $town
 * @property string $amount
 * @property integer $status
 * @property integer $create_time
 */
class ThqOrder extends CActiveRecord
{
    public $status_arr=array(1=>'待发货',2=>'已发货',3=>'已收货');
    public $buyer_name;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'thq_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('buyer_id, province, city, county, town, status, create_time', 'numerical', 'integerOnly'=>true),
			array('sn, shou_huo_name, tel, zip, address', 'length', 'max'=>255),
			array('amount', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sn, buyer_id, shou_huo_name, tel, zip, address, province, city, county, town, amount, status, create_time', 'safe', 'on'=>'search'),
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
            'customer' => array(self::BELONGS_TO, 'Customer', 'buyer_id'),
            'good_list' => array(self::HAS_MANY, 'ThqOrderGoods', 'thq_order_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sn' => '提货券单号',
			'buyer_id' => '买家帐号',
			'shou_huo_name' => '收货人',
			'tel' => '收货人电话',
			'zip' => '邮编',
			'address' => '详细地址',
			'province' => 'Province',
			'city' => 'City',
			'county' => 'County',
			'town' => 'Town',
			'amount' => '订单金额',
			'status' => '订单状态',
			'create_time' => '下单时间',
            'buyer_name'=>'买家帐号',
            'delivery_express_name'=>'送货快递',
            'delivery_express_sn'=>'送货单号',
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
		$criteria->compare('buyer_id',$this->buyer_id);
		$criteria->compare('shou_huo_name',$this->shou_huo_name,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('province',$this->province);
		$criteria->compare('city',$this->city);
		$criteria->compare('county',$this->county);
		$criteria->compare('town',$this->town);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time);
        $criteria->order = 'create_time desc';
        
//        if($this->buyer_name){
//            $criteria->with=array(  
//                'customer',  
//            );
//            $criteria->compare('customer.name', $this->buyer_name, true);
//        }
        
        
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ThqOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    //提货券订单状态
    public function getStatusName()
    {
        return $this->status_arr[$this->status];
    }
    //获取买家帐号
    public function getCustomerName(){
        return empty($this->customer) ? $this->buyer_id : (empty($this->customer->name) ? $this->customer->username : $this->customer->name);
    }
    public function getThqStatusName($status)
    {
        return $this->status_arr[$status];
    }
}
