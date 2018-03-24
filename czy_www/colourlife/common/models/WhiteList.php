<?php

/**
 * This is the model class for table "white_list".
 *
 * The followings are the available columns in table 'white_list':
 * @property integer $id
 * @property string $mobile
 * @property string $dec
 * @property integer $state
 * @property integer $create_time
 */
class WhiteList extends CActiveRecord
{

	public $modelName ="转账白名单";
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'white_list';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mobile,dec', 'required'),
			array('state, create_time', 'numerical', 'integerOnly'=>true),
			array('mobile', 'length', 'max'=>15),
			array('dec', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, mobile, dec, state, create_time', 'safe', 'on'=>'search'),
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
			'mobile' => '彩之云账号',
			'dec' => '描述',
			'state' => '状态',
			'create_time' => '时间',
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
		$criteria->compare('`dec`',$this->dec,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return WhiteList the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
