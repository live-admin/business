<?php

/**
 * This is the model class for table "v23_door_data".
 *
 * The followings are the available columns in table 'v23_door_data':
 * @property integer $id
 * @property integer $customer_id
 * @property string $comm_name
 * @property string $doorId
 * @property string $build_name
 * @property integer $create_time
 * @property string $note
 * @property integer $flag
 */
class V23DoorData extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'v23_door_data';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('time_consuming', 'required'),
            array('flag', 'in', 'range'=>array(0,1)),
            array('customer_id, create_time, flag', 'numerical', 'integerOnly'=>true),
            array('comm_name, doorId, build_name', 'length', 'max'=>255),
            array('time_consuming', 'length', 'max'=>10),
            array('note', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, customer_id, comm_name, doorId, build_name, create_time, note, flag, time_consuming', 'safe', 'on'=>'search'),
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
            'id' => 'id',
            'customer_id' => '接受人ID',
            'comm_name' => '小区名称',
            'doorId' => '小区名称',
            'build_name' => '小区名称',
            'create_time' => '创建时间',
            'note' => '备注',
            'flag' => '标志',
            'time_consuming' => '耗时',
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
        $criteria->compare('customer_id',$this->customer_id);
        $criteria->compare('comm_name',$this->comm_name,true);
        $criteria->compare('doorId',$this->doorId,true);
        $criteria->compare('build_name',$this->build_name,true);
        $criteria->compare('create_time',$this->create_time);
        $criteria->compare('note',$this->note,true);
        $criteria->compare('flag',$this->flag);
        $criteria->compare('time_consuming',$this->time_consuming,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return V23DoorData the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

	/**
     * @return array
     */
    public function behaviors()
    {
        return array(
        	'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => false,
            ),
        );
    }


}
