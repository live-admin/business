<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Cao Hui
 * Date: 13-8-27
 * Time: 下午3:48
 * 小区关联表的控制类
 */

class RelationLib
{
    //模型
    public $model;
    //模型名
    public $modelName;
    //关系的模型名
    public $relationModel;
    //小区ID
    public $community_id;
    //小区的模型
    public $communityModel;
    //关系的模型里的字段名
    public $fieldName;

    //初始化对象
    public function __construct($model, $id, $fieldName)
    {
        if (empty($model))
            throw new CHttpException(404, '请确认您传了model');

        try {
            $this->model = new $model();
            $this->modelName = $model;
            $this->relationModel = $model . 'CommunityRelation'; //关连表
            $this->community_id = $id;
            $this->fieldName = $fieldName;
            $this->communityModel = Community::model()->findByPK($this->community_id);
        } catch (Exception $e) {
            throw new CHttpException(404, '请确认您传了正确的model');
        }
    }

    //创建时更改关联表
    public function updateRelationByCreated()
    {
        $this->addRelation();
    }

    public function updateRelationByEdit()
    {
        $this->delRelation();
        $this->addRelation();
    }

    public function updateRelationByDeleted()
    {
        $this->delRelation();
    }

    public function updateRelationByEnabled()
    {
        $this->addRelation();
    }

    public function updateRelationByDisabled()
    {
        $this->delRelation();
    }

    private function delRelation()
    {
        $model = new $this->relationModel;
        $model->deleteAll('community_id=:community_id', array(':community_id' => $this->community_id));
    }

    private function addRelation()
    {
        if ($this->communityModel->state == 1) { //如果被禁用，则不再创建关联
            return;
        }
        $branch_id = $this->communityModel->branch_id;
        $branch = Branch::model()->findByPk($branch_id);

        $fieldName = $this->fieldName;
        //得到小区与关联表的关联数据
        $dataList = $branch->getBranchAllParentData($this->modelName);
        foreach ($dataList as $data) {
            //---------------------自动创建关系表----------------
            if ($data->is_auto_chance_community) {
                $model = new $this->relationModel;
                $model->$fieldName = $data->id;
                $model->community_id = $this->community_id;
                $model->save();
            }
        }
    }
}