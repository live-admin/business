<?php

class BaseShop extends Shop
{
    public $modelName = '平台商家';
    private $_old_branch_id;
    private $_old_state;
    private $_old_auto_chance_community;

    public $logoFile;
    public $detailFile;
    public $mapFile;

    public $community_ids = array();
    //以下字段仅供搜索用
    public $community = array(); //小区
    public $region; //地区

    public $contact_tel; //联系电话,包括手机和电话

    public static $surrounding_tab = array();//周边优惠
    public $surrounding_cate_id;//周边优惠ID
    public $surrounding_tab_id;//TAB标签内容
    public $app_content;//TAB APP内容
    public $content;//TAB 内容
    public $benefit_category = array();//便民服务分类
    public $benefit_category_id;//便民服务分类ID
    public $benefit = array();//便民服务
    public $benefit_id;//便民服务ID

    public $communityIds;
    public $province_id;
    public $city_id;
    public $district_id;

    const SHOP_SURROUNDING = 1;//便民服务
    const SHOP_BENEFIT = 0;//周边优惠

    public static $_surrounding_benefit = array(
        self::SHOP_BENEFIT => '周边优惠',
        self::SHOP_SURROUNDING => '便民服务'
    );

    public function rules()
    {
        $array = array(
            array('username,password', 'required', 'on' => 'create'),
            array('branch_id, type, name,contact,mobile,domain,category_id', 'required'),
            array(
                'branch_id, type, create_time, last_time, state, is_deleted, update_employee_time, update_employee_id,is_auto_chance_community ,life_cate_id,life_cate_id,is_goods_sales,is_goods_seller,is_goods_supplier,is_service_repair, surrounding_cate_id, surrounding_benefit, surrounding_tab_id',
                'numerical',
                'integerOnly' => true
            ),
            array('name, address', 'length', 'max' => 255),
            array('username', 'checkUserExit', 'on' => 'create'),
            array('username, password', 'length', 'max' => 100),
            array('contact,last_ip', 'length', 'max' => 20),
            array('state', 'checkGoodsDisable', 'on' => 'disable'), // 禁用时商家时要判断商家下面的商品是否被禁用
            array('is_deleted', 'checkGoodsDelete', 'on' => 'delete'),
            array('surrounding_benefit, surrounding_cate_id, life_cate_id', 'checkSurrounding', 'on' => 'create, update'),
            array(
                'domain',
                'unique',
                'caseSensitive' => false,
                'criteria' => array('condition' => 'is_deleted=0'),
                'on' => 'create, update'
            ),
            array('attributeName', 'checkAttributeName', 'on' => 'create,update'),
            array('attributeName', 'checkAttributeNameUpdate', 'on' => 'update'),
            array('domain', 'filter', 'filter' => 'strtoupper', 'on' => 'create, update'),
            array('domain', 'match', 'pattern' => '/^[A-Za-z0-9]*$/', 'message' => '域名只能是字母和数字。'),
            array(
                'desc_html',
                'filter',
                'filter' => array($obj = new CHtmlPurifier(), 'purify'),
                'on' => 'create, update'
            ),
            //array('mobile', 'common.components.validators.ChinaMobileValidator', 'on' => 'create, update'),
            array('score', 'length', 'max' => 10),
            array('community,region,mobile,tel,reserve_desc,contact_tel, app_content, content, deduct', 'safe'),
            array('community_ids,logoFile,detailFile,mapFile,sign_type,attributeName,surrounding_cate_id,surrounding_tab_id,surrounding_benefit,benefit_category,benefit_category_id,benefit,benefit_id', 'safe', 'on' => 'create, update'),
            //			ICE 搜索数据
            array('communityIds,province_id,city_id,district_id', 'safe'),

        );
        return CMap::mergeArray(parent::rules(), $array);
    }

    public function attributeLabels()
    {
        $array = array(
            'logoFile' => 'Logo图片',
            'detailFile' => '详情图片',
            'mapFile' => '地图图片',
            'community_ids' => '服务范围',
            'community' => '小区',
            'region' => '地区',
            'contact_tel' => '联系电话',
            'surrounding_cate_id' => '便民服务',
            'surrounding_tab_id' => '便民服务TAB标签',
            'surroundingContent' => '便民服务内容',
            'benefit_category_id' => '周边优惠分类',
            'benefit_id' => '周边优惠',
            'content' => 'TAB 内容',
            'app_content' => 'TAB APP内容',
        );
        return CMap::mergeArray(parent::attributeLabels(), $array);
    }

    public function search()
    {
        $criteria = new CDbCriteria;
//         ICE 改为调用下面的静态方法了
//        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        //选择的组织架构ID
        if (!empty($this->branch)) {
            $criteria->addInCondition('branch_id', $this->branch->getChildrenIdsAndSelf());
        } else //自己的组织架构的ID
        {
//            $criteria->addInCondition('branch_id', $employee->getBranchIds());
//      ICE 上面的逻辑也是去employeebranchrelation查数字branch_id 但是findbypk有可能报错，所以完善方法。
            $criteria->addInCondition('branch_id', Employee::ICEgetOldBranchIds());
        }

//        if (!empty($this->community)) {
//            $criteria->distinct = true;
//            $criteria->join = 'inner join shop_community_relation s on s.shop_id=t.id';
//            $criteria->addInCondition('s.community_id', $this->community);
//        } else {
//            if ($this->region != '') {
//                $criteria->distinct = true;
//                $criteria->join = 'inner join shop_community_relation s on s.shop_id=t.id';
//                $criteria->addInCondition('s.community_id', Region::model()->getRegionCommunity($this->region, 'id'));
//            }
//        }

        if (!empty($this->community)) {
            //如果有小区
            $community_ids = $this->community;
            $criteria->distinct = true;
            $criteria->join = 'inner join shop_community_relation s on s.shop_id=t.id';
            $criteria->addInCondition('s.community_id', $community_ids);
        } else if ($this->province_id) {
            //如果有地区
            if ($this->district_id) {
                $regionId = $this->district_id;
            } else if ($this->city_id) {
                $regionId = $this->city_id;
            } else if ($this->province_id) {
                $regionId = $this->province_id;
            } else {
                $regionId = 0;
            }
            $community_ids = ICERegion::model()->getRegionCommunity(
                $regionId,
                'id'
            );

            $criteria->distinct = true;
            $criteria->join = 'inner join shop_community_relation s on s.shop_id=t.id';
            $criteria->addInCondition('s.community_id', $community_ids);
        }



        if ($this->contact_tel) {
            $criteria->addCondition(" mobile like '%{$this->contact_tel}%' or tel like '%{$this->contact_tel}%'");
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('branch_id', $this->branch_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('contact', $this->contact, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('tel', $this->tel, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('last_time', $this->last_time);
        $criteria->compare('last_ip', $this->last_ip, true);
        $criteria->compare('state', $this->state);
        $criteria->compare('update_employee_time', $this->update_employee_time);
        $criteria->compare('update_employee_id', $this->update_employee_id);
        $criteria->compare('score', $this->score, true);
        $criteria->compare('is_auto_chance_community', $this->is_auto_chance_community);
        $criteria->compare('type', $this->type);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('desc_html', $this->desc_html, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function afterFind()
    {
        $arr = array();
        $this->_old_branch_id = $this->branch_id;
        $this->_old_state = $this->state;
        $this->_old_auto_chance_community = $this->is_auto_chance_community;
        //更新服务
        if (!empty($this->is_goods_sales)) {
            $arr['is_goods_sales'] = 'is_goods_sales';
        }
        if (!empty($this->is_goods_seller)) {
            $arr['is_goods_seller'] = 'is_goods_seller';
        }
        if (!empty($this->is_goods_supplier)) {
            $arr['is_goods_supplier'] = 'is_goods_supplier';
        }
        if (!empty($this->is_service_repair)) {
            $arr['is_service_repair'] = 'is_service_repair';
        }
        if (!empty($this->is_internal_procurement)) {
            $arr['is_internal_procurement'] = 'is_internal_procurement';
        }

        $this->attributeName = $arr;

        if(BaseShop::SHOP_SURROUNDING == $this->surrounding_benefit){
            if($tab = SurroundingTab::model()->findAllByAttributes(array('surrounding_id' => $this->surrounding_cate_id))){
                /**
                 * @var SurroundingTab $val
                 */
                foreach($tab as $val)
                {
                    if(empty($this->surrounding_tab_id)){
                        $this->surrounding_tab_id = $val->id;
                    }
                    self::$surrounding_tab[$val->id] = $val->name;
                }
            }
            if(!empty($this->surrounding_tab_id)){
                if($content = SurroundingContent::model()->findByAttributes(array('shop_id' => $this->id, 'tab_id' => $this->surrounding_tab_id))){
                    $this->app_content = $content->app_content;
                    $this->content = $content->content;
                }
            }
        }

        return parent::afterFind();
    }

    //添加商家与小区的关系
    public function afterSave()
    {
        //禁用时删除小区与商家关系
        if ($this->state == Item::SHOP_COMMUNITY_STATUS_OK && $this->_old_state != $this->state) {
            ShopCommunityRelation::model()->deleteAll('shop_id=:shop_id', array(':shop_id' => $this->id));
        }
        //商家的组织机构发生变化时
        if ($this->_old_branch_id != $this->branch_id && !empty($this->_old_branch_id)) {
            //得到该组织架构下的所有小区
            $oldBranch = Branch::model()->findByPk($this->_old_branch_id);
            $oldIds = $oldBranch->getBranchAllIds('Community', $this->_old_branch_id);
            $newBranch = Branch::model()->findByPk($this->branch_id);
            $newIds = $newBranch->getBranchAllIds('Community', $this->branch_id);
            $ids = array_intersect($oldIds, $newIds); //交集部分,不需要调整
            $chearIds = array_diff($oldIds, $ids); //需要清理的ID集合
            $criteria = new CDbCriteria();
            $criteria->compare('shop_id', $this->id);
            $criteria->addInCondition('community_id', $chearIds);
            //清理商家和小区的关联
            ShopCommunityRelation::model()->deleteAll($criteria);
            //清理商家-小区-商品的关联
            ShopCommunityGoodsOwnership::model()->deleteAll($criteria);
            ShopCommunityGoodsSell::model()->deleteAll($criteria);
            $shopIds = ShopRelation::getSellerIds($this->id);
            //清理加盟商的销售记录
            $criteria = new CDbCriteria();
            $criteria->addInCondition('shop_id', $shopIds);
            $criteria->addInCondition('community_id', $chearIds);
            ShopCommunityGoodsSell::model()->deleteAll($criteria);
            //清理商家和商家的关联
            $criteria = new CDbCriteria();
            $criteria->addCondition('supplier_id=' . $this->id . ' OR seller_id=' . $this->id);
            $criteria->addInCondition('community_id', $chearIds);
            ShopRelation::model()->deleteAll($criteria);
        }
        return parent::afterSave();
    }

    public function beforeDelete()
    {
        ShopCommunityRelation::model()->deleteAll('shop_id=:shop_id', array(':shop_id' => $this->id));
        ShopRelation::model()->deleteAll('supplier_id=:shop_id or seller_id=:shop_id', array(':shop_id' => $this->id));
        ShopCommunityGoodsOwnership::model()->deleteAll('shop_id=:seller_id', array(':seller_id' => $this->id));
        ShopCommunityGoodsSell::model()->deleteAll('shop_id=:seller_id', array(':seller_id' => $this->id));
        return parent::beforeDelete();
    }

    protected function beforeValidate()
    {
        if (empty($this->logo_image) && !empty($this->logoFile)) {
            $this->logo_image = '';
        }
        if (empty($this->detail_image) && !empty($this->detailFile)) {
            $this->detail_image = '';
        }
        if (empty($this->map_image) && !empty($this->mapFile)) {
            $this->map_image = '';
            $this->map_thumb_image = '';
        }

        //重置属性为0
        foreach (self::$_service as $service) {
            $this->$service = 0;
        }
        //设置服务属性
        if (!empty($this->attributeName)) {
            foreach ($this->attributeName as $val) {
                if (in_array($val, self::$_service)) {
                    $this->$val = 1;
                }
            }
        }
        return parent::beforeValidate();
    }

    /**
     * 处理图片
     * @return bool
     */
    protected function beforeSave()
    {
        if (!empty($this->logoFile)) {
            $this->logo_image = Yii::app()->ajaxUploadImage->moveSave($this->logoFile, $this->logo_image);
        }
        if (!empty($this->detailFile)) {
            $this->detail_image = Yii::app()->ajaxUploadImage->moveSave($this->detailFile, $this->detail_image);
        }
        if (!empty($this->mapFile)) {
            $this->map_image = Yii::app()->ajaxUploadImage->moveSave($this->mapFile, $this->map_image);
        }
        if (!empty($this->mapFile)) {
            $oldFilename = $this->map_thumb_image;
            $this->map_thumb_image = Yii::app()->ajaxUploadImage->getNewName($this->map_image);
            $conversion = new ImageConversion(Yii::app()->ajaxUploadImage->getFilename($this->map_image));
            $conversion->conversion(
                Yii::app()->ajaxUploadImage->getFilename($this->map_thumb_image),
                array(
                    'w' => 437, // 结果图的宽
                    'h' => 150, // 结果图的高
                    't' => 'resize,clip', // 转换类型
                )
            );
            Yii::app()->ajaxUploadImage->delete($oldFilename);
        }

        return parent::beforeSave();
    }

    public function checkUserExit($attribute, $params)
    {
        if (!$this->hasErrors() && Shop::model()->findByAttributes(array('username' => $this->username))) {
            $this->addError("username", '该账户已被注册。');
        }
    }

    public function checkGoodsDisable($attribute, $params)
    {
        $goodList = Goods::model()->enabled()->findAllByAttributes(array('shop_id' => $this->id));
        if (!$this->hasErrors() && count($goodList) > 0) {
            $this->addError($attribute, '该商家下还存在启用的商品，无法继续进行操作！');
        }
    }

    public function checkAttributeName($attribute, $params)
    {
        if (!$this->hasErrors() && !empty($this->attributeName)) {
            if (in_array('is_goods_seller', $this->attributeName) &&
                in_array('is_goods_supplier', $this->attributeName)
            ) {
                $this->addError($attribute, '该商家下服务内容不能同时是加盟商品又是供应商品，无法继续进行操作！');
            }
        }
    }

   /* public function checkSurroundingBenefit($attribute, $params){
            if(($this->surrounding_benefit == 0 || $this->surrounding_benefit == 1) &&
                $this->type != Shop::TYPE_LOCAL){
            }

           $this->addError($attribute,);
    }*/

    public function checkAttributeNameUpdate($attribute, $params)
    {
        $shop = self::model()->findByPk($this->id);
        if (!$this->hasErrors() && !empty($this->attributeName)) {
            if ($shop->is_goods_seller){
                $data = ShopRelation::model()->find('seller_id=:seller_id',array(':seller_id' => $this->id));
                if (!$this->hasErrors() && !empty($data) && (!in_array('is_goods_seller', $this->attributeName)))
                    $this->addError($attribute, '该商家下服务内容加盟商品/供应商品有数据，无法继续进行修改加盟商品/供应商品服务！');
                if (!$this->hasErrors() && !empty($data) && (in_array('is_goods_supplier', $this->attributeName)))
                    $this->addError($attribute, '该商家下服务内容加盟商品/供应商品有数据，无法继续进行修改加盟商品/供应商品服务！');
            }
            if ($shop->is_goods_supplier){
                $data = ShopRelation::model()->find('supplier_id=:supplier_id',array(':supplier_id' => $this->id));
                if (!$this->hasErrors() && !empty($data) && (!in_array('is_goods_supplier', $this->attributeName)))
                    $this->addError($attribute, '该商家下服务内容加盟商品/供应商品有数据，无法继续进行修改加盟商品/供应商品服务！');
                if (!$this->hasErrors() && !empty($data) && (in_array('is_goods_seller', $this->attributeName)))
                    $this->addError($attribute, '该商家下服务内容加盟商品/供应商品有数据，无法继续进行修改加盟商品/供应商品服务！');
            }
        }else
        {
            $data = ShopRelation::model()->find('seller_id=:seller_id or supplier_id=:supplier_id',array(':seller_id' => $this->id,':supplier_id' => $this->id));
            if (!$this->hasErrors() && !empty($data))
                $this->addError($attribute, '该商家下服务内容加盟商品/供应商品有数据，无法继续进行修改加盟商品/供应商品服务！');
        }
    }

    public function checkGoodsDelete($attribute, $params)
    {
        $goodList = Goods::model()->findAllByAttributes(array('shop_id' => $this->id, 'is_deleted' => 0));
        if (!$this->hasErrors() && count($goodList) > 0) {
            $this->addError($attribute, '该商家下还存在商品，无法继续进行操作！');
        }
    }

    public function checkSurrounding($attribute, $params)
    {
        if(!$this->hasErrors()){
            /*if(self::SHOP_SURROUNDING == $this->surrounding_benefit){
                if(empty($this->surrounding_cate_id)){
                    $this->addError('surrounding_cate_id', '请选择便民服务分类');
                }
            }
            elseif(self::SHOP_BENEFIT == $this->surrounding_benefit){
                if(empty($this->life_cate_id)){
                    $this->addError('benefit_id', '请选择周边优惠');
                }
            }*/
        }
    }

    public function getCommunityTreeData()
    {
        if (empty($this->branch)) {
            return array(array('id' => "r_0", 'pId' => "-1", 'name' => "[地区]全国", 'open' => true));
        }
        return $this->branch->getRegionCommunityRelation($this->id, 'Shop');
    }

    public function saveSurroundingContent()
    {
        if(!$model = SurroundingContent::model()->findByAttributes(array('shop_id' => $this->id, 'tab_id' => $this->surrounding_tab_id))){
            $model = new SurroundingContent();
            $model->shop_id = $this->id;
            $model->tab_id = $this->surrounding_tab_id;
        }
        $model->content = $this->content;
        $model->app_content = $this->app_content;
        if($model->validate()){
            return $model->save();
        }
        else{
            $model->addError('surrounding_cate_id', '增加失败');
        }
    }

    public function getSurroundAll()
    {
        return Surrounding::model()->findAllByAttributes(array('state' => 0));
    }

    public function getSurroundingBenefit()
    {
        return self::$_surrounding_benefit[$this->surrounding_benefit];
    }

    public function getBenefitAll()
    {
        return $this->benefit;
    }

    public static function getBenefitCategoryAll()
    {
        return LifeCategory::model()->findAllByAttributes(array('state' => 0,'is_deleted' => 0));
    }

    public function ICEGetLinkageRegionDefaultValue()
    {
        $updateDefaults = $this->ICEGetLinkageRegionDefaultValueForUpdate();
        return $updateDefaults
            ? $updateDefaults
            : $this->ICEGetLinkageRegionDefaultValueForSearch();
    }

    protected function ICEGetLinkageRegionDefaultValueForUpdate()
    {
        return array();
    }

    public function ICEGetLinkageRegionDefaultValueForSearch()
    {
        $searchRegion = $this->ICEGetSearchRegionData(isset($_GET[__CLASS__])
            ? $_GET[__CLASS__] : array());

        $defaultValue = array();

        if ($searchRegion['province_id']) {
            $defaultValue[] = $searchRegion['province_id'];
        } else {
            return $defaultValue;
        }

        if ($searchRegion['city_id']) {
            $defaultValue[] = $searchRegion['city_id'];
        } else {
            return $defaultValue;
        }

        if ($searchRegion['district_id']) {
            $defaultValue[] = $searchRegion['district_id'];
        } else {
            return $defaultValue;
        }

        return $defaultValue;
    }

    protected function ICEGetSearchRegionData($search = array())
    {
        return array(
            'province_id' => isset($search['province_id']) && $search['province_id']
                ? $search['province_id'] : '',
            'city_id' => isset($search['city_id']) && $search['city_id']
                ? $search['city_id'] : '',
            'district_id' => isset($search['district_id']) && $search['district_id']
                ? $search['district_id'] : '',
        );
    }
}
