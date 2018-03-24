<?php

/**
 * This is the model class for table "lucky_shop".
 *
 * The followings are the available columns in table 'lucky_shop':
 * @property integer $id
 * @property integer $shop_id
 * @property string $item
 * @property string $name
 * @property string $discount
 * @property string $market_price
 */
class LuckyShop extends CActiveRecord
{	

	public $modelName = "商家套餐管理";
	public $shop_list = array(
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
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_shop';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shop_id', 'numerical', 'integerOnly'=>true),
			array('item, name', 'length', 'max'=>255),
			array('discount, market_price', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, shop_id, item, name, discount, market_price, create_time, is_deleted', 'safe', 'on'=>'search'),
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
			'shop_id' => '商家',
			'item' => '套餐类型',
			'name' => '套餐内容',
			'discount' => '优惠价',
			'market_price' => '门店价',
			'ShopName' => '商家',
			'create_time' => '创建时间',
			'is_deleted' => '删除',
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
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('item',$this->item,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('discount',$this->discount,true);
		$criteria->compare('market_price',$this->market_price,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



	public function behaviors()
    {
        return array(
        	'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }




	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyShop the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	public function getShopName($html = false)
    {
        $return = '';
        $return .= ($html) ? '<span class="label label-success">' : '';
        $return .= $this->shop_list[$this->shop_id];
        $return .= ($html) ? '</span>' : '';
        return $return;
    }


}
