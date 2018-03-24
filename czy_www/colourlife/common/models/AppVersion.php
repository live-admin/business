<?php

/**
 * This is the model class for table "app_version".
 *
 * The followings are the available columns in table 'app_version':
 * @property integer $id
 * @property string $version
 * @property string $code
 * @property string $func
 * @property string $bug
 * @property double $size
 * @property integer $create_time
 */
class AppVersion extends CActiveRecord
{
    public $modelName = 'APP版本发布';
    public $start_time;
    public $end_time;

    private static  $data = array(
        'czy-android' => '彩之云android版本',
        'czy-ios' => '彩之云IOS版本',
        'cgj-android' => '彩管家android版本',
        'cgj-ios' => '彩管家IOS版本',
    );
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'app_version';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('create_time', 'numerical', 'integerOnly'=>true),
			array('size', 'numerical'),
			array('version, code', 'length', 'max'=>45),
			array('func, bug', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, version, code, func, bug, size, create_time, start_time, end_time', 'safe', 'on'=>'search'),
            array('version, code, func, bug, size', 'required', 'on' => 'create'),
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
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'version' => '版本号',
			'code' => 'APP类型',
			'func' => '主要功能',
			'bug' => '修复的BUG',
			'size' => '版本文件大小',
			'create_time' => '发布时间',
            'start_time' => '开始时间',
            'end_time' => '结束时间',
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
		$criteria->compare('version',$this->version,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('func',$this->func,true);
		$criteria->compare('bug',$this->bug,true);
		$criteria->compare('size',$this->size);
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
	 * @return AppVersion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public static function getCodeName($code = null)
    {
        if(!empty($code)){
            return self::$data[$code];
        }
        return self::$data;
    }
}
