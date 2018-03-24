<?php

/**
 * This is the model class for table "ef_fund_project_evolution_pic".
 *
 * The followings are the available columns in table 'ef_fund_project_evolution_pic':
 * @property string $id
 * @property string $project_evolution_id
 * @property string $thumb_pic_url
 * @property string $pic_url
 */
class FundProjectEvolutionPic extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ef_fund_project_evolution_pic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_evolution_id', 'required'),
			array('project_evolution_id', 'length', 'max'=>10),
			array('thumb_pic_url, pic_url', 'length', 'max'=>2000),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, project_evolution_id, thumb_pic_url, pic_url', 'safe', 'on'=>'search'),
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
			'project_evolution_id' => '项目进展ID',
			'thumb_pic_url' => '缩略图片',
			'pic_url' => '图片',
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
		$criteria->compare('project_evolution_id',$this->project_evolution_id,true);
		$criteria->compare('thumb_pic_url',$this->thumb_pic_url,true);
		$criteria->compare('pic_url',$this->pic_url,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FundProjectEvolutionPic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
