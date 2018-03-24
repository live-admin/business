<?php

/**
 * This is the model class for table "milk_examine".
 *
 * The followings are the available columns in table 'milk_examine':
 * @property integer $id
 * @property integer $customer_id
 * @property integer $create_time
 * @property string $answers1
 * @property string $answers2
 * @property string $answers3
 * @property string $answers4
 * @property string $answers5
 * @property string $answers6
 * @property integer $is_deleted
 * @property string $note
 */
class MilkExamine extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'milk_examine';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id, answers1, answers2, answers3, answers4, answers5, answers6', 'required', 'on' => 'create'),
			array('customer_id, create_time, is_deleted', 'numerical', 'integerOnly'=>true),
			array('answers1, answers2, answers3, answers4, answers5, answers6', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('note', 'safe'),
			array('id, customer_id, create_time, answers1, answers2, answers3, answers4, answers5, answers6, is_deleted, note', 'safe', 'on'=>'search'),
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
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'customer_id' => '业主id',
			'create_time' => '创建时间',
			'answers1' => '答案',
			'answers2' => '答案',
			'answers3' => '答案',
			'answers4' => '答案',
			'answers5' => '答案',
			'answers6' => '答案',
			'is_deleted' => 'Is Deleted',
			'note' => '备注',
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
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('answers1',$this->answers1,true);
		$criteria->compare('answers2',$this->answers2,true);
		$criteria->compare('answers3',$this->answers3,true);
		$criteria->compare('answers4',$this->answers4,true);
		$criteria->compare('answers5',$this->answers5,true);
		$criteria->compare('answers6',$this->answers6,true);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('note',$this->note,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MilkExamine the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function beforeSave(){
        /*一个月内禁止评论*/
	    $date = date('Y-m') . '-01';
		$hour = date('H:i:s');
		$month = 1;
		$end_date =  date('Y-m-d', strtotime($date."+{$month} month -1 day"));
	    $start_date = strtotime($date . ' 00:00:00');
	    $end_date = strtotime($end_date . ' 23:59:59');
	    $re = $this::model()->find("customer_id=:uid AND create_time>=:start_date AND create_time<=:end_date",array(':uid'=>$this->customer_id, ':start_date'=>$start_date, ':end_date'=>$end_date));
		if (!empty($re)) {
			echo json_encode(array("success"=>0,"data"=>array('msg'=>'您已经提交过了问卷调查',"errors"=>array('您已经提交过了问卷调查'))));
			exit;
		}
		return parent::beforeSave();
	}

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    
}
