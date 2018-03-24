<?php

/**
 * This is the model class for table "others_fees_log".
 *
 * The followings are the available columns in table 'others_fees_log':
 * @property integer $id
 * @property integer $others_fees_id
 * @property string $user_model
 * @property integer $user_id
 * @property integer $create_time
 * @property integer $status
 * @property string $note
 */
class OthersFeesLog extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'others_fees_log';
    }

    public function rules()
    {
        return array(
            array('others_fees_id, note', 'required'),
            array('others_fees_id, user_id, create_time, status', 'numerical', 'integerOnly' => true),
            array('user_model', 'length', 'max' => 255),
            array('id, others_fees_id, user_model, user_id, create_time, status, note', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array();
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'others_fees_id' => 'Others Fees',
            'user_model' => 'User Model',
            'user_id' => 'User',
            'create_time' => 'Create Time',
            'status' => 'Status',
            'note' => 'Note',
        );
    }

    public function search()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('others_fees_id', $this->others_fees_id);
        $criteria->compare('user_model', $this->user_model, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('status', $this->status);
        $criteria->compare('note', $this->note, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'create_time desc',
            )

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

    static public function createOtherFeesLog($others_fees_id, $user_model, $status, $note = '', $user_id=0)
    {
        $model = new self;
        $model->others_fees_id = $others_fees_id;
        $model->user_model = $user_model;
        if(intval($user_id)!=0){
            $model->user_id = $user_id;
        }else{
            $model->user_id = Yii::app()->user->id;
        }
        $model->status = $status;
        $model->note = $note;
        return $model->save();
    }

}
