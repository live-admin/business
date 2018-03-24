<?php

/**
 * This is the model class for table "notify".
 *
 * The followings are the available columns in table 'notify':
 * @property integer $id
 * @property integer $category_id
 * @property string $title
 * @property string $contact
 * @property string $contact_html
 * @property integer $branch_id
 * @property integer $is_deleted
 * @property integer $create_time
 */
abstract class Article extends CActiveRecord
{
    public $modelName;
    public $belongModel;
    public $belongKey;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('contact,contact_html,category_id', 'required', 'on' => 'create, update'),
            array('contact_html', 'filter', 'filter' => array($obj = new CHtmlPurifier(), 'purify'), 'on' => 'create, update'),
            array('category_id, branch_id', 'numerical', 'integerOnly' => true),
            array('title', 'length', 'max' => 255, 'on' => 'create, update'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('title, branch_id', 'safe', 'on' => 'search'),
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
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            $this->belongKey . '_category' => array(self::BELONGS_TO, $this->belongModel, 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'category_id' => '分类',
            'categoryName' => '分类',
            'title' => '标题',
            'contact' => '内容',
            'contact_html' => 'HTML 内容',
            'branch_id' => '管辖部门',
            'branchName' => '管辖部门',
            'create_time' => '创建时间',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('contact', $this->contact, true);
        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        //选择的组织架构ID
        if (!empty($this->branch))
            $criteria->addInCondition('branch_id', $this->branch->getChildrenIdsAndSelf());
        else //自己的组织架构的ID
            $criteria->addInCondition('branch_id', $employee->getBranchIds());

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
        );
    }

    /**类型名称
     * @return string
     */
    public function getCategoryName()
    {
        $name = $this->belongKey . '_category';
        return isset($this->$name) ? $this->$name->name : '';
    }

    public function getBranchName()
    {
        return isset($this->branch) ? $this->branch->name : '';
    }

}
