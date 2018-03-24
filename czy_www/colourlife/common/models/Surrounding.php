<?php

/**
 * This is the model class for table "surrounding".
 *
 * The followings are the available columns in table 'surrounding':
 * @property integer $id
 * @property string $name
 * @property integer $state
 * @property string $logo
 * @property integer $display_order
 * @property integer $create_time
 */
class Surrounding extends CActiveRecord
{
	public $modelName = "便民服务";
    public $logoFile;
    public $logoTips = '图片大小为100*1001';
    public $logoDefault = 'nopic.png';
    /**
     * 分类有图片 LOGO 和描述
     * @var bool
     */
    public $hasLogoAndDesc = false;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'surrounding';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state, create_time, display_order', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),
            array('logoFile', 'safe', 'on' => 'create,update'),
            array('id', 'checkDisable', 'on' => 'disable'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, state, create_time, display_order, logo', 'safe', 'on'=>'search'),
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
			'name' => '名称',
			'state' => '状态',
			'create_time' => '创建时间',
            'display_order' => '排序',
            'logo' => 'LOGO图片',
            'logoFile' => '图片LOGO',
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
        $criteria->compare('logo',$this->logo,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('create_time',$this->create_time);
        $criteria->compare('display_order',$this->display_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Surrounding the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getCreateTime(){
		return date("Y-m-d H:i:s",$this->create_time);
	}

    public function checkDisable($attribute, $param)
    {
        if (!$this->hasErrors()) {
            if(SurroundingTab::model()->findByAttributes(array('surrounding_id' => $this->id, 'state' => 0))){
                $this->addError($attribute,  '该' . $this->modelName . '下存在TAB标签页，无法被禁用。');
            }
        }
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->logoFile)) {
            $this->logo = Yii::app()->ajaxUploadImage->moveSave($this->logoFile, $this->logo);
        }
        return parent::beforeSave();
    }

    public function getLogoUrl()
    {
        return Yii::app()->imageFile->getUrl($this->logo, '/common/images/' . $this->logoDefault);
    }

    protected function beforeValidate()
    {
        if (empty($this->logo) && !empty($this->logoFile))
            $this->logo = '';
        return parent::beforeValidate();
    }

    public function behaviors()
    {
        return array(
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }
}
