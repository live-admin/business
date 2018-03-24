<?php

/**
 * This is the model class for table "shop_community_goods_ownership".
 *
 * The followings are the available columns in table 'shop_community_goods_ownership':
 * @property integer $shop_id
 * @property integer $goods_id
 * @property integer $community_id
 */
class ShopCommunityGoodsOwnership extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    // public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = '商家小区商品归属';

    public $shopName;
    public $communityName;
    public $goodsName;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopCommunityGoodsOwnership the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop_community_goods_ownership';
    }

    public function primaryKey()
    {
        return array('shop_id', 'community_id', 'goods_id');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('shop_id, goods_id, community_id', 'numerical', 'integerOnly' => true),
            array('shop_id,goods_id,community_id', 'shopGoodsCommunityRelationIsExist', 'on' => 'create'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('shop_id, goods_id, community_id', 'safe', 'on' => 'search'),
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
            'goodsShop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
            'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'shop_id' => '销售商ID',
            'goods_id' => '商品ID',
            'community_id' => '小区ID',
            'shopName' => '所属商家',
            'communityName' => '小区名称',
            'goodsName' => '商品名称',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('shop_id', $this->shop_id);
        $criteria->compare('goods_id', $this->goods_id);
        $criteria->compare('community_id', $this->community_id);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function shopGoodsCommunityRelationIsExist($attribute)
    {
        if (!$this->hasErrors()) {
            $criteria = new CDbCriteria;
            $criteria->addCondition('shop_id=' . $this->shop_id);
            $criteria->addCondition('goods_id=' . $this->goods_id);
            $criteria->addCondition('community_id=' . $this->community_id);
            if (ShopCommunityGoodsOwnership::model()->find($criteria)) {
                $this->addError($attribute, '你输入的记录已经存在！');
                return false;
            }
            return true;
        }
        return false;
    }

    public function  updateShopCommunityGoodsRelation($id, $communityList)
    {
        //删除所有的相关记录
        $this->deleteAllByAttributes(array('goods_id' => $id, 'shop_id' => Yii::app()->user->id));
        return $this->saveAll($id, $communityList);

    }

    public function saveAll($goodsId, $communityIds = array())
    {
        //$myCommunityIds = array();
        $shopId = Yii::app()->user->id;
        if (empty($communityIds)) //如果传入的小区为空。则不需要添加关联记录。直接返回成功
            return true;

        //获得该组织架构下的所有小区
        //$shop = Shop::model()->findByPk($shopId);
        /*if (!empty($shop->branch)) {
            $myCommunityIds = $shop->branch->getBranchAllIds("Community");
        }*/

        foreach ($communityIds as $key => $val) {
            //如果要插入的小区ID不属于该组织架构直接跳过
//            if (!in_array($val, $myCommunityIds)) {
//                continue;
//            }
            //保存关连关系
            $model = new self;
            $model->community_id = intval($val);
            $model->shop_id = intval($shopId);
            $model->goods_id = intval($goodsId);

            if (!$model->save())
                return false;
        }
        return true;
    }

}
