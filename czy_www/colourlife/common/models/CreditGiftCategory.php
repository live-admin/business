<?php

/**
 * This is the model class for table "credit_gift_category".
 *
 * The followings are the available columns in table 'credit_gift_category':
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $display_order
 * @property integer $state
 * @property integer $create_time
 * @property CreditGiftCategory $parent
 */
class CreditGiftCategory extends CActiveRecord
{
    public $modelName = '积分礼品分类';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'credit_gift_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('parent_id, display_order, state', 'numerical', 'integerOnly'=>true),
            array('state', 'checkEnable', 'on' => 'enable'),
            array('state', 'checkDisable', 'on' => 'disable'),
            array('parent_id', 'checkParentEnable', 'on' => 'create'),
			array('name', 'length', 'max'=>45),
            array('id', 'checkDelete', 'on' => 'delete'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, parent_id, display_order, state, create_time', 'safe', 'on'=>'search'),
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
            'parent' => array(self::BELONGS_TO, 'CreditGiftCategory', 'parent_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => '分类名称',
			'parent_id' => '上级分类',
			'display_order' => '排序',
			'state' => '状态',
			'create_time' => '创建时间',
		);
	}

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'updateAttribute' => NULL,
                'setUpdateOnCreate' => true,
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
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
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('display_order',$this->display_order);
		$criteria->compare('state',$this->state);
		$criteria->compare('create_time',$this->create_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
            'sort' => array(
                'defaultOrder' => 'parent_id, display_order DESC'
            ),
		));
	}

    public static function getAllCreditGiftCategory()
    {
        $cdb = new CDbCriteria();
        $cdb->addCondition('state = 0');
        $cdb->order = 'parent_id, display_order DESC';
        return self::model()->findAll($cdb);
    }

    public function getParentName()
    {
        if ($this->parent === null) {
            return '-';
        }
        return $this->parent->name;
    }

    public function findEnabledChildrenByPk($id, $withoutId = 0)
    {
        return $this->enabled()->findAll('parent_id=:parent_id AND id!=:without_id', array(
            ':parent_id' => $id,
            ':without_id' => $withoutId,
        ));
    }

    public function checkParentEnable($attribute, $params)
    {
        if (!empty($this->parent_id)) {
            $parent = $this->enabled()->findByPk($this->parent_id);
            if ($parent === null) {
                $this->addError($attribute, '当前' . $this->modelName . '被禁用，无法在其下增加新' . $this->modelName);
            }
        }
    }

    public function checkEnable($attribute, $params)
    {
        if (!$this->getCanEnable()) {
            $this->addError($attribute, '因为该' . $this->modelName . '的上级' . $this->modelName . '被禁用，无法启用');
        }
    }

    protected function afterSave()
    {
        if('disable' == $this->getScenario()){
            CreditGift::model()->updateAll(array('state' => Item::STATE_YES), 'category_id = ' . $this->id);
        }
        if('enable' == $this->getScenario()){
            CreditGift::model()->updateAll(array('state' => Item::STATE_ON), 'category_id = ' . $this->id);
        }
    }

    public function checkDisable($attribute, $params)
    {
        if (!$this->getCanDisable()) {
            $this->addError($attribute, '因为该' . $this->modelName . '下还存在下级' . $this->modelName . '，无法禁用。');
        }
    }

    public function checkDelete($attribute, $params)
    {
        if(CreditGift::model()->findByAttributes(array('category_id' => $this->id))){
            $this->addError($attribute, '因为该' . $this->modelName . '下还存在积分礼品内容' . '，无法删除。');
        }
        if (!$this->getCanDelete()) {
            $this->addError($attribute, '因为该' . $this->modelName . '下还存在下级' . $this->modelName . '，无法删除。');
        }
    }

    public function checkParentExist($attribute, $params)
    {
        if (!empty($this->parent_id) && $this->findByPk($this->parent_id) === null) {
            $this->addError($attribute, '指定的上级' . $this->modelName . '不存在。');
        }
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CreditGiftCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
