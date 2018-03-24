<?php

/**
 * This is the model class for table "czz_building_pic".
 *
 * The followings are the available columns in table 'czz_building_pic':
 * @property string $id
 * @property string $building_id
 * @property string $pic_url
 */
class CzzBuildingPic extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'czz_building_pic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('building_id, pic_url', 'required'),
			array('building_id', 'length', 'max'=>10),
			array('pic_url', 'length', 'max'=>400),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, building_id, pic_url', 'safe', 'on'=>'search'),
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
			'building_id' => '楼盘ID',
			'pic_url' => '图片url地址',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('building_id',$this->building_id,true);
		$criteria->compare('pic_url',$this->pic_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CzzBuildingPic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
