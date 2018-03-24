<?php
/**
 * 星巴克活动
 * Time: 2015-07-08
 */

class ColourlifeXBKActivityConfig  extends  Config{

    public $valueAttributes = array('xbkStart', 'xbkStartTime','xbkEndTime', 'xbkValidTime', 'xbk09Count', 'xbk10Count', 'xbk11Count', 'xbk12Count');
    public $xbkStartTime;
    public $xbkEndTime;
	public $xbkValidTime;
	public $xbkStart;
    public $xbk09Count;
    public $xbk10Count;
    public $xbk11Count;
    public $xbk12Count;
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('xbkStart,xbkStartTime,xbkEndTime,xbkValidTime,xbk09Count,xbk10Count,xbk11Count,xbk12Count', 'safe'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'xbkStartTime' => '抢购星巴克开始时间',
            'xbkEndTime' =>'抢购星巴克结束时间',
			'xbkValidTime' =>'抢购星巴克码有效期',
			'xbkStart'=>'星巴克活动开关',
            'xbk09Count'=>'星巴克活动9号数量',
            'xbk10Count'=>'星巴克活动10号数量',
            'xbk11Count'=>'星巴克活动11号数量',
            'xbk12Count'=>'星巴克活动12号数量',
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
            array('name'=>'xbkEndTime'),	
            array('name'=>'xbkStartTime'),
            array('name'=>'xbkValidTime'),
			array('name'=>'xbkStart'),
            array('name'=>'xbk09Count'),
            array('name'=>'xbk10Count'),
            array('name'=>'xbk11Count'),
            array('name'=>'xbk12Count'),
        ));
    }

} 