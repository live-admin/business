<?php

/**
 * This is the model class for table "purchase_retreat_order_log".
 *
 * The followings are the available columns in table 'purchase_retreat_order_log':
 * @property integer $id
 * @property integer $retreat_id
 * @property integer $employee_id
 * @property integer $create_time
 * @property integer $status
 * @property string $note
 */
class PurchaseRetreatOrderLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PurchaseRetreatOrderLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase_retreat_order_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('note', 'required'),
			array('retreat_id, employee_id, create_time, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, retreat_id, employee_id, create_time, status, note', 'safe', 'on'=>'search'),
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
			'retreat_id' => 'Retreat',
			'employee_id' => 'Employee',
			'create_time' => 'Create Time',
			'status' => 'Status',
			'note' => 'Note',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('retreat_id',$this->retreat_id);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    static public function createOrderLog($order_id, $status, $note = '')
    {
        $model = new self;
        $model->retreat_id = $order_id;
        $model->employee_id = Yii::app()->user->id;
        $model->status = $status;
        $model->note = $note;
        return $model->save();
    }
}