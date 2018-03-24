<?php

/**
 * This is the model class for table "employee_meeting".
 *
 * The followings are the available columns in table 'employee_meeting':
 * @property integer $id
 * @property string $oa_username
 * @property string $name
 * @property string $job_name
 * @property string $mobile
 * @property string $department
 * @property string $org_level_one
 * @property string $org_level_two
 * @property string $org_level_three
 * @property string $org_level_four
 * @property string $org_level_five
 * @property string $org_level_six
 * @property integer $update_time
 */
class EmployeeMeeting extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'employee_meeting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('oa_username, name, update_time', 'required'),
			array('update_time', 'numerical', 'integerOnly'=>true),
			array('oa_username, name, job_name, department, org_level_one, org_level_two, org_level_three, org_level_four, org_level_five, org_level_six', 'length', 'max'=>250),
			array('mobile', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, oa_username, name, job_name, mobile, department, org_level_one, org_level_two, org_level_three, org_level_four, org_level_five, org_level_six, update_time', 'safe', 'on'=>'search'),
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
			'oa_username' => 'OA账号',
			'name' => '姓名',
			'job_name' => '职位名称',
			'mobile' => '电话',
			'department' => '部门',
			'org_level_one' => 'Org Level One',
			'org_level_two' => 'Org Level Two',
			'org_level_three' => 'Org Level Three',
			'org_level_four' => 'Org Level Four',
			'org_level_five' => 'Org Level Five',
			'org_level_six' => 'Org Level Six',
			'update_time' => 'Update Time',
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
		$criteria->compare('oa_username',$this->oa_username,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('job_name',$this->job_name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('department',$this->department,true);
		$criteria->compare('org_level_one',$this->org_level_one,true);
		$criteria->compare('org_level_two',$this->org_level_two,true);
		$criteria->compare('org_level_three',$this->org_level_three,true);
		$criteria->compare('org_level_four',$this->org_level_four,true);
		$criteria->compare('org_level_five',$this->org_level_five,true);
		$criteria->compare('org_level_six',$this->org_level_six,true);
		$criteria->compare('update_time',$this->update_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return EmployeeMeeting the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
