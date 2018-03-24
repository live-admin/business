<?php
/**
 * Created by PhpStorm.
 * User: wede
 * Date: 14-5-6
 * Time: 上午11:09
 */

class PushMessageConfig  extends  Config{

    public $valueAttributes = array('pushKey', 'secretKey','employeeApiKey','employeeSectetKey');

    public $pushKey;
    public $secretKey;
    public $employeeApiKey;
    public $employeeSectetKey;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('pushKey, secretKey,employeeApiKey,employeeSectetKey', 'safe'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'pushKey' => '彩之云API KEY',
            'secretKey' => '彩之云SECRET KEY',
            'employeeApiKey' => '彩管家API KEY',
            'employeeSectetKey' =>'彩管家 SECTET KEY',
        ));
    }

    protected function afterFind()
    {
        if (!is_array($this->val)) {
            $this->val = array();
        }
        foreach ($this->val as $name => $value) {
            if (!empty($name) && in_array($name, $this->valueAttributes)) {
                $this->$name = $value;
            }
        }
        return parent::afterFind();
    }

    protected function beforeSave()
    {
        $this->val = $this->getAttributes($this->valueAttributes);
        return parent::beforeSave();
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            array(
                'name' => 'pushKey',
            ),
            array(
                'name' => 'secretKey',
            ),
            array('name'=>'employeeSectetKey'),
            array('name'=>'employeeApiKey'),

        ));
    }

} 