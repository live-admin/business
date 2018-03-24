<?php

/**
 * This is the model class for table "complain_repairs_visit".
 *
 * The followings are the available columns in table 'complain_repairs_visit':
 * @property integer $id
 * @property integer $complain_repairs_id
 * @property integer $customer_id
 * @property string $telphone
 * @property integer $is_connect
 * @property integer $connect_time
 * @property integer $visit_time
 * @property integer $score
 * @property string $note
 */
class ComplainRepairsVisit extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
   // public $search_all;
   
   /**
     * @var string 模型名
     */
    public $modelName = '投诉报修回访';
    public $customer_tel;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ComplainRepairsVisit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'complain_repairs_visit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('complain_repairs_id, customer_id, is_connect, connect_time, visit_time, score', 'numerical', 'integerOnly'=>true),
			array('telphone', 'length', 'max'=>255),
			array('note', 'safe'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, complain_repairs_id, customer_id, telphone, is_connect, connect_time, visit_time, score, note', 'safe', 'on'=>'search'),
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
            'complain_repairs' => array(self::BELONGS_TO, 'ComplainRepairs', 'complain_repairs_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'complain_repairs_id' => 'Common Repairs',
			'customer_id' => 'Customer',
			'telphone' => 'Telphone',
			'is_connect' => 'Is Connect',
			'connect_time' => 'Connect Time',
			'visit_time' => 'Visit Time',
			'score' => 'Score',
			'note' => 'Note',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('complain_repairs_id',$this->complain_repairs_id);
		$criteria->compare('customer_id',$this->customer_id);
		$criteria->compare('telphone',$this->telphone,true);
		$criteria->compare('is_connect',$this->is_connect);
		$criteria->compare('connect_time',$this->connect_time);
		$criteria->compare('visit_time',$this->visit_time);
		$criteria->compare('score',$this->score);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function gerMobile(){
        return empty($this->complain_repairs)?"":$this->complain_repairs->customer_tel;
    }
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'visit_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }
    
}