<?php

/**
 * This is the model class for table "purchase_goods".
 *
 * The followings are the available columns in table 'purchase_goods':
 * @property integer $id
 * @property integer $shop_id
 * @property integer $category_id
 * @property string $name
 * @property string $brief
 * @property string $description
 * @property integer $is_on_sale
 * @property integer $create_time
 * @property integer $update_time
 * @property string $note
 * @property string $market_price
 * @property string $purchase_price
 * @property string $cost_price
 * @property integer $state
 * @property integer $audit
 * @property integer $is_deleted
 * @property integer $update_employee_time
 * @property integer $update_employee_id
 * @property integer $display_order
 * @property string $good_image
 * @property string $description_html
 * @property string $unit
 * @property integer $sales
 * @property integer $week_sales
 * @property string $integral
 */
class PurchaseGoods extends CActiveRecord
{
    public $modelName = '内部采购商品';

    public $_old_state;

    public $_old_audit;
    public $_old_category;

    const STATE_DISABLE = 1; //商品状态-禁用
    const STATE_ENABLE = 0; //商品状态-启用
    const AUDIT_PASS = 1; //审核状态-通过
    const AUDIT_WAIT = 0; //审核状态 - 待审
    const AUDIT_OUT = 2; //审核状态-不通过
    const SALE_NO = 0; // 下架
    const SALE_YES = 1; // 上架

    public $goodFile; //商品图片
    public $_shopName; //商品图片

    public $community_ids = array();
    //以下字段仅供搜索用
    public $community = array(); //小区
    public $region; //地区

	public function tableName()
	{
		return 'purchase_goods';
	}

	public function rules()
	{
		return array(
			array('brief, description, note, description_html', 'required'),
			array('shop_id, category_id, is_on_sale, create_time,tax_point update_time, state, audit, is_deleted, update_employee_time, update_employee_id, display_order, sales, week_sales', 'numerical', 'integerOnly'=>true),
			array('name, good_image', 'length', 'max'=>255),
			array('market_price, purchase_price, cost_price, integral', 'length', 'max'=>10),
			array('unit', 'length', 'max'=>8),
            array('goodFile', 'safe', 'on'=>'create,update'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, shop_id, category_id, name, brief, description, is_on_sale, create_time, update_time, note, market_price, purchase_price, cost_price, state, audit, is_deleted, update_employee_time, update_employee_id, display_order, good_image, description_html, unit, sales, week_sales, integral', 'safe', 'on'=>'search'),
		);
	}

	public function relations()
	{
		return array(
            'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
            'employee' => array(self::BELONGS_TO, 'Employee', 'update_employee_id'),
            'category' => array(self::BELONGS_TO, 'PurchaseGoodsCategory', 'category_id'),
            'purchaseGoodsCount' => array(self::STAT, 'purchaseGoods', 'category_id', 'condition' => 't.is_deleted=0'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'shop_id' => '商家的ID',
			'category_id' => '类别',
			'name' => '商品名称',
			'brief' => '商品别名',
			'description' => '商品描述',
			'is_on_sale' => '是否上架',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
			'note' => '卖家说明',
			'market_price' => '市场价格',
			'purchase_price' => '采购价格',
			'cost_price' => '成本价格',
			'state' => '商品状态',
			'audit' => '审核状态',
			'is_deleted' => '是否被删除',
			'update_employee_time' => '操作时间',
			'update_employee_id' => '操作人',
			'display_order' => '排序',
			'good_image' => '商品图片',
			'description_html' => '描述',
			'unit' => '计量单位',
			'sales' => '总销量',
			'week_sales' => '周销量',
			'integral' => '积分',
            'goodFile' => '商品图片',
            'community_ids' => '服务范围',
            'community' => '小区',
            'region' => '地区',
            'tax_point' => '税点',
            '_shopName' => '商家',
            'region_ids' => '配送城市',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('shop_id',$this->shop_id);
		$criteria->compare('category_id',$this->category_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('brief',$this->brief,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('is_on_sale',$this->is_on_sale);
		$criteria->compare('create_time',$this->create_time);
		$criteria->compare('update_time',$this->update_time);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('market_price',$this->market_price,true);
		$criteria->compare('purchase_price',$this->purchase_price,true);
		$criteria->compare('cost_price',$this->cost_price,true);
		$criteria->compare('state',$this->state);
		$criteria->compare('audit',$this->audit);
		$criteria->compare('is_deleted',$this->is_deleted);
		$criteria->compare('update_employee_time',$this->update_employee_time);
		$criteria->compare('update_employee_id',$this->update_employee_id);
		$criteria->compare('display_order',$this->display_order);
		$criteria->compare('good_image',$this->good_image,true);
		$criteria->compare('description_html',$this->description_html,true);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('sales',$this->sales);
		$criteria->compare('week_sales',$this->week_sales);
		$criteria->compare('integral',$this->integral,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    protected function beforeValidate()
    {
        if (empty($this->good_image) && !empty($this->goodFile))
            $this->good_image = '';

        return parent::beforeValidate();
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->goodFile)) {
            $this->good_image = Yii::app()->ajaxUploadImage->moveSave($this->goodFile, $this->good_image);
        }
        return parent::beforeSave();
    }

    public function behaviors()
    {
        return array(

            'CTimestampBehavior' => array('class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_time',
                'setUpdateOnCreate' => true,
            ),
            'StateBehavior' => array('class' => 'common.components.behaviors.StateBehavior'),
            'IsDeletedBehavior' => array('class' => 'common.components.behaviors.IsDeletedBehavior'),
            'OnSaleBehavior' => array('class' => 'common.components.behaviors.OnSaleBehavior'),
            'AuditBehavior' => array('class' => 'common.components.behaviors.AuditBehavior'),
        );
    }

    public function getNameHtml()
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' => '电话:' . $this->shop->tel . ', 手机:' . $this->shop->mobile . ', 地址:' .
            $this->shop->address . ', 所属部门:' . Branch::getMyParentBranchName($this->shop->branch_id, true)), $this->shop->name);
    }
    public function getTypeTag()
    {
        $category = PurchaseGoodsCategory::getMyParentCategoryName($this->category_id, true);

        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
            'data-original-title' => '商品类别:' . $category), $category);
    }

    public function downGood()
    {
        $this->setOnSaleFlag(self::SALE_NO);
    }

    public function upGood()
    {
        $this->setOnSaleFlag(self::SALE_YES);
    }

    public function passGoods()
    {
        $this->audit = self::AUDIT_PASS;

    }

    public function outGoods()
    {
        $this->audit = self::AUDIT_OUT;
    }

    public function getIsAudit()
    {
        return $this->getAttribute('audit') == self::AUDIT_WAIT;
    }

    public function getGoodImgUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->good_image);
    }
    public function getShopName()
    {
        return empty($this->shop) ? '' : $this->shop->name;
    }

    public function getCategoryName()
    {
        return empty($this->category) ? '' : $this->category->name;
    }

    public function getShopUrl($path = '')
    {
        return empty($this->shop) ? '' : $this->shop->getUrl($path);
    }

    public function getGoodsAllCategoryName()
    {
        return PurchaseGoodsCategory::getMyParentCategoryName($this->category_id, true);
    }

    //获取商品评论数量
    public function getGoodsReviewCount()
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('model="goods"');
        $criteria->addCondition('object_id=' . $this->id);
        return GoodsReview::model()->count($criteria);
    }

    //获取商品评论
    public function getGoodsReview($id, $pageSize)
    {
        $criteria = new CDbCriteria;
        $criteria->addCondition('model="goods"');
        $criteria->addCondition('object_id=' . $id);

        $count = GoodsReview::model()->count($criteria);

        $pager = new CPagination($count);
        $pager->pageSize = $pageSize;
        $pager->applyLimit($criteria);

        return array('list' => GoodsReview::model()->findAll($criteria), 'pages' => $pager);
    }

    //前台获得商品价格
    public function getGoodsPrice()
    {
            return $this->purchase_price;
    }

    // 增加商品周销量
    static public function addWeekSales($goods_id, $num)
    {
        $week_sales = $num + self::model()->findByPk($goods_id)->week_sales;
        return self::model()->updateByPk($goods_id, array('week_sales' => $week_sales));
    }

    // 增加商品总销量
    static public function addSales($goods_id, $num)
    {
        $sales = $num + self::model()->findByPk($goods_id)->sales;
        return self::model()->updateByPk($goods_id, array('sales' => $sales));
    }

    public function getCityTreeData()
    {
        $branch = empty($this->shop->branch) ? Branch::model() : $this->shop->branch;
        return $branch->getShopRegion($this->id, 'PurchaseGoodsRegionRelation');
    }

    //APP图文详情base64
    public function getDescriptionHtml(){
        return isset($this->description_html)?base64_encode($this->description_html):"";
    }

    //APP商家介绍base64
    public function getShopHtml(){
        if(isset($this->shop)){
            return base64_encode($this->shop->desc_html);
        }else{
            return "";
        }
    }


    public function ShortGoodsName($goods_name){
        return mb_strlen($goods_name,'utf-8')>30?F::msubstr($goods_name,0,30).'...':$goods_name;
    }
}
