<?php

/**
 * This is the model class for table "taikang_life".
 *
 * The followings are the available columns in table 'taikang_life':
 * @property string $id
 * @property string $customer_id
 * @property string $name
 * @property string $identity
 * @property string $mobile
 * @property string $email
 * @property integer $create_time
 * @property integer $status
 * @property integer $lucky_result_id
 */
class TaikangLife extends CActiveRecord
{	


	public $modelName = "泰康人寿";

	const STATUS_WAIT = 0;      //等待处理
    const STATUS_DEAL = 1;      //已处理
    const STATUS_REFUSED = 2;   //拒绝



	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'taikang_life';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id,create_time, mobile, type, status, lucky_result_id', 'numerical', 'integerOnly'=>true),
			array('name, identity, email', 'length', 'max'=>255),
			array('mobile', 'length', 'is'=>11),
			// array('status', 'in', 'range'=>array(0,1,2)),
			array('email','email','on'=>'update'),
			array('customer_id,create_time,status,lucky_result_id', 'required'),
			array('id, customer_id, name, identity, mobile, email, type, create_time, status, lucky_result_id', 'safe', 'on'=>'search'),
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
			'customer_id' => '用户ID',
			'name' => '姓名',
			'identity' => '身份证号码',
			'mobile' => '电话',
			'email' => '电子邮箱',
			'type' => '投保类型',
			'create_time' => '创建时间',
			'status' => '状态',//'0未处理；1已处理；2拒绝',
			'lucky_result_id' => '中奖ID',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('customer_id',$this->customer_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('identity',$this->identity,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('status',$this->status);
		$criteria->compare('lucky_result_id',$this->lucky_result_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}



	public function getCustomerName(){
        return $this->customer?$this->customer->name:"";
    }
    
    public function getCustomerMobile(){
        return $this->customer?$this->customer->mobile:"";
    }

    public function getCustomerAddress(){
        if($this->customer){
            return $this->customer->CommunityAddress."-".$this->customer->BuildName."-".$this->customer->room;
        }else{
            return "";
        }
    }


    
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TaikangLife the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
