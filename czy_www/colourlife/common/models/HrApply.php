<?php

/**
 * This is the model class for table "hr_apply".
 *
 * The followings are the available columns in table 'hr_apply':
 * @property integer $id
 * @property integer $invite_id
 * @property string $name
 * @property string $telephone
 * @property string $address
 * @property integer $status
 * @property integer $update_user_id
 * @property string $update_date
 */
class HrApply extends CActiveRecord
{
	
	public $verifyCode;
	public $modelName = '招聘申请';
	public $start_date;
	public $end_date;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'hr_apply';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invite_id, name, telephone, address', 'required'),
			array('invite_id, status, update_user_id', 'numerical', 'integerOnly'=>true),
			array('verifyCode', 'captcha', 'on' => 'apply', 'allowEmpty' => !CCaptcha::checkRequirements()),
			array('name', 'length', 'max'=>50),
			array('telephone', 'length', 'max'=>20),
			array('address', 'length', 'max'=>500),
			array('update_date,deal_remark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, invite_id, name, telephone, address, status, update_user_id, update_date,start_date,end_date', 'safe', 'on'=>'search'),
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
			'hrInvite' => array(self::BELONGS_TO, 'HrInvite', 'invite_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'invite_id' => '招聘岗位',
			'name' => '姓名',
			'telephone' => '联系电话',
			'address' => '地址',
			'status' => '状态',
			'update_user_id' => '更新者',
			'update_date' => '更新时间',
			'deal_remark'=>'处理说明',
			'verifyCode' => '验证码',
			'apply_date'=>'申请时间',
			'apply_reason'=>'申请理由',
			'start_date'=>'从这一天',
			'end_date'=>'到这一天',
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
		$criteria->compare('invite_id',$this->invite_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('status',$this->status);
		//时间
		if ($this->start_date != '' && $this->end_date) {
			$criteria->compare("apply_date", ">=" . date("Y-m-d H:i:s",strtotime($this->start_date)) );
			$criteria->compare("apply_date", "<=" . date("Y-m-d H:i:s",strtotime($this->end_date)) );
		}
		
		$criteria->compare('update_user_id',$this->update_user_id);
		$criteria->compare('update_date',$this->update_date,true);
		$criteria->order="status asc,apply_date desc";
		
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HrApply the static model class
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
	
	/**
	 * 截取字符并hover显示全部字符串
	 * $str string 截取的字符串
	 * $len int 截取的长度
	 */
	public function getPositionString($str)
	{
		$info= $this->hrInvite->hrCompany->name."-->".$this->hrInvite->position ;
		return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void(0);', 'data-original-title' =>$info ),$str);
	}
	
	public function getStatus(){
		return array(
			0=>'未处理',
			1=>'处理中',
			2=>'拒绝',
			3=>'通过',
		);
	}
	public function getStatusName(){
		$s=array(
			0=>'未处理',
			1=>'处理中',
			2=>'拒绝',
			3=>'通过',
		);
		return $s[$this->status];
	}
	
	public function delete(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->status=2;
		$model->update_user_id=Yii::app()->user->id;
		$model->update_date=date("Y-m-d H:i:s");
		$model->save();
	}
}
