<?php

/**
 * This is the model class for table "ef_fund_project_evolution".
 *
 * The followings are the available columns in table 'ef_fund_project_evolution':
 * @property string $id
 * @property string $fund_project_id
 * @property string $title
 * @property string $content
 * @property string $create_time
 * @property FundPorject $project
 */
class FundProjectEvolution extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ef_fund_project_evolution';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('fund_project_id, title, content, create_time', 'required'),
			array('fund_project_id, create_time', 'length', 'max'=>10),
			array('title', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, fund_project_id, title, content, create_time', 'safe', 'on'=>'search'),
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
		    'evolution_pics' => array(self::HAS_MANY, "FundProjectEvolutionPic", "project_evolution_id"),
		    'project' => array(self::BELONGS_TO, "FundProject", "fund_project_id"), 
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'fund_project_id' => '项目ID',
			'title' => '进展标题',
			'content' => '进展描述',
			'create_time' => '更新时间',
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
		$criteria->compare('fund_project_id',$this->fund_project_id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FundProjectEvolution the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
