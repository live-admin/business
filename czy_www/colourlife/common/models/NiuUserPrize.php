<?php

/**
 * This is the model class for table "niu_user_prize".
 *
 * The followings are the available columns in table 'niu_user_prize':
 * @property integer $id
 * @property string $mobile
 * @property integer $prize_id
 * @property string $prize_name
 * @property integer $create_time
 */
class NiuUserPrize extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'niu_user_prize';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('prize_id, create_time', 'numerical', 'integerOnly'=>true),
			array('mobile, prize_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mobile, prize_id, prize_name, create_time', 'safe', 'on'=>'search'),
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
			'id' => '主键id',
			'mobile' => '用户手机号码',
			'prize_id' => '奖品id(
1:环球精选”内任选奶粉2罐(第1-5名)
2:伊利安慕希牛奶或伊利金典纯牛奶1箱(第6-10名)
3:1份Sunrise日升芦荟绵羊油(第11－25名)
4:贝瑟斯创意陶瓷马克杯1个(第26-45名)
5:价值20元无门槛优惠券(第46-70名)
6:价值10元无门槛优惠券(第71-100名)
7:达到300ml
8:达到900ml
9:达到1600ml
)',
			'prize_name' => '奖品名称',
			'create_time' => '创建时间',
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
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('prize_name',$this->prize_name,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NiuUserPrize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
