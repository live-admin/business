<?php

abstract class Category extends CActiveRecord
{
    /**
     * 模型的中文名称
     * @return mixed
     */
    abstract function getModelName();

    /**
     * 关联模型名
     * @return mixed
     */
    abstract function getItemModelName();

    /**
     * 关联属性名
     * @param $enabled 是否是启用的关联；是，需要返回不同的数据；否，不需要
     * @return mixed
     */
    abstract function getItemRelationName($enabled);

    public $relationKeyName = 'category_id';
    public $itemHasState = false;
    public $itemHasDeleted = true;
    public $relationCountName = 'Count';

    public $logoFile;
    public $logoTips = '图片大小为100*1001';
    public $logoDefault = '';

    public $hasDisPlayOrder = false;

    /**
     * 分类有图片 LOGO 和描述
     * @var bool
     */
    public $hasLogoAndDesc = false;

    private $_rules;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        if (isset($this->_rules))
            return $this->_rules;
        $this->_rules = array(
            array('name', 'required', 'on' => 'create, update'), // 增加和编辑名称必填
            array('state', 'required', 'on' => 'create'), // 增加时状态必填
           // array('state', 'checkDisable', 'on' => 'disable'), // 禁用时检查关联项目
            array('state', 'checkDelete', 'on' => 'delete'), // 删除时检查关联项目
            array('name', 'length', 'max' => 255, 'on' => 'create, update'),
            array('state', 'boolean', 'on' => 'create, enable, disable'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, state', 'safe', 'on' => 'search'),
        );
        if ($this->hasLogoAndDesc) {
            $this->_rules[] = array('logoFile', 'safe', 'on' => 'create, update'); // 增加和编辑图片文件
            $this->_rules[] = array('desc', 'length', 'max' => 255, 'on' => 'create, update'); // 增加和编辑
        }
        if($this->hasDisPlayOrder)
        {
            $this->_rules[] = array('display_order', 'safe', 'on' => 'create, update'); // 增加和编辑显示排序
            $this->_rules[] = array('display_order', 'numerical', 'integerOnly' => true, 'on' => 'create,update'); // 增加和编辑显示排序
        }
        return $this->_rules;
    }

    private $_relations;

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        if (isset($this->_relations))
            return $this->_relations;
        $this->_relations = array();
        $relation = $this->getItemRelationName(false);
        $this->_relations[$relation] = array(
            self::HAS_MANY,
            $this->getItemModelName(),
            $this->relationKeyName,
        );
        $relation .= $this->relationCountName;
        $this->_relations[$relation] = array(
            self::STAT,
            $this->getItemModelName(),
            $this->relationKeyName,
        );
        if ($this->itemHasDeleted)
            $this->_relations[$relation]['condition'] = 't.is_deleted=0';
        if ($this->itemHasState) {
            $relation = $this->getItemRelationName(true);
            $this->_relations[$relation] = array(
                self::HAS_MANY,
                $this->getItemModelName(),
                $this->relationKeyName,
                'condition' => 't.state=0',
            );
            $relation .= $this->relationCountName;
            $this->_relations[$relation] = array(
                self::STAT,
                $this->getItemModelName(),
                $this->relationKeyName,
                'condition' => 't.state=0',
            );
            if ($this->itemHasDeleted)
                $this->_relations[$relation]['condition'] .= ' AND t.is_deleted=0';
        }
        return $this->_relations;
    }

    private $_label;

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        if (isset($this->_label))
            return $this->_label;
        $this->_label = array(
            'id' => 'ID',
            'name' => '名称',
            'state' => '状态',
            'parent_id' => '上级分类',
        );
        if ($this->hasLogoAndDesc) {
            $this->_label['logo'] = $this->_label['logoFile'] = $this->_label['logoUrl'] = $this->_label['logoFileUrl'] = '图片 LOGO';
            $this->_label['desc'] = '描述';
        }
        if($this->hasDisPlayOrder)
            $this->_label['display_order'] = '显示排序';

        return $this->_label;
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

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('state', $this->state);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
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

    /**
     * 禁用时检查是否有启用的下级项目
     * @param $attribute
     * @param $params
     */
    public function checkDisable($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $relation = $this->getItemRelationName($this->itemHasState) . $this->relationCountName;
            if (!empty($this->$relation)) {
                $item = CActiveRecord::model($this->getItemModelName());
                $this->addError($attribute, $this->modelName . '下还存在' . $item->modelName . '，无法操作。');
            }
        }
    }

    /**
     * 删除时检查是否有下级项目
     * @param $attribute
     * @param $params
     */
    public function checkDelete($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $relation = $this->getItemRelationName(false) . $this->relationCountName;
            if (!empty($this->$relation)) {
                $item = CActiveRecord::model($this->getItemModelName());
                $this->addError($attribute, $this->modelName . '下还存在' . $item->modelName . '，无法操作。');
            }
        }
    }

    protected function beforeValidate()
    {
        if ($this->hasLogoAndDesc) {
            if (empty($this->logo) && !empty($this->logoFile))
                $this->logo = '';
        }
        return parent::beforeValidate();
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if ($this->hasLogoAndDesc) {
            if (!empty($this->logoFile)) {
                $this->logo = Yii::app()->ajaxUploadImage->moveSave($this->logoFile, $this->logo);
            }
        }
        return parent::beforeSave();
    }

    public function getLogoUrl()
    {
        //echo '/common/images/'.$this->logoDefault;
        if ($this->hasLogoAndDesc) {
            return Yii::app()->imageFile->getUrl($this->logo, '/common/images/' . $this->logoDefault);
        }
        return '';
    }

    public function getParentName()
    {
        if (isset($this->parent)) {
            if ($this->parent === null) {
                return '-';
            }
            return $this->parent->name;
        } else {
            return "-";
        }
    }

}
