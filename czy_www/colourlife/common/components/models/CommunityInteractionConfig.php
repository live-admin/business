<?php

class CommunityInteractionConfig extends Config
{
    public $valueAttributes = array('newsAutoAudit', 'commentsAutoAudit');
    public $newsAutoAudit;
    public $commentsAutoAudit;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('newsAutoAudit, commentsAutoAudit', 'boolean', 'on' => 'update'),
        ));
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'newsAutoAudit' => '信息自动审核',
            'commentsAutoAudit' => '评论自动审核',
        ));
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), array(
            array(
                'name' => 'newsAutoAudit',
                'type' => 'boolean',
            ),
            array(
                'name' => 'commentsAutoAudit',
                'type' => 'boolean',
            ),
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
