<?php

/**
 * This is the model class for table "credit_order".
 *
 * The followings are the available columns in table 'credit_order':
 * @property integer $id
 * @property integer $credit_gift_id
 * @property integer $credit
 * @property integer $customer_id
 * @property string $customer_phone
 * @property integer $community_id
 * @property integer $status
 * @property string $express_sn
 * @property string $delivery_address
 * @property string $desc
 * @property integer $create_time
 * @property integer $num
 * @property string $build_id
 * @property string $room_id
 * @property string $customer_name
 */
class CreditOrder extends CActiveRecord
{
    public $start_time;
    public $end_time;
    public $modelName = '积分礼品订单';
    public $region;
    public $build_name;
    public $room_name;

    const CREDIT_EXCHANGE_SUCCESS = 1;//兑换成功
    const CREDIT_EXCHANGE_REQUEST = 0;//申请兑换
    const CREDIT_EXCHANGE_REQUESqT = 2;//兑换
    public static $_status = array(
        self::CREDIT_EXCHANGE_REQUEST => '申请兑换',
        self::CREDIT_EXCHANGE_SUCCESS => '兑换成功',
    );

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'credit_order';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('credit_gift_id, num, community_id, customer_phone, customer_id', 'required'),
            array('credit_gift_id, num, community_id, customer_phone, customer_id', 'checkCreate', 'on' => 'create'),//创建订单时需要收货地址
            array('express_sn, desc', 'required', 'on' => 'exchange'),//修改状态时需要填写快递订单与备注信息
			array('credit_gift_id, credit, customer_id, community_id, status, create_time, num', 'numerical', 'integerOnly'=>true),
			array('customer_phone', 'length', 'max'=>15),
			array('express_sn', 'length', 'max'=>45),
			array('delivery_address', 'length', 'max'=>200),
			array('desc, region, build_name, room_name, customer_name, room_id, build_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, credit_gift_id, credit, customer_id, customer_phone, community_id, status, express_sn, delivery_address, desc, create_time, start_time, end_time, num, build_id, room_id, customer_name', 'safe', 'on'=>'search'),
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
            'credit_gift' => array(self::BELONGS_TO, 'CreditGift', 'credit_gift_id'),
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
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
			'credit_gift_id' => '礼品名称',
			'credit' => '兑换积分',
			'customer_id' => '兑换业主',
			'customer_phone' => '业主电话',
			'community_id' => '小区',
			'status' => '兑换状态',
			'express_sn' => '快递单号',
			'delivery_address' => '收货地址',
			'desc' => '备注',
			'create_time' => '创建时间',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'region' => '地区',
            'build_name' => '楼栋',
            'room_name' => '房间',
            'customer_name' => '收获人',
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
		$criteria->compare('credit_gift_id',$this->credit_gift_id);
		$criteria->compare('credit',$this->credit);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('customer_phone',$this->customer_phone,true);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('express_sn',$this->express_sn,true);
		$criteria->compare('delivery_address',$this->delivery_address,true);
		$criteria->compare('desc',$this->desc,true);
        $criteria->compare('num',$this->num);
        $criteria->compare('build_id',$this->build_id,true);
        $criteria->compare('room_id',$this->room_id,true);
        $criteria->compare('customer_name',$this->customer_name,true);
        if ($this->start_time != "") {
            $criteria->addCondition('create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 'create_time DESC'
            ),
		));
	}

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    protected function afterSave()
    {
        if($this->isNewRecord){
            //保存之后 更改礼品表的存量 更改用户的积分
            /**
             * @var $customer Customer
             * @var $creditGift CreditGift
             */
            $creditGift = CreditGift::model()->findByPk($this->credit_gift_id);
            $customer = Customer::model()->findByPk(Yii::app()->user->id);
            if(!empty($creditGift) && !empty($customer)){
                $customer->changeCredit('decrease', array('credit' => $this->credit, 'note' => '业主兑换礼品【'.$creditGift->name.'】【'.$this->num.'个】 使用了【'.$this->credit.'】积分！'));
                $creditGift->num -= $this->num;
                $result = ($creditGift->save());
                return $result;
            }
            else{
                Yii::log('业主兑换礼品【'.$creditGift->name.'】【'.$this->num.'个】 使用了【'.$this->credit.'】积分！ 变更礼品表与业主积分失败', CLogger::LEVEL_INFO);
            }
        }
    }

    protected function beforeSave()
    {
        if($this->isNewRecord){
            if(empty($this->delivery_address)){
                //包装送货地址
                if(is_array($this->region)){
                    foreach($this->region as $key => $id)
                    {
                        $region = Region::model()->findByPk($id);
                        $this->delivery_address .= 0 == $key ? '' : '-';
                        $this->delivery_address .= !empty($region) ? $region->name : '';
                    }
                }
                if(!empty($this->build_name)){
                    $this->delivery_address .= '-'.$this->build_name;
                }
                if(!empty($this->room_name)){
                    $this->delivery_address .= '-'.$this->room_name;
                }
            }
        }
        return parent::beforeSave();
    }

    public function getType($type){
        if($type==Item::RED_PACKET_TYPE_CONSUME){
            return "消费";
        }else if($type==Item::RED_PACKET_TYPE_ACQUIRE){
            return "获取";
        }else if($type==self::CREDIT_EXCHANGE_REQUESqT){
            return "获取";
        }
    }

    public function checkCreate($attribute, $param = null)
    {
        if(!$this->hasErrors()){
            /**
             * @var CreditGift $gift
             */
            if($gift = CreditGift::model()->findByAttributes(array('id' => $this->credit_gift_id, 'state' => Item::STATE_ON))){
                $this->credit = $gift->exchange_credit * $this->num;
                if($this->num > $gift->num){
                    $this->addError('credit_gift_id', '兑换礼品存量不足！');
                }
                else{
                    /**
                     * @var Customer $customer
                     */
                    if($customer = Customer::model()->findByPk(Yii::app()->user->id)){
                        $this->customer_id = $customer->id;
                        if($this->credit > $customer->credit){
                            $this->addError('credit', '用户积分不足！');
                        }
                    }
                    else{
                        $this->addError('credit', '用户积分不足！');
                    }
                }
            }
            else{
                $this->addError('credit_gift_id', '兑换礼品不存在！');
            }
        }
    }

    public function getCreditGiftName($id = null, $html = true)
    {
        $model = $name = '';
        if(empty($id)){
            $id = $this->credit_gift_id;
        }
        if($model = CreditGift::model()->findByPk($id)){
            $name = $model->name;
        }
        if($html && $model && Yii::app()->user->checkAccess('op_backend_creditGift_view')){
            $name = CHtml::link($name, '/creditGift/'.$model->id, array('target' => '_blank'));
        }
        return $name;
    }

    public function getCustomerName($id = null, $html = true)
    {
        $model = $name = '';
        if(empty($id)){
            $id = $this->customer_id;
        }
        if($model = Customer::model()->findByPk($id)){
            $name = empty($this->customer_name) ? (empty($model->name) ? $model->username : $model->name) : $this->customer_name;
        }
        if($html && $model && Yii::app()->user->checkAccess('op_backend_customer_view')){
            $name = CHtml::link($name, '/customer/'.$model->id, array('target' => '_blank'));
        }
        return $name;
    }

    public function getCommunityName($id = null, $html = true)
    {
        $model = $name = '';
        if(empty($id)){
            $id = $this->community_id;
        }
        if($model = Community::model()->findByPk($id)){
            $name = $model->name;
        }
        if($html && $model && Yii::app()->user->checkAccess('op_backend_community_view')){
            $name = CHtml::link($name, '/community/'.$model->id, array('target' => '_blank'));
        }
        return $name;
    }

    /**
     * 可通过传进行的值来获取订单状态名称
     * @param integer $status   状态
     * @param bool $html        是否返回html
     * @return string
     */
    public function getStatusName($status = null, $html = true)
    {
        if(empty($status)){
            $status = $this->status;
        }
        $name = static::$_status[$status];
        if($html){
            $name = sprintf('<span class="label label-%s">%s</span>', self::CREDIT_EXCHANGE_SUCCESS == $status ? 'success' : 'warning', $name);
        }
        return $name;
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CreditOrder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * 积分礼品兑换
     * @param $attributes
     * array(
     *  'region[]' => '收获地区'
     *  'credit_gift_id' => '',礼品ID
     *  'num' => ''数量,
     *  'customer_name' => '收获人名称',
     *  'customer_phone' => '收获人电话',
     *  'community_id'  => '小区ID,
     * )
     * @return array
     */
    public static function createOrder($attributes)
    {
        $result = array('state' => false, 'msg' => '兑换礼品失败！','sn'=>0);
        if(!Yii::app()->user->isGuest){
            $model = new self;
            $model->setScenario('create');
            $model->attributes = $attributes;
            if($model->save()){
                $result['state'] = true;
                $result['msg'] = '兑换礼品成功！';
                $result['sn'] = $model->id;
            }
            elseif($model->hasErrors()){
                if($model->hasErrors()){
                    foreach($model->getErrors() as $val)
                    {
                        foreach($val as $str)
                        {
                            $result['msg'] .= $str.PHP_EOL;
                        }
                    }
                }
            }
        }
        else{
            $result['msg'] = '请先登录！';
        }
        return $result;
    }

    public function getPics(){
        if(!empty($this->credit_gift)){
            $pic_id=$this->credit_gift->id;
            $pic = array();
            $CDbCriteria = new CDbCriteria();
            $CDbCriteria->compare("model", 'creditGift');
            $CDbCriteria->compare("object_id", $pic_id);
            $picture = Picture::model()->findAll($CDbCriteria);
            if (empty($picture)) {
                return $pic;
            } else {

                foreach ($picture as $val) {
                    $pic[] = Yii::app()->imageFile->getUrl($val->url);
                }
                return $pic;
            }
        }
    }

    //APP获取状态名字
    public function getStatusNames(){
        if(empty($status)){
            $status = $this->status;
        }
        $name = static::$_status[$status];
        return $name;
    }

    //获取礼品详情
   public function getAppDesc(){
       return isset($this->credit_gift)?base64_encode($this->credit_gift->app_desc):"";
   }
    //获取商品名字
    public function getGiftName(){
        return isset($this->credit_gift)?$this->credit_gift->name:"";
    }
}
