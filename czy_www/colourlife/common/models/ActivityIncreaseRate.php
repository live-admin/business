<?php

/**
 * This is the model class for table "activity_increase_rate".
 *
 * The followings are the available columns in table 'activity_increase_rate':
 * @property integer $id
 * @property integer $community_id
 * @property string $rate
 * @property integer $state
 * @property integer $is_deleted
 */
class ActivityIncreaseRate extends CActiveRecord
{	
	public $modelName = '活动涨幅';


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'activity_increase_rate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('community_id', 'checkEnable', 'on' => 'create'),
			array('rate', 'required', 'on' => 'create, update'),
            array('rate','checkRate','on' => 'create, update'),
			array('community_id', 'numerical', 'integerOnly'=>true),
			array('id, community_id, rate', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '编号',
			'community_id' => '小区',
			'rate' => '涨幅',
		);
	}

	
	public function search()
	{
		
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('community_id',$this->community_id);
		$criteria->compare('rate',$this->rate,true);
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	public function checkEnable($attribute, $params)
    {
        if (!$this->hasErrors() && (empty($this->community) || $this->community->isDisabled)) {
            $this->addError($attribute, '指定的小区不存在或被禁用，无法创建、修改或启用' . $this->modelName);
        }
    }

    public function checkRate($attribute, $params)
    {
        if (!$this->hasErrors() && ($this->rate>1 || $this->rate<=-1)) {
            $this->addError($attribute, $this->modelName .'必须在-1~1之间');
        }
    }
   
	
}
