<?php

/**
 * This is the model class for table "hr_invite".
 *
 * The followings are the available columns in table 'hr_invite':
 * @property integer $id
 * @property integer $comp_id
 * @property string $position
 * @property string $department
 * @property string $pub_date
 * @property string $start_date
 * @property string $end_date
 * @property string $create_date
 * @property string $work_place
 * @property string $work_content
 * @property integer $need_person
 * @property string $work_need
 * @property string $pay
 * @property string $remark
 * @property integer $disabled
 * @property integer $isdelete
 */
class HrInvite extends CActiveRecord
{
	
	public $modelName = '招聘职位';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'hr_invite';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('comp_id, position, department, pub_date', 'required'),
			array('comp_id, need_person, disabled, isdelete', 'numerical', 'integerOnly'=>true),
			array('position, department', 'length', 'max'=>200),
			array('work_place, remark', 'length', 'max'=>500),
			array('pay', 'length', 'max'=>100),
			array('start_date, end_date, create_date, work_content, work_need', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, comp_id, position, department, pub_date, start_date, end_date, create_date, work_place, work_content, need_person, work_need, pay, remark, disabled, isdelete', 'safe', 'on'=>'search'),
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
			'hrCompany' => array(self::BELONGS_TO, 'HrCompany', 'comp_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'comp_id' => '企业名称',
			'position' => '职务',
			'department' => '部门',
			'pub_date' => '发布时间',
			'start_date' => '开始时间',
			'end_date' => '结束时间',
			'create_date' => '创建时间',
			'work_place' => '工作地点',
			'work_content' => '工作职责',
			'need_person' => '需求人数',
			'work_need' => '任职要求',
			'pay' => '薪资',
			'remark' => '备注',
			'disabled' => '禁用',
			'isdelete' => '删除',
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
		$criteria->compare('comp_id',$this->comp_id);
		$criteria->compare('position',$this->position,true);
		$criteria->compare('department',$this->department,true);
		$criteria->compare('pub_date',$this->pub_date,true);
		//$criteria->compare('start_date',$this->start_date,true);
		//$criteria->compare('end_date',$this->end_date,true);
		if ($this->start_date != '') {
            $criteria->compare("start_date", ">=" . date("Y-m-d",strtotime($this->start_date)));
        }

        if ($this->end_date != '') {
            $criteria->compare("end_date", "<=" . date("Y-m-d",strtotime($this->end_date)));
        }
		
		$criteria->compare('create_date',$this->create_date,true);
		$criteria->compare('work_place',$this->work_place,true);
		$criteria->compare('work_content',$this->work_content,true);
		$criteria->compare('need_person',$this->need_person);
		$criteria->compare('work_need',$this->work_need,true);
		$criteria->compare('pay',$this->pay,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('disabled',$this->disabled);
		$criteria->compare('isdelete',0);

		$criteria->order='create_date DESC';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HrInvite the static model class
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
		//拒绝所有此招聘下的申请
		$condition="invite_id=".$model->id;
		HrApply::model()->updateAll(array("status"=>2,"deal_remark"=>"招聘职位删除，连带拒绝所有此职位下的申请"),$condition);
	}
	
	public function disable(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->disabled=1;
		$model->save();
	}
	public function enable(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->disabled=0;
		$model->save();
	}
}
