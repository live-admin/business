<?php
/**
 * Created by PhpStorm.
 * User: jayjiang
 * Date: 13-11-27
 * Time: 下午3:34
 */

class PersonalRepairsStateConfig extends  Config{
    public $valueAttributes = array('create','execution','two_execution','finish', 'comment_end', 'stoped');
    public $create;
    public $execution;
    public $two_execution;
    public $finish;
    public $comment_end;
    public $stoped;

    public static function model($className = __CLASS__){
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('create, execution, two_execution, finish, comment_end, stoped', 'safe', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'create'=>"等待处理",
            'execution' => '处理中1',
            'two_execution' => '处理中2',
            'finish' => '处理完成',
            'comment_end' => '评论完成',
            'stoped' => '已经结束'
        ));
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            'create','execution',
            'two_execution',
            'finish',
            'comment_end',
            'stoped'
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

} 