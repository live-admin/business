<?php

/**
 * This is the model class for table "shop_relation".
 *
 * The followings are the available columns in table 'shop_relation':
 * @property integer $id
 * @property integer $supplier_id
 * @property integer $community_id
 * @property integer $seller_id
 * @property integer $create_time
 * @property integer $create_employee_id
 */
class ShopRelation extends CActiveRecord
{
    public $modelName = '关联商家';
    public $username;
    public $shopName;
    public $communitys = array(); //小区
    public $region; //地区
    public $community_id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopRelation the static model class
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
        return 'shop_relation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('supplier_id, seller_id,community_id', 'required'),
            array('supplier_id, seller_id,community_id, create_time, create_employee_id', 'numerical', 'integerOnly' => true),

            array('supplier_id,seller_id,community_id', 'shopCommunityRelationIsExist', 'on' => 'create'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id,username,shopName,region,community_id, communitys,supplier_id, seller_id,community_id, create_time, create_employee_id', 'safe', 'on' => 'search'),
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
            'supplier' => array(self::BELONGS_TO, 'Shop', 'supplier_id'),
            'seller' => array(self::BELONGS_TO, 'Shop', 'seller_id'),
            'community' => array(self::BELONGS_TO, 'Community', 'community_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'supplier_id' => '供应商',
            'seller_id' => '加盟商',
            'community_id' => '小区',
            'create_time' => '创建时间',
            'create_employee_id' => '创建人',
            'username' => '商家帐号',
            'shopName' => '商家名称',
            'region' => '地区',
            'communitys' => '小区',

        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($type, $id = null)
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        if ($type == 'supplier') {
            if ($id != null) {
                $criteria->compare('supplier_id', $id);
            }
            $criteria->with[] = 'seller';
            $criteria->compare("seller.name", $this->shopName, true);
            $criteria->compare("seller.username", $this->username, true);
            if (!empty($this->communitys)) {
                $criteria->addInCondition('community_id', $this->communitys);
            } else if ($this->region != '') {
                $criteria->addInCondition('community_id', Region::model()->getRegionCommunity($this->region, 'id'));
            }
        } elseif ($type == 'seller') {
            if ($id != null) {
                $criteria->compare("seller_id", $id);
            }
            $criteria->with[] = 'supplier';
            $criteria->compare("supplier.name", $this->shopName, true);
            $criteria->compare("supplier.username", $this->username, true);
            if (!empty($this->communitys)) {
                $criteria->addInCondition('community_id', $this->communitys);
            } else if ($this->region != '') {
                $criteria->addInCondition('community_id', Region::model()->getRegionCommunity($this->region, 'id'));

            }
        }
        return new CActiveDataProvider(ShopRelation::model(), array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => Yii::app()->params['pageSize'],
            ),
        ));
    }

    /*
     * 商家后台
     * */
    public function shopSearch($type = "")
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('seller_id', $this->seller_id);
        $criteria->compare('community_id', $this->community_id);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('create_employee_id', $this->create_employee_id);
        if ($type == 'supplier') {
            $criteria->addCondition('supplier_id=' . Yii::app()->user->id);
        } elseif ($type == "seller") {
            $criteria->addCondition('seller_id=' . Yii::app()->user->id);
        }
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    //根据小区Id得到供应商或者加盟商
    public function getCommunityShop($type)
    {
        $criteria = new CDbCriteria;
        if ($type == Shop::TYPE_SUPPLIER) {
            $criteria->compare('seller_id', $this->seller_id);
        } elseif ($type == Shop::TYPE_SELLER) {
            $criteria->compare('supplier_id', $this->supplier_id);
        }
        $criteria->compare('community_id', $this->community_id);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function shopCommunityRelationIsExist($attribute)
    {
        if (!$this->hasErrors()) {
            $criteria = new CDbCriteria;
            //  $criteria->addCondition('seller_id='.$this->seller_id);
            $criteria->addCondition('supplier_id=' . $this->supplier_id);
            $criteria->addCondition('community_id=' . $this->community_id);
            if (shopRelation::model()->find($criteria)) {
                $this->addError($attribute, '你输入的记录已经存在！');
                return false;
            }
            return true;
        }
        return false;
    }

    public function getShopState($shop_id = null)
    {
        if (empty($shop_id)) {
            return "";
        } else {
            $shopModel = Shop::model()->findByPk($shop_id);
            if (isset($shopModel->state)) {
                if ($shopModel->state == 1) {
                    $return = '<span class="label label-error">已禁用</span>';
                } else {
                    $return = '<span class="label label-success">已启用</span>';
                }
                return $return;
            } else {
                return "";
            }
        }
    }


    //判断存在
    public function ShopRelationIsExist($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (self::model()->find(array('seller_id = :seller_id and supplier_id = :supplier_id',
                ':seller_id' => $this->seller_id, ':supplier_id' => $this->supplier_id))
            ) {
                $this->addError($attribute, '您选择的关系已存在,请重新选择！');
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 自动处理
     */
    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => null,
                'setUpdateOnCreate' => true,
            ),
        );
    }

    public function beforeDelete()
    {
        //取消和一个供应商的关联，那么删除加盟商在该小区的销售表记录
        ShopCommunityGoodsSell::model()->deleteAll('shop_id=:seller_id AND community_id=:community_id',
            array(':seller_id' => $this->seller_id, ':community_id' => $this->community_id));
        return parent::beforeDelete();
    }

    public function afterSave()
    {
        //新增关联需要添加商品-小区-商家的关联
        $this->_addShopCommunityGoodsRelation();
        return parent::afterSave();
    }

    public function _addShopCommunityGoodsRelation()
    {
        //得到供应商的加盟商品
        $sellerGoods = Goods::model()->enabled()->passed()->onSale()->findAllByAttributes(
            array('shop_id' => $this->supplier_id, 'type' => Goods::TYPE_SELLER)
        );
        //设置供应商在该小区的加盟商能销售的商品
        foreach ($sellerGoods as $goods) {
            $sell = new ShopCommunityGoodsSell();
            $sell->shop_id = $this->seller_id;
            $sell->goods_id = $goods->id;
            $sell->community_id = $this->community_id;
            $sell->save();
        }
    }

    //根据加盟商的ID得到他的供应商
    public function getSupplierIds($seller_id)
    {
        if (empty($seller_id)) {
            return array();
        } else {
            $supplierList = ShopRelation::model()->findAllByAttributes(array('seller_id' => $seller_id));
            $ids = array();
            foreach ($supplierList as $supplier) {
                $ids[] = $supplier->supplier_id;
            }
            $ids = array_unique($ids);
            return $ids;
        }
    }

    //根据供应商的ID得到所有的关联加盟商ID集合
    public static function getSellerIds($supplier_id)
    {
        if (empty($supplier_id)) {
            return array();
        } else {
            $sellerList = ShopRelation::model()->findAllByAttributes(array('supplier_id' => $supplier_id));
            $ids = array_map(function ($rModel) {
                return $rModel->seller_id;
            }, $sellerList);
            $ids = array_unique($ids);
            return $ids;
        }
    }

}
