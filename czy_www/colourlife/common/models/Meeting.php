<?php

/**
 * This is the model class for table "meeting".
 *
 * The followings are the available columns in table 'meeting':
 * @property integer $id
 * @property string $type
 * @property string $title
 * @property string $content
 * @property string $meet_time
 * @property integer $create_time
 * @property integer $update_time
 */
class Meeting extends CActiveRecord
{
	public $meeting_type_arr = array(
		'1' =>'月度经营会议',
		'2' =>'中高层干部大会',
		'3' =>'季度经营会议',
	);
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'meeting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('content, create_time, update_time', 'required'),
			array('create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('type, title, meet_time', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, title, content, meet_time, create_time, update_time', 'safe', 'on'=>'search'),
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
			'type' => '会议类型',
			'title' => '会议标题',
			'content' => '会议内容',
			'meet_time' => '签到时间',
			'create_time' => '创建时间',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('meet_time',$this->meet_time,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/*获取会议类型名称*/
	public function getType(){
		return $this->meeting_type_arr[$this->type];
	}
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Meeting the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
