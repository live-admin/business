<?php

/**
 * This is the model class for table "purchase_order_log".
 *
 * The followings are the available columns in table 'purchase_order_log':
 * @property integer $id
 * @property integer $order_id
 * @property integer $employee_id
 * @property integer $create_time
 * @property integer $status
 * @property string $note
 */
class PurchaseOrderLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'purchase_order_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id, note', 'required'),
			array('order_id, employee_id, create_time, status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, order_id, employee_id, create_time, status, note', 'safe', 'on'=>'search'),
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
			'order_id' => '订单id',
			'employee_id' => '员工',
			'create_time' => '处理时间',
			'status' => '改变后的状态',
			'note' => '自动备注',
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
		$criteria->compare('order_id',$this->order_id);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PurchaseOrderLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getUserName()
    {
        if($employee = Employee::model()->findByPk($this->employee_id)){
            return empty($employee->name) ? $employee->username : $employee->name;
        }
        return '';
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
        );
    }

    public static function createOrderLog($order_id, $status, $note = '', $employee_id=0)
    {
        $model = new self;
        $model->order_id = $order_id;
        if(intval($employee_id)!=0){
            $model->employee_id = $employee_id;
        }else{
            $model->employee_id = Yii::app()->user->id;
        }
        $model->status = $status;
        $model->note = $note;
        return $model->save();
    }
}
