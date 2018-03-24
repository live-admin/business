<?php

/**
 * This is the model class for table "sms_count".
 *
 * The followings are the available columns in table 'sms_count':
 * @property integer $id
 * @property string $mobile
 * @property string $model
 * @property string $message
 * @property integer $sms_type
 * @property integer $create_time
 * @property integer $status
 */
class SmsLog extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'sms_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('mobile', 'required'),
            array('create_time', 'numerical', 'integerOnly'=>true),
            array('mobile', 'length', 'max'=>15),
            array('mobile', 'common.components.validators.RegChangePassworSms','on' => 'register, resetPassword' ),
            array('mobile', 'common.components.validators.BindColourLifeSms','on' => 'bindDing' ),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, mobile,model,sms_type,message,status,create_time', 'safe', 'on'=>'search'),
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
        'mobile' => 'Mobile',
        'create_time' => 'Create Time',
        'model'=>'发送短信用户类型',
        'sms_type'=>'短信商类型',
        'message'=>'发送的短信内容',
        'status'=>'短信状态',
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
        $criteria->compare('mobile',$this->mobile,true);
        $criteria->compare('create_time',$this->create_time);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SmsCount the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}
