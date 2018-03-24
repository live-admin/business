<?php

/**
 * This is the model class for table "examine_question".
 *
 * The followings are the available columns in table 'examine_question':
 * @property integer $id
 * @property integer $category_id
 * @property string $question
 * @property integer $create_time
 * @property integer $desc
 * @property integer $state
 * @property integer $is_deleted
 */
class ExamineQuestion extends CActiveRecord
{


    public $modelName = '调查问题';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ExamineQuestion the static model class
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
        return 'examine_question';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('category_id,question,desc', 'required', 'on' => 'create'),
            array('create_time, category_id,desc', 'numerical', 'integerOnly' => true),
            array('question', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('question', 'safe'),
            array('desc', 'checkCRepeat','on' => 'create'),
            array('desc', 'checkURepeat','on' => 'update'),
            array('id,category_id, question, desc,state, create_time', 'safe', 'on' => 'search'),
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
            'category' => array(self::BELONGS_TO, 'ExamineCategory', 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'question' => '问题',
            'state' => '状态',
            'desc' => '唯一排序码',
            'create_time' => '创建时间',
            'category_id' => '问卷期数',
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
        $criteria->compare('question', $this->question, true);
        $criteria->compare('state', $this->state);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('desc', $this->desc);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    public function checkURepeat($attribute)
    {
        $examine = self::model()->find('`t`.desc=:desc AND `t`.category_id=:category_id AND `t`.id<>:id', array(':desc' => $this->desc,':category_id' => $this->category_id,':id' => $this->id));
        if (!$this->hasErrors() && !empty($examine)) {
            $this->addError($attribute, '因为该调查期已存在相同排序码，不能添加或修改。');
        }
    }

    public function checkCRepeat($attribute)
    {
        $examine = self::model()->find('`t`.desc=:desc AND `t`.category_id=:category_id', array(':desc' => $this->desc,':category_id' => $this->category_id));
        if (!$this->hasErrors() && !empty($examine)) {
            $this->addError($attribute, '因为该调查期已存在相同排序码，不能添加或修改。');
        }
    }

}
