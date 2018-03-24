<?php

/**
 * This is the model class for table "hr_company".
 *
 * The followings are the available columns in table 'hr_company':
 * @property integer $id
 * @property string $name
 * @property string $telephone
 * @property string $email
 * @property string $address
 * @property string $postcode
 * @property string $contacter
 * @property integer $isdelete
 */
class HrCompany extends CActiveRecord
{
	
	public $modelName = '招聘企业';
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'hr_company';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, telephone', 'required'),
			array('isdelete', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),
			array('telephone, postcode', 'length', 'max'=>20),
			array('email', 'length', 'max'=>100),
			array('address', 'length', 'max'=>400),
			array('contacter', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, telephone, email, address, postcode, contacter, isdelete', 'safe', 'on'=>'search'),
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
			'name' => '企业名称',
			'telephone' => '联系电话',
			'email' => '邮箱',
			'address' => '地址',
			'postcode' => '邮编',
			'contacter' => '联系人',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('postcode',$this->postcode,true);
		$criteria->compare('contacter',$this->contacter,true);
		$criteria->compare('isdelete',0);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return HrCompany the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function delete(){
		$model=$this->findByPk($this->getPrimaryKey());
		$model->isdelete=1;
		$model->save();
	}
}
