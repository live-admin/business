<?php

/**
 * This is the model class for table "ef_fund_use_detail".
 *
 * The followings are the available columns in table 'ef_fund_use_detail':
 * @property string $id
 * @property string $project_id
 * @property string $amount
 * @property integer $create_time
 * @property string $description
 */
class FundUseDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ef_fund_use_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id, amount, create_time', 'required'),
			array('create_time', 'numerical', 'integerOnly'=>true),
			array('project_id', 'length', 'max'=>10),
			array('amount', 'length', 'max'=>14),
		    array('amount', 'numerical'),
		    array('amount', 'checkAmount'),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, project_id, amount, create_time, description', 'safe', 'on'=>'search'),
		);
	}

	public function checkAmount()
	{
	    if($this->amount < 0.01)
	    {
	        $this->addError("amount", "捐款金额必须大于0.01");
	    }
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
			'project_id' => '项目id',
			'amount' => '捐款资金',
			'create_time' => '创建日期',
			'description' => '说明',
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
		$criteria->compare('project_id',$this->project_id,true);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FundUseDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
