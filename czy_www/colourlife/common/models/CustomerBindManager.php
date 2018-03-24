<?php


/**
 * This is the model class for table "jf_customer_bind_manager".
 *
 * The followings are the available columns in table 'jf_customer_bind_manager':
 * @property string $id
 * @property string $proprietor_id
 * @property string $manager_id
 * @property string $bind_time
 * @property string $unbind_time
 * @property string $state
 */
class CustomerBindManager extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'jf_customer_bind_manager';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('proprietor_id, manager_id, bind_time', 'required'),
			array('proprietor_id, manager_id, bind_time, unbind_time, state', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, proprietor_id, manager_id, bind_time, unbind_time, state', 'safe', 'on'=>'search'),
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
				'proprietor' => array(self::BELONGS_TO, 'Customer', "proprietor_id"),
				'manager' => array(self::BELONGS_TO, 'Customer', 'manager_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'proprietor_id' => '业主ID',
			'manager_id' => '客户经理ID',
			'bind_time' => '绑定时间',
			'unbind_time' => '解绑时间',
			'state' => '绑定状态', //0--未绑定, 1--已绑定, 2--已解绑
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
		$criteria->compare('proprietor_id',$this->proprietor_id,true);
		$criteria->compare('manager_id',$this->manager_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CustomerBindManager the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
