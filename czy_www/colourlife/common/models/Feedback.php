<?php

/**
 * This is the model class for table "Feedback".
 *
 * The followings are the available columns in table 'Feedback':
 * @property integer $id
 * @property string $answers
 * @property string $note
 * @property integer $create_time
 * @property integer $customer_id
 */
class Feedback extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Feedback the static model class
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
        return 'feedback';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('customer_id', 'required', 'on' => 'create'),
            array('create_time, customer_id', 'numerical', 'integerOnly' => true),
            array('answers', 'length', 'max' => 255),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('note', 'safe'),
            array('id,type, answers, note, create_time, customer_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'answers' => 'Answers',
            'note' => 'Note',
            'create_time' => 'Create Time',
            'customer_id' => 'Customer',
            'type' => '类型',
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
        $criteria->compare('answers', $this->answers, true);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('customer_id', $this->customer_id);

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
        );
    }

}
