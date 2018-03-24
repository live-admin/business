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
 */
class ShopReport extends CActiveRecord
{
    public $modelName = '商家报表';
    public $region;
    public $community;
    public $branch_id;
    public $create_start_time;
    public $create_end_time;
    public $last_start_time;
    public $last_end_time;
    const TYPE_ONLINE = 0;
    const TYPE_LOCAL = 1;
    const TYPE_SUPPLIER = 2;
    const TYPE_SELLER = 3;

    // public $type;

    public $communityIds;
    public $province_id;
    public $city_id;
    public $district_id;


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'shop';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id, branch_id, type, name, username, password,create_start_time,create_end_time, salt, contact, mobile, tel, address, desc, is_vip, state, update_employee_time, update_employee_id, score, is_auto_chance_community, lat, lng, logo_image, detail_image, map_image, map_thumb_image, life_cate_id, life_display_order, is_benefit, benefit_display_order, is_house, house_display_order, is_educate, educate_display_order, is_breakfast, breakfast_display_order, is_rabbit, rabbit_display_order, domain, desc_html,reserve_desc,region,community, category_id,sign_type', 'safe', 'on' => 'report_search'),
            //			ICE 搜索数据
            array('communityIds,province_id,city_id,district_id', 'safe'),
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

    public function getTypeName()
    {
        if ($this->type == "0") {
            return "在线商家";
        } else if ($this->type == "1") {
            return "本地商家";
        } else if ($this->type == "2") {
            return "供应商家";
        } else if ($this->type == "3") {
            return "加盟商家";
        }
        /*$tpye=$this->type;
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
        }*/
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
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
            'region' => '地区',
            'community' => '小区',
            'create_start_time' => '注册时间开始',
            'create_end_time' => '注册时间结束',
            'last_start_time' => '最后登录时间开始',
            'last_end_time' => '最后登录时间结束'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function report_search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        $criteria = new CDbCriteria;
        if (isset($_GET['ShopReport']) && !empty($_GET['ShopReport'])) {
            $_SESSION['ShopReport'] = array();
            $_SESSION['ShopReport'] = $_GET['ShopReport'];
        }
        if (isset($_GET['exportAction']) && $_GET['exportAction'] == 'exportReport') {
            if (isset($_SESSION['ShopReport']) && !empty($_SESSION['ShopReport'])) {
                foreach ($_SESSION['ShopReport'] as $key => $val) {
                    if ($val != "") {
                        $this->$key = $val;
                    }
                }
            }
        }
//         ICE 改为调用下面的静态方法了
//        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        //选择的组织架构ID
        if (!empty($this->branch))
            $criteria->addInCondition('branch_id', $this->branch->getChildrenIdsAndSelf());
        else //自己的组织架构的ID
//            $criteria->addInCondition('branch_id', $employee->getBranchIds());
//      ICE 上面的逻辑也是去employeebranchrelation查数字branch_id 但是findbypk有可能报错，所以完善方法。
            $criteria->addInCondition('branch_id', Employee::ICEgetOldBranchIds());

//        if (!empty($this->community)) {
//            $criteria->distinct = true;
//            $criteria->join = 'inner join shop_community_relation s on s.shop_id=t.id';
//            $criteria->addInCondition('s.community_id', $this->community);
//        } else if ($this->region != '') {
//            $criteria->distinct = true;
//            $criteria->join = 'inner join shop_community_relation s on s.shop_id=t.id';
//            $criteria->addInCondition('s.community_id', Region::model()->getRegionCommunity($this->region, 'id'));
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

        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('tel', $this->tel, true);
        $criteria->compare('address', $this->address, true);
        if ($this->create_start_time != "") {
            $criteria->addCondition("create_time>=" . strtotime($this->create_start_time));
        }
        if ($this->create_end_time != "") {
            $criteria->addCondition("create_time<=" . strtotime($this->create_end_time . " 23:59:59"));
        }
        if ($this->last_start_time != "") {
            $criteria->addCondition('last_time>=' . strtotime($this->last_start_time));
        }
        if ($this->create_end_time != "") {
            $criteria->addCondition('last_time<=' . strtotime($this->last_end_time . " 23:59:59"));
        }
        $criteria->compare('state', $this->state);
        $criteria->compare("category_id", $this->category_id);
        $criteria->compare('score', $this->score, true);
        $criteria->compare('is_auto_chance_community', $this->is_auto_chance_community);
        $criteria->compare('desc', $this->desc, true);
        $criteria->compare('sign_type', $this->sign_type);
        return new ActiveDataProvider($this, array(
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

    /*
     * 获取注册时间格式"Y-m-d H:i :s"
     * */
    public function getCreateTime()
    {
        return date("Y-m-d H:i:s", $this->create_time);
    }


    /*
    * 获取最后登录时间格式"Y-m-d H:i :s"
    * */
    public function getLastTime()
    {
        return date("Y-m-d H:i:s", $this->last_time);
    }

    //取得商家的小区信息
    public function getShopCommunity()
    {
        Branch::model()->id = $this->branch_id;
        return Branch::model()->getBranchAllData('community');
    }

    /*
     * 获取行业列表
     * */
    public function getCategoryNames()
    {
        $model = ShopCategory::model()->findAll();
        if ($model) {
            $list = array();
            $list[""] = "全部";
            foreach ($model as $val) {
                $key = $val['id'];
                $list[$key] = $val['name'];
            }
        }
        return $list;
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

    public function getIsVip()
    {
        if ($this->is_vip) {
            return "是";
        } else {
            return "否";
        }
    }

    public function getBranchName()
    {
        return empty($this->branch) ? '' : $this->branch->name;
    }

    public function getScoreLevel()
    {
        $score = array(
            "" => "全部",
            "0" => "0",
            "1" => "1",
            "2" => "2",
            "3" => "3",
            "4" => "4",
            "5" => "5",
        );
        return $score;
    }

    public function getBranchNames()
    {
        $branchName = '';
        if (!empty($this->branch_id)) {
            $branchName = $this->branch->getMyParentBranchName($this->branch_id);
        }
        return $branchName;
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
    //商家签约类型
    public function getSignType()
    {
        return array(
            0=>'普通商家',
            1=>'VIP商家',
            2=>'特惠商家',
        );
    }

    public function getSignTypeName()
    {
        $arr =  array(
            0=>'普通商家',
            1=>'VIP商家',
            2=>'特惠商家',
        );
        return  $arr[$this->sign_type];
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
