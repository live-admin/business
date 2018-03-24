<?php

/**
 * This is the model class for table "feedback_type".
 *
 * The followings are the available columns in table 'feedback_type':
 * @property integer $id
 * @property string $content
 * @property integer $state
 * @property integer $create_time
 * @property integer $update_time
 */
class FeedbackType extends CActiveRecord
{
	public $modelName = '用户反馈类型';
	public $state_arr=array(0=>'已启用',1=>'已禁用');//是否启用(0启用，1禁用)
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'feedback_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, state, create_time, update_time', 'safe', 'on'=>'search'),
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
			'id' => '表ID',
			'name' => '反馈类型',
			'state' => '是否禁用',
			'create_time' => '添加时间',
			'update_time' => '更新时间',
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
		$criteria->compare('state',$this->state);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FeedbackType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 获取反馈类型
	 */
	public function getTypeAndTotal($customer_id){
		$type=array();
		//反馈类型
		$typeModel=FeedbackType::model()->findAll("state=0");
		if (!empty($typeModel)){
			foreach ($typeModel as $val){
				$tmp=array();
				if (empty($val->name)){
					continue;
				}
				$tmp['id']=$val->id;
				$tmp['typeName']=$val->name;
				$type[]=$tmp;
			}
		}
		//反馈总数
		$total=0;
		if (!empty($customer_id)){
			$total=FeedbackReply::model()->count("is_read=0 and to_user_id=:to_user_id",array(':to_user_id'=>$customer_id));
		}
		return array (
				'type' => $type,
				'total' => $total
		);
	}
	
	/**
	 * 获取启用名称
	 * @param string $state
	 * @return string
	 */
	public function getStateName($state = '')
	{
		$return = '';
		switch ($state) {
			case '':
				$return = "";
				break;
			case 0:
				$return = '<span class="label label-success">'.$this->state_arr[0].'</span>';
				break;
			case 1:
				$return = '<span class="label label-error">'.$this->state_arr[1].'</span>';
				break;
		}
		return $return;
	}
	
	/*
	 * @version 启用功能
	*/
	public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=0;
		$model->update_time=time();
		$model->save();
	}
	/*
	 * @version 禁用功能
	*/
	public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->state=1;
		$model->update_time=time();
		$model->save();
	}
}
