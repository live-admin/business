<?php

/**
 * This is the model class for table "lucky_shop_data".
 *
 * The followings are the available columns in table 'lucky_shop_data':
 * @property integer $id
 * @property integer $lucky_shop_id
 * @property integer $entity_id
 * @property integer $status
 * @property integer $avoid_time
 */
class LuckyShopData extends CActiveRecord
{	

	public $modelName = "优惠券消费情况";

	static $count_status = array( "确认消费","已经消费" );

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_shop_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lucky_shop_id, entity_id, status, avoid_time', 'numerical', 'integerOnly'=>true),
			array('note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, lucky_shop_id, entity_id, status, avoid_time, note', 'safe', 'on'=>'search'),
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
			'luckyShop' => array(self::BELONGS_TO, 'LuckyShop', 'lucky_shop_id'),
			'LuckyEntity' => array(self::BELONGS_TO, 'LuckyEntity', 'entity_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'lucky_shop_id' => '优惠套餐ID',
			'entity_id' => '优惠券ID',
			'status' => '消费状态',
			'avoid_time' => '消费时间',
			'note' => '备注',
			'ShopItem' => '套餐',
			'ShopItemName' => '套餐内容',
			'ShopDiscount' => '优惠价',
			'ShopPrice' => '门店价',
			'StatusName' => '套餐处理情况',
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
		$criteria->compare('lucky_shop_id',$this->lucky_shop_id);
		$criteria->compare('entity_id',$this->entity_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('avoid_time',$this->avoid_time);
		$criteria->compare('note',$this->note,true);
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyShopData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}



	public function getShopItem(){
        return $this->luckyShop?$this->luckyShop->item:"";
    }


    public function getShopItemName(){
        return $this->luckyShop?$this->luckyShop->name:"";
    }


    public function getShopDiscount(){
        return $this->luckyShop?$this->luckyShop->discount:0;
    }


    public function getShopPrice(){
        return $this->luckyShop?$this->luckyShop->market_price:0;
    }


    public function getStatusName($html = false)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= self::$count_status[$this->status];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }


}
