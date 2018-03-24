<?php

/**
 * This is the model class for table "home_world".
 *
 * The followings are the available columns in table 'home_world':
 * @property integer $id
 * @property integer $resource_id
 * @property string $redirect_url
 * @property integer $category
 * @property integer $type
 * @property string $img
 * @property string $native
 * @property integer $state
 * @property integer $create_at
 */
class HomeWorld extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'home_world';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_at', 'required'),
			array('resource_id, category, type, state, create_at', 'numerical', 'integerOnly'=>true),
			array('redirect_url, img', 'length', 'max'=>255),
			array('native', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, resource_id, redirect_url, category, type, img, native, state, create_at', 'safe', 'on'=>'search'),
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
			'resource_id' => 'Resource',
			'redirect_url' => 'Redirect Url',
			'category' => 'Category',
			'type' => 'Type',
			'img' => 'Img',
			'native' => 'Native',
			'state' => 'State',
			'create_at' => 'Create At',
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
		$criteria->compare('resource_id',$this->resource_id);
		$criteria->compare('redirect_url',$this->redirect_url,true);
		$criteria->compare('category',$this->category);
		$criteria->compare('type',$this->type);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('native',$this->native,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('create_at',$this->create_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HomeWorld the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
