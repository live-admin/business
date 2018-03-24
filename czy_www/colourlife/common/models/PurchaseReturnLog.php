<?php

/**
 * This is the model class for table "purchase_return_log".
 *
 * The followings are the available columns in table 'purchase_return_log':
 * @property integer $id
 * @property integer $return_id
 * @property string $user_model
 * @property integer $user_id
 * @property integer $create_time
 * @property integer $status
 * @property string $note
 */
class PurchaseReturnLog extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PurchaseReturnLog the static model class
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
        return 'purchase_return_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('return_id, user_id, create_time, status', 'numerical', 'integerOnly' => true),
            array('user_model', 'length', 'max' => 50),
            array('note', 'length', 'max' => 500),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, return_id, user_model, user_id, create_time, status, note', 'safe', 'on' => 'search'),
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
            'return_id' => 'Return',
            'user_model' => 'User Model',
            'user_id' => 'User',
            'create_time' => 'Create time',
            'status' => 'Status',
            'note' => 'Note',
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
        $criteria->compare('return_id', $this->return_id);
        $criteria->compare('user_model', $this->user_model, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('status', $this->status);
        $criteria->compare('note', $this->note, true);

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
                'setUpdateOnCreate' => true,
            ),
        );
    }

}
