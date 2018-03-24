<?php
/**
 * 630感恩大促
 * Time: 2015-06-29
 */

class Colourlife630ActivityConfig  extends  Config{

    public $valueAttributes = array('sfStart', 'sfStartTime', 'sfEndTime', 'anshiStart', 'anshiStartTime','anshiEndTime', 'anshiValidTime');
    public $sfStart;
    public $sfStartTime;
    public $sfEndTime;
    public $anshiStartTime;
    public $anshiEndTime;
	public $anshiValidTime;
	public $anshiStart;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('sfStartTime,sfEndTime,sfStart,anshiStart,anshiStartTime,anshiEndTime,anshiValidTime', 'safe'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
		    'sfStart' => '顺风活动开关', 
            'sfStartTime' => '顺风活动开始时间',
            'sfEndTime' => '顺风活动结束时间',
            'anshiStartTime' => '海外直购开始时间',
            'anshiEndTime' =>'海外直购结束时间',
			'anshiValidTime' =>'海外直购码有效期',
			'anshiStart'=>'海外直购码开关',
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
                'name' => 'sfStart', 
            ),
            array(
                'name' => 'sfStartTime',
            ),
            array(
                'name' => 'sfEndTime',
            ),
            array('name'=>'anshiEndTime'),	
            array('name'=>'anshiStartTime'),
            array('name'=>'anshiValidTime'),
			array('name'=>'anshiStart'),
        ));
    }

} 