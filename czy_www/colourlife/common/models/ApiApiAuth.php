<?php

Yii::import('common.components.models.ApiAuth');

/**
 * This is the model class for table "{{api_auth}}".
 *
 * The followings are the available columns in table '{{api_api_auth}}':
 * @property integer $id
 * @property string $name
 * @property text $desc
 * @property string $secret
 * @property integer $create_time
 * @property string $create_ip
 * @property integer $update_time
 * @property string $update_ip
 * @property integer $last_time
 * @property string $last_ip
 * @property integer $expire
 * @property string $data
 */
class ApiApiAuth extends ApiAuth
{
    public $modelName ='服务对接管理';
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ApiAuth the static model class
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
        return 'api_api_auth';
    }

    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $arr =  array(
            array('name, desc', 'required'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, desc,secret, create_time, create_ip, update_time, update_ip, last_time, last_ip', 'safe', 'on' => 'search'),
        );

        return Cmap::mergeArray(parent::rules(),$arr);
    }


    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        $arr =  array(
            'name'=>'名称',
            'desc'=>'描述',
        );
        return Cmap::mergeArray(parent::attributeLabels(),$arr);
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('secret', $this->secret, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('create_ip', $this->create_ip, true);
        $criteria->compare('update_time', $this->update_time);
        $criteria->compare('update_ip', $this->update_ip, true);
        $criteria->compare('last_time', $this->last_time);
        $criteria->compare('last_ip', $this->last_ip, true);
        $criteria->compare('expire', $this->expire, true);

        return new ActiveDataProvider($this, array(
            'sort' => array(
                'defaultOrder' => 'update_time DESC',
            ),
            'criteria' => $criteria,
        ));
    }

}
