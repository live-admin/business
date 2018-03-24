<?php

/**
 * This is the model class for table "activity_test".
 *
 * The followings are the available columns in table 'activity_test':
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $amount
 */
class ActivityTest extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activity_test';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, name', 'length', 'max'=>255),
			array('amount', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, username, name, amount', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'name' => 'Name',
			'amount' => 'Amount',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('amount',$this->amount,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ActivityTest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    /**
     * 获取冲抵物业费明细接口
     * @throws CException
     */
    public function PropertyFeeCostDetail($sn,$page,$pageSize)
    {
        if (empty($sn))
             throw new CHttpException(400,"订单号为空");
            
        Yii::import('common.services.ProfitService');
        $profitService = new ProfitService();

        $result = $profitService->getPropertyFeeCostDetail($sn, $page=1, $pageSize=10);
        if (false == $result)
            throw new CHttpException($profitService->getErrorCode(),$profitService->getErrorMsg());
        return $result;
    }
}
