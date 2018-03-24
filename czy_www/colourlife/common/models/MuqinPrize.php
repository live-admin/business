<?php

/**
 * This is the model class for table "muqin_prize".
 *
 * The followings are the available columns in table 'muqin_prize':
 * @property integer $id
 * @property string $mobile
 * @property integer $prize_id
 * @property string $prize_name
 * @property integer $create_time
 */
class MuqinPrize extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'muqin_prize';
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
			'id' => '表ID',
			'mobile' => '手机号',
			'prize_id' => ' 奖品ID
(1:3元优惠券；2：6元优惠券；3：8元优惠券；4：12元优惠券；5：谢谢惠顾)',
			'prize_name' => '奖品名称',
			'create_time' => '添加时间',
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
	 * @return MuqinPrize the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
