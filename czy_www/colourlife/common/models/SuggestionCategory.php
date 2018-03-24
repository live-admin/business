<?php

/**
 * This is the model class for table "suggestion_category".
 *
 * The followings are the available columns in table 'suggestion_category':
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property string $desc
 * @property integer $state
 * @property string $is_deleted
 */
class SuggestionCategory extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = '建议分类';


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SuggestionCategory the static model class
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
        return 'suggestion_category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required', 'on' => 'create, update'),
            array('parent_id, state', 'numerical', 'integerOnly' => true),
            array('name, desc', 'length', 'max' => 255),
            array('is_deleted', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, parent_id, desc, state, is_deleted', 'safe', 'on' => 'search'),
            array('parentName', 'checkEnable', 'on' => 'create'),
            array('parentName', 'checkParentExist', 'on' => 'create'),
            array('state', 'checkEnable', 'on' => 'enable'),
            array('state', 'checkDisable', 'on' => 'disable'),
            array('is_deleted', 'checkDelete', 'on' => 'delete'),
            // array('name', 'checkExists', 'on' => 'create'),
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
            'parent' => array(self::BELONGS_TO, 'SuggestionCategory', 'parent_id'),
            'enabledSuggestionCount' => array(self::STAT, 'Suggestion', 'category_id', 'condition' => 't.is_deleted=0'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '标题',
            'parent_id' => '上级分类',
            'desc' => '说明',
            'state' => '状态',
            'is_deleted' => '是否删除',
            'parentName' => '上级分类',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('state', $this->state);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(

            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    public function checkEnable($attribute, $params)
    {
        if (!$this->hasErrors() && !$this->getCanEnable()) {
            if ($this->isNewRecord) {
                $this->addError($attribute, '上级' . $this->modelName . '被禁用，无法在其下增加新' . $this->modelName);
            } else if ($attribute == 'state') {
                $this->addError($attribute, '因为该' . $this->modelName . '的上级' . $this->modelName . '被禁用，无法启用。');
            } else {
                $this->addError($attribute, '当前' . $this->modelName . '的上级' . $this->modelName . '被禁用，无法移动。');
            }
        }
    }

    public function checkParentExist($attribute, $params)
    {
        if (!$this->hasErrors() && !empty($this->parent_id) && $this->parent === null) {
            $this->addError($attribute, '指定的上级' . $this->modelName . '不存在。');
        }
    }

    public function checkDisable($attribute, $params)
    {
        if (!$this->hasErrors() && !$this->getCanDisable()) {
            $this->addError($attribute, '因为该' . $this->modelName . '下还存在下级' . $this->modelName . '，无法禁用。');
        }
        // 需要判断是否有建议
        if (!$this->hasErrors() && !empty($this->enabledSuggestionCount)) {
            $this->addError($attribute, '因为该' . $this->modelName . '下还存在建议，无法禁用。');
        }

    }

    public function checkDelete($attribute, $params)
    {
        if (!$this->hasErrors() && !$this->getCanDelete()) {
            $this->addError($attribute, '因为该' . $this->modelName . '下还存在下级' . $this->modelName . '，无法删除。');
        }
        // 需要判断是否有物业用户
        if (!$this->hasErrors() && !empty($this->enabledSuggestionCount)) {
            $this->addError($attribute, '因为该' . $this->modelName . '下还存在建议，无法删除。');
        }

    }

    public function checkExists($attribute, $params)
    {
        $suggestion = self::model()->find('name=:name', array(':name' => $this->name));
        if (!$this->hasErrors() && !empty($suggestion)) {
            $this->addError($attribute, '因为该标题已存在，不能添加或修改。');
        }
    }

    public function getParentName()
    {
        if ($this->parent === null) {
            return '-';
        }
        return $this->parent->name;
    }

}
