<?php

/**
 * This is the model class for table "tree_two_seed".
 *
 * The followings are the available columns in table 'tree_two_seed':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $times
 * @property integer $way
 * @property integer $is_new
 * @property integer $is_cai
 * @property integer $is_click
 * @property integer $create_time
 * @property integer $update_time
 */
class TreeTwoSeed extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tree_two_seed';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, times, way, is_new, is_cai, is_click, create_time, update_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, times, way, is_new, is_cai, is_click, create_time, update_time', 'safe', 'on'=>'search'),
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
			'customer_id' => '用户ID',
			'times' => '非彩富人生弹出的次数',
			'way' => '获得种子途径（1系统赠送，2为经验值）',
			'is_new' => '新老用户弹框',
			'is_cai' => '是否彩富人生',
			'is_click' => '土地点击后(new标志;1:显示，2:不现实)',
			'create_time' => '添加时间',
			'update_time' => '修改时间',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('times',$this->times);
		$criteria->compare('way',$this->way);
		$criteria->compare('is_new',$this->is_new);
		$criteria->compare('is_cai',$this->is_cai);
		$criteria->compare('is_click',$this->is_click);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TreeTwoSeed the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
