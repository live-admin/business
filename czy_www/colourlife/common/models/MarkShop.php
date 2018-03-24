<?php

class MarkShop extends Shop
{
    public $modelName = '特约商家';

    public $columnName;

    public $lifeCateName;


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array("id,$this->columnName", 'required'),
            array("id,$this->columnName,benefit_display_order,house_display_order,educate_display_order,breakfast_display_order,
                    is_benefit,is_house,is_educate,is_breakfast,is_rabbit,rabbit_display_order", 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array("id,name,lifeCateName,", 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'branch' => array(self::BELONGS_TO, 'Branch', 'branch_id'),
            'servers' => array(self::HAS_MANY, 'Server', 'shop_id'),
            'reserve' => array(self::HAS_MANY, 'Reserve', 'shop_id'),
            'cateName' => array(self::BELONGS_TO, 'LifeCategory', 'life_cate_id'),
        );
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
            'logoFile' => 'Logo图片',
            'detailFile' => '详情图片',
            'mapFile' => '地图图片',
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
            'shopName' => '商家名称',
            'lifeCateName' => '商家类型',
        );
    }

    public function delete()
    {
        return $this->updateByPk($this->id, array($this->columnName => '0'));
    }

    public function search()
    {

        $criteria = new CDbCriteria;
        $criteria->addCondition($this->columnName . '>0');

        $employee = Employee::model()->findByPk(Yii::app()->user->id);
        //选择的组织架构ID
        if (!empty($this->branch))
            $criteria->addInCondition('branch_id', $this->branch->getChildrenIdsAndSelf());
        else //自己的组织架构的ID
            $criteria->addInCondition('branch_id', $employee->getBranchIds());

        if ($this->name != '') {
            $criteria->compare('t.name', $this->name, true);
        }

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
