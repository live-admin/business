<?php

/**
 * This is the model class for table "dian_zan".
 *
 * The followings are the available columns in table 'dian_zan':
 * @property integer $id
 * @property integer $tie_zi_id
 * @property integer $user_id
 * @property integer $is_praised
 * @property integer $create_time
 * @property integer $update_time
 */
class DianZan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'dian_zan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tie_zi_id, user_id, is_praised, create_time, update_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tie_zi_id, user_id, is_praised, create_time, update_time', 'safe', 'on'=>'search'),
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
			'id' => '主键',
			'tie_zi_id' => '帖子id',
			'user_id' => '用户id',
			'is_praised' => '是否点赞(1:未点赞:2:已点赞)',
			'create_time' => '创建时间',
			'update_time' => '修改时间',
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
		$criteria->compare('tie_zi_id',$this->tie_zi_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('is_praised',$this->is_praised);
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
	 * @return DianZan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
