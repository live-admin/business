<?php

/**
 * This is the model class for table "surrounding_tab".
 *
 * The followings are the available columns in table 'surrounding_tab':
 * @property integer $id
 * @property integer $surrounding_id
 * @property string $name
 * @property integer $create_time
 * @property integer $state
 * @property integer $shop_id
 * @property string $content
 * @property string $app_content
 * @property BaseSho $shop
 */
class SurroundingTab extends CActiveRecord
{
	public $modelName = "标签";
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'surrounding_tab';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('surrounding_id, name', 'required', 'on' => 'create, update'),
			array('surrounding_id, create_time', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>200),
            array('id', 'checkEnable', 'on' => 'enable'),
            array('id', 'checkDisable', 'on' => 'disable'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, surrounding_id, name, create_time', 'safe', 'on'=>'search'),
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
            'shop' => array(self::BELONGS_TO, 'BaseShop', 'shop_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'surrounding_id' => '周边优惠',
			'name' => '标签名称',
			'create_time' => '创建时间',
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
		$criteria->compare('surrounding_id',$this->surrounding_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SurroundingTab the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getCreateTime(){
		return date("Y-m-d H:i:s",$this->create_time);
	}

    public function behaviors()
    {
        return array(
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function getSurroundingName($surrounding_id = null, $html = true)
    {
        $name = '';
        if(empty($surrounding_id)){
            $surrounding_id = $this->surrounding_id;
        }
        if($model = Surrounding::model()->findByPk($surrounding_id)){
            $name = $model->name;
            if($html && Yii::app()->user->checkAccess('op_backend_surrounding_view')){
                $name = CHtml::link($model->name, '/surrounding/'.$model->id);
            }
        }
        return $name;
    }

    public function getShopName($shop_id = null, $html = true)
    {
        $name = '';
        if(empty($shop_id)){
            $shop_id = $this->shop_id;
        }
        if($model = Shop::model()->findByPk($shop_id)){
            $name = $model->name;
            if($html && Yii::app()->user->checkAccess('op_backend_shop_view')){
                $name = CHtml::link($model->name, '/shop/'.$model->id);
            }
        }
        return $name;
    }

    public function checkDisable($attribute, $param)
    {
        if (!$this->hasErrors()) {
            if(SurroundingContent::model()->findByAttributes(array('tab_id' => $this->id))){
                $this->addError($attribute,  '该' . $this->modelName . '下还存在内容，无法被禁用。');
            }
        }
    }

    public function checkEnable($attribute, $param)
    {
        if (!$this->hasErrors()) {
            if(Surrounding::model()->findByAttributes(array('id' => $this->surrounding_id, 'state' => 1))){
                $this->addError($attribute,  '该' . $this->modelName . '上的分类被禁用，无法被启用。');
            }
        }
    }

    public function getAppContent(){
        return empty($this->app_content)?"":base64_encode($this->app_content);
    }
}
