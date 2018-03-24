<?php

/**
 * This is the model class for table "year_invite".
 *
 * The followings are the available columns in table 'year_invite':
 * @property integer $id
 * @property string $name
 * @property string $mobile
 * @property integer $create_time
 * @property integer $state
 * @property string $build
 */
class YearInvite extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'year_invite';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_time, state', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('mobile', 'length', 'max'=>15),
			array('build', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, mobile, create_time, state, build', 'safe', 'on'=>'search'),
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
			'name' => '姓名',
			'mobile' => '手机号',
			'create_time' => '创建时间',
			'state' => '状态（默认0）',
			'build' => '单位',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('state',$this->state);
		$criteria->compare('build',$this->build,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return YearInvite the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
