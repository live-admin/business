<?php

/**
 * This is the model class for table "dideba".
 *
 * The followings are the available columns in table 'dideba':
 * @property integer $id
 * @property string $model
 * @property integer $customer_id
 * @property integer $object_id
 * @property integer $create_time
 */
class Dideba extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dideba';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('model, object_id', 'required'),
			array('customer_id, object_id, create_time', 'numerical', 'integerOnly'=>true),
			array('model', 'length', 'max'=>200),
            array('object_id', 'checkDideba', 'on' => 'createDideba'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, model, customer_id, object_id, create_time', 'safe', 'on'=>'search'),
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
			'model' => 'Model',
			'customer_id' => 'Customer',
			'object_id' => 'Object',
			'create_time' => 'Create Time',
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
		$criteria->compare('model',$this->model,true);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('object_id',$this->object_id);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function checkDideba($attribute, $parsm)
    {
        if(!$this->hasErrors()){
            if(empty($this->object_id)){
                $this->addError($attribute, '内容ID不能为空！');
            }
            if(empty($this->customer_id) && Yii::app()->user->isGuest){
                $this->addError('customer_id', '请先登录！');
            }
            $this->customer_id = empty($this->customer_id) ? $this->customer_id : Yii::app()->user->id;
            if(self::model()->findByAttributes(array('customer_id' => $this->customer_id, 'object_id' => $this->object_id))){
                $this->addError('customer_id', '您已成功赞过一次了。');
            }
        }
    }

    /**
     * 点击赞一个
     * @param array $attributes
     * @return array|bool
     */
    public static function createDideba(array $attributes)
    {
        $model = new self;
        $model->setScenario('createDideba');
        $model->attributes = $attributes;
        if($model->validate()){
            $model->customer_id = !empty($model->customer_id) ? $model->customer_id : Yii::app()->user->id;
            if($model->save()){
                return true;
            }
        }
        return $model->getErrors();
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Dideba the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function behaviors(){
		$array = array(
            'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'create_time',
				'updateAttribute' => null,
				'setUpdateOnCreate' => true,
		));
		return CMap::mergeArray(parent::behaviors(),$array);
	}
}
