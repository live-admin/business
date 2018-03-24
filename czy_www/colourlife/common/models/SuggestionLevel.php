<?php

/**
 * This is the model class for table "suggestion_category".
 *
 * The followings are the available columns in table 'suggestion_category':
 * @property integer $id
 * @property string $name
 * @property integer $redpacket
 * @property string $desc
 * @property integer $state
 * @property string $is_deleted
 */
class SuggestionLevel extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = '建议级别';


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
        return 'suggestion_level';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, redpacket', 'required', 'on' => 'create, update'),
            array('state', 'numerical', 'integerOnly' => true),
            array('name, desc', 'length', 'max' => 255),
            array('is_deleted', 'length', 'max' => 50),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, redpacket, desc, state, is_deleted', 'safe', 'on' => 'search'),
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
           
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '级别名称',
            'redpacket' => '赠送红包',
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
        $criteria->compare('redpacket', $this->redpacket);
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

		}

		public function checkDisable($attribute, $params)
    {

		}

		public function checkDelete($attribute, $params)
    {

		}


    public function checkExists($attribute, $params)
    {
        $suggestion = self::model()->find('name=:name', array(':name' => $this->name));
        if (!$this->hasErrors() && !empty($suggestion)) {
            $this->addError($attribute, '因为该标题已存在，不能添加或修改。');
        }
    }



}
