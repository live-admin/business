<?php

/**
 * This is the model class for table "complain_repairs_customer_log".
 *
 * The followings are the available columns in table 'complain_repairs_customer_log':
 * @property integer $id
 * @property integer $complain_repairs_id
 * @property integer $employee_id
 * @property integer $type
 * @property integer $user_state
 * @property string $comment
 * @property integer $user_comment_state
 * @property integer $create_time
 */
class ComplainRepairsCustomerLog extends CActiveRecord
{
	public $oldState ;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'complain_repairs_customer_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_time', 'required'),
			array('complain_repairs_id,shop_id, employee_id, type, user_state, user_comment_state, create_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id,shop_id, complain_repairs_id, employee_id, type, user_state, comment, user_comment_state, create_time', 'safe', 'on'=>'search'),
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
				'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
				'complain_repairs' => array(self::BELONGS_TO, 'ComplainRepairs', 'complain_repairs_id','on' =>' t.type <> '.Item::COMPLAIN_REPAIRS_TYPE_PERSON),
				'personal_repairs' => array(self::BELONGS_TO, 'PersonalRepairsInfo', 'complain_repairs_id','on'=>' t.type = '.Item::COMPLAIN_REPAIRS_TYPE_PERSON),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'complain_repairs_id' => 'Complain Repairs',
			'employee_id' => 'Employee',
			'type' => 'Type',
			'user_state' => 'User State',
			'comment' => 'Comment',
			'user_comment_state' => 'User Comment State',
			'create_time' => 'Create Time',
		);
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
	/**
	 * 业主评论
	 */
	public static  function createCustomerLog($complain_repairs_id,$comment,$user_comment_state,$user_state,$type=0){
		$model = new self;
		$model->complain_repairs_id = $complain_repairs_id;
		$model->comment = $comment;
		$model->user_comment_state = $user_comment_state;
		$model->user_state = $user_state;
		$model->type = $type;
		$model->create_time = time();
		$model->save();
		return $model;
	}
	
	public static function createShopLog($complain_repairs_id,$comment,$user_state,$type=Item::COMPLAIN_REPAIRS_TYPE_PERSON){
		$model = new self;
		$model->shop_id = $complain_repairs_id;
		$model->comment = $comment;
		$model->user_state = $user_state;
		$model->type = $type;
		$model->employee_id =  empty(Yii::app()->user->id)?0:Yii::app()->user->id;
		$model->create_time = time();
		$model->save();
		return $model;
	}
	
	/**
	 * 物业人员操作log
	 */
 	public static function createEmployeeLog($complain_repairs_id,$comment,$user_state,$type=Item::COMPLAIN_REPAIRS_TYPE_CUSTOMER){
 		$model = new self;
 		$model->complain_repairs_id = $complain_repairs_id;
 		$model->comment = $comment;
 		$model->user_state = $user_state;
 		$model->type = $type;
 		$model->employee_id = empty(Yii::app()->user->id)?0:Yii::app()->user->id;
 		$model->create_time = time();
 		$model->save();
 		return $model;
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
		$criteria->compare('complain_repairs_id',$this->complain_repairs_id);
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('user_state',$this->user_state);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('user_comment_state',$this->user_comment_state);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ComplainRepairsCustomerLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
	
  	public function getComplainRepairs(){
		if($this->type == Item::COMPLAIN_REPAIRS_TYPE_PERSON){
			return $this->personal_repairs;
		}else{
			return $this->complain_repairs;
		}
	}
}
