<?php

/**
 * This is the model class for table "lucky_activity".
 *
 * The followings are the available columns in table 'lucky_activity':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $start_date
 * @property string $end_date
 * @property integer $disable
 * @property integer $isdelete
 * @property string $create_username
 * @property integer $create_userid
 * @property string $create_date
 * @property string $update_username
 * @property integer $update_userid
 * @property string $update_date
 */
class LuckyActivity extends CActiveRecord
{
	
	public $modelName = '抽奖活动';
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lucky_activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
// 			array('name, description, start_date, end_date, create_username, create_userid, create_date,update_username, update_userid, update_date', 'required'),
			array('name, description, start_date, end_date', 'required', 'on' => 'create,update'),
			array('start_date, end_date', 'date', 'format' => 'yyyy-MM-dd', 'on' => 'create, update'),
// 			array('name, description, start_date, end_date, create_username', 'required', 'on' => 'update'),
			array('disable, isdelete, create_userid, update_userid', 'numerical', 'integerOnly'=>true),
			array('name, create_username, update_username', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, start_date, end_date, disable, isdelete, create_username, create_userid, create_date, update_username, update_userid, update_date', 'safe', 'on'=>'search'),
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
			'name' => '活动名称',
			'description' => '活动描述',
			'start_date' => '活动开始时间',
			'end_date' => '活动结束时间',
			'disable' => '禁用',
			'isdelete' => '删除状态',
			'create_username' => 'Create Username',
			'create_userid' => 'Create Userid',
			'create_date' => 'Create Date',
			'update_username' => 'Update Username',
			'update_userid' => 'Update Userid',
			'update_date' => 'Update Date',
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
		$criteria->compare('description',$this->description,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('end_date',$this->end_date,true);
		$criteria->compare('disable',$this->disable);
		//$criteria->compare('isdelete',$this->isdelete);
		$criteria->compare('isdelete',0);
		$criteria->compare('create_username',$this->create_username,true);
		$criteria->compare('create_userid',$this->create_userid);
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('update_username',$this->update_username,true);
		$criteria->compare('update_userid',$this->update_userid);
		$criteria->compare('update_date',$this->update_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LuckyActivity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 截取字符并hover显示全部字符串
	 * $str string 截取的字符串
	 * $len int 截取的长度
	 */
	public function getFullString($str, $len)
	{
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' => $str), F::sub($str, $len, $show = true, $f = '……'));
	}
	
	public function delete(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->isdelete=1;
		$model->save();
	}
	
}
