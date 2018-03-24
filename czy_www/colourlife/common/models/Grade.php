<?php

/**
 * This is the model class for table "grade".
 *
 * The followings are the available columns in table 'grade':
 * @property integer $id
 * @property string $name
 * @property string $img
 * @property integer $credit_start
 * @property integer $credit_end
 * @property integer $state
 * @property integer $create_time
 */
class Grade extends CActiveRecord
{
    public $start_time;
    public $end_time;
    public $credit;
    public $imgFile;
    public $modelName = '等级管理';

    const GRADE_STATE_ENABLED = 0;
    const GRADE_STATE_DISABLED = 0;

    public static $_state = array(
        self::GRADE_STATE_DISABLED => '禁用',
        self::GRADE_STATE_ENABLED => '启用'
    );

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grade';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, credit_start, credit_end', 'required'),
			array('credit_start, credit_end, state', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('img', 'length', 'max'=>255),
            array('imgFile','safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, img, credit_start, credit_end, state, create_time, start_time, end_time, credit', 'safe', 'on'=>'search'),
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

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '等级名称',
			'img' => '等级标识',
            'imgFile' => '标识图片',
			'credit_start' => '积分范围-开始',
			'credit_end' => '积分范围-结束',
			'state' => '状态 ',
			'create_time' => '创建时间',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'credit' => '积分'
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('img',$this->img,true);
        if(!empty($this->credit)){
            $criteria->addCondition('t.credit_start <= ' . $this->credit);
            $criteria->addCondition('t.credit_end >= '.$this->credit);
        }
		$criteria->compare('state',$this->state);
        if ($this->start_time != "") {
            $criteria->addCondition('create_time>=' . strtotime($this->start_time));
        }
        if ($this->end_time != "") {
            $criteria->addCondition('create_time<=' . strtotime($this->end_time . " 23:59:59"));
        }
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Grade the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    protected function beforeValidate()
    {
        if($this->credit_end <= $this->credit_start){
            $this->addError('credit_start',$this->getAttributeLabel('credit_start').'错误！');
            $this->addError('credit_end',$this->getAttributeLabel('credit_end').'错误！');
        }
        elseif($model = self::model()->findAll()){
            foreach($model as $val)
            {
                if( $this->id != $val->id){
                    if($val->credit_start <= $this->credit_start && $val->credit_end >= $this->credit_start){
                        $this->addError('credit_start',$this->getAttributeLabel('credit_start').'已重复！');
                    }
                    if($val->credit_start <= $this->credit_end && $val->credit_end >= $this->credit_end){
                        $this->addError('credit_end',$this->getAttributeLabel('credit_end').'已重复！');
                    }
                    //如果其它积分范围-开始小于现修改的积分范围-开始  则修改的积分范围-结束必须要小于其它积分范围-开始才是合理
                    if($val->credit_start > $this->credit_start && $val->credit_start < $this->credit_end){
                        $this->addError('credit_end',$this->getAttributeLabel('credit_end').'已重复！');
                    }
                }
            }
        }
        return parent::beforeValidate();
    }

    protected function beforeSave()
    {
        if (!empty($this->imgFile)) {
            $this->img = Yii::app()->ajaxUploadImage->moveSave($this->imgFile, $this->img);
        }
        return parent::beforeSave();
    }

    public function getImgUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->img, '/common/images/nopic-logo.jpg');
    }

    public function getImgFile()
    {
        return $this->img;
    }

    public function getCreditRange()
    {
        return $this->credit_start.'-'.$this->credit_end;
    }
}
