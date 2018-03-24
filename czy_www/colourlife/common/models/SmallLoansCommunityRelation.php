<?php

/**
 * This is the model class for table "small_loans_community_relation".
 *
 * The followings are the available columns in table 'small_loans_community_relation':
 * @property integer $small_loans_id
 * @property integer $community_id
 * @property integer $update_time
 */
class SmallLoansCommunityRelation extends CActiveRecord
{
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'small_loans_community_relation';
    }
    
    public function rules()
    {
        return array(
            array('small_loans_id, community_id', 'required'),
            array('small_loans_id, community_id, update_time', 'numerical', 'integerOnly' => true),
            array('small_loans_id, community_id', 'safe', 'on' => 'search'),
        );
    }
    
    /**
     * 自动处理
     */
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => NULL,
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
        );
    }
    
    
    public function  updateSmallloansCommunityRelation($id, $communityList)
    {
        //删除所有的相关记录
        $this->deleteAllByAttributes(array('small_loans_id' => $id));
        return $this->saveAll($id, $communityList);
    }
    
    public function saveAll($smallLoansId, $communityIds = array())
    {
        if (empty($communityIds)) //如果传入的小区为空。则不需要添加关联记录。直接返回成功
            return true;
        
        foreach ($communityIds as $key => $val) {
            //保存关连关系
            $model = new self;
            $model->community_id = intval($val);
            $model->small_loans_id = intval($smallLoansId);

            if (!$model->save())
                return false;
        }
        return true;
    }
    
    
}
