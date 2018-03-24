<?php

/**
 * This is the model class for table "travel_like".
 *
 * The followings are the available columns in table 'travel_like':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $title_id
 * @property integer $like
 * @property integer $type
 * @property integer $create_time
 */
class TravelLike extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'travel_like';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, title_id, like, type, create_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, customer_id, title_id, like, type, create_time', 'safe', 'on'=>'search'),
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
			'customer_id' => '用户ID',
			'title_id' => '文章ID',
			'like' => '点赞',
			'type' => '点赞类型（11为攻略，22为游记）',
			'create_time' => '创建时间',
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
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('title_id',$this->title_id);
		$criteria->compare('like',$this->like);
		$criteria->compare('type',$this->type);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TravelLike the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*判断是否能够点赞*/
	public  function getCheckLike($id,$type,$user_id){
		$start_time = strtotime(date('Y-m-d'));
		$end_time = strtotime(date('Y-m-d',strtotime('+1 day')));
		$travelLike = TravelLike::model();
		//		$likeCount =$travelLike->findAll('customer_id=:customer_id and type=:type and create_time>=:start_time and create_time<:end_time',
		//			array(':customer_id'=>$user_id , ':type'=>$type , ':start_time'=>$start_time , ':end_time'=>$end_time));
		$shareLike = $travelLike->findAll('customer_id=:customer_id and title_id=:title_id and type=:type and create_time>=:start_time and create_time<:end_time',
				array(':customer_id'=>$user_id ,':title_id'=>$id, ':type'=>$type , ':start_time'=>$start_time , ':end_time'=>$end_time));
		if(count($shareLike)<1){
			return 1;
		}else{
			return 0;
		}
	}
}
