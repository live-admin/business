<?php

/**
 * This is the model class for table "goods".
 *
 * The followings are the available columns in table 'goods':
 * @property integer $id
 * @property integer $shop_id
 * @property integer $type
 * @property integer $category_id
 * @property string $name
 * @property string $brief
 * @property string $description
 * @property integer $is_on_sale
 * @property integer $create_time
 * @property integer $update_time
 * @property string $note
 * @property string $shop_price
 * @property string $customer_price
 * @property integer $state
 * @property integer $audit
 * @property integer $is_deleted
 * @property integer $update_employee_time
 * @property integer $update_employee_id
 * @property integer $cheap_category_id
 * @property string $cheap_price
 * @property integer $start_cheap_time
 * @property integer $end_cheap_time
 * @property integer $display_order
 * @property integer $audit_cheap
 * @property string $good_image
 * @property string $description_html
 * @property string $unit
 * @property integer $sales
 * @property integer $week_sales
 * @property integer $cheap_pic
 * @property integer $market_price
 * @property integer $integral
 * @property integer $integra_price
 */
class Goods extends CActiveRecord
{
    /**
     * @var string 模型名
     */
    public $modelName = '商品';

    public $_shopName;

    public $_old_state;

    public $_old_audit;

    public $_old_audit_cheap;

    public $_old_category; //商品类别

    public $_old_cheap_category; //商品优惠类型
    const TYPE_SELLER = 0; //加盟商品
    const TYPE_CUSTOMER = 1; //业主商品
    const STATE_DISABLE = 1; //商品状态-禁用
    const STATE_ENABLE = 0; //商品状态-启用
    const AUDIT_PASS = 1; //审核状态-通过
    const AUDIT_WAIT = 0; //审核状态 - 待审
    const AUDIT_OUT = 2; //审核状态-不通过
    const AUDIT_CHEAP_YES = 1; //推荐
    const AUDIT_CHEAP_WAIT = 0; //待推荐
    const AUDIT_CHEAP_NO = 2; //不推荐
    const IS_CHEAP_NO = 0; //商家不申请优惠
    const IS_CHEAP_YES = 1; //商家申请天天特价
    const SALE_NO = 0; // 下架
    const SALE_YES = 1; // 上架
    const TICKETMALL_ORDER=1000;
    //适用平台
    public $ping_tai_arr=array('0'=>'请选择','1'=>'平台通用','2'=>'京东特供','3'=>'环球精选',4=>'大闸蟹', 1000=>'饭票商城');
    public $goods_type_arr=array('0'=>'请选择','1'=>'实物','2'=>'虚物');
    public $jia_jian_arr=array('1'=>'加价','2'=>'减价');
    public $bi_num_arr=array('1'=>'比例','2'=>'数量');

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Goods the static model class
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
        return 'goods';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('id, shop_id,_shopName, type, category_id, name, brief, description, is_on_sale, create_time, update_time,
                    note, shop_price, customer_price, e, audit, is_deleted, update_employee_time, update_employee_id,
                    cheap_category_id, cheap_price, start_cheap_time, end_cheap_time, display_order, audit_cheap, score,
                    good_image,unit,sales,week_sales,description_html,cheap_pic,market_price,integral,integralPrice,ping_tai,goods_type,ku_cun,sku,name', 'safe', 'on' => 'search,update,create'),
            array('couponNo', 'length', 'max' => 20)
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'shop' => array(self::BELONGS_TO, 'Shop', 'shop_id'),
            'category' => array(self::BELONGS_TO, 'GoodsCategory', 'category_id'),
            'employee' => array(self::BELONGS_TO, 'Employee', 'update_employee_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => '商品ID',
            'shop_id' => '所属商家',
            'type' => '商品类型',
            'category_id' => '商品类别',
            'name' => '商品名称',
            '_shopName' => '所属商家',
            'brief' => '商品简介',
            'description' => '商品描述',
            'is_on_sale' => '是否上架',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'note' => '卖家备注',
            'shop_price' => '加盟商价格',
            'customer_price' => '饭票价',
            'state' => '商品状态',
            'audit' => '审核状态',
            'is_deleted' => '是否被删除',
            'update_employee_time' => '操作时间',
            'update_employee_id' => '操作人',
            //'is_cheap' => '是否特价',
            'cheap_category_id' => '优惠类型',
            'audit_cheap' => '审核特价',
            'display_order' => '优先级',
            'cheap_price' => '优惠价格',
            'start_cheap_time' => '开始时间',
            'end_cheap_time' => '结束时间',
            'good_image' => '商品图片',
            'startTime' => '开始时间',
            'endTime' => '结束时间',
            'score' => '评分',
            'description_html' => '商品 HTML 描述',
            'unit' => '计量单位',
            'sales' => '总销量',
            'week_sales' => '周销量',
            'cheap_pic' => '天天特价图',
            'community_ids' => '服务范围',
            'region' => '地区',
            'community' => '小区',
            'market_price' => '人民币',
            'integral' => '积分价格',
            'integralPrice' => '可使用积分支付金额',
            'couponNo' => '优惠券码',
            'couponNos'=>'优惠券码',
//            'limit_num'=>'限购数量',
            'ping_tai'=>'适用平台',
            'goods_type'=>'商品属性',
            'ku_cun'=>'库存',
            'sku'=>'京东商品ID',
            'jd_price'=>'协议价',
            
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {

        $criteria = new CDbCriteria;

        if ($this->_shopName != '') {
            $criteria->with[] = 'shop';
            $criteria->compare('shop.name', $this->_shopName, true);
        }
        $criteria->compare('type', $this->type);
        if ($this->category !== null)
            $criteria->addInCondition('category_id', $this->category->childrenIdsAndSelf);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('brief', $this->brief, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('is_on_sale', $this->is_on_sale);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);
        $criteria->compare('note', $this->note, true);
        $criteria->compare('shop_price', $this->shop_price, true);
        $criteria->compare('market_price', $this->market_price, true);
        $criteria->compare('customer_price', $this->customer_price, true);
        $criteria->compare('state', $this->state);
        $criteria->compare('audit', $this->audit);
        $criteria->compare('update_employee_time', $this->update_employee_time);
        $criteria->compare('update_employee_id', $this->update_employee_id);
        //$criteria->compare('is_cheap',$this->is_cheap);
        $criteria->compare('cheap_category_id', $this->cheap_category_id);
        $criteria->compare('cheap_price', $this->cheap_price, true);
        $criteria->compare('start_cheap_time', $this->start_cheap_time);
        $criteria->compare('end_cheap_time', $this->end_cheap_time);
        $criteria->compare('display_order', $this->display_order);
        $criteria->compare('audit_cheap', $this->audit_cheap);
        $criteria->compare('score', $this->score);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getMySupplierGoods()
    {
        $criteria = new CDbCriteria;
        $shopRelation = new ShopRelation();
        $ids = $shopRelation->getSupplierIds(Yii::app()->user->id);
        if ($this->category !== null)
            $criteria->addInCondition('`t`.category_id', $this->category->childrenIdsAndSelf);
        $criteria->compare('`t`.name', $this->name, true);
        $criteria->compare('`t`.is_on_sale', self::SALE_YES, true);
        $criteria->compare('`t`.state', self::STATE_ENABLE, true);
        $criteria->compare('`t`.audit', self::AUDIT_PASS, true);
        $criteria->compare('`t`.type', self::TYPE_SELLER, true);
        $criteria->addInCondition('`t`.shop_id', $ids);

        $communityIds = array_map(function ($scrModel) {
            return $scrModel->community_id;
        }, ShopCommunityRelation::model()->findAll('shop_id=' . Yii::app()->user->id));
        $criteria2 = new CDbCriteria();
        $criteria2->addInCondition('community_id', $communityIds);
        $criteria2->compare('shop_id', Yii::app()->user->id);
        $goodsIds = array_map(function ($scgoModel) {
            return $scgoModel->goods_id;
        }, ShopCommunityGoodsSell::model()->findAll($criteria2));

        $criteria->addInCondition('id', $goodsIds);

        if ($this->shopName != '') {
            $criteria->with[] = 'shop';
            $criteria->compare('shop.name', $this->shopName, true);
        }


        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
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

    public function afterFind()
    {
        $this->_old_state = $this->state;
        $this->_old_audit = $this->audit;
        $this->_old_audit_cheap = $this->audit_cheap;
        $this->_old_category = $this->category_id;
        $this->_old_cheap_category = $this->cheap_category_id;
        return parent::afterFind();
    }

    public function getAuditName($audit = '')
    {
        $return = '';
        switch ($audit) {
            case '':
                $return = "";
                break;
            case 0:
                $return = '<span class="label label-error">待审核</span>';
                break;
            case 1:
                $return = '<span class="label label-success">审核通过</span>';
                break;
            case 2:
                $return = '<span class="label label-error">审核未通过</span>';
                break;
        }
        return $return;
    }

    public function getAuditCheapName($audit_cheap = '')
    {
        $return = '';
        switch ($audit_cheap) {
            case '':
                $return = "";
                break;
            case 0:
                $return = '<span class="label label-error">待审核</span>';
                break;
            case 1:
                $return = '<span class="label label-success">优惠中</span>';
                break;
            case 2:
                $return = '<span class="label label-error">未通过</span>';
                break;
        }
        return $return;
    }

    public function getCheapName()
    {
        if ($this->cheap_category_id == 0) {
            $return = '<span class="label label-error">未申请优惠</span>';
        } else {
            $cheap = CheapCategory::getCheapCategoryName($this->cheap_category_id);
            $return = '<span class="label label-success">' . $cheap . '</span>';
        }
        return $return;
    }

    public function getGoodImgUrl()
    {
        $arr=explode(':', $this->good_image);
        if(count($arr)>1){
            return  $this->good_image;
        }else{
            return Yii::app()->ajaxUploadImage->getUrl($this->good_image);
        }
    }

    //获得商品所属商家
    public function getShopName()
    {
        return empty($this->shop) ? '' : $this->shop->name;
    }

    //获得商品在该小区的所属销售商
    public function getSellerShopName($community_id){
        $attr = array('goods_id'=>$this->id,'community_id'=>$community_id,'is_on_sale'=>self::SALE_YES);
        //获得商家小区商品销售表记录
        $result = ShopCommunityGoodsSell::model()->findByAttributes($attr);
        return empty($result->sellShop)?"":$result->sellShop->name;
    }

    public function getCategoryName()
    {
        return empty($this->category) ? '' : $this->category->name;
    }

    //获得商品所属商家的路径
    public function getShopUrl($path = '')
    {
        return empty($this->shop) ? '' : $this->shop->getUrl($path);
    }

    //获得商品所属小区的销售商的路径
    public function getSellShopUrl($community_id,$path=""){
        $attr = array('goods_id'=>$this->id,'community_id'=>$community_id,'is_on_sale'=>self::SALE_YES);
        //获得商家小区商品销售表记录
        $result = ShopCommunityGoodsSell::model()->findByAttributes($attr);
        return empty($result->sellShop)?"":$result->sellShop->getUrl($path);
    }

    public function getNameHtml()
    {
        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();', 'data-original-title' => '电话:' . $this->shop->tel . ', 手机:' . $this->shop->mobile . ', 地址:' .
            $this->shop->address . ', 所属部门:' . Branch::getMyParentBranchName($this->shop->branch_id, true)), $this->shop->name);
    }

    public function getTypeTag()
    {
        $category = GoodsCategory::getMyParentCategoryName($this->category_id, true);

        return CHtml::tag('a', array('rel' => 'tooltip', 'href' => 'javascript:void();',
            'data-original-title' => '商品类别:' . $category), $category);
    }

    public function getGoodsAllCategoryName()
    {
        return GoodsCategory::getMyParentCategoryName($this->category_id, true);
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
        if ($this->audit_cheap == Goods::AUDIT_CHEAP_YES)
            return $this->cheap_price;
        else
            return $this->customer_price;
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

    //获取商品base64加密后的详情介绍
    public function getDescriptionHtml(){
        return isset($this->description_html)?base64_encode($this->description_html):'';
    }

    //获取商家的base64加密后的介绍
    public function getShopDescriptionHtml(){
        return isset($this->shop)?base64_encode($this->shop->desc_html):'';
    }

    //获取用户的积分
    public function getCustomerCredit(){
        $customer=Customer::model()->findByPk(Yii::app()->user->id);
        $credit=$customer->credit;
        return $credit;
    }
    //适用商家
    public function getPingTai()
    {
        return $this->ping_tai_arr[$this->ping_tai];
    }
    //商品属性
    public function getGoodsType()
    {
        return $this->goods_type_arr[$this->goods_type];
    }
    /*
     * @version 支付成功后扣商品库存
     * @param int $order_id
     * @return boolean
     */
    public function goodsKuCun($order_id){
        if(empty($order_id)){
            return false;
        }
        $orderGoodsArr=OrderGoodsRelation::model()->find('order_id=:order_id',array(':order_id'=>$order_id));
        $stock=MayPreferential::model()->getKuCun($orderGoodsArr['goods_id']);
        if($stock>0){
            $sql="update goods set ku_cun=ku_cun-".$orderGoodsArr['count']." where id=".$orderGoodsArr['goods_id'];
            $res = Yii::app()->db->createCommand($sql)->execute();
        }else{
            $res=0;
        }
        return $res;
    }
    /*
     * @version 支付成功后扣商品库存
     * @param int $goods_id
     * @return boolean
     */
    public function goodsKuCunOther($goods_id){
        if(empty($goods_id)){
            return false;
        }
        $transaction = Yii::app()->db->beginTransaction();
        $sqlSelect="select ku_cun from goods where id=".$goods_id." for update"; 
        $query = Yii::app()->db->createCommand($sqlSelect);
        $result = $query->queryAll();
        $stock=$result[0]['ku_cun'];
//        $stock=MayPreferential::model()->getKuCun($goods_id);
        if($stock>0){
            $sql="update goods set ku_cun=ku_cun-1 where id=".$goods_id;
            $res = Yii::app()->db->createCommand($sql)->execute();
            if($res){
                $transaction->commit();
            }else{
                $transaction->rollback();
            }
        }else{
            $res=0;
        }
        return $res;
    }
    /*
     * @version 判断订单是否够库存进行直接下单 
     * @param int $order_id
     * @return boolean
     */
    public function checkPayStock($order_id){
        if(empty($order_id)){
            return false;
        }
        $orderGoodsArr=OrderGoodsRelation::model()->find('order_id=:order_id',array(':order_id'=>$order_id));
        $goodsArr=Goods::model()->findByPk($orderGoodsArr['goods_id']);
        if($orderGoodsArr['count']>$goodsArr['ku_cun']){
            return false;
        }else{
            return true;
        }
    }
    /*
     * @version 退款减库存
     * @param string $sn
     */
    public function jianStock($sn){
        if(empty($sn)){
            return false;
        }
        $orderArr=Order::model()->find('sn=:sn',array(':sn'=>$sn));
        if($orderArr['seller_id']!=Item::JD_SELL_ID || $orderArr['seller_id']!=Item::DA_ZHA_XIE){
            $orderGoodsArr=OrderGoodsRelation::model()->find('order_id=:order_id',array(':order_id'=>$orderArr['id']));
            $sql="update goods set ku_cun=ku_cun+".$orderGoodsArr['count']." where id=".$orderGoodsArr['goods_id'];
            $res=Yii::app()->db->createCommand($sql)->execute();
            if($res){
                return true;
            }else{
                Yii::log('订单{$sn}扣除库存失败', CLogger::LEVEL_INFO,'colourlife.cwy.order.kucun');
                return false;
            }
        }else{
            return true;
        }
    }
    /**
     *@version 针对京东商品
     * 
     */
    public function searchOther()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id);
        $criteria->compare('sku', $this->sku);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('shop_id', Item::JD_SELL_ID);
        $criteria->addCondition('ping_tai = '.self::TICKETMALL_ORDER);//平台
    	$criteria->addCondition('is_on_sale =' . Goods::SALE_YES);
    	$criteria->addCondition('is_deleted =' . Item::DELETE_ON);
    	$criteria->addCondition('state =' . Item::STATE_ON);
    	$criteria->addCondition('audit = 1');//审核通过的
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
    /*
     * @version 获取京东协议价格
     * @param int $sku 商品ID
     * return 价格
     */
    public static function getJdPrice($sku)
    {
        if(empty($sku)){
            return 0;
        }
        $productPriceJson=JdApi::model()->getProductXyPrice($sku);
        $productPriceArr=json_decode($productPriceJson,true);
        if(!empty($productPriceArr['result'])){
            return  $productPriceArr['result'][0]['price'];
        }else{
            return 0;
        }
    }
    /*
     * @version 调整价格运算
     * @pram int $jia_type
     * @pram int $jia_jian
     * @pram int $bi_num
     * @pram int $number
     * @pram int $goods_id
     * return boolean
     */
    public static function getLastPrice($jia_type,$jia_jian,$bi_num,$number,$goods_id=''){
        if(empty($jia_type) || empty($jia_jian) || empty($bi_num) || empty($number)){
            return false;
        }
        if($jia_type==1){
            $price='market_price';
        }else{
            $price='customer_price';
        }
        if($jia_jian==1){
            $biao='+';
        }else{
            $biao='-';
        }
        if(!empty($goods_id)){
            if($bi_num==1){
                $number=$number/100;
                $sqlUpdate1="update goods set ".$price."=".$price.$biao.$price."*".$number." where id=".$goods_id;
                $result=Yii::app()->db->createCommand($sqlUpdate1)->execute();
            }else{
                $sqlUpdate2="update goods set ".$price."=".$price.$biao.$number." where id=".$goods_id;
                $result=Yii::app()->db->createCommand($sqlUpdate2)->execute();
            }
        }else{
            if($bi_num==1){
                $number=$number/100;
                $sqlUpdate1="update goods set ".$price."=".$price.$biao.$price."*".$number." where shop_id=".Item::JD_SELL_ID." and ping_tai=".self::TICKETMALL_ORDER." and is_on_sale=1 and is_deleted=0 and state=0 and audit=1";
                $result=Yii::app()->db->createCommand($sqlUpdate1)->execute();
            }else{
                $sqlUpdate2="update goods set ".$price."=".$price.$biao.$number." where shop_id=".Item::JD_SELL_ID." and ping_tai=".self::TICKETMALL_ORDER." and is_on_sale=1 and is_deleted=0 and state=0 and audit=1";
                $result=Yii::app()->db->createCommand($sqlUpdate2)->execute();
            }
        }
        return $result;
    }
    

}
