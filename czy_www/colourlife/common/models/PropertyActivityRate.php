<?php

/**
 * This is the model class for table "property_activity_rate".
 *
 * The followings are the available columns in table 'property_activity_rate':
 * @property string $id
 * @property string $name
 * @property integer $month
 * @property string $rate
 * @property string $description
 * @property string $value
 * @property string $template
 * @property integer $state
 * @property integer $is_deleted
 */
class PropertyActivityRate extends CActiveRecord
{	

	public $modelName = '冲抵活动配置';
	public $type;
	public $countHouse;
	public $countMoney;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'property_activity_rate';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,month,rate,ratio,template,sort', 'required'),
			array('month, state, is_deleted', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('rate,ratio', 'length', 'max'=>10),
			array('sort', 'length', 'max'=>2),
			array('description, value, template', 'safe'),
			array('state', 'boolean', 'on' => 'enable, disable'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, month, rate, ratio, description, value, template, state, is_deleted', 'safe', 'on'=>'search'),
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
			'name' => '模板名称',
			'month' => '参加冲抵的月数',
			'rate' => '收益率(小数)',
			'description' => '模板描述',
			'value' => '模板需要的参数',
			'template' => '模板',
			'state' => '状态',
			'is_deleted' => '删除',
			'ratio' => '系数',
			'sort' => '排序',
		);
	}


	public function attributes()
    {
        return array(
            'name',
            'month',
            'rate',
            'ratio',
            array(
                'name' => 'state',
                'type' => 'raw',
                'value' => $this->getStateName(true),
            ),
            array(
                'name' => 'template',
                'type' => 'ntext',
            ),
            array(
                'name' => 'description',
                'type' => 'ntext',
            ),
            'value',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('month',$this->month);
		$criteria->compare('rate',$this->rate,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('template',$this->template,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('is_deleted',$this->is_deleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


	/**
     * 组件挂载
     * @return array
     */
    public function behaviors()
    {
        return array(
        	'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
        );
    }



    public function setType($data)
    {
        $this->countHouse = $data['countHouse'];
        $this->countMoney = $data['countMoney'];
    }




    protected function render($template, $params)
    {
        $keys = array_keys($params);
        $values = array_values($params);
        return str_replace($keys, $values, $template);
    }



    public function replaceMessage($data,$template)
    {   
    	$this->setType($data);
        return $this->render($template, array('{countHouse}' => $this->countHouse, '{countMoney}' => $this->countMoney));
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PropertyActivityRate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/*
	 * 得到彩富人生排序 update 20150402
	 */
	public static function rateList()
	{
		return self::model()->findAll(array('condition'=>'state=:state','params'=>array(':state'=>0), 'order' => 't.sort asc'));
	}
}
