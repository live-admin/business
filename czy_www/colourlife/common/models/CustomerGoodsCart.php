<?php

/**
 * This is the model class for table "customer_goods_cart".
 *
 * The followings are the available columns in table 'customer_goods_cart':
 * @property integer $id
 * @property integer $good_id
 * @property integer $number
 * @property string $good_name
 * @property string $good_price
 * @property integer $community_id
 * @property integer $shop_name
 * @property integer $customer_id
 * @property integer $create_time
 */
class CustomerGoodsCart extends CActiveRecord
{
   /**
     * @var string 模型名
     */
    public $modelName = '业主商品购物车';

    public $number;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CustomerGoodsCart the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customer_goods_cart';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('good_id, number, good_name, good_price, community_id, shop_id, customer_id', 'required'),
			array('good_id, number, community_id, customer_id, shop_id, create_time', 'numerical', 'integerOnly'=>true),
			array('good_name', 'length', 'max'=>200),
			array('good_price', 'length', 'max'=>10),
			array('id, good_id, number, good_name, good_price, community_id, shop_id, customer_id, create_time', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'goodInfo'=>array(self::BELONGS_TO, 'Goods', 'good_id'),
            'customerInfo'=>array(self::BELONGS_TO,'Customer','customer_id'),
            'communityInfo'=>array(self::BELONGS_TO,'Community','community_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'good_id' => '商品ID',
			'number' => '商品数量',
			'good_name' => '商品名称',
			'good_price' => '商品单价',
			'community_id' => '所属小区',
			'shop_id' => '商家名称',
			'customer_id' => '购买人',
			'create_time' => '添加时间',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('good_id',$this->good_id);
		$criteria->compare('number',$this->number);
		$criteria->compare('good_name',$this->good_name,true);
		$criteria->compare('good_price',$this->good_price,true);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function behaviors()
    {
        return array(
			'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                                'createAttribute' => 'create_time',
                                'updateAttribute' => null,
                                'setUpdateOnCreate' => true,
                            ),                        
        );
    }

    //获取图片
    public function getGoodsImgUrl(){
        return Yii::app()->ajaxUploadImage->getUrl($this->goodInfo->good_image);
    }

    //获取可使用的积分
    public function getUseIntegral(){
        return isset($this->goodInfo)?$this->goodInfo->integral:"0.00";
    }

    //获取业主积分
    public function getCustomerCredit(){
        $customer=Customer::model()->findByPk(Yii::app()->user->id);
        return $customer->credit;
    }
    //获取可使用的积分对应的金额
    public function getUseIntegralPrice(){
        return isset($this->goodInfo)?$this->goodInfo->integralPrice:"0.00";
    }
    //APP判断购物车的商品是否已经使用积分
    public function getIsUse(){
        if($this->integral>0){
            return 1;
        }else{
            return 0;
        }
    }

    //获取积分开关
    public function getIsUseIntegral(){
        $switch=Yii::app()->config->integralSwitch;
        $verify="off";
        if($switch['goodsSwitch']){
            $verify="on";
        }
        return $verify;
    }
}