<?php

/**
 * This is the model class for table "finance_pay_type".
 *
 * The followings are the available columns in table 'finance_pay_type':
 * @property string $id
 * @property string $atid
 * @property string $pano
 * @property integer $typeid
 * @property string $name
 * @property string $memo
 */
class FinancePayType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'finance_pay_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('typeid', 'required'),
			array('typeid', 'numerical', 'integerOnly'=>true),
			array('atid', 'length', 'max'=>10),
			array('pano', 'length', 'max'=>32),
			array('name', 'length', 'max'=>300),
			array('memo', 'length', 'max'=>500),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, atid, pano, typeid, name, memo', 'safe', 'on'=>'search'),
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
			'atid' => '支付账号类型',
			'pano' => '支付主账号编号',
			'typeid' => '支付类型
1：微信支付
2：支付宝
6：全国饭票
',
			'name' => '支付类型名称',
			'memo' => '备注',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('atid',$this->atid,true);
		$criteria->compare('pano',$this->pano,true);
		$criteria->compare('typeid',$this->typeid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('memo',$this->memo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FinancePayType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
