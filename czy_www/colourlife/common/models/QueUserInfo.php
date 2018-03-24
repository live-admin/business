<?php

/**
 * This is the model class for table "que_user_info".
 *
 * The followings are the available columns in table 'que_user_info':
 * @property integer $id
 * @property string $photo
 * @property string $name
 * @property integer $age
 * @property integer $brand_age
 * @property string $pronouncement
 * @property string $record
 * @property integer $is_champion
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 */
class QueUserInfo extends CActiveRecord
{
    public $modelName = '参赛者';
    public $typeFile; //头像
    public $is_champion_arr=array(0=>'否',1=>'是');//是否冠军
    public $status_arr=array(0=>'启用',1=>'禁用');//是否启用
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'que_user_info';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pronouncement', 'required'),
			array('age, is_champion, status, create_time, update_time', 'numerical', 'integerOnly'=>true),
			array('photo', 'length', 'max'=>500),
			array('name, record', 'length', 'max'=>255),
            array('brand_age', 'length', 'max'=>10),
            array('typeFile', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, photo, name, age, brand_age, pronouncement, record, is_champion, status, create_time, update_time,typeFile', 'safe', 'on'=>'search'),
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
			'photo' => '照片',
			'name' => '姓名',
			'age' => '年纪',
			'brand_age' => '牌龄',
			'pronouncement' => '比赛宣言',
			'record' => '战绩',
			'is_champion' => '是否冠军',
			'status' => '状态',
			'create_time' => '创建时间',
			'update_time' => '修改时间',
            'typeFile'=>'头像',
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
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('age',$this->age);
		$criteria->compare('brand_age',$this->brand_age);
		$criteria->compare('pronouncement',$this->pronouncement,true);
		$criteria->compare('record',$this->record,true);
		$criteria->compare('is_champion',$this->is_champion);
		$criteria->compare('status',$this->status);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QueUserInfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    /*
     * @version 显示已有的图片或者默认的图片
     */
    public function getTypeImageUrl(){
        return Yii::app()->imageFile->getUrl($this->photo, '/common/images/nopic-map.jpg');
    }
    /**
     * @version 处理添加或者修改时候的图片
     */
    protected function beforeSave(){
        if (!empty($this->typeFile)) {
            $this->photo = Yii::app()->ajaxUploadImage->moveSave($this->typeFile, $this->photo);
        }
        return parent::beforeSave();
    }
    /*
     * @version 是否冠军
     */
    public function getIschampion()
    {
        return $this->is_champion_arr[$this->is_champion];
    }
    /*
     * @version 是否启用
     */
    public function getStatus()
    {
        return $this->status_arr[$this->status];
    }
    /*
     * @version 启用功能
     */
    public function up(){
		$model=$this->findByPk($this->getPrimaryKey());
        $model->status=0;
        $model->save();
	}
    /*
     * @version 禁用功能
     */
    public function down(){
		$model=$this->findByPk($this->getPrimaryKey());
        $model->status=1;
        $model->save();
	}
    
}
