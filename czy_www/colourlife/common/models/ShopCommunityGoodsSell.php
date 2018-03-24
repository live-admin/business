<?php

/**
 * This is the model class for table "shop_community_goods_sell".
 *
 * The followings are the available columns in table 'shop_community_goods_sell':
 * @property integer $shop_id
 * @property integer $goods_id
 * @property integer $community_id
 * @property integer $is_on_sale
 */
class ShopCommunityGoodsSell extends CActiveRecord
{
    /**
     * @var 标记是否搜索全部数据
     */
    // 如果你有上级
    // public $search_all;

    /**
     * @var string 模型名
     */
    public $modelName = '商家小区商品销售';

    public $sellName;
    public $communityName;
    public $goodsName;


    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopCommunityGoodsSell the static model class
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
        return 'shop_community_goods_sell';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('shop_id, goods_id, community_id, is_on_sale', 'numerical', 'integerOnly' => true),
            array('shop_id,goods_id,community_id', 'shopGoodsCommunityRelationIsExist', 'on' => 'create'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('sellName,goodsName,communityName,shop_id, goods_id, community_id, is_on_sale', 'safe', 'on' => 'search'),
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
            'sellShop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
            'goods' => array(self::BELONGS_TO, 'Goods', 'goods_id'),
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
        );
    }

    public function primaryKey()
    {
        return array('shop_id', 'community_id', 'goods_id');
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
            'is_on_sale' => '是否销售',
            'sellName' => '销售商',
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
        $criteria->group = 'id';

        $goodsIds = array_map(function ($cModel) {
            return $cModel->goods_id;
        }, ShopCommunityGoodsSell::model()->findAll('shop_id=:shop_id', array(':shop_id' => Yii::app()->user->id)));

        $criteria->addInCondition('id', $goodsIds);

        if ($this->sellName != '') {
            $criteria2 = new CDbCriteria();
            $criteria2->compare('name', $this->sellName, true);
            $shopIds = array_map(function ($shop) {
                return $shop->id;
            }, Shop::model()->enabled()->findAll($criteria2));
            $criteria3 = new CDbCriteria();
            $criteria3->addInCondition('shop_id', $shopIds);
            $Ids = array_map(function ($scgs) {
                return $scgs->goods_id;
            }, ShopCommunityGoodsSell::model()->findAll($criteria3));

            $criteria->addInCondition('id', $Ids);
        }

        $criteria->compare('name', $this->goodsName, true);
        $criteria->compare('is_on_sale', Goods::SALE_YES);
        $criteria->compare('audit', Goods::AUDIT_PASS);
        $criteria->compare('state', Item::STATE_ON);

        if ($this->communityName != '') {
            $criteria2 = new CDbCriteria();
            $criteria2->compare('name', $this->communityName, true);
            $communityIds = array_map(function ($community) {
                return $community->id;
            }, Community::model()->enabled()->findAll($criteria2));

            $criteria3 = new CDbCriteria();
            $criteria3->addInCondition('community_id', $communityIds);
            $Ids = array_map(function ($scgs) {
                return $scgs->goods_id;
            }, ShopCommunityGoodsSell::model()->findAll($criteria3));

            $criteria->addInCondition('id', $Ids);
        }

        return new CActiveDataProvider('Goods', array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'OnSaleBehavior' => array('class' => 'common.components.behaviors.OnSaleBehavior'),
        );
    }

    public function shopGoodsCommunityRelationIsExist($attribute)
    {
        if (!$this->hasErrors()) {
            $criteria = new CDbCriteria;
            $criteria->addCondition('shop_id=' . $this->shop_id);
            $criteria->addCondition('goods_id=' . $this->goods_id);
            $criteria->addCondition('community_id=' . $this->community_id);
            if (ShopCommunityGoodsSell::model()->find($criteria)) {
                $this->addError($attribute, '你输入的记录已经存在！');
                return false;
            }
            return true;
        }
        return false;
    }

    public function  updateShopCommunityGoodsRelation($id, $communityList, $shop_id = '')
    {
        $shopId = ($shop_id != '') ? intval($shop_id) : Yii::app()->user->id;
        //删除所有的相关记录
        $this->deleteAllByAttributes(array('goods_id' => $id, 'shop_id' => $shopId));
        return $this->saveAll($id, $communityList, $shop_id);

    }

    public function saveAll($goodsId, $communityIds = array(), $shop_id = '')
    {
        $myCommunityIds = array();
        $shopId = ($shop_id != '') ? intval($shop_id) : Yii::app()->user->id;
        if (empty($communityIds)) //如果传入的小区为空。则不需要添加关联记录。直接返回成功
            return true;

        //获得该组织架构下的所有小区
        $shop = Shop::model()->findByPk($shopId);
        
        $goods = Goods::model()->findByPk($goodsId);
        
        if (!empty($shop->branch)) {
            $myCommunityIds = $shop->branch->getBranchAllIds("Community");
        }

        foreach ($communityIds as $key => $val) {
            //如果要插入的小区ID不属于该组织架构直接跳过
            if (!in_array($val, $myCommunityIds)) {
                continue;
            }
            //保存关连关系
            $model = new self;
            $model->community_id = intval($val);
            $model->shop_id = intval($shopId);
            $model->goods_id = intval($goodsId);
            if(($goods->type==Goods::TYPE_CUSTOMER)){
            	$model->is_on_sale = 1;//如果为业主商品默认为可销售，这个标志位只控制加盟商品是否可销售
            }
            if (!$model->save())
                return false;
        }
        return true;
    }

    /**
     *更新商品在小区的销售状态
     */
    static public function updateGoodsSale($goods_id, $shop_id, $communityIds)
    {
        //根据商品和商家得到所有的小区
        $scgsList = ShopCommunityGoodsSell::model()->findAll('goods_id=:goods_id AND shop_id=:shop_id',
            array(':goods_id' => $goods_id, 'shop_id' => $shop_id));
        $community_ids = array_map(function ($scgs) {
            return $scgs->community_id;
        }, $scgsList);
        //先将所有的小区下架
        ShopCommunityGoodsSell::model()->updateAll(array('is_on_sale' => 0), 'goods_id=:goods_id AND shop_id=:shop_id',
            array(':goods_id' => $goods_id, 'shop_id' => $shop_id));
        //取小区数组的交集，保证更新的小区都是商家的,更新小区销售状态
        $criteria = new CDbCriteria();
        $criteria->addInCondition('community_id', array_intersect($communityIds, $community_ids));
        $criteria->compare('shop_id', $shop_id);
        $criteria->compare('goods_id', $goods_id);
        ShopCommunityGoodsSell::model()->updateAll(array('is_on_sale' => 1), $criteria);
    }

    static public function batchGoodsSale($goodsIds, $shop_id, $is_on_sale = 0)
    {
        $Ids = is_array($goodsIds) ? $goodsIds : array($goodsIds);
        $criteria = new CDbCriteria();
        $criteria->compare('shop_id', $shop_id);
        $criteria->addInCondition('goods_id', $Ids);
        ShopCommunityGoodsSell::model()->updateAll(array('is_on_sale' => $is_on_sale), $criteria);
    }


    public function getRegionCommunityRelation($goods_id = 0, $model = '', $regionIsDisabled = false)
    {
        //得到所有的地区
        $regionList = Region::getRegionList($regionIsDisabled);
        //得到我能操作的小区
        $communityList = self::getCommunity($goods_id);
        $rList = array();
        //循环小区得到小区所对应的所有地区
        foreach ($communityList as $key => $value) {
            $region_id = trim(trim($value['pId'], "r_"));
            $region = Region::model()->findByPk($region_id);
            if (!empty($region)) {
                $regionIds = $region->getParentIds();
                array_push($regionIds, $region_id);
            }
            $rList = array_unique(array_merge($rList, $regionIds));
        }
        $showList = array();
        //循环地区,去掉用户不能操作的地区
        foreach ($regionList as $regionData) {
            $region_id = trim(trim($regionData['id'], "r_"));
            if (in_array($region_id, $rList)) {
                array_push($showList, $regionData);
            }
        }
        //构建数据
        $list = array_merge($showList, $communityList);

        //必须补充根节点。。 否则不展示子节点
        array_push($list, array('id' => "r_0", 'pId' => "-1", 'name' => "[地区]全国", 'open' => true, 'chkDisabled' => $regionIsDisabled));
        return $list;
    }

    private function getCommunity($goods_id = 0)
    {
        $communityIds = array_map(function ($record) {
            return trim($record['community_id']);
        }, $this->findAll('shop_id=:shop_id and goods_id=:goods_id', array(':shop_id' => Yii::app()->user->id, ':goods_id' => $goods_id)));

        $criteria = new CDbCriteria;
        $criteria->addInCondition('id', $communityIds);
        $communityList = Community::model()->findAll($criteria);
        $list = array();

        $regionList = Region::getRegionList();
        $regionIds = array_map(function ($record) {
            return trim($record['id'], 'r_');
        }, $regionList);
        foreach ($communityList as $community) {
            if (!in_array($community->region_id, $regionIds)) {
                continue;
            }
            if (self::isCommunityRelation($community->id, $goods_id)) {
                $arr = array('id' => "{$community->id}", 'pId' => "r_{$community->region_id}", 'name' => "[小区]" . $community->name, 'checked' => 'true');
            } else {
                $arr = array('id' => "{$community->id}", 'pId' => "r_{$community->region_id}", 'name' => "[小区]" . $community->name);
            }

            array_push($list, $arr);
        }
        return $list;
    }

    /*
   * 根据小区加ID得到是否被数据选中
   *  @return bool
   */
    static public function isCommunityRelation($community_id, $id)
    {

        $criteria = new CDbCriteria;
        $criteria->addCondition('community_id=' . $community_id);
        $criteria->addCondition('goods_id=' . $id);
        $criteria->addCondition('Shop_id=' . Yii::app()->user->id);
        $criteria->addCondition('is_on_sale=' . Goods::SALE_YES);
        $result = ShopCommunityGoodsSell::model()->find($criteria);
        if ($result)
            return true;
        else
            return false;
    }


    public function getSaleGoods($community)
    {
        $connection = Yii::app()->db;
        $sql = "select g.* from goods as g LEFT JOIN shop_community_goods_sell as sgcr  ON g.id = sgcr.goods_id
    	WHERE  sgcr.is_on_sale =1 and g.cheap_category_id = 1  and g.audit_cheap = 1 and sgcr.community_id = {$community}";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        return ($result);
    }

}
