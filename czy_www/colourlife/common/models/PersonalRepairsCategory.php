<?php
Yii::import('common.components.models.Category');
/**
 * This is the model class for table "personal_repairs_category".
 *
 * The followings are the available columns in table 'personal_repairs_category':
 * @property integer $id
 * @property string $name
 * @property integer $state
 * @property integer $is_deleted
 */
class PersonalRepairsCategory extends Category
{
    public $community_ids;
    public $logoImgFile;

    public function rules()
    {
        $array = array(
            array('name,state', 'required', 'on' => 'create,update'),
            array('community_ids,name,state,logo,logoImgFile', 'safe', 'on' => 'create, update'),
            array('state', 'checkDisable', 'on' => 'disable'),
            array('is_deleted', 'checkDelete', 'on' => 'delete'),
        );
        return CMap::mergeArray(parent::rules(), $array);
    }

    public function attributeLabels()
    {
        $array = array(
            'community_ids' => '小区',
            'logoImgFile' => 'logo图',
        );
        return CMap::mergeArray(parent::attributeLabels(), $array);
    }

    public function relations()
    {
        $array = array(
            'communityCount' => array(self::STAT, 'PersonalRepairsCateCommunityRelation', 'repairs_cate_id'),
        );
        return CMap::mergeArray(parent::relations(), $array);
    }

    public function getModelName()
    {
        return '个人报修分类';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getItemModelName()
    {
        return 'PersonalRepairs';
    }

    public function getItemRelationName($enabled)
    {
        return 'personal_repairs';
    }

    public $relationKeyName = 'category_id';
    public $itemHasState = false;
    public $hasLogoAndDesc = false;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'personal_repairs_category';
    }

    /**
     * 禁用时检查小区
     * @param $attribute
     * @param $params
     */
    public function checkDisable($attribute, $params)
    {
        if (!$this->hasErrors() && !empty($this->communityCount)) {
            $this->addError($attribute, '该' . $this->modelName . '下存在关联的小区，无法被禁用。');
        }
    }

    /**
     * 删除时检查小区
     * @param $attribute
     * @param $params
     */
    public function checkDelete($attribute, $params)
    {
        if (!$this->hasErrors() && !empty($this->communityCount)) {
            $this->addError($attribute, '该' . $this->modelName . '下存在关联的小区，无法被删除。');
        }
    }

    //小区树
    public function getCommunityTreeData()
    {
        if (empty($this->id))
            return Branch::model()->findByPk(1)->getRegionCommunityRelation();
        else
            return Branch::model()->findByPk(1)->getRegionCommunityRelation($this->id, 'PersonalRepairsCateCommunityRelation');
    }

    /**
     * @param $shop_id
     * @return bool
     * 检测商家和分类是否存在关联
     */
    public function IsExitsRelation($shop_id)
    {
        if (empty($shop_id)) {
            return false;
        } else {
            $data = PersonalRepairsCateShopRelation::model()->findByAttributes(array(
                'shop_id' => $shop_id,
                'repairs_cate_id' => $this->id,
            ));
            if (empty($data)) {
                return false;
            } else {
                return true;
            }
        }
    }

    public function getLogoUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->logo);
    }

    protected function beforeValidate()
    {
        if (empty($this->logo) && !empty($this->logoImgFile))
            $this->logo = '';

        return parent::beforeValidate();
    }

    protected function beforeSave()
    {
        if (!empty($this->logoImgFile)) {
            $this->logo = Yii::app()->ajaxUploadImage->moveSave($this->logoImgFile, $this->logo);
        }
        return parent::beforeSave();
    }

    public  function delete()
    {
        if (!empty($this->logo))
            Yii::app()->ajaxUploadImage->delete($this->logo);

        return parent::delete();
    }

}
