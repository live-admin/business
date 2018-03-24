<?php

    /**
     * This is the model class for table "purchase_goods_category".
     *
     * The followings are the available columns in table 'goods_category':
     * @property integer $id
     * @property integer $parent_id
     * @property string $name
     * @property integer $display_order
     * @property integer $state
     * @property integer $is_deleted
     * @property integer $create_time
     * @property integer $create_employee_id
     */
class PurchaseGoodsCategory extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    public $search_all;
    /**
     * @var string 模型名
     */
    public $modelName = '内部采购商品分类';

    public $categoryFile; //商品分类图片

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'purchase_goods_category';
    }

    public function rules()
    {
        return array(
            array('name, state', 'required', 'on' => 'create'),
            array('parentName', 'checkParentEnable', 'on' => 'create'),
            //array('parent_id', 'checkParentExist', 'on' => 'create,move'),
            //array('parent_id', 'checkParentGoodsExist', 'on' => 'create,move'),
            array('name', 'required', 'on' => 'update'),
            array('parent_id', 'checkParentNotSelf', 'on' => 'move'),
            array('state', 'checkEnable', 'on' => 'enable'),
            array('state', 'checkDisable', 'on' => 'disable'),
            array('is_deleted', 'checkDelete', 'on' => 'delete'),
            array('is_deleted', 'checkGoodsExist', 'on' => 'delete'),
            array('state', 'checkGoodsExist', 'on' => 'disable'),
            array('parent_id, state, is_deleted,display_order', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 50),
            array('id, parent_id, name, display_order, state,desc, search_all', 'safe', 'on' => 'search'),
            array('categoryFile', 'safe', 'on' => 'create,update'),
        );
    }

    public function relations()
    {
        return array(
            'parent' => array(self::BELONGS_TO, 'PurchaseGoodsCategory', 'parent_id'),
            'children' => array(self::HAS_MANY, 'PurchaseGoodsCategory', 'parent_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'parent_id' => '上级分类',
            'parentName' => '上级分类',
            'name' => '分类名称',
            'display_order' => '显示顺序',
            'state' => '状态',
            'is_deleted' => '是否被删除',
            'create_time' => '创建时间',
            'create_employee_id' => '创建人',
            'categoryFile' => '分类图片',
            'category_image' => '分类图片',
            'desc' => '分类描述',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('display_order', $this->display_order);
        $criteria->compare('state', $this->state);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('create_employee_id', $this->create_employee_id);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    protected function beforeValidate()
    {
        if (empty($this->category_image) && !empty($this->categoryFile))
            $this->category_image = '';
        return parent::beforeValidate();
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->categoryFile)) {
            $this->category_image = Yii::app()->ajaxUploadImage->moveSave($this->categoryFile, $this->category_image);
        }
        return parent::beforeSave();
    }

    public function behaviors()
    {
        return array(
            'ParentBehavior' => array('class' => 'common.components.behaviors.ParentBehavior'),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function findEnabledChildrenByPk($id, $withoutId = 0)
    {
        return $this->enabled()->findAll('parent_id=:parent_id AND id!=:without_id', array(
            ':parent_id' => $id,
            ':without_id' => $withoutId,
        ));
    }

    public function checkParentEnable($attribute, $params)
    {
        if (!empty($this->parent_id)) {
            $parent = $this->enabled()->findByPk($this->parent_id);
            if ($parent === null) {
                $this->addError($attribute, '当前' . $this->modelName . '被禁用，无法在其下增加新' . $this->modelName);
            }
        }
    }

    public function checkEnable($attribute, $params)
    {
        if (!$this->getCanEnable()) {
            $this->addError($attribute, '因为该' . $this->modelName . '的上级' . $this->modelName . '被禁用，无法启用');
        }
    }

    public function checkDisable($attribute, $params)
    {
        if (!$this->getCanDisable()) {
            $this->addError($attribute, '因为该' . $this->modelName . '下还存在下级' . $this->modelName . '，无法禁用。');
        }
    }

    public function checkDelete($attribute, $params)
    {
        if (!$this->getCanDelete()) {
            $this->addError($attribute, '因为该' . $this->modelName . '下还存在下级' . $this->modelName . '，无法删除。');
        }
    }

    public function checkParentNotSelf($attribute, $params)
    {
        if (!$this->getCanMoveToParentId($this->parent_id)) {
            $this->addError($attribute, '不能将该' . $this->modelName . '的上级' . $this->modelName . '设置为自己或自己的下级，无法转移。');
        }
    }

    public function checkParentExist($attribute, $params)
    {
        if (!empty($this->parent_id) && $this->findByPk($this->parent_id) === null) {
            $this->addError($attribute, '指定的上级' . $this->modelName . '不存在。');
        }
    }

    public function checkGoodsExist($attribute, $params)
    {
        if (PurchaseGoods::model()->findAll('category_id=:category_id', array(':category_id' => $this->id))) {
            $this->addError($attribute, '因为该' . $this->modelName . '下还存在商品' . '，无法进行该操作。');
        }
    }

    public function checkParentGoodsExist($attribute, $params)
    {
        if (PurchaseGoods::model()->findAll('category_id=:category_id', array(':category_id' => $this->parent_id))) {
            $this->addError($attribute, '因为该' . $this->modelName . '下还存在商品' . '，无法进行该操作。');
        }
    }

    public function getGoodsAllCategoryName()
    {
        return self::getMyParentCategoryName($this->id, true);
    }

    public function getParentName()
    {
        if ($this->parent === null) {
            return '-';
        }
        return $this->parent->name;
    }

    /**
     * 得到上级类别(所有上级部门的名字的一个字符串)
     * 参数：@myBranchId:类别id @isOneSelf:是否包括自身,defautl false;
     * return String
     * */
    public static function getMyParentCategoryName($myCategoryId = 0, $isOneSelf = false)
    {
        if (empty($myCategoryId)) {
            return "-";
        } else {
            $category = PurchaseGoodsCategory::model()->Enabled()->findByPk($myCategoryId);
            //如果部门不存在或上级部门不存在
            if (empty($category)) {
                return "-";
            } else {
                $categoryList = $category->getParents();
                $categoryNameStr = "";
                foreach ($categoryList as $pCategory) {
                    $categoryNameStr .= (empty($pCategory) ? "" : $pCategory->name) . " - ";
                }
                if ($isOneSelf) {
                    $categoryNameStr .= $category->name;
                }
            }
            return trim(trim($categoryNameStr), '-');
        }
    }

    public function checkChildren()
    {
        $child = $this->getChildrenIds();
        return empty($child) ? false : true;
    }

    public function getLogoUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->category_image);
    }

    public function getCategoryImgUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->category_image, '/common/images/nopic.png');
    }

    public function getCategoryByShop($shop_id)
    {
        if (empty($shop_id)) {
            return null;
        } else {
            $criteria = new CDbCriteria;
            $criteria->group = 'category_id';
            $criteria->compare('shop_id', $shop_id);

            return PurchaseGoods::model()->findAll($criteria);
        }
    }

    public static function getCategory()
    {
        $data = array();
        if($model = self::model()->findAll('state = 1')){
            foreach($model as $val)
            {
                $data[$val->id] = $val->name;
            }
        }
        return $data;
    }

}
