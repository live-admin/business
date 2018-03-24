<?php

/**
 * This is the model class for table "hong_bao_uplist".
 *
 * The followings are the available columns in table 'hong_bao_uplist':
 * @property integer $id
 * @property integer $oid
 * @property string $oauser
 * @property string $realname
 * @property integer $year
 * @property integer $month
 * @property string $hbfee
 */
class HongBaoUplist extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'hong_bao_uplist';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('employee_id, oid, year, month', 'numerical', 'integerOnly'=>true),
            array('oauser', 'length', 'max'=>100),
            array('realname', 'length', 'max'=>255),
            array('hbfee', 'length', 'max'=>10),
            array('oid', 'unique', 'on' => 'create'),
            array('oid, oauser', 'required', 'on' => 'create, update'),
            array('oauser, realname', 'length', 'encoding' => 'UTF-8', 'max' => 255, 'on' => 'create, update'),  
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, employee_id, oid, oauser, realname, year, month, hbfee', 'safe', 'on'=>'search'),
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
        	'employee' => array(self::BELONGS_TO, 'Employee', 'employee_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'employee_id' => '员工ID',
            'oid' => '当前批次唯一编号',
            'oauser' => '对应OA帐号',
            'realname' => '真实姓名',
            'year' => '年',
            'month' => '月',
            'hbfee' => '红包额，为-1时未计算个人红包',
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
        $criteria->compare('employee_id',$this->employee_id);
        $criteria->compare('oid',$this->oid);
        $criteria->compare('oauser',$this->oauser,true);
        $criteria->compare('realname',$this->realname,true);
        $criteria->compare('year',$this->year);
        $criteria->compare('month',$this->month);
        $criteria->compare('hbfee',$this->hbfee,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return HongBaoUplist the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    //根据用户名判断用户是否存在
    public static function checkIsExist($oid){
        return HongBaoUplist::model()->find('oid=:oid',array(':oid'=>$oid));
    }


}
