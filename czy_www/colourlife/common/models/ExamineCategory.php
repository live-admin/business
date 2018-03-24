<?php

/**
 * This is the model class for table "examine_category".
 *
 * The followings are the available columns in table 'examine_category':
 * @property integer $id
 * @property string $name
 * @property string $desc
 * @property integer $state
 * @property string $is_deleted
 */
class ExamineCategory extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = '调查期数';


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ExamineCategory the static model class
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
        return 'examine_category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name,desc', 'required', 'on' => 'create, update'),
            array('state', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 255),
            array('is_deleted', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, state,desc, is_deleted', 'safe', 'on' => 'search'),
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
            'desc' => '说明',
            'state' => '状态',
            'is_deleted' => '是否删除',
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
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('state', $this->state);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(

            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }



    public function checkExists($attribute, $params)
    {
        $examine = self::model()->find('name=:name', array(':name' => $this->name));
        if (!$this->hasErrors() && !empty($examine)) {
            $this->addError($attribute, '因为该标题已存在，不能添加或修改。');
        }
    }



}
