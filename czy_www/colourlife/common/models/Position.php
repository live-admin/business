<?php

/**
 * This is the model class for table "position".
 *
 * The followings are the available columns in table 'position':
 * @property integer $id
 * @property string $name
 * @property integer $is_deleted
 * @property integer $create_time
 * @property integer $update_time
 */
class Position extends CActiveRecord
{
    public $modelName = '职位';
    public $employee_name;
    public $tel;
    public $mobile;

    public function tableName()
    {
        return 'position';
    }

    public function rules()
    {
        return array(
            array('is_deleted, create_time, update_time', 'numerical', 'integerOnly' => true),
            array('name','required'),
            array('name', 'length', 'max' => 32),
            array('id,name', 'safe', 'on' => 'search'),
        );
    }

    public function relations()
    {
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => '职位名称',
            'is_deleted' => 'Is Deleted',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'id desc,create_time desc',
            )
        ));
    }

    public static function model($className = __CLASS__)
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
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
            'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),
        );
    }

    //判断是否有员工
    public function getIsEmployee($employee_id)
    {
        $data = EmployeePositionRelation::model()->find('employee_id=:employee_id and position_id=:position_id',
            array('employee_id'=>$employee_id,'position_id'=>$this->id));
        return empty($data)?false:true;
    }
    


}
