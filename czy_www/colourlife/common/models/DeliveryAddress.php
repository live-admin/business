<?php

/**
 * This is the model class for table "delivery_address".
 *
 * The followings are the available columns in table 'delivery_address':
 * @property integer $id
 * @property integer $employee_id
 * @property string $name
 * @property integer $province
 * @property integer $city
 * @property integer $area
 * @property string $address
 * @property string $mobile
 * @property string $postal_code
 * @property integer $create_time
 * @property integer $default_address
 */
class DeliveryAddress extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'delivery_address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('province, city, area, address, name, mobile', 'required'),
			array('employee_id, province, city, area, create_time, default_address, mobile, postal_code', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),
			array('mobile, postal_code', 'length', 'max'=>20),
			array('address', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, employee_id, name, province, city, area, address, mobile, postal_code, create_time, default_address', 'safe', 'on'=>'search'),
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
			'employee_id' => '物业员工ID',
			'name' => '收货人姓名',
			'province' => '省',
			'city' => '市',
			'area' => '区、县',
			'address' => '地址',
			'mobile' => '联系电话号码',
			'postal_code' => '邮政编码',
			'create_time' => '创建时间',
			'default_address' => '是否默认地址',
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
		$criteria->compare('employee_id',$this->employee_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('province',$this->province);
		$criteria->compare('city',$this->city);
		$criteria->compare('area',$this->area);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('postal_code',$this->postal_code,true);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('default_address',$this->default_address);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'updateAttribute' => null,
            ),
        );
    }

    protected function beforeSave()
    {
        $this->employee_id = Yii::app()->user->id;
        if($this->default_address){
            self::model()->updateAll(array('default_address' => 0), 'employee_id = :employee_id', array(':employee_id' => $this->employee_id));
        }
        return parent::beforeSave();
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DeliveryAddress the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getAreaName(){
        $region = Region::model()->findByPk($this->area);
        return empty($region->name)?"":$region->name;
    }

    public function getCityName(){
        $region = Region::model()->findByPk($this->city);
        return empty($region->name)?"":$region->name;
    }

    public function getProvinceName(){
        $region = Region::model()->findByPk($this->province);
        return empty($region->name)?"":$region->name;
    }

    public function getGroupAddress()
    {
        return '';
    }

    public function getDetailedAddress()
    {
        $address = '';
        if(!empty($this->province) && $province = Region::model()->findByPk($this->province)){
            $address .= $province->name . '-';
        }
        if(!empty($this->city) && $city = Region::model()->findByPk($this->city)){
            $address .= $city->name . '-';
        }
        if(!empty($this->area) && $area = Region::model()->findByPk($this->area)){
            $address .= $area->name;
        }
        return $address.$this->address;
    }
}
