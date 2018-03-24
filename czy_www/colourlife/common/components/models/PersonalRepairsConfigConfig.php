<?php
/**
 * Created by PhpStorm.
 * User: jayjiang
 * Date: 13-11-27
 * Time: 下午3:34
 */

class PersonalRepairsConfigConfig  extends  Config{

    public $valueAttributes = array('communityLeader', 'secondSendTime', 'automaticSupervisoryPositions');
    public $secondSendTime; //第二次发送时间
    public $communityLeader; //小区主任对应的职位ID
    public $automaticSupervisoryPositions; //第一次自动监督职位设置

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('secondSendTime,automaticSupervisoryPositions', 'required', 'on' => 'update'),
            array('communityLeader', 'safe', 'on' => 'update'),
            array('secondSendTime', 'numerical', 'integerOnly' => true, 'on' => 'update')
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'secondSendTime' => '监控超时时间',
            'communityLeader'=>'超时自动指派职位',
            'automaticSupervisoryPositions' => '第一次自动监督职位',
        ));
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            array('name'=>'communityLeader','value'=>$this->getCommunityLeaderName(),'type'=>'raw'),
            array('name' => 'automaticSupervisoryPositions', 'value' => $this->getAutomaticSupervisoryPositionsName(), 'type' => 'raw'),
            array('name' => 'secondSendTime', 'type' => 'timeValue'),

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

    //取得员工职位
    public function getPositions()
    {
        return CHtml::listData(Position::model()->findAll(), 'id', 'name');
    }


    public function getPositionsName()
    {
        $str = '';
        if (is_array($this->secondSenderPosition)) {
            foreach ($this->secondSenderPosition as $secondSenderPosition) {
                $position = Position::model()->findByPk(intval($secondSenderPosition));
                if (!empty($position))
                    $str .= $position->name .' , ';
            }
        }
        return $str;
    }

    public function getCommunityLeaderName()
    {
        $position = Position::model()->findByPk(intval($this->communityLeader));
        if(empty($position)){
            return null;
        }else{
            return $position->name;
        }
    }

    public function getAutomaticSupervisoryPositionsName()
    {
        $position = Position::model()->findByPk(intval($this->automaticSupervisoryPositions));
        if (empty($position)) {
            return null;
        } else {
            return $position->name;
        }
    }
}