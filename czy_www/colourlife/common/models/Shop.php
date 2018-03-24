<?php

/**
 * This is the model class for table "shop".
 *
 * The followings are the available columns in table 'shop':
 * @property integer $id
 * @property integer $branch_id
 * @property integer $type
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $contact
 * @property string $mobile
 * @property string $tel
 * @property string $address
 * @property string $desc
 * @property integer $create_time
 * @property integer $last_time
 * @property string $last_ip
 * @property integer $state
 * @property integer $is_deleted
 * @property integer $update_employee_time
 * @property integer $update_employee_id
 * @property string $score
 * @property integer $is_auto_chance_community
 * @property string $lat
 * @property string $lng
 * @property string $logo_image
 * @property string $detail_image
 * @property string $map_image
 * @property string $map_thumb_image
 * @property integer $life_cate_id
 * @property integer $life_display_order
 * @property integer $is_benefit
 * @property integer $benefit_display_order
 * @property integer $is_house
 * @property integer $house_display_order
 * @property integer $is_educate
 * @property integer $educate_display_order
 * @property integer $is_breakfast
 * @property integer $breakfast_display_order
 * @property integer $is_rabbit
 * @property integer $rabbit_display_order
 * @property string $domain
 * @property string $desc_html
 * @property integer $category_id
 * @property integer $is_goods_sales
 * @property integer $is_goods_seller
 * @property integer $is_goods_supplier
 * @property integer $is_service_repair
 * @property integer $surrounding_benefit
 * @property integer $surrounding_cate_id
 * @property string $lifeCateId
 * @property integer $deduct
 */
class Shop extends CActiveRecord
{
    public $modelName = '商家';

    public $attributeName;
    public static $_service = array(
        'is_goods_sales',//商品销售
        'is_goods_seller',//加盟商(商品）
        'is_goods_supplier',//供应商（商品）
        'is_service_repair',//报修
        'is_internal_procurement',//内部采购商家
    );
    const TYPE_ONLINE = 0;
    const TYPE_LOCAL = 1;
    const TYPE_SUPPLIER = 2;
    const TYPE_SELLER = 3; //加盟商

    const STATE_DISABLE = 1;
    const STATE_ENABLE = 0;

    const YES_AUTO_CHANCE_COMMUNITY = 1; //自动更新小区状态
    const NO_AUTO_CHANCE_COMMUNITY = 0; //不自动更新小区状态

   /* public static function getTypeID($type = null)
    {
        if ($type == null) {
            return "";
        } else {
            switch ($type) {
                case self::TYPE_LOCAL:
                    return "local";
                    break;
                case self::TYPE_ONLINE:
                    return "online";
                    break;
                case self::TYPE_SELLER:
                    return "seller";
                    break;
                case self::TYPE_SUPPLIER:
                    return "supplier";
                    break;
            }
        }
    }*/

    public function getTypeName($type)
    {
        if (empty($type)) {
            return "商家";
        } else {
            switch ($type) {
                case self::TYPE_LOCAL:
                    return "本地商家";
                    break;
                case self::TYPE_ONLINE:
                    return "在线商家";
                    break;
                case self::TYPE_SELLER:
                    return "加盟商家";
                    break;
                case self::TYPE_SUPPLIER:
                    return "供应商家";
                    break;
            }
        }
    }

    public function getTypeNames()
    {
        return array(
            self::TYPE_LOCAL=>'本地商家',
            self::TYPE_ONLINE=>'在线商家',
            self::TYPE_SELLER=>'加盟商家',
            self::TYPE_SUPPLIER=>'供应商家',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'shop';
    }

    public function rules()
    {
        return array(
            array(
                'id, deduct,branch_id, type, name, username, password, salt, contact, mobile,
                            tel, address, desc, sign_type, state, update_employee_time, update_employee_id,
                            score, is_auto_chance_community, lat, lng, logo_image, detail_image, map_image,
                             map_thumb_image, life_cate_id, life_display_order, is_benefit, benefit_display_order,
                             is_house, house_display_order, is_educate, educate_display_order, is_breakfast,
                             breakfast_display_order, is_rabbit, rabbit_display_order, domain, desc_html,reserve_desc,
                             category_id,is_goods_sales,is_goods_seller,is_goods_supplier,is_service_repair, deduct',
                'safe',
                'on' => 'search'
            ),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            'category' => array(self::BELONGS_TO, 'ShopCategory', 'category_id'),
            'servers' => array(self::HAS_MANY, 'Server', 'shop_id'),
            'reserve' => array(self::HAS_MANY, 'Reserve', 'shop_id'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'branch_id' => '管辖部门',
            'type' => '商家类型',
            'name' => '商家名称',
            'username' => '商家帐号',
            'password' => '密码',
            'salt' => '密码加盐码',
            'contact' => '联系人',
            'mobile' => '手机号码',
            'tel' => '固定电话',
            'address' => '地址',
            'create_time' => '注册时间',
            'last_time' => '最后登录时间',
            'last_ip' => '最后登录IP',
            'state' => '状态',
            'update_employee_time' => '操作时间',
            'update_employee_id' => '操作人',
            'score' => '评分',
            'is_auto_chance_community' => '自动增加小区范围',
            'desc' => '商家描述',
            'life_cate_id' => '周边优惠分类',
            'lat' => '纬度',
            'lng' => '经度',
            'logo_image' => 'Logo图片',
            'detail_image' => '详情图片',
            'map_thumb_image' => '地图缩略图',
            'map_image' => '地图图片',
            'life_display_order' => '优惠优先级',
            'is_benefit' => '是否是便民',
            'benefit_display_order' => '便民优先级',
            'is_breakfast' => '是否是营养早餐',
            'breakfast_display_order' => '优先级',
            'is_educate' => '是否是教育',
            'educate_display_order' => '教育优先级',
            'is_house' => '是否是房屋',
            'house_display_order' => '房屋优先级',
            'is_rabbit' => '是否是小白兔',
            'rabbit_display_order' => '优先级',
            'sign_type' => '签约商家类型',
            'domain' => '域名',
            'desc_html' => '商家 HTML 描述',
            'category_id' => '行业',
            'reserve_desc' => '预订说明',
            'is_goods_sales' => '商品销售',
            'is_goods_seller' => '加盟商品',
            'is_goods_supplier' => '供应商品',
            'is_service_repair' => '维修服务',
            'is_internal_procurement' => '内部采购',
            'attributeName' => '服务内容',
            'deduct' =>'平台分成比例',
            'surrounding_benefit' => '便民/优惠',
            'surrounding_cate_id' => '周边优惠分类',
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

        $criteria->compare('id', $this->id);
        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        //选择的组织架构ID
        if (!empty($this->branch)) {
            $criteria->addInCondition('branch_id', $this->branch->getChildrenIdsAndSelf());
        } else //自己的组织架构的ID
        {
            $criteria->addInCondition('branch_id', $employee->getBranchIds());
        }

        $criteria->compare('type', $this->type);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('contact', $this->contact, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('tel', $this->tel, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('last_time', $this->last_time);
        $criteria->compare('last_ip', $this->last_ip, true);
        $criteria->compare('state', $this->state);
        $criteria->compare('update_employee_time', $this->update_employee_time);
        $criteria->compare('update_employee_id', $this->update_employee_id);
        $criteria->compare('score', $this->score, true);
        $criteria->compare('is_auto_chance_community', $this->is_auto_chance_community);
        $criteria->compare('lat', $this->lat, true);
        $criteria->compare('lng', $this->lng, true);
        $criteria->compare('logo_image', $this->logo_image, true);
        $criteria->compare('detail_image', $this->detail_image, true);
        $criteria->compare('map_image', $this->map_image, true);
        $criteria->compare('map_thumb_image', $this->map_thumb_image, true);
        $criteria->compare('life_cate_id', $this->life_cate_id);
        $criteria->compare('life_display_order', $this->life_display_order);
        $criteria->compare('is_benefit', $this->is_benefit);
        $criteria->compare('benefit_display_order', $this->benefit_display_order);
        $criteria->compare('is_house', $this->is_house);
        $criteria->compare('house_display_order', $this->house_display_order);
        $criteria->compare('is_educate', $this->is_educate);
        $criteria->compare('educate_display_order', $this->educate_display_order);
        $criteria->compare('is_breakfast', $this->is_breakfast);
        $criteria->compare('breakfast_display_order', $this->breakfast_display_order);
        $criteria->compare('is_rabbit', $this->is_rabbit);
        $criteria->compare('rabbit_display_order', $this->rabbit_display_order);
        $criteria->compare('sign_type', $this->sign_type);
        $criteria->compare('deduct',$this->deduct);
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function behaviors()
    {
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'create_time',
                'updateAttribute' => 'update_employee_time',
                'setUpdateOnCreate' => true,
            ),
            'IsDeletedBehavior' => array(
                'class' => 'common.components.behaviors.IsDeletedBehavior',
            ),
            'StateBehavior' => array(
                'class' => 'common.components.behaviors.StateBehavior',
            ),
            'UserBehavior' => array(
                'class' => 'common.components.behaviors.UserBehavior',
            ),
            'IpBehavior' => array(
                'class' => 'common.components.behaviors.IpBehavior',
                'createAttribute' => 'last_ip',
                'updateAttribute' => 'last_ip',
                'setUpdateOnCreate' => true,
            ),
        );
    }

    //取得商家的小区信息
    public function getShopCommunity()
    {
        Branch::model()->id = $this->branch_id;
        return Branch::model()->getBranchAllData('community');
    }

    //取得商家的小区信息
    public function getShopAllBranch()
    {
        $branchName = '';
        if (!empty($this->branch)) {
            $branchName = $this->branch->getMyParentBranchName($this->branch_id);
        }
        return $branchName;
    }

    public function getVipNames($select = false)
    {
        $return = array();
        if ($select) {
            $return[''] = '全部';
        }
        $return[1] = '是';
        $return[0] = '否';
        return $return;
    }

    public function getBranchName()
    {
        return empty($this->branch) ? '' : $this->branch->name;
    }

    public function getCategoryName()
    {
        return empty($this->category) ? '' : $this->category->name;
    }

    public function getLogoImgUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->logo_image, '/common/images/nopic-logo.jpg');
    }

    public function getDetailImgUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->detail_image, '/common/images/nopic-detail.jpg');
    }

    public function getMapImgUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->map_image, '/common/images/nopic-map.jpg');
    }

    public function getMapThumbImgUrl()
    {
        return Yii::app()->ajaxUploadImage->getUrl($this->map_thumb_image, '/common/images/nopic-map-thumb.jpg');
    }

    public function getUrl($path = '')
    {
        return 'http://' . strtolower($this->domain) . SHOP_DOMAIN . $path;
    }

    //得到服务内容的属性名称
    public function getAttributeNames($type = false)
    {
        if ($type) {
            $str = '';
            foreach (self::$_service as $val) {
                if ($this->$val) {
                    $str .= $this->getAttributeLabel($val) . ',';
                }
            }
            return $str;
        } else {
            $arr = array();
            foreach (self::$_service as $val) {
                $arr[$val] = $this->getAttributeLabel($val);
            }
            return $arr;
        }
    }

    //商家签约类型
    public function getSignType()
    {
        return array(
            0=>'VIP商家',
            1=>'特惠商家',
            2=>'普通商家',
        );
    }
    public function getSignTypeName()
    {
        $arr =  array(
            0=>'VIP商家',
            1=>'特惠商家',
            2=>'普通商家',
        );
        return  $arr[$this->sign_type];
    }

    public function getIs_vip()
    {
        if($this->sign_type==0 || $this->sign_type==1){
            return 1;
        }else{
            return 0;
        }
    }
    
    public function setLatAndLng(){        
        $count = self::model()->count();
        $pageMax = ceil($count/10);          //每页10条
        for($i=1;$i<=$pageMax;$i++){
            $index = 10*($i-1);
            $l=$index.",10";
            $totalModel = self::model()->findAll(array('limit'=>$l));
            foreach($totalModel as $_model){
                $url="http://api.map.baidu.com/geocoder/v2/?address=".urlencode($_model['address'])."&ak=zZtp9zi2isS4PRb43rOTaG8f&output=json";
                $res=file_get_contents($url);
                $data=json_decode($res);
                if($data && $data->status == 0){
                    $_model['lat'] = $data->result->location->lat;
                    $_model['lng'] = $data->result->location->lng;
                    if($_model->save()){
                        echo iconv("utf-8","gbk",$_model['address'].":设置经纬度成功！lat=".$_model['lat'].";lng=".$_model['lng']."\r\n");
                    }
                }
            }
            sleep(2000);
        }
        die("获取经纬度完毕");
    }
    

    public function getDeductName(){
        return $this->deduct."%";
    }

    public function getLifeCateId()
    {
        $name = '';
        if(empty($this->life_cate_id)){
            $name = '';
        }
        elseif($model = LifeCategory::model()->findByPk($this->life_cate_id)){
            $name = $model->name;
        }
        return $name;
    }
}
