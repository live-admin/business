<?php

/**
 * This is the model class for table "personal_repairs_cate_community_relation".
 *
 * The followings are the available columns in table 'personal_repairs_cate_community_relation':
 * @property integer $repairs_cate_id
 * @property integer $community_id
 * @property integer $update_time
 */
class PersonalRepairsCateCommunityRelation extends CActiveRecord
{
    public function primaryKey()
    {
        return array('repairs_cate_id', 'community_id');
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'personal_repairs_cate_community_relation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('repairs_cate_id, community_id, update_time', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('repairs_cate_id, community_id, update_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'repairsCategory'=>array(self::BELONGS_TO,'PersonalRepairsCategory','repairs_cate_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'repairs_cate_id' => 'Repairs Cate',
            'community_id' => 'Community',
            'update_time' => 'Update Time',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('repairs_cate_id', $this->repairs_cate_id);
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('update_time', $this->update_time);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PersonalRepairsCateCommunityRelation the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function  updateRepairsCateCommunityRelation($repairsCateId, $communityList)
    {
        //删除所有的相关记录
        $this->deleteAllByAttributes(array('repairs_cate_id' => $repairsCateId));
        return $this->saveAll($repairsCateId, $communityList);

    }

    public function saveAll($repairsCateId, $communityIds = array())
    {
        if (empty($communityIds)) //如果传入的小区为空。则不需要添加关联记录。直接返回成功
            return true;

        foreach ($communityIds as $key => $val) {
            //保存关连关系
            $model = new self;
            $model->community_id = intval($val);
            $model->repairs_cate_id = intval($repairsCateId);

            if (!$model->save())
                return false;
        }
        return true;
    }


    //根据小区获得个人报修分类
    public function getCategoryByCommunity($community_id){
        $relationList = self::model()->findAllByAttributes(array('community_id'=>$community_id));
        if(empty($relationList)){
            $ids = array();
        }else{
            $ids = array_map(function($relat){
                return $relat->repairs_cate_id;
            },$relationList);
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id',$ids);
        $categoryList = PersonalRepairsCategory::model()->findAll($criteria);
        return CHtml::listData($categoryList,'id','name','logoUrl');
    }

    //根据小区获得个人报修分类 返回数组对像
    public function getCategoryCommunity($community_id){
        $relationList = self::model()->findAllByAttributes(array('community_id'=>$community_id));
        if(empty($relationList)){
            $ids = array();
        }else{
            $ids = array_map(function($relat){
                return $relat->repairs_cate_id;
            },$relationList);
        }
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id',$ids);
        $categoryList = PersonalRepairsCategory::model()->findAll($criteria);
        return $categoryList;
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => Null,
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function getCateCommunity($community_id,$cate_id){
        $retrun  = false;
        $relationList = self::model()->findAllByAttributes(array('community_id'=>$community_id,'repairs_cate_id'=>$cate_id));
        if(!empty($relationList)){
            $category = PersonalRepairsCategory::model()->findByPk($cate_id);
            if(!empty($category)&&$category->is_deleted==0&&$category->state==0){
                $retrun =  true;
            }
        }
        return $retrun ;
    }

}
