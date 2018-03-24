<?php

/**
 * This is the model class for table "config".
 *
 * The followings are the available columns in table 'config':
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property string $type
 * @property string $value
 * @property integer $update_employee_id
 * @property integer $update_time
 */
class Config extends CActiveRecord
{

	/**
	 * @var string 模型名
	 */
	public $modelName = '基本设置';

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Config the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('key, name, type', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'key' => '键',
			'name' => '名称',
			'type' => '类型',
			'update_employee_id' => '修改人',
			'update_time' => '修改时间',
			'EmployeeName' => '修改人',
		);
	}

	public function attributes()
	{
		return array(
			'name',
			'key',
			'EmployeeName',
			array('name' => 'update_time', 'type' => 'localeDatetime'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('`key`', $this->key, true);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('type', $this->type);
		$criteria->compare('update_employee_id', $this->update_employee_id);
		$criteria->compare('update_time', $this->update_time);

		return new ActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public function behaviors()
	{
		return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => null,
			),
		);
	}

	public function getClassName()
	{
		$className = ucfirst($this->type) . 'Config';
		Yii::import('common.components.models.' . $className);
		return $className;
	}

	public function findByKey($key)
	{
		return self::model()->find('`key`=:key', array(':key' => $key))->specificModel;
	}

	public function getSpecificModel()
	{
		return self::model($this->getClassName())->findByPk($this->id);
	}

	public function getEmployeeName()
	{
		$model = Employee::model()->findByPk($this->update_employee_id);
		if (isset($model)) {
			return $model->username;
		} else
			return '';
	}

	private $_val;

	public function getVal()
	{
		if (!isset($this->_val))
			$this->_val = @unserialize($this->value);
		return $this->_val;
	}

	public function setVal($val)
	{
		$this->_val = $val;
		$this->value = serialize($this->_val);
	}

	protected function beforeSave()
	{
		$this->update_employee_id = isset(Yii::app()->user->id)
			? Yii::app()->user->id
			: 0;
		return parent::beforeSave();
	}

	protected function afterSave()
	{
		Yii::app()->config->reload();
		return parent::afterSave();
	}

	public function getNameHtml()
	{
		return CHtml::tag('span', array('rel' => 'tooltip', 'data-original-title' => $this->name), $this->name);
	}

}
