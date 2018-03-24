<?php
/**
 * Created by PhpStorm.
 * User: ゛嗨⑩啉°
 * Date: 13-12-2
 * Time: 下午5:48
 */

class SmsInterface extends CActiveRecord
{
    public $id;
    public $key;
    public $name;
    public $description;
    public $config;
    public $update_time;
    public $modelName = '短信基本配置';
    private $_val;

    public static function model ( $className = __CLASS__ )
    {
        return parent::model ( $className );
    }

    public function tableName ()
    {
        return 'sms_interface';
    }

    public function rules ()
    {
        return array (
            array (
                'name, key',
                'safe',
                'on' => 'search'
            ),
        );
    }

    public function attributeLabels ()
    {
        return array (
            'id' => 'ID',
            'key' => '代码',
            'name' => '名称',
            'description' => '描述',
            'config' => '配置信息',
            'update_time' => '更新时间'
        );
    }

    public function attributes ()
    {
        return array (
            'name',
            'key',
            //array('name' => 'update_time', 'type' => 'localeDatetime'),
        );
    }

    public function getVal()
    {
        if (!isset($this->_val))
            $this->_val = @unserialize($this->config);
        return $this->_val;
    }

    public function setVal($val)
    {
        $this->_val = $val;
        $this->config = serialize($this->_val);
    }

    public function search ()
    {
        $criteria = new CDbCriteria();
        $criteria->compare ( '`name`', $this->name, true );
        $criteria->compare ( '`key`', $this->key, true );
        return new ActiveDataProvider( $this, array (
            'criteria' => $criteria
        ) );
    }

    public function getClassName ()
    {
        $className = ucfirst ( $this->key ) . 'Interface';
        Yii::import ( 'common.components.models.' . $className );
        return $className;
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => null,
            ),
        );
    }

    public function findByKey($key)
    {
        var_dump(self::model()->find('`key`=:key', array(':key' => $key))->specificModel);exit;
        return self::model()->find('`key`=:key', array(':key' => $key))->specificModel;
    }

    public function getSpecificModel ()
    {
        return self::model ( $this->getClassName () )->findByPk ( $this->id );
    }

    public function getSms()
    {
        $data = self::model()->findAll();
        $list = array();
        foreach( $data as $key => $val )
        {
            $list[$val->id] = $val->name;
        }
        return $list;
    }
} 