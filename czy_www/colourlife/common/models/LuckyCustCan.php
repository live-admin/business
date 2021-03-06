<?php

/**
 * This is the model class for table "lucky_cust_can".
 *
 * The followings are the available columns in table 'lucky_cust_can':
 * @property integer $id
 * @property string $cust_name
 * @property integer $cust_id
 * @property integer $cust_can
 * @property integer $lucky_act_id
 */
class LuckyCustCan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_cust_can';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lucky_act_id', 'required'),
			array('cust_id, cust_can, lucky_act_id', 'numerical', 'integerOnly'=>true),
			array('cust_name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cust_name, cust_id, cust_can, lucky_act_id', 'safe', 'on'=>'search'),
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
			'cust_name' => '客户名称',
			'cust_id' => 'Cust',
			'cust_can' => '可抽奖次数',
			'lucky_act_id' => '活动id',
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
		$criteria->compare('cust_name',$this->cust_name,true);
		$criteria->compare('cust_id',$this->cust_id);
		$criteria->compare('cust_can',$this->cust_can);
		$criteria->compare('lucky_act_id',$this->lucky_act_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyCustCan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获得用户可抽奖次数
	 */
	public function getCustCan($custName,$custId,$luckyActId=0){
	    //
	    //log:  2013-12-17 10:16发现用户 名可更改，更改后，会出问题，去掉用户名的判断
	    //
	    //
	    
// 			return $this->find("cust_name=:custName and cust_id=:custId",
// 								array(":custName"=>$custName,":custId"=>$custId));
			
			$criteria = new CDbCriteria;
			/* $criteria->addCondition("cust_id=:custId");
			$criteria->addCondition("lucky_act_id=:lucky_act_id");
			$criteria->params=array(":custId"=>$custId,":lucky_act_id"=>$luckyActId); */
			$criteria->compare('cust_id', $custId);
			$criteria->compare('lucky_act_id', $luckyActId);
			
			return $this->find($criteria);
	}
}
