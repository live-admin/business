<?php

/**
 * This is the model class for table "lucky_prize_born".
 *
 * The followings are the available columns in table 'lucky_prize_born':
 * @property integer $id
 * @property integer $prize_id
 * @property string $last_born_date
 */
class LuckyPrizeBorn extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_prize_born';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('last_born_date', 'required'),
			array('prize_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, prize_id, last_born_date', 'safe', 'on'=>'search'),
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
			'prize_id' => '奖品id',
			'last_born_date' => '上一次产生时间',
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
		$criteria->compare('prize_id',$this->prize_id);
		$criteria->compare('last_born_date',$this->last_born_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyPrizeBorn the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获得奖项的上一次产生时间
	 * @param unknown $ids
	 */
	public function getLastBorn($ids){
		if(count($ids)<=0){return array();}
		
		$idStr=implode(",",$ids);
		$connection=Yii::app()->db;
		$sql="SELECT prize_id,MAX(last_born_date) AS last_date FROM lucky_prize_born
				where prize_id in  ($idStr)
				GROUP BY prize_id";
		$rows=$connection->createCommand ($sql)->queryAll();
		return $rows;
	}
}
